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
include("classes/db_ativtipo_classe.php");
include("classes/db_tipcalc_classe.php");

$clissportetipo = new cl_issportetipo;
$clativtipo = new cl_ativtipo;
$cltipcalc = new cl_tipcalc;

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

if ((isset($q41_codporte) && $q41_codporte!="") && (isset($q41_codclasse) && $q41_codclasse!="") && (isset($atualizar))){
  db_inicio_transacao();
  $sqlerro=false;
  $result_reg = $clissportetipo->sql_record($clissportetipo->sql_query_file(null,"q41_codigo",null,"q41_codporte=$q41_codporte and q41_codclasse=$q41_codclasse"));
  if($clissportetipo->numrows>0){
    $numrows_1=$clissportetipo->numrows;
    for($xw=0; $xw<$numrows_1; $xw++){
      db_fieldsmemory($result_reg,$xw);
      $clissportetipo->excluir($q41_codigo);
      $erro_msg = $clissportetipo->erro_msg;
      if ($clissportetipo->erro_status==0){
	$sqlerro=true;
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
      $clissportetipo->q41_codporte = $q41_codporte; 
      $clissportetipo->q41_codclasse= $q41_codclasse; 
      $clissportetipo->q41_codtipcalc = $dados[1]; 
      $clissportetipo->incluir(null);
      $erro_msg = $clissportetipo->erro_msg;
      if ($clissportetipo->erro_status==0){
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
function js_emite(){
  if ((document.form1.q41_codporte.value!="")&&(document.form1.q41_codclasse.value!="")){
    document.form1.submit();
  }else{
    alert("Campo vazio!!");
    if (document.form1.q41_codporte.value==""){
      document.form1.q41_codporte.focus();
    }else if (document.form1.q41_codclasse.value==""){
      document.form1.q41_codclasse.focus();
    }
  }
}

function js_limpa(){
   location.href='iss4_issportetipo001.php'; 
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
    <td  align="left" nowrap title="<?=$Tq12_classe?>"><?db_ancora(@$Lq12_classe,"js_pesquisaq12_classe(true);",1);?></td>
    <td align="left" nowrap>
      <? db_input("q41_codclasse",6,$Iq12_classe,true,"text",$db_opcao,"onchange='js_pesquisaq12_classe(false);'");
         db_input("q12_descr",40,"$Iq12_descr",true,"text",3);  
        ?></td>
  </tr>
  <tr> 
    <td  align="left" nowrap title="<?=$Tq40_codporte?>"><?db_ancora(@$Lq40_codporte,"js_pesquisaq40_codporte(true);",1);?></td>
    <td align="left" nowrap>
      <? db_input("q41_codporte",6,$Iq40_codporte,true,"text",$db_opcao,"onchange='js_pesquisaq40_codporte(false);'");
         db_input("q40_descr",40,"$Iq40_descr",true,"text",3);  
        ?></td>
  </tr>
  <?
    if ((isset($q41_codporte) && $q41_codporte!="") && (isset($q41_codclasse) && $q41_codclasse!="")&&(!isset($atualizar))){
       $result01=$cltipcalc->sql_record($cltipcalc->sql_query("","q81_codigo,q81_descr,q85_descr","q81_cadcalc,q81_descr","q81_tipo in (3,4,5)" ));
       $numrows01=$cltipcalc->numrows;
       if($numrows01>0){ 
          echo "
	  <table>
           <tr>
	     <td class='cabec'  title='Inverte marcação' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
	     <td class='cabec' align='center'  title='$Tq81_codigo'>".str_replace(":","",$Lq81_codigo)."</td>
	     <td class='cabec' align='center'  title='$Tq81_descr'>".str_replace(":","",$Lq81_descr)."</td>
	     <td class='cabec' align='center'  title='$Tq85_descr'>".str_replace(":","",$Lq85_descr)."</td>
	   </tr>
          "; 	   
       } 
       $result02=$clissportetipo->sql_record($clissportetipo->sql_query_file(null,"q41_codtipcalc",null,"q41_codporte=$q41_codporte and q41_codclasse=$q41_codclasse"));
       $numrows02=$clissportetipo->numrows;
       for($i=0; $i<$numrows01; $i++){
         db_fieldsmemory($result01,$i);
         $che="";
	 for($h=0; $h<$numrows02; $h++){
           db_fieldsmemory($result02,$h);
	   if($q41_codtipcalc==$q81_codigo){
	     $che="checked";
	   } 
	 }
         echo"
           <tr>
	     <td  class='corpo' title='Inverte a marcação' align='center'><input $che type='checkbox' name='CHECK_$q81_codigo' id='CHECK_".$q81_codigo."'></td>
              <td  class='corpo'  align='center' title='$Tq81_codigo'><label for='CHECK_".$q81_codigo."' style=\"cursor: hand\"><small>$q81_codigo</small></label></td>
              <td  class='corpo'  align='center' title='$Tq81_descr'><label for='CHECK_".$q81_codigo."' style=\"cursor: hand\"><small>$q81_descr</small></label></td>
              <td  class='corpo'  align='center' title='$Tq85_descr'><label style=\"cursor: hand\"><small>$q85_descr</small></label></td>
           </tr>";
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
  <?
  }else{
  ?>
  <tr>
  <td colspan="2" align="center">
    <input name="processar" type="button"   value="Processar" onclick='js_emite();'>
    <input name="limpa" type="button" onclick='js_limpa();'  value="Limpar">
  <?
  }  
  ?>
  </td>
  </tr>
  </table>
  </form>
 

</center>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
//---------------------------------------------------------------
function js_pesquisaq40_codporte(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issporte','func_issporte.php?funcao_js=parent.js_mostraporte1|q40_codporte|q40_descr','Pesquisa',true);
  }else{
     if(document.form1.q41_codporte.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_issporte','func_issporte.php?pesquisa_chave='+document.form1.q41_codporte.value+'&funcao_js=parent.js_mostraporte','Pesquisa',false);
     }else{
       document.form1.q40_descr.value = ''; 
     }
  }
}
function js_mostraporte(chave,erro){
  document.form1.q40_descr.value = chave; 
  if(erro==true){ 
    document.form1.q41_codporte.focus(); 
    document.form1.q41_codporte.value = ''; 
  }
}
function js_mostraporte1(chave1,chave2){
  document.form1.q41_codporte.value = chave1;
  document.form1.q40_descr.value = chave2;
  db_iframe_issporte.hide();
}
//----------------------------------------------------------------------
function js_pesquisaq12_classe(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_classe','func_classe.php?funcao_js=parent.js_mostraclasse1|q12_classe|q12_descr','Pesquisa',true);  
    }else{
     if(document.form1.q41_codclasse.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_classe','func_classe.php?pesquisa_chave='+document.form1.q41_codclasse.value+'&funcao_js=parent.js_mostraclasse','Pesquisa',false);
     }else{
       document.form1.q12_descr.value = ''; 
     }
  }
}
function js_mostraclasse(chave,erro){
  document.form1.q12_descr.value = chave;
  if(erro==true){ 
    document.form1.q41_codclasse.value = ''; 
    document.form1.q41_codclasse.focus(); 
  }
}
function js_mostraclasse1(chave1,chave2){
  document.form1.q41_codclasse.value =chave1;
  document.form1.q12_descr.value =chave2;
  db_iframe_classe.hide();
}
//-----------------------------------------------------
function js_pesquisaq41_codtipcalc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issportetipo','func_tipcalc.php?funcao_js=parent.js_mostraportetipo1|q81_codigo|q81_descr','Pesquisa',true);
  }else{
     if(document.form1.q41_codtipcalc.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_issportetipo','func_tipcalc.php?pesquisa_chave='+document.form1.q41_codtipcalc.value+'&funcao_js=parent.js_mostraportetipo','Pesquisa',false);
     }else{
       document.form1.q81_descr.value = ''; 
     }
  }
}
function js_mostraportetipo(chave,erro){
  document.form1.q81_descr.value = chave; 
  if(erro==true){ 
    document.form1.q41_codtipcalc.focus(); 
    document.form1.q41_codtipcalc.value = ''; 
  }
}
function js_mostraportetipo1(chave1,chave2){
  document.form1.q41_codtipcalc.value = chave1;
  document.form1.q81_descr.value = chave2;
  db_iframe_issportetipo.hide();
}
//----------------------------------------------------------------------
</script>
<?
if (isset($atualizar)){
    db_msgbox($erro_msg);
    if($clissportetipo->erro_campo!=""){
      echo "<script> document.form1.".$clissportetipo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clissportetipo->erro_campo.".focus();</script>";
    }else{ 
      echo"<script>top.corpo.location.href='iss4_issportetipo001.php';</script>";
    }
}
?>
</body>
</html>