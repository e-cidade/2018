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

db_app::import('pessoal.CalculoFolha');

/**
 * Definicoes sobre o Calculo de Salario de um servidor em uma competencia
 * 
 * @uses    Ponto
 * @package Pessoal 
 * @author  Rafael Serpa Nery <rafael.nery@dbseller.com.br> 
 * @author  Jeferson Belmiro  <jeferson.belmiro@dbseller.com.br>
 */
class CalculoFolhaRescisao extends CalculoFolha {  

  const TABELA       = "gerfres";
  const SIGLA_TABELA = "r20";

  private static $aRubricas = array();

  public function __construct ( Servidor $oServidor ) {

    parent::__construct($oServidor);

    $this->sTabela = self::TABELA;      
    $this->sSigla  = self::SIGLA_TABELA;
  } 

  /**
   * Funcao para gerar ponto para o mes selecionado
   */
  public function calcular() {}

    public function gerar() {}

    /**
     * Ajusta rubricas de salário e de férias, somando as bases para o ajuste de previdência.
     * Sendo executado antes de entrar no cálculo.
     * @return 
     */
    public static function ajustarBasesPrevidenciaFerias () {

     /**
      * Desfazendo ajuste
      */
    return true;




      $oDaoRhRubricas               = new cl_rhrubricas;
      $oRubricaBaseSalario          = RubricaRepository::getInstanciaByCodigo('R985',db_getsession('DB_instit'));
      $oRubricaBaseFerias           = RubricaRepository::getInstanciaByCodigo('R987',db_getsession('DB_instit'));

      //Array para guardar as informações atuais das fórmulas.
    self::$aRubricas["R985"]["1"] = $oRubricaBaseSalario->getFormulaCalculo();
    self::$aRubricas["R985"]["2"] = $oRubricaBaseSalario->getFormulaCalculo2();
    self::$aRubricas["R985"]["3"] = $oRubricaBaseSalario->getFormulaCalculo3();

    self::$aRubricas["R987"]["1"] = $oRubricaBaseFerias->getFormulaCalculo();
    self::$aRubricas["R987"]["2"] = $oRubricaBaseFerias->getFormulaCalculo2();
    self::$aRubricas["R987"]["3"] = $oRubricaBaseFerias->getFormulaCalculo3();

      $aRubricas                    = self::$aRubricas;

      //Alteramos o valor, para somar as bases das rubricas de salário com as de férias
    $oDaoRhRubricas->rh27_form   = "( {$aRubricas["R985"]["1"]} ) + ( {$aRubricas["R987"]["1"]} )"; 
    $oDaoRhRubricas->rh27_form2  = "( {$aRubricas["R985"]["2"]} ) + ( {$aRubricas["R987"]["2"]} )"; 
    $oDaoRhRubricas->rh27_form3  = "( {$aRubricas["R985"]["3"]} ) + ( {$aRubricas["R987"]["3"]} )"; 
      $oDaoRhRubricas->rh27_rubric = "R985"; 
      $oDaoRhRubricas->rh27_instit = db_getsession("DB_instit");

      $oDaoRhRubricas->alterar('R985',db_getsession("DB_instit"));

      if($oDaoRhRubricas->erro_status == '0'){
        throw new DBException($oDaoRhRubricas->erro_msg);
      }

      //Hack para não cair na validação de vazio do dao.
      $GLOBALS["HTTP_POST_VARS"]["rh27_form"]  = "";
    $GLOBALS["HTTP_POST_VARS"]["rh27_form1"] = "";
      $GLOBALS["HTTP_POST_VARS"]["rh27_form2"] = "";

      //Definimos vazio para as formulas da rubrica de férias.
      $oDaoRhRubricas->rh27_form   = ""; 
      $oDaoRhRubricas->rh27_form2  = ""; 
      $oDaoRhRubricas->rh27_form3  = "";   
      $oDaoRhRubricas->rh27_rubric = "R987"; 
      $oDaoRhRubricas->rh27_instit = db_getsession("DB_instit"); 

      $oDaoRhRubricas->alterar('R987',db_getsession("DB_instit"));

      if($oDaoRhRubricas->erro_status == '0'){
        throw new DBException($oDaoRhRubricas->erro_msg);
      }

    }

