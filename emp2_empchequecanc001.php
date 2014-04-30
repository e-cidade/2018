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
include("classes/db_empagetipo_classe.php");

//---  parser POST/GET
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

//---- instancia classes
$clempempenho = new cl_empempenho;
$aux = new cl_arquivo_auxiliar;
$clempagetipo = new cl_empagetipo;
//--- cria rotulos e labels
$clempempenho->rotulo->label();

//----
//----
$cllote = new cl_lote;
$cliframe_seleciona = new cl_iframe_seleciona;

$cllote->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");

$dia=date('d',db_getsession("DB_datausu"));
$mes=date('m',db_getsession("DB_datausu"));
$ano=date('Y',db_getsession("DB_datausu"));


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
      <form name="form1" method="post" action="emp2_empchequecanc002.php" >

      <table border="0" width="320px">
       <tr>
           <td colspan=2><b><font size=+1 align=left>Cheques cancelados</font></b></td>
       </tr>

       <tr>
          <td nowrap colspan=2><b> Data da agenda</b></td>
        </tr>	  
	
      <tr>
	 <td nowrap><b>De:</b></td>
	 <td><?db_inputdata("dtini","$dia","$mes","$ano","true","text",2)    ?> 
	 <b>Ate:</b><?db_inputdata("dtfim","$dia","$mes","$ano","true","text",2) ?> </td>
      </tr>
      <tr>
         <td >
           <b>Conta:</b>
         </td>
         <td colspan=2>
         <?
           
        $result05  = $clempagetipo->sql_record($clempagetipo->sql_query(null,"e83_codtipo as codtipo,e83_descr,e83_conta","e83_descr"));
        $numrows05 = $clempagetipo->numrows;
        $arr['0']="Nenhum";
        for($r=0; $r<$numrows05; $r++){
    	
         db_fieldsmemory($result05,$r);
	     $arr[$codtipo] = "{$e83_conta} - {$e83_descr}";
	    
       }
       $e83_codtipo ='0';
       db_select("e83_codtipo",$arr,true,1,"onchange='ordem.js_padrao(this.value)';");
       ?>
        
         </td>
      </tr>
      <tr>	 
           <td colspan=2 align=center><input type="button" value="relatorio" onClick="js_seleciona()"></td>
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
<script>
variavel = 1;
function js_seleciona(){
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

  </body>
</html>