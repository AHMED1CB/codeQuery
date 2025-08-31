<?php


namespace App\Controllers;
use App\Core\Request;
use App\Core\Response;
use App\Core\DB;
use App\Core\Session;




class AuthController extends Controller
{


    public function RegisterUser()
    {
        $isValid = True;


        $data = Request::group('full_name', 'username', 'email', 'password', 'confirm_password');



        $userExists = DB::table('users')
            ->where('username', $data['username'])
            ->orWhere('email', $data['email'])->first();


        foreach ($data as $dataItem) {

            if (empty(trim($dataItem))) {
                $isValid = False;
            }

        }

        if ($userExists) {
            $isValid = False;
        }

        if ($data['password'] !== $data['confirm_password'] or strlen(trim($data['password'])) < 8) {
            $isValid = False;
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
            $isValid = False;
        }



        if ($userExists or !$isValid) {
            $checkMessage = $userExists
                ? "User With This Data is Already Registerd"
                : 'Invalid Register Data';
            Session::set('error', $checkMessage);
            return Response::redirect("/auth/register");
        }
        $schema = [

            'full_name' => $data['full_name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),

        ];

        $Insert = DB::table('users')->insert($schema);

        if ($Insert) {

            return Response::redirect('/auth/login');

        }





    }

    public function LoginUser(){

        $username = Request::input('username');
        $password = Request::input('password');

        if (!trim($username) or !trim($password) or strlen($password) < 8) {
            Session::set('error','Invalid User Data');
            return Response::redirect('/auth/login');
        }

        $user = DB::table('users')->where('username', $username)->first();
        $validation = password_verify($password , $user->password);


        if (!$user or !$validation) {
            Session::set('error','Invalid User Data ');
            return Response::redirect('/auth/login');
        }

        Session::set('CQ_APP_AUTH', $user->id);

        return Response::redirect('/');



    }


    public function logout(){
        Session::remove("CQ_APP_AUTH");
        return Response::redirect("/");
    }

}
