<?php

namespace App\Controllers;




class QuestionsController extends Controller {


    public function showQuestion($id){
        return $this->display('question.show');

    }

    
}
