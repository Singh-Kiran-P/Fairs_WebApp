-- Accounts---------------------------------------------------
DROP TABLE IF EXISTS accounts;

CREATE TABLE accounts (
  user_id serial PRIMARY KEY,
  name VARCHAR (50),
  username VARCHAR (50) UNIQUE NOT NULL,
  password VARCHAR (255) NOT NULL,
  email VARCHAR (255) UNIQUE NOT NULL,
  type VARCHAR (30) NOT NULL,
  created_on TIMESTAMP NOT NULL,
  active boolean DEFAULT 'f',
  activation_hash VARCHAR (255) UNIQUE,
);

-- Reset Password------------------------------------------------
DROP TABLE IF EXISTS password_reset;

Create table password_reset(
  id serial PRIMARY KEY,
  email VARCHAR (50) NOT NULL,
  token VARCHAR (50) UNIQUE NOT NULL,
  FOREIGN KEY (email) REFERENCES accounts (email) ON DELETE CASCADE
);

-- City-----------------------------------------------------
DROP TABLE IF EXISTS city;

CREATE TABLE city (
  city_id serial PRIMARY KEY,
  user_id INT NOT NULL,
  telephone VARCHAR (50) UNIQUE,
  short_description TEXT,
  FOREIGN KEY (user_id) REFERENCES accounts (user_id) ON DELETE CASCADE
);

-- Fair table-----------------------------------------------------
DROP TABLE IF EXISTS fair;

CREATE TABLE fair (
  fair_id serial PRIMARY KEY,
  city_id INT NOT NULL,
  title VARCHAR (50) NOT NULL,
  description TEXT,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  opening_hour TIME NOT NULL,
  closing_hour TIME NOT NULL,
  location VARCHAR (50),
  totImg INT,
  FOREIGN KEY (city_id) REFERENCES city (city_id) ON DELETE CASCADE
);

-- zones-------------------------------------------------------
DROP TABLE IF EXISTS zones;

CREATE TABLE zones (
  zone_id serial PRIMARY KEY,
  fair_id INT NOT NULL,
  title VARCHAR (50) NOT NULL,
  description TEXT,
  location VARCHAR (500),
  open_spots INT NOT NULL,
  attractions TEXT,
  totImg INT,
  totVideo INT,
  FOREIGN KEY (fair_id) REFERENCES fair (fair_id) ON DELETE CASCADE
);

-- zoneSlots ------------------------------------------------
CREATE TABLE zoneslots (
  zoneslot_id serial PRIMARY KEY,
  zone_id INT NOT NULL,
  opening_slot TIME NOT NULL,
  closing_slot TIME NOT NULL,
  free_slots INT,
  start_date DATE NOT NULL,
  FOREIGN KEY (zone_id) REFERENCES zones (zone_id) ON DELETE CASCADE
);

-- reservations----------------------------------------------
DROP TABLE IF EXISTS reservations;

CREATE TABLE reservations (
  reservation_id serial PRIMARY KEY,
  user_id INT NOT NULL,
  zoneslot_id INT NOT NULL,
  fair_id INT NOT NULL,
  nOfPeople INT NOT NULL,
  going boolean NOT NULL DEFAULT 'f',
  review_rating INT,
  review_description TEXT,
  FOREIGN KEY (fair_id) REFERENCES fair (fair_id) ON DELETE CASCADE,
  FOREIGN KEY (zoneslot_id) REFERENCES zoneslots (zoneslot_id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES accounts (user_id) ON DELETE CASCADE
);

-- notifications----------------------------------------------
DROP TABLE IF EXISTS notifications;

CREATE TABLE notifications (
  notification_id serial PRIMARY KEY,
  user_id INT NOT NULL,
  msg TEXT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES accounts (user_id) ON DELETE CASCADE
);

-- Reviews --------------------------------------------------
DROP TABLE IF EXISTS review;

CREATE TABLE review (
  review_id serial PRIMARY KEY,
  zone_id INT NOT NULL,
  user_id INT NOT NULL,
  rating INT NOT NULL,
  review TEXT,
  FOREIGN KEY (user_id) REFERENCES accounts (user_id) ON DELETE CASCADE,
  FOREIGN KEY (zone_id) REFERENCES zones (zone_id) ON DELETE CASCADE
);

-- waitingList-----------------------------------------------
DROP TABLE IF EXISTS waitingList;

CREATE TABLE waitingList (
  user_id INT NOT NULL,
  zoneslot_id INT NOT NULL,
  placement serial,
  FOREIGN KEY (zoneslot_id) REFERENCES zoneslots (zoneslot_id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES accounts (user_id) ON DELETE CASCADE
);

-- messaging-----------------------------------------------
DROP TABLE IF EXISTS messaging;

CREATE TABLE messaging (
  message_id serial PRIMARY KEY,
  msgFrom INT NOT NULL,
  msgTo INT NOT NULL,
  message TEXT,
  send_dateTime TIMESTAMP NOT NULL,
  openend BOOLEAN DEFAULT False,
  showed BOOLEAN DEFAULT False,
  FOREIGN KEY (msgFrom) REFERENCES accounts (user_id) ON DELETE CASCADE,
  FOREIGN KEY (msgTo) REFERENCES accounts (user_id) ON DELETE CASCADE
);
