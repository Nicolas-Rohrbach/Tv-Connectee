<?php
/**
 * Created by PhpStorm.
 * User: SFW
 * Date: 06/05/2019
 * Time: 11:01
 */

class ViewAlert extends ViewG
{

    public function displaySelect($years, $groups, $halfgroups){
        echo '<option value="0">Aucun</option>
              <option value="all">Tous</option>
                        <optgroup label="Année">';
        foreach ($years as $year) {

            echo '<option value="'.$year['code'].'">'.$year['title'].'</option >';
        }
        echo '</optgroup>
                          <optgroup label="Groupe">';
        foreach ($groups as $group){
            echo '<option value="'.$group['code'].'">'.$group['title'].'</option>';
        }
        echo '</optgroup>
                          <optgroup label="Demi groupe">';
        foreach ($halfgroups as $halfgroup){
            echo '<option value="'.$halfgroup['code'].'">'.$halfgroup['title'].'</option>';
        }
        echo '</optgroup>
        </select>';
    }

    public function displaySelectModif($years, $groups, $halfgroups, $name){
        $selected = $name;
        echo '<option value="0">Aucun</option>
              <option value="all"';if('all' == $selected) echo "selected";  echo '> Tous</option>  
                        <optgroup label="Année">';
        foreach ($years as $year) {
            echo '<option value="'.$year['code'].'" '; if($year['code'] == $selected) echo "selected"; echo'>'.$year['title'].'</option >';
        }
        echo '</optgroup>
                          <optgroup label="Groupe">';
        foreach ($groups as $group){
            echo '<option value="'.$group['code'].'"'; if($group['code'] == $selected) echo "selected"; echo'>'.$group['title'].'</option>';
        }
        echo '</optgroup>
                          <optgroup label="Demi groupe">';
        foreach ($halfgroups as $halfgroup){
            echo '<option value="'.$halfgroup['code'].'" '; if($halfgroup['code'] == $selected) echo "selected"; echo'>'.$halfgroup['title'].'</option>';
        }
        echo '</optgroup>
        </select>';
    }

    /**
     * Display the creation form.
     */
    public function displayAlertCreationForm($years, $groups, $halfgroups) {
        $dateMin = date('Y-m-d',strtotime("+1 day")); //date minimum pour la date d'expiration

        echo '
        <div class="cadre">
            <form id="creationAlert" method="post">
                   Contenu : <input type="text" name="content" required maxlength="280"> <br>
                   Date d\'expiration : <input type="date" name="endDateAlert" min="'.$dateMin.'" required > </br> </br>
                   
                   Année, groupe, demi-groupes concernés : </br>
                    <select class="form-control firstSelect" name="selectAlert[]" required="">';
                        $this->displaySelect($years, $groups, $halfgroups);
        echo'
                <input type="button" onclick="addButtonAlert()" value="+">
                    <input type="submit" value="Publier" name="createAlert">
            </form>
        </div> 
        ';
    } //displayCreationForm();

    /**
     * Set the head of the table for the alert's management page.
     */
    public function tabHeadAlert(){
        $tab = ["Auteur","Contenu","Date de création","Date de fin"];
        $this->displayStartTab($tab);
    }//tabHeadAlert();

    /**
     * Display the table of the management page, with delete and modify button.
     * @param $id
     * @param $author
     * @param $content
     * @param $creationDate
     * @param $endDate
     * @param $row
     */
    public function displayAllAlert($id, $author, $content, $creationDate, $endDate, $row){
        $tab = [$author, $content, $creationDate, $endDate];
        $this->displayAll($row, $id, $tab);
        echo '
          <td class="text-center"> <a href="http://'.$_SERVER['HTTP_HOST'].'/modification-alerte/'.$id.'" name="modifetud" type="submit" value="Modifier">Modifier</a></td>
        </td>';
    } //displayAllAlert()


    public function displayModifyAlertForm($result, $years, $groups, $halfgroups){
        $content = $result['text'];
        $endDate = date('Y-m-d', strtotime($result['end_date']));
        $dateMin = date('Y-m-d', strtotime("+1 day"));

        $codes = unserialize($result['codes']);

        $count = 0;
        echo '
                <div class="cadre">
                    <form id="modify_alert" method="post">
                      Contenu : <textarea name="contentInfo" maxlength="280">' . $content . '</textarea> </br>
                      Date d\'expiration : <input type="date" name="endDateInfo" min="' . $dateMin . '" value = "' . $endDate . '" required > </br>';
        foreach ($codes as $code) {
            $count = $count + 1;
            if($count == 1){
                echo '<select class="form-control firstSelect" name="selectAlert[]" id="selectId'.$count.'">';
                $this->displaySelectModif($years, $groups, $halfgroups, $code);
                echo '<br/>';
            } else {
                echo '<div class="row">';
                echo '<select class="form-control select" name="selectAlert[]" id="selectId'.$count.'">';
                $this->displaySelectModif($years, $groups, $halfgroups, $code);
                echo '<input type="button" id="selectId'.$count.'" onclick="deleteRowAlert(this.id)" class="selectbtn" value="Supprimer"></div>';
            }
        }
                      
          echo '    <input type="button" onclick="addButtonAlert()" value="+">    
                    <input type="submit" name="validateChange" value="Valider" ">
                    <a href="/gerer-les-alertes">Annuler</a>
                 </form>
            </div>';
    } //displayModifyAlertForm()

    /**
     * Display alerts in the main page with a scrolling effect.
     * @param $content
     */
    public function displayAlertMain($content) {
                echo '
        <div class="alerts" id="alert">
             <div class="ti_wrapper">
                <div class="ti_slide">
                    <div class="ti_content">';
                        for($i = 0; $i < sizeof($content); ++$i){
                            echo '<div class="ti_news"><span>' .$content[$i].'</span> </div>';
                        }
                        echo '
                    </div>
                </div>
            </div>
        </div>
        ';
    } //displayAlertMain()

}