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
include("classes/db_issbase_classe.php");
include("classes/db_iptubase_classe.php");
include("classes/db_promitente_classe.php");
include("classes/db_propri_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_socios_classe.php");
include("classes/db_inicialnomes_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$clpromitente = new cl_promitente;
$clpropri = new cl_propri;
$clsocios = new cl_socios;
$clcgm = new cl_cgm;
$clissbase = new cl_issbase;
$cliptubase = new cl_iptubase;
$clrotulo = new rotulocampo;
$clinicialnomes = new cl_inicialnomes;
$clrotulo->label("v50_inicial");

$db_botao=1;
$db_opcao=1;
$retorno = false;
$monitora = false;
if($modo=="inscricao"){
  $q02_inscr = $chave; 
}else{
  $j01_matric = $chave;      
} 
$dadosini = "xx".$inicial."ww".$chave."ww".$modo;



?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
td {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
input {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
  height: 17px;
  border: 1px solid #999999;
}
-->
</style>
<script>
function js_gera(){
  obj=document.getElementsByTagName("INPUT");
  var numatu="";
  var numant="";
  var t="";
  var v="";
  var ant=false;
  var atu=false;
  for(var i=0; i<obj.length; i++){
    if(obj[i].type=="checkbox"){
      if(obj[i].id=="check_ant"){
        if(obj[i].checked){
          numant += t+obj[i].value;
          var ant=true;
          t="x";
        } 	 
      }else{
        if(obj[i].checked){
          numatu += v+obj[i].value;
          var atu=true;
          v="x";
        } 	 
      }	
    }
  }
  if(atu==false){
    alert("Marque um das opções de atual proprietário!");
  }else if(ant==false){
    alert("Marque um das opções de antigo proprietário!");
  }else{
//    alert(numant+"e"+numatu);
    jan = window.open('jur2_geradoc.php?trocapropri=true&dadosini=<?=$dadosini?>&cgmant='+numant+'&cgmatu='+numatu,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
//    parent.location.href="jur2_inclupropri.php";
  }  
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="595" height="345"  border="1" valign="top" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
<tr> 
<td align="center" valign="top" bgcolor="#cccccc">     
<form name="form1" method="post" action="">
<input type="hidden" name="inicial" value="<?=$inicial?>">
<table  border="0" cellspacing="0" cellpadding="0">
<br><br>
<tr>
<td  align="center" colspan="2">
<b>Proprietários:</b>      
</td>
</tr>
<?
$resultini = $clinicialnomes->sql_record($clinicialnomes->sql_query($inicial,"","v58_numcgm","z01_nome")); 
$numrows = $clinicialnomes->numrows;
$numini="";
$xc="";
$numcgms="";
if($numrows!=0){
  for($f=0; $f<$numrows; $f++){
    db_fieldsmemory($resultini,$f);
    $numini .= $xc.$v58_numcgm;
    $xc="X";
  }
}else{
  echo "<script>parent.location.href='jur2_trocapropri.php?noininomes=true';</script>"; 
}

if(isset($j01_matric)){
  $reip = $cliptubase->sql_record($cliptubase->sql_query($j01_matric,"cgm.z01_numcgm")); 
  db_fieldsmemory($reip,0);
  $numcgms .= $z01_numcgm;
  
  $repro = $clpropri->sql_record($clpropri->sql_query($j01_matric,"","cgm.z01_numcgm")); 
  $numpropri=$clpropri->numrows;
  if($numpropri!=0){
    for($xi=0;$xi<$numpropri;$xi++){
      db_fieldsmemory($repro,$xi);
      $numcgms .= "X".$z01_numcgm;
    }
  } 
  $repromi = $clpromitente->sql_record($clpromitente->sql_query($j01_matric,"","cgm.z01_numcgm")); 
  $numpromi=$clpromitente->numrows;
  if($numpromi!=0){
    for($xy=0;$xy<$numpromi;$xy++){
      db_fieldsmemory($repromi,$xy);
      $numcgms .= "X".$z01_numcgm;
    }  
  }  
  
  
  $matriz1=split("X",$numini);
  $igual="false";
  $m=0;
  for($u=0; $u<sizeof($matriz1); $u++){
    if($matriz1[$u]!=""){
      $matriz2=split("X",$numcgms);
      for($w=0; $w<sizeof($matriz2); $w++){
        if($matriz2[$w]!=""){
          if($matriz1[$u]==$matriz2[$w]){
            $m++;
          }
          
        }  
      }  
    }
  } 
  if($m==sizeof($matriz1) && $m==sizeof($matriz2)){
    echo "<script>parent.location.href='jur2_trocapropri.php?notroca=true';</script>"; 
    
  }
  
}else if(isset($q02_inscr)){
  $resultini = $clinicialnomes->sql_record($clinicialnomes->sql_query($inicial,"","v58_numcgm","z01_nome")); 
  $numrows = $clinicialnomes->numrows;
  $numini="";
  $xc="";
  $numcgms="";
  if($numrows!=0){
    for($f=0; $f<$numrows; $f++){
      db_fieldsmemory($resultini,$f);
      $numini .= $xc.$v58_numcgm;
      $xc="X";
    }
  }else{
    echo "<script>parent.location.href='jur2_trocapropri.php?noininomes=true';</script>"; 
  }
  $reiptu = $clissbase->sql_record($clissbase->sql_query($q02_inscr,"cgm.z01_numcgm")); 
  db_fieldsmemory($reiptu,0);
  $numcgms = $z01_numcgm;
  
  $reso = $clsocios->sql_record($clsocios->sql_query_file("",$z01_numcgm,"q95_numcgm")); 
  $numso=$clsocios->numrows;
  if($numso!=0){
    for($xr=0;$xr<$numso;$xr++){
      db_fieldsmemory($reso,$xr);
      $numcgms .= "X".$q95_numcgm;
    }
  } 
  $matriz1=split("X",$numini);
  $igual="false";
  $m=0;
  for($u=0; $u<sizeof($matriz1); $u++){
    if($matriz1[$u]!=""){
      $matriz2=split("X",$numcgms);
      for($w=0; $w<sizeof($matriz2); $w++){
        if($matriz2[$w]!=""){
          if($matriz1[$u]==$matriz2[$w]){
            $m++;
          }
          
        }  
      }  
    }
  } 
  if($m==sizeof($matriz1) && $m==sizeof($matriz2)){
    //echo "<script>parent.location.href='jur2_trocapropri.php?notroca=true';</script>"; 
    die("sem inicialnomes");	 
  }
}  

echo "<tr>
<td valign='top'>
<table border='0'>
<tr><td colspan='2'><b>ANTIGOS PROPRIETÁRIOS</b></td></tr>
";

for($u=0; $u<sizeof($matriz1); $u++){
  if($matriz1[$u]!=""){
    $resulti = $clcgm->sql_record($clcgm->sql_query_file($matriz1[$u],"z01_nome")); 
    db_fieldsmemory($resulti,0);
    echo "<tr><td><input type='checkbox' id='check_ant' value='".$matriz1[$u]."' name='x$u' checked></td><td>$z01_nome</td></tr>";
    
  }
}  	 
echo "  </table> 
</td> 
<td valign='top'>
<table>
<tr><td colspan='2'><b>ATUAIS PROPRIETÁRIOS</b></td></tr>";
$ond = "";
$on="";
for($u=0; $u<sizeof($matriz2); $u++){
  if($matriz2[$u]!=""){
    $ond .= $on." z01_numcgm=".$matriz2[$u];
    $on =" or ";
  }
}  	 
$resulti = $clcgm->sql_record($clcgm->sql_query_file("","z01_nome,z01_numcgm","z01_nome",$ond)); 
$numro = $clcgm->numrows;
for($d=0; $d<$numro; $d++){
  db_fieldsmemory($resulti,$d);
  echo "<tr><td><input type='checkbox' id='check_atu' value='".$z01_numcgm."' name='x$d' checked></td><td>$z01_nome</td></tr>";
}	 

echo " </table> </td> </tr>";

?>  
</table> 	 
</form>
</td>
</tr>
</table>
</body>
</html>