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

require_once ("model/patrimonio/BemTipoAquisicao.php");
require_once ("model/patrimonio/BemTipoDepreciacao.php");
/**
 * Classe para dados dos bens patrimoniais
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 * @package patrimonio
 * @version $Revision: 1.57 $
 */
class Bem {

  protected $iCodigoBem;

  protected $nValorAquisicao;

  /**
   * Classificacao do bem
   * @var BemClassificacao
   */
  protected $oClassificacao;

  /**
   * Valor residual do bem
   * @var float
   */
  protected $nValorResidual;

  /**
   * Fornecedor do bem
   * @var CgmBase
   */
  protected $oCgm;

  /**
   * Data de aquisicao do bem
   * @var string
   */
  protected $dtAquisicao;

  protected $sIdentificacao;

  protected $sDescricao;

  protected $iMarca;

  protected $iModelo;

  protected $iMedida;

  protected $iInstituicao;

  protected $sObservacao;

  protected $iDepartamento;

  protected $iDivisao;

  /**
   * Cedente do Bem
   * @var BemCedente
   */
  protected $oCedente;

  /**
   * Valor atual do bem
   */
  protected $nValorAtual;

  /**
   * Tipo da da depreciacao
   * @var BemTipoDepreciacaos
   */
  protected $oTipoDepreciacao;

  /**
   * Vida util em anos do bem
   * @var float
   */
  protected $nVidaUtil;

  /**
   * Data da ultima avaliacao do bem
   * @var date
   */
  protected $dtUltimaAvaliacao;

  /**
   * Tipo de aquisicao do bem
   * @var BemTipoAquisicao
   */
  protected $oTipoAquisicao;

  /**
   * Placa do Bem
   * @var PlacaBem
   */
  protected $oPlaca;

  /**
   *
   * @var integer
   */
  protected $iCodigoBemDepreciacao;

  protected $iCodigoSituacao;

  /**
   * Dados do Imovel do bem
   * @var BemDadosImovel
   */
  protected $oDadosImovel;

  /**
   * Dados de Compra do Bem
   * @var BemDadosMaterial
   */
  protected $oDadosCompra;

  /**
  * Quantidade de meses depreciados
  * @var integer
  */
  protected $iQuantidadeMesesDepreciados = null;

  /**
   * Dados da baixa do bem
   * @var stdClass
   */
  protected $oDadosBaixa = null;

  /**
   * Verifica se o bem j� foi reavaliado
   * @var booelan
   */
  protected $lBemReavaliadao = null;

  /**
   * C�digo do item da tabela empitemnota
   * @var integer
   */
  protected $iCodigoItemNota;

  /**
   * Valor depreciavel do bem
   * @var float
   */
  protected $nValorDepreciavel = 0;

  const SEMNOTA      = 'SEM NOTA';
  const LIQUIDADO    = 'LIQUIDADO';
  const EMLIQUIDACAO = 'EM LIQUIDA��O';

  /**
   * Nota fiscal da compra do bem
   * @var NotaLiquidacao
   */
  protected $oNotaFiscal;

  /**
   * M�todo construtor
   * @param string $iCodigoBem
   */
  function __construct($iCodigoBem = '') {

     if (!empty($iCodigoBem)) {

       $oDaoBem         = db_utils::getDao("bens");

       $sSqlSituacaoBem  = "(select t56_situac ";
       $sSqlSituacaoBem .= "   from histbem  ";
       $sSqlSituacaoBem .= "  where t56_codbem = t52_bem ";
       $sSqlSituacaoBem .= "  order by t56_histbem desc limit 1) as situacao,";

       $sSqlCodigoPlaca  = "(select t41_codigo ";
       $sSqlCodigoPlaca .= "   from bensplaca ";
       $sSqlCodigoPlaca .= "  where t41_bem = t52_bem ";
       $sSqlCodigoPlaca .= "  order by t41_codigo desc limit 1) as codigoplaca";
       $sSqlDadosBem    = $oDaoBem->sql_query_dados_depreciacao($iCodigoBem, "*,{$sSqlSituacaoBem} {$sSqlCodigoPlaca}", "t44_ultimaavaliacao desc");

       $rsDadosBem      = $oDaoBem->sql_record($sSqlDadosBem);
       if ($oDaoBem->numrows > 0) {

         $oDadosBem        = db_utils::fieldsMemory($rsDadosBem, 0);

         $this->iCodigoBem = $oDadosBem->t52_bem;
         $this->setDescricao($oDadosBem->t52_descr);
         if ($oDadosBem->t09_benscadcedente != "") {
           $this->setCedente(new BemCedente($oDadosBem->t09_benscadcedente));
         }
         $this->setDataAquisicao($oDadosBem->t52_dtaqu);
         $this->setClassificacao(new BemClassificacao($oDadosBem->t52_codcla));
         $this->setDepartamento($oDadosBem->t52_depart);
         $this->setDivisao($oDadosBem->t33_divisao);
         $this->setFornecedor(CgmFactory::getInstanceByCgm($oDadosBem->t52_numcgm));
         $this->setInstituicao($oDadosBem->t52_instit);
         $this->setMarca($oDadosBem->t52_bensmarca);
         $this->setModelo($oDadosBem->t52_bensmodelo);
         $this->setMedida($oDadosBem->t52_bensmedida);
         $this->setObservacao($oDadosBem->t52_obs);
         $this->setTipoAquisicao(new BemTipoAquisicao($oDadosBem->t44_benstipoaquisicao));
         $this->setTipoDepreciacao(new BemTipoDepreciacao($oDadosBem->t44_benstipodepreciacao));
         $this->setValorAquisicao($oDadosBem->t52_valaqu);
         $this->setValorResidual($oDadosBem->t44_valorresidual);
         $this->nValorAtual       = $oDadosBem->t44_valoratual + $oDadosBem->t44_valorresidual;
         $this->nValorDepreciavel = $oDadosBem->t44_valoratual;
         $this->setIdentificacao($oDadosBem->t52_ident);
         $this->setSituacaoBem($oDadosBem->situacao);
         $this->setVidaUtil($oDadosBem->t44_vidautil);
         $this->setPlaca(new PlacaBem($oDadosBem->codigoplaca));
         $this->iCodigoBemDepreciacao = $oDadosBem->t44_sequencial;

         $oDadosBaixa = new stdClass();
         if (!empty($oDadosBem->t55_baixa)) {

           $oDadosBaixa->databaixa  = db_formatar($oDadosBem->t55_baixa, "d");
           $oDadosBaixa->motivo     = $oDadosBem->t55_motivo;
           $oDadosBaixa->observacao = $oDadosBem->t55_obs;
           $this->oDadosBaixa       = $oDadosBaixa;
         }
         unset($oDadosBem);
       }
     }
  }
  /**
   * Retorna a data da aquisi��o do bem no formato YYYY/mm/dd
   * @return string
   */
  public function getDataAquisicao() {

    return $this->dtAquisicao;
  }

