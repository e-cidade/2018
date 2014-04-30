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
include("dbforms/db_funcoes.php");
include("libs/db_liborcamento.php");

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');

db_postmemory($HTTP_POST_VARS);

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

function js_emite(){
  
	var iInstit  = document.form1.db_selinstit.value;  
	var sQry     = ""; 
  
	if ( document.form1.orgaos1.value == "" ) {
		alert("Favor selecionar os dados do nível 1");
		return false;
	}
	if ( document.form1.orgaos2.value == "" ) {
		alert("Favor selecionar os dados do nível 2");
		return false;
	}
	
	if (iInstit == 0) {
    alert('Você não escolheu nenhuma Instituição. Verifique!');
    return false;
  }else{
		
		sQry += "?instit="		  +iInstit;
	  sQry += "&dataf="			  +document.form1.dataf.value;	
		sQry += "&sAgrupador1=" +document.form1.sAgrupador1.value;
	  sQry += "&sAgrupador2=" +document.form1.sAgrupador2.value;
		sQry += "&sOrgaos1="	  +document.form1.orgaos1.value;
		sQry += "&sOrgaos2="	  +document.form1.orgaos2.value;
		sQry += "&iTipoDespesa="+document.form1.iTipoDespesa.value;

		jan = window.open('con2_acomporc002.php'+sQry,'safo1','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  }
}

	function js_selecionar(iSelAgrupa,iValSel) {
		
		var iInstit  = document.form1.db_selinstit.value;
	  
		if ( iSelAgrupa == "SelAgrupa1") {
			 var iVerNivelVal = document.form1.vernivel1.value;
			 var iVerNivel		= document.form1.vernivel1.name;
			 var iOrgao				= document.form1.orgaos1.name;
		} else {
			 var iVerNivelVal = document.form1.vernivel2.value;
			 var iVerNivel		= document.form1.vernivel2.name;
			 var iOrgao				= document.form1.orgaos2.name;
		}


		if (iInstit == 0) {
			alert('Você não escolheu nenhuma Instituição. Verifique!');
			return false;
	  }
	  
		if ( iVerNivelVal != '' && iVerNivelVal != iValSel){
			
			if (confirm('Você já escolheu anteriormente dados do nível selecionado , deseja altera-los?')==false) {
				return false
			} else {
				js_OpenJanelaIframe('','db_iframe_orgao','func_selorcdotacao.php?nivel='+iValSel+'&db_selinstit='+iInstit+'&qvernivel='+iVerNivel+'&qorgaos='+iOrgao,'pesquisa',true);
	  	}
		
		} else if (top.corpo.db_iframe_orgao != undefined) {

		  if (iValSel == iVerNivelVal) {
		 	  db_iframe_orgao.show();
		  } else {
			  js_OpenJanelaIframe('','db_iframe_orgao','func_selorcdotacao.php?nivel='+iValSel+'&db_selinstit='+iInstit+'&qvernivel='+iVerNivel+'&qorgaos='+iOrgao,'pesquisa',true);
		  }
	  
		} else {
		  js_OpenJanelaIframe('','db_iframe_orgao','func_selorcdotacao.php?nivel='+iValSel+'&db_selinstit='+iInstit+'&qvernivel='+iVerNivel+'&qorgaos='+iOrgao,'pesquisa',true);
	  }

	}

	function js_criaSelect(Sel,iVal){

		var aNiveis = new Array(8);
		aNiveis[0] = new Array("1A","Órgão");
		aNiveis[1] = new Array("2A","Unidade");
		aNiveis[2] = new Array("3B","Função");
		aNiveis[3] = new Array("4B","Subfunção");
		aNiveis[4] = new Array("5B","Programa");
		aNiveis[5] = new Array("6B","Proj/Ativ");
		aNiveis[6] = new Array("7B","Elemento");
		aNiveis[7] = new Array("8B","Recurso");	
		
	  for (i=0; i<aNiveis.length; i++){	
				Sel.options[i] = new Option(aNiveis[i][1],aNiveis[i][0]) ;
		}
		Sel.options[iVal].selected = true;
	
	}

	function js_mudaSelect(Sel,SelCorr){
		
		if ( SelCorr.name == "sAgrupador1" ) {
			document.form1.vernivel1.value = "";
			document.form1.orgaos1.value = "";
		} else if ( SelCorr.name == "sAgrupador2" ) {
			document.form1.vernivel2.value = "";
			document.form1.orgaos2.value = "";
		}	

		iValSel = SelCorr.value; 
		iValAnt = Sel.value;
		
		for (i=0; i<Sel.length;i++) {
			Sel.options[i] = null ;
		}
		
		var aNiveis    = new Array(8);
				aNiveis[0] = new Array("1A","Órgão");
				aNiveis[1] = new Array("2A","Unidade");
				aNiveis[2] = new Array("3B","Função");
				aNiveis[3] = new Array("4B","Subfunção");
				aNiveis[4] = new Array("5B","Programa");
				aNiveis[5] = new Array("6B","Proj/Ativ");
				aNiveis[6] = new Array("7B","Elemento");
				aNiveis[7] = new Array("8B","Recurso");	
	  
		x = 0;	
		for (i=0; i<aNiveis.length; i++){	
			if (iValSel == aNiveis[i][0]) {		
				continue;
			} else {	
				Sel.options[x] = new Option(aNiveis[i][1],aNiveis[i][0]) ;
				x++;
			}
		}
		
		for (i=0; i<Sel.length;i++) {
			if (Sel.options[i].value == iValAnt) {  
				Sel.options[i].selected = true;
			}
		}
		
			
	}

</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

  <table  align="center">
    <form name="form1" method="post" action="">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
        <td align="center" colspan="3">
					<?
						db_selinstit('',300,100);
					?>
				</td>
      </tr>
      <tr>
        <td style="padding-top:15px;" align="center">
          <fieldset>
						<table>
							<tr>
								<td align="left" >
									<b>Posição Até :</b> 
								</td>
								<td>
									<?
										$iDia = date("d",db_getsession("DB_datausu"));
                    $iMes = date("m",db_getsession("DB_datausu"));
                    $iAno = date("Y",db_getsession("DB_datausu"));
										
										db_inputdata("dataf",$iDia,$iMes,$iAno,true,"text",1,"");
									?>
									
									<b>Despesa :</b> 
									
									<?
										$aTipoDespesa = array("1"=>"Empenhada","2"=>"Liquidada","3"=>"Paga");									
										db_select('iTipoDespesa',$aTipoDespesa,true,1,"");				
									?>	
								</td>
							</tr>
							<tr>
								<td align="left" >
									<b>Agrupado por nível 1:</b>
								</td>
								<td>
									<select name="sAgrupador1" style="width:297px;"  onChange="js_mudaSelect(document.form1.sAgrupador2,this);">
										<option></option>
										<script>js_criaSelect(document.form1.sAgrupador1,0);</script>
										<input type="button" name="SelAgrupa1" value="Selecionar" onClick="js_selecionar(this.name,document.form1.sAgrupador1.value);"/>
									</select>
								</td>
							</tr>
							<tr>
								<td align="left" >
									<b>Agrupado por nível 2:</b>
								</td>
								<td>
									<select name="sAgrupador2" style="width:297px;"  onChange="js_mudaSelect(document.form1.sAgrupador1,this);" >
										<option></option>
										<script>js_criaSelect(document.form1.sAgrupador2,1);</script>
										<input type="button" name="SelAgrupa2" value="Selecionar" onClick="js_selecionar(this.name,document.form1.sAgrupador2.value);"/>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="2" align = "center">
									<input  name="orgaos1"   id="orgaos1"		type="hidden" value="" >
									<input  name="orgaos2"   id="orgaos2"		type="hidden" value="" >
									<input  name="vernivel1" id="vernivel1"	type="hidden" value="" >
									<input  name="vernivel2" id="vernivel2" type="hidden" value="" >
								</td>
							</tr>
						</table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td>&nbsp;
				</td>
      </tr>
      <tr>
        <td align="center">
          <input  name="emite" id="emite" type="button" value="Imprimir" onclick="js_emite();">
        </td>
      </tr>
  </form>
    </table>
</body>
</html>