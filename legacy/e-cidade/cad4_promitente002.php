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
include("classes/db_promitente_classe.php");
include("classes/db_iptubase_classe.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$db_botao=1;
$db_opcao=1;
$cliptubase = new cl_iptubase;
$cliptubase->rotulo->label();
$clpromitente = new cl_promitente;
$clpromitente->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j01_numcgm");
$rotulocampo = new rotulocampo;
$rotulocampo->label("z01_nome");  

$outros = false;


   
if(isset($incluir)){
   db_inicio_transacao();
   $verifica=false;
   $result = $clpromitente->sql_record($clpromitente->sql_query($j41_matric,"","j41_tipopro as tipopro#j41_promitipo as promitipo","",""));
   $num=$clpromitente->numrows;
   if($num!=0){  
     pg_exec("update promitente set j41_promitipo= '$j41_promitipo' where j41_matric='$j41_matric'"); 

     $num=$clpromitente->numrows;
     if($num!=0){  
       for($i=0;$i<$num;$i++){
           db_fieldsmemory($result,$i);
  	   if($tipopro=="t" && $j41_tipopro=="t"){
             pg_exec("update promitente set j41_tipopro= false"); 
    	     $verifica="true";
   	     break;
           }  
       }  
     }
     if($verifica=="false"){
       echo $verifica;
       $clpromitente->j41_tipopro="t";  
     }
   }else{
       $clpromitente->j41_tipopro="t";  
   }  
   $clpromitente->j41_tipopro=$j41_tipopro;
   $clpromitente->j41_promitipo=$j41_promitipo;  
   $clpromitente->incluir($j41_matric,$j41_numcgm);
   db_fim_transacao();
   $result = $clpromitente->sql_record($clpromitente->sql_query($j41_matric,"","cgm.z01_nome"));
   $numcgm=$j41_numcgm;
   if($clpromitente->numrows > 1){
     $outros=true;
     $recol="ok";
   }else{
     $outros = false;
   }
}else if(isset($alterar)){
   db_inicio_transacao();
   $verifica="false";
   $result = $clpromitente->sql_record($clpromitente->sql_query($j41_matric,"","j41_tipopro as tipopro#j41_promitipo as promitipo","",""));
   
   pg_exec("update promitente set j41_promitipo= '$j41_promitipo' where j41_matric='$j41_matric'"); 
   
   $num=$clpromitente->numrows;
   if($num!=0){  
       for($i=0;$i<$num;$i++){
	   db_fieldsmemory($result,$i);
	   if($tipopro=="t" && $j41_tipopro=="t"){
	     pg_exec("update promitente set j41_tipopro= false"); 
	     $verifica="true";
	     break;
	   }  
       }  
     }
     if($verifica=="false"){
       $clpromitente->j41_tipopro="t";  
   }

   
   $clpromitente->j41_tipopro=$j41_tipopro;
   $clpromitente->j41_promitipo=$j41_promitipo;  
   $clpromitente->alterar($j41_matric,$j41_numcgm);




   db_fim_transacao();
}else if(isset($excluir)){
   $clpromitente->excluir($j41_matric);
   if($clpromitente->erro_status==0){
     $clpromitente->erro(true,false);
     db_redireciona("cad4_promitente002.php");
   }
}else if(isset($j41_matric) && isset($j41_numcgm)){  
 
   $result = $clpromitente->sql_record($clpromitente->sql_query($j41_matric,"","promitente.*#cgm.z01_nome#a.z01_nome as z01_nomematri","","j41_numcgm=$j41_numcgm and j41_matric = $j41_matric "));
   db_fieldsmemory($result,0);
   $db_opcao=2;
   $result = $clpromitente->sql_record($clpromitente->sql_query($j41_matric,"","cgm.z01_nome"));
   $numcgm=$j41_numcgm;
   if($clpromitente->numrows > 0){
     $outros=true;
     $recol="ok";
   }else{
     $outros = false;
   }
}else if(isset($j41_matric) && !isset($j41_numcgm)){  
   $result = $clpromitente->sql_record($clpromitente->sql_query($j41_matric,"","j41_promitipo#j41_tipopro#a.z01_nome as z01_nomematri","",""));
   if($clpromitente->numrows!=0){
      @db_fieldsmemory($result,0);
	$db_opcao=1;
      $outros=true;
   }else{
      $result = $cliptubase->sql_record($cliptubase->sql_query($j41_matric,"z01_nome as z01_nomematri",""));
      @db_fieldsmemory($result,0);
      if($cliptubase->numrows==0){
        db_redireciona("cad4_promitente001.php?invalido=true");
      }else{
        $db_opcao=1;
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
    location.href="cad4_promitente002.php?j41_matric=<?=$j41_matric?>&j41_numcgm="+valor;

}
function js_lo5(){
  document.form1.j41_numcgm.focus();
  js_trocacordeselect();
}

</script>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_lo5()">
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
  <tr height="50%">
    <td align="left" valign="bottom" bgcolor="#CCCCCC">
      <center>
      <table border="0">
      <tr>
        <td nowrap title="<?=@$Tj41_matric?>">
        <?=@$Lj41_matric?>
        </td>
        <td> 
<?
  db_input('j41_matric',4,$Ij41_matric,true,'text',3," onchange='js_pesquisaj41_matric(false);'");
  db_input("z01_nome",45,$Ij01_numcgm,true,"text",3,"","z01_nomematri");
?>
        </td>
      </tr>
      <tr> 
        <td nowrap title="<?=@$Tj41_numcgm?>">
<?
  db_ancora($Lj41_numcgm,' js_cgm(true); ',1);
?>
        </td>
        <td> 
<?
  db_input('j41_numcgm',4,$Ij41_numcgm,true,'text',$db_opcao,"onchange='js_cgm(false)'");
  db_input('z01_nome',45,$Iz01_nome,true,'text',3,"");
?>
        </td>
      </tr>
      <tr>
       <td nowrap title="<?=@$Tj41_promitipo?>">
       <?=@$Lj41_promitipo?>
       </td>
       <td> 
<?
$x = array("C"=>"Com contrato","S"=>"Sem contrato");
db_select('j41_promitipo',$x,true,$db_opcao,"");
?>
       </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj41_tipopro?>">
          <?=@$Lj41_tipopro?>
        </td>
        <td> 
<?
$xy = array("f"=>"Secundário","t"=>"Principal");
db_select('j41_tipopro',$xy,true,$db_opcao,"");
?>
        </td>
      </tr>
      </table>
      <br>
      <input name="incluir" type="submit" id="incluir" value="Incluir" <?=($db_opcao!=1?"disabled":"")?> >
      <input name="alterar" type="submit" id="alterar" value="Alterar" <?=($db_opcao==1?"disabled":"")?> >
      <input name="excluir" type="submit" id="excluir" value="Excluir" <?=($db_opcao==1?"disabled":"")?> >
      <input name="voltar" type="button" id="voltar" value="Voltar" onclick="js_volta();" >
      </center>
    </td>
  </tr>
  <tr height="50%">
        <td valign="top" bgcolor="#CCCCCC" colspan="2">
      <br>
         <center>
<?
 if($outros==true){
   $result = $clpromitente->sql_record($clpromitente->sql_query($j41_matric,"","promitente.*#cgm.z01_nome"));
   $num = $clpromitente->numrows;
     echo "<select name='lista' onchange='js_trocaid(this.value)' size='".($num>10?10:($num)+1)."'>";
   for($i=0;$i<$num;$i++){
     db_fieldsmemory($result,$i);
     if($j41_numcgm!=$numcgm){
       echo "<option  value='".$j41_numcgm."'>$z01_nome</option>";
     }
   }
   if(!isset($recol)){
     echo "</select>";
     echo "<script>";  
     echo "  function js_prime(){"; 
     echo "    document.form1.j41_tipopro.options[0].selected=true;";
     echo "  }";	
     echo "  js_prime();";
     echo "</script>";
   }  
 }else{
   echo "<script>";  
   echo "  function js_prime(){"; 
   echo "    document.form1.j41_tipopro.options[1].selected=true;";
   echo "  }";	
   echo "  js_prime();";
   echo "</script>";
 }
?>
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
  location.href = 'cad4_promitente001.php';
}
function js_pesquisaj41_matric(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_iptubase.php?funcao_js=parent.js_mostraiptubase1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_iptubase.php?pesquisa_chave='+document.form1.j41_matric.value+'&funcao_js=parent.js_mostraiptubase';
  }
}
function js_mostraiptubase(chave,erro){
  document.form1.j01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.j41_matric.focus(); 
    document.form1.j41_matric.value = ''; 
  }
}
function js_mostraiptubase1(chave1,chave2){
  document.form1.j41_matric.value = chave1;
  document.form1.j01_numcgm.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_promitente.php?funcao_js=parent.js_preenchepesquisa|0';
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
    db_iframe.jan.location.href = 'func_nome.php?pesquisa_chave='+document.form1.j41_numcgm.value+'&funcao_js=parent.js_mostra';
  }
}
function js_mostra1(chave1,chave2){
  document.form1.j41_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
function js_mostra(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.j41_numcgm.focus();
    document.form1.j41_numcgm.value="";
  }
}
function js_pesquisaj41_matric(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_iptubase.php?funcao_js=parent.js_mostraiptubase1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_iptubase.php?pesquisa_chave='+document.form1.j41_matric.value+'&funcao_js=parent.js_mostraiptubase';
  }
}
function js_mostraiptubase(chave,erro){
  document.form1.j01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.j41_matric.focus(); 
    document.form1.j41_matric.value = ''; 
  }
}
function js_mostraiptubase1(chave1,chave2){
  document.form1.j41_matric.value = chave1;
  document.form1.j01_numcgm.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_promitente.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
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
if(isset($incluir)||isset($alterar)||isset($excluir)){
  if($clpromitente->erro_status=="0"){
    $clpromitente->erro(true,false);
    if($clpromitente->erro_campo!=""){
      echo "<script> document.form1.".$clpromitente->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpromitente->erro_campo.".focus();</script>";
    }
  }else{
    $clpromitente->erro(true,false);
    db_redireciona("cad4_promitente002.php?j41_matric=$j41_matric");
  }
}
?>