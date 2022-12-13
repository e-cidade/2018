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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("libs/db_liborcamento.php");
include ("classes/db_solicitem_classe.php");
include ("classes/db_solicitemele_classe.php");
include ("classes/db_solicitemunid_classe.php");
include ("classes/db_solicitempcmater_classe.php");
include ("classes/db_pcdotac_classe.php");
include ("classes/db_solicitatipo_classe.php");
include ("classes/db_orcreserva_classe.php");
include ("classes/db_orcreservasol_classe.php");
include ("classes/db_orcdotacao_classe.php");
include ("classes/db_pcparam_classe.php");
include ("classes/db_protprocesso_classe.php");
include ("classes/db_solicitemprot_classe.php");
include ("classes/db_db_config_classe.php");
include ("dbforms/db_funcoes.php");

db_postmemory($_GET);
db_postmemory($_POST);


$clsolicitem        = new cl_solicitem;
$clsolicitemele     = new cl_solicitemele;
$clsolicitemunid    = new cl_solicitemunid;
$clsolicitempcmater = new cl_solicitempcmater;
$clpcdotac          = new cl_pcdotac;
$clorcreserva       = new cl_orcreserva;
$clorcreservasol    = new cl_orcreservasol;
$clorcdotacao       = new cl_orcdotacao;
$clpcparam          = new cl_pcparam;
$cldb_config        = new cl_db_config;
$clprotprocesso     = new cl_protprocesso;
$clsolicitemprot    = new cl_solicitemprot;
$clrotulo           = new rotulocampo;

