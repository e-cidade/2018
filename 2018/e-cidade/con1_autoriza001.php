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
include("classes/db_editalrua_classe.php");
include("dbforms/db_funcoes.php");
$cleditalrua = new cl_editalrua;
$clrotulo = new rotulocampo;
$clrotulo->label("d02_contri");
$clrotulo->label("j14_nome");
$db_opcao = 1;
$db_botao = true;
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);



if(isset($confirmar)){
  $sqlerro=false;
  db_inicio_transacao();
   $cleditalrua->d02_contri=$d02_contri;
   $cleditalrua->d02_autori=$d02_autori;
   $cleditalrua->d02_dtauto= date("Y-m-d",db_getsession("DB_datausu"));
   $cleditalrua->alterar($d02_contri);
   if($cleditalrua->erro_status=='0'){
       $sqlerro = true;
       break;
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
function js_confirmar(){
  if(document.form1.d02_contri.value==""){
    alert("Selecione uma contribuição.");
    return false;
  }
   return true;
}
  </script>


  <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_pesquisa();" >
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
  <form name="form1" method="post" action="">
  <center>
  <table border="0">
    <tr>
      <td nowrap title="<?=@$Td02_contri?>">
      <?=$Ld02_contri?>
      </td>
      <td> 
  <?
  db_input('d02_contri',7,$Id02_contri,true,'text',3);
  db_input('j14_nome',40,$Ij14_nome,true,'text',3);
  ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Td02_autori?>">
         <?=@$Ld02_autori?>
      </td>
      <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('d02_autori',$x,true,$db_opcao,"");
?>
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center">
      <br>
	  <input name="confirmar" type="submit" id="confirmar" value="Confirmar"  onclick="return js_confirmar()">
	  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar"  onclick="js_pesquisa()">
      </td>
    </tr>
  </table>
  </center>
</form>
<script>
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_editalrua.php?funcao_js=parent.js_preenchepesquisa|d02_contri|d02_autori|j14_nome';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave,autori,rua){
  db_iframe.hide();
  document.form1.d02_contri.value = chave;
  document.form1.j14_nome.value = rua;
  var d= autori=='f'?0:1;
  document.form1.d02_autori.options[d].selected=true;
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
if($cleditalrua->erro_status=="0"){
  $cleditalrua->erro(true,false);
  $db_botao=true;
  if($cleditalrua->erro_campo!=""){
    $cleditalrua->erro(true,false);
  };
}else{
  $cleditalrua->erro(true,false);
  echo "<script>document.form1.d02_contri.value='';
          document.form1.d02_autori.options[0].selected=true;
         
         </script>";
};
?>