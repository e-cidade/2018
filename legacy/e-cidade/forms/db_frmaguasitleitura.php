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

//MODULO: agua
$claguasitleitura->rotulo->label();
?>
<form name="form1" method="post" action="">

  <fieldset>

    <legend>Situações de Leitura</legend>

    <table border="0">

      <tr>
        <td nowrap title="<?=@$Tx17_codigo?>">
           <strong>Código:</strong>
        </td>
        <td>
        <?php
        db_input('x17_codigo',5,$Ix17_codigo,true,'text',$db_opcao,"")
        ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=@$Tx17_descr?>">
           <strong>Descrição:</strong>
        </td>
        <td>
        <?php
        db_input('x17_descr',40,$Ix17_descr,true,'text',$db_opcao,"")
        ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=@$Tx17_regra?>">
           <strong>Regra:</strong>
        </td>
        <td>
        <?
        $aOpcoes = array(
          '0' => 'Normal',
          '1' => 'Sem Leitura - Sem Saldo',
          '3' => 'Sem Leitura - Com Saldo',
          '2' => 'Cancelamento',
          '4' => 'Média Últimos Meses',
          '5' => 'Média Penalidade',
        );
        db_select('x17_regra', $aOpcoes, true, $db_opcao, "");
        ?>
        </td>
      </tr>
    </table>

  </fieldset>
  <?php $sName = ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>
  <?php $sValue = ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>
  <input name="<?php echo $sName ?>" type="submit" id="db_opcao" value="<?php echo $sValue ?>" <?=($db_botao == false ? "disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_aguasitleitura','func_aguasitleitura.php?funcao_js=parent.js_preenchepesquisa|x17_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){

  db_iframe_aguasitleitura.hide();
  <?php
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
