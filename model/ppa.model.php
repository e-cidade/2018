<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
 *
 * @author Iuri
 * @version $Revisison$
 */
class ppa {

  /**
   * Código da Lei da PPA
   * @var integer
   */
  private $iCodigoLei    = null;

  /**
   * Código da perspectiva relacionada a lei
   * @var integer
   */
  private $iCodigoVersao = null;

  /**
   * Tipo de PPA
   * 1 - Receita
   * 2 - Despesa
   * @var integer
   */
  private $iTipo         = null;

  /**
   * Armazena um objeto do tipo ppaReceita ou ppaDespesa de acordo com o $iTipo
   * @var ppaDespesa
   * @var ppaReceita
   */
  public  $oObjeto       = null;

  /**
   * Aplica o CM (cenário macro-econômico) em cima da média total das receitas/despesas baseada nos últimos 4 anos
   * @var integer
   */
  const TIPO_CALCULO_MEDIA           = 1;

  /**
   * Aplica o CM (cenário macro-econômico) em cima da arrecadação/despesas das receitas/despesas baseada
   * nos últimos 4 anos
   * @var integer
   */
  const TIPO_CALCULO_EXERCICIO_ATUAL = 2;

  /**
   * Define a quantidade em anos para que seja feita uma perspectiva
   * @var integer
   */
  const ANOS_PREVISAO_CALCULO = 4;

  /**
   * Constrói um objeto do tipo PPA. Este pode ser do tipo Receita ou Despesa
   *
   * @param integer $iCodigoLei
   * @param integer $iTipo
   * @param string $iVersao
   */
  public function __construct($iCodigoLei, $iTipo, $iVersao=null) {

    $this->iCodigoLei = $iCodigoLei;
    $this->iTipo      = $iTipo;

    if ($iVersao != null) {

      if ($iTipo == 1) {

         require_once("model/ppaReceita.model.php");
         $this->oObjeto =  new ppaReceita($iVersao);

      } else if ($iTipo == 2) {

         require_once("model/ppadespesa.model.php");
         $this->oObjeto =  new ppaDespesa($iVersao);
      }
    }
  }

  /**
   * Processa a base de cálculo
   * @see ppa::ANOS_PREVISAO_CALCULO
   * @param integer $iAno
   */
  function processaBaseCalculo($iAno) {
    $this->oObjeto->processaBaseCalculo($iAno);
  }

  /**
   * Processa as estimativas para os anos seguintes
   * @param integer $iAnoInicial
   * @param integer $iAnoFinal
   * @param string $iCodigo
   */
  function processarEstimativasGlobais($iAnoInicial, $iAnoFinal, $iCodigo = null) {
    $this->oObjeto->processarEstimativasGlobais($iAnoInicial, $iAnoFinal, $iCodigo = null);
  }

  /**
   * Busca o quadro de estimativas processado nos demais métodos
   * @param string $sEstrutural
   * @param string $sFiltro
   * @return multitype:
   */
  function getQuadroEstimativas($sEstrutural = null, $sFiltro = null) {
    return $this->oObjeto->getQuadroEstimativas($sEstrutural, $sFiltro);
  }

  function saveEstimativa($iCodCon, $iAno, $nValor, $iConcarpeculiar) {
    return $this->oObjeto->saveEstimativas($iCodCon, $iAno, $nValor,$this->iTipo, $iConcarpeculiar);
  }

  /**
   * Processa as estimativas para a conta e ano informado
   * @param integer $iCodCon
   * @param integer $iAno
   * @param integer $iConcarpeculiar
   * @return Ambigous <number, unknown>
   */
  function processarEstimativas($iCodCon, $iAno, $iConcarpeculiar) {
    return $this->oObjeto->processarEstimativas($iCodCon, $iAno, $iConcarpeculiar);
  }

  /**
   * Verifica o nivel do estrutural (deve ser separado por ponto ".")
   *
   * @param  string $sEstrutural Estrutural (seperado por pontos)
   * @return integer
   */
  public function estruturalNivel($sEstrutural) {

    $iNiveis = array();
    $iAux    = 1;
    $iNiveis = explode(".", $sEstrutural);
    $iLaco   = count($iNiveis);

    for ($i = 1; $i < $iLaco; $i++) {

      if ($iNiveis[$i] != 0 ) {
        $iAux = $i+1;
      }
    }
    return $iAux;
  }

  function criaContaMae($string) {

  	$string = db_formatar($string,"sistema");
  	$iNivel = ppa::estruturalNivel($string);
  	$stringnova = "";
  	$aNiveis = explode(".", $string);
    for ($i = 0;  $i < $iNivel; $i++) {

      $stringnova .=  $aNiveis[$i];
    }
    return $stringnova;
  }

  function getDesdobramentos($iEstrutural, $iAno) {
    return $this->oObjeto->getDesdobramentos($iEstrutural, $iAno);
  }

  function adicionarEstimativa($oObjetoEstimativa) {
    return $this->oObjeto->adicionarEstimativa($oObjetoEstimativa);
  }

  function setInstituicoes($sInstituicoes) {
    return $this->oObjeto->setInstituicoes($sInstituicoes);
  }


