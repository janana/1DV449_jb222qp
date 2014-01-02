<?php

class UserDAL extends DAL {
	/**
	 * @param string name
	 * @param int id
	 */
	public function addUser($name, $id) {
		$sql = "INSERT INTO FbUsers VALUES (?, ?)";
		
		$prep = $this->connection->prepare($sql);
        if ($prep == false) {
            throw new Exception("prepare of [$sql] failed " . $this->connection->error);
        }
        $exec = $prep->bind_param("is", $id, $name);
		if ($exec == false) {
			throw new Exception("Bind param of [$sql] failed " . $prep->error);
		}
        $exec = $prep->execute();
        if ($exec == false) {
        	throw new Exception("execute of [$sql] failed " . $prep->error);
        }
		
	}
	
	/**
	 * @return Array of users
	 */
	public function getUsers() {
		$sql = "SELECT ID, Name FROM FbUsers";
        $prep = $this->connection->prepare($sql);
        if ($prep == false) {
            throw new Exception("prepare of [$sql] failed " . $this->connection->error);
        }
        
        $exec = $prep->execute();
        if ($exec == false) {
        	throw new Exception("execute of [$sql] failed " . $prep->error);
        }

        $exec = $prep->bind_result($id, $name);
        if ($exec == false) {
        	throw new Exception("execute of [$sql] failed " . $prep->error);
        }

        $return = array();

	    while ($prep->fetch()) {
	        $return[] = array("id" => $id,
							"name" => $name);
	 	}
        return $return;
	}
}