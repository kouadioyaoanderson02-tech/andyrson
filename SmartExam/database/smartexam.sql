CREATE DATABASE smartexam_db;
USE smartexam_db;

    CREATE TABLE utilisateurs (

    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100),
    email VARCHAR(150) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('ETUDIANT','ADMIN') DEFAULT 'ETUDIANT',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);
CREATE TABLE concours (

    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);
CREATE TABLE matieres (

    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    concours_id INT NOT NULL,
    CONSTRAINT fk_matiere_concours
    FOREIGN KEY (concours_id)
    REFERENCES concours(id)
    ON DELETE CASCADE

);
CREATE TABLE cours (

    id INT AUTO_INCREMENT PRIMARY KEY,

    titre VARCHAR(200) NOT NULL,
    contenu LONGTEXT,
    fichier VARCHAR(255),
    matiere_id INT NOT NULL,
    CONSTRAINT fk_cours_matiere
    FOREIGN KEY (matiere_id)
    REFERENCES matieres(id)

    ON DELETE CASCADE

);
CREATE TABLE examens (

    id INT AUTO_INCREMENT PRIMARY KEY,

    titre VARCHAR(200) NOT NULL,
    duree INT NOT NULL,
    niveau VARCHAR(50),
    concours_id INT NOT NULL,
    CONSTRAINT fk_examen_concours
    FOREIGN KEY (concours_id)
    REFERENCES concours(id)
    ON DELETE CASCADE

);
CREATE TABLE questions (

    id INT AUTO_INCREMENT PRIMARY KEY,

    question TEXT NOT NULL,
    choix_a VARCHAR(255),
    choix_b VARCHAR(255),
    choix_c VARCHAR(255),
    choix_d VARCHAR(255),
    bonne_reponse CHAR(1) NOT NULL,
    explication TEXT,
    matiere_id INT NOT NULL,
    CONSTRAINT fk_question_matiere
    FOREIGN KEY (matiere_id)
    REFERENCES matieres(id)
    ON DELETE CASCADE

);
CREATE TABLE examen_questions (

    id INT AUTO_INCREMENT PRIMARY KEY,
    examen_id INT NOT NULL,
    question_id INT NOT NULL,
    CONSTRAINT fk_examen_question_examen
    FOREIGN KEY (examen_id)
    REFERENCES examens(id)
    ON DELETE CASCADE,
    CONSTRAINT fk_examen_question_question
    FOREIGN KEY (question_id)
    REFERENCES questions(id)
    ON DELETE CASCADE

);
CREATE TABLE participations (

    id INT AUTO_INCREMENT PRIMARY KEY,

    utilisateur_id INT NOT NULL,
    examen_id INT NOT NULL,
    score INT,
    date_passage TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_participation_utilisateur
    FOREIGN KEY (utilisateur_id)
    REFERENCES utilisateurs(id)
    ON DELETE CASCADE,
    CONSTRAINT fk_participation_examen
    FOREIGN KEY (examen_id)
    REFERENCES examens(id)
    ON DELETE CASCADE

);
CREATE TABLE reponses (

    id INT AUTO_INCREMENT PRIMARY KEY,

    participation_id INT NOT NULL,
    question_id INT NOT NULL,
    reponse_donnee CHAR(1),
    est_correct BOOLEAN,
    CONSTRAINT fk_reponse_participation
    FOREIGN KEY (participation_id)
    REFERENCES participations(id)
    ON DELETE CASCADE,
    CONSTRAINT fk_reponse_question
    FOREIGN KEY (question_id)
    REFERENCES questions(id)
    ON DELETE CASCADE

);











