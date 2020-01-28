<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: Ambulatorial
$oDaoSauCotasAgendamento->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("vc01_i_unidade");
?>
<form name="form1" method="post" action="">
  <table style='width: 100%;' border="0">
    <tr>
      <td colspan="2">
        <fieldset style='width: 97%;'> <legend><b>Prestadora:</b></legend>
          <table>
            <tr>
             <td><?db_ancora($Ls163_i_upsprestadora,"js_pesquisas163_i_upsprestadora(true);",$db_opcao);?></td>
             <td>
               <?
                 $sSql       = $oDaoUnidades->sql_query("","sd02_i_codigo as cod,descrdepto as desc","descrdepto");
                 $rsUnidades = $oDaoUnidades->sql_record($sSql);
                 $aOptions=array();
                 for ($iInd=0; $iInd < $oDaoUnidades->numrows; $iInd++) {
                     
                   $oUnidade                  = db_utils::fieldsmemory($rsUnidades,$iInd);
                   $aOptions[$oUnidade->cod]  = $oUnidade->desc;
                   $aOptions2[$oUnidade->cod] = $oUnidade->cod;

                 }
                 $sFunc = "js_trocaUnidade ('s163_i_upsprestadora2','s163_i_upsprestadora')";
                 db_select ("s163_i_upsprestadora2",$aOptions2,$Is163_i_upsprestadora,$db_opcao,"onchange=\"$sFunc\"");
                 $sFunc = "js_trocaUnidade ('s163_i_upsprestadora','s163_i_upsprestadora2')";
                 db_select ("s163_i_upsprestadora",$aOptions,$Is163_i_upsprestadora,$db_opcao,"onchange=\"$sFunc\"");
               ?>
             </td>
             <td><?=$Ls163_i_mescomp?></td>
             <td>
               <?
                 $aOptions = array();
                 for ($iMes=1; $iMes <= 12; $iMes++) {
                     
                   $sMes     = db_mes($iMes,2);
                   $aOptions[$iMes] = $sMes;
                        
                 }
                 db_select("s163_i_mescomp",$aOptions,$Is163_i_mescomp,$db_opcao,"");
               ?>
             </td>
             <td><?=$Ls163_i_anocomp?></td>
             <td>
               <?db_input('s163_i_anocomp',4,$Is163_i_anocomp,true,'text',$db_opcao,'');?>
             </td>
             <td>
               <input type="button" name="pesquisar" id="pesquisar" value="Pesquisar" onclick="js_pesquisar();">
             </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td valign="top">
        <fieldset style='width: 95%; height: 100%;'> <legend><b>Ofertado:</b></legend>
          <center>
          <div id="dOfertado" name="dOfertado"></div>
          </center>
        </fieldset>
      </td>
      <td>
        <fieldset style='width: 95%; height: 100%;'> <legend><b>Distribui��o:</b></legend>
          <center>
          <div id="dDistribuido" name="dDistribuido"></div>
          <br>
          <input name="confirmar" id="confirmar" value="confirmar" type="button" onclick="js_confirma()" disabled>
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input name="cancelar" id="cancelar" value="cancelar" disabled
                 type="button" onclick="js_pesquisarUnidades (iEspecMarcado,iProfMarcado)" >
          </center>
        </fieldset>
      </td>
    </tr>
  </table>
</form>
<script>
/* Objetos de controle */
oAgendamentoCotas = function() {

    var me         = this;
    this.iCodEspec = 0;
    this.iEspec    = 0;
    this.sNome     = '';
    this.iCotas    = 0;
    this.iSaldo    = 0;
    this.lMostra   = false;
    this.aProf     = new Array();

    this.addEspec = function (iCodigo, iEspec, sNome, iCotas, iSaldo) {

    	this.iCodEspec = iCodigo;
      this.iEspec    = iEspec;
      this.sNome     = sNome;
      this.iCotas    = iCotas;
      this.iSaldo    = iSaldo;

    }
    this.addProf = function (iCodigo, sNome, iCotas, iSaldo) {

      var oProf         = new Object();
      oProf.iCodigo     = iCodigo;
      oProf.sNome       = sNome;
      oProf.iCotas      = iCotas;
      oProf.iSaldo      = iSaldo;
      iTam              = this.aProf.length;
      this.aProf[iTam] = oProf;

    }
  }
