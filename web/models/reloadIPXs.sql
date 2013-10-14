CREATE TABLE config (
  idconfig INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(50) NULL,
  NIT VARCHAR(12) NULL,
  idFacturacion VARCHAR(100) NULL,
  correo VARCHAR(200) NULL,
  direccion VARCHAR(100) NULL,
  ciudad VARCHAR(20) NULL,
  PRIMARY KEY(idconfig)
);

CREATE TABLE Cuenta (
  idCuenta INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  saldo FLOAT NULL,
  fechaCreacion DATETIME NULL,
  estado INTEGER UNSIGNED NULL,
  PRIMARY KEY(idCuenta)
);

CREATE TABLE operadora (
  idoperadora INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Nombre VARCHAR(20) NULL,
  detalle VARCHAR(100) NULL,
  PRIMARY KEY(idoperadora)
);

CREATE TABLE POS (
  idPOS INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  codIdentificacion VARCHAR(200) NULL,
  estado INTEGER UNSIGNED NULL,
  PRIMARY KEY(idPOS)
);

CREATE TABLE prefijos (
  idprefijos INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  operadora_idoperadora INTEGER UNSIGNED NOT NULL,
  prefijo INTEGER UNSIGNED NULL,
  PRIMARY KEY(idprefijos),
  INDEX prefijos_FKIndex1(operadora_idoperadora)
);

CREATE TABLE tipoTransaccion (
  idtipoTransaccion INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  detalle INTEGER(20) UNSIGNED NULL,
  PRIMARY KEY(idtipoTransaccion)
);

CREATE TABLE TipoUser (
  idTipoUser INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  detalle VARCHAR(20) NULL,
  PRIMARY KEY(idTipoUser)
);

CREATE TABLE Transaccion (
  idTransaccion INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Cuenta_idCuenta INTEGER UNSIGNED NOT NULL,
  Users_idUsers INTEGER UNSIGNED NOT NULL,
  operadora_idoperadora INTEGER UNSIGNED NOT NULL,
  tipoTransaccion_idtipoTransaccion INTEGER UNSIGNED NOT NULL,
  monto FLOAT NULL,
  Detalle VARCHAR(200) NULL,
  fecha DATETIME NULL,
  estado INTEGER UNSIGNED NULL,
  PRIMARY KEY(idTransaccion, Cuenta_idCuenta, Users_idUsers),
  INDEX Transaccion_FKIndex1(Cuenta_idCuenta),
  INDEX Transaccion_FKIndex2(tipoTransaccion_idtipoTransaccion),
  INDEX Transaccion_FKIndex3(operadora_idoperadora)
);

CREATE TABLE Users (
  idUsers INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  TipoUser_idTipoUser INTEGER UNSIGNED NOT NULL,
  Users_idUsers INTEGER UNSIGNED NOT NULL,
  Nombre VARCHAR(20) NULL,
  segundoNombre VARCHAR(20) NULL,
  apPaterno VARCHAR(25) NULL,
  apMaterno VARCHAR(25) NULL,
  CI/NIT VARCHAR(11) NULL,
  fechaIncripcion DATETIME NULL,
  fechaNac DATE NULL,
  estado INTEGER UNSIGNED NULL,
  ciudad VARCHAR(20) NULL,
  direccion VARCHAR(100) NULL,
  PRIMARY KEY(idUsers),
  INDEX Users_FKIndex1(Users_idUsers),
  INDEX Users_FKIndex2(TipoUser_idTipoUser)
);

CREATE TABLE UsersCuenta (
  Cuenta_idCuenta INTEGER UNSIGNED NOT NULL,
  Users_idUsers INTEGER UNSIGNED NOT NULL,
  fecha DATETIME NULL,
  PRIMARY KEY(Cuenta_idCuenta, Users_idUsers),
  INDEX Users_has_Cuenta_FKIndex2(Cuenta_idCuenta)
);

CREATE TABLE Users_POS (
  Users_idUsers INTEGER UNSIGNED NOT NULL,
  POS_idPOS INTEGER UNSIGNED NOT NULL,
  fecha DATE NULL,
  PRIMARY KEY(Users_idUsers, POS_idPOS),
  INDEX Users_has_POS_FKIndex1(Users_idUsers),
  INDEX Users_has_POS_FKIndex2(POS_idPOS)
);


