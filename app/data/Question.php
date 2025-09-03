<?php
namespace App\Data;
use App\Core\DB;
use \Carbon\Carbon;


class Question
{
    public $id;
    public function __construct($id)
    {
        $this->id = $id;
    }

    public function get()
    {
        return DB::table('questions')->where('id', $this->id)->first();
    }

    static public function find($id)
    {
        return DB::table('questions')->where('id', $id)->first();
    }

    static public function get_popular($limit)
    {

        $query = "SELECT  q.* , COUNT(DISTINCT  a.id) as answers_count , COUNT(DISTINCT  v.id) as votes_count
                    FROM questions q
                    LEFT JOIN answers a ON a.question_id = q.id
                    LEFT JOIN votes  v ON v.question_id = q.id
                    GROUP BY q.id
                    ORDER BY answers_count DESC
                    LIMIT $limit
                    ";

        $results = DB::query($query)->fetchAll(\PDO::FETCH_ASSOC);

        $results = array_map(function ($question) {
            $question['creation_date'] = Carbon::make($question['created_at'])->diffForHumans();
            return $question;
        }, $results);

        return $results;
    }

    public function update($data)
    {
        DB::table('questions')->where('id', $this->id)->update($data);
    }

    static public function paginate($items_per_page, $page = 1, $options = [])
    {

        // User Is Able to use one filter
        $offset = max((int) $page - 1, 0) * $items_per_page;

        $binds = [];

        $filter = "";
        if (!empty($options['tag'])) {
            $filter = "WHERE t.name = ?";
            $binds[] = $options['tag'];
        }
        $binds[] = $items_per_page;
        $binds[] = $offset;


        $query = "SELECT q.* , q.id , u.username as creator_name , t.name as main_tag,
                  COUNT(DISTINCT a.id) as answers_count ,
                  COUNT(DISTINCT v.id) as votes_count
            FROM questions q
            LEFT JOIN users u ON q.creator_id = u.id
            LEFT JOIN answers a ON a.question_id = q.id
            LEFT JOIN votes v ON v.question_id = q.id
            LEFT JOIN question_tags qt ON qt.question_id = q.id
            LEFT JOIN tags t ON t.id = qt.tag_id
            $filter
            GROUP BY q.id
            ORDER BY answers_count DESC
            LIMIT ?
            OFFSET ?  
        ";




        $questions = DB::query($query, $binds)->fetchAll(\PDO::FETCH_ASSOC);



        foreach ($questions as &$question) {

            $question["creation_date"] = Carbon::make($question['created_at'])->diffForHumans();

        }

        $count_query = "SELECT COUNT(DISTINCT q.id)
                FROM questions q
                LEFT JOIN question_tags qt ON qt.question_id = q.id
                LEFT JOIN tags t ON t.id = qt.tag_id";

        $count_binds = [];
        
        if (!empty($options['tag'])) {
            $count_query .= " WHERE t.name = ?";
            $count_binds[] = $options['tag'];
        }

        $count_questions = DB::query($count_query, $count_binds)->fetchColumn();

        $total_pages = ceil($count_questions / $items_per_page);



        return [
            'data' => $questions,
            'pagination' => [
                'pages_count' => $total_pages,
            ]
        ];



    }



}