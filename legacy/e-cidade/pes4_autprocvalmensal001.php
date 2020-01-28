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
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$oRotulo = new rotulo('rhsefip');
$oRotulo->label('rh90_anousu');
$oRotulo->label('rh90_mesusu');

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
	db_app::load("scripts.js");
	db_app::load("prototype.js");
	db_app::load("datagrid.widget.js");
	db_app::load("strings.js");
	db_app::load("grid.style.css");
	db_app::load("estilos.css");
	db_app::load("widgets/windowAux.widget.js");
	db_app::load("dbmessageBoard.widget.js");
?>
<style>

#titleWinObs {
  padding: 0px;
  text-align: right;
  border-bottom: 2px outset white;
  background-color: #2C7AFE;
  color: white
}

#winObs {
	position: fixed;
	border: 2px outset white;
	background-color: #CCCCCC;
	z-index: 0;
	visibility: hidden;
}

</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<center>
<form name="form1" method="post" action="">
<table style="padding-top: 25px;">
	<tr>
		<td>
		  <fieldset>
		    <legend> 
		      <b>Processa Valores Mensais</b> 
		    </legend>
		    <table>
					<tr>
						<td>
						  <b>Competência ( Mês / Ano ) :</b>
						</td>
						<td>
						   <?php
						   
						     $anousu = db_anofolha();
						     $mesusu = db_mesfolha();
						   
                 db_input('mesusu',2,true,$Irh90_anousu,'text',1);
                 echo "/";
                 db_input('anousu',4,true,$Irh90_mesusu,'text',1);     						     
						   ?>
						</td>
						<td>
						  <input type="button" id="pesquisar" value="Pesquisar" onClick="js_pesquisaRegistros();" />
						</td>
					</tr>
			  </table>
			</fieldset>
	  </td>
	</tr>
  <div id='winObs'>
    <div id='titleWinObs'>
      <span style='float: left'> 
        <b>&nbsp;Observações</b> 
      </span> 
      <img src='imagens/jan_fechar_on.gif' border='0' onclick="$('winObs').style.visibility='hidden';">
    </div>
    <div style='padding: 3px; border: 1px inset white'>
      <div id="obsDados" style='padding: 5px; background:#FFF;border: 1px solid #AAA;margin: 5px; text-align: left;line-height: 1.5em;' >
      </div>
    </div>
  </div>	
	<tr>
		<td>
  		<fieldset>
  		  <legend>
  		    <b>Registros a Processar</b> 
  		  </legend>
		    <table style='border: 2px inset white; width: 780px' cellspacing="0">
					<tr>
						<td class='table_header' width="20px" ><a href="#" onClick="js_marcaTodos();">M</a></td>
						<td class='table_header' width="60px" >CGM</td>
						<td class='table_header' width="300px">Nome</td>
						<td class='table_header' width="100px">Data Liquidação</td>
						<td class='table_header' width="100px">Valor Serviço</td>
						<td class='table_header' width="100px">Valor Retenção</td>
						<td class='table_header' width="50px" >Obs</td>
						<td class='table_header' width="15px" >&nbsp;</td>
					</tr>
					<tbody id='listaReg' style='height: 300px; overflow: scroll; overflow-x: hidden; background-color: white'>
					</tbody>
					<tfoot>
						<tr>
							<td colspan='8'>
							  <b>&nbsp;Total de Registros :&nbsp;<span id='totalRegGrid'></span></b>
							</td>
						</tr>
					</tfoot>
				</table>
		  </fieldset>
		</td>
	</tr>
	<tr align="center" id="teste">
		<td>
		  <input type="button" id="processar" value="Processar" onClick="js_processar();" disabled/>
		</td>
	</tr>
