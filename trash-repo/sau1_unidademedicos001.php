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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_unidademedicos_classe.php");
include("classes/db_especmedico_ext_classe.php");
require_once("classes/db_ausencias_classe.php");
require_once("classes/db_prontproced_classe.php");
require_once("classes/db_prontprofatend_classe.php");
require_once("libs/db_utils.php");

include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clunidademedicos         = new cl_unidademedicos;
$clespecmedico            = new cl_especmedico_ext;

$oDaoausencias            = db_utils::getdao('ausencias');
$oDaoprontproced          = db_utils::getdao('prontproced');
$oDaoprontprofatend       = db_utils::getdao('prontprofatend');
$oDaoFarCbos              = db_utils::getdao('far_cbos');
$oDaoFarCbosProfissional  = db_utils::getdao('far_cbosprofissional');

$oDaoAgendamentos         = db_utils::getdao('agendamentos');

$db_opcao  = 1;
$db_botao  = true;

// Situa��o refere-se a possibilidade de realizar uma altera��o da situa��o do m�dico!
$lSituacao = true;


$db_botao1 = false;
if(isset($opcao) && ( $opcao=="alterar" || $opcao=="excluir" ) ){
  
	$db_opcao = 2;
	$db_botao1 = true;
	//$result = $clunidademedicos->sql_record($clunidademedicos->sql_query($sd04_i_codigo));
  $sCampos  = '*, (select fa54_i_cbos from far_cbosprofissional ';
  $sCampos .= 'where far_cbosprofissional.fa54_i_unidademedico = sd04_i_codigo limit 1) as fa54_i_cbos ';
	$result = $clespecmedico->sql_record($clespecmedico->sql_query($sd27_i_codigo, $sCampos));
	db_fieldsmemory($result,0);

}

if ((isset($incluir) || isset($alterar) || isset($excluir)) && isset($sd27_c_situacao) && $sd27_c_situacao == 'D') {
    
  $dDataUsu  = date("Y",db_getsession( "DB_datausu" ) )."-".
               date("m",db_getsession( "DB_datausu" ) )."-".
               date("d",db_getsession( "DB_datausu" ) );
  $sHora     = substr(db_hora(), 0, 2);
  $sMin      = substr(db_hora(), 3, 2);
  $sWhere    = " ( sd23_d_consulta > '$dDataUsu'::DATE "; 
  $sWhere   .= "   OR (sd23_d_consulta = '$dDataUsu'::DATE ";
  $sWhere   .= "       and substring(sd23_c_hora, 1, 2)::INTEGER > ($sHora)::INTEGER) ";
  $sWhere   .= "   OR (sd23_d_consulta = '$dDataUsu'::DATE ";
  $sWhere   .= "       and substring(sd23_c_hora, 1, 2)::INTEGER = ($sHora)::INTEGER ";
  $sWhere   .= "       and substring(sd23_c_hora, 4, 2)::INTEGER >= ($sMin)::INTEGER) ";
  $sWhere   .= " ) ";
  $sWhere   .= " AND sd04_i_medico =  $sd04_i_medico ";
  $sWhere   .= " AND s114_i_agendaconsulta is null ";
  $sSql      = $oDaoAgendamentos->sql_query_situacao ("","count(sd23_d_consulta) as nro_agendamentos",null, $sWhere);
  $rsAgend   = $oDaoAgendamentos->sql_record($sSql);
  $oAgend    = db_utils::fieldsmemory($rsAgend, 0);
  if ((int)$oAgend->nro_agendamentos > 0) {
      
    $sMsg  = "O M�dico ".$z01_nome." possui $oAgend->nro_agendamentos consulta(s) agendada(s).";
    $sMsg .= "\\nSua situa��o n�o pode ser Alterada para  Desativado!";
    db_msgbox("$sMsg");
    $lSituacao = false;
      
  } 
  
}

