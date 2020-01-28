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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo();
$clrotulo->label("j01_matric");
$clrotulo->label("j14_nome");
$clrotulo->label("j30_codi");
$clrotulo->label("j30_descr");

?>
<html>
<head>
  <script>
		function js_relatorio(){

			// abre o arquivo que gera o relatório passando os parametros 
			// configurados por $_GET
			var matricula = document.getElementById("j01_matric").value;
			var setor     = document.getElementById("j30_codi").value;
			var ordemimp  = document.getElementById("ordemimp").value;
			var imprimir  = document.getElementById("imprimir").value;
			var filtro    = document.getElementById("filtro").value;
			var formato   = document.getElementById("formato").value;
			var sUrl      = 'cad1_reliptuender002.php?matricula='+matricula+
			                                        '&filtro='+filtro+
			                                        '&setor='+setor+
			                                        '&ordemimp='+ordemimp+
			                                        '&imprimir='+imprimir+
			                                        '&formato='+formato;	

			// abre a janela para o usuário fazer download do arquivo
			if (document.form1.formato.value == "csv") {
			
	  		js_OpenJanelaIframe('', 'db_rel', sUrl, 'Relatorio', true);
	  	
			// abre o relatório em uma nova janela com o PDF
			} else {
			
			  var sParam = 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ';
	    	jan        = window.open(sUrl,'',sParam);
  			jan.moveTo(0,0);

			}

		}
  </script>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC">
<table>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<center>
	<form name="form1" method="post" action="" >
	 <table cellpadding="0" cellspacing="0" border="0">
	   <tr>
	     <td>&nbsp;</td>
	   </tr>
	   <tr>
	     <td>
				<fieldset> 
				   <legend align="left"> <b>Relatório de endereço de entrega</b> </legend>
				     <table align="center" border="0">
				    	 <tr>
				    	   <td align="right" title="Matricula do ímovel">
	                <?
	                  db_ancora("<b>Matrícula do ímovel:</b>", "js_pesquisamatricula(true);", 1);
	                ?>				    	   
				    	   </td>
				    	   <td align="left">
	                <?
                    db_input('j01_matric', 10, $Ij01_matric, true, 'text', 1, "onChange='js_pesquisamatricula(false);'");
                  ?>			    	   
				    	   </td>
				    	   <td align="left">
				    	    <?
                    db_input('z01_nomepropri', 40, $Ij14_nome, true, 'text', 3, "");
				    	    ?>
				    	   </td>
				    	 </tr>	
               <tr>
                 <td align="right" title="Setor Quadra Lote">
                  <?
                    db_ancora("<b>Setor:</b>", "js_pesquisasetor(true);", 1);
                  ?>                 
                 </td>
                 <td align="left">
                  <?
                    db_input('j30_codi', 10, $Ij30_codi, true, 'text', 1, "onChange='js_pesquisasetor(false);'");
                  ?>               
                 </td>
                 <td align="left">
                  <?
                    db_input('j30_descr', 40, $Ij30_descr, true, 'text', 3, "");
                  ?>
                 </td>
               </tr>
               <tr>
                 <td align="right" title="Filtro Ordem de Impresão">
                   <b>Ordem Impressão:</b>
                 </td>
                 <td align="left" colspan="2">
		              <?
		                $aOrdemImp = array (
		                                  "cl"  => "Cidade/Logradouro",
		                                  "bl"  => "Bairro/Logradouro",
		                                  "an"  => "Alfabética/Nome",
		                                  "ze"  => "Zona de Entrega",
		                                  "ra"  => "Referência Anterior",
		                                  "sql"  => "Setor/Quadra/Lote",
		                                  "ba"  => "Bairro/Alfabética"
		                );
		                
		                db_select('ordemimp', $aOrdemImp, true, 1, "style='width: 150;'");
		              ?>                 
                 </td>
               </tr>
               <tr>
                 <td align="right" title="Filtro Imprimir">
                   <b>Imprimir:</b>
                 </td>
                 <td align="left" colspan="2">
		              <?
		                $aImprimir = array (
		                                    "amb"  => "Ambos",
		                                    "sem"  => "Somente sem Endereço",
		                                    "com"  => "Somente com Endereço"
		                );
		                
		                db_select('imprimir', $aImprimir, true, 1, "style='width: 150;'");
		              ?>                 
                 </td>
               </tr>	
               <tr>
                 <td align="right" title="Filtro Baixa">
                   <b>Filtro Baixa:</b>
                 </td>
                 <td align="left" colspan="2">
		              <?
		                $aFiltro = array (
		                                  "t"  => "Todas",
		                                  "b"  => "Baixadas",
		                                  "nb" => "Não Baixadas"
		                );
		                
		                db_select('filtro', $aFiltro, true, 1, "style='width: 150;'");
		              ?>                 
                 </td>
               </tr>
               <tr>
                 <td align="right" title="Formato de Impressão">
                   <b>Formato:</b>
                 </td>
                 <td align="left" colspan="2">
	                <?
	                  $aFormato = array (
	                    "pdf" => "PDF",
	                    "csv" => "CSV",
	                  );
	                
	                  db_select('formato', $aFormato, true, 1, "");
	                ?>                 
                 </td>
               </tr>                                             			    	 		    
				     </table>
				  </fieldset>
	     </td>
	   </tr>
	 </table>
  </form> 
	<!-- Botão de gerar relatório -->
	<div style="margin-top: 5px;">
	  <input name="emite" onclick="return js_relatorio()" type="button" id="emite" value="Gerar Relatório">
	</div>
