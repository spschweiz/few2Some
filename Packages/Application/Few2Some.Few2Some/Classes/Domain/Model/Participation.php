<?php
namespace Few2Some\Few2Some\Domain\Model;

/*
 * This file is part of the Few2Some.Few2Some package.
 */

use Doctrine\Common\Collections\ArrayCollection;
use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Participation
{

    /**
     * @var \Few2Some\Few2Some\Domain\Model\Campaign
     * @ORM\ManyToOne(inversedBy="participations")
     */
    protected $campaign;

    /**
     * @var \Few2Some\Few2Some\Domain\Model\InstagramUser
     * @ORM\ManyToOne
     */
    protected $activist;

    /**
     * @var \Doctrine\Common\Collections\Collection<InstagramUser>
     * @ORM\ManyToMany(inversedBy="recommendedIn")
     * @ORM\JoinTable(name="few2some_few2some_domain_model_recommendedusers_join")
     * @ORM\Column(nullable=true)
     */
    protected $recommendedUsers;

    /**
     * @var bool
     * @ORM\Column(nullable=true)
     */
    protected $isProcessed;


    /**
     * @return \Few2Some\Few2Some\Domain\Model\Campaign
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * @param \Few2Some\Few2Some\Domain\Model\Campaign $campaign
     * @return void
     */
    public function setCampaign($campaign)
    {
        $this->campaign = $campaign;
    }
    /**
     * @return \Few2Some\Few2Some\Domain\Model\InstagramUser
     */
    public function getActivist()
    {
        return $this->activist;
    }

    /**
     * @param \Few2Some\Few2Some\Domain\Model\InstagramUser $activist
     * @return void
     */
    public function setActivist($activist)
    {
        $this->activist = $activist;
    }
    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecommendedUsers()
    {
        return $this->recommendedUsers;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $recommendedUsers
     * @return void
     */
    public function setRecommendedUsers($recommendedUsers)
    {
        $this->recommendedUsers = $recommendedUsers;
    }

    /**
     * @param InstagramUser $recommendedUser
     * @return void
     */
    public function addRecommendedUser($recommendedUser)
    {
        if($this->recommendedUsers == null){
            $this->recommendedUsers = new ArrayCollection();
        }

        if(!$this->recommendedUsers->contains($recommendedUser)) {
            $this->recommendedUsers->add($recommendedUser);
        }
    }

    /**
     * @return bool
     */
    public function getIsProcessed()
    {
        return $this->isProcessed;
    }

    /**
     * @param bool $isProcessed
     */
    public function setIsProcessed($isProcessed)
    {
        $this->isProcessed = $isProcessed;
    }


}
