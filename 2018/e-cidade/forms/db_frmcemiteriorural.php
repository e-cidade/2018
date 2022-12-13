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

//MODULO: cemiterio
$clcemiteriorural->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("cm14_i_codigo");
?>
<form name="form1" method="post" action="">
  <fieldset>
    <legend>Cemitério Rural</legend>

      <table>
        <tr>
          <td nowrap title="<?=@$Tcm14_i_codigo?>"><?=@$Lcm14_i_codigo?></td>
          <td>
          <?php
            db_input('cm16_i_cemiterio',10,$Icm16_i_cemiterio,true,'text',3,"");
          ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tcm16_c_nome?>"><?=@$Lcm16_c_nome?></td>
          <td>
          <?php
            db_input('cm16_c_nome',60,$Icm16_c_nome,true,'text',$db_opcao,"");
          ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tcm16_c_endereco?>"><?=@$Lcm16_c_endereco?></td>
          <td>
          <?php
            db_input('cm16_c_endereco',60,$Icm16_c_endereco,true,'text',$db_opcao,"");
          ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tcm16_c_cep?>"><?=@$Lcm16_c_cep?></td>
          <td>
          <?php
            db_input('cm16_c_cep',10,$Icm16_c_cep,true,'text',$db_opcao,"");
          ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tcm16_c_cidade?>"><?=@$Lcm16_c_cidade?></td>
          <td>
          <?php
            db_input('cm16_c_cidade',60,$Icm16_c_cidade,true,'text',$db_opcao,"");
          ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tcm16_c_bairro?>"><?=@$Lcm16_c_bairro?></td>
          <td>
          <?php
            db_input('cm16_c_bairro',50,$Icm16_c_bairro,true,'text',$db_opcao,"");
          ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tcm16_c_telefone?>"><?=@$Lcm16_c_telefone?></td>
          <td>
          <?php
            db_input('cm16_c_telefone',14,$Icm16_c_telefone,true,'text',$db_opcao,"");
          ?>
          </td>
        </tr>
      </table>
  </fieldset>

  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> />
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" <? if($db_opcao == 1){ echo "disabled"; } ?> />
</form>
<script type="text/javascript">

function js_pesquisacm16_i_cemiterio(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cemiterio','func_cemiterio.php?funcao_js=parent.js_mostracemiterio1|cm14_i_codigo|cm14_i_codigo','Pesquisa',true);
  }else{

     if(document.form1.cm16_i_cemiterio.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_cemiterio','func_cemiterio.php?pesquisa_chave='+document.form1.cm16_i_cemiterio.value+'&funcao_js=parent.js_mostracemiterio','Pesquisa',false);
     }else{
       document.form1.cm14_i_codigo.value = '';
     }
  }
}
function js_mostracemiterio(chave,erro){

  document.form1.cm14_i_codigo.value = chave;
  if(erro==true){
    document.form1.cm16_i_cemiterio.focus();
    document.form1.cm16_i_cemiterio.value = '';
  }
}
function js_mostracemiterio1(chave1,chave2){

  document.form1.cm16_i_cemiterio.value = chave1;
  document.form1.cm14_i_codigo.value = chave2;
  db_iframe_cemiterio.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cemiteriorural','func_cemiteriorural.php?funcao_js=parent.js_preenchepesquisa|cm16_i_cemiterio','Pesquisa',true);
}
function js_preenchepesquisa(chave){

  db_iframe_cemiteriorural.hide();
  <?php
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?tp=$tp&chavepesquisa='+chave";
  }
  ?>
}
</script>