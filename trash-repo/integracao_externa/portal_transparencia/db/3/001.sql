create table visitantes (
                     id serial, 
                     quantidade integer default 0 not null, 
                     constraint visitantes_id_pk primary key (id)
                     );
                     
                     
insert into visitantes values (1, 0);                        