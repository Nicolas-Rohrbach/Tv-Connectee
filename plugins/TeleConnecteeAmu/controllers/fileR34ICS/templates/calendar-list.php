<?php
/*
Version: 2.0
Author: Room 34 Creative Services, LLC & Nicolas Rohrbach & Gwenaêl ROUX
Author URI: http://room34.com
License: GPL2
Text Domain: r34ics
Domain Path: /i18n/languages/
Description: Recupère le format ical et l'affiche sous forme de tableau
on n'affiche pas les cours qui ont déja eu lieu en utilisant un timestamp de la date et heure actuel
Grace au timestamp on surligne en vert les cours qui ont lieu au moment du visionnage
Possibilite de splitté le tableau en un nombre d'évèment définis (possibilte de developpement pour creer un slide avec les tableau)
Possibilite de parametrer le nombre d'évement à afficher
*/

// Require object
if (empty($ics_data)) { return false; }

global $R34ICS;
global $wp_locale;

$days_of_week = $R34ICS->get_days_of_week();
$start_of_week = get_option('start_of_week', 0);

// Not currently used in this template; uncomment if needed in a future update
//$today = date_i18n('Ymd', current_time('timestamp'));

$date_format = !empty($args['format']) ? strip_tags($args['format']) : 'l, F j';

echo'<section class="ics-calendar'; if (!empty($args['hidetimes'])) { echo ' hide_times'; } echo'">';

// Title and description
if (isset($ics_data['title'])) {
    echo '<h2 class="ics-calendar-title">'.$ics_data['title'].'</h2>';
}
if (isset($ics_data['description'])) {
    echo '<p class="ics-calendar-description">'.$ics_data['description'].'</p>';
}

// Empty calendar message
if (empty($ics_data['events'])){
    return false;
}

// Display calendar
else {
    // Build monthly calendars
    $i = 0;
    foreach (array_keys((array)$ics_data['events']) as $year) {
        for ($m = 1; $m <= 12; $m++) {
            $month = $m < 10 ? '0' . $m : '' . $m;
            $ym = $year . $month;
            if ($ym < $ics_data['earliest']) { continue; }
            if ($ym > $ics_data['latest']) { break(2); }
            $first_date = mktime(0,0,0,$month,1,$year);
            $month_label = ucwords(date_i18n('F Y', $first_date));

            // Build month's calendar
            if (isset($ics_data['events'][$year][$month])) {

                foreach ((array)$ics_data['events'][$year][$month] as $day => $day_events) {
                    $date = mktime(0,0,0,$month,$day,$year);
                    $date_label = ucwords(date_i18n('l j F Y', $date));

                    $nbevents = 0;
                    $nboccurence = 0;
                    foreach ((array)$day_events as $time => $events) {
                        $all_day_indicator_shown = false;
                        foreach ((array)$events as $event) {

                            if(($nboccurence == 0 || $nbevents == 20)){
                                if($nbevents == 20){
                                    $nbevents = 0;
                                    echo'</tbody>
                                           </table>';
                                }
                                echo'<table class="table tabSchedule">
                                            <thead class="headerTab">
                                            <tr>
                                                <th scope="col" class="text-light text-center" width="20%">Horaire</th>
                                                <th scope="col" class="text-light text-center" width="35%">Cours</th>
                                                <th scope="col" class="text-light text-center" width="25%">Enseignant</th>
                                                <th scope="col" class="text-light text-center" width="20%">Salle</th>
                                            </tr>
                                            </thead>
                                            <tbody>';
                            }

                            ++$nboccurence;
                            // et on supprime cours qui ont déja eu lieu
                            $heure = date("H:i");
                            if (!(date("H:i",strtotime($event['fin'])) <= $heure) ){
                                if(date("H:i",strtotime($event['deb'])) <= $heure && $heure < date("H:i",strtotime($event['fin']))){
                                    ++$nbevents;
                                    echo '<tr class="table-success" scope="row">';
                                }
                                else if(date("H:i",strtotime($event['deb'])) > $heure) {
                                    ++$nbevents;
                                    echo '<tr scope="row">';
                                }
                                if ($time == 'all-day') {
                                    if (!$all_day_indicator_shown) {
                                        echo '<td class="all-day-indicator">'; _e('All Day', 'R34ICS'); echo'</td>';
                                        $all_day_indicator_shown = true;
                                    }
                                    echo '<td class="event">
                                            <span class="title">';  echo str_replace('/', '/<wbr />',$event['label']).'</span>';
                                    if (!empty($event['sublabel'])) {
                                        echo '<span class="sublabel">'; echo str_replace('/()', '/<wbr />',$event['sublabel']).'</span>';
                                    }
                                    echo '</td>';
                                }
                                else {
                                    if (!empty($event['start'])) {
                                        echo '<td class="text-center" width="20%">';
                                        $deb = date("H:i",strtotime($event['deb']));
                                        $newDeb = str_replace(':','h',$deb);
                                        echo $newDeb.' ';
                                        if (!empty($event['end'])) {
                                            echo '<span class="time">&#8211;'; $fin = date("H:i",strtotime($event['fin']));
                                            $newFin = str_replace(':','h',$fin);
                                            echo ' '.$newFin.'</span>';
                                        }
                                        echo '</td>';
                                    }
                                    echo '<td class="text-center" width="35%">
                                            <span class="title">'; echo str_replace('/', '/<wbr />',$event['label']).'</span>';
                                    if (!empty($event['sublabel'])) {
                                        echo '<span class="sublabel">';
                                        if (empty($event['start']) && !empty($event['end'])) {
                                            echo '<span class="carryover">&#10554;</span>';
                                        }
                                        echo str_replace('/', '/<wbr />',$event['sublabel']).'</span>';
                                    }
                                    echo '</td>
                                        <td class="text-center" width="25%">
												<span class="sublabel">'; $des = $event['description'];
                                    $des = substr($des,0,-29);
                                    echo $des.'</span>
                                        </td >
                                        <td class="text-center" width="20%">
                                            <span>'; echo str_replace('/', '/<wbr />',$event['location']).'</span>
                                        </td>';
                                }
                                echo '</tr>';
                            }
                            if ($nbevents == 5){
                                break(2);
                            }
                        }
                    }
                    echo '</tbody>
                        </table>';
                    break(3);
                }
                echo '</div>';
            }
        }
    }
}
echo '</section>';