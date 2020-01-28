<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo;
$clrotulo->label('rh164_datainicio');
$clrotulo->label('rh164_datafim');
$clrotulo->label('rh01_regist');
$clrotulo->label('z01_nome');

?>

<html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php 
    db_app::load('scripts.js, prototype.js, strings.js, DBLookUp.widget.js, dates.js, geradorrelatorios.js, DBDownload.widget.js');
  ?>
  </script>  
  <link href="estilos.css" rel="stylesheet" type="text/css" />
  </head>
  <body>
    <form id="form1" name="form1" action="" method="post" class="container">
      <fieldset>
        <legend>Buscar Contratos Emergenciais</legend>
        <table class="container-form">
          <tr>
            <td>
              <?php echo $Lrh164_datainicio; ?>
            </td>
            <td>
              <?php db_inputdata('rh164_datainicio', @$rh164_datainicio_dia, @$rh164_datainicio_mes, @$rh164_datainicio_ano, true, 'text', 2, ""); ?>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $Lrh164_datafim; ?>
            </td>
            <td>
              <?php db_inputdata('rh164_datafim', @$rh164_datafim_dia, @$rh164_datafim_mes, @$rh164_datafim_ano, true, 'text', 2, ""); ?>
            </td>
          </tr>
          <tr>
            <td>
              <a id="procurarMatricula"><?php echo $Lrh01_regist; ?></a>
            </td>
            <td>
              <?php db_input('rh01_regist', 10, '', true, 'text', 1); ?>
              <?php db_input('z01_nome', 50, '', true, 'text', 3); ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" id="buscar" name="buscar" value="Buscar" onClick="js_validacampos()" />
    </form>
    <script type="text/javascript">

      var iCodigoRelatorio = 29;

      var oLookupServidor = new DBLookUp($("procurarMatricula"), $("rh01_regist"), $("z01_nome"), {
        "sArquivo"              : "func_rhpessoal.php",
        "sObjetoLookUp"         : "db_iframe_rhpessoal",
        "aParametrosAdicionais" : ["testarescisao=true&contratosEmergenciais=1"]
      });

      $("rh01_regist").className = "";

      function js_validacampos() {

        var sDataInicio = '';
        var sDataFim    = '';

        if(document.form1.rh01_regist.value == "" && document.form1.rh164_datainicio.value == "" && document.form1.rh164_datafim.value == "") {
          if(!confirm("Tem certeza que deseja emitir o relatório com todos contratos emergenciais?")) {
            return false;
          }
        }

        if(document.form1.rh164_datainicio.value == "" && document.form1.rh164_datafim.value != "") {
          alert("Informe uma data inicial para o período.");
          return false;
        }

        if(document.form1.rh164_datainicio.value != '' && document.form1.rh164_datafim.value != ''){
          sDataInicio = getDateInDatabaseFormat(document.form1.rh164_datainicio.value);
          sDataFim    = getDateInDatabaseFormat(document.form1.rh164_datafim.value);
          if(sDataFim < sDataInicio) {
            alert("A data final deve ser maior que a data inicial.");
            return false;
          }
        }

        js_emiteRelatorio();
      }

      function js_emiteRelatorio() {

        var aParametros = new Array();
        var sDatainicio = $F("rh164_datainicio") || '01/01/1979';
        var sDatafim    = $F("rh164_datafim")    || '31/12/2099';
        var sRegist     = $F("rh01_regist")      || 0;

        sDatainicio = getDateInDatabaseFormat(sDatainicio);
        sDatafim    = getDateInDatabaseFormat(sDatafim);

        aParametros.push( new js_criaObjetoVariavel('$sDatainicio', sDatainicio));
        aParametros.push( new js_criaObjetoVariavel('$sDatafim', sDatafim));
        aParametros.push( new js_criaObjetoVariavel('$sRegist', sRegist));

        js_imprimeRelatorio(iCodigoRelatorio,js_downloadArquivo,Object.toJSON(aParametros));
      }

      /**
       * Trata o retorno da função js_imprimeRelatorio
       */
      function js_downloadArquivo(oAjax) {

        js_removeObj('msgBox');
        var oRetorno = eval("("+oAjax.responseText+")");

        if ( oRetorno.erro ){

          alert(oRetorno.sMsg.urlDecode());
          return false;
        }

        var sUrl = oRetorno.sMsg;
            sUrl = sUrl.urlDecode();

        var oDBDownload = new DBDownload();
        oDBDownload.addFile(sUrl,'Contratos Emergenciais');
        oDBDownload.show();
      }

    </script>
    <?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
  </body>
</html>