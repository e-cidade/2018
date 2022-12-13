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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_obrasalvara_classe.php");

$clObrasAlvara = new cl_obrasalvara;
$clRotulo 		 = new rotulocampo;

$clObrasAlvara->rotulo->label();
$clRotulo->label("ob01_nomeobra");
$clRotulo->label("p58_codproc");
$clRotulo->label("p58_requer");

$oPost = db_utils::postMemory($_POST);

$db_opcao = 22;
$db_botao = false;

if(isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]=="Alterar"){
  db_inicio_transacao();
  $db_opcao = 2;
  $clObrasAlvara->alterar($ob04_codobra);
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clObrasAlvara->sql_record($clObrasAlvara->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
}
?>

<html>
<head>
<?php 
  db_app::load('scripts.js, prototype.js, strings.js, widgets/windowAux.widget.js, widgets/dbmessageBoard.widget.js');
  db_app::load('estilos.css');
?>
<style>
fieldset.form {
  margin: 20px auto;
  width: 700px;
}

#botoes {
 text-align: center;
}

#window {
	border-collapse: collapse;	
}
#window td {
  margin: 10px;
  padding: 2px;
  border: 1px solid #CCC;
}
#window .descricao {
	font-weight: bold;
  width: 25%;
}
#window .conteudo {
  background-color: #FFF;
  width: 75%;
}
</style>
</head>

<body bgcolor=#CCCCCC>

<?
//MODULO: projetos

?>
<form name="form1" method="post" action="">
<fieldset class="form">
	<legend>
		<strong>Alvar&aacute;s</strong>
	</legend>
	
	<table align="center" >
		<tr>
			<td nowrap title="<?=@$Tob04_codobra?>" width="30%">
			<?
				db_ancora(@$Lob04_codobra,"js_pesquisaob04_codobra(true);",($db_opcao == 2?3:$db_opcao));
			?>
			</td>
			<td>
			<? 
				db_input('ob04_codobra',10,$Iob04_codobra,true,'text',($db_opcao == 2?3:$db_opcao)," onchange='js_pesquisaob04_codobra(false);'"); 
			  db_input('ob01_nomeobra',40,$Iob01_nomeobra,true,'text',3,'');      
			?>
			</td>
		</tr>
		
		<tr>
			<td nowrap title="<?=@$Tob04_alvara?>">
				<?=@$Lob04_alvara?>
			</td>
			<td nowrap>
				<? 
					db_input('ob04_alvara',10,$Iob04_alvara,true,'text',1,"") ;
					if ($db_opcao==1){
						echo "(se não preencher, codigo será gerado automaticamente)";
					}
				?>
			</td>
		</tr>
		
		<tr>
			<td nowrap title="<?=@$Tob04_data?>">
				<?=@$Lob04_data?>
			</td>
			<td>
				<?
				if($db_opcao == 1){
					$ob04_data_dia = date("d",db_getsession("DB_datausu"));
					$ob04_data_mes = date("m",db_getsession("DB_datausu"));
					$ob04_data_ano = date("Y",db_getsession("DB_datausu"));
				}
				db_inputdata('ob04_data',@$ob04_data_dia,@$ob04_data_mes,@$ob04_data_ano,true,'text',$db_opcao,"")
				?>
			</td>
		</tr>
		
		<tr>
			<td nowrap title="<?=@$Tob04_dtvalidade?>">
				<?=@$Lob04_dtvalidade?>
			</td>
			<td>
				<?
  				db_inputdata('ob04_dtvalidade',@$ob04_dtvalidade_dia,@$ob04_dtvalidade_mes,@$ob04_dtvalidade_ano,true,'text',$db_opcao,"")
				?>
			</td>
		</tr>
		
		<tr>
			<td nowrap title="Processos registrado no sistema?">
				<strong>Processo do Sistema</strong>
			</td>
			<td nowrap>
				<?
				  $lProcessoSistema = true;
					db_select('lProcessoSistema', array(true=>'SIM', false=>'NÃO'), true, $db_opcao, "onchange='js_processoSistema(this.value)' style='width: 95px'") 
				?>
			</td>
		</tr>
		
		<tr id="processoSistema">
			<td nowrap title="<?=@$Tp58_codproc?>">
				<?
					db_ancora($Lp58_codproc, 'js_pesquisaProcesso(true)', $db_opcao);
				?>
			</td>
			<td nowrap>
				<? 
					db_input('p58_codproc', 10, $Ip58_codproc, true, 'text', $db_opcao, 'onchange="js_pesquisaProcesso(false)"') ;
					
					db_input('p58_requer', 40, $Ip58_requer, true, 'text', 3);
				?>
			</td>
		</tr>
		
		<tr id="processoExterno1" style="display: none;">
			<td nowrap title="Número do processo externo">
				<strong>Processo</strong>
			</td>
			<td nowrap>
				<? 
					db_input('ob04_processo', 10, $Iob04_processo, true, 'text', $db_opcao) ;
				?>
			</td>
		</tr>
	
		<tr id="processoExterno2" style="display: none;">
			<td nowrap title="Número do processo externo">
				<?=@$Lob04_titularprocesso?>
			</td>
			<td nowrap>
				<? 
					db_input('ob04_titularprocesso', 54, $Iob04_titularprocesso, true, 'text', $db_opcao) ;
				?>
			</td>
		</tr>
		
		<tr id="processoExterno3" style="display: none;">
			<td nowrap title="Número do processo externo">
				<?=@$Lob04_dtprocesso?>
			</td>
			<td nowrap>
				<? 
					db_inputdata('ob04_dtprocesso', @$ob04_dtprocesso_dia, @$ob04_dtprocesso_mes, @$ob04_dtprocesso_ano, true, 'text', $db_opcao);
				?>
			</td>
		</tr>
		
		<tr>
		  <td title="<?=$Tob04_obsprocesso?>" colspan="2" align="center">
		  	<fieldset style="width: 568px; margin:0;">
		  		<legend><?=$Lob04_obsprocesso?></legend>
		  		<?
		  			db_textarea('ob04_obsprocesso', 10, 70, $Iob04_obsprocesso, true, 'text', $db_opcao);
		  		?>
		  		
		  	</fieldset>
		  </td>
		</tr>
		
	</table>
	
	<div id="botoes">
		<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>> 
		<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
	  <input name="detalhes" id="detalhes" type="button" value="Detalhes Obra" onclick="js_pesquisaConstrucoes()" />
	</div>
	
