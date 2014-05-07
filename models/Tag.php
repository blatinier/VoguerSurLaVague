<?php
class Tag extends Model {

    public $id;
    public $name;

    public function __construct($id, $name, $slug, $nb=0) {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->nb = $nb;
    }

    public static function load($dict) {
        if (array_key_exists('nb', $dict)) {
            return new Tag($dict['id'], $dict['name'], $dict['slug'],
                           $dict['nb']);
        } else {
            return new Tag($dict['id'], $dict['name'], $dict['slug']);
        }
    }
}
