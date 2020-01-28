<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("classes/db_parprojetos_classe.php");

$clrotulo = new rotulocampo;
$clrotulo->label("ob04_codobra");
$clrotulo->label("ob01_nomeobra");
$oDaoParProjetos = new cl_parprojetos();
$iTipoRelatorio  = null;

$sSqlParametros  = $oDaoParProjetos->sql_query_pesquisaParametros( db_getsession('DB_anousu') );
$rsParametros    = $oDaoParProjetos->sql_record($sSqlParametros);
$db_opcao        = 1;

if ($oDaoParProjetos->erro_status != "0") {

  $oParametros    = db_utils::fieldsMemory($rsParametros, 0);
  $db_opcao       = 3;
  $iTipoRelatorio = $oParametros->ob21_tipocartaalvara;
} else {
  db_msgbox(_M('tributario.projetos.pro2_execobra001.paremetros_nao_configurados'));
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
<body class="body-default" onLoad="document.form1.ob04_codobra.focus()">
<div class="container">
<form name="form1" method="post" >
    <fieldset>
      <legend>Emissão de carta de alvará</legend>
      <table class="form-container">
      <tr>
        <td nowrap title="<?php echo @$Tob04_codobra; ?>">
          <?php
            db_ancora(@$Lob04_codobra,"js_pesquisaob04_codobra(true);",4);
          ?>
        </td>
        <td>
          <?php
            db_input('ob04_codobra',10,$Iob04_codobra,true,'text',4," onchange='js_pesquisaob04_codobra(false);'");
            db_input('ob01_nomeobra',40,$Iob01_nomeobra,true,'text',3);
          ?>
        </td>
      </tr>
      </table>
    </fieldset>
    <input name="exibir_relatorio" type="button" id="exibir_relatorio" value="Exibir relat&oacute;rio" onClick="js_AbreJanelaRelatorio(<?=$iTipoRelatorio; ?>)" />
</form>
</div>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">
function js_AbreJanelaRelatorio(iTipoRelatorio) {

  /**
   * Verifica qual relatório abrir, 0 pdf, 1 office
   */
  if(iTipoRelatorio == 0) {
    sTipoArquivoRelatorio = "pro2_execobra002.php";
  } else {
    sTipoArquivoRelatorio = "pro2_execobra003.php";
  }

  if( document.form1.ob04_codobra.value!='' ) {

    jan = window.open(sTipoArquivoRelatorio + '?codigo='+document.form1.ob04_codobra.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }else{
    alert(_M('tributario.projetos.pro2_execobra001.digite_obra'));
  }
}

function js_pesquisaob04_codobra(mostra) {

  if(mostra == true){
    js_OpenJanelaIframe('top.corpo', 'db_iframe_obrasalvara', 'func_obrasalvara.php?funcao_js=parent.js_mostratermoalvara1|ob04_codobra|ob01_nomeobra', 'Pesquisa', true);
  }else{

    if(document.form1.ob04_codobra.value != '') {
      js_OpenJanelaIframe('top.corpo', 'db_iframe_obrasalvara', 'func_obrasalvara.php?pesquisa_chave=' + document.form1.ob04_codobra.value + '&funcao_js=parent.js_mostratermoalvara', 'Pesquisa', false);
    }
  }
}

function js_mostratermoalvara(chave, erro) {

  if(erro == true){

    document.form1.ob04_codobra.focus();
    document.form1.ob04_codobra.value = '';
  }

  document.form1.ob01_nomeobra.value = chave;
}

function js_mostratermoalvara1 (iCodigoObra, sNomeObra) {

  document.form1.ob04_codobra.value  = iCodigoObra;
  document.form1.ob01_nomeobra.value = sNomeObra;
  db_iframe_obrasalvara.hide();
}
</script>