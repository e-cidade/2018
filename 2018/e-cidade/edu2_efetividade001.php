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
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));
db_postmemory($_POST);
$db_opcao    = 1;
$db_botao    = true;
$sNomeEscola = db_getsession("DB_nomedepto");
$iModulo     = db_getsession("DB_modulo");
$clrotulo    = new rotulocampo;
$clrotulo->label("ed98_i_mes");
$clrotulo->label("ed98_i_ano");
$clrotulo->label("ed98_c_tipo");
$ed98_i_ano = date("Y");
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
<body  >
  <div class="container">
    <?php MsgAviso(db_getsession("DB_coddepto"), "escola"); ?>

    <form name="form1" method="post" action="">
      <fieldset ><legend><b>Relatório de Efetividade RH</b></legend>
        <table class="form-container">
          <?php
            if ($iModulo == 7159) {

              echo '<tr>';
              echo '  <td>';
              echo '    <b>Selecione a escola:</b>';
              echo '  </td >';
              echo '  <td >';
                     $oDaoEscola     = new cl_escola();
                     $sSqlEscola     = $oDaoEscola->sql_query_file("", "ed18_i_codigo,ed18_c_nome", "", "");
                     $rsResultEscola = $oDaoEscola->sql_record($sSqlEscola);
                     $iLinhas        = $oDaoEscola->numrows;
                     echo '<select name="escola" id="escola"  onchange="js_ano()">';
                     echo ' <option value="">Selecione a Escola</option>';
                            for ($iCont = 0; $iCont < $iLinhas; $iCont++) {
                              $oDadosEscola = db_utils::fieldsmemory($rsResultEscola, $iCont);
                              echo " <option value='$oDadosEscola->ed18_i_codigo'>$oDadosEscola->ed18_c_nome</option>";
                            }
                     echo ' </select>';
              echo '  </td>';
              echo '</tr>';
            } else {

              $iEscola = db_getsession("DB_coddepto");
              echo "<tr> <td colspan='2'>";
              echo "  <input type= 'hidden' id ='escola' value = '$iEscola' >";
              echo "</td></tr> ";
            }
            ?>

          <tr >
            <td nowrap title="<?= $Ted98_i_ano?>" style='width: 200px;' >
              <?=$Led98_i_ano ?>
            </td>
            <td>
              <?php
                $aAnos[""] = "";
                for ($iCont = (date("Y")+1); $iCont > (date("Y")-30); $iCont--) {
                  $aAnos[$iCont] = $iCont;
                }
                $iPos = $aAnos;
                db_select('ed98_i_ano', $iPos, true, 1, "onchange='js_ano(this.value)'");
              ?>
            </td>
          </tr>
          <tr>
             <td nowrap colspan ="2" >
                <div id="registros"></div>
             </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" id='btnProcessar' name="processar" value="Processar" onclick="js_processar();" disabled='disabled' />
    </form>
  </div>
   <?php
     db_menu();
   ?>
 </body>
</html>
<script>

function js_ano(valor) {

  $('btnProcessar').setAttribute('disabled', 'disabled');
  if (valor == "") {
    $('registros').innerHTML = "";
  } else {

    js_divCarregando("Aguarde, buscando registros","msgBox");
    var sAction = 'Relatorio';
    var url     = 'edu1_efetividaderhRPC.php';
    parametros  = 'ano='+$('ed98_i_ano').value+'&iEscola='+$('escola').value;
    var oAjax   = new Ajax.Request(url,{method    : 'post',
                                        parameters: parametros+'&sAction='+sAction,
                                        onComplete: js_retornoRelatorio
                                       }
                                  );
  }

}

function js_retornoRelatorio(oAjax) {

  js_removeObj("msgBox");
  mes_ext      = new Array("JANEIRO","FEVEREIRO","MARÇO","ABRIL","MAIO","JUNHO","JULHO","AGOSTO",
		                   "SETEMBRO","OUTUBRO","NOVEMBRO","DEZEMBRO"
		                  );
  var oRetorno = eval("("+oAjax.responseText+")");
  sHtml        = '<b>Registros:<br></b>';
  sHtml       += '<select id = "ed98_i_codigo" name="ed98_i_codigo" size="20" multiple style="width:700px">';

  if (oRetorno.length == 0) {
    sHtml += '<option value="">Nenhum registro para o ano selecionado.</option>';
  } else {

    for (var i = 0;i < oRetorno.length; i++) {

      with (oRetorno[i]) {

        tipo_efetividade = ed98_c_tipo.urlDecode() == "P" ? "PROFESSORES" : "FUNCIONÁRIOS";
        tipo_competencia = ed98_c_tipocomp.urlDecode() == "M" ? "MENSAL" : "PERIÓDICA";

        if (ed98_c_tipocomp.urlDecode() == "M") {

          mes_indice  = parseInt(ed98_i_mes.urlDecode())-1;
          competencia = "Mês/Ano: "+mes_ext[mes_indice]+" / "+ed98_i_ano.urlDecode();

        } else {

          data_inicial = ed98_d_dataini.urlDecode().substr(8,2)+"/"+ed98_d_dataini.urlDecode().substr(5,2)+
                         "/"+ed98_d_dataini.urlDecode().substr(0,4);
          data_final   = ed98_d_datafim.urlDecode().substr(8,2)+"/"+ed98_d_datafim.urlDecode().substr(5,2)+
                         "/"+ed98_d_datafim.urlDecode().substr(0,4);
          competencia  = data_inicial+" à "+data_final;

        }

        qtdeinf = "Qtde Pessoal informado: "+qtde.urlDecode();
        desab   = qtde.urlDecode()=="0"?"disabled":""
        sHtml   += '  <option value="'+ed98_i_codigo.urlDecode()+'" '+desab+'>'+tipo_efetividade+
                   ' - '+tipo_competencia+' - '+competencia+' - '+qtdeinf+'</option>';

      }

    }

  }

  sHtml += '</select>';

  if (oRetorno.length > 0) {
    $('btnProcessar').removeAttribute('disabled');
  }

  $('registros').innerHTML = sHtml;

}

function js_processar() {

  registros = "";
  sep       = "";
  for (var i = 0; i < $('ed98_i_codigo').length; i++) {

    if ($('ed98_i_codigo').options[i].selected == true) {

      registros += sep+$('ed98_i_codigo').options[i].value;
      sep        = ",";

    }

  }

  if (registros == "") {

    alert("Selecione alguma competência!");
    return false;
  }

  /*ATENÇÃO... O PLUGIN: RelatorioEfetividadeCarazinho realiza alteração na linha abaixo*/
  jan = window.open('edu2_efetividade002.php?sRegistros='+registros+'&iEscola='+$('escola').value,'',
		                'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
		           );
  jan.moveTo(0,0);

}
js_ano($('ed98_i_ano').value);

</script>