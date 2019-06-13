<?php
/**
 * Created by PhpStorm.
 * User: r17000292
 * Date: 06/02/2019
 * Time: 17:23
 */

class Schedule extends ControllerG
{
    /**
     * View de Schedule
     * @var ViewSchedule
     */
    private $view;

    /**
     * Constructeur de Schedule.
     */
    public function __construct(){
        $this->view = new ViewSchedule();
    }

    /**
     * Affiche l'emploi du temps demandé
     * @param $code     Code ADE de l'emploi du temps
     */
    public function displaySchedule($code){
        global $R34ICS;
        $R34ICS = new R34ICS();

        $url = ABSPATH."/wp-content/plugins/TeleConnecteeAmu/controllers/fileICS/".$code;
        $args = array(
            'count' => 10,
            'description' => null,
            'eventdesc' => null,
            'format' => null,
            'hidetimes' => null,
            'showendtimes' => null,
            'title' => null,
            'view' => 'list',
        );
        $R34ICS->display_calendar($url, $code, $args);
    }

    /**
     * Affiche l'emploi du temps d'une année en fonction de l'ID récupéré dans l'url
     */
    public function displayYearSchedule(){
        $code = $this->getMyIdUrl();
        if($code == 'emploi-du-temps') {
            $this->view->displaySelectSchedule();
        } else {
            return $this->displaySchedule($code);
        }
    }

    /**
     * Affiche l'emploi du temps de la personne connectée,
     * @throws Exception
     */
    public function displaySchedules(){
        $current_user = wp_get_current_user();
        if (in_array("television",$current_user->roles) || in_array("etudiant",$current_user->roles) || in_array("enseignant",$current_user->roles)) {
            $codes = unserialize($current_user->code);

            if(in_array("enseignant",$current_user->roles)) {
                $this->displaySchedule($codes[0]);
            }

            if(in_array("etudiant",$current_user->roles) || in_array("television",$current_user->roles)){
                if(is_array($codes)){
                    foreach ($codes as $code) {
                        $addCode = new CodeAde();
                        $path = $addCode->getFilePath($code);
                        if(! file_exists($path) || file_get_contents($path) == ''){
                            $addCode->addFile($code);
                        }
                    }
                    if (in_array("television",$current_user->roles)) {
                        $this->view->displayStartSlide();
                        foreach ($codes as $code) {
                            $path = $this->getFilePath($code);
                            if(file_exists($path)){
                                $this->displaySchedule($code);
                                $this->view->displayMidSlide();
                            }
                        }
                        $this->view->displayEndSlide();
                    } else {
                        $this->displaySchedule(end($codes));
                    }
                } else {
                    $this->displaySchedule($codes);
                }
            }
        } elseif (in_array("technicien", $current_user->roles)){
            $model = new CodeAdeManager();
            $years = $model->getCodeYear();
            $row = 0;
            foreach ($years as $year){
                if($row % 2 == 0) {
                    $this->view->displayRow();
                }
                $this->displaySchedule($year['code']);
                if($row % 2 == 1) {
                    $this->view->displayEndDiv();
                }
                $row = $row + 1;
            }
        } else {
            $this->view->displayWelcome();
        }
    }
}