  /**
   *
   * Define a data de aquisi��o do bem
   * @param string $dtAquisicao
   */
  public function setDataAquisicao($dtAquisicao) {
    $this->dtAquisicao = $dtAquisicao;
  }

  /**
   *  Retorna data da ultima avalia��o do bem
   * @return a data da ultima avalia��o do bem
   */
  public function getDataUltimaAvaliacao() {

    return $this->dtUltimaAvaliacao;
  }

  /**
   * Retorna o codigo de cadastrado do bem
   * @return integer
   */
  public function getCodigoBem() {
    return $this->iCodigoBem;
  }

  /**
   * Retorna o Departamento que o bem est� vinculado
   * @return integer
   */
  public function getDepartamento() {
    return $this->iDepartamento;
  }

  /**
   * define o Departamento que o bem est� vinculado.
   * @param integer $iDepartamento C�digo do Departamento DB_depart.coddepto
   */
  public function setDepartamento($iDepartamento) {
    $this->iDepartamento = $iDepartamento;
  }

  /**
   * Retorna a instirui��o que o bem est� vinculado
   * @return integer
   */
  public function getInstituicao() {
    return $this->iInstituicao;
  }

  /**
   * Define  a institui��o que o bem est� vinculado
   * @param integer $iInstituicao C�digo da institui��o (db_config.codigo)
   */
  public function setInstituicao($iInstituicao) {
    $this->iInstituicao = $iInstituicao;
  }

  /**
   * Retorna a marca do bem
   * @return integer
   */
  public function getMarca() {
    return $this->iMarca;
  }

  /**
   * Define a marca do bem
   * @param integer $iMarca C�digo da marca (bensmarca.t65_sequencial)
   */
  public function setMarca($iMarca) {
    $this->iMarca = $iMarca;
  }

  /**
   * Retorna a forma de medida do bem
   * @return integer
   */
  public function getMedida() {
    return $this->iMedida;
  }

  /**
   * Define a forma de medida dos bens
   * @param integer $iMedida C�digo da medida (bensmedida.t67_sequencial)
   */
  public function setMedida($iMedida) {
    $this->iMedida = $iMedida;
  }

  /**
   * @return unknown
   */
  public function getModelo() {
    return $this->iModelo;
  }

  /**
   * Define o Modelo do Bem
   * @param integer $iModelo C�digo do modelo (bensmodelo.t66_sequencial)
   */
  public function setModelo($iModelo) {
    $this->iModelo = $iModelo;
  }

  /**
   * Retorna o tipo de Aquisi��o do bem
   * @return BemTipoAquisicao
   */
  public function getTipoAquisicao() {
    return $this->oTipoAquisicao;
  }

  /**
   * Define o tipo de aquisi��o do bem
   * @param BemTipoAquisicao $oTipoAquisicao Tipo de aquisi��o do bem
   */
  public function setTipoAquisicao(BemTipoAquisicao $oTipoAquisicao) {
    $this->oTipoAquisicao = $oTipoAquisicao;
  }

  /**
   * Retorna o tipo de deprecia��o do bem
   * @return BemTipoDepreciacao
   */
  public function getTipoDepreciacao() {
    return $this->oTipoDepreciacao;
  }

  /**
   * Define o tipo de calculo de deprecia��o do bem
   * @param BemTipoDepreciacao oTipoDepreciacao Instancia do tipo de deprecia��o
   */
  public function setTipoDepreciacao(BemTipoDepreciacao $oTipoDepreciacao) {
    $this->oTipoDepreciacao = $oTipoDepreciacao;
  }

  /**
   * Retorna o valor de aquisi��o do bem
   * @return float
   */
  public function getValorAquisicao() {
    return $this->nValorAquisicao;
  }

  /**
   * Define o valor de aquisi��o do bem
   * @param float $nValorAquisicao valor de aquisi��o do bem
   */
  public function setValorAquisicao($nValorAquisicao) {
    $this->nValorAquisicao = $nValorAquisicao;
  }

  /**
   * Retorna o valor atual do bem
   * @return float
   */
  public function getValorAtual() {
    return $this->nValorAtual;
  }

  /**
   * Retorna o valor residual do bem.
   * @return float
   */
  public function getValorResidual() {
    return $this->nValorResidual;
  }

  /**
   * Define o valor residual do bem.
   * Informa��o utilizada para o calculo da deprecia��o
   * @param float $nValorResidual
   */
  public function setValorResidual($nValorResidual) {
    $this->nValorResidual = $nValorResidual;
    $this->nValorAtual    = $this->nValorAtual + $nValorResidual;
  }

  /**
   * Retorna o tempo de vida util do bem
   * @return float
   */
  public function getVidaUtil() {
    return $this->nVidaUtil;
  }

  /**
   * define o tempo de vida util do bem
   * @param float $nVidaUtil Vida util do bem
   */
  public function setVidaUtil($nVidaUtil) {
    $this->nVidaUtil = $nVidaUtil;
  }

  /**
   * Retorna o fornecedor do bem
   * @return CgmBase
   */
  public function getFornecedor() {
    return $this->oCgm;
  }

