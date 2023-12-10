# ZappRental
 DBMS Project Fall 2023

## Code to Set-up Database in Mysql

Mysql for setting up:


First create schema name it adg_car_rental.code:

```SQL
USE adg_car_rental;
CREATE TABLE adg_card_details (
    cardid     INT NOT NULL,
    cardtype   VARCHAR(10) NOT NULL,
    cardnumber INT NOT NULL,
    card_fname VARCHAR(10) NOT NULL,
    card_lname VARCHAR(10) NOT NULL,
    cardexpiry DATE NOT NULL,
    card_cvv   INT NOT NULL,
    PRIMARY KEY (cardid)
);

ALTER TABLE adg_card_details 
    MODIFY cardid INT COMMENT 'Card ID',
    MODIFY cardtype VARCHAR(10) COMMENT 'Card Type',
    MODIFY cardnumber INT COMMENT 'Card Number',
    MODIFY card_fname VARCHAR(10) COMMENT 'Card First Name',
    MODIFY card_lname VARCHAR(10) COMMENT 'Card Last Name',
    MODIFY cardexpiry DATE COMMENT 'Card Expiration Date',
    MODIFY card_cvv INT COMMENT 'Card CVV';
    
CREATE TABLE adg_corporate (
    custid              INT NOT NULL,
    corporation_name    VARCHAR(20) NOT NULL,
    registration_number VARCHAR(20) NOT NULL,
    corporate_discount  VARCHAR(10),
    PRIMARY KEY (custid)
);

CREATE TABLE adg_customer (
    custid      INT NOT NULL,
    emailid     VARCHAR(30) NOT NULL,
    phonenumber INT NOT NULL,
    cust_fname  VARCHAR(20) NOT NULL,
    cust_lname  VARCHAR(30) NOT NULL,
    custcity    VARCHAR(20) NOT NULL,
    custstate   VARCHAR(20) NOT NULL,
    custst      VARCHAR(20) NOT NULL,
    custzipcode INT NOT NULL,
    custcountry VARCHAR(20) NOT NULL,
    cust_type   VARCHAR(1) NOT NULL CHECK (cust_type IN ('C', 'I')),
    PRIMARY KEY (custid)
);

CREATE TABLE adg_discountcoupon (
    couponid           INT NOT NULL,
    discountpercentage INT NOT NULL CHECK (discountpercentage BETWEEN 0 AND 100),
    valid_from         DATE NOT NULL,
    valid_to           DATE NOT NULL,
    custid             INT,
    PRIMARY KEY (couponid)
);

CREATE TABLE adg_individual (
    custid                  INT NOT NULL,
    fname                   VARCHAR(20) NOT NULL,
    lname                   VARCHAR(20) NOT NULL,
    driver_license_number   VARCHAR(20) NOT NULL,
    insurance_company_name  CHAR(20) NOT NULL,
    insurance_policy_number VARCHAR(20) NOT NULL,
    individual_discount     INT,
    PRIMARY KEY (custid)
);

CREATE TABLE adg_invoice (
    invoiceid   INT NOT NULL,
    invoicedate DATE NOT NULL,
    rentalid    INT NOT NULL,
    PRIMARY KEY (invoiceid)
);

CREATE TABLE adg_office (
    office_id     INT NOT NULL,
    phonenumber   INT NOT NULL,
    officest      VARCHAR(20) NOT NULL,
    officecity    VARCHAR(20) NOT NULL,
    officestate   VARCHAR(20) NOT NULL,
    officecountry VARCHAR(20) NOT NULL,
    officezipcode VARCHAR(20) NOT NULL,
    emailid       VARCHAR(30),
    PRIMARY KEY (office_id)
);

CREATE TABLE adg_payment (
    paymentid     INT NOT NULL,
    paymentdate   DATE NOT NULL,
    paymentmethod VARCHAR(10) NOT NULL CHECK (paymentmethod IN ('credit', 'debit', 'gift')),
    invoiceid     INT NOT NULL,
    cardid        INT NOT NULL,
    PRIMARY KEY (paymentid)
);

CREATE TABLE adg_rentalservice (
    rentalid             INT NOT NULL,
    pickup_date          DATE NOT NULL,
    dropoff_date         DATE NOT NULL,
    start_odometer       INT NOT NULL,
    end_odometer         INT,
    daily_odometer_limit INT,
    pickup_location      VARCHAR(20) NOT NULL,
    dropoff_location     VARCHAR(20) NOT NULL,
    custid               INT NOT NULL,
    vin                  VARCHAR(10) NOT NULL,
    PRIMARY KEY (rentalid)
);

CREATE TABLE adg_vehicle (
    vin          VARCHAR(10) NOT NULL,
    vehiclemake  VARCHAR(10) NOT NULL,
    vehiclemodel VARCHAR(10) NOT NULL,
    vehicleyear  INT NOT NULL,
    licenseplate VARCHAR(10) NOT NULL,
    office_id    INT NOT NULL,
    statusid     INT NOT NULL,
    classid      INT NOT NULL,
    PRIMARY KEY (vin)
);

CREATE TABLE adg_vehicleclass (
    classname        VARCHAR(30) NOT NULL,
    classid          INT NOT NULL,
    daily_rate       INT NOT NULL,
    over_mileagefees INT NOT NULL CHECK (classname IN ('Mini Van', 'Premium SUV', 'SUV', 'Station Wagon', 'luxury car', 'mid-size car', 'small car')),
    PRIMARY KEY (classid)
);

CREATE TABLE adg_vehiclestatus (
    statusid INT NOT NULL,
    status   VARCHAR(10) NOT NULL CHECK (status IN ('Available', 'Rented', 'Under Maintenance')),
    PRIMARY KEY (statusid)
);

ALTER TABLE adg_payment ADD CONSTRAINT card_details_fk FOREIGN KEY (cardid) REFERENCES adg_card_details (cardid);
ALTER TABLE adg_discountcoupon ADD CONSTRAINT customer_fk FOREIGN KEY (custid) REFERENCES adg_customer (custid);
ALTER TABLE adg_individual ADD CONSTRAINT customer_fkv1 FOREIGN KEY (custid) REFERENCES adg_customer (custid);
ALTER TABLE adg_rentalservice ADD CONSTRAINT customer_fkv2 FOREIGN KEY (custid) REFERENCES adg_customer (custid);
ALTER TABLE adg_corporate ADD CONSTRAINT customer_fkv3 FOREIGN KEY (custid) REFERENCES adg_customer (custid);
ALTER TABLE adg_payment ADD CONSTRAINT invoice_fk FOREIGN KEY (invoiceid) REFERENCES adg_invoice (invoiceid);
ALTER TABLE adg_vehicle ADD CONSTRAINT office_fk FOREIGN KEY (office_id) REFERENCES adg_office (office_id);
ALTER TABLE adg_invoice ADD CONSTRAINT rentalservice_fk FOREIGN KEY (rentalid) REFERENCES adg_rentalservice (rentalid);
ALTER TABLE adg_rentalservice ADD CONSTRAINT vehicle_fk FOREIGN KEY (vin) REFERENCES adg_vehicle (vin);
ALTER TABLE adg_vehicle ADD CONSTRAINT vehicleclass_fk FOREIGN KEY (classid) REFERENCES adg_vehicleclass (classid);
ALTER TABLE adg_vehicle ADD CONSTRAINT vehiclestatus_fk FOREIGN KEY (statusid) REFERENCES adg_vehiclestatus (statusid);

ALTER TABLE adg_corporate DROP FOREIGN KEY customer_fkv3;
ALTER TABLE adg_discountcoupon DROP FOREIGN KEY customer_fk;
ALTER TABLE adg_individual DROP FOREIGN KEY customer_fkv1;
ALTER TABLE adg_rentalservice DROP FOREIGN KEY customer_fkv2;

ALTER TABLE adg_customer MODIFY custid INTEGER AUTO_INCREMENT;

ALTER TABLE adg_corporate ADD CONSTRAINT customer_fkv3 FOREIGN KEY (custid) REFERENCES adg_customer(custid);
ALTER TABLE adg_discountcoupon ADD CONSTRAINT customer_fk FOREIGN KEY (custid) REFERENCES adg_customer(custid);
ALTER TABLE adg_individual ADD CONSTRAINT customer_fkv1 FOREIGN KEY (custid) REFERENCES adg_customer(custid);
ALTER TABLE adg_rentalservice ADD CONSTRAINT customer_fkv2 FOREIGN KEY (custid) REFERENCES adg_customer(custid);

ALTER TABLE adg_customer MODIFY phonenumber VARCHAR(20);

SELECT 
    CONSTRAINT_NAME, 
    CHECK_CLAUSE 
FROM 
    information_schema.CHECK_CONSTRAINTS 
WHERE 
    TABLE_SCHEMA = 'adg_car_rental' -- Replace with your database name
    AND TABLE_NAME = 'adg_customer' 
    AND CONSTRAINT_SCHEMA = 'adg_car_rental'; -- Replace with your database name

ALTER TABLE adg_office MODIFY phonenumber VARCHAR(20);

INSERT INTO ADG_Office (Office_ID, PhoneNumber, OfficeSt, OfficeCity, OfficeState, OfficeCountry, OfficeZipcode, EmailID) 
VALUES
(1, '3105550145', '456 Downtown St', 'Los Angeles', 'California', 'US', '90015', 'la_office@wowrentals.com'),
(2, '4155550189', '123 Airport Rd', 'San Francisco', 'California', 'US', '94128', 'sf_office@wowrentals.com'),
(3, '2065550123', '789 Market St', 'Seattle', 'Washington', 'US', '98101', 'seattle_office@wowrentals.com'),
(4, '3125550145', '321 Windy Blvd', 'Chicago', 'Illinois', 'US', '60606', 'chicago_office@wowrentals.com'),
(5, '3055550198', '654 Ocean Dr', 'Miami', 'Florida', 'US', '33139', 'miami_office@wowrentals.com'),
(6, '2125550159', '567 Broadway Ave', 'New York', 'New York', 'US', '10012', 'ny_office@wowrentals.com'),
(7, '7025550177', '432 Strip Ln', 'Las Vegas', 'Nevada', 'US', '89109', 'vegas_office@wowrentals.com'),
(8, '6025550134', '321 Desert Rd', 'Phoenix', 'Arizona', 'US', '85004', 'phoenix_office@wowrentals.com'),
(9, '6175550111', '246 Freedom Trl', 'Boston', 'Massachusetts', 'US', '02114', 'boston_office@wowrentals.com'),
(10, '3035550166', '852 Mile High Dr', 'Denver', 'Colorado', 'US', '80204', 'denver_office@wowrentals.com'),
(11, '2145550192', '975 Big Tex Blvd', 'Dallas', 'Texas', 'US', '75201', 'dallas_office@wowrentals.com');

SELECT * FROM ADG_Office LIMIT 5;

INSERT INTO ADG_VehicleStatus VALUES (1, 'Available');
Insert into ADG_VehicleStatus VALUES (2,'Rented');
Insert into ADG_VehicleStatus VALUES (3,'Available');
INSERT INTO ADG_VehicleStatus VALUES (4, 'Available');
INSERT INTO ADG_VehicleStatus VALUES (5, 'Rented');
INSERT INTO ADG_VehicleStatus VALUES (6, 'Available');
INSERT INTO ADG_VehicleStatus VALUES (7, 'Rented');
INSERT INTO ADG_VehicleStatus VALUES (8, 'Available');
INSERT INTO ADG_VehicleStatus VALUES (9, 'Rented');
INSERT INTO ADG_VehicleStatus VALUES (10, 'Available');
INSERT INTO ADG_VehicleStatus VALUES (11, 'Rented');

INSERT INTO ADG_VehicleClass (ClassName, ClassID, Daily_Rate, Over_MileageFees) VALUES ('luxury car', 12, 80, 30);
INSERT INTO ADG_VehicleClass (ClassName, ClassID, Daily_Rate, Over_MileageFees) VALUES ('mid-size car', 13, 45, 18);
INSERT INTO ADG_VehicleClass (ClassName, ClassID, Daily_Rate, Over_MileageFees) VALUES ('Mini Van', 14, 60, 22);
INSERT INTO ADG_VehicleClass (ClassName, ClassID, Daily_Rate, Over_MileageFees) VALUES ('Premium SUV', 15, 85, 33);
INSERT INTO ADG_VehicleClass (ClassName, ClassID, Daily_Rate, Over_MileageFees) VALUES ('SUV', 16, 55, 25);
INSERT INTO ADG_VehicleClass (ClassName, ClassID, Daily_Rate, Over_MileageFees) VALUES ('Station Wagon', 17, 50, 20);
INSERT INTO ADG_VehicleClass (ClassName, ClassID, Daily_Rate, Over_MileageFees) VALUES ('small car', 18, 35, 15);
INSERT INTO ADG_VehicleClass (ClassName, ClassID, Daily_Rate, Over_MileageFees) VALUES ('luxury car', 19, 90, 35);
INSERT INTO ADG_VehicleClass (ClassName, ClassID, Daily_Rate, Over_MileageFees) VALUES ('mid-size car', 20, 47, 19);
INSERT INTO ADG_VehicleClass (ClassName, ClassID, Daily_Rate, Over_MileageFees) VALUES ('Mini Van', 21, 65, 23);

INSERT INTO ADG_Vehicle (VIN, VehicleMake, VehicleModel, VehicleYear, LicensePlate, Office_ID, StatusID, ClassID, ImageFileName) VALUES ('VIN1234567', 'Toyota', 'Camry', 2019, 'LIC123', 1, 1, 13, '1.png');
INSERT INTO ADG_Vehicle (VIN, VehicleMake, VehicleModel, VehicleYear, LicensePlate, Office_ID, StatusID, ClassID, ImageFileName) VALUES ('VIN2345678', 'Honda', 'Civic', 2018, 'LIC234', 2, 2, 14, '2.png');
INSERT INTO ADG_Vehicle (VIN, VehicleMake, VehicleModel, VehicleYear, LicensePlate, Office_ID, StatusID, ClassID, ImageFileName) VALUES ('VIN3456789', 'Ford', 'Fusion', 2020, 'LIC345', 3, 3, 15, '3.png');
INSERT INTO ADG_Vehicle (VIN, VehicleMake, VehicleModel, VehicleYear, LicensePlate, Office_ID, StatusID, ClassID, ImageFileName) VALUES ('VIN4567890', 'Chevy', 'Impala', 2019, 'LIC456', 4, 4, 16, '4.png');
INSERT INTO ADG_Vehicle (VIN, VehicleMake, VehicleModel, VehicleYear, LicensePlate, Office_ID, StatusID, ClassID, ImageFileName) VALUES ('VIN5678901', 'BMW', 'X3', 2021, 'LIC567', 5, 5, 12, '5.png');
INSERT INTO ADG_Vehicle (VIN, VehicleMake, VehicleModel, VehicleYear, LicensePlate, Office_ID, StatusID, ClassID, ImageFileName) VALUES ('VIN6789012', 'Audi', 'A4', 2018, 'LIC678', 6, 6, 19, '6.png');
INSERT INTO ADG_Vehicle (VIN, VehicleMake, VehicleModel, VehicleYear, LicensePlate, Office_ID, StatusID, ClassID, ImageFileName) VALUES ('VIN7890123', 'Tesla', 'Model S', 2022, 'LIC789', 7, 7, 12, '7.png');
INSERT INTO ADG_Vehicle (VIN, VehicleMake, VehicleModel, VehicleYear, LicensePlate, Office_ID, StatusID, ClassID, ImageFileName) VALUES ('VIN8901234', 'Kia', 'Sorento', 2020, 'LIC890', 8, 8, 17, '8.png');
INSERT INTO ADG_Vehicle (VIN, VehicleMake, VehicleModel, VehicleYear, LicensePlate, Office_ID, StatusID, ClassID, ImageFileName) VALUES ('VIN9012345', 'Nissan', 'Altima', 2019, 'LIC901', 9, 9, 18, '9.png');
INSERT INTO ADG_Vehicle (VIN, VehicleMake, VehicleModel, VehicleYear, LicensePlate, Office_ID, StatusID, ClassID, ImageFileName) VALUES ('VIN0123456', 'Hyundai', 'Tucson', 2021, 'LIC012', 10, 10, 20, '10.png');
INSERT INTO ADG_Vehicle (VIN, VehicleMake, VehicleModel, VehicleYear, LicensePlate, Office_ID, StatusID, ClassID, ImageFileName) VALUES ('VIN1123456', 'Mercedes', 'C-Class', 2021, 'LIC111', 11, 1, 19, '11.png');
INSERT INTO ADG_Vehicle (VIN, VehicleMake, VehicleModel, VehicleYear, LicensePlate, Office_ID, StatusID, ClassID, ImageFileName) VALUES ('VIN2234567', 'Volkswagen', 'Golf', 2020, 'LIC222', 1, 2, 18, '12.png');
INSERT INTO ADG_Vehicle (VIN, VehicleMake, VehicleModel, VehicleYear, LicensePlate, Office_ID, StatusID, ClassID, ImageFileName) VALUES ('VIN3345678', 'Subaru', 'Outback', 2022, 'LIC333', 2, 3, 17, '13.png');
INSERT INTO ADG_Vehicle (VIN, VehicleMake, VehicleModel, VehicleYear, LicensePlate, Office_ID, StatusID, ClassID, ImageFileName) VALUES ('VIN4456789', 'Mazda', 'CX-5', 2021, 'LIC444', 3, 4, 20, '14.png');
INSERT INTO ADG_Vehicle (VIN, VehicleMake, VehicleModel, VehicleYear, LicensePlate, Office_ID, StatusID, ClassID, ImageFileName) VALUES ('VIN5567890', 'Lexus', 'RX', 2022, 'LIC555', 4, 5, 12, '15.png');
INSERT INTO ADG_Vehicle (VIN, VehicleMake, VehicleModel, VehicleYear, LicensePlate, Office_ID, StatusID, ClassID, ImageFileName) VALUES ('VIN6678901', 'Jeep', 'Wrangler', 2019, 'LIC666', 5, 6, 15, '16.png');
INSERT INTO ADG_Vehicle (VIN, VehicleMake, VehicleModel, VehicleYear, LicensePlate, Office_ID, StatusID, ClassID, ImageFileName) VALUES ('VIN7789012', 'Dodge', 'Charger', 2020, 'LIC777', 6, 7, 16, '17.png');
INSERT INTO ADG_Vehicle (VIN, VehicleMake, VehicleModel, VehicleYear, LicensePlate, Office_ID, StatusID, ClassID, ImageFileName) VALUES ('VIN8890123', 'Chrysler', '300', 2019, 'LIC888', 7, 8, 13, '18.png');
INSERT INTO ADG_Vehicle (VIN, VehicleMake, VehicleModel, VehicleYear, LicensePlate, Office_ID, StatusID, ClassID, ImageFileName) VALUES ('VIN9901234', 'GMC', 'Acadia', 2021, 'LIC999', 8, 9, 14, '19.png');
INSERT INTO ADG_Vehicle (VIN, VehicleMake, VehicleModel, VehicleYear, LicensePlate, Office_ID, StatusID, ClassID, ImageFileName) VALUES ('VIN0012345', 'Cadillac', 'Escalade', 2022, 'LIC000', 9, 10, 21, '20.png');


ALTER TABLE ADG_Vehicle ADD ImageFileName VARCHAR(255);



INSERT INTO ADG_Customer (emailid, phonenumber, cust_fname, cust_lname, custcity, custstate, custst, custzipcode, custcountry, cust_type, username, password) VALUES 
('email1@example.com', '1234567890', 'John', 'Doe', 'City1', 'State1', 'Street1', 10001, 'Country1', 'C', 'john1', '$2y$10$CmkLKbOR9F8uSgNw634Fe.4NdfO8fEcHUt02.5MDAYZ3cSkd6F/Nm'),
('email2@example.com', '1234567891', 'Jane', 'Smith', 'City2', 'State2', 'Street2', 10002, 'Country2', 'I', 'jane2', '$2y$10$CmkLKbOR9F8uSgNw634Fe.4NdfO8fEcHUt02.5MDAYZ3cSkd6F/Nm'),
('email3@example.com', '1234567892', 'Jim', 'Beam', 'City3', 'State3', 'Street3', 10003, 'Country3', 'C', 'jim3', '$2y$10$CmkLKbOR9F8uSgNw634Fe.4NdfO8fEcHUt02.5MDAYZ3cSkd6F/Nm'),
('email4@example.com', '1234567893', 'Jack', 'Daniels', 'City4', 'State4', 'Street4', 10004, 'Country4', 'I', 'jack4', '$2y$10$CmkLKbOR9F8uSgNw634Fe.4NdfO8fEcHUt02.5MDAYZ3cSkd6F/Nm'),
('email5@example.com', '1234567894', 'Jessica', 'Rabbit', 'City5', 'State5', 'Street5', 10005, 'Country5', 'C', 'jessica5', '$2y$10$CmkLKbOR9F8uSgNw634Fe.4NdfO8fEcHUt02.5MDAYZ3cSkd6F/Nm'),
('email6@example.com', '1234567895', 'Jake', 'Long', 'City6', 'State6', 'Street6', 10006, 'Country6', 'I', 'jake6', '$2y$10$CmkLKbOR9F8uSgNw634Fe.4NdfO8fEcHUt02.5MDAYZ3cSkd6F/Nm'),
('email7@example.com', '1234567896', 'Jasmine', 'Flower', 'City7', 'State7', 'Street7', 10007, 'Country7', 'C', 'jasmine7', '$2y$10$CmkLKbOR9F8uSgNw634Fe.4NdfO8fEcHUt02.5MDAYZ3cSkd6F/Nm'),
('email8@example.com', '1234567897', 'Jeremy', 'Fisher', 'City8', 'State8', 'Street8', 10008, 'Country8', 'I', 'jeremy8', '$2y$10$CmkLKbOR9F8uSgNw634Fe.4NdfO8fEcHUt02.5MDAYZ3cSkd6F/Nm'),
('email9@example.com', '1234567898', 'Julia', 'Roberts', 'City9', 'State9', 'Street9', 10009, 'Country9', 'C', 'julia9', '$2y$10$CmkLKbOR9F8uSgNw634Fe.4NdfO8fEcHUt02.5MDAYZ3cSkd6F/Nm'),
('email10@example.com', '1234567899', 'Jason', 'Momoa', 'City10', 'State10', 'Street10', 10010, 'Country10', 'I', 'jason10', '$2y$10$CmkLKbOR9F8uSgNw634Fe.4NdfO8fEcHUt02.5MDAYZ3cSkd6F/Nm');

INSERT INTO ADG_Customer (emailid, phonenumber, cust_fname, cust_lname, custcity, custstate, custst, custzipcode, custcountry, cust_type, username, password) VALUES 
('email21@example.com', '1234567810', 'Anna', 'Bell', 'City21', 'State21', 'Street21', 10021, 'Country21', 'C', 'anna.bell', '$2y$10$CmkLKbOR9F8uSgNw634Fe.4NdfO8fEcHUt02.5MDAYZ3cSkd6F/Nm'),
('email22@example.com', '1234567811', 'Brian', 'Adams', 'City22', 'State22', 'Street22', 10022, 'Country22', 'I', 'brian.adams', '$2y$10$CmkLKbOR9F8uSgNw634Fe.4NdfO8fEcHUt02.5MDAYZ3cSkd6F/Nm'),
('email23@example.com', '1234567812', 'Carla', 'Moss', 'City23', 'State23', 'Street23', 10023, 'Country23', 'C', 'carla.moss', '$2y$10$CmkLKbOR9F8uSgNw634Fe.4NdfO8fEcHUt02.5MDAYZ3cSkd6F/Nm'),
('email24@example.com', '1234567813', 'David', 'Tennant', 'City24', 'State24', 'Street24', 10024, 'Country24', 'I', 'david.tennant', '$2y$10$CmkLKbOR9F8uSgNw634Fe.4NdfO8fEcHUt02.5MDAYZ3cSkd6F/Nm'),
('email25@example.com', '1234567814', 'Evelyn', 'Woods', 'City25', 'State25', 'Street25', 10025, 'Country25', 'C', 'evelyn.woods', '$2y$10$CmkLKbOR9F8uSgNw634Fe.4NdfO8fEcHUt02.5MDAYZ3cSkd6F/Nm'),
('email26@example.com', '1234567815', 'Frank', 'Ocean', 'City26', 'State26', 'Street26', 10026, 'Country26', 'I', 'frank.ocean', '$2y$10$CmkLKbOR9F8uSgNw634Fe.4NdfO8fEcHUt02.5MDAYZ3cSkd6F/Nm'),
('email27@example.com', '1234567816', 'Gina', 'Hall', 'City27', 'State27', 'Street27', 10027, 'Country27', 'C', 'gina.hall', '$2y$10$CmkLKbOR9F8uSgNw634Fe.4NdfO8fEcHUt02.5MDAYZ3cSkd6F/Nm'),
('email28@example.com', '1234567817', 'Harry', 'Potter', 'City28', 'State28', 'Street28', 10028, 'Country28', 'I', 'harry.potter', '$2y$10$CmkLKbOR9F8uSgNw634Fe.4NdfO8fEcHUt02.5MDAYZ3cSkd6F/Nm'),
('email29@example.com', '1234567818', 'Irene', 'Adler', 'City29', 'State29', 'Street29', 10029, 'Country29', 'C', 'irene.adler', '$2y$10$CmkLKbOR9F8uSgNw634Fe.4NdfO8fEcHUt02.5MDAYZ3cSkd6F/Nm'),
('email30@example.com', '1234567819', 'John', 'Watson', 'City30', 'State30', 'Street30', 10030, 'Country30', 'I', 'john.watson', '$2y$10$CmkLKbOR9F8uSgNw634Fe.4NdfO8fEcHUt02.5MDAYZ3cSkd6F/Nm');

INSERT INTO ADG_Individual VALUES (2, 'FName1', 'LName1', 'DL001', 'InsCo1', 'Pol001', 10);
INSERT INTO ADG_Individual VALUES (4, 'FName2', 'LName2', 'DL002', 'InsCo2', 'Pol002', 15);
INSERT INTO ADG_Individual VALUES (6, 'FName3', 'LName3', 'DL003', 'InsCo3', 'Pol003', 12);
INSERT INTO ADG_Individual VALUES (8, 'FName4', 'LName4', 'DL004', 'InsCo4', 'Pol004', 20);
INSERT INTO ADG_Individual VALUES (10, 'FName5', 'LName5', 'DL005', 'InsCo5', 'Pol005', 25);
INSERT INTO ADG_Individual VALUES (12, 'FName6', 'LName6', 'DL006', 'InsCo6', 'Pol006', 18);
INSERT INTO ADG_Individual VALUES (14, 'FName7', 'LName7', 'DL007', 'InsCo7', 'Pol007', 10);
INSERT INTO ADG_Individual VALUES (16, 'FName8', 'LName8', 'DL008', 'InsCo8', 'Pol008', 22);
INSERT INTO ADG_Individual VALUES (18, 'FName9', 'LName9', 'DL009', 'InsCo9', 'Pol009', 14);
INSERT INTO ADG_Individual VALUES (20, 'FName10', 'LName10', 'DL010', 'InsCo10', 'Pol010', 11);

INSERT INTO ADG_Corporate VALUES (1, 'CorpName1', 'Reg0001', 17);
INSERT INTO ADG_Corporate VALUES (3, 'CorpName2', 'Reg0002', 17);
INSERT INTO ADG_Corporate VALUES (5, 'CorpName3', 'Reg0003', 17);
INSERT INTO ADG_Corporate VALUES (7, 'CorpName4', 'Reg0004', 17);
INSERT INTO ADG_Corporate VALUES (9, 'CorpName5', 'Reg0005', 17);
INSERT INTO ADG_Corporate VALUES (11, 'CorpName6', 'Reg0006', 17);
INSERT INTO ADG_Corporate VALUES (13, 'CorpName7', 'Reg0007', 17);
INSERT INTO ADG_Corporate VALUES (15, 'CorpName8', 'Reg0008', 17);
INSERT INTO ADG_Corporate VALUES (17, 'CorpName9', 'Reg0009', 17);
INSERT INTO ADG_Corporate VALUES (19, 'CorpName10', 'Reg0010', 17);

INSERT INTO ADG_DiscountCoupon VALUES (1, 10, DATE '2023-01-01', DATE '2023-12-31', 1);
INSERT INTO ADG_DiscountCoupon VALUES (2, 15, DATE'2023-01-01', DATE'2023-12-31', 2);
INSERT INTO ADG_DiscountCoupon VALUES (3, 20, DATE '2023-01-01', DATE'2023-12-31', 3);
INSERT INTO ADG_DiscountCoupon VALUES (4, 25, DATE'2023-01-01', DATE'2023-12-31', 4);
INSERT INTO ADG_DiscountCoupon VALUES (5, 5, DATE'2023-01-01', DATE'2023-12-31', 5);
INSERT INTO ADG_DiscountCoupon VALUES (6, 10, DATE'2023-01-01', DATE'2023-12-31', 6);
INSERT INTO ADG_DiscountCoupon VALUES (7, 15,DATE '2023-01-01', DATE'2023-12-31', 7);
INSERT INTO ADG_DiscountCoupon VALUES (8, 20, DATE'2023-01-01',DATE '2023-12-31', 8);
INSERT INTO ADG_DiscountCoupon VALUES (9, 25,DATE '2023-01-01', DATE'2023-12-31', 9);
INSERT INTO ADG_DiscountCoupon VALUES (10, 5, DATE '2023-01-01', DATE'2023-12-31', 10);
INSERT INTO ADG_DiscountCoupon VALUES (11, 10,DATE '2023-01-01', DATE'2023-12-31', 11);
INSERT INTO ADG_DiscountCoupon VALUES (12, 15, DATE '2023-01-01', DATE '2023-12-31', 12);
INSERT INTO ADG_DiscountCoupon VALUES (13, 20, DATE'2023-01-01', DATE'2023-12-31', 13);
INSERT INTO ADG_DiscountCoupon VALUES (14, 25,DATE '2023-01-01',DATE '2023-12-31', 14);
INSERT INTO ADG_DiscountCoupon VALUES (15, 5, DATE'2023-01-01', DATE '2023-12-31', 15);
INSERT INTO ADG_DiscountCoupon VALUES (16, 10,DATE '2023-01-01', DATE'2023-12-31', 16);
INSERT INTO ADG_DiscountCoupon VALUES (17, 15,DATE '2023-01-01',DATE '2023-12-31', 17);
INSERT INTO ADG_DiscountCoupon VALUES (18, 20, DATE'2023-01-01', DATE '2023-12-31', 18);
INSERT INTO ADG_DiscountCoupon VALUES (19, 25,DATE '2023-01-01',DATE '2023-12-31', 19);
INSERT INTO ADG_DiscountCoupon VALUES (20, 5, DATE'2023-01-01',DATE '2023-12-31', 20);

INSERT INTO ADG_RentalService VALUES (1, DATE '2023-01-01', DATE '2023-01-05', 0, 350, 100, 'LocationA', 'LocationB', 1, 'VIN1234567');
INSERT INTO ADG_RentalService VALUES (2, DATE '2023-01-02', DATE '2023-01-06', 0, 420, 100, 'LocationC', 'LocationD', 2, 'VIN2345678');
INSERT INTO ADG_RentalService VALUES (3, DATE '2023-01-03', DATE  '2023-01-07', 0, 380, 100, 'LocationE', 'LocationF', 3, 'VIN3456789');
INSERT INTO ADG_RentalService VALUES (4, DATE '2023-01-04', DATE '2023-01-08', 0, 460, 100, 'LocationG', 'LocationH', 4, 'VIN4567890');
INSERT INTO ADG_RentalService VALUES (5, DATE '2023-01-05', DATE '2023-01-09', 0, 500, 100, 'LocationI', 'LocationJ', 5, 'VIN5678901');
INSERT INTO ADG_RentalService VALUES (6, DATE '2023-01-06', DATE '2023-01-10', 0, 620, 100, 'LocationA', 'LocationB', 6, 'VIN6789012');
INSERT INTO ADG_RentalService VALUES (7, DATE '2023-01-07', DATE '2023-01-11', 0, 290, 100, 'LocationC', 'LocationD', 7, 'VIN7890123');
INSERT INTO ADG_RentalService VALUES (8, DATE '2023-01-08', DATE '2023-01-12', 0, 550, 100, 'LocationE', 'LocationF', 8, 'VIN8901234');
INSERT INTO ADG_RentalService VALUES (9, DATE '2023-01-09', DATE '2023-01-13', 0, 610, 100, 'LocationG', 'LocationH', 9, 'VIN9012345');
INSERT INTO ADG_RentalService VALUES (10, DATE '2023-01-10', DATE '2023-01-14', 0, 330, 100, 'LocationI', 'LocationJ', 10, 'VIN0123456');
INSERT INTO ADG_RentalService VALUES (11, DATE '2023-01-11', DATE '2023-01-15', 0, 440, 100, 'LocationA', 'LocationB', 11, 'VIN1123456');
INSERT INTO ADG_RentalService VALUES (12, DATE '2023-01-12',DATE  '2023-01-16', 0, 370, 100, 'LocationC', 'LocationD', 12, 'VIN2234567');
INSERT INTO ADG_RentalService VALUES (13, DATE '2023-01-13', DATE '2023-01-17', 0, 520, 100, 'LocationE', 'LocationF', 13, 'VIN3345678');
INSERT INTO ADG_RentalService VALUES (14, DATE '2023-01-14', DATE '2023-01-18', 0, 405, 100, 'LocationG', 'LocationH', 14, 'VIN4456789');
INSERT INTO ADG_RentalService VALUES (15, DATE '2023-01-15', DATE '2023-01-19', 0, 480, 100, 'LocationI', 'LocationJ', 15, 'VIN5567890');
INSERT INTO ADG_RentalService VALUES (16, DATE '2023-01-16', DATE '2023-01-20', 0, 530, 100, 'LocationA', 'LocationB', 16, 'VIN6678901');
INSERT INTO ADG_RentalService VALUES (17, DATE '2023-01-17', DATE '2023-01-21', 0, 390, 100, 'LocationC', 'LocationD', 17, 'VIN7789012');
INSERT INTO ADG_RentalService VALUES (18, DATE '2023-01-18', DATE '2023-01-22', 0, 420, 100, 'LocationE', 'LocationF', 18, 'VIN8890123');
INSERT INTO ADG_RentalService VALUES (19, DATE '2023-01-19', DATE '2023-01-23', 0, 465, 100, 'LocationG', 'LocationH', 19, 'VIN9901234');
INSERT INTO ADG_RentalService VALUES (20, DATE '2023-01-20',DATE  '2023-01-24', 0, 350, 100, 'LocationI', 'LocationJ', 20, 'VIN0012345');

INSERT INTO ADG_Invoice VALUES (1, DATE'2023-01-01', 1);
INSERT INTO ADG_Invoice VALUES (2, DATE'2023-01-02', 2);
INSERT INTO ADG_Invoice VALUES (3, DATE'2023-01-03', 3);
INSERT INTO ADG_Invoice VALUES (4, DATE'2023-01-04', 4);
INSERT INTO ADG_Invoice VALUES (5, DATE'2023-01-05', 5);
INSERT INTO ADG_Invoice VALUES (6, DATE'2023-01-06', 6);
INSERT INTO ADG_Invoice VALUES (7, DATE'2023-01-07', 7);
INSERT INTO ADG_Invoice VALUES (8, DATE'2023-01-08', 8);
INSERT INTO ADG_Invoice VALUES (9, DATE'2023-01-09', 9);
INSERT INTO ADG_Invoice VALUES (10, DATE'2023-01-10', 10);
INSERT INTO ADG_Invoice VALUES (11, DATE'2023-01-11', 11);
INSERT INTO ADG_Invoice VALUES (12, DATE'2023-01-12', 12);
INSERT INTO ADG_Invoice VALUES (13, DATE'2023-01-13', 13);
INSERT INTO ADG_Invoice VALUES (14, DATE'2023-01-14', 14);
INSERT INTO ADG_Invoice VALUES (15, DATE'2023-01-15', 15);
INSERT INTO ADG_Invoice VALUES (16, DATE'2023-01-16', 16);
INSERT INTO ADG_Invoice VALUES (17, DATE'2023-01-17', 17);
INSERT INTO ADG_Invoice VALUES (18, DATE'2023-01-18', 18);
INSERT INTO ADG_Invoice VALUES (19, DATE'2023-01-19', 19);
INSERT INTO ADG_Invoice VALUES (20, DATE'2023-01-20', 20);

ALTER TABLE ADG_Card_Details MODIFY cardnumber VARCHAR(19);

INSERT INTO ADG_Card_Details VALUES (1, 'VISA', '4111111111111111', 'John', 'Doe', '2025-12-31', 123),
(2, 'MASTERCARD', '5222222222222222', 'Jane', 'Doe', '2024-11-30', 234),
(3, 'AMEX', '371449635398431', 'Alice', 'Smith', '2023-10-31', 345),
(4, 'DISCOVER', '6011111111111117', 'Bob', 'Smith', '2026-01-31', 456),
(5, 'VISA', '4111111111111111', 'Charlie', 'Brown', '2025-07-31', 567),
(6, 'MASTERCARD', '5222222222222222', 'Diana', 'Ross', '2023-09-30', 678),
(7, 'AMEX', '371449635398431', 'Ethan', 'Hunt', '2024-08-31', 789),
(8, 'DISCOVER', '6011111111111117', 'Fiona', 'Grace', '2027-06-30', 890),
(9, 'VISA', '4111111111111111', 'George', 'King', '2023-05-31', 901),
(10, 'MASTERCARD', '5222222222222222', 'Hannah', 'Lee', '2028-04-30', 112);

INSERT INTO ADG_Payment VALUES (1, DATE '2023-01-05', 'credit', 1, 1);
INSERT INTO ADG_Payment VALUES (2, DATE '2023-01-06', 'debit',  2, 2);
INSERT INTO ADG_Payment VALUES (3, DATE '2023-01-07', 'gift', 3, 3);
INSERT INTO ADG_Payment VALUES (4, DATE '2023-01-08', 'credit', 4, 4);
INSERT INTO ADG_Payment VALUES (5, DATE '2023-01-09', 'debit',  5, 5);
INSERT INTO ADG_Payment VALUES (6, DATE '2023-01-10', 'gift',  6, 6);
INSERT INTO ADG_Payment VALUES (7, DATE '2023-01-11', 'credit',  7, 7);
INSERT INTO ADG_Payment VALUES (8, DATE '2023-01-12', 'debit',  8, 8);
INSERT INTO ADG_Payment VALUES (9, DATE '2023-01-13', 'gift',  9, 9);
INSERT INTO ADG_Payment VALUES (10, DATE '2023-01-14', 'credit', 10, 10);
INSERT INTO ADG_Payment VALUES (11, DATE '2023-01-15', 'debit',  11, 1);
INSERT INTO ADG_Payment VALUES (12, DATE '2023-01-16', 'gift', 12, 2);
INSERT INTO ADG_Payment VALUES (13, DATE '2023-01-17', 'credit',  13, 3);
INSERT INTO ADG_Payment VALUES (14, DATE '2023-01-18', 'debit',  14, 4);
INSERT INTO ADG_Payment VALUES (15, DATE '2023-01-19', 'gift',  15, 5);
INSERT INTO ADG_Payment VALUES (16, DATE '2023-01-20', 'credit',  16, 6);
INSERT INTO ADG_Payment VALUES (17, DATE '2023-01-21', 'debit',  17, 7);
INSERT INTO ADG_Payment VALUES (18, DATE '2023-01-22', 'gift',  18, 8);
INSERT INTO ADG_Payment VALUES (19, DATE '2023-01-23', 'credit',  19, 9);
INSERT INTO ADG_Payment VALUES (20, DATE '2023-01-24', 'debit',  20, 10);

SET SQL_SAFE_UPDATES = 0;
UPDATE ADG_rentalservice
SET pickup_date = REPLACE(pickup_date, '2023', '2024'),
    dropoff_date = REPLACE(dropoff_date, '2023', '2024');
SET SQL_SAFE_UPDATES = 1;

-- Update existing statuses
SET SQL_SAFE_UPDATES = 0;
UPDATE ADG_vehiclestatus
SET status = CASE statusid
                 WHEN 1 THEN 'Rented'
                 WHEN 2 THEN 'Available'
                 WHEN 3 THEN 'Available'
                 WHEN 4 THEN 'Available'
                 WHEN 5 THEN 'Rented'
                 WHEN 6 THEN 'Available'
                 WHEN 7 THEN 'Rented'
                 WHEN 8 THEN 'Available'
                 WHEN 9 THEN 'Rented'
                 WHEN 10 THEN 'Available'
                 WHEN 11 THEN 'Rented'
                 ELSE 'Under Maintenance'
             END;
SET SQL_SAFE_UPDATES = 1;

-- Update the statusid in the Vehicle table
SET SQL_SAFE_UPDATES = 0;
UPDATE ADG_vehicle
SET statusid = CASE statusid
                   WHEN 1 THEN 1  -- 'Rented'
                   WHEN 2 THEN 2  -- 'Available'
                   WHEN 3 THEN 2  -- 'Available'
                   WHEN 4 THEN 2  -- 'Available'
                   WHEN 5 THEN 1  -- 'Rented'
                   WHEN 6 THEN 2  -- 'Available'
                   WHEN 7 THEN 1  -- 'Rented'
                   WHEN 8 THEN 2  -- 'Available'
                   WHEN 9 THEN 1  -- 'Rented'
                   WHEN 10 THEN 2 -- 'Available'
                   WHEN 11 THEN 1 -- 'Rented'
                   ELSE 3         -- 'Under Maintenance'
               END;
SET SQL_SAFE_UPDATES = 1;

SET SQL_SAFE_UPDATES = 0;
-- Delete statuses that are no longer needed
DELETE FROM ADG_vehiclestatus
WHERE statusid NOT IN (1, 2, 3);
SET SQL_SAFE_UPDATES = 1;

SET SQL_SAFE_UPDATES = 0;
UPDATE ADG_vehicle v
JOIN (
    SELECT vin
    FROM ADG_vehicle
    ORDER BY RAND()
    LIMIT 3
) AS random_vins ON v.vin = random_vins.vin
SET v.statusid = 3;
SET SQL_SAFE_UPDATES = 1;

ALTER TABLE ADG_vehiclestatus
MODIFY COLUMN status VARCHAR(20);

UPDATE ADG_vehiclestatus
SET status = 'Under Maintenance'
WHERE statusid = 3;

UPDATE adg_rentalservice SET pickup_date = '2024-01-01', dropoff_date = '2024-01-10' WHERE rentalid = 1;
UPDATE adg_rentalservice SET pickup_date = '2024-01-02', dropoff_date = '2024-01-12' WHERE rentalid = 2;
UPDATE adg_rentalservice SET pickup_date = '2024-01-03', dropoff_date = '2024-01-13' WHERE rentalid = 3;
UPDATE adg_rentalservice SET pickup_date = '2024-01-04', dropoff_date = '2024-01-14' WHERE rentalid = 4;
UPDATE adg_rentalservice SET pickup_date = '2024-01-05', dropoff_date = '2024-01-15' WHERE rentalid = 5;
UPDATE adg_rentalservice SET pickup_date = '2024-01-06', dropoff_date = '2024-01-16' WHERE rentalid = 6;
UPDATE adg_rentalservice SET pickup_date = '2024-01-07', dropoff_date = '2024-01-17' WHERE rentalid = 7;
UPDATE adg_rentalservice SET pickup_date = '2024-01-08', dropoff_date = '2024-01-18' WHERE rentalid = 8;
UPDATE adg_rentalservice SET pickup_date = '2024-01-09', dropoff_date = '2024-01-19' WHERE rentalid = 9;
UPDATE adg_rentalservice SET pickup_date = '2024-01-10', dropoff_date = '2024-01-20' WHERE rentalid = 10;
UPDATE adg_rentalservice SET pickup_date = '2024-01-11', dropoff_date = '2024-01-21' WHERE rentalid = 11;
UPDATE adg_rentalservice SET pickup_date = '2024-01-12', dropoff_date = '2024-01-22' WHERE rentalid = 12;
UPDATE adg_rentalservice SET pickup_date = '2024-01-13', dropoff_date = '2024-01-23' WHERE rentalid = 13;
UPDATE adg_rentalservice SET pickup_date = '2024-01-14', dropoff_date = '2024-01-24' WHERE rentalid = 14;
UPDATE adg_rentalservice SET pickup_date = '2024-01-15', dropoff_date = '2024-01-25' WHERE rentalid = 15;
UPDATE adg_rentalservice SET pickup_date = '2024-01-16', dropoff_date = '2024-01-26' WHERE rentalid = 16;
UPDATE adg_rentalservice SET pickup_date = '2024-01-17', dropoff_date = '2024-01-27' WHERE rentalid = 17;
UPDATE adg_rentalservice SET pickup_date = '2024-01-18', dropoff_date = '2024-01-28' WHERE rentalid = 18;
UPDATE adg_rentalservice SET pickup_date = '2024-01-19', dropoff_date = '2024-01-29' WHERE rentalid = 19;
UPDATE adg_rentalservice SET pickup_date = '2024-01-20', dropoff_date = '2024-01-30' WHERE rentalid = 20;

-- Office ID: 1 (Los Angeles)
UPDATE adg_vehicle SET statusid = 2 WHERE office_id = 1 AND vin IN ('VIN1234567', 'VIN2234567');

-- Office ID: 2 (San Francisco)
UPDATE adg_vehicle SET statusid = 2 WHERE office_id = 2 AND vin IN ('VIN2345678', 'VIN3345678');

-- Office ID: 3 (Seattle)
UPDATE adg_vehicle SET statusid = 2 WHERE office_id = 3 AND vin IN ('VIN3456789', 'VIN4456789');

-- Office ID: 4 (Chicago)
UPDATE adg_vehicle SET statusid = 2 WHERE office_id = 4 AND vin IN ('VIN4567890', 'VIN5567890');

-- Office ID: 5 (Miami)
UPDATE adg_vehicle SET statusid = 2 WHERE office_id = 5 AND vin IN ('VIN5678901', 'VIN6678901');

-- Office ID: 6 (New York)
UPDATE adg_vehicle SET statusid = 2 WHERE office_id = 6 AND vin IN ('VIN6789012', 'VIN7789012');

-- Office ID: 7 (Las Vegas)
UPDATE adg_vehicle SET statusid = 2 WHERE office_id = 7 AND vin IN ('VIN7890123', 'VIN8890123');

-- Office ID: 8 (Phoenix)
UPDATE adg_vehicle SET statusid = 2 WHERE office_id = 8 AND vin IN ('VIN8901234', 'VIN9012345');

-- Office ID: 9 (Boston)
UPDATE adg_vehicle SET statusid = 2 WHERE office_id = 9 AND vin IN ('VIN9901234', 'VIN0012345');

-- Office ID: 10 (Denver)
UPDATE adg_vehicle SET statusid = 2 WHERE office_id = 10 AND vin IN ('VIN0123456', 'VIN1123456');

-- Office ID: 11 (Dallas)
UPDATE adg_vehicle SET statusid = 2 WHERE office_id = 11 AND vin IN ('VIN1234567', 'VIN2234567');


-- Office ID: 1 (Los Angeles)
UPDATE adg_vehicle SET statusid = 2 WHERE office_id = 1 AND vin IN ('VIN1234567', 'VIN2234567', 'VIN5567890', 'VIN6678901');

-- Office ID: 2 (San Francisco)
UPDATE adg_vehicle SET statusid = 2 WHERE office_id = 2 AND vin IN ('VIN2345678', 'VIN3345678', 'VIN7789012', 'VIN7890123');

-- Office ID: 3 (Seattle)
UPDATE adg_vehicle SET statusid = 2 WHERE office_id = 3 AND vin IN ('VIN3456789', 'VIN4456789', 'VIN8890123', 'VIN8901234');

-- Office ID: 4 (Chicago)
UPDATE adg_vehicle SET statusid = 2 WHERE office_id = 4 AND vin IN ('VIN4567890', 'VIN5567890', 'VIN9012345', 'VIN9901234');

-- Office ID: 5 (Miami)
UPDATE adg_vehicle SET statusid = 2 WHERE office_id = 5 AND vin IN ('VIN5678901', 'VIN6678901', 'VIN0012345', 'VIN0123456');

-- Office ID: 6 (New York)
UPDATE adg_vehicle SET statusid = 2 WHERE office_id = 6 AND vin IN ('VIN6789012', 'VIN7789012', 'VIN1123456', 'VIN1234567');

-- Office ID: 7 (Las Vegas)
UPDATE adg_vehicle SET statusid = 2 WHERE office_id = 7 AND vin IN ('VIN7890123', 'VIN8890123', 'VIN2234567', 'VIN2345678');

-- Office ID: 8 (Phoenix)
UPDATE adg_vehicle SET statusid = 2 WHERE office_id = 8 AND vin IN ('VIN8901234', 'VIN9012345', 'VIN3345678', 'VIN3456789');

-- Office ID: 9 (Boston)
UPDATE adg_vehicle SET statusid = 2 WHERE office_id = 9 AND vin IN ('VIN9901234', 'VIN0012345', 'VIN4456789', 'VIN4567890');

-- Office ID: 10 (Denver)
UPDATE adg_vehicle SET statusid = 2 WHERE office_id = 10 AND vin IN ('VIN0123456', 'VIN1123456', 'VIN5567890', 'VIN5678901');

-- Office ID: 11 (Dallas)
UPDATE adg_vehicle SET statusid = 2 WHERE office_id = 11 AND vin IN ('VIN1234567', 'VIN2234567', 'VIN6678901', 'VIN6789012');


INSERT INTO adg_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES 
('VIN0012346', 'Cadillac', 'XT5', '2023', 'LIC010', 1, 2, 21),
('VIN0123457', 'Hyundai', 'Elantra', '2023', 'LIC011', 2, 2, 20),
('VIN1123457', 'Mercedes', 'E-Class', '2023', 'LIC112', 3, 2, 19),
('VIN1234568', 'Toyota', 'Corolla', '2023', 'LIC124', 4, 2, 13),
('VIN2234568', 'Volkswagen', 'Passat', '2023', 'LIC223', 5, 2, 18),
('VIN2345679', 'Honda', 'Accord', '2023', 'LIC235', 6, 2, 14),
('VIN3345679', 'Subaru', 'Forester', '2023', 'LIC334', 7, 2, 17),
('VIN3456790', 'Ford', 'Explorer', '2023', 'LIC346', 8, 2, 15),
('VIN4456790', 'Mazda', '3', '2023', 'LIC445', 9, 2, 20),
('VIN4567901', 'Chevy', 'Malibu', '2023', 'LIC457', 10, 2, 16),
('VIN5567901', 'Lexus', 'ES', '2023', 'LIC556', 11, 2, 12),
('VIN5678902', 'BMW', '5 Series', '2023', 'LIC568', 1, 2, 12),
('VIN6678902', 'Jeep', 'Cherokee', '2023', 'LIC667', 2, 2, 15),
('VIN6789013', 'Audi', 'Q5', '2023', 'LIC679', 3, 2, 19),
('VIN7789013', 'Dodge', 'Durango', '2023', 'LIC778', 4, 2, 16),
('VIN7890124', 'Tesla', 'Model X', '2023', 'LIC790', 5, 2, 12),
('VIN8890124', 'Chrysler', 'Pacifica', '2023', 'LIC889', 6, 2, 13),
('VIN8901235', 'Kia', 'Optima', '2023', 'LIC891', 7, 2, 17),
('VIN9012346', 'Nissan', 'Maxima', '2023', 'LIC902', 8, 2, 18),
('VIN9901235', 'GMC', 'Yukon', '2023', 'LIC990', 9, 2, 14);

INSERT INTO adg_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES
-- Adding more premium SUVs in Los Angeles
('VIN7890125', 'Tesla', 'Model X', '2023', 'LIC791', 1, 2, 15),
('VIN7890126', 'Audi', 'Q7', '2023', 'LIC792', 1, 2, 15),
('VIN7890127', 'BMW', 'X5', '2023', 'LIC793', 1, 2, 15),

-- Adding various vehicles to other offices
('VIN0012347', 'Cadillac', 'CT6', '2023', 'LIC003', 2, 2, 12),
('VIN0123458', 'Hyundai', 'Santa Fe', '2023', 'LIC013', 3, 2, 16),
('VIN1123458', 'Mercedes', 'GLC', '2023', 'LIC114', 4, 2, 15),
('VIN1234569', 'Toyota', 'RAV4', '2023', 'LIC125', 5, 2, 16),
('VIN2234569', 'Volkswagen', 'Tiguan', '2023', 'LIC224', 6, 2, 16),
('VIN2345680', 'Honda', 'Pilot', '2023', 'LIC236', 7, 2, 16),
('VIN3345680', 'Subaru', 'Legacy', '2023', 'LIC335', 8, 2, 13),
('VIN3456791', 'Ford', 'Mustang', '2023', 'LIC347', 9, 2, 19),
('VIN4456791', 'Mazda', '6', '2023', 'LIC446', 10, 2, 13),
('VIN4567902', 'Chevy', 'Tahoe', '2023', 'LIC458', 11, 2, 16);

UPDATE adg_vehicle
SET vehiclemake = 'Cadillac', vehiclemodel = 'XT5', vehicleyear = 2023, classid = 12
WHERE vin = 'VIN0012345';

UPDATE adg_vehicle
SET vehiclemake = 'Hyundai', vehiclemodel = 'Elantra', vehicleyear = 2023, classid = 13
WHERE vin = 'VIN0123456';

UPDATE adg_vehicle
SET vehiclemake = 'Mercedes', vehiclemodel = 'GLA', vehicleyear = 2023, classid = 15
WHERE vin = 'VIN1123456';

UPDATE adg_vehicle
SET vehiclemake = 'Toyota', vehiclemodel = 'Corolla', vehicleyear = 2023, classid = 13
WHERE vin = 'VIN1234567';

UPDATE adg_vehicle
SET vehiclemake = 'Volkswagen', vehiclemodel = 'Passat', vehicleyear = 2023, classid = 13
WHERE vin = 'VIN2234567';

UPDATE adg_vehicle
SET vehiclemake = 'Honda', vehiclemodel = 'Accord', vehicleyear = 2023, classid = 13
WHERE vin = 'VIN2345678';

UPDATE adg_vehicle
SET vehiclemake = 'Subaru', vehiclemodel = 'Forester', vehicleyear = 2023, classid = 16
WHERE vin = 'VIN3345678';

UPDATE adg_vehicle
SET vehiclemake = 'Ford', vehiclemodel = 'Explorer', vehicleyear = 2023, classid = 16
WHERE vin = 'VIN3456789';

UPDATE adg_vehicle
SET vehiclemake = 'Mazda', vehiclemodel = 'CX-9', vehicleyear = 2023, classid = 16
WHERE vin = 'VIN4456789';

-- Repeat for remaining vehicles as needed

COMMIT;

UPDATE adg_vehicle
SET vehiclemake = 'Cadillac', vehiclemodel = 'XT5', vehicleyear = 2023, classid = 21
WHERE vin = 'VIN0012346';

UPDATE adg_vehicle
SET vehiclemake = 'Hyundai', vehiclemodel = 'Elantra', vehicleyear = 2023, classid = 20
WHERE vin = 'VIN0123457';

UPDATE adg_vehicle
SET vehiclemake = 'Mercedes', vehiclemodel = 'E-Class', vehicleyear = 2023, classid = 19
WHERE vin = 'VIN1123457';

UPDATE adg_vehicle
SET vehiclemake = 'Toyota', vehiclemodel = 'Corolla', vehicleyear = 2023, classid = 13
WHERE vin = 'VIN1234568';

UPDATE adg_vehicle
SET vehiclemake = 'Volkswagen', vehiclemodel = 'Passat', vehicleyear = 2023, classid = 18
WHERE vin = 'VIN2234568';

UPDATE adg_vehicle
SET vehiclemake = 'Honda', vehiclemodel = 'Accord', vehicleyear = 2023, classid = 14
WHERE vin = 'VIN2345679';

UPDATE adg_vehicle
SET vehiclemake = 'Subaru', vehiclemodel = 'Forester', vehicleyear = 2023, classid = 17
WHERE vin = 'VIN3345679';

UPDATE adg_vehicle
SET vehiclemake = 'Ford', vehiclemodel = 'Explorer', vehicleyear = 2023, classid = 15
WHERE vin = 'VIN3456790';

UPDATE adg_vehicle
SET vehiclemake = 'Mazda', vehiclemodel = '3', vehicleyear = 2023, classid = 20
WHERE vin = 'VIN4456790';

UPDATE adg_vehicle
SET vehiclemake = 'Chevy', vehiclemodel = 'Malibu', vehicleyear = 2023, classid = 16
WHERE vin = 'VIN4567901';

UPDATE adg_vehicle
SET vehiclemake = 'Lexus', vehiclemodel = 'ES', vehicleyear = 2023, classid = 12
WHERE vin = 'VIN5567901';

UPDATE adg_vehicle
SET vehiclemake = 'BMW', vehiclemodel = '5 Series', vehicleyear = 2023, classid = 12
WHERE vin = 'VIN5678902';

UPDATE adg_vehicle
SET vehiclemake = 'Jeep', vehiclemodel = 'Cherokee', vehicleyear = 2023, classid = 15
WHERE vin = 'VIN6678902';

UPDATE adg_vehicle
SET vehiclemake = 'Audi', vehiclemodel = 'Q5', vehicleyear = 2023, classid = 19
WHERE vin = 'VIN6789013';

UPDATE adg_vehicle
SET vehiclemake = 'Dodge', vehiclemodel = 'Durango', vehicleyear = 2023, classid = 16
WHERE vin = 'VIN7789013';

UPDATE adg_vehicle
SET vehiclemake = 'Tesla', vehiclemodel = 'Model X', vehicleyear = 2023, classid = 12
WHERE vin = 'VIN7890124';

UPDATE adg_vehicle
SET vehiclemake = 'Chrysler', vehiclemodel = 'Pacifica', vehicleyear = 2023, classid = 13
WHERE vin = 'VIN8890124';

UPDATE adg_vehicle
SET vehiclemake = 'Kia', vehiclemodel = 'Optima', vehicleyear = 2023, classid = 17
WHERE vin = 'VIN8901235';

UPDATE adg_vehicle
SET vehiclemake = 'Nissan', vehiclemodel = 'Maxima', vehicleyear = 2023, classid = 18
WHERE vin = 'VIN9012346';

UPDATE adg_vehicle
SET vehiclemake = 'GMC', vehiclemodel = 'Yukon', vehicleyear = 2023, classid = 14
WHERE vin = 'VIN9901235';

COMMIT;



-- Disable foreign key checks
SET FOREIGN_KEY_CHECKS = 0;

DELETE FROM adg_vehicle
WHERE vin IN (
    'VIN1000006','VIN1000005','VIN1000004','VIN1000003','VIN0012345', 'VIN0012346', 'VIN0012347', 'VIN0123456', 'VIN0123457', 'VIN0123458', 'VIN1000001', 'VIN1000002', 'VIN1000007', 'VIN1000008', 'VIN1000009', 'VIN1000010', 'VIN1000011', 'VIN1123456', 'VIN1123457', 'VIN1123458', 'VIN1234567', 'VIN1234568', 'VIN1234569', 'VIN2234567', 'VIN2234568', 'VIN2234569', 'VIN2345678', 'VIN2345679', 'VIN2345680', 'VIN3345678', 'VIN3345679', 'VIN3345680', 'VIN3456789', 'VIN3456790', 'VIN3456791', 'VIN4456789', 'VIN4456790', 'VIN4456791', 'VIN4567890', 'VIN4567901', 'VIN4567902', 'VIN5567890', 'VIN5567901', 'VIN5678901', 'VIN5678902', 'VIN6678901', 'VIN6678902', 'VIN6789012', 'VIN6789013', 'VIN7789012', 'VIN7789013', 'VIN7890123', 'VIN7890124', 'VIN7890125', 'VIN7890126', 'VIN7890127', 'VIN8890123', 'VIN8890124', 'VIN8901234', 'VIN8901235', 'VIN9012345', 'VIN9012346', 'VIN9901234', 'VIN9901235'
    -- Add more old VINs as needed
);

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000007', 'Hyundai', 'Tucson', '2023', 'LIC007', '10', '3', '20');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000008', 'Mercedes', 'C-Class', '2023', 'LIC008', '11', '3', '19');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000009', 'Toyota', 'Camry', '2023', 'LIC009', '1', '1', '13');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000010', 'Volkswagen', 'Golf', '2023', 'LIC010', '1', '2', '18');

-- Continue with similar INSERT INTO statements for VIN1000011 and beyond...

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000011', 'Honda', 'Civic', '2023', 'LIC011', '2', '2', '14');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000012', 'Subaru', 'Outback', '2023', 'LIC012', '2', '2', '17');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000013', 'Ford', 'Fusion', '2023', 'LIC013', '3', '2', '15');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000014', 'Mazda', 'CX-5', '2023', 'LIC014', '3', '2', '20');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000015', 'Chevy', 'Impala', '2023', 'LIC015', '4', '2', '16');

-- Continue with similar INSERT INTO statements for VIN1000016 and beyond...
INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000016', 'Lexus', 'RX', '2023', 'LIC016', '4', '1', '12');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000017', 'BMW', 'X3', '2023', 'LIC017', '5', '1', '12');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000018', 'Jeep', 'Wrangler', '2023', 'LIC018', '5', '2', '15');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000019', 'Audi', 'A4', '2023', 'LIC019', '6', '2', '19');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000020', 'Dodge', 'Charger', '2023', 'LIC020', '6', '1', '16');

-- Continue with similar INSERT INTO statements for VIN1000021 and beyond...
INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000021', 'Tesla', 'Model S', '2023', 'LIC021', '7', '1', '12');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000022', 'Chrysler', '300', '2023', 'LIC022', '7', '2', '13');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000023', 'Kia', 'Sorento', '2023', 'LIC023', '8', '2', '17');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000024', 'Nissan', 'Altima', '2023', 'LIC024', '9', '1', '18');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000025', 'GMC', 'Acadia', '2023', 'LIC025', '8', '3', '14');

-- Continue with similar INSERT INTO statements for VIN1000026 and beyond...

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000026', 'Cadillac', 'Escalade', '2023', 'LIC026', '9', '2', '21');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000027', 'Hyundai', 'Tucson', '2023', 'LIC027', '10', '3', '20');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000028', 'Mercedes', 'C-Class', '2023', 'LIC028', '11', '3', '19');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000029', 'Toyota', 'Camry', '2023', 'LIC029', '1', '1', '13');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000030', 'Volkswagen', 'Golf', '2023', 'LIC030', '1', '2', '18');

-- Continue with similar INSERT INTO statements for VIN1000031 and beyond...
INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000031', 'Honda', 'Civic', '2023', 'LIC031', '2', '2', '14');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000032', 'Subaru', 'Outback', '2023', 'LIC032', '2', '2', '17');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000033', 'Ford', 'Fusion', '2023', 'LIC033', '3', '2', '15');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000034', 'Mazda', 'CX-5', '2023', 'LIC034', '3', '2', '20');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000035', 'Chevy', 'Impala', '2023', 'LIC035', '4', '2', '16');

-- Continue with similar INSERT INTO statements for VIN1000036 and beyond...
INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000041', 'Tesla', 'Model S', '2023', 'LIC041', '7', '1', '12');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000042', 'Chrysler', '300', '2023', 'LIC042', '7', '2', '13');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000043', 'Kia', 'Sorento', '2023', 'LIC043', '8', '2', '17');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000044', 'Nissan', 'Altima', '2023', 'LIC044', '9', '1', '18');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000045', 'GMC', 'Acadia', '2023', 'LIC045', '8', '3', '14');

-- Continue with similar INSERT INTO statements for VIN1000046 and beyond...
INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000036', 'Lexus', 'RX', '2023', 'LIC036', '4', '1', '12');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000037', 'BMW', 'X3', '2023', 'LIC037', '5', '1', '12');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000038', 'Jeep', 'Wrangler', '2023', 'LIC038', '5', '2', '15');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000039', 'Audi', 'A4', '2023', 'LIC039', '6', '2', '19');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000040', 'Dodge', 'Charger', '2023', 'LIC040', '6', '1', '16');

-- Continue with similar INSERT INTO statements for VIN1000041 and beyond...
INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000046', 'Ford', 'Fusion', '2023', 'LIC046', '3', '2', '15');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000047', 'Mazda', 'CX-5', '2023', 'LIC047', '3', '2', '20');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000048', 'Chevy', 'Impala', '2023', 'LIC048', '4', '2', '16');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000049', 'Lexus', 'RX', '2023', 'LIC049', '4', '2', '12');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000050', 'BMW', 'X3', '2023', 'LIC050', '5', '2', '12');

-- Continue with similar INSERT INTO statements for VIN1000051 and beyond...
INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000051', 'Jeep', 'Wrangler', '2023', 'LIC051', '5', '2', '15');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000052', 'Audi', 'A4', '2023', 'LIC052', '6', '2', '19');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000053', 'Dodge', 'Charger', '2023', 'LIC053', '6', '2', '16');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000054', 'Tesla', 'Model S', '2023', 'LIC054', '7', '2', '12');

INSERT INTO ADG_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES ('VIN1000055', 'Chrysler', '300', '2023', 'LIC055', '7', '2', '13');

DELETE FROM ADG_rentalservice;

INSERT INTO adg_rentalservice (rentalid, pickup_date, dropoff_date, start_odometer, end_odometer, daily_odometer_limit, pickup_location, dropoff_location, custid, vin)
VALUES
('1', '2024-01-01', '2024-01-05', '0', '350', '100', 'LocationA', 'LocationB', '1', 'VIN1000007'),
('2', '2024-01-02', '2024-01-06', '0', '420', '100', 'LocationC', 'LocationD', '2', 'VIN1000008'),
('3', '2024-01-03', '2024-01-07', '0', '380', '100', 'LocationE', 'LocationF', '3', 'VIN1000009'),
('4', '2024-01-04', '2024-01-08', '0', '460', '100', 'LocationG', 'LocationH', '4', 'VIN1000010'),
('5', '2024-01-05', '2024-01-09', '0', '500', '100', 'LocationI', 'LocationJ', '5', 'VIN1000011'),
('6', '2024-01-06', '2024-01-10', '0', '620', '100', 'LocationA', 'LocationB', '6', 'VIN1000012'),
('7', '2024-01-07', '2024-01-11', '0', '290', '100', 'LocationC', 'LocationD', '7', 'VIN1000013'),
('8', '2024-01-08', '2024-01-12', '0', '550', '100', 'LocationE', 'LocationF', '8', 'VIN1000014'),
('9', '2024-01-09', '2024-01-13', '0', '610', '100', 'LocationG', 'LocationH', '9', 'VIN1000015'),
('10', '2024-01-10', '2024-01-14', '0', '330', '100', 'LocationI', 'LocationJ', '10', 'VIN1000016'),
('11', '2024-01-11', '2024-01-15', '0', '440', '100', 'LocationA', 'LocationB', '11', 'VIN1000017'),
('12', '2024-01-12', '2024-01-16', '0', '370', '100', 'LocationC', 'LocationD', '12', 'VIN1000018'),
('13', '2024-01-13', '2024-01-17', '0', '520', '100', 'LocationE', 'LocationF', '13', 'VIN1000019'),
('14', '2024-01-14', '2024-01-18', '0', '405', '100', 'LocationG', 'LocationH', '14', 'VIN1000020'),
('15', '2024-01-15', '2024-01-19', '0', '480', '100', 'LocationI', 'LocationJ', '15', 'VIN1000021'),
('16', '2024-01-16', '2024-01-20', '0', '530', '100', 'LocationA', 'LocationB', '16', 'VIN1000022'),
('17', '2024-01-17', '2024-01-21', '0', '390', '100', 'LocationC', 'LocationD', '17', 'VIN1000023'),
('18', '2024-01-18', '2024-01-22', '0', '420', '100', 'LocationE', 'LocationF', '18', 'VIN1000024'),
('19', '2024-01-19', '2024-01-23', '0', '465', '100', 'LocationG', 'LocationH', '19', 'VIN1000025'),
('20', '2024-01-20', '2024-01-24', '0', '350', '100', 'LocationI', 'LocationJ', '20', 'VIN1000026');

ALTER TABLE adg_invoice
ADD CONSTRAINT rentalservice_fk
FOREIGN KEY (rentalid)
REFERENCES adg_rentalservice(rentalid);

UPDATE adg_rentalservice
SET pickup_date = '2024-01-25', dropoff_date = '2024-01-28'
WHERE rentalid IN (21, 22, 23, 24);

UPDATE adg_rentalservice 
SET pickup_date = '2024-01-22', dropoff_date = '2024-01-24' 
WHERE rentalid = 1;


UPDATE adg_rentalservice 
SET dropoff_date = '2024-01-24' 
WHERE rentalid = 2;

UPDATE adg_rentalservice 
SET pickup_date = '2024-01-31', dropoff_date = '2024-02-03' 
WHERE rentalid = 3;

UPDATE adg_rentalservice 
SET pickup_date = '2024-01-20', dropoff_date = '2024-01-23' 
WHERE rentalid = 4;

UPDATE adg_rentalservice 
SET pickup_date = '2024-01-18', dropoff_date = '2024-01-22' 
WHERE rentalid = 5;

UPDATE adg_rentalservice 
SET pickup_date = '2024-02-01', dropoff_date = '2024-02-05' 
WHERE rentalid = 6;

UPDATE adg_rentalservice 
SET pickup_date = '2024-01-31', dropoff_date = '2024-02-04' 
WHERE rentalid = 7;

UPDATE adg_rentalservice 
SET pickup_date = '2024-01-15', dropoff_date = '2024-01-19' 
WHERE rentalid = 8;

UPDATE adg_rentalservice 
SET pickup_date = '2024-02-02', dropoff_date = '2024-02-06' 
WHERE rentalid = 9;

UPDATE adg_rentalservice 
SET pickup_date = '2024-01-16', dropoff_date = '2024-01-24' 
WHERE rentalid = 10;

UPDATE adg_rentalservice 
SET pickup_date = '2024-01-21', dropoff_date = '2024-01-24' 
WHERE rentalid = 11;

UPDATE adg_rentalservice 
SET pickup_date = '2024-01-21', dropoff_date = '2024-02-24' 
WHERE rentalid = 12;

-- Example SQL statements to adjust rental records
-- These will change the rental periods so that some vehicles are available between January 2nd and January 15th, 2024.

UPDATE adg_rentalservice
SET pickup_date = '2023-12-20', dropoff_date = '2023-12-30'
WHERE pickup_date BETWEEN '2024-01-02' AND '2024-01-15';

UPDATE adg_rentalservice
SET pickup_date = '2024-01-16', dropoff_date = '2024-01-25'
WHERE dropoff_date BETWEEN '2024-01-02' AND '2024-01-15';

UPDATE adg_rentalservice
SET dropoff_date = '2024-01-01'
WHERE dropoff_date BETWEEN '2024-01-02' AND '2024-01-15';

-- Adjusting bookings starting near the end of the target period
UPDATE adg_rentalservice
SET pickup_date = '2024-01-16'
WHERE pickup_date BETWEEN '2024-01-02' AND '2024-01-15';

-- Adjusting bookings that fully encompass the target period
UPDATE adg_rentalservice
SET pickup_date = '2023-12-26', dropoff_date = '2023-12-31'
WHERE pickup_date < '2024-01-02' AND dropoff_date > '2024-01-15';

-- Example SQL to add new premium SUVs in Los Angeles office
INSERT INTO adg_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid)
VALUES 
('VIN1000056', 'Tesla', 'Model X', 2023, 'LIC056', 1, 2, 15),
('VIN1000057', 'Audi', 'Q7', 2023, 'LIC057', 1, 2, 15),
('VIN1000058', 'BMW', 'X5', 2023, 'LIC058', 1, 2, 15);

-- Adjusting pickup dates to be within January 1st to 5th, 2024
UPDATE adg_rentalservice
SET pickup_date = '2024-01-01'
WHERE pickup_date < '2024-01-01';

UPDATE adg_rentalservice
SET pickup_date = '2024-01-05'
WHERE pickup_date > '2024-01-05' AND pickup_date < '2024-01-11';

-- Adjusting pickup dates to be within January 11th to 13th, 2024
UPDATE adg_rentalservice
SET pickup_date = '2024-01-11'
WHERE pickup_date > '2024-01-05' AND pickup_date < '2024-01-11';

UPDATE adg_rentalservice
SET pickup_date = '2024-01-13'
WHERE pickup_date > '2024-01-13';

INSERT INTO adg_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid) VALUES
('VIN2000001', 'Toyota', 'Camry', 2023, 'PLT001', 1, 2, 13),
('VIN2000002', 'Toyota', 'Camry', 2023, 'PLT002', 3, 2, 13),
('VIN2000003', 'Toyota', 'Camry', 2023, 'PLT003', 4, 2, 13),
('VIN2000004', 'Toyota', 'Camry', 2023, 'PLT004', 5, 2, 13),
('VIN2000005', 'Toyota', 'Camry', 2023, 'PLT005', 6, 2, 13),
('VIN2000006', 'Toyota', 'Camry', 2023, 'PLT006', 8, 2, 13),
('VIN2000007', 'Toyota', 'Camry', 2023, 'PLT007', 9, 2, 13),
('VIN2000008', 'Toyota', 'Camry', 2023, 'PLT008', 11, 2, 13);


INSERT INTO adg_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid) VALUES
('VIN3000001', 'Honda', 'Civic', 2023, 'CVC001', 1, 2, 14),
('VIN3000002', 'Honda', 'Civic', 2023, 'CVC002', 3, 2, 14),
('VIN3000003', 'Honda', 'Civic', 2023, 'CVC003', 4, 2, 14),
('VIN3000004', 'Honda', 'Civic', 2023, 'CVC004', 5, 2, 14),
('VIN3000005', 'Honda', 'Civic', 2023, 'CVC005', 6, 2, 14),
('VIN3000006', 'Honda', 'Civic', 2023, 'CVC006', 8, 2, 14),
('VIN3000007', 'Honda', 'Civic', 2023, 'CVC007', 9, 2, 14),
('VIN3000008', 'Honda', 'Civic', 2023, 'CVC008', 11, 2, 14);

INSERT INTO adg_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid) VALUES
('VIN4000001', 'Ford', 'Fusion', 2023, 'FSN001', 1, 2, 15),
('VIN4000002', 'Ford', 'Fusion', 2023, 'FSN002', 3, 2, 15),
('VIN4000003', 'Ford', 'Fusion', 2023, 'FSN003', 4, 2, 15),
('VIN4000004', 'Ford', 'Fusion', 2023, 'FSN004', 5, 2, 15),
('VIN4000005', 'Ford', 'Fusion', 2023, 'FSN005', 6, 2, 15),
('VIN4000006', 'Ford', 'Fusion', 2023, 'FSN006', 8, 2, 15),
('VIN4000007', 'Ford', 'Fusion', 2023, 'FSN007', 9, 2, 15),
('VIN4000008', 'Ford', 'Fusion', 2023, 'FSN008', 11, 2, 15);

INSERT INTO adg_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid) VALUES
('VIN5000001', 'Chevrolet', 'Impala', 2023, 'IMP001', 1, 2, 16),
('VIN5000002', 'Chevrolet', 'Impala', 2023, 'IMP002', 3, 2, 16),
('VIN5000003', 'Chevrolet', 'Impala', 2023, 'IMP003', 4, 2, 16),
('VIN5000004', 'Chevrolet', 'Impala', 2023, 'IMP004', 5, 2, 16),
('VIN5000005', 'Chevrolet', 'Impala', 2023, 'IMP005', 6, 2, 16),
('VIN5000006', 'Chevrolet', 'Impala', 2023, 'IMP006', 8, 2, 16),
('VIN5000007', 'Chevrolet', 'Impala', 2023, 'IMP007', 9, 2, 16),
('VIN5000008', 'Chevrolet', 'Impala', 2023, 'IMP008', 11, 2, 16);

INSERT INTO adg_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid) VALUES
('VIN6000001', 'Volkswagen', 'Golf', 2023, 'GLF001', 1, 2, 18),
('VIN6000002', 'Volkswagen', 'Golf', 2023, 'GLF002', 3, 2, 18),
('VIN6000003', 'Volkswagen', 'Golf', 2023, 'GLF003', 4, 2, 18),
('VIN6000004', 'Volkswagen', 'Golf', 2023, 'GLF004', 5, 2, 18),
('VIN6000005', 'Volkswagen', 'Golf', 2023, 'GLF005', 6, 2, 18),
('VIN6000006', 'Volkswagen', 'Golf', 2023, 'GLF006', 8, 2, 18),
('VIN6000007', 'Volkswagen', 'Golf', 2023, 'GLF007', 9, 2, 18),
('VIN6000008', 'Volkswagen', 'Golf', 2023, 'GLF008', 11, 2, 18);

INSERT INTO adg_vehicle (vin, vehiclemake, vehiclemodel, vehicleyear, licenseplate, office_id, statusid, classid) VALUES
('VIN7000001', 'Cadillac', 'Escalade', 2023, 'ESD001', 1, 2, 21),
('VIN7000002', 'Cadillac', 'Escalade', 2023, 'ESD002', 3, 2, 21),
('VIN7000003', 'Cadillac', 'Escalade', 2023, 'ESD003', 4, 2, 21),
('VIN7000004', 'Cadillac', 'Escalade', 2023, 'ESD004', 5, 2, 21),
('VIN7000005', 'Cadillac', 'Escalade', 2023, 'ESD005', 6, 2, 21),
('VIN7000006', 'Cadillac', 'Escalade', 2023, 'ESD006', 8, 2, 21),
('VIN7000007', 'Cadillac', 'Escalade', 2023, 'ESD007', 9, 2, 21),
('VIN7000008', 'Cadillac', 'Escalade', 2023, 'ESD008', 11, 2, 21);

ALTER TABLE ADG_PAYMENT DROP FOREIGN KEY card_details_fk;

ALTER TABLE ADG_CARD_DETAILS
MODIFY COLUMN cardid int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE ADG_PAYMENT ADD CONSTRAINT card_details_fk FOREIGN KEY (cardid) REFERENCES ADG_CARD_DETAILS(cardid);


ALTER TABLE adg_invoice DROP FOREIGN KEY rentalservice_fk;
ALTER TABLE adg_rentalservice MODIFY rentalid int(11) AUTO_INCREMENT;
ALTER TABLE adg_invoice ADD CONSTRAINT rentalservice_fk FOREIGN KEY (rentalid) REFERENCES adg_rentalservice(rentalid);

ALTER TABLE ADG_customer MODIFY cust_fname VARCHAR(20) NULL;
ALTER TABLE ADG_customer MODIFY cust_lname VARCHAR(30) NULL;

```

If you encounter error 1558:
Solution
(MacOs) I ran mysql_upgrade in the terminal from:
/Applications/XAMPP/xamppfiles/bin
using:
sudo ./mysql_upgrade



