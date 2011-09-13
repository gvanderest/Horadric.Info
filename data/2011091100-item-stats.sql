ALTER TABLE items ADD COLUMN diablo_id VARCHAR(255) NOT NULL;
ALTER TABLE items ADD COLUMN ilvl INT UNSIGNED NOT NULL DEFAULT 1;
ALTER TABLE items ADD COLUMN clvl INT UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE items ADD COLUMN version_added VARCHAR(255) NOT NULL;
ALTER TABLE items CHANGE COLUMN cost gold INT UNSIGNED NOT NULL DEFAULT 0;
CREATE INDEX ilvl ON items (ilvl);
CREATE INDEX clvl ON items (clvl);
CREATE UNIQUE INDEX diablo_id ON items (diablo_id);
ALTER TABLE items ADD COLUMN notes VARCHAR(255);
ALTER TABLE items ADD COLUMN subtype VARCHAR(255); 
ALTER TABLE items ADD COLUMN slot VARCHAR(255); 

ALTER TABLE items ADD COLUMN sockets_min INT UNSIGNED NOT NULL DEFAULT 0; 
ALTER TABLE items ADD COLUMN sockets_max INT UNSIGNED NOT NULL DEFAULT 0; 
ALTER TABLE items ADD COLUMN armor INT UNSIGNED NOT NULL DEFAULT 0; 
ALTER TABLE items ADD COLUMN damage_min INT UNSIGNED NOT NULL DEFAULT 0; 
ALTER TABLE items ADD COLUMN damage_max INT UNSIGNED NOT NULL DEFAULT 0; 
ALTER TABLE items ADD COLUMN speed FLOAT NOT NULL DEFAULT 1.0; 

CREATE INDEX slot ON items (slot);
CREATE INDEX sockets_min ON items (sockets_min);
CREATE INDEX sockets_max ON items (sockets_max);
