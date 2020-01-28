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
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
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
<?
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  // Tabelas

  $sql = "select nomearq as nometab, tipotabela
          from db_sysarquivo
          where codarq = $codarq";
  $resulta = pg_exec($sql);
  db_fieldsmemory($resulta,0);
		      
  if($tipotabela=='2'){
    ?>
    <table width="100%"><tr><td align="center"><h3>Tabela Dependente. Sem programas.</h3></td></tr></table>
    <?
  }else{

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
     $result = pg_exec($sql);
     $numrows = pg_numrows($result);
     $RecordsetTabMod = $result;
     if($numrows == 0) {
       echo "Não foi encontrada nenhuma tabela com o nome de $nometab";
     } else {

       $root = substr($HTTP_SERVER_VARS['SCRIPT_FILENAME'],0,strrpos($HTTP_SERVER_VARS['SCRIPT_FILENAME'],"/"));
       $siglamod = pg_result($result,0,'nomemod');
     
       if($tipotabela=='0'){
    
	  $arq001 = $root."/".strtolower(substr($siglamod,0,3)."1_".trim($nometab))."001.php";
	  //$arq001 = "/tmp/".substr($siglamod,0,3)."1_".trim($nometab)."001.php";
          if(file_exists($arq001) && !is_writable($arq001)){
            ?>
            <table width="100%"><tr><td align="center"><h6>Sem permissão para gravar "<?=substr($siglamod,0,3)."1_".trim($nometab)?>001"</h6></td></tr></table>
            </body>
            </html>
            <?
            exit;
          } 
	  umask(74); 
	  $fd1 = fopen($arq001,"w");
	  fputs($fd1,"<?\n");
	  for($i = 0;$i < $numrows;$i++) {
	    $varpk = ""; 
	    $pk = pg_exec("select a.nomearq,c.nomecam,p.sequen
			     from db_sysprikey p
				  inner join db_sysarquivo a on a.codarq = p.codarq
				  inner join db_syscampo c   on c.codcam = p.codcam
			     where a.codarq = ".pg_result($result,$i,"codarq")."
			     order by p.sequen");
	    $campo = pg_exec("select c.*
				from db_syscampo c
				     inner join db_sysarqcamp a   on a.codcam = c.codcam
				where codarq = ".pg_result($result,$i,"codarq").
					    " order by a.seqarq");
	    $Ncampos = pg_numrows($campo);
	    if($Ncampos > 0) {
	      fputs($fd1,'require("libs/db_stdlib.php");'."\n");
	      fputs($fd1,'require("libs/db_conecta.php");'."\n");
	      fputs($fd1,'include("libs/db_sessoes.php");'."\n");
	      fputs($fd1,'include("libs/db_usuariosonline.php");'."\n");
	      fputs($fd1,'include("classes/db_'.trim(pg_result($result,$i,"nomearq")).'_classe.php");'."\n");
	      fputs($fd1,'include("dbforms/db_funcoes.php");'."\n");
	      fputs($fd1,'db_postmemory($HTTP_POST_VARS);'."\n");
	      fputs($fd1,'$cl'.trim(pg_result($result,$i,"nomearq")).' = new cl_'.trim(pg_result($result,$i,"nomearq")).';'."\n");
	      fputs($fd1,'$db_opcao = 1;'."\n");
	      fputs($fd1,'$db_botao = true;'."\n");
	      fputs($fd1,'if(isset($incluir)){'."\n");
	      fputs($fd1,'  db_inicio_transacao();'."\n");
	      fputs($fd1,'  $cl'.trim(pg_result($result,$i,"nomearq")).'->incluir(');

	      if(pg_numrows($pk) > 0) {
		    $Npk = pg_numrows($pk);
			$virgula = "";
		    for($p = 0;$p < $Npk;$p++) {
		      fputs($fd1,$virgula.'$'.trim(pg_result($pk,$p,"nomecam")));
			  $virgula = ",";
	        } 
	      }
	      fputs($fd1,');'."\n");
	      fputs($fd1,'  db_fim_transacao();'."\n");
	      fputs($fd1,'}'."\n");
	      fputs($fd1,'?>'."\n");
	      fputs($fd1,'<html>'."\n");
	      fputs($fd1,'<head>'."\n");
	      fputs($fd1,'<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>'."\n");
	      fputs($fd1,'<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">'."\n");
	      fputs($fd1,'<meta http-equiv="Expires" CONTENT="0">'."\n");
	      fputs($fd1,'<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>'."\n");
	      fputs($fd1,'<link href="estilos.css" rel="stylesheet" type="text/css">'."\n");
	      fputs($fd1,'</head>'."\n");
	      fputs($fd1,'<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >'."\n");
	      fputs($fd1,'<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">'."\n");
	      fputs($fd1,'  <tr> '."\n");
	      fputs($fd1,'    <td width="360" height="18">&nbsp;</td>'."\n");
	      fputs($fd1,'    <td width="263">&nbsp;</td>'."\n");
	      fputs($fd1,'    <td width="25">&nbsp;</td>'."\n");
	      fputs($fd1,'    <td width="140">&nbsp;</td>'."\n");
	      fputs($fd1,'  </tr>'."\n");
	      fputs($fd1,'</table>'."\n");
	      fputs($fd1,'<table width="790" border="0" cellspacing="0" cellpadding="0">'."\n");
	      fputs($fd1,'  <tr> '."\n");
	      fputs($fd1,'    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> '."\n");
	      fputs($fd1,'    <center>'."\n");
	      fputs($fd1,'	<?'."\n");
	      fputs($fd1,'	include("forms/db_frm'.trim(pg_result($result,$i,"nomearq")).'.php");'."\n");
	      fputs($fd1,'	?>'."\n");
	      fputs($fd1,'    </center>'."\n");
	      fputs($fd1,'	</td>'."\n");
	      fputs($fd1,'  </tr>'."\n");
	      fputs($fd1,'</table>'."\n");
	      fputs($fd1,'<?'."\n");
	      fputs($fd1,'db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));'."\n");
	      fputs($fd1,'?>'."\n");
	      fputs($fd1,'</body>'."\n");
          fputs($fd1,'</html>'."\n");
          
          fputs($fd1,'<script>'."\n");
	      fputs($fd1,'js_tabulacaoforms("form1","'.trim(pg_result($campo,1,1)).'",true,1,"'.trim(pg_result($campo,1,1)).'",true);'."\n");
	      fputs($fd1,'</script>'."\n");
          
	      fputs($fd1,'<?'."\n");

      //        fputs($fd1,'if($cl'.trim(pg_result($result,$i,"nomearq")).'->erro_status=="0"){'."\n");
      //        fputs($fd1,'  $db_botao=true;'."\n");
      //        fputs($fd1,'  $cl'.trim(pg_result($result,$i,"nomearq")).'->erro(true,false);'."\n");
      //        fputs($fd1,'}else{'."\n");
      //        fputs($fd1,'  $cl'.trim(pg_result($result,$i,"nomearq")).'->erro(true,true);'."\n");
      //        fputs($fd1,'}'."\n");




	      fputs($fd1,'if(isset($incluir)){'."\n");
	      fputs($fd1,'  if($cl'.trim(pg_result($result,$i,"nomearq")).'->erro_status=="0"){'."\n");
	      fputs($fd1,'    $cl'.trim(pg_result($result,$i,"nomearq")).'->erro(true,false);'."\n");
	      fputs($fd1,'    $db_botao=true;'."\n");
	      fputs($fd1,'    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";'."\n");
	      fputs($fd1,'    if($cl'.trim(pg_result($result,$i,"nomearq")).'->erro_campo!=""){'."\n");
	      fputs($fd1,'      echo "<script> document.form1.".$cl'.trim(pg_result($result,$i,"nomearq")).'->erro_campo.".style.backgroundColor=\'#99A9AE\';</script>";'."\n");
	      fputs($fd1,'      echo "<script> document.form1.".$cl'.trim(pg_result($result,$i,"nomearq")).'->erro_campo.".focus();</script>";'."\n");
	      fputs($fd1,'    }'."\n");
	      fputs($fd1,'  }else{'."\n");
	      fputs($fd1,'    $cl'.trim(pg_result($result,$i,"nomearq")).'->erro(true,true);'."\n");
	      fputs($fd1,'  }'."\n");
	      fputs($fd1,'}'."\n");
	      fputs($fd1,'?>'."\n");
		      // fim dos java scripts
	    }
	  }
	  fclose($fd1);  
       }
       if($tipotabela=='0' ){
	  $arq002 = $root."/".strtolower(substr($siglamod,0,3)."1_".trim($nometab))."002.php";
	  //$arq002 = "/tmp/".substr($siglamod,0,3)."1_".trim($nometab)."002.php";
	  if(file_exists($arq002) && !is_writable($arq002)){
            ?>
            <table width="100%"><tr><td align="center"><h6>Sem permissão para gravar "<?=substr($siglamod,0,3)."1_".trim($nometab)?>002"</h6></td></tr></table>
            </body>
            </html>
            <?
            exit;
          } 
	  umask(74); 
	  $fd2 = fopen($arq002,"w");

	  fputs($fd2,"<?\n");
	  for($i = 0;$i < $numrows;$i++) {
	    $varpk = ""; 
	    $pk = pg_exec("select a.nomearq,c.nomecam,p.sequen
			     from db_sysprikey p
				  inner join db_sysarquivo a on a.codarq = p.codarq
				  inner join db_syscampo c   on c.codcam = p.codcam
			     where a.codarq = ".pg_result($result,$i,"codarq")."
			     order by p.sequen");
	    $campo = pg_exec("select c.*
				from db_syscampo c
				     inner join db_sysarqcamp a   on a.codcam = c.codcam
				where codarq = ".pg_result($result,$i,"codarq").
					    " order by a.seqarq");
	    $Ncampos = pg_numrows($campo);
	    if($Ncampos > 0) {
	      fputs($fd2,'require("libs/db_stdlib.php");'."\n");
	      fputs($fd2,'require("libs/db_conecta.php");'."\n");
	      fputs($fd2,'include("libs/db_sessoes.php");'."\n");
	      fputs($fd2,'include("libs/db_usuariosonline.php");'."\n");
	      fputs($fd2,'include("classes/db_'.trim(pg_result($result,$i,"nomearq")).'_classe.php");'."\n");
	      fputs($fd2,'include("dbforms/db_funcoes.php");'."\n");
	      fputs($fd2,'parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);'."\n");
	      fputs($fd2,'db_postmemory($HTTP_POST_VARS);'."\n");
	      fputs($fd2,'$cl'.trim(pg_result($result,$i,"nomearq")).' = new cl_'.trim(pg_result($result,$i,"nomearq")).';'."\n");
	      fputs($fd2,'$db_opcao = 22;'."\n");
	      fputs($fd2,'$db_botao = false;'."\n");
	      fputs($fd2,'if(isset($alterar)){'."\n");
	      fputs($fd2,'  db_inicio_transacao();'."\n");
	      fputs($fd2,'  $db_opcao = 2;'."\n");
	      fputs($fd2,'  $cl'.trim(pg_result($result,$i,"nomearq")).'->alterar(');

	      if(pg_numrows($pk) > 0) {
		$Npk = pg_numrows($pk);
			$virgula = "";
		for($p = 0;$p < $Npk;$p++) {
		  fputs($fd2,$virgula.'$'.trim(pg_result($pk,$p,"nomecam")));
			  $virgula = ",";
		} 
	      }else{
		 fputs($fd2,'$oid');
	      }
	      fputs($fd2,');'."\n");
	      fputs($fd2,'  db_fim_transacao();'."\n");
	      fputs($fd2,'}else if(isset($chavepesquisa)){'."\n");

	      fputs($fd2,'   $db_opcao = 2;'."\n");
	      fputs($fd2,'   $result = $cl'.trim(pg_result($result,$i,"nomearq")).'->sql_record($cl'.trim(pg_result($result,$i,"nomearq")).'->sql_query($chavepesquisa');
		      
	      if(pg_numrows($pk) > 1) {
		$Npk = pg_numrows($pk);
			$virgula = "";
		for($p = 1;$p < $Npk;$p++) {
		  fputs($fd2,',$chavepesquisa'.$p);
			  $virgula = ",";
		} 
	      }

		      fputs($fd2,')); '."\n");
	      fputs($fd2,'   db_fieldsmemory($result,0);'."\n");
	      fputs($fd2,'   $db_botao = true;'."\n");
	      fputs($fd2,'}'."\n");

	      fputs($fd2,'?>'."\n");
	      fputs($fd2,'<html>'."\n");
	      fputs($fd2,'<head>'."\n");
	      fputs($fd2,'<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>'."\n");
	      fputs($fd2,'<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">'."\n");
	      fputs($fd2,'<meta http-equiv="Expires" CONTENT="0">'."\n");
	      fputs($fd2,'<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>'."\n");
	      fputs($fd2,'<link href="estilos.css" rel="stylesheet" type="text/css">'."\n");
	      fputs($fd2,'</head>'."\n");
	      fputs($fd2,'<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >'."\n");
	      fputs($fd2,'<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">'."\n");
	      fputs($fd2,'  <tr> '."\n");
	      fputs($fd2,'    <td width="360" height="18">&nbsp;</td>'."\n");
	      fputs($fd2,'    <td width="263">&nbsp;</td>'."\n");
	      fputs($fd2,'    <td width="25">&nbsp;</td>'."\n");
	      fputs($fd2,'    <td width="140">&nbsp;</td>'."\n");
	      fputs($fd2,'  </tr>'."\n");
	      fputs($fd2,'</table>'."\n");
	      fputs($fd2,'<table width="790" border="0" cellspacing="0" cellpadding="0">'."\n");
	      fputs($fd2,'  <tr> '."\n");
	      fputs($fd2,'    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> '."\n");
	      fputs($fd2,'    <center>'."\n");
	      fputs($fd2,'	<?'."\n");
	      fputs($fd2,'	include("forms/db_frm'.trim(pg_result($result,$i,"nomearq")).'.php");'."\n");
	      fputs($fd2,'	?>'."\n");
	      fputs($fd2,'    </center>'."\n");
	      fputs($fd2,'	</td>'."\n");
	      fputs($fd2,'  </tr>'."\n");
	      fputs($fd2,'</table>'."\n");
	      fputs($fd2,'<?'."\n");
	      fputs($fd2,'db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));'."\n");
	      fputs($fd2,'?>'."\n");
	      fputs($fd2,'</body>'."\n");
	      fputs($fd2,'</html>'."\n");
	      fputs($fd2,'<?'."\n");
		      
	      fputs($fd2,'if(isset($alterar)){'."\n");
	      fputs($fd2,'  if($cl'.trim(pg_result($result,$i,"nomearq")).'->erro_status=="0"){'."\n");
	      fputs($fd2,'    $cl'.trim(pg_result($result,$i,"nomearq")).'->erro(true,false);'."\n");
	      fputs($fd2,'    $db_botao=true;'."\n");
	      fputs($fd2,'    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";'."\n");
	      fputs($fd2,'    if($cl'.trim(pg_result($result,$i,"nomearq")).'->erro_campo!=""){'."\n");
	      fputs($fd2,'      echo "<script> document.form1.".$cl'.trim(pg_result($result,$i,"nomearq")).'->erro_campo.".style.backgroundColor=\'#99A9AE\';</script>";'."\n");
	      fputs($fd2,'      echo "<script> document.form1.".$cl'.trim(pg_result($result,$i,"nomearq")).'->erro_campo.".focus();</script>";'."\n");
	      fputs($fd2,'    }'."\n");
	      fputs($fd2,'  }else{'."\n");
	      fputs($fd2,'    $cl'.trim(pg_result($result,$i,"nomearq")).'->erro(true,true);'."\n");
	      fputs($fd2,'  }'."\n");
	      fputs($fd2,'}'."\n");
	      fputs($fd2,'if($db_opcao==22){'."\n");
	      fputs($fd2,'  echo "<script>document.form1.pesquisar.click();</script>";'."\n");
	      fputs($fd2,'}'."\n");
	      fputs($fd2,'?>'."\n");
	      
	      fputs($fd2,'<script>'."\n");
          fputs($fd2,'js_tabulacaoforms("form1","'.trim(pg_result($campo,1,1)).'",true,1,"'.trim(pg_result($campo,1,1)).'",true);'."\n");
	      fputs($fd2,'</script>'."\n");
	      
   	    }
	  }
	  fclose($fd2);  
       }
       if($tipotabela=='0'){
	  $arq003 = $root."/".strtolower(substr($siglamod,0,3)."1_".trim($nometab)."003.php");
	  //$arq003 = "/tmp/".substr($siglamod,0,3)."1_".trim($nometab)."003.php";
	  if(file_exists($arq003) && !is_writable($arq003)){
            ?>
            <table width="100%"><tr><td align="center"><h6>Sem permissão para gravar "<?=substr($siglamod,0,3)."1_".trim($nometab)?>003"</h6></td></tr></table>
            </body>
            </html>
            <?
            exit;
          }
	  umask(74);
	  $fd3 = fopen($arq003,"w");
	  fputs($fd3,"<?\n");
	  for($i = 0;$i < $numrows;$i++) {
	    $varpk = ""; 
	    $pk = pg_exec("select a.nomearq,c.nomecam,p.sequen
			     from db_sysprikey p
				  inner join db_sysarquivo a on a.codarq = p.codarq
				  inner join db_syscampo c   on c.codcam = p.codcam
			     where a.codarq = ".pg_result($result,$i,"codarq")."
			     order by p.sequen");
	    $campo = pg_exec("select c.*
				from db_syscampo c
				     inner join db_sysarqcamp a   on a.codcam = c.codcam
				where codarq = ".pg_result($result,$i,"codarq").
					    " order by a.seqarq");
	    $Ncampos = pg_numrows($campo);
            if($Ncampos > 0) {
	      fputs($fd3,'require("libs/db_stdlib.php");'."\n");
	      fputs($fd3,'require("libs/db_conecta.php");'."\n");
	      fputs($fd3,'include("libs/db_sessoes.php");'."\n");
	      fputs($fd3,'include("libs/db_usuariosonline.php");'."\n");
	      fputs($fd3,'include("classes/db_'.trim(pg_result($result,$i,"nomearq")).'_classe.php");'."\n");
	      fputs($fd3,'include("dbforms/db_funcoes.php");'."\n");
	      fputs($fd3,'parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);'."\n");
	      fputs($fd3,'db_postmemory($HTTP_POST_VARS);'."\n");
	      fputs($fd3,'$cl'.trim(pg_result($result,$i,"nomearq")).' = new cl_'.trim(pg_result($result,$i,"nomearq")).';'."\n");
	      fputs($fd3,'$db_botao = false;'."\n");
	      fputs($fd3,'$db_opcao = 33;'."\n");
	      fputs($fd3,'if(isset($excluir)){'."\n");
	      fputs($fd3,'  db_inicio_transacao();'."\n");
	      fputs($fd3,'  $db_opcao = 3;'."\n");
	      fputs($fd3,'  $cl'.trim(pg_result($result,$i,"nomearq")).'->excluir(');

	      if(pg_numrows($pk) > 0) {
		$Npk = pg_numrows($pk);
			$virgula = "";
		for($p = 0;$p < $Npk;$p++) {
		  fputs($fd3,$virgula.'$'.trim(pg_result($pk,$p,"nomecam")));
			  $virgula = ",";
		} 
	      }else{
		 fputs($fd3,'$oid');
	      }
	      fputs($fd3,');'."\n");
	      fputs($fd3,'  db_fim_transacao();'."\n");
	      fputs($fd3,'}else if(isset($chavepesquisa)){'."\n");

	      fputs($fd3,'   $db_opcao = 3;'."\n");

	      fputs($fd3,'   $result = $cl'.trim(pg_result($result,$i,"nomearq")).'->sql_record($cl'.trim(pg_result($result,$i,"nomearq")).'->sql_query($chavepesquisa');
		      
	      if(pg_numrows($pk) > 1) {
		$Npk = pg_numrows($pk);
			$virgula = "";
		for($p = 1;$p < $Npk;$p++) {
		  fputs($fd3,',$chavepesquisa'.$p);
			  $virgula = ",";
		} 
	      }

	      fputs($fd3,')); '."\n");

	      
	      fputs($fd3,'   db_fieldsmemory($result,0);'."\n");
	      fputs($fd3,'   $db_botao = true;'."\n");
	      fputs($fd3,'}'."\n");

	      fputs($fd3,'?>'."\n");
	      fputs($fd3,'<html>'."\n");
	      fputs($fd3,'<head>'."\n");
	      fputs($fd3,'<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>'."\n");
	      fputs($fd3,'<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">'."\n");
	      fputs($fd3,'<meta http-equiv="Expires" CONTENT="0">'."\n");
	      fputs($fd3,'<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>'."\n");
	      fputs($fd3,'<link href="estilos.css" rel="stylesheet" type="text/css">'."\n");
	      fputs($fd3,'</head>'."\n");
	      fputs($fd3,'<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >'."\n");
	      fputs($fd3,'<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">'."\n");
	      fputs($fd3,'  <tr> '."\n");
	      fputs($fd3,'    <td width="360" height="18">&nbsp;</td>'."\n");
	      fputs($fd3,'    <td width="263">&nbsp;</td>'."\n");
	      fputs($fd3,'    <td width="25">&nbsp;</td>'."\n");
	      fputs($fd3,'    <td width="140">&nbsp;</td>'."\n");
	      fputs($fd3,'  </tr>'."\n");
	      fputs($fd3,'</table>'."\n");
	      fputs($fd3,'<table width="790" border="0" cellspacing="0" cellpadding="0">'."\n");
	      fputs($fd3,'  <tr> '."\n");
	      fputs($fd3,'    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> '."\n");
	      fputs($fd3,'    <center>'."\n");
	      fputs($fd3,'	<?'."\n");
	      fputs($fd3,'	include("forms/db_frm'.trim(pg_result($result,$i,"nomearq")).'.php");'."\n");
	      fputs($fd3,'	?>'."\n");
		      
	      fputs($fd3,'    </center>'."\n");
	      fputs($fd3,'	</td>'."\n");
	      fputs($fd3,'  </tr>'."\n");
	      fputs($fd3,'</table>'."\n");
	      fputs($fd3,'<?'."\n");
	      fputs($fd3,'db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));'."\n");
	      fputs($fd3,'?>'."\n");
	      fputs($fd3,'</body>'."\n");
	      fputs($fd3,'</html>'."\n");
	      fputs($fd3,'<?'."\n");
	      fputs($fd3,'if(isset($excluir)){'."\n");
	      fputs($fd3,'  if($cl'.trim(pg_result($result,$i,"nomearq")).'->erro_status=="0"){'."\n");
	      fputs($fd3,'    $cl'.trim(pg_result($result,$i,"nomearq")).'->erro(true,false);'."\n");
	      fputs($fd3,'  }else{'."\n");
	      fputs($fd3,'    $cl'.trim(pg_result($result,$i,"nomearq")).'->erro(true,true);'."\n");
	      fputs($fd3,'  }'."\n");
	      fputs($fd3,'}'."\n");
	      fputs($fd3,'if($db_opcao==33){'."\n");
	      fputs($fd3,'  echo "<script>document.form1.pesquisar.click();</script>";'."\n");
	      fputs($fd3,'}'."\n");
	      fputs($fd3,'?>'."\n");
	      
	      fputs($fd3,'<script>'."\n");
          fputs($fd3,'js_tabulacaoforms("form1","excluir",true,1,"excluir",true);'."\n");
	      fputs($fd3,'</script>'."\n");
	      
		      // fim dos java scripts
		}
	  }
	  fclose($fd3);  
	}
 
        if($tipotabela=='1'){
	  $arq002 = $root."/".strtolower(substr($siglamod,0,3)."1_".trim($nometab)."002.php");
	  //$arq002 = "/tmp/".substr($siglamod,0,3)."1_".trim($nometab)."002.php";
	  if(file_exists($arq002) && !is_writable($arq002)){
            ?>
            <table width="100%"><tr><td align="center"><h6>Sem permissão para gravar "<?=substr($siglamod,0,3)."1_".trim($nometab)?>002"</h6></td></tr></table>
            </body>
            </html>
            <?
            exit;
          } 
	  umask(74); 
	  $fd2 = fopen($arq002,"w");

	  fputs($fd2,"<?\n");
	  for($i = 0;$i < $numrows;$i++) {
	    $varpk = ""; 
	    $pk = pg_exec("select a.nomearq,c.nomecam,p.sequen
			     from db_sysprikey p
				  inner join db_sysarquivo a on a.codarq = p.codarq
				  inner join db_syscampo c   on c.codcam = p.codcam
			     where a.codarq = ".pg_result($result,$i,"codarq")."
			     order by p.sequen");
	    $campo = pg_exec("select c.*
				from db_syscampo c
				     inner join db_sysarqcamp a   on a.codcam = c.codcam
				where codarq = ".pg_result($result,$i,"codarq").
					    " order by a.seqarq");
	    $Ncampos = pg_numrows($campo);
	    if($Ncampos > 0) {
	      fputs($fd2,'require("libs/db_stdlib.php");'."\n");
	      fputs($fd2,'require("libs/db_conecta.php");'."\n");
	      fputs($fd2,'include("libs/db_sessoes.php");'."\n");
	      fputs($fd2,'include("libs/db_usuariosonline.php");'."\n");
	      fputs($fd2,'include("classes/db_'.trim(pg_result($result,$i,"nomearq")).'_classe.php");'."\n");
	      fputs($fd2,'include("dbforms/db_funcoes.php");'."\n");
	      fputs($fd2,'db_postmemory($HTTP_SERVER_VARS);'."\n");
	      fputs($fd2,'db_postmemory($HTTP_POST_VARS);'."\n");
	      fputs($fd2,'$cl'.trim(pg_result($result,$i,"nomearq")).' = new cl_'.trim(pg_result($result,$i,"nomearq")).';'."\n");
	      fputs($fd2,'$db_opcao = 22;'."\n");
	      fputs($fd2,'$db_botao = false;'."\n");
	      fputs($fd2,'if(isset($alterar)){'."\n");
	      fputs($fd2,'   db_inicio_transacao();'."\n");
	      fputs($fd2,'   $result = $cl'.trim(pg_result($result,$i,"nomearq")).'->sql_record($cl'.trim(pg_result($result,$i,"nomearq")).'->sql_query());'."\n");
	      fputs($fd2,'   if($result==false || $cl'.trim(pg_result($result,$i,"nomearq")).'->numrows==0){'."\n");
	      fputs($fd2,'     $cl'.trim(pg_result($result,$i,"nomearq")).'->incluir(');
	      if(pg_numrows($pk) > 0) {
		$Npk = pg_numrows($pk);
			$virgula = "";
		for($p = 0;$p < $Npk;$p++) {
		  fputs($fd2,$virgula.'$'.trim(pg_result($pk,$p,"nomecam")));
			  $virgula = ",";
		} 
	      }
	      fputs($fd2,');'."\n");

	      fputs($fd2,'   }else{'."\n");

              fputs($fd2,'     $cl'.trim(pg_result($result,$i,"nomearq")).'->alterar(');

	      if(pg_numrows($pk) > 0) {
		$Npk = pg_numrows($pk);
			$virgula = "";
		for($p = 0;$p < $Npk;$p++) {
		  fputs($fd2,$virgula.'$'.trim(pg_result($pk,$p,"nomecam")));
			  $virgula = ",";
		} 
	      }else{
		 fputs($fd2,'$oid');
	      }
	      fputs($fd2,');'."\n");
	      fputs($fd2,'   }'."\n");
	      fputs($fd2,'   db_fim_transacao();'."\n");
	      fputs($fd2,'}'."\n");

	      fputs($fd2,'$db_opcao = 2;'."\n");
	      fputs($fd2,'$result = $cl'.trim(pg_result($result,$i,"nomearq")).'->sql_record($cl'.trim(pg_result($result,$i,"nomearq")).'->sql_query());'."\n");
		      
	      fputs($fd2,'if($result!=false && $cl'.trim(pg_result($result,$i,"nomearq")).'->numrows>0){'."\n");
	      fputs($fd2,'  db_fieldsmemory($result,0);'."\n");
	      fputs($fd2,'}'."\n");
	      fputs($fd2,'$db_botao = true;'."\n");

	      fputs($fd2,'?>'."\n");
	      fputs($fd2,'<html>'."\n");
	      fputs($fd2,'<head>'."\n");
	      fputs($fd2,'<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>'."\n");
	      fputs($fd2,'<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">'."\n");
	      fputs($fd2,'<meta http-equiv="Expires" CONTENT="0">'."\n");
	      fputs($fd2,'<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>'."\n");
	      fputs($fd2,'<link href="estilos.css" rel="stylesheet" type="text/css">'."\n");
	      fputs($fd2,'</head>'."\n");
	      fputs($fd2,'<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >'."\n");
	      fputs($fd2,'<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">'."\n");
	      fputs($fd2,'  <tr> '."\n");
	      fputs($fd2,'    <td width="360" height="18">&nbsp;</td>'."\n");
	      fputs($fd2,'    <td width="263">&nbsp;</td>'."\n");
	      fputs($fd2,'    <td width="25">&nbsp;</td>'."\n");
	      fputs($fd2,'    <td width="140">&nbsp;</td>'."\n");
	      fputs($fd2,'  </tr>'."\n");
	      fputs($fd2,'</table>'."\n");
	      fputs($fd2,'<table width="790" border="0" cellspacing="0" cellpadding="0">'."\n");
	      fputs($fd2,'  <tr> '."\n");
	      fputs($fd2,'    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> '."\n");
	      fputs($fd2,'    <center>'."\n");
	      fputs($fd2,'	<?'."\n");
	      fputs($fd2,'	include("forms/db_frm'.trim(pg_result($result,$i,"nomearq")).'.php");'."\n");
	      fputs($fd2,'	?>'."\n");
	      fputs($fd2,'    </center>'."\n");
	      fputs($fd2,'	</td>'."\n");
	      fputs($fd2,'  </tr>'."\n");
	      fputs($fd2,'</table>'."\n");
	      fputs($fd2,'<?'."\n");
	      fputs($fd2,'db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));'."\n");
	      fputs($fd2,'?>'."\n");
	      fputs($fd2,'</body>'."\n");
	      fputs($fd2,'</html>'."\n");
	      fputs($fd2,'<?'."\n");
		      
	      fputs($fd2,'if(isset($alterar)){'."\n");
	      fputs($fd2,'  if($cl'.trim(pg_result($result,$i,"nomearq")).'->erro_status=="0"){'."\n");
	      fputs($fd2,'    $cl'.trim(pg_result($result,$i,"nomearq")).'->erro(true,false);'."\n");
	      fputs($fd2,'    $db_botao=true;'."\n");
	      fputs($fd2,'    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";'."\n");
	      fputs($fd2,'    if($cl'.trim(pg_result($result,$i,"nomearq")).'->erro_campo!=""){'."\n");
	      fputs($fd2,'      echo "<script> document.form1.".$cl'.trim(pg_result($result,$i,"nomearq")).'->erro_campo.".style.backgroundColor=\'#99A9AE\';</script>";'."\n");
	      fputs($fd2,'      echo "<script> document.form1.".$cl'.trim(pg_result($result,$i,"nomearq")).'->erro_campo.".focus();</script>";'."\n");
	      fputs($fd2,'    }'."\n");
	      fputs($fd2,'  }else{'."\n");
	      fputs($fd2,'    $cl'.trim(pg_result($result,$i,"nomearq")).'->erro(true,true);'."\n");
	      fputs($fd2,'  }'."\n");
	      fputs($fd2,'}'."\n");
	      fputs($fd2,'if($db_opcao==22){'."\n");
	      fputs($fd2,'  echo "<script>document.form1.pesquisar.click();</script>";'."\n");
	      fputs($fd2,'}'."\n");
	      fputs($fd2,'?>'."\n");
   	    }
	  }
	  fclose($fd2);  
       }
	
     } 
     ?>
     <table width="100%"><tr><td align="center"><h3>Concluído...</h3></td></tr></table>
     <?
  }
  ?>
</body>
</html>