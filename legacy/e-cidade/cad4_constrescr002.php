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
include("classes/db_iptubase_classe.php");
include("classes/db_constrescr_classe.php");
include("classes/db_constrcar_classe.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);


$db_botao=1;
$db_opcaoid=1;
$db_opcao=1;
$testasel=false;
$clconstrescr = new cl_constrescr;
$cliptubase = new cl_iptubase;
$clconstrcar = new cl_constrcar;
$clconstrescr->rotulo->label();
$clconstrescr->rotulo->tlabel();

$clrotulo = new rotulocampo;
$clrotulo->label("j14_nome");
$clrotulo->label("z01_nome");


$result = $cliptubase->sql_record($cliptubase->sql_query($j52_matric,"z01_nome",""));
if($cliptubase->numrows==0){
  db_redireciona("cad4_constrescr001.php?invalido=true");
}else{
  db_fieldsmemory($result,0);
}  
       

if(isset($j52_idcons)&&$j52_idcons=="nova"){
   $result = $cliptubase->sql_record($cliptubase->sql_query($j52_matric,"z01_nome",""));
   @db_fieldsmemory($result,0);
   $j52_idcons=""; 
}else if(isset($incluir)){
   db_inicio_transacao();
   if($j52_idcons==0){
     $result = $clconstrescr->sql_record($clconstrescr->sql_query_file($j52_matric,"",'max(j52_idcons) as j52_idcons'));
     if($clconstrescr->numrows>0){
       db_fieldsmemory($result,0);
     }else{ 
       $j52_idcons = 0;
     }
     $j52_idcons = $j52_idcons + 1;
   }      
   $clconstrescr->incluir($j52_matric,$j52_idcons);
   $matriz= split("X",$caracteristica);
   for($i=0;$i<sizeof($matriz);$i++){
     $j53_caract = $matriz[$i];
     if($j53_caract!=""){
       $clconstrcar->incluir($j52_matric,$j52_idcons,$j53_caract);
     }  
   } 
  db_fim_transacao();
  $db_botao=1; 
}else if(isset($alterar)){
  db_inicio_transacao();
  $clconstrcar->j53_matric=$j52_matric;
  $clconstrcar->j53_idcons=$j52_idcons;
  $clconstrcar->excluir();
  $clconstrescr->alterar($j52_matric,$j52_idcons);
  $matriz= split("X",$caracteristica);
  for($i=0;$i<sizeof($matriz);$i++){
    $j53_caract = $matriz[$i];
    if($j53_caract!=""){
      $clconstrcar->incluir($j52_matric,$j52_idcons,$j53_caract);
    }  
  }
  db_fim_transacao();
 $db_botao=2; 
}else if(isset($j52_matric)&&isset($j52_idcons)){
  $result = $clconstrescr->sql_record($clconstrescr->sql_query($j52_matric,$j52_idcons,"*","",""));
  if($clconstrescr->numrows!=0){
    $db_opcaoid=3;
    $db_botao=2;  
    db_fieldsmemory($result,0);
    $result = $clconstrcar->sql_record($clconstrcar->sql_query($j52_matric,$j52_idcons,"","*"));
    $caracteristica = null;
    $car="X";
    for($i=0; $i<$clconstrcar->numrows; $i++){
      db_fieldsmemory($result,$i);
      $caracteristica .= $car.$j53_caract ;
      $car="X";
    }
    $caracteristica .= $car; 
     
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
<script> 	
function js_verificaid(valor){
     num=(document.form1.selid.options.length)-1;
    for(i=1;i<=num;i++){
      selid=document.form1.selid.options[i].value;
      if(valor==selid){
        alert("Construção já cadastrada!");
        document.form1.j52_idcons.value="";
        document.form1.j52_idcons.focus();
        return false;
        break;
      }
   }
   if(document.form1.caracteristica.value=="X" || document.form1.caracteristica.value==""){
     alert("Informe as caracteristicas!");
        return false;
   }

  }
 function js_testacar2(){
   if(document.form1.caracteristica.value=="X" || document.form1.caracteristica.value==""){
     alert("Informe as caracteristicas!");
        return false;
   }
  }

<?if(isset($j52_matric)){?>
  function js_trocaid(valor){
    location.href="cad4_constrescr002.php?j52_matric=<?=$j52_matric?>&j52_idcons="+valor+"&z01_nome="+document.form1.z01_nome.value;
  } 
<?}?>
</script> 	
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
function js_lo2(){
  js_trocacordeselect();
  document.form1.j52_idcons.focus();
  
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_lo2()"  >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table height="430" width="790" border="0" cellspacing="0" cellpadding="0">
<form name="form1" method="post" onSubmit="return js_verifica_campos_digitados();" action="">
  <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
      <center>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td>     
           <?=$Lj52_matric?>
          </td>
          <td> 
          <?
           db_input('j52_matric',5,0,true,'text',3,"onchange='js_matri(false)'");
           db_input('z01_nome',30,0,true,'text',3,"");
          ?>
          </td>
        </tr>
        <tr> 
          <td>     
           <?=$Lj52_idcons?>*
          </td>
          <td> 
<?
  db_input('j52_idcons',5,$Ij52_idcons,true,'text',$db_opcaoid,"");
?>
          </td>
        
          <td rowspan="8" valign="top">
            <table border="0" cellpadding="0" cellspacing="0">
              <tr><td><b>Construções já Cadastradas</b></td></tr> 
              <tr>
                <td align="center">  
<?
if(isset($j52_matric)){


  if(!isset($incluir)){
    $result = $clconstrescr->sql_record($clconstrescr->sql_query_file($j52_matric,"","j52_idcons","",""));
  }
  $num=$clconstrescr->numrows?$num=$clconstrescr->numrows:$num=0;
  if($num!=0){  
    echo "<select name='selid' onchange='js_trocaid(this.value)'  size='".($num>7?8:($num+1))."'>";
    echo "<option value='nova' ".(!isset($j52_idcons)?"selected":"").">Nova</option>"; 
    $idcons=$j52_idcons;  
    $testasel=true;
    for($i=0;$i<$num;$i++){  
      db_fieldsmemory($result,$i);
      if($j52_idcons!=$idcons){
        echo "<option  value='".$j52_idcons."' ".($j52_idcons==$idcons?"selected":"").">$j52_idcons</option>";         
      }
    }
  }
}
?> 	
                </td>
              </tr>
            </table>     
          </td>
        </tr>
        <tr> 
          <td>          
           <?=$Lj52_ano?>
          </td>
          <td> 
<?
  db_input('j52_ano',5,$Ij52_ano,true,'text',1,"");
?>
          </td>
        </tr>
        <tr> 
          <td>     
           <?=$Lj52_area?>
          </td>
          <td> 
<?
  db_input('j52_area',5,$Ij52_area,true,'text',1,"");
?>
          </td>
        </tr>
        <tr> 
          <td>     
           <?=$Lj52_areap?>
          </td>
          <td> 
<?
  db_input('j52_areap',5,$Ij52_areap,true,'text',1,"");
?>
          </td>
        </tr>
        <tr> 
          <td>     
           <?=$Lj52_dtlan?>
          </td>
          <td> 
<?
  db_inputdata('j52_dtlan',@$j52_dtlan_dia,@$j52_dtlan_mes,@$j52_dtlan_ano,true,'text',2,"");
?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tj52_codigo?>">
<?
  db_ancora(@$Lj52_codigo,"js_pesquisaj52_codigo(true);",$db_opcao);
?>
          </td>
          <td> 
<?
  db_input('j52_codigo',5,$Ij52_codigo,true,'text',$db_opcao," onchange='js_pesquisaj52_codigo(false);'");
  db_input('j14_nome',40,$Ij14_nome,true,'text',3,'');
?>
          <td>
        <tr>
        <tr> 
          <td>     
           <?=$Lj52_numero?>
          </td>
          <td> 
<?
  db_input('j52_numero',5,$Ij52_numero,true,'text',1,"");
?>
          </td>
        </tr>
        <tr> 
          <td>     
           <?=$Lj52_compl?>
          </td>
          <td> 
<?
  db_input('j52_compl',5,$Ij52_compl,true,'text',1,"");
?>
          </td>
        </tr>
        <tr>
          <td>
            <b>
<?
  db_ancora("Características","js_mostracaracteristica();",1);
?>
            </b> 
          </td>
          <td> 
<?

  db_input('caracteristica',15,1,true,'hidden',1,"")
?>
          <td>
        </tr>
	<tr>
	  <td colspan="2" align="center">
	  <br>
<input name="<?=($db_botao==1?"incluir":"alterar")?>" type="submit" value="<?=($db_botao==1?"Incluir":"Alterar")?>" <?=($testasel==true?"onclick=\"return js_verificaid(document.form1.j52_idcons.value)\"":"onclick=\"return js_testacar2()\"")?>>
<input type="button" name="voltar" value="Voltar" onclick="js_volta();">
          </td>
        </tr>
	<tr>
	  <td colspan="2" align="left">
	  <br><br>
	    *(caso o campo não seja preenchido, o código será gerado automaticamente)
          </td>
        </tr>
      </table>
      </center>
    </td>
  </tr>
</form>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_volta(){
  location.href = 'cad4_constrescr001.php';
}
function js_matri(mostra){
  var matri=document.form1.j52_matric.value;
  if(mostra==true){
    db_iframe.jan.location.href = 'func_iptubase.php?funcao_js=parent.js_mostra|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_iptubase.php?pesquisa_chave='+matri+'&funcao_js=parent.js_mostra1';
  }
}
function js_mostra(chave1,chave2){
  document.form1.j52_matric.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
function js_mostra1(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.j52_matric.focus(); 
    document.form1.j52_matric.value = ''; 
  }
}

function js_mostracaracteristica(){
  caracteristica=document.form1.caracteristica.value;
   if(caracteristica!=""){
     db_iframe.jan.location.href = 'cad1_cargeral001.php?db_opcao=<?=$db_opcao?>&caracteristica='+caracteristica+'&tipogrupo=C';
   }else{
    db_iframe.jan.location.href = 'cad1_cargeral001.php?db_opcao=<?=$db_opcao?>&tipogrupo=C&codigo='+document.form1.j52_idcons.value;
   }
    db_iframe.setTitulo('Pesquisa Caracteristica');
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
}
function js_pesquisaj52_codigo(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_ruas.php?funcao_js=parent.js_mostraruas1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_ruas.php?pesquisa_chave='+document.form1.j52_codigo.value+'&funcao_js=parent.js_mostraruas';
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.j52_codigo.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe.hide();
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave; 
  if(erro==true){ 
    document.form1.j52_codigo.focus(); 
    document.form1.j52_codigo.value = ''; 
  }
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

if(isset($incluir)||(isset($alterar))){
  if($clconstrescr->erro_status=="0"){
    $clconstrescr->erro(true,false);
    if($clconstrescr->erro_campo!=""){
      echo "<script> document.form1.".$clconstrescr->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clconstrescr->erro_campo.".focus();</script>";
    }
  }else{
     $clconstrescr->erro(true,false);
      db_redireciona("cad4_constrescr002.php?j52_matric=$j52_matric&j52_idcons=nova");

  }
}
?>