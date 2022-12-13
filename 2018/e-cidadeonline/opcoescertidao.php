<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

session_start ();

require_once("libs/db_conecta.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_sql.php");
require_once("libs/db_mens.php");
require_once("classes/db_issbase_classe.php");
require_once("classes/db_cgm_classe.php");
require_once("classes/db_iptubase_classe.php");

parse_str( base64_decode( $HTTP_SERVER_VARS ["QUERY_STRING"] ) );

$aRetorno = array();
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]),$aRetorno);

$matricula1 = @$aRetorno['matricula1'];
$cgc        = @$aRetorno['cgc'];
$cpf        = @$aRetorno['cpf'];
$opcao      = @$aRetorno['opcao'];
$id_usuario = @$aRetorno['id_usuario'];	

db_postmemory($_POST);
db_postmemory($_GET);

$db_datausu      = date("Y-m-d");
$ano             = date("Y");
$instit          = db_getsession("DB_instit");
$iLogin          = db_getsession("DB_login");
$db21_usasisagua = 'f';
$virgula         = "";
$sK00_tipo       = "";
$cgccpf          = "";
$aK00_tipo       = array();

$clissbase  = new cl_issbase;
$clcgm      = new cl_cgm;
$cliptubase = new cl_iptubase;

if(isset($matricula1) && $matricula1 == "") {
	$matricula1 = $matricula;
}

/*
 * consulta se o parâmetro do módulo prefeitura online está habilidado como true
*/

$sqlRetornoConfig  = " select *                       ";
$sqlRetornoConfig .= "   from configdbpref            "; 
$sqlRetornoConfig .= "  where w13_instit = {$instit}  ";

$rsRetornoConfig  = db_query($sqlRetornoConfig);
$sConfig          = pg_fetch_assoc($rsRetornoConfig);

$sqlinst  = " select codigo as instituicao,  "; 
$sqlinst .= "        db21_regracgmiptu,      ";
$sqlinst .= "        db21_usasisagua         ";
$sqlinst .= "   from db_config               ";
$sqlinst .= "  where codigo = {$instit}      ";
                 
$resultinst = db_query($sqlinst);
db_fieldsmemory($resultinst, 0);

// $cgccpf recebe o valor informado no formulário [CPF ou CNPJ]
// Depois com o preg_replace é retirada a formatação do campo 

$cgccpf = "";

if (isset($cgc) || isset($cpf)) {
	
	if ( !empty ( $cgc ) ) {
	  $cgccpf = ereg_replace("[./-]","",$cgc); 
	} else if (!empty ( $cpf ) ) {
	  $cgccpf = ereg_replace("[./-]","",$cpf);   
	}	
}

// valida se o número da matrícula fornecida é válida
if ( isset($matricula1) && $matricula1 != "" ) {

  $sWhere = "";  
  $sJoin  = "";      
  $sCampo = "";
  
  if ($sConfig["w13_exigecpfcnpjmatricula"] == "t") {
   switch ($db21_regracgmiptu) {
     case 0:
       
       $sJoin  .= " left join propri         on j42_matric           = j01_matric                               ";
       $sJoin  .= " left join promitente     on j41_matric           = j01_matric                               ";
       $sJoin  .= " left join cgm cgmpropri  on cgmpropri.z01_numcgm = j42_numcgm                               ";
       $sJoin  .= " left join cgm cgmpromi   on cgmpromi.z01_numcgm  = j41_numcgm                               ";
       $sJoin  .= " left join cgm cgmiptu    on cgmiptu.z01_numcgm   = j01_numcgm                               ";
       $sWhere .= " and (trim(cgmpropri.z01_cgccpf) = '{$cgccpf}' or trim(cgmpromi.z01_cgccpf) = '{$cgccpf}'    ";   
       $sWhere .= "                                               or trim(cgmiptu.z01_cgccpf)  = '{$cgccpf}' )  ";   
       $sCampo .= " j01_numcgm                                                                                  ";   
       break;
       
     case 1:
       
       $sJoin  .= " left join propri            on j42_matric              = j01_matric                          ";
       $sJoin  .= " left join cgm cgmoutpropri  on cgmoutpropri.z01_numcgm = j42_numcgm                          ";
       $sJoin  .= " left join cgm cgmiptu       on cgmiptu.z01_numcgm      = j01_numcgm                          ";
       $sWhere .= " and  (trim(cgmiptu.z01_cgccpf) = '{$cgccpf}' or trim(cgmoutpropri.z01_cgccpf) = '{$cgccpf}') ";
       $sCampo .= " j42_numcgm                                                                                   ";
       break;

     case 2:
       
       $sJoin  .= " left join promitente     on j41_matric           = j01_matric                                ";
       $sJoin  .= " left join cgm cgmpromi   on cgmpromi.z01_numcgm  = j41_numcgm                                ";
       $sJoin  .= " left join cgm cgmpropri  on cgmpropri.z01_numcgm = j01_numcgm                                ";
       $sWhere .= " and  (case when j41_matric is not null then trim(cgmpromi.z01_cgccpf) = '{$cgccpf}'          ";
       $sWhere .= "       else trim(cgmpropri.z01_cgccpf) = '{$cgccpf}' end )                                    ";
       $sCampo .= " j41_numcgm                                                                                   ";
       break;
     
     default:
       $sCampo = " null                                                                                          ";
       break;
     	
     }
   } else {
     $sCampo = " null                                                                                          ";
   }
   
   $sql  = " select {$sCampo} as q02_numcgm,                                     "; 
   $sql .= "                j01_matric                                           ";
   $sql .= "           from iptubase                                             ";
   $sql .= "                left  join issbase   on q02_numcgm = j01_numcgm      ";
   $sql .= "               {$sJoin}                                              ";
   $sql .= "         where j01_matric = {$matricula1}                            "; 
   $sql .= "      {$sWhere}                                                      ";
   
  $rsValidaMatricula = db_query($sql);
  $sResultadoValMat  = pg_num_rows($rsValidaMatricula);
  
  if ($sResultadoValMat == 0 ) {
  	  $sUrl = base64_encode("erroscripts=Aviso: Os dados informados não conferem. Verifique o número da matrícula ou o CPF/CNPJ indicado!");
      db_redireciona("certidaoimovel003.php?". $sUrl);
  }
  
}