$clrotulo->label("pc16_codmater");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("pc13_coddot");
$clrotulo->label("pc13_codele");
$clrotulo->label("pc13_depto");
$clrotulo->label("o56_elemento");
$db_opcao = 1;
$db_botao = true;
if (isset($incluir) && $incluir != "") {
  
	db_inicio_transacao();
	$sqlerro            = false;
	$sCamposParametros  = "pc30_mincar, pc30_obrigamat, pc30_obrigajust, pc30_seltipo, pc30_sugforn, pc30_permsemdotac, ";
	$sCamposParametros .= "pc30_contrandsol, pc30_tipoprocsol, pc30_gerareserva, pc30_passadepart";
	$sSqlParametros     = $clpcparam->sql_query_file(db_getsession("DB_instit"), $sCamposParametros);
	$result_tipo        = $clpcparam->sql_record($sSqlParametros);
	
	if ($clpcparam->numrows > 0) {
		db_fieldsmemory($result_tipo, 0);
	}
	
	$sSqlItensAnt      = "select distinct                                                                                ";
	$sSqlItensAnt     .= "			 pc11_codigo as codigo_ant,                                                              ";
	$sSqlItensAnt     .= "			 pc16_codmater,                                                                          ";
	$sSqlItensAnt     .= "			 pc01_descrmater,                                                                        ";
	$sSqlItensAnt     .= "			 pc13_coddot,                                                                            ";
	$sSqlItensAnt     .= "			 pc13_valor,                                                                             ";
	$sSqlItensAnt     .= "			 pc13_quant,                                                                             ";
	$sSqlItensAnt     .= "			 pc13_depto,                                                                             ";
	$sSqlItensAnt     .= "			 pc13_codele,                                                                            ";
	$sSqlItensAnt     .= "			 o56_elemento                                                                            ";
	$sSqlItensAnt     .= "			 from solicitem                                                                          ";
	$sSqlItensAnt     .= "       left  join pcdotac          on pc13_codigo    = pc11_codigo                             ";
	$sSqlItensAnt     .= "       inner join solicitempcmater on pc16_solicitem = pc11_codigo                             ";
	$sSqlItensAnt     .= "       inner join solicita         on pc10_numero    = pc11_numero                             ";
	$sSqlItensAnt     .= "       left  join orcelemento      on o56_codele     = pc13_codele                             ";
	$sSqlItensAnt     .= "															    and o56_anousu     = extract (year from pc10_data)           ";
	$sSqlItensAnt     .= "       inner join pcmater on pc01_codmater=pc16_codmater                                       ";
	$sSqlItensAnt     .= " where pc11_numero = {$importado}                                                              ";
	$result_itens_ant  = db_query($sSqlItensAnt);
	$numrows_itens_ant = pg_numrows($result_itens_ant);
	$sequencia = 0;
  
	for ($w = 0; $w < $numrows_itens_ant; $w ++) {
	  
		db_fieldsmemory($result_itens_ant, $w);
		$sequencia ++;
		
		$sWhereItem   = " pc11_codigo = {$codigo_ant}";
		$sCamposItem  = "pc11_codigo as codigo, pc11_numero, pc11_seq, pc11_quant, pc11_vlrun, pc11_prazo, pc11_pgto, ";
		$sCamposItem .= "pc11_resum, pc11_just, pc11_liberado ";
		$sSqlItem     = $clsolicitem->sql_query_file(null, $sCamposItem, "pc11_seq", $sWhereItem);
		//die($sSqlItem);
		$result_importacaoitem = $clsolicitem->sql_record($sSqlItem);
		db_fieldsmemory($result_importacaoitem, 0);

		if ($sqlerro == false) {
		  
			$clsolicitem->pc11_numero = $codnovo;
			$clsolicitem->pc11_seq = $sequencia;
			$clsolicitem->pc11_quant = $pc11_quant;
			$clsolicitem->pc11_vlrun = $pc11_vlrun;
			$clsolicitem->pc11_prazo = $pc11_prazo;
			$clsolicitem->pc11_pgto = $pc11_pgto;
			$clsolicitem->pc11_resum = addslashes($pc11_resum);
			$clsolicitem->pc11_just = $pc11_just;
			$clsolicitem->pc11_liberado = "false";		
			$clsolicitem->incluir(null);
			$pc11_codigo = $clsolicitem->pc11_codigo;
			$erro_msg = $clsolicitem->erro_msg;
			if ($clsolicitem->erro_status == 0) {
				$sqlerro = true;
				$erro_msg = $clsolicitem->erro_msg;			
				break;
			}
		}

		if ($sqlerro == false) {
			$result_pcmater = $clsolicitempcmater->sql_record($clsolicitempcmater->sql_query(null, null, "pc16_codmater", "", " pc10_numero=$importado and pc11_codigo=$codigo"));
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
			}
		}
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

		if ($pc30_contrandsol == 't') {
		  
				if ($sqlerro == false) {
				  
				  $sSqlConfig     = $cldb_config->sql_query(null, "numcgm,z01_nome", null, "codigo=".db_getsession("DB_instit"));
					$result_cgmpref = $cldb_config->sql_record($sSqlConfig);
					
					db_fieldsmemory($result_cgmpref, 0);
					$clprotprocesso->p58_codigo = $pc30_tipoprocsol;
					$clprotprocesso->p58_dtproc = date('Y-m-d', db_getsession("DB_datausu"));
					$clprotprocesso->p58_id_usuario = db_getsession("DB_id_usuario");
					$clprotprocesso->p58_numcgm = $numcgm;
					$clprotprocesso->p58_requer = $z01_nome;
					$clprotprocesso->p58_coddepto = db_getsession("DB_coddepto");
					$clprotprocesso->p58_codandam = '0';
					$clprotprocesso->p58_obs = "";
					$clprotprocesso->p58_despacho = "";
					$clprotprocesso->p58_hora = db_hora();
					$clprotprocesso->p58_interno = 't';
					$clprotprocesso->p58_publico = '0';
					$clprotprocesso->p58_ano     = db_getsession("DB_anousu");
          $clprotprocesso->p58_instit = db_getsession("DB_instit");
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

			
		if ($sqlerro == false) {

		  $sCampoImportaDotacao = "pc13_anousu,pc13_coddot,pc13_depto,pc13_quant,pc13_valor,pc13_codele";
		  $sSqlImportaDotacao   = $clpcdotac->sql_query_file($codigo, null, null, $sCampoImportaDotacao);
			$result_importacaodot = $clpcdotac->sql_record($sSqlImportaDotacao);
			$numrows_importacaodot = $clpcdotac->numrows;
			
			for ($ii = 0; $ii < $numrows_importacaodot; $ii ++) {
			  
				db_fieldsmemory($result_importacaodot, $ii);
				$pc13_coddot_pos = "pc13_coddot_$pc16_codmater"."_".$pc13_coddot;
				$pc13_coddot = $$pc13_coddot_pos;
				$pc13_anousu = db_getsession("DB_anousu");
				
				if (trim($pc13_coddot) != "") {
				  
					$result_codele = $clorcdotacao->sql_record($clorcdotacao->sql_query_file($pc13_anousu, $pc13_coddot));
					if ($clorcdotacao->numrows > 0) {
					  
						db_fieldsmemory($result_codele, 0);
						$pc13_codele = $o58_codele;
					}
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
						if (((isset ($atual_menos_reservado) && $atual_menos_reservado < $pc13_valor) || (isset ($tot) && $tot < 0)) && $sqlerro == false) {
							$sqlerrosaldo = true;
							$saldoreserva = $atual_menos_reservado;
						} else {
							$saldoreserva = $pc13_valor;
						}
					}

					$clpcdotac->pc13_anousu = $pc13_anousu;
					$clpcdotac->pc13_coddot = $pc13_coddot;
					$clpcdotac->pc13_depto = $pc13_depto;
					$clpcdotac->pc13_quant = $pc13_quant;
					$clpcdotac->pc13_valor = $pc13_valor;
					$clpcdotac->pc13_codele = $pc13_codele;
					$clpcdotac->pc13_codigo = $pc11_codigo;
					$clpcdotac->incluir(null);
					if ($clpcdotac->erro_status == 0) {
						$sqlerro = true;
						$erro_msg = $clpcdotac->erro_msg;
						break;
					}
					if ($pc30_gerareserva == 't') {
						if ($sqlerro == false) {
							$clorcreserva->o80_anousu = db_getsession("DB_anousu");
							$clorcreserva->o80_coddot = $pc13_coddot;
							$clorcreserva->o80_dtfim = date('Y', db_getsession('DB_datausu'))."-12-31";
							$clorcreserva->o80_dtini = date('Y-m-d', db_getsession('DB_datausu'));
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
  								    $clorcreservasol->o82_codres     = $o80_codres;
									$clorcreservasol->o82_solicitem = $pc11_codigo;
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
		}
	}	
	db_fim_transacao($sqlerro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style>
<?$cor="#999999"?>
.bordas{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
<?$cor="999999"?>
.bordas_corp{
         border: 1px solid #cccccc;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
       }
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <form name='form1'>
    <?
db_input("codnovo", 10, "", true, "hidden", 3);
db_input("importado", 10, "", true, "hidden", 3);

$sSql = "select distinct 
                pc16_codmater, 
                pc01_descrmater,
                pc13_coddot, 
                pc13_depto, 
                pc13_codele, 
                o56_elemento 
           from solicitem 
          left  join pcdotac          on pc13_codigo    = pc11_codigo
          inner join solicitempcmater on pc16_solicitem = pc11_codigo 
          inner join solicita         on pc10_numero    = pc11_numero 
          left  join orcelemento      on o56_codele     = pc13_codele
                                     and o56_anousu     = extract (year from pc10_data) 
          inner join pcmater          on pc01_codmater  = pc16_codmater  
          where pc11_numero = $importado";
$rsResult = db_query($sSql);
$iNumrows = pg_numrows($rsResult);
if ($iNumrows > 0) {
?>
    <table>
      <tr class='bordas'>
	      <td class='bordas' align='center'><b><small><?=@$RLpc16_codmater?></small></b></td>
	      <td class='bordas' align='center'><b><small><?=@$RLpc01_descrmater?></small></b></td>
	      <td class='bordas' align='center'><b><small><?=@$RLpc13_coddot?></small></b></td>
        <td class='bordas' align='center'><b><small><?=@$RLpc13_codele?></small></b></td>
        <td class='bordas' align='center'><b><small><?=@$RLo56_elemento?></small></b></td>
	      <td class='bordas' align='center'><b><small>Nova Dotação</small></b></td>
	    </tr>
    <?
	    for ($x = 0; $x < $iNumrows; $x ++) {
		    db_fieldsmemory($rsResult, $x);
    ?>
			<tr>  	            
			  <td	 class='bordas_corp' align='center'><small><?=@$pc16_codmater?></small></td>
		   	<td	 class='bordas_corp' nowrap align='left' title='<?=@$pc01_descrmater?>'><small><?=@substr($pc01_descrmater,0,20)?>&nbsp;</small></td>
		   	<td	 class='bordas_corp' align='center'><small><?=@$pc13_coddot?></small></td>
				<td	 class='bordas_corp' align='center'><small><?=@$pc13_codele?></small></td>                
			  <td	 class='bordas_corp' align='right'><small><?=@$o56_elemento?></small></td>
			  <td	 class='bordas_corp' align='right'><small><?=db_ancora(@$Lpc13_coddot,"js_pesquisapc13_coddot(true,$o56_elemento,$pc16_codmater,$pc13_coddot);",1);db_input("pc13_coddot_$pc16_codmater"."_".$pc13_coddot,8,$Ipc13_coddot,true,'text',3);?> &nbsp;</small></td>
		  </tr>
		<?
	    }
    ?>
    </table>
<?
}
?>	
  <input name="incluir" type="submit" id="db_opcao" value="Incluir" >
    </form>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<script>
codmater = "";
coddot = "";
function js_pesquisapc13_coddot(mostra,elemento,mater,dot){
  codmater=mater;
  coddot=dot;
  qry= 'obriga_depto=sim';
  qry+= '&elemento='+elemento;
  qry+= '&departamento=<?=(db_getsession("DB_coddepto")) ?>';  
  qry+= '&retornadepart=true';
  if(mostra==true){  	
    qry+= '&funcao_js=parent.js_mostraorcdotacao1|o58_coddot';
    js_OpenJanelaIframe('','db_iframe_orcdotacao','func_permorcdotacao.php?'+qry,'Pesquisa',true,0);
  }
}
function js_mostraorcdotacao1(chave1,chave2){  
  eval("document.form1.pc13_coddot_"+codmater+"_"+coddot+".value ="+ chave1);                       
  db_iframe_orcdotacao.hide();
}
</script>
<?
if (isset ($incluir) && $incluir != "") {
	if($sqlerro == true){
	 db_msgbox($erro_msg);
	} else {
		db_msgbox($erro_msg);
		echo "<script>parent.location.href='com1_solicita005.php?liberaaba=true&chavepesquisa=$codnovo'</script>";
	}
}
?>