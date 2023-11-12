<?php
class Topic {
    private $topicId;
    private $name;
    private $observers = array(); // Observadores suscritos al tema
    private $alerts = array(); // Alertas asociadas al tema

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

    // Añadir un observador al tema
    public function addObserver(Observer $observer) {
        $this->observers[] = $observer;
    }

    // Enviar una alerta a todos los observadores del tema
    public function sendAlert(Alert $alert) {
        $this->alerts[] = $alert; // Agregar la alerta a la lista del tema
        foreach ($this->observers as $observer) {
            $observer->update($alert); // Notificar a cada observador sobre la nueva alerta
        }
    }

    // Obtener todas las alertas asociadas al tema
    public function getAlerts() {
        return $this->alerts;
    }

    // Obtener alertas no leídas para un usuario específico
    public function getUnreadAlertsForUser(User $user) {
        return array_filter(
            $this->alerts,
            function ($alert) use ($user) {
                // Filtrar alertas no leídas y no caducadas que son para el usuario específico
                return !$alert->isRead() && !$alert->isExpired() && $this->isAlertForUser($alert, $user);
            }
        );
    }

    // Verificar si la alerta es para el usuario específico
    private function isAlertForUser(Alert $alert, User $user) {
        return $alert->isForAllUsers() || $alert->getTargetUser() == $user;
    }

    // Obtener todas las alertas no caducadas asociadas al tema
    public function getUnreadAlerts() {
        return array_filter(
            $this->alerts,
            function ($alert) {
                return !$alert->isExpired();
            }
        );
    }
}

