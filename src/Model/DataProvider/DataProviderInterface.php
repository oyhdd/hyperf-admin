<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Model\DataProvider;

interface DataProviderInterface
{
    /**
     * Get the instance as an array.
     * @return array
     */
    public function toArray(): array;

    /**
     * Set filter column
     * @return $this
     */
    public function setFilterColumns(array $filterColumns);

    /**
     * Add a column sortable to column header.
     *
     * @param string $column
     * @param string $cast  asc,desc
     *
     * @return $this
     */
    public function sortable(string $column, string $cast);

    /**
     * Retrieve all input data from request, include query parameters, parsed body and json body.
     * @return $this
     */
    public function getParams();

}
