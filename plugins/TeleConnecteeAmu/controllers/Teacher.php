<?php
/**
 * Created by PhpStorm.
 * User: Rohrb
 * Date: 25/04/2019
 * Time: 11:25
 */

class Teacher extends ControllerG
{
    private $view;
    private $model;

    /**
     * Constructeur de Teacher
     */
    public function __construct(){
        $this->view = new ViewTeacher();
        $this->model = new TeacherManager();
    }

    /**
     * Insère tout les professeurs depuis un fichier excel
     * @param $actionTeacher
     */
    public function insertTeacher($actionTeacher){
        $this->view->displayInsertImportFileTeacher();
        if ($actionTeacher) {
            $allowed_extension = array("Xls", "Xlsx", "Csv");
            $extension = ucfirst(strtolower(end(explode(".", $_FILES["excelProf"]["name"]))));

            // allowed extension
            if (in_array($extension, $allowed_extension)) {
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($extension);
                $reader->setReadDataOnly(TRUE);
                $spreadsheet = $reader->load($_FILES["excelProf"]["tmp_name"]);

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
                if($cells[0] == "Numero Ent" && $cells[1] == "email" && $cells[2] == "Code") {
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
                        $codes = [$cells[2]];
                        if(isset($login) && isset($email)) {
                            if ($this->model->insertTeacher($login, $hashpass, $email, $codes)) {
                                foreach ($codes as $code) {
                                    $path = $this->getFilePath($code);
                                    if (!file_exists($path))
                                        $this->addFile($code);
                                }
                                $to = $email;
                                $subject = "Inscription à la télé-connecté";
                                $message = '
                                 <html>
                                  <head>
                                   <title>Inscription à la télé-connecté</title>
                                  </head>
                                  <body>
                                   <p>Bonjour, vous avez été inscrit sur le site de la Télé Connecté de votre département en tant qu\'enseignant</p>
                                   <p> Sur ce site, vous aurez accès à votre emploie du temps, aux informations concernant votre scolarité et vous pourrez poster des alertes.</p>
                                   <p> Votre identifiant est ' . $login . ' et votre mot de passe est ' . $pwd . '.</p>
                                   <p> Veuillez changer votre mot de passe lors de votre première connexion pour plus de sécurité !</p>
                                   <p> Pour vous connecter, rendez-vous sur le site : <a href="' . home_url() . '">.</p>
                                   <p> Nous vous souhaitons une bonne expérience sur notre site.</p>
                                  </body>
                                 </html>
                                 ';

                                // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
                                $headers[] = 'MIME-Version: 1.0';
                                $headers[] = "Content-Type: text/html; charset=UTF-8";

                                // Envoi
                                mail($to, $subject, $message, implode("\n", $headers));
                            } else {
                                array_push($doubles, $cells[0]);
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
     * Affiche tout les utilisateurs dans un tableau
     */
    public function displayAllTeachers(){
        $results = $this->model->getUsersByRole('enseignant');
        if(isset($results)){
            $this->view->displayTabHeadTeacher();
            $row = 0;
            foreach ($results as $result){
                ++$row;
                $this->view->displayAllTeacher($result, $row);
            }
            $this->view->displayEndTab();
        }
        else{
            $this->view->displayEmpty();
        }
    }

    /**
     * Modifie l'utilisateur
     * @param $result
     */
    public function modifyTeacher($result){
        $action = $_POST['modifValidate'];
        $code = [$_POST['modifCode']];
        $this->view->displayModifyTeacher($result);
        if($action === 'Valider'){
            if($this->model->modifyTeacher($result, $code)){
                $this->view->displayModificationValidate();
            }
        }
    }
}