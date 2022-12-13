<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2014 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

define( "MENSAGENS_ENTREGARESULTADO_MODEL", "saude.laboratorio.EntregaResultadoExame." );

/**
 * Classe para controle dos registros de lab_entrega
 * @author     Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package    saude
 * @subpackage laboratorio
 */
class EntregaResultadoExame {

  /**
   * Código de lab_entrega
   * @var integer
   */
  private $iCodigo;

  /**
   * Instancia de RequisicaoExame do resultado do exame entregue
   * @var RequisicaoExame
   */
  private $oRequisicaoExame;

  /**
   * Código do tipo de documento
   * @var integer
   */
  private $iTipoDocumento;

  /**
   * Informação referente ao tipo de documento
   * @var string
   */
  private $sDocumento;

  /**
   * Instância do Cgs da requisição
   * @var Cgs
   */
  private $oCgs;

  /**
   * Instância de UsuarioSistema do usuário que entregou o resultado do exame
   * @var UsuarioSistema
   */
  private $oUsuarioSistema;

  /**
   * Instância de DBDate da data de entrega do resultado do exame
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
   * Construtor da classe. Recebe o código de lab_entrega como parâmetro
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
   * Retorna o código da entrega
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Seta o código da entrega
   * @param integer $iCodigo
   */
  public function setCodigo( $iCodigo ) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna a instância de RequisicaoExame do resultado do exame entregue
   * @return RequisicaoExame
   */
  public function getRequisicaoExame() {
    return $this->oRequisicaoExame;
  }

  /**
   * Seta uma instância de RequisicaoExame
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
   * Retorna a informação referente ao tipo de documento
   * @return string
   */
  public function getDocumento() {
    return $this->sDocumento;
  }

  /**
   * Seta a informação referente ao tipo de documento
   * @param string $sDocumento
   */
  public function setDocumento( $sDocumento ) {
    $this->sDocumento = $sDocumento;
  }

  /**
   * Retorna uma instância do Cgs da requisição
   * @return Cgs
   */
  public function getCgs() {
    return $this->oCgs;
  }

  /**
   * Seta uma instância de Cgs
   * @param Cgs $oCgs
   */
  public function setCgs( Cgs $oCgs ) {
    $this->oCgs = $oCgs;
  }

  /**
   * Retorna uma instância de UsuarioSistema do usuário que entregou o resultado do exame
   * @return UsuarioSistema
   */
  public function getUsuarioSistema() {
    return $this->oUsuarioSistema;
  }

  /**
   * Seta uma instância de UsuarioSistema do usuário que entregou o resultado do exame
   * @param UsuarioSistema $oUsuarioSistema
   */
  public function setUsuarioSistema( UsuarioSistema $oUsuarioSistema ) {
    $this->oUsuarioSistema = $oUsuarioSistema;
  }

  /**
   * Retorna uma instância de DBDate da data que foi entregue o resultado do exame
   * @return DBDate
   */
  public function getData() {
    return $this->oData;
  }

  /**
   * Seta uma instância de DBDate da data de entrega do resultado do exame
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
   * Salva as informações da entrega do resultado do exame
   * @throws BusinessException
   */
  public function salvar() {

    /**
     * Validações verificando se os campos foram informados
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