<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_empagegera_classe.php");
include("classes/db_empageconfgera_classe.php");
include("classes/db_empageconf_classe.php");
include("classes/db_empagetipo_classe.php");
db_postmemory($HTTP_POST_VARS);
$clempagegera = new cl_empagegera;
$clempageconfgera = new cl_empageconfgera;
$clempageconf = new cl_empageconf;
$clempagetipo = new cl_empagetipo;
$clrotulo = new rotulocampo;
$clempagegera->rotulo->label();
$clempagetipo->rotulo->label();

if(isset($cancelar)){
  $sqlerro = false;
  $arr_movs = split(',',$movimentos);
  db_inicio_transacao();
  for($i=0;$i<sizeof($arr_movs);$i++){
    $codmov = $arr_movs[$i];
    if($sqlerro==false){
      $clempageconfgera->e90_codmov  = $codmov;
      $clempageconfgera->e90_codgera = $e87_codgera;
      $clempageconfgera->e90_correto = "false";
      $clempageconfgera->alterar($codmov,$e87_codgera);
      $erro_msg = $clempageconfgera->erro_msg;;
      if($clempageconfgera->erro_status==0){
	$sqlerro = true;
	break;
      }
    }
    if($sqlerro==false){
      $clempageconf->e86_codmov  = $codmov;
      $clempageconf->e86_correto = "false";
      $clempageconf->alterar($codmov);
      $erro_msg = $clempageconf->erro_msg;;
      if($clempageconf->erro_status==0){
	$sqlerro = true;
	break;
      }
    }
  }
 // $sqlerro = true;
  db_fim_transacao($sqlerro);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.e87_codgera.focus();" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <center>
    <form name="form1" method="post">
      <table border='0'>
	<tr height="20px">
	  <td ></td>
	  <td ></td>
	</tr>
	<tr> 
	  <td align="right" nowrap title="<?=$Te87_codgera?>" width='45%'> <? db_ancora(@$Le87_codgera,"js_pesquisa_gera(true);",1);?>  </td>
	  <td align="left" nowrap>
	  <?
	   db_input("e87_codgera",8,$Ie87_codgera,true,"text",4,"onchange='js_pesquisa_gera(false);'"); 
	   db_input("e87_descgera",40,$Ie87_descgera,true,"text",3);
	  ?>
	  </td>
	</tr>
        <?
	$desabilita = " disabled ";
	if(isset($e87_codgera) && trim($e87_codgera)!=""){
	  db_input("movimentos",10,"",true,"hidden",3);
	  echo "
	  <tr> 
	    <td align='center' nowrap colspan='2'>
	      <iframe name='anular' src='emp4_anularq001_iframe.php?lCancelado=0&codgera=".(@$e87_codgera)."' width='760' height='320' marginwidth='0' marginheight='0' frameborder='0'></iframe>
	    </td>
	  </tr>
	  ";
          $desabilita = " ";
	}
        ?>
	<tr>
	  <td colspan="2" align="center">
	    <input name="cancelar" type="button" value="Cancelar arquivo" <?=($desabilita)?> onclick='js_enviarmovimentos();'>
	  </td>
	</tr>
      </table>
    </form>
  </center>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
//--------------------------------
function js_enviarmovimentos(){
  document.form1.movimentos.value = "";
  obj = anular.document.form1;
  movimentos = "";
  virgula = "";
  for(i=0;i<obj.length;i++){
    if(obj[i].type=="checkbox"){
      if(obj[i].checked==true){
	movimentos += virgula + obj[i].value;
	virgula = ",";
      }
    }
  }
  if(movimentos==""){
    alert("Selecione algum movimento para cancelar.");
  }else{
    document.form1.movimentos.value = movimentos;
    obj=document.createElement('input');
    obj.setAttribute('name','cancelar');
    obj.setAttribute('type','hidden');
    obj.setAttribute('value','cancelar');
    document.form1.appendChild(obj);
    document.form1.submit();
  }
}
function js_pesquisa_gera(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empagegera','func_empagegera.php?funcao_js=parent.js_mostragera1|e87_codgera|e87_descgera&processado=false','Pesquisa',true);
  }else{
     if(document.form1.e87_codgera.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_empagegera','func_empagegera.php?pesquisa_chave='+document.form1.e87_codgera.value+'&funcao_js=parent.js_mostragera&processado=false','Pesquisa',false);
     }else{
       document.form1.e87_descgera.value = ''; 
       document.form1.submit();
     }
  }
}
function js_mostragera(chave,erro){
  if(erro==true){ 
    document.form1.e87_codgera.focus(); 
    document.form1.e87_codgera.value = ''; 
  }
  document.form1.e87_descgera.value = chave; 
  document.form1.submit();
}
function js_mostragera1(chave1,chave2){
  document.form1.e87_codgera.value = chave1;
  document.form1.e87_descgera.value = chave2;
  db_iframe_empagegera.hide();
  document.form1.submit();
}
//--------------------------------
</script>
</body>
</html>
<?
if(isset($cancelar)){
  if($sqlerro == true){
    db_msgbox($erro_msg);
  }
}
?>