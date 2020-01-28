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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_utils.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("libs/JSON.php"));

$objJson    = new services_json();
$objParam   = $objJson->decode(str_replace("\\","",$_POST["json"]));

$objRetorno          = new stdClass();
$objRetorno->status  = 1;
$objRetorno->message = '';

$booRetorno         = new stdClass();
$booRetorno->status = '';

switch ($objParam->exec){
	//Especialidade
	case "getEspecialidade":
		$clespecmedico   = db_utils::getDao("especmedico");
		$str_where  = "     especmedico.sd27_c_situacao   = 'A' ";

		if( isset($objParam->rh70_estrutural) ){
			$str_where .= " and rhcbo.rh70_estrutural = '{$objParam->rh70_estrutural}' ";
		}else{
			//$str_where .= " and especmedico.sd27_b_principal  = 't' ";
		}

		$str_where .= " and unidademedicos.sd04_i_unidade = {$objParam->sd24_i_unidade}";

		if( isset($objParam->sd03_i_codigo) ){
			$str_where .= " and unidademedicos.sd04_i_medico  = {$objParam->sd03_i_codigo}";
		}else{
			$str_where .= " and a.z01_nome  = '{$objParam->z01_nome}'";
		}

		$res_especmedico = $clespecmedico->sql_record( $clespecmedico->sql_query("","*","especmedico.sd27_b_principal, sd04_i_codigo limit 1","$str_where") );

		if( $clespecmedico->numrows > 0){
			$objRetorno->itens  = db_utils::getCollectionByRecord($res_especmedico, true, false, true);
		}else{
			$objRetorno->status  = 2;
			$objRetorno->message = urlencode( 'Especialidade não encontrada.' );
		}
		break;

	//Grid Procedimentos
	case "getGridProcedimentos":
		$clprontproced     = db_utils::getDao("prontproced_ext");

		$strCampos = "sd29_i_codigo,
					sd58_i_login,
					sd03_i_codigo,
					z01_nome,
					sd29_i_profissional,
					rh70_sequencial,
					rh70_estrutural,
					rh70_descr,
					sd29_i_procedimento,
					sd63_c_procedimento,
					sd63_c_nome,
					sd29_d_data,
					sd29_c_hora,
					sd29_t_tratamento,
					false as tmp_b_tabela ";
		$strSQL = $clprontproced->sql_query_ext("",$strCampos,""," prontproced.sd29_i_prontuario = {$objParam->sd24_i_codigo}");

		$res_procedimentos = $clprontproced->sql_record( $strSQL );

		$booRetorno->status = $clprontproced->numrows == 0? 2: 1;

		if( $clprontproced->numrows > 0 ){
			$objRetorno->itens  = db_utils::getCollectionByRecord($res_procedimentos, true, false, true);
		}
		if(isset($_SESSION['objRegProfissional'])){
			$objRetorno->profissional = unserialize($_SESSION['objRegProfissional']);
		}
		break;

	//Procedimento
	case "getProcedimento":

		$clsau_proccbo = db_utils::getDao("sau_proccbo");
		$strWhere = " sau_procedimento.sd63_c_procedimento = '{$objParam->sd63_c_procedimento}'";

		if (file_exists("funcoes/db_func_sau_proccbo.php") == true) {
			include(modification("funcoes/db_func_sau_proccbo.php"));
		} else {
			$campos = "sau_proccbo.*";
		}

		//remove filtro de unidade
		if(!isset($objParam->sd24_i_unidade)){
			$objParam->sd24_i_unidade=0;
			$lFiltraServico = false;
		}else{
			$lFiltraServico = true;
		}

		$strSQL = $clsau_proccbo->sql_query_func("",
               									$campos,
               									"sd96_i_anocomp desc , sd96_i_mescomp desc limit 1",
               									$strWhere,
               									$objParam->sd24_i_unidade,
               									$lFiltraServico, $objParam->rh70_sequencial);
		$strSQLCID  = "select *, ";
		$strSQLCID .= "  ( select count(*) ";
		$strSQLCID .= "      from sau_proccid ";
		$strSQLCID .= "     where sd72_i_procedimento = db_sd96_i_procedimento ";
		$strSQLCID .= "       and sd72_i_anocomp = sd96_i_anocomp ";
		$strSQLCID .= "       and sd72_i_mescomp = sd96_i_mescomp ";
		$strSQLCID .= "  ) as intCID ";
		$strSQLCID .= "  from ( $strSQL ) as xx";
		$res_sau_proccbo = $clsau_proccbo->sql_record( $strSQLCID );

		if( $clsau_proccbo->numrows > 0){
			$objRetorno->itens  = db_utils::getCollectionByRecord($res_sau_proccbo, true, false, true);
		}else{
			$objRetorno->status = 2;
			$objRetorno->message = urlencode( "Procedimento [{$objParam->sd63_c_procedimento}], não encontrado ou não vinculado com a especialidade [{$objParam->rh70_descr}] " );
		}

		break;

	case "getCID":
		if( $objParam->booValidaCID == true ){
			$clsau_proccid   = db_utils::getDao("sau_proccid");
			$strWhere        = " sd70_c_cid = '{$objParam->sd70_c_cid}' ";
			$strWhere       .= " and sd63_i_codigo = {$objParam->sd29_i_procedimento} ";

			$res_sau_proccid = $clsau_proccid->sql_record(
				$clsau_proccid->sql_query("",
					"sd70_i_codigo, sd70_c_cid, sd70_c_nome","sd70_c_cid", $strWhere
				)
			);
		}else{
			$clsau_cid   = db_utils::getDao("sau_cid");
			$strWhere    = " sd70_c_cid = '{$objParam->sd70_c_cid}' ";

			$sql=$clsau_cid->sql_query("",
					"sd70_i_codigo, sd70_c_cid, sd70_c_nome","sd70_c_cid", $strWhere
				);
			$res_sau_proccid = $clsau_cid->sql_record($sql);
		}
		if(($res_sau_proccid!=false) && (pg_num_rows($res_sau_proccid)  > 0)){
			$objRetorno->itens  = db_utils::getCollectionByRecord($res_sau_proccid, true, false, true);
		}else{
			$objRetorno->status  = 2;
			$objRetorno->message = urlencode( 'CID não encontrado.' );
		}
		break;

	case "getAlterar":
		$clprontproced     = db_utils::getDao("prontproced_ext");

		$strCampos = "sd29_i_codigo,
					sd58_i_login,
					sd03_i_codigo,
					z01_nome,
					sd29_i_profissional,
					rh70_sequencial,
					rh70_estrutural,
					rh70_descr,
					sd29_i_procedimento,
					sd63_c_procedimento,
					sd63_c_nome,
					sd29_d_data,
					sd29_c_hora,
					sd29_t_tratamento,
					sau_cid.*,
					false as tmp_b_tabela ";
		$strSQL = $clprontproced->sql_query_ext("",$strCampos,""," prontproced.sd29_i_codigo = {$objParam->sd29_i_codigo}");

		$res_procedimentos = $clprontproced->sql_record( $strSQL );

		$booRetorno->status = $clprontproced->numrows == 0? 2: 1;

		if( $clprontproced->numrows > 0 ){
			$objRetorno->itens  = db_utils::getCollectionByRecord($res_procedimentos, true, false, true);
		}
		break;
	case "Incluir":
		$clprontproced   = db_utils::getDao("prontproced");
		$clprontprocedcid= db_utils::getDao("prontprocedcid");
		$clprontuarios   = db_utils::getDao("prontuarios");

		db_inicio_transacao();
		$intQuant = isset($objParam->intQuant)&&(int)$objParam->intQuant>0?$objParam->intQuant:1;

		for( $intX=0; $intX<$intQuant; $intX++){
			$clprontproced->sd29_i_prontuario   = $objParam->sd24_i_codigo;
			$clprontproced->sd29_i_procedimento = $objParam->sd29_i_procedimento;
			$clprontproced->sd29_d_data         = implode("-",array_reverse(explode("/", $objParam->sd29_d_data)));
			$clprontproced->sd29_c_hora         = $objParam->sd29_c_hora;
			$clprontproced->sd29_t_tratamento   = $objParam->sd29_t_tratamento;
			$clprontproced->sd29_i_usuario      = DB_getsession("DB_id_usuario");
			$clprontproced->sd29_d_cadastro     = date("Y-m-d",db_getsession("DB_datausu"));
			$clprontproced->sd29_c_cadastro     = date("H",db_getsession("DB_datausu")).":".date("m",db_getsession("DB_datausu"));
			$clprontproced->sd29_i_profissional = $objParam->sd29_i_profissional;
			$clprontproced->sd29_t_diagnostico  = "null";
			$clprontproced->sd29_sigilosa				= 'false';
			$clprontproced->incluir(null);
			if( $clprontproced->numrows_incluir == 0){
				$objRetorno->status  = 2;
				$objRetorno->message = urlencode($clprontproced->erro_msg);
				break;
			}else{
				if( (int)$objParam->sd70_i_codigo > 0 ){
					$clprontprocedcid->s135_i_prontproced = $clprontproced->sd29_i_codigo;
					$clprontprocedcid->s135_i_cid         = $objParam->sd70_i_codigo;
					$clprontprocedcid->incluir(null);
					if( $clprontprocedcid->numrows_incluir == 0){
						$objRetorno->status  = 2;
						$objRetorno->message = urlencode($clprontprocedcid->erro_msg);
						break;
					}
				}
			}
		}//for
		if( $objRetorno->status == 1){
			$clprontuarios->sd24_c_digitada = 'S';
			$clprontuarios->sd24_i_login    = DB_getsession("DB_id_usuario");
			$clprontuarios->sd24_i_codigo   = $objParam->sd24_i_codigo;
			$clprontuarios->alterar($objParam->sd24_i_codigo);
			if( $clprontuarios->numrows_alterar == 0){
				$objRetorno->status  = 2;
				$objRetorno->message = urlencode($clprontuarios->erro_msg);
			}
		}
		db_fim_transacao( $objRetorno->status == 2 );
		break;
	case "Alterar":
		//Altera na tabela
		if( (int)$objParam->sd29_i_codigo > 0){
			db_inicio_transacao();

			$clprontproced   = db_utils::getDao("prontproced");

			$clprontproced->sd29_i_codigo       = $objParam->sd29_i_codigo;
			$clprontproced->sd29_i_procedimento = $objParam->sd29_i_procedimento;
			$clprontproced->sd29_i_profissional = $objParam->sd29_i_profissional;
			$clprontproced->sd29_t_tratamento   = $objParam->sd29_t_tratamento;
			$clprontproced->sd29_d_data         = implode("-",array_reverse(explode("/", $objParam->sd29_d_data)));
			$clprontproced->sd29_c_hora         = $objParam->sd29_c_hora;
			$clprontproced->sd29_i_usuario      = DB_getsession("DB_id_usuario");
			$clprontproced->alterar($objParam->sd29_i_codigo);
			if( $clprontproced->numrows_alterar == 0 ){
				$objRetorno->status  = 2;
				$objRetorno->message = urlencode( $clprontproced->erro_msg );
			}else{
				$clprontprocedcid= db_utils::getDao("prontprocedcid");
				$clprontprocedcid->excluir(null, "s135_i_prontproced = {$objParam->sd29_i_codigo}");
				if( (int)$objParam->sd70_i_codigo > 0 ){
					$clprontprocedcid->s135_i_prontproced = $objParam->sd29_i_codigo;
					$clprontprocedcid->s135_i_cid         = $objParam->sd70_i_codigo;
					$clprontprocedcid->incluir(null);
					if( $clprontprocedcid->numrows_incluir == 0){
						$objRetorno->status  = 2;
						$objRetorno->message = urlencode($clprontprocedcid->erro_msg);
						break;
					}
				}
			}
			db_fim_transacao( $objRetorno->status == 2 );
		}
		break;
	case "Excluir":
		if( (int)$objParam->sd29_i_codigo > 0){
			db_inicio_transacao();

			$clprontproced   = db_utils::getDao("prontproced");
			$clprontprocedcid= db_utils::getDao("prontprocedcid");
			$clprontuarios   = db_utils::getDao("prontuarios");
		  $clfechapront    = db_utils::getDao("sau_fechapront");

			$clfechapront->excluir(null, "sd98_i_prontproced = {$objParam->sd29_i_codigo}");
	  	$clprontproced->sd29_i_codigo       = $objParam->sd29_i_codigo;
			$clprontprocedcid->excluir(null, "s135_i_prontproced = {$objParam->sd29_i_codigo}");
			if( $clprontprocedcid->erro_status == "0" && $clprontprocedcid->numrows_excluir == 0 ){
				$objRetorno->status  = 2;
				$objRetorno->message = urlencode( $clprontprocedcid->erro_msg );
			}
			if( $objRetorno->status == 1){
				$clprontproced->excluir($objParam->sd29_i_codigo);
				if( $clprontproced->numrows_excluir == 0 ){
					$objRetorno->status  = 2;
					$objRetorno->message = urlencode( $clprontproced->erro_msg );
				}else{
					$clprontproced->sql_record( $clprontproced->sql_query(null,"*","", "prontproced.sd29_i_prontuario = {$objParam->sd24_i_codigo}") );
					if( $clprontproced->numrows == 0){
				 		$clprontuarios->sd24_c_digitada = 'N';
						$clprontuarios->sd24_i_login  = DB_getsession("DB_id_usuario");
						$clprontuarios->sd24_i_codigo = $objParam->sd24_i_codigo;
						$clprontuarios->alterar($objParam->sd24_i_codigo);
						if( $clprontuarios->numrows_alterar == 0){
							$objRetorno->status  = 2;
							$objRetorno->message = urlencode( $clprontuarios->erro_msg );
						}
					}
				}
			}
			db_fim_transacao( $objRetorno->status == 2 );
		}
		break;
  case "geraGridCid":
  	$oDaoProntuarios = db_utils::getdao('prontproced_ext');
  	$sWhere = " sd70_c_cid <> '' and  sd24_i_numcgs={$objParam->sd24_i_numcgs}";
  	$sSql    = $oDaoProntuarios->sql_query_ext("","sd24_d_cadastro,sd70_c_cid,sd70_c_nome","sd24_d_cadastro desc",$sWhere);
  	$result = $oDaoProntuarios->sql_record($sSql);
  	$objRetorno->sql = $sSql;
    if ($oDaoProntuarios->numrows > 0) {
    	$aItens = array();
    	for($iX=0;$iX<$oDaoProntuarios->numrows;$iX++){
    		$oProntuarios = db_utils::fieldsmemory($result,$iX,true);
    	  $aItens[$iX][0] = $oProntuarios->sd24_d_cadastro;
    	  $aItens[$iX][1] = $oProntuarios->sd70_c_cid;
    	  $aItens[$iX][2] = urlencode($oProntuarios->sd70_c_nome);
    	}
    	$objRetorno->aItens = $aItens;
    }else{

  	  $objRetorno->status  = 2;
      $objRetorno->message = "CGS sem cid! ";

    }
  	break;
}


echo $objJson->encode($objRetorno);

?>