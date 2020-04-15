<?php

namespace App\Controller;

use App\Entity\WebsiteVar;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class WebsiteVarController extends AbstractController {
	private $serializer;
	private $websiteVarRepository;

	public function __construct(EntityManagerInterface $em)
	{
		$encoders = [new XmlEncoder(), new JsonEncoder()];
		$normalizers = [new ObjectNormalizer()];
		$this->serializer = new Serializer($normalizers, $encoders);

		$this->websiteVarRepository = $em->getRepository(WebsiteVar::class);
	}

	/**
	 * @Route("/api/websitevars/all", name="get_website_vars", methods={"GET"})
	 *
	 * @return Response
	 */
	public function getAllWebsiteVars(): Response
	{
		$partners = $this->websiteVarRepository->findAll();
		$response = $this->serializer->serialize($partners, 'json');
		return new Response($response);
	}

	/**
	 * @Route("/api/websitevars/{component}", name="get_website_vars", methods={"GET"})
	 *
	 * @return Response
	 */
	public function getWebsiteVarsByComponent($component): Response
	{
		$partners = $this->websiteVarRepository->findVarsByComponent($component);
		$response = $this->serializer->serialize($partners, 'json');
		return new Response($response);
	}
}