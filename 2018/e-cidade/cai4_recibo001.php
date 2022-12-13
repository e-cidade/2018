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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_cairetordem_classe.php"));
require_once(modification("classes/db_cgm_classe.php"));
require_once(modification("classes/db_procrec_classe.php"));
require_once(modification("classes/db_orctiporec_classe.php"));
require_once(modification("classes/db_protprocesso_classe.php"));
require_once(modification("classes/db_taxagruporeg_classe.php"));
require_once(modification("classes/db_numpref_classe.php"));
require_once(modification("classes/db_db_config_classe.php"));

$clcairetordem  = new cl_cairetordem;
$clcgm          = new cl_cgm;
$clprocrec      = new cl_procrec;
$clorctiporec   = new cl_orctiporec;
$clprotprocesso = new cl_protprocesso;
$cltaxagruporeg = new cl_taxagruporeg;
$clnumpref      = new cl_numpref;
$cldb_config    = new cl_db_config;

$clcgm->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k32_ordpag");
$clrotulo->label('j01_matric');
$clrotulo->label('p58_codproc');
$clrotulo->label('p58_numero');
$clrotulo->label('q02_inscr');
$clrotulo->label('k02_codigo');
$clrotulo->label('k02_drecei');
$clrotulo->label('codsubrec');
$clrotulo->label('k07_descr');
$clrotulo->label('k130_concarpeculiar');
$clrotulo->label('c58_descr');
$clrotulo->label('e60_codemp');

db_postmemory($HTTP_SERVER_VARS);

/*
 * Esta variável controla onde serão mostradas as LookUp's.
 *
 * Por padrão ela é setada com (window.CurrentWindow || parent.CurrentWindow).corpo, mas se estiver dentro de outro iframe, deve ser passado o nome deste frame
 * para que a lookup abra corretamente dentro deste iframe.
 *
 * Um exemplo é o cadastro de protocolo, que após incluir ou alterar o processo redireciona para este arquivo passando o
 * nome do iframe para abrir as LookUp's
 *
 */
$sIframeLocation = "(window.CurrentWindow || parent.CurrentWindow).corpo";
if (isset($sIframe)) {
  $sIframeLocation .= ".".$sIframe;
}

/**
 * Verifica se foi passado por parametro no menu a variavel reciboRetencao
 * para saber se esconde ou nao os dados do cgm
 */
if (isset($reciboRetencao) && $reciboRetencao == 'sim') {
  $lEmissaoReciboRetencao = true;
} else {
  $lEmissaoReciboRetencao = false;
}

$iInstitSessao = db_getsession('DB_instit');
$result        = $cldb_config->sql_record($cldb_config->sql_query_file($iInstitSessao, "cgc, db21_codcli"));
db_fieldsmemory($result, 0);

if (isset($mostramenu)&& $mostramenu == 't') {

  $historico    = $textarea;
  $p58_codproc  = $codproc;
  $dbopcao      = 3;
  $disabled     = 'disabled';
  if (isset($titulo) && $titulo == 'CGM') {
    $z01_numcgm = $origem;
  } else if (isset($titulo) && $titulo == 'MATRICULA') {
    $j01_matric = $origem;
  } else if (isset($titulo) && $titulo == 'INSCRICAO') {
    $q02_inscr = $origem;
  }
} else {

  $dbopcao  = 4;
  $disabled = '';
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js, strings.js, prototype.js");
db_app::load("estilos.css, grid.style.css");
?>
<style type="text/css">
.nomerecibo {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
  background-color: #CCCCCC;
  color: #000000;
  text-decoration: blink;
}

.fieldsetinterno {
  border:none;
  border-top:2px groove white;
  margin-top:10px;
  margin-bottom:10px;
  border-bottom: none;
  border-right: none;
  border-left: none;
}

#DBF_ender, #DBF_munic, #DBF_cep, #DBF_uf, #valor, #k02_drecei, #historico {
 width: 100%;
}

#nomeemp {
 width: 77%;
}

#o15_codigo {
 width: 22%;
}

#o15_codigodescr {
 width: 78%;
}

.table-box {
  padding:1px;
  border-right:1px inset black;
  border-bottom:1px inset black;
  border-bottom:1px outset white;
  border-right:1px outset white;
  background-color:#FFFFFF;
  cursor: default;
  empty-cells: show;
}
</style>
<script>


function js_novo() {

  if (confirm('Esse procedimento zera todos os campos desse formulario! Confirma?') == true) {
    location.href='cai4_recibo001.php';
  }
}


