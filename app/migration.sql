--- Its only example of migration SQL script.

CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY,
    username VARCHAR(200) UNIQUE NOT NULL
);

INSERT INTO users (id, username) VALUES (1, 'aShortUsername');
