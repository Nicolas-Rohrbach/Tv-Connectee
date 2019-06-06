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
    public function deleteAlert($action) {
        if(isset($action)) {
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
    public function createAlert($action, $content, $endDate){
        $years = $this->DB->getCodeYear();
        $groups = $this->DB->getCodeGroup();
        $halfgroups = $this->DB->getCodeHalfgroup();

        $this->view->displayAlertCreationForm($years, $groups, $halfgroups);
        if(isset($action)) {
            $codes = serialize($_POST['selectAlert']);

            $this->DB->addAlertDB($content, $endDate, $codes);
        }
    } //createAlert()

    /**
     * Affiche un tableau avec toutes les alertes et des boutons de modification ainsi qu'un bouton de suppression.
     * cf snippet Handle Alert
     */
    function alertsManagement(){

        $current_user = wp_get_current_user();
        $user = $current_user->user_login;
        if($current_user->role == 'administrator') $result = $this->DB->getListAlert();
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
        if ($current_user->roles[0] == "television" || $current_user->roles[0] == "etudiant" || $current_user->roles[0] == "enseignant") {
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
            if($current_user->roles[0] == 'administrator' || $current_user->roles[0] == 'secretaire' ) {
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

    public function test(){
      echo 'bienvenue sur la page de test';
    }
}