  /**
   * Define o Fornecedor do Bem
   * @param CgmBase $oCgm Fornecedor do Bem
   */
  public function setFornecedor(CgmBase $oCgm) {
    $this->oCgm = $oCgm;
  }

  /**
   * Retorna a Classificaco do bem
   * @return BemClassificacao
   */
  public function getClassificacao() {
    return $this->oClassificacao;
  }

  /**
   * Define a classifica��o do Bem
   * @param BemClassificacao $oClassificacao
   */
  public function setClassificacao(BemClassificacao $oClassificacao) {
    $this->oClassificacao = $oClassificacao;
  }

  /**
   * Retorna a descri��o/nome do Bem
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Define o nome/descri��o do bem
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * @return unknown
   */
  public function getIdentificacao() {
    return $this->sIdentificacao;
  }

  /**
   * define a Identifica�ao da placa do bem
   * @param integer $sIdentificacao
   */
  public function setIdentificacao($sIdentificacao) {
    $this->sIdentificacao = $sIdentificacao;
  }

  /**
   * Retorna a Observa��o do bem
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * define a observa��o do bem
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Placa do Bem
   * @param PlacaBem $oPlaca instacia da placa do bem
   */
  public function setPlaca(PlacaBem $oPlaca) {
    $this->oPlaca = $oPlaca;
  }
  
  /**
   * Retorna a placa do Bem.
   * @return PlacaBem
   */
  public function getPlaca() {
    return $this->oPlaca;
  }

  /**
   * Define a situa��o do bem
   * @param integer $iSituacaoBem
   */
  public function setSituacaoBem($iSituacaoBem) {
    $this->iCodigoSituacao = $iSituacaoBem;
  }

  /**
   * Retorna o codigo da situacao do Bem
   */
  public function getSituacaoBem() {
    return $this->iCodigoSituacao;
  }

  /**
   * Retorna a divis�o que o Bem est� vinculado
   * @return integer
   */
  public function getDivisao() {
    return $this->iDivisao;
  }

  /**
   * Define a divis�o que o Bem est� vinculado.
   * @param integer $iDivisao
   */
  public function setDivisao($iDivisao) {
    $this->iDivisao = $iDivisao;
  }

  /**
   * Retorna o Cedente do Bem
   * @return BemCedente
   */
  public function getCedente() {
    return $this->oCedente;
  }

  /**
   * Seta os dados do imovel.
   * @param BemDadosImovel $oDadosImovel
   */
  public function setDadosImovel(BemDadosImovel $oDadosImovel) {
    $this->oDadosImovel = $oDadosImovel;
  }

  /**
   * Retorna os dados do Imovel do Bem
   * @return BemDadosImovel
   */
  public function getDadosImovel() {

    if (empty($this->oDadosImovel)) {

      $oDaoBensImoveis = db_utils::getDao("bensimoveis");
      $sSqlDados       = $oDaoBensImoveis->sql_query_file($this->iCodigoBem);
      $rsDados         = $oDaoBensImoveis->sql_record($sSqlDados);
      if ($oDaoBensImoveis->numrows > 0) {

        $this->oDadosImovel = new BemDadosImovel();
        $oDadosImovel       = db_utils::fieldsMemory($rsDados, 0);
        $this->oDadosImovel->setObservacao($oDadosImovel->t54_obs);
        $this->oDadosImovel->setIdBql($oDadosImovel->t54_idbql);
        $this->oDadosImovel->setBem($this->iCodigoBem);
        unset($oDadosImovel);
      }
    }
    return $this->oDadosImovel;
  }

  /**
   * Retorna os dados da Compra do Bem
   * @return BemDadosMaterial
   */
  public function getDadosCompra() {

    if (empty($this->oDadosCompra)) {

      $oDaoBensMaterial = db_utils::getDao("bensmater");
      $sSqlDados       = $oDaoBensMaterial->sql_query_file($this->iCodigoBem);
      $rsDados         = $oDaoBensMaterial->sql_record($sSqlDados);
      if ($oDaoBensMaterial->numrows > 0) {

        $this->oDadosCompra = new BemDadosMaterial($this->iCodigoBem);
        unset($oDadosMaterial);
      }
    }
    return $this->oDadosCompra;
  }

  /**
   * Seta os dados de compra do Imovel
   * @param BemDadosMaterial $oDadosCompra
   */
  public function setDadosCompra(BemDadosMaterial $oDadosCompra) {
    $this->oDadosCompra = $oDadosCompra;
  }
  /**
   * @param BemCedente $oCedente
   */
  public function setCedente($oCedente) {
    $this->oCedente = $oCedente;
  }

  /**
   * Define o valor atual do bem
   */
  public function setValorAtual($nValorAtual) {
    $this->nValorAtual = $nValorAtual + $this->getValorResidual();
  }

  /**
   * Retorna o valor  deprecial do bem
   * @return float
   */
  public function getValorDepreciavel() {
    return $this->nValorDepreciavel;
  }

  /**
   * define o valor depreciavel do bem
   * @param $nValorDepreciavel
   */
  public function setValorDepreciavel($nValorDepreciavel) {
    $this->nValorDepreciavel = $nValorDepreciavel;
  }


  /**
   * Persiste os dados do Bem
   * @throws exception
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new Exception(_M('patrimonial.patrimonio.Bem.sem_transacao'));
    }

    /**
     * Realizamos algumas valida��es b�sicas
     */
    if ($this->oPlaca == null || !$this->oPlaca instanceof  PlacaBem) {
      throw new Exception(_M('patrimonial.patrimonio.Bem.placa_nao_definida'));
    }
    if (!$this->oCgm instanceof CgmBase) {
      throw new Exception(_M('patrimonial.patrimonio.Bem.informe_fornecedor'));
    }
    if (!$this->oClassificacao instanceof BemClassificacao) {
      throw new Exception(_M('patrimonial.patrimonio.Bem.informe_classificacao'));
    }

