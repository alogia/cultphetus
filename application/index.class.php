<?php

require_once 'idisplay.class.php';
require_once 'article.class.php';

# Takes an array of type $articles[] = array($key, $value);
abstract class Index implements iDisplay {


    //$index is just a reference to an array of articles passed to init()
    //$sort stores an array of keys for $index with its own key some variable of the 
    //Article in $index[$key]
    //$index[$sort[$date]] would return the Article which contains $date
    protected $index = null;
    protected $sort  = array();

    abstract public function display();

    public function __construct(array $articles) {
        $this->init($articles);
    }

    public function init(array $articles) {
        $this->index &= $articles;
    }

    public function getArticle($key) {
        foreach($this->index as $art) {
            list($k, $a) = $art;
            if ($k == $key) {
                return $a;
            }
        }

        return null;
    }

    public function sort(string $type) {
        $this->sort = array();
        foreach($this->index as $key => $art) {
            list($k, $a) = $art;
            switch ($type) {
            case 'DATE' :
                $this->sort[$a->date()] = $key;
                break;
            case 'ALPHA' : // FIXME <<<<---- This will override two Articles with the same title
                $this->sort[$a->title()] = $key;
                break;
            case 'ADDED' :
                $this->sort[] = $key;
                break;
            default :
            }
        }

        ksort($this->sort);
        return $this->sort;
    }
        
    public function keys() {
        return $this->sort;
    }
}

?>
