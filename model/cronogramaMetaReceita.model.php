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


final class cronogramaMetaReceita {
  
  protected $iReceita;

  protected $iPerspectiva;
  
  protected $aAnos = array();
  
  protected $oReceita ;
  
  protected $iCodigoReceita;
  
  public function __construct($oReceita) {
    
    $this->iReceita       = $oReceita->o70_codrec;
    $this->iPerspectiva   = $oReceita->iPerspectiva;
    $this->iCodigoReceita = $oReceita->iSequencial;
    $this->oReceita       = $oReceita;
    
  }
  
  public function calcularMetas() {
    
    /*
     * Calculos o valor arrecadado no mes, em todos os anos selecionados total de cada ano
     * com base no valor previsto da receita para 2010(orcreceita.o70_valor)
     */
    $nValorPrevisto = $this->oReceita->o70_valor;
    
    /**
     * Verificamos se existe algum valor previsto anterior para essa receita
     * caso nao exista nenhum valor, devemos dividir o valor previsto no ppa igualmente 
     * entre os meses, não seguindo a curva definida pelo usuario
     */
    $sSqlTotalPrevisto   = "select coalesce(sum(o125_valor),0) as valortotalprevisto"; 
    $sSqlTotalPrevisto  .= "  from cronogramabasecalculoreceita";
    $sSqlTotalPrevisto  .= " where o125_cronogramaperspectivareceita = {$this->iCodigoReceita}";
    $rsTotalPrevisto     = db_query($sSqlTotalPrevisto);
    
    $nValorBaseCalculo = db_utils::fieldsMemory($rsTotalPrevisto, 0)->valortotalprevisto; 
    $lApenasProporciona  = false;
    if ($nValorBaseCalculo == 0) {
      $lApenasProporciona  = true;
    }
    
    $nValorTotalCalculado = 0;
    $nPontoPercentual     = 0;
    for ($iMes = 1; $iMes <= 12; $iMes++) {
      
      $sSqlTotalArrecadadoMes  = "select o125_percentual, o125_valor as valor"; 
      $sSqlTotalArrecadadoMes .= "  from cronogramabasecalculoreceita";
      $sSqlTotalArrecadadoMes .= " where o125_cronogramaperspectivareceita = {$this->iCodigoReceita}";
      $sSqlTotalArrecadadoMes .= "   and o125_mes    = {$iMes}";
      $rsTotalArrecadadoMes   = db_query($sSqlTotalArrecadadoMes);
      if ($rsTotalArrecadadoMes) {
        
        $iTotalMeses      = pg_num_rows($rsTotalArrecadadoMes);
        $oMesBase         = db_utils::fieldsMemory($rsTotalArrecadadoMes, 0);
        $oMes             = new stdClass();
        $nPercentual      = 0;
        if (abs($nValorBaseCalculo > 0)) {
          $nPercentual      = round((($oMesBase->valor*100)/$nValorBaseCalculo) ,2);
        }
        if ($lApenasProporciona && $nValorPrevisto != 0) {
          $nPercentual = 8.33;
        }
        $oMes->valor = 0;
        if (abs($nPercentual) > 0) {
          $oMes->valor           = round(($nValorPrevisto*$nPercentual)/100);
        }
        $oMes->sequencial      = null;
        $oMes->mes             = $iMes;
        $oMes->percentual      = $nPercentual;
        $aArrecadadoMes[$iMes] = $oMes; 
        $nValorTotalCalculado += $oMes->valor;
        $nPontoPercentual     += $nPercentual;
              
      }
    }
    
    /**
     * Fizemos o arredondamento , caso necessário em Dezembro;
     */
    if ((round($nPontoPercentual,2) < 100) || ($nValorTotalCalculado < $nValorPrevisto)) {
      
      $nPercentualDiferenca            = (100 - $nPontoPercentual);
      $aArrecadadoMes[12]->valor      += round(($nValorPrevisto-$nValorTotalCalculado)); 
      $aArrecadadoMes[12]->percentual += $nPercentualDiferenca;
      
    } else if ((round($nPontoPercentual,2) > 100) || ($nValorTotalCalculado > $nValorPrevisto)) {
      
      $nPercentualDiferenca            = ($nPontoPercentual - 100);
      $aArrecadadoMes[12]->valor      -= round($nValorTotalCalculado - $nValorPrevisto); 
      $aArrecadadoMes[12]->percentual -= ($nPercentualDiferenca);
      
    }
    
//    print_r($aArrecadadoMes);
//    exit;
    /**
     * Percorremos os meses  encontrados e persistimos na base
     */
    foreach($aArrecadadoMes as $oMesMeta) {

      $nPercentual = $oMesMeta->percentual;
      if ($oMesMeta->valor == 0) {
        $nPercentual = 0;
      }
      $oDaoCronogramaMeta    =  db_utils::getDao("cronogramametareceita");
      $oDaoCronogramaMeta->o127_cronogramaperspectivareceita = $this->iCodigoReceita;       
      $oDaoCronogramaMeta->o127_mes                          = $oMesMeta->mes;       
      $oDaoCronogramaMeta->o127_percentual                   = "".round($nPercentual,2)."";       
      $oDaoCronogramaMeta->o127_valor                        = "".round($oMesMeta->valor)."";
      
      /**
       * Verificamos se já existe a meta cadastrada
       */
      $sWhere  = "o127_cronogramaperspectivareceita = {$this->iCodigoReceita} "; 
      $sWhere .= " and o127_mes = {$oMesMeta->mes}"; 
      $sSqlVerificaMeta = $oDaoCronogramaMeta->sql_query_file(null,"o127_sequencial", null, $sWhere);
      $rsVerificaMeta   = $oDaoCronogramaMeta->sql_record($sSqlVerificaMeta);
      if ($oDaoCronogramaMeta->numrows > 1) {
        
        $sMsgErro  = "Não foi salvar Meta de arrecadação para o para a Receita {$this->iReceita} ";        
        $sMsgErro .= "no Mês de ".db_mes($oMesMeta->mes)." existem  mais que uma projeção para o mês.";
        throw new Exception($sMsgErro);
        
      } else if ($oDaoCronogramaMeta->numrows == 1) {
        
        $oDaoCronogramaMeta->o127_sequencial = db_utils::fieldsMemory($rsVerificaMeta, 0)->o127_sequencial;
        $oDaoCronogramaMeta->alterar($oDaoCronogramaMeta->o127_sequencial);
        
      } else { 
        $oDaoCronogramaMeta->incluir(null);
      }
      if ($oDaoCronogramaMeta->erro_status == 0) {
        
        $sMsgErro  = "Não foi salvar incluir Meta de arrecadação para o para a Receita {$this->iReceita} ";        
        $sMsgErro .= "no Mês de ".db_mes($oMesMeta->mes);
        throw new Exception($sMsgErro);
                
      }
    } 
  }
  
