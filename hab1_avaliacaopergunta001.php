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

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clavaliacaopergunta       = new cl_avaliacaopergunta;
$clavaliacaogrupopergunta  = new cl_avaliacaogrupopergunta;
$clavaliacaotiporesposta   = new cl_avaliacaotiporesposta;
$clavaliacaoperguntaopcao  = new cl_avaliacaoperguntaopcao;
$clavaliacaoresposta       = new cl_avaliacaoresposta;

$db_opcao         = 22;
$db_botao         = false;
$sqlerro          = false;
$lResposta        = false;
$lSqlErro         = false;
$iTipoRespostaAnt = "";

if (isset($oPost->incluir)) {
	
  if ($sqlerro == false) {
  	
    db_inicio_transacao();
    
    $clavaliacaopergunta->db103_avaliacaogrupopergunta = $oPost->db103_avaliacaogrupopergunta;
    $clavaliacaopergunta->db103_avaliacaotiporesposta  = $oPost->db103_avaliacaotiporesposta;
    $clavaliacaopergunta->db103_descricao              = $oPost->db103_descricao;
    $clavaliacaopergunta->db103_identificador          = $oPost->db103_identificador;
    $clavaliacaopergunta->db103_obrigatoria            = $oPost->db103_obrigatoria;
    $clavaliacaopergunta->db103_ativo                  = $oPost->db103_ativo;
    $clavaliacaopergunta->db103_ordem                  = $oPost->db103_ordem;
    $clavaliacaopergunta->incluir(null);
    
    $erro_msg = $clavaliacaopergunta->erro_msg;
    
    if ($clavaliacaopergunta->erro_status == 0) {
      $sqlerro = true;
    }
    
    $iTipoRespostaAnt = $clavaliacaopergunta->db103_avaliacaotiporesposta;
    
		if (isset($oPost->db103_avaliacaotiporesposta)) {
		  
		  if ($oPost->db103_avaliacaotiporesposta == 2) {
		  	
		    if (!$sqlerro) {
		              
		      $sIdentificadorOpcao                               = $oPost->db103_identificador."_".$oPost->db103_avaliacaotiporesposta;
		      $clavaliacaoperguntaopcao->db104_avaliacaopergunta = $clavaliacaopergunta->db103_sequencial;
		      $clavaliacaoperguntaopcao->db104_identificador     = $sIdentificadorOpcao;
		      $clavaliacaoperguntaopcao->db104_aceitatexto       = 'true';
		      $clavaliacaoperguntaopcao->incluir(null);
		      
		      if ($clavaliacaoperguntaopcao->erro_status == 0) {
            
            $sqlerro  = true;
            $erro_msg = $clavaliacaoperguntaopcao->erro_msg;
          }
		    }
		  }
		}
    
    db_fim_transacao($sqlerro);
  }
} else if (isset($oPost->alterar)) {
	
  if ($sqlerro == false) {
  	
    db_inicio_transacao();
    
    $clavaliacaopergunta->db103_sequencial             = $oPost->db103_sequencial;
    $clavaliacaopergunta->db103_avaliacaogrupopergunta = $oPost->db103_avaliacaogrupopergunta;
    $clavaliacaopergunta->db103_avaliacaotiporesposta  = $oPost->db103_avaliacaotiporesposta;
    $clavaliacaopergunta->db103_descricao              = $oPost->db103_descricao;
    $clavaliacaopergunta->db103_identificador          = $oPost->db103_identificador;
    $clavaliacaopergunta->db103_obrigatoria            = $oPost->db103_obrigatoria;
    $clavaliacaopergunta->db103_ativo                  = $oPost->db103_ativo;
    $clavaliacaopergunta->db103_ordem                  = $oPost->db103_ordem;
    $clavaliacaopergunta->alterar($clavaliacaopergunta->db103_sequencial);
    $erro_msg = $clavaliacaopergunta->erro_msg;

    if ($clavaliacaopergunta->erro_status == 0) {
      $lSqlErro = true;
    }

    if ($oPost->db103_avaliacaotiporesposta == 2) {
    		
      	$sWhere                 = "db104_avaliacaopergunta = {$clavaliacaopergunta->db103_sequencial}";
        $sSqlAvaliacaoResposta  = $clavaliacaoresposta->sql_query(null,"*",null,$sWhere);
        $rsSqlAvaliacaoResposta = $clavaliacaoresposta->sql_record($sSqlAvaliacaoResposta);
        if ($clavaliacaoresposta->numrows > 0) {
          
          if ($oPost->iTipoRespostaAnt != $oPost->db103_avaliacaotiporesposta) {
            
            $lSqlErro                    = true;
            $lResposta                   = true;
            $db103_avaliacaotiporesposta = "";
            
            $erro_msg  = "Essa pergunta já possui uma resposta vinculada. \\n";
            $erro_msg .= "Você poderá apenas desativar essa pergunta. \\nAlteração Abortada.";
          }
        }

        db_inicio_transacao();
        
        if (!$lResposta) {
        	
          $sWherePerguntaOpcao = "db104_avaliacaopergunta = {$clavaliacaopergunta->db103_sequencial}";
          $clavaliacaoperguntaopcao->excluir(null,$sWherePerguntaOpcao);
          if ($clavaliacaoperguntaopcao->erro_status == 0) {  
            $lSqlErro  = true;
          }
        }
        
        if (!$lResposta) {
                    
          $clavaliacaoperguntaopcao->db104_avaliacaopergunta = $clavaliacaopergunta->db103_sequencial;
          $clavaliacaoperguntaopcao->db104_aceitatexto       = 'true';
          $clavaliacaoperguntaopcao->incluir(null);
          if ($clavaliacaoperguntaopcao->erro_status == 0) {
            $lSqlErro = true;
          }
        }
        
        db_fim_transacao($lSqlErro);
    	}
    	
    	db_fim_transacao($lSqlErro);
    }

} else if (isset($oPost->excluir)) {
	
  if ($sqlerro == false) {
  	
    db_inicio_transacao();
    
    $sWhere                 = "db104_avaliacaopergunta = {$oPost->db103_sequencial}";
    $sSqlAvaliacaoResposta  = $clavaliacaoresposta->sql_query(null,"*",null,$sWhere);
    $rsSqlAvaliacaoResposta = $clavaliacaoresposta->sql_record($sSqlAvaliacaoResposta);
    
    if ($clavaliacaoresposta->numrows > 0) {
      
      $sqlerro   = true;
      $erro_msg  = "Essa pergunta já possui uma resposta vinculada. \\n";
      $erro_msg .= "Você poderá apenas desativar essa pergunta. \\nExclusão Abortada.";
    }
    
    if (!$sqlerro) {
    	    	
	    $sWherePerguntaOpcao = "db104_avaliacaopergunta = {$oPost->db103_sequencial}";
	    $clavaliacaoperguntaopcao->excluir(null,$sWherePerguntaOpcao);
    }
    
    if (!$sqlerro) {
    	
	    $clavaliacaopergunta->excluir($oPost->db103_sequencial);
	    $erro_msg = $clavaliacaopergunta->erro_msg;
	    if ($clavaliacaopergunta->erro_status == 0) {
	      $sqlerro = true;
	    }
    }
    
    db_fim_transacao($sqlerro);
  }
} 

