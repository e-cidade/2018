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
 * Modelo de negocios para solicitacoes de compra
 *
 */
class solicitacaoCompra {

  /**
   * C�digo da solicita��o
   *
   * @var integer
   */
  private $iSolicitacao;

  /**
   * array das contrapartidas cadastradas
   *
   * @var unknown_type
   */
  private $aContrapartida = array (
     
  );
  
  /**
   * departamento da solicita��o
   * @var integer
   */
  private $iDepartamento;

  /**
   * dao da tabela solicita
   *
   * @var object
   */
  
  private $oDaoSolicita = null;
  
  
  /**
   * C�digo que a institui��o est� vinculada
   * @var integer
   */
  protected $iCodigoInstituicao;
  
  /**
   * metodo Construtor da solicita��a
   *
   * @param integer $iSolicitacao
   */
  function __construct($iSolicitacao) {

    $this->iSolicitacao = $iSolicitacao;
    $this->oDaoSolicita = db_utils::getDao("solicita");
    
    $oDaoSolicita = db_utils::getDao("solicita");
    
    if (isset($iSolicitacao) && !empty($iSolicitacao)) {
      
      $sSqlSolicita = $oDaoSolicita->sql_query_file ($iSolicitacao);
      $rsSolicita   = $oDaoSolicita->sql_record($sSqlSolicita);
      if ($oDaoSolicita->numrows > 0) {
        
        $oDadosSolicita           = db_utils::fieldsMemory($rsSolicita, 0);
        $this->setDepartamento($oDadosSolicita->pc10_depto);
        $this->iCodigoInstituicao = $oDadosSolicita->pc10_instit;
      }
      
      
    }
  }
  
  /**
   * Retorna o c�digo da institui��o � qual a solicita��o est� vinculada
   * @return integer
   */
  public function getCodigoInstituicao() {
    return $this->iCodigoInstituicao;
  }
  
  /**
   * Seta o c�digo da institui��o � qual a solicita��o est� vinculada
   * @param integer $iCodigoInstituicao
   */
  public function setCodigoInstituicao($iCodigoInstituicao) {
    $this->iCodigoInstituicao = $iCodigoInstituicao;
  }
  
  
  /**
   * @return unknown
   */
  public function getDepartamento() {
    return $this->iDepartamento;
  }
  
  /**
   * define a departamento da solicitacao
   * @param integer $iDepartamento
   */
  public function setDepartamento($iDepartamento) {
    $this->iDepartamento = $iDepartamento;
  }
  
  /**
   * Salva as Contrapartidas da dotacao
   *
   * @param integer $iDotacao c�digo da dotacao do item (pc13_sequencial)
   * @param integer $lDeleta se devemos Excluir as contrapartidas antes de incluir novamente
   * @return boolean
   */
  function saveContrapartidas($iDotacao, $iRecurso, $nValor) {

    $oDaoContrapartida = db_utils::getDao("pcdotaccontrapartida");
    $oDaoContrapartida->excluir(null, "pc19_pcdotac = {$iDotacao}"); 
    if ($iRecurso != 0) {
      
      $oDaoContrapartida->pc19_orctiporec = $iRecurso;
      $oDaoContrapartida->pc19_valor      = $nValor;
      $oDaoContrapartida->pc19_pcdotac    = $iDotacao;
      $oDaoContrapartida->incluir(null);
      if ($oDaoContrapartida->erro_status == 0) {
        
        $sErroMsg = "N�o foi pos�vel incluir contrapartida ({$iRecurso})\\n";
        $sErroMsg .= "poss�vel erro:$oDaoContrapartida->erro_msg";
        throw new Exception($sErroMsg);
        return false;
        
      }
    }
    return true;  
  }
  
  /**
   * verifica se o itme possui dotacoes
   *
   * @param integer $iCodItem    c�digo do item
   * @param integer $iCodDotacao codigo da dotacao
   * @param integer $iCodRecurso codigo do recurso
   * @return boolean
   */
  function itemHasDotacoes($iCodItem, $iCodDotacao, $iCodRecurso = 0, $iCodDotac = null) {

    $sInnerJoin = null;
    $sInnerJoin = " inner join pcdotaccontrapartida on pc13_sequencial = pc19_pcdotac";
    if ($iCodRecurso != 0) {
      $sWhere = " and pc19_orctiporec = {$iCodRecurso}";
    } else {
      $sWhere = " and pc19_orctiporec is null";
    }
    if ($iCodDotac != null) {
      $sWhere .= " and pc13_sequencial <> {$iCodDotac}";
    }
    $sSqlDotacao = "select pc13_sequencial";
    $sSqlDotacao .= "  from pcdotac  {$sInnerJoin}";
    $sSqlDotacao .= " where pc13_codigo = {$iCodItem} ";
    $sSqlDotacao .= "  and  pc13_coddot = {$iCodDotacao} {$sWhere}";
    $rsDotacao = $this->oDaoSolicita->sql_record($sSqlDotacao);
    //die($sSqlDotacao);
    if ($this->oDaoSolicita->numrows > 0) {
      return true;
    } else {
      return false;
    }
  }
  /**
   * Vincula itens da solicitacao com o item do pacto, e realiza o comprometimento do saldo
   *
   * @param unknown_type $iCodigoItem
   * @param unknown_type $iItemPacto
   * @param unknown_type $nQuantidade
   * @param unknown_type $nValor
   */
  function vincularItemPacto($iCodigoItem, $iItemPacto, $nQuantidade, $nValor) {
    
    $oItemPacto = new itemPacto($iItemPacto);
    $oItemPacto->baixarSaldoSolicitacao($nQuantidade, $nValor,$iCodigoItem);
  
  }
  /**
   * Desvincula o item ao saldo do item do pacto.
   *
   * @param integer $iCodigoItem item da solicitacao
   */
  
  function excluirVinculacaoItemPacto($iCodigoItem) {
    
    $oDaoPactoSolicitaItem = db_utils::getDao("pactovalormovsolicitem");
    $sSqlItemPacto         = $oDaoPactoSolicitaItem->sql_query_item(null,
                                                                  'o87_sequencial',
                                                                  null,
                                                                  "o101_solicitem={$iCodigoItem}"
                                                                 );

    $rsItemPacto         = $oDaoPactoSolicitaItem->sql_record($sSqlItemPacto);
    if ($oDaoPactoSolicitaItem->numrows > 0) {
      
      $oItem      = db_utils::fieldsmemory($rsItemPacto,0);
      $oItemPacto = new itemPacto($oItem->o87_sequencial);
      $oItemPacto->excluirSaldoSolicitacao($iCodigoItem);
      
    }
  }
  /**
   * Exclui todas as vincula��es e dos itens do pacto com a solicita��o
   *
   */
  function excluirItensPactoGeral() {

    $oDaoSolicitem = new cl_solicitem;
    $sSqlItens     = $oDaoSolicitem->sql_query_file(null,"*",null,"pc11_numero={$this->iSolicitacao}");
    $rsItens       = $oDaoSolicitem->sql_record($sSqlItens);
    $iNumRows      = $oDaoSolicitem->numrows;
    
    for ($i = 0; $i < $iNumRows; $i++) {
    
      $oITem = db_utils::fieldsMemory($rsItens, $i);
      $this->excluirVinculacaoItemPacto($oITem->pc11_codigo);
      
    }
  }
  