function js_gravareceita() {

  var tab      = document.getElementById('tab');
  var processa = true;

  // primeira fez q grava a taxa... anterior nullo
  if (document.form1.arretipoant.value == '') {

    document.form1.arretipoant.value = document.form1.arretipo.value;
    document.form1.descrarretipoant.value = document.form1.descrarretipo.value;
  } else if (document.form1.arretipoant.value != document.form1.arretipo.value) {

    alert('Taxa ou receitacom tipo de débito diferente do já selecionado');
    document.form1.arretipo.value            = document.form1.arretipoant.value;
    document.form1.descrarretipo.value       = document.form1.descrarretipoant.value;
    document.form1.k02_codigo.value          = '';
    document.form1.k02_drecei.value          = '';
    document.form1.o15_codigo.value          = '';
    document.form1.valor.value               = '';
    document.form1.k130_concarpeculiar.value = '';
    processa = false;
    return;
  }


  if (document.form1.k130_concarpeculiar.value == '') {

    alert('Você deve selecionar uma C.Peculiar/Cod de Aplicação antes de emitir o Recibo.');
    processa = false;
    return false;

  }

  if ( document.form1.k02_codigo.value == '') {

    alert('Receita não Selecionada. Verifique!');
    processa = false;
    document.form1.k02_codigo.focus();
    return;
  }

  if (processa == true) {

    var vlr = new Number(document.form1.valor.value);
    if (isNaN(vlr) || vlr == 0) {

       //if( document.form1.valor.value == '' && document.form1.valor.value == '0'){
       alert('Valor digitado nao esta correto. Verifique!');
       document.form1.valor.focus();
       processa = false;
       return
    }
  }


  for (i = 1; i < tab.rows.length; i++) {

    if (  document.form1.o15_codigo.value != tab.rows[i].cells[2].innerHTML) {

      alert('Recurso diferente. Faça outro recibo.');
      return;
    }

    if (document.form1.k02_codigo.value == tab.rows[i].cells[0].innerHTML) {

      if (document.form1.codsubrec.value == tab.rows[i].cells[3].innerHTML) {

        if (confirm('Receita e taxa já Digitada. Valor : '+tab.rows[i].cells[5].innerHTML+' \n Somar ao valor?')==true ) {

          var soma = new Number(tab.rows[i].cells[5].innerHTML) + Number(document.form1.valor.value);
          tab.rows[i].cells[5].innerHTML = soma;
          processa = false;
          break;
        } else {

          processa = true;
          if (document.form1.k130_concarpeculiar.value == tab.rows[i].cells[6].innerHTML) {
            var processa = false;
          }
          break;
        }
      } else {

        if (document.form1.codsubrec.value == tab.rows[i].cells[2].innerHTML) {

          if (confirm('Receita já Digitada. Valor : '+tab.rows[i].cells[5].innerHTML+' \n Somar ao valor?')==true ) {

            var soma = new  Number(tab.rows[i].cells[5].innerHTML) + Number(document.form1.valor.value);
            tab.rows[i].cells[5].innerHTML = soma;
            processa = false;
            break;
          } else {

            processa = true;
	          if (document.form1.k130_concarpeculiar.value == tab.rows[i].cells[6].innerHTML) {
	            var processa = false;
	          }
            break;
          }
        }
      }
    }
  }


  if (processa == true) {

    var NovaLinha             = tab.insertRow(tab.rows.length);
    NovaLinha.id              = 'id_'+tab.rows.length;

    NovaColuna                = NovaLinha.insertCell(0);
    NovaColuna.align          = 'left';
    NovaColuna.style.border   = '1px outset white';
    NovaColuna.innerHTML      = document.form1.k02_codigo.value;

    NovaColuna                = NovaLinha.insertCell(1);
    NovaColuna.align          = 'left';
    NovaColuna.style.border   = '1px outset white';
    NovaColuna.innerHTML      = document.form1.k02_drecei.value;

    NovaColuna                = NovaLinha.insertCell(2);
    NovaColuna.align          = 'left';
    NovaColuna.style.border   = '1px outset white';
    NovaColuna.innerHTML      = document.form1.o15_codigo.value;

    NovaColuna                = NovaLinha.insertCell(3);
    NovaColuna.align          = 'left';
    NovaColuna.style.border   = '1px outset white';
    NovaColuna.innerHTML      = document.form1.codsubrec.value;

    NovaColuna                = NovaLinha.insertCell(4);
    NovaColuna.align          = 'left';
    NovaColuna.style.border   = '1px outset white';
    NovaColuna.innerHTML      = document.form1.k07_descr.value;

    NovaColuna                = NovaLinha.insertCell(5);
    NovaColuna.align          = 'right';
    NovaColuna.style.border   = '1px outset white';
    NovaColuna.innerHTML      = document.form1.valor.value;

    NovaColuna                = NovaLinha.insertCell(6);
    NovaColuna.align          = 'right';
    NovaColuna.style.border   = '1px outset white';
    NovaColuna.innerHTML      = document.form1.k130_concarpeculiar.value;

    NovaColuna                = NovaLinha.insertCell(7);
    NovaColuna.align          = 'center';
    NovaColuna.style.border   = '1px outset white';
    NovaColuna.innerHTML      = '<input value="Excluir" type="button" onclick="js_removelinha(\'id_'+tab.rows.length+'\')">';
  }

  document.form1.k02_codigo.value          = '';
  document.form1.k02_drecei.value          = '';
  document.form1.codsubrec.value           = '';
  document.form1.k07_descr.value           = '';
  document.form1.valor.value               = '';
  document.form1.o15_codigo.value          = '';
  document.form1.k130_concarpeculiar.value = '';
  document.form1.c58_descr.value           = '';
  document.form1.o15_codigo.onchange();
  document.form1.k02_codigo.focus();

  js_desabilitaConCarPeculiar(false);
}

