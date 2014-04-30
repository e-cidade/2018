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
include("libs/db_liborcamento.php");
include("classes/db_lote_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_empempenho_classe.php");
include("classes/db_empresto_classe.php");
include("classes/db_orctiporec_classe.php");

$clempresto = new cl_empresto;
$clrecurso = new cl_orctiporec;
$clrecurso->rotulo->label();
//---  parser POST/GET
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
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC"  >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>  &nbsp; </td>
  </tr>  
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
       <form name="form1" method="post" action="emp2_relrestos002.php">
       <table border="0" >
       <tr>
       <td align="center" colspan="3">
       <?
       db_selinstit('parent.js_limpa',300,100);
       ?>
       </td>
       </tr>
       
       <tr> 
       <td ><strong>Posição até:</strong></td>
       <td>
       <?
       db_inputdata("dtfim",date("d",db_getsession("DB_datausu")),date("m",db_getsession("DB_datausu")),date("Y",db_getsession("DB_datausu")),true,"text",2);
       ?>
       </td>
       </tr>
       <tr> 
       <td ><strong>Acumular por:</strong></td>
       <td>
       <?
       $acumu = array("e"=>"Empenho (Ordem Número)",
		      "d"=>"Empenho (Ordem Data de Emissão)", 
		      "c"=>"Empenho (Ordem Credor)",
                      "t"=>"Empenho (Ordem Tipo Resto)",
		      "u"=>"Empenho (Ordem Unidade)",
                      "subfunc"=>"Empenho (SubFunção)",
		      "o"=>"Empenho (Ordem Recurso)",	    
		      "a"=>"Tipo de Resto",
		      "r"=>"Recurso",
		      "r_e"=>"Recurso/Elemento",
		      "a_o"=>"Orgão");
       db_select("tipo",$acumu,true,"text",2);
       ?>
       </td>
       </tr>
       <tr> 
       <td ><strong>Restos a Pagar:</strong></td>
       <td>
       <?
       $acumu = array("0"=>"Geral","1"=>"Com Movimento até a Data","2"=>"Com saldo a pagar");
       db_select("commov",$acumu,true,"text",2);
       ?>
       </td>
       </tr>
       
       <tr>
       <td><?=$Lo15_codigo?></td>
       <td>
       <?
       $result = $clrecurso->sql_record($clrecurso->sql_query_file(null,"o15_codigo,o15_descr","o15_codigo"));
       db_selectrecord("o15_codigo",$result,true,2,"","","","0");
       ?>
       </td>       
       </tr>

       <tr> 
       <td><strong>Opção:</strong></td>
       <td>
       <?
       $opcao = array("0"=>"Todos","1"=>"Liquidados","2"=>"Não liquidados","3"=>"Anulados");
       db_select("opcao",$opcao,true,"text",2);
       ?>
       </td>
       </tr>

       <tr> 
       <td><strong>Exercicio:</strong></td>
       <td>
       <?
       $result = $clempresto->sql_record($clempresto->sql_query_empenho(db_getsession("DB_anousu"),null,' distinct e60_anousu ','e60_anousu'));
       $opcao = array("0"=>"Todos");// "1"=>"Liquidados","2"=>"Não liquidados","3"=>"Anulados");
       
       for ($ini=0;$ini < $clempresto->numrows;$ini++){
       	   db_fieldsmemory($result,$ini);
           $opcao[$e60_anousu]=$e60_anousu;
       }	
       
       db_select("exercicio",$opcao,true,"text",2);
       ?>
       </td>
       </tr>


       <tr> 
       <td colspan="2" align="center"><br>
         <input type="button" value="relatorio" onClick="js_emite()">
         <input type="hidden" name="filtra_despesa" >
	 </td>
       </tr>

       </table>
   </center>
   </form>
    </td>
  </tr>
</table>

  </body>
</html>



<script>

variavel = 1;
function js_emite(){
  sel_instit  = new Number(document.form1.db_selinstit.value);
  if(sel_instit == 0){
     alert('Você não escolheu nenhuma Instituição. Verifique!');
     return false;
  }
 // pega dados da func_selorcdotacao_aba.php
 // descomentar a linha abaixo quando colocar as abas
 //document.form1.filtra_despesa.value = parent.iframe_filtro.js_atualiza_variavel_retorno();
 
 jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 document.form1.target = 'safo' + variavel++;
 setTimeout("document.form1.submit()",1000);
 return true;
}


</script>