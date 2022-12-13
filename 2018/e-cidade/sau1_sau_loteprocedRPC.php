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

require ("libs/db_stdlib.php");
require ("libs/db_utils.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("dbforms/db_funcoes.php");
include ("libs/JSON.php");

$objJson = new services_json ( );
$objParam = $objJson->decode ( str_replace ( "\\", "", $_POST ["json"] ) );

$objRetorno = new stdClass ( );
$objRetorno->status = 1;
$objRetorno->message = '';

switch ($objParam->exec) {
	//Especialidade
	case "getEspecialidade" :
		$clespecmedico = db_utils::getDao ( "especmedico" );
		$str_where = "     especmedico.sd27_c_situacao   = 'A' ";

		if (isset ( $objParam->rh70_estrutural )) {
			$str_where .= " and rhcbo.rh70_estrutural = '{$objParam->rh70_estrutural}' ";
		} else {
			//$str_where .= " and especmedico.sd27_b_principal  = 't' ";
		}

		$str_where .= " and unidademedicos.sd04_i_unidade = {$objParam->sd24_i_unidade}";

		if (isset ( $objParam->sd03_i_codigo )) {
			$str_where .= " and unidademedicos.sd04_i_medico  = {$objParam->sd03_i_codigo}";
		} else {
			$str_where .= " and a.z01_nome  = '{$objParam->z01_nome}'";
		}

		$res_especmedico = $clespecmedico->sql_record ( $clespecmedico->sql_query ( "", "*", "especmedico.sd27_b_principal, sd04_i_codigo limit 1", "$str_where" ) );

		if ($clespecmedico->numrows > 0) {
			$objRetorno->itens = db_utils::getCollectionByRecord ( $res_especmedico, true, false, true );
		} else {
			$objRetorno->status = 2;
			$objRetorno->message = urlencode ( 'Especialidade não encontrada.' );
		}
		break;

	//Grid Procedimentos
	case "getGridProcedimentos" :
		$clprontproced = db_utils::getDao ( "prontproced_ext" );

		$strCampos = "sd58_i_codigo,
					sd59_i_codigo,
					sd29_i_codigo,
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
					sd70_i_codigo,
					sd70_c_cid,
					sd70_c_nome,
					false as tmp_b_tabela ";
		$strSQL = $clprontproced->sql_query_ext ( "", $strCampos, "", " prontproced.sd29_i_prontuario =
														(
															 select sau_lotepront.sd59_i_prontuario
															   from sau_lotepront
															  where sau_lotepront.sd59_i_lote = {$objParam->sd58_i_codigo}
															  limit 1
														)" );
		//$strSQL .= " union ";
		//$strSQL .= $cltmp_prontproced->sql_query("",$strCampos.",true as tmp_b_tabela","");


		$res_procedimentos = $clprontproced->sql_record ( $strSQL );

		$booRetorno->status = $clprontproced->numrows == 0 ? 2 : 1;

		if ($clprontproced->numrows > 0) {
			$objRetorno->itens = db_utils::getCollectionByRecord ( $res_procedimentos, true, false, true );
			if (! isset ( $_SESSION ['objRegistros'] )) {
				$_SESSION ["objRegistros"] = serialize ( $objRetorno->itens );
			}
		} else if (isset ( $_SESSION ['objRegistros'] )) {
			$objRetorno->itens = unserialize ( $_SESSION ["objRegistros"] );
		}

		if (isset ( $_SESSION ['objRegProfissional'] )) {
			$objRetorno->profissional = unserialize ( $_SESSION ['objRegProfissional'] );
		}
		break;

	//Procedimento
	case "getProcedimento" :

		$clsau_proccbo = db_utils::getDao("sau_proccbo");
		$strWhere = " sau_procedimento.sd63_c_procedimento = '".$objParam->sd63_c_procedimento."'";

		if (file_exists("funcoes/db_func_sau_proccbo.php") == true) {
			include ("funcoes/db_func_sau_proccbo.php");
		} else {
			$campos = "sau_proccbo.*";
		}

		//remove filtro de unidade
		if (!isset($objParam->sd24_i_unidade)) {
			$objParam->sd24_i_unidade = 0;
		}
		$strSQL = $clsau_proccbo->sql_query_func ( "", $campos, "sd96_i_anocomp desc , sd96_i_mescomp desc limit 1",
		                                          $strWhere, $objParam->sd24_i_unidade, false, $objParam->rh70_sequencial);
    //echo " SQL: $strSQL ";
		$strSQLCID = "select *, ";
		$strSQLCID .= "  ( select count(*) ";
		$strSQLCID .= "      from sau_proccid ";
		$strSQLCID .= "     where sd72_i_procedimento = db_sd96_i_procedimento ";
		$strSQLCID .= "       and sd72_i_anocomp = sd96_i_anocomp ";
		$strSQLCID .= "       and sd72_i_mescomp = sd96_i_mescomp ";
		$strSQLCID .= "  ) as intCID ";
		$strSQLCID .= "  from ( $strSQL ) as xx";
		$res_sau_proccbo = $clsau_proccbo->sql_record ( $strSQLCID );

		if ($clsau_proccbo->numrows > 0) {
			$objRetorno->itens = db_utils::getCollectionByRecord ( $res_sau_proccbo, true, false, true );
		} else {
			$objRetorno->status = 2;
			$objRetorno->message = urlencode ( "Procedimento [{$objParam->sd63_c_procedimento}], não encontrado ou não vinculado com a especialidade [{$objParam->rh70_descr}]" );
		}

		break;
	case "getCID" :
		if ($objParam->booValidaCID == false) {
			$clsau_proccid = db_utils::getDao ( "sau_cid" );
		} else {
			$clsau_proccid = db_utils::getDao ( "sau_proccid" );
		}
		$strWhere = " sd70_c_cid = '{$objParam->sd70_c_cid}' ";
		$strWhere .= $objParam->booValidaCID == false ? "" : " and sd63_i_codigo = {$objParam->sd29_i_procedimento} ";

		$res_sau_proccid = $clsau_proccid->sql_record ( $clsau_proccid->sql_query ( "", "sd70_i_codigo, sd70_c_cid, sd70_c_nome", "sd70_c_cid", $strWhere ) );
		if ($clsau_proccid->numrows > 0) {
			$objRetorno->itens = db_utils::getCollectionByRecord ( $res_sau_proccid, true, false, true );
		} else {
			$objRetorno->status = 2;
			$objRetorno->message = urlencode ( 'CID não encontrado.' );
		}
		break;

	case "getSair" :
		if (isset ( $_SESSION ["objRegistros"] )) {
			$objRegProcedimento = unserialize ( $_SESSION ["objRegistros"] );

			if (sizeof ( $objRegProcedimento ) > 0) {
				//Verifica se tem algum procedimento com codig em branco
				foreach ( $objRegProcedimento as $intKey => $valor ) {
					$objRegistro = $objRegProcedimento [$intKey];
					if (( int ) $objRegistro->sd29_i_codigo == 0) {
						$objRetorno->status = 2;
						$objRetorno->message = urlencode ( "E R R O ! ! ! \n\nSessão aberta. \nProcesse os registro para sair." );
						break;
					}
				}
			}
			if ($objRetorno->status == 1) {
				unset ( $_SESSION ["objRegistros"] );
				if (isset ( $_SESSION ['objRegProfissional'] ))
					unset ( $_SESSION ['objRegProfissional'] );
			}
		}
		break;
	case "getAlterar" :
		if (! isset ( $_SESSION ["objRegistros"] )) {
			$objRetorno->status = 2;
			$objRetorno->message = urlencode ( "E R R O ! ! ! \n\nSessão não aberta." );
		} else {
			$objRegProcedimento = unserialize ( $_SESSION ["objRegistros"] );
			$objRegProcedimentoNew = array ();
			foreach ( $objRegProcedimento as $intKey => $valor ) {

				if ($objParam->intIterator == $intKey) {
					$objRegProcedimento [$intKey]->tmp_i_registro = $objParam->intIterator + 1;
					$objRetorno->itens [] = $objRegProcedimento [$intKey];
					break;
				}
			}
			if (! isset ( $objRetorno->itens )) {
				$objRetorno->status = 2;
				$objRetorno->message = urlencode ( "E R R O ! ! ! \n\nRegistro não localizado." );
			}
		}
		break;
	case "Incluir" :
		if (! isset ( $_SESSION ["objRegistros"] )) {
			$objRegProcedimento = array ();
		} else {
			$objRegProcedimento = unserialize ( $_SESSION ["objRegistros"] );
		}
		$objAdd = getParametros($objParam);
		$objAdd->tmp_b_tabela = 't';
		$objAdd->tmp_i_registro = sizeof ( $objRegProcedimento ) + 1;

		$objRegProcedimento [] = $objAdd;
		$objRetorno->itens = $objRegProcedimento;
		$_SESSION ["objRegistros"] = serialize ( $objRetorno->itens );

		if (isset ( $_SESSION ['objRegProfissional'] )) {
			$objRetorno->profissional = unserialize ( $_SESSION ['objRegProfissional'] );
		}

		$objRetorno->message = urlencode ( "Registro incluído com sucesso." );

		break;
	case "Alterar" :
		if (! isset ( $_SESSION ["objRegistros"] )) {
			$objRetorno->status = 2;
			$objRetorno->message = urlencode ( "E R R O ! ! ! \n\nSessão não aberta." );
		} else {
			$objRegProcedimento = unserialize ( $_SESSION ["objRegistros"] );
			$objRegProcedimentoNew = array ();

			foreach ( $objRegProcedimento as $intKey => $valor ) {

				if ($objParam->intIterator == $intKey) {
					//Altera na tabela
					if (( int ) $objParam->sd29_i_codigo > 0) {
						db_inicio_transacao ();

						$clprontproced = db_utils::getDao ( "prontproced" );
						$clprontprocedcid = db_utils::getDao ( "prontprocedcid" );
						$res_prontproced = $clprontproced->sql_record ( $clprontproced->sql_query ( $objParam->sd29_i_codigo ) );
						if ($clprontproced->numrows > 0) {
							//Busca os procedimento das FAA's
							//$result = $clprontproced->sql_record(
							//             $clprontproced->sql_query("","sd29_i_procedimento",""," prontproced.sd29_i_codigo = $sd29_i_codigo " ) );
							$obj_procalterar = db_utils::fieldsMemory ( $res_prontproced, 0 );

							//Busca FAA's
							$result = $clprontproced->sql_record ( $clprontproced->sql_query ( "", "*", "", " prontproced.sd29_i_prontuario in
							             							(
																		 select sau_lotepront.sd59_i_prontuario
																		   from sau_lotepront
																		  where sau_lotepront.sd59_i_lote = $objParam->sd58_i_codigo
																	)
																	and prontproced.sd29_i_procedimento = {$obj_procalterar->sd29_i_procedimento}
																	" ) );
							$x = $clprontproced->numrows;
							for($recno = 0; $recno < $x; $recno ++) {

								$obj_prontproced = db_utils::fieldsMemory ( $result, $recno );

								$clprontproced->sd29_sigilosa       = 'false';
								$clprontproced->sd29_i_codigo = $obj_prontproced->sd29_i_codigo;
								$clprontproced->sd29_i_procedimento = $objParam->sd29_i_procedimento;
								$clprontproced->sd29_i_profissional = $objParam->sd29_i_profissional;
								$clprontproced->sd29_t_tratamento = $objParam->sd29_t_tratamento;
								$clprontproced->sd29_i_usuario = DB_getsession ( "DB_id_usuario" );
								$clprontproced->alterar ( $obj_prontproced->sd29_i_codigo );
								if ($clprontproced->numrows_alterar == 0) {
									$objRetorno->status = 2;
									$objRetorno->message = urlencode ( $clprontproced->erro_msg );
									break;
								}
								//prontprocedcid
								$clprontprocedcid->excluir ( null, "s135_i_prontproced = {$obj_prontproced->sd29_i_codigo}" );
								if (( int ) $objParam->sd70_i_codigo > 0) {
									$clprontprocedcid->s135_i_prontproced = $obj_prontproced->sd29_i_codigo;
									$clprontprocedcid->s135_i_cid = $objParam->sd70_i_codigo;
									$clprontprocedcid->incluir ( null );
									if ($clprontprocedcid->numrows_incluir == 0) {
										$objRetorno->status = 2;
										$objRetorno->message = urlencode ( $clprontprocedcid->erro_msg );
										break;
									}
								}
							} //for
						}
						db_fim_transacao ( $objRetorno->status == 2 );
					}

					//Altera na Sessão
					if ($objRetorno->status == 1) {
						$objAdd = getParametros($objParam);
						$objAdd->tmp_b_tabela = 't';
						$objAdd->tmp_i_registro = $objParam->intIterator + 1;

						$objRegProcedimento [$intKey] = $objAdd;
						$objRetorno->itens = $objRegProcedimento;
						$_SESSION ["objRegistros"] = serialize ( $objRetorno->itens );
						$objRetorno->message = urlencode ( "Registro alterado com sucesso." );
					}
					break;
				}
			}
			if (! isset ( $objRetorno->itens ) && $objRetorno->status == 1) {
				$objRetorno->status = 2;
				$objRetorno->message = urlencode ( "E R R O ! ! ! \n\nRegistro não localizado." . $objParam->intIterator );
			}
		}
		break;
	case "Excluir" :
		if (! isset ( $_SESSION ["objRegistros"] )) {
			$objRetorno->status = 2;
			$objRetorno->message = urlencode ( "E R R O ! ! ! \n\nSessão não aberta." );
		} else {
			$objRegProcedimento = unserialize ( $_SESSION ["objRegistros"] );
			$objRegProcedimentoNew = array ();
			//if( $objParam->sd29_i_codigo == ""){
			foreach ( $objRegProcedimento as $intKey => $valor ) {

				$objRegistro = $objRegProcedimento [$intKey];
				if ($objParam->intIterator != $intKey && $objRegistro->sd29_i_procedimento != $objParam->sd29_i_procedimento) {
					$objRegProcedimentoNew [] = $objRegistro;
				} else {
					//Altera na tabela
					if (( int ) $objRegistro->sd29_i_codigo > 0) {
						db_inicio_transacao ();

						$clprontproced = db_utils::getDao ( "prontproced" );
						$clprontprocedcid = db_utils::getDao ( "prontprocedcid" );

						$res_prontproced = $clprontproced->sql_record ( $clprontproced->sql_query ( $objRegistro->sd29_i_codigo ) );
						if ($clprontproced->numrows > 0) {
							//Busca os procedimento das FAA's
							$obj_procalterar = db_utils::fieldsMemory ( $res_prontproced, 0 );

							//Busca FAA's
							$strSQL = $clprontproced->sql_query ( "", "sd29_i_codigo", "", " prontproced.sd29_i_prontuario in
								             							(
																			 select sau_lotepront.sd59_i_prontuario
																			   from sau_lotepront
																			  where sau_lotepront.sd59_i_lote = $objParam->sd58_i_codigo
																		)
																		and prontproced.sd29_i_procedimento = {$obj_procalterar->sd29_i_procedimento}
																		" );

							$result = $clprontproced->sql_record ( $strSQL );
							$x = $clprontproced->numrows;
							for($recno = 0; $recno < $x; $recno ++) {
								$obj_prontproced = db_utils::fieldsMemory ( $result, $recno );
								//prontprocedcid
								$clprontprocedcid->excluir ( null, "s135_i_prontproced = {$obj_prontproced->sd29_i_codigo}" );
								if ($clprontprocedcid->erro_status == "0" && $clprontprocedcid->numrows_excluir == 0) {
									$objRetorno->status = 2;
									$objRetorno->message = urlencode ( $clprontprocedcid->erro_msg );
									break;
								}

								//prontproced
								$clprontproced->sd29_i_codigo = $obj_prontproced->sd29_i_codigo;
								$clprontproced->excluir ( $obj_prontproced->sd29_i_codigo );
								if ($clprontproced->numrows_excluir == 0) {
									$objRetorno->status = 2;
									$objRetorno->message = urlencode ( $clprontproced->erro_msg );
									break;
								}
							}
						}
						db_fim_transacao ( $objRetorno->status == 2 );
					}
				}
			} //foreeach


			if ($objRetorno->status == 1) {
				$objRetorno->message = urlencode ( "Registro excluído com sucesso." );
				$objRetorno->itens = $objRegProcedimentoNew;
				$_SESSION ["objRegistros"] = serialize ( $objRetorno->itens );
			}
			//}
		}
		break;
	case "Processar" :

		$objRetorno->status = 2;
		$objRetorno->message = urlencode ( "Nenhum registro para processar." );
		if (! isset ( $_SESSION ["objRegistros"] )) {
			$objRetorno->message = urlencode ( "E R R O ! ! ! \n\nSessão não aberta." );
		} else {
			$objRegProcedimento = unserialize ( $_SESSION ["objRegistros"] );
			foreach ( $objRegProcedimento as $intKey => $valor ) {
				if (( int ) $objRegProcedimento [$intKey]->sd29_i_codigo == 0) {
					$objRegistro = $objRegProcedimento [$intKey];
					$objRetorno->status = 1;
					$objRetorno->message = urlencode ( "Todos registros processados." );

					$clsau_lote = db_utils::getDao ( "sau_lote" );
					$clsau_lotepront = db_utils::getDao ( "sau_lotepront_ext" );
					$clprontuarios = db_utils::getDao ( "prontuarios" );
					$clprontproced = db_utils::getDao ( "prontproced" );
					$clprontprocedcid = db_utils::getDao ( "prontprocedcid" );

					$result = $clsau_lotepront->sql_record ( $clsau_lotepront->sql_query_ext ( "", "*", "", " sau_lotepront.sd59_i_lote = $objRegistro->sd58_i_codigo " ) );
					if ($clsau_lotepront->numrows > 0) {
						db_inicio_transacao ();

						for($recno = 0; $recno < $clsau_lotepront->numrows; $recno ++) {


							$obj_sau_lote = db_utils::fieldsMemory ( $result, $recno );

							$clprontproced->sd29_sigilosa       = 'false';
							$clprontproced->sd29_i_prontuario   = $obj_sau_lote->sd59_i_prontuario;
							$clprontproced->sd29_i_procedimento = $objRegistro->sd29_i_procedimento;
							$clprontproced->sd29_d_data         = implode ( "-", array_reverse ( explode ( "/", $objRegistro->sd29_d_data ) ) );
							$clprontproced->sd29_c_hora         = $objRegistro->sd29_c_hora;
							$clprontproced->sd29_t_tratamento   = $objRegistro->sd29_t_tratamento;
							$clprontproced->sd29_i_usuario      = DB_getsession ( "DB_id_usuario" );
							$clprontproced->sd29_d_cadastro     = date ( "Y-m-d", db_getsession ( "DB_datausu" ) );
							$clprontproced->sd29_c_cadastro     = date ( "H", db_getsession ( "DB_datausu" ) ) . ":" . date ( "m", db_getsession ( "DB_datausu" ) );
							$clprontproced->sd29_i_profissional = $objRegistro->sd29_i_profissional;
							$clprontproced->sd29_t_diagnostico  = "null";
							$clprontproced->incluir ( null );
							if ($clprontproced->numrows_incluir == 0) {
								$objRetorno->status = 2;
								$objRetorno->message = urlencode ( $clprontproced->erro_msg );
								break;
							}
							//prontprocedcid
							if (( int ) $objRegistro->sd70_i_codigo > 0) {
								$clprontprocedcid->s135_i_prontproced = $clprontproced->sd29_i_codigo;
								$clprontprocedcid->s135_i_cid = $objRegistro->sd70_i_codigo;
								$clprontprocedcid->incluir ( null );
								if ($clprontprocedcid->numrows_incluir == 0) {
									$objRetorno->status = 2;
									$objRetorno->message = urlencode ( $clprontprocedcid->erro_msg );
									break;
								}
							}

							//prontuarios
							$clprontuarios->sd24_c_digitada = 'S';
							$clprontuarios->sd24_i_codigo = $obj_sau_lote->sd59_i_prontuario;
							$clprontuarios->alterar ( $obj_sau_lote->sd59_i_prontuario );
							if ($clprontuarios->numrows_alterar == 0) {
								$objRetorno->status = 2;
								$objRetorno->message = urlencode ( $clprontproced->erro_msg );
								break;
							}
						} //for


						if ($objRetorno->status != 2) {
							//sau_lote
							$clsau_lote->sd58_c_digitada = 'S';
							$clsau_lote->sd58_i_login = DB_getsession ( "DB_id_usuario" );
							$clsau_lote->sd58_i_codigo = $objRegistro->sd58_i_codigo;
							$clsau_lote->alterar ( $objRegistro->sd58_i_codigo );
							if ($clsau_lote->numrows_alterar == 0) {
								$objRetorno->status = 2;
								$objRetorno->message = urlencode ( $clsau_lote->erro_msg );
								break;
							}

							db_fim_transacao ( $objRetorno->status == 2 );
							if ($objRetorno->status == 1) {
								unset ( $_SESSION ["objRegistros"] );
								if (isset ( $_SESSION ['objRegProfissional'] )) {
									unset ( $_SESSION ['objRegProfissional'] );
								}
							}
						} // if status
					} //numrows
				} //intKey
			} //foreach
		} //else session
		break;
}

function getParametros($objParam) {

  $objAdd = new stdClass ( );
  $objAdd->sd58_i_codigo = $objParam->sd58_i_codigo;
  $objAdd->sd59_i_codigo = $objParam->sd59_i_codigo;
  $objAdd->sd29_i_codigo = $objParam->sd29_i_codigo;
  $objAdd->sd58_i_login = $objParam->sd58_i_login;
  $objAdd->sd03_i_codigo = $objParam->sd03_i_codigo;
  $objAdd->z01_nome = $objParam->z01_nome;
  $objAdd->sd29_i_profissional = $objParam->sd29_i_profissional;
  $objAdd->rh70_sequencial = $objParam->rh70_sequencial;
  $objAdd->rh70_estrutural = $objParam->rh70_estrutural;
  $objAdd->rh70_descr = $objParam->rh70_descr;
  $objAdd->sd29_i_procedimento = $objParam->sd29_i_procedimento;
  $objAdd->sd63_c_procedimento = $objParam->sd63_c_procedimento;
  $objAdd->sd63_c_nome = $objParam->sd63_c_nome;
  $objAdd->sd29_d_data = $objParam->sd29_d_data;
  $objAdd->sd29_c_hora = $objParam->sd29_c_hora;
  $objAdd->sd29_t_tratamento = $objParam->sd29_t_tratamento;
  $objAdd->sd70_i_codigo = $objParam->sd70_i_codigo;
  $objAdd->sd70_c_cid = $objParam->sd70_c_cid;
  $objAdd->sd70_c_nome = $objParam->sd70_c_nome;
  return $objAdd;
}

echo $objJson->encode ( $objRetorno );

?>