  /**
   * Adiciona um item na solicitacao para o registro de preco
   *
   * @param unknown_type $iMaterial
   */
  public function addItemRegistroPreco($iSolicitem, $iMaterial, $iRegistroPreco, $nQuantidade, $iCodigoItemORigem) {
    
    /**
     * Cria um processo de compras para a solicitacao, caso nao exista. se existir, devemos usar a mesma
     */
    $iCodigoProcesso      = null;
    $iCodigoItemProcesso  = null;
    $iCodigoOrcamento     = null;
    $iCodigoItemOrcamento = null;
    $iCodigoFornecedor    = null;
    $oDaoPcProc           = new cl_pcproc;
    $oDaoPcProcItem       = new cl_pcprocitem;
    $oDaoPcOrcam          = new cl_pcorcam;
    $oDaopcOrcamItem      = new cl_pcorcamitem; 
    $oDaopcOrcamForne     = new cl_pcorcamforne; 
    $oDaopcOrcamItemProc  = new cl_pcorcamitemproc; 
    $oDaopcOrcamJulg      = new cl_pcorcamjulg;
    $oDaopcOrcamVal       = new cl_pcorcamval;
    $oDaoSolicitemVinculo = db_utils::getDao("solicitemvinculo"); 
    /**
     * Devemos vincular o item da solicitacao ao registro de pre�o 
     */
    $sSqlVerificaVinculo  = $oDaoSolicitemVinculo->sql_query_file(null, 
                                                                  "*", 
                                                                  null,
                                                                  "pc55_solicitemfilho={$iSolicitem}"
                                                                  );
    $rsVerificaVinculo    = $oDaoSolicitemVinculo->sql_record($sSqlVerificaVinculo);
    if ($oDaoSolicitemVinculo->numrows == 0) {
      
      $oDaoSolicitemVinculo->pc55_solicitemfilho = $iSolicitem;                                                                  
      $oDaoSolicitemVinculo->pc55_solicitempai   = $iCodigoItemORigem;
      $oDaoSolicitemVinculo->incluir(null);                                                                  
    }
    $sSqlVerificaProcesso = $oDaoPcProcItem->sql_query_file(null,"*", null,"pc81_solicitem={$iSolicitem}");
    $rsVerificaProcesso   = $oDaoPcProcItem->sql_record($sSqlVerificaProcesso);
    if ($oDaoPcProcItem->numrows > 0) {
      
      $oItemProc           = db_utils::fieldsMemory($rsVerificaProcesso, 0);
      $iCodigoItemProcesso = $oItemProc->pc81_codprocitem;
      $iCodigoProcesso     = $oItemProc->pc81_codproc;
       
    } else {
      
      /**
       * Verificamos se j� existem processo para essa solicitacao
       */
      $sSqlVerificaProcesso = $oDaoPcProcItem->sql_query(null,"pc80_codproc", null, "pc11_numero = {$this->iSolicitacao}");
      $rsVerificaProcesso   = $oDaoPcProc->sql_record($sSqlVerificaProcesso);
      if ($oDaoPcProc->numrows > 0) {

         $iCodigoProcesso = db_utils::fieldsMemory($rsVerificaProcesso, 0)->pc80_codproc;
      } else {
        
        /**
         * Incluimos o procsso
         */
        $oDaoPcProc->pc80_data     = date("Y-m-d",db_getsession("DB_datausu"));
        $oDaoPcProc->pc80_depto    = db_getsession("DB_coddepto");
        $oDaoPcProc->pc80_usuario  = db_getsession("DB_id_usuario");
        $oDaoPcProc->pc80_resumo   = "Processo de Compras Criado automatico para a soliciticao {$this->iSolicitacao} ";
        $oDaoPcProc->pc80_resumo  .= "do registro de pre�os {$iRegistroPreco}";
        $oDaoPcProc->pc80_situacao = 2;
        $oDaoPcProc->incluir(null);
        if ($oDaoPcProc->erro_status == 0) {
           
           $sErroMsg = "N�o foi poss�vel incluir item\\n";
           $sErroMsg .= "Erro:$oDaoPcProc->erro_msg";
           throw new Exception($sErroMsg);   
        }
        
        $iCodigoProcesso = $oDaoPcProc->pc80_codproc;
        
        /**
         * incluimos o item 
         */
        
      }
      $oDaoPcProcItem->pc81_codproc   = $iCodigoProcesso;
      $oDaoPcProcItem->pc81_solicitem = $iSolicitem;
      $oDaoPcProcItem->incluir(null);
      if ($oDaoPcProcItem->erro_status == 0) {
           
         $sErroMsg = "N�o foi poss�vel incluir item\\n";
         $sErroMsg .= "Erro:$oDaoPcProcItem->erro_msg";
         throw new Exception($sErroMsg);   
      }
      $iCodigoItemProcesso = $oDaoPcProcItem->pc81_codprocitem;
    }
    /**
     * Verificamos se j� existem um orcamento para esse processo de compras
     */
    $sSqlOrcamItem = $oDaopcOrcamItemProc->sql_query_orcam(null,null,"*",null,"pc31_pcprocitem={$iCodigoItemProcesso}");
    $rsOrcamItem   = $oDaopcOrcamItemProc->sql_record($sSqlOrcamItem);
    if ($oDaopcOrcamItemProc->numrows > 0 ) {

      $oOrcamentoItem       = db_utils::fieldsMemory($rsOrcamItem, 0);
      $iCodigoOrcamento     = $oOrcamentoItem->pc22_codorc;
      $iCodigoItemOrcamento = $oOrcamentoItem->pc22_orcamitem;
      
    } else {
      
      /**
       * incluirmos o orcamento
       */
      $sSqlVerificaOrcamento = $oDaoPcOrcam->sql_query_pcmaterproc(null, "pc20_codorc",
                                                                   null, "pc11_numero = {$this->iSolicitacao}");
      $rsVerificaOrcamento   = $oDaoPcOrcam->sql_record($sSqlVerificaOrcamento);     
      if ($oDaoPcOrcam->numrows > 0) {
        $iCodigoOrcamento = db_utils::fieldsMemory($rsVerificaOrcamento, 0)->pc20_codorc;
      }else{
         
        $oDaoPcOrcam->pc20_obs   = "Orcamento Processo de Compras Criado automatico para a soliciticao {$this->iSolicitacao} ";
        $oDaoPcOrcam->pc20_obs  .= "do registro de pre�os {$iRegistroPreco}";
        $oDaoPcOrcam->pc20_dtate = date("Y-m-d",db_getsession("DB_datausu"));
        $oDaoPcOrcam->pc20_hrate = "18:00";
        $oDaoPcOrcam->incluir(null);
        if ($oDaoPcOrcam->erro_status == 0) {
          
          $sErroMsg = "N�o foi poss�vel incluir item\\n";
          $sErroMsg .= "Erro:$oDaoPcOrcam->erro_msg";
          throw new Exception($sErroMsg);  
        }
        
        $iCodigoOrcamento = $oDaoPcOrcam->pc20_codorc;
      }
      /**
       * incluimos o material no orcamento
       */
      $oDaopcOrcamItem->pc22_codorc = $iCodigoOrcamento;
      $oDaopcOrcamItem->incluir(null);
      if ($oDaopcOrcamItem->erro_status == 0) {
        
        $sErroMsg  = "N�o foi poss�vel incluir item\\n";
        $sErroMsg .= "Erro:$oDaopcOrcamItem->erro_msg";
        throw new Exception($sErroMsg);  
          
      }
      
      /**
       * inserindo na tabela pcorcamitemproc
       */
      $oDaopcOrcamItemProc->pc31_orcamitem = $oDaopcOrcamItem->pc22_orcamitem;
      $oDaopcOrcamItemProc->pc31_pcprocitem = $iCodigoItemProcesso;
      $oDaopcOrcamItemProc->incluir($oDaopcOrcamItem->pc22_orcamitem, $iCodigoItemProcesso);
      if ($oDaopcOrcamItemProc->erro_status == 0) {
        
        $sErroMsg  = "N�o foi poss�vel incluir item\\n";
        $sErroMsg .= "Erro:$oDaopcOrcamItemProc->erro_msg";
        throw new Exception($sErroMsg);  
        
      }
      $iCodigoItemOrcamento = $oDaopcOrcamItem->pc22_orcamitem;
    }
    
    
    /**
     * Verificamos o menor preco
     */
    require_once("model/compilacaoRegistroPreco.model.php");
    $oCompilacao = new compilacaoRegistroPreco($iRegistroPreco);
    $oDadosFornecedor = $oCompilacao->getFornecedorItem($iMaterial, $iCodigoItemORigem);
    $oItem            = $oCompilacao->getItemByCodigo($iCodigoItemORigem);
    
    /**
     * Buscamos o C�digo do item da Estimativa do departamento. caso nao exista devemos lan�ar uma excess�o
     * verificamos se o item possui saldo para solicitar
     */
    $sSqlItemEstimativa  = "select pc11_codigo"; 
    $sSqlItemEstimativa .= "  from solicitemvinculo"; 
    $sSqlItemEstimativa .= "       inner join solicitem on pc55_solicitempai = pc11_codigo"; 
    $sSqlItemEstimativa .= "       inner join solicita  on pc10_numero       = pc11_numero";
    $sSqlItemEstimativa .= "       left  join solicitaanulada on pc10_numero = pc67_solicita"; 
    $sSqlItemEstimativa .= " where pc10_depto = ".db_getsession("DB_coddepto"); 
    $sSqlItemEstimativa .= "   and pc55_solicitemfilho = {$iCodigoItemORigem}";
    $sSqlItemEstimativa .= "   and pc67_solicita is null";
    $rsItemEstimativa    = db_query($sSqlItemEstimativa);
    if (pg_num_rows($rsItemEstimativa) != 1) {

      $sErro  = "Departamento ".db_getsession("DB_coddepto")." Nao possui estimativa lan�ada para ";
      $sErro .= "item {$oItem->getDescricaoMaterial()}";
      throw new Exception($sErro);
    }
    $iCodigoItemEstimativa = db_utils::fieldsMemory($rsItemEstimativa, 0)->pc11_codigo;
    $oItemEstimativa      = new ItemEstimativa($iCodigoItemEstimativa);
    $oSaldosItem          = $oItemEstimativa->getMovimentacao();
    if ($nQuantidade > ($oSaldosItem->saldo+$nQuantidade)) {
      
      $sErroMsg  = "N�o foi poss�vel incluir item\\n";
      $sErroMsg .= "Saldo solicitado do item ".urldecode($oItem->getDescricaoMaterial())." maior que o dispon�vel.\\n";
      $sErroMsg .= "Saldo Disponivel no Departamento: ".($oSaldosItem->saldo+$nQuantidade);
      throw new Exception($sErroMsg); 
    }
    
    /**
     * Verificamos se o fornecedor j� foi cadastrado
     */
    $sSqlFornecedor = $oDaopcOrcamForne->sql_query_file(null,"*", 
                                                        null,
                                                        "pc21_numcgm = {$oDadosFornecedor->codigocgm}
                                                        and pc21_codorc = {$iCodigoOrcamento}"
                                                        );
    $rsFornecedor   = $oDaopcOrcamForne->sql_record($sSqlFornecedor);
    if ($oDaopcOrcamForne->numrows > 0) {
      $iCodigoFornecedor = db_utils::fieldsMemory($rsFornecedor, 0)->pc21_orcamforne;
    } else {
      
      $oDaopcOrcamForne->pc21_codorc    = $iCodigoOrcamento;
      $oDaopcOrcamForne->pc21_numcgm    = $oDadosFornecedor->codigocgm;
      $oDaopcOrcamForne->pc21_importado = "false";
      $oDaopcOrcamForne->incluir(null);
      if ($oDaopcOrcamForne->erro_status == 0) {
        
        $sErroMsg  = "N�o foi poss�vel incluir item para Fornecedor\\n";
        $sErroMsg .= "Erro:$oDaopcOrcamForne->erro_msg";
        throw new Exception($sErroMsg);  
        
      }
      $iCodigoFornecedor = $oDaopcOrcamForne->pc21_orcamforne;
    }
    
    /**
     * incluimos o valor orcado para o fornecedor
     */
    $oDaopcOrcamVal->pc23_orcamforne = $iCodigoFornecedor;
    $oDaopcOrcamVal->pc23_orcamitem  = $iCodigoItemOrcamento;
    $oDaopcOrcamVal->pc23_quant      = "$nQuantidade";
    $oDaopcOrcamVal->pc23_obs        = $oDadosFornecedor->obsorcamento;
    $oDaopcOrcamVal->pc23_vlrun      = $oDadosFornecedor->valorunitario;
    $oDaopcOrcamVal->pc23_valor      = $oDadosFornecedor->valorunitario*$nQuantidade;
    $oDaopcOrcamVal->incluir($iCodigoFornecedor, $iCodigoItemOrcamento);
    if ($oDaopcOrcamVal->erro_status == 0) {
      
      $sErroMsg  = "N�o foi poss�vel incluir item\\n";
      $sErroMsg .= "Erro:$oDaopcOrcamVal->erro_msg";
      throw new Exception($sErroMsg);
        
    }
    /**
     * Incluimos  julgamento
     */
    
    $oDaopcOrcamJulg->pc24_orcamforne = $iCodigoFornecedor;
    $oDaopcOrcamJulg->pc24_orcamitem  = $iCodigoItemOrcamento;
    $oDaopcOrcamJulg->pc24_pontuacao  = 1;
    $oDaopcOrcamJulg->incluir($iCodigoItemOrcamento, $iCodigoFornecedor);
    if ($oDaopcOrcamJulg->erro_status == 0) {
      
      $sErroMsg  = "N�o foi poss�vel incluir item\\n";
      $sErroMsg .= "Erro:$oDaopcOrcamJulg->erro_msg";
      throw new Exception($sErroMsg);
      
    }
  }
  
