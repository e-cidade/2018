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

// recebe numcgm e variavel fechar com o nome do iframe

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_cgm_classe.php");
require_once("classes/db_cgmalt_classe.php");
require_once("classes/db_ruas_classe.php");
require_once("classes/db_issbase_classe.php");
require_once("classes/db_db_usuarios_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if (!isset($numcgm)) {
  
  db_redireciona("db_erros.php?fechar=true&db_erro=Número do CGM nao Informado");
  exit;
}



$clsqlinscricoes = new cl_issbase;
$sqlsocios		= $clsqlinscricoes->sqlinscricoes_socios(0,$numcgm,"cgmsocio.z01_nome");
$resultsocios = pg_exec($sqlsocios);

if(pg_numrows($resultsocios) != 0){
   $socios = true;
}else{
   $socios = false;
}

$clcgm				= new cl_cgm;
$clcgmalt			= new cl_cgmalt;
$cldb_cgmruas = new cl_cgm;

if(isset($lcgmalt) && $lcgmalt == "true"){

$sCamposAlt = " z05_sequencia, z05_ufcon as z01_ufcon,z05_uf as z01_uf, z05_telef as z01_telef, z05_telcon as z01_telcon, z05_telcel as z01_telcel,z05_profis as z01_profis,
								z05_numero as z01_numero,z05_numcon as z01_numcon,z05_numcgm as z01_numcgm,z05_nome as z01_nome,z05_nacion as z01_nacion,z05_munic as z01_munic,
								z05_muncon as z01_muncon,z05_login as z01_login, z05_incest as z01_incest, z05_ident as z01_ident, z05_estciv as z01_estciv, z05_ender as z01_ender,
								z05_endcon as z01_endcon,z05_emailc as z01_emailc, z05_email as z01_email, z05_cxpostal as z01_cxpostal, z05_cxposcon as z01_cxposcon, z05_cpf as z01_cpf,
								z05_compl as z01_compl, z05_comcon as z01_comcon, z05_cgccpf as z01_cgccpf, z05_cgc as z01_cgc, z05_cepcon as z01_cepcon, z05_cep as z01_cep,
								z05_celcon as z01_celcon, z05_cadast as z01_cadast, z05_bairro as z01_bairro, z05_baicon as z01_baicon, z05_mae as z01_mae, z05_pai as z01_pai,
								z05_nomefanta as z01_nomefanta, z05_contato  as z01_contato, z05_sexo as z01_sexo, z05_nasc as z01_nasc, z05_fax as z01_fax, z05_login_alt, z05_data_alt,
								z05_hora_alt, case when z05_tipo_alt = 'A' then 'Alteração' else 'Exclusão' end as tipo_alt, z05_ultalt as z01_ultalt";

	$result = $clcgmalt->sql_record($clcgmalt->sql_query_file($seqalt,$sCamposAlt));
}else{
	$result = $clcgm->sql_record($clcgm->sql_query_file($numcgm));
	if($clcgm->numrows==0){
		db_redireciona("db_erros.php?fechar=true&db_erro=Número do CGM nao Encontrado");
		exit;
	}

}

db_fieldsmemory($result,0);

$clcgm->rotulo->label();
$clcgmalt->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("DBtxt1");
$clrotulo->label("DBtxt5");
$clrotulo->label("DBtxt29");
$clrotulo->label("DBtxt31");
$clrotulo->label("j14_codigo");
$clrotulo->label("j13_codi");
$clrotulo->label("z01_cpf");
$clrotulo->label("z05_sequencia");
$clrotulo->label("nome");
$clrotulo->label("nome_alt");
$clrotulo->label("z04_rhcbo");
$clrotulo->label("rh70_descr");
$db_opcao = 3;

if(isset($z01_cgccpf) && $z01_cgccpf != ""){
  if(strlen($z01_cgccpf) == 11){

    $oDaoCgmFisico = db_utils::getDao('cgmfisico');
    $sSqlCgmFisico = $oDaoCgmFisico->sql_query(null, '*', null, "z04_numcgm = {$z01_numcgm}");
    $rsCgmFisico   = $oDaoCgmFisico->sql_record($sSqlCgmFisico);

    if ($oDaoCgmFisico->numrows > 0) {
      db_fieldsmemory($rsCgmFisico, 0);
      
      $rh70_descr = $rh70_estrutural . ' - ' . $rh70_descr;
      
    }

  }

}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>


