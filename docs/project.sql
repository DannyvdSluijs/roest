CREATE TABLE Invoice (
    id SERIAL,
    date DATE,
    paid BOOLEAN DEFAULT false,
    reminder INTEGER DEFAULT 0
);

CREATE TABLE Appointment (
    id SERIAL,
    therapyId INTEGER NOT NULL,
    therapistId INTEGER NOT NULL,
    customerId INTEGER NOT NULL,
    invoiceId INTEGER,
    duration FLOAT,
    date DATE
);

CREATE TABLE Customer (
    id SERIAL,
    name VARCHAR(255),
    address VARCHAR(255),
    postalCode VARCHAR(255),
    city VARCHAR(255),
    telephone VARCHAR(20)
);

CREATE TABLE InsuranceAgency (
    id SERIAL,
    name VARCHAR(255),
    address VARCHAR(255),
    postalCode VARCHAR(255),
    city VARCHAR(255)
);

CREATE TABLE CustomerInsurance (
    customerId INTEGER,
    insuranceAgencyId INTEGER,
    polisNumber VARCHAR(255),
    fromDate DATE,
    PRIMARY KEY (customerId, insuranceAgencyId, fromDate)
);

CREATE TABLE Therapy (
    id SERIAL,
    name VARCHAR(255),
    active BOOLEAN DEFAULT true
);

CREATE TABLE TherapyPrice (
    therapyId INTEGER,
    pricePerHour FLOAT,
    fromDate DATE,
    PRIMARY KEY (therapyId, fromDate)
);

CREATE TABLE Therapist (
    id SERIAL,
    name VARCHAR(255),
    active BOOLEAN DEFAULT true
);

CREATE TABLE BusinessRules (
    `key` VARCHAR(32),
    `value` VARCHAR(32),
    fromDate DATE,
    PRIMARY KEY (`key`, fromDate)
);

