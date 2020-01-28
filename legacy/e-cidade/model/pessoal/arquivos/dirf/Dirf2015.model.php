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

/**
 * Model para processamento da DIRF com o layout para 2016
 *
 * @package Pessoal
 * @subpackage Dirf
 */
class Dirf2015 extends Dirf2012 {


  /**
   * Construtor da classe
   *
   * @param integer $iAno
   * @param string $sCnpj
   * @return null
   */
  public function __construct($iAno,  $sCnpj) {

    parent::__construct($iAno, $sCnpj, $sCodigoArquivo = 279);
    $this->aGruposRRA["RIP65"] = 24;
  }

  public function processarRRA(Servidor $oServidor, $lPortadorMolestia, $oPessoa) {


    $oCompetenciaAtual   = DBPessoal::getCompetenciaFolha();
    $oParametros         = ParametrosPessoalRepository::getParametros($oCompetenciaAtual);

    if (!isset($oPessoa->aValorGrupo[24])) {
      $oPessoa->aValorGrupo[24] = 0;
    }

    $oBaseParcelaIsenta = $oParametros->getBaseRraParcelaIsenta();
    if (!empty($oBaseParcelaIsenta)) {
      $oPessoa->aValorGrupo[24] += $this->getValorParcelaIsenta($oServidor, $oBaseParcelaIsenta);
    }

    return parent::processarRRA($oServidor, $lPortadorMolestia, $oPessoa);
  }

  public function getValorParcelaIsenta(Servidor $oServidor, Base $oBaseParcelaIsenta) {
    return $this->getValorBaseServidor($oServidor, $oBaseParcelaIsenta);
  }

  public function getInformacoesPrevidenciaPrivada($iCgm) {

    $aPrevidenciaComplementar = false;

    $oDaoPrevidenciaComplementar  = new cl_rhdirfgeracaopessoalvalorprevidencia();
    $sSqlPrevidenciaComplementar  = $oDaoPrevidenciaComplementar->sql_query (null,$campos = "cgm.z01_nome , cgm.z01_cgccpf, rh204_numcgm, rh98_mes, rh98_valor", 'rh204_numcgm, rh98_mes', "rh95_ano = {$this->iAno} and rh96_numcgm = {$iCgm}");
    $rsSqlPrevidenciaComplementar = db_query($sSqlPrevidenciaComplementar);

    if (!$rsSqlPrevidenciaComplementar) {
      throw new DBException("Ocorreu um erro ao verificar a previdencia complementar");
    }

    if (pg_num_rows($rsSqlPrevidenciaComplementar) == 0) {
      return $aPrevidenciaComplementar;
    }

    db_utils::makeCollectionFromRecord($rsSqlPrevidenciaComplementar, function($oDados) use (&$aPrevidenciaComplementar) {
      $aPrevidenciaComplementar[$oDados->rh204_numcgm][$oDados->rh98_mes] = abs($oDados->rh98_valor);
    });
    return $aPrevidenciaComplementar;
  }

  public function getDadosPensionista($oPessoa, $iTipoDirf) {

    $oDaoDirfPensionista = new cl_rhdirfgeracaopessoalpensionista();
    $sCampos             =  "rh202_sequencial, rh96_numcgm, rh202_numcgm, rh202_rhdirfgeracaopessoal, ";
    $sCampos            .= " r52_relacaodependencia as relacao_dependencia, z01_cgccpf as cpf, z01_nome as nome,";
    $sCampos            .= "z01_nasc as data_nascimento ";
    $sWhere              =  "rh95_ano = {$this->iAno} and rh95_fontepagadora='{$this->sCnpj}' ";
    $sWhere             .= " and rh96_numcgm = $oPessoa->cgm ";
    $sSqlPensionista     = $oDaoDirfPensionista->sql_query_dados_pensionista($sCampos, $sWhere);
    $rsPensionistas      = db_query($sSqlPensionista);
    if (!$rsPensionistas) {
      throw new \DBException('Erro ao pesquisar dados dos pensionistas para a geração dos pensionistas');
    }
    $oInstancia    = $this;
    $aPensionistas = array(); 
    db_utils::makeCollectionFromRecord($rsPensionistas, function ($oDados) use ($oInstancia, $iTipoDirf, &$aPensionistas) {

      $oPensionista          = $oDados;
      $oPensionista->valores = $oInstancia->calcularValorPensao($oDados->rh202_sequencial, $iTipoDirf);      
      if (count($oDados->valores) == 0) {
        return db_utils::ITERATION_CONTINUE;
      }
      $aPensionistas[$oDados->rh202_numcgm] = $oPensionista;
      return $oPensionista;
    });    
    return $aPensionistas;
  }

  /**
   * @param $iPensionista
   * @param $iTipoDirf
   * @return \stdClass[]
   * @throws \DBException
   */
  public function calcularValorPensao($iPensionista, $iTipoDirf) {

    $oDaoDirfPensionistaValor = new cl_rhdirfgeracaopessoalpensionistavalor();

    $sCampos = "rh98_rhdirftipovalor, rh98_valor as valor, rh98_mes as mes";
    $sWhere  = "rh203_rhdirfgeracaopessoalpensionista = {$iPensionista} and rh98_tipoirrf = '$iTipoDirf'";

    $sSqlPagamentos  = $oDaoDirfPensionistaValor->sql_query_valores(null, $sCampos, 'rh98_mes', $sWhere);    
    $rsPagamentos    = db_query($sSqlPagamentos);

    if (!$rsPagamentos) {
      throw new \DBException('Erro ao pesquisar dados dos pagamentos de pensionistas para a geração dos pensionistas');
    }
    $aValor = array();
    db_utils::makeCollectionFromRecord($rsPagamentos, function ($oDados) use(&$aValor) {
      $aValor[$oDados->mes] = $oDados->valor;
    });
    return $aValor;
  }

}