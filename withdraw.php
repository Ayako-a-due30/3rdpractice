<?php
session_start();

$dbh = dbConnect();
$sql = ('UPDATE users SET delete_flg=1 WHERE id = :u_id')
$stmt = execute(array(':u_id'=>$SESSION['user_id']));
queryPost($dbh,$sql,$data);

?>
