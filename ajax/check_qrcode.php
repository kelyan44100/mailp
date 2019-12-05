<?php
require_once dirname ( __FILE__ ) . '/../domain/Enterprise.class.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

date_default_timezone_set('Europe/Paris');

$appService = AppServiceImpl::getInstance();

// Ajax data
if( isset($_POST['myInput']) && !empty($_POST['myInput']) ) {
    
    // Type forcing
    $qrcode = (string) $_POST['myInput'];
    
     // index [0] = idPurchasingFair, [1] = idEnterprise, [2] = idParticipant, [3] = lunchBreak
    $parts = explode('|', $qrcode);
    
    // If incorrect sequence of characters
    if(count($parts) != 4) { 
        echo '<div class="alert alert-danger">CODE ERREUR #01 : SÉQUENCE INCORRECTE</div>'; 
    }
    
    else {
        
        if( !(intval($parts[0]) != 0 && intval($parts[1]) != 0 && intval($parts[2]) != 0 && intval($parts[3]) == 0) ) {
            echo '<div class="alert alert-danger">CODE ERREUR #05 : SÉQUENCE INCORRECTE : INVITÉ EXCEPTIONNEL SCANNÉ ?</div>';
        }
        else {
        
        
            // Get Objects of PF / Enterprise / Participant
            $purchasingFairObject = $appService->findOnePurchasingFair($parts[0]);
            $participantObject    = $appService->findOneParticipant($parts[2]);
            $enterpriseExtracted  = $appService->findOneEnterprise($parts[1]);

            // Get all QRCode already scanned (can be empty)
            $arrayQRCodesScan = $appService->findAllQRCodeScanByTrio($parts[0], $parts[1], $parts[2]);

            // NO QRCode already scanned
            if( empty($arrayQRCodesScan) ) {

                // Get Lunch for this Enterprise
                $lunch = $appService->findLunchForOneEnterpriseAndPf($parts[1], $parts[0]);

                // Add 1 lunch canceled
                $lunch->setLunchesCanceled($lunch->getLunchesCanceled() + 1);
                $appService->updateLunch($lunch);

                // Scan saved
                $appService->saveQRCodeScan($appService->createQRCodeScan(
                        $purchasingFairObject, 
                        $enterpriseExtracted, 
                        $participantObject, 
                        ''));

                // Msg info
                echo '<div class="alert alert-warning"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> ANNULATION REPAS PRISE EN COMPTE</div>';
            }

            // At least one QRCode already scanned
            else {

                // Get last QRCode scanned
                $lastQRCodeScanned = $arrayQRCodesScan[0]; // Last QRCode scanned

                // Get datetime for today and set time to midnight
                $datetimeToday = DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
                $datetimeToday->setTime(0,0,1);

                // Get datetime QRCode and convert it
                $datetimeLastQRCode = DateTime::createFromFormat('Y-m-d H:i:s', $lastQRCodeScanned->getScanDatetime());

                // Compare these datetimes - If greater the Participant QRCode has already been scanned
                if($datetimeLastQRCode > $datetimeToday) {

                    //Msg info
                    echo '<div class="alert alert-danger">CODE ERREUR #04 : PARTICIPANT DÉJÀ SCANNÉ AUJOURD\'HUI</div>';
                }

                // If Participant QRCode has already been scanned in the previous days it is ok
                else {

                    // Get Lunch for this Enterprise
                    $lunch = $appService->findLunchForOneEnterpriseAndPf($parts[1], $parts[0]);

                    // Add 1 lunch canceled
                    $lunch->setLunchesCanceled($lunch->getLunchesCanceled() + 1);
                    $appService->updateLunch($lunch);

                    // Scan saved
                    $appService->saveQRCodeScan($appService->createQRCodeScan(
                            $purchasingFairObject, 
                            $enterpriseExtracted, 
                            $participantObject, 
                            ''));

                    // Msg info
                    echo '<div class="alert alert-warning"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> ANNULATION REPAS PRISE EN COMPTE</div>';
                }
            }

            if($enterpriseExtracted->getOneProfile()->getName() == "Magasin") {

                // Check if Participation exist
                $participation = $appService->findOneParticipation($parts[2], $parts[0]);

                if($participation != NULL) {

                    $ape = $appService->findAllAssignmentsParticipantEnterpriseForOneParticipant($parts[2]);

                    // Check if this is a participant associated with an Enterprise as Store (Magasin)
                    $enterpriseName = $ape[0]->getOneEnterprise()->getName();

                    echo '
                    <div class="alert alert-success" >
                        <h1 id="mshOk">QRCODE VALIDE</h1>
                        <h2 id="participantToString">'.$participation->getOneParticipant().'</h2>
                        <table style="width:100%">
                            <tr>
                                <td class="text-right">
                                    <span class="fa fa-id-badge m-r-xs"></span> <label>Entreprise :</label>
                                </td>
                                <td class="text-left">
                                    &nbsp;
                                    <span id="participantEnterprise">'.$enterpriseName.'</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right">
                                    <span class="fa fa-cutlery m-r-xs"></span> <label>Repas :</label>
                                </td>
                                <td class="text-left">
                                    &nbsp;
                                    <span id="participantLunch">'.( ( $participation->getLunch() ) ? 'NON' : 'NON' ).'</span>
                                </td>
                            </tr>
                        </table>
                    </div>     
                    ';
                }
                else { echo '<div class="alert alert-danger">CODE ERREUR #02 : PARTICIPANT INEXISTANT POUR LE SALON</div>'; }            
            }

            if($enterpriseExtracted->getOneProfile()->getName() == "Fournisseur") {

                $asps = $appService->findOneAssignmentSpStoreTer($parts[2], $parts[1],$parts[0]);

                if($asps != NULL) {

                    // Check if this is a participant associated with an Enterprise as Store (Magasin)
                    $enterpriseName = $asps[0]->getOneProvider()->getName();

                    echo '
                    <div class="alert alert-success" >
                        <h1 id="mshOk">QRCODE VALIDE</h1>
                        <h2 id="participantToString">'.$asps[0]->getOneParticipant().'</h2>
                        <table style="width:100%">
                            <tr>
                                <td class="text-right">
                                    <span class="fa fa-id-badge m-r-xs"></span> <label>Entreprise :</label>
                                </td>
                                <td class="text-left">
                                    &nbsp;
                                    <span id="participantEnterprise">'.$enterpriseName.'</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right">
                                    <span class="fa fa-cutlery m-r-xs"></span> <label>Repas :</label>
                                </td>
                                <td class="text-left">
                                    &nbsp;
                                    <span id="participantLunch">'.( ( 0 == 0 ) ? 'NON' : 'NON' ).'</span>
                                </td>
                            </tr>
                        </table>
                    </div>     
                    ';
                }
                else { echo '<div class="alert alert-danger">CODE ERREUR #03 : PARTICIPANT INEXISTANT POUR LE SALON</div>'; }
            }
        }
    }
}
?>