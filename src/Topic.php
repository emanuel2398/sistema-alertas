<?php
// Topic.php

class Topic {
    private $topicId;
    private $name;
    private $observers = array();
    private $alerts = array();

    public function __construct($topicId, $name) {
        $this->topicId = $topicId;
        $this->name = $name;
    }
    public function getName() {
        return $this->name;
    }
    public function getTopicId() {
        return $this->topicId;
    }
    

    public function addObserver(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function sendAlert(Alert $alert) {
        $this->alerts[] = $alert;
        foreach ($this->observers as $observer) {
            $observer->update($alert);
        }
    }

    public function getAlerts() {
        return $this->alerts;
    }

    public function getUnreadAlertsForUser(User $user) {
        return array_filter(
            $this->alerts,
            function ($alert) use ($user) {
                return !$alert->isRead() && !$alert->isExpired() && $this->isAlertForUser($alert, $user);
            }
        );
    }

    private function isAlertForUser(Alert $alert, User $user) {
        return $alert->isForAllUsers() || $alert->getTargetUser() == $user;
    }

    public function getUnreadAlerts() {
        return array_filter(
            $this->alerts,
            function ($alert) {
                return !$alert->isExpired();
            }
        );
    }
}
