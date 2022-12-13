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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_utils.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("libs/JSON.php"));

$objJson             = new services_json();
$objParam            = $objJson->decode(str_replace("\\","",$_POST["json"]));
$objRetorno          = new stdClass();
$objRetorno->status  = 1;
$objRetorno->message = '';


if ($objParam->exec == "getFAA") {

	$clprontuarios   = db_utils::getDao("prontuarios_ext");
	$res_prontuarios = $clprontuarios->sql_record($clprontuarios->sql_query_nolote_ext("", "*, m.z01_nome as profissional", "", " sd24_i_codigo = {$objParam->sd24_i_codigo} "));

	$objRetorno->status  = 2;
	if( $clprontuarios->numrows > 0){
		$obj_prontuarios = db_utils::fieldsMemory($res_prontuarios, 0 );
		if( $objParam->sd24_i_unidade == $obj_prontuarios->sd24_i_unidade ){
			if( $obj_prontuarios->sd24_c_digitada == 'N'){
		   		if( (int)$obj_prontuarios->sd59_i_lote > 0 ){
		   			$objRetorno->message = urlencode( "FAA foi lançada no lote {$obj_prontuarios->sd59_i_lote}.");
		   		}else{
		   			$objRetorno->status = 1;
		   			$objRetorno->itens  = db_utils::getCollectionByRecord($res_prontuarios, true, false, true);
		   			//Sessão para armazenar profissional
					//if (!isset($_SESSION["objRegProfissional"])) {
						$clprontagendameto   = db_utils::getDao("prontagendamento_ext");
						$strSQL              = $clprontagendameto->sql_query_ext(null,"especmedico.*, rhcbo.*, medicos.*, cgm.*",null, "prontagendamento.s102_i_prontuario = {$objParam->sd24_i_codigo}");
						$res_prontagendameto = $clprontagendameto->sql_record( $strSQL );
						if( $clprontagendameto->numrows > 0 ){
							$obj_prontagendameto = db_utils::getCollectionByRecord($res_prontagendameto,true,false,true);
			  				$_SESSION["objRegProfissional"] = serialize($obj_prontagendameto);
						}
					//}
		   		}
			}else{
		   		$objRetorno->message = urlencode( 'FAA já digitada.' );
			}
		}else{
			$objRetorno->message = urlencode( "FAA pertence a UPS - {$obj_prontuarios->sd24_i_unidade}." );
		}
	}else{
	   	$objRetorno->message = urlencode( 'FAA não localizada.');
	}

}else if ($objParam->exec == "getCID") {
	$clsau_cid   = db_utils::getDao("sau_cid");
	$res_sau_cid = $clsau_cid->sql_record( $clsau_cid->sql_query("","sd70_i_codigo, sd70_c_cid, sd70_c_nome","sd70_c_cid"," sd70_c_cid = '{$objParam->sd70_c_cid}' ") );
	if($clsau_cid->numrows > 0){
		$objRetorno->itens  = db_utils::getCollectionByRecord($res_sau_cid, true, false, true);
	}else{
		$objRetorno->status  = 2;
		$objRetorno->message = urlencode( 'CID não encontrado.' );
	}

}else if ($objParam->exec == "incluir" || $objParam->exec == "alterar" ) {
	db_inicio_transacao();

	$objRetorno->message = urlencode("Registro ".($objParam->exec == "incluir"?"incluído":"alterado")." com sucesso.");

	//Gera fc_numatend
	if( (int)$objParam->sd24_i_codigo == 0 ){
		$sql_fc      = "select fc_numatend()";
		$query_fc    = db_query($sql_fc) or die(pg_errormessage().$sql_fc);
		$fc_numatend = explode(",",pg_result($query_fc,0,0));
		unset( $_SESSION["objRegProfissional"] );
	}

	//Prontuarios/CGS_UND/
	$clprontuarios   = db_utils::getDao("prontuarios_ext");
	$clcgs_und       = db_utils::getDao("cgs_und");
	//$clprontcid      = db_utils::getDao("prontcid");

	$oDaoSetorAmbulatorial = new cl_setorambulatorial();

	if( $objRetorno->status == 1 ){

		//Prontuario
		$clprontuarios->sd24_i_unidade     = $objParam->sd24_i_unidade;
		$clprontuarios->sd24_i_numcgs      = $objParam->z01_i_cgsund;
		$clprontuarios->sd24_t_diagnostico = $objParam->sd24_t_diagnostico;

		if( (int)$objParam->sd24_i_codigo == 0 ){
			$clprontuarios->sd24_i_ano      = trim($fc_numatend[0]);
			$clprontuarios->sd24_i_mes      = trim($fc_numatend[1]);
			$clprontuarios->sd24_i_seq      = trim($fc_numatend[2]);
			$clprontuarios->sd24_d_cadastro = date("Y-m-d",db_getsession("DB_datausu"));
			$clprontuarios->sd24_c_cadastro = date("H",db_getsession("DB_datausu")).":".date("m",db_getsession("DB_datausu"));
			$clprontuarios->sd24_i_login    = DB_getsession("DB_id_usuario");
			$clprontuarios->sd24_c_digitada = 'N';

			$sSetorAmbulatorialCampos  = "sd91_codigo";
    	$sSetorAmbulatorialWhere   = "sd91_local = 1 and sd91_unidades = {$objParam->sd24_i_unidade}";
    	$sSetorAmbulatorialOrder   = " sd91_codigo limit 1";
    	$sSetorAmbulatorialSql     = $oDaoSetorAmbulatorial->sql_query_file( null, $sSetorAmbulatorialCampos, $sSetorAmbulatorialOrder, $sSetorAmbulatorialWhere );
    	$rsSetorAmbulatorial       = db_query( $sSetorAmbulatorialSql );

    	if ( !$rsSetorAmbulatorial || pg_num_rows($rsSetorAmbulatorial) == 0 ) {

    		$sMsg  = "Não foi encontrado um setor ambulatorial para esta unidade.\n";
				$sMsg .= "Cadastre um setor de ambulatorial em:\n\tCadastro > Setor Ambulatorial para o Local: RECEPÇÃO";
				$objRetorno->message  = urlencode( $sMsg );
				$objRetorno->status   = 2;
    	} else {

	    	$clprontuarios->sd24_setorambulatorial = db_utils::fieldsMemory( $rsSetorAmbulatorial, 0 )->sd91_codigo;
				$clprontuarios->incluir(null);
				$objParam->sd24_i_codigo = $clprontuarios->sd24_i_codigo;
    	}

		}else{
			$clprontuarios->sd24_i_codigo = $objParam->sd24_i_codigo;
			$clprontuarios->alterar($objParam->sd24_i_codigo);
		}

		if ( !empty($objParam->sd24_i_codigo) ) {

			$objRetorno->sd24_i_codigo = $objParam->sd24_i_codigo;


			if( $clprontuarios->numrows_incluir > 0 || $clprontuarios->numrows_alterar > 0){
				$objRetorno->status  = 1;
				$objRetorno->message = urlencode( "Digitação de FAA salva com sucesso!" );
			}else{
				$objRetorno->status  = 2;
				$objRetorno->message = urlencode( "Erro ao salvar a Digitação de FAA." );
			}
		}
	}


	db_fim_transacao( $objRetorno->status == 2 );

}else if ($objParam->exec == "excluir") {
	$clprontuarios   = db_utils::getDao("prontuarios_ext");
	$clprontproced   = db_utils::getDao("prontproced");
	$clprontprocedcid= db_utils::getDao("prontprocedcid");

	db_inicio_transacao();

	$objRetorno->message = urlencode("Registro excluído com sucesso.");

	//$clprontprocecid->excluir(null, " sd55_i_prontuario = {$objParam->sd24_i_codigo} ");
	$clprontprocedcid->excluir(null,"s135_i_prontproced in (select sd29_i_codigo from prontproced where  sd29_i_prontuario = {$objParam->sd24_i_codigo})");
	if( $clprontprocedcid->erro_status == "0" && $clprontprocedcid->numrows_excluir == 0 ){
		$objRetorno->status  = 2;
		$objRetorno->message = urlencode( $clprontprocedcid->erro_msg );
	}else if( $objRetorno->status == 1 ){
		$clprontproced->excluir(null, " sd29_i_prontuario = {$objParam->sd24_i_codigo} ");
		if( $clprontproced->erro_status == "0" && $clprontproced->numrows_excluir == 0 ){
			$objRetorno->status  = 2;
			$objRetorno->message = urlencode( $clprontproced->erro_msg );
		}else if( $objRetorno->status == 1 ){
			$clprontuarios->excluir($objParam->sd24_i_codigo);
			if( $clprontproced->erro_status == "0" && $clprontproced->numrows_excluir == 0 ){
				$objRetorno->status  = 2;
				$objRetorno->message = urlencode( $clprontproced->erro_msg );
			}
		}
	}

	db_fim_transacao( $objRetorno->status == 2 );

}

echo $objJson->encode($objRetorno);
?>