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

//MODULO: educação
$oDaoTransfEscolaRede->rotulo->label();
$oClRotulo = new rotulocampo;
$oClRotulo->label("ed18_i_codigo");
$oClRotulo->label("ed102_i_codigo");
$oClRotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
  <table border="0">
    <tr>
      <td nowrap title="<?=@$Ted103_i_codigo?>">
        <?=@$Led103_i_codigo?>
      </td>
      <td>
        <? db_input('ed103_i_codigo', 15, $Ied103_i_codigo, true, 'text', 3, "") ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Ted103_i_escolaorigem?>">
        <? db_ancora(@$Led103_i_escolaorigem, "", 3); ?>
      </td>
      <td>
        <? db_input('ed103_i_escolaorigem', 15, $Ied103_i_escolaorigem, true, 'text', 3, "") ?>
        <? db_input('ed18_c_nome', 50, @$Ied18_c_nome, true, 'text', 3, '') ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Ted103_i_atestvaga?>">
        <? db_ancora(@$Led103_i_atestvaga, "js_pesquisaed103_i_atestvaga(true);", $db_opcao); ?>
      </td>
      <td>
        <? db_input('ed103_i_atestvaga', 15, $Ied103_i_atestvaga, true, 'text', $db_opcao, 
                    " onchange='js_pesquisaed103_i_atestvaga();'"
                   )
        ?>
        <? db_input('ed47_v_nome', 50, @$Ied47_v_nome, true, 'text', 3, '') ?>
        <? db_input('ed47_i_codigo', 15, @$Ied47_i_codigo, true, 'hidden', 3, '') ?>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <fieldset style="width:95%;">
          <table>
            <tr>
              <td>
                <b>Matrícula Atual:</b>
              </td>
              <td>
                <? db_input('matricula', 40, @$matricula, true, 'text', 3, '') ?>
              </td>
            </tr>
            <tr>
              <td>
                <b>Etapa / Turma Atual:</b>
              </td>
              <td>
                <? db_input('turma', 40, @$turma, true, 'text', 3, '') ?>
                <? db_input('base', 40, @$base, true, 'hidden', 3, '') ?>
                <? db_input('calendario', 40, @$calendario, true, 'hidden', 3, '') ?>
                <? db_input('concluida', 40, @$concluida, true, 'hidden', 3, '') ?>
              </td>
            </tr>
            <tr>
              <td>
                <b>Situação Atual:</b>
              </td>
              <td>
                <? db_input('situacao', 20, @$situacao, true, 'text', 3, '') ?>
                <b>Data Matrícula:</b>
                <? db_input('datamatricula', 10, @$datamatricula, true, 'text', 3, '') ?>
                <? db_input('datamodif', 10, @$datamodif, true, 'hidden', 3, '') ?>
              </td>
            </tr>
            <tr>
              <td>
                <b>Calendário Atual:</b>
              </td>
              <td>
                <? db_input('caldescr', 20, @$situacao, true, 'text', 3, '') ?>
                <b>Início:</b>
                <? db_input('ed52_d_inicio', 10, @$ed52_d_inicio, true, 'text', 3, '') ?>
                <b>Final:</b>
                <? db_input('ed52_d_fim', 10, @$ed52_d_fim, true, 'text', 3, '') ?>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <hr>
              </td>
            </tr>
            <tr>
              <td>
                <b>Escola Destino:</b>
              </td>
              <td>
                <? db_input('codigoescola', 15, @$codigoescola, true, 'text', 3, '') ?>
                <? db_input('nomeescola', 40, @$nomeescola, true, 'text', 3, '') ?>
              </td>
            </tr>
            <tr>
              <td>
                <b>Etapa Destino:</b>
              </td>
              <td>
                <? db_input('codigoserie', 15, @$codigoserie, true, 'text', 3, '') ?>
                <? db_input('nomeserie', 40, @$nomeserie, true, 'text', 3, '') ?>
              </td>
            </tr>
            <tr>
              <td>
                <b>Turno Destino:</b>
              </td>
              <td>
                <? db_input('codigoturno', 15, @$codigoturno, true, 'text', 3, '') ?>
                <? db_input('nometurno', 40, @$nometurno, true, 'text', 3, '') ?>
                <? db_input('codigobase', 40, @$codigobase, true, 'hidden', 3, '') ?>
                <? db_input('codigocalendario', 40, @$codigocalendario, true, 'hidden', 3, '') ?>
              </td>
            </tr>
            <tr>
              <td>
                <b>Data Atestado:</b>
              </td>
              <td>
                <? db_input('dataatestado', 10, @$dataatestado, true, 'text', 3, '') ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
  <tr>
    <td nowrap title="<?=@$Ted103_d_data?>">
      <?=@$Led103_d_data?>
    </td>
    <td>
      <? db_inputdata('ed103_d_data', @$ed103_d_data_dia, @$ed103_d_data_mes, @$ed103_d_data_ano,
                      true,'text', $db_opcao, ""
                     ) 
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted103_t_obs?>">
      <?=@$Led103_t_obs?>
    </td>
    <td>
      <? db_textarea('ed103_t_obs', 4, 63, $Ied103_t_obs, true, 'text', $db_opcao, "") ?>
    </td>
  </tr>
  <tr>
    <td>
      <b>Emissor:</b>
    </td>
    <td>
      <?=Assinatura(db_getsession("DB_coddepto"))?> (Informação para a Guia de Transferência)
    </td>
  </tr>
  <tr>
    <?
      $sSqlQuery = $oDaoObsTransferencia->sql_query("",
                                                    "ed283_t_mensagem",
                                                    "",
                                                    " ed283_i_escola = $iEscola"
                                                   );
      $rsResult = $oDaoObsTransferencia->sql_record($sSqlQuery);      
      if ($oDaoObsTransferencia->numrows > 0) {
  
        $obs = db_utils::fieldsmemory($rsResult, 0)->ed283_t_mensagem;
     
      }
    ?>
    <td colspan="3">
      <b>Bolsa Família:</b>
    <?
      $x = array("1" => "NÃO", "2" => "SIM");
      db_select('ed283_c_bolsafamilia', $x, true, @$db_opcao, "");
    ?>
    </td>
  </tr>
  <tr>
    <td valign="top" colspan="3">
      <b>Observação Geral:</b><br>
      <? db_textarea('obs', 3, 90, @$obs, true, 'text', @$db_opcao, "") ?><br />
    </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>" 
       type="submit" id="db_opcao" value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || 
       $db_opcao == 22 ? "Alterar" : "Excluir"))?>" <?=($db_botao == false ? "disabled" : "")?> 
       onclick="return js_submit()" <?=isset($incluir) ? "style='visibility:hidden;'" : ""?>>
