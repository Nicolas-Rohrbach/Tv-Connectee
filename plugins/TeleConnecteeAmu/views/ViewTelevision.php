<?php
/**
 * Created by PhpStorm.
 * User: Rohrb
 * Date: 25/04/2019
 * Time: 10:46
 */

class ViewTelevision extends ViewG{

    public function displaySelect($years, $groups, $halfgroups){
        echo '<option value="0">Aucun</option>
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

    public function displayFormTelevision($years, $groups, $halfgroups) {
        echo '
         <div class="cadre">
            <div align="center">
                <form method="post" id="registerTvForm">
                    <label for="loginTv">Login</label>
                    <input type="text" class="form-control text-center modal-sm" name="loginTv" placeholder="Nom de compte" required="">
                    <label for="pwdTv">Mot de passe</label>
                    <input type="password" class="form-control text-center modal-sm" name="pwdTv" placeholder="Mot de passe" required="">
                    <label>Premier emploi du temps</label>
                    <select class="form-control firstSelect" name="selectTv[]" required="">';
        $this->displaySelect($years, $groups, $halfgroups);

        echo'
                <input type="button" onclick="addButtonTv()" value="Ajouter des emplois du temps">
                    <button type="submit" name="createTv">Créer</button>
                </form>
            </div> 
         </div>';
    }

    public function displayHeaderTabTv(){
        $title = "Télévisions";
        $tab = ["Login", "Nombre d'emploi du temps"];
        $this->displayStartTab($tab, $title);
    }

    public function displayAllTv($id, $login,  $nbCode, $row){
        $tab = [$login, $nbCode];
        $this->displayAll($row, $id, $tab);
        echo '<td class="text-center"> <a href="/gestion-des-utilisateurs/modification-utilisateur/'.$id.'" name="modif" type="submit" value="Modifier">Modifier</a></td>
        </tr>';
    }

    public function displaySelectSelected($years, $groups, $halfgroups, $name){
        $selected = $name;
        echo '<option value="0">Aucun</option>
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

    public function displayModifyTv($result, $years, $groups, $halfgroups){
        $codes = unserialize($result['code']);
        $count = 0;
        echo '
         <h3>'.$result['user_login'].'</h3>
         <div class="cadre">
         <div align="center">
         <form method="post" id="registerTvForm">
            <label>Mot de passe </label>
            <input type="password" class="form-control text-center modal-sm" name="pwdTv" placeholder="Mot de passe">
            <label> Emploi du temps</label>';
        if(is_array($codes)){
            foreach ($codes as $code) {
                $count = $count + 1;
                if($count == 1){
                    echo '<select class="form-control firstSelect" name="selectTv[]" id="selectId'.$count.'">';
                    $this->displaySelectSelected($years, $groups, $halfgroups, $code);
                    echo '<br/>';
                } else {
                    echo '<div class="row">';
                    echo '<select class="form-control select" name="selectTv[]" id="selectId'.$count.'">';
                    $this->displaySelectSelected($years, $groups, $halfgroups, $code);
                    echo '<input type="button" id="selectId'.$count.'" onclick="deleteRow(this.id)" class="selectbtn" value="Supprimer"></div>';
                }
            }
        } else {
            echo '<select class="form-control firstSelect" name="selectTv[]" id="selectId'.$count.'">';
            $this->displaySelectSelected($years, $groups, $halfgroups, $codes);
            echo '<br/>';
        }

        echo '
            <input type="button" onclick="addButtonTv()" value="Ajouter des emplois du temps">
            <input name="modifValidate" type="submit" value="Valider">
            <a href="/gestion-des-utilisateurs">Annuler</a>
         </form>
         </div>
         </div>';
    }

    public function displayErrorLogin(){
        $this->displayStartModal('Inscription échouée');
        echo '<div class="alert alert-danger"> Le login est déjà utilisé ! </div>';
        $this->displayEndModal();
    }
}