<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InformationRepository")
 * @Vich\Uploadable()
 */
class Information
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $Title;

	/**
	 * @ORM\Column(type="text")
	 */
	private $Description;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $CreationDate;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $UpdatedAt;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $Image;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $Published;

	/**
	 * @Vich\UploadableField(mapping="news", fileNameProperty="Image")
	 */
	private $ImageFile;

	/**
	 * NewsArticle constructor.
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->setCreationDate(time()) ;
		$this->setUpdatedAt(time());
	}

	/**
	 * @return int|null
	 */
	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * @return string|null
	 */
	public function getTitle(): ?string
	{
		return $this->Title;
	}

	/**
	 * @param string $Title
	 *
	 * @return $this
	 * @throws Exception
	 */
	public function setTitle(string $Title): self
	{
		$this->Title = $Title;
		if($Title)
		{
			$this->setUpdatedAt(time());
		}
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getDescription(): ?string
	{
		return $this->Description;
	}

	/**
	 * @param string $Description
	 *
	 * @return $this
	 * @throws Exception
	 */
	public function setDescription(string $Description): self
	{
		$this->Description = $Description;
		if($Description)
		{
			$this->setUpdatedAt(time());
		}
		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getCreationDate(): ?int
	{
		return $this->CreationDate;
	}

	/**
	 * @param int $CreationDate
	 * @return $this
	 */
	public function setCreationDate(int $CreationDate): self
	{
		$this->CreationDate = $CreationDate;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getImage(): ?string
	{
		return $this->Image;
	}

	/**
	 * @param string|null $Image
	 *
	 * @return $this
	 * @throws Exception
	 */
	public function setImage(?string $Image): self
	{
		$this->Image = $Image;
		if($Image) {
			$this->setUpdatedAt(time());
		}
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getImageFile()
	{
		return $this->ImageFile;
	}

	/**
	 * @param mixed $ImageFile
	 *
	 * @throws Exception
	 */
	public function setImageFile($ImageFile): void
	{
		$this->ImageFile = $ImageFile;
		if($ImageFile)
		{
			$this->setUpdatedAt(time());
		}
	}

	/**
	 * @return int|null
	 */
	public function getUpdatedAt(): ?int
	{
		return $this->UpdatedAt;
	}

	/**
	 * @param int $UpdatedAt
	 * @return $this
	 */
	public function setUpdatedAt(int $UpdatedAt): self
	{
		$this->UpdatedAt = $UpdatedAt;
		return $this;
	}

	public function getPublished(): ?bool
	{
		return $this->Published;
	}

	public function setPublished(bool $isPublished): self
	{
		$this->Published = $isPublished;
		if($isPublished) {
			$this->setUpdatedAt(time());
		}
		return $this;
	}
}
