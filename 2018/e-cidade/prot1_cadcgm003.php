<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_cgm_classe.php");
require_once("classes/db_cgmalt_classe.php");
require_once("classes/db_db_cgmruas_classe.php");
require_once("classes/db_db_cgmbairro_classe.php");
require_once("classes/db_db_cgmcgc_classe.php");
require_once("classes/db_db_cgmcpf_classe.php");
require_once("classes/db_db_cgmcpf_classe.php");
require_once("classes/db_cgmendereco_classe.php");
require_once("classes/db_db_cepmunic_classe.php");
require_once("classes/db_cgmdocumento_classe.php");
require_once("classes/db_ruascep_classe.php");


db_postmemory($HTTP_POST_VARS);

$clcgm          = new cl_cgm();
$clcgmalt       = new cl_cgmalt();
$cldb_cgmruas   = new cl_db_cgmruas();
$cldb_cgmbairro = new cl_db_cgmbairro();
$cldb_cgmcpf    = new cl_db_cgmcpf();
$cldb_cgmcgc    = new cl_db_cgmcgc();
$clcgmdocumento = new cl_cgmdocumento();
$clcgmendereco  = new cl_cgmendereco();

$db_opcao = 3;
$db_botao = false;
$lSqlErro = false;

if (isset ($HTTP_POST_VARS ["db_opcao"]) && $HTTP_POST_VARS ["db_opcao"] == "Excluir") {
	
	db_inicio_transacao();
	
	$cgm_alt    = $z01_numcgm;
	$result_cgm = $clcgm->sql_record ( $clcgm->sql_query_file ( $cgm_alt ) );
	$numrows    = $clcgm->numrows;
	if ($numrows != 0) {
		
		db_fieldsmemory ( $result_cgm, 0 );
		
		$clcgmalt->z05_numcgm = $z01_numcgm;
		$clcgmalt->z05_numcon = $z01_numcon;
		$clcgmalt->z05_estciv = $z01_estciv;
		$clcgmalt->z05_nacion = $z01_nacion;
		$clcgmalt->z05_tipcre = $z01_tipcre;
		$clcgmalt->z05_hora_alt = db_hora ();
		$clcgmalt->z05_data_alt = date ( 'Y-m-d', db_getsession ( "DB_datausu" ) );
		$clcgmalt->z05_login_alt = db_getsession ( "DB_id_usuario" );
		$clcgmalt->z05_nomefanta = $z01_nomefanta;
		$clcgmalt->z05_contato = $z01_contato;
		$clcgmalt->z05_sexo = $z01_sexo;
		$clcgmalt->z05_fax = $z01_fax;
		$clcgmalt->z05_nasc = $z01_nasc;
		$clcgmalt->z05_mae = $z01_mae;
		$clcgmalt->z05_pai = $z01_pai;
		$clcgmalt->z05_ultalt = $z01_ultalt;
		$clcgmalt->z05_cpf = @$z01_cpf;
		$clcgmalt->z05_cgc = @$z01_cgc;
		$clcgmalt->z05_cep = $z01_cep;
		$clcgmalt->z05_ender = $z01_ender;
		$clcgmalt->z05_cxposcon = $z01_cxposcon;
		$clcgmalt->z05_cepcon = $z01_cepcon;
		$clcgmalt->z05_baicon = $z01_baicon;
		$clcgmalt->z05_celcon = $z01_celcon;
		$clcgmalt->z05_bairro = $z01_bairro;
		$clcgmalt->z05_uf = $z01_uf;
		$clcgmalt->z05_telef = $z01_telef;
		$clcgmalt->z05_telcon = $z01_telcon;
		$clcgmalt->z05_telcel = $z01_telcel;
		$clcgmalt->z05_profis = $z01_profis;
		$clcgmalt->z05_incest = $z01_incest;
		$clcgmalt->z05_ident = $z01_ident;
		$clcgmalt->z05_endcon = $z01_endcon;
		$clcgmalt->z05_cxpostal = $z01_cxpostal;
		$clcgmalt->z05_comcon = $z01_comcon;
		$clcgmalt->z05_cgccpf = @$z01_cgccpf;
		$clcgmalt->z05_ufcon = $z01_ufcon;
		$clcgmalt->z05_muncon = $z01_muncon;
		$clcgmalt->z05_nome = $z01_nome;
		$clcgmalt->z05_munic = $z01_munic;
		$clcgmalt->z05_emailc = $z01_emailc;
		$clcgmalt->z05_email = $z01_email;
		$clcgmalt->z05_numero = $z01_numero;
		$clcgmalt->z05_cadast = $z01_cadast;
		$clcgmalt->z05_login = $z01_login;
		$clcgmalt->z05_compl = $z01_compl;
		$clcgmalt->z05_hora = @$z01_hora;
		$clcgmalt->z05_tipo_alt = "E";
		$clcgmalt->incluir(null);
		$sMsgErro = $clcgmalt->erro_msg;
		if ($clcgmalt->erro_status == 0) {
			$lSqlErro = true;
		}
	}
	
	$sDocWhere = "z06_numcgm=" . $z01_numcgm;

  if (!$lSqlErro) {
    
		$cldb_cgmruas->excluir($z01_numcgm);
		$sMsgErro = $cldb_cgmruas->erro_msg;
		if ($cldb_cgmruas->erro_status == 0) {
			$lSqlErro = true;
		}
  }
	
  if (!$lSqlErro) {
     
		$cldb_cgmbairro->excluir($z01_numcgm);
		$sMsgErro = $cldb_cgmbairro->erro_msg;
	  if ($cldb_cgmbairro->erro_status == 0) {
	    $lSqlErro = true;
	  }
  }
	
  if (!$lSqlErro) {
     
		$cldb_cgmcgc->excluir($z01_numcgm);
		$sMsgErro = $cldb_cgmcgc->erro_msg;
	  if ($cldb_cgmcgc->erro_status == 0) {
	    $lSqlErro = true;
	  }
  }
	
  if (!$lSqlErro) {
     
		$cldb_cgmcpf->excluir($z01_numcgm);
		$sMsgErro = $cldb_cgmcpf->erro_msg;
	  if ($cldb_cgmcpf->erro_status == 0) {
	    $lSqlErro = true;
	  }
  }
	
  if (!$lSqlErro) {
     
		$clcgmdocumento->excluir(null, $sDocWhere);
		$sMsgErro = $clcgmdocumento->erro_msg;
	  if ($clcgmdocumento->erro_status == 0) {
	    $lSqlErro = true;
	  }
  }
	
  if (!$lSqlErro) {
     
    $clcgmendereco->excluir(null, "z07_numcgm = {$z01_numcgm}");
    $sMsgErro = $clcgmendereco->erro_msg;
    if ($clcgmendereco->erro_status == 0) {
      $lSqlErro = true;
    }
  }
  
  if (!$lSqlErro) {
  
    $oDaoCgmFisico   = db_utils::getDao('cgmfisico');
    $oDaoCgmJuridico = db_utils::getDao('cgmjuridico');
  
    $oDaoCgmFisico->excluir(null, "z04_numcgm = $z01_numcgm");
    $oDaoCgmJuridico->excluir(null, "z08_numcgm = $z01_numcgm");
  
    if ($oDaoCgmFisico->erro_status == "" || $oDaoCgmJuridico->erro_status == 0) {
  
      $lSqlErro = true;
  
    }
  
  }
  
  if (!$lSqlErro) {
     
		$clcgm->excluir($z01_numcgm);
		$sMsgErro = $clcgm->erro_msg;
	  if ($clcgm->erro_status == 0) {
	    $lSqlErro = true;
	  }
  }
  
	if (isset($sMsgErro) && !empty($sMsgErro)) {
	  db_msgbox($sMsgErro);
	}
  
	db_fim_transacao($lSqlErro);
	db_redireciona("prot1_cadcgm003.php");
} else if (isset($chavepesquisa)) {
	
	$db_botao = true;
	$result = $clcgm->sql_record ( $clcgm->sql_query ( $chavepesquisa ) );
	db_fieldsmemory($result, 0);
}
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
<table width="790" border="0" cellpadding="0" cellspacing="0"
	bgcolor="#5786B2">
	<tr>
		<td width="360" height="18">&nbsp;</td>
		<td width="263">&nbsp;</td>
		<td width="25">&nbsp;</td>
		<td width="140">&nbsp;</td>
	</tr>
</table>
<table width="790" height="100%" border="0" cellpadding="0"
	cellspacing="0">
	<tr>
		<td height="430" align="center" valign="top" bgcolor="#CCCCCC">
			<?
			  include ("forms/db_frmcgm.php");
			?>
	</td>
	</tr>
</table>
<?
db_menu ( db_getsession ( "DB_id_usuario" ), db_getsession ( "DB_modulo" ), db_getsession ( "DB_anousu" ), db_getsession ( "DB_instit" ) );
?>
</body>
</html>
<?
if ($db_botao == false) {
	echo "<script>js_func_nome();func_nome.show();</script>\n";
}
?>