<?
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
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/JSON.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

$oJson               = new services_json();
$oParam              = $oJson->decode( str_replace( "\\", "", $_POST["json"] ) );
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

define( "MENSAGENS_COLETAEXAME_RPC", "saude.laboratorio.lab4_coletaexame_RPC." );

try {

  db_inicio_transacao();

  switch ( $oParam->exec ) {

    /**
     * ************************************************************
     * Salva os registros na tabelas lab_coletaitem e lab_requiitem
     * @param string  $oParam->dtReceber
     *        string  $oParam->dtEntrega
     *        integer $oParam->iAvisaPaciente
     *        array   $oParam->aRequisicoesItem
     *        boolean $oParam->lFalta
     * ************************************************************
     */
    case 'salvar':

      db_inicio_transacao();

      $oDaoLabColetaItem = new cl_lab_coletaitem();
      $oDaoLabRequiItem  = new cl_lab_requiitem();

      /**
       * Cria instancias de DBDate da data e data de entrega, caso estas tenham sido setadas
       */
      $oData        = !empty( $oParam->dtEntrega ) ? new DBDate( $oParam->dtEntrega ) : "";
      $sData        = $oData instanceof DBDate ? $oData->getDate( DBDate::DATA_EN ) : "";
      $oDataEntrega = !empty( $oParam->dtReceber ) ? new DBDate( $oParam->dtReceber ) : "";
      $sDataEntrega = $oDataEntrega instanceof DBDate ? $oDataEntrega->getDate( DBDate::DATA_EN ) : "";

      /**
       * Seta as propriedades da classe com os dados padrѕes a serem salvos
       */
      $oDaoLabColetaItem->la32_d_data          = $sData;
      $oDaoLabColetaItem->la32_d_entrega       = $sDataEntrega;
      $oDaoLabColetaItem->la32_i_avisapaciente = $oParam->iAvisaPaciente == 1 ? 1 : 2;
      $oDaoLabColetaItem->la32_i_usuario       = db_getsession( "DB_id_usuario" );
      $oDaoLabColetaItem->la32_c_hora          = $oParam->sHoraReceber;
      $oDaoLabColetaItem->la32_c_horaentrega   = $oParam->sHoraEntrega;

      /**
       * Percorre os itens da requisiчуo para salvс-los na tabela lab_coletaitem
       */
      foreach ( $oParam->aItemRequisicao as $iRequisicaoItem ) {

        $oDaoLabColetaItem->la32_i_requiitem = $iRequisicaoItem;
        $oDaoLabColetaItem->incluir( null );

        if ( $oDaoLabColetaItem->erro_status == "0" ) {

          $oMensagem        = new stdClass();
          $oMensagem->sErro = $oDaoLabColetaItem->erro_msg;
          throw new DBException( _M( MENSAGENS_COLETAEXAME_RPC . "erro_incluir_coletaitem", $oMensagem ) );
        }

        /**
         * Caso os dados na tabela lab_coletaitem tenham sido salvos, salva os registros em lab_requiitem
         */
        $oDaoLabRequiItem->la21_c_situacao = "6 - Coletado";
        if ( isset( $oParam->lFalta ) && $oParam->lFalta ) {
          $oDaoLabRequiItem->la21_c_situacao = "f - falta material";
        }

        $oDaoLabRequiItem->la21_i_codigo = $iRequisicaoItem;
        $oDaoLabRequiItem->alterar( $iRequisicaoItem );

        if ( $oDaoLabRequiItem->erro_status == "0" ) {

          $oMensagem        = new stdClass();
          $oMensagem->sErro = $oDaoLabRequiItem->erro_msg;
          throw new DBException( _M( MENSAGENS_COLETAEXAME_RPC . "erro_incluir_requiitem", $oMensagem ) );
        }
      }

      $oRetorno->sMensagem = urlencode( _M( MENSAGENS_COLETAEXAME_RPC . "exames_salvos" ) );

      db_fim_transacao();

      break;
  }
} catch ( Exception $eErro ) {

  db_fim_transacao( true );
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode( str_replace( "\\n", "\n", $eErro->getMessage() ) );
}

echo $oJson->encode( $oRetorno );
?>