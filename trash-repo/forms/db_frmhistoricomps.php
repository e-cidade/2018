<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
?>
<form name="form2">
 <table>
  <tr>
   <td valign="top" bgcolor="#CCCCCC">
    <input type="radio" name="opcao" value="ER" onclick="js_direciona1();"
            <?=$db_opcao==1?"checked":""?>> Escolas da rede municipal<br>
   </td>
   <td>
    <input type="radio" name="opcao" value="EF" onclick="js_direciona2();"> Outras escolas<br><br>
   </td>
  </tr>
 </table>
</form>
<script>
function js_direciona1() {

  location.href = "edu1_historicomps001.php?ed62_i_historico=<?=$ed62_i_historico?>&ed29_c_descr=<?=$ed29_c_descr?>"+
                  "&ed29_i_codigo=<?=$ed29_i_codigo?>&ed61_i_aluno=<?=$ed61_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>";

}

function js_direciona2() {

  location.href = "edu1_historicompsfora001.php?ed99_i_historico=<?=$ed62_i_historico?>"+
                  "&ed29_c_descr=<?=$ed29_c_descr?>&ed29_i_codigo=<?=$ed29_i_codigo?>"+
                  "&ed61_i_aluno=<?=$ed61_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>";

}

parent.disciplina.location.href = "edu1_historicodisciplina.php?ed65_i_historicomps=<?=@$chavepesquisa?>";
</script>

<?
if ((isset($ed61_i_escola) && $ed61_i_escola != db_getsession("DB_coddepto"))
     || (isset($situacao) && $situacao == "CONCLUÍDO")) {
  $db_botao = false;
}

$oDaoHistoricoMps->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed61_i_codigo");
$clrotulo->label("ed18_i_codigo");
$clrotulo->label("ed11_i_codigo");
$clrotulo->label("ed57_i_codigo");
$clrotulo->label("ed06_i_codigo");

