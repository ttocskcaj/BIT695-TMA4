<?php
error_reporting( E_ALL );
// Require needed files.
require_once "includes/helpers.php";
require_once "includes/Database.php";
require_once "includes/Validate.php";
require_once "includes/ValidationResult.php";
require_once "includes/PlayerModel.php";

// If user is requesting the create page.
if ( $_GET['page'] == "create" ) {
	// Require the html to show the player creation form.
	include "templates/players/create.html";
} // If user is requesting the store page.

// If the user is requesting the store page (save player to database)
elseif ( $_GET['page'] == "store" ) {
	// Validate the form data.
	$validation_results = Validate::check( $_POST, [
		"firstName"  => "required|max:35",
		"familyName" => "required|max:35",
		"email"      => "required|email",
		"phone"      => "required|number|min:5|max:15",
	] );

	// If validation failed
	if ( $validation_results->hasErrors() ) {
		// Variable to allow the user to navigate back to the form.
		$try_again = "/players.php?page=create";
		// Include the template to display errors to user.
		include 'templates/other/validation_failed.php';

	} // If validation passed
	else {
		// Create a new PlayerModel with the information from the form.
		$player = new PlayerModel( $_POST['firstName'], $_POST['familyName'], $_POST['email'], $_POST['phone'] );
		// Try and save that player to the database.
		try {
			$player->save();
			// If the user is saved correctly, show a success message.
			showSuccessMessage( "New Player", "New player created successfully.", 'players.php?page=create' );
		} catch ( PDOException $e ) {
			// If there is an error with PDO, show the error message.
			showErrorMessage( "Database Error", $e->getMessage() );
		}
	}
}

// If the user is requesting the show page.
elseif ( $_GET['page'] == "show" ) {
	// Quick bit of validation.
	// If the id is set and is a number get the player from database.
	if ( isset ( $_GET['id'] ) && is_numeric( $_GET['id'] ) ) {
		try {
			// Get that player from the database.
			$player = PlayerModel::findByID( $_GET['id'] );
			// Include the template to show that player.
			include 'templates/players/show.php';
		} catch ( PDOException $e ) {
			// If there was a database error, display it.
			showErrorMessage( "Database Error", $e->getMessage() );
		} catch ( Exception $e ) {
			// If no such player was found, show a message.
			if ( $e->getCode() == ROW_NOT_FOUND ) {
				showErrorMessage( 'User Not Found', 'That user was not found in the database.', 'players.php?page=index' );
			}
			else {
				// In case the exception was something else, throw it again.
				throw $e;
			}
		}
	} // If the id isn't set or isn't a number, show an error.
	else {
		showErrorMessage( 'Error', 'Invalid player id.', 'players.php?page=index' );
	}
}

// If the user is requesting the update page.
elseif ( $_GET['page'] == "update" ) {
	// Validate
	$validation_results = Validate::check( $_POST, [
		"id"         => "required|number",
		"firstName"  => "required|max:35",
		"familyName" => "required|max:35",
		"email"      => "required|email",
		"phone"      => "required|number|min:5|max:15",
	] );

	// If validation failed
	if ( $validation_results->hasErrors() ) {
		// Variable to allow the user to navigate back to the form.
		$try_again = "/players.php?page=show&id=" . $_POST['id'];
		// Include the template to display errors to user.
		include 'templates/other/validation_failed.php';

	} // If validation passed
	else {
		try {
			// Try and load the existing player from the database using the ID.
			$player = PlayerModel::findByID( $_POST['id'] );

			// Update the players attributes from the form data.
			$player->setFirstName( $_POST['firstName'] );
			$player->setFamilyName( $_POST['familyName'] );
			$player->setEmail( $_POST['email'] );
			$player->setPhone( $_POST['phone'] );

			// Update the database row.
			$player->update();

			// If the user is updated correctly, show a success message.
			showSuccessMessage( "Updated Player", "The player's information was updated.", 'players.php?page=index' );
		} catch ( PDOException $e ) {
			// If there is an error with PDO, show the error message.
			showErrorMessage( "Database Error", $e->getMessage() );
		}
	}
}

// If the user is requesting the delete page.
elseif ( $_GET['page'] == "delete" ) {
	// Quick bit of validation.
	// If the id is set and is a number get the player from database.
	if ( isset ( $_GET['id'] ) && is_numeric( $_GET['id'] ) ) {
		// Check if the user has confirmed the deletion
		if ( isset( $_GET['confirm'] ) ) {
			try {
				// Delete that player from the database.
				PlayerModel::delete( $_GET['id'] );

				showSuccessMessage( "Deleted Player", "The player was deleted.", 'players.php?page=index' );

			} catch ( PDOException $e ) {
				// If there was a database error, display it.
				showErrorMessage( "Database Error", $e->getMessage() );
			} catch ( Exception $e ) {
				// If no such player was found, show a message.
				if ( $e->getCode() == ROW_NOT_FOUND ) {
					showErrorMessage( 'User Not Found', 'That user was not found in the database.', 'players.php?page=index' );
				}
				else {
					// In case the exception was something else, throw it again.
					throw $e;
				}
			}
		}
		else {
			// Show a confirmation message.
			showErrorMessage( "Delete User", 'Are you sure you want to delete this user?! <a href="players.php?page=delete&id=' . $_GET['id'] . '&confirm=yes">CLICK TO DELETE</a>' );
		}
	} // If the id isn't set or isn't a number, show an error.
	else {
		showErrorMessage( 'Error', 'Invalid player id.', 'players.php?page=index' );
	}
}

// If the user is requesting the index page.
elseif ( $_GET['page'] == "index" ) {
	// Get array of all players from the database.
	try {
		$players = PlayerModel::getAll();
	} catch ( Exception $e ) {
		// If no such player was found, show a message.
		if ( $e->getCode() == ROW_NOT_FOUND ) {
			showErrorMessage( 'No Players Found', 'There are no players in the database <a href="players.php?page=create">Create One</a>' );
		}
		else {
			// In case the exception was something else, throw it again.
			throw $e;
		}
	}
	// Include the template to show them.
	include 'templates/players/index.php';
}
else {
	// If no "page" is provided, redirect to index.
	header("Location: /players.php?page=index");
}