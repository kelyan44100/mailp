<?php
require_once dirname ( __FILE__ ) . '/view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

// Not connected as Admin ?
if(!isset($_SESSION['adminConnected']) && empty($_SESSION['adminConnected'])) {
    header('Location: ./disconnection.php'); // Redirection to Purchasing Fair list
}

header( 'content-type: text/html; charset=utf-8' ); // Specifies to the server to return UTF-8 - put in prod

date_default_timezone_set('Europe/Paris');

$appService = AppServiceImpl::getInstance();

// Added 27.08.2018 - Taking others Pf into account
$isOtherPf = ( $_SESSION['purchasingFairConcerned']->getOneTypeOfPf()->getNameTypeOfPf() === 'Autre' ) ? true : false;

if(isset($_POST) && !empty($_POST) && isset( $_SESSION['purchasingFairConcerned'] ) && !empty( $_SESSION['purchasingFairConcerned'] )) {
    
    // Get content from templates files - Added 15.06.2018
    $contentEmailProviderFromFileBody = "";
    $contentEmailProviderFromFileAltBody = "";
    $emailTemplateProviderFile = fopen(dirname ( __FILE__ ) . '/templates_emails/template_email_provider.txt', 'r');
    if ($emailTemplateProviderFile) {
        while (($buffer = fgets($emailTemplateProviderFile)) !== false) { 
            $contentEmailProviderFromFileBody .= nl2br($buffer);
            $contentEmailProviderFromFileAltBody .= $buffer;
        }
        fclose($emailTemplateProviderFile);
    }
    
    $contentEmailStoreFromFileBody = "";
    $contentEmailStoreFromFileAltBody = "";
    $emailTemplateStoreFile = fopen(dirname ( __FILE__ ) . '/templates_emails/template_email_store.txt', 'r');
    if ($emailTemplateStoreFile) {
        while (($buffer = fgets($emailTemplateStoreFile)) !== false) { 
            $contentEmailStoreFromFileBody .= nl2br($buffer);
            $contentEmailStoreFromFileAltBody .= $buffer;
        }
        fclose($emailTemplateStoreFile);
    }
    
    // Reset passwords for all Providers
//    $arrayProviders         = $appService->findAllEnterprisesAsProviders();
//    $limitArrayProviders    = count($arrayProviders);
//    $arrayPasswordProviders = $appService->generateNPasswords($limitArrayProviders);
//    for( $n = 0 ; $n < $limitArrayProviders ; $n++) {
//        $arrayProviders[$n]->setPassword($arrayPasswordProviders[$n]);
//        $appService->saveEnterprise($arrayProviders[$n]);
//    }
    
    
    
    
    
    // Excel export
    $excel = '
    <table id="tablePasswords" style="width:100%;border:1px solid #000000;display:none">
        <tr style="width:100%">
            <th style="border:1px solid #000000">NUMERO UNIQUE</th>
            <th style="border:1px solid #000000">PROFIL</th>
            <th style="border:1px solid #000000">ENTREPRISE</th>
            <th style="border:1px solid #000000">MOT DE PASSE</th>
        </tr>'; // Table not visible in source code
    
    // Mails for Providers Present
    if(isset($_POST['msgProvider']) && !empty($_POST['msgProvider'])){

        // Providers Present
        $arrayPP      = $appService->findAllProviderPresentForOnePurchasingFair($_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
        $totProviders = count($arrayPP);

        // Total mails to send
        $counterMailsToSend = $totProviders;

        // Passwords generation
        $arrayPasswordsPP = $appService->generateNPasswords($totProviders);

        // Check sending
        $counterSuccesfulSendingsProvider = 0;

        for( $i = 0 ; $i < $totProviders ; $i++) {
            $arrayPP[$i]->getOneProvider()->setPassword($arrayPasswordsPP[$i]);
            $appService->saveEnterprise($arrayPP[$i]->getOneProvider());
                        
            $excel .= '<tr style="width:100%">
                <td style="border:1px solid #000000">'.$arrayPP[$i]->getOneProvider()->getIdEnterprise().'</td>
                <td style="border:1px solid #000000">'.$arrayPP[$i]->getOneProvider()->getOneProfile()->getName().'</td> 
                <td style="border:1px solid #000000">'.$arrayPP[$i]->getOneProvider()->getName().'('.$arrayPP[$i]->getOneProvider()->getOneTypeOfProvider()->getNameTypeOfProvider()[0].')</td>
                <td style="border:1px solid #000000">'.$arrayPasswordsPP[$i].'</td></tr>';

            $enterpriseContact = $appService->findOneEnterpriseContactByEnterprise($arrayPP[$i]->getOneProvider()->getIdEnterprise());

            if( !empty($enterpriseContact)) {

                $recipientAddress = $enterpriseContact->getEmail(); // 'test@scaouest.fr'
                $recipientName    = $enterpriseContact->getCivility().' '.$enterpriseContact->getSurname().' '.$enterpriseContact->getName();

                $recipient = array( 'recipientAddress' => $recipientAddress, 'recipientName' => $recipientName);

                $attachments = ($isOtherPf) ? array() : array('./doc/guides/provider_guide.pdf');

                $subject = 'Information importante - '.$_SESSION['purchasingFairConcerned']->getNamePurchasingFair();
                
                $body = str_replace('$$PASSWORD$$', $arrayPasswordsPP[$i], $contentEmailProviderFromFileBody);
                
                if($isOtherPf) {
                    $body  = 'Bonjour,<br/><br/>';
                    $body .= 'Vous pouvez dès à présent saisir les informations pour le salon d\'achats mentionné en objet, ';
                    $body .= 'qui commence le '.$_SESSION['purchasingFairConcerned']->getStartDatetime().' et se termine le '.$_SESSION['purchasingFairConcerned']->getEndDatetime().'.<br/><br/>';
                    $body .= '<a href="http://www.scaouest.info/pf_management/">Cliquez ici pour accéder à l\'application dédiée</a>, sélectionnez votre nom de '.$enterpriseContact->getOneEnterprise()->getOneProfile()->getName().' dans la liste et saissez le mot de passe suivant :';
                    $body .= '&nbsp;'.$arrayPasswordsPP[$i].'<br/><br/>';
                    $body .= 'La clôture des inscriptions aura lieu le <strong>'.$_SESSION['purchasingFairConcerned']->getRegistrationClosingDateFournisseur().'</strong>. Passé ce délai, il faudra contacter le Service SCA Ouest concerné pour toute modification.<br/><br/>';
                    $body .= '<em>Cet email est destiné à '.$enterpriseContact->getCivility().' '.$enterpriseContact->getSurname().' '.$enterpriseContact->getName().' du '.$enterpriseContact->getOneEnterprise()->getOneProfile()->getName().' '.$enterpriseContact->getOneEnterprise()->getName().'.';
                    $body .= '&nbsp; Si ce n\'est pas vous, merci de bien vouloir en informer le Service SCA Ouest concerné.</em>';
                    $body .= '<p style="color:#36a629;font-family:Arial,Helvetica,sans-serif;"> 
                            <img src="data:image/jpeg;base64,
                            iVBORw0KGgoAAAANSUhEUgAAAT8AAAAnCAMAAABOir/bAAABFFBMVEUafQ0hmxMpYiAroR4vhyQ2
                            pik2plM2pnk2tVM2tZ02xJ02xL5BqjVJrj5NsEJSsUZWtExdpihdplNdtShdtlJdxL5d1N9kj11l
                            uVttvWR5wnB/g3yApiiAtZ2AxCiA4r6A4v+CxnqJyoKQzYiUz42Y0JGc0pafopyitSiixCii1FOi
                            1Hmi4p2i8f+k1Z2y3Ky33rK8v7nBxCjBxFPB8Z3B8f/B/77B/9/B///C477Eu8XJ5sXQytLd79rg
                            1uDh1FPh1Hnh4nnh/77h/9/h///n9Obr6ez3/Pf67fv6/fn79Pz7/Pr8/Pz8/vz9/fz9/v7+/P3+
                            /f7+/v3+/v7/4nn/8Z3/8b7//P///v///77//9////82E3HrAAAFMElEQVRo3u2aDXsaRRCA74MN
                            oZC0RLwEgxWoLalKlNNWjLSntmpSWjDhqJD9///DmdmP2ztIuRgen1N3Q+5ud+dmZ19m9itxuE13
                            SY5FYPlZfpbf/5vf5WyxpvJyuuCXllEOfoub6t9Zfrni94cHa9ML63/5+DVcXyTGGN0o43awbtmC
                            QrbTv3Nj1yfl4dYsX7Yqr4vDr4ngILmO4zKd/K62dNm6O8Dt8Jt/XFx+3vHZ2bHnr+HHI9YuRMTM
                            69XC8vNG+DzybuKHkUy+WHnKIHt9IrN0m9eTKM8IhuB3y1YV/W/Z2q2Xh/gmlYl6dU9U3QOBz1pm
                            mZSRb6IzV3nB+Lk9Pr3iMz5QMZyKX+x75bX43enjHZDCR5WCZMwC7RumYAwcYuw76gDEqExlTDn1
                            OwfE8/pOX8ikdSn/uz4JisXPd5wBn/LJhL/o9Uq+yU/OH4QnEt3ArkWsqqBF6Hh6fMsKgu+hDyoc
                            oh4uSb24q/eQUbZM5RU/HhaMn3c2GPEJn80ge+7tMz8dvxxDmFJbdQVjCV1QlGK97FJWkIeVX1oB
                            1/xoLF3DT71n8svqSvi1C8UPgpfzP/iMssfug86Rz9wurv80PzM8pa/FrKpKjRF9RXB+8NVBn+fz
                            PzlHZMtW+BVq/df0/R7/c3G1mKH/vXRg/dds+H6aX7a7IXpKkDiZXuFkBXGmAR2aXzL+peVMRjfV
                            4VhQxPnXP6QdL/rfjPdgAnE7GL9T01IcCcW0qeNXzr/oYzj/MiGaEZShrfnxZP5Nc9aqEn4rukIx
                            yRdu/VIa8Hf0/Bs4ICxh/KN9iN+F3aPlXb84ZxzDdfBS8mvuuV1LKC+/mgMTyHTKRz1ww2MH+HVw
                            /LP+lzt+z+mpR+Fb63Yavhz/bNrMDxYrpcPzN29+7XinI9gB1073vuni+mVmGeUd/9zS4WHJ8fyS
                            w9zGoyfsiVw///dSvIXDuFV+NVj0lbwSPLiPPm82umr9R7tXWO1V5wfrml1fmuMg5aC/8VVTIEyv
                            m7/t89sYkxLfvHXJ26ns+R/wq8Ft//FRt0nj3xXygxMA5Lf9s6jboE+3f0sX2p7HfZifh/4Hc+8n
                            p/fF/hfm37j8RRv4RQF0N9r5iN3DzUQMD3iwwsrfGaWwuIWFciy2w7QPhk5TGeaUlDiR0v4Hi+nd
                            OrawbLW1MK6wqwow6YkCUUk1cEEiIS22AzTjuVKaNab8kJW/F+L0PJRtGMaJxsRVG4dKh8qcvPyY
                            OHhh8iSf5o+4/PzTn3ETQcaBXti1tWM8EQkwtM3SSEDgkTjOQhldxrWUqNL8ItywSX5aGKreP5P8
                            hB4ShLuoEQ4F7LA0Fl+XUJoxBgvJXClglOv2dGNw1XpQqTZn4/zrwnkL/jBxFR/4+8cU+Q1/+lLx
                            A2OFQfg9VtEsszRE9oF2G6Ioy6j7QgqLTH7okZKfFkYZ4KoEOE80UY0MyLDyCs4BKaOUZoyJFS1O
                            PdFtpIwjlclV8INXEnM28Pt6z0j76qH2I80f2OoKPzxRCrL8ZE/lUBXjCWigep/wCzH8V/zPVCB3
                            223tf3RaGiX7cAkEGsdvlrqqlGaMWeWng8M0TrgZ0lV68JVo0x5b///B5GI8nkzgOhmPL8YX8Bm/
                            /f2SS3400GT87yGNgml+4kQhVucIEd7lKYPBD+phwMuMfzDu7CoFcvyjYYgrPapM1tSlA9JRGB3u
                            SqVZY0SE1sX4h8+yDcM486r1oFJtzkZ+t15CbfFPkerb/xevny2/f5ifTZaf5Wf5WX6Wn01/O/0F
                            EIMkX0dvhQ8AAAAASUVORK5CYII=" 
                            alt="Pensez environnement ! N\'imprimez ce mail que si c\'est vraiment nécessaire."/>
                            </p>';
                }

                // http://www.subclosure.com/how-to-set-new-line-breaks-wiht-phpmailer-for-text-only-emails.html
                $altBody = str_replace('$$PASSWORD$$', $arrayPasswordsPP[$i], $contentEmailProviderFromFileAltBody);
                
                if($isOtherPf) {
                    $altBody  = "Bonjour,\n\n";
                    $altBody .= "Vous pouvez dès à présent saisir les informations pour le salon d'achats mentionné en objet, ";
                    $altBody .= "qui commence le ".$_SESSION['purchasingFairConcerned']->getStartDatetime()." et se termine le ".$_SESSION['purchasingFairConcerned']->getEndDatetime().".\n\n";
                    $altBody .= "Rendez-vous sur http://www.scaouest.info/pf_management/ pour accéder à l'application dédiée, sélectionnez votre nom de ".$enterpriseContact->getOneEnterprise()->getOneProfile()->getName()." dans la liste et saissez le mot de passe suivant :";
                    $altBody .= " ".$arrayPasswordsPP[$i]."\n\n";
                    $altBody .= "La clôture des inscriptions aura lieu le ".$_SESSION['purchasingFairConcerned']->getRegistrationClosingDateFournisseur().". Passé ce délai, il faudra contacter le Service SCA Ouest concerné pour toute modification.\n\n";
                    $altBody .= "Cet email est destiné à ".$enterpriseContact->getCivility()." ".$enterpriseContact->getSurname()." ".$enterpriseContact->getName()." du ".$enterpriseContact->getOneEnterprise()->getOneProfile()->getName()." ".$enterpriseContact->getOneEnterprise()->getName().".";
                    $altBody .= " Si ce n'est pas vous, merci de bien vouloir en informer le Service SCA Ouest concerné.\n\n";
                    $altBody .= "Pensez environnement ! N'imprimez ce mail que si c'est vraiment nécessaire.";
                }

                $successfulSending = $appService->sendMail(new MyEmail($recipient, $attachments, $subject, $body, $altBody));

                if($successfulSending) {
                    $counterSuccesfulSendingsProvider++;
                    // http://php.net/manual/fr/function.file-put-contents.php
                    file_put_contents('./errors/log_emails.txt', '['.date('d-M-Y H:i:s e').'] EMAIL ENVOYÉ : '.$enterpriseContact->getOneEnterprise()->getOneProfile()->getName().' '.$enterpriseContact->getOneEnterprise()->getName()."\n", FILE_APPEND);
                
                    // Save data in CSV file - Added 30/05/2018
                    $csvData = $arrayPP[$i]->getOneProvider()->getIdEnterprise().';'.date('Y-m-d H:i:s');
                    file_put_contents('./tmp/tmp_check_emails_pf_provider'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'.csv', $csvData."\n", FILE_APPEND);                
                }
                else {
                    file_put_contents('./errors/log_emails.txt', '['.date('d-M-Y H:i:s e').'] EMAIL NON ENVOYÉ #ERR-01 : '.$enterpriseContact->getOneEnterprise()->getOneProfile()->getName().' '.$enterpriseContact->getOneEnterprise()->getName()."\n", FILE_APPEND);
                }
            }
            else {
                file_put_contents('./errors/log_emails.txt', '['.date('d-M-Y H:i:s e').'] EMAIL NON ENVOYÉ #ERR-02 : '.$arrayPP[$i]->getOneProvider()->getOneProfile()->getName().' '.$arrayPP[$i]->getOneProvider()->getName()."\n", FILE_APPEND);
            }
        }

        $_SESSION['counterSuccesfulSendingsProvider'] = $counterSuccesfulSendingsProvider;

    }
    

    // Mails for Stores
    if(isset($_POST['msgStore']) && !empty($_POST['msgStore'])){

        // Stores
        $arrayStores = $appService->findAllEnterprisesAsStores();
        $totStores   = count($arrayStores);
            
        // Total mails to send
        $counterMailsToSend = $totStores;
        
        // Passwords generation
        $arrayPasswordsPP = $appService->generateNPasswords($totProviders);
        
        // Check sending
        $counterSuccesfulSendingsStore = 0;

        for( $j = 0 ; $j < $totStores ; $j++) {
                        
            $excel .= '<tr style="width:100%">
                <td style="border:1px solid #000000">'.$arrayStores[$j]->getIdEnterprise().'</td>
                <td style="border:1px solid #000000">'.$arrayStores[$j]->getOneProfile()->getName().'</td> 
                <td style="border:1px solid #000000">'.$arrayStores[$j]->getName().'</td>
                <td style="border:1px solid #000000">'.$arrayStores[$j]->getPanel().'</td></tr>';

            $enterpriseContact = $appService->findOneEnterpriseContactByEnterprise($arrayStores[$j]->getIdEnterprise());

            if( !empty($enterpriseContact)) {

                $recipientAddress = $enterpriseContact->getEmail(); // 'test@scaouest.fr'
                $recipientName    = $enterpriseContact->getCivility().' '.$enterpriseContact->getSurname().' '.$enterpriseContact->getName();

                $recipient = array( 'recipientAddress' => $recipientAddress, 'recipientName' => $recipientName);

                $attachments = ($isOtherPf) ? array() : array('./doc/guides/store_guide.pdf');

                $subject = 'Information importante - '.$_SESSION['purchasingFairConcerned']->getNamePurchasingFair();
                
                $body = str_replace('$$PASSWORD$$', $arrayStores[$j]->getPanel(), $contentEmailStoreFromFileBody);
                
                if($isOtherPf) {
                    $body  = 'Bonjour,<br/><br/>';
                    $body .= 'Vous pouvez dès à présent saisir les informations pour le salon d\'achats mentionné en objet, ';
                    $body .= 'qui commence le '.$_SESSION['purchasingFairConcerned']->getStartDatetime().' et se termine le '.$_SESSION['purchasingFairConcerned']->getEndDatetime().'.<br/><br/>';
                    $body .= '<a href="http://205.0.211.85/pf_management/">Cliquez ici pour accéder à l\'application dédiée</a>, sélectionnez votre nom de '.$enterpriseContact->getOneEnterprise()->getOneProfile()->getName().' dans la liste et saissez le mot de passe suivant :';
                    $body .= '&nbsp;'.$arrayStores[$j]->getPanel().'<br/><br/>';
                    $body .= 'La clôture des inscriptions aura lieu le <strong>'.$_SESSION['purchasingFairConcerned']->getRegistrationClosingDateMagasin().'</strong>. Passé ce délai, il faudra contacter le Service SCA Ouest concerné pour toute modification.<br/><br/>';
                    $body .= '<em>Cet email est destiné à '.$enterpriseContact->getCivility().' '.$enterpriseContact->getSurname().' '.$enterpriseContact->getName().' du '.$enterpriseContact->getOneEnterprise()->getOneProfile()->getName().' '.$enterpriseContact->getOneEnterprise()->getName().'.';
                    $body .= '&nbsp; Si ce n\'est pas vous, merci de bien vouloir en informer le Service SCA Ouest concerné.</em>';
                    $body .= '<p style="color:#36a629;font-family:Arial,Helvetica,sans-serif;"> 
                            <img src="data:image/jpeg;base64,
                            iVBORw0KGgoAAAANSUhEUgAAAT8AAAAnCAMAAABOir/bAAABFFBMVEUafQ0hmxMpYiAroR4vhyQ2
                            pik2plM2pnk2tVM2tZ02xJ02xL5BqjVJrj5NsEJSsUZWtExdpihdplNdtShdtlJdxL5d1N9kj11l
                            uVttvWR5wnB/g3yApiiAtZ2AxCiA4r6A4v+CxnqJyoKQzYiUz42Y0JGc0pafopyitSiixCii1FOi
                            1Hmi4p2i8f+k1Z2y3Ky33rK8v7nBxCjBxFPB8Z3B8f/B/77B/9/B///C477Eu8XJ5sXQytLd79rg
                            1uDh1FPh1Hnh4nnh/77h/9/h///n9Obr6ez3/Pf67fv6/fn79Pz7/Pr8/Pz8/vz9/fz9/v7+/P3+
                            /f7+/v3+/v7/4nn/8Z3/8b7//P///v///77//9////82E3HrAAAFMElEQVRo3u2aDXsaRRCA74MN
                            oZC0RLwEgxWoLalKlNNWjLSntmpSWjDhqJD9///DmdmP2ztIuRgen1N3Q+5ud+dmZ19m9itxuE13
                            SY5FYPlZfpbf/5vf5WyxpvJyuuCXllEOfoub6t9Zfrni94cHa9ML63/5+DVcXyTGGN0o43awbtmC
                            QrbTv3Nj1yfl4dYsX7Yqr4vDr4ngILmO4zKd/K62dNm6O8Dt8Jt/XFx+3vHZ2bHnr+HHI9YuRMTM
                            69XC8vNG+DzybuKHkUy+WHnKIHt9IrN0m9eTKM8IhuB3y1YV/W/Z2q2Xh/gmlYl6dU9U3QOBz1pm
                            mZSRb6IzV3nB+Lk9Pr3iMz5QMZyKX+x75bX43enjHZDCR5WCZMwC7RumYAwcYuw76gDEqExlTDn1
                            OwfE8/pOX8ikdSn/uz4JisXPd5wBn/LJhL/o9Uq+yU/OH4QnEt3ArkWsqqBF6Hh6fMsKgu+hDyoc
                            oh4uSb24q/eQUbZM5RU/HhaMn3c2GPEJn80ge+7tMz8dvxxDmFJbdQVjCV1QlGK97FJWkIeVX1oB
                            1/xoLF3DT71n8svqSvi1C8UPgpfzP/iMssfug86Rz9wurv80PzM8pa/FrKpKjRF9RXB+8NVBn+fz
                            PzlHZMtW+BVq/df0/R7/c3G1mKH/vXRg/dds+H6aX7a7IXpKkDiZXuFkBXGmAR2aXzL+peVMRjfV
                            4VhQxPnXP6QdL/rfjPdgAnE7GL9T01IcCcW0qeNXzr/oYzj/MiGaEZShrfnxZP5Nc9aqEn4rukIx
                            yRdu/VIa8Hf0/Bs4ICxh/KN9iN+F3aPlXb84ZxzDdfBS8mvuuV1LKC+/mgMTyHTKRz1ww2MH+HVw
                            /LP+lzt+z+mpR+Fb63Yavhz/bNrMDxYrpcPzN29+7XinI9gB1073vuni+mVmGeUd/9zS4WHJ8fyS
                            w9zGoyfsiVw///dSvIXDuFV+NVj0lbwSPLiPPm82umr9R7tXWO1V5wfrml1fmuMg5aC/8VVTIEyv
                            m7/t89sYkxLfvHXJ26ns+R/wq8Ft//FRt0nj3xXygxMA5Lf9s6jboE+3f0sX2p7HfZifh/4Hc+8n
                            p/fF/hfm37j8RRv4RQF0N9r5iN3DzUQMD3iwwsrfGaWwuIWFciy2w7QPhk5TGeaUlDiR0v4Hi+nd
                            OrawbLW1MK6wqwow6YkCUUk1cEEiIS22AzTjuVKaNab8kJW/F+L0PJRtGMaJxsRVG4dKh8qcvPyY
                            OHhh8iSf5o+4/PzTn3ETQcaBXti1tWM8EQkwtM3SSEDgkTjOQhldxrWUqNL8ItywSX5aGKreP5P8
                            hB4ShLuoEQ4F7LA0Fl+XUJoxBgvJXClglOv2dGNw1XpQqTZn4/zrwnkL/jBxFR/4+8cU+Q1/+lLx
                            A2OFQfg9VtEsszRE9oF2G6Ioy6j7QgqLTH7okZKfFkYZ4KoEOE80UY0MyLDyCs4BKaOUZoyJFS1O
                            PdFtpIwjlclV8INXEnM28Pt6z0j76qH2I80f2OoKPzxRCrL8ZE/lUBXjCWigep/wCzH8V/zPVCB3
                            223tf3RaGiX7cAkEGsdvlrqqlGaMWeWng8M0TrgZ0lV68JVo0x5b///B5GI8nkzgOhmPL8YX8Bm/
                            /f2SS3400GT87yGNgml+4kQhVucIEd7lKYPBD+phwMuMfzDu7CoFcvyjYYgrPapM1tSlA9JRGB3u
                            SqVZY0SE1sX4h8+yDcM486r1oFJtzkZ+t15CbfFPkerb/xevny2/f5ifTZaf5Wf5WX6Wn01/O/0F
                            EIMkX0dvhQ8AAAAASUVORK5CYII=" 
                            alt="Pensez environnement ! N\'imprimez ce mail que si c\'est vraiment nécessaire."/>
                            </p>';
                }

                // http://www.subclosure.com/how-to-set-new-line-breaks-wiht-phpmailer-for-text-only-emails.html
                $altBody = str_replace('$$PASSWORD$$', $arrayStores[$j]->getPanel(), $contentEmailStoreFromFileAltBody);
                
                if($isOtherPf) {
                    $altBody  = "Bonjour,\n\n";
                    $altBody .= "Vous pouvez dès à présent saisir les informations pour le salon d'achats mentionné en objet, ";
                    $altBody .= "qui commence le ".$_SESSION['purchasingFairConcerned']->getStartDatetime()." et se termine le ".$_SESSION['purchasingFairConcerned']->getEndDatetime().".\n\n";
                    $altBody .= "Rendez-vous sur http://205.0.211.85/pf_management/ pour accéder à l'application dédiée, sélectionnez votre nom de ".$enterpriseContact->getOneEnterprise()->getOneProfile()->getName()." dans la liste et saissez le mot de passe suivant :";
                    $altBody .= " ".$arrayStores[$j]->getPanel()."\n\n";
                    $altBody .= "La clôture des inscriptions aura lieu le ".$_SESSION['purchasingFairConcerned']->getRegistrationClosingDateMagasin().". Passé ce délai, il faudra contacter le Service SCA Ouest concerné pour toute modification.\n\n";
                    $altBody .= "Cet email est destiné à ".$enterpriseContact->getCivility()." ".$enterpriseContact->getSurname()." ".$enterpriseContact->getName()." du ".$enterpriseContact->getOneEnterprise()->getOneProfile()->getName()." ".$enterpriseContact->getOneEnterprise()->getName().".";
                    $altBody .= " Si ce n'est pas vous, merci de bien vouloir en informer le Service SCA Ouest concerné.\n\n";
                    $altBody .= "Pensez environnement ! N'imprimez ce mail que si c'est vraiment nécessaire.";
                }

                $successfulSending = $appService->sendMail(new MyEmail($recipient, $attachments, $subject, $body, $altBody));

                if($successfulSending) { 
                    $counterSuccesfulSendingsStore++;
                    // http://php.net/manual/fr/function.file-put-contents.php
                    file_put_contents('./errors/log_emails.txt', '['.date('d-M-Y H:i:s e').'] EMAIL ENVOYÉ : '.$enterpriseContact->getOneEnterprise()->getOneProfile()->getName().' '.$enterpriseContact->getOneEnterprise()->getName()."\n", FILE_APPEND);
                    
                    // Save data in CSV file - Added 30/05/2018
                    $csvData = $arrayStores[$j]->getIdEnterprise().';'.date('Y-m-d H:i:s');
                    file_put_contents('./tmp/tmp_check_emails_pf_store'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'.csv', $csvData."\n", FILE_APPEND);                
                }
                else {
                    file_put_contents('./errors/log_emails.txt', '['.date('d-M-Y H:i:s e').'] EMAIL NON ENVOYÉ #ERR-01 : '.$enterpriseContact->getOneEnterprise()->getOneProfile()->getName().' '.$enterpriseContact->getOneEnterprise()->getName()."\n", FILE_APPEND);
                }
            }
            else {
                file_put_contents('./errors/log_emails.txt', '['.date('d-M-Y H:i:s e').'] EMAIL NON ENVOYÉ #ERR-02 : '.$arrayStores[$j]->getOneProfile()->getName().' '.$arrayStores[$j]->getName()."\n", FILE_APPEND);
            }
        }

        $_SESSION['counterSuccesfulSendingsStore'] = $counterSuccesfulSendingsStore;

    }
    
    $excel .= '</table>';
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b70b5"><!-- Mobile browser Tab Color -->

    <title>PFManagement | Invitations</title>
	
    <!-- Favicon -->
    <?php require_once dirname ( __FILE__ ) . '/view/favicon.inc.php'; ?>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	
    <!-- Font Awesome -->
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    
    <!-- Select2 -->
    <link href="css/plugins/select2/select2.min.css" rel="stylesheet">
    
    <!-- DataTables -->
    <link href="css/plugins/dataTables/datatables.min.css" rel="stylesheet">
	
    <!-- Hover -->
    <link href="css/plugins/hover.css/hover-min.css" rel="stylesheet">
    
    <!-- Toastr style -->
    <link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">

    <!-- Animate -->
    <link href="css/animate.css" rel="stylesheet">
	
    <!-- Global -->
    <link href="css/style.css" rel="stylesheet">
    
    <!-- Custom style -->
    <style>
    /* Widget */
    .widget { color:#ffffff;border:1px solid #ffffff; }
    /* ibox */
    .ibox-title { border-top:2px solid #0b70b5; }
    .ibox-title h5 { color:#0b70b5; }
    /* Replace the icon of the button */
    .hvr-icon-forward:before { content:'\f1d8'; }
    /* Color orange E.Leclerc */
    .colorOrangeLeclerc { color:#ed8b18; }
    .colorBlueLeclerc { color:#0b70b5; }
    /* Spinenr color */
    .sk-spinner-wave div, .sk-spinner-three-bounce div{ background-color:#0b70b5; }
    /* Update/Remove icons */
    .actionEdit, .actionDelete { cursor:pointer }
    .actionEdit { color:#0b70b5; }
    .actionDelete { color:#ed8b18; }
    /* Button to access the list of purchasing fairs */
    #submitButtonProviders { background-color:#0b70b5;color:#ffffff; }
    #submitButtonProviders:hover { border:1px solid #ed8b18; }

    #submitButtonStore { background-color:#0b70b5;color:#ffffff; }
    #submitButtonStore:hover { border:1px solid #ed8b18; }
    /* Select2 custom height input */
    .select2-selection.select2-selection--multiple {min-height:100px;}
    /* Spinner */
    .ibox-content.sk-loading > .sk-spinner { top: 35%!important; }
    .sk-spinner-circle.sk-spinner { width:40px!important;height:40px!important; }
    .sk-spinner-circle .sk-circle:before { background-color:#0b70b5; }
    </style>

</head>

<body>

    <div id="wrapper">

        <?php require_once dirname ( __FILE__ ) . '/view/menu.global.inc.php'; ?>

        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
        </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message">Invitations</span>
                </li>
                <li>
                    <a href="./disconnection.php">
                        <i class="fa fa-sign-out"></i> Quitter
                    </a>
                </li>
            </ul>

        </nav>
        </div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-9">
                    <h2><i class="fa fa-flag-o" aria-hidden="true"></i> Envoi des invitations aux Fournisseurs sélectionnés + Magasins</h2>
                    <ol class="breadcrumb">
                        <li class="active">
                            <strong>Administration/Invitations</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-3">
                <?php if( isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned']) && !isset($_SESSION['adminConnected']) && empty($_SESSION['adminConnected']) ) { ?>
                <h2 class="text-center">Vous avez sélectionné le <?php echo $_SESSION['enterpriseConcerned']->getOneProfile()->getName() ?></h2>
                <h2 class="colorOrangeLeclerc text-center"><em><?php echo $_SESSION['enterpriseConcerned']->getName(); ?></em></h2>
                <?php } elseif( isset($_SESSION['adminConnected']) && !empty($_SESSION['adminConnected']) && isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned'])) { ?>
                <?php require_once dirname ( __FILE__ ) . '/view/widget_pf_info.inc.php'; ?>
                <?php } elseif( isset($_SESSION['adminConnected']) && !empty($_SESSION['adminConnected']) && !isset($_SESSION['purchasingFairConcerned']) && empty($_SESSION['purchasingFairConcerned'])) { ?>
                <h2 class="text-center">Vous n'avez pas sélectionné de salon</h2>
                <?php } ?>
                </div>
            </div>

            <div class="wrapper wrapper-content">

                <!--<div class="alert alert-danger">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>&nbsp;
                    L'ensemble des Magasins et Fournisseurs présents sélectionnés dans Gestion Fournisseurs > Choix présents vont recevoir un email d'invitation. Les modèles ci-dessous doivent être vérifiés et modifiés avant leur envoi (valable uniquement pour les salons d'achats du Service Textile).
                </div>-->
                
                <div class="row" >

                    <!--Mails for Providers Present-->
                    <?php if(!$isOtherPf) { ?>
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title" style="border-top:1px solid #0b70b5">
                                <h5><i class="fa fa-envelope-o" aria-hidden="true"></i> Formulaire d'envoi Fournisseurs</h5>
                                <div class="ibox-tools">
                                    <a>
                                        <i class="fa fa-chevron-up collapse-link"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="ibox-content">

                                <!-- Always place after ibox-content -->
                                <div class="sk-spinner sk-spinner-circle">
                                    <div class="sk-circle1 sk-circle"></div>
                                    <div class="sk-circle2 sk-circle"></div>
                                    <div class="sk-circle3 sk-circle"></div>
                                    <div class="sk-circle4 sk-circle"></div>
                                    <div class="sk-circle5 sk-circle"></div>
                                    <div class="sk-circle6 sk-circle"></div>
                                    <div class="sk-circle7 sk-circle"></div>
                                    <div class="sk-circle8 sk-circle"></div>
                                    <div class="sk-circle9 sk-circle"></div>
                                    <div class="sk-circle10 sk-circle"></div>
                                    <div class="sk-circle11 sk-circle"></div>
                                    <div class="sk-circle12 sk-circle"></div>
                                </div>

                                <div class="alert alert-warning">
                                NE PAS EFFACER $$PASSWORD$$ qui contiendra les mots de passe. 
                                </div>
                                <div class="form-group">

                                    <form role="form" action="#" method="POST"> 

                                        <label for="comment">Comment:</label>
                                        <textarea name="msgProvider" class="form-control" rows="20" id="textareaEmailProvider" style="resize:none"><?php
                                        $counterErrors = 0;
                                        $result = "";
                                        $logFile = fopen(dirname ( __FILE__ ) . '/templates_emails/template_email_provider.txt', 'r');
                                        if ($logFile) {

                                            while (($buffer = fgets($logFile)) !== false) { 
                                                $counterErrors++;
                                                $result .= $buffer; 
                                            }

                                            if(!$counterErrors) { 
                                                $result = 'Fichier vide.'; 
                                            }

                                            if (!feof($logFile)) { 
                                                $result = "Erreur: fgets() a échoué\n"; 
                                            }

                                            fclose($logFile);

                                            echo $result;
                                        }
                                        ?>
                                        </textarea>
                                        <button type="button" id="updateButtonEmailProviders" name="updateButtonEmailProviders" class="btn btn-success block full-width m-t-xl m-b-md hvr-icon-spin" onclick="updateEmailEnterprise(1);">Mettre à jour email</button>

                                        <?php if(isset($_POST['msgProvider']) && !empty($_POST['msgProvider'])) { ?>
                                        <div class="alert alert-success">
                                            <i class="fa fa-check-circle" aria-hidden="true"></i>&nbsp;
                                            Le téléchargement automatique d'un fichier Excel contenant les mots de passe des Fournisseurs présents va démarrer. Vérifiez votre dossier de téléchargements.
                                        </div>
                                        <?php } ?>

                                        <?php if(isset($_POST['msgProvider']) && !empty($_POST['msgProvider'])) { echo $excel; } ?>

                                        <!--------------------------------------------------------------------------------->
                                        
                                        <?php if( isset($_SESSION['purchasingFairConcerned']) 
                                                && !empty($_SESSION['purchasingFairConcerned']) 
                                                && !file_exists('./tmp/tmp_check_emails_pf_provider'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'.csv') ) { ?>
                                        <button type="submit" id="submitButtonProviders" name="submitButtonProviders" class="btn block full-width m-t-xl m-b-md hvr-icon-forward">Envoyer les invitations Fournisseurs par mail</button>
                                        <?php } ?>
                                        <?php if( isset($_SESSION['purchasingFairConcerned']) 
                                                && !empty($_SESSION['purchasingFairConcerned']) 
                                                && file_exists('./tmp/tmp_check_emails_pf_provider'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'.csv') ) { ?>
                                        <button class="btn block full-width m-t-xl m-b-md" disabled><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Emails d'invitations déjà envoyés, en cas d'ajout de Fournisseur présent, se rendre dans Envoi invitations 2</button>
                                        <?php } ?>
                                        <?php if( !isset($_SESSION['purchasingFairConcerned']) 
                                                && empty($_SESSION['purchasingFairConcerned']) ) { ?>
                                        <button class="btn block full-width m-t-xl m-b-md" disabled><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Veuillez choisir un salon d'achats</button>
                                        <?php } ?>
                                        
                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>
                    <?php } ?>

                    <!--Mails for Store-->
                    <?php if(!$isOtherPf) { ?>
                    <div class="col-lg-12">
                        <div class="ibox float-e-fournisseur">
                            <div class="ibox-title" style="border-top:1px solid #0b70b5">
                                <h5><i class="fa fa-envelope-o" aria-hidden="true"></i> Formulaire d'envoi Magasin</h5>
                                <div class="ibox-tools">
                                    <a>
                                        <i class="fa fa-chevron-up collapse-link"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="ibox-content">

                                <!-- Always place after ibox-content -->
                                <div class="sk-spinner sk-spinner-circle">
                                    <div class="sk-circle1 sk-circle"></div>
                                    <div class="sk-circle2 sk-circle"></div>
                                    <div class="sk-circle3 sk-circle"></div>
                                    <div class="sk-circle4 sk-circle"></div>
                                    <div class="sk-circle5 sk-circle"></div>
                                    <div class="sk-circle6 sk-circle"></div>
                                    <div class="sk-circle7 sk-circle"></div>
                                    <div class="sk-circle8 sk-circle"></div>
                                    <div class="sk-circle9 sk-circle"></div>
                                    <div class="sk-circle10 sk-circle"></div>
                                    <div class="sk-circle11 sk-circle"></div>
                                    <div class="sk-circle12 sk-circle"></div>
                                </div>

                                <div class="alert alert-warning">
                                NE PAS EFFACER $$PASSWORD$$ qui contiendra les mots de passe. 
                                </div>
                                <div class="form-group">

                                    <form role="form" action="#" method="POST"> 

                                        <label for="comment">Comment:</label>
                                        <textarea name="msgStore" class="form-control" rows="20" id="textareaEmailStore" style="resize:none"><?php
                                        $counterErrors = 0;
                                        $result = "";
                                        $logFile = fopen(dirname ( __FILE__ ) . '/templates_emails/template_email_store.txt', 'r');
                                        if ($logFile) {

                                            while (($buffer = fgets($logFile)) !== false) { 
                                                $counterErrors++;
                                                $result .= $buffer; 
                                            }

                                            if(!$counterErrors) { 
                                                $result = 'Fichier vide.'; 
                                            }

                                            if (!feof($logFile)) { 
                                                $result = "Erreur: fgets() a échoué\n"; 
                                            }

                                            fclose($logFile);

                                            echo $result;
                                        }
                                        ?>
                                        </textarea>
                                        <button type="button" id="updateButtonEmailStores" name="updateButtonEmailStores" class="btn btn-success block full-width m-t-xl m-b-md hvr-icon-spin" onclick="updateEmailEnterprise(2);">Mettre à jour email</button>

                                        <!--------------------------------------------------------------------------------->
                                        
                                        <?php if( isset($_SESSION['purchasingFairConcerned']) 
                                                && !empty($_SESSION['purchasingFairConcerned']) 
                                                && !file_exists('./tmp/tmp_check_emails_pf_store'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'.csv') ) { ?>
                                        <button type="submit" id="submitButtonStore" name="submitButtonStore" class="btn block full-width m-t-xl m-b-md hvr-icon-forward">Envoyer les invitations Fournisseurs par mail</button>
                                        <?php } ?>
                                        <?php if( isset($_SESSION['purchasingFairConcerned']) 
                                                && !empty($_SESSION['purchasingFairConcerned']) 
                                                && file_exists('./tmp/tmp_check_emails_pf_store'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'.csv') ) { ?>
                                        <button class="btn block full-width m-t-xl m-b-md" disabled><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Emails d'invitations déjà envoyés</button>
                                        <?php } ?>
                                        <?php if( !isset($_SESSION['purchasingFairConcerned']) 
                                                && empty($_SESSION['purchasingFairConcerned']) ) { ?>
                                        <button class="btn block full-width m-t-xl m-b-md" disabled><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Veuillez choisir un salon d'achats</button>
                                        <?php } ?>
                                        
                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>
                    <?php } ?>
                    
                </div><!-- ./row -->                

            </div>
            <div class="footer">
                <div class="pull-right">
                    <strong><i class="fa fa-copyright" aria-hidden="true"></i></strong> E.Leclerc | SCA Ouest <?php echo date('Y'); ?>
                </div>
                <div>
                    <strong>PFManagement</strong>
                </div>
            </div>

        </div>
        </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- DataTables -->
    <script src="js/plugins/dataTables/datatables.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>
    
    <!-- Toastr script -->
    <script src="js/plugins/toastr/toastr.min.js"></script>
    
    <!-- table2csv -->
    <script src="js/plugins/table2csv/table2csv.js"></script>
    
    <!-- table2excel -->
    <script src="js/plugins/table2excel/jquery.table2excel.js"></script>
    
    <!-- Custom script -->
    <script>
    function updateEmailEnterprise(profileEnterprise) {
         
        var enterprise = '';
        var emailContent = '';
         
        // Provider
        if(profileEnterprise === 1) { enterprise = 'provider'; emailContent = $('#textareaEmailProvider').val(); }

        // Store
        if(profileEnterprise === 2) { enterprise = 'store'; emailContent = $('#textareaEmailStore').val(); }

        
        $.post(
            './ajax/updateEmailTemplate.php',
            {
                profileEnterprise : enterprise,
                emailContent : emailContent
            },
            function(data) {
                if(data.trim() === 'Success') { 
                    toastr.success('L\'email a été mis à jour.', 'Succès.');
                }
            },
            'text'
        );    
    }

    <?php if(isset($_POST['msgProvider']) && !empty($_POST['msgProvider']) &&  $_SESSION['counterSuccesfulSendingsProvider'] == $counterMailsToSend) { ?>
    toastr.success('Tous les emails ont été envoyés.', 'Succès.');
    <?php } elseif(isset($_POST['msgProvider']) && !empty($_POST['msgProvider']) &&  $_SESSION['counterSuccesfulSendingsProvider'] != $counterMailsToSend) { ?>
    toastr.error('Tous les emails n\'ont pas été envoyés.', 'Erreur.');
    <?php } ?>

    <?php if(isset($_POST['msgStore']) && !empty($_POST['msgStore']) &&  $_SESSION['counterSuccesfulSendingsStore'] == $counterMailsToSend) { ?>
    toastr.success('Tous les emails ont été envoyés.', 'Succès.');
    <?php } elseif(isset($_POST['msgStore']) && !empty($_POST['msgStore']) &&  $_SESSION['counterSuccesfulSendingsStore'] != $counterMailsToSend) { ?>
    toastr.error('Tous les emails n\'ont pas été envoyés.', 'Erreur.');
    <?php } ?>

        
    <?php if(isset($_POST) && !empty($_POST)) { ?>

    $('#tablePasswords').table2excel({
        exclude: ".noExl",
        name: "Mots de passe",
        filename: "passwords_" + new Date().toISOString().replace(/[\-\:\.]/g, ""),
        fileext: ".xls",
        exclude_img: true,
        exclude_links: true,
        exclude_inputs: true
    });
    <?php } ?>
    /* Spinner */
    $(function(){ $('#submitButtonProviders').on('click', function(){ $('.ibox-content').toggleClass('sk-loading'); }); });
    $(function(){ $('#submitButtonStore').on('click', function(){ $('.ibox-content').toggleClass('sk-loading'); }); });
    </script>

</body>

</html>