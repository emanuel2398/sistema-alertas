<?php 

use PHPUnit\Framework\TestCase;

require_once 'src/User.php';  // Asegúrate de ajustar la ruta según tu estructura de archivos
require_once 'src/Topic.php';
require_once 'src/Alert.php';

class UserTest extends TestCase
{
    public function testSubscribeToTopic()
    {
        $user = new User(1, "Usuario1");
        $topic = new Topic(1, "Tema1");

        $user->subscribeToTopic($topic);

        $this->assertTrue(in_array($topic, $user->getSubscribedTopics()));
    }

    // Agrega más métodos de prueba para otros métodos de la clase User
    // ...

    // Por ejemplo:
    public function testSendAlert()
    {
        $user = new User(1, "Usuario1");
        $alert = new Alert(1, "Alerta informativa", Alert::ALERT_TYPE_INFORMATIVA, new DateTime('+1 day'));

        $user->sendAlert($alert);

        $this->assertTrue(in_array($alert, $user->getReadAlerts()));
    }

}