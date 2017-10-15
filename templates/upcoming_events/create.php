<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Event</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./style.css">

</head>
<body>
<div class="container">
	<?php require "templates/other/navigation.php" ?>

    <h1 class="text-center">Create Event</h1>
    <p>Fill out the form and click "Submit" to create a new event.</p>
    <form action="/upcoming_events.php?page=store" method="post" id="form">
        <div class="form-group">
            <label for="name">Event Name: </label>
            <!-- Input for First Name. Using HTML5 validation, can't be more than 35 characters and is required -->
            <input type="text" id="name" name="name" class="form-input"
                   placeholder="Event Name" required maxlength="35">
        </div>
        <div class="form-group">
            <label for="location">Location: </label>
            <!-- Input for Last Name. Using HTML5 validation, can't be more than 35 characters and is required -->
            <input type="text" id="location" name="location" class="form-input"
                   placeholder="Location" required maxlength="90">
        </div>
        <div class="form-group">
            <label for="dateTime">Date and Time: </label>
            <!-- Input for Email. Using HTML5 validation, must be an email address and is required -->
            <input type="datetime-local" id="dateTime" name="dateTime" class="form-input" required>
        </div>
        <div class="form-group">
            <label for="boardgame">Board Game: </label>
            <select id="boardgame" name="boardgame" class="form-input" required>
				<?php foreach ( $boardgames as $boardgame ): ?>
                    <option value="<?php echo $boardgame->getId(); ?>"><?php echo $boardgame->getName(); ?></option>
				<?php endforeach; ?>
            </select>
        </div>
        <button type="submit">Submit</button>
        <button type="reset">Clear Form</button>
    </form>
</div>

<script type="text/javascript">

    /**
     * Checks if the name field is valid. If not, sets an appropriate message.
     * @param name The input object
     */
    var nameValidation = function (name) {
        if (name.validity.valueMissing) {
            name.setCustomValidity("A name for the event is required.");
        } else if (name.validity.tooLong) {
            name.setCustomValidity("The event name can't be more than 35 characters long.")
        } else {
            name.setCustomValidity("");
        }
    };

    /**
     * Checks if the location field is valid. If not, sets an appropriate message.
     * @param location The input object
     */
    var locationValidation = function (location) {
        if (location.validity.valueMissing) {
            location.setCustomValidity("A location for the event is required.");
        } else if (location.validity.tooLong) {
            location.setCustomValidity("The location can't be more than 90 characters long.")
        } else {
            location.setCustomValidity("");
        }
    };

    /**
     * Checks if the dateTime field is valid. If not, sets an appropriate message.
     * @param dateTime The input object
     */
    var dateTimeValidation = function (dateTime) {
        if (dateTime.validity.valueMissing) {
            dateTime.setCustomValidity("A date and time is required.");
        } else {
            dateTime.setCustomValidity("");
        }
    };

    /**
     * Checks if the boardgame field is valid. If not, sets an appropriate message.
     * @param boardgame The input object
     */
    var boardgameValidation = function (boardgame) {
        if (boardgame.validity.valueMissing) {
            dateTime.setCustomValidity("Please select a board game.");
        } else {
            dateTime.setCustomValidity("");
        }
    };


    // When the page loads,
    window.onload = function () {

        // Gets each object from the DOM.
        var form = document.getElementById("form");
        var name = document.getElementById("name");
        var location = document.getElementById("location");
        var dateTime = document.getElementById("dateTime");
        var boardgame = document.getElementById("boardgame");


        // When the page is loaded, call each of the methods to set the validation messages for each input.
        // This is in case a user submits without entering anything.
        nameValidation(name);
        locationValidation(location);
        dateTimeValidation(dateTime);
        boardgameValidation(boardgame);

        // Each time there is an "input" event, update each of the validation messages.
        form.addEventListener("input", function () {
            nameValidation(name);
            locationValidation(location);
            dateTimeValidation(dateTime);
            boardgameValidation(boardgame);
        });

    };
</script>

</body>
</html>