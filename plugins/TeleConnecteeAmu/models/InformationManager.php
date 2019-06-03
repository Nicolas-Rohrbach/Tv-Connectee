<?php
/**
 * Created by PhpStorm.
 * User: Léa Arnaud
 * Date: 17/04/2019
 * Time: 11:34
 */


class InformationManager
{

    /**
     * InformationManager constructor.
     */
    public function __construct(){
    }


    /**
     * Correspond to the database.
     * @var
     */
    private static $db;

    /**
     * Set the database with wordpress.
     */
    private static function setDb(){
        global $wpdb;
        self::$db = new PDO('mysql:host='.$wpdb->dbhost.'; dbname='.$wpdb->dbname, $wpdb->dbuser, $wpdb->dbpassword);
        self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    } //setDb()

    /**
     * Return the database.
     * @return mixed
     */
    protected function getDb(){
        if (self:: $db == null)
            self::setDb();
        return self::$db;
    }//getDb()

    /**
     * Add the information in the database with today date and current user.
     * @param $title
     * @param $content
     * @param $endDate
     * @param $type
     * @return
     */
    public function addInformationDB($title, $content, $endDate, $type){
        global $wpdb;
        $current_user = wp_get_current_user();

        if (isset($current_user)) {
            $user = $current_user->user_login;
        }
        $creationDate = date('Y-m-d');
        $null = null;

        $wpdb->query($wpdb->prepare("INSERT INTO informations (`ID_info`, `title`, `author`, `creation_date`, `end_date`, `content`, `type`) 
                                         VALUES (%d, %s, %s, %s, %s, %s, %s)",
            null, $title, $user, $creationDate, $endDate, $content, $type));


        return $wpdb->insert_id;

    } //addInformationDB()

    /**
     * Delete an information in the database
     * @param $id
     */
    public function deleteInformationDB($id){
        global $wpdb;
        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM `informations` WHERE ID_info = %d",
                $id
            )
        );
    } //deleteInformationDB()

    /**
     * Return the list of information present in database
     * @return array|null|object
     */
    public function getListInformation()
    {
        global $wpdb;
        $result = $wpdb->get_results("SELECT * FROM informations", ARRAY_A);
        return $result;
    } //getListInformation()

    /**
     * Return the list of information created by an user
     * @param $user
     * @return array|null|object
     */
    public function getListInformationByAuthor($user)
    {
        global $wpdb;
        $result = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM informations WHERE author = %s",
                $user
            ), ARRAY_A
        );
        return $result;
    } //getListInformationByAuthor()

    /**
     * Return an information corresponding to the ID
     * @param $id
     * @return mixed
     */
    public function getInformationByID($id) {
        global $wpdb;
        $result = $wpdb->get_row('SELECT * FROM informations WHERE ID_info ="'.$id.'"',ARRAY_A );
        return $result;
    } //getInformationByID()

    /**
     * Modify the information in database
     * @param $id
     * @param $title
     * @param $content
     * @param $endDate
     */
    public function modifyInformation($id, $title, $content, $endDate){
        $req = $this->getDb()->prepare('UPDATE informations SET title=:title, content=:content, end_date=:endDate
                                         WHERE ID_info=:id');
        $req->bindParam(':id',$id);
        $req->bindParam(':title',$title);
        $req->bindParam(':content',$content);
        $req->bindParam(':endDate',$endDate);

        $req->execute();

    } //modifyInformation()


}