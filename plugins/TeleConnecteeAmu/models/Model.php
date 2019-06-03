<?php
/**
 * Created by PhpStorm.
 * User: Rohrb
 * Date: 25/03/2019
 * Time: 18:10
 */

abstract class Model
{
    private static $db;

    /**
     * Set the db with PDO
     */
    private static function setDb(){
        self::$db = new PDO('mysql:host='.DB_HOST.'; dbname='.DB_NAME, DB_USER, DB_PASSWORD);
        self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }

    /**
     * Return the db
     * @return mixed
     */
    protected function getDb(){
        if (self:: $db == null)
            self::setDb();
        return self::$db;
    }

    protected function getAll($table){
        $var = [];
        $req = $this->getDb()->prepare('SELECT * FROM ' . $table . ' ORDER BY ID desc');
        $req->execute();
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
            $var[] = $data;
        }
        return $var;
        $req->closeCursor();
    }

    protected function verifyTuple($login){
        $var = 0;
        $req = $this->getDb()->prepare('SELECT * FROM wp_users WHERE user_login =:login');
        $req->bindValue(':login', $login);
        $req->execute();
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
            $var = $var + 1;
        }
        if ($var > 0) {
            return true;
        } else {
            return false;
        }
        $req->closeCursor();
    }

    protected function verifyNoDouble($email, $login){
        $var = 0;
        $req = $this->getDb()->prepare('SELECT * FROM wp_users WHERE user_email =:mail OR user_login =:login');
        $req->bindValue(':mail', $email);
        $req->bindValue(':login', $login);
        $req->execute();
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
            $var = $var + 1;
        }
        if ($var > 0) {
            return false;
        } else {
            return true;
        }
        $req->closeCursor();
    }

    protected function insertUser($login, $pwd, $role, $email, $code = array()){
        if ($this->verifyNoDouble($email, $login)){
            $req = $this->getDb()->prepare('INSERT INTO wp_users (user_login, user_pass, role, code,
                                      user_nicename, user_email, user_url, user_registered, user_activation_key,
                                      user_status, display_name) 
                                         VALUES (:login, :pwd, :role, :code, :name, :email, :url, NOW(), :key, :status, :displayname)');

            $nul = " ";
            $zero = "0";

            $serCode = serialize($code);
            $req->bindParam(':login', $login);
            $req->bindParam(':pwd', $pwd);
            $req->bindParam(':role', $role);
            $req->bindParam(':code', $serCode);
            $req->bindParam(':name', $login);
            $req->bindParam(':email', $email);
            $req->bindParam(':url', $nul);
            $req->bindParam(':key', $nul);
            $req->bindParam(':status', $zero);
            $req->bindParam(':displayname', $login);

            $req->execute();

            $capa = 'wp_capabilities';
            $role = 'a:1:{s:10:"'.$role.'";b:1;}';

            $id = $this->getDb()->lastInsertId();

            $req = $this->getDb()->prepare('INSERT INTO wp_usermeta(user_id, meta_key, meta_value) VALUES (:id, :capabilities, :role)');

            $req->bindParam(':id', $id);
            $req->bindParam(':capabilities', $capa);
            $req->bindParam(':role', $role);

            $req->execute();

            $level = "wp_user_level";

            $req = $this->getDb()->prepare('INSERT INTO wp_usermeta(user_id, meta_key, meta_value) VALUES (:id, :level, :value)');

            $req->bindParam(':id', $id);
            $req->bindParam(':level', $level);
            $req->bindParam(':value', $zero);

            $req->execute();

            return true;
        }
        else{
            return false;
        }
    }

    protected function modifyUser($id, $login, $pwd, $email, $codes){
        if ($this->verifyTuple($login)) {
            $req = $this->getDb()->prepare('UPDATE wp_users SET user_login=:login, user_pass=:pwd, code=:codes, user_nicename=:name, 
                                            user_email=:email, display_name=:displayname WHERE ID=:id');

            $serCode = serialize($codes);
            $req->bindParam(':id', $id);
            $req->bindParam(':login', $login);
            $req->bindParam(':pwd', $pwd);
            $req->bindParam(':codes', $serCode);
            $req->bindParam(':name', $login);
            $req->bindParam(':email', $email);
            $req->bindParam(':displayname', $login);

            $req->execute();

            return true;
        }
        else {
            return false;
        }
    }

    public function getUsersByRole($role){
        $req = $this->getDb()->prepare('SELECT * FROM wp_users WHERE role = :role');
        $req->bindParam(':role', $role);
        $req->execute();
        while ($data = $req->fetch()) {
            $var[] = $data;
        }
        return $var;
        $req->closeCursor();
    }

    public function getTitleOfCode($code){
        $req = $this->getDb()->prepare('SELECT title FROM code_ade WHERE code = :code');
        $req->bindParam(':code', $code);
        $req->execute();
        while ($data = $req->fetch()) {
            $var[] = $data;
        }
        return $var;
        $req->closeCursor();
    }

    public function getCodeYear(){
        $req = $this->getDb()->prepare('SELECT * FROM code_ade WHERE type = "Annee"');
        $req->execute();
        while ($data = $req->fetch()) {
            $var[] = $data;
        }
        return $var;
        $req->closeCursor();
    }

    public function getCodeGroup(){
        $req = $this->getDb()->prepare('SELECT * FROM code_ade WHERE type = "Groupe"');
        $req->execute();
        while ($data = $req->fetch()) {
            $var[] = $data;
        }
        return $var;
        $req->closeCursor();
    }

    /**
     * Return all code from Halfgroup
     * @return array
     */
    public function getCodeHalfgroup(){
        $req = $this->getDb()->prepare('SELECT * FROM code_ade WHERE type = "Demi-Groupe"');
        $req->execute();
        while ($data = $req->fetch()) {
            $var[] = $data;
        }
        return $var;
        $req->closeCursor();
    }

    /**
     * If the code has not a title, return the code
     * @param $code
     * @return mixed
     */
    public function getTitle($code){
        $var = $this->getTitleOfCode($code);
        if(! isset($var[0]['title']))  $var[0]['title'] = $code;
        return $var[0]['title'];
    }

    /**
     * Delete a row from a table due to the id
     * @param $table
     * @param $id
     */
    protected function deleteTuple($table, $id){

        $req = $this->getDb()->prepare('DELETE FROM '.$table.' WHERE ID = :id');
        $req->bindValue(':id', $id);

        $req->execute();
    }

    /**
     * Supprime un utilisateur
     * @param $id
     */
    public function deleteUser($id){
        $this->deleteTuple('wp_users', $id);
        $req = $this->getDb()->prepare('DELETE FROM wp_usermeta WHERE user_id = :id');
        $req->bindValue(':id', $id);

        $req->execute();
    }

    /**
     * Get a user due to his id
     * @param $id
     * @return mixed
     */
    public function getById($id){
        global $wpdb;
        $result = $wpdb->get_row('SELECT * FROM `wp_users` WHERE `ID` ="' . $id . '"', ARRAY_A);
        return $result;
    }

    protected function getAllCodeFromUser($type = null){
        $req = $this->getDb()->prepare('SELECT code FROM wp_users WHERE role =:role');
        $role = "etudiant";
        $req->bindParam(':role',$role);
        $req->execute();
        $var = array();
        while ($data = $req->fetch()) {
            $var[] = $data;
        }
        $req->closeCursor();
        foreach ($var as $value){
            $unserCodes = unserialize($value['code']);
            $userCodes[] = $unserCodes[$type];
        }
        return $userCodes;
    }

    /**
     * Renvoie tout les code ADE qui n'ont pas été enregistré dans la bd code_ade mais enregistré dans les utilisateurs
     * @return array
     */
    public function codeNotBound($type = null){
        $userCodes = $this->getAllCodeFromUser($type);
        $codesAde = $this->getAll('code_ade');
        $allCode = array();
        $notRegisterCode = array();
        if(isset($codesAde)){
            foreach ($codesAde as $codeAde){
                $allCode[] = $codeAde['code'];
            }
        }
        if(isset($userCodes)){
            foreach ($userCodes as $userCode){
                if(! in_array($userCode, $allCode))
                    $notRegisterCode[] = $userCode;
            }
        }
        if(empty($notRegisterCode)) {
            return null;
        } else {
            return $notRegisterCode;
        }
    }
}