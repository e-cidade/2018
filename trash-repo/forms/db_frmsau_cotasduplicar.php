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
  <fieldset style='width: 90%; height: 100%;'> <legend><b>Duplicar Cotas:</b></legend>
  <table style='width: 99%;' border="0">
    <tr>
      <td colspan="2" align="center">
        <fieldset style='width: 90%;'> <legend><b>Prestadora:</b></legend>
          <table border="0">
            <tr>
              <td colspan="3">
                <?
                  db_ancora($Ls163_i_upsprestadora, "js_pesquisas163_i_upsprestadora(true);", $db_opcao);
                  $sWhere     = " EXISTS (select * from sau_cotasagendamento where s163_i_upsprestadora = "; 
                  $sWhere    .= " sd02_i_codigo)";
                  $sSql       = $oDaoUnidades->sql_query("", "sd02_i_codigo as cod,descrdepto as desc", "descrdepto", 
                                                         $sWhere
                                                        );
                  $rsUnidades = $oDaoUnidades->sql_record($sSql);
                  $aOptions   = array();
                  $aOptions2  = array();
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
            </tr>
            <tr>
              <td style='height:30px;'>
                <br>
                <fieldset style='width: 92%; height:35px;' > <legend><b>Compatência Anterior:</b></legend>
                <table>
                  <tr>
                    <td>
                      <?=$Ls163_i_mescomp?>
                    </td>
                    <td>
                      <?
                        $aOptions = array();
                        for ($iMes=1; $iMes <= 12; $iMes++) {

                          $sMes            = db_mes($iMes,2);
                          $aOptions[$iMes] = $sMes;

                        }
                        db_select("s163_i_mescomp", $aOptions, $Is163_i_mescomp,$db_opcao, "");
                      ?>
                    </td>
                    <td>
                      <?=$Ls163_i_anocomp?>
                    </td>
                    <td>
                      <?db_input('s163_i_anocomp',4,$Is163_i_anocomp,true,'text',$db_opcao,'');?>
                    </td>
                  <tr>
                </table>
                </fieldset>
              </td>
              <td>
                <br>
                <fieldset style='width: 92%; height:35px;'> <legend><b>Próxima Competência:</b></legend>
                <table>
                  <tr>
                    <td>
                      <?=$Ls163_i_mescomp?>
                    </td>
                    <td>
                      <?
                        if ($s163_i_mescomp == 12) {

                          $s163_i_mescomp2 = 1;
                          $s163_i_anocomp2 = $s163_i_anocomp+1;

                        } else {

                          $s163_i_mescomp2 = $s163_i_mescomp+1;
                          $s163_i_anocomp2 = $s163_i_anocomp;

                        }
                        db_select("s163_i_mescomp2",$aOptions,$Is163_i_mescomp,$db_opcao,"");
                      ?>
                    </td>
                    <td>
                      <?=$Ls163_i_anocomp?>
                    </td>
                    <td>
                      <?db_input('s163_i_anocomp2',4,$Is163_i_anocomp,true,'text',$db_opcao,'');?>
                    </td>
                  <tr>
                </table>
                </fieldset>
              </td>
              <td style='height:36px;' valign="bottom">
                <input type="button" name="pesquisar" id="pesquisar" value="Pesquisar" onclick="js_pesquisar();">
              </td>
            </tr>
            <tr>
              <td colspan="3">
                 <fieldset style='width: 92%;'> <legend><b>Especialidades:</b></legend>
                   <div id="dEspec" name="dEspec"></div>
                 </fieldset>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td valign="top" align="center">
        <fieldset style='width: 90%;'> <legend><b>Distribuicao:</b></legend>
          <center>
          <div id="dDistri" name="dDistri"></div>
          </center>
        </fieldset>
      </td>
    </tr>
  </table>
  <input type="button" value="Processar" id="processar" onclick="js_processar()" disabled>
  <input type="button" value="Limpar" id="limpar" onclick="js_limpar()" >
  </fieldset>
