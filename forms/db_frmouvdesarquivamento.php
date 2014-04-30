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

$clprotprocesso->rotulo->label("p58_codproc");
$clprotprocesso->rotulo->label("p58_requer");
$clprotprocesso->rotulo->label("p58_obs");

$cltipoproc->rotulo->label("p51_descr");
$clprocarquiv->rotulo->label("p67_dtarq");
$clouvidoriaatendimento->rotulo->label("ov01_tipoprocesso");
?>
<form id="form1" name="form1" method="post" action="">
<fieldset>
  <legend><b>Desarquivamento de Processos</b></legend>
  <table border="0" align="center">
    <tr>
      <td title="<?=$Tp58_codproc?>">
        <?=$Lp58_codproc?>
      </td>
      <td title="<?=$Tp58_codproc?>">
        <?
          db_input("p58_codproc",10,$Ip58_codproc,true,"text",3,"");
        ?>
      </td>
      <td title="<?=$Tp58_requer?>">
        <?
          db_input("p58_requer",50,$Ip58_requer,true,"text",3,"");
        ?>
      </td>
    </tr>
    <tr>
      <td title="<?=$Tov01_tipoprocesso?>">
        <?=$Lov01_tipoprocesso?>
      </td>
      <td title="<?=$Tp58_codproc?>">
        <?
          db_input("ov01_tipoprocesso",10,$Iov01_tipoprocesso,true,"text",3,"");
        ?>
      </td>
      <td title="<?=$Tp51_descr?>">
        <?
          db_input("p51_descr",50,$Ip51_descr,true,"text",3,"");
        ?>
      </td>
    </tr>
	  <tr>
      <td title="<?=$Tp67_dtarq?>">
        <?=$Lp67_dtarq?>
      </td>
	    <td colspan="2" title="<?=$Tp67_dtarq?>">
	      <?
	        db_inputdata('p67_dtarq','','','',true,'text',3);
	      ?>
	    </td> 
	  </tr>
		<tr align="center">
		   <td colspan="3">  
		     <fieldset>
		       <legend>
		         <b>Observação</b>
		       </legend>
		       <?
		         db_textarea('p58_obs',4,78,$Ip58_obs,true,'text',3,'');
		       ?>
		     </fieldset>
		   </td>
		</tr>
  </table>
</fieldset>
<table align="center">
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>
      <input id="processar" name="processar" type="button" value="Desarquivar" 
             onclick="return js_desarquivarDados();" disabled>
    </td>
    <td>
      <input id="pesquisar" name="pesquisar" type="button" value="Pesquisar" onclick="js_pesquisaProcessoArquivado();">
    </td>
  </tr>
</table>
</form>
<script>
function js_pesquisaProcessoArquivado() {
  
  var sCampos = '|p67_codproc|p58_requer|ov01_tipoprocesso|p51_descr|p67_dtarq|p58_obs';
  var sParam  = 'funcao_js=parent.js_mostraProcessoArquivado1'+sCampos;
  var sUrl    = 'func_ouvidoriaprocessoarquivado.php?'+sParam;
  js_OpenJanelaIframe('top.corpo','db_iframe_processoarquivado',sUrl,'Pesquisa',true); 
}
  
function js_mostraProcessoArquivado1(iCodProc,sDescricao,iTipoProc,sTipoProcDescr,dtArq,sObservacao) {

  $('p58_codproc').value       = iCodProc;
  $('p58_requer').value        = sDescricao;
  $('ov01_tipoprocesso').value = iTipoProc;
  $('p51_descr').value         = sTipoProcDescr;
  $('p67_dtarq').value         = js_formatar(dtArq,"d");
  $('p58_obs').value           = sObservacao; 
  $('processar').disabled      = false;
  db_iframe_processoarquivado.hide();
}

/**
 * Desarquiva processos de ouvidoria por departamento
 */
function js_desarquivarDados(){
   
  var iCodProc       = $('p58_codproc').value;
  var sDescricao     = $('p58_requer').value;
  var iTipoProc      = $('ov01_tipoprocesso').value;
  var sTipoProcDescr = $('p51_descr').value;
  var dtArq          = $('p67_dtarq').value;
  var sObservacao    = $('p58_obs').value; 

  if (iCodProc == '') {

    alert('Nenhuma Processo Informado!');
    return false;  
  }

  js_divCarregando('Aguarde Processando...','msgBoxProcessar');
   
  var oParam           = new Object();
      oParam.exec      = 'Desarquivar';
      oParam.processo  = iCodProc;
    
  var sUrl    = 'ouv4_desarqprocesso.RPC.php';
  var oAjax   = new Ajax.Request( sUrl, {
                                          method: 'post', 
                                          parameters: 'json='+js_objectToJson(oParam), 
                                          onComplete: js_retornoProcessarDados
                                        }
                                ); 
}

/**
 * Retorno desarquivamento de processos de ouvidoria
 */
function js_retornoProcessarDados(oAjax) {

  js_removeObj("msgBoxProcessar");
 
  var oRetorno = eval("("+oAjax.responseText+")");
  
  if (oRetorno.status == 2) {
  
    alert(oRetorno.message.urlDecode());
  } else {
    alert("Processo desarquivado com sucesso.");
  }

  $('p58_codproc').value       = '';
  $('p58_requer').value        = '';
  $('ov01_tipoprocesso').value = '';
  $('p51_descr').value         = '';
  $('p67_dtarq').value         = '';
  $('p58_obs').value           = ''; 
  $('processar').disabled      = true;
  
  js_pesquisaProcessoArquivado();

}
</script>