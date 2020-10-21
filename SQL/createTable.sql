-- https://www.postgresqltutorial.com/postgresql-create-table/
-- accounts---------------------------------------------------
DROP TABLE IF EXISTS accounts;

CREATE TABLE accounts (
  user_id serial PRIMARY KEY,
  name VARCHAR (50),
  username VARCHAR (50) UNIQUE NOT NULL,
  password VARCHAR (50) NOT NULL,
  email VARCHAR (255) UNIQUE NOT NULL,
  type VARCHAR (30) NOT NULL,
  created_on TIMESTAMP NOT NULL,
  last_login TIMESTAMP
);

INSERT INTO
  "accounts"
VALUES
  (
    DEFAULT,
    'kiran',
    'kiranhass',
    'hello',
    'singh@sigh.com',
    'gemeente',
    CURRENT_TIMESTAMP,
    CURRENT_TIMESTAMP
  ),
  (
    DEFAULT,
    'kiran',
    'singh',
    'singh',
    'singh@sigh.singh',
    'bezoeker',
    CURRENT_TIMESTAMP,
    CURRENT_TIMESTAMP
  ),
  (
    DEFAULT,
    'admin',
    'admin',
    'admin',
    'admin@admin.com',
    'admin',
    CURRENT_TIMESTAMP,
    CURRENT_TIMESTAMP
  );

-- gemeente-----------------------------------------------------
DROP TABLE IF EXISTS gemeente;

CREATE TABLE gemeente (
  gemeente_id serial PRIMARY KEY,
  user_id INT NOT NULL,
  telephone VARCHAR (50) UNIQUE,
  short_description VARCHAR (1000),
  FOREIGN KEY (user_id) REFERENCES accounts (user_id)
);

INSERT INTO
  "gemeente"
VALUES
  (
    DEFAULT,
    1,
    '048704756',
    'hasselt is goed'
  );

-- kermis-------------------------------------------------------
DROP TABLE IF EXISTS kermis;

CREATE TABLE kermis (
  kermis_id serial PRIMARY KEY,
  gemeente_id INT NOT NULL,
  title VARCHAR (50) NOT NULL,
  description VARCHAR (500),
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  opening_hour TIME NOT NULL,
  closing_hour TIME NOT NULL,
  location VARCHAR (50),
  FOREIGN KEY (gemeente_id) REFERENCES gemeente (gemeente_id)
);

INSERT INTO
  "kermis"
VALUES
  (
    DEFAULT,
    1,
    'hasselt 2020',
    'hasselt kermis 2020',
    '2008-11-11',
    '2008-11-20',
    13 :30,
    18 :30
  ),
  (
    DEFAULT,
    2,
    'hasselt 2010',
    'hasselt kermis 2010',
    '2048-11-11',
    '2048-11-20',
    13 :30,
    18 :30,
    "hasselt"
  );

-- zones-------------------------------------------------------
DROP TABLE IF EXISTS zones;

CREATE TABLE zones (
  zone_id serial PRIMARY KEY,
  kermis_id INT NOT NULL,
  title VARCHAR (50) NOT NULL,
  description VARCHAR (50),
  location VARCHAR (50),
  open_spots INT NOT NULL,
  FOREIGN KEY (kermis_id) REFERENCES kermis (kermis_id)
);

INSERT INTO
  "zones"
VALUES
  (
    DEFAULT,
    1,
    'Zone 1',
    'hasselt kermis 2020 zone1',
    'hasselt kermis 2020 zone1 DEsc',
    'hasselt',
    30
  );

-- attractions------------------------------------------------
DROP TABLE IF EXISTS attractions;

CREATE TABLE attractions (
  attraction_id serial PRIMARY KEY,
  zone_id INT NOT NULL,
  kermis_id INT NOT NULL,
  title VARCHAR (50) NOT NULL,
  description VARCHAR (50),
  FOREIGN KEY (kermis_id) REFERENCES kermis (kermis_id),
  FOREIGN KEY (zone_id) REFERENCES zones (zone_id)
);

-- reservations----------------------------------------------
DROP TABLE IF EXISTS reservations;

CREATE TABLE reservations (
  reservation_id serial PRIMARY KEY,
  user_id INT NOT NULL,
  zone_id INT NOT NULL,
  kermis_id INT NOT NULL,
  going boolean NOT NULL DEFAULT 'f',
  review_rating INT,
  review_description VARCHAR (500),
  FOREIGN KEY (kermis_id) REFERENCES kermis (kermis_id),
  FOREIGN KEY (zone_id) REFERENCES zones (zone_id),
  FOREIGN KEY (user_id) REFERENCES accounts (user_id)
);

-- waitingList-----------------------------------------------
-- CREATE TABLE waitingList (
--   user_id INT NOT NULL,
--   zone_id INT NOT NULL,
--   kermis_id INT NOT NULL,
--   going boolean (50) NOT NULL DEFAULT 'f',
--   review_rating INT,
--   review_description VARCHAR (500),
--   FOREIGN KEY (kermis_id) REFERENCES kermis (kermis_id),
--   FOREIGN KEY (zone_id) REFERENCES zones (zone_id),
--   FOREIGN KEY (user_id) REFERENCES accounts (user_id)
-- );
-- messaging
-- CREATE TABLE messaging (
--   reservation_id serial PRIMARY KEY,
--   user_id INT NOT NULL,
--   zone_id INT NOT NULL,
--   kermis_id INT NOT NULL,
--   going boolean (50) NOT NULL DEFAULT 'f',
--   review_rating INT,
--   review_description VARCHAR (500),
--   FOREIGN KEY (kermis_id) REFERENCES kermis (kermis_id),
--   FOREIGN KEY (zone_id) REFERENCES zones (zone_id),
--   FOREIGN KEY (user_id) REFERENCES accounts (user_id)
-- );
