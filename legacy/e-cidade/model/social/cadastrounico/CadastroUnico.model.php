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
 * Identifica um cidadão com cadastro unico
 * @author Andrio Costa
 * @package social
 * @subpackage social
 * @version $Revision: 1.10 $
 */
class CadastroUnico extends Cidadao {
  
  /**
   * Codigo sequencial do cadastro unico
   * @var integer
   */
  private $iCodigoSequencial;
  
  /**
   * Numero Identificador Social
   * @var integer
   */
  private $iNis;
  
  /**
   * Apelido do Cidadao
   * @var string
   */
  private $sApelido;
  
  protected $dtAtualizacao;
  
  /**
   * data de alteracao anterior; 
   */
  protected $dtAlteracaoAnterior;
  
  /**
   * Array dos benefícios do cidadão
   * @var array
   */
  protected $aBeneficios = array();

  /**
   * Codigo do cidadao no cadastro Unico
   * @var string 
   */
  protected $sCodigoCadastroUnico;
  
  /**
   * Instancia um Cadastro unico
   * @param integer $iCodigoSequencial Código do cadastro unico
   */
  public function __construct($iCodigoSequencial = null) {
    
    if (!empty($iCodigoSequencial)) {
      
      $oDaoCadastroUnico = db_utils::getDao('cidadaocadastrounico');
      $sSqlCadastroUnico = $oDaoCadastroUnico->sql_query($iCodigoSequencial);
      $rsCadastroUnico   = $oDaoCadastroUnico->sql_record($sSqlCadastroUnico);
      
      if ($oDaoCadastroUnico->numrows > 0) {
      
        $oCadastroUnico = db_utils::fieldsMemory($rsCadastroUnico, 0);
        
        parent::__construct($oCadastroUnico->as02_cidadao);
        
        $this->iCodigoSequencial   = $oCadastroUnico->as02_sequencial;
        $this->iNis                = $oCadastroUnico->as02_nis;
        $this->sApelido            = $oCadastroUnico->as02_apelido;
        $this->dtAlteracaoAnterior = db_formatar($oCadastroUnico->as02_dataatualizacao, "d");
        $this->dtAtualizacao       = db_formatar($oCadastroUnico->as02_dataatualizacao, "d"); 
        $this->setCodigoCadastroUnico($oCadastroUnico->as02_codigounicocidadao);
      }
    }
  }
  
  /**
   * Salva / Altera os dados do cadastro unico e do cidadao
   * @throws DBException
   * @return true
   */
  public function salvar() {
    
    $oDaoCadastroUnico = db_utils::getDao('cidadaocadastrounico');
    
    parent::salvar();
    
    $oDaoCadastroUnico->as02_cidadao  	    = $this->getCodigo();
    $oDaoCadastroUnico->as02_cidadao_seq    = $this->getSequencialInterno();
    $oDaoCadastroUnico->as02_nis  	        = $this->iNis;
    $oDaoCadastroUnico->as02_apelido  	    = $this->sApelido;
    $oDaoCadastroUnico->as02_dataatualizacao = '';
    $oDaoCadastroUnico->as02_codigounicocidadao = $this->getCodigoCadastroUnico();
    if ($this->dtAtualizacao != "") {
      $oDaoCadastroUnico->as02_dataatualizacao = implode("-", array_reverse(explode("/", $this->dtAtualizacao)));
    }
    if (!empty($this->iCodigoSequencial)) {
      
      $oDaoCadastroUnico->as02_sequencial = $this->iCodigoSequencial;
      $oDaoCadastroUnico->alterar($this->iCodigoSequencial);
    } else {
      
      $oDaoCadastroUnico->incluir(null);
      $this->iCodigoSequencial = $oDaoCadastroUnico->as02_sequencial;
    }
    
    if ($oDaoCadastroUnico->erro_status == 0) {
      
      $sMsgErro  = "Erro ao salvar os dados do Cadastro Unico {$this->getNome()}";
      $sMsgErro .= "\n\nErro técnico: {$oDaoCadastroUnico->erro_msg}\n{$oDaoCadastroUnico->erro_campo}";
      throw new BusinessException($sMsgErro);
    }
    
    return  true;
    
  }
  
  /**
   * retorna o codigo sequencial do cidadao
   * @return integer
   */
  public function getCodigoSequencial() {
    
    return $this->iCodigoSequencial ;
  }
  
  /**
   * seta o numero do NIS
   * @param integer $iNis
   */
  public function setNis ($iNis) {
  
    $this->iNis  = $iNis;
  }
  /**
   * retorna o numero do NIS
   * @return integer
   */
  public function getNis () {
  
    return $this->iNis ;
  }
  
  /**
   * seta o apelido
   * @param string $sApelido
   */
  public function setApelido ($sApelido) {
  
    $this->sApelido = $sApelido;
  }
  /**
   * retorna o apelido 
   * @return string
   */
  public function getApelido () {
  
    return $this->sApelido;
  }
  
