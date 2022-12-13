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


class SimulaCalculoInscricao {
  
  public $iCodigoSimulacao           = null;  
  public $oAtividadesCalculo         = array();
  public $dDataCalculo               = null;
  public $iAnoCalculo                = null;
  public $iMesCalculo                = null;
                                     
  public $nValorBase                 = null;
  public $iCalculaFixVar             = null;
  public $iDiasParaVencimento        = null;
  public $dDataBase                  = null;
  public $nValorInflator             = null; 
  public $iTipoQuantidade            = null;
                                     
  public $dDataCadastroInscricao     = null;
  public $dDataInicioAtividade       = null;
  public $iAnoInicioAtividade        = null;
  public $iMesInicioAtividade        = null;
  
  public $lDebug                     = false;
  public $sLogCalculo                = null;
  
  function __construct ($iCodigoSimulacao) {
    
      $this->setCodigoSimulacao($iCodigoSimulacao); 
      
      $oDaoIssSimulaCalculo  = db_utils::getDao("isssimulacalculo");
      
      $sSqlIssSimulaCalculo  = $oDaoIssSimulaCalculo->sql_query_file($iCodigoSimulacao); 
      $rsIssSimulacaoCalculo = $oDaoIssSimulaCalculo->sql_record($sSqlIssSimulaCalculo);
      if ($oDaoIssSimulaCalculo->numrows == 0 ) {
        throw new Exception("[ Erro 1 ] - Não encontrados dados para processamento do calculo de simulação");
      }  
      
      $oDadosSimulacao =  db_utils::fieldsMemory($rsIssSimulacaoCalculo,0);
      
      $this->setDataCalculo(date("Y-m-d",db_getsession("DB_datausu")));
      $this->setDataInicioAtividade($oDadosSimulacao->q130_datainicio);
      $this->setAtividadeCalculo();
      
      $this->setParametrosISSQN($this->getAnoCalculo());
    
  }
  
  function setCodigoSimulacao($iCodigoSimulacao){
    
    $sMsgLog = "Código da Simulação: {$iCodigoSimulacao}";
    $this->logCalculo($sMsgLog);
    
    $this->iCodigoSimulacao = $iCodigoSimulacao;
    
  }
  
  function getCodigoSimulacao() {
    return $this->iCodigoSimulacao;
  }  
  
  private function setParametrosISSQN($iAno) {
    
    $oDaoCISSQN   = db_utils::getDao("cissqn"); 
    $oDaoParISSQN = db_utils::getDao("parissqn");
    
    $sSqlCISSQN = $oDaoCISSQN->sql_query($iAno, "*");
    $rsCISSQN   = $oDaoCISSQN->sql_record($sSqlCISSQN);
    if ($oDaoCISSQN->numrows == 0 ){
       $sMsg = "[ Erro 2 ] - Verifique o cadastro dos parametros do módulo ISSQN!";
       $this->logCalculo($sMsg);
       throw new Exception($sMsg);
    }
    
    $oDadosCISSQN = db_utils::fieldsMemory($rsCISSQN,0);
    $this->nValorBase       = $oDadosCISSQN->q04_vbase;
    if(empty($this->nValorBase)) {
      $sMsg = "[ Erro 2.1 ] - Valor Base não configurado nos parâmetros do ISSQN";
      $this->logCalculo($sMsg);
      throw new Exception($sMsg);
    }
    
    $this->iCalculaFixVar   = $oDadosCISSQN->q04_calfixvar;
    
    $this->iDiasParaVencimento  = $oDadosCISSQN->q04_diasvcto;
    if($this->iDiasParaVencimento == "") {
      $sMsg = "[ Erro 2.2 ] - Dias de Vencimento não configurados nos parâmetros do ISSQN";
      $this->logCalculo($sMsg);
      throw new Exception($sMsg);
    }
    
    $this->dDataBase        = $oDadosCISSQN->q04_dtbase;
    if(empty($this->dDataBase)) {
      $sMsg = "[ Erro 2.3 ] - Data Base não configurada nos parâmetros do ISSQN";
      $this->logCalculo($sMsg);
      throw new Exception($sMsg);
    }
    

    if ($oDadosCISSQN->q04_inflat == "REAL") {
    	$this->nValorInflator   = 1;
    } else {
      $sSqlValorInflator  = "select distinct                                    ";
      $sSqlValorInflator .= "       i02_valor                                   ";
      $sSqlValorInflator .= "  from cissqn                                      ";
      $sSqlValorInflator .= "       inner join infla on q04_inflat = i02_codigo ";
      $sSqlValorInflator .= " where cissqn.q04_anousu = {$iAno}                 ";
      $sSqlValorInflator .= "   and date_part('y',i02_data) = {$iAno}           ";
      $rsValorInflator   = db_query($sSqlValorInflator);
      if(pg_num_rows($rsValorInflator) == 0) {
        $sMsg = "[ Erro 2.4 ] - Valor do Inflator {$oDadosCISSQN->q04_inflat} não encontrado";
        $this->logCalculo($sMsg);
        throw new Exception($sMsg);
      }
      $this->nValorInflator   = db_utils::fieldsMemory($rsValorInflator,0)->i02_valor;
    }

    $sSqlParISSQN = $oDaoParISSQN->sql_query_file(null, "q60_campoutilcalc");
    $rsParISSQN   = $oDaoParISSQN->sql_record($sSqlParISSQN);
    if ($oDaoParISSQN->numrows == 0){
       $sMsg = "[ Erro 2.5 ] - Verifique o cadastro dos parametros do módulo ISSQN!";
       $this->logCalculo($sMsg);
       throw new Exception($sMsg);
    }    
    
    $this->iTipoQuantidade = db_utils::fieldsMemory($rsParISSQN,0)->q60_campoutilcalc;
    
    $sMsgLog = "Parâmetros do ISSQN : <br>";
    $sMsgLog .= "Valor Base ..................: {$this->nValorBase}          <br>";
    $sMsgLog .= "Calcula Fixo e Variável .....: {$this->iCalculaFixVar}      <br>"; 
    $sMsgLog .= "Dias para Vencimento ........: {$this->iDiasParaVencimento}     <br>";
    $sMsgLog .= "Data base para calculo ......: {$this->dDataBase}           <br>";
    $sMsgLog .= "Inflator ....................: {$oDadosCISSQN->q04_inflat}  <br>";      
    $sMsgLog .= "Valor do Inflator ...........: {$this->nValorInflator}      <br>";
    $sMsgLog .= "Utiliza tipo de Quantidade ..: {$this->iTipoQuantidade}     <br>";   
    $this->logCalculo($sMsgLog);
     
  }
  
  
  function setDataCalculo($dData) {
    
    $sMsgLog = "Data para calculo: {$dData}";
    $this->logCalculo($sMsgLog);
    
    $this->dDataCalculo = $dData;
    
    if ($this->getAnoCalculo() == null) {
      $this->setAnoCalculo(substr($dData,0,4));
    }
    
    if ($this->getMesCalculo() == null) {
      $this->setMesCalculo(substr($dData,5,2));
    }    
     
  }  
  
