<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include("libs/JSON.php");

$objJson             = new services_json();
$objParam            = $objJson->decode(str_replace("\\","",$_POST["json"]));
$objRetorno          = new stdClass();
$objRetorno->status  = 1;
$objRetorno->message = '';

switch ( $objParam->exec ){
	case "setAgendamento":
		$clagendamentos     = db_utils::getDao("agendamentos_ext");
		$clprontuarios      = db_utils::getDao("prontuarios_ext");
		$clprontagendamento = db_utils::getDao("prontagendamento");
		$clprontproced      = db_utils::getDao("prontproced_ext");
		$clprontprofatend   = db_utils::getDao("prontprofatend_ext");

		$ano = substr ( $objParam->sd23_d_consulta, 6, 4 );
		$mes = substr ( $objParam->sd23_d_consulta, 3, 2 );
		$dia = substr ( $objParam->sd23_d_consulta, 0, 2 );

		if (isset ( $objParam->codigos )) {
			$objParam->codigos = " and sd23_i_codigo in ({$objParam->codigos})";
		}

		$res_agendamento = $clagendamentos->sql_record (
							$clagendamentos->sql_query_ext ( "", "*,
								fc_totalagendado('$ano/$mes/$dia',{$objParam->sd27_i_codigo},{$objParam->chave_diasemana})",
								"sd23_i_codigo", "sd23_d_consulta = '$ano/$mes/$dia'
								{$objParam->codigos}
								and not exists ( select *
		            							from agendaconsultaanula
		            							where s114_i_agendaconsulta = sd23_i_codigo
		            							)
								and sd27_i_codigo = {$objParam->sd27_i_codigo}" ) );
		$obj_agendamento = db_utils::fieldsMemory ( $res_agendamento, 0 );
		$arr_totalagenda = explode ( ",", $obj_agendamento->fc_totalagendado );

		$qtd = pg_num_rows($res_agendamento);
		$intProfissional = $objParam->sd27_i_codigo;

		db_inicio_transacao ();

		try{

			// busca o primeiro setor da unidade  incluso para recepção
	    $sSqlSetor  = " select min(sd91_codigo) as sd91_codigo from setorambulatorial ";
	    $sSqlSetor .= " where sd91_unidades = {$objParam->unidade} and sd91_local = 1 ";

	    $rsSetorUnidade = db_query($sSqlSetor);

	    $lErroBuscarSetor = false;
	    $sMsgErroSetor    = "Não foi encontrado um setor ambulatorial para esta unidade.\n";
	    $sMsgErroSetor   .= "Cadastre um setor ambulatorial em:\n\tCadastro > Setor Ambulatorial para o Local: RECEPÇÃO";
	    if ( !$rsSetorUnidade || pg_num_rows($rsSetorUnidade) == 0) {

	      $lErroBuscarSetor   = true;
	      $oRetorno->iStatus  = 2;
	      $oRetorno->sMessage = urlencode( $sMsgErroSetor );
	    }

	    $iCodigoSetorAmbulatorial = null;
	    if ( !$lErroBuscarSetor ) {

	      $iCodigoSetorAmbulatorial = db_utils::fieldsMemory($rsSetorUnidade, 0)->sd91_codigo;

	      if ( empty($iCodigoSetorAmbulatorial) ) {

	        $lErroBuscarSetor   = true;
	        $objRetorno->status  = 2;
					$objRetorno->message = urlencode( $sMsgErroSetor );
					break;
	      }
	    }

			//linca agendamento com prontuario
			for( $intQtd = 0; $intQtd < $qtd; $intQtd++ ){

				$obj_agendamento = db_utils::fieldsMemory ( $res_agendamento, $intQtd );
				$sd24_i_codigo = ( int ) $obj_agendamento->s102_i_prontuario;
				if (( int ) $obj_agendamento->s102_i_prontuario == 0) {

					$clprontagendamento->sql_record ( $clprontagendamento->sql_query ( null, "*", null, "s102_i_agendamento = {$obj_agendamento->sd23_i_codigo}" ) );
					if ($clprontagendamento->numrows == 0) {

						//Gerar número prontuário automático
						//gera numatend
						$sql_fc = "select fc_numatend()";
						$query_fc = db_query ( $sql_fc ) or die ( pg_errormessage () . "<br>$sql_fc  <br> $intQtd" );
						$fc_numatend = explode ( ",", pg_result ( $query_fc, 0, 0 ) );

						$clprontuarios->sd24_i_ano      				= trim ( $fc_numatend [0] );
						$clprontuarios->sd24_i_mes      				= trim ( $fc_numatend [1] );
						$clprontuarios->sd24_i_seq      				= trim ( $fc_numatend [2] );
						$clprontuarios->sd24_i_login    				= DB_getsession ( "DB_id_usuario" );
						$clprontuarios->sd24_i_unidade  				= $objParam->unidade;
						$clprontuarios->sd24_i_numcgs   				= isset ( $objParam->agendamentofa ) ? $obj_agendamento->sd23_i_numcgs : null;
						$clprontuarios->sd24_d_cadastro 				= $obj_agendamento->sd23_d_consulta; //date ( "Y-m-d", db_getsession ( "DB_datausu" ) );
						$clprontuarios->sd24_c_cadastro 				= $obj_agendamento->sd23_c_hora; //db_hora ();
						$clprontuarios->sd24_setorambulatorial  = $iCodigoSetorAmbulatorial;
						$clprontuarios->incluir (null);
						if ($clprontuarios->numrows_incluir == 0) {
							$objRetorno->status  = 2;
							$objRetorno->message = urlencode("Prontuários: " . $clprontuarios->erro_msg );
							break;
						}

						//linca agendamento com prontuario
						if (isset ( $objParam->agendamentofa ) && ( $objParam->agendamentofa == 'true'||$objParam->agendamentofa == true) ) {
							$clprontagendamento->s102_i_agendamento = $obj_agendamento->sd23_i_codigo;
							$clprontagendamento->s102_i_prontuario  = $clprontuarios->sd24_i_codigo;
							$clprontagendamento->incluir ( "" );
							if( $clprontagendamento->numrows_incluir == 0  ){
								$objRetorno->status  = 2;
								$objRetorno->message = urlencode("Prontuários: " . $clprontagendamento->erro_msg );
								break;
							}
							//Profissional de Atendimento
							$clprontprofatend->s104_i_prontuario   = $clprontuarios->sd24_i_codigo;
							$clprontprofatend->s104_i_profissional = $intProfissional;
							$clprontprofatend->incluir ( "" );
							if( $clprontprofatend->numrows_incluir == 0 ){
								$objRetorno->status  = 2;
								$objRetorno->message = urlencode("Prontuários: " . $clprontprofatend->erro_msg );
								break;
							}

						}
						//prontproced
						if (isset ( $obj_agendamento->s125_i_procedimento ) && ( int )$obj_agendamento->s125_i_procedimento > 0) {
							$clprontproced->sd29_i_prontuario   = $clprontuarios->sd24_i_codigo;
							$clprontproced->sd29_i_procedimento = $obj_agendamento->s125_i_procedimento;
							$clprontproced->sd29_i_profissional = $intProfissional;
							$clprontproced->sd29_d_data         = $obj_agendamento->sd23_d_consulta;
							$clprontproced->sd29_c_hora         = $obj_agendamento->sd23_c_hora;
							$clprontproced->sd29_i_usuario      = $obj_agendamento->sd23_i_usuario;
							$clprontproced->sd29_sigilosa      	= 'false';
							$clprontproced->incluir(null);
							if( $clprontproced->numrows_incluir == 0 ){
								$objRetorno->status  = 2;
								$objRetorno->message = urlencode("Prontuários: " . $clprontproced->erro_msg );
								break;
							}
							//Digitada sim
							$clprontuarios->sd24_c_digitada = 'S';
							$clprontuarios->alterar($clprontuarios->sd24_i_codigo);
							if( $clprontuarios->numrows_alterar == 0 ){
								$objRetorno->status  = 2;
								$objRetorno->message = urlencode("Prontuários: " . $clprontuarios->erro_msg );
								break;
							}
						}
					}//if numrows
				}//if
			}//for
		}catch (Exception $eException){
			$objRetorno->status  = 2;
			$objRetorno->message = urlencode ($eException->getMessage() );
		}

		db_fim_transacao ($objRetorno->status == 2);

		break;
}
echo $objJson->encode($objRetorno);
?>