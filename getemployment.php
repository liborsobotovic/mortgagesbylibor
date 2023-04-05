<?php
require_once "pdo.php";
session_start();

header('Content-Type: application/json; charset=utf-8');
$sql = "SELECT * FROM employment  WHERE borrower_id = :borrower_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
  "borrower_id" => $_SESSION['borrower_id']
));
unset($_SESSION['borrower_id']);
$rows = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $rows[] = $row;
}

echo json_encode($rows, JSON_PRETTY_PRINT);
