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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");

require_once("classes/db_db_depart_classe.php");
$clDBDepart    = new cl_db_depart();

require_once("classes/db_db_usuarios_classe.php");
$clDBUsuarios  = new cl_db_usuarios();

require_once("classes/db_ouvidor_classe.php");
$clOuvidor     = new cl_ouvidor();


$sSqlOuvidor       = $clOuvidor->sql_query_file(null,"*",null," ov21_db_usuario = ".db_getsession('DB_id_usuario'));
$rsVerificaOuvidor = $clOuvidor->sql_record($sSqlOuvidor);

if ( $clOuvidor->numrows > 0 ) {
	$lOuvidor = true;
} else {
	$lOuvidor = false;
}

$usuario = db_getsession('DB_id_usuario');
$depto   = db_getsession('DB_coddepto');

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
<style>
#depto, #usuario {
  width: 60px;
}
#deptodescr, #usuariodescr {
  width: 80%;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <form  name="form1"  action="">
	  <table align="center" style="padding-top:20px;">
  		<tr>
	    	<td>
	    	  <fieldset>
	    	    <legend>
	    	      <b>Controle de Atendimento</b>
	    	    </legend>
	    	    <table>
	    	      <?
	    	        if ( $lOuvidor ) {
	    	      ?>
              <tr>
                <td>
                  <b>Departamento:</b>
                </td>
                <td>
                  <?
                    $sWhereDepart  = "     limite is null                                         "; 
                    $sWhereDepart .= "  or limite > '".date('Y-m-d',db_getsession('DB_datausu'))."'";
                    $rsDepart = $clDBDepart->sql_record($clDBDepart->sql_query_file(null,"*","descrdepto",$sWhereDepart));
                    db_selectrecord('depto',$rsDepart,true,1,'');
                  ?>
                </td>
              </tr>	    	    
              <tr>
                <td>
                  <b>Usuários:</b>
                </td>
                <td>
                  <?
                    $sWhereUsusarios  = " usuarioativo = 1 "; 
                    $rsUsuarios = $clDBUsuarios->sql_record($clDBUsuarios->sql_query_file(null,"*",null,$sWhereUsusarios));
                    db_selectrecord('usuario',$rsUsuarios,true,1,'');
                  ?>
                </td>
              </tr>            
              <?
	    	        } else {
	    	        	db_input('usuario',10,'',true,'hidden',1,'');
	    	        	db_input('depto'  ,10,'',true,'hidden',1,'');
	    	        }
              ?>  
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
              <td nowrap="nowrap">
                <?
                  db_ancora('<b>Atendimento:</b>', ' js_pesquisaNumeroAtendimento(true); ', '');
                ?>
              </td>
              <td nowrap="nowrap">
                <?
                  db_input('ov01_numero',     10, "", true, 'text', 1, ' onchange="js_pesquisaNumeroAtendimento(false); "');
                  db_input('ov01_requerente', 40,  0, true, 'text', 3, '');
                  db_input('ov01_anousu',      5, "", true, 'hidden', 1, '');
                ?>
              </td>
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
	    	    </table>
	    	  </fieldset>
        </td>
      </tr>
      <tr>
        <td align="center">
          <input type="button" id="pesquisar" value="Pesquisar" onClick="js_pesquisar();"/>
          <input type="button" id="relatorio" value="Relatório" onClick="js_imprimeRelatorio();" disabled/>
        </td>
      </tr>
	  </table>
	  <center>
    <fieldset style="width: 1024px;">
      <legend>
        <b>Lista Processos</b>
      </legend>
      <div id="listaProcessos"></div>
      <input type="hidden" name="aObjProcessos" id="aObjProcessos" value=""/>
    </fieldset>
    </center>
  </form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

  var sUrl = 'ouv1_controleatendimento.RPC.php';
   
  var oDBGridListaProcessos = new DBGrid('Processos');
  oDBGridListaProcessos.nameInstance = 'oDBGridListaProcessos';
  oDBGridListaProcessos.setCellWidth(new Array('10%', '10%', '25%', '20%', '20%', '10%'));
  oDBGridListaProcessos.setHeader(new Array('Processo', 'Atendimento', 'Requerente', 
		                                        'Tipo', 'Depto Atual', 'Despacho'));
  oDBGridListaProcessos.setHeight(200);
  oDBGridListaProcessos.setCellAlign(new Array('center', 'center', 'left', 
		                                           'left', 'left', 'center'));
  oDBGridListaProcessos.show($('listaProcessos'));
  
  function js_pesquisar() {

    js_divCarregando('Aguarde...','msgBox');

    var sQuery  = 'sMethod=consultaProcessos';
        sQuery += '&iProcIni='+$F('procini');
        sQuery += '&iProcFin='+$F('procfin');
        sQuery += '&sAtendimento='+encodeURIComponent(tagString($F('ov01_numero')));
        sQuery += '&dtDataIni='+$F('dataini');
        sQuery += '&dtDataFin='+$F('datafin');
        sQuery += '&iProcTipo='+$F('proctipo');
        sQuery += '&iDepto='+$F('depto');
        sQuery += '&iUsuario='+$F('usuario');

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
    $('aObjProcessos').value = Object.toJSON(aRetorno.aListaProcessos);
    
    if ( aRetorno.lErro ) {
      $('relatorio').disabled = true;
      alert(aRetorno.sMsg.urlDecode());
      return false;
    } else {
      $('relatorio').disabled = false;
      js_montaGridProcessos(aRetorno.aListaProcessos);
    }
        
  }
  

  function js_montaGridProcessos(aListaProcessos){
  
    oDBGridListaProcessos.clearAll(true);
    var iNumRows = aListaProcessos.length;
    
    if( iNumRows > 0 ){
      aListaProcessos.each(
        function (oProcesso,iInd){
          var aRow = new Array();
          aRow[0]  = oProcesso.p58_codproc;
          aRow[1]  = oProcesso.ov01_anousu.urlDecode();
          aRow[2]  = oProcesso.p58_requer.urlDecode();
          aRow[3]  = oProcesso.p58_codigo.urlDecode();
          aRow[4]  = oProcesso.p61_coddepto.urlDecode();
          aRow[5]  = "<input type='button' value='Consulta' onClick='js_consultaProcesso("+oProcesso.p58_codproc+","+eval(oProcesso.recebido)+");'/>";
          oDBGridListaProcessos.addRow(aRow);
        }
      );
    }
      
    oDBGridListaProcessos.renderRows();
    
  }
  
  
  function js_consultaProcesso(iCodProcesso,lRecebido){
    js_OpenJanelaIframe('top.corpo','db_iframe_detalhes','ouv1_detalhesdespacho001.php?iCodProcesso='+iCodProcesso+'&lRecebido='+lRecebido+'&iDepto='+$F('depto'),'Detalhes do Processo',true);    
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
  
  function js_imprimeRelatorio(){
    js_OpenJanelaIframe('','db_iframe_rel','ouv1_controleatendlistaprocrel002.php?aObjProcessos='+$F('aObjProcessos'),'Relatório de Processo',false);
  }

  /**
   * Efetua a pesquisa de número de atendimento.
   */
  function js_pesquisaNumeroAtendimento(mostra) {
   
    if (document.getElementById('ov01_numero').value == '' && mostra == false) {
       
      $('ov01_numero').value      = '';
      $('ov01_requerente').value = '';
    } else {
      if (mostra == true) {
       
        var sUrlLookUp = 'func_ouvidoriaatendimento.php?funcao_js=parent.js_mostraNumeroAtendimento|ov01_numero|ov01_requerente|true|ov01_anousu';
        js_OpenJanelaIframe('', 'db_iframe', sUrlLookUp, 'Pesquisa Número Atendimento', true);
      } else {
       
        var sValorPesquisa = $('ov01_numero').value;
        var sUrlLookUp     = 'func_ouvidoriaatendimento.php?requer=1&pesquisa_chave='+sValorPesquisa+'&funcao_js=parent.js_mostraNumeroAtendimento';
        js_OpenJanelaIframe('', 'db_iframe', sUrlLookUp, 'Pesquisa Número Atendimento', false);
      }
    }
  }
   
  /**
   * Insere no formulário o retorno da pesquisa de numero de atendimento.
   */
  function js_mostraNumeroAtendimento() { // tem que buscar qual o parâmetro correto pra esse método

    if (arguments[1] === true) {
      
      $('ov01_numero').value     = '';
      $('ov01_requerente').value = arguments[0];
    } else {
      
      $('ov01_numero').value     = arguments[0];
      $('ov01_requerente').value = arguments[1];
      $('ov01_anousu').value     = arguments[3];
    }
    db_iframe.hide();
  }

 
</script>
