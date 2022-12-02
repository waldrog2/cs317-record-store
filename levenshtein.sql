DELIMITER $$
CREATE FUNCTION levenshtein( s1 VARCHAR(255), s2 VARCHAR(255) )
    RETURNS INT
    DETERMINISTIC
    BEGIN
        DECLARE s1_len, s2_len, i, j, c, c_temp, cost INT; -- declare variables
        DECLARE s1_char CHAR; -- declare variables
        -- max strlen=255
        DECLARE cv0, cv1 VARBINARY(256); -- declare variables

        SET s1_len = CHAR_LENGTH(s1), s2_len = CHAR_LENGTH(s2), cv1 = 0x00, j = 1, i = 1, c = 0; -- set default values

        IF s1 = s2 THEN -- if strings are equal, the distance is 0
            RETURN 0;
        ELSEIF s1_len = 0 THEN -- if s1 is 0, the distance is the length of s2
            RETURN s2_len;
        ELSEIF s2_len = 0 THEN -- same as above, but vice versa
            RETURN s1_len;
        ELSE
            WHILE j <= s2_len DO -- go over every character and check if it counts toward the distance
                SET cv1 = CONCAT(cv1, UNHEX(HEX(j))), j = j + 1; -- concat add all numbers between 1 and s2_len to cv1
            END WHILE;
            WHILE i <= s1_len DO -- for every character in s1 
                SET s1_char = SUBSTRING(s1, i, 1), c = i, cv0 = UNHEX(HEX(i)), j = 1; -- set the current character being checked to the ith one. set total cost = i, set cv0 to bytes of i, set j to 1
                WHILE j <= s2_len DO -- for every character in s2
                    SET c = c + 1; -- increment total cost
                    IF s1_char = SUBSTRING(s2, j, 1) THEN 
                        SET cost = 0; ELSE SET cost = 1; -- if char is equal in both strings, set cost to 0
                    END IF;
                    SET c_temp = CONV(HEX(SUBSTRING(cv1, j, 1)), 16, 10) + cost; -- set c_temp = the char in cv1 at j converted to decimal + cost (0 or 1)
                    IF c > c_temp THEN SET c = c_temp; END IF; -- if c > c_temp, make c = c_temp
                    SET c_temp = CONV(HEX(SUBSTRING(cv1, j+1, 1)), 16, 10) + 1; -- set c_temp = the char in cv1 at j+1 converted to decimal + 1
                    IF c > c_temp THEN
                        SET c = c_temp; -- if c > c_temp, make c = c_temp
                    END IF;
                    SET cv0 = CONCAT(cv0, UNHEX(HEX(c))), j = j + 1; -- add c to cv0 and increment j
                END WHILE;
                SET cv1 = cv0, i = i + 1; -- set cv1 = cv0 (the string after an operation has been performed) and increment i
            END WHILE;
        END IF;
        RETURN c; -- return the total distance
    END$$
DELIMITER ;

DROP FUNCTION levenshtein;


------------------------ attempt to fix dumb stuff
DELIMITER $$
CREATE FUNCTION levenshtein( s1 VARCHAR(255), s2 VARCHAR(255) )
    RETURNS INT
    DETERMINISTIC
    BEGIN
        DECLARE s1_len, s2_len, i, j, c, c_temp, cost INT; -- declare variables
        DECLARE s1_char CHAR; -- declare variables
        -- max strlen=255
        DECLARE cv0, cv1 VARBINARY(256); -- declare variables

        SET s1_len = CHAR_LENGTH(s1), s2_len = CHAR_LENGTH(s2), cv1 = 0x00, j = 1, i = 1, c = 0; -- set default values

        IF s1 = s2 THEN -- if strings are equal, the distance is 0
            RETURN 0;
        ELSEIF s1_len = 0 THEN -- if s1 is 0, the distance is the length of s2
            RETURN s2_len;
        ELSEIF s2_len = 0 THEN -- same as above, but vice versa
            RETURN s1_len;
        ELSE
            WHILE j <= s2_len DO -- go over every character and check if it counts toward the distance
                SET cv1 = CONCAT(cv1, ASCII(j), j = j + 1; -- concat add all numbers between 1 and s2_len to cv1
            END WHILE;
            WHILE i <= s1_len DO -- for every character in s1 
                SET s1_char = SUBSTRING(s1, i, 1), c = i, cv0 = UNHEX(HEX(i)), j = 1; -- set the current character being checked to the ith one. set total cost = i, set cv0 to bytes of i, set j to 1
                WHILE j <= s2_len DO -- for every character in s2
                    SET c = c + 1; -- increment total cost
                    IF s1_char = SUBSTRING(s2, j, 1) THEN 
                        SET cost = 0; ELSE SET cost = 1; -- if char is equal in both strings, set cost to 0
                    END IF;
                    SET c_temp = CHAR(SUBSTRING(cv1, j, 1)) + cost; -- set c_temp = the char in cv1 at j converted to decimal + cost (0 or 1)
                    IF c > c_temp THEN SET c = c_temp; END IF; -- if c > c_temp, make c = c_temp
                    SET c_temp = CHAR(SUBSTRING(cv1, j+1, 1)) + 1; -- set c_temp = the char in cv1 at j+1 converted to decimal + 1
                    IF c > c_temp THEN
                        SET c = c_temp; -- if c > c_temp, make c = c_temp
                    END IF;
                    SET cv0 = CONCAT(cv0, ASCII(c)), j = j + 1; -- add c to cv0 and increment j
                END WHILE;
                SET cv1 = cv0, i = i + 1; -- set cv1 = cv0 (the string after an operation has been performed) and increment i
            END WHILE;
        END IF;
        RETURN c; -- return the total distance
    END$$
DELIMITER ;