  function getDataCalculo() {
    return $this->dDataCalculo; 
  }  
  
  function setDataInicioAtividade($dData) {
    
    $sMsgLog = "Data de Início da Atividade: {$dData}";
    $this->logCalculo($sMsgLog);
    
    $this->dDataInicioAtividade = $dData;
    if ($this->getAnoInicioAtividade() == null) {
      $this->setAnoInicioAtividade(substr($dData,0,4));
    }
    
    if ($this->getMesInicioAtividade() == null) {
      $this->setMesInicioAtividade(substr($dData,5,2));
    }    
    
  }
  
  function getDataInicioAtividade() {
    return $this->dDataInicioAtividade;
  }
  
  function setAnoInicioAtividade($iAno) {
    $this->iAnoInicioAtividade = $iAno;
  }
  
  function getAnoInicioAtividade() {
    return $this->iAnoInicioAtividade;
  }
  
  function setMesInicioAtividade($iMes) {
    $this->iMesInicioAtividade = $iMes;
  }

  function getMesInicioAtividade() {
    return $this->iMesInicioAtividade;
  }
  
  function setAnoCalculo($iAno) {
    
    $sMsgLog = "Ano para calculo: {$iAno}";
    $this->logCalculo($sMsgLog); 
       
    $this->iAnoCalculo = $iAno;
     
  }
  
  function getAnoCalculo() {
    return $this->iAnoCalculo;
  }
  
  function setMesCalculo($iMes) {
    
    $sMsgLog = "Mes para calculo: {$iMes}";
    $this->logCalculo($sMsgLog); 
       
    $this->iMesCalculo = $iMes;
     
  }  
  
  function getMesCalculo() {
    return $this->iMesCalculo;
  }
  
  function setAtividadeCalculo() {
    
    $oDaoIssSimulaCalculoAtividade = db_utils::getDao("isssimulacalculoatividade");
    
       
    $sCampos = "q131_permanente as permanente, 
                q131_seq        as sequencia,
                q131_principal  as principal, 
                '*'::char(1)    as calcula,
                q131_atividade  as atividade, 
                q03_descr       as descricao, 
                q131_quantidade as quantidade";
    $sWhere = "q131_issimulacalculo = {$this->getCodigoSimulacao()}";
    
    $sSqlIssSimulaCalculoAtividade = $oDaoIssSimulaCalculoAtividade->sql_query(null, $sCampos, null, $sWhere);
    $rsIssSimulaCalculoAtividade   = $oDaoIssSimulaCalculoAtividade->sql_record($sSqlIssSimulaCalculoAtividade); 
    if ($oDaoIssSimulaCalculoAtividade->numrows == 0) {
      $sMsg = "[ Erro 3 ] - Nenhuma atividade encontrada para a simulacao do Calculo";
      $this->logCalculo($sMsg);
      throw new Exception($sMsg);
    }      
      
    $oAtividadeSimulacao = db_utils::getCollectionByRecord($rsIssSimulaCalculoAtividade);
    
    $sMsgLog = "Atividades para Simulação: <br>";
    foreach ($oAtividadeSimulacao as $oAtividade) {
      $sMsgLog .= "($oAtividade->sequencia) - Atividade: {$oAtividade->atividade} - ".str_pad($oAtividade->descricao,40," ");
      $sMsgLog .= "Quantidade: {$oAtividade->quantidade} Permanente: {$oAtividade->permanente} <br>"; 
    }
    $this->logCalculo($sMsgLog);
    
    $this->oAtividadesCalculo = $oAtividadeSimulacao;
    
  }
  
  function getAtividadeCalculo() {
    return $this->oAtividadesCalculo;
  }
    
  function buscaPontuacaoSimulacao($iCodigoAtividade, $iTipoQuantidade) {
    
    $oDaoIssSimulaCalculo = db_utils::getDao("isssimulacalculo");
    $oDaoAreaPont         = db_utils::getDao("areapont");
    $oDaoEmpregPont       = db_utils::getDao("empregpont");
    $oDaoZonaPont         = db_utils::getDao("zonapont");
    $oDaoClassePont       = db_utils::getDao("classepont");
    
    $sSqlQuantidadeSimulacao = $oDaoIssSimulaCalculo->sql_query_file($this->getCodigoSimulacao(), "q130_zona,q130_empregados,q130_area");
    
    $rsQuantidadeSimulacao   = $oDaoIssSimulaCalculo->sql_record($sSqlQuantidadeSimulacao);
    if ($oDaoIssSimulaCalculo->numrows == 0) {
      throw new Exception ("[ Erro 4 ] - Erro ao buscar quantidades informadas para a simulação do calculo");
    }
    
    $oDadosQuantidadeSimulacao = db_utils::fieldsMemory($rsQuantidadeSimulacao,0);
    
    switch ($iTipoQuantidade) {
      
      case 1:
        
        $sMsgLog = "Quantidade configurada (quantidade de Area): {$oDadosQuantidadeSimulacao->q130_area} ";
        $iQuantidadeAtividade = $oDadosQuantidadeSimulacao->q130_area;
        
      break;  
      
      case 2:
        
        $sMsgLog = "Quantidade configurada (quantidade de empregados): {$oDadosQuantidadeSimulacao->q130_empregados} ";
        $iQuantidadeAtividade = $oDadosQuantidadeSimulacao->q130_empregados;
        
      break;
        
      case 3:
        /*
         * Pontuação por area
         */
        $iAreaPont = 0;
        $sWhere = "$oDadosQuantidadeSimulacao->q130_area between q28_quantini and q28_quantfim";
        $sSqlAreaPont = $oDaoAreaPont->sql_query(null, "coalesce(q28_pontuacao,0) as pontos", null, $sWhere);
        $rsAreaPont   = $oDaoAreaPont->sql_record($sSqlAreaPont);
        if($oDaoAreaPont->numrows > 0) {
          $iAreaPont    = db_utils::fieldsMemory($rsAreaPont,0)->pontos;
        }
        
        
        /*
         * Pontuação por quantidade de empregados 
         */
        $iEmpregPont = 0;
        $sWhere = "$oDadosQuantidadeSimulacao->q130_empregados between q27_quantini and q27_quantfim";
        $sSqlEmpregPont = $oDaoEmpregPont->sql_query(null, "coalesce(q27_pontuacao,0) as pontos", null, $sWhere);
        $rsEmpregPont   = $oDaoEmpregPont->sql_record($sSqlEmpregPont);
        if ($oDaoEmpregPont->numrows > 0) {
          $iEmpregPont    = db_utils::fieldsMemory($rsEmpregPont,0)->pontos;
        } 
        
        /*
         * Pontuação por Zona
         */ 
        $iZonaPont = 0;
        $sWhere = "zonapont.q26_zona = $oDadosQuantidadeSimulacao->q130_zona ";
        $sSqlZonaPont = $oDaoZonaPont->sql_query(null, "coalesce(zonapont.q26_pontuacao,0) as pontos", null, $sWhere);
        $rsZonaPont   = $oDaoZonaPont->sql_record($sSqlZonaPont);
        if ($oDaoZonaPont->numrows > 0) {
          $iZonaPont    = db_utils::fieldsMemory($rsZonaPont,0)->pontos;
        }    
        
        
         /*
          * Pontuação das Classes da Atividade
          */ 
        $iClassePont = 0;
        $sWhere = "clasativ.q82_ativ = {$iCodigoAtividade} ";
        $sSqlClassePont = $oDaoClassePont->sql_query(null, "coalesce(classepont.q25_pontuacao,0) as pontos", null, $sWhere);
        $rsClassePont   = $oDaoClassePont->sql_record($sSqlClassePont);
        if ($oDaoClassePont->numrows > 0) {
          $iClassePont    = db_utils::fieldsMemory($rsClassePont,0)->pontos;
        } 
             
        $iQuantidadeAtividade = $iAreaPont + $iEmpregPont + $iZonaPont + $iClassePont;
            
        $sMsgLog  = "ATIVIDADE: {$iCodigoAtividade}<br>";
        $sMsgLog .= "Quantidade encontrada por Area .....................: {$oDadosQuantidadeSimulacao->q130_area} <br>"; 
        $sMsgLog .= "Pontuacao encontrada para a Area ...................: {$iAreaPont} <br>";
        $sMsgLog .= "Quantidade encontrada por Empregados ...............: {$oDadosQuantidadeSimulacao->q130_empregados} <br>"; 
        $sMsgLog .= "Pontuacao encontrada por quantidade de Empregados ..: {$iEmpregPont} <br>";
        $sMsgLog .= "Pontuacao encontrada por Zona ......................: {$iZonaPont} <br>";
        $sMsgLog .= "Pontuacao encontrada por Classe ....................: {$iClassePont} <br>";
        $sMsgLog .= "Pontuacao encontrada por pontuacao .................: {$iQuantidadeAtividade} <br>";
      break;     
    }
    
    $this->logCalculo($sMsgLog);
    return $iQuantidadeAtividade;
    
  }
  
