<?php
/**
 * Created by PhpStorm.
 * User: SFW
 * Date: 06/05/2019
 * Time: 11:01
 */

class Alert
{
    private $DB;
    private $view;

    /**
     * Constructeur d'alert, initialise le modèle et la vue.
     */
    public function __construct(){
        $this->DB = new AlertManager();
        $this->view = new ViewAlert();
    }

    /**
     * Supprime les alertes sélectionnées dans la page de gestion des alertes.
     * @param $action
     * @see alertsManagement()
     */
    public function deleteAlert() {
        $actionDelete = $_POST['Delete'];
        if(isset($actionDelete)) {
            if (isset($_REQUEST['checkboxstatus'])) {
                $checked_values = $_REQUEST['checkboxstatus'];
                foreach ($checked_values as $val) {
                    $this->DB->deleteAlertDB($val);
                }
            }
            $this->view->refreshPage();
        }
    } //deleteAlert()


    /**
     * Affiche le formulaire de création et ajoute l'alerte créée.
     * cf snippet Create Alert
     * @param $action
     * @param $content
     * @param $endDate
     */
    public function createAlert(){
        $action = $_POST['createAlert'];
        $content = filter_input(INPUT_POST,'content');
        $endDate = $_POST['endDateAlert'];

        if(isset($action)) {
            $codes = serialize($_POST['selectAlert']);

            $this->DB->addAlertDB($content, $endDate, $codes);
        }

    } //createAlert()

    public function sendAlert($id){

        $alertCodes = $this->DB->getListCodes($id);
        $codes = unserialize($alertCodes['codes']);

        $alertListCodes = array();
        if(is_array($codes)){
            foreach ($codes as $code) {
                array_push($alertListCodes,$code);
                echo '</br> code de l\'alerte :' .$code .'</br>';
            }
        } else {
            array_push($alertListCodes, $codes);
        }

        $students = $this->DB->getUsersByRole("etudiant");
        $userListCodes = array();
        //$userLoginListToSend = array();
        foreach ($students as $student) {
            $codesUser = unserialize($student['code']);
            echo 'etudiant : '. $student['user_login'];
        }

//            if(is_array($codesUser)){
//                foreach ($codes as $code) {
//                    array_push($userListCodes,$code);
//                    echo $code.'</br>';
//                }
//            } else {
//                array_push($userListCodes, $codes);
//            }
//            if(in_array($userListCodes, $alertListCodes)){
//
//                array_push($userLoginListToSend,$student['user_login']);
//            }
////            echo $student['user_login'] . ' ';
//        }
//
//        $userLoginListToSend = array_unique($userLoginListToSend);

    }

    /**
     * Affiche un tableau avec toutes les alertes et des boutons de modification ainsi qu'un bouton de suppression.
     * cf snippet Handle Alert
     */
    function alertsManagement(){

        $current_user = wp_get_current_user();
        $user = $current_user->user_login;
        if(in_array("administrator", $current_user->roles)) $result = $this->DB->getListAlert();
        else $result = $this->DB->getListAlertByAuthor($user);

        $this->view->tabHeadAlert();
        $i = 0;

        foreach ($result as $row) {
            $id = $row['ID_alert'];
            $author = $row['author'];
            $content = $row['text'];
            $creationDate = $row['creation_date'];
            $endDate = $row['end_date'];

            $this->endDateCheckAlert($id, $endDate);

            // change l'affichage de la date en français (jour-mois-année)
            $endDatefr = date("d-m-Y", strtotime($endDate));
            $creationDatefr = date("d-m-Y", strtotime($creationDate));

            $this->view->displayAllAlert($id, $author, $content, $creationDatefr, $endDatefr, ++$i);
        }
        $this->view->displayEndTab();
    } //alertManagement()

    /**
     * Verifie si la date de fin est dépassée et supprime l'alerte si c'est le cas.
     * @param $id
     * @param $endDate
     */
    public function endDateCheckAlert($id, $endDate){
        if($endDate <= date("Y-m-d")) {
            $this->DB->deleteAlertDB($id);
        }
    } //endDateCheckAlert()


