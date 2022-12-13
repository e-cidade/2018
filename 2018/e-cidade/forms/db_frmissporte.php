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

//MODULO: issqn
$clissporte->rotulo->label();
?>
<form name="form1" method="post" action="">
  <fieldset>
    <legend>Dados do Porte</legend>
    <table>
      <tr>
        <td nowrap title="<?php echo $Tq40_codporte?>">
           <label for="q40_codporte"><?php echo $Lq40_codporte?></label>
        </td>
        <td>
          <?php
            db_input('q40_codporte',10,$Iq40_codporte,true,'text',3,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo $Tq40_descr?>">
           <label for="q40_descr"><?php echo $Lq40_descr?></label>
        </td>
        <td>
          <?php
            db_input('q40_descr',40,$Iq40_descr,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo $Tq40_fisica?>">
           <label for="q40_fisica"><?php echo $Lq40_fisica?></label>
        </td>
        <td>
          <?php
            $x = array('t'=>'Física','f'=>'Jurídica');
            db_select('q40_fisica',$x,true,$db_opcao,"");
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?php echo ($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?php echo ($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?php echo ($db_botao==false?"disabled":"")?> >
  <?php
    if ($db_opcao != 1) {
      echo "<input name=\"pesquisar\" type=\"button\" id=\"pesquisar\" value=\"Pesquisar\" onclick=\"js_pesquisa();\" >";
    }
  ?>
</form>
<script type="text/javascript">

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_issporte','func_issporte.php?funcao_js=parent.js_preenchepesquisa|q40_codporte','Pesquisa',true);
}

function js_preenchepesquisa(chave){

  db_iframe_issporte.hide();
  <?php
    if ( $db_opcao != 1 ) {
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
  ?>
}
</script>