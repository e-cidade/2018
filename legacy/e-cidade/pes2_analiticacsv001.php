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
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_gerfcom_classe.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_classesgenericas.php");

$clgerfcom = new cl_gerfcom;
$clrotulo  = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<style>

 .formTable td {
   text-align: left;
  }

</style>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

<form name="form1">

<center>

	<fieldset style="margin-top: 50px; width: 40%">
	<legend style="font-weight: bold;">Folha Analítica / Sintética (CSV) </legend>
	
		<table align="left" class='formTable'>
			  <?
			  if(!isset($tipo)){
			    $tipo = "l";
			  }
			  if(!isset($filtro)){
			    $filtro = "i";
			  }
			  if(!isset($anofolha) || (isset($anofolha) && trim($anofolha) == "")){
			    $anofolha = db_anofolha();
			  }
			  if(!isset($mesfolha) || (isset($mesfolha) && trim($mesfolha) == "")){
			    $mesfolha = db_mesfolha();
			  }
			  
			  
			  $geraform = new cl_formulario_rel_pes;
			
			  $geraform->usaregi = true;                      // PERMITIR SELEÇÃO DE MATRÍCULAS
			  $geraform->usalota = true;                      // PERMITIR SELEÇÃO DE LOTAÇÕES
			  $geraform->usaorga = true;                      // PERMITIR SELEÇÃO DE ÓRGÃO
			  $geraform->usaloca = true;                      // PERMITIR SELEÇÃO DE LOCAL DE TRABALHO
			
			  $geraform->re1nome = "regisi";                  // NOME DO CAMPO DA MATRÍCULA INICIAL
			  $geraform->re2nome = "regisf";                  // NOME DO CAMPO DA MATRÍCULA FINAL
			  $geraform->re3nome = "selreg";                  // NOME DO CAMPO DE SELEÇÃO DE MATRÍCULAS
			
			  $geraform->lo1nome = "lotaci";                  // NOME DO CAMPO DA LOTAÇÃO INICIAL
			  $geraform->lo2nome = "lotacf";                  // NOME DO CAMPO DA LOTAÇÃO FINAL
			  $geraform->lo3nome = "sellot";                  // NOME DO CAMPO DE SELEÇÃO DE LOTAÇÕES
			
			  $geraform->or1nome = "orgaoi";                  // NOME DO CAMPO DO ÓRGÃO INICIAL
			  $geraform->or2nome = "orgaof";                  // NOME DO CAMPO DO ÓRGÃO FINAL
			  $geraform->or3nome = "selorg";                  // NOME DO CAMPO DE SELEÇÃO DE ÓRGÃOS 
			
			  $geraform->tr1nome = "locali";                  // NOME DO CAMPO DO LOCAL INICIAL
			  $geraform->tr2nome = "localf";                  // NOME DO CAMPO DO LOCAL FINAL
			  $geraform->tr3nome = "selloc";                  // NOME DO CAMPO DE SELEÇÃO DE LOCAIS
			
			  $geraform->trenome = "tipo";                    // NOME DO CAMPO TIPO DE RESUMO
			  $geraform->tfinome = "filtro";                  // NOME DO CAMPO TIPO DE FILTRO
			
			  $geraform->resumopadrao = "l";                  // NOME DO DAS LOTAÇÕES SELECIONADAS
			  $geraform->filtropadrao = "i";                  // NOME DO DAS LOTAÇÕES SELECIONADAS
			
			  $geraform->strngtipores = "glomt";              // OPÇÕES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
			                                                  //                                       l - lotação,
			                                                  //                                       o - órgão,
			                                                  //                                       m - matrícula,
			                                                  //                                       t - local de trabalho
			
			  $geraform->tipofol = true;                      // MOSTRAR DO CAMPO PARA TIPO DE FOLHA
			  $geraform->arr_tipofol = array(
			                                 "r14"=>"Salário",
			                                 "r48"=>"Complementar",
			                                 "r20"=>"Rescisão",
			                                 "r35"=>"13o. Salário",
			                                 "r22"=>"Adiantamento"
			                                );
			  $geraform->complementar = "r48";                // VALUE DA COMPLEMENTAR PARA BUSCAR SEMEST 
			
			  $geraform->campo_auxilio_regi = "faixa_regis";  // NOME DO DAS MATRÍCULAS SELECIONADAS
			  $geraform->campo_auxilio_lota = "faixa_lotac";  // NOME DO DAS LOTAÇÕES SELECIONADAS
			  $geraform->campo_auxilio_orga = "faixa_orgao";  // NOME DO DOS ÓRGÃOS SELECIONADOS
			  $geraform->campo_auxilio_loca = "faixa_local";  // NOME DO DOS LOCAIS SELECIONADOS
			
			  $geraform->selecao = true;                      // CAMPO PARA ESCOLHA DA SELEÇÃO
			  $geraform->selregime = true;                    // CAMPO PARA ESCOLHA DO REGIME
			
			  $geraform->onchpad = true;                      // MUDAR AS OPÇÕES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
			  $geraform->gera_form($anofolha,$mesfolha);
			  ?>
		  <tr>
		    <td align="right" nowrap title="Tipo de impressão">
		      <b>Tipo de impressão:</b>
		    </td>
		    <td nowrap>
		      <?
		      $aTipoFolha = array('a' => 'Analítica','s'=>'Sintética');
		      db_select("ansin", $aTipoFolha, true, 1, "");
		      ?>
		    </td>
		  </tr>
		  <tr>
		    <td align="right" nowrap title="Tipo de impressão">
		      <b>Imprimir Afastados:</b>
		    </td>
		    <td nowrap>
		      <?
		      $aProcessaAfastados = array('s' => 'Sim','n'=>'Não');
		      db_select("afastado", $aProcessaAfastados, true, 1, "");
		      ?>
		    </td>
		  </tr>
		
		</table>
	
	</fieldset>

	<table style="margin-top: 10px;">
	  <tr>
	    <td colspan="2" align = "center"> 
	      <!-- <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" > -->
	      <input  name="geraCSV" id="geraCSV" type="button" value="Processar" onclick="js_geraCsv();" >
	    </td>
	  </tr>
	</table>

