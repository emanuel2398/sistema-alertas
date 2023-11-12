<?php

class Alert {
    private $alertId;
    private $text;
    private $alertType;
    private $expirationDateTime;
    private $topic;
    private $targetUser;
    private $read = false; // Indica si la alerta ha sido leída

    // Constantes para tipos de alerta
    const ALERT_TYPE_INFORMATIVA = 'Informativa';
    const ALERT_TYPE_URGENTE = 'Urgente';

    // Constructor para inicializar la alerta con datos proporcionados
    public function __construct($alertId, $text, $alertType, $expirationDateTime, $topic = null, $targetUser = null) {
        $this->alertId = $alertId;
        $this->text = $text;
        $this->alertType = $alertType;
        $this->expirationDateTime = $expirationDateTime;
        $this->topic = $topic;
        $this->targetUser = $targetUser;
    }

    // Obtener el tipo de la alerta
    public function getAlertType() {
        return $this->alertType;
    }

    // Obtener el ID de la alerta
    public function getAlertId() {
        return $this->alertId;
    }

    // Verificar si la alerta ha caducado
    public function isExpired() {
        return $this->expirationDateTime != null && $this->expirationDateTime <= new DateTime();
    }

    // Obtener el tema asociado a la alerta
    public function getTopic() {
        return $this->topic;
    }

    // Obtener el texto de la alerta
    public function getText() {
        return $this->text;
    }

    // Verificar si la alerta es para todos los usuarios
    public function isForAllUsers() {
        return $this->targetUser == null;
    }

    // Obtener el usuario específico para el que se ha enviado la alerta
    public function getTargetUser() {
        return $this->targetUser;
    }

    // Obtener la fecha de caducidad de la alerta
    public function getExpirationDateTime() {
        return $this->expirationDateTime;
    }

    // Verificar si la alerta ha sido leída
    public function isRead() {
        return $this->read;
    }

    // Marcar la alerta como leída
    public function markAsRead() {
        $this->read = true;
    }
}
