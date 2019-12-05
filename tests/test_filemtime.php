<?php
/* To prevent server misconfigured */
date_default_timezone_set('Europe/Paris');

// Today
$datetimeNow = new Datetime('now');

$filename = dirname ( __FILE__ ) . '/../tmp/tmp_planning_pf5.html';
        
if (file_exists($filename)) {
    
    // http://php.net/manual/en/function.filemtime.php
    $datetimeFileModification = new Datetime(date('Y-m-d H:i:s', filemtime($filename)));
    
    echo "$filename a été modifié le : ".$datetimeFileModification->format('d/m/Y H:i:s').'<br/><br/>';
    
    $datetimeFileModification->add(new DateInterval('P5D')); // Add 5 days

    echo "Il est possible de saisir Présents + repas jusqu'au ".$datetimeFileModification->format('d/m/Y H:i:s').' (sinon masqué menu)<br/><br/>';
    
}
else { die("Fichier inexistant"); }
?>