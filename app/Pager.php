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

    public function getSortedLinkForPage($pageNumber, $sort, $order, $search) {
        $link = $this->getLinkForPage($pageNumber) . "&" . http_build_query([
                    'sort' => $sort,
                    'order' => $order,
                    'search' => $search
        ]);
        return $link;
    }

    public function getLinkForLastPage() {
        return $this->template . $this->getTotalPages();
    }

    //Get LIMIT and OFFSET for StudentsDataGateway 
    //function getStudents()
    public function getLimit($pageNumber) {
        return $this->recordsPerPage;
    }

    public function getOffset($pageNumber) {
        return $pageNumber * $this->recordsPerPage - $this->recordsPerPage;
    }

    public function getBackwardLink($currentPage, $sort, $order, $search) {
        if ($currentPage == 1) {
            return "<li class='disabled'><span>&laquo;</span></li>";
        } else {
            return "<li><a href='{$this->getSortedLinkForPage($currentPage - 1, $sort, $order, $search)}'>&laquo;</a></li>";
        }
    }

    public function getNumbersLinks($currentPage, $sort, $order, $search) {
        $link = '';
        for ($i = 1; $i < $this->getTotalPages(); $i++) {
            if ($i == 1 && $currentPage == 1) {
                $link .= "<li class='active'><span>1</span></li>";
            } elseif ($i == $this->getTotalPages() && $currentPage == $this->getTotalPages()) {
                $link .= "<li class='active'><span>{$this->getTotalPages()}</span></li>";
            } elseif ($i == $currentPage) {
                $link .= "<li class='active'><a href='{$this->getSortedLinkForPage($i, $sort, $order, $search)}'>{$i}</a></li>";
            } else {
                $link .= "<li><a href='{$this->getSortedLinkForPage($i, $sort, $order, $search)}'>{$i}</a></li>";
            }
        }
        return $link;
    }

    public function getForwardLink($currentPage, $sort, $order, $search) {
        if ($currentPage == $this->getTotalPages()) {
            return "<li class='disabled'><span>&raquo;</span></li>";
        } else {
            return "<li><a href='{$this->getSortedLinkForPage($currentPage + 1, $sort, $order, $search)}'>&raquo;</a></li>";
        }
    }

}
