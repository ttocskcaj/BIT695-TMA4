<?php

class PlayerModel {

	private $id;
	private $first_name;
	private $family_name;
	private $email;
	private $phone;

	/**
	 * PlayerModel constructor.
	 * Creates a new PlayerModel with the defined attributes.
	 *
	 * @param $first_name String - The players first name.
	 * @param $family_name String - The players family/last name.
	 * @param $email String - The players email address.
	 * @param $phone String - The players phone number.
	 * @param $id int - The players ID.
	 */
	function __construct( $first_name, $family_name, $email, $phone, $id = null ) {
		$this->first_name  = $first_name;
		$this->family_name = $family_name;
		$this->email       = $email;
		$this->phone       = $phone;
		if ( $id != null ) {
			$this->id = $id;
		}
	}

	/**
	 * Saves (inserts) the user into the database.
	 *
	 * @throws PDOException
	 * @return bool True if the query is successful. False if it fails.
	 */
	public function save() {
		// Get DPO object.
		$pdo = Database::getPdo();

		// Create a prepared statement for the query to insert the player.
		$statement = $pdo->prepare( "INSERT INTO players (first_name, family_name, email, phone) VALUES (:first_name, :family_name, :email, :phone)" );
		// Bind the named parameters to the statement.
		$statement->bindParam( ":first_name", $this->first_name );
		$statement->bindParam( ":family_name", $this->family_name );
		$statement->bindParam( ":email", $this->email );
		$statement->bindParam( ":phone", $this->phone );

		// Execute the statement to insert the player.
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

		// Create a prepared statement for the query to insert the player.
		$statement = $pdo->prepare( "DELETE FROM players WHERE id=?" );

		// Execute the statement to insert the player.
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

		// Create a prepared statement for the query to insert the player.
		$statement = $pdo->prepare( "UPDATE players SET first_name = :first_name, family_name = :family_name, email = :email, phone = :phone WHERE id = :id" );
		// Bind the named parameters to the statement.
		$statement->bindParam( ":first_name", $this->first_name );
		$statement->bindParam( ":family_name", $this->family_name );
		$statement->bindParam( ":email", $this->email );
		$statement->bindParam( ":phone", $this->phone );
		$statement->bindParam( ":id", $this->id );

		// Execute the statement to insert the player.
		return $statement->execute();
	}

	/**
	 * Finds a player in the database by matching their ID.
	 *
	 * @param $id String - The Players ID number
	 */
	public static function findByID( $id ) {
		$pdo = Database::getPdo();

		$statement = $pdo->prepare( "SELECT id, first_name, family_name, email, phone FROM players  WHERE id=?" );
		$statement->execute( [ $id ] );
		if ( $statement->rowCount() > 0 ) {
			$player_data = $statement->fetch();

			return new PlayerModel( $player_data['first_name'], $player_data['family_name'], $player_data['email'], $player_data['phone'], $player_data['id'] );
		}
		else {
			throw new Exception( "Player not found", ROW_NOT_FOUND );
		}
	}

	public static function getAll() {
		$pdo = Database::getPdo();

		$statement = $pdo->prepare( "SELECT id, first_name, family_name, email, phone FROM players" );
		$statement->execute();
		if ( $statement->rowCount() > 0 ) {
			$player_results = $statement->fetchAll();
			$players        = [];
			foreach ( $player_results as $player ) {
				$players[] = new PlayerModel( $player['first_name'], $player['family_name'], $player['email'], $player['phone'], $player['id'] );
			}

			return $players;
		}
		else {
			throw new Exception( "No Players Found", ROW_NOT_FOUND );
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
	public function getFirstName(): String {
		return $this->first_name;
	}

	/**
	 * @param String $first_name
	 */
	public function setFirstName( String $first_name ) {
		$this->first_name = $first_name;
	}

	/**
	 * @return String
	 */
	public function getFamilyName(): String {
		return $this->family_name;
	}

	/**
	 * @param String $family_name
	 */
	public function setFamilyName( String $family_name ) {
		$this->family_name = $family_name;
	}

	public function getFullName() {
		return $this->first_name . " " . $this->family_name;
	}

	/**
	 * @return String
	 */
	public function getEmail(): String {
		return $this->email;
	}

	/**
	 * @param String $email
	 */
	public function setEmail( String $email ) {
		$this->email = $email;
	}

	/**
	 * @return String
	 */
	public function getPhone(): String {
		return $this->phone;
	}

	/**
	 * @param String $phone
	 */
	public function setPhone( String $phone ) {
		$this->phone = $phone;
	}


}