  function removeItensRegitro($iRegistroPreco, $iSolicitem) {

    $iCodigoProcesso      = null;
    $iCodigoItemProcesso  = null;
    $iCodigoOrcamento     = null;
    $iCodigoItemOrcamento = null;
    $iCodigoFornecedor    = null;
    $oDaoPcProc           = new cl_pcproc;
    $oDaoPcProcItem       = new cl_pcprocitem;
    $oDaoPcOrcam          = new cl_pcorcam;
    $oDaopcOrcamItem      = new cl_pcorcamitem; 
    $oDaopcOrcamForne     = new cl_pcorcamforne; 
    $oDaopcOrcamItemProc  = new cl_pcorcamitemproc; 
    $oDaopcOrcamJulg      = new cl_pcorcamjulg;
    $oDaopcOrcamVal       = new cl_pcorcamval; 
    $oDaoSolicitemVinculo = db_utils::getDao("solicitemvinculo");
   /**
     * Devemos vincular o item da solicitacao ao registro de pre�o 
     */
    $sSqlVerificaVinculo  = $oDaoSolicitemVinculo->sql_query_file(null, 
                                                                  "*", 
                                                                  null,
                                                                  "pc55_solicitemfilho={$iSolicitem}"
                                                                  );
    $rsVerificaVinculo    = $oDaoSolicitemVinculo->sql_record($sSqlVerificaVinculo);
    if ($oDaoSolicitemVinculo->numrows > 0) {
      
      $oDaoSolicitemVinculo->excluir(null,"pc55_solicitemfilho={$iSolicitem}");                                                                  
    }
    $sSqlVerificaProcesso = $oDaoPcProcItem->sql_query_file(null,"*", null,"pc81_solicitem={$iSolicitem}");
    $rsVerificaProcesso   =  $oDaoPcProcItem->sql_record($sSqlVerificaProcesso);
    if ($oDaoPcProcItem->numrows > 0) {
      
      $oProcessoItem = db_utils::fieldsMemory($rsVerificaProcesso, 0);
      /**
       * Verificamos o oitem do orcamento .
       */
      $sSqlOrcamItem = $oDaopcOrcamItemProc->sql_query_orcam(null,null,
                                                             "*",null,
                                                             "pc31_pcprocitem={$oProcessoItem->pc81_codprocitem}"
                                                            );
      $rsOrcamItem   = $oDaopcOrcamItemProc->sql_record($sSqlOrcamItem);
      if ($oDaopcOrcamItemProc->numrows > 0 ) {

        $oOrcamentoItem       = db_utils::fieldsMemory($rsOrcamItem, 0);
        $iCodigoItemOrcamento = $oOrcamentoItem->pc22_orcamitem;
        $iCodigoOrcamento     = $oOrcamentoItem->pc22_codorc;
        /**
         * excluimos o julgamento e os valores or�ados do para o item 
         */
        $oDaopcOrcamJulg->excluir($iCodigoItemOrcamento);
        if ($oDaopcOrcamJulg->erro_status == 0) {
          
          $sErroMsg  = "N�o foi poss�vel Excluir item julgado \\n";
          $sErroMsg .= "Erro:$oDaopcOrcamJulg->erro_msg";
          throw new Exception($sErroMsg);
          
        }
        
        $oDaopcOrcamVal->excluir(null,$iCodigoItemOrcamento);
        if ($oDaopcOrcamVal->erro_status == 0) {
          
          $sErroMsg  = "N�o foi poss�vel Excluir item julgado \\n";
          $sErroMsg .= "Erro:$oDaopcOrcamVal->erro_msg";
          throw new Exception($sErroMsg);
          
        }
        $oDaopcOrcamItemProc->excluir($iCodigoItemOrcamento);
        if ($oDaopcOrcamItemProc->erro_status == 0) {
          
          $sErroMsg  = "N�o foi poss�vel Excluir item julgado \\n";
          $sErroMsg .= "Erro:$oDaopcOrcamItemProc->erro_msg";
          throw new Exception($sErroMsg);
          
        }
        
        $oDaopcOrcamItem->excluir($iCodigoItemOrcamento);
        if ($oDaopcOrcamItem->erro_status == 0) {
          
          $sErroMsg  = "N�o foi poss�vel Excluir item julgado \\n";
          $sErroMsg .= "Erro:$oDaopcOrcamItem->erro_msg";
          throw new Exception($sErroMsg);
          
        }
        /**
         * Verificamos se ainda existem itens no orcamento
         */
        $sSqlItensOrcamento = $oDaopcOrcamItem->sql_query_file(null,"1",null,"pc22_codorc = {$iCodigoItemOrcamento}");
        $rsItensOrcamento   = $oDaoPcProcItem->sql_record($sSqlItensOrcamento);
        if ($oDaopcOrcamItem->numrows == 0) {
          
          $oDaopcOrcamForne->excluir(null,"pc21_codorc = {$iCodigoOrcamento}");
          if ($oDaopcOrcamForne->erro_status == 0) {
          
            $sErroMsg  = "N�o foi poss�vel Excluir item julgado \\n";
            $sErroMsg .= "Erro:$oDaopcOrcamForne->erro_msg";
            throw new Exception($sErroMsg);
          
          }   
          $oDaoPcOrcam->excluir($iCodigoItemOrcamento);
          if ($oDaopcOrcam->erro_status == 0) {
          
            $sErroMsg  = "N�o foi poss�vel Excluir item julgado \\n";
            $sErroMsg .= "Erro:$oDaopcOrcam->erro_msg";
            throw new Exception($sErroMsg);
          }   
        }
        
        $oDaoPcProcItem->excluir($oProcessoItem->pc81_codprocitem);
        if ($oDaoPcProcItem->erro_status == 0) {
          
          $sErroMsg  = "N�o foi poss�vel Excluir item julgado \\n";
          $sErroMsg .= "Erro:$oDaoPcProcItem->erro_msg";
          throw new Exception($sErroMsg);
        }
        /**
         * Verificamos se o processo de compras ainda possui itens.
         * se n�o possuir, o excluimos
         */
        $sSqlProcesso = $oDaoPcProcItem->sql_query_file(null,"1",null,"pc81_codproc = {$oProcessoItem->pc81_codproc}");
        $rsProcesso   = $oDaoPcProcItem->sql_record($sSqlProcesso);
        if ($oDaopcOrcamItem->numrows == 0) {
          
          $oDaoPcProc->excluir($oProcessoItem->pc81_codproc);
          $sErroMsg  = "N�o foi poss�vel Excluir processo de compras \\n";
          $sErroMsg .= "Erro:$oDaoPcProc->erro_msg";
          throw new Exception($sErroMsg);
        }
      }
    }
  }
  
