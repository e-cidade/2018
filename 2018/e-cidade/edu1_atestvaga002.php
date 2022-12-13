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
require_once("classes/db_atestvaga_classe.php");
require_once("libs/db_jsplibwebseller.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$ed102_d_data_dia = date("d", db_getsession("DB_datausu"));
$ed102_d_data_mes = date("m", db_getsession("DB_datausu"));
$ed102_d_data_ano = date("Y", db_getsession("DB_datausu"));
db_postmemory($HTTP_POST_VARS);
$oDaoAtestVaga = db_utils::getdao("atestvaga");
$oDaoAluno     = db_utils::getdao("aluno");
$db_opcao      = 22;
$db_botao      = false;
$escola        = db_getsession("DB_coddepto");

if (isset($alterar)) {
	
  db_inicio_transacao();
  $db_opcao                       = 2;
  $oDaoAtestVaga->ed102_i_usuario = db_getsession("DB_id_usuario");
  $oDaoAtestVaga->alterar($ed102_i_codigo);
  db_fim_transacao();
  
} elseif (isset($chavepesquisa)) {
	
  $db_opcao      = 2;
  $sSqlAtestVaga = $oDaoAtestVaga->sql_query($chavepesquisa);
  $rsAtestVaga   = $oDaoAtestVaga->sql_record($sSqlAtestVaga);
  db_fieldsmemory($rsAtestVaga, 0);
  $ed52_d_inicio = db_formatar($ed52_d_inicio, 'd');
  $ed52_d_fim    = db_formatar($ed52_d_fim, 'd');
  $sCampos       = " aluno.ed47_i_codigo, aluno.ed47_v_nome, alunocurso.ed56_c_situacao as situacao, ";
  $sCampos      .= " serie.ed11_i_codigo as codigoserie, serie.ed11_c_descr as nomeserie, "; 
  $sCampos      .= " case ";
  $sCampos      .= "  when alunocurso.ed56_i_codigo is not null ";
  $sCampos      .= " then escola.ed18_i_codigo ";
  $sCampos      .= " else null end as codigoescola, "; 
  $sCampos      .= " case ";
  $sCampos      .= " when alunocurso.ed56_i_codigo is not null";
  $sCampos      .= " then escola.ed18_c_nome ";
  $sCampos      .= " else null end as nomeescola, "; 
  $sCampos      .= " cursoedu.ed29_i_codigo as codigocurso, cursoedu.ed29_c_descr as nomecurso, "; 
  $sCampos      .= " calendario.ed52_c_descr as calendario, calendario.ed52_i_ano as anocal, "; 
  $sCampos      .= " to_char((select ed60_d_datamatricula from matricula where ed60_i_aluno = ed56_i_aluno ";
  $sCampos      .= " order by ed60_d_datamatricula desc limit 1), 'DD/MM/YYYY') as datamatricula";
  $sSqlAluno     = $oDaoAluno->sql_query_atestvaga("",$sCampos,"","ed47_i_codigo = $ed102_i_aluno");
  $rsAluno       = $oDaoAluno->sql_record($sSqlAluno);
  db_fieldsmemory($rsAluno, 0);
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
 <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
     <?MsgAviso(db_getsession("DB_coddepto"), "escola");?>
     <br>
     <center>
      <fieldset style="width:95%"><legend><b>Alteração de Atestado de Vaga</b></legend>
       <?include("forms/db_frmatestvagaalt.php");?>
      </fieldset>
     </center>
    </td>
   </tr>
  </table>
 </body>
</html>
<?
if (isset($alterar)) {
	
  if($oDaoAtestVaga->erro_status == "0") {
  	
    $oDaoAtestVaga->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($oDaoAtestVaga->erro_campo != "") {
    	
    	
      echo "<script> document.form1.".$oDaoAtestVaga->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoAtestVaga->erro_campo.".focus();</script>";
      
    }
    
  } else {
  	
    $oDaoAtestVaga->erro(true, false);
    ?>
    <script>
     parent.document.form1.codcaldest.value = <?=$ed102_i_calendario?>;
     parent.document.form1.nomecaldest.value = '<?=$ed52_c_descr?>';
     parent.db_iframe_atestvaga.hide();
    </script>
    <?
    
  }
  
}
?>
<script>
js_tabulacaoforms("form1", "ed102_d_data", true, 1, "ed102_d_data", true);
</script>