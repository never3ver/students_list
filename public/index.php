<?php
require_once __DIR__ . '/../app/init.php';
require_once __DIR__ . '/../autoload.php';

$gateway = new StudentsDataGateway($pdo);
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel='stylesheet' href='/bootstrap/css/bootstrap.min.css'
              type='text/css' media='all'>
        <link href="/bootstrap/styles.css" rel="stylesheet">
        <meta charset="UTF-8">
        <title>List of students</title>
    </head>
    <body>
        <div class="container">
            <?php
            include __DIR__ . '/../templates/header.html';
            if (!isset($_POST['search']) || $_POST['search'] === "") {
                $search = "";
            } else {
                $search = strval($_POST['search']);
                $search = trim($search);
                $list = $gateway->searchStudents($search);
            }

            if (!isset($_GET['page'])) {
                $currentPage = 1;
            } else {
                $currentPage = intval($_GET['page']);
            }

            $totalRecords = $gateway->countAllStudents(); //total entries in database
            var_dump($totalRecords);
            $recordsPerPage = 50; //quantity of records per page is defined here
            $pager = new Pager($totalRecords, $recordsPerPage, "index.php?page=");
            $limits = $pager->getLimitAndOffset($currentPage);
            
            if (!isset($_GET['sort'])) {
                $sort = "";
                $order = "";
            } else {
                $sort = strval($_GET['sort']);
                $order = strval($_GET['order']);
            }
            
            $pageList = $gateway->getStudents($limits['limit'], $limits['offset'], $sort, $order);
            $rows = $pageList->rowCount(); //quantity of records dislpayed on the page

            include __DIR__ . '/../templates/listOfStudents.html';
            ?>
            <?php if (!isset($_COOKIE['name'])): ?>
                <form action="register.php">
                    <button type="submit" class="table btn btn-success">
                        Зарегистрироваться</button>
                </form>
            <?php else: ?>
                <form action="edit.php">
                    <button type="submit" class="table btn btn-success">
                        Редактировать</button>
                </form>
            <?php endif; ?>
        </div>
        <?php if ($totalRecords > $recordsPerPage): ?> 
            <?php include __DIR__ . '/../templates/pagination.html' ?>
        <?php endif; ?>
    </body>
</html>
