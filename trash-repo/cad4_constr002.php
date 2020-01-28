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
include("classes/db_iptuconstr_classe.php");
include("classes/db_carconstr_classe.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$db_botao=1;
$db_opcaoid=1;
$cliptuconstr = new cl_iptuconstr;
$cliptubase = new cl_iptubase;
$clcarconstr = new cl_carconstr;
$cliptuconstr->rotulo->label();
$cliptuconstr->rotulo->tlabel();

$clrotulo = new rotulocampo;
$clrotulo->label("j14_nome");
$clrotulo->label("z01_nome");

if(isset($incluir)){
   db_inicio_transacao();
   if($j39_idcons==0){
     $result = $cliptuconstr->sql_record($cliptuconstr->sql_query_file($j39_matric,"",'max(j39_idcons) as j39_idcons'));
     if($cliptuconstr->numrows>0){
       db_fieldsmemory($result,0);
     }else{ 
       $j39_idcons = 0;
     }
     $j39_idcons = $j39_idcons + 1;
   }      
   $cliptuconstr->incluir($j39_matric,$j39_idcons);
   $matriz= split("X",$caracteristica);
   for($i=0;$i<sizeof($matriz);$i++){
     $j48_caract = $matriz[$i];
     if($j48_caract!=""){
       $clcarconstr->incluir($j39_matric,$j39_idcons,$j48_caract);
     }  
   }
  db_fim_transacao();
  $db_botao=1; 
}else{
  if(isset($alterar)){
    db_inicio_transacao();
    $clcarconstr->j48_matric=$j39_matric;
    $clcarconstr->j48_idcons=$j39_idcons;
    $clcarconstr->excluir();

    $cliptuconstr->alterar($j39_matric,$j39_idcons);
    $matriz= split("X",$caracteristica);
    for($i=0;$i<sizeof($matriz);$i++){
      $j48_caract = $matriz[$i];
      if($j48_caract!=""){
        $clcarconstr->incluir($j39_matric,$j39_idcons,$j48_caract);
      }  
    }
    db_fim_transacao();
    $db_botao=2; 
    
  }else{ 
    if(isset($j39_idcons)&&$j39_idcons!=""){
      $result = $cliptuconstr->sql_record($cliptuconstr->sql_query($j39_matric,$j39_idcons,"*","",""));
      if($cliptuconstr->numrows!=0){
        $db_opcaoid=3;
        $db_botao=2;  
        db_fieldsmemory($result,0);
        $result = $clcarconstr->sql_record($clcarconstr->sql_query($j39_matric,$j39_idcons,"","*"));
        $caracteristica = null;
        $car="X";
        for($i=0; $i<$clcarconstr->numrows; $i++){
          db_fieldsmemory($result,$i);
          $caracteristica .= $car.$j48_caract ;
          $car="X";
        }
        $caracteristica .= $car; 
      }else{
          $j39_idcons="";   
      }
    }else{
      if(isset($j39_matric)){  
        $result = $cliptuconstr->sql_record($cliptuconstr->sql_query($j39_matric,"","j39_matric"));
        @db_fieldsmemory($result,0);
        if($cliptuconstr->numrows==0){
          db_redireciona("cad4_constr001.php?invalido=true");
        }
      }  
    }   
  }
}
$db_opcao=1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script> 	
  function js_trocaid(valor){
   location.href="cad4_constr002.php?permi=true&j39_matric=<?=$j39_matric?>&j39_idcons="+valor;
  }
  function js_verificaid(valor){
     num=(document.form1.selid.options.length)-1;   
    for(i=1;i<=num;i++){
      selid=document.form1.selid.options[i].value;   
      if(valor==selid){ 
        alert("Construção já cadastrada!");
        document.form1.j39_idcons.value="";
        document.form1.j39_idcons.focus();
        return false;  
        break;   
      }  
   }

  }
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


</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect()"  >
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
    <td align="left" valign="center" bgcolor="#CCCCCC">
      <center>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td>          
           <?=$Lj39_matric?>
          </td>
          <td> 
<?
  $result = $cliptubase->sql_record($cliptubase->sql_query($j39_matric,"z01_nome"));
  db_fieldsmemory($result,0);
  db_input('j39_matric',5,$Ij39_matric,true,'text',3,"");
  db_input('z01_nome',40,$Iz01_nome,true,'text',3,"");
?>
          </td>
        </tr>
        <tr> 
          <td>     
           <?=$Lj39_idcons?>
          </td>
          <td> 
<?
  db_input('j39_idcons',5,$Ij39_idcons,true,'text',$db_opcaoid,"");
?>
          </td>
        
          <td rowspan="8" valign="top">
            <table border="0" cellpadding="0" cellspacing="0">
              <tr><td><b>Construções já Cadastradas</b></td></tr> 
              <tr>
                <td align="center">  
<?
  $result = $cliptuconstr->sql_record($cliptuconstr->sql_query_file($j39_matric,"","j39_idcons","",""));
  $num=$cliptuconstr->numrows;
  if($num!=""){  
    echo "<select name='selid' onchange='js_trocaid(this.value)'  size='".($num>7?8:($num+1))."'>";
    echo "<option value='nova' ".(!isset($j39_idcons)?"selected":"").">Nova</option>"; 
    $idcons=$j39_idcons;  
    for($i=0;$i<$num;$i++){  
      db_fieldsmemory($result,$i);
      echo "<option  value='".$j39_idcons."' ".($j39_idcons==$idcons?"selected":"").">$j39_idcons</option>";         
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
           <?=$Lj39_ano?>
          </td>
          <td> 
<?
  db_input('j39_ano',5,$Ij39_ano,true,'text',1,"");
?>
          </td>
        </tr>
        <tr> 
          <td>     
           <?=$Lj39_area?>
          </td>
          <td> 
<?
  db_input('j39_area',5,$Ij39_area,true,'text',1,"");
?>
          </td>
        </tr>
        <tr> 
          <td>     
           <?=$Lj39_areap?>
          </td>
          <td> 
<?
  db_input('j39_areap',5,$Ij39_areap,true,'text',1,"");
?>
          </td>
        </tr>
        <tr> 
          <td>     
           <?=$Lj39_dtlan?>
          </td>
          <td> 
<?
  db_inputdata('j39_dtlan',@$j39_dtlan_dia,@$j39_dtlan_mes,@$j39_dtlan_ano,true,'text',2,"");
?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tj39_codigo?>">
<?
  db_ancora(@$Lj39_codigo,"js_pesquisaj39_codigo(true);",$db_opcao);
?>
          </td>
          <td> 
<?
  db_input('j39_codigo',5,$Ij39_codigo,true,'text',$db_opcao," onchange='js_pesquisaj39_codigo(false);'");
  db_input('j14_nome',40,$Ij14_nome,true,'text',3,'');
?>
          <td>
        <tr>
        <tr> 
          <td>     
           <?=$Lj39_numero?>
          </td>
          <td> 
<?
  db_input('j39_numero',5,$Ij39_numero,true,'text',1,"");
?>
          </td>
        </tr>
        <tr> 
          <td>     
           <?=$Lj39_compl?>
          </td>
          <td> 
<?
  db_input('j39_compl',5,$Ij39_compl,true,'text',1,"");
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
      </table>
      <input name="<?=($db_botao==1?"incluir":"alterar")?>" type="submit" value="<?=($db_botao==1?"Incluir":"Alterar")?>" <?=($db_botao==1?"onclick=\"return js_verificaid(document.form1.j39_idcons.value)\"":"")?>>
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
function js_mostracaracteristica(){
  caracteristica=document.form1.caracteristica.value;
   if(caracteristica!=""){
     db_iframe.jan.location.href = 'cad1_cargeral001.php?db_opcao=<?=$db_opcao?>&caracteristica='+caracteristica+'&tipogrupo=C';
   }else{
    db_iframe.jan.location.href = 'cad1_cargeral001.php?db_opcao=<?=$db_opcao?>&tipogrupo=C&codigo='+document.form1.j39_idcons.value;
   }
    db_iframe.setTitulo('Pesquisa Caracteristica');
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
}
function js_pesquisaj39_codigo(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_ruas.php?funcao_js=parent.js_mostraruas1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_ruas.php?pesquisa_chave='+document.form1.j39_codigo.value+'&funcao_js=parent.js_mostraruas';
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.j39_codigo.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe.hide();
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave; 
  if(erro==true){ 
    document.form1.j39_codigo.focus(); 
    document.form1.j39_codigo.value = ''; 
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
  if($cliptuconstr->erro_status=="0"){
    $cliptuconstr->erro(true,false);
    if($cliptuconstr->erro_campo!=""){
      echo "<script> document.form1.".$cliptuconstr->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cliptuconstr->erro_campo.".focus();</script>";
    }
  }else{
     $cliptuconstr->erro(true,false);
     db_redireciona("cad4_constr002.php?j39_matric=$j39_matric");
  }
}
?>