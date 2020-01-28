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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($_POST);
db_postmemory($_GET);

$oDaoHistoricoMps = new cl_historicomps();
$oDaoAlunoCurso   = new cl_alunocurso();
$oDaoHistMpsAdap  = new cl_histmpsadap();
$oDaoHistMpsAprov = new cl_histmpsaprov();
$oDaoHistMpsDisc  = new cl_histmpsdisc();

$db_opcao         = 2;
$db_opcao1        = 3;
$db_botao         = false;

$lConfirmouExclusao = false;

if (isset($alterar)) {
	
  $vinculo  = false;
  $db_botao = true;
  
  if ($ed62_c_situacao == "AMPARADO") {
  	
    $ed62_c_resultadofinal = "A";
    if (DiscVinc($ed62_i_codigo) > 0) {
      $vinculo = true;
    }
    
  } else if ( $ed62_c_situacao == "CONCLUÍDO" || $ed62_c_situacao == "RECLASSIFICADO" ) {
    $ed62_c_resultadofinal = $ed62_c_resultadofinal;
  } else {
  	
    $ed62_c_resultadofinal = "R";
    if (DiscVinc($ed62_i_codigo) > 0) {
      $vinculo = true;
    }
    
  }
  
  if ($vinculo == true) {
    db_msgbox("Etapa com situação diferente de CONCLUÍDO ou RECLASSIFICADO não deve ter disciplinas vinculadas!");
  } else {
  	
    db_inicio_transacao();
    $db_opcao  = 2;
    $db_opcao1 = 3;
    $oDaoHistoricoMps->ed62_c_resultadofinal     = $ed62_c_resultadofinal;
    $oDaoHistoricoMps->ed62_lancamentoautomatico = 'false';
    $oDaoHistoricoMps->ed62_observacao           = "{$ed62_observacao}";
    $oDaoHistoricoMps->alterar($ed62_i_codigo);
    db_fim_transacao();
    
  }
  
  $sSqlHistMps = $oDaoHistoricoMps->sql_query_historico($chavepesquisa);
  $rsHistMps   = $oDaoHistoricoMps->sql_record($sSqlHistMps);
  
  if ($oDaoHistoricoMps->numrows > 0) {
    db_fieldsmemory($rsHistMps,  0);
  }
  
  
} else if (isset($chavepesquisa)) {
	
  $db_opcao    = 2;
  $db_opcao1   = 3;
  $sSqlHistMps = $oDaoHistoricoMps->sql_query_historico($chavepesquisa);
  $rsHistMps   = $oDaoHistoricoMps->sql_record($sSqlHistMps);
  
  if ($oDaoHistoricoMps->numrows > 0) {
    
    db_fieldsmemory($rsHistMps,  0);
    
    $sSqlAlunoCurso = $oDaoAlunoCurso->sql_query("",  "ed56_c_situacao",  "",  " ed56_i_aluno = $ed61_i_aluno");
    $rsAlunoCurso   = $oDaoAlunoCurso->sql_record($sSqlAlunoCurso);
    
    if ($oDaoAlunoCurso->numrows > 0) {
    	
      db_fieldsmemory($rsAlunoCurso,  0);
      $situacao = $ed56_c_situacao == "CONCLUÍDO" ? "CONCLUÍDO" : "EM ANDAMENTO";
      
    } else {
      $situacao = "CADASTRADO";
    }
  }
  
  
  $db_botao = true;
}

if ( isset( $excluir ) || ( isset ( $lExcluir ) && $lExcluir ) ) {
	
  db_inicio_transacao();
  
  $db_opcao = 3;
  $lConfirmouExclusao = true;
  
  $oDaoHistMpsAdap->excluir( null, "ed66_i_historicomps = {$ed62_i_codigo}" );
  $oDaoHistMpsAprov->excluir( null, "ed28_i_historicomps = {$ed62_i_codigo}" );
  $oDaoHistMpsDisc->excluir( null, "ed65_i_historicomps = {$ed62_i_codigo}" );
  $oDaoHistoricoMps->excluir($ed62_i_codigo);
  
  db_fim_transacao();
  
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
 <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
     <center>
      <fieldset style="width:95%;"><legend><b>Etapa cursada na Rede Municipal</b></legend>
       <?include("forms/db_frmhistoricomps.php");?>
      </fieldset>
     </center>
    </td>
   </tr>
  </table>
 </body>
</html>
<?
if (isset($alterar)) {
	
  if ($oDaoHistoricoMps->erro_status == "0") {
  	
    $oDaoHistoricoMps->erro(true,  false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($oDaoHistoricoMps->erro_campo != "") {
    	
      echo "<script> document.form1.".$oDaoHistoricoMps->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoHistoricoMps->erro_campo.".focus();</script>";
      
    }
    
  } else {
  	
    $oDaoHistoricoMps->erro(true,  false);
    ?>
    <script>
      location.href               = "edu1_histmpsdisc002.php?ed65_i_historicomps=<?=$ed62_i_codigo?>";
      parent.arvore.location.href = "edu1_historicoarvore.php?ed61_i_aluno=<?=$ed61_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>";
    </script>
   <?
  }
  
}

if ( isset($excluir) || $lConfirmouExclusao ) {
	
  if ($oDaoHistoricoMps->erro_status == "0") {
    $oDaoHistoricoMps->erro(true,  false);
  } else {
  	
    $oDaoHistoricoMps->erro(true,  false);
    ?>
    <script>
     location.href               = "edu1_historicomps001.php?ed62_i_historico=<?=$ed62_i_historico?>"+
                                   "&ed29_c_descr=<?=$ed29_c_descr?>&ed29_i_codigo=<?=$ed29_i_codigo?>"+
                                   "&ed61_i_aluno=<?=$ed61_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>";
     parent.arvore.location.href = "edu1_historicoarvore.php?ed61_i_aluno=<?=$ed61_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>";
    </script>
    <?
    
  }
  
}

function DiscVinc($ed62_i_codigo) {
	
 
 $sSqlHistMpsDisc    = $oDaoHistMpsDisc->sql_query_file("",   "ed65_i_codigo",   "",  "ed65_i_historicomps = $ed62_i_codigo");
 $rsHistMpsDisc      = $oDaoHistMpsDisc->sql_record($sSqlHistMpsDisc);
 $iLinhasHistMpsDisc = $oDaoHistMpsDisc->numrows;
 return $iLinhasHistMpsDisc;
 
}
?>