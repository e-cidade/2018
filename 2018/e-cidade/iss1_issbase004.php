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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

/** Extensao : Inicio [integracao-icad] */
db_postmemory($HTTP_SERVER_VARS);
/** Extensao : Fim [integracao-icad] */

$clrotulo = new rotulocampo;
$clrotulo->label('q02_numcgm');
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
<body class="body-default" onload="document.form1.q02_numcgm.focus();" >
  <div class="container">
    <form name="form1" method="post" action="iss1_issbaseiframe.php">
      <fieldset>
        <legend>Inclusão de Alvará</legend>
        <table>
          <tr>
            <td nowrap title="<?php echo $Tq02_numcgm; ?>">
              <?php db_ancora($Lz01_nome,' js_cgm(true); ',1); ?>
            </td>
            <td>
             <?php
              db_input('q02_numcgm',5,$Iq02_numcgm,true,'text',1,"onchange='js_cgm(false)'");
              db_input('z01_nome',40,0,true,'text',3,"");
             ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="entrar" type="submit" id="pesquisa" value="Pesquisar" onclick="return js_checa()">
    </form>
  </div>
  <?php
    db_menu(db_getsession("DB_id_usuario"),
            db_getsession("DB_modulo"),
            db_getsession("DB_anousu"),
            db_getsession("DB_instit"));
  ?>
</body>
</html>
<script>
function js_checa(){
  if(document.form1.q02_numcgm.value==""){
    alert("Informe um NUMCGM.");
    return false;
  }
  return true;
}

function js_cgm(mostra){
  var numcgm=document.form1.q02_numcgm.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_nome.php?testanome=true&funcao_js=parent.js_mostra|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_nome.php?testanome=true&pesquisa_chave='+numcgm+'&funcao_js=parent.js_mostra1','Pesquisa',false);
  }
}
function js_mostra(chave1,chave2){
  document.form1.q02_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
function js_mostra1(erro,chave){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.q02_numcgm.focus();
    document.form1.q02_numcgm.value = '';
  }
}
</script>
<?

if(isset($cgccpf)){
  db_msgbox('Atualize o CGCCPF do contribuinte no CGM');
}
if(isset($cep)){
  db_msgbox('Atualize o cep do contribuinte no CGM');
}
if(isset($invalido)){
  db_msgbox('NUMCGM inválido!');
}
if(isset($permissao)){
  db_msgbox("Usuario sem permissao para alteração de alvara com CNPJ");
}
?>