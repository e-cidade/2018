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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
$clrotulo = new rotulocampo;
$clrotulo->label("t21_codigo");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
function js_AbreJanelaRelatorio() {

    if ( document.form1.t21_codigo.value!='' ) {

      var sUrl  = 'pat2_reltermoguarda001.php?';
          sUrl += 'iTermo='+ document.form1.t21_codigo.value;//$F('t22_bensguarda');

      js_OpenJanelaIframe('', 'db_iframe_imprime_termo', sUrl, 'Imprime Termo', true);

	    //jan = window.open('pat2_termoguarda002.php?codigo='+document.form1.t21_codigo.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
		//jan.moveTo(0,0);
    } else {
       alert(_M("patrimonial.patrimonio.pat2_termoguarda001.preencha_codigo_guarda"));
    }
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC onLoad="document.form1.t21_codigo.focus()" >
<form class="container" name="form1" method="post" >
  <fieldset>
    <legend>Relatórios - Emissão Termo de Guarda</legend>
    <table class="form-container">
    <tr>
      <td nowrap title="<?=@$Tt21_codigo?>" >
        <?
          db_ancora(@$Lt21_codigo,"js_pesquisabensguarda(true);",4)
        ?>
        <b>:</b>
      </td>
      <td>
        <?
          db_input('t21_codigo',10,$It21_codigo,true,'text',4,"onchange='js_pesquisabensguarda(false);'")
        ?>
      </td>
    </tr>
    </table>
  </fieldset>
  <input name="exibir_relatorio" type="button" id="exibir_relatorio" value="Exibir relat&oacute;rio" onClick="js_AbreJanelaRelatorio()">
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisabensguarda(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_bensguarda','func_bensguarda.php?funcao_js=parent.js_mostrabensguarda1|t21_codigo','Pesquisa',true);
  }else{
     if(document.form1.t21_codigo.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_bensguarda','func_bensguarda.php?pesquisa_chave='+document.form1.t21_codigo.value+'&funcao_js=parent.js_mostrabensguarda','Pesquisa',false);
     }else{
       document.form1.t21_codigo.value = '';
     }
  }
}
function js_mostrabensguarda(chave,erro){
  document.form1.t21_codigo.value = chave;
  if(erro==true){
    document.form1.t21_codigo.focus();
    document.form1.t21_codigo.value = '';
  }
}
function js_mostrabensguarda1(chave1){
  document.form1.t21_codigo.value = chave1;
  db_iframe_bensguarda.hide();
}
</script>
<script>

$("t21_codigo").addClassName("field-size2");

</script>