oUnidadeCotas = function() {

  var me          = this;
  this.iCodigo    = 0;
  this.iUnidade   = 0;
  this.sNome      = 0;
  this.iCotas     = 0;
  this.iAgendado  = 0;
  this.iRealisado = 0;
  this.iAusente   = 0;
  this.lAlterado  = false;

  this.addUnidade = function (iCodigo, iUnidade, sNome, iCotas, iDistribuido, iAgendado, iRealizado, iAusente) {

    this.iCodigo         = iCodigo;
    this.iUnidade        = iUnidade;
    this.sNome           = sNome;
    this.iCotas          = iCotas;
    this.iDistribuido    = iDistribuido;
    this.iDistribuidoOld = iDistribuido;
    this.iAgendado       = iAgendado;
    this.iRealizado      = iRealizado;
    this.iAusente        = iAusente;

  }
}
/*
 * Variaveis globais
 */
aEspec        = new Array();
aUnidades     = new Array();
iProximo      = 0;
iEspecMarcado = -1;
iProfMarcado  = -1;

/*
 * Fun��es dos Grids
 */
oDBGridOfertado    = js_cria_datagridOfertado();
oDBGridDistribuido = js_cria_datagridDistribuido(-1);
function js_cria_datagridOfertado() {
  
  oDBGrid                = new DBGrid('gridOfertado');
  oDBGrid.nameInstance   = 'oDBGridOfertado';
  oDBGrid.hasTotalizador = false;
  oDBGrid.setCellWidth(new Array('4%','20%', '40%', '18%', '18%'));
  oDBGrid.setHeight(240);
  oDBGrid.allowSelectColumns(false);

  var aHeader = new Array();
  aHeader[0]  = '&nbsp;';
  aHeader[1]  = 'CBO';
  aHeader[2]  = 'Especialidade';
  aHeader[3]  = 'Cotas';
  aHeader[4]  = 'Saldo';
  oDBGrid.setHeader(aHeader);

  var aAligns = new Array();
  aAligns[0]  = 'center';
  aAligns[1]  = 'left';
  aAligns[2]  = 'left';
  aAligns[3]  = 'center';
  aAligns[4]  = 'center';

  oDBGrid.setCellAlign(aAligns);
  oDBGrid.show($('dOfertado'));
  oDBGrid.clearAll(true);

  return oDBGrid;

}
function js_cria_datagridDistribuido(iprof) {

  oDBGrid                = new DBGrid('gridDistribuido');
  oDBGrid.nameInstance   = 'oDBGridDistribuido';
  oDBGrid.hasTotalizador = false;
  oDBGrid.setCellWidth(new Array('14%', '25%', '15%', '15%','15%','15%'));
  oDBGrid.setHeight(240);
  oDBGrid.allowSelectColumns(true);

  var aHeader   = new Array();
  iX            = 0;
  aHeader[iX++] = 'C�digo';
  aHeader[iX++] = 'UPS';
  if (iProfMarcado > -1) {
    aHeader[iX++] = 'Cotas';
  }
  aHeader[iX++] = 'Distribu�do';
  aHeader[iX++] = 'Agendado';
  aHeader[iX++] = 'Realizado';
  aHeader[iX++] = 'Ausente';
  oDBGrid.setHeader(aHeader);

  var aAligns = new Array();
  aAligns[0]  = 'center';
  aAligns[1]  = 'left';
  aAligns[2]  = 'left';
  aAligns[3]  = 'left';
  aAligns[4]  = 'left';
  aAligns[5]  = 'left';
  if (iProfMarcado > -1) {
    aAligns[6]  = 'left';
  }
      
  oDBGrid.setCellAlign(aAligns);
  oDBGrid.show($('dDistribuido'));
  oDBGrid.clearAll(true);

  return oDBGrid;

}
function js_pesquisar(){
  
  $('confirmar').disabled = true;
  $('cancelar').disabled  = true;    
  if ($F('s163_i_anocomp').trim() == '') {
    
    alert('Entre com o ano da competencia!');
    return false;
    
  }
  var oParam            = new Object();
  oParam.exec           = 'getCotasEspecialidades';
  oParam.iUpsPrestadora = $F('s163_i_upsprestadora');
  oParam.iMescomp       = $F('s163_i_mescomp');
  oParam.iAnocomp       = $F('s163_i_anocomp');
  js_webajax(oParam, 'js_retornoPesquisar','sau4_ambulatorial.RPC.php');

}

