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

require_once("model/processoOuvidoria.model.php");
$oProcessoOuvidoria = new processoOuvidoria();


$sCamposProcesso  = "protprocesso.*,";
$sCamposProcesso .= "p51_descr,     ";
$sCamposProcesso .= "z01_nome,      ";
$sCamposProcesso .= "d.descrdepto   ";
$rsDadosProcesso  = $clProtProcesso->sql_record($clProtProcesso->sql_query_transand($oGet->iCodProcesso,$sCamposProcesso));
db_fieldsmemory($rsDadosProcesso,0);

$deptorec = $oProcessoOuvidoria->getProximoDepto($oGet->iCodProcesso,db_getsession('DB_coddepto')); 
 
if ( trim($deptorec) != '' ) {
  $sSqlDepart = "select descrdepto as descrdeptorec 
                   from db_depart
                   where coddepto = {$deptorec}";
  $rsDepart = db_query($sSqlDepart);
  db_fieldsmemory($rsDepart,0);                   	
}


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
<script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1;" >
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
			          <td width="130px;">
		              <b>Processo:</b>
			          </td>
			          <td colspan="3">
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
	                ?>
	              </td>
	            </tr>             
			      </table>
		      </fieldset>
		  	</td>
		  </tr>
		  <tr>
		    <td>
		      <fieldset>
		        <legend>
		          <b>Dados Despacho</b>
		        </legend>
		        <table>
              <tr>
                <td width="130px;">
                  <b>Despacho:</b>
                </td>
                <td>
                  <?
                    db_textarea('despacho',5,61,'',true,'text',1);
                  ?>
                </td>
              </tr>		        
              <tr>
                <td>
                  <b>Despacho Público:</b>
                </td>
                <td>
                  <?
                    $aPublico = array("s"=>"Sim","n"=>"Não"); 
                    db_select('publico',$aPublico,true,1,'');
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <b>Despacho Público:</b>
                </td>
                <td id="tipoDespacho">
                  <input type="radio" name="tipodesp" value="d" onChange="js_validaTelaTransf();" checked><b>Despachar</b></input>
                  <input type="radio" name="tipodesp" value="t" onChange="js_validaTelaTransf();"><b>Despachar e Transferir</b></input>
                </td>
              </tr> 
		        </table>
		      </fieldset>
		    </td>
		  </tr>
      <tr id="telaTransferencia" style="display:none">
        <td>
          <fieldset>
            <legend>
              <b>Transferência de Processo</b>
            </legend>
            <table>
              <tr>
                <td>
                  <b>
                  <?
                    db_ancora('Departamento:','js_mostraDepartamento(true);',1,'');
                  ?>
                  </b>
                </td>
                <td>
                  <?
                    db_input('ordem'        ,10,'',true,'hidden',1,"");
                    db_input('deptorec'     ,10,'',true,'text',1,"onChange='js_mostraDepartamento(false);'");
                    db_input('descrdeptorec',50,'',true,'text',3,'');
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <b>Recebimento:</b>
                </td>
                <td>
                  <select id="selRecebimento" name="selRecebimento" style="width:250px;" disabled>
                  </select>
                </td>
              </tr>               
            </table>
          </fieldset>
        </td>
      </tr>		  
      <tr align="center">
        <td>
          <input type="button" id="incluir" name="incluir" value="Incluir" onClick="js_validaProcesso();"/>
        </td>
      </tr>
		</table>
	</form>
