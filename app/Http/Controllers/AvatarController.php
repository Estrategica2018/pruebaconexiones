<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class AvatarController
 * @package App\Http\Controllers
 */
class AvatarController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $request->user('afiliadoempresa')->authorizeRoles(['student']);
        return view('roles.student.avatar');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_avatar(Request $request)
    {
        $request->user('afiliadoempresa')->authorizeRoles(['student']);
        $user = $request->user('afiliadoempresa');
        
        if (isset($request->url_image)) {
            $user->url_image = $request->url_image;
            $user->save();
        } elseif (isset($request->custom_image)) {
            
            //busca el archivo de avatar anterior para eliminarlo
            $directory = env('ADMIN_DESIGN_PATH') . '/../../images/users_images/';
            $scan = scandir($directory);
            
            foreach($scan as $file) {
               if (!is_dir($directory.$file)) {
                  if (stristr($file, 'student_'.$user->id)) {
                    unlink($directory.$file);
                  }
               }
            }
            $img_file = "/images/users_images/student_" . $user->id  . '_' . $this->random_string(5)  . ".jpeg";
            
            //Se guarda la imagen como jpeg, ya que como png, el fondo transparente queda en negro.
            $b64 = str_replace("data:image/jpeg;base64", "", $request->custom_image);// Define the Base64
            $bin = base64_decode($b64);// Obtain the original content (usually binary data)
            $im = imageCreateFromString($bin);// Load GD resource from binary data
            if (!$im) { // Validate that the GD library was able to load the image
                die('Base64 value is not a valid image'); // This is important, because you should not miss corrupted or unsupported images
            } 
            
            $directory = env('ADMIN_DESIGN_PATH') . '/../..' .  $img_file;
            imagepng($im, $directory , 0); // Save the GD resource as PNG in the best possible quality (no compression)

            $user->url_image = $img_file;
            $user->save(); 
        }
        return redirect()->route('student.available_sequences', ['empresa' => $request->empresa, 'success' => 'Avatar modificado exitosamente']);
    }


    /**
     * @param $length
     * @return string
     */
    function random_string($length)
    {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }

}
