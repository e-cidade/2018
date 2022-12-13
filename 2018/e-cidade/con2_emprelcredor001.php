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
include("classes/db_lote_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_empempenho_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clempempenho = new cl_empempenho;
$aux = new cl_arquivo_auxiliar;
$clempempenho->rotulo->label();
$cllote = new cl_lote;
$cliframe_seleciona = new cl_iframe_seleciona;
$cllote->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");

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
       <form name="form1" method="post" action="" >
       <table border="0" >
       <tr> 
           <td align="center">
                <strong>Opções:</strong>
                <select name="ver">
                    <option name="condicao1" value="com">Com os credores selecionados</option>
                    <option name="condicao1" value="sem">Sem os credores selecionadas</option>
                </select>
          </td>
       </tr>
       <tr>
          <td nowrap width="50%">
               <?
                 // $aux = new cl_arquivo_auxiliar;
                 $aux->cabecalho = "<strong>Credores</strong>";
                 $aux->codigo = "e60_numcgm"; //chave de retorno da func
                 $aux->descr  = "z01_nome";   //chave de retorno
                 $aux->nomeobjeto = 'credor';
                 $aux->funcao_js = 'js_mostra';
                 $aux->funcao_js_hide = 'js_mostra1';
                 $aux->sql_exec  = "";
                 $aux->func_arquivo = "func_cgm_empenho.php";  //func a executar
                 $aux->nomeiframe = "db_iframe_cgm";
                 $aux->localjan = "";
                 $aux->onclick = "";
                 $aux->db_opcao = 2;
                 $aux->tipo = 2;
                 $aux->top = 1;
                 $aux->linhas = 7;
                 $aux->vwhidth = 400;
                 $aux->funcao_gera_formulario();
              ?>    
          </td>
       </tr>
       </table>
      <table border="0" width="48%">
      <tr>
          <td nowrap colspan=3>
               <b> Período </b>
               <? 
	          $dia="01";
		  $mes="01";
		  $ano= db_getsession("DB_anousu");
		  $dia2="31";
		  $mes2="12";
		  $ano2= db_getsession("DB_anousu");
	          db_inputdata('data1',@$dia,@$mes,@$ano,true,'text',1,"");   		          
                  echo " a ";
                  db_inputdata('data11',@$dia2,@$mes2,@$ano2,true,'text',1,"");
               ?>
          </td>
       </tr><tr>
           <td nowrap>
               <b>  Tipo : </b>
               <select name="tipo">
                    <option name="tipo" value="a">Analitico </option>
                    <option name="tipo" value="s">Sintético </option>
               </select><br><br><br><center>
                  <input type="button" value="relatorio" onClick="js_mandadados()"></center>
           </td>
	   
           <td nowrap>
           </td>
           <td>
             <b>
	      Totaliza&ccedil;&atilde;o por:</b><br>
	      <input type="checkbox" name="hist" value="h" >Histórico<br>
	      <input type="checkbox" name="dot" value="d">Dota&ccedil;&otilde;es<br>
              <input type="checkbox" name="rec" value="r">Recursos<br>
	   </td>
         </tr> 
       <tr>
       <td colspan="3" align="center">
       <td>
       </tr>
       </table>
       </center>
       </form>

    </td>
  </tr>
</table>
<script>

function js_mandadados(){
 vir="";
 listacredor="";
 for(x=0;x<document.form1.credor.length;x++){
  listacredor+=vir+document.form1.credor.options[x].value;
  vir=",";
 }
 vir="";
 listahist="";
 for(x=0;x<parent.iframe_g2.document.form1.historico.length;x++){
  listahist+=vir+parent.iframe_g2.document.form1.historico.options[x].value;
  vir=",";
 }
 vir="";
 listadot="";
 for(x=0;x<parent.iframe_g3.document.form1.dotacao.length;x++){
  listadot+=vir+parent.iframe_g3.document.form1.dotacao.options[x].value;
  vir=",";
 }
 vir="";
 listarec="";
 for(x=0;x<parent.iframe_g4.document.form1.recurso.length;x++){
  listarec+=vir+parent.iframe_g4.document.form1.recurso.options[x].value;
  vir=",";
 }

 jan = window.open('con2_emprelatorio002.php?listacredor='+listacredor+'&listahist='+listahist+'&listadot='+listadot+'&listarec='+listarec+'&datacredor='+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value+'&datacredor1='+document.form1.data11_ano.value+'-'+document.form1.data11_mes.value+'-'+document.form1.data11_dia.value+'&vercredor='+document.form1.ver.value+'&verhist='+parent.iframe_g2.document.form1.ver.value+'&verdot='+parent.iframe_g3.document.form1.ver.value+'&verrec='+parent.iframe_g4.document.form1.ver.value+'&tipo='+document.form1.tipo.value+'&hist='+document.form1.hist.checked+'&dot='+document.form1.dot.checked+'&rec='+document.form1.rec.checked,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);





}







</script>

  </body>
</html>