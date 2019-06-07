<?php
/**
 * Created by PhpStorm.
 * User: Rohrb
 * Date: 25/04/2019
 * Time: 10:44
 */

class ViewStudent extends ViewG
{
    public function displayInsertImportFileStudent() {
        $this->displayInsertImportFile("Etu");
    }

    public function displayTabHeadStudent(){
        $title = "Étudiants";
        $tab = ["Numéro étudiant", "Année", "Groupe", "Demi groupe"];
        $this->displayStartTab($tab, $title);
    }

    public function displayAllStudent($id, $login, $year, $group, $halfgroup, $row){
        echo '
        <tr>
          <th scope="row" class="text-center">'.$row.'</th>
          <td class="text-center"><input type="checkbox" name="checkboxstatus[]" value="'.$id.'"/></td>
          <td class="text-center">'.$login.'</td>
          <td class="text-center'; if(is_numeric($year)) echo ' errorNotRegister'; echo '">'.$year.'</td>
          <td class="text-center'; if(is_numeric($group)) echo ' errorNotRegister'; echo '">'.$group.'</td>
          <td class="text-center'; if(is_numeric($halfgroup)) echo ' errorNotRegister'; echo '">'.$halfgroup.'</td>
          <td class="text-center"> <a href="/gestion-des-utilisateurs/modification-utilisateur/'.$id.'" name="modif" type="submit" value="Modifier">Modifier</a></td>
        </tr>';
    }

    public function displayRedSignification(){
        echo '<div class="red">Zone rouge = Code ADE non enregistré</div>';
    }

    public function displayModifyStudent($result, $years, $groups, $halfgroups){
        $code = unserialize($result->code);
        $model = new CodeAdeManager();
        $titleYear = $model->getTitle($code[0]);
        $titleGroup = $model->getTitle($code[1]);
        $titleHalfgroup = $model->getTitle($code[2]);
        echo '
<div class="cadre">
         <h3>'.$result->user_login.'</h3>
         <form method="post">
            <label>Année</label>
            <select class="form-control" name="modifYear">
                <option value="'.$code[0].'">'.$titleYear.'</option>
                <option value="0">Aucun</option>
                <optgroup label="Année">
            ';
        $selected = $_POST['modifYear'];
        foreach ($years as $year) {
                echo '<option value="'.$year['code'].'"'; if($year['code'] == $selected) echo "selected"; echo'>'.$year['title'].'</option >';
        }
        echo'
            </optgroup>
            </select>
            <label>Groupe</label>
            <select class="form-control" name="modifGroup">
                <option value="'.$code[1].'">'.$titleGroup.'</option>
                <option value="0">Aucun</option>
                <optgroup label="Groupe">';
        $selected = $_POST['modifGroup'];
        foreach ($groups as $group){
            echo'<option value="'.$group['code'].'"'; if($group['code'] == $selected) echo "selected"; echo'>'.$group['title'].'</option>';
        }
        echo'
            </optgroup>
            </select>
            <label>Demi-groupe</label>
            <select class="form-control" name="modifHalfgroup">
                <option value="'.$code[2].'">'.$titleHalfgroup.'</option>
                <option value="0"> Aucun</option>
                <optgroup label="Demi-Groupe">';
        $selected = $_POST['modifHalfgroup'];
        foreach ($halfgroups as $halfgroup){
            echo'<option value="'.$halfgroup['code'].'"'; if($halfgroup['code'] == $selected) echo "selected"; echo'>'.$halfgroup['title'].'</option>';
        }
        echo'
            </optgroup>
            </select>
            <input name="modifvalider" type="submit" value="Valider">
            <a href="/gestion-des-utilisateurs">Annuler</a>
         </form>
         </div>';
    }
}