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
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

require_once("classes/db_processoforocodforoant_classe.php");
require_once("classes/db_processoforo_classe.php");
require_once("classes/db_processoforonumcgm_classe.php");

$oPost                    = db_utils::postMemory($_POST);
$oGet                     = db_utils::postMemory($_GET);
$clprocessoforocodforoant = new cl_processoforocodforoant();
$clprocessoforo           = new cl_processoforo();
$clprocessoforonumcgm     = new cl_processoforonumcgm();
$db_opcao                 = 1;
$db_botao                 = true;
$lSqlErro                 = false;

if (isset($oPost->incluir)) {
	
	if (!$lSqlErro) {
		
	  db_inicio_transacao();
    
	  $clprocessoforo->v70_codforo         = $oPost->v70_codforo;
	  $clprocessoforo->v70_processoforomov = null;
	  $clprocessoforo->v70_vara            = $oPost->v70_vara;
	  $clprocessoforo->v70_id_usuario      = db_getsession('DB_id_usuario');
	  $clprocessoforo->v70_data            = $oPost->v70_data;
	  $clprocessoforo->v70_valorinicial    = $oPost->v70_valorinicial;
	  $clprocessoforo->v70_observacao      = $oPost->v70_observacao;
	  $clprocessoforo->v70_anulado         = 'false';
	  $clprocessoforo->v70_instit          = db_getsession('DB_instit');
	  $clprocessoforo->v70_cartorio        = $oPost->v82_sequencial;
	  $clprocessoforo->incluir(null);
	  
	  $sMsgErro   = $clprocessoforo->erro_msg;
	  if ($clprocessoforo->erro_status == "0") {
	  	$lSqlErro = true;
	  } else {
	  	
	  	/**
	  	 * Caso a inclusão do processo seja efetuada inclui codigo anterior como "0" na 
	  	 * tabela processoforocodforoant.
	  	 */
	  	if ($lSqlErro == false) {

        $clprocessoforocodforoant->v85_processoforo = $clprocessoforo->v70_sequencial;
        $clprocessoforocodforoant->v85_data         = date("Y-m-d",db_getsession('DB_datausu'));
        $clprocessoforocodforoant->v85_codforo      = "0";
        $clprocessoforocodforoant->incluir(null);
        
        //$sMsgErro = $clprocessoforocodforoant->erro_msg;
        
		    if ($clprocessoforocodforoant->erro_status == "0") {
		      $lSqlErro = true;
		    }
	  	}
    
      /**
       * Caso exista número do cgm faz vinculo do processo com o cgm
       */
	    if ( $lSqlErro == false && $oPost->v75_numcgm != "") {
	      
	      $clprocessoforonumcgm->v75_seqprocforo = $clprocessoforo->v70_sequencial;
	      $clprocessoforonumcgm->v75_numcgm      = $oPost->v75_numcgm;
	    
	      $clprocessoforonumcgm->incluir(null);
	    
	      $sMsgErro   = $clprocessoforonumcgm->erro_msg;
	      
	      if ($clprocessoforonumcgm->erro_status == "0") {        
	        $lSqlErro = true;
	      }
	      
	    }
	    
	  }

	  db_fim_transacao($lSqlErro);

	}
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?   
  db_app::load("scripts.js, strings.js, prototype.js");
  db_app::load("estilos.css, grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC>
        <?
          include("forms/db_frminicialcodforo.php");
        ?>
</body>
<?
if (isset($oPost->incluir)) {

  if ($lSqlErro) {
  	
  	db_msgbox($sMsgErro);
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clprocessoforo->erro_campo != "") {
      
      echo "<script> document.form1.".$clprocessoforo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprocessoforo->erro_campo.".focus();</script>";
    }
  } else {
    // Evandro incluir essa linha abaixo
    $v70_sequencial = $clprocessoforo->v70_sequencial;
  	db_msgbox($sMsgErro);
    echo " <script>                                                                                                                ";
    echo "   parent.iframe_processoforo.location.href      = 'jur1_inicialcodforo005.php?chavepesquisa={$v70_sequencial}';         ";
    echo "   parent.iframe_iniciais.location.href          = 'jur1_processoforoiniciais001.php?v70_sequencial={$v70_sequencial}';  ";
    echo "   parent.document.formaba.iniciais.disabled     = false;                                                                ";
    echo "   parent.document.formaba.processoforo.disabled = false;                                                                ";
    echo "   parent.mo_camada('iniciais');                                                                                         ";
    echo " </script>                                                                                                               ";
  }
}
?>
</html>