<?php
/**
 * Created by PhpStorm.
 * User: Rohrb
 * Date: 26/04/2019
 * Time: 11:03
 */

class ViewManagementUsers extends ViewG
{
    public function displayButtonChoise(){
        return '
        <form method="post">
          <label for="students">Étudiants</label>
          <input type="radio" name="seeUsers" id="students" value="students">
          <label for="teachers">Enseignants</label>
          <input type="radio" name="seeUsers" id="teachers" value="teachers">
          <label for="secretarys">Secrétaires</label>
          <input type="radio" name="seeUsers" id="secretarys" value="secretarys">
          <label for="technicians">Techniciens</label>
          <input type="radio" name="seeUsers" id="technicians" value="technicians">
          <label for="televisions">Télévisions</label>
          <input type="radio" name="seeUsers" id="televisions" value="televisions">
          <input type="submit" value="Sélectionner">
        </form>';
    }
}