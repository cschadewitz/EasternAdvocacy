CREATE TABLE subscribers (
	email varchar(255) NOT NULL,
	name varchar(255) NOT NULL,
	zip integer NOT NULL,
	type enum ('Student', 'Alumni', 'Friend') NOT NULL,
	verificationDate datetime NULL,
	PRIMARY KEY (email)
);

CREATE TABLE admins (
	email varchar(255) NOT NULL,
	password varchar(225) NOT NULL, -- Change this depending on
	super boolean NOT NULL DEFAULT false,
	salt varchar(255) NOT NULL, -- See above note
	name varchar(255) NOT NULL,
	PRIMARY KEY (email)
);

CREATE TABLE emails (
	id integer NOT NULL AUTO_INCREMENT,
	content text NOT NULL,
	sendDate datetime NOT NULL,
	PRIMARY KEY (id)
);