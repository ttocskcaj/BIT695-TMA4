<?php

class EventModel {

	private $id;
	private $name;
	private $location;
	private $dateTime;
	private $carbon;
	private $results;
	private $boardgame;

	/**
	 * EventModel constructor.
	 * Creates a new EventModel with the defined attributes.
	 *
	 * @param name String - The name of the event.
	 * @param location String - The location of the event.
	 * @param $dateTime String - A string representation of the dateTime.
	 * @param $id int - The events ID.
	 */
	function __construct( string $name, string $location, string $dateTime, BoardgameModel $boardgame, int $id = null ) {
		$this->name      = $name;
		$this->location  = $location;
		$this->dateTime  = $dateTime;
		$this->carbon    = new Carbon\Carbon( $dateTime );
		$this->boardgame = $boardgame;
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
		$statement = $pdo->prepare( "INSERT INTO events (name, location, datetime, boardgame_id) VALUES (:name, :location, :dateTime, :boardgame_id)" );

		// Bind the named parameters to the statement.
		$statement->bindValue( ":name", $this->name );
		$statement->bindValue( ":location", $this->location );
		$statement->bindValue( ":dateTime", $this->carbon->toDateTimeString() );
		$statement->bindValue( ":boardgame_id", $this->boardgame->getId() );

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
		$statement = $pdo->prepare( "UPDATE events SET name = :name, location = :location, dateTime = :dateTime, boardgame_id = :boardgame_id WHERE id = :id" );
		// Bind the named parameters to the statement.
		$statement->bindParam( ":name", $this->name );
		$statement->bindParam( ":location", $this->location );
		$statement->bindParam( ":dateTime", $this->carbon->toDateTimeString() );
		$statement->bindParam( ":boardgame_id", $this->boardgame->getId() );
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

		$statement = $pdo->prepare( "SELECT events.id, events.name, events.location, events.dateTime, boardgames.id AS boardgame_id, boardgames.name AS boardgame_name, boardgames.description AS boardgame_description FROM events  JOIN boardgames ON boardgames.id = events.boardgame_id WHERE events.id=?" );
		$statement->execute( [ $id ] );
		if ( $statement->rowCount() > 0 ) {
			$row      = $statement->fetch();

			$boardgame = new BoardgameModel( $row['boardgame_name'], $row['boardgame_description'], $row['boardgame_id'] );
			return new EventModel( $row['name'], $row['location'], $row['dateTime'], $boardgame, $row['id'] );
		}
		else {
			throw new Exception( "Event not found", ROW_NOT_FOUND );
		}
	}

	public static function getAll() {
		$pdo = Database::getPdo();

		$statement = $pdo->prepare( "SELECT events.id, events.name, events.location, events.dateTime, boardgames.id AS boardgame_id, boardgames.name AS boardgame_name, boardgames.description AS boardgame_description FROM events  JOIN boardgames ON boardgames.id = events.boardgame_id" );
		$statement->execute();
		if ( $statement->rowCount() > 0 ) {
			$rows = $statement->fetchAll();
			$events  = [];
			foreach ( $rows as $row ) {
				$boardgame = new BoardgameModel( $row['boardgame_name'], $row['boardgame_description'], $row['boardgame_id'] );
				$events[] = new EventModel( $row['name'], $row['location'], $row['dateTime'], $boardgame, $row['id'] );
			}

			return $events;
		}
		else {
			throw new Exception( "No Events Found", ROW_NOT_FOUND );
		}
	}

	public static function getPast() {
		$pdo = Database::getPdo();

		$statement = $pdo->prepare( "SELECT events.id, events.name, events.location, events.dateTime, boardgames.id AS boardgame_id, boardgames.name AS boardgame_name, boardgames.description AS boardgame_description FROM events  JOIN boardgames ON boardgames.id = events.boardgame_id WHERE events.dateTime <= now()" );
		$statement->execute();
		if ( $statement->rowCount() > 0 ) {
			$rows = $statement->fetchAll();
			$events  = [];
			foreach ( $rows as $row ) {
				$boardgame = new BoardgameModel( $row['boardgame_name'], $row['boardgame_description'], $row['boardgame_id'] );
				$events[] = new EventModel( $row['name'], $row['location'], $row['dateTime'], $boardgame, $row['id'] );
			}

			return $events;
		}
		else {
			throw new Exception( "No Events Found", ROW_NOT_FOUND );
		}
	}

	public static function getFuture() {
		$pdo = Database::getPdo();

		$statement = $pdo->prepare( "SELECT events.id, events.name, events.location, events.dateTime, boardgames.id AS boardgame_id, boardgames.name AS boardgame_name, boardgames.description AS boardgame_description FROM events  JOIN boardgames ON boardgames.id = events.boardgame_id WHERE events.dateTime >= now()" );
		$statement->execute();
		if ( $statement->rowCount() > 0 ) {
			$rows = $statement->fetchAll();
			$events  = [];
			foreach ( $rows as $row ) {
				$boardgame = new BoardgameModel( $row['boardgame_name'], $row['boardgame_description'], $row['boardgame_id'] );
				$events[] = new EventModel( $row['name'], $row['location'], $row['dateTime'], $boardgame, $row['id'] );
			}

			return $events;
		}
		else {
			throw new Exception( "No Events Found", ROW_NOT_FOUND );
		}
	}

	private function loadResults() {
		$this->results = ResultModel::getResultsForEvent( $this->id );
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

	public function setDateTime( String $dateTime ) {
		$this->dateTime = $dateTime;
		$this->carbon   = new Carbon\Carbon( $dateTime );
	}

	/**
	 * @return BoardgameModel
	 */
	public function getBoardgame(): BoardgameModel {
		return $this->boardgame;
	}

	/**
	 * @param BoardgameModel $boardgame
	 */
	public function setBoardgame( BoardgameModel $boardgame ) {
		$this->boardgame = $boardgame;
	}


	public function getResults() {
		if ( $this->results == null ) {
			$this->loadResults();
		}

		return $this->results;
	}

}