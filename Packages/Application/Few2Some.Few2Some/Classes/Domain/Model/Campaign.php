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
class Campaign
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $textToShare;

    /**
     * @var string
     */
    protected $instagramAccounts;

    /**
     * @var \Doctrine\Common\Collections\Collection<Participation>
     * @ORM\OneToMany(mappedBy="campaign")
     * @ORM\Column(nullable=true)
     */
    protected $participations;


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    /**
     * @return string
     */
    public function getTextToShare()
    {
        return $this->textToShare;
    }

    /**
     * @param string $textToShare
     * @return void
     */
    public function setTextToShare($textToShare)
    {
        $this->textToShare = $textToShare;
    }
    /**
     * @return string
     */
    public function getInstagramAccounts()
    {
        return $this->instagramAccounts;
    }

    /**
     * @param string $instagramAccounts
     * @return void
     */
    public function setInstagramAccounts($instagramAccounts)
    {
        $this->instagramAccounts = $instagramAccounts;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipations()
    {
        return $this->participations;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $participations
     */
    public function setParticipations($participations)
    {
        $this->participations = $participations;
    }


}
