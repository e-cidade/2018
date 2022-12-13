<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
require_once(modification("classes/db_avaliacaoperguntaopcao_classe.php"));
require_once(modification("classes/db_avaliacaopergunta_classe.php"));
require_once(modification("classes/db_avaliacaoresposta_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clavaliacaoperguntaopcao  = new cl_avaliacaoperguntaopcao;
$clavaliacaopergunta       = new cl_avaliacaopergunta;
$clavaliacaoresposta       = new cl_avaliacaoresposta;

$db_opcao = 22;
$db_botao = false;
$sqlerro  = false;

if (isset($oPost->incluir)) {
  
  if ($sqlerro == false) {
    
    db_inicio_transacao();
    
    $clavaliacaoperguntaopcao->db104_avaliacaopergunta = $oPost->db103_sequencial;
    $clavaliacaoperguntaopcao->db104_descricao         = $oPost->db104_descricao;
    $clavaliacaoperguntaopcao->db104_identificador     = $oPost->db104_identificador;
    $clavaliacaoperguntaopcao->db104_aceitatexto       = $oPost->db104_aceitatexto;
    $clavaliacaoperguntaopcao->db104_peso              = $oPost->db104_peso;
    $clavaliacaoperguntaopcao->db104_valorresposta     = $oPost->db104_valorresposta;
    $clavaliacaoperguntaopcao->incluir(null);
    $erro_msg = $clavaliacaoperguntaopcao->erro_msg;
    if ($clavaliacaoperguntaopcao->erro_status == 0) {
      $sqlerro = true;
    }
    
    db_fim_transacao($sqlerro);
  }
} else if (isset($oPost->alterar)) {
  
  if ($sqlerro == false) {
    
    db_inicio_transacao();
    
    $clavaliacaoperguntaopcao->db104_sequencial        = $oPost->db104_sequencial;
    $clavaliacaoperguntaopcao->db104_avaliacaopergunta = $oPost->db103_sequencial;
    $clavaliacaoperguntaopcao->db104_descricao         = $oPost->db104_descricao;
    $clavaliacaoperguntaopcao->db104_identificador     = $oPost->db104_identificador;
    $clavaliacaoperguntaopcao->db104_aceitatexto       = $oPost->db104_aceitatexto;
    $clavaliacaoperguntaopcao->db104_peso              = $oPost->db104_peso;
    $clavaliacaoperguntaopcao->db104_valorresposta     = $oPost->db104_valorresposta;
    $clavaliacaoperguntaopcao->alterar($clavaliacaoperguntaopcao->db104_sequencial);
    $erro_msg = $clavaliacaoperguntaopcao->erro_msg;
    if ($clavaliacaoperguntaopcao->erro_status == 0) {
      $sqlerro = true;
    }
    
    db_fim_transacao($sqlerro);
  }
} else if (isset($oPost->excluir)) {
  
  if ($sqlerro == false) {
    
    db_inicio_transacao();
    
    $sWhere                 = "db106_avaliacaoperguntaopcao = {$oPost->db104_sequencial}";
    $sSqlAvaliacaoResposta  = $clavaliacaoresposta->sql_query(null,"*",null,$sWhere);
    $rsSqlAvaliacaoResposta = $clavaliacaoresposta->sql_record($sSqlAvaliacaoResposta);
    if ($clavaliacaoresposta->numrows > 0) {
    	
    	$sqlerro  = true;
    	$erro_msg = "Essa pergunta já possui uma resposta cadastrada. Exclusão Abortada.";
    }
    
    if (!$sqlerro) {
    	
	    $clavaliacaoperguntaopcao->excluir($oPost->db104_sequencial);
	    $erro_msg = $clavaliacaoperguntaopcao->erro_msg;
	    if ($clavaliacaoperguntaopcao->erro_status == 0) {
	      $sqlerro = true;
	    }
    }
    
    
    db_fim_transacao($sqlerro);
  }
} 

if (isset($oPost->opcao)) {
  
  $result = $clavaliacaoperguntaopcao->sql_record($clavaliacaoperguntaopcao->sql_query($oPost->db104_sequencial));
  if($result != false && $clavaliacaoperguntaopcao->numrows > 0) {
    db_fieldsmemory($result,0);
  }
} else {
  $db104_sequencial = $clavaliacaoperguntaopcao->db104_sequencial;
}



if (isset($oGet->db103_sequencial)) {
  $db103_sequencial = $oGet->db103_sequencial;
} else {
  $db103_sequencial = $oPost->db103_sequencial;
}
 
$sSqlAvaliacaoPergunta = $clavaliacaopergunta->sql_query(null,"*",null,"db103_sequencial = {$db103_sequencial}");
$rsAvaliacaoPergunta   = $clavaliacaopergunta->sql_record($sSqlAvaliacaoPergunta);
if ($rsAvaliacaoPergunta != false && $clavaliacaopergunta->numrows > 0) {
  db_fieldsmemory($rsAvaliacaoPergunta,0);
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
              width: 120;
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
      <?
        include(modification("forms/db_frmavaliacaoperguntaopcao.php"));
      ?>
    </td>
  </tr>
</table>
</body>
<script> document.form1.db104_descricao.focus();</script>
</html>
<?
if (isset($oPost->alterar) || isset($oPost->excluir) || isset($oPost->incluir)) {
  
  db_msgbox($erro_msg);
  if ($clavaliacaoperguntaopcao->erro_campo != "") {
    
    echo "<script> document.form1.".$clavaliacaoperguntaopcao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clavaliacaoperguntaopcao->erro_campo.".focus();</script>";
  }
}
?>