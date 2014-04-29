<?php
namespace Eksmo\Cinema\Model;

/**
 * @method static Film create()
 */
class Film extends AbstractModel
{
    public static $_table = 'film';

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}