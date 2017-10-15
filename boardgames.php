<?php
error_reporting( E_ALL );
// Require needed files.
require_once "includes/helpers.php";
require_once "includes/Database.php";
require_once "includes/Validate.php";
require_once "includes/ValidationResult.php";
require_once "includes/Carbon.php"; // Third party date time class.
require_once "includes/BoardgameModel.php";

// If user is requesting the create page.
if ( $_GET['page'] == "create" ) {
	// Require the html to show the boardgame creation form.
	include "templates/boardgames/create.php";
} // If user is requesting the store page.

// If the user is requesting the store page (save boardgame to database)
elseif ( $_GET['page'] == "store" ) {
	// Validate the form data.
	$validation_results = Validate::check( $_POST, [
		"name"        => "required|max:90",
		"description" => "required|max:512",
	] );

	// If validation failed
	if ( $validation_results->hasErrors() ) {
		// Variable to allow the user to navigate back to the form.
		$try_again = "/boardgames.php?page=create";
		// Include the template to display errors to user.
		include 'templates/other/validation_failed.php';

	} // If validation passed
	else {
		// Create a new BoardgameModel with the information from the form.
		$boardgame = new BoardgameModel( $_POST['name'], $_POST['description'] );
		// Try and save that boardgame to the database.
		try {
			$boardgame->save();
			// If the user is saved correctly, show a success message.
			showSuccessMessage( "New Boardgame", "New boardgame created successfully.", 'boardgames.php?page=index' );
		} catch ( PDOException $e ) {
			// If there is an error with PDO, show the error message.
			showErrorMessage( "Database Error", $e->getMessage() );
		}
	}
}

// If the user is requesting the show page.
elseif ( $_GET['page'] == "show" ) {
	// Quick bit of validation.
	// If the id is set and is a number get the boardgame from database.
	if ( isset ( $_GET['id'] ) && is_numeric( $_GET['id'] ) ) {
		try {
			// Get that boardgame from the database.
			$boardgame = BoardgameModel::findByID( $_GET['id'] );
			// Include the template to show that boardgames.
			include 'templates/boardgames/show.php';
		} catch ( PDOException $e ) {
			// If there was a database error, display it.
			showErrorMessage( "Database Error", $e->getMessage() );
		} catch ( Exception $e ) {
			// If no such boardgame was found, show a message.
			if ( $e->getCode() == ROW_NOT_FOUND ) {
				showErrorMessage( 'Boardgame Not Found', 'That boardgame was not found in the database.', 'boardgames.php?page=index' );
			}
			else {
				// In case the exception was something else, throw it again.
				throw $e;
			}
		}
	} // If the id isn't set or isn't a number, show an error.
	else {
		showErrorMessage( 'Error', 'Invalid boardgame id.', 'boardgames.php?page=index' );
	}
}

// If the user is requesting the update page.
elseif ( $_GET['page'] == "update" ) {
	// Validate
	$validation_results = Validate::check( $_POST, [
		"name"        => "required|max:90",
		"description" => "required|max:512",
	] );

	// If validation failed
	if ( $validation_results->hasErrors() ) {
		// Variable to allow the user to navigate back to the form.
		$try_again = "/boardgames.php?page=show&id=" . $_POST['id'];
		// Include the template to display errors to user.
		include 'templates/other/validation_failed.php';

	} // If validation passed
	else {
		try {
			// Try and load the existing boardgame from the database using the ID.
			$boardgame = BoardgameModel::findByID( $_POST['id'] );

			// Update the boardgames attributes from the form data.
			$boardgame->setName( $_POST['name'] );
			$boardgame->setDescription( $_POST['description'] );

			// Update the database row.
			$boardgame->update();

			// If the user is updated correctly, show a success message.
			showSuccessMessage( "Updated Boardgame", "The boardgame's information was updated.", 'boardgames.php?page=index' );
		} catch ( PDOException $e ) {
			// If there is an error with PDO, show the error message.
			showErrorMessage( "Database Error", $e->getMessage() );
		}
	}
}

// If the user is requesting the delete page.
elseif ( $_GET['page'] == "delete" ) {
	// Quick bit of validation.
	// If the id is set and is a number get the boardgame from database.
	if ( isset ( $_GET['id'] ) && is_numeric( $_GET['id'] ) ) {
		// Check if the user has confirmed the deletion
		if ( isset( $_GET['confirm'] ) ) {
			try {
				// Delete that boardgame from the database.
				BoardgameModel::delete( $_GET['id'] );

				showSuccessMessage( "Deleted Boardgame", "The boardgame was deleted.", 'boardgames.php?page=index' );

			} catch ( PDOException $e ) {
				// If there was a database error, display it.
				showErrorMessage( "Database Error", $e->getMessage() );
			} catch ( Exception $e ) {
				// If no such boardgame was found, show a message.
				if ( $e->getCode() == ROW_NOT_FOUND ) {
					showErrorMessage( 'Boardgame Not Found', 'That boardgame was not found in the database.', 'boardgames.php?page=index' );
				}
				else {
					// In case the exception was something else, throw it again.
					throw $e;
				}
			}
		}
		else {
			// Show a confirmation message.
			showErrorMessage( "Delete Boardgame", 'Are you sure you want to delete this boardgame?! <a href="boardgames.php?page=delete&id=' . $_GET['id'] . '&confirm=yes">CLICK TO DELETE</a>' );
		}
	} // If the id isn't set or isn't a number, show an error.
	else {
		showErrorMessage( 'Error', 'Invalid boardgame id.', 'boardgames.php?page=index' );
	}
}

// If the user is requesting the index page.
elseif ( $_GET['page'] == "index" ) {
	// Get array of all boardgame from the database.
	try {
		$boardgames = BoardgameModel::getAll();
	} catch ( Exception $e ) {
		// If no such boardgame was found, show a message.
		if ( $e->getCode() == ROW_NOT_FOUND ) {
			showErrorMessage( 'No Boardgames Found', 'There are no boardgames in the database <a href="boardgames.php?page=create">Create One</a>' );
		}
		else {
			// In case the exception was something else, throw it again.
			throw $e;
		}
	}
	// Include the template to show them.
	include 'templates/boardgames/index.php';
}
else {
	// If no "page" is provided, redirect to index.
	header( "Location: /boardgames.php?page=index" );
}