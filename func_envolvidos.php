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
include("dbforms/db_funcoes.php");
include("classes/db_tarefalogenvol_classe.php");
include("classes/db_tarefaenvol_classe.php");
include("classes/db_db_usuarios_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldb_usuarios    = new cl_db_usuarios;
$cltarefaenvol    = new cl_tarefaenvol;
$cltarefalogenvol = new cl_tarefalogenvol;

$usuarios = "";
$erro_msg = "";
$sqlerro  = false;
if(isset($incluir)){
	db_inicio_transacao();
	
	if($at43_sequencial=="") {
		db_msgbox("Não é possivel incluir usuários envolvidos neste registro pois, este ainda não foi incluido!");
		$sqlerro = true;
	}
	
	if($sqlerro==false) {
		$rs_usuarios = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file(null,"id_usuario","id_usuario"," usuarioativo = '1' and usuext = 0"));
		if($cldb_usuarios->numrows>0) {
			$NumRows = $cldb_usuarios->numrows;
			$maxperc = 0;
			for($i=0;$i<$NumRows;$i++) {
				db_fieldsmemory($rs_usuarios,$i);
				$chk  = "chk_".$id_usuario;
				$perc = "perc_".$id_usuario;
				if(@$HTTP_POST_VARS[$chk] == $id_usuario) {
					if(@$HTTP_POST_VARS[$perc]==""||trim(@$HTTP_POST_VARS[$perc])=="0") {
						$erro_msg = "Percentual deve ser maior que zero";
						$sqlerro  = true;
						break;
					}
					else {
					  if ($HTTP_POST_VARS[$perc] > $maxperc) {
					    $maxperc = $HTTP_POST_VARS[$perc];
					  }
						$rs_tarefalogenvol = $cltarefalogenvol->sql_record($cltarefalogenvol->sql_query_file(null,"at35_usuario","at35_tarefalog","at35_tarefalog=$at43_sequencial and at35_usuario=$id_usuario"));
						if($cltarefalogenvol->numrows==0) {
							$cltarefalogenvol->at35_tarefalog = $at43_sequencial;
							$cltarefalogenvol->at35_usuario   = $id_usuario;
							$cltarefalogenvol->at35_perc      = $HTTP_POST_VARS[$perc];
							$cltarefalogenvol->incluir(null);
							$erro_msg = $cltarefalogenvol->erro_msg;
							if($cltarefalogenvol->erro_status=="0") {
								$sqlerro  = true;
								$erro_msg = $cltarefalogenvol->erro_msg;
								break;
							}
						}
						if($sqlerro==false) {
	   						$rs_tarefaenvol = $cltarefaenvol->sql_record($cltarefaenvol->sql_query_file(null,"at45_usuario, at45_sequencial","at45_tarefa","at45_tarefa=$at43_tarefa and at45_usuario=$id_usuario"));
							if($cltarefaenvol->numrows==0) {
								$cltarefaenvol->at45_tarefa  = $at43_tarefa;
								$cltarefaenvol->at45_usuario = $id_usuario;
								$cltarefaenvol->at45_perc    = $HTTP_POST_VARS[$perc];
								$cltarefaenvol->incluir(null);
								if($cltarefaenvol->erro_status=="0") {
									$sqlerro  = true;
									$erro_msg = $cltarefaenvol->erro_msg;
									break;
								}
							} else {

								db_fieldsmemory($rs_tarefaenvol, 0);
								$cltarefaenvol->at45_sequencial  = $at45_sequencial;
								$cltarefaenvol->at45_tarefa  = $at43_tarefa;
								$cltarefaenvol->at45_usuario = $id_usuario;
								$cltarefaenvol->at45_perc    = $HTTP_POST_VARS[$perc];
								$cltarefaenvol->alterar($at45_sequencial);
								if($cltarefaenvol->erro_status=="0") {
									$sqlerro  = true;
									$erro_msg = $cltarefaenvol->erro_msg;
									break;
								}




							  
							}
						}
					}
				} 
			}

			if ($maxperc < 100) {
			  $erro_msg = "Deve haver no mínimo 1 usuario com 100% de envolvimento!";
			  $sqlerro = true;
			}
		}
	}

	db_fim_transacao($sqlerro);
}
else if(isset($alterar)) {
	db_inicio_transacao();

	$rs_usuarios = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file(null,"id_usuario","id_usuario"," usuarioativo = '1' and usuext = 0"));
	if($cldb_usuarios->numrows>0) {
		$NumRows = $cldb_usuarios->numrows;
		$maxperc = 0;
		for($i=0;$i<$NumRows;$i++) {
			db_fieldsmemory($rs_usuarios,$i);
			$chk  = "chk_".$id_usuario;
			$perc = "perc_".$id_usuario;
			if(@$HTTP_POST_VARS[$chk] == $id_usuario) {
				if(@$HTTP_POST_VARS[$perc]==""||trim(@$HTTP_POST_VARS[$perc])=="0") {
					$erro_msg = "Percentual deve ser maior que zero";
					$sqlerro  = true;
					break;
				}
				else {
					if ($HTTP_POST_VARS[$perc] > $maxperc) {
					  $maxperc = $HTTP_POST_VARS[$perc];
					}
					$rs_tarefalogenvol = $cltarefalogenvol->sql_record($cltarefalogenvol->sql_query_file(null,"at35_sequencia","at35_tarefalog","at35_tarefalog=$at43_sequencial and at35_usuario=$id_usuario"));
					if($cltarefalogenvol->numrows>0) {
						db_fieldsmemory($rs_tarefalogenvol,0);
						$cltarefalogenvol->at35_sequencia = $at35_sequencia; 
						$cltarefalogenvol->at35_perc      = $HTTP_POST_VARS[$perc];
						$cltarefalogenvol->alterar($at35_sequencia);
						$erro_msg = $cltarefalogenvol->erro_msg;
						if($cltarefalogenvol->erro_status=="0") {
							$sqlerro  = true;
							break;
						}
					}

					if($sqlerro==false) {
   						$rs_tarefaenvol = $cltarefaenvol->sql_record($cltarefaenvol->sql_query_file(null,"at45_sequencial","at45_tarefa","at45_tarefa=$at43_tarefa and at45_usuario=$id_usuario"));
						if($cltarefaenvol->numrows>0) {
							db_fieldsmemory($rs_tarefaenvol,0);
							$cltarefaenvol->at45_sequencial = $at45_sequencial;
							$cltarefaenvol->at45_perc       = $HTTP_POST_VARS[$perc];
							$cltarefaenvol->alterar($at45_sequencial);
							if($cltarefaenvol->erro_status=="0") {
								$sqlerro  = true;
								$erro_msg = $cltarefaenvol->erro_msg;
								break;
							}
						}
					}
				}
			} 
		}

		if ($maxperc < 100) {
		  $erro_msg = "Deve haver no mínimo 1 usuario com 100% de envolvimento!";
		  $sqlerro = true;
		}

	}

	db_fim_transacao($sqlerro);
}
else if(isset($excluir)) {
	db_inicio_transacao();

	$rs_usuarios = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file(null,"id_usuario","id_usuario"," usuarioativo = '1' and usuext = 0"));
	if($cldb_usuarios->numrows>0) {
		$NumRows = $cldb_usuarios->numrows;
		for($i=0;$i<$NumRows;$i++) {
			db_fieldsmemory($rs_usuarios,$i);
			$chk = "chk_".$id_usuario;
			if(@$HTTP_POST_VARS[$chk] == $id_usuario) {
				$cltarefalogenvol->excluir(null,"at35_tarefalog=$at43_sequencial and at35_usuario=$id_usuario");
				$erro_msg = $cltarefalogenvol->erro_msg;
				if($cltarefalogenvol->erro_status=="0") {
					$sqlerro = true;
					break;
				}
				if($sqlerro==false) {
					$cltarefaenvol->excluir(null,"at45_tarefa=$at43_tarefa and at45_usuario=$id_usuario");
					if($cltarefaenvol->erro_status=="0") {
						$sqlerro  = true;
						$erro_msg = $cltarefaenvol->erro_msg;
						break;
					}
				}
			} 
		}
	}

	db_fim_transacao($sqlerro);
}

