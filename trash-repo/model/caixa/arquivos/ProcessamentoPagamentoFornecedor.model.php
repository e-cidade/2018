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


class ProcessamentoPagamentoFornecedor {
  
  /**
   * Caminho em que o arquivo se encontra armazenado
   * @var string
   */
  protected $sCaminhoArquivo;
  
  /**
   * Codigo do banco que se refere o arquivo
   * @var integer
   */
  protected $iCodigoBancoProcessar;
  
  
  /**
   * Dados com os registros encontrados no arquivo
   * @var stdClass
   */
  protected $oRegistroArquivo;
  
  /**
   * Propriedade setada quando o arquivo j� foi processado em outro momento
   * @var boolean
   */
  protected $lArquivoProcessado = false;
  
  /**
   * Movimentos que foram descartados pelo processamento do arquivo caso ja tenham
   * sido processados em outro momento
   * @var array
   */
  protected $aMovimentosDescartados = array();
  
  /**  
   * Construtor do objeto
   * @param string $sArquivo [caminho do arquivo que foi feito upload]
   */
  public function __construct($sArquivo) {

    $this->sCaminhoArquivo = $sArquivo;
    $this->validarArquivo();

    $oPagamentoFornecedor   = PagamentoFornecedorFactory::getInstance($this->iCodigoBancoProcessar);
    $oPagamentoFornecedor->setArquivo($this->sCaminhoArquivo)->processarArquivoRetorno();
    $this->oRegistroArquivo = $oPagamentoFornecedor->getDados();
    return true;
  }
  
  /**
   * Validamos se a movimenta��o ja foi recebida
   * @return boolean
   */
  public function possuiRetornoProcessado() {
    
    $aRetornosParaProcessar = array();
    $aRetornosProcessados   = array(5, 2, 104, 105, 106);
    foreach ($this->oRegistroArquivo->registros as $oRegistro) {
      
      $oRegistro->codigo_movimento    = (int)$oRegistro->codigo_movimento;
      $sWhereCodigoGeracao            = "     empagedadosret.e75_codgera   = empageconfgera.e90_codgera ";
      $sWhereCodigoGeracao           .= " and empagedadosretmov.e76_codmov = {$oRegistro->codigo_movimento} ";
      $sWhereCodigoGeracao           .= " and empagedadosret.e75_ativo is true ";
      $sWhereCodigoGeracao           .= " and empageconfgera.e90_cancelado is false ";
      $oDaoEmpAgeDadosRet             = db_utils::getDao('empagedadosret');
      
      $sSqlBuscaMovimentosProcessados = $oDaoEmpAgeDadosRet->sql_query_retmov(null, "distinct e75_codret, e02_errobanco", null, $sWhereCodigoGeracao);
      $rsBuscaMovimentosProcessados   = $oDaoEmpAgeDadosRet->sql_record($sSqlBuscaMovimentosProcessados);
      
      if ($oDaoEmpAgeDadosRet->numrows > 0) {

        $lProcessaMovimento = false;
        for ($iRowOcorrencia = 0; $iRowOcorrencia < $oDaoEmpAgeDadosRet->numrows; $iRowOcorrencia++) {
          
          $oDadoOcorrenciaExistente = db_utils::fieldsMemory($rsBuscaMovimentosProcessados, $iRowOcorrencia);
          $iCodigoRetornoProcessado  = $oDadoOcorrenciaExistente->e75_codret;
          $oRegistro->iCodigoRetorno = $iCodigoRetornoProcessado;
          
          if ($oDadoOcorrenciaExistente->e02_errobanco == 35) {
            $lProcessaMovimento = true;
          }
          
          if (in_array($oDadoOcorrenciaExistente->e02_errobanco, $aRetornosProcessados)) {
            $lProcessaMovimento = false;
          }
        }
        
        if ($lProcessaMovimento) {
          $aRetornosParaProcessar[] = $oRegistro;
        } else {
          
          $oRegistro->iCodigoRetorno      = $iCodigoRetornoProcessado;
          $this->aMovimentosDescartados[] = $oRegistro;
        }
        
      } else {
        $aRetornosParaProcessar[] = $oRegistro;
      }
    }
    $this->oRegistroArquivo->registros = $aRetornosParaProcessar;
    if (count($this->oRegistroArquivo->registros) == 0) {
      return $iCodigoRetornoProcessado;
    }
    return false;
  }
  
  
  /**
   * M�todo que valida se o arquivo um arquivo de retorno v�lido
   * @throws Exception
   */
  protected function validarArquivo() {
     
   if (!is_file($this->sCaminhoArquivo)) {
      throw new Exception("Arquivo {$this->sCaminhoArquivo} n�o � um arquivo v�lido.");
    }
    
    if (!is_readable($this->sCaminhoArquivo)) {
      throw new Exception("Arquivo {$this->sCaminhoArquivo} sem permiss�o de leitura.");
    }
    
    /**
     * Valida se o arquivo � um arquivo de banco.
     * para a valida��o, apenas devemos validar a posi��o 142-1 do arquivo (essa posi��o dever� ser igual a 2)
     */
    $aLinhasArquivo = file($this->sCaminhoArquivo);
    if (count($aLinhasArquivo) == 0) {
      throw new Exception("Arquivo {$this->sCaminhoArquivo} est� vazio.");
    }
    $sLinhaHeader = $aLinhasArquivo[0];
    if (substr($sLinhaHeader, 142, 1) != '2') {
      throw new Exception("Arquivo {$this->sCaminhoArquivo} n�o � um arquivo de retorno v�lido.");
    }
    $this->iCodigoBancoProcessar = substr($sLinhaHeader, 0, 3);
    unset($aLinhasArquivo);
    unset($sLinhaHeader);
    return true;
  }
  
