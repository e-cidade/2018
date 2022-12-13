<?php
namespace ECidade\V3\Modification;

use \ECidade\V3\Extension\Logger as ExtensionLogger;
use \ECidade\V3\Extension\Registry;

class Logger extends ExtensionLogger {

  protected $id;

  public function __construct($id) {

    $this->id = $id;
    $this->setFile(ECIDADE_MODIFICATION_LOG_PATH . $id);
    if (Registry::has('app.config')) {
      $this->setVerbosity(
        Registry::get('app.config')->get('app.log.verbosity', Logger::QUIET)
      );
    }
  }

}
