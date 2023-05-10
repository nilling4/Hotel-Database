drop table reservations cascade constraints;
drop table reserves cascade constraints;
drop table roomContains cascade constraints;
drop table guest cascade constraints;
drop table hotel_owner cascade constraints;
drop table belongsTo;
drop table HotelOwns1 cascade constraints;
drop table HotelOwns2 cascade constraints;
drop table TransactionMadeTo1 cascade constraints;
drop table TransactionMadeTo2 cascade constraints;
drop table EmployeeManagesWorks cascade constraints;
drop table Receives cascade constraints;
drop table Checks_in1 cascade constraints;
drop table Checks_in2 cascade constraints;
drop table Keeps cascade constraints;
drop table Creates cascade constraints;
drop table Inventory1 cascade constraints;
drop table Inventory2 cascade constraints;

create table reservations
    (start_date varchar(20) not null,
    end_date varchar(40) not null,
    reservation_id int not null,
    primary key (reservation_id));
 
grant ALL PRIVILEGES on reservations to public;

-- !!! roomcontains1 and 2 ??

create table roomContains
    (room_number int not null,
	floor int not null,
	room_type varchar(20) not null,
	status varchar(20) not null,
	price int not null,
    primary key (room_number));
 
grant ALL PRIVILEGES on roomContains to public;

create table reserves
    (reservation_id int not null,
	room_number int not null,
    primary key (reservation_id, room_number),
	foreign key (reservation_id) references reservations
	    ON DELETE CASCADE,
    foreign key (room_number) references roomContains
        ON DELETE CASCADE);
 
grant ALL PRIVILEGES on reserves to public;

create table guest
    (guest_id int not null,
	card_number int not null,
	guest_name varchar(20) not null,
	email varchar(30) not null,
    primary key (guest_id));
 
grant select on guest to public;

create table hotel_owner
    (owner_name varchar(20) not null,
	email varchar(20) not null,
    primary key (owner_name));
 
grant select on hotel_owner to public;

create table belongsTo
    (belongsTo_id int not null,
	reservation_id int not null,
    primary key (belongsTo_id, reservation_id));
 
grant select on belongsTo to public;

CREATE TABLE HotelOwns1 (
location  CHAR(40) NOT NULL,
	name     CHAR(20), 
	PRIMARY KEY (location)
);

grant select on HotelOwns1 to public;

CREATE TABLE HotelOwns2 (
location  CHAR(40) NOT NULL,
	owner_name varchar(20) NOT NULL,
	hotel_id INTEGER,
	acquisition_date CHAR(20) NOT NULL,
	PRIMARY KEY (hotel_id),
FOREIGN KEY (location) REFERENCES HotelOwns1
	ON DELETE CASCADE,
FOREIGN KEY (owner_name) REFERENCES hotel_owner
	ON DELETE CASCADE
);

grant select on HotelOwns2 to public;

CREATE TABLE TransactionMadeTo1 (
amount INTEGER NOT NULL,
hours_worked INTEGER,
wage INTEGER, 
PRIMARY KEY(hours_worked, amount)
);

grant select on TransactionMadeTo1 to public;

CREATE TABLE TransactionMadeTo2 (
	id INTEGER PRIMARY KEY,
	transaction_date varchar(30),
	transaction_type varchar(20),
period varchar(60),
hotel_id INTEGER NOT NULL,
hours_worked INTEGER,
amount INTEGER NOT NULL,
FOREIGN KEY(hotel_id) REFERENCES HotelOwns2
	ON DELETE CASCADE,
FOREIGN KEY (hours_worked, amount) REFERENCES TransactionMadeTo1
	ON DELETE CASCADE
);

grant select on TransactionMadeTo2 to public;


-- what is this suppossed to reference??? !!! Manages or Manager?
CREATE TABLE EmployeeManagesWorks (
	employee_id INTEGER PRIMARY KEY,
	name CHAR(20),
	email CHAR(30),
    worker_type CHAR (20) NOT NULL,
	manager_id INTEGER NOT NULL,
	id INTEGER NOT NULL,
	FOREIGN KEY (manager_id) REFERENCES EmployeeManagesWorks(employee_id)
	ON DELETE CASCADE,
	FOREIGN KEY (id) REFERENCES HotelOwns2
	ON DELETE CASCADE,
	UNIQUE (email)
);

