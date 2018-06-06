CREATE DATABASE searchdb;
use searchdb;
CREATE TABLE `details` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `tablename` varchar(100)NOT NULL,
  UNIQUE(`tablename`)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;