</form>
<script>
/* variaveis globais */
var aEspec = new Array();
var aCotas = new Array();

/*
 * Funções dos Grids
 */
oDBGridDistri = jsCriaDataGridDistri();
oDBGridEspec  = jsCriaGridEspec();
function jsCriaDataGridDistri() {
  
  oDBGrid                = new DBGrid('gridDistri');
  oDBGrid.nameInstance   = 'oDBGridDistri';
  oDBGrid.hasTotalizador = false;
  oDBGrid.setCellWidth(new Array('5%','30%', '30%', '20%', '15%'));
  oDBGrid.setHeight(80);
  oDBGrid.allowSelectColumns(false);

  var aHeader = new Array();
  aHeader[0]  = '';
  aHeader[1]  = 'Especialidade';
  aHeader[2]  = 'UPS';
  aHeader[3]  = 'Profissional';
  aHeader[4]  = 'Distribuido';
  oDBGrid.setHeader(aHeader);

  var aAligns = new Array();
  aAligns[0]  = 'center';
  aAligns[1]  = 'left';
  aAligns[2]  = 'left';
  aAligns[3]  = 'left';
  aAligns[4]  = 'center';
  
  oDBGrid.setCellAlign(aAligns);
  oDBGrid.show($('dDistri'));
  sStr = '* Obs: registros em amarelo: cota ja foi duplicada (valor pode ser alterado).';
  oDBGrid.setStatus(sStr);
  oDBGrid.clearAll(true);

  return oDBGrid;

}
function jsCriaGridEspec() {
    
    oDBGrid                = new DBGrid('gridEspec');
    oDBGrid.nameInstance   = 'oDBGridEspec';
    oDBGrid.hasTotalizador = false;
    oDBGrid.setCellWidth(new Array('5%','20%', '35%', '20%','20%'));
    oDBGrid.setHeight(80);
    oDBGrid.allowSelectColumns(false);

    var aHeader = new Array();
    aHeader[0]  = '';
    aHeader[1]  = 'CBO';
    aHeader[2]  = 'Especialidades';
    aHeader[3]  = 'Cotas';
    aHeader[4]  = 'Saldo';
    oDBGrid.setHeader(aHeader);

    var aAligns = new Array();
    aAligns[0]  = 'center';
    aAligns[1]  = 'center';
    aAligns[2]  = 'left';
    aAligns[3]  = 'center';
    aAligns[4]  = 'center';

    oDBGrid.setCellAlign(aAligns);
    oDBGrid.show($('dEspec'));
    sStr = '* Obs: Em vermelho saldo insuficiente';
    oDBGrid.setStatus(sStr);
    oDBGrid.clearAll(true);

    return oDBGrid;

  }
