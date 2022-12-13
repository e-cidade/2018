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
include("libs/db_sql.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_issportetipo_classe.php");
include("classes/db_ativid_classe.php");
include("classes/db_ativtipo_classe.php");
include("classes/db_tipcalc_classe.php");

$clissportetipo = new cl_issportetipo;
$clativid = new cl_ativid;
$clativtipo = new cl_ativtipo;
$cltipcalc = new cl_tipcalc;

$clativid->rotulo->label(); 
$clrotulo = new rotulocampo;
$clrotulo->label("q81_codigo");
$clrotulo->label("q81_descr");
$clrotulo->label("q85_descr");
$clrotulo->label('q40_codporte');
$clrotulo->label('q40_descr');
$clrotulo->label('q12_classe');
$clrotulo->label('q12_descr');
$clrotulo->label('q41_codtipcalc');

db_postmemory($HTTP_POST_VARS);

if ((isset($q81_codigo) && $q81_codigo!="") && (isset($atualizar))){
  db_inicio_transacao();
  $sqlerro=false;
  $result03=$clativtipo->sql_record($clativtipo->sql_query_file("","$q81_codigo","q80_ativ"));
  if($clativtipo->numrows>0){
  $numrows03=$clativtipo->numrows;
    for($y=0; $y<$numrows03; $y++){
      if ($sqlerro==false){
	db_fieldsmemory($result03,$y);  
	$clativtipo->q80_tipcal=$q81_codigo;
	$clativtipo->q80_ativ=$q80_ativ;
	$clativtipo->excluir($q80_ativ,$q81_codigo);
	$erro_msg = $clativtipo->erro_msg;
	if($clativtipo->erro_status=='0'){
	  $sqlerro = true;
	}
      }
    }
  }  
  
  $vt=$HTTP_POST_VARS;
  $ta=sizeof($vt);
  reset($vt);
  for($i=0; $i<$ta; $i++){
    $chave=key($vt);
    if(substr($chave,0,5)=="CHECK"){
      $dados=split("_",$chave); 
      $clativtipo->q80_ativ=$dados[1];
      $clativtipo->q80_tipcal=$q81_codigo;
      $clativtipo->incluir($dados[1],$q81_codigo);
      $erro_msg = $clativtipo->erro_msg;
      if ($clativtipo->erro_status==0){
	$sqlerro=true;
      }
      if($clativtipo->erro_status=='0'){
        $sqlerro = true;
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
function js_emite(){
  if (document.form1.q81_codigo.value!=""){
    document.form1.submit();
  }else{
    alert("Campo vazio!!");
  }
}

function js_limpa(){
   location.href='iss1_ativtipo044.php'; 
}
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
 <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<form name="form1" method="post" target="" action="">
<table border='0'>
<tr height="20px">
<td ></td>
<td ></td>
</tr>
  <?
  if ((isset($processar))&&(!isset($atualizar))){
    $db_opcao=3;
  }else $db_opcao=1;
  ?>
  <tr> 
    <td  align="left" nowrap title="<?=$Tq81_codigo?>"><?db_ancora(@$Lq81_codigo,"js_pesquisa_tipcalc(true);",1);?></td>
    <td align="left" nowrap>
      <? db_input("q81_codigo",6,$Iq81_codigo,true,"text",$db_opcao,"onchange='js_pesquisa_tipcalc(false);'");
         db_input("q81_descr",40,"$Iq81_descr",true,"text",3);  
        ?></td>
  </tr>
  <tr>
  <td colspan="2" align="center">
    <input name="processar" type="button"   value="Processar" onclick='js_emite();'>
    <input name="limpa" type="button" onclick='js_limpa();'  value="Limpar">
  
    
 
  </td>
  </tr>

  <?
    if (isset($q81_codigo) && $q81_codigo!=""){
       $result01=$clativid->sql_record($clativid->sql_query_file(null,"*","q03_ativ"));
       $numrows01=$clativid->numrows;
       if($numrows01>0){ 
          echo "
	  <table>
           <tr>
	     <td class='cabec'  title='Inverte marcação' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
	     <td class='cabec' align='center'  title='$Tq03_ativ'>".str_replace(":","",$Lq03_ativ)."</td>
	     <td class='cabec' align='center'  title='$Tq03_descr'>".str_replace(":","",$Lq03_descr)."</td>
	   </tr>
          "; 	   
       } 
       $result02=$clativtipo->sql_record($clativtipo->sql_query_file(null,null,"*","","q80_tipcal=$q81_codigo"));
       $numrows02=$clativtipo->numrows;
       for($i=0; $i<$numrows01; $i++){
         db_fieldsmemory($result01,$i);
         $che="";
	 for($h=0; $h<$numrows02; $h++){
           db_fieldsmemory($result02,$h);
	   if($q80_ativ==$q03_ativ){
	     $che="checked";
	   } 
	 }
	 $result_naumostra=$clativtipo->sql_record($clativtipo->sql_query_file(null,null,"*","","q80_ativ=$q03_ativ and  q80_tipcal<>$q81_codigo"));
         $numrows_naumostra=$clativtipo->numrows;
	 
	 if ($numrows_naumostra!=0){ 	 
	   
	 }else{
         echo"
           <tr>
	     <td  class='corpo' title='Inverte a marcação' align='center'><input $che type='checkbox' name='CHECK_$q03_ativ' id='CHECK_".$q03_ativ."'></td>
              <td  class='corpo'  align='center' title='$Tq03_ativ'><label for='CHECK_".$q03_ativ."' style=\"cursor: hand\"><small>$q03_ativ</small></label></td>
              <td  class='corpo'  align='center' title='$Tq03_descr'><label for='CHECK_".$q03_ativ."' style=\"cursor: hand\"><small>$q03_descr</small></label></td>
           </tr>";
	 }
	 }
	 echo"
	   </table>";	        
       

  ?>
  <tr height="20px">
  <td ></td>
  <td ></td>
  </tr>
  <tr>
  <td colspan="2" align="center">
    <input name="atualizar" type="submit"   value="Atualizar">
    <input name="limpa" type="button" onclick='js_limpa();'  value="Voltar">
  </td>
  </tr>
  <?
  }
  ?>
 
  </table>
  </form>
 

</center>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
//---------------------------------------------------------------
function js_pesquisa_tipcalc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issportetipo','func_tipcalc.php?funcao_js=parent.js_mostraportetipo1|q81_codigo|q81_descr','Pesquisa',true);
  }else{
     if(document.form1.q81_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_issportetipo','func_tipcalc.php?pesquisa_chave='+document.form1.q81_codigo.value+'&funcao_js=parent.js_mostraportetipo','Pesquisa',false);
     }else{
       document.form1.q81_descr.value = ''; 
     }
  }
}
function js_mostraportetipo(chave,erro){
  document.form1.q81_descr.value = chave; 
  if(erro==true){ 
    document.form1.q81_codigo.focus(); 
    document.form1.q81_codigo.value = ''; 
  }
}
function js_mostraportetipo1(chave1,chave2){
  document.form1.q81_codigo.value = chave1;
  document.form1.q81_descr.value = chave2;
  db_iframe_issportetipo.hide();
}
//----------------------------------------------------------------------
</script>
<?
if (isset($atualizar)){
    db_msgbox($erro_msg);
    if($clativtipo->erro_campo!=""){
      echo "<script> document.form1.".$clativtipo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clativtipo->erro_campo.".focus();</script>";
    }else{ 
      echo"<script>top.corpo.location.href='iss1_ativtipo004.php';</script>";
    }
}
?>
</body>
</html>