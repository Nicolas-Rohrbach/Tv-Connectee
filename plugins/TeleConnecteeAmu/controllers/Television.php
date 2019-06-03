<?php
/**
 * Created by PhpStorm.
 * User: Rohrb
 * Date: 25/04/2019
 * Time: 11:41
 */

class Television extends ControllerG{

    /**
     * View de Television
     * @var ViewTelevision
     */
    private $view;

    /**
     * Model de Television
     * @var TelevisionManager
     */
    private $model;

    /**
     * Constructeur de Television
     */
    public function __construct(){
        $this->view = new ViewTelevision();
        $this->model = new TelevisionManager();
    }

    public function insertTelevision(){
        $action = $_POST['createTv'];
        $years = $this->model->getCodeYear();
        $groups = $this->model->getCodeGroup();
        $halfgroups = $this->model->getCodeHalfgroup();

        $this->view->displayFormTelevision($years, $groups, $halfgroups);
        if(isset($action)){
            $login = filter_input(INPUT_POST,'loginTv');
            $pwd = md5(filter_input(INPUT_POST,'pwdTv'));
            $codes = $_POST['selectTv'];
            if($this->model->insertMyTelevision($login, $pwd, $codes)){
                $this->view->refreshPage();
            }
            else{
                $this->view->displayErrorLogin();
            }
        }
    }

    public function displayAllTv(){
        $results = $this->model->getUsersByRole('television');
        if(isset($results)){
            $this->view->displayHeaderTabTv();
            $row = 0;
            foreach ($results as $result){
                ++$row;
                $id = $result['ID'];
                $login = $result['user_login'];
                $codes = unserialize($result['code']);
                if(is_array($codes)) {
                    $nbCode = sizeof($codes);
                } else {
                    $nbCode = 1;
                }

                $this->view->displayAllTv($id, $login, $nbCode, $row);
            }
            $this->view->displayEndTab();
        }
        else {
            $this->view->displayEmpty();
        }
    }

    public function modifyTv($result){
        $years = $this->model->getCodeYear();
        $groups = $this->model->getCodeGroup();
        $halfgroups = $this->model->getCodeHalfgroup();
        $this->view->displayModifyTv($result, $years, $groups, $halfgroups);

        $action = $_POST['modifValidate'];

        if(isset($action)){
            $codes = $_POST['selectTv'];
            $pwd = $_POST['pwdTv'];
            $pwd = wp_hash_password($pwd);
            if(empty($pwd)){
                $pwd = $result['user_pass'];
            }
            if($this->model->modifyTv($result, $pwd, $codes)){
                $this->view->refreshPage();
            }
        }
    }
}