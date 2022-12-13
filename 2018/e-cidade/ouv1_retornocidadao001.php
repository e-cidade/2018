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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include ("libs/db_app.utils.php");

require_once("classes/db_tiporetorno_classe.php");
$clTipoRetorno = new cl_tiporetorno();


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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <form  name="form1"  action="">
	  <table align="center" style="padding-top:20px;" width="780px">
  		<tr>
	    	<td>
	    	  <fieldset>
	    	    <legend>
	    	      <b>Retorno ao Cidadão</b>
	    	    </legend>
	    	    <table>
	    	      <tr>
	    	        <td>
	    	          <?
                    db_ancora('<b>Código do Processo:</b>','js_pesquisaProcessoIni();',1,'');
	    	          ?>
	    	        </td>
	    	        <td>
	    	          <?
                    db_input('procini',10,'',true,'text',1,'');
	    	            db_ancora('<b>à</b>','js_pesquisaProcessoFin();',1,'');
	    	            db_input('procfin',10,'',true,'text',1,'');
	    	          ?>
	    	        </td>
	    	      </tr>
              <tr>
                <td>
                  <b>Período:</b>
                </td>
                <td>
                  <?
                    db_inputdata('dataini','','','',true,'text',1,'');
                    echo"<b>à</b>";
                    db_inputdata('datafin','','','',true,'text',1,'');
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?
                    db_ancora('<b>Tipo de Processo:</b>','js_pesquisaTipoProcesso(true);',1,'');
                  ?>
                </td>
                <td>
                  <?
                    db_input('proctipo' ,10,'',true,'text',1,"onChange='js_pesquisaTipoProcesso(false);'");
                    db_input('descrtipo',40,'',true,'text',3,'');
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <b>Tipo de Retorno:</b>
                </td>
                <td>
                  <?
                    $aTipoRetorno = array(0=>"Todos",
											                    1=>"Pessoalmente",
											                    2=>"Carta",
											                    3=>"Email",
											                    4=>"Telefone/Fax",
											                    5=>"Sem Retorno");

                    db_select('tiporetorno',$aTipoRetorno,true,1,'');											                    
                  ?>
                </td>
              </tr>                             	    	      
	    	      
	    	      <!-- Ancora para Atendimento  -->
	    	      <tr>
                <td>
                  <?
                  db_ancora('<b>Número do Atendimento:</b>', ' js_pesquisaNumeroAtendimento(true); ', '');
                  ?>
                </td>
                <td>
                  <?
                  db_input('ov01_numero', 10, "", true, 'text',1, ' onchange="js_pesquisaNumeroAtendimento(false); "');
                  db_input('ov01_ano', 10, "", true, 'hidden');
                  db_input('ov01_solicitacao', 50, 0, true, 'text', 3, '');
                  ?>
                </td>
              </tr>
	    	      
	    	    </table>
	    	  </fieldset>
        </td>
      </tr>
      <tr>
        <td align="center">
          <input type="button" id="pesquisar" value="Pesquisar" onClick="js_pesquisar();"/>
        </td>
      </tr>
      <tr>
        <td>  
          <fieldset>
            <legend>
              <b>Lista Processos</b>
            </legend>
            <div id="listaProcessos"></div>
          </fieldset>
        </td>
      </tr>      
	  </table>
  </form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

  var sUrl = 'ouv1_retornocliente.RPC.php';
   
  var oDBGridListaProcessos = new DBGrid('Processos');
  oDBGridListaProcessos.nameInstance = 'oDBGridListaProcessos';
  oDBGridListaProcessos.setHeader(new Array('Processo', 'Atendimento', 'Requerente','Tipo','Data Processo','Retorno'));
  oDBGridListaProcessos.setHeight(200);
  oDBGridListaProcessos.setCellAlign(new Array('center', 'center','left','left','center','center'));
  oDBGridListaProcessos.show($('listaProcessos'));
  
  function js_pesquisar(){

    js_divCarregando('Aguarde...','msgBox');
   
    var sQuery  = 'sMethod=consultaProcessos';
        sQuery += '&iProcIni='+$F('procini');
        sQuery += '&iProcFin='+$F('procfin');
        sQuery += '&dtDataIni='+$F('dataini');
        sQuery += '&dtDataFin='+$F('datafin');
        sQuery += '&iProcTipo='+$F('proctipo');
        sQuery += '&iTipoRetorno='+$F('tiporetorno');
        sQuery += '&iNumeroAtendimento='+$F('ov01_numero');
        sQuery += '&iAnoAtendimento='+$F('ov01_ano');
        
    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoDadosProcessos
                                          }
                                  );  
  }
  
    
  function js_retornoDadosProcessos(oAjax){
  
    js_removeObj("msgBox");
    var aRetorno = eval("("+oAjax.responseText+")");
    
    oDBGridListaProcessos.clearAll(true);
    
    if ( aRetorno.lErro ) {
      alert(aRetorno.sMsg.urlDecode());
      return false;
    } else {
      js_montaGridProcessos(aRetorno.aListaProcessos);
    }
        
  }
  

  function js_montaGridProcessos(aListaProcessos){
  
    oDBGridListaProcessos.clearAll(true);
    var iNumRows = aListaProcessos.length;
    
    if( iNumRows > 0 ){
      aListaProcessos.each(
        function (oProcesso,iInd){
          with(oProcesso){
	          var aRow = new Array();
	          aRow[0]  = p58_codproc;
	          aRow[1]  = ov01_numero+"/"+ov01_anousu;
	          aRow[2]  = p58_requer.urlDecode();
	          aRow[3]  = p58_codigo+'-'+p51_descr.urlDecode();
	          aRow[4]  = js_formatar(p58_dtproc,'d');
	          aRow[5]  = "<input type='button' value='Consulta' onClick='js_consultaProcesso("+p58_codproc+");'/>";
	          oDBGridListaProcessos.addRow(aRow);
          }
        }
      );
    }
    oDBGridListaProcessos.renderRows();
  }
  
  
  function js_consultaProcesso(iCodProcesso){
    js_OpenJanelaIframe('top.corpo','db_iframe_detalhes','ouv1_retornocidadao002.php?iCodProcesso='+iCodProcesso,'Detalhes do Processo',true);    
  }
  
  function js_pesquisaProcessoIni(){
    js_OpenJanelaIframe('top.corpo','db_iframe_processoIni','func_protprocesso.php?grupo=2&funcao_js=parent.js_mostraProcessoIni|p58_codproc','Processos',true);
  }

  function js_mostraProcessoIni(iCodProc){
    document.form1.procini.value = iCodProc;
    db_iframe_processoIni.hide();
  }
  
  function js_pesquisaProcessoFin(){
    js_OpenJanelaIframe('top.corpo','db_iframe_processoFin','func_protprocesso.php?grupo=2&funcao_js=parent.js_mostraProcessoFin|p58_codproc','Processos',true);
  }

  function js_mostraProcessoFin(iCodProc){
    document.form1.procfin.value = iCodProc;
    db_iframe_processoFin.hide();
  }  
  
  function js_pesquisaTipoProcesso( lMostra ){
    
    if( lMostra ){
      js_OpenJanelaIframe('top.corpo','db_iframe_tipoproc','func_tipoproc.php?grupo=2&funcao_js=parent.js_mostraTipoProcesso1|p51_codigo|p51_descr','Tipo de Processo',true);
    }else{
       if( $F('proctipo') != '' ){ 
         js_OpenJanelaIframe('top.corpo','db_iframe_tipoproc','func_tipoproc.php?grupo=2&pesquisa_chave='+$F('proctipo')+'&funcao_js=parent.js_mostraTipoProcesso','Tipo de Processo',false);
       }else{
         document.form1.descrtipo.value = ''; 
       }
    }
    
  }
  
  function js_mostraTipoProcesso(chave,lErro){
    
    document.form1.descrtipo.value = chave;
     
    if( lErro ){ 
      document.form1.proctipo.focus(); 
      document.form1.proctipo.value = '';
      return false; 
    }
    
  }
  
  function js_mostraTipoProcesso1(chave1,chave2){
    document.form1.proctipo.value  = chave1;
    document.form1.descrtipo.value = chave2;
    db_iframe_tipoproc.hide();
  }

  
  /**
   * Efetua a pesquisa de número de atendimento.
   */
  function js_pesquisaNumeroAtendimento(lMostra) {
   
    if ($('ov01_numero').value == '' && lMostra == false) {
     
      $('ov01_numero').value      = '';
      $('ov01_ano').value         = '';
      $('ov01_solicitacao').value = '';
      
    } else {
      
      if (lMostra == true) {
        
        var sUrlLookUp = 'func_ouvidoriaatendimento.php?funcao_js=parent.js_mostraNumeroAtendimento|ov01_numero|ov01_anousu|ov01_requerente';
      } else {
       
        var sValorPesquisa = document.getElementById('ov01_numero').value;
        var sUrlLookUp     = 'func_ouvidoriaatendimento.php?requer=1&pesquisa_chave='+sValorPesquisa+'&funcao_js=parent.js_preencheNumeroAtendimento';
      }
      js_OpenJanelaIframe('', 'db_iframeouvidoriaatendimento', sUrlLookUp, 'Pesquisa Número Atendimento', lMostra);
    }
  }
   
  /**
   * Insere no formulário o retorno da pesquisa de numero de atendimento.
   */
  function js_mostraNumeroAtendimento(iNumeroAtendimento, iAnoUsu, sRequerente) {

    $('ov01_numero').value      = iNumeroAtendimento;
    $('ov01_ano').value         = iAnoUsu;
    $('ov01_solicitacao').value = sRequerente;
    db_iframeouvidoriaatendimento.hide();
  }

  /**
   * Dependendo da forma de pesquisa (digitando ou abrindo lookup) é enviado os parâmetros de forma diferente.
   * Quando o usuário digita o numero do atendimento, é retornado apenas 2 parâmetros quando ele não encontra
   * o atendimento. Do contrario retorna os 4 parametros
   */
  function js_preencheNumeroAtendimento(iNumeroAtendimento, sRequerente, lErro, iAno) {

    if (sRequerente == true) {
      
      $('ov01_numero').value      = "";
      $('ov01_ano').value         = "";
      $('ov01_solicitacao').value = iNumeroAtendimento;
    } else {

      $('ov01_numero').value      = iNumeroAtendimento;
    	$('ov01_ano').value         = iAno;
    	$('ov01_solicitacao').value = sRequerente;
    }
  }
</script>