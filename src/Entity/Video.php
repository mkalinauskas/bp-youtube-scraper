<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestampable;

/**
 * @ORM\Entity(repositoryClass=VideoRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Video
{
    /*
     * Timestampable trait
     */
    use Timestampable;    

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=24)
     */
    private $video_id;

    /**
     * @ORM\ManyToOne(targetEntity=Channel::class, inversedBy="videos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $channel;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, mappedBy="videos")
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=Statistic::class, mappedBy="video", orphanRemoval=true)
     */
    private $statistics;

    /**
     * @ORM\Column(type="decimal", nullable=true, scale=2)
     */
    private $performance;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->statistics = new ArrayCollection();
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

    public function getVideoId(): ?string
    {
        return $this->video_id;
    }

    public function setVideoId(string $video_id): self
    {
        $this->video_id = $video_id;

        return $this;
    }

    public function getChannel(): ?Channel
    {
        return $this->channel;
    }

    public function setChannel(?Channel $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addVideo($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeVideo($this);
        }

        return $this;
    }

    /**
     * @return Collection|Statistic[]
     */
    public function getStatistics(): Collection
    {
        return $this->statistics;
    }

    public function addStatistic(Statistic $statistic): self
    {
        if (!$this->statistics->contains($statistic)) {
            $this->statistics[] = $statistic;
            $statistic->setVideo($this);
        }

        return $this;
    }

    public function removeStatistic(Statistic $statistic): self
    {
        if ($this->statistics->removeElement($statistic)) {
            // set the owning side to null (unless already changed)
            if ($statistic->getVideo() === $this) {
                $statistic->setVideo(null);
            }
        }

        return $this;
    }

    public function getPerformance(): ?float
    {
        return $this->performance;
    }

    public function setPerformance(?float $performance): self
    {
        $this->performance = $performance;

        return $this;
    }
}
