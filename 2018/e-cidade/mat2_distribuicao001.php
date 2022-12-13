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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
?>

<html>
  <head>

    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" content="0">

    <link rel="stylesheet" type="text/css" href="estilos.css">
    <link rel="stylesheet" type="text/css" href="estilos/grid.style.css">

    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
    <script type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBTreeView.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
    <script type="text/javascript" src="scripts/classes/material/FiltroGrupoSubgrupo.js"></script>

  </head>
  <body bgcolor="#cccccc" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">

    <div class="container">

      <form id="frmDistribuicaoMateriais" name="frmDistribuicaoMateriais" method="post" action="">
        <table>
          <tr>
            <td align="center" colspan="3">
              <fieldset>

                <legend>Distribui��o Mensal de Materiais</legend>

                <!-- Filtros de Pesquisa -->
                <fieldset class="separator">

                  <legend>Filtros</legend>

                  <table style="width: 100%;">

                    <tr>
                      <td style="width: 180px;">
                        <label class="bold" id="lbl_competencia_inicial" for="competencia_inicial_mes">Compet�ncia Inicial:</label>
                      </td>
                      <td>
                          <?php
                          $Scompetencia_inicial_mes = 'Compet�ncia Inicial';
                          $Scompetencia_inicial_ano = 'Compet�ncia Final';
                          db_input('competencia_inicial_mes', '', 1, false, '', '', 'style="width: 8%;" placeholder="m�s"', '', '', '', 2);
                          ?> /
                          <?php
                          db_input('competencia_inicial_ano', '', 1, false, '', '', 'style="width: 16%;" placeholder="ano"', '', '', '', 4)
                          ?>
                      </td>
                    </tr>

                    <tr>
                      <td>
                        <label class="bold" id="lbl_competencia_final" for="competencia_final_mes">Compet�ncia Final:</label>
                      </td>
                      <td>
                          <?php
                          $Scompetencia_final_mes = 'Compet�ncia Final';
                          $Scompetencia_final_ano = 'Compet�ncia Final';
                          db_input('competencia_final_mes', '', 1, false, '', '', 'style="width: 8%;" placeholder="m�s"', '', '', '', 2);
                          ?> /
                          <?php db_input('competencia_final_ano', '', 1, false, '', 1, 'style="width: 16%;" placeholder="ano"', '', '', '', 4) ?>
                      </td>
                    </tr>

                    <tr>
                      <td>
                        <label class="bold" id="lbl_distribuicao_zerada" for="distribuicao_zerada">Exibe Distribui��o Zerada:</label>
                      </td>
                      <td>
                          <?php
                          $aOpcoesDistribuicao = array(
                            '0' => 'N�o',
                            '1' => 'Sim',
                          );
                          db_select('distribuicao_zerada', $aOpcoesDistribuicao, true, 1, 'style="width: 50%;"');
                          ?>
                      </td>
                    </tr>

                    <tr>
                      <td>
                        <label class="bold" id="lbl_grupo_subgrupo" for="btn_grupo_subgrupo">Grupo/Subgrupo:</label>
                      </td>
                      <td>
                          <input type="button" value="Escolher" id="btn_grupo_subgrupo">
                      </td>
                    </tr>

                    <tr>
                      <td colspan="2">
                        <div id="lancador-departamentos" style="margin-top: 5px;">&nbsp;</div>
                      </td>
                    </tr>

                  </table>
                </fieldset>

                <!-- Op��es de visualiza��o -->
                <fieldset class="separator">

                  <legend>Op��es de Visualiza��o</legend>

                  <table style="width: 100%;">

                    <tr>
                      <td style="width: 180px;">
                        <label class="bold" id="lbl_quebra_pagina" for="quebra_pagina">Quebra de P�gina:</label>
                      </td>
                      <td>
                          <?php
                          $aOpcoesQuebraPagina = array(
                            '0' => 'Nenhuma',
                            '1' => 'Por Departamento',
                          );
                          db_select('quebra_pagina', $aOpcoesQuebraPagina, true, 1, 'style="width: 50%;"');
                          ?>
                      </td>
                    </tr>

                    <tr>
                      <td>
                        <label class="bold" id="lbl_agrupar" for="agrupar">Agrupar por Grupo/Subgrupo:</label>
                      </td>
                      <td>
                          <?php
                          $aOpcoesAgrupar = array(
                            '1' => 'Sim',
                            '0' => 'N�o',
                          );
                          db_select('agrupar', $aOpcoesAgrupar, true, 1, 'style="width: 50%;"');
                          ?>
                      </td>
                    </tr>

                  </table>
                </fieldset>
              </fieldset>

              <input style="margin-top: 10px;" name="emite" id="emite" type="button" value="Gerar" onclick="js_emite();">

            </td>
          </tr>

        </table>
      </form>
    </div>

    <script type="text/javascript">
      var oLancador;
      var oFiltroGrupoSubgrupo;
      var URL_RELATORIO = 'mat2_distribuicao002.php';

      document.observe('dom:loaded', function () {

        oFiltroGrupoSubgrupo = new FiltroGrupoSubgrupo('filtroGrupoSubgrupo', true);
        $('btn_grupo_subgrupo').observe('click', function(){
          oFiltroGrupoSubgrupo.show();
        });

        $('competencia_inicial_ano').observe('change', function(){
          $('competencia_final_ano').value = $('competencia_inicial_ano').value;
        });

        oLancador = new DBLancador('Lancador');
        oLancador.setNomeInstancia('oLancador');
        oLancador.setLabelAncora('Departamento:');
        oLancador.setParametrosPesquisa('func_db_almoxdepto.php', ['coddepto','descrdepto']);
        oLancador.setTextoFieldset('Departamentos');
        oLancador.setGridHeight(100);
        oLancador.show($('lancador-departamentos'));

        $('agrupar').value = '1';
      });

      function js_emite() {

        var oForm               = $('frmDistribuicaoMateriais');
        var iAnoInicial         = oForm.competencia_inicial_ano.value;
        var iMesInicial         = oForm.competencia_inicial_mes.value;
        var iAnoFinal           = oForm.competencia_final_ano.value;
        var iMesFinal           = oForm.competencia_final_mes.value;
        var iDistribuicaoZerada = oForm.distribuicao_zerada.value;
        var iQuebraPagina       = oForm.quebra_pagina.value;
        var iAgrupar            = oForm.agrupar.value;
        var aGrupoSubgrupo      = oFiltroGrupoSubgrupo.getSelecionados();
        var aDepartamentos      = [];

        $(oLancador.getRegistros()).each(function(oRegistro){
          aDepartamentos.push(oRegistro.sCodigo);
        });

        /**
         * Valida campos vazios
         */
        if (empty(iMesInicial)) {

          alert('O campo M�s da Compet�ncia Inicial � de preenchimento obrigat�rio.');
          return;
        }
        if (parseInt(iMesInicial) > 12 || parseInt(iMesInicial) < 1) {

          alert('O M�s da Compet�ncia Inicial � inv�lido. Informe um m�s entre 01 e 12.');
          return;
        }
        if (empty(iAnoInicial)) {

          alert('O campo Ano da Compet�ncia Inicial � de preenchimento obrigat�rio.');
          return;
        }
        if (iAnoInicial.length !== 4) {

          alert('O Ano da Compet�ncia Inicial � inv�lido. Informe um ano com 4 d�gitos.');
          return;
        }
        if (empty(iMesFinal)) {

          alert('O campo M�s da Compet�ncia Final � de preenchimento obrigat�rio.');
          return;
        }
        if (parseInt(iMesFinal) > 12 || parseInt(iMesFinal) < 1) {

          alert('O M�s da Compet�ncia Final � inv�lido. Informe um m�s entre 01 e 12.');
          return;
        }
        if (empty(iAnoFinal)) {

          alert('O campo Ano da Compet�ncia Final � de preenchimento obrigat�rio.');
          return;
        }
        if (iAnoFinal.length !== 4) {

          alert('O Ano da Compet�ncia Final � inv�lido. Informe um ano com 4 d�gitos.');
          return;
        }
        if (parseInt(iAnoInicial+iMesInicial) > parseInt(iAnoFinal+iMesFinal)) {

          alert('A Compet�ncia Inicial n�o pode ser maior que a Compet�ncia Final.');
          return;
        }

        var aMeses = [];
        for (var iAno = iAnoInicial; iAno <= iAnoFinal; iAno ++) {

          var iMesFimLaco  = iMesFinal;
          if (iAno < iAnoFinal) {
            iMesFimLaco = 12;
          }
          for (iMesCorrente = iMesInicial; iMesCorrente <= iMesFimLaco; iMesCorrente++) {
            aMeses.push(iMesCorrente+"/"+iAno);
          }
        }
        if (aMeses.length > 12) {
          
          alert("O intervalo de emiss�o do relat�rio n�o pode ser maior que 12 (doze) meses.");
          return false;

        }
        /**
         * Valida sele��o de ao menos um Grupo/Subgrupo
         */
        if (aGrupoSubgrupo.length == 0) {

          alert('Escolha ao menos um Grupo/Subgrupo.');
          return;
        }

        var sQuery  = '?';
            sQuery += 'mes_inicial='             + iMesInicial;
            sQuery += '&ano_inicial='            + iAnoInicial;
            sQuery += '&mes_final='              + iMesFinal;
            sQuery += '&ano_final='              + iAnoFinal;
            sQuery += '&distribuicao_zerada='    + iDistribuicaoZerada;
            sQuery += '&grupo_subgrupo='         + aGrupoSubgrupo.join(',');
            sQuery += '&departamentos='          + aDepartamentos.join(',');
            sQuery += '&quebra_pagina='          + iQuebraPagina;
            sQuery += '&agrupar_grupo_subgrupo=' + iAgrupar;

        var iHeight = (screen.availHeight - 40);
        var iWidth  = (screen.availWidth  - 5);
        var sOpcoes = 'width=' + iWidth + ',height=' + iHeight + ',scrollbars=1,location=0';
        var oJanela = window.open(URL_RELATORIO + sQuery, '', sOpcoes);

        oJanela.moveTo(0, 0);
      }
    </script>

    <?php db_menu() ?>
  </body>
</html>
