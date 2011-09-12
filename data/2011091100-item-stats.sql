ALTER TABLE items DROP COLUMN level;
ALTER TABLE items ADD COLUMN diablo_id VARCHAR(255) NOT NULL;
ALTER TABLE items ADD COLUMN ilvl INT UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE items ADD COLUMN clvl INT UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE items ADD COLUMN version_added VARCHAR(255) NOT NULL;
ALTER TABLE items CHANGE COLUMN cost gold INT UNSIGNED NOT NULL DEFAULT 0;
CREATE INDEX ON items ilvl (ilvl);
CREATE INDEX ON items clvl (clvl);
CREATE UNIQUE INDEX ON items diablo_id (diablo_id);