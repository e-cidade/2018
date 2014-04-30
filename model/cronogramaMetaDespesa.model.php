<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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


final class cronogramaMetaDespesa {
  
  protected $iDespesa;

  protected $iPerspectiva;
  
  protected $aAnos = array();
  
  protected $oDespesa ;
  
  protected $iCodigoDespesa;
  
  protected $iNivel = null;
  
  protected $aInstituicoes = array();
  public function __construct($oDespesa, $iNivel = null) {
    
    if ($iNivel == null) {
      
      $this->iDespesa       = $oDespesa->dotacao;
      $this->iPerspectiva   = $oDespesa->iPerspectiva;
      $this->iCodigoDespesa = $oDespesa->iSequencial;
      $this->oDespesa       = $oDespesa;
      
    } else {
      
      $this->iNivel = $iNivel;
      $this->iPerspectiva   = $oDespesa->iPerspectiva;
      $this->oDespesa       = $oDespesa;
      if ($iNivel != 99) {
        $this->iDespesa       = $oDespesa->codigo;
      }
    }
  }
  
  public function calcularMetas() {
    
    $nValorTotalCalculado = 0;
    $nPontoPercentual     = 0;
    /**
     * Descobrimos o total projeto para a arrecadacao da receita no ano 
     */
    $sSqlTotalReceita  =  "SELECT coalesce(sum(o127_valor),0) as valortotal"; 
    $sSqlTotalReceita .=  "  from cronogramametareceita";
    $sSqlTotalReceita .=  "       inner join cronogramaperspectivareceita on o127_cronogramaperspectivareceita = o126_sequencial";
    $sSqlTotalReceita .=  "       inner join orcreceita on o70_codrec = o126_codrec and o70_anousu = o126_anousu";
    $sSqlTotalReceita .=  " where o126_cronogramaperspectiva = {$this->iPerspectiva}";
    $sSqlTotalReceita .=  "   and o70_codigo                 = {$this->oDespesa->recurso}";
    $rsTotalReceita   = db_query($sSqlTotalReceita);
    $nTotalReceita    = db_utils::fieldsMemory($rsTotalReceita, 0)->valortotal;
    
    $nValorDespesa    = $this->oDespesa->valororcado;
    for ($iMes = 1; $iMes <= 12; $iMes++) {

//      $oMes->sequencial      = null;
//      $oMes->mes             = $iMes;
//      $oMes->valordot        = 0;
//      $oMes->dot             = 0;
//      $oMes->percentual      = 0;
//      $oMes->valormes        = 0;
//      $aGastoMes[$iMes]      = $oMes; 
      
      /**
       * Calculamos o total das receita no mes
       */
      $sSqlTotalReceitaMes  = "SELECT coalesce(sum(o127_valor),0) as valortotal"; 
      $sSqlTotalReceitaMes .= "  from cronogramametareceita";
      $sSqlTotalReceitaMes .= "       inner join cronogramaperspectivareceita on o127_cronogramaperspectivareceita = o126_sequencial";
      $sSqlTotalReceitaMes .= "       inner join orcreceita on o70_codrec = o126_codrec and o70_anousu = o126_anousu";
      $sSqlTotalReceitaMes .= " where o126_cronogramaperspectiva = {$this->iPerspectiva}";
      $sSqlTotalReceitaMes .= "   and o127_mes                   = {$iMes}";
      $sSqlTotalReceitaMes .= "   and o70_codigo                 = {$this->oDespesa->recurso}";
      
      $rsTotalReceitaMes   = db_query($sSqlTotalReceitaMes);
      if ($rsTotalReceitaMes) {
        
        $oMesBase         = db_utils::fieldsMemory($rsTotalReceitaMes, 0);
        $oMes             = new stdClass();
        $nPercentual      = 0;
        if ($oMesBase->valortotal  < 0) {
          
          $sMsgErro  = "Total da meta de arrecadação do recurso ({$this->oDespesa->recurso}) ";
          $sMsgErro .= "para o mês de ".db_mes($iMes) ." está negativo.\n";
          $sMsgErro .= "Você deverá acessar a rotina de processamento das metas de receita e verificar. ";
          $sMsgErro .= "Provavelmente o valor previsto para as deduções da receita está superando o ";
          $sMsgErro .= "previsto para as arrecadações.";
          $iCodigoErro = $this->oDespesa->recurso;
          throw new Exception($sMsgErro, $iCodigoErro);
          
        }
        
        if (abs($nTotalReceita > 0)) {
          $nPercentual     = round((($oMesBase->valortotal*100)/$nTotalReceita) ,2);
        }
        
        $oMes->valor = 0;
        if (abs($nPercentual) > 0) {
          $oMes->valor           = round(($nValorDespesa*$nPercentual)/100);
        }
        
        $oMes->sequencial      = null;
        $oMes->mes             = $iMes;
        $oMes->valordot        = $nValorDespesa;
        $oMes->dot             = $this->iDespesa;
        $oMes->percentual      = $nPercentual;
        $oMes->valormes        = $oMesBase->valortotal;
        $aGastoMes[$iMes]      = $oMes; 
        
        $nValorTotalCalculado += $oMes->valor;
        $nPontoPercentual     += $nPercentual;
              
      }
    }
    /**
     * Fizemos o arredondamento , caso necessário em Dezembro;
     */
    if ((round($nPontoPercentual,2) < 100) || ($nValorTotalCalculado < $nValorDespesa)) {
      
      $nPercentualDiferenca       = (100 - $nPontoPercentual);
      $aGastoMes[12]->valor      += round(($nValorDespesa-$nValorTotalCalculado)); 
      $aGastoMes[12]->percentual += $nPercentualDiferenca;
      
    } else if ((round($nPontoPercentual,2) > 100) || ($nValorTotalCalculado > $nValorDespesa)) {
      
      $nPercentualDiferenca       = ($nPontoPercentual - 100);
      $aGastoMes[12]->valor      -= round($nValorTotalCalculado - $nValorDespesa); 
      $aGastoMes[12]->percentual -= ($nPercentualDiferenca);
      
    }
    
    /**
     * Percorremos os meses  encontrados e persistimos na base
     */
    foreach($aGastoMes as $oMesMeta) {

      $nPercentual = $oMesMeta->percentual;
      if ($oMesMeta->valor == 0) {
        $nPercentual = 0;
      }
      
      $oDaoCronogramaMeta    =  db_utils::getDao("cronogramametadespesa");
      $oDaoCronogramaMeta->o131_cronogramaperspectivadespesa = $this->iCodigoDespesa;       
      $oDaoCronogramaMeta->o131_mes                          = $oMesMeta->mes;       
      $oDaoCronogramaMeta->o131_percentual                   = "".round($nPercentual,2)."";       
      $oDaoCronogramaMeta->o131_valor                        = "".round($oMesMeta->valor)."";
      
      /**
       * Verificamos se já existe a meta cadastrada
       */
      $sWhere  = "o131_cronogramaperspectivadespesa = {$this->iCodigoDespesa} "; 
      $sWhere .= " and o131_mes = {$oMesMeta->mes}"; 
      $sSqlVerificaMeta = $oDaoCronogramaMeta->sql_query_file(null,"o131_sequencial", null, $sWhere);
      $rsVerificaMeta   = $oDaoCronogramaMeta->sql_record($sSqlVerificaMeta);
      if ($oDaoCronogramaMeta->numrows > 1) {
        
        $sMsgErro  = "Não foi salvar Meta de gastos para o para a Despesa {$this->iDespesa} ";        
        $sMsgErro .= "no Mês de ".db_mes($oMesMeta->mes)." existem  mais que uma projeção para o mês.";
        throw new Exception($sMsgErro);
        
      } else if ($oDaoCronogramaMeta->numrows == 1) {
        
        $oDaoCronogramaMeta->o131_sequencial = db_utils::fieldsMemory($rsVerificaMeta, 0)->o131_sequencial;
        $oDaoCronogramaMeta->alterar($oDaoCronogramaMeta->o131_sequencial);
        
      } else { 
        $oDaoCronogramaMeta->incluir(null);
      }
      if ($oDaoCronogramaMeta->erro_status == 0) {
        
        $sMsgErro  = "Não foi salvar incluir Meta gastos para o para a Despesa {$this->iDespesa} ";        
        $sMsgErro .= "no Mês de ".db_mes($oMesMeta->mes);
        throw new Exception($sMsgErro);
                
      }
    } 
  }
  
