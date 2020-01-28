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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("std/db_stdClass.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");

$oJson               = new Services_JSON();
$oParam              = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';
$oRetorno->erro      = false;

$oUsuario = UsuarioSistemaRepository::getPorCodigo( db_getsession( "DB_id_usuario" ) );
$oErro    = new stdClass();

define( "MENSAGENS_SAU4_ADMINISTRACAOMEDICAMENTOS", "saude.ambulatorial.sau4_administracaomedicamentos." );

try {

  switch( $oParam->sExecuta ) {

    /**
     * Salva as informações referentes a administração de um medicamento
     */
    case 'salvar':

      if( !isset( $oParam->iMedicamento ) || empty( $oParam->iMedicamento ) ) {
        throw new ParameterException( _M( MENSAGENS_SAU4_ADMINISTRACAOMEDICAMENTOS . "medicamento_nao_informado" ) );
      }

      if( !isset( $oParam->iUnidade ) || empty( $oParam->iUnidade ) ) {
        throw new ParameterException( _M( MENSAGENS_SAU4_ADMINISTRACAOMEDICAMENTOS . "unidade_nao_informada" ) );
      }

      if( !isset( $oParam->sData ) || empty( $oParam->sData ) ) {
        throw new ParameterException( _M( MENSAGENS_SAU4_ADMINISTRACAOMEDICAMENTOS . "data_nao_informada" ) );
      }

      if( !isset( $oParam->sHora ) || empty( $oParam->sHora ) ) {
        throw new ParameterException( _M( MENSAGENS_SAU4_ADMINISTRACAOMEDICAMENTOS . "hora_nao_informada" ) );
      }

      if( !isset( $oParam->nQuantidadeAdministrada ) || empty( $oParam->nQuantidadeAdministrada ) ) {
        throw new ParameterException( _M( MENSAGENS_SAU4_ADMINISTRACAOMEDICAMENTOS . "quantidade_administrada_nao_informada" ) );
      }

      if( $oParam->nQuantidadeAdministrada <= 0 ) {
        throw new ParameterException( _M( MENSAGENS_SAU4_ADMINISTRACAOMEDICAMENTOS . "quantidade_administrada_negativa" ) );
      }

      if( !isset( $oParam->nQuantidadeEmbalagem ) || empty( $oParam->nQuantidadeEmbalagem ) ) {
        throw new ParameterException( _M( MENSAGENS_SAU4_ADMINISTRACAOMEDICAMENTOS . "quantidade_embalagem_nao_informada" ) );
      }

      if( !isset( $oParam->iProntuario ) || empty( $oParam->iProntuario ) ) {
        throw new ParameterException( _M( MENSAGENS_SAU4_ADMINISTRACAOMEDICAMENTOS . 'prontuario_nao_informado' ) );
      }

      db_inicio_transacao();

      $iCodigo          = isset( $oParam->iCodigo ) && !empty( $oParam->iCodigo ) ? $oParam->iCodigo : null;
      $oMedicamento     = new Medicamento( $oParam->iMedicamento );
      $oUnidadeMaterial = UnidadeMaterialRepository::getByCodigo( $oParam->iUnidade );
      $oData            = new DBDate( $oParam->sData );

      $oAdministracaoMedicamento = new AdministracaoMedicamento( $iCodigo );
      $oAdministracaoMedicamento->setData( $oData );
      $oAdministracaoMedicamento->setHora( $oParam->sHora );
      $oAdministracaoMedicamento->setMedicamento( $oMedicamento );
      $oAdministracaoMedicamento->setUnidade( $oUnidadeMaterial );
      $oAdministracaoMedicamento->setUsuario( $oUsuario );
      $oAdministracaoMedicamento->setQuantidadeAdministrada( $oParam->nQuantidadeAdministrada );
      $oAdministracaoMedicamento->salvar();

      $oProntuario = new Prontuario( $oParam->iProntuario );
      $oProntuario->administrarMedicamento( $oAdministracaoMedicamento );

      $oRetorno->sMensagem = urlencode( _M( MENSAGENS_SAU4_ADMINISTRACAOMEDICAMENTOS . "administracao_salva" ) );

      db_fim_transacao();

      break;

    /**
     * Remove uma administração de medicamento
     */
    case 'remover':

      if( !isset( $oParam->iCodigo ) || empty( $oParam->iCodigo ) ) {
        throw new ParameterException( _M( MENSAGENS_SAU4_ADMINISTRACAOMEDICAMENTOS . 'codigo_nao_informado' ) );
      }

      if( !isset( $oParam->iProntuario ) || empty( $oParam->iProntuario ) ) {
        throw new ParameterException( _M( MENSAGENS_SAU4_ADMINISTRACAOMEDICAMENTOS . 'prontuario_nao_informado' ) );
      }

      db_inicio_transacao();

      $oAdministracaoMedicamento = new AdministracaoMedicamento( $oParam->iCodigo );
      $oProntuario               = new Prontuario( $oParam->iProntuario );
      
      $oProntuario->removerAdministracaoMedicamento( $oAdministracaoMedicamento );
      $oAdministracaoMedicamento->remover();

      $oRetorno->sMensagem = urlencode( _M( MENSAGENS_SAU4_ADMINISTRACAOMEDICAMENTOS . "administracao_removida" ) );

      db_fim_transacao();

      break;

    /**
     * Retorno os medicamentos administrados para um prontuário
     */
    case 'buscarMedicamentosAdministrados':

      if( !isset( $oParam->iProntuario ) || empty( $oParam->iProntuario ) ) {
        throw new ParameterException( _M( MENSAGENS_SAU4_ADMINISTRACAOMEDICAMENTOS . 'prontuario_nao_informado' ) );
      }

      $oProntuario             = new Prontuario((int)$oParam->iProntuario);
      $oRetorno->aMedicamentos = array();

      foreach( $oProntuario->getAdministracoesDeMedicamento() as $oAdministracaoMedicamento ) {

        $oDadosMedicamento                       = new stdClass();
        $oDadosMedicamento->iCodigoAdministracao = $oAdministracaoMedicamento->getCodigo();
        $oDadosMedicamento->iMedicamento         = $oAdministracaoMedicamento->getMedicamento()->getCodigo();
        $oDadosMedicamento->sMedicamento         = urlencode( $oAdministracaoMedicamento->getMedicamento()->getMaterial()->getDescricao() );
        $oDadosMedicamento->sDosagem             = $oAdministracaoMedicamento->getQuantidadeAdministrada();
        $oDadosMedicamento->sDosagem            .= ' ' . $oAdministracaoMedicamento->getUnidade()->getSAbreviatura();
        $oDadosMedicamento->sData                = $oAdministracaoMedicamento->getData()->getDate(DBDate::DATA_PTBR);
        $oDadosMedicamento->sHora                = $oAdministracaoMedicamento->getHora();

        $oRetorno->aMedicamentos[] = $oDadosMedicamento;
      }

      break;
  }
} catch ( Exception $oErro ) {

  db_fim_transacao(true);

  $oRetorno->erro      = true;
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);