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

insert into korisnik (prezime,ime,email) values
('Jakopec', 'Tomislav','tjakopec@gmail.com'),
('Ereš','Mirko','mirko.eres1@gmail.com'),
('Filipović','Srđan','srdjanfilipovic991@gmail.com'),
('Grbeša','Antonio','agrbesa995@gmail.com'),
('Ivanšić','Ivan','ivan.ivansic@sdfgroup.com'),
('Klarić','Ines','klaricnes@gmail.com'),
('Kožić','Borna','borna.kozic2@gmail.com'),
('Kožić','Sven','svenkozic@hotmail.com'),
('Kucelj','Valentina','valentina.kucelj@gmail.com'),
('Luketić','Darko','oninator@gmail.com'),
('Mikić','Marijan','marijan.mikic@icloud.com'),
('Raguž','Gabrijela','gabrijela.ragu@gmail.com'),
('Lalić','Ivana','ilalic110@gmail.com');

insert into zanr(naziv,opis) values 
('Akcija','Akcijski film je filmski žanr u kojem akcijske sekvence, poput borbe, kaskaderskih scena, 
borilačkih vještina, automobilskih potjera ili eksplozija, imaju prednost pred elementima kao što su 
karakterizacija ili kompleksna priča. Akcija obično uključuje individualne napore heroja, što je u 
suprotnosti s većinom ratnih filmova.'),
('Avantura','Pustolovni ili avanturistički film (eng. Adventure film) je žanr igranog filma koji 
obiluje akcijom, potragom i egzotičnim lokacijama. Blizak je žanru akcijskog filma, a dijeli poveznice 
i s nekim drugim žanrovima, poput znanstveno-fantastičnog ili ratnog filma.'),
('Komedija','Komedija je vrsta drame, odlikuje se veselim sadržajem,
crta smiješne strane ljudskog života i ljudi, ismijava njihove nedostatke i mane. 
Komedija se, kao i tragedija, razvila u antičkoj Grčkoj. U početku je označavala 
pjesmu komosa, tj. grupe mladića koji su na otvorenim prostorima izvodili šale na 
račun svojih sugrađana. Cilj komedije je nasmijati čitatelje i gledatelje'),
('Drama','Dramski film je filmski žanr koji ponajviše ovisi o unutarnjem razvoju 
stvarnih likova koji se suočavaju s emocionalnim temama. Dramske teme kao što su 
alkoholizam, narkomanija, rasne predrasude, religijska netolerancija, siromaštvo, 
kriminal i korupcija stavljaju likove u sukob sa njima samima, drugima, društvom 
i čak prirodnim fenomenima. Ovaj filmski žanr u suprotnosti je s akcijskim filmom,
koji se oslanja na dinamičnu akciju i fizički sukob, ali i na površnu karakterizaciju.
Svi filmski žanrovi mogu uključivati dramske elemente, ali obično se filmovi koji se 
smatraju dramskim fokusiraju uglavnom na dramu glavnog sukoba.'),
('Fantazija','Fantastika je oblik spekulativne fikcije koji za razliku od naučne fantastike, 
koja čvrsto poštuje današnje ili buduće fizikalne zakonitosti 
(u budućnosti će možda biti moguć let brzinama većim od brzine svjetlosti), 
ne vodi previše brigu o fizici, nego čitatelja uvodi u svijet vila, čarobnjaka i 
magije.'),
('Horor','Horor (čiji naziv dolazi od engleske riječi horror, užas) 
ili strava je naziv koji se danas koristi za žanr u fiktivnim djelima 
(kao što su književnost, likovna umjetnost, strip, film, video-igre), a 
čiji autori nastoje izazvati osjećanja straha, uznemirenja i nelagode kod 
njihovih konzumenata. Kao najčešći izvor neugodnih osjećaja jest uvođenje 
nekog ili nečeg zlog - a ponekad neobjašnjivog i nerazumljivog - elementa 
natprirodnog porijekla u "normalnu" ljudsku svakodnevnicu. Od 1960-ih se izraz 
horor koristi i za djela s morbidnim, neugodnim temama i snažnom emocionalnom 
napetošću. Horor se ponekad prepliće s djelima naučne fantastike, kao i fantastike 
općenito, s kojima zajedno čini poseban nad-žanr spekulativne fikcije. Za izraz 
horor se u prošlosti koristio izraz strava i užas, odnosno strava.'),
('Romantika','Romantični film ili ljubavni film je filmski žanr koji je fokusiran 
na strast, emocije i odnos privrženosti između glavnih likova. Kod ljubavnih 
filmova, glavna tema zapleta je ljubavna priča ili traganje za ljubavlju. 
Povremeno se glavni junaci suočavaju sa preprekama kao što su finansije, bolest, 
razni oblici diskriminacije, psihološke barijere ili pretnje porodica koje ne 
odobravaju njihovu vezu. Zaplet u ljubavnim filmovima mogu činiti i svakodnevne 
životne tenzije i iskušenja, neverstvo, kao i karakterne i druge razlike između 
glavnih junaka.'),
('Triler','Triler (eng. thriller) je široki žanr koji obuhvaća film, književnost 
i televiziju. Triler karakteriziraju dinamika, neprestana akcija i vješti junaci 
koji moraju osjetiti planove moćnijih i bolje opremljenih zlikovaca. Često se 
koriste književna sredstva kao što su napetost, skretanje pozornosti i napeti 
završetci. Trileri se često preklapaju s misterioznim pričama, ali se odlikuju 
strukturom svojih priča. U trileru, junak mora poremetiti planove neprijatelja, 
a ne riješiti zločin koji se već dogodio. Trileri se često javljaju na mnogo višoj 
razini: zločini koji se moraju spriječiti su serijska ili masovna ubojstva, terorizam, 
atentati ili zbacivanja vlada. Izazov i nasilne konfrontacije su standardni elementi 
priče. Dok je vrhunac misterije kad se ona riješi, vrhunac trilera je kad junak konačno 
porazi zlikovca, spašavajući vlastiti život, a često i živote drugih.');

INSERT into dobavljac (naziv, homepage) values
('Crackle','www.sonycrackle.com'),
('Popcorn Flix','www.popcornflix.com'),
('Vudu Movies on Us','www.vudu.com'),
('Tubi TV','gdpr.tubi.tv'),
('Pluto TV','corporate.pluto.tv'),
('CONtv','www.contv.com'),
('SnagFilms','www.snagfilms.com'),
('Classic Cinema Online','www.classiccinemaonline.com'),
('Veoh','www.veoh.com'),
('YouTube (USA)','www.youtube.com'),
('Panda Streaming','pandastreaming.org');
