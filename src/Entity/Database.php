<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\DatabaseRepository")
 */
class Database
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
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titleRemainder;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $partTitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titleOrgScript;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sortingTitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $publisher;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $publisherWebsite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $publisherWebsiteLinkText;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="date")
     */
    private $lastModified;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bibid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $needsProxy;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $databaseGuide;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $databaseGuideLinkText;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $accessPolicy;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Keyword", mappedBy="databases")
     */
    private $keywords;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Type", mappedBy="databases")
     */
    private $types;

    public function __construct()
    {
        $this->keywords = new ArrayCollection();
        $this->types = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitleRemainder(): ?string
    {
        return $this->titleRemainder;
    }

    public function setTitleRemainder(?string $titleRemainder): self
    {
        $this->titleRemainder = $titleRemainder;

        return $this;
    }

    public function getPartTitle(): ?string
    {
        return $this->partTitle;
    }

    public function setPartTitle(?string $partTitle): self
    {
        $this->partTitle = $partTitle;

        return $this;
    }

    public function getTitleOrgScript(): ?string
    {
        return $this->titleOrgScript;
    }

    public function setTitleOrgScript(?string $titleOrgScript): self
    {
        $this->titleOrgScript = $titleOrgScript;

        return $this;
    }

    public function getSortingTitle(): ?string
    {
        return $this->sortingTitle;
    }

    public function setSortingTitle(?string $sortingTitle): self
    {
        $this->sortingTitle = $sortingTitle;

        return $this;
    }

    public function getPublisher(): ?string
    {
        return $this->publisher;
    }

    public function setPublisher(?string $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function getPublisherWebsite(): ?string
    {
        return $this->publisherWebsite;
    }

    public function setPublisherWebsite(?string $publisherWebsite): self
    {
        $this->publisherWebsite = $publisherWebsite;

        return $this;
    }

    public function getPublisherWebsiteLinkText(): ?string
    {
        return $this->publisherWebsiteLinkText;
    }

    public function setPublisherWebsiteLinkText(?string $publisherWebsiteLinkText): self
    {
        $this->publisherWebsiteLinkText = $publisherWebsiteLinkText;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getLastModified(): ?\DateTimeInterface
    {
        return $this->lastModified;
    }

    public function setLastModified(\DateTimeInterface $lastModified): self
    {
        $this->lastModified = $lastModified;

        return $this;
    }

    public function getBibid(): ?string
    {
        return $this->bibid;
    }

    public function setBibid(?string $bibid): self
    {
        $this->bibid = $bibid;

        return $this;
    }

    public function getNeedsProxy(): ?string
    {
        return $this->needsProxy;
    }

    public function setNeedsProxy(?string $needsProxy): self
    {
        $this->needsProxy = $needsProxy;

        return $this;
    }

    public function getDatabaseGuide(): ?string
    {
        return $this->databaseGuide;
    }

    public function setDatabaseGuide(?string $databaseGuide): self
    {
        $this->databaseGuide = $databaseGuide;

        return $this;
    }

    public function getDatabaseGuideLinkText(): ?string
    {
        return $this->databaseGuideLinkText;
    }

    public function setDatabaseGuideLinkText(?string $databaseGuideLinkText): self
    {
        $this->databaseGuideLinkText = $databaseGuideLinkText;

        return $this;
    }

    public function getAccessPolicy(): ?string
    {
        return $this->accessPolicy;
    }

    public function setAccessPolicy(?string $accessPolicy): self
    {
        $this->accessPolicy = $accessPolicy;

        return $this;
    }

    /**
     * @return Collection|Keyword[]
     */
    public function getKeywords(): Collection
    {
        return $this->keywords;
    }

    public function addKeyword(Keyword $keyword): self
    {
        if (!$this->keywords->contains($keyword)) {
            $this->keywords[] = $keyword;
            $keyword->addDatabase($this);
        }

        return $this;
    }

    public function removeKeyword(Keyword $keyword): self
    {
        if ($this->keywords->contains($keyword)) {
            $this->keywords->removeElement($keyword);
            $keyword->removeDatabase($this);
        }

        return $this;
    }

    /**
     * @return Collection|Type[]
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(Type $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types[] = $type;
            $type->addDatabase($this);
        }

        return $this;
    }

    public function removeType(Type $type): self
    {
        if ($this->types->contains($type)) {
            $this->types->removeElement($type);
            $type->removeDatabase($this);
        }

        return $this;
    }
}
