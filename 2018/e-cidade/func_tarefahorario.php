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
include("classes/db_tarefa_classe.php");
include("classes/db_tarefaparam_classe.php");
db_postmemory($HTTP_POST_VARS,2);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
if($pesquisa_chave!=null && $pesquisa_chave!=""){
	$erro = testa_horarios($pesquisa_chave,$at40_sequencial);
	if($erro == false) {
        echo "<script>".$funcao_js."($at40_sequencial,$pesquisa_chave,false);</script>";
    } else{
	    echo "<script>".$funcao_js."($at40_sequencial,$pesquisa_chave,true);</script>";
    }
}

function testa_horarios($at40_responsavel,$at40_sequencial) {
	global $at40_diaini, $at40_diafim, $at40_horainidia, $at40_horafim, $at40_previsao;         // data e horario da tarefa corrente
	global $at53_horaini_manha, $at53_horafim_manha, $at53_horaini_tarde, $at53_horafim_tarde;  // parametros para controle de expediente
	
	$id_tarefa  = $at40_sequencial;
	$id_usuario = $at40_responsavel;
	$db_diaini  = "";
	$db_diafim  = "";
	$db_horaini = "";
	$db_horafim = "";

	$cltarefaparam = new cl_tarefaparam;
	$result        = $cltarefaparam->sql_record($cltarefaparam->sql_query(null,"*",null,null));
	if($cltarefaparam->numrows > 0) {
		db_fieldsmemory($result,0);
	}

	$cltarefa   = new cl_tarefa;
	$retorno    = true;
	// Pega data inicial e final + horarios da tarefa corrente
	$result     = $cltarefa->sql_record($cltarefa->sql_query($at40_sequencial,"at40_diaini,at40_diafim,at40_horainidia,at40_horafim,at40_previsao",null,""));
	if($cltarefa->numrows > 0) {
		db_fieldsmemory($result, 0);
		$diaini     = $at40_diaini;
		$diafim     = $at40_diafim;
		$horainidia = $at40_horainidia;
		$horafim    = $at40_horafim;
		$previsao   = $at40_previsao;
	} 
	// Todos os registros do usuario    
    $result = $cltarefa->sql_record($cltarefa->sql_query_envol(null,"at40_sequencial,at40_diaini,at40_diafim,at40_horainidia,at40_horafim,at40_previsao","at40_sequencial desc,at45_usuario","at40_sequencial<>$id_tarefa and 
                                                                                                                                                                                              at45_usuario=$id_usuario    and
   		                                    																																				 (at40_diaini <= '$diafim' or at40_diafim >= '$diaini') and 
																																		                                                     (at40_horainidia <= '$horafim' and at40_horafim >= '$horainidia')"));
	if($cltarefa->numrows > 0) {
		$NumRows    = $cltarefa->numrows;
		$NumFields  = pg_numfields($result);
		$vet_update = array();
		$vet_keys   = array();
					
		for($i = 0; $i < $NumRows; $i++) {
			for($j = 0; $j < $NumFields; $j++) {
				$resto = 0;
				if(pg_fieldname($result, $j) == "at40_diaini") {
					$db_diaini = pg_result($result, $i, $j);
				}
				if(pg_fieldname($result, $j) == "at40_diafim") {
					$db_diafim = pg_result($result, $i, $j);
				}
				if(pg_fieldname($result, $j) == "at40_horainidia") {
					$db_horaini = pg_result($result, $i, $j);
					if(strlen(trim($db_horaini)) == 2) {
						$db_horaini  = substr(pg_result($result, $i, $j),0,2);
						$db_horaini .= ":00";
					}
					$uphoraini  = substr(pg_result($result, $i, $j),0,2) + $previsao;
					$min        = substr(pg_result($result, $i, $j),3,2);
					$uphoraini .= ":" . $min;

					if($uphoraini > $at53_horafim_manha) {		// tarde
						if($uphoraini > $at53_horafim_tarde) {	// maior que hora final da tarde 18
							$resto = substr($uphoraini,0,2) - substr($at53_horafim_tarde,0,2);
							if($resto == 0) {
								$resto    += substr($uphoraini,3,2) - substr($at53_horafim_tarde,3,2);
								$uphoraini = substr($at53_horaini_manha,0,2);
								$resto    += substr($at53_horaini_manha,3,2);
							}
							else {
								$uphoraini = db_formatar((substr($at53_horaini_manha,0,2) + ($resto * 2)),'s','0',2,'e',0);
								$resto     = 0;
							}
							if($resto >= 60) {
								$resto    -= 60;
								$uphoraini = db_formatar((substr($at53_horaini_manha,0,2) + 1),'s','0',2,'e',0);
							}
							$uphoraini .= ":" . db_formatar($resto,'s','0',2,'e',0);
							$db_diafim  = substr($db_diafim,0,4) . "-" . substr($db_diafim,5,2) . "-" . db_formatar((substr($db_diafim,8,2) + 1),'s','0',2,'e',0); 
						}
						else {		// maior que hora final da manha 12
								    // 13:00 < $uphoraini and $uphoraini < 18:00 
								    // testa se $uphoraini esta dentro do horario da tarde
							if($at53_horaini_tarde <= $uphoraini && $uphoraini <= $at53_horafim_tarde) {
								// nda	  
							}
							else {
								$resto     = substr($uphoraini,0,2) - substr($at53_horafim_manha,0,2);
								$resto    += substr($uphoraini,3,2);
								$uphoraini = substr($at53_horaini_tarde,0,2) . ":" . db_formatar($resto,'s','0',2,'e',0);
							}
						}
					}
					
					$vet_update[pg_result($result, $i, 0)] = "update tarefa set at40_horainidia = '$uphoraini', ";   
				}
				if(pg_fieldname($result, $j) == "at40_horafim") {
					$db_horafim = pg_result($result, $i, $j);
					if(strlen(trim($db_horafim)) == 2) {
						$db_horafim  = substr(pg_result($result, $i, $j),0,2);
						$db_horafim .= ":00";
					}
					$uphorafim  = substr(pg_result($result, $i, $j),0,2) + $previsao;
					$min        = substr(pg_result($result, $i, $j),3,2);
					$uphorafim .= ":" . $min;

					if($uphorafim > $at53_horafim_manha) {		// tarde
						if($uphorafim > $at53_horafim_tarde) {	// maior que hora final da tarde 18
							$resto = substr($uphorafim,0,2) - substr($at53_horafim_tarde,0,2);
							if($resto == 0) {
								$resto    += substr($uphorafim,3,2) - substr($at53_horafim_tarde,3,2);
								$uphorafim = substr($at53_horaini_manha,0,2);
								$resto    += substr($at53_horaini_manha,3,2);
							}
							else {
								$uphorafim = db_formatar((substr($at53_horaini_manha,0,2) + ($resto * 2)),'s','0',2,'e',0);
								$resto     = 0;
							}
							if($resto >= 60) {
								$resto    -= 60;
								$uphorafim = db_formatar((substr($at53_horaini_manha,0,2) + 1),'s','0',2,'e',0);
							}
							$uphorafim .= ":" . db_formatar($resto,'s','0',2,'e',0);
							$db_diafim  = substr($db_diafim,0,4) . "-" . substr($db_diafim,5,2) . "-" . db_formatar((substr($db_diafim,8,2) + 1),'s','0',2,'e',0); 
						}
						else {		// maior que hora final da manha 12
								    // 13:00 < $uphorafim and $uphorafim < 18:00 
								    // testa se $uphorafim esta dentro do horario da tarde
							if($at53_horaini_tarde <= $uphorafim && $uphorafim <= $at53_horafim_tarde) {
								// nda	  
							}
							else {
								$resto     = substr($uphorafim,0,2) - substr($at53_horafim_manha,0,2);
								$resto    += substr($uphorafim,3,2);
								$uphorafim = substr($at53_horaini_tarde,0,2) . ":" . db_formatar($resto,'s','0',2,'e',0);
							}
						}
					}

					$vet_update[pg_result($result, $i, 0)] .= "at40_horafim = '$uphorafim', at40_diaini = '$db_diaini', at40_diafim = '$db_diafim' where at40_sequencial = " . pg_result($result, $i, 0);  
				}

				if(strlen($db_diaini)  > 0 &&
				   strlen($db_diafim)  > 0 &&
			   	   strlen($db_horaini) > 0 &&
			       strlen($db_horafim) > 0) {
				   	if($db_diaini <= $diafim ||	// Ex.: 01/02/2006 <= 03/02/2006 or 
				   	   $db_diafim >= $diaini) { //      02/02/2006 >= 02/02/2006
				   	   	if($horainidia <= $db_horafim &&
				   	   	   $horafim    >= $db_horaini) {
				   	   	   	$vet_keys[pg_result($result, $i, 0)] = pg_result($result, $i, 0);
				   	   	}
				   	}
		        }
			}
		} 

		foreach($vet_keys as $tarefa) {
//			echo $vet_update[$tarefa] . "<br>";
			pg_exec($vet_update[$tarefa]);
		}
		
	  	$retorno = false;
	}
	
	return($retorno);
}
?>