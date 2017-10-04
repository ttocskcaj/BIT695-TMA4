<?php

class BoardgameModel {

	private $id;
	private $name;
	private $description;

	/**
	 * BoardgameModel constructor.
	 * Creates a new BoardgameModel with the defined attributes.
	 *
	 * @param name String - The name of the boardgame.
	 * @param location String - The location of the boardgame.
	 * @param $dateTime String - A string representation of the dateTime.
	 * @param $id int - The boardgames ID.
	 */
	function __construct( $name, $description, $id = null ) {
		$this->name        = $name;
		$this->description = $description;
		if ( $id != null ) {
			$this->id = $id;
		}
	}

	/**
	 * Saves (inserts) the boardgame into the database.
	 *
	 * @throws PDOException
	 * @return bool True if the query is successful. False if it fails.
	 */
	public function save() {
		// Get DPO object.
		$pdo = Database::getPdo();

		// Create a prepared statement for the query to insert the boardgame.
		$statement = $pdo->prepare( "INSERT INTO boardgames (name, description) VALUES (:name, :description)" );
		// Bind the named parameters to the statement.
		$statement->bindParam( ":name", $this->name );
		$statement->bindParam( ":description", $this->description );

		// Execute the statement to insert the boardgame.
		return $statement->execute();
	}

	/**
	 * Deletes the boardgame from the database.
	 *
	 * @throws PDOException
	 * @return bool True if the query is successful. False if it fails.
	 */
	public static function delete( $id ) {
		// Get DPO object.
		$pdo = Database::getPdo();

		// Create a prepared statement for the query to insert the boardgame.
		$statement = $pdo->prepare( "DELETE FROM boardgames WHERE id=?" );

		// Execute the statement to insert the boardgame.
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

		// Create a prepared statement for the query to insert the boardgame.
		$statement = $pdo->prepare( "UPDATE boardgames SET name = :name, description = :description WHERE id = :id" );
		// Bind the named parameters to the statement.
		$statement->bindParam( ":name", $this->name );
		$statement->bindParam( ":description", $this->description );

		// Execute the statement to insert the boardgame.
		return $statement->execute();
	}

	/**
	 * Finds a boardgame in the database by matching their ID.
	 *
	 * @param $id integer - The Boardgames ID number
	 */
	public static function findByID( $id ) {
		$pdo = Database::getPdo();

		$statement = $pdo->prepare( "SELECT id, name, description FROM boardgames  WHERE id=?" );
		$statement->execute( [ $id ] );
		if ( $statement->rowCount() > 0 ) {
			$data = $statement->fetch();

			return new BoardgameModel( $data['name'], $data['description'], $data['id'] );
		}
		else {
			throw new Exception( "Boardgame not found", ROW_NOT_FOUND );
		}
	}

	public static function getAll() {
		$pdo = Database::getPdo();

		$statement = $pdo->prepare( "SELECT id, name, description FROM boardgames" );
		$statement->execute();
		if ( $statement->rowCount() > 0 ) {
			$results    = $statement->fetchAll();
			$boardgames = [];
			foreach ( $results as $boardgame ) {
				$boardgames[] = new BoardgameModel( $boardgame['name'], $boardgame['description'], $boardgame['id'] );
			}

			return $boardgames;
		}
		else {
			throw new Exception( "No Boardgames Found", ROW_NOT_FOUND );
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
	public function getDescription(): String {
		return $this->description;
	}

	/**
	 * @param String $family_name
	 */
	public function setDescription( String $description ) {
		$this->description = $description;
	}

}