</table>
</form>
</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
  
  var sUrlRPC = 'pes4_autprocvalores.RPC.php';
  var oParam  = new Object();


  function js_pesquisaRegistros(){

    var sAnoUsu = new String($F('anousu'));
    var sMesUsu = new String($F('mesusu'));
	    
    if ( sAnoUsu.trim() == '' || sMesUsu.trim() == '' ) {
      alert('Competência não informada!');
      return false;
    }
	    
    if ( sMesUsu < 1 || sMesUsu > 12  ) {
      alert('Mês inválido!');
      return false; 
    }
	    
	  oParam.iAnoUsu = sAnoUsu;
	  oParam.iMesUsu = sMesUsu;

    js_pesquisaAutonomos();
      
  }

  function js_pesquisaAutonomos() {
    
	  js_divCarregando('Aguarde, pesquisando...', 'msgbox');
	  
	  oParam.sMethod = 'consultaRegistros';
	  
	  var oAjax   = new Ajax.Request( 
	                                 sUrlRPC, 
	                                 {
	                                   method: 'post', 
	                                   parameters: 'json='+Object.toJSON(oParam), 
	                                   onComplete: js_retornoConsultaReg 
	                                 }
	                                );      
  
  }
  
  
  function js_retornoConsultaReg(oAjax){

    var oRetorno = eval("("+oAjax.responseText+")");
    js_removeObj('msgbox');
    
    if ( oRetorno.iStatus == 2 ) {
      alert(oRetorno.sMsg.urlDecode());
      return false;
    } else {
      js_montaGrid(oRetorno.aListaAutonomos,oRetorno.lAlteraCgm);
    }
  
  }
  
  
  function js_montaGrid(aListaAutonomos,lAlteraCgm) {
    
    var sHTML = '';   
    
    $('listaReg').innerHTML     = '';
    $('totalRegGrid').innerHTML = aListaAutonomos.length;
    
       
    if ( aListaAutonomos.length > 0 ) {
    
      $('processar').disabled = false;
    } else {
      $('processar').disabled = true;
    }   
       
    aListaAutonomos.each(
    
      function( oDadosAutonomo ){
        
        var aObs = new Array();
        
        
        // Caso não tenha PIS/PASEP cadastrado irá gerar inconsistência
        if ( oDadosAutonomo.pis == '' ) {
           aObs.push(' - PIS/PASEP não cadastrado!');
        }
        
        // Caso não tenha CBO cadastrado irá gerar inconsistência        
        if ( oDadosAutonomo.cbo == '' ) {
           aObs.push(' - CBO não cadastrado!');
        }
          
        // Não caso em que houver inconsistência será desabilitado o checkbox para seleção  
        if ( aObs.length > 0 ) {
          var sDisabled = 'disabled'; 
        } else {
          var sDisabled = '';
        } 
        
        // Caso não haja inconsistência e já temha sido processado anteriormente então 
        // o checkbox será marcado automaticamente 
        if ( oDadosAutonomo.configurado == 't' && sDisabled == '' ) {
          var sChecked = 'checked';
        } else {
          var sChecked = '';
        }  

        oDadosAutonomoRetorno = new Object();

        oDadosAutonomoRetorno.codord          = oDadosAutonomo.codord         ; 
        oDadosAutonomoRetorno.numcgm          = oDadosAutonomo.numcgm         ;
        oDadosAutonomoRetorno.pis             = oDadosAutonomo.pis            ;
        oDadosAutonomoRetorno.cbo             = oDadosAutonomo.cbo            ;
        oDadosAutonomoRetorno.configurado     = oDadosAutonomo.configurado    ;
        oDadosAutonomoRetorno.data_liquidacao = oDadosAutonomo.data_liquidacao;
        oDadosAutonomoRetorno.valor_inss      = oDadosAutonomo.valor_inss     ;
        oDadosAutonomoRetorno.valor_irrf      = oDadosAutonomo.valor_irrf     ;
        oDadosAutonomoRetorno.valor_servico   = oDadosAutonomo.valor_servico  ;
        
        var sInputChk = "<input class='chk' type='checkbox' value='"+Object.toJSON(oDadosAutonomoRetorno)+"' "+sChecked+" "+sDisabled+" >";        
        var sClass    = 'class="linhagrid"';


        if ( aObs.length > 0 ) {
          
          var sTextObs = '';
          
          aObs.each(
            function (sObsMsg) {
              sTextObs += sObsMsg+'<br>';
            }
          );
        
          // Insere a imagem com das observações  
          var sObs  ='<span>                                                    ' 
                    +'  <a href="#" onclick="js_showObs(this,\''+sTextObs+'\')">'
                    +'    <img src="imagens/edittext.png" border="0" />         '
                    +'  </a>                                                    '
                    +'</span>                                                   '
                    +'&nbsp;...                                                 ';
        
          // LinhaInconsistente
          var sColorRow = '#FF4649';
        } else {
        
          // Linha Consistente
          var sColorRow = '#FFF';
          var sObs = '&nbsp;';
        }

        if (lAlteraCgm) {
	        var sAncoraCGM = '<a href="#" onClick="js_alterarCgm('+oDadosAutonomo.numcgm+')">'+oDadosAutonomo.numcgm+'</a>';        
        } else {
          var sAncoraCGM = oDadosAutonomo.numcgm;
        }
        
        sHTML +=' <tr style="background:'+sColorRow+';">                                                                                    '
               +'    <td '+sClass+' style="text-align:center"                    >'+sInputChk                                      +'</td>  '
               +'    <td '+sClass+' style="text-align:right ;padding-right:5px;" >'+sAncoraCGM                                     +'</td>  '
               +'    <td '+sClass+' style="text-align:left  ;padding-left:5px;"  >'+oDadosAutonomo.nome.urlDecode()                +'</td>  '
               +'    <td '+sClass+' style="text-align:center"                    >'+js_formatar(oDadosAutonomo.data_liquidacao,'d')+'</td>  '
               +'    <td '+sClass+' style="text-align:right ;padding-right:5px;" >'+js_formatar(oDadosAutonomo.valor_servico  ,'f')+'</td>  '
               +'    <td '+sClass+' style="text-align:right ;padding-right:5px;" >'+js_formatar(oDadosAutonomo.valor_inss     ,'f')+'</td>  '
               +'    <td '+sClass+' style="text-align:left  ;padding-left:5px;"  >'+sObs                                           +'</td>  '
               +'    <td '+sClass+' style="text-align:center">&nbsp;</td>                                                                   '
               +' </tr>                                                                                                                     '
      }
    );
    
    sHTML += "<tr>";
    sHTML += "  <td colspan='8' style='height:100%;'>&nbsp;</td>";
    sHTML += "</tr>";
    
    $('listaReg').innerHTML = sHTML;
        
  }

  function js_marcaTodos() {
  
    var aListaCheck = $$('.chk');
  
    aListaCheck.each(
    
      function(oChk) {
      
        if ( !oChk.disabled ) {
        
	        if ( oChk.checked  ) {
	          oChk.checked = false; 
	        } else {
	          oChk.checked = true;
	        }
        }
      }
    );
  
  }


  function js_processar() {
  
    var aListaChk       = $$('.chk:checked');
    var aListaAutonomos = new Array();

    aListaChk.each(
      function (oChk) {
        aListaAutonomos.push(oChk.value.evalJSON());
      }
    );

    js_divCarregando('Aguarde, processando...', 'msgbox');
    
    oParam.sMethod = 'insereRegistros';
    oParam.aListaAutonomos = aListaAutonomos;
    
    var oAjax   = new Ajax.Request( 
                                   sUrlRPC, 
                                   {
                                     method: 'post', 
                                     parameters: 'json='+Object.toJSON(oParam), 
                                     onComplete: js_retornoInsereReg 
                                   }
                                  );      
  }
  
  function js_retornoInsereReg(oAjax){

    var oRetorno = eval("("+oAjax.responseText+")");
    js_removeObj('msgbox');
    
    if ( oRetorno.iStatus == 2 ) {
	    alert(oRetorno.sMsg.urlDecode());
      return false;
    } else {
      alert('Registros processados com sucesso!');
	    $('listaReg').innerHTML = ''; 
	    $('processar').disabled = true;
    }
  
  }  
  
  function js_showObs(obj,sObs) {
  
    var el = obj; 
    var x  = 0;
    var y  = 0;
    
    while (el.offsetParent && el.tagName.toUpperCase() != 'BODY') {
      x += el.offsetLeft;
      y += el.offsetTop;
      el = el.offsetParent;
    }
        
    var iDiminuir = $('listaReg').scrollTop;
      $('winObs').style.top        = (y-(iDiminuir-10))+"px";
        
    if (((y-iDiminuir)+$('winObs').scrollHeight) > document.body.scrollHeight) {
      $('winObs').style.top      = (y-iDiminuir-($('winObs').scrollHeight))+"px";
    }
    
    $('winObs').style.left       = (x)+"px";
    $('winObs').style.visibility = 'visible';
    $('obsDados').innerHTML      = sObs;
    $('obsDados').focus();
  }    
  
  
	function js_alterarCgm(iCgm) {
	
	  if (iCgm != "") {
	  js_OpenJanelaIframe('', 
	                      'db_iframe_novocgm', 
	                      'prot1_cadgeralmunic002.php?chavepesquisa='+iCgm+
	                      '&lMenu=false&lCpf=true&funcaoRetorno=top.corpo.js_retornoAlteraCgm',
	                      'Novo CGM');
	 }
	} 
	
	function js_retornoAlteraCgm(iCgm) {
	  db_iframe_novocgm.hide();
	  js_pesquisaAutonomos();
	}	
	 
</script>
</html>