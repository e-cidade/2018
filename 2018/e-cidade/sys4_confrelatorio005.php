<?php
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

$margSup = 0;
$margInf = 0;
$margEsq = 20;
$margDir = 20;


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/json2.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1; parent.lPropriedades = true;" bgcolor="#cccccc">
<center>
<form name="form1" class="container">
<table style="padding-top:20px;" class="form-container">
	<tr class="tipos">
		<td>
			<fieldset>
	    	<legend>Relatório</legend>

				<table>
					 <tr>
			  		<td>
			  		  <b>Nome Relatório :</b>
			  		</td>
			  		<td>
			  		  <?php
								db_input("nomeRel",40,"",true,"text",1,"onChange='js_incluirPropriedades();'");
			  		  ?>
			  		</td>	
		  	  </tr>
					<tr>
						<td><b>Formato de saída:</b></td>
						<td>
							<?php 
								$aTiposSaida = array('pdf' => 'PDF', 'csv' => 'CSV', 'txt' => 'TXT');
								db_select('tipoSaida', $aTiposSaida, true, 1, "style='width:150px;'"); 
							?>
						</td>
					</tr>
				</table>    	
	    </fieldset>
		</td>
	</tr>
  <tr class="pdf"> 
    <td>
	    <fieldset>
		    <legend>
		      <b>Configuração da Página</b>
		    </legend>
		    <table>
		      <tr>
			  		<td>
			  		  <b>Formato Página :</b>
			  		</td>
			  		<td>
			  		  <?php
								$aFormato = array("A4"=>"A4");
			  		    db_select("formato",$aFormato,true,1,"style='width:150px;' onChange='js_incluirPropriedades();'");
			  		  ?>
			  		</td>	
		  	  </tr>
		  	  <tr>
			  		<td>
			  		  <b>Orientação Página :</b>
			  		</td>
			  		<td>
			  		  <?php
								$aOrientacao = array("portrait"=>"Retrato","landscape"=>"Paisagem");				
			  		    db_select("orientacao",$aOrientacao,true,1,"style='width:150px;'onChange='js_incluirPropriedades();'");	  		  
			  		  ?>
			  		</td>
		  	  </tr>
		  	  <tr>
			  		<td>
			  		  <b>Relatório Listrado :</b>
			  		</td>
			  		<td>
					  	<input type="checkbox" name="chkListra" checked />
			  		</td>
		  	  </tr>	  	  
		    </table>
		  </fieldset>
		</td>
  </tr>
  <tr class="pdf">
		<td>  
		  <fieldset>
		    <legend>
		      <b>Margens</b>
		    </legend>	    	    
		    <table>	  	  			  	  
		  	  <tr>
			  		<td>
			  		  <b>Esquerda :</b>
			  		</td>
			  		<td>
			  		  <?php
			  		    db_input("margEsq",10,"",true,"text",1,"onChange='js_incluirPropriedades();'");
			  		  ?>
			  		</td>
		  	  </tr>	  			  	  	  	  
		  	  <tr>
			  		<td>
			  		  <b>Direita :</b>
			  		</td>
			  		<td>
			  		  <?php
			  		    db_input("margDir",10,"",true,"text",1,"onChange='js_incluirPropriedades();'");	  		  
			  		  ?>
			  		</td>
		  	  </tr>
		  	  <tr>
			  		<td>
			  		  <b>Superior :</b>
			  		</td>
			  		<td>
			  		  <?php
			  		    db_input("margSup",10,"",true,"text",1,"onChange='js_incluirPropriedades();'");	  		  
			  		  ?>
			  		</td>
		  	  </tr>
		  	  <tr>
			  		<td>
			  		  <b>Inferior :</b>
			  		</td>
			  		<td>
			  		  <?php
			  		    db_input("margInf",10,"",true,"text",1,"onChange='js_incluirPropriedades();'");	  		  
			  		  ?>
			  		</td>
		  	  </tr>	  	  	  	  	  	  
		    </table>	
		  </fieldset>	      
    </td>
  </tr>
</table>
</form>
</center>
</body>
</html>
<script>

	(function(){

		$('tipoSaida').onchange = function() {
			var oTipos 	= $$('.tipos'),
					sTipo 	= this.value,
					oTrs 		= oTipos[0].nextSiblings()

			oTrs.each(function(oTr, iIndex) {
				oTr.style.display = (oTr.hasClassName(sTipo) ? '' : 'none');
			});

			js_incluirPropriedades();
		}

	})();

 function js_criaObjetoPropriedades(sNome,sFormato,sOrientacao,iMargemSup,iMargemInf,iMargemEsq,iMargemDir,sTipoSaida){
    
    this.iVersao 	   = "1.0";
		this.sLayout  	 = "dbseller";
		this.sNome 		   = sNome;
		this.sFormato 	 = sFormato;
		this.sOrientacao = sOrientacao;
		this.iMargemSup  = iMargemSup; 
		this.iMargemInf  = iMargemInf;
		this.iMargemEsq  = iMargemEsq;
		this.iMargemDir  = iMargemDir;
		this.sTipoSaida	 = sTipoSaida;
	
 }

 function js_processaPropriedades(objPropriedades){
    
 	var doc = document.form1;
 	with(objPropriedades){
	  doc.formato.value    = sFormato;
	  doc.nomeRel.value    = sNome.urlDecode();
	  doc.orientacao.value = sOrientacao;
	  doc.margSup.value	   = iMargemSup;
	  doc.margInf.value	   = iMargemInf;
	  doc.margEsq.value	   = iMargemEsq;
	  doc.margDir.value	   = iMargemDir;
	  doc.tipoSaida.value	 = sTipoSaida;
	  doc.tipoSaida.onchange();
	}
	js_incluirPropriedades();
	
 }


 function js_incluirPropriedades(){
    
  var doc = document.form1;
	var objPropriedades = new js_criaObjetoPropriedades(doc.nomeRel.value,
																											doc.formato.value,	
																											doc.orientacao.value,
																											doc.margSup.value,
																											doc.margInf.value,
																											doc.margEsq.value,
																											doc.margDir.value,
																											doc.tipoSaida.value);
	
 	js_enviaPropriedades(objPropriedades);
 	
 }


 function js_enviaPropriedades(objPropriedades){
	
   	var ConsultaTipo  = 'incluirPropriedades';
    
    var sQuery  = "objPropriedades="+JSON.stringify(objPropriedades);
        sQuery += "&tipo="+ConsultaTipo;	    	    	    
	
   	var url           = 'sys4_consultaviewRPC.php';
   	var oAjax         = new Ajax.Request( url, {
                                                method: 'post', 
    	                                          parameters: sQuery
                                              }
                                       );


}

</script>
