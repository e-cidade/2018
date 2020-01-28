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

require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_usuariosonline.php';
require_once 'classes/db_folha_classe.php';
require_once 'classes/db_selecao_classe.php';
require_once 'classes/db_gerfsal_classe.php';
require_once 'classes/db_gerfadi_classe.php';
require_once 'classes/db_gerffer_classe.php';
require_once 'classes/db_gerfres_classe.php';
require_once 'classes/db_gerfs13_classe.php';
require_once 'classes/db_gerfcom_classe.php';
require_once 'classes/db_gerffx_classe.php';
require_once 'dbforms/db_funcoes.php';

db_postmemory($HTTP_POST_VARS);

$clfolha   = new cl_folha;
$clselecao = new cl_selecao;
$clgerfsal = new cl_gerfsal;
$clgerfadi = new cl_gerfadi;
$clgerffer = new cl_gerffer;
$clgerfres = new cl_gerfres;
$clgerfs13 = new cl_gerfs13;
$clgerfcom = new cl_gerfcom;
$clgerffx  = new cl_gerffx;
$clrotulo  = new rotulocampo;

$clrotulo->label('r90_valor');
$clrotulo->label('r48_semest');

$db_opcao = 1;
$db_botao = true;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <form name="form1" method="post" action="">
      <center>
      
     <table style="margin-top:10px;">
     <tr><td>
      
      <fieldset>
      <legend><b>Consignações</b></legend>
      
      <table border="0">
       
       	<tr>
          <td align="right"><strong>Totaliza por Recurso:</strong>
          </td>
          <td>
            <?
            $arr_tipo = array("s"=>"Sim", "n"=>"Não");
            db_select('totaliza',$arr_tipo,true,4,'','totaliza');
            ?>
          </td>
        </tr>
        <tr>
          <td align="right"><strong>Consignações:</strong>
          </td>
          <td>
            <?
            $arr_consig = array("0"=>"Todas", "1"=>"Somente Empenhadas", "2"=>"Somente Extra");
            db_select('consig',$arr_consig,true,4);
            ?>
          </td>
        </tr>
        <tr>
           <td colspan="2" align="center">
          
          <?          
	          $aPontos = Array("r14" =>"Salário",
										       	 "r22" =>"Adiantamento",
										       	 "r20" =>"Rescisão",
										       	 "r35" =>"13o. Salário",
										       	 "r48" =>"Complementar" );
	 
	          db_multiploselect("valor", "descr", "", "", $aPontos, null, 6, 150, '', '', 'false','js_validaTipoPonto();');
          ?>
          
          </td>
        </tr>
        
        <tr style="display:none" id="linhaComplementar"></tr>
        
        <tr>
          <td>
        <?
        include("dbforms/db_classesgenericas.php");
        $geraform = new cl_formulario_rel_pes;
        if(!isset($anofolha) || (isset($anofolha) && trim($anofolha) == "")){
          $anofolha = db_anofolha();
        }
        if(!isset($mesfolha) || (isset($mesfolha) && trim($mesfolha) == "")){
          $mesfolha = db_mesfolha();
        }

        $geraform->usarubr = true;
        $geraform->selrubr = true;
        $geraform->onchpad = true;
        $geraform->gera_form($anofolha,$mesfolha);
        ?>
          </td>
        </tr>
        
        <tr>
          <td nowrap align="left">
          
          <?
                    
          $aux                                  = new cl_arquivo_auxiliar;
          $aux->cabecalho                       = "<strong>RECURSOS Selecionados</strong>";
          $aux->codigo                          = "o15_codigo";
          $aux->descr                           = "o15_descr";
          $aux->nomeobjeto                      = "recursos";
          $aux->funcao_js                       = 'js_geraform_mostrarec';
          $aux->funcao_js_hide                  = 'js_geraform_mostrarec1';
          $aux->func_arquivo                    = "func_orctiporec.php";
          $aux->nomeiframe                      = "db_iframe_orctiporec";
          $aux->executa_script_apos_incluir 		= "document.form1.o15_codigo.focus();";
          $aux->executa_script_lost_focus_campo = "js_insSelectrecursos()";
          $aux->executa_script_change_focus 		= "document.form1.o15_codigo.focus();";
          $aux->mostrar_botao_lancar            = false;
          $aux->db_opcao                        = 2;
          $aux->tipo                            = 2;
          $aux->top                             = 20;
          $aux->linhas                          = 8;
          $aux->vwidth                          = "400";
          $aux->tamanho_campo_descricao         = 35;
          $aux->funcao_gera_formulario();
          
          ?>
          
          </td>
        </tr>
        
      </table>
      
      </fieldset>
      
      </td></tr>
      </table>
      
      </center>
      <input name="incluir" type="button" id="db_opcao" onclick="js_enviardados();" value="Gerar">
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

$('anofolha').setAttribute('maxlength','4');
$('mesfolha').setAttribute('maxlength','2');

$('fieldset_selrubri').style.width = '400px';
oDBToogleRubricas = new DBToogle('fieldset_selrubri',false);

$('fieldset_recursos').style.width = '400px';
oDBToogleRecursos = new DBToogle('fieldset_recursos',false);

