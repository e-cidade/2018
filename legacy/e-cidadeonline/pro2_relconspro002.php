<?
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

include ("fpdf151/pdf.php");
include ("libs/db_sql.php");
//include ("libs/db_sessoes.php");
//include ("libs/db_usuariosonline.php");
include ("classes/db_protprocesso_classe.php");
include ("classes/db_procandam_classe.php");
include ("classes/db_proctransfer_classe.php");
include ("classes/db_proctransferproc_classe.php");
include ("classes/db_proctransand_classe.php");
include ("classes/db_proctransferintand_classe.php");
include ("classes/db_proctransferint_classe.php");
include ("classes/db_procandamint_classe.php");
include ("classes/db_procandamintand_classe.php");
include ("classes/db_arqproc_classe.php");
include ("classes/db_arqandam_classe.php");
include ("dbforms/db_funcoes.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$clprotprocesso = new cl_protprocesso;
$clprocandam = new cl_procandam;
$clproctransfer = new cl_proctransfer;
$clproctransferproc = new cl_proctransferproc;
$clproctransand = new cl_proctransand;
$clproctransferintand = new cl_proctransferintand;
$clproctransferint = new cl_proctransferint;
$clprocandamint = new cl_procandamint;
$clprocandamintand = new cl_procandamintand;
$clarqproc = new cl_arqproc;
$clarqandam = new cl_arqandam;

$cod_procandamint = 0;
$arquiv = false;
$arqant = false;

// seleciona o nome da instituição pelo código do processo e coloca o resultado na váriavel $nomeinstabrev
$sql  = "select nomeinstabrev from db_config where codigo = ";
$sql .= " (select p58_instit from protprocesso where p58_codproc = $codproc)";
  $sRetorno = db_query($sql);
  $rsResultado = pg_fetch_assoc($sRetorno);
  $nomeinstabrev = $rsResultado["nomeinstabrev"];

if (isset ($codproc) && $codproc != "") {

	$head3 = "Consulta de Processo ";
	$head4 = "Processo N° $codproc";

	$pdf = new PDF();
	$pdf->Open();
	$pdf->AliasNbPages();
	$pdf->addpage('L');
	$total = 0;
	$pdf->setfillcolor(235);
	$pdf->setfont('arial', 'b', 8);
	$troca = 1;
	$alt = 4;
	$total = 0;

		$result_protprocesso = $clprotprocesso->sql_record($clprotprocesso->sql_query($codproc));
	if ($clprotprocesso->numrows != 0) {
		
		db_fieldsmemory($result_protprocesso, 0);
		
	  $pdf->cell(50, $alt, 'Número de Controle do Processo :', 0, 0, "R", 0);
	  $pdf->cell(75, $alt, $codproc, 0, 1, "L", 0);
	  
	  $pdf->cell(50, $alt, 'Número do Processo :', 0, 0, "R", 0);
		$pdf->cell(75, $alt, $p58_numero . '/' . $p58_ano, 0, 0, "L", 0);
		$pdf->cell(50, $alt, 'Titular do Processo :', 0, 0, "R", 0);
		$pdf->cell(75, $alt, $z01_nome, 0, 1, "L", 0);

		$pdf->cell(50, $alt, 'Data :', 0, 0, "R", 0);
		$pdf->cell(75, $alt, db_formatar($p58_dtproc, 'd'), 0, 0, "L", 0);
		$pdf->cell(50, $alt, 'Hora :', 0, 0, "R", 0);
		$pdf->cell(75, $alt, $p58_hora, 0, 1, "L", 0);

		$pdf->cell(50, $alt, 'Tipo :', 0, 0, "R", 0);
		$pdf->cell(75, $alt, $p51_descr, 0, 0, "L", 0);
		$pdf->cell(50, $alt, 'Atendente :', 0, 0, "R", 0);
		$pdf->cell(75, $alt, $nome, 0, 1, "L", 0);

		$pdf->cell(50, $alt, 'Requerente :', 0, 0, "R", 0);
		$pdf->cell(75, $alt, $p58_requer, 0, 0, "L", 0);

		$pdf->cell(50, $alt, 'Instituicao :', 0, 0, "R", 0);
		$pdf->cell(75, $alt, $nomeinst, 0, 1, "L", 0);

		$pdf->cell(50, $alt, 'Observação :', 0, 0, "R", 0);
		$pdf->multicell(175, $alt, $p58_obs, 0, "L", 0);

		$pdf->Ln(2);
		
		$result_proctransferproc = $clproctransferproc->sql_record($clproctransferproc->sql_query_file(null, null, "distinct *", "p63_codtran", "p63_codproc = $codproc"));
		if ($clproctransferproc->numrows != 0) {			
			$tramite = 0;
			$exe = $clproctransferproc->numrows - 1;
			for ($y = 0; $y < $clproctransferproc->numrows; $y ++) {				
				if ($pdf->gety() > $pdf->h - 30 || $troca != 0) {
					if ($troca == 0) {
						$pdf->addpage('L');
					}
					$pdf->setfont('arial', 'b', 8);
					$pdf->cell(15, $alt, 'Data', 1, 0, "C", 1);
					$pdf->cell(10, $alt, 'Hora', 1, 0, "C", 1);
					$pdf->cell(40, $alt, 'Departamento', 1, 0, "C", 1);
					$pdf->cell(25, $alt, 'Instit', 1, 0, "C", 1);
					//$pdf->cell(45, $alt, 'Login', 1, 0, "C", 1);
					$pdf->cell(80, $alt, 'Ocorrência', 1, 0, "C", 1);
					$pdf->cell(65, $alt, 'Despacho', 1, 1, "C", 1);
					if ($troca == 1) {
						$pdf->setfont('arial', '', 7);
						$pdf->cell(15, $alt, db_formatar($p58_dtproc, 'd'), 0, 0, "C", 0);
						$pdf->cell(10, $alt, $p58_hora, 0, 0, "C", 0);
						$pdf->cell(40, $alt, substr($p58_coddepto.'-'.$descrdepto,0,25), 0, 0, "L", 0);
						$pdf->cell(25, $alt, substr($nomeinstabrev,0,14), 0, 0, "L", 0);
						//$pdf->cell(45, $alt, substr($nome,0,25), 0, 0, "L", 0);
						$pdf->cell(80, $alt, 'Processo Criado', 0, 0, "L", 0);
						$pdf->cell(65, $alt, '', 0, 1, "C", 0);
					}
					$troca = 0;
				}				
				$pdf->setfont('arial', '', 7);
				db_fieldsmemory($result_proctransferproc, $y);
				$result_proctransfer = $clproctransfer->sql_record($clproctransfer->sql_query_deps(null, "atual.instit,instiatual.nomeinstabrev,p62_codtran,p62_dttran,p62_hora,p62_coddepto,p62_coddeptorec,atual.descrdepto as deptoatual,destino.descrdepto as deptodestino,destino.coddepto as coddeptodestino, usu_atual.nome as nome", null, "p62_codtran = $p63_codtran"));
				if ($clproctransfer->numrows != 0) {
					db_fieldsmemory($result_proctransfer, 0);
					if ($tramite == 0) {						
						$pdf->cell(15, $alt, db_formatar($p62_dttran, 'd'), 0, 0, "C", 0);
						$pdf->cell(10, $alt, $p62_hora, 0, 0, "C", 0);
						$pdf->cell(40, $alt, substr($p62_coddepto.'-'.$deptoatual,0,25), 0, 0, "L", 0);
						$pdf->cell(25, $alt, substr($nomeinstabrev,0,14), 0, 0, "L", 0);
						//$pdf->cell(45, $alt, substr($nome,0,25), 0, 0, "L", 0);
						$pdf->cell(80, $alt, 'Tramite Inicial: ' . $deptodestino, 0, 0, "L", 0);
						$pdf->cell(65, $alt, '', 0, 1, "C", 0);
						$tramite = 1;
					} else {
						$result_proctransand = $clproctransand->sql_record($clproctransand->sql_query_consandam("", "p64_codandam", null, "p64_codtran = $p62_codtran and p61_codproc = $codproc  "));
						if ($clproctransand->numrows != 0) {
							db_fieldsmemory($result_proctransand, 0);
							$result_procandam = $clprocandam->sql_record($clprocandam->sql_query_com(null, "procandam.*", null, "p61_codandam = $p64_codandam and p61_codproc = $codproc "));
							if ($clprocandam->numrows != 0) {
								db_fieldsmemory($result_procandam, 0);
							}
						}
						$result_arqandam = $clarqandam->sql_record($clarqandam->sql_query_file(null, "*", null, "p69_codandam = $p61_codandam"));
						if ($p62_coddepto == $p62_coddeptorec && $clarqandam->numrows != 0) {
							$arquiv = true;
						} else {
							$pdf->cell(15, $alt, db_formatar($p62_dttran, 'd'), 0, 0, "C", 0);
							$pdf->cell(10, $alt, $p62_hora, 0, 0, "C", 0);
							$pdf->cell(40, $alt, substr($p62_coddepto.'-'.$deptoatual,0,25), 0, 0, "L", 0);
							$pdf->cell(25, $alt, substr($nomeinstabrev,0,14), 0, 0, "L", 0);
							//$pdf->cell(45, $alt, substr($nome,0,25), 0, 0, "L", 0);
							$pdf->cell(80, $alt, substr('Tranferência p/ o Departamento:'.$deptodestino, 0, 58), 0, 0, "L", 0);
							$pdf->cell(65, $alt, '', 0, 1, "C", 0);
						}
					}
					$result_proctransand = $clproctransand->sql_record($clproctransand->sql_query_consandam("", "*", null, "p64_codtran = $p62_codtran and p61_codproc = $codproc  "));
					if ($clproctransand->numrows != 0) {
						db_fieldsmemory($result_proctransand, 0);
						$result_procandam = $clprocandam->sql_record($clprocandam->sql_query_com(null, "*", null, "p61_codandam = $p64_codandam"));
						if ($clprocandam->numrows != 0) {
							db_fieldsmemory($result_procandam, 0);
						  $pdf->cell(15, $alt, db_formatar($p61_dtandam, 'd'), 0, 0, "C", 0);
							$pdf->cell(10, $alt, $p61_hora, 0, 0, "C", 0);
							$pdf->cell(40, $alt, substr($p61_coddepto.'-'.$descrdepto,0,25), 0, 0, "L", 0);
							$pdf->cell(25, $alt, substr($nomeinstabrev,0,14), 0, 0, "L", 0);
							//$pdf->cell(45, $alt, substr($nome,0,25), 0, 0, "L", 0);

							if ($arquiv == true) {
								$result_arqandam = $clarqandam->sql_record($clarqandam->sql_query_file(null, "*", null, "p69_codandam = $p61_codandam"));
								if ($clarqandam->numrows != 0) {
									db_fieldsmemory($result_arqandam, 0);
									$arqant = true;
									if ($p69_arquivado == 't') {
										$pdf->cell(80, $alt, 'Processo Arquivado', 0, 0, "L", 0);
									} else {
										$pdf->cell(80, $alt, 'Desarquivamento', 0, 0, "L", 0);
									}
								} else {
									$pdf->cell(80, $alt, 'Desarquivamento', 0, 0, "L", 0);
								}
							} else {
								$pdf->cell(80, $alt, 'Recebeu Tranferência', 0, 0, "L", 0);
							}
							$pdf->multicell(65, $alt, $p61_despacho, 0, "L", 0);							      	    

							$result_procandamint_des = $clprocandamint->sql_record($clprocandamint->sql_query_sim(null, "*", "p78_sequencial", "p78_codandam = $p61_codandam and p78_publico <> 'f' "));
							if ($clprocandamint->numrows != 0) {
								for ($x = 0; $x < $clprocandamint->numrows; $x ++) {
									db_fieldsmemory($result_procandamint_des, $x);
									if ($p78_transint == 't') {
										break;
									} else {
										if ($pdf->gety() > $pdf->h - 30 || $troca != 0) {

											if ($troca == 0) {
												$pdf->addpage('L');
											}
											$pdf->setfont('arial', 'b', 8);
											$pdf->cell(15, $alt, 'Data', 1, 0, "C", 1);
											$pdf->cell(10, $alt, 'Hora', 1, 0, "C", 1);
											$pdf->cell(40, $alt, 'Departamento', 1, 0, "C", 1);
											$pdf->cell(25, $alt, 'Instituição', 1, 0, "C", 1);
											//$pdf->cell(45, $alt, 'Login', 1, 0, "C", 1);
											$pdf->cell(80, $alt, 'Ocorrência', 1, 0, "C", 1);
											$pdf->cell(65, $alt, 'Despacho', 1, 1, "C", 1);

											$troca = 0;
											$pdf->setfont('arial', '', 8);
										}

										$pdf->cell(15, $alt, db_formatar($p78_data, 'd'), 0, 0, "C", 0);
										$pdf->cell(10, $alt, $p78_hora, 0, 0, "C", 0);
										$pdf->cell(40, $alt, substr($p61_coddepto.'-'.$descrdepto,0,25), 0, 0, "L", 0);
										$pdf->cell(25, $alt, substr($nomeinstabrev,0,14), 0, 0, "L", 0);
										//$pdf->cell(45, $alt, substr($nome,0,25), 0, 0, "L", 0);
										$pdf->cell(80, $alt, 'Despacho Interno', 0, 0, "L", 0);
										$pdf->multicell(65, $alt, $p78_despacho, 0, "L", 0);
										$cod_procandamint = $p78_sequencial;
									}
								}
							}
							$result_proctransferintand = $clproctransferintand->sql_record($clproctransferintand->sql_query_file(null, "*", "p87_codtransferint", "p87_codandam = $p61_codandam"));
							if ($clproctransferintand->numrows != 0) {
								for ($yy = 0; $yy < $clproctransferintand->numrows; $yy ++) {
									if ($pdf->gety() > $pdf->h - 30 || $troca != 0) {

										if ($troca == 0) {
											$pdf->addpage('L');
										}
										$pdf->setfont('arial', 'b', 8);
										$pdf->cell(15, $alt, 'Data', 1, 0, "C", 1);
										$pdf->cell(10, $alt, 'Hora', 1, 0, "C", 1);
										$pdf->cell(40, $alt, 'Departamento', 1, 0, "C", 1);
										$pdf->cell(25, $alt, 'Instituição', 1, 0, "C", 1);
										//$pdf->cell(45, $alt, 'Login', 1, 0, "C", 1);
										$pdf->cell(80, $alt, 'Ocorrência', 1, 0, "C", 1);
										$pdf->cell(65, $alt, 'Despacho', 1, 1, "C", 1);

										$troca = 0;
										$pdf->setfont('arial', '', 8);
									}
									db_fieldsmemory($result_proctransferintand, $yy);
									
									$result_proctransferint = $clproctransferint->sql_record($clproctransferint->sql_query_andusu(null, "p88_codigo,p88_data,p88_hora,p88_despacho,p88_publico,atual.nome as usuatual,destino.nome as usudestino", null, "p88_codigo=$p87_codtransferint"));
									if ($clproctransferint->numrows != 0) {
										db_fieldsmemory($result_proctransferint, 0);
										$pdf->cell(15, $alt, db_formatar($p88_data, 'd'), 0, 0, "C", 0);
										$pdf->cell(10, $alt, $p88_hora, 0, 0, "C", 0);
										$pdf->cell(40, $alt, substr($p61_coddepto.'-'.$descrdepto,0,25), 0, 0, "L", 0);
										$pdf->cell(25, $alt, substr($nomeinstabrev,0,14), 0, 0, "L", 0);
										//$pdf->cell(45, $alt, substr($usuatual,0,25), 0, 0, "L", 0);
										$pdf->cell(80, $alt, substr('Tranferência Interna para '.$usudestino, 0, 58), 0, 0, "L", 0);
										
										if ( $p88_publico !=  'f' ){
										   $pdf->multicell(65, $alt, $p88_despacho, 0, "L", 0);
										}else{
										   $pdf->multicell(65, $alt, "", 0, "L", 0);
										}
										
										$result_procandamintand = $clprocandamintand->sql_record($clprocandamintand->sql_query_file(null, "*", "p86_codtrans", "p86_codtrans=$p88_codigo and p86_codandam = $p87_codandam "));
										if ($clprocandamintand->numrows != 0) {
											db_fieldsmemory($result_procandamintand, 0);
											$result_procandamint_trans = $clprocandamint->sql_record($clprocandamint->sql_query_sim(null, "*", "p78_sequencial", "p78_sequencial > $cod_procandamint  and p78_codandam = $p86_codandam  and p78_publico <> 'f' "));
											if ($clprocandamint->numrows != 0) {
												for ($xx = 0; $xx < $clprocandamint->numrows; $xx ++) {
													if ($pdf->gety() > $pdf->h - 30 || $troca != 0) {

														if ($troca == 0) {
															$pdf->addpage('L');
														}
														$pdf->setfont('arial', 'b', 8);
														$pdf->cell(15, $alt, 'Data', 1, 0, "C", 1);
														$pdf->cell(10, $alt, 'Hora', 1, 0, "C", 1);
														$pdf->cell(40, $alt, 'Departamento', 1, 0, "C", 1);
														$pdf->cell(25, $alt, 'Instituição', 1, 0, "C", 1);
														//$pdf->cell(45, $alt, 'Login', 1, 0, "C", 1);
														$pdf->cell(80, $alt, 'Ocorrência', 1, 0, "C", 1);
														$pdf->cell(65, $alt, 'Despacho', 1, 1, "C", 1);

														$troca = 0;
														$pdf->setfont('arial', '', 8);
													}
													db_fieldsmemory($result_procandamint_trans, $xx);
													if ($xx > 0) {
														if ($cod_usu != $p78_usuario) {
															break;
														}
													}
													if ($p78_transint == 't') {
													  $pdf->cell(15, $alt, db_formatar($p78_data, 'd'), 0, 0, "C", 0);
														$pdf->cell(10, $alt, $p78_hora, 0, 0, "C", 0);
														$pdf->cell(40, $alt, substr($p61_coddepto.'-'.$descrdepto,0,25), 0, 0, "L", 0);
														$pdf->cell(25, $alt, substr($nomeinstabrev,0,14), 0, 0, "L", 0);
														//$pdf->cell(45, $alt, substr($nome,0,25), 0, 0, "L", 0);
														$pdf->cell(80, $alt, 'Recebeu Tranferência Interna', 0, 0, "L", 0);
														$pdf->multicell(65, $alt, "", 0, "L", 0);
													} else {
														$pdf->cell(15, $alt, db_formatar($p78_data, 'd'), 0, 0, "C", 0);
														$pdf->cell(10, $alt, $p78_hora, 0, 0, "C", 0);
														$pdf->cell(40, $alt, substr($p61_coddepto.'-'.$descrdepto,0,25), 0, 0, "L", 0);
														$pdf->cell(25, $alt, substr($nomeinstabrev,0,14), 0, 0, "L", 0);
														//$pdf->cell(45, $alt, substr($nome,0,25), 0, 0, "L", 0);
														$pdf->cell(80, $alt, 'Despacho Interno', 0, 0, "L", 0);
														$pdf->multicell(65, $alt, $p78_despacho, 0, "L", 0);
													}
													$cod_usu = $p78_usuario;
													$cod_procandamint = $p78_sequencial;
												}
											}
										}
									}
								}
							}
						}
					}
				}
				$arquiv = false;
				if (isset ($p90_andatual) && $p90_andatual == "t") {
					if ($y == $clproctransferproc->numrows - 1) {
					$pdf->cell(15, $alt, db_formatar($p61_dtandam, 'd'), 0, 0, "C", 0);
					$pdf->cell(10, $alt, $p61_hora, 0, 0, "C", 0);
					$pdf->cell(40, $alt, substr($p61_coddepto.'-'.$descrdepto,0,25), 0, 0, "L", 0);
					$pdf->cell(25, $alt, substr($nomeinstabrev,0,14), 0, 0, "L", 0);
					//$pdf->cell(45, $alt, substr($nome,0,25), 0, 0, "L", 0);
					$pdf->cell(80, $alt, 'Recebeu Processo', 0, 0, "L", 0);
					$pdf->multicell(65, $alt, $p58_despacho, 0, "L", 0);
						
					}
				}
			}
		} else {
			$result_procandam = $clprocandam->sql_record($clprocandam->sql_query_com(null, "*", "p61_codandam", "p61_codproc = $codproc"));
			if ($clprocandam->numrows != 0) {
				for ($xy = 0; $xy < $clprocandam->numrows; $xy ++) {
					db_fieldsmemory($result_procandam, $xy);
					$pdf->cell(15, $alt, db_formatar($p61_dtandam, 'd'), 0, 0, "C", 0);
					$pdf->cell(10, $alt, $p61_hora, 0, 0, "C", 0);
					$pdf->cell(40, $alt, substr($p61_coddepto.'-'.$descrdepto,0,25), 0, 0, "L", 0);
					$pdf->cell(25, $alt, substr($nomeinstabrev,0,14), 0, 0, "L", 0);
					//$pdf->cell(45, $alt, substr($nome,0,25), 0, 0, "L", 0);
					$pdf->cell(80, $alt, 'Recebeu Processo', 0, 0, "L", 0);
					$pdf->multicell(65, $alt, $p61_despacho, 0, "L", 0);					
					$result_procandamint_des = $clprocandamint->sql_record($clprocandamint->sql_query_sim(null, "*", "p78_sequencial", "p78_codandam = $p61_codandam  and p78_publico <> 'f'"));
					if ($clprocandamint->numrows != 0) {
						for ($x = 0; $x < $clprocandamint->numrows; $x ++) {
							db_fieldsmemory($result_procandamint_des, $x);
							if ($p78_transint == 't') {
								break;
							} else {
								$pdf->cell(15, $alt, db_formatar($p78_data, 'd'), 0, 0, "C", 0);
								$pdf->cell(10, $alt, $p78_hora, 0, 0, "C", 0);
								$pdf->cell(40, $alt, substr($p61_coddepto.'-'.$descrdepto,0,25), 0, 0, "L", 0);
								$pdf->cell(25, $alt, substr($nomeinstabrev,0,14), 0, 0, "L", 0);
							  //$pdf->cell(45, $alt, substr($nome,0,25), 0, 0, "L", 0);
							  $pdf->cell(80, $alt, 'Despacho Interno', 0, 0, "L", 0);
								$pdf->multicell(65, $alt, $p78_despacho, 0, "L", 0);
								$cod_procandamint = $p78_sequencial;
							}
						}
					}
					$result_proctransferintand = $clproctransferintand->sql_record($clproctransferintand->sql_query_file(null, "*", "p87_codtransferint", "p87_codandam = $p61_codandam"));
					if ($clproctransferintand->numrows != 0) {
						for ($yy = 0; $yy < $clproctransferintand->numrows; $yy ++) {
							db_fieldsmemory($result_proctransferintand, $yy);
							$result_proctransferint = $clproctransferint->sql_record($clproctransferint->sql_query_andusu(null, "p88_codigo,p88_data,p88_hora,p88_despacho,p88_publico,atual.nome as usuatual,destino.nome as usudestino", null, "p88_codigo=$p87_codtransferint"));
							if ($clproctransferint->numrows != 0) {
								db_fieldsmemory($result_proctransferint, 0);
								$pdf->cell(15, $alt, db_formatar($p78_data, 'd'), 0, 0, "C", 0);
								$pdf->cell(10, $alt, $p78_hora, 0, 0, "C", 0);
								$pdf->cell(40, $alt, substr($p61_coddepto.'-'.$descrdepto,0,25), 0, 0, "L", 0);
								$pdf->cell(25, $alt, substr($nomeinstabrev,0,14), 0, 0, "L", 0);
								//$pdf->cell(45, $alt, substr($nome,0,25), 0, 0, "L", 0);
								$pdf->cell(80, $alt, 'Despacho Interno', 0, 0, "L", 0);
								$pdf->multicell(65, $alt, $p78_despacho, 0, "L", 0);
								$result_procandamintand = $clprocandamintand->sql_record($clprocandamintand->sql_query_file(null, "*", "p86_codtrans", "p86_codtrans=$p88_codigo and p86_codandam = $p87_codandam "));
								if ($clprocandamintand->numrows != 0) {
									db_fieldsmemory($result_procandamintand, 0);
									$result_procandamint_trans = $clprocandamint->sql_record($clprocandamint->sql_query_sim(null, "*", "p78_sequencial", "p78_sequencial > $cod_procandamint  and p78_codandam = $p86_codandam and p78_publico <> 'f' "));
									if ($clprocandamint->numrows != 0) {
										for ($xx = 0; $xx < $clprocandamint->numrows; $xx ++) {
											db_fieldsmemory($result_procandamint_trans, $xx);
											if ($xx > 0) {
												if ($cod_usu != $p78_usuario) {
													break;
												}
											}
											if ($p78_transint == 't') {
												
												$pdf->cell(15, $alt, db_formatar($p78_data, 'd'), 0, 0, "C", 0);
														$pdf->cell(10, $alt, $p78_hora, 0, 0, "C", 0);
														$pdf->cell(40, $alt, substr($p61_coddepto.'-'.$descrdepto,0,25), 0, 0, "L", 0);
														$pdf->cell(25, $alt, substr($nomeinstabrev,0,14), 0, 0, "L", 0);
														//$pdf->cell(45, $alt, substr($nome,0,25), 0, 0, "L", 0);
														$pdf->cell(80, $alt, 'Recebeu Tranferência Interna', 0, 0, "L", 0);
														$pdf->multicell(65, $alt, $p78_despacho, 0, "L", 0);
													} else {
														$pdf->cell(15, $alt, db_formatar($p78_data, 'd'), 0, 0, "C", 0);
														$pdf->cell(10, $alt, $p78_hora, 0, 0, "C", 0);
														$pdf->cell(40, $alt, substr($p61_coddepto.'-'.$descrdepto,0,25), 0, 0, "L", 0);
														$pdf->cell(25, $alt, $nomeinstabrev, 0, 0, "L", 0);
														//$pdf->cell(45, $alt, substr($nome,0,25), 0, 0, "L", 0);
														$pdf->cell(80, $alt, 'Despacho Interno', 0, 0, "L", 0);
														$pdf->multicell(65, $alt, $p78_despacho, 0, "L", 0);
											}
											$cod_usu = $p78_usuario;
											$cod_procandamint = $p78_sequencial;
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	if ($arqant == false) {
		$result_arqproc = $clarqproc->sql_record($clarqproc->sql_query_file(null, null, "*", null, "p68_codproc = $codproc"));
		if ($clarqproc->numrows != 0) {
			db_fieldsmemory($result_arqproc, 0);
			$result_procandam_arq = $clprocandam->sql_record($clprocandam->sql_query_com(null, "*", "p61_codandam desc limit 1", "p61_codproc = $codproc"));
			if ($clprocandam->numrows != 0) {
				db_fieldsmemory($result_procandam_arq, 0);
				$pdf->cell(15, $alt, db_formatar($p61_dtandam, 'd'), 0, 0, "C", 0);
				$pdf->cell(10, $alt, $p61_hora, 0, 0, "C", 0);
				$pdf->cell(40, $alt, substr($p61_coddepto.'-'.$descrdepto,0,25), 0, 0, "L", 0);
				$pdf->cell(25, $alt, substr($nomeinstabrev,0,14), 0, 0, "L", 0);
				//$pdf->cell(45, $alt, substr($nome,0,25), 0, 0, "L", 0);
				$pdf->cell(80, $alt, 'Processo Arquivado', 0, 0, "L", 0);
				$pdf->multicell(65, $alt, $p61_despacho, 0, "L", 0);

				if (isset ($p90_andatual) && $p90_andatual == "t") {
					$pdf->cell(15, $alt, db_formatar($p61_dtandam, 'd'), 0, 0, "C", 0);
					$pdf->cell(10, $alt, $p61_hora, 0, 0, "C", 0);
					$pdf->cell(40, $alt, substr($p61_coddepto.'-'.$descrdepto,0,25), 0, 0, "L", 0);
				  $pdf->cell(25, $alt, substr($nomeinstabrev,0,14), 0, 0, "L", 0);
					//$pdf->cell(45, $alt, $nome, 0, 0, "L", 0);
					$pdf->cell(80, $alt, 'Recebeu Processo', 0, 0, "L", 0);
					$pdf->multicell(65, $alt, $p58_despacho, 0, "L", 0);
				}
			}
		}
	}

}
$pdf->Output();
?>