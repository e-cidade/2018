<?php
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

$dir = "classes/";

// Abre um diretorio conhecido, e faz a leitura de seu conteudo
if (is_dir($dir)) {
	if ($dh = opendir($dir)) {
		$numero= "";
		while ((($file = readdir($dh)) !== false) && ($numero!=30)){
			$tipo = filetype($dir . $file);
			if($tipo=='file'){
				$arr = split("_", $file);
				$count = count($arr);
				//pega somente db_* _classe.php
				if($arr[0]=="db" and $arr[$count-1]=="classe.php" and $count >= 3){
					$numero= $numero + 1;
					// print "arquivo: $file - tipo: " . filetype($dir . $file) . " classe ".$arr[1]."<br>";
					//print "$numero - $file <br><br> ";
					echo "<br><b>classe $numero : $file </b><br><br>";
					$lines = file ('/var/www/dbportal_prj/classes/'.$file);
					$chave = "";
					 
					foreach ($lines as $line_num => $line) {
						$linha =htmlspecialchars($line);
						//echo "$linha <br>";
						$funcao = strstr($linha, 'function');
						if($funcao!=""){
							//echo "$funcao <br>";
							$arrfunc = split(" ", $funcao);
							// se não for incluir, alterar, excluir,sql_query,sql_query_file
							if(($arrfunc[1]!="incluir")and($arrfunc[1]!="alterar")and($arrfunc[1]!="excluir")and($arrfunc[1]!="sql_query")and($arrfunc[1]!="sql_query_file")){
								//echo "$funcao <br>";

								// se não tiver sql_record
								$funcao1 = strstr($funcao, 'sql_record');
								if($funcao1==""){
									// se não tiver atualizacampos
									$funcao2 = strstr($funcao, 'atualizacampos');
									if($funcao2==""){
										// se não tiver erro
										$funcao3 = strstr($funcao, 'erro');
										if($funcao3==""){
											// se não tiver classe cl_
											$funcao4 = strstr($funcao, 'cl_');
											if($funcao4==""){
												// aqui estão as função que preciso...na $funcao
												
												// para a tabela tenho que ver quando for db_db_... para pegar o 1 e 2 do split
												if ($arr[1]=="db"){
													$tabela = $arr[1]."_".$arr[2];
												}else{
													$tabela = $arr[1];
												}

												// para pegaro codigo da tabela
												$sqltab = "select codarq from db_sysarquivo where nomearq = '$tabela'";
												$resulttab = pg_query($sqltab);
												$codigo = pg_result($resulttab,0,"codarq");
												
												
												// para o metodo tenho que tirar o "(" das funções xxx(
												$metodo = split("\(", $arrfunc[1]);
												//print_r($metodo);
												
												// para ver se ja tem cadastrado no banco
												$sql = "select * from db_sysclasses where codarq = $codigo and nomclasse = '$metodo[0]'";
												
												// ver tabela db_sysclasses
												$result = pg_query($sql);	
												$linhasres = pg_num_rows($result);
												if($linhasres>0){
													$exibe = montaMetodoEx($lines,$line_num);
													$exibe = addslashes($exibe);
													echo "tem no banco <br>";
													/*
													$sqldel = "delete from db_sysclasses where codarq = $codigo and nomclasse = '$metodo[0]'";
													$resultdel = @pg_exec($sqldel); 
													*/
												}else{
													echo "<b>não tem no banco </b><br>";
													$exibe = montaMetodoEx($lines,$line_num);
													$exibe = addslashes($exibe);
													$sqlinsert = "insert into db_sysclasses (codarq,nomclasse,descrclasse,codigoclass) 
																					values ($codigo,
																							'$metodo[0]',
																							'atualização de metodos',
																							'$exibe')";
											       // die("xx ".$sqlinsert." xxxxxxxxxxx");
													$resultinsert = @pg_exec($sqlinsert); 
													if($resultinsert==false){
														db_msgbox("Inclusão não efetuada.codigo = $codigo");
													}else{
												  		db_msgbox("Inclusão efetuada com sucesso codigo = $codigo ");
												    }
												  
													
												}
												echo "<br> codigo = $codigo <br>
														   tabela = ".$tabela."<br>
											               metodo= ".$metodo[0]."<br>";
												//echo "$line_num - $funcao <br>";
												
												
												echo "<br> *************************inicio******************************************** <br>" ;
												//echo "$exibe <br>";
												echo "<br> *************************fim******************************************** <br>" ;
													
											}
										}
									}
								}
							}// do if incluir, alterar, excluir,sql_query,sql_query_file
								


						}
							
					}

				}
			}
		}
		closedir($dh);
	}
}

function montaMetodoEx($array,$posIni){
	$metodoEx = "";
	$chave= "";
	$total = sizeof($array);
	//echo "<br> ************************************************************************ <br>" ;
	for($i = $posIni;$i<$total;$i++){
		$funcao = $array[$i];
		//echo "$funcao <br>";
		$metodoEx .= $funcao;
		//pegara função
     	$chaveabre= strstr($funcao, '{');
		//echo "chaveabre= $chaveabre<br>";
		if($chaveabre!=""){
			if($chave==""){
				$linhainicial = $i;
				echo "linhas inicial $linhainicial<br>";
			}
			$chave = $chave + 1;
			//echo "chave abre= $chave <br>";
		}
		$chavefecha= strstr($funcao, '}');
		//echo "chavefecha= $chavefecha <br>";
		if($chavefecha!=""){
			$chave = $chave - 1;
			//echo "chave fecha= $chave <br>";
		}
		if($chave==0){
			$chave = "";
			$linhafinal= $i;
			echo "linha final $linhafinal<br>";
			break;
		}
	}
	
	return $metodoEx;
	
}

?>