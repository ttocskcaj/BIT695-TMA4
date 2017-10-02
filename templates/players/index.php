<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Players</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./style.css">

</head>
<body>
<div class="container">
    <h1 class="text-center">Players</h1>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Family Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Tools</th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ( $players as $player ): ?>
            <tr>
                <td><?php echo $player->getId(); ?></td>
                <td><?php echo $player->getFirstName(); ?></td>
                <td><?php echo $player->getFamilyName(); ?></td>
                <td><?php echo $player->getEmail(); ?></td>
                <td><?php echo $player->getPhone(); ?></td>
                <td><a href="players.php?page=show&id=<?php echo $player->getId(); ?>">Edit</a> <a
                            href="players.php?page=delete&id=<?php echo $player->getId(); ?>">Delete</a></td>
            </tr>
		<?php endforeach; ?>
        </tbody>

    </table>
    <a href="players.php?page=create">Create new Player</a>

</div>


</body>
</html>