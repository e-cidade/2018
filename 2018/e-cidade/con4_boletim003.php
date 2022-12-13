<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("libs/db_liborcamento.php");
include ("libs/db_libcontabilidade.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_boletim_classe.php");
include ("classes/db_saltes_classe.php");
include ("classes/db_orcreceita_classe.php");
include ("classes/db_orcreceitaval_classe.php");
include ("classes/db_orcfontes_classe.php");
include ("classes/db_orcfontesdes_classe.php");
include ("classes/db_conlancambol_classe.php");
include ("classes/db_conlancam_classe.php");
include ("classes/db_conlancamrec_classe.php");
include ("classes/db_conlancamval_classe.php");
include ("classes/db_conlancamdoc_classe.php");
include ("classes/db_conlancamlr_classe.php");
include ("classes/db_conlancampag_classe.php");
include ("classes/db_contrans_classe.php");
include ("classes/db_conplanoreduz_classe.php");
include ("classes/db_conlancamcompl_classe.php");
include ("classes/db_conlancamcgm_classe.php");
include ("classes/db_conlancamcorgrupocorrente_classe.php");
include ("model/contaTesouraria.model.php");
require_once ("libs/db_utils.php");

db_postmemory($HTTP_POST_VARS);

$clsaltes = new cl_saltes;
$clorcreceita = new cl_orcreceita;
$clorcreceitaval = new cl_orcreceitaval;
$clorcfontes = new cl_orcfontes;
$clorcfontesdes = new cl_orcfontesdes;

$db_opcao = 1;
$db_botao = false;
$msg_erro = "";

