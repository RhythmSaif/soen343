<?php

    Class ReservationSession{
        private $catalog;
        private $isComplete;

        public function __construct(ReservationCatalog $catalog)
        {
            $this->catalog= $catalog;
        }

        public function initiateRoomEntrySession(){
            $this->isComplete = false;
            // Testing: TBD / To be deleted
            echo "Initializing Room entry session";
        }

        public function makeNewRoom($roomNumber, $time, $user){
            if($this->isComplete){
                echo "Room entry session wasn't initialized...";
            } else{
                $this->catalog->makeNewRoom($roomNumber, $time, $user);
                echo "Room made";
            }
        }

        public function becomeComplete(){
            $this->isComplete=true;
            echo "Session completed";
        }
}

?>