<?php

namespace App\Controller;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailController extends AbstractController {

	private $transport;
	private $mailer;

	public function __construct() {
		$this->transport = new Swift_SmtpTransport();
		$this->mailer = new Swift_Mailer($this->transport);
	}

	/**
	 * @param array $mailInformation
	 */
	public function sendMail(array $mailInformation)
	{
		$message = (new Swift_Message('test'))
			->setFrom('noreply@ewahaaglanden.nl')
			->setTo($mailInformation['to'])
			->setBody(
				'<h1>' . $mailInformation['to'] . '</h1>'
			);

		$this->mailer->send($message);
	}
}