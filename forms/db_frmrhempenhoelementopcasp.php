<?php
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

/**
 * MODULO: pessoal
 */
$oDaoRhempenhoelementopcasp->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("rh38_codele");
$oRotulo->label("rh38_codele");
$oRotulo->label("o56_descr");

if ($db_opcao == 1) {
  $sNameBotaoProcessar = "incluir";
} else if ($db_opcao == 2 || $db_opcao == 22) {
  $sNameBotaoProcessar = "alterar";
} else {
  $sNameBotaoProcessar = "excluir";
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
      <form name="form1" method="post" action="">
        <fieldset>
          <legend>Desdobramento PCASP</legend>
          <table>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Trh119_rhelementoempdef; ?>" >
                <?php db_input('rh119_sequencial', 6, $Irh119_sequencial, true, 'hidden', 3, ""); ?>
                <label class="bold" for="rh119_rhelementoempdef" id="lbl_rh119_rhelementoempdef">
                  <?php
                    db_ancora( $Srh119_rhelementoempdef . ':', 
                               "js_pesquisarh119_rhelementoempdef(true, false);", $db_opcao);
                  ?>
                </label>
              </td>
              <td>
                <?php
                  db_input('rh119_rhelementoempdef', 10, $Irh119_rhelementoempdef, true, 'hidden', 3);
                  db_input('rh38_codeledef', 5, $Irh38_codele, true, 'text', $db_opcao, " onchange='js_pesquisarh119_rhelementoempdef(false, false);'"); 
                  db_input('o56_descrdef', 35, $Io56_descr, true, 'text', 3, ''); 
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Trh119_rhelementoempnov; ?>" >
                <label class="bold" for="rh119_rhelementoempnov" id="lbl_rh119_rhelementoempnov">
                  <?php
                    db_ancora( $Srh119_rhelementoempnov . ':', 
                               "js_pesquisarh119_rhelementoempnov(true, false);", $db_opcao);
                  ?>
                </label>
              </td>
              <td>
                <?php
                  db_input('rh119_rhelementoempnov', 10, $Irh119_rhelementoempnov, true, 'hidden', 3);
                  db_input('rh38_codelenov', 5, $Irh38_codele, true, 'text', $db_opcao, " onchange='js_pesquisarh119_rhelementoempnov(false, false);'"); 
                  db_input('o56_descrnov', 35, $Io56_descr, true, 'text', 3, ''); 
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input name="<?php echo $sNameBotaoProcessar; ?>" type="submit" id="db_opcao" value="<?php echo ucfirst($sNameBotaoProcessar); ?>" <?php echo (!$db_botao ? "disabled" : ""); ?> >

        <?php if ($db_opcao != 1): ?>
          <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
        <?php endif; ?>
      </form>
    </div>
    <?php db_menu( db_getsession("DB_id_usuario"), 
                   db_getsession("DB_modulo"), 
                   db_getsession("DB_anousu"), 
                   db_getsession("DB_instit") ); ?>
  </body>
  <script>

    function js_pesquisarh119_rhelementoempdef(lExibeJanela, lSequencial) {

      if (lExibeJanela) {
        js_OpenJanelaIframe( 'top.corpo', 
                             'db_iframe_rhelementoempdef', 
                             'func_rhelementoemp.php?funcao_js=parent.js_mostrarhelementoempdef1|rh38_seq|rh38_codele|o56_descr', 
                             'Pesquisa Elemento Principal', true);
      } else {
        if (lSequencial && document.form1.rh119_rhelementoempdef.value != '') {
          js_OpenJanelaIframe( 'top.corpo', 
                             'db_iframe_rhelementoempdef', 
                             'func_rhelementoemp.php?'
                             + 'chave_rh38_seq=' + document.form1.rh119_rhelementoempdef.value
                             +'&funcao_js=parent.js_mostrarhelementoempdef1|rh38_seq|rh38_codele|o56_descr', 
                             'Pesquisa Elemento Principal', true);
        } else if (document.form1.rh38_codeledef.value != '') {
          js_OpenJanelaIframe( 'top.corpo', 
                               'db_iframe_rhelementoempdef', 
                               'func_rhelementoemp.php?'
                               + 'pesquisa_chave=' + document.form1.rh38_codeledef.value
                               + '&funcao_js=parent.js_mostrarhelementoempdef', 
                               'Pesquisa Elemento Principal', false);
        } else {
          document.form1.rh38_codeledef.value = ''; 
          document.form1.o56_descrdef.value = ''; 
          document.form1.rh119_rhelementoempdef.value = ''; 
        }
      }
    }

    function js_mostrarhelementoempdef(sDescricao, lErro, iSequencial) {

      document.form1.o56_descrdef.value = sDescricao;

      if (lErro) {

        document.form1.rh119_rhelementoempdef.value = '';
        document.form1.rh38_codeledef.focus();
        document.form1.rh38_codeledef.value = '';
      } else {
        document.form1.rh119_rhelementoempdef.value = iSequencial;
      }
    }

    function js_mostrarhelementoempdef1(iSequencial, iCodigo, sDescricao) {

      document.form1.rh119_rhelementoempdef.value = iSequencial;
      document.form1.rh38_codeledef.value = iCodigo;
      document.form1.o56_descrdef.value = sDescricao;

      db_iframe_rhelementoempdef.hide();
    }

    function js_pesquisarh119_rhelementoempnov(lExibeJanela, lSequencial) {

      if (lExibeJanela) {
        js_OpenJanelaIframe( 'top.corpo', 
                             'db_iframe_rhelementoemp', 
                             'func_rhelementoemp.php?funcao_js=parent.js_mostrarhelementoempnov1|rh38_seq|rh38_codele|o56_descr', 
                             'Pesquisa Elemento Novo', true);
      } else {
        if (lSequencial && document.form1.rh119_rhelementoempnov.value != '') {
          js_OpenJanelaIframe( 'top.corpo', 
                             'db_iframe_rhelementoemp', 
                             'func_rhelementoemp.php?'
                             + 'chave_rh38_seq=' + document.form1.rh119_rhelementoempnov.value
                             + '&funcao_js=parent.js_mostrarhelementoempnov1|rh38_seq|rh38_codele|o56_descr', 
                             'Pesquisa Elemento Novo', true);
        } else if (document.form1.rh38_codelenov.value != '') {
          js_OpenJanelaIframe( 'top.corpo', 
                               'db_iframe_rhelementoemp', 
                               'func_rhelementoemp.php?'
                               + 'pesquisa_chave=' + document.form1.rh38_codelenov.value
                               + '&funcao_js=parent.js_mostrarhelementoempnov', 
                               'Pesquisa Elemento Novo', false);
        } else {
          document.form1.rh38_codelenov.value = '';
          document.form1.o56_descrnov.value = ''; 
          document.form1.rh119_rhelementoempnov.value = ''; 
        }
      }
    }

    function js_mostrarhelementoempnov(sDescricao, lErro, iSequencial) {

      document.form1.o56_descrnov.value = sDescricao;

      if (lErro) {

        document.form1.rh119_rhelementoempnov.value = '';
        document.form1.rh38_codelenov.focus();
        document.form1.rh38_codelenov.value = '';
      } else {
        document.form1.rh119_rhelementoempnov.value = iSequencial;
      }
    }

    function js_mostrarhelementoempnov1(iSequencial, iCodigo, sDescricao) {

      document.form1.rh119_rhelementoempnov.value = iSequencial;
      document.form1.rh38_codelenov.value = iCodigo;
      document.form1.o56_descrnov.value = sDescricao;

      db_iframe_rhelementoemp.hide();
    }

    function js_pesquisa() {
      js_OpenJanelaIframe( 'top.corpo', 
                           'db_iframe_rhempenhoelementopcasp', 
                           'func_rhempenhoelementopcasp.php?funcao_js=parent.js_preenchepesquisa|rh119_sequencial', 
                           'Pesquisa Desdobramento PCASP', true);
    }

    function js_preenchepesquisa(sChave) {

      db_iframe_rhempenhoelementopcasp.hide();
      <?php
        if ($db_opcao != 1) {
          echo "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa=' + sChave;";
        }
      ?>
    }

    <?php echo (isset($sPosScripts) ? $sPosScripts : ""); ?>
  </script>
</html>