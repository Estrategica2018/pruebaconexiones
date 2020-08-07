<?php

use Illuminate\Database\Seeder;

class DefaultUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $users = [
            ['rol_id'=>[4],'user_name'=>'CristianJojoa','nombre'=>'Cristian','apellido'=>'Jojoa','correo'=>'cristianjojoa01@gmail.com','password'=>'cristianjojoa01','empresa_id'=>1],
            ['rol_id'=>[4],'user_name'=>'HenryGarzon','nombre'=>'Henry ','apellido'=>'Garzon','correo'=>'hsgarzon2020@gmail.com','password'=>'hsgarzon2020','empresa_id'=>1],
            ['rol_id'=>[4],'user_name'=>'DavidCamacho','nombre'=>'David','apellido'=>'Camacho','correo'=>'davithc01@gmail.com','password'=>'davithc01','empresa_id'=>1,],
            //['rol_id'=>[3,4],'user_name'=>'JairoContreras','nombre'=>'Jairo','apellido'=>'Contreras','correo'=>'jairocontrerasch@gmail.com','password'=>'jairocontrerasch','empresa_id'=>1,],
            //['rol_id'=>[3,4],'user_name'=>'JordyChimbí','nombre'=>'Jordy','apellido'=>'Chimbí','correo'=>'jordychimbi@gmail.com','password'=>'jordychimbi','empresa_id'=>1,],
            //['rol_id'=>[3,4],'user_name'=>'CatalinaAlbeláez','nombre'=>'Catalina','apellido'=>'Albeláez','correo'=>'luciacatalina@gmail.com','password'=>'luciacatalina','empresa_id'=>1,],
            //['rol_id'=>[3,4],'user_name'=>'YessicaPinzón','nombre'=>'Yessica','apellido'=>'Pinzón','correo'=>'jesspinzong@gmail.com','password'=>'jesspinzong','empresa_id'=>1,],

        ];

        foreach ($users as $user){

            $userN = new \App\Models\AfiliadoEmpresa();
            $userN->user_name = $user['user_name'];
            $userN->name = $user['nombre'];
            $userN->last_name = $user['apellido'];
            $userN->email = $user['correo'];
            $userN->password = \Illuminate\Support\Facades\Hash::make($user['password']);
            $userN->save();
            foreach ($user['rol_id'] as $rol){
                $affiliatedCompanyRoles = new \App\Models\AffiliatedCompanyRole();
                $affiliatedCompanyRoles->affiliated_company_id = $userN->id;
                $affiliatedCompanyRoles->rol_id = $rol;
                $affiliatedCompanyRoles->company_id = $user['empresa_id'];
                $affiliatedCompanyRoles->save();

            }

/*
            $affiliatedCompanyRoles = new \App\Models\AffiliatedCompanyRole();
            $affiliatedCompanyRoles->affiliated_company_id = $userN->id;
            $affiliatedCompanyRoles->rol_id = 4;
            $affiliatedCompanyRoles->company_id = $user['empresa_id'];
            $affiliatedCompanyRoles->save();

*/
        }
    }
}
