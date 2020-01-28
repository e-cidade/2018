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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$oDaoPlacaixa = new cl_placaixa();
$oDaoPlacaixa->rotulo->label("k80_codpla");

db_postmemory($_GET);

?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  </head>
  <body class="body-default">

    <div class="container">

      <form name="frmRelatorio" method="post" action="" >
        <fieldset>
          <legend>Opções</legend>
          <table>
            <tr>
              <td  nowrap title="<?=$Tk80_codpla?>">
              <?=$Lk80_codpla?>
              </td>
              <td align="left" nowrap>
               <?
               db_input("k80_codpla", 10,$Ik80_codpla,true,"text",1);
               ?>
               </td>
            </tr>

            <tr>
              <td  nowrap title="Processo administrativo">
                <strong>Processo Administrativo:</strong>
              </td>
              <td align="left" nowrap>
               <?
               db_input("k144_numeroprocesso", 10,null,true,"text",1, null,null,null,null,15);
               ?>
               </td>
            </tr>

            <tr>
              <td>
                <b>Data Inicial:</b>
              </td>
              <td>
                 <?
                  db_inputdata("dataini",null,null,null,true,'text',1);
                 ?>
              </td>
              <td>
                <b>Data Final</b>
              </td>
              <td>
                 <?
                  db_inputdata("datafim",null,null,null,true,'text',1);
                 ?>
              </td>
            </tr>

            <tr>
              <td colspan='1'>
                <b>Filtrar Por:</b>
              </td>
              <td colspan='3'>
                <?
                   $aFiltro = array(
                                    "k80_data"  => "Data de Lancamento",
                                    "k80_dtaut" => "Data de Autenticação"
                                   );
                  db_select("sFiltro", $aFiltro,true,1);
                 ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input name="pesquisar" type="button" id="pesquisar2" value="Pesquisar" onclick="js_showReport()">
        <input name="limpar" type="reset" id="limpar" value="Limpar" >
      </form>
    </div>
    <?php db_menu(); ?>
    <script type="text/javascript">
      function js_showReport() {

        var iPlanilha = $F('k80_codpla');
        var dtDataIni = $F('dataini');
        var dtDataFim = $F('datafim');
        var sFiltro   = $F("sFiltro");
        var sProcesso = $F("k144_numeroprocesso");

        if (dtDataIni != '' && dtDataFim != '') {

          var oDataInicial = new Date( document.getElementById("dataini_ano").value,
                                       document.getElementById("dataini_mes").value,
                                       document.getElementById("dataini_dia").value ),
              oDataFinal   = new Date( document.getElementById("datafim_ano").value,
                                       document.getElementById("datafim_mes").value,
                                       document.getElementById("datafim_dia").value );

          if (oDataInicial.getTime() > oDataFinal.getTime()) {
            return alert("A data inicial deve ser menor ou igual a data final.");
          }
        }

        if (iPlanilha != '' || sProcesso != '') {
          jan = window.open('cai2_emiteplanilha002.php?k144_numeroprocesso='+sProcesso+'&codpla='+iPlanilha,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
        } else {

      	  sFuncaoPesquisa = 'func_placaixairelatorio.php?dataini='+dtDataIni+'&datafim='+dtDataFim+'&sFiltro='+sFiltro ;
      	  <?php
      		  if (isset($Modulo)) { ?>
      				sFuncaoPesquisa += '&Modulo=Pessoal';
      				<?php
      		  }
      		?>
          js_OpenJanelaIframe('top.corpo','db_iframe_plan',sFuncaoPesquisa,'Planilhas',true);
        }
      }
    </script>
  </body>
</html>