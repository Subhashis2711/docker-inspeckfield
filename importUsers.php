<?php

require 'autoload.php';

$database['host']       = '172.16.238.10'; 
$database['user']       = 'root';
$database['password']   = 'mindfire';
$database['database']   = 'inspektech2';

$obj = DB::getInstance($database);

$users = $obj->table('user as u')
                ->select(['u.user_id', 'u.fullName', 'u.login', 'u.password'])
                ->join('user_group as ug', 'u.user_id', '=', 'ug.user_id')
                ->where('group_id', '3')
                ->orderBy('created_date', 'desc')
                ->get();


$emails = $obj->table('user_user_attribute as uua')
                ->select(['uua.value as email', 'ug.user_id'])
                ->join('user_group as ug', 'uua.user_id', '=', 'ug.user_id')
                ->where('uua.user_attribute_id', '1')
                ->where('ug.group_id', '3')
                ->get();

$user_datas = array();

foreach($users as $user){
    foreach($emails as $email){
        if($email['user_id'] == $user['user_id']){
            array_push($user_datas, array(
                'user_id' => $user['user_id'],
                'email' => $email['email'],
                'full_name' => $user['fullName'],
                'username' => $user['login'],
                'password' => $user['password']
            )); 
        }
    }
}

DB::closePDOConnection();

$obj = DB::getInstance();

// echo '<pre>';
// print_r($user_datas);


foreach($user_datas as $user){

    if(!empty($user['user_id'])){
        $user_obj = $obj->table('user')->select()
                        ->where('id', $user['user_id'])
                        ->one();
    }
    if($user_obj){
        $obj->table('user')->update()
                ->set([
                    'id' => (!empty($user['user_id']))? $user['user_id']: '',   
                    'full_name' => (!empty($user['full_name']))? $user['full_name']: '',
                    'username' => (!empty($user['username']))? $user['username']: '',
                    'password' => (!empty($user['password']))? $user['password']: '',
                    'email' => (!empty($user['email']))? $user['email']: '',
                    'user_type' => '2'
                ])->where('id', $user_obj['id'])->execute();
    } else {
        $obj->table('user')
                ->insert([
                    'id' => (!empty($user['user_id']))? $user['user_id']: '',   
                    'full_name' => (!empty($user['full_name']))? $user['full_name']: '',
                    'username' => (!empty($user['username']))? $user['username']: '',
                    'password' => (!empty($user['password']))? $user['password']: '',
                    'email' => (!empty($user['email']))? $user['email']: '',
                    'user_type' => '2'
                ])->execute();
    }
}



echo "Success";

?>