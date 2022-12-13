<?php
namespace ECidade\V3\Modification\Data;

use \ECidade\V3\Extension\Storage;

/**
 * @package modification
 */
class FileTypeModification extends Storage {

  public function __construct() {
    parent::__construct(ECIDADE_MODIFICATION_DATA_PATH . "file/file-type-modification.data");
  }

}
