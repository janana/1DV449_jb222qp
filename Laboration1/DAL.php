<?php

class DAL {
	private $connection;

	public function __construct() {
		$this->connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		if (mysqli_connect_errno()) {
			echo "Connection failed: ". mysqli_connect_error();

			exit();
		}
	}

	public function addProducer(Producer $producer) {
        $name = $producer->name;
        $homepage = $producer->homepage;
        $location = $producer->location;
        $id = $producer->id;
        $status = $producer->status;

		$sql = "INSERT INTO Producers(Name, Homepage, Location, ProducerID, Status) 
				VALUES (?, ?, ?, ?, ?)";
		$prep = $this->connection->prepare($sql);
        if ($prep == false) {
            throw new Exception("prepare of [$sql] failed " . 
                                $this->connection->error);
        }
		
        $exec = $prep->bind_param("sssis", $name, $homepage, $location, $id, $status);
		if ($exec == false) {
			throw new Exception("Bind param of [$sql] failed " . $prep->error);
		}
        $exec = $prep->execute();
        if ($exec == false) {
        	throw new Exception("execute of [$sql] failed " . $prep->error);
        }
	}


	public function getProducers() {
		$sql = "SELECT Name, Homepage, Location, ProducerID, Status FROM Producers";
        $prep = $this->connection->prepare($sql);
        if ($prep == false) {
            throw new Exception("prepare of [$sql] failed " . 
                                $this->connection->error);
        }
        $exec = $prep->execute();
        if ($exec == false) {
        	throw new Exception("execute of [$sql] failed " . $prep->error);
        }
        $exec = $prep->bind_result($name, $homepage, $location, $id, $status);
        if ($exec == false) {
        	throw new Exception("execute of [$sql] failed " . $prep->error);
        }
        $producerList = array();
	    while ($prep->fetch()) {
	        $producerList[] = new Producer($name, $homepage, $location, $id, $status);
	    }
        return $producerList;
	}
}