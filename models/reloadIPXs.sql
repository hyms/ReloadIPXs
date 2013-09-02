CREATE TABLE Cuenta (
  idCuenta INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  saldo FLOAT NULL,
  fechaCreacion DATETIME NULL,
  PRIMARY KEY(idCuenta)
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
  Users_idUsers INTEGER UNSIGNED NOT NULL,
  Cuenta_idCuenta INTEGER UNSIGNED NOT NULL,
  tipoTransaccion_idtipoTransaccion INTEGER UNSIGNED NOT NULL,
  monto FLOAT NULL,
  Detalle VARCHAR(200) NULL,
  fecha DATETIME NULL,
  estado INTEGER UNSIGNED NULL,
  PRIMARY KEY(idTransaccion, Users_idUsers, Cuenta_idCuenta),
  INDEX Transaccion_FKIndex1(Cuenta_idCuenta),
  INDEX Transaccion_FKIndex2(tipoTransaccion_idtipoTransaccion)
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
  idParent INTEGER UNSIGNED NULL,
  tipo INTEGER UNSIGNED NULL,
  fechaIncripcion DATETIME NULL,
  fechaNac DATE NULL,
  PRIMARY KEY(idUsers),
  INDEX Users_FKIndex1(Users_idUsers),
  INDEX Users_FKIndex2(TipoUser_idTipoUser)
);

CREATE TABLE Users_has_Cuenta (
  Users_idUsers INTEGER UNSIGNED NOT NULL,
  Cuenta_idCuenta INTEGER UNSIGNED NOT NULL,
  fecha DATETIME NULL,
  PRIMARY KEY(Users_idUsers, Cuenta_idCuenta),
  INDEX Users_has_Cuenta_FKIndex1(Users_idUsers),
  INDEX Users_has_Cuenta_FKIndex2(Cuenta_idCuenta)
);


