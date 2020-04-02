create table User(
  `Id` int
(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar
(64) NOT NULL UNIQUE,
  `Email` varchar
(64) NOT NULL UNIQUE,
  `HashPassword` varchar
(61) NOT NULL,
  `Gender` varchar
(64),
  PRIMARY KEY
(`Id`)
);