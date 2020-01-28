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

namespace ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Retorno;

class RetornoCollection implements \Countable, \Iterator, \ArrayAccess
{
  private $rsResource;

  private $iNumRows;

  private $iPosition;

  public function __construct($rsResource) {

    $this->rewind();
    $this->iNumRows = 0;

    if ($rsResource) {
      $this->iNumRows = pg_num_rows($rsResource);
    }

    $this->rsResource = $rsResource;
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
   * @return StdClass
   */
  private function getRow($iRow) {

    $oRow = \db_utils::fieldsMemory($this->rsResource, $iRow);

    $oRegistro = new \StdClass;

    $oRegistro->sCgm               = $oRow->cgm;
    $oRegistro->sCodigoArrecadacao = $oRow->codigo_arrecadacao;
    $oRegistro->aTipo              = explode('#', $oRow->tipo);
    $oRegistro->sConvenio          = $oRow->convenio;
    $oRegistro->oDataEmissao       = \DBDate::create($oRow->data_emissao);
    $oRegistro->aOcorrencia        = explode('#', $oRow->ocorrencia);

    return $oRegistro;
  }
}
