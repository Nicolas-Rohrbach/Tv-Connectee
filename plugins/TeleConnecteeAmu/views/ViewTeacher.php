<?php
/**
 * Created by PhpStorm.
 * User: Rohrb
 * Date: 25/04/2019
 * Time: 10:58
 */

class ViewTeacher extends ViewG
{
    /**
     * Display the input for read a file
     */
    public function displayInsertImportFileTeacher() {
        return $this->displayInsertImportFile("Prof");
    }

    /**
     * Header from the table
     */
    public function displayTabHeadTeacher(){
        $tab = ["Numéro Ent", "Code ADE"];
        $title = "Enseignants";
        $this->displayStartTab($tab, $title);
    }

    /**
     * Display the content of all teacher per row in a table
     * @param $result
     * @param $row
     */
    public function displayAllTeacher($result, $row){
        $code = unserialize($result['code']);
        $tab = [$result['user_login'], $code[0]];
        $this->displayAll($row, $result['ID'], $tab);
        echo '
          <td class="text-center"> <a href="/gestion-des-utilisateurs/modification-utilisateur/'.$result['ID'].'" name="modif" type="submit" value="Modifier">Modifier</a></td>
        </tr>';
    }

    /**
     * Display the page for modify the code of the teacher
     * @param $result
     */
    public function displayModifyTeacher($result){
        $code = unserialize($result->code);
        echo '
         <div class="cadre">
             <form method="post">
                <h3>'.$result->user_login.'</h3>
                <label>Code ADE</label>
                <input name="modifCode" type="text" class="form-control" placeholder="Entrer le Code ADE" value="'.$code[0].'" required="">
                <button name="modifValidate" type="submit" value="Valider">Valider</button>
                <a href="/gestion-des-utilisateurs">Annuler</a>
             </form>
         </div>';
    }
}