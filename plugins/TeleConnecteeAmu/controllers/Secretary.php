<?php
/**
 * Created by PhpStorm.
 * User: Rohrb
 * Date: 25/04/2019
 * Time: 11:37
 */

class Secretary{
    private $view;
    private $model;

    /**
     * Constructeur de Secretary.
     */
    public function __construct(){
        $this->view = new ViewSecretary();
        $this->model = new SecretaryManager();
    }

    public function insertSecretary(){
        $this->view->displayFormSecretary();

        $action = $_POST['createSecre'];
        $login = filter_input(INPUT_POST,'loginSecre');
        $pwd = md5(filter_input(INPUT_POST,'pwdSecre'));
        $email = filter_input(INPUT_POST,'emailSecre');

        if(isset($action)){
            if($this->model->insertMySecretary($login, $pwd, $email)){
                $this->view->displayInsertValidate();
            }
            else{
                $this->view->displayErrorInsertion();
            }
        }
    }

    public function displayAllSecretary(){
        $results = $this->model->getUsersByRole('secretaire');
        if(isset($results)){
            $this->view->displayHeaderTabSecretary();
            $row = 0;
            foreach ($results as $result){
                ++$row;
                $this->view->displayAllSecretary($row, $result['ID'], $result['user_login']);
            }
            $this->view->displayEndTab();
        }
        else{
            $this->view->displayEmpty();
        }
    }
}