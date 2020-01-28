<?php
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_app.utils.php");

$oRotulo = new rotulocampo;
$oRotulo->label("t21_codigo");

$oGet        = db_utils::postMemory($_GET);
$iDbOpcao    = 1;
$lMostraMenu = true;

/**
 * Se o programa for acessado por uma rotina de inclusão/alteracao de Termo/Devolucao de Termo
 * setmos a propriedade $iDbOpcao para bloquear os campos e o Codigo do termo no forumlario
 */
if (isset($oGet->iTermo)) {
  
  $t21_codigo  = $oGet->iTermo;
  $iDbOpcao    = 3;
  $lMostraMenu = false;
}

$iTemplateTipo = 31;  // Template de Termo de Guarda
$sLengenda     = "Termo de Guarda";
if (isset($oGet->devolucao) && $oGet->devolucao == 'true') {
  
  $iTemplateTipo = 32;  // Template de Devolucao Termo de Guarda
  $sLengenda     = "Devolução de Termo de Guarda";
}


$oDaoDocumento = db_utils::getDao('db_documentotemplate');
$oDaoDocumento = new cl_db_documentotemplate();
$sCampos       = " db82_sequencial, db82_descricao";

$sSqlDocumentoTemplate = $oDaoDocumento->sql_query_file(null, $sCampos, null, "db82_templatetipo = {$iTemplateTipo}");
$rsDocumentoTemplate   = $oDaoDocumento->sql_record($sSqlDocumentoTemplate);


if ($oDaoDocumento->erro_status == "0") {
  db_msgbox(_M("patrimonial.patrimonio.pat2_reltermoguarda001.nao_ha_templates"));
}


?>



<style>

  #documentotemplate {
  
    width: 80px;
  }
  #documentotemplatedescr{
  
    width: 300px;
  }
</style>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php 
    db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js, webseller.js");
    db_app::load("estilos.css, grid.style.css");
  ?>
</head>
<body bgcolor="#cccccc">
    <form class="container" name="form1" method="post" action="">
    <fieldset>
      <legend><?php echo $sLengenda;?></legend>
      <table class="form-container">
        <tr>
          <td title="<?=@$Tt21_codigo?>" >
	          <?
	            db_ancora("Termo de Guarda:", "js_pesquisaBensGuarda(true);", $iDbOpcao);
	          ?>
          </td>
			    <td>		     
	          <?
	            db_input('t21_codigo', 10, $It21_codigo, true, 'text', $iDbOpcao, "onchange='js_pesquisaBensGuarda(false);'");
	          ?>
			    </td>
        </tr>             
        <tr>
          <td>Responsável:</td>
          <td>
            <?
              db_input('cgm',         10, '', true, 'hidden', 3);
	            db_input('responsavel', 40, '', true, 'text',   3);
	          ?>
          </td>
        </tr>
        <tr title='Titulo para a assinatura do responsável.'>
          <td>Função: </td>
          <td>
            <?
	            db_input('funcao', 40, '', true, 'text', 1);
	          ?>
          </td>
        </tr>
        <tr>
          <td>
            Documento Template:
          </td>
          <td>
            <?
             db_selectrecord('documentotemplate',$rsDocumentoTemplate,true,1,'');
            ?>
          </td>
        </tr>
      </table>
    </fieldset >
    <input type="button" id="btnImprimir" value="Imprimir" onclick="js_imprime();"/>
    </form>
</body>
<?php
if ($lMostraMenu) {
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
}
?>
<script type="text/javascript">
var oGet = js_urlToObject();
/**
 * Função que chama a lookup dos Termos de Guarda
 */
function js_pesquisaBensGuarda(lMostra) {

  var sUrl = 'func_termoguarda.php?';

  var devolucao = false;
  if (oGet.devolucao) {
    var devolucao = true;
  }
  sUrl += 'devolucao='+devolucao;
  
  if (lMostra) {

    sUrl += '&funcao_js=parent.js_bensGuarda|t21_codigo|t21_numcgm|z01_nome';
    js_OpenJanelaIframe('top.corpo', 'db_iframe_bensguarda', sUrl, 'Pesquisa',true);
  } else {
    
     if ($F('t21_codigo') != '') {

       sUrl += '&pesquisa_chave='+$F('t21_codigo');
       sUrl += '&funcao_js=parent.js_bensGuardaOnChange';
       js_OpenJanelaIframe('', 'db_iframe_bensguarda', sUrl,'Pesquisa', false);
     }else{
       document.form1.t21_codigo.value = ''; 
     }
  }
}

/**
 * Usada quando chamada de um programa de manutencao de Termo de Guarda
 */
function js_pesquisaTermo(lMostra) {

  var sUrl = 'func_termoguarda.php?';
  var devolucao = false;
  if (oGet.devolucao) {
    var devolucao = true;
  }
  sUrl += 'devolucao='+devolucao;
  if ($F('t21_codigo') != '') {

    sUrl += '&pesquisa_chave='+$F('t21_codigo');
    sUrl += '&funcao_js=parent.js_bensGuardaOnChange';
    js_OpenJanelaIframe('', 'db_iframe_bensguarda', sUrl,'Pesquisa', false);
  }
}



function js_bensGuardaOnChange(lErro, iTermo, iCgm, sResponsavel) {

  $('t21_codigo').value  = iTermo;
  $('cgm').value         = iCgm;
  $('responsavel').value = sResponsavel;
  
  if (lErro) {
     
    $('t21_codigo').focus(); 
    $('t21_codigo').value  = '';
    $('cgm').value         = '';
    $('responsavel').value = '';
  }
}
function js_bensGuarda(iTermo, iCgm, sResponsavel, lErro) {

  $("t21_codigo").value  = iTermo;
  $("cgm").value         = iCgm;
  $("responsavel").value = sResponsavel;

  db_iframe_bensguarda.hide();
}


/**
 * Limpa os dados do Formulário
 */
function js_limpaForm() {

  $('t21_codigo').value  = '';
  $('cgm').value         = '';
  $('responsavel').value = '';
  $('funcao').value      = '';
}

/**
 * Se o programa for acessado por uma rotina de inclusão/alteracao de Termo/Devolucao de Termo
 * devemos buscar os dados do termo 
 */
if (oGet.iTermo != 'undefined') {

  js_pesquisaBensGuarda(false);
}

js_limpaForm();

/**
 * Função que emite o Termo de Guarda
 */
function js_imprime() {

  if ($F('t21_codigo') == "") {

    alert(_M("patrimonial.patrimonio.pat2_reltermoguarda001.selecione_termno_de_guarda"))
    return false;
  }

  
  var sUrl  = '';
  sUrl      = 'pat2_emitetermodeguarda002.php';
  if (oGet.devolucao) {
    sUrl  = 'pat2_emitedevolucaotermodeguarda002.php';
  }
  sUrl     += '?iModeloImpressao='+$F('documentotemplate');
  sUrl     += '&iCodigoTermo='+$F('t21_codigo');
  sUrl     += '&sFuncao='+$F('funcao');

  var jan = window.open(sUrl, '',
                        'location=0, width='+(screen.availWidth - 5)+'width='+(screen.availWidth - 5)+', scrollbars=1');
      jan.moveTo(0, 0);
}
</script>
<script>

$("t21_codigo").addClassName("field-size2");
$("responsavel").addClassName("field-size9");
$("funcao").addClassName("field-size9");

</script>