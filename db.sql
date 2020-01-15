USE knowledge_city_test;

ALTER USER 'devuser' @'%' IDENTIFIED WITH mysql_native_password BY 'devpass';

DROP TABLE IF EXISTS `api_users`;
DROP TABLE IF EXISTS `students`;

CREATE TABLE IF NOT EXISTS `api_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `token` varchar(500),
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `students` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `group` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

TRUNCATE TABLE `api_users`;

TRUNCATE TABLE `students`;

INSERT INTO
  `api_users` (`username`, `password`)
VALUES
  (
    'admin',
    '$2y$10$8RBSDgDRiGOruGe21/aSf.KeIQVf7o5NHi4F/rN2V.rVkaTeNLEs.'
  );

INSERT INTO
  `students` (`first_name`, `last_name`, `group`, `status`)
VALUES
  (
    'Robin',
    'Jackman',
    'Software Engineer',
    "ACTIVE"
  ),
  (
    'Taylor',
    'Edward',
    'Software Architect',
    "ACTIVE"
  ),
  (
    'Vivian',
    'Dickens',
    'Database Administrator',
    "ACTIVE"
  ),
  (
    'Harry',
    'Clifford',
    'Database Administrator',
    "ACTIVE"
  ),
  (
    'Eliza',
    'Clifford',
    'Software Engineer',
    "ACTIVE"
  ),
  ('Nancy', 'Newman', 'Software Engineer', "ACTIVE"),
  (
    'Melinda',
    'Clifford',
    'Project Manager',
    "ACTIVE"
  ),
  (
    'Harley',
    'Gilbert',
    'Software Architect',
    "ACTIVE"
  ),
  (
    'John',
    'Doe',
    'Project Manager',
    "ACTIVE"
  ),
  (
    'Sara',
    'Smith',
    'Project Manager',
    "ACTIVE"
  ),
  (
    'Tim',
    'Morris',
    'Project Manager',
    "ACTIVE"
  ),
  (
    'Pat',
    'Withe',
    'Project Manager',
    "ACTIVE"
  ),
  (
    'George',
    'Jonhson',
    'Project Manager',
    "ACTIVE"
  ),
  (
    'Rol',
    'Madison',
    'Project Manager',
    "ACTIVE"
  ),
  (
    'Daniel',
    'Graves',
    'Project Manager',
    "ACTIVE"
  ),
  (
    'Ed',
    'Pats',
    'Project Manager',
    "ACTIVE"
  ),
  (
    'Fin',
    'Wood',
    'Project Manager',
    "ACTIVE"
  ),
  (
    'Phil',
    'Paterson',
    'Project Manager',
    "ACTIVE"
  );