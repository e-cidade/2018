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
include("libs/db_app.utils.php");

$oGet = db_utils::postMemory($_GET);

require_once('classes/db_ouvidoriaatendimentotiporetorno_classe.php');
$clOuvidoriaAtendimentoTipoRetorno = new cl_ouvidoriaatendimentotiporetorno();

require_once('classes/db_ouvidoriaatendimento_classe.php');
$clOuvidoriaAtendimento = new cl_ouvidoriaatendimento();

$rsDadosAtendimento = $clOuvidoriaAtendimento->sql_record($clOuvidoriaAtendimento->sql_query($oGet->iCodAtendimento));
db_fieldsmemory($rsDadosAtendimento,0);

require_once("dbforms/verticalTab.widget.php");

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
<link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1;js_detalheDefault();" >
	<form name="form1" action=""> 
		<table align="center" >
		  <tr>  
		    <td>
		      <fieldset>
		        <legend>
		          <b>Dados do Atendimento</b>
		        </legend>
			      <table>
			        <tr>
			          <td width="130px;">
		              <b>Número:</b>
			          </td>
			          <td colspan="3">
			            <?
			              db_input('ov01_sequencial',10,'',true,'hidden',3,'');
	                  db_input('ov01_numero'    ,10,'',true,'text',3,'');
			            ?>
			          </td>
			        </tr>
              <tr>
                <td>
                  <b>Usuário:</b>
                </td>
                <td colspan="3">
                  <?
                    db_input('ov01_usuario'  ,10,'',true,'text',3,'');
                    db_input('nome',50,'',true,'text',3,'');
                  ?>
                </td>
              </tr>             
              <tr>
                <td>
                  <b>Departamento:</b>
                </td>
                <td colspan="3">
                  <?
                    db_input('ov01_depart'  ,10,'',true,'text',3,'');
                    db_input('descrdepto',50,'',true,'text',3,'');
                  ?>
                </td>
              </tr>            			        
	            <tr>
	              <td>
	                <b>Data da Criação:</b>
	              </td>
	              <td>
	                <?
	                  
	                  if ( trim($ov01_dataatend) != '' ) {
		                  $aDataAtend= explode('-',$ov01_dataatend);
		                  $iDia = $aDataAtend[2];
		                  $iMes = $aDataAtend[1];
		                  $iAno = $aDataAtend[0];
	                  }
	                  
	                  db_inputdata('ov01_dataatend',@$iDia,@$iMes,@$iAno,true,'text',3,'');
	                ?>
	              </td>
	              <td align="right">
	                <b>Hora Inclusão:</b>
	              </td>
	              <td align="right">
	                <?
	                  db_input('ov01_horaatend',10,'',true,'text',3,'');
	                ?>
	              </td>             
	            </tr>
	            <tr>
	              <td>
	                <b>Requerente:</b>
	              </td>
	              <td colspan="3">
	                <?
	                  db_input('ov01_requerente',63,'',true,'text',3,'');
	                ?>
	              </td>
	            </tr>
	            <tr>
	              <td>
	                <b>Solicitação:</b>
	              </td>
	              <td colspan="3">
	                <?
	                  db_textarea('ov01_solicitacao',5,61,'',true,'text',3);
	                ?>
	              </td>
	            </tr>             
			      </table>
		      </fieldset>
		  	</td>
		  </tr>
		  <tr>
		    <td>
	        <table>
            <tr>
              <td>
                <fieldset>  
                  <legend>
                    <b>Informação</b>
                  </legend>
                  <table>
                    <tr>
                      <td>
			                   <?
			                     db_textarea('ov20_informa',5,35,'',true,'text',1);
			                   ?>
                      </td>
                    </tr>
                  </table>
                </fieldset>
              </td>
              <td>
                <fieldset>  
                  <legend>
                    <b>Resposta</b>
                  </legend>
                  <table>
                    <tr>
                      <td>
                         <?
                           db_textarea('ov20_resposta',5,35,'',true,'text',1);
                         ?>
                      </td>
                    </tr>
                  </table>
                </fieldset>
              </td>              
            </tr>
          </table>
		    </td>
		  </tr>
      <tr>
        <td>
          <table>		  
            <tr>
			        <td> 
			          <b>Tipo de Retorno:</b>
			        </td>
			        <td>
			          <?
			            $sWhereTipoRetorno = " ov17_ouvidoriaatendimento = {$oGet->iCodAtendimento} ";
			            $sSqlTipoRetorno   = $clOuvidoriaAtendimentoTipoRetorno->sql_query(null,"ov17_tiporetorno,ov22_descricao",null,$sWhereTipoRetorno);
			            $rsTipoRetorno     = $clOuvidoriaAtendimentoTipoRetorno->sql_record($sSqlTipoRetorno);
						            
			            db_selectrecord('tiporetorno',$rsTipoRetorno,true,1,"style='width:150px;'",'','','','',1);
			          ?>
			        </td>
			      </tr>
            <tr>
              <td> 
                <b>Confirma Retorno:</b>
              </td>
              <td id="idConfirma">
                <input type="radio" name="confirma" value="s" checked><b>Sim</b></input>
                <input type="radio" name="confirma" value="n"        ><b>Não</b></input>                 
              </td>
            </tr>			       
          </table>
        </td>
      </tr>                  		        
      <tr align="center">
        <td>
          <input type="button" id="incluir" name="incluir" value="Incluir Retorno" onClick="js_incluirRetorno()"/>
          <input type="button" id="fechar"  name="fechar"  value="Fechar"          onClick="js_fechar();"/>
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
        </td>
      </tr>		  
		</table>
		<table align="center"  width="70%" id="idDetalhesRetorno">
		  <tr>
		    <td>
					<fieldset style='padding-left:0px'>
					  <legend>
					    <b>Detalhamento Retorno</b>
					  </legend>
						<?
							$oTabDetalhes = new verticalTab("detalhesretorno",200);
							$oTabDetalhes->add("1","Pessoalmente"       ,"ouv1_detalhesretornoender001.php?iCodAtendimento={$ov01_sequencial}");
							$oTabDetalhes->add("3","Email"              ,"ouv1_detalhesretornoemail001.php?iCodAtendimento={$ov01_sequencial}");
							$oTabDetalhes->add("4","Telefone"           ,"ouv1_detalhesretornotelefone001.php?iCodAtendimento={$ov01_sequencial}");
							$oTabDetalhes->add("0","Retornos Anteriores","ouv1_detalhesretornosanteriores001.php?iCodAtendimento={$ov01_sequencial}");
							$oTabDetalhes->show();
						?>
					</fieldset>
				</td>
			</tr>
		</table>					
	</form>
