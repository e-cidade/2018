<?
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

//MODULO: laboratorio
$oRotulo = new rotulocampo;
$oRotulo->label("la02_i_codigo");
$oRotulo->label("sd62_c_nome");
$oRotulo->label("descrdepto");
$oRotulo->label("la08_i_codigo");
$oRotulo->label("sd60_c_nome");
$oRotulo->label("sd61_c_nome");
?>
<form name="form1" method="post" action="" id="frmFisicoFinanceiro">
<center>
<table border="0" width="100%">
  <tr>
  <?
  // Nenhum tipo de controle foi informado ainda, então, exibo o select com as opções
  if (!isset($iSelectControle) || empty($iSelectControle) || $iSelectControle < 1 || $iSelectControle > 4) {
  ?>
    <tr>
      <td nowrap title="Selecione um tipo de controle." align="center">
        <?
        $aX = array('1' => 'DEPARTAMENTO SOLICITANTE', '2' => 'LABORATÓRIO', '3' => 'GRUPO DE EXAME', '4' => 'EXAME');
        db_select('iSelectControle', $aX, true, 1, '');
        $confirmar = 'Confirmar';
        db_input('confirmar', 10, '', true, 'submit', 1, '');
        ?>
      </td>
    </tr>
  <?
  } elseif ($iSelectControle == 1 || $iSelectControle == 2) { // DEPARTAMENTO OU LABORATÓRIO

    echo "<tr>\n";
    if ($iSelectControle == 1) { // DEPARTAMENTO

      $sLabel = 'Departamento';
  ?>
      <td nowrap>
        <b><?=$sLabel?>:</b>
        <?
        $oDaoDbDepart = db_utils::getdao('db_depart');
        $sSql         = $oDaoDbDepart->sql_query_file(null, 'coddepto, descrdepto', 'coddepto');
        $rs           = $oDaoDbDepart->sql_record($sSql);
        $aX           = array();
        for ($iCont = 0; $iCont < $oDaoDbDepart->numrows; $iCont++) {

          $oDados                = db_utils::fieldsmemory($rs, $iCont);
          $aX[$oDados->coddepto] = $oDados->coddepto.' - '.$oDados->descrdepto;

        }
        db_select('iLabDepto', $aX, true, 1,
                  "onchange=\"window.frames['iframeControle'].js_getInfoControleFisicoFinanceiro();\""
                 );
        ?>
      </td>
  <?
    } else {

      $sLabel = 'Laboratório';
  ?>
      <td nowrap align="center">
        <b><?=$sLabel?>:</b>
        <?
        $oDaoLabLaboratorio = db_utils::getdao('lab_laboratorio');
        $sSql               = $oDaoLabLaboratorio->sql_query_file(null, 'la02_i_codigo, la02_c_descr');
        $rs                 = $oDaoLabLaboratorio->sql_record($sSql);
        $aX                 = array();
        for ($iCont = 0; $iCont < $oDaoLabLaboratorio->numrows; $iCont++) {

          $oDados                     = db_utils::fieldsmemory($rs, $iCont);
          $aX[$oDados->la02_i_codigo] = $oDados->la02_c_descr;

        }
        db_select('iLabDepto', $aX, true, 1,
                  "onchange=\"window.frames['iframeControle'].js_getInfoControleFisicoFinanceiro();\""
                 );
        ?>
      </td>
  <?
    }
  ?>
      <td align="left" width="20%" nowrap>
        <fieldset style='width: 10%;'> <legend><b>Controle por:</b></legend>
          <input type="radio" value="1" name="iRadioControles" onchange="js_loadFrame();" checked>
          <?=$sLabel?>
          <br>
          <?
          if ($iSelectControle == 1) {
          ?>
            <input type="radio" value="4" name="iRadioControles" onchange="js_loadFrame();">
            Laboratório
            <br>
          <?
          }
          ?>
          <input type="radio" value="2" name="iRadioControles" onchange="js_loadFrame();">
          Exame
          <br>
          <input type="radio" value="3" name="iRadioControles" onchange="js_loadFrame();">
          Grupo de Exame
        </fieldset>

      </td>
    </tr>
  <?
  } else { // EXAME OU GRUPO DE EXAMES

    // GRUPO DE EXAMES
    if ($iSelectControle == 3) {
      $sLabel = 'GRUPO DE EXAMES';
    } else {
      $sLabel = 'EXAMES';
    }
  ?>
    <tr>
      <td align="center">
        <b>Controle Físico / Financeiro: </b>
        <input type="text" disabled value="<?=$sLabel?>">
      </td>
    </tr>
  <?
  }

  // Nenhum tipo de controle foi informado ainda, então, não exibo o iframe também
  if (isset($iSelectControle) && !empty($iSelectControle) && $iSelectControle > 0 && $iSelectControle < 5) {
  ?>
    <tr> <!-- IFRAME -->
      <td colspan="4" width="100%" height="370">
        <iframe src="" style="overflow: auto; overflow-y: hidden;" frameborder="0" width="100%" height="100%"
          id="iframeControle" name="iframeControle">
          Sem suporte a IFrame
        </iframe>
      </td>
    </tr>
  <?
  }
  ?>
