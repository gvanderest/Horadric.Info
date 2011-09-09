-- track the views by accounts on threads
-- as well as keep track of subscriptions to forums and threads

-- the latest views an account has made on a thread
DROP TABLE IF EXISTS forum_thread_views;
CREATE TABLE forum_thread_views (
    id          INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    account_id  INT UNSIGNED,
    thread_id   INT UNSIGNED,
    date_viewed DATETIME
);
CREATE UNIQUE INDEX account_thread ON forum_thread_views (account_id, forum_id);

-- subscription_type: forum, thread .. the name of the entity type being tracked, basically
-- subscrpition_id: the entity identifier
DROP TABLE IF EXISTS forum_subscriptions;
CREATE TABLE forum_subscriptions (
    id              INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    subscription_type VARCHAR(255) NOT NULL,
    subscription_id   INT UNSIGNED NOT NULL,
    account_id      INT UNSIGNED NOT NULL,
    date_created    DATETIME NOT NULL,
    date_expires    DATETIME
);
