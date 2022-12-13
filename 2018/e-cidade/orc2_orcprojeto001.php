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
include("libs/db_liborcamento.php");
require("classes/db_orcsuplem_classe.php");  // declaração da classe orcreserva
include("classes/db_orcprojeto_classe.php");
include("classes/db_orcparametro_classe.php");

$clorcsuplem    = new cl_orcsuplem ; // instancia classe orcsuplem
$clorcprojeto   = new cl_orcprojeto;
$clorcparametro = new cl_orcparametro;

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$clorcsuplem->rotulo->label();
db_postmemory($HTTP_POST_VARS);
$db_opcao=1;
if (isset($chavepesquisa) && $chavepesquisa!=""){
	$o46_codlei = $chavepesquisa;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_projeto(){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojeto','func_orcprojeto001.php?funcao_js=parent.js_mostra|o39_codproj','Pesquisa',true);
}
function js_mostra(chave,erro){
  document.form1.o46_codlei.value = chave; 
  db_iframe_orcprojeto.hide();
  location.href='orc2_orcprojeto001.php?chavepesquisa='+chave;
  if(erro==true){ 
    document.form1.o46_codlei.focus(); 
    document.form1.o46_codlei.value = ''; 
  }
}
function js_refresh(tipo){
  chave = document.form1.o46_codlei.value;  
  location.href='orc2_orcprojeto001.php?chavepesquisa='+chave+'&tipo='+tipo;
}
function emite(){
   obj = document.form1;

   if (obj.o46_codlei.value ==''){
      alert('Preencha o Numero do Projeto ');
   } else {  
      if (obj.modelo.value=='1'){  
          jan = window.open('orc2_orcprojeto002.php?o46_codlei='+obj.o46_codlei.value+'&timbre=s','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
          jan.moveTo(0,0);
      } else if (obj.modelo.value=='2') {
	  jan = window.open('orc2_orcprojeto003.php?o46_codlei='+obj.o46_codlei.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
          jan.moveTo(0,0);
      } else if (obj.modelo.value=='3') {
          jan = window.open('orc2_orcprojeto004.php?o46_codlei='+obj.o46_codlei.value+'&timbre=s','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
          jan.moveTo(0,0);
      } else if (obj.modelo.value=='4') {
          jan = window.open('orc2_orcprojeto002.php?o46_codlei='+obj.o46_codlei.value+'&timbre=n','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
          jan.moveTo(0,0);
      }
   }  
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">  &nbsp;</td>
    <td width="25">   &nbsp;</td>
    <td width="140">  &nbsp;</td>
  </tr>
</table> 
 <br><br>
  <table  align="center" border="0" >
   <form name="form1" method="post" action="" >   
   <tr>
      <td nowrap title="<?=@$To46_codlei?>"><? db_ancora(@$Lo46_codlei,"js_projeto();",$db_opcao);  ?> </td>
      <td nowrap> <? db_input('o46_codlei',8,$Io46_codlei,true,'text',$db_opcao,"") ?>  </td>

      <td nowrap><b>Modelo </b>
      <? 
          $rr = $clorcparametro->sql_record($clorcparametro->sql_query_file(db_getsession("DB_anousu"),"o50_tipoproj as modelo"));	  
	  if ($clorcparametro->numrows > 0 ){
	     db_fieldsmemory($rr,0);
	  }
          $m = array("1"=>"1-com Timbre","2"=>"2-Dotacao Sintetica","3"=>"3-Com CodDot","4"=>"4-Sem Timbre");
          db_select("modelo",$m,True,1);               
      ?>
      </td>

   </tr>
         
   <tr>
      <td> &nbsp;  </td>
      <td colspan="2"  >  &nbsp;  </td>
   </tr>
   <?
   if (isset($o46_codlei) && $o46_codlei!=""){
          /*
           *  seleciona o conteúdo do texto livre dos projetos
           */
        if (isset($tipo) && $tipo=="retif"){
           $res = $clorcprojeto->sql_record($clorcprojeto->sql_query_file($o46_codlei));
           if ($clorcprojeto->numrows > 0 ){
           	  db_fieldsmemory($res,0);
           	  ?>
           	  <tr>
                <td><b>Texto Livre</b></td>
      			<td colspan="2" >
      			<textarea name="texto_livre" cols=70 rows=4><?=$o39_textolivre?></textarea></td>
   			  </tr>
           	  <?
           }
     }
   }
   ?>   
   <tr>
      <td> &nbsp;  </td>
      <td colspan="2"  >  &nbsp;  </td>
   </tr>
   
   <tr>
      <td> &nbsp;  </td>
      <td colspan="2"  nowrap align="center"> <input name="emitir" type="button" value="Emitir Projeto" onclick="emite();" ></td>
   </tr>
  
 </table>
 </form>
 <? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
</body>
</html>