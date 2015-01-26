CREATE TABLE subscribers (
	email varchar(255) NOT NULL,
	name varchar(255) NOT NULL,
	zip mediumint(5) NOT NULL,
	type enum ('Student', 'Alumni', 'Friend', 'Other') NOT NULL,
	verificationDate datetime NULL,
	PRIMARY KEY (email)
);

CREATE TABLE admins (
	email varchar(255) NOT NULL,
	password varchar(225) NOT NULL, -- Change this depending on hashing algo
	super boolean NOT NULL DEFAULT false,
	salt varchar(255) NOT NULL, -- See above note
	name varchar(255) NOT NULL,
	PRIMARY KEY (email)
);

CREATE TABLE emails (
	id integer NOT NULL AUTO_INCREMENT,
	subject varchar(255) NOT NULL,
	abstract text NOT NULL,
	content text NOT NULL,
	sendDate datetime NOT NULL,
	randomEmailId integer Not NULL,
	PRIMARY KEY (id)
);

CREATE TABLE representatives (
	id integer NOT NULL AUTO_INCREMENT,
	firstName varchar(55) NOT NULL,
	lastName varchar(55) NOT NULL,
	politicalParty varchar(55) NOT NULL,
	image varchar(255) NULL,
	phoneNumber varchar(20) NULL,
	physicalAddress varchar(255) NULL,
	emailAddress varchar(255) NULL,
	PRIMARY KEY (id)
);

CREATE TABLE zip_representatives (
	zip mediumint(5) NOT NULL,
	representative_id integer NOT NULL,
	PRIMARY KEY (zip, representative_id),
	FOREIGN KEY (representative_id) REFERENCES representatives (id)
);
