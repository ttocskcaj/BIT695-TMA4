<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Boardgame</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./style.css">


</head>
<body>
<div class="container">
	<?php require "templates/other/navigation.php" ?>

    <h1 class="text-center">Create Boardgame</h1>
    <p>Fill out the form and click "Submit" to create a new boardgame.</p>
    <form action="/boardgames.php?page=store" method="post" id="form">
        <div class="form-group">
            <label for="name">Name: </label>
            <!-- Input for First Name. Using HTML5 validation, can't be more than 35 characters and is required -->
            <input type="text" id="name" name="name" class="form-input"
                   placeholder="Name of boardgame" required maxlength="90">
        </div>
        <div class="form-group">
            <label for="description">Description: </label>
            <!-- Input for Last Name. Using HTML5 validation, can't be more than 35 characters and is required -->
            <textarea name="description" id="description" rows="8" class="form-input" required
                      maxlength="512"></textarea>
        </div>

        <div class="form-group">
            <button type="submit">Submit</button>
            <button type="reset">Clear Form</button>
        </div>

    </form>
</div>

<script type="text/javascript">
    /**
     * Checks if the name field is valid. If not, sets an appropriate message.
     * @param name The input object
     */
    var nameValidation = function (name) {
        if (name.validity.valueMissing) {
            name.setCustomValidity("The name of the board game is required.");
        } else if (name.validity.tooLong) {
            name.setCustomValidity("The name of the board game can't be more than 90 characters long.")
        } else {
            name.setCustomValidity("");
        }
    };

    /**
     * Checks if the description field is valid. If not, sets an appropriate message.
     * @param description The input object
     */
    var descriptionValidation = function (description) {
        if (description.validity.valueMissing) {
            description.setCustomValidity("A description for the boardgame is required.");
        } else if (description.validity.tooLong) {
            description.setCustomValidity("The description for the boardgame can't be more than 512 characters long.")
        } else {
            description.setCustomValidity("");
        }
    };

    // When the page loads,
    window.onload = function () {

        // Gets each object from the DOM.
        var form = document.getElementById("form");
        var name = document.getElementById("name");
        var description = document.getElementById("description");

        // When the page is loaded, call each of the methods to set the validation messages for each input.
        // This is in case a user submits without entering anything.
        nameValidation(firstName);
        descriptionValidation(familyName);


        // Each time there is an "input" event, update each of the validation messages.
        form.addEventListener("input", function () {
            nameValidation(firstName);
            descriptionValidation(familyName);
        });

    };
</script>

</body>
</html>