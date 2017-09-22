<?php

namespace database{
use \PDO;
use \PDOException;
class connection{

		public $queries;
	    private $connection;
		private $database;
		public $db_host;
		
		# Sets Database
		public function __construct($db = "probate"){
			// save the database name to the property
			$this->database = $db;
			try {
				// instantiate the PDO using the DB constants, the provided database name, and utf8
                $this->connection = new PDO("mysql:host=".SERVER.";dbname=".$db.";charset=utf8",
                    USER, PASSWORD, array());
				// raise exceptions for MySQL errors
				$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				// emulate prepared statements in PHP (allows query buffering)
				$this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
				// buffer MySQL queries to support InnoDB transactions
				$this->connection->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
				return true;
			}
			catch (PDOException $exception) {
				error_log('Connection failed: ' . $exception->getMessage()."\n".$exception->getTraceAsString());
				return false;
			}
		}

		#Prepares a SQL query and executes it, returning the number of rows affected or 'false'. 
		#This function should be used for UPDATE and DELETE queries
		public function put($statement, $vars = array()) 
		{
			// run the query
			$stmt = $this->execute($statement,$vars);
			$q = new \stdClass();
			$q->statement = $statement;
			$q->vars = $vars;
            $this->queries[] = $q;
            $index = count($this->queries) - 1;
			if ($stmt){ // if the query succeeded
				// return the affected row count
				$this->queries[$index]->successStatus = "success";
				return $stmt->rowCount();
			}
			else
            {
            	$this->queries[$index]->successStatus = "failed";
                return false;
            }
		}


		#Runs a SQL query and returns the resulting rows or false
		public function select($statement, $vars = array()) 
		{
			// run the query
			$stmt = $this->execute($statement,$vars);
			$q = new \stdClass();
			$q->statement = $statement;
			$q->vars = $vars;
            $this->queries[] = $q;
            $index = count($this->queries) - 1;
			if ($stmt) // if execution succeeded
            {
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				// fetch and return the rows
                $this->queries[$index]->results = $results;
                $this->queries[$index]->successStatus = "success";
				return $results;
            }
			else
            {
            	$this->queries[$index]->successStatus = "failed";
                var_dump($this->queries[$index]);
                return false;
            }
		}
		

		# Runs a SQL query and returns the last inserted primary key or false.
		public function insert($sql, $vars = array()) 
		{
			// run the query
			$stmt = $this->execute($sql,$vars);
			$q = new \stdClass();
			$q->statement = $statement;
			$q->vars = $vars;
            $this->queries[] = $q;
            $index = count($this->queries) - 1;
			if ($stmt){ // if query succeeded
				// return the last inserted primary key value
                $this->queries[$index]->successStatus = "success";
				return $this->connection->lastInsertId();
			}
			else{
            	$this->queries[$index]->successStatus = "failed";
                return false;
            }
		}


		#Executes a statement.
		private function execute($sql,$vars = array())
		{
			$error = '';
			try
			{
				// prepare the query
				$stmt = $this->connection->prepare($sql);
				// execute with params
				if ($stmt->execute($vars)) // if query succeeded
					// return the PDOStatement object
					return $stmt;
			}
			catch (PDOException $e) { $error = $e->getMessage()."\n".$e->getTraceAsString(); }
			error_log("Query failed: ".$error);
			error_log("QUERY: ".$sql);
			return false;
		}
	}
}

?>