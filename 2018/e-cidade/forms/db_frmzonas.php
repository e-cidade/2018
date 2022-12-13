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

//MODULO: cadastro
$clzonas->rotulo->label();
?>
<form name="form1" method="post" action="">

  <fieldset>
    <legend>Zona Fiscal</legend>

    <table>
      <tr>
        <td nowrap title="<?php echo $Tj50_zona ?>">
          <label for='j50_zona'><?php echo $Lj50_zona ?></label>
        </td>
        <td>
          <?php db_input('j50_zona',10,$Ij50_zona,true,'text', 3,"") ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo $Tj50_descr ?>">
          <label for='j50_descr'><?php echo $Lj50_descr ?></label>
        </td>
        <td>
          <?php db_input('j50_descr',40,$Ij50_descr,true,'text',$db_opcao,"") ?>
        </td>
      </tr>
    </table>
  </fieldset>

  <input name="db_opcao" type="submit" id="db_opcao" onclick="return js_validaFormulario();" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >

  <?php
    if ( $db_opcao != 1 ) {
      echo "<input name='pesquisar' type='button' id='pesquisar' value='Pesquisar' onclick='js_pesquisa();' >";
    }
  ?>
</form>

<script type="text/javascript">

  function js_validaFormulario(){

    if( js_empty( $F('j50_descr') ) ){

      alert('Campo Descrição é de preenchimento obrigatório.');
      return false;
    }

    return true;
  }

  function js_pesquisa(){
    js_OpenJanelaIframe('top.corpo','db_iframe_zonas','func_zonas.php?funcao_js=parent.js_preenchepesquisa|j50_zona','Pesquisa',true);
  }
  function js_preenchepesquisa(chave){

    db_iframe_zonas.hide();
    <?
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
    ?>
}
</script>