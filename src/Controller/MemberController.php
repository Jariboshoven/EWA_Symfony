<?php

namespace App\Controller;

use App\Entity\Member;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class MemberController extends AbstractController {

	private $serializer;
	private $memberRepository;
	private $mailController;

	public function __construct(EntityManagerInterface $em)
	{
		$encoders = [new XmlEncoder(), new JsonEncoder()];
		$normalizers = [new ObjectNormalizer()];
		$this->serializer = new Serializer($normalizers, $encoders);
		$this->mailController = new MailController();

		$this->memberRepository = $em->getRepository(Member::class);
	}

	/**
	 * @Route("/api/members/all", name="get_members", methods={"GET"})
	 *
	 * @return Response
	 */
	public function getAllMembers(): Response
	{
		$partners = $this->memberRepository->findAll();
		$response = $this->serializer->serialize($partners, 'json');
		return new Response($response);
	}

	/**
	 * @Route("/api/members/new/{email}/{firstName}/{lastName}")
	 * @param EntityManagerInterface $em
	 * @param string $email
	 * @param string $firstName
	 * @param string $lastName
	 *
	 * @return Response
	 */
	public function addNew(EntityManagerInterface $em, string $email, string $firstName, string $lastName): Response
	{
		$member = new Member();
		$member
			->setEmail($email)
			->setFirstName($firstName)
			->setLastName($lastName)
		;

		$registeredCountByMail = count($this->memberRepository->findBy(['email' => $email]));
		if($registeredCountByMail >= 1)
		{
			Return new Response('Failed');
		}

		$em->persist($member);
		$em->flush();

		Return new Response('Success');
	}

	/**
	 * @Route("/letsmail")
	 */
	public function mailMembers()
	{
		$members = $this->memberRepository->findAll();

		foreach($members as $member)
		{
			$this->mailController->sendMail(
				[
					'to' => $member->getEmail(),
					'name' => $member->getFirstName()
				]
			);
		}


	}
}