  function getMetas($sFiltros) {
    
    require_once("libs/db_liborcamento.php");
    $oSelDotacao = new cl_selorcdotacao();
    $oSelDotacao->setDados($sFiltros); // passa os parametros vindos da func_selorcdotacao_abas.php
    $this->aMeses       = array();
    $aDadosBases        = array();
    $oDaoCronogramaMeta = db_utils::getDao("cronogramametadespesa");
    if ($this->iNivel  == null) {
      
      $sWhere = "o127_cronogramaperspectivadespesa={$this->oDespesa->iSequencial}";
      $sSqlDadosMeta      = $oDaoCronogramaMeta->sql_query_file(null, 
                                                                "*",
                                                                "o127_mes", 
                                                                "");  
    } else {
      
      
      $sInstituicoes   = implode(",", $this->getInstituicoes());
      $sWhere  = " o130_cronogramaperspectiva = {$this->iPerspectiva}";
      $sWhere .= " and o58_instit in ({$sInstituicoes})";
      $sWhere     .= " and ".$oSelDotacao->getDados(false);             
      $sWhere .= $this->getFiltroByNivel();
      $sSqlDadosMeta     = $oDaoCronogramaMeta->sql_query(null, 
                                                                "sum(o131_valor) as valor, o131_mes",
                                                                "o131_mes", 
                                                                $sWhere ." group by o131_mes"
                                                                ); 
             
    }
    //die($sSqlDadosMeta);
    /**
     * Aplicamos um generate series, para garantir que teremos linhas  para todos os meses.
     */
    $sSqlDadosMetas  =  "select valor, meses as o131_mes ";
    $sSqlDadosMetas .=  "  from generate_series(1,12) meses";
    $sSqlDadosMetas .= "        left join ({$sSqlDadosMeta}) as dados on meses = dados.o131_mes";
    
    $rsDadosMeta = $oDaoCronogramaMeta->sql_record($sSqlDadosMetas);
    $iTotalMeses = $oDaoCronogramaMeta->numrows; 
    for ($i = 0; $i < $iTotalMeses; $i++) {

       $oMesBase         = db_utils::fieldsMemory($rsDadosMeta, $i);
       $oMes             = new stdClass();
       if ($this->iNivel == null) {
         
         $oMes->valor      = $oMesBase->o131_valor;
         $oMes->sequencial = $oMesBase->o131_sequencial;
         $oMes->mes        = $oMesBase->o131_mes;
         $oMes->percentual = $oMesBase->o131_percentual;
         $oMes->valormedia = $this->oDespesa->valororcado;
         
       } else {
         
         $oMes->valor      = $oMesBase->valor;
         $oMes->sequencial = null;
         $oMes->mes        = $oMesBase->o131_mes;
         $oMes->percentual = 0;
         if ($this->oDespesa->valororcado > 0) {
           $oMes->percentual = round(($oMesBase->valor*100)/$this->oDespesa->valororcado, 2);  
         }
         $oMes->valormedia = $this->oDespesa->valororcado;
         
       }
       $this->aMeses[] = $oMes;
    }
    
    $aDadosBases = $this->aMeses;     
    return $aDadosBases;
  }
  
