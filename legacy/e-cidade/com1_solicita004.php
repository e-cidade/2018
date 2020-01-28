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
require_once ("libs/db_app.utils.php");
require_once ("std/db_stdClass.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_liborcamento.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_solicita_classe.php");
require_once ("classes/db_solicitem_classe.php");
require_once ("classes/db_solicitemele_classe.php");
require_once ("classes/db_solicitemunid_classe.php");
require_once ("classes/db_solicitempcmater_classe.php");
require_once ("classes/db_pcdotac_classe.php");
require_once ("classes/db_pcdotaccontrapartida_classe.php");
require_once ("classes/db_solicitatipo_classe.php");
require_once ("classes/db_orcreserva_classe.php");
require_once ("classes/db_orcreservasol_classe.php");
require_once ("classes/db_db_config_classe.php");
require_once ("classes/db_pctipocompra_classe.php");
require_once ("classes/db_db_depart_classe.php");
require_once ("classes/db_pcsugforn_classe.php");
require_once ("classes/db_pcparam_classe.php");
require_once ("classes/db_protprocesso_classe.php");
require_once ("classes/db_solicitemprot_classe.php");
require_once ("classes/db_pcproc_classe.php");
require_once ("classes/db_liclicitem_classe.php");
require_once ("classes/db_pactovalormov_classe.php");
require_once ("classes/db_pactovalormovsolicitem_classe.php");
require_once ("classes/db_orctiporecconveniosolicita_classe.php");
require_once ("classes/db_solicitaprotprocesso_classe.php");

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

$clsolicita                  = new cl_solicita;
$clsolicitem                 = new cl_solicitem;
$clsolicitemele              = new cl_solicitemele;
$clsolicitemunid             = new cl_solicitemunid;
$clsolicitempcmater          = new cl_solicitempcmater;
$clpcdotac                   = new cl_pcdotac;
$clorcreserva                = new cl_orcreserva;
$clorcreservasol             = new cl_orcreservasol;
$clsolicitatipo              = new cl_solicitatipo;
$clpctipocompra              = new cl_pctipocompra;
$cldb_depart                 = new cl_db_depart;
$clpcsugforn                 = new cl_pcsugforn;
$clpcparam                   = new cl_pcparam;
$cldb_config                 = new cl_db_config;
$clprotprocesso              = new cl_protprocesso;
$clsolicitemprot             = new cl_solicitemprot;
$clpcproc                    = new cl_pcproc;
$clliclicitem                = new cl_liclicitem;
$oDaoContrapartida           = new cl_pcdotaccontrapartida();
$oDaoItemPacto               = new cl_pactovalormovsolicitem();
$oDaoItemPactomov            = new cl_pactovalormov;
$oDaoOrctiporecConvenioPacto = new cl_orctiporecconveniosolicita();
$oDaoProcessoAdministrativo  = new cl_solicitaprotprocesso();

$opselec   = 1;
$db_opcao  = 1;
$db_opcaoBtnRegistroPreco = 1;
if(isset($lBloqueiaAncoraRegistro)){
  $db_opcaoBtnRegistroPreco = 3;
}


$db_botao  = true;
$departusu = true;
$confirma  = false;
$aParametrosOrcamento = db_stdClass::getParametro("orcparametro",array(db_getsession("DB_anousu")));
$lUtilizaPacto        = false;
if (count($aParametrosOrcamento) > 0) {

  if ($aParametrosOrcamento[0]->o50_utilizapacto == "t") {
    $lUtilizaPacto = true;
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<?
db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js, estilos.css");
?>
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body style="background-color: #CCCCCC; margin-top:30px;" >

<?
if (isset ($conf) && $conf == 'true') {
	$confirma = true;
}

$result_tipo = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "pc30_mincar,pc30_obrigamat,pc30_obrigajust,pc30_seltipo,pc30_sugforn,pc30_permsemdotac,pc30_contrandsol,pc30_tipoprocsol,pc30_gerareserva,pc30_passadepart"));
if ($clpcparam->numrows > 0) {
	db_fieldsmemory($result_tipo, 0);
} else {

	$db_botao = false;
	$msgalert = "Usuário:\\n\\nConfigure os parâmetros para continuar. \\n\\nAdministrador:";
}

