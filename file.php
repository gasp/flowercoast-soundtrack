<?php
require 'lib/pdo.php';
$conn = db();
$session = $_COOKIE['albumsess'];
$file = $_GET['file_id'];

function stream ($file, $name) {
    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$name.'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
  }
  else {
    echo 'Error: file not found';
  }
}

try {

    $stmt = $conn->prepare("SELECT count(*) as c FROM sessions WHERE sess=? AND created_at > NOW() - 86400");
    $stmt->execute(array($session));
    $accessGranted = $stmt->fetch(PDO::FETCH_ASSOC)['c'] > 0;
    unset($stmt);

    if (!$accessGranted) {
        header('Location: index.php?error=expired');
        exit();
    }

    // someone, one day, should use a database for that
    /// good luck motherfucker <3
    if ($file == 1) {
        stream('./files/ubatuba-mp3.zip', 'rodanton-ubatuba-mp3.zip');
    }
    if ($file == 2) {
        stream('./files/ubatuba-flac.zip', 'rodanton-ubatuba-flac.zip');
    }

}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>
