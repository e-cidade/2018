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
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_jsplibwebseller.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$oDaoAtestVaga = db_utils::getdao("atestvaga");
$db_botao      = false;
$db_opcao      = 33;
$escola        = db_getsession("DB_coddepto");

if (isset($excluir)) {
	
  $db_opcao = 3;
  db_inicio_transacao();
  $oDaoAtestVaga->excluir($ed102_i_codigo);
  db_fim_transacao();
  
} elseif (isset($chavepesquisa)) {
	
  $db_opcao      = 3;
  $sSqlAtestVaga = $oDaoAtestVaga->sql_query($chavepesquisa);
  $rsAtestvaga   = $oDaoAtestVaga->sql_record($sSqlAtestVaga);
  db_fieldsmemory($rsAtestvaga,0);
  $ed52_d_inicio = db_formatar($ed52_d_inicio,'d');
  $ed52_d_fim    = db_formatar($ed52_d_fim,'d');
  $sSql          = " SELECT * ";
  $sSql         .= "       FROM ( ";
  $sSql         .= "              SELECT distinct on (aluno.ed47_i_codigo) aluno.ed47_i_codigo,";
  $sSql         .= "                                  aluno.ed47_v_nome, ";
  $sSql         .= "                                  alunocurso.ed56_c_situacao as situacao,"; 
  $sSql         .= "                                  serie.ed11_i_codigo as codigoserie, ";
  $sSql         .= "                                  serie.ed11_c_descr as nomeserie, ";
  $sSql         .= "                                  case";
  $sSql         .= "                                    when alunocurso.ed56_i_codigo is not null";
  $sSql         .= "                                  then escola.ed18_i_codigo";
  $sSql         .= "                                    else null end as codigoescola,"; 
  $sSql         .= "                                  case";
  $sSql         .= "                                    when alunocurso.ed56_i_codigo is not null";
  $sSql         .= "                                  then escola.ed18_c_nome";
  $sSql         .= "                                    else null end as nomeescola,"; 
  $sSql         .= "                                  cursoedu.ed29_i_codigo as codigocurso,"; 
  $sSql         .= "                                  cursoedu.ed29_c_descr as nomecurso, ";
  $sSql         .= "                                  calendario.ed52_c_descr as calendario,"; 
  $sSql         .= "                                  calendario.ed52_i_ano as anocal, ";
  $sSql         .= "                                  to_char((select ed60_d_datamatricula from matricula where ";
  $sSql         .= "                                                  ed60_i_aluno = ed56_i_aluno "; 
  $sSql         .= "                                                  order by ed60_d_datamatricula desc limit 1), ";
  $sSql         .= "                                                  'DD/MM/YYYY') as datamatricula";
  $sSql         .= "                     FROM aluno";
  $sSql         .= "                          left join alunocurso on alunocurso.ed56_i_aluno = aluno.ed47_i_codigo";
  $sSql         .= "                          left join escola on escola.ed18_i_codigo = alunocurso.ed56_i_escola";
  $sSql         .= "                          left join calendario on  calendario.ed52_i_codigo = alunocurso.ed56_i_calendario";
  $sSql         .= "                          left join base on  base.ed31_i_codigo = alunocurso.ed56_i_base";
  $sSql         .= "                          left join cursoedu on  cursoedu.ed29_i_codigo = base.ed31_i_curso";
  $sSql         .= "                          left join alunopossib on  alunopossib.ed79_i_alunocurso = alunocurso.ed56_i_codigo";
  $sSql         .= "                          left join serie on  serie.ed11_i_codigo = alunopossib.ed79_i_serie";
  $sSql         .= "                     WHERE ed47_i_codigo = $ed102_i_aluno";
  $sSql         .= "                           AND ed56_i_escola != $escola";
  $sSql         .= "                           AND ed56_c_situacao != 'TRANSFERIDO REDE'";
  $sSql         .= "                           AND ed56_c_situacao != 'TRANSFERIDO FORA'";
  $sSql         .= "                           AND ed56_c_situacao != 'CANDIDATO'";
  $sSql         .= "                           AND ed56_c_situacao != 'FALECIDO'";
  $sSql         .= "            )as x ORDER BY ed47_v_nome";
  $rsAluno       = pg_query($sSql);
  
  if (pg_num_rows($rsAluno) > 0) {
    db_fieldsmemory($rsAluno, 0); 	
  }
  
  $db_botao = true;
  
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
 <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
   <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
   </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
     <?MsgAviso(db_getsession("DB_coddepto"), "escola");?>
     <br>
     <center>
      <fieldset style="width:95%"><legend><b>Exclusão de Atestado de Vaga</b></legend>
       <?include("forms/db_frmatestvaga.php");?>
      </fieldset>
     </center>
    </td>
   </tr>
  </table>
   <?
     db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), 
             db_getsession("DB_anousu"), db_getsession("DB_instit")
            );
   ?>
 </body>
</html>
<?
if (isset($excluir)) {
	
  if ($oDaoAtestVaga->erro_status == "0") {
    $oDaoAtestVaga->erro(true, false);
  } else {
    $oDaoAtestVaga->erro(true, true);
  }
  
}

if ($db_opcao == 33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1", "excluir", true, 1, "excluir", true);
</script>