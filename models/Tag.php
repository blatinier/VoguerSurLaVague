<?php
class Tag extends Model {

    public $id;
    public $name;

    public function __construct($id, $name, $slug) {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
    }

    public static function load($dict) {
        return new Tag($dict['id'], $dict['name'], $dict['slug']);
    }
}
