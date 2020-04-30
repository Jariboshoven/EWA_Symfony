<?php

namespace App\Controller;

use App\Entity\NewsArticle;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Message;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class NewsArticleController extends AbstractController {

	private $serializer;
	private $newsArticleRepository;

	private $em;
	private $mailer;

	public function __construct(EntityManagerInterface $em, \Swift_Mailer $mailer)
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
	 * @Route("/api/notify/members", name="f", methods={"GET"})
	 *
	 * @return Response
	 * @throws TransportExceptionInterface
	 */
	public function notifyMembers()
	{
		$newsLetterRegistrationController = new NewsLetterRegistrationController($this->em);
		$mailAddresses = $newsLetterRegistrationController->getAllNewsMembers();

		$message = (new Swift_Message('Hello Email'))
			->setFrom('send@example.com')
			->setTo('jarbo0174@outlook.com')
			->setBody(
				'hoi'
			);

		try {
			$this->mailer->send($message);
			var_dump('done');
		}
		catch(TransportExceptionInterface $e) {
			trigger_error('Failed to send Email');
		}

		return new Response('succes');
	}
}