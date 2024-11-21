-- Tabla de usuarios
create table usuarios (
    id int auto_increment primary key,
    nombre varchar(255) not null,
    correo varchar(80) not null unique,
    telefono varchar(20) not null,
    contraseña varchar(255) not null,
    tipo_cliente enum('estudiante', 'adulto_mayor', 'completo', 'administrador') not null
);

-- Tabla de terminales
create table terminales (
    id int auto_increment primary key,
    ubicacion varchar(255) not null,
    ciudad varchar(50) not null,
    codigo_postal varchar(10) not null
);

-- Tabla de autobuses
create table autobuses (
    id int auto_increment primary key,
    placa varchar(10) not null unique,
    numero_asientos int not null default 30
);

-- Tabla de rutas
create table rutas (
    id int auto_increment primary key,
    origen varchar(255) not null,
    destino varchar(255) not null
);

-- Tabla de reservas
create table reservas (
    id int auto_increment primary key,
    usuario_id int not null,
    ruta_id int not null,
    autobus_id int not null,
    numero_asiento int not null,
    fecha_compra date not null,
    precio decimal(10, 2) not null,
    foreign key (usuario_id) references usuarios(id),
    foreign key (ruta_id) references rutas(id),
    foreign key (autobus_id) references autobuses(id)
);

-- Insertar rutas
insert into rutas (origen, destino) values
('Villahermosa', 'Macuspana'),
('Macuspana', 'Villahermosa'),
('Macuspana', 'Jalapa'),
('Jalapa', 'Macuspana');

-- Insertar terminales
insert into terminales (ubicacion, ciudad, codigo_postal) values
('Adolfo Ruiz Cortines, Casa Blanca 1a., Centro', 'Villahermosa', '86060'),
('P. José Narciso Rovirosa, Centro', 'Macuspana', '86700'),
('Carlos A. Madrazo 911, Centro', 'Jalapa', '86850');

-- Insertar autobuses con placas aleatorias
insert into autobuses (placa) values
('ABC1234'),
('DEF5678'),
('GHI9101'),
('JKL1213'),
('MNO1415');

-- Crear vista para precios según el tipo de cliente
create view precios_clientes as
select
    tipo_cliente,
    case tipo_cliente
        when 'estudiante' then 40
        when 'adulto mayor' then 25
        when 'completo' then 55
    end as precio
from
    (select 'estudiante' as tipo_cliente union all
     select 'adulto mayor' union all
     select 'completo') as tipos;


