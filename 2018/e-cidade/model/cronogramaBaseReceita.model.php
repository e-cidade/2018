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


final class cronogramaBaseReceita {
  
  protected $iReceita;

  protected $iPerspectiva;
  
  protected $aAnos = array();
  
  protected $oReceita ;
  protected $iCodigoReceita;
  
  protected $aMeses = array();
  /**
   * 
   */
  public function __construct($oReceita) {
    
    $this->iReceita       = $oReceita->o70_codrec;
    $this->iPerspectiva   = $oReceita->iPerspectiva;
    $this->iCodigoReceita = $oReceita->iSequencial;
    $this->oReceita       = $oReceita;
  }
  
  public function calcularBases() {
    
    /*
     * Calculos o valor arrecadado no mes, em todos os anos selecionados total de cada ano
     */
    $nPercentual = 0;
    /**
     * Verificamos o total arrecadado nos Anos
     */
    require_once("libs/db_liborcamento.php");
    require_once("libs/db_libcontabilidade.php");
    $iAnoInicial = ($this->oReceita->o70_anousu - 4);
    $iAnoFinal   = ($this->oReceita->o70_anousu - 1);
    $iInstit     = db_getsession("DB_instit");
    /**
     * Percorremos os anos para somarmos a receita arrecadada nos anos anteriores
     */
    for ($iAno = $iAnoInicial; $iAno <= $iAnoFinal; $iAno++) {
      
      $dtDataInicial   = "{$iAno}-01-01";
      $dtDataFinal     = "{$iAno}-12-31";
      $lMediaPonderada = false;
      if ($iAno == db_getsession("DB_anousu")) {
        
        $mesAnterior = date("m",db_getsession("DB_datausu"))-1;
        if ($mesAnterior <= 0) {
          $mesAnterior = 1;
        }
        $ultimoDiaMesAtual = cal_days_in_month(CAL_GREGORIAN,$mesAnterior, $iAno);
        $dtDataFinal       = "{$iAno}-{$mesAnterior}-{$ultimoDiaMesAtual}";
        $lMediaPonderada   = true;
      }
      require_once("libs/db_libpostgres.php");
      if (PostgreSQLUtils::isTableExists("work_receita")) {
        db_query("drop table if exists work_receita");
      }
      $rsReceita = db_receitasaldo(11,
                                   2,
                                   3,
                                   true,
                                   "o70_instit = {$iInstit} and 
                                    o70_codfon = {$this->oReceita->o70_codfon} 
                                    and o70_concarpeculiar = '{$this->oReceita->o70_concarpeculiar}'",
                                   $iAno,
                                   $dtDataInicial,
                                   $dtDataFinal,
                                   false,
                                   ' * ',
                                   false
                                 );
      if (pg_num_rows($rsReceita) == 0) {

         $oAno             = new stdClass();
         $oAno->valor      = 0;
         $oAno->usarmedia  = false;
         $oAno->ano        = $iAno;
         $oAno->sequencial = null;
        
      } else {
        
        
        $oReceitaAno      = db_utils::fieldsMemory($rsReceita, 0);
        $oAno             = new stdClass();
        $oAno->valor      = $oReceitaAno->saldo_arrecadado;
        $oAno->usarmedia  = true;
        $oAno->ano        = $iAno;
        $oAno->sequencial = null; 
       
      }
      $this->aAnos[$iAno] = $oAno;
    }
    /**
     * Calculamos o valor total conforme anos escolhidos para compor a média
     */
    $sAnos        = "";
    $sVirgula     = "";  
    $iBaseDividir = 0;
    foreach ($this->aAnos as $oAno) {
       
      if ($oAno->usarmedia) {
         
        $sAnos    .= $sVirgula.$oAno->ano;
        $sVirgula = ",";
        $iBaseDividir++;
                     
      }
    }
      
    $nMediaValor    = $this->getValorMedia();
    /**
     * Percorremos todos os anos , e os que sao usados para 
     * calcular na media, calculamos os valores mensais do mesmo
     */
    foreach ($this->aAnos as $oAno) {
       
      for ($iMes = 1; $iMes <= 12; $iMes++) {
          
        if (!isset($this->aMeses[$iMes])) {
          
          $oMes             = new stdClass();
          $oMes->valor      = 0;
          $oMes->sequencial = null;
          $oMes->mes        = $iMes;
          $oMes->percentual = "0";
          $this->aMeses[$iMes] = $oMes;
            
        }
        $dtDataInicial   = "{$oAno->ano}-".str_pad($iMes,2,"0",STR_PAD_LEFT)."-01";
        $ultimoDiaMesAtual = cal_days_in_month(CAL_GREGORIAN,$iMes, $oAno->ano);
        $dtDataFinal     = "{$oAno->ano}-".str_pad($iMes,2,"0",STR_PAD_LEFT)."-{$ultimoDiaMesAtual}";
        $lMediaPonderada = false;
          
        if ($oAno->ano == db_getsession("DB_anousu")) {

          $iAno = $oAno->ano;
          $mesAnterior       = date("m",db_getsession("DB_datausu"))-1;
          if ($mesAnterior <= 0) {
            $mesAnterior = 1;
          }
          $ultimoDiaMesAtual = cal_days_in_month(CAL_GREGORIAN,$mesAnterior, $iAno);
          $dtDataFinal       = "{$oAno->ano}-{$mesAnterior}-{$ultimoDiaMesAtual}";
          $lMediaPonderada   = true;  
          
        }

        require_once("libs/db_libpostgres.php");
        if (PostgreSQLUtils::isTableExists("work_receita")) {
          db_query("drop table if exists work_receita");
        }
        if ($oAno->usarmedia) {

          $rsReceita = db_receitasaldo(11,2,3,true,
                                       "o70_instit = {$iInstit} and 
                                        o70_codfon  = {$this->oReceita->o70_codfon}
                                       and o70_concarpeculiar = '{$this->oReceita->o70_concarpeculiar}'",
                                       $oAno->ano,
                                       $dtDataInicial,
                                       $dtDataFinal,
                                       false,
                                       ' * ',
                                       false
                                      );
           if (pg_num_rows($rsReceita) > 0) {
             
             $oReceitaMes      = db_utils::fieldsMemory($rsReceita, 0);
             $this->aMeses[$iMes]->valor += $oReceitaMes->saldo_arrecadado;
              
          }
        }
      }
    }
    /**
     * Persistimos os dados na base de dados
     */
    $oDaoMes = db_utils::getDao("cronogramabasecalculoreceita");
    $oDaoAno = db_utils::getDao("cronogramabaserecano");
    foreach ($this->aMeses as $oArrecadadoMes) {

      $iMes          = $oArrecadadoMes->mes;
      $nValor        = 0;
      if ($iBaseDividir != 0) {
        $nValor        = round($oArrecadadoMes->valor/$iBaseDividir, 2);
      }  
      $nPercentual   = 0;
      if ($nMediaValor !=  0) { 
        $nPercentual    =  abs(round(($nValor*100)/$nMediaValor, 2));
      }
      $oDaoMes->o125_cronogramaperspectivareceita = $this->iCodigoReceita;
      $oDaoMes->o125_percentual                   = "{$nPercentual}";
      $oDaoMes->o125_valor                        = "{$nValor}";
      $oDaoMes->o125_mes                          = "$iMes";
      
      /**
       * Verificamos se nao existe a Receita Cadastrada no mes
       */
      $sWhere       = "o125_cronogramaperspectivareceita = {$this->iCodigoReceita} and o125_mes = {$iMes}";
       
      $sSqlVerifica = $oDaoMes->sql_query_file(null,"*", null, $sWhere);
      $rsVerifica   = $oDaoMes->sql_record($sSqlVerifica);
      if ($oDaoMes->numrows > 1) {
        
        $sMsgErro  = "Não foi possível salvar base de cálculo para a para a receita{$this->iReceita} ";        
        $sMsgErro .= "no Mês de ".db_mes($iMes)." existem mais que uma projeção para o mês.\nContate Suporte";
        throw new Exception($sMsgErro);
        
      } else if ($oDaoMes->numrows == 1) {
        
        $oMesExistente = db_utils::fieldsMemory($rsVerifica, 0);
        $oDaoMes->o125_sequencial = $oMesExistente->o125_sequencial;
        $oDaoMes->alterar($oMesExistente->o125_sequencial);
        
      } else {
        $oDaoMes->incluir(null);
      }
      
      if ($oDaoMes->erro_status == 0) {
        throw new Exception("Erro ao processar base de calculo da Receita {$this->iReceita}!".$oDaoMes->erro_msg);
      }
    }
    
    foreach ($this->aAnos as $oAno) {
      
      $oDaoAno->o129_ano = $oAno->ano;
      $oDaoAno->o129_cronogramaperspectivareceita = $this->iCodigoReceita;
      $oDaoAno->o129_usamedia                     = $oAno->usarmedia?"true":"false";
      $oDaoAno->o129_valor                        = "$oAno->valor";
      /**
       * Verificamos se nao existe a Receita Cadastrada no mes
       */
      $iAno = $oAno->ano;
      $sWhere       = "o129_cronogramaperspectivareceita = {$this->iCodigoReceita} and o129_ano = {$iAno}"; 
      $sSqlVerifica = $oDaoAno->sql_query_file(null,"*", null, $sWhere);
      $rsVerifica   = $oDaoAno->sql_record($sSqlVerifica);
      if ($oDaoAno->numrows > 1) {
        
        $sMsgErro  = "Não foi possível salvar base de cálculo para a para a receita {$this->iReceita} ";        
        $sMsgErro .= "no ano de {$iAno} existem mais que um ano base cadastrado.\nContate Suporte";
        throw new Exception($sMsgErro);
        
      } else if ($oDaoAno->numrows == 1) {
        
        $oAnoExistente = db_utils::fieldsMemory($rsVerifica, 0);
        $oDaoAno->o129_sequencial = $oAnoExistente->o129_sequencial;
        $oDaoAno->alterar($oAnoExistente->o129_sequencial);
        
      } else {
        $oDaoAno->incluir(null);
      }
      if ($oDaoAno->erro_status == 0) {
        throw new Exception("Erro ao processar ano da base de calculo da Receita {$this->iReceita}!".$oDaoAno->erro_msg);
      }      
    }
  }
  
