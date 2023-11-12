<?php
require_once 'src/User.php';
require_once 'src/Topic.php';
require_once 'src/Alert.php';
require_once 'src/AlertManager.php';

    // Crear usuarios
    $user1 = new User(1, "Usuario1");
    $user2 = new User(2, "Usuario2");
    $users = [$user1, $user2]; // Agrega todos tus usuarios 
    // Crear temas
    $topic1 = new Topic(1, "Tema1");
    $topic2 = new Topic(2, "Tema2");
    $topic3 = new Topic(2, "Tema3");
    // Pruebas de suscripción
    $user1->subscribeToTopic($topic1);
    $user1->subscribeToTopic($topic2);
    $user2->subscribeToTopic($topic1);
    $user2->subscribeToTopic($topic2);

    // Iterar sobre cada usuario
    foreach ($users as $user) {
        // Obtener temas suscritos para el usuario actual
        $subscribedTopics = $user->getSubscribedTopics();
        // Imprimir los temas
        echo "Temas subscritos de {$user->getuserId()} {$user->getName()}: ";
        foreach ($subscribedTopics as $topic) {
            echo $topic->getName() . ', ';
        }
        echo PHP_EOL;
    }

    // Crear alertas
    $alert1 = new Alert(1, "Alerta informativa", Alert::ALERT_TYPE_INFORMATIVA, new DateTime('+1 day'), $topic1,$user1);// envia a usaurio 1
    $alert2 = new Alert(2, "Alerta urgente", Alert::ALERT_TYPE_URGENTE, new DateTime('+1 day'), $topic2,null);// envia a todos los usuarios 
    $alert3 = new Alert(3, "Alerta Urgente Usaurio 1", Alert::ALERT_TYPE_URGENTE, new DateTime('+2 days'), $topic1, $user1);// envia a usaurio 1
    $alert4 = new Alert(4, "Otra alerta urgente", Alert::ALERT_TYPE_URGENTE, new DateTime('+2 days'), $topic2, null);// envia a todos los usuarios
    $alert5 = new Alert(5, "Alerta específica para Usuario 1", Alert::ALERT_TYPE_INFORMATIVA, new DateTime('+1 day'), $topic2, $user1);// envia a usaurio 1


    // Crear AlertManager añadir usuarios
    $alertManager = new AlertManager();
    $alertManager->addUser($user1);
    $alertManager->addUser($user2);
    // Enviar alertas a usuarios suscritos
    $alertManager->sendAlertToSubscribedUsers($alert1);
    $alertManager->sendAlertToSubscribedUsers($alert2);
    $alertManager->sendAlertToSubscribedUsers($alert3);
    $alertManager->sendAlertToSubscribedUsers($alert4);
    $alertManager->sendAlertToSubscribedUsers($alert5);


    //marcar alertas como leidas
    $user1->markAlertAsRead($alert2);
    $user1->markAlertAsRead($alert3);

    //mostrar resultados
    foreach ($users as $user) {
        // Obtener alertas no leídas
        $unreadAlertsForUser = $user->getUnreadAlertsForUser();
        echo "Alertas sin leer para {$user->getName()}: " . implode(', ', array_map(function ($alert) {
            return $alert->getAlertType() . ': ' . $alert->getExpirationDateTime()->format('Y-m-d H:i:s') . ' '. $alert->getText();
        }, $unreadAlertsForUser)) . PHP_EOL;

        // Marcar alertas como leídas
        
        // Obtener alertas leídas
        $readAlerts = $user->getReadAlerts();
        echo "Alertas leidas para {$user->getName()}: " . implode(', ', array_map(function ($alert) {
            return $alert->getAlertType() . ': ' . $alert->getExpirationDateTime()->format('Y-m-d H:i:s') . ' '.$alert->getText();
        }, $readAlerts)) . PHP_EOL;
    }

?>