  /**
   * Retorna a data de atualizacao do cadastro unico
   */
  public function getDataAtualizacaoCadastroUnico() {
    return $this->dtAtualizacao;
  }
  
  /**
   * Define a data de atualizacao dos dados do cadastro unico
   */
  public function setDataAtualizacaoCadastroUnico($dtAtualizacao) {
    $this->dtAtualizacao = $dtAtualizacao;
  }
  
  /**
   * Retorna a data de atualizacao Anterior do cadastro unico
   * @return string no formado Y-m-d
   */
  public function getDataAtualizacaoAnterior() {
    return $this->dtAlteracaoAnterior;
  }
  
  /**
   * Retorna um array dos benefícios
   * @return array
   */
  public function getBeneficios() {
    
    if (count($this->aBeneficios) == 0) {

      $oDaoCidadaoBeneficio   = db_utils::getDao("cidadaobeneficio");
      $sWhereCidadaoBeneficio = " as08_nis = '{$this->getNis()}'";
      $sSqlCidadaoBeneficio   = $oDaoCidadaoBeneficio->sql_query(null, "as08_sequencial", null, $sWhereCidadaoBeneficio);
      $rsCidadaoBeneficio     = $oDaoCidadaoBeneficio->sql_record($sSqlCidadaoBeneficio);
      $aBeneficios            = db_utils::getCollectionByRecord($rsCidadaoBeneficio);
      foreach ($aBeneficios as $oBeneficios) {
        $this->aBeneficios[] = new CidadaoBeneficio($oBeneficios->as08_sequencial);
      }
    }
    
    unset($oBeneficios);
    return $this->aBeneficios;
  }
  
  /**
   * Retorna a avaliacao do Cadastro Unico.
   * @return Avaliacao
   */
  public function getAvaliacao() {

    if (empty($this->oAvaliacao)) {

      parent::getAvaliacao();
      $this->atualizarAvaliacao();
    }
    
    return $this->oAvaliacao;
  }
  
  /**
   * Retorna o codigo do cadastro Unico 
   * @return integer
   */
  public function getCodigoCadastroUnico() {
    return $this->sCodigoCadastroUnico;
  }
  
  /**
   * Define o codigo do cadastro unico do Cidadao
   * @param string $sCodigoCadastroUnico codigo do cadastro unico
   */
  public function setCodigoCadastroUnico($sCodigoCadastroUnico) {
    $this->sCodigoCadastroUnico = $sCodigoCadastroUnico;
  }
  
  /**
   * Atualiza os dados do cidadao
   */
  protected function atualizarAvaliacao() {
    
    db_app::import("social.cadastrounico.ImportacaoCadastroUnico");
    $oImportacaoCadastroUnico = new ImportacaoCadastroUnico(null);
    $oImportacaoCadastroUnico->atualizarCidadao($this);
  }
  
  /**
   * Verificamos se o cidadao possuiu remuneracao
   * 3000125) 8.08) - Qual foi a remuneração bruta de todos os trabalhos recebidos por (nome) nesse período
   * Codigo da opcao de resposta = 3000433
   * @return boolean
   */
  public function getRendaBrutaNoPeriodo() {
    
    $iValor = (int) $this->getAvaliacao()->retornaValorRespostaMarcada("RemuneracaoBrutaNoPeriodo", 3000433);
    if (!empty($iValor)) {
      return $iValor;
    }
    
    return 0;
  }
  
  /**
   * Retorna um array com as situações existentes para um cadastro único
   * @return array
   */
  public function getSituacoes() {
    
    $aSituacoes                  = array();
    $oDaoCadastroUnicoSituacao   = new cl_cadastrounicosituacao();
    $sWhereCadastroUnicoSituacao = "as12_cidadaocadastrounico = {$this->getCodigoSequencial()}";
    $sSqlCadastroUnicoSituacao   = $oDaoCadastroUnicoSituacao->sql_query_file(
                                                                               null,
                                                                               "as12_tiposituacaocadastrounico",
                                                                               null,
                                                                               $sWhereCadastroUnicoSituacao
                                                                             );
    $rsCadastroUnicoSituacao     = $oDaoCadastroUnicoSituacao->sql_record($sSqlCadastroUnicoSituacao);
    $iTotalCadastroUnicoSituacao = $oDaoCadastroUnicoSituacao->numrows;
    
    if ($iTotalCadastroUnicoSituacao > 0) {
      
      for ($iContador = 0; $iContador < $iTotalCadastroUnicoSituacao; $iContador++) {
        
        $iTipoSituacaoCadastroUnico = db_utils::fieldsMemory($rsCadastroUnicoSituacao, $iContador)->as12_tiposituacaocadastrounico;
        $oTipoSituacaoCadastroUnico = TipoSituacaoCadastroUnicoRepository::getTipoSituacaoByCodigo($iTipoSituacaoCadastroUnico);
        $aSituacoes[$oTipoSituacaoCadastroUnico->getCodigo()] = $oTipoSituacaoCadastroUnico;
      }
    }
    
    return $aSituacoes;
  }
}
?>