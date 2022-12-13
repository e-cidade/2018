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
include ("libs/db_usuariosonline.php");
include ("classes/db_caracter_classe.php");
include ("dbforms/db_funcoes.php");
include ("dbforms/db_classesgenericas.php");
include ("classes/db_db_usuarios_classe.php");
include ("classes/db_empnota_classe.php");

$cldb_usuarios = new cl_db_usuarios;
$clempnota = new cl_empnota;
$cliframe_seleciona = new cl_iframe_seleciona;
$clrotulo = new rotulocampo;
$cldb_usuarios->rotulo->label();
$clrotulo->label("e69_numemp");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
</style>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br><br><br><!-- espaço dos menus -->

<form name="form1" method="post" action="" target="">

<table  border="0" cellspacing="0" cellpadding="0" align=center>
<tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC">
         <?
		$aux = new cl_arquivo_auxiliar;
		$aux->cabecalho = "<strong>CGM</strong>";
		$aux->codigo = "z01_numcgm";
		$aux->descr = "z01_nome";
		$aux->nomeobjeto = 'lista';
		$aux->funcao_js = 'js_mostra';
		$aux->funcao_js_hide = 'js_mostra1';
		$aux->sql_exec = "";
		$aux->func_arquivo = "func_nome.php";
		$aux->nomeiframe = "db_iframe";
 		$aux->localjan = "";
		$aux->onclick = "";
		$aux->db_opcao = 2;
		$aux->tipo = 2;
		$aux->top = 0;
		$aux->linhas = 10;
		$aux->vwhidth = 400;
		$aux->funcao_gera_formulario();
	 ?>	       
	</td>
</tr>
<tr>
    <td><b>Emissao de Empenho</b><?db_inputdata("data1","","","","true","text",2); ?>      
        <b>Ate:</b>              <?db_inputdata("data2","","","","true","text",2); ?>
    </td>
</tr>
	      
<tr>
   <td colspan=1 height=40px><b>Limite de Registros:</b><input type=text size=3 name=limite value=100><b></td>
</tr>


	      
<tr>
  <td colspan=2 height=40px> &nbsp;

  </td>

</tr>

	       
<tr>
    <td colspan=1  align="center"><input  name="emite2" id="emite2" type="button" value="Emitir Relátorio" onclick="js_emite();" ></td>
		 
</tr>

</table>

</form>


<script>


function js_mostra1(lErro,sNome){
  
	if(lErro){
    document.form1.z01_numcgm.value = "";
    document.form1.z01_nome.value   = "";
    document.form1.z01_numcgm.focus();
  }else{
    document.form1.z01_nome.value = sNome;
    document.form1.db_lanca.onclick = js_insSelectlista;
  }

}


function js_emite(){
 variavel = 1;
 vir="";
 listagem="";
 for(i=0;i<document.form1.length;i++){      
      if(document.form1.elements[i].name == "lista[]"){
   	       for(x=0;x< document.form1.elements[i].length;x++){
	            listagem+=vir+document.form1.elements[i].options[x].value;
	           vir=",";
	       } 
      }
 }  
 obj = document.form1;
 dt1 = obj.data1_ano.value+'-'+obj.data1_mes.value+'-'+obj.data1_dia.value;
 dt2 = obj.data2_ano.value+'-'+obj.data2_mes.value+'-'+obj.data2_dia.value;
 
 if (dt2 < dt1 ){
    alert('Datas Inválidas ');  
 }  else { 
    jan = window.open('emp2_empnotas002.php?codigos='+listagem+'&data_ini='+dt1+'&data_fin='+dt2+'&limite='+obj.limite.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
 }    
}
</script>

<?


db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>