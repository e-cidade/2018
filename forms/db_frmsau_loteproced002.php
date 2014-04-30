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
?>
<tr>
				<td colspan="4">
					<div id="divProcedimento">
					<table>

						<!-- PROFISSIONAL -->
						<tr>
							<td nowrap title="<?=@$Tsd03_i_codigo?>" width="80">
							<?
							db_ancora ( @$Lsd03_i_codigo, "js_pesquisasd03_i_codigo(true);", $db_opcao );
							?>
							</td>
							<td>
							<?
							db_input ( 'sd29_i_codigo', 10, $Isd29_i_codigo, true, 'hidden', 3, "" );
							db_input ( 'tmp_i_registro', 10, @$tmp_i_registro, true, 'hidden', 3, "" );
							db_input ( 'sd03_i_codigo', 10, $Isd03_i_codigo, true, 'text', $db_opcao, " onchange='js_pesquisasd03_i_codigo(false);' onFocus='js_foco(this, \"z01_nome\");' " );
							?>
							</td>
							<td colspan="2">
							<?
							db_input('z01_nome',49,$Iz01_nome,true,'text',$db_opcao," onchange='js_pesquisaz01_nome();' onFocus='js_foco(this, \"sd63_c_procedimento\");' ");
							?>
							</td>
						</tr>
			
						<!-- CBO -->
						<tr>
							<td nowrap title="<?=@$Tsd04_i_cbo?>">
							<?
							db_ancora ( @$Lsd04_i_cbo, "js_pesquisasd04_i_cbo(true);", $db_opcao );
							?>
							</td>
							<td>
							<?
							db_input ( 'sd29_i_profissional', 10, $Isd29_i_profissional, true, 'hidden', $db_opcao, " onchange='js_pesquisasd04_i_cbo(false);'" );
							db_input ( 'rh70_sequencial', 10, $Irh70_sequencial, true, 'hidden', $db_opcao, "" );
							db_input ( 'rh70_estrutural', 10, $Irh70_estrutural, true, 'text', $db_opcao, " onchange='js_pesquisasd04_i_cbo(false);' onFocus='js_foco(this, \"sd63_c_procedimento\");' " );
							?>
							</td>
							<td colspan="2">
							<?
							db_input ( 'rh70_descr', 49, $Irh70_descr, true, 'text', 3, '' );
							?>
							</td>
						</tr>
			
						<!-- PROCEDIMENTO -->
						<tr>
							<td nowrap title="<?=@$Tsd29_i_procedimento?>">
							<?
							db_ancora ( @$Lsd29_i_procedimento, "js_pesquisasd29_i_procedimento(true);", $db_opcao );
							?>
							</td>
							<td>
							<?
							db_input ( 'sd29_i_procedimento', 10, $Isd29_i_procedimento, true, 'hidden', $db_opcao, " onchange='js_pesquisasd29_i_procedimento(false);'" );
							db_input ( 'sd63_c_procedimento', 10, $Isd63_c_procedimento, true, 'text', $db_opcao, " onchange='js_pesquisasd29_i_procedimento(false);' onFocus='js_foco(this, \"sd70_c_cid\");' " );
							?>
							</td>
							<td colspan="2">
							<?
							db_input ( 'sd63_c_nome', 49, $Isd63_c_nome, true, 'text', 3, '' );
							?>       
							</td>
						</tr>
						<!-- CID -->
						<tr>
							<td nowrap title="<?=@$Tsd70_c_cid?>" valign="top" align="top">
								<?
								db_ancora(@$Lsd70_c_cid,"js_pesquisasd70_c_cid(true); \" onFocus='js_foco(this, \"sd24_t_diagnostico\");' ",$db_opcao);
								?>
							</td>
							<td valign="top" align="top" colspan=3>
								<?
								db_input('sd70_i_codigo',10,$Isd70_i_codigo,true,'hidden',$db_opcao);
								db_input('sd70_c_cid',10,$Isd70_c_cid,true,'text',$db_opcao,"onchange='js_pesquisasd70_c_cid(false);' onFocus='js_foco(this, \"sd29_d_data\");' onblur='js_validacid(this);'");
								db_input('sd70_c_nome',49,$Isd70_c_nome,true,'text',3,"tabIndex='0' ");
								?>
							</td>
						</tr>
						<!-- DATA / HORA -->
						<tr>
							<td nowrap title="<?=@$Tsd29_d_data?>">
							<?=@$Lsd29_d_data?>
							</td>
							<td colspan="3">
							<table width="100%" cellspacing="0" cellpadding="0">
								<tr>
									<td> 
									<?
									db_inputdata ( 'sd29_d_data', @$sd29_d_data_dia, @$sd29_d_data_mes, @$sd29_d_data_ano, true, "text\" onFocus=\"nextfield='sd29_c_hora'\" onblur=\"js_validadata(this.name);\"  ", $db_opcao );
									?>
									</td>
									<td align="<?=isset($intQuant)?'center':'right'?>" nowrap title="<?=@$Tsd29_c_hora?>">
									<?=@$Lsd29_c_hora?>
									<?db_input ( 'sd29_c_hora', 5, $Isd29_c_hora, true, 'text', $db_opcao, "OnKeyUp=mascara_hora(this.value,'sd29_c_hora',event,false) onFocus='js_foco(this, \"".(isset($intQuant)?"intQuant":"sd29_t_tratamento")."\" );'  onblur=\"js_verifica_hora_webseller(this.value,this.name);\"  "   )?>
									</td>
									<? if( isset($intQuant) ){ ?>
										<td align="right" nowrap >
										<b>Quantidade:</b>
										<?
										db_input ( 'intQuant', 5, $intQuant, true, 'text', $db_opcao, "onFocus='js_foco(this, \"sd29_t_tratamento\");'  ", "", "", "", 3 )
										?>
										</td>
									<?} ?>
								</tr>
							</table>
							</td>
			
						</tr>
						<!-- TRATAMENTO -->
						<tr>
							<td valign="top" nowrap title="<?=@$Tsd29_t_tratamento?>">
							<?=@$Lsd29_t_tratamento?>
							</td>
							<td colspan="3"> 
							<?
							//$sd29_t_tratamento = ! isset ( $sd29_t_tratamento ) ? ' ' : $sd29_t_tratamento;
							db_textarea ( 'sd29_t_tratamento', 2, 59, @$sd29_t_tratamento, true, 'text', $db_opcao, " onFocus='js_foco(this, \"btnGravar\");' " );
							?>
							</td>
						</tr>
					</table>
					</div>
				</td>
			</tr>