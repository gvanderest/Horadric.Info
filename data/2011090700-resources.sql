-- add class resources
ALTER TABLE classes ADD COLUMN resource VARCHAR(255);
UPDATE classes SET resource = 'Fury' WHERE url = 'barbarian';
UPDATE classes SET resource = 'Hatred / Discipline' WHERE url = 'demon-hunter';
UPDATE classes SET resource = 'Spirit' WHERE url = 'monk';
UPDATE classes SET resource = 'Mana' WHERE url = 'witch-doctor';
UPDATE classes SET resource = 'Arcane Power' WHERE url = 'wizard';
