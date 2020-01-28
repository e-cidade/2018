<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
include ("dbforms/db_funcoes.php");
include ("dbforms/db_classesgenericas.php");
include ("classes/db_pcproc_classe.php");
include ("classes/db_solicitem_classe.php");
include ("classes/db_liclicitem_classe.php");
include ("classes/db_proctransfer_classe.php");
include ("classes/db_proctransferproc_classe.php");
include ("classes/db_solicitemprot_classe.php");
include ("classes/db_pcandpadrao_classe.php");
include ("classes/db_pcandpadraodepto_classe.php");
include ("classes/db_solandpadrao_classe.php");
include ("classes/db_solandpadraodepto_classe.php");
include ("classes/db_solandam_classe.php");
include ("classes/db_solandamand_classe.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
$cliframe_seleciona = new cl_iframe_seleciona;
$clpcproc = new cl_pcproc;
$clsolicitem = new cl_solicitem;
$clliclicitem = new cl_liclicitem;
$clproctransfer = new cl_proctransfer;
$clproctransferproc = new cl_proctransferproc;
$clsolicitemprot = new cl_solicitemprot;
$clpcandpadrao = new cl_pcandpadrao;
$clpcandpadraodepto = new cl_pcandpadraodepto;
$clsolandpadrao = new cl_solandpadrao;
$clsolandpadraodepto = new cl_solandpadraodepto;
$clsolandam = new cl_solandam;
$clsolandamand = new cl_solandamand; 
$db_opcao = 1;
$db_botao = true;

if (isset ($chaves)) {
	/*
	$result_itens=$clpcprocitem->sql_record($clpcprocitem->sql_query_file(null,"*",null,"pc81_codproc=$codprocant"));
	if ($clpcprocitem->numrows>0){
	for($w=0;$w<$clpcprocitem->numrows;$w++){
		db_fieldsmemory($result_itens,$w);
	if ($cods!=""){
		$cod=split(',',$cods);
	  	$new_cods="";
	  	$vir="";
	   	for($x=0;$x<count($cod);$x++){
			$pci=$cod[$x];
			if ($pc81_codprocitem!=$pci){
				$new_cods .= $vir.$pci;
			    $vir=",";	    			
			}
		}
		$cods=$new_cods;
	 }
	
	}
	}
	*/
	$info = split('#', $chaves);
	if (trim($cods) != "") {
		$vir = ",";
	} else {
		$vir = "";
	}
	for ($y = 0; $y < count($info); $y ++) {
		if (trim($info[$y]) != "") {
			$cods .= $vir.$info[$y];
			$vir = ",";
		}
	}
	if ($cods != "") {
		echo "<script>
				 		if (parent.document.form1.cods.value!=''){
				 		  parent.document.form1.cods.value=$cods;
				 		}
				   	    </script>";
	}
	if (isset ($incluir) && trim($incluir) != "") {
		$sqlerro = false;
		db_inicio_transacao();
		$dados = split(',', $cods);
		if (count($dados)) {
			$result_deptorec = $clpcandpadraodepto->sql_record($clpcandpadraodepto->sql_query(null, "*", null, "pc45_ordem = 2 and pc45_instit=".db_getsession("DB_instit")));
			if ($clpcandpadraodepto->numrows > 0) {
				db_fieldsmemory($result_deptorec, 0);
				$clproctransfer->p62_hora = db_hora();
				$clproctransfer->p62_dttran = date("Y-m-d", db_getsession("DB_datausu"));
				$clproctransfer->p62_id_usuario = db_getsession("DB_id_usuario");
				$clproctransfer->p62_coddepto = db_getsession("DB_coddepto");
				$clproctransfer->p62_coddeptorec = $pc46_depart;
				$clproctransfer->p62_id_usorec = '0';
				$clproctransfer->incluir(null);
				$codtran = $clproctransfer->p62_codtran;
				if ($clproctransfer->erro_status == 0) {
					$sqlerro == true;
					$erro_msg=$clproctransfer->erro_msg;
					//db_msgbox("1");
				}
			} else {
				echo "<script>parent.location.href='com4_solandini001.php';</script>";
				exit;
			}
		}
		for ($w = 0; $w < count($dados); $w ++) {
			if (trim($dados[$w]) != "") {
				if ($sqlerro == false) {
					$result_proc = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_file($dados[$w]));
					if ($clsolicitemprot->numrows > 0) {
						db_fieldsmemory($result_proc, 0);
						$clproctransferproc->incluir($codtran, $pc49_protprocesso);
						if ($clproctransferproc->erro_status == 0) {
							$sqlerro = true;
							$erro_msg=$clproctransferproc->erro_msg;
							//db_msgbox("2");
							break;
						}
					}
				}
				if ($sqlerro == false) {
					$result_andpadrao = $clpcandpadrao->sql_record($clpcandpadrao->sql_query_depto(null, "*", null, "pc45_instit=".db_getsession("DB_instit")));
					for ($x = 0; $x < $clpcandpadrao->numrows; $x ++) {
						db_fieldsmemory($result_andpadrao, $x);
						$clsolandpadrao->pc47_solicitem = $dados[$w];
						$clsolandpadrao->pc47_dias = $pc45_dias;
						$clsolandpadrao->pc47_ordem = $pc45_ordem;
						$clsolandpadrao->pc47_pctipoandam = $pc45_pctipoandam;
						$clsolandpadrao->incluir(null);
						if ($clsolandpadrao->erro_status == 0) {
							$sqlerro = true;
							$erro_msg=$clsolandpadrao->erro_msg;
							//db_msgbox("4");
							break;
						}
						if (isset($pc46_depart)&&$pc46_depart!="") {
							if ($sqlerro == false) {
								$clsolandpadraodepto->pc48_depto = $pc46_depart;
								$clsolandpadraodepto->incluir($clsolandpadrao->pc47_codigo);
								if ($clsolandpadraodepto->erro_status == 0) {
							      $sqlerro = true;
							      $erro_msg=$clsolandpadraodepto->erro_msg;
							      //db_msgbox("5");
							      break;
							      
						        }
							}
						}
					}
				}
				if ($sqlerro == false) {
					$clsolandam->pc43_depto=db_getsession("DB_coddepto");
					$clsolandam->pc43_ordem=1;
					$clsolandam->pc43_solicitem=$dados[$w];
					$clsolandam->incluir(null);
					if ($clsolandam->erro_status==0){
						$sqlerro = true;
						$erro_msg=$clsolandam->erro_msg;
						//db_msgbox("6");
						break;						
					}
				}
			}
		}
/*
		db_msgbox($erro_msg);
		exit;
	*/
		db_fim_transacao($sqlerro);
		if ($sqlerro == false) {
			db_msgbox("Transferência N° $codtran Efetivada com Sucesso!!");
			echo "<script>parent.location.href='com4_solandini001.php';</script>";
		} else {
			db_msgbox("Operação Cancelada!!Contate Suporte!!");
		}
		$incluir = "";
	}
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_submit_form(){ 
  document.form1.codsolant.value=document.form1.codsol.value;
  js_gera_chaves();
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<!--
<style>
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #999999;
}
.bordas{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #cccccc;
}
</style>
-->
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" >
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <? 


db_input('incluir', 10, '', true, 'hidden', 3);
db_input('codsol', 10, '', true, 'hidden', 3);
db_input('codsolant', 10, '', true, 'hidden', 3);
db_input('cods', 10, '', true, 'hidden', 3);
if (isset ($codsol) && $codsol != "") {
	//$sql = $clsolicitem->sql_query_prot(null, "distinct pc11_numero,pc11_codigo,pc11_quant,pc11_seq,pc11_vlrun,pc11_resum,pc01_codmater,pc01_descrmater,pc05_servico,pc17_unid,pc17_quant,m61_descr,m61_usaquant", null, "pc11_numero=$codsol");
	$sql = "select distinct pc11_numero,pc11_codigo,pc11_quant,pc11_seq,pc11_vlrun,pc11_resum,pc01_codmater,pc01_descrmater,pc01_servico,pc17_unid,pc17_quant,m61_descr,m61_usaquant
	 from solicitem 
	         inner join solicita on  solicita.pc10_numero = solicitem.pc11_numero
	         inner join db_depart  on  db_depart.coddepto = solicita.pc10_depto
	         left  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
	         left  join pcmater  on  pcmater.pc01_codmater = solicitempcmater.pc16_codmater
	         left  join pcprocitem  on  pcprocitem.pc81_solicitem = solicitem.pc11_codigo
	         left  join solicitemunid  on  solicitemunid.pc17_codigo = solicitem.pc11_codigo
	         left  join matunid  on  matunid.m61_codmatunid = solicitemunid.pc17_unid
	         left  join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo
	         left  join pctipo  on  pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo
	         inner join solicitemprot  on solicitemprot.pc49_solicitem  = solicitem.pc11_codigo
	 		 inner join protprocesso  on solicitemprot.pc49_protprocesso  = protprocesso.p58_codproc
	 		 left join  procandam    on procandam.p61_codandam   = protprocesso.p58_codandam
             left join proctransferproc  on proctransferproc.p63_codproc  = protprocesso.p58_codproc
	where p61_codandam is null and p63_codproc is null and pc11_numero=$codsol
	union
	select  distinct pc11_numero,pc11_codigo,pc11_quant,pc11_seq,pc11_vlrun,pc11_resum,pc01_codmater,pc01_descrmater,pc01_servico,pc17_unid,pc17_quant,m61_descr,m61_usaquant
	
	       from solicitem
	         inner join solicita on  solicita.pc10_numero = solicitem.pc11_numero
	         inner join db_depart  on  db_depart.coddepto = solicita.pc10_depto
	         left  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
	         left  join pcmater  on  pcmater.pc01_codmater = solicitempcmater.pc16_codmater
	         left  join pcprocitem  on  pcprocitem.pc81_solicitem = solicitem.pc11_codigo
	         left  join solicitemunid  on  solicitemunid.pc17_codigo = solicitem.pc11_codigo
	         left  join matunid  on  matunid.m61_codmatunid = solicitemunid.pc17_unid
	         left  join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo
	         left  join pctipo  on  pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo
             inner join solicitemprot  on solicitemprot.pc49_solicitem  = solicitem.pc11_codigo
	  		 inner join protprocesso  on solicitemprot.pc49_protprocesso  = protprocesso.p58_codproc
	         inner join proctransferproc  on proctransferproc.p63_codproc  = protprocesso.p58_codproc
	         inner join proctransand  on proctransand.p64_codtran   = proctransferproc.p63_codtran     
	 where pc11_numero=$codsol
	";
	if (isset ($cods) && $cods != "") {
		$sql_marca = $clsolicitem->sql_query_prot(null, "distinct pc11_numero,pc11_codigo,pc11_quant,pc11_seq,pc11_vlrun,pc11_resum,pc01_codmater,pc01_descrmater,pc01_servico,pc17_unid,pc17_quant,m61_descr,m61_usaquant", null, "pc11_numero=$codsol and pc11_codigo in ($cods)");
		//die($sql_marca);
	}
}
$cliframe_seleciona->campos = "pc11_numero,pc11_codigo,pc11_quant,pc11_seq,pc11_vlrun,pc11_resum,pc01_codmater,pc01_descrmater,pc01_servico,pc17_unid,m61_descr,pc17_quant";
$cliframe_seleciona->legenda = "Itens";
$cliframe_seleciona->sql = @ $sql;
$cliframe_seleciona->sql_marca = @ $sql_marca;
//$cliframe_seleciona->iframe_height ="200";
$cliframe_seleciona->iframe_width = "900";
$cliframe_seleciona->iframe_nome = "itens_teste";
$cliframe_seleciona->chaves = "pc11_codigo";
$cliframe_seleciona->iframe_seleciona(1);
?>
    </center>
    </td>
  </tr>
</table>
</form>
<script>
</script>
</body>
</html>