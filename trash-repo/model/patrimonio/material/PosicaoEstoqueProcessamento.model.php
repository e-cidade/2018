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

define("URL_MENSAGEM_POSICAOESTOQUEPROCESSAMENTO", "patrimonial.material.PosicaoEstoqueProcessamento.");

require_once("std/DBDate.php");
require_once("model/patrimonio/material/PosicaoEstoque.model.php");

class PosicaoEstoqueProcessamento {

  /**
   * C�digo sequencial
   * @var integer
   */
  private $iCodigo;

  /**
   * C�digo do usu�rio que executou o processamento
   * @var integer
   */
  private $iCodigoUsuario;

  /** 
   * Data de execu��o do processamento
   * @var DBDate
   */
  private $oDataProcessamento;

  /**
   * C�digo sequencial da institui��o
   * @var integer
   */
  private $iCodigoInstituicao;

  /** 
   * Cole��o de Posi��es para o processamento
   * @var PosicaoEstoque[]
   */
  private $aPosicoesEstoque = array();

  /**
   * Carrega os dados do objeto de acordo com o par�metro passado
   * @param integer
   */
  public function __construct($iCodigo = null) {

    $this->iCodigo = $iCodigo;
    if ( !empty($this->iCodigo) ) {

      $oDaoEstoqueProcessamento = db_utils::getDao('posicaoestoqueprocessamento');
      $sSqlBuscaProcessamento   = $oDaoEstoqueProcessamento->sql_query_file($iCodigo);
      $rsBuscaProcessamento     = $oDaoEstoqueProcessamento->sql_record($sSqlBuscaProcessamento);
      if ($oDaoEstoqueProcessamento->erro_status == "0") {
        throw new BusinessException (_M(URL_MENSAGEM_POSICAOESTOQUEPROCESSAMENTO."erro_busca_processamento"));
      }

      $oStdDadosProcessamento   = db_utils::fieldsMemory($rsBuscaProcessamento, 0);
      $this->iCodigo            = $oStdDadosProcessamento->m05_sequencial;
      $this->iCodigoUsuario     = $oStdDadosProcessamento->m05_usuario;
      $this->oDataProcessamento = new DBDate($oStdDadosProcessamento->m05_data);
      $this->iCodigoInstituicao = $oStdDadosProcessamento->m05_instit;
      unset($oStdDadosProcessamento);
    }
  }

  /**
   * Getter para propriedade $iCodigo
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo; 
  }
  
  /**
   * Setter para o c�digo do usu�rio
   * @param integer
   */
  public function setCodigoUsuario ($iCodigoUsuario) {
    $this->iCodigoUsuario = $iCodigoUsuario;
  }
  
  /**
   * Getter para o c�digo do usu�rio
   * @return integer
   */
  public function getCodigoUsuario () {
    return $this->iCodigoUsuario; 
  }
  

  /**
   * Setter para a data de execu��o do processamento
   * @param DBDate
   */
  public function setDataProcessamento (DBDate $oDataProcessamento) {
    $this->oDataProcessamento = $oDataProcessamento;
  }
  
  /**
   * Getter para a data de execu��o do processamento
   * @return DBDate
   */
  public function getDataProcessamento () {
    return $this->oDataProcessamento; 
  }


  /**
   * Setter c�digo sequencial da institui��o
   * @param integer
   */
  public function setCodigoInstituicao ($iCodigoInstituicao) {
    $this->iCodigoInstituicao = $iCodigoInstituicao;
  }
  
  /**
   * Getter c�digo sequencial da institui��o
   * @return integer
   */
  public function getCodigoInstituicao () {
    return $this->iCodigoInstituicao; 
  }
  


  /**
  *M�todo que altera ou inclui um novo processamento
  * @return true
  */
  public function salvar() {

    $oDaoEstoqueProcessamento                 = db_utils::getDao('posicaoestoqueprocessamento');
    $oDaoEstoqueProcessamento->m05_sequencial = $this->iCodigo;
    $oDaoEstoqueProcessamento->m05_usuario    = $this->iCodigoUsuario;
    $oDaoEstoqueProcessamento->m05_data       = $this->oDataProcessamento->getDate();
    $oDaoEstoqueProcessamento->m05_instit     = $this->iCodigoInstituicao;

    if (! empty($this->iCodigo)) {
      
      $oDaoEstoqueProcessamento->alterar($this->iCodigo);
      $this->excluirPosicoesVinculadas();

    } else {
      
      $oDaoEstoqueProcessamento->incluir(null);
      $this->iCodigo = $oDaoEstoqueProcessamento->m05_sequencial;
    }

    if ($oDaoEstoqueProcessamento->erro_status == '0') {
      throw new BusinessException (_M(URL_MENSAGEM_POSICAOESTOQUEPROCESSAMENTO."erro_salvar_processamento"));
    }

    $this->processar();

    return true;
  }