function js_processarecibo(hist) {

  var tab = document.getElementById('tab');
  var processa = true;
  if (tab.rows.length==1) {

    alert("Receitas nao Digitadas.");
    processa = false;
  }

  if (processa == true) {

    if(document.form1.reciboRetencao.value == 'sim' && document.form1.k32_ordpag.value == "" ){

      alert("Selecione a Ordem de pagamento .");
      processa = false;
    } else if (document.form1.z01_numcgm.value =="" && document.form1.q02_inscr.value =="" && document.form1.j01_matric.value =="") {

      alert("Selecione o Nome do Contribuinte.");
      processa = false;
    }
  }

  if (processa == true) {

    if (confirm('Confirma Emissao do Recibo?')==true) {

      var query_string = 'z01_numcgm='+document.form1.z01_numcgm.value+'&';
      query_string    += 'j01_matric='+document.form1.j01_matric.value+'&';
      query_string    += 'q02_inscr='+document.form1.q02_inscr.value+'&';
      query_string    += 'p58_codproc='+document.form1.p58_codproc.value+'&';

      var codrece = '';
      var vlrrece = '';
      var bota    = '';
      var codtaxa = '';
      var codrecu = '';
      var codcpca = '';

      for (i = 1; i < tab.rows.length; i++) {

        codrece += bota+tab.rows[i].cells[0].innerHTML;
        codtaxa += bota+tab.rows[i].cells[3].innerHTML;
        vlrrece += bota+tab.rows[i].cells[5].innerHTML;
        codrecu += bota+tab.rows[i].cells[2].innerHTML;
        codcpca += bota+tab.rows[i].cells[6].innerHTML;
        bota = 'YY';
      }

      if (document.form1.historico.value == '') {
        document.form1.historico.value = ' ';
      }

      query_string += '&historico='+escape(document.form1.historico.value);
      query_string += '&tipocert='+document.form1.tipo.value;
      query_string += '&mostramenu='+document.form1.mostramenu.value;
      query_string += '&codrece='+codrece;
      query_string += '&codcpca='+codcpca;
      query_string += '&codtaxa='+codtaxa;
      query_string += '&vlrrece='+vlrrece;
      query_string += '&historico='+escape(document.form1.historico.value);
      query_string += '&db_datausu='+document.form1.k00_dtoper_ano.value+'-'+document.form1.k00_dtoper_mes.value+'-'+document.form1.k00_dtoper_dia.value;
      query_string += '&k32_ordpag='+document.form1.k32_ordpag.value;
      query_string += '&codrecu='+codrecu;
      query_string += '&arretipo='+document.form1.arretipo.value;

      jan = window.open('cai4_recibo003.php?'+query_string,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
      if (document.form1.incproc.value!=""&&document.form1.incproc.value=='true') {
        parent.location.href='pro4_protprocesso001.php';
      }
    }
  }
}

function js_vertaxas() {

}

function js_removelinha(linha) {

  var tab = (document.all)?document.all.tab:document.getElementById('tab');
  for (i = 0; i < tab.rows.length; i++) {

    if(linha == tab.rows[i].id) {

      tab.deleteRow(i);
	    break;
	  }
  }
}
</script>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin=" 0" topmargin=" 0" marginwidth=" 0" marginheight=" 0" onLoad="a=1">
<form name="form1" action="" method="get">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
<?
  if (!isset($mostramenu) || $mostramenu == 'f') {
?>
  <tr>
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
<?
  }
?>
</table>
<br>
<center>
<fieldset style="width:40%;">
  <legend>
    <strong>Recibo de Receita</strong>
  </legend>
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <?
      db_input('reciboRetencao', 10, '', true, 'hidden', 3);
    ?>
    <tr id="infContr">
      <td nowrap="nowrap">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tr>
            <td nowrap="nowrap" valign="top">
              <table border="0" width="100%">
                <tr>
                  <td nowrap="nowrap" width="140px">
                    <?
                      db_ancora($Lz01_numcgm, 'js_mostranomes(true);', $dbopcao);
                    ?>
                  </td>
                  <td nowrap="nowrap" width="60px">
                    <?
                      if ( ( isset($p58_codproc) && $p58_codproc!="" ) && ( isset($incproc) && $incproc==true) ) {

                        $result_prot = $clprotprocesso->sql_record($clprotprocesso->sql_query($p58_codproc, "p58_numcgm,z01_nome,p58_obs as historico"));
                        if ($clprotprocesso->numrows > 0) {

                          db_fieldsmemory($result_prot,0);
                          $z01_numcgm = $p58_numcgm;
                        }
                      }

                      db_input('incproc', 10, '', true, 'hidden', 3);
                      db_input('z01_numcgm', 10, $Iz01_numcgm, true, 'text', $dbopcao, ' onchange="js_mostranomes(false);"');
                    ?>
                  </td>
                  <td nowrap="nowrap">
                    <?
                      db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3);
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap="nowrap">
                    <?
                      db_ancora($Lj01_matric,'js_mostramatriculas(true);',$dbopcao);
                    ?>
                  </td>
                  <td nowrap="nowrap" colspan="2">
                    <?
                      db_input('j01_matric',10,$Ij01_matric,true,'text',$dbopcao,'onchange="js_mostramatriculas(false);"');
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap="nowrap">
                    <?
                      db_ancora($Lq02_inscr, 'js_mostrainscricao(true)', $dbopcao);
                    ?>
                  </td>
                  <td nowrap="nowrap" colspan="2">
                    <?
                      db_input('q02_inscr', 10, $Iq02_inscr, true, 'text', $dbopcao, 'onchange="js_mostrainscricao(false);"');
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap="nowrap">
                    <?
                      db_ancora($Lp58_codproc, 'js_mostracodproc(true);', $dbopcao);
                    ?>
                  </td>
                  <td nowrap="nowrap" colspan="2">
                    <?
                      db_input('p58_codproc', 10, $Ip58_codproc, true, 'text', $dbopcao, 'onchange="js_mostracodproc(false);"');
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap="nowrap">
                    <b>Data Vencimento:</b>
                  </td>
                  <td nowrap="nowrap" colspan="2">
                    <?
                      if ($db21_codcli == 19985) {
                        $novadata  = mktime (0, 0, 0, date('m',db_getsession("DB_datausu")), date('d',db_getsession("DB_datausu"))+30, date('Y',db_getsession("DB_datausu")) );
                        $k00_dtoper     = date('Y-m-d',$novadata);
                        $k00_dtoper_dia = date('d',$novadata);
                        $k00_dtoper_mes = date('m',$novadata);
                        $k00_dtoper_ano = date('Y',$novadata);
                      } else {
                        $k00_dtoper     = date('Y-m-d',db_getsession("DB_datausu"));
                        $k00_dtoper_dia = date('d',db_getsession("DB_datausu"));
                        $k00_dtoper_mes = date('m',db_getsession("DB_datausu"));
                        $k00_dtoper_ano = date('Y',db_getsession("DB_datausu"));
                      }

                      $Ik00_dtoper = '9';
                      db_inputdata('k00_dtoper', $k00_dtoper_dia, $k00_dtoper_mes, $k00_dtoper_ano, true, 'text', $dbopcao);
                    ?>
                  </td>
                </tr>
              </table>
            </td>
            <td nowrap="nowrap" valign="top">
              <table border="0" width="100%">
                <tr>
                  <td nowrap="nowrap" colspan="4">&nbsp;</td>
                </tr>
                <tr>
                  <td nowrap="nowrap">
                    <?=$Lz01_ender?>
                  </td>
                  <td nowrap="nowrap" colspan="3">
                    <?
                      db_input('z01_ender', 40, $Iz01_ender, true, 'text', 3, '', 'DBF_ender');
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap="nowrap">
                    <?=$Lz01_munic?>
                  </td>
                  <td nowrap="nowrap" colspan="3">
                    <?
                      db_input('z01_munic', 20, $Iz01_munic, true, 'text', 3, '', 'DBF_munic');
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap="nowrap">
                    <?=$Lz01_cep?>
                  </td>
                  <td nowrap="nowrap">
                    <?
                      db_input('z01_cep', 8, $Iz01_cep, true, 'text', 3, '', 'DBF_cep');
                    ?>
                  </td>
                  <td nowrap="nowrap" width="50px" align="center">
                    <?=$Lz01_uf?>
                  </td>
                  <td nowrap="nowrap">
                    <?
                      db_input('z01_uf', 2, $Iz01_uf, true, 'text', 3, '', 'DBF_uf');
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap="nowrap" colspan="4">&nbsp;</td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <?
      /**
       * Só mostra campo ordem de pagamento se for emissão de recibo de retenção.
       */
      if ($lEmissaoReciboRetencao) {
    ?>
    <tr>
      <td nowrap="nowrap" valign="top">
        <table border="0" width="100%">
          <tr>
            <td nowrap="nowrap" width="140px">
              <?
                db_ancora($Lk32_ordpag, 'js_mostraordpag(true);', $dbopcao);
              ?>
            </td>
            <td nowrap="nowrap" width="60px">
              <?
                db_input('k32_ordpag', 10, $Ik32_ordpag, true, 'text', $dbopcao, 'onchange="js_mostraordpag(false);"');
              ?>
            </td>
            <td nowrap="nowrap">
              <b><?php echo @$Le60_codemp;?></b>
            </td>
            <td nowrap="nowrap" width="60px">
              <?
                db_input('codempenho', 10, '', true, 'text', 3);
              ?>
            </td>
            <td nowrap="nowrap" width="50px" align="center">
              <b>CGM:</b>
            </td>
            <td nowrap="nowrap">
              <?
                db_input('e60_numcgm', 10, '', true, 'text', 3);
                db_input('nomeemp', 40, '', true, 'text', 3);
              ?>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <?
      } else {
        db_input('k32_ordpag', 10, $Ik32_ordpag, true, 'hidden', 3);
      }
    ?>
    <tr>
      <td nowrap="nowrap" valign="top">
        <fieldset class="fieldsetinterno">
          <legend>
            <b>Dados da Receita/Taxas</b>
          </legend>
            <table border="0" width="100%" height="100%">
              <tr>
                <td nowrap="nowrap" width="130px">
                  <?
                    db_ancora(@$Lk02_codigo, "js_pesquisatabrec(true);", $dbopcao);
                  ?>
                </td>
                <td nowrap="nowrap" width="20px">
                  <?
                    db_input('k02_codigo', 10, $Ik02_codigo, true, 'text', $dbopcao, "onchange='js_pesquisatabrec(false);'");
                  ?>
                </td>
                <td nowrap="nowrap">
                  <?
                    db_input('codsubrec', 40, $Icodsubrec, true, 'hidden', 3);
                    db_input('k07_descr', 100, $Ik07_descr,true, 'hidden', 3);
                    db_input('k02_drecei', 40, $Ik02_drecei, true, 'text', 3);
                  ?>
                </td>
                <td nowrap="nowrap" width="20px">
                  <input name="taxa" value='Taxas' type="button" id="taxa" onclick="js_vertaxas()">
                </td>
                <td nowrap="nowrap" width="244px">
                  <input name="valor" type="text" id="valor" size="10" maxlength="10" onBlur="js_valor(this);"
                         onKeyUp="this.value = this.value.replace(',','.')">
                </td>
                <td nowrap="nowrap">
                  <input name="mostramenu" type="hidden" id="" value="">
                  <input name="tipo" type="hidden" id="" value="<?=@$tipo?>">
                  <input name="tipocert" type="hidden" id="" value="">
                  <input name="gravar" type="button" id="gravar" value="Gravar" onClick="js_gravareceita();">
                </td>
              </tr>
              <tr>
                <td nowrap="nowrap">
                  <b>Recurso:</b>
                </td>
                <td nowrap="nowrap" colspan="2">
                  <?
                    $dbwhere = " o15_datalimite is null or o15_datalimite > '".date('Y-m-d',db_getsession('DB_datausu'))."'";
                    $rs = $clorctiporec->sql_record($clorctiporec->sql_query(null,"o15_codigo,o15_descr","o15_codigo", $dbwhere));
                    db_selectrecord('o15_codigo', $rs, true, 2,'','','');
                  ?>
                </td>
                <td nowrap="nowrap" align="center">
                  <b>Tipo:</b>
                </td>
                <td nowrap="nowrap" colspan="4">
                  <input name="arretipo" type="text" value="<?=@$arretipo?>" size="10"
                         readonly style="background-color:#DEB887">
                  <input name="descrarretipo" type="text" value="<?=@$descrarretipo?>" size="30"
                         readonly style="background-color:#DEB887">
                  <input name="arretipoant" type="hidden" value="<?=@$arretipoant?>" size="10">
                  <input name="descrarretipoant" type="hidden" value="<?=@$descrarretipoant?>" size="20">
                </td>
              </tr>
              <tr id="boxConCarPeculiar">
                <td nowrap="nowrap" width="130px" id="lnkConcarpeculiar">
                  <?
                    db_ancora(@$Lk130_concarpeculiar, "js_pesquisak130_concarpeculiar(true);", $dbopcao);
                  ?>
                </td>
                <td nowrap="nowrap" width="20px">
                  <?
                    db_input('k130_concarpeculiar', 10, $Ik130_concarpeculiar, true, 'text', $dbopcao, "onchange='js_pesquisak130_concarpeculiar(false);'");
                  ?>
                </td>
                <td nowrap="nowrap">
                  <?
                    db_input('c58_descr', 40, $Ic58_descr, true, 'text', 3);
                  ?>
                </td>
                <td nowrap="nowrap" colspan="2">&nbsp;</td>
              </tr>
            </table>
          </legend>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td nowrap="nowrap">
        <fieldset>
          <legend>
            <b>Receitas Lançadas</b>
          </legend>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="tab" align="center" class="table-box">
              <tr class="table_header">
                <th class="linhagrid" width="46" align="center" nowrap>Receita</th>
                <th class="linhagrid" width="240" align="center" nowrap>Descri&ccedil;&atilde;o</th>
                <th class="linhagrid" width="15" align="center" nowrap>Rec</th>
                <th class="linhagrid" width="36" align="center" nowrap>Taxa</th>
                <th class="linhagrid" width="177" align="center" nowrap>Descri&ccedil;&atilde;o</th>
                <th class="linhagrid" width="109" align="center" nowrap>Valor</th>
                <th class="linhagrid" width="109" align="center" nowrap>CP/CA</th>
                <th class="linhagrid" width="67" align="center" nowrap>Cancela</th>
              </tr>
            </table>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td nowrap="nowrap">
        <fieldset>
          <legend>
            <b>Histórico</b>
          </legend>
          <textarea name="historico" cols="100" rows="5" id="historico" nowrap
                    onkeyup='js_controlatextarea(this.name, 900, "r");'><?=@$historico?></textarea>
        </fieldset>
      </td>
    </tr>
  </table>
