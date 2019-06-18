create table user(
	id int(6) unsigned auto_increment primary key,
	name varchar(30) not null, 
	email varchar(30) not null,
	website varchar(50), 
	comment varchar(255),
	gender varchar(10) not null
);
