<?php

class StudentsDataGateway {

    protected $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function addStudent(Student $student) {
        $students = $this->pdo->prepare("INSERT INTO students (`name`, `secondname`, `sex`, `group`, `email`, `score`, `birthyear`, `local`, `cookie`) VALUES (:name, :secondname, :sex, :group, :email, :score, :birthyear, :local, :cookie)");
        $students->bindValue(":name", $student->name);
        $students->bindValue(":secondname", $student->secondName);
        $students->bindValue(":sex", $student->sex);
        $students->bindValue(":group", $student->group);
        $students->bindValue(":email", $student->email);
        $students->bindValue(":score", $student->score);
        $students->bindValue(":birthyear", $student->birthYear);
        $students->bindValue(":local", $student->local);
        $students->bindvalue(":cookie", $student->cookie);
        $students->execute();
    }

    public function searchStudents($search) {
        $students = $this->pdo->prepare("SELECT * FROM students WHERE CONCAT(`name`, ' ', `secondname`, ' ', `group`) LIKE :search");
        //$students = $this->pdo->prepare("SELECT * FROM students WHERE `name` LIKE :search OR `secondname` LIKE :search OR `group` LIKE :search");
        $students->bindValue(":search", "%" . $search . "%");
        $students->execute();
        return $students;
    }

    public function getStudent($cookie) {
        $students = $this->pdo->prepare("SELECT * FROM students WHERE `cookie` = :cookie;");
        $students->bindValue(":cookie", $cookie);
        $students->execute();
        $rows = $students->fetch(PDO::FETCH_NUM);
        return $rows;
    }

    public function countAllStudents() {
        $studentsTotal = $this->pdo->query("SELECT COUNT(*) FROM students");
        return $studentsTotal->execute();
    }

    public function updateStudent(Student $student) {
        $students = $this->pdo->prepare("UPDATE students SET `name` = :name, `secondname` = :secondname, `sex` = :sex, `group` = :group, `email` = :email, `score` = :score, `birthyear` = :birthyear, `local` = :local WHERE `id` = :id");
        $students->bindValue(":name", $student->name);
        $students->bindValue(":secondname", $student->secondName);
        $students->bindValue(":sex", $student->sex);
        $students->bindValue(":group", $student->group);
        $students->bindValue(":email", $student->email);
        $students->bindValue(":score", $student->score);
        $students->bindValue(":birthyear", $student->birthYear);
        $students->bindValue(":local", $student->local);
        $students->bindValue(":id", $student->id);
        $students->execute();
    }

    public function isEmailUnique($email) {
        $students = $this->pdo->prepare("SELECT COUNT(*) FROM students WHERE email = :email");
        $students->bindValue(":email", $email);
        $students->execute();
        $result = $students->fetchColumn();
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
        $students = $this->pdo->prepare($sql);
        $students->bindValue(":limit", intval($limit, 10), PDO::PARAM_INT);
        $students->bindValue(":offset", intval($offset, 10), PDO::PARAM_INT);
        $students->execute();
        return $students;
    }

}