</fieldset>
<table width="1000">
  <tr>
    <td nowrap="nowrap">&nbsp;</td>
  </tr>
  <tr>
    <td nowrap="nowrap" align="center">
      <input name="confirma" type="button" id="confirma" value="Confirmar"   onClick="js_processarecibo()">
      <input name="cancela"  type="button" id="cancela"  value="Limpa"       onclick="document.form1.historico.value=''">
      <input name="novo"     type="button" id="novo"     value="Novo Recibo" onclick="js_novo()">
    </td>
  </tr>
</table>
</center>
</form>
<?
 if (!isset($mostramenu) || $mostramenu == 'f') {
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
 }
?>
</body>
</html>
<script>
<?
  if ($lEmissaoReciboRetencao) {
    echo " document.getElementById('infContr').style.display = 'none'; ";
  }
?>



/*
 * funcao para verificar o grupo das receitas.
 * Nesta rotina nao permitiremos mais receitas do Grupo 11
 */
 function js_verificaReceita() {

  var sRPC          = "cai4_devolucaoadiantamento004.RPC.php";
  var iReceita         = $F("k02_codigo");
	var oParametros      = new Object();
 	var msgDiv           = "Verificando grupo receita selecionado \n Aguarde ...";

 	oParametros.exec     = 'verificaGrupoReceita';
 	oParametros.iReceita = iReceita;

  if (iReceita == '' || iReceita == null) {
    return false;
	}

 	js_divCarregando(msgDiv,'msgBox');

 	new Ajax.Request(sRPC,
 	                 {method: "post",
 	                  parameters:'json='+Object.toJSON(oParametros),
 	                  onComplete: js_retornoVerificacaoReceita
 	                 });
 }

 function js_retornoVerificacaoReceita(oAjax) {

 	js_removeObj('msgBox');
 	var oRetorno = eval("(" + oAjax.responseText + ")");

 	if (oRetorno.iStatus == '2') {

 	  alert(oRetorno.sMessage.urlDecode());
 	  $('k02_codigo') .value = '';
 	  $('k02_drecei').value = '';
 	  $('k07_descr') .value = '';
    $('codsubrec') .value = '';


 	  return false;
 	}
 }

 $('k02_codigo').observe("change", function() {
	 js_verificaReceita();
 });









