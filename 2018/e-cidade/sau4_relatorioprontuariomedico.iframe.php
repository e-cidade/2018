<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
require_once('libs/db_conecta.php');
require_once('libs/db_sessoes.php');
require_once('libs/db_usuariosonline.php');
require_once('libs/db_utils.php');
require_once('dbforms/db_funcoes.php');

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<br><br>
<center>
<fieldset style="width: 40%;"><legend><b>Período para Gerar o Prontuário Eletrônico</b></legend>
  <table border="0">
    <tr>
      <td nowrap title="<?=@$Ts115_c_cartaosus?>">
         <b>Data de Início:</b>
      </td>
      <td>
        <?
        db_input('iFaa', 5, '', true, 'hidden', 3, '');
        db_input('iCgs', 5, '', true, 'hidden', 3, '');
        db_inputdata('dIni', @$dIni_dia, @$dIni_mes, @$dIni_ano, true, 'text', 1);
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Ts115_c_cartaosus?>">
         <b>Data de Fim:</b>
      </td>
      <td>
        <?
        $dDataFim = date('d/m/Y', db_getsession('DB_datausu'));
        $aDataFim = explode('/', $dDataFim);
        $dFim_dia = $aDataFim[0];
        $dFim_mes = $aDataFim[1];
        $dFim_ano = $aDataFim[2];
  
        db_inputdata('dFim', @$dFim_dia, @$dFim_mes, @$dFim_ano, true, 'text', 1);
        ?>
      </td>
    </tr>
  </table>
</fieldset>  
<br>
<input name="relatorio" type="button" id="relatorio" value="Relatório" onclick="js_mandaDados();">
<input name="fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_relatorioprontuario.hide();">
</center>

</form>

<script type="text/javascript">

$('dIni').style.backgroundColor = 'rgb(230, 228, 241)';

<?
if (isset($iFaa) && !isset($iCgs)) {
  echo 'js_getCgsFaa();';
}
?>

function js_ajax( objParam, jsRetorno, sUrl, lAsync) {

  var mRetornoAjax;

  if (sUrl == undefined) {
    sUrl = 'far1_far_retiradaRPC.php';
  }

  if (lAsync == undefined) {
    lAsync = true;
  }
  
    var objAjax = new Ajax.Request(
                         sUrl, 
                         {
                          method    : 'post', 
                          asynchronous: lAsync,
                          parameters: 'json='+Object.toJSON(objParam),
                          onComplete: function(objAjax) {
                             
                                        var evlJS    = jsRetorno+'( objAjax );';
                                        return mRetornoAjax = eval( evlJS );
                                        
                                    }
                         }
                        );

  return mRetornoAjax;

}

function js_validaData() {

  if ($F('dFim') == '') {

    alert('A data final deve ser preenchida.');
    return false;

  }
  
  if ($F('dIni') != '' && $F('dFim') != '') {

    var dIni = new Date($('dIni').value.substring(6,10),
                        $('dIni').value.substring(3,5),
                        $('dIni').value.substring(0,2)
                       );
    var dFim = new Date($('dFim').value.substring(6,10),
                        $('dFim').value.substring(3,5),
                        $('dFim').value.substring(0,2)
                       );
    
    if (dIni > dFim) {
  
      alert('A data de início não pode ser maior que a data final.');
      $('dFim').value = '';
      $('dFim').focus();
  
      return false;
  
    }

  }

  return true;

}

function js_mandaDados() {

  if (!js_validaData()) {
    return false;
  }

  if ($F('iCgs') == '') {

    alert('CGS não informado.');
    return false;

  }

  sGet = 'dIni='+$F('dIni')+'&dFim='+$F('dFim')+'&cgs='+$F('iCgs');

  oJan = window.open('sau4_prontuariomedico003.php?'+sGet, '', 'width='+(screen.availWidth - 5)+
                     ',height='+(screen.availHeight - 40)+',scrollbars=1,location=0 '
                    );
  oJan.moveTo(0, 0);

}

function js_getCgsFaa() {

  if ($F('iFaa') == '') {
    alert('Nenhuma FAA informada.');
  }

  var oParam             = new Object();
  oParam.exec            = "getCgsFaa";
  oParam.iFaa            = $F('iFaa');
  
  js_ajax(oParam, 'js_retornoGetCgsFaa', 'sau4_sau_encaminhamentos.RPC.php');

}

function js_retornoGetCgsFaa(objRetorno) {

  oRetorno = eval("("+objRetorno.responseText+")");
  if (oRetorno.iStatus == 1) {
    $('iCgs').value = oRetorno.iCgs;
  } else {
    alert('Nenhum CGS encontrado!');
  }

}

</script>

</body>
</html>