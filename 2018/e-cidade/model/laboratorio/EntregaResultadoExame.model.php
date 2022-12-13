<?php
/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2014 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

define( "MENSAGENS_ENTREGARESULTADO_MODEL", "saude.laboratorio.EntregaResultadoExame." );

/**
 * Classe para controle dos registros de lab_entrega
 * @author     F�bio Esteves <fabio.esteves@dbseller.com.br>
 * @package    saude
 * @subpackage laboratorio
 */
class EntregaResultadoExame {

  /**
   * C�digo de lab_entrega
   * @var integer
   */
  private $iCodigo;

  /**
   * Instancia de RequisicaoExame do resultado do exame entregue
   * @var RequisicaoExame
   */
  private $oRequisicaoExame;

  /**
   * C�digo do tipo de documento
   * @var integer
   */
  private $iTipoDocumento;

  /**
   * Informa��o referente ao tipo de documento
   * @var string
   */
  private $sDocumento;

  /**
   * Inst�ncia do Cgs da requisi��o
   * @var Cgs
   */
  private $oCgs;

  /**
   * Inst�ncia de UsuarioSistema do usu�rio que entregou o resultado do exame
   * @var UsuarioSistema
   */
  private $oUsuarioSistema;

  /**
   * Inst�ncia de DBDate da data de entrega do resultado do exame
   * @var DBDate
   */
  private $oData;

  /**
   * Hora da entrega do resultado do exame
   * @var string
   */
  private $sHora;

  /**
   * Nome de quem retirou o resultado do exame
   * @var string
   */
  private $sRetirado;

  /**
   * Construtor da classe. Recebe o c�digo de lab_entrega como par�metro
   * @param integer $iCodigo
   */
  public function __construct( $iCodigo = null ) {

    if( !empty( $iCodigo ) ) {

      $oDaoLabEntrega = new cl_lab_entrega();
      $sSqlLabEntrega = $oDaoLabEntrega->sql_query_file( $iCodigo );
      $rsLabEntrega   = db_query( $sSqlLabEntrega );

      if( !$rsLabEntrega ) {

        $oMensagem        = new stdClass();
        $oMensagem->sErro = pg_last_error();
        throw new DBException( _M( MENSAGENS_ENTREGARESULTADO_MODEL . "erro_buscar_entrega", $oMensagem ) );
      }

      if( pg_num_rows( $rsLabEntrega ) == 0 ) {
        throw new DBException( _M( MENSAGENS_ENTREGARESULTADO_MODEL . "entrega_nao_encontrada" ) );
      }

      $oDadosEntrega          = db_utils::fieldsMemory( $rsLabEntrega, 0 );
      $this->iCodigo          = $iCodigo;
      $this->oRequisicaoExame = new RequisicaoExame( $oDadosEntrega->la31_i_requiitem );
      $this->iTipoDocumento   = $oDadosEntrega->la31_i_tipodocumento;
      $this->sDocumento       = $oDadosEntrega->la31_c_documento;
      $this->oCgs             = new Cgs( $oDadosEntrega->la31_i_cgs );
      $this->oUsuarioSistema  = new UsuarioSistema( $oDadosEntrega->la31_i_usuario );
      $this->oData            = new DBDate( $oDadosEntrega->la31_d_data );
      $this->sHora            = $oDadosEntrega->la31_c_hora;
      $this->sRetirado        = $oDadosEntrega->la31_retiradopor;
    }
  }

  /**
   * Retorna o c�digo da entrega
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Seta o c�digo da entrega
   * @param integer $iCodigo
   */
  public function setCodigo( $iCodigo ) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna a inst�ncia de RequisicaoExame do resultado do exame entregue
   * @return RequisicaoExame
   */
  public function getRequisicaoExame() {
    return $this->oRequisicaoExame;
  }

  /**
   * Seta uma inst�ncia de RequisicaoExame
   * @param RequisicaoExame $oRequisicaoExame
   */
  public function setRequisicaoExame( RequisicaoExame $oRequisicaoExame ) {
    $this->oRequisicaoExame = $oRequisicaoExame;
  }

  /**
   * Retorna o tipo de documento
   * @return integer
   */
  public function getTipoDocumento() {
    return $this->iTipoDocumento;
  }

  /**
   * Seta o tipo de documento
   * @param integer $iTipoDocumento
   */
  public function setTipoDocumento( $iTipoDocumento ) {
    $this->iTipoDocumento = $iTipoDocumento;
  }

  /**
   * Retorna a informa��o referente ao tipo de documento
   * @return string
   */
  public function getDocumento() {
    return $this->sDocumento;
  }

  /**
   * Seta a informa��o referente ao tipo de documento
   * @param string $sDocumento
   */
  public function setDocumento( $sDocumento ) {
    $this->sDocumento = $sDocumento;
  }

  /**
   * Retorna uma inst�ncia do Cgs da requisi��o
   * @return Cgs
   */
  public function getCgs() {
    return $this->oCgs;
  }

