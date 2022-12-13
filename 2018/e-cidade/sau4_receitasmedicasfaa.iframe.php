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
        $oRotulo->label('z01_nome');
        $oRotulo->label('s162_i_prontuario');
        $oRotulo->label('z01_i_cgsund');
        $oRotulo->label('z01_v_nome');
        ?>
        <form name="form1" method="post" action=''>
        <center>
        <table border="0">
          <tr>
            <td nowrap>
              <fieldset style='width: 96%;'> <legend><b>Paciente / FAA:</b></legend>
                <table>
                  <tr>
                    <td nowrap title="<?=@$Ts162_i_prontuario?>">
                      <?=@$Ls162_i_prontuario?>
                    </td>
                    <td> 
                      <?
                      db_input('s162_i_prontuario',10,$Is162_i_prontuario,true,'text',3,'');
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td nowrap title="<?=@$Tz01_i_cgsund?>">
                      <?=$Lz01_i_cgsund?>
                    </td>
                    <td nowrap> 
                      <?
                      db_input('z01_i_cgsund', 10, $Iz01_i_cgsund, true, 'text', 3, '');
                      db_input('z01_v_nome', 50, $Iz01_v_nome, true, 'text', 3, '');
                      ?>
                    </td>
                  </tr>
                </table>
              </fieldset>
            </td>
          </tr>
          <tr>
            <td nowrap>
              <fieldset style='width: 96%;'> <legend><b>Receitas:</b></legend>
                <table width="100%">
                  <tr>
                    <td nowrap>
                      <table border="0" width="90%">
                        <tr>
                          <td>
                            <div id='grid_receitas' style='width: 100%;'></div>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </fieldset>
            </td>
          </tr>
          <tr>
            <td align="center">
              <input type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_receitas.hide();">
            </td>
          </tr>
        </table>

        <script>
        oDBGridReceitas = js_criaDataGrid();
        js_getReceitasFaa();
        
        function js_ajax(oParam, jsRetorno, sUrl, lAsync) {
        
          var mRetornoAjax;
        
          if (sUrl == undefined) {
            sUrl = 'sau4_ambulatorial.RPC.php';
          }
        
          if (lAsync == undefined) {
            lAsync = true;
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
        
          var oDBGrid            = new DBGrid('grid_receitas');
          oDBGrid.nameInstance   = 'oDBGridReceitas';
          oDBGrid.hasTotalizador = false;
          oDBGrid.setCellWidth(new Array('15%', '40%', '15%', '15%', '15%'));
          oDBGrid.setHeight(160);
          oDBGrid.allowSelectColumns(false);
        
          var aHeader = new Array();
          aHeader[0] = 'Receita';
          aHeader[1] = 'Tipo Receita';
          aHeader[2] = 'Validade';
          aHeader[3] = 'Situação';
          aHeader[4] = 'Opções';
          oDBGrid.setHeader(aHeader);
        
          var aAligns = new Array();
          aAligns[0]  = 'left';
          aAligns[1]  = 'left';
          aAligns[2]  = 'left';
          aAligns[3]  = 'left';
          aAligns[4]  = 'center';
          oDBGrid.setCellAlign(aAligns);
        
          oDBGrid.show($('grid_receitas'));
          oDBGrid.clearAll(true);
        
          return oDBGrid;
        
        }
        
        function js_getReceitasFaa() {
        
          var oParam  = new Object();
          oParam.exec = 'getReceitasFaa';
          oParam.iFaa = $F('s162_i_prontuario');
        
          oDBGridReceitas.clearAll(true); // Limpo o grid
          if ($F('s162_i_prontuario').trim() != '') {
            js_ajax(oParam, 'js_retornoGetReceitasFaa');
          }
        
        }
        
        function js_retornoGetReceitasFaa(oRetorno) {
          
          var oRetorno = eval("("+oRetorno.responseText+")");
        
          if (oRetorno.iStatus == 1) {
            
            for (var iCont = 0; iCont < oRetorno.aReceitas.length; iCont++) {
        
              with (oRetorno.aReceitas[iCont]) {

                var aLinha  = new Array();
                var sSit    = s158_i_situacao == 1 ? 'Normal' : (s158_i_situacao == 2 ? 'Atendida' : 'Anulada');
                var sHidden = '<input type="hidden" id="s158_i_codigo'+iCont+'" value="'+s158_i_codigo+'">';
                sHidden    += '<input type="hidden" id="s158_i_situacao'+iCont+'" value="'+s158_i_situacao+'">';
                aLinha[0]   = s158_i_codigo+sHidden;
                aLinha[1]   = fa03_c_descr.urlDecode();
                aLinha[2]   = js_formataData(s158_d_validade);
                aLinha[3]   = sSit;
                aLinha[4]   = '<input type="button" onclick="js_visualizar('+s158_i_codigo+');" value="Visualizar">';
        
                oDBGridReceitas.addRow(aLinha);
        
              }
        
            }
        
            oDBGridReceitas.renderRows();
            return true;
        
          } else {

            alert('Nenhuma receita emitida para este paciente.');
            return false;

          }
        
        }
        /* FUNÇÕES DO GRID - FIM *************************/

        function js_formataData(dData) {
  
          if (dData == undefined || dData.length != 10) {
            return dData;
          }
          return dData.substr(8, 2)+'/'+dData.substr(5, 2)+'/'+dData.substr(0, 4);
        
        }
        function js_visualizar(iCod) {

          parent.js_nova(iCod);

        }
        </script>
      </center>
    </td>
  </tr>
</table>
</center>
</body>
</html>