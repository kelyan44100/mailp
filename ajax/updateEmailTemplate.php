<?php
$profileEnterprise = $_POST['profileEnterprise'];
$emailContent      = $_POST['emailContent'];
file_put_contents('./../templates_emails/template_email_'.$profileEnterprise.'.txt', $emailContent);
echo 'Success';
?>