  /**
   * 
   */
  public function alterarItemRegistroPreco ($iCodigoItemSolicitacao, $iMaterial, $nQuantidade) {
    
    
    $oDaoSolicitemVinculo = new cl_solicitemvinculo();
    $sSqlRegistroPreco    = $oDaoSolicitemVinculo->sql_query(null, 
                                                            "pc10_numero,
                                                             pc11_codigo", 
                                                             null, 
                                                            "pc55_solicitemfilho = {$iCodigoItemSolicitacao}");
                                                                  
    $rsRegistroPreco = $oDaoSolicitemVinculo->sql_record($sSqlRegistroPreco);
    if ($oDaoSolicitemVinculo->numrows > 0) {
      
      $oDaoSolicitem  = new cl_solicitem;
      $sSqlDadosItem  = $oDaoSolicitem->sql_query_file($iCodigoItemSolicitacao, "pc11_quant");
      $rsDadosItem    = $oDaoSolicitem->sql_record($sSqlDadosItem); 
      $nQuantidadeAnterior = db_utils::fieldsMemory($rsDadosItem, 0)->pc11_quant;
         
      $oDadosItensRP    = db_utils::fieldsMemory($rsRegistroPreco, 0);
      require_once("model/compilacaoRegistroPreco.model.php");
      $oCompilacao = new compilacaoRegistroPreco($oDadosItensRP->pc10_numero);
      $oItem       = $oCompilacao->getItemByCodigo($oDadosItensRP->pc11_codigo);
      /**
       * Buscamos o C�digo do item da Estimativa do departamento. caso nao exista devemos lan�ar uma excess�o
       * verificamos se o item possui saldo para solicitar
       */
      $sSqlItemEstimativa  = "select pc11_codigo"; 
      $sSqlItemEstimativa .= "  from solicitemvinculo"; 
      $sSqlItemEstimativa .= "       inner join solicitem on pc55_solicitempai = pc11_codigo"; 
      $sSqlItemEstimativa .= "       inner join solicita  on pc10_numero       = pc11_numero"; 
      $sSqlItemEstimativa .= " where pc10_depto = ".db_getsession("DB_coddepto"); 
      $sSqlItemEstimativa .= "   and pc55_solicitemfilho = {$oDadosItensRP->pc11_codigo}";
      $rsItemEstimativa    = db_query($sSqlItemEstimativa);
      if (pg_num_rows($rsItemEstimativa) <> 1) {
  
        $sErro  = "Departamento ".db_getsession("DB_coddepto")." Nao possui estimativa lan�ada para ";
        $sErro .= "item {$oItem->getDescricaoMaterial()}";
        throw new Exception($sErro);
      }
      $iCodigoItemEstimativa = db_utils::fieldsMemory($rsItemEstimativa, 0)->pc11_codigo;
      $oItemEstimativa      = new ItemEstimativa($iCodigoItemEstimativa);
      $oSaldosItem          = $oItemEstimativa->getMovimentacao();
      if ($nQuantidade > ($oSaldosItem->saldo + $nQuantidadeAnterior)) {
        
        $sErroMsg  = "N�o foi poss�vel alterar item\n";
        $sErroMsg .= "Saldo solicitado do item ".urldecode($oItem->getDescricaoMaterial())." maior que o dispon�vel.\n";
        $sErroMsg .= "Saldo Disponivel no Departamento: ".($oSaldosItem->saldo+$nQuantidadeAnterior);
        throw new Exception($sErroMsg); 
      }
      
      /**
       * alteramos os dados do orcamento do item . 
       */
      $oDadosFornecedor  = $oCompilacao->getFornecedorItem($iMaterial, $oDadosItensRP->pc11_codigo);
      $oDaoItemOrcamento = db_utils::getDao("pcorcamval");
      $sSqlOrcamento     = $oDaoItemOrcamento->sql_query_valor_rp(null,
                                                                  null,
                                                                  "pc23_orcamforne, pc23_orcamitem",
                                                                  null,
                                                                  "pc81_solicitem = {$iCodigoItemSolicitacao}"
                                                                  );
      $rsDadosOrcamento  = $oDaoItemOrcamento->sql_record($sSqlOrcamento);
      $oDadosOrcamento   = db_utils::fieldsMemory($rsDadosOrcamento, 0);

      $oDaoItemOrcamento->pc23_quant      = $nQuantidade;
      $oDaoItemOrcamento->pc23_vlrun      = $oDadosFornecedor->valorunitario;
      $oDaoItemOrcamento->pc23_valor      = round($oDadosFornecedor->valorunitario*$nQuantidade, 2);
      $oDaoItemOrcamento->pc23_orcamforne = $oDadosOrcamento->pc23_orcamforne;
      $oDaoItemOrcamento->pc23_orcamitem  = $oDadosOrcamento->pc23_orcamitem;
      $oDaoItemOrcamento->alterar($oDadosOrcamento->pc23_orcamforne, $oDadosOrcamento->pc23_orcamitem);
      if ($oDaoItemOrcamento->erro_status == 0) {
        throw new Exception("N�o foi poss�vel alterar item.\nErro ao alterar dados do or�amento.");
      }
    }
  }
  