  function buscaProporcionalidade($nValor, $sTipoProporcionalidade,$dDataInicio, $dDataFim, $iAnoCalculo, $dDataBaixa = null){
    
    $sSql = "select rnValorProporcional     as valor,
                    rsTipoProporcionalidade as descricao 
               from fc_issqn_proporcionalidade($nValor,
                                               '$sTipoProporcionalidade',
                                               '{$dDataInicio}',
                                               '{$dDataFim}',
                                               $iAnoCalculo,
                                               null)";
                                               
    $rsProporcionalidade = db_query($sSql);
    $oProporcionalidade  = db_utils::getCollectionByRecord($rsProporcionalidade); 
     
    return $oProporcionalidade;
    
  }

  function processaAtividadeTipoCalculo() {

    $oDaoTipoCalculo            = db_utils::getDao("tipcalc");
    
    $nMaiorValor                = 0;
         
		$nValorAlvara      = 0;
		$nMaiorValorAlvara = 0;
		
		$nValorISSQN      = 0;
		$nMaiorValorISSQN = 0;

    $lCalculoVariavel           = false;
    $lCalculoFixo               = false; 
    
    $aDadosTipoCalculoAtividade = array();
    $aTipoCalculoProcessado     = array();
    
    foreach( $this->getAtividadeCalculo() as $oAtividade) {
      
      $sMsgLog  = "-------------------------------------------------------------------------------------------------<br>";
      $sMsgLog .= "Processando Atividade {$oAtividade->atividade}<br>";
      $this->logCalculo($sMsgLog);
      
      $iQuantidadeAtividade = $this->buscaPontuacaoSimulacao($oAtividade->atividade, $this->iTipoQuantidade);
      if ($iQuantidadeAtividade == 0 && $this->iTipoQuantidade == 3) {
           
        $sMsgLog  = "[ Erro 6 ] - Não encontrada pontuação para a Atividade {$oAtividade->atividade}. <br>";
        $sMsgLog .= "Verifique o cadastro de pontuação das Classes, Areas, Zonas e Empregados";
        $this->logCalculo($sMsgLog);
        throw new Exception($sMsgLog);
           
      }      

      $sSqlTipoCalculo = $oDaoTipoCalculo->sql_dados_calculo($oAtividade->atividade, $this->getAnoInicioAtividade());
      $rsTipoCalculo = db_query($sSqlTipoCalculo);
      if (pg_num_rows($rsTipoCalculo) == 0) {
         throw new Exception("[ Erro 5 ] - Nenhum tipo de calculo encontrado para as atividades");
      }      
      
       for ( $iInd = 0; $iInd < pg_num_rows($rsTipoCalculo); $iInd ++) {

         $oDadosTipoCalculo = db_utils::fieldsMemory($rsTipoCalculo, $iInd);
         
         $sMsgLog = ">> Processando tipo de Calculo: {$oDadosTipoCalculo->tipocalculo} - {$oDadosTipoCalculo->tipocalculo_descricao} Quantidade: $iQuantidadeAtividade";
         $this->logCalculo($sMsgLog); 
         
         /*
          * Verificamos os calculos dos tipos de calculos encontrados
          * 
          * Caso seja encontrado um tipo de calculo variável e outro fixo, 
          * utilizaremos apenas o tipo de calculo variável.
          * 
          */
         if ($lCalculoVariavel == true && $this->iCalculaFixVar == 1) {
           if ($oDadosTipoCalculo->calculo == 2) {
             $sMsgLog = "Dois calculos encontrados (fixo/var), utilizando somente Variável para calculo";
             $this->logCalculo($sMsgLog);    
             continue;
           }
         }
         
         /*
          * 
          * Comparamos o ano do inicio da atividade com o ano do Calculo
          * Para sabermos quais os dados utilizar, o exercício atual ou do próximo
          *  
          */
         if ( $this->getAnoInicioAtividade() == $this->getAnoCalculo() ) {
           
          $iReceita           = $oDadosTipoCalculo->receitaexercicio;
          $iQuantidadeInicial = $oDadosTipoCalculo->quantidadeinicialexercicio;
          $iQuantidadeFinal   = $oDadosTipoCalculo->quantidadefinalexercicio;
          $nValorCalculo      = $oDadosTipoCalculo->valorexercicio;           
           
         } else {
           
          $iReceita           = $oDadosTipoCalculo->receitaproximoexercicio;
          $iQuantidadeInicial = $oDadosTipoCalculo->quantidadeinicialproximoexercicio;
          $iQuantidadeFinal   = $oDadosTipoCalculo->quantidadefinalproximoexercicio;
          $nValorCalculo      = $oDadosTipoCalculo->valorproximoexercicio;           
           
         }
         
         $sMsgLog = "Quantidade : $iQuantidadeAtividade Entre : {$iQuantidadeInicial} e {$iQuantidadeFinal}";
         $this->logCalculo($sMsgLog);
         
         /*
          * Verificamos a pontuação da atividade para saber se ela está no intervalo de pontuação/quantidade configurado para calculo
          */
         if ( $iQuantidadeAtividade >= $iQuantidadeInicial && $iQuantidadeAtividade <= $iQuantidadeFinal ) {

           /*
            * Verificamos se o ano de início da atividade é maior que a data de calculo
            */
           if ( $oDadosTipoCalculo->configuracaogeracao == 1 && $this->getAnoInicioAtividade() < $this->getAnoCalculo() ) {
             
             $sMsgLog = " Não irá Processar o tipo de Calculo ";
             $this->logCalculo($sMsgLog);
             
           } else {
             
             /*
              * Verificamos a quantidade a ser utilizada para o tipo de calculo
              * 
              * Se o parâmetro utilizaquantidadeatividade for 'f' o valor default é 1 do contrário utilizaremos 
              * a quantidade da atividade
              */
             $iQuantidadeCalculo = 1;
             if ( $oDadosTipoCalculo->utilizaquantidadeatividade == "f" ) {
               
               $sMsgLog = "Quantidade utilizada da tabela para calculo (utilizada default do sistema): {$iQuantidadeCalculo} ";
               $this->logCalculo($sMsgLog);
               
             } else {
               
                if ( $oAtividade->quantidade > 0) {
                  $iQuantidadeCalculo = $oAtividade->quantidade;
                }  
               
                $sMsgLog = "Quantidade encontrada para calculo (baseada na quantidade da atividade lancada): {$iQuantidadeCalculo}";
                $this->logCalculo($sMsgLog);
               
             }
             
             /*
              * Verificamos se utiliza multiplicador
              * 
              * Para simulação não é utilizado, por isso sempre será 1;
              */
              $iMultiplicador = 1;
              if ($oDadosTipoCalculo->utilizamultiplicador == "t") {
                $iMultiplicador = 1;
              }
             
             /*
              * Verificamos se será Integral ou não
              */
             $sMsgLog = "Integral (t = SIM - f = NÃO): {$oDadosTipoCalculo->integral}";
             $this->logCalculo($sMsgLog);
             
             /*
              * Verificamos se o cadcalc é variável ou não
              */
             if (empty($oDadosTipoCalculo->variavel)) {
               $sMsgLog = "[ Erro 7 ] - Não definido no cadastro do calculo {$oDadosTipoCalculo->calculo} se é Variável ou Não";
               $this->logCalculo($sMsgLog);
               throw new Exception($sMsgLog);
             }   

             /*
              * Verificamos qual a forma de calculo
              */
             if (empty($oDadosTipoCalculo->formacalculo)) {
               $sMsgLog = "[ Erro 8] - Não definido no cadastro de calculo {$oDadosTipoCalculo->calculo} a forma de calculo";
               $this->logCalculo($sMsgLog);
               throw new Exception($sMsgLog);  
             }
             $sMsgLog = "Forma de calculo (1 = atividade principal - 2 = atividade com maior valor - 3 = soma do valor das atividades): {$oDadosTipoCalculo->formacalculo}";
             $this->logCalculo($sMsgLog);
             
             /*
              * Verificamos se o calculo se trata de permanente ou provisório 
              */
             $iPercentualProvisorio = 1;
             if ($oDadosTipoCalculo->permanente == "t" && $oAtividade->permanente == "f") {
               
               $iPercentualProvisorio = $oDadosTipoCalculo->percentualprovisorio;
               $sMsgLog = "Provisorio: Vai acrescer {$iPercentualProvisorio} por centro no valor calculado";
                
             } else {
               $sMsgLog = "Nao provisorio <br>";               
             }
             $this->logCalculo($sMsgLog);
             
             /*
              * Verificamos se existe cadastro de vencimento válido
              */
             if ($oDadosTipoCalculo->codigovencimento == "") {
               $sMsgLog = "[ Erro 9 ] - Vencimento não encontrado no cadastro do tipo de calculo";
               $this->logCalculo($sMsgLog);
               throw new Exception($sMsgLog);
             }
             
             /*
              * Calculando valor do tipo de calculo
              */
             $nValor       = $nValorCalculo * $this->nValorBase * $iQuantidadeCalculo * $iMultiplicador * $iPercentualProvisorio * $this->nValorInflator;
             $nValorOrigem = $nValorCalculo * $iPercentualProvisorio * $this->nValorInflator; 
             
             /*
              * Calculando o valor do tipo de calculo de acordo com o tipo de proporcionalidade
              * Integral: sim ou não
              * 
              */
             if ($oDadosTipoCalculo->integral == 'f') {
               
               $sMsgLog = "Valor sem proporcionalidade : $nValor <br>";  
             
               if ($this->iAnoInicioAtividade == $this->iAnoCalculo ) {
                 
                 $dDtProporcionalidade = $this->iAnoCalculo."-12-31";
                 
                 $oProporcionalidade = $this->buscaProporcionalidade($nValor, 
                                                                     $oDadosTipoCalculo->tipoproporcionalidade, 
                                                                     $this->dDataInicioAtividade, 
                                                                     $dDtProporcionalidade, 
                                                                     $this->iAnoCalculo);
                 $nValorIntegral = $oProporcionalidade[0]->valor;
                 
                 $sMsgLog .= "Tipo de Proporcionalidade ..: {$oProporcionalidade[0]->descricao} <br>";
                 $sMsgLog .= "Valor ......................: {$oProporcionalidade[0]->valor} <br>";
                 
               }
                 
             } else {
               
               $sMsgLog = "Tipo de Proporcionalidade ..: Integral <br>";
               $sMsgLog .= "Valor ......................: $nValor <br>";
               $nValorIntegral = $nValor;
               
             }
             
             $this->logCalculo($sMsgLog);             
             
             /*
              * Apenas processamos um tipo de calculo, nunca um mesmo tipo de calculo será calculado mais de uma vez
              *  
              */
             if ($oDadosTipoCalculo->variavel == "f") {
               
               switch ($oDadosTipoCalculo->formacalculo) {
          
                 /*
                  * Atividade Principal
                  */
                 case 1:
            
                   if ($oAtividade->principal == "t") {
                     
                     if ( !in_array($oDadosTipoCalculo->calculo."-".$oDadosTipoCalculo->formacalculo."-".$oDadosTipoCalculo->variavel, $aTipoCalculoProcessado) ) {
                      
                       $oTipoCalculo = new stdClass();
                       $oTipoCalculo->iAtividade                     = $oAtividade->atividade;
                       $oTipoCalculo->sPrincipal                     = $oAtividade->principal;
                       $oTipoCalculo->iTipoCalculo                   = $oDadosTipoCalculo->tipocalculo;
                       $oTipoCalculo->sTipoCalculoDescricao          = $oDadosTipoCalculo->tipocalculo_descricao;
                       $oTipoCalculo->sTipoCalculoAbreviacao         = $oDadosTipoCalculo->tipocalculo_abreviacao;
                       $oTipoCalculo->iCalculo                       = $oDadosTipoCalculo->calculo;
                       $oTipoCalculo->sCalculoDescricao              = $oDadosTipoCalculo->calculo_descricao;
                       $oTipoCalculo->iReceitaExercicio              = $oDadosTipoCalculo->receitaexercicio;
                       $oTipoCalculo->iReceitaProximoExercicio       = $oDadosTipoCalculo->receitaproximoexercicio;
                       $oTipoCalculo->iQuantidade                     = $iQuantidadeAtividade;
                       $oTipoCalculo->iQuantidadeInicialExercicio    = $oDadosTipoCalculo->quantidadeinicialexercicio;
                       $oTipoCalculo->iQuantidadeFinalExercicio      = $oDadosTipoCalculo->quantidadefinalexercicio;
                       $oTipoCalculo->iQuantidadeCalculo             = $iQuantidadeCalculo;
                       $oTipoCalculo->iMultiplicador                 = 1;
                       $oTipoCalculo->iFormaCalculo                  = $oDadosTipoCalculo->formacalculo;
                       $oTipoCalculo->iCodigoVencimento              = $oDadosTipoCalculo->codigovencimento;
                       $oTipoCalculo->iIntegral                      = $oDadosTipoCalculo->integral;
                       $oTipoCalculo->sTipoProporcionalidade         = $oDadosTipoCalculo->tipoproporcionalidade;
                       $oTipoCalculo->nValor                         = $nValor;  
                       $oTipoCalculo->nValorOrigem                   = $nValorOrigem;
                       $oTipoCalculo->nValorIntegral                 = $nValorIntegral;
                       $oTipoCalculo->sVariavel                      = $oDadosTipoCalculo->variavel;
                       $oTipoCalculo->iConfiguracaoGeracao           = $oDadosTipoCalculo->configuracaogeracao;
                       
                       $aDadosTipoCalculoAtividade[] = $oTipoCalculo;
                       $aTipoCalculoProcessado[]     = $oDadosTipoCalculo->calculo."-".$oDadosTipoCalculo->formacalculo."-".$oDadosTipoCalculo->variavel; 

                       break;
                       
                     }
                                        
                   }
                   
                 break;
                 
                 /*
                  * Atividade de Maior Valor
                  */
                 case 2:

									 /**
										* Cria variaveis dinamicamente pelo tipo de calculo
									  * para comparar os valores de cada tipo
									  */	 
									 if ($oDadosTipoCalculo->calculo == 1) {

									   $sTipoValor      = 'nValorAlvara'; 
										 $sTipoMaiorValor = 'nMaiorValorAlvara';

									 } else if ($oDadosTipoCalculo->calculo == 2 || $oDadosTipoCalculo->calculo == 3) {

										 $sTipoValor      = 'nValorISSQN';
										 $sTipoMaiorValor = 'nMaiorValorISSQN';

									 } else {

										 $sTipoValor      = 'nValor';
										 $sTipoMaiorValor = 'nMaiorValor';
									 }

									 $$sTipoValor = $nValor;

                   if ($$sTipoValor > $$sTipoMaiorValor ) {

                     if ( !in_array($oDadosTipoCalculo->calculo."-".$oDadosTipoCalculo->formacalculo."-".$oDadosTipoCalculo->variavel, $aTipoCalculoProcessado) ) {
                   
                       $oTipoCalculo = new stdClass();
                       $oTipoCalculo->iAtividade                     = $oAtividade->atividade;
                       $oTipoCalculo->sPrincipal                     = $oAtividade->principal;
                       $oTipoCalculo->iTipoCalculo                   = $oDadosTipoCalculo->tipocalculo;
                       $oTipoCalculo->sTipoCalculoDescricao          = $oDadosTipoCalculo->tipocalculo_descricao;
                       $oTipoCalculo->sTipoCalculoAbreviacao         = $oDadosTipoCalculo->tipocalculo_abreviacao;                       
                       $oTipoCalculo->iCalculo                       = $oDadosTipoCalculo->calculo;
                       $oTipoCalculo->sCalculoDescricao              = $oDadosTipoCalculo->calculo_descricao;
                       $oTipoCalculo->iReceitaExercicio              = $oDadosTipoCalculo->receitaexercicio;
                       $oTipoCalculo->iReceitaProximoExercicio       = $oDadosTipoCalculo->receitaproximoexercicio;
                       $oTipoCalculo->iQuantidade                    = $iQuantidadeAtividade;
                       $oTipoCalculo->iQuantidadeInicialExercicio    = $oDadosTipoCalculo->quantidadeinicialexercicio;
                       $oTipoCalculo->iQuantidadeFinalExercicio      = $oDadosTipoCalculo->quantidadefinalexercicio;
                       $oTipoCalculo->iQuantidadeCalculo             = $iQuantidadeCalculo;
                       $oTipoCalculo->iMultiplicador                 = 1;
                       $oTipoCalculo->iFormaCalculo                  = $oDadosTipoCalculo->formacalculo;
                       $oTipoCalculo->iCodigoVencimento              = $oDadosTipoCalculo->codigovencimento;
                       $oTipoCalculo->iIntegral                      = $oDadosTipoCalculo->integral;
                       $oTipoCalculo->sTipoProporcionalidade         = $oDadosTipoCalculo->tipoproporcionalidade;
                       $oTipoCalculo->nValor                         = $nValor;  
                       $oTipoCalculo->nValorOrigem                   = $nValorOrigem;
                       $oTipoCalculo->nValorIntegral                 = $nValorIntegral;
                       $oTipoCalculo->sVariavel                      = $oDadosTipoCalculo->variavel;
                       $oTipoCalculo->iConfiguracaoGeracao           = $oDadosTipoCalculo->configuracaogeracao;
                       
                       $aDadosTipoCalculoAtividade[] = $oTipoCalculo;
                       $aTipoCalculoProcessado[]     = $oDadosTipoCalculo->calculo."-".$oDadosTipoCalculo->formacalculo."-".$oDadosTipoCalculo->variavel; 

                       $$sTipoMaiorValor = $nValor;
                       
                     }
                     
                   }
                   
                 break;
                 

                /*
                 * Soma das Atividades
                 */
                 case 3:
                   
                   if ( !in_array($oDadosTipoCalculo->calculo."-".$oDadosTipoCalculo->formacalculo."-".$oDadosTipoCalculo->variavel, $aTipoCalculoProcessado) ) {

                     $oTipoCalculo = new stdClass();
                     $oTipoCalculo->iAtividade                     = $oAtividade->atividade;
                     $oTipoCalculo->sPrincipal                     = $oAtividade->principal;
                     $oTipoCalculo->iTipoCalculo                   = $oDadosTipoCalculo->tipocalculo;
                     $oTipoCalculo->sTipoCalculoDescricao          = $oDadosTipoCalculo->tipocalculo_descricao;
                     $oTipoCalculo->sTipoCalculoAbreviacao         = $oDadosTipoCalculo->tipocalculo_abreviacao;                     
                     $oTipoCalculo->iCalculo                       = $oDadosTipoCalculo->calculo;
                     $oTipoCalculo->sCalculoDescricao              = $oDadosTipoCalculo->calculo_descricao;
                     $oTipoCalculo->iReceitaExercicio              = $oDadosTipoCalculo->receitaexercicio;
                     $oTipoCalculo->iReceitaProximoExercicio       = $oDadosTipoCalculo->receitaproximoexercicio;
                     $oTipoCalculo->iQuantidade                    = $iQuantidadeAtividade;
                     $oTipoCalculo->iQuantidadeInicialExercicio    = $oDadosTipoCalculo->quantidadeinicialexercicio;
                     $oTipoCalculo->iQuantidadeFinalExercicio      = $oDadosTipoCalculo->quantidadefinalexercicio;
                     $oTipoCalculo->iQuantidadeCalculo             = $iQuantidadeCalculo;
                     $oTipoCalculo->iMultiplicador                 = 1;
                     $oTipoCalculo->iFormaCalculo                  = $oDadosTipoCalculo->formacalculo;
                     $oTipoCalculo->iCodigoVencimento              = $oDadosTipoCalculo->codigovencimento;
                     $oTipoCalculo->iIntegral                      = $oDadosTipoCalculo->integral;
                     $oTipoCalculo->sTipoProporcionalidade         = $oDadosTipoCalculo->tipoproporcionalidade;
                     $oTipoCalculo->nValor                         += $nValor;  
                     $oTipoCalculo->nValorOrigem                   += $nValorOrigem;
                     $oTipoCalculo->nValorIntegral                 += $nValorIntegral;
                     $oTipoCalculo->sVariavel                      = $oDadosTipoCalculo->variavel;
                     $oTipoCalculo->iConfiguracaoGeracao           = $oDadosTipoCalculo->configuracaogeracao;
                     
                     $aDadosTipoCalculoAtividade[] = $oTipoCalculo;
                     $aTipoCalculoProcessado[]     = $oDadosTipoCalculo->calculo."-".$oDadosTipoCalculo->formacalculo."-".$oDadosTipoCalculo->variavel;
                      
                     break;
                     
                   }
                 
                 break;
                 
               }    
               
             } else {
               
               /*
                * Variável
                */
                if ( !in_array($oDadosTipoCalculo->calculo."-".$oDadosTipoCalculo->formacalculo."-".$oDadosTipoCalculo->variavel, $aTipoCalculoProcessado) ) {
                
                  $oTipoCalculo = new stdClass();
                  $oTipoCalculo->iAtividade                     = $oAtividade->atividade;
                  $oTipoCalculo->sPrincipal                     = $oAtividade->principal;
                  $oTipoCalculo->iTipoCalculo                   = $oDadosTipoCalculo->tipocalculo;
                  $oTipoCalculo->sTipoCalculoDescricao          = $oDadosTipoCalculo->tipocalculo_descricao;
                  $oTipoCalculo->sTipoCalculoAbreviacao         = $oDadosTipoCalculo->tipocalculo_abreviacao;                  
                  $oTipoCalculo->iCalculo                       = $oDadosTipoCalculo->calculo; 
                  $oTipoCalculo->sCalculoDescricao              = $oDadosTipoCalculo->calculo_descricao;
                  $oTipoCalculo->iReceitaExercicio              = $oDadosTipoCalculo->receitaexercicio;
                  $oTipoCalculo->iReceitaProximoExercicio       = $oDadosTipoCalculo->receitaproximoexercicio;
                  $oTipoCalculo->iQuantidade                    = $iQuantidadeAtividade;
                  $oTipoCalculo->iQuantidadeInicialExercicio    = $oDadosTipoCalculo->quantidadeinicialexercicio;
                  $oTipoCalculo->iQuantidadeFinalExercicio      = $oDadosTipoCalculo->quantidadefinalexercicio;
                  $oTipoCalculo->iQuantidadeCalculo             = $iQuantidadeCalculo;
                  $oTipoCalculo->iMultiplicador                 = 1;
                  $oTipoCalculo->iFormaCalculo                  = $oDadosTipoCalculo->formacalculo;
                  $oTipoCalculo->iCodigoVencimento              = $oDadosTipoCalculo->codigovencimento;
                  $oTipoCalculo->iIntegral                      = $oDadosTipoCalculo->integral;
                  $oTipoCalculo->sTipoProporcionalidade         = $oDadosTipoCalculo->tipoproporcionalidade;
                  $oTipoCalculo->nValor                         = 0;  
                  $oTipoCalculo->nValorOrigem                   = 0;
                  $oTipoCalculo->nValorIntegral                 = 0;
                  $oTipoCalculo->sVariavel                      = $oDadosTipoCalculo->variavel;
                  $oTipoCalculo->iConfiguracaoGeracao           = $oDadosTipoCalculo->configuracaogeracao;
                
                  $aDadosTipoCalculoAtividade[] = $oTipoCalculo;
                  $aTipoCalculoProcessado[]      = $oDadosTipoCalculo->calculo."-".$oDadosTipoCalculo->formacalculo."-".$oDadosTipoCalculo->variavel;
                   
                }               
               
             }
             
           }
             
         } else {
           
          $sMsgLog  = " Não irá Processar o tipo de Calculo! <br>"; 
          $sMsgLog .= " Quantidades da Atividade estão fora do intervalo configurado para o tipo de calculo";
          $this->logCalculo($sMsgLog); 
           
         }

       }
      
    }
    return $aDadosTipoCalculoAtividade;
    
  }
  