  /**
   * Retorna os codigos de Arquivo envolvidos no retorno do banco.
   * @param array $aMovimentos Codigos dos movimentos
   */
  protected function getCodigoArquivoPorMovimento($aMovimentos) {
    
    $aCodigoArquivo      = array();
    $oDaoEmpagecConfGera = db_utils::getDao("empageconfgera");
    $sListaMovimentos    = implode(",", $aMovimentos);
    $sCampos             = "distinct e90_codgera ";
    $sWhere              = "e90_codmov in ({$sListaMovimentos}) and e90_cancelado is false";
    $sSqlDadosArquivo    = $oDaoEmpagecConfGera->sql_query_file(null, null, $sCampos, null, $sWhere);
    $rsEmpagecConfGera   = $oDaoEmpagecConfGera->sql_record($sSqlDadosArquivo);
    if ($oDaoEmpagecConfGera->numrows > 0) {
      
      for($i = 0; $i < $oDaoEmpagecConfGera->numrows; $i++) {
        
        $aCodigoArquivo[] = db_utils::fieldsMemory($rsEmpagecConfGera, $i)->e90_codgera;
      }
    }
    return $aCodigoArquivo;    
  }
  
  /**
   * Busca os movimentos que foram enviados para o banco.
   * @param array $aCodigoGeracao (array contendo codgera)
   * @return array indexado pelo codigo da geracao e seus movimentos
   */
  protected function getMovimentosPorGeracao($aCodigosGeracao) {
    
    $aRetornoMovimentos = array();
    foreach ($aCodigosGeracao as $iCodigoGeracao) {
      
      $oDaoEmpAgeConfGera         = db_utils::getDao('empageconfgera');
      $sSqlBuscaMovimentosGeracao = $oDaoEmpAgeConfGera->sql_query_movimentacoes_banco(null, $iCodigoGeracao, 'e90_codmov');
      $rsBuscaMovimentosGeracao   = $oDaoEmpAgeConfGera->sql_record($sSqlBuscaMovimentosGeracao);
      $iTotalMovimentos           = $oDaoEmpAgeConfGera->numrows;
      $aMovimentos = array();
      if ($iTotalMovimentos > 0) {
        
        for ($iRowMovimento = 0; $iRowMovimento < $iTotalMovimentos; $iRowMovimento++) {
          
          $iCodigoMovimento = db_utils::fieldsMemory($rsBuscaMovimentosGeracao, $iRowMovimento)->e90_codmov;
          
          $oDaoMovimentoOcorrencia    = db_utils::getDao('empagedadosretmovocorrencia');
          $sWhereMovimentoOcorrencia  = "    e75_codgera   = {$iCodigoGeracao}   ";
          $sWhereMovimentoOcorrencia .= "and e76_codmov    = {$iCodigoMovimento} ";
          $sSqlBuscaMovimentoOcorrencia  = $oDaoMovimentoOcorrencia->sql_query(null,"e02_errobanco", null, $sWhereMovimentoOcorrencia);
          $rsBuscaMovimentoOcorrencia    = $oDaoMovimentoOcorrencia->sql_record($sSqlBuscaMovimentoOcorrencia);
          if ($oDaoMovimentoOcorrencia->numrows > 0) {
            
            for ($iRowOcorrencia = 0; $iRowOcorrencia < $oDaoMovimentoOcorrencia->numrows; $iRowOcorrencia++) {
              
              $iCodigoOcorrencia = db_utils::fieldsMemory($rsBuscaMovimentoOcorrencia, $iRowOcorrencia)->e02_errobanco;
              if ($iCodigoOcorrencia == 35) {
                $aMovimentos[] = $iCodigoMovimento;
              }
            }
          } else {
            $aMovimentos[] = $iCodigoMovimento;
          }
        }
      }
      $aRetornoMovimentos[$iCodigoGeracao] = $aMovimentos;
    }
    return $aRetornoMovimentos;
  }
  
  
  /**
   * M�todo que devolve o movimento para a agenda
   * - Apenas exclui os dados da tabela empageconfgera
   * @param  integer $iCodigoMovimento
   * @throws BusinessException
   */
  protected function devolverMovimentoParaAgenda($iCodigoMovimento) {
    
    $oDaoEmpAgeConfGera = db_utils::getDao('empageconfgera');
    $oDaoEmpAgeConfGera->excluir($iCodigoMovimento);
    if ($oDaoEmpAgeConfGera->erro_status == "0") {
      throw new BusinessException("N�o foi poss�vel devolver o movimento {$iCodigoMovimento} para a agenda de pagamentos.");
    }
    return true;
  }
  
