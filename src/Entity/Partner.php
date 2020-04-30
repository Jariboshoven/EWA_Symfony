<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PartnerRepository")
 * @Vich\Uploadable()
 */
class Partner
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
    private $name;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $url;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

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
     * @Vich\UploadableField(mapping="partner", fileNameProperty="Image")
     */
    private $ImageFile;

    /**
     * NewsArticle constructor.
     * @throws Exception
     */
    public function __construct()
    {
		$this->setCreationDate(time());
		$this->setUpdatedAt(time());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

	/**
	 * @param string $name
	 *
	 * @return $this
	 * @throws Exception
	 */
    public function setName(string $name): self
    {
        $this->name = $name;
		if($name){
			$this->setUpdatedAt(time());
		}
        return $this;
    }

	/**
	 * @return string|null
	 */
	public function getURL(): ?string
	{
		return $this->url;
	}

	/**
	 * @param string $url
	 *
	 * @return $this
	 * @throws Exception
	 */
	public function setURL(string $url): self
	{
		$this->url = $url;
		if($url){
			$this->setUpdatedAt(time());
		}
		return $this;
	}

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

	/**
	 * @param string $description
	 *
	 * @return $this
	 * @throws Exception
	 */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        if($description)
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
}