if (isset($id_usuario) && trim($id_usuario) != ""){
// verifica se é um escritório contábel
$sVerificaEscrito  = " select q86_numcgm                    ";
$sVerificaEscrito .= "    from cadescrito                   ";
$sVerificaEscrito .= "   where q86_numcgm = {$id_usuario}   ";

$rsVerificaEscrito = db_query($sVerificaEscrito);

  // impede o usuário, que for escritório, de fazer consultas que NÃO estejam ligadas ao seu cgm
  if (pg_num_rows($rsVerificaEscrito) > 0 && $opcao == "i" && !isset($naovalida) ) {
     if (isset($inscricaow)) {
       $iInscricao = $inscricaow;
     } else if (isset($inscricao)) {
       $iInscricao = $inscricao;
     }
 
  $sSqlVerifica  = " select q10_numcgm,                ";
  $sSqlVerifica .= "        q10_inscr                  ";
  $sSqlVerifica .= "   from escrito                    ";
  $sSqlVerifica .= "  where q10_numcgm = {$id_usuario} ";
  $sSqlVerifica .= "    and q10_inscr =  {$iInscricao} ";
  $sSqlVerifica .= "        union                      ";
  $sSqlVerifica .= " select q02_numcgm,                "; 
  $sSqlVerifica .= "        q02_inscr                  ";
  $sSqlVerifica .= "   from issbase                    ";
  $sSqlVerifica .= "  where q02_numcgm = {$id_usuario} "; 
  $sSqlVerifica .= "    and q02_inscr =  {$iInscricao} ";
  
  $rsVerificaInscricao = db_query($sSqlVerifica);
  $iVerificaInscricao  = pg_numrows($rsVerificaInscricao);
  
    if ($iVerificaInscricao == 0) {
  	  $sUrl = base64_encode("id_usuario=".$id_usuario."&opcao=i&inscricao=".$iInscricao);
      db_msgbox("Não é permitido fazer consultas que não estejam ligadas ao seu CGM.");
      db_redireciona("certidaoinscr003.php?".$sUrl);
    }
  }
}

if (!session_is_registered ("DB_processacaptcha")) {
	session_register ( "DB_processacaptcha" );
	$_SESSION ["DB_processacaptcha"] = false;
} else {
	$_SESSION ["DB_processacaptcha"] = false;
}

$sqlmenu  = " select funcao          "; 
$sqlmenu .= "   from db_itensmenu    ";
$sqlmenu .= "  where id_item = 5468  ";

$resultmenu = db_query ( $sqlmenu );
$linhasmenu = pg_num_rows ( $resultmenu );

if ($linhasmenu > 0) {
  db_fieldsmemory ( $resultmenu, 0 );
}

$where = "";

if (isset ( $id_usuario ) and $id_usuario != "") {
	$codperfil = $HTTP_SESSION_VARS ['DB_codperfil'];
	$where = " and  p.id_usuario = " . $codperfil;
} else {
	$sqlusu = "select id_usuario as usuario from db_usuarios where login = 'dbpref'";
	$resultusu = db_query ( $sqlusu );
	$linhasusu = pg_num_rows ( $resultusu );
	db_fieldsmemory ( $resultusu, 0 );
	$where = " and p.id_usuario = $usuario ";
}

$sqlmenucert  = " SELECT i.descricao,                                               ";
$sqlmenucert .= "        i.libcliente                                               ";
$sqlmenucert .= "	FROM db_menu m                                                    ";
$sqlmenucert .= "	  	 INNER JOIN db_permissao p ON p.id_item = m.id_item_filho     ";
$sqlmenucert .= "		 INNER JOIN db_itensmenu i ON i.id_item = m.id_item_filho       "; 
$sqlmenucert .= "                                 AND p.anousu = $ano               ";
$sqlmenucert .= "                                 AND p.id_instit = $instit         ";
$sqlmenucert .= "  WHERE i.itemativo = 1                                            ";
$sqlmenucert .= "	  		 $where                      																";

$resultmenucert = db_query($sqlmenucert);
$linhasmenucert = pg_numrows($resultmenucert);

$sqlmenuissqn  = " SELECT i.descricao,                                                ";
$sqlmenuissqn .= "        i.libcliente,                                               ";
$sqlmenuissqn .= "        p.id_usuario,                                               ";
$sqlmenuissqn .= "        nome,                                                       ";
$sqlmenuissqn .= "        login                                                       ";
$sqlmenuissqn .= "   FROM db_menu m                                                   ";
$sqlmenuissqn .= "        INNER JOIN db_permissao p ON p.id_item = m.id_item_filho    ";
$sqlmenuissqn .= "        INNER JOIN db_itensmenu i ON i.id_item = m.id_item_filho    "; 
$sqlmenuissqn .= "                                 AND p.anousu = $ano                ";
$sqlmenuissqn .= "                                 AND p.id_instit =$instit           ";
$sqlmenuissqn .= "        INNER JOIN db_usuarios  u ON u.id_usuario = p.id_usuario    ";
$sqlmenuissqn .= "  WHERE i.itemativo   = 1 ";
$sqlmenuissqn .= "    AND id_item_filho = 5465 $where";

$resultmenuissqn = db_query ( $sqlmenuissqn );
$linhasmenuissqn = pg_num_rows ( $resultmenuissqn );

