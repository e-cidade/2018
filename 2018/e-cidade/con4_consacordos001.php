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
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("dbforms/db_funcoes.php"));
$clrotulo = new rotulocampo;
$db_opcao = 3;
$clrotulo->label("ac16_sequencial");
$clrotulo->label("ac16_resumoobjeto");
$clrotulo->label("ac16_coddepto");
$clrotulo->label("descrdepto");
$clrotulo->label("z01_nome");
$clrotulo->label("ac16_contratado");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<?
db_app::load("scripts.js, strings.js, prototype.js,datagrid.widget.js, widgets/dbautocomplete.widget.js");
db_app::load("widgets/windowAux.widget.js");
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<style>

 .fora {background-color: #d1f07c;}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
  <table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
  </table>
  <center>
    <table style="margin-top: 20px;">
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>Consultar Acordos</b>
            </legend>
            <table>
            <tr>
              <td nowrap title="<?php echo $Tac16_sequencial; ?>" width="130">
                 <?php db_ancora($Lac16_sequencial, "js_acordo(true);",1); ?>
              </td>
              <td colspan="2">
                <?php
                  db_input('ac16_sequencial', 10, $Iac16_sequencial, true, 'text', 1, "onchange='js_acordo(false);'");
                  db_input('ac16_resumoobjeto', 40, $Iac16_resumoobjeto, true, 'text', 3);
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="" width="130">
                 <b>Origem:</b>
              </td>
              <td colspan="2">
                <?
                $aOrigem = array();
                $aOrigem[0] = "Todas";
                $oDaoAcordoOrigem = db_utils::getDao("acordoorigem");
                $sSql  = $oDaoAcordoOrigem->sql_query_file(null, "ac28_sequencial,ac28_descricao", null, '');
                $rsSql = $oDaoAcordoOrigem->sql_record($sSql);
                if ($rsSql !== false) {

                	for ($iInd = 0; $iInd < $oDaoAcordoOrigem->numrows; $iInd++) {

                		$chave            = db_utils::fieldsMemory($rsSql,$iInd)->ac28_sequencial;
                		$aOrigem[$chave]  = db_utils::fieldsMemory($rsSql,$iInd)->ac28_descricao;
                	}
                }
                db_select('ac16_origem', $aOrigem, true, 1);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <?
                  db_ancora("<b>Contratado:</b>", "js_pesquisaContratado(true);", 1);
                ?>
              </td>
              <td>
                <?
                 db_input('ac16_contratado', 10, $Iac16_contratado, true, 'text', 1, "onchange='js_pesquisaContratado(false);'");
                 db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <?
                  db_ancora("<b>Departamento:</b>", "js_departamento(true);", 1);
                ?>
              </td>
              <td>
                <?
                 db_input('ac16_coddepto', 10, $Iac16_coddepto, true, 'text', 1, "onchange='js_departamento(false);'");
                 db_input('descrdepto', 40, $Idescrdepto, true, 'text', 3);
                ?>
              </td>
            </tr>
          </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td style="text-align: center;">
          <input type='button' value='Pesquisar' onclick="js_abrir();" >

        </td>
      </tr>
    </table>
  </center>
</body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script type="text/javascript">

function js_abrir(){

 var ac16_sequencial  = "";
 var ac16_coddepto    = "";
 var ac16_contratado  = "";
 var ac16_origem      = $F('ac16_origem');
 var sQuery           = "";

 if ($F('ac16_sequencial') != "") {
  ac16_sequencial = $F('ac16_sequencial');
 }

 if ($F('ac16_coddepto') != "") {
  ac16_coddepto = $F('ac16_coddepto');
 }

 if ($F('ac16_contratado') != "") {
  ac16_contratado = $F('ac16_contratado');
 }



 funcao_js = 'parent.retornoSelecao|ac16_sequencial';

 sQuery += "ac16_sequencial="+ac16_sequencial;
 sQuery += "&ac16_coddepto="+ac16_coddepto;
 sQuery += "&ac16_contratado="+ac16_contratado;
 sQuery += "&ac16_origem="+ac16_origem;
 sQuery += "&funcao_js="+funcao_js;

 js_OpenJanelaIframe('','db_iframe_consulta',
                     'con4_consacordos002.php?'+sQuery,
                     'Pesquisa',true);
}

function retornoSelecao(iNumero) {
  db_iframe_consulta.hide();
  js_exibeSelecao(iNumero);
}

function js_exibeSelecao(iNumero){

 var sQuery = "";
 var ac16_sequencial = iNumero;
 sQuery = "ac16_sequencial="+ac16_sequencial;

 js_OpenJanelaIframe('','db_iframe_consultaabertura',
                     'con4_consacordos003.php?'+sQuery,
                     'Detalhes',true);
}

function js_departamento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_depart',
                        'func_db_depart.php?funcao_js=parent.js_mostradepart1|coddepto|descrdepto',
                        'Pesquisa',true);
  }else{
     if($F('ac16_coddepto').trim() != ''){
        js_OpenJanelaIframe('','db_iframe_depart',
                            'func_db_depart.php?pesquisa_chave='+$F('ac16_coddepto')+'&funcao_js=parent.js_mostradepart',
                            'Pesquisa',false);
     }else{
       $('descrdepto').value = '';
     }
  }
}
function js_mostradepart(chave,erro){
  $('descrdepto').value = chave;
  if(erro==true){
    $('ac16_coddepto').focus();
    $('ac16_coddepto').value = '';
  }
}
function js_mostradepart1(chave1,chave2){
  $('ac16_coddepto').value = chave1;
  $('descrdepto').value = chave2;
  db_iframe_depart.hide();
}

