PRAGMA foreign_keys = ON;
.header on
.mode column

DROP TABLE IF EXISTS Media;
DROP TABLE IF EXISTS Messages;
DROP TABLE IF EXISTS Reviews;
DROP TABLE IF EXISTS Orders;
DROP TABLE IF EXISTS CustomOrders;

DROP TABLE IF EXISTS Services;

DROP TABLE IF EXISTS Admins;

DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Categories;


CREATE TABLE Users (
    user_id INTEGER PRIMARY KEY,
    name TEXT NOT NULL,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    email TEXT UNIQUE,
    register_date DATE DEFAULT (DATE('now')),
    is_fl BOOLEAN DEFAULT 0,
    is_cl BOOLEAN DEFAULT 0,
    is_admin BOOLEAN DEFAULT 0
);

CREATE TABLE Services (
    service_id INTEGER PRIMARY KEY,
    price REAL CHECK(price >= 0) NOT NULL,
    delivery_time INTEGER NOT NULL,
    service_title TEXT NOT NULL,
    service_desc TEXT,
    active BOOLEAN DEFAULT 1,
    category TEXT,
    freelancer_id INTEGER,
    FOREIGN KEY (freelancer_id) REFERENCES Users(user_id),
    FOREIGN KEY (category) REFERENCES Categories(name)
);

CREATE TABLE Media (
    media_id INTEGER PRIMARY KEY,
    media_url TEXT,
    media_type TEXT CHECK(media_type IN ('image', 'video')),
    service_id INTEGER,
    FOREIGN KEY (service_id) REFERENCES Services(service_id)
);

CREATE TABLE Categories (
    name text PRIMARY KEY
);

CREATE TABLE Reviews (
    review_id INTEGER PRIMARY KEY,
    rating INTEGER CHECK(rating BETWEEN 1 AND 5) NOT NULL,
    review_title TEXT NOT NULL,
    review_text TEXT,
    review_date DATE DEFAULT (DATE('now')),
    client_id INTEGER,
    service_id INTEGER,
    FOREIGN KEY (client_id) REFERENCES Users(user_id),
    FOREIGN KEY (service_id) REFERENCES Services(service_id)
);

CREATE TABLE Orders (
    order_id INTEGER PRIMARY KEY,
    status TEXT CHECK(status IN ('pending', 'accepted', 'rejected', 'completed')) NOT NULL,
    order_date DATE DEFAULT (DATE('now')),
    client_id INTEGER,
    service_id INTEGER,
    FOREIGN KEY (client_id) REFERENCES Users(user_id),
    FOREIGN KEY (service_id) REFERENCES Services(service_id)
);

CREATE TABLE CustomOrders (
    custom_order_id INTEGER PRIMARY KEY,
    custom_title TEXT NOT NULL,
    custom_desc TEXT,
    custom_price REAL CHECK(custom_price >= 0),
    order_date DATE DEFAULT (DATE('now')),
    custom_delivery_time INTEGER NOT NULL,
    status TEXT CHECK(status IN ('pending', 'accepted', 'rejected', 'completed')),
    client_id INTEGER,
    service_id INTEGER,
    FOREIGN KEY (client_id) REFERENCES Users(user_id),
    FOREIGN KEY (service_id) REFERENCES Services(service_id)
);

CREATE TABLE Messages (
    message_id INTEGER PRIMARY KEY,
    message_title TEXT NOT NULL,
    message_text TEXT,
    message_date DATE DEFAULT (DATE('now')),
    client_id INTEGER,
    service_id INTEGER,
    FOREIGN KEY (client_id) REFERENCES Users(user_id),
    FOREIGN KEY (service_id) REFERENCES Services(service_id)
);

-- password: test123
INSERT INTO Users (name, username, password, email, register_date, is_fl, is_cl, is_admin) VALUES('Andre','AndreLTW','$2y$12$xdyYk7.Pk84JG1PcEpCt8.yd1PLSooAFs8WYTQCK1BV2l1Btjx9sy','ltwtest@test.com','2025-04-29', 1, 1, 1);




