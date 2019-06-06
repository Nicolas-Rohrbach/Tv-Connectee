<?php
/**
 * Created by PhpStorm.
 * User: Rohrb
 * Date: 29/04/2019
 * Time: 09:54
 */

class ViewCodeAde extends ViewG
{
    /**
     * Display a form for create a new ADE code with a title and a type
     */
    public function displayFormAddCode(){
        echo '
         <div class="cadre">
             <div align="center">
                <form method="post">
                    <label for="titleAde">Titre</label>
                    <input type="text" class="form-control text-center modal-sm" name="titleAde" placeholder="Titre" required="">
                    <label for="codeAde">Code ADE</label>
                    <input type="text" class="form-control text-center modal-sm" name="codeAde" placeholder="Code ADE" required="">
                    <input type="radio" name="typeCode" value="Annee"> Année 
                    <input type="radio" name="typeCode" value="Groupe"> Groupe
                    <input type="radio" name="typeCode" value="Demi-groupe"> Demi-Groupe
                    <br/>
                  <button type="submit" name="addCode" value="Valider">Ajouter</button>
                </form>
            </div>
         </div>';
    }

    /**
     * Header of the table
     */
    public function displayTableHeadCode(){
        $tab = ["Titre", "Code ADE", "Type"];
        $this->displayStartTab($tab);
    }

    /**
     * Display all codes in a tab
     * @param $result
     * @param $row
     */
    public function displayAllCode($result, $row){
        $tab = [$result['title'], $result['code'], $result['type']];
        $this->displayAll($row, $result['ID'], $tab);
        echo '<td class="text-center"> <a href="/gestion-codes-ade/modification-code-ade/'.$result['ID'].'" name="modifCode" type="submit">Modifier</a></td>
                </tr>';
    }

    /**
     * Display the page for modify this code
     * @param $result
     */
    public function displayModifyCode($result){
        echo '
         <form method="post">
            <label>Titre</label>
            <input name="modifTitle" type="text" class="form-control" placeholder="Titre" value="'.$result[0]['title'].'">
            <label>Code</label>
            <input name="modifCode" type="text" placeholder="Code" value="'.$result[0]['code'].'">
            <div class="form-group">
            <label for="exampleFormControlSelect1">Selectionner un type</label>
                <select class="form-control" name="modifType">
                    <option>'.$result[0]['type'].'</option>
                    <option>Annee</option>
                    <option>Groupe</option>
                    <option>Demi-Groupe</option>
                </select>
            </div>
            <input name="modifCodeValid" type="submit" value="Valider">
            <a href="http://'.$_SERVER['HTTP_HOST'].'/gestion-codes-ade">Annuler</a>
         </form>';
    }

    /**
     * Error message if the insertion or the modification want to have a double code or title
     */
    public function displayErrorDoubleCode(){
        echo '<div class="alert alert-danger"> Ce code ou ce titre existe déjà ! </div>';
    }

    public function displayEmptyCode() {
        echo '<div> Il n\'y a pas de code ajouté!';
    }
}