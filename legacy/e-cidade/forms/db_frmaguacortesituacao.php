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

$claguacortesituacao->rotulo->label();
?>
<form name="form1" method="post" action="">
  <fieldset>
    <legend>Situação de Corte</legend>
    <table>
      <tr>
        <td title="Código da Situação">
          <label for="x43_codsituacao" class="bold">Situação:</label>
        </td>
        <td>
          <?php db_input('x43_codsituacao', 5, 1, true, 'text', 3, "") ?>
        </td>
      </tr>

      <tr>
        <td title="Descrição da situação de corte">
          <label for="x43_descr" class="bold">Descrição:</label>
        </td>
        <td>
          <?php db_input('x43_descr', 40, 0, true, 'text', $db_opcao, "") ?>
        </td>
      </tr>

      <tr>
        <td title="Regra da situação de corte">
          <label for="x43_regra" class="bold">Regra:</label>
        </td>
        <td>
          <?php
            $aRegras = array(
              0 => 'Normal',
              1 => 'Inicia Procedimento de Corte',
              2 => 'Finaliza Procedimento de Corte',
              3 => 'Bloqueia Corte'
            );
            db_select('x43_regra', $aRegras, true, $db_opcao, "");
          ?>
        </td>
      </tr>

      <tr>
        <td>
          <label for="x43_realizacobranca" class="bold">Realiza Cobrança:</label>
        </td>
        <td>
          <?php
            if (!isset($x43_realizacobranca) || $x43_realizacobranca == 't') {
              $x43_realizacobranca = 1;
            }
            db_select('x43_realizacobranca', array(1 => 'Sim', 0 => 'Não'), true, $db_opcao);
          ?>
        </td>
      </tr>
    </table>
  </fieldset>

  <input name="<?= ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>" type="submit" id="db_opcao" value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" <?= ($db_botao == false ? "disabled" : "") ?>>
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>

<script type="text/javascript">

  function js_pesquisa() {

    js_OpenJanelaIframe(
      'CurrentWindow.corpo',
      'db_iframe_aguacortesituacao',
      'func_aguacortesituacao.php?funcao_js=parent.js_preenchepesquisa|x43_codsituacao',
      'Pesquisa',
      true
    );
  }

  function js_preenchepesquisa(chave) {

    db_iframe_aguacortesituacao.hide();
    <?php
      if ($db_opcao != 1) {
        echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
      }
    ?>
  }
</script>
