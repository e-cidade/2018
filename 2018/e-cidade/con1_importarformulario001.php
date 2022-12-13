<?php
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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
</head>
<body class='body-default'>
  <div class='container'>

    <form action="post">
      <fieldset>
        <legend>Importar Formulário</legend>
        <table>
          <tr >
            <td><input style='height: 30px;' type="file" name="file" id="file"/></td>
          </tr>
        </table>
      </fieldset>
      <input type="button" name="importar" id="btnImportar" value="Importar" />
    </form>

  </div>
<?php
  db_menu();
?>
</body>
<script type="text/javascript">

var fonteMsg = 'configuracao.configuracao.con1_importarformulario001.';

function validarArquivo() {

  var nomeArquivo = $F('file');
  if ( empty(nomeArquivo)) {

    alert( _M(fonteMsg + 'selecione_arquivo') );
    return false;
  }
  nomeArquivo = nomeArquivo.split('.');
  extensao    = nomeArquivo[nomeArquivo.length - 1].toLowerCase();
  if (extensao != 'yml' && extensao != 'yaml'){

    alert( _M(fonteMsg + "arquivo_invalido" ) );
    return false;
  }
  return true;
}

$('btnImportar').addEventListener('click', function (){

  if ( !validarArquivo() ){
    return;
  }

  new AjaxRequest('con4_manutencaoformulario.RPC.php', {exec : 'importar'}, function(oRetorno, lErro) {

    alert(oRetorno.mensagem)
    if (lErro) {
      return false;
    }

    location.href = 'con1_importarformulario001.php';
  })
  .addFileInput($('file'))
  .setMessage( _M(fonteMsg + "processando_arquivo") )
  .execute();
});
</script>

</html>
