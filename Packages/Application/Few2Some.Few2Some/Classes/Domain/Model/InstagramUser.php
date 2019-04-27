<?php
namespace Few2Some\Few2Some\Domain\Model;

/*
 * This file is part of the Few2Some.Few2Some package.
 */

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class InstagramUser
{

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(nullable=true)
     */
    protected $imageUrl;

    /**
     * @var \Doctrine\Common\Collections\Collection<Participation>
     * @ORM\ManyToMany(mappedBy="recommendedUsers")
     * @ORM\Column(nullable=true)
     */
    protected $recommendedIn;


    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return void
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @param string $imageUrl
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }



}
