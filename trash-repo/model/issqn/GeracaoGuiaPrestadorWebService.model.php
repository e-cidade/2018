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

require_once('std/DBDate.php');
require_once('model/arrecadacao/boletos/EmissaoBoletoWebService.model.php');

/**
 * Classe responsavel por receber e tratar os dados referentes ao Prestador recebidos do Web Service
 *
 * @author Renan Melo <renan@dbseller.com.br>
 * @package webservices
 */
class GeracaoGuiaPrestadorWebService {
  
  /**
   * Tipo do Imposto
   * @var string
   */
  private $sTipoImposto;
  
  /**
   * Número da Inscrição
   * @var integer
   */
  private $iInscricao;
  
  /**
   * Codigo do CGM
   * @var integer
   */
  private $iNumcgm;
  
  /**
   * Data Competencia (mes/ano)
   * @var string
   */
  private $sCompetencia;
  
  /**
   * Valor do serviço prestado
   * @var float
   */
  private $nValorServico;
  
  /**
   * Mes da competencia
   * @var integer
   */
  private $iMesCompetencia;
  
  /**
   * Ano da competencia
   * @var integer
   */
  private $iAnoCompetencia;
  
  /**
   * Valor do imposto
   * @var float
   */
  private $nValorImposto;
  
  /**
   * Data do pagamento
   * @var date
   */
  private $oDataPagamento;
  
  
  /**
   * lista de planilhas criadas
   * @var array
   */
  private $aPlanilhas = array();
  
  /**
   * Metodo Construtor da Classe
   */
  public function __construct() {
    
  }

  /**
   * Seta o tipo de Imposto
   * @param string $sTipoImposto Tipo do imposto
   */
  public function setTipoImposto($sTipoImposto) {
    $this->sTipoImposto = $sTipoImposto;
  }
  
  /**
   * Seta o número da Inscrição.
   * @param integer $iInscricao numero da Inscricao
   */
  public function setInscricao($iInscricao) {
    $this->iInscricao = $iInscricao;
  }
  
  /**
   * Código do Cgm
   * @param integer $iNumCgm Codigo do CGM
   */
  public function setCgm($iNumCgm) {
    $this->iNumcgm = $iNumCgm;
  }
  
  /**
   * Seta o valor do serviço
   * @param float $nValorServico Valor total do servico
   */
  public function setValorServico($nValorServico) {
    $this->nValorServico = $nValorServico;
  }
  
  /**
   * Seta o valor do imposto
   * @param float $nValorImposto valor recolhido de imposto
   */
  public function setValorImposto($nValorImposto) {
    $this->nValorImposto = $nValorImposto;
  }
  
  /**
   * Seta os valores no mes e do ano da competencia
   * @param integer $iMesCompetencia Mes da competencia
   * @param integer $iAnoCompetencia Ano da Competencia
   */
  public function setCompetencia($iMesCompetencia, $iAnoCompetencia) {
  
    $this->iMesCompetencia = $iMesCompetencia;
    $this->iAnoCompetencia = $iAnoCompetencia;
  }
  
  /**
   * Seta a Data do Processamento
   * @param string $dDataPagamento Data de Processamento
   */
  public function setDataPagamento($dDataPagamento) {
    $this->oDataPagamento = new DBDate($dDataPagamento);
  }
  
  /**
   * Gera a Guia de pagamento do Prestador
   * @throws Exception Erro ao gerar guia
   * @return stdClass
   */
  public function gerarGuia() {
    
    db_inicio_transacao();
    
    try {
      
      $oRetorno = $this->salvar();
      
      db_fim_transacao(false);
      
      return $oRetorno;
      
    } catch ( Exception $eErro ) {

      db_fim_transacao(true);
      throw new Exception( $eErro->getMessage() );
      
    }
    
  }

