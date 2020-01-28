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
 * Classe para controle das informações da Efetividade RH( figura da competência )
 * @package educacao
 * @author Fabio Esteves - fabio.esteves@dbseller.com.br
 *         André Mello   - andre.mello@dbseller.com.br
 * @version $Revision: 1.1 $
 */
class EfetividadeRH {

  const MENSAGENS_EFETIVIDADERH = 'educacao.recursohumano.EfetividadeRH.';

  /**
   * Código de efetividaderh
   * @var integer
   */
  private $iCodigo = null;

  /**
   * Instância de Escola
   * @var Escola|null
   */
  private $oEscola = null;

  /**
   * Instância de DBDate referente a data de início
   * @var DBDate|null
   */
  private $oDataInicio = null;

  /**
   * Instância de DBDate referente a data de fim
   * @var DBDate|null
   */
  private $oDataFinal = null;

  /**
   * Coleção com as efetividades da competência
   * @var Efetividade[]
   */
  private $aEfetividades = array();

  /**
   * Construtor da classe. Recebe o códido de vínculo como parâmetro e busca as informações da competência lançada
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
   * Retorna o código do vínculo
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna uma instância de escola da competência lançada
   * @return Escola|null
   */
  public function getEscola() {
    return $this->oEscola;
  }

  /**
   * Retorna uma instância da data de início da competência
   * @return DBDate|null
   */
  public function getDataInicio() {
    return $this->oDataInicio;
  }

  /**
   * Retorna uma instância da data de fim da competência
   * @return DBDate|null
   */
  public function getDataFim() {
    return $this->oDataFinal;
  }

  /**
   * Retorna as efetividades lançadas dentro da competência
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