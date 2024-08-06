<?php 
//Database conf
$host = "localhost";
$db = "raintree";
$user = "root";
$password = "";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  //Exception instead of failure
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //Keys of the returned array are column names
];

try {
    $pdo = new PDO($dsn, $user, $password, $options); //PHP data objects
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$sql = "
    SELECT p.pn, p.last, p.first, i.iname,  DATE_FORMAT(i.from_date, '%m-%d-%y') AS from_date, DATE_FORMAT(i.to_date, '%m-%d-%y') AS to_date
    FROM patient p
    JOIN insurance i ON p._id = i.patient_id
    ORDER BY i.from_date ASC, p.last ASC";

$statement = $pdo->query($sql);

while ($row = $statement->fetch()) {
    printf(
        "%011d, %s, %s, %s, %s, %s\n",
        $row['pn'],
        $row['last'],
        $row['first'],
        $row['iname'],
        $row['from_date'],
        $row['to_date']
    );
}

//------Second part of the ex2 - PHP scripting-------------
// SQL query to get first and last names from Patient
$sql = "
    SELECT first, last FROM patient
    ";

$statement = $pdo->query($sql);

$occuredLetters = []; //Array that contains occured letters and a occurance coutner for each letter
$letterCounter = 0; //Counts processed letters

while ($row = $statement->fetch()) {
    $fullName = $row['first'] . $row['last'];
    $fullName = strtoupper(preg_replace("/[^A-Za-z]/", '', $fullName)); // Remove non-alphabetic characters and convert to uppercase

    $letters = str_split($fullName);
    foreach ($letters as $letter) {
        if (!isset($occuredLetters[$letter])) {
            $occuredLetters[$letter] = 0;
        }
        $occuredLetters[$letter]++;
        $letterCounter++;
    }
}

ksort($occuredLetters); // Sort alphabetically by letter

foreach ($occuredLetters as $letter => $count) {
    $percentage = ($count / $letterCounter) * 100;
    printf(
        "%s\t%d\t%.2f%%\n",
        $letter,
        $count,
        $percentage
    );
}
?>