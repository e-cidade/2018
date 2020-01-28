<?php
/**
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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($_POST);
$datai_dia = '';
$datai_mes = '';
$datai_ano = '';
$dataf_dia = '';
$dataf_mes = '';
$dataf_ano = '';


$clrhpessoal = new cl_rhpessoal;
$classenta = new cl_assenta;
$clrotulo = new rotulocampo;
$clrotulo->label('rh01_regist');
$clrotulo->label('z01_nome');
$clrotulo->label('h12_codigo');
$clrotulo->label('h12_assent');
$clrotulo->label('h12_descr');

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
  <div class="container">
    <form name="form1" method="post">
      <fieldset>
        <legend>Assentamentos do Servidor</legend>
        <table class="form-container">
          <tr>
            <td title="<?=@$Trh01_regist?>">
              <label for="rh01_regist"><?php db_ancora(@$Lrh01_regist,"js_pesquisarh01_regist(true);",1); ?></label>
            </td>
            <td>
              <?php
                db_input('rh01_regist',6,$Irh01_regist,true,'text',1,"onchange='js_pesquisarh01_regist(false);'");
                db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label for="datai">Período:</label>
            </td>
            <td>
              <?php
                db_inputdata('datai', $datai_dia, $datai_mes, $datai_ano,true,'text',1,"");
              ?>
              &nbsp;<b>a</b>&nbsp;
              <?php
                db_inputdata('dataf', $dataf_dia, $dataf_mes, $dataf_ano,true,'text',1,"");
              ?>
            </td>
          </tr>
          <tr>
            <td title="<?=@$Th12_codigo?>">
              <label for="h12_assent"><?php db_ancora(@$Lh12_codigo,"js_pesquisah12_codigo(true);",1); ?></label>
            </td>
            <td >
              <?php
                db_input('h12_codigo',6,$Ih12_codigo,true,'hidden',3,"");
                db_input('h12_assent',6,$Ih12_assent,true,'text',1," onchange='js_pesquisah12_codigo(false);'");
                db_input('h12_descr',40,$Ih12_descr,true,'text',3,'');
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="consulta" id="consulta" type="button" value="Consulta" onclick="js_consulta();" >
    </form>
  </div>
<?php
  db_menu();
?>
</body>
</html>
<script>
function js_consulta() {

  var datInici = document.form1.datai_ano.value + "-" + document.form1.datai_mes.value + "-" + document.form1.datai_dia.value;
  var datFinal = null;

  dtDatInici = new Date(document.form1.datai_ano.value, document.form1.datai_mes.value, document.form1.datai_dia.value);
  if (document.form1.rh01_regist.value == "") {

    alert("Informe a matrícula do funcionário!");
    document.form1.rh01_regist.focus();
    return;
  }

  if( empty($F('datai')) ) {

    alert("Informe a data inicial!");
    document.form1.datai_dia.focus();
    return;
  }

  var qry  = "?codAssen=" + document.form1.h12_codigo.value;
      qry += "&codMatri=" + document.form1.rh01_regist.value;
      qry += "&dataIni=" + datInici;

  if ( !empty( $F('dataf') ) ) {

    qry += "&dataFim=" + document.form1.dataf_ano.value + "-" + document.form1.dataf_mes.value + "-" + document.form1.dataf_dia.value;
    var dtDatFinal = new Date(document.form1.dataf_ano.value, document.form1.dataf_mes.value, document.form1.dataf_dia.value);
    if (dtDatInici > dtDatFinal) {

      alert("Período de data inválido. Data final maior que data inicial.");
      document.form1.dataf_dia.focus();
      return;
    }
  }

  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tipoasse','rec3_consafastfunc002.php' + qry,'Consulta',true);
}

function limparCampos() {

  $('datai').value = '';
  $('dataf').value = '';
  $('h12_assent').value = '';
  $('h12_codigo').value = '';
  $('h12_assent').value = '';
  $('h12_descr').value = '';
}

function js_pesquisarh01_regist(mostra) {

  limparCampos();
  if (mostra) {
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?filtro_lotacao=true&funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
  } else {

    if(document.form1.rh01_regist.value != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?filtro_lotacao=true&pesquisa_chave='+document.form1.rh01_regist.value+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
    }else{
      document.form1.z01_nome.value = '';
    }
  }
}
function js_mostrapessoal(chave,erro) {

  document.form1.z01_nome.value = chave;
  if ( erro ) {

    document.form1.rh01_regist.focus();
    document.form1.rh01_regist.value = '';
  }
}
function js_mostrapessoal1(chave1,chave2) {

  document.form1.rh01_regist.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_rhpessoal.hide();
}

function js_pesquisah12_codigo(mostra) {

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tipoasse','func_tipoasse.php?chave_codigo=true&funcao_js=parent.js_mostratipoasse1|h12_codigo|h12_assent|h12_descr','Pesquisa',true);
  }else{
    if(document.form1.h12_assent.value != ''){
       js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tipoasse','func_tipoasse.php?chave_assent='+document.form1.h12_assent.value+'&funcao_js=parent.js_mostratipoasse','Pesquisa',false);
    }else{
      document.form1.h12_descr.value = '';
      document.form1.h12_codigo.value = '';
    }
  }
}
function js_mostratipoasse(chave,chave2,erro,chave3) {

  document.form1.h12_descr.value = chave2;
  if (erro) {

    document.form1.h12_assent.focus();
    document.form1.h12_assent.value = '';
    document.form1.h12_codigo.value = '';
  } else {
    document.form1.h12_codigo.value = chave3;
  }
}
function js_mostratipoasse1(chave1,chave2,chave3){
  document.form1.h12_codigo.value = chave1;
  document.form1.h12_assent.value = chave2;
  document.form1.h12_descr.value = chave3;
  db_iframe_tipoasse.hide();
}
js_tabulacaoforms("form1","rh01_regist",true,0,"rh01_regist",true);
</script>