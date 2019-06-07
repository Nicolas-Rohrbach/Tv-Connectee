<?php


class Technician extends ControllerG
{
    private $view;
    private $model;

    /**
     * Constructeur de Secretary.
     */
    public function __construct(){
        $this->view = new ViewTechnician();
        $this->model = new TechnicianManager();
    }

    public function insertTechnician(){
        $this->view->displayFormTechnician();
        $action = $_POST['createTech'];
        $login = filter_input(INPUT_POST,'loginTech');
        $pwd = md5(filter_input(INPUT_POST,'pwdTech'));
        $email = filter_input(INPUT_POST,'emailTech');

        if(isset($action)){
            if($this->model->insertMyTechnician($login, $pwd, $email)){
                $this->view->displayInsertValidate();
            }
            else{
                $this->view->displayErrorInsertion();
            }
        }
    }

    public function displayAllTechnician(){
        $results = $this->model->getUsersByRole('technicien');
        if(isset($results)){
            $this->view->displayHeaderTabTechnician();
            $row = 0;
            foreach ($results as $result){
                ++$row;
                $this->view->displayAllTechnicians($row, $result['ID'], $result['user_login']);
            }
            $this->view->displayEndTab();
        }
        else{
            $this->view->displayEmpty();
        }
    }
}