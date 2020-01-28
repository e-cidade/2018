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

$oDaoPortariaassinatura->rotulo->label();

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
          <legend><?php echo ucfirst($sNameBotaoProcessar); ?> Assinaturas Portaria</legend>
          <table>
            <?php
              db_input('rh136_sequencial', 10, $Irh136_sequencial, true, 'hidden', $db_opcao, "");
            ?>
            <tr>
              <td nowrap title="<?php echo $Trh136_nome; ?>" >
                <label class="bold" for="rh136_nome" id="lbl_rh136_nome"><?php echo $Srh136_nome; ?>:</label>
              </td>
              <td>
                <?php db_input('rh136_nome', 70, $Irh136_nome, true, 'text', $db_opcao, "onkeyup=js_ValidaCampos(this,3,'Nome','f','t',event);"); ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Trh136_cargo; ?>" >
                <label class="bold" for="rh136_cargo" id="lbl_rh136_cargo"><?php echo $Srh136_cargo; ?>:</label>
              </td>
              <td>
                <?php db_input('rh136_cargo', 70, $Irh136_cargo, true, 'text', $db_opcao, ""); ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Trh136_amparo; ?>" >
                <label class="bold" for="rh136_amparo" id="lbl_rh136_amparo"><?php echo $Srh136_amparo; ?>:</label>
              </td>
              <td>
                <?php db_textarea('rh136_amparo',4, 68, $Irh136_amparo, true, 'text', $db_opcao, ""); ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input name="<?php echo $sNameBotaoProcessar; ?>" type="submit" id="db_opcao" value="<?php echo ucfirst($sNameBotaoProcessar); ?>" <?php echo (!$db_botao ? "disabled" : ""); ?> >
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
      </form>
    </div>
    <?php db_menu( db_getsession("DB_id_usuario"), 
                   db_getsession("DB_modulo"), 
                   db_getsession("DB_anousu"), 
                   db_getsession("DB_instit") ); ?>
  </body>
  <script>

    function js_pesquisa() {
      js_OpenJanelaIframe( 'top.corpo', 
                           'db_iframe_portariaassinatura', 
                           'func_portariaassinatura.php?funcao_js=parent.js_preenchepesquisa|rh136_sequencial', 
                           'Pesquisa', true);
    }

    function js_preenchepesquisa(sChave) {

      db_iframe_portariaassinatura.hide();
      <?php
        if ($db_opcao != 1) {
          echo "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa=' + sChave;";
        }
      ?>
    }

    <?php echo (isset($sPosScripts) ? $sPosScripts : ""); ?>
  </script>
</html>
