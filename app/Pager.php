<?php

class Pager {

    protected $totalRecords;
    protected $recordsPerPage;
    protected $template;

    function __construct($totalRecords, $recordsPerPage, $template) {
        $this->totalRecords = $totalRecords;
        $this->recordsPerPage = $recordsPerPage;
        $this->template = $template; // index.php?page=
    }

    public function getTotalPages() {
        if ($this->totalRecords <= $this->recordsPerPage) {
            return 1;
        } else {
            return ceil($this->totalRecords / $this->recordsPerPage);
        }
    }

    public function getLinkForPage($pageNumber) {
        return $this->template . $pageNumber;
    }

    public function getSortedLinkForPage($pageNumber, $sort, $order) {
        $link = $this->getLinkForPage($pageNumber) . "&" . http_build_query([
                    'sort' => $sort,
                    'order' => $order
        ]);
        return $link;
    }

    public function getLinkForLastPage() {
        return $this->template . $this->getTotalPages();
    }

    //Get LIMIT and OFFSET for StudentsDataGateway 
    //function getStudents()
    public function getLimitAndOffset($pageNumber) {
        $arguments['limit'] = $this->recordsPerPage;
        $arguments['offset'] = $pageNumber * $this->recordsPerPage - $this->recordsPerPage;
        return $arguments;
    }

}