</table>
</center>

</form>
<script>

iIndexSelect = null;

<?
if (isset($iSelectControle) && !empty($iSelectControle) && $iSelectControle > 0 && $iSelectControle < 5) {
  echo "js_loadFrame();\n";
}
?>
/*
Recarrega o frame com as informações do controle de acordo com o tipo de controle escolhido no radio box
e no select da outra tela
*/
function js_loadFrame() {

  var iControle = <?=isset($iSelectControle) ? "'$iSelectControle'" : "''"?>;
  var iValor    = 0;
  var iFrame    = $('iframeControle');
  var oRadios   = document.getElementsByName('iRadioControles');
  var sUrl      = 'lab4_controlefisicofinanceiro002.php';

  for (var iCont = 0; iCont < oRadios.length; iCont++) {

    if (oRadios[iCont].checked) {
      iValor = oRadios[iCont].value;
    }

  }

  if (iControle == '1') {

    if (iValor == 1) {
      iFrame.src = sUrl+'?iTipoControle=1';
    } else if (iValor == 2) {
      iFrame.src = sUrl+'?iTipoControle=2';
    } else if (iValor == 3) {
      iFrame.src = sUrl+'?iTipoControle=3';
    } else if (iValor == 4) {
      iFrame.src = sUrl+'?iTipoControle=9';
    } else {
      iFrame.src = '';
    }

  } else if (iControle == '2') {

    if (iValor == 1) {
      iFrame.src = sUrl+'?iTipoControle=4';
    } else if (iValor == 2) {
      iFrame.src = sUrl+'?iTipoControle=5';
    } else if (iValor == 3) {
      iFrame.src = sUrl+'?iTipoControle=6';
    } else {
      iFrame.src = '';
    }

  } else if (iControle == '3') {
    iFrame.src = sUrl+'?iTipoControle=7';
  } else if (iControle == '4') {
    iFrame.src = sUrl+'?iTipoControle=8';
  } else {
    iFrame.src = '';
  }

}

/* Repassagem de dados para o iframe */
function js_mostralab_exame(chave, erro) {
  window.frames['iframeControle'].js_mostralab_exame(chave, erro);
}
function js_mostralab_exame1(chave1, chave2) {
  window.frames['iframeControle'].js_mostralab_exame1(chave1, chave2);
}

function js_mostralab_laboratorio(chave, erro) {
  window.frames['iframeControle'].js_mostralab_laboratorio(chave, erro);
}
function js_mostralab_laboratorio1(chave1, chave2) {
  window.frames['iframeControle'].js_mostralab_laboratorio1(chave1, chave2);
}

function js_mostrasau_grupo(chave1, chave2, chave3) {
  window.frames['iframeControle'].js_mostrasau_grupo(chave1, chave2, chave3);
}

function js_mostrasau_subgrupo(chave1, chave2, chave3) {
  window.frames['iframeControle'].js_mostrasau_subgrupo(chave1, chave2, chave3);
}

function js_mostrasau_formaorganizacao(chave1, chave2, chave3) {
  window.frames['iframeControle'].js_mostrasau_formaorganizacao(chave1, chave2, chave3);
}

var sRPCCotasAtendimento = "lab4_cotasatendimento.RPC.php";
var sFonteMensagem       = "saude.laboratorio.db_frmlab_controlefisicofinanceiro.";

(function() {

  new AjaxRequest(sRPCCotasAtendimento, {exec: 'verificaUsoCotas'}, function(oRetorno, lErro) {

    if(lErro){
      alert(oRetorno.sMessage);
      return;
    }

    if ( oRetorno.tipo == 2) {

      setFormReadOnly( $('frmFisicoFinanceiro'), true );
      alert( _M( sFonteMensagem + 'configuracao_cotas_cadastrado' ) );
      return;
    }

    buscaDadosLaboratorio();
  }).setMessage( _M( sFonteMensagem + 'validando_parametros' ) ).execute();

})();

</script>