  /**
   * Retorna o Valor da Media dos exercicios anteriores
   *
   * @return float
   */
  public function getValorMedia() {
    
    $nValorMedia = 0;
    
    if (count($this->getAnos()) > 0) {
      
      $aAnos        = $this->getAnos();
      $iBaseDividir = 0;
      $nValorTotal  = 0;
      foreach ($aAnos as $oAno) {
        
        if ($oAno->usarmedia) {
          
          $nValorTotal += $oAno->valor;
          $iBaseDividir++;
                     
        }
      }
      if ($iBaseDividir > 0) {
        $nValorMedia = round($nValorTotal/$iBaseDividir, 2);
      }
    }
    return $nValorMedia;
  }
  
  public function getAnos() {
    
    if (count($this->aAnos) == 0) {
      
     /**
      * Consultamos os anos que fazem parte do calculo da média do me
      */
     $sWhere  = "o129_cronogramaperspectivareceita={$this->oReceita->iSequencial}";
     
     if ($this->oReceita->iSequencial == "") {
       
       print_r($this->oReceita);
       exit;
     }
     $oDaoCronogramaReceitaBaseAnos = db_utils::getDao("cronogramabaserecano");
     $sSqlAnos  = $oDaoCronogramaReceitaBaseAnos->sql_query(null,
                                                                "*",
                                                                "o129_ano",
                                                                $sWhere);
                                                                
      $rsAnos     = $oDaoCronogramaReceitaBaseAnos->sql_record($sSqlAnos);
      $iTotalAnos = $oDaoCronogramaReceitaBaseAnos->numrows;                                                                
      for ($j = 0; $j < $iTotalAnos; $j++) {
        
        $oAnoBase         = db_utils::fieldsMemory($rsAnos, $j);
        $oAno             = new stdClass();
        $oAno->valor      = $oAnoBase->o129_valor;
        $oAno->sequencial = $oAnoBase->o129_sequencial;
        $oAno->ano        = $oAnoBase->o129_ano;
        $oAno->usarmedia  = $oAnoBase->o129_usamedia=="t"?true:false;
        $this->aAnos[$oAnoBase->o129_ano] = $oAno;                                                                   
      }
    }
    return $this->aAnos;
  }
  
  
  public function getDadosBase() {
    
    /**
     * Consultamos todas as bases cadastradas para a receita
     */
    $this->aMeses = array();
    $aDadosBases  = array();
    $oDaoCronogramaReceitaBase = db_utils::getDao("cronogramabasecalculoreceita");
    $sSqlDadosBase = $oDaoCronogramaReceitaBase->sql_query_file(null, 
                                                                "*,
                                                                (0) as valormedia",
                                                                "o125_mes", 
                                                                "o125_cronogramaperspectivareceita={$this->oReceita->iSequencial}");
    $rsDadosBase = $oDaoCronogramaReceitaBase->sql_record($sSqlDadosBase);
    $iTotalMeses = $oDaoCronogramaReceitaBase->numrows;                                                                
    for ($i = 0; $i < $iTotalMeses; $i++) {

       $oMesBase         = db_utils::fieldsMemory($rsDadosBase, $i);
       $oMes             = new stdClass();
       $oMes->valor      = $oMesBase->o125_valor;
       $oMes->sequencial = $oMesBase->o125_sequencial;
       $oMes->mes        = $oMesBase->o125_mes;
       $oMes->percentual = $oMesBase->o125_percentual;
       $oMes->valormedia = $oMesBase->valormedia;
       $this->aMeses[] = $oMes;
       
    }
    
    $aDadosBases = $this->aMeses;     
    return $aDadosBases;
  }
  
