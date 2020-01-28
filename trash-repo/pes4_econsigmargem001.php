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
  require_once("libs/db_utils.php");
  require_once("libs/db_app.utils.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js, strings.js, prototype.js, estilos.css");
    ?>
    <script type="text/javascript" src="scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js"></script>
    <script type="text/javascript" src="scripts/json2.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
  </head>
  <body style="background-color: #ccc; margin-top: 30px">

    <div class="container">

      <form action="" method="POST">
        
        <fieldset>
          <legend>Arquivo E-Consig - Margem</legend>

          <table class="form-container">
            <tr>
              <td id="labelCompetencia"></td>
              <td id="competencia"></td>
            </tr>
          </table>

        </fieldset>
        
        <input type="button" id="gerar" value="Gerar" />

      </form>
    </div>

  <script type="text/javascript">

    (function() {

      var oCompetencia = new DBViewFormularioFolha.CompetenciaFolha(true);

      oCompetencia.renderizaLabel($("labelCompetencia"));
      oCompetencia.renderizaFormulario($("competencia"));


      var sUrlRpc = "pes4_geracaoarquivoeconsig.RPC.php";

      $("gerar").observe("click", function() {

        var oParametros = {
          iAnoUsu: oCompetencia.oAno.sValue,
          iMesUsu: oCompetencia.oMes.sValue,
          sExecucao: "gerarArquivoMargem"
        }

        var oDadosRequisicao = {
          method: "POST",
          asynchronous: false,
          parameters: 'json='+Object.toJSON(oParametros),
          onComplete: function(oAjax) {

            var oRetorno = JSON.parse(oAjax.responseText);

            if (oRetorno.iStatus == "2") {
              alert(oRetorno.sMensagem.urlDecode());
              return false;
            }

            /**
             * Remove DBDownload caso ja exista.
             */ 
            if( $('window01') ){
              $('window01').outerHTML = '';
            }

            var oDownload = new DBDownload();
            oDownload.addGroups("txt", "Arquivos");
            oDownload.addFile(oRetorno.sArquivoEconsig.urlDecode(), oRetorno.sNomeArquivo.urlDecode(), "txt");
            oDownload.show();
          }
        }

        new Ajax.Request(sUrlRpc, oDadosRequisicao)


        return false;
      })

    })();

  </script>

  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  </body>
</html>
<?php

?>