<?php
/**
 * Plugin Name: TvConnecteeAmu
 * Description: Plugin de la télé connectée de l'AMU, ce plugin permet de générer des fichiers ICS. Ces fichiers sont ensuite lus pour pouvoir afficher l'emploi du temps de la personne connectée. Ce plugin permet aussi d'afficher la météo, des informations, des alertes. Tant en ayant une gestion des utilisateurs et des informations.
 * Version: 2.5.3
 * Author: Alexis Sola & Nicolas Rohrbach & Gwenaêl Roux
 * Author URI: http://tvconnectee.alwaysdata.net/
*/

include_once 'controllers/ControllerG.php';
include_once 'models/Model.php';
include_once 'views/ViewG.php';

include_once 'controllers/CodeAde.php';
include_once 'models/CodeAdeManager.php';
include_once 'views/ViewCodeAde.php';

include_once 'controllers/Student.php';
include_once 'models/StudentManager.php';
include_once 'views/ViewStudent.php';

include_once 'controllers/Teacher.php';
include_once 'models/TeacherManager.php';
include_once 'views/ViewTeacher.php';

include_once 'controllers/Television.php';
include_once 'models/TelevisionManager.php';
include_once 'views/ViewTelevision.php';

include_once 'controllers/Secretary.php';
include_once 'models/SecretaryManager.php';
include_once 'views/ViewSecretary.php';

include_once 'controllers/ManagementUsers.php';
include_once 'views/ViewManagementUsers.php';

include_once 'controllers/MyAccount.php';
include_once 'models/MyAccountManager.php';
include_once 'views/ViewMyAccount.php';

include_once 'models/DAO/DAOUser.php';
include_once 'models/DAO/DAOStudent.php';
include_once 'models/DAO/DAOTeacher.php';

include_once 'controllers/R34ICS.php';
include_once 'controllers/Schedule.php';
include_once 'views/ViewSchedule.php';
include_once 'widgets/WidgetSchedule.php';

include_once 'controllers/Weather.php';
include_once 'views/ViewWeather.php';
include_once 'widgets/WidgetWeather.php';

include_once 'controllers/Information.php';
include_once 'models/InformationManager.php';
include_once 'views/ViewInformation.php';
include_once 'widgets/WidgetInformation.php';

include_once 'controllers/Alert.php';
include_once 'models/AlertManager.php';
include_once 'views/ViewAlert.php';
include_once 'widgets/WidgetAlert.php';

require ('models/Excel/vendor/autoload.php');

//Users
$student = new Student();
$teacher = new Teacher();
$television = new Television();
$secretary = new Secretary();
$myAccount = new MyAccount();

$information = new Information();
$alert = new Alert();

$managementUsers = new ManagementUsers();

$code = new CodeAde();

$schedule = new Schedule();
//Function for Schedule
add_action('displaySchedule',array($schedule,'displaySchedules'));
add_action('displayYear_schedule', array($schedule, 'displayYearSchedule'));

//All functions for users
add_action('add_student', array($student, 'insertStudent'), 0, 1);
add_action('add_teacher', array($teacher, 'insertTeacher'), 0, 1);
add_action('add_television', array($television, 'insertTelevision'), 0, 7);
add_action('add_secretary', array($secretary, 'insertSecretary'));

add_action('displayManagementUsers', array($managementUsers, 'displayUsers'), 0, 1);
add_action('modify_user', array($managementUsers, 'ModifyUser'));
add_action('modify_my_password', array($myAccount, 'modifyPwd'));
add_action('delete_users', array($managementUsers, 'deleteUsers'), 0, 1);
add_action('delete_my_account', array($myAccount, 'deleteAccount'));

//All functions for code ADE
add_action('add_code_ade', array($code, 'insertCode'));
add_action('display_all_codes', array($code, 'displayAllCodes'));
add_action('modify_code_ade', array($code, 'modifyCode'));
add_action('delete_codes', array($code, 'deleteCodes'), 0, 1);

