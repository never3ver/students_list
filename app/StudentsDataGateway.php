<?php

class StudentsDataGateway {

    protected $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function addStudent(Student $student) {
        $pdoStatement = $this->pdo->prepare("INSERT INTO students (`name`, `secondname`, `sex`, `group`, `email`, `score`, `birthyear`, `local`, `cookie`) VALUES (:name, :secondname, :sex, :group, :email, :score, :birthyear, :local, :cookie)");
        $pdoStatement->bindValue(":name", $student->name);
        $pdoStatement->bindValue(":secondname", $student->secondName);
        $pdoStatement->bindValue(":sex", $student->sex);
        $pdoStatement->bindValue(":group", $student->group);
        $pdoStatement->bindValue(":email", $student->email);
        $pdoStatement->bindValue(":score", $student->score);
        $pdoStatement->bindValue(":birthyear", $student->birthYear);
        $pdoStatement->bindValue(":local", $student->local);
        $pdoStatement->bindvalue(":cookie", $student->cookie);
        $pdoStatement->execute();
    }

    public function searchStudents($search) {
        $pdoStatement = $this->pdo->prepare("SELECT * FROM students WHERE CONCAT(`name`, ' ', `secondname`, ' ', `group`) LIKE :search");
        //$pdoStatement = $this->pdo->prepare("SELECT * FROM students WHERE `name` LIKE :search OR `secondname` LIKE :search OR `group` LIKE :search");
        $pdoStatement->bindValue(":search", "%" . $search . "%");
        $pdoStatement->execute();
        $students = $pdoStatement->fetchAll();
        return $students;
    }

    public function getStudent($cookie) {
        $pdoStatement = $this->pdo->prepare("SELECT * FROM students WHERE `cookie` = :cookie;");
        $pdoStatement->bindValue(":cookie", $cookie);
        $pdoStatement->execute();
        $rows = $pdoStatement->fetch(PDO::FETCH_NUM);
        return $rows;
    }

    public function countAllStudents() {
        $pdoStatementTotal = $this->pdo->query("SELECT COUNT(*) FROM students");
        return $pdoStatementTotal->execute();
    }

    public function updateStudent(Student $student) {
        $pdoStatement = $this->pdo->prepare("UPDATE students SET `name` = :name, `secondname` = :secondname, `sex` = :sex, `group` = :group, `email` = :email, `score` = :score, `birthyear` = :birthyear, `local` = :local WHERE `id` = :id");
        $pdoStatement->bindValue(":name", $student->name);
        $pdoStatement->bindValue(":secondname", $student->secondName);
        $pdoStatement->bindValue(":sex", $student->sex);
        $pdoStatement->bindValue(":group", $student->group);
        $pdoStatement->bindValue(":email", $student->email);
        $pdoStatement->bindValue(":score", $student->score);
        $pdoStatement->bindValue(":birthyear", $student->birthYear);
        $pdoStatement->bindValue(":local", $student->local);
        $pdoStatement->bindValue(":id", $student->id);
        $pdoStatement->execute();
    }

    public function isEmailUnique($email) {
        $pdoStatement = $this->pdo->prepare("SELECT COUNT(*) FROM students WHERE email = :email");
        $pdoStatement->bindValue(":email", $email);
        $pdoStatement->execute();
        $result = $pdoStatement->fetchColumn();
        if (!$result) {
            return TRUE;
        }
    }

    public function getStudents($limit, $offset, $sort, $order) {
//        checking if $sort is acceptable
        $fields = ["name", "secondName", "group", "score"];
        $key = array_search($sort, $fields);
        $sort = $fields[$key];
//      checking if $order is acceptable
        $directions = ["ASC", "DESC"];
        $key = array_search($order, $directions);
        $order = $directions[$key];

        $sql = "SELECT * FROM students ORDER BY $sort $order LIMIT :limit OFFSET :offset";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->bindValue(":limit", intval($limit, 10), PDO::PARAM_INT);
        $pdoStatement->bindValue(":offset", intval($offset, 10), PDO::PARAM_INT);
        $pdoStatement->execute();
        $students = $pdoStatement->fetchAll();
        return $students;
    }

}
