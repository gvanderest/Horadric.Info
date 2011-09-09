-- blue tracker 2.0 posts
-- source_type: twitter, community, oldforum
-- thread_id: if any of these posts are considered replies to an original post, use this
-- blizzard: bool flag for whether or not the posting is from Blizzard
DROP TABLE IF EXISTS blues;
CREATE TABLE blues (
    id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    url             VARCHAR(255) NOT NULL,
    source_type     VARCHAR(255) NOT NULL,
    source_url      VARCHAR(255) NOT NULL,
    source_id       VARCHAR(255) NOT NULL,
    thread_id       INT UNSIGNED,
    author          VARCHAR(255) NOT NULL,
    blizzard        INT(1) UNSIGNED DEFAULT 0,
    title           VARCHAR(255),
    body            TEXT,
    date_created    DATETIME,
    date_updated    DATETIME
);
