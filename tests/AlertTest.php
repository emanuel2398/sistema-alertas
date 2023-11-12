<?php 

use PHPUnit\Framework\TestCase;

require_once 'src/User.php';  
require_once 'src/Topic.php';
require_once 'src/Alert.php';

class AlertTest extends TestCase
{
    public function testIsExpired()
    {
        $alert = new Alert(1, 'Test', Alert::ALERT_TYPE_INFORMATIVA, new DateTime('-1 day'));

        $this->assertTrue($alert->isExpired());
    }
    public function testMarkAsRead()
    {
        $alert = new Alert(1, 'Test', Alert::ALERT_TYPE_INFORMATIVA, new DateTime('+1 day'));

        $alert->markAsRead();

        $this->assertTrue($alert->isRead());
    }
    public function testGetTargetUser()
    {
        $user = new User(1, 'Usuario1');
        $alert = new Alert(1, 'Test', Alert::ALERT_TYPE_INFORMATIVA, new DateTime('+1 day'), null, $user);

        $this->assertSame($user, $alert->getTargetUser());
    }

}