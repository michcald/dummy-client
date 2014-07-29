<?php

namespace Michcald\DummyClient\Dao;

class Paginator
{
    private $totalResults;
    
    private $currentPage;
    
    private $totalPages;
    
    public function setTotalResults($totalResults)
    {
        $this->totalResults = $totalResults;
        
        return $this;
    }
    
    public function getTotalResults()
    {
        return $this->totalResults;
    }
    
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
        
        return $this;
    }
    
    public function getCurrentPage()
    {
        return $this->currentPage;
    }
    
    public function setTotalPages($totalPages)
    {
        $this->totalPages = $totalPages;
        
        return $this;
    }
    
    public function getTotalPages()
    {
        return $this->totalPages;
    }
}