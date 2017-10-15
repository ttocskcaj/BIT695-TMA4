<?php
error_reporting( E_ALL );
// Require needed files.
require_once "includes/helpers.php";
require_once "includes/Database.php";
require_once "includes/Validate.php";
require_once "includes/ValidationResult.php";
require_once "includes/Carbon.php"; // Third party date time class.
require_once "includes/EventModel.php";
require_once "includes/BoardgameModel.php";

// If user is requesting the create page.
if ( $_GET['page'] == "create" ) {
	$boardgames = BoardgameModel::getAll();
	// Require the html to show the event creation form.
	include "templates/upcoming_events/create.php";
} // If user is requesting the store page.

// If the user is requesting the store page (save event to database)
elseif ( $_GET['page'] == "store" ) {
	// Validate the form data.
	$validation_results = Validate::check( $_POST, [
		"name" => "required|max:35",
		"location"  => "required|max:90",
		"dateTime"  => "required|datetime",
		"boardgame" => "required"
	] );

	// If validation failed
	if ( $validation_results->hasErrors() ) {
		// Variable to allow the user to navigate back to the form.
		$try_again = "/upcoming_events.php?page=create";
		// Include the template to display errors to user.
		include 'templates/other/validation_failed.php';

	} // If validation passed
	else {
		// Create a new EventModel with the information from the form.
		$boardgame = BoardgameModel::findByID($_POST['boardgame']);
		$event = new EventModel( $_POST['name'], $_POST['location'], $_POST['dateTime'], $boardgame);
		// Try and save that event to the database.
		try {
			$event->save();
			// If the user is saved correctly, show a success message.
			showSuccessMessage( "New Event", "New event created successfully.", 'upcoming_events.php?page=index' );
		} catch ( PDOException $e ) {
			// If there is an error with PDO, show the error message.
			showErrorMessage( "Database Error", $e->getMessage() );
		}
	}
}

// If the user is requesting the show page.
elseif ( $_GET['page'] == "show" ) {
	// Quick bit of validation.
	// If the id is set and is a number get the event from database.
	if ( isset ( $_GET['id'] ) && is_numeric( $_GET['id'] ) ) {
		try {
			// Get that event from the database.
			$event = EventModel::findByID( $_GET['id'] );
			// Include the template to show that events.
			include 'templates/upcoming_events/show.php';
		} catch ( PDOException $e ) {
			// If there was a database error, display it.
			showErrorMessage( "Database Error", $e->getMessage() );
		} catch ( Exception $e ) {
			// If no such event was found, show a message.
			if ( $e->getCode() == ROW_NOT_FOUND ) {
				showErrorMessage( 'Event Not Found', 'That event was not found in the database.', 'upcoming_events.php?page=index' );
			}
			else {
				// In case the exception was something else, throw it again.
				throw $e;
			}
		}
	} // If the id isn't set or isn't a number, show an error.
	else {
		showErrorMessage( 'Error', 'Invalid event id.', 'upcoming_events.php?page=index' );
	}
}

// If the user is requesting the update page.
elseif ( $_GET['page'] == "update" ) {
	// Validate
	$validation_results = Validate::check( $_POST, [
		"name" => "required|max:35",
		"location"  => "required|max:90",
		"dateTime"  => "required|datetime",
		"boardgame" => "required"
	] );

	// If validation failed
	if ( $validation_results->hasErrors() ) {
		// Variable to allow the user to navigate back to the form.
		$try_again = "/upcoming_events.php?page=show&id=" . $_POST['id'];
		// Include the template to display errors to user.
		include 'templates/other/validation_failed.php';

	} // If validation passed
	else {
		try {
			// Try and load the existing event from the database using the ID.
			$event = EventModel::findByID( $_POST['id'] );

			// Update the events attributes from the form data.
			$event->setName( $_POST['name'] );
			$event->setLocation( $_POST['location'] );
			$event->setDateTime( $_POST['dateTime'] );

			// Update the database row.
			$event->update();

			// If the user is updated correctly, show a success message.
			showSuccessMessage( "Updated Event", "The event's information was updated.", 'upcoming_events.php?page=index' );
		} catch ( PDOException $e ) {
			// If there is an error with PDO, show the error message.
			showErrorMessage( "Database Error", $e->getMessage() );
		}
	}
}

// If the user is requesting the delete page.
elseif ( $_GET['page'] == "delete" ) {
	// Quick bit of validation.
	// If the id is set and is a number get the event from database.
	if ( isset ( $_GET['id'] ) && is_numeric( $_GET['id'] ) ) {
		// Check if the user has confirmed the deletion
		if ( isset( $_GET['confirm'] ) ) {
			try {
				// Delete that event from the database.
				EventModel::delete( $_GET['id'] );

				showSuccessMessage( "Deleted Event", "The event was deleted.", 'upcoming_events.php?page=index' );

			} catch ( PDOException $e ) {
				// If there was a database error, display it.
				showErrorMessage( "Database Error", $e->getMessage() );
			} catch ( Exception $e ) {
				// If no such event was found, show a message.
				if ( $e->getCode() == ROW_NOT_FOUND ) {
					showErrorMessage( 'Event Not Found', 'That event was not found in the database.', 'upcoming_events.php?page=index' );
				}
				else {
					// In case the exception was something else, throw it again.
					throw $e;
				}
			}
		}
		else {
			// Show a confirmation message.
			showErrorMessage( "Delete Event", 'Are you sure you want to delete this event?! <a href="upcoming_events.php?page=delete&id=' . $_GET['id'] . '&confirm=yes">CLICK TO DELETE</a>' );
		}
	} // If the id isn't set or isn't a number, show an error.
	else {
		showErrorMessage( 'Error', 'Invalid event id.', 'upcoming_events.php?page=index' );
	}
}

// If the user is requesting the index page.
elseif ( $_GET['page'] == "index" ) {
	// Get array of all event from the database.
	try {
		$events = EventModel::getFuture();
	} catch ( Exception $e ) {
		// If no such event was found, show a message.
		if ( $e->getCode() == ROW_NOT_FOUND ) {
			showErrorMessage( 'No Events Found', 'There are no upcoming events in the database <a href="upcoming_events.php?page=create">Create One</a>' );
		}
		else {
			// In case the exception was something else, throw it again.
			throw $e;
		}
	}
	// Include the template to show them.
	include 'templates/upcoming_events/index.php';
}
else {
	// If no "page" is provided, redirect to index.
	header( "Location: /upcoming_events.php?page=index" );
}