  protected function inativarProcessamentosAnteriores($iCodigoGeracao, $iCodigoMovimento, $iCodigoRetorno) {
       
    $oDaoEmpAgeConfGera = db_utils::getDao('empageconfgera');
    $oDaoEmpAgeDadosRet = db_utils::getDao('empagedadosret');
    $sWhere  = " e90_codmov      = {$iCodigoMovimento}";
    $sWhere .= " and e90_codgera = {$iCodigoGeracao}";
    $sWhere .= " and e75_ativo   is true ";    
    $sWhere .= " and e75_codret  <> {$iCodigoRetorno}";
    $sSqlDadosProcessamentoAnterior = $oDaoEmpAgeConfGera->sql_query_buscacodretempagedadosretmov(null,
                                                                                              null,
                                                                                              "e75_codret",
                                                                                              "e75_codret",
                                                                                              $sWhere); 
    $rsDadosProcessamentoAnterior = $oDaoEmpAgeConfGera->sql_record($sSqlDadosProcessamentoAnterior);
    if ($oDaoEmpAgeConfGera->numrows > 0){
      
      for($iInd = 0; $iInd < $oDaoEmpAgeConfGera->numrows; $iInd++) {
        $oDadosProcessamentoAnterior = db_utils::fieldsMemory($rsDadosProcessamentoAnterior, $iInd);
        
        $oDaoEmpAgeDadosRet->e75_ativo  = 'false';
        $oDaoEmpAgeDadosRet->e75_codret = $oDadosProcessamentoAnterior->e75_codret;
        $oDaoEmpAgeDadosRet->alterar($oDadosProcessamentoAnterior->e75_codret);
        if ($oDaoEmpAgeDadosRet->erro_status == "0") {
          throw new Exception("Erro ao inativar processamento de retorno {$oDadosProcessamentoAnterior->e75_codret} ");       
        }
        
      }  
      
    }
    
  }

  
  /**
   * Verifica se o movimento est� cancelado
   * @param integer $iCodigoMovimento
   * @return boolean
   */
  protected function movimentoCancelado($iCodigoMovimento, $iCodigoGeracao) {
    
    $oDaoConfiguracaoRemessa   = db_utils::getDao('empageconfgera');
    $sSqlBuscaMovimentoRemessa = $oDaoConfiguracaoRemessa->sql_query_file(null, null, "1", null,"e90_codgera = {$iCodigoGeracao} and e90_codmov = {$iCodigoMovimento} and e90_cancelado is false");
    $rsBuscaMovimentoRemessa   = $oDaoConfiguracaoRemessa->sql_record($sSqlBuscaMovimentoRemessa);
    if ($oDaoConfiguracaoRemessa->numrows > 0) {
      //Movimento cancelado para o arquivo  
      return false;
    }
    
    //Movimento ativo
    return true;
  }
  
