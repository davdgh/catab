create table clientes (
    id_cliente int auto_increment primary key,
    nombre varchar(30) not null,
    correo varchar(60) not null,
    numero_telefono varchar(20) not null,
    tipo_cliente enum('estudiante', 'adulto_mayor', 'completo') not null
);


create table terminales (
    id_terminal int auto_increment primary key,
    direccion varchar(255) not null,
    ciudad varchar(50) not null,
    codigo_postal varchar(10) not null
);


create table rutas (
    id_ruta int auto_increment primary key,
    origen varchar(50) not null,
    destino varchar(50) not null
);


create table autobuses (
    id_autobus int auto_increment primary key,
    placa varchar(20) not null,
    numero_asientos int not null
);


create table reservas (
    id_reserva int auto_increment primary key,
    id_cliente int not null,
    id_ruta int not null,
    id_autobus int not null,
    numero_asiento int not null,
    fecha_compra date not null,
    precio decimal(10, 2) not null,
    foreign key (id_cliente) references clientes(id_cliente),
    foreign key (id_ruta) references rutas(id_ruta),
    foreign key (id_autobus) references autobuses(id_autobus)
);


create table usuarios (
    id_usuario int auto_increment primary key,
    correo varchar(60) not null,
    contraseña varchar(30) not null
);


insert into rutas (origen, destino) values
('Villahermosa', 'Macuspana'),
('Macuspana', 'Villahermosa'),
('Macuspana', 'Jalapa'),
('Jalapa', 'Macuspana'),
('Villahermosa', 'Jalapa');


create table precios (
    tipo_cliente enum('estudiante', 'adulto_mayor', 'completo') primary key,
    precio decimal(10, 2) not null
);

insert into precios (tipo_cliente, precio) values
('estudiante', 40.00),
('adulto_mayor', 25.00),
('completo', 55.00);