$lExcluir = false;
?>

 <form name="form1" method="post" action="" class="container">
   <table class="form-container">
     <tr>
       <td nowrap title="<?=@$Ted62_i_codigo?>">
         <?=@$Led62_i_codigo?>
       </td>
       <td>
         <?db_input('ed62_i_codigo', 20, $Ied62_i_codigo, true, 'text', 3, "")?>
       </td>
     </tr>
     <tr>
       <td nowrap title="<?=@$Ted62_i_historico?>">
         <?db_ancora(@$Led62_i_historico, "js_pesquisaed62_i_historico(true);", 3);?>
       </td>
       <td>
         <?db_input('ed62_i_historico', 15, $Ied62_i_historico, true, 'text', 3, " onchange='js_pesquisaed62_i_historico(false);'")?>
         <?db_input('ed29_c_descr', 40, @$Ied29_c_descr, true, 'text', 3, '')?>
         <?db_input('ed29_i_codigo', 40, @$Ied29_i_codigo, true, 'hidden', 3, '')?>
       </td>
     </tr>
     <tr>
       <td nowrap title="<?=@$Ted62_i_serie?>">
         <?db_ancora(@$Led62_i_serie, "js_pesquisaed62_i_serie(true);", $db_opcao1);?>
       </td>
       <td>
         <?db_input('ed62_i_serie', 15, $Ied62_i_serie, true, 'text', 3, " onchange='js_pesquisaed62_i_serie(false);'")?>
         <?db_input('ed11_c_descr', 20, @$Ied11_c_descr, true, 'text', 3, '')?>
       </td>
     </tr>
     <tr>
       <td nowrap title="<?=@$Ted62_i_anoref?>">
         <?=@$Led62_i_anoref?>
       </td>
       <td>
         <?db_input('ed62_i_anoref', 4, $Ied62_i_anoref, true, 'text', $db_opcao, "")?>
         <?=@$Led62_i_periodoref?>
         <?db_input('ed62_i_periodoref', 4, 1, true, 'text', $db_opcao, "")?>
         <?=@$Led62_i_turma?>
         <?db_input('ed62_i_turma', 30, $Ied62_i_turma, true, 'text', $db_opcao, "")?>
       </td>
     </tr>
     <tr>
       <td nowrap title="<?=@$Ted62_i_escola?>">
         <?db_ancora(@$Led62_i_escola, "js_pesquisaed62_i_escola(true);", $db_opcao);?>
       </td>
       <td>
         <?db_input('ed62_i_escola', 15, $Ied62_i_escola, true, 'text', $db_opcao, " onchange='js_pesquisaed62_i_escola(false);'")?>
         <?db_input('ed18_c_nome', 30, @$Ied18_c_nome, true, 'text', 3, '')?>
       </td>
     </tr>
     <tr>
       <td nowrap title="<?=@$Ted62_c_situacao?>">
         <?=@$Led62_c_situacao?>
       </td>
       <td>
         <?
           $x = array(''=>'', 'CONCLUÍDO'=>'CONCLUÍDO', 'AMPARADO'=>'AMPARADO', 'TRANSFERIDO'=>'TRANSFERIDO',
                      'CANCELADO'=>'CANCELADO', 'EVADIDO'=>'EVADIDO', 'FALECIDO'=>'FALECIDO', 'RECLASSIFICADO' => 'RECLASSIFICADO'
                     );
           db_select('ed62_c_situacao', $x, true, $db_opcao, " onchange='js_situacao(this);'");
         ?>
       </td>
     </tr>
     <tr>
       <td colspan="2">
         <fieldset>
           <legend class="bold">Observação</legend>
         <?php 
           db_textarea( 'ed62_observacao', 4, 10, $Ied62_observacao, true, '', 1, '', '', '', 500 );
         ?>
         </fieldset>
       </td>
     </tr>
     <tr>
       <td colspan="2">
         <div name="situacao" id="situacao">
           <fieldset>
             <legend>
               <input type="text" name="legenda" value="<?=@$ed62_c_situacao?>" size="15"
                     style="border:0px;background:#cccccc;font-weight:bold;text-align:center;">
             </legend>
             <table>
               <tr id="justificativa">
                 <td nowrap title="<?=@$Ted62_i_justificativa?>">
                   <?db_ancora(@$Led62_i_justificativa, "js_pesquisaed62_i_justificativa(true);", $db_opcao);?>
                 </td>
                 <td>
                   <?db_input('ed62_i_justificativa', 10, $Ied62_i_justificativa, true, 'text', $db_opcao,
                              " onchange='js_pesquisaed62_i_justificativa(false);'"
                             )
                   ?>
                   <?db_input('ed06_c_descr', 30, @$Ied06_c_descr, true, 'text', 3, '')?>
                 </td>
               </tr>
               <tr id="resultado">
                 <td nowrap title="<?=@$Ted62_c_resultadofinal?>">
                   <?=@$Led62_c_resultadofinal?>
                 </td>
                 <td>
                   <?
                     $x = array(''=>'', 'A'=>'APROVADO', 'R'=>'REPROVADO', 'P'=>'APROVADO PARCIALMENTE');
                     db_select('ed62_c_resultadofinal', $x, true, $db_opcao, "");
                   ?>
                 </td>
                 <td>
                   <?=@$Led62_i_diasletivos?>
                 </td>
                 <td>
                   <?db_input('ed62_i_diasletivos', 10, $Ied62_i_diasletivos, true, 'text', $db_opcao, "")?>
                 </td>
               </tr>
               <tr>
                 <td nowrap title="<?=@$Ted62_i_qtdch?>">
                   <?=@$Led62_i_qtdch?>&nbsp;&nbsp;&nbsp;
                 </td>
                 <td>
                   <?db_input('ed62_i_qtdch', 10, $Ied62_i_qtdch, true, 'text', $db_opcao, "");?>
                 </td>
                 <td nowrap title="<?=@$Ted62_c_minimo?>" id="labelMinimo">
                   <?=@$Led62_c_minimo?>
                 </td>
                 <td id="inputMinimo">
                   <?db_input('ed62_c_minimo', 20, $Ied62_c_minimo, true, 'text', $db_opcao, "");?>
                 </td>
               </tr>
               <tr>
                 <td>
                   <?=@$Led62_c_termofinal?>
                 </td>
                 <td>
                   <?php
                     db_input('ed62_c_termofinal', 10, $Ied62_c_termofinal, true, 'text', $db_opcao, "");
                   ?>
                 </td>
                 <td>
                   <?=$Led62_percentualfrequencia?>
                 </td>
                 <td>
                   <?php
                     db_input('ed62_percentualfrequencia', 6, $Ied62_percentualfrequencia, true, 'text', $db_opcao, 'onblur="js_validaFrequencia(this)"', '', '', '', '5');
                   ?>
                 </td>
               </tr>
             <tr style="display: none;">
               <td>
                 <input id="lExcluir" name="lExcluir" value="<?=$lExcluir;?>">
               </td>
             </tr>
             </table>
           </fieldset>
         </div>
       </td>
     </tr>
   </table>
 <? if ($db_opcao == 1) {?>

      <input name="incluir" type="submit" id="db_opcao" value="Incluir" <?=($db_botao==false?"disabled":"")?>
             onclick="return js_minimoaprov();">

 <? } else {?>

      <input name="alterar" type="submit" id="db_opcao" value="Alterar" <?=($db_botao==false?"disabled":"")?>
             onclick="return js_minimoaprov();">
      <input name="excluir" type="button" id="db_opcao" value="Excluir" <?=($db_botao==false?"disabled":"")?> 
             onclick="js_confirmaExclusao();" >

 <? }?>

