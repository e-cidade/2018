/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

DBViewRemocaoPeriodoGradeHorario = function (iTurma, iEtapa, iRegenciaHorario, iRecHumano) {

  this.oWindow          = {};
  this.iTurma           = iTurma;
  this.iEtapa           = iEtapa;
  this.iRegenciaHorario = iRegenciaHorario;
  this.iRecHumano       = iRecHumano;
  this.callbackSalvar   = function() {};
  this.callbackFechar   = function() {};

  this.sFonteMsg = 'educacao.escola.DBViewRemocaoPeriodoGradeHorario.';
};

DBViewRemocaoPeriodoGradeHorario.prototype.criaWindow = function () {

  var oSelf    = this;
  this.oWindow = new windowAux("wndGradeHorario", "Remoção de período da grade de horários", 750, 350 );
  this.oWindow.allowCloseWithEsc(true);

  var sConteudo  = "<div class='container'>                                                         ";
      sConteudo += "  <form method='post'>                                                          ";
      sConteudo += "    <fieldset>                                                                  ";
      sConteudo += "      <legend>Remoção de Períodos</legend>                                      ";
      sConteudo += "      <table class='form-container'>                                            ";
      sConteudo += "        <tr>                                                                    ";
      sConteudo += "          <td>                                                                  ";
      sConteudo += "            <label for='tipoRemocao'>Tipo: </label>                             ";
      sConteudo += "          </td>                                                                 ";
      sConteudo += "          <td>                                                                  ";
      sConteudo += "            <select id='tipoRemocao'>                                           ";
      sConteudo += "              <option value='1'>Remoção permanente</option>                     ";
      sConteudo += "              <option value='2'>Vigência do período até a data</option>         ";
      sConteudo += "            </select>                                                           ";
      sConteudo += "          </td>                                                                 ";
      sConteudo += "        </tr>                                                                   ";
      sConteudo += "        <tr id='linhaData' style='display:none'>                                ";
      sConteudo += "          <td>                                                                  ";
      sConteudo += "            <label for='dataFinalPeriodo'>Data: </label>                        ";
      sConteudo += "          </td>                                                                 ";
      sConteudo += "          <td>                                                                  ";
      sConteudo += "            <input type='text' id='dataFinalPeriodo' name='dataFinalPeriodo' /> ";
      sConteudo += "          </td>                                                                 ";
      sConteudo += "        </tr>                                                                   ";
      sConteudo += "      </table>                                                                  ";
      sConteudo += "    </fieldset>                                                                 ";
      sConteudo += "  </form>                                                                       ";
      sConteudo += "  <input type='button' value='Confirmar' name='confirmar' id='btnConfirmar' />  ";
      sConteudo += "</div>                                                                          ";

  this.oWindow.setShutDownFunction( function() {

    oSelf.callbackFechar();
    oSelf.oWindow.destroy();
  });

  var sMsg         = _M(this.sFonteMsg + 'titulo_msg');
  var sHelpMsgBox  = _M(this.sFonteMsg + 'msg_help_permanente');;
      sHelpMsgBox += _M(this.sFonteMsg + 'msg_help_periodo');

  this.oWindow.setContent(sConteudo);
  this.oMessageBoard = new DBMessageBoard( 'msgBoardGrade', sMsg, sHelpMsgBox, this.oWindow.getContentContainer() );
  this.oWindow.show();
};

DBViewRemocaoPeriodoGradeHorario.prototype.show = function ( ) {

  this.criaWindow();
  var oSelf = this;

  new DBInputDate( $('dataFinalPeriodo') );

  $('tipoRemocao').addEventListener('change', function () {

    if ( this.value == 1 ) {

      $('linhaData').style.display = 'none';
      return;
    }

    $('linhaData').style.display = 'table-row';
  });

  $('btnConfirmar').addEventListener('click', function () {
    oSelf.confirmarRemocao();
  });
};

DBViewRemocaoPeriodoGradeHorario.prototype.confirmarRemocao = function ( ) {

  var oSelf        = this;
  var oParamentros = {exec: 'removerPeriodo', iTipo: $F('tipoRemocao'), sData : '',
    iTurma : this.iTurma, iEtapa : this.iEtapa, iRegenciaHorario : this.iRegenciaHorario, iRecHumano : this.iRecHumano
  };

  if ( oParamentros.iTipo == 1 && !confirm( _M(this.sFonteMsg + 'confirmar_remocao_permanente') ) ) {
    return false;
  }

  if ( oParamentros.iTipo == 2 && empty($F('dataFinalPeriodo'))) {

    alert ( _M(this.sFonteMsg + 'informe_data') );
    return;
  }

  if ( oParamentros.iTipo == 2 ) {

    oParamentros.exec  = 'removerPeriodoAteData';
    oParamentros.sData = $F('dataFinalPeriodo');
  }

  new AjaxRequest('edu4_regenciaHorario.RPC.php', oParamentros, function (oRetorno, lErro) {

    alert(oRetorno.sMessage);
    if ( lErro ) {
      return true;
    }

    oSelf.callbackSalvar();
    oSelf.oWindow.destroy();
  }).setMessage( _M(this.sFonteMsg + 'removendo_periodo') ).execute();
};

DBViewRemocaoPeriodoGradeHorario.prototype.setCallback = function ( callback ) {
  this.callbackSalvar = callback;
};

DBViewRemocaoPeriodoGradeHorario.prototype.setCallBackFechar = function ( callback ) {
  this.callbackFechar = callback;
}
