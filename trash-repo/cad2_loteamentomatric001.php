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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
$clrotulo = new rotulocampo;
$clrotulo->label('j34_loteam');
$clrotulo->label('j34_descr');
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_SERVER_VARS);

$db_botao = true;
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

function js_emite(){
  jan = window.open('cad2_loteamentomatric002.php?selEmiteValor='+document.form1.selEmiteValor.value+'&ordem='+document.form1.ordem.value+'&loteam='+document.form1.j34_loteam.value+'&descr='+document.form1.j34_descr.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

<table  align="center">
  <form name="form1" method="post" action="" >
			<tr>
				 <td >&nbsp;</td>
				 <td >&nbsp;</td>
			</tr>
			<tr>
				<td>
					<fieldset>
						<legend>
							<b>&nbsp;Lotemento/Matrícula&nbsp;</b>
						</legend>
						<table>
							<tr>
								<td align="right" nowrap title="<?=@$Tj34_loteam?>" >
									<?
										db_ancora(@$Lj34_loteam,"js_pesquisaloteam(true);",4)
									?>
								</td>
								<td align="left">&nbsp;&nbsp;&nbsp;
									<?
										db_input('j34_loteam',12,$Ij34_loteam,true,'text',4,"onchange='js_pesquisaloteam(false);'");
										db_input('j34_descr',40,$Ij34_descr,true,'text',3,'');
									?>
								</td>
							</tr>
							<tr >
								<td align="right" nowrap title="Emissão de Valores." >
									<strong>Listar Valor:</strong>
								</td>
								<td align="left">&nbsp;&nbsp;&nbsp;
									<?
										$xx = array("s"=>"Sim","n"=>"Não");
										db_select('selEmiteValor',$xx,true,4,"style ='width:92px;'");
									?>
								</td>
							</tr>
							<tr >
								<td align="right" nowrap title="Ordem para a emissão do relatório." >
									<strong>Ordem:</strong>
								</td>
								<td align="left">&nbsp;&nbsp;&nbsp;
									<?
										$xx = array("a"=>"Alfabética","n"=>"Numérica");
										db_select('ordem',$xx,true,4,"");
									?>
								</td>
							</tr>
						</table>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td colspan="2" align = "center"> 
				 <input name="imprime" type="button" id="imprime" value="Imprimir" onClick="js_emite();">
				</td>
			</tr>
  
	</form>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisa(){
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_pesquisaloteam(mostra){
     if(mostra==true){
       db_iframe.jan.location.href = 'func_loteam.php?funcao_js=parent.js_mostraloteam1|j34_loteam|j34_descr';
       db_iframe.mostraMsg();
       db_iframe.show();
       db_iframe.focus();
     }else{
       db_iframe.jan.location.href = 'func_loteam.php?pesquisa_chave='+document.form1.j34_loteam.value+'&funcao_js=parent.js_mostraloteam';
     }
}
function js_mostraloteam(chave,erro){
  document.form1.j34_descr.value = chave;
  if(erro==true){
     document.form1.j34_descr.focus();
     document.form1.j34_descr.value = '';
  }
}
function js_mostraloteam1(chave1,chave2){
     document.form1.j34_loteam.value = chave1;
     document.form1.j34_descr.value = chave2;
     db_iframe.hide();
}


</script>


<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

?>