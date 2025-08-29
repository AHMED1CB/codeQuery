<?php

namespace App\Controllers;
use App\Core\Request;
use App\Core\Session;
use App\Data\Tag;
use App\Data\User;
use App\Data\Question;

class RoutesController extends Controller {
    protected $user;
    protected $app;

    public function __construct(){

        if (Session::has("CQ_APP_AUTH")) {
            $id = Session::get("CQ_APP_AUTH");
            $user = User::find( $id );
            
            if ($user) {
                $this->user = $user;
            }else{
                Session::remove("CQ_APP_AUTH");
            }
            
            $this->app['user'] = $user;


        }


        // Top Questions

        
        $popularTags = Tag::get_popular(6);
 
        $this->app['contents']['popularTags'] = $popularTags;


    }

    public function mainPage() {         

        $topQuestions = Question::get_popular(3);
        $popularUsers = User::get_popular(12);

        $this->app['contents']['topQuestions'] = $topQuestions;
        $this->app['contents']['popularUsers'] = $popularUsers;


        return $this->display("main" , ['app' => $this->app]);

    }

    public function questionsPage(){
        
        $tag_filter = Request::input('t');
        $questions = Question::paginate(5 , (Request::input('p') ?? 1) , ['tag' => $tag_filter]);
       
        
        $this->app['contents']['questions'] = $questions['data'];
        
        $this->app['contents']['pages'] = $questions['pagination']['pages_count'];
       
        return $this->display('question.display' , ['app' => $this->app]);
    }

    public function tagsPage(){

        $search = Request::input('q');
        $tagsPage = Tag::paginate(20 , (Request::input('p') ?? 1) , [
            'search' => $search
        ]);

        $tags = $tagsPage['data'];
        $pages = $tagsPage['pagination']['pages_count'];

        $this->app['contents']['pages'] = $pages;
        $this->app['contents']['tags'] = $tags;

        return $this->display('tags.display' , ['app' => $this->app]);
    }

    public function usersPage(){
        return $this->display('users.display' , ['app' => $this->app]);
    }

     public function userProfile($username){
        return $this->display('users.profile' , ['app' => $this->app]);
    }

    public function askPage(){
        return $this->display('question.ask' , ['app' => $this->app]);
    }

    public function registerPage(){
        $error = Session::has('error') ?   Session::get('error') : "";
        Session::remove('error');

        return $this->display('auth.register' , ['error' => $error]);
    }

    public function loginPage(){
           $error = Session::has('error') ?   Session::get('error') : "";
            Session::remove('error');
        return $this->display('auth.login' , ['error' => $error]);
    }

}
