<?php
/**
 * Created by PhpStorm.
 * User: Rohrb
 * Date: 26/04/2019
 * Time: 08:49
 */

abstract class ViewG{

    protected function displayInsertImportFile($name){
        return '<a href="/wp-content/plugins/TeleConnecteeAmu/models/Excel/addUsers/ajout'.$name.'s.xlsx"
                download="Ajout '.$name.'s.xlsx">Télécharger le fichier Excel ! </a>
             <form id="'.$name.'" method="post" enctype="multipart/form-data">
				<input type="file" name="excel'.$name.'" class="inpFil" required=""/>
				<br/>
				<button type="submit" name="import'.$name.'" value="Importer">Importer le fichier</button>
			</form>
			<br/>';
    }

    protected function displayBaseForm($name){
        return '
        <div class="cadre">
             <div align="center">
                <form method="post">
                    <label for="login'.$name.'">Login</label>
                    <input type="text" class="form-control text-center modal-sm" name="login'.$name.'" placeholder="Login" required="">
                    <label for="pwd'.$name.'">Mot de passe</label>
                    <input type="password" class="form-control text-center modal-sm" name="pwd'.$name.'" placeholder="Mot de passe" required="">
                    <label for="email'.$name.'">Email</label>
                    <input type="email" class="form-control text-center modal-sm" name="email'.$name.'" placeholder="Email" required="">
                  <button type="submit" name="create'.$name.'">Créer</button>
                </form>
            </div>
         </div>';
    }

    protected function displayHeaderTab($title = null){
        echo '
            <h1>'.$title.'</h1>
            <form method="post">
                <div class="table-responsive">
                <table class="table text-center"> 
                <thead>
                    <tr class="text-center">
                        <th scope="col" width="5%" class="text-center">#</th>
                        <th scope="col" width="5%" class="text-center"><input type="checkbox" onClick="toggle(this)" /></th>';
    }

    protected function displayStartTabLog($title){
        $this->displayHeaderTab($title);
        echo '<th scope="col">Login</th>
                    </tr>
                </thead>
                <tbody>';
    }

    /**
     * Build the header of a table
     * @param $tab
     */
    protected function displayStartTab($tab, $title = null){
        $this->displayHeaderTab($title);
        foreach ($tab as $value){
            echo'<th scope="col" class="text-center"> '.$value.'</th>';
        }
        $this->displayEndheaderTab();
    }

    protected function displayEndheaderTab(){
        echo'
                <th scope="col" class="text-center">Modifer</th>
                     </tr>
                </thead>
                <tbody>
        ';
    }

    /**
     * Display the content of a row in a table
     * @param $row
     * @param $id
     * @param $tab
     */
    protected function displayAll($row, $id, $tab){
        echo '
        <tr>
          <th scope="row" class="text-center">'.$row.'</th>
          <td class="text-center"><input type="checkbox" name="checkboxstatus[]" value="'.$id.'"/></td>';
        if(isset($tab)){
            foreach ($tab as $value){
                echo '<td class="text-center">'.$value.'</td>';
            }
        }
    }

    /**
     * Close the table
     */
    public function displayEndTab(){
        echo'
          </tbody>
        </table>
        </div>
        <input type="submit" value="Supprimer" name="Delete" onclick="return confirm(\' Voulez-vous supprimer le(s) élément(s) sélectionné(s) ?\');"/>
        </form>';
    }

    /**
     * Refresh the page
     */
    public function refreshPage(){
        echo '<meta http-equiv="refresh" content="0">';
    }