  function getMetas() {
    
    $this->aMeses       = array();
    $aDadosBases        = array();
    $oDaoCronogramaMeta = db_utils::getDao("cronogramametareceita");
    $sSqlDadosMeta      = $oDaoCronogramaMeta->sql_query_file(null, 
                                                                "*",
                                                                "o127_mes", 
                                                                "o127_cronogramaperspectivareceita={$this->oReceita->iSequencial}");
    //die($sSqlDadosMeta);
    $rsDadosMeta = $oDaoCronogramaMeta->sql_record($sSqlDadosMeta);
    $iTotalMeses = $oDaoCronogramaMeta->numrows;                                                                
    for ($i = 0; $i < $iTotalMeses; $i++) {

       $oMesBase         = db_utils::fieldsMemory($rsDadosMeta, $i);
       $oMes             = new stdClass();
       $oMes->valor      = $oMesBase->o127_valor;
       $oMes->sequencial = $oMesBase->o127_sequencial;
       $oMes->mes        = $oMesBase->o127_mes;
       $oMes->percentual = $oMesBase->o127_percentual;
       $oMes->valormedia = $this->oReceita->o70_valor;
       $this->aMeses[] = $oMes;
       
    }
    
    
    $aDadosBases = $this->aMeses;     
    return $aDadosBases;
  }
  
  public function setValorMes($iMes, $nValor) {

    $nPercentual = 0; 
    if (isset($this->aMeses[$iMes  -1])) {

      $nValorMedia = $this->oReceita->o70_valor;
      $nPercentual = round(($nValor*100)/$nValorMedia,2);
      $this->aMeses[$iMes-1]->valor = $nValor;
      
    }
    return $nPercentual;     
  }
  
  function getReceita() {
    return $this->iReceita;
  }
  
  function getConta() {
    return $this->oReceita->o57_codfon;
  }
  
  function getPercentualDesbramento() {
    return $this->oReceita->o60_perc;
  }
  
  function getValorTotal() {
    return $this->oReceita->o70_valor;
  }
  
  function getDescricaoRecurso() {
    return $this->oReceita->o15_descr;
  }
  
  public function getCaracteristicaPeculiar() {
    return (string)$this->oReceita->o70_concarpeculiar;
  }
  
  public function isDeducao() {
    
    return $this->oReceita->deducao=="t"?true:false;
    
  }
  public function save() {

    /**
     * percorremos todas os meses previstos e anos para a receita e salvamos
     */
    $oDaoMes = db_utils::getDao("cronogramametareceita");
    foreach ($this->aMeses as $oMes) {
      
      $oDaoMes->o127_cronogramaperspectivareceita = $this->iCodigoReceita;
      $oDaoMes->o127_percentual                   = "{$oMes->percentual}";
      $oDaoMes->o127_valor                        = "{$oMes->valor}";
      $oDaoMes->o127_mes                          = "$oMes->mes";
      $oDaoMes->o127_sequencial                   = $oMes->sequencial;
      $oDaoMes->alterar($oDaoMes->o127_sequencial);
      if ($oDaoMes->erro_status == 0) {
        throw new Exception("Erro ao salvar Meses da base de calculo da Receita {$this->iReceita}!".$oDaoMes->erro_msg);
      }
    }
  }
}
?>