</form>
<script>
var sCaminhoMensagem = "educacao.escola.db_frmhistoricomps";

function js_validaFrequencia(oPercentualFrequencia) {

  var aPercentualFrequencia = oPercentualFrequencia.value.split('-');
  if (aPercentualFrequencia.length > 1) {

    alert('Percentual de frequência não pode ser negativo!');
    return false;
  }

  if (oPercentualFrequencia.value > 100.00) {

    alert('Percentual de frequência deve ser no máximo 100%');
    return false;
  }

  if (oPercentualFrequencia.value < 0.00) {

    alert('Percentual de frequência não pode ser negativo!');
    return false;
  }
}
<?
  if (
           isset($ed62_c_situacao) && trim($ed62_c_situacao) == "CONCLUÍDO"
        || isset($ed62_c_situacao) && trim($ed62_c_situacao) == "RECLASSIFICADO"
     ) { ?>

    document.getElementById("resultado").style.display     = "";
    document.getElementById("labelMinimo").style.display   = "";
    document.getElementById("inputMinimo").style.display   = "";
    document.getElementById("justificativa").style.display = "none";
<?} else if(isset($ed62_c_situacao) && trim($ed62_c_situacao) == "AMPARADO") { ?>

    document.getElementById("justificativa").style.display = "";
    document.getElementById("resultado").style.display     = "none";
    document.getElementById("labelMinimo").style.display   = "none";
    document.getElementById("inputMinimo").style.display   = "none";
<?} else {?>
    document.getElementById("situacao").style.display = "none";
<?}?>