  /** 
   * Retorna um array contendo uma cole��o do objeto PosicaoEstoque
   * @return PosicaoEstoque[]
   */
  public function getPosicoesEstoque() {

    if ( count($this->aPosicoesEstoque) == 0 ) {

      $oDaoPosicaoEstoque = db_utils::getDao('posicaoestoque');
      $sSqlPosicaoEstoque = $oDaoPosicaoEstoque->sql_query_file(null, 'm06_sequencial', null, 'm06_posicaoestoqueprocessamento = '.$this->iCodigo);
      $rsPosicaoEstoque   = $oDaoPosicaoEstoque->sql_record($sSqlPosicaoEstoque);

      if ($oDaoPosicaoEstoque->erro_status == '0') {
        throw new BusinessException (_M(URL_MENSAGEM_POSICAOESTOQUEPROCESSAMENTO.'erro_carregar_posicoesestoque'));
      }

      for ($iPosicao = 0; $iPosicao < $oDaoPosicaoEstoque->numrows; $iPosicao++) {
        
        $oStdDadosPosicaoEstoque  = db_utils::fieldsMemory($rsPosicaoEstoque, $iPosicao);
        $this->aPosicoesEstoque[] = new PosicaoEstoque($oStdDadosPosicaoEstoque->m06_sequencial);
      }
    }
    return $this->aPosicoesEstoque;
  }

