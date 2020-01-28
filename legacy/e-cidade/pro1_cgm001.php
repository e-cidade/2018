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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  //Inicio excluir registro
  if(isset($HTTP_POST_VARS["excluir"])) {
    pg_exec("BEGIN");
    $result = pg_exec("DELETE db_cgm WHERE numcgm = $numcgm");
    if(pg_cmdtuples($result) == 0) {
      pg_exec("ROLLBACK");
	  echo "Erro deletando tabela tb_cgm.<a href=\"\" onclick=\"history.back();return false\">Voltar</a>\n";
	  exit;
    }	
    $result = pg_exec("DELETE tb_cgmcpf WHERE numcgm = $numcgm");
    $result = pg_exec("DELETE tb_cgmcgc WHERE numcgm = $numcgm");    
    $result = pg_exec("DELETE tb_cgmlog WHERE numcgm = $numcgm");
    if(pg_cmdtuples($result) == 0) {
      pg_exec("ROLLBACK");
	  echo "Erro deletando tabela tb_cgmlog.<a href=\"\" onclick=\"history.back();return false\">Voltar</a>\n";
	  exit;
    }					
    $result = pg_exec("DELETE tb_cgmendlog WHERE codigo = $numcgm");
    if(pg_cmdtuples($result) == 0) {
      pg_exec("ROLLBACK");
	  echo "Erro deletando tabela tb_cgmendlog.<a href=\"\" onclick=\"history.back();return false\">Voltar</a>\n";
	  exit;
    }					
    $result = pg_exec("DELETE tb_cgmend WHERE numcgm = $numcgm");
    if(pg_cmdtuples($result) == 0) {
      pg_exec("ROLLBACK");
	  echo "Erro deletando tabela tb_cgmend.<a href=\"\" onclick=\"history.back();return false\">Voltar</a>\n";
	  exit;
    }					 
	pg_exec("COMMIT");	
  //Inicio alterar registro
  } else if(isset($HTTP_POST_VARS["alterar"])) {
    postmemory($HTTP_POST_VARS);
    $result = pg_exec("select numcgm from tb_cgm where numcgm = $numcgm");
	if(pg_numrows($result) == 0) {
	  echo "<script>alert('Codigo $numcgm não encontrado')</script>\n";
	} else {
	  pg_exec("BEGIN");
 	  $result = pg_exec("UPDATE tb_cgm SET
						nome = '$nome',
						endereco = '$endereco',
						municipio = '$municipio',
						uf = '$uf',
						cep = '$cep',
						cadastro = CURRENT_DATE,
						telefone = '$telefone',
						identidade = '$identidade',
						login = '$DB_login',
						bairro = '$bairro',
						incest = '$incest',
						telcel = '$telcel',
                        email = '$email'
					WHERE numcgm = $numcgm");
    if(pg_cmdtuples($result) == 0) {
      pg_exec("ROLLBACK");
	  echo "Erro alterando tabela tb_cgm.<a href=\"\" onclick=\"history.back();return false\">Voltar</a>\n";
	  exit;
    }
	
    if(!empty($cpf)) {
      $result = pg_exec("UPDATE tb_cgmcpf SET
                         cpf = '$cpf'
						 WHERE numcgm = $numcgm");
	  if(pg_cmdtuples($result) == 0) {
        pg_exec("ROLLBACK");
	    echo "Erro alterando tabela tb_cgmcpf.<a href=\"\" onclick=\"history.back();return false\">Voltar</a>\n";
	    exit;
      }
    } else if(!empty($cgc)) {
      $result = pg_exec("UPDATE tb_cgmcgc SET
	                 cgc = '$cgc',
					 tipocredor = $tipocredor
					 WHERE numcgm = $numcgm");
      if(pg_cmdtuples($result) == 0) {
        pg_exec("ROLLBACK");
	    echo "Erro alterando tabela tb_cgmcgc.<a href=\"\" onclick=\"history.back();return false\">Voltar</a>\n";
        exit;
      }
    }
    $result = pg_exec("UPDATE tb_cgmlog set
	                   codlog = $logend
					   WHERE numcgm = $numcgm");
    if(pg_cmdtuples($result) == 0) {
      pg_exec("ROLLBACK");
	  echo "Erro alterando tabela tb_cgmlog.<a href=\"\" onclick=\"history.back();return false\">Voltar</a>\n";
	  exit;
    }					
    $result = pg_exec("UPDATE tb_cgmendlog SET
                       codlog = $logendcon
					   WHERE codigo = $numcgm");
    if(pg_cmdtuples($result) == 0) {
      pg_exec("ROLLBACK");
	  echo "Erro alterando tabela tb_cgmendlog.<a href=\"\" onclick=\"history.back();return false\">Voltar</a>\n";
	  exit;
    }					
    $result = pg_exec("UPDATE tb_cgmend SET
					   endcon = '$endcon',
                       muncon = '$muncon',
					   baicon = '$baicon',
                       ufcon = '$ufcon',
                       cepcon = '$cepcon',
                       telcon = '$telcon',
                       celcon = '$celcon',
                       emailc = '$emailc'
					   WHERE numcgm = $numcgm");
    if(pg_cmdtuples($result) == 0) {
      pg_exec("ROLLBACK");
	  echo "Erro alterando tabela tb_cgmend.<a href=\"\" onclick=\"history.back();return false\">Voltar</a>\n";
	  exit;
    }					 
	pg_exec("COMMIT");
	}
  //Inicio incluir registro
  } else if(isset($HTTP_POST_VARS["salvar"])) {
    postmemory($HTTP_POST_VARS);
    $result = pg_exec("select max(numcgm) from tb_cgm");
	$numcgm = pg_result($result,0,0) == ""?1:((integer)pg_result($result,0,0) + 1);
	pg_exec("BEGIN");
	$result = pg_exec("INSERT INTO tb_cgm VALUES(
	                    $numcgm,
						'$nome',
						'$endereco',
						'$municipio',
						'$uf',
						'$cep',
						CURRENT_DATE,
						'$telefone',
						'$identidade',
						'$DB_login',
						'$bairro',
						'$incest',
						'$telcel',
                        '$email')
				      ");
    if(pg_cmdtuples($result) == 0) {
      pg_exec("ROLLBACK");
	  echo "Erro Inserindo na tabela tb_cgm.<a href=\"\" onclick=\"history.back();return false\">Voltar</a>\n";
	  exit;
    }
	
    if(!empty($cpf)) {
      $result = pg_exec("INSERT INTO tb_cgmcpf VALUES(
                         '$cpf',
						 $numcgm)           
				      ");
	  if(pg_cmdtuples($result) == 0) {
        pg_exec("ROLLBACK");
	    echo "Erro Inserindo na tabela tb_cgmcpf.<a href=\"\" onclick=\"history.back();return false\">Voltar</a>\n";
	    exit;
      }
    } else if(!empty($cgc)) {
      $result = pg_exec("INSERT INTO tb_cgmcgc VALUES(
	                 '$cgc',
	  				 $numcgm,
					 $tipocredor)
		             ");
      if(pg_cmdtuples($result) == 0) {
        pg_exec("ROLLBACK");
	    echo "Erro Inserindo na tabela tb_cgmcgc.<a href=\"\" onclick=\"history.back();return false\">Voltar</a>\n";
        exit;
      }
    }
    $result = pg_exec("INSERT INTO tb_cgmlog VALUES($numcgm,$logend)");
    if(pg_cmdtuples($result) == 0) {
      pg_exec("ROLLBACK");
	  echo "Erro Inserindo na tabela tb_cgmlog.<a href=\"\" onclick=\"history.back();return false\">Voltar</a>\n";
	  exit;
    }					
    $result = pg_exec("INSERT INTO tb_cgmendlog VALUES(
                       $numcgm,
                       $logendcon)
                    ");
    if(pg_cmdtuples($result) == 0) {
      pg_exec("ROLLBACK");
	  echo "Erro Inserindo na tabela tb_cgmendlog.<a href=\"\" onclick=\"history.back();return false\">Voltar</a>\n";
	  exit;
    }					
    $result = pg_exec("select max(codigo) from tb_cgmend");
    $codigo = pg_result($result,0,0) == ""?1:((integer)pg_result($result,0,0) + 1);
    $result = pg_exec("INSERT INTO tb_cgmend VALUES(
                       $codigo,
					   $numcgm,
					   '$endcon',
                       '$muncon',
					   '$baicon',
                       '$ufcon',
                       '$cepcon',
                       '$telcon',
                       '$celcon',
                       '$emailc')
					 ");
    if(pg_cmdtuples($result) == 0) {
      pg_exec("ROLLBACK");
	  echo "Erro Inserindo na tabela tb_cgmend.<a href=\"\" onclick=\"history.back();return false\">Voltar</a>\n";
	  exit;
    }					 
	pg_exec("COMMIT");
  }
/*
  //Fim incluir registro
  $inc = substr($perm,0,1);
  $alt = substr($perm,1,1);
  $exc = substr($perm,2,1);
*/

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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<?
    include("forms/db_frmcgm.php"); 
    ?>
	</td>
  </tr>
</table>
<?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>	
</body>
</html>