if(strlen($erro_msg)>0) {
	db_msgbox($erro_msg);
}

$rs_tarefaenvol = $cltarefaenvol->sql_record($cltarefaenvol->sql_query(null,"db_usuarios.id_usuario,db_usuarios.nome,at45_perc","nome","at45_tarefa=$at43_tarefa"));
if($cltarefaenvol->numrows>0) {
	$id       = "";
	$usuarios = "";
	$NumRows  = $cltarefaenvol->numrows;
	for($i=0;$i<$NumRows;$i++) {
		db_fieldsmemory($rs_tarefaenvol,$i);
		if($id!=$id_usuario) {
			$usuarios .= $id_usuario.",";
			$id        = $id_usuario;
		} 
	}
	$usuarios = substr($usuarios,0,strlen($usuarios)-1);
}
if(strlen($usuarios)>0) {
	$where = "usuarioativo = '1' and usuext = 0 and id_usuario not in(".$usuarios.")";
}
else {
	$where = " usuarioativo = '1' and usuext = 0 ";
}
$rs_usuarios = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file(null,"id_usuario,nome","nome",$where));
?>
<link href="estilos.css" rel="stylesheet" type="text/css">
<form name="formulario" action="<?=$PHP_SELF?>" method="post">
	<table border="0" cellspacing="1" cellpadding="0">
		<tr><td colspan="3">Marque os usuários:</td><tr>