function js_pesquisar() {

  if ($F('s163_i_anocomp').trim() == '' || $F('s163_i_anocomp2').trim() == '') {

    alert('Entre com o ano da competência!');
    return false;

  }
  if ( (parseInt($F('s163_i_anocomp').trim(),10) > parseInt($F('s163_i_anocomp2').trim(),10))
         || 
         (parseInt($F('s163_i_anocomp').trim(),10) == parseInt($F('s163_i_anocomp2').trim(),10)
          && parseInt($F('s163_i_mescomp').trim(),10) >= parseInt($F('s163_i_mescomp2').trim(),10))
       ) {

        if (parseInt($F('s163_i_anocomp').trim(),10) == parseInt($F('s163_i_anocomp2').trim(),10)
            && parseInt($F('s163_i_mescomp').trim(),10) == parseInt($F('s163_i_mescomp2').trim(),10)) {

          alert('Competência anterior não pode ser igual a próxima competência!');
          return false;

        }  
        alert('Compêtencia anterior não pode ser maior que próxima competência!');
        return false;

    }
  var oParam          = new Object();
  oParam.exec         = 'getCotasUnidadesDuplicar';
  oParam.iMescomp     = $F('s163_i_mescomp');
  oParam.iAnocomp     = $F('s163_i_anocomp');
  oParam.iMescompAlvo = $F('s163_i_mescomp2');
  oParam.iAnocompAlvo = $F('s163_i_anocomp2');
  oParam.iUps         = $F('s163_i_upsprestadora');
  js_webajax(oParam, 'js_retornoPesquisar', 'sau4_ambulatorial.RPC.php');
  
}
function js_retornoPesquisar(oRetorno){

  oRetorno = eval("("+oRetorno.responseText+")");
  if (oRetorno.iStatus == 0) {

    aEspec = new Array();
    aCotas = new Array();
    $('processar').disabled = true;
    message_ajax(oRetorno.sMessage.urlDecode());
    return false;

  }
  aEspec = oRetorno.aEspec;
  aCotas = oRetorno.aCotas;

  //Subtrai os valores das cotas que ainda não foram duplicadas
  //Percorre os lançamentos de cotas para unidade
  for (iI=0; iI < aCotas.length; iI++) {
    for (iZ=0; iZ < aEspec.length; iZ++) {
      if (aCotas[iI].iEspec == aEspec[iZ].iEspec && aCotas[iI].iProxima == 0) {

        aEspec[iZ].iSaldo -= aCotas[iI].iDistribuido;
        for (iX=0; iX < aCotas[iI].aProf.length; iX++) {
          for (iY=0; iY < aEspec[iZ].aProf.length; iY++) {

            if (aCotas[iI].aProf[iX].iCodigo == aEspec[iZ].aProf[iY].iCodigo && aCotas[iI].aProf[iY].iProxima == 0) {
              aEspec[iZ].aProf[iY].iSaldo -= aCotas[iI].aProf[iY].iDistribuido;
            }

          }
        }

      }
    }

  }
  js_carregaGids();

}
function js_carregaGids(){

  $('processar').disabled = false;
  var aLinha              = new Array();
  var aLinhasNegativas    = new Array();
  var aLinhascount        = -1;
  oDBGridEspec.clearAll(true);
  for (iX = 0; iX < aEspec.length; iX++) {

    if (aEspec[iX].lMostra == true) {
      sValor = '-';
    } else {
      sValor = '+';
    }
    sfunc     = "js_mostraEspecialidade('"+sValor+"',"+iX+");";
    aLinha[0] = '<div onclick="'+sfunc+'">'+sValor+'<div>';
    aLinha[1] = aEspec[iX].iEspecEst;
    aLinha[2] = aEspec[iX].sEspecDes.urlDecode();
    aLinha[3] = aEspec[iX].iCotas;
    aLinha[4] = aEspec[iX].iSaldo;
    oDBGridEspec.addRow(aLinha);
    aLinhascount++;
    if (aEspec[iX].iSaldo < 0) {
      aLinhasNegativas[aLinhasNegativas.length] = aLinhascount;
    }
    if (aEspec[iX].lMostra == true) {
      for (iY=0; iY < aEspec[iX].aProf.length; iY++) {

        aLinha[0] = '';
        aLinha[1] = '';
        aLinha[2] = aEspec[iX].aProf[iY].iCodigo+' - '+aEspec[iX].aProf[iY].sNome.urlDecode();
        aLinha[3] = aEspec[iX].aProf[iY].iCotas;
        aLinha[4] = aEspec[iX].aProf[iY].iSaldo;
        oDBGridEspec.addRow(aLinha);
        aLinhascount++;
        if (aEspec[iX].aProf[iY].iSaldo < 0) {
          aLinhasNegativas[aLinhasNegativas.length] = aLinhascount;
        }

      }
    }

  }
  oDBGridEspec.renderRows();
  for (iX = 0; iX < aLinhasNegativas.length; iX++) {
    $('gridEspecrowgridEspec'+aLinhasNegativas[iX]).className = 'classNegativo';
  }
  oDBGridDistri.clearAll(true);
  var aLinha           = new Array();
  var aLinhasNegativas = new Array();
  var aLinhasDuplicada = new Array();
  var aLinhascount     = -1;
  for (iX = 0; iX < aCotas.length; iX++) {

    if (aCotas[iX].lMostra == true) {
      sValor = '-';
    } else {
      sValor = '+';
    }
    sfunc     = "js_mostraCotas('"+sValor+"',"+iX+")";
    aLinha[0] = '<div onclick="'+sfunc+'">'+sValor+'<div>';
    aLinha[1] = aCotas[iX].sEspecDes.urlDecode();
    aLinha[2] = aCotas[iX].sUndSoliDes.urlDecode();
    aLinha[3] = '';
    sFunc     = ' onchange="js_alteraValor(this, '+iX+', -1,'+aCotas[iX].iEspecSeq+',-1)" ';
    aLinha[4] = '<input type="text" value="'+aCotas[iX].iDistribuido+'" size="5" style="text-align:right;" '+sFunc+' >';
    oDBGridDistri.addRow(aLinha);
    aLinhascount++;
    if (aCotas[iX].iSaldo < 0) {
      aLinhasNegativas[aLinhasNegativas.length] = aLinhascount;
    }
    if (aCotas[iX].iProxima > 0) {
      aLinhasDuplicada[aLinhasDuplicada.length] = aLinhascount;
    }
    if (aCotas[iX].lMostra == true) {
      for (iY=0; iY < aCotas[iX].aProf.length; iY++) {

        aLinha[0]  = '&nbsp;';
        aLinha[1]  = '&nbsp;';
        aLinha[2]  = '&nbsp;';
        aLinha[3]  = aCotas[iX].aProf[iY].iCodigo+' - '+aCotas[iX].aProf[iY].sNome.urlDecode();
        aLinha[4]  = '<input type="text" value="'+aCotas[iX].aProf[iY].iDistribuido+'" size="5" ';
        sFunc      = ' onchange="js_alteraValor(this,'+iX+','+iY+','+aCotas[iX].iEspecSeq+', ';
        sFunc     += aCotas[iX].aProf[iY].iCodigo+')" ';
        aLinha[4] += ' style="text-align:right;" '+sFunc+' >';
        oDBGridDistri.addRow(aLinha);
        aLinhascount++;
        if (aCotas[iX].aProf[iY].iSaldo < 0) {
          aLinhasNegativas[aLinhasNegativas.length] = aLinhascount;
        }
        if (aCotas[iX].aProf[iY].iProxima > 0) {
          aLinhasDuplicada[aLinhasDuplicada.length] = aLinhascount;
        }

      }

    }

  }
  oDBGridDistri.renderRows();
  for (iX = 0; iX < aLinhasDuplicada.length; iX++) {
    $('gridDistrirowgridDistri'+aLinhasDuplicada[iX]).className = 'classAlterado';
  }
  for (iX = 0; iX < aLinhasNegativas.length; iX++) {
    $('gridDistrirowgridDistri'+aLinhasNegativas[iX]).className = 'classNegativo';
  }

}
function js_mostraEspecialidade (sValor, iLinhas) {

  if (sValor == '-') {
    aEspec[iLinhas].lMostra = false;
  } else {
    aEspec[iLinhas].lMostra = true;
  }
  js_carregaGids();

}
function js_mostraCotas(sValor, iLinhas){

  if (sValor == '-') {
    aCotas[iLinhas].lMostra = false;
  } else {
    aCotas[iLinhas].lMostra = true;
  }
  js_carregaGids();

}

