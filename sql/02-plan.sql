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

CREATE TABLE Tok_studiow (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    typ TEXT NOT NULL, --licencjackie, magisterskie, doktoranckie
    tryb TEXT NOT NULL, --stacjonarne, niestacjonarne
    typ_skrot TEXT NOT NULL,
    tryb_skrot TEXT NOT NULL
);

CREATE TABLE Przedmiot (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nazwa TEXT NOT NULL,
--     opis TEXT NOT NULL,
    tok_studiow_id INTEGER NOT NULL,
    FOREIGN KEY (tok_studiow_id) REFERENCES Tok_studiow(id)
);

CREATE TABLE Grupa (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nazwa TEXT NOT NULL
);

CREATE TABLE Student (
    id INTEGER PRIMARY KEY --id to numer albumu
);

CREATE TABLE Grupa_Student(
    grupa_id INTEGER NOT NULL,
    student_id INTEGER NOT NULL,
    FOREIGN KEY (grupa_id) REFERENCES Grupa(id),
    FOREIGN KEY (student_id) REFERENCES Student(id)
);

CREATE TABLE Wykladowca (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nazwisko_imie TEXT NOT NULL
--     imie TEXT NOT NULL,
--     nazwisko TEXT NOT NULL,
--     tytul TEXT NOT NULL,
--     zastepca text,
);

CREATE TABLE Zajecia(
    id INTEGER PRIMARY KEY,
--     opis
    data_start TEXT NOT NULL,
    data_koniec TEXT NOT NULL,
    zastepca TEXT,
    wykladowca_id INTEGER NOT NULL,
    wydzial_id INTEGER NOT NULL,
    grupa_id INTEGER NOT NULL,
    tok_studiow_id INTEGER NOT NULL,
    FOREIGN KEY (wykladowca_id) REFERENCES Wykladowca(id),
    FOREIGN KEY (wydzial_id) REFERENCES Wydzial(id),
    FOREIGN KEY (grupa_id) REFERENCES Grupa(id),
    FOREIGN KEY (tok_studiow_id) REFERENCES Tok_studiow(id)
)