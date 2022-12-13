<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Tributario\Arrecadacao\EmissaoGeral;

use ECidade\Tributario\Arrecadacao\EmissaoGeral\EmissaoGeral;
use ECidade\Tributario\Arrecadacao\EmissaoGeral\Registro\Factory as RegistroFactory;
use ECidade\Tributario\Arrecadacao\EmissaoGeral\Registro\IPTU as RegistroIPTU;
use ECidade\Tributario\Arrecadacao\EmissaoGeral\Registro\Padrao as RegistroPadrao;

class RegistroCollection implements \Countable, \Iterator, \ArrayAccess {

  private $resource;

  private $iNumRows = 0;

  private $iPosition = 0;

  /**
   * @var EmissaoGeral
   */
  private $oEmissaoGeral;

  public function __construct($resource, EmissaoGeral $oEmissao) {

    if (empty($resource)) {
      throw new \Exception("Invalid Resource");
    }

    $this->resource = $resource;
    $this->iNumRows = pg_num_rows($resource);
    $this->oEmissaoGeral = $oEmissao;
  }

  public function count() {
    return $this->iNumRows;
  }

  public function current() {
    return $this->offsetGet($this->iPosition);
  }

  public function key() {
    return $this->iPosition;
  }

  public function next() {
    ++$this->iPosition;
  }

  public function rewind() {
    $this->iPosition = 0;
  }

  public function valid() {
    return $this->iPosition < $this->iNumRows;
  }

  public function offsetExists($iOffset) {

    $iOffset = (int) $iOffset;
    return (is_int($iOffset) && $iOffset >= 0 && $iOffset < $this->iNumRows);
  }

  public function offsetGet($iOffset) {

    if (!$this->offsetExists($iOffset)) {
      throw new \OutOfRangeException("Undefined Index.");
    }

    return $this->getRow($iOffset);
  }

  public function offsetSet($iOffset, $mValue) { }

  public function offsetUnset($iOffset) { }

  /**
   * @param integer $iRow
   * @return RegistroPadrao
   */
  private function getRow($iRow) {

    $oObject = \db_utils::fieldsMemory($this->resource, $iRow);

    $oRegistro = RegistroFactory::getRegistro($this->oEmissaoGeral);
    $oRegistro->setNumpre($oObject->tr02_numpre);
    $oRegistro->setParcela($oObject->tr02_parcela);
    $oRegistro->setCgm($oObject->tr02_numcgm);
    $oRegistro->setSituacao($oObject->tr02_situacao);

    if ($oRegistro instanceof RegistroIPTU) {
      $oRegistro->setMatricula($oObject->tr03_matric);
    }

    return $oRegistro;
  }
}
