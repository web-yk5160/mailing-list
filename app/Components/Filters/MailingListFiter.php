<?php

namespace App\Components\Filters;


use Illuminate\Support\Collection;



class MailingListFilter extends CollectionFilter
{

  /**
   * @var Collectioon
   */
  private $subscriberEmails;

  /**
   * @var Collectioon
   */
  public $errorBug;

  /**
   * MailingListFilter constructor.
   *
   * @param Collection $collection,
   * @param Collection $subscriberEmails
   */
  public function __construct(
    Collection $collection,
    Collection $subscriberEmails
    )
  {
    parent::__construct($collection);

    $this->subscriberEmails = $subscriberEmails;
  }


    /**
     * Get filtered collection.
     *
     * @return Collection
     */
  public function all() : Collection
  {
    $all = $this->collection->map([$this, 'purify']);
    $invalidEmails = $all->filter([$this, 'invalidEmails']);
    $nonUniqueEmails = $invalidEmails->unique('email');
    $duplicateEmails = $nonUniqueEmails->filter([$this, 'duplicateEmails']);

    $this->errorBug = new FilterErrorBug(new Collection ([
      'all' => $all,
      'invalidEmails' => $invalidEmails,
      'nonUniqueEmails' => $nonUniqueEmails,
      'duplicateEmails' => $duplicateEmails
    ]));

    return $duplicateEmails;
  }


  /**
   * Purify record.
   *
   * @param array $subscriber
   * @return array
   */
  public function purify(array $subscriber) : array
  {
    $subscriber['email'] = $this->purifyEmail(  (string) $subscriber['email']);

    return $subscriber;
  }

  /**
   * Purify email.
   *
   * @param string $email
   * @return string
   */
  public function purifyEmail($email) : string
  {
    return (string) strtolower(preg_replace('/[^\w@\.\-]/i', '', $email));
  }

  /**
   * Determine if email is valid.
   *
   * @param array $subscriber
   * @return bool
   */
  public function invalidEmails(array $subscriber) : bool
  {
    return filter_var($subscriber['email'], FILTER_VALIDATE_EMAIL);
  }

  /**
   * Determine if subscriber email is already in the database.
   *
   * @param array $subscriber
   * @return bool
   */
  public function duplicateEmails(array $subscriber) : bool
  {
    return ! $this->subscriberEmails->contains($subscriber['email']);
  }
}