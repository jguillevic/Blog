CREATE TABLE website_user
(
	id SERIAL
	, login VARCHAR(200) NOT NULL UNIQUE
	, email VARCHAR(200) NOT NULL UNIQUE
	, avatar_url VARCHAR(500) NOT NULL
	, password_hash VARCHAR(128) NOT NULL -- SHA-512
	, is_activated BOOLEAN NOT NULL
	, activation_code CHAR(36) NOT NULL UNIQUE -- GUID
	, forgotten_password_code CHAR(36) NULL UNIQUE -- GUID
	, PRIMARY KEY (id)
 );

CREATE TABLE contact
(
	id SERIAL
	, first_name VARCHAR(200) NOT NULL
	, last_name VARCHAR(200) NOT NULL
	, email VARCHAR(200) NOT NULL
	, content TEXT NOT NULL
	, PRIMARY KEY (id)
);

CREATE history
(
	id SERIAL
	, date_time DATETIME NOT NULL
	, user_id INT NOT NULL
	, PRIMARY KEY (id)
	, FOREIGN KEY (user_id) REFERENCES website_user (id)
);

CREATE post
(
	id SERIAL
	, title VARCHAR(200) NOT NULL
	, slug VARCHAR(200) NOT NULL
	, description VARCHAR(500) NOT NULL
	, content TEXT NOT NULL
	, is_published BOOLEAN NOT NULL
	, creation_history_id INT NOT NULL
	, PRIMARY KEY (id)
	, FOREIGN KEY (creation_history_id) REFERENCES history (id)
);

CREATE post_update
(
	post_id INT NOT NULL
	, history_id INT NOT NULL
	, PRIMARY KEY (post_id, history_id)
	, FOREIGN KEY (post_id) REFERENCES post (id)
	, FOREIGN KEY (history_id) REFERENCES history (id)
);