  public function setValorMes($iMes, $nValor) {

    $nPercentual = 0; 
    if (isset($this->aMeses[$iMes  -1])) {

      $nValorMedia = $this->oDespesa->valororcado;
      $nPercentual = round(($nValor*100)/$nValorMedia,2);
      $this->aMeses[$iMes-1]->valor = $nValor;
      
    }
    return $nPercentual;     
  }
  
  function getDespesa() {
    return $this->iDespesa;
  }
  
  function getValorTotal() {
    return $this->oDespesa->valororcado;
  }
  
  public function save() {

    /**
     * percorremos todas os meses previstos e anos para a receita e salvamos
     */
    $oDaoMes = db_utils::getDao("cronogramametadespesa");
    foreach ($this->aMeses as $oMes) {
      
      
      /**
       * Percorremos as dotacoes cadastradas  conforme o nivel informado, e aplicamos o valor definido no nivel para cada dotacao
       */
      $sInstituicoes  = implode(",", $this->getInstituicoes());
      $sWhere         = " o130_cronogramaperspectiva = {$this->iPerspectiva}";
      $sWhere        .= " and o58_instit in ({$sInstituicoes})";
      $sWhere        .= " and o131_mes = $oMes->mes";
      $sWhere        .= $this->getFiltroByNivel();
      
      $sSqlDotacoesNivel =  $oDaoMes->sql_query(null, 
                                                "o131_valor , o58_coddot, o131_mes, o131_sequencial, o58_valor",
                                                "o131_mes", 
                                                $sWhere
                                               );
      $rsDotacoesNivel = $oDaoMes->sql_record($sSqlDotacoesNivel);
      if ($oDaoMes->numrows > 0) {

        $nValorTotalMes       = $oMes->valor;
        $nValorAcumulado      = 0;
        $iTotalRegistrosNivel = $oDaoMes->numrows;  
        for ($i = 0; $i <  $iTotalRegistrosNivel; $i++) {
          
          $oDotacao  = db_utils::fieldsMemory($rsDotacoesNivel, $i);
          $nValor    = round(($oDotacao->o58_valor*$oMes->percentual/100));
          $nValorAcumulado += $nValor;
          //echo "dotacao: {$oDotacao->o58_coddot}- {$oDotacao->o58_valor} nValor: {$nValor} - Valor Acumulado: {$nValorAcumulado}\n";
          if (($i+1) == $iTotalRegistrosNivel) {
            
            $nDiferenca = $nValorTotalMes - $nValorAcumulado;
           // echo "dotacao: {$oDotacao->o58_coddot} nValor: {$nValor} - Valor Acumulado: {$nValorAcumulado} Dif: {$nDiferenca}\n";
            $nValor     += $nDiferenca;
           // echo " Modificado: {$oDotacao->o58_coddot} nValor: {$nValor} - Valor Acumulado: {$nValorAcumulado} Dif: {$nDiferenca}\n"; 
          }
          $oDaoMes->o131_percentual                   = "{$oMes->percentual}";
          $oDaoMes->o131_valor                        = "{$nValor}";
          $oDaoMes->o131_mes                          = "$oMes->mes";
          $oDaoMes->o131_sequencial                   = $oDotacao->o131_sequencial;
          $oDaoMes->alterar($oDotacao->o131_sequencial);
          if ($oDaoMes->erro_status == 0) {
            throw new Exception("Erro ao salvar Meses da base de calculo da Despesa {$this->iDespesa}!".$oDaoMes->erro_msg);
          }
        }
      }
    }
  }
  
