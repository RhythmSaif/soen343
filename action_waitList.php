<?php

    include_once ('objects/WaitList.php');
    include_once ('objects/Console.php');

    session_start();

    $catalog = new ReservationCatalog();
    $session = new ReservationSession($catalog);
    $roomCatalog = new RoomCatalog();
    $wait = new WaitList();
	$wait->updateWaitListObject();
    $console = new Console($session, $roomCatalog, $wait);

    $console->addReservationToWaitList($_SESSION['reservation']);
    $console->updateWaitList();

    $roomCatalog->unlockRoom($_SESSION['login_user']);

    header('Location: ' . 'booking.php?valid=true&action=add');
?>