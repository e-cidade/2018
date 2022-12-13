<?php
namespace ECidade\V3\Extension;

use \ECidade\V3\Extension\AbstractMetadata;

/**
 * @package core
 */
class PackageMetadata extends AbstractMetadata {

  const STATUS_ENABLED = 1;
  const STATUS_DISABLED = 2;

  const TYPE_GLOBAL = 'global';
  const TYPE_USER = 'user'; 

  /**
   * @var string
   */
  private $id;

  /**
   * @var string 
   */
  private $label;

  /**
   * @var string
   */
  private $type = self::TYPE_GLOBAL;

  /**
   * @var integer
   */
  private $status = self::STATUS_DISABLED;

  /**
   * status por usuario
   * @var array
   */
  private $usersStatus = array();
  
  /**
   * versao do package
   * @var string
   */
  private $version;

  /**
   * @var string $path
   */
  public function __construct($path) {
    parent::__construct($path);
  }

  public function setStatus($status, $user = null) {

    if ($user !== null && $this->isUserType()) {
      return $this->usersStatus[$user] = $status;
    }

    $this->status = $status;
  }

  public function getStatus($user = null) {

    if ($user !== null && $this->isUserType() && isset($this->usersStatus[$user])) {
      return $this->usersStatus[$user];
    }
    return $this->status;
  }

  public function isEnabled($user = null) {
    return $this->getStatus($user) === self::STATUS_ENABLED; 
  }

  public function setUsersStatus(array $usersStatus) {

    $this->usersStatus = $usersStatus;
    return $this;
  }

  public function getUsersStatus() {
    return $this->usersStatus;
  } 

  public function isUserType() {
    return $this->type === self::TYPE_USER;  
  }

  public function setId($id) {

    $this->id = $id;
    return $this;
  }

  public function getId() {
    return $this->id;
  } 

  public function setLabel($label) {

    $this->label = $label;
    return $this;
  }

  public function getLabel() {
    return $this->label;
  } 

  public function setType($type) {

    $this->type = $type;
    return $this;
  }

  public function getType() {
    return $this->type;
  }

  public function setVersion($version) {
    $this->version = $version;
  }

  public function getVersion() {
    return $this->version;
  }

}
