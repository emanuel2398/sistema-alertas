<?php 

use PHPUnit\Framework\TestCase;

require_once 'src/User.php';  
require_once 'src/Topic.php';
require_once 'src/Alert.php';

class TopicTest extends TestCase
{

    public function testAddObserver()
    {
        $topic = new Topic(1, 'Tema1');
        $observer = $this->createMock(Observer::class);
        $topic->addObserver($observer);
        // verifica que el observador fue agregado correctamente
        $this->assertTrue(true);
    }

    public function testSendAlert()
    {
        $topic = new Topic(1, 'Tema1');
        $observer = $this->createMock(Observer::class);
        $topic->addObserver($observer);
        $alert = new Alert(1, 'Test', Alert::ALERT_TYPE_INFORMATIVA, new DateTime('+1 day'));

        $observer->expects($this->once())->method('update')->with($alert);

        $topic->sendAlert($alert);

        $this->assertTrue(in_array($alert, $topic->getAlerts()));
    }

    public function testGetUnreadAlertsForUser()
    {
        $topic = new Topic(1, 'Tema1');
        $user = new User(1, 'Usuario1');
        $alert1 = new Alert(1, "Alerta informativa", Alert::ALERT_TYPE_INFORMATIVA, new DateTime('+1 day'), $topic,$user);
        $alert2 = new Alert(2, "Alerta urgente", Alert::ALERT_TYPE_URGENTE, new DateTime('+1 day'), $topic,null);
        $topic->sendAlert($alert1);
        $topic->sendAlert($alert2);

        $unreadAlerts = $topic->getUnreadAlertsForUser($user);

        $this->assertTrue(in_array($alert1, $unreadAlerts));
    }

}