  /**
   * Desfaz as alterações feitas na função ajustarBasesPrevidenciaFerias(), voltando as bases originais da rubrica.
   * É executado após feito o cálculo.
   * @return 
   */
  public static function desfazerAjustePrevidenciaFerias () {
     /**
      * Desfazendo ajuste
      */
    return true;
    $aRubricas                               = self::$aRubricas;
    $oDaoRhRubricas                          = new cl_rhrubricas;

    $oDaoRhRubricas->rh27_form               = "{$aRubricas["R985"]["1"]}"; 
    $oDaoRhRubricas->rh27_form2              = "{$aRubricas["R985"]["2"]}"; 
    $oDaoRhRubricas->rh27_form3              = "{$aRubricas["R985"]["3"]}"; 
    $oDaoRhRubricas->rh27_rubric             = "R985"; 
    $oDaoRhRubricas->rh27_instit             = db_getsession("DB_instit");

    $oDaoRhRubricas->alterar('R985',db_getsession("DB_instit"));

    if($oDaoRhRubricas->erro_status == '0'){
      throw new DBException($oDaoRhRubricas->erro_msg);
    }
    
    $oDaoRhRubricas->rh27_form               = "{$aRubricas["R987"]["1"]}"; 
    $oDaoRhRubricas->rh27_form2              = "{$aRubricas["R987"]["2"]}"; 
    $oDaoRhRubricas->rh27_form3              = "{$aRubricas["R987"]["3"]}";    
    $oDaoRhRubricas->rh27_rubric             = "R987"; 
    $oDaoRhRubricas->rh27_instit             = db_getsession("DB_instit");
    
    $oDaoRhRubricas->alterar('R987',db_getsession("DB_instit"));

    if($oDaoRhRubricas->erro_status == '0'){
      throw new DBException($oDaoRhRubricas->erro_msg);
    }

  }

  public static function ajustarBaseIRRF() {
     /**
      * Desfazendo ajuste
      */
    return true;
    $oDaoBasesR = new cl_basesr;
    $iAnoUsu    = DBPessoal::getAnoFolha();
    $iMesUsu    = DBPessoal::getMesFolha();
    $iInstit    = db_getsession("DB_instit");
    $sWhere     = "r09_base in ('B004','B005') and r09_anousu = {$iAnoUsu} and r09_mesusu = {$iMesUsu} and r09_instit = {$iInstit}";

    $sSql       = $oDaoBasesR->sql_query_file(null, null, null, null, null, "*", null, $sWhere);
    $sSql       = "create temp table w_precalculo_rescisao as ".$sSql;

    $rsDbQuery  = db_query($sSql);

    if (!$rsDbQuery) {
      throw new Exception("Erro ao processar query.".pg_last_error());
    }

    $oDaoBasesR->excluir(null, null, null, null, null, $sWhere);

      $sSql  = "select                                                ";  
      $sSql .= "  *                                                   ";
      $sSql .= "from                                                  ";
      $sSql .= "  w_precalculo_rescisao                               ";
      $sSql .= "where                                                 ";
      $sSql .= "  r09_base in ('B004')                                ";
      $sSql .= "  and r09_instit = {$iInstit}                         ";
      $sSql .= "  and r09_anousu = {$iAnoUsu}                         ";
      $sSql .= "  and r09_mesusu = {$iMesUsu}                         ";
      $sSql .= "  and r09_rubric in (                                 ";
      $sSql .= "                     select                           ";
      $sSql .= "                       r09_rubric                     ";
      $sSql .= "                     from                             ";
      $sSql .= "                       w_precalculo_rescisao          ";
      $sSql .= "                     where                            ";
      $sSql .= "                       r09_base in ('B005')           ";
      $sSql .= "                       and r09_instit = {$iInstit}    ";
      $sSql .= "                       and r09_anousu = {$iAnoUsu}    ";
      $sSql .= "                       and r09_mesusu = {$iMesUsu}    ";
      $sSql .= "                    );                                ";

      $rsDbQuery                     = $oDaoBasesR->sql_record($sSql);

      if ($rsDbQuery) {
       
        $iQuantidadeRubricasDuplicadas = pg_numrows($rsDbQuery);
        $sRubricasDuplicadas           = "";      
      
        if($iQuantidadeRubricasDuplicadas > 0) {
                
          for($iIndice = 0; $iIndice < $iQuantidadeRubricasDuplicadas; $iIndice++) {
            
            if($iIndice == $iQuantidadeRubricasDuplicadas - 1) {
              $sRubricasDuplicadas .= "\n".db_utils::fieldsMemory($rsDbQuery, $iIndice)->r09_rubric.".";
            } else {
              $sRubricasDuplicadas .= "\n".db_utils::fieldsMemory($rsDbQuery, $iIndice)->r09_rubric.",";
            }
  
          }
  
           throw new Exception("Existem rubricas duplicadas no cadastro das bases B004 e B005: ".$sRubricasDuplicadas);
        }
      
      } else {
      $rsDbQuery = db_query("insert into basesr select r09_anousu, r09_mesusu, 'B004', r09_rubric, r09_instit from w_precalculo_rescisao;");

    if (!$rsDbQuery) {
      throw new Exception("Erro ao processar query.".pg_last_error());
    }
      }
  }



