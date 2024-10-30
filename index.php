<?php 
require_once 'core/models.php'; 
require_once 'core/handleForms.php'; 

if (!isset($_SESSION['username'])) {
	header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>

    <?php if (isset($_SESSION['message'])) { ?>
		<h1 style="color: red;"><?php echo $_SESSION['message']; ?></h1>
	<?php } unset($_SESSION['message']); ?>

	<?php if (isset($_SESSION['username'])) { ?>
		<h1>Hello there!! <?php echo $_SESSION['username']; ?></h1>
		<a href="core/handleForms.php?logoutAUser=1">Logout</a>
	<?php } else { echo "<h1>No user logged in</h1>";}?>

	<h1>Welcome To Book Store Management System. Add new Authors!</h1>
	<form action="core/handleForms.php" method="POST">
		<p>
			<label for="firstname">First Name: </label> 
			<input type="text" name="firstname">
		</p>
		<p>
			<label for="lastname">Last Name: </label> 
			<input type="text" name="lastname">
		</p>
		<p>
			<label for="nationality">Nationality: </label> 
			<input type="text" name="nationality">
		</p>
		<p>
			<label for="contactInfo">Contact Info: </label> 
			<input type="text" name="contactInfo">
		</p>
		<p>
			<label for="dateAdded">Date: </label> 
			<input type="date" name="dateAdded">
			<input type="submit" name="insertAuthorBtn">
		</p>
	</form>
	<?php $getAllAuthors = getAllAuthors($pdo); ?>
	<?php foreach ($getAllAuthors as $row) { ?>
	<div class="container" style="border-style: solid; width: 50%; height: 300px; margin-top: 20px;">
	    <h3>Author ID: <?php echo $row['authorID']; ?></h3>
	    <h3>First Name: <?php echo $row['firstname']; ?></h3>
		<h3>Last Name: <?php echo $row['lastname']; ?></h3>
		<h3>Nationality: <?php echo $row['nationality']; ?></h3>
		<h3>Contact Info: <?php echo $row['contactInfo']; ?></h3>
		<h3>Date Added: <?php echo $row['dateAdded']; ?></h3>


		<div class="editAndDelete" style="float: right; margin-right: 20px;">
			<a href="viewbooks.php?authorID=<?php echo $row['authorID']; ?>">View Books</a>
			<a href="editauthor.php?authorID=<?php echo $row['authorID']; ?>">Edit</a>
			<a href="deleteauthor.php?authorID=<?php echo $row['authorID']; ?>">Delete</a>
		</div>


	</div> 
	<?php } ?>
</body>
</html>