    /**
     * Récupère l'id de l'alerte depuis l'url et affiche le formulaire de modification pré-remplis.
     * cf snippet Modification Alert
     */
    public function modifyAlert()
    {
        $years = $this->DB->getCodeYear();
        $groups = $this->DB->getCodeGroup();
        $halfgroups = $this->DB->getCodeHalfgroup();

        $urlExpl = explode('/', $_SERVER['REQUEST_URI']);
        $id = $urlExpl[2];

        $action = filter_input(INPUT_POST,'validateChange');

        $result = $this->DB->getAlertByID($id);


        $this->view->displayModifyAlertForm($result,$years, $groups,$halfgroups);

        if ($action == "Valider") {
            $content = filter_input(INPUT_POST,'contentInfo');
            $endDate = filter_input(INPUT_POST,'endDateInfo');
            $codes = $_POST['selectAlert'];

            $this->DB->modifyAlert($id, $content, $endDate, $codes);
            $this->view->refreshPage();
        }
    } //modifyAlert()


    /**
     * Récupère la liste des alertes et l'affiche sur la page principale
     *cf snippet Display Alert
     */
    public function alertMain(){
        // Recuperation des codes de l'utilisateur
        $current_user = wp_get_current_user();
        $codesUserList = array();
        if (in_array("television", $current_user->roles) || in_array("etudiant", $current_user->roles) || in_array("enseignant", $current_user->roles)) {
            $codes = unserialize($current_user->code);
            if(is_array($codes)){
                foreach ($codes as $code) {
                    array_push($codesUserList,$code);
                }
            } else {
                array_push($codesUserList, $codes);
            }

            array_push($codesUserList, 'all'); // Pour avoir les alertes concerné par tous
        }

        //Ajoute dans une liste les alertes avec le même code que l'utilisateur
        $result = $this->DB->getListAlert();
        $alertIDList = array();
        foreach ($result as $row) {
            $alertCodes = unserialize($row['codes']);
            $id = $row['ID_alert'];
            if(in_array("administrator",$current_user->roles) || in_array("secretaire", $current_user->roles)) {
                array_push($alertIDList,$id);
            } else {
                foreach ($alertCodes as $code){
                    if(in_array($code, $codesUserList)) {
                        array_push($alertIDList,$id);
                    }
                }
            }
        }


        $alertIDList = array_unique($alertIDList); //retire les doublons
        $contentList = array();
        foreach ($alertIDList as $id){
            $result = $this->DB->getAlertByID($id);
            $content = $result['text'];
            $endDate = date('Y-m-d',strtotime($result['end_date']));

            $this->endDateCheckAlert($id,$endDate); //verifie si l'alerte est depassé

            $content .= "&emsp;&emsp;&emsp;&emsp;";
            array_push($contentList, $content);
        }
        $this->view->displayAlertMain($contentList);

    } // alertMain()



// ONESIGNAL NOTIFICATIONS PUSH

function sendMessage($login) {

    $content      = array(
        "en" => 'Ceci est une alerte test'
    );
    $hashes_array = array();
    $fields = array(
        'app_id' => "317b1068-1f28-4e19-81e4-9a3553c449ea",
        'included_segments' => array(
            'All'
        ),
        'data' => array(
            "foo" => "bar"
        ),
        'contents' => $content,
        'web_buttons' => $hashes_array,
        'filters' => array(array("field" => "tag", "key" => "login", "relation" => "=", "value" => $login))
    );

    $fields = json_encode($fields);


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic YjY1ZGUxMDktYjNhZi00NTYxLWIwZjYtNWEwMmZhNzQ2ZGY1'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}


    public function test(){

        echo 'bienvenue sur la page de test  ! </br>
               
         <a href="#" id="my-notification-button" style="display: none;">recevoirNotifications</a>';
        //$this->sendMessage("Lea");
        $this->sendAlert(2);

    }
}