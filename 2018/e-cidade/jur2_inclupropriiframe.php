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
include("classes/db_promitente_classe.php");
include("classes/db_propri_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_socios_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$clpromitente = new cl_promitente;
$clpropri = new cl_propri;
$clsocios = new cl_socios;
$clcgm = new cl_cgm;
$clissbase = new cl_issbase;
$clrotulo = new rotulocampo;
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
/*
<?if(isset($inicial)){?>
function js_geratu(){
  obj=document.getElementsByTagName("INPUT");
  var nums="";
  var t="";
  var ent="";
  for(var i=0; i<obj.length; i++){
    if(obj[i].type=="checkbox"){
       if(obj[i].checked){
	  nums=obj[i].value;
          ent+=1;
       } 	 
    }
  }
  if(ent==""){
    alert("Marque um das opções!");
  }else if(ent>1){
    alert("Marque apenas uma das opções!");
  }else{

    jan = window.open('jur2_geradoc.php?inclupropri=true&modo=<?=$modo?>&chave=<?=$chave?>&inicial=<?=$inicial?>&cgm='+nums,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
    location.href="jur2_inclupropri.php";
  }  
}
<?
}
?>
*/
function js_gera(){
  obj=document.getElementsByTagName("INPUT");
  var nums="";
  var t="";
  var ent=false;
  for(var i=0; i<obj.length; i++){
    if(obj[i].type=="checkbox"){
       if(obj[i].checked){
         nums += t+obj[i].value;
         var ent=true;
       } 	 
       t="x";
    }
  }
  if(ent==false){
    alert("Marque um das opções!");
  }else{
    jan = window.open('jur2_geradoc.php?inclupropri=true&dadosini=<?=$dadosini?>&cgm='+nums,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
    parent.location.href="jur2_inclupropri.php";
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
      <b>Selecione apenas um outro proprietário para incluir:</b>      
    </td>
  </tr>
<?
     if(isset($j01_matric)){
        $repro = $clpropri->sql_record($clpropri->sql_query($j01_matric,"","cgm.z01_nome as nome,cgm.z01_numcgm")); 
        $numpropri=$clpropri->numrows;
        if($numpropri!=0){
          for($xi=0;$xi<$numpropri;$xi++){
            db_fieldsmemory($repro,$xi);
            echo "<tr><td><input type='checkbox'  id='check' value='".$z01_numcgm."'  name='propri$xi'></td><td><b>Outro proprietário:</b>$nome</td></tr>";
	    
	  }
	} 
        $repromi = $clpromitente->sql_record($clpromitente->sql_query($j01_matric,"","cgm.z01_nome as nome,j41_tipopro as tipopro,cgm.z01_numcgm")); 
        $numpromi=$clpromitente->numrows;
        if($numpromi!=0){
          for($xy=0;$xy<$numpromi;$xy++){
            db_fieldsmemory($repromi,$xy);
	    if($tipopro=="f"){
		echo "<tr><td><input type='checkbox' id='check' value='".$z01_numcgm."'  name='promi$xy' ></td><td><b>Promitente comprador:</b>$nome</td></tr>";
	     
	    }else{
              echo "<tr><td><input type='checkbox' id='check' value='".$z01_numcgm."' name='promi$xy' ></td><td><b>Promitente comprador prinicipal:</b>$nome</td></tr>";
	    }  
	  }
	} 
    }else if(isset($q02_inscr)){
        $reiptu = $clissbase->sql_record($clissbase->sql_query($q02_inscr,"cgm.z01_numcgm,z01_nome as nome")); 
        db_fieldsmemory($reiptu,0);
        echo "<tr><td><input type='checkbox' id='check' value='".$z01_numcgm."' name='proprinci' checked></td><td><b>Principal:</b>$nome</td></tr>";
	
        
        $reso = $clsocios->sql_record($clsocios->sql_query_file("",$z01_numcgm,"q95_numcgm")); 
        $numso=$clsocios->numrows;
        if($numso!=0){
          for($xr=0;$xr<$numso;$xr++){
            db_fieldsmemory($reso,$xr);
            $re = $clcgm->sql_record($clcgm->sql_query_file($q95_numcgm,"z01_nome as nome,z01_numcgm"));
            db_fieldsmemory($re,0);
            echo "<tr><td><input type='checkbox' id='check' value='".$z01_numcgm."' name='proprinci$xr'> </td><td><b>Sócio:</b>$nome</td></tr>";
	      
	  }
	} 
    } 	
?>  
    </table> 	 
  </form>
  </td>
  </tr>
</table>
</body>
</html>