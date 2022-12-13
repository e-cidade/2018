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

set_time_limit(0);
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$cliframe_seleciona = new cl_iframe_seleciona;

$db_opcao = 1;
$sql = "select q12_classe,q12_descr from clasativ inner join classe on q82_classe=q12_classe group by q12_classe,q12_descr";
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
td {
  white-space: nowrap
}

.table-principal td:first-child {
              width: 30%;
              white-space: nowrap
}

#mostrar, #grupo, #tativ, #processar, #tipoinscricao {
  width: 50%;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" bgcolor="#CCCCCC" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post" action="iss4_contativ003.php" target='alvo'>
		<table width="50%" align="center" border="0" cellspacing="2" cellspacing="0" cellpadding="0" class="table-principal">
	    <tr>
	      <td colspan="2">&nbsp;</td>
	    </tr>
	    <tr>
	      <td colspan="2">&nbsp;</td>
	    </tr>
		  <tr>
		    <td>
		      <b>Mostrar Atividades sem Inscr: </b>
		    </td>
		    <td>
					<?
					  $aAtividades = array ("n" => "Não", 
					                        "s" => "Sim");
					  db_select('mostrar', $aAtividades, true, 2);
					?>
		    </td>
		  </tr>
		  <tr>
	      <td>
	        <b>Agrupar por: </b>     
	      </td>
			  <td>
					<?
						$aAgrupar = array ("a" => "Atividade", 
						                   "c" => "Classe", 
						                   "r" => "Ruas", 
						                   "i" => "Inscrição");
						db_select('grupo', $aAgrupar, true, 2);
					?>
			  </td>
		  </tr>
		  <tr>
	      <td>
	       <b>Data Início de: </b> 
	      </td>
		    <td>
					<?
					  db_inputdata('data', "", "", "", true, 'text', 1, "");
					  echo "&nbsp;&nbsp;a&nbsp;&nbsp;";
					  db_inputdata('data1', "", "", "", true, 'text', 1, "");
					?>
		    </td>
		  </tr>
		  <tr>
	      <td>
	        <b>Mostrar atividades: </b>
	      </td>
			  <td>
					<?
						$aMostrarAtividades = array ("p" => "Apenas principal", 
						                             "t" => "Todas atividades");
						db_select('tativ', $aMostrarAtividades, true, 2);
					?>
			  </td>
		  </tr>
		  <tr>
	      <td>
	        <b>Processar Inscrições: </b>      
	      </td>
			  <td>
					<?
						$aProcessar = array ("t" => "Todas", 
						                     "n" => "Não baixadas", 
						                     "s" => "Baixadas");
						db_select('processar', $aProcessar, true, 2,"onchange='js_periodo();'" );
					?>
			  </td>
		  </tr>
		  <tr>
	      <td>
	        <b>Tipo de Inscrição: </b>
	      </td>
		    <td>
				  <?
				    $aTipoInscricao = array ("t"   => "Todas", 
				                             "per" => "Permanentes", 
				                             "pro" => "Provisórios");
				    db_select('tipoinscricao', $aTipoInscricao, true, 2,"onchange='js_periodoVencimento();'" );
				  ?>
		    </td>
		  </tr>
			<tr id='baix' style='display: none;'>
			  <td>
			    <b>Periodo de baixa: </b>
			  </td>
				<td>
					<?
					  db_inputdata('baixai', "", "", "", true, 'text', 1, "");
						echo "&nbsp;&nbsp;a&nbsp;&nbsp;";
						db_inputdata('baixaf', "", "", "", true, 'text', 1, "");
					?>
				</td>
			</tr>
	    <tr id='periodovenc' style='display: none;'>
	      <td>
	        <b>Período de Vencimento: </b>
	      </td>
	      <td>
	        <?
	          db_inputdata('dataini', "", "", "", true, 'text', 1, "");
	          echo "&nbsp;&nbsp;a&nbsp;&nbsp;";
	          db_inputdata('datafim', "", "", "", true, 'text', 1, "");
	        ?>
	      </td>
	    </tr>
		</table>
	<table  width="50%" align="center" border=0 cellspacing="2" cellspacing="0" cellpadding="0">
	  <tr>
	    <td>&nbsp;</td>
	  </tr>
	  <tr>
	    <td align="center"> 
	      <input name="analitico" id="emite2" type="button" value="Analítico" onClick="js_relatorio('analitico')">&nbsp;&nbsp;
	      <input name="sintetico" id="emite" type="button" value="Sintético" onClick="js_relatorio('sintetico')">
	      <input name="classe1" id="emite" type="hidden" value="" onClick="">
	    </td>
	  </tr>
	  <tr>
	    <td>&nbsp;</td>
	  </tr>
	</table>
	<table width="50%" align="center" border="0" cellspacing="2" cellspacing="0" cellpadding="0">
		<tr>
		 <td height=100% valign=top width=100%>
				<?
				db_input('dados1',"","","",'hidden',"","",'dados1',"");
				db_input('dados2',"","","",'hidden',"","",'dados2',"");
				db_input('dados3',"","","",'hidden',"","",'dados3',"");
				db_input('dados4',"","","",'hidden',"","",'dados4',"");
				db_input('dados5',"","","",'hidden',"","",'dados5',"");
				db_input('dados6',"","","",'hidden',"","",'dados6',"");
				db_input('dados7',"","","",'hidden',"","",'dados7',"");
				db_input('dados8',"","","",'hidden',"","",'dados8',"");
				db_input('dados9',"","","",'hidden',"","",'dados9',"");
				db_input('dados10',"","","",'hidden',"","",'dados10',"");
				
				db_input('recruas1',"","","",'hidden',"","",'recruas1',"");
				db_input('recruas2',"","","",'hidden',"","",'recruas2',"");
				db_input('recruas3',"","","",'hidden',"","",'recruas3',"");
				db_input('recruas4',"","","",'hidden',"","",'recruas4',"");
				db_input('recruas5',"","","",'hidden',"","",'recruas5',"");
				db_input('recruas6',"","","",'hidden',"","",'recruas6',"");
				db_input('recruas7',"","","",'hidden',"","",'recruas7',"");
				db_input('recruas8',"","","",'hidden',"","",'recruas8',"");
				db_input('recruas9',"","","",'hidden',"","",'recruas9',"");
				db_input('recruas10',"","","",'hidden',"","",'recruas10',"");
				
				db_input('opcao',"","","",'hidden',"","",'opcao',"");
				db_input('atividades1',"","","",'hidden',"","",'ruas1',"");
				db_input('ruas',"","","",'hidden',"","",'ruas2',"");
				db_input('datai',"","","",'hidden',"","",'ruas3',"");
				db_input('dataf',"","","",'hidden',"","",'ruas4',"");
				db_input('baixai',"","","",'hidden',"","",'ruas3',"");
				db_input('baixaf',"","","",'hidden',"","",'ruas4',"");
				
				echo "<script>
				        document.form1.analitico.disabled = true;
				        document.form1.sintetico.disabled = true;
				      </script>";
				
				$cliframe_seleciona->sql           = $sql;
				$cliframe_seleciona->campos        = "q12_classe,q12_descr";
				$cliframe_seleciona->legenda       = "Classes";
				$cliframe_seleciona->textocabec    = "darkblue";
				$cliframe_seleciona->textocorpo    = "black";
				$cliframe_seleciona->fundocabec    = "#aacccc";
				$cliframe_seleciona->fundocorpo    = "#ccddcc";
				$cliframe_seleciona->iframe_height = '400px';
				$cliframe_seleciona->iframe_width  = '100%';
				$cliframe_seleciona->iframe_nome   = "classe";
				$cliframe_seleciona->chaves        = "q12_classe";
				$cliframe_seleciona->marcador      = true;
				$cliframe_seleciona->dbscript      = "onClick='parent.js_mandadados();'";
				$cliframe_seleciona->js_marcador   = 'parent.js_mandadados();';
				$cliframe_seleciona->iframe_seleciona($db_opcao);
			  ?>
		  </td>
		</tr>
	</table>
