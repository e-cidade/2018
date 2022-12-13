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
  <script language="JavaScript" type="text/javascript" src="scripts/arrays.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBFileUpload.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
</head>
<body class='body-default'>
    <div class='container'>
        <form >
            <fieldset>
                <legend>Envio do certificado</legend>
                <table class='form-container'>
                    <tr>
                        <td><label for="cboEmpregador">Empregador:</label></td>
                        <td>
                            <select name="empregador" id="cboEmpregador">
                                <option value="">selecione</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="documento">Documento:</label></td>
                        <td>
                            <input type="text" id='documento' class='readonly field-size3' disabled='disabled'>
                            <input type="text" id='tipo' class='readonly field-size1' disabled='disabled'>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="password">Senha do certificado:</label></td>
                        <td><input type="password" id='password' ></td>
                    </tr>
                </table>
                <fieldset class="separator">
                    <legend>Clique no botão "Arquivo" e selecione o certificado</legend>
                    <div id="ctnImportacao"></div>
                </fieldset>
            </fieldset>
            <input type="button" id="btnProcessar" value="Processar" disabled='disabled' />
        </form>
    </div>
<?php
  db_menu();
?>
<script type="text/javascript">

var empregadores = [];
(function(){

    new AjaxRequest( 'eso4_esocialapi.RPC.php', {exec : 'getEmpregadores'}, function ( retorno, lErro ) {

        if ( lErro ) {
            alert(retorno.sMessage);
            return false;
        }
        empregadores = retorno.empregadores;

        limpar();
        $('cboEmpregador').length = 0;
        $('cboEmpregador').add( new Option("Selecione", ''));
        for (var empregador of empregadores) {
            $('cboEmpregador').add(new Option(empregador.nome, empregador.cgm));
        }


    }).setMessage('Buscando servidores.').execute();
})();

function limpar() {

    $('tipo').value = '';
    $('documento').value = '';
    $('password').value = '';
    document.querySelector(".inputUploadFile").value = '';
}

$('cboEmpregador').addEventListener('change', function() {

    if ($F('cboEmpregador') == '') {
        limpar();
        return;
    }

    for (var empregador of empregadores) {

        if ($F('cboEmpregador') == empregador.cgm) {

            $('documento').value = empregador.documento;
            $('tipo').value = empregador.documento.length == 11 ? 'CPF' : 'CNPJ';
            break;
        }
    }
});

function retornoEnvioArquivo(retorno) {

  if (retorno.error) {

    alert(retorno.error);
    $('btnProcessar').disabled = true;
    return false;
  }

  var extension = ['crt', 'pfx', 'p12'];
  if (!extension.in_array(retorno.extension.toLowerCase())) {

    alert("Arquivo inválido.\nArquivo selecionado deve ser um certificado com a extensão \"" + extension.implode(', ') + "\".");
    $('btnProcessar').disabled = true;
    document.querySelector(".inputUploadFile").value = '';
    return false;
  }

  $('btnProcessar').disabled = false;
}


var fileUpload = new DBFileUpload( {callBack: retornoEnvioArquivo, labelButton : 'Arquivo'} );
    fileUpload.show($('ctnImportacao'));

document.querySelector(".inputUploadFile").addClassName('field-size5');


function validar() {

    if ($F('cboEmpregador') == '') {
        alert('Selecione o emrpegador.');
        return false;
    }

    if ($F('password') == '') {
        alert('Informe a senha do certificado.');
        return false;
    }

    return true;
}

function getDocumento() {

    for (var empregador of empregadores) {
        if ($F('cboEmpregador') == empregador.cgm) {
            return empregador.documento;
        }
    }
}

$('btnProcessar').addEventListener('click', function() {

    if (!validar()) {
        return;
    }

    var documento = getDocumento();
    var paramentros = {
        'exec' : 'empregador',
        'empregador' : $F('cboEmpregador'),
        'razao_social' : $('cboEmpregador').options[$('cboEmpregador').selectedIndex].innerHTML,
        'documento' : documento,
        'senha' : $F('password'),
        'sFile' : fileUpload.file,
        'sPath' : fileUpload.filePath
    };

    new AjaxRequest( 'eso4_esocialapi.RPC.php', paramentros, function ( retorno, lErro ) {

        alert(retorno.sMessage);
        if ( lErro ) {
            return false;
        }

        $('cboEmpregador').value = '';
        limpar();
        $('btnProcessar').disabled = false;
    }).setMessage('Enviando dados para ').execute();
});


</script>
</body>
</html>
