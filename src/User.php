<?php 
require_once 'Observer.php';
// User.php

class User implements Observer {
    private $userId;
    private $name;
    private $subscribedTopics = array();
    private $unreadAlerts = array();
    private $readAlerts = array();
    public function __construct($userId, $name) {
        $this->userId = $userId;
        $this->name = $name;
    }
    public function getName() {
        return $this->name;
    }

    public function subscribeToTopic(Topic $topic) {
        if (!in_array($topic, $this->subscribedTopics)) {
            $this->subscribedTopics[] = $topic;
            $topic->addObserver($this);
        }
    }

    public function getSubscribedTopics() {
        return $this->subscribedTopics;
    }

    public function sendAlert(Alert $alert) {
        // Marcar la alerta como leída cuando se envía a través de un tema
        if ($this->isAlertFromTopic($alert)) {
           //$this->markAlertAsRead($alert);
        }
        // Enviar alerta directa a un usuario
        $this->update($alert);
    }

    public function update(Alert $alert) {
        if ($alert->getExpirationDateTime() == null || $alert->getExpirationDateTime() > new DateTime()) {
            // Verifica si la alerta ya está marcada como leída antes de agregarla a las leídas
            if (!$alert->isRead()) {
                if ($alert->getAlertType() == Alert::ALERT_TYPE_URGENTE) {
                    // Agregar alerta urgente al principio (LIFO)
                    array_unshift($this->unreadAlerts, $alert);
                } else {
                    // Agregar alerta informativa al final (FIFO)
                    $this->unreadAlerts[] = $alert;
                }
            } else {
                $this->readAlerts[] = $alert;
            }
        }
    }
    
    public function isUserSubscribedToAlert(Alert $alert) {
        // Verifica si el usuario está suscrito a la alerta
        if ($alert->isForAllUsers()&& in_array($alert->getTopic(), $this->subscribedTopics)) {
            return true; // Si la alerta es para todos los usuarios, entonces el usuario está suscrito
        }
    
        return in_array($alert->getTopic(), $this->subscribedTopics);
    }

    private function isAlertFromTopic(Alert $alert) {
        return $alert->getTopic() !== null;
    }
    public function isSubscribedToTopic(Topic $topic) {
        return in_array($topic, $this->subscribedTopics);
    }
    public function getUnreadAlertsForUser() {
        return $this->sortAlerts($this->unreadAlerts);
    }
    
    public function getUnreadAlertsForTopic(Topic $topic) {
        $alertsForTopic = array_filter(
            $this->unreadAlerts,
            function ($alert) use ($topic) {
                return $alert->getTopic() == $topic;
            }
        );
        return $this->sortAlerts($alertsForTopic);
    }
    
    private function sortAlerts($alerts) {
        // Ordenar las alertas según los criterios especificados
        usort($alerts, function ($a, $b) {
            if ($a->getAlertType() == Alert::ALERT_TYPE_URGENTE && $b->getAlertType() == Alert::ALERT_TYPE_INFORMATIVA) {
                return -1;
            } elseif ($a->getAlertType() == Alert::ALERT_TYPE_INFORMATIVA && $b->getAlertType() == Alert::ALERT_TYPE_URGENTE) {
                return 1;
            } else {
                return 0;
            }
        });
        return $alerts;
    }
    
    
    
    public function getReadAlerts() {
        return array_filter(
            $this->readAlerts,
            function ($alert) {
                return $alert->isRead();
            }
        );
    }
    public function markAlertAsRead(Alert $alert) {
        $alert->markAsRead();
    }
}