  /**
   * Salva os dados da guia
   * @throws Exception Erro ao Realizar querys
   * @return stdClass
   */
  public function salvar() {

    db_utils::getDao('arreinscr'   ,false);
    db_utils::getDao('arrecad'     ,false);
    db_utils::getDao('arrenumcgm'  ,false);
    db_utils::getDao('issvarnotas' ,false);
    
    
    if (!empty($this->iInscricao)) {
      
      $oDaoArrecad             = db_utils::getDao('arrecad');
      $oDaoIssVar              = db_utils::getDao('issvar');
      $sWhereDadosCompetencia  = "    arreinscr.k00_inscr = {$this->iInscricao}      ";
      $sWhereDadosCompetencia .= "and issvar.q05_ano      = {$this->iAnoCompetencia} ";
      $sWhereDadosCompetencia .= "and issvar.q05_mes      = {$this->iMesCompetencia} ";
      $sWhereDadosCompetencia .= "and issvar.q05_valor    = 0                         ";
      
      $sSqlDadosCompetencia = $oDaoIssVar->sql_query_arrecad(null, "*", null, $sWhereDadosCompetencia);
      $rsDadosCompentencia  = $oDaoIssVar->sql_record($sSqlDadosCompetencia);
      $aDadosCompetencia = db_utils::getCollectionByRecord($rsDadosCompentencia);
      
      foreach ($aDadosCompetencia as $oDadosCompetencia) {
        
        $oDaoIssVar->excluir(null, "q05_codigo = {$oDadosCompetencia->q05_codigo}");
        
        if ($oDaoIssVar->erro_status == "0") {
          throw new Exception('Erro ao excluir valores zerados da issvar.' . $oDaoIssVar->erro_msg);
        }
        $sWhere = "k00_numpre = {$oDadosCompetencia->k00_numpre} and k00_numpar = {$oDadosCompetencia->k00_numpar}";
        $oDaoArrecad->excluir(null, $sWhere);
        
        if ($oDaoArrecad->erro_status == "0") {
          throw new Exception('Erro ao excluir valores zerados da arrecad.' . $oDaoArrecad->erro_msg);
        }
      }
    }
    $nValorTotal     = 0;
    $nValorImposto   = 0;
    $aListaPlanilhas = array();
    
    foreach ($this->aPlanilhas as $iCodigoPlanilha) {
      
      $oNotaPlanilha = new NotaPlanilhaRetencao($iCodigoPlanilha);
      if (!isset($aListaPlanilhas[$oNotaPlanilha->getCodigoPlanilha()])) {
        
        $oPlanilha                = new stdClass;
        $oPlanilha->codigo        = $oNotaPlanilha->getCodigoPlanilha();
        $oPlanilha->valor_imposto = 0;
        $oPlanilha->valor_servico = 0;
        
        $aListaPlanilhas[$oNotaPlanilha->getCodigoPlanilha()] = $oPlanilha;
      }
      
      $oPlanilha                 = $aListaPlanilhas[$oNotaPlanilha->getCodigoPlanilha()];
      $oPlanilha->valor_imposto += $oNotaPlanilha->getValorImposto();
      $oPlanilha->valor_servico += $oNotaPlanilha->getValorServico();
    }
    
    $oDadosRetorno                = new stdClass;
    $oDadosRetorno->dados_boleto  = '';
    $oDadosRetorno->lista_debitos = array();
    foreach ($aListaPlanilhas as $oPlanilha) {
      
      $rsNumpre = db_query("select nextval('numpref_k03_numpre_seq') as k03_numpre");
      if (!$rsNumpre) {
        throw new Exception("Ocorreu um erro ao retornar o numero do debito $rsNumpre");
      }
      $iNumpreGerado                  = db_utils::fieldsMemory($rsNumpre, 0)->k03_numpre;
      $oDebito                        = new stdClass();
      $oDebito->planilha              = $oPlanilha->codigo;
      $oDebito->numpre                = $iNumpreGerado;
      $oDadosRetorno->lista_debitos[] = $oDebito;
      
      $aDebitos[] = $iNumpreGerado;
      // Trata os dados para incluir o ISSQN variável complementar.
      $oDaoIssVar             = new cl_issvar();
      $oDaoIssVar->q05_numpre = $iNumpreGerado;
      $oDaoIssVar->q05_numpar = $this->iMesCompetencia;
      $oDaoIssVar->q05_valor  = $oPlanilha->valor_imposto;
      $oDaoIssVar->q05_ano    = $this->iAnoCompetencia;
      $oDaoIssVar->q05_mes    = $this->iMesCompetencia;
      $oDaoIssVar->q05_histor = 'Recebido pelo WebService';
      $oDaoIssVar->q05_aliq   = '0';
      $oDaoIssVar->q05_bruto  = '0';
      $oDaoIssVar->q05_vlrinf = 'null';
      
      if ( !$oDaoIssVar->incluir_issvar_complementar(array(), $this->iInscricao, $this->iNumcgm, "P") ) {
        throw new Exception("Ocorreu um erro ao incluir o issqn.{$oDaoIssVar->erro_msg}" );
      }
      
      /**
       * Vincula o debito gerado na planilha de retencao
       */
      $oDaoIssplan               = new cl_issplan();
      $oDaoIssplan->q20_numpre   = $iNumpreGerado;
      $oDaoIssplan->q20_planilha = $oPlanilha->codigo;
      $oDaoIssplan->alterar($oPlanilha->codigo);
      if ($oDaoIssplan->erro_status == 0) {
        throw new Exception("Não Foi possível vincular débito com a Planilha");
      }
      $oDaoIssPlanNumpre               = new cl_issplannumpre;
      $oDaoIssPlanNumpre->q32_planilha = $oPlanilha->codigo;
      $oDaoIssPlanNumpre->q32_numpre   = $iNumpreGerado;
      $oDaoIssPlanNumpre->q32_dataop   = date('Y-m-d');
      $oDaoIssPlanNumpre->q32_horaop   = db_hora();
      $oDaoIssPlanNumpre->q32_status   = 1 ;
      $oDaoIssPlanNumpre->incluir(null);
      if ($oDaoIssPlanNumpre->erro_status == 0) {
        throw new Exception("Não Foi possível vincular débito com a Planilha");
      }
      
      /**
       * Vincula Todas as notas da planilha ao debito
       */
      $oDaoIssplanIt     = new cl_issplanit;
      $sSqlNotasPlanilha = $oDaoIssplanIt->sql_query_file(null,"*",
                                                          null,
                                                          "q21_planilha = {$oPlanilha->codigo} and q21_status = 1"
                                                         );
      $rsNotasPlanilha   = $oDaoIssplanIt->sql_record($sSqlNotasPlanilha);
      if (!$rsNotasPlanilha) {
        throw new Exception("Erro ao pesquisar dados da planilha");
      }
      for ($i = 0; $i < $oDaoIssplanIt->numrows; $i++) {
        
        $oNotaPlanilha = db_utils::fieldsMemory($rsNotasPlanilha, $i);
        
        $oDaoNotaNumpre                    = new cl_issplannumpreissplanit;
        $oDaoNotaNumpre->q77_issplanit     = $oNotaPlanilha->q21_sequencial;
        $oDaoNotaNumpre->q77_issplannumpre = $oDaoIssPlanNumpre-> q32_sequencial;
        $oDaoNotaNumpre->incluir(null);
        if ($oDaoNotaNumpre->erro_status == 0) {
          throw new Exception("Não Foi possível incluir débito na nota ");
        }
      }
    }
    
    $oDadosRetorno->dados_boleto = $this->gerarRecibo($aDebitos);
    // Realiza a geração do recibo
    return $oDadosRetorno;
  }
  
  /**
   * Gera o Recibo a partir do debitos gerados para o iss
   * @param array $aListaDebitos lista de numpres
   */
  public function gerarRecibo(array $aListaDebitos) {
    
    $oGerarBoleto = new EmissaoBoletoWebservice();
    foreach ($aListaDebitos as $iNumpre) {
      $oGerarBoleto->adicionarDebito($iNumpre, $this->iMesCompetencia);
    }
    $oGerarBoleto->setInscricao($this->iInscricao);
    $oGerarBoleto->setCodigoCgm($this->iNumcgm);
    $oGerarBoleto->setDataVencimento($this->oDataPagamento);
    $oGerarBoleto->setForcaVencimento(true);
    $oGerarBoleto->setModeloImpressao(21);
    $oGerarBoleto->gerarRecibo();
    $oGerarBoleto->imprimir();
    
    return $oGerarBoleto->getDadosBoleto();
  }
  
  /**
   * Adiciona um array com os codigos das notas de planilhas ao recibo
   * @param string $aPlanilhas array com os codigos da planilhas
   */
  public function adicionarPlanilhasNotas($aPlanilhas) {
    $this->aPlanilhas = $aPlanilhas;
  }
  
}