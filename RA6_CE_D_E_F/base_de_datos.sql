CREATE DATABASE IF NOT EXISTS control_accesos
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE control_accesos;

CREATE TABLE logins (
  usuario VARCHAR(50) PRIMARY KEY,
  passwd  VARCHAR(32) NOT NULL
);

CREATE TABLE aplicaciones (
  id_app            INT AUTO_INCREMENT PRIMARY KEY,
  nombre_aplicacion VARCHAR(50)  NOT NULL,
  descripcion       VARCHAR(300) NOT NULL
);

CREATE TABLE usuarios_aplicaciones (
  usuario VARCHAR(50) NOT NULL,
  id_app  INT         NOT NULL,
  PRIMARY KEY (usuario, id_app),
  FOREIGN KEY (usuario) REFERENCES logins(usuario)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_app) REFERENCES aplicaciones(id_app)
    ON DELETE CASCADE ON UPDATE CASCADE
);
