<?php

namespace App\Controllers;
use App\Core\DB;




class RoutesController extends Controller {


    public function mainPage() {         

        return $this->display("main");

    }

    public function questionsPage(){
        return $this->display('question.display');
    }

    public function tagsPage(){
        return $this->display('tags.display');
    }

    public function usersPage(){
        return $this->display('users.display');
    }

     public function userProfile($username){
        return $this->display('users.profile' , ['username'=> $username]);
    }

    public function askPage(){
        return $this->display('question.ask');
    }

    public function registerPage(){
        return $this->display('auth.register');
    }

    public function loginPage(){
        return $this->display('auth.login');
    }

}
