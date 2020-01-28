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

define("URL_MENSAGEM_PEDIDOTFD", "saude.tfd.PedidoTFD.");

/**
 * Pedido de TFD
 * 
 * @author Andrio Costa    <andrio.costa@dbseller.com.br>
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 * @package tfd
 * @version $Revision: 1.1 $
 */
final class PedidoTFD {
  
  /**
   * Codigo do Atendimento
   * @var integer
   */
  protected $iCodigo;
  
  /**
   * Medico Solicitante
   * @var Medico
   */
  protected $oMedico;
  
  /**
   * CBO do medico solicitante
   * @var CBO
   */
  protected $oCboSolicitante;
  
  /**
   * Data do agendamento do atendimento
   * @var DBDate
   */
  protected $oDataAgendamento;
  
  /**
   * Acompanhantes do paciente
   * @var Cgs[]
   */
  protected $aAcompanhantes = array();
  
  /**
   * Ajudas De Custo Adicionadas no Pedido
   * @var AjudaCustoPedido[]
   */
  protected $aAjudasDeCusto = array();
  
  /**
   * Paciente do Pedido
   * @var Cgs
   */
  protected $oPaciente;
  
  
  /**
   * Cria uma nova instancia do pedido de TFD
   * @param integer $iCodigoPedido Codigo do pedido
   */
  public function __construct($iCodigo) {
     
    if (!empty($iCodigo)) {

      $sCampos = "tf01_i_codigo, tf01_i_cgsund, tf01_i_profissionalsolic, tf01_rhcbosolicitante, tf16_d_dataagendamento";
      
      $oDaoPedido = new cl_tfd_pedidotfd();
      $sSqlPedido = $oDaoPedido->sql_query_pedido_prestadora($iCodigo, $sCampos);
      $rsPedido   = $oDaoPedido->sql_record($sSqlPedido);
      
      if ($oDaoPedido->numrows == 0) {
        throw new BusinessException(_M(URL_MENSAGEM_PEDIDOTFD."pedido_nao_encontrado"));  
      }
      $oDados = db_utils::fieldsMemory($rsPedido, 0);
      
      $this->iCodigo          = $oDados->tf01_i_codigo;
      $this->oPaciente        = new Cgs($oDados->tf01_i_cgsund);
      $this->oMedico          = new Medico($oDados->tf01_i_profissionalsolic);
      $this->oCboSolicitante  = new CBO($oDados->tf01_rhcbosolicitante);
      $this->oDataAgendamento = new DBDate($oDados->tf16_d_dataagendamento);
    }
  }
    
  /**
   * Retorna o código sequencial  
   * @return integer
   */
  public function getCodigo () {
    return $this->iCodigo;
  }

  /**
   * Define o médico que solicitou o tfd
   * @param Medico $oMedico
   */
  public function setMedico (Medico $oMedico) {
    $this->oMedico = $oMedico;
  }
  
  /**
   * Retorna o médico que solicitou o tfd
   * @return Medico $oMedico
   */
  public function getMedico () {
    return $this->oMedico; 
  }

  /**
   * Define o CBO do médico solicitante
   * @param CBO $oCBO
   */
  public function setCBOSolicitante ($oCBO) {
    $this->oCboSolicitante = $oCBO;
  }
  
  /**
   * Retorna o CBO do médico solicitante
   * @return CBO $oCBO
   */
  public function getCBOSolicitante () {
    return $this->oCboSolicitante; 
  }
  

  /**
   * Define a data de agendamento
   * @param DBDate
   */
  public function setDataAgendamento (DBDate $oData) {
    $this->oDataAgendamento = $oData;
  }
  
  /**
   * Retorna a data de agendamento
   * @return DBDate
   */
  public function getDataAgendamento () {
    return $this->oDataAgendamento; 
  }
 

  /**
   * Define o paciente 
   * @param Cgs $oPaciente
   */
  public function setPaciente ($oPaciente) {
    $this->oPaciente = $oPaciente;
  }
  
  /**
   * Retorna o paciente 
   * @return Cgs $oPaciente
   */
  public function getPaciente () {
    return $this->oPaciente; 
  }

  /**
   * Retorna os acompanhantes para o pedido selecionado
   * @return multitype:Cgs |multitype:
   */
  public function getAcompanhantes() {
  	
    if(count($this->aAcompanhantes) > 0) {
    	return $this->aAcompanhantes;
    }
    
    $sWhere = " tf13_i_pedidotfd = {$this->iCodigo} ";
    
    $oDaoAcompanhente = new cl_tfd_acompanhantes();
    $sSqlAcompanhante = $oDaoAcompanhente->sql_query_file(null, "tf13_i_cgsund", null, $sWhere);
    $rsAcompanhante   = $oDaoAcompanhente->sql_record($sSqlAcompanhante);
    
    $iLinhas = $oDaoAcompanhente->numrows;
    
    if ($iLinhas > 0) {
      
      for ($i = 0; $i < $iLinhas; $i ++) {
        $this->aAcompanhantes[] = new Cgs(db_utils::fieldsMemory($rsAcompanhante, $i)->tf13_i_cgsund);
      }
    }
    
    return $this->aAcompanhantes;
  }
  
  /**
   * Busca as ajudas de custo cadastradas para o paciente e seu(s) acompanhante(s).
   * @return stdClass[]::array
   */
  public function getAjudasDeCusto() {
  	
    if (count($this->aAjudasDeCusto) > 0) {
      return $this->aAjudasDeCusto;
    }
    /**
     * Busca as ajudas para o pedido
     */  
    $sWhere = " tf14_i_pedidotfd = {$this->iCodigo} ";
    
    $oDaoBeneficio         = new cl_tfd_beneficiadosajudacusto();
    $sSqlBeneficioPaciente = $oDaoBeneficio->sql_query2(null, "tfd_beneficiadosajudacusto.*", null, $sWhere);
    $rsBeneficioPaciente   = $oDaoBeneficio->sql_record($sSqlBeneficioPaciente);
    
    $iLinhas = $oDaoBeneficio->numrows;
    
    if ($iLinhas > 0) {
    	
      for ($i = 0; $i < $iLinhas; $i++) {
         
        $oDados = db_utils::fieldsMemory($rsBeneficioPaciente, $i);
      
        $oDadosAjuda                    = new stdClass();
        $oDadosAjuda->lPaciente         = $oDados->tf15_i_cgsund == $this->oPaciente->getCodigo() ? true : false;
        $oDadosAjuda->lFaturaAutomatico = false;
        $oDadosAjuda->iCgs              = $oDados->tf15_i_cgsund;
        $oDadosAjuda->oAjudaCusto       = AjudaCustoRepository::getAjudaCustoByCodigo($oDados->tf15_i_ajudacusto);
      
        $this->aAjudasDeCusto[] = $oDadosAjuda;
      }
    }
    
    return $this->aAjudasDeCusto;
  }
  
}