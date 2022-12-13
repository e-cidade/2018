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

//MODULO: patrim
require_once(modification("dbforms/db_classesgenericas.php"));
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clbensmater->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("e60_codemp");
$clrotulo->label("e60_numemp");

?>
<fieldset>
<legend><b>Dados do Material</b></legend>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tt53_codbem?>">
       <?=@$Lt53_codbem?>
    </td>
    <td>
<?
db_input('t53_codbem',8,$It53_codbem,true,'text',3,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt53_ntfisc?>">
       <?=@$Lt53_ntfisc?>
    </td>
    <td>
<?
db_input('t53_ntfisc',51,$It53_ntfisc,true,'text',$db_opcao);
?>
    </td>
  </tr>

 <?
 /*
  <tr>
    <td nowrap title="<?=@$Te60_codemp?>">
       <?
       db_ancora(@$Le60_codemp,"js_pesquisat53_empen(true,'cod');",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('e60_codemp',12,$Ie60_codemp,true,'text',$db_opcao," onchange='js_pesquisat53_empen(false,\"cod\");'")
?>
    </td>
  </tr>
  */
 ?>

  <tr>
    <td>
     <b> Empenho do Sistema : </b>
    </td>
    <td>
      <select id="emp_sistema" name="emp_sistema" style="width: 80px;" onChange='js_mudaProc(this.value);'>
        <option value="s">Sim</option>
        <option value="n">Não</option>
      </select>
    </td>
  </tr>


  <tr>
    <td nowrap title="<?=@$Te60_numemp?>" id="tdAncoraEmpenho">
      <label style="font-weight: bold;" id='procAdm'>
        <? db_ancora(@$Le60_numemp,"js_pesquisae60_numemp(true,'emp');",$db_opcao); ?>
       </label>

      <label style="font-weight: bold; display: none;" id='procAdm1'>
	       Seq. Empenho:
       </label>
    </td>
    <td>
       <?
          db_input('e60_numemp',8,$Ie60_numemp,true,'text',$db_opcao," onchange='js_pesquisae60_numemp(false,\"emp\");'");
        echo "<span id='procSis'>";
          db_input('z01_nome',40,$Iz01_nome,true,'text',3,"");
        echo "</span>";
       ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Te60_codemp?>">
      <label class="bold">
        <?=$Le60_codemp?>
       </label>
    </td>
    <td>
       <?
         db_input('e60_codemp', 8, $Ie60_codemp, true, 'text', 3, "");
       ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tt53_ordem?>">
       <?=@$Lt53_ordem?>
    </td>
    <td>
<?
db_input('t53_ordem',11,$It53_ordem,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt53_garant?>">
       <?=@$Lt53_garant?>
    </td>
    <td>
<?
db_inputdata('t53_garant',@$t53_garant_dia,@$t53_garant_mes,@$t53_garant_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
</table>
</fieldset>
<table>
  <tr>
    <td colspan="2" align="center">
<input name="<?=($db_opcao==1?"incluir":"alterar")?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":"Alterar")?>" <?=($db_botao==false||(isset($tipo_inclui)&&$tipo_inclui=="true"||isset($global) && $global=="true")?"disabled":"")?>>
<input name="excluir" type="submit" id="db_opcao" value="Excluir" <?=(($db_opcao==1||$db_opcao==22||$db_opcao==33||(isset($tipo_inclui)&&$tipo_inclui=="true")||isset($global) && $global=="true")?"disabled":"")?>>
    </td>
  </tr>
  </table>
  </center>
</form>

<script>

/*
 *  Função que valida se o empenho é do sistema ou nao
 *  se for SIM (opção padrao) disponibiliza o campo para pesquisa do empenho
 *  se for NAO disponibiliza apenas um campo varchar(20) -  (não obrigatório) para digitar o numero do empenho.
*/

function js_mudaProc(sTipoProc){
  $('e60_numemp').value = '';
  $('z01_nome').value = '';
  if ( sTipoProc == 's') {
    $('procSis').style.display = '';
    $('procAdm1').style.display = 'none';
    $('procAdm').style.display = '';
  } else {
    $('procSis').style.display = 'none';
    $('procAdm').style.display = 'none';
    $('procAdm1').style.display = '';
  }

}

function js_pesquisae60_numemp(mostra,opcao){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_bensmater','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_mostraempempenho1|e60_numemp|e60_codemp|e60_anousu|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.e60_numemp.value != '' && opcao == 'emp'){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_bensmater','db_iframe_empempenho','func_empempenhopat.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
     }else if(document.form1.e60_codemp.value != '' && opcao == 'cod'){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_bensmater','db_iframe_empempenho','func_empempenhopat.php?funcao_js=parent.js_mostraempempenho1|e60_numemp|e60_codemp|e60_anousu|z01_nome&chave_e60_codemp='+document.form1.e60_codemp.value,'Pesquisa',true);
     }else{
       document.form1.z01_nome.value = '';
       document.form1.e60_numemp.value = '';
       document.form1.e60_codemp.value = '';
     }
  }
}
function js_mostraempempenho(chave,chave2,chave3,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.e60_numemp.focus();
    document.form1.e60_numemp.value = '';
    document.form1.e60_codemp.value = '';
  }else{
    document.form1.e60_codemp.value = chave2+"/"+chave3;
  }
}
function js_mostraempempenho1(chave1,chave2,chave3,chave4){

  document.form1.e60_numemp.value = chave1;
  document.form1.e60_codemp.value = chave2+"/"+chave3;
  document.form1.z01_nome.value = chave4;
  db_iframe_empempenho.hide();
}