  function processaSimulacao() {

    $aRetorno          = array();
    $oDaoCadVenc       = db_utils::getDao("cadvenc");
      
    $oDadosTipoCalculo = $this->processaAtividadeTipoCalculo();
    
    foreach ($oDadosTipoCalculo as $oDadosCalculo) {
      
      $sMsgLog  = "----------------------------------------------------------------------------------------------<br>";
      $sMsgLog .= "Processando Calculo ...: $oDadosCalculo->iCalculo - $oDadosCalculo->sCalculoDescricao <br><br>";
      $sMsgLog .= "Tipo de Calculo .......: $oDadosCalculo->iTipoCalculo - $oDadosCalculo->sTipoCalculoDescricao <br>";
      $sMsgLog .= "Vencimento ............: $oDadosCalculo->iCodigoVencimento <br>";
      $this->logCalculo($sMsgLog);
      
      
      //Processamos os calculos de variável
      if ($oDadosCalculo->sVariavel == 't') {
        
        $sMsgLog = "Processando calculo de ISSQN Variável <br>";
        $this->logCalculo($sMsgLog);
        
        $sSqlVencimentos        = $oDaoCadVenc->sql_query($oDadosCalculo->iCodigoVencimento, null, "*", "q82_parc asc");
        $rsVencimentos          = $oDaoCadVenc->sql_record($sSqlVencimentos);
        $iQuantidadeVencimentos = $oDaoCadVenc->numrows; 
        if ( $iQuantidadeVencimentos == 0) {
          
          $sMsgLog = "[ Erro 10] - Erro ao buscar dados dos vemcimentos";
          $this->logCalculo($sMsgLog);
          throw new Exception($sMsgLog);
          
        }
        
        for ($iInd = 0; $iInd < $iQuantidadeVencimentos; $iInd++) {
          
          $oDadosVencimento = db_utils::fieldsMemory($rsVencimentos, $iInd);

          if (substr(str_replace("-","",$oDadosVencimento->q82_venc),0,6) > substr(str_replace("-","",$this->getDataInicioAtividade()),0,6)) {
            
            $nValorParcela = $oDadosCalculo->nValorOrigem / $this->nValorInflator;
            $iParcela      = $oDadosVencimento->q82_parc;
            $dVencimento   = $oDadosVencimento->q82_venc;
            
            $sMsgLog = "Parcela : {$iParcela} Vencimento : {$dVencimento} Valor: {$nValorParcela}<br>";
            $this->logCalculo($sMsgLog);
            
            $oRetornoCalculo = new stdClass;
            $oRetornoCalculo->iTipoCalculo         = $oDadosCalculo->iTipoCalculo;            
            $oRetornoCalculo->sDescricaoCalculo    = $oDadosCalculo->sCalculoDescricao;
            $oRetornoCalculo->iParcela             = $iParcela;
            $oRetornoCalculo->dVencimento          = $dVencimento;
            $oRetornoCalculo->nValor               = $nValorParcela;  
            $aRetorno[] = $oRetornoCalculo;              
            
            
          } else {
            
            $sMsgLog  = "Parcela : {$oDadosVencimento->q82_parc} Vencimento : {$oDadosVencimento->q82_venc} ";
            $sMsgLog .= "não calculada pois o ano/mes de vencimento é menor ou igual ao ano/mes do início da ativiade {$this->getDataInicioAtividade()} <br>";
            $this->logCalculo($sMsgLog);
            
          }
          
        }
        
        
        $sMsgLog = "FIM DO PROCESSAMENTO DO ISSQN VARIÁVEL";
        $this->logCalculo($sMsgLog);
        
      } else {
        
        $sMsgLog = "Processando calculo de ISSQN NÃO VARIÁVEL<br>";
        $this->logCalculo($sMsgLog);
        

        $bProcessaParcelaVencida        = false;
        $bUltimoVencimento              = false;
        $iQuantidadeVencimentoProcessar = 0;    
        $iVencimentosProcessados        = 0;
        $iDiasSomados                   = 0;
        
        $dUltimoDiaAno                  = $this->getAnoCalculo()."-12-31";
        $sMktimeUltimoDiaAno            = mktime(0,0,0,"12","31",$this->getAnoCalculo());      
                                                                                  
                                                                                                                                
        $sWhere  = " cadvencdesc.q92_codigo = {$oDadosCalculo->iCodigoVencimento}                                               ";
        $sWhere .= " and ( case                                                                                                 ";
        $sWhere .= "         when cadvencdesc.q92_formacalcparcvenc = 1                                                         ";
        $sWhere .= "           then                                                                                             ";
        $sWhere .= "             case                                                                                           ";
        $sWhere .= "               when q82_venc >= '{$this->getDataInicioAtividade()}'                                         ";
        $sWhere .= "                 then true                                                                                  ";
        $sWhere .= "               else false                                                                                   ";
        $sWhere .= "             end                                                                                            ";
        $sWhere .= "         when cadvencdesc.q92_formacalcparcvenc = 3                                                         ";
        $sWhere .= "           then q82_venc >= '{$this->getDataCalculo()}' and q82_venc >= '{$this->getDataInicioAtividade()}' ";
        $sWhere .= "         else                                                                                               ";
        $sWhere .= "           case                                                                                             ";
        $sWhere .= "             when cadvenc.q82_calculaparcvenc is true                                                       ";
        $sWhere .= "               then true                                                                                    ";
        $sWhere .= "             else                                                                                           ";
        $sWhere .= "               q82_venc >= '{$this->getDataCalculo()}' and q82_venc >= '{$this->getDataInicioAtividade()}'  ";
        $sWhere .= "           end                                                                                              ";
        $sWhere .= "       end )                                                                                                ";
        $sSqlVerificaVencimentosProcessar = $oDaoCadVenc->sql_query(null, null, "count(*) as qtd", null, $sWhere);
        $rsVerificaVencimentosProcessar   = $oDaoCadVenc->sql_record($sSqlVerificaVencimentosProcessar);
        $iQuantidadeVencimentoProcessar   = db_utils::fieldsMemory($rsVerificaVencimentosProcessar,0)->qtd;
        

        $sCampos = "max(cadvenc.q82_venc)                     as maiorvencimento,
                    coalesce(max(cadvencdesc.q92_diasvcto),0) as dias,
                    count(*)                                  as total";
        $sSqlComplementoVencimento = $oDaoCadVenc->sql_query($oDadosCalculo->iCodigoVencimento, null, $sCampos, null);
        $rsComplementoVencimento   = $oDaoCadVenc->sql_record($sSqlComplementoVencimento);
        $dMaiorVencimento          = db_utils::fieldsMemory($rsComplementoVencimento,0)->maiorvencimento;
        $iDiasParaVencimento       = db_utils::fieldsMemory($rsComplementoVencimento,0)->dias;           
        $iTotalVencimentos         = db_utils::fieldsMemory($rsComplementoVencimento,0)->total;
              
        $sSqlVencimentos        = $oDaoCadVenc->sql_query($oDadosCalculo->iCodigoVencimento, null, "*", "q82_parc asc");
        $rsVencimentos          = $oDaoCadVenc->sql_record($sSqlVencimentos);
        $iQuantidadeVencimentos = $oDaoCadVenc->numrows; 
        if ( $iQuantidadeVencimentos == 0) {
          
          $sMsgLog = "[ Erro 10] - Erro ao buscar dados dos vemcimentos";
          $this->logCalculo($sMsgLog);
          throw new Exception($sMsgLog);
          
        }          
        
        for ($iInd = 0; $iInd < $iQuantidadeVencimentos; $iInd++) {
          
          $oDadosVencimento = db_utils::fieldsMemory($rsVencimentos, $iInd);
          
          $sMsgLog = "Processando Vencimento {$oDadosVencimento->q82_venc} Parcela: {$oDadosVencimento->q82_parc} <br>";
          $this->logCalculo($sMsgLog);
          
          //Guardar o vencimento atual do cadvenc
          $dVencimento = $oDadosVencimento->q82_venc;
          if ($dVencimento == "") {
            $dVencimento = $this->getDataCalculo();
          }            
          
          $bProcessaParcela = true;
          
          if ($iQuantidadeVencimentoProcessar == 0) {

            if ($iDiasParaVencimento > 0) {
              $aDataCalculo = explode("-", $this->getDataCalculo());
              $dVencimento  = date("Y-m-d", mktime(0,0,0,$aDataCalculo[1], $aDataCalculo[2]+$iDiasParaVencimento, $aDataCalculo[0]));
            } else {
              $dVencimento = $dUltimoDiaAno;
            }
            
            $sMsgLog = "Trocou o vencimento para : {$dVencimento}";
            $this->logCalculo($sMsgLog);
            
          }
          
          /*
           * Verificamos se será gerada parcela vencida
           */
          if ($oDadosVencimento->q92_formacalcparcvenc == 1) {
            
            $bProcessaParcelaVencida = true; 
            
          } else if ($oDadosVencimento->q92_formacalcparcvenc == 3) {
            
            if ($oDadosVencimento->q82_venc >= $this->getDataCalculo() && $oDadosVencimento->q82_venc <= $dUltimoDiaAno) {
              $bProcessaParcelaVencida = true;
            }
            
          } else {
            
            if ($oDadosVencimento->q82_calculaparcvenc == "t") { 
              $bProcessaParcelaVencida = true; 
            } else if($oDadosVencimento->q82_venc >= $this->getDataCalculo() && $oDadosVencimento->q82_venc <= $dUltimoDiaAno) {
              $bProcessaParcelaVencida = true;
            }
            
          } 

          $aVencimento               = explode("-", $dVencimento);
          $sMktimeVencimento         = mktime(0,0,0,$aVencimento[1],$aVencimento[2],$aVencimento[0]); 
                                     
          $aMaiorVencimento          = explode("-", $dMaiorVencimento);
          $sMktimeMaiorVencimento    = mktime(0,0,0,$aMaiorVencimento[1],$aMaiorVencimento[2],$aMaiorVencimento[0]);

          $aDataInicioAtividade      = explode("-", $this->getDataInicioAtividade());
          $sMktimeDataInicioAtividade = mktime(0,0,0,$aDataInicioAtividade[1],$aDataInicioAtividade[2],$aDataInicioAtividade[0]);
          
          $aDataCalculo              = explode("-", $this->getDataCalculo());
          $sMktimeDataCalculo        = mktime(0,0,0,$aDataCalculo[1],$aDataCalculo[2],$aDataCalculo[0]);
          
          if ($sMktimeVencimento > $sMktimeMaiorVencimento ) {

            $sMsgLog = "Vencimento do cadastro de vencimentos maior que o maximo vencimento, pasando bUltVenc para true";
            $this->logCalculo($sMsgLog);
            $bUltimoVencimento = true;
            
          }

          
          if ($this->getAnoInicioAtividade() <> $this->getAnoCalculo()) {
            
            $sMsgLog = "Ano de inicio diferente do atual";
            $this->logCalculo($sMsgLog);
            
            $bProcessaParcela = true;
            
          } else {
            
            
            if ($sMktimeVencimento >= $sMktimeDataInicioAtividade || $dVencimento == "" || $iQuantidadeVencimentoProcessar == 0 || $bProcessaParcelaVencida == true) {
              $bProcessaParcela = true;
            } else {
              $bProcessaParcela = false;
            }
            
            
          }
          
          if ( $sMktimeDataCalculo > $sMktimeVencimento && $bProcessaParcelaVencida == false) {
            
            $sMsgLog = "Inicio maior que data de vencimento e processar parcelas vencidas NAO";
            $this->logCalculo($sMsgLog);
            $bProcessaParcela = false;
             
          }
          

          if ( $sMktimeDataInicioAtividade > $sMktimeVencimento && $bUltimoVencimento == false && $iTotalVencimentos <> $iVencimentosProcessados ) {
            $bProcessaParcela= false;
          }
          
          if ($bProcessaParcela == true) {
            
            $iVencimentosProcessados++;
            if ( $iQuantidadeVencimentoProcessar == 0) {
              $nPercentualParcela = 100;
            } else {
              
              if ($oDadosCalculo->sTipoProporcionalidade == "D") {
                
                $dVencimentoCalculado = $dVencimento;
                if ($bUltimoVencimento == true || $sMktimeVencimento == $sMktimeMaiorVencimento) {
                  
                  $dVencimentoCalculado   = $dUltimoDiaAno;
                  $dMaiorVencimento       = $dVencimentoCalculado;
                  $aMaiorVencimento       = explode("-",$dMaiorVencimento);
                  $sMktimeMaiorVencimento = mktime(0,0,0,$aMaiorVencimento[1],$aMaiorVencimento[2],$aMaiorVencimento[0]);
                   
                }
                
                if ($this->getAnoInicioAtividade() < $this->getAnoCalculo()) { 
                  $dInicioAtividadeCalculado = $this->getAnoCalculo()."-01-01";
                } else {
                  $dInicioAtividadeCalculado = $this->getDataInicioAtividade();
                }
                
                $aInicioAtividadeCalculado       = explode("-", $dInicioAtividadeCalculado);
                $sMktimeInicioAtividadeCalculado = mktime(0,0,0,$aInicioAtividadeCalculado[1],$aInicioAtividadeCalculado[2],$aInicioAtividadeCalculado[0]);
                
                $iDiasInicio     = date("d", ($sMktimeUltimoDiaAno - $sMktimeDataInicioAtividade)) + 1; 
                $iDiasVencimento = (date("d", ($sMktimeUltimoDiaAno - $sMktimeInicioAtividadeCalculado))+1) - $iDiasSomados;
                $iDiasSomados   += $iDiasVencimento;
                
                $nPercentualParcela = round((100/$iDiasInicio) * $iDiasVencimento,2);
                
              } else {
                $nPercentualParcela = round(100/$iQuantidadeVencimentos,2);
              }
              
              
            }
            
            
            if($dVencimento == "" and $this->getAnoInicioAtividade() < $this->getAnoCalculo()) {

               $dVencimento = $dUltimoDiaAno;

               if($iDiasParaVencimento > 0 && $this->getAnoInicioAtividade() == $this->getAnoCalculo()) {

                 $dVencimento = date("Y-m-d",mktime(0,0,0, $aDataCalculo[1], $aDataCalculo[2]+$iDiasParaVencimento, $aDataCalculo[0]));

               }
            }
            
            $nValorParcela = round( ( ( $oDadosCalculo->nValorIntegral * $nPercentualParcela ) / 100 ) ,2 );
            
            $oRetornoCalculo = new stdClass;
            $oRetornoCalculo->iTipoCalculo         = $oDadosCalculo->iTipoCalculo; 
            $oRetornoCalculo->sDescricaoCalculo    = $oDadosCalculo->sCalculoDescricao;
            $oRetornoCalculo->iParcela             = $iVencimentosProcessados;
            $oRetornoCalculo->dVencimento          = $dVencimento;
            $oRetornoCalculo->nValor               = $nValorParcela;  
            $aRetorno[]                            = $oRetornoCalculo;
               
            
            if ($nPercentualParcela == 100) {
              break;
            }
          
          } 

        }
      
      }
      
    }  
    
    return $aRetorno;
    
  }  
  
  function logCalculo($sMsgLog) {
    
    $this->sLogCalculo .= $sMsgLog;
    if ($this->lDebug == true) {
     echo str_replace("<br>", "\n",$sMsgLog)."\n";
    }
    
  }
   
}