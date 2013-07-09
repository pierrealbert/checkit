<?php

require_once dirname(__FILE__) . '/../bootstrap.php';

$bootstrap->bootstrap('mailer');

$mailManager = $bootstrap->getResource('mailer');

$mailManager->sendMessages();
