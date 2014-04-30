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
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");


//---  parser POST/GET
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

//---- instancia classes

$aux = new cl_arquivo_auxiliar;

//--- cria rotulos e labels

//----
$cliframe_seleciona = new cl_iframe_seleciona;
$clrotulo = new rotulocampo;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_emite() {
  
   vir="";
   listabairro="";
   for(x=0;x<document.form1.bairro.length;x++){
     listabairro+=vir+document.form1.bairro.options[x].value;
     vir=",";
   }
 
   vir="";
   listarua="";
   for(x=0;x<parent.iframe_g2.document.form1.rua.length;x++){
     listarua+=vir+parent.iframe_g2.document.form1.rua.options[x].value;
     vir=",";
   } 
   
   jan = window.open('cad2_totalizalotes002.php?ruas='+listarua+'&bairros='+listabairro+'&verbairro='+document.form1.ver.value+'&verrua='+parent.iframe_g2.document.form1.ver.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   jan.moveTo(0,0);
 
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC"  >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>  &nbsp; </td>
  </tr>  
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
       <form name="form1" method="post" action="">
       <table border="0" >
       <tr> 
           <td align="center">
                <strong>Opções:</strong>
                <select name="ver">
                    <option name="condicao2" value="com">Com os bairros selecionados</option>
                    <option name="condicao2" value="sem">Sem os bairros selecionadas</option>
                </select>
          </td>
       </tr>
       <tr>
          <td nowrap width="50%">
               <?
                 // $aux = new cl_arquivo_auxiliar;
                 $aux->cabecalho = "<strong>Bairro</strong>";
                 $aux->codigo = "j13_codi"; //chave de retorno da func
                 $aux->descr  = "j13_descr";   //chave de retorno
                 $aux->nomeobjeto = 'bairro';
                 $aux->funcao_js = 'js_mostra';
                 $aux->funcao_js_hide = 'js_mostra1';
                 $aux->sql_exec  = "";
                 $aux->func_arquivo = "func_bairro.php";  //func a executar
                 $aux->nomeiframe = "db_iframe_bairro";
                 $aux->localjan = "";
                 $aux->onclick = "";
                 $aux->db_opcao = 2;
                 $aux->tipo = 2;
                 $aux->top = 1;
                 $aux->linhas = 10;
                 $aux->vwhidth = 400;
                 $aux->funcao_gera_formulario();
              ?>    
          </td>
       </tr>
       </table>
      <table border="0" width="48%">
      
         <tr> 
           <td colspan=2 align='center'>
           <input name="relatorio" type="button" value="Emitir Relatório" onClick="js_emite();">
           </td>
       </tr> 
       </table>
       </center>
        
       <input type='hidden' name='quadra'>
       <input type='hidden' name='setor'>
       
       </form>
    </td>
  </tr>
</table>
<script>
</script>
</body>
</html>