grant select on EmployeeManagesWorks to public;

CREATE TABLE Receives (
id INTEGER,
employee_id INTEGER,
PRIMARY KEY(id, employee_id),
FOREIGN KEY (id) REFERENCES TransactionMadeTo2
	ON DELETE CASCADE,
FOREIGN KEY (employee_id) REFERENCES EmployeeManagesWorks
	ON DELETE CASCADE
);

grant select on Receives to public;

CREATE TABLE Checks_in1(
	check_in_date CHAR(30) NOT NULL, 
	employee_id INTEGER,
PRIMARY KEY (check_in_date),
	FOREIGN KEY (employee_id) REFERENCES EmployeeManagesWorks
	ON DELETE CASCADE
);

grant select on Checks_in1 to public;

CREATE TABLE Checks_in2(
	check_in_date CHAR(30) NOT NULL,
	guest_id INTEGER,
PRIMARY KEY (guest_id),
	FOREIGN KEY (guest_id) REFERENCES Guest
	ON DELETE CASCADE,
	FOREIGN KEY (check_in_date) REFERENCES Checks_in1
	ON DELETE CASCADE
);

grant select on Checks_in2 to public;

CREATE TABLE Inventory1 (
amount INTEGER,
cost INTEGER,
inventory_type CHAR(20),
PRIMARY KEY(inventory_type, amount)
);

grant select on Inventory1 to public;

CREATE TABLE Inventory2 (
amount INTEGER,
id INTEGER PRIMARY KEY,
inventory_type CHAR(20),
FOREIGN KEY (inventory_type, amount) REFERENCES Inventory1
	ON DELETE CASCADE
);

grant select on Inventory2 to public;

CREATE TABLE Keeps (
	id INTEGER,
	employee_id INTEGER,
	PRIMARY KEY(id, employee_id),
	FOREIGN KEY (id) REFERENCES Inventory2
	ON DELETE CASCADE,
	FOREIGN KEY (employee_id) REFERENCES EmployeeManagesWorks
	ON DELETE CASCADE
);

grant select on Keeps to public;

CREATE TABLE Creates(
	Guest_id INTEGER,
	Reservation_id INTEGER,
	PRIMARY KEY(Guest_id, Reservation_id),
	FOREIGN KEY (Guest_id) REFERENCES Guest
	ON DELETE CASCADE,
	FOREIGN KEY (Reservation_id) REFERENCES Reservations
	ON DELETE CASCADE
);

grant select on Creates to public;

-- insert into reservations values
-- ('jan 1', 'jan 2', 1);

-- insert into reservations values
-- ('jan 4', 'jan 7', 6);

insert into reservations values
('jan 4', 'jan 7', 101234);

insert into reservations values
('jan 4', 'jan 7', 101235);

insert into reservations values
('jan 4', 'jan 7', 101236);

insert into reservations values
('jan 4', 'jan 7', 101237);

insert into reservations values
('jan 4', 'jan 7', 101238);

insert into roomContains values
(200, 2, 'single', 'vacant', 150);

insert into roomContains values
(201, 2, 'queen', 'vacant', 250);

insert into roomContains values
(202, 2, 'double', 'vacant', 200);

insert into roomContains values
(401, 4, 'single', 'vacant', 150);

insert into roomContains values
(501, 5, 'double', 'vacant', 200);

insert into roomContains values
(600, 6, 'queen', 'vacant', 250);

insert into roomContains values
(601, 6, 'double', 'vacant', 200);

insert into roomContains values
(602, 6, 'king', 'vacant', 300);

insert into roomContains values
(701, 7, 'double', 'vacant', 200);

insert into roomContains values
(801, 8, 'master', 'vacant', 350);

INSERT
INTO reserves(reservation_id, room_number)
VALUES (101234, 200);

INSERT
INTO reserves(reservation_id, room_number)
VALUES (101234, 201);

INSERT
INTO reserves(reservation_id, room_number)
VALUES (101234, 202);

INSERT
INTO reserves(reservation_id, room_number)
VALUES (101234, 401);

