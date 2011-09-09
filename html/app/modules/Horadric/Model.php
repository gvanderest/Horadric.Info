<?php
/**
 * Horadric Model for neato stuff
 */
class Horadric_Model extends Exo_Model
{
    /**
     * What's the level of a person from their experience
     * @param int $exp
     * @return int level
     */
    public static function get_level_from_experience($exp)
    {
        return 1;
    }
}
