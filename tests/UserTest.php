<?php 

use PHPUnit\Framework\TestCase;

require_once 'src/User.php';  
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

    public function testSendAlert()
    {
        $user = new User(1, "Usuario1");
        $alert = new Alert(1, "Alerta informativa", Alert::ALERT_TYPE_INFORMATIVA, new DateTime('+1 day'));

        $user->sendAlert($alert);

        $this->assertTrue($user->hasReceivedAlert($alert));
    }
    public function testIsUserSubscribedToAlert()
    {
        $user = new User(1, "Usuario1");
        $topic = new Topic(1, "Tema1");
        $alert = new Alert(1, "Alerta informativa", Alert::ALERT_TYPE_INFORMATIVA, new DateTime('+1 day'), $topic,$user);
        
        $user->subscribeToTopic($topic);
        $topic->sendAlert($alert);

        $this->assertTrue($user->isUserSubscribedToAlert($alert));
    }

    public function testIsSubscribedToTopic()
    {
        $user = new User(1, "Usuario1");
        $topic = new Topic(1, "Tema1");

        $user->subscribeToTopic($topic);

        $this->assertTrue($user->isSubscribedToTopic($topic));
    }

    public function testMarkAlertAsRead()
    {
        $user = new User(1, "Usuario1");
        $alert = new Alert(1, "Alerta informativa", Alert::ALERT_TYPE_INFORMATIVA, new DateTime('+1 day'));

        $user->sendAlert($alert);
        $user->markAlertAsRead($alert);

        $this->assertTrue(in_array($alert, $user->getReadAlerts()));
    }
    public function testGetUnreadAlertsForUser()
    {
        $user = new User(1, "Usuario1");
        $alert = new Alert(1, "Alerta informativa", Alert::ALERT_TYPE_INFORMATIVA, new DateTime('+1 day'));

        $user->sendAlert($alert);

        $this->assertTrue(in_array($alert, $user->getUnreadAlertsForUser()));
    }

    public function testGetUnreadAlertsForTopic()
    {
        $user = new User(1, "Usuario1");
        $topic = new Topic(1, "Tema1");
        $alert =new Alert(1, "Alerta informativa", Alert::ALERT_TYPE_INFORMATIVA, new DateTime('+1 day'), $topic,$user);

        $user->subscribeToTopic($topic);
        $topic->sendAlert($alert);

        $this->assertTrue(in_array($alert, $user->getUnreadAlertsForTopic($topic)));
    }

    public function testHasReceivedAlert()
    {
        $user = new User(1, "Usuario1");
        $alert = new Alert(1, "Alerta informativa", Alert::ALERT_TYPE_INFORMATIVA, new DateTime('+1 day'));

        $user->sendAlert($alert);

        $this->assertTrue($user->hasReceivedAlert($alert));
    }


}