add_action('handleInfos',array($information,'informationManagement'));
add_action('delete_infos',array($information, 'deleteInformations'),0 ,1);
add_action('modify_info',array($information,'modifyInformation'));
//add_action('displayInformations',array($information,'informationMain'));
add_action('add_info',array($information,'insertInformation'), 0, 7);

add_action('createAlert',array($alert,'createAlert'),0,3);
add_action('handleAlert', array($alert,'alertsManagement'));
add_action('delete_alert', array($alert,'deleteAlert'), 0 ,1);
add_action('modify_alert',array($alert,'modifyAlert'));

add_action('test', array($alert,'test'));
// Initialize plugin
add_action('init', function(){
    global $R34ICS;
    $R34ICS = new R34ICS();
});

add_action( 'downloadFileICS', 'downloadFileICS_func' );
function downloadFileICS_func() {
    $model = new CodeAdeManager();
    $allCodes = $model->getAllCode();
    $controllerAde = new CodeAde();
    foreach ($allCodes as $code){
        $path = $controllerAde->getFilePath($code['code']);
        $controllerAde->addFile($code['code']);
        if(file_get_contents($path) == '')
            $controllerAde->addFile($code['code']);
    }
}

function wpdocs_plugin_teleconnecteeAmu_scripts() {
    wp_enqueue_style('plugin-bootstrap-style', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css', array(), true);
    wp_enqueue_style('weather-style', '/wp-content/plugins/TeleConnecteeAmu/views/css/weather.css', array(), true);
    wp_enqueue_style('alert-style', '/wp-content/plugins/TeleConnecteeAmu/views/css/alert.css', array(), true);
    wp_enqueue_style('info-style', '/wp-content/plugins/TeleConnecteeAmu/views/css/information.css', array(), true);
    wp_enqueue_style('schedule-style', '/wp-content/plugins/TeleConnecteeAmu/views/css/schedule.css', array(), true);
    wp_enqueue_script( 'theme-jquery', get_template_directory_uri() . '/assets/js/jquery-3.3.1.min.js', array (), '', false);
    wp_enqueue_script( 'theme-jqueryUI', get_template_directory_uri() . '/assets/js/jquery-ui.min.js', array ( 'jquery' ), '', false);
    wp_enqueue_script( 'theme-jqueryEzTic', '/wp-content/plugins/TeleConnecteeAmu/views/js/jquery.easy-ticker.js', array ( 'jquery' ), '', false);
    wp_enqueue_script( 'plugin-addCheckBox', '/wp-content/plugins/TeleConnecteeAmu/views/js/addAllCheckBox.js', array ( 'jquery' ), '', false);
    wp_enqueue_script( 'plugin-addCodeTv', '/wp-content/plugins/TeleConnecteeAmu/views/js/addOrDeleteTvCode.js', array ( 'jquery' ), '', false);
    wp_enqueue_script( 'plugin-addCodeAlert', '/wp-content/plugins/TeleConnecteeAmu/views/js/addOrDeleteAlertCode.js', array ( 'jquery' ), '', false);
    wp_enqueue_script( 'plugin-marquee', '/wp-content/plugins/TeleConnecteeAmu/views/js/jquery.marquee.js', array ( 'jquery' ), '', false);
    wp_enqueue_script( 'plugin-slideshow', '/wp-content/plugins/TeleConnecteeAmu/views/js/slideshow.js', array ( 'jquery' ), '', true);
    wp_enqueue_script( 'plugin-showModal', '/wp-content/plugins/TeleConnecteeAmu/views/js/modal.js', array ( 'jquery' ), '', true);
    wp_enqueue_script( 'plugin-ticker', '/wp-content/plugins/TeleConnecteeAmu/views/js/jquery.tickerNews.js', array ( 'jquery' ), '', true);
    wp_enqueue_script( 'plugin-alertTicker', '/wp-content/plugins/TeleConnecteeAmu/views/js/alertTicker.js', array ( 'jquery' ), '', true);
}
add_action( 'wp_enqueue_scripts', 'wpdocs_plugin_teleconnecteeAmu_scripts' );
