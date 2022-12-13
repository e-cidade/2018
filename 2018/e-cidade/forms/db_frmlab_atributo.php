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

$cllab_atributo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("la26_i_exameatributopai");

if( !isset($la25_c_estrutural) ) {
  $la25_c_estrutural = $la49_c_estrutural;
}

if( !isset($la25_i_nivel) ){
  $la25_i_nivel = "1";
}

$db_opcao2 = $db_opcao;

if($db_opcao == 2 || $db_opcao == 22) {
  $db_opcao2 = 3;
}

try {

  if( $db_opcao == 1 ) {

    $aVet = explode(".", $la25_c_estrutural);

    // Retorna o último valor da série do estrutural, retornando 1 caso não haja registros
    $sCampos         = "(coalesce(max(split_part(la25_c_estrutural, '.', 1))::int, 0) + 1) as estrutural";
    $sSqlEstrutural  = $cllab_atributo->sql_query_file(null, $sCampos);
    $rsSqlEstrutural = db_query($sSqlEstrutural);

    if ( !$rsSqlEstrutural ) {
      throw new DBException('Falha ao buscar o estrutural do exame.');
    }

    $sProximo          = db_utils::fieldsMemory($rsSqlEstrutural, 0)->estrutural;
    $iTam              = strlen($aVet[0]);
    $aVet[0]           = str_pad($sProximo, $iTam, "0", STR_PAD_LEFT);
    $la25_c_estrutural = implode(".", $aVet);
  }

} catch (Exception $oErro) {

  $sMessage = urlencode($oErro->getMessage());
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMessage}");
}

?>

<form name="form1" method="post" action="">
  <div class="container">
    <fieldset style="width: 75%;">
      <legend>Estrutural</legend>
      <table class="form-container">
        <tr>
          <td nowrap="nowrap" title="<?=$Tla25_i_codigo?>">
              <?=@$Lla25_i_codigo?>
          </td>
          <td nowrap="nowrap">
              <?=db_input('la25_i_codigo',10,$Ila25_i_codigo,true,'text',3,"")?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" title="<?=$Tla25_c_estrutural?>">
              <?=$Lla25_c_estrutural?>
          </td>
          <td nowrap="nowrap">
              <?=db_input('la25_c_estrutural', 10, $Ila25_c_estrutural, true, 'text', 3, "");?>
              <?=$Lla25_i_nivel?>
              <?=db_input('la25_i_nivel', 1, $Ila25_i_nivel, true, 'text', 3, "");?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Tla26_i_exameatributopai?>">
            <?=db_ancora($Lla26_i_exameatributopai, "js_pesquisala26_i_exameatributopai(true);", $db_opcao2);?>
          </td>
          <td>
          <?php
            db_input('la26_i_exameatributopai', 10, $Ila26_i_exameatributopai, true, 'text', $db_opcao2, " onchange='js_pesquisala26_i_exameatributopai(false);'");
            db_input('la25_c_estrutural_pai', 10, $Ila25_c_estrutural, true, 'text', 3, '');
            db_input('la25_c_descr_pai', 30, $Ila25_c_descr, true, 'text', 3, '');
            db_input('la25_i_nivel_pai', 1, $Ila25_i_nivel, true, 'text', 3, "");
          ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Tla25_c_descr?>">
            <?=$Lla25_c_descr?>
          </td>
          <td>
            <?=db_input('la25_c_descr', 50, $Ila25_c_descr, true, 'text', $db_opcao, "");?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Tla25_c_tipo?>">
            <?=$Lla25_c_tipo?>
          </td>
          <td>
            <?php
              $aTipos = array("1" => "Sintetico", "2" => "Analitico");
              db_select("la25_c_tipo", $aTipos, true, $db_opcao2, "");
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="<?=($db_opcao== 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>"
       type="submit"
       id="db_opcao"
       value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
       <?=($db_botao == false ? "disabled" : "")?>
       onclick="return js_valida()" >
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    <?=db_input('la49_c_estrutural', 10, $Ila25_i_codigo, true, 'hidden', 3, "");?>
  </div>
</form>

<script type="text/javascript">

  F=document.form1;

  function js_valida(){

      if((F.la25_i_nivel.value==<?=$tamanho?>)&&(F.la25_c_tipo.value==1)){
        alert('Este estrutural nao pode ser sintetico!');
      return false;
      }
      if((F.la25_i_nivel.value==1)&&(F.la25_c_tipo.value==2)){
          alert('Este estrutural nao pode ser Analitico!');
        return false;
       }
      return true;
  }

  //lookups

  //Atributo pai
  function js_pesquisala26_i_exameatributopai(mostra){

    if(mostra==true){
      js_OpenJanelaIframe('','db_iframe_lab_atributo','func_lab_atributo.php?sintetico=1&funcao_js=parent.js_mostralab_atributo1|la25_i_codigo|la25_c_descr|la25_c_estrutural|la25_i_nivel|filhos','Pesquisa',true);
    }else{
       if(document.form1.la26_i_exameatributopai.value != ''){
          js_OpenJanelaIframe('','db_iframe_lab_atributo','func_lab_atributo.php?sintetico=1&pesquisa_chave='+document.form1.la26_i_exameatributopai.value+'&funcao_js=parent.js_mostralab_atributo','Pesquisa',false);
       }else{
         F.la25_c_descr_pai.value = '';
         F.la25_c_estrutural_pai.value = '';
         F.la25_i_nivel_pai.value = '';
       }
    }
  }

  function js_mostralab_atributo(chave1,erro,chave2,chave3,filhos) {

    F.la25_c_descr_pai.value = chave1;
    if(erro==true){
      document.form1.la26_i_exameatributopai.focus();
      document.form1.la26_i_exameatributopai.value = '';
    }else{
      F.la25_c_estrutural_pai.value = chave2;
      F.la25_i_nivel_pai.value = chave3;
      F.la25_c_estrutural.value=js_proximoestrutural(chave2,chave3,filhos);
      F.la25_i_nivel=chave3+1;
    }
  }

  function js_mostralab_atributo1(chave1,chave2,chave3,chave4,filhos){

    F.la26_i_exameatributopai.value = chave1;
    F.la25_c_descr_pai.value = chave2;
    F.la25_c_estrutural_pai.value = chave3;
    F.la25_i_nivel_pai.value = chave4;
    F.la25_c_estrutural.value=js_proximoestrutural(chave3,chave4,filhos);
    F.la25_i_nivel.value=parseInt(chave4,10)+1;
    db_iframe_lab_atributo.hide();
  }

  function js_proximoestrutural(pai,nivel,filhos){

    aVet=pai.split('.');
    aVet[nivel]=parseInt(filhos,10)+1;
    estrutural = F.la49_c_estrutural.value;
    aestrutural=estrutural.split('.');
    tam_nivel=aestrutural[nivel].length;
    atual=''+aVet[nivel]
    tam_atual=atual.length;
    dif=tam_nivel-tam_atual;
    zeros='';
    for(x=0;x < dif;x++){zeros+='0';}
    aVet[nivel]=zeros+aVet[nivel];
    novo_estrutural=aVet[0];
    for(x=1;x<aVet.length;x++){
      novo_estrutural+='.'+aVet[x];
    }
    return novo_estrutural;

  }

  function js_pesquisa(){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_lab_atributo','func_lab_atributo.php?funcao_js=parent.js_preenchepesquisa|la25_i_codigo','Pesquisa',true);
  }

  function js_preenchepesquisa(chave){

    db_iframe_lab_atributo.hide();
    <?
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
    ?>
  }

</script>