/**
 * Abre o formulário de Lançar documentos passando a Flag "consulta" para identificar 
 */
function js_abreDocumentos(iNumCgm, sNome) {
// prot1_lancdoc001.php?z06_numcgm=35
  js_OpenJanelaIframe('',
                      'db_iframe_cgmdocumentos',
                      'prot1_lancdoc001.php?z06_numcgm='
                      +iNumCgm+'&z01_nome='+sNome+'&consulta=true',
                      'Consulta de Documentos',
                      true
                      );
}

function js_pesquisacgmalt(numcgm){
  js_OpenJanelaIframe('','db_iframe_cgmaltres',
                      'func_cgmaltresum.php?pesquisa_numcgm='
                      +numcgm+'&funcao_js=parent.js_mostracgmalt|z05_sequencia|z05_numcgm',
                      'Pesquisa',
                      true);
}

function js_mostracgmalt(iSeq,iNumcgm){
  db_iframe_cgmaltres.hide();
  document.location.href = "prot3_conscgm002.php?numcgm="+iNumcgm+"&seqalt="+iSeq+"&lcgmalt="+true+"&lcgmorig="+true; 
}

function js_pesquisacgmoriginal(iNumcgm){
	document.location.href = "prot3_conscgm002.php?numcgm="+iNumcgm+"&lcgmalt="+false+"&lcgmorig="+false; 
}


function js_socios(numcgm){
  func_nome.jan.location.href = 'cai3_gerfinanc018.php?opcao=socios&numcgm='+numcgm;
  func_nome.mostraMsg();
  func_nome.show();
  func_nome.focus();
}

