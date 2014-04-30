<?php
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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_aluno_classe.php");
require_once("classes/db_matricula_classe.php");

$oDaoAluno = db_utils::getDao("aluno");
$oDaoAluno->rotulo->label();
$clrotulo = new rotulocampo();
$clrotulo->label("ed47_i_codigo");
$clrotulo->label("ed60_i_codigo");
$clrotulo->label("ed60_c_situacao");
$clrotulo->label("ed11_c_descr");
$clrotulo->label("ed15_c_nome");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style type="">
      #fieldsetMatriculas {
  	    border:0px;
  	    border-top: 2px groove white;
      }
    </style>
  </head>
  <body bgcolor="#CCCCCC" style='margin-top: 25px'>
    <center>
      <form name='form1' method='post' id='frmCancelaTrocaTurma'>
        <?php 
          if (db_getsession("DB_modulo") == 1100747) {
            MsgAviso(db_getsession("DB_coddepto"),"escola");
          }
        ?>
        <div style='display: table'>
          <fieldset>
            <legend><b>Cancelar Troca de Turma</b></legend>
            <table>
              <tr>
                <td>
                  <?php
                    db_ancora('<b>Aluno: </b>', 'js_pesquisaAluno(true);', 1);
                  ?>
                </td>
                <td>
                  <?php
                    db_input('ed47_i_codigo', 10, $Ied47_i_codigo, true, 'text', 1, "onChange='js_pesquisaAluno(false);'");
                  ?>
                </td>
                <td>
                  <?php
                    db_input('ed47_v_nome', 69, $Ied47_v_nome, true, 'text', 3);
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <input type='hidden' id='codigoMatriculaAluno' >
                </td>
                <td>
                  <input type='hidden' id='codigoTurma' >
                </td>
                <td>
                  <input type='hidden' id='codigoEtapa' >
                </td>
              </tr>
            </table>
            <br>
            <table>
              <tr>
                <fieldset id='fieldsetMatriculas'>
                  <legend><b>Matrículas</b></legend>
                </fieldset>
              </tr>
              <tr>
                <td>
                  <fieldset>
                    <legend>
                      <b>Turma Atual - <label for='turmaAtual' id='turmaAtual'></label></b>
                    </legend>
                    <table>
                      <tr>
                        <td><b>Matrícula: </b></td>
                        <td>
                          <?php
                            db_input('matriculaAtual', 30, $Ied60_i_codigo, true, 'text', 3);
                          ?>
                        </td>
                        <td>
                          <?php
                            db_input('codigoMatriculaAtual', 30, $Ied60_i_codigo, true, 'hidden', 3);
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td><b>Situação: </b></td>
                        <td>
                          <?php
                            db_input('situacaoAtual', 30, $Ied60_c_situacao, true, 'text', 3);
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td><b>Etapa: </b></td>
                        <td>
                          <?php
                            db_input('etapaAtual', 30, $Ied11_c_descr, true, 'text', 3);
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td><b>Turno: </b></td>
                        <td>
                          <?php
                            db_input('turnoAtual', 30, $Ied15_c_nome, true, 'text', 3);
                          ?>
                        </td>
                      </tr>
                    </table>
                  </fieldset>
                </td>
                <td>
                  <fieldset>
                    <legend>
                      <b>Turma Origem - <label for='turmaOrigem' id='turmaOrigem'></label></b>
                    </legend>
                    <table>
                      <tr>
                        <td><b>Matrícula: </b></td>
                        <td>
                          <?php
                            db_input('matriculaOrigem', 30, $Ied60_i_codigo, true, 'text', 3);
                          ?>
                        </td>
                        <td>
                          <?php
                            db_input('codigoMatriculaOrigem', 30, $Ied60_i_codigo, true, 'hidden', 3);
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td><b>Situação: </b></td>
                        <td>
                          <?php
                            db_input('situacaoOrigem', 30, $Ied60_c_situacao, true, 'text', 3);
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td><b>Etapa: </b></td>
                        <td>
                          <?php
                            db_input('etapaOrigem', 30, $Ied11_c_descr, true, 'text', 3);
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td><b>Turno: </b></td>
                        <td>
                          <?php
                            db_input('turnoOrigem', 30, $Ied15_c_nome, true, 'text', 3);
                          ?>
                        </td>
                      </tr>
                    </table>
                  </fieldset>
                </td>
              </tr>
            </table>
          </fieldset>
        </div>
      </form>
      <input type='submit' id='btnConfirmar' name='btnConfirmar' value='Confirmar'>
      <input type='submit' id='btnLimparDados' name='btnLimparDados' value='Limpar Dados' onClick='js_limparDados();'>
    </center>
  </Body>
  <?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</html>
<script>
var sUrl = 'edu4_turmas.RPC.php';

function js_pesquisaAluno(lMostra) {

  if (lMostra) {

    js_OpenJanelaIframe(
                        '',
                        'db_iframe_alunotrocaturma',
                        'func_alunotrocaturma.php?funcao_js=parent.js_mostraAluno1|ed47_i_codigo|ed47_v_nome'
                                                                                +'|ed60_i_codigo|ed57_i_codigo|ed221_i_serie',
                        'Pesquisa Aluno com Troca de Turma',
                        true
                       );
  } else {

    if (document.form1.ed47_i_codigo.value != '') {

      js_OpenJanelaIframe(
                          '',
                          'db_iframe_alunotrocaturma',
                          'func_alunotrocaturma.php?pesquisa_chave='+document.form1.ed47_i_codigo.value+
                                                  '&funcao_js=parent.js_mostraAluno',
                          'Pesquisa Aluno com Troca de Turma',
                          false
                         );
    } else {
      document.form1.ed47_v_nome.value = '';
    }
  }
}

