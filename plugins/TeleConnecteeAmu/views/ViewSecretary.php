<?php
/**
 * Created by PhpStorm.
 * User: Rohrb
 * Date: 25/04/2019
 * Time: 10:52
 */

class ViewSecretary extends ViewG
{
    public function displayFormSecretary() {
        $this->displayBaseForm('Secre');
    }

    public function displayHeaderTabSecretary(){
        $title = "Secrétaires";
        $this->displayStartTabLog($title);
    }

    public function displayAllSecretary($row, $id, $login){
        $tab[] = $login;
        $this->displayAll($row, $id, $tab);
    }
}