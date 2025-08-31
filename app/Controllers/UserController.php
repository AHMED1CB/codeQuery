<?php

namespace App\Controllers;

use App\Core\DB;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;



class UserController extends Controller
{



    public function update()
    {
        header("Content-Type: Application/json");
        $current_user_id = Session::get("CQ_APP_AUTH");

        $data = [];
        $target_keys = ['full_name', 'username', 'bio'];

        foreach ($target_keys as $key) {
            if (Request::has($key)) {
                if (strlen(Request::input($key)) < 3) {
                    return Response::display([
                        'message' => "Invalid Data",
                        "error" => "$key Must Be AtLeast 3 Letters"
                    ]);
                }
                $data[$key] = Request::input($key);

            }
        }



        if (Request::hasFile('avatar')) {

            $file = Request::file('avatar');
            $tmp = $file['tmp_name'];

            $newName = str_replace(['\\', '/'], '', uniqid('CQUSRIMG__'));
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));


            $newName .= '.' . $ext;

            if (move_uploaded_file($tmp, ('storage/images/' . $newName))) {
                $data['avatar'] = $newName;
            }
            ;

        }




        if (!empty($data)) {
            $update = DB::table('users')->where('id', (int) $current_user_id)->update($data);

            if ($update) {
                return Response::display([
                    'message' => "User Updated Successfully",

                ]);


            }

        }

        return Response::display([
            "message" => 'Nothing To Update'
        ]);





    }

}