function js_mostraAluno(chave, erro) {

  document.form1.ed47_v_nome.value = chave;
  if (erro == true) {

    document.form1.ed47_i_codigo.focus();
    document.form1.ed47_i_codigo.value = '';
  } else {
    pesquisaDadosAluno();
  }
}

function js_mostraAluno1(chave1, chave2, chave3, chave4, chave5) {

  document.form1.ed47_i_codigo.value        = chave1;
  document.form1.ed47_v_nome.value          = chave2;
  document.form1.codigoMatriculaAluno.value = chave3;
  document.form1.codigoTurma.value          = chave4;
  document.form1.codigoEtapa.value          = chave5;
  db_iframe_alunotrocaturma.hide();
  pesquisaDadosAluno();
}


function pesquisaDadosAluno() {

  var oParametro              = new Object();
  oParametro.exec             = 'pesquisaDadosAluno';
  oParametro.iAluno           = $('ed47_i_codigo').value;
  oParametro.iCodigoMatricula = $('codigoMatriculaAluno').value;
  oParametro.iTurma           = $('codigoTurma').value;
  oParametro.iEtapa           = $('codigoEtapa').value;

  var oAjax = new Ajax.Request(
                               sUrl,
                               {
                                 method: 'post',
                                 parameters: 'json='+Object.toJSON(oParametro),
                                 onComplete: retornaPesquisaDadosAluno
                               }
                              );
}

function retornaPesquisaDadosAluno(oResponse) {

  var oRetorno = eval('('+oResponse.responseText+')');

  $('turmaAtual').innerHTML       = oRetorno.sTurmaAtual.urlDecode();
  $('codigoMatriculaAtual').value = oRetorno.iCodigoAtual;
  $('matriculaAtual').value       = oRetorno.iMatriculaAtual;
  $('situacaoAtual').value        = oRetorno.sSituacaoAtual;
  $('etapaAtual').value           = oRetorno.sEtapaAtual.urlDecode();
  $('turnoAtual').value           = oRetorno.sTurnoAtual.urlDecode();
  
  $('turmaOrigem').innerHTML       = oRetorno.sTurmaOrigem.urlDecode();
  $('codigoMatriculaOrigem').value = oRetorno.iCodigoOrigem;
  $('matriculaOrigem').value       = oRetorno.iMatriculaOrigem;
  $('situacaoOrigem').value        = oRetorno.sSituacaoOrigem;
  $('etapaOrigem').value           = oRetorno.sEtapaOrigem.urlDecode();
  $('turnoOrigem').value           = oRetorno.sTurnoOrigem.urlDecode();
}

function js_limparDados() {

  $('ed47_i_codigo').value = '';
  $('ed47_v_nome').value   = '';
  
  $('turmaAtual').innerHTML = '';
  $('matriculaAtual').value = '';
  $('situacaoAtual').value  = '';
  $('etapaAtual').value     = '';
  $('turnoAtual').value     = '';
  
  $('turmaOrigem').innerHTML = '';
  $('matriculaOrigem').value = '';
  $('situacaoOrigem').value  = '';
  $('etapaOrigem').value     = '';
  $('turnoOrigem').value     = '';
}

/**
 * Executamos o cancelamento da troca de turma, caso os dados tenham sido preenchidos
 */
$('btnConfirmar').observe('click', function(event) {

  if ($('ed47_i_codigo').value == '' || $('ed47_v_nome').value == '') {

    alert('Informe o código e nome do aluno para cancelamento da troca de turma.');
    return false;
  }

  var sMsg  = "Confirma o cancelamento da troca de turma do aluno "+$('ed47_i_codigo').value+" - ";
  sMsg     += $('ed47_v_nome').value.trim()+"?";
  
  if (confirm(sMsg)){
    
    var oParametro              = new Object();
    oParametro.exec             = 'cancelarTrocaDeTurma';
    oParametro.iCodigoMatricula = $('codigoMatriculaAtual').value;
    oParametro.iTurma           = $('codigoTurma').value;
  
    js_divCarregando("Aguarde, o procedimento de cancelamento da troca de turma está sendo executado.", "msgBox");
    var oAjax = new Ajax.Request(
                                 sUrl,
                                 {
                                   method:     'post',
                                   parameters: 'json='+Object.toJSON(oParametro),
                                   onComplete: function (oResponse) {
  
                                     js_removeObj("msgBox");
                                     var oRetorno = eval('('+oResponse.responseText+')');
  
                                     if (oRetorno.status == '2') {
  
                                       alert(oRetorno.message.urlDecode());
                                       return false;
                                     } else {
                                       alert('Cancelamento de troca de turma realizado com sucesso.');
                                     }
                                     
                                     js_limparDados();
                                     return true;
                                   }
                                 }
                                );
  }
});

js_pesquisaAluno(true);
js_limparDados();
</script>