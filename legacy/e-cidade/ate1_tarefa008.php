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


define("TAREFA",true);

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_tarefa_classe.php");
include ("classes/db_tarefamodulo_classe.php");
include ("classes/db_tarefaproced_classe.php");
include ("classes/db_tarefasituacao_classe.php");
include ("classes/db_tarefausu_classe.php");
include ("classes/db_tarefaenvol_classe.php");
include("classes/db_tarefamotivo_classe.php");
include("classes/db_db_versaotarefa_classe.php");

$cltarefa         = new cl_tarefa;
$cltarefamodulo   = new cl_tarefamodulo;
$cltarefaproced   = new cl_tarefaproced;
$cltarefasituacao = new cl_tarefasituacao;
$cltarefaenvol    = new cl_tarefaenvol;
$cltarefausu      = new cl_tarefausu;
$cltarefamotivo   = new cl_tarefamotivo;
$cldb_versaotarefa= new cl_db_versaotarefa;

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$db_opcao = 22;
$db_botao = false;

if (isset ($alterar)) {
	$sqlerro = false;
	
	$result = $cltarefa->sql_record($cltarefa->sql_query_envol(null,"*","at40_sequencial,at45_usuario","at40_progresso < 100 and at40_sequencial<>$at40_sequencial and at45_usuario=$at40_responsavel"));
	if($cltarefa->numrows > 0) {
	  
	    $cltarefa->at40_sequencial = $at40_sequencial;
	    if (isset($at40_diaini)) {
	      $cltarefa->at40_diaini = $at40_diaini_ano . "-" . db_formatar($at40_diaini_mes,'s','0',2,'e',0) . "-" . db_formatar($at40_diaini_dia,'s','0',2,'e',0);
	    }
	    if (isset($at40_diafim)) {
	      $cltarefa->at40_diafim = $at40_diafim_ano . "-" . db_formatar($at40_diafim_mes,'s','0',2,'e',0) . "-" . db_formatar($at40_diafim_dia,'s','0',2,'e',0);
	    }
	  	  
	    if(isset($at40_horainidia) and strlen($at40_horainidia) == 2) {
	        $at40_horainidia .= ":00";
	    }
	  	  
	    if(isset($at40_horafim) and strlen($at40_horafim) == 2) {
		$at40_horafim .= ":00";
	    }
	     
	    if (isset($at40_horainidia)) {
	      $cltarefa->at40_horainidia = $at40_horainidia;
	    }
	    
	    if (isset($at40_horafim)) {
	      $cltarefa->at40_horafim    = $at40_horafim;
	    }
	      
	    $erro_horario = testa_horarios($result, $cltarefa);
	}
	
	db_inicio_transacao();

	$cltarefa->at40_autorizada = true;
	
	$cltarefa->alterar($at40_sequencial);
	$erro_msg = $cltarefa->erro_msg;
	if ($cltarefa->erro_status == 0) {
		$sqlerro = true;
	}
	$result = $cltarefamodulo->sql_record($cltarefamodulo->sql_query(null, "at49_sequencial", null, "at49_tarefa=$at40_sequencial"));
	if ($cltarefamodulo->numrows > 0) {
		db_fieldsmemory($result, 0);
		$cltarefamodulo->at49_modulo = $at49_modulo;
		$cltarefamodulo->at49_tarefa = $at40_sequencial;
		$cltarefamodulo->at49_sequencial = $at49_sequencial;
		$cltarefamodulo->alterar($at49_sequencial);
		if ($cltarefamodulo->erro_status == 0) {
			$erro_msg = $cltarefamodulo->erro_msg;
			$sqlerro = true;
		}
	}
	if ($sqlerro == false) {
		$cltarefaproced->at41_proced = $at41_proced;
		$cltarefaproced->at41_tarefa = $at40_sequencial;
		$cltarefaproced->alterar($at40_sequencial, null);
		if ($cltarefaproced->erro_status == 0) {
			$erro_msg = $cltarefaproced->erro_msg;
			$sqlerro = true;
		}
	}
	if ($sqlerro == false) {
		$result = $cltarefasituacao->sql_record($cltarefasituacao->sql_query(null, "at47_sequencial", null, "at47_tarefa=$at40_sequencial"));
		if ($cltarefasituacao->numrows > 0) {
			db_fieldsmemory($result, 0);
			$cltarefasituacao->at47_situacao = $at47_situacao;
			$cltarefasituacao->at47_sequencial = $at47_sequencial;
			$cltarefasituacao->alterar($at47_sequencial);
			if ($cltarefasituacao->erro_status == 0) {
				$erro_msg = $cltarefasituacao->erro_msg;
				$sqlerro = true;
			}
		}
	}
	if ($sqlerro == false) {
		$result = $cltarefausu->sql_record($cltarefausu->sql_query(null, "at42_sequencial", null, "at42_tarefa=$at40_sequencial and at42_usuario=$at40_usuant"));
		if ($cltarefausu->numrows > 0) {
			db_fieldsmemory($result, 0);
			$cltarefausu->at42_usuario = $at40_responsavel;
			$cltarefausu->at42_perc = 10;
//			$cltarefausu->at42_perc = $at40_progresso;
			$cltarefausu->at42_sequencial = $at42_sequencial;
			$cltarefausu->at42_tarefa = $at40_sequencial;
			$cltarefausu->alterar($at42_sequencial);
			if ($cltarefausu->erro_status == 0) {
				$erro_msg = $cltarefausu->erro_msg;
				$sqlerro = true;
			}
		}
	}
	if ($sqlerro == false) {
		$result = $cltarefaenvol->sql_record($cltarefaenvol->sql_query(null, "at45_sequencial", null, "at45_tarefa=$at40_sequencial and at45_usuario=$at40_usuant"));
		if ($cltarefaenvol->numrows > 0) {
			db_fieldsmemory($result, 0);
			$cltarefaenvol->at45_usuario = $at40_responsavel;
			$cltarefaenvol->at45_perc = 100;
//			$cltarefaenvol->at45_perc = $at40_progresso;
			$cltarefaenvol->at45_sequencial = $at45_sequencial;
			$cltarefaenvol->alterar($at45_sequencial);
			if ($cltarefaenvol->erro_status == 0) {
				$erro_msg = $cltarefaenvol->erro_msg;
				$sqlerro = true;
			}
		}
	}
	if($sqlerro == false) {
		$cltarefamotivo->at55_tarefa = $at40_sequencial;
		$cltarefamotivo->at55_motivo = $at54_sequencial;
		$cltarefamotivo->alterar($at40_sequencial);
		if ($cltarefamotivo->erro_status == 0) {
			$erro_msg = $cltarefamotivo->erro_msg;
			$sqlerro = true;
		}
	}
	if($sqlerro == false) {
		$result = $cltarefamodulo->sql_record($cltarefamodulo->sql_query_file(null, "at49_sequencial", null, "at49_tarefa=$at40_sequencial"));
		if ($cltarefamodulo->numrows > 0) {
			db_fieldsmemory($result, 0);
			$cltarefamodulo->at49_sequencial = $at49_sequencial;
			$cltarefamodulo->at49_modulo     = $at49_modulo;
			$cltarefamodulo->alterar($at49_sequencial);
			if ($cltarefamodulo->erro_status == 0) {
				$erro_msg = $cltarefamodulo->erro_msg;
				$sqlerro = true;
			}
		}
	}

	if($sqlerro == false) {
		$result = $cltarefaprojetoativcli->sql_record($cltarefaprojetoativcli->sql_query_file(null, "at69_sequencial", null, "at69_seqtarefa=$at40_sequencial"));
		if ($cltarefamodulo->numrows > 0) {
      db_fieldsmemory($result,0);
			$cltarefaprojetoativcli->at69_sequencial = $at69_sequencial;
			$cltarefaprojetoativcli->at69_seqprojeto = $at64_sequencial;
      $cltarefaprojetoativcli->at69_seqtarefa  = $at40_sequencial;
			$cltarefaprojetoativcli->alterar($at69_sequencial);
			if ($cltarefaprojetoativcli->erro_status == 0) {
				$erro_msg = $cltarefaprojetoativcli->erro_msg;
				$sqlerro = true;
			}
		}else{
			$cltarefaprojetoativcli->at69_sequencial = 0;
			$cltarefaprojetoativcli->at69_seqprojeto = $at64_sequencial;
      $cltarefaprojetoativcli->at69_seqtarefa  = $at40_sequencial;
			$cltarefaprojetoativcli->incluir($cltarefaprojetoativcli->at69_sequencial);
			if ($cltarefaprojetoativcli->erro_status == 0) {
				$erro_msg = $cltarefaprojetoativcli->erro_msg;
				$sqlerro = true;
			}
	
    }
    
	}


	db_fim_transacao($sqlerro);
	$db_opcao = 2;
	$db_botao = true;
} else {
	if(isset($chavepesquisa)) {
		$db_opcao = 2;
		$db_botao = true;
		$result = $cltarefa->sql_record($cltarefa->sql_query($chavepesquisa));
		if ($cltarefa->numrows > 0) {
			db_fieldsmemory($result, 0);
		}
		$result = $cltarefamodulo->sql_record($cltarefamodulo->sql_query(null, "*", null, "at49_tarefa=$chavepesquisa"));
		if ($cltarefamodulo->numrows > 0) {
			db_fieldsmemory($result, 0);
		}
		$result = $cltarefaproced->sql_record($cltarefaproced->sql_query(null, null, "*", null, "at41_tarefa=$chavepesquisa"));
		if ($cltarefaproced->numrows > 0) {
			db_fieldsmemory($result, 0);
		}

	    $result = $cltarefa->sql_record($cltarefa->sql_query_envol(null,"*","at40_sequencial,at45_usuario","at40_progresso < 100 and at40_sequencial<>$at40_sequencial and at45_usuario=$at40_responsavel"));
		if($cltarefa->numrows > 0) {
			$cltarefa->at40_sequencial = $chavepesquisa;
		    $cltarefa->at40_diaini     = $at40_diaini_ano . "-" . db_formatar($at40_diaini_mes,'s','0',2,'e',0) . "-" . db_formatar($at40_diaini_dia,'s','0',2,'e',0);
		    $cltarefa->at40_diafim     = $at40_diafim_ano . "-" . db_formatar($at40_diafim_mes,'s','0',2,'e',0) . "-" . db_formatar($at40_diafim_dia,'s','0',2,'e',0);
		  	  
		    if(strlen($at40_horainidia) == 2) {
		        $at40_horainidia .= ":00";
	   	  	}
		  	  
		  	if(strlen($at40_horafim) == 2) {
		  	    $at40_horafim .= ":00";
		  	}
		  	  
		  	$cltarefa->at40_horainidia = $at40_horainidia;
		  	$cltarefa->at40_horafim    = $at40_horafim;
		  	  
		  	$erro_horario = testa_horarios($result, $cltarefa);
		}
		
		db_fieldsmemory($result, 0);
			
		$result = $cltarefamotivo->sql_record($cltarefamotivo->sql_query(null,"at55_motivo","at55_tarefa","at55_tarefa=$chavepesquisa"));
		if ($cltarefamotivo->numrows > 0) {
			db_fieldsmemory($result, 0);
			$at54_sequencial = $at55_motivo;
		}
		$result = $cltarefasituacao->sql_record($cltarefasituacao->sql_query(null, "*", null, "at47_tarefa=$chavepesquisa"));
		if ($cltarefasituacao->numrows > 0) {
			db_fieldsmemory($result, 0);
		}
	}
	else {
		if(isset($at40_sequencial)&&$at40_sequencial != "") {
			$result = $cltarefa->sql_record($cltarefa->sql_query($at40_sequencial));
			if ($cltarefa->numrows > 0) {
				db_fieldsmemory($result, 0);
			}
			$result = $cltarefamodulo->sql_record($cltarefamodulo->sql_query(null, "*", null, "at49_tarefa=$at40_sequencial"));
			if ($cltarefamodulo->numrows > 0) {
				db_fieldsmemory($result, 0);
			}
			$result = $cltarefaproced->sql_record($cltarefaproced->sql_query(null, null, "*", null, "at41_tarefa=$at40_sequencial"));
			if ($cltarefaproced->numrows > 0) {
				db_fieldsmemory($result, 0);
			}

		  $result = $cltarefa->sql_record($cltarefa->sql_query_envol(null,"*","at40_sequencial,at45_usuario","at40_progresso < 100 and at40_sequencial<>$at40_sequencial and at45_usuario=$at40_responsavel"));
			if($cltarefa->numrows > 0) {
				$cltarefa->at40_sequencial = $at40_sequencial;
			    $cltarefa->at40_diaini     = $at40_diaini_ano . "-" . db_formatar($at40_diaini_mes,'s','0',2,'e',0) . "-" . db_formatar($at40_diaini_dia,'s','0',2,'e',0);
			    $cltarefa->at40_diafim     = $at40_diafim_ano . "-" . db_formatar($at40_diafim_mes,'s','0',2,'e',0) . "-" . db_formatar($at40_diafim_dia,'s','0',2,'e',0);
			  	  
			    if(strlen($at40_horainidia) == 2) {
			        $at40_horainidia .= ":00";
		   	  	}
			  	  
			  	if(strlen($at40_horafim) == 2) {
			  	    $at40_horafim .= ":00";
			  	}
			  	  
			  	$cltarefa->at40_horainidia = $at40_horainidia;
			  	$cltarefa->at40_horafim    = $at40_horafim;
			  	  
			  	$erro_horario = testa_horarios($result, $cltarefa);
			}

			$result = $cltarefa->sql_record($cltarefa->sql_query_envol(null,"at40_autorizada, tarefa_lanc.*, db_usuarios2.nome as criador","","at40_sequencial=$at40_sequencial"));
			db_fieldsmemory($result,0);

			$result = $cltarefamotivo->sql_record($cltarefamotivo->sql_query(null,"at55_motivo","at55_tarefa","at55_tarefa=$at40_sequencial"));
			if ($cltarefamotivo->numrows > 0) {
				db_fieldsmemory($result, 0);
				$at54_sequencial = $at55_motivo;
			}
			$result = $cltarefasituacao->sql_record($cltarefasituacao->sql_query(null, "*", null, "at47_tarefa=$at40_sequencial"));
			if ($cltarefasituacao->numrows > 0) {
				db_fieldsmemory($result, 0);
			}
			
			$db_opcao = 2;
			$db_botao = true;
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
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
include ("forms/db_frmcontarefa.php");
?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?


if (isset ($alterar)) {
	if ($sqlerro == true) {
		db_msgbox($erro_msg);
		if ($cltarefa->erro_campo != "") {
			echo "<script> document.form1.".$cltarefa->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$cltarefa->erro_campo.".focus();</script>";
		};
	} else {
		db_msgbox($erro_msg);
		echo "<script>top.corpo.iframe_tarefausu.location.href='ate1_tarefausu001.php?at42_tarefa=".@$at40_sequencial."'</script>";
	}
}
if (isset ($chavepesquisa)||isset($at40_sequencial)) {
	echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.tarefaobs.disabled=false;
         parent.document.formaba.tarefaclientes.disabled=false;
         parent.document.formaba.tarefausu.disabled=false;
         parent.document.formaba.tarefaenvol.disabled=false;
         " . (($at40_autorizada == 't' or 1==1)?"parent.document.formaba.tarefalog.disabled=false;":"") . "
         parent.document.formaba.agenda.disabled=false;
	     parent.document.formaba.tarefaanexos.disabled=false;
         ".(isset($menu)?"parent":"top.corpo").".iframe_tarefaobs.location.href='ate1_tarefaobs001.php?at42_tarefa=".@$at40_sequencial."';
         ".(isset($menu)?"parent":"top.corpo").".iframe_tarefaclientes.location.href='ate1_tarefaclientes002.php?at70_tarefa=".@$at40_sequencial."';
         ".(isset($menu)?"parent":"top.corpo").".iframe_tarefausu.location.href='ate1_tarefausu002.php?at42_tarefa=".@$at40_sequencial."';
         ".(isset($menu)?"parent":"top.corpo").".iframe_tarefaenvol.location.href='ate1_tarefaenvol002.php?at45_tarefa=".@$at40_sequencial."';
         ".(isset($menu)?"parent":"top.corpo").".iframe_tarefalog.location.href='ate1_tarefalogand002.php?at43_tarefa=".@$at40_sequencial."&at43_usuario=".db_getsession("DB_id_usuario")."';
         ".(isset($menu)?"parent":"top.corpo").".iframe_agenda.location.href='ate1_tarefaagenda002.php?at13_tarefa=".@$at40_sequencial."';
         ".(isset($menu)?"parent":"top.corpo").".iframe_tarefaanexos.location.href='ate3_constarefaanexos.php?at25_tarefa=".@$at40_sequencial."';
     ";
	if (isset ($liberaaba)) {
		echo "  parent.mo_camada('tarefausu');";
	}
	echo "}\n
    js_db_libera();
  </script>\n
 ";
}

if (isset($db_opcao)&&$db_opcao == 22) {
	echo "<script> js_pesquisa_tarefa(); </script>";
}

function testa_horarios($result, $cltarefa) {
	$retorno    = false;
	$NumRows    = $cltarefa->numrows;
	$NumFields  = pg_numfields($result);
	$db_diaini  = "";
	$db_diafim  = "";
	$db_horaini = "";
	$db_horafim = "";
		
	for($i= 0; $i < $NumRows; $i++) {
		for($j = 0; $j < $NumFields; $j++) {
			if(pg_fieldname($result, $j) == "at40_diaini") {
				$db_diaini = db_formatar(pg_result($result, $i, $j),'d');
			}
			if(pg_fieldname($result, $j) == "at40_diafim") {
				$db_diafim = db_formatar(pg_result($result, $i, $j),'d');
			}
			if(pg_fieldname($result, $j) == "at40_horainidia") {
				$db_horaini = pg_result($result, $i, $j);
				if(strlen(trim($db_horaini)) == 2) {
					$db_horaini  = substr(pg_result($result, $i, $j),0,2);
					$db_horaini .= ":00";
				}
			}
			if(pg_fieldname($result, $j) == "at40_horafim") {
				$db_horafim = pg_result($result, $i, $j);
				if(strlen(trim($db_horafim)) == 2) {
					$db_horafim  = substr(pg_result($result, $i, $j),0,2);
					$db_horafim .= ":00";
				}
			}

			if(strlen($db_diaini)  > 0 &&
			   strlen($db_diafim)  > 0 &&
			   strlen($db_horaini) > 0 &&
			   strlen($db_horafim) > 0) {
			   	if($db_diaini <= $cltarefa->at40_diafim ||	// Ex.: 01/02/2006 <= 03/02/2006 or 
			   	   $db_diafim >= $cltarefa->at40_diaini) {  //      02/02/2006 >= 02/02/2006
			   	   	if($cltarefa->at40_horainidia <= $db_horafim &&
			   	   	   $cltarefa->at40_horafim    >= $db_horaini) {
			   	   	   	$retorno = true;
			   	   	   	break;
			   	   	}
			   	}
			}
		}
	}
	
	return($retorno);
}
?>