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

/* MODULO: Laboratorio */
$oDaolabControlefisicoFinanceiro->rotulo->label();
$clrotulo = new rotulocampo;

?>
<form name="form1" method="post" action="">
  <table style='width: 95%;' border="0">
    <tr>
      <td colspan="2" align="center">
        <fieldset style='width: 99%;'> <legend><b>Periodo:</b></legend>
          <table>
            <tr>
              <td>
                <br>
                <fieldset style=' height:35px;' > <legend><b>Período Anterior:</b></legend>
                  <table>
                    <tr valign="center">
                      <td>
                        <b>Início:</b>
                      </td>
                      <td>
                        <?db_inputdata('la56_d_ini', @$la56_d_ini_dia, @$la56_d_ini_mes, @$la56_d_ini_ano,
                                        true,'text',$db_opcao,"")?>
                      </td>
                      <td>
                        <b>Fim:</b>
                      </td>
                      <td>
                        <?db_inputdata('la56_d_fim', @$la56_d_fim_dia, @$la56_d_fim_mes, @$la56_d_fim_ano,
                                        true,'text',$db_opcao,"")?>
                      </td>
                    <tr>
                  </table>
                </fieldset>
              </td>
              <td>
                <br>
                <fieldset style=' height:35px;'> <legend><b>Próximo Período:</b></legend>
                  <table>
                    <tr>
                      <td>
                        <b>Início:</b>
                      </td>
                      <td>
                        <?db_inputdata('la56_d_ini2', @$la56_d_ini2_dia, @$la56_d_ini2_mes, @$la56_d_ini2_ano,
                                        true,'text',$db_opcao,"")?>
                      </td>
                      <td>
                        <b>Fim:</b>
                      </td>
                      <td>
                        <?db_inputdata('la56_d_fim2', @$la56_d_fim2_dia, @$la56_d_fim2_mes, @$la56_d_fim2_ano,
                                        true,'text',$db_opcao,"")?>
                      </td>
                    <tr>
                  </table>
                </fieldset>
              </td>  
            </tr>
              <td colspan="2" align="center">
                <br>
                <input type="button" name="processar" id="processar" value="Processar" onclick="js_processar();">
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
  </table>
</form>

<script>

function js_processar() {
  
  if ($F('la56_d_ini').trim() == '' || $F('la56_d_fim').trim() == '') {
        
    alert('Entre com o período inicial');
    return false;
          
  }

  if (parseInt($F('la56_d_ini_ano').trim(), 10) >  parseInt($F('la56_d_fim_ano').trim(), 10) ||
     (parseInt($F('la56_d_ini_ano').trim(), 10) == parseInt($F('la56_d_fim_ano').trim(), 10) && 
      parseInt($F('la56_d_ini_mes').trim(), 10) >  parseInt($F('la56_d_fim_mes').trim(), 10))||
     (parseInt($F('la56_d_ini_ano').trim(), 10) == parseInt($F('la56_d_fim_ano').trim(), 10) &&
      parseInt($F('la56_d_ini_mes').trim(), 10) == parseInt($F('la56_d_fim_mes').trim(), 10) &&
      parseInt($F('la56_d_ini_dia').trim(), 10) >  parseInt($F('la56_d_fim_dia').trim(), 10))) {
    
    alert("Data inicial superior a data final do período a ser duplicado!");
    return false;
  
  } 

  if ($F('la56_d_ini2').trim() == '' || $F('la56_d_fim2').trim() == '') {
      
	  alert('Entre com o período final');
	  return false;
	          
	}
  
  if (parseInt($F('la56_d_ini2_ano').trim(), 10) >  parseInt($F('la56_d_fim2_ano').trim(), 10) ||
     (parseInt($F('la56_d_ini2_ano').trim(), 10) == parseInt($F('la56_d_fim2_ano').trim(), 10) && 
      parseInt($F('la56_d_ini2_mes').trim(), 10) >  parseInt($F('la56_d_fim2_mes').trim(), 10))||
     (parseInt($F('la56_d_ini2_ano').trim(), 10) == parseInt($F('la56_d_fim2_ano').trim(), 10) &&
      parseInt($F('la56_d_ini2_mes').trim(), 10) == parseInt($F('la56_d_fim2_mes').trim(), 10) &&
      parseInt($F('la56_d_ini2_dia').trim(), 10) >  parseInt($F('la56_d_fim2_dia').trim(), 10))) {
    
    alert("Data inicial superior a data final do próximo período!");
    return false;
  
  } 
  
  if ( (parseInt($F('la56_d_fim_ano').trim(), 10) >  parseInt($F('la56_d_ini2_ano').trim(), 10)) || 
	     (parseInt($F('la56_d_fim_ano').trim(), 10) == parseInt($F('la56_d_ini2_ano').trim(), 10) && 
        parseInt($F('la56_d_fim_mes').trim(), 10) >  parseInt($F('la56_d_ini2_mes').trim(), 10)) ||
	     (parseInt($F('la56_d_fim_ano').trim(), 10) == parseInt($F('la56_d_ini2_ano').trim(), 10) && 
        parseInt($F('la56_d_fim_mes').trim(), 10) == parseInt($F('la56_d_ini2_mes').trim(), 10) &&
        parseInt($F('la56_d_fim_dia').trim(), 10) >= parseInt($F('la56_d_ini2_dia').trim(), 10))) {

	  alert('A próxima competência não pode conter o intervalo de dias da competência anterior.');
	  return false;
	            
  }
  
  var oParam         = new Object();
  oParam.exec        = 'duplicarControleFisFin';
  oParam.la56_d_ini  = $F('la56_d_ini');
  oParam.la56_d_fim  = $F('la56_d_fim');
  oParam.la56_d_ini2 = $F('la56_d_ini2');
  oParam.la56_d_fim2 = $F('la56_d_fim2');

  js_webajax(oParam, 'js_retornoProcessar','lab4_laboratorio.RPC.php');
  
}

function js_retornoProcessar(oRetorno) {
  
  oRetorno = eval("("+oRetorno.responseText+")");
  
  if (oRetorno.iStatus == 0) {
    message_ajax(oRetorno.sMessage.urlDecode());
  } else {
    message_ajax(oRetorno.sMessage.urlDecode());
  }

}

</script>