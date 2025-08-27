<?php
namespace App\Data;
use App\Core\DB;



class User{
    public $id;
    public function __construct($id){
        $this->id = $id;
    }


    public function get(){
        return DB::table('users')->where('id',$this->id)->first();
    }

    static public function find($id){
        return DB::table('users')->where('id',$id)->first();
    }

    static public function get_popular($limit){
        
        return DB::query("SELECT * FROM users ORDER BY reputation DESC LIMIT $limit")->fetchAll(\PDO::FETCH_ASSOC);
    
    }

    public function update($data){
        DB::table('users')->where('id',$this->id)->update($data);
    }

    

}