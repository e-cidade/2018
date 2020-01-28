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
include ("classes/db_cgs_und_classe.php");

$clcgs_und = new cl_cgs_und ( );
$clrotulo = new rotulocampo ( );
$clcgs_und->rotulo->label ();

db_postmemory ( $HTTP_POST_VARS );
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript"
	src="scripts/scripts.js"></script>
<script>
// action = "pro2_duploscgs_und002.php"
function js_gerar(){
  ok = 0;
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].type == "checkbox"){
      if(document.form1.elements[i].checked == true){
	ok ++;
      }
    }
  }
  if(ok==0){
    alert("ERRO: Uma das opções de filtro deve ser selecionada.");
    return false;
  }
  jan = window.open('','rel','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
  document.form1.action="sau2_cgsduplos002.php?zerados="+document.form1.zerados.value;
  document.form1.submit();
}
function js_changenome(x){
  if(x==true || (document.form1.Cz01_v_nome.checked==false && x==false)){
    document.form1.z01_v_nome.disabled=true;
    document.form1.z01_v_nome.style.backgroundColor='#DEB887';
  }else if(x==false){
    if(document.form1.Cz01_v_nome.checked==true){
      document.form1.z01_v_nome.value="";
      document.form1.z01_v_nome.disabled=false;
      document.form1.z01_v_nome.style.backgroundColor='';
    }
  }
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"
	marginheight="0" bgcolor="#cccccc" onload="js_changenome(true);">
<table width="790" border="0" cellpadding="0" cellspacing="0"
	bgcolor="#5786B2">
	<tr>
		<td width="360" height="18">&nbsp;</td>
		<td width="263">&nbsp;</td>
		<td width="25">&nbsp;</td>
		<td width="140">&nbsp;</td>
	</tr>
</table>
<center>
<form name="form1" method="post" target="rel">

<table border='0'>
	<tr height="20px">
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td>
		<fieldset><Legend><strong>FILTRAR DUPLOS POR:</strong></legend>
		<table>
			<tr>
				<td align="left" nowrap title="Filtrar por nomes "><input
					type="checkbox" name="Cz01_v_nome" value="z01_v_nome"
					onclick="js_changenome(false)"></td>
				<td>
				<table>
					<tr>
						<td align="left" nowrap title="<?=$Tz01_v_nome?>"><?
						db_ancora ( @$Lz01_v_nome, "", 3 );
						?></td>
					</tr>
					<tr>
						<td>
	  <?
			db_input ( 'z01_v_nome', 40, $Iz01_v_nome, true, 'text', 1, "" )?>
	</td>
				
				</table>
				</td>
			</tr>
			<tr>
				<td align="left" nowrap title="Filtrar por endereços "><input
					type="checkbox" name="z01_v_ender" value="z01_v_ender"></td>
				<td align="left" nowrap title="<?=$Tz01_v_ender?>"><?
				db_ancora ( @$Lz01_v_ender, "", 3 );
				?></td>
			</tr>
			<tr>
				<td align="left" nowrap title="Filtrar por números "><input
					type="checkbox" name="z01_i_numero" value="z01_i_numero"></td>
				<td align="left" nowrap title="<?=$Tz01_i_numero?>"><?
				db_ancora ( @$Lz01_i_numero, "", 3 );
				?></td>
			</tr>
			<tr>
				<td align="left" nowrap title="Filtrar por complementos "><input
					type="checkbox" name="z01_v_compl" value="z01_v_compl"></td>
				<td align="left" nowrap title="<?=$Tz01_v_compl?>"><?
				db_ancora ( @$Lz01_v_compl, "", 3 );
				?></td>
			</tr>
			<tr>
				<td align="left" nowrap title="Filtrar por bairros "><input
					type="checkbox" name="z01_v_bairro" value="z01_v_bairro"></td>
				<td nowrap title="<?=@$Tz01_v_bairro?>"><?
				db_ancora ( @$Lz01_v_bairro, "", 3 );
				?>
    </td>
			</tr>
			<tr>
				<td align="left" nowrap title="Filtrar por municípios "><input
					type="checkbox" name="z01_v_munic" value="z01_v_munic"></td>
				<td nowrap title="<?=@$Tz01_v_munic?>"><?
				db_ancora ( @$Lz01_v_munic, "", 3 );
				?>
    </td>
			</tr>
			<tr>
				<td align="left" nowrap title="Filtrar por CPF/CNPJ "><input
					type="checkbox" name="z01_v_cgccpf" value="z01_v_cgccpf"></td>
				<td nowrap title="<?=@$Tz01_v_cgccpf?>"><?
				db_ancora ( @$Lz01_v_cgccpf, "", 3 );
				?>
    </td>
			</tr>
			<tr>
			</tr>




			<table>
				<tr>
					<td align="left" nowrap title="Sim/Não"><strong>Considerar
					preenchidos totalmente com zero ou em branco:&nbsp;&nbsp;</strong>
					</td>
					<td>
	  <?
			$tipo_zerados = array ("n" => "Não", "s" => "Sim", "m" => "Somente os zerados/branco" );
			db_select ( "zerados", $tipo_zerados, true, 2 );
			?>
        </td>
				</tr>
				<tr>
					<td>* a regra acima é validada somente nos casos em que o campo
					CNPJ/CPF estiver marcado;&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>



		</table>
		</fieldset>
		</td>
	</tr>
	<tr height="20px">
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input name="relatorio" type="submit"
			value="Gerar relatório" onclick="return js_gerar();"></td>
	</tr>
</table>
</form>
</center>
<?
db_menu ( db_getsession ( "DB_id_usuario" ), db_getsession ( "DB_modulo" ), db_getsession ( "DB_anousu" ), db_getsession ( "DB_instit" ) );
?>
</body>
</html>