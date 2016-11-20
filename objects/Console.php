<?php

include_once 'ReservationCatalog.php';
include_once 'ReservationSession.php';
include_once 'RoomCatalog.php';
include_once 'WaitList.php';

    Class Console
    {
        private $session;
        private $catalog;
        private $roomCatalog;
        private $waitList;

        public function __construct(ReservationCatalog $catalog, ReservationSession $session, RoomCatalog $roomCatalog,
                                    WaitList $waitList)
        {
            $this->catalog = $catalog;
            $this->session = $session;
            $this->roomCatalog = $roomCatalog;
            $this->waitList = $waitList;
        }

        public function makeNewRoomEntry(Student $student, ReservationCatalog $catalog)
        {
            $this->catalog = $catalog;
            $this->session = new ReservationSession($this->catalog);
            $this->session->initiateRoomEntrySession($student, $catalog);
        }

        public function addReservation($roomNumber, $time, $user, $description){
//            $room = $this->roomCatalog->getRoom($roomNumber);
            return $this->session->makeNewReservation($roomNumber, $time, $user, $description);
        }

        public function endRoomEntry(){
            $this->session->becomeComplete();
        }

        public function dropReservation($reservationId){
            $this->session->dropReservation($reservationId);
        }

        public function modifyReservation($reservationId, $newDescription){
            $this->session->modifyReservation($reservationId, $newDescription);
        }

        public function getAllRoomNumber(){
            return $this->roomCatalog->getAllRoomNumbers();
        }

        public function addReservationToWaitList($reservation){
            $this->waitList->addReservation($reservation);
        }

        public function updateWaitList(){
            $this->waitList->updateDB();
        }

    }


?>