function js_retornoPesquisar(oRetorno){
  oRetorno = eval("("+oRetorno.responseText+")");

  aUnidades     = new Array();
  iEspecMarcado = -1;
  iProfMarcado  = -1;
  oDBGridOfertado.clearAll(true);
  oDBGridDistribuido.clearAll(true);
  if (oRetorno.iStatus == 0) {

	aEspec = new Array();
    oDBGridOfertado.clearAll(true);
    oDBGridOfertado.renderRows();
    oDBGridDistribuido.clearAll(true);
    oDBGridDistribuido.renderRows();
    message_ajax(oRetorno.sMessage.urlDecode());
    return false;

  }
  aEspec   = oRetorno.aEspec;
  iProximo = oRetorno.iProximo;
  js_rendereiza();

}
function js_rendereiza(){

  oDBGridOfertado.clearAll(true);
  var aLinha = new Array();
  iLinhasAbertas = -1;
  iLinhaMarcada  = -1;
  for (iX=0; iX < aEspec.length; iX++) {

    if(aEspec[iX].lMostra){
      sValor = "-";
    } else {
      sValor = "+";
    }
    aLinha[0] = '<b><spam style="cursor: hand;" onclick="js_mostra('+iX+')">'+sValor+'</spam></b>';
    sJsFunc1  = '<spam style="cursor: hand;" onclick="js_pesquisarUnidades('+iX+',-1)">';
    sJsFunc2  = '</spam>'; 
    aLinha[1] = sJsFunc1+aEspec[iX].iEspec+sJsFunc2;
    aLinha[2] = sJsFunc1+aEspec[iX].sNome.urlDecode()+sJsFunc2;
    aLinha[3] = aEspec[iX].iCotas;
    aLinha[4] = aEspec[iX].iSaldo;
    oDBGridOfertado.addRow(aLinha);
    iLinhasAbertas++;
    if (iEspecMarcado == iX && iProfMarcado == -1) {
        iLinhaMarcada = iLinhasAbertas;
    }
    if (aEspec[iX].lMostra == true) {
      for (iY=0; iY < aEspec[iX].aProf.length; iY++) {

        aLinha[0] = '';
        aLinha[1] = '';
        sJsFunc1  = '<spam style="cursor: hand;" onclick="js_pesquisarUnidades('+iX+','+iY+')">';
        sJsFunc2  = '</spam>';
        aLinha[2] = sJsFunc1+aEspec[iX].aProf[iY].iCodigo+'-'+aEspec[iX].aProf[iY].sNome.urlDecode()+sJsFunc2;
        aLinha[3] = aEspec[iX].aProf[iY].iCotas;
        aLinha[4] = aEspec[iX].aProf[iY].iSaldo;
        oDBGridOfertado.addRow(aLinha);
        iLinhasAbertas++;
        if (iEspecMarcado == iX && iProfMarcado == iY) {
          iLinhaMarcada = iLinhasAbertas;
        }

      }

    }

  }
  oDBGridOfertado.renderRows();
  if (iLinhaMarcada > -1) {
    $('gridOfertadorowgridOfertado'+iLinhaMarcada).className = 'classMarcado';
  }
}
function js_mostra(iN) {

  if (aEspec[iN].lMostra == true) {
    aEspec[iN].lMostra= false;
  } else {
    aEspec[iN].lMostra= true;
  }
  js_rendereiza();

}
function js_pesquisarUnidades (iEspec,iProf) {

  iEspecMarcado         = iEspec;
  iProfMarcado          = iProf;
  js_rendereiza();
  var oParam            = new Object();
  oParam.exec           = 'getCotasUnidades';
  oParam.iUpsPrestadora = $F('s163_i_upsprestadora');
  oParam.iMescomp       = $F('s163_i_mescomp');
  oParam.iAnocomp       = $F('s163_i_anocomp');
  oParam.iEspecialidade = aEspec[iEspec].iEspec;
  oParam.iCodEspec      = aEspec[iEspec].iCodEspec;
  if (iProf > -1) {
    oParam.iProfissional  = aEspec[iEspec].aProf[iProf].iCodigo;
  }else{
    oParam.iProfissional  = -1;
  }
  js_webajax(oParam, 'js_retornoPesquisarUnidades', 'sau4_ambulatorial.RPC.php');

}
function js_retornoPesquisarUnidades(oRetorno){
  oRetorno = eval("("+oRetorno.responseText+")");
  
  oDBGridDistribuido.clearAll(true);
  if (oRetorno.iStatus == 0) {
      
    $('confirmar').disabled = true;
    $('cancelar').disabled  = true;
    oDBGridOfertado.renderRows();
    message_ajax(oRetorno.sMessage.urlDecode());
    return false;
      
  }
  oDBGridDistribuido = js_cria_datagridDistribuido(iProfMarcado);
  aUnidades = oRetorno.aSolicitantes;
  js_rendereizaUnidades();
}
function js_rendereizaUnidades() {

  if (iProximo == 0) {

    sDisabled               = '';
    $('confirmar').disabled = false;
    $('cancelar').disabled  = false;

  } else {

    sDisabled               = 'disabled'; 
    $('confirmar').disabled = true;
    $('cancelar').disabled  = true;
    
  }
  oDBGridDistribuido.clearAll(true);
  var aLinha = new Array();
  for (iX=0; iX < aUnidades.length; iX++) {

    iY           = 0;
    aLinha[iY++] = aUnidades[iX].iCodigo;
    aLinha[iY++] = aUnidades[iX].sNome.urlDecode();
    if (iProfMarcado > -1) {
      aLinha[iY++] = aUnidades[iX].iCotas;
      sFunc        = 'js_atualiza('+iX+',this,'+aUnidades[iX].iCotas+')';
    } else {
      sFunc = 'js_atualiza('+iX+',this,0)';
    }
    
    iTam          = iY++;
    aLinha[iTam]  = '<input type="text" id="iItem'+iX+'" value="'+aUnidades[iX].iDistribuido+'" ';
    aLinha[iTam] += ' size="5" '+sDisabled+' onChange="'+sFunc+'" >';
    aLinha[iY++]  = aUnidades[iX].iAgendado;
    aLinha[iY++]  = aUnidades[iX].iRealizado;
    aLinha[iY++]  = aUnidades[iX].iAusente;
    oDBGridDistribuido.addRow(aLinha);

  }
  oDBGridDistribuido.renderRows();

}
function js_atualiza(iNum, oCota, iDistribuido) {

  //verifica se o valor � valido
  if (isNaN(oCota.value) || oCota.value == '' || parseInt(oCota.value, 10) < 0) {
        
    alert('Valor Invalido!');
    oCota.value = aUnidades[iNum].iDistribuido;
    return false;
        
  }
  //verifica se o valor n�o � menor o que ja foi agendado
  if ( parseInt(oCota.value, 10) < aUnidades[iNum].iAgendado) {

    alert('Valor informado n�o pode ser menor que o agendado!');
    oCota.value = aUnidades[iNum].iDistribuido;
    return false;

  }

  //verifica se o valor n�o � menor o que ja foi distribuidoa para UPS
  if (iProfMarcado > -1) {
    if ( parseInt(oCota.value, 10) > iDistribuido) {

      alert('Valor informado n�o pode ser maior que o disponivel para a unidade!');
      oCota.value = aUnidades[iNum].iDistribuido;
      return false;

    }
  }

  //Soma os valores alterados sejam eles positivos ou negativos
  iValorAlterado = 0;
  for (iX=0; iX < aUnidades.length; iX++) {

    if (iX == iNum) {
      iValorAlterado = iValorAlterado+(parseInt(oCota.value, 10)-aUnidades[iNum].iDistribuidoOld);
    }
    iValorAlterado = iValorAlterado+(aUnidades[iX].iDistribuido-aUnidades[iX].iDistribuidoOld);

  }
  
  if (iProfMarcado > -1) {
    if (aEspec[iEspecMarcado].aProf[iProfMarcado].iSaldo < aEspec[iEspecMarcado].iSaldo) {
      iSaldo = aEspec[iEspecMarcado].aProf[iProfMarcado].iSaldo;
    } else {
      iSaldo = aEspec[iEspecMarcado].iSaldo;
    }
  } else {
    iSaldo = aEspec[iEspecMarcado].iSaldo;
  }

  if ( (iSaldo-iValorAlterado) < 0) {

    alert('Saldo insuficiente!');
    oCota.value = aUnidades[iNum].iDistribuido;
    return false;

  } else {

    aUnidades[iNum].iDistribuido = parseInt(oCota.value, 10);
    aUnidades[iNum].lAlterado    = true;
    js_rendereizaUnidades();

  }
  
}

