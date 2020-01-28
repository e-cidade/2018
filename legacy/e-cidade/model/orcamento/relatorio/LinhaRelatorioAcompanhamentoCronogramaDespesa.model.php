<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

/**
 * Value Object para o relatório acompanhamento do cronograma da despesa
 * Class LinhaRelatorioAcompanhamentoCronogramaDespesa
 */
class LinhaRelatorioAcompanhamentoCronogramaDespesa {

  /**
   * @type int
   */
  private $iCodigo;

  /**
   * @type int
   */
  private $iCodigoOrgao;

  /**
   * @type int
   */
  private $iCodigoUnidade;

  /**
   * @type int
   */
  private $iCodigoRecurso;

  /**
   * @type int
   */
  private $iCodigoAnexo;

  /**
   * @type string
   */
  private $sDescricao;

  /**
   * @type string
   */
  private $sDescricaoRecurso;


  /**
   * @type string
   */
  private $sDescricaoAnexo;

  /**
   * @type array
   */
  private $aValoresDespesa;


  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @param int $iCodigoOrgao
   */
  public function setCodigoOrgao($iCodigoOrgao) {
    $this->iCodigoOrgao = $iCodigoOrgao;
  }

  /**
   * @param int $iCodigoUnidade
   */
  public function setCodigoUnidade($iCodigoUnidade) {
    $this->iCodigoUnidade = $iCodigoUnidade;
  }

  /**
   * @param int $iCodigoRecurso
   */
  public function setCodigoRecurso($iCodigoRecurso) {
    $this->iCodigoRecurso = $iCodigoRecurso;
  }

  /**
   * @param int $iCodigoAnexo
   */
  public function setCodigoAnexo($iCodigoAnexo) {
    $this->iCodigoAnexo = $iCodigoAnexo;
  }

  /**
   * @return CronogramaInformacaoDespesa[]
   */
  public function getValoresDespesa() {
    return $this->aValoresDespesa;
  }

  /**
   * @param CronogramaInformacaoDespesa $oInformacaoDespesa
   */
  public function adicionarValores(CronogramaInformacaoDespesa $oInformacaoDespesa, $iMes) {
    $this->aValoresDespesa[$iMes] = $oInformacaoDespesa;
  }

  /**
   * @param $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * @param $sDescricao
   */
  public function setDescricaoRecurso($sDescricao) {
    $this->sDescricaoRecurso = $sDescricao;
  }

  /**
   * @param $sDescricao
   */
  public function setDescricaoAnexo($sDescricao) {
    $this->sDescricaoAnexo = $sDescricao;
  }

  /**
   * Retorna a descrição da linha do relatório de acordo com os valores setados.
   * @return string
   */
  public function getDescricaoLinha() {

    $sDescricao = "";

    if (isset($this->iCodigoOrgao)) {
      $sDescricao .= "{$this->iCodigoOrgao}.";
    }

    if (isset($this->iCodigo)) {
      $sDescricao .= "{$this->iCodigo} - {$this->sDescricao}";
    } else {
      $sDescricao .= "{$this->iCodigoUnidade}.{$this->iCodigoRecurso}.{$this->iCodigoAnexo} - ";
      $sDescricao .= "{$this->sDescricaoRecurso} - {$this->sDescricaoAnexo}";
    }

    return $sDescricao;
  }
}