</body>
</html>
<script>
  
  var sUrl = 'ouv1_retornocliente.RPC.php';
   
  
  function js_incluirRetorno(){
  
    if ( $F('ov20_informa') == '' ) {
      alert('Campo informação não informado!');
      return false;
    }
    
    js_divCarregando('Aguarde...','msgBox');
    var sQuery  = 'sMethod=incluirRetorno';
        sQuery += '&iTipoRetorno='+$F('tiporetorno');
        sQuery += '&sInformacao='+$F('ov20_informa');
        sQuery += '&sResposta='+$F('ov20_resposta');
        sQuery += '&sConfirma='+js_getConfirma();
        sQuery += '&iCodAtendimento='+$F('ov01_sequencial');
        
    
    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoIncluirRetorno
                                          }
                                  );  
  }
  
    
  function js_retornoIncluirRetorno(oAjax){
  
    js_removeObj("msgBox");
	
    var aRetorno = eval("("+oAjax.responseText+")");
    var sExpReg  = new RegExp('\\\\n','g');
    
    alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
  
    if ( aRetorno.lErro ) {
      return false;
    } else {
      parent.js_consultaAtendimentos();
      js_fechar();   
    }
        
  }
  
 
  function js_getConfirma(){
    
    var aRadio    = $$("#idConfirma input[type='radio']");
    var sConfirma = '';
     
    aRadio.each(
      function ( eRadio, iInd ) {
        if ( eRadio.checked ) {
          sConfirma = eRadio.value;
        }
      }
    );
  
    return sConfirma;
  
  }
  
  function js_fechar(){
    parent.db_iframe_retorno.hide()
  }  
  
  function js_detalheDefault(){
    
    var idTab  = $('tiporetorno').value;
    
    if ( idTab > 2 ) {
	    js_marcaTab($(idTab));
	    detalhesretornoDetalhes.location.href = $(idTab).href;
    }
    
  }
  
</script>