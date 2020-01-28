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

//programas
require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("classes/db_db_sysprikey_classe.php");
include ("classes/db_db_sysarqcamp_classe.php");
include ("classes/db_db_sysarquivo_classe.php");
include ("classes/db_db_sysarqmod_classe.php");
$cldb_sysarqcamp = new cl_db_sysarqcamp ( );
$cldb_sysprikey = new cl_db_sysprikey ( );
$cldb_sysarquivo = new cl_db_sysarquivo ( );
$cldb_sysarqmod = new cl_db_sysarqmod ( );
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript"
	src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"
	marginheight="0" onLoad="a=1">
<?

parse_str ( $HTTP_SERVER_VARS ["QUERY_STRING"] );
// Tabelas
$codarqpai = $codarq;

//pega os dados ta tabela db_sysarquivo
$result01 = $cldb_sysarquivo->sql_record ( $cldb_sysarquivo->sql_query_file ( $codarq, "nomearq as nometab, tipotabela" ) );
db_fieldsmemory ( $result01, 0 );

$arr_nometabfilho = array ();
$arr_codarqfilho = split ( "XX", $codfilhos );
for($i = 0; $i < count ( $arr_codarqfilho ); $i ++) {
	$result01 = $cldb_sysarquivo->sql_record ( $cldb_sysarquivo->sql_query_file ( $arr_codarqfilho [$i], "nomearq as tabfilho, tipotabela as tipotabelafilho" ) );
	db_fieldsmemory ( $result01, 0 );
	$arr_nometabfilho [$i] = $tabfilho;
}

for($l = 0; $l < count ( $arr_nometabfilho ); $l ++) {
	$arr_nometabfilho [$l] = trim ( $arr_nometabfilho [$l] );
}

$result = $cldb_sysarqmod->sql_record ( $cldb_sysarqmod->sql_query ( null, $codarq, "db_sysarqmod.codarq,db_sysarquivo.nomearq,db_sysarqmod.codmod,db_sysmodulo.nomemod,db_sysarquivo.rotulo" ) );

//rotina que gera abas
$RecordsetTabMod = $result;
$root = substr ( $HTTP_SERVER_VARS ['SCRIPT_FILENAME'], 0, strrpos ( $HTTP_SERVER_VARS ['SCRIPT_FILENAME'], "/" ) );
$siglamod = strtolower(pg_result ( $result, 0, 'nomemod' ));

umask ( 74 );
$siglamod = substr ( $siglamod, 0, 3 );
$arr_arqaba = array ("0" => "001.php", "1" => "002.php", "2" => "003.php" );
$arr_arqpro = array ("0" => "004.php", "1" => "005.php", "2" => "006.php" );
$arr_arqnom = array ("0" => "incluir", "1" => "alterar", "2" => "excluir" );

