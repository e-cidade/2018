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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_utils.php");
include ("libs/db_usuariosonline.php");
include ("classes/db_protprocesso_classe.php");
include ("classes/db_procprocessodoc_classe.php");
include ("classes/db_processosapensados_classe.php");
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
include ("classes/db_protparam_classe.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$clprotprocesso          = new cl_protprocesso;
$clprotprocessodoc       = new cl_procprocessodoc;
$clprotprocessoapensados = new cl_processosapensados; 
$clprocandam             = new cl_procandam;
$clproctransfer          = new cl_proctransfer;
$clproctransferproc      = new cl_proctransferproc;
$clproctransand          = new cl_proctransand;
$clproctransferintand    = new cl_proctransferintand;
$clproctransferint       = new cl_proctransferint;
$clprocandamint          = new cl_procandamint;
$clprocandamintand       = new cl_procandamintand;
$clarqproc               = new cl_arqproc;
$clarqandam              = new cl_arqandam;
$clprotparam             = new cl_protparam;

$cod_procandamint = 0;
$arquiv = false;
$arqant = false;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_imprime(cod){
  jan = window.open('pro2_relconspro002.php?codproc='+cod,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_imprimedespacho(codproc,codprocandamint){
  jan = window.open('pro2_despachointer002.php?codproc='+codproc+'&codprocandamint='+codprocandamint,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name=form1 action="">
   <center>
    <table    width="100%" >
      <tr>
	<td>
	<?

$result_param=$clprotparam->sql_record($clprotparam->sql_query(null,"*",null,"p90_instit=".db_getsession("DB_instit")));
if ($clprotparam->numrows>0){
	db_fieldsmemory($result_param,0);
}

if (isset ($codproc) && $codproc != "") {
	$result_protprocesso = $clprotprocesso->sql_record($clprotprocesso->sql_query($codproc));
	if ($clprotprocesso->numrows != 0) {
		db_fieldsmemory($result_protprocesso, 0);
		echo "<table  border=0>
			        <tr>
			          <td  nowrap><b>NÚMERO DE CONTROLE DO PROCESSO: </b></td>
			      	  <td nowrap>$codproc </td>
			    	</tr>
			    	<tr>
                <td nowrap><b>NÚMERO DO PROCESSO: </b></td>
                <td nowrap>{$p58_numero}/{$p58_ano} </td>
                <td ><b>NOME:</b></td>
                <td nowrap>$z01_nome</td>
            </tr>
			    	<tr>  
			      	  <td ><b>DATA:</b> </td>
			      	  <td nowrap>".db_formatar($p58_dtproc, 'd')."</td>
			      	  <td ><b>HORA:</b> </td>
			      	  <td nowrap>$p58_hora&nbsp;</td>
			    	</tr>
			    	<tr>  
			       	  <td ><b>TIPO:</b> </td>
			      	  <td nowrap>$p51_descr</td>
			      	  <td ><b>ATENDENTE:</b> </td>
			      	  <td nowrap>$nome</td>	
			    	</tr>
	          <tr>  
			       	  <td ><b>DEPARTAMENTO:</b> </td>
			      	  <td colspan='3' nowrap>$p58_coddepto-$descrdepto</td>
			    	</tr>
	          <tr>  
			       	  <td ><b>INSTITUIÇÃO:</b> </td>
			      	  <td colspan='3' nowrap>$p58_instit-$nomeinst</td>
			    	</tr>
			    	<tr>  
			          <td ><b>REQUERENTE:</b> </td>
			      	  <td  nowrap>$p58_requer</td>
			          <td colspan=2>
							  </td>
            </tr>
            <tr>
             <td>&nbsp</td>
           </tr>";
            
      $rsConsultaDoc = $clprotprocessodoc->sql_record($clprotprocessodoc->sql_query($codproc));    
      $iLinhasDoc    = $clprotprocessodoc->numrows; 
      if ( $iLinhasDoc > 0 ) {     
         
				  for ($i=0 ;$i < $iLinhasDoc; $i++) {
				    $oDocumento = db_utils::fieldsMemory($rsConsultaDoc,$i);  
				  	
             if ($oDocumento->p81_doc == 't'){
               $vSelecionado = " Sim";
             } else if ($oDocumento->p81_doc == 'f'){
               $vSelecionado = " Não";
             }
		                 
             echo " <tr>
		                 <td>".($i==0?"<b>DOCUMENTOS:</b>":"&nbsp")."</td>
		                 <td colspan=1>{$oDocumento->p56_descr}<br>
		                 <td nowrap <b>RECEBIDO:</b>&nbsp$vSelecionado<br>
		                </tr>";
				   }

       }

    echo " <tr>
             <td>&nbsp</td>
           </tr>";

      $codproc = (isset($codproc)&&!empty($codproc))?$codproc:'null';
      $sqlConsultaProcApen = " select * 
                                 from processosapensados 
                                where p30_procprincipal = {$codproc} ";
      $rsConsultaProcApen  = db_query($sqlConsultaProcApen);
      $iLinhasProcApen     = pg_num_rows($rsConsultaProcApen);
      
      if ( $iLinhasProcApen > 0 ) {        
          for ($i = 0 ;$i < 1; $i++) {
             echo " <tr>
                     <td valign='top'>".($i == 0 ? "<b>APENSADOS:</b>":"&nbsp")."</td>";
                      $y = 1;
                      $x = 0;
                        while ($x < $iLinhasProcApen){
                        	  $oProcApensado = db_utils::fieldsMemory($rsConsultaProcApen,$x);
                        	  
                        	     if ($x == 0) {
                        		     echo "<td colspan=2>";
                        	     }
                        	     
                        	     if ($y == 22) {
                                 $sBr = "<br>";
                                 $sVirgula = "";
                                 $y   = 0;
                               } else {
                               	 $sBr = "";
                               	 $sVirgula = ",";
                               }
                               
                        	   echo "&nbsp;".$oProcApensado->p30_procapensado.$sVirgula.$sBr;
                        	   $x++;
                        	   $y++;
                        }
                        
             echo "  </td>
                    </tr>";

           }

       }

    echo " <tr>
             <td>&nbsp</td>
           </tr>          
           
           
           
           <tr> 
               <td colspan=6 align='center'>
                   <input name='imprimir' type='button' value='Imprimir Consulta' onclick='js_imprime($codproc);' >
               </td>
           </tr>
		       <tr> 
			      	  <td ><b>OBSERVAÇÃO:</b> </td>
			      	  <td colspan='3'>". ($p58_obs == "" ? "&nbsp;" : nl2br($p58_obs))."</td>
			    	</tr>  
			  	  </table>";
		echo "<table bgcolor='#cccccc' width='100%' cellspacing=0 cellpading=0 border=1>
			 	    <tr>
			    	  <td colspan=7 align='center'><b>Andamentos</b></td>
			    	</tr>
			    	<tr>
			      	  <td align='center'><b>Data</b></td>
			      	  <td align='center'><b>Hora</b></td>
			      	  <td align='center'><b>Depto</b></td>
			      	  <td align='center'><b>Insti</b></td>
			      	  <td align='center'><b>Login</b></td>
			      	  <td align='center'><b>Ocorrencia</b></td>
			      	  <td align='center'><b>Despacho</b></td>
			     	</tr>";
		
		
		$result_proctransferproc = $clproctransferproc->sql_record($clproctransferproc->sql_query_file(null, null, "*", "p63_codtran", "p63_codproc = $codproc"));
		if ($clproctransferproc->numrows != 0) {
			echo "	<tr>
					      <td>";
			echo db_formatar($p58_dtproc, 'd');
			echo "	  </td>
					      <td>$p58_hora</td>
					      <td>$p58_coddepto - $descrdepto</td>
					      <td>$p58_instit - $nomeinstabrev</td>
					      <td>$nome</td>
					      <td>Processo Criado</td>
					      <td>&nbsp</td>
				        </tr>";
			$tramite = 0;
			$exe = $clproctransferproc->numrows - 1;
			for ($y = 0; $y < $clproctransferproc->numrows; $y ++) {
				
			  db_fieldsmemory($result_proctransferproc, $y);
			  
				$sCamposProcessoTransf  = " atual.instit, ";
				$sCamposProcessoTransf .= " instiatual.nomeinstabrev, ";
				$sCamposProcessoTransf .= " p62_codtran, ";
				$sCamposProcessoTransf .= " p62_dttran, ";
				$sCamposProcessoTransf .= " p62_hora, ";
				$sCamposProcessoTransf .= " p62_coddepto, ";
				$sCamposProcessoTransf .= " p62_coddeptorec, ";
				$sCamposProcessoTransf .= " atual.descrdepto as deptoatual, ";
				$sCamposProcessoTransf .= " destino.descrdepto as deptodestino, ";
				$sCamposProcessoTransf .= " destino.coddepto as coddeptodestino, "; 
				$sCamposProcessoTransf .= " usu_atual.nome as nome, ";
				$sCamposProcessoTransf .= " proctransfer.p62_id_usorec as idusuariodestino, "; 
				$sCamposProcessoTransf .= " usu_destino.login as loginusuariodestino ";
				$sWhereProcessoTranf    = "p62_codtran = $p63_codtran";
				
				$sSqlProcessoTransf     = $clproctransfer->sql_query_deps(null, $sCamposProcessoTransf, null, $sWhereProcessoTranf);
				$result_proctransfer    = $clproctransfer->sql_record($sSqlProcessoTransf);
				
				if ($clproctransfer->numrows != 0) {

				  db_fieldsmemory($result_proctransfer, 0);
					
				  if ($tramite == 0) {
					  
						echo "<tr>
								   <td>";
						echo db_formatar($p62_dttran, 'd');
						echo " </td>
                   <td>$p62_hora&nbsp;</td>
								   <td>$p62_coddepto - $deptoatual</td>
					         <td>$instit - $nomeinstabrev</td>
								   <td>$nome  </td>
								   <td>Tramite Inicial $p62_codtran p/ Departamento: $coddeptodestino - $deptodestino ". ((int) $idusuariodestino > 0?" - usuário especificado: $idusuariodestino - $loginusuariodestino":" (sem usuário especificado)") . "</td>
								   <td>&nbsp</td>
								 </tr>";
						$tramite = 1;
					} else {
					  
					  $sWhereProcessoTransand = " p64_codtran = $p62_codtran and p61_codproc = $codproc ";
					  $sSqlProcessoTransand   = $clproctransand->sql_query_consandam("", "p64_codandam", null, $sWhereProcessoTransand);
						$result_proctransand    = $clproctransand->sql_record($sSqlProcessoTransand);
            
						if ($clproctransand->numrows != 0) {
						  
							db_fieldsmemory($result_proctransand, 0);
							$result_procandam = $clprocandam->sql_record($clprocandam->sql_query_com(null, "procandam.*", null, "p61_codandam = $p64_codandam"));
							if ($clprocandam->numrows != 0) {
								db_fieldsmemory($result_procandam, 0);
							}
						}
						
						$result_arqandam = $clarqandam->sql_record($clarqandam->sql_query_file(null, "*", null, "p69_codandam = ".@$p61_codandam));
						if ($p62_coddepto == $p62_coddeptorec && $clarqandam->numrows != 0) {
						  
							$arquiv = true;
						} else {
							echo "<tr>
												  <td>  ";
							echo db_formatar($p62_dttran, 'd');
							echo "  </td>
										      <td>$p62_hora&nbsp</td>
										 		  <td>$p62_coddepto-$deptoatual</td>
					                <td>$instit-$nomeinstabrev</td>
												  <td>$nome </td>
												  <td>Transferência $p62_codtran p/ o Departamento: $coddeptodestino - $deptodestino" . ((int) $idusuariodestino > 0?" - usuário especificado: $idusuariodestino - $loginusuariodestino":" (sem usuário especificado)") . "</td>
												  <td>&nbsp</td>
									      		</tr>";
						}
					}
					$result_proctransand = $clproctransand->sql_record($clproctransand->sql_query_consandam("", "*", null, "p64_codtran = $p62_codtran and p61_codproc = $codproc  "));
          
					if ($clproctransand->numrows != 0) {

						db_fieldsmemory($result_proctransand, 0);
						$result_procandam = $clprocandam->sql_record($clprocandam->sql_query_com(null, "*", null, "p61_codandam = $p64_codandam"));
						if ($clprocandam->numrows != 0) {
							db_fieldsmemory($result_procandam, 0);
							echo "<tr>
												  <td>";
							echo db_formatar($p61_dtandam, 'd');
							echo "  </td>
												  <td>$p61_hora&nbsp</td>
												  <td>$p61_coddepto-$descrdepto</td>
					                <td>$instit-$nomeinstabrev</td>
												  <td>$nome </td>";
							if ($arquiv == true) {
								$result_arqandam = $clarqandam->sql_record($clarqandam->sql_query_file(null, "*", null, "p69_codandam = $p61_codandam"));
								if ($clarqandam->numrows != 0) {
									db_fieldsmemory($result_arqandam, 0);
									$arqant = true;
									if ($p69_arquivado == 't') {
										echo "<td><b>Processo Arquivado </b></td>";
									} else {
										echo "<td><b>Desarquivamento</b></td>";
									}
								} else {
									//echo "<td><b>Desarquivamento</b></td>";
								}
							} else {
								echo "<td>Recebeu Transferência - $p62_codtran</td>";
							}
							echo "  <td>$p61_despacho&nbsp</td>
										      	    </tr>";

							$result_procandamint_des = $clprocandamint->sql_record($clprocandamint->sql_query_sim(null, "*", "p78_sequencial", "p78_codandam = $p61_codandam  "));
							if ($clprocandamint->numrows != 0) {
								for ($x = 0; $x < $clprocandamint->numrows; $x ++) {
									db_fieldsmemory($result_procandamint_des, $x);
									if ($p78_transint == 't') {
										break;
									} else {
										echo "<tr>
																      <td>";
										echo db_formatar($p78_data, 'd');
										echo "  </td>
														      		  <td>$p78_hora&nbsp</td>
														      		  <td>$p61_coddepto-$descrdepto</td>
					                              <td>$instit-$nomeinstabrev</td>
														      		  <td>$nome</td>
														      		  <td>Despacho Interno</td>";
										$usu_atual = db_getsession("DB_id_usuario");
										if ($p78_usuario == $usu_atual) {
											echo "<td>$p78_despacho<input name='imprimirdes' type='button' value='Imprimir' onclick='js_imprimedespacho($p58_codproc,$p78_sequencial);' > </td>
															            </tr>";
										} else {
											echo "<td>$p78_despacho </td>
															            </tr>";
										}
										$cod_procandamint = $p78_sequencial;
									}
								}
							}
							$result_proctransferintand = $clproctransferintand->sql_record($clproctransferintand->sql_query_file(null, "*", "p87_codtransferint", "p87_codandam = $p61_codandam"));
							if ($clproctransferintand->numrows != 0) {
								for ($yy = 0; $yy < $clproctransferintand->numrows; $yy ++) {
									db_fieldsmemory($result_proctransferintand, $yy);
									
									$sCamposTransfInt = "p88_codigo, 
									                     p88_data, 
									                     p88_hora, 
									                     p88_despacho, 
									                     p88_publico, 
									                     atual.nome as usuatual, 
									                     destino.nome as usudestino, 
									                     destino.id_usuario as idusudestino";
									
									$sSqlProcTransfInt      = $clproctransferint->sql_query_andusu(null, $sCamposTransfInt, null, "p88_codigo=$p87_codtransferint");
									$result_proctransferint = $clproctransferint->sql_record($sSqlProcTransfInt);
									if ($clproctransferint->numrows != 0) {
										db_fieldsmemory($result_proctransferint, 0);
										echo "<tr>
                            <td>";
										echo db_formatar($p88_data, 'd');
										echo "  </td>
															          <td>$p88_hora&nbsp</td>
														      	      <td>$p61_coddepto-$descrdepto</td>
					                                <td>$instit-$nomeinstabrev</td>
														              <td>$usuatual</td>
														              <td>Transferência Interna - $p87_codtransferint para: $idusudestino - $usudestino</td>
														              <td>$p88_despacho</td>
														    		</tr>";

										$result_procandamintand = $clprocandamintand->sql_record($clprocandamintand->sql_query_file(null, "*", "p86_codtrans", "p86_codtrans=$p88_codigo and p86_codandam = $p87_codandam "));
										if ($clprocandamintand->numrows != 0) {
											db_fieldsmemory($result_procandamintand, 0);
											
											$result_procandamint_trans = $clprocandamint->sql_record($clprocandamint->sql_query_sim(null, "*", "p78_sequencial", "p78_sequencial > $cod_procandamint  and p78_codandam = $p86_codandam  "));
											
											
											if ($clprocandamint->numrows != 0) {
												for ($xx = 0; $xx < $clprocandamint->numrows; $xx ++) {
													db_fieldsmemory($result_procandamint_trans, $xx);
													if ($xx > 0) {
														if ($cod_usu != $p78_usuario) {
															break;
														}
													}
													if ($p78_transint == 't') {
														echo "<tr>
																			      	          <td>";
														echo db_formatar($p78_data, 'd');
														echo "  </td>
															  		  <td>$p78_hora&nbsp</td>
														  			  <td>$p61_coddepto-$descrdepto</td>
					                            <td>$instit-$nomeinstabrev</td>
														   			  <td>$nome</td>
														   			  <td>Recebeu Transferência Interna</td>
														   			  <td>$p78_despacho</td>
																			    			</tr>";
													} else {
														echo "<tr>
																			        		  <td>";
														echo db_formatar($p78_data, 'd');
														echo "  </td>
																			      			  <td>$p78_hora&nbsp</td>
																			      			  <td>$p61_coddepto-$descrdepto</td>
					                                          <td>$instit-$nomeinstabrev</td>
																			      		 	  <td>$nome</td>
																			      			  <td>Despacho Interno</td>";
														$usu_atual = db_getsession("DB_id_usuario");
														if ($p78_usuario == $usu_atual) {
															echo "<td>$p78_despacho<input name='imprimirdes' type='button' value='Imprimir' onclick='js_imprimedespacho($p58_codproc,$p78_sequencial );' > </td>
																				            	</tr>";
														} else {
															echo "<td>$p78_despacho> </td>
																				    			</tr>";
														}
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

						echo "<tr>
								     <td>";
						echo db_formatar($p61_dtandam, 'd');
						echo "  </td>
								 		<td>$p61_hora&nbsp</td>
									  	<td>$p61_coddepto-$descrdepto</td>
					            <td>$instit-$nomeinstabrev</td>
									  	<td>$nome</td>
									  	<td><b>Andamento atual</b></td>
									  	<td>$p58_despacho&nbsp</td>
									 </tr>";
					}
				}
			}
		} else {
			$result_procandam = $clprocandam->sql_record($clprocandam->sql_query_com(null, "*", "p61_codandam", "p61_codproc = $codproc"));
			if ($clprocandam->numrows != 0) {
				for ($xy = 0; $xy < $clprocandam->numrows; $xy ++) {
					db_fieldsmemory($result_procandam, $xy);
					echo "<tr>
								        <td>";
					echo db_formatar($p61_dtandam, 'd');
					echo "  </td>
								        <td>$p61_hora&nbspaqui</td>
								        <td>$p61_coddepto-$descrdepto</td>
					              <td>$instit-$nomeinstabrev</td>
								        <td>$nome</td>
								        <td>Recebeu Processo &nbsp</td>
								        <td>$p61_despacho</td>
								      </tr>";
					$result_procandamint_des = $clprocandamint->sql_record($clprocandamint->sql_query_sim(null, "*", "p78_sequencial", "p78_codandam = $p61_codandam  "));
					if ($clprocandamint->numrows != 0) {
						for ($x = 0; $x < $clprocandamint->numrows; $x ++) {
							db_fieldsmemory($result_procandamint_des, $x);
							if ($p78_transint == 't') {
								break;
							} else {
								echo "<tr>
														  <td>";
								echo db_formatar($p78_data, 'd');
								echo "  </td>
												  		  <td>$p78_hora&nbsp</td>
												  		  <td>$p61_coddepto-$descrdepto</td>
					                      <td>$instit-$nomeinstabrev</td>
												  		  <td>$nome</td>
												  		  <td>Despacho Interno</td>";
								$usu_atual = db_getsession("DB_id_usuario");
								if ($p78_usuario == $usu_atual) {
									echo "<td>$p78_despacho<input name='imprimirdes' type='button' value='Imprimir' onclick='js_imprimedespacho($p58_codproc,$p78_sequencial );' > </td>
													  		</tr>";
								} else {
									echo "<td>$p78_despacho> </td>
															</tr>";
								}
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
								echo "<tr>
												  	     <td>";
								echo db_formatar($p88_data, 'd');
								echo "  </td>
														  <td>$p88_hora&nbsp</td>
								 						  <td>$p61_coddepto-$descrdepto</td>
					                    <td>$instit-$nomeinstabrev</td>
														  <td>$usuatual</td>
														  <td>Transferência Interna para $usudestino</td>
														  <td>$p88_despacho</td>
														</tr>";
								$result_procandamintand = $clprocandamintand->sql_record($clprocandamintand->sql_query_file(null, "*", "p86_codtrans", "p86_codtrans=$p88_codigo and p86_codandam = $p87_codandam "));
								if ($clprocandamintand->numrows != 0) {
									db_fieldsmemory($result_procandamintand, 0);
									$result_procandamint_trans = $clprocandamint->sql_record($clprocandamint->sql_query_sim(null, "*", "p78_sequencial", "p78_sequencial > $cod_procandamint  and p78_codandam = $p86_codandam  "));
									if ($clprocandamint->numrows != 0) {
										for ($xx = 0; $xx < $clprocandamint->numrows; $xx ++) {
											db_fieldsmemory($result_procandamint_trans, $xx);
											if ($xx > 0) {
												if ($cod_usu != $p78_usuario) {
													break;
												}
											}
											if ($p78_transint == 't') {
												echo "<tr>
																	  			<td>";
												echo db_formatar($p78_data, 'd');
												echo "  </td>
																	  			<td>$p78_hora&nbsp</td>
																	  			<td>$p61_coddepto-$descrdepto</td>
					                                <td>$instit-$nomeinstabrev</td>
																	  			<td>$nome</td>
																	  			<td>Recebeu Transferência Interna</td>
																	  			<td>$p78_despacho</td>
																			  </tr>";
											} else {
												echo "<tr>
																	  			<td>";
												echo db_formatar($p78_data, 'd');
												echo "  </td>
																	  			<td>$p78_hora&nbsp</td>
																	  			<td>$p61_coddepto-$descrdepto</td>
					                                <td>$instit-$nomeinstabrev</td>
																	  			<td>$nome</td>
																	  			<td>Despacho Interno</td>";
												$usu_atual = db_getsession("DB_id_usuario");
												if ($p78_usuario == $usu_atual) {
													echo "<td>$p78_despacho<input name='imprimirdes' type='button' value='Imprimir' onclick='js_imprimedespacho($p58_codproc,$p78_sequencial );' > </td>
																		  	      </tr>";
												} else {
													echo "<td>$p78_despacho </td>
																				  </tr>";
												}
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
				echo "<tr>
						        <td>";
				echo db_formatar($p61_dtandam, 'd');
				echo "  </td>
						 		<td>$p61_hora&nbsp</td>
							  	<td>$p61_coddepto-$descrdepto</td>
					        <td>$instit-$nomeinstabrev</td>
							  	<td>$nome</td>
							  	<td><b>Processo Arquivado</b></td>
							  	<td>$p61_despacho / Cod. Arquivamento: $p68_codarquiv &nbsp</td>
							  </tr>";
				if (isset ($p90_andatual) && $p90_andatual == "t") {
					echo "<tr>
							     <td>";
					echo db_formatar($p61_dtandam, 'd');
					echo "  </td>
							 		<td>$p61_hora&nbsp</td>
								   	<td>$p61_coddepto-$descrdepto</td>
					          <td>$instit-$nomeinstabrev</td>
								  	<td>$nome</td>
								  	<td><b>Andamento atual</b></td>
								  	<td>$p58_despacho&nbsp</td>
								 </tr>";
				}
			}
		}
	}
	echo "<table>";
}
?>       
</td>
</tr>
</table>
</center>
</form>  
</body>
</html>