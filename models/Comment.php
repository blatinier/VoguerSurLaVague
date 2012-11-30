<?php
class Comment extends Model {

    public $id;
    public $idarticle;
    public $moment;
    public $pseudo;
    public $commentaire;
    public $site;
    public $ip;

    public function __construct($id, $idarticle, $moment, $pseudo, $commentaire, $site, $ip) {
        $this->id = $id;
        $this->idarticle = $idarticle;
        $this->moment = $moment;
        $this->pseudo = $pseudo;
        $this->commentaire = $commentaire;
        $this->site = $site;
        $this->ip = $ip;
    }

    public static function load($dict) {
        return new Comment($dict['id'],
            $dict['idarticle'],
            $dict['moment'],
            $dict['pseudo'],
            $dict['commentaire'],
            $dict['site'],
            $dict['ip']);
    }
}
