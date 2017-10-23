<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Lock;

use SetBased\Abc\Abc;

/**
 * Class for named locks that are released on commit or rollback.
 *
 * The names of the locks are unique with respect to the schema of the data layer and company of ABC.
 */
class CoreNamedLock implements NamedLock
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the lock.
   *
   * @var int|null
   */
  private $lnmId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getId()
  {
    return $this->lnmId;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getLock($id)
  {
    Abc::$DL->abcLockNamedGetLock(Abc::$companyResolver->getCmpId(), $id);

    $this->lnmId = $id;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getName()
  {
    if ($this->lnmId===null) return null;

    return Abc::$DL->abcLockNamedGetName(Abc::$companyResolver->getCmpId(), $this->lnmId);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
