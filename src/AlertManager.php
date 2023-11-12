<?php
// AlertManager.php
class AlertManager {
    private $users;

    // Constructor para inicializar la lista de usuarios
    public function __construct() {
        $this->users = [];
    }

    // Agregar un usuario a la lista
    public function addUser(User $user) {
        $this->users[] = $user;
    }

    // Enviar una alerta a los usuarios suscritos
    public function sendAlertToSubscribedUsers(Alert $alert) {
        foreach ($this->users as $user) {
            // Verificar si la alerta es para todos los usuarios o si el usuario está suscrito a la alerta específica
            if (($alert->getTargetUser() == null && $user->isSubscribedToTopic($alert->getTopic())) || ($user->isUserSubscribedToAlert($alert) && $alert->getTargetUser() == $user)) {
                // Enviar la alerta al usuario
                $user->sendAlert($alert);
            }
        }
    }
}
