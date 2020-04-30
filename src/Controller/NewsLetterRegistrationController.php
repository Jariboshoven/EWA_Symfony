<?php

namespace App\Controller;

use App\Entity\NewsletterRegistration;
use App\Entity\Partner;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class NewsLetterRegistrationController extends AbstractController {

	private $serializer;
	private $newsMembersRepository;

	public function __construct(EntityManagerInterface $em)
	{
		$encoders = [new XmlEncoder(), new JsonEncoder()];
		$normalizers = [new ObjectNormalizer()];
		$this->serializer = new Serializer($normalizers, $encoders);

		$this->newsMembersRepository = $em->getRepository(NewsletterRegistration::class);
	}

	/**
	 * @Route("/api/newsmember/add/{email}", name="set_newsmember", methods={"PUT"})
	 *
	 * @param string $email
	 *
	 * @param EntityManagerInterface $em
	 *
	 * @return Response
	 */
	public function setNewsMember(string $email, EntityManagerInterface $em): Response
	{
		if (count($this->getNewsMemberByEmail($email)) >= 1)
		{
			return new Response('Emailadres: ' . $email . ' is al geregistreerd voor nieuws berichten.');
		}

		$newsMember = new NewsletterRegistration();
		$newsMember->setEmail($email);

		$em->persist($newsMember);
		$em->flush();

		return new Response('succes');
	}

	/**
	 * @param string $email
	 *
	 * @return array
	 */
	public function getNewsMemberByEmail(string $email): array
	{
		return $this->newsMembersRepository->findBy(['email' => $email]);
	}

	public function getAllNewsMembers(): array
	{
		$members = $this->newsMembersRepository->findAll();
		$membersJson = $this->serializer->serialize($members, 'json');
		$memberArray = json_decode($membersJson,true);

		$memberMails = [];
		foreach($memberArray as $member) {
			array_push($memberMails, $member['email']);
		}
		return $memberMails;
	}
}