<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $member->getFullName(); ?></title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./style.css">

</head>
<body>
<div class="container">
    <nav>
        <ul>
            <li><a href="/events.php">Upcoming Events</a></li>
            <li><a href="/members.php">Members</a></li>
            <li><a href="/boardgames.php">Board Games</a></li>
            <li><a href="/members.php">Previous Results/High Scores</a></li>
        </ul>
    </nav>
    <h1 class="text-center"><?php echo $member->getFullName(); ?></h1>
    <p>Edit the member and click "update" to save changes.</p>
    <form action="/members.php?page=update" method="post" id="form">
        <!-- A hidden input to hold the members ID for updating the DB row -->
        <input type="hidden" name="id" id="id" value="<?php echo $member->getId(); ?>">
        <div class="form-group">
            <label for="firstName">First Name: </label>
            <!-- Input for First Name. Using HTML5 validation, can't be more than 35 characters and is required -->
            <input type="text" id="firstName" name="firstName" class="form-input"
                   placeholder="First Name" required maxlength="35"
                   value="<?php echo $member->getFirstName(); ?>">
        </div>
        <div class="form-group">
            <label for="familyName">Family Name: </label>
            <!-- Input for Last Name. Using HTML5 validation, can't be more than 35 characters and is required -->
            <input type="text" id="familyName" name="familyName" class="form-input"
                   placeholder="Family Name" required maxlength="35"
                   value="<?php echo $member->getFamilyName(); ?>">
        </div>
        <div class="form-group">
            <label for="email">Email Address: </label>
            <!-- Input for Email. Using HTML5 validation, must be an email address and is required -->
            <input type="email" id="email" name="email" class="form-input"
                   placeholder="Email Address" required
                   value="<?php echo $member->getEmail(); ?>">
        </div>
        <div class="form-group">
            <label for="email">Phone Number: </label>
            <!-- Input for Phone. Using HTML5 validation, Must contain 5-10 numbers -->
            <input type="tel" id="phone" name="phone" class="form-input"
                   placeholder="Phone Number" required pattern="[0-9]{5,15}"
                   value="<?php echo $member->getPhone(); ?>">
        </div>
        <button type="submit">Update</button>
        <button type="reset">Clear Form</button>
    </form>
</div>

<script type="text/javascript">

    /**
     * Checks if the firstName field is valid. If not, sets an appropriate message.
     * @param firstName The input object
     */
    var firstNameValidation = function (firstName) {
        if (firstName.validity.valueMissing) {
            firstName.setCustomValidity("Your first name is required.");
        } else if (firstName.validity.tooLong) {
            firstName.setCustomValidity("Your first name can't be more than 35 characters long.")
        } else {
            firstName.setCustomValidity("");
        }
    };

    /**
     * Checks if the familyName field is valid. If not, sets an appropriate message.
     * @param familyName The input object
     */
    var familyNameValidation = function (familyName) {
        if (familyName.validity.valueMissing) {
            familyName.setCustomValidity("Your family name is required.");
        } else if (familyName.validity.tooLong) {
            familyName.setCustomValidity("Your family name can't be more than 35 characters long.")
        } else {
            familyName.setCustomValidity("");
        }
    };

    /**
     * Checks if the email field is valid. If not, sets an appropriate message.
     * @param email The input object
     */
    var emailValidation = function (email) {
        if (email.validity.typeMismatch) {
            email.setCustomValidity("Please enter a valid email address.");
        } else if (email.validity.valueMissing) {
            email.setCustomValidity("Your email address is required.");
        } else {
            email.setCustomValidity("");
        }
    };

    /**
     * Checks if the phone field is valid. If not, sets an appropriate message.
     * @param phone The input object
     */
    var phoneValidation = function (phone) {
        if (phone.validity.patternMismatch) {
            phone.setCustomValidity("Your phone must be between 5-15 numbers only.");
        } else if (phone.validity.valueMissing) {
            phone.setCustomValidity("Your phone number is required.");
        } else {
            phone.setCustomValidity("");
        }
    };


    // When the page loads,
    window.onload = function () {

        // Gets each object from the DOM.
        var form = document.getElementById("form");
        var firstName = document.getElementById("firstName");
        var familyName = document.getElementById("familyName");
        var email = document.getElementById("email");
        var phone = document.getElementById("phone");

        // When the page is loaded, call each of the methods to set the validation messages for each input.
        // This is in case a user submits without entering anything.
        firstNameValidation(firstName);
        familyNameValidation(familyName);
        emailValidation(email);
        phoneValidation(phone);

        // Each time there is an "input" event, update each of the validation messages.
        form.addEventListener("input", function () {
            firstNameValidation(firstName);
            familyNameValidation(familyName);
            emailValidation(email);
            phoneValidation(phone);
        });

    };
</script>

</body>
</html>