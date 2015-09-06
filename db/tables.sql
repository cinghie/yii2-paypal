CREATE TABLE IF NOT EXISTS `paypal_orders` (
  `order_id` int(11) NOT NULL,
  `user_id` varchar(254) NOT NULL,
  `payment_id` varchar(50) DEFAULT NULL,
  `state` varchar(20) DEFAULT NULL,
  `amount` varchar(20) DEFAULT NULL,
  `description` varchar(40) DEFAULT NULL,
  `created_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `paypal_orders`
  ADD PRIMARY KEY (`order_id`);
  
ALTER TABLE `paypal_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;