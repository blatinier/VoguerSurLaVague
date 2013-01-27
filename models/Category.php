<?php
class Category extends Model {

    public $id;
    public $titre;
    public $slug;
    public $abstract;
    public $type;

    public function __construct($id, $titre, $slug, $abstract, $type) {
        $this->id = $id;
        $this->titre = $titre;
        $this->slug = $slug;
        $this->abstract = $abstract;
        $this->type = $type;
    }

    public static function load($dict) {
        if (empty($dict['id'])) {
            $dict['id'] = null;
        }
        return new Category($dict['id'],
            $dict['titre'],
            $dict['slug'],
            $dict['abstract'],
            $dict['type']);
    }
}