$sqlmenuretido  = " SELECT i.descricao,                                                ";
$sqlmenuretido .= "        i.libcliente,                                               ";
$sqlmenuretido .= "        p.id_usuario,                                               ";
$sqlmenuretido .= "        nome,                                                       ";
$sqlmenuretido .= "        login                                                       ";
$sqlmenuretido .= "   FROM db_menu m                                                   ";
$sqlmenuretido .= "        INNER JOIN db_permissao p ON p.id_item = m.id_item_filho    ";
$sqlmenuretido .= "        INNER JOIN db_itensmenu i ON i.id_item = m.id_item_filho    ";
$sqlmenuretido .= "                                 AND p.anousu = $ano                ";
$sqlmenuretido .= "                                 AND p.id_instit =$instit           ";
$sqlmenuretido .= "        INNER JOIN db_usuarios u  ON u.id_usuario = p.id_usuario    ";
$sqlmenuretido .= "  WHERE i.itemativo = 1                                             ";
$sqlmenuretido .= "    AND id_item_filho=5466 $where                                   ";

$resultmenuretido = db_query ( $sqlmenuretido );
$linhasmenuretido = pg_num_rows ( $resultmenuretido );

if (isset ( $referencia ) and $referencia != "") {	
	$sqlref = "select * from iptuant where j40_refant = '$referencia' ";
	$resultref = db_query ( $sqlref );
	$linhasref = pg_num_rows ( $resultref );
	
	if ($linhasref > 0) {
		db_fieldsmemory ( $resultref, 0 );
		$matricula1 = $j40_matric;
		$matricula = $j40_matric;
	}

}

if (isset ( $inscricao )) {
	$q02_inscr = $inscricao;
}

//busca dados para armazemar em cookies
if (@$_COOKIE ["cookie_codigo_cgm"] == "") {

	// issbase
	if (@$inscricaow != "") {
      @$result = $clissbase->sql_record($clissbase->sql_query("", 
                                                              "cgm.z01_numcgm,cgm.z01_nome", 
                                                              "", 
                                                              "issbase.q02_inscr = $inscricaow and trim(cgm.z01_cgccpf) = '$cgccpf'"));
	  @$linhas1 = $clissbase->numrows;
	  
	} else if ( !empty ( $codigo_cgm ) || !empty ( $cgc ) || !empty ( $cpf ) ) { //CGM
	   if ( !empty ( $codigo_cgm )){ 
	      $condicao = "cgm.z01_numcgm = $codigo_cgm"; 
	   } else if ( !empty ($cgccpf)) { 
	      $condicao = "trim(cgm.z01_cgccpf) = '$cgccpf'";
	   }
	   
     @$result  = $clcgm->sql_record($clcgm->sql_query("", "cgm.z01_numcgm,cgm.z01_nome", "", $condicao));
	 @$linhas2 = $clcgm->numrows;
	 
	} else if (@$matricula1 != "") { 
		//Matricula
		$sql_exe  = " select cgm.*															   ";
		$sql_exe .= "   from fc_busca_envolvidos(false,{$db21_regracgmiptu},'M',{$matricula1}) ";
		$sql_exe .= "		 inner join cgm on z01_numcgm = rinumcgm				           ";
		$sql_exe .= "  where rimatric = {$matricula1}		 								   ";
		
     if ($sConfig["w13_exigecpfcnpjmatricula"] == "t") {
       $sql_exe .= " and trim(z01_cgccpf) = '{$cgccpf}' ";
     }
     
	 $result = db_query ( $sql_exe );
	 $linhas3 = pg_num_rows ( $result );

	}
	
	if (@$linhas1 != 0 || @$linhas2 != 0 || @$linhas3 != 0)
	   @db_fieldsmemory ( $result, 0 );
	   @setcookie ( "cookie_codigo_cgm", $z01_numcgm );
	   @setcookie ( "cookie_nome_cgm", $z01_nome );
	   @$cookie_codigo_cgm = $z01_numcgm;
} else {
	 @$cookie_codigo_cgm = $_COOKIE ["cookie_codigo_cgm"];
}
?>
<div id='int_perc1' align="left"
	 style="position: absolute; top: 30%; left: 35%; float: left; width: 200; background-color: #ECEDF2; 
	        padding: 5px; margin: 0px; border: 0px #C2C7CB solid; margin-left: 10px; font-size: 80%; visibility: hidden">
  <div style="border: 0px #ffffff solid; margin: 8px 3px 3px 3px;">
    <div id='int_perc2' style="width: 100%; background-color: #eaeaea;" align="center">
	  <img src="imagens/processando.gif" align="center"> Processando...
    </div>
  </div>
</div>
<script>
  document.getElementById('int_perc1').style.visibility='visible';
</script>
<?
if ($opcao == 'n') {
	$arquivosel = 'certidaonome003.php';
} elseif ($opcao == 'm') {
	$arquivosel = 'certidaoimovel003.php';
} elseif ($opcao == 'i') {
   $arquivosel = "certidaoinscr003.php";
}

echo "<script>";
echo "   function js_voltar(){ ";
if (isset ($iLogin) && $iLogin != "") {
	echo "   location.href = '" . $arquivosel . "?id_usuario=" . $iLogin . "';";
} else {
	echo "	 document.cookie = 'cookie_codigo_cgm = ';";
	echo "	 location.href = '" . $arquivosel . "';";
}
echo "	 }";
echo "</script>";

//$sqlMenuPref  = " SELECT distinct m_publico,                              ";
//$sqlMenuPref .= "             m_arquivo,                                  ";
//$sqlMenuPref .= "             m_descricao                                 ";
//$sqlMenuPref .= "   FROM db_menupref                                      ";
//$sqlMenuPref .= "  WHERE m_arquivo = '$arquivosel'                        ";
//$sqlMenuPref .= "  ORDER BY m_descricao                                   ";
//
//$result1 = db_query($sqlMenuPref);
//db_fieldsmemory ( $result1, 0 );

//if ($m_publico != 't') {
//	if (! session_is_registered ( "DB_acesso" ))
//		echo "<script>location.href='centro_pref.php?" . base64_encode ( 'erroscripts=3' ) . "'</script>";
//}

//mens_help();
$script = false;
if (isset ( $codigo_cgm ) && $codigo_cgm == "") {
	?>
<script>alert("Dados Inválidos. Verifique!"); history.back();</script>
<?

}

