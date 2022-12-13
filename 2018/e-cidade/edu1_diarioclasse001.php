<?
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

require(modification("libs/db_stdlibwebseller.php"));
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
$db_opcao = 1;
$escola = db_getsession("DB_nomedepto");
if(isset($retorno)){
 $arquivo = "edu1_diarioclasse004.php?turma=$turma&ed57_c_descr=$ed57_c_descr&ed52_c_descr=$ed52_c_descr";
}else{
 $arquivo = "";
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#cccccc" leftmargin="15" marginheight="0" marginwidth="3" topmargin="5">
<table width="100%" height="25"  border="0" cellpadding="0" cellspacing="0">
 <tr>
  <td>&nbsp;</td>
 </tr>
</table>
<table align="left" valign="top" marginwidth="0" width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" >
   <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
   <fieldset style="width:98%"><legend><b>Diário de Classe</b></legend>
    <table width="100%" valign="top" marginwidth="0" border="0" cellspacing="0" cellpadding="0">
     <tr>
      <td width="40%">
       <iframe name="arvore" id="arvore" src="edu1_diarioclasse002.php" width="100%" height="470" scrolling="yes"></iframe>
      </td>
      <td>
       <iframe name="dados" id="dados" src="<?=$arquivo?>" width="100%" height="470"></iframe>
      </td>
     </tr>
    </table>
   </fieldset>
  </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<table width="300" height="100" id="tab_aguarde" style="visibility:hidden;border:2px solid #444444;position:absolute;top:100px;" cellspacing="1" cellpading="2">
 <tr>
  <td bgcolor="#DEB887" align="center" style="border:1px solid #444444;">
   <b>Aguarde...Carregando.</b>
  </td>
 </tr>
</table>

<script>

(function () {

  var sMsgAviso  = 'Prezados clientes,\n\n';
      sMsgAviso += 'Informamos que esta rotina não estará mais disponível a partir de janeiro de 2015.';
      sMsgAviso += '\nPara efetuar os lançamentos no diário de classe dos alunos, acesse:\n';
      sMsgAviso += 'Procedimentos > Diário de Classe > Lançamentos por Turma';

  alert(sMsgAviso);
  
  // Adicionado redirecionamento para nova rotina
  window.location = 'edu4_lancamentoavaliacoesturma001.php';
  return;

  var oParametro  = new Object();
  oParametro.exec = 'buscaTurmasComMaisUmProcedimentoAvaliacao';

  var oObjeto          = {};
  oObjeto.method       = 'post';
  oObjeto.parameters   = 'json='+Object.toJSON(oParametro);
  oObjeto.asynchronous = false;
  oObjeto.onComplete   = function(oAjax) {

    var oRetorno = eval("(" + oAjax.responseText + ")");

    if (oRetorno.lPossue) {

      var sMsgAvisoTurmas  = "O Lançamentos das avaliações das turmas listadas abaixo, deverá ser realizado através da rotina:";
          sMsgAvisoTurmas += "\nProcedimentos > Diário de Classe > Lançamentos por Turma";
          sMsgAvisoTurmas += "\npois as mesmas possuem mais de um procedimento de avaliação";
          sMsgAvisoTurmas += "\n";

      for ( iIndice in oRetorno.aTurmas) {

        sMsgAvisoTurmas += "\n" + oRetorno.aTurmas[iIndice].sNome.urlDecode();
        oRetorno.aTurmas[iIndice].aTurmas.each( function( sTurma ) {

          sMsgAvisoTurmas += "\n\t"+ sTurma.urlDecode();
        });
      }
      alert(sMsgAvisoTurmas);
    }
  };
  new Ajax.Request('edu4_turmas.RPC.php', oObjeto);
})();
</script>