</fieldset>

</form>

<script>

function js_processoSistema(lProcessoSistema) {

	if (lProcessoSistema == 1) {
		document.getElementById('processoExterno1').style.display = 'none';
		document.getElementById('processoExterno2').style.display = 'none';
		document.getElementById('processoExterno3').style.display = 'none';
		document.getElementById('processoSistema').style.display  = '';
	}	else {
		document.getElementById('processoExterno1').style.display = '';
		document.getElementById('processoExterno2').style.display = '';
		document.getElementById('processoExterno3').style.display = '';
		document.getElementById('processoSistema').style.display  = 'none';
	}
		
}

sUrl = 'pro1_obrasalvara.RPC.php';

function js_pesquisaConstrucoes() {

	var iCodigoObra = $F('ob04_codobra');
	var oParam      = new Object();

	if(iCodigoObra == '') {
		alert('Código da obra não informado.');
		return false;
	}
	
	oParam.sExec       = 'getConstrucoes';
	oParam.iCodigoObra = iCodigoObra;

	
	js_divCarregando('Pesquisando construções vinculadas a obra, aguarde.', 'msgbox');

	var oAjax = new Ajax.Request(sUrl,
			                        {
        											 method    : 'POST',
                               parameters: 'json=' + Object.toJSON(oParam), 
                               onComplete: js_retornaConstrucoes
                              });
	
}

function js_retornaConstrucoes(oAjax) {

	js_removeObj('msgbox');
	
	var oRetorno        = eval("("+oAjax.responseText+")");

	if (oRetorno.iStatus == 1) {

		with (oRetorno.aConstrucao[0]) {

			var sContent = '';
			
			sContent += "<div style='margin: 10px auto; text-align: center;'>                                                          ";
			sContent += "  <div id='msgtopo'></div>                                                                                    ";
			sContent += "  <div style='width:550px; margin:10px auto;'>                                                                ";
			sContent += "    <fieldset>                                                                                                ";
			sContent += "      <table id='window' width='100%'>                                                                        ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "					 <td class='descricao'>Obra</td>                                                           					 ";
			sContent += "					 <td class='conteudo'>"+ob08_codobra+"</td>                                                          ";                 
			sContent += "			   </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "				   <td class='descricao'>Construção</td>                                                     					 ";
			sContent += "					 <td class='conteudo'>"+ob08_codconstr+"</td>                                                        ";                 
			sContent += "        </tr>                                                                                                 "; 
			sContent += "				 <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Descrição Obra</td>                                                           ";
			sContent += "          <td class='conteudo'>"+ob01_nomeobra.urlDecode()+"</td>                                             ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Area</td>                                                                     ";
			sContent += "          <td class='conteudo'>"+ob08_area+"m2</td>                                                           ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Ocupação</td>                                                                 ";
			sContent += "          <td class='conteudo'>"+ob08_ocupacao + " - " + ob08_descrocupacao.urlDecode()+"</td>                ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Tipo de Construção</td>                                                       ";
			sContent += "          <td class='conteudo'>"+ob08_tipoconstr + " - " + ob08_descrtipoconstr.urlDecode()+"</td>            ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Tipo Lançamento</td>                                                          ";
			sContent += "          <td class='conteudo'>"+ob08_tipolanc + " - " + ob08_descrtipolanc.urlDecode()+"</td>                ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Logradouro</td>                                                               ";
			sContent += "          <td class='conteudo'>"+ob07_lograd + " - " + j14_nome.urlDecode()+", "+ob07_numero+"</td>           ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Complemento</td>                                                              ";
			sContent += "          <td class='conteudo'>"+ob07_compl.urlDecode()+"</td>                                                ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Bairro</td>                                                                   ";
			sContent += "          <td class='conteudo'>"+ob07_bairro +" - "+ j13_descr.urlDecode()+"</td>                             ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Detalhes</td>                                                                 ";
			sContent += "          <td class='conteudo'>                                                                               ";     						 
			sContent += "            Área - " + ob07_areaatual + " | Unidades - " +ob07_unidades+" | Pavimentos - "+ob07_pavimentos+"  ";
			sContent += "          </td>                                                                                               ";
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Data Início</td>                                                              ";
			sContent += "          <td class='conteudo'>"+ob07_inicio+"</td>                                                           ";                 
			sContent += "        </tr>                                                                                                 "; 
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Data Final</td>                                                               ";
			sContent += "          <td class='conteudo'>"+ob07_fim+"</td>                                                              ";                 
			sContent += "        </tr>                                                                                                 ";
			sContent += "      </table>                                                                                                ";
			sContent += "    </fieldset>                                                                                               ";
			sContent += "    <div style='margin: 10px auto;'>                                                                          ";
			sContent += "      <input type='button' name='fechar' value='Fechar' onclick='js_fechar_janela()'/>                    ";
			sContent += "    </div>																																																		 ";		
			sContent += "  </div>                                                                                                      ";
			sContent += "</div>                                                                                                        ";
			
			js_montaGradeConstrucao(sContent);
		}
		
	} else {
		
		alert(oRetorno.sMessage);
		
	}

}

