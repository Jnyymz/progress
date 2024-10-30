<?php  

function insertNewUser($pdo, $username, $userPassword) {

	$checkUserSql = "SELECT * FROM users WHERE username = ?";
	$checkUserSqlStmt = $pdo->prepare($checkUserSql);
	$checkUserSqlStmt->execute([$username]);

	if ($checkUserSqlStmt->rowCount() == 0) {

		$sql = "INSERT INTO users (username,userPassword) VALUES(?,?)";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$username, $userPassword]);

		if ($executeQuery) {
			$_SESSION['message'] = "User successfully inserted";
			return true;
		}

		else {
			$_SESSION['message'] = "An error occured from the query";
		}

	}
	else {
		$_SESSION['message'] = "User already exists";
	}	
}

function loginUser($pdo, $username, $userPassword) {
	$sql = "SELECT * FROM users WHERE username=?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$username]); 

	if ($stmt->rowCount() == 1) {
		$userInfoRow = $stmt->fetch();
		$usernameFromDB = $userInfoRow['username']; 
		$passwordFromDB = $userInfoRow['userPassword'];

		if ($userPassword == $passwordFromDB) {
			$_SESSION['username'] = $usernameFromDB;
			$_SESSION['message'] = "Login successful!";
			return true;
		}

		else {
			$_SESSION['message'] = "Password is invalid, but user exists";
		}
	}

	
	if ($stmt->rowCount() == 0) {
		$_SESSION['message'] = "Username doesn't exist from the database. You may consider registration first";
	}

}

function getAllUsers($pdo) {
	$sql = "SELECT * FROM users";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}

}

function getUserByID($pdo, $userID) {
	$sql = "SELECT * FROM users WHERE userID = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$userID]);
	if ($executeQuery) {
		return $stmt->fetch();
	}
}

function insertAuthor($pdo, $firstname, $lastname, $nationality, 
	$contactInfo, $dateAdded) {

	$sql = "INSERT INTO authors (firstname, lastname, 
		nationality, contactInfo, dateAdded) VALUES(?,?,?,?,?)";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$firstname, $lastname, 
		$nationality, $contactInfo, $dateAdded]);

	if ($executeQuery) {
		return true;
	}
}

function getAllAuthors($pdo) {
	$sql = "SELECT * FROM authors";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getAuthorByID($pdo, $authorID) {
	$sql = "SELECT * FROM authors WHERE authorID = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$authorID]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}


function updateAuthor($pdo, $firstname, $lastname, 
	$nationality, $contactInfo, $dateAdded, $authorID) {

	$sql = "UPDATE authors
			SET firstname = ?,
				lastname = ?,
				nationality = ?, 
				contactInfo = ?,
                dateAdded = ?
			WHERE authorID = ?";
	
	$stmt = $pdo->prepare($sql);

	$executeQuery = $stmt->execute([$firstname, $lastname, 
		$nationality, $contactInfo, $dateAdded, $authorID]);
	
	if ($executeQuery) {
		return true;
	}

}

function deleteAuthor($pdo, $authorID) {
	$deleteAuthorBooks = "DELETE FROM books WHERE authorID = ?";
	$deleteStmt = $pdo->prepare($deleteAuthorBooks);
	$executeDeleteQuery = $deleteStmt->execute([$authorID]);

	if ($executeDeleteQuery) {
		$sql = "DELETE FROM authors WHERE authorID = ?";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$authorID]);

		if ($executeQuery) {
			return true;
		}

	}
	
}

//Edit tayo dito banda
function getAllInfoByAuthorID($pdo, $authorID) {
    $sql = "SELECT CONCAT(authors.firstname,' ',authors.lastname) AS book_Author
            FROM authors
            WHERE authorID = ?";
    
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$authorID]);
    
    if ($executeQuery) {
        $result = $stmt->fetch();
        if ($result) {
            return $result;
        } else {
            return false; // No record found
        }
    }

    return false; // Query execution failed
}
	

function getBooksByAuthor($pdo, $authorID) {
	
	$sql = "SELECT 
				books.bookID AS bookID,
				books.title AS title,
				books.genre AS genre,
				CONCAT(authors.firstname,' ',authors.lastname) AS Author,
				books.price AS price,
				books.dateAdded AS dateAdded
			FROM books
			JOIN authors ON books.authorID = authors.authorID
			WHERE books.authorID = ? 
			GROUP BY books.title;
			";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$authorID]);
	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}


function insertBook($pdo, $title, $genre, $price, $authorID, $dateAdded) {
    $sql = "INSERT INTO books (title, genre, price, authorID, dateAdded) 
            VALUES (?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$title, $genre, $price, $authorID, $dateAdded]);
    
    if ($executeQuery) {
        return true;
    } else {
        // Check for SQL errors
        $errorInfo = $stmt->errorInfo();
        echo "SQL error: " . $errorInfo[2];
        return false;
    }
}


function getBookByID($pdo, $bookID) {
    $sql = "SELECT 
                books.bookID AS bookID,
                books.title AS title,
                books.genre AS genre,
                books.price AS price,
                books.dateAdded AS dateAdded,
                CONCAT(authors.firstname, ' ', authors.lastname) AS book_Author
            FROM books
            JOIN authors ON books.authorID = authors.authorID
            WHERE books.bookID = ?";
    
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$bookID]);
    
    if ($executeQuery) {
        $result = $stmt->fetch();
        if ($result) {
            return $result;  // Return the result if found
        } else {
            return false;  // No result found
        }
    } else {
        // Log SQL errors
        $errorInfo = $stmt->errorInfo();
        echo "SQL error: " . $errorInfo[2];
        return false;
    }
}


function updateBook($pdo, $title, $genre, $price, $authorID, $dateAdded, $bookID) {
    $sql = "UPDATE books
            SET title = ?,
                genre = ?,
                price = ?,
                authorID = ?,
                dateAdded = ?
            WHERE bookID = ?;";
    
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$title, $genre, $price, $authorID, $dateAdded, $bookID]);

    if ($executeQuery) {
        return true;
    } else {
        // Log any SQL errors for debugging
        $errorInfo = $stmt->errorInfo();
        echo "SQL error: " . $errorInfo[2];
        return false;
    }
}


function deleteBook($pdo, $bookID) {
	$sql = "DELETE FROM books WHERE bookID = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$bookID]);
	if ($executeQuery) {
		return true;
	}
}


?> 