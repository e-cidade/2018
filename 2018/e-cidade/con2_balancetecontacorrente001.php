<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("libs/db_conecta.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <meta http-equiv="Expires" CONTENT="0"/>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css" />
  </head>
  <body bgcolor="#CCCCCC" style="margin-top: 30px;">
    <center>
      <fieldset style="width: 500px;">
        <legend>
          <strong>Balancete de Conta Corrente</strong>
        </legend>

        <table>
          <tr>
            <td><strong>Período:</strong></td>
            <td colspan="2">
              <?php
                db_inputdata("dtInicial", "", "", "", true, "text", 1, "");
                echo " a ";
                db_inputdata("dtFinal", "", "", "", true, "text", 1, "");
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <?php
                db_ancora("<b>Conta Corrente:<b>", "js_pesquisaContaCorrente(true);", 1);
              ?>
            </td>
            <td>
              <?php
                $funcaoJsInscricaoPassivo = "onchange = 'js_pesquisaContaCorrente(false);'";
                db_input("iContaCorrente", 5, 1, true, "text", 2, $funcaoJsInscricaoPassivo);
              ?>
            </td>
            <td>
              <?php
                db_input("sContaCorrente", 40, "", true, 'text', 3, "");
              ?>
          </td>
          </tr>
        </table>
      </fieldset>
      <input name="lProcessar" id="lProcessar" onclick="js_emiteRelatorio();" type="button" style="margin-top: 10px;" value="Emitir" />
    </center>
  </body>
</html>

<script type="text/javascript">


  function js_emiteRelatorio() {

    if (js_validaFiltros()) {

      var dtInicial      = $("dtInicial").value;
      var dtFinal        = $("dtFinal").value;
      var iContaCorrente = $("iContaCorrente").value;
      var sUrl           = "con2_balancetecontecorrente002.php";

      oJanela = window.open(sUrl+'?dtInicial='+dtInicial+'&dtFinal='+dtFinal+'&aCC='+iContaCorrente, '', 'width='+(screen.availWidth-5)+', height='+(screen.availHeight-40)+', scrollbars=1, location=0 ');
      oJanela.moveTo(0,0);
    }
  }

  function js_validaFiltros() {

    var dtInicial = $("dtInicial").value;
    var dtFinal   = $("dtFinal").value;

    if (dtInicial == "") {

      alert("Preencha a data inicial");
      return false;
    }

    if (dtFinal == "") {

      alert("Preencha a data final");
      return false;
    }

    var aDataInicial = dtInicial.split("/");
    var aDataFinal   = dtFinal.split("/");

    var iAnoInicial  = parseInt(aDataInicial[2]);
    var iAnoFinal    = parseInt(aDataFinal[2]);

    if (iAnoInicial <= iAnoFinal) {

      if (iAnoInicial == iAnoFinal) {

        var iMesInicial  = parseInt(aDataInicial[1]);
        var iMesFinal    = parseInt(aDataFinal[1]);

        if (iMesInicial > iMesFinal) {

          alert("A data final tem que ser maior que a data inicial.");
          return false;
        } else {

          var iDiaInicial = aDataInicial[0];
          var iDiaFinal   = aDataFinal[0];

          if (iDiaInicial > iDiaFinal) {

            alert("A data final tem que ser maior que a data inicial.");
            return false;
          }
        }
      }
    } else {

      alert("A data final tem que ser maior que a data inicial.");
      return false;
    }

    //Chamar RPC para emitir o relatório
    return true;
  }

  function js_pesquisaContaCorrente(lMostra) {

    if ($F('iContaCorrente') == "") {
      $('sContaCorrente').value = '';
    }

    var sFunction = lMostra ? "js_preencheContaCorrente" : "js_preencheContaCorrenteDescricao";
    if(lMostra) {

      var sUrlLookUp = "func_contacorrente.php?funcao_js=parent." + sFunction + "|c17_sequencial|c17_contacorrente|c17_descricao";
      js_OpenJanelaIframe("", "db_iframe_contacorrente", sUrlLookUp, "Pesquisa Conta Corrente", true);
    } else {

      var sUrlLookUp = "func_contacorrente.php?pesquisa_chave=" + $F("iContaCorrente") + "&funcao_js=parent." + sFunction;
      js_OpenJanelaIframe("", "db_iframe_contacorrente", sUrlLookUp, "Pesquisa", false);
    }
  }

  function js_preencheContaCorrente(iContaCorrente, sContaCorrente, sDescricaoConta) {

    $("sContaCorrente").value = sContaCorrente + " - " + sDescricaoConta;
    $("iContaCorrente").value = iContaCorrente;
    db_iframe_contacorrente.hide();
  }

  function js_preencheContaCorrenteDescricao(sContaCorrente, lErro) {

    $("sContaCorrente").value = sContaCorrente;
    if(lErro) {

      $("iContaCorrente").focus();
      $("iContaCorrente").value = "";
    }
  }

</script>
<?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>