INSERT
INTO reserves(reservation_id, room_number)
VALUES (101235, 501);

INSERT
INTO reserves(reservation_id, room_number)
VALUES (101236, 600);

INSERT
INTO reserves(reservation_id, room_number)
VALUES (101236, 601);

INSERT
INTO reserves(reservation_id, room_number)
VALUES (101236, 602);

INSERT
INTO reserves(reservation_id, room_number)
VALUES (101237, 701);

INSERT
INTO reserves VALUES (101238, 801);

insert into guest values
('123456', '24429988', 'Henry Kim', 'walkingbuddies2002@gmail.com');

insert into guest values
('222222', '48603847', 'Benry Bim', 'zedandshen@gmail.com');

insert into guest values
('123457',  '66739853', 'Jenry Jim', 'yuumicarry@gmail.com');

insert into guest values
('444444', '12546434', 'Tenry Tim', 'thisisnotanemail@gmail.com');

insert into guest values
('666666', '89745676', 'Lenry Lim', 'impostersussy@gmail.com');

INSERT
INTO hotel_owner(owner_name, email)
VALUES ('Elon Musk', 'henry@gmail.com');

INSERT
INTO hotel_owner(owner_name, email)
VALUES ('Tarzan Man', 'noel@gmail.com');

INSERT
INTO hotel_owner(owner_name, email)
VALUES ('John Smith', 'babak@gmail.com');

INSERT
INTO hotel_owner(owner_name, email)
VALUES ('Heimerdinger Smith', 'henryJoe@gmail.com');

INSERT
INTO hotel_owner(owner_name, email)
VALUES ('Garen Darius', 'henryCam@gmail.com');

INSERT
INTO belongsTo VALUES (123455, 101234);

INSERT
INTO belongsTo VALUES (123456, 101235);

INSERT
INTO belongsTo VALUES (123457, 101236);

INSERT
INTO belongsTo VALUES (123458, 101237);

INSERT
INTO belongsTo VALUES (123459, 101238);

INSERT
INTO HotelOwns1 VALUES ('900 W Georgia St, Vancouver', 'Fairmont');

INSERT
INTO HotelOwns1(location, name)
VALUES ('5959 Student Union Blvd, Vancouver', 'Gage');

INSERT
INTO HotelOwns1(location, name)
VALUES ('783 Imagination Rd, Vancouver', 'Imagine Hotel');

INSERT
INTO HotelOwns1(location, name)
VALUES ('696 Lover Drive, Vancouver', 'Love Hotel');

INSERT
INTO HotelOwns1(location, name)
VALUES ('3204 Database St, China', 'Data Hotel');

INSERT
INTO HotelOwns2 VALUES ('900 W Georgia St, Vancouver', 'Elon Musk', 1234, 'October 10, 2004');

INSERT
INTO HotelOwns2 VALUES ('5959 Student Union Blvd, Vancouver', 'Tarzan Man', 3243, 'November 17, 1999');

INSERT
INTO HotelOwns2 VALUES ('783 Imagination Rd, Vancouver', 'John Smith', 2342, 'January 1, 2002');

INSERT
INTO HotelOwns2 VALUES ('696 Lover Drive, Vancouver', 'Heimerdinger Smith', 4532, 'July 23, 2005');

INSERT
INTO HotelOwns2 VALUES ('3204 Database St, China', 'Garen Darius', 8978, 'February 11, 1987');

INSERT
INTO TransactionMadeTo1(hours_worked, amount, wage)
VALUES (1, 10, 15);

INSERT
INTO TransactionMadeTo1(hours_worked, amount, wage)
VALUES (2, 11, 16);

INSERT
INTO TransactionMadeTo1(hours_worked, amount, wage)
VALUES (3, 12, 17);

INSERT
INTO TransactionMadeTo1(hours_worked, amount, wage)
VALUES (4, 13, 18);

INSERT
INTO TransactionMadeTo1(hours_worked, amount, wage)
VALUES (5, 14, 19);

INSERT
INTO TransactionMadeTo2 VALUES (1234, 'February 20, 2002', 'Room Payment', 'October, 10, 2002 - February, 10, 2002', 1234, 1, 10);

