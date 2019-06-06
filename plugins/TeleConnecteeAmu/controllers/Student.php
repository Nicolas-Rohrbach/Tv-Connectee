<?php
/**
 * Created by PhpStorm.
 * User: Rohrb
 * Date: 25/04/2019
 * Time: 10:33
 */

class Student extends ControllerG
{
    /**
     * View de Student
     * @var ViewStudent
     */
    private $view;

    /**
     * Model de Student
     * @var StudentManager
     */
    private $model;

    /**
     * Constructeur de Student.
     */
    public function __construct()
    {
        $this->view = new ViewStudent();
        $this->model = new StudentManager();
    }

    /**
     * Ajoute tout les étudiants présent dans un fichier excel
     * @param $actionStudent    Est à true si le bouton est préssé
     */
    public function insertStudent() {
        $actionStudent = $_POST['importEtu'];
        $this->view->displayInsertImportFileStudent();
        if ($actionStudent) {
            $allowed_extension = array("Xls", "Xlsx", "Csv");
            $extension = ucfirst(strtolower(end(explode(".", $_FILES["excelEtu"]["name"]))));
            // allowed extension
            if (in_array($extension, $allowed_extension)) {
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($extension);
                $reader->setReadDataOnly(TRUE);
                $spreadsheet = $reader->load($_FILES["excelEtu"]["tmp_name"]);

                $worksheet = $spreadsheet->getActiveSheet();
                $highestRow = $worksheet->getHighestRow();

                $row = $worksheet->getRowIterator(1, 1);
                $cells = [];
                foreach ($row as $value){
                    $cellIterator = $value->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE);
                    foreach ($cellIterator as $cell) {
                        $cells[] = $cell->getValue();
                    }
                }
                if($cells[0] == "Numero Ent" && $cells[1] == "email" && $cells[2] == "Annee" && $cells[3] == "Groupe" && $cells[4] == "Demi-groupe") {
                    $doubles = array();
                    for ($i = 2; $i < $highestRow + 1; ++$i) {
                        $cells = array();
                        foreach ($worksheet->getRowIterator($i, $i + 1) as $row) {
                            $cellIterator = $row->getCellIterator();
                            $cellIterator->setIterateOnlyExistingCells(FALSE);
                            foreach ($cellIterator as $cell) {
                                $cells[] = $cell->getValue();
                            }
                        }
                        $pwd = wp_generate_password();
                        $hashpass = wp_hash_password($pwd);
                        $login = $cells[0];
                        $email = $cells[1];
                        $codes = [$cells[2], $cells[3], $cells[4]];
                        if(isset($login) && isset($email)) {
                            if($this->model->insertStudent($login, $hashpass, $email, $codes)){
                                foreach ($codes as $code){
                                    $path = $this->getFilePath($code);
                                    if(! file_exists($path))
                                        $this->addFile($code);
                                }

                                $to  = $email;
                                $subject = "Inscription à la télé-connecté";
                                $message = '
                                 <html>
                                  <head>
                                   <title>Inscription à la télé-connecté</title>
                                  </head>
                                  <body>
                                   <p>Bonjour, vous avez été inscrit sur le site de la Télé Connecté de votre département en tant qu\'étudiant</p>
                                   <p> Sur ce site, vous aurez accès à votre emploie du temps, à vos notes et aux informations concernant votre scolarité.</p>
                                   <p> Votre identifiant est '.$login.' et votre mot de passe est '.$pwd.'.</p>
                                   <p> Veuillez changer votre mot de passe lors de votre première connexion pour plus de sécurité !</p>
                                   <p> Pour vous connecter, rendez-vous sur le site : <a href="'.home_url().'"> '.home_url().' </a>.</p>
                                   <p> Nous vous souhaitons une bonne expérience sur notre site.</p>
                                  </body>
                                 </html>
                                 ';

                                // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
                                $headers[] = 'MIME-Version: 1.0';
                                $headers[] = "Content-Type: text/html; charset=UTF-8";

                                // Envoi
                                mail($to, $subject, $message, implode("\n", $headers));
                            }
                            else {
                                array_push($doubles, $login);
                            }
                        }
                    }
                    if(! is_null($doubles[0])) {
                        $this->view->displayErrorDouble($doubles);
                    } else {
                        $this->view->displayInsertValidate();
                    }
                }
                else {
                    $this->view->displayWrongFile();
                }
            } else {
                $this->view->displayWrongExtension();
            }
        }
    }

    /**
     * Affiche tout les étudiants dans un tableau
     */
    function displayAllStudents(){
        $results = $this->model->getUsersByRole('etudiant');
        if(isset($results)){
            $this->view->displayTabHeadStudent();
            $row = 0;
            foreach ($results as $result){
                ++$row;
                $id = $result['ID'];
                $login = $result['user_login'];
                $code = unserialize($result['code']);
                $year = $this->model->getTitle($code[0]);
                $group = $this->model->getTitle($code[1]);
                $halfgroup = $this->model->getTitle($code[2]);
                $this->view->displayAllStudent($id, $login, $year, $group, $halfgroup, $row);
            }
            $this->view->displayRedSignification();
            $this->view->displayEndTab();
        }
        else{
            $this->view->displayEmpty();
        }
    }

    /**
     * Modifie l'étudiant sélectionné
     * @param $result   Données de l'étudiant avant modification
     */
    public function modifyMyStudent($result){
        $years = $this->model->getCodeYear();
        $groups = $this->model->getCodeGroup();
        $halfgroups = $this->model->getCodeHalfgroup();
        $this->view->displayModifyStudent($result, $years, $groups, $halfgroups);

        $action = $_POST['modifvalider'];
        $year = filter_input(INPUT_POST,'modifYear');
        $group = filter_input(INPUT_POST,'modifGroup');
        $halfgroup = filter_input(INPUT_POST,'modifHalfgroup');

        $codes = [$year, $group, $halfgroup];
        if($action == 'Valider'){
            if($this->model->modifyStudent($result['ID'], $codes)){
                $this->view->displayModificationValidate();
            }
        }
    }
}