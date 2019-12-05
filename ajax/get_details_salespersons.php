<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

$appService = AppServiceImpl::getInstance();

// https://www.tutorialrepublic.com/faq/how-to-remove-empty-values-from-an-array-in-php.php
http://php.net/manual/fr/function.json-decode.php
$arraySalespersons = array_filter( json_decode( $_POST['idSalespersons'], true) );
$limit             = count($arraySalespersons);
$result            = '';
$counterIds        = 0;

foreach($arraySalespersons as $idSalesperson => $totLunches) {
    $counterIds++;
    $result .= $appService->findOneParticipant($idSalesperson);
    $result .= ' : '.$arraySalespersons[$idSalesperson]['totLunches'].' repas<br/>';
    
    // Added 29/05/2018
    $daysExploded       = explode( '|', $arraySalespersons[$idSalesperson]['detailsDays'] );
    $counterDetailsDays = count($daysExploded);

    for( $i = 0 ; $i < $counterDetailsDays ; $i++) {
        $result .= '<em>'.$daysExploded[$i].'</em>';
        $result .= ( $i < ( $counterDetailsDays - 1 ) ) ? '<br/>' : '';
    }
    
    $result .= ( ( $counterIds < $limit ) ? '<br/>' : '' );
}

// Print the result containing all the Participants as a string (thanks to __toString() method)
echo $result;
?>