INSERT
INTO TransactionMadeTo2 VALUES (123456, 'February 21, 2002', 'Room Payment', 'October, 10, 2002 - February, 10, 2002', 3243, 2, 11);

INSERT
INTO TransactionMadeTo2 VALUES (123457,  'February 22, 2002', 'Cancellation Fee', 'October, 10, 2002 - February, 10, 2002', 3243, 3, 12);

INSERT
INTO TransactionMadeTo2 VALUES (123458,  'February 23, 2002', 'Cancellation Fee',  'October, 10, 2002 - February, 10, 2002', 1234, 4, 13);

INSERT
INTO TransactionMadeTo2 VALUES (123459,  'February 24, 2002', 'Cancellation Fee',  'October, 10, 2002 - February, 10, 2002', 3243, 5, 14);

INSERT
INTO Creates VALUES (123456, 101234);

INSERT
INTO Creates VALUES (222222, 101235);

INSERT
INTO Creates VALUES (222222, 101236);

INSERT
INTO Creates VALUES (444444, 101237);

INSERT
INTO Creates VALUES (666666, 101238);

INSERT
INTO Inventory1 VALUES (100, 1000, 'chairs');

INSERT
INTO Inventory1 VALUES (50, 100, 'spoons');

INSERT
INTO Inventory1 VALUES (100, 80, 'forks');

INSERT
INTO Inventory1 VALUES (100, 100, 'knives');

INSERT
INTO Inventory1 VALUES (50, 700, 'blankets');

INSERT
INTO Inventory2 VALUES (100, 12345678, 'chairs');

INSERT
INTO Inventory2 VALUES (50, 58349544, 'spoons');

INSERT
INTO Inventory2 VALUES (100, 54982365, 'forks');

INSERT
INTO Inventory2 VALUES (100, 45217893, 'knives');

INSERT
INTO Inventory2 VALUES (50, 99875757, 'blankets');

INSERT
INTO EmployeeManagesWorks VALUES (123455,'Alan', 'alan@email.com', 'Kitchen', 123455, 1234);

INSERT
INTO EmployeeManagesWorks VALUES (123456,'Bob', 'bob@email.com', 'Front_Desk', 123455, 2342);

INSERT
INTO EmployeeManagesWorks VALUES (123457,'Cole', 'cole@email.com', 'House_Keeping', 123455, 3243);

INSERT
INTO EmployeeManagesWorks VALUES (123458,'David', 'david@email.com', 'Manager', 123455, 4532);

INSERT
INTO EmployeeManagesWorks VALUES (123459,'Elliot', 'elliot@email.com', 'Kitchen', 123455, 8978);

INSERT
INTO Keeps VALUES (12345678, 123455);

INSERT
INTO Keeps VALUES (58349544, 123456);

INSERT
INTO Keeps VALUES (54982365, 123457);

INSERT
INTO Keeps VALUES (45217893, 123458);

INSERT
INTO Keeps VALUES (99875757, 123459);

INSERT
INTO Receives VALUES(123456, 123455);

INSERT
INTO Receives VALUES(123456, 123456);

INSERT
INTO Receives VALUES(123457, 123457);

INSERT
INTO Receives VALUES(123458, 123458);

INSERT
INTO Receives VALUES(123459, 123459);

INSERT
INTO Checks_in1 VALUES ('October 5, 2021', 123455);

INSERT
INTO Checks_in1 VALUES ('October 6, 2021', 123456);

INSERT
INTO Checks_in1 VALUES ('October 7, 2021', 123456);

INSERT
INTO Checks_in1 VALUES ('October 8, 2021', 123457);

INSERT
INTO Checks_in1 VALUES ('October 9, 2021', 123457);

INSERT
INTO Checks_in2 VALUES ('October 5, 2021', 123456);

INSERT
INTO Checks_in2 VALUES ('October 6, 2021', 222222);

INSERT
INTO Checks_in2 VALUES ('October 7, 2021', 123457);

INSERT
INTO Checks_in2 VALUES ('October 8, 2021', 444444);

INSERT
INTO Checks_in2 VALUES ('October 9, 2021', 666666);







