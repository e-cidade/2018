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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include ("libs/db_app.utils.php");

$oGet = db_utils::postMemory($_GET);

require_once("classes/db_protprocesso_classe.php");
$clProtProcesso = new cl_protprocesso();

$sCamposProcesso  = "protprocesso.*,   ";
$sCamposProcesso .= "p51_descr,        ";
$sCamposProcesso .= "z01_nome,         ";
$sCamposProcesso .= "d.descrdepto,     ";
$sCamposProcesso .= "c.nome as ouvidor ";

$rsDadosProcesso  = $clProtProcesso->sql_record($clProtProcesso->sql_query_transand($oGet->iCodProcesso,$sCamposProcesso));
db_fieldsmemory($rsDadosProcesso,0);


require_once("classes/db_ouvidoriaatendimentolocal_classe.php");
$clOuvidoriaAtendimentoLocal = new cl_ouvidoriaatendimentolocal();

$sCamposLocal = "ov25_sequencial,ov25_descricao";
$sWhereLocal  = " ov09_protprocesso = {$oGet->iCodProcesso} ";
$rsDadosLocal = $clOuvidoriaAtendimentoLocal->sql_record($clOuvidoriaAtendimentoLocal->sql_query_prot(null,$sCamposLocal,null,$sWhereLocal));
if ( $clOuvidoriaAtendimentoLocal->numrows > 0 ) {
	db_fieldsmemory($rsDadosLocal,0);
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<? 
  db_app::load('strings.js');
  db_app::load('scripts.js');
  db_app::load('datagrid.widget.js');
  db_app::load('prototype.js');
  db_app::load('estilos.css');
  db_app::load('grid.style.css');
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1;js_consultaDespachos();" >
	<form name="form1" action=""> 
		<table align="center" >
		  <tr>  
		    <td>
		      <fieldset>
		        <legend>
		          <b>Dados do Processo</b>
		        </legend>
			      <table>
			        <tr>
			          <td>
		              <b>Processo:</b>
			          </td>
			          <td>
			            <?
	                  db_input('p58_codproc',10,'',true,'text',3,'');
			            ?>
			          </td>
	              <td align="right">
	                <b>Ouvidor:</b>
	              </td>
	              <td align="left">
	                <?
	                  db_input('ouvidor',37,'',true,'text',3,'');
	                ?>
	              </td>		          
			        </tr>
	            <tr>
	              <td>
	                <b>Data do Processo:</b>
	              </td>
	              <td>
	                <?
	                  
	                  if ( trim($p58_dtproc) != '' ) {
		                  $aDataProc = explode('-',$p58_dtproc);
		                  $iDia = $aDataProc[2];
		                  $iMes = $aDataProc[1];
		                  $iAno = $aDataProc[0];
	                  }
	                  
	                  db_inputdata('p58_dtproc',@$iDia,@$iMes,@$iAno,true,'text',3,'');
	                ?>
	              </td>
	              <td align="right">
	                <b>Hora Inclusão:</b>
	              </td>
	              <td align="left">
	                <?
	                  db_input('p58_hora',10,'',true,'text',3,'');
	                ?>
	              </td>             
	            </tr>
	            <tr>
	              <td>
	                <b>Departamento Inicial:</b>
	              </td>
	              <td colspan="3">
	                <?
	                  db_input('p58_coddepto'  ,10,'',true,'text',3,'');
	                  db_input('descrdepto',50,'',true,'text',3,'');
	                ?>
	              </td>
	            </tr>            
	            <tr>
	              <td>
	                <b>Tipo de Processo:</b>
	              </td>
	              <td colspan="3">
	                <?
	                  db_input('p58_codigo',10,'',true,'text',3,'');
	                  db_input('p51_descr' ,50,'',true,'text',3,'');
	                ?>
	              </td>
	            </tr>                       		        
	            <tr>
	              <td>
	                <b>Titular de Processo:</b>
	              </td>
	              <td colspan="3">
	                <?
	                  db_input('p58_numcgm',10,'',true,'text',3,'');
	                  db_input('z01_nome'  ,50,'',true,'text',3,'');
	                ?>
	              </td>
	            </tr>
	            <tr>
	              <td>
	                <b>Requerente:</b>
	              </td>
	              <td colspan="3">
	                <?
	                  db_input('p58_requer',63,'',true,'text',3,'');
	                ?>
	              </td>
	            </tr>                       
              <tr>
                <td>
                  <b>Local:</b>
                </td>
                <td colspan="3">
                  <?
                    db_input('ov25_sequencial',10,'',true,'text',3,'');
                    db_input('ov25_descricao',50,'',true,'text',3,'');
                  ?>
                </td>
              </tr>	            
	            <tr>
	              <td>
	                <b>Solicitação:</b>
	              </td>
	              <td colspan="3">
	                <?
	                  db_textarea('p58_obs',5,61,'',true,'text',3);
	                  db_input('despachos',10,'',true,'hidden',3,'');
	                ?>
	              </td>
	            </tr>             
			      </table>
		      </fieldset>
		  	</td>
		  </tr>
		  <tr>
		    <td align="center">
		      <input type="button" id="imprimir" name="imprimir" value="Imprimir Despachos" onClick="js_imprimirDespachos();" disabled />
		    </td>
		  </tr>
		  <tr>
		    <td>
		      <div id="listaDespachos"></div>
		    </td>
		  </tr>
		</table>
	</form>
</body>
</html>
<script>

  var sUrl = 'ouv1_controleatendimento.RPC.php';
   
  var oDBGridListaDespachos = new DBGrid('Despachos');
  oDBGridListaDespachos.nameInstance = 'oDBGridListaDespachos';
  oDBGridListaDespachos.setHeader( new Array('Data','Hora','Depto','Usuário Envolvido','Despacho') );
  oDBGridListaDespachos.setHeight(200);
  oDBGridListaDespachos.setCellAlign(new Array('center','center','left','left','Left'));
  oDBGridListaDespachos.show($('listaDespachos'));
  
  function js_consultaDespachos(){
  
    js_divCarregando('Aguarde...','msgBox');
    var sQuery  = 'sMethod=consultaDespachos';
        sQuery += '&iCodProcesso='+$F('p58_codproc');
    
    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoDadosDespachos
                                          }
                                  );  
  }
  
    
  function js_retornoDadosDespachos(oAjax){
  
    js_removeObj("msgBox");
    var aRetorno = eval("("+oAjax.responseText+")");

    $('despachos').value = Object.toJSON(aRetorno.aListaDespachos);
        
    if ( aRetorno.lErro ) {
      $('imprimir').disabled = true; 
      alert(aRetorno.sMsg.urlDecode());
      return false;
    } else {
      $('imprimir').disabled = false;
      js_montaGridDespachos(aRetorno.aListaDespachos);
    }
        
  }
  

  function js_montaGridDespachos(aListaDespachos){
  
    oDBGridListaDespachos.clearAll(true);
    
    var iNumRows = aListaDespachos.length;
    if( iNumRows > 0 ){
      aListaDespachos.each(
        function (oDespacho,iInd){
          var aRow = new Array();
          aRow[0]  = js_formatar(oDespacho.data,'d');
          aRow[1]  = oDespacho.hora.urlDecode();
          aRow[2]  = oDespacho.descrdepto.urlDecode();
          aRow[3]  = oDespacho.nome.urlDecode();
          aRow[4]  = oDespacho.despacho.urlDecode();
          oDBGridListaDespachos.addRow(aRow);
        }
      );
    }
      
    oDBGridListaDespachos.renderRows();
    
  }

  function js_imprimirDespachos(){
    js_OpenJanelaIframe('','db_iframe_rel','ouv1_controleatendlistadesprel002.php?aObjDespachos='+$F('despachos')+'&iCodProc='+$F('p58_codproc'),'Relatório de Processo',false);
  }


</script>