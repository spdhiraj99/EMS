use androclients;
CREATE TABLE `masterstock` (
  `partValue` varchar(50)NOT NULL ,
  `Package` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `qty` int(11) NOT NULL,
  `qtypb` int(11) NOT NULL,
  `comments` varchar(200),
  primary key (`partValue`,`Package`)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;