function js_situacao(campo) {

  if (campo.value == "CONCLUÍDO" || campo.value == "RECLASSIFICADO") {

    document.getElementById("situacao").style.display      = "";
    document.getElementById("justificativa").style.display = "none";
    document.getElementById("resultado").style.display     = "";
    document.getElementById("labelMinimo").style.display   = "";
    document.getElementById("inputMinimo").style.display   = "";
    document.form1.legenda.value                           = campo.value;
    document.getElementById("ed62_c_resultadofinal").value = "A";
  } else if (campo.value == "AMPARADO") {

    document.getElementById("situacao").style.display      = "";
    document.getElementById("justificativa").style.display = "";
    document.getElementById("resultado").style.display     = "none";
    document.getElementById("labelMinimo").style.display   = "none";
    document.getElementById("inputMinimo").style.display   = "none";
    document.form1.legenda.value                           = "AMPARADO";
    document.getElementById("ed62_c_resultadofinal").value = "";
  } else {

    document.getElementById("situacao").style.display      = "none";
    document.getElementById("justificativa").style.display = "none";
    document.getElementById("resultado").style.display     = "none";
    document.getElementById("labelMinimo").style.display   = "none";
    document.getElementById("inputMinimo").style.display   = "none";
    document.getElementById("ed62_c_resultadofinal").value = "";
  }
}

function js_pesquisaed62_i_escola(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('parent', 'db_iframe_escola', 'func_escola.php?funcao_js=parent.dados.js_mostraescola1|'+
    	                'ed18_i_codigo|ed18_c_nome', 'Pesquisa de Escolas', true
    	               );

  } else {

    if (document.form1.ed62_i_escola.value != '') {

      js_OpenJanelaIframe('parent', 'db_iframe_escola',
    	                  'func_escola.php?pesquisa_chave='+document.form1.ed62_i_escola.value+
    	                  '&funcao_js=parent.dados.js_mostraescola', 'Pesquisa', false
    	                 );

    } else {
      document.form1.ed18_c_nome.value = '';
    }

  }

}

function js_mostraescola(chave, erro) {

  document.form1.ed18_c_nome.value = chave;

  if (erro == true) {

    document.form1.ed62_i_escola.focus();
    document.form1.ed62_i_escola.value = '';

  }

}

function js_mostraescola1(chave1, chave2) {

  document.form1.ed62_i_escola.value = chave1;
  document.form1.ed18_c_nome.value   = chave2;
  parent.db_iframe_escola.hide();

}

function js_pesquisaed62_i_serie(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('parent', 'db_iframe_serie', 'func_seriehist.php?historico='+document.form1.ed62_i_historico.value+
    	                '&funcao_js=parent.dados.js_mostraserie1|ed11_i_codigo|ed11_c_descr', 'Pesquisa de Etapas', true
    	               );

  } else {

    if (document.form1.ed62_i_serie.value != '') {

      js_OpenJanelaIframe('parent', 'db_iframe_serie',
    	                  'func_seriehist.php?historico='+document.form1.ed62_i_historico.value+
    	                  '&pesquisa_chave='+document.form1.ed62_i_serie.value+'&funcao_js=parent.dados.js_mostraserie',
    	                  'Pesquisa', false
    	                 );

    } else {
      document.form1.ed11_c_descr.value = '';
    }

  }

}

function js_mostraserie(chave, erro) {

  document.form1.ed11_c_descr.value = chave;
  if (erro == true) {

    document.form1.ed62_i_serie.focus();
    document.form1.ed62_i_serie.value = '';

  }

}

function js_mostraserie1(chave1, chave2) {

  document.form1.ed62_i_serie.value = chave1;
  document.form1.ed11_c_descr.value = chave2;
  parent.db_iframe_serie.hide();

}

function js_pesquisaed62_i_turma(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('parent', 'db_iframe_turma', 'func_turma.php?funcao_js=parent.dados.js_mostraturma1|'+
    	                'ed57_i_codigo|ed57_c_descr', 'Pesquisa de Turmas', true
    	               );

  } else {

    if (document.form1.ed62_i_turma.value != '') {

      js_OpenJanelaIframe('parent', 'db_iframe_turma', 'func_turma.php?pesquisa_chave='+document.form1.ed62_i_turma.value+
    	                  '&funcao_js=parent.dados.js_mostraturma', 'Pesquisa', false
    	                 );

    } else {
      document.form1.ed57_c_descr.value = '';
    }

  }

}