function getCodigoItemNaNota() {

  if ($F('t53_codbem') == "") {
    return;
  }
  js_divCarregando("Aguarde, verificando vínculo do bem com empenho do sistema...", "msgBox");

  var oParam = {"exec":"getCodigoDoItemNaNota", "iCodigoBem": $F('t53_codbem')};

  new Ajax.Request("pat1_bensnovo.RPC.php",
                    {method: 'post',
                      parameters: 'json='+Object.toJSON(oParam),
                      async     : false,
                      onComplete: function (oAjax) {

                        js_removeObj("msgBox");
                        var oRetorno = eval("("+oAjax.responseText+")");
                        if (oRetorno.lEmpenhoVinculado) {
                          getDadosEmpenho(oRetorno.iCodigoItemNaNota);
                        }

                      }
                    });

}


function getDadosEmpenho (iCodigoItemNota) {

  js_divCarregando("Aguarde, verificando vínculo do bem com empenho do sistema...", "msgBox");

  var oParam = {"exec" : "getDadosItemNota", "iCodigoItemNota" : iCodigoItemNota };

  new Ajax.Request("pat1_bensnovo.RPC.php",
                  {method: 'post',
                    parameters: 'json='+Object.toJSON(oParam),
                    onComplete: preencheFormularioComDadosDoEmpenho
                  });

}



getCodigoItemNaNota();

function preencheFormularioComDadosDoEmpenho(oAjax) {

  js_removeObj("msgBox");

  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.erro) {
    return alert(oRetorno.message.urlDecode());
  }

  oRetorno = oRetorno.aNotas[0];

  $("emp_sistema").disabled = true;
  $("e60_numemp").readOnly  = true;
  $("t53_ntfisc").readOnly  = true;
  $("t53_ordem").readOnly   = true;


  $("e60_numemp").value = oRetorno.e69_numemp;
  $("t53_ntfisc").value = oRetorno.nota_fiscal.urlDecode();
  $("t53_ordem").value  = oRetorno.ordem_compra;

  $("t53_ntfisc").style.color = "#000";
  $("e60_numemp").style.color = "#000";
  $("t53_ordem").style.color  = "#000";

  $("e60_numemp").style.backgroundColor = "#DEB887";
  $("t53_ntfisc").style.backgroundColor = "#DEB887";
  $("t53_ordem").style.backgroundColor  = "#DEB887";

  $("tdAncoraEmpenho").innerHTML = "<b>Seq. Empenho:</b>";
  js_pesquisae60_numemp(false,"emp");
}





<?
if(isset($incluir) || isset($excluir)){
  $clbensmater->sql_record($clbensmater->sql_query_file($t53_codbem));
  if($clbensmater->numrows > 0){
    echo "(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_bensimoveis.location.href='pat1_bensimoveis001.php?desabilita=true&t54_codbem=$t53_codbem';";
  }else{
    if(isset($excluir)){
      echo "(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_bensimoveis.location.href='pat1_bensimoveis001.php?t54_codbem=$t53_codbem';";
    }
  }
}
?>
</script>