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
db_postmemory($HTTP_POST_VARS);

if(isset($verifica)){
	$seq = 0;
	// ############################ função ##############################
	function montaMetodoEx($array,$posIni){
		$metodoEx = "";
		$chave= "";
		$total = sizeof($array);

		for($i = $posIni;$i<$total;$i++){
			$funcao = $array[$i];
			//echo "$funcao <br>";
			$metodoEx .= $funcao;
			//pegara função
			$chaveabre= strstr($funcao, '{');
			//echo "chaveabre= $chaveabre<br>";
			if($chaveabre!=""){
				if($chave==""){
					//$linhainicial = $i;
					//echo "linhas inicial $linhainicial<br>";
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
				//echo "linha final $linhafinal<br>";
				break;
			}
		}

		return $metodoEx;

	}
	// fim da função

	// drop a tabela
	$sqldrop = "DROP TABLE temp_classeatualiza";
	$resultdrop = @pg_query($sqldrop);
	// cria a tabela
	$sqlcria = "
				CREATE TABLE temp_classeatualiza(
				seq integer,
				codarq integer,
				nomearq varchar(100),
				metodo varchar(100),
				fonteorig text,
				fontenovo text,
				operacao varchar(20)
				);
				";
	$resultcria=pg_query($sqlcria);

	$dir = "classes/";

	// Abre um diretorio conhecido, e faz a leitura de seu conteudo
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			$numero= "";
			while ((($file = readdir($dh)) !== false) ){
				$tipo = filetype($dir . $file);
				if($tipo=='file'){
					$arr = split("_", $file);
					$count = count($arr);
					//pega somente db_* _classe.php
					if($arr[0]=="db" and $arr[$count-1]=="classe.php" and $count >= 3 ){
						$numero= $numero + 1;
						// print "arquivo: $file - tipo: " . filetype($dir . $file) . " classe ".$arr[1]."<br>";
						//print "$numero - $file <br><br> ";
						//echo "<br><b>classe $numero : $file </b><br><br>";
						$lines = file (dirname($_SERVER["SCRIPT_FILENAME"])."/classes/".$file);
						$chave = "";

						foreach ($lines as $line_num => $line) {
							$linha =htmlspecialchars($line);
							//echo "$linha <br>";
							$funcao = strstr($linha, 'function');
							if($funcao!=""){
								//echo "$funcao <br>";
								$arrfunc = split(" ", $funcao);
								if(isset($arrfunc[1])){
								// se não for incluir, alterar, excluir,sql_query,sql_query_file
								if(    ($arrfunc[1]!="incluir")
								    and($arrfunc[1]!="alterar")
								    and($arrfunc[1]!="excluir")
								    and($arrfunc[1]!="sql_query")
								    and($arrfunc[1]!="sql_query_file")){
								//if(($arrfunc[1]!="incluir")and($arrfunc[1]!="alterar")and($arrfunc[1]!="excluir")){
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

                          //
                          // Tirando do db_ do inicio e o _classe.php do final
                          //
                          $iPosDB_     = strpos($file,'db_');
                          $iPos_classe = strpos($file,'_classe.php');

                          $tabela =  substr($file, ($iPosDB_ + 3), ($iPos_classe - 3) );

													// para pegaro codigo da tabela
													$sqltab = "select codarq from db_sysarquivo where nomearq = '$tabela'";
													$resulttab = pg_query($sqltab);
													$linhatab = pg_numrows($resulttab);
													if($linhatab>0){
														
														db_fieldsmemory($resulttab,0);
														$codigo = $codarq;
													
														// para o metodo tenho que tirar o "(" das funções xxx(
														$met="";
														$pos = strpos($arrfunc[1], "(");
														if ($pos == false) {
															$met =$arrfunc[1];
															
														}else{
															$metodo = split("\(", $arrfunc[1]);
															$met = $metodo[0];
															
														}
														
														//print_r($metodo);
	
														// para ver se ja tem cadastrado no banco
														$sql = "select * from db_sysclasses where codarq = $codigo and nomclasse = '$met'";
	
														// ver tabela db_sysclasses
														$result = pg_query($sql);
														$linhasres = pg_num_rows($result);
														if($linhasres>0){
															db_fieldsmemory($result,0);
															// tem no banco
															$operacao = "Alterar";
														}else{
															// não tem no banco
															$operacao = "Incluir";
															$codigoclass ="Não tem ";
														}
													
													//$operacao
													$fonteorig = "";
													$codarq = $codigo;
													$nomearq = $file;
													//$metodo= $metodo[0];
													$exibe = montaMetodoEx($lines,$line_num);
													$exibe = addslashes($exibe);
													$fontenovo = $exibe;
													$fonteorig = addslashes($codigoclass);
													$seq = $seq + 1;
													/*if(($numero==103)||($numero==98)){
														echo"<br><br>$numero - $nomearq - metodo = $met<br>
																arr0 = $arrfunc[0] <br> arr1 = $arrfunc[1] <br>$funcao<br>";
												   }*/
													$sqlinc = "insert into temp_classeatualiza values(   $seq,
																										 $codigo,
																										 '".trim($nomearq)."',
																										 '".trim($met)."',
																										 '".addslashes($fonteorig)."',
																										 '".addslashes($fontenovo)."',
																										 '$operacao')";

													$resultinc = pg_query($sqlinc) or die($sqlinc);
													}
													/*
														echo"
														<br>operação = $operacao<br>
														codigo = $codigo <br>
														nome do arquivo =$nomearq <br>
														metodo = $metodo <br>
														fonte novo = $fontenovo<br>
														fonte original = $fonteorig<br><br>
														";*/
														

												}
											}
										}
									}
								}// do if incluir, alterar, excluir,sql_query,sql_query_file
							}//coloquei



							}

						}

					}
				}
			}
			closedir($dh);
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
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>

