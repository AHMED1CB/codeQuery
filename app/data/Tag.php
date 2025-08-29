<?php
namespace App\Data;
use App\Core\DB;
use \Carbon\Carbon;
use PDO;


class Tag
{
    public $id;
    public function __construct($id)
    {
        $this->id = $id;
    }

    public function get()
    {
        return DB::table('tags')->where('id', $this->id)->first();
    }

    static public function find($id)
    {
        return DB::table('tags')->where('id', $id)->first();
    }

    static public function get_popular($limit)
    {

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

    static public function paginate($items_per_page, $page = 1 , $options = [])
    {

        $binds = [];
        $filter = "";
        $offset = max((int) $page - 1, 0) * $items_per_page;
        
        
        if (!empty($options["search"])) {

            $filter = " WHERE t.name LIKE ?";
            $binds[] = '%' .$options['search'] . "%"; 
        }

        $binds[] = $items_per_page;
        $binds[] = $offset; 
        $query = "SELECT t.* , COUNT(DISTINCT q.id) as questions_count FROM tags t
        LEFT JOIN question_tags qt ON t.id = qt.tag_id
        LEFT JOIN questions q ON qt.question_id = q.id
        $filter
        GROUP BY t.id
        LIMIT ? 
        OFFSET ?";


        $tags = DB::query($query , $binds)->fetchAll(\PDO::FETCH_ASSOC);

        $tags_count = DB::query("SELECT COUNT(*) FROM tags")->fetchColumn();
        
        $pages_count = ceil($tags_count / $items_per_page);

        return [
            'data' => $tags,
            'pagination' => [
                'pages_count' => $pages_count
            ]
        ];


    }

    public function update($data)
    {
        DB::table('tags')->where('id', $this->id)->update($data);
    }



}