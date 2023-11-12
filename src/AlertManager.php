<?php
// AlertManager.php
class AlertManager {
    private $users;

    public function __construct() {
        $this->users = [];
    }

    public function addUser(User $user) {
        $this->users[] = $user;
    }

    public function sendAlertToSubscribedUsers(Alert $alert) {
        foreach ($this->users as $user) {
            if ($alert->getTargetUser() === null) {
                # code...
                if ($user->isSubscribedToTopic($alert->getTopic())) {
                    $user->sendAlert($alert);
                }
            }elseif($user->isUserSubscribedToAlert($alert) && $alert->getTargetUser()===$user){
                $user->sendAlert($alert);
            }
        }
    }
}

