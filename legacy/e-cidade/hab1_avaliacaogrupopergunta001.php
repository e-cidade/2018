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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_avaliacaogrupopergunta_classe.php"));
require_once(modification("classes/db_avaliacaopergunta_classe.php"));
require_once(modification("classes/db_avaliacaoresposta_classe.php"));
require_once(modification("classes/db_avaliacaoperguntaopcao_classe.php"));
require_once(modification("classes/db_avaliacao_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clavaliacao               = new cl_avaliacao;
$clavaliacaopergunta       = new cl_avaliacaopergunta;
$clavaliacaogrupopergunta  = new cl_avaliacaogrupopergunta;
$clavaliacaoperguntaopcao  = new cl_avaliacaoperguntaopcao;
$clavaliacaoresposta       = new cl_avaliacaoresposta;

$db_opcao   = 22;
$db_botao   = false;
$sqlerro    = false;

if (isset($oPost->incluir)) {
	
  if ($sqlerro == false) {
  	
    db_inicio_transacao();

    $sWhereVerificaExistenciaGrupoPerguntaPorIdentificador = " db102_identificador = '{$oPost->db102_identificador}'";
    $sSqlVerificaExistenciaGrupoPerguntaPorIdentificador   = $clavaliacaogrupopergunta->sql_query(null, '*', null, $sWhereVerificaExistenciaGrupoPerguntaPorIdentificador);
    $rsVerificaExistenciaGrupoPerguntaPorIdentificador     = db_query($sSqlVerificaExistenciaGrupoPerguntaPorIdentificador);

    if(!$rsVerificaExistenciaGrupoPerguntaPorIdentificador) {
      $erro_msg = "Ocorreu um erro ao consultar o grupo pelo identificador.";
      $sqlerro  = true;
    }
    
    if(!$sqlerro && pg_num_rows($rsVerificaExistenciaGrupoPerguntaPorIdentificador) > 0) {
      $erro_msg = "Já existe um grupo com o identificador informado.";
      $sqlerro  = true;
    }

    if(!$sqlerro) {

      $clavaliacaogrupopergunta->db102_avaliacao     = $oPost->db102_avaliacao;
      $clavaliacaogrupopergunta->db102_descricao     = $oPost->db102_descricao;
      $clavaliacaogrupopergunta->db102_identificador = $oPost->db102_identificador;
      $clavaliacaogrupopergunta->incluir(null);
      $erro_msg = $clavaliacaogrupopergunta->erro_msg;
      if ($clavaliacaogrupopergunta->erro_status == 0) {
        $sqlerro = true;
      }
    }

    db_fim_transacao($sqlerro);
  }
} else if (isset($oPost->alterar)) {
  
  if ($sqlerro == false) {
    
    db_inicio_transacao();

    $sWhereVerificaExistenciaGrupoPerguntaPorIdentificador  = " db102_identificador = '{$oPost->db102_identificador}'";
    $sSqlVerificaExistenciaGrupoPerguntaPorIdentificador    = $clavaliacaogrupopergunta->sql_query(null, '*', null, $sWhereVerificaExistenciaGrupoPerguntaPorIdentificador);
    $rsVerificaExistenciaGrupoPerguntaPorIdentificador      = db_query($sSqlVerificaExistenciaGrupoPerguntaPorIdentificador);

    if(!$rsVerificaExistenciaGrupoPerguntaPorIdentificador) {
      $erro_msg = "Ocorreu um erro ao consultar o grupo pelo identificador.";
      $sqlerro  = true;
    }
    
    if(!$sqlerro && pg_num_rows($rsVerificaExistenciaGrupoPerguntaPorIdentificador) > 0) {

      if($oPost->db102_sequencial != db_utils::fieldsMemory($rsVerificaExistenciaGrupoPerguntaPorIdentificador, 0)->db102_sequencial) {
        $erro_msg = "Já existe um grupo com o identificador informado.";
        $sqlerro  = true;
      }
    }
    
    if(!$sqlerro) {

      $clavaliacaogrupopergunta->db102_sequencial    = $oPost->db102_sequencial;
      $clavaliacaogrupopergunta->db102_avaliacao     = $oPost->db102_avaliacao;
      $clavaliacaogrupopergunta->db102_descricao     = $oPost->db102_descricao;
      $clavaliacaogrupopergunta->db102_identificador = $oPost->db102_identificador;
      $clavaliacaogrupopergunta->alterar($clavaliacaogrupopergunta->db102_sequencial);
      $erro_msg = $clavaliacaogrupopergunta->erro_msg;
      if ($clavaliacaogrupopergunta->erro_status == 0) {
        $sqlerro = true;
      }
    }
    
    db_fim_transacao($sqlerro);
  }
} else if (isset($oPost->excluir)) {
	
  if ($sqlerro == false) {
  	
    db_inicio_transacao();
    
    $sWhere                 = "db103_avaliacaogrupopergunta = {$oPost->db102_sequencial}";
    $sSqlAvaliacaoResposta  = $clavaliacaoresposta->sql_query(null,"*",null,$sWhere);
    $rsSqlAvaliacaoResposta = $clavaliacaoresposta->sql_record($sSqlAvaliacaoResposta);
    if ($clavaliacaoresposta->numrows > 0) {
      
      $sqlerro  = true;
      $erro_msg = "Existe uma resposta vinculada as perguntas desse grupo. Exclusão Abortada.";
    }
    
    
    if (!$sqlerro) {
            
	    $sWhere                = "db103_avaliacaogrupopergunta = {$oPost->db102_sequencial}";
	    $sSqlAvaliacaoPergunta = $clavaliacaopergunta->sql_query(null,"avaliacaopergunta.*",null,$sWhere);
	    $rsAvaliacaoPergunta   = $clavaliacaopergunta->sql_record($sSqlAvaliacaoPergunta);
	    if ($clavaliacaopergunta->numrows > 0) {
	    	
    		for ($iInd = 0; $clavaliacaopergunta->numrows > $iInd; $iInd++) {
    			
    			$oAvaliacaoPergunta = db_utils::fieldsMemory($rsAvaliacaoPergunta,$iInd);
    			if (!$sqlerro) {
    				
			      $sWherePerguntaOpcao = "db104_avaliacaopergunta = {$oAvaliacaoPergunta->db103_sequencial}";
			      $clavaliacaoperguntaopcao->excluir(null,$sWherePerguntaOpcao);
			      if ($clavaliacaoperguntaopcao->erro_status == 0) {
			      	
			      	$sqlerro  = true;
			      	$erro_msg = $clavaliacaoperguntaopcao->erro_msg;
			      }
    			}
    		}
	    }
	    
    }
    
    if (!$sqlerro) {
    	
	    $sWhere = "db103_avaliacaogrupopergunta = {$oPost->db102_sequencial}";
	    $clavaliacaopergunta->excluir(null,$sWhere);
	    $erro_msg = $clavaliacaopergunta->erro_msg;
	    if ($clavaliacaopergunta->erro_status == 0) {
	      $sqlerro = true;
	    }
    }
    
    if (!$sqlerro) {
    	
	    $clavaliacaogrupopergunta->excluir($oPost->db102_sequencial);
	    $erro_msg = $clavaliacaogrupopergunta->erro_msg;
	    if ($clavaliacaogrupopergunta->erro_status == 0) {
	      $sqlerro = true;
	    }
    }
    
    db_fim_transacao($sqlerro);
  }
}

if (isset($oPost->opcao)) {

  $rsAvaliacaoGrupoPergunta = $clavaliacaogrupopergunta->sql_record($clavaliacaogrupopergunta->sql_query($oPost->db102_sequencial));
  if ($rsAvaliacaoGrupoPergunta != false && $clavaliacaogrupopergunta->numrows > 0) {
    db_fieldsmemory($rsAvaliacaoGrupoPergunta,0);
  }
} else {
	$db102_sequencial = $clavaliacaogrupopergunta->db102_sequencial;
}

if (isset($oGet->db102_avaliacao)) {
  $db102_avaliacao = $oGet->db102_avaliacao;
} else {
  $db102_avaliacao = $oPost->db102_avaliacao;
}
  
$sSelect     = "db101_sequencial as db102_avaliacao,db101_descricao";
$rsAvaliacao = $clavaliacao->sql_record($clavaliacao->sql_query($db102_avaliacao, $sSelect, 
                                                                  "db101_sequencial", ""));
if ($rsAvaliacao != false && $clavaliacao->numrows > 0) {
  db_fieldsmemory($rsAvaliacao,0);
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
<style>
td {
  white-space: nowrap
}

fieldset table td:first-child {
              width: 80px;
              white-space: nowrap
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table border="0" align="center" cellspacing="0" cellpadding="0" >
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td valign="top" bgcolor="#CCCCCC"> 
    <center>
      <?
        include(modification("forms/db_frmavaliacaogrupopergunta.php"));
      ?>
    </center>
  </td>
  </tr>
</table>
</body>
<script> document.form1.db102_descricao.focus();</script>
</html>
<?
if (isset($oPost->alterar) || isset($oPost->excluir) || isset($oPost->incluir)) {
	
  db_msgbox($erro_msg);
  if ($clavaliacaogrupopergunta->erro_campo != "") {
  	
    echo "<script> document.form1.".$clavaliacaogrupopergunta->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clavaliacaogrupopergunta->erro_campo.".focus();</script>";
  }
}
?>