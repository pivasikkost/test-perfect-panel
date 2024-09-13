/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `get_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `book_id` (`book_id`),
  CONSTRAINT `user_books_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `user_books_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `user_books_ibfk_3` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `books` (`id`, `name`, `author`) VALUES
    ("1","Romeo and Juliet","William Shakespeare"),
    ("2","War and Peace","Leo Tolstoy"),
    ("3","Sevastopolskie Rasskazy","Leo Tolstoy"),
    ("4","Anna Karenina","Leo Tolstoy"),
    ("5","Hamlet","William Shakespeare"),
;

INSERT INTO `users` (`id`, `first_name`, `last_name`, `birthday`) VALUES
    ("1","Ivan","Ivanov","2005-01-01"),
    ("2","Marina","Ivanova","2011-03-01"),
    ("3","Constantine","Zosimenko","1994-05-05"),
    ("4","Veronika","Zosimenko","2014-09-11"),
    ("5","Veronika","Dobrinina","2015-09-15"),
    ("6","Maxim","Dobrinin","2013-12-25"),
    ("7","Nullion","Nullov","2014-01-01"),
    ("8","Prosrochnik","Prosro4in","2013-12-12"),
    ("9","Andrey","Masyuk","2014-06-06"),
    ("10","Igor","Vostrikov","2015-03-15")
;

INSERT INTO `user_books` (`id`, `user_id`, `book_id`, `get_date`, `return_date`) VALUES
    ("1","1","2","2022-01-01","2022-02-01"),
    ("2","2","1","2021-02-01","2021-02-11"),
    ("3","3","2","2021-03-01","2021-03-11"),
    ("4","3","3","2023-03-02","2023-03-13"),
    ("5","4","2","2021-04-01","2021-04-11"),
    ("20","4","2","2022-04-01","2022-04-11"),
    ("6","4","3","2024-04-02","2024-04-13"),
    ("7","5","3","2021-05-01","2021-05-11"),
    ("8","5","4","2022-05-02","2022-05-13"),
    ("9","6","2","2021-06-01","2021-06-16"),
    ("10","6","3","2022-06-02","2022-06-13"),
    ("11","6","4","2023-06-03","2023-06-15"),
    ("12","8","3","2021-08-01","2021-08-11"),
    ("13","8","4","2022-08-02","2022-09-02"),

    
    ("14","9","3","2021-09-02","2021-09-12"),
    ("15","9","4","2022-09-02","2022-09-12"),
    ("16","9","5","2023-09-02","2023-09-12"),
    ("17","9","1","2024-09-02","2024-09-12"),
    ("18","10","1","2021-10-02","2021-10-12"),
    ("19","10","2","2022-10-02","2022-10-12")
;