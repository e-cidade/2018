<?
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
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <div class="container">
      <form name="form1" method="post" action="" enctype="multipart/form-data">
        <fieldset>
          <legend>Importação do Arquivo de Movimento</legend>
          <table>
            <tr>
              <td colspan="2">
                <fieldset>
                  <legend>Atenção</legend>
                  <table style="background-color: #fff; padding: 05px 10px;">
                    <tr>
                      <td>
                        Atenção após a importação deve ser efetuado o processamento do ponto.<br/>
                        Procedimentos > Processamento de Dados do Ponto
                      </td>
                    </tr>
                  </table>
                </fieldset>
            </tr>
            <tr>
              <td nowrap title="Ano / Mês">
                <label class="bold">
                  Ano / Mês:
                </label>
              </td>
              <td id="formularioCompetencia"></td>
            </tr>
            <tr>
              <td nowrap title="Arquivo" >
                <label class="bold" for="ano_mes" id="lbl_ano_mes">
                  Arquivo:
                </label>
              </td>
              <td>
                <?php db_input('aArquivoMovimento', 35, '', true, 'file', 1, ''); ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input name="incluir" type="submit" id="db_opcao" value="Processar" onclick="return js_validaCampo();">
      </form>
    </div>
    <?php 
      $sMensagem  = "Este menu mudou para:\n";
      $sMensagem .= "Pessoal > Procedimentos > Manutenção de Empréstimos Consignados > Convênios > Consignet > Importar Arquivo de Movimento\n";
      $sMensagem .= "A partir da próxima atualização o menu atual será retirado.";

      if(isset($oGet->menuDepreciado) && $oGet->menuDepreciado) {
        db_msgbox($sMensagem);
      }
      db_menu();
    ?>
  </body>
  <script>

    function js_validaCampo() {
      if ($('aArquivoMovimento').value == '') {
        alert( _M("recursoshumanos.pessoal.pes4_importacaoarquivoconsignado.arquivo_invalido") );
        return false;
      }

      js_divCarregando("Importando dados...",'carregandoArquivoImportacao');
      return true;
    }

    function js_exibeRelatorioImportacao(sArquivo) {

      var oDownload = new DBDownload();
  
      if( $('window01') ){
        $('window01').outerHTML = '';
      }
      
      if( sArquivo != ''){

        sArquivo = sArquivo.urlDecode();
        
        var sNomeArquivo = sArquivo.split('/')[1];
        oDownload.addFile( sArquivo, sNomeArquivo);
        oDownload.show();
      }
    }

    (function() {

      var oCompetenciaFolha = new DBViewFormularioFolha.CompetenciaFolha(true);
    
      oCompetenciaFolha.renderizaFormulario($('formularioCompetencia'));
      oCompetenciaFolha.desabilitarFormulario();
    })()
  </script>
</html>