</center>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>


<script>

var sUrlRPC = "pes2_analiticacsv.RPC.php";

function js_geraCsv() {

  var sFolha    = $F("tipofol");
  var sTipo     = $F("tipo");
  var iAno      = $F("anofolha");
  var iMes      = $F("mesfolha");
  var sAnsin    = $F("ansin");
  var sAfastado = $F("afastado");
  var sSel      = $F("selecao");
  var sReg      = $F("regime");
  var sSemest   = null;
  var sFaixareg = null;
  var iRegini   = null;
  var iRegfim   = null;
  var sFaixalot = null;
  var iLotini   = null;
  var iLotfim   = null;
  var sFaixaloc = null;
  var iLocini   = null;
  var iLocfim   = null;
  var sFaixaorg = null;
  var iOrgini   = null;
  var iOrgfim   = null;
  
  if(document.form1.complementar){
    sSemest = $F("complementar");
  }
  if(document.form1.selreg){
    if(document.form1.selreg.length > 0){
      sFaixareg = js_campo_recebe_valores();
    }
    
  }else if(document.form1.regisi){
    iRegini = $F("regisi");
    iRegfim = $F("regisf");
  }

  if(document.form1.sellot){
    if(document.form1.sellot.length > 0){
      sFaixalot = js_campo_recebe_valores();
    }
  }else if(document.form1.lotaci){
    iLotini = document.form1.lotaci.value;
    iLotfim = document.form1.lotacf.value;
  }

  if(document.form1.selloc){
    if(document.form1.selloc.length > 0){
      sFaixaloc = js_campo_recebe_valores();
    }
  }else if(document.form1.locali){
    iLocini = document.form1.locali.value;
    iLocfim = document.form1.localf.value;
  }

  if(document.form1.selorg){
    if(document.form1.selorg.length > 0){
      sFaixaorg = js_campo_recebe_valores();
    }
  }else if(document.form1.orgaoi){
    iOrgini = document.form1.orgaoi.value;
    iOrgfim = document.form1.orgaof.value;
  }
  
  var oParametros       = new Object();
  var msgDiv            = "Gerando arquivo CSV \n Aguarde ...";
  
  oParametros.exec      = 'gerarCsv';
  oParametros.sFolha    = sFolha;
  oParametros.sTipo     = sTipo;
  oParametros.iAno      = iAno;
  oParametros.iMes      = iMes;
  oParametros.sAnsin    = sAnsin;
  oParametros.sAfastado = sAfastado;
  oParametros.sSel      = sSel;
  oParametros.sReg      = sReg;
  oParametros.sSemest   = sSemest;
  oParametros.sFaixareg = sFaixareg;
  oParametros.iRegini   = iRegini;
  oParametros.iRegfim   = iRegfim;
  oParametros.sFaixalot = sFaixalot;
  oParametros.iLotini   = iLotini;
  oParametros.iLotfim   = iLotfim;
  oParametros.sFaixaloc = sFaixaloc;
  oParametros.iLocini   = iLocini;
  oParametros.iLocfim   = iLocfim;
  oParametros.sFaixaorg = sFaixaorg;
  oParametros.iOrgini   = iOrgini;
  oParametros.iOrgfim   = iOrgfim; 
   
  js_divCarregando(msgDiv,'msgBox');
   
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoCsv
                                             });   
}

