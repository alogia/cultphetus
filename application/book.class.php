<?php

require_once 'idisplay.class.php';
require_once 'article.class.php';
require_once 'index.class.php';

class Book implements iDisplay {

    private   $uid      = 0;
    private   $articles = array();
    private   $index    = null;

    public    $name     = '';

    public function __construct(string $name, array $articles, Index $index) {
        $this->name = $name;
        $this->articles = $articles;
        $this->index = $index;
        $this->initIndex();
    }

    protected function init(array $articles) {
        foreach($articles as $key => $a) {
            $this->articles[] = array($key, &$a);
        }
        $this->initIndex();
    }

    public function getArticle($key) {
        foreach($this->articles as $art) {
            list($k, $a) = $art;
            if ($k == $key) {
                return $a;
            }
        }

        return null;
    }

    public function sort(string $type) {
        $this->index->sort($type);
    }

    public function display() {
        $str = '';
        $keys = $this->index->keys();
        foreach($keys as $k) {
            $str = $str . $this->getArticle[$k]->display();
        }
        
        return "<div class={$this->name}>{$str}</div>";
    }

    public function displayIndex() {
        return $this->index ? $this->index->display() : "";
    }

    // Returns the key that can be used for lookup
    public function add(Article $article) {
        $id = $article->id() ? $article->id() : ('u' . strval($this->uid++));
        $this->articles[] = array($id, &$article);
        $this->initIndex();
        return $id;
    }

    // Returns the article removed || null
    public function remove($key) {
        foreach($this->articles as $ak => $art) {
            list($k, $a) = $art;
            if ($k == $key) {
                unset($this->articles[$ak]);
                $this->initIndex();
                return $a;
            }
        }
        return null;
    }

    protected function initIndex() {
        $this->index->init($this->articles);
    }

}

?>
