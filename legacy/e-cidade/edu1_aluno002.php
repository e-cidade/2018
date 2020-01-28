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
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_utils.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_jsplibwebseller.php");

parse_str( $_SERVER["QUERY_STRING"] );
db_postmemory( $_POST );

$oDaoAluno           = new cl_aluno();
$oDaoAlunoAltera     = new cl_alunoaltera();
$oDaoAlunoCurso      = new cl_alunocurso();
$oDaoCensoUf         = new cl_censouf();
$oDaoCensoMunic      = new cl_censomunic();
$oDaoCensoOrgEmissRg = new cl_censoorgemissrg();
$oDaoCensoCartorio   = new cl_censocartorio();

$db_opcao = 2;
$db_botao = true;

if (isset($alterar)) {
	
  $db_opcao = 2;
  $db_botao = true;

  /**
   * Valida se o cartão do SUS é válido conforme funções
   */
  if (    isset( $ed47_cartaosus )
       && !empty( $ed47_cartaosus )
       && !validaCnsDefinitivo( $ed47_cartaosus )
       && !validaCnsProvisorio( $ed47_cartaosus ) ) {


    db_msgbox( "Número do cartão do SUS não é válido." );
    db_redireciona( "edu1_aluno002.php?chavepesquisa={$chavepesquisa}" );
  }

  db_inicio_transacao();

  if( !isset( $ed47_c_certidaocart ) ) {
    $oDaoAluno->ed47_c_certidaocart = '';
  }

  if( !isset( $ed47_i_censomuniccert ) ) {
    $oDaoAluno->ed47_i_censomuniccert = null;
  }

  if( !isset( $ed47_i_censoufcert ) ) {
    $oDaoAluno->ed47_i_censoufcert = null;
  }

  if( !isset( $ed47_i_censoufident ) ) {
    $oDaoAluno->ed47_i_censoufident = null;
  }

  if( !isset( $ed47_i_censoorgemissrg ) ) {
    $oDaoAluno->ed47_i_censoorgemissrg = null;
  }

  $oDaoAluno->ed47_certidaomatricula = $ed47_certidaomatricula;
  $oDaoAluno->alterar($ed47_i_codigo);
  
  if ($oDaoAluno->erro_status == "0") {
    
    $_rollback = true;
  
  } else {
    $_rollback = false;
  }
  
  db_fim_transacao($_rollback);
}

