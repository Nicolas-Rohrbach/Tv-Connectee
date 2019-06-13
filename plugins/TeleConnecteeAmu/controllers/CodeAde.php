<?php
/**
 * Created by PhpStorm.
 * User: Rohrb
 * Date: 29/04/2019
 * Time: 09:53
 * Ce controller permet de créer/modifier/supprimer des codes ADE
 */

class CodeAde extends ControllerG
{
    /**
     * Vue de CodeAde
     * @var ViewCodeAde
     */
    private $view;

    /**
     * Model de CodeAde
     * @var CodeAdeManager
     */
    private $model;

    /**
     * Constructeur de CodeAde.
     */
    public function __construct(){
        $this->view = new ViewCodeAde();
        $this->model = new CodeAdeManager();
    }

    /**
     * Lorsque le bouton est préssé, le controller appel le model pour pouvoir insérer le code écrit
     */
    public function insertCode(){
        $badCodesYears = $this->model->codeNotBound(0);
        $badCodesGroups = $this->model->codeNotBound(1);
        $badCodesHalfgroups = $this->model->codeNotBound(2);
        $badCodes = [$badCodesYears, $badCodesGroups, $badCodesHalfgroups];
        if(isset($badCodes)){
            $this->view->displayUnregisteredCode($badCodes);
        }

        $action = $_POST['addCode'];
        $code = filter_input(INPUT_POST, 'codeAde');
        $title = filter_input(INPUT_POST, 'titleAde');
        $type = filter_input(INPUT_POST, 'typeCode');

        if($action == "Valider"){
            if($this->model->addCode($type, $title, $code)){
                $this->addFile($code);
                $this->view->refreshPage();
            }
            else{
                $this->view->displayErrorDoubleCode();
            }
        }
    }

    /**
     * Affiche tout les codes ADE enregistrés dans un tableau où on peut soit les supprimer soit les modifier
     */
    public function displayAllCodes(){
        $years = $this->model->getCodeYear();
        $groups = $this->model->getCodeGroup();
        $halfgroups = $this->model->getCodeHalfgroup();
        $this->view->displayTableHeadCode();
        $row = 0;
        if(isset($years[0])){
            foreach ($years as $year) {
                $row = $row + 1;
                $this->view->displayAllCode($year, $row);
            }
        }
        if(isset($groups[0])) {
            foreach ($groups as $group){
                $row = $row + 1;
                $this->view->displayAllCode($group, $row);
            }
        }
        if(isset($halfgroups[0])) {
            foreach ($halfgroups as $halfgroup) {
                $row = $row + 1;
                $this->view->displayAllCode($halfgroup, $row);
            }
        }
        $this->view->displayEndTab();
    }

    /**
     * Supprime tout les codes qui sont sélectionnés
     * @param $action       Bouton de validation
     */
    public function deleteCodes($action){
        $model = new CodeAdeManager();
        if(isset($action)){
            if(isset($_REQUEST['checkboxstatus'])) {
                $checked_values = $_REQUEST['checkboxstatus'];
                foreach($checked_values as $val) {
                    $oldCode = $model->getCode($val);
                    $this->deleteFile($oldCode[0]['code']);
                    $model->deleteCode($val);
                    $this->view->refreshPage();
                }
            }
        }
    }

    /**
     * Modifie le code ADE lorsque le bouton est préssé
     */
    public function modifyCode(){
        $result = $this->model->getCode($this->getMyIdUrl());
        $this->view->displayModifyCode($result);

        $action = $_POST['modifCodeValid'];
        $title = filter_input(INPUT_POST,'modifTitle');
        $code = filter_input(INPUT_POST,'modifCode');
        $type = filter_input(INPUT_POST,'modifType');

        if($action == "Valider"){
            if($this->model->checkModify($result, $this->getMyIdUrl(), $title, $code, $type)){
                if($result[0]['code'] != $code){
                    $this->deleteFile($result[0]['code']);
                    $this->addFile($code);
                }
                $this->view->refreshPage();
            }
            else{
                $this->view->displayErrorDoubleCode();
            }
        }
    }
}