<?php

namespace Shop\Records\Model;
 
class Transport extends \Zend_Mail_Transport_Smtp implements \Magento\Framework\Mail\TransportInterface
{
  
    protected $_message;
 

    public function __construct(\Magento\Framework\Mail\MessageInterface $message)
    {
         if (!$message instanceof \Zend_Mail) {
            throw new \InvalidArgumentException('The message should be an instance of \Zend_Mail');
         }
         $smtpHost= 'smtp.gmail.com';
         $smtpConf = [
            'auth' => 'login',
            'tsl' => 'tsl', 
            'port' => '587',
            'username' => 'junsayjohn32@gmail.com',
            'password' => 'ma.shekinah143'
         ];
 
        parent::__construct($smtpHost, $smtpConf);
        $this->_message = $message;
    }
 
    public function sendMessage()
    {
        try {
            parent::send($this->_message);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\MailException(new \Magento\Framework\Phrase($e->getMessage()), $e);
        }
    }
}