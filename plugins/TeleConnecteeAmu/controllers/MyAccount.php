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
                $code = $this->model->createRandomCode($current_user->ID);
                $to  = $current_user->user_email;
                $subject = "Désinscription à la télé-connecté";
                $message = '
                                 <html>
                                  <head>
                                   <title>Désnscription à la télé-connecté</title>
                                  </head>
                                  <body>
                                   <p>Bonjour, vous avez décidé de vous désinscrire sur le site de la Télé Connecté</p>
                                   <p> Votre code de désinscription est : '.$code.'.</p>
                                   <p> Pour vous désinscrire, rendez-vous sur le site : <a href="'.home_url().'/mon-compte/"> Tv Connectée.</p>
                                  </body>
                                 </html>
                                 ';

                // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
                $headers[] = 'MIME-Version: 1.0';
                $headers[] = "Content-Type: text/html; charset=UTF-8";

                // Envoi
                mail($to, $subject, $message, implode("\n", $headers));
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
                if($current_user->roles[0] == 'enseignant' ){
                    $code = unserialize($current_user->code);
                    unlink($this->getFilePath($code[0]));
                }
                if($current_user->roles[0] == 'enseigant' || $current_user->roles[0] == 'secretaire'){
                    $modelAlert = new AlertManager();
                    $modelInfo = new InformationManager();
                    $result = $this->model->getById($current_user->ID);
                    $alerts = $modelAlert->getListAlertByAuthor($result[0]['user_login']);
                    if(isset($alerts)){
                        foreach ($alerts as $alert) {
                            $modelAlert->deleteAlertDB($alert['ID_alert']);
                        }
                    }
                    $infos = $modelInfo->getListInformationByAuthor($result[0]['user_login']);
                    if(isset($infos)){
                        foreach ($infos as $info) {
                            $modelInfo->deleteInformationDB($info['ID_info']);
                        }
                    }
                }
                $this->model->deleteCode($current_user->ID);
                require_once( ABSPATH.'wp-admin/includes/user.php' );
                wp_delete_user( $current_user->ID);
                $this->view->displayModificationValidate();
            }
            else{
                echo "bad code";
            }
        }
    }
}