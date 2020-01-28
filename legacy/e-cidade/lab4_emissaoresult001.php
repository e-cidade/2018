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

require_once(modification("dbforms/db_funcoes.php"));
require_once(modification('libs/db_stdlibwebseller.php'));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification('libs/db_utils.php'));
require_once(modification('libs/db_app.utils.php'));

$cllab_labsetor   = new cl_lab_labsetor;
$cllab_requisicao = new cl_lab_requisicao;
$cllab_exame      = new cl_lab_exame;
$clrotulo         = new rotulocampo;

$clrotulo->label("la08_c_descr");
$clrotulo->label("la21_i_codigo");
$clrotulo->label("la23_i_codigo");
$clrotulo->label("la23_c_descr");
$clrotulo->label("la24_i_codigo");

/**
 * Função para descobrir o laboratorio que o usuario esta logado
 * @return integer Codigo do laboratorio logado
 */
function laboratorioLogado(){

  $iUsuario        = db_getsession('DB_id_usuario');
  $iDepto          = db_getsession('DB_coddepto');
  $oLab_labusuario = new cl_lab_labusuario();
  $oLab_labdepart  = new cl_lab_labdepart();

  $sCampos = 'la02_i_codigo, la02_c_descr';
  $sql     = $oLab_labusuario->sql_query( null, $sCampos, "la02_i_codigo", " la05_i_usuario = {$iUsuario}" );
  $rResult = $oLab_labusuario->sql_record($sql);

  if ($oLab_labusuario->numrows == 0) {

    $sCampos = 'la02_i_codigo, la02_c_descr';
    $sql     = $oLab_labdepart->sql_query(null, $sCampos, "la02_i_codigo", "la03_i_departamento = {$iDepto}");
    $rResult = $oLab_labdepart->sql_record($sql);

    if ($oLab_labdepart->numrows == 0) {
      return false;
    }
  }

  $oLab = db_utils::getCollectionByRecord($rResult);
  return $oLab[0]->la02_i_codigo;
}

$iLaboratorioLogado = laboratorioLogado();

