<?php
require_once 'src/User.php';
require_once 'src/Topic.php';
require_once 'src/Alert.php';
require_once 'src/AlertManager.php';
// Crear usuarios
$user1 = new User(1, "Usuario1");
$user2 = new User(1, "Usuario2");
// Crear temas
$topic1 = new Topic(1, "Tema1");
$topic2 = new Topic(2, "Tema2");

// Pruebas de suscripción
$user1->subscribeToTopic($topic1);
$user1->subscribeToTopic($topic2);
$user2->subscribeToTopic($topic1);
$user2->subscribeToTopic($topic2);
// Verificar suscripciones
$subscribedTopics = $user1->getSubscribedTopics();
echo "Subscribed Topics for User1: " . implode(', ', array_map(function ($topic) {
    return $topic->getName();
}, $subscribedTopics)) . PHP_EOL;

// Crear alertas
$alert1 = new Alert(1, "Alerta informativa", Alert::ALERT_TYPE_INFORMATIVA, new DateTime('+1 day'), $topic1,$user1);
$alert2 = new Alert(2, "Alerta urgente", Alert::ALERT_TYPE_URGENTE, new DateTime('+1 day'), $topic2,null);
//$alert3 = new Alert(3, "Alerta específica para Usuario1", Alert::ALERT_TYPE_INFORMATIVA, new DateTime('+1 day'),null, $user1);
$alertForAllUsers = new Alert(4, "Alerta para todos los usuarios", Alert::ALERT_TYPE_INFORMATIVA, new DateTime('+1 day'),$topic2);
// Pruebas de alertas
// Crear AlertManager e añadir usuarios
$alertManager = new AlertManager();
$alertManager->addUser($user1);
$alertManager->addUser($user2);
// Enviar alertas a usuarios suscritos
$alertManager->sendAlertToSubscribedUsers($alert1);
$alertManager->sendAlertToSubscribedUsers($alert2);
//$alertManager->sendAlertToSubscribedUsers($alert3);
//$user1->sendAlert($alertForAllUsers);


$users = [$user1, $user2]; // Agrega todos tus usuarios aquí
$user1->markAlertAsRead($alert1);
$user1->markAlertAsRead($alert2);
foreach ($users as $user) {
    // Obtener alertas no leídas
    $unreadAlertsForUser = $user->getUnreadAlertsForUser();
    echo "Unread alerts for {$user->getName()}: " . implode(', ', array_map(function ($alert) {
        return $alert->getAlertType() . ': ' . $alert->getExpirationDateTime()->format('Y-m-d H:i:s') . $alert->getText();
    }, $unreadAlertsForUser)) . PHP_EOL;

    // Marcar alertas como leídas
     
    // Obtener alertas leídas
    $readAlerts = $user->getReadAlerts();
    echo "Read alerts for {$user->getName()}: " . implode(', ', array_map(function ($alert) {
        return $alert->getAlertType() . ': ' . $alert->getExpirationDateTime()->format('Y-m-d H:i:s') . $alert->getText();
    }, $readAlerts)) . PHP_EOL;
}

?>
