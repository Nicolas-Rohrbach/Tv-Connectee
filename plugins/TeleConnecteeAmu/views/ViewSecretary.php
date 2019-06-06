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
        echo '
         <div class="cadre">
             <div align="center">
                <form method="post">
                    <label for="loginSecre">Login</label>
                    <input type="text" class="form-control text-center modal-sm" name="loginSecre" placeholder="Login" required="">
                    <label for="pwdSecre">Mot de passe</label>
                    <input type="password" class="form-control text-center modal-sm" name="pwdSecre" placeholder="Mot de passe" required="">
                    <label for="emailSecre">Email</label>
                    <input type="email" class="form-control text-center modal-sm" name="emailSecre" placeholder="Email" required="">
                  <button type="submit" name="createSecre">Créer</button>
                </form>
            </div>
         </div>';
    }

    public function displayHeaderTabSecretary(){
        $title = "Secrétaires";
        $this->displayHeaderTab($title);
        echo'<th scope="col">Login</th>
                    </tr>
                </thead>
                <tbody>';
    }

    public function displayAllSecretary($row, $id, $login){
        $tab[] = $login;
        $this->displayAll($row, $id, $tab);
    }

    public function displayErrorInsertion(){
        $this->displayStartModal('Erreur lors de l\'inscription ');
        echo '<div class="alert alert-danger"> Le login ou l\'adresse mail est déjà utilisé(e) </div>';
        $this->displayEndModal();
    }
}