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
include("classes/db_arrecad_classe.php");
include("classes/db_arrecant_classe.php");
include("classes/db_autonumpre_classe.php");
include("classes/db_auto_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clauto = new cl_auto;
$clarrecad = new cl_arrecad;
$clarrecant = new cl_arrecant;
$clautonumpre = new cl_autonumpre;

if(isset($calcular)){
  $result = $clauto->sql_calculo($y50_codauto);
  db_fieldsmemory($result,0);
  $info = $fc_autodeinfracao;
  echo "<script>parent.iframe_calculo.location.href='fis1_autocalc001.php?y50_codauto=".$y50_codauto."&info1=$info';</script>";
  exit;
}elseif(!isset($info1)){
  $info = "";
  $result = $clautonumpre->sql_record($clautonumpre->sql_query($y50_codauto));
  if($clautonumpre->numrows > 0){
    db_fieldsmemory($result,0);
    $result  = $clarrecad->sql_record($clarrecad->sql_query("","arrecad.*",""," k00_numpre = $y17_numpre and k00_instit = ".db_getsession('DB_instit') ));
    $result1 = $clarrecant->sql_record($clarrecant->sql_query("","*",""," k00_numpre = $y17_numpre"));
    if($clarrecad->numrows > 0){
      $info = "Auto já calculado Numpre = $y69_numpre";
    }elseif($clarrecant->numrows > 0){
      $info = "Auto já Pago Numpre = $y69_numpre";
    }else{
      $info = "Auto  não calculado";
    }
  }
}else{
  $info = $info1;
  $disabilita = true;
}
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
</script>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<form method="post" name="form1" action="" onload='js_codigo();' >

<?db_input('y50_codauto',10,'',true,'hidden',3);?>

<table width="60%">
    <tr>
      <td align="center"><br><br><font face="Arial, Helvetica, sans-serif"><strong>Cálculo do Auto de Infração</strong></font></td>
    </tr>
    <tr>
      <td><table width="100%">
  <tr>
    <td nowrap title="">
      <fieldset>
      <legend><strong>CÁLCULO: </strong></legend>
	<strong><?=$info?></strong>
      </legend>
    </td>
  </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center"><input name="calcular" type="submit" value="Calcular">
      </td>
      </tr>
  </table>
</form>
<script>
function js_calculo(){
  document.form1.y50_codauto.value=parent.iframe_auto.document.form1.y50_codauto.value;
}
</script>
</center>
</body>
</html>
<?
if(@$disabilita == true){
  echo "<script>document.form1.calcular.disabled = true</script>";
}
?>