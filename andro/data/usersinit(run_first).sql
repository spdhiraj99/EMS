CREATE DATABASE AndroClients;
use AndroClients;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(100)NOT NULL,
  `password` varchar(100) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=latin1;
