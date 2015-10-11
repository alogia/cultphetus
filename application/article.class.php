<?php 

require_once 'idisplay.class.php';
require_once 'storage.class.php';

abstract class Article implements iDisplay {

    protected $id    = 0;
    protected $date  = null;

    public    $type  = "";
    public    $title = "";
    public    $body  = "";

    abstract public function display();
    abstract public function displayId();
    abstract public function displayTitle();
    abstract public function displayDate();
    abstract public function displayBody(); 

    public function __construct(int $id, string $type, string $title, DateTime $date, string $body) {
        $this->id    = $id;
        $this->type  = $type;
        $this->title = $title;
        $this->date  = $date;
        $this->body  = $body;
    }

    public function id() {
        return $this->id;
    }

    public function date() {
        return $this->date;
    }

    public function save(Storage $database) {
        // If id == 0, assume this article does not exist in the database yet
        if($this->id == 0) {
            $res = $database->save($this->type, $this->title, $this->body);
            $this->become($database, $this->type, $res['id']);
        } else {
            $database->modify($this->type, $this->id, $this->title, $this->body);
        }
    }

    public function duplicate(Article $article) {
        $this->id    = $article->id();
        $this->type  = $article->type;
        $this->title = $article->title;
        $this->date  = $article->date();
        $this->body  = $article->body;
    }
    
    public function become(Storage $database, string $type, int $id) {
        try {
            $r = $database->getID($type, $id);
            $this->id    = $r['id'];
            $this->type  = $r['type'];
            $this->title = $r['title'];
            $this->date  = $r['date'];
            $this->body  = $r['body'];

        } catch (Exception $e) {

        }
    }
}

?>