function js_alteraValor(oQuant, iEspec, iProf, iEspecCod, iProfCod) {

  //percorre o grid das especialidades para encontrar o saldo a especialidade alvo e atualiza
  for (iX = 0; iX < aEspec.length; iX++) {

    //if verifica se é a especialidade indicada
    if (iEspecCod == aEspec[iX].iEspecSeq) {

      //if verifica se o valor alterado é de um profissional especifico
      if (iProf > -1) {

        //percorre a lista de profissionais para a especialidade alvo
        for (iY=0; iY < aEspec[iX].aProf.length; iY++) {

          //if verifica se é o profissional alvo
          if (aEspec[iX].aProf[iY].iCodigo == iProfCod) {

            //Este for soma todos os lançamentos dos profissionais se o valor supera o lançamanto da cota informar erro
            iQuantCotasProf = 0;
            for (iZ=0; iZ < aCotas[iEspec].aProf.length; iZ++) {
              if (aCotas[iEspec].aProf[iZ].iCodigo != iProfCod) {
                iQuantCotasProf += parseInt(aCotas[iEspec].aProf[iZ].iDistribuido, 10);
              } else {
                iQuantCotasProf += parseInt(oQuant.value, 10);
              }
            }
            //se a quantidade de cotas lançada para determinado profissional for superar a quantidade distribuida para
            //especialidade alertar o usuario
            if (parseInt(iQuantCotasProf, 10) > parseInt(aCotas[iEspec].iDistribuido, 10)) {

              alert('Quantidade de de cotas para profissional supera quantidade distribuida para especialidade!');
              oQuant.value = aCotas[iEspec].aProf[iProf].iDistribuido;
              return false;

            }
            aEspec[iX].aProf[iY].iSaldo -= parseInt(oQuant.value, 10)-aCotas[iEspec].aProf[iProf].iDistribuido;
            break;

          }
        }
      } else {

        //qundo for diminuir a quantidade de cotas para especialidade tem que verificar se a quantidade não é menor do 
        //que ja foi distribuido para os profissionais
        iQuantCotasProf = 0;
        for (iZ=0; iZ < aCotas[iEspec].aProf.length; iZ++) {
          iQuantCotasProf += parseInt(aCotas[iEspec].aProf[iZ].iDistribuido, 10);
        }
        //se a quantidade de cotas lançada para determinado profissional for superar a quantidade distribuida para
        //especialidade alertar o usuario
        if (parseInt(iQuantCotasProf, 10) > parseInt(oQuant.value, 10)) {

          alert('Quantidade de cotas para especialidade não pode ser inferior a quantidade distribuida para os profissionais!');
          oQuant.value = aCotas[iEspec].iDistribuido;
          return false;

        }
        aEspec[iX].iSaldo -= parseInt(oQuant.value, 10)-parseInt(aCotas[iEspec].iDistribuido, 10);

      }
      break;

    }

  }
  if (iProf == -1) {
    aCotas[iEspec].iDistribuido = parseInt(oQuant.value, 10);
  } else {
    aCotas[iEspec].aProf[iProf].iDistribuido = parseInt(oQuant.value, 10);
  }

  js_carregaGids();

}

