start transaction;

delete
from kdo_liste
where la_personne not in (select id_personne from kdo_personne);

create table kdo_users (
    id      int auto_increment not null,
    name    varchar(30)        not null,
    icon_id int                not null,
    primary key (id)
)
    default character set utf8mb4
    collate utf8mb4_unicode_ci
    engine = InnoDB;

create table kdo_gifts (
    id          int auto_increment not null,
    user_id     int                not null,
    title       varchar(300)  default null,
    link        varchar(2000) default null,
    description longtext           not null,
    booked_by   int           default null,
    index idx_4f811b8aa76ed395 (user_id),
    index idx_4f811b8a7efefd57 (booked_by),
    primary key (id)
)
    default character set utf8mb4
    collate utf8mb4_unicode_ci
    engine = InnoDB;

alter table kdo_gifts
    add constraint fk_4f811b8aa76ed395 foreign key (user_id) references kdo_users (id);
alter table kdo_gifts
    add constraint fk_4f811b8a7efefd57 foreign key (booked_by) references kdo_users (id);

insert into kdo_users (id, name, icon_id)
select id_personne, nom_personne, choix_illu
from kdo_personne;

insert into kdo_gifts (id, user_id, title, link, description, booked_by)
select id, la_personne, titre, lien, description, if(iduser_resa = 0, null, iduser_resa)
from kdo_liste;

update kdo_gifts
set description = null
where description = '';

update kdo_gifts
set link = null
where link = '';

drop table kdo_personne;
drop table kdo_liste;

commit;
