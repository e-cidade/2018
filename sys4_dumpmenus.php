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
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="102%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="102%" height="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#CCCCCC">
  <tr> 
    <td>
    <center>
    <?
    $root = substr($HTTP_SERVER_VARS['SCRIPT_FILENAME'],0,strrpos($HTTP_SERVER_VARS['SCRIPT_FILENAME'],"/"));
    $arquivo = $root."/"."tmp/atualiza_menus.txt";
    $fd = fopen($arquivo,"w");
    fputs($fd,"<? \n");
    fputs($fd,"//data : ".date("d/m/Y",db_getsession("DB_datausu"))."\n");
	// itens dos menus 
	$sql = "select * from db_itensmenu";
    $result = pg_query($sql);
	if(pg_numrows($result)!=0){
	  for($i=0;$i<pg_numrows($result);$i++){
	     db_fieldsmemory($result,$i);
	     fputs($fd,'$sql = "delete from db_itensmenu where id_item = '.$id_item.'";'."\n");
	     fputs($fd,'$result = pg_query($sql);'."\n");
	     fputs($fd,'$sql = "insert into db_itensmenu(id_item,descricao,help,funcao,itemativo,manutencao,desctec) value ('.$id_item.",'".$descricao."','".$help."','".$funcao."','".$itemativo."','".$manutencao."','".$desctec.'\')";'."\n");
	     fputs($fd,'$result = pg_query($sql);'."\n");
	  }
	}
	// menus 
	$sql = "select * from db_menu";
    $result = pg_query($sql);
	if(pg_numrows($result)!=0){
	  for($i=0;$i<pg_numrows($result);$i++){
	     db_fieldsmemory($result,$i);
	     fputs($fd,'$sql = "delete from db_menu where id_item = '.$id_item.' and id_item_filho = '.$id_item.' and modulo = '.$modulo.'";'."\n");
	     fputs($fd,'$result = pg_query($sql);'."\n");
	     fputs($fd,'$sql = "insert into db_menu(id_item,id_item_filho,menusequencia,modulo) value ('.$id_item.",".$id_item_filho.",".$menusequencia.",".$modulo.')";'."\n");
	     fputs($fd,'$result = pg_query($sql);'."\n");
	  }
	}
	// meodulos dos menus
	$sql = "select * from db_modulo";
    $result = pg_query($sql);
	if(pg_numrows($result)!=0){
	  for($i=0;$i<pg_numrows($result);$i++){
	     db_fieldsmemory($result,$i);
	     fputs($fd,'$sql = "delete from db_modulo where id_item = '.$id_item.'";'."\n");
	     fputs($fd,'$result = pg_query($sql);'."\n");
	     fputs($fd,'$sql = "insert into db_modulo(id_item,nome_modulo,descr_modulo,imagem,temexerc) value ('.$id_item.",'".$nome_modulo."','".$descr_modulo."','".$imagem."','".$temexerc."')\";"."\n");
	     fputs($fd,'$result = pg_query($sql);'."\n");
	  }
	}
	// permissoes do usuário dbseller
    fputs($fd,'$sql = "delete from db_permissao where id_usuario = 1";'."\n");
    fputs($fd,'$result = pg_query($sql);'."\n");
	$sql = "select * from db_permissao where id_usuario = 1";
    $result = pg_query($sql);
	if(pg_numrows($result)!=0){
	  for($i=0;$i<pg_numrows($result);$i++){
	     db_fieldsmemory($result,$i);
	     fputs($fd,'$sql = "insert into db_permissao(id_usuario,id_item,permissaoativa,anousu,id_instit,id_modulo) value ('.$id_usuario.",".$id_item.",'".$permissaoativa."',".$anousu.",".$id_instit.",".$id_modulo.")\";"."\n");
	     fputs($fd,'$result = pg_query($sql);'."\n");
	  }
	}
    fputs($fd,'?>'."\n");
    fclose($fd);
    ?>
	<a name="arquivo" href="tmp/<?=basename($arquivo)?>" title="Arquivo gerado no formato php">Clique aqui para baixar o arquivo dos menus</a>
    <center>
    </td>
  </tr>
</table>
<!--table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
	<br><br><h3>Dump gerado...</h3>
	if(!file_exists("/usr/bin/pg_dump"))
	  db_erro("Arquivo /usr/bin/pg_dump não encontrado");
	flush();
	$root = substr($HTTP_SERVER_VARS['SCRIPT_FILENAME'],0,strrpos($HTTP_SERVER_VARS['SCRIPT_FILENAME'],"/"));
	$arquivo = tempnam("tmp","dump");
	
	shell_exec("echo 'drop table db_menu;' > ".$arquivo);
	shell_exec("echo 'drop table db_itensmenu;' >> ".$arquivo);
	shell_exec("echo 'drop table db_permissao;' >> ".$arquivo);
	shell_exec("echo -e 'drop table db_modulos;\n\n\n' >> ".$arquivo);
	
	shell_exec("pg_dump -h $DB_SERVIDOR -U postgres $DB_BASE -x -O -s -t db_menu >> ".$arquivo);
	shell_exec("pg_dump -h $DB_SERVIDOR -U postgres $DB_BASE -x -O -s -t db_itensmenu >> ".$arquivo);
	shell_exec("pg_dump -h $DB_SERVIDOR -U postgres $DB_BASE -x -O -s -t db_permissao >> ".$arquivo);
	shell_exec("pg_dump -h $DB_SERVIDOR -U postgres $DB_BASE -x -O -s -t db_modulos >> ".$arquivo);
	
	shell_exec("pg_dump -h $DB_SERVIDOR -U postgres $DB_BASE -x -O -a -d -t db_menu >> ".$arquivo);			
	shell_exec("pg_dump -h $DB_SERVIDOR -U postgres $DB_BASE -x -O -a -d -t db_itensmenu >> ".$arquivo);			
	shell_exec("pg_dump -h $DB_SERVIDOR -U postgres $DB_BASE -x -O -a -d -t db_permissao >> ".$arquivo);			
	shell_exec("pg_dump -h $DB_SERVIDOR -U postgres $DB_BASE -x -O -a -d -t db_modulos >> ".$arquivo);
	shell_exec("bzip2 ".$arquivo);
	<a href="<?=$arquivo?>.bz2" title="Clique aqui para download">Download</a>
	</td>
  </tr>
</table-->


<?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>