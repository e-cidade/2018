<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_stdlibwebseller.php");

db_postmemory($HTTP_POST_VARS);

$oDaoAluno       = db_utils::getdao('aluno');
$oDaoAlunoBairro = db_utils::getdao('alunobairro');
$oDaoPais        = db_utils::getdao('pais');
$oDaoEscola      = db_utils::getdao('escola');

$db_opcao        = 1;
$db_opcao1       = 1;
$db_botao        = true;

$sSqlEscola      = $oDaoEscola->sql_query("",
                                          "ed261_c_nome as ed47_i_censomunicend,ed260_c_sigla as ed47_v_uf,ed18_c_cep as ed47_v_cep",
                                          "",
                                          " ed18_i_codigo = ".db_getsession("DB_coddepto").""
                                         );
$rsSqlEscola     = $oDaoEscola->sql_record($sSqlEscola);

db_fieldsmemory($rsSqlEscola, 0);

$ed47_d_cadast_dia = date("d");
$ed47_d_cadast_mes = date("m");
$ed47_d_cadast_ano = date("Y");
$ed47_d_ultalt_dia = date("d");
$ed47_d_ultalt_mes = date("m");
$ed47_d_ultalt_ano = date("Y");

if (isset($incluir)) {

  if($ed47_v_nome != "") {

    $sSqlAluno  = $oDaoAluno->sql_query("",
                                        "ed47_i_codigo as jatem",
                                        "",
                                        " ed47_v_nome = '$ed47_v_nome'"
                                       );
    $rsSqlAluno = $oDaoAluno->sql_record($sSqlAluno);
    
    if ($oDaoAluno->numrows > 0) {
    
      db_fieldsmemory($rsSqlAluno, 0);
      db_msgbox("Este nome ($ed47_v_nome) já possui cadastro! Redirecionando para visualização...");
      db_redireciona("edu1_alunodadosfora002.php?chavepesquisa=$jatem");
    
    } else {
      
      db_inicio_transacao();
      
      $oDaoAluno->incluir($ed47_i_codigo);
      
      db_fim_transacao();
    
    }

  } else {
    
    db_inicio_transacao();
    
    $oDaoAluno->incluir($ed47_i_codigo);
    
    db_fim_transacao();
  
  }

  if ($oDadoAluno->erro_status != 0) {
    
    if ($j13_codi != "") {
      
      db_inicio_transacao();
      
      $oDaoAlunoBairro->ed225_i_aluno  = $ed47_i_codigo;
      $oDaoAlunoBairro->ed225_i_bairro = $j13_codi;
      $oDaoAlunoBairro->incluir(null);
      
      db_fim_transacao();
  
    }

  }

  $db_botao = false;

} elseif (isset($chavepesquisa)) {

  db_redireciona("edu1_alunodadosfora002.php?chavepesquisa=$chavepesquisa");

}

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
          <center>
            <fieldset style="width:95%"><legend><b>Inclusão de Aluno</b></legend>
              <?
                include("forms/db_frmalunodadosfora.php");
              ?>
            </fieldset>
          </center>
        </td>
      </tr>
    </table>
  </body>
</html>

<?

if (isset($incluir)) {

  if ($oDaoAluno->erro_status == "0") {
  
    $oDaoAluno->erro(true, false);
    $db_botao = true;
 
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  
    if ($oDaoAluno->erro_campo != "") {

      echo "<script> document.form1.".$oDaoAluno->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoAluno->erro_campo.".focus();</script>";
  
    }

  } else {
  
    $oDaoAluno->erro(true, false);
    db_redireciona("edu1_alunodadosfora002.php?chavepesquisa=$ultimo");
  
  }

}

?>