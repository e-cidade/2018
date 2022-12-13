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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include("classes/db_termoanuproc_classe.php");
$cltermoanuproc = new cl_termoanuproc;
$cltermoanuproc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("v09_sequencial");
$clrotulo->label("p58_codproc");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($_POST);

$db_opcao = 1;
$sql = "select * from db_usuarios where id_usuario =$usuario";
$result = pg_query($sql);
db_fieldsmemory($result,0);


?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="estilos.css" >
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/strings.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript">
</script>
<form name="form1" method="post" action="">
<!-- form name="form1" method="post" action="cai4_anulaparc.php"-->
<table width="780" border=0 align="center">
	<tr>
		<td>
			<fieldset>
				<legend><b>Anulação do Parcelamento&nbsp;<?=$parcel?></b></legend>
			
		<table width="95%"  border=0>
			<!--<tr>
				<td colspan=2 align='center'> <b>Anulação do parcelamento <?=$parcel?></b>
				</td>
			</tr>-->
			<tr>
				<td colspan=2 align='center'> &nbsp;
				</td>
			</tr>
			<tr>
				<td width="10%" valign = 'top'> <b>Usuário:</b>
				</td>
				<td > <?=$nome ?>
				</td>
			</tr>
			<tr>
				<td width="10%" valign = 'top'> <b>Data da anulação:</b>
				</td>
				<td > <?=date("d/m/Y");?>
				</td>
			</tr>
			<tr>
				<td width="10%" valign = 'top'> <b>Motivo:</b>
				</td>
				<td > <?db_textarea('motivo',8, 50,0, true, 'text', $db_opcao, "") ?>
				</td>
			</tr>
			<tr>
		    	<td nowrap title="<?=@$Tv22_processo?>">
			    	<?
			       	db_ancora(@$Lv22_processo,"js_pesquisaq14_proces(true);",$db_opcao);
			       	?>
		    	</td>
		   		<td>
					<?
						db_input('v22_processo',10,$Iv22_processo,true,'text',$db_opcao," onchange='js_pesquisaq14_proces(false);'");
						//db_input('p58_codproc',40,$Ip58_codproc,true,'text',3,'');
						db_input('p58_requer',40,$Ip58_codproc,true,'text',3,'')
		       		?>
		    	</td>
		 	</tr>
			<tr>
				<td colspan=2 align='center'> 
					<input name="anular" type="button"  value="Anular Parcelamento" onclick="js_confirmaExclusao();" >
				</td>
			</tr>
			<?
			db_input('parcel',10,"",true,'hidden',3,'');
			db_input('usuario',10,"",true,'hidden',3,'');
			?>
		</table>
		</fieldset>
		</td>
	</tr>
</table>
</form>
</html>
<script>
  function js_confirmaExclusao() {
   js_divCarregando("Aguarde, excluindo registros","msgBox");
   
   var motivo   = document.getElementById('motivo').value;
   var processo = document.form1.v22_processo.value
   strJson      = '{"exec":"setConfirmaExclusao", "parcel":"<?=$parcel?>",';
   strJson     += '"v21_sequencial":"<?=$v21_sequencial?>","usuario":"<?=$usuario?>",';
   strJson     += '"motivo":"'+ encodeURIComponent(motivo) +'","processo":"'+processo+'"}';
   var url      = 'cai4_anulaparcRPC.php';
   var oAjax    = new Ajax.Request( url, {
                                          method: 'post', 
                                          parameters: 'json='+strJson, 
                                          onComplete: js_saida
                                        }
                                 );
  }

  function js_saida(oAjax) {
    
   var obj = eval("(" + oAjax.responseText + ")");
    
   if ( obj.erro && obj.erro == true ){
       js_removeObj("msgBox");
       alert(obj.mensagem.urlDecode());
       return false ;
    }
    js_removeObj("msgBox");
    alert(obj.mensagem.urlDecode());
    parent.db_iframe_anulaparc1.hide();
    parent.db_iframe_mostrainscr.hide();
    parent.db_iframe_anulaparc1conf.hide(); 
    parent.document.formatu.pesquisar.click();
  }
  
  function js_pesquisaq14_proces(mostra){
    if (mostra==true) {
      js_OpenJanelaIframe('','db_iframe_processo','func_protprocesso.php?funcao_js=parent.js_mostraprocesso1|p58_codproc|p58_requer','Pesquisa',true);
    } else {
      if (document.form1.v22_processo.value != '') {
        js_OpenJanelaIframe('','db_iframe_processo','func_protprocesso.php?pesquisa_chave='+document.form1.v22_processo.value+'&funcao_js=parent.js_mostraprocesso','Pesquisa',false);
      } else {
        document.form1.p58_requer.value = ''; 
      }
    }
  }
  
function js_mostraprocesso(chave1,chave,erro){
    document.form1.p58_requer.value = chave;
    if (erro==true) {
        document.form1.v22_processo.focus(); 
    	document.form1.v22_processo.value = ''; 
    }
  }
  
  function js_mostraprocesso1(chave1,chave2) {
    document.form1.v22_processo.value = chave1;
    document.form1.p58_requer.value = chave2;
    db_iframe_processo.hide();
  }

function js_anula(){
	var confirma = confirm('Confirma a anulação do parcelamento?');
	if (confirma==true){
	   document.form1.submit();
	   return true;
	} else {
	  return false;
	}
}
</script>