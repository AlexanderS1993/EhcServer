<?php
namespace PhlyContact\Form;

use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Element;
use Zend\Form\Form;

class ContactForm extends Form
{
    protected $captchaAdapter;
    protected $csrfToken;

    public function __construct($name = null, CaptchaAdapter $captchaAdapter = null)
    {
        parent::__construct($name);

        if (null !== $captchaAdapter) {
            $this->captchaAdapter = $captchaAdapter;
        }

        $this->init();
    }

    public function init()
    {
        $name = $this->getName();
        if (null === $name) {
            $this->setName('contact');
        }
        
        $this->add(array(
            'name' => 'from',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Ihre E-Mail:',
            ),
        ));

        $this->add(array(
            'name'  => 'subject',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Betreff:',
            ),
        ));


        $this->add(array(
            'name'  => 'body',
            'type'  => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => 'Ihre Nachricht:',
            ),
        ));

        //$captcha = new Element\Captcha('captcha');
        //$captcha->setCaptcha($this->captchaAdapter);
        //$captcha->setOptions(array('label' => 'Anti-Spam-Prüfung:'));
        //$this->add($captcha);

        $this->add(new Element\Csrf('csrf'));

        $this->add(array(
            'name' => 'Send',
            'type'  => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Abschicken',
            ),
        ));
    }
}
