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
include("classes/db_propri_classe.php");
include("classes/db_iptubase_classe.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$db_botao=1;
$db_opcao=1;
$outros=false; 
$cliptubase = new cl_iptubase;
$cliptubase->rotulo->label();
$clpropri = new cl_propri;
$clpropri->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j01_numcgm");
$rotulocampo = new rotulocampo;
$rotulocampo->label("z01_nome");  
if(isset($atualizar)){
  db_redireciona("cad4_propri002.php?j42_matric=$j42_matric" );
}
if(isset($incluir)){
   db_inicio_transacao();
   $result03=$cliptubase->sql_record($cliptubase->sql_query_file($j42_matric,"j01_numcgm"));
   db_fieldsmemory($result03,0);
   if($j01_numcgm!=$j42_numcgm){
     $clpropri->incluir($j42_matric,$j42_numcgm);
   }else{
     $mesmocgm="ok";
   }  
   db_fim_transacao();
   $j42_numcgm="";
   $z01_nome="";
   $outros = true; 
}else{
  if(isset($excluir)){
     $clpropri->excluir($j42_matric,$j42_numcgm);
     $j42_numcgm="";
     $z01_nome="";
  }else{ 
      if(isset($j42_matric)){  
        if(isset($j42_matric) && isset($j42_numcgm)){
           $result = $clpropri->sql_record($clpropri->sql_query($j42_matric,$j42_numcgm,"propri.*#cgm.z01_nome#a.z01_nome as z01_nomematri"));
           db_fieldsmemory($result,0);
           $result = $clpropri->sql_record($clpropri->sql_query($j42_matric,"","propri.*#cgm.z01_nome"));
           $j42_numalt=$j42_numcgm; 
           if($clpropri->numrows > 1){ 
             $outros=true; 
           }else{
             $outros = false;
           } 
        }else{
          $result = $clpropri->sql_record($clpropri->sql_query($j42_matric,"","a.z01_nome as z01_nomematri"));
          @db_fieldsmemory($result,0);
          if($clpropri->numrows!=0){
            $db_opcao=2;
            $outros=true; 
          }else{
            $result = $cliptubase->sql_record($cliptubase->sql_query($j42_matric,"z01_nome as z01_nomematri",""));
            @db_fieldsmemory($result,0);
            if($cliptubase->numrows==0){
              db_redireciona("cad4_propri001.php?invalido=true");
            }else{
             $db_opcao=1;
            } 
          }
        }  
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
function js_trocaid(valor){
  location.href="cad4_propri002.php?j42_matric=<?=$j42_matric?>&j42_numcgm="+valor;

}
function js_lo4(){
  document.form1.j42_numcgm.focus();
  js_trocacordeselect();
}

</script>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_lo4()">
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
    <table border="0">
    <tr>
      <td nowrap title="<?=@$Tj42_matric?>">
        <?=@$Lj42_matric?>
      </td>
      <td> 
<?
  db_input('j42_matric',4,$Ij42_matric,true,'text',3," onchange='js_pesquisaj42_matric(false);'");
  db_input('z01_nome',45,$Ij01_numcgm,true,'text',3,'','z01_nomematri');
?>
      </td>
    </tr>
    <tr> 
      <td nowrap title="<?=@$Tj42_numcgm?>">
<?
  db_ancora($Lj42_numcgm,' js_cgm(true); ',(isset($j42_numalt)?3:1));
?>
      </td>
      <td> 
<?
  db_input('j42_numcgm',4,$Ij42_numcgm,true,'text',(isset($j42_numalt)?3:1),"onchange='js_cgm(false)'");
  db_input('z01_nome',45,$Iz01_nome,true,'text',3,"");
?>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <center>
        <input name="incluir" type="submit" value="Incluir" <?=(!isset($j42_numalt)?"":"disabled")?> >
        <input name="excluir" type="submit" id="excluir" value="Excluir" <?=(isset($j42_numalt)?"":"disabled")?> >               
        <input name="atualizar" type="submit" id="atualizar" value="Atualizar" <?=(isset($j42_numalt)?"":"disabled")?> >               
        <input name="voltar" type="button" id="voltar" value="Voltar" onclick="js_volta();">               
        </center>
      </td>  
    </tr>  
    <tr>
      <td colspan="2">
      <center>
<?
  if($outros==true){  
    $result = $clpropri->sql_record($clpropri->sql_query($j42_matric,"","propri.*#cgm.z01_nome"));
    $num = $clpropri->numrows;
    echo "<select name='lista' onchange='js_trocaid(this.value)' size='".($num>10?10:($num)+1)."'>";
    for($i=0;$i<$num;$i++){  
      db_fieldsmemory($result,$i);
      if($j42_numcgm!=$j42_numalt){  
        echo "<option  value='".$j42_numcgm."'>$z01_nome</option>";         
      }
    }
    echo "</select>"; 
  }  
?>
      </center>
      </td>
    </tr>  
    </table>
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
  location.href = 'cad4_propri001.php';
}
function js_pesquisaj42_matric(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_iptubase.php?funcao_js=parent.js_mostraiptubase1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_iptubase.php?pesquisa_chave='+document.form1.j42_matric.value+'&funcao_js=parent.js_mostraiptubase';
  }
}
function js_mostraiptubase(chave,erro){
  document.form1.j01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.j42_matric.focus(); 
    document.form1.j42_matric.value = ''; 
  }
}
function js_mostraiptubase1(chave1,chave2){
  document.form1.j42_matric.value = chave1;
  document.form1.j01_numcgm.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_propri.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
function js_cgm(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_nome.php?funcao_js=parent.js_mostra1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_nome.php?pesquisa_chave='+document.form1.j42_numcgm.value+'&funcao_js=parent.js_mostra';
  }
}
function js_mostra1(chave1,chave2){
  document.form1.j42_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
function js_mostra(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.j42_numcgm.focus();
    document.form1.j42_numcgm.value="";
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
if(isset($excluir)){
  $clpropri->erro(true,false);
  db_redireciona("cad4_propri002.php?j42_matric=$j42_matric" );
}
if(isset($incluir)){
  if(isset($mesmocgm)){
    db_msgbox("Não é possível incluir o proprietário principal da matrícula.");  
  }else{
    if($clpropri->erro_status=="0"){
      $clpropri->erro(true,false);
      if($clpropri->erro_campo!=""){
        echo "<script> document.form1.".$clpropri->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clpropri->erro_campo.".focus();</script>";
      }
    }else{
      $clpropri->erro(true,false);
    }
  }  
}
?>