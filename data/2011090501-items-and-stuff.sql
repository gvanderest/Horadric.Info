
-- items, that all items are based on
-- item types 'weapon', 'armor', 'runestone', 'gem', 'reagent', 'potion', 'elixir', 'consumable', 'inventory', 'trinket', 'book', 'page', 'dye', 'vial'(?), 
-- quality: normal, magic, rare, set, unique, legendary, quest, runestone, gem, trinket(?)
-- basis_id links to another item that this item is based upon
DROP TABLE IF EXISTS items;
CREATE TABLE items (
    id      INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    url     VARCHAR(255) NOT NULL UNIQUE,
    name    VARCHAR(255),
    quality VARCHAR(255),
    type    VARCHAR(255),
    sprite  VARCHAR(255),
    level   INT UNSIGNED,
    flavor  VARCHAR(255),
    basis_id INT UNSIGNED,
    cost    INT UNSIGNED NOT NULL DEFAULT 0
);

-- the effects anything in the game can have
-- effect_type is the 'field' this effect does.. like power, or maybe grants a skill? hard to say.
-- effect_description uses placeholder {value} to display the min/max
-- effect_number: 'number' or 'percent'
DROP TABLE IF EXISTS effects;
CREATE TABLE effects (
    id          INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    affix       VARCHAR(255),
    effect_type VARCHAR(255) NOT NULL,
    effect_min  FLOAT,
    effect_max  FLOAT,
    effect_number VARCHAR(255) DEFAULT 'number',
    effect_description VARCHAR(255)
);

-- the names of item sets
DROP TABLE IF EXISTS item_sets;
CREATE TABLE item_sets (
    id      INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    url     VARCHAR(255) NOT NULL UNIQUE,
    name    VARCHAR(255) NOT NULL
);

-- the effects this set can produce, and the pieces required to do it
DROP TABLE IF EXISTS item_set_effects;
CREATE TABLE item_set_effects (
    id      INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    pieces  INT UNSIGNED NOT NULL,
    effect_id   INT UNSIGNED NOT NULL
);

-- the items that are involved in making the set
DROP TABLE IF EXISTS item_set_items;
CREATE TABLE item_set_items (
    id      INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    set_id  INT UNSIGNED NOT NULL,
    item_id INT UNSIGNED NOT NULL
);

-- equipment, that equippable items come from
DROP TABLE IF EXISTS equipment;
CREATE TABLE equipment (
    id INT UNSIGNED NOT NULL PRIMARY KEY UNIQUE,
    durability INT UNSIGNED NOT NULL DEFAULT 0,
    sockets INT UNSIGNED NOT NULL DEFAULT 0
);

-- weapons
-- weapon types: axe, bow, crossbow, pistol(?), dagger, fist, mace, polearm, stave, sword, thrown, wand
DROP TABLE IF EXISTS weapons;
CREATE TABLE weapons (
    id INT UNSIGNED NOT NULL PRIMARY KEY UNIQUE,
    hands   INT UNSIGNED NOT NULL DEFAULT 1,
    weapon_type     VARCHAR(255) NOT NULL,
    damage_min      INT UNSIGNED NOT NULL DEFAULT 1,
    damage_max      INT UNSIGNED NOT NULL DEFAULT 1,
    speed           DECIMAL(4,2) DEFAULT 1.0
);

-- armors
-- armor types: amulet, belt, boots, chest, gloves, helm, legs, ring, shield, shoulder, orb
DROP TABLE IF EXISTS armors;
CREATE TABLE armors (
    id INT UNSIGNED NOT NULL PRIMARY KEY UNIQUE,
    armor_type VARCHAR(255) NOT NULL,
    armor       INT UNSIGNED NOT NULL DEFAULT 0
);


-- runestones
-- color: the color of the runestone will be simple: white, black, red, blue, yellow
DROP TABLE IF EXISTS runestones;
CREATE TABLE runestones (
    id INT UNSIGNED NOT NULL PRIMARY KEY UNIQUE,
    color   VARCHAR(255)
);



-- npcs
-- npc_type will 
DROP TABLE IF EXISTS npcs;
CREATE TABLE npcs (
    id      INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    url     VARCHAR(255) NOT NULL UNIQUE,
    name    VARCHAR(255) NOT NULL,
    npc_type    VARCHAR(255) NOT NULL

);

-- gems
-- effect_helm
DROP TABLE IF EXISTS gems;
CREATE TABLE gems (
    id INT UNSIGNED NOT NULL PRIMARY KEY UNIQUE,
    color VARCHAR(255) NOT NULL
);
