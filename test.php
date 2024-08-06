<?php
require_once 'Patient.php';
require_once 'Insurance.php';

// Database conf
$host = "localhost";
$db = "raintree";
$user = "root";
$password = "";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$statement = $pdo->query("SELECT pn FROM patient ORDER BY pn ASC");
$patientNumbers = $statement->fetchAll(PDO::FETCH_COLUMN);

$currentDate = date('m-d-y');

foreach ($patientNumbers as $pn) {
    $patient = new Patient($pn);
    $patient->printInsuranceStatus($currentDate);
}
?>
