<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Boardgames</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">


</head>
<body>
<div class="container">
	<?php require "templates/other/navigation.php" ?>
    <h1 class="text-center">Board Games</h1>
	<?php foreach ( $boardgames as $boardgame ): ?>
        <div class="card">
            <h3><?php echo $boardgame->getName(); ?></h3>
            <p>
				<?php echo $boardgame->getDescription(); ?>
            </p>

            <div class="card-controls">
                <a class="card-button" href="boardgames.php?page=show&id=<?php echo $boardgame->getId(); ?>">Edit</a>
                <a class="card-button"
                   href="boardgames.php?page=delete&id=<?php echo $boardgame->getId(); ?>">Delete</a>
            </div>
        </div>
	<?php endforeach; ?>
    <div>
    </div>
    <a href="boardgames.php?page=create">Create new Boardgame</a>

</div>


</body>
</html>