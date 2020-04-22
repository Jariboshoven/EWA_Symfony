<?php

namespace App\Controller;

use App\Entity\Partner;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PartnerController extends AbstractController {

	private $serializer;
	private $partnerRepository;

	public function __construct(EntityManagerInterface $em)
	{
		$encoders = [new XmlEncoder(), new JsonEncoder()];
		$normalizers = [new ObjectNormalizer()];
		$this->serializer = new Serializer($normalizers, $encoders);

		$this->partnerRepository = $em->getRepository(Partner::class);
	}

	/**
	 * @Route("/api/partner/all", name="get_partners", methods={"GET"})
	 *
	 * @return Response
	 */
	public function getAllPartners(): Response
	{
		$partners = $this->partnerRepository->getAll();
		$response = $this->serializer->serialize($partners, 'json');

		return new Response($response);
	}

	/**
	 * @Route("/api/partner/id/{id}", name="get_partner_by_id", methods={"GET"})
	 * @param int $id
	 *
	 * @return Response
	 */
	public function getPartnerById(int $id): Response
	{
		$partner = $this->partnerRepository->findBy(['id' => $id]);
		$response = $this->serializer->serialize($partner, 'json');
		return new Response($response);
	}
}