<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_stdlibwebseller.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_baixabib_classe.php"));
include(modification("classes/db_exemplar_classe.php"));
include(modification("classes/db_cgm_classe.php"));
include(modification("classes/db_bib_parametros_classe.php"));
include(modification("dbforms/db_funcoes.php"));
db_postmemory($HTTP_POST_VARS);
$clbaixabib = new cl_baixabib;
$clexemplar = new cl_exemplar;
$clcgm = new cl_cgm;
$clbib_parametros = new cl_bib_parametros;
$clcgm->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("bi23_codbarras");
$clrotulo->label("bi23_codigo");
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
 db_inicio_transacao();
 $clbaixabib->bi08_usuario = db_getsession("DB_id_usuario");
 $clbaixabib->incluir(null);
 db_fim_transacao();
}
$depto = db_getsession("DB_coddepto");
$sql = "SELECT bi17_codigo,bi17_nome FROM biblioteca WHERE bi17_coddepto = $depto";
$result = db_query($sql);;
$linhas = pg_num_rows($result);
if($linhas!=0){
 db_fieldsmemory($result,0);
 $result1 = $clbib_parametros->sql_record($clbib_parametros->sql_query("","bi26_leitorbarra",""," bi26_biblioteca = $bi17_codigo"));
 if($clbib_parametros->numrows>0){
  db_fieldsmemory($result1,0);
 }else{
  $bi26_leitorbarra = "N";
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
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<?MsgAviso(db_getsession("DB_coddepto"),"biblioteca",""," bi17_coddepto = ".db_getsession("DB_coddepto")."");?>
<table width="790" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
  <center>
   <?
   if(!isset($bi23_codigo)){
    if($bi26_leitorbarra=="S"){
     $ancora = "Lbi23_codbarras";
     $tipocampo1 = "hidden";
     $tipocampo2 = "text";
    }else{
     $ancora = "Lbi23_codigo";
     $tipocampo1 = "text";
     $tipocampo2 = "hidden";
    }
    ?>
    <table width="760" border="0">
     <tr>
      <td>
       <br>
       <fieldset width="90%"><legend><b>Baixa de Acervo:</b></legend>
       <form name="form1" method="POST" action="">
       <table border="0">
        <tr>
         <td nowrap title="<?=@$Tbi23_codbarras?>">
          <label for="bi23_codbarras"><?db_ancora(@$$ancora,"js_pesquisabi23_codbarras(true);",$db_opcao);?></label>
         </td>
         <td>
          <?db_input('bi23_codigo',10,@$Ibi23_codigo,true,$tipocampo1,$db_opcao,"")?>
          <?db_input('bi23_codbarras',20,@$Ibi23_codbarras,true,$tipocampo2,$db_opcao," onchange='js_pesquisabi23_codbarras(false);'")?>
          <?db_input('bi06_titulo',50,@$bi06_titulo,true,'text',3," ")?>
          <input name="proximo" type="submit" id="proximo" value="Próximo" <?=@$bi23_codigo!=""?"disabled":""?> onclick="return js_valida();">
         </td>
        </tr>
       </table>
       </fieldset>
       </form>
      </td>
     </tr>
     <tr>
      <td>
      </td>
     </tr>
    </table>
    <br>
    <?
   }else{
    ?><br><fieldset width="90%"><legend><b>Baixa de Acervo:</b></legend><?
     include(modification("forms/db_frmbaixabib.php"));
    ?></fieldset><?
   }
   ?>
  </center>
  </td>
 </tr>
</table>
<script>
<?if($bi26_leitorbarra=="S"){?>
 js_tabulacaoforms("form1","bi23_codbarras",true,1,"bi23_codbarras",true);
<?}else{?>
 js_tabulacaoforms("form1","bi23_codigo",true,1,"bi23_codigo",true);
<?}?>

</script>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<?
if(isset($incluir)){
 if($clbaixabib->erro_status=="0"){
  $clbaixabib->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clbaixabib->erro_campo!=""){
   echo "<script> document.form1.".$clbaixabib->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clbaixabib->erro_campo.".focus();</script>";
  };
 }else{
  $sql = "UPDATE exemplar SET bi23_situacao = 'N' WHERE bi23_codigo = $bi08_exemplar";
  $result = db_query($sql);
  $sql = "DELETE FROM localexemplar WHERE bi27_exemplar = $bi08_exemplar";
  $result = db_query($sql);
  $clbaixabib->erro(true,true);
 };
};
?>
<script>
function js_pesquisabi23_codbarras(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_exemplar','func_exemplarbaixa.php?funcao_js=parent.js_mostraexemplar1|bi23_codbarras|bi06_titulo|bi23_codigo','Pesquisa',true);
 }else{
  if(document.form1.bi23_codbarras.value != ''){
   js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_exemplar','func_exemplarbaixa.php?pesquisa_chave='+document.form1.bi23_codbarras.value+'&funcao_js=parent.js_mostraexemplar','Pesquisa',false);
  }else{
   document.form1.bi06_titulo.value = '';
   document.form1.bi23_codigo.value = '';
  }
 }
}
function js_mostraexemplar(chave1,chave2,erro){
 document.form1.bi06_titulo.value = chave1;
 document.form1.bi23_codigo.value = chave2;
 if(erro==true){
  document.form1.bi23_codbarras.focus();
  document.form1.bi23_codbarras.value = '';
 }
}
function js_mostraexemplar1(chave1,chave2,chave3){
 document.form1.bi23_codbarras.value = chave1;
 document.form1.bi06_titulo.value = chave2;
 document.form1.bi23_codigo.value = chave3;
 db_iframe_exemplar.hide();
}
function js_valida(){
 if(document.form1.bi23_codigo.value==""){
  alert("Informe o código de barras do exemplar!")
  return false;
 }
 return true;
}
</script>