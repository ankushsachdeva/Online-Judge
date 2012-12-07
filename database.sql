create database onj;
use onj;
create table contests(
    contestID int auto_increment,
    Name varchar(50),
    startTime datetime,
    endTime datetime,
    primary key(contestID)
);

create table users(
    userID int not null auto_increment,
    username varchar(50) unique not null,
    password varchar(50) not null,
    firstname varchar(50) default null,
    lastname varchar(50) default null,
    college varchar(50) default null,
    email varchar(50) not null,
    rank int default 0,
    score int default 0,
    penalty int default 0,
    primary key(userID)
);

create table problems(
    contestID int not null,
    problemID int not null auto_increment primary key,
    visible bool default 0,
    problemName varchar(50) not null,
    statement varchar(10000),
    accepted int default 0,
    submissions int default 0,
    timeLimit int default 2,
    memoryLimit int default 5,
    score int default 0
);

create table blog(
    blogName varchar(50),
    blogID int auto_increment primary key,
    time timestamp not null
);
create table submissions(
    subtime TIMESTAMP default CURRENT_TIMESTAMP,
    submID int not null auto_increment primary key,
    problemID int not null,
    problemName varchar(50) not null,
    userID int not null,
    username varchar(50) not null,
    runtime decimal(4,2),
    runmem decimal(4,2) default 0,
    score int,
    submlang varchar(15),
    status int default 0
);



