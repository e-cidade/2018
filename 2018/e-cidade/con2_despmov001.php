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
  sel_instit  = new Number(document.form1.db_selinstit.value);
  if(sel_instit == 0){
     alert('Você não escolheu nenhuma Instituição. Verifique!');
     return false;
  }

  var data1 = new Date(document.form1.data1_ano.value,document.form1.data1_mes.value,document.form1.data1_dia.value,0,0,0);
  var data2 = new Date(document.form1.data2_ano.value,document.form1.data2_mes.value,document.form1.data2_dia.value,0,0,0);
  if(data1.valueOf() > data2.valueOf()){
    alert('Data inicial maior que data final. Verifique!');
    return false;
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
       <form name="form1" method="post" action="con2_despmov002.php" >
       <table border="0" >
       <tr>    
         <td align="center" colspan="3">
  	   <? db_selinstit('parent.js_limpa',300,100); 	?>
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
               <b>  Quebra Credor: </b>
               <select name="credor">
                    <option name="credor" value="n">Não</option>
		    <option name="credor" value="s">Sim</option>
               </select><br>
               <b>  Lista RP: </b>
               <select name="rp">
                   <option name="rp" value="n">Não</option>
                   <option name="rp" value="s">Sim</option>
               </select><br>
           </td> 
           <td> 
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