function js_montaGradeConstrucao(sHtml) {

	windowConstr = new windowAux('wndConstr', 'Construção da obra', 570, 440);
	windowConstr.setContent(sHtml);

  var w = ((screen.width - 582) / 2);
  var h = ((screen.height / 2) - 410);

  windowConstr.show(h, w);
	
	$('window'+windowConstr.idWindow+'_btnclose').observe("click",js_fechar_janela);

	oMessage  = new DBMessageBoard('msgboard', 
  										           'Detalhes da Construção',
  										           'Detalhes da Construção',
            										 $('msgtopo'));
	oMessage.show();

	
}

function js_fechar_janela(){
	
	windowConstr.destroy();
	  
} 

function js_pesquisaProcesso(lMostra) {

  if (lMostra) {
    js_OpenJanelaIframe('','db_iframe_matric', 'func_protprocesso.php?funcao_js=parent.js_mostraProcesso|p58_codproc|z01_nome','Pesquisa',true);
  } else {
    js_OpenJanelaIframe('','db_iframe_matric', 'func_protprocesso.php?pesquisa_chave='+document.form1.p58_codproc.value+'&funcao_js=parent.js_mostraProcessoHidden','Pesquisa',false);
  }
   
}

function js_mostraProcesso(iCodProcesso, sRequerente) {

  document.form1.p58_codproc.value = iCodProcesso;
  document.form1.p58_requer.value  = sRequerente;
  db_iframe_matric.hide();
  
}

function js_mostraProcessoHidden(iCodProcesso, sNome, lErro) {

  if(lErro == true) {
    document.form1.p58_codproc.value = "";
    document.form1.p58_requer.value  = sNome;
  } else {
    document.form1.p58_requer.value  = sNome;
  }

}

function js_pesquisaob04_codobra(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_obras','func_obras.php?funcao_js=parent.js_mostraobras1|ob01_codobra|ob01_nomeobra','Pesquisa',true);
  }else{
     if(document.form1.ob04_codobra.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_obras','func_obras.php?pesquisa_chave='+document.form1.ob04_codobra.value+'&funcao_js=parent.js_mostraobras','Pesquisa',false);
     }else{
       document.form1.ob01_nomeobra.value = ''; 
     }
  }
}
function js_mostraobras(chave,erro){
  document.form1.ob01_nomeobra.value = chave; 
  if(erro==true){ 
    document.form1.ob04_codobra.focus(); 
    document.form1.ob04_codobra.value = ''; 
  }
}
function js_mostraobras1(chave1,chave2){
  document.form1.ob04_codobra.value = chave1;
  document.form1.ob01_nomeobra.value = chave2;
  db_iframe_obras.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_obrasalvara','func_obrasalvara.php?funcao_js=parent.js_preenchepesquisa|ob04_codobra','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_obrasalvara.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>



<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<?
if(isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]=="Alterar"){
  if($clObrasAlvara->erro_status=="0"){
    $clObrasAlvara->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clObrasAlvara->erro_campo!=""){
      echo "<script> document.form1.".$clObrasAlvara->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clObrasAlvara->erro_campo.".focus();</script>";
    };
  }else{
    $clObrasAlvara->erro(true,true);
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>