<?php

/**
 * Description of Player
 *
 * @author Chris
 */
class Player {

    protected $hand;
    protected $total;
    protected $name;

    public function __construct($name) {
        $this->name = $name;
        $this->hand = [];
    }

    /////////////////
    //  ACCESSORS  //
    /////////////////

    public function getHand() {
        return $this->hand;
    }

    public function getTotal() {
        return $this->total;
    }

    public function getDealer() {
        return $this->dealer;
    }

    public function getName() {
        return $this->name;
    }

    public function printDetails() {
        echo '<br>';
        if($this->name!='Dealer') {
            echo 'Player ';
        }
        echo $this->name, ':: ';
        foreach ($this->hand as &$card) {
            echo $card->getDetails(), ', ';
        }
        echo ' Total = ', $this->total;

        //boolean to hold true if ace in hand
        $ace = NULL;
        //check for ace
        foreach ($this->hand as &$card) {
            if (!is_null($card->getAceLow())) {
                $ace = $card->getAceLow();
            }
        }
        //if ace then print detils of both possible scores
        if (!is_null($ace)) {
            switch ($ace) {
                case true:
                    if ($this->total + 10 < 22) {
                        echo ' or ', ($this->total + 10);
                    }
                    break;

                case false:
                    if ($this->total - 10 > 0) {
                        echo ' or ', ($this->total - 10);
                    }
                    break;
            }
        }
    }

    public function updateTotal() {
        $ace = 0;
        // check for aces
        foreach ($this->hand as &$card) {
            if ($card->getAceLow() !== NULL) {
                $ace++;
            }
        }

        // total as stands at the moment
        $this->total = $this->sumCardsInHand();


        // If the hand contains aces
        if ($ace > 0) {
            $total1 = $this->sumCardsInHand(); //curent hand total with ace as is
            foreach ($this->hand as &$card) {
                if ($card->getAceLow() !== null) { //card is an ace
                    switch ($card->getAceLow()) {
                        case true:
                            //if score is over 21 and the ace is low player is bust
                            if ($this->total < 21) {
                                // if making ace high will not bust player
                                if ($this->total + 10 <= 21) {
                                    // if high ace will be more than 16 but les than 21 switch it
                                    if ($this->total + 10 >= 16) {
                                        $card->setAceValue();
                                        $this->total = $this->sumCardsInHand();
                                    }
                                }
                            }
                            break;
                        case false:
                            if ($this->total > 21) {
                                $card->setAceValue();
                                $this->total = $this->sumCardsInHand();
                            }
                            break;
                    }
                }
            }
        }
    }

    public function sumCardsInHand() {
        $total = 0;
        foreach ($this->hand as &$card) {
            $total+=$card->getNumericValue();
        }
        return $total;
    }

    public function action() {
        if ($this->total == 11) {
            return 'doubleDown';
        }
        if ($this->total < 16) {
            return 'hit';
        }
        return 'stick';
    }

    ////////////////
    //  MUTATORS  //
    ////////////////

    function addCardToHand($card) {
        array_push($this->hand, $card);
    }
}