var sUrlRpc = 'cai4_recibo.RPC.php';

function js_valor(obj) {

  if (obj.value < 0) {
    alert('Valor não pode ser negativo!!');
  }
}

function js_pesquisae60_codemp(mostra) {

  if (mostra==true) {
    js_OpenJanelaIframe("<?=$sIframeLocation?>",'db_iframe_empempenho02','func_empempenho.php?funcao_js=parent.js_mostraempempenho2|e60_codemp','Pesquisa',true);
  } else {
     // js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empempenho02','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesqui
  }
}

function js_controlatextarea(objt, max, dv) {

  obj = eval('document.form1.'+objt);
  atu = max-obj.value.length;
  if (obj.value.length > max) {

    alert('A mensagem pode ter no máximo '+max+' caracteres !');
    obj.value = obj.value.substr(0,max);
    obj.select();
    obj.focus();
  }

  if (obj.value.length == 0) {
    document.getElementById(eval('dv')).innerHTML = '';
  }
}

function js_linha(evt,val){
  if(evt.keyCode==13){
     return false;
  }

}

function js_mostraordpag(mostra) {
  if(mostra==true) {
     js_OpenJanelaIframe("<?=$sIframeLocation?>",'db_iframe_pagordem','func_pagordemalt.php?funcao_js=parent.js_mostraordpag1|e50_codord|e60_codemp|e60_anousu|e60_numcgm|z01_nome','Pesquisa',true);
  }else {
    if(document.form1.k32_ordpag.value!="") {
      js_OpenJanelaIframe("<?=$sIframeLocation?>",'db_iframe_pagordem','func_pagordemalt.php?pesquisa_chave='+document.form1.k32_ordpag.value+'&funcao_js=parent.js_mostraordpag2','Pesquisa',false);
    }
  }
}

