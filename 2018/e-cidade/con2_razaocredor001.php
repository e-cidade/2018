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
<script>
variavel = 1;
function js_imprimir() {
  var data1 = new Date(document.form1.data1_ano.value,document.form1.data1_mes.value,document.form1.data1_dia.value,0,0,0);
  var data2 = new Date(document.form1.data2_ano.value,document.form1.data2_mes.value,document.form1.data2_dia.value,0,0,0);
  if(data1.valueOf() > data2.valueOf()){
    alert('Data inicial maior que data final. Verifique!');
    return false;
  }
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].name == "lista[]"){
      for(x=0;x< document.form1.elements[i].length;x++){
        document.form1.elements[i].options[x].selected = true;
      }
    }
  }
  jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  document.form1.target = 'safo' + variavel++;
  setTimeout("document.form1.submit()",1000);
  return true;
}

</script>

</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC"  >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>  &nbsp; </td>
  </tr>  
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
       <form name="form1" method="post" action="con2_razaocredor002.php" >
       <table border="0" >
       <tr> 
           <td align="center">
                <strong>Opções:</strong>
                <select name="ver">
                    <option name="condicao" value="com">Com os credores selecionados</option>
                    <option name="condicao" value="sem">Sem os credores selecionadas</option>
                </select>
          </td>
       </tr>
       <tr>
          <td nowrap width="50%">
               <?
                 // $aux = new cl_arquivo_auxiliar;
                  $aux->cabecalho = "<strong>Credores</strong>";
                  $aux->codigo = "c76_numcgm"; //chave de retorno da func
                  $aux->descr  = "z01_nome";   //chave de retorno
                  $aux->nomeobjeto = 'lista';
                  $aux->funcao_js = 'js_mostra';
                  $aux->funcao_js_hide = 'js_mostra1';
                  $aux->sql_exec  = "";
                  $aux->func_arquivo = "func_cgm_razao.php";  //func a executar
                  $aux->nomeiframe = "db_iframe_cgm";
                  $aux->localjan = "";
                  $aux->onclick = "";
                  $aux->db_opcao = 2;
                  $aux->tipo = 2;
                  $aux->top = 0;
                  $aux->linhas = 5;
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
                  db_inputdata('data2',@$dia2,@$mes2,@$ano2,true,'text',1,"");
               ?>
          </td>
       </tr>
           <td nowrap align="right">
               <b>  Somente Empenho : </b>
               <select name="so_emp">
                    <option name="so_emp" value="s">Sim</option>
                    <option name="so_emp" value="n">Não</option>
               </select>
	       <br>
               <b>  Tipo : </b>
               <select name="tipo">
                    <option name="tipo" value="a">Analitico </option>
                    <option name="tipo" value="s">Sintético </option>
               </select><br>
                    <b> Ordem:</b>
                    <select name="ordem">
<!--                      <option name="ordem" value="e">Empenho </option> -->
                         <option name="ordem" value="g">Geral   </option>
                    </select>
	       <br>
               <b>  Quebra Credor: </b>
               <select name="credor">
                    <option name="credor" value="s">Sim</option>
                    <option name="credor" value="n">Não</option>
               </select><br>
               <b>  Lista RP: </b>
               <select name="rp">
                    <option name="rp" value="s">Sim</option>
                    <option name="rp" value="n">Não</option>
               </select><br>
           </td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           <td> 
<!--             <b>
	      Totaliza&ccedil;&atilde;o por:</b><br>-->
	      <input type="checkbox" name="emp" checked >Empenhado<br>
	      <input type="checkbox" name="liq" checked >Liquidado<br>
              <input type="checkbox" name="pag" checked >Pago<br>
	   </td>
	   <tr>
           <td colspan="3" align="center">
               <input type="button" id="emite" value="Emite" onClick="js_imprimir()">
           </td>
	   </tr>
       </tr> 
       </table>
       </center>
       </form>

    </td>
  </tr>
</table>
<!---  menu --->
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<!--- --->
  </body>
</html>