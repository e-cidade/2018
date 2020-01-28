<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";

$clpcproc          = new cl_pcproc;
$clpcorcamitem     = new cl_pcorcamitem;
$clpcorcamitemproc = new cl_pcorcamitemproc;
$clrotulo          = new rotulocampo;
$clpcproc->rotulo->label();

db_postmemory($HTTP_POST_VARS);

$action = "com1_processo004.php";

if ($op == "alterar") {
  $action = "com1_processo005.php";
} else if($op == "excluir") {
  $action = "com1_processo006.php";
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
  <body class="body-default">

    <div class="container">
      <form name="form1" method="post" action="<?=$action?>">

        <fieldset>
          <legend>Orçamento de Processo de Compras</legend>

          <table>
            <tr>
              <td align="left" nowrap title="<?php echo $Tpc80_codproc; ?>">
                <?php db_ancora($Lpc80_codproc, "js_pesquisapc80_codproc(true);", 1);?>
              </td>
              <td align="left" nowrap>
                <?php
                  db_input('pc80_codproc',8,$Ipc80_codproc,true,"text",3);
                  db_input('db_opcaoal',8,0,true,"hidden",3,"");
                  db_input('pc22_codorc',8,0,true,"hidden",3,"");
                  db_input('retorno',8,0,true,"hidden",3,"");
                ?>
              </td>
            </tr>
          </table>
        </fieldset>

        <input name="enviar" type="button" id="enviar" value="Processar" onclick='js_verifica();'>
      </form>
    </div>

  </body>
</html>
<script type="text/javascript">
  <?php
    $clickaut     = false;
    if (isset($pc22_codorc) && !empty($pc22_codorc)) {

      $result_solic = $clpcorcamitemproc->sql_record($clpcorcamitemproc->sql_query(null,null,"pc22_orcamitem","","pc22_codorc=".@$pc22_codorc));
      if ($clpcorcamitemproc->numrows>0) {
        $clickaut = true;
      }
    }
  ?>

  function js_verifica() {

    if(document.form1.pc80_codproc.value==''){
      alert("Informe o número do processo de compras.");
    }else{
      document.form1.submit();
    }
  }

  function js_pesquisapc80_codproc(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo.iframe_orcam',
                          'db_iframe_pcproc',
                          'func_pcproc.php?orclic=true&situacao=2&lBloqueiaVinculadosOrcamento=true&funcao_js=parent.js_mostrapcproc1|pc80_codproc',
                          'Pesquisa',
                          true,
                          '0');
    }else{
      js_OpenJanelaIframe('top.corpo.iframe_orcam',
                          'db_iframe_pcproc',
                          'func_pcproc.php?orclic=true&situacao=2&lBloqueiaVinculadosOrcamento=true&funcao_js=parent.js_mostrapcproc1|pc80_codproc',
                          'Pesquisa',false,'0');
    }
  }
  function js_mostrapcproc1(chave1,chave2){
    document.form1.pc80_codproc.value = chave1;
    db_iframe_pcproc.hide();
    <?
    if($clickaut == true){
      echo "document.form1.enviar.click();";
    }
    ?>
  }
  <?
  if($clickaut == true){
    echo "js_pesquisapc80_codproc(false);";
  }else{
    echo "js_pesquisapc80_codproc(true);";
  }
  ?>
  document.form1.retorno.value = document.form1.pc22_codorc.value;
</script>