<?php
namespace ECidade\V3\Modification\Data;

use \ECidade\V3\Extension\AbstractMetadata;

/**
 * @package modification
 */
class Group extends AbstractMetadata {

  /**
   * @var array
   */
  private $groups = array();

  public function __construct() {
    parent::__construct(ECIDADE_MODIFICATION_DATA_PATH . 'group/modifications');
  }

  /**
   * @param string $modificationID
   * @param string $groupID
   * @param boolean $overwrite
   * @return boolean
   */
  public function add($groupID, $modificationID) {

    if (isset($this->groups[$groupID]) && in_array($modificationID, $this->groups[$groupID])) {
      return false;
    }

    $this->groups[$groupID][] = $modificationID;
    return true;
  }

  /**
   * @param string $groupID
   * @param string $modificationID
   * @return boolean
   */
  public function removeItem($groupID, $modificationID = null) {

    if (!isset($this->groups[$groupID])) {
      return false;
    }

    if ($modificationID !== null) {

      $key = array_search($modificationID, $this->groups[$groupID]);
      if ($key === false) {
        return false;
      }

      array_splice($this->groups[$groupID], $key, 1);
    }

    if ($modificationID === null || empty($this->groups[$groupID])) {
      unset($this->groups[$groupID]);
    }

    return true;
  }

  /**
   * @param string $groupID
   * @return array
   */
  public function get($groupID = null) {

    if ($groupID === null) {
      return $this->groups;
    }

    if (!isset($this->groups[$groupID])) {
      return array();
    }

    return $this->groups[$groupID];
  }

  /**
   * @param string $modificationID
   * @return array
   */
  public function getSiblings($modificationID) {

    foreach ($this->groups as $groupID => $modificationsID) {
      if (in_array($modificationID, $modificationsID)) {
        return $this->get($groupID);
      }
    }

    return array();
  }

}