</form>

<script>

sUrl = 'edu4_escola.RPC.php';

function js_getDadosLookup(ed47_i_codigo, ed102_i_base, ed102_i_calendario, ed102_d_data, ed18_i_codigo, ed18_c_nome,
                           ed102_i_codigo, ed15_i_codigo, ed15_c_nome, ed11_i_codigo, ed11_c_descr, ed47_v_nome) {

  db_iframe_atestvaga.hide();

  if (ed47_i_codigo != undefined) {

    $('ed103_i_atestvaga').value = ed102_i_codigo;
    $('ed47_i_codigo').value     = ed47_i_codigo;
    $('ed47_v_nome').value       = ed47_v_nome;
    $('codigoescola').value      = ed18_i_codigo;
    $('nomeescola').value        = ed18_c_nome;
    $('codigoserie').value       = ed11_i_codigo;
    $('nomeserie').value         = ed11_c_descr;
    $('codigobase').value        = ed102_i_base;
    $('dataatestado').value      = ed102_d_data.substr(8,2)+"/"+ed102_d_data.substr(5,2)+"/"+ed102_d_data.substr(0,4);
    $('codigoturno').value       = ed15_i_codigo;
    $('nometurno').value         = ed15_c_nome;
    $('codigobase').value        = ed102_i_base;
    $('codigocalendario').value  = ed102_i_calendario;

    $('db_opcao').disabled       = false;

    js_getDadosMatricula(ed47_i_codigo);

  } else {
    $('db_opcao').disabled = true;
    js_limpacampos();
    js_pesquisaed103_i_atestvaga(true);
  }

}

function js_getDadosMatricula(iAluno) {

  var oParam     = new Object();

  oParam.exec    = 'getDadosUltimaMatriculaAluno';
  oParam.iAluno  = iAluno;

  oParam.iEscola = <?=$iEscola?>;
  
  js_webajax(oParam, 'js_retornoGetDadosMatricula', sUrl);

}

function js_retornoGetDadosMatricula(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {

    alert(oRetorno.sMessage.urlDecode());
    return false;

  } else {

    $('matricula').value     = oRetorno.ed60_i_codigo;
    $('turma').value         = oRetorno.ed11_c_descr.urlDecode()+" / "+oRetorno.ed57_c_descr.urlDecode();
    $('base').value          = oRetorno.ed57_i_base;
    $('calendario').value    = oRetorno.ed57_i_calendario;
    $('concluida').value     = oRetorno.ed60_c_concluida.urlDecode();
    $('situacao').value      = oRetorno.ed60_c_situacao.urlDecode();
    $('datamatricula').value = oRetorno.ed60_d_datamatricula;
    $('datamodif').value     = oRetorno.ed60_d_datamodif;

    $('caldescr').value      = oRetorno.ed52_c_descr.urlDecode();
    $('ed52_d_inicio').value = oRetorno.ed52_d_inicio;
    $('ed52_d_fim').value    = oRetorno.ed52_d_fim;

  }

}

function js_limpacampos() {

  $('ed103_i_atestvaga').value = "";
  $('ed47_i_codigo').value     = "";
  $('ed47_v_nome').value       = "";
  $('codigoescola').value      = "";
  $('nomeescola').value        = "";
  $('codigoserie').value       = "";
  $('nomeserie').value         = "";
  $('codigobase').value        = "";
  $('dataatestado').value      = "";
  $('codigoturno').value       = "";
  $('nometurno').value         = "";
  $('codigobase').value        = "";
  $('codigocalendario').value  = "";
  $('matricula').value         = "";
  $('turma').value             = "";
  $('base').value              = "";
  $('calendario').value        = "";
  $('concluida').value         = "";
  $('situacao').value          = "";
  $('datamatricula').value     = "";
  $('datamodif').value         = "";
  $('caldescr').value          = "";
  $('ed52_d_inicio').value     = "";
  $('ed52_d_fim').value        = "";                                          

}

