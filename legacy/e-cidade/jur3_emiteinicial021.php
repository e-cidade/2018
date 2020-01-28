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
include("classes/db_cgm_classe.php");
include("classes/db_advog_classe.php");
include("classes/db_inicial_classe.php");
include("classes/db_inicialcert_classe.php");
include("classes/db_inicialmov_classe.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$db_botao=1;
$botao=1;
$db_opcao=1;
$verificachave=true;
$veinclu=false;

$clinicial = new cl_inicial;
$clinicialcert = new cl_inicialcert;
$clinicialmov = new cl_inicialmov;
$cladvog = new cl_advog;
$cladvog->rotulo->label();
$clcgm = new cl_cgm;
$clcgm->rotulo->label("z01_numcgm");
$clcgm->rotulo->label("z01_nome");



$clrotulo = new rotulocampo;
$clrotulo->label("v56_data");
$clrotulo->label("v56_codsit");
$clrotulo->label("v52_descr");
$clrotulo->label("v53_descr");
$clrotulo->label("v54_codlocal");
$clrotulo->label("v54_descr");
$clrotulo->label("v50_inicial");
$clrotulo->label("v50_advog");
$clrotulo->label("v13_certid");

$sql="";
$codsit="";
$codini="";
$data="";
$advog="";
$codlocal="";

if(isset($pesquisar)){
  $codsit="";
  $data="";
  $advog="";
  $codlocal="";
  $chave="";
  $xx="#"; 
 
  if(isset($v50_inicial) && $v50_inicial!="" && isset($auto)){
    $sql=$clinicial->sql_query_sit($v50_inicial,"v50_inicial,v52_descr,v54_descr,cgm.z01_nome","v50_inicial"," v50_situacao = $selTipo and v50_inicial = $v50_inicial and v50_instit = ".db_getsession('DB_instit') );
    $clinicial->sql_record($sql); 
    $chave = $Lv50_inicial.$v50_inicial.$xx;
  }else{ 

 if(isset($v50_inicial) && $v50_inicial!=""){
    $codini =" and v50_inicial = $v50_inicial ";
 }
 if(isset($v56_codsit) && $v56_codsit!=""){
    $codsit =" and v56_codsit= $v56_codsit ";
 }
 if($dataini_dia!="" && $dataini_mes!="" && $dataini_ano!=""){
   $dataini=$dataini_ano."-".$dataini_mes."-".$dataini_dia;
 }  
 if($datafim_dia!="" && $datafim_mes!="" && $datafim_ano!=""){
   $datafim=$datafim_ano."-".$datafim_mes."-".$datafim_dia;
 }  
 if(isset($dataini) && trim($dataini) != "" && trim($datafim) != "" && isset($datafim)  ){
    $data =" and v56_data between '$dataini' and '$datafim' ";
 }
 if(isset($dataini) && trim($dataini) != "" && !isset($datafim) ){
    $data =" and v56_data > '$dataini' ";
 }
 if(!isset($dataini) && isset($datafim) && trim($datafim) != ""){
    $data =" and v56_data < '$datafim' ";
 }
 if(isset($v50_advog) && $v50_advog!=""){
    $advog =" and v50_advog = '$v50_advog' ";
 }   
 if(isset($v54_codlocal) && $v54_codlocal!=""){
    $codlocal=" and v50_codlocal = '$v54_codlocal' ";
 }   
  $sql=$clinicial->sql_query_sit("","v50_inicial,v52_descr,v54_descr,cgm.z01_nome","v50_inicial"," 1=1 and v50_situacao = $selTipo and v50_instit = ".db_getsession('DB_instit')." ".$codsit.$data.$advog.$codlocal.$codini);
	$clinicial->sql_record($sql); 
  if($clinicial->numrows==0){
    db_redireciona("jur3_emiteinicial002.php?invalido=true");  
  }
 if(isset($v50_inicial) && $v50_inicial!=""){
    $chave .= $Lv50_inicial.$v50_inicial.$xx ;
 }
 if(isset($v56_codsit) && $v56_codsit!=""){
    $chave .= $Lv56_codsit.$v52_descr.$xx ;
 }
 if(isset($dataini) && trim($dataini) != ""  && isset($datafim) && trim($dataini) != "" ){
    $chave .= $Lv56_data.$dataini." até ".$datafim.$xx ;
 }
 if(isset($dataini) && trim($dataini) != "" && !isset($datafim) ){
    $chave .= $Lv56_data." a partir de ".$dataini.$xx;
 }
 if(!isset($dataini) && isset($datafim) && trim($dataini) != ""){
    $chave .= $Lv56_data." até ".$datafim.$xx;
 }
 if(isset($v50_advog) && $v50_advog!=""){
    $chave .= $Lv50_advog.$z01_nome.$xx;
 }
 if(isset($v54_codlocal) && $v54_codlocal!=""){
    $chave .= $Lv54_codlocal.$v54_descr.$xx;
 }

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
function js_volta(){
  location.href="jur3_emiteinicial002.php";  
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<table height="" width="" border="0" valign="top" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
  <tr> 
    <td>&nbsp;</td>
  </tr>   
  <tr>   
    <td>
			<fieldset>
				<legend align="center">
					<b>&nbsp; Critérios da Pesquisa &nbsp;</b>
				</legend>
				<table border="0" cellspacing="0" cellpadding="0">
					<tr>  
					  <td>&nbsp;</td>
            <td valign="top"  align="left">
              <? 
                $matriz=split("#",@$chave);
               	for($y=0;$y<sizeof($matriz);$y++){
               	  if($matriz[$y]!=""){
           	        echo "<label>";
									  echo $matriz[$y];
									  echo "</label>";
									  echo "<br>";
									} 
								}  
              ?>
            </td>
          </tr>
				  <tr> 
						<td valign="top" align="center" colspan="2" >
							<? 
								db_lovrot($sql,15,"()","","js_inicialmovcert|0");
							?>
						</td>
					</tr>  
				</table>
			</fieldset>
				<table align="center">
					<tr>  
						<td>
							<input name="voltar" type="button" value="Nova Pesquisa" onclick="js_volta()">
						</td>  
					</tr>
				</table>
			</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_inicialmovcert(inicial){
    db_iframe.jan.location.href = 'func_inicialmovcert.php?v50_inicial='+inicial+'&funcao_js=parent.js_oculta';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
}
function js_oculta(){
  db_iframe.hide();
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

  if(isset($v50_inicial) && $v50_inicial!="" && isset($auto)){
     echo"<script>js_inicialmovcert($v50_inicial);</script>";
  }   
?>