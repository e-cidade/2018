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

/**
 * Classe para controle das informa��es da Efetividade RH( figura da compet�ncia )
 * @package educacao
 * @author Fabio Esteves - fabio.esteves@dbseller.com.br
 *         Andr� Mello   - andre.mello@dbseller.com.br
 * @version $Revision: 1.1 $
 */
class EfetividadeRH {

  const MENSAGENS_EFETIVIDADERH = 'educacao.recursohumano.EfetividadeRH.';

  /**
   * C�digo de efetividaderh
   * @var integer
   */
  private $iCodigo = null;

  /**
   * Inst�ncia de Escola
   * @var Escola|null
   */
  private $oEscola = null;

  /**
   * Inst�ncia de DBDate referente a data de in�cio
   * @var DBDate|null
   */
  private $oDataInicio = null;

  /**
   * Inst�ncia de DBDate referente a data de fim
   * @var DBDate|null
   */
  private $oDataFinal = null;

  /**
   * Cole��o com as efetividades da compet�ncia
   * @var Efetividade[]
   */
  private $aEfetividades = array();

  /**
   * Construtor da classe. Recebe o c�dido de v�nculo como par�metro e busca as informa��es da compet�ncia lan�ada
   * @param integer|null $iCodigo
   * @throws DBException
   * @throws ParameterException
   */
  public function __construct( $iCodigo = null ) {

    if ( empty($iCodigo) ) {
      return null;
    }

    $oDaoEfetividadeRH = new cl_efetividaderh();
    $sSqlEfetividadeRH = $oDaoEfetividadeRH->sql_query_file( $iCodigo );
    $rsEfetividadeRH   = db_query($sSqlEfetividadeRH);

    if ( !$rsEfetividadeRH ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();
      throw new DBException( _M( self::MENSAGENS_EFETIVIDADERH . 'erro_buscar_efetividaderh', $oErro ) );
    }

    if ( pg_num_rows( $rsEfetividadeRH ) == 0 ) {
      throw new ParameterException( _M( self::MENSAGENS_EFETIVIDADERH . 'efetividaderh_nao_encontrada' ) );
    }

    $oDadosEfetividadeRH = db_utils::fieldsMemory($rsEfetividadeRH, 0);
    $this->iCodigo       = $oDadosEfetividadeRH->ed98_i_codigo;
    $this->oEscola       = EscolaRepository::getEscolaByCodigo( $oDadosEfetividadeRH->ed98_i_escola );
    $this->oDataInicio   = new DBDate( $oDadosEfetividadeRH->ed98_d_dataini );
    $this->oDataFinal    = new DBDate( $oDadosEfetividadeRH->ed98_d_datafim );
  }

  /**
   * Retorna o c�digo do v�nculo
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna uma inst�ncia de escola da compet�ncia lan�ada
   * @return Escola|null
   */
  public function getEscola() {
    return $this->oEscola;
  }

  /**
   * Retorna uma inst�ncia da data de in�cio da compet�ncia
   * @return DBDate|null
   */
  public function getDataInicio() {
    return $this->oDataInicio;
  }

  /**
   * Retorna uma inst�ncia da data de fim da compet�ncia
   * @return DBDate|null
   */
  public function getDataFim() {
    return $this->oDataFinal;
  }

  /**
   * Retorna as efetividades lan�adas dentro da compet�ncia
   * @return Efetividade[]
   * @throws DBException
   */
  public function getEfetividades() {

    if( count( $this->aEfetividades ) > 0 ) {
      return $this->aEfetividades;
    }

    $oDaoEfetividade   = new cl_efetividade();
    $sWhereEfetividade = "ed97_i_efetividaderh = {$this->iCodigo}";
    $sSqlEfetividade   = $oDaoEfetividade->sql_query_file( null, 'ed97_i_codigo', null, $sWhereEfetividade );
    $rsEfetividade     = db_query( $sSqlEfetividade );

    if( !$rsEfetividade ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();
      throw new DBException( _M( self::MENSAGENS_EFETIVIDADERH . 'erro_buscar_efetividades', $oErro ) );
    }

    $iQuantidadeLinhas = pg_num_rows($rsEfetividade);

    for( $iContador = 0; $iContador < $iQuantidadeLinhas; $iContador++ ) {

      $iEfetividade          = db_utils::fieldsMemory($rsEfetividade, $iContador)->ed97_i_codigo;
      $this->aEfetividades[] = new Efetividade($iEfetividade);
    }

    return $this->aEfetividades;
  }
}