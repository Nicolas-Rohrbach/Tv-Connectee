<?php
/**
 * Created by PhpStorm.
 * User: Rohrb
 * Date: 26/04/2019
 * Time: 10:09
 */

class ManagementUsers extends ControllerG{
    private $view;

    /**
     * Constructeur de ManagementUsers.
     */
    public function __construct(){
        $this->view = new ViewManagementUsers();
    }

    /**
     * Affiche les utilisateurs choisis
     * @param $action
     */
    public function displayUsers(){
        $action = $_POST['seeUsers'];
        if($action == "students"){
            $controller = new Student();
            $controller->displayAllStudents();
        } elseif ($action == "teachers") {
            $controller = new Teacher();
            $controller->displayAllTeachers();
        } elseif ($action == "televisions"){
            $controller = new Television();
            $controller->displayAllTv();
        } elseif ($action == "secretarys"){
            $controller = new Secretary();
            $controller->displayAllSecretary();
        } elseif ($action == "technicians") {
            $controller = new Technician();
            $controller->displayAllTechnician();
        }
    }

    /**
     * Modifie l'utilisateur choisi
     */
    public function modifyUser(){
        $user = get_user_by( 'ID', $this->getMyIdUrl() );
        if(in_array("etudiant",$user->roles)){
            $controller = new Student();
            $controller->modifyMyStudent($user);
        } elseif (in_array("enseignant",$user->roles)){
            $controller = new Teacher();
            $controller->modifyTeacher($user);
        } elseif (in_array("television",$user->roles)){
            $controller = new Television();
            $controller->modifyTv($user);
        }
    }
}