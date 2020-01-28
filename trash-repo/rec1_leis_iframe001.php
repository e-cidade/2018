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
include("classes/db_leis_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
$clleis = new cl_leis;
$db_botao = true;
$clleis->rotulo->label();
if(isset($h08_codlei) && trim($h08_codlei) != ""){
  $result_dados = $clleis->sql_record($clleis->sql_query_file($h08_codlei));
  if($clleis->numrows > 0){
    db_fieldsmemory($result_dados, 0);
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
         border: 2px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #999999;
}
.bordas1{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #cccccc;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td>
      <table>
        <tr>
          <td align="center" class="bordas"><b>Anos</b></td>
          <td align="center" class="bordas"><b>Perc.</b></td>
          <td align="center" class="bordas"><b>Inf.</b></td>
        </tr>
	<?
	for($i=1; $i<10; $i++){
	  $Ih08_anos = "Ih08_anos".$i;
	  $Ih08_perc = "Ih08_perc".$i;
	  $Ih08_car  = "Ih08_car".$i;
	  $h08_anos  = "h08_anos".$i;
	  $h08_perc  = "h08_perc".$i;
	  $h08_car   = "h08_car".$i;
	  if(isset($$h08_anos) && $$h08_anos == 0 && isset($$h08_perc) && $$h08_perc == 0){
	    $$h08_anos = "";
	    $$h08_perc = "";
	    $$h08_car  = "";
	  }
	?>
        <tr>
          <td align="center" class="bordas1"> 
            <?
            db_input('h08_anos'.$i,2,$$Ih08_anos,true,'text',$db_opcao,"")
            ?>
          </td>
          <td align="center" class="bordas1"> 
            <?
            db_input('h08_perc'.$i,5,$$Ih08_perc,true,'text',$db_opcao,"")
            ?>
          </td>
          <td align="center" class="bordas1"> 
            <?
            db_input('h08_car'.$i,3,$$Ih08_car,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
	<?
	}
	?>
      </table>
    </td>
    <td>
      <table>
        <tr>
          <td align="center" class="bordas"><b>Anos</b></td>
          <td align="center" class="bordas"><b>Perc.</b></td>
          <td align="center" class="bordas"><b>Inf.</b></td>
        </tr>
	<?
	for($i=10; $i<19; $i++){
	  $Ih08_anos = "Ih08_anos".$i;
	  $Ih08_perc = "Ih08_perc".$i;
	  $Ih08_car  = "Ih08_car".$i;
	  $h08_anos  = "h08_anos".$i;
	  $h08_perc  = "h08_perc".$i;
	  $h08_car   = "h08_car".$i;
	  if(isset($$h08_anos) && $$h08_anos == 0 && isset($$h08_perc) && $$h08_perc == 0){
	    $$h08_anos = "";
	    $$h08_perc = "";
	    $$h08_car  = "";
	  }
	?>
        <tr>
          <td align="center" class="bordas1"> 
            <?
            db_input('h08_anos'.$i,2,$$Ih08_anos,true,'text',$db_opcao,"")
            ?>
          </td>
          <td align="center" class="bordas1"> 
            <?
            db_input('h08_perc'.$i,5,$$Ih08_perc,true,'text',$db_opcao,"")
            ?>
          </td>
          <td align="center" class="bordas1"> 
            <?
            db_input('h08_car'.$i,3,$$Ih08_car,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
	<?
	}
	?>
      </table>
    </td>
  </tr>
</table>
</center>
</form>
<?if(isset($valores) && trim($valores) != ""){?>
<script>
parent.js_atualixaIframe("<?=$valores?>");
</script>
<?}?>