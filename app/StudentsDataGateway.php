<?php

class StudentsDataGateway {

    protected $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function addStudent(Student $student) {
        $pdoStatement = $this->pdo->prepare("INSERT INTO students (`name`, "
                . "`secondName`, `sex`, `groupName`, `email`, `score`, "
                . "`birthYear`, `local`, `cookie`) VALUES (:name, :secondName,"
                . " :sex, :groupName, :email, :score, :birthYear, :local, :cookie)");
        $pdoStatement->bindValue(":name", $student->name);
        $pdoStatement->bindValue(":secondName", $student->secondName);
        $pdoStatement->bindValue(":sex", $student->sex);
        $pdoStatement->bindValue(":groupName", $student->groupName);
        $pdoStatement->bindValue(":email", $student->email);
        $pdoStatement->bindValue(":score", $student->score);
        $pdoStatement->bindValue(":birthYear", $student->birthYear);
        $pdoStatement->bindValue(":local", $student->local);
        $pdoStatement->bindvalue(":cookie", $student->cookie);
        $pdoStatement->execute();
    }

    public function getStudent($cookie) {
        $pdoStatement = $this->pdo->prepare("SELECT * FROM students WHERE `cookie` = :cookie;");
        $pdoStatement->bindValue(":cookie", $cookie);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS, 'Student');
        $student = $pdoStatement->fetch();
        return $student;
    }

    public function countAllStudents() {
        $pdoStatement = $this->pdo->query("SELECT COUNT(*) FROM students");
        $pdoStatement->execute();
        $studentsTotal = $pdoStatement->fetchColumn();
        return $studentsTotal;
    }

    public function updateStudent(Student $student) {
        $pdoStatement = $this->pdo->prepare("UPDATE students SET `name` = :name,"
                . " `secondName` = :secondName, `sex` = :sex, `groupName` = :groupName,"
                . " `email` = :email, `score` = :score, `birthYear` = :birthYear,"
                . " `local` = :local WHERE `id` = :id");
        $pdoStatement->bindValue(":name", $student->name);
        $pdoStatement->bindValue(":secondName", $student->secondName);
        $pdoStatement->bindValue(":sex", $student->sex);
        $pdoStatement->bindValue(":groupName", $student->groupName);
        $pdoStatement->bindValue(":email", $student->email);
        $pdoStatement->bindValue(":score", $student->score);
        $pdoStatement->bindValue(":birthYear", $student->birthYear);
        $pdoStatement->bindValue(":local", $student->local);
        $pdoStatement->bindValue(":id", $student->id);
        $pdoStatement->execute();
    }

    public function isEmailUnique($email, $id) {
        $pdoStatement = $this->pdo->prepare("SELECT COUNT(*) FROM students WHERE email = :email AND id != :id");
        $pdoStatement->bindValue(":email", $email);
        $pdoStatement->bindValue(":id", $id);
        $pdoStatement->execute();
        $result = $pdoStatement->fetchColumn();
        if ($result > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function getStudents($search, $limit, $offset, $sort, $order) {
//        checking if $sort is acceptable
        $fields = ["name", "secondName", "groupName", "score"];
        $key = array_search($sort, $fields);
        $sort = $fields[$key];
//      checking if $order is acceptable
        $directions = ["ASC", "DESC"];
        $key = array_search($order, $directions);
        $order = $directions[$key];

        if ($search == "") {
            $sql = "SELECT * FROM students ORDER BY $sort $order LIMIT :limit OFFSET :offset";
            $pdoStatement = $this->pdo->prepare($sql);
        } else {
            $sql = "SELECT * FROM students WHERE CONCAT(`name`, ' ', `secondName`,"
                    . " ' ', `groupName`) LIKE :search ORDER BY $sort $order LIMIT :limit OFFSET :offset";
            $pdoStatement = $this->pdo->prepare($sql);
            $pdoStatement->bindValue(":search", "%" . $search . "%");
        }

        $pdoStatement->bindValue(":limit", intval($limit, 10), PDO::PARAM_INT);
        $pdoStatement->bindValue(":offset", intval($offset, 10), PDO::PARAM_INT);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS, 'Student');
        $students = $pdoStatement->fetchAll();
        return $students;
    }

    public function isAbiturientExisting($cookie) {
        $sql = "SELECT COUNT(*) FROM students WHERE cookie = :cookie";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->bindValue(":cookie", $cookie);
        $pdoStatement->execute();
        $result = $pdoStatement->fetchColumn();
        if ($result > 0) {
            return TRUE;
        }
        return FALSE;
    }

}
