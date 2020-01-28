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


require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_utils.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oDaoAluno      = db_utils::getdao('aluno');
$oDaoAlunoBairo = db_utils::getdao('alunobairro');
$oDaoPais       = db_utils::getdao('pais');

$db_opcao       = 22;
$db_opcao1      = 3;
$db_botao       = false;

if (isset($alterar)) {

  db_inicio_transacao();
  
  $db_opcao                 = 2;
  $db_opcao1                = 3;
  $oDaoAluno->ed47_d_ultalt = date("Y-m-d");
  $oDaoAluno->alterar($ed47_i_codigo);
  
  db_fim_transacao();

  if ($oDaoAluno->erro_status != 0) {

    if ($j13_codi != "") {
      
      $sSqlBairro    = $oDaoAlunoBairro->sql_query("", "*", "", " ed225_i_aluno = $ed47_i_codigo");
      $result_bairro = $oDaoAlunoBairro->sql_record($sSqlBairro);
      
      db_inicio_transacao();
      
      $oDaoAlunoBairro->ed225_i_aluno  = $ed47_i_codigo;
      $oDaoAlunoBairro->ed225_i_bairro = $j13_codi;
      
      if ($oDaoAlunoBairro->numrows > 0) {
          
        db_fieldsmemory($result_bairro, 0);
        $oDaoAlunoBairro->ed225_i_codigo = $ed225_i_codigo;
        $oDaoAlunoBairro->alterar($ed225_i_codigo);
   
      } else {
        $oDaoAlunoBairro->incluir(null);
      }

      db_fim_transacao();
    
    }

  }
  
  $db_botao = true;

} elseif (isset($chavepesquisa)) {

  $db_opcao  = 2;
  $db_opcao1 = 3;
  $sSql      = $oDaoAluno->sql_query($chavepesquisa);
  $rsSql     = $oDaoAluno->sql_record($sSql);
  db_fieldsmemory($rsSql, 0);

  $sSqlBairro    = $oDaoAlunoBairro->sql_query("", "*", "", " ed225_i_aluno = $chavepesquisa");
  $result_bairro = $oDaoAlunoBairro->sql_record($sSqlBairro);
  
  if ($oDaoAlunoBairro->numrows > 0) {
    
    db_fieldsmemory($result_bairro, 0);
    $j13_codi = $ed225_i_bairro;
  
  }

  $db_botao          = true;
  $ed47_d_ultalt_dia = $ed47_d_ultalt_dia == "" ? date("d") : $ed47_d_ultalt_dia;
  $ed47_d_ultalt_mes = $ed47_d_ultalt_mes == "" ? date("m") : $ed47_d_ultalt_mes;
  $ed47_d_ultalt_ano = $ed47_d_ultalt_ano == "" ? date("Y") : $ed47_d_ultalt_ano;
  $ed47_d_cadast_dia = $ed47_d_cadast_dia == "" ? date("d") : $ed47_d_cadast_dia;
  $ed47_d_cadast_mes = $ed47_d_cadast_mes == "" ? date("m") : $ed47_d_cadast_mes;
  $ed47_d_cadast_ano = $ed47_d_cadast_ano == "" ? date("Y") : $ed47_d_cadast_ano;

?>
  <script>
    parent.document.formaba.a2.disabled    = false;
    parent.document.formaba.a2.style.color = "black";
    parent.document.formaba.a3.disabled    = false;
    parent.document.formaba.a3.style.color = "black";
    top.corpo.iframe_a2.location.href='edu1_foraaluno002.php?chavepesquisa=<?=$ed47_i_codigo?>';
    top.corpo.iframe_a3.location.href='edu1_alunofora001.php?ed216_i_aluno=<?=$ed47_i_codigo?>&ed47_v_nome=<?=$ed47_v_nome?>';
  </script>
 <?

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
            <fieldset style="width:95%"><legend><b>Alteração de Aluno</b></legend>
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
if (isset($alterar)) {
    
  if ($oDaoAluno->erro_status == "0") {
      
    $oDaoAluno->erro(true,false);
    $db_botao=true;
      
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  
    if($oDaoAluno->erro_campo != ""){
        
      echo "<script> document.form1.".$oDaoAluno->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoAluno->erro_campo.".focus();</script>";
    
    }   

  } else {

    $oDaoAluno->erro(true, false);
    db_redireciona("edu1_alunodadosfora002.php?chavepesquisa=$ed47_i_codigo");
  
  }

}

if ($db_opcao == 22) {
 echo "<script>document.form1.pesquisar.click();</script>";
}

?>