</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
/////////////// matricula ///////////////
function js_pesquisamatricula(lMostra){
	if (lMostra) {
	    
	var	sUrl = 'func_iptubase.php?funcao_js=parent.js_mostramatricula1|j01_matric|z01_nome';
	  js_OpenJanelaIframe('','db_iframe_matricula',sUrl,'Pesquisa',true);
	  
	} else {
	    
	if (document.form1.j01_matric.value != '') {
	     
	var sUrl = 'func_iptubase.php?pesquisa_chave='+document.form1.j01_matric.value+'&funcao_js=parent.js_mostramatricula'; 
	 js_OpenJanelaIframe('','db_iframe_matricula',sUrl,'Pesquisa',false);
	 
	} else {
	  document.form1.z01_nomepropri.value   = '';
	  document.form1.j01_matric.value       = '';        
	  }
	}  
}

function js_mostramatricula(chave,erro){
  document.form1.z01_nomepropri.value = chave; 
  if (erro==true) { 
    document.form1.j01_matric.focus(); 
    document.form1.z01_nomepropri.value   = '';
    document.form1.j01_matric.value       = '';
  }
}
    
function js_mostramatricula1(chave1,chave2){
  document.form1.j01_matric.value = chave1;
  document.form1.z01_nomepropri.value = chave2;
  db_iframe_matricula.hide();
}       

/////////////// setor ///////////////
function js_pesquisasetor(lMostra){

  var obj = document.form1;
  if (lMostra) {
      
  var sUrl = 'func_setor.php?funcao_js=parent.js_mostrasetor1|j30_codi|j30_descr';
    js_OpenJanelaIframe('','db_iframe_setor',sUrl,'Pesquisa',true);
    
  } else {
      
	  if (obj.j30_codi.value != '') {
	       
	  var sUrl = 'func_setor.php?pesquisa_chave='+obj.j30_codi.value+'&funcao_js=parent.js_mostrasetor'; 
	   js_OpenJanelaIframe('','db_iframe_matricula',sUrl,'Pesquisa',false);
	   
	  } else {
	  
	    obj.j30_codi.value   = '';
	    obj.j30_descr.value  = '';        
	  }
  }  
}

function js_mostrasetor(chave,erro){
  var obj = document.form1;
      obj.j30_descr.value = chave;
       
  if (erro == true) {
   
    obj.j30_codi.focus(); 
    obj.j30_codi.value   = '';
    obj.j30_descr.value  = '';
  }
}
    
function js_mostrasetor1(chave1,chave2){

  var obj = document.form1;
      obj.j30_codi.value  = chave1;
      obj.j30_descr.value = chave2;
  db_iframe_setor.hide();
}           
</script>