 public function getInstituicoes() {

    return $this->aInstituicoes;
  }
  
  /**
   * @param array $aInstituicoes
   */
  public function setInstituicoes($aInstituicoes) {

    $this->aInstituicoes = $aInstituicoes;
  }
  
  public function getNivel() {
    return $this->iNivel; 
  }
  
  private function getFiltroByNivel() {
    
     $sWhere = "";
    switch ($this->iNivel) {
        
      case 1:
         
        $sWhere  .= "   and o58_orgao     = {$this->oDespesa->codigo}";    
        break;
          
      case 2:
  
        $sWhere .= "   and o58_unidade   = {$this->oDespesa->codigo} and o58_orgao = {$this->oDespesa->o58_orgao}";    
        break;
  
      case 3:
          
        $sWhere  .= "   and o58_funcao    = {$this->oDespesa->codigo}";   
        break;  
  
      case 4:
        
        $sWhere  .= "   and o58_subfuncao = {$this->oDespesa->codigo}";   
        break;  
          
      case 5:
          
        $sWhere    .= "   and o58_programa  = {$this->oDespesa->codigo}";    
        break;
  
      case 6:
          
        $sWhere   .= "   and o58_projativ  = {$this->oDespesa->codigo}";
        break;  
          
      case 7:
          
        $sWhere    .= "   and o58_codele  = {$this->oDespesa->codigo}";   
        break;  
  
      case 8:
          
        $sWhere   .= "   and o58_codigo   = {$this->oDespesa->codigo}";    
        break;
     case 99:

        $sWhere .= "   and o58_orgao     = {$this->oDespesa->o58_orgao}";    
        $sWhere .= "   and o58_unidade   = {$this->oDespesa->o58_unidade}";    
        $sWhere .= "   and o58_funcao    = {$this->oDespesa->o58_funcao}";   
        $sWhere .= "   and o58_subfuncao = {$this->oDespesa->o58_subfuncao}";   
        $sWhere .= "   and o58_programa  = {$this->oDespesa->o58_programa}";    
        $sWhere .= "   and o58_projativ  = {$this->oDespesa->o58_projativ}";
        $sWhere .= "   and o58_codele    = {$this->oDespesa->o58_codele}";   
        $sWhere .= "   and o58_codigo    = {$this->oDespesa->o58_codigo}";
        break;     
    }

    return $sWhere;
  }
}
?>