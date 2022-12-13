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

define( "MENSAGENS_PROCCID_MODEL", "saude.laboratorio.ProcedimentoCID." );

/**
 * Classe com vínculo de um CID com um procedimento
 * @package laboratorio
 * @author  Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class ProcedimentoCID {

  /**
   * Código de proccid
   * @var integer
   */
  private $iCodigo;

  /**
   * Instãncia de CID
   * @var CID
   */
  private $oCID;

  /**
   * Instância de ProcedimentoSaude
   * @var ProcedimentoSaude
   */
  private $oProcedimento;

  /**
   * Controla se o CID é o principal
   * @var boolean
   */
  private $lPrincipal;

  /**
   * Construtor da classe. Recebe o código de proccid por parâmetro
   * @param integer $iCodigo
   */
  public function __construct( $iCodigo = null ) {

    if ( !empty( $iCodigo ) ) {

      $oDaoProcCID = new cl_sau_proccid();
      $sSqlProcCID = $oDaoProcCID->sql_query_file( $iCodigo );
      $rsProcCID   = db_query( $sSqlProcCID );

      if ( !$rsProcCID ) {

        $oMensagem        = new stdClass();
        $oMensagem->sErro = pg_result_error( $oMensagem );
        throw new DBException( _M( MENSAGENS_PROCCID_MODEL . "erro_buscar_proccid" ) );
      }

      if ( pg_num_rows( $rsProcCID ) == 0 ) {

        $oMensagem          = new stdClass();
        $oMensagem->iCodigo = $iCodigo;
        throw new DBException( _M( MENSAGENS_PROCCID_MODEL . "codigo_nao_encontrado" ) );
      }

      $oDadosProcCID       = db_utils::fieldsMemory( $rsProcCID, 0 );
      $this->iCodigo       = $oDadosProcCID->sd72_i_codigo;
      $this->oCID          = new CID( $oDadosProcCID->sd72_i_cid );
      $this->oProcedimento = new ProcedimentoSaude( $oDadosProcCID->sd72_i_procedimento );
      $this->lPrincipal    = $oDadosProcCID->sd72_c_principal == 'S' ? true : false;
    }
  }

  /**
   * Retorna o código de proccid
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a instância de CID
   * @return CID
   */
  public function getCID() {
    return $this->oCID;
  }

  /**
   * Retorna a instância de ProcedimentoSaude
   * @return ProcedimentoSaude
   */
  public function getProcedimento() {
    return $this->oProcedimento;
  }

  /**
   * Retorna se é o CID principal
   * @return boolean
   */
  public function cidPrincipal() {
    return $this->lPrincipal;
  }
}