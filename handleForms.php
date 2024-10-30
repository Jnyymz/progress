<?php 

require_once 'dbConfig.php'; 
require_once 'models.php';


if (isset($_POST['registerUserBtn'])) {

	$username = $_POST['username'];
	$userPassword = sha1($_POST['userPassword']);

	if (!empty($username) && !empty($userPassword)) {

		$insertQuery = insertNewUser($pdo, $username, $userPassword);

		if ($insertQuery) {
			header("Location: ../login.php");
		}
		else {
			header("Location: ../register.php");
		}
	}

	else {
		$_SESSION['message'] = "Please make sure the input fields 
		are not empty for registration!";

		header("Location: ../register.php");
	}

}




if (isset($_POST['loginUserBtn'])) {

	$username = $_POST['username'];
	$userPassword = sha1($_POST['userPassword']);

	if (!empty($username) && !empty($userPassword)) {

		$loginQuery = loginUser($pdo, $username, $userPassword);
	
		if ($loginQuery) {
			header("Location: ../index.php");
		}
		else {
			header("Location: ../login.php");
		}

	}

	else {
		$_SESSION['message'] = "Please make sure the input fields 
		are not empty for the login!";
		header("Location: ../login.php");
	}
 
}



if (isset($_GET['logoutAUser'])) {
	unset($_SESSION['username']);
	header('Location: ../login.php');
}


if (isset($_POST['insertAuthorBtn'])) {

	$query = insertAuthor($pdo, $_POST['firstname'], $_POST['lastname'], 
		$_POST['nationality'], $_POST['contactInfo'], $_POST['dateAdded']);

	if ($query) {
		header("Location: ../index.php");
	}
	else {
		echo "Insertion failed";
	}

}

if (isset($_POST['editAuthorBtn'])) {
	$query = updateAuthor($pdo, $_POST['firstname'], $_POST['lastname'], 
		$_POST['nationality'], $_POST['contactInfo'], $_POST['dateAdded'], 
		$_GET['authorID']);

	if ($query) {
		header("Location: ../index.php");
	}

	else {
		echo "Edit failed";;
	}

}

ob_start();

if (isset($_POST['deleteAuthorBtn'])) {
    $query = deleteAuthor($pdo, $_GET['authorID']);

    if ($query) {
        header("Location: ../index.php");
        exit; // Ensure script stops after redirection
    } else {
        echo "Deletion failed";
    }
}

// End output buffering and flush it
ob_end_flush();

if (isset($_POST['insertNewBookBtn'])) {

    if (isset($_GET['authorID'])) {
        $authorID = $_GET['authorID'];
    } else {
        echo "Error: Author ID not found!";
        exit;
    }

    $query = insertBook($pdo, $_POST['title'], $_POST['genre'], 
              $_POST['price'], $authorID, $_POST['dateAdded']);

    if ($query) {
        header ("Location: ../viewbooks.php?authorID=" . $authorID);
    } else {
        echo "Insertion failed";
    }
}






if (isset($_POST['editBookBtn'])) {
	$query = updateBook($pdo, $_POST['title'], $_POST['genre'], 
	         $_POST['price'], $_GET['authorID'], $_POST['dateAdded'],
			 $_GET['bookID']);

	if ($query) {
		header("Location: ../viewbooks.php?authorID=" .$_GET['authorID']);
	}
	else {
		echo "Update failed";
	}

}




if (isset($_POST['deleteBookBtn'])) {
    if (isset($_GET['bookID']) && isset($_GET['authorID'])) {
        $bookID = $_GET['bookID'];
        $authorID = $_GET['authorID'];

        // Debugging
        echo "Attempting to delete Book ID: $bookID for Author ID: $authorID";

        // Proceed with deletion
        $query = deleteBook($pdo, $bookID);

        if ($query) {
            header("Location: ../viewbooks.php?authorID=" . $authorID);
        } else {
            echo "Deletion failed";
        }
    } else {
        echo "Book ID or Author ID not set.";
    }
}




?>