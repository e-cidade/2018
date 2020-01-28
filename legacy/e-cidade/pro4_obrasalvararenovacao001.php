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

  require_once("libs/db_stdlib.php");
  require_once("libs/db_utils.php");
  require_once("libs/db_app.utils.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");
  require_once("dbforms/db_funcoes.php");

  $clObrasAlvara = new cl_obrasalvara;
  $clObrasAlvaraHistorico = new cl_obrasalvarahistorico;
  $clRotulo      = new rotulocampo;

  $clObrasAlvara->rotulo->label();
  $clObrasAlvaraHistorico->rotulo->label();
  $clRotulo->label("ob01_nomeobra");
  $clRotulo->label("p58_codproc");
  $clRotulo->label("p58_requer");

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js, strings.js, prototype.js, dates.js, estilos.css");
    ?>
  </head>
  <body style="background-color: #ccc; margin-top: 30px">

  <form class="container" name="form1" id="form1">
    <fieldset>
      <legend>Renovação de Alvará de Obras</legend>
      <table class="form-container">
        <tr>
          <td>
            <?
              db_ancora($Lob04_codobra,"js_pesquisaObra(true);",3);
            ?>
          </td>
          <td>
            <? 
              db_input('ob04_codobra',10,$Iob04_codobra,true,'text',3," onchange='js_pesquisaObra(false);'");
              db_input('ob01_nomeobra',40,$Iob01_nomeobra,true,'text',3,'');
            ?>
          </td>
        </tr>
        <tr>
          <td><label><?=$Lob04_alvara?></label></td>
          <td>
            <?php
              db_input('ob04_alvara', 10, $Iob04_alvara, true, 'text', 3, "");
            ?>
          </td>
        </tr>
        <tr>
          <td><label><?=$Lob04_dataexpedicao?></label></td>
          <td>
            <?php
              db_input('ob04_dataexpedicao', 10, $Iob04_dataexpedicao, true, 'text', 3, "");
            ?>
          </td>
        </tr>
        <tr>
          <td><label>Último Período: </label></td>
          <td>
            <?php
              db_input('ob04_data', 10, $Iob04_data, true, "text", 3);
            ?>
            <strong>á</strong>
            <?php
              db_input('ob04_dtvalidade', 10, $Iob04_dtvalidade, true, "text", 3);
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <label>Data Inicial da Renovação: </label>
          </td>
          <td>
            <?php
              db_inputdata('datainicial', null, null, null, true, 'text', 1, "");
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <label>Data Final da Renovação: </label>
          </td>
          <td>
            <?php
              db_inputdata('datafinal', null, null, null, true, 'text', 1, "");
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="button" id="processar" name="processar" value="Processar" onclick="js_processar()" />
    <input type="button" name="pesquisar" value="Pesquisar" onclick="js_pesquisa()" />
  </form>



  <script type="text/javascript">

    js_pesquisa();
    $('processar').disable();
    const MENSAGEM = 'tributario.projetos.pro4_obrasalvara.';

    var sURL = 'pro4_obrasalvara.RPC.php';

    function js_processar(){

      if ($F('datainicial') == ''){

        alert(_M(MENSAGEM + 'data_inicial_nao_informado'));  
        return false;
      }

      if ($F('datafinal') == ''){

        alert(_M(MENSAGEM + 'data_final_nao_informado'));  
        return false;
      }


      var oParam          = {};
      oParam.exec         = 'renovaAlvara';
      oParam.iCodigoObra  = $F('ob04_codobra');
      oParam.sDataInicial = js_formatar($F('datainicial'),'d');
      oParam.sDataFinal   = js_formatar($F('datafinal'),'d');
      var oAjax = new Ajax.Request(sURL,
                                  {
                                    method    : 'POST',
                                    parameters: 'json=' + Object.toJSON(oParam), 
                                    onComplete: function(oAjax){

                                      var oRetorno = eval("("+oAjax.responseText+")");
                                      
                                      alert(oRetorno.sMessage.urlDecode());

                                      if (oRetorno.iStatus == "1") {
                                        window.location = window.location;
                                      }
                                    }
                                  });

    }

    function js_dadosAlvara(iCodigoObra){

      var oParam         = {};
      oParam.exec        = 'getAlvara';
      oParam.iCodigoObra = iCodigoObra;

      var oAjax = new Ajax.Request(sURL,
                                  {
                                    method    : 'POST',
                                    parameters: 'json=' + Object.toJSON(oParam), 
                                    onComplete: function(oAjax){

                                      var oRetorno = eval("("+oAjax.responseText+")");
                                      var oAlvara  = oRetorno.oAlvara;
                                      $('ob04_alvara').value        = oAlvara.ob04_alvara;
                                      $('ob04_dataexpedicao').value = js_formatar(oAlvara.ob04_dataexpedicao,'d');
                                      $('ob04_data').value          = js_formatar(oAlvara.ob04_data,'d');
                                      $('ob04_dtvalidade').value    = js_formatar(oAlvara.ob04_dtvalidade,'d');
                                      $('datafinal').value          = '';

                                      var aData = oAlvara.ob04_dtvalidade.split('-');
                                      var dDate = new Date(+aData[0], +aData[1]-1, +aData[2]+1);
                                      
                                      $('datainicial').value = dDate.toLocaleDateString().replace(/(\d{2})-(\d{2})-(\d+)/, "$1/$2/$3");
                                    }
                                  });
    }

    function js_mostraObra(iCodObra,sNomeObra){
      
      $('processar').enable();
      $('ob04_codobra').value  = iCodObra;
      $('ob01_nomeobra').value = sNomeObra;
      js_dadosAlvara(iCodObra);
      db_iframe_obrasalvara.hide();
    }

    function js_pesquisa(){
        js_OpenJanelaIframe('top.corpo','db_iframe_obrasalvara','func_obrasalvara.php?funcao_js=parent.js_mostraObra|ob04_codobra|ob01_nomeobra','Pesquisa',true);
    }
  </script>
  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  </body>
</html>
<?php
?>