  /**
   * Processa um arquivo de retorno salvando os movimentos e suas ocorrencias
   * @throws BusinessException
   * @return mixed Object stdClass
   */
  public function processar() {

    /*
     * Criamos um array com os c�digos de movimentos retornados pelo banco
     */
    $aMovimentosArquivo     = array();
    $aMovimentosCancelados  = array();
    $aRegistrosConfigurados = array();
    
    if (count($this->oRegistroArquivo->registros) == 0) {
      
      $sMsgArquivoProcessado  = "Arquivo j� processado.\n\n";
      $sMsgArquivoProcessado .= "Acesse: Procedimentos > Agenda > Arquivo Retorno > Confirmar Movimento ";
      $sMsgArquivoProcessado .= "para confirmar os movimentos deste arquivo.";
      throw new BusinessException($sMsgArquivoProcessado);
    }
    foreach ($this->oRegistroArquivo->registros as $oRegistro) {
      $oRegistro->codigo_movimento = (int)$oRegistro->codigo_movimento;
      $aMovimentosArquivo[]        = $oRegistro->codigo_movimento;
    }
    
    //Criamos um array com os c�digos da gera��o (codgera) referente a cada movimento retornado pelo banco
    $aCodigoArquivo               = $this->getCodigoArquivoPorMovimento($aMovimentosArquivo);
    if (count($aCodigoArquivo) == 0) {
      
      $sErroMsg  = "Todos os registros desse arquivo foram pagos, ";
      $sErroMsg .= "ou devolvidos a agenda com inconsist�ncia em um processamento anterior.";
      throw new BusinessException($sErroMsg);
    }
    
    /*
     * Organizamos em um array associativo todos os movimentos de um determiado arquivo
     * Ex: [array] => [codgera] => [codmovs]
     */
    $aMovimentosPorGeracao        = $this->getMovimentosPorGeracao($aCodigoArquivo);
    
    /*
     * criamos um array com todos os movimentos enviados para o banco
     */
    $aMovimentoEnviados = array();
    foreach ($aMovimentosPorGeracao as $iCodigoGeracao => $aMovimentosGeracao) {
      foreach ($aMovimentosGeracao as $iCodigoMovimento) {
        $aMovimentoEnviados[] = $iCodigoMovimento;
        
        if ($this->movimentoCancelado($iCodigoMovimento,$iCodigoGeracao)) {
          $aMovimentosCancelados[] = $iCodigoMovimento;
        }
        
      }
    }
    
    /*
     * Executamos o array_diff para descobrir qual dos movimentos enviados o banco n�o retornou 
     * pois precisamos armazenar a ocorrencia
     */
    $aMovimentosNaoRetornados = array_diff($aMovimentoEnviados, $aMovimentosArquivo);
    
    /*
     * Criamos um codigo de retorno para cada codigo de geracao
     */
    $aArquivosGerados = array();
    foreach ($aCodigoArquivo as $iCodigoGeracao) {
      
      $oDaoEmpAgeDadosRet = db_utils::getDao("empagedadosret");
      $oDaoEmpAgeDadosRet->e75_febraban    = $this->oRegistroArquivo->header->febraban;
      $oDaoEmpAgeDadosRet->e75_arquivoret  = $this->oRegistroArquivo->header->uso_banco;
      $oDaoEmpAgeDadosRet->e75_codfebraban = $this->oRegistroArquivo->header->codigo_banco;
      $oDaoEmpAgeDadosRet->e75_seqarq      = "{$this->oRegistroArquivo->header->seq_arquivo}";
      $oDaoEmpAgeDadosRet->e75_codgera     = $iCodigoGeracao;
      $oDaoEmpAgeDadosRet->e75_ativo       = 'true';
      $oDaoEmpAgeDadosRet->incluir(null);
    
      if ($oDaoEmpAgeDadosRet->erro_status == "0") {
        throw new BusinessException("[Erro 1] N�o foi poss�vel incluir os dados do cabe�alho do arquivo.");
      }
      $aArquivosGerados[] = $oDaoEmpAgeDadosRet->e75_codret;
    }
    
    /*
     * Caso tenha movimentos n�o retornados pelo banco, salvamos os dados como ocorrencia 114 (errobanco) 
     */
    $aMovimentosNaoProcessados = array();
    if (count($aMovimentosNaoRetornados) > 0) {
      
      foreach ($aMovimentosNaoRetornados as $iCodigoMovimentoNaoRetornado) {
        
        $oDaoEmpAgeMov   = db_utils::getDao('empagemov');
        $sWhereEmpAgeMov = " e81_codmov = {$iCodigoMovimentoNaoRetornado} ";
        $sSqlEmpAgeMov   = $oDaoEmpAgeMov->sql_query_file(null, " e81_valor ", null, $sWhereEmpAgeMov);
        $rsEmpAgeMov     = $oDaoEmpAgeMov->sql_record($sSqlEmpAgeMov);
         
        if ($oDaoEmpAgeMov->numrows > 0) {
           
          $nValorEfetivado                       = db_utils::fieldsMemory($rsEmpAgeMov, 0)->e81_valor;
          $oDaoEmpAgeDadosRetMov                 = db_utils::getDao("empagedadosretmov");
          $oDaoEmpAgeDadosRetMov->e76_lote       = $this->oRegistroArquivo->registros[0]->numero_lote;
          $oDaoEmpAgeDadosRetMov->e76_movlote    = $this->oRegistroArquivo->registros[0]->mov_lote;
          $oDaoEmpAgeDadosRetMov->e76_numbanco   = $this->oRegistroArquivo->registros[0]->numero_banco;
          $oDaoEmpAgeDadosRetMov->e76_dataefet   = $this->oRegistroArquivo->registros[0]->data_efetivacao;
          $oDaoEmpAgeDadosRetMov->e76_valorefet  = "{$nValorEfetivado}";
          $oDaoEmpAgeDadosRetMov->e76_processado = 'false';
          $oDaoEmpAgeDadosRetMov->e76_codret     = $aArquivosGerados[0];
          $oDaoEmpAgeDadosRetMov->e76_codmov     = $iCodigoMovimentoNaoRetornado;
          $oDaoEmpAgeDadosRetMov->incluir($aArquivosGerados[0], $iCodigoMovimentoNaoRetornado);
          if ($oDaoEmpAgeDadosRetMov->erro_status == "0") {
            throw new BusinessException("[Erro 2] N�o foi poss�vel salvar o movimento n�o encontrado - {$iCodigoMovimentoNaoRetornado} " );
          }
           
          $oDaoEmpAgeDadosRetMovOcorrencia                        = db_utils::getDao('empagedadosretmovocorrencia');
          $oDaoEmpAgeDadosRetMovOcorrencia->e02_empagedadosret    = $aArquivosGerados[0];
          $oDaoEmpAgeDadosRetMovOcorrencia->e02_empagedadosretmov = $iCodigoMovimentoNaoRetornado;
          $oDaoEmpAgeDadosRetMovOcorrencia->e02_errobanco         = 114;
          $oDaoEmpAgeDadosRetMovOcorrencia->incluir(null);
          if ($oDaoEmpAgeDadosRetMovOcorrencia->erro_status == "0") {
            throw new BusinessException("[Erro 3] N�o foi poss�vel salvar a ocorr�ncia para o movimento {$iCodigoMovimentoNaoRetornado}.");
          }
          /**
           * Excluimos da tabela empageconfgera para o registro voltar para agenda
           */
          $this->devolverMovimentoParaAgenda($iCodigoMovimentoNaoRetornado);
          $aMovimentosNaoProcessados[] = $iCodigoMovimentoNaoRetornado;
        }
      }
    }
    
    
    /**
     * Percorremos os registros que o banco devolveu para salvar os mesmos na tabelas de retorno
     * empagedadosretmov | empagedadosretmovocorrencia
     */
    foreach ($this->oRegistroArquivo->registros as $oMovimentoRetorno) {
      
      $iCodigoMovimento = (int)$oMovimentoRetorno->codigo_movimento;
      
      /*
       * Desconsideramos os movimentos que foram cancelados pelo usuario depois de remeter o arquivo ao bancpo
       */
      if (in_array($iCodigoMovimento, $aMovimentosCancelados)) {
        continue;
      }
      /*
       * executamos um pr�-processamento dos erros para podermos setar o campo [e76_processado]
       * e j� armazenamos o c�digo do movimento em um array de inconsistencia para apresentarmos ele no relatorio
       * ao termino do processamento
       */
      $lProcessaRetorno = true;
      foreach ($oMovimentoRetorno->codigo_retorno as $sCodigoErro => $oDadoErroBanco) {
        
        if (!$oDadoErroBanco->processa) {
          
          $lProcessaRetorno = false;
          if (!in_array($iCodigoMovimento, $aMovimentosNaoProcessados) && $oDadoErroBanco->e92_coderro != "BD") {
            $aMovimentosNaoProcessados[] = $iCodigoMovimento;
          }
        }
      }
      
      /*
       * Como incluimos os codrets em outro momento, precisamos buscar atraves do codigo_movimento a qual codigo de retorno
       * este movimento sera vinculado.
       */
      $oDaoConfGera                     = db_utils::getDao('empageconfgera');
      $sWhereRetornoVinculado           = "     e90_codmov = {$iCodigoMovimento} ";
      $sWhereRetornoVinculado          .= " and e75_codret in (".implode($aArquivosGerados,",").")";
      $sSqlBuscaCodigoRetornoVinculado  = $oDaoConfGera->sql_query_buscacodretempagedadosretmov(null,
                                                                                                null,
                                                                                                'e75_codret',
                                                                                                null,
                                                                                                $sWhereRetornoVinculado);
      $rsBuscaCodigoRetornoVinculado    = $oDaoConfGera->sql_record($sSqlBuscaCodigoRetornoVinculado);
      if ($oDaoConfGera->numrows == 0) {
        throw new BusinessException("[Erro 4] N�o foi poss�vel localizar o c�digo de retorno para o movimento {$iCodigoMovimento}.");
      }
      $iCodigoRetornoVinculado  = db_utils::fieldsMemory($rsBuscaCodigoRetornoVinculado, 0)->e75_codret;
      
      /*
       * Verificamos se o codigo de retorno j� est� vinculado ao movimento. Isso pode acontecer quando o arquivo
       * teve um retorno de coderro = 'BD' (Sequencial 35). Caso seja, o codret passa a ser o primeiro indexado no array.
       */
      $oDaoMovimentoOcorrencia  = db_utils::getDao('empagedadosretmovocorrencia');
      $sWhereOcorrencia         = "    e02_empagedadosret    = {$iCodigoRetornoVinculado} ";
      $sWhereOcorrencia        .= "and e02_empagedadosretmov = {$iCodigoMovimento} ";
      $sSqlBuscaOcorrencia      = $oDaoMovimentoOcorrencia->sql_query_file(null, "*", null, $sWhereOcorrencia);
      $rsBuscaOcorrencia        = $oDaoMovimentoOcorrencia->sql_record($sSqlBuscaOcorrencia);
      if ($oDaoMovimentoOcorrencia->numrows > 0) {
        
        $oDadoOcorrencia = db_utils::fieldsMemory($rsBuscaOcorrencia, 0);
        if ($oDadoOcorrencia->e02_errobanco == 35) {
          $iCodigoRetornoVinculado = $aArquivosGerados[0];
        }
      }

      $oDaoEmpAgeDadosRetMov                 = db_utils::getDao('empagedadosretmov');
      $oDaoEmpAgeDadosRetMov->e76_lote       = $oMovimentoRetorno->numero_lote;
      $oDaoEmpAgeDadosRetMov->e76_movlote    = $oMovimentoRetorno->mov_lote;
      $oDaoEmpAgeDadosRetMov->e76_numbanco   = $oMovimentoRetorno->numero_banco;
      $oDaoEmpAgeDadosRetMov->e76_dataefet   = $oMovimentoRetorno->data_efetivacao;
      $oDaoEmpAgeDadosRetMov->e76_valorefet  = "{$oMovimentoRetorno->valor_efetivado}";
      $oDaoEmpAgeDadosRetMov->e76_processado = $lProcessaRetorno ? 'true' : 'false';
      $oDaoEmpAgeDadosRetMov->e76_codret     = $iCodigoRetornoVinculado;
      $oDaoEmpAgeDadosRetMov->e76_codmov     = $iCodigoMovimento;
      $oDaoEmpAgeDadosRetMov->incluir($iCodigoRetornoVinculado, $iCodigoMovimento);
      if ($oDaoEmpAgeDadosRetMov->erro_status == "0") {
        
        $sMensagemErro  = "[Erro 5] O movimento {$iCodigoMovimento} j� encontra-se vinculado ao ";
        $sMensagemErro .= "retorno {$iCodigoRetornoVinculado}.\n\n Procedimento abordado! Contate o suporte."; 
        throw new BusinessException($sMensagemErro);
      }

      /*
       * Vinculamos o movimento com as ocorrencias que eles tiveram neste processamento do arquivo
       */
      foreach ($oMovimentoRetorno->codigo_retorno as $oDadoErroBanco) {
      
        $oDaoEmpAgeDadosRetMovOcorrencia                        = db_utils::getDao('empagedadosretmovocorrencia');
        $oDaoEmpAgeDadosRetMovOcorrencia->e02_empagedadosret    = $iCodigoRetornoVinculado;
        $oDaoEmpAgeDadosRetMovOcorrencia->e02_empagedadosretmov = $iCodigoMovimento;
        $oDaoEmpAgeDadosRetMovOcorrencia->e02_errobanco         = $oDadoErroBanco->sequencia;
        $oDaoEmpAgeDadosRetMovOcorrencia->incluir(null);
        if ($oDaoEmpAgeDadosRetMovOcorrencia->erro_status == "0") {
          throw new BusinessException("[Erro 6] N�o foi poss�vel salvar a ocorr�ncia para o movimento {$iCodigoMovimento}.");
        }
      }
      
      /*
       * Inativamos todos os processamentos de arquivo anteriores
       */
      $this->inativarProcessamentosAnteriores($iCodigoGeracao, $iCodigoMovimento,$iCodigoRetornoVinculado);
      
      if (!$lProcessaRetorno) {
      
        /*
         * Caso a ocorrencia do banco seja 'BD' significa que o mesmo foi agendando com sucesso, portanto 
         * n�o podemos devolver ele para a agenda, pois em outro momento ele retornar� ao sistema com codigo erro 00
         */
        foreach ($oMovimentoRetorno->codigo_retorno as $sCodigoErroBanco => $oDadosErro) {
          if ($oDadosErro->e92_coderro == "BD" && $oDadosErro->sequencia == 35) {
            continue 2;
          }
        }
        
        $oDadosAgendaPgto            = new stdClass();
        $oDadosAgendaPgto->iCodForma = 0;
        $oDadosAgendaPgto->iCodMov   = $iCodigoMovimento;
        $oDaoAgendaPgto              = new agendaPagamento();
        $sDataAtual                  = db_getsession('DB_datausu');
        $oDaoAgendaPgto->configurarPagamentos($sDataAtual, $oDadosAgendaPgto);
        
        $this->devolverMovimentoParaAgenda($iCodigoMovimento);
      }
    }
    
    $lMovimentosNaoProcessados = false;
    $iTotalInconsistencia      = count($aMovimentosNaoProcessados);
    
    if ($iTotalInconsistencia > 0) {
      $lMovimentosNaoProcessados = true;
    }
    
    $oRetorno                            = new stdClass();
    $oRetorno->aArquivos                 = $aArquivosGerados;
    $oRetorno->nInconsistencias          = $iTotalInconsistencia;
    $oRetorno->lInconsistenciaNosErros   = $lMovimentosNaoProcessados;
    $oRetorno->aMovimentosCancelados     = $aMovimentosCancelados;
    $oRetorno->aMovimentosNaoProcessados = $aMovimentosNaoProcessados;
    return $oRetorno;
  }
  
  /**
   * Retorna os movimentos descartados pelo processamento do arquivo
   * @return array
   */
  public function getMovimentosDescartados() {
    return $this->aMovimentosDescartados;
  }
}
?>