if (isset ($processar) || isset ($desprocessar)) {
	$data = $c70_data_ano."-".$c70_data_mes."-".$c70_data_dia;
	$clboletim = new cl_boletim;
	$result = $clboletim->sql_record($clboletim->sql_query($data, db_getsession("DB_instit")));
	$executar = false;
	if ($clboletim->numrows == 0) {
		db_msgbox('Boletim não gerado para esta data. ('.$c70_data_dia."/".$c70_data_mes."/".$c70_data_ano.')');
		db_redireciona('con4_boletim003.php');
	} else {
		db_fieldsmemory($result, 0);
		if ($k11_libera == 'f' || $k11_lanca == 'f' || db_getsession('DB_anousu') != $c70_data_ano) {
			if ($k11_libera == 'f') {
				db_msgbox('Boletim não liberado para a Contabilidade.');
			} else
				if ($k11_lanca == 'f') {
					db_msgbox('Boletim não  processado pela Contabilidade.');
				} else {
					db_msgbox('Exercício inválido. Permitido: '.db_getsession("DB_anousu"));
				}
			db_redireciona('con4_boletim003.php');
		} else {
		  
			$lErro = false;
			$msg_erro = 'Processamento concluido com sucesso.';
			db_inicio_transacao();
			
		  $sSqlSaltes = "select k13_conta, k13_saldo 
		                   from saltes 
		                        inner join conplanoreduz on conplanoreduz.c61_reduz  = saltes.k13_reduz
		                                                and conplanoreduz.c61_anousu = ".db_getsession('DB_anousu')."
		                                                and conplanoreduz.c61_instit = ".db_getsession('DB_instit')."
		                  where k13_datvlr >= '{$data}' and k13_vlratu <> 0";
     
		     $rsSaltes   = db_query($sSqlSaltes);
          if (pg_num_rows($rsSaltes) > 0) {
        
          	for($iInd = 0 ; $iInd<pg_num_fields($rsSaltes); $iInd++ ){
          		
          		$oResultConta = db_utils::fieldsMemory($rsSaltes, $iInd);
          		
          		/*
               * subtrai um dia da data de processamento
               */
              $dataAnt = date("Y-m-d", mktime(0, 0, 0, $c70_data_mes, ($c70_data_dia)-1, $c70_data_ano) );        		
              
              try {
          		  $oContaTes = new contaTesouraria($oResultConta->k13_conta);
          		  $oContaTes->implantarSaldo($dataAnt, $oResultConta->k13_saldo, false);
              }catch (Exception $e ){
              	$msg_erro = $e->getMessage();
              	$lErro = true;              	
              }
          		
          	}      
          }
          
      db_fim_transacao($lErro);
          
		  $executar = true;
		}
	};

	if ($executar == true) {
		$erro = false;
		db_inicio_transacao();
		$arrecada_boletim = false;

		$clconlancambol   = new cl_conlancambol;
		$clconlancam      = new cl_conlancam;
		$clconlancamrec   = new cl_conlancamrec;
		$clconlancamdoc   = new cl_conlancamdoc;
		$clconlancamval   = new cl_conlancamval;
		$clconlancamlr    = new cl_conlancamlr;
		$clconlancampag   = new cl_conlancampag;
		$clcontrans       = new cl_contrans;
		$clconplanoreduz  = new cl_conplanoreduz;
		$clconlancamcompl = new cl_conlancamcompl;
		$clconlancamcgm   = new cl_conlancamcgm;
		$clconlancamcorgrupocorrente   = new cl_conlancamcorgrupocorrente;
		

		// seleciona  conlancam que tenha referencia no conlancambol e coloca num array     
		// apaga por ultimo o conlancam
		
		$res = $clconlancam->sql_record("select  c77_codlan as c70_codlan
		                                   from conlancambol
		                                           inner join conlancam on c77_codlan      = c70_codlan and
		                  					                                              c77_dataproc  = c70_data
		                                   where  
				                                   c77_instit= ".db_getsession("DB_instit")."  and
							                       c77_databol   = '$k11_data'  ");							                       
		$rows = $clconlancam->numrows;
		if ($rows > 0) {
			for ($x = 0; $x < $rows; $x ++) {
				db_fieldsmemory($res, $x);
				$rval = $clconlancamval->sql_record($clconlancamval->sql_query_file(null, 'c69_sequen', null, "c69_codlan=$c70_codlan"));
				$val_rows = $clconlancamval->numrows;
				if ($val_rows > 0) {
					for ($v = 0; $v < $val_rows; $v ++) {
						db_fieldsmemory($rval, $v);
						// apaga conlancamlr //
						$clconlancamlr->excluir($c69_sequen);
						if ($clconlancamlr->erro_status == 0) {
							$erro = true;
							db_msgbox("1".$clconlancamlr->erro_msg);
							break;
						}
						// apaga conlancamlr // 
						$clconlancamval->excluir($c69_sequen);
						if ($clconlancamval->erro_status == 0) {
							$erro = true;
							db_msgbox("2".$clconlancamval->erro_msg);
							break;
						}
					};
				};
				$clconlancamrec->excluir($c70_codlan);
				if ($clconlancamrec->erro_status == 0) {
					$erro = true;
					db_msgbox("3".$clconlancamrec->erro_msg);
					break;
				}
				$clconlancamdoc->excluir($c70_codlan);
				if ($clconlancamdoc->erro_status == 0) {
					$erro = true;
					db_msgbox("4".$clconlancamdoc->erro_msg);
					break;
				}
				$clconlancampag->excluir($c70_codlan);
				if ($clconlancampag->erro_status == 0) {
					$erro = true;
					db_msgbox("5".$clconlancampag->erro_msg);
					break;
				}
				$clconlancambol->excluir($c70_codlan);
				if ($clconlancambol->erro_status == 0) {
					$erro = true;
					db_msgbox("6".$clconlancambol->erro_msg);
					break;
				}
				$clconlancamcompl->excluir($c70_codlan);
				if ($clconlancamcompl->erro_status == 0) {
					$erro = true;
					db_msgbox("7".$clconlancamcompl->erro_msg);
					break;
				}
     	  			$clconlancamcgm->excluir($c70_codlan);
				if ($clconlancamcgm->erro_status == 0) {
					$erro = true;
					db_msgbox("9".$clconlancamcgm->erro_msg);
					break;
				}
			    $clconlancamcorgrupocorrente->excluir(null,"c23_conlancam = {$c70_codlan}");
			    if ($clconlancamcorgrupocorrente->erro_status == 0) {
					$erro = true;
					db_msgbox("10".$clconlancamcorgrupocorrente->erro_msg);
					break;
				}
				$clconlancam->excluir($c70_codlan);
				if ($clconlancam->erro_status == 0) {
					$erro = true;
					db_msgbox("8".$clconlancam->erro_msg);
				}
				

				
			} // end for

			if ($erro == false) {
				$clboletim->k11_data = "$data";
				$clboletim->k11_instit = db_getsession("DB_instit");
				$clboletim->k11_lanca = 'false';
				$clboletim->alterar($data, $clboletim->k11_instit);
				$erro_msg = $clboletim->erro_msg;
				if ($clboletim->erro_status == 0) {
					$erro = true;
					db_msgbox("8".$clboletim->erro_msg);
				}
			}

		}
		if($erro==false&&$rows==0) {
			$clboletim->k11_data = "$data";
			$clboletim->k11_instit = db_getsession("DB_instit");
			$clboletim->k11_lanca = 'false';
			$clboletim->alterar($data, $clboletim->k11_instit);
			$erro_msg = $clboletim->erro_msg;
			if($clboletim->erro_status == 0) {
				$erro = true;
				db_msgbox("8".$clboletim->erro_msg);
			}
		}
		db_fim_transacao($erro);
	} //  
} // fim codigo novo 
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?


include ("forms/db_frmboletim003.php");
?>
    </center>
	</td>
  </tr>
</table>
<?


db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?


if ($msg_erro != '') {
	db_msgbox($msg_erro);
	db_redireciona("con4_boletim003.php");
}
?>