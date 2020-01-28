<?
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
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<br>
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
        <?
        $oRotulo = new rotulocampo;
        $oRotulo->label('s159_i_receita');
        $oRotulo->label('fa04_i_cgsund');
        ?>
        <form name="form1" method="post" action=''>
        <center>
        <table border="0" width="80%">
          <tr>
            <td nowrap>
              <fieldset style='width: 96%;'> <legend><b>Receita:</b></legend>
                <table>
                  <tr>
                    <td nowrap title="<?=$Ts159_i_receita?>">
                      <?=$Ls159_i_receita?>
                    </td>
                    <td> 
                      <?
                      db_input('s159_i_receita', 10, $Is159_i_receita, true, 'text', 3, '');
                      db_input('fa04_i_cgsund', 10, $Ifa04_i_cgsund, true, 'hidden', 3, '');
                      ?>
                    </td>
                  </tr>
                </table>
              </fieldset>
            </td>
          </tr>
          <tr>
            <td nowrap>
              <fieldset style='width: 96%;'> <legend><b>Medicamentos:</b></legend>
                <table width="100%">
                  <tr>
                    <td nowrap>
                      <div id='grid_remedios' style='width: 100%;'></div>
                    </td>
                  </tr>
                </table>
              </fieldset>
            </td>
          </tr>
          <tr>
            <td align="center">
              <input type="button" id="confirmar" value="Confirmar" onclick="js_confirmar();">
              <input type="button" id="fechar" value="Fechar" onclick="js_fechar();">
            </td>
          </tr>
        </table>

        <script>

        parent.document.getElementById('fechardb_iframe_medicamentosreceita').onclick = function() { js_fechar(); }
        oDBGridRemedios = js_criaDataGrid();

        js_getRemediosReceita();

        function js_ajax(oParam, jsRetorno, sUrl, lAsync) {

          var mRetornoAjax;
        
          if (sUrl == undefined) {
            sUrl = 'sau4_ambulatorial.RPC.php';
          }
        
          if (lAsync == undefined) {
            lAsync = false;
          }
          
          var oAjax = new Ajax.Request(sUrl, 
                                       {
                                         method: 'post', 
                                         asynchronous: lAsync,
                                         parameters: 'json='+Object.toJSON(oParam),
                                         onComplete: function(oAjax) {
                                            
                                                       var evlJS    = jsRetorno+'(oAjax);';
                                                       return mRetornoAjax = eval(evlJS);
                                                       
                                                   }
                                      }
                                     );

          return mRetornoAjax;

        }

        /**** Bloco de funções do grid início */
        function js_criaDataGrid() {

          var oDBGrid            = new DBGrid('oDBGridRemedios');
          oDBGrid.nameInstance   = 'oDBGridRemedios';
          oDBGrid.hasTotalizador = false;
          oDBGrid.setCellWidth(new Array('10%', '40%', '10%', '20%', '20%'));
          oDBGrid.setHeight(60);
          oDBGrid.allowSelectColumns(false);
        
          var aHeader = new Array();
          aHeader[0] = '<input type="button" onclick="js_marcarTodos(this)" value="D">';
          aHeader[1] = 'Código';
          aHeader[2] = 'Medicamento';
          aHeader[3] = 'Qtde.';
          aHeader[4] = 'Saldo';
          oDBGrid.setHeader(aHeader);
        
          var aAligns = new Array();
          aAligns[0]  = 'center';
          aAligns[1]  = 'left';
          aAligns[2]  = 'left';
          aAligns[3]  = 'left';
          aAligns[4]  = 'left';
          oDBGrid.setCellAlign(aAligns);
        
          oDBGrid.show($('grid_remedios'));
          oDBGrid.clearAll(true);
        
          return oDBGrid;
        
        }
       
        function js_getRemediosReceita() {

          var oParam      = new Object();
          oParam.exec     = 'getRemediosReceita';
          oParam.iReceita = $F('s159_i_receita');
        
          oDBGridRemedios.clearAll(true); // Limpo o grid
          if ($F('s159_i_receita').trim() != '') {
            js_ajax(oParam, 'js_retornoGetRemediosReceita');
          } else {
            alert('Receita não informada!');
          }

        }
       
        function js_retornoGetRemediosReceita(oRetorno) {
  
          var oRetorno = eval("("+oRetorno.responseText+")");
          var oInfo    = new Object();
          var aPaint   = new Array();
        
          if (oRetorno.iStatus == 1) {
            
            for (var iCont = 0; iCont < oRetorno.aMedicamentos.length; iCont++) {
        
              with (oRetorno.aMedicamentos[iCont]) {

                oInfo = js_getInfoMedicamento(s159_i_medicamento);
                if (parseInt(s159_n_quant, 10) > parseInt(oInfo.iSaldoDepartamento, 10)) {
                  aPaint[aPaint.length] = iCont;
                }
                oDBGridRemedios.addRow(js_criaLinhaGrid(s159_i_codigo, s159_i_formaadm, s159_i_medicamento, 
                                                        m60_descr.urlDecode(), s159_n_quant, s160_c_descr.urlDecode(),
                                                        s159_t_posologia.urlDecode(), iCont, oInfo
                                                       )
                                      );
        
              }
        
            }
        
            oDBGridRemedios.renderRows();

            for (var iCont = 0; iCont < aPaint.length; iCont++) {

              $('oDBGridRemediosrowoDBGridRemedios'+aPaint[iCont]).style.backgroundColor = '#FF0000';

            }
        
          }

        }
       
        function js_criaLinhaGrid(s159_i_codigo, s159_i_formaadm, s159_i_medicamento, 
                                  m60_descr, s159_n_quant, s160_c_descr, s159_t_posologia, iId, oInfo) {

          var aLinha  = new Array();
          var sDisab  = parseInt(s159_n_quant, 10) > parseInt(oInfo.iSaldoDepartamento, 10) ? ' disabled ' : '';
          var sCheck  = parseInt(s159_n_quant, 10) > parseInt(oInfo.iSaldoDepartamento, 10) ? '' : ' checked ';
          var sHidden = '<input type="hidden" id="s159_i_codigo'+iId+'" value="'+s159_i_codigo+'">';
          sHidden    += '<input type="hidden" id="s159_i_medicamento'+iId+'" value="'+s159_i_medicamento+'">';
          sHidden    += '<input type="hidden" id="s159_i_formaadm'+iId+'" value="'+s159_i_formaadm+'">';
          sHidden    += '<input type="hidden" id="s159_t_posologia'+iId+'" value="'+s159_t_posologia+'">';
          sHidden    += '<input type="hidden" id="m60_descr'+iId+'" value="'+m60_descr+'">';
          sHidden    += '<input type="hidden" id="s160_c_descr'+iId+'" value="'+s160_c_descr+'">';
          sHidden    += '<input type="hidden" id="s159_n_quant'+iId+'" value="'+s159_n_quant+'">';
          sHidden    += '<input type="hidden" id="sTipo'+iId+'" value="'+oInfo.sTipo+'">';
          sHidden    += '<input type="hidden" id="iSaldoDepartamento'+iId+'" value="'+oInfo.iSaldoDepartamento+'">';
          sHidden    += '<input type="hidden" id="dValidade'+iId+'" value="'+oInfo.dValidade+'">';
          sHidden    += '<input type="hidden" id="iCodMatItem'+iId+'" value="'+oInfo.iCodMatItem+'">';
          sHidden    += '<input type="hidden" id="sLote'+iId+'" value="'+oInfo.sLote+'">';
          sHidden    += '<input type="hidden" id="nPontoPedido'+iId+'" value="'+oInfo.nPontoPedido+'">';
          sHidden    += '<input type="hidden" id="lHiperdia'+iId+'" value="'+oInfo.lHiperdia+'">';
          sHidden    += '<input type="hidden" id="iMargemCont'+iId+'" value="'+oInfo.iMargemCont+'">';
          sHidden    += '<input type="hidden" id="iPrazoCont'+iId+'" value="'+oInfo.iPrazoCont+'">';
          sHidden    += '<input type="hidden" id="iQuantidadeCont'+iId+'" value="'+oInfo.iQuantidadeCont+'">';
          sHidden    += '<input type="hidden" id="iSaldoCont'+iId+'" value="'+oInfo.iSaldoCont+'">';
          sHidden    += '<input type="hidden" id="dProxDisp'+iId+'" value="'+oInfo.dProxDisp+'">';

          aLinha[0]   = '<input type="checkbox" name="ckbox" id=ckbox'+iId+' value="'+iId+'" '+sDisab+sCheck+'>';
          aLinha[1]   = s159_i_medicamento+sHidden;
          aLinha[2]   = m60_descr;
          aLinha[3]   = s159_n_quant;
          aLinha[4]   = oInfo.iSaldoDepartamento;
        
          return aLinha;

        }

        function js_marcarTodos(oButton) {

          var oCk = document.getElementsByName('ckbox');

          if (oButton.value == 'M') {

            oButton.value = 'D';
            for (var iCont = 0; iCont < oCk.length; iCont++) {
              
              if (!oCk[iCont].disabled) {
                oCk[iCont].checked = true;
              }

            }


          } else {

            oButton.value = 'M';
            for (var iCont = 0; iCont < oCk.length; iCont++) {

              if (!oCk[iCont].disabled) {
                oCk[iCont].checked = false;
              }

            }

          }

        }

        function js_getInfoMedicamento(iMedicamento) {

          var oParam               = new Object();
          oParam.exec              = 'ConsultaSaldo';
          oParam.fa06_i_matersaude = iMedicamento;
          oParam.fa04_i_cgsund     = $F('fa04_i_cgsund');

          return js_ajax(oParam, 'js_retornoGetInfoMedicamento', 'far1_far_retiradaRPC.php');
        
        }
       
        function js_retornoGetInfoMedicamento(oRetorno) {

          var oRetorno = eval('('+oRetorno.responseText+')');
          var oRet     = new Object();

          if (oRetorno.status == 1) {


            oRet.iSaldoDepartamento = oRetorno.quant_disp;
            oRet.dValidade          = oRetorno.validade;
            oRet.iCodMatItem        = oRetorno.lote;
            oRet.sLote              = oRetorno.loteReal;
            oRet.nPontoPedido       = oRetorno.m64_pontopedido;
            oRet.lHiperdia          = oRetorno.hiperdia;

            if (oRetorno.iExContinuado == 0) { // Verifico é ou não um continuado do paciente

              oRet.sTipo           = 'N'; // Não é continuado
              oRet.iMargemCont     = '';
              oRet.iPrazoCont      = '';
              oRet.iQuantidadeCont = '';
              oRet.iSaldoCont      = '';
              oRet.dProxDisp       = '';

            } else {
        
              oRet.sTipo           = 'C';
              oRet.iMargemCont     = oRetorno.fa10_i_margem;
              oRet.iPrazoCont      = oRetorno.fa10_i_prazo;
              oRet.iQuantidadeCont = oRetorno.fa10_i_quantidade;
              oRet.iSaldoCont      = oRetorno.saldo_atual;
              oRet.dProxDisp       = oRetorno.prox_data.urlDecode();

            }

          } else {
            alert(oRetorno.message.urlDecode()); 
          }

          return oRet;

        }

        function js_confirmar() {

          var oCk       = document.getElementsByName('ckbox');
          var aRemedios = new Array();
          var iInd      = 0;

          for (var iCont = 0; iCont < oCk.length; iCont++) {

            if ($('ckbox'+iCont).checked) {

              aRemedios[iInd]  = $F('sTipo'+iCont)+'#'+$F('s159_i_medicamento'+iCont);
              aRemedios[iInd] += '#'+$F('m60_descr'+iCont)+'#'+$F('dProxDisp'+iCont);
              aRemedios[iInd] += '#'+$F('iPrazoCont'+iCont)+'#'+$F('iMargemCont'+iCont)+'#'+$F('iQuantidadeCont'+iCont);
              aRemedios[iInd] += '#'+$F('iSaldoCont'+iCont)+'#'+$F('iSaldoDepartamento'+iCont);
              aRemedios[iInd] += '#'+$F('dValidade'+iCont)+'#'+$F('sLote'+iCont)+'#'+$F('s159_n_quant'+iCont);
              aRemedios[iInd] += '#'+$F('s159_t_posologia'+iCont)+'#'+$F('iCodMatItem'+iCont);
              aRemedios[iInd] += '#'+$F('nPontoPedido'+iCont)+'#'+$F('lHiperdia'+iCont)+'#false';
              iInd++;

            }

          }

          if (aRemedios.length <= 0) {

            alert('Selecione pelo menos 1 medicamento para dispensar.');
            return false;

          }
          
          // Lanço as informações no formulário da retirada de medicamentos
          var lFlag     = false; // Indica se o medicamento era continuado e já estava no select / grid
          var iTam      = 0;
          var oSelDados = parent.document.getElementById('DadosGridRemedios');
          var iTamDados = 0;
          if (oSelDados != undefined && oSelDados != null) {
            iTamDados = oSelDados.length;
          }
          for (var iCont = 0; iCont < aRemedios.length; iCont++) {
           
            // Verifico se o medicamento já está lançado como continuado. Se tiver, só atualizo a qtde e a poso.
            for (var iCont2 = 0; iCont2 < iTamDados; iCont2++ ) {
              
              var aTmp = aRemedios[iCont].split('#');
              if (oSelDados.options[iCont2].text.split('#')[1] == aTmp[1]) {

                parent.js_atualizaCampo(iCont2, aTmp[11], 11);
                parent.js_atualizaCampo(iCont2, aTmp[12], 12);
                lFlag = true;
                break;

              }

            }

            if (lFlag) {

              lFlag = false;
              continue;

            }
            parent.document.getElementById('DadosGridRemedios').add(new Option(aRemedios[iCont], 1), null);

          }
          parent.js_AtualizaGrid();
          parent.db_iframe_medicamentosreceita.hide();

        }
        /* FUNÇÕES DO GRID - FIM *************************/

        function js_fechar() {
          
          parent.js_limpaDadosReceita();
          parent.db_iframe_medicamentosreceita.hide();

        }
        </script>
      </center>
    </td>
  </tr>
</table>
</center>
</body>
</html>