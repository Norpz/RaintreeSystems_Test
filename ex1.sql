-----Table "patient" creation:
CREATE TABLE patient (
    _id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    pn VARCHAR(11) DEFAULT NULL,
    first VARCHAR(15) DEFAULT NULL,
    last VARCHAR(25) DEFAULT NULL,
    dob DATE DEFAULT NULL,
    PRIMARY KEY (_id)
);

-----Table "insurance" creation:
CREATE TABLE insurance (
    _id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    patient_id INT(10) UNSIGNED NOT NULL,
    iname VARCHAR(40) DEFAULT NULL,
    from_date DATE DEFAULT NULL,
    to_date DATE DEFAULT NULL,
    PRIMARY KEY (_id),
    FOREIGN KEY (patient_id) REFERENCES patient(_id)
);

-----Populating "patient"
INSERT INTO patient(pn, first, last, dob)
VALUES ('50206291234', 'Peeter', 'Lepik', '2002-06-29'),
('50105223421', 'Toomas', 'Pärn', '2001-05-28'),
('50404273412', 'Mari', 'Murakas', '2004-04-27'),
('60303261234', 'Heli', 'Kopter', '2003-03-26'),
('50002251234', 'Olev', 'Ait', '2000-02-25');

-----Populating "insurance"
INSERT INTO insurance (patient_id, iname, from_date, to_date)
VALUES (1, 'Medicare', '09-01-01', '10-01-01'),
(1, 'Blue Shield', '15-01-02', '16-01-02' ),
(2, 'Medicaid', '08-01-02', '09-01-02' ),
(2, 'Blue Shield', '15-01-02', '16-01-02' ),
(3, 'Hea Kindlustus OÜ', '20-06-01', '21-06-01' ),
(3, 'Medicare', '12-01-01', '13-01-01' ),
(4, 'Veel Parem Kindlustus OÜ', '23-01-01', '24-01-01' ),
(4, 'Medicaid', '08-01-02', '09-01-02' ),
(5, 'Keskmine Kindlustus OÜ', '22-01-02', '23-01-02' ),
(5, 'Blue Cross', '17-01-01', '18-01-01' ),
(1, 'Blue Cross', '24-01-01', '25-01-01');
