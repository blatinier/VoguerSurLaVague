<?php
class tag_cloud extends Widget {
    public function __construct($cfg, $mysql_connector) {
        parent::__construct($cfg, $mysql_connector);
    }
    
    public function _do_($action_name) {
        $tag_repo = new TagRepository();
        $tags = $tag_repo->get_all_with_count();
        //sorting tags into X groups
        $X = 4;
        $l = count($tags);
        $order = array();
        foreach ($tags as $i => $t) {
            $order[$i] = $t->nb;
        }
        asort($order);
        $i = 0;
        $j = 1;
        foreach ($order as $k => $v) {
            if ($i > $j * $l / $X) {
                $j += 1;
            }
            $tags[$k]->size = $j;
            $i += 1;
        }
        $this->data['tags'] = $tags;
    }
}