if (isset($oPost->opcao)) {
	
  $result = $clavaliacaopergunta->sql_record($clavaliacaopergunta->sql_query($oPost->db103_sequencial));
  if($result != false && $clavaliacaopergunta->numrows > 0) {
    
  	db_fieldsmemory($result,0);
    $iTipoRespostaAnt = $db103_avaliacaotiporesposta;
  }
} else {
  $db103_sequencial = $clavaliacaopergunta->db103_sequencial;
}

if (isset($oGet->db103_avaliacaogrupopergunta)) {
  $db103_avaliacaogrupopergunta = $oGet->db103_avaliacaogrupopergunta;
} else {
  $db103_avaliacaogrupopergunta = $oPost->db103_avaliacaogrupopergunta;
}
 

$sSqlGrupoPergunta = $clavaliacaogrupopergunta->sql_query($db103_avaliacaogrupopergunta, "avaliacaogrupopergunta.*", 
                                                          "db102_sequencial", "");
$rsAvaliacaoGrupoPergunta = $clavaliacaogrupopergunta->sql_record($sSqlGrupoPergunta);
if ($rsAvaliacaoGrupoPergunta != false && $clavaliacaogrupopergunta->numrows > 0) {
  db_fieldsmemory($rsAvaliacaoGrupoPergunta,0);
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
              width: 110px;
              white-space: nowrap
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_disbledadicionarresp();" >
<table border="0" align="center" cellspacing="0" cellpadding="0" >
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td valign="top" bgcolor="#CCCCCC"> 
      <?
        include("forms/db_frmavaliacaopergunta.php");
      ?>
    </td>
  </tr>
</table>
</body>
<script> document.form1.db103_descricao.focus();</script>
</html>
<?
if (isset($oPost->alterar) || isset($oPost->excluir) || isset($oPost->incluir)) {
	
  db_msgbox($erro_msg);
  if ($clavaliacaopergunta->erro_campo != "") {
  	
    echo "<script> document.form1.".$clavaliacaopergunta->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clavaliacaopergunta->erro_campo.".focus();</script>";
  }
}
?>