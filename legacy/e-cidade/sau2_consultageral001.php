<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once('libs/db_stdlib.php');
require_once('libs/db_utils.php');
require_once('libs/db_conecta.php');
require_once('libs/db_sessoes.php');
require_once('libs/db_usuariosonline.php');
require_once('dbforms/db_funcoes.php');
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  </head>
  <body>
    <center>
      <table>
        <tr>
          <td>
            <fieldset>
              <Legend><b>Imprimir pesquisa</b></Legend>
              <table>
                <tr>
                  <td>
                    <b>Escolha o que será Impresso no relatório:</b>
                    <hr>
                  </td>
                </tr>  
                <tr>
                  <td>
                    <input type='checkbox' id='ck1' value='1' name='opcoes' checked>
                    <label for='ck1'>Documentos</label>
                    <?
                    db_input('z01_i_cgsund', 10, '', true, 'hidden', 3, '');
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <input type='checkbox' id='ck2' value='2' name='opcoes' checked>
                    <label for='ck2'>Cartão SUS</label>
                  </td>
                </tr>
                <tr>
                  <td>
                    <input type='checkbox' id='ck3' value='3' name='opcoes' checked>
                    <label for='ck3'>Agendamentos</label>
                  </td>
                </tr>
                <tr>
                  <td>
                    <input type='checkbox' id='ck4' value='4' name='opcoes' checked>
                    <label for='ck4'>Atendimentos</label>
                  </td>
                </tr>
                <tr>
                  <td>
                    <input type='checkbox' id='ck5' value='5' name='opcoes' checked>
                    <label for='ck5'>Retiradas de Medicamentos</label>
                  </td>
                </tr>
                <tr>
                  <td>
                    <input type='checkbox' id='ck6' value='6' name='opcoes' checked>
                    <label for='ck6'>Exames</label>
                  </td>
                </tr>
                <tr>
                  <td>
                    <input type='checkbox' id='ck7' value='7' name='opcoes' checked>
                    <label for='ck7'>Pedidos de TFD</label>
                  </td>
                </tr>
                <tr>
                  <td>
                    <input type='checkbox' id='ck8' value='8' name='opcoes' checked>
                    <label for='ck8'>Vacinas</label>
                  </td>
                </tr>
                <tr>
                  <td>
                    <input type='checkbox' id='ck9' value='9' name='opcoes' checked>
                    <label for='ck9'>Hiperdia</label>
                  </td>
                </tr>
                <? if (db_permissaomenu(date('Y'), db_getsession('DB_modulo'), 8813) == 'true') { ?>
                <tr>
                  <td>
                    <input type='checkbox' id='ck10' value='10' name='opcoes' checked>
                    <label for='ck10'>CID's</label>
                  </td>
                </tr>
                <?}?>
              </table>  
            </fieldset>
          </td>
          <td align="center" valign="top">
            <fieldset><legend><b>Periodo</b></legend>
              <table>
                <tr>
                  <td><b>Inicio:</b></td>
                  <td>
                    <? db_inputdata('dIni',@$dia1,@$mes1,@$ano1,true,'text',1,"") ?>
                  </td>
                  <td><b>Fim:</b></td>
                  <td>
                    <? db_inputdata('dFim',@$dia2,@$mes2,@$ano2,true,'text',1,"") ?>
                  </td>
                </tr>
              </table>
            </fieldset>
            <br>
            <fieldset><legend><b>Opções</b></legend>
              <table>
                <tr>
                  <td><b>Tipo:</b></td>
                  <td>
                    <?
                      $aX = array('1'=>'Analitico', '2'=>'Sintetico');
                      db_select('iTipo', $aX, true, 1, '');
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>
            <br><br>
            
            <input type='button' value='Imprimir' onclick='js_imprimir();'>
          </td>
        </tr>
      </table>
    </center>
  </body>
</html>
<script>
function js_imprimir(iNumEmp) {
  
  if (document.getElementById('z01_i_cgsund').value == '') {

    alert('CGS não informado!');
    return false;

  }

  var sUrl    = 'sau2_consultageral002.php?iCgs='+document.getElementById('z01_i_cgsund').value+'&sOpcoesImp=';
  var aOpcoes = document.getElementsByName('opcoes');
  var sSep    = '';
  
  for (var iCont = 0; iCont < aOpcoes.length; iCont++) {

    if (aOpcoes[iCont].checked) {

      sUrl += sSep+aOpcoes[iCont].value;
      sSep  = ',';

    }

  }

  if (sSep == '') { // nenhum checkbox foi marcado
	  
	  alert('Selecione no minimo uma opção de relatório!');
	  return false;
	  
	}
  sUrl += '&iTipo='+document.getElementById('iTipo').value;
  if(document.getElementById('dIni').value != ''){
    sUrl += '&dIni='+document.getElementById('dIni').value;
  }
  if(document.getElementById('dFim').value != ''){
	  sUrl += '&dFim='+document.getElementById('dFim').value;
	}
  
  var oJan = window.open(sUrl, '', 'width='+(screen.availWidth - 5)+', height='+(screen.availHeight - 40)+
		                     ',scrollbars=1,location=0 '
                        );

}
</script>