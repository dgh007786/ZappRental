# ZappRental
 DBMS Project Fall 2023

## Code to Set-up Database in Mysql

Mysql for setting up:

First create schema name it adg_car_rental.code:
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

If you encounter error 1558:
Solution
(MacOs) I ran mysql_upgrade in the terminal from:
/Applications/XAMPP/xamppfiles/bin
using:
sudo ./mysql_upgrade



