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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
require("libs/db_utils.php");
include("classes/db_base_classe.php");
include("classes/db_baseserie_classe.php");
include("classes/db_basemps_classe.php");
include("classes/db_basediscglob_classe.php");
include("classes/db_escolabase_classe.php");
include("classes/db_baseregimematdiv_classe.php");
include("classes/db_regimemat_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clbase             = new cl_base;
$clescolabase       = new cl_escolabase;
$clbaseserie        = new cl_baseserie;
$clbasemps          = new cl_basemps;
$clbasediscglob     = new cl_basediscglob;
$clbaseregimematdiv = new cl_baseregimematdiv;
$clregimemat        = new cl_regimemat;
$db_opcao           = 1;
$db_botao           = true;
$oGet               = db_utils::postMemory($_GET);
$clbase->rotulo->label();

if (isset($incluir)) {

  $erro_transacao = false;
  db_inicio_transacao();
  //base
  $rsBase = $clbase->sql_record($clbase->sql_query("", "base.*,ed218_c_divisao", "", " ed31_i_codigo = $ed31_i_codigo"));
  $oBase  = db_utils::fieldsmemory($rsBase, 0);
  
  $cod_regime               = explode("|", $ed31_i_regimemat_new);
  $clbase->ed31_i_curso     = $oBase->ed31_i_curso;
  $clbase->ed31_c_descr     = $ed31_c_descr;
  $clbase->ed31_c_turno     = $oBase->ed31_c_turno;
  $clbase->ed31_c_medfreq   = $oBase->ed31_c_medfreq;
  $clbase->ed31_c_contrfreq = $oBase->ed31_c_contrfreq;
  $clbase->ed31_t_obs       = $oBase->ed31_t_obs;
  $clbase->ed31_c_conclusao = $oBase->ed31_c_conclusao;
  $clbase->ed31_i_regimemat = $cod_regime[0];
  $clbase->ed31_c_ativo     = "S";
  $clbase->incluir(null);
  
  if ($clbase->erro_status == "0") {

    db_msgbox($clbase->erro_msg);
    $erro_transacao = true;
  }
  $iNewCodBase = $clbase->ed31_i_codigo;
  //basemps
  $rsBaseserie = $clbaseserie->sql_record($clbaseserie->sql_query_file("", "*", "", " ed87_i_codigo = {$oBase->ed31_i_codigo}"));
  $oBaseserie  = db_utils::fieldsmemory($rsBaseserie, 0);
  
  $clbaseserie->ed87_i_serieinicial = $oBaseserie->ed87_i_serieinicial;
  $clbaseserie->ed87_i_seriefinal   = $oBaseserie->ed87_i_seriefinal;
  $clbaseserie->incluir($iNewCodBase);
  if ($clbaseserie->erro_status == "0") {

    db_msgbox($clbaseserie->erro_msg);
    $erro_transacao = true;
  }
  
  //escolabase
  $clescolabase->ed77_i_escola   = db_getsession("DB_coddepto");
  $clescolabase->ed77_i_base     = $iNewCodBase;
  $clescolabase->ed77_i_basecont = null;
  $clescolabase->incluir(null);
  if ($clescolabase->erro_status == "0") {

    db_msgbox($clescolabase->erro_msg);
    $erro_transacao = true;
  }

  //baseregimematdiv
  if ($cod_regime[1] == "S") {

    for ($r = 0; $r < count($divisao); $r++) {

      $clbaseregimematdiv->ed224_i_regimematdiv = $divisao[$r];
      $clbaseregimematdiv->ed224_i_base = $iNewCodBase;
      $clbaseregimematdiv->incluir(null);
      if ($clbaseregimematdiv->erro_status == "0") {

        db_msgbox($clbaseregimematdiv->erro_msg);
        $erro_transacao = true;
      }
    }
  }
  //basemps
  $rsBasemps      = $clbasemps->sql_record($clbasemps->sql_query_file("", "*", "", " ed34_i_base = {$oBase->ed31_i_codigo}"));
  $iLinhasBasemps = $clbasemps->numrows;
  for ($r = 0; $r < $iLinhasBasemps; $r++) {

    $oBasemps = db_utils::fieldsmemory($rsBasemps, $r);
    
    $clbasemps->ed34_i_base                 = $iNewCodBase;
    $clbasemps->ed34_i_serie                = $oBasemps->ed34_i_serie;
    $clbasemps->ed34_i_disciplina           = $oBasemps->ed34_i_disciplina;
    $clbasemps->ed34_i_qtdperiodo           = $oBasemps->ed34_i_qtdperiodo;
    $clbasemps->ed34_i_chtotal              = $oBasemps->ed34_i_chtotal;
    $clbasemps->ed34_c_condicao             = $oBasemps->ed34_c_condicao;
    $clbasemps->ed34_i_ordenacao            = $oBasemps->ed34_i_ordenacao;
    $clbasemps->ed34_lancarhistorico        = $oBasemps->ed34_lancarhistorico        == 't' ? 'true' : 'false';
    $clbasemps->ed34_disiciplinaglobalizada = $oBasemps->ed34_disiciplinaglobalizada == 't' ? 'true' : 'false'; 
    $clbasemps->ed34_caracterreprobatorio   = $oBasemps->ed34_caracterreprobatorio   == 't' ? 'true' : 'false'; 
    $clbasemps->ed34_basecomum              = $oBasemps->ed34_basecomum              == 't' ? 'true' : 'false'; 
    
    $clbasemps->incluir(null);
    if ($clbasemps->erro_status == "0") {

      db_msgbox($clbasemps->erro_msg);
      $erro_transacao = true;
    }
  }
  
  db_fim_transacao();
  db_fim_transacao();
  if ($erro_transacao == true) {
    db_redireciona("edu1_replicabase001.php?codbase=" . $oBase->ed31_i_codigo);
  } else {

    db_msgbox("Replicação de base efetuada com sucesso!");
    ?>
    <script>
      parent.location.href = "edu1_base002.php?chavepesquisa=<?= $iNewCodBase ?>";
      parent.db_iframe_replicacao.hide();
    </script>
    <?
  }
  exit;
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
  <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a = 1" >
    <form name="form1" method="post">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
            <br>
            <fieldset style="width:95%"><legend><b>Replicação de Base Curricular</b></legend>
<?
$rsBase = $clbase->sql_record($clbase->sql_query("", "*", "", " ed31_i_codigo = {$oGet->codbase}"));
$oBase = db_utils::fieldsmemory($rsBase, 0);
$ed31_i_codigo = $oBase->ed31_i_codigo;
$ed31_c_descr_old = $oBase->ed31_c_descr;
?>
<? db_input('ed31_i_codigo', 10, @$Ied31_i_codigo, true, 'text', 3, "") ?>
<? db_input('ed31_c_descr_old', 40, @$Ied31_c_descr_old, true, 'text', 3, "") ?><br>
              <b>
                Este procedimento irá replicar a base curricular <?= $oBase->ed31_i_codigo ?> - <?= $oBase->ed31_c_descr ?>,
                alterando somente os dados modificados abaixo:<br><br>
                <table>
                  <tr>
                    <td>
                      <b>Regime de matrícula atual:</b>    
                    </td>
                    <td>
              <?= $oBase->ed218_i_codigo ?> - <?= $oBase->ed218_c_nome ?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <b>Novo Regime de Matrícula</b>
                    </td>
                    <td>  
<?
$rsRegimemat = $clregimemat->sql_record($clregimemat->sql_query("", "ed218_i_codigo,ed218_c_nome,ed218_c_divisao", "ed218_i_codigo", ""));
?>
                      <select name="ed31_i_regimemat_new" onchange="js_VerRegime(<?= $oBase->ed31_i_codigo ?>, this.value)">
                        <option value=""></option>
                      <?
                      for ($t = 0; $t < $clregimemat->numrows; $t++) {

                        $oRegimemat = db_utils::fieldsmemory($rsRegimemat, $t);
                        echo "<option value='" . $oRegimemat->ed218_i_codigo . "|" . $oRegimemat->ed218_c_divisao . "' >" . $oRegimemat->ed218_i_codigo . " - " . $oRegimemat->ed218_c_nome . "</option>";
                      }
                      ?>
                      </select>
                    </td>
                  </tr>
                  <tbody id="div_divisao">
                  </tbody>
                  <tr>
                    <td>    
                      <b>Novo Nome da Base:</b>
                    </td>
                    <td>
                        <? db_input('ed31_c_descr', 40, @$Ied31_c_descr, true, 'text', $db_opcao, "") ?><br>    
                    </td>  
                  </tr>
                  <tr>
                    <td colspan="2">    
                      <br>
                      <input type="submit" name="incluir" id="incluir" value="Incluir" onclick="return js_valida();">
                    </td>
                  </tr>
                </table>
            </fieldset>
          </td>
        </tr>
      </table>
    </form>
  </body>
</html>
<script>
  function js_verificadivisao() {

    tam = document.form1.divisao.length;
    if (tam == undefined) {

      if (document.form1.divisao.value == "N") {
        return true;
      } else {

        if (document.form1.divisao.checked == false) {
          return true;
        }

      }

    } else {

      checado = false;
      for (i = 0; i < tam; i++) {

        if (document.form1.divisao[i].checked == true) {

          checado = true;
          break;

        }

      }
      if (checado == false) {
        return true;
      }

    }

  }
  function js_VerRegime(codbase, codregime) {

    if (codregime != "") {

      js_divCarregando("Aguarde, verificando registro(s)", "msgBox");
      var sAction = 'PesquisaRegime';
      var url = 'edu1_baseRPC.php';
      parametros = 'sAction=' + sAction + '&codbase=' + codbase + '&codregime=' + codregime;
      var oAjax = new Ajax.Request(url, {method: 'post',
        parameters: parametros,
        onComplete: js_retornaPesquisaRegime
      });

    }

  }
  function js_retornaPesquisaRegime(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("(" + oAjax.responseText + ")");
    if (oRetorno.length > 1) {

      msg = "Atenção!\n\n";
      msg += "As etapas abaixo, pertencentes à base curricular <?= @$ed31_c_descr_old ?>, ";
      msg += "não possuem vínculo com o regime de matrícula selecionado (" + document.form1.ed31_i_regimemat_new.options[document.form1.ed31_i_regimemat_new.selectedIndex].text + ").\n\n";
      for (i = 1; i < oRetorno.length; i++) {

        with (oRetorno[i]) {
          msg += oRetorno[i] + "\n";
        }

      }
      msg += "\nVerifique o cadastro das etapas (Módulo Secretaria de Educação -> Cadastros -> Etapas)";
      alert(msg);
      document.form1.incluir.disabled = true;

    } else {

      if (oRetorno[0] == "S") {

        codreg = document.form1.ed31_i_regimemat_new.value.split("|");
        js_divisoes(codreg[0]);

      } else {
        js_divisoes(0);
      }
      document.form1.incluir.disabled = false;

    }

  }
  function js_divisoes(codregime, tipodml) {

    if (codregime == 0) {

      $('div_divisao').innerHTML = "";
      return false;

    }
    js_divCarregando("Aguarde, buscando registro(s)", "msgBox");
    var sAction = 'PesquisaDivisao';
    var url = 'edu1_baseRPC.php';
    parametros = 'sAction=' + sAction + '&regime=' + codregime;
    var oAjax = new Ajax.Request(url, {method: 'post',
      parameters: parametros,
      onComplete: js_retornaPesquisaDivisao
    });
  }

  function js_retornaPesquisaDivisao(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("(" + oAjax.responseText + ")");
    sHtml = '<tr>';
    sHtml += ' <td valign="top"><b>Divisão do Regime:</b>';
    sHtml += ' </td>';
    sHtml += ' <td>';
    if (oRetorno.length == 0) {

      sHtml += '  Nenhuma divisão cadastrada para o regime de matrícula selecionado.';
      sHtml += '  <input type="hidden" name="divisao[]" id="divisao" value="N">';

    } else {

      cont = 0;
      for (var i = 0; i < oRetorno.length; i++) {

        cont++;
        with (oRetorno[i]) {

          sHtml += '  <input type="checkbox" name="divisao[]" id="divisao" value="' + ed219_i_codigo + '" > ' + ed219_c_nome.urlDecode();
          if ((cont % 3) == 0) {
            sHtml += '<br>';
          }

        }

      }

    }
    sHtml += ' </td>';
    sHtml += '</tr>';
    $('div_divisao').innerHTML = sHtml;
  }
  function js_valida() {

    if (document.form1.ed31_i_regimemat_new.value == "") {

      alert("Informe o novo regime de matrícula!");
      return false;

    }
    if (document.form1.ed31_c_descr.value == "") {

      alert("Informe o novo nome da base!");
      return false;

    }

    codreg = document.form1.ed31_i_regimemat_new.value.split("|");
    if (codreg[1] == "S" && js_verificadivisao()) {

      alert("Informe alguma divisão do Regime de Matrícula!");
      return false;

    }
    return true;

  }
</script>