function js_mostraordpag1(chave,emp,ano,cgm,nome){
var codemp = emp+'/'+ano;
  document.form1.k32_ordpag.value = chave;
  document.form1.codempenho.value = codemp;
  document.form1.e60_numcgm.value = cgm;
  document.form1.nomeemp.value    = nome;
  db_iframe_pagordem.hide();

  js_OpenJanelaIframe("<?=$sIframeLocation?>",'db_iframe_dadoscgm','func_pesquisadadosreten.php?pesquisa_chave='+document.form1.k32_ordpag.value+'','Pesquisa',false);

}

function js_mostraordpag2(chave,emp,ano,cgm,nome){

var codemp = emp+'/'+ano;
  document.form1.k32_ordpag.value = chave;
  document.form1.codempenho.value = codemp;
  document.form1.e60_numcgm.value = cgm;
  document.form1.nomeemp.value    = nome;

  js_OpenJanelaIframe("<?=$sIframeLocation?>",'db_iframe_dadoscgm','func_pesquisadadosreten.php?pesquisa_chave='+document.form1.k32_ordpag.value+'','Pesquisa',false);
}

function js_dadosCgm(cgm,nome,munic,cep,uf,ender,erro){

  if (!erro) {

    document.form1.z01_numcgm.value = cgm;
    document.form1.z01_nome.value   = nome;
    document.form1.DBF_munic.value  = munic;
    document.form1.DBF_cep.value    = cep;
    document.form1.DBF_uf.value     = uf;
    document.form1.DBF_ender.value  = ender;

  }
}

function js_mostracodproc(mostra) {

  if(mostra==true) {

    js_OpenJanelaIframe(
      "<?=$sIframeLocation?>",
      'db_iframe_proc',
      'func_protprocesso.php?funcao_js=parent.js_mostraproc1|p58_codproc|p58_numcgm|z01_nome',
      'Pesquisa',
      mostra
    );
  } else {

    if(document.form1.p58_codproc.value != '') {

      js_OpenJanelaIframe(
        '',
        'db_iframe_proc',
        'func_protprocesso.php?pesquisa_chave='+document.form1.p58_codproc.value+'&funcao_js=parent.js_mostraproc',
        'Pesquisa',
        mostra
      );
    }
  }
}

function js_mostraproc(chave, obs, erro) {

  if(erro === true) {

    document.form1.p58_codproc.focus();

    document.form1.p58_codproc.value = '';
    document.form1.z01_numcgm.value  = '';
    document.form1.z01_nome.value    = '';
  } else {

     document.form1.z01_numcgm.value = arguments[3];
     document.form1.z01_nome.value   = arguments[1];
     document.form1.historico.value  = '';
  }
}

function js_mostraproc1(chave1,n,z,f){
  document.form1.z01_numcgm.value = n;
  document.form1.z01_nome.value = z;
  document.form1.p58_codproc.value = chave1;
  //document.form1.historico.value = f;
  db_iframe_proc.hide();
}

