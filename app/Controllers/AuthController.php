<?php


namespace App\Controllers;
use App\Core\Request;
use App\Core\Response;
use App\Core\DB;
use Exception;




class AuthController extends Controller
{


    public function RegisterUser()
    {
        $isValid = True;


        $data = Request::group('full_name', 'username', 'email', 'password', 'confirm_password');



        $userExists = DB::table('users')
            ->where('username', $data['username'])
            ->orWhere('email' , $data['email'])->first();


        foreach ($data as $dataItem) {
            
            if (empty(trim($dataItem))){
                $isValid = False;
            }

        }

        if ($userExists) {
            $isValid = False;
        }

        if ($data['password'] !== $data['confirm_password'] or strlen(trim($data['password'])) <  8) {
            $isValid = False;
        }



        if ($userExists or !$isValid) {
            $checkMessage = $userExists
                ? "User With This Data is Already Registerd"
                : 'Invalid Register Data';
            return Response::redirect("/auth/register?err=$checkMessage");
        }
        $schema = [
                
                'full_name' => $data['full_name'],
                'username' => $data['username'],
                'email'=> $data['email'],
                'password'=> password_hash($data['password'], PASSWORD_DEFAULT),

        ];

            $Insert = DB::table('users')->insert($schema);
           
            if ($Insert) {

                return Response::redirect('/auth/login');

            }





    }


}