function js_acordo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_acordo',
                        'func_acordoinstit.php?funcao_js=parent.js_mostraAcordo1|ac16_sequencial|ac16_resumoobjeto',
                        'Pesquisa',true);
  }else{
     if($F('ac16_sequencial').trim() != ''){
        js_OpenJanelaIframe('','db_iframe_depart',
                            'func_acordoinstit.php?pesquisa_chave='+$F('ac16_sequencial')+'&funcao_js=parent.js_mostraAcordo'+
                            '&descricao=true',
                            'Pesquisa',false);
     }else{
       $('ac16_resumoobjeto').value = '';
     }
  }
}
function js_mostraAcordo(chave, descricao, erro){

  $('ac16_resumoobjeto').value = descricao;
  if(erro==true){
    $('ac16_sequencial').focus();
    $('ac16_sequencial').value = '';
  }
}
function js_mostraAcordo1(chave1,chave2){
  $('ac16_sequencial').value = chave1;
  $('ac16_resumoobjeto').value = chave2;
  db_iframe_acordo.hide();
}


  /**
   * Pesquisa os contatado do acordo
   * @param lMostra
   */
  function js_pesquisaContratado(lMostra) {

    var sUrlOpenContratado = 'func_cgm.php?';
    if (lMostra) {
      sUrlOpenContratado += "funcao_js=parent.js_preencheContratado|z01_numcgm|z01_nome";
    } else {

      var iCgmFormulario = $F('ac16_contratado');
      sUrlOpenContratado += "pesquisa_chave="+iCgmFormulario+"&funcao_js=parent.js_completaContratado";
    }

    js_OpenJanelaIframe(
      'CurrentWindow.corpo',
      'func_nome',
      sUrlOpenContratado,
      'Pesquisa CGM',
      lMostra
    );
  }

  /**
   * @param iCodigo
   * @param sNome
   */
  function js_preencheContratado(iCodigo, sNome) {

    $('ac16_contratado').value = iCodigo;
    $('z01_nome').value        = sNome;
    func_nome.hide();
  }

  /**
   * @param lErro
   * @param sNome
   */
  function js_completaContratado(lErro, sNome) {

    $('z01_nome').value = sNome;
    if (lErro) {
      $('ac16_contratado').value = '';
    }
  }

  $('ac16_contratado').observe('change',
    function () {
      if ($('ac16_contratado').value == "") {
        $('z01_nome').value = "";
      }
    }
  );
</script>
