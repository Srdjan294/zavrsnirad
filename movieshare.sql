drop database if exists movieshare;
create database movieshare;
use movieshare;

create table operater(
    sifra   int not null primary key auto_increment,
    email   varchar(50) not null,
    lozinka char(60) not null,
    ime     varchar(50) not null,
    prezime varchar(50) not null,
    uloga   varchar(10) not null
);

# admin@edunova.hr a 
# oper@edunova.hr o
insert into operater(email,lozinka,ime,prezime,uloga) values 
('oper@edunova.hr','$2y$10$m/4kVMvKhI2Wp3YLsW.8e.VMHQuC7Fdy8KYMUMykrjM2T2XMjZBCq',
'Operater', 'Edunova', 'oper'),
('admin@edunova.hr','$2y$10$ZpzbFNHRpWR6LjgrlucAmeGoMXI1IrHXvS.Eud71pyelM444HLrw.',
'Admin', 'Edunova', 'admin');


create table film(
    sifra int not null primary key auto_increment,
    naziv varchar(50) not null,
    zanr int,
    ime_redatelja varchar(50),
    prezime_redatelja varchar(50),
    ime_glavnog_glumca varchar(50),
    prezime_glavnog_glumca varchar(50),
    trajanje int,
    dobavljac int,
    godina_izlaska datetime
);

create table zanr(
    sifra int not null primary key auto_increment,
    naziv varchar(50) not null,
    opis text
);

create table dobavljac(
    sifra int not null primary key auto_increment,
    naziv varchar(50),
    homepage varchar(50)
);

create table gledanje(
    sifra int not null primary key auto_increment,
    film int,
    korisnik int,
    vrijeme_gledanja datetime
);

create table korisnik(
    sifra int not null primary key auto_increment,
    ime varchar(50),
    prezime varchar(50),
    datum_rodenja datetime,
    korisnicko_ime varchar(50),
    adresa varchar(50),
    email varchar(50),
    broj_mobitela varchar(20)
);

alter table film add foreign key (zanr) references zanr(sifra);
alter table film add foreign key (dobavljac) references dobavljac(sifra);

alter table gledanje add foreign key (film) references film(sifra);
alter table gledanje add foreign key (korisnik) references korisnik(sifra);