  /**
   * Busca as perspectivas disponíveis para uma determinada lei do PPA
   * @param integer $iTipoConsulta
   * @return multitype:stdClass
   */
  public function getVersoes($iTipoConsulta, $lAtivas = null) {

    require_once ('classes/db_ppaversao_classe.php');
    $oDaoPPaVersao = new cl_ppaversao;
    $sWhere        = "";
    switch ($iTipoConsulta) {

      case 0:

        $sWhere .="";
        break;

      case 1:

        $sWhere .= " and o119_versaofinal is true";
        break;

      case 2:

        $sWhere .= " and (o123_sequencial is null or o123_tipointegracao <> 1) ";
         break;

      case 3:

        $sWhere .= " and o123_sequencial is not null";
        break;
    }

    if(!empty($lAtivas)){
      $sWhere .= " and o119_ativo = true ";
    }

    $sSqlVersao    = $oDaoPPaVersao->sql_query_integracao(null,
                                                          "distinct ppaversao.*",
                                                          "o119_versao",
                                                          "o119_ppalei = {$this->iCodigoLei} {$sWhere} ");
    $rsVersao      = $oDaoPPaVersao->sql_record($sSqlVersao);
    $aVersoes     = array();
    for ($i = 0; $i < $oDaoPPaVersao->numrows; $i++) {

      $oVersao = db_utils::fieldsMemory($rsVersao, $i);
      $iCodigoVersao = $oVersao->o119_sequencial;
      $clppaestimativareceita = db_utils::getDao("ppaestimativareceita");
      $oVersao->receitaprocessada = false;
      $oVersao->despesaprocessada = false;
      $sSqlEstimativas = $clppaestimativareceita->sql_query_analitica(null,"*",
                                                                      "o05_ppaversao limit 1",
                                                                      "    o05_ppaversao = {$iCodigoVersao}
                                                                       and c61_instit = ".db_getsession("DB_instit"));
      $rsEstmativas    = $clppaestimativareceita->sql_record($sSqlEstimativas);
      if ($clppaestimativareceita->numrows > 0) {
        $oVersao->receitaprocessada = true;
      }
      $clppaestimativadespesa = db_utils::getDao("ppaestimativadespesa");

      $sSqlEstimativas = $clppaestimativadespesa->sql_query_conplano(null,"*",
                                                  "o05_ppaversao limit 1",
                                                  "o05_ppaversao = {$iCodigoVersao} and o08_instit = ".db_getsession("DB_instit"));
      $rsEstmativas    = $clppaestimativadespesa->sql_record($sSqlEstimativas);
      if ($clppaestimativadespesa->numrows > 0) {
        $oVersao->despesaprocessada = true;
      }
      $aVersoes[] = $oVersao;
    }
    return $aVersoes;
  }

  /**
   * Seta o código da perspectiva para a lei do PPA
   * @param integer $iVersao
   */
  public function setVersao($iVersao) {
    $this->iCodigoVersao = $iVersao;
  }

  /**
   * Busca o tipo de cálculo que deve ser executado para a receita
   * @param integer $iCodCon - Código do Plano de Contas
   * @param integer $iAnoReferencia - Ano de Referencia
   * @see ppa::TIPO_CALCULO_EXERCICIO_ATUAL, ppa::TIPO_CALCULO_MEDIA
   * @return integer
   */
  public static function getTipoCalculo($iCodCon, $iAnoReferencia) {

    $iInstituicaoSessao   = db_getsession("DB_instit");
    $iTipoCalculo         = ppa::TIPO_CALCULO_MEDIA;
    $oDaoCenarioConplano  = db_utils::getDao("orccenarioeconomicoconplano");
    $sSqlBuscaTipoCalculo = $oDaoCenarioConplano->sql_query(null,
                                                            "distinct o04_tipocalculo",
                                                            null,
                                                            "    o03_anoreferencia = {$iAnoReferencia}
                                                             and o03_anoreferencia = {$iAnoReferencia}
                                                             and o04_anousu        = {$iAnoReferencia}
                                                             and c60_anousu        = {$iAnoReferencia}
                                                             and o04_conplano      = {$iCodCon}
                                                             and o03_instit        = {$iInstituicaoSessao}");
    $rsBuscaTipoCalculo = $oDaoCenarioConplano->sql_record(analiseQueryPlanoOrcamento($sSqlBuscaTipoCalculo));
    if ($oDaoCenarioConplano->numrows == 1) {

      $iTipoCalculo = db_utils::fieldsMemory($rsBuscaTipoCalculo, 0)->o04_tipocalculo;
      if ($iTipoCalculo == 2) {
        $iTipoCalculo = ppa::TIPO_CALCULO_EXERCICIO_ATUAL;
      }
    }
    return $iTipoCalculo;
  }

  /**
   * Busca o percentual que deve ser aplicado em cima da perspectiva para a receita e/ou despesa
   * @param integer $iCodCon
   * @param integer $iAnoReferencia
   * @return number
   */
  public static function getAcrescimosEstimativa($iCodCon, $iAnoReferencia) {

    $oDaoCenarioConplano = db_utils::getDao("orccenarioeconomicoconplano");
    $iInstituicaoSessao  = db_getsession("DB_instit");
    $nValorparametro     = 0;
    $sSqlParametros      = $oDaoCenarioConplano->sql_query(null,
                                                           "sum(o03_valorparam) as valorparametro",
                                                           null,
                                                           "    o03_anoreferencia = {$iAnoReferencia}
                                                            and o04_conplano   = {$iCodCon}
                                                            and o03_instit     = {$iInstituicaoSessao}");
    $rsParametros     = $oDaoCenarioConplano->sql_record(analiseQueryPlanoOrcamento($sSqlParametros));
    if ($oDaoCenarioConplano->numrows > 0) {

      $nValorParametro = db_utils::fieldsMemory($rsParametros, 0)->valorparametro;
      $nValorparametro = 1+($nValorParametro/100);
    }
    return $nValorparametro;
  }
}
?>