if (isset($param) && trim($param) != "") {

  if ($pc30_contrandsol == "t") {

    db_redireciona('db_erros.php?db_erro=Sistema configurado para controle de andamento, esta opção fica desabilitada.');
    exit;
  }
}

if (isset ($incluir) || (isset ($importar) && $confirma == true)) {


  $db_opcaoBtnRegistroPreco = 3;
  $sqlerro = false;
	db_inicio_transacao();

  if (isset($param) && trim($param) != "") {

	  $flag_erro = true;
	  if (isset($codproc) && trim($codproc) != "" && $codproc > 0) {

	    $flag_erro = false;
	    $codproc2  = $codproc;
	  }
	  if (isset($codliclicita) && trim($codliclicita) != "" && $codliclicita > 0) {

	    $flag_erro     = false;
	    $codliclicita2 = $codliclicita;
	  }
	  $sqlerro = $flag_erro;

	  if ($sqlerro == true) {
	      $erro_msg = "Selecione um processo de compras ou licitação!";
	  }
	}

	if ($pc30_permsemdotac == "f") {
		$clsolicita->pc10_correto = "false";
	} else if ($pc30_permsemdotac == "t") {
			$clsolicita->pc10_correto = "true";
		}
	if (isset ($importar) && $importar != "") {

		$clpcdotac->sql_record("update empparametro set e39_anousu = e39_anousu where e39_anousu =".db_getsession("DB_anousu"));
		$result_importacao = $clsolicita->sql_record($clsolicita->sql_query_file($importar, "pc10_resumo,pc10_log,pc10_correto,pc10_data as data_imp,pc10_solicitacaotipo "));

		if ($clsolicita->numrows > 0) {

			db_fieldsmemory($result_importacao, 0);
			$ano_imp 							 = substr($data_imp, 0, 4);
			$clsolicita->pc10_data = date("Y-m-d", db_getsession("DB_datausu"));
			$clsolicita->pc10_log  = $pc10_log;
			if ($pc10_correto == "f") {
				$clsolicita->pc10_correto = "false";
			} else if ($pc10_correto == "t") {
			  $clsolicita->pc10_correto = "true";
			}
			$clsolicita->pc10_depto = db_getsession("DB_coddepto");
			if ($pc30_seltipo == 't') {

				$result_importacaotipo = $clsolicitatipo->sql_record($clsolicitatipo->sql_query_file($importar, "pc12_vlrap,pc12_tipo"));
				db_fieldsmemory($result_importacaotipo, 0);
				$clsolicitatipo->pc12_vlrap = $pc12_vlrap;
				$clsolicitatipo->pc12_tipo  = $pc12_tipo;
			}
		}

		//echo "<br><br>ERRO [1] => ". $sqlerro;
	}

  if (!$sqlerro) {

    $clsolicita->pc10_instit          = db_getsession("DB_instit");
	  $clsolicita->pc10_login           = db_getsession("DB_id_usuario");
	  $clsolicita->pc10_data            = date("Y-m-d", db_getsession("DB_datausu"));
	  $clsolicita->pc10_resumo          = addslashes(stripslashes(chop($pc10_resumo)));
	  $clsolicita->pc10_solicitacaotipo = $pc10_solicitacaotipo;
    $clsolicita->incluir(@ $pc10_numero);
    $pc10_numero = $clsolicita->pc10_numero;
	  if ($clsolicita->erro_status == 0) {
	    $sqlerro = true;
	  }
	  $erro_msg = $clsolicita->erro_msg;
	}
	//echo "<br><br>ERRO [2] => ". $sqlerro;
	/**
	 * Bloco que inclui o número do processo na solicitaprotprocesso
	 */
	if (!$sqlerro && !empty($pc90_numeroprocesso)) {

	  $oDaoProcessoAdministrativo->pc90_numeroprocesso = $pc90_numeroprocesso;
	  $oDaoProcessoAdministrativo->pc90_solicita       = $pc10_numero;
	  $oDaoProcessoAdministrativo->incluir(null);
	  if ($oDaoProcessoAdministrativo->erro_status == 0) {

	    $sqlerro  = true;
	    $erro_msg = $oDaoProcessoAdministrativo->erro_msg;
	  }
	}
	//echo "<br><br>ERRO [3] => ". $sqlerro;
	if (!$sqlerro) {

	  $sSqlTipoConvenio = $oDaoOrctiporecConvenioPacto->sql_query_file(null,"*",null,"o78_solicita={$pc10_numero}");
	  $rsTipoConvenio   = $oDaoOrctiporecConvenioPacto->sql_record($sSqlTipoConvenio);
	  if ($oDaoOrctiporecConvenioPacto->numrows > 0) {

	    $oPactoPlano = db_utils::fieldsMemory($rsTipoConvenio, 0);
	    $oDaoOrctiporecConvenioPacto->o78_pactoplano = $oPactoPlano->o78_pactoplano;
	    $oDaoOrctiporecConvenioPacto->o78_solicita   = $pc10_numero;
	    $oDaoOrctiporecConvenioPacto->incluir(null);
	    if ($oDaoOrctiporecConvenioPacto->erro_status == 0) {

	      $sqlerro  = true;
	  	  $erro_msg = $oDaoOrctiporecConvenioPacto->erro_msg;
	    }
	  }
	}
	//echo "<br><br>ERRO [4] => ". $sqlerro;
	if ($sqlerro == false && $pc30_seltipo == "t") {

	  $clsolicitatipo->pc12_numero = $pc10_numero;
	  $clsolicitatipo->incluir($pc10_numero);

	  if ($clsolicitatipo->erro_status == 0) {

	    $sqlerro = true;
		  $erro_msg = $clsolicitatipo->erro_msg;

	  }
	}
	//echo "<br><br>ERRO [5] => ". $sqlerro;
	/**
	 * caso a solicitacao seje do tipo 5, devemos
	 * incluir o a informaca na tabela
	 * solicita Vinculo
	 */
	 if ($pc10_solicitacaotipo == 5) {

	   if ($pc54_solicita == "") {

	     $erro_msg = "Informe o Registro de Preço!";
	     $sqlerro  = true;

	   }
	   if (!$sqlerro) {

	     $oDaoSolicitaVinculo = db_utils::getDao("solicitavinculo");
	     $oDaoSolicitaVinculo->pc53_solicitafilho = $pc10_numero;
	     $oDaoSolicitaVinculo->pc53_solicitapai   = $pc54_solicita;

	     $oDaoSolicitaVinculo->incluir(null);
	     if ($oDaoSolicitaVinculo->erro_status == 0) {

	       $erro_msg = $oDaoSolicitaVinculo->erro_msg;
         $sqlerro  = true;
	     }
	   }
	 }

	if (isset ($importar) && trim($importar) != "" && $sqlerro == false) {


	if (isset($lRegistroPreco)) {
	  $sItensNaoImportados = db_getsession("sCodigoItensSemSaldo");
	}
	$sWhereItens = "";
	if (isset($sItensNaoImportados) && !empty($sItensNaoImportados)) {
	  $sWhereItens = " and pc11_codigo not in ({$sItensNaoImportados})";

	}

	$sSqlItem = $clsolicitem->sql_query_file(null,"pc11_codigo as codigo,
		                                             pc11_numero,
		                                             pc11_seq,
		                                             pc11_quant,
		                                             pc11_vlrun,
		                                             pc11_prazo,
		                                             pc11_pgto,
		                                             pc11_resum,
		                                             pc11_just,
		                                             pc11_liberado",
		                                            "pc11_seq",
		                                            " pc11_numero=".$importar." {$sWhereItens}");
	  //die($sSqlItem);
		$result_importacaoitem =   $clsolicitem->sql_record($sSqlItem);


		$numrows_importacaoitem = $clsolicitem->numrows;
		$sequencia = 0;
		for ($i = 0; $i < $numrows_importacaoitem; $i ++) {

			$sequencia ++;
			db_fieldsmemory($result_importacaoitem, $i);



			if ($pc30_obrigajust == 't') {

				if ((isset ($pc11_just) && $pc11_just == "") || !isset ($pc11_just)) {
					$sqlerro = true;
					$erro_msg = "Usuário: \\n\\nImportação abortada.\\nJustificativa para compra não informada.\\n\\nAdministrador:";
					break;
				} else {

					if (strlen(trim($pc11_just)) < $pc30_mincar) {
						$sqlerro = true;
						$erro_msg = "Usuário: \\n\\nImportação abortada.\\nJustificativa para compra deve ter no mínimo $pc30_mincar caracteres.\\n\\nAdministrador:";
						break;
					}
				}
			}
			//echo "<br><br>ERRO [7] => ". $sqlerro;
			if ($ano_imp != db_getsession("DB_anousu")) {

			} else {

			  $iCodigoSolicitemImportado = $codigo;

				$clsolicitem->pc11_numero = $pc10_numero;
				$clsolicitem->pc11_seq = $sequencia;
				$clsolicitem->pc11_quant = $pc11_quant;
				$clsolicitem->pc11_vlrun = $pc11_vlrun;
				$clsolicitem->pc11_prazo = addslashes(stripslashes(chop($pc11_prazo)));
				$clsolicitem->pc11_pgto  = addslashes(stripslashes(chop($pc11_pgto)));
				$clsolicitem->pc11_resum = addslashes(stripslashes(chop($pc11_resum)));
				$clsolicitem->pc11_just  = addslashes(stripslashes(chop($pc11_just)));

        if (isset($param) && trim($param) != ""){
				  $liberado = "true";
				} else {
				  $liberado = "false";
				}

				$clsolicitem->pc11_liberado = $liberado;
				$clsolicitem->incluir(null);
				$pc11_codigo = $clsolicitem->pc11_codigo;

				/*
				 * se for regisatro de preço
				 * criamos vinculo na solicitemvinclulo
				 *
				*/
				if (isset($lRegistroPreco)) {

  				$lVinculoRegistroPreco = db_getsession("lVinculoRegistroPreco");
  				if ( isset($lVinculoRegistroPreco) ) {

            /**
             *  para criar o vinculo na solicitemvinculo:
             *  1º - retornar o codigo do item da solicitacao que esta sendo importada = $importa
             *  2º - descobrir o codigo do pai da solicitacao do item na
             *
             *    $iCodigoSolicitemImportado
             *    $iCodigoItemNovo
             *    $iCodigoPaiItemImportado
  				   */
  				  //echo $iCodigoSolicitemImportado."\n";
  				  $iCodigoItemNovo           = $clsolicitem->pc11_codigo;

  				  $sSqlPai  = " select *                                                  ";
  				  $sSqlPai .= " from solicitemvinculo                                     ";
  				  $sSqlPai .= " where pc55_solicitemfilho = {$iCodigoSolicitemImportado}  ";

  				  $rsSolicitacaoPai = db_query($sSqlPai);

  				  if ($rsSolicitacaoPai > 0) {
  			      $iCodigoPaiItemImportado = db_utils::fieldsMemory($rsSolicitacaoPai, 0)->pc55_solicitempai;
  				  }

  				 // echo "<br/> Pai {$iCodigoPaiItemImportado} filho {$iCodigoSolicitemImportado} ";
  				//  echo "<br/> Pai {$iCodigoPaiItemImportado} filho importado {$iCodigoItemNovo} <br/>**<br/>";

  				  $iCodigoSolicitacao = $clsolicita->pc10_numero;

  				  /**
  				   *   Descobrir o código do item na pcmater, de acordo com o vinculo na solicitempcmater,
  				   *   usando  o código do item da solicitação que está sendo importada.
  				   *
  				  **/

  				  $sSqlCodigoPcmater = "select * from solicitempcmater where pc16_solicitem = {$iCodigoSolicitemImportado}";
  				  $rsCodigoItemPcmater = db_query($sSqlCodigoPcmater);

  				  if ($rsCodigoItemPcmater > 0) {
  				    $iCodigoPcmater = db_utils::fieldsMemory($rsCodigoItemPcmater, 0)->pc16_codmater;
  				  }

      		  /**
      		   *  Cria vinculos na solicitemvinculo
      		   *
      		   **/
  				  require_once("classes/solicitacaocompras.model.php");
  				  require_once("classes/db_pcprocitem_classe.php");
  				  require_once("classes/db_pcorcam_classe.php");
  				  require_once("classes/db_pcorcamitem_classe.php");
  				  require_once("classes/db_pcorcamforne_classe.php");
  				  require_once("classes/db_pcorcamitemproc_classe.php");
  				  require_once("classes/db_pcorcamjulg_classe.php");
  				  require_once("classes/db_pcorcamval_classe.php");
  				  require_once("model/ItemEstimativa.model.php");

      		  $oSolicitacao = new solicitacaoCompra($iCodigoSolicitacao);
  				  $oSolicitacao->addItemRegistroPreco($iCodigoItemNovo, $iCodigoPcmater, $pc54_solicita, $clsolicitem->pc11_quant, $iCodigoPaiItemImportado);

  				}
				}

				if ($clsolicitem->erro_status == 0) {

					$sqlerro = true;
					$erro_msg = $clsolicitem->erro_msg;
					break;
				}
				//echo "<br><br>ERRO [8] => ". $sqlerro;
				if ($sqlerro == false) {
					$result_pcmater = $clsolicitempcmater->sql_record($clsolicitempcmater->sql_query(null,
					                                                  null,
					                                                  "pc16_codmater",
					                                                  "",
					                                                  " pc10_numero=$importar and pc11_codigo=$codigo"));
					if ($clsolicitempcmater->numrows > 0) {

						db_fieldsmemory($result_pcmater, 0);
						$clsolicitempcmater->pc16_codmater = $pc16_codmater;
						$clsolicitempcmater->pc16_solicitem = $pc11_codigo;
						$clsolicitempcmater->incluir($pc16_codmater, $pc11_codigo);
						if ($clsolicitempcmater->erro_status == 0) {

							$sqlerro = true;
							$erro_msg ($clsolicitempcmater->erro_msg);
							break;
						}
						//echo "<br><br>ERRO [9] => ". $sqlerro;
					}else if ($pc30_obrigamat == 't') {

						$sqlerro = true;
						$erro_msg = "Usuário: \\n\\nImportação abortada.\\nCódigo do material não informado. Campo obrigatório.\\n\\nAdministrador:";
						break;
					}
				}
				//echo "<br><br>ERRO [10] => ". $sqlerro;
				if ($sqlerro == false) {

					$result_elemento = $clsolicitemele->sql_record($clsolicitemele->sql_query_file($codigo, null, "pc18_codele"));
					if ($clsolicitemele->numrows > 0) {

						db_fieldsmemory($result_elemento, 0);
						$clsolicitemele->incluir($pc11_codigo, $pc18_codele);
						if ($clsolicitemele->erro_status == 0) {

							$sqlerro = true;
							$erro_msg = $clsolicitemele->erro_msg;
						}
					}
				}
				//echo "<br><br>ERRO [11] => ". $sqlerro;
				if ($sqlerro == false) {

					$result_solicitemunid = $clsolicitemunid->sql_record($clsolicitemunid->sql_query($codigo, "pc17_unid,pc17_quant"));
					if ($clsolicitemunid->numrows > 0) {

						db_fieldsmemory($result_solicitemunid, 0);
						$clsolicitemunid->pc17_unid = $pc17_unid;
						$clsolicitemunid->pc17_quant = $pc17_quant;
						$clsolicitemunid->incluir($pc11_codigo);
						if ($clsolicitemunid->erro_status == 0) {

							$sqlerro = true;
							$erro_msg = $clsolicitemunid->erro_msg;
							break;
						}
					}
				}
				//echo "<br><br>ERRO [12] => ". $sqlerro;
				if ($sqlerro == false) {

					$result_importacaodot = $clpcdotac->sql_record($clpcdotac->sql_query_file(
					                                               $codigo,
					                                               null,
					                                               null,
					                                               "pc13_anousu,
					                                                pc13_coddot,
					                                                pc13_codigo,
					                                                pc13_sequencial,
					                                                pc13_depto,
					                                                pc13_quant,
					                                                pc13_valor,
					                                                pc13_codele"));
					$numrows_importacaodot = $clpcdotac->numrows;
					for ($ii = 0; $ii < $numrows_importacaodot; $ii ++) {

						db_fieldsmemory($result_importacaodot, $ii);
						if (isset ($pc13_coddot) && $pc13_coddot != "") {
							// ===================================================>>
							// *******rotina que verifica se ainda existe saldo disponivel******************//
							// rotina para calcular o saldo final
							$result = db_dotacaosaldo(8, 2, 2, "true", "o58_coddot=$pc13_coddot", db_getsession("DB_anousu"));
							db_fieldsmemory($result, 0, true);

							$tot = ((0 + $atual_menos_reservado) - (0 + $pc13_valor));
						}

						if ($pc13_valor <= 0) {
							$pc30_gerareserva = 'f';
						}
						$sqlerrosaldo = false;
						if ($pc30_gerareserva == 't') {
							//echo "<BR><BR>".("isset($atual_menos_reservado) && $atual_menos_reservado<$pc13_valor) || (isset($tot) && $tot<0)) && $sqlerro==false");
							if (((isset ($atual_menos_reservado) && $atual_menos_reservado < $pc13_valor)
							      || (isset ($tot) && $tot < 0)) && $sqlerro == false) {

						  	$sqlerrosaldo = true;
							  $saldoreserva = $atual_menos_reservado;
							} else {
							  $saldoreserva = $pc13_valor;
							}
						}

						$clpcdotac->pc13_anousu = $pc13_anousu;
						$clpcdotac->pc13_coddot = $pc13_coddot;
						$clpcdotac->pc13_depto  = $pc13_depto;
						$clpcdotac->pc13_quant  = $pc13_quant;
						$clpcdotac->pc13_valor  = $pc13_valor;
						$clpcdotac->pc13_codele = $pc13_codele;
						$clpcdotac->pc13_codigo = $pc11_codigo;
						$clpcdotac->incluir(null);
						if ($clpcdotac->erro_status == 0) {

							$sqlerro = true;
							$erro_msg = $clpcdotac->erro_msg;
							break;
						}
						/**
						 * Caso exista contrapartida, na dotacao devemos importar também a
						 * contrapartida (tabela pcdotaccontrapartida)
						 */
						$rsContrapartida = $oDaoContrapartida->sql_record($oDaoContrapartida->sql_query_file(null,
						                                                                      "*",
						                                                                       null,
						                                                                      "pc19_pcdotac = {$pc13_sequencial}"));
            if ($oDaoContrapartida->numrows > 0) {

               $oContrapartida = db_utils::fieldsMemory($rsContrapartida, 0);
               $oDaoContrapartida->pc19_orctiporec = $oContrapartida->pc19_orctiporec;
               $oDaoContrapartida->pc19_pcdotac    = $clpcdotac->pc13_sequencial;
               $oDaoContrapartida->pc19_valor      = $oContrapartida->pc19_valor;
               $oDaoContrapartida->incluir(null);
               if ($oDaoContrapartida->erro_status == 0) {

                 $sqlerro  = true;
							   $erro_msg = $oDaoContrapartida->erro_msg;
							   break;
               }
            }
						if ($pc30_gerareserva == 't') {

							if ($sqlerro == false) {

								$clorcreserva->o80_anousu = db_getsession("DB_anousu");
								$clorcreserva->o80_coddot = $pc13_coddot;
								$clorcreserva->o80_dtfim  = date('Y', db_getsession('DB_datausu'))."-12-31";
								$clorcreserva->o80_dtini  = date('Y-m-d', db_getsession('DB_datausu'));
								$clorcreserva->o80_dtlanc = date('Y-m-d', db_getsession('DB_datausu'));

								if (isset ($sqlerrosaldo) && $sqlerrosaldo == false) {

									$clorcreserva->o80_valor = $pc13_valor;
									$saldoreserva = $pc13_valor;
								} else {
									$clorcreserva->o80_valor = $saldoreserva;
								}
								$clorcreserva->o80_descr = " ";
								if ($saldoreserva > 0) {

									$clorcreserva->incluir(null);
									$o80_codres = $clorcreserva->o80_codres;
									if ($clorcreserva->erro_status == 0) {

										$sqlerro = true;
										$erro_msg = $clorcreserva->erro_msg;
									}
									if ($sqlerro == false) {

									  $clorcreservasol->o82_codres    = $o80_codres;
									  $clorcreservasol->o82_solicitem = $clpcdotac->pc13_codigo;
									  $clorcreservasol->o82_pcdotac   = $clpcdotac->pc13_sequencial;
										$clorcreservasol->incluir(null);
										if ($clorcreservasol->erro_status == 0) {

											$sqlerro = true;
											$erro_msg = $clorcreservasol->erro_msg;
										}
									}
								}
							}
						}
					}
				}

			if ($pc30_contrandsol == 't') {

				if ($sqlerro == false) {

					$result_cgmpref = $cldb_config->sql_record($cldb_config->sql_query(null, "numcgm,z01_nome", null, "codigo=".db_getsession("DB_instit")));
					db_fieldsmemory($result_cgmpref, 0);
					$clprotprocesso->p58_codigo     = $pc30_tipoprocsol;
					$clprotprocesso->p58_dtproc     = date('Y-m-d', db_getsession("DB_datausu"));
					$clprotprocesso->p58_id_usuario = db_getsession("DB_id_usuario");
					$clprotprocesso->p58_numcgm     = $numcgm;
					$clprotprocesso->p58_requer     = $z01_nome;
					$clprotprocesso->p58_coddepto   = db_getsession("DB_coddepto");
					$clprotprocesso->p58_codandam   = '0';
					$clprotprocesso->p58_obs        = "";
					$clprotprocesso->p58_despacho   = "";
					$clprotprocesso->p58_ano        = db_getsession("DB_anousu");
					$clprotprocesso->p58_hora       = db_hora();
					$clprotprocesso->p58_numero     = $pc30_tipoprocsol;
					$clprotprocesso->p58_interno    = 't';
					$clprotprocesso->p58_publico    = '0';
          $clprotprocesso->p58_instit     = db_getsession("DB_instit");
					$clprotprocesso->incluir(null);
					$codproc = $clprotprocesso->p58_codproc;
					if ($clprotprocesso->erro_status == 0) {

						$sqlerro = true;
						$erro_msg = $clprotprocesso->erro_msg;
					}
				}
				if ($sqlerro == false) {

					$clsolicitemprot->pc49_protprocesso = $codproc;
					$clsolicitemprot->pc49_solicitem = $pc11_codigo;
					$clsolicitemprot->incluir($pc11_codigo);
					if ($clsolicitemprot->erro_status == 0) {

						$sqlerro = true;
						$erro_msg = $clsolicitemprot->erro_msg;
					}
				}
			}
		  }
      /*
       * Caso o item possua vinculacao de controle de pacto incluimos ele para a nova solicitacao
       */
		  $sSqlItemPacto = $oDaoItemPacto->sql_query(null,"*",null,"o101_solicitem ={$codigo}");
		  $rsItemPacto   = $oDaoItemPacto->sql_record($sSqlItemPacto);
		  if ($oDaoItemPacto->numrows > 0) {

		    $aItemsPacto  = db_utils::getCollectionByRecord($rsItemPacto);
		    foreach ($aItemsPacto as $oItemPacto) {

          $oDaoItemPactomov->o88_pactovalor = $oItemPacto->o88_pactovalor;
          $oDaoItemPactomov->o88_quantidade = $oItemPacto->o88_quantidade;
          $oDaoItemPactomov->o88_valor      = $oItemPacto->o88_valor;
          $oDaoItemPactomov->incluir(null) ;
          if ($oDaoItemPactomov->erro_status == 0) {

            $sqlerro  = true;
						$erro_msg = $oDaoItemPactomov->erro_msg;
						break;
          }
		    }
		    if (!$sqlerro) {

		      $oDaoItemPacto->o101_pactovalormov = $oDaoItemPactomov->o88_sequencial;
		      $oDaoItemPacto->o101_solicitem     = $pc11_codigo;
		      $oDaoItemPacto->incluir(null);
		      if ($oDaoItemPacto->erro_status == 0) {

		      	$sqlerro  = true;
						$erro_msg = $oDaoItemPacto->erro_msg;
						break;
		      }
		    }
		  }

		 // echo "<br>" .$clsolicitem->pc11_resum;
		}

		//die();
		if ($sqlerro == false && $pc30_sugforn) {

			$result_importacaoforn = $clpcsugforn->sql_record($clpcsugforn->sql_query_file($importar, "pc40_numcgm"));
			$numrows_importacaoforn = $clpcsugforn->numrows;
			for ($i = 0; $i < $numrows_importacaoforn; $i ++) {

				db_fieldsmemory($result_importacaoforn, $i);
				$clpcsugforn->pc40_solic   = $pc10_numero;
				$clpcsugforn->pc40_numcgm  = $pc40_numcgm;
				$clpcsugforn->incluir($pc10_numero, $pc40_numcgm);
				if ($clpcsugforn->erro_status == 0) {

				  $sqlerro = true;
				  $erro_msg = $clpcsugforn->erro_msg;
				  break;
				}
			}
		}
	}
	if ($sqlerro==true){
		//db_msgbox($erro_msg);
	}
	/**
	 * Caso o usuario escolheu um plano, incluimos na tabela /
	 */
	// $sqlerro = true;
	if ($lUtilizaPacto) {

	  if (!$sqlerro && isset($o74_sequencial) && $o74_sequencial != "") {

	    $oDaoOrctiporecConvenioPacto  = db_utils::getDao("orctiporecconveniosolicita");
	    $oDaoOrctiporecConvenioPacto->o78_pactoplano = $o74_sequencial;
	    $oDaoOrctiporecConvenioPacto->o78_solicita   = $pc10_numero;
	    $oDaoOrctiporecConvenioPacto->incluir(null);
	    if ($oDaoOrctiporecConvenioPacto->erro_status == 0) {

	      $sqlerro = true;
	      $erro_msg = $oDaoOrctiporecConvenioPacto->erro_msg;

	    }
	  }
	}
	//$sqlerro = true;
	db_fim_transacao($sqlerro);
}
?>
  <div class="container">
			<?php
		  	require_once ("forms/db_frmsolicita.php");
		  ?>
  </div>
</body>
</html>
<?
if (isset ($incluir) || (isset ($importar) && $confirma == true)) {


  $db_opcaoBtnRegistroPreco = 3;
	if ($sqlerro == true) {
		db_msgbox(str_replace("\n", "\\n", $erro_msg));
		if ($clsolicita->erro_campo != "") {
			echo "<script> document.form1.".$clsolicita->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$clsolicita->erro_campo.".focus();</script>";
		};
	} else {

		if (isset($param) && trim($param) != "") {
	    $parametro = "&param=alterar&param_ant=incluir";
			if (isset($codproc2) && trim($codproc2) != "" && $codproc2 > 0) {
		  	$parametro .= "&codproc=".$codproc2;
		  }
		  if (isset($codliclicita2) && trim($codliclicita2) != "" && $codliclicita2 > 0) {
		  	$parametro .= "&codliclicita=".$codliclicita2;
		  }
		} else {
		  $parametro = "";
		}

		if ($pc30_contrandsol == 't') {
			db_msgbox("Controle da solicitação ativo!!É necessário efetuar o andamento inicial após a inclusão dos itens!!");
		}
		if (isset ($importar) && trim($importar) != "") {

			if ($ano_imp != db_getsession("DB_anousu")) {

				echo "<script>
	              if (confirm('ATENÇÃO: \\n Solicitação de outro ano!!\\nDeseja incluir os itens com suas respectivas Dotações?')) {
								  js_OpenJanelaIframe('top.corpo.iframe_solicita','db_iframe_dotac','com4_altdotacsol001.php?importado=$importar&codnovo=$pc10_numero','Dotações',true);
								} else {
				          location.href='com1_solicita005.php?db_opcaoBtnRegistroPreco=3&liberaaba=true&chavepesquisa=$pc10_numero';
				        }
						  </script>";
			} else {
				db_redireciona("com1_solicita005.php?db_opcaoBtnRegistroPreco=3&liberaaba=true&chavepesquisa=$pc10_numero$parametro");
			}
		} else {
			db_redireciona("com1_solicita005.php?db_opcaoBtnRegistroPreco=1&liberaaba=true&chavepesquisa=$pc10_numero$parametro");
		}
	}
}

if (isset ($confirma) && $confirma == false && isset ($importar)) {

  $db_opcaoBtnRegistroPreco = 3;
  $sQueryString = "";
  if (isset($pc54_solicita)){
    $sQueryString = "&pc54_solicita={$pc54_solicita}";
  }

	echo "<script>

		      if(confirm('ATENÇÃO: \\nSerão importados os itens, as dotações e os fornecedores sugeridos desta solicitação.\\nDeseja continuar?')){
		      	location.href = 'com1_solicita004.php?lBloqueiaAncoraRegistro=1&db_opcaoBtnRegistroPreco=3$sQueryString&importar=$importar&conf=true';
		      }
		    </script>";
}
?>