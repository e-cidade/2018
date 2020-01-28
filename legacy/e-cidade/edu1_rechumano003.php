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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clrechumano           = new cl_rechumano;
$cldb_uf               = new cl_db_uf;
$clrhpessoal           = new cl_rhpessoal;
$clrhpesdoc            = new cl_rhpesdoc;
$clrhraca              = new cl_rhraca;
$clrhinstrucao         = new cl_rhinstrucao;
$clrhestcivil          = new cl_rhestcivil;
$clrhnacionalidade     = new cl_rhnacionalidade;
$clpais                = new cl_pais;
$clcensouf             = new cl_censouf;
$clcensoorgemissrg     = new cl_censoorgemissrg;
$clcensomunic          = new cl_censomunic;
$clcensocartorio       = new cl_censocartorio();
$oDaoRecHumanoEscola   = new cl_rechumanoescola();
$oDaoRecHumanoAtiv     = new cl_rechumanoativ();
$oDaoRecHumanoHoraDisp = new cl_rechumanohoradisp();
$oDaoRelacaoTrabalho   = new cl_relacaotrabalho();

$db_botao      = false;
$db_opcao      = 33;
$db_opcao1     = 3;
$iEscola       = db_getsession( "DB_coddepto" );
$lErroExclusao = false;
$sErro         = "";

if( isset( $excluir ) ) {

  $db_opcao  = 3;
  $db_opcao1 = 3;

  db_inicio_transacao();

  $sWhereRecHumanoEscola = "ed75_i_escola = {$iEscola} AND ed75_i_rechumano = {$ed20_i_codigo}";
  $sSqlRecHumanoEscola   = $oDaoRecHumanoEscola->sql_query_file( null, "ed75_i_codigo", null, $sWhereRecHumanoEscola );
  $rsRecHumanoEscola     = db_query( $sSqlRecHumanoEscola );

  if( $rsRecHumanoEscola && pg_num_rows( $rsRecHumanoEscola ) > 0 ) {

    $iCodigo = db_utils::fieldsMemory( $rsRecHumanoEscola, 0 )->ed75_i_codigo;

    $oDaoRecHumanoAtiv->excluir( null, "ed22_i_rechumanoescola = {$iCodigo}" );
    if( $oDaoRecHumanoAtiv->erro_status == "0" ) {

      $lErroExclusao = true;
      $sErro         = $oDaoRecHumanoAtiv->erro_msg;
    }

    if( !$lErroExclusao ) {

      $oDaoRecHumanoHoraDisp->excluir( null, "ed33_rechumanoescola = {$iCodigo}" );
      if( $oDaoRecHumanoHoraDisp->erro_status == "0" ) {

        $lErroExclusao = true;
        $sErro         = $oDaoRecHumanoHoraDisp->erro_msg;
      }
    }

    if( !$lErroExclusao ) {

      $oDaoRelacaoTrabalho->excluir( null, "ed23_i_rechumanoescola = {$iCodigo}" );
      if( $oDaoRelacaoTrabalho->erro_status == "0" ) {

        $lErroExclusao = true;
        $sErro         = $oDaoRelacaoTrabalho->erro_msg;
      }
    }

    if( !$lErroExclusao ) {

      $oDaoRecHumanoEscola->excluir( $iCodigo );
      if( $oDaoRecHumanoEscola->erro_status == "0" ) {

        $lErroExclusao = true;
        $sErro         = $oDaoRecHumanoEscola->erro_msg;
      }
    }
  }

  db_fim_transacao( $lErroExclusao );
} else if( isset( $chavepesquisa ) ) {

  $db_opcao  = 3;
  $db_opcao1 = 3;
  $result    = $clrechumano->sql_record($clrechumano->sql_query($chavepesquisa));
  db_fieldsmemory($result,0);
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset align="left" style="width:95%"><legend><b>Exclusão de Recurso Humano</b></legend>
    <?include("forms/db_frmrechumano.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if( isset( $excluir ) ) {

  if( !$lErroExclusao ) {
    db_msgbox( "Vínculos do recurso humano com a escola, excluídos com sucesso." );
  } else {
    db_msgbox( $sErro );
  }
}
if($db_opcao==33){
 echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>