if (!isset ( $opcao )) {
	if ( !isset ( $HTTP_POST_VARS ["opcao"] )) {
		db_logs ( "", "", 0, "Acesso a Rotina Invalida. - Variável opcao nao setada" );
?>
 <script>
   alert("Acesso a Rotina Iválida. Verifique!"); 
   history.back();
 </script>
<?
	} else {
		$opcao = $HTTP_POST_VARS ["opcao"];
	}
}
$db_verifica_ip = db_verifica_ip ();

// PESQUISA MATRICULA
if ($opcao == "m") {
//	$Caminho = "&nbsp;<a href=\"javascript:history.back()\" class=\"links\">Imóvel &gt;</a>
//	                &nbsp;<font class=\"links\">Opções Imóvel &gt;</font>\n";
	db_logs ( "", "", 0, "Listando debitos - consulta por matrícula." );
	db_mensagem ( "opcoesmatricula_cab", "opcoesmatricula_rod" );
	
	if (! isset ( $matricula )) {
	    if (! isset ( $matricula1 )) {
			db_logs ( "", "", 0, "Acesso a Rotina Invalida." );
			db_redireciona ( "centro_pref.php?" . base64_encode ( 'erroscripts=Acesso a Rotina Inválido.' ) );
		}
		$matricula = trim ( $matricula1 );
		if (! empty ( $cgc )) {
			$cgccpf = $cgc;
		} else {
			if (! empty ( $cpf )) {
				$cgccpf = $cpf;
			} else if (isset ( $z01_cgccpf ) and $z01_cgccpf != "") {
				$cgccpf = $z01_cgccpf;
			} else {
				$cgccpf = "";
			}
		
		}
		$cgccpf = str_replace ( ".", "", $cgccpf );
		$cgccpf = str_replace ( "/", "", $cgccpf );
		$cgccpf = str_replace ( "-", "", $cgccpf );
		
		if (! isset ( $matricula ) or empty ( $matricula ) or ! is_int ( 0 + $matricula )) {
			db_logs ( "", "", 0, "Variável Matricula Invalida." );
			if (isset ( $referencia )) {
				db_redireciona ( "digitamatricula_arapiraca.php?" . base64_encode ( 'erroscripts=Matrícula Inválida.' ) );
			}
			db_redireciona ( $arquivosel . "?" . base64_encode ( 'erroscripts=Matrícula Inválida.' ) );
		}
		if (! isset ( $cgccpf )) {
			db_logs ( "", "", 0, "Variavel CGCCPF Invalida." );
			db_redireciona ( "digitamatricula.php?" . base64_encode ( 'erroscripts=Variável CNPJ/CPF Inválida.' ) );
		}
		$sql_exe1 = "select ident from db_config where codigo = " . db_getsession ( 'DB_instit' );
		
		$result222 = db_query ( $sql_exe1 ) or die ( "Erro: " . pg_ErrorMessage ( $conn ) );
		db_fieldsmemory ( $result222, 0 );
		
		$sWhere = "";
		
		// ---- Verifica se é Imobiliária
		if ( isset($id_usuario) && (trim($id_usuario) != "") && ( trim($matricula) && isset($matricula) != "") ) {
			
			$sSqlImobil  = " select *							 ";
			$sSqlImobil .= "   from imobil					     ";
			$sSqlImobil .= "  where j44_numcgm = {$id_usuario}   ";
			$sSqlImobil .= "	and j44_matric = {$matricula}    ";
			
			$rsImobil = db_query ( $sSqlImobil );
			$iLinhaImobil = pg_num_rows ( $rsImobil );
		
		} else {
			$iLinhaImobil = 0;
		}
		
		    // ---- Caso não seja Imobiliária testa CGCCPF
		    if ( ($iLinhaImobil == 0) && ($sConfig["w13_exigecpfcnpjmatricula"] == "t") && ($db_verifica_ip == "0") ) {
		      $sWhere = " and trim(z01_cgccpf) = '{$cgccpf}'";
		    }

		    
		// ---- Consulta Proprietário e Promitente conforme parametrização
		$sql_exe = " select cgm.*															   ";
		$sql_exe .= "   from fc_busca_envolvidos(false,{$db21_regracgmiptu},'M',{$matricula})  ";
		$sql_exe .= "		 inner join cgm on z01_numcgm = rinumcgm				           ";
		$sql_exe .= "	 where rimatric = {$matricula}										   ";
		
		// ---- Caso regra seja 2 e não retornar nenhum promitente, testar CGM da iptubase
		if ($db21_regracgmiptu == 2) {
			$rsTestaEnvol = db_query ( $sql_exe );
			$iLinhasEnvol = pg_num_rows ( $rsTestaEnvol );
			
			if ($iLinhasEnvol == 0) {
				$sql_exe = " select cgm.*	  													    ";
				$sql_exe .= "		from propri														";
				$sql_exe .= "			 inner join cgm on cgm.z01_numcgm = propri.j42_numcgm       ";
				$sql_exe .= "	   where j42_matric = {$matricula}									";
				$sql_exe .= $sWhere;
				$sql_exe .= "	union																";
				$sql_exe .= " select cgm.*	  														";
				$sql_exe .= "		from iptubase													";
				$sql_exe .= "		     inner join cgm on cgm.z01_numcgm = iptubase.j01_numcgm     ";
				$sql_exe .= "	   where j01_matric = {$matricula}									";
				$sql_exe .= $sWhere;
			} else {
				$sql_exe .= $sWhere;
			}
		}
		else {
			$sql_exe .=	$sWhere;
		}

		$result = db_query ( $sql_exe );
		
	} else {
	    $sqlIptuBase  = " select *                         ";
	    $sqlIptuBase .= "   from iptubase,                 ";
	    $sqlIptuBase .= "        cgm                       ";
	    $sqlIptuBase .= "  where j01_matric = $matricula   ";
	    $sqlIptuBase .= "    and j01_numcgm = z01_numcgm   ";
	                        
		$result = db_query ($sqlIptuBase) or die ( "Erro: " . pg_ErrorMessage ( $conn ) );
		$cgccpf = trim ( pg_result ( $result, 0, 'z01_cgccpf' ) );
	}
	
	$linhasexe = pg_num_rows ( $result );
	
	if ($linhasexe == 0) {
		db_logs ( "$matricula", "", 0, "Dados Inconsistentes. Numero : $matricula" );
		if (isset ( $referencia )) {
		
			db_redireciona ( "digitamatricula_arapiraca.php?" . base64_encode ( 'erroscripts=Os dados informados não conferem, verifique!' ) );
		}
		$script = false;
	} else {
		db_logs ( "$matricula", "", 0, "Matricula Pesquisada. Numero: $matricula" );
	}
	
//PESQUISA INSCRICAO
} else if ($opcao == "i") {
//	$Caminho = "&nbsp;<a href=\"javascript:history.back()\" class=\"links\">Alvará &gt;</a>
//		                &nbsp;<font class=\"links\">Opções Alvará &gt;</font>\n";
	db_logs ( "", "", 0, "Listando debitos - consulta por matrícula." );
	db_mensagem ( "opcoesinscricao_cab", "opcoesinscricao_rod" );
	
	if (! isset ( $inscricao )) {
		if (! isset ( $HTTP_POST_VARS ["inscricaow"] )) {
			db_logs ( "", "", 0, "Acesso a Rotina Invalido" );
			db_redireciona ( "digitainscricao.php?" . base64_encode ( 'erroscripts=Acesso a Rotina Inválido.' ) );
		}
		$inscricao = $HTTP_POST_VARS ["inscricaow"];
		$cgc = $HTTP_POST_VARS ["cgc"];
		$cpf = $HTTP_POST_VARS ["cpf"];
		if (! empty ( $cgc )) {
			$cgccpf = $cgc;
		} else {
			if (! empty ( $cpf )) {
				$cgccpf = $cpf;
			} else {
				$cgccpf = "";
			}
		}
		$cgccpf = str_replace ( ".", "", $cgccpf );
		$cgccpf = str_replace ( "/", "", $cgccpf );
		$cgccpf = str_replace ( "-", "", $cgccpf );
		
		if (! isset ( $inscricao ) or empty ( $inscricao ) or ! is_int ( 0 + $inscricao )) {
			db_logs ( "", "$inscricao", 0, "Variavel Inscricao Invalida" );
			db_redireciona ( "digitainscricao.php?" . base64_encode ( 'erroscripts=Inscrição Inválida.' ) );
		}
		
		if (! isset ( $cgccpf )) {
			db_logs ( "", "$inscricao", 0, "CNPJ/CPF Invalidos" );
			db_redireciona ( "digitainscricao.php?" . base64_encode ( 'erroscripts=Número do CNPJ/CPF Inválido.' ) );
		}
		
		$sql_exe = "select ident from db_config";
		$result = db_query ( $sql_exe ) or die ( "Erro: " . pg_ErrorMessage ( $conn ) );
		
		if (pg_numrows ( $result ) > 0)
			db_fieldsmemory ( $result, 0 );
		    $sql_exe  = " select *                         ";
		    $sql_exe .= "   from issbase,                  ";
		    $sql_exe .= "        cgm                       ";
			$sql_exe .= "  where q02_inscr  = $inscricao   "; 
			$sql_exe .= "    and q02_numcgm = z01_numcgm   ";
			                 
		if ($db_verifica_ip == "0") {
			$sql_exe = $sql_exe . " and trim(z01_cgccpf) = '$cgccpf'";
		}
		$result = db_query ( $sql_exe ) or die ( "Erro: " . pg_ErrorMessage ( $conn ) );
	} else {
	  if (isset($inscricao) != "") {
	  
	    $sql  = " select * from issbase,                 ";
	    $sql .= "               cgm                      ";
	    $sql .= "   where q02_inscr = {$inscricao} and   "; 
	    $sql .= "	      q02_numcgm = z01_numcgm        ";

		$result = db_query ($sql) or die ( "Erro: " . pg_ErrorMessage ( $conn ) );
	  }
		$cgccpf = trim ( pg_result ( $result, 0, 'z01_cgccpf' ) );
	}
	
	if (pg_numrows ( $result ) == 0) {
		$sUrl = base64_encode ( "id_usuario=''&erroscripts=Dados Inconsistentes na Inscrição Número: " . $inscricao . "." );
		db_logs ( "", "$inscricao", 0, "Dados Inconsistentes na Inscricao Numero: $inscricao" );
		db_redireciona ( "digitainscricao.php?" . $sUrl );
		$script = false;
		
    } else {
        if (pg_result($result, 0, "z01_cgccpf") == "00000000000000" || 
           pg_result($result, 0, "z01_cgccpf") == "              " || 
           trim(pg_result($result, 0, "z01_cgccpf")) != "$cgccpf") {
		   $script = true;
	    }
	    if (pg_numrows ( $result ) > 0) {
	       db_fieldsmemory ( $result, 0 );
	       db_logs ( "", "$inscricao", 0, "Inscricao Pesquisada. Numero: $inscricao" );
	       $inscricaobaixada = @ $q02_dtbaix;
	    }
//	    if (! isset ( $DB_LOGADO ) && $m_publico != 't') {
//		   $sql = "select fc_permissaodbpref(" . db_getsession ( "DB_login" ) . ",2,$inscricao)";
//		   $result = db_query ( $sql );
//		   if (pg_numrows ( $result ) == 0) {
//			  db_redireciona ( "digitainscricao.php?" . base64_encode ( 'erroscripts=Acesso não Permitido. Contate a Prefeitura.' ) );
//			  exit ();
//		   }
//		   $result = pg_result ( $result, 0, 0 );
//		   if ($result == "0") {
//			  db_redireciona ( "digitainscricao.php?" . base64_encode ( 'erroscripts=Acesso não Permitido. Contate a Prefeitura.' ) );
//			  exit ();
//		   }
//	    }
    }
	
// PESQUISA NUMCGM
} else if ($opcao == "n") {
//	$Caminho = "&nbsp;<a href=\"javascript:history.back()\" class=\"links\">Contribuinte &gt;</a>
//			                &nbsp;<font class=\"links\">Opções Contribuinte &gt;</font>\n";
	db_logs ( "", "", 0, "Listando opções de debito - consulta por numcgm." );
	db_mensagem ( "opcoescontribuinte_cab", "opcoescontribuinte_rod" );
	
	if (! isset ( $codigo_cgm ) && @$sConfig["w13_permconscgm"] == "t") {
		if (! isset ( $HTTP_POST_VARS ["codigo_cgm"] )) {
			db_logs ( "", "", $numcgm, "Acesso a Rotina Inválido." );
			db_redireciona ( "certidaonome003.php?" . base64_encode ( 'erroscripts=Código identificador Inválido.' ) );
		}
	}

	if (! empty ( $cgc )) {
		$cgccpf = $cgc;
	} else {
		if (! empty ( $cpf )) {
			$cgccpf = $cpf;
		} else {
			$cgccpf = "xxxxxxxxxxxxxx";
		}
	}
	$cgccpf = str_replace ( ".", "", $cgccpf );
	$cgccpf = str_replace ( "/", "", $cgccpf );
	$cgccpf = str_replace ( "-", "", $cgccpf );
	
	if (! isset ( $cgccpf )) {
		db_logs ( "", "$codigo_cgm", 0, "CNPJ/CPF Invalidos" );
		db_redireciona ( "certidaonome003.php?" . base64_encode ( 'erroscripts=Número do CNPJ/CPF Inválido.' ) );
	}
	
	if (! isset ( $sConfig["w13_permconscgm"] ) or @ $sConfig["w13_permconscgm"] == "f") {
		//inner join iptubase on j01_numcgm = z01_numcgm
		$sql_exe = "select * from cgm
				                   where trim(z01_cgccpf) = '$cgccpf'";
	} else {
		//inner join iptubase on j01_numcgm = z01_numcgm
		$sql_exe = "select * from cgm
				                   where z01_numcgm = $codigo_cgm";
		/**
		 * Removida validacao de IP do usuario
		 */
		//if ($db_verifica_ip == "0") {
			$sql_exe .= "  and trim(z01_cgccpf) = '$cgccpf'";
		//}
	}
    $result = @db_query($sql_exe);
	if (pg_num_rows ( $result ) == 0) {
		db_logs ( "", "", 0, "Contribuinte não Cadastrado ou dados não conferem." );
		db_redireciona ( "certidaonome003.php?" . base64_encode ( "id_usuario=''&erroscripts=Contribuinte não cadastrado ou dados não conferem." ) );
		$script = false;
	} elseif (pg_result ( $result, 0, "z01_cgccpf" ) == "00000000000000" || pg_result ( $result, 0, "z01_cgccpf" ) == "              " || trim ( pg_result ( $result, 0, "z01_cgccpf" ) ) != "$cgccpf") {
		$script = true;
	} elseif (pg_numrows ( $result ) == 1) {
		db_fieldsmemory ( $result, 0 );
		
	//$sql2 = "select * from iptubase where j01_numcgm = $z01_numcgm";
	//$result2 = @db_query($sql2);
	//if(pg_numrows($result2)==0){
	//  db_redireciona("certidao.php");
	//}else{
	// db_redireciona("digitamatricula.php");
	//}
	//exit;
	
	} else
  if (pg_numrows($result) > 1) {
		msgbox ( "Inconsistencia de dados, procure a Prefeitura." );
		setcookie ( "cookie_codigo_cgm" );
		db_redireciona ( "certidaonome003.php?outro" );
		$script = false;
	}
//	if (! isset ( $DB_LOGADO ) && $m_publico != 't') {
//		$sql = "select fc_permissaodbpref(" . db_getsession ( "DB_login" ) . ",1,$codigo_cgm)";
//		$result = db_query ( $sql );
//		if (pg_numrows ( $result ) == 0) {
//			db_redireciona ( "digitacontribuinte.php?" . base64_encode ( 'erroscripts=Acesso não Permitido. Contate a Prefeitura.' ) );
//			exit ();
//		}
//		$result = pg_result ( $result, 0, 0 );
//		if ($result == "0") {
//			db_redireciona ( "digitacontribuinte.php?" . base64_encode ( 'erroscripts=Acesso não Permitido. Contate a Prefeitura.' ) );
//			exit ();
//		}
//	}
}
if (pg_num_rows($result) > 0) {
	db_fieldsmemory ( $result, 0 );
	if (@$codigo_cgm == "")
		@$codigo_cgm = $z01_numcgm;
}
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="include/estilodai.css">

