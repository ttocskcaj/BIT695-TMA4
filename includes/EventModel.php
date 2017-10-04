<?php

class EventModel {

	private $id;
	private $name;
	private $location;
	private $dateTime;
	private $carbon;

	/**
	 * EventModel constructor.
	 * Creates a new EventModel with the defined attributes.
	 *
	 * @param name String - The name of the event.
	 * @param location String - The location of the event.
	 * @param $dateTime String - A string representation of the dateTime.
	 * @param $id int - The events ID.
	 */
	function __construct( $name, $location, $dateTime, $id = null ) {
		$this->name     = $name;
		$this->location = $location;
		$this->dateTime = $dateTime;
		$this->carbon   = new Carbon\Carbon( $dateTime );
		if ( $id != null ) {
			$this->id = $id;
		}
	}

	/**
	 * Saves (inserts) the event into the database.
	 *
	 * @throws PDOException
	 * @return bool True if the query is successful. False if it fails.
	 */
	public function save() {
		// Get DPO object.
		$pdo = Database::getPdo();

		// Create a prepared statement for the query to insert the event.
		$statement = $pdo->prepare( "INSERT INTO events (name, location, datetime) VALUES (:name, :location, :dateTime)" );
		// Bind the named parameters to the statement.
		$statement->bindParam( ":name", $this->name );
		$statement->bindParam( ":location", $this->location );
		$statement->bindParam( ":dateTime", $this->carbon->toDateTimeString() );

		// Execute the statement to insert the event.
		return $statement->execute();
	}

	/**
	 * Deletes the user from the database.
	 *
	 * @throws PDOException
	 * @return bool True if the query is successful. False if it fails.
	 */
	public static function delete( $id ) {
		// Get DPO object.
		$pdo = Database::getPdo();

		// Create a prepared statement for the query to insert the event.
		$statement = $pdo->prepare( "DELETE FROM events WHERE id=?" );

		// Execute the statement to insert the event.
		return $statement->execute( [ $id ] );
	}

	/**
	 * Updates an existing row in the database.
	 *
	 * @throws PDOException
	 * @return bool True if the query is successful. False if it fails.
	 */
	public function update() {
		// Get PDO object.
		$pdo = Database::getPdo();

		// Create a prepared statement for the query to insert the event.
		$statement = $pdo->prepare( "UPDATE events SET name = :name, location = :location, dateTime = :dateTime WHERE id = :id" );
		// Bind the named parameters to the statement.
		$statement->bindParam( ":name", $this->name );
		$statement->bindParam( ":location", $this->location );
		$statement->bindParam( ":dateTime", $this->carbon->toDateTimeString() );
		$statement->bindParam( ":id", $this->id );

		// Execute the statement to insert the event.
		return $statement->execute();
	}

	/**
	 * Finds a event in the database by matching their ID.
	 *
	 * @param $id integer - The Events ID number
	 */
	public static function findByID( $id ) {
		$pdo = Database::getPdo();

		$statement = $pdo->prepare( "SELECT id, name, location, dateTime FROM events  WHERE id=?" );
		$statement->execute( [ $id ] );
		if ( $statement->rowCount() > 0 ) {
			$data = $statement->fetch();

			return new EventModel( $data['name'], $data['location'], $data['dateTime'], $data['id'] );
		}
		else {
			throw new Exception( "Event not found", ROW_NOT_FOUND );
		}
	}

	public static function getAll() {
		$pdo = Database::getPdo();

		$statement = $pdo->prepare( "SELECT id, name, location, dateTime FROM events" );
		$statement->execute();
		if ( $statement->rowCount() > 0 ) {
			$results = $statement->fetchAll();
			$events = [];
			foreach ( $results as $event ) {
				$events[] = new EventModel( $event['name'], $event['location'], $event['dateTime'], $event['id'] );
			}

			return $events;
		}
		else {
			throw new Exception( "No Events Found", ROW_NOT_FOUND );
		}
	}

	/**
	 * @return int|null
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int|null $id
	 */
	public function setId( $id ) {
		$this->id = $id;
	}

	/**
	 * @return String
	 */
	public function getName(): String {
		return $this->name;
	}

	/**
	 * @param String $first_name
	 */
	public function setName( String $name ) {
		$this->name = $name;
	}

	/**
	 * @return String
	 */
	public function getLocation(): String {
		return $this->location;
	}

	/**
	 * @param String $family_name
	 */
	public function setLocation( String $location ) {
		$this->location = $location;
	}

	public function getDateTime() {
		return $this->dateTime;
	}
	public function getCarbon() {
		return $this->carbon;
	}

	public function setDateTime(String $dateTime){
		$this->dateTime = $dateTime;
		$this->carbon = new Carbon\Carbon($dateTime);
	}
}