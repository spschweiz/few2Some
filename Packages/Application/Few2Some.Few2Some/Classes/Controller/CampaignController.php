<?php
namespace Few2Some\Few2Some\Controller;

/*
 * This file is part of the Few2Some.Few2Some package.
 */

use Few2Some\Few2Some\Domain\Model\Participation;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;
use Few2Some\Few2Some\Domain\Model\Campaign;

class CampaignController extends ActionController
{

    /**
     * @Flow\Inject
     * @var \Few2Some\Few2Some\Domain\Repository\InstagramUserRepository
     */
    protected $instagramUserRepository;

    /**
     * @Flow\Inject
     * @var \Few2Some\Few2Some\Domain\Repository\CampaignRepository
     */
    protected $campaignRepository;

    /**
     * @Flow\Inject
     * @var \Few2Some\Few2Some\Domain\Repository\ParticipationRepository
     */
    protected $participationRepository;


    /**
     * @return void
     */
    public function indexAction()
    {
        $campaigns = $this->campaignRepository->findAll();
        $this->view->assign('campaigns', $campaigns);
    }

    /**
     * @param \Few2Some\Few2Some\Domain\Model\Campaign $campaign
     * @return void
     */
    public function participateAction(Campaign $campaign)
    {
        $this->view->assign('campaign', $campaign);
    }

    /**
     * @param string $username
     * @param \Few2Some\Few2Some\Domain\Model\Campaign $campaign
     * @return void
     */
    public function showRecommendationsAction($username, Campaign $campaign)
    {
        $activist = $this->instagramUserRepository->findOrCreateByUsername($username);

        $participation = $this->participationRepository->findOneByActivistAndCampaign($activist, $campaign);

        if($participation == null) {
            $participation = new Participation();
            $participation->setActivist($activist);
            $participation->setCampaign($campaign);
            $this->participationRepository->add($participation);

            $this->persistenceManager->persistAll();

            file_get_contents("https://5e23594d.eu.ngrok.io/"
                . $this->persistenceManager->getIdentifierByObject($participation)
                . "/" . trim($username) . "/" . $campaign->getInstagramAccounts());
        }

        $this->view->assign('participation', $participation);
    }

    /**
     * @param \Few2Some\Few2Some\Domain\Model\Participation $participation
     * @param string $username
     * @param string $image
     * @return void
     */
    public function addRecommendationAction(Participation $participation, $username, $image = '')
    {
        $instagramUser = $this->instagramUserRepository->findOrCreateByUsername($username);
        $instagramUser->setImageUrl($image);
        $this->instagramUserRepository->update($instagramUser);

        $participation->addRecommendedUser($instagramUser);
        $this->participationRepository->update($participation);

        $this->persistenceManager->persistAll();
    }

    /**
     * @param \Few2Some\Few2Some\Domain\Model\Participation $participation
     * @return void
     */
    public function getRecommendationsAction(Participation $participation)
    {
        $this->view->assign('participation', $participation);
    }

    /**
     * @param \Few2Some\Few2Some\Domain\Model\Campaign $campaign
     * @return void
     */
    public function showAction(Campaign $campaign)
    {
        $this->view->assign('campaign', $campaign);
    }

    /**
     * @return void
     */
    public function newAction()
    {
    }

    /**
     * @param \Few2Some\Few2Some\Domain\Model\Campaign $newCampaign
     * @return void
     */
    public function createAction(Campaign $newCampaign)
    {
        $this->campaignRepository->add($newCampaign);
        $this->addFlashMessage('Created a new campaign.');
        $this->redirect('index');
    }

    /**
     * @param \Few2Some\Few2Some\Domain\Model\Campaign $campaign
     * @return void
     */
    public function editAction(Campaign $campaign)
    {
        $this->view->assign('campaign', $campaign);
    }

    /**
     * @param \Few2Some\Few2Some\Domain\Model\Campaign $campaign
     * @return void
     */
    public function updateAction(Campaign $campaign)
    {
        $this->campaignRepository->update($campaign);
        $this->addFlashMessage('Updated the campaign.');
        $this->redirect('index');
    }

    /**
     * @param \Few2Some\Few2Some\Domain\Model\Campaign $campaign
     * @return void
     */
    public function deleteAction(Campaign $campaign)
    {
        $this->campaignRepository->remove($campaign);
        $this->addFlashMessage('Deleted a campaign.');
        $this->redirect('index');
    }
}
