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

require_once modification("model/ambulatorial/ICompetenciaSaude.interface.php");
define("URL_MENSAGEM_COMPETENCIATFD", "saude.tfd.CompetenciaTFD.");

/**
 * Pedidos de TFP por Competencia
 * @package tfd
 * @author  Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.10 $
 */
class CompetenciaTFD implements ICompetenciaSaude {
	
  /**
   * Codigo de fechamento da competência do TFD
   * @var integar
   */
  private $iCodigo;
  
  /**
   * Hora do fechamento (H:i)
   * @var string
   */
  private $sHora;
  
  /**
   * Descrição informada ao realizar fechamento
   * @var string
   */
  private $sDescricao;
  
  /**
   * Usuário que encerrou a competência
   * @var UsuarioSistema
   */
  private $oUsuarioSistema;

  /**
   * Data do sistema que foi encerrada a competência
   * @var DBDate
   */
  private $oDataInclusao;
  
  /**
   * Período inicial de abrangencia ao encerrar a competência
   * @var DBDate
   */
  private $oPeriodoInicial;
  
  /**
   * Período final de abrangencia ao encerrar a competência
   * @var DBDate
   */
  private $oPeriodoFinal;
  
  /**
   * Competência
   * @var DBCompetencia
   */
  private $oCompetencia;
  
  /**
   * Instancia do Financiamento 
   * @var FinanciamentoSaude
   */
  private $oFinanciamentoSaude;
  
  /**
   * Procedimentos encerrados para Competência
   * @var array
   */
  private $aProcedimentos = array();
  
  /**
   * Lista de filtros usados para buscar os procedimentos
   * @var array
   */
  private $aFiltrosProcedimentos = array();
  
  /**
   * Cria instancia da Competência 
   * Se informado o código da competência, busca os dados da competência
   * @param integer $iCodigo
   */
  public function __construct($iCodigo = null) {
  	
    if (empty($iCodigo)) {
    	return $this;
    }
    
    $oDaoFechamento = new cl_tfd_fechamento();
    $sSqlFechamento = $oDaoFechamento->sql_query_file($iCodigo);
    $rsFechamento   = $oDaoFechamento->sql_record($sSqlFechamento);
    
    if ($oDaoFechamento->numrows == 0) {
    	throw new BusinessException(_M(URL_MENSAGEM_COMPETENCIATFD."competencia_nao_localizada"));
    }

    $oDadosFechamento = db_utils::fieldsMemory($rsFechamento, 0);

    $this->iCodigo             = $oDadosFechamento->tf32_i_codigo;
    $this->sHora               = $oDadosFechamento->tf32_c_horasistema;
    $this->sDescricao          = $oDadosFechamento->tf32_c_descr;
    $this->oUsuarioSistema     = new UsuarioSistema($oDadosFechamento->tf32_i_login);
    $this->oDataInclusao       = new DBDate($oDadosFechamento->tf32_d_datasistema);
    $this->oPeriodoInicial     = new DBDate($oDadosFechamento->tf32_d_datainicio);
    $this->oPeriodoFinal       = new DBDate($oDadosFechamento->tf32_d_datafim);
    $this->oCompetencia        = new DBCompetencia($oDadosFechamento->tf32_i_anocompetencia, $oDadosFechamento->tf32_i_mescompetencia);
    $this->oFinanciamentoSaude = FinanciamentoSaudeRepository::getFinanciamentoSaudeByCodigo($oDadosFechamento->tf32_i_financiamento);
    
  }

  /**
   * Retorna o código 
   * @return integar
   */
  public function getCodigo() {
  	return $this->iCodigo;
  }

  /**
   * Setter Hora
   * @param string
   */
  public function setHora ($shora) {
    $this->sHora = $shora;
  }

  /**
   * Getter Hora
   * @return string
   */
  public function getHora () {
    return $this->sHora; 
  }


