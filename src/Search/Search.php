<?php


namespace App\Search;


class Search
{

    private string $keyword;
    private $types;
    private $extensions;

    /**
     * @return string
     */
    public function getKeyword(): string
    {
        return $this->keyword;
    }

    /**
     * @param string $keyword
     */
    public function setKeyword(string $keyword): void
    {
        $this->keyword = $keyword;
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @param array $types
     */
    public function setTypes($types): void
    {
        $this->types = $types;
    }
    
     /**
     * @return array
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * @param array $extensions
     */
    public function setExtensions($extensions): void
    {
        $this->extensions = $extensions;
    }



    public function __toString()
    {
        return 'search';
    }


}