if ( isset( $requisicao ) ) {

  if ( $sSituacao != '6 - importado' ) {

    $sParametros = "requisicao={$requisicao}&requiitem={$requiitem}&iLabSetor={$iLabSetor}&iModelo=$iModelo";
    echo "<script>
            jan = window.open( 'lab4_emissaoresultnovo002.php?{$sParametros}', '', 'width=1000,height=600' );
          </script>";
  }else{

    $oDaoLabEmissao = new cl_lab_emissao();
    $sWhere         = "la34_i_requiitem = {$requiitem}";

    if ( !empty($iLabSetor) ) {
      $sWhere .= " and la24_i_codigo = {$iLabSetor}";
    }

    $sCampos   = "la34_o_laudo, la34_c_nomearq";
    $sSql      = $oDaoLabEmissao->sql_query_labsetor(null, $sCampos, "la34_d_data desc, la34_c_hora desc", $sWhere);
    $rsEmissao = $oDaoLabEmissao->sql_record($sSql);

    if ($oDaoLabEmissao->numrows > 0) {

      $oEmissao = db_utils::fieldsmemory($rsEmissao,0);
      db_inicio_transacao();
      if (pg_lo_export($oEmissao->la34_o_laudo,'tmp/'.$oEmissao->la34_c_nomearq,$conn)) {

        ?>
        <script>
          jan = window.open('tmp/<?=$oEmissao->la34_c_nomearq?>',
                            '',
                            'width='+(screen.availWidth-5)+
                            ',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
        </script>
        <?php

      } else {
        db_msgbox("Erro durante abertura do arquivo!");
      }

      db_fim_transacao();
    } else {
      db_msgbox("Arquivo importado, porém não à registro de emissão!");
    }
  }
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/DBFormCache.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/DBFormSelectCache.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<?php if ( $iLaboratorioLogado == 0 ) { ?>
    <table width='100%'>
      <tr>
        <td align='center'>
          <br><br>
          <font color='#FF0000' face='arial'>
            <b>Usuário ou departamento não consta como laboratório!<br>
            </b>
          </font>
        </td>
      </tr>
    </table>
    </center>
<?php  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    exit;
  }
?>
  <div class='container'>
    <fieldset>
      <legend>Emissão/Reemissão de Resultado</legend>
      <form name='form1'>
        <table class="form-container">
          <tr>
            <td title="<?=@$Tla22_i_codigo?>">
              <?php db_ancora ( '<b>Requisição:</b>', "js_pesquisala22_i_codigo(true);", "" );?>
            </td>
            <td>
              <?php db_input ( 'la22_i_codigo', 10, @$Ila22_i_codigo, true, 'text',"", " onchange='js_pesquisala22_i_codigo(false);'" )?>
              <?php db_input ( 'z01_v_nome2', 50, @$Iz01_v_nome, true, 'text', 3, '' )?>
            </td>
          </tr>
          <tr>
            <td title="requiitem">
              <label for='la08_i_codigo'><?php db_ancora ( '<b>Exame:</b>', "js_pesquisala21_i_codigo(true);", "" );?></label>
            </td>
            <td>
              <?php db_input ( 'la08_i_codigo',   10, @$Ila08_i_codigo,   true, 'text',   "", " onchange='js_pesquisala21_i_codigo(false);'" )?>
              <?php db_input ( 'la21_i_codigo',   10, @$Ila21_i_codigo,   true, 'hidden', "", "")?>
              <?php db_input ( 'la21_c_situacao', 10, @$Ila21_c_situacao, true, 'hidden', "", "")?>
              <?php db_input ( 'la08_c_descr',    50, @$Ila08_c_descr,    true, 'text',    3, '')?>
             </td>
          </tr>
          <tr>
            <td>
              <label for="la23_i_codigo"><?php db_ancora ( 'Setor:', "js_pesquisala23_i_codigo(true);", "" );?></label>
            </td>
            <td>
              <?php db_input ( 'la23_i_codigo', 10, $Ila23_i_codigo, true, 'text', "", " onchange='js_pesquisala23_i_codigo(false);'" )?>
              <?php db_input ( 'la23_c_descr',  50, $Ila23_c_descr,  true, 'text', 3, '' )?>
              <?php db_input ( 'la24_i_codigo', 10, $Ila24_i_codigo, true, 'hidden', "", "")?>
            </td>
          </tr>
          <tr>
            <td><label for="modelo">Modelo: </label></td>
            <td>
              <select id='modelo'>
                <option value="1">Modelo 1</option>
                <option value="2">Modelo 2</option>
              </select>
            </td>
          </tr>
        </table>
      </form>
    </fieldset>
    <input name='start' type='button' value='Gerar' onclick="js_mandaDados()">
  </div>
<?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>
</body>
</html>
<script>
function js_limpaCamposTrocaReq() {

  $('la08_i_codigo').value   = '';
  $('la21_i_codigo').value   = '';
  $('la21_c_situacao').value = '';
  $('la08_c_descr').value    = '';
  $('la23_i_codigo').value   = '';
  $('la23_c_descr').value    = '';
  $('la24_i_codigo').value   = '';
}

function js_pesquisala22_i_codigo(mostra) {

  if( mostra == true) {
    js_OpenJanelaIframe(
                         '',
                         'db_iframe_lab_requisicao',
                         'func_lab_requisicao.php?iLaboratorioLogado=<?=$iLaboratorioLogado?>'
                                               +'&autoriza=2'
                                               +'&funcao_js=parent.js_mostrarequisicao1|la22_i_codigo'
                                                                                     +'|z01_v_nome',
                         'Pesquisa',
                         true
                       );
  } else {

    if( document.form1.la22_i_codigo.value != '') {
      js_OpenJanelaIframe(
                           '',
                           'db_iframe_lab_requisicao',
                           'func_lab_requisicao.php?iLaboratorioLogado=<?=$iLaboratorioLogado?>'
                                                 +'&autoriza=2'
                                                 +'&pesquisa_chave='+document.form1.la22_i_codigo.value
                                                 +'&funcao_js=parent.js_mostrarequisicao',
                           'Pesquisa',
                           false
                         );
    } else {
      document.form1.z01_v_nome2.value = '';
    }
  }
}

function js_mostrarequisicao(chave, erro) {

  document.form1.z01_v_nome2.value = chave;

  if( erro == true ) {

    document.form1.la22_i_codigo.focus();
    document.form1.la22_i_codigo.value = '';
  }

  js_limpaCamposTrocaReq();
}

function js_mostrarequisicao1(chave1, chave2){

  document.form1.la22_i_codigo.value = chave1;
  document.form1.z01_v_nome2.value   = chave2;
  db_iframe_lab_requisicao.hide();
  js_limpaCamposTrocaReq();
}

function js_pesquisala21_i_codigo(mostra){

  if(document.form1.la22_i_codigo.value == '') {

    alert('Escolha uma requisição primeiro.');
    js_limpaCamposTrocaReq();
    return false;
  }

  sPesq  = 'la21_i_requisicao='+document.form1.la22_i_codigo.value;
  sPesq += '&iLaboratorioLogado=<?=$iLaboratorioLogado?>&sSituacao=|7 - Conferido|,|6 - importado|,|3 - Entregue|';

  if(mostra == true) {
    js_OpenJanelaIframe(
                         '',
                         'db_iframe_lab_requiitem',
                         'func_lab_requiitem.php?'+sPesq+'&funcao_js=parent.js_mostrarequiitem1|la08_i_codigo|la08_c_descr'
                                                                                             +'|la21_i_codigo|la21_c_situacao',
                         'Pesquisa',
                         true
                       );
  } else {

    if(document.form1.la08_i_codigo.value != '') {
       js_OpenJanelaIframe(
                            '',
                            'db_iframe_lab_requiitem',
                            'func_lab_requiitem.php?'+sPesq+'&pesquisa_chave='+document.form1.la08_i_codigo.value
                                                     +'&funcao_js=parent.js_mostrarequiitem',
                            'Pesquisa',
                            false
                          );
    } else {

      document.form1.la08_c_descr.value    = '';
      document.form1.la21_c_situacao.value = '';
    }
  }
}

function js_mostrarequiitem(chave, erro, requiitem, situacao) {

  document.form1.la08_c_descr.value = chave;

  if(erro == true) {

    document.form1.la08_i_codigo.focus();
    document.form1.la08_i_codigo.value = '';
  } else {

    document.form1.la21_i_codigo.value   = requiitem;
    document.form1.la21_c_situacao.value = situacao;
  }
}

function js_mostrarequiitem1(chave1, chave2, requiitem, situacao) {

  document.form1.la08_i_codigo.value   = chave1;
  document.form1.la08_c_descr.value    = chave2;
  document.form1.la21_i_codigo.value   = requiitem;
  document.form1.la21_c_situacao.value = situacao;
  db_iframe_lab_requiitem.hide();
}

function js_mandaDados() {

  oF = document.form1;

  if(!js_validaDados()) {
    return false;
  }

  oDBFormCache.save();

  iRequisicao   = 'requisicao=' + oF.la22_i_codigo.value;
  iRequiitem    = '&requiitem=' + oF.la21_i_codigo.value;
  sSituacao     = '&sSituacao=' + oF.la21_c_situacao.value;
  iLabSetor     = '&iLabSetor=' + $F('la23_i_codigo');
  iModelo       = '&iModelo='   + $F('modelo');
  location.href = 'lab4_emissaoresult001.php?' + iRequisicao + iRequiitem + sSituacao + iLabSetor + iModelo;
}

function js_validaDados(){

  oF = document.form1;

  if(oF.la22_i_codigo.value == '') {

    alert('Preencha os dados do formulario.');
    return false;
  }

  return true;
}

/**
 * Função para buscar os setores cadastrados para o laboratório que o usuário está logado
 * @param  {boolean} lMostra
 */
function js_pesquisala23_i_codigo( lMostra ) {

  if ( empty( $F('la22_i_codigo') ) ) {

    alert("Preencha primeiro a Requisição.");
    $('la23_i_codigo').value = '';
    return;
  }

  var sGet  = 'la24_i_laboratorio=' + <?php echo $iLaboratorioLogado?>;
      sGet += "&la22_i_codigo= "    + $F('la22_i_codigo');

  if (lMostra) {

    sGet += '&funcao_js=parent.js_mostralab_labsetor1|la24_i_setor|la23_c_descr|la24_i_codigo';
    js_OpenJanelaIframe( '', 'db_iframe_lab_labsetor', 'func_lab_labsetor.php?' + sGet, 'Pesquisa', true);
  } else {

    if ( $F('la23_i_codigo') != '' ) {

      sGet += '&pesquisa_chave=' + $F('la23_i_codigo') + '&funcao_js=parent.js_mostralab_labsetor';
      js_OpenJanelaIframe( '', 'db_iframe_lab_labsetor', 'func_lab_labsetor.php?' + sGet, 'Pesquisa', false);
    } else {
      $('la23_c_descr').value = '';
    }
  }
}

/**
 * Função chamada após selecionar o setor desejado.
 * Altera os valores do código do setor e da descrição pelo selecionado.
 * @param  {integer} iCodigoSetor
 * @param  {string}  sDescricaoSetor
 */
function js_mostralab_labsetor1( iCodigoSetor, sDescricaoSetor, iCodigoLabSetor ) {

  $('la23_i_codigo').value = iCodigoSetor;
  $('la23_c_descr').value  = sDescricaoSetor;
  $('la24_i_codigo').value = iCodigoLabSetor;
  db_iframe_lab_labsetor.hide();
}

/**
 * Função chamada após digitado código do setor desejado.
 * Caso código exista, preenche o campo descrição do setor com o valor referênte.
 * @param  {string}  sDescricaoSetor
 * @param  {boolean} lErro
 */
function js_mostralab_labsetor( sDescricaoSetor, lErro, iCodigoLabSetor) {

  $('la23_c_descr').value  = sDescricaoSetor;
  $('la24_i_codigo').value = iCodigoLabSetor;

  if ( lErro ) {

    $('la23_i_codigo').focus();
    $('la23_i_codigo').value = '';
    $('la24_i_codigo').value = '';
  }
}

var oDBFormCache = new DBFormCache('oDBFormCache', 'lab4_emissaoresult001.php');
(function () {

  oDBFormCache.setElements(new Array($('modelo')));
  oDBFormCache.load();
})();
</script>