  /**
   * M�todo respons�vel por processar o fechamento do material.
   * @return true;
   */
  private function processar() {

    /**
     * Buscamos todos os materiais do estoque em que n�o sejam servi�o
     */
    $oDaoMatMater       = db_utils::getDao("matmater");
    $sCamposMateriais   = "distinct matmater.m60_codmater,matestoque.m70_codigo";
    $sWhereMateriais    = "matestoqueitem.m71_servico is false";
    $sSqlBuscaMateriais = $oDaoMatMater->sql_query_deptoestoque(null, $sCamposMateriais, "matmater.m60_codmater", $sWhereMateriais);
    $rsBuscaMateriais   = $oDaoMatMater->sql_record($sSqlBuscaMateriais);

    if ($oDaoMatMater->erro_status == "0") {
      throw new BusinessException(_M(URL_MENSAGEM_POSICAOESTOQUEPROCESSAMENTO."busca_materiais"));
    }

    $oDaoMaterialEstoque = db_utils::getDao("matestoque");
    $sCamposEstoque      = " matestoque.m70_codigo";
    $sCamposEstoque     .= ",matestoqueinimei.m82_quant as quantidade";
    $sCamposEstoque     .= ",db_depart.coddepto";
    $sCamposEstoque     .= ",matestoqueini.m80_codigo";
    $sCamposEstoque     .= ",matestoqueini.m80_data";
    $sCamposEstoque     .= ",matestoqueini.m80_hora";
    $sCamposEstoque     .= ",m89_precomedio as preco_medio";
    $sCamposEstoque     .= ",m81_tipo";
    $sCamposEstoque     .= ",m82_codigo ";
    $sOrdenadoPor        = "to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS'), m80_codigo, m82_codigo";
    $sWhereEstoque       = "     matestoque.m70_codmatmater = $1 ";
    $sWhereEstoque      .= " and matestoque.m70_codigo      = $2 ";
    $sWhereEstoque      .= " and db_depart.instit           = $3 ";
    $sWhereEstoque      .= " and matestoquetipo.m81_tipo   in (1, 2) ";
    $sWhereEstoque      .= " and matestoqueini.m80_data    between $4 and $5 ";

    $sSqlBuscaMaterial  = $oDaoMaterialEstoque->sql_query_saida(null, $sCamposEstoque, $sOrdenadoPor, $sWhereEstoque);
    $rsPrepararQuery    = pg_prepare("busca_movimentacao_material", $sSqlBuscaMaterial);

    /**
     * Percorremos as movimenta��es encontradas para processarmos a posi��o do material at� o momento atual.
     */
    for ($iMaterial = 0; $iMaterial < $oDaoMatMater->numrows; $iMaterial++) {

      $oStdDadoMaterial   = db_utils::fieldsMemory($rsBuscaMateriais, $iMaterial);

      /**
       * Data padr�o para inicio do processamento
       */
      $dtProcessamentoAnterior = "1900-01-01";

      /**
       * Quantidade total do item
       */
      $nQuantidadeTotal        = 0;
      
      /**
       * �ltimo pre�o m�dio encontrado para o item.
       */
      $nUltimoPrecoMedio       = 0;

      /**
       * C�digo das movimenta��es (matestoqueinimei) utilizadas para encontrarmos os dados
       * salvos na tabela posicaoestoque
       */
      $aCodigosMovimentacao    = array();

      /**
       * Busca se h� processamentos anteriores, caso haja assumiremos a quantidade do �ltimo processamento
       */
      $oDaoPosicaoEstoqueProcessamento    = db_utils::getDao('posicaoestoqueprocessamento');
      $sCamposPosicaoEstoqueProcessamento = " posicaoestoqueprocessamento.m05_data, m06_quantidade, m06_valor ";
      $sWherePosicaoEstoqueProcessamento  = "    posicaoestoque.m06_matestoque        = {$oStdDadoMaterial->m70_codigo} ";
      $sWherePosicaoEstoqueProcessamento .= " and posicaoestoqueprocessamento.m05_data < '{$this->oDataProcessamento->getDate()}'";
      $sSqlBuscaProcessamentoAnterior     = $oDaoPosicaoEstoqueProcessamento->sql_query_posicaoestoque(null, 
                                                                                                       $sCamposPosicaoEstoqueProcessamento, 
                                                                                                       "m05_data desc limit 1", 
                                                                                                       $sWherePosicaoEstoqueProcessamento);
      $rsBuscaProcessamentoAnterior       = $oDaoPosicaoEstoqueProcessamento->sql_record($sSqlBuscaProcessamentoAnterior);

      /**
       * Se h� processamento anterior, busca a data do dia posterior a do �ltimo processamento e a quantidade do material
       */
      if ($oDaoPosicaoEstoqueProcessamento->numrows == 1) {

        $oStdDadoMovimentacaoAnterior = db_utils::fieldsMemory($rsBuscaProcessamentoAnterior, 0);
        $dtProcessamentoAnterior      = strtotime($oStdDadoMovimentacaoAnterior->m05_data);
        $dtProcessamentoAnterior      = strtotime("+1 day", $dtProcessamentoAnterior);
        $dtProcessamentoAnterior      = date('Y-m-d', $dtProcessamentoAnterior);
        $nQuantidadeTotal             = $oStdDadoMovimentacaoAnterior->m06_quantidade;
      }

      $aParametrosQuery    = array( $oStdDadoMaterial->m60_codmater
                                   ,$oStdDadoMaterial->m70_codigo
                                   ,$this->iCodigoInstituicao
                                   ,$dtProcessamentoAnterior
                                   ,$this->oDataProcessamento->getDate());


      $rsBuscaMovimentacao = pg_execute("busca_movimentacao_material", $aParametrosQuery);
      $iTotalMovimentacao  = pg_num_rows($rsBuscaMovimentacao);

      for ($iMovimentacao = 0; $iMovimentacao < $iTotalMovimentacao; $iMovimentacao++) {

        $oStdMovimentacao = db_utils::fieldsMemory($rsBuscaMovimentacao, $iMovimentacao);

        switch ($oStdMovimentacao->m81_tipo) {

          case 1:
            $nQuantidadeTotal += $oStdMovimentacao->quantidade;
            break;
          
          case 2:
            $nQuantidadeTotal -= $oStdMovimentacao->quantidade;
            break;

          default:
            throw new BusinessException(_M(URL_MENSAGEM_POSICAOESTOQUEPROCESSAMENTO."movimentacao_nao_permitida"));
        }

        /**
         * M�todo para verificar quais materiais tiveram movimenta��o negativa
         * Caso tenha alguma movimenta��o negativa, o material � inclu�do numa tabela de log
         * @todo remover no futuro
         */
        $lPossuiMovimentacaoNegativa = $this->verificaMovimentacaoNegativa($oStdDadoMaterial->m60_codmater, $nQuantidadeTotal);
        
        if ($lPossuiMovimentacaoNegativa) {
          break;
        }

        /**
         * Pegamos o pre�o m�dio da �ltima movimenta��o
         */
        if ( $iMovimentacao == ($iTotalMovimentacao - 1) ) {
          $nUltimoPrecoMedio = $oStdMovimentacao->preco_medio;
        }

        /**
         * Guardamos os c�digos de movimenta��es no qual encontramos os valores que comp�e a posi��o do estoque.
         * - Com eles, executamos o v�nculo entre posicaoestoque e matestoqueinimei
         */
        $aCodigosMovimentacao[] = $oStdMovimentacao->m82_codigo;
      }

      /**
       * Quando n�o existir movimenta��o no per�odo, buscamos a �ltima posi��o existente e replicamos para o processamento
       * atual. Do contr�rio, � inserida uma nova posi��o com as movimenta��es do intervalo de per�odo.
       */
      if ($iTotalMovimentacao == 0) {

        $oPosicaoEstoqueUltimaPosicao = PosicaoEstoque::getUltimaPosicaoEstoque($oStdDadoMaterial->m70_codigo, $this->oDataProcessamento->getDate());

        if ( $oPosicaoEstoqueUltimaPosicao ) {

          $oNovaPosicaoEstoque  = clone $oPosicaoEstoqueUltimaPosicao;
          $oNovaPosicaoEstoque->setCodigoProcessamento($this->iCodigo);
          $oNovaPosicaoEstoque->salvar();
        }

      } else {

        if ($this->verificaMovimentacaoNegativa($oStdDadoMaterial->m60_codmater, $nQuantidadeTotal)) {
          continue;
        }
      
        if ($nQuantidadeTotal == 0) {
          $nUltimoPrecoMedio = 0;
        }

        $oPosicaoEstoque = new PosicaoEstoque(null);
        $oPosicaoEstoque->setCodigoProcessamento($this->iCodigo);
        $oPosicaoEstoque->setCodigoMaterialEstoque($oStdDadoMaterial->m70_codigo);
        $oPosicaoEstoque->setQuantidade($nQuantidadeTotal);
        $oPosicaoEstoque->setValor($nQuantidadeTotal * $nUltimoPrecoMedio);
        $oPosicaoEstoque->setPrecoMedio($nUltimoPrecoMedio);
        $oPosicaoEstoque->setCodigoMovimentacoes($aCodigosMovimentacao);
        $oPosicaoEstoque->salvar();
        unset($oPosicaoEstoque);
      }
    }
    return true;
  }