if (isset($chavepesquisa)) {
	
  $db_opcao = 2;
  $sSql     = $oDaoAluno->sql_query($chavepesquisa);
  $rsResult = $oDaoAluno->sql_record($sSql);
  db_fieldsmemory($rsResult,0);
  $db_botao = true;
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
  <script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
        <center>
          <fieldset style="width:95%">
            <legend><b>Documentos</b></legend>
            <b>Situação da Documentação:</b>
            <select id="possuiDocumentacao">
              <option value="0">Aluno possui documentação</option>
              <option value="1">Aluno não possui documentação</option>
              <option value="2">Escola não possui informação de documentação do aluno</option>
            </select>
          </fieldset>
          <fieldset style="width:95%"><legend><b>Documentação do Aluno</b></legend>
            <?include("forms/db_frmaluno.php");?>
          </fieldset>
        </center>
      </td>
    </tr>
  </table>
</body>
</html>
<?php
if (isset($alterar)) {
	
  if ($oDaoAluno->erro_status == "0") {
  	
    $oDaoAluno->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($oDaoAluno->erro_campo != "") {
    	
      echo "<script> document.form1.".$oDaoAluno->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoAluno->erro_campo.".focus();</script>";
      
    }
    
  } else {
  	
    $oDaoAluno->erro(true,false);
    db_redireciona("edu1_aluno002.php?chavepesquisa=$chavepesquisa");
  }
}

if (isset($excluirfoto)) {
  db_redireciona("edu1_aluno002.php?chavepesquisa=$chavepesquisa");
}
?>
<script>
/**
 * Verificamos qual opcao selecionada na situacao da documentacao, para tratamento dos campos do formulario
 * Caso tenha sido selecionado:
 * 0: Aluno possui documentação - os campos sao liberados para informar os dados
 * 1 ou 2: Aluno não possui documentacao ou Escola nao possui informacao de documentacao do aluno - todos os campos sao
 * bloqueados
 */
function js_verificaOpcaoSelecionada() {

  var lDesabilitaCampos = false;
  var iNacionalidade    = <?=$ed47_i_nacion?>;
  
  if ($('possuiDocumentacao').value != '0') {
    lDesabilitaCampos = true;
  }

  setFormReadOnly($('frmDocumentacaoAluno'), lDesabilitaCampos);

  if (!lDesabilitaCampos) {
    
    $('ed47_i_codigo').readOnly                     = true;
    $('ed47_i_codigo').style.backgroundColor        = '#DEB887';
    $('ed47_v_nome').readOnly                       = true;
    $('ed47_v_nome').style.backgroundColor          = '#DEB887';
    $('ed47_c_codigoinep').readOnly                 = true;
    $('ed47_c_codigoinep').style.backgroundColor    = '#DEB887';
    $('ed47_c_certidaonum').style.backgroundColor   = '#E6E4F1';
    $('ed47_c_certidaofolha').style.backgroundColor = '#E6E4F1';
    $('ed47_c_certidaolivro').style.backgroundColor = '#E6E4F1';
    $('ed47_c_certidaodata').style.backgroundColor  = '#E6E4F1';
    $('ed47_v_ident').style.backgroundColor         = '#E6E4F1';
    $('ed47_v_identcompl').style.backgroundColor    = '#E6E4F1';
    $('ed47_d_identdtexp').style.backgroundColor    = '#E6E4F1';
    $('ed47_v_cnh').style.backgroundColor           = '#E6E4F1';
    $('ed47_d_dthabilitacao').style.backgroundColor = '#E6E4F1';
    $('ed47_d_dtemissao').style.backgroundColor     = '#E6E4F1';
    $('ed47_d_dtvencimento').style.backgroundColor  = '#E6E4F1';
    $('ed47_v_cpf').style.backgroundColor           = '#E6E4F1';
    
    if (iNacionalidade != 3) {
    
      $('ed47_c_passaporte').readOnly               = true;
      $('ed47_c_passaporte').style.backgroundColor  = '#DEB887';
    }
  }

  if (iNacionalidade == 3 && $('possuiDocumentacao').value == '0') {

	  setFormReadOnly($('frmDocumentacaoAluno'), true);
  	$('ed47_c_passaporte').readOnly               = false;
    $('ed47_c_passaporte').style.backgroundColor  = '#E6E4F1';
  }
  
  $('alterar').disabled                 = false;
  $('ed47_t_obs').style.backgroundColor = "#E6E4F1";
  $('ed47_t_obs').readOnly              = false;
  
  $('ed47_v_contato').style.backgroundColor = "#E6E4F1";
  $('ed47_v_contato').readOnly              = false;
  
}

$('possuiDocumentacao').observe("change", function() {

  js_salvaSituacao();
  js_verificaOpcaoSelecionada();
  
});

/**
 * Verificamos a situacao da documentacao do aluno. Caso seja igual a 0, bloqueamos as outras opcoes de selecao, pois o
 * aluno ja possui documento
 */
function js_verificaSituacaoDocumentacao() {

  var oParametro    = new Object();
  oParametro.exec   = 'verificaSituacaoDocumentacao';
  oParametro.iAluno = $F('ed47_i_codigo');

  var oAjax = new Ajax.Request(
                               'edu4_escola.RPC.php',
                               {
                                 method:     'post',
                                 parameters: 'json='+Object.toJSON(oParametro),
                                 onComplete: js_retornoVerificaSituacaoDocumentacao
                               }
                              );
}

function js_retornoVerificaSituacaoDocumentacao(oResponse) {

  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.iStatus == 1) {

    $('possuiDocumentacao').value = oRetorno.iSituacaoDocumentacao;

    if ($('possuiDocumentacao').value == "0" && oRetorno.lBloqueiaDocumentacao) {

      var iTotalDocumentacao = $('possuiDocumentacao').length;

      for (var iContador = 0; iContador < iTotalDocumentacao; iContador++) {

        if ($('possuiDocumentacao')[iContador].value != '0') {
          $('possuiDocumentacao')[iContador].disabled = true;
        }
      }
    }
    js_verificaOpcaoSelecionada();
  } else {

    alert(oRetorno.sMessage.urlDecode());
    return false;
  }
}

function js_salvaSituacao() {

  var oParametro                   = new Object();
  oParametro.exec                  = 'salvaSituacaoDocumentacao';
  oParametro.iAluno                = $F('ed47_i_codigo');
  oParametro.iSituacaoDocumentacao = $F('possuiDocumentacao');

  var oAjax = new Ajax.Request(
                                'edu4_escola.RPC.php',
                                {
                                  method:     'post',
                                  parameters: 'json='+Object.toJSON(oParametro),
                                  onComplete: js_retornoSalvaSituacao
                                }
                              );
}

function js_retornoSalvaSituacao(oResponse) {

  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.iStatus != 1) {

    alert(oRetorno.sMessage.urlDecode());
    return false;
  }

  js_verificaSituacaoDocumentacao();
  return true;
}

js_verificaSituacaoDocumentacao();
</script>