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
/**
 * Namespace para views de formulários para o módulo laboratório
 * @version $Revision: 1.2 $
 */
var Laboratorio = {};

/**
 * Valida se um departamento é um laboratório.
 *
 * Para validar um departamento especifico, informe-o por parâmetro.
 * @default Se não informado um departamento por parâmetro, será validado o departamento logado.
 *          Utilizando-se do db_getsession()
 * @return {bool}
 */
Laboratorio.departamentoIsLaboratorio = function () {

  var oParametro                 = {};
  oParametro.exec                = 'departamentoIsLaboratorio';
  oParametro.iDepartamentoLogado = '';
  if ( arguments[0] ) {
    oParametro.iDepartamentoLogado = arguments[0];
  }

  var oSelf = this;
  oSelf.lIsLaboratorio = false;

  var oRequest        = {};
  oRequest.method     = 'post';
  oRequest.parameters = 'json='+Object.toJSON(oParametro);
  oRequest.onComplete = function(oAjax) {

    var oRetorno = eval( "(" + oAjax.responseText + ")" );
    if ( oRetorno.lIsLaboratorio ) {
      oSelf.lIsLaboratorio = true;
    }

  };
  oRequest.asynchronous = false;

  new Ajax.Request( 'lab4_laboratorioBase.RPC.php' , oRequest );
  return oSelf.lIsLaboratorio;
};

/**
 * Valida se um usuário esta vinculado ao laboratório.
 *
 * Para validar um departamento e (ou) usuário especifico, informe-o por parâmetro.
 * @default Se não informado um departamento por parâmetro, será validado o departamento e usuário logado.
 *          Utilizando-se do db_getsession()
 * @return {bool}
 */
Laboratorio.usuarioIsTecnicoLaboratorio = function (iDepartamento, iUsuario) {

  var oParametro                 = {};
  oParametro.exec                = 'usuarioIsTecnicoLaboratorio';
  oParametro.iDepartamentoLogado = '';
  oParametro.iUsuario            = '';
  if ( iDepartamento && iDepartamento != '' ) {
    oParametro.iDepartamentoLogado = iDepartamento;
  }

  if ( iUsuario && iUsuario != '' ) {
    oParametro.iUsuario = iUsuario;
  }

  var oSelf = this;
  oSelf.lIsTecnicoVinculado = false;

  var oRequest        = {};
  oRequest.method     = 'post';
  oRequest.parameters = 'json='+Object.toJSON(oParametro);
  oRequest.onComplete = function(oAjax) {
    var oRetorno = eval( "(" + oAjax.responseText + ")" );
    if ( oRetorno.lIsVinculado ) {
      oSelf.lIsTecnicoVinculado = true;
    }

  };
  oRequest.asynchronous = false;

  new Ajax.Request( 'lab4_laboratorioBase.RPC.php' , oRequest );

  return oSelf.lIsTecnicoVinculado;
};

/**
 * Retorna os dados do laboratório de acordo com o departamento.
 *
 * Para validar um departamento especifico, informe-o por parâmetro.
 * @default Se não informado um departamento por parâmetro, será validado o departamento logado.
 *          Utilizando-se do db_getsession()
 * @return {Laboratorio}
 */
Laboratorio.getLaboratorioByDepartamento = function() {

  var oParametro                 = {};
  oParametro.exec                = 'getLaboratorioByDepartamento';
  oParametro.iDepartamentoLogado = '';
  if ( arguments[0] ) {
    oParametro.iDepartamentoLogado = arguments[0];
  }

  var oSelf = this;
  var oRequest        = {};
  oRequest.method     = 'post';
  oRequest.parameters = 'json='+Object.toJSON(oParametro);
  oRequest.onComplete = function(oAjax) {

    var oRetorno = eval( "(" + oAjax.responseText + ")" );
    if ( parseInt(oRetorno.iStatus) == 1 ) {

      oSelf.iLaboratorio = oRetorno.iLaboratorio;
      oSelf.sLaboratorio = oRetorno.sLaboratorio;
    }

  };
  oRequest.asynchronous = false;

  new Ajax.Request( 'lab4_laboratorioBase.RPC.php' , oRequest );

  return this;
}




