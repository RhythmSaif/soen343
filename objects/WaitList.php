<?php

include_once (__DIR__.'/../connection.php');
include_once (__DIR__.'/TimeSlot.php');
include_once (__DIR__.'/Reservation.php');

class WaitList{

    private $reservations = [];
    private $ranking = [];

    public function __construct()
    {
        $this->updateWaitListObject();
    }

    public function addReservation($reservation){
        foreach ($this->reservations as $r){
            if($r->getRoom()==$reservation->getRoom()

            ){

            }
        }
        $position='';
        $this->ranking[$reservation->getID()]=$position;
        array_push($this->reservations,$reservation);
    }

    public function updateWaitListObject(){
        $con = new Connection();
        $sql = "SELECT * FROM reservation
                    INNER JOIN timeslot ON reservation.id = timeslot.ReservationID
                    INNER JOIN waitList ON reservation.id = waitList.ReservationID";
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
                $this->ranking = array($reservation->getID()=>$row["position"]);
            }
        }
    }

    public function updateDB(){
        // Updating reservation table's data
        $con = new Connection();
        $sql='';
        foreach ($this->reservations as $reservation){
            echo 'Ranking is '.$this->ranking['$reservation->getID()'];
            $sql .= "UPDATE waitlist SET position=$this->ranking[$reservation->getID()] WHERE ReservationID=$reservation->getID();";
        }
        echo '<br>SQL is '.$sql;
        $con->setQuery($sql);
        $con->executeQuery();


        $con->close();
    }

    public function display(){
        echo "Displaying the reservation catalog<br>";
        for ($i = 0; $i < sizeof($this->reservations); $i++){
            $this->reservations[$i]->display();
            echo 'Waitlist position: '.$this->ranking[$this->reservations[$i]->getID()].'<br>';
            echo"<br>";
        }
    }


    // Removed the Reservation from the array;
    public function nextReservation(){
        $flipped_ranking = array_flip($this->ranking);
        $reservationID=$flipped_ranking['2'];
        array_splice($this->ranking, 1, 2);
        foreach ($this->reservations as $key => $reservation){
            if($reservation->getID()==$reservationID){
                unset($this->reservations[$key]);
                return $reservation;
            }
        }

    }
}

?>