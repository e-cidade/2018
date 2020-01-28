<?php

/**
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

session_start();

include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_cfpess_classe.php");

validaUsuarioLogado();

$aRetorno = array();
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]),$aRetorno);

$iMatric = $aRetorno['iMatric'];
$iInstit = $aRetorno['iInstit'];

$sSqlCalculoAnos = " select distinct rh02_anousu,rh02_anousu 
                       from rhpessoalmov
                            left join rhpesrescisao on rh05_seqpes = rh02_seqpes 
                      where rh02_regist  = {$iMatric}
                        and rh02_instit  = {$iInstit}
                        and rh05_seqpes is null
                        and case when rh02_anousu = fc_anofolha({$iInstit})
                                  and rh02_mesusu = fc_mesfolha({$iInstit}) then false else true end
                      order by rh02_anousu desc ";

if (cl_cfpess::verificarUtilizacaoEstruturaSuplementar()) {
  $sSqlCalculoAnos = " select distinct rh02_anousu,rh02_anousu 
                       from rhpessoalmov
                            left  join rhpesrescisao    on rh05_seqpes = rh02_seqpes 
                            inner join rhfolhapagamento on rh141_instit = rh02_instit
                                                       and rh141_anousu = rh02_anousu
                                                       and rh141_mesusu = rh02_mesusu
                                                       and rh141_aberto = false
                      where rh02_regist  = {$iMatric}
                        and rh02_instit  = {$iInstit}
                        and rh05_seqpes is null
                      order by rh02_anousu desc ";
}


$rsCalculoAnos   = db_query($sSqlCalculoAnos);
$iNroCalculoAnos = pg_num_rows($rsCalculoAnos);                        


?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="config/estilos.css"        rel="stylesheet" type="text/css">
<link href="config/portalservidor.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="scripts/scripts.js"  ></script>
<script language="JavaScript" src="scripts/strings.js"  ></script>
<script language="JavaScript" src="scripts/db_script.js"></script>
<script language="JavaScript" src="scripts/prototype.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <?mens_OnHelp()?>>
  <form name="form1" method="post"  target="iframeFichaFinanceira" >
    <table align="center" width="100%">
      <tr>  
        <td class="tituloForm">FICHA FINANCEIRA</td>
      </tr>
      <tr>  
        <td>
          <fieldset>
          <?
           
         if ( $iNroCalculoAnos > 0 ) {
          
          ?>
          <table  class="tableForm"  align="center">
            <tr>

              <td class="labelForm">
                Ano Base:
              </td>

              <td class="dadosForm">
                <?
                   if ( $iNroCalculoAnos > 0 ) {
                     db_selectrecord('anocalc',$rsCalculoAnos,true,1,'','','','','js_consultaMes()',1);
                   }
                   
                   db_input('iMatric',10,'',true,'hidden',1,'');
                   db_input('iInstit',10,'',true,'hidden',1,'');
                   db_input('iSequencial',10,'',true,'hidden',1,'');
                   db_input('sDescricao',40,'',true,'hidden',1,'');
                   db_input('iCodigo',40,'',true,'hidden',1,'');

                ?>
              </td>

              <td class="labelForm">
                Mês:
              </td>

              <td class="dadosForm">
                <select id="selMes"      name="mescalc" onChange="js_consultaTipo()"></select>
              </td>

              <td class="labelForm">
                Tipo de Folha:
              </td>
              <td class="dadosForm">

                <select id="selTipoCalc" name="tipocalc" onChange="js_limpaFrame();">
                  <option>Selecione...</option>
                </select>              
              </td>
              <td colspan="2">
                <input type="button" name="pesquisar" id="pesquisar" value="Pesquisar"              onClick="js_atualizaFrame()">
                <input type="button" name="imprimir"  id="imprimir"  value="Imprimir Contra Cheque" onClick="js_imprimir()">
              </td>
            </tr>
          </table>
          <?
              
            } else {
          ?>      
          <table  class="tableForm" align="center">
            <tr>
              <td class="labelForm">      
                <b>Nenhum Registro Encontrado</b>
              </td>
            </tr>
          </table>          
          <?
            }
          ?>  
          </fieldset>
        </td>
      </tr>
      <tr>
        <td>
        <!--<iframe id="iframeFichaFinanceira" name="iframeFichaFinanceira" src="fichafinaceira.php" width="100%" height="400" style="border:hidden;"></iframe>-->
          <iframe id="iframeFichaFinanceira" name="iframeFichaFinanceira" src="" width="100%" height="400px;" style="border:hidden;"></iframe>
        </td>
      </tr>
    </table>
  </form>
</body>
<script>
  
  var sUrl = 'portalservidorRPC.php';
    
  function js_consultaMes(){
    $('iframeFichaFinanceira').src = '';
    $('selTipoCalc').innerHTML = '';
    $('pesquisar').disabled = true;
    $('imprimir').disabled  = true;
  
    js_divCarregando('Aguarde...','msgBox');
    
    var sQuery  = 'tipo=consultaMes';
        sQuery += '&matric='+$F('iMatric');
        sQuery += '&anousu='+$F('anocalc');
        sQuery += '&instit='+$F('iInstit');
    var oAjax   = new Ajax.Request( sUrl, {
                                           method: 'post', 
                                           parameters: sQuery, 
                                           onComplete: js_retornoMes
                                          }
                                  );          
  }
  
  function js_retornoMes(oAjax){

    js_removeObj('msgBox');
    
    var aRetorno = eval("("+oAjax.responseText+")");
     
    if ( aRetorno.lErro ) {
      alert(aRetorno.sMsg)
    } else {
      js_montaSelect('selMes',aRetorno.aLista);
    }
    
  } 
  
  function js_consultaTipo(){
  
    $('selTipoCalc').innerHTML = '';
    $('iframeFichaFinanceira').src = '';
    
    js_divCarregando('Aguarde...','msgBox');
    
    var sQuery  = 'tipo=consultaTipoCalc';
        sQuery += '&matric='+$F('iMatric');
        sQuery += '&anousu='+$F('anocalc');
        sQuery += '&instit='+$F('iInstit');
        sQuery += '&mesusu='+$('selMes').value;
    var oAjax   = new Ajax.Request( sUrl, { 
                                           method: 'post', 
                                           parameters: sQuery, 
                                           onComplete: js_retornoTipo
                                          }
                                  );          
  }
  
  function js_retornoTipo(oAjax){

    js_removeObj('msgBox');
    
    var aRetorno = eval("("+oAjax.responseText+")");
     
    if ( aRetorno.lErro ) {
      alert(aRetorno.sMsg)
    } else {
      js_montaSelect('selTipoCalc',aRetorno.aLista);

    }
    
  }  
  var aSequencialFolha = {};

  function js_montaSelect( idObj, aLista ){
  
    var iLinhas = aLista.length;
    var sOpcoes = '';

    $(idObj).innerHTML = '';
    
    if ( iLinhas > 0 ) {
     
      for ( var iInd=0; iInd < iLinhas; iInd++ ) {
        
        oCombos = aLista[iInd];
        with ( aLista[iInd] ) {

          $(idObj).options[iInd]            = new Option();
          $(idObj).options[iInd].value      = codigo; 

          <?php if (cl_cfpess::verificarUtilizacaoEstruturaSuplementar()) { ?>
            if ( idObj == "selTipoCalc" ) {
              $(idObj).options[iInd].sequencial = sequencial; 
              $(idObj).options[iInd].numero     = numero; 
            }
          <?php } ?>
          
          $(idObj).options[iInd].text       = descr.urlDecode();
        }        
      }
      
      $(idObj).options[0].selected = true;
      
      if ( idObj == 'selMes' ) {
        js_consultaTipo();        
      } else {
        $('pesquisar').disabled = false;
        

        if ( $('selTipoCalc').value != 'r20' ) {
          $('imprimir').disabled  = false;
        } else {
          $('imprimir').disabled  = true;
        }
      }
                
    } else {
      $(idObj).innerHTML = '';
    }
  
  }  
  
  
  function js_atualizaFrame(){

    var iIndice = $('selTipoCalc').selectedIndex;
    var oOption = $('selTipoCalc').options[iIndice];
    
    $('iSequencial').setValue(oOption.sequencial);
    $('sDescricao') .setValue(oOption.text);
    $('iCodigo')    .setValue(oOption.numero);

    document.form1.target = 'iframeFichaFinanceira';
    document.form1.action = 'fichafinaceira.php';
    document.form1.submit();
    
  }
   
   
  function js_imprimir(){
  
    var iIndice = $('selTipoCalc').selectedIndex;
    var oOption = $('selTipoCalc').options[iIndice];

    $('iSequencial').setValue(oOption.sequencial);
    $('sDescricao') .setValue(oOption.text);
    $('iCodigo')    .setValue(oOption.numero);
  
    document.form1.target = 'iframemitcontracheque';     
    document.form1.action = 'emitecontracheque.php';
    document.form1.submit(); 

  } 
  
  function js_limpaFrame(){
    $('iframeFichaFinanceira').src = '';
  }
  
  
  js_consultaMes();
   
</script>
