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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
$oRotulo = new rotulocampo();
$oRotulo->label("z01_nome");
$oRotulo->label("z01_numcgm");
$oRotulo->label("pc01_codmater");
$oRotulo->label("pc01_descrmater");
$oDaoUnidades  = new cl_matunid();
$sSqlUnidades  = $oDaoUnidades->sql_query_file(null, "m61_codmatunid, m61_descr", "m61_descr");
$rsUnidades    = $oDaoUnidades->sql_record($sSqlUnidades);
$aUnidades = array();
for ($i = 0; $i < $oDaoUnidades->numrows; $i++) {

 $oUnidade = db_utils::fieldsMemory($rsUnidades, $i);
 $aUnidades[$oUnidade->m61_codmatunid] = $oUnidade->m61_descr;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
db_app::load("scripts.js, prototype.js, datagrid.widget.js,windowAux.widget.js,messageboard.widget.js, strings.js");
db_app::load("classes/ultimosOrcamentos.classe.js,dbtextFieldData.widget.js, dbtextField.widget.js");
db_app::load("dbcomboBox.widget.js, dbautocomplete.widget.js");
db_app::load("estilos.css, grid.style.css");
?>

<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name='form1' method="get"onsubmit="return false">
<br>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr height="50">
      <td align="left" valign="top" bgcolor="#CCCCCC">&nbsp;</td>
    </tr>
  </table>
  <center>
    <table>
       <tr>
         <td>
           <fieldset>
             <legend>
               <b>Cotações de Preços</b>
             </legend>
             <table>
               <tr>
                 <td>
                   <b>Data Inicial:</b>
                 </td>
                 <td style="">
                   <span id='ctnDataInicial'></span>
                   <b>&nbsp;Data Final:</b>
                 <span id='ctnDataFinal' style="text-align: right"></span>
                 </td>
               </tr>
               <tr>
                  <td nowrap title="<?=@$Tz01_numcgm?>">
                    <?
                      db_ancora(@$Lz01_nome, "js_pesquisa_cgm(true);",1);
                    ?>
                  </td>
                  <td colspan="2" nowrap="nowrap">
                   <span id='ctnTxtNumcgm'></span>
                   <span id='ctnTxtNome'></span>
                  </td>
               </tr>
               <tr>
                  <td nowrap title="<?=@$Tpc01_descrmater?>">
                    <?
                      db_ancora(@$Lpc01_descrmater, "js_pesquisapc16_codmater(true);",1);
                    ?>
                  </td>
                  <td colspan="2" nowrap="nowrap">
                   <span id='ctnTxtCodigoMater'></span>
                   <span id='ctnTxtDescricaoMater'></span>
                  </td>
               </tr>
               <tr>
                  <td nowrap title="Unides">
                    <b>Unidades:</b>
                  </td>
                  <td colspan="2" nowrap="nowrap">
                    <?php
                       db_select('cboUnidades', $aUnidades, true, 1);
                    ?>
                   <span id='ctnCboUnidades'></span>
                  </td>
               </tr>
             </table>
           </fieldset>
         </td>
       </tr>
       <tr>
          <td colspan="3" style="text-align: center">
            <input type="button" value='Processar' id='btnProcessar'>
          </td>
       </tr>
    </table>
  </center>
</form>
</body>
</html>
<script>
$('cboUnidades').style.width = '100%';
$('cboUnidades').size        = 10;
$('cboUnidades').multiple    = true;
function js_pesquisa_cgm(mostra){
  if (mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_pcforne',
                          'func_nome.php?funcao_js=parent.js_mostra_cgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{

    if (txtNumCgm.getValue() != "") {
    js_OpenJanelaIframe('top.corpo','db_iframe_pcforne',
                        'func_nome?pesquisa_chave='+
                        $F('txtNumCgm')+'&funcao_js=parent.js_mostra_cgm','Pesquisa CGM',false);
    } else {
      txtNome.setValue("");
    }
  }
}
function js_mostra_cgm(erro, nome){

  txtNome.setValue(nome);
  if (erro==true) {

    txtNumCgm.setValue("");
    $('txtNumCgm').focus();

  }
}
function js_mostra_cgm1(numcgm,nome){

  txtNome.setValue(nome);
  txtNumCgm.setValue(numcgm);
  db_iframe_pcforne.hide();
}

function js_pesquisapc16_codmater(mostra) {

  if (mostra==true) {
    js_OpenJanelaIframe('',
                        'db_iframe_pcmater',
                        'func_pcmatersolicita.php?funcao_js=parent.js_mostrapcmater1|pc01_codmater|pc01_descrmater',
                        'Pesquisar Materias/Serviços',
                         true

                        );
  } else {

    if ($F('txtCodigoMater') != '') {

      js_OpenJanelaIframe('',
                          'db_iframe_pcmater',
                          'func_pcmatersolicita.php?pesquisa_chave='+
                          $F('txtCodigoMater')+
                          '&funcao_js=parent.js_mostrapcmater',
                          'Pesquisar Materiais/Serviços',
                          false,'0'
                      );
    } else {
      txtDescricaoMater.setValue('');
    }
  }
}

function js_mostrapcmater(sDescricaoMaterial, erro) {


  txtDescricaoMater.setValue(sDescricaoMaterial);
  if (erro==true) {

    txtCodigoMater.setValue("");
    $('txtCodigoMater').focus();

  }

}

function js_mostrapcmater1(iCodigoMaterial, sDescricaoMaterial) {


  txtCodigoMater.setValue(iCodigoMaterial);
  txtDescricaoMater.setValue(sDescricaoMaterial);
  db_iframe_pcmater.hide();
}
function init() {

  txtDataInicial = new DBTextFieldData('dtInicial', 'txtDataInicial', '', 10);
  txtDataFinal   = new DBTextFieldData('dtFinal', 'txtDataFinal', '', 10);

  txtDataInicial.show($('ctnDataInicial'));
  txtDataFinal.show($('ctnDataFinal'));

  txtNumCgm   = new DBTextField('txtNumCgm', 'txtNumCgm', '', 10);
  txtNumCgm.addEvent("onBlur", "js_pesquisa_cgm(false)");
  txtNumCgm.addEvent("onKeyDown", "openPesquisa(\"js_pesquisa_cgm\",event)");
  //txtNumCgm.addStyle("width", "100%");
  txtNumCgm.show($('ctnTxtNumcgm'));

  txtNome   = new DBTextField('txtNome', 'txtNome', '', 40);
  txtNome.addStyle("background-color","rgb(222, 184, 135); text-transform: uppercase;");
  txtNome.addStyle("text-transform", "uppercase");
  txtNome.show($('ctnTxtNome'));

  txtCodigoMater   = new DBTextField('txtCodigoMater', 'txtCodigoMater', '', 10);
  txtCodigoMater.addEvent("onBlur", "js_pesquisapc16_codmater(false)");
  txtCodigoMater.addEvent("onKeyDown", "openPesquisa(\"js_pesquisapc16_codmater\",event)");
  txtCodigoMater.show($('ctnTxtCodigoMater'));

  txtDescricaoMater   = new DBTextField('txtDescricaoMater', 'txtDescricaoMater', '', 40);
  txtDescricaoMater.show($('ctnTxtDescricaoMater'));

  oAutoComplete = new dbAutoComplete($('txtDescricaoMater'),'com4_pesquisamateriais.RPC.php');
  oAutoComplete.setTxtFieldId(document.getElementById('txtCodigoMater'));
  oAutoComplete.show();
}

function openPesquisa(sFuncao, event) {

   if (event.which == F3) {
     eval(sFuncao+"(true)");
     event.preventDefault();
     event.stopPropagation();
   }
}

function js_abreOrcamentos() {

  if (txtCodigoMater.getValue() == "" && txtNumCgm.getValue() == "") {

    alert('Informe o material, ou o Fornecedor!');
    return false;
  }

  oUltimosOrcamentos = new ultimosOrcamentos();
  oUltimosOrcamentos.setItem(txtCodigoMater.getValue());
  oUltimosOrcamentos.setFornecedor(txtNumCgm.getValue());
  oUltimosOrcamentos.addUnidade($('cboUnidades').value);
  oUltimosOrcamentos.setDataInicial(txtDataInicial.getValue());
  oUltimosOrcamentos.setDataFinal(txtDataFinal.getValue());
  oUltimosOrcamentos.addUnidade(cboUnidades.getValue());
  oUltimosOrcamentos.getOrcamentos();
  oUltimosOrcamentos.showUltimosOrcamentos();

}

init();

$('btnProcessar').observe("click", js_abreOrcamentos);
</script>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>