    /**
     * Dados da tabela bens
     */
    $oDaoBens                 = db_utils::getDao("bens");
    $oDaoBens->t52_bensmarca  = "{$this->getMarca()}";
    $oDaoBens->t52_bensmedida = "{$this->getMedida()}";
    $oDaoBens->t52_bensmodelo = "{$this->getModelo()}";
    $oDaoBens->t52_codcla     = $this->getClassificacao()->getCodigo();
    $oDaoBens->t52_depart     = $this->getDepartamento();
    $oDaoBens->t52_descr      = $this->getDescricao();
    $oDaoBens->t52_dtaqu      = $this->getDataAquisicao();
    $oDaoBens->t52_instit     = $this->getInstituicao();
    $oDaoBens->t52_numcgm     = $this->getFornecedor()->getCodigo();
    $oDaoBens->t52_obs        = $this->getObservacao();
    $oDaoBens->t52_valaqu     = "{$this->getValorAquisicao()}";

    /**
     * Inclusao - busca placa 
     */
    if ( empty($this->iCodigoBem) ) {
      $oDaoBens->t52_ident = $this->getPlaca()->getNumeroPlaca();
    }

    /**
     * Dados da tabela bensdepreciacao
     */
    $oDaoBensDepreciacao                          = db_utils::getDao("bensdepreciacao");
    $oDaoBensDepreciacao->t44_benstipoaquisicao   = $this->getTipoAquisicao()->getCodigo();
    $oDaoBensDepreciacao->t44_benstipodepreciacao = $this->getTipoDepreciacao()->getCodigo();
    $oDaoBensDepreciacao->t44_valoratual          = "{$this->getValorDepreciavel()}";
    $oDaoBensDepreciacao->t44_valorresidual       = "{$this->getValorResidual()}";
    $oDaoBensDepreciacao->t44_vidautil            = "{$this->getVidaUtil()}";

    $lIncorporacaoBem = false;
    if (!empty($this->iCodigoBem)) {

      $oDaoBens->t52_bem = $this->iCodigoBem;
      $oDaoBens->alterar($this->iCodigoBem);
      $sHistoricoBem = 'Altera��o de dados do Bem';
    } else {

      $sHistoricoBem = 'Inclus�o do Bem';
      $oDaoBens->incluir(null);
      $this->iCodigoBem = $oDaoBens->t52_bem;
      $lIncorporacaoBem = true;
    }

    if ($oDaoBens->erro_status == 0) {
      //throw new Exception("Erro ao salvar dados do bem.\n".$oDaoBens->erro_msg);
      throw new Exception(_M('patrimonial.patrimonio.Bem.erro_salvar', (object)array("erro_msg" => $oDaoBens->erro_msg)));
    }

    $lRealizarEscrituracao = false;

    /**
     * Criamos o v�nculo entre o bem e a nota fiscal
     */
    if ($this->getCodigoItemNota() != "") {

      /**
       * Instanciamos a DAO bensempnotaitem, vinculando o item a uma nota fiscal
       */
      $oDaoBensEmpNotaItem                   = db_utils::getDao("bensempnotaitem");
      $sWhereBensEmpNotaItem                 = "e136_bens = {$oDaoBens->t52_bem}";
      $sSqlBensEmpNotaItem                   = $oDaoBensEmpNotaItem->sql_query_file(null, "*", null, $sWhereBensEmpNotaItem);
      $rsBensEmpNotaItem                     = $oDaoBensEmpNotaItem->sql_record($sSqlBensEmpNotaItem);
      if ($oDaoBensEmpNotaItem->numrows > 0) {

      	$oDaoBensEmpNotaItem->excluir(null, $sWhereBensEmpNotaItem);
      	if ($oDaoBensEmpNotaItem->erro_status == 0) {
      		throw new Exception(_M('patrimonial.patrimonio.Bem.nao_possivel_excluir_vinculo'));
      	}
      } else if ($this->getCodigoItemNota() != "") {
        $lRealizarEscrituracao = true;
      }

    	$oDaoBensEmpNotaItem->e136_sequencial  = null;
    	$oDaoBensEmpNotaItem->e136_bens        = $oDaoBens->t52_bem;
	    $oDaoBensEmpNotaItem->e136_empnotaitem = $this->getCodigoItemNota();
	    $oDaoBensEmpNotaItem->incluir(null);

	    if ($oDaoBensEmpNotaItem->erro_status == 0) {

	      $sMensagemErro = _M('patrimonial.patrimonio.Bem.nao_possivel_criar_vinculo', (object)array("erro_msg" => $oDaoBensEmpNotaItem->erro_msg));
	    	throw new Exception($sMensagemErro);
	    }
    }

    $oDataAtual            = new DBDate(date('Y-m-d', db_getsession("DB_datausu")));
    $oInstit               = new Instituicao(db_getsession("DB_instit"));
    $lIntegracaoFinanceiro = ParametroIntegracaoPatrimonial::possuiIntegracaoPatrimonio($oDataAtual, $oInstit); 

    if ($lRealizarEscrituracao) {

      $oNota = $this->getNotaFiscal();

      /**
       * realiza lancamentos contabens do bem
       * caso o desdobramento da nota do bem esteja  no grupo 9 (bem permante)
       */
      if (!empty($oNota)) {

        $oGrupo = GrupoContaOrcamento::getGrupoConta($oNota->getDesdobramento(), db_getsession("DB_anousu"));
        if (!empty($oGrupo) && $oGrupo->getCodigo() == 9) {

          $sObs = "Lan�amento em liquida��o do tombamento do bem {$this->getCodigoBem()}.";

          if ( $lIntegracaoFinanceiro ) {

            $oProcessarLancamento                          = new stdClass();
            $oProcessarLancamento->iCodigoDotacao          = $oNota->getEmpenho()->getDotacao()->getCodigo();
            $oProcessarLancamento->iCodigoElemento         = $oNota->getDesdobramento();
            $oProcessarLancamento->iCodigoNotaLiquidacao   = $oNota->getCodigoNota();
            $oProcessarLancamento->iFavorecido             = $oNota->getEmpenho()->getCgm()->getCodigo();
            $oProcessarLancamento->iNumeroEmpenho          = $oNota->getEmpenho()->getNumero();
            $oProcessarLancamento->sObservacaoHistorico    = $sObs;
            $oProcessarLancamento->nValorTotal             = $this->getValorAquisicao();
            $oProcessarLancamento->oClassificacao          = $this->getClassificacao();
            LancamentoEmpenhoEmLiquidacao::processaLancamento($oProcessarLancamento);
          }
        }
      }
    }

