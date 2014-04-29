<?php
namespace Eksmo\Cinema\Model;

abstract class AbstractModel extends \Model
{
    /**
     * @return AbstractModel
     */
    public static function create()
    {
        return self::getFactory()->create();
    }

    /**
     * @return \ORM|\ORMWrapper
     */
    public static function getFactory()
    {
        return \Model::factory(get_called_class());
    }
}