  /**
   * Retorna o c�digo da solicita��o
   *
   * @return integer
   */
  public function getCodigo() {
    return $this->iSolicitacao;
  }
  
  /**
   * Busca todos os itens de uma solicita�ao para gerar uma autoriza��o de empenho
   * @return array ($aItens)
   */
  public function getItensParaAutorizacao() {

    $oDaoPcOrcamJulg      = db_utils::getDao("pcorcamjulg");
    $oDaoOrcReservaSol    = db_utils::getDao("orcreservasol");
    $this->oDaoParametros = db_utils::getDao("empparametro");
    $oDaoSolicita         = db_utils::getDao("solicita");
    
    /**
     * 
     * Verifica se h� fornecedor julgado, 
     * Se houver, deve filtra a query sql_query_gerautsol buscando somente o fornecedor vencedor
     */
    $oPcOrcamento                  = db_utils::getDao('pcorcam');
    $sWhereBuscaFornecedorJulgado  = "pc11_numero = {$this->getCodigo()} and pc24_pontuacao = 1";
    $sSqlBuscaFornecedorJulgado    = $oPcOrcamento->sql_query_valitemjulgsol(null, "pc24_pontuacao", null, $sWhereBuscaFornecedorJulgado);
    $rsBuscaFornecedorJulgado      = $oPcOrcamento->sql_record($sSqlBuscaFornecedorJulgado);
    $iBuscaFornecedorJulgado       = $oPcOrcamento->numrows;
    
    $sCampos  = "pc11_seq,";
    $sCampos .= "pc01_codmater   as codigomaterial,";
    $sCampos .= "pc01_descrmater as descricaomaterial,";
    $sCampos .= "pc01_servico    as servico,";
    $sCampos .= "pc11_quant      as quanttotalitem,";
    $sCampos .= "pc11_vlrun      as valorunitario,";
    $sCampos .= "pc11_numero,                             ";
    $sCampos .= "pc11_codigo     as codigoitemsolicitacao,";
    $sCampos .= "pc13_coddot     as codigodotacao,    ";
    $sCampos .= "pc13_sequencial as codigodotacaoitem,";
    $sCampos .= "pc13_quant      as quanttotaldotacao,";
    $sCampos .= "pc13_anousu     as anodotacao,       ";
    $sCampos .= "pc13_valor      as valordotacao,     ";
    $sCampos .= "pc17_unid,                           ";
    $sCampos .= "pc17_quant,                          ";
    $sCampos .= "pc23_orcamforne,";
    $sCampos .= "pc23_valor      as valorfornecedor,";
    $sCampos .= "case when trim(pc11_resum) <> '' then pc11_resum";
    $sCampos .= "     else pc10_resumo ";
    $sCampos .= " end as observacao,";    
    $sCampos .= "pc10_resumo as observacao_solicita,";
    $sCampos .= "pc23_vlrun as valorunitariofornecedor,";
    $sCampos .= "pc23_quant as quantfornecedor,";
    $sCampos .= "z01_numcgm as codigofornecedor,";
    $sCampos .= "z01_nome as fornecedor,";
    $sCampos .= "m61_descr,";
    $sCampos .= "m61_usaquant,";
    $sCampos .= "pc10_numero as codigosolicitacao,";
    $sCampos .= "pc19_orctiporec as contrapartida,";
    $sCampos .= "pc81_codprocitem as codigoitemprocesso,";
    $sCampos .= "pc22_orcamitem,";
    $sCampos .= "pc18_codele as codigoelemento,";
    $sCampos .= "o56_descr as descricaoelemento,";
    $sCampos .= "o56_elemento as elemento, ";
    
    /* 
     * acrescentado campo para controle do servi�o por quantidade
     * renomeado sem a sigla, para diminuir chances de impacto pois o mesmo campo foi
     * criado em outras 2 tabelas com siglas diferentes 
    */
    $sCampos .= "pc11_servicoquantidade  as servicoquantidade "; 
    

    $sOrder   = "z01_numcgm,pc13_coddot,pc18_codele, pc19_sequencial,pc11_seq, pc19_orctiporec,pc13_sequencial";
    $sWhere   = "pc11_numero = {$this->getCodigo()}  and pc10_instit = ".db_getsession("DB_instit");
    
    if ($iBuscaFornecedorJulgado > 0) {
      $sWhere .= " and pcorcamjulg.pc24_pontuacao = 1";
    }

    $sSqlSolicitacao = $oDaoSolicita->sql_query_gerautsol(null, $sCampos, $sOrder, $sWhere);
    $rsSolicitacao   = $oDaoSolicita->sql_record($sSqlSolicitacao);
    $iRowSolicitacao = $oDaoSolicita->numrows;
    $aItens          = array();
    if ($iRowSolicitacao > 0) {
      
      $oDaoFornecedorSugerido = db_utils::getDao("pcsugforn");
      $sSqlBuscaFornecedor    = $oDaoFornecedorSugerido->sql_query_dados_fornecedor($this->getCodigo());
      $rsBuscaFornecedor      = $oDaoFornecedorSugerido->sql_record($sSqlBuscaFornecedor);
      $iRowsFornecedor        = $oDaoFornecedorSugerido->numrows;
      
      /**
       * @todo ver com henrique, mensagem de erro
       */
      for ($i = 0; $i < $iRowSolicitacao; $i++) {
        
        $oDados = db_utils::fieldsMemory($rsSolicitacao, $i, false, false, true);
        
        if ($oDados->codigofornecedor == "") {

          if ($iRowsFornecedor > 1) {
            throw new Exception("Existe mais de um fornecedor sugerido para esta solicita��o. � necess�rio apenas um fornecedor.");
          } else if ($iRowsFornecedor == 0) {
            throw new Exception("N�o existe or�amento julgado nem fornecedor sugerido para esta solicita��o.");
          } 
          $oDadosFornecedor = db_utils::fieldsMemory($rsBuscaFornecedor, 0);
          $oDados->codigofornecedor        = $oDadosFornecedor->z01_numcgm;
          $oDados->fornecedor              = $oDadosFornecedor->z01_nome;
          $oDados->valorfornecedor         = $oDados->valordotacao;
          $oDados->quantfornecedor         = $oDados->quanttotaldotacao;
          $oDados->valorunitariofornecedor = $oDados->valorfornecedor / $oDados->quantfornecedor;
        }
        /*
         * calcula o percentual da dota��o em relacao ao valor total
         */ 
        $nPercentualDotacao = 100;
        if ($oDados->valorunitario > 0 && !$oDados->servico) {
          $nPercentualDotacao = ($oDados->valordotacao*100)/($oDados->quanttotalitem*$oDados->valorunitario);
        }
        $oDados->percentual = $nPercentualDotacao;
        /**
         * retorna o valor novo da dotacao; (pode ter um aumento/diminui��o do valor)
         */
        $nValorDotacao          = round(($oDados->valorfornecedor * $nPercentualDotacao)/100, 2);
        $oDados->valordiferenca = $nValorDotacao;
        
        /**
         * Verificamos o valor reservado para o item
         */ 
        $sSqlReservaDotacao    = $oDaoOrcReservaSol->sql_query_orcreserva(
                                                                       null,
                                                                       null,
                                                                       "o80_codres,o80_valor", 
                                                                       "", 
                                                                       "o82_pcdotac = {$oDados->codigodotacaoitem}");
        $rsReservaDotacao          = $oDaoOrcReservaSol->sql_record($sSqlReservaDotacao);
        $oDados->valorreserva      = 0;
        $oDados->dotacaocomsaldo   = true;
        $oDados->saldofinaldotacao = 0;

        if ($oDaoOrcReservaSol->numrows == 1) {
          $oDados->valorreserva = db_utils::fieldsMemory($rsReservaDotacao, 0)->o80_valor;
        }

        $oDados->quantidadeautorizada = 0; 
        $oDados->valorautorizado      = 0;
        $oDados->saldoautorizar       = $oDados->valordotacao;
        if (!empty($oDados->codigoitemprocesso)) {
          
          $oValoresAutorizados          = $this->getValoresParciais($oDados->codigoitemprocesso,
                                                                    $oDados->codigodotacao,
                                                                    $oDados->contrapartida);
                                                                    
          $oDados->quantidadeautorizada = $oValoresAutorizados->iQuantidadeAutorizacao; 
          $oDados->valorautorizado      = $oValoresAutorizados->nValorAutorizacao;
          $oDados->saldoautorizar       = $oValoresAutorizados->nValorSaldoTotal;
        }
        $oDotacao                     = new Dotacao($oDados->codigodotacao, $oDados->anodotacao);
        $oDados->saldofinaldotacao    = $oDotacao->getSaldoAtualMenosReservado();
        $oDados->servico              = $oDados->servico=='t'?true:false;

        /**
         * Verifica se a dota��o tem saldo para poder autorizar o item 
         */
        $nSaldoAtualReserva = $oDotacao->getSaldoAtualMenosReservado() + $oDados->valorreserva;
        
        if ($nSaldoAtualReserva <= 0 && $oDados->valorreserva == 0) {
          $oDados->dotacaocomsaldo = false;          
        }
        
        $nValorUnitario = $oDados->valorunitario;
        $oDados->saldoquantidade      = $oDados->quanttotaldotacao - $oDados->quantidadeautorizada;
        if ($oDados->saldoquantidade < 1  && $oDados->saldoquantidade > 0) {
        	$nValorUnitario = $nValorUnitario = $oDados->valorunitario * $oDados->saldoquantidade;
        }
        if (($nSaldoAtualReserva) < $nValorUnitario && $oDados->servico == false) {
        	
          $oDados->dotacaocomsaldo = false;          
          if ($oDados->valorreserva >= $nValorUnitario) {
            $oDados->dotacaocomsaldo = true;
          }
        }
        /**
         * Verificamos as quantidades executadas do item
         */
        $oDados->saldovalor           = $oDados->valordiferenca    - $oDados->valorautorizado;
        
        if ($oDados->servicoquantidade == "f") {
          
          if ($oDados->servico) {
            $oDados->saldoquantidade = 1;
          }
        }
        
        $oDados->autorizacaogeradas = array();
        if (!empty($oDados->codigoitemprocesso)) {
          $oDados->autorizacaogeradas  = licitacao::getAutorizacoes($oDados->codigoitemprocesso, $oDados->codigodotacao);
        }
        /**
         * busca o parametro de casas decimais para formatar o valor jogado na grid
         */
        $iAnoSessao             = db_getsession("DB_anousu");
        $sWherePeriodoParametro = " e39_anousu = {$iAnoSessao} ";
        $sSqlPeriodoParametro   = $this->oDaoParametros->sql_query_file(null, "e30_numdec", null, $sWherePeriodoParametro);
        $rsPeriodoParametro     = $this->oDaoParametros->sql_record($sSqlPeriodoParametro);
        
        $iNumDec = 2;
        if ($this->oDaoParametros->numrows > 0) {
          
          $iNumDec =  (int)db_utils::fieldsMemory($rsPeriodoParametro, 0)->e30_numdec;
          
        }
        $oDados->valorunitariofornecedor = number_format((float)$oDados->valorunitariofornecedor, 
                                                        $iNumDec, 
                                                         '.','');
        $aItens[] = $oDados;
        
      }
    }
    
    return $aItens;
  }
  
