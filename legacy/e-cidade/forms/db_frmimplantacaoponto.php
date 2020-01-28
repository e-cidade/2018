<?php 

/**
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

include_once("dbforms/db_classesgenericas.php");
$oDaoHistoricoCalculo   = new cl_rhhistoricocalculo();
$oIframe                = new cl_iframe_alterar_excluir();
?>
<html>
  <head>
    <title>DBSeller Informática Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
      #rh143_tipoevento,
      #rh143_tipoevento > *  {
        height: 19px;
        padding: 0px;
        margin: 0;
        font-size: 12px;
      }
    </style>
  </head>
  <body>
    <div class="container">
     <!-- <form id="cadastro" name="cadastro" method='post'> -->
     <form id="form1" name="form1" method="post" action="pes1_gerffx001.php?gerf=<?php echo $gerf; ?>">
        <fieldset>
          <legend>Implantação do Ponto</legend>
          <table class="form-container">
    
            <tr>
              <td>
                <label for="ano_folha">Competência:</label>
              </td>
              <td>
                <?php db_input('ano_folha',   6, 0, true, 'text', 1); ?>
                /
                <?php db_input('mes_folha',   6, 0, true, 'text', 1); ?>
              </td>
            </tr>
            
            <tr>
              <td>
                <a id="link_competencia">Servidor:</a>
              </td>
              <td>
                <?php 
                  db_input('gerf',            6, 0, true, 'hidden', 3); 
                  db_input('rh143_sequencial',6, 0, true, 'hidden', 3); 
                  db_input('rh01_regist',     6, 0, true, 'text', $db_opcao);
                  db_input('z01_nome',        6, 0, true, 'text', 3); 
                ?>
              </td>
            </tr>
            <tr id="numero_folha" style="display: none;">
              <td>
                <label for="rh143_folhapagamento">Número:</label>
              </td>
              <td>
                <?php 

                  if ( !isset($oRequest->rh143_folhapagamento) ) {
                    reset($aCodigosFolha);
                    $aCodigo                        = each($aCodigosFolha);
                    $iCodigo                        = $aCodigo[0];
                    $oRequest->rh143_folhapagamento = $rh143_folhapagamento = $iCodigo;
                    reset($aCodigosFolha);
                  }
                  db_select("rh143_folhapagamento", $aCodigosFolha, true, $db_opcao, "class='field-size2' rel='ignore-css'  onchange='this.form.submit()'");
                ?>
              </td>
            </tr>
    
          </table>
        </fieldset>

        <fieldset id="field_lancamento" style="display: none;">
          <legend>Dados do Lançamento</legend>
          
          <table class="form-container">
            <tr>
              <td>
                <fieldset>
                  <legend>
                    <a id="link_rubrica">Rubrica</a>
                  </legend>
                  <?php 
                    if ( isset($oRequest->opcao) && $oRequest->opcao == "excluir") {
                      $db_opcao = 3;
                    }
                    db_input('rh143_rubrica', 4, 0, true, 'text', $db_opcao, 'lang="rh27_rubric" class="field-size2"'); 
                    db_input('rh27_descr',    0, 0, true, 'text', 3,         'disabled'); 
                  ?>
                </fieldset>
              </td>
              <td>
                <fieldset>
                  <legend><label for="rh143_tipoevento">Tipo</label></legend>
                  <?php 
                    $aTiposEventos = array("1" =>"Provento", 
                                           "2" =>"Desconto", 
                                           "3" =>"Base"    );
                    db_select("rh143_tipoevento", $aTiposEventos, true, $db_opcao, "class='field-size2' rel='ignore-css'");
                  ?>
                </fieldset>
              </td>
              <td>
                <fieldset>
                  <legend><label for="rh143_quantidade">Quantidade</label></legend>
                  <?php db_input('rh143_quantidade', 4, 4, true, 'text', $db_opcao, 'class="field-size2"'); ?>
                </fieldset>
              </td>
              <td>
                <fieldset>
                  <legend><label for="rh143_valor">Valor</label></legend>
                  <?php db_input('rh143_valor', 4, 0, true, 'text', $db_opcao, 'class="field-size2"'); ?>
                </fieldset>
              </td>
            </tr>
          </table>
        </fieldset>

        <?php 
          $sLabel = "Incluir";
          if ( isset($oRequest->opcao) ) {
            $sLabel = $oRequest->opcao == "alterar" ? "Alterar" : "Excluir";
          }
          echo "<input class='botoes' name='processar' id='processar' value='{$sLabel}' type='submit' style='display:none' /> ";
          echo "<input class='botoes' name='limpar'    id='limpar'    value='Limpar'    type='button' style='display:none' /> ";

          if ( isset($oRequest->opcao) ) {
            echo '<input class="botoes" id="novo" value="Novo"  type="button" name="novo" onclick="js_Redireciona();" style="display:none" />';
          }
        ?>
      <fieldset id="field_lancamentos" style="display: none; height: 300px;">
        <legend>Lançamentos</legend>
          <?php
           if ( !empty($oRequest->rh01_regist) ) {
              $oIframe->chavepri      = array("rh143_sequencial"  => $oRequest->rh143_sequencial, 
                                              "rh143_rubrica"     => $oRequest->rh143_rubrica,
                                              "rh143_quantidade"  => $oRequest->rh143_quantidade,
                                              "rh143_valor"       => $oRequest->rh143_valor,
                                              "rh143_tipoevento"  => $oRequest->rh143_tipoevento, 
                                              "rh27_descr"        => $oRequest->rh27_descr );
              $oIframe->iframe_height = "100%";
              $oIframe->iframe_width  = "100%";
              $oIframe->opcoes        = 1;
              $oIframe->fieldset      = false;
              $oIframe->formulario    = false;
              $oIframe->msg_vazio     = "Nenhum evento lançado para o Servidor selecionado.";
              $oIframe->campos        = "rh143_rubrica, rh27_descr, rh143_tipoevento, rh143_quantidade, rh143_valor";
              
              $sCampos                = "rh143_rubrica,";
              $sCampos               .= "rh27_descr,";
              $sCampos               .= "rh143_sequencial,";
              $sCampos               .= "case rh143_tipoevento ";
              $sCampos               .= "  when 1 then '1 - Provento'";
              $sCampos               .= "  when 2 then '2 - Desconto'";
              $sCampos               .= "  when 3 then '3 - Base'";
              $sCampos               .= "  else 'Inconsistente'";
              $sCampos               .= "end as rh143_tipoevento,";
              $sCampos               .= "rh143_quantidade,";
              $sCampos               .= "rh143_valor ";
              
              $sOrdem                 = "rh143_tipoevento, rh143_rubrica";

              $sWhere                 = "rh143_folhapagamento = {$oRequest->rh143_folhapagamento} ";
              $sWhere                .= " and rh143_regist    = {$oRequest->rh01_regist}";

              if ( !empty($oRequest->rh143_sequencial)) {
                $sWhere                .= " and rh143_sequencial <> {$oRequest->rh143_sequencial}";
              }

              $oIframe->sql          .= $oDaoHistoricoCalculo->sql_query(null, $sCampos, $sOrdem, $sWhere);
              $oIframe->iframe_alterar_excluir(1);
           }
          ?>
        </fieldset>
      </form>
    </div>
    <script>

      var js_Redireciona = function() {
        window.location.href = "pes1_gerffx001.php?gerf=" + $F('gerf')        + 
                               "&rh01_regist="            + $F('rh01_regist') +
                               "&z01_nome="               + $F('z01_nome')    + 
                               "&ano_folha="              + $F("ano_folha")   + 
                               "&mes_folha="              + $F("mes_folha");
      };
  
      (function(window) {
  
        require_once("scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js");
        require_once("scripts/widgets/DBLookUp.widget.js");
       
        /**
         * Validação de dados dos campos
         */
        $('rh01_regist').oninput       = function (event) { js_ValidaCampos( this, 1, 'Matrícula do Servidor', 'f', 'f', event ); };
        $('rh143_quantidade').oninput  = function (event) { js_ValidaCampos( this, 4, 'Quantidade', 'f', 'f', event ); };
        $('rh143_valor').oninput       = function (event) { js_ValidaCampos( this, 4, 'Valor', 'f', 'f', event ); };

        $('ano_folha').observe("change", js_Redireciona);
        $('mes_folha').observe("change", js_Redireciona);
       
        /**
         * Dados da competencia
         */
        var oCompetencia  = new DBViewFormularioFolha.CompetenciaFolha();
        oCompetencia.iAno = $F('ano_folha');
        oCompetencia.iMes = $F('mes_folha');
        oCompetencia.renderizaFormulario($('ano_folha'), $('mes_folha') );


        /**
         * Lookup Servidor
         * @type {DBLookUp}
         */
        var oLookupServidor = new DBLookUp(
          $('link_competencia'),
          $('rh01_regist'),
          $('z01_nome'),
          {sLabel : "Pesquisar Servidor", sArquivo : "func_rhpessoal.php", sObjetoLookUp : "db_iframe_rhpessoal"}
        );
       
       
        oLookupServidor.setCallBack("onChange", js_Redireciona);
        oLookupServidor.setCallBack("onClick", js_Redireciona);
        


        /**
         * Lookup de Pesquisa de Rubricas
         * @type {DBLookUp}
         */
        var oLookupRubrica = new DBLookUp(
          $('link_rubrica'),
          $('rh143_rubrica'),
          $('rh27_descr'),
          {sLabel : "Pesquisar Rubrica", sArquivo : "func_rhrubricas.php", sObjetoLookUp : "db_iframe_rhpessoal"}
        );
        oLookupRubrica.setCallBack("onChange", function() {
          $('rh143_tipoevento').focus();
        });
  
        /**
         * Comportamento dos dados dos FieldSets
         */
        if ( $F('rh01_regist') != "" && !$('rh01_regist').readOnly && $('rh143_folhapagamento').getValue()) {
          $('field_lancamento').style.display = '';
          $('field_lancamentos').style.display = '';
          $('processar').style.display = '';
          $('limpar').style.display = '';
          if ( $('novo') ) {
            $('novo').style.display = '';
          }
        };
  

        /**
         * Comportamento do campo da matricula
         */
        $('rh01_regist').observe("change", function() {
  
          if ( this.value == "" ) {
            js_Redireciona();
          }
        });
        
        /**
         * Comportamentos da mudança de numero da suplementar
         * Quand
         */
        if ( $F('gerf') != "fs" && $('rh143_folhapagamento').getValue()) {
          $('numero_folha').style.display = '';
        }
      })(window);
    </script>
  </body>
</html>
