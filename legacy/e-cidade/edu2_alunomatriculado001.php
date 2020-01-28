<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");

db_postmemory($HTTP_POST_VARS);

$oDaoMatricula  = db_utils::getdao("matricula");
$oDaoCalendario = db_utils::getdao("calendario");
$db_opcao       = 1;
$db_botao       = true;
$sNomeEscola    = db_getsession("DB_nomedepto");
$iEscola        = db_getsession("DB_coddepto");
$iModulo        = db_getsession("DB_modulo");
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
 <body class="body-default">
  <div class="container">
  <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
  <a name="topo"></a>
  <form name="form1" method="post" action="">

      <fieldset>
        <legend><strong>Relatório de Alunos Matriculados</strong></legend>
       <table border="0">
        <tr>
         <?
           if ($iModulo == 7159) {

              echo '<td align="left">';
              echo ' <strong>Selecione a escola:</strong>';
                     $oDaoEscola     = db_utils::getdao('escola');
                     $sSqlEscola     = $oDaoEscola->sql_query_file("", "ed18_i_codigo, ed18_c_nome", "", "");
                     $rsResultEscola = $oDaoEscola->sql_record($sSqlEscola);
                     $iLinhas        = $oDaoEscola->numrows;
                     echo '<select name="escola" id="escola" onChange="js_escola(this.value);" style="height:18px;font-size:10px;">';
                     echo ' <option value="">Selecione a Escola</option>';

                            for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

                              $oDadosEscola = db_utils::fieldsmemory($rsResultEscola, $iCont);
                              echo " <option value='$oDadosEscola->ed18_i_codigo'>$oDadosEscola->ed18_c_nome</option>";

                            }

                     echo ' </select>';
                     echo '</td>';

          } else {

            $iEscola = db_getsession("DB_coddepto");
            echo "<input type= 'hidden' id ='escola' value = '$iEscola' >";

          }
         ?>
         <td>
          <strong>Selecione o Calendário:</strong>
          <select name="calendario" id="select_calendario" onchange="js_etapa(this.value)"
                  style="width:150px;height:18px;font-size:10px;;" >
          </select>
         </td>
         <td>
          <strong>Selecione a Etapa:</strong>
          <select name="etapa" id="etapa" onchange="js_liberaBotao(this.value)"
                  style="width:150px;height:18px;font-size:10px;;" >
          </select>
         </td>
         <td>
          <strong>Filtro:</strong>
          <select name="filtro" style="font-size:9px;width:180px;height:18px;">
           <option value="2" <?=@$filtro==2?"selected":""?>>TURMAS</option>
           <option value="1" <?=@$filtro==1?"selected":""?>>TURMAS E PERCENTUAIS</option>
          </select>
         </td>
         <td valign='bottom'>
           <input type="button" name="procurar" id="procurar" value="Processar"
                  onclick="js_procurar(document.form1.calendario.value,document.form1.etapa.value)" disabled>
         </td>
        </tr>
       </table>
      </fieldset>
  </form>
  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  </div>
 </body>
<script type="text/javascript">
js_init();

function js_init() {

}

function js_procurar(calendario, etapa) {

  jan = window.open('edu2_alunomatriculado002.php?x&iCalendario='+calendario+'&iSerieEscolhida='+etapa+
    	              '&iFiltro='+document.form1.filtro.value+'&iEscola='+$('escola').value,'',
    	              'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
    	             );
  jan.moveTo(0,0);


}

function js_botao(valor) {

  if (valor != "") {
    document.form1.procurar.disabled = false;
  } else {
    document.form1.procurar.disabled = true;
  }

}


function js_escola(escola) {

  var oParam    = new Object();

  oParam.exec   = "PesquisaCalendario";
  oParam.escola =  escola;

  var url       = 'edu4_escola.RPC.php';

  js_webajax(oParam, 'js_retornoPesquisaCalendario', url);

}

function js_retornoPesquisaCalendario(oRetorno) {


  var oRetorno = eval("("+oRetorno.responseText+")");
  sHtml        = '';

  if (oRetorno.iStatus  != 1) {

    alert(oRetorno.sMessage.urlDecode());
    return false;

  } else {

    sHtml += '<option value="">Selecione o Calendário</option>';

    for (var i = 0;i < oRetorno.aResult.length; i++) {

      with (oRetorno.aResult[i]) {

        sHtml += '<option value="'+oRetorno.aResult[i].ed52_i_codigo+'">';
        sHtml += oRetorno.aResult[i].ed52_c_descr.urlDecode()+'</option>';

      }

    }

    $('select_calendario').innerHTML             = sHtml;
    document.form1.select_calendario[0].selected = true;


  }

  $('select_calendario').disabled  = false;

}

function js_etapa(calendario) {

  $('etapa').innerHTML = "";
  $('etapa').disabled  = true;
  var oParam           = new Object();

  oParam.exec          = "PesquisaEtapa";

  <? if ($iModulo != 7159) { ?>

    oParam.escola = <?=$iEscola?>

  <? } else { ?>

    oParam.escola  =  $('escola').value;

  <? } ?>

  oParam.calendario = calendario;

  var url           = 'edu4_escola.RPC.php';

  js_webajax(oParam, 'js_retornoPesquisaEtapa', url);

}

function js_retornoPesquisaEtapa(oRetorno) {


  var oRetorno = eval("("+oRetorno.responseText+")");
  sHtml        = '';

  if (oRetorno.iStatus  != 1) {

    alert(oRetorno.sMessage.urlDecode());
    return false;

  } else {

    if (oRetorno.aResult1.length == 0) {
      sHtml += '<option value="">Não há Etapa</option>';
    } else {

      sHtml += '<option value="">Selecione a Etapa</option>';
      sHtml += '<option value="0">Todas</option>';

      for (var i = 0;i < oRetorno.aResult1.length; i++) {

        sHtml += '<option value="'+oRetorno.aResult1[i].ed11_i_codigo+'">'+
                 oRetorno.aResult1[i].ed11_c_descr.urlDecode()+'</option>';

      }

    }

  $('etapa').innerHTML   = sHtml;
  $('etapa').disabled    = false;

  }

}

function js_liberaBotao(valor){

  if (valor.trim() != "") {
    $('procurar').disabled = false;
  } else {
    $('procurar').disabled = true;
  }

}

</script>

<?

  if ($iModulo != 7159) {

?>

<script>
  js_escola(<?=$iEscola?>);
</script>

<?

  }

?>
</html>
