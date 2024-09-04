<?php
function getTotalUsers($conn)
{
    $sql = "SELECT COUNT(*) as total_users FROM tb_users";
    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['total_users'];
    } else {
        return 0;
    }
}
?>