  /**
   * Define se o ano escolhido sera usado na média para o mes
   *
   * @param integer $iAno Ano base
   * @param integer $iMes Mes base
   * @param boolean $lUsarMedia true, o Ano é utilizado no mes , False nao é considerado
   */
  
  public function setAnoNaMedia($iAno, $lUsarMedia) {
    
    if ($lUsarMedia == ""){
      $lUsarMedia = false;
    }
    if (isset($this->aAnos[$iAno])) {
      $this->aAnos[$iAno]->usarmedia = $lUsarMedia;      
    }
  }
  
  /**
   * Define o valor da base de um ano
   *
   * @param integer $iAno Ano base
   * @param integer $iMes Mes base
   * @param float   $nValor valor novo da base
   */
  public function setValorAno($iAno, $nValor) {
    
    if (isset($this->aAnos[$iAno])) {
      $this->aAnos[$iAno]->valor = $nValor;      
    }
  }
  
  public function setValorMes($iMes, $nValor) {

    $nPercentual = 0; 
    if (isset($this->aMeses[$iMes  -1])) {
      
      $nValorMedia = $this->getValorMedia();
      $nPercentual = round(($nValor*100)/$nValorMedia,2);
      $this->aMeses[$iMes-1]->valor = $nValor;
            
    }
    return $nPercentual;     
  }
  
