<?php

namespace App\Domain\Admins\Service;

use App\Factory\SettingsFactory;
use App\Service\Service;
use App\Domain\Admins\Repository\FeedbackRepository;
use Symfony\Component\HttpFoundation\Session\Session;

class FeedbackUpdater extends Service
{
    private $repo;
    private $user;
    private $session;

    public function __construct(SettingsFactory $settings, FeedbackRepository $repo, Session $session)
    {
        parent::__construct($settings);
        $this->repo = $repo;
        $this->session = $session;
        $this->user = $session->get('user');
    }

    public function feedbackUpdater()
    {
        $this->payload->setTemplate('tgdb/feedback.twig');
        return $this->payload;
    }

    public function updateFeedbackLink($link)
    {
        $link = filter_var(
            $link,
            FILTER_VALIDATE_URL,
            \FILTER_FLAG_PATH_REQUIRED | \FILTER_FLAG_QUERY_REQUIRED
        );
        if (
              !str_starts_with(
                  $link,
                  'https://tgstation13.org/phpBB/viewtopic.php?f=37&t='
              )
        ) {
            $this->payload->throwError(400, "This URL is not valid", "tgdb/feedback.twig");
            return;
        }
        $result = $this->repo->insertFeedbackLink($this->user->getCkey(), $link);
        if (true === $result) {
            $this->payload->addSuccessMessage("Your feedback link has been updated");
            //We need to update the user's feedback link and recycle it back
            //into the session, since the session user data doesn't update until
            //they log out and then back in.
            $this->user->setFeedback($link);
            $this->session->set('user', $this->user);
        } else {
            if ($this->settings->getSettingsByKey('debug')) {
                $this->payload->throwError(500, $result, 'tgdb/feedback.twig');
            } else {
                $this->payload->throwError(500, "Your feedback link could not be updated", 'tgdb/feedback.twig');
            }
        }
    }
}
