<?php
include ("phpmailer.php");
class Mail extends PhpMailer{
   public $From = 'noreply@rpgo.net';
   public $FromName = SITENAME;
   //public $Host = 'smtp.gmail.com';
   //public $Mailer = 'smtp';
   //public $SMTPAuth = 'true';
   //public $Username = 'adorufus@gmail.com';
   //public $Password = 'incorect';
   //public $SMTPSecurr = 'tls';
   public $WordWarp = '75';
   public function subject($subject){
      $this -> Subject = $subject;
   }
   
   public function body($body){
      $this -> Body = $body;
   }
   
   public function send(){
      $this -> AltBody = strip_tags(stripslashes($this -> Body)) ."\n\n";
      $this -> AltBody = str_replace("&nbsp;", "\n\n", $this -> AltBody);
      return parent:: send();
   }
}
