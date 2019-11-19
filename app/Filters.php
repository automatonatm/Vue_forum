<?php


namespace App;


use Illuminate\Http\Request;

abstract class Filters
{
    protected $request, $builder;

    protected $filters = [];


    /**
     *  ThreadFilters constructor.
     **/

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)

    {

        $this->builder = $builder;

        collect($this->getFilters())
            ->filter(function ($value, $filter) {
                return method_exists($this, $filter);
            })
            ->each(function ($value, $filter) {
                $this->$filter($value);
            });


        foreach ($this->getFilters() as $filter => $value) {

            if(method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    public function getFilters()
    {
        return collect($this->request->only($this->filters));
    }

    /**
     * @param $filter
     * @return bool
     */

}