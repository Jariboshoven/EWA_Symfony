<?php

namespace App\Controller;

use App\Entity\Information;
use Doctrine\ORM\EntityManagerInterface;

use Exception;
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
	 * @throws Exception
	 */
	public function getAllInformation(): Response
	{
		$informationArticles = $this->informationRepository->findPublishedArticles();

		$response = $this->serializer->serialize($informationArticles, 'json');
		return new Response($response);
	}

	/**
	 * @Route("/api/information/recent/{number}", name="get_recent_information", methods={"GET"})
	 * @param int $number
	 *
	 * @return Response
	 * @throws Exception
	 */
	public function getRecentInformation(int $number): Response
	{
		$informationArticles = $this->informationRepository->findRecentArticles($number);

		$response = $this->serializer->serialize($informationArticles, 'json');
		return new Response($response);
	}
}