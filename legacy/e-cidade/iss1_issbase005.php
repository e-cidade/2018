<?php
/**
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
db_postmemory($HTTP_SERVER_VARS);
$clrotulo = new rotulocampo;
$clrotulo->label('q02_inscr');
$clrotulo->label('z01_nome');
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">

  </head>
  <body class="body-default" onload="document.form1.q02_inscr.focus();" >
    <div class="container">
      <form name="form1" method="post" action="iss1_issbaseiframe.php">
        <fieldset>
          <legend>Alteração de Alvará</legend>
          <table>
            <tr>
              <td nowrap title="<?php echo $Tq02_inscr; ?>">
              <?
              db_input('alterar',100,0,true,'hidden',1);
              db_ancora($Lq02_inscr,' js_inscr(true); ',1);
              ?>
            </td>
            <td>
              <?
              db_input('q02_inscr',5,$Iq02_inscr,true,'text',1,"onchange='js_inscr(false)'");
              db_input('z01_nome',40,0,true,'text',3,"");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="entrar" type="button" id="pesquisa" value="Pesquisar" onclick="js_checa()" disabled="disabled">
    </form>
  </div>
  <?
  db_menu(db_getsession("DB_id_usuario"),
          db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),
          db_getsession("DB_instit"));
  ?>
  </body>
</html>
<script>

  function js_checa(){
    if(document.form1.q02_inscr.value==""){
      alert("Informe uma inscrição.");
      document.form1.q02_inscr.focus();
    }else{
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe','func_issbase.php?pesquisa_chave='+document.form1.q02_inscr.value+'&funcao_js=parent.js_testbaix','Pesquisa',false);
    }
  }

  function js_testbaix(chave,erro,baixa){
    if (baixa!=""){
      alert("Inscrição já  Baixada");
      document.form1.q02_inscr.value="";
    }else{
      if (erro==false){
        document.form1.submit();
      }
    }
  }

  function js_inscr(mostra){
    document.getElementById('pesquisa').disabled=true;
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe','func_issbase.php?funcao_js=parent.js_mostra|q02_inscr|z01_nome|q02_dtbaix','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe','func_issbase.php?pesquisa_chave='+document.form1.q02_inscr.value+'&funcao_js=parent.js_mostra1','Pesquisa',false);
    }
  }
  function js_mostra(chave1,chave2,baixa){
    if (baixa!=""){
      db_iframe.hide();
      alert("Inscrição já  Baixada");
      document.form1.z01_nome.value = "";
    }else{
      document.form1.q02_inscr.value = chave1;
      document.form1.z01_nome.value  = chave2;
      db_iframe.hide();
      document.getElementById('pesquisa').disabled=false;
    }
  }
  function js_mostra1(chave,erro,baixa){
    if(erro==true){
      document.form1.z01_nome.value = chave;
      document.form1.q02_inscr.focus();
      document.form1.q02_inscr.value = '';
    }else if (baixa!=""){
      alert("Inscrição já  Baixada");
      document.form1.q02_inscr.value = "";
      document.form1.z01_nome.value  = "";
    }else{
      document.form1.z01_nome.value = chave;
      document.getElementById('pesquisa').disabled=false;
    }
  }
</script>
<?
  if(isset($invalido)){
    db_msgbox("Inscrição inválida.");
  }
  if(isset($permissao)){
    db_msgbox("Usuario sem permissao para alteração de alvara com CNPJ");
  }
?>