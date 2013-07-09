<?php

require_once dirname(__FILE__) . '/../bootstrap.php';

$bootstrap->bootstrap('translate');
$bootstrap->bootstrap('emailTemplate');

$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/mail.ini',
        APPLICATION_ENV, true);

$newsletterIssueGateway = new Model_NewsletterIssue_Gateway();

$crit = Model_NewsletterIssue_Gateway::getCriteria();
$crit->addWhere('status', Model_NewsletterIssue::READY);

$newsletterIssue = $newsletterIssueGateway->fetchOne($crit);

if ($newsletterIssue) {
    $receivers = $newsletterIssue->getReceivers();

    $mailManager = new Mail_Manager();

    foreach ($receivers as $receiver) {

        if ($receiver->language) {
            Model_Translatable_Field::setCurrentLanguage($receiver->language);
        }

        $tempalte = new Mail_Template('newsletter', array(
            'message'       => (string) $newsletterIssue->message,
            'newsletterId'  => $newsletterIssue->id
        ));
               
        $mailManager->addMessage(array(
            'from'      => $config->newsletterFrom->toArray(),
            'reply-to'  => $config->newsletterReplyTo->toArray(),
            'toAddress' => $receiver->email,
            'subject'   => (string) $newsletterIssue->subject,
            'message'   => $tempalte->getBody()
        ));
    }
    
    $newsletterIssue->complete();
}