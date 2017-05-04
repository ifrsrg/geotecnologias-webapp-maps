-- Isso é um esquema(um exemplo) de como o  banco tem que ser para gerenciar os usuários, privilégios para cada 
-- conteúdo ser exibido de acordo com o nível do usuário

-- Níveis de usuário:
-- 1 - super_admin
-- 2 - admin (Carol e Fran)
-- 3 - epidemio
-- 4 - ambiental
-- 5 - saude  (esse usuário pode ver os mapas de epidemio e ambiental)
-- 6 - bolsistas (editar notícias)





######################### CRIAÇÃO BANCO/TABELAS #########################

CREATE DATABASE projeto_geo;

\c projeto_geo

CREATE TABLE users (
    id_user     SERIAL PRIMARY KEY, 
    name_user   VARCHAR(25)NOT NULL,
    type        INTEGER NOT NULL,
    password    VARCHAR(40) NOT NULL
 );

CREATE TABLE news(
    id_news                 SERIAL PRIMARY KEY,
    headline_news           VARCHAR (50) NOT NULL,
    text_news               TEXT NOT NULL,
    publication_date_news   DATE,
    owner_news              INTEGER NOT NULL REFERENCES USERS (id_user)
);



######################### INSERTS #########################

INSERT INTO users VALUES (
    DEFAULT,
    'MARCOS OLEIRO',
    1,
    '123456'
);
INSERT INTO users VALUES (
    DEFAULT,
    'CAROLINA',
    2,
    '123456'
);
INSERT INTO users VALUES (
    DEFAULT,
    'BOLSISTA 1',
    3,
    '123456'
);

INSERT INTO news VALUES (
    DEFAULT,
    'NOTICIA 1',
    'TEXTO NOTICIA 1',
    '24-11-2015',
    3
)


######################### PRIVILÉGIOS #########################

-- Quando o usuario logar no site, o sistema pega o tipo do usuario e de acordo com esse tipo executa 
-- uma query de conexão com o banco (cada tipo de usuario tem um user do banco diferente) 

CREATE ROLE admin WITH PASSWORD 'senha' LOGIN;
CREATE ROLE epidemio WITH PASSWORD 'senha'LOGIN;
CREATE ROLE ambiental WITH PASSWORD 'senha'LOGIN;
CREATE ROLE saude WITH PASSWORD 'senha'LOGIN;
CREATE ROLE bolsistas WITH PASSWORD 'senha'LOGIN;


GRANT INSERT, SELECT ON users TO admin;
GRANT USAGE, SELECT ON SEQUENCE users_id_user_seq to admin;
GRANT INSERT, SELECT ON news TO bolsistas;
GRANT USAGE, SELECT ON SEQUENCE news_id_news_seq to bolsistas;





