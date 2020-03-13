<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreationDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $UpdatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Image;

    /**
     * @Vich\UploadableField(mapping="images", fileNameProperty="Image")
     */
    private $ImageFile;

    /**
     * NewsArticle constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->CreationDate = new DateTime();
        $this->setUpdatedAt(new DateTime());
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
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

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
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        if($description)
        {
            $this->setUpdatedAt(new DateTime());
        }
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getCreationDate(): ?DateTime
    {
        return $this->CreationDate;
    }

    /**
     * @param DateTimeInterface $CreationDate
     * @return $this
     */
    public function setCreationDate(DateTimeInterface $CreationDate): self
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
     * @return $this
     */
    public function setImage(?string $Image): self
    {
        $this->Image = $Image;
        if($Image) {
            $this->setUpdatedAt(new DateTime);
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
     */
    public function setImageFile($ImageFile): void
    {
        $this->ImageFile = $ImageFile;
        if($ImageFile)
        {
            $this->setUpdatedAt(new DateTime);
        }
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->UpdatedAt;
    }

    /**
     * @param DateTimeInterface $UpdatedAt
     * @return $this
     */
    public function setUpdatedAt(DateTimeInterface $UpdatedAt): self
    {
        $this->UpdatedAt = $UpdatedAt;
        return $this;
    }
}
