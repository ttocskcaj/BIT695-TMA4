<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Members</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">


</head>
<body>
<div class="container">
    <nav>
        <ul>
            <li><a href="/events.php">Upcoming Events</a></li>
            <li><a href="/members.php">Members</a></li>
            <li><a href="/boardgames.php">Board Games</a></li>
            <li><a href="/members.php">Members</a></li>
            <li><a href="/members.php">Previous Results/High Scores</a></li>
        </ul>
    </nav>
    <h1 class="text-center">Members</h1>
	<?php foreach ( $members as $member ): ?>
        <div class="card">
            <h3><?php echo $member->getFullName(); ?></h3>
            <p>
                <i class="fa fa-fw fa-envelope"></i> <?php echo $member->getEmail(); ?><br/>
                <i class="fa fa-fw fa-phone"></i> <?php echo $member->getPhone(); ?>
            </p>
            <h4>Board Games:</h4>
            <p>
				<?php
				$boardgames = $member->getBoardgames();
				if ( count( $boardgames ) > 0 ) {
					$string = '';
					foreach ( $boardgames as $boardgame ) {
						$string .= '<a href="boardgames.php?show&id=' . $boardgame->getId() . '">' . $boardgame->getName() . '</a>, ';
					}
					echo rtrim( $string, ', ' );
				}
				else {
					echo "None.";
				}
				?>


            </p>
            <div class="card-controls">
                <a class="card-button" href="members.php?page=show&id=<?php echo $member->getId(); ?>">Edit</a>
                <a class="card-button" href="members.php?page=delete&id=<?php echo $member->getId(); ?>">Delete</a>
            </div>
        </div>
	<?php endforeach; ?>
    <div>
    </div>
    <a href="members.php?page=create">Create new Member</a>

</div>


</body>
</html>