function js_mostraturma(chave, erro) {

  document.form1.ed57_c_descr.value = chave;
  if (erro == true) {

    document.form1.ed62_i_turma.focus();
    document.form1.ed62_i_turma.value = '';

  }

}

function js_mostraturma1(chave1, chave2) {

  document.form1.ed62_i_turma.value = chave1;
  document.form1.ed57_c_descr.value = chave2;
  parent.db_iframe_turma.hide();

}

function js_pesquisaed62_i_justificativa(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('parent', 'db_iframe_justificativa',
    	                'func_justificativa.php?funcao_js=parent.dados.js_mostrajustificativa1|'+
    	                'ed06_i_codigo|ed06_c_descr', 'Pesquisa de Justificativas', true
    	               );

  } else {

    if (document.form1.ed62_i_justificativa.value != '') {

      js_OpenJanelaIframe('parent', 'db_iframe_justificativa',
    	                  'func_justificativa.php?pesquisa_chave='+document.form1.ed62_i_justificativa.value+
    	                  '&funcao_js=parent.dados.js_mostrajustificativa', 'Pesquisa', false
    	                 );

    } else {
      document.form1.ed06_c_descr.value = '';
    }

  }

}

function js_mostrajustificativa(chave, erro) {

  document.form1.ed06_c_descr.value = chave;
  if (erro == true) {

    document.form1.ed62_i_justificativa.focus();
    document.form1.ed62_i_justificativa.value = '';

  }

}

function js_mostrajustificativa1(chave1, chave2) {

  document.form1.ed62_i_justificativa.value = chave1;
  document.form1.ed06_c_descr.value         = chave2;
  parent.db_iframe_justificativa.hide();

}

function js_novaescola() {

  js_OpenJanelaIframe('parent', 'db_iframe_novaescola', 'edu1_escolaprocnova001.php',
		              'Nova Escola de Procedência', true, 0, 0, 780, 405
		             );

}

function js_minimoaprov() {

  if (document.form1.ed62_c_situacao.value == "CONCLUÍDO" && document.form1.ed62_c_minimo.value == "") {

    alert("Preencha o campo Mínimo para Aprovação");
    return false;
  }

  if (document.getElementById('ed62_i_escola').value == '') {

    alert('Preencha a escola.');
    return false;
  }

  if (document.getElementById('ed62_i_serie').value == '') {

    alert('Preencha a etapa.');
    return false;
  }

  if (document.getElementById('ed62_i_anoref').value == '') {

    alert('Preencha o ano.');
    return false;
  }

  return true;

}

function $ ( sElemento ) {
  return document.getElementById(sElemento);
}

$('ed62_i_codigo')      .setAttribute("class", 'field-size2');
$('ed62_i_historico')   .setAttribute("class", 'field-size2');
$('ed29_c_descr')       .setAttribute("class", 'field-size7');
$('ed62_i_serie')       .setAttribute("class", 'field-size2');
$('ed11_c_descr')       .setAttribute("class", 'field-size7');
$('ed62_i_anoref')      .setAttribute("class", 'field-size2');
$('ed62_i_periodoref')  .setAttribute("class", 'field-size2');
$('ed62_i_turma')       .style.width = '118px';
$('ed62_i_escola')      .setAttribute("class", 'field-size2');
$('ed18_c_nome')        .setAttribute("class", 'field-size7');
$('ed62_c_situacao')    .setAttribute("rel", 'ignore-css');
$('ed62_c_situacao')    .setAttribute("class", 'field-size9');

function js_confirmaExclusao() {

  if ( confirm( _M( sCaminhoMensagem+".confirma_exclusao" ) ) ) {
    
    $('lExcluir').value = true;
    document.form1.submit();
  }
}
</script>