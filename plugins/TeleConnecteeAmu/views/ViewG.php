<?php
/**
 * Created by PhpStorm.
 * User: Rohrb
 * Date: 26/04/2019
 * Time: 08:49
 */

abstract class ViewG{

    protected function displayInsertImportFile($name){
        echo '<a href="/wp-content/plugins/TeleConnecteeAmu/models/Excel/addUsers/ajout'.$name.'s.xlsx"
                download="Ajout '.$name.'">Télécharger le fichier Excel ! </a>
             <form method="post" enctype="multipart/form-data">
				<input type="file" name="excel'.$name.'" class="inpFil"/>
				<br/>
				<button type="submit" name="import'.$name.'" value="Importer">Importer le fichier</button>
			</form>
			<br/>';
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
        <input type="submit" value="Supprimer" name="Delete"/>
        </form>';
    }

    /**
     * Refresh the page
     */
    public function refreshPage(){
        echo '<meta http-equiv="refresh" content="0">';
    }

    /**
     * Display a message
     */
    public function displayEmpty(){
        echo "<div> Il n'y pas d'utilisateur de ce rôle inscrit!</div>";
    }

    public function displayErrorDouble($doubles){
        echo '
        <div class="alert alert-danger">
        <h2>Attention !</h2>';
        foreach ($doubles as $double) {
            echo "<h4>$double a rencontré un problème lors de l'enregistrement, vérifié son login et son email ! </h4>";
        }
        echo '</div>';
    }

    public function displayInsertValidate(){
        echo "<p class='alert alert-success'>Votre inscription a été validé. </p>";
    }

    public function displayWrongExtension(){
        echo '<p class="alert alert-danger"> Mauvaise extension de fichier ! </p>';
    }

    public function displayWrongFile(){
        echo '<p class="alert alert-danger"> Vous utilisez un mauvais fichier excel/ ou vous avez changé le nom des colonnes </p>';
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
}