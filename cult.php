<?php

error_reporting(E_ALL);

$site_path = realpath(dirname(__FILE__));

define('__SITE_PATH', $site_path);
define('__APPLICATION', __SITE_PATH . '/application/');

require_once 'includes/init.php';


class BlogView extends View {

    private $book    = null;
    private $storage = null;

    public function __construct(Book $book, Model $model) {
        parent::__construct($model);
        $this->book = $book;
    }

    public function display() {
    }

}

class BlogModel extends Model {

    private $storage = null;

    public function __construct(Storage $storage) {
        $this->storage = $storage;
    }

    public function request($request) {}

}

class CultArticle extends Article {

    public function display() {
        $t = $this->displayTitle() . $this->displayDate() . $this->displayBody();
        $id = $this->displayId();

        return "<article id=\"ar{$id}\" >\r {$t} \r</article>";
    }

    public function displayId() {
        return strval($this->id);
    }

    public function displayTitle() {
        return "<h1 class='aTitle'> {$this->title} </h1>";
    }
    
    public function displayDate() {
        $d = $this->date->format('d-m-Y'); 
        return "<div class='aDate'> {$d} </div>";
    }

    public function displayBody() {
        return "<div class='aBody'> {$this->body} </div>";
    }

}

class CultIndex extends Index {

    public function display() {
        $dates = '';
        foreach($this->sort as $key) {
            $dates = $dates . '<li> <a href="#" onclick=\'moveTo("' . $this->getArticle($key)->id() . '")\'> ' . $ar->htmlDate() . '</a></li>';
        }
        return "<ul> {$dates} </ul>";
    }

}

class Cultphetus extends API {

    private $act = null; 
    private $db  = null;
    
    public function __construct($db) {
        $this->act = new ActionHandler();
        $this->db  = new Storage($db);
    }

    public function register($name, $closure) {
        $this->act->register($name, $closure);
    }

    public static function sanitizeString($var) {
        $var = strip_tags($var);
        $var = htmlentities($var);
        return stripslashes($var);
    }

    public function mainHandler() {
        if (isset($_POST['page'])) {
            $this->pageHandler($_POST['page']);
        } else if(isset($_POST['action'])) {
            $this->actionHandler($_POST['action']);
        }
    }

    public function pageHandler($page) {
        switch ($page) {
            case 'about' :
                echo file_get_contents("about.html");
                break; 
            case 'art' :
                echo file_get_contents("grid.html");
                break;
            default:
                $arts = $this->db->queryTableAll($page);
                foreach($arts as $a){
                    echo $a->html();
                }
        }
    }

}


//$cult = new Cultphetus("database/cultphetus.db");
//$cult->mainHandler();



//$model = new pModel();

//$controller = new pController($model);

?>
