<?php

require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php';

$appService = AppServiceImpl::getInstance();

// Generate passwords for all Enterprises (SQL + CSV File for clear passwords)
// http://php.net/manual/fr/function.fputcsv.php
$arrayEnterprises = $appService->findAllEnterprises();
$nbEnterprises = count($arrayEnterprises);
$arrayPasswords = $appService->generateNPasswords($nbEnterprises);

$file = fopen('./doc/passwords.csv', 'w+');
fputcsv($file, array('NUMERO UNIQUE', 'PROFIL', 'ENTREPRISE', 'MOT DE PASSE'), ';'); // Headers

for( $i = 0 ; $i < $nbEnterprises ; $i++) {
    
    // SQL update query
    echo  'UPDATE enterprise SET password_enterprise = UNHEX(SHA1("'.$arrayPasswords[$i].'")) WHERE id_enterprise = '.$arrayEnterprises[$i]->getIdEnterprise().';<br/>';
    // CSV file
    fputcsv($file, 
            array(
                $arrayEnterprises[$i]->getIdEnterprise(),
                $arrayEnterprises[$i]->getOneProfile()->getName(),
                $arrayEnterprises[$i]->getName(),
                $arrayPasswords[$i]
            ), 
            ';');
}

fclose($file);
?>