    public function displayUnregisteredCode($badCodes){
        if(! is_null($badCodes[0]) || ! is_null($badCodes[1]) || ! is_null($badCodes[2])) {
            echo'
        <h3> Ces codes ne sont pas encore enregistrés ! </h3>
        <table class="table text-center"> 
                <thead>
                    <tr class="text-center">
                        <th scope="col" width="33%" class="text-center">Année</th>
                        <th scope="col" width="33%" class="text-center">Groupe</th>
                        <th scope="col" width="33%" class="text-center">Demi-Groupe</th>
                        </tr>
                </thead>
                <tbody>';
            if(is_null($badCodes[0])){
                $sizeYear = 0;
            } else {
                $sizeYear = sizeof($badCodes[0]);
            }
            if(is_null($badCodes[1])){
                $sizeGroup = 0;
            } else {
                $sizeGroup = sizeof($badCodes[1]);
            }
            if(is_null($badCodes[2])){
                $sizeHalfgroup = 0;
            } else {
                $sizeHalfgroup = sizeof($badCodes[2]);
            }
            $size = 0;
            if($sizeYear >= $sizeGroup && $sizeYear >= $sizeHalfgroup) $size = $sizeYear;
            if($sizeGroup >= $sizeYear && $sizeGroup >= $sizeHalfgroup) $size = $sizeGroup;
            if($sizeHalfgroup >= $sizeYear && $sizeHalfgroup >= $sizeGroup) $size = $sizeHalfgroup;
            for($i = 0; $i < $size; ++$i){
                echo '<tr>
                    <td class="text-center">';
                if($sizeYear > $i)
                    echo $badCodes[0][$i];
                else
                    echo ' ';
                echo '</td>
            <td class="text-center">';
                if($sizeGroup > $i)
                    echo $badCodes[1][$i];
                else
                    echo ' ';
                echo '</td>
            <td class="text-center">';
                if($sizeHalfgroup > $i)
                    echo $badCodes[2][$i];
                else
                    echo ' ';
                echo '</td>

                  </tr>';
            }
            echo '
                </tbody>
        </table>
        ';
        }
    }

    public function displayStartModal($title){
        echo '<!-- Modal -->
        <div class="modal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">'.$title.'</h5>
              </div>
              <div class="modal-body">';
    }

    public function displayEndModal($redirect = null){
        echo '</div>
              <div class="modal-footer">';
        if(empty($redirect)){
        echo '<button type="button" onclick="closeModal()">Fermer</button>';
        } else {
            echo '<button type="button" onclick="document.location.href =\' '.$redirect.' \'">Fermer</button>';
        }
        echo '</div>
            </div>
          </div>
        </div>
        
        <script> $("#myModal").show() </script>';
    }

    public function displayTest() {
        echo '<div class="alert alert-danger"> Cette fonctionnalitée est en test ! </div>';
    }

    /**
     * Display a message
     */
    public function displayEmpty(){
        echo "<div> Il n'y pas d'utilisateur de ce rôle inscrit!</div>";
    }

    public function displayErrorDouble($doubles){
        $this->displayStartModal('Erreur durant l\'incription ');
        foreach ($doubles as $double) {
            echo "<div class='alert alert-danger'>$double a rencontré un problème lors de l'enregistrement, vérifié son login et son email ! </div>";
        }
        $this->displayEndModal();
    }

    public function displayInsertValidate(){
        $this->displayStartModal('Inscription validée');
        echo "<p class='alert alert-success'>Votre inscription a été validée. </p>";
        $this->displayEndModal();
    }

    public function displayWrongExtension(){
        $this->displayStartModal('Mauvais fichier !');
        echo '<p class="alert alert-danger"> Mauvaise extension de fichier ! </p>';
        $this->displayEndModal();
    }

    public function displayWrongFile(){
        $this->displayStartModal('Mauvais fichier !');
        echo '<p class="alert alert-danger"> Vous utilisez un mauvais fichier excel / ou vous avez changé le nom des colonnes </p>';
        $this->displayEndModal();
    }

    public function displayModificationValidate(){
        $this->displayStartModal('Modification réussie');
        echo '<div class="alert alert-success"> La modification a été appliquée </div>';
        $this->displayEndModal();
    }

    public function displayErrorInsertion(){
        $this->displayStartModal('Erreur lors de l\'inscription ');
        echo '<div class="alert alert-danger"> Le login ou l\'adresse mail est déjà utilisé(e) </div>';
        $this->displayEndModal();
    }

    public function displayRow(){
        echo '<div class="row">';
    }

    public function displayEndDiv(){
        echo '</div>';
    }
}