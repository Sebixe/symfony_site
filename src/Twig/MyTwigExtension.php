<?php
namespace App\Twig;

use App\Search\SearchFormGenerator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MyTwigExtension extends AbstractExtension
{

    private SearchFormGenerator $searchFormGenerator;

    /**
     * MyTwigExtension constructor.
     * @param SearchFormGenerator $searchFormGenerator
     */
    public function __construct(SearchFormGenerator $searchFormGenerator)
    {
        $this->searchFormGenerator = $searchFormGenerator;
    }
    public function getFunctions()
    {
        return [
                new TwigFunction('getSearchForm', [$this->searchFormGenerator,'getSearchForm'])
        ];
    }


}