function js_processar() {
  
  if ($F('s163_i_anocomp').trim() == '' || $F('s163_i_anocomp2').trim() == '') {

    alert('Entre com o ano da competência!');
    return false;

  }
  if ((parseInt($F('s163_i_anocomp'),10) < 1000 && parseInt($F('s163_i_anocomp'),10)  > 3000)
      && (parseInt($F('s163_i_anocomp2,.'),10) < 1000 && parseInt($F('s163_i_anocomp2'),10)  > 3000)) {

    alert('Entre com um ano valido!');
    return false;

  }
  if ( (parseInt($F('s163_i_anocomp').trim(),10) > parseInt($F('s163_i_anocomp2').trim(),10))
       || 
       (parseInt($F('s163_i_anocomp').trim(),10) == parseInt($F('s163_i_anocomp2').trim(),10)
        && parseInt($F('s163_i_mescomp').trim(),10) >= parseInt($F('s163_i_mescomp2').trim(),10))
     ) {

      if (parseInt($F('s163_i_anocomp').trim(),10) == parseInt($F('s163_i_anocomp2').trim(),10)
          && parseInt($F('s163_i_mescomp').trim(),10) == parseInt($F('s163_i_mescomp2').trim(),10)) {

        alert('Competência anterior não pode ser igual a proxima competência!');
        return false;
      
      }  
      alert('Competência anterior não pode ser maior que proxima competência!');
      return false;

  }

  var oParam          = new Object();
  oParam.exec         = 'duplicarCotas';
  oParam.iMescomp     = $F('s163_i_mescomp');
  oParam.iAnocomp     = $F('s163_i_anocomp');
  oParam.iMescompAlvo = $F('s163_i_mescomp2');
  oParam.iAnocompAlvo = $F('s163_i_anocomp2');
  oParam.iUps         = $F('s163_i_upsprestadora');
  aCotasSend          = new Array();
  oParam.aCotas       = aCotas;
  for (iX=0; iX < aCotas.length; iX++) {

    oParam.aCotas[iX].sEspecDes   = '';
    oParam.aCotas[iX].sUndSoliDes = '';
    if (oParam.aCotas[iX].iDistribuido < 0) {

        alert('Existe especialidade com saldo negativo!');
        return false;

    }
    for (iY=0; iY < aCotas[iX].aProflength; iY++) {

      oParam.aCotas[iX].aProf[iY].sNome = '';
      if (oParam.aCotas[iX].aProf[iY].iDistribuido < 0) {

        alert('Existe especialidade com saldo negativo!');
        return false;

      }

    }

  }
  oParam.aEspec       = aEspec;
  for (iX=0; iX < aEspec.length; iX++) {

    oParam.aEspec[iX].sEspecDes = '';
    for (iY=0; iY < aEspec[iX].aProflength; iY++) {
      oParam.aEspec[iX].aProf[iY].sNome = '';
    }

  }
  js_webajax(oParam, 'js_retornoProcessar', 'sau4_ambulatorial.RPC.php');

}

