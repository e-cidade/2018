<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_sanitario_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clsanitario = new cl_sanitario;
$clsanitario->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("q03_descr");
$clrotulo->label("j14_nome");
$clrotulo->label("j13_descr");
$db_opcao=1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC">
<form name="form1" method="post" action="">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

<center>

	<table border="0">
		<tr>
			<td height="10"></td>
	  </tr>
		<tr>
			<td>
				<fieldset><legend><b>&nbsp;Alvará Sanitário &nbsp;</b></legend>
					<table border="0">
						<tr>
							<td nowrap title="<?=@$Ty80_codsani?>">
								<?
									db_ancora(@$Ly80_codsani,"js_pesquisa(true);",1);
								?>
							</td>
							<td> 
								<?
									db_input('y80_codsani',10,$Iy80_codsani,true,'text',$db_opcao," onchange='js_pesquisa(false);'")
								?>
							</td>
						</tr>
						<tr>
							<td nowrap title="<?=@$Tz01_nome?>">
							  <?
									db_ancora(@$Lz01_nome,"js_pesquisay80_numcgm(true);",$db_opcao);
							  ?>
							</td>
							<td> 
								<?
									db_input('y80_numcgm',10,$Iy80_numcgm,true,'text',$db_opcao," onchange='js_pesquisay80_numcgm(false);'");
								
									db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
								?>
							</td>
						</tr>
						<tr>
							<td nowrap title="Atividades">
								<strong>
									<?
										db_ancora("Atividade","js_pesquisay83_ativ(true);",$db_opcao);
									?>
								</strong>
							</td>
							<td> 
								<?
									db_input('ativ',10,"",true,'text',$db_opcao," onchange='js_pesquisay83_ativ(false);'");
								
									db_input('q03_descr',40,$Iq03_descr,true,'text',3,'');
							  ?>
							</td>
						</tr>
						<tr>
							<td nowrap title="<?=@$Tj14_nome?>">
								<?
									db_ancora(@$Lj14_nome,"js_pesquisaruas(true);",($db_opcao == 3 || $db_opcao == 33)?3:1);
								?>
							</td>
							<td> 
								<?
									db_input('y80_codrua',10,$Iy80_codrua,true,'text',$db_opcao," onChange='js_pesquisaruas(false)'");
								
									db_input('j14_nome',40,$Ij14_nome,true,'text',3,'');
								?>
							</td>
						</tr>
						<tr>
						 	<td nowrap title="<?=@$Tj13_descr?>">
								<?
									db_ancora(@$Lj13_descr,"js_pesquisabairro(true);",($db_opcao == 3 || $db_opcao == 33)?3:1);
								?>
							</td>
							<td> 
								<?
									db_input('y80_codbairro',10,$Iy80_codbairro,true,'text',$db_opcao," onChange='js_pesquisabairro(false)'");
										 
									db_input('j13_descr',40,$Ij13_descr,true,'text',3,'');
								?>
							</td>
						</tr>


						<tr>
							<td nowrap title="Mês">
								 <b>Mês:</b>
							</td>
							<td> 
								<?
									db_input('mes',5,$mes,true,'text',$db_opcao);
								?>
							</td>
						</tr>


						<tr>
							<td nowrap title="<?=@$Ty80_data?>">
								 <?=@$Ly80_data?>
							</td>
							<td> 
								<?
									db_inputdata('dtini',@$dia,@$mes,@$ano,true,'text',$db_opcao,"");
								?>
								&nbsp;&nbsp;À&nbsp;&nbsp;
								<?
									db_inputdata('a',@$diaa,@$mesa,@$anoa,true,'text',$db_opcao,"");
								?>
							</td>
						</tr>
						<tr height="10"></tr>
								
							<table align="center" border="0" width="100%" >		
								<tr>
									<td align="left">
										<strong>Opções : </strong>
									</td>
									<td align="left">
										<select name="baixados" style="width:150px;">
											 <option name="tipo1" value="nao">Não baixados</option>
											 <option name="tipo1" value="bai">Baixados		</option>
											 <option name="tipo1" value="tod">Todos				</option>
										</select>
									</td>
								
									<td>
										<strong>Atividade : </strong>
									</td>
									<td align="left">
										<select name="selativ" style="width:150px;">
											 <option name="tipo1" value="tod">Todos		 </option>
											 <option name="tipo1" value="pri">Principal</option>
										</select>
									</td>
								</tr>
								
								<tr>
									<td align="left">
										<strong>Tipo : </strong>
									</td>
									<td align="left">
										<select name="tipo" style="width:150px;">
											 <option name="tipo1" value="sim">Sintético </option>
											 <option name="tipo1" value="ana">Analítico </option>
										</select>
									</td>
							
									<td>
										<strong>Ordem : </strong>
									</td>
									<td align="left">
										<select name="ordem" style="width:150px;">
											 <option name="tipo1" value="y80_codsani">Alvará					 </option>
											 <option name="tipo1" value="y80_numcgm" >CGM						   </option>
											 <option name="tipo1" value="q03_descr"  >Atividade				 </option>
											 <option name="tipo1" value="y80_data"	 >Data de Liberação</option>
										</select>
									</td>
							</table>
						</tr>
						<tr>
						  <td></td>
						  <td></td>
						</tr>
					</table>
				</fieldset>    
			</td>
    </tr>
	</table>
		<input name="consultar" type="button" value="Relatório" onClick="js_consultasani();js_limpacampos();" >
 </center>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
