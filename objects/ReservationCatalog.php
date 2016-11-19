<?php

    include (__DIR__.'/Reservation.php');
    include (__DIR__.'/TimeSlot.php');

    class ReservationCatalog{
        private $reservations = [];
        protected $valid;

        public function __construct()
        {
        }

        public function makeNewReservation($roomNumber, $timeSlot, $user, $description){
            $reservation = new Reservation($roomNumber, $timeSlot, $user, $description);
            array_push($this->reservations, $reservation);
        }

        public function modifyReservation($reservationId, $newDescription){
            var_dump($this->reservations);
            foreach($this->reservations as $reservation){
                echo $reservation->getID() . 'nice';
                echo $reservationId;
                if($reservation->getID() === $reservationId){
                    $reservation->modifyReservation($newDescription);
                }
                break;
            }
        }

        public function dropReservation($reservationId){
            $con = new Connection();
            $sql = "DELETE FROM reservation WHERE id='$reservationId'";
            $con->setQuery($sql);
            $this->valid = $con->executeQuery();
        }

        public function display(){
            echo "Displaying the reservation catalog<br>";
            for ($i = 0; $i < sizeof($this->reservations); $i++){
                $this->reservations[$i]->display();
                echo"<br>";
            }
        }

        public function updateDB(){
            for ($i = 0; $i < sizeof($this->reservations); $i++){
                $this->reservations[$i]->updateDB();
            }
        }

        public function updateCatalogByUser($user){
            $con = new Connection();
            $sql = "SELECT * FROM reservation
                    INNER JOIN timeslot ON reservation.id = timeslot.ReservationID
                    WHERE loginID='$user'";
            $con->setQuery($sql);
            $con->executeQuery();
            $result = $con->getResult();

            if ($result->num_rows > 0) {
                // output data
                while($row = $result->fetch_assoc()) {
                    $timeSlot = new TimeSlot($row["StartTime"],$row["EndTime"],$row["date"]);
                    $reservation = new Reservation($row["roomID"],$timeSlot,$user,$row["description"]);
                    $reservation->setID($row["id"]);
                    array_push($this->reservations, $reservation);
                }
            }
        }

        public function updateCatalogObject(){
            $con = new Connection();
            $sql = "SELECT * FROM reservation
                    INNER JOIN timeslot ON reservation.id = timeslot.ReservationID";
            $con->setQuery($sql);
            $con->executeQuery();
            $result = $con->getResult();

            if ($result->num_rows > 0) {
                // output data
                while($row = $result->fetch_assoc()) {
                    $timeSlot = new TimeSlot($row["StartTime"],$row["EndTime"],$row["date"]);
                    $reservation = new Reservation($row["roomID"],$timeSlot,$row["loginID"],$row["description"]);
                    $reservation->setID($row["id"]);
                    array_push($this->reservations, $reservation);
                }
            }
        }

        public function updateCatalogByDate ($date){
            $con = new Connection();
            $sql = "SELECT * FROM reservation
                    INNER JOIN timeslot ON reservation.id = timeslot.ReservationID
                    WHERE date='$date'";
            $con->setQuery($sql);
            $con->executeQuery();
            $result = $con->getResult();

            if ($result->num_rows > 0) {
                // output data
                while($row = $result->fetch_assoc()) {
                    $timeSlot = new TimeSlot($row["StartTime"],$row["EndTime"],$row["date"]);
                    $reservation = new Reservation($row["roomID"],$timeSlot,$row["loginID"],$row["description"]);
                    $reservation->setID($row["id"]);
                    array_push($this->reservations, $reservation);
                }
            }
        }

        public function getCalendar(){
            $reservations = array();
            for ($i = 0; $i < sizeof($this->reservations); $i++){
                $reservation = array("room"=>$this->reservations[$i]->getRoom(),
                                        "start_time"=>$this->reservations[$i]->getTimeSlot()->getStart(),
                                        "end_time"=>$this->reservations[$i]->getTimeSlot()->getEnd(),
                                        "username"=>$this->reservations[$i]->getUser(),
                                        "description"=>$this->reservations[$i]->getDescription(),
                                        "date"=>$this->reservations[$i]->getTimeSlot()->getDate(),
                                        "id"=>$this->reservations[$i]->getID());
                array_push($reservations, $reservation);
            }
            return $reservations;
        }

        public function querySuccess(){
            return $this->valid;
        }

    }

?>