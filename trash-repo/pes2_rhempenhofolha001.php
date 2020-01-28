<?php
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

/**
 * 
 * @author I
 * @revision $Author: dbalberto $
 * @version $Revision: 1.9 $
 */
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
?>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("estilos.css");
?>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table align="center" style="padding-top:28px;">
  <tr>
    <td>
      <?
      $clrotulo  = new rotulocampo;
      $clrotulo->label('DBtxt23');
      $clrotulo->label('DBtxt25');
      ?>
      <center>
      	<form name="form1" method="post" action="">
      	  <fieldset style="width: 400px;">
      	    <legend>
      	      <b>Empenhos da folha</b>
      	    </legend>
      	    <table>
      			  <tr>
      			    <td align="left" nowrap >
      			      <b>Ano / Mês :</b>
      			    </td>
      			    <td nowrap>
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
      			     
      			       $aSigla = array( "r14"=>"Salário",
      					                    "r48"=>"Complementar",
      					                    "r35"=>"13o. Salário",
      					                    "r20"=>"Rescisão",
      					                    "r22"=>"Adiantamento");
      			       
      			       db_select('ponto',$aSigla,true,4," style='width: 150px;' onChange='js_validaTipoPonto()'");
      			     ?>
      			    </td>
      		    </tr>
              <tr>
                <td>
                  <b>Tipo:</b>
                </td>
                <td>
                 <?
                 
                   $aTipos = array(
                                   "1" => "Salário        ",
                                   "2" => "Previdência    ",
                                   "3" => "FGTS           ",
                                  );
                   
                   db_select('tipo',$aTipos,true,4," style='width: 150px;' onChange='js_validaTipoGeracao()'");
                 ?>
                </td>
              </tr>
      		    <tr id='linhaComplementar' style='display:none'>
      		    </tr>
              <tr id='tabelasPrevidencia' style='display:none'>
	              <td align="center" colspan="2" >
	               <?
		               $sql  = "select distinct (cast(r33_codtab as integer) - 2) as r33_codtab,              ";
		               $sql .= "                r33_nome                 ";
		               $sql .= "           from inssirf                  ";
		               $sql .= "          where r33_anousu = {$anofolha} "; 
		               $sql .= "            and r33_mesusu = {$mesfolha} ";
		               $sql .= "            and r33_codtab > 2           ";
		               $sql .= "            and r33_instit = ".db_getsession('DB_instit') ;
		               $rsPrev = db_query($sql);
		               
		               db_multiploselect("r33_codtab", "r33_nome", "nselecionados", "selecionados", $rsPrev, array(), 4, 250);
	               ?>
	              </td>
	            </tr>
	            <tr>
	              <td> <b> Mostrar Retenções: </b></td>
	              <td> 
                  <?
                    $sql = "select r11_geraretencaoempenho 
                              from cfpess 
                             where r11_anousu = {$anofolha}
                               and r11_mesusu = {$mesfolha}
                               and r11_instit   = ".db_getsession("DB_instit");
                    $rsRetencao = db_query($sql);
                    $lRetencao  = @db_utils::fieldsMemory($rsRetencao,0)->r11_geraretencaoempenho;
                    $x = array("t"=>"SIM","f"=>"NÃO");
                    db_select('lRetencao',$x,true,1," style='width: 150px;' ");
                  ?>
                </td> 
	            </tr>
      		  </table> 
      	  </fieldset>
      	  <table>  
      		  <tr>
      		    <td align = "center"> 
      		      <input name="gera" id="gera" type="button" value="Processar" onClick="js_emiteRelatorio();">
      		    </td>
      		  </tr>
      	  </table>
      	</form>
      </center>	
    </td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

var sUrl = 'pes1_rhempenhofolhaRPC.php';
 
function js_consultaPontoComplementar(){

  js_divCarregando('Consultando ponto complementar...','msgBox');
  js_bloqueiaTela(true);
  
  var sQuery  = 'sMethod=consultaPontoComplementar';
      sQuery += '&iAnoFolha='+$F('anofolha');
      sQuery += '&iMesFolha='+$F('mesfolha');
      sQuery += '&sSigla='+$F('ponto');   
      sQuery += '&iTipo='+$F('tipo');  
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
  var iLinhasSemestre = aRetorno.aSemestre.length;
  
  if ( iLinhasSemestre > 0 ) {
  
  
    sLinha += " <td align='left' title='Nro. Complementar'>                               ";
    sLinha += "   <strong>Nro. Complementar:</strong>                                     ";
    sLinha += " </td>                                                                     ";
    sLinha += " <td>                                                                      ";
    sLinha += "   <select id='semestre' name='semestre'>                                  ";
    sLinha += "     <option value = ''>Todos</option>                                     ";
    
    for ( var iInd=0; iInd < iLinhasSemestre; iInd++ ) {
      with( aRetorno.aSemestre[iInd] ){
        sLinha += " <option value = '"+semestre+"'>"+semestre+"</option>                  ";
      }  
    }
    
    sLinha += " </td>                                                                     ";
  
  } else {
  
    sLinha += " <td colspan='2' align='center'>                                                 ";
    sLinha += "   <font color='red'>Sem complementar encerrada para o período informado.</font> ";
    sLinha += " </td>                                                                           ";
  
  }
  
  $('linhaComplementar').innerHTML     = sLinha;
  $('linhaComplementar').style.display = '';

}

function js_validaTipoPonto(){

  if ( $F('ponto') == 'r48') {
    js_consultaPontoComplementar();
  } else {
    $('linhaComplementar').style.display = 'none';
  }
  
}

function js_validaTipoGeracao(){

  if ($F('tipo') == 2) {
    $('tabelasPrevidencia').style.display = '';
  } else {
    $('tabelasPrevidencia').style.display = 'none';
  }  
  
}  
 
function js_verifica(){

  if ( $F('anofolha') == '' || $F('mesfolha') == '' ) {
    alert('Ano / Mês não informado!');
    return false;
  }
  
  js_consultaEmpenhos();    

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

function js_getQueryTela() {

  var oParam       = new Object();
  oParam.iAnoFolha = $F('anofolha');
  oParam.iMesFolha = $F('mesfolha');
  oParam.sSigla    = $F('ponto');
  oParam.iTipo     = $F('tipo');        
  oParam.lRetencao = $F('lRetencao');
  if ( $F('ponto') == 'r48' ) {
    if ($('semestre')) {
      oParam.sSemestre = $F('semestre');
    } else {
      alert('Sem complementar encerrada para o período informado.');
      return false;
    }  
  }

  if ( $F('tipo') == 2) {

    var sSelecionados = "";
    var sVirg         = "";
    
    for(var i=0; i<document.form1.selecionados.length; i++){
      sSelecionados += sVirg+document.form1.selecionados.options[i].value;
      sVirg          = ",";
    }
         
    oParam.sPrevidencia = sSelecionados;
  }
          
  return "json="+Object.toJSON(oParam);    

}
 
function js_emiteRelatorio() {
  
  if (js_getQueryTela() === false) {
    return false;
  }

  var sUrl = 'pes2_rhempenhofolha002.php?'+js_getQueryTela();
  oJanela =  window.open(sUrl, '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  oJanela.moveTo(0, 0); 
  
}
</script>