<?php

namespace App\Controllers;

use App\Core\DB;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;




class QuestionsController extends Controller
{


    public function showQuestion($id)
    {
        return $this->display('question.show');

    }

    public function store()
    {
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");


        $tags = Request::input("tags", False);
        $title = Request::input("title");
        $desc = Request::input("desc");


        // Insert Tags If Not Exists
        $question_tags = [];

        $tags = array_map(function ($tag) {
            $tag = trim(strtolower($tag));
            return $tag;
        }, $tags);


        foreach (array_unique($tags) as $tag) {


            $tagExists = DB::table("tags")->where('name', $tag)->first();

            if (!$tagExists) {

                DB::table('tags')->insert([
                    'name' => $tag,
                ]);


            }
            $question_tags[] = DB::table('tags')->where('name', $tag)->first()->id;
        }


        $insertQuestion = DB::table('questions')->insert([
            'title' => $title,
            'description' => $desc,
            'creator_id' => Session::get('CQ_APP_AUTH'),
        ]);


        if ($insertQuestion) {

            $questionId = DB::table('questions')->where('title', $title)->where('description', $desc)
                ->where('creator_id', Session::get('CQ_APP_AUTH'))
                ->first()->id;

            foreach ($question_tags as $questionTag) {

                DB::table('question_tags')->insert([
                    'question_id' => $questionId,
                    'tag_id' => $questionTag
                ]);

            }

            return Response::display([
                'message' => "Question Created Successfully"
            ]);

        }


        return Response::display([
            'error' => "Something Went Wrong"
        ]);




    }


}