$nometab = trim ( $nometab );
for($u = 0; $u < count ( $arr_arqaba ); $u ++) {
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//rotina  que gera as abas de manutenção	
	$arq = $root . "/" . $siglamod . "1_" . $nometab . $arr_arqaba [$u];
	$fd1 = fopen ( strtolower ( $arq ), "w" );
	fputs ( $fd1, "<?\n" );
	fputs ( $fd1, 'require("libs/db_stdlib.php");' . "\n" );
	fputs ( $fd1, 'require("libs/db_conecta.php");' . "\n" );
	fputs ( $fd1, 'include("libs/db_sessoes.php");' . "\n" );
	fputs ( $fd1, 'include("libs/db_usuariosonline.php");' . "\n" );
	fputs ( $fd1, 'include("dbforms/db_funcoes.php");' . "\n" );
	fputs ( $fd1, 'include("dbforms/db_classesgenericas.php");' . "\n" );
	fputs ( $fd1, '$clcriaabas     = new cl_criaabas;' . "\n" );
	fputs ( $fd1, '$db_opcao = 1;' . "\n" );
	fputs ( $fd1, '?>' . "\n" );
	fputs ( $fd1, '<html>' . "\n" );
	fputs ( $fd1, '<head>' . "\n" );
	fputs ( $fd1, '  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>' . "\n" );
	fputs ( $fd1, '  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">' . "\n" );
	fputs ( $fd1, '  <meta http-equiv="Expires" CONTENT="0">' . "\n" );
	fputs ( $fd1, '  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>' . "\n" );
	fputs ( $fd1, '  <link href="estilos.css" rel="stylesheet" type="text/css">' . "\n" );
	fputs ( $fd1, '</head>' . "\n" );
	fputs ( $fd1, '<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >' . "\n" );
	fputs ( $fd1, '<table width="790" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">' . "\n" );
	fputs ( $fd1, '  <tr> ' . "\n" );
	fputs ( $fd1, '    <td width="360">&nbsp;</td>' . "\n" );
	fputs ( $fd1, '    <td width="263">&nbsp;</td>' . "\n" );
	fputs ( $fd1, '    <td width="25">&nbsp;</td>' . "\n" );
	fputs ( $fd1, '    <td width="140">&nbsp;</td>' . "\n" );
	fputs ( $fd1, '  </tr>' . "\n" );
	fputs ( $fd1, '</table>' . "\n" );
	fputs ( $fd1, '<table valign="top" marginwidth="0" width="790" border="0" cellspacing="0" cellpadding="0">' . "\n" );
	fputs ( $fd1, '  <tr> ' . "\n" );
	fputs ( $fd1, '    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> ' . "\n" );
	fputs ( $fd1, '     <?' . "\n" );
	fputs ( $fd1, '	     $clcriaabas->identifica = array("' . $nometab . '"=>"' . $nometab . '"' );
	//for para colocar todas as tabelhas filhas 
	for($l = 0; $l < count ( $arr_nometabfilho ); $l ++) {
		fputs ( $fd1, ',"' . $arr_nometabfilho [$l] . '"=>"' . $arr_nometabfilho [$l] . '"' );
	}
	fputs ( $fd1, '); ' . "\n" );
	fputs ( $fd1, '	     $clcriaabas->src = array("' . $nometab . '"=>"' . strtolower ( $siglamod . '1_' . $nometab . $arr_arqpro [$u] ) . '");' . "\n" );
	fputs ( $fd1, '	     $clcriaabas->disabled   =  array(' );
	
	//for para colocar todas as tabelhas filhas 
	$pri = '';
	for($l = 0; $l < count ( $arr_nometabfilho ); $l ++) {
		fputs ( $fd1, $pri . '"' . $arr_nometabfilho [$l] . '"=>"true"' );
		$pri = ',';
	}
	fputs ( $fd1, '); ' . "\n" );
	fputs ( $fd1, '	     $clcriaabas->cria_abas(); ' . "\n" );
	fputs ( $fd1, '     ?> ' . "\n" );
	fputs ( $fd1, '    </td>' . "\n" );
	fputs ( $fd1, '  </tr>' . "\n" );
	fputs ( $fd1, '</table>' . "\n" );
	fputs ( $fd1, '<form name="form1">' . "\n" );
	fputs ( $fd1, '</form>' . "\n" );
	fputs ( $fd1, '<? ' . "\n" );
	fputs ( $fd1, '	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));' . "\n" );
	fputs ( $fd1, '?>' . "\n" );
	fputs ( $fd1, '</body>' . "\n" );
	fputs ( $fd1, '</html>' . "\n" );
	fclose ( $fd1 );
}
/*************************************************************************************************************************/
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//rotina que gera os programas de manutenção da tabela pai	  
for($u = 0; $u < count ( $arr_arqaba ); $u ++) {
	umask ( 74 );
	$arq002 = $root . "/" . $siglamod . "1_" . $nometab . $arr_arqpro [$u];
	$fd1 = fopen ( strtolower ( $arq002 ), "w" );
	fputs ( $fd1, "<?\n" );
	//$resultpkfilho   = $cldb_sysprikey->sql_record($cldb_sysprikey->sql_query(null,$codarqfilho,null,"db_syscampo.nomecam as nomecamfilho","db_sysprikey.sequen"));  
	//$numrowspkfilho =  $cldb_sysprikey->numrows;
	

	$resultpk = $cldb_sysprikey->sql_record ( $cldb_sysprikey->sql_query ( null, 
                                                                         $codarq, 
                                                                         null, 
                                                                         "db_sysarquivo.nomearq,db_syscampo.nomecam,db_sysprikey.sequen", 
                                                                         "db_sysprikey.sequen" ) 
                                          );
	$numrowspk = $cldb_sysprikey->numrows;
	
	$resultcamp = $cldb_sysarqcamp->sql_record ( $cldb_sysarqcamp->sql_query ( $codarq, null, null, "db_syscampo.*", "db_sysarqcamp.seqarq" ) );
	$numrowscamp = $cldb_sysarqcamp->numrows;
	if ($numrowscamp > 0) {
		fputs ( $fd1, 'require("libs/db_stdlib.php");' . "\n" );
		fputs ( $fd1, 'require("libs/db_conecta.php");' . "\n" );
		fputs ( $fd1, 'include("libs/db_sessoes.php");' . "\n" );
		fputs ( $fd1, 'include("libs/db_usuariosonline.php");' . "\n" );
		fputs ( $fd1, 'include("dbforms/db_funcoes.php");' . "\n" );
		fputs ( $fd1, 'include("classes/db_' . $nometab . '_classe.php");' . "\n" );
		
		//rotina que inclui todas as classes filhas
		for($l = 0; $l < count ( $arr_nometabfilho ); $l ++) {
			fputs ( $fd1, 'include("classes/db_' . $arr_nometabfilho [$l] . '_classe.php");' . "\n" );
		}
		
		fputs ( $fd1, '$cl' . $nometab . ' = new cl_' . $nometab . ';' . "\n" );
		
		fputs ( $fd1, '  /*' . "\n" );
		for($l = 0; $l < count ( $arr_nometabfilho ); $l ++) {
			fputs ( $fd1, '$cl' . $arr_nometabfilho [$l] . ' = new cl_' . $arr_nometabfilho [$l] . ';' . "\n" );
		}
		fputs ( $fd1, '  */' . "\n" );
		
		fputs ( $fd1, 'db_postmemory($HTTP_POST_VARS);' . "\n" );
		if ($arr_arqnom [$u] == "incluir") {
			fputs ( $fd1, '   $db_opcao = 1;' . "\n" );
			fputs ( $fd1, '$db_botao = true;' . "\n" );
		} else if ($arr_arqnom [$u] == "alterar") {
			fputs ( $fd1, '   $db_opcao = 22;' . "\n" );
			fputs ( $fd1, '$db_botao = false;' . "\n" );
		} else if ($arr_arqnom [$u] == "excluir") {
			fputs ( $fd1, '   $db_opcao = 33;' . "\n" );
			fputs ( $fd1, '$db_botao = false;' . "\n" );
		}
		
		fputs ( $fd1, 'if(isset($' . $arr_arqnom [$u] . ')){' . "\n" );
		fputs ( $fd1, '  $sqlerro=false;' . "\n" );
		fputs ( $fd1, '  db_inicio_transacao();' . "\n" );
		
		//////////////////////////////////////////////////////////////////////////////////
		//quando for o programa de exclusão, ele irá montar para excluir das tabelas filhas
		if ($arr_arqnom [$u] == "excluir") {
			
			for($l = 0; $l < count ( $arr_codarqfilho ); $l ++) {
				$codarqfilho = $arr_codarqfilho [$l];
				$nometabfilho = $arr_nometabfilho [$l];
				
				$resultpkfilho = $cldb_sysprikey->sql_record ( $cldb_sysprikey->sql_query ( null, $codarqfilho, null, "db_syscampo.nomecam as nomecamfilho", "db_sysprikey.sequen" ) );
				$numrowspkfilho = $cldb_sysprikey->numrows;
				if ($numrowspk > 0) {
					for($p = 0; $p < $numrowspk; $p ++) {
						db_fieldsmemory ( $resultpkfilho, $p );
						db_fieldsmemory ( $resultpk, $p );
						fputs ( $fd1, '  $cl' . $nometabfilho . '->' . trim ( $nomecamfilho ) . '=$' . $nomecam . ';' . "\n" );
					}
				}
				fputs ( $fd1, '  $cl' . $nometabfilho . '->' . $arr_arqnom [$u] . '(' );
				if ($numrowspkfilho > 0) {
					$virgula = "";
					for($p = 0; $p < $numrowspk; $p ++) {
						db_fieldsmemory ( $resultpk, $p );
						fputs ( $fd1, $virgula . '$' . trim ( $nomecam ) );
						$virgula = ",";
					}
				}
				fputs ( $fd1, ');' . "\n\n" );
				fputs ( $fd1, '  if($cl' . $nometabfilho . '->erro_status==0){' . "\n" );
				fputs ( $fd1, '    $sqlerro=true;' . "\n" );
				fputs ( $fd1, '  } ' . "\n" );
				fputs ( $fd1, '  $erro_msg = $cl' . $nometabfilho . '->erro_msg; ' . "\n" );
			}
		}
		//fim
		//////////////////////////////////////////////////
		fputs ( $fd1, '  $cl' . $nometab . '->' . $arr_arqnom [$u] . '(' );
		if ($numrowspk > 0) {
			$virgula = "";
			for($p = 0; $p < $numrowspk; $p ++) {
				db_fieldsmemory ( $resultpk, $p );
				fputs ( $fd1, $virgula . '$' . trim ( $nomecam ) );
				$virgula = ",";
			}
		}
		fputs ( $fd1, ');' . "\n" );
		fputs ( $fd1, '  if($cl' . $nometab . '->erro_status==0){' . "\n" );
		fputs ( $fd1, '    $sqlerro=true;' . "\n" );
		fputs ( $fd1, '  } ' . "\n" );
		fputs ( $fd1, '  $erro_msg = $cl' . $nometab . '->erro_msg; ' . "\n" );
		fputs ( $fd1, '  db_fim_transacao($sqlerro);' . "\n" );
		if ($arr_arqnom [$u] == "alterar") {
			fputs ( $fd1, '   $db_opcao = 2;' . "\n" );
		} else if ($arr_arqnom [$u] == "excluir") {
			fputs ( $fd1, '   $db_opcao = 3;' . "\n" );
		} else {
			if ($numrowspk > 0) {
				$virgula = "";
				for($p = 0; $p < $numrowspk; $p ++) {
					db_fieldsmemory ( $resultpk, $p );
					fputs ( $fd1, '   $' . trim ( $nomecam ) . '= $cl' . $nometab . '->' . $nomecam . ';' . "\n" );
					$virgula = ",";
				}
			}
			fputs ( $fd1, '   $db_opcao = 1;' . "\n" );
		}
		fputs ( $fd1, '   $db_botao = true;' . "\n" );
		
		if ($arr_arqnom [$u] == "alterar" || $arr_arqnom [$u] == "excluir") {
			fputs ( $fd1, '}else if(isset($chavepesquisa)){' . "\n" );
			
			if ($arr_arqnom [$u] == "alterar") {
				fputs ( $fd1, '   $db_opcao = 2;' . "\n" );
			} else {
				fputs ( $fd1, '   $db_opcao = 3;' . "\n" );
			}
			fputs ( $fd1, '   $db_botao = true;' . "\n" );
			
			fputs ( $fd1, '   $result = $cl' . $nometab . '->sql_record($cl' . $nometab . '->sql_query($chavepesquisa' );
			
			if ($numrowspk > 1) {
				$virgula = "";
				for($p = 1; $p < $numrowspk; $p ++) {
					fputs ( $fd1, ',$chavepesquisa' . $p );
					$virgula = ",";
				}
			}
			
			fputs ( $fd1, ')); ' . "\n" );
			fputs ( $fd1, '   db_fieldsmemory($result,0);' . "\n" );
		}
		
		fputs ( $fd1, '}' . "\n" );
		fputs ( $fd1, '?>' . "\n" );
		fputs ( $fd1, '<html>' . "\n" );
		fputs ( $fd1, '<head>' . "\n" );
		fputs ( $fd1, '<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>' . "\n" );
		fputs ( $fd1, '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">' . "\n" );
		fputs ( $fd1, '<meta http-equiv="Expires" CONTENT="0">' . "\n" );
		fputs ( $fd1, '<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>' . "\n" );
		fputs ( $fd1, '<link href="estilos.css" rel="stylesheet" type="text/css">' . "\n" );
		fputs ( $fd1, '</head>' . "\n" );
		fputs ( $fd1, '<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >' . "\n" );
		fputs ( $fd1, '<table width="790" border="0" cellspacing="0" cellpadding="0">' . "\n" );
		fputs ( $fd1, '  <tr> ' . "\n" );
		fputs ( $fd1, '    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> ' . "\n" );
		fputs ( $fd1, '    <center>' . "\n" );
		fputs ( $fd1, '	<?' . "\n" );
		fputs ( $fd1, '	include("forms/db_frm' . $nometab . '.php");' . "\n" );
		fputs ( $fd1, '	?>' . "\n" );
		fputs ( $fd1, '    </center>' . "\n" );
		fputs ( $fd1, '	</td>' . "\n" );
		fputs ( $fd1, '  </tr>' . "\n" );
		fputs ( $fd1, '</table>' . "\n" );
		fputs ( $fd1, '</body>' . "\n" );
		fputs ( $fd1, '</html>' . "\n" );
		fputs ( $fd1, '<?' . "\n" );
		
		fputs ( $fd1, 'if(isset($' . $arr_arqnom [$u] . ')){' . "\n" );
		fputs ( $fd1, '  if($sqlerro==true){' . "\n" );
		fputs ( $fd1, '    db_msgbox($erro_msg);' . "\n" );
		fputs ( $fd1, '    if($cl' . $nometab . '->erro_campo!=""){' . "\n" );
		fputs ( $fd1, '      echo "<script> document.form1.".$cl' . $nometab . '->erro_campo.".style.backgroundColor=\'#99A9AE\';</script>";' . "\n" );
		fputs ( $fd1, '      echo "<script> document.form1.".$cl' . $nometab . '->erro_campo.".focus();</script>";' . "\n" );
		fputs ( $fd1, '    };' . "\n" );
		fputs ( $fd1, '  }else{' . "\n" );
		fputs ( $fd1, '   db_msgbox($erro_msg);' . "\n" );
		if ($arr_arqnom [$u] == "incluir") {
			if ($numrowspk > 0) {
				$query = '';
				$virgula = "";
				$cont = '';
				for($p = 0; $p < $numrowspk; $p ++) {
					db_fieldsmemory ( $resultpk, $p );
					$query .= $virgula . "chavepesquisa=$" . trim ( $nomecam ) . "";
					$virgula = "&";
				}
			
			}
		 // aqui tem que mexer para arrumar bug	
			fputs ( $fd1, '   db_redireciona("' . $siglamod . "1_" . $nometab . '005.php?liberaaba=true&' . $query . '");' . "\n" );
		}
		if ($arr_arqnom [$u] == "excluir") {
			fputs ( $fd1, ' echo "' . "\n" );
			fputs ( $fd1, '  <script>' . "\n" );
			fputs ( $fd1, '    function js_db_tranca(){' . "\n" );
			fputs ( $fd1, '      parent.location.href=\'' . strtolower ( $siglamod . '1_' . $nometab ) . '003.php\';' . "\n" );
			
			fputs ( $fd1, '    }\n' . "\n" );
			fputs ( $fd1, '    js_db_tranca();' . "\n" );
			fputs ( $fd1, '  </script>\n' . "\n" );
			fputs ( $fd1, ' ";' . "\n" );
		
		}
		fputs ( $fd1, '  }' . "\n" );
		fputs ( $fd1, '}' . "\n" );
		
		///rotina usada somente na alteração e na exclusão 		
		if ($arr_arqnom [$u] == "alterar" || $arr_arqnom [$u] == "excluir") {
			$resultp = $cldb_sysprikey->sql_record ( $cldb_sysprikey->sql_query ( null, $codarq, null, "db_syscampo.nomecam as nomecam", "db_sysprikey.sequen" ) );
			db_fieldsmemory ( $resultp, 0 );
			
			fputs ( $fd1, 'if(isset($chavepesquisa)){' . "\n" );
			fputs ( $fd1, ' echo "' . "\n" );
			fputs ( $fd1, '  <script>' . "\n" );
			fputs ( $fd1, '      function js_db_libera(){' . "\n" );
			//rotina que direciona as abas  
			for($l = 0; $l < count ( $arr_nometabfilho ); $l ++) {
				$codarqfilho = $arr_codarqfilho [$l];
				$nometabfilho = $arr_nometabfilho [$l];
				
				$resultpkfilho = $cldb_sysprikey->sql_record ( $cldb_sysprikey->sql_query ( null, $codarqfilho, null, "db_syscampo.nomecam as nomecamfilho", "db_sysprikey.sequen" ) );
				db_fieldsmemory ( $resultpkfilho, 0 );
				
				fputs ( $fd1, '         parent.document.formaba.' . $nometabfilho . '.disabled=false;' . "\n" );
				fputs ( $fd1, '         top.corpo.iframe_' . $nometabfilho . '.location.href=\'' . strtolower ( $siglamod . '1_' . $nometabfilho ) . '001.php?' . ($arr_arqnom [$u] == "excluir" ? "db_opcaoal=33&" : "") . $nomecamfilho . '=".@$' . $nomecam . '."\';' . "\n" );
			}
			fputs ( $fd1, '     ";' . "\n" );
			
			//rotina que simula um clique na primeira  aba
			fputs ( $fd1, '         if(isset($liberaaba)){' . "\n" );
			fputs ( $fd1, '           echo "  parent.mo_camada(\'' . $arr_nometabfilho [0] . '\');";' . "\n" );
			fputs ( $fd1, '         }' . "\n" );
			
			fputs ( $fd1, ' echo"}\n' . "\n" );
			fputs ( $fd1, '    js_db_libera();' . "\n" );
			fputs ( $fd1, '  </script>\n' . "\n" );
			fputs ( $fd1, ' ";' . "\n" );
			fputs ( $fd1, '}' . "\n" );
			
			if ($arr_arqnom [$u] == "alterar" || $arr_arqnom [$u] == "excluir") {
				fputs ( $fd1, ' if($db_opcao==22||$db_opcao==33){' . "\n" );
				fputs ( $fd1, '    echo "<script>document.form1.pesquisar.click();</script>";' . "\n" );
				fputs ( $fd1, ' }' . "\n" );
			}
		}
		////////////////////////////
		//fim
		

		fputs ( $fd1, '?>' . "\n" );
		// fim dos java scripts
	}
	fclose ( $fd1 );
}
//final dos programas de manutenção  
/********************************************************************************************************************/
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//rotina que gera a manutenção das tabelas filhas
for($l = 0; $l < count ( $arr_nometabfilho ); $l ++) {
	$codarqfilho = $arr_codarqfilho [$l];
	$nometabfilho = $arr_nometabfilho [$l];
	
	// Tabelas
	$qr = "where nomearq = '$nometab'";
	$sql = "select a.codarq,a.nomearq,m.codmod,m.nomemod, a.rotulo
			   from db_sysmodulo m
			   inner join db_sysarqmod am
			   on am.codmod = m.codmod
			   inner join db_sysarquivo a
			   on a.codarq = am.codarq
			   $qr
			   order by codmod";
	$result = pg_exec ( $sql );
	$numrows = pg_numrows ( $result );
	
	$arq003 = $root . "/" . substr ( $siglamod, 0, 3 ) . "1_" . trim ( $nometabfilho ) . "001.php";
	if (file_exists ( $arq003 ) && ! is_writable ( $arq003 )) {
		?>
            <table width="100%">
	<tr>
		<td align="center">
		<h6>Sem permissão para gravar "<?=substr ( $siglamod, 0, 3 ) . "1_" . trim ( $nometab )?>002"</h6>
		</td>
	</tr>
</table>
</body>
</html>
<?
		exit ();
	}
	umask ( 74 );
	$fd2 = fopen ( strtolower ( $arq003 ), "w" );
	fputs ( $fd2, "<?\n" );
	
	$resultpk = $cldb_sysprikey->sql_record ( $cldb_sysprikey->sql_query ( null, $codarqfilho, null, "db_sysarquivo.nomearq,db_syscampo.nomecam,db_sysprikey.sequen", "db_sysprikey.sequen" ) );
	$numrowspk = $cldb_sysprikey->numrows;
	
	$resultcamp = $cldb_sysarqcamp->sql_record ( $cldb_sysarqcamp->sql_query ( $codarqfilho, null, null, "db_syscampo.*", "db_sysarqcamp.seqarq" ) );
	$numrowscamp = $cldb_sysarqcamp->numrows;
	
	if ($numrowscamp > 0) {
		fputs ( $fd2, 'require("libs/db_stdlib.php");' . "\n" );
		fputs ( $fd2, 'require("libs/db_conecta.php");' . "\n" );
		fputs ( $fd2, 'include("libs/db_sessoes.php");' . "\n" );
		fputs ( $fd2, 'include("libs/db_usuariosonline.php");' . "\n" );
		fputs ( $fd2, 'include("classes/db_' . $nometabfilho . '_classe.php");' . "\n" );
		fputs ( $fd2, 'include("classes/db_' . $nometab . '_classe.php");' . "\n" );
		fputs ( $fd2, 'include("dbforms/db_funcoes.php");' . "\n" );
		fputs ( $fd2, 'parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);' . "\n" );
		fputs ( $fd2, 'db_postmemory($HTTP_POST_VARS);' . "\n" );
		fputs ( $fd2, '$cl' . $nometabfilho . ' = new cl_' . $nometabfilho . ';' . "\n" );
		fputs ( $fd2, '$cl' . $nometab . ' = new cl_' . $nometab . ';' . "\n" );
		fputs ( $fd2, '$db_opcao = 22;' . "\n" );
		fputs ( $fd2, '$db_botao = false;' . "\n" );
		fputs ( $fd2, 'if(isset($alterar) || isset($excluir) || isset($incluir)){' . "\n" );
		fputs ( $fd2, '  $sqlerro = false;' . "\n" );
		fputs ( $fd2, '  /*' . "\n" );
		for($r = 0; $r < $numrowscamp; $r ++) {
			db_fieldsmemory ( $resultcamp, $r );
			fputs ( $fd2, '$cl' . $nometabfilho . '->' . $nomecam . ' = $' . $nomecam . ';' . "\n" );
		}
		fputs ( $fd2, '  */' . "\n" );
		fputs ( $fd2, '}' . "\n" );
		fputs ( $fd2, 'if(isset($incluir)){' . "\n" );
		fputs ( $fd2, '  if($sqlerro==false){' . "\n" );
		fputs ( $fd2, '    db_inicio_transacao();' . "\n" );
		fputs ( $fd2, '    $cl' . $nometabfilho . '->incluir(' );
		if ($numrowspk > 0) {
			$virgula = "";
			for($p = 0; $p < $numrowspk; $p ++) {
				db_fieldsmemory ( $resultpk, $p );
				fputs ( $fd2, $virgula . '$' . trim ( $nomecam ) );
				$virgula = ",";
			}
		}
		fputs ( $fd2, ');' . "\n" );
		fputs ( $fd2, '    $erro_msg = $cl' . $nometabfilho . '->erro_msg;' . "\n" );
		fputs ( $fd2, '    if($cl' . trim ( $nometabfilho ) . '->erro_status==0){' . "\n" );
		fputs ( $fd2, '      $sqlerro=true;' . "\n" );
		fputs ( $fd2, '    }' . "\n" );
		fputs ( $fd2, '    db_fim_transacao($sqlerro);' . "\n" );
		fputs ( $fd2, '  }' . "\n" );
		fputs ( $fd2, '}else if(isset($alterar)){' . "\n" );
		fputs ( $fd2, '  if($sqlerro==false){' . "\n" );
		fputs ( $fd2, '    db_inicio_transacao();' . "\n" );
		fputs ( $fd2, '    $cl' . trim ( $nometabfilho ) . '->alterar(' );
		if ($numrowspk > 0) {
			$virgula = "";
			for($p = 0; $p < $numrowspk; $p ++) {
				db_fieldsmemory ( $resultpk, $p );
				fputs ( $fd2, $virgula . '$' . trim ( $nomecam ) );
				$virgula = ",";
			}
		}
		fputs ( $fd2, ');' . "\n" );
		fputs ( $fd2, '    $erro_msg = $cl' . $nometabfilho . '->erro_msg;' . "\n" );
		fputs ( $fd2, '    if($cl' . $nometabfilho . '->erro_status==0){' . "\n" );
		fputs ( $fd2, '      $sqlerro=true;' . "\n" );
		fputs ( $fd2, '    }' . "\n" );
		fputs ( $fd2, '    db_fim_transacao($sqlerro);' . "\n" );
		fputs ( $fd2, '  }' . "\n" );
		fputs ( $fd2, '}else if(isset($excluir)){' . "\n" );
		fputs ( $fd2, '  if($sqlerro==false){' . "\n" );
		fputs ( $fd2, '    db_inicio_transacao();' . "\n" );
		fputs ( $fd2, '    $cl' . $nometabfilho . '->excluir(' );
		if ($numrowspk > 0) {
			$virgula = "";
			for($p = 0; $p < $numrowspk; $p ++) {
				db_fieldsmemory ( $resultpk, $p );
				fputs ( $fd2, $virgula . '$' . trim ( $nomecam ) );
				$virgula = ",";
			}
		}
		fputs ( $fd2, ');' . "\n" );
		fputs ( $fd2, '    $erro_msg = $cl' . $nometabfilho . '->erro_msg;' . "\n" );
		fputs ( $fd2, '    if($cl' . $nometabfilho . '->erro_status==0){' . "\n" );
		fputs ( $fd2, '      $sqlerro=true;' . "\n" );
		fputs ( $fd2, '    }' . "\n" );
		fputs ( $fd2, '    db_fim_transacao($sqlerro);' . "\n" );
		fputs ( $fd2, '  }' . "\n" );
		fputs ( $fd2, '}else if(isset($opcao)){' . "\n" );
		fputs ( $fd2, '   $result = $cl' . $nometabfilho . '->sql_record($cl' . $nometabfilho . '->sql_query(' );
		if ($numrowspk > 0) {
			$virgula = "";
			for($p = 0; $p < $numrowspk; $p ++) {
				db_fieldsmemory ( $resultpk, $p );
				fputs ( $fd2, $virgula . '$' . trim ( $nomecam ) );
				$virgula = ",";
			}
		}
		fputs ( $fd2, '));' . "\n" );
		fputs ( $fd2, '   if($result!=false && $cl' . $nometabfilho . '->numrows>0){' . "\n" );
		fputs ( $fd2, '     db_fieldsmemory($result,0);' . "\n" );
		fputs ( $fd2, '   }' . "\n" );
		fputs ( $fd2, '}' . "\n" );
		
		fputs ( $fd2, '?>' . "\n" );
		fputs ( $fd2, '<html>' . "\n" );
		fputs ( $fd2, '<head>' . "\n" );
		fputs ( $fd2, '<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>' . "\n" );
		fputs ( $fd2, '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">' . "\n" );
		fputs ( $fd2, '<meta http-equiv="Expires" CONTENT="0">' . "\n" );
		fputs ( $fd2, '<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>' . "\n" );
		fputs ( $fd2, '<link href="estilos.css" rel="stylesheet" type="text/css">' . "\n" );
		fputs ( $fd2, '</head>' . "\n" );
		fputs ( $fd2, '<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >' . "\n" );
		fputs ( $fd2, '<table width="790" border="0" cellspacing="0" cellpadding="0">' . "\n" );
		fputs ( $fd2, '  <tr> ' . "\n" );
		fputs ( $fd2, '    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> ' . "\n" );
		fputs ( $fd2, '    <center>' . "\n" );
		fputs ( $fd2, '	<?' . "\n" );
		fputs ( $fd2, '	include("forms/db_frm' . $nometabfilho . '.php");' . "\n" );
		fputs ( $fd2, '	?>' . "\n" );
		fputs ( $fd2, '    </center>' . "\n" );
		fputs ( $fd2, '	</td>' . "\n" );
		fputs ( $fd2, '  </tr>' . "\n" );
		fputs ( $fd2, '</table>' . "\n" );
		fputs ( $fd2, '</body>' . "\n" );
		fputs ( $fd2, '</html>' . "\n" );
		fputs ( $fd2, '<?' . "\n" );
		fputs ( $fd2, 'if(isset($alterar) || isset($excluir) || isset($incluir)){' . "\n" );
		fputs ( $fd2, '    db_msgbox($erro_msg);' . "\n" );
		fputs ( $fd2, '    if($cl' . $nometabfilho . '->erro_campo!=""){' . "\n" );
		fputs ( $fd2, '        echo "<script> document.form1.".$cl' . $nometabfilho . '->erro_campo.".style.backgroundColor=\'#99A9AE\';</script>";' . "\n" );
		fputs ( $fd2, '        echo "<script> document.form1.".$cl' . $nometabfilho . '->erro_campo.".focus();</script>";' . "\n" );
		fputs ( $fd2, '    }' . "\n" );
		fputs ( $fd2, '}' . "\n" );
		
		fputs ( $fd2, '?>' . "\n" );
	}
	fclose ( $fd2 );
}
//final	  
/**********************************************************************************************************************/

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//rotina que ira gera  o formulario da tabela filha
for($l = 0; $l < count ( $arr_nometabfilho ); $l ++) {
	$codarqfilho = $arr_codarqfilho [$l];
	$codarq = $codarqfilho;
	$nometabfilho = $arr_nometabfilho [$l];
	// Tabelas
	$root = substr ( $HTTP_SERVER_VARS ['SCRIPT_FILENAME'], 0, strrpos ( $HTTP_SERVER_VARS ['SCRIPT_FILENAME'], "/" ) );
	$arquivo = $root . "/forms/" . "db_frm" . trim ( $arr_nometabfilho [$l] ) . ".php";
	if (! is_writable ( $root . "/forms" )) {
		?>
<table width="100%">
	<tr>
		<td align="center">
		<h6>Sem permissão para gravar em "forms/" ou não existe.</h6>
		</td>
	</tr>
</table>
</body>
</html>
<?
		exit ();
	}
	
	if (file_exists ( $arquivo ) && ! is_writable ( $arquivo )) {
		?>
<table width="100%">
	<tr>
		<td align="center">
		<h6>Sem permissão para gravar "forms/db_frm<?=$nometab?>"</h6>
		</td>
	</tr>
</table>
</body>
</html>
<?
		exit ();
	}
	
	umask ( 74 );
	$fd = fopen ( strtolower ( $arquivo ), "w" );
	fputs ( $fd, "<?\n" );
	for($i = 0; $i < $numrows; $i ++) {
		$varpk = "";
		$pk = pg_exec ( "select a.nomearq,c.nomecam,p.sequen
			   from db_sysprikey p
				inner join db_sysarquivo a on a.codarq = p.codarq
				inner join db_syscampo c   on c.codcam = p.codcam
			   where a.codarq = " . $codarq );
		if (pg_numrows ( $pk ) > 0) {
			$Npk = pg_numrows ( $pk );
			$virgula = "";
			$virconc = "";
			for($p = 0; $p < $Npk; $p ++) {
				$varpk .= "##" . trim ( pg_result ( $pk, $p, "nomecam" ) );
			}
		}
		$campo = pg_exec ( "select c.*
			      from db_syscampo c
				   inner join db_sysarqcamp a   on a.codcam = c.codcam
			      where codarq = " . $codarq . " order by a.seqarq" );
		$Ncampos = pg_numrows ( $campo );
		if ($Ncampos > 0) {
			fputs ( $fd, "//MODULO: " . trim ( pg_result ( $result, $i, "nomemod" ) ) . "\n" );
			fputs ( $fd, "include(\"dbforms/db_classesgenericas.php\");\n" );
			fputs ( $fd, '$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;' . "\n" );
			fputs ( $fd, '$cl' . $nometabfilho . '->rotulo->label();' . "\n" );
			
			// testar se existe chaves estrangeiras deste arquivo
			$forkey = pg_exec ( "select distinct f.codcam,b.nomecam as nomecerto,f.referen, q.nomearq, c.camiden,x.nomecam as nomepri, a.nomecam, a.tamanho,f.tipoobjrel
			      from db_sysforkey f 
							   inner join db_sysprikey c on c.codarq = f.referen 
							   inner join db_syscampo a on a.codcam = c.camiden 
							   inner join db_syscampo x on x.codcam = c.codcam 
							   inner join db_syscampo b on b.codcam = f.codcam 
							   inner join db_sysarquivo q on q.codarq = f.referen 
			      where f.codarq = " . $codarq );
			$Nforkey = pg_numrows ( $forkey );
			$campofk = "";
			$campofktipo = "";
			if ($Nforkey > 0) {
				fputs ( $fd, '$clrotulo = new rotulocampo;' . "\n" );
				for($fk = 0; $fk < $Nforkey; $fk ++) {
					$campofk .= "#" . trim ( pg_result ( $forkey, $fk, 'codcam' ) );
					if (trim ( pg_result ( $forkey, $fk, 'tipoobjrel' ) == "1" )) {
						$campofktipo .= "#" . trim ( pg_result ( $forkey, $fk, 'codcam' ) );
					}
					fputs ( $fd, '$clrotulo->label("' . trim ( pg_result ( $forkey, $fk, 'nomecam' ) ) . '");' . "\n" );
				}
			}
			
			fputs ( $fd, 'if(isset($db_opcaoal)){' . "\n" );
			fputs ( $fd, '   $db_opcao=33;' . "\n" );
			fputs ( $fd, '    $db_botao=false;' . "\n" );
			fputs ( $fd, '}else if(isset($opcao) && $opcao=="alterar"){' . "\n" );
			fputs ( $fd, '    $db_botao=true;' . "\n" );
			fputs ( $fd, '    $db_opcao = 2;' . "\n" );
			fputs ( $fd, '}else if(isset($opcao) && $opcao=="excluir"){' . "\n" );
			fputs ( $fd, '    $db_opcao = 3;' . "\n" );
			fputs ( $fd, '    $db_botao=true;' . "\n" );
			fputs ( $fd, '}else{  ' . "\n" );
			fputs ( $fd, '    $db_opcao = 1;' . "\n" );
			fputs ( $fd, '    $db_botao=true;' . "\n" );
			fputs ( $fd, '    if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){' . "\n" );
			for($j = 0; $j < $Ncampos; $j ++) {
				if (trim ( pg_result ( $pk, 0, 'nomecam' ) ) != trim ( pg_result ( $campo, $j, "nomecam" ) )) {
					fputs ( $fd, '     $' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . ' = "";' . "\n" );
				}
			}
			fputs ( $fd, '   }' . "\n" );
			fputs ( $fd, '} ' . "\n" );
			
			fputs ( $fd, '?>' . "\n" );
			fputs ( $fd, '<form name="form1" method="post" action="">' . "\n" );
			fputs ( $fd, '<center>' . "\n" );
			fputs ( $fd, '<table border="0">' . "\n" );
			$gera_oid = false;
			for($j = 0; $j < $Ncampos; $j ++) {
				fputs ( $fd, '  <tr>' . "\n" );
				//coluna label
				fputs ( $fd, '    <td nowrap title="<?=@$T' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . '?>">' . "\n" );
				if ($varpk == "" && $gera_oid == false) {
					$gera_oid = true;
					fputs ( $fd, '    <input name="oid" type="hidden" value="<?=@$oid?>">' . "\n" );
				}
				$funcaojava = '""';
				if (strpos ( $campofk, trim ( pg_result ( $campo, $j, "codcam" ) ) ) > 0) {
					
					if (strpos ( $campofktipo, trim ( pg_result ( $campo, $j, "codcam" ) ) ) == 0) {
						fputs ( $fd, '       <?' . "\n" );
						$funcaojava = '"js_pesquisa' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . '(true);"';
						fputs ( $fd, '       db_ancora(@$L' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . ',' . $funcaojava . ',$db_opcao);' . "\n" );
						fputs ( $fd, '       ?>' . "\n" );
						$funcaojava = '" onchange=\'js_pesquisa' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . '(false);\'"';
					} else {
						fputs ( $fd, '       <?=@$L' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . '?>' . "\n" );
					}
				} else {
					fputs ( $fd, '       <?=@$L' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . '?>' . "\n" );
				}
				fputs ( $fd, '    </td>' . "\n" );
				fputs ( $fd, '    <td> ' . "\n" );
				//$x = pg_result($campo,$j,"tipo");
				$xc = pg_result ( $campo, $j, "conteudo" );
				
				// coloca select    
				if (strpos ( $campofktipo, trim ( pg_result ( $campo, $j, "codcam" ) ) ) > 0) {
					for($fk = 0; $fk < $Nforkey; $fk ++) {
						if (pg_result ( $campo, $j, "codcam" ) == pg_result ( $forkey, $fk, 'codcam' ) && pg_result ( $forkey, $fk, 'tipoobjrel' ) == 1) {
							fputs ( $fd, '       <?' . "\n" );
							fputs ( $fd, '       include("classes/db_' . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . '_classe.php");' . "\n" );
							fputs ( $fd, '       $cl' . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . ' = new cl_' . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . ';' . "\n" );
							fputs ( $fd, '       $result = $cl' . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . '->sql_record($cl' . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . '->sql_query(' );
							$virgulapk = "";
							for($pkk = 0; $pkk < pg_numrows ( $pk ); $pkk ++) {
								fputs ( $fd, $virgulapk . '""' );
								$virgulapk = ",";
							}
							if ($virgulapk == "") {
								fputs ( $fd, '""' );
							}
							fputs ( $fd, ',"",""));' . "\n" );
							fputs ( $fd, '       db_selectrecord("' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . '",$result,true,$db_opcao);' . "\n" );
							fputs ( $fd, '       ?>' . "\n" );
						
						}
					}
				
				} else {
					
					$verificadep = "select defcampo,defdescr
			      from db_syscampodef
			      where codcam = " . pg_result ( $campo, $j, "codcam" );
					$verres = pg_exec ( $verificadep );
					if ($verres == false || pg_numrows ( $verres ) == 0) {
						
						if (substr ( $xc, 0, 4 ) != "date") {
							if ((substr ( $xc, 0, 3 ) == "cha") || (substr ( $xc, 0, 3 ) == "var") || (substr ( $xc, 0, 3 ) == "flo")) {
								if (strpos ( "--" . $varpk, trim ( pg_result ( $campo, $j, "nomecam" ) ) ) != 0) {
									//chave primaria
									fputs ( $fd, "<?" . "\n" );
									fputs ( $fd, "db_input('" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "'" . ',' . trim ( pg_result ( $campo, $j, "tamanho" ) ) . ',$I' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . ",true,'text',3," . $funcaojava . ")" . "\n" );
									fputs ( $fd, "?>" . "\n" );
								} else {
									fputs ( $fd, "<?" . "\n" );
									fputs ( $fd, "db_input('" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "'" . ',' . trim ( pg_result ( $campo, $j, "tamanho" ) ) . ',$I' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . ",true,'text',$" . "db_opcao," . $funcaojava . ")" . "\n" );
									fputs ( $fd, "?>" . "\n" );
								}
							} else if (substr ( $xc, 0, 3 ) == "boo") {
								fputs ( $fd, "<?" . "\n" );
								fputs ( $fd, '$x = array("f"=>"NAO","t"=>"SIM");' . "\n" );
								fputs ( $fd, "db_select('" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "'," . '$x' . ",true,$" . "db_opcao," . $funcaojava . ");" . "\n" );
								fputs ( $fd, "?>" . "\n" );
							} else if (substr ( $xc, 0, 3 ) == "tex") {
								fputs ( $fd, "<?" . "\n" );
								fputs ( $fd, "db_textarea('" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "'" . ',0,0,$I' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . ",true,'text',$" . "db_opcao," . $funcaojava . ")" . "\n" );
								fputs ( $fd, "?>" . "\n" );
							} else {
								$result09 = $cldb_sysarqcamp->sql_record ( $cldb_sysarqcamp->sql_query_file ( null, pg_result ( $campo, $j, "codcam" ), null, "codsequencia" ) );
								$opcao = "$" . "db_opcao";
								if ($cldb_sysarqcamp->numrows > 0) {
									db_fieldsmemory ( $result09, 0 );
									if ($codsequencia != 0) {
										$opcao = '3';
									}
								}
								if (strpos ( "--" . $varpk, trim ( pg_result ( $campo, $j, "nomecam" ) ) ) != 0) {
									fputs ( $fd, "<?" . "\n" );
									if (strpos ( pg_result ( $campo, $j, "nomecam" ), "anousu" ) > 0) {
										fputs ( $fd, "$" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . " = db_getsession('DB_anousu');" . "\n" );
									}
									fputs ( $fd, "db_input('" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "'" . ',' . trim ( pg_result ( $campo, $j, "tamanho" ) ) . ',$I' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . ",true,'text'," . $opcao . "," . $funcaojava . ")" . "\n" );
									fputs ( $fd, "?>" . "\n" );
								} else {
									fputs ( $fd, "<?" . "\n" );
									if (strpos ( pg_result ( $campo, $j, "nomecam" ), "anousu" ) > 0) {
										fputs ( $fd, "$" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . " = db_getsession('DB_anousu');" . "\n" );
										fputs ( $fd, "db_input('" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "'" . ',' . trim ( pg_result ( $campo, $j, "tamanho" ) ) . ',$I' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . ",true,'text',3," . $funcaojava . ")" . "\n" );
									} else {
										fputs ( $fd, "db_input('" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "'" . ',' . trim ( pg_result ( $campo, $j, "tamanho" ) ) . ',$I' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . ",true,'text'," . $opcao . "," . $funcaojava . ")" . "\n" );
									}
									fputs ( $fd, "?>" . "\n" );
								}
							}
						} else {
							fputs ( $fd, "<?" . "\n" );
							fputs ( $fd, "db_inputdata('" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "',@$" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "_dia,@$" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "_mes,@$" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "_ano,true,'text',$" . "db_opcao," . $funcaojava . ")" . "\n" );
							fputs ( $fd, "?>" . "\n" );
						}
						if ($funcaojava != '""') {
							// strpos($campofk,pg_result($campo,$j,"codcam")) > 0 ){
							fputs ( $fd, '       <?' . "\n" );
							for($fk = 0; $fk < $Nforkey; $fk ++) {
								if (pg_result ( $forkey, $fk, 'codcam' ) == pg_result ( $campo, $j, "codcam" )) {
									fputs ( $fd, "db_input('" . trim ( pg_result ( $forkey, $fk, "nomecam" ) ) . "'" . ',' . trim ( pg_result ( $forkey, $fk, "tamanho" ) ) . ',$I' . trim ( pg_result ( $forkey, $fk, "nomecam" ) ) . ",true,'text',3,'')" . "\n" );
								}
							}
							fputs ( $fd, '       ?>' . "\n" );
						}
					
					} else {
						
						fputs ( $fd, "<?" . "\n" );
						fputs ( $fd, '$x = array(' );
						$virgula = "";
						for($ver = 0; $ver < pg_numrows ( $verres ); $ver ++) {
							
							fputs ( $fd, $virgula . "'" . pg_result ( $verres, $ver, 'defcampo' ) . "'=>'" . pg_result ( $verres, $ver, 'defdescr' ) . "'" );
							$virgula = ",";
						}
						fputs ( $fd, ");" . "\n" );
						fputs ( $fd, "db_select('" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "'," . '$x' . ",true,$" . "db_opcao," . $funcaojava . ");" . "\n" );
						fputs ( $fd, "?>" . "\n" );
					
					}
				
				}
				
				fputs ( $fd, '    </td>' . "\n" );
				fputs ( $fd, '  </tr>' . "\n" );
			}
			
			fputs ( $fd, '  </tr>' . "\n" );
			fputs ( $fd, '    <td colspan="2" align="center">' . "\n" );
			fputs ( $fd, ' <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >' . "\n" );
			fputs ( $fd, ' <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style=\'visibility:hidden;\'":"")?> >' . "\n" );
			fputs ( $fd, '    </td>' . "\n" );
			fputs ( $fd, '  </tr>' . "\n" );
			fputs ( $fd, '  </table>' . "\n" );
			
			fputs ( $fd, ' <table>' . "\n" );
			fputs ( $fd, '  <tr>' . "\n" );
			fputs ( $fd, '    <td valign="top"  align="center">  ' . "\n" );
			fputs ( $fd, '    <?' . "\n" );
			fputs ( $fd, '	 $chavepri= array(' );
			$vir = '';
			for($p = 0; $p < $Npk; $p ++) {
				fputs ( $fd, $vir . '"' . trim ( pg_result ( $pk, $p, 'nomecam' ) ) . '"=>@$' . trim ( pg_result ( $pk, $p, 'nomecam' ) ) );
				$vir = ',';
			}
			fputs ( $fd, ');' . "\n" );
			
			fputs ( $fd, '	 $cliframe_alterar_excluir->chavepri=$chavepri;' . "\n" );
			fputs ( $fd, '	 $cliframe_alterar_excluir->sql     = $cl' . $nometabfilho . '->sql_query_file($' . trim ( pg_result ( $pk, 0, 'nomecam' ) ) . ');' . "\n" );
			
			fputs ( $fd, '	 $cliframe_alterar_excluir->campos  ="' );
			$vir = '';
			for($r = 0; $r < pg_numrows ( $campo ); $r ++) {
				fputs ( $fd, $vir . trim ( pg_result ( $campo, $r, "nomecam" ) ) );
				$vir = ',';
			}
			fputs ( $fd, '";' . "\n" );
			fputs ( $fd, '	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";' . "\n" );
			fputs ( $fd, '	 $cliframe_alterar_excluir->iframe_height ="160";' . "\n" );
			fputs ( $fd, '	 $cliframe_alterar_excluir->iframe_width ="700";' . "\n" );
			fputs ( $fd, '	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);' . "\n" );
			fputs ( $fd, '    ?>' . "\n" );
			fputs ( $fd, '    </td>' . "\n" );
			fputs ( $fd, '   </tr>' . "\n" );
			fputs ( $fd, ' </table>' . "\n" );
			
			fputs ( $fd, '  </center>' . "\n" );
			fputs ( $fd, '</form>' . "\n" );
			//
			// escreve os java scripts para controle dos iframe
			fputs ( $fd, '<script>' . "\n" );
			
			fputs ( $fd, 'function js_cancelar(){' . "\n" );
			fputs ( $fd, '  var opcao = document.createElement("input");' . "\n" );
			fputs ( $fd, '  opcao.setAttribute("type","hidden");' . "\n" );
			fputs ( $fd, '  opcao.setAttribute("name","novo");' . "\n" );
			fputs ( $fd, '  opcao.setAttribute("value","true");' . "\n" );
			fputs ( $fd, '  document.form1.appendChild(opcao);' . "\n" );
			fputs ( $fd, '  document.form1.submit();' . "\n" );
			fputs ( $fd, '}' . "\n" );
			
			for($fk = 0; $fk < $Nforkey; $fk ++) {
				fputs ( $fd, 'function js_pesquisa' . trim ( pg_result ( $forkey, $fk, "nomecerto" ) ) . '(mostra){' . "\n" );
				fputs ( $fd, '  if(mostra==true){' . "\n" );
				fputs ( $fd, "    js_OpenJanelaIframe('top.corpo.iframe_" . $nometabfilho . "','db_iframe_" . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . "','func_" . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . ".php?funcao_js=parent.js_mostra" . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . "1|" . trim ( pg_result ( $forkey, $fk, 'nomepri' ) ) . "|" . trim ( pg_result ( $forkey, $fk, 'nomecam' ) ) . "','Pesquisa',true,'0','1','775','390');" . "\n" );
				fputs ( $fd, "  }else{" . "\n" );
				fputs ( $fd, "     if(document.form1." . trim ( pg_result ( $forkey, $fk, 'nomecerto' ) ) . ".value != ''){ " . "\n" );
				fputs ( $fd, "        js_OpenJanelaIframe('top.corpo.iframe_" . $nometabfilho . "','db_iframe_" . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . "','func_" . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . ".php?pesquisa_chave='+document.form1." . trim ( pg_result ( $forkey, $fk, 'nomecerto' ) ) . ".value+'&funcao_js=parent.js_mostra" . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . "','Pesquisa',false);" . "\n" );
				fputs ( $fd, "     }else{" . "\n" );
				fputs ( $fd, "       document.form1." . trim ( pg_result ( $forkey, $fk, 'nomecam' ) ) . ".value = ''; " . "\n" );
				fputs ( $fd, "     }" . "\n" );
				fputs ( $fd, "  }" . "\n" );
				fputs ( $fd, "}" . "\n" );
				fputs ( $fd, "function js_mostra" . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . "(chave,erro){" . "\n" );
				fputs ( $fd, "  document.form1." . trim ( pg_result ( $forkey, $fk, 'nomecam' ) ) . ".value = chave; " . "\n" );
				
				fputs ( $fd, "  if(erro==true){ " . "\n" );
				fputs ( $fd, "    document.form1." . trim ( pg_result ( $forkey, $fk, 'nomecerto' ) ) . ".focus(); " . "\n" );
				fputs ( $fd, "    document.form1." . trim ( pg_result ( $forkey, $fk, 'nomecerto' ) ) . ".value = ''; " . "\n" );
				fputs ( $fd, "  }" . "\n" );
				
				fputs ( $fd, "}" . "\n" );
				
				fputs ( $fd, "function js_mostra" . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . "1(chave1,chave2){" . "\n" );
				fputs ( $fd, "  document.form1." . trim ( pg_result ( $forkey, $fk, 'nomecerto' ) ) . ".value = chave1;" . "\n" );
				fputs ( $fd, "  document.form1." . trim ( pg_result ( $forkey, $fk, 'nomecam' ) ) . ".value = chave2;" . "\n" );
				fputs ( $fd, "  db_iframe_" . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . ".hide();" . "\n" );
				fputs ( $fd, "}" . "\n" );
			}
			fputs ( $fd, "</script>" . "\n" );
			// fim dos java scripts
		}
	}
	
	fclose ( $fd );
}
//final
/**********************************************************************************************************************/
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//rotina que ira gera a o formulario da tabela pai
$codarq = $codarqpai;
//  $nometab = $nometabfilho;


$sql = "select nomearq as nometab
          from db_sysarquivo
               where codarq = $codarq";
$resulta = pg_exec ( $sql );
db_fieldsmemory ( $resulta, 0 );

// Tabelas
$qr = "where nomearq = '$nometab'";
$sql = "select a.codarq,a.nomearq,m.codmod,m.nomemod, a.rotulo
                     from db_sysmodulo m
                     inner join db_sysarqmod am
                     on am.codmod = m.codmod
                     inner join db_sysarquivo a
                     on a.codarq = am.codarq
                     $qr
                     order by codmod";
$result = pg_exec ( $sql );
$numrows = pg_numrows ( $result );
$RecordsetTabMod = $result;
if ($numrows == 0) {
	echo "Não foi encontrada nenhum módulo com o nome de $nometab";
} else {
	$root = substr ( $HTTP_SERVER_VARS ['SCRIPT_FILENAME'], 0, strrpos ( $HTTP_SERVER_VARS ['SCRIPT_FILENAME'], "/" ) );
	$arquivo = $root . "/forms/" . "db_frm" . trim ( $nometab ) . ".php";
	//$arquivo = "/tmp/forms/"."db_frm".trim($nometab).".php";
	if (! is_writable ( $root . "/forms" )) {
		?>
<table width="100%">
	<tr>
		<td align="center">
		<h6>Sem permissão para gravar em "forms/" ou não existe.</h6>
		</td>
	</tr>
</table>
</body>
</html>
<?
		exit ();
	}
	
	if (file_exists ( $arquivo ) && ! is_writable ( $arquivo )) {
		?>
<table width="100%">
	<tr>
		<td align="center">
		<h6>Sem permissão para gravar "forms/db_frm<?=$nometab?>"</h6>
		</td>
	</tr>
</table>
</body>
</html>
<?
		exit ();
	}
	
	umask ( 74 );
	$fd = fopen ( strtolower ( $arquivo ), "w" );
	fputs ( $fd, "<?\n" );
	for($i = 0; $i < $numrows; $i ++) {
		$varpk = "";
		$pk = pg_exec ( "select a.nomearq,c.nomecam,p.sequen
                       from db_sysprikey p
                            inner join db_sysarquivo a on a.codarq = p.codarq
                            inner join db_syscampo c   on c.codcam = p.codcam
                       where a.codarq = " . pg_result ( $result, $i, "codarq" ) );
		if (pg_numrows ( $pk ) > 0) {
			$Npk = pg_numrows ( $pk );
			$virgula = "";
			$virconc = "";
			for($p = 0; $p < $Npk; $p ++) {
				$varpk .= "##" . trim ( pg_result ( $pk, $p, "nomecam" ) );
			}
		}
		$campo = pg_exec ( "select c.*
                          from db_syscampo c
                               inner join db_sysarqcamp a   on a.codcam = c.codcam
                          where codarq = " . pg_result ( $result, $i, "codarq" ) . " order by a.seqarq" );
		$Ncampos = pg_numrows ( $campo );
		if ($Ncampos > 0) {
			fputs ( $fd, "//MODULO: " . trim ( pg_result ( $result, $i, "nomemod" ) ) . "\n" );
			fputs ( $fd, '$cl' . trim ( pg_result ( $result, $i, "nomearq" ) ) . '->rotulo->label();' . "\n" );
			
			// testar se existe chaves estrangeiras deste arquivo
			$forkey = pg_exec ( "select distinct f.codcam,b.nomecam as nomecerto,f.referen, q.nomearq, c.camiden,x.nomecam as nomepri, a.nomecam, a.tamanho,f.tipoobjrel
                          from db_sysforkey f 
						       inner join db_sysprikey c on c.codarq = f.referen 
						       inner join db_syscampo a on a.codcam = c.camiden 
						       inner join db_syscampo x on x.codcam = c.codcam 
						       inner join db_syscampo b on b.codcam = f.codcam 
						       inner join db_sysarquivo q on q.codarq = f.referen 
                          where f.codarq = " . pg_result ( $result, $i, "codarq" ) );
			$Nforkey = pg_numrows ( $forkey );
			$campofk = "";
			$campofktipo = "";
			if ($Nforkey > 0) {
				fputs ( $fd, '$clrotulo = new rotulocampo;' . "\n" );
				for($fk = 0; $fk < $Nforkey; $fk ++) {
					$campofk .= "#" . trim ( pg_result ( $forkey, $fk, 'codcam' ) );
					if (trim ( pg_result ( $forkey, $fk, 'tipoobjrel' ) == "1" )) {
						$campofktipo .= "#" . trim ( pg_result ( $forkey, $fk, 'codcam' ) );
					}
					fputs ( $fd, '$clrotulo->label("' . trim ( pg_result ( $forkey, $fk, 'nomecam' ) ) . '");' . "\n" );
				}
			}
			fputs ( $fd, '      if($db_opcao==1){' . "\n" );
			fputs ( $fd, ' 	   $db_action="' . strtolower ( $siglamod . '1_' . $nometab ) . '004.php";' . "\n" );
			fputs ( $fd, '      }else if($db_opcao==2||$db_opcao==22){' . "\n" );
			fputs ( $fd, ' 	   $db_action="' . strtolower ( $siglamod . '1_' . $nometab ) . '005.php";' . "\n" );
			fputs ( $fd, '      }else if($db_opcao==3||$db_opcao==33){' . "\n" );
			fputs ( $fd, ' 	   $db_action="' . strtolower ( $siglamod . '1_' . $nometab ) . '006.php";' . "\n" );
			fputs ( $fd, '      }  ' . "\n" );
			fputs ( $fd, '?>' . "\n" );
			fputs ( $fd, '<form name="form1" method="post" action="<?=$db_action?>">' . "\n" );
			fputs ( $fd, '<center>' . "\n" );
			fputs ( $fd, '<table border="0">' . "\n" );
			$gera_oid = false;
			for($j = 0; $j < $Ncampos; $j ++) {
				fputs ( $fd, '  <tr>' . "\n" );
				//coluna label
				fputs ( $fd, '    <td nowrap title="<?=@$T' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . '?>">' . "\n" );
				if ($varpk == "" && $gera_oid == false) {
					$gera_oid = true;
					fputs ( $fd, '    <input name="oid" type="hidden" value="<?=@$oid?>">' . "\n" );
				}
				$funcaojava = '""';
				if (strpos ( $campofk, trim ( pg_result ( $campo, $j, "codcam" ) ) ) > 0) {
					
					if (strpos ( $campofktipo, trim ( pg_result ( $campo, $j, "codcam" ) ) ) == 0) {
						fputs ( $fd, '       <?' . "\n" );
						$funcaojava = '"js_pesquisa' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . '(true);"';
						fputs ( $fd, '       db_ancora(@$L' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . ',' . $funcaojava . ',$db_opcao);' . "\n" );
						fputs ( $fd, '       ?>' . "\n" );
						$funcaojava = '" onchange=\'js_pesquisa' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . '(false);\'"';
					} else {
						fputs ( $fd, '       <?=@$L' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . '?>' . "\n" );
					}
				} else {
					fputs ( $fd, '       <?=@$L' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . '?>' . "\n" );
				}
				fputs ( $fd, '    </td>' . "\n" );
				fputs ( $fd, '    <td> ' . "\n" );
				//$x = pg_result($campo,$j,"tipo");
				$xc = pg_result ( $campo, $j, "conteudo" );
				
				// coloca select    
				if (strpos ( $campofktipo, trim ( pg_result ( $campo, $j, "codcam" ) ) ) > 0) {
					for($fk = 0; $fk < $Nforkey; $fk ++) {
						if (pg_result ( $campo, $j, "codcam" ) == pg_result ( $forkey, $fk, 'codcam' ) && pg_result ( $forkey, $fk, 'tipoobjrel' ) == 1) {
							fputs ( $fd, '       <?' . "\n" );
							fputs ( $fd, '       include("classes/db_' . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . '_classe.php");' . "\n" );
							fputs ( $fd, '       $cl' . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . ' = new cl_' . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . ';' . "\n" );
							fputs ( $fd, '       $result = $cl' . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . '->sql_record($cl' . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . '->sql_query(' );
							$virgulapk = "";
							for($pkk = 0; $pkk < pg_numrows ( $pk ); $pkk ++) {
								fputs ( $fd, $virgulapk . '""' );
								$virgulapk = ",";
							}
							if ($virgulapk == "") {
								fputs ( $fd, '""' );
							}
							fputs ( $fd, ',"",""));' . "\n" );
							fputs ( $fd, '       db_selectrecord("' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . '",$result,true,$db_opcao);' . "\n" );
							fputs ( $fd, '       ?>' . "\n" );
						
						}
					}
				
				} else {
					
					$verificadep = "select defcampo,defdescr
	                  from db_syscampodef
			  where codcam = " . pg_result ( $campo, $j, "codcam" );
					$verres = pg_exec ( $verificadep );
					if ($verres == false || pg_numrows ( $verres ) == 0) {
						
						if (substr ( $xc, 0, 4 ) != "date") {
							if ((substr ( $xc, 0, 3 ) == "cha") || (substr ( $xc, 0, 3 ) == "var") || (substr ( $xc, 0, 3 ) == "flo")) {
								if (strpos ( "--" . $varpk, trim ( pg_result ( $campo, $j, "nomecam" ) ) ) != 0) {
									//chave primaria
									fputs ( $fd, "<?" . "\n" );
									fputs ( $fd, "db_input('" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "'" . ',' . trim ( pg_result ( $campo, $j, "tamanho" ) ) . ',$I' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . ",true,'text',3," . $funcaojava . ")" . "\n" );
									fputs ( $fd, "?>" . "\n" );
								} else {
									fputs ( $fd, "<?" . "\n" );
									fputs ( $fd, "db_input('" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "'" . ',' . trim ( pg_result ( $campo, $j, "tamanho" ) ) . ',$I' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . ",true,'text',$" . "db_opcao," . $funcaojava . ")" . "\n" );
									fputs ( $fd, "?>" . "\n" );
								}
							} else if (substr ( $xc, 0, 3 ) == "boo") {
								fputs ( $fd, "<?" . "\n" );
								fputs ( $fd, '$x = array("f"=>"NAO","t"=>"SIM");' . "\n" );
								fputs ( $fd, "db_select('" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "'," . '$x' . ",true,$" . "db_opcao," . $funcaojava . ");" . "\n" );
								fputs ( $fd, "?>" . "\n" );
							} else if (substr ( $xc, 0, 3 ) == "tex") {
								fputs ( $fd, "<?" . "\n" );
								fputs ( $fd, "db_textarea('" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "'" . ',0,0,$I' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . ",true,'text',$" . "db_opcao," . $funcaojava . ")" . "\n" );
								fputs ( $fd, "?>" . "\n" );
							} else {
								if (strpos ( "--" . $varpk, trim ( pg_result ( $campo, $j, "nomecam" ) ) ) != 0) {
									fputs ( $fd, "<?" . "\n" );
									if (strpos ( pg_result ( $campo, $j, "nomecam" ), "anousu" ) > 0) {
										fputs ( $fd, "$" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . " = db_getsession('DB_anousu');" . "\n" );
									}
									$result09 = $cldb_sysarqcamp->sql_record ( $cldb_sysarqcamp->sql_query_file ( null, pg_result ( $campo, $j, "codcam" ), null, "codsequencia" ) );
									$opcao = "$" . "db_opcao";
									if ($cldb_sysarqcamp->numrows > 0) {
										db_fieldsmemory ( $result09, 0 );
										if ($codsequencia != 0) {
											$opcao = '3';
										}
									}
									fputs ( $fd, "db_input('" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "'" . ',' . trim ( pg_result ( $campo, $j, "tamanho" ) ) . ',$I' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . ",true,'text'," . $opcao . "," . $funcaojava . ")" . "\n" );
									fputs ( $fd, "?>" . "\n" );
								} else {
									fputs ( $fd, "<?" . "\n" );
									if (strpos ( pg_result ( $campo, $j, "nomecam" ), "anousu" ) > 0) {
										fputs ( $fd, "$" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . " = db_getsession('DB_anousu');" . "\n" );
										fputs ( $fd, "db_input('" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "'" . ',' . trim ( pg_result ( $campo, $j, "tamanho" ) ) . ',$I' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . ",true,'text',3," . $funcaojava . ")" . "\n" );
									} else {
										fputs ( $fd, "db_input('" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "'" . ',' . trim ( pg_result ( $campo, $j, "tamanho" ) ) . ',$I' . trim ( pg_result ( $campo, $j, "nomecam" ) ) . ",true,'text',$" . "db_opcao," . $funcaojava . ")" . "\n" );
									}
									fputs ( $fd, "?>" . "\n" );
								}
							}
						} else {
							fputs ( $fd, "<?" . "\n" );
							fputs ( $fd, "db_inputdata('" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "',@$" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "_dia,@$" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "_mes,@$" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "_ano,true,'text',$" . "db_opcao," . $funcaojava . ")" . "\n" );
							fputs ( $fd, "?>" . "\n" );
						}
						if ($funcaojava != '""') {
							// strpos($campofk,pg_result($campo,$j,"codcam")) > 0 ){
							fputs ( $fd, '       <?' . "\n" );
							for($fk = 0; $fk < $Nforkey; $fk ++) {
								if (pg_result ( $forkey, $fk, 'codcam' ) == pg_result ( $campo, $j, "codcam" )) {
									fputs ( $fd, "db_input('" . trim ( pg_result ( $forkey, $fk, "nomecam" ) ) . "'" . ',' . trim ( pg_result ( $forkey, $fk, "tamanho" ) ) . ',$I' . trim ( pg_result ( $forkey, $fk, "nomecam" ) ) . ",true,'text',3,'')" . "\n" );
								}
							}
							fputs ( $fd, '       ?>' . "\n" );
						}
					
					} else {
						
						fputs ( $fd, "<?" . "\n" );
						fputs ( $fd, '$x = array(' );
						$virgula = "";
						for($ver = 0; $ver < pg_numrows ( $verres ); $ver ++) {
							
							fputs ( $fd, $virgula . "'" . pg_result ( $verres, $ver, 'defcampo' ) . "'=>'" . pg_result ( $verres, $ver, 'defdescr' ) . "'" );
							$virgula = ",";
						}
						fputs ( $fd, ");" . "\n" );
						fputs ( $fd, "db_select('" . trim ( pg_result ( $campo, $j, "nomecam" ) ) . "'," . '$x' . ",true,$" . "db_opcao," . $funcaojava . ");" . "\n" );
						fputs ( $fd, "?>" . "\n" );
					
					}
				
				}
				
				fputs ( $fd, '    </td>' . "\n" );
				fputs ( $fd, '  </tr>' . "\n" );
			}
			fputs ( $fd, '  </table>' . "\n" );
			fputs ( $fd, '  </center>' . "\n" );
			fputs ( $fd, '<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >' . "\n" );
			fputs ( $fd, '<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >' . "\n" );
			fputs ( $fd, '</form>' . "\n" );
			//
			// escreve os java scripts para controle dos iframe
			fputs ( $fd, '<script>' . "\n" );
			for($fk = 0; $fk < $Nforkey; $fk ++) {
				fputs ( $fd, 'function js_pesquisa' . trim ( pg_result ( $forkey, $fk, "nomecerto" ) ) . '(mostra){' . "\n" );
				fputs ( $fd, '  if(mostra==true){' . "\n" );
				//fputs($fd,"    db_iframe.jan.location.href = 'func_".trim(pg_result($forkey,$fk,'nomearq')).".php?funcao_js=parent.js_mostra".trim(pg_result($forkey,$fk,'nomearq'))."1|0|1';"."\n");
				

				fputs ( $fd, "    js_OpenJanelaIframe('top.corpo.iframe_" . $nometab . "','db_iframe_" . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . "','func_" . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . ".php?funcao_js=parent.js_mostra" . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . "1|" . trim ( pg_result ( $forkey, $fk, 'nomepri' ) ) . "|" . trim ( pg_result ( $forkey, $fk, 'nomecam' ) ) . "','Pesquisa',true,'0','1','775','390');" . "\n" );
				
				//fputs($fd,"    db_iframe.mostraMsg();"."\n");
				//fputs($fd,"    db_iframe.show();"."\n");
				//fputs($fd,"    db_iframe.focus();"."\n"); 
				fputs ( $fd, "  }else{" . "\n" );
				fputs ( $fd, "     if(document.form1." . trim ( pg_result ( $forkey, $fk, 'nomecerto' ) ) . ".value != ''){ " . "\n" );
				fputs ( $fd, "        js_OpenJanelaIframe('top.corpo.iframe_" . $nometab . "','db_iframe_" . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . "','func_" . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . ".php?pesquisa_chave='+document.form1." . trim ( pg_result ( $forkey, $fk, 'nomecerto' ) ) . ".value+'&funcao_js=parent.js_mostra" . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . "','Pesquisa',false,'0','1','775','390');" . "\n" );
				fputs ( $fd, "     }else{" . "\n" );
				fputs ( $fd, "       document.form1." . trim ( pg_result ( $forkey, $fk, 'nomecam' ) ) . ".value = ''; " . "\n" );
				fputs ( $fd, "     }" . "\n" );
				//fputs($fd,"    db_iframe.jan.location.href = 'func_".trim(pg_result($forkey,$fk,'nomearq')).".php?pesquisa_chave='+document.form1.".trim(pg_result($forkey,$fk,'nomecerto')).".value+'&funcao_js=parent.js_mostra".trim(pg_result($forkey,$fk,'nomearq'))."';"."\n");
				fputs ( $fd, "  }" . "\n" );
				fputs ( $fd, "}" . "\n" );
				fputs ( $fd, "function js_mostra" . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . "(chave,erro){" . "\n" );
				fputs ( $fd, "  document.form1." . trim ( pg_result ( $forkey, $fk, 'nomecam' ) ) . ".value = chave; " . "\n" );
				
				fputs ( $fd, "  if(erro==true){ " . "\n" );
				fputs ( $fd, "    document.form1." . trim ( pg_result ( $forkey, $fk, 'nomecerto' ) ) . ".focus(); " . "\n" );
				fputs ( $fd, "    document.form1." . trim ( pg_result ( $forkey, $fk, 'nomecerto' ) ) . ".value = ''; " . "\n" );
				fputs ( $fd, "  }" . "\n" );
				
				fputs ( $fd, "}" . "\n" );
				
				fputs ( $fd, "function js_mostra" . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . "1(chave1,chave2){" . "\n" );
				fputs ( $fd, "  document.form1." . trim ( pg_result ( $forkey, $fk, 'nomecerto' ) ) . ".value = chave1;" . "\n" );
				fputs ( $fd, "  document.form1." . trim ( pg_result ( $forkey, $fk, 'nomecam' ) ) . ".value = chave2;" . "\n" );
				fputs ( $fd, "  db_iframe_" . trim ( pg_result ( $forkey, $fk, 'nomearq' ) ) . ".hide();" . "\n" );
				fputs ( $fd, "}" . "\n" );
			}
			fputs ( $fd, "function js_pesquisa(){" . "\n" );
			
			//fputs($fd,"  db_iframe.jan.location.href = 'func_".trim(pg_result($result,$i,'nomearq')).".php?funcao_js=parent.js_preenchepesquisa|0");
			

			if (pg_numrows ( $pk ) > 0) {
				
				fputs ( $fd, "  js_OpenJanelaIframe('top.corpo.iframe_" . $nometab . "','db_iframe_" . trim ( pg_result ( $result, $i, 'nomearq' ) ) . "','func_" . trim ( pg_result ( $result, $i, 'nomearq' ) ) . ".php?funcao_js=parent.js_preenchepesquisa|" . trim ( pg_result ( $pk, 0, 'nomecam' ) ) );
				
				$Npk = pg_numrows ( $pk );
				$virgula = "";
				$virconc = "";
				for($p = 1; $p < $Npk; $p ++) {
					fputs ( $fd, "|" . trim ( pg_result ( $pk, $p, 'nomecam' ) ) );
				}
			} else {
				fputs ( $fd, "  js_OpenJanelaIframe('top.corpo.iframe_" . $nometab . "','db_iframe_" . trim ( pg_result ( $result, $i, 'nomearq' ) ) . "','func_" . trim ( pg_result ( $result, $i, 'nomearq' ) ) . ".php?funcao_js=parent.js_preenchepesquisa|0" );
			
			}
			
			//fputs($fd,"';"."\n");
			fputs ( $fd, "','Pesquisa',true,'0','1','775','390');" . "\n" );
			
			//fputs($fd,"  db_iframe.mostraMsg();"."\n");
			//fputs($fd,"  db_iframe.show();"."\n");
			//fputs($fd,"  db_iframe.focus();"."\n");
			fputs ( $fd, "}" . "\n" );
			fputs ( $fd, "function js_preenchepesquisa(chave" );
			
			if (pg_numrows ( $pk ) > 1) {
				$Npk = pg_numrows ( $pk );
				$virgula = "";
				$virconc = "";
				for($p = 1; $p < $Npk; $p ++) {
					fputs ( $fd, ",chave" . $p );
				}
			}
			
			fputs ( $fd, "){" . "\n" );
			fputs ( $fd, "  db_iframe_" . trim ( pg_result ( $result, $i, 'nomearq' ) ) . ".hide();" . "\n" );
			fputs ( $fd, '  <?' . "\n" );
			fputs ( $fd, '  if($db_opcao!=1){' . "\n" );
			fputs ( $fd, "    echo \" location.href = '\".basename($" . "GLOBALS[\"HTTP_SERVER_VARS\"][\"PHP_SELF\"]).\"?chavepesquisa='+chave" );
			if (pg_numrows ( $pk ) > 1) {
				$Npk = pg_numrows ( $pk );
				$virgula = "";
				$virconc = "";
				for($p = 1; $p < $Npk; $p ++) {
					fputs ( $fd, "+'&chavepesquisa" . $p . "='+chave" . $p );
				}
			}
			
			fputs ( $fd, "\";" . "\n" );
			fputs ( $fd, "  }" . "\n" );
			fputs ( $fd, '  ?>' . "\n" );
			
			//	fputs($fd,';'."\n");
			fputs ( $fd, "}" . "\n" );
			fputs ( $fd, "</script>" . "\n" );
			// fim dos java scripts
		}
	}
}
fclose ( $fd );
?>
<table width="100%">
	<tr>
		<td align="center">
		<h3>Concluído...</h3>
		</td>
	</tr>
</table>
</body>
</html>