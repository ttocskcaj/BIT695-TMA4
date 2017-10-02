<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Validation Failed</title>

    <link rel="stylesheet" href="./style.css">

</head>
<body>
<div class="container">
    <h1>Validation Failed!</h1>

	<?php foreach ( $validation_results->getResults() as $input => $errors ): ?>
        <h4><?php echo $input; ?></h4>
        <ul>
			<?php foreach ( $errors as $error ): ?>
                <li><?php echo $error; ?></li>
			<?php endforeach; ?>
        </ul>
	<?php endforeach; ?>

    <a href="<?php echo $try_again; ?>">Click to try again.</a>
</div>


</body>
</html>