    if (!empty($this->iCodigoBemDepreciacao)) {

      $oDaoBensDepreciacao->t44_sequencial = $this->iCodigoBemDepreciacao;
      $oDaoBensDepreciacao->alterar($this->iCodigoBemDepreciacao);

    } else {

      /**
       * Salvamos as informa��es da placa.
       */
      $oDaoBensDepreciacao->t44_ultimaavaliacao  = date("Y-m-d", db_getsession("DB_datausu"));
      $oDaoBensDepreciacao->t44_bens = $this->iCodigoBem;
      $oDaoBensDepreciacao->incluir(null);
      $this->iCodigoBemDepreciacao = $oDaoBensDepreciacao->t44_sequencial;
    }
    
    
    if ($oDaoBensDepreciacao->erro_status == 0) {

      /*$sMsg  = "Erro ao salvar as informa��es para c�lculo de deprecia��o do bem.\n";
      $sMsg .= "{$oDaoBensDepreciacao->erro_msg}";*/
      $sMsg = _M('patrimonial.patrimonio.Bem.erro_salvar_calculo', (object)array("erro_msg" => $oDaoBensDepreciacao->erro_msg));
      throw new Exception($sMsg);
    }

    $this->oPlaca->setCodigoBem($this->iCodigoBem);
    $this->oPlaca->salvar();

    /**
     * Salvamos o Historico do bem
     */
    $oHistoricoBem = new BemHistoricoMovimentacao();
    $oHistoricoBem->setData(date("Y-m-d", db_getsession("DB_datausu")));
    $oHistoricoBem->setDepartamento(db_getsession("DB_coddepto"));
    $oHistoricoBem->setHistorico($sHistoricoBem);
    $oHistoricoBem->setCodigoSituacao($this->getSituacaoBem());
    $oHistoricoBem->salvar($this->iCodigoBem);

    $this->salvarDadosDivisao();
    $this->salvarDadosCedente();
    if ($this->getDadosImovel() instanceof BemDadosImovel) {

      $this->getDadosImovel()->setBem($this->iCodigoBem);
      $this->getDadosImovel()->salvar();
    }

    if ($this->getDadosCompra() instanceof BemDadosMaterial) {

      $this->getDadosCompra()->setBem($this->iCodigoBem);
      $this->getDadosCompra()->salvar();
    }

