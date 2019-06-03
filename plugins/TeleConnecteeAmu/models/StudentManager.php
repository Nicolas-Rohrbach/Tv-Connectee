<?php
/**
 * Created by PhpStorm.
 * User: Rohrb
 * Date: 25/04/2019
 * Time: 10:02
 */

class StudentManager extends Model{

    public function insertStudent($login, $pwd, $email, $code){
        $role = "etudiant";
        return $this->insertUser($login, $pwd, $role, $email, $code);
    }

    public function modifyStudent($id, $code){
        $result = $this->getById($id);
        return $this->modifyUser($id, $result['user_login'], $result['user_pass'], $result['user_email'], $code);
    }
}