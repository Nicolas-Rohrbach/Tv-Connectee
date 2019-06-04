<?php
/**
 * Created by PhpStorm.
 * User: Rohrb
 * Date: 06/05/2019
 * Time: 08:58
 */

class MyAccount extends ControllerG {
    private $view;
    private $model;

    /**
     * Constructeur MyAccount.
     */
    public function __construct(){
        $this->view = new ViewMyAccount();
        $this->model = new MyAccountManager();
    }

    /**
     * Modifie le mot de passe de l'utilisateur si il écrit bien son mot de passe actuel
     */
    public function modifyPwd(){
        $this->view->displayVerifyPassword();
        $this->view->displayModifyPassword();
        $action = $_POST['modifyMyPwd'];
        $current_user = wp_get_current_user();
        if(isset($action)){
            $pwd = filter_input(INPUT_POST, 'verifPwd');
            if(wp_check_password($pwd, $current_user->user_pass)){
                $newPwd = filter_input(INPUT_POST, 'newPwd');
                wp_set_password( $newPwd, $current_user->ID);
                $this->view->displayModificationValidate();
            }
            else{
                $this->view->displayWrongPassword();
            }
        }
    }

    /**
     * Supprime le compte de l'utilisateur si son mot de passe est correcte et si le code qui rentre est correct
     */
    public function deleteAccount(){
        $this->view->displayVerifyPassword();
        $this->view->displayDeleteAccount();
        $this->view->displayEnterCode();
        $action = $_POST['deleteMyAccount'];
        $actionDelete = $_POST['deleteAccount'];
        $current_user = wp_get_current_user();
        if(isset($action)){
            $pwd = filter_input(INPUT_POST, 'verifPwd');
            if(wp_check_password($pwd, $current_user->user_pass)) {
                $code = wp_generate_password();
                $exist = $this->model->getCode($current_user->ID);
                if(isset($exist)){
                    $this->model->modifyCode($current_user->ID, $code);
                } else {
                    $this->model->createRandomCode($current_user->ID, $code);
                }
                $message = "Voici votre code pour pouvoir vous désinscrire sur ".$_SERVER['HTTP_HOST'].".";
                $message .= "Le code est: ".$code.".";
                mail($current_user->user_email, "Code de désinscription", $message);
                $this->view->displayMailSend();
            }
            else{
                $this->view->displayWrongPassword();
            }
        }
        elseif (isset($actionDelete)){
            $code = $_POST['codeDelete'];
            $userCode = $this->model->getCode($current_user->ID);
            if($code == $userCode[0]['Code']){
                if($current_user->role == 'enseignant')
                    $code = unserialize($current_user->code);
                unlink($this->getFilePath($code[0]));
                $this->model->deleteCode($current_user->ID);
                $this->deleteUsers($actionDelete);
                $this->view->displayModificationValidate();
            }
            else{
                echo "bad code";
            }
        }
    }
}