    if ($this->getTipoAquisicao() instanceof BemTipoAquisicao) {

      /**
       * S� executa se bem for uma inclus�o manual ($lRealizarEscrituracao == false)
       */
    	if ($lIncorporacaoBem == true && !$lRealizarEscrituracao && USE_PCASP && $lIntegracaoFinanceiro) {

        $oLancamentoauxiliarBem = new LancamentoAuxiliarBem();
        $oEventoContabil        = new EventoContabil(700, db_getsession('DB_anousu'));
        $oLancamentoauxiliarBem->setValorTotal($this->getValorAquisicao());
        $oLancamentoauxiliarBem->setBem($this);
        $oLancamentoauxiliarBem->setObservacaoHistorico('Incorpora��o de bem.');
        
        $aLancamentos      = $oEventoContabil->getEventoContabilLancamento();
        $oLancamentoauxiliarBem->setHistorico($aLancamentos[0]->getHistorico());
        $oEventoContabil->executaLancamento($oLancamentoauxiliarBem);
    	}
    }

  }

  /**
   * Persiste os dados da Divisao do Bem , quando Existir
   * @throws exception
   * @return void
   */
  protected function salvarDadosDivisao() {

    $oDaoBensDivisao = db_utils::getDao("bensdiv");
    if (!empty($this->iDivisao)) {

      $oDaoBensDivisao->excluir($this->iCodigoBem);
      $oDaoBensDivisao->t33_bem     = $this->iCodigoBem;
      $oDaoBensDivisao->t33_divisao = $this->iDivisao;
      $oDaoBensDivisao->incluir($this->iCodigoBem);
      if ($oDaoBensDivisao->erro_status == 0) {
       throw new Exception(_M('patrimonial.patrimonio.Bem.erro_salvar_divisao_bem'));
      }
    }
  }


  /**
   * Salva os Dados do cedente do bem
   * @return void
   * @throws exceptiongetBem
   */
  protected function salvarDadosCedente() {

    $oDaoBensCedente = db_utils::getDao("benscedente");
    if (!empty($this->oCedente)) {

      $oDaoBensCedente->excluir(null, "t09_bem={$this->iCodigoBem}");
      $oDaoBensCedente->t09_bem            = $this->iCodigoBem;
      $oDaoBensCedente->t09_benscadcedente = $this->oCedente->getCodigo();
      $oDaoBensCedente->incluir(null);
      if ($oDaoBensCedente->erro_status == 0) {

        throw new Exception(_M('patrimonial.patrimonio.Bem.erro_salvar_dados_cedente'));
      }
    }
  }
  /**
   * M�todo m�gico Clone.
   */
  public function __clone() {

    $this->iCodigoBem            = null;
    $this->oPlaca                = null;
    $this->iCodigoBemDepreciacao = null;
  }

  /**
   * Retorna a quantidade de meses Depreciados
   * @return integer
   */
  public function getQuantidadeMesesDepreciados() {

    if (empty($this->iQuantidadeMesesDepreciado)) {

      if (!empty($this->iCodigoBem)) {

        $oDaoBensHistoricoCalculo   = db_utils::getDao("benshistoricocalculobem");
        $sWhereHistorico            = "     t57_ativo is true and ";
        $sWhereHistorico           .= "t57_processado is true and ";
        $sWhereHistorico           .= "t58_bens       = {$this->getCodigoBem()} ";
        $sCamposHistorico           = "t58_bens, t58_benstipodepreciacao";
        $sSqlBensHistoricoCalculo   = $oDaoBensHistoricoCalculo->sql_query_calculo(null,
                                                                                   $sCamposHistorico,
                                                                                   "t57_datacalculo",
                                                                                   $sWhereHistorico);
        $rsBensHistoricoCalculo     = $oDaoBensHistoricoCalculo->sql_record($sSqlBensHistoricoCalculo);
        $iTotalMeses                = 0;
        $iTotalDepreciacoes         = $oDaoBensHistoricoCalculo->numrows;
        for ($i = 0; $i < $iTotalDepreciacoes; $i++)  {

          $oDadosCalculo = db_utils::fieldsMemory($rsBensHistoricoCalculo, $i);
          $iTotalMeses++;
          if ($oDadosCalculo->t58_benstipodepreciacao == 6) {
            $iTotalMeses = 0;
          }
        }
      }
      $this->iQuantidadeMesesDepreciados = $iTotalMeses;
    }

    return $this->iQuantidadeMesesDepreciados;
  }

  /**
   * Realiza a baixa do bem
   * @param integer $iMotivoBaixa motivo da baixa do bem
   * @param string  $dtBaixa data da baixa. string no formato dd/mm/YYYY
   * @param string  $sObservacao observa��es sobre a baixa do bem
   */
  public function baixar($iMotivoBaixa, $dtBaixa, $sObservacao = '') {

    if (!db_utils::inTransaction()) {
      throw new Exception(_M('patrimonial.patrimonio.Bem.sem_transacao'));
    }

    if (empty($iMotivoBaixa)) {
      throw new Exception(_M('patrimonial.patrimonio.Bem.informe_motivo_baixa'));
    }

    if (empty($dtBaixa)) {
      throw new Exception(_M('patrimonial.patrimonio.Bem.informe_data_baixa'));
    }

    if ($this->isBaixado()) {
      throw new Exception(_M('patrimonial.patrimonio.Bem.bem_ja_baixado'));
    }

    $oDaoBensBaixa = db_utils::getDao("bensbaix");
    $oDaoBensBaixa->t55_codbem = $this->getCodigoBem();
    $oDaoBensBaixa->t55_baixa  = implode("-", array_reverse(explode("/", $dtBaixa)));
    $oDaoBensBaixa->t55_motivo = $iMotivoBaixa;
    $oDaoBensBaixa->t55_obs    = $sObservacao;
    $oDaoBensBaixa->incluir($this->getCodigoBem());
    if ($oDaoBensBaixa->erro_status == 0) {
      throw new Exception(_M('patrimonial.patrimonio.Bem.erro_baixar_bem'));
    }

    $oDaoBensBaixa->motivo = $iMotivoBaixa;
    $this->oDadosBaixa     = $oDaoBensBaixa;

    /**
     * Verificar situacao da nota em que o bem est� vinculado.
     */
    $oNota = $this->getNotaFiscal();

    $oDataAtual            = new DBDate(date('Y-m-d', db_getsession("DB_datausu")));
    $oInstit               = new Instituicao(db_getsession("DB_instit"));
    $lIntegracaoFinanceiro = ParametroIntegracaoPatrimonial::possuiIntegracaoPatrimonio($oDataAtual, $oInstit);

    /**
     *  realiza lancamentos contabens do bem
     *  caso o desdobramento da nota do bem esteja  no grupo 9 (bem permante)
     */
    if (!empty($oNota) && $this->verificaSituacaoNota() && $oNota->getValorLiquidado() == 0) {

      $oGrupo = GrupoContaOrcamento::getGrupoConta($oNota->getDesdobramento(), db_getsession("DB_anousu"));
      if (!empty($oGrupo) && $oGrupo->getCodigo() == 9 ) {

        $sObs = "Estorno do Lan�amento em liquida��o do tombamento do bem {$this->getCodigoBem()}.";

        if ( $lIntegracaoFinanceiro ) {

          $oProcessarLancamento                        = new stdClass();
          $oProcessarLancamento->iCodigoDotacao        = $oNota->getEmpenho()->getDotacao()->getCodigo();
          $oProcessarLancamento->iCodigoElemento       = $oNota->getDesdobramento();
          $oProcessarLancamento->iCodigoNotaLiquidacao = $oNota->getCodigoNota();
          $oProcessarLancamento->iFavorecido           = $oNota->getEmpenho()->getCgm()->getCodigo();
          $oProcessarLancamento->iNumeroEmpenho        = $oNota->getEmpenho()->getNumero();
          $oProcessarLancamento->sObservacaoHistorico  = $sObs;
          $oProcessarLancamento->nValorTotal           = $this->getValorAquisicao();
          $oProcessarLancamento->oClassificacao        = $this->getClassificacao();
          LancamentoEmpenhoEmLiquidacao::estornarLancamento($oProcessarLancamento);
        }
      }

      // caso nao tenha nota, verificamos o tipo de aquisi��o, se for doa��o, realizamos o lan�amento
    }

    if (USE_PCASP && empty($oNota) && $lIntegracaoFinanceiro) {

      /**
       *  Realiza acerto nas contas de deprecia��o e classifica��o, caso seja necess�rio
       */
      $oBemAcertoContaClassificacao = new BemAcertoContaClassificacao();
      $oDBData                      = new DBDate($dtBaixa);
      $oBemAcertoContaClassificacao->acertaContasDepreciacaoClassificacao($this, $oDBData);

      $oBemDepreciacao = BemDepreciacao::getInstance($this);
      $nValorAtual     = $this->nValorAquisicao;
      if (!empty($oBemDepreciacao)) {
        $nValorAtual   = $oBemDepreciacao->getValorAtual();
      }

      $oLancamentoauxiliarBem = new LancamentoAuxiliarBem();
      $oLancamentoauxiliarBem->setValorTotal($nValorAtual);
      $oLancamentoauxiliarBem->setBem($this);
      $oLancamentoauxiliarBem->setObservacaoHistorico($sObservacao);
      $oLancamentoauxiliarBem->setEstorno(true);
      $oEventoContabil       = new EventoContabil(701, db_getsession('DB_anousu'));
      $aLancamentos          = $oEventoContabil->getEventoContabilLancamento();
      $oLancamentoauxiliarBem->setHistorico($aLancamentos[0]->getHistorico());
      $oEventoContabil->executaLancamento($oLancamentoauxiliarBem);
    }
  }

  /**
   * Retorna um objeto com os dados da baixa do bem
   * @return stdClass
   */
  public function getDadosBaixa() {
    return $this->oDadosBaixa;
  }

  /**
   * Verifica se o bem est� baixado
   * @return boolean
   */
  public function isBaixado() {
    return !empty($this->oDadosBaixa);
  }

  /**
   * Reativa o bem, quando estiver baixado
   */
  public function reativar($sObservacao = null) {

    if (empty($this->iCodigoBem)) {
      throw new Exception(_M('patrimonial.patrimonio.Bem.bem_nao_existe_base_de_dados'));
    }
    if (!db_utils::inTransaction()) {
      throw new Exception(_M('patrimonial.patrimonio.Bem.sem_transacao'));
    }

    if (!$this->isBaixado()) {
      throw new Exception(_M('patrimonial.patrimonio.Bem.bem_nao_esta_baixado'));
    }
    $oDaoBensBaixa = db_utils::getDao("bensbaix");
    $oDaoBensBaixa->excluir($this->getCodigoBem());
    if ($oDaoBensBaixa->erro_status == 0) {
      throw new Exception(_M('patrimonial.patrimonio.Bem.erro_reativar_bem', (object)array("erro_msg" => $oDaoBensBaixa->erro_msg)));
      //throw new Exception("Erro ao reativar bem.\n[Erro T�nico:\n{$oDaoBensBaixa->erro_msg}");
    }

    $oDataAtual            = new DBDate(date('Y-m-d', db_getsession("DB_datausu")));
    $oInstit               = new Instituicao(db_getsession("DB_instit"));
    $lIntegracaoFinanceiro = ParametroIntegracaoPatrimonial::possuiIntegracaoPatrimonio($oDataAtual, $oInstit);

    $oNota = $this->getNotaFiscal();

    /**
     *  realiza lancamentos contabens do bem
     *  caso o desdobramento da nota do bem esteja  no grupo 9 (bem permante)
     */
    if (!empty($oNota) && $this->verificaSituacaoNota() && $oNota->getValorLiquidado() == 0) {

      $oGrupo = GrupoContaOrcamento::getGrupoConta($oNota->getDesdobramento(), db_getsession("DB_anousu"));
      if (!empty($oGrupo) && $oGrupo->getCodigo() == 9 ) {

        $sObs = "Estorno do Lan�amento em liquida��o do tombamento do bem {$this->getCodigoBem()}.";

        if ( $lIntegracaoFinanceiro ) {

          $oProcessarLancamento                        = new stdClass();
          $oProcessarLancamento->iCodigoDotacao        = $oNota->getEmpenho()->getDotacao()->getCodigo();
          $oProcessarLancamento->iCodigoElemento       = $oNota->getDesdobramento();
          $oProcessarLancamento->iCodigoNotaLiquidacao = $oNota->getCodigoNota();
          $oProcessarLancamento->iFavorecido           = $oNota->getEmpenho()->getCgm()->getCodigo();
          $oProcessarLancamento->iNumeroEmpenho        = $oNota->getEmpenho()->getNumero();
          $oProcessarLancamento->sObservacaoHistorico  = $sObs;
          $oProcessarLancamento->nValorTotal           = $this->getValorAquisicao();
          $oProcessarLancamento->oClassificacao        = $this->getClassificacao();
          LancamentoEmpenhoEmLiquidacao::processaLancamento($oProcessarLancamento);
        }
      }
    }
    
    if (USE_PCASP && empty($oNota) && $lIntegracaoFinanceiro ) {

      /**
       *  Realiza acerto nas contas de deprecia��o e classifica��o, caso seja necess�rio
       */
      $oBemAcertoContaClassificacao = new BemAcertoContaClassificacao();
      $oDBData                      = new DBDate( date("d/m/Y", db_getsession("DB_datausu")) );
      $oBemAcertoContaClassificacao->acertaContasDepreciacaoClassificacao($this, $oDBData);

      $oBemDepreciacao = BemDepreciacao::getInstance($this);
      $nValorAtual     = $this->nValorAquisicao;
      if (!empty($oBemDepreciacao)) {
        $nValorAtual   = $oBemDepreciacao->getValorAtual();
      }

      $oLancamentoauxiliarBem = new LancamentoAuxiliarBem();
      $oLancamentoauxiliarBem->setValorTotal($nValorAtual);
      $oLancamentoauxiliarBem->setBem($this);
      $oLancamentoauxiliarBem->setObservacaoHistorico($sObservacao);
      $oLancamentoauxiliarBem->setEstorno(true);
      $oEventoContabil       = new EventoContabil(702, db_getsession('DB_anousu'));
      $aLancamentos          = $oEventoContabil->getEventoContabilLancamento();
      $oLancamentoauxiliarBem->setHistorico($aLancamentos[0]->getHistorico());
      $oEventoContabil->executaLancamento($oLancamentoauxiliarBem);
    }
  }

  /**
  *
  * Seta o n�mero de meses que j� foram depreciado.
  * @param integer $iMesQuantidadeMesDepreciado
  */
  public function setQuantidadeMesDepreciado($iMesQuantidadeMesDepreciado) {
    $this->iQuantidadeMesesDepreciados = $iMesQuantidadeMesDepreciado;
  }

  /**
   * Seta a nota fiscal de um Bem
   * @param integer $iCodigoItemNota
   */
  public function setCodigoItemNota($iCodigoItemNota) {
  	$this->iCodigoItemNota = $iCodigoItemNota;
  }
  /**
   * Retorna o valor da propriedade $iCodigoItemNota
   * @return integer $iCodigoItemNota
   */
  public function getCodigoItemNota() {
  	return $this->iCodigoItemNota;
  }

  /**
   * metodo para retornar dados da deprecia��o do bem
   * @throws BusinessException
   * @return Object
   */
  public function getDepreciacao(){

    $oDaoBensDeprecia     = db_utils::getDao("bensdepreciacao");
    $sWhereBensDeprecia   = "t44_bens = {$this->getCodigoBem()} group by t44_vidautil, t44_valoratual";
    $sCamposBensDeprecia  = "max (t44_sequencial), ";
    $sCamposBensDeprecia .= "t44_vidautil, ";
    $sCamposBensDeprecia .= "t44_valoratual ";

    $sSqlBensDeprecia     = $oDaoBensDeprecia->sql_query_file(null, $sCamposBensDeprecia, null, $sWhereBensDeprecia);
    $rsBensDeprecia       = $oDaoBensDeprecia->sql_record($sSqlBensDeprecia);
    if ($oDaoBensDeprecia->numrows == 0) {

      $oParms = new stdClass();
      $oParms->codigoBem = $this->getCodigoBem();
      throw new BusinessException(_M('patrimonial.patrimonio.Bem.sem_especificacao', $oParms));
      //throw new BusinessException("Erro T�cnico: N�o h� deprecia��o para o bem {$this->getCodigoBem()} ");

    }
    $oBensDepreciacao  = db_utils::fieldsMemory($rsBensDeprecia, 0);
    return $oBensDepreciacao;
  }



  /**
   * Define o codigo da depreciacao do bem
   * @param integer $iCodigoBemDepreciacao
   */
  public function setCodigoBemDepreciacao($iCodigoBemDepreciacao) {
    $this->iCodigoBemDepreciacao = $iCodigoBemDepreciacao;
  }

  /**
   * Retorna o codigo da depreciacao
   * @return float
   */
  public function getCodigoBemDepreciacao() {

    return $this->iCodigoBemDepreciacao;
  }


  /**
   * metodo que realizara a contagem de total de reavalia��es processadas do bem
   * @return integer
   */
  public function getTotalDeReavaliacoes() {

    $oDaoReavaliacaoBem = db_utils::getDao("inventariobem");

    $sWhere  =  "t77_bens = {$this->getCodigoBem()} and ";
    $sWhere .=  "t75_situacao = 3  ";

    $sSqlReavaliacaoBem = $oDaoReavaliacaoBem->sql_query_inventario(null, "count(*) as total", null, $sWhere);
    $rsReavaliacaoBem   = $oDaoReavaliacaoBem->sql_record($sSqlReavaliacaoBem);
    return db_utils::fieldsMemory($rsReavaliacaoBem, 0)->total;

  }

  /**
   * Retorna o valor da ultima reavaliacao em que o bem foi processado
   * @return bool
   */
  public function bemFoiReavaliacao() {

    if ($this->lBemReavaliadao == null) {

      $this->lBemReavaliadao = false;
      if ($this->getTotalDeReavaliacoes() > 0) {
        $this->lBemReavaliadao = true;
      }
    }
    return $this->lBemReavaliadao;
  }

  /**
   * Retorna o valor atualizado do bem. caso o bem nao tenha reavaliacoes , o valor do bem deve ser
   * o valor de aquisicao do bem. Apos a reavaliacao, retorna o valor da reavaliacao do bem.
   * @return float
   */
  public function getValorUltimaReavaliacao() {

    if ($this->getTotalDeReavaliacoes() > 0) {

      $oDaoReavaliacaoBem = db_utils::getDao("inventariobem");

      $sWhere  =  "t77_bens = {$this->getCodigoBem()} and ";
      $sWhere .=  "t75_situacao = 3  ";
      $sOrder  = "t75_dataabertura desc limit 1";

      $sSqlReavaliacaoBem = $oDaoReavaliacaoBem->sql_query_inventario(null,
                                                                      "(t77_valordepreciavel +
                                                                           t77_valorresidual) as valor ",
                                                                      $sOrder,
                                                                      $sWhere
                                                                     );

      $rsReavaliacaoBem   = $oDaoReavaliacaoBem->sql_record($sSqlReavaliacaoBem);
      if ($oDaoReavaliacaoBem->numrows == 1) {
        return db_utils::fieldsMemory($rsReavaliacaoBem, 0)->valor;
      }
    } else {
      return $this->getValorAquisicao();
    }
  }
  /**
   * Verifica a situacao da nota do Bem
   * @return const |  SEMNOTA  | LIQUIDADO | EMLIQUIDACAO
   */
  public function verificaSituacaoNota() {

    $oDaoBemNota = db_utils::getDao("bensempnotaitem");

    $sWhere      = "e136_bens = " . $this->getCodigoBem();
    $sSqlBemNota = $oDaoBemNota->sql_query_bens_ativos(null, "e72_vlrliq", null, $sWhere);
    $rsBemNota   = $oDaoBemNota->sql_record($sSqlBemNota);

    if ($oDaoBemNota->numrows == 0) {
      return self::SEMNOTA;
    }

    $nValorLiquidado = db_utils::fieldsMemory($rsBemNota, 0)->e72_vlrliq;

    if ($nValorLiquidado > 0) {

      return self::LIQUIDADO;
    }
    return self::EMLIQUIDACAO;
  }

  /**
   * Retorna a nota de liquidacao de compra do bem .
   * @return NotaLiquidacao
   */
  public function getNotaFiscal() {

    if (empty($this->oNotaFiscal) && !empty($this->iCodigoBem )) {

      $oDaoBemNota = db_utils::getDao("bensempnotaitem");
      $sWhere      = "e136_bens = {$this->getCodigoBem()}";
      $sSqlBemNota = $oDaoBemNota->sql_query_bens_nota(null, "e69_codnota", null, $sWhere);
      $rsBemNota   = $oDaoBemNota->sql_record($sSqlBemNota);
      if ($oDaoBemNota->numrows > 0) {
         $this->oNotaFiscal = new NotaLiquidacao(db_utils::fieldsMemory($rsBemNota, 0)->e69_codnota);
      }
    }
    return $this->oNotaFiscal;
  }
}
?>