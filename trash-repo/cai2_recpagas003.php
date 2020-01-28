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
include("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);

$aux = new cl_arquivo_auxiliar;
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
  </tr>
</table >

  <table    align="center">
    <form name="form1" method="post" action="">
      <tr>
         <td align='right' ></td>
         <td ></td>
      </tr>
       <tr> 
           <td colspan=2  align="center">
                <strong>Opções:</strong>
                <select name="ver">
                    <option name="condicao1" value="com">Com os tipos de debitos selecionados</option>
                    <option name="condicao1" value="sem">Sem os tipos de debitos selecionados</option>
                </select>
          </td>
       </tr>
      <tr >
        <td colspan=2 ><?
                 // $aux = new cl_arquivo_auxiliar;
                 $aux->cabecalho = "<strong>Tipo de Debitos</strong>";
                 $aux->codigo = "k00_tipo"; //chave de retorno da func
                 $aux->descr  = "k00_descr";   //chave de retorno
                 $aux->nomeobjeto = 'debitos';
                 $aux->funcao_js = 'js_mostra';
                 $aux->funcao_js_hide = 'js_mostra1';
                 $aux->sql_exec  = "";
                 $aux->func_arquivo = "func_arretipo.php";  //func a executar
                 $aux->nomeiframe = "db_iframe_arretipo";
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
        <td align='center'  colspan=2 >
               <b> Período </b>
               <? 
	          db_inputdata('data1','','','',true,'text',1,"");   		          
                  echo "<b> a</b> ";
                  db_inputdata('data2','','','',true,'text',1,"");
               ?>&nbsp;
	         </td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_mandadados();" >
        </td>
      </tr>

  </form>
    </table>
</body>
</html>
<script>
function js_mandadados(){
 
 query="";
 vir="";
 listadeb="";
 
 for(x=0;x<document.form1.debitos.length;x++){
  listadeb+=vir+document.form1.debitos.options[x].value;
  vir=",";
 }
 
 vir="";
 listarec="";
 for(x=0;x<parent.iframe_g2.document.form1.receitas.length;x++){
  listarec+=vir+parent.iframe_g2.document.form1.receitas.options[x].value;
  vir=",";
 }
  
 query+='&listadeb='+listadeb+'&verdeb='+document.form1.ver.value;
 query+='&listarec='+listarec+'&verrec='+parent.iframe_g2.document.form1.ver.value;
 query+='&data='+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value;
 query+='&data1='+document.form1.data2_ano.value+'-'+document.form1.data2_mes.value+'-'+document.form1.data2_dia.value; 
 
 if (document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value!="--"&&document.form1.data2_ano.value+'-'+document.form1.data2_mes.value+'-'+document.form1.data2_dia.value!="--" ){
   jan = window.open('cai2_recpagas002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   jan.moveTo(0,0);   	 
 }else{
	 alert("Preencha um período para emitir o relatório!!");
 }
}
</script>