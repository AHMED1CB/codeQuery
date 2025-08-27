<?php
namespace App\Data;
use App\Core\DB;
use \Carbon\Carbon;


class Question{
    public $id;
    public function __construct($id){
        $this->id = $id;
    }

    public function get(){
        return DB::table('questions')->where('id',$this->id)->first();
    }

    static public function find($id){
        return DB::table('questions')->where('id',$id)->first();
    }

    static public function get_popular($limit){
        
        $query = "SELECT  q.* , COUNT(DISTINCT  a.id) as answers_count , COUNT(DISTINCT  v.id) as votes_count
                    FROM questions q
                    LEFT JOIN answers a ON a.question_id = q.id
                    LEFT JOIN votes  v ON v.question_id = q.id
                    GROUP BY q.id
                    ORDER BY answers_count DESC
                    LIMIT $limit
                    ";

        $results = DB::query($query)->fetchAll(\PDO::FETCH_ASSOC);

        array_map(function ($question){
            $question['creation_date'] = Carbon::make($question['created_at'])->diffForHumans();
        }, $results);

        return $results;
    }

    public function update($data){
        DB::table('questions')->where('id',$this->id)->update($data);
    }

    

}