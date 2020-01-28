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
include("classes/db_notificacao_classe.php");
$clrotulo      = new rotulocampo;
$clnoticonf    = new cl_noticonf;
$clnotificacao = new cl_notificacao;
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
db_postmemory($HTTP_POST_VARS);
$db_botao = true;
$instit = db_getsession("DB_instit");

if(isset($k60_codigo)&& ($k60_codigo !="")){
 $sqlerro = false;
 db_inicio_transacao();
 $sql = "select distinct k50_notifica
           from notificacao
          inner join listanotifica on k63_notifica = k50_notifica
          where not exists (select k54_notifica
                              from noticonf
                             where k50_notifica = k54_notifica
                           )
            and k63_codigo = ".$k60_codigo."
            and k50_instit = $instit ";
 
 $result = $clnotificacao->sql_record($sql);
 $clnoticonf->k54_codigo = 3;
 $clnoticonf->k54_data   = date("Y-m-d",db_getsession("DB_datausu"));
 $clnoticonf->k54_hora   = date("H:i");
 for($x=0; $x<$clnotificacao->numrows;$x++){
  db_fieldsmemory($result,$x);
  $clnoticonf->incluir($k50_notifica);
  if($clnoticonf->erro_status==0){
   $sqlerro = true;
   break;
  }
 }
 db_fim_transacao($sqlerro);
 db_msgbox($clnoticonf->erro_msg);
}
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
<body bgcolor="#cccccc">

  
<form class="container" name="form1" method="post" action="" >
  <fieldset>
    <legend>Procedimentos - Notificações por Edital</legend>
	<table class="form-container">
      <tr>
        <td title="<?=@$Tk60_codigo?>" >
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
    </table>
  </fieldset>
  <input name="executar" type="button" id="executar" value="Executar" onClick="js_executar();">
</form>

<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_executar(){
 if(document.form1.k60_codigo.value == ""){
  alert(_M('tributario.notificacoes.not4_notiedital001.informe_lista'));
  document.form1.k60_codigo.focus();
  return false;
 }
 if(confirm(_M('tributario.notificacoes.not4_notiedital001.deseja_executar'))){
  document.form1.submit();
 }
}

function js_pesquisalista(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_lista.php?funcao_js=parent.js_mostra1|k60_codigo|k60_descr','Pesquisa',true);
  }else{
     if(document.form1.k60_codigo.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe','func_lista.php?pesquisa_chave='+document.form1.k60_codigo.value+'&funcao_js=parent.js_mostra','Pesquisa',false);
     }else{
       document.form1.k60_descr.value = '';
     }
  }
}
function js_mostra(chave,erro){
 document.form1.k60_descr.value = chave;

 if(erro==true){
  document.form1.k60_codigo.focus();
   document.form1.k60_codigo.value = '';
 }
}
function js_mostra1(chave1,chave2){
  document.form1.k60_codigo.value = chave1;
  document.form1.k60_descr.value = chave2;
  db_iframe.hide();
}
</script>
<script>

$("k60_codigo").addClassName("field-size2");
$("k60_descr").addClassName("field-size7");

</script>