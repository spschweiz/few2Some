<?php
namespace Few2Some\Few2Some\Domain\Repository;

/*
 * This file is part of the Few2Some.Few2Some package.
 */

use Few2Some\Few2Some\Domain\Model\Campaign;
use Few2Some\Few2Some\Domain\Model\InstagramUser;
use Few2Some\Few2Some\Domain\Model\Participation;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class ParticipationRepository extends Repository
{

    // add customized methods here
    /**
     * @param InstagramUser $instagramUser
     * @param Campaign $campaign
     * @return Participation
     */
    public function findOneByActivistAndCampaign($instagramUser, $campaign){
        $query = $this->createQuery();

        $constraints = [];
        $constraints[] = $query->equals('activist', $instagramUser);
        $constraints[] = $query->equals('campaign', $campaign);

        $query->matching($query->logicalAnd($constraints));

        $result = $query->execute();

        return $result->getFirst();
    }
}
