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
        if(isset($action)){
            if(isset($_REQUEST['checkboxstatus'])) {
                $checked_values = $_REQUEST['checkboxstatus'];
                foreach($checked_values as $val) {
                    $this->deleteUser($val);
                }
            }
        }
    }

    public function deleteUser($id){
        $model = new StudentManager();
        $user = $model->getById($id);
        $data = get_userdata($id);
        $model->deleteUser($id);
        if(in_array("enseignant", $data->roles) == 'enseignant' ){
            $code = unserialize($user[0]['code']);
            unlink($this->getFilePath($code[0]));
        }
        if(in_array("enseignant", $data->roles) || in_array("secretaire", $data->roles) || in_array("administrator", $data->roles)){
            $modelAlert = new AlertManager();
            $modelInfo = new InformationManager();
            $alerts = $modelAlert->getListAlertByAuthor($user[0]['user_login']);
            if(isset($alerts)){
                foreach ($alerts as $alert) {
                    $modelAlert->deleteAlertDB($alert['ID_alert']);
                }
            }
            if(in_array("secretaire", $data->roles) || in_array("administrator", $data->roles)) {
                $infos = $modelInfo->getListInformationByAuthor($user[0]['user_login']);
                if(isset($infos)){
                    foreach ($infos as $info) {
                        $type = $info['type'];
                        if($type == "img" || $type == "") {
                            $this->deleteFile($info['ID_info']);
                        }
                        $modelInfo->deleteInformationDB($info['ID_info']);
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

    public  function getUrl($code){
        $str = strtotime("last Monday");
        $str2 = strtotime(date("Y-m-d", strtotime('last Monday')) . " +6 day");
        $start =  date('Y-m-d',$str);
        $end = date('Y-m-d',$str2);
        $url = 'https://ade-consult.univ-amu.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=8&resources=' . $code . '&calType=ical&firstDate='.$start.'&lastDate='.$end;
        echo $url;
        return $url;
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
        $url = $this->getUrl($code);
        $file = @fopen($url, 'r');
        if(isset($file)){
            file_put_contents($path, $file);
        }
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