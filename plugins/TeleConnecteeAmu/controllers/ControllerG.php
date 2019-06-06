<?php
/**
 * Created by PhpStorm.
 * User: Rohrb
 * Date: 29/04/2019
 * Time: 14:54
 */

abstract class ControllerG {

    /**
     * Renvoie l'ID
     * @return mixed
     */
    protected function getMyIdUrl(){
        $urlExpl = explode('/', $_SERVER['REQUEST_URI']);
        $size = sizeof($urlExpl);
        return $urlExpl[$size-2];
    }

    /**
     * Supprime tout les utilisateurs sélectionnés
     * @param $action
     */
    public function deleteUsers($action){
        $model = new StudentManager();
        $modelAlert = new AlertManager();
        $modelInfo = new InformationManager();
        if(isset($action)){
            if(isset($_REQUEST['checkboxstatus'])) {
                $checked_values = $_REQUEST['checkboxstatus'];
                foreach($checked_values as $val) {
                    $result = $model->getById($val);
                    $model->deleteUser($val);
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
            }
        }
    }

    public function addLogEvent($event){
        $time = date("D, d M Y H:i:s");
        $time = "[".$time."] ";
        $event = $time.$event."\n";
        file_put_contents(ABSPATH."/wp-content/plugins/TeleConnecteeAmu/fichier.log", $event, FILE_APPEND);
    }

    /**
     * Renvoie les dates de début et de fin, de l'emploi du temps
     * @return array
     */
    public function getTabConfig(){
        ### Initialisation
        $planning = new Planning();
        ## Récupération de la configuration
        $conf = $planning->getConf();
        # On prépare l’export en iCal
        list($startDay, $startMonth, $startYear) = explode('/', gmdate('d\/m\/Y', $conf['FIRST_WEEK']));
        list($endDay, $endMonth, $endYear) = explode('/', gmdate('d\/m\/Y', intval($conf['FIRST_WEEK'] + ($conf['NB_WEEKS'] * 7 * 24 * 3600))));
        $tab = [$startDay, $startMonth, $startYear, $endDay, $endMonth, $endYear];
        return $tab;
    }

    public function getFilePath($code){
        $path = ABSPATH . "/wp-content/plugins/TeleConnecteeAmu/controllers/fileICS/" . $code;
        return $path;
    }

    /**
     * Ajoute un fichier via le code donné
     * @param $code     Code ADE
     * @param $tab      Configuration pour les dates de début & fin de l'année scolaire
     */
    public function addFile($code){
        $path = $this->getFilePath($code);
        $url = 'https://ade-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=' . $code . '&projectId=8&calType=ical';
        file_put_contents($path, fopen($url, 'r'));
    }

    /**
     * Supprime le fichier lié au code
     * @param $code     Code ADE
     */
    public function deleteFile($code){
        $path = ABSPATH . "/wp-content/plugins/TeleConnecteeAmu/controllers/fileICS/" .$code;
        if(! unlink($path))
            $this->addLogEvent("Le fichier ne s'est pas supprimer (chemin: ".$path.")");
    }
}