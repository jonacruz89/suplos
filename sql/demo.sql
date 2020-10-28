
DROP TABLE IF EXISTS `places`;
CREATE TABLE `places` (
  `id` int(11) NOT NULL,
  `address` varchar(250) DEFAULT '',
  `city` varchar(100) DEFAULT '',
  `phone` varchar(20) DEFAULT '',
  `code` varchar(20) DEFAULT '',
  `type` varchar(50) DEFAULT '',
  `price` varchar(50) DEFAULT '',
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `places`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `places`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  
