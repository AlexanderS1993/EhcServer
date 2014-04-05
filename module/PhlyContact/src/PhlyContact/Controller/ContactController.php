<?php

namespace PhlyContact\Controller;

//use \Locale; // php 5.3.;
use PhlyContact\Form\ContactForm;
use Zend\Debug\Debug;
use Zend\I18n\Translator\Translator;
use Zend\Mail\Transport;
use Zend\Mail\Message as Message;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ContactController extends AbstractActionController
{
    protected $form;
    protected $message;
    protected $transport;
    protected $sentMessage;
    
    public function setMessage(Message $message)
    {
        $this->message = $message;
    }

    public function setMailTransport(Transport\TransportInterface $transport)
    {
        $this->transport = $transport;
    }
    
    public function setSentMessage($message){
    	$this->sentMessage = $message;
    }
    
    public function getSentMessage(){
    	return $this->sentMessage;
    }
    
    public function indexAction()
    {
    	return new ViewModel(array('form' => $this->form));
    }

    public function processAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute('contact');
        }
        $post = $this->request->getPost();
        $form = $this->form;
        $form->setData($post);
        if (!$form->isValid()) {
        	$model = new ViewModel(array(
                	'error' => true,
                	'form'  => $form,
            ));
            $model->setTemplate('phly-contact/contact/index');
            return $model;
        }
        // send email...
        $validData = $form->getData();
        $head = "Jobamail, EHC Kontaktaufnahme";
        $fromMail = $validData['from'];
        //$fromName = $validData['name'];
        $fromName = "Kein Name"; 
        $subject = $validData['subject'];
        $comment = $validData['body'];
        $mailContent = "Folgender Kommentar wurde gerade abgesendet: [Absender: " . $fromName . "][Betreff: " . $subject . "][Name: " . $fromMail . "] [Nachricht: " . $comment . "] \n";
        $formkeys = array("email", "comment");
        $res = $this->sendMail($form, $head, $mailContent, $formkeys);
        $this->setSentMessage("");
        if ($res == false) {
        	$sentMessage = "<p style='color: red'>E-Mail konnte nicht versendet werden!</p>";
        	$this->setSentMessage($sentMessage);
        	return $this->redirect()->toRoute('contact/thank-you');
        } else {
        	$sentMessage = "<p style='color: green'>E-Mail wurde versendet!</p>";
        	$this->setSentMessage($sentMessage);
        	return $this->redirect()->toRoute('contact/thank-you');
        }
    }

    public function thankYouAction()
    {
        $headers = $this->request->getHeaders();
        if (!$headers->has('Referer') || !preg_match('#/contact$#', $headers->get('Referer')->getFieldValue())) {
            return $this->redirect()->toRoute('contact');
        }
        $model = new ViewModel(array(
        		'sentMessage' 		=> $this->getSentMessage(),
        ));
        return $model;
    }

    public function setContactForm(ContactForm $form){
        $this->form = $form;
    }

    protected function sendEmail(array $data){ // TODO Check if obsolete
        $from    = $data['from'];
        $subject = '[Contact Form] ' . $data['subject'];
        $body    = $data['body'];

        $this->message->addFrom($from)
                      ->addReplyTo($from)
                      ->setSubject($subject)
                      ->setBody($body);
        $this->transport->send($this->message);
    }
	private function sendMail($form, $head, $content, $formkeys) {
		$formEmail = $form->getValue ( $formkeys [0] );
		$formComment = $form->getValue ( $formkeys [1] ); // optional
		// Mailversand mit PHPMailer
		require_once (APP_ROOT . '/vendor/phpmailer/class.phpmailer.php');
		require_once (APP_ROOT . '/vendor/phpmailer/class.smtp.php');
		$mailbetreff = $head;
		$mailinhalt = $content . " " . $formEmail . " - " . $formComment;
		// TODO use local config to store vars
		// SMTP-Versand
		$mail = new \PHPMailer();
		$mail->IsSMTP(); // set mailer to use SMTP
		$config = $this->getServiceLocator()->get('Config');
		$jobaGlobalOptions = $config['jobaGlobalOptions'];
		$mail->Host = $jobaGlobalOptions['mailHost'];
		$mail->SMTPAuth = true;
		$mail->Username = $jobaGlobalOptions['mailUser'];
		$mail->Password = $jobaGlobalOptions['mailPass'];
		$mail->From = $jobaGlobalOptions['mailFrom'];
		$mail->FromName = $jobaGlobalOptions['mailFromName'];
		$mail->AddAddress($jobaGlobalOptions['mailRec'], $jobaGlobalOptions['mailRecName']);
		$mail->AddCC($jobaGlobalOptions['mailCc'], $jobaGlobalOptions['mailCcName'] );
		$mail->Subject = $mailbetreff;
		$mail->Body = $mailinhalt;
		$mail->CharSet = "utf-8";
		if (! $mail->Send ()) {
			return false;
		} else {
			return true; // Versand erfolgreich
		}
	}
}
