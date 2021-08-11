CREATE TABLE holdersv2 (
    id int NOT NULL AUTO_INCREMENT,
	time datetime NOT NULL DEFAULT NOW(),
    total BIGINT NOT NULL,
    eth BIGINT NOT NULL,
    bsc BIGINT NOT NULL,
    PRIMARY KEY (id)
); 

CREATE TABLE price (
    id int NOT NULL AUTO_INCREMENT,
	time datetime NOT NULL DEFAULT NOW(),
    price decimal(10,2) NOT NULL,
    PRIMARY KEY (id)
); 

CREATE TABLE stats (
    id int NOT NULL AUTO_INCREMENT,
    holders bigint NOT NULL,
    marketcap bigint NOT NULL,
    price decimal(15,2) NOT NULL,
    circSuply int NOT NULL,
    volume1d decimal(15,2) NOT NULL,
    volume7d decimal(15,2) NOT NULL,
    volume30d decimal(15,2) NOT NULL,
    volume1dpct decimal(15,2) NOT NULL,
    volume7dpct decimal(15,2) NOT NULL,
    volume30dpct decimal(15,2) NOT NULL,
    price1d decimal(15,2) NOT NULL,
    price7d decimal(15,2) NOT NULL,
    price30d decimal(15,2) NOT NULL,
    price1dpct decimal(5,2) NOT NULL,
    price7dpct decimal(5,2) NOT NULL,
    price30dpct decimal(5,2) NOT NULL,
    PRIMARY KEY (id)
);
INSERT INTO stats (id, holders, marketcap, price, circSuply, volume1d, volume7d, volume30d, volume1dpct, volume7dpct, volume30dpct, price1d, price7d, price30d, price1dpct, price7dpct, price30dpct)
VALUES (NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

CREATE TABLE accesslog (
    id BIGINT NOT NULL AUTO_INCREMENT,
	time datetime NOT NULL DEFAULT NOW(),
    address varchar(100) NOT NULL,
    agent varchar(500) NOT NULL,
    PRIMARY KEY (id)
); 

CREATE TABLE ranks (
    id int NOT NULL AUTO_INCREMENT,
    coinmarketcap bigint NOT NULL DEFAULT 0,
    coingecko bigint NOT NULL DEFAULT 0,
    dextools bigint NOT NULL DEFAULT 0,
    define bigint NOT NULL DEFAULT 0,
    uniswap bigint NOT NULL DEFAULT 0,
    nomics bigint NOT NULL DEFAULT 0,
    PRIMARY KEY (id)
); 
INSERT INTO ranks (coinmarketcap, coingecko, dextools, define, uniswap) VALUES (0, 0, 0, 0, 0);

CREATE TABLE top1000 (
    id int NOT NULL AUTO_INCREMENT,
    rank int NOT NULL,
    address varchar(255) NOT NULL,
    quantity int NOT NULL,
    percentage decimal(5,2) NOT NULL,
    value BIGINT NOT NULL,
    PRIMARY KEY (id)
); 