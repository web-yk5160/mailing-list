<?php

namespace App\Components\Filters;


use Illuminate\Support\Collection;



abstract class CollectionFilter
{
  /**
   * @var Collection
   */
    protected $collection;

  /**
   * CollectionFilter constructor.
   *
   * @param Collection $collection
   */
  public function __construct(Collection $collection)
  {
    $this->collection = $collection;
  }
}