</body>
</html>
<script>
  
  var sUrlRPC = 'ouv1_controleatendimento.RPC.php'; 
  
  function js_validaTelaTransf(){
    
    var sTipoDesp = js_getTipoDespacho();
    
    if ( sTipoDesp == 't' ) {
      $('telaTransferencia').style.display = '';
    } else {
      $('telaTransferencia').style.display = 'none';
    }    
    
  }

  function js_mostraDepartamento(lMostra){
    if( lMostra ){
      js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart_transferencias.php?funcao_js=parent.js_mostradb_depart1|0|1&todasinstit=1','Pesquisa',true);
    } else {
      js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart_transferencias.php?pesquisa_chave='+$F('deptorec')+'&funcao_js=parent.js_mostradb_depart&todasinstit=1&instituicao=0','Pesquisa',false);
    }
  }
  
	function js_mostradb_depart(chave,erro){
	  $('descrdeptorec').value = chave; 
	  if( erro ){ 
	    $('deptorec').focus(); 
	    $('deptorec').value = ''; 
	  } else {
	    js_pesquisaUsuarios($F('deptorec'));
	  }
	}
	
	function js_mostradb_depart1(chave1,chave2){
	  $('deptorec').value      = chave1;
	  $('descrdeptorec').value = chave2;  
	  db_iframe_db_depart.hide();
	  js_pesquisaUsuarios(chave1);
	}  


	function js_pesquisaUsuarios(iCodDepto) {
	
	  if( iCodDepto != "" ){
	  
	    js_divCarregando('Consultando Usuários...','div_processando');
	  
	    var sUrl     = 'pro4_consusuariodeptoRPC.php';
	    var sQuery   = "json={icoddepto:"+iCodDepto+"}";
	    var objAjax  = new Ajax.Request ( sUrl,{ 
	                                             method:'post',
	                                             parameters:sQuery, 
	                                             onComplete:js_carregaUsuariosSelect
	                                           }
	                                    );
	  } else {
	    $('selRecebimento').disabled = true;
	    $('selRecebimento').value    = '0';
	  }
	}
	
	function js_carregaUsuariosSelect(oAjax) {
	
	  var aUsuarios = eval("("+oAjax.responseText+")");
	  
	  if ( aUsuarios.length > 0 ) {
		  for (var iInd = 0; iInd < aUsuarios.length; iInd++) {
		    $('selRecebimento').options[iInd]       = new Option();
		    $('selRecebimento').options[iInd].value = aUsuarios[iInd].id_usuario.urlDecode();
		    $('selRecebimento').options[iInd].text  = aUsuarios[iInd].nome.urlDecode();
		  }
		  $('selRecebimento').disabled = false;
	  } else {
  	  $('selRecebimento').disabled = true;
  	}  
	  
	  js_removeObj('div_processando');
	
	}
  
  
  function js_incluirDespacho(oProcesso,lNovoDepto){
    
    js_desabilitaBotao(false); 
    var sTipoDespacho = js_getTipoDespacho();
     
    if ( $F('despacho').trim() == '' ) {
      alert('Despacho não informado!');
      return false;
    } 
    
    if ( sTipoDespacho == 't' && $F('deptorec').trim() == '' ) {
      alert('Nenhum departamento de recebimento informado!');
      return false;
    }         
    
    js_divCarregando('Aguarde...','msgBox');
    js_desabilitaBotao(true);
         
    var sQuery  = 'sMethod=incluirDespacho';
        sQuery += '&lNovoDepto='+lNovoDepto;
        sQuery += '&oProcesso='+Object.toJSON(oProcesso);
        sQuery += '&sDespacho='+$F('despacho');
        sQuery += '&iCodDeptoRec='+$F('deptorec');
        sQuery += '&iUsuarioRec='+$F('selRecebimento');
        sQuery += '&sTipo='+js_getTipoDespacho();
        sQuery += '&sPublico='+$F('publico');
        sQuery += '&iOrdemProrrogacao='+$F('ordem');
  
    var oAjax   = new Ajax.Request( sUrlRPC, {
                                            method:'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoIncluirDespacho
                                          }
                                  );         
    
  
  }

	function js_retornoIncluirDespacho(oAjax){
	
	  js_removeObj("msgBox");
    js_desabilitaBotao(false);
	  
	  var aRetorno = eval("("+oAjax.responseText+")");
	  var sTipo    = js_getTipoDespacho(); 
	  
    alert(aRetorno.sMsg.urlDecode());
	  
    if ( aRetorno.lErro ) {
      return false;
    } else {
      if ( sTipo == 't' ) {
	      url = "pro4_termorecebimento.php?codtran="+aRetorno.iCodTran;
	      window.open(url,'','location=0');
        parent.js_fechar();
	    } else {
			  parent.iframe_listadespacho.js_consultaDespachos();
        $('despacho').value = '';
	    }
    }
	}
  
 
  function js_getTipoDespacho(){
  
    var aRadio = $$('#tipoDespacho input[type="radio"]');
    var sTipo  = '';
    
    aRadio.each(
      function ( eRadio, iInd ) {
        if ( eRadio.checked ) {
          sTipo = eRadio.value;
        }
      }
    );
      
    return sTipo;
    
  }  
  

	
	function js_telaDiferencas(lTemDepto) {
	  
	  
	  if ( lTemDepto ){
	    sDisabled = '';
	  } else {
	    sDisabled = 'disabled'; 
   	}
   	
	  var sContent  = "<div id='msg' style='border-bottom: 2px groove white;padding:5px;background-color:white;vertical-align:bottom;font-weight:bold;width:98%;height:50px;text-align:left'> ";
	      sContent += "Departamento escolhido difere do andamento padrão, favor digite o número de dias referente ao departamento escolhido.</div>                                              ";
	      sContent += "<table width='100%' style='padding-top:20px;'> ";
	      sContent += "  <tr>                               ";
	      sContent += "    <td>                             "; 
	      sContent += "      <fieldset>                     ";
	      sContent += "        <table>                      ";
	      sContent += "          <tr>                       ";
        sContent += "            <td>                     ";
        sContent += "              <b>Quantidade Dias:</b>";
        sContent += "            </td>                    ";
        sContent += "            <td>                     ";
        sContent += "              <input type='text' name='qtdDias' id='qtdDias' value='' size='10px;'/>";
        sContent += "            </td>                    ";
        sContent += "          </tr>                      ";        
        sContent += "          <tr>                       ";
        sContent += "            <td>                     ";
        sContent += "              <b>Proseguir Andamento:</b>";
        sContent += "            </td>                    ";                        	      
        sContent += "            <td>                     ";
	      sContent += "              <select style='width:95px' id='segueAndamento' "+sDisabled+"> ";
	      sContent += "                <option value='false' >Não</option>                         "; 
	      sContent += "                <option value='true'  >Sim</option>                         ";
	      sContent += "              </select>                                                     ";
	      sContent += "            </td>                    ";
	      sContent += "          </tr>                      ";
        sContent += "          <tr>                       ";
        sContent += "            <td>                     ";
        sContent += "              <b>Motivo:</b>";
        sContent += "            </td>                    ";
        sContent += "            <td>                     ";
        sContent += "              <textarea rows='5' cols='40' name='motivo' id='motivo'></textarea>";
        sContent += "            </td>                    ";
        sContent += "          </tr>                      ";	      
	      sContent += "        </table>                     ";
	      sContent += "      </fieldset>                    ";
	      sContent += "    </td>                            ";
	      sContent += "  </tr>                              ";
	      sContent += "  <tr align='center'>                ";
	      sContent += "    <td>                             ";
	      sContent += "      <input type='button' id='btnIncluir' value='Incluir'/>";
	      sContent += "      <input type='button' id='btnFechar'  value='Fechar'/> ";
	      sContent += "    </td>                            ";
	      sContent += "  </tr>                              ";
	      sContent += "</table>                             ";
	      
	  windowAuxiliarDias  = new windowAux('wnddias', 'Informe a quantidade de dias', 500, 300);
	  windowAuxiliarDias.setContent(sContent);
	  windowAuxiliarDias.show(150,330);
	  
	  $('btnFechar').observe("click",js_fecharJanela);
	  $('btnIncluir').observe("click",js_validaCamposDif);
	  $('window'+windowAuxiliarDias.idWindow+'_btnclose').observe("click",js_fecharJanela);
	 
	}
	
	
	function js_fecharJanela(){
	  js_desabilitaBotao(false);
	  windowAuxiliarDias.destroy();
	} 
	
	
	function js_validaProcesso(){
    
    var sTipoDespacho = js_getTipoDespacho();
    

    if ( sTipoDespacho == 't') {
    
		  if ( $F('deptorec') == '' ) {
		    alert('Departamento de recebimento não informado!');
		    return false;
		  }
		  
	    js_desabilitaBotao(true);
	    js_divCarregando('Aguarde...','msgBox');
		     
		  var sQuery  = 'sMethod=validaDepto';
		      sQuery += '&iCodProcesso='+$F('p58_codproc');
		      sQuery += '&iCodDeptoRec='+$F('deptorec');
		      sQuery += '&iOrdemProrrogacao='+$F('ordem');
		  
		  var oAjax   = new Ajax.Request( sUrlRPC, {
		                                          method: 'post', 
		                                          parameters: sQuery, 
		                                          onComplete: js_retornoValidaProcesso
		                                        }
		                                );
    } else {
      var oProcesso = new js_objProcesso($F('p58_codproc'),0,false,'');   
      js_incluirDespacho(oProcesso,false);    
    }	                                      
	 
	}	
	 
	function js_retornoValidaProcesso(oAjax){
	
	  js_removeObj("msgBox");
    	   
	  var aRetorno = eval("("+oAjax.responseText+")");

	  if ( aRetorno.lDiferenca ) {
	    js_telaDiferencas(aRetorno.lTemDepto);
	  } else {
      var oProcesso = new js_objProcesso($F('p58_codproc'),0,false,'');	  
      js_incluirDespacho(oProcesso,false);	  
	  }
	    
	}	 
	 
	function js_validaCamposDif(){
    
    js_desabilitaBotao(false);	
	  var lRetorno     = true;
	  var aProcessoDif = new Array(); 
	  
	  if ( $F('deptorec') == '' ) {
	    alert('Departamento de recebimento não informado!');
	    return false;
	  }  
	  
    if ( $F('qtdDias') == '' ) {
      alert('Número de Dias não informado!');
	    return false;    
    }
    
    if ( $F('qtdDias') == 0 ) {
      alert('Número de Dias deve ser maior que zero!');
      return false;    
    }      

    var iNumOpt = $('segueAndamento').options.length;

    for ( var iIndOpt=0; iIndOpt < iNumOpt; iIndOpt++ ) {
      if ( $('segueAndamento').options[iIndOpt].selected ) {
        lSegue = eval($('segueAndamento').options[iIndOpt].value);
      }
    }
	      
	  var oProcesso = new js_objProcesso($F('p58_codproc'),$F('qtdDias'),lSegue,$F('motivo'));

	  js_fecharJanela();
	  js_incluirDespacho(oProcesso,true);
	
	}
	
	function js_objProcesso(iCodProc,iDias,lSegue,sMotivo){
  
	  this.iCodProc = iCodProc;
	  this.iDias    = iDias;
	  this.lSegue   = lSegue;
	  this.sMotivo  = sMotivo;

  }
  
  if ( $F('deptorec') != '' ) {
    js_pesquisaUsuarios($F('deptorec'));
  }
  
  
  function js_desabilitaTransf(lDesabilita){
    
    var aRadio = $$('#tipoDespacho input[value="t"]');
        aRadio[0].disabled = lDesabilita;
        
  }
  
  function js_desabilitaBotao(lDesabilita){
    $('incluir').disabled = lDesabilita;
  }
  
  
  <? 
	  if ( $oGet->lDeptoSessao == 'true') {
      echo " js_desabilitaTransf(false);";	  
	  } else {
	  	echo " js_desabilitaTransf(true);";
	  }
  ?>
    	 
</script>