function js_mostranomes(mostra){
  if(mostra==true){
    js_OpenJanelaIframe("<?=$sIframeLocation?>",'db_iframe_nome','func_nome.php?testanome=true&funcao_js=parent.js_preenche|0|1','Pesquisa',true);
    /*
    func_iframe.jan.location.href = 'func_nome.php?testanome=true&funcao_js=parent.js_preenche|0|1';
    func_iframe.setLargura(770);
    func_iframe.setAltura(430);
    func_iframe.mostraMsg();
    func_iframe.show();
    func_iframe.focus();
    */
  }else{
    js_OpenJanelaIframe("<?=$sIframeLocation?>",'db_iframe_nome','func_nome.php?testanome=true&pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_preenche1','Pesquisa',false);
  }
}

function js_preenche1(chave,chave1){
  document.form1.q02_inscr.value = "";
  document.form1.j01_matric.value = "";
  document.form1.z01_nome.value = chave1;
  if(chave==true){
    document.form1.z01_numcgm.value = '';
    document.form1.z01_numcgm.focus();
  }
}

function js_preenche(chave,chave1){
  document.form1.q02_inscr.value = "";
  document.form1.j01_matric.value = "";
  document.form1.z01_numcgm.value = chave;
  document.form1.z01_nome.value = chave1;

  db_iframe_nome.hide();
}

/*
 alterada para js_Open..
*/
function js_pesquisatabrec(mostra) {

  if (mostra==true) {

    js_OpenJanelaIframe("<?=$sIframeLocation?>",
                        'db_iframe_tabrec',
                        'func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_drecei|recurso|arretipo|k00_descr',
                        'Pesquisa',true);
  } else {

    if (document.form1.k02_codigo.value != '') {

      js_OpenJanelaIframe("<?=$sIframeLocation?>",
                          'db_iframe_tabrec',
                          'func_tabrec.php?pesquisa_chave='+
                          document.form1.k02_codigo.value+'&funcao_js=parent.js_mostratabrec',
                          'Pesquisa',false);
    } else {

      document.form1.k02_drecei.value = '';
      js_desabilitaConCarPeculiar(false);
    }
  }
}

function js_mostratabrec(chave2,erro,chave3,chave4,chave5){

 // document.form1.k02_codigo.value = chave1;
  document.form1.k02_drecei.value  = chave2;
  document.form1.o15_codigo.value  = chave3;
  document.form1.arretipo.value    = chave4;
  document.form1.descrarretipo.value = chave5;
  document.form1.o15_codigo.onchange();

  if(erro==true){
     document.form1.k02_codigo.focus();
     document.form1.k02_codigo.value = '';
  } else {
    js_buscaConCarPeculiar();
    js_verificaReceita();
  }
}

function js_mostratabrec1(chave1,chave2,chave3,chave4,chave5) {
     document.form1.k02_codigo.value = chave1;
     document.form1.k02_drecei.value = chave2;
     document.form1.o15_codigo.value = chave3;
     document.form1.arretipo.value   = chave4;
     document.form1.descrarretipo.value = chave5;
     document.form1.o15_codigo.onchange();

     db_iframe_tabrec.hide();
     js_buscaConCarPeculiar();
     js_verificaReceita();
}

function js_mostramatriculas(mostra) {
  if(mostra==true){
     js_OpenJanelaIframe("<?=$sIframeLocation?>",'db_iframe_iptubase','func_iptubase.php?funcao_js=parent.js_preenchematriculas|j01_matric|z01_nome','Pesquisa',true);
  }else{
     js_OpenJanelaIframe("<?=$sIframeLocation?>",'db_iframe_iptubase','func_iptubase.php?pesquisa_chave='+document.form1.j01_matric.value+'&funcao_js=parent.js_preenchematriculas2','Pesquisa',false);
  }
}

function js_preenchematriculas(chave,chave1){
  document.form1.q02_inscr.value = "";
  document.form1.z01_numcgm.value = "";
  document.form1.j01_matric.value = chave;
  document.form1.z01_nome.value = chave1;
  db_iframe_iptubase.hide();
}

function js_preenchematriculas2(chave,chave1){
  document.form1.q02_inscr.value = "";
  document.form1.z01_numcgm.value = "";
  document.form1.z01_nome.value = chave;
  db_iframe_iptubase.hide();
}

function js_mostrainscricao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe("<?=$sIframeLocation?>",'db_iframe_issbase','func_issbase.php?funcao_js=parent.js_preencheinscricao|0|2','Pesquisa',true);
  }else{
    js_OpenJanelaIframe("<?=$sIframeLocation?>",'db_iframe_issbase','func_issbase.php?pesquisa_chave='+document.form1.q02_inscr.value+'&funcao_js=parent.js_preencheinscricao2','Pesquisa',false);
  }
}

function js_preencheinscricao(chave,chave1){
  document.form1.j01_matric.value = "";
  document.form1.z01_numcgm.value = "";
  document.form1.z01_nome.value = chave1;
  document.form1.q02_inscr.value = chave;
  db_iframe_issbase.hide();
}

function js_preencheinscricao2(chave,chave1){
  document.form1.j01_matric.value = "";
  document.form1.z01_numcgm.value = "";
  document.form1.z01_nome.value = chave;
  db_iframe_issbase.hide();
}

function js_vertaxas(){
    js_OpenJanelaIframe("<?=$sIframeLocation?>",'func_iframe_taxas','cai4_recibo005.php?receita='+document.form1.k02_codigo.value+'&funcao_js=parent.js_preenchereceita|k02_codigo|k02_drecei','Pesquisa',true);
}

function js_gravarec(cod,descr,valor) {

  document.form1.k02_codigo.value=cod;
  document.form1.k02_drecei.value=descr;
  document.form1.valor.value=valor;
  js_buscaConCarPeculiar();
  document.form1.gravar.click();
}