if(isset($incluir) && $lSituacao){
	$result = @$clespecmedico->sql_record( "select sd04_i_codigo
                                           from unidademedicos 
                                          where sd04_i_unidade=$sd04_i_unidade
                                            and sd04_i_medico=$sd04_i_medico
                                          order by sd04_i_codigo ") ;
                                      /*echo   $teste=" select sd04_i_codigo
                                           from unidademedicos 
                                          where sd04_i_unidade=$sd04_i_unidade
                                            and sd04_i_medico=$sd04_i_medico
                                          order by sd04_i_codigo";*/
	db_inicio_transacao();
	if( @pg_numrows( $result ) == 0 ) {
		 $clunidademedicos->sd04_c_situacao = $sd27_c_situacao;
     $clunidademedicos->incluir($sd04_i_codigo);
		 $sd27_i_undmed = $clunidademedicos->sd04_i_codigo;
	}else{
		 $sd27_i_undmed = pg_result($result,0,0);
	}


  if ($clunidademedicos->erro_status != '0') {

		$clespecmedico->sd27_i_undmed = $sd27_i_undmed;
		$clespecmedico->sd27_b_principal = 't';
    $clespecmedico->sd27_c_situacao = $sd27_c_situacao;
    $clespecmedico->incluir($sd27_i_codigo);
    if ($clespecmedico->erro_status == '0') {
  
      $clunidademedicos->erro_status = '0';
      $clunidademedicos->erro_msg    = $clespecmedico->erro_msg;
  
    }

  }

  if ($clunidademedicos->erro_status != '0') {

    /* BLOCO DE VERIFICA��O / INCLUS�O DO CBOS DO PROFISSIONAL */
    $sSql = $oDaoFarCbosProfissional->sql_query_file(null, 'fa54_i_codigo', '', 
                                                     "fa54_i_unidademedico = $sd27_i_undmed ".
                                                     "and fa54_i_cbos = $fa54_i_cbos "
                                                    );
    $rs   = $oDaoFarCbosProfissional->sql_record($sSql);
    if ($oDaoFarCbosProfissional->numrows <= 0) { // o profissional n�o possui o c�digo CBOS na unidade, ent�o, incluo
    
      $oDaoFarCbosProfissional->fa54_i_unidademedico = $sd27_i_undmed;
      $oDaoFarCbosProfissional->fa54_i_cbos          = $fa54_i_cbos;
      $oDaoFarCbosProfissional->incluir(null);
      if ($oDaoFarCbosProfissional->erro_status == '0') {
      
        $clunidademedicos->erro_status = '0';
        $clunidademedicos->erro_msg    = $oDaoFarCbosProfissional->erro_msg;
      
      }

    }

  }
	
	db_fim_transacao($clunidademedicos->erro_status == '0' ? true : false);

}

if (isset($alterar) && $lSituacao){

  $sSql = $oDaoFarCbosProfissional->sql_query_file(null, 'fa54_i_codigo', '', 
                                                   "fa54_i_unidademedico = $sd04_i_codigo ".
                                                   "and fa54_i_cbos = $fa54_i_cbos "
                                                  );
  $rs   = $oDaoFarCbosProfissional->sql_record($sSql);

	db_inicio_transacao();

	$db_opcao = 2;
	$clunidademedicos->alterar($sd04_i_codigo);

  if ($clunidademedicos->erro_status != '0') {

		$clespecmedico->alterar_ext($sd27_i_codigo);
    if ($clespecmedico->erro_status == '0') {
  
        $clunidademedicos->erro_status = '0';
        $clunidademedicos->erro_msg    = $clespecmedico->erro_msg;
  
    }

  }

  if ($clunidademedicos->erro_status != '0') { 
    
    if ($oDaoFarCbosProfissional->numrows <= 0) { // tenho que incluir o c�digo CBOS para o profissional na unidade

      $oDaoFarCbosProfissional->fa54_i_unidademedico = $sd04_i_codigo;
      $oDaoFarCbosProfissional->fa54_i_cbos          = $fa54_i_cbos;
      $oDaoFarCbosProfissional->incluir(null);
      if ($oDaoFarCbosProfissional->erro_status == '0') {
  
        $clunidademedicos->erro_status = '0';
        $clunidademedicos->erro_msg    = $oDaoFarCbosProfissional->erro_msg;
  
      }

    }

  }

	db_fim_transacao($clunidademedicos->erro_status == '0' ? true : false);

}

if(isset($excluir) && $lSituacao) {

  $sSql = $oDaoprontprofatend->sql_query(null, '*', null, " 104_i_profissional = $sd27_i_codigo ");
  $oDaoprontprofatend->sql_record($sSql);
  $sSql = $oDaoprontproced->sql_query(null, '*', null, " sd29_i_profissional = $sd27_i_codigo ");
  $oDaoprontproced->sql_record($sSql);

  /* Verifico se o vinculo (especmedico) ja tem algum prontuario */
  if($oDaoprontprofatend->numrows == 0 && $oDaoprontproced->numrows == 0) {
 
  	db_inicio_transacao();
	  $db_opcao = 3;
    
    $oDaoausencias->excluir(null, " sd06_i_especmed = $sd27_i_codigo ");
    if($oDaoausencias->erro_status == '0') {

      $clunidademedicos->erro_status = '0';
      $clunidademedicos->erro_msg = $oDaoausencias->erro_msg;

    }

	  $clespecmedico->excluir($sd27_i_codigo);
    if($clespecmedico->erro_status == '0' && $oDaoausencias->erro_status != '0') {
 
      $clunidademedicos->erro_status = '0';
      $clunidademedicos->erro_msg = $clespecmedico->erro_msg;

    }

    $oDaoFarCbosProfissional->excluir(null, 'fa54_i_unidademedico = '.$sd04_i_codigo);
    if ($oDaoFarCbosProfissional->erro_status == '0') {
  
      $clunidademedicos->erro_status = '0';
      $clunidademedicos->erro_msg    = $oDaoFarCbosProfissional->erro_msg;
  
    }

    /* Faco a verificacao se ainda existe algum registro na especmedico que referencia o codigo da unidademedicos. Se nao existe, apago a linha da unidademedicos  */
    $sSql = $clespecmedico->sql_query(null, ' sd27_i_codigo ', null, " sd27_i_codigo = $sd04_i_codigo ");
    $clespecmedico->sql_record($sSql);
    if($clespecmedico->numrows == 0 && $clunidademedicos->erro_status != '0') {   
	    $clunidademedicos->excluir($sd04_i_codigo);
    }

	  db_fim_transacao($clunidademedicos->erro_status == '0');

  } else {

    echo "<script>alert('Vinculo ja possui FAA. Impossivel excluir.');</script>";
    db_redireciona("sau1_unidademedicos001.php?sd04_i_medico=$sd04_i_medico&z01_nome=$z01_nome");
  
  }

}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
    <fieldset style="width:95%"><legend><b>V�nculo</b></legend>
     <?
     include("forms/db_frmunidademedicos.php");
     ?>
    </fieldset>
    </center>
     </td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","sd04_i_unidade",true,1,"sd04_i_unidade",true);
</script>



<?
if(isset($incluir) || isset($alterar)){
  if($clunidademedicos->erro_status=="0"){
    $clunidademedicos->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clunidademedicos->erro_campo!=""){
      echo "<script> document.form1.".$clunidademedicos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clunidademedicos->erro_campo.".focus();</script>";
    }
  }else{
    if($clespecmedico->erro_status=="0"){
      $clespecmedico->erro(true,false);
      $db_botao=true;
      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
      if($clespecmedico->erro_campo!=""){
        echo "<script> document.form1.".$clespecmedico->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clespecmedico->erro_campo.".focus();</script>";
      }
    }else{
      $clespecmedico->erro(true,false);
      db_redireciona("sau1_unidademedicos001.php?sd04_i_medico=$sd04_i_medico&z01_nome=$z01_nome");
    }
  }
}



if(isset($excluir)){
 if($clunidademedicos->erro_status=="0"){
  $clunidademedicos->erro(true,false);
 }else{
  $clunidademedicos->erro(true,false);
    db_redireciona("sau1_unidademedicos001.php?sd04_i_medico=$sd04_i_medico&z01_nome=$z01_nome");
 }
}
if(isset($cancelar)){
    db_redireciona("sau1_unidademedicos001.php?sd04_i_medico=$sd04_i_medico&z01_nome=$z01_nome");
}
?>