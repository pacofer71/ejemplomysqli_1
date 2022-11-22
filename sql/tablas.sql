create table usuarios(
    id int auto_increment primary key,
    nombre varchar(100) not null,
    email varchar(100) unique,
    perfil enum("Admin", "Normal") default "Normal"
);

insert into usuarios(nombre, email, perfil) values("manuel23", "manolo@email.es", "Admin");
insert into usuarios(nombre, email, perfil) values("felipe44", "felipe@email.com", "Normal");
insert into usuarios(nombre, email, perfil) values("ana67", "ana@email.es", "Normal");
insert into usuarios(nombre, email, perfil) values("rosa43", "rosa4@email.es", "Admin");
insert into usuarios(nombre, email, perfil) values("kiko28", "kiko23@email.es", "Normal");