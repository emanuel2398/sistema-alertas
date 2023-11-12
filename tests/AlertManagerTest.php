<?php

use PHPUnit\Framework\TestCase;
require_once 'src/User.php';  
require_once 'src/Topic.php';
require_once 'src/Alert.php';
require_once 'src/AlertManager.php';
class AlertManagerTest extends TestCase {
    public function testSendAlertToSubscribedUsers() {
        $user1 = new User(1, "Usuario1");
        $user2 = new User(2, "Usuario2");

        $topic1 = new Topic(1, "Tema1");
        $alert = new Alert(1, "Alerta informativa", Alert::ALERT_TYPE_INFORMATIVA, new DateTime('+1 day'), $topic1, null);
        // Configura AlertManager y agregar usuarios
        $alertManager = new AlertManager();
        $alertManager->addUser($user1);
        $alertManager->addUser($user2);
        // Hacer que el usuario1 esté suscrito al tema1
        $user1->subscribeToTopic($topic1);
        // Enviar la alerta a usuarios suscritos
        $alertManager->sendAlertToSubscribedUsers($alert);
        // Verificar que la alerta ha sido enviada solo a usuarios suscritos
        $this->assertTrue($user1->hasReceivedAlert($alert));
        $this->assertFalse($user2->hasReceivedAlert($alert));
    }
}
