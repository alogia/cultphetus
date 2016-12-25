<?php

class Page extends iDisplay {

    public  $title = '';

    private $html  = '';
    private $tags  = array();


    public function display() {
        return $this->html; #FIXME <<<<<<----------------------
    }

    public function setHTML(String $html) {
        $this->html = $html;
    }

    public function addTag($tag) {
        $this->tag[] = $tag;
    }

    public function deleteTag($tag) {
        foreach( $this->tags as $k => $t){
            if( $t == $tag) {
                unset($this->tags[$k]);
                $this->tags = array_values($this->tags);
            }
        }
    }

    public function tags() {
        return $this->tags;
    }

}

?>
