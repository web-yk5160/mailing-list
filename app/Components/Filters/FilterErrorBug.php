<?php

namespace App\Components\Filters;

class FilterErrorBug
{
  /**
   * @var \Illuminate\Support\Collection
   */
  private $collection;

  /**
   * @var array
   */
  private $errors = [];

  /**
   * @var \Illuminate\Support\Collection
   */
  private $current;

  public function __construct(\Illuminate\Support\Collection $collection)
  {
    $this->collection = $collection;
    $this->current = $collection->first();

    $this->parse();
  }

  /**
   * @var \Illuminate\Support\Collection
   */
  private function parse()
  {
    $this->collection->each(function (Collection $item, $key) {
      $this->addError($key, $this->current->count() - $item->count());
      $this->current = $item;
    });
  }

  /**
   * Add error.
   *
   * @param string $key
   * @param int $count
   * @return void
   */
  private function addError(string $key, int $count)
  {
    $this->errors[$key] = $count;
  }

  /**
   * Get count of errors for a given key.
   *
   * @param string $key
   * @param int
   */
  public function get()
  {
    return $this->errors[$key];
  }

}
