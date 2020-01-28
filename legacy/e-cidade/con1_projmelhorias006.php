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
include("classes/db_projmelhorias_classe.php");
include("classes/db_projmelhoriasresp_classe.php");
include("classes/db_projmelhoriasmatric_classe.php");
include("classes/db_testada_classe.php");
include("dbforms/db_funcoes.php");
$clprojmelhorias = new cl_projmelhorias;
$clprojmelhoriasresp = new cl_projmelhoriasresp;
$clprojmelhoriasmatric = new cl_projmelhoriasmatric;
$db_opcao = 33;
$db_botao = true;

if(isset($confirma)){
  db_postmemory($HTTP_POST_VARS);
  db_inicio_transacao();
  $sqlerro = false;

  $resultt = $clprojmelhoriasmatric->sql_record($clprojmelhoriasmatric->sql_query($d40_codigo,'','d41_matric'));
  if($resultt!=false && $clprojmelhoriasmatric->numrows>0){
    $numrows = $clprojmelhoriasmatric->numrows;
    for($ii=0;$ii<$numrows;$ii++){
      db_fieldsmemory($resultt,$ii);
      $clprojmelhoriasmatric->d41_codigo = $d40_codigo;
      $clprojmelhoriasmatric->d41_matric = $d41_matric;
      $clprojmelhoriasmatric->excluir($d40_codigo,$d41_matric);
      if($clprojmelhoriasmatric->erro_status=='0'){
        $sqlerro = true;
      }
    }
  }
  $clprojmelhoriasresp->d42_codigo=$d40_codigo;
  $clprojmelhoriasresp->d42_numcgm=$d42_numcgm;
  $r = $clprojmelhoriasresp->sql_record($clprojmelhoriasresp->sql_query($d40_codigo));
  if($clprojmelhoriasresp->numrows>0){
    $clprojmelhoriasresp->excluir($d40_codigo);
    if($clprojmelhoriasresp->erro_status=='0'){
       $sqlerro = true;
     } 
  }  
  $result = $clprojmelhorias->excluir($d40_codigo);
  if($clprojmelhorias->erro_status=='0'){
    $sqlerro = true;
  } 
  db_fim_transacao($sqlerro);
  unset($chavepesquisa);
}

if(isset($chavepesquisa)){
  $result = $clprojmelhorias->sql_record($clprojmelhorias->sql_query($chavepesquisa));
  db_fieldsmemory($result,0);
  
  $r = $clprojmelhoriasresp->sql_record($clprojmelhoriasresp->sql_query($chavepesquisa,"d42_numcgm,z01_nome"));
  if($clprojmelhoriasresp->numrows>0){
    db_fieldsmemory($r,0);
  }
  $db_opcao=3;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>

<?
//MODULO: contrib
$clprojmelhorias->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("j14_nome");
$clrotulo->label("z01_nome");
$clrotulo->label("d42_numcgm");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td>
    <input name="testada" type="hidden">
    <input name="obs" type="hidden">
    <input name="eixo" type="hidden">
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td40_codigo?>">
       <?=@$Ld40_codigo?>
    </td>
    <td> 
<?
db_input('d40_codigo',10,$Id40_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td40_codlog?>">
       <?=$Ld40_codlog?>
    </td>
    <td> 
<?
db_input('d40_codlog',7,$Id40_codlog,true,'text',$db_opcao)
?>
       <?
db_input('j14_nome',40,$Ij14_nome,true,'text',$db_opcao,'')
       ?>
    </td>
  </tr>
    <tr>
    <td nowrap title="<?=@$Td42_numcgm?>">
       <?=$Ld42_numcgm?>
    </td>
    <td> 
<?
db_input('d42_numcgm',6,$Id42_numcgm,true,'text',$db_opcao)
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td40_profun?>">
       <?=@$Ld40_profun?>
    </td>
    <td> 
<?
db_input('d40_profun',6,$Id40_profun,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td40_trecho?>">
       <?=@$Ld40_trecho?>
    </td>
    <td> 
<?
db_input('d40_trecho',60,$Id40_trecho,true,'text',3,"")
?>
    </td>
  </tr>
  </table>
  <table>
  <tr>
  <td colspan="2">
  <iframe name="matriculas" id="matriculas" src="" width="750" height="270" disabled="true"></iframe>
  
  </td>
  </tr>
  
  </table>
  <input name="pesquisa" type="button" onclick="js_pesquisa();" id="pesquisa" value="Pesquisa Listas" >
  <input name="confirma" type="submit" style='visibility:hidden'  id="confirma" value="Excluir lista" >
  </center>
</form>
<script>
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_projmelhorias.php?funcao_js=parent.js_preenchepesquisa|0';
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
?>


	
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if($clprojmelhorias->erro_status=="0"){
  $clprojmelhorias->erro(true,false);
  $db_botao=true;
  if($clprojmelhorias->erro_campo!=""){
    echo "<script> document.form1.".$clprojmelhorias->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clprojmelhorias->erro_campo.".focus();</script>";
  };
}else{
  $clprojmelhorias->erro(true,true);
};

if(isset($chavepesquisa)){
 echo "<script>
       document.getElementById('matriculas').src = 'con1_projmelhorias004.php?disab=true&codproj=$chavepesquisa&d40_codlog=$d40_codlog';
       document.form1.confirma.style.visibility='visible';
       </script>";
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisa.click();</script>";
}
?>