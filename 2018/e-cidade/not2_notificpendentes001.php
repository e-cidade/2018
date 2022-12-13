<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_noticonf_classe.php");
include("classes/db_notisitu_classe.php");
$clrotulo = new rotulocampo;
$clnoticonf = new cl_noticonf;
$clnotisitu = new cl_notisitu;
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
db_postmemory($HTTP_POST_VARS);
$db_botao = true;
$db_opcao = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="a=1" bgcolor="#cccccc">
  
<form class="container" name="form1" method="post" action="" >
  <fieldset>
    <legend>Relatórios - Situação da Notificação</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Tk60_codigo?>" >
          <?
           db_ancora(@$Lk60_codigo,"js_pesquisalista(true);",4)
          ?>
        </td>
        <td>
          <?
          db_input('k60_codigo',4,$Ik60_codigo,true,'text',4,"onchange='js_pesquisalista(false);'");
          db_input('k60_descr',40,$Ik60_descr,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td>
         Situação:
        </td>
        <td>
          <?
            $result = $clnotisitu->sql_record($clnotisitu->sql_query(""));
            db_selectrecord("situ",$result,true,$db_opcao);
          ?>
        </td>
      </tr>
	</table>
  </fieldset>
  <input name="executar" type="button" id="executar" value="Gerar Relatorio" onClick="js_executar();">
</form>
 
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_executar(){
 if(document.form1.k60_codigo.value == ""){
  alert(_M('tributario.notificacoes.not2_notificpendentes001.informe_lista'));
  document.form1.k60_codigo.focus();
  return false;
 }
  jan = window.open('not2_notificpendentes002.php?lista='+document.form1.k60_codigo.value+'&situ='+document.form1.situ.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_pesquisalista(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_listaalt.php?funcao_js=parent.js_mostra1|k60_codigo|k60_descr','Pesquisa',true);
  }else{
     if(document.form1.k60_codigo.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe','func_listaalt.php?pesquisa_chave='+document.form1.k60_codigo.value+'&funcao_js=parent.js_mostra','Pesquisa',false);
     }else{
       document.form1.k60_descr.value = '';
     }
  }
}
function js_mostra(chave,erro){
//  alert (chave);
 document.form1.k60_descr.value = chave;
// document.form1.k60_codigo.value = '';
 if(erro==true){
  document.form1.k60_codigo.focus();
 }
}
function js_mostra1(chave1,chave2){
  document.form1.k60_codigo.value = chave1;
  document.form1.k60_descr.value  = chave2;
  db_iframe.hide();
}
</script>
<script>

$("k60_codigo").addClassName("field-size2");
$("k60_descr").addClassName("field-size7");
$("situ").setAttribute("rel","ignore-css");
$("situ").addClassName("field-size2");
$("situdescr").setAttribute("rel","ignore-css");
$("situdescr").addClassName("field-size7");

</script>