  /**
   * M�todo utilizado para mapearmos as movimenta��es negativas. Este m�todo dever� ser removido quando
   * terminarmos de acertar o estoque nos clientes.
   * 
   * Quando a propriedade $nQuantidadeTotal estiver < 0, ser� salvo em uma tabela de log
   *
   * @param integer $iCodigoMaterial - c�digo sequencial do material
   * @param float   $nQuantidadeTotal - Quantidade encontrada na movimenta��o
   * @return boolean
   */
  private function verificaMovimentacaoNegativa($iCodigoMaterial, $nQuantidadeTotal) {

    $iQuantidadeFormatada = db_formatar(round($nQuantidadeTotal,2), "p", " ", 2);

    if ($iQuantidadeFormatada < 0) {

      $rsCriaTabelaBackup      = db_query('create table if not exists materiaisnegativospatrimonial (codigo_material integer)');
      $rsBuscarMaterialIncluso = db_query("select * from materiaisnegativospatrimonial where codigo_material = {$iCodigoMaterial}");
      
      if (pg_num_rows($rsBuscarMaterialIncluso) == 0) {

        $rsExecutaInclusao = pg_query("insert into materiaisnegativospatrimonial values ({$iCodigoMaterial})");
        if ( !$rsExecutaInclusao ) {
          throw new BusinessException (_M(URL_MENSAGEM_POSICAOESTOQUEPROCESSAMENTO.'erro_verificar_movimentacaonegativa'));
        }
      }
      return true;
    }
    return false;
  }

