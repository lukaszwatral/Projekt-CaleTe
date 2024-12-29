CREATE TABLE Wydzial (
     id INTEGER PRIMARY KEY AUTOINCREMENT,
     nazwa TEXT NOT NULL,
     skrot TEXT NOT NULL
);

CREATE TABLE Sala_z_budynkiem (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    budynek_sala TEXT NOT NULL,
    wydzial_id INTEGER NOT NULL,
    FOREIGN KEY (wydzial_id) REFERENCES Wydzial(id)
);

CREATE TABLE Przedmiot (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nazwa TEXT NOT NULL,
    opis TEXT NOT NULL,
    sala_id INTEGER NOT NULL,
    grupa_id INTEGER NOT NULL,
    FOREIGN KEY (sala_id) REFERENCES Sala_z_budynkiem(id),
);

CREATE TABLE Grupa (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nazwa TEXT NOT NULL,
);

CREATE TABLE Grupa_Przedmiot (
    grupa_id INTEGER NOT NULL,
    przedmiot_id INTEGER NOT NULL,
    FOREIGN KEY (grupa_id) REFERENCES Grupa(id),
    FOREIGN KEY (przedmiot_id) REFERENCES Przedmiot(id)
);

CREATE TABLE Wykladowca (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    imie TEXT NOT NULL,
    nazwisko TEXT NOT NULL,
    tytul TEXT NOT NULL,
    zastepca text,
    wydzial_id INTEGER NOT NULL,
    przedmiot_id INTEGER NOT NULL,
    FOREIGN KEY (wydzial_id) REFERENCES Wydzial(id),
    FOREIGN KEY (przedmiot_id) REFERENCES Przedmiot(id)
);

CREATE TABLE Student (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    album INTEGER NOT NULL,
    grupa_id INTEGER NOT NULL,
    wydzial_id INTEGER NOT NULL,
    FOREIGN KEY (grupa_id) REFERENCES Grupa(id),
    FOREIGN KEY (wydzial_id) REFERENCES Wydzial(id)
);