  /**
   * Retorna o valor total parcial da licitacao
   *
   * @param integer_type $iCodigoItemProcesso
   * @param integer_type $iCodigoDotacao
   * @param integer_type $iOrcTipoRec
   * @return $oDadoValorParcial
   */
  public function getValoresParciais($iCodigoItemProcesso, $iCodigoDotacao, $iOrcTipoRec=null) {
  	
    if (empty($iCodigoItemProcesso)) {
      throw new Exception("C�digo do item do processo n�o informado!");
    }
  	
    if (empty($iCodigoDotacao)) {
      throw new Exception("C�digo da dota��o n�o informado!");
    }
    
    /**
     * Retorna somentes as autorizacoes das contrapartidas
     */
    $sWhereContrapartida = " and e56_orctiporec is null";
    if (!empty($iOrcTipoRec)) {
      $sWhereContrapartida = " and e56_orctiporec = {$iOrcTipoRec}"; 
    }
    
    $oDaoEmpAutItem    = db_utils::getDao("empautitem");
  	$oDaoPcOrcam       = db_utils::getDao("pcorcam");
  	  	
  	$oDadoValorParcial = new stdClass();
  	$oDadoValorParcial->nValorAutorizacao      = 0;
    $oDadoValorParcial->iQuantidadeAutorizacao = 0;
    $oDadoValorParcial->nValorItemJulgado      = 0;
    $oDadoValorParcial->iQuantidadeItemJulgado = 0;
  	
    /**
     * Retorna o valor total da autorizacao de empenho da licitacao
     */
    $sCampos           = "sum(e55_vltot) as valorautorizacao,               "; 
    $sCampos          .= "sum(e55_quant) as quantidadeautorizacao           ";
    $sWhere            = "          e73_pcprocitem = {$iCodigoItemProcesso} ";
    $sWhere           .= "      and e56_coddot     = {$iCodigoDotacao}      ";
    $sWhere           .= "      and e54_anulad is null                      ";
    $sWhere           .= "      {$sWhereContrapartida}                      ";   
    $sWhere           .= " group by e55_vltot,                              ";
    $sWhere           .= "          e55_quant                               ";
    $sSqlAutorizacao   = $oDaoEmpAutItem->sql_query_itemdot(null, null, $sCampos, null, $sWhere);
    $rsSqlAutorizacao  = $oDaoEmpAutItem->sql_record($sSqlAutorizacao);
    if ($oDaoEmpAutItem->numrows > 0) {
    	
    	for ($iIndEmpAutItem = 0; $iIndEmpAutItem < $oDaoEmpAutItem->numrows; $iIndEmpAutItem++) {
    		
	  	  $oAutorizacao                               = db_utils::fieldsMemory($rsSqlAutorizacao, $iIndEmpAutItem);
	  	  $oDadoValorParcial->nValorAutorizacao      += $oAutorizacao->valorautorizacao; 
	      $oDadoValorParcial->iQuantidadeAutorizacao += $oAutorizacao->quantidadeautorizacao;
    	}
    }
    
    /**
     * Retorna o valor do item julgado na licitacao
     */
  	$sCampos              = "pc23_quant, pc23_valor, pc13_valor, pc13_quant, pc11_vlrun, pc11_quant";
  	$sWhere               = "pc81_codprocitem = {$iCodigoItemProcesso} and pc24_pontuacao = 1";
  	$sWhere              .= " and pc13_coddot  = {$iCodigoDotacao} ";
  	$sWhereContrapartida  = " and pc19_orctiporec is null "; 
    if ($iOrcTipoRec > 0) {
      $sWhereContrapartida = "  and pc19_orctiporec = {$iOrcTipoRec} ";  
    }
    $sWhere .= $sWhereContrapartida;
    $sSqlPcOrcam       = $oDaoPcOrcam->sql_query_valitemjulgsol(null, $sCampos, null, $sWhere);
  	$rsSqlPcOrcam      = $oDaoPcOrcam->sql_record($sSqlPcOrcam);
  	if ($oDaoPcOrcam->numrows > 0) {
  		
  		for ($iIndPcOrcam = 0; $iIndPcOrcam < $oDaoPcOrcam->numrows; $iIndPcOrcam++) {
  			
	  	  $oItemJulgado                               = db_utils::fieldsMemory($rsSqlPcOrcam, $iIndPcOrcam);
	  	  $nPercentualDotacao = ($oItemJulgado->pc13_valor * 100) / 
	  	                        ($oItemJulgado->pc11_quant * $oItemJulgado->pc11_vlrun);
        /**
         * retorna o valor novo da dotacao; (pode ter um aumento/diminui��o do valor)
         */
        $nValorDotacao          = round(($oItemJulgado->pc23_valor * $nPercentualDotacao) / 100, 2);
        $oDados->valordiferenca = $nValorDotacao;
		    $oDadoValorParcial->nValorItemJulgado      += $nValorDotacao;
		    $oDadoValorParcial->iQuantidadeItemJulgado += $oItemJulgado->pc23_quant;
  		}
  	} else {
  	  
  	  /**
  	   * validamos o saldo do item pelo cadastro da solicitacao. 
  	   * caso nao exista o julgamento, a solicitacao possui fonecedor sugerido;
  	   */
  	  $oDaoItemProcesso = db_Utils::getDao("pcprocitem");
  	  $sSqlItem         = $oDaoItemProcesso->sql_query($iCodigoItemProcesso, 
  	                                                   "pc11_quant, pc11_vlrun, 
  	                                                   (pc11_quant*pc11_vlrun) as valor_total"
  	                                                   ); 
  	  $rsItemProcesso = $oDaoItemProcesso->sql_record($sSqlItem);
  	  if ($oDaoItemProcesso->numrows > 0) {
  	    
  	    $oDadosItemProcesso                        = db_utils::fieldsMemory($rsItemProcesso, 0);
  	    $oDadoValorParcial->iQuantidadeItemJulgado = $oDadosItemProcesso->pc11_quant;
  	    $oDadoValorParcial->nValorItemJulgado      = $oDadosItemProcesso->valor_total;
  	  }
  	}
    $oDadoValorParcial->nValorSaldoTotal = ( $oDadoValorParcial->nValorItemJulgado 
                                           - $oDadoValorParcial->nValorAutorizacao);
    return $oDadoValorParcial;
  }
  
  
  /**
   * Gera a autoriza��o de empenho para uma solicita��o de compras
   * @param array $aDadosAutorizacao
   */
  public function gerarAutorizacoes($aDadosAutorizacao) {
    
    $aAutorizacoes   = array();
    $oProcessoCompra = new ProcessoCompras();
    $oProcessoCompra->setCodigoDepartamento(db_getsession("DB_coddepto"));
    $oProcessoCompra->setDataEmissao(date("Y-m-d", db_getsession("DB_datausu")));
    $oProcessoCompra->setResumo("Processo de compras autom�tico");
    $oProcessoCompra->setSituacao(2);
    $oProcessoCompra->setUsuario(db_getsession("DB_id_usuario"));
    /**
     * calcular reservas para a Solicitacao (quando parcial)
     * calcular orcreserva
     */
    $oDaoOrcReservaSol = db_utils::getDao("orcreservasol");
    $oDaoOrcReserva    = db_utils::getDao("orcreserva");
    $oDaoPcdotac       = db_utils::getDao("pcdotac");
    $oDaoSolicitem     = db_utils::getDao("solicitem");
    
    /**
     * Percorre os ITENS da autoriza��o para criar um processo de compra caso n�o exista.
     */
    $sWhere          = "     pc11_numero = {$this->getCodigo()}";
    $sWhere         .= " and pc81_codprocitem is null ";
    $sSqlSolicitem   = $oDaoSolicitem->sql_query_item_processo_compras(null, 
                                                                        "pc11_codigo as pc81_solicitem", 
                                                                        "pc11_seq", 
                                                                        $sWhere
                                                                        );
    $rsSolicitem     = $oDaoSolicitem->sql_record($sSqlSolicitem);
    $iRowSolicitem   = $oDaoSolicitem->numrows;
  
    for ($iRow = 0; $iRow < $iRowSolicitem; $iRow++) {
       
      $oItem = db_utils::fieldsMemory($rsSolicitem, $iRow);
      $oProcessoCompra->adicionarItem($oItem);
    }
    
    
    /**
     * Caso existam itens setados, ser� inclu�do um processo de compras
     */
    if (count($oProcessoCompra->getItens()) > 0) {
      $oProcessoCompra->salvar();
    }
    
    /**
     * Criamos um orcamento para os itens que nao possuem orcamento lan�ado
     * 
     */
     
    //echo ("BBBB<pre>".print_r($aDadosAutorizacao, 1)."</pre>"); die();
    
    foreach ($aDadosAutorizacao as $oDados) {
      
      $nValorTotal = 0;
      foreach ($oDados->itens as $oItem) {

        $nValorTotal += $oItem->valortotal;
        /**
         * verificamos se exite reserva de saldo para a solicitacao;
         * caso exista, devemos calcular a diferen�a entre o que deve ser gerado para a autorizacao e a solictacao
         */
        
        $aReservas = itemSolicitacao::getReservasSaldoDotacao($oItem->pcdotac);
        
        $nNovoValorReserva = $oItem->valortotal;
        
        if (count($aReservas)  > 0) {
          
          $nNovoValorReserva   = $aReservas[0]->valor - $oItem->valortotal;
          if ($nNovoValorReserva < 0) {
            $nNovoValorReserva = 0;  
          }
          
          /**
           * excluirmos a reserva e incluimos uma nova 
           */
          $oDaoOrcReservaSol->excluir(null, "o82_codres = {$aReservas[0]->codigoreserva}");
          if ($oDaoOrcReservaSol->erro_status == 0) {
            throw new Exception($oDaoOrcReservaSol->erro_msg);
          }
          
          /**
           * Excluir OrcReserva
           */
          $oDaoOrcReserva->excluir($aReservas[0]->codigoreserva);
          if ($oDaoOrcReserva->erro_status == 0) {
            throw new Exception($oDaoOrcReserva->erro_msg);          
          }
        }  
        /**
         * Caso a propriedade $oItem->codigoprocesso venha vazia, ser� criado um processo de compras para o item
         */
        $iCodigoProcessoCompra = $oItem->codigoprocesso;
        if (empty($oItem->codigoprocesso)) {
          
          foreach ($oProcessoCompra->getItens() as $oItemProcesso) {
             
            if ($oItemProcesso->pc81_solicitem == $oItem->solicitem) {
              $oItem->codigoprocesso = $oItemProcesso->pc81_codprocitem;
            }
          }
        }
        
        /**
         * Inclu�mos os dados na OrcReserva, caso o item ainda tenha valor dispo
         */
        $oSaldo = $this->getValoresParciais($oItem->codigoprocesso, 
                                            $oDados->dotacao,
                                            $oDados->contrapartida
                                           );
        if ($nNovoValorReserva > 0 && ($oSaldo->nValorAutorizacao + $oItem->valortotal < $oSaldo->nValorItemJulgado)) {
          
          $oDaoOrcReserva->o80_anousu = db_getsession("DB_anousu");
          $oDaoOrcReserva->o80_coddot = $oDados->dotacao;
          $oDaoOrcReserva->o80_dtfim  = db_getsession("DB_anousu")."-12-31";
          $oDaoOrcReserva->o80_dtini  = date("Y-m-d", db_getsession("DB_datausu"));
          $oDaoOrcReserva->o80_dtlanc = date("Y-m-d", db_getsession("DB_datausu"));
          $oDaoOrcReserva->o80_valor  = $nNovoValorReserva;
          $oDaoOrcReserva->o80_descr  = "Reserva item Solicitacao";
          $oDaoOrcReserva->incluir(null);
          
          if ($oDaoOrcReserva->erro_status == 0) {
            
            $sMsgErro  = "N�o foi possivel gerar reserva para a dota��o: {$oDados->dotacao}.\n";
            $sMsgErro .= $oDaoOrcReserva->erro_msg;
            throw new Exception($sMsgErro);                    
          }
          
          $oDaoOrcReservaSol->o82_codres    = $oDaoOrcReserva->o80_codres;
          $oDaoOrcReservaSol->o82_pcdotac   = $oDados->pcdotac;
          $oDaoOrcReservaSol->o82_solicitem = $oItem->solicitem;
          $oDaoOrcReservaSol->incluir(null);
          if ($oDaoOrcReservaSol->erro_status == 0) {
            
            $sMsgErro  = "N�o foi possivel gerar reserva para a dota��o: {$oDados->dotacao}.\n";
            $sMsgErro .= $oDaoOrcReservaSol->erro_msg;
            throw new Exception($sMsgErro);                    
          }
        }
      }
      /**
       * Salvamos a Autorizacao;
       */
      
      /*
       * Resumo da autoriza��o
       * 
       */
      $rsPcdotac = $oDaoPcdotac->sql_record($oDaoPcdotac->sql_query_solicita(null, 
      																																			 null, 
      																																			 null, 
      																																			 "pc10_resumo", 
      																																			 null, 
      																																			 "pc13_sequencial = {$oItem->pcdotac}"));
      
      $sResumo   = $oDaoPcdotac->numrows > 0 ? db_utils::fieldsMemory($rsPcdotac, 0)->pc10_resumo : $oDados->resumo;
      
      $oAutorizacao = new AutorizacaoEmpenho();
      $oAutorizacao->setDesdobramento($oDados->elemento);
      $oAutorizacao->setDotacao($oDados->dotacao);
      $oAutorizacao->setContraPartida($oDados->contrapartida);
      
      $oFornecedor  = CgmFactory::getInstanceByCgm($oDados->cgm);
      $oAutorizacao->setFornecedor($oFornecedor);
      $oAutorizacao->setValor($nValorTotal);
      $oAutorizacao->setTipoEmpenho($oDados->tipoempenho);
      $oAutorizacao->setCaracteristicaPeculiar($oDados->concarpeculiar);
      
      $aItemSolcitem = array();
      
      
      foreach ($oDados->itens as $oItem) {
        
        $oAutorizacao->addItem($oItem);
        $aItemSolcitem[] = $oItem->solicitem;
      }
      
      $oAutorizacao->setDestino($oDados->destino);
      $oAutorizacao->setContato($oDados->sContato);
      $oAutorizacao->setTelefone($oDados->sTelefone);
      $oAutorizacao->setResumo(addslashes($sResumo));
      $oAutorizacao->setTipoCompra($oDados->tipocompra);
      $oAutorizacao->setPrazoEntrega($oDados->prazoentrega);
      $oAutorizacao->setTipoLicitacao($oDados->sTipoLicitacao);
      $oAutorizacao->setNumeroLicitacao($oDados->iNumeroLicitacao);
      $oAutorizacao->setOutrasCondicoes($oDados->sOutrasCondicoes);
      $oAutorizacao->setCondicaoPagamento($oDados->condicaopagamento);
      $oAutorizacao->salvar();
      
      /**
       * Buscar o c�digo do processo da tabela solicitaprotprocesso e incluir na empautorizaprotprocesso caso tenha
       */
      $oDaoSolicitem             = db_utils::getDao("solicitem");
      $sCodigosItens             = implode(",", $aItemSolcitem);
      $sSqlBuscaSolicitem        = $oDaoSolicitem->sql_query_solicitaprotprocesso(null,
                                                                                  "solicitaprotprocesso.*", 
                                                                                  null,
                                                                                  "pc11_codigo in ({$sCodigosItens})");
      $rsBuscaSolicitem          = $oDaoSolicitem->sql_record($sSqlBuscaSolicitem);
      $oDadoSolicitaProtProcesso = db_utils::fieldsMemory($rsBuscaSolicitem, 0);
      
      if (!empty($oDadoSolicitaProtProcesso->pc90_numeroprocesso)) {
      
        $oDaoEmpAutorizaProcesso                      = db_utils::getDao("empautorizaprocesso");
        $oDaoEmpAutorizaProcesso->e150_sequencial     = null;
        $oDaoEmpAutorizaProcesso->e150_empautoriza    = $oAutorizacao->getAutorizacao();
        $oDaoEmpAutorizaProcesso->e150_numeroprocesso = $oDadoSolicitaProtProcesso->pc90_numeroprocesso;
        $oDaoEmpAutorizaProcesso->incluir(null);
        if ($oDaoEmpAutorizaProcesso->erro_status == 0) {
      
          $sMensagemProcessoAdministrativo  = "Ocorreu um erro para incluir o n�mero do processo administrativo ";
          $sMensagemProcessoAdministrativo .= "na autoriza��o de empenho.\n\n{$oDaoEmpAutorizaProcesso->erro_msg}";
          throw new Exception($sMensagemProcessoAdministrativo);
        }
      }
      $aAutorizacoes[] = $oAutorizacao->getAutorizacao();
    }
    return $aAutorizacoes;
  }
  