<?php
	db_input("at43_sequencial",10,"",true,"hidden",3,"");
	db_input("at43_tarefa",10,"",true,"hidden",3,"");
	db_input("db_opcao",1,"",true,"hidden",3,"");
	db_input("db_botao",10,"",true,"hidden",3,"");
	
	if($cltarefaenvol->numrows>0) {
?>
		<tr><td colspan="3" align="center"><b>USUARIOS ENVOLVIDOS</b></td><tr>
<?php
		$usuario = "";
		$NumRows = $cltarefaenvol->numrows;
		for($i=0;$i<$NumRows;$i++) {
			db_fieldsmemory($rs_tarefaenvol,$i);
			if($usuario!=$id_usuario) {
?>
		<tr>
			<td><input name="chk_<?=$id_usuario?>" type="checkbox" checked value="<?=$id_usuario?>"></td>
			<td><?=$nome?></td>
			<td><input name="perc_<?=$id_usuario?>"type="text" onchange='js_testaperc(this);'value="<?=$at45_perc?>" size="5" maxlength="5">&nbsp;%</td>
		</tr>
<?php
				$usuario = $id_usuario;
			}
		}
	}
?>
		<tr><td height="20" colspan="3">&nbsp;</td></tr>
		<tr><td colspan="3" align="center"><b>USUARIOS NAO ENVOLVIDOS</b></td><tr>
<?php	
	if($cldb_usuarios->numrows>0) {
		$NumRows = $cldb_usuarios->numrows;
		for($i=0;$i<$NumRows;$i++) {
			db_fieldsmemory($rs_usuarios,$i);
?>
		<tr>
			<td><input name="chk_<?=$id_usuario?>" type="checkbox" value="<?=$id_usuario?>"></td>
			<td><?=$nome?></td>
			<td><input name="perc_<?=$id_usuario?>" type="text" onchange='js_testaperc(this);' value="" size="5" maxlength="5">&nbsp;%</td>
		</tr>
<?php
		}
	}
?>			
	</table>
	<center>
	<table border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td align="left"><input name="incluir" type="submit" id="1" value="Incluir"></td>
			<td align="left"><input name="alterar" type="submit" id="2" value="Alterar"></td>
			<td align="left"><input name="excluir" type="submit" id="3" value="Excluir"></td>
		</tr>
	</table>
	</center>
</form>
<script>
function js_testaperc(obj) {
	if (obj.value > 100) {
		alert('Percentual nao pode ser maior que 100 por cento!');
		obj.value = 0;
		return false;
	}
	return true;
}
</script>