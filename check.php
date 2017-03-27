<?php
require 'lib/pdo.php';
$conn = db();
$submittedKey = $_POST['key'];


function generateRandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

try {
    //
    $stmt = $conn->prepare("SELECT count(*) as c FROM codes WHERE LOWER(accesskey)=LOWER(?) AND inc < 50");
    $stmt->execute(array($submittedKey));
    $accessGranted = $stmt->fetch(PDO::FETCH_ASSOC)['c'] > 0;
    unset($stmt);

    if ($accessGranted) {
        // get album id
        $stmt = $conn->prepare("SELECT id, album_id, inc FROM codes WHERE LOWER(accesskey)=LOWER(?)");
        $stmt->execute(array($submittedKey));
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $album_id = $res['album_id'];
        $code_id = $res['id'];
        $code_inc = $res['inc'];
        unset($res, $stmt);

        // increment the number of connections
        $stmt = $conn->prepare("UPDATE codes SET inc=? WHERE id=?");
        $stmt->execute(array($code_inc + 1,$code_id));
        unset($stmt);

        // grant access by cookie
        $randomString = generateRandomString(16);
        $stmt = $conn->prepare("INSERT INTO `sessions` (`id`, `code_id`, `sess`, `created_at`) VALUES (NULL, ?, ?, CURRENT_TIMESTAMP)");
        $stmt->execute(array($code_id, $randomString));
        setcookie('albumsess', $randomString, time() + 86400);
        unset($stmt);

        header('Location: listing.php');
    }
    else {
        $stmt = $conn->prepare("SELECT id, inc FROM codes WHERE accesskey=?");
        $stmt->execute(array($submittedKey));
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if($res == false) {
            header('Location: index.php?error=invalid');
            exit();
        }
        else {
            header('Location: index.php?error=many');
            exit();
        }
        unset($stmt, $res);
    }
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
