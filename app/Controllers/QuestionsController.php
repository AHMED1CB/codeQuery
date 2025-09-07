<?php

namespace App\Controllers;

use App\Core\DB;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Data\Question;




class QuestionsController extends Controller
{




    public function store()
    {
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");

        $tags = Request::input("tags", False);
        $title = Request::input("title" , False);
        $desc = Request::input("desc" , False);


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

            // Update reputation of User; 
            $currentReputation = DB::table('users')->where('id', Session::get('CQ_APP_AUTH'))->first()->reputation;

            DB::table('users')->where('id', Session::get('CQ_APP_AUTH'))->update([
                'reputation' => $currentReputation + 5
            ]);


            return Response::display([
                'message' => "Question Created Successfully"
            ]);

        }


        return Response::display([
            'error' => "Something Went Wrong"
        ]);




    }


    public function vote($question)
    {
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");

        $voteType = strtoupper(Request::input("vote"));
        $userId = Session::get('CQ_APP_AUTH');

        $getTotalVotes = function () use ($question) {
            return DB::query("
            SELECT COALESCE(SUM(
                CASE 
                    WHEN type = 'UP' THEN 1 
                    WHEN type = 'DOWN' THEN -1 
                    ELSE 0 
                END
            ), 0) AS total_votes
            FROM votes
            WHERE question_id = ?
        ", [$question])->fetchObject()->total_votes;
        };

        $vote = DB::table('votes')
            ->where("question_id", $question)
            ->where('user_id', $userId)
            ->first();

        if ($vote) {
            if ($vote->type !== $voteType) {
                DB::table('votes')
                    ->where("question_id", $question)
                    ->where('user_id', $userId)
                    ->update(['type' => $voteType]);

                return Response::display([
                    'message' => "Vote Changed Successfully",
                    'currentVote' => $voteType,
                    'totalVotes' => $getTotalVotes()
                ]);
            }

            DB::table('votes')
                ->where("question_id", $question)
                ->where('user_id', $userId)
                ->delete();

            return Response::display([
                'message' => "Vote Deleted Successfully",
                'currentVote' => null,
                'totalVotes' => $getTotalVotes()
            ]);
        }

        DB::table('votes')->insert([
            "question_id" => $question,
            "user_id" => $userId,
            "type" => $voteType
        ]);

        return Response::display([
            'message' => "Vote Created Successfully",
            'currentVote' => $voteType,
            'totalVotes' => $getTotalVotes()
        ]);
    }


    public function answer($question)
    {

        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");


        $answerContent = Request::input('answer');

        // Store The Answer

        DB::table("answers")->insert([
            "creator_id" => Session::get("CQ_APP_AUTH"),
            "question_id" => $question,
            "answer" => $answerContent 
        ]);


        return Response::display([
            "message" => "Answer Created Successfully"
        ]);



    }



}