<script language="JavaScript" src="scripts/db_script.js"></script>
<style type="text/css">
<?
db_estilosite();
?>
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"	bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<table align="center" width="60%" border="0" cellspacing="0" cellpadding="0">
	<tr class="bold3">
		<td height="50" align="<?=$DB_align1?>"><?=$DB_mens1?></td>
	</tr>
	<tr>
		<td height="200" align="center" valign="middle"><?
				
		if ($opcao == "i") {
      $sSqlInner  = "inner join arreinscr  on arreinscr.k00_numpre  = arresusp.k00_numpre";
   	  $sSqlWhere  = "k00_inscr = {$inscricao}"; 		
			$result = debitos_tipos_inscricao ( $inscricao );
			$chave = "inscricao=$inscricao";
			$valor = $inscricao;
		}
		if ($opcao == "m") {
      $sSqlInner  = "inner join arrematric on arrematric.k00_numpre = arresusp.k00_numpre";
      $sSqlWhere  = "k00_matric = {$matricula}";     		
		  $result 	  = debitos_tipos_matricula($matricula);
		  $chave 	  = "matricula=$matricula";
		  $valor 	  = $matricula;
		}
		if ($opcao == "n") {
			$result = debitos_tipos_numcgm ( $codigo_cgm );
			$chave = "numcgm=$codigo_cgm";
			$valor = $codigo_cgm;
      $sSqlInner  = "inner join arrenumcgm on arrenumcgm.k00_numpre = arresusp.k00_numpre";
      $sSqlWhere  = "arrenumcgm.k00_numcgm = {$codigo_cgm}";   		 			  
		}
		
	    $sSqlVerificaSuspensao  = " select k00_sequencial 	   ";
	    $sSqlVerificaSuspensao .= "   from arresusp       	   ";
	    $sSqlVerificaSuspensao .= "   	   inner join suspensao on ar18_sequencial = arresusp.k00_suspensao ";
	    $sSqlVerificaSuspensao .= " 	   {$sSqlInner}   	   ";
	    $sSqlVerificaSuspensao .= "  where {$sSqlWhere}   	   ";
	    $sSqlVerificaSuspensao .= "    and ar18_situacao = 1   ";
			$rsVerificaDebitosSuspensos = db_query($sSqlVerificaSuspensao);
			$iNroLinhasDebitosSuspensos = pg_num_rows($rsVerificaDebitosSuspensos);		
			
			
//			//situacao do corte
//			if((isset($matricula)) and ($db21_usasisagua == "t")) {
//				require_once ("agu3_conscadastro_002_classe.php");
//	      $Consulta = new ConsultaAguaBase($matricula);
//	      $sqlcorte = $Consulta->GetAguaCorteMatMovSQL();
//	      $resultcorte = db_query($sqlcorte) or die($sqlcorte);
//	      if (pg_numrows($resultcorte) > 0) {
//	        $mensagemcorte = pg_result($resultcorte, 0, "x43_descr");
//	      }
//	      if(trim($mensagemcorte) != '') {
//	      	$msgcortesituacao = "<font color=\"#FF0000\" size=\"3\"><blink>Corte: $mensagemcorte<blink></font>";
//	      }
//			}			

		if ( $result == true ) {

			$linhas = pg_num_rows ( $result );
			
			?>
		<form name="form1" action="">
		<table width="100%" border="0" bordercolor="#cccccc" cellpadding="5"
			cellspacing="0">
			<tr>
				<td width="100%" nowrap height="28" bgcolor="<?=$w01_corfundomenu?>">
				<table width="100%" border="0" cellpadding="1" cellspacing="0">
					<tr class="texto">
						<td><img src="imagens/icone.gif" border="0"></td>
						<td>CNPJ/CPF: <span class="bold3"><?=$cgccpf?></span><br>
						<? if(@$inscricao!=""){?> Inscrição:&nbsp; <span class="bold3"><?=@$inscricao?></span><br>
						<?}else if(@$matricula!=""){?> Matrícula:&nbsp; <span
							class="bold3"><?=@$matricula?></span><br>
							<?}else if(@$codigo_cgm!=""){?> CGM:&nbsp; <span class="bold3"><?=@$codigo_cgm?></span><br>
							<?}?>
						</td>
						<td>
							<?=@$msgcortesituacao ?>
						</td>
							
					</tr>
				</table>
				</td>
			</tr>
			<?
        
			$aK00_tipo = array();
			$lNome     = false;
			$lEmitir   = true;
			
			for($x = 0; $x < $linhas; $x ++) {
				db_fieldsmemory ( $result, $x );
				$k00_recibodbpref = 1;
				$sqlmostra = "select k00_tipo, k00_descr,k00_recibodbpref from arretipo where k00_tipo = $k00_tipo";
				$resultmostra = db_query ( $sqlmostra );
   			$linhasmostra = pg_num_rows ( $resultmostra );
   			
				if ($linhasmostra > 0) {
					db_fieldsmemory ( $resultmostra, 0 );
					// echo "<br> $k00_descr  = $k00_recibodbpref..tipo. $k00_tipo";
				}
				
				if (@ $k00_tipo == 3)	$k00_agnum = "nivel3";
				
				if (@ $k00_tipo == 3 && @ $id_usuario != "") {
					// se tiver logado e tipo = 3 é issqn variavel
					
					if (($k00_recibodbpref != "3") and ($linhasmenuissqn > 0)) {
						$aK00_tipo[] = $k00_tipo;
						?>
			<tr>
				<td height="28"><a class='links'
					href="cai3_gerfinanc000.php?numcgm=<?=$k00_numcgm?>&tipo=<?=$k00_tipo?>&emrec=<?=$k00_emrec?>&agnum=<?=$k00_agnum?>&agpar=<?=$k00_agpar?>&inscr=<?=@$q02_inscr?>&db_datausu=<?=date ( 'Y-m-d', db_getsession ( 'DB_datausu' ) )?>&id_usuario=<?=@$id_usuario?>&opcao=<?=$opcao?>"><img
					src="imagens/pasta2.gif" border="0"> <?=$k00_descr?></a></td>
			</tr>
			
			<?
					}
          $lNome = true;
				}

				if (@$k00_tipo == 3 && @$id_usuario == "" && @$sConfig["w13_permvarsemlog"] == "f") {
					if ($linhasmenuissqn > 0) {
						//não mostrar isso se o escritorio digitar a inscrição do cliente....
						if (! isset ( $logadoescrito )) {
							if ($k00_recibodbpref != "3") {
								?>

			<?
							}
						}
					}
				} else if ($k00_tipo != 3 || @$sConfig["w13_permvarsemlog"] == "t" && @$id_usuario == "") {
					// se não for variavel e permite mostrar variavel sem log
					

					if ($k00_recibodbpref != "3") {
						//Verifica se usa o modulo agua para fazer as demais verificações
						$lExibe = true;

						//Se utilizar o módulo agua tem que verificar a situação do contribuinte.
						if ($db21_usasisagua == 't'){
							
							if(isset($matricula) and $matricula != '') {
								//Verifica a situação de corte da matrícula em questão.
								require_once ("agu3_conscadastro_002_classe.php");
								$Consulta = new ConsultaAguaBase($matricula);
								$sqlcorte = $Consulta->GetAguaCorteMatMovSQL();
							  $resultcorte = db_query($sqlcorte) or die($sqlcorte);
							  
					      if (pg_numrows($resultcorte) > 0) {
					        $x42_codsituacao = pg_result($resultcorte, 0, "x42_codsituacao");
						      //echo $x42_codsituacao;
						      //Verifico se o codigo da situação da matricula esta na tabela de restriçoes configdbprefagua
						      $w16_recibodbpref = false;
						      $sExibeDebitos = "select w16_recibodbpref 
						      										from configdbprefagua 
						      									where w16_instit = $DB_INSTITUICAO and w16_aguacortesituacao = $x42_codsituacao ";
						      //die($sExibeDebitos);
						      $rsExibeDebitos = db_query($sExibeDebitos);
						      if(pg_num_rows($rsExibeDebitos) > 0){
						      	db_fieldsmemory($rsExibeDebitos,0);
						      }
						      if ($w16_recibodbpref !== false && $w16_recibodbpref == 3){
						      	$lExibe = false;
						      }
						    }
					      
							}
						}
					}
				}
				?>
			</td>
			</tr>
			<?
				
			}
			
		   if ($lEmitir) {
          
          if ($linhasmenucert > 0) {

            if ($opcao == "n") {
            	 if ($lNome || $codigo_cgm != $cookie_codigo_cgm) {
        ?>
						      <tr>
						        <td height="28"><a class='links'
						          href="cai3_certidao.php?numcgm=<?=$k00_numcgm?>"><img
						          src="imagens/folder4.gif" border="0"> Emitir Certidão por Nome</a>
						        </td>
						      </tr>            	 	
        <?    	 	
            	 } else {
            	 	
       ?>
						      <tr>
						        <td height="28"><a class='links'
						          href="cai3_certidao.php?numcgm=<?=$cookie_codigo_cgm?>"> <img
						          src="imagens/folder4.gif" border="0"> Emitir Certidão por Nome</a> </td>
						      </tr>
      <?            	 	
            	 	
            	 }

           }
           if ($opcao == "i") {
       ?>
      <tr>
        <td height="28"><a class="links"
          href="cai3_certidao.php?inscricao=<?=$inscricao?>"> <img
          src="imagens/folder4.gif" border="0"> Emitir Certidão por Inscrição </a> </td>
      </tr>
      <?
          }
          if ($opcao == "m") {
       ?>
      <tr>
        <td height="28"><a class='links'
          href="cai3_certidao.php?matricula=<?=$matricula1?>"> <img
          src="imagens/folder4.gif" border="0"> Emitir Certidão por matricula</a> </td>
      </tr>
      <?
          }
        }           
          
        }

	
			?>
		</table>
		</form>
		<input type="submit" value="Voltar" class="botao"
			onclick="js_voltar();">
 <?
		
		} else {

			?>
		<table width="100%" border="0" bordercolor="#cccccc" cellpadding="5"
			cellspacing="0">
			<tr>
				<td width="100%" nowrap height="28" bgcolor="#eaeaea">
				<table width="100%" border="0" cellpadding="1" cellspacing="0">
					<tr class="texto">
						<td><img src="imagens/icone.gif" border="0"></td>
						<td>CNPJ/CPF: <span class="bold3"><?=@$cgccpf?></span><br>
            <? if(@$inscricao!=""){?> Inscrição:&nbsp; <span
							class="bold3"><?=@$inscricao?></span><br>
						<?}else if(@$matricula!=""){?> Matrícula:&nbsp; <span
							class="bold3"><?=@$matricula?></span><br>
							<?}else if(@$codigo_cgm!=""){?> CGM:&nbsp; <span class="bold3"><?=@$codigo_cgm?></span><br>
							<?}?>						
              </span>
            </td>
            <td><?=@$msgcortesituacao?></td>
					</tr>
				</table>
				</td>
			</tr>
       <?
			if ($linhasmenucert > 0) {

				if ($opcao == "n") {
				  if ( $codigo_cgm != $cookie_codigo_cgm ) {
				   ?>
            <tr>
             <td height="28"><a class='links' href="cai3_certidao.php?numcgm=<?=$codigo_cgm?>"> 
             <img src="imagens/folder4.gif" border="0"> Emitir Certidão</a> </td>
            </tr>
           <?
				  } else {
					  ?>
			      <tr>
				     <td height="28"><a class='links' href="cai3_certidao.php?numcgm=<?=$cookie_codigo_cgm?>"> 
				     <img src="imagens/folder4.gif" border="0"> Emitir Certidão</a> </td>
		    	  </tr>
			    <?  
				  }
			
				}
				if ($opcao == "i") {
					?>
			<tr>
				<td height="28"><a class="links"
					href="cai3_certidao.php?inscricao=<?=$inscricao?>"> <img
					src="imagens/folder4.gif" border="0"> Emitir Certidão por Inscrição	</a> </td>
			</tr>
			<?
				}
				if ($opcao == "m") {
					?>
      <tr>
				<td height="28"><a class='links'
					href="cai3_certidao.php?matricula=<?=$matricula1?>"> <img
					src="imagens/folder4.gif" border="0"> Emitir Certidão por matricula</a>	</td>
			</tr>
			<?
				}
			}
			?>
			<tr>
				<td align="center">
				  <input type="submit" value="Voltar" class="botao" onclick="js_voltar();">
				</td>
			</tr>
		</table>
		<?
		
		}
		?>
   </td>
	</tr>
	<tr>
		<td height="60" align="<?=$DB_align2?>"><?=$DB_mens2?></td>
	</tr>
</table>
</center>
<?php 
	if(count($aK00_tipo)>0){
		$virgula = "";
		$sK00_tipo = "";
		foreach ($aK00_tipo as $value){
			$sK00_tipo .= $virgula.$value;
			$virgula = ","; 
		}
	}
?>
<input type="hidden" name="sK00_tipo" id="sK00_tipo" value="<?php echo $sK00_tipo; ?>">
<?

if ($script == true) {
	echo "<script>alert('$MensCgcCpf')</script>\n";
}
if (isset ( $erroscript )) {
	echo "<script>alert('$erroscript')</script>\n";
}
?>
<script>
  document.getElementById('int_perc1').style.visibility='hidden';
</script>