  /**
   * Seta uma inst�ncia de Cgs
   * @param Cgs $oCgs
   */
  public function setCgs( Cgs $oCgs ) {
    $this->oCgs = $oCgs;
  }

  /**
   * Retorna uma inst�ncia de UsuarioSistema do usu�rio que entregou o resultado do exame
   * @return UsuarioSistema
   */
  public function getUsuarioSistema() {
    return $this->oUsuarioSistema;
  }

  /**
   * Seta uma inst�ncia de UsuarioSistema do usu�rio que entregou o resultado do exame
   * @param UsuarioSistema $oUsuarioSistema
   */
  public function setUsuarioSistema( UsuarioSistema $oUsuarioSistema ) {
    $this->oUsuarioSistema = $oUsuarioSistema;
  }

  /**
   * Retorna uma inst�ncia de DBDate da data que foi entregue o resultado do exame
   * @return DBDate
   */
  public function getData() {
    return $this->oData;
  }

  /**
   * Seta uma inst�ncia de DBDate da data de entrega do resultado do exame
   * @param DBDate $oData
   */
  public function setData( DBDate $oData ) {
    $this->oData = $oData;
  }

  /**
   * Retorna a hora de entrega do resultado do exame
   * @return string
   */
  public function getHora() {
    return $this->sHora;
  }

  /**
   * Seta a hora de entrega do resultado do exame
   * @param string $sHora
   */
  public function setHora( $sHora ) {
    $this->sHora = $sHora;
  }

  /**
   * Retorna o nome de quem retirou o resultado do exame
   * @return string
   */
  public function getRetirado() {
    return $this->sRetirado;
  }

  /**
   * Seta o nome de quem retirou o resultado do exame
   * @param string $sRetirado
   */
  public function setRetirado( $sRetirado ) {
    $this->sRetirado = $sRetirado;
  }

  /**
   * Salva as informa��es da entrega do resultado do exame
   * @throws BusinessException
   */
  public function salvar() {

    /**
     * Valida��es verificando se os campos foram informados
     */

    if( $this->oRequisicaoExame->getCodigo() == null ) {
      throw new BusinessException( _M( MENSAGENS_ENTREGARESULTADO_MODEL . "exame_nao_informado" ) );
    }

    if( empty( $this->iTipoDocumento ) ) {
      throw new BusinessException( _M( MENSAGENS_ENTREGARESULTADO_MODEL . "tipo_documento_nao_informado" ) );
    }

    if( empty( $this->sDocumento ) ) {
      throw new BusinessException( _M( MENSAGENS_ENTREGARESULTADO_MODEL . "documento_nao_informado" ) );
    }

    if( $this->oCgs->getCodigo() == null ) {
      throw new BusinessException( _M( MENSAGENS_ENTREGARESULTADO_MODEL . "cgs_nao_informado" ) );
    }

    if( $this->oUsuarioSistema->getCodigo() == null ) {
      throw new BusinessException( _M( MENSAGENS_ENTREGARESULTADO_MODEL . "usuario_nao_informado" ) );
    }

    if( empty( $this->sHora ) ) {
      throw new BusinessException( _M( MENSAGENS_ENTREGARESULTADO_MODEL . "hora_nao_informada" ) );
    }

    if( empty( $this->sRetirado ) ) {
      throw new BusinessException( _M( MENSAGENS_ENTREGARESULTADO_MODEL . "retirado_nao_informado" ) );
    }

    $oDaoLabEntrega                       = new cl_lab_entrega();
    $oDaoLabEntrega->la31_i_requiitem     = $this->oRequisicaoExame->getCodigo();
    $oDaoLabEntrega->la31_i_tipodocumento = $this->iTipoDocumento;
    $oDaoLabEntrega->la31_c_documento     = $this->sDocumento;
    $oDaoLabEntrega->la31_i_cgs           = $this->oCgs->getCodigo();
    $oDaoLabEntrega->la31_i_usuario       = $this->oUsuarioSistema->getCodigo();
    $oDaoLabEntrega->la31_d_data          = $this->oData->getDate();
    $oDaoLabEntrega->la31_c_hora          = $this->sHora;
    $oDaoLabEntrega->la31_retiradopor     = $this->sRetirado;

    $lErroSalvar      = false;
    $oMensagem        = new stdClass();
    $oMensagem->sErro = '';

    if( empty( $this->iCodigo ) ) {

      $oDaoLabEntrega->incluir( null );

      if( $oDaoLabEntrega->erro_status == "0" ) {
        $lErroSalvar = true;
      }

      $this->iCodigo = $oDaoLabEntrega->la31_i_codigo;
    } else {

      $oDaoLabEntrega->la31_i_codigo = $this->iCodigo;
      $oDaoLabEntrega->alterar( $this->iCodigo );

      if( $oDaoLabEntrega->erro_status == "0" ) {
        $lErroSalvar = true;
      }
    }

    if( $lErroSalvar ) {

      $oMensagem->sErro = $oDaoLabEntrega->erro_msg;
      throw new DBException( _M( MENSAGENS_ENTREGARESULTADO_MODEL . "erro_salvar_entrega", $oMensagem ) );
    }
  }
}