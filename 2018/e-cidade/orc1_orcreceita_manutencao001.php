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
include ("classes/db_orcreceita_classe.php");
include ("dbforms/db_funcoes.php");
require ("libs/db_liborcamento.php");
include ("classes/db_orcparametro_classe.php");
include ("classes/db_orcfontes_classe.php");
include ("classes/db_orcfontesdes_classe.php");
include ("dbforms/db_classesgenericas.php");
include ("classes/db_orcreceitaval_classe.php");

db_postmemory($HTTP_POST_VARS);	

$clorcreceita = new cl_orcreceita;
$clorcfontes = new cl_orcfontes;
$clorcfontesdes = new cl_orcfontesdes;
$clorcparametro = new cl_orcparametro;
$clestrutura = new cl_estrutura;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clorcreceitaval = new cl_orcreceitaval;

$db_opcao = 1;
$db_botao = true;
$anousu = db_getsession("DB_anousu"); 

$clrotulo = new rotulocampo;
$clrotulo->label("o70_codrec");
$clrotulo->label("o57_descr");
$clrotulo->label("o71_anousu");
$clrotulo->label("o71_mes");
$clrotulo->label("o71_valor");

if (isset($incluir)   &&  ($incluir== 'Incluir')){
	 if ($o71_anousu = $anousu ){
	 	 db_msgbox("Manutenção no exercicio atual não permitida ");
	 	 db_redireciona();
	 	 exit;
	 }	
	 $clorcreceitaval->o71_codrec = $o70_codrec;
	 $clorcreceitaval->o71_anousu = $o71_anousu;	 
	 // function incluir ($o71_anousu,$o71_codrec,$o71_coddoc,$o71_mes){	 	
	  $res = $clorcreceitaval->incluir("$o71_anousu",$o70_codrec,100,$o71_mes); 	
	  if ($clorcreceitaval->erro_status == 0 ){
      	  db_msgbox($clorcreceitaval->erro_msg);      	  
      }
} else if (isset($alterar)  &&  ($alterar== 'Alterar')){
	if ($o71_anousu = $anousu ){
	 	 db_msgbox("Manutenção no exercicio atual não permitida ");
	 	 db_redireciona();
	 	 exit;
	 }
     $clorcreceitaval->o71_codrec = $o70_codrec;
	 $clorcreceitaval->o71_anousu = $o71_anousu;
	 $clorcreceitaval->o71_coddoc = 100 ; 
     $res = $clorcreceitaval->alterar("$o71_anousu",$o70_codrec,100,$o71_mes); 	
	 if ($clorcreceitaval->erro_status == 0 ){
      	  db_msgbox($clorcreceitaval->erro_msg);      	  
     }        
} else if (isset ($opcao) && ($opcao == "alterar")) {	
	$res = $clorcreceitaval->sql_record($clorcreceitaval->sql_query_file(null, null, null, null, "*", "o71_mes", " o71_codrec = $o71_codrec and   o71_mes =   $o71_mes and o71_anousu=$o71_anousu "));	
	if ($clorcreceitaval->numrows > 0) {
		db_fieldsmemory($res, 0,true);		
		$o70_codrec = $o71_codrec;
	}
	$db_opcao = 2;
} else if (isset ($opcao) && ($opcao == "excluir")) {
	$res = $clorcreceitaval->sql_record($clorcreceitaval->sql_query_file(null, null, null, null, "*", "o71_mes", " o71_codrec = $o71_codrec and   o71_mes =   $o71_mes and o71_anousu=$o71_anousu "));
	if ($clorcreceitaval->numrows > 0) {
		db_fieldsmemory($res, 0,true);
		$o70_codrec = $o71_codrec;		
	}
	$db_opcao = 3;
} else if (isset($chavepesquisa) && ($chavepesquisa !="")){
	$res = $clorcreceita->sql_record($clorcreceita->sql_query(null, $chavepesquisa));
    if ($clorcreceita->numrows > 0) {
		db_fieldsmemory($res, 0,true);		
		$db_opcao = 1;
	}	
}
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#CCCCCC">
<form name="form2"  action="" method="POST">
<tr>
<td>
  <table border=0 align="center">
   <tr> 
       <td><? db_ancora($Lo70_codrec,'js_receitas(true)','1'); ?></td>
	   <td><? db_input('o70_codrec',10,$Io70_codrec,true,'text',1,"onchange='js_receitas(false)'"); ?></td> 	
	   <td><? db_input('o57_descr',55,$Io57_descr,true,'text',3,""); ?></td>
	</tr>       
	<tr> 
       <td><? db_ancora($Lo71_anousu,'','3'); ?></td>
	   <td><? db_input('o71_anousu',10,$Io71_anousu,true,'text',1,""); ?></td>	
	</tr>
	<tr> 
       <td><? db_ancora($Lo71_mes,'','3'); ?></td>
	   <td><? db_input('o71_mes',10,$Io71_mes,true,'text',1,""); ?></td>
	</tr>
	<tr> 
       <td><? db_ancora($Lo71_valor,'','3'); ?></td>
	   <td><? db_input('o71_valor',15,$Io71_valor,true,'text',1,""); ?></td>	
	  
	</tr>
	<tr>
	  <td colspan=2 align=center>
	   <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"") ?> >
	 </td>
	</tr>
	
    </table> 
  
   </td>
 </tr>
 </table>   
 </form>
     
<?


$db_opcao = 1;
$chavepri = array ("o71_anousu" => @ $o71_anousu, "o71_codrec" => @ $o71_codrec, "o71_mes" => @ $o71_mes);
$cliframe_alterar_excluir->chavepri = $chavepri;
$cliframe_alterar_excluir->sql = $clorcreceitaval->sql_query_file(null, null, null, null, "*", " o71_anousu desc, o71_mes", " o71_codrec = $o70_codrec and o71_anousu < $anousu");
$cliframe_alterar_excluir->campos = "o71_anousu,o71_codrec,o71_coddoc,o71_mes,o71_valor";
$cliframe_alterar_excluir->legenda = "lista";
$cliframe_alterar_excluir->iframe_height = "370";
$cliframe_alterar_excluir->iframe_width = "100%";
$cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
?>       
     

  
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_receitas(mostra){
   if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?funcao_js=parent.js_mostraReceita|o70_codrec','Pesquisa',true);
  }else{
    rec = document.form2.o70_codrec.value;
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?pesquisa_chave='+rec+'&funcao_js=parent.js_mostraReceita1','Pesquisa',false);
  }	 
}
function js_mostraReceita(chave1){
	 db_iframe_orcreceita.hide();
     <?
	 echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave1;";
     ?> 
}
function js_mostraReceita1(chave1,erro){      
     rec = document.form2.o70_codrec.value;
	 <?
	 echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+rec;";
     ?> 
}
</script>