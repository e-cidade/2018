<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_setor_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_sanitario_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clsetor = new cl_setor;
$cliframe_seleciona = new cl_iframe_seleciona;
$clsetor->rotulo->label();
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
<script>
function js_imprime(){
  if(document.form1.idbql.value!=""){
    jan = window.open('','rel','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    jan.moveTo(0,0);
    document.form1.action = "cad2_matric002.php";
    document.form1.target = "rel";
    document.form1.submit();
  }else{
    alert("Selecione corretamente os setores, as quadras e os lotes que dever�o constar no relat�rio.");
    return false;
  }
}
function js_nome(obj){
  j34_setor = "";
  vir = "";
  x = 0;
  for(i=0;i<setor.document.form1.length;i++){
   if(setor.document.form1.elements[i].type == "checkbox"){
     if(setor.document.form1.elements[i].checked == true){
       valor = setor.document.form1.elements[i].value.split("_")
       j34_setor += vir + valor[0];
       vir = ",";
       x += 1; 
     }
   }
  }
  parent.iframe_g2.location.href = '../cad2_matric004.php?j34_setor='+j34_setor;
  if(j34_setor == ""){
    parent.iframe_g1.document.form1.idbql.value = '';    
  }
}
</script>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<table align='center' border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    
      <form name="form1" method="post">
      
      <table border="0" align='center'>
        <tr>
          <td align="top" align='center' colspan="5">
   <?
      $cliframe_seleciona->campos        = "j30_codi,j30_descr";
      $cliframe_seleciona->legenda       = "SETOR";
      $cliframe_seleciona->sql=$clsetor->sql_query(""," * ","j30_codi");      
      $cliframe_seleciona->textocabec    = "darkblue";
      $cliframe_seleciona->textocorpo    = "black";
      $cliframe_seleciona->fundocabec    = "#aacccc";
      $cliframe_seleciona->fundocorpo    = "#ccddcc";
      $cliframe_seleciona->iframe_height = "250";
      $cliframe_seleciona->iframe_width  = "700";
      $cliframe_seleciona->iframe_nome   = "setor";
      $cliframe_seleciona->chaves        = "j30_codi,j30_descr";
      $cliframe_seleciona->dbscript      = "onClick='parent.js_nome(this)'";
      $cliframe_seleciona->marcador      = true;
      $cliframe_seleciona->iframe_seleciona(@$db_opcao);    
   ?>
          </td>
        </tr> 
   <?
      db_input('idbql',"",0,true,'hidden',3,"");
   ?>   
        <tr>
	  <td colspan=2 align='right' >
	  <b>Tipo Im�vel:</b>
	  </td>
          
	  <td colspan=3 align='left' >
	    <?
	    $tipo_t = array("T"=>"Todos","B"=>"Territorial","P"=>"Predial");
	     db_select("terreno",$tipo_t,true,2); 	      
	    ?>
	  </td>
          </tr>
          <tr>
	  <td colspan=2 align='right' >
          	
          <strong>Listar: </strong>
          </td>
          
	  <td colspan=3 align='left' >
	    <select name="process" id="process">
	      <option value="T" selected>Todos</option>
	      <option value="S">Baixados</option>
	      <option value="N">N�o baixados</option>
	    </select>
          </td>
          </tr>
          <tr>
	  <td colspan=2 align='right' >
	  <b>Mostra Endere�o:</b>
	  </td>
          
	  <td colspan=3 align='left' >
	    <?
	    $tipo_m = array("n"=>"N�o","s"=>"Sim");
	     db_select("mostra",$tipo_m,true,2); 	      
	    ?>
	  </td>
          </tr>
          <tr>	
          <td colspan='5' align='center'>
	    <input type="submit" name="relatorio1" value="Gerar relat�rio" onClick="return js_imprime();">
          </td>          	
        </tr>
      </table>
      
      </form>
    
    </td>
  </tr>
</table>
</center>
</body>
</html>