<?php
namespace App\Data;
use App\Core\DB;
use PDO;



class User
{
    public $id;
    public function __construct($id)
    {
        $this->id = $id;
    }


    public function get()
    {
        return DB::table('users')->where('id', $this->id)->first();
    }

    static public function find($id)
    {
        return DB::table('users')->where('id', $id)->first();
    }

    static public function get_popular($limit)
    {

        return DB::query("SELECT * FROM users ORDER BY reputation DESC LIMIT $limit")->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function update($data)
    {
        DB::table('users')->where('id', $this->id)->update($data);
    }

    static public function getDetails(string $username)
    {

        $bindings = [];
        $userExists = DB::table('users')->where('username', $username)->first();
        if ($userExists) {

            $userData = "SELECT u.* , COUNT(DISTINCT q.id) as questions_count , COUNT(DISTINCT a.id) as answers_count  
            FROM users u 
            LEFT JOIN questions q ON u.id = q.creator_id
            LEFT JOIN answers a ON u.id = a.creator_id
            WHERE u.username  = ?
        ";
            $bindings[] = $username;

            $userQuestions = " SELECT q.* , COUNT(DISTINCT v.id) as votes_count , COUNT(DISTINCT a.id) as answers_count
                FROM questions q
                LEFT JOIN answers a ON a.question_id = q.id
                LEFT JOIN votes v ON v.question_id = q.id
                LEFT JOIN users u on q.creator_id = u.id

                WHERE u.username = ? 
                GROUP BY q.id
        ";
            $userData = DB::query($userData, $bindings)->fetchObject();
            $userQuestions = DB::query($userQuestions, [$username])->fetchAll(\PDO::FETCH_ASSOC);

            $results = [];
            $results['user'] = $userData;
            $results['questions'] = $userQuestions;
            return $results;
        }
        return null;
    }


}