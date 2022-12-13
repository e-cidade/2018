<?php
/*
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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF;

/**
 * Class ProcessamentoRelatorioLegal
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF
 */
class ProcessamentoRelatorioLegal extends \RelatoriosLegaisBase {

  protected $iAno;

  /**
   * Período contábil
   * @var \Periodo
   */
  protected $oPeriodo;

  /**
   * @var \RelatoriosLegaisBase
   */
  protected $oRelatorio;

  /**
   * @var \Instituicao
   */
  protected $oPrefeitura;

  /**
   * Linhas do relatório com todas informacoes
   * @var array
   */
  protected $aLinhas = array();

  /**
   * Linhas já processadas com as colunas
   * @var Linha[]
   */
  protected $aLinhasProcessadas = array();

  /**
   * Instituições informadas para calculo
   * @var \Instituicao[]
   */
  protected $aInstituicoes;

  /**
   * @param integer        $iAno
   * @param \Periodo       $oPeriodo
   * @param integer        $iCodigoRelatorio
   * @param \Instituicao[] $aInstituicoes
   */
  function __construct($iAno, \Periodo $oPeriodo, $iCodigoRelatorio, $aInstituicoes) {

    $this->iAno       = $iAno;
    $this->oPeriodo   = $oPeriodo;
    parent::__construct($iAno, $iCodigoRelatorio, $oPeriodo->getCodigo() );

    $this->oPrefeitura = \InstituicaoRepository::getInstituicaoPrefeitura();

    $aCodigos = array_map(function($oInstiuicao) {
      return $oInstiuicao->getCodigo();
    }, $aInstituicoes);

    $this->setInstituicoes( implode(', ', $aCodigos));
    $this->aInstituicoes = $aInstituicoes;
  }

  /**
   * Retorna a instituição prefeitura
   * @return \Instituicao
   */
  public function getPrefeitura() {
    return $this->oPrefeitura;
  }

  /**
   * Retorna a instancia do período informado
   * @return \Periodo
   */
  public function getPeriodo() {
    return $this->oPeriodo;
  }

  /**
   * Retorna o ano informado
   * @return integer
   */
  public function getAno() {
    return $this->iAno;
  }

  /**
   * Retorna a instituição selecionada
   * @return array
   */
  public function getInstituicoesSelecionadas() {
    return $this->aInstituicoes;
  }

}