var sUrl = 'pes1_rhempenhofolhaRPC.php';
var lExisteComplementar = false;
function js_consultaPontoComplementar(){
 
  js_divCarregando('Consultando ponto complementar...','msgBox');
     
  var sQuery  = 'sMethod=consultaPontoComplementar';
      sQuery += '&iAnoFolha='+$F('anofolha');
      sQuery += '&iMesFolha='+$F('mesfolha');
      sQuery += '&sSigla=r48';
      sQuery += '&lNaoExibeComplementarZero=true';
   
  var oAjax   = new Ajax.Request( sUrl, {
                                           method: 'post', 
                                           parameters: sQuery,
                                           asynchronous:false, 
                                           onComplete: js_retornoPontoComplementar
                                          }
                                  );
}

function js_retornoPontoComplementar(oAjax){
   
   js_removeObj("msgBox");
      
   var aRetorno = eval("("+oAjax.responseText+")");
   var sExpReg  = new RegExp('\\\\n','g');
   
    if ( aRetorno.lErro ) {
     alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
     return false;
   }

   var sLinha          = "";
   var iLinhasSemestre = aRetorno.aSemestre.length;
   
   if ( iLinhasSemestre > 0 ) {
   
   
     sLinha += " <td align='right' title='Nro. Complementar'>";
     sLinha += "   <strong>Nro. Complementar:</strong>       ";
     sLinha += " </td>                                       ";
     sLinha += " <td>                                        ";
     sLinha += "   <select id='semestre' name='semestre'>    ";
     sLinha += "     <option value = ''>Todos</option>       ";
     
     for ( var iInd=0; iInd < iLinhasSemestre; iInd++ ) {
       with( aRetorno.aSemestre[iInd] ){
         sLinha += " <option value = '"+semestre+"'>"+semestre+"</option>";
       }  
     }
     
     sLinha += " </td>                                       ";
     lExisteComplementar = true;
   } else {

     alert('Sem complementar encerrada para o período informado.');
     lExisteComplementar = false;
     return false;
   
   }
   
   $('linhaComplementar').innerHTML     = sLinha;
   $('linhaComplementar').style.display = '';

 }


function js_enviardados(){
  
  if(document.form1.anofolha.value == ""){
    alert("Informe o ano a ser pesquisado.");
    document.form1.anofolha.focus();
  }else if(document.form1.mesfolha.value == ""){
    alert("Informe o mês a ser pesquisado.");
    document.form1.mesfolha.focus();
  }else{
    
    stringretorno = "?ano=" + document.form1.anofolha.value;
    stringretorno+= "&mes=" + document.form1.mesfolha.value;
    stringretorno+= "&totaliza=" + document.form1.totaliza.value;
    stringretorno+= "&consig=" + document.form1.consig.value;
    
    stringretorno+= "&ponts=";

    virstrretorno = "";

    semestre      = '';
    
    for(i=0;i<document.form1.objeto2.length;i++){

      stringretorno += virstrretorno+document.form1.objeto2.options[i].value;

      virstrretorno  = ",";

      if (document.form1.objeto2.options[i].value == 'r48') {

        semestre = '&semestre='+document.form1.semestre.value;
        
      }
            
    }

    stringretorno += semestre;
    
    stringretorno += "&rubrs=";

    for (i=0;i<document.form1.selrubri.length;i++) {

      stringretorno+= document.form1.selrubri.options[i].value;

      if ( i < (document.form1.selrubri.length - 1) ) {

        stringretorno+= ",";
      }
    }
    
    stringretorno+= "&recrs=";

		for (i=0;i<document.form1.recursos.length;i++) {

			stringretorno += document.form1.recursos.options[i].value;

			if ( i < ( document.form1.recursos.length - 1) ) {
				stringretorno += ",";
			}
    }

		if (document.form1.objeto2.length == 0) {
		  alert('Selecione o(s) tipo(s) de ponto(s).');
		  return false;
		}

		jan = window.open('pes2_consigpag002.php' + stringretorno,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    jan.moveTo(0,0);
  }
}

function js_validaTipoPonto(){

	var bR48 = false;
  
	for (i=0; i < document.form1.objeto2.length; i++) {

    if (document.form1.objeto2.options[i].value == 'r48') {
      
      document.form1.objeto2.options[i].selected = true;
      
      js_consultaPontoComplementar();
      
      if (!lExisteComplementar) {
        
        js_db_multiploselect_incluir_item(document.form1.objeto2, document.form1.objeto1);
        
      }
      
      bR48 = true;
      
    } else {
      
      document.form1.objeto2.options[i].selected = false;
      
    }
		
	}

	if (bR48) {
	  $('linhaComplementar').style.display = '';
	}	else {
	  $('linhaComplementar').style.display = 'none';
	}
	
}

document.form1.seltodosD.onclick = function () {

  for (i = 0; i < document.form1.objeto1.length; i++) {

    document.form1.objeto1.options[i].selected = true;
    
  }
  
  js_db_multiploselect_incluir_item(document.form1.objeto1, document.form1.objeto2);
}

js_validaTipoPonto();

</script>