function js_confirma() {

  iAlterado = 0;
  for (iX=0; iX < aUnidades.length; iX++) {
    if (aUnidades[iX].lAlterado == true) {
      iAlterado = 1;
    }
  }
  if (iAlterado == 0) {

    alert('Nenhum valor lan�ado!');
    return false;

  }
  for (iX=0; iX < aUnidades.length; iX++) {
    aUnidades[iX].sNome = '';
  }

  var oParam            = new Object();
  oParam.exec           = 'saveCotas';
  oParam.iUpsPrestadora = $F('s163_i_upsprestadora');
  oParam.iMescomp       = $F('s163_i_mescomp');
  oParam.iAnocomp       = $F('s163_i_anocomp');
  oParam.iEspecialidade = aEspec[iEspecMarcado].iEspec;
  oParam.iCodEspec      = aEspec[iEspecMarcado].iCodEspec;
  if (iProfMarcado > -1) {
    oParam.iProf = aEspec[iEspecMarcado].aProf[iProfMarcado].iCodigo;
  } else {
    oParam.iProf = -1;
  }
  oParam.aUnidades      = aUnidades;

  js_webajax(oParam, 'js_retornoconfirma','sau4_ambulatorial.RPC.php');

}
function js_retornoconfirma(oRetorno){
  oRetorno = eval("("+oRetorno.responseText+")");
  if (oRetorno.iStatus == 0) {
    message_ajax(oRetorno.sMessage.urlDecode());
  } else {
    
    message_ajax(oRetorno.sMessage.urlDecode());
    js_pesquisar();
    
  }
}
/*
 * BUSCAR UPS 
 */
function js_pesquisas163_i_upsprestadora(){
  js_OpenJanelaIframe('','db_iframe_unidades',
                      'func_unidades.php?funcao_js=parent.js_mostraunidade|sd02_i_codigo','Pesquisa',true);
}

/*
 * MOSTRAR UPS
 */ 
function js_mostraunidade(chave1) {

  for (iInd=0; iInd < $('s163_i_upsprestadora').length; iInd++) {
    if ($('s163_i_upsprestadora').options[iInd].value == chave1) {
          
      $('s163_i_upsprestadora').selectedIndex  = iInd;
      $('s163_i_upsprestadora2').selectedIndex = iInd;
      db_iframe_unidades.hide();
      return true;
      
    }
  }
  db_iframe_unidades.hide();

}
function js_trocaUnidade (campo, campoAlvo) {
  $(campoAlvo).selectedIndex = $(campo).selectedIndex;
}
/*  */
</script>