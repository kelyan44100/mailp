<?php
class MyEmail {
    
    // Attributes
    private $idMyEmail;
    private $recipient;
    private $attachments;
    private $subject;
    private $body;
    private $altBody;
    
    // Constructor
    public function __construct($recipient, $attachments, $subject, $body, $altBody, $idMyEmail = -1) {
        $this->idMyEmail = $idMyEmail;
        $this->recipient = $recipient;
        $this->attachments = $attachments;
        $this->subject = $subject;
        $this->body = $body;
        $this->altBody = $altBody;
    }
    
    // Getters & setters
    public function getIdMyEmail() { return $this->idMyEmail; }

    public function getRecipient() { return $this->recipient; }
 
    public function getAttachments() { return $this->attachments; }

    public function getSubject() { return $this->subject; }

    public function getBody() { return $this->body; }

    public function getAltBody() { return $this->altBody; }

    public function setIdMyEmail($idMyEmail) { $this->idMyEmail = $idMyEmail; }

    public function setRecipient($recipient) { $this->recipient = $recipient; }

    public function setAttachments($attachments) { $this->attachments = $attachments; }

    public function setSubject($subject) { $this->subject = $subject; }

    public function setBody($body) { $this->body = $body; }

    public function setAltBody($altBody) { $this->altBody = $altBody; }
}
?>