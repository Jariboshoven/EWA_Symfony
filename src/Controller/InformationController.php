<?php

namespace App\Controller;

use App\Entity\Information;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class InformationController extends AbstractController {

	private $serializer;
	private $informationRepository;

	public function __construct(EntityManagerInterface $em)
	{
		$encoders = [new XmlEncoder(), new JsonEncoder()];
		$normalizers = [new ObjectNormalizer()];
		$this->serializer = new Serializer($normalizers, $encoders);

		$this->informationRepository = $em->getRepository(Information::class);
	}

	/**
	 * @Route("/api/information/all", name="get_information", methods={"GET"})
	 *
	 * @return Response
	 */
	public function getAllInformation(): Response
	{
		$newsArticles = $this->informationRepository->findPublishedArticles();
		$response = $this->serializer->serialize($newsArticles, 'json');
		return new Response($response);
	}

	/**
	 * @Route("/api/information/recent/{number}", name="get_recent_information", methods={"GET"})
	 * @param int $number
	 *
	 * @return Response
	 */
	public function getRecentInformation(int $number): Response
	{
		$newsArticles = $this->informationRepository->findRecentArticles($number);
		$response = $this->serializer->serialize($newsArticles, 'json');
		return new Response($response);
	}
}