  /**
   * Busca as solicita��es que tem dota��o do ano anterior.
   * @return mixed
   */
  public function getSolicitacoesDotacaoAnoAnterior() {
    
    $oDaoSolicitem    = db_utils::getDao("solicitem");
    $sWhereDotacao    = "pc11_numero = {$this->getCodigo()} and pc13_anousu < ".db_getsession("DB_anousu");
    $sCamposDotacao   = "distinct pc11_numero as solicita";
    $sSqlBuscaDotacao = $oDaoSolicitem->sql_query_ancoradotorc(null, $sCamposDotacao, null, $sWhereDotacao);
    $rsBuscaDotacao   = $oDaoSolicitem->sql_record($sSqlBuscaDotacao);
    $iRowDotacao      = $oDaoSolicitem->numrows;
    $aSolicitacao     = array();
    
    if ($iRowDotacao > 0) {
      
      for ($iRow = 0; $iRow < $iRowDotacao; $iRow++) {
        
        $iSolicita      = db_utils::fieldsMemory($rsBuscaDotacao, $iRow)->solicita;
        $aSolicitacao[] = $iSolicita;
      }
    }
    return $aSolicitacao;
  }
  
  /**
   * Metodo para retornar os itens de uma solicita��o
   * independente se os itens est�o autorizados ou n�o
   * @return array
   */
  