function js_iframeok(){
  //FUNCAO PARA PARAR A EXECUÇÃO DO PROGRAMA ATEH Q SEJA CRIADO O IFRAME
   while (!carregado){
    /*
     FICA DENTRO DO WHILE ATEH O INPUT HIDDEN CARREGADO seja CRIADO
     QUE ESTA NO FINAL DO IFRAME
    */
   }
}

function js_pesquisak130_concarpeculiar(mostra) {

 if (mostra == true) {
    js_OpenJanelaIframe('','db_iframe_concarpeculiar','func_concarpeculiar.php?funcao_js=parent.js_mostraconcarpeculiar1|c58_sequencial|c58_descr','Pesquisa',true);
  } else {

    if ($('k130_concarpeculiar').value != '') {
      js_OpenJanelaIframe('','db_iframe_concarpeculiar','func_concarpeculiar.php?pesquisa_chave='+$('k130_concarpeculiar').value+'&funcao_js=parent.js_mostraconcarpeculiar','Pesquisa',false);
    } else {
      $('c58_descr').value = '';
    }
  }
}

function js_mostraconcarpeculiar(chave1, erro) {

  if (erro == true) {

    $('k130_concarpeculiar').value = '';
    $('c58_descr').value           = chave1;
    $('k130_concarpeculiar').focus;
  } else {
    $('c58_descr').value           = chave1;
  }
}

function js_mostraconcarpeculiar1(chave1, chave2) {

  $('k130_concarpeculiar').value = chave1;
  $('c58_descr').value           = chave2;
  db_iframe_concarpeculiar.hide();
}

/**
 * Busca C.Peculiar/C.Aplicação existente.
 **/
function js_buscaConCarPeculiar() {

  if ($('k02_codigo').value != "") {

    js_divCarregando('Aguarde pesquisando...', 'msgBox');

    var oParam            = new Object();
    oParam.exec           = 'getConCarPeculiar';
    oParam.iCodigoReceita = $('k02_codigo').value;
    var oAjax             = new Ajax.Request(sUrlRpc,
                                               { method:'post',
      																					 asynchronous: false,
                                                 parameters:'json='+Object.toJSON(oParam),
                                                 onComplete: js_retornoConCarPeculiar
                                               }
                                             );
  }
}

function js_retornoConCarPeculiar(oAjax) {

  js_removeObj("msgBox");

  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 2) {

    alert(oRetorno.message.urlDecode());
    js_desabilitaConCarPeculiar(false);
    return false;
  } else {

    $('k130_concarpeculiar').value = oRetorno.o70_concarpeculiar.replace(/\./g,"");
    js_pesquisak130_concarpeculiar(false);

    if ($('k130_concarpeculiar').value != 0) {
      js_desabilitaConCarPeculiar(true);
    } else {
      js_desabilitaConCarPeculiar(false);
    }
    return true;
  }
}

function js_desabilitaConCarPeculiar(lMudar) {

  if (lMudar) {

    $('k130_concarpeculiar').style.backgroundColor = "#DEB887";
    $('k130_concarpeculiar').setAttribute("readonly", "readonly");

    var sContent = "  <strong>C.Peculiar/C.Aplicação:</strong>";
  } else {

    $('k130_concarpeculiar').style.backgroundColor = "#FFFFFF";
    $('k130_concarpeculiar').removeAttribute("readonly");

    var sContent  = "<a href='#' class='dbancora' style='text-decoration:underline;'";
        sContent += "   onclick='js_pesquisak130_concarpeculiar(true);'>";
        sContent += "  <strong>C.Peculiar/C.Aplicação:</strong>";
        sContent += "</a>";
  }

  $('lnkConcarpeculiar').innerHTML = sContent;
}
</script>
<?
if ((isset($codtipo)&&$codtipo!="")&&(isset($incproc)&&$incproc==true)) {

  $result_procrec=$clprocrec->sql_record($clprocrec->sql_query($codtipo));
  for ($w=0;$w<$clprocrec->numrows;$w++) {

    db_fieldsmemory($result_procrec,$w);
    echo "<script>js_gravarec($p52_codrec,'$k02_drecei',$p52_valor);</script>";
    flush();
  }
}

if (isset($mostramenu)&&$mostramenu=='t') {

  sleep(2);
  echo "<script>js_iframeok();</script>";
  echo "<script>document.form1.mostramenu.value = 't';</script>";
  echo "<script>js_mostranomes(false);</script>";

  $rsResult = $clnumpref->sql_record($clnumpref->sql_query( db_getsession('DB_anousu'), db_getsession('DB_instit'),"*",null,""));
  $intNumrows = $clnumpref->numrows;
  if($intNumrows > 0){
    db_fieldsmemory($rsResult,0);
  }else{
      db_redireciona('db_erros.php?fechar=true&db_erro=Configure o grupo de taxas para as certidões.');
  }

  $sTaxaGrupoReq  = $cltaxagruporeg->sql_query(null,"*","k02_codigo","k08_taxagrupo = $k03_taxagrupo");
  $rsTaxagruporeg = $cltaxagruporeg->sql_record($sTaxaGrupoReq);
  $numrows = $cltaxagruporeg->numrows;
  for ($w=0;$w<$numrows;$w++) {

    db_fieldsmemory($rsTaxagruporeg,$w);
    echo "<script>js_gravarec($k02_codigo,'$k02_drecei',$k07_valorf);</script>";
  }
}
?>