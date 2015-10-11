<?php

class Storage extends SQLite3 {

    public function __construct($path) {
        $this->open($path);
    }

    public function __destruct() {
        $this->close();
    }

    public function save($table, $title, $body) {
        $sql = "INSERT INTO {$table} (title, post) VALUES (?, ?)";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(1, $title, SQLITE3_TEXT);
        $stmt->bindValue(1, $body, SQLITE3_TEXT);
        $res = $stmt->exec();
        $id = $this->lastInsertRowID();
        return getID($table, $id);
    }

    public function getAll($table) {
        $sql = "SELECT * FROM {$table}";
        $res = $this->query($sql);
        $arts = array();
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $arts[] = $this->resToAr($table, $row);
        }
        return $arts;
    }

    public function getID($table, $id) {
        $sql = "SELECT * FROM {$table} WHERE id={$id}";
        return $this->resToAr($table, $this->querySingle($sql));
    }

    public function modify($table, $id, $title, $body) {
        $sql = "UPDATE {$table} SET title=:title, post=:body WHERE id=:id";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->bindValue(':title', $title, SQLITE3_TEXT);
        $stmt->bindValue(':body', $body, SQLITE3_TEXT);
        $stmt->exec();
        return getID($table, $id);
    }

    protected function resToAr($table, $res) {
        return array(
            'id'    => intval($res['id']),
            'type'  => $table,
            'title' => $res['title'],
            'date'  => new DateTime($res['date']),
            'body'  => $res['post'] );
    }
}

?>
