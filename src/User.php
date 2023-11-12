<?php 
require_once 'Observer.php';

class User implements Observer {
    private $userId;
    private $name;
    private $subscribedTopics = array();
    private $unreadAlerts = array();
    private $readAlerts = array();

    // Constructor para inicializar las propiedades del usuario
    public function __construct($userId, $name) {
        $this->userId = $userId;
        $this->name = $name;
    }

    // Obtener el nombre del usuario
    public function getName() {
        return $this->name;
    }

    // Obtener el ID del usuario
    public function getuserId() {
        return $this->userId;
    }

    // Suscribir al usuario a un tema y agregarlo a los observadores del tema
    public function subscribeToTopic(Topic $topic) {
        if (!in_array($topic, $this->subscribedTopics)) {
            $this->subscribedTopics[] = $topic;
            $topic->addObserver($this);
        }
    }

    // Obtener los temas a los que está suscrito el usuario
    public function getSubscribedTopics() {
        return $this->subscribedTopics;
    }

    // Enviar una alerta al usuario
    public function sendAlert(Alert $alert) {
        // Marcar la alerta como leída cuando se envía a través de un tema
        $this->update($alert);
    }

    // Método llamado por los observadores cuando se actualiza una alerta
    public function update(Alert $alert) {
        if ($alert->getExpirationDateTime() == null || $alert->getExpirationDateTime() > new DateTime()) {
            // Verificar si la alerta ya está marcada como leída antes de agregarla a las leídas
            if (!$alert->isRead()) {
                // Agregar alerta urgente al principio (LIFO) o informativa al final (FIFO)
                $this->unreadAlerts = $alert->getAlertType() == Alert::ALERT_TYPE_URGENTE ?
                    array_merge([$alert], $this->unreadAlerts) :
                    array_merge($this->unreadAlerts, [$alert]);
            } 
        }
    }
    
    // Verificar si el usuario está suscrito a una alerta específica
    public function isUserSubscribedToAlert(Alert $alert) {
        if ($alert->isForAllUsers() && in_array($alert->getTopic(), $this->subscribedTopics)) {
            return true; // Si la alerta es para todos los usuarios, entonces el usuario está suscrito
        }
        return in_array($alert->getTopic(), $this->subscribedTopics);
    }

    // Verificar si el usuario está suscrito a un tema específico
    public function isSubscribedToTopic(Topic $topic) {
        return in_array($topic, $this->subscribedTopics);
    }

    // Obtener las alertas no leídas del usuario y ordenarlas
    public function getUnreadAlertsForUser() {
        return $this->sortAlerts($this->unreadAlerts);
    }
    
    // Obtener las alertas no leídas para un tema específico y ordenarlas
    public function getUnreadAlertsForTopic(Topic $topic) {
        $alertsForTopic = array_filter(
            $this->unreadAlerts,
            function ($alert) use ($topic) {
                return $alert->getTopic() == $topic;
            }
        );
        return $this->sortAlerts($alertsForTopic);
    }
    
    // Ordenar las alertas según los criterios especificados
    private function sortAlerts($alerts) {
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

    // Obtener las alertas leídas del usuario
    public function getReadAlerts() {
        return array_filter(
            $this->readAlerts,
            function ($alert) {
                return $alert->isRead();
            }
        );
    }

    // Marcar la alerta como leída y moverla de no leídas a leídas
    public function markAlertAsRead(Alert $alert) {
        if (!$alert->isRead()) {
            $alert->markAsRead();
            $key = array_search($alert, $this->unreadAlerts);
            if ($key !== false) {
                unset($this->unreadAlerts[$key]);
                $this->readAlerts[] = $alert;
            }
        }
    }

    // Verificar si el usuario ha recibido la alerta (leída o no)
    public function hasReceivedAlert(Alert $alert) {
        return in_array($alert, $this->unreadAlerts) || in_array($alert, $this->readAlerts);
    }
}
