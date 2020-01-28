<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
include("libs/db_sql.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_db_almox_classe.php");
include("classes/db_db_almoxdepto_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_matparam_classe.php");
include("dbforms/db_funcoes.php");
$cldb_almox = new cl_db_almox;
$cldb_almoxdepto = new cl_db_almoxdepto;
$cldb_depart = new cl_db_depart;
$clmatparam = new cl_matparam;
$clrotulo = new rotulocampo;
$clrotulo->label('coddepto');
$clrotulo->label('descrdepto');
$clrotulo->label('m92_codalmox');
db_postmemory($HTTP_POST_VARS);

if (isset($atualizar)){
  db_inicio_transacao();
  $sqlerro=false;
  $result03=$cldb_almoxdepto->sql_record($cldb_almoxdepto->sql_query_file("$codalmox","","m92_depto"));
  if($cldb_almoxdepto->numrows>0){
    $numrows03=$cldb_almoxdepto->numrows;
    for($y=0; $y<$numrows03; $y++){
      if ($sqlerro==false){
        db_fieldsmemory($result03,$y);  
        $cldb_almoxdepto->m92_codalmox=$codalmox;
        $cldb_almoxdepto->m92_depto=$m92_depto;
        $cldb_almoxdepto->excluir($codalmox,$m92_depto);
        $erro_msg = $cldb_almoxdepto->erro_msg;
        if($cldb_almoxdepto->erro_status=='0'){
          $sqlerro = true;
        }
      }
    }
		$cldb_almoxdepto->m92_depto=$depto_almox;
		$cldb_almoxdepto->m92_codalmox=$m92_codalmox;
		$cldb_almoxdepto->incluir($m92_codalmox,$depto_almox);
		$erro_msg = $cldb_almoxdepto->erro_msg;
		if ($cldb_almoxdepto->erro_status==0){
			$sqlerro=true;
		}
  }  
  $vt=$HTTP_POST_VARS;
  $ta=sizeof($vt);
  reset($vt);
  for($i=0; $i<$ta; $i++){
    $chave=key($vt);
    if(substr($chave,0,5)=="CHECK"){
      $dados=split("_",$chave); 
      $cldb_almoxdepto->m92_depto=$dados[1];
      $cldb_almoxdepto->m92_codalmox=$codalmox;
      $cldb_almoxdepto->incluir($codalmox,$dados[1]);
      $erro_msg = $cldb_almoxdepto->erro_msg;
      if ($cldb_almoxdepto->erro_status==0){
        $sqlerro=true;
      }
    }
    $proximo=next($vt);
  }  
  db_fim_transacao($sqlerro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_marca(obj){ 
  var OBJ = document.form1;
  for(i=0;i<OBJ.length;i++){
    if(OBJ.elements[i].type == 'checkbox'){
      OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
    }
  }
  return false;
}
</script>  
<style>
.cabec {
  text-align: center;
  color: darkblue;
  background-color:#aacccc;       
  border-color: darkblue;
}
.corpo {
  text-align: center;
  color: black;
  background-color:#ccddcc;       
}
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
</table>
<center>
<form name="form1" method='post'>
<table border='0'>
<tr>
<td ></td>
<td ></td>
</tr>
<tr> 
<td align="left" nowrap title="<?=$Tm92_codalmox?>"><?db_ancora(@$Lm92_codalmox,"js_pesquisam92_codalmox(true);",3);?></td>
<td align="left" nowrap>
<? 
$result_descr=$cldb_almox->sql_record($cldb_almox->sql_query($codalmox,"m91_depto as depto_almox,descrdepto"));
if ($cldb_almox->numrows>0){
  db_fieldsmemory($result_descr,0);
}
db_input("codalmox",6,$Im92_codalmox,true,"hidden",3,"");
db_input("depto_almox",6,"",true,"hidden",3,"");
$m92_codalmox=@$codalmox;
db_input("m92_codalmox",6,$Im92_codalmox,true,"text",3,"");
db_input("descrdepto",40,$Idescrdepto,true,"text",3);  
?>
</td>
</tr>
<tr>
<td colspan="2" align="center">
<br>

<? 
//desativa botao atualizar caso seja exclusão
if( isset($db_opcao) == 33){
  $read = "disabled";
} else {
  $read = null;		
}
echo "<input $read name=\"atualizar\" type=\"submit\" value=\"Atualizar\">";
?>

<br>
</td>
</tr>
<?

if (isset($codalmox) && $codalmox!=""){

$dtAtual      = date("Y-m-d", db_getsession('DB_datausu'));
$iInstituicao = db_getsession("DB_instit");

$sSqlDepartamentos = $cldb_depart->sql_query_file(null,"*","coddepto"," instit = {$iInstituicao} and (limite >=  '$dtAtual' or  limite is null)");

  $result01=$cldb_depart->sql_record($sSqlDepartamentos);
  $numrows01=$cldb_depart->numrows;
  if($numrows01>0){ 
    echo "<table>
    <tr>
    <td class='cabec'  title='Inverte marcação' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
    <td class='cabec' align='center'  title='$Tcoddepto'>".str_replace(":","",$Lcoddepto)."</td>
    <td class='cabec' align='center'  title='$Tdescrdepto'>".str_replace(":","",$Ldescrdepto)."</td>
    </tr>"; 	   
  } 
  $result02=$cldb_almoxdepto->sql_record($cldb_almoxdepto->sql_query_file(null,null,"*","","m92_codalmox=$codalmox"));
  $numrows02=$cldb_almoxdepto->numrows;
  $read="";
  $result_param=$clmatparam->sql_record($clmatparam->sql_query_file());
  if ($clmatparam->numrows>0){
    db_fieldsmemory($result_param,0);
  }
  for($i=0; $i<$numrows01; $i++){
    db_fieldsmemory($result01,$i);
    $che="";
    $read="";
    if (@$depto_almox==$coddepto){
      $che="checked";
      $read="disabled";
    }
    for($h=0; $h<$numrows02; $h++){
      db_fieldsmemory($result02,$h);        
      if($m92_depto==$coddepto){
        $che="checked";
      } 
    }
    if (isset($m90_deptalmox)&&$m90_deptalmox=='t'){
    }else{
      $result_naumostra=$cldb_almoxdepto->sql_record($cldb_almoxdepto->sql_query_file(null,null,"*","","m92_depto=$coddepto and m92_codalmox<>$codalmox"));
      $numrows_naumostra=$cldb_almoxdepto->numrows;
      if ($numrows_naumostra!=0){
        continue; 	 
      }
    }
    
    //desativa checkbox caso seja exclusão
    if( isset($db_opcao) == "33"){
      $read = "disabled";
    }
    
    echo "<tr>
    <td  class='corpo' title='Inverte a marcação' align='center'><input $read  $che type='checkbox' name='CHECK_$coddepto' id='CHECK_".$coddepto."'></td>
    <td  class='corpo'  align='center' title='$Tcoddepto'><label for='CHECK_".$coddepto."' style=\"cursor: hand\"><small>$coddepto</small></label></td>
    <td  class='corpo'  align='center' title='$Tdescrdepto'><label for='CHECK_".$coddepto."' style=\"cursor: hand\"><small>$descrdepto</small></label></td>
    </tr>";
  }
  echo "</table>";	        
  ?>
  <tr >
  <td ></td>
  <td ></td>
  </tr>
  <?
}
?>
</table>
</form>
</center>
<? 
if (isset($atualizar)){
  db_msgbox($erro_msg);
  if($cldb_almoxdepto->erro_campo!=""){
    echo "<script> document.form1.".$cldb_almoxdepto->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$cldb_almoxdepto->erro_campo.".focus();</script>";
  }else{ 
    //echo"<script>top.corpo.location.href='mat1_matdb_almoxdepto001.php?codalmox=$codalmox';</script>";
  }
}
?>
</body>
</html>