function js_retornoCsv(oAjax) {
    
    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    
    // se o retorno do csv "status" for 1, significa que nao ocorreram erros e exibimos a opção de download
    
    if (oRetorno.status == 1) {

     var listagem  = oRetorno.sArquivo+"# Download do Arquivo - "+ oRetorno.sArquivo;
         js_montarlista(listagem,'form1');      
           
    } else {  // senão  Exibimos o erro ocorriodo na geração do CSV
      
      alert(oRetorno.message);
      return false;
    
    }
}
/*
function js_emite(){
  qry = "?folha="+document.form1.tipofol.value;
  qry+= "&tipo="+document.form1.tipo.value;
  qry+= "&ano="+document.form1.anofolha.value;
  qry+= "&mes="+document.form1.mesfolha.value;
  qry+= "&ansin="+document.form1.ansin.value;
  qry+= "&afastado="+document.form1.afastado.value;
  qry+= "&sel="+document.form1.selecao.value;
  qry+= "&reg="+document.form1.regime.value;
  if(document.form1.complementar){
    qry+= "&semest="+document.form1.complementar.value;
  }

  if(document.form1.selreg){
    if(document.form1.selreg.length > 0){
      faixareg = js_campo_recebe_valores();
      qry+= "&fre="+faixareg;
    }
  }else if(document.form1.regisi){
    regini = document.form1.regisi.value;
    regfim = document.form1.regisf.value;
    qry+= "&rei="+regini;
    qry+= "&ref="+regfim;
  }

  if(document.form1.sellot){
    if(document.form1.sellot.length > 0){
      faixalot = js_campo_recebe_valores();
      qry+= "&flt="+faixalot;
    }
  }else if(document.form1.lotaci){
    lotini = document.form1.lotaci.value;
    lotfim = document.form1.lotacf.value;
    qry+= "&lti="+lotini;
    qry+= "&ltf="+lotfim;
  }

  if(document.form1.selloc){
    if(document.form1.selloc.length > 0){
      faixaloc = js_campo_recebe_valores();
      qry+= "&flc="+faixaloc;
    }
  }else if(document.form1.locali){
    locini = document.form1.locali.value;
    locfim = document.form1.localf.value;
    qry+= "&lci="+locini;
    qry+= "&lcf="+locfim;
  }

  if(document.form1.selorg){
    if(document.form1.selorg.length > 0){
      faixaorg = js_campo_recebe_valores();
      qry+= "&for="+faixaorg;
    }
  }else if(document.form1.orgaoi){
    orgini = document.form1.orgaoi.value;
    orgfim = document.form1.orgaof.value;
    qry+= "&ori="+orgini;
    qry+= "&orf="+orgfim;
  }

  jan = window.open('pes2_analitica002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);

}
*/
</script>