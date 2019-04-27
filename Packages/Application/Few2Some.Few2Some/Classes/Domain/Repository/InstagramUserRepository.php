<?php
namespace Few2Some\Few2Some\Domain\Repository;

/*
 * This file is part of the Few2Some.Few2Some package.
 */

use Few2Some\Few2Some\Domain\Model\InstagramUser;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class InstagramUserRepository extends Repository
{

    /**
     * @param string $username
     */
    public function findOrCreateByUsername($username){
        $user = $this->findOneByUsername($username);

        if($user == null){
            $user = new InstagramUser();
            $user->setUsername($username);
            $this->add($user);
        }

        return $user;
    }
}
