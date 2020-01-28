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
include("classes/db_face_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
$clface = new cl_face;
$cliframe_seleciona = new cl_iframe_seleciona;
$clface->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
//die($HTTP_SERVER_VARS["QUERY_STRING"]);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
if(isset($j37_quadra) && $j37_quadra != ""){
  $quadra = split(",",$j37_quadra);
  $vir = "";
  $qua = "";
  for($i=0;$i<count($quadra);$i++){
    $qua .= $vir."'".$quadra[$i]."'";
    $vir = ",";
  }
}
if(isset($j34_setor) && $j34_setor != ""){
  $setor = split(",",$j34_setor);
  $vir = "";
  $set = "";
  for($i=0;$i<count($setor);$i++){
    $set .= $vir."'".$setor[$i]."'";
    $vir = ",";
  }
}
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
</script>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <form name="form1" method="post" action="" target="">
      <center>
      <table border="0">
        <tr>
          <td align="top" colspan="2">
      <?
	if(isset($j37_quadra)&& $j37_quadra!=""){
	  $cliframe_seleciona->campos  = "j14_codigo,j14_nome";
	  $cliframe_seleciona->legenda="RUAS";
	  $cliframe_seleciona->sql=$clface->sql_query(""," distinct j14_codigo,j14_nome","","j37_quadra in ($qua) and j37_setor in ($set)");
	  $cliframe_seleciona->textocabec ="darkblue";
	  $cliframe_seleciona->textocorpo ="black";
	  $cliframe_seleciona->fundocabec ="#aacccc";
	  $cliframe_seleciona->fundocorpo ="#ccddcc";
	  $cliframe_seleciona->iframe_height ="250";
	  $cliframe_seleciona->iframe_width ="700";
	  $cliframe_seleciona->iframe_nome ="ruas";
	  $cliframe_seleciona->chaves ="j14_codigo";
	  $cliframe_seleciona->iframe_seleciona(@$db_opcao);   
      ?>
	<script>
	  tempo = setInterval('ruas.js_marca();clearInterval(tempo)',1500);
	</script>
      <?
	}else{
	  echo "<br><strong>SELECIONE UMA QUADRA PARA ESCOLHER A(S) RUA(S)</strong>";
	}
      ?>   
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
</html>