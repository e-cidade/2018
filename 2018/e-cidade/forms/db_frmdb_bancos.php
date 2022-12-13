<?
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

//MODULO: configuracoes
$cldb_bancos->rotulo->label();
?>
<form name="form1" method="post" action="" enctype="multipart/form-data" >
  <center>
    <table border="0">
      <tr>
        <td nowrap title="<?=@$Tdb90_codban?>">
          <?=@$Ldb90_codban?>
        </td>
        <td>
          <?
          db_input('db90_codban',10,$Idb90_codban,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tdb90_descr?>">
          <?=@$Ldb90_descr?>
        </td>
        <td>
          <?
          db_input('db90_descr',40,$Idb90_descr,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tdb90_digban?>">
          <?=@$Ldb90_digban?>
        </td>
        <td>
          <?
          db_input('db90_digban',2,$Idb90_digban,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tdb90_abrev?>">
          <?=@$Ldb90_abrev?>
        </td>
        <td>
          <?
          db_input('db90_abrev',20,$Idb90_abrev,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tdb90_logo?>">
          <?=@$Ldb90_logo?>
        </td>
        <td>
          <input type="file" name="db90_logo" size="40" />
        </td>
      </tr>
    </table>
  </center>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  <br><br>
  <?
  if(isset($db90_logo) and $db90_logo!= ""){
    ?>
    <table >
      <tr>
        <td align="center"> <b>Imagem gravada no banco</b></td>
      </tr>
      <tr>
        <td align="center">
          <img src="mostralogo.php?db90_logo=<?=$db90_logo?>" >
        </td>
      </tr>
    </table>
    <?
  }else{
    echo "Não possui imagem gravada no banco";
  }

  ?>

</form>
<script>
  function js_pesquisa(){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_bancos','func_db_bancos.php?funcao_js=parent.js_preenchepesquisa|db90_codban','Pesquisa',true);
  }
  function js_preenchepesquisa(chave){
    db_iframe_db_bancos.hide();
    <?
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
    ?>
  }
</script>