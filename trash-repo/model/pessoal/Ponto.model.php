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
 * Classse de  Definicao do Ponto da Folha
 * 
 * @abstract 
 * @package Pessoal
 * @version $id$
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br> 
 */
abstract class Ponto {

  /**
   * Nome das tabelas do ponto 
   */
  const SALARIO         = "pontofs";
  const ADIANTAMENTO    = "pontofa";
  const FERIAS          = "pontofe";
  const COMPLEMENTAR    = "pontocom";
  const PONTO_13o       = "pontof13";
  const RESCISAO        = "pontofr";
  const FIXO            = "pontofx";
  const PROVISAO_FERIAS = "pontoprovfe";
  const PROVISAO_13o    = "pontoprovf13";

  /**
   * Registros do Ponto do Servidor
   * @var RegistroPonto[]
   */
  protected $aRegistros = array();
  
  /**
   * Tabela do ponto 
   * 
   * @var string
   * @access protected
   */
  protected $sTabela;

  /**
   * Sigla da tabela 
   * 
   * @var string
   * @access protected
   */
  protected $sSigla;

  /**
   * Servidor propriet�rio do Ponto
   * 
   * @var Servidor
   * @access private
   */
  protected $oServidor;

  /**
   * Construtor da Classe 
   * 
   * @param  Servidor $oServidor 
   * @access public
   * @return void
   */
  public function __construct ( Servidor $oServidor) {
    $this->oServidor = $oServidor;
  }

  /**
   * Retorna instancia do Servidor 
   * @return Servidor
   */
  public function getServidor() { 
    return $this->oServidor;
  }

  /**
   * Retorna Ano do ponto
   */
  public function getAnoCompetencia () { 
    return $this->getServidor()->getAnoCompetencia(); 
  }

  /**
   * Retorna Mes do ponto 
   */
  public function getMesCompetencia () { 
    return $this->getServidor()->getMesCompetencia(); 
  }

  /**
   * Funcao para gerar ponto para o mes selecionado
   */
  abstract public function gerar();

  /**
   * Funcao para retornar as movimentacoes das rubricas do ponto
   */
  abstract public function getMovimentacoes($iCodigoRubrica = null);

  /**
   * Funcao para retornar as rubricas utilizadas no ponto
   */
  abstract public function getRubricas();

  /**
   * Carrega em mem�ria os registros do ponto do servidor guardados na tabela.
   *
   * @param mixed $mRubrica - array de rubricas ou string com codigo da rubrica
   * @access public
   * @return void
   */
  public function carregarRegistros( $mRubrica = null ) {
     
    $oDaoPonto = db_utils::getDao($this->sTabela);

    $sWhere  = "     {$this->sSigla}_regist = {$this->getServidor()->getMatricula()}                    ";
    $sWhere .= " and {$this->sSigla}_anousu = {$this->getServidor()->getAnoCompetencia()}                          ";
    $sWhere .= " and {$this->sSigla}_mesusu = {$this->getServidor()->getMesCompetencia()}                          ";
    $sWhere .= " and {$this->sSigla}_instit = {$this->getServidor()->getInstituicao()->getSequencial()} ";

    /**
     * Informou rubrica, adiciona ao where 
     */
    if ( !empty($mRubrica) ) {   

      $sWhere .= " and {$this->sSigla}_rubric "; 
      
      /**
       * Rubrica � um array 
       */
      if ( is_array($mRubrica) ) {

        $aRubricas = array();

        foreach ( $mRubrica as $sRubrica ) {
          $aRubricas[] = "'$sRubrica'";
        }

        $sWhere .= " in (" . implode(", ", $aRubricas) . ")";

      } else {

        /**
         * $mRubrica � uma string 
         */
        $sWhere .= " = '{$mRubrica}' ";
      }                                                
    }

    switch ( $this->sTabela ) {

      default :

        $sSql  = $oDaoPonto->sql_query_file(null, 
                                            null, 
                                            null, 
                                            null, 
                                            " {$this->sSigla}_rubric as codigo_rubrica, 
                                              {$this->sSigla}_valor  as valor_rubrica, 
                                              {$this->sSigla}_quant  as quantidade_rubrica ", 
                                            null, 
                                            $sWhere);
      break;

      case Ponto::RESCISAO :
      case Ponto::PROVISAO_FERIAS :
    //  case Ponto::FERIAS : Tem Carregamento especifico PontoFerias::carregarRegistros();

        $sSql  = $oDaoPonto->sql_query_file(null, 
                                            null, 
                                            null, 
                                            null, 
                                            null, 
                                            " {$this->sSigla}_rubric as codigo_rubrica, 
                                              {$this->sSigla}_valor  as valor_rubrica, 
                                              {$this->sSigla}_quant  as quantidade_rubrica ", 
                                            null, 
                                            $sWhere);


      break;
    }     
    
    $rsRegistros = db_query($sSql);

    if ( !$rsRegistros ) {
      throw new DBException("Erro ao Buscar dados dos registros do ponto." . pg_last_error() );
    }

    for ( $iEvento = 0; $iEvento < pg_num_rows($rsRegistros); $iEvento++ ) {
      
      $oDadosRegistro = db_utils::fieldsMemory($rsRegistros, $iEvento);
      $oRegistro      = new RegistroPonto();
      $oRubrica       = RubricaRepository::getInstanciaByCodigo($oDadosRegistro->codigo_rubrica);

      $oRegistro->setServidor($this->oServidor); 
      $oRegistro->setRubrica($oRubrica); 
      $oRegistro->setQuantidade($oDadosRegistro->quantidade_rubrica);
      $oRegistro->setValor($oDadosRegistro->valor_rubrica);

      $this->aRegistros[ $oRubrica->getCodigo() ] = $oRegistro;
    }

    return true;
  }

  /**
   * Retorna os Registros do ponto do servidor
   * @return RegistroPonto;
   */
  public function getRegistros() {
    return $this->aRegistros;
  }
  
  /**
   * Adiciona um registro ao ponto
   * @param RegistroPonto $oRegistro
   * @return void
   */
  public function adicionarRegistro( RegistroPonto $oRegistroPonto ) {
    
    $this->aRegistros[] = $oRegistroPonto;
    return true;
  }
  
  /**
   * Remove o registro do ponto
   * @param Registro $oRegistro
   * @return void
   */
  public function removerRegistro( Rubrica $oRubrica) {
  
    if ( isset($this->aRegistros[$oRubrica->getCodigo()]) ) {
      unset($this->aRegistros[$oRubrica->getCodigo()]);
    }
    
    return true;
  }

  /**
   * Limpar tabela do ponto
   *
   * @param string $sRubrica
   * @access public
   * @return bool
   */
  public function limpar($sRubrica = null) {
  
    $iAnoCompetencia = $this->getServidor()->getAnoCompetencia();
    $iMesCompetencia = $this->getServidor()->getMesCompetencia();
    $iMatricula      = $this->getServidor()->getMatricula();
  
    $oDaoPonto = db_utils::getDao($this->sTabela);
    $oDaoPonto->excluir($iAnoCompetencia, $iMesCompetencia, $iMatricula, $sRubrica);
  
    /**
     * Erro ao excluir registro
     */
    if ( $oDaoPonto->erro_status == "0" ) {
      throw new Exception($oDaoPonto->erro_msg);
    }
  
    return true;
  }

}