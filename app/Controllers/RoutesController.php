<?php

namespace App\Controllers;
use App\Core\DB;




class RoutesController extends Controller {


    public function mainPage() {         

        return $this->display("main");

    }

}