function js_submit() {

  if ($('ed103_i_atestvaga').value == "") {

    alert('Digite ou pesquisa o código do atestado antes de incluir.');
    document.form1.ed103_i_atestvaga.focus();
    document.form1.ed103_i_atestvaga.style.backgroundColor='#99A9AE';
    return false;

  } else if ($('ed103_d_data').value == "") {
    
    alert("Informe a Data da Transferência para prosseguir!");
    document.form1.ed103_d_data.focus();
    document.form1.ed103_d_data.style.backgroundColor='#99A9AE';
    return false;
 
  } else {

    datamat = document.form1.datamatricula.value;
    datatransf = document.form1.ed103_d_data_ano.value+"-"+document.form1.ed103_d_data_mes.value+"-"+
                 document.form1.ed103_d_data_dia.value;
    dataatest = document.form1.dataatestado.value.substr(6,4)+"-"+
                document.form1.dataatestado.value.substr(3,2)+"-"+document.form1.dataatestado.value.substr(0,2);

    if (document.form1.concluida.value != "S") {

      if (document.form1.ed52_d_inicio.value != "") {

        dataini = document.form1.ed52_d_inicio.value.substr(6,4)+"-"+
                  document.form1.ed52_d_inicio.value.substr(3,2)+"-"+
                  document.form1.ed52_d_inicio.value.substr(0,2);
        datafim = document.form1.ed52_d_fim.value.substr(6,4)+"-"+
                  document.form1.ed52_d_fim.value.substr(3,2)+"-"+
                  document.form1.ed52_d_fim.value.substr(0,2);
        check = js_validata(datatransf,dataini,datafim);
        
        if (check == false) {
          
          data_ini = dataini.substr(8,2)+"/"+dataini.substr(5,2)+"/"+dataini.substr(0,4);
          data_fim = datafim.substr(8,2)+"/"+datafim.substr(5,2)+"/"+datafim.substr(0,4);
          alert("Data da Transferência fora do periodo do calendario ( "+data_ini+" a "+data_fim+" ).");
          document.form1.ed103_d_data.focus();
          document.form1.ed103_d_data.style.backgroundColor='#99A9AE';
          return false;
        
        }
      }
    }
    datatransf  = datatransf.substr(0,4)+''+datatransf.substr(5,2)+''+datatransf.substr(8,2);
    if (datamat != "") {

      datamat  = datamat.substr(6,4)+''+datamat.substr(3,2)+''+datamat.substr(0,2);
      if (parseInt(datamat) > parseInt(datatransf)) {

        alert("Data da Transferência menor que a data da matrícula do aluno!");
        document.form1.ed103_d_data.focus();
        document.form1.ed103_d_data.style.backgroundColor='#99A9AE';
        return false;
      
      }
    
    }
    if(dataatest != "") {

      dataatest = dataatest.substr(0,4)+''+dataatest.substr(5,2)+''+dataatest.substr(8,2);
      if (parseInt(dataatest) > parseInt(datatransf)) {

        alert("Data da Transferência menor que a Data do Atestado do aluno!");
        document.form1.ed103_d_data.focus();
        document.form1.ed103_d_data.style.backgroundColor='#99A9AE';
        return false;

      }
    
    }
  
  }
  document.form1.db_opcao.style.visibility = "hidden";
  return true;
}

function js_pesquisaed103_i_atestvaga(mostra){
 
  if(mostra==true){
    
    js_OpenJanelaIframe('top.corpo','db_iframe_atestvaga',
                        'func_atestvagatransf.php?funcao_js=parent.js_getDadosLookup|ed47_i_codigo|ed102_i_base'+
                        '|ed102_i_calendario|ed102_d_data|ed18_i_codigo|ed18_c_nome|ed102_i_codigo|ed15_i_codigo|'+
                        'ed15_c_nome|ed11_i_codigo|ed11_c_descr|ed47_v_nome','Pesquisa',true
                       );
 
  } else {
    if(document.form1.ed103_i_atestvaga.value != ''){
      
      js_OpenJanelaIframe('top.corpo','db_iframe_atestvaga',
                          'func_atestvagatransf.php?pesquisa_chave='+$('ed103_i_atestvaga').value+
                          '&funcao_js=parent.js_getDadosLookup|ed47_i_codigo|ed102_i_base'+
                          '|ed102_i_calendario|ed102_d_data|ed18_i_codigo|ed18_c_nome|ed102_i_codigo|ed15_i_codigo|'+
                          'ed15_c_nome|ed11_i_codigo|ed11_c_descr|ed47_v_nome','Pesquisa',true
                         );

    
    } else {
      document.form1.ed47_v_nome.value = '';
    }
  }

}

<?
  if ($db_opcao != 1) {
    
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  
  }
?>
</script>