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
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

define('MSGTFD4_VEICULOS_RPC', 'saude.tfd.tfd4_veiculosRPC.');

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "buscaVericulosTFD":

      $oRetorno->aVeiculosComPedido = array();
      $oRetorno->aVeiculosSemPedido = array();

      $oDaoVeiculoAgenda = new cl_tfd_veiculodestino();

      $oData      = new DBDate($oParam->dtSaida);
      $sData      = $oData->getDate();
      $sSqlAgenda = $oDaoVeiculoAgenda->sql_consulta_veiculos( $sData, $oParam->iDestino );
      $rsAgenda   = db_query($sSqlAgenda);


      $oMsgErro = new stdClass();
      if ( !$rsAgenda ) {

        $oMsgErro->sErro = pg_last_error();
        throw new Exception( _M( MSGTFD4_VEICULOS_RPC . "erro_buscar_veiculos_com_agenda", $oMsgErro) );
      }

      $iLinhas = pg_num_rows($rsAgenda);
      for ( $i = 0; $i < $iLinhas; $i++ ) {

        $oDados            = db_utils::fieldsMemory($rsAgenda, $i);
        $oDados->destino   = urlencode( $oDados->destino );
        $oDados->modelo    = urlencode($oDados->modelo);
        $oDados->motorista = urlencode($oDados->motorista);

        $oRetorno->aVeiculosComPedido [] = $oDados;
      }

      $sCampos = " ve01_placa as placa, ve22_descr as modelo, ve01_quantcapacidad as vagas ";
      $sOrder  = " ve22_descr ";
      $sWhere  = " ve01_codigo not in ( select tf18_i_veiculo ";
      $sWhere .= "                        from tfd_veiculodestino where tf18_d_datasaida = '{$sData}' ) ";

      $oDaoVeiculos = new cl_veiculos;
      $sSqlLivres   = $oDaoVeiculos->sql_query_modelo(null, $sCampos, $sOrder, $sWhere);
      $rsLivres     = db_query( $sSqlLivres );

      if ( !$rsLivres ) {

        $oMsgErro->sErro = pg_last_error();
        throw new Exception( _M( MSGTFD4_VEICULOS_RPC . "erro_buscar_veiculos_sem_agenda", $oMsgErro) );
      }

      $iLinhasSemPedido = pg_num_rows($rsLivres);
      for ( $i = 0; $i < $iLinhasSemPedido; $i++ ) {

        $oSemPedido            = db_utils::fieldsMemory($rsLivres, $i);
        $oSemPedido->modelo    = urlencode($oSemPedido->modelo);

        $oRetorno->aVeiculosSemPedido[] = $oSemPedido;
      }
      break;
  }

  db_fim_transacao(false);


} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}

$oRetorno->erro = $oRetorno->iStatus == 2;
echo $oJson->encode($oRetorno);