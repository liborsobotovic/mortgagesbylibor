<?php
require_once "pdo.php";
session_start();

header('Content-Type: application/json; charset=utf-8');
$sql = "SELECT borrower_id, first_name, last_name FROM borrower
  WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
  "user_id" => $_SESSION['id']
));
$rows = array();
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
  $rows[] = $row;
}

echo json_encode($rows, JSON_PRETTY_PRINT);
