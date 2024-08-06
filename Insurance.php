<?php
require_once "PatientRecord.php";

class Insurance implements PatientRecord {
    private $_id;
    private $patient_id;
    private $iname;
    private $from_date;
    private $to_date;

    public function __construct($id) {
        $this->_id = $id;
        // Fetch insurance data and init properties
        $this->loadInsuranceData();
    }

    public function getId() {
        return $this->_id;
    }

    public function getPatientNumber() {
        return $this->patient_id;
    }

    public function getInsuranceName() {
        return $this->iname;
    }

    private function loadInsuranceData() {
        // Database conf
        $host = "localhost";
        $db = "raintree";
        $user = "root";
        $pass = "";
        $charset = "utf8mb4";

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }

        $statement = $pdo->prepare("SELECT * FROM insurance WHERE _id = ?");
        $statement->execute([$this->_id]);
        $data = $statement->fetch();

        if ($data) {
            $this->patient_id = $data["patient_id"];
            $this->iname = $data["iname"];
            $this->from_date = $data["from_date"];
            $this->to_date = $data["to_date"];
        }
    }

    public function isValidOnDate($date) { //Check if the given date is in our date range
        $compare_date = DateTime::createFromFormat("m-d-y", $date);
        $from_date = new DateTime($this->from_date);
        $to_date = $this->to_date ? new DateTime($this->to_date) : null;

        if ($to_date) {
            return $compare_date >= $from_date && $compare_date <= $to_date;
        } else {
            return $compare_date >= $from_date;
        }
    }
}
?>
