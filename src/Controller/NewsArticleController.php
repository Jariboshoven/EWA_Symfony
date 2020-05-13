<?php

namespace App\Controller;

use App\Entity\NewsArticle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class NewsArticleController extends AbstractController {

	private $serializer;
	private $newsArticleRepository;

	private $em;
	private $mailer;

	public function __construct(EntityManagerInterface $em, MailerInterface $mailer)
	{
		$this->em = $em;
		$this->mailer = $mailer;

		$encoders = [new XmlEncoder(), new JsonEncoder()];
		$normalizers = [new ObjectNormalizer()];
		$this->serializer = new Serializer($normalizers, $encoders);

		$this->newsArticleRepository = $this->em->getRepository(NewsArticle::class);
	}

	/**
	 * @Route("/api/news/all", name="get_news", methods={"GET"})
	 *
	 * @return Response
	 */
	public function getAllNewsArticles(): Response
	{
		$newsArticles = $this->newsArticleRepository->findPublishedArticles();
		$response = $this->serializer->serialize($newsArticles, 'json');
		return new Response($response);
	}

	/**
	 * @Route("/api/news/recent/{number}", name="get_recent_news", methods={"GET"})
	 * @param int $number
	 *
	 * @return Response
	 */
	public function getRecentNewsArticles(int $number): Response
	{
		$newsArticles = $this->newsArticleRepository->findRecentArticles($number);
		$response = $this->serializer->serialize($newsArticles, 'json');
		return new Response($response);
	}

	/**
	 * @Route("/api/news/id/{id}", name="get_news_by_id", methods={"GET"})
	 * @param int $id
	 *
	 * @return Response
	 */
	public function getNewsArticleById(int $id): Response
	{
		$newsArticle = $this->newsArticleRepository->findBy(['id' => $id]);
		$response = $this->serializer->serialize($newsArticle, 'json');
		return new Response($response);
	}

	/**
	 * @Route("/api/notifymembers/{postId}/{postTitle}", name="notify_members", methods={"POST"})
	 *
	 * @param int $postId
	 * @param string $postTitle
	 *
	 * @return Response
	 */
	public function notifyNewsLetterRegistrations(int $postId, string $postTitle)
	{

		$websiteVarController = new WebsiteVarController($this->em);
		$mailText = $websiteVarController->getEmailVars();

		if(empty($mailText))
		{
			return new Response('failed');
		}

		if(!array_key_exists('Titel', $mailText) || !array_key_exists('Body', $mailText))
		{
			return new Response('failed');
		}

		$mailBody = str_replace(
			['%POSTID%', '%POSTTITLE%'],
			[$postId, $postTitle],
			$mailText['Body']
		);

		$mailTitle = str_replace(
			['%POSTID%', '%POSTTITLE%'],
			[$postId, $postTitle],
			$mailText['Titel']
		);

		$mail = (new TemplatedEmail())
			->from('302726147@student.rocmondriaan.nl')
			->subject(strip_tags($mailTitle))
			->html($mailBody)
		;

		$newsLetterRegistrationController = new NewsLetterRegistrationController($this->em);
		$mailAddresses = $newsLetterRegistrationController->getAllNewsMembers();

		foreach($mailAddresses as $mailAddress) {
			$mail->addBcc($mailAddress);
		}

		try {
			$this->mailer->send($mail);
			return new Response('succes');
		}
		catch(TransportExceptionInterface $e) {
			return new Response('failed');
		}
	}

	/**
	 * @Route("/api/publish/article/{id}", name="publish_article", methods={"POST"})
	 * @param int $id
	 *
	 * @return Response
	 */
	public function publishArticle(int $id)
	{
		$article = $this->newsArticleRepository->findOneBy(['id' => $id]);

		/** @var $article NewsArticle */
		$article->setPublished(true);

		return new Response('set published success');
	}
}