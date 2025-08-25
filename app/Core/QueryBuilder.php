<?php

namespace App\Core;
use App\Database\Connection;

class QueryBuilder
{

    protected $table = "";
    protected $query = '';
    protected $bindings = [];

    protected $set = [];
    protected $limt = null;

    protected $conditions = [];
    protected $connection;



    public function __construct($table)
    {
        $this->table = $table;
        $this->connection = new Connection()->connection;
    }


    public function select($cols = [])
    {

        if (is_array($cols)) {

            $cols = array_map(function ($col) {
                $col = " `$col` ";
                return $col;
            }, $cols);

            $cols = implode(",", $cols);
        }



        $this->query = "SELECT $cols ";
        return $this;
    }

    public function delete()
    {
        $this->query = "DELETE";


        return $this->runQuery($this->completeduery(), $this->bindings);
    }


    public function update($data)
    {
        $this->query = "UPDATE ";


        foreach ($data as $key => $value) {

            $this->set[] = " `{$key}` = ? ";
            $this->bindings[] = $value;

        }


        return $this->runQuery($this->completeduery(), $this->bindings);
    }

    public function where($column, $value, $operator = "=")
    {
        $this->conditions[] = " `{$column}` {$operator} ?";

        $this->bindings[] = $value;
        return $this;
    }


    public function get()
    {

        if (!str_starts_with($this->query, 'select')) {
            $this->select('*');
        }
        return $this->runQuery($this->completeduery(), $this->bindings)->fetchObject();
    }


    public function first()
    {


        if (!str_starts_with($this->query, 'select')) {
            $this->select('*');
        }

        $this->limt = 1;

        return $this->runQuery($this->completeduery(), $this->bindings)->fetchObject();

    }



    public function insert($data)   
    {

        $this->query = " INSERT INTO ";

        $cols = array_keys($data);

        $placeholders = implode(", ", array_fill(0, count($cols), "?"));

        $columnsList = implode("`, `", $cols);

        $this->query = "INSERT INTO `{$this->table}` (`{$columnsList}`) VALUES ({$placeholders})";

        $this->bindings = array_values($data);
        return $this->runQuery($this->completeduery(), $this->bindings);

    }


    protected function completeduery(): string
    {

        $query = $this->query;


        if (str_starts_with(strtoupper($query), 'SELECT') or str_starts_with(strtoupper($query), 'DELETE')) {
            $query .= " FROM `{$this->table}`";
        } elseif (str_starts_with(strtoupper($query), 'UPDATE')) {
            $query .= " `{$this->table}` SET " . implode(', ', $this->set);
        }

        if (!empty($this->conditions)) {

            $query .= " WHERE " . implode(' AND ', $this->conditions);
        }




        if (is_numeric($this->limt)) {

            $query .= " LIMIT {$this->limt}";

        }

        return $query;


    }


    public function runQuery($query, $bindings = []): \PDOStatement
    {



        $run = $this->connection->prepare($query);

        $run->execute($bindings);
        return $run;

    }



    public function sql(): string
    {
        return $this->completeduery();
    }





}


