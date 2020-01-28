<?
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

$rsDadosProcesso  = $clProtProcesso->sql_record($clProtProcesso->sql_query($oGet->iCodProcesso));
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1;js_consultaAtendimentos();" >
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
                  <b>Usuário :</b>
                </td>
                <td colspan="3">
                  <?
                    db_input('p58_id_usuario'  ,10,'',true,'text',3,'');
                    db_input('nome',50,'',true,'text',3,'');
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
	                  db_textarea('p58_despacho',5,61,'',true,'text',3);
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
		      <input type="button" id="arquivar" name="arquivar" value="Arquivar Processo" onClick="js_arquivarProcesso();"/>
		      <input type="button" id="voltar"   name="voltar"   value="Voltar"            onClick="js_voltar();"/>
		    </td>
		  </tr>
		  <tr>
		    <td>
		      <div id="listaAtendimentos"></div>
		    </td>
		  </tr>
		</table>
	</form>
</body>
</html>
<script>

  var sUrl = 'ouv1_retornocliente.RPC.php';
   
  var oDBGridListaAtendimentos = new DBGrid('Despachos');
  oDBGridListaAtendimentos.nameInstance = 'oDBGridListaAtendimentos';
  oDBGridListaAtendimentos.setHeader( new Array('Número','Requerente','Situação','Confirmação','Ação') );
  oDBGridListaAtendimentos.setHeight(150);
  oDBGridListaAtendimentos.setCellAlign(new Array('center','left','center','center','center'));
  oDBGridListaAtendimentos.show($('listaAtendimentos'));
  
  function js_consultaAtendimentos(){
  
    js_divCarregando('Aguarde...','msgBox');
    var sQuery  = 'sMethod=consultaAtendimentos';
        sQuery += '&iCodProcesso='+$F('p58_codproc');
    
    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoDadosAtendimentos
                                          }
                                  );  
  }
  
    
  function js_retornoDadosAtendimentos(oAjax){
  
    js_removeObj("msgBox");
    var aRetorno = eval("("+oAjax.responseText+")");

    $('despachos').value = Object.toJSON(aRetorno.aListaAtendimentos);
        
    if ( aRetorno.lErro ) {
      alert(aRetorno.sMsg.urlDecode());
      return false;
    } else {
      js_montaGridAtendimentos(aRetorno.aListaAtendimentos);
    }
        
  }
  

  function js_montaGridAtendimentos(aListaAtendimentos){
  
    oDBGridListaAtendimentos.clearAll(true);
    
    var iNumRows = aListaAtendimentos.length;
    if( iNumRows > 0 ){
      aListaAtendimentos.each(
        function (oAtendimento,iInd){
          with(oAtendimento){
          
            if ( eval(tiporetorno) ) {
            
              var sDisabled = "";
              if ( confirmacao == 'Sim' ) {
                var sClass = "";
              } else {
                var sClass = "class='semRetorno'";
              }
              
            } else {
              var sDisabled = "disabled";
              var sClass    = "";
            }
            
	          var aRow = new Array();
	          aRow[0]  = ov01_numero.urlDecode();
	          aRow[1]  = ov01_requerente.urlDecode();
	          aRow[2]  = situacao.urlDecode();
	          aRow[3]  = confirmacao.urlDecode();
	          aRow[4]  = "<input type='button' value='Retorno' "+sClass+" onClick='js_retornoAtendimento("+ov01_sequencial+");' "+sDisabled+" />";
	          oDBGridListaAtendimentos.addRow(aRow);
          }
        }
      );
    }
      
    oDBGridListaAtendimentos.renderRows();
    
  }

  function js_retornoAtendimento(iCodAtendimento){
    js_OpenJanelaIframe('','db_iframe_retorno','ouv1_retornocidadao003.php?iCodAtendimento='+iCodAtendimento,'Retorno Atendimento',true);    
  }
 
 
  function js_arquivarProcesso(){
  
    if ( !js_validaAtendimentos() ) {
      return false;
    }
    
    js_divCarregando('Aguarde...','msgBox');
  
    var sQuery  = 'sMethod=arquivarProcesso';
        sQuery += '&iCodProcesso='+$F('p58_codproc');
    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoArquivarProcesso
                                          }
                                  );  
  
  
  }
  

  function js_retornoArquivarProcesso(oAjax){
  
    js_removeObj("msgBox");
  
    var aRetorno = eval("("+oAjax.responseText+")");
    var sExpReg  = new RegExp('\\\\n','g');
    
    alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
  
    if ( aRetorno.lErro ) {
      return false;
    } else {
      parent.js_pesquisar();
      js_voltar();
    }
        
  }


  function js_validaAtendimentos(){
    
    var aAtendimentos = $$('input.semRetorno');
    
    if ( aAtendimentos.length > 0 ) {
      alert('Existem atendimentos sem retorno ou não confirmados!');
      return false;
    } else {     
      return true;
    }
     
  }

  function js_voltar() {
     parent.db_iframe_detalhes.hide();
  }

</script>