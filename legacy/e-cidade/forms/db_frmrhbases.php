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

//MODULO: pessoal
$clbases->rotulo->label();

$r08_instit = db_getsession("DB_instit");
?>
<form name="form1" method="post" action="" class="container">
  <fieldset>
    <legend>
      Dados da Base
    </legend>

    <table class="form-container">
      <tr>
        <td  title="<?=$Tr08_codigo?>">
          <?=$Lr08_codigo?>
        </td>
        <td>
          <?php
          db_input('r08_codigo',4,$Ir08_codigo,true,'text',($db_opcao!=1?3:1),"onchange='valida_r08_codigo();'");
          db_input('r08_instit',4,$Ir08_instit,true,'hidden',3,"");
          ?>
        </td>
      </tr>
      <tr>
        <td  title="<?=$Tr08_descr?>">
          <?=$Lr08_descr?>
        </td>
        <td>
          <?php
          db_input('r08_descr',30,$Ir08_descr,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td  title="<?=$Tr08_calqua?>">
          <?=$Lr08_calqua?>
        </td>
        <td>
          <?php
          $x = array("f"=>"Não","t"=>"Sim");
          db_select('r08_calqua',$x,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td  title="<?=$Tr08_mesant?>">
          <?=$Lr08_mesant?>
        </td>
        <td>
          <?php
          db_select('r08_mesant',$x,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td  title="<?=$Tr08_pfixo?>">
          <?=$Lr08_pfixo?>
        </td>
        <td>
          <?php
          db_select('r08_pfixo',$x,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      <!-- ContratosPADRS: tipo de base form -->
    </table>
  </fieldset>

  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return valida_r08_codigo(); return js_verificacodigo();" >

  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>

function js_verificacodigo(){
  CodBase = document.form1.r08_base.value;
  DBase   = CodBase.substr(0,1);
  NBase   = CodBase.substr(1,3);
  if(DBase != "B" || isNaN(NBase)){
    alert("Base inválida.");
    return false;
  }
  return true;
}

function js_pesquisa(){
  <?php
  $altura = 0;
  if($db_opcao == 3 || $db_opcao == 33){
    $altura = 20;
  }
  ?>
  js_OpenJanelaIframe('','db_iframe_rhbases','func_bases.php?funcao_js=parent.js_preenchepesquisa|r08_codigo&instit=<?=db_getsession("DB_instit")?>','Pesquisa',true,<?=$altura?>);
}

function js_preenchepesquisa(chave){
  db_iframe_rhbases.hide();
  <?php
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

/**
 *  Função para validar o campo r08_codigo
 *  não será permitida inclusão / alteração ou exclusao,
 *  se o valor vor acima de B900
 */
function valida_r08_codigo() {

 var valor          = document.getElementById('r08_codigo').value;
 var ivalor         = valor.substr(1,3);
 var iAdministrador = <?php echo db_getsession('DB_administrador') ?>;

 if ( ivalor > 900 && iAdministrador != '1') {

   document.getElementById('db_opcao').style.display = 'none';
   alert('Valor maior que 900 \n Impossivel Incluir, Alterar ou Excluir');
   return false;
 } else {

   document.getElementById('db_opcao').style.display = 'inline';
   return true;
 }
}

</script>