  /**
   * Busca data do �ltimo processamento encontrado para a institui��o
   * @param  Instituicao $oInstituicao
   * @return mixed retorna false em caso de n�o existir processamento e um objeto do tipo DBDate caso encontre
   */
  public static function getDataUltimoProcessamento(Instituicao $oInstituicao) {

    $oDaoEstoqueProcessamento   = db_utils::getDao('posicaoestoqueprocessamento');
    $sWhereProcessamento        = "m05_instit = {$oInstituicao->getSequencial()}";
    $sSqlUltimoProcessamento    = $oDaoEstoqueProcessamento->sql_query_file(null, "m05_data", "m05_data desc limit 1", $sWhereProcessamento);
    $rsBuscaUltimoProcessamento = $oDaoEstoqueProcessamento->sql_record($sSqlUltimoProcessamento);

    if ($oDaoEstoqueProcessamento->erro_status == "0") {
      return false;
    }
    return new DBDate (db_utils::fieldsMemory($rsBuscaUltimoProcessamento, 0)->m05_data);
  }

  /**
   * Retorna uma instancia do objeto de acordo com a data informada no par�metro. Caso n�o 
   * exista, retorna um objeto vazio
   * @param DBDate $oData
   * @param Instituicao $oInstituicao
   */
  public static function getInstanciaPorData(DBDate $oData, Instituicao $oInstituicao) {

    $oDaoEstoqueProcessamento = db_utils::getDao('posicaoestoqueprocessamento');
    $sWhereProcessamento      = "     m05_instit = {$oInstituicao->getSequencial()}";
    $sWhereProcessamento     .= " and m05_data   = '{$oData->getDate()}'";
    $sSqlBuscaProcessamento   = $oDaoEstoqueProcessamento->sql_query_file(null, "m05_sequencial", null, $sWhereProcessamento);
    $rsBuscaProcessamento     = $oDaoEstoqueProcessamento->sql_record($sSqlBuscaProcessamento);

    if ($oDaoEstoqueProcessamento->erro_status == "0") {
      return new PosicaoEstoqueProcessamento(null);
    }
    return new PosicaoEstoqueProcessamento(db_utils::fieldsMemory($rsBuscaProcessamento, 0)->m05_sequencial);
  }

  /**
   * Exclui as movimenta��es vinculadas
   * - N�o foi utilizado o objeto devido a volume de dados que ser�o processados. (~10000).
   */
  private function excluirPosicoesVinculadas() {

    $oDaoPosicaoEstoque     = db_utils::getDao('posicaoestoque');
    $sSqlBuscaProcessamento = $oDaoPosicaoEstoque->sql_query_file(null, "m06_sequencial", null, "m06_posicaoestoqueprocessamento = {$this->iCodigo}");
    $rsBuscaProcessamento   = $oDaoPosicaoEstoque->sql_record($sSqlBuscaProcessamento);

    if ($oDaoPosicaoEstoque->erro_status = "0") {
      throw new BusinessException (_M (URL_MENSAGEM_POSICAOESTOQUEPROCESSAMENTO."erro_buscar_posicoes"));
    }

    
    for ($iPosicaoEstoque = 0; $iPosicaoEstoque < $oDaoPosicaoEstoque->numrows; $iPosicaoEstoque++) {
      
      $iCodigoPosicaoEstoque = db_utils::fieldsMemory($rsBuscaProcessamento, $iPosicaoEstoque)->m06_sequencial;

      $oDaoPosicaoEstoqueMaterial = db_utils::getDao('posicaoestoquematestoqueinimei');
      $rsExcluirVinculo = $oDaoPosicaoEstoqueMaterial->excluir(null, "m07_posicaoestoque = {$iCodigoPosicaoEstoque}");

      if ($oDaoPosicaoEstoqueMaterial->erro_status = "0") {
        throw new BusinessException (_M (URL_MENSAGEM_POSICAOESTOQUEPROCESSAMENTO."erro_excluir_vinculo_posicao"));
      }

    }

    $oDaoPosicaoEstoque->excluir(null, "m06_posicaoestoqueprocessamento = {$this->iCodigo}");

    if ($oDaoPosicaoEstoque->erro_status = "0") {
      throw new BusinessException (_M (URL_MENSAGEM_POSICAOESTOQUEPROCESSAMENTO."erro_excluir_posicoes"));
    }

    return true;
  }
}