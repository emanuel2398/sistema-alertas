<?php

// Alert.php
class Alert {
    private $alertId;
    private $text;
    private $alertType;
    private $expirationDateTime;
    private $topic;
    private $targetUser;
    private $read = false; // Nuevo atributo para indicar si la alerta ha sido leída

    const ALERT_TYPE_INFORMATIVA = 'Informativa';
    const ALERT_TYPE_URGENTE = 'Urgente';

    public function __construct($alertId, $text, $alertType, $expirationDateTime, $topic = null, $targetUser = null) {
        $this->alertId = $alertId;
        $this->text = $text;
        $this->alertType = $alertType;
        $this->expirationDateTime = $expirationDateTime;
        $this->topic = $topic;
        $this->targetUser = $targetUser;
    }

    public function getAlertType() {
        return $this->alertType;
    }

    public function getAlertId() {
        return $this->alertId;
    }

    public function isExpired() {
        return $this->expirationDateTime != null && $this->expirationDateTime <= new DateTime();
    }

    public function getTopic() {
        return $this->topic;
    }

    public function getText() {
        return $this->text;
    }

    public function isForAllUsers() {
        return $this->targetUser == null;
    }

    public function getTargetUser() {
        return $this->targetUser;
    }

    public function getExpirationDateTime() {
        return $this->expirationDateTime;
    }

    public function isRead() {
        return $this->read;
    }

    public function markAsRead() {
        $this->read = true;
    }
}
