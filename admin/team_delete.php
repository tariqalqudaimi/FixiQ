<?php
require_once "user_auth.php"; 
require_once "db.php";

if(isset($_GET['id'])) {
    $member_id = intval($_GET['id']); 

    $stmt_select = $dbcon->prepare("SELECT image_file FROM team_members WHERE id = ?");
    $stmt_select->bind_param("i", $member_id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    if($row = $result->fetch_assoc()) {
        $image_to_delete = $row['image_file'];
        $file_path = "../assets/img/team/" . $image_to_delete;
        if(file_exists($file_path) && $image_to_delete !== 'default_avatar.png') {
            unlink($file_path); 
        }
    }
    $stmt_select->close();

    $stmt_delete = $dbcon->prepare("DELETE FROM team_members WHERE id = ?");
    $stmt_delete->bind_param("i", $member_id);
    $stmt_delete->execute();
    $stmt_delete->close();
}

header('Location: manage_team.php');
exit();
?>