  public function getItensSolicitacao() {
  	
  	$aItens              = array();
  	$iSolicitao          = $this->getCodigo();
  	$oDaoSolicitem       = db_utils::getDao("solicitem");
  	                   
  	$sCamposItens       = "distinct         ";
  	$sCamposItens		   .= "pc01_codmater,   ";
  	$sCamposItens      .= "pc11_codigo,     ";
  	$sCamposItens      .= "pc11_seq,        ";
  	$sCamposItens	     .= "pc01_descrmater, ";
  	$sCamposItens	     .= "pc11_quant,      ";
  	$sCamposItens	     .= "m61_descr,       ";
  	$sCamposItens	     .= "pc11_vlrun,      ";
  	$sCamposItens	     .= "pc01_servico     ";
  	
  	$sWhereItens        = "pc11_numero = {$iSolicitao} ";
  	
  	$sOrdem             = "pc11_seq, pc01_descrmater ";
  	
  	$sSqlSolicitem      = $oDaoSolicitem->sql_query_rel (null, $sCamposItens, $sOrdem, $sWhereItens);
  	$rsItensSolicitacao = $oDaoSolicitem->sql_record($sSqlSolicitem);
  	if ($oDaoSolicitem->numrows <= 0) {
  		throw new Exception("N�o Foram Encontrados Itens para a Solicita��o: {$iSolicitao} ");
  	}
  	
  	$iTotalItens = $oDaoSolicitem->numrows;
  	
  	for ($iLinha = 0; $iLinha < $iTotalItens; $iLinha++) {
  		
  		$oItens   = db_utils::fieldsMemory($rsItensSolicitacao, $iLinha);
  		$aItens[] = $oItens;
  	}
  	
  	return $aItens;
  }
  
}
?>