</form>
</body>
</html>

<script>
function js_periodo(){

  if (document.form1.processar.value == 's'){
	  document.getElementById('baix').style.display = '';
	} else {
	  document.getElementById('baix').style.display = 'none';
	} 
}

function js_periodoVencimento(){

  if (document.form1.tipoinscricao.value == 'pro'){
    document.getElementById('periodovenc').style.display = '';
  } else {
    document.getElementById('periodovenc').style.display = 'none';
  } 
}

function js_mandadados() {

   var virgula = '';
   var dados = '';
   var passa = 'f';
   for(i = 0;i < classe.document.form1.elements.length;i++){
      if(classe.document.form1.elements[i].type == "checkbox" &&  classe.document.form1.elements[i].checked){
        dados = dados+virgula+classe.document.form1.elements[i].value;
	    virgula = ', ';
	    passa = 't';
      }
    }
    
    if(passa == 'f'){
       parent.document.formaba.g2.disabled = true;
       parent.document.formaba.g3.disabled = true;
       
       document.form1.analitico.disabled = true;
       document.form1.sintetico.disabled = true;
    }
    
    if(passa == 't'){
    
	    parent.document.formaba.g2.disabled = false;
	    parent.document.formaba.g3.disabled = false;
	    document.form1.analitico.disabled = false;
      document.form1.sintetico.disabled = false;
       
      parent.iframe_g2.document.form1.dados1.value = document.form1.dados1.value;
      parent.iframe_g2.document.form1.chaves1.value = dados;
	    parent.iframe_g1.document.form1.classe1.value = dados;
	    parent.iframe_g2.document.form1.submit();
   }
}

function js_relatorio(tipo){
   var classe1 = '';
   var atividades1 = '';
   var ruas1 = '';
   var virgula = '';

   //pega os dados marcados da aba 1 classes
   for(i = 0;i < classe.document.form1.elements.length;i++){
      if(classe.document.form1.elements[i].type == "checkbox" &&  classe.document.form1.elements[i].checked){
        classe1 += virgula+classe.document.form1.elements[i].value;
	    virgula = ', ';
      }
    }
    
   document.form1.opcao.value = tipo; 
   data=document.form1.data_ano.value+'-'+document.form1.data_mes.value+'-'+document.form1.data_dia.value;
   data1=document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value;
   datai=data;
   dataf=data1;
   
   baixai = document.form1.baixai_ano.value+'-'+document.form1.baixai_mes.value+'-'+document.form1.baixai_dia.value;
   baixaf = document.form1.baixaf_ano.value+'-'+document.form1.baixaf_mes.value+'-'+document.form1.baixaf_dia.value;
   
   processa=document.form1.processar.value;
   mostrar=document.form1.mostrar.value;
   
   parent.iframe_g3.js_mandaruas2();

   jan = window.open('','alvo','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   document.form1.submit();
}
</script>