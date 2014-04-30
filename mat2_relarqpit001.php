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
require("libs/db_app.utils.php");

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
$clrotulo->label('e14_sequencial');
$clrotulo->label('e14_nomearquivo');
db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js, widgets/windowAux.widget.js");
db_app::load("estilos.css, grid.style.css");
?>

<script>

function js_emite(){
  var qry = "";
  var dtInicial = document.form1.dt_inicial_ano.value+'-'+document.form1.dt_inicial_mes.value+'-'+document.form1.dt_inicial_dia.value;
  var dtFinal = document.form1.dt_final_ano.value+'-'+document.form1.dt_final_mes.value+'-'+document.form1.dt_final_dia.value;
  
  if($F('e14_sequencial') == ''){
		if(document.form1.dt_inicial_ano.value == ''){
			alert("Usuário:\n\nData Inicial Obrigatória\n\n");
			document.form1.dt_inicial.focus();
			return false;
		}
		if(document.form1.dt_final_ano.value == ''){
			alert("Usuário:\n\nData Final Obrigatória\n\n");
			document.form1.dt_final.focus();
			return false;
		}
	
	  
	  var validaDatas = js_diferenca_datas(dtInicial,dtFinal,3);
	  if(validaDatas){
	  	alert("Usuário:\n\nData Inicial deve ser menor que a data final!\n\n");
	  	document.form1.dt_inicial.value = '';
	  	document.form1.dt_inicial.focus();
	  	return false;
	  }
  }   
  qry  = 'dt_inicial='+dtInicial;
  qry += '&dt_final='+dtFinal;
  qry += '&tipo='+document.form1.tipo.value;
  qry += '&e14_sequencial='+$F('e14_sequencial');
  
  jan  = window.open('mat2_relarqpit002.php?'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_pesquisae14_sequencial(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_emparquivopit','func_emparquivopit.php?funcao_js=parent.js_mostrae14_sequencial1|e14_sequencial|e14_nomearquivo','Pesquisa',true);
  }else{
     if($('e14_sequencial').value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_emparquivopit','func_emparquivopit.php?pesquisa_chave='+$('e14_sequencial').value+'&funcao_js=parent.js_mostrae14_sequencial','Pesquisa',false);
     }else{
       $('e14_nomearquivo').value = ''; 
     }
  }
}
function js_mostrae14_sequencial(chave,erro){
  $('e14_nomearquivo').value = chave; 
  if(erro==true){ 
    $('e14_sequencial').focus(); 
    $('e14_sequencial').value = ''; 
  }
}
function js_mostrae14_sequencial1(chave1,chave2){
  $('e14_sequencial').value  = chave1;
  $('e14_nomearquivo').value = chave2;
  db_iframe_emparquivopit.hide();
}

</script>  
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" style="margin-top: 30px;">
  <tr>
    <td> 
    	<fieldset>
    		<legend><b>Datas de Geração dos Arquivos</b></legend>
    		<form name="form1" method="post" action="" >
  			<table  align="center">
  			 <tr>
          <td nowrap title="<?=@$Te14_sequencial?>">
          <?
            db_ancora("<b>Arquivo:</b>","js_pesquisae14_sequencial(true);",1);
          ?>
          </td>
          <td colspan="3"> 
          <?
            db_input('e14_sequencial',10,$Ie14_sequencial,true,'text',1," onchange='js_pesquisae14_sequencial(false);'");
          ?>
          <?
            db_input('e14_nomearquivo',40,$Ie14_nomearquivo,true,'text',3,'');
          ?>
            </td>
          </tr>
    			<tr>
        		<td align='left'><b>Data Inicial:</b>
        		</td>
        		<td nowrap>
						<?
							db_inputdata('dt_inicial',"","","",false,
             'text',1,"","",""); 
						?>
					 	</td>
         		<td align='left'>
         		<b>Data Final:</b>
        		</td>
        		<td nowrap>
						<?
							db_inputdata('dt_final',"","","",false,
             'text',1,"","",""); 
						?>
					 	</td>
      		</tr>
      	<tr>
        <td align="left"><b>Tipo:</b></td>
        <td colsapn="3">
  				<?
          $x = array("1"=>"Analítico","2"=>"Sintético");
          db_select("tipo",$x,true,2);
  				?>
        </td>
      	
   		</table>
  		</form>	
    	</fieldset>
    </td>
  </tr>
  <tr>
  	<td height="10">&nbsp;</td>
  </tr>
  <tr>
  	<td align = "center"> 
    	<input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
    </td>
  </tr>
</table>
  <?
  	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
	?>
</body>
</html>