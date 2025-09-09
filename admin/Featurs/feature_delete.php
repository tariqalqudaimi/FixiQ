<?php
require_once "../UserSetting/user_auth.php";
require_once '../Database/db.php';
if(isset($_GET['id'])) {
    $feature_id = intval($_GET['id']); 

    $stmt = $dbcon->prepare("DELETE FROM features WHERE id = ?");
    $stmt->bind_param("i", $feature_id);
    $stmt->execute();
    $stmt->close();
}

header('Location: manage_features.php');
exit();
?>