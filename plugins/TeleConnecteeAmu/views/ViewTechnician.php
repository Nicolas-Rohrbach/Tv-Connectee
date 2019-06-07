<?php


class ViewTechnician extends ViewG
{
    public function displayFormTechnician(){
        $this->displayBaseForm('Tech');
    }

    public function displayHeaderTabTechnician(){
        $this->displayStartTabLog('Techniciens');
    }

    public function displayAllTechnicians($row, $id, $login){
        $tab[] = $login;
        $this->displayAll($row, $id, $tab);
    }
}