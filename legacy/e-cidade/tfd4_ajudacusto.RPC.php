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
define( 'MENSAGENS_TFD4_AJUDACUSTO_RPC', 'saude.tfd.tfd4_ajudacusto_RPC.' );

require_once ("libs/db_stdlib.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");

$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
$oRetorno->erro    = false;

try {

  switch($oParam->exec) {

    /**
     * Busca o valor padrão de um procedimento
     */
  	case 'valorPadraoProcedimento':

  	  $aWhere = array();
  	  if ( !empty($oParam->iCodigo) ) {
  	    $aWhere[] = "sd63_i_codigo = {$oParam->iCodigo}";
  	  }

  	  $sCampos = " (sd63_f_sh + sd63_f_sa + sd63_f_sp) as valor_procedimento ";
  	  $sWhere  = implode(" and ", $aWhere);

  	  $oDaoProcedimento      = new cl_sau_procedimento();
  	  $sSqlValorProcedimento = $oDaoProcedimento->sql_query_file(null, $sCampos, null, $sWhere);
  	  $rsValorProcedimento   = $oDaoProcedimento->sql_record($sSqlValorProcedimento);

  	  if ($oDaoProcedimento->numrows == 0) {
  	  	throw new BusinessException( _M( MENSAGENS_TFD4_AJUDACUSTO_RPC . 'procedimento_nao_encontrado' ) );
  	  }

  	  $oRetorno->nValorProcedimento = db_utils::fieldsMemory($rsValorProcedimento, 0)->valor_procedimento;

  	  break;

    /**
     * Busca as ajudas de custo vinculadas a um pedido
     */
    case 'ajudaCustoPorPedido':

      if( !isset( $oParam->iPedido ) || empty( $oParam->iPedido ) ) {
        throw new ParameterException( _M( MENSAGENS_TFD4_AJUDACUSTO_RPC . 'pedido_nao_informado' ) );
      }

      $oDaoAjudaCusto    = new cl_tfd_beneficiadosajudacusto();
      $sCamposAjudaCusto = " tf14_i_cgsretirou, tf12_i_codigo, tf12_descricao, tf15_f_valoremitido, tf15_observacao, tf15_i_cgsund ";
      $sWhereAjudaCusto  = " tf14_i_pedidotfd = {$oParam->iPedido} ";
      $sSqlAjudaCusto    = $oDaoAjudaCusto->sql_query( null, $sCamposAjudaCusto, "tf15_i_codigo", $sWhereAjudaCusto );
      $rsAjudaCusto      = db_query( $sSqlAjudaCusto );

      if( !$rsAjudaCusto ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();
        throw new DBException( _M( MENSAGENS_TFD4_AJUDACUSTO_RPC . 'erro_buscar_ajudas_custo', $oErro ) );
      }

      $iTotalAjudasCusto      = pg_num_rows( $rsAjudaCusto );

      if ( $iTotalAjudasCusto == 0) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();
        throw new DBException( _M( MENSAGENS_TFD4_AJUDACUSTO_RPC . 'ajudas_custo_vazia', $oErro ) );
      }

      $oRetorno->aAjudasCusto = array();

      for( $iContador = 0; $iContador < $iTotalAjudasCusto; $iContador++ ) {

        $oDadosAjudaCusto = db_utils::fieldsMemory( $rsAjudaCusto, $iContador );

        if( !isset( $oRetorno->iCgsRetirante ) ) {

          $oCgsRetirante           = new Cgs( $oDadosAjudaCusto->tf14_i_cgsretirou );
          $oRetorno->iCgsRetirante = $oCgsRetirante->getCodigo();
          $oRetorno->sCgsRetirante = urlencode( $oCgsRetirante->getNome() );
        }

        $oDadosRetorno                  = new stdClass();
        $oCgsBeneficiado                = new Cgs( $oDadosAjudaCusto->tf15_i_cgsund );

        $oDadosRetorno->iCgsBeneficiado = $oCgsBeneficiado->getCodigo();
        $oDadosRetorno->sCgsBeneficiado = urlencode( $oCgsBeneficiado->getNome() );
        $oDadosRetorno->iCodigoAjuda    = $oDadosAjudaCusto->tf12_i_codigo;
        $oDadosRetorno->sDescricaoAjuda = urlencode( $oDadosAjudaCusto->tf12_descricao );
        $oDadosRetorno->fValor          = $oDadosAjudaCusto->tf15_f_valoremitido;
        $oDadosRetorno->sObservacao     = urlencode( $oDadosAjudaCusto->tf15_observacao );

        $oRetorno->aAjudasCusto[] = $oDadosRetorno;
      }

      break;
  }
} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
  $oRetorno->erro    = true;
}

unset($_SESSION["DB_desativar_account"]);
echo $oJson->encode($oRetorno);