  /**
   * Setter descrição da competência
   * @param string
   */
  public function setDescricao ($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Getter descrição da competência
   * @return string
   */
  public function getDescricao () {
    return $this->sDescricao; 
  }


  /**
   * Setter usuário do sistema
   * @param UsuarioSistema
   */
  public function setUsuario (UsuarioSistema $oUsuario) {
    $this->oUsuarioSistema = $oUsuario;
  }

  /**
   * Getter usuário do sistema
   * @return UsuarioSistema
   */
  public function getUsuario () {
    return $this->oUsuarioSistema; 
  }

  /**
   * Setter data que foi realizado o encerramento 
   * @param DBDate $oDtInclusao
   */
  public function setDataInclusao (DBDate $oDtInclusao) {
    $this->oDataInclusao = $oDtInclusao;
  }

  /**
   * Getter data que foi realizado o encerramento 
   * @return DBDate $oDtInclusao
   */
  public function getDataInclusao () {
    return $this->oDataInclusao; 
  }

  /**
   * Setter periodo inicial da competência
   * @param DBDate $oPeriodoInicial
   */
  public function setPeriodoInicial (DBDate $oPeriodoInicial) {
    $this->oPeriodoInicial = $oPeriodoInicial;
  }

  /**
   * Getter periodo inicial da competência
   * @return DBDate $oPeriodoInicial
   */
  public function getPeriodoInicial () {
    return $this->oPeriodoInicial; 
  }

  /**
   * Setter periodo final da competência
   * @param DBDate $oPeriodoFinal
   */
  public function setPeriodoFinal (DBDate $oPeriodoFinal) {
    $this->oPeriodoFinal = $oPeriodoFinal;
  }

  /**
   * Getter periodo final da competência
   * @return DBDate $oPeriodoFinal
   */
  public function getPeriodoFinal () {
    return $this->oPeriodoFinal; 
  }

  /**
   * Setter competência 
   * @param DBCompetencia $oCompetencia
   */
  public function setCompetencia (DBCompetencia $oCompetencia) {
    $this->oCompetencia = $oCompetencia;
  }

  /**
   * Getter competência 
   * @return DBCompetencia $oCompetencia
   */
  public function getCompetencia () {
    return $this->oCompetencia; 
  }

  /**
   * Setter Financiamento encerrado
   * @param FinanciamentoSaude oFinanciamentoSaude
   */
  public function setFinanciamento (FinanciamentoSaude $oFinanciamentoSaude) {
    $this->oFinanciamentoSaude = $oFinanciamentoSaude;
  }

  /**
   * Getter Financiamento encerrado
   * @param FinanciamentoSaude
   */
  public function getFinanciamento () {
    return $this->oFinanciamentoSaude; 
  }

  /**
   * Salva o encerramento da competencia
   */
  public function salvar() {

    $aProcedimentosEncerrados = $this->getPedidosEncerradosNoPeriodo();
    
    if (count($aProcedimentosEncerrados) == 0) {
      throw new BusinessException(_M(URL_MENSAGEM_COMPETENCIATFD."sem_pedidos_a_encerrar_ou_sem_para_financiamento_selecionado"));
    }
    
    $oDaoFechamento                        = new cl_tfd_fechamento();
    $oDaoFechamento->tf32_i_login          = $this->oUsuarioSistema->getIdUsuario();
    $oDaoFechamento->tf32_i_mescompetencia = $this->oCompetencia->getMes();
    $oDaoFechamento->tf32_i_anocompetencia = $this->oCompetencia->getAno();
    $oDaoFechamento->tf32_d_datainicio     = $this->oPeriodoInicial->getDate();
    $oDaoFechamento->tf32_d_datafim        = $this->oPeriodoFinal->getDate();
    $oDaoFechamento->tf32_d_datasistema    = $this->oDataInclusao->getDate();
    $oDaoFechamento->tf32_c_horasistema    = $this->sHora;
    $oDaoFechamento->tf32_c_descr          = $this->sDescricao;
    $oDaoFechamento->tf32_i_financiamento  = $this->oFinanciamentoSaude->getCodigo();
    
    if (empty($this->iCodigo)) {
      
      $oDaoFechamento->tf32_i_codigo = null;
      $oDaoFechamento->incluir(null);
    } else {
    	
      $oDaoFechamento->tf32_i_codigo = $this->iCodigo;
      $oDaoFechamento->alterar($this->iCodigo);
    }
    $oMsgErro = new stdClass();
    if ($oDaoFechamento->erro_status == 0) {
    	
      $oMsgErro->sql_erro = str_replace('\\n', "\n", $oDaoFechamento->erro_sql); 
      throw new BusinessException(_M(URL_MENSAGEM_COMPETENCIATFD."erro_ao_salvar_encerrametno", $oMsgErro));
    }
    
    $this->iCodigo = $oDaoFechamento->tf32_i_codigo;
    
    $sWhereExcluiProcedimento = " tf40_tfd_fechamento = {$this->iCodigo} ";
    $oDaoProcedimentoFechado  = new cl_fechamentotfdprocedimento(); 
    $oDaoProcedimentoFechado->excluir(null, $sWhereExcluiProcedimento);
    
    foreach ($aProcedimentosEncerrados as $oProcedimentosEncerrado) {
      
      $oDaoProcedimentoFechado->tf40_sequencial            = null;
      $oDaoProcedimentoFechado->tf40_tfd_fechamento        = $this->iCodigo;
      $oDaoProcedimentoFechado->tf40_tfd_pedidotfd         = $oProcedimentosEncerrado->iPedidoTFD;
      $oDaoProcedimentoFechado->tf40_cgs_und               = $oProcedimentosEncerrado->iCgs;
      $oDaoProcedimentoFechado->tf40_sau_procedimento      = $oProcedimentosEncerrado->oAjudaCusto->getProcedimento()->getCodigo();
      $oDaoProcedimentoFechado->tf40_faturamentoautomatico = $oProcedimentosEncerrado->lFaturaAutomatico ? 'true' : 'false';
      $oDaoProcedimentoFechado->tf40_paciente              = $oProcedimentosEncerrado->lPaciente ? 'true' : 'false';
      
      $oDaoProcedimentoFechado->incluir(null);

      if ($oDaoFechamento->erro_status == 0) {
         
        $oMsgErro->sql_erro = str_replace('\\n', "\n", $oDaoProcedimentoFechado->erro_sql);
        throw new BusinessException(_M(URL_MENSAGEM_COMPETENCIATFD."erro_ao_encerrar_procedimentos", $oMsgErro));
      }
    }
  }
  
  /**
   * Busca todos pedidos encerrados no período
   *
   * @return array
   * @throws BusinessException
   * @throws DBException
   * @throws ParameterException
   */
  private function getPedidosEncerradosNoPeriodo() {
  	
    $aProcedimentosEncerrados = array();
    
    $sDataInicial = $this->oPeriodoInicial->convertTo(DBDate::DATA_EN);
    $sDataFinal   = $this->oPeriodoFinal->convertTo(DBDate::DATA_EN);
    
    $sWhere  = "     tfd_pedidotfd.tf01_i_situacao = 2 ";
    $sWhere .= " and tfd_agendamentoprestadora.tf16_d_dataagendamento between '{$sDataInicial}' and '{$sDataFinal}' ";
    
    $oDaoFechamento = new cl_tfd_pedidotfd();
    $sSqlPedidos    = $oDaoFechamento->sql_query_pedido_fechamento(null, " tf01_i_codigo ", null, $sWhere);
    $rsPedidos      = $oDaoFechamento->sql_record($sSqlPedidos);
    $iLinhas        = $oDaoFechamento->numrows;
    
    if ($oDaoFechamento->numrows == 0) {
      throw new BusinessException(_M(URL_MENSAGEM_COMPETENCIATFD."sem_pedidos_a_encerrar_para_encerrar"));
    }
    
    for ($i = 0; $i < $iLinhas; $i++) {
    	
      $oPedidoTFD        = new PedidoTFD(db_utils::fieldsMemory($rsPedidos, $i)->tf01_i_codigo);
      $lSaidaPorPassagem = count( $oPedidoTFD->getDadosSaidaTransporteColetivo() ) > 0;

      /**
       * Criamos um array de stdclass com o paciente e acompanhantes com as informações necessárias 
       * para salvar-mos o fechamento  
       */
      $aPessoasTFD = array();
      
      $oPaciente              = new stdClass();  
      $oPaciente->lPaciente   = true;
      $oPaciente->iCgs        = $oPedidoTFD->getPaciente()->getCodigo();
      $oPaciente->iPedidoTFD  = $oPedidoTFD->getCodigo();
      $aPessoasTFD[]          = $oPaciente;
      
      foreach ($oPedidoTFD->getAcompanhantes() as $oAcompanhantes) {
      	
        $oAcompanhante             = new stdClass();
        $oAcompanhante->iCgs       = $oAcompanhantes->getCodigo();
        $oAcompanhante->lPaciente  = false;
        $oAcompanhante->iPedidoTFD = $oPedidoTFD->getCodigo();
        $aPessoasTFD[]             = $oAcompanhante;
      }

      /**
       * Busca as ajudas de custo lançadas para o paciente e acompanhante(s)
       */
      foreach ($oPedidoTFD->getAjudasDeCusto() as $oInformacoesAjudaCusto) {
      	
        /**
         * Se financiamento diferente de : 00 - Todos, devemos buscar somente as ajudas de custo do 
         * Financiamento selecionado
         */
        if (!$this->procedimentoComFinanciamentoValido($oInformacoesAjudaCusto->oAjudaCusto->getProcedimento()->getFinanciamentoSaude())) {
        	continue;
        }
        
        $oInformacoesAjudaCusto->iPedidoTFD = $oPedidoTFD->getCodigo();
        $aProcedimentosEncerrados[]         = $oInformacoesAjudaCusto;
      }
      
      /**
       * Busca os procedimentos padrões (que são lançados de forma automática)
       */
      foreach ($aPessoasTFD as $oPessoaTDF) {

        foreach (AjudaCustoRepository::getAjudaCustoAutomatico() as $oAjudaCustoAutomatico) {
      	
          /**
           * Continua quando ajuda for para acompanhante e estivérmos tratando de um paciente
           */
          if (($oAjudaCustoAutomatico->isSomenteAcompanhante() && $oPessoaTDF->lPaciente)) {
          	continue;
          } 
          
          /**
           * Continua quando ajuda for não para for para acompanhante e estivérmos tratando de um acompanhante
           */
          if ((!$oAjudaCustoAutomatico->isSomenteAcompanhante() && !$oPessoaTDF->lPaciente)) {
            continue;
          }

          if (!$this->procedimentoComFinanciamentoValido($oAjudaCustoAutomatico->getProcedimento()->getFinanciamentoSaude())) {
            continue;
          }

          if( $lSaidaPorPassagem ) {
            continue;
          }
          
          /**
           * Redefinido a stdClass com os dados das pessoas envolvidas no pedido tfd (paciente e acompanhante)
           * pois estava sempre sobrescrevendo a ultima ajuda de custo  
           */
          $oPessoa = new stdClass();
          $oPessoa->lPaciente         = $oPessoaTDF->lPaciente;
          $oPessoa->iCgs              = $oPessoaTDF->iCgs; 
          $oPessoa->iPedidoTFD        = $oPessoaTDF->iPedidoTFD; 
          $oPessoa->lFaturaAutomatico = true;
          $oPessoa->oAjudaCusto       = $oAjudaCustoAutomatico;
          $aProcedimentosEncerrados[] = $oPessoa;
        }
      }
    }

    return $aProcedimentosEncerrados;
  }
  
  /**
   * Valida se o financiamento informado é válido para a competência  
   * @param FinanciamentoSaude $oFinanciamentoSaude
   * @return boolean
   */
  private function procedimentoComFinanciamentoValido(FinanciamentoSaude $oFinanciamentoSaude) {
    
    if ($this->oFinanciamentoSaude->getFinanciamento() != "00"
        && ($oFinanciamentoSaude->getFinanciamento() != $this->oFinanciamentoSaude->getFinanciamento()) ) {
      return false;
    }
    return true;
  }

  /**
   * Remove o encerramento da competência
   * @throws BusinessException
   */
  public function remover() {
  	
    $oMsgErro = new stdClass();
    
    $sWhereExcluiArquivo = " tf33_i_fechamento = {$this->iCodigo} ";
    $oDaoArquivo         = new cl_tfd_bpamagnetico();
    $oDaoArquivo->excluir(null, $sWhereExcluiArquivo);
    
    if ($oDaoArquivo->erro_status == 0) {
    	
      $oMsgErro->sql_erro = str_replace('\\n', "\n", $oDaoArquivo->erro_sql);
      throw new BusinessException(_M(URL_MENSAGEM_COMPETENCIATFD."erro_ao_excluir_vinculo_bpa", $oMsgErro));
    }
    
    $sWhereExcluiProcedimento = " tf40_tfd_fechamento = {$this->iCodigo} ";
    $oDaoProcedimentoFechado  = new cl_fechamentotfdprocedimento();
    $oDaoProcedimentoFechado->excluir(null, $sWhereExcluiProcedimento);
    
    if ($oDaoProcedimentoFechado->erro_status == 0) {
    	
      $oMsgErro->sql_erro = str_replace('\\n', "\n", $oDaoProcedimentoFechado->erro_sql);
      throw new BusinessException(_M(URL_MENSAGEM_COMPETENCIATFD."erro_ao_excluir_procedimentos", $oMsgErro));
    }
    
    $oDaoFechamento                = new cl_tfd_fechamento();
    $oDaoFechamento->tf32_i_codigo = $this->iCodigo;
    $oDaoFechamento->excluir($this->iCodigo);
    
    if ($oDaoFechamento->erro_status == 0) { 
      
      $oMsgErro->sql_erro = str_replace('\\n', "\n", $oDaoFechamento->erro_sql);
      throw new BusinessException(_M(URL_MENSAGEM_COMPETENCIATFD."erro_ao_excluir_encerrametno", $oMsgErro));
    }
    
  }
  
  
  /**
   * Retorna os procedimentos da competência encerrada
   * @see ICompetenciaSaude::getProcedimentos()
   * @return array[] com os procedimentos do fechamento
   */
  public function getProcedimentos() {
     
    if (count($this->aProcedimentos) == 0) {
    	
      $sWhere = " tf40_tfd_fechamento = {$this->iCodigo} ";
      if (count($this->aFiltrosProcedimentos) > 0) {
        $sWhere .= " and " . implode(" and ", $this->aFiltrosProcedimentos);
      }
      
      
      $oDaoProcedimentoFechado = new cl_tfd_fechamento();
      $sSqlProcedimento        = $oDaoProcedimentoFechado->sql_query_programas($sWhere);
      $rsProcedimento          = $oDaoProcedimentoFechado->sql_record($sSqlProcedimento);
      $iLinhas                 = $oDaoProcedimentoFechado->numrows;
      if ($iLinhas == 0) {
        throw new BusinessException(_M(URL_MENSAGEM_COMPETENCIATFD."nenhum_procedimento_encontrado"));
      }
      
      $this->aProcedimentos = db_utils::getCollectionByRecord($rsProcedimento);
      
    }
  
    return $this->aProcedimentos;
  }

  /**
   * Adiciona um filtro para ser usado no where do sql que buscará os procedimentos 
   * @param string $sFiltro
   */
  public function adicionaFiltroBuscaProcedimentos($sFiltro) {
     
    $this->aFiltrosProcedimentos[] = $sFiltro;
  }
}