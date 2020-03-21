<?php

namespace App\Controller;

use App\Entity\NewsArticle;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class NewsArticleController extends AbstractController {

	private $serializer;
	private $newsArticleRepository;

	public function __construct(EntityManagerInterface $em)
	{
		$encoders = [new XmlEncoder(), new JsonEncoder()];
		$normalizers = [new ObjectNormalizer()];
		$this->serializer = new Serializer($normalizers, $encoders);

		$this->newsArticleRepository = $em->getRepository(NewsArticle::class);
	}

	/**
	 * @Route("/api/news/all", name="get_news", methods={"GET"})
	 */
	public function getAllNewsArticles(): Response
	{
		$newsArticles = $this->newsArticleRepository->findPublishedArticles();
		$response = $this->serializer->serialize($newsArticles, 'json');
		return new Response($response);
	}

	/**
	 * @Route("/api/news/recent/{number}", name="get_recent_news", methods={"GET"})
	 */
	public function getRecentNewsArticles($number): Response
	{
		$newsArticles = $this->newsArticleRepository->findRecentArticles($number);
		$response = $this->serializer->serialize($newsArticles, 'json');
		return new Response($response);
	}
}