<link href="estilos.css" rel="stylesheet" type="text/css">

</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"
	marginheight="0">
<table width="100%" height="18" border="0" cellpadding="0"
	cellspacing="0" bgcolor="#5786B2">
	<tr>
		<td width="360">&nbsp;</td>
		<td width="263">&nbsp;</td>
		<td width="25">&nbsp;</td>
		<td width="140">&nbsp;</td>
	</tr>
</table>
<form name="form1" action="">
<table width="100%" border="0" cellpadding="0" cellspacing="0" >
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b>Atualização de métodos</b></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><iframe name="centro" src="sys4_verificaclasse002.php"
			frameBorder="0" width="100%" height="400" scrolling="Auto"></iframe>
		</td>
	</tr>
	<tr>
		<td>&nbsp;
		</td>
	</tr>
	<tr>
		<td align="center"><input name="verifica"  type="submit" value="Verifica"> 
			<input name="processar" type="button" value="Processar" onclick = "js_gravar();"></td>
	</tr>
</table>
</form>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">

function js_retorno(oResposta){
  js_removeObj("msgBox");
  js_apaga(); 
}

function js_gravar(){
  var char = '';
  var classes = '';
  var objForm   = centro.document.form1;
  var arrInputs = objForm.getElementsByTagName('input');
  for(i=0; i<arrInputs.length; i++){
    if(arrInputs[i].type == 'checkbox'){
      if(arrInputs[i].checked){
        classes += char+arrInputs[i].value;
        char = '|';
      }
    }
  }
  if(classes != ''){
  	
    js_divCarregando("Aguarde, Processando registros","msgBox");
    var url       = 'sys4_verificaclasse003.php';
    var parametro = 'classes='+classes;	

    var objAjax   = new Ajax.Request (url,{ method:'post',parameters:parametro,onComplete:js_retorno});
     
  }else{
     alert('Selecione um metodo');  
  }
  
}
function js_apaga(){
 	document.form1.submit();
}
</script>