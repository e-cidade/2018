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

use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\BaseAbstract as Regra;

abstract class ArquivoLicitaCon {

  /**
   * Tipos de Linhas.
   */
  const TIPO_LINHA_CABECALHO = 1;
  const TIPO_LINHA_REGISTRO  = 3;

  /**
   * @var CabecalhoLicitaCon
   */
  protected $oCabecalho;

  /**
   * @var int
   */
  protected $iCodigoLayout;

  /**
   * @var string
   */
  protected $sNomeArquivo;

  /**
   * @var array
   */
  protected $aAnexos = array();

  /**
   * @var array
   */
  protected $aRemoveQuebraLinhas = array();

	/**
	 * Regra do licitacon para o arquivo.
	 * @var \ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Contrato
	 *    | \ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Alteracao
	 *    | \ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\DotacaoCon
	 *    | \ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\DotacaoLic
	 *    | \ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Geral
	 *    | \ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Item
	 *    | \ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Licitacao
	 *    | \ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Licitante
	 *    | \ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Lote
	 *    | \ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Proposta
	 */
	protected $oRegra;

  /**
   * ArquivoLicitaCon constructor.
   *
   * @param CabecalhoLicitaCon $oCabecalho
	 * @param Regra
   */
  public function __construct(CabecalhoLicitaCon $oCabecalho, Regra $oRegraEmissaoLicitacon = null) {

    $this->oCabecalho = $oCabecalho;
		$this->oRegra     = $oRegraEmissaoLicitacon;
  }

  /**
   * @param int $iCodigoLayout
   */
  public function setCodigoLayout($iCodigoLayout) {
    $this->iCodigoLayout = $iCodigoLayout;
  }

  /**
   * @param string $sNomeArquivo
   */
  public function setNomeArquivo($sNomeArquivo) {
    $this->sNomeArquivo = $sNomeArquivo;
  }

  /**
   * @return array
   */
  public function getAnexos() {
    return $this->aAnexos;
  }

  /**
   * @return stdClass[]
   */
  public abstract function getDados();

  /**
   * Gera o arquivo do LicitaCon.
   * @return File
   * @throws BusinessException
   * @throws ParameterException
   */
  public function gerar() {

    if (empty($this->sNomeArquivo)) {
      throw new ParameterException("Nome do Arquivo do LicitaCon não informado.");
    }

    if (empty($this->iCodigoLayout)) {
      throw new ParameterException("Código do Arquivo {$this->sNomeArquivo} do LicitaCon não informado.");
    }

    $sNomeArquivo = "tmp/" . strtoupper("{$this->sNomeArquivo}.txt");
    if (file_exists($sNomeArquivo)) {
      unlink($sNomeArquivo);
    }

    $oLayoutTXT = new db_layouttxt($this->iCodigoLayout, $sNomeArquivo);
    $oLayoutTXT->desabilitarQuebraAutomatica();

    $aDados = $this->getDados();
    foreach ($aDados as $oDado) {
      $this->tratarDados($oDado);
    }
    $this->oCabecalho->setTotalRegistros(count($aDados));
    $oLayoutTXT->setByLineOfDBUtils($this->oCabecalho->getDadosLayout(), self::TIPO_LINHA_CABECALHO);

    $oLayoutTXT->habilitarQuebraAutomatica();
    $oLayoutTXT->setPosicionamentoQuebraAutomatica(db_layouttxt::QUEBRA_AUTOMATICA_ANTES);

    foreach ($aDados as $oStdLinha) {

      if (!$oLayoutTXT->setByLineOfDBUtils($oStdLinha, self::TIPO_LINHA_REGISTRO)) {

        $oLayoutTXT->fechaArquivo();
        unlink($sNomeArquivo);
        throw new BusinessException("Houve um erro ao gravar a linha do arquivo {$this->sNomeArquivo} do LicitaCon.");
      }
    }

    $oLayoutTXT->fechaArquivo();

    $sConteudoArquivo = utf8_encode(file_get_contents($sNomeArquivo));
    file_put_contents($sNomeArquivo, $sConteudoArquivo);
    chmod ($sNomeArquivo , 777 );
    return new File($sNomeArquivo);
  }

  /**
   * Remove quebras de linhas de um conjunto de strings.
   * @param string[] $aStrings
   *
   * @return string[]
   */
  protected function removeQuebrasDeLinha($aStrings) {

    $aQuebrasDeLinha = array("\n", "\r\n", "\r");
    return str_replace($aQuebrasDeLinha, "", $aStrings);
  }

  /**
   * Trata os dados configurados nos arrays de cada classe, removendo quebras de linhas, por exemplo.
   * @param $oDado
   */
  protected function tratarDados($oDado) {

    if (!empty($this->aRemoveQuebraLinhas)) {

      $aCamposLimpar = array();
      foreach ($this->aRemoveQuebraLinhas as $sNomeCampo) {
        $aCamposLimpar[$sNomeCampo] = $oDado->{$sNomeCampo};
      }

      $aCamposLimpos = $this->removeQuebrasDeLinha($aCamposLimpar);

      foreach ($aCamposLimpos as $sNomeCampo => $sValorCampoLimpo) {
        $oDado->{$sNomeCampo} = $sValorCampoLimpo;
      }
    }
  }
}