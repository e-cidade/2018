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

//MODULO: educação
$clturmaac->rotulo->label();
$clrotulo       = new rotulocampo;
$ed268_i_escola = db_getsession("DB_coddepto");
$result         = $clescola->sql_record($clescola->sql_query($ed268_i_escola));
db_fieldsmemory($result,0);

if ($db_opcao != 1 && isset($ed268_i_codigo) && !empty($ed268_i_codigo)) {

  $sSqlTurmaAc = $clturmaacmatricula->sql_query_censo("",
                                                      "count(*) as qtdmatr",
                                                      "",
                                                      "ed269_i_turmaac = ".@$ed268_i_codigo
                                                     );
  $result4 = $clturmaacmatricula->sql_record($sSqlTurmaAc);
  if ($clturmaacmatricula->numrows > 0) {
    db_fieldsmemory($result4,0);
  }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="100%">
 <tr>
  <td width="220" nowrap title="<?=@$Ted268_i_codigo?>" width="20%">
   <?=@$Led268_i_codigo?>
  </td>
  <td>
   <?db_input('ed268_i_codigo',15,$Ied268_i_codigo,true,'text',3,"")?>
   <?=@$Led268_i_codigoinep?>
   <?db_input('ed268_i_codigoinep',10,$Ied268_i_codigoinep,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted268_c_descr?>">
   <?=@$Led268_c_descr?>
  </td>
  <td>
   <?db_input('ed268_c_descr',80,$Ied268_c_descr,true,'text',$db_opcao,
               " onKeyUp=\"js_ValidaCamposEdu(this,2,'$GLOBALS[Sed268_c_descr]','f','t',event);\"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted268_i_escola?>">
   <?db_ancora(@$Led268_i_escola,"",3);?>
  </td>
  <td>
   <?db_input('ed268_i_escola',15,$Ied268_i_escola,true,'text',3,"")?>
   <?db_input('ed18_c_nome',40,@$Ied18_c_nome,true,'text',3,'')?>
  </td>
 </tr>
</table>
<table border="0" width="100%">
 <tr>
  <td width="220">
   <?=@$Led268_i_tipoatend?>
  </td>
  <td>
   <?
   if (@$ed255_i_ativcomplementar == 3) {
     $x = array('5'=>'ATENDIMENTO EDUCACIONAL ESPECIAL - AEE');
   }
   if (@$ed255_i_aee == 3) {
     $x = array('4'=>'ATIVIDADE COMPLEMENTAR');
   }
   if (@$ed255_i_aee == 3 && @$ed255_i_ativcomplementar == 3) {
     $x = array(''=>'');
   }
   if ((@$ed255_i_aee != 3 && @$ed255_i_ativcomplementar != 3) || ($db_opcao == 2 || $db_opcao == 3)) {
     $x = array('4'=>'ATIVIDADE COMPLEMENTAR',
                '5'=>'ATENDIMENTO EDUCACIONAL ESPECIAL - AEE'
               );
   }
   db_select('ed268_i_tipoatend',$x,true,$db_opcao1," onchange='js_tipoatend(this.value)'");
   ?>
  </td>
  <td rowspan="9">
   <table width="100%">
    <tr>
     <td valign="top">
      <?
      if (!isset($ed268_c_aee)) {
        $ed268_c_aee = "00000000000";
      }
      if (isset($ed268_i_tipoatend) && $ed268_i_tipoatend == 5) {
        $visible = "visible";
      } else {
        $visible = "hidden";
      }
      ?>
      <fieldset id="AEE" style="padding:0px;visibility:<?=$visible?>;"><legend><?=$Led268_c_aee?></legend>
       <input <?=substr(@$ed268_c_aee,0,1)=="1"?"checked":""?> style="height:13px;" id="ed268_c_aee"
              name="ed268_c_aee[]" type="checkbox" value="1"> Ensino do Sistema Braille<br>
       <input <?=substr(@$ed268_c_aee,2,1)=="1"?"checked":""?> style="height:13px;" id="ed268_c_aee"
              name="ed268_c_aee[]" type="checkbox" value="3"> Ensino do uso de recursos ópticos e não ópticos<br>
       <input <?=substr(@$ed268_c_aee,3,1)=="1"?"checked":""?> style="height:13px;" id="ed268_c_aee"
              name="ed268_c_aee[]" type="checkbox" value="4"> Estratégias para o desenvolvimento de processos mentais<br>
       <input <?=substr(@$ed268_c_aee,4,1)=="1"?"checked":""?> style="height:13px;" id="ed268_c_aee"
              name="ed268_c_aee[]" type="checkbox" value="5"> Técnicas de orientação e mobilidade<br>
       <input <?=substr(@$ed268_c_aee,5,1)=="1"?"checked":""?> style="height:13px;" id="ed268_c_aee"
              name="ed268_c_aee[]" type="checkbox" value="6"> Ensino de Língua Brasileira de Sinais - Libras<br>
       <input <?=substr(@$ed268_c_aee,6,1)=="1"?"checked":""?> style="height:13px;" id="ed268_c_aee"
              name="ed268_c_aee[]" type="checkbox" value="7"> Ensino de uso da Comunicação Alternativa e Aumentativa - CAA<br>
       <input <?=substr(@$ed268_c_aee,7,1)=="1"?"checked":""?> style="height:13px;" id="ed268_c_aee"
              name="ed268_c_aee[]" type="checkbox" value="8"> Estratégias para enriquecimento curricular<br>
       <input <?=substr(@$ed268_c_aee,8,1)=="1"?"checked":""?> style="height:13px;" id="ed268_c_aee"
              name="ed268_c_aee[]" type="checkbox" value="9"> Ensino das técnicas de cálculo no Soroban<br>
       <input <?=substr(@$ed268_c_aee,9,1)=="1"?"checked":""?> style="height:13px;" id="ed268_c_aee"
              name="ed268_c_aee[]" type="checkbox" value="10"> Ensino da usabilidade e das funcionalidades da informática acessível<br>
       <input <?=substr(@$ed268_c_aee,10,1)=="1"?"checked":""?> style="height:13px;" id="ed268_c_aee"
              name="ed268_c_aee[]" type="checkbox" value="11"> Ensino da Língua Portuguesa na modalidade escrita<br>
       <input <?=substr(@$ed268_c_aee,11,1)=="1"?"checked":""?> style="height:13px;" id="ed268_c_aee"
              name="ed268_c_aee[]" type="checkbox" value="12"> Estratégias para autonomia no ambiente escolar<br>
      </fieldset>
     </td>
    </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td>
   <?=@$Led268_i_ativqtd?>
  </td>
  <td>
   <?
   $x = array('1'=>'UMA VEZ POR SEMANA',
              '2'=>'DUAS VEZES POR SEMANA',
              '3'=>'TRÊS VEZES POR SEMANA',
              '4'=>'QUATRO VEZES POR SEMANA',
              '5'=>'CINCO VEZES POR SEMANA'
             );
   db_select('ed268_i_ativqtd',$x,true,$db_opcao,"");
   ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted268_i_calendario?>">
   <?db_ancora(@$Led268_i_calendario,"js_pesquisaed268_i_calendario(true);",$db_opcao1);?>
  </td>
  <td>
   <?db_input('ed268_i_calendario',15,$Ied268_i_calendario,true,'text',$db_opcao1,
              " onchange='js_pesquisaed268_i_calendario(false);'")?>
   <?db_input('ed52_c_descr',20,@$Ied52_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted268_i_turno?>">
   <?db_ancora(@$Led268_i_turno,"js_pesquisaed268_i_turno(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed268_i_turno',15,$Ied268_i_turno,true,'text',$db_opcao," onchange='js_pesquisaed268_i_turno(false);'")?>
   <?db_input('ed15_c_nome',20,@$Ied15_c_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted268_i_sala?>">
   <?db_ancora(@$Led268_i_sala,"js_pesquisaed268_i_sala(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed268_i_sala',15,$Ied268_i_sala,true,'text',$db_opcao," onchange='js_checaDependencia();'")?>
   <?db_input('ed16_c_descr',20,@$Ied16_c_descr,true,'text',3,'')?>
   <?=@$Led16_i_capacidade?>
   <?db_input('ed16_i_capacidade',5,@$Ied16_i_capacidade,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted268_i_numvagas?>">
   <?=@$Led268_i_numvagas?>
  </td>
  <td>
   <?db_input('ed268_i_numvagas',10,$Ied268_i_numvagas,true,'text',$db_opcao," onKeyUp=\"js_calcvagas(event);\"
               onchange=\"js_checaNumVagas();\"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted268_i_nummatr?>">
   <?=@$Led268_i_nummatr?>
  </td>
  <td>
   <?db_input('ed268_i_nummatr',10,$Ied268_i_nummatr,true,'text',3,"")?>
   <b>Vagas Restantes:</b>
   <?db_input('restantes',10,'',true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted268_t_obs?>">
   <?=@$Led268_t_obs?>
  </td>
  <td>
   <?db_textarea('ed268_t_obs',3,50,$Ied268_t_obs,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr id='programaMaisEducacao' style="display: none;">
   <td nowrap title="<?=@$Ted268_programamaiseducacao?>">
    <?=@$Led268_programamaiseducacao?>
   </td>
   <td>
     <?
       $aOpcoes = array(0 => "Não", 1 => "Sim");
       db_select('ed268_programamaiseducacao', $aOpcoes, true, 1);
     ?>
   </td>
 </tr>
</table>
</center>

<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
       type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
       <?=($db_botao==false?"disabled":"")?> onclick="return js_valida();">
<?if (!isset($abre)) { ?>
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar"
           onclick="js_pesquisa();" <?=($db_botao2==false?"disabled":"")?>>
    <input name="novo" type="button" id="novo" value="Novo Registro"
           onclick="js_novo()" <?=$db_opcao==1?"disabled":""?> <?=($db_botao2==false?"disabled":"")?>>
<?}?>
</form>
<script>
if ($F('ed268_i_tipoatend') == 4) {
  $('programaMaisEducacao').style.display = 'table-row';
}

$('ed268_i_tipoatend').observe("change", function() {

  $('programaMaisEducacao').style.display = 'table-row';
  if ($F('ed268_i_tipoatend') != 4) {
    $('programaMaisEducacao').style.display = 'none';
  }
});

function js_pesquisaed268_i_calendario(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('','db_iframe_calendarioturma','func_calendarioturma.php?funcao_js=parent.js_mostracalendario1'+
    	                    '|ed52_i_codigo|ed52_c_descr','Pesquisa de Calendários',true);

  } else {

    if (document.form1.ed268_i_calendario.value != '') {

      js_OpenJanelaIframe('','db_iframe_calendarioturma',
    	                  'func_calendarioturma.php?pesquisa_chave='+document.form1.ed268_i_calendario.value+
    	                  '&funcao_js=parent.js_mostracalendario','Pesquisa',false);

    } else {
      document.form1.ed52_c_descr.value = '';
    }

  }
}

function js_checaDependencia() {

  iDependencia = $('ed268_i_sala').value;

  if (iDependencia == "") {

    $('ed16_c_descr').value      = "";
    $('ed16_i_capacidade').value = "";

  }

  if (parseInt(iDependencia, 10) > 0) {

    var oParam = new Object();

    oParam.exec         = "getDependenciaTurmaAc";
    oParam.iDependencia = iDependencia;

    sUrl                = "edu4_escola.RPC.php";

    js_webajax(oParam, 'js_retornoChecaDependencia', sUrl);

  }

}

function js_retornoChecaDependencia(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {

    alert("Dependência não encontrada, verifique o código da dependência\n ou pesquise!");
    $('ed268_i_sala').value      = "";
    $('ed16_c_descr').value      = "";
    $('ed16_i_capacidade').value = "";
    $('ed268_i_sala').focus();
    return false;

  } else {

    $('ed268_i_sala').value      = oRetorno.ed16_i_codigo;
    $('ed16_c_descr').value      = oRetorno.ed16_c_descr.urlDecode();
    $('ed16_i_capacidade').value = oRetorno.ed16_i_capacidade;

    if ($('ed268_i_numvagas').value == ""
        || $('ed268_i_numvagas').value == "0") {

      $('ed268_i_numvagas').value  = oRetorno.ed16_i_capacidade;

    }

    js_calcvagas();

  }

  return true;

}

function js_checaNumVagas() {

  var iNumMatriculados = $('ed268_i_nummatr').value;
  var iNumVagas        = $('ed268_i_numvagas').value;

  if (parseInt(iNumVagas, 10) < parseInt(iNumMatriculados, 10)) {

    alert("O número de vagas deve ser superior a "+iNumMatriculados+" \npois já existem alunos matriculados"+
           " nesta turma.");
    $('restantes').value        = "0";
    $('ed268_i_numvagas').value = "";
    $('ed268_i_numvagas').focus();
    return false;

  }

  return true;

}

function js_mostracalendario(chave,erro) {

  document.form1.ed52_c_descr.value = chave;

  if (erro == true) {

    document.form1.ed268_i_calendario.focus();
    document.form1.ed268_i_calendario.value = '';

  }
}

function js_mostracalendario1(chave1,chave2) {

  document.form1.ed268_i_calendario.value = chave1;
  document.form1.ed52_c_descr.value       = chave2;
  db_iframe_calendarioturma.hide();

}

function js_pesquisaed268_i_turno(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('','db_iframe_turno','func_turnoturmaac.php?funcao_js=parent.js_mostraturno1|ed15_i_codigo|'+
    	                  'ed15_c_nome','Pesquisa de Turnos',true);

  } else {

    if (document.form1.ed268_i_turno.value != '') {

      js_OpenJanelaIframe('','db_iframe_turno','func_turnoturmaac.php?pesquisa_chave='+$('ed268_i_turno').value+
    	                    '&funcao_js=parent.js_mostraturno','Pesquisa',false);

    } else {
      document.form1.ed15_c_nome.value = '';
    }

  }
}

function js_mostraturno(chave,erro) {

  document.form1.ed15_c_nome.value       = chave;
  document.form1.ed268_i_sala.value      = "";
  document.form1.ed16_c_descr.value      = "";
  document.form1.ed16_i_capacidade.value = "";

  if (erro == true) {

    document.form1.ed268_i_turno.focus();
    document.form1.ed268_i_turno.value = '';

  }

}

function js_mostraturno1(chave1,chave2) {

  document.form1.ed268_i_turno.value     = chave1;
  document.form1.ed15_c_nome.value       = chave2;
  document.form1.ed268_i_sala.value      = "";
  document.form1.ed16_c_descr.value      = "";
  document.form1.ed16_i_capacidade.value = "";
  db_iframe_turno.hide();

}

function js_pesquisaed268_i_sala(mostra){

  if (document.form1.ed268_i_turno.value == "") {

    alert("Informe o Turno!");
    document.form1.ed268_i_sala.value                  = '';
    document.form1.ed268_i_turno.style.backgroundColor = '#99A9AE';
    document.form1.ed268_i_turno.focus();

  } else if (document.form1.ed268_i_calendario.value == "") {

    alert("Informe o Calendário!");
    document.form1.ed268_i_sala.value                       = '';
    document.form1.ed268_i_calendario.style.backgroundColor = '#99A9AE';
    document.form1.ed268_i_calendario.focus();

  } else {

    if (mostra == true) {

      js_OpenJanelaIframe('','db_iframe_sala','func_sala.php?funcao_js=parent.js_mostrasala1|ed16_i_codigo|'+
    	                  'ed16_c_descr|ed16_i_capacidade','Pesquisa de Salas',true);

    } else {

      if (document.form1.ed268_i_sala.value != '') {

        js_OpenJanelaIframe('','db_iframe_sala','func_sala.php?pesquisa_chave='+document.form1.ed268_i_sala.value+
                            '&funcao_js=parent.js_mostrasala|ed16_i_codigo|ed16_c_descr|ed16_i_capacidade','Pesquisa',false);

      } else {
        document.form1.ed16_c_descr.value = '';
      }

    }
  }
}

function js_mostrasala(chave1,erro,chave2) {

  if (erro == true) {

    alert("Dependência não encontrada,\n verifique o código ou pesquise!");
    document.form1.ed268_i_sala.focus();
    document.form1.ed268_i_sala.value = '';

  } else {
    document.form1.ed16_c_descr.value      = chave1;
    document.form1.ed16_i_capacidade.value = chave2;
    document.form1.ed268_i_numvagas.value  = chave2;
    js_calcvagas();
  }

}

function js_mostrasala1(chave1,chave2,chave3) {

 document.form1.ed268_i_sala.value      = chave1;
 document.form1.ed16_c_descr.value      = chave2;
 document.form1.ed16_i_capacidade.value = chave3;
 document.form1.ed268_i_numvagas.value  = chave3;
 js_calcvagas();
 db_iframe_sala.hide();

}

function js_pesquisa() {

  js_OpenJanelaIframe('','db_iframe_turmaac','func_turmaac.php?funcao_js=parent.js_preenchepesquisa|ed268_i_codigo'+
		              '|ed268_i_tipoatend','Pesquisa de Turmas com Atividade Complementar',true);

}

function js_preenchepesquisa(chave,tipo) {

  db_iframe_turmaac.hide();
  <?
  if ($db_opcao != 1) {

    if ($db_opcao == 3 || $db_opcao == 33) {
      $n_arquivo = "003";
    } else {
      $n_arquivo = "002";
    }
    echo " parent.location.href = 'edu1_turmaacabas$n_arquivo.php?chavepesquisa='+chave+'&tipoatendimento='+tipo";
  }
 ?>
}

function js_novo() {
  parent.location.href="edu1_turmaacabas001.php";
}

function js_calcvagas(evento) {

  var iNumVagas        = $('ed268_i_numvagas').value;
  var iCapacidade      = $('ed16_i_capacidade').value;
  var iNumMatriculados = $('ed268_i_nummatr').value;

  if (evento != undefined) {

    var iTecla           = evento.which;

    if (iTecla != 13 && iTecla != 27) {

      if ($('ed268_i_sala').value == "") {

        if (parseInt(iNumVagas, 10) > 0) {

          if (iNumVagas-iNumMatriculados < 0) {
            $('restantes').value = 0;
          } else {
            $('restantes').value = iNumVagas-iNumMatriculados;
          }

        } else {
          alert("Número de vagas disponível deve ser maior que 0 (zero).");
          $('ed268_i_numvagas').value = "";
          $('restantes').value        = "";
        }

      } else {

        if (parseInt(iNumVagas, 10) > 0) {

          if (parseInt(iNumVagas, 10) > parseInt(iCapacidade, 10)) {

            alert('Número maior do que o suportado pela sala.');
            $('ed268_i_numvagas').value = "";
            $('restantes').value        = "";

          } else {
            $('restantes').value = iNumVagas-iNumMatriculados;
          }

        } else {
          alert('Número disponível de vagas precisa ser maior que 0 (zero).');
          $('ed268_i_numvagas').value = "";
          $('restantes').value        = "";
        }

      }

    }

  }

}

function js_tipoatend(valor) {

  if (valor == "5") {
    document.getElementById("AEE").style.visibility = "visible";
  } else {

    document.getElementById("AEE").style.visibility = "hidden";
    document.form1.ed268_c_aee[0].checked           = false;
    document.form1.ed268_c_aee[1].checked           = false;
    document.form1.ed268_c_aee[2].checked           = false;
    document.form1.ed268_c_aee[3].checked           = false;
    document.form1.ed268_c_aee[4].checked           = false;
    document.form1.ed268_c_aee[5].checked           = false;
    document.form1.ed268_c_aee[6].checked           = false;
    document.form1.ed268_c_aee[7].checked           = false;
    document.form1.ed268_c_aee[8].checked           = false;
    document.form1.ed268_c_aee[9].checked           = true;
    document.form1.ed268_c_aee[10].checked          = false;

  }

}

function js_valida() {

  tam  = document.form1.ed268_c_aee.length;
  cont = 0;
  for (i = 0; i < tam; i++) {

    if (document.form1.ed268_c_aee[i].checked == true) {
      cont++;
    }

  }

  var iNumVagas   = document.form1.ed268_i_numvagas.value;
  var iCapacidade = document.form1.ed16_i_capacidade.value;

  js_checaNumVagas();

  if (js_checaDependencia()) {

    if (document.form1.ed268_i_numvagas.value == "") {

      alert("A quantidade de vagas deve ser informado.");
      document.form1.ed268_i_numvagas.focus();
      return false;

    }

    if (document.form1.ed268_i_sala.value != "") {

      if (parseInt(iNumVagas, 10) <= 0) {

        alert("Número de vagas deve ser maior que 0 (zero).");
        document.form1.ed268_i_numvagas.value = "";
        document.form1.ed268_i_numvagas.focus();
        return false;

      } else if (parseInt(iNumVagas, 10) > parseInt(iCapacidade, 10)) {

        alert("Número de vagas de matrícula não podem ser maior\n que a capacidade da dependência.");
        document.form1.ed268_i_numvagas.value = "";
        document.form1.ed268_i_numvagas.focus();
        return false;

      }

    } else {

      if (parseInt(iNumVagas, 10) < 0) {

        alert("Número de vagas deve ser maior que 0 (zero).");
        document.form1.ed268_i_numvagas.value = "";
        document.form1.ed268_i_numvagas.focus();
        return false;

      }

    }

  }

  if (cont == 0 && document.form1.ed268_i_tipoatend.value == 5) {

    alert("Campo Tipo de Atendimento Educ. Especial - AEE não informado!");
    return false;

  }

  return true;
}

function mascara_hora(hora,x) {

  var myhora = '';
  myhora     = myhora + hora;
  if (myhora.length == 2) {

    if ( (myhora < 00 ) || (myhora > 23) ) {

      alert("E R R O !!!\n\nHora inválida!\nPreencha corretamente o campo!");
      document.form1[x].value = "";
      document.form1[x].focus();

    }

    myhora = myhora + ':';
    document.form1[x].value = myhora;
  }

  if (myhora.length == 5) {
    verifica_hora(x);
  }
}

function verifica_hora(x) {

  hrs = (document.form1[x].value.substring(0,2));
  min = (document.form1[x].value.substring(3,5));
  situacao = "";
  // verifica hora
  if ( (hrs < 00 ) || (hrs > 23) || ( min < 00) || ( min > 59) ) {

    alert("E R R O !!!\n\nHora inválida!\nPreencha corretamente o campo!");
    document.form1[x].value = "";
    document.form1[x].focus();

  }
}

if (document.form1.ed268_i_nummatr.value == "") {
  document.form1.ed268_i_nummatr.value = 0;
}

if (document.form1.ed268_i_numvagas.value == "") {
  document.form1.ed268_i_numvagas.value = 0;
}

if (document.form1.ed16_i_capacidade.value == "") {
  document.form1.ed16_i_capacidade.value = 0;
}

if (document.form1.restantes.value == "") {
  document.form1.restantes.value = 0;
}

if (document.form1.ed268_i_numvagas.value-document.form1.ed268_i_nummatr.value < 0) {
  document.form1.restantes.value = 0;
} else {
  document.form1.restantes.value = document.form1.ed268_i_numvagas.value-document.form1.ed268_i_nummatr.value;
}

if (document.form1.ed268_i_tipoatend.length == 1 && document.form1.ed268_i_tipoatend.value == 5) {
  document.getElementById("AEE").style.visibility = "visible";
}
</script>