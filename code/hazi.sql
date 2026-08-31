drop database if exists hazi;

create database hazi
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;
    
use hazi;

create table bolt(
id int primary key auto_increment,
nev varchar(30),
cim varchar(30),
fonok varchar(20)
);

insert into bolt(nev, cim, fonok) values ("HM", "Kecskemét, Búza utca 3." , "Kelemen János");
insert into bolt(nev, cim, fonok) values ("Zara", "Budapest, Szép utca 1.", "Kovács Anna");
insert into bolt(nev, cim, fonok) values ("Basics", "Budapest, Arany utca 15.", "Kis Attila");

create table ruha(
id int primary key auto_increment,
nev varchar(30),
marka varchar(20),
kateg varchar(20),
db int
);

insert into ruha(nev, marka, kateg, db) values ("Sárga nyári ruci", "Divided", "ruha", 21);
insert into ruha(nev, marka, kateg, db) values ("Lenge póló", "Basic", "póló", 10);
insert into ruha(nev, marka, kateg, db) values ("Boyfriend farmer", "Divided", "nadrág", 150);
insert into ruha(nev, marka, kateg, db) values ("Kötött póló", "Demphsey" , "póló", 1);

create table rendeles(
id int primary key auto_increment,
	boltid int not null,
    ruhaid int not null,
    db int,
    datum date not null,
    keszdatum date,
    
    foreign key (boltid) references bolt(id),
    foreign key (ruhaid) references ruha(id)
    );
    
    insert into rendeles (boltid, ruhaid, db, datum,keszdatum) values (1, 1, 5, subdate(curdate(), 50),null);
    insert into rendeles (boltid, ruhaid, db, datum,keszdatum) values (3, 1, 10, subdate(curdate(), 40),null);
    insert into rendeles (boltid, ruhaid, db, datum,keszdatum) values (2, 4, 50, subdate(curdate(), 3),null);
    insert into rendeles (boltid, ruhaid, db, datum,keszdatum) values (1, 2, 20, subdate(curdate(), 11),null);
    insert into rendeles (boltid, ruhaid, db, datum,keszdatum) values (2, 3, 15, subdate(curdate(), 5),curdate());
    insert into rendeles (boltid, ruhaid, db, datum,keszdatum) values (2, 3, 11, subdate(curdate(), 5),curdate());
    
    
