<?php

namespace App\Entity;

use App\Repository\StatisticRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestampable;

/**
 * @ORM\Entity(repositoryClass=StatisticRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Statistic
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
     * @ORM\Column(type="integer")
     */
    private $comment_count;

    /**
     * @ORM\Column(type="integer")
     */
    private $dislike_count;

    /**
     * @ORM\Column(type="integer")
     */
    private $favorite_count;

    /**
     * @ORM\Column(type="integer")
     */
    private $like_count;

    /**
     * @ORM\Column(type="integer")
     */
    private $view_count;

    /**
     * @ORM\ManyToOne(targetEntity=Video::class, inversedBy="Statistics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $video;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentCount(): ?int
    {
        return $this->comment_count;
    }

    public function setCommentCount(int $comment_count): self
    {
        $this->comment_count = $comment_count;

        return $this;
    }

    public function getDislikeCount(): ?int
    {
        return $this->dislike_count;
    }

    public function setDislikeCount(int $dislike_count): self
    {
        $this->dislike_count = $dislike_count;

        return $this;
    }

    public function getFavoriteCount(): ?int
    {
        return $this->favorite_count;
    }

    public function setFavoriteCount(int $favorite_count): self
    {
        $this->favorite_count = $favorite_count;

        return $this;
    }

    public function getLikeCount(): ?int
    {
        return $this->like_count;
    }

    public function setLikeCount(int $like_count): self
    {
        $this->like_count = $like_count;

        return $this;
    }

    public function getViewCount(): ?int
    {
        return $this->view_count;
    }

    public function setViewCount(int $view_count): self
    {
        $this->view_count = $view_count;

        return $this;
    }

    public function getVideo(): ?Video
    {
        return $this->video;
    }

    public function setVideo(?Video $video): self
    {
        $this->video = $video;

        return $this;
    }
}