function js_limpacampos(){
    
		document.form1.y80_codsani.value = ''; 
    document.form1.y80_numcgm.value = ''; 
    document.form1.z01_nome.value = ''; 
    document.form1.ativ.value = ''; 
    document.form1.q03_descr.value = ''; 
    document.form1.dtini.value = ''; 
    document.form1.a.value = ''; 
    document.form1.y80_codbairro.value = ''; 
    document.form1.mes.value = ''; 
    document.form1.j13_descr.value = ''; 
    document.form1.y80_codrua.value = ''; 
    document.form1.j14_nome.value = ''; 

}
function js_consultasani(){
  f = document.form1; 
  if(f.y80_codrua.value == "" && f.y80_codbairro.value == "" && f.y80_codsani.value == "" && f.y80_numcgm.value == "" && f.ativ.value == "" && f.dtini.value == "" && f.a.value == "" && f.mes.value == ""){
    alert('Preencha um dos campos para o relatório!');
  }else{
	 
	   qry  = "?tipo="+document.form1.tipo.value;
	   qry += "&ordem="+document.form1.ordem.value;
	   qry += "&selativ="+document.form1.selativ.value;
	   qry += "&baixados="+document.form1.baixados.value;
	   qry += "&y80_codsani="+document.form1.y80_codsani.value;
	   qry += "&y80_numcgm="+document.form1.y80_numcgm.value;
	   qry += "&ativ="+document.form1.ativ.value;
	   qry += "&dataini="+document.form1.dtini_ano.value+"-"+document.form1.dtini_mes.value+"-"+document.form1.dtini_dia.value;
	   qry += "&datafim="+document.form1.a_ano.value+"-"+document.form1.a_mes.value+"-"+document.form1.a_dia.value; 
	   qry += "&rua="+document.form1.y80_codrua.value; 
	   qry += "&mes="+document.form1.mes.value; 
	   qry += "&bairro="+document.form1.y80_codbairro.value; 

		jan = window.open('fis2_relatoriosani002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    jan.moveTo(0,0);
  }
}
function js_pesquisay80_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.y80_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
  }
}
function js_mostracgm(chave,erro){
  document.form1.z01_nome.value = erro; 
  if(chave==true){ 
    document.form1.y80_numcgm.focus(); 
    document.form1.y80_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.y80_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_sanitario.php?funcao_js=parent.js_preenchepesquisa|y80_codsani','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_sanitario.php?pesquisa_chave='+document.form1.y80_codsani.value+'&funcao_js=parent.js_preenchepesquisa1','Pesquisa',false);
  }
}
function js_preenchepesquisa(chave){
  
  document.form1.y80_codsani.value = chave;
  db_iframe.hide();
}
function js_preenchepesquisa1(chave,nome,erro){
  if (erro==true){
  	document.form1.y80_codsani.value = "";
  	document.form1.y80_codsani.focus();
  }else{
  	document.form1.y80_codsani.value = chave;
  }  
}
function js_pesquisay83_ativ(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_ativid','func_ativid.php?funcao_js=parent.js_mostraativid1|q03_ativ|q03_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_ativid','func_ativid.php?pesquisa_chave='+document.form1.ativ.value+'&funcao_js=parent.js_mostraativid','Pesquisa',false);
  }
}
function js_mostraativid(chave,erro){
  document.form1.q03_descr.value = chave; 
  if(erro==true){ 
    document.form1.ativ.focus(); 
    document.form1.ativ.value = ''; 
  }
}
function js_mostraativid1(chave1,chave2){
  document.form1.ativ.value = chave1;
  document.form1.q03_descr.value = chave2;
  db_iframe_ativid.hide();
}
function js_abreconsulta(chave){
  js_OpenJanelaIframe('','db_iframe_consulta','fis3_consultasani002.php?y80_codsani='+chave,'Pesquisa',true,15);
}
function js_pesquisaruas(mostra){
if(mostra == true){
  js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preencheruas|j14_codigo|j14_nome','Pesquisa',true,15);
}else{
  js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?pesquisa_chave='+document.form1.y80_codrua.value+'&rural=1&funcao_js=parent.js_preencheruas1','Pesquisa',false,15);
}
}
function js_preencheruas(chave,chave1){
  document.form1.y80_codrua.value = chave;
  document.form1.j14_nome.value = chave1;
  db_iframe_ruas.hide();
}
function js_preencheruas1(chave,erro){
  document.form1.j14_nome.value = chave;
  if(erro == true){ 
    document.form1.y80_codrua.focus();
    document.form1.y80_codrua.value = '';
  }
  db_iframe_ruas.hide();
}
function js_pesquisabairro(mostra){
if(mostra == true){
  js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro|j13_codi|j13_descr','Pesquisa',true,15);
}else{
  js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?pesquisa_chave='+document.form1.y80_codbairro.value+'&rural=1&funcao_js=parent.js_preenchebairro1','Pesquisa',false,15);
}
}
function js_preenchebairro(chave,chave1){
  document.form1.y80_codbairro.value = chave;
  document.form1.j13_descr.value = chave1;
  db_iframe_bairro.hide();
}
function js_preenchebairro1(chave,erro){
  document.form1.j13_descr.value = chave;
  if(erro == true){  
    document.form1.y80_codbairro.value = '';
    document.form1.y80_codbairro.focus();
  }
  db_iframe_bairro.hide();
}
</script>