  function save() {

    /**
     * percorremos todas os meses previstos e anos para a receita e salvamos
     */
    $oDaoMes = db_utils::getDao("cronogramabasecalculoreceita");
    $oDaoAno = db_utils::getDao("cronogramabaserecano");  
    foreach ($this->aMeses as $oMes) {
      
      $oDaoMes->o125_cronogramaperspectivareceita = $this->iCodigoReceita;
      $oDaoMes->o125_percentual                   = "{$oMes->percentual}";
      $oDaoMes->o125_valor                        = "{$oMes->valor}";
      $oDaoMes->o125_mes                          = "$oMes->mes";
      $oDaoMes->o125_sequencial                   = $oMes->sequencial;
      $oDaoMes->alterar($oDaoMes->o125_sequencial);
      if ($oDaoMes->erro_status == 0) {
        throw new Exception("Erro ao salvar Meses da base de calculo da Receita {$this->iReceita}!".$oDaoMes->erro_msg);
      }
    }
    
    foreach ($this->aAnos as $oAno) {
      
      $oDaoAno->o129_usamedia              = $oAno->usarmedia?"true":"false";
      $oDaoAno->o129_valor                 = "$oAno->valor";
      $oDaoAno->o129_sequencial            = $oAno->sequencial;
      $oDaoAno->alterar($oDaoAno->o129_sequencial);
      if ($oDaoAno->erro_status == 0) {
         throw new Exception("Erro ao salvar Anos da base de calculo da Receita {$this->iReceita}!".$oDaoAno->erro_msg);
      }      
    }
  }
  
  function getReceita() {
     return $this->iReceita;
  }
  
  public function isDeducao() {
    
    return $this->oReceita->deducao=="t"?true:false;
    
  }
  
  function getValorTotal () {
    return $this->getValorMedia();
  }
}
?>