  public static function desfazerAjusteBaseIRRF() {
    /**
     * Desfazendo ajuste
     */
    return true;
    $oDaoBasesR = new cl_basesr;
    $iAnoUsu    = DBPessoal::getAnoFolha();
    $iMesUsu    = DBPessoal::getMesFolha();
    $iInstit    = db_getsession("DB_instit");
    $sWhere     = "r09_base in ('B004','B005') and r09_anousu = {$iAnoUsu} and r09_mesusu = {$iMesUsu} and r09_instit = {$iInstit}";
    $oDaoBasesR->excluir($iAnoUsu, $iMesUsu, 'B004', null, $iInstit);

    $rsDbQuery  = db_query("insert into basesr select * from w_precalculo_rescisao;");

    if (!$rsDbQuery) {
      throw new Exception("Erro ao processar query.".pg_last_error());
    }

  }

  /**
   * Verifica se foi efetuado o cálculo de rescisão para os servidores passados como parâmetro, 
   * e deleta os mesmos do historico do cálculo.
   * @param  Array $aMatriculasSelecionadas 
   * @return Boolean
   */
  public static function posCalcular ($aMatriculasSelecionadas) { 

    if ( !DBPessoal::verificarUtilizacaoEstruturaSuplementar() ){
      return;
    }

    $oDaoGerfres            = new cl_gerfres();
    $oDaoRhHistoricoCalculo = new cl_rhhistoricocalculo();

    foreach ($aMatriculasSelecionadas as $aMatricula) {

      $iMatricula    = $aMatricula['r01_regist'];
      $oCompetencia  = DBPessoal::getCompetenciaFolha();
      $aFolha        = FolhaPagamento::getFolhaCompetenciaTipo($oCompetencia, FolhaPagamento::TIPO_FOLHA_SALARIO);
      $oFolha        = $aFolha[0];

      $sSqlGerfRes = $oDaoGerfres->sql_query_file($oFolha->getCompetencia()->getAno(), 
      $oFolha->getCompetencia()->getMes(), 
      $iMatricula, 
      null, 
      null, 
      'r20_regist');
      $rsGerfRes = db_query($sSqlGerfRes);

      if(!$rsGerfRes) {
        throw new DBException("Ocorreu um erro ao consultar a folha de rescisao.");
      }

      if (pg_num_rows($rsGerfRes) > 0) {

        $sWhere = "rh143_folhapagamento = {$oFolha->getSequencial()} and rh143_regist = {$iMatricula}";
        $oDaoRhHistoricoCalculo->excluir(null, $sWhere);

        if ( $oDaoRhHistoricoCalculo->erro_sql == '0') {
          throw new DBException($oDaoRhHistoricoCalculo->erro_msg);
        }
      }
    }

    return true;
  }

  /**
   * Método que calcula o valor da isenção para o servidor atual
   *
   * @access public
   * @return Number
   */
  public function ajustarParcelaIsentaAposentadoPensionista($sRubrica, $nValorIsencao, $nValorAtual) {

    $oServidorAtual    = $this->getServidor();
    $nValorMaximoAtual = $nValorAtual; 

    LogCalculoFolha::write('');
    LogCalculoFolha::write('Ajustando parcela de isencao para o servidor: '.$oServidorAtual->getMatricula());
    LogCalculoFolha::write('Rubrica.....................................: '.$sRubrica);

    if($oServidorAtual->getCalculoFinanceiro(CalculoFolha::CALCULO_COMPLEMENTAR) instanceof CalculoFolha) {

      $aEventosFinanceirosComplementarServidorAtual = $oServidorAtual->getCalculoFinanceiro(CalculoFolha::CALCULO_COMPLEMENTAR)->getEventosFinanceiros(null, $sRubrica);;

      if(!empty($aEventosFinanceirosComplementarServidorAtual) && count($aEventosFinanceirosComplementarServidorAtual) > 0) {

        LogCalculoFolha::write('');
        LogCalculoFolha::write("Verificando eventos financeiros de complementar do servidor atual.");

        $oEventoFinanceiroComplementarServidorAtual = $aEventosFinanceirosComplementarServidorAtual[0];
        $nValorAtual                               -= $oEventoFinanceiroComplementarServidorAtual->getValor();
        LogCalculoFolha::write('Valor da isencao da folha complementar do servidor atual.........: ' .$oEventoFinanceiroComplementarServidorAtual->getValor());
      }
    }

    $mValorVinculado = $this->verificarParcelaIsentaAposentadoPensionistaServidorVinculado($oServidorAtual, $sRubrica);

    if($mValorVinculado !== false) {
      return $this->calcularParcelaIsentaAposentadoPensionista($nValorIsencao, $nValorMaximoAtual, $nValorAtual, $mValorVinculado);
    }

    return $this->calcularParcelaIsentaAposentadoPensionista($nValorIsencao, $nValorMaximoAtual, $nValorAtual);
  }
}