function js_imprimir(numcgm){
	if(document.form1.z05_sequencia.value != ""){
		jan = window.open('prot2_cadcgm002.php?numcgm='+numcgm+'&seqalt='+document.form1.z05_sequencia.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	}else{
		jan = window.open('prot2_cadcgm002.php?numcgm='+numcgm,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	}	
jan.moveTo(0,0);
}

</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc"> 
	<center>
		<form name="form1" method="post" action="" >
			<table width="730" border="0" >
				<tr align="left" valign="top"> 
					<td>
						<fieldset>
							<table border="0">
								<?
								
									db_fieldsmemory(pg_exec("select munic, uf from db_config where codigo = " . db_getsession("DB_instit")),0);
									if(isset($z01_numcgm)){
										$result = $cldb_cgmruas->sql_record($cldb_cgmruas->sql_query($z01_numcgm,"*",""," cgm.z01_numcgm = $z01_numcgm"));
										if($cldb_cgmruas->numrows > 0){
											$municipio = "t";
										}else{
											if(strtoupper($munic) == strtoupper($z01_munic) && strtoupper($uf) == strtoupper($z01_uf)){
												$municipio = "t";
											}else{
												$municipio = "f";
											}
										}
										$consulta = 1;
									}
									
									if(strlen($z01_cgccpf) == 14){
										include("prot1_pjuridica.php");
									}else{
										include("prot1_pfisica.php");
									}
								
								?>
							</table>
						</fieldset>
					</td>
				</tr>
				<tr>		
					<td>		
						<table width="50%" border="0">
							<tr> 
								<td width="39%" align="center" title="<?=$TDBtxt1?>" valign="middle"> 
									<?//=$LDBtxt1?>
								</td>
								<td width="61%" align="center" valign="middle" title="<?=$TDBtxt5?>"> 
									<?//=$LDBtxt5?>
								</td>
							</tr>
							<tr align="center" valign="middle"> 
								<td width="39%"> 
									<fieldset>
										<legend align="center">
											<b><?=$LDBtxt1?></b>
										</legend>
										<table width="100%" border="0">
											<tr> 
												<td nowrap title="<?=@$Tz01_ender?>"> 
													<?=@$Lz01_ender?>
												</td>
												<td nowrap> 
													<?
														db_input('z05_sequencia',5,$Iz05_sequencia,true,'hidden',$db_opcao);
														db_input('j14_codigo',5,$Ij14_codigo,true,'hidden',$db_opcao);
														db_input('z01_ender',41,$Iz01_ender,true,'text',$db_opcao);
													?>
												</td>
											</tr>
											<tr> 
												<td width="29%" nowrap title="<?=@$Tz01_numero?>"> 
													<?=@$Lz01_numero?> 
												</td>
												<td width="71%" nowrap>
													<a name="AN3"> 
														<?
															db_input('z01_numero',8,$Iz01_numero,true,'text',$db_opcao);
														?>
														&nbsp; 
														<?=@$Lz01_compl?>
														<?
															db_input('z01_compl',10,$Iz01_compl,true,'text',$db_opcao);
														?>
													</a> 
												</td>
											</tr>
											<tr> 
												<td nowrap title="<?=@$Tz01_munic?>"> 
													<?=@$Lz01_munic?>
												</td>
												<td nowrap> 
													<?
														db_input('z01_munic',20,$Iz01_munic,true,'text',$db_opcao);
													?>
												</td>
											</tr>
											<tr> 
												<td nowrap title="<?=@$Tz01_uf?>"> 
													<?=@$Lz01_uf?>
												</td>
												<td nowrap> 
													<?
														db_input('z01_uf',2,$Iz01_uf,true,'text',$db_opcao);
													?>
												</td>
											</tr>
											<tr> 
												<td nowrap title="<?=@$Tz01_bairro?>"> 
													<?=@$Lz01_bairro?>
												</td>
												<td nowrap> 
													<?
														db_input('z01_bairro',25,$Iz01_uf,true,'text',$db_opcao);
														db_input('j13_codi',6,$Ij13_codi,true,'hidden',1);
													?>
												</td>
											</tr>
											<tr> 
												<td nowrap title="<?=@$Tz01_cep?>"> 
													<?=@$Lz01_cep?>
												</td>
												<td nowrap> 
													<?
														db_input('z01_cep',9,$Iz01_cep,true,'text',$db_opcao);
													?>
												</td>
											</tr>
											<tr> 
												<td nowrap title="<?=@$Tz01_telef?>"> 
													<?=@$Lz01_telef?>
												</td>
												<td nowrap> 
													<?
														db_input('z01_telef',12,$Iz01_telef,true,'text',$db_opcao);
													?>
												</td>
											</tr>
											<tr> 
												<td nowrap title="<?=@$Tz01_telcel?>"> 
													<?=@$Lz01_telcel?>
												</td>
												<td nowrap> 
													<?
														db_input('z01_telcel',12,$Iz01_telcel,true,'text',$db_opcao);
													?>
												</td>
											</tr>
											<tr> 
												<td nowrap title="<?=@$Tz01_fax?>"> 
													<?=@$Lz01_fax?>
												</td>
												<td nowrap> 
													<?
														db_input('z01_fax',12,$Iz01_fax,true,'text',$db_opcao);
													?>
												</td>
											</tr>
											<tr> 
												<td nowrap title="<?=@$Tz01_email?>"> 
													<?=@$Lz01_email?>
												</td>
												<td nowrap> 
													<?
														db_input('z01_email',30,$Iz01_email,true,'text',$db_opcao);
													?>
												</td>
											</tr>
											<tr> 
												<td nowrap title="<?=@$Tz01_cxpostal?>"> 
													<?=@$Lz01_cxpostal?>
												</td>
												<td nowrap> 
													<?
														db_input('z01_cxpostal',10,$Iz01_cxpostal,true,'text',$db_opcao);
													?>
												</td>
											</tr>
										</table>
									</fieldset>
								</td>
							  <td width="61%"> 
									<fieldset>
										<legend align="center">
											<b><?=$LDBtxt5?></b>
										</legend>
										<table width="100%" border="0">
											<tr> 
												<td nowrap title="<?=@$Tz01_endcon?>"> 
													<?=@$Lz01_endcon?>
												</td>
												<td nowrap> 
													<?
														db_input('z01_endcon',40,$Iz01_endcon,true,'text',$db_opcao);
													?>
												</td>
											</tr>
											<tr> 
												<td width="29%" nowrap title="<?=@$Tz01_numcon?>"> 
													<?=@$Lz01_numcon?>
												</td>
												<td width="71%" nowrap > 
													<?
														db_input('z01_numcon',8,$Iz01_numcon,true,'text',$db_opcao);
													?>
													<?=@$Lz01_comcon?>
													<?
														db_input('z01_comcon',10,$Iz01_comcon,true,'text',$db_opcao);
													?>
												</td>
											</tr>
											<tr> 
												<td nowrap title="<?=@$Tz01_muncon?>"> 
													<?=@$Lz01_muncon?>
												</td>
												<td nowrap> 
													<?
														db_input('z01_muncon',20,$Iz01_muncon,true,'text',$db_opcao);
													?>
												</td>
											</tr>
											<tr> 
												<td nowrap title="<?=$Tz01_ufcon?>"> 
													<?=@$Lz01_ufcon?>
												</td>
												<td nowrap> 
													<?
														db_input('z01_ufcon',2,$Iz01_ufcon,true,'text',$db_opcao);
													?>
												</td>
											</tr>
											<tr> 
												<td nowrap title="<?=@$Tz01_baicon?>"> 
													<?=@$Lz01_baicon?>
												</td>
												<td nowrap> 
													<?
														db_input('z01_baicon',25,$Iz01_baicon,true,'text',$db_opcao);
													?>
												</td>
											</tr>
											<tr> 
												<td nowrap title="<?=@$Tz01_cepcon?>"> 
													<?=@$Lz01_cepcon?>
												</td>
												<td nowrap> 
													<?
														db_input('z01_cepcon',9,$Iz01_cepcon,true,'text',$db_opcao);
													?>
												</td>
											</tr>
											<tr> 
												<td nowrap title="<?=@$Tz01_telcon?>"> 
													<?=@$Lz01_telcon?>
												</td>
												<td nowrap> 
													<?
														db_input('z01_telcon',12,$Iz01_telcon,true,'text',$db_opcao);
													?>
												</td>
											</tr>
											<tr> 
												<td nowrap title="<?=@$Tz01_celcon?>"> 
													<?=@$Lz01_celcon?>
												</td>
												<td nowrap> 
													<?
														db_input('z01_celcon',12,$Iz01_celcon,true,'text',$db_opcao);
													?>
												</td>
											</tr>
											<tr> 
												<td nowrap title="<?=@$Tz01_emailc?>"> 
													<?=@$Lz01_emailc?>
												</td>
												<td nowrap> 
													<?
														db_input('z01_emailc',30,$Iz01_emailc,true,'text',$db_opcao);
													?>
												</td>
											</tr>
											<tr> 
												<td nowrap title="<?=@$Tz01_cxposcon?>"> 
													<?=@$Lz01_cxposcon?>
												</td>
												<td nowrap> 
													<?
														db_input('z01_cxposcon',10,$Iz01_cxposcon,true,'text',$db_opcao);
													?>
												</td>
											</tr>
											<tr>
												<td>&nbsp;</td>
											<tr>
										</table>
									</fieldset>
							  </td>
							</tr>
							<tr align="left" valign="middle">
								<td height="21" colspan="2" nowrap>
									<fieldset>
										<table  border="0">
											<tr>
												<td>
													<table>
														<tr> 
															<td align='left' width="40%">
																<?
																 echo $Lz01_login;
																?>
															</td>
															<td>
																<?
																	$cldb_usuarios = new cl_db_usuarios;
																	$result				 = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file($z01_login,'nome'));
																	$numrowscgm		 = $cldb_usuarios->numrows;
																	
																	if($numrowscgm>0){
																		db_fieldsmemory($result,0);
																		db_input("nome",30,$Inome,true,'text',3);
																	}
																	
																	$z01_login = db_getsession("DB_id_usuario");
																	db_input("z01_login",6,$Iz01_login,true,'hidden',3);
																?>
															</td>
														</tr>
														<tr>
															<td width="18%"> 
																<?=@$Lz01_cadast?>
															</td>
															<td width="96%" nowrap>
																<?
																	db_inputdata('z01_cadast',@$z01_cadast_dia,@$z01_cadast_mes,@$z01_cadast_ano,true,'text',3);
																?>
															</td>
														</tr>
														<tr>
															<td width="18%"> 
																<?=@$Lz01_ultalt?>
															</td>
															<td width="96%" nowrap>
																<?
																	db_inputdata('z01_cadast',@$z01_ultalt_dia,@$z01_ultalt_mes,@$z01_ultalt_ano,true,'text',3);
																?>
															</td>
														</tr>
													</table>
												</td>
												<td align='left' width="53%">	
												
												<? if(isset($lcgmalt) && $lcgmalt == "true"){ ?>
												
													<fieldset>
														<legend>
															<b>&nbsp;Dados Alteração&nbsp;</b>
														</legend>
														<table>	
															<tr>
																<td>
																		<?=@$Lz05_login_alt?>
																</td>
																<td>
																	<?
																		$result				 = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file($z05_login_alt,'nome as nome_alt'));
																		$numrowscgm		 = $cldb_usuarios->numrows;
																		
																		if($numrowscgm > 0){
																			db_fieldsmemory($result,0);
																			db_input("nome_alt",30,"",true,'text',3);
																		}
																	?>
																</td>
															</tr>
															<tr>
																<td>
																		<?=@$Lz05_data_alt?>
																</td>
																<td>
																	<?
																		db_inputdata("z05_data_alt",@$z05_data_alt_dia,@$z05_data_alt_mes,@$z05_data_alt_ano,true,'text',3);
																	?>
																</td>
															</tr>
															<tr>
																<td>
																		<?=@$Lz05_hora_alt?>
																</td>
																<td>
																	<?
																		db_input("z05_hora_alt",10,$Iz05_hora_alt,true,'text',3);
																	?>
																</td>
															</tr>
															<tr>
																<td>
																		<?=@$Lz05_tipo_alt?>
																</td>
																<td>
																	<?
																		db_input("tipo_alt",10,"",true,'text',3);
																	?>
																</td>
															</tr>
														</table>	
													</fieldset>
												
												<? } ?>
												
												</td>	
											</tr>	
										</table>
									</fieldset>
								</td>
							</tr>
							<tr align="center" valign="middle"> 
								<td height="30" colspan="2" nowrap> 
									<?
										// CODIGO ADICIONADO PARA ATUALIZAR TELA DO CADASTRO DO CGM
										// PASSAR NA VARIÁVEL executalocation QUAL ARQUIVO DEVERÁ ABRIR
							
										$locationhreh = "";
										
										if(isset($executalocation)){
											$locationhreh = "parent.location.href='$executalocation';";
										}
										///////////////////////////////////////////////////////////
									?>
									<input name="pesquisar"   type="button" id="pesquisar"   value="Fechar"   
									       onclick="parent.<?=$fechar?>.hide();<?=$locationhreh?>"> 
									<input name="imprimir"    type="button" id="imprimir"    value="Imprimir"   
									       onclick="js_imprimir(<?=$numcgm?>);">
									<input name="documentos"  type="button" id="documentos"  value="Documentos" 
									       onclick="js_abreDocumentos(<?=$numcgm?>, '<?=$z01_nomecomple?>');">
									<?
										if($socios == true){
											?>
												<input name="socios" type="button" id="socios" value="Sócios" onclick="js_socios(<?=$numcgm?>);">
											<?
										}
										
										$rsVerificaAlt = $clcgmalt->sql_record($clcgmalt->sql_query_file(null,"*",null,"z05_numcgm = $numcgm and z05_tipo_alt = 'A'"));
										if($clcgmalt->numrows > 0 ){
											?>
												<input name="alteracoes" type="button" id="alteracoes" value="Alterações" onclick="js_pesquisacgmalt(<?=$numcgm?>);">
											<?
										}
								    
										if(isset($lcgmorig) && $lcgmorig == "true"){
											?>
												<input name="original" type="button" id="original" value="Original" onclick="js_pesquisacgmoriginal(<?=$numcgm?>);">
											<?
										}
									?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
		  </table>
		</form>
	</center>
</body>
</html>
<?

$func_nome = new janela('func_nome','');
$func_nome ->posX=1;
$func_nome ->posY=20;
$func_nome ->largura=740;
$func_nome ->altura=400;
$func_nome ->titulo="Sócios";
$func_nome ->iniciarVisivel = false;
$func_nome ->mostrar();

?>