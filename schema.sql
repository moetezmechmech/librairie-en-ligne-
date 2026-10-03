SET NAMES utf8mb4;
CREATE DATABASE IF NOT EXISTS scolaris CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE scolaris;
CREATE TABLE ecole(id INT AUTO_INCREMENT PRIMARY KEY,nom VARCHAR(120) NOT NULL,ville VARCHAR(60) DEFAULT 'Nabeul');
CREATE TABLE niveau(id INT AUTO_INCREMENT PRIMARY KEY,libelle_fr VARCHAR(60) NOT NULL,libelle_ar VARCHAR(60));
CREATE TABLE categorie(id INT AUTO_INCREMENT PRIMARY KEY,nom_fr VARCHAR(60) NOT NULL,nom_ar VARCHAR(60));
CREATE TABLE produit(id INT AUTO_INCREMENT PRIMARY KEY,categorie_id INT NOT NULL,type ENUM('livre','fourniture') NOT NULL,
 titre_fr VARCHAR(160) NOT NULL,titre_ar VARCHAR(160),isbn VARCHAR(20),prix DECIMAL(8,3) NOT NULL,stock INT NOT NULL DEFAULT 0,
 FOREIGN KEY(categorie_id) REFERENCES categorie(id));
CREATE TABLE liste_scolaire(id INT AUTO_INCREMENT PRIMARY KEY,ecole_id INT NOT NULL,niveau_id INT NOT NULL,annee VARCHAR(9) NOT NULL,
 UNIQUE(ecole_id,niveau_id,annee),FOREIGN KEY(ecole_id) REFERENCES ecole(id),FOREIGN KEY(niveau_id) REFERENCES niveau(id));
CREATE TABLE liste_scolaire_ligne(id INT AUTO_INCREMENT PRIMARY KEY,liste_id INT NOT NULL,produit_id INT NOT NULL,quantite INT NOT NULL DEFAULT 1,
 FOREIGN KEY(liste_id) REFERENCES liste_scolaire(id),FOREIGN KEY(produit_id) REFERENCES produit(id));
CREATE TABLE commande(id INT AUTO_INCREMENT PRIMARY KEY,client_nom VARCHAR(100) NOT NULL,client_tel VARCHAR(20) NOT NULL,
 mode ENUM('livraison','retrait') NOT NULL,adresse VARCHAR(255),
 statut ENUM('en_attente','payee','prete','livree','annulee') NOT NULL DEFAULT 'en_attente',
 total DECIMAL(10,3) NOT NULL,cree_le TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE commande_ligne(id INT AUTO_INCREMENT PRIMARY KEY,commande_id INT NOT NULL,produit_id INT NOT NULL,quantite INT NOT NULL,
 prix_unitaire DECIMAL(8,3) NOT NULL,FOREIGN KEY(commande_id) REFERENCES commande(id),FOREIGN KEY(produit_id) REFERENCES produit(id));
CREATE TABLE paiement(id INT AUTO_INCREMENT PRIMARY KEY,commande_id INT NOT NULL,passerelle VARCHAR(30),
 reference_transaction VARCHAR(80),statut VARCHAR(20),montant DECIMAL(10,3),FOREIGN KEY(commande_id) REFERENCES commande(id));
-- Données d'exemple : à remplacer par vos vrais produits et listes
INSERT INTO categorie(nom_fr,nom_ar) VALUES('Livres scolaires','كتب مدرسية'),('Papeterie','أدوات مكتبية');
INSERT INTO niveau(libelle_fr,libelle_ar) VALUES('1ère année','السنة الأولى'),('2ème année','السنة الثانية'),('3ème année','السنة الثالثة');
INSERT INTO ecole(nom) VALUES('École exemple A'),('École exemple B');
INSERT INTO produit(categorie_id,type,titre_fr,titre_ar,prix,stock) VALUES
(1,'livre','Cahier d''exercices de français','كراس تمارين فرنسية',9.500,40),
(1,'livre','Manuel de mathématiques','كتاب الرياضيات',14.000,25),
(1,'livre','Livre d''anglais','كتاب الإنجليزية',18.500,30),
(2,'fourniture','Cahier 96 pages','كراس 96 صفحة',2.800,200),
(2,'fourniture','Trousse complète','مقلمة كاملة',12.000,60),
(2,'fourniture','Stylos bleus (lot de 4)','أقلام زرقاء (4)',3.200,120);
INSERT INTO liste_scolaire(ecole_id,niveau_id,annee) VALUES(1,1,'2026-2027'),(2,2,'2026-2027');
INSERT INTO liste_scolaire_ligne(liste_id,produit_id,quantite) VALUES(1,1,1),(1,2,1),(1,4,4),(1,6,1),(2,3,1),(2,2,1),(2,5,1);
