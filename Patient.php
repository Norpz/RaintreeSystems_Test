<?php
require_once "PatientRecord.php";
require_once "Insurance.php";

class Patient implements PatientRecord {
    private $_id;
    private $pn;
    private $first;
    private $last;
    private $dob;
    private $insurances = [];

    public function __construct($pn) {
        $this->pn = $pn;
        // Fetch patient data from d and init properties
        $this->loadPatientData();
        // Fetch insurances
        $this->loadInsurances();
    }

    public function getId() {
        return $this->_id;
    }

    public function getPatientNumber() {
        return $this->pn;
    }

    public function getName() {
        return $this->first . " " . $this->last;
    }

    public function getInsurances() {
        return $this->insurances;
    }

    private function loadPatientData() {
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

        $statement = $pdo->prepare("SELECT * FROM patient WHERE pn = ?");
        $statement->execute([$this->pn]);
        $data = $statement->fetch();

        if ($data) {
            $this->_id = $data["_id"];
            $this->first = $data["first"];
            $this->last = $data["last"];
            $this->dob = $data["dob"];
        }
    }

    private function loadInsurances() {
        // Database configuration
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

        $statement = $pdo->prepare("SELECT _id FROM insurance WHERE patient_id = ?");
        $statement->execute([$this->_id]);

        while ($row = $statement->fetch()) {
            $this->insurances[] = new Insurance($row["_id"]);
        }
    }

    public function printInsuranceStatus($date) {
        foreach ($this->insurances as $insurance) {
            $isValid = $insurance->isValidOnDate($date) ? "Yes" : "No";
            printf(
                "%011d, %s, %s, %s\n",
                $this->pn,
                $this->getName(),
                $insurance->getInsuranceName(),
                $isValid
            );
        }
    }
}
?>
