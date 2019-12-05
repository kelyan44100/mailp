<?php

// get JSON
$jsonToDecode = $_POST['enterprisesToContact'];

// COnvert JSON & remove duplicate elements
$arrayIdsEnterprisesToContact = array_unique(json_decode($jsonToDecode));

require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

$counterArrayIds = count($arrayIdsEnterprisesToContact);
$counterSuccesfulSendings = 0;
foreach($arrayIdsEnterprisesToContact as $idEnterprise) { // We don't care about the key
    
    $enterpriseToContact = $appService->findOneEnterprise($idEnterprise);
//    $enterpriseContact = null;
    
//    if($enterpriseToContact->getOneprofile()->getIdprofile() == 1) { // Provider
        $enterpriseContact = $appService->findOneEnterpriseContactByEnterprise($idEnterprise);
//    }
//    elseif ($enterpriseToContact->getOneprofile()->getIdprofile() == 2) { // Store
//        $enterpriseContact = $appService->findOneEnterpriseContactByEnterprise($idEnterprise);
//    }
//    else { // Others
//        // nothing
//    }
    if( !empty($enterpriseContact) ) {
        
        $recipientAddress = $enterpriseContact->getEmail(); // 'test@scaouest.fr'
        $recipientName    = $enterpriseContact->getCivility().' '.$enterpriseContact->getSurname().' '.$enterpriseContact->getName();

        $recipient = array( 'recipientAddress' => $recipientAddress, 'recipientName' => $recipientName);

        $attachments = array(); // './doc/passwords.pdf'

        $subject = 'Information importante - '.$_SESSION['purchasingFairConcerned']->getNamePurchasingFair();

        $body  = 'Bonjour,<br/><br/>';
        $body .= 'Votre planning pour le salon mentionné en objet a été modifié.';
        $body .= '&nbsp;Vous pouvez consulter dès à présent les modifications depuis votre espace '.$enterpriseToContact->getOneProfile()->getName().'.<br/><br/>';
        $body .= '<em>Cet email est destiné à '.$recipientName.' du <strong>'.$enterpriseToContact->getOneProfile()->getName().' '.$enterpriseToContact->getName().'.</strong>';
        $body .= '&nbsp;Si ce n\'est pas vous, merci de bien vouloir en informer le Service Textile SCA Ouest.</em>';
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

        // http://www.subclosure.com/how-to-set-new-line-breaks-wiht-phpmailer-for-text-only-emails.html
        $altBody  = "Bonjour,\n\n";
        $altBody .= "Votre planning pour le salon mentionné en objet a été modifié.";
        $altBody .= " Vous pouvez consulter dès à présent les modifications depuis votre espace ".$enterpriseToContact->getOneProfile()->getName().".\n\n";
        $altBody .= "Cet email est destiné à ".$recipientName." du ".$enterpriseToContact->getOneProfile()->getName()." ".$enterpriseToContact->getName().".";
        $altBody .= " Si ce n'est pas vous, merci de bien vouloir en informer le Service Textile SCA Ouest.\n\n";
        $altBody .= "Pensez environnement ! N'imprimez ce mail que si c'est vraiment nécessaire.";

        $successfulSending = $appService->sendMail(new MyEmail($recipient, $attachments, $subject, $body, $altBody));

        if($successfulSending) { 
            $counterSuccesfulSendings++;
            // http://php.net/manual/fr/function.file-put-contents.php
            file_put_contents('./../errors/log_emails.txt', '['.date('d-M-Y H:i:s e').'] EMAIL ENVOYÉ : '.$enterpriseToContact->getOneProfile()->getName().' '.$enterpriseToContact->getName()."\n", FILE_APPEND);
        }
        else {
            file_put_contents('./../errors/log_emails.txt', '['.date('d-M-Y H:i:s e').'] EMAIL NON ENVOYÉ #ERR-01 : '.$enterpriseToContact->getOneProfile()->getName().' '.$enterpriseToContact->getName()."\n", FILE_APPEND);
        }
    }
    else {
        file_put_contents('./../errors/log_emails.txt', '['.date('d-M-Y H:i:s e').'] EMAIL NON ENVOYÉ #ERR-02 : '.$enterpriseToContact->getOneProfile()->getName().' '.$enterpriseToContact->getName()."\n", FILE_APPEND);
    }
}

echo ($counterArrayIds == $counterSuccesfulSendings) ? 'OK' : 'NOK';
?>