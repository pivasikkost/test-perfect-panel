/* WORKING VARIANT 1 */
SELECT
	u.id as ID,
    CONCAT_WS(' ', u.first_name, u.last_name) as Name,
    b.author as Author,
    GROUP_CONCAT(DISTINCT b.name ORDER BY b.name SEPARATOR ', ') as Books
FROM user_books ub
INNER JOIN books b
  ON ub.book_id = b.id
INNER JOIN users u
  ON ub.user_id = u.id
WHERE
  user_id IN (
    SELECT user_id
    FROM user_books ub1
    GROUP BY ub1.user_id
    HAVING COUNT(DISTINCT book_id) = 2
  ) -- users with 2 books
  AND TIMESTAMPDIFF(YEAR, u.birthday, CURRENT_DATE) BETWEEN 7 AND 17
  AND TIMESTAMPDIFF(DAY, ub.get_date, CURRENT_DATE) <= 14
GROUP BY ub.user_id , b.author
HAVING COUNT(DISTINCT book_id) = 2
;


/* WORKING VARIANT 2 */
SELECT
	u.id as ID,
    CONCAT_WS(' ', u.first_name, u.last_name) as Name,
    b.author as Author,
    GROUP_CONCAT(DISTINCT b.name ORDER BY b.name SEPARATOR ', ') as Books
FROM user_books ub
INNER JOIN books b
  ON ub.book_id = b.id
INNER JOIN users u
  ON ub.user_id = u.id
WHERE
  user_id IN (
    SELECT user_id
    FROM user_books ub1
    GROUP BY ub1.user_id
    HAVING COUNT(DISTINCT book_id) = 2
  ) -- users with 2 books
  AND TIMESTAMPDIFF(YEAR, u.birthday, CURRENT_DATE) BETWEEN 7 AND 17
  AND TIMESTAMPDIFF(DAY, ub.get_date, ub.return_date) <= 14
GROUP BY ub.user_id , b.author
HAVING COUNT(DISTINCT book_id) = 2
;


/* NOT WORKING VARIANTS */
SELECT
    u.id as ID,
    CONCAT_WS(' ', u.first_name, u.last_name) as Name,
    b.author as Author,
    GROUP_CONCAT(b.name ORDER BY b.name SEPARATOR ', ') as Books
FROM users u
INNER JOIN user_books ub
    ON u.id = ub.user_id
INNER JOIN books b
    ON ub.book_id = b.id
WHERE TIMESTAMPDIFF(YEAR, u.birthday, CURRENT_DATE) BETWEEN  7 AND 17
  AND TIMESTAMPDIFF(DAY, ub.get_date, ub.return_date) <= 14
GROUP BY u.id, b.author
HAVING COUNT(DISTINCT book_id) = 2
;


SELECT
    u.id as ID,
    CONCAT_WS(' ', u.first_name, u.last_name) as Name,
    b.author as Author,
    GROUP_CONCAT(b.name ORDER BY b.name SEPARATOR ', ') as Books
FROM users u
INNER JOIN user_books ub
    ON u.id = ub.user_id
INNER JOIN books b
    ON ub.book_id = b.id
WHERE TIMESTAMPDIFF(YEAR, u.birthday, CURRENT_DATE) BETWEEN  7 AND 17
  -- AND TIMESTAMPDIFF(DAY, ub.get_date, ub.return_date) <= 14
GROUP BY u.id, b.author
HAVING COUNT(DISTINCT ub.book_id) = 2
  AND COUNT(DISTINCT b.author) = 1
  AND MAX(ub.return_date) >= CURRENT_DATE() - INTERVAL 14 DAY
;


SELECT
    u.id as ID,
    CONCAT_WS(' ', u.first_name, u.last_name) as Name,
    b.author as Author,
    GROUP_CONCAT(DISTINCT b.name ORDER BY b.name SEPARATOR ', ') as Books
FROM users u
INNER JOIN user_books ub
    ON u.id = ub.user_id
INNER JOIN books b
    ON ub.book_id = b.id
WHERE
  user_id in (
      SELECT ub1.user_id
      FROM user_books ub1
      INNER JOIN books b1
          ON ub1.book_id = b1.id
      GROUP BY ub1.user_id, b1.author
      HAVING COUNT(DISTINCT book_id) = 2
    ) -- users with 2 books by the same author
  AND TIMESTAMPDIFF(YEAR, u.birthday, CURRENT_DATE) BETWEEN  7 AND 17
    AND TIMESTAMPDIFF(DAY, ub.get_date, ub.return_date) <= 14
GROUP by user_id
HAVING COUNT(DISTINCT book_id) = 2


SELECT
    u3.id as ID,
    CONCAT_WS(' ', u3.first_name, u3.last_name) as Name,
    b3.author as Author,
    GROUP_CONCAT(DISTINCT b3.name ORDER BY b3.name SEPARATOR ', ') as Books
FROM (
  SELECT DISTINCT u.*
  FROM user_books ub
  INNER JOIN books b
  	ON ub.book_id = b.id
  INNER JOIN users u
  	ON ub.user_id = u.id
  GROUP BY ub.user_id, b.author
  HAVING COUNT(DISTINCT book_id) = 2
) u3 -- users with 2 books by the same author
INNER JOIN (
  SELECT *
  FROM user_books ub2
  WHERE TIMESTAMPDIFF(DAY, ub2.get_date, ub2.return_date) <= 14
) ub3 -- not expired user_books
	ON u3.id = ub3.user_id
INNER JOIN books b3
    ON ub3.book_id = b3.id
-- library visitors aged from 7 to 17 years
WHERE TIMESTAMPDIFF(YEAR, u3.birthday, CURRENT_DATE) BETWEEN 7 AND 17
GROUP BY u3.id, b3.author
HAVING COUNT(DISTINCT book_id) = 2
;

/* PARTS OF QUERIES*/

SELECT
    CONCAT_WS(' ', u.first_name, u.last_name) as Name,
    count(*),
    u.*
FROM user_books ub
INNER JOIN books b
    ON ub.book_id = b.id
INNER JOIN users u
    ON ub.user_id = u.id
GROUP BY ub.user_id, b.author
HAVING COUNT(DISTINCT book_id) = 2
-- users with 2 books by the same author
;


SELECT
    b.name,
    ub.*
-- book_id
FROM user_books ub
INNER JOIN books b
    ON ub.book_id = b.id
WHERE TIMESTAMPDIFF(DAY, ub.get_date, ub.return_date) <= 14
-- group BY user_id, book_id
-- not expired books
;