<?php
namespace App\Data;
use App\Core\DB;
use \Carbon\Carbon;
use PDO;


class Tag{
    public $id;
    public function __construct($id){
        $this->id = $id;
    }

    public function get(){
        return DB::table('tags')->where('id',$this->id)->first();
    }

    static public function find($id){
        return DB::table('tags')->where('id',$id)->first();
    }

    static public function get_popular($limit){
        
        $query = "SELECT  t.*  , COUNT(DISTINCT  qt.id) as questions_count
                    FROM tags t
                    LEFT JOIN question_tags qt ON qt.question_id = t.id
                    GROUP BY t.id
                    ORDER BY questions_count DESC
                    LIMIT $limit
                    ";

        $results = DB::query($query)->fetchAll(\PDO::FETCH_ASSOC);

        return $results;
    }

    public function update($data){
        DB::table('tags')->where('id',$this->id)->update($data);
    }

    

}