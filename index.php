<?php
header('Content-Type: text/html; charset=UTF-8');

class Person {
    protected $id;
    protected $name;
    protected $surname;
    protected $sex;
    protected $birthdate;

    /**
     * $person
     * 
     * id : integer
     * name : string
     * surname : string
     * sex : string (M/F)
     * birthdate : string ( "dd.mm.yyyy" )
     */

    public function __construct($person)
    {
        $this->id = $person[0];
        $this->name = $person[1];
        $this->surname = $person[2];
        $this->sex = $person[3];
        $this->birthdate = $person[4];
    }

    public function getAgeInDays() {
        $birthdate = new DateTime($this->birthdate);
        $now = new DateTime();
        $diff = $birthdate->diff($now);
        return intval(($diff->days)/365) + 1;
    }

}

$personme = [ 'row-1', 'Khamidrak', 'Roman', 'M', '11.07.1998'];

$person = new Person($personme);
// var_dump($person->getAgeInDays());

class Mankind {
    protected $persons = [];
    protected $Mnums ;
    protected $Fnums ;
    public function __construct($filename) {
        // Load people from the file
        $handle = fopen($filename, 'r');
        if ($handle !== false) {
            $male = 0;
            $female = 0;
            while (($data = fgetcsv($handle, 10000, ',')) !== false) {
                $personArray = explode(';',$data[0]);
                if ( count($personArray) != 5 ) continue;
                $this->persons[$personArray[0]] = new Person($personArray);
                if ( $personArray[3] == 'M') $male ++;
                else $female ++;
            }
            $this->Mnums = $male;
            $this->Fnums = $female;
            fclose($handle);
        } else {
            throw new Exception('Could not open file');
        }
    }

    public function getPersonFromID($id) {
        return $this->persons[$id];
    }

    public function getMalePercentage  () {
        return floatval($this->Mnums / ($this->Fnums+$this->Mnums));
    }

}

$filename = 'people.csv';
$persons = new Mankind('people.csv');

$age = $persons->getPersonFromID(456)->getAgeInDays();
$percent = $persons->getMalePercentage();
