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

$clrotulo  = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
?>
<center>
  <form name="form1" method="post" action="">
    <fieldset style="width: 350px">
      <legend>
        <b>Conferência de Slips</b>
      </legend>
      <table align="center">
        <tr>
          <td align="left" nowrap>
            <b>Ano / Mês :</b>
          </td>
          <td>
            <?
              $anofolha = db_anofolha();
              db_input('anofolha',4,$IDBtxt23,true,'text',2,"onChange='js_validaTipoPonto()'");
            ?>
            &nbsp;/&nbsp;
            <?
              $mesfolha = db_mesfolha();
              db_input('mesfolha',2,$IDBtxt25,true,'text',2,"onChange='js_validaTipoPonto()'");
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <b>Ponto:</b>
          </td>
          <td>
           <?
             $aSigla = array( "td"=>"Todos",
                              "r14"=>"Salário",
                              "r48"=>"Complementar",
                              "r35"=>"13o. Salário",
                              "r20"=>"Rescisão",
                              "r22"=>"Adiantamento");
             
             db_select('ponto',$aSigla,true,4," style='width: 100%' onChange='js_validaTipoPonto()'");
           ?>
          </td>
        </tr>
        <tr id='linhaComplementar' style='display:none'>
        </tr>
        <tr>
          <td>
            <b>Ordem:</b>
          </td>
          <td>
           <?
             $aOrdem = array( "rc"=>"Recurso",
                              "tf"=>"Tipo de Folha");
             
             db_select('ordem',$aOrdem,true,4," style='width: 100%'");
           ?>
          </td>
        </tr>
        <tr id='erroComplementar' style='display:none'>
        </tr>
      </table> 
    </fieldset>
    <table>  
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align = "center"> 
          <input name="gera" id="gera" type="button" value="Gerar Relatório" onClick="return js_verifica();">
        </td>
      </tr>
    </table>
  </form>
</center> 
<script>

 var sUrl = 'pes1_rhempenhofolhaRPC.php';
  
 function js_consultaPontoComplementar(){
 
   js_divCarregando('Consultando ponto complementar...','msgBox');
   js_bloqueiaTela(true);
   
   var sQuery  = 'sMethod=consultaPontoComplementar';
       sQuery += '&iAnoFolha='+$F('anofolha');
       sQuery += '&iMesFolha='+$F('mesfolha');
       sQuery += '&sSigla='+$F('ponto');
       sQuery += '&lNaoExibeComplementarZero=true';      
   
   var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoPontoComplementar
                                          }
                                  );      
 
 }

 function js_retornoPontoComplementar(oAjax){

   js_removeObj("msgBox");
   js_bloqueiaTela(false);
   
   var aRetorno = eval("("+oAjax.responseText+")");
   var sExpReg  = new RegExp('\\\\n','g');
    
  
   if ( aRetorno.lErro ) {
     alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
     return false;
   }

   var sLinha          = "";
   var sLinhaErro      = "";
   var iLinhasSemestre = aRetorno.aSemestre.length;
   
   if ( iLinhasSemestre > 0 ) {
   
   
     sLinha += " <td align='left' title='Nro. Complementar'>                  ";
     sLinha += "   <strong>Nro. Complementar:</strong>                        ";
     sLinha += " </td>                                                        ";
     sLinha += " <td>                                                         ";
     sLinha += "   <select id='semestre' name='semestre' style='width: 100%'> ";
     sLinha += "     <option value = ''>Todos</option>                        ";
     
     for ( var iInd=0; iInd < iLinhasSemestre; iInd++ ) {
       with( aRetorno.aSemestre[iInd] ){
         sLinha += " <option value = '"+semestre+"'>"+semestre+"</option>";
       }  
     }
     
     sLinha += " </td>                                                            ";
   
     $('linhaComplementar').innerHTML     = sLinha;
     $('linhaComplementar').style.display = '';
     $('erroComplementar').style.display  = 'none';
   
   } else {
   
     alert('Sem complementar encerrada para o período informado.');
   
     $('erroComplementar').innerHTML      = sLinhaErro;
     $('erroComplementar').style.display  = '';
     $('linhaComplementar').style.display = 'none';

     return false;
   
   }

 }

 function js_validaTipoPonto(){
 
   if ( $F('ponto') == 'r48') {
     js_consultaPontoComplementar();
   } else {
   
     $('linhaComplementar').style.display = 'none';
     $('erroComplementar').style.display  = 'none';
   }
   
 }
 
 function js_verifica(){
 
   if ( $F('anofolha') == '' || $F('mesfolha') == '' ) {
     alert('Ano / Mês não informado!');
     return false;
   }
   
   var iComplementar = '';
   if ($F('ponto') == 'r48') {

     sMostrarCompl = $('linhaComplementar').style.display;
     
     if (sMostrarCompl != 'none') {
       
       iComplementar = $('semestre').value;
       
     } else {

       alert('Sem complementar encerrada para o período informado.');
       
       return false;  

     }
     
   }
   
   var sUrl  = 'anofolha='+$F('anofolha');
       sUrl += '&mesfolha='+$F('mesfolha');
       sUrl += '&ponto='+$F('ponto');
       sUrl += '&complementar='+iComplementar;
       sUrl += '&ordem='+$F('ordem');

    var sParam = 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ';
    var jan    = window.open('pes2_conferenciaslips002.php?'+sUrl,'',sParam);
        jan.moveTo(0,0);    
 
 }
 
 function js_bloqueiaTela(lBloq){
 
   if ( lBloq ) {
     $('anofolha').disabled = true;         
     $('mesfolha').disabled = true;
     $('ponto').disabled    = true;
     $('gera').disabled     = true;
     
     if ($F('ponto') == 'r48') {
       if ($('semestre')) {
         $('semestre').disabled = true;
       } 
     }     
     
   } else {
     $('anofolha').disabled = false;         
     $('mesfolha').disabled = false;
     $('ponto').disabled    = false;
     $('gera').disabled     = false;
     
     if ($F('ponto') == 'r48') {
       if ($('semestre')) {
         $('semestre').disabled = false;
       }
     }
        
   }
 
 }
</script>