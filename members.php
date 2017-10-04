<?php
error_reporting( E_ALL );
// Require needed files.
require_once "includes/helpers.php";
require_once "includes/Database.php";
require_once "includes/Validate.php";
require_once "includes/ValidationResult.php";
require_once "includes/MemberModel.php";
require_once "includes/BoardgameModel.php";


// If user is requesting the create page.
if ( $_GET['page'] == "create" ) {
	$boardgames = BoardgameModel::getAll();
	// Require the template for member creation form.
	include "templates/members/create.php";
} // If user is requesting the store page.

// If the user is requesting the store page (save member to database)
elseif ( $_GET['page'] == "store" ) {
	// Convert a single boardgame submission to an array.
	$boardgames = array();
	if ( isset( $_POST['boardgames'] ) ) {
		if ( is_array( $_POST['boardgames'] ) ) {
			$boardgames = $_POST['boardgames'];
		}
		else {
			$boardgames[] = $_POST['boardgames'];
		}
	}

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
		$try_again = "/members.php?page=create";
		// Include the template to display errors to user.
		include 'templates/other/validation_failed.php';

	} // If validation passed
	else {
		// Create a new MemberModel with the information from the form.
		$member = new MemberModel( $_POST['firstName'], $_POST['familyName'], $_POST['email'], $_POST['phone'] );
		// Try and save that member to the database.
		try {
			$member->save();
			$member->syncBoardgames($boardgames);
			// If the user is saved correctly, show a success message.
			showSuccessMessage( "New Member", "New member created successfully.", 'members.php?page=create' );
		} catch ( PDOException $e ) {
			// If there is an error with PDO, show the error message.
			showErrorMessage( "Database Error", $e->getMessage() );
		}
	}
}

// If the user is requesting the show page.
elseif ( $_GET['page'] == "show" ) {
	// Quick bit of validation.
	// If the id is set and is a number get the member from database.
	if ( isset ( $_GET['id'] ) && is_numeric( $_GET['id'] ) ) {
		try {
			// Get that member from the database.
			$member = MemberModel::findByID( $_GET['id'] );
			// Include the template to show that member.
			include 'templates/members/show.php';
		} catch ( PDOException $e ) {
			// If there was a database error, display it.
			showErrorMessage( "Database Error", $e->getMessage() );
		} catch ( Exception $e ) {
			// If no such member was found, show a message.
			if ( $e->getCode() == ROW_NOT_FOUND ) {
				showErrorMessage( 'User Not Found', 'That user was not found in the database.', 'members.php?page=index' );
			}
			else {
				// In case the exception was something else, throw it again.
				throw $e;
			}
		}
	} // If the id isn't set or isn't a number, show an error.
	else {
		showErrorMessage( 'Error', 'Invalid member id.', 'members.php?page=index' );
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
		$try_again = "/members.php?page=show&id=" . $_POST['id'];
		// Include the template to display errors to user.
		include 'templates/other/validation_failed.php';

	} // If validation passed
	else {
		try {
			// Try and load the existing member from the database using the ID.
			$member = MemberModel::findByID( $_POST['id'] );

			// Update the members attributes from the form data.
			$member->setFirstName( $_POST['firstName'] );
			$member->setFamilyName( $_POST['familyName'] );
			$member->setEmail( $_POST['email'] );
			$member->setPhone( $_POST['phone'] );

			// Update the database row.
			$member->update();

			// If the user is updated correctly, show a success message.
			showSuccessMessage( "Updated Member", "The member's information was updated.", 'members.php?page=index' );
		} catch ( PDOException $e ) {
			// If there is an error with PDO, show the error message.
			showErrorMessage( "Database Error", $e->getMessage() );
		}
	}
}

// If the user is requesting the delete page.
elseif ( $_GET['page'] == "delete" ) {
	// Quick bit of validation.
	// If the id is set and is a number get the member from database.
	if ( isset ( $_GET['id'] ) && is_numeric( $_GET['id'] ) ) {
		// Check if the user has confirmed the deletion
		if ( isset( $_GET['confirm'] ) ) {
			try {
				// Delete that member from the database.
				MemberModel::delete( $_GET['id'] );

				showSuccessMessage( "Deleted Member", "The member was deleted.", 'members.php?page=index' );

			} catch ( PDOException $e ) {
				// If there was a database error, display it.
				showErrorMessage( "Database Error", $e->getMessage() );
			} catch ( Exception $e ) {
				// If no such member was found, show a message.
				if ( $e->getCode() == ROW_NOT_FOUND ) {
					showErrorMessage( 'User Not Found', 'That user was not found in the database.', 'members.php?page=index' );
				}
				else {
					// In case the exception was something else, throw it again.
					throw $e;
				}
			}
		}
		else {
			// Show a confirmation message.
			showErrorMessage( "Delete User", 'Are you sure you want to delete this user?! <a href="members.php?page=delete&id=' . $_GET['id'] . '&confirm=yes">CLICK TO DELETE</a>' );
		}
	} // If the id isn't set or isn't a number, show an error.
	else {
		showErrorMessage( 'Error', 'Invalid member id.', 'members.php?page=index' );
	}
}

// If the user is requesting the index page.
elseif ( $_GET['page'] == "index" ) {
	// Get array of all members from the database.
	try {
		$members = MemberModel::getAll();
	} catch ( Exception $e ) {
		// If no such member was found, show a message.
		if ( $e->getCode() == ROW_NOT_FOUND ) {
			showErrorMessage( 'No Members Found', 'There are no members in the database <a href="members.php?page=create">Create One</a>' );
		}
		else {
			// In case the exception was something else, throw it again.
			throw $e;
		}
	}
	// Include the template to show them.
	include 'templates/members/index.php';
}
else {
	// If no "page" is provided, redirect to index.
	header( "Location: /members.php?page=index" );
}