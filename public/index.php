<?php

require_once __DIR__ . '/../app/init.php';
$gateway = new StudentsDataGateway($pdo);

if (!isset($_GET['page'])) {
    $currentPage = 1;
} else {
    $currentPage = intval($_GET['page']);
}
//total entries in database
$totalRecords = $gateway->countAllStudents();
//quantity of records per page is set here
$recordsPerPage = 50;
$pager = new Pager($totalRecords, $recordsPerPage, "index.php?page=");
$limit = $pager->getLimit($currentPage);
$offset = $pager->getOffset($currentPage);

if (!isset($_GET['sort'])) {
    $sort = "";
    $order = "";
} else {
    $sort = strval($_GET['sort']);
    $order = strval($_GET['order']);
}

if (!isset($_GET['search']) || $_GET['search'] === "") {
    $search = "";
} else {
    $search = trim(strval($_GET['search']));
}
//list of students to display
$pageList = $gateway->getStudents($search, $limit, $offset, $sort, $order);

include __DIR__ . '/../templates/index.html';
include __DIR__ . '/../templates/footer.html';
