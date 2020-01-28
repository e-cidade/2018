<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_meiimporta_classe.php"));

$clMeiImporta = new cl_meiimporta();

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
	db_app::load("estilos.css");
	db_app::load("widgets/windowAux.widget.js");
	db_app::load("dbmessageBoard.widget.js");
?>
<style>
.processa      { background: #8ae58a; }

.descarta      { background: #FFFF66; }

.inconsistente { background: #FF4649; }

.subRowGrid    { border-bottom: 1px inset black; }

.valDetalhe {
	font-family: Arial, Helvetica, serif, verdana, sans-serif;
	font-size: 12px;
	height: 17px;
	border: 1px solid #999999;
	background: #FFF;
}

#digitarMotivo {
	position: fixed;
	border: 2px outset white;
	background-color: #CCCCCC;
	z-index: 0;
	visibility: hidden;
}

#titleDigitarMotivo {
	padding: 0px;
	text-align: right;
	border-bottom: 2px outset white;
	background-color: #2C7AFE;
	color: white
}
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"
	onLoad="a=1" bgcolor="#cccccc">
<center>
<form name="form1" method="post" action="">
<table style="padding-top: 25px;">
	<tr>
		<td>
		  <fieldset>
		    <legend>Processar Arquivo MEI</legend>
		    <table>
					<tr>
						<td>
						  <strong>Competência:</strong>
						</td>
						<td>
						  <select id="competencia" style="width:100px;" disabled>
						  </select>
						</td>
						<td>
						  <input type="button" id="pesquisar" value="Pesquisar" onClick="js_pesquisaRegistros();" />
						</td>
					</tr>
			  </table>
			</fieldset>
	  </td>
	</tr>
	<div id='digitarMotivo'>
	  <div id='titleDigitarMotivo'>
	    <span style='float: left'>
	      <strong>&nbsp;Motivo Descarte</strong>
	    </span>
	    <img src='imagens/jan_fechar_on.gif' border='0' onclick="$('digitarMotivo').style.visibility='hidden';">
	  </div>
	  <div style='padding: 3px; border: 1px inset white'>
	    <textarea id='motivo' rows="10" cols="30">
      </textarea>
	    <center>
	      <input value='Confirma' type='button' id='atualizarMotivo'>
	    </center>
	  </div>
	</div>
	<tr>
		<td>
  		<fieldset>
  		  <legend>Registros a Processar</legend>
		    <table style='border: 2px inset white; width: 780px' cellspacing="0">
					<tr>
						<td class='table_header' width="14px"><img src="imagens/espaco.gif"></td>
						<td class='table_header' width="752px" colspan="7">CNPJ / Nome ( MEI )</td>
						<td class='table_header' width="14px">
						  <img src='imagens/identacao.gif' border='0'>
						  <img src="imagens/espaco.gif">
						</td>
					</tr>
					<tr>
						<td class='table_header' width="20px"><img src="imagens/espaco.gif"></td>
						<td class='table_header' width="25px" title="Processar">P</td>
						<td class='table_header' width="25px" title="Descartar">D</td>
						<td class='table_header' width="50px">Código</td>
						<td class='table_header' width="392px">Descrição Evento</td>
						<td class='table_header' width="80px">Data Evento</td>
						<td class='table_header' width="80px">Situação</td>
						<td class='table_header' width="80px">Motivo</td>
						<td class='table_header' width="14px">
						  <img src='imagens/identacao.gif' border='0'>
						  <img src="imagens/espaco.gif">
					  </td>
					</tr>
					<tbody id='listaReg' style='height: 300px; overflow: scroll; overflow-x: hidden; background-color: white'>
					</tbody>
					<tfoot>
						<tr>
							<td colspan='8'>
							  <strong>&nbsp;Total de Registros :&nbsp;<span id='totalRegGrid'></span></strong>
							</td>
						</tr>
					</tfoot>
				</table>
		  </fieldset>
		</td>
	</tr>
	<tr align="center" id="teste">
		<td>
		  <input type="button" id="processar" value="Processar" onClick="js_processar();" />
		</td>
	</tr>
</table>
</form>
</center>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>

  var sUrlRPC        = 'iss4_processamentomei.RPC.php';
  var oParam         = new Object();
  var sCnaeAtividade = '';


  function js_pesquisaRegistros(){
    js_consultaImportacao($F('competencia'));
  }

  function js_consultaImportacao(sCompetencia){

	  js_divCarregando('Aguarde, pesquisando...', 'msgbox');

	  oParam.sMethod      = 'consultaRegistros';
	  oParam.sCompetencia = sCompetencia;

	  var oAjax   = new Ajax.Request(
	                                 sUrlRPC,
	                                 {
	                                   method: 'post',
	                                   parameters: 'json='+Object.toJSON(oParam),
	                                   onComplete: js_retornoConsultaImp
	                                 }
	                                );

  }


  function js_retornoConsultaImp(oAjax){

    var oRetorno = eval("("+oAjax.responseText+")");
    js_removeObj('msgbox');

    if ( oRetorno.iStatus == 2 ) {
      alert(oRetorno.sMsg.urlDecode());
      return false;
    } else {
      $('processar').disabled = false;
      js_montaGrid(oRetorno.aDadosImporta);
    }

  }


  function js_montaGrid(aDadosImporta) {

    $('listaReg').innerHTML     = '';
    $('totalRegGrid').innerHTML = aDadosImporta.length;

    aDadosImporta.each(
      function( oDadosImporta ){

        var iCnpj = oDadosImporta.iCnpj;

        var oRowImporta              = document.createElement("TR");
        oRowImporta.id               = 'linhaMei'+iCnpj;

        if ( oDadosImporta.lBloqueado ) {
          oRowImporta.className      = 'inconsistente';
        }

        var oCellTree                = document.createElement("TD");
        oCellTree.innerHTML          = "<img src='imagens/treeplus.gif' onClick=\"js_mostraEventos(this,'"+iCnpj+"')\">";
        oCellTree.align              = "center";
        oCellTree.className          = 'linhagrid';

        var oCellNomeMEI             = document.createElement("TD");
        oCellNomeMEI.innerHTML       = js_formatar(iCnpj,'cpfcnpj')+" - "+oDadosImporta.sNome.urlDecode();
        oCellNomeMEI.style.textAlign = "left";
        oCellNomeMEI.className       = 'linhagrid';
        oCellNomeMEI.colSpan         = '7';

        oRowImporta.appendChild(oCellTree);
        oRowImporta.appendChild(oCellNomeMEI);

        $('listaReg').appendChild(oRowImporta);

        var iCountEventos = oDadosImporta.aEventos.length;

        oDadosImporta.aEventos.each(
          function( oDadosEvento, iInd ){

            var sIdEvento  = iCnpj+oDadosEvento.sCodEvento;
            var jsonEvento = Object.toJSON(oDadosEvento);

				    var oRowEvento           = document.createElement("TR");
				    oRowEvento.style.display = 'none';
				    oRowEvento.id            = "linhaEvento"+iCnpj;
				    oRowEvento.name          = sIdEvento;


            if ( oDadosEvento.lInconsistente ) {
	  			    oRowEvento.className   = "inconsistente";
		          sDisabled = 'disabled';
            } else {
		          sDisabled = '';
              oRowEvento.className   = "linhagrid";
            }

				    var oCellTree               = document.createElement("TD");
            oCellTree.style.background  = '#FFFFFF';

  	        if ( ( iInd == 0 && iCountEventos == 1 ) || iInd == (iCountEventos-1) ) {
				      oCellTree.innerHTML  = "<img src='imagens/tree/join.gif'>";
				    } else {
				      oCellTree.innerHTML  = "<img src='imagens/tree/joinbottom.gif'>";
				    }

		        var oCellChkProc                     = document.createElement("TD");
		        oCellChkProc.innerHTML               = "<input type='checkbox' class='chkProc' id='chkProc"+sIdEvento+"' value='"+jsonEvento+"' onChange='js_verificaChk(this,true,true);' "+sDisabled+">";
		        oCellChkProc.align                   = 'center';
		        oCellChkProc.className               = 'linhagrid';

		        var oCellChkDesc                     = document.createElement("TD");
		        oCellChkDesc.innerHTML               = "<input type='checkbox' class='chkDesc' id='chkDesc"+sIdEvento+"' value='"+jsonEvento+"' onChange='js_verificaChk(this,true,true);'>";
		        oCellChkDesc.align                   = 'center';
		        oCellChkDesc.className               = 'linhagrid';

				    var oCellEventoCodigo                = document.createElement("TD");
				    oCellEventoCodigo.innerHTML          = oDadosEvento.sCodEvento.urlDecode();
				    oCellEventoCodigo.style.textAlign    = 'center';
				    oCellEventoCodigo.className          = 'subRowGrid';

				    var oCellEventoDescricao             = document.createElement("TD");
				    oCellEventoDescricao.innerHTML       = "&nbsp;<a href='#' onClick='js_consultaDetalheEvento("+iCnpj+","+oDadosEvento.sCodEvento.urlDecode()+","+oDadosEvento.lVinculaMEI+")'>MI</a>";

            var sDescrEvento = new String(oDadosEvento.sDescricao.urlDecode());
            if ( sDescrEvento.length > 70 ) {
              sDescrEvento = sDescrEvento.substr(0,55)+' ... ';
            }

				    oCellEventoDescricao.innerHTML      += "&nbsp;-&nbsp;"+sDescrEvento;
				    oCellEventoDescricao.style.textAlign = "left";
				    oCellEventoDescricao.className       = "subRowGrid";

				    var oCellEventoData                  = document.createElement("TD");
				    oCellEventoData.innerHTML            = js_formatar(oDadosEvento.dtData,'d');
				    oCellEventoData.style.textAlign      = "center";
				    oCellEventoData.className            = "subRowGrid";

				    var oCellEventoSituacao              = document.createElement("TD");
				    oCellEventoSituacao.style.textAlign  = "center";
				    oCellEventoSituacao.className        = "subRowGrid";

				    if ( oDadosEvento.lInconsistente ) {
					    oCellEventoSituacao.innerHTML      = 'Inconsistente';
					    oCellEventoSituacao.onclick        = function() {
					                                           js_consultaDetalheInconsistencias(iCnpj,oDadosEvento.sCodEvento.urlDecode());
					                                         };
					    oCellEventoSituacao.onmouseover    = function() {
					                                           oCellEventoSituacao.style.cursor = 'pointer';
					                                           js_showHint(oCellEventoSituacao,oDadosEvento.sMsgSituacao.urlDecode())
					                                         };
				    } else {
				      oCellEventoSituacao.innerHTML      = 'Consistente';
				    }

		        var oCellMotivo              = document.createElement("TD");
		        oCellMotivo.innerHTML        = " <div id='divMotivo"+sIdEvento+"' style='overflow:hidden;width:80px;text-align:left;visibility:hidden'>"
			                                    +"   <span>&nbsp;<a href='#' onclick='js_showObs(\""+sIdEvento+"\",this)'><img src='imagens/edittext.png' border='0' ></a></span>"
																	        +"   <span id='descrmotivo"+sIdEvento+"' ></span>"
																	        +"   <span id='motivo"+sIdEvento+"' style='display:none'></span>"
																	        +" </div>";
		        oCellMotivo.style.textAlign  = "left";
		        oCellMotivo.className        = 'linhagrid';
		        oCellMotivo.noWrap           = true;

				    oRowEvento.appendChild(oCellTree);
		        oRowEvento.appendChild(oCellChkProc);
		        oRowEvento.appendChild(oCellChkDesc);
				    oRowEvento.appendChild(oCellEventoCodigo);
				    oRowEvento.appendChild(oCellEventoDescricao);
				    oRowEvento.appendChild(oCellEventoData);
				    oRowEvento.appendChild(oCellEventoSituacao);
				    oRowEvento.appendChild(oCellMotivo);

		        $('listaReg').appendChild(oRowEvento);

          }
        );

        var oRowVazio                 = document.createElement("TR");
        oRowVazio.id                  = "linhaEvento"+iCnpj;
        oRowVazio.style.display       = 'none';
        oRowVazio.className           = 'rowVazio';

        var oCellVazio                = document.createElement("TD");
        oCellVazio.colSpan            = 8;
        oCellVazio.style.borderBotton = "1px inset black";
        oCellVazio.innerHTML          = '&nbsp;';

        oRowVazio.appendChild(oCellVazio);

        $('listaReg').appendChild(oRowVazio);

      }
    );

    var oRowUltimaLinha = document.createElement('TR');
    oRowUltimaLinha.id  = 'ultimaLinha';

    $('listaReg').appendChild(oRowUltimaLinha);

    oRowUltimaLinha.innerHTML = "<td colspan='8' style='height:100%;'>&nbsp;</td>";

  }

  function js_consultaDetalheInconsistencias(iCnpj,sCodEvento) {

    js_divCarregando('Aguarde, pesquisando inconsistências...', 'msgbox');

    oParam.sMethod            = 'consultaDetalheInconsistencias';
    oParam.iCnpj              = new String(iCnpj);
    oParam.sCodEvento         = sCodEvento;

    var oAjax = new Ajax.Request ( sUrlRPC,
                                   {
                                     method: 'post',
                                     parameters: 'json='+Object.toJSON(oParam),
                                     onComplete: function(oReqAjax){
                                       js_retornoDetalheInconsistencia(oReqAjax,iCnpj,sCodEvento);
                                     }
                                   }
                                 );
  }

  function js_retornoDetalheInconsistencia(oAjax,iCnpj,sCodEvento){

    var oRetorno = eval("("+oAjax.responseText+")");

    js_removeObj('msgbox');

    if ( oRetorno.iStatus == 2 ) {

      alert(oRetorno.sMsg.urlDecode());
      return false;

    } else {

      var sContent  = "<table width='100%' >                                      "
                     +"  <tr>                                                     "
                     +"    <td>                                                   "
                     +oRetorno.sTelaDetalhe.urlDecode()
                     +"    </td>                                                  "
                     +"  </tr>                                                    "
                     +"  <tr align='center'>                                      "
                     +"    <td>                                                   "
                     +"      <input type='button' id='btnAlterar' value='Processar Alterações'/> "
                     +"      <input type='button' id='btnFechar'  value='Fechar' /> "
                     +"    </td>                                                  "
                     +"  </tr>                                                    "
                     +"</table>                                                   ";

      winDetalheInconsistencia  = new windowAux('detalheInconsistencia', '&nbsp;Lista de Inconsistências', 780, 400);
      winDetalheInconsistencia.setContent(sContent);
      winDetalheInconsistencia.show(50,280);

      var oMessageBoard = new DBMessageBoard('msgboard1',
                                             'Detalhes da Inconsistência',
                                             '',
                                             $('windowdetalheInconsistencia_content'));
      oMessageBoard.show();

      $('window'+winDetalheInconsistencia.idWindow+'_btnclose').observe("click",js_fecharJanelaDetalheInconsistencia);
      $('btnFechar').observe("click",js_fecharJanelaDetalheInconsistencia);
      $('btnAlterar').observe("click",function(){
         js_alterarInconsistencias(iCnpj,sCodEvento);
       }
      );

    }

  }

  function js_alterarInconsistencias(iCnpj,sCodEvento){

    if ( !confirm('Deseja realmente alterar todas as informações \nreferente aos dados em inconsistência?')) {
      return false;
    }

    if ( $('codlogradouro') != undefined ) {
	    oParam.iCodLogradouro = $('codlogradouro').value;
    }

    if ( $('codbairro') != undefined ) {
	    oParam.iCodBairro = $('codbairro').value;
    }

    if ( $('empresacadastrada') != undefined ) {
      if ( $('empresacadastrada').checked ) {
        oParam.lEmpresaCadastrada = true;
      }
    }

    if ( $('responsavelcadastrado') != undefined ) {
      if ( $('responsavelcadastrado').checked ) {
        oParam.lResponsavelCadastrado = true;
      }
    }

    var aListaAtividades = new Array();

    var aListaInputAtividades = $$('.inputAtiv');

    aListaInputAtividades.each(
      function (oNodeAtividade){
        var sCnae         = new String(oNodeAtividade.id).replace('codatividade','');
        var iCodAtividade = oNodeAtividade.value;
        aListaAtividades.push(new Array(sCnae,iCodAtividade));
      }
    );

    var aListaRadioAtividades = $$('.radioAtiv:checked');

    aListaRadioAtividades.each(
      function (oNodeAtividade){
        var sCnae         = new String(oNodeAtividade.name).replace('radioatividade','');
        var iCodAtividade = oNodeAtividade.value;
        aListaAtividades.push(new Array(sCnae,iCodAtividade));
      }
    );

    if ( aListaAtividades.length > 0 ) {
	    oParam.aListaAtividades  = aListaAtividades;
    }

    js_divCarregando('Aguarde, alterando inconsistências...', 'msgbox');

    oParam.sMethod            = 'alterarInconsistencias';
    oParam.iCnpj              = new String(iCnpj);
    oParam.sCodEvento         = sCodEvento;


    var oAjax = new Ajax.Request ( sUrlRPC,
                                   {
                                     method: 'post',
                                     parameters: 'json='+Object.toJSON(oParam),
                                     onComplete: js_retornoAlteracaoInconsistencia
                                   }
                                 );




  }

  function js_retornoAlteracaoInconsistencia(oAjax){

    var oRetorno = eval("("+oAjax.responseText+")");
    js_removeObj('msgbox');

    if ( oRetorno.iStatus == 2 ) {

      alert(oRetorno.sMsg.urlDecode());
      return false;

    } else {

      js_fecharJanelaDetalheInconsistencia();
      js_consultaImportacao($F('competencia'));

    }

  }

  function js_mostraEventos(oRowMeiImg,iCnpj){

    var aRowEventos = $$('#linhaEvento'+iCnpj);
    aRowEventos.each(
      function( oRowEvento ){
		    if ( oRowEvento.style.display == 'none' ) {
		      oRowMeiImg.src = 'imagens/treeminus.gif';
		      oRowEvento.style.display = '';
		    } else {
		      oRowMeiImg.src = 'imagens/treeplus.gif';
		      oRowEvento.style.display = 'none';
		    }
      }
    );
  }

  function js_marcaTodos(sTipo){

    var aListaMarca = new Array();
    var lPassa      = false;

    if ( sTipo == 'p' ) {
      aListaMarca = $$('.chkProc');
    } else if ( sTipo == 'd' ) {
      aListaMarca = $$('.chkDesc');
    }

    if ( aListaMarca.length > 0 ) {
      aListaMarca.each(
        function(oChk){
          if (!lPassa) {
	          var sId = new String(oChk.id).substr(7);

	          if ( oChk.checked || oChk.disabled ) {
	            if ( oChk.disabled ) {
	              $('chkDesc'+sId).checked = true;
	              js_verificaChk($('chkDesc'+sId),false,false);
	            } else {
	              oChk.checked = false;
	              js_verificaChk(oChk,true,false);
	              lPassa = true;
	            }
	          } else {
	            oChk.checked = true;
		          js_verificaChk(oChk,false,false);
	          }
          }
        }
      );
    }
  }

  function js_verificaChk(oChk,lCorrigeEventos,lConfirma){

    var oEvento        = oChk.value.evalJSON();
    var sIdEvento      = oEvento.iCnpj+oEvento.sCodEvento;
    var oRowEvento     = ((oChk.parentNode).parentNode);
    var oRowMEI        = $('linhaMei'+oEvento.iCnpj);
    var aEventosMEI    = $$("#linhaEvento"+oEvento.iCnpj+":not([class='rowVazio'])");
    var oDivMotivo     = $('divMotivo'+sIdEvento);
        oDivMotivo.style.visibility = 'hidden';
    var oChkProc       = $('chkProc'+sIdEvento);
    var oChkDesc       = $('chkDesc'+sIdEvento);

    var lInconsistente = false;
    var lDescarta      = false;
    var lProcessa      = false;

    if ( oChkProc.disabled ) {
      var lMeiBloqueado = true;
    } else {
      var lMeiBloqueado = false;
    }

    if ( oChk.checked ) {
      if ( oChk.className == 'chkProc') {
        oChkDesc.checked = false;
      } else {
        oChkProc.checked = false;
        oDivMotivo.style.visibility = 'visible';
      }
    }

    if ( !lMeiBloqueado ) {
	    if ( oChk.checked ) {
	      if ( oChk.className == 'chkProc') {
	        oRowEvento.className = 'processa';
	      } else {
	        oRowEvento.className = 'descarta';
	        oDivMotivo.style.visibility = 'visible';
	      }
	    } else {
	      oRowEvento.className = 'linhagrid';
	    }
    }

    if ( aEventosMEI.length > 1 ) {
      aEventosMEI.each(
        function (oRowEvento) {
          if ( oRowEvento.className == 'inconsistente' ) {
            lInconsistente = true;
          } else if ( oRowEvento.className == 'descarta' ) {
            lDescarta = true;
          } else if ( oRowEvento.className == 'processa' ) {
            lProcessa = true;
          }
        }
      );
    }

    if ( !lInconsistente && !lMeiBloqueado ) {
	    if ( oChk.checked ) {
	      if ( oChk.className == 'chkProc' ) {
	        if ( !lDescarta ) {
	          oRowMEI.className = 'processa';
	        }
	      } else {
	        oRowMEI.className = 'descarta';
	      }
	    } else {
	      if ( !lDescarta && !lProcessa ) {
		      oRowMEI.className = 'linhagrid';
	      }
	    }
    }

	  var iIndexEvento = (oRowEvento.rowIndex-2);

    if ( lCorrigeEventos ) {
      js_marcaEventosMei(oChk.checked,iIndexEvento,oChk,lConfirma);
    }

  }


  function js_marcaEventosMei(lMarca,iIndice,oChk,lConfirma){

    var oTable = $('listaReg');

    if ( lMarca ) {

      var oRowEvento = oTable.rows[--iIndice];

      while ( new String(oRowEvento.id).substr(0,11) == 'linhaEvento' ) {

        var oChkProcNode = $('chkProc'+oRowEvento.name);
        var oChkDescNode = $('chkDesc'+oRowEvento.name);

        if ( !oChkProcNode.checked && !oChkDescNode.checked ) {
          if ( oChkProcNode.disabled ) {
	          oChkDescNode.checked = true;
          } else {
            oChkProcNode.checked = true;
          }
          js_verificaChk(oChkProcNode,false,false);
        }

        oRowEvento = oTable.rows[--iIndice];

      }

    } else {

      var oRowEvento = oTable.rows[++iIndice];

      while ( new String(oRowEvento.id).substr(0,11) == 'linhaEvento' && oRowEvento.className != 'rowVazio'  ) {

        var oChkProcNode = $('chkProc'+oRowEvento.name);
        var oChkDescNode = $('chkDesc'+oRowEvento.name);

        if ( oChkProcNode.checked || oChkDescNode.checked ) {

          if ( lConfirma ) {

            if ( !confirm('Os eventos com data superior serão desmarcados deseja continuar?')) {

              oChk.checked = true;
              js_verificaChk(oChk,false,false);
              return false;

	          } else {

	            oChkProcNode.checked = false;
	            oChkDescNode.checked = false;


	            js_verificaChk(oChkProcNode,false,false);
	            js_verificaChk(oChkDescNode,false,false);
            }

            lConfirma = false;

          } else {

	          oChkProcNode.checked = false;
	          oChkDescNode.checked = false;

	          js_verificaChk(oChkProcNode,false,false);
	          js_verificaChk(oChkDescNode,false,false);

          }

        }

        oRowEvento = oTable.rows[++iIndice];

      }

    }

  }


  function js_consultaDetalheEvento(iCnpj,sCodEvento,lVinculaMEI){

	  js_divCarregando('Aguarde, pesquisando...', 'msgbox');

	  oParam.sMethod      = 'consultaDetalheEvento';
	  oParam.iCnpj        = new String(iCnpj);
	  oParam.lVinculaMEI  = lVinculaMEI;
	  oParam.sCodEvento   = sCodEvento;

	  var oAjax = new Ajax.Request ( sUrlRPC,
	                                 {
	                                   method: 'post',
															       parameters: 'json='+Object.toJSON(oParam),
															       onComplete: function(oReqAjax){
															         js_retornoDetalheEvento(oReqAjax,iCnpj,sCodEvento,lVinculaMEI);
										                 }
	                                 }
	                               );
  }

  function js_retornoDetalheEvento(oAjax,iCnpj,sCodEvento,lVinculaMEI) {

    var oRetorno = eval("("+oAjax.responseText+")");

    js_removeObj('msgbox');

    if ( oRetorno.iStatus == 2 ) {

      alert(oRetorno.sMsg.urlDecode());
      return false;

    } else {

      if ( lVinculaMEI ) {
        var sDisplayVincula = '';
      } else {
        var sDisplayVincula = 'none';
      }

      var sContent  = "<center>                                                ";
      sContent += "<table width='100%' >                                       ";
      sContent += "  <tr>                                                      ";
      sContent += "    <td>                                                    ";
      sContent +=  oRetorno.sTelaDetalhe.urlDecode();
      sContent += "    </td>                                                   ";
      sContent += "  </tr>                                                     ";
      sContent += "  <tr align='center'>                                       ";
      sContent += "    <td>                                                    ";
      sContent += "      <input type='button' id='btnVinculaMEI'  value='Vincula MEI' style='display:"+sDisplayVincula+"'/> ";
      sContent += "      <input type='button' id='btnFechar'      value='Fechar'/> ";
      sContent += "    </td>                                                   ";
      sContent += "  </tr>                                                     ";
      sContent += "</table>                                                    ";
      sContent += "</center>                                                   ";

      winDetalheEvento  = new windowAux('detalheEvento', '&nbsp;Detalhes do Evento', 780, 400);
      winDetalheEvento.setContent(sContent);
      winDetalheEvento.show(50,280);

      var oMessageBoard = new DBMessageBoard('msgboard1',
                                             'Evento : '+oRetorno.oEvento.q101_codigo,
                                             oRetorno.oEvento.q101_descricao.urlDecode(),
                                             $('windowdetalheEvento_content'));
      oMessageBoard.show();

      $('window'+winDetalheEvento.idWindow+'_btnclose').observe("click",js_fecharJanelaDetalheEvento);
      $('btnFechar').observe("click",js_fecharJanelaDetalheEvento);
      $('btnVinculaMEI').observe("click", function() {
	        js_vinculaMeiCgm(iCnpj,sCodEvento);
	      }
      );
    }
  }


  function js_vinculaMeiCgm(iCnpj,sCodEvento){

    if ( !confirm('Deseja realmente vincular o MEI selecionado ao CGM da empresa já cadastrada!')) {
      return false;
    }

    js_divCarregando('Aguarde, processando...', 'msgbox');

    oParam.sMethod      = 'vinculaMeiCgm';
    oParam.iCnpj        = new String(iCnpj);
    oParam.sCodEvento   = sCodEvento;

    var oAjax   = new Ajax.Request( sUrlRPC,
                                    {
                                      method: 'post',
                                      parameters: 'json='+Object.toJSON(oParam),
                                      onComplete: js_retornoVinculaMeiCgm
                                    }
                                  );

  }


  function js_retornoVinculaMeiCgm(oAjax){

    var oRetorno = eval("("+oAjax.responseText+")");
    js_removeObj('msgbox');

    alert(oRetorno.sMsg.urlDecode());

    if ( oRetorno.iStatus == 1 ) {
      js_fecharJanelaDetalheEvento();
      js_consultaCompetencias();
    }

  }


  function js_fecharJanelaDetalheEvento(){
    winDetalheEvento.destroy();
  }

  function js_fecharJanelaDetalheInconsistencia(){
    winDetalheInconsistencia.destroy();
  }


  function js_toggle(idNode,idImg){

    var oNode = $(idNode);
    var oImg  = $(idImg);

    if ( oNode.style.display == 'none' ) {
      oNode.style.display = '';
      oImg.src = 'imagens/setabaixo.gif';
    } else {
      oNode.style.display = 'none';
      oImg.src = 'imagens/seta.gif';
    }

  }


  function js_showObs(sIdEvento,obj) {

    var el = obj;
    var x  = 0;
    var y  = 0;

    while (el.offsetParent && el.tagName.toUpperCase() != 'BODY') {
      x += el.offsetLeft;
      y += el.offsetTop;
      el = el.offsetParent;
    }

    var iDiminuir = $('listaReg').scrollTop;
    $('digitarMotivo').style.top        = (y-(iDiminuir-10))+"px";

    if (((y-iDiminuir)+$('digitarMotivo').scrollHeight) > document.body.scrollHeight) {
      $('digitarMotivo').style.top      = (y-iDiminuir-($('digitarMotivo').scrollHeight))+"px";
    }

    $('digitarMotivo').style.left       = (x)+"px";
    $('digitarMotivo').style.visibility = 'visible';
    $('motivo').value                   = $('motivo'+sIdEvento).innerHTML;
    $('motivo').focus();
    $('atualizarMotivo').onclick = ( function()
      {
        $('digitarMotivo').style.visibility   = 'hidden';
        $('motivo'+sIdEvento).innerHTML       = $('motivo').value;
        $('descrmotivo'+sIdEvento).innerHTML  = new String($('motivo').value).substr(0,5)+"...";
        $('motivo').value                     = '';
      }
    );
  }


  function js_showHint(obj,sMsg) {


    var el = obj;
    var x  = 0;
    var y  = 0;

    while (el.offsetParent && el.tagName.toUpperCase() != 'BODY') {
      x += el.offsetLeft;
      y += el.offsetTop;
      el = el.offsetParent;
    }

    var sLeft     = (x)+"px";
    var iDiminuir = $('listaReg').scrollTop;

    var sTop = (y-(iDiminuir-20))+"px";

    if ((y-iDiminuir) > document.body.scrollHeight ) {
      var sTop = (y-iDiminuir)+"px";
    }

    var oDivHint = document.createElement("DIV");
    oDivHint.setAttribute("id",'testeHint');
	  oDivHint.style.position   = "fixed";
	  oDivHint.style.left       = sLeft;
	  oDivHint.style.top        = sTop;
	  oDivHint.style.zIndex     = "0";
	  oDivHint.style.visibility = 'visible';
	  oDivHint.style.fontFamily = 'Verdana, Arial, Helvetica, sans-serif';
	  oDivHint.style.fontSize   = '15px';
    oDivHint.style.border     = '1px solid';

    oDivHint.innerHTML = ' <table border="0"  style="background-color: #FFFFCC; border-collapse: collapse;"> '
                        +'    <tr> '
                        +'      <td  style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; font-weight: bold;"> '
                        +'        '+sMsg+''
                        +'      </td> '
                        +'    </tr> '
                        +' </table> ';

    document.body.appendChild(oDivHint);
    obj.onmouseout = function(){document.body.removeChild(oDivHint);};

  }

  function js_processar(){

    var aProcessar      = $$('input.chkProc:checked');
    var aDescartar      = $$('input.chkDesc:checked');
    var aListaProcessar = new Array();

    if ( aDescartar.length > 0 ) {
      if ( !confirm('Existem registros a serem descartados, deseja realmente continuar?')) {
        return false;
      }
    }

    aProcessar.each(
      function(oChk){
        var oProcessa           = (oChk.value.urlDecode()).evalJSON();
            oProcessa.lDescarta = false;
        aListaProcessar.push(oProcessa);
      }
    );

    aDescartar.each(
      function(oChk){
        var oDescarta           = (oChk.value.urlDecode()).evalJSON();
            oDescarta.lDescarta = true;
            oDescarta.sMotivo   = $('motivo'+oDescarta.iCnpj+oDescarta.sCodEvento).innerHTML;
        aListaProcessar.push(oDescarta);
      }
    );

    if ( aListaProcessar.length == 0 ) {
      alert('Nenhum registro selecionado!');
      return false;
    }

    js_divCarregando('Aguarde, processando...', 'msgbox');

    oParam.sMethod         = 'processaArquivoMEI';
    oParam.aListaProcessar = aListaProcessar;

    var oAjax   = new Ajax.Request(
                                   sUrlRPC,
                                   {
                                     method: 'post',
                                     parameters: 'json='+Object.toJSON(oParam),
                                     onComplete: js_retornoProcesso
                                   }
                                  );


  }

  function js_retornoProcesso(oAjax){

    var oRetorno = eval("("+oAjax.responseText+")");
    js_removeObj('msgbox');

    alert(oRetorno.sMsg.urlDecode());

    if ( oRetorno.iStatus == 1 ) {
      js_consultaCompetencias();
    }

  }

  function js_consultaCompetencias(){

    js_divCarregando('Aguarde, consultando competências...', 'msgbox');

    $('competencia').innerHTML = '';
    $('listaReg').innerHTML    = '';

    $('processar').disabled = true;
    oParam.sMethod = 'consultaCompetencias';

    var oAjax = new Ajax.Request(
                                  sUrlRPC,
                                  {
                                    method: 'post',
                                    parameters: 'json='+Object.toJSON(oParam),
                                    onComplete: js_retornoConsultaCompetencias
                                  }
                                );

  }

  function js_retornoConsultaCompetencias(oAjax){

    var oRetorno = eval("("+oAjax.responseText+")");
    js_removeObj('msgbox');

    if ( oRetorno.iStatus == 2 ) {

	    alert(oRetorno.sMsg.urlDecode());
	    return false;

    } else {

      var oSelCompetencia = $('competencia');

      if ( oRetorno.aCompetencias.length > 0 ) {

	      oRetorno.aCompetencias.each(
	        function( oCompetencia ) {
	          var sCompetencia = oCompetencia.competencia.urlDecode();
	          var oOpt = new Option(sCompetencia,sCompetencia);
	          oSelCompetencia.appendChild(oOpt);
	        }
	      );

	      oSelCompetencia.disabled = false;

      } else {

        oSelCompetencia.disabled = true;
        var oOpt = new Option('Selecione...',0);
        oSelCompetencia.appendChild(oOpt);
        $('processar').disabled  = true;
        $('pesquisar').disabled  = true;

      }

    }

  }


  function js_pesquisaLogradouro(mostra){
	  if(mostra==true){
	    js_OpenJanelaIframe('',
	                        'db_iframe_logradouro',
	                        'func_ruas.php?funcao_js=parent.js_mostraLogradouro1|j14_codigo|j14_nome',
	                        'Pesquisa Logradouro',
	                        true);
	    $('Jandb_iframe_logradouro').style.zIndex = '999999999';
	  }else{
	     if($('codlogradouro').value != ''){
	        js_OpenJanelaIframe('',
	                            'db_iframe_logradouro',
	                            'func_ruas.php?pesquisa_chave='+$('codlogradouro').value+'&funcao_js=parent.js_mostraLogradouro',
	                            'Pesquisa Logradouro',
	                            false);
	     }else{
	       $('descrlogradouro').value = '';
	     }
	  }
	}

	function js_mostraLogradouro(chave,erro){
	  $('descrlogradouro').value = chave;
	  if(erro==true){
	    $('codlogradouro').focus();
	    $('codlogradouro').value = '';
	  }
	}

	function js_mostraLogradouro1(chave1,chave2){
	  $('codlogradouro').value = chave1;
	  $('descrlogradouro').value = chave2;
	  db_iframe_logradouro.hide();
	}


  function js_pesquisaBairro(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('',
                          'db_iframe_bairro',
                          'func_bairro.php?funcao_js=parent.js_mostraBairro1|j13_codi|j13_descr',
                          'Pesquisa Bairro',
                          true);
      $('Jandb_iframe_bairro').style.zIndex = '999999999';
    }else{
       if($('codbairro').value != ''){
          js_OpenJanelaIframe('',
                              'db_iframe_bairro',
                              'func_bairro.php?pesquisa_chave='+$('codbairro').value+'&funcao_js=parent.js_mostraBairro',
                              'Pesquisa Bairro',
                              false);
       }else{
         $('descrbairro').value = '';
       }
    }
  }

  function js_mostraBairro(chave,erro){
    $('descrbairro').value = chave;
    if(erro==true){
      $('codbairro').focus();
      $('codbairro').value = '';
    }
  }

  function js_mostraBairro1(chave1,chave2){
    $('codbairro').value = chave1;
    $('descrbairro').value = chave2;
    db_iframe_bairro.hide();
  }


  function js_pesquisaAtividade(mostra,sCnae){

    sCnaeAtividade = sCnae;

    if(mostra==true){
      js_OpenJanelaIframe('',
                          'db_iframe_atividade',
                          'func_atividade.php?funcao_js=parent.js_mostraAtividade1|q03_ativ|q03_descr&mei=true',
                          'Pesquisa Atividade',
                          true);
      $('Jandb_iframe_atividade').style.zIndex = '999999999';
    }else{
       if($('codatividade'+sCnaeAtividade).value != ''){
          js_OpenJanelaIframe('',
                              'db_iframe_atividade',
                              'func_atividade.php?pesquisa_chave='+$('codatividade'+sCnaeAtividade).value+'&funcao_js=parent.js_mostraAtividade&mei=true',
                              'Pesquisa Atividade',
                              false);
       }else{
         $('descratividade'+sCnaeAtividade).value = '';
       }
    }
  }

  function js_mostraAtividade(chave,erro){
    $('descratividade'+sCnaeAtividade).value = chave;
    if(erro==true){
      $('codatividade'+sCnaeAtividade).focus();
      $('codatividade'+sCnaeAtividade).value = '';
    }
  }

  function js_mostraAtividade1(chave1,chave2){
    $('codatividade'+sCnaeAtividade).value   = chave1;
    $('descratividade'+sCnaeAtividade).value = chave2;
    db_iframe_atividade.hide();
  }

  js_consultaCompetencias();

</script>
</html>