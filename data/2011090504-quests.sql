-- Quests and their objectives

DROP TABLE IF EXISTS quests;
CREATE TABLE quests (
    id      INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name    VARCHAR(255) NOT NULL,
    url     VARCHAR(255) NOT NULL UNIQUE,
    start_type  VARCHAR(255) NOT NULL,
    start_id    INT UNSIGNED
);

-- a series of quests, requiring the previous to be completed before the next
DROP TABLE IF EXISTS quest_series;
CREATE TABLE quest_series (
    id      INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name    VARCHAR(255) NOT NULL,
    url     VARCHAR(255) NOT NULL UNIQUE
);

-- quests in a series
-- rank: the order in which the quest is in the series.. 0-based index
DROP TABLE IF EXISTS quest_series_quests;
CREATE TABLE quest_series_quests (
    id          INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    series_id   INT UNSIGNED,
    quest_id    INT UNSIGNED,
    rank        INT UNSIGNED
);


-- objective_type: 'kill', 'gather', 'talk', 'zone'
-- objective_id: monster id if kill, item id if gather, npc id if talk, zone_id if zone (to visit somewhere?)
-- objective_amount: the amount of whatever to whatever (people to kill, items to gather)
-- zone_id: the id applicable to the zone, if there is one.. for the objective
DROP TABLE IF EXISTS quest_objectives;
CREATE TABLE quest_objectives (
    id      INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    description         VARCHAR(255) NOT NULL,
    objective_type      VARCHAR(255) NOT NULL,
    objective_id        INT UNSIGNED,
    objective_amount    INT UNSIGNED,
    zone_id             INT UNSIGNED
);
