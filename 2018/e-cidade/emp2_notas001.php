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
include("libs/db_usuariosonline.php");
include("classes/db_caracter_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_empnota_classe.php");
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
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
        <form name="form1" method="post" action="" target="">
           <center>
             <table border="0">
	       <tr>
	         <td height="2%">
		 </td>
	       </tr>
	       <tr>
                 <td align="left" colspan="3">
                   <table>
                     <tr> <td><br><br><br> <b>Data de emiss&atilde;o  de:</b><?db_inputdata("data","","","","true","text",2)      ?>      </td>
	               <td><br><br><br><b>Ate:</b>  <?db_inputdata("data1","","","","true","text",2)      ?> </td>
	             </tr>
                     <tr>
	                <td><b>Data do recebimento de:</b><?db_inputdata("data2","","","","true","text",2)      ?>      </td>
	                <td><b>Ate:</b>  <?db_inputdata("data3","","","","true","text",2)      ?> </td>
	             </tr>
		     <tr>
                       <td align="left" nowrap >
                         <strong>Apartir do Empenho:</strong>
	                 <?
                           db_input('e69_numemp',8,$Ie69_numemp,true,'text',1,"onChange=\"js_testa('i',this.value,'e69_numempINI','e69_numempFIM')\"","e69_numempINI","");             
	                 ?>
                         </td>
	                 <td><strong>ate:</strong>
	                   <?
                              db_input('e69_numemp',8,$Ie69_numemp,true,'text',1,"onChange=\"js_testa('f',this.value,'e69_numempINI','e69_numempFIM')\"","e69_numempFIM","");             
                           ?>
	                 </td>
                      </tr>
                   </table>
	         </td>
	       </tr>
	       <tr>
		 <td colspan="3" align="center">
		   <table>
		     <tr>
		       <td align="center">
			  <?
			  $aux = new cl_arquivo_auxiliar;
			  $aux->cabecalho = "<strong>CGM</strong>";
			  $aux->codigo = "z01_numcgm";
			  $aux->descr  = "z01_nome";
			  $aux->nomeobjeto = 'lista';
			  $aux->funcao_js = 'js_mostra';
			  $aux->funcao_js_hide = 'js_mostra1';
			  $aux->sql_exec  = "";
			  $aux->func_arquivo = "func_nome.php";
			  $aux->nomeiframe = "db_iframe";
			  $aux->localjan = "";
			  $aux->onclick  = "";
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
		       <td>
		       </td>
	             </tr>
		   </table>
		 </td>
	       </tr>
               <tr>
	         <td align="right"> <strong>Opção de Seleção :<strong></td>
		 <td align="left">&nbsp;&nbsp;&nbsp;
		   <?
		   $xxx = array("S"=>"Somente Selecionados","N"=>"Menos os Selecionados");
		   db_select('selecionados',$xxx,true,2);
		   ?><br><br>
		 </td>
	       </tr>
	       <tr>
	         <td colspan=2  align="center">
	           <input  name="emite2" id="emite2" type="button" value="Emitir Relátorio" onclick="js_emite();" >
	         </td>
		 
	       </tr>
             </table>
	   </center>
	 </form>
       </center>
     </td>
   </tr>
</table>
</body>
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


function js_testa(campo,valor){
  msg = "Informe um intervalo de código válido!";
  erro = false;
  if(campo=="i"){
    if(document.form1.e69_numempFIM.value!="" && parseInt(valor)>=parseInt(document.form1.e69_numempFIM.value)){
      erro = true;
    }
  }else if(campo=="f"){
    if(document.form1.e69_numempINI.value!="" && parseInt(valor)<=parseInt(document.form1.e69_numempINI.value)){
      erro = true;
    }
  }
  if(erro == true){
    alert(msg);
    document.form1.e69_numempINI.value = "";
    document.form1.e69_numempFIM.value = "";
    document.form1.e69_numempINI.focus();
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
  
  jan = window.open('emp2_notas002.php?codigos='+listagem+'&ver='+document.form1.selecionados.value+'&data='+document.form1.data_ano.value+'-'+document.form1.data_mes.value+'-'+document.form1.data_dia.value+'&data1='+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value+'&data2='+document.form1.data2_ano.value+'-'+document.form1.data2_mes.value+'-'+document.form1.data2_dia.value+'&data3='+document.form1.data3_ano.value+'-'+document.form1.data3_mes.value+'-'+document.form1.data3_dia.value+'&numempini='+document.form1.e69_numempINI.value+'&numempfim='+document.form1.e69_numempFIM.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>
</html>