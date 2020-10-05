<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class GoogleDriveServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        \Storage::extend('google', function($app, $config) {
            $client = new \Google_Client();
            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
            $client->refreshToken($config['refreshToken']);
            $service = new \Google_Service_Drive($client);

            $options = [];
            if(isset($config['teamDriveId'])) {
                $options['teamDriveId'] = $config['teamDriveId'];
            }
            
            /*$tokenPath = public_path() .'/backups/token.json';
            if (file_exists($tokenPath)) {
                $accessToken = json_decode(file_get_contents($tokenPath), true);
                $client->setAccessToken($accessToken);
            }
            
            // If there is no previous token or it's expired.
            if ($client->isAccessTokenExpired()) {
                // Refresh the token if possible, else fetch a new one.
                if ($client->getRefreshToken()) {
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                } else {
                    // Request authorization from the user.
                    $authUrl = $client->createAuthUrl();
                    printf("Open the following link in your browser:\n%s\n", $authUrl);
                    print 'Enter verification code: ';
                    $authCode = trim(fgets(STDIN));

                    // Exchange authorization code for an access token.
                    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                    $client->setAccessToken($accessToken);

                    // Check to see if there was an error.
                    if (array_key_exists('error', $accessToken)) {
                        throw new Exception(join(', ', $accessToken));
                    }
                }
                // Save the token to a file.
                if (!file_exists(dirname($tokenPath))) {
                    mkdir(dirname($tokenPath), 0700, true);
                }
                
                file_put_contents($tokenPath, json_encode($client->getAccessToken()));
            }
            else {
                file_put_contents($tokenPath, json_encode($client->getAccessToken()));
            }*/
            
            $adapter = new GoogleDriveAdapter($service, $config['folderId'], $options);
            
            // Valida si el folder backup del día existe
            $folderName = date('Ymd');
            $filesystem = new \League\Flysystem\Filesystem($adapter);
            $contents = collect($filesystem->listContents('/', false));
            $dir = $contents->where('type', '=', 'dir')
                ->where('filename', '=', $folderName)
                ->first(); // There could be duplicate directory names!
            // Si no existe lo crea
            if (!$dir) {
                $fileMetadata = new \Google_Service_Drive_DriveFile(
                    array('name' => $folderName,'mimeType' => 'application/vnd.google-apps.folder','parents' => array($config['folderId']))
                );
                $file = $service->files->create($fileMetadata,
                    array( 'mimeType' => 'application/vnd.google-apps.folder', 'uploadType' => 'multipart', 'fields' => 'id')
                );
            }
            
            //Borra ficheros anteriores
            $oldFolderName = Carbon::now()->subDays(2)->format('Ymd');
            $contents = collect($filesystem->listContents('/', false));
            $dir = $contents->where('type', '=', 'dir')
                ->where('filename', '=', $oldFolderName)
                ->first(); // There could be duplicate directory names!
            if ($dir) {
                $file = $service->files->delete($dir['path']);
            }
            return $filesystem;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
