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
include("classes/db_empagemov_classe.php");
include("classes/db_empagetipo_classe.php");
$clempagemov  = new cl_empagemov;
$clempagetipo = new cl_empagetipo;
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

$sair = true;
if(isset($valores) && trim($valores)!=""){
  $result_valores = $clempagemov->sql_record($clempagemov->sql_query_tipo(null,"sum(e81_valor) as totaltipo,e83_codtipo,e83_descr","e83_codtipo","e81_codmov in ($valores) and e80_instit = " . db_getsession("DB_instit") . "group by e83_codtipo,e83_descr"));
  $numrows_valores = $clempagemov->numrows;
  if($numrows_valores>0){
    $sair = false;
  }  
}else if(isset($coluna) && trim($coluna)!=""){
  $arr_valorescoluna = split(",",$coluna);
  $arr_codigtipo = Array();
  $arr_valortipo = Array();
  $arr_indextipo = Array();
  $numrows_valores = 0;
  $sair  = false;
  for($i=0;$i<sizeof($arr_valorescoluna);$i++){    
    $arr_quebracoluna = split("-",$arr_valorescoluna[$i]);
    $codigtipo = $arr_quebracoluna[0];
    $valortipo = $arr_quebracoluna[1];
    if(!isset($arr_codigtipo[$codigtipo])){
      $arr_indextipo[$numrows_valores] = $codigtipo;
      $arr_codigtipo[$codigtipo] = $codigtipo;
      $arr_valortipo[$codigtipo] = 0;
      $numrows_valores++;
    }
    $arr_valortipo[$codigtipo] += $valortipo;
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
<style>
.bordas{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #cccccc;
}
.bordas01{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #DEB887;
}
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #999999;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.focado.focus();">
<table width="100%" height="100%" border="2" cellspacing="0" cellpadding="0">
<form name='form1'>
  <tr>
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
      <br><br>
      <center>
      <?
      if($sair==true){      	 
        echo "<input type='text' name='focado1' onBlur='parent.db_iframe_mostratotal.hide();' size='1'>";
	echo "<strong> Registros não encontrados.</strong>";
        echo "<input type='text' name='focado' onBlur='parent.db_iframe_mostratotal.hide();' size='1'>";
      }else if($sair==false){
      	echo "<table border='0'>";
        echo "  <tr>";
       	echo "    <td colspan='1' align='center' bgcolor='#CCCCCC' class='bordas02'><b>Conta pagadora</b></td>";
       	echo "    <td colspan='1' align='center' bgcolor='#CCCCCC' class='bordas02'>";
        echo "    <input type='text' name='focado1' onBlur='parent.db_iframe_mostratotal.hide();' size='1'>";
       	echo "    <b>Valor</b>";
        echo "    <input type='text' name='focado' onBlur='parent.db_iframe_mostratotal.hide();' size='1'>";
       	echo "    </td>";
        echo "  </tr>";
        $valortotal = 0;
	for($i=0;$i<$numrows_valores;$i++){
	  if(!isset($coluna)){
	    db_fieldsmemory($result_valores,$i);
	    $valortotal += $totaltipo;
	    echo "  <tr>";
	    echo "    <td colspan='1' align='left'  bgcolor='#CCCCCC' class='bordas'>$e83_descr</td>";
	    echo "    <td colspan='1' align='right' bgcolor='#CCCCCC' class='bordas'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".db_formatar($totaltipo,"f")."</td>";
	    echo "  </tr>";
	  }else{
	    $codigtipo = $arr_indextipo[$i];
	    $valortipo = $arr_valortipo[$codigtipo];
	    $result_descrtipo = $clempagetipo->sql_record($clempagetipo->sql_query($codigtipo,"e83_codtipo,e83_descr"));
	    if($clempagetipo->numrows>0){
	      db_fieldsmemory($result_descrtipo,0);
	      $valortotal += $valortipo;
	      echo "  <tr>";
	      echo "    <td colspan='1' align='left'  bgcolor='#CCCCCC' class='bordas'>$e83_descr</td>";
	      echo "    <td colspan='1' align='right' bgcolor='#CCCCCC' class='bordas'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".db_formatar($valortipo,"f")."</td>";
	      echo "  </tr>";
	    }
	  }
	}
	echo "  <tr>";
	echo "    <td colspan='1' align='right' bgcolor='#CCCCCC' class='bordas'><b>Valor total</b></td>";
	echo "    <td colspan='1' align='right' bgcolor='#CCCCCC' class='bordas'><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".db_formatar($valortotal,"f")."</b></td>";
	echo "  </tr>";
      }      
/*
    <td align="center" valign="top" bgcolor="#CCCCCC" colspan='2'>
      <input type='button' name='fechar' value='Fechar' onclick='parent.db_iframe_mostratotal.hide();'>
    </td>
*/
      ?>
      </center>
    </td>
  </tr>
</form>
</table>
</body>
<script>
  document.form1.focado.style.visibility = 'hidden';
  document.form1.focado1.style.visibility = 'hidden';
</script>
</html>