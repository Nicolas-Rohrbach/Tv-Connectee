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
     * @param $code
     * @param $force
     */
    public function displaySchedule($code, $force){
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
        $model = new CodeAdeManager();
        $title = $model->getTitle($code);
        if ($code == $title){
            $this->addLogEvent("Code non enregistré: ".$code);
            $current_user = wp_get_current_user();
            $title = $current_user->user_login;
        }
        $this->view->displayName($title);
        $R34ICS->display_calendar($url, $force, $args);
    }

    /**
     * Affiche l'emploi du temps d'une promo en fonction de l'ID récupéré dans l'url
     */
    public function displayYearSchedule(){
        $code = $this->getMyIdUrl();
        if($code == 'emploi-du-temps') {
            $this->view->displaySelectSchedule();
        } else {
            $force = true;
            $this->displaySchedule($code, $force);
        }
    }

    /**
     * Affiche l'emploi du temps de la personne concerné sauf si il s'agit d'une personne qui n'a pas de code ADE lié à son compte
     * @throws Exception
     */
    public function displaySchedules(){
        $current_user = wp_get_current_user();
        if ($current_user->roles[0] == "television" || $current_user->roles[0] == "etudiant" || $current_user->roles[0] == "enseignant") {
            $force = true;
            $codes = unserialize($current_user->code);

            if($current_user->role == "enseignant") {
                $this->displaySchedule($codes[0], $force);
            }

            if($current_user->roles[0] == "etudiant" || $current_user->roles[0] == "television"){
                if(is_array($codes)){
                    foreach ($codes as $code) {
                        $addCode = new CodeAde();
                        $path = $addCode->getFilePath($code);
                        if(! file_exists($path) || file_get_contents($path) == ''){
                            $addCode->addFile($code);
                        }
                    }
                    if ($current_user->role == "television") {
                        $this->view->displayStartSlide();
                        foreach ($codes as $code) {
                            $this->displaySchedule($code, $force);
                            $this->view->displayMidSlide();
                        }
                        $this->view->displayEndSlide();
                    } else {
                        $this->displaySchedule(end($codes), $force);
                    }
                }
                else
                    $this->displaySchedule($codes, $force);
                }
        } else {
            $this->view->displayWelcome();
        }
    }
}