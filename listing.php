<?php
require 'lib/pdo.php';
$conn = db();
$session = $_COOKIE['albumsess'];

try {

    $stmt = $conn->prepare("SELECT count(*) as c FROM sessions WHERE sess=? AND created_at > NOW() - 86400");
    $stmt->execute(array($session));
    $accessGranted = $stmt->fetch(PDO::FETCH_ASSOC)['c'] > 0;
    unset($stmt);

    if (!$accessGranted) {
        header('Location: index.php?error=expired');
        exit();
    }
    include 'templates/listing.tpl.html';
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>