function js_retornoProcessar(oRetorno) {
  oRetorno = eval("("+oRetorno.responseText+")");

  /* Erro comun */
  if (oRetorno.iStatus == 0) {
    message_ajax(oRetorno.sMessage.urlDecode());
  }

  /* Inconsistencia de saldo atualizar grid de especialidade */
  if (oRetorno.iStatus == 2) {

    sErro  = 'Saldo insuficiente para a especialidade(s) '+oRetorno.sEspec;
    alert(sErro);
    for (iX = 0; iX < aEspec.length; iX++) {

      aEspec[iX][2] = oRetorno.aEspec[iX][2];
      aEspec[iX][3] = oRetorno.aEspec[iX][3];

    }
    js_carregaGids();

  }
  message_ajax(oRetorno.sMessage.urlDecode());
  js_pesquisar();

}
/*
 * BUSCAR UPS 
 */
function js_pesquisas163_i_upsprestadora () {
  js_OpenJanelaIframe('', 'db_iframe_unidades',
                      'func_unidades.php?iIssetCotas=1&funcao_js=parent.js_mostraunidade|sd02_i_codigo', 'Pesquisa',
                      true);
}

/*
 * MOSTRAR UPS
 */
function js_mostraunidade(chave1) {

  for (iInd=0; iInd < $('s163_i_upsprestadora').length; iInd++) {

    if ($('s163_i_upsprestadora').options[iInd].value == chave1) {

      $('s163_i_upsprestadora').selectedIndex = iInd;
      db_iframe_unidades.hide();
      return true;

    }

  }
  db_iframe_unidades.hide();

}
function js_limpar () {

  if (confirm("Ao limpar as informações do formulário, todos os lançamentos serão perdidos.")) {

    aEspec = new Array();
    aCotas = new Array();
    js_carregaGids();
    $('processar').disabled = true;

  }

}
function js_trocaUnidade (campo, campoAlvo) {
  $(campoAlvo).selectedIndex = $(campo).selectedIndex;
}
</script>