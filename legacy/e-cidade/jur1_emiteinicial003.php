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

require ("libs/db_stdlib.php");
require ("libs/db_utils.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_iptubase_classe.php");
include ("classes/db_cgm_classe.php");
include ("classes/db_advog_classe.php");
include ("classes/db_inicial_classe.php");
include ("classes/db_processoforoinicial_classe.php");
include ("classes/db_inicialnomes_classe.php");
include ("classes/db_inicialnumpre_classe.php");
include ("classes/db_inicialcert_classe.php");
include ("classes/db_inicialmov_classe.php");
include ("classes/db_inicialcodforo_classe.php");
include ("classes/db_termoini_classe.php");
include ("classes/db_arrecad_classe.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$botao		= 3;
$opcao    = 1;
$verificachave = true;

$cltermoini				     = new cl_termoini;
$clinicial				     = new cl_inicial;
$clprocessoforoinicial = new cl_processoforoinicial;
$clinicialnomes		     = new cl_inicialnomes;
$clinicialnumpre	     = new cl_inicialnumpre;
$clinicialcert		     = new cl_inicialcert;
$clinicialcodforo      = new cl_inicialcodforo;
$clinicialmov			     = new cl_inicialmov;
$clarrecad				     = new cl_arrecad;
$cladvog					     = new cl_advog;
$clcgm						     = new cl_cgm;

$clrotulo					     = new rotulocampo;

$cladvog->rotulo->label();
$clcgm->rotulo->label("z01_numcgm");
$clcgm->rotulo->label("z01_nome");

$clrotulo->label("v50_inicial");
$clrotulo->label("v50_advog");
$clrotulo->label("v54_descr");
$clrotulo->label("v53_descr");
$clrotulo->label("v50_codlocal");
$clrotulo->label("v51_certidao");

if (!isset ($excluir)) {
  
	if (isset ($inicialini)) {
		$botao = 1;
		$opcao = 3;
		$v50_inicialini = $inicialini;
		$v50_inicialfim = $inicialfim;
		$InicialParc = '';
    $vir = "";
		
		$rsTermoini = $cltermoini->sql_record($cltermoini->sql_query(null, null, "distinct inicial", null, " inicial between $v50_inicialini and $v50_inicialfim"));
		if($cltermoini->numrows != 0){
			$botao    = 3;
			for($i=0; $i< $cltermoini->numrows; $i++){	
		    $oTermoini = db_utils::fieldsMemory($rsTermoini,$i);
				$InicialParc .= $vir.$oTermoini->inicial;
        $vir = ",";

			}
			if($cltermoini->numrows == 1){
				db_msgbox(_M('tributario.juridico.db_frmemiteinicialexc.impossivel_excluir_inicial'));
		  }else{
		    
		    $oParms = new stdClass();
		    $oParms->sInicial = $InicialParc;
				db_msgbox(_M('tributario.juridico.db_frmemiteinicialexc.impossivel_excluir_iniciais', $oParms));
			}
		
		} else {
			
			$sWhere                  = " v71_inicial between $v50_inicialini and $v50_inicialfim and v71_anulado is false";
			$sSqlProcessoForoInicial = $clprocessoforoinicial->sql_query_file(null, "*", null, $sWhere);
			$result_foro             = $clprocessoforoinicial->sql_record($sSqlProcessoForoInicial);
			if ($clprocessoforoinicial->numrows != 0) {
				$botao    = 3;
				db_msgbox(_M('tributario.juridico.db_frmemiteinicialexc.existe_processo_para_inicial'));
		  }
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
<body bgcolor=#CCCCCC>
    <?
    include ("forms/db_frmemiteinicialexc.php"); 
    ?>   
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?
if (isset ($excluir)) {
  
  try { 
    
	  db_inicio_transacao();
    
	  $sqlPardiv = "select v04_tipocertidao as tipocertidao from pardiv where v04_instit  = ".db_getsession('DB_instit') ;
    $rsPardiv  = db_query($sqlPardiv);
	  if (pg_num_rows($rsPardiv) > 0 ) {
	  	$oParDiv = db_utils::fieldsMemory($rsPardiv,0);		
	  }else{
      db_msgbox(_M('tributario.juridico.db_frmemiteinicialexc.configure_parametro'));
	  }
	  
	  for ($iInicial = $v50_inicialini; $iInicial <= $v50_inicialfim; $iInicial ++) {
	  	echo "<script>termo($iInicial,$v50_inicialfim);</script>";
	  	flush();
	  	$v50_inicial = $iInicial;
	  	
	  	$rsProcessoForoInicial = $clprocessoforoinicial->sql_record($clprocessoforoinicial->sql_query_file(null, "v71_processoforo", null, "v71_inicial = $v50_inicial"));
	  	if ($clprocessoforoinicial->numrows > 0) {
         $erro_msg = _M('tributario.juridico.db_frmemiteinicialexc.procedimento_nao_permitido');
         throw new Exception("{$erro_msg}");
	  	}
	  
	  	$clinicialcert->v51_inicial = $v50_inicial;
	  	$clinicialcert->excluir($v50_inicial);
	  	if ($clinicialcert->erro_status == 0) {
	  		$erro_msg = $clinicialcert->erro_msg;
	  		throw new Exception("{$erro_msg}");
	  	}
    
	  	$rsNumpre = $clinicialnumpre->sql_record($clinicialnumpre->sql_query_file(null, "v59_numpre as numpre", null, "v59_inicial=$v50_inicial"));
	  	if ($clinicialnumpre->numrows > 0) {
	  		for ($iNumpre = 0; $iNumpre < $clinicialnumpre->numrows; $iNumpre ++) {
	  			$oDadosNumpre = db_utils::fieldsMemory($rsNumpre, $iNumpre);
	  			$clarrecad->k00_tipo   = $oParDiv->tipocertidao;
	  			$clarrecad->k00_numpre = $oDadosNumpre->numpre;
	  			$clarrecad->alterar_arrecad("k00_numpre=$oDadosNumpre->numpre");
	  			if ($clarrecad->erro_status == 0) {
	  				$erro_msg = $clarrecad->erro_msg;
	  				throw new Exception("{$erro_msg}");
	  			}
	  		}
	  	}
	  	
	  	$clinicialnumpre->excluir(null, "v59_inicial=$v50_inicial");
	  	if ($clinicialnumpre->erro_status == 0) {
	  		$erro_msg = $clinicialnumpre->erro_msg;
	  		throw new Exception("{$erro_msg}");
	  	}

	  	$clinicialnomes->v58_inicial = $v50_inicial;
	  	$clinicialnomes->excluir($v50_inicial);
	  	if ($clinicialnomes->erro_status == 0) {
	  		$erro_msg = $clinicialnomes->erro_msg;
	  		throw new Exception("{$erro_msg}");
	  	}
	  	
	  	$rsMovimento      = $clinicialmov->sql_record($clinicialmov->sql_query_file(null, "v56_codmov", null, "v56_inicial=$v50_inicial"));
	  	$iLinhasMovimento = $clinicialmov->numrows;
	  	if ( $iLinhasMovimento > 0) {
	  		
	  		for ($iMovimento = 0; $iMovimento < $iLinhasMovimento; $iMovimento ++) {
	  			$oMovimento = db_utils::fieldsMemory($rsMovimento, $iMovimento);
	  			$clinicialmov->v56_codmov = $oMovimento->v56_codmov;
	  			$clinicialmov->excluir($oMovimento->v56_codmov);
	  			if ($clinicialmov->erro_status == 0) {
	  				$erro_msg = $clinicialmov->erro_msg;
	  				throw new Exception("{$erro_msg}");
	  			}
	  		}
	  	}

	  	$clinicialcodforo->excluir($v50_inicial);
	    if ($clinicialcodforo->erro_status == 0) {
	  		$erro_msg = $clinicialcodforo->erro_msg;
	  		throw new Exception("{$erro_msg}");
	  	}
	  	
	  	$clinicial->excluir($v50_inicial);
	  	if ($clinicial->erro_status == 0) {
	  		$erro_msg = $clinicial->erro_msg;
	  		throw new Exception("{$erro_msg}");
	  	}
	  		
	  }
	
	  db_fim_transacao(false);
	  
	  db_msgbox(_M('tributario.juridico.db_frmemiteinicialexc.processo_realizado_sucesso'));
	  db_redireciona("jur1_emiteinicial003.php");
  } catch (Exception $oErro) {
    
    db_fim_transacao(true);
    db_msgbox($erro_msg); 
  }
}

$func_iframe = new janela('db_iframe', '');
$func_iframe->posX = 1;
$func_iframe->posY = 20;
$func_iframe->largura = 780;
$func_iframe->altura = 430;
$func_iframe->titulo = 'Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>