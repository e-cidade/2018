<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

$cliptucalcpadrao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j01_numcgm");
if($db_opcao == 1){
  $db_action = "cad1_iptucalcpadrao004.php";
}else if($db_opcao == 2 || $db_opcao == 22){
  $db_action = "cad1_iptucalcpadrao005.php";
}else if($db_opcao == 3 || $db_opcao == 33){
  $db_action = "cad1_iptucalcpadrao006.php";
}

?>
<form name="form1" method="post" action="<?=$db_action?>">
  <?php
    db_input('chavepesquisa',10,"",true,'hidden',3,"");
    db_input('forma',10,"",true,'hidden',3,"");
    db_input('j10_anousu',10,$Ij10_anousu,true,'hidden',3,"");
  ?>
<fieldset style="width:410px;">
<legend>Cálculo</legend>
<table border="0">

  <tr>
    <td nowrap style="width:170px;">
        <strong>Matrícula:</strong>
    </td>
    <td>
    <?php
      db_input('j10_matric',10,"",true,'text',3);
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj10_vlrter?>">
       <strong>Valor venal territorial:</strong>
    </td>
    <td>
    <?php
      db_input('j10_vlrter',10,4,true,'text',$db_opcao);
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj10_aliq?>">
       <?=@$Lj10_aliq?>
    </td>
    <td>
    <?php
      db_input('j10_aliq',10,$Ij10_aliq,true,'text',$db_opcao,"");
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj10_perccorre?>">
       <?=@$Lj10_perccorre?>
    </td>
    <td>
		<?php
		  db_input('j10_perccorre',10,$Ij10_perccorre,true,'text',3,"");
		?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj10_perccorre?>">
       <strong>Ano de origem:</strong>
    </td>
    <td>
		<?php
		  db_input('j23_anousu',10,"",true,'text',3,"");
		?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj23_vlrter?>">
       <strong>Valor de origem:</strong>
    </td>
    <td>
		<?php
		  db_input('j23_vlrter',10,"",true,'text',3,"");
		?>
    </td>
  </tr>
  </table>
 </fieldset>

 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_enviar();" />

</form>
<script type="text/javascript">

$("j10_matric").addClassName("field-size5");
$("j10_vlrter").addClassName("field-size5");
$("j10_aliq").addClassName("field-size5");
$("j10_perccorre").addClassName("field-size5");
$("j23_anousu").addClassName("field-size5");
$("j23_vlrter").addClassName("field-size5");

function js_enviar(){

  if( empty($F('j10_vlrter')) || $F('j10_vlrter') <= 0 ){

    alert('Campo Valor venal territorial é de preenchimento obrigatório e não pode ser nulo.');
    return false;
  }

  if( $F('j10_aliq') < 0 ){

    alert('Campo Alíquota de ser maior do que 0.');
    return false;
  }

  return true;
}

function js_pesquisaj10_matric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_iptucalcpadrao','db_iframe_iptubase','func_iptubasealtpadrao.php?funcao_js=parent.js_mostraiptubase1|j01_matric|j01_numcgm','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.j10_matric.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_iptucalcpadrao','db_iframe_iptubase','func_iptubasealtpadrao.php?pesquisa_chave='+document.form1.j10_matric.value+'&funcao_js=parent.js_mostraiptubase','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.j01_numcgm.value = '';
     }
  }
}
function js_mostraiptubase(chave,erro){
  document.form1.j01_numcgm.value = chave;
  if(erro==true){
    document.form1.j10_matric.focus();
    document.form1.j10_matric.value = '';
  }
}
function js_mostraiptubase1(chave1,chave2){
  document.form1.j10_matric.value = chave1;
  document.form1.j01_numcgm.value = chave2;
  db_iframe_iptubase.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_iptucalcpadrao','db_iframe_iptucalcpadrao','func_iptucalcpadrao.php?funcao_js=parent.js_preenchepesquisa|j10_sequencial','Pesquisa',true,'0','1','775','390');
}
function js_preenchepesquisa(chave){
  db_iframe_iptucalcpadrao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>