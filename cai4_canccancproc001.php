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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_sql.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_arrecant_classe.php");
require_once("classes/db_arrecad_classe.php");
require_once("classes/db_arrehist_classe.php");
require_once("classes/db_cancdebitos_classe.php");
require_once("classes/db_cancdebitosprot_classe.php");
require_once("classes/db_cancdebitosreg_classe.php");
require_once("classes/db_cancdebitossusp_classe.php");
require_once("classes/db_cancdebitosproc_classe.php");
require_once("classes/db_cancdebitosprocreg_classe.php");
require_once("classes/db_cancdebitosconcarpeculiar_classe.php");
require_once("classes/db_cancdebitosprocconcarpeculiar_classe.php");
require_once("model/cancelamentoDebitos.model.php");

$clarrecad                       = new cl_arrecad;
$clarrecant                      = new cl_arrecant;
$clarrehist                      = new cl_arrehist;
$clcancdebitos                   = new cl_cancdebitos;
$clcancdebitosprot               = new cl_cancdebitosprot;
$clcancdebitosreg                = new cl_cancdebitosreg;
$clcancdebitosproc               = new cl_cancdebitosproc;
$clcancdebitossusp               = new cl_cancdebitossusp;
$clcancdebitosprocreg            = new cl_cancdebitosprocreg;
$clcancdebitosconcarpeculiar     = new cl_cancdebitosconcarpeculiar;
$clcancdebitosprocconcarpeculiar = new cl_cancdebitosprocconcarpeculiar;
$oCancelamentoDebitos 			     = new cancelamentoDebitos();

$clcancdebitos->k20_instit = db_getsession("DB_instit");
db_postmemory($HTTP_POST_VARS);
$db_opcao = 33;
$db_botao = false;

if (isset($processa) && isset($chaves)) {
	
  db_inicio_transacao();
	
  $sqlerro = false;
  $aRegs   = split("#", $chaves);
  
  for ( $i = 0; $i < count($aRegs); $i++) {
  	
    $aNumpreParRec = split("-", $aRegs[$i]);
    
    $aDebitos[$i]['Numpre'] = $aNumpreParRec[1];
    $aDebitos[$i]['Numpar'] = $aNumpreParRec[2];
    $aDebitos[$i]['Receit'] = $aNumpreParRec[3];
  }
  try {
	  $oCancelamentoDebitos->excluiCancelamento($aDebitos);
  } catch (Exception $eExeption){
	  $sqlerro = true;
	  $erromsg = $eExeption->getMessage();	
  }
  
//  $sqlerro = true;
  db_fim_transacao($sqlerro);

}

if (isset ($chavepesquisa)){
  
  $db_opcao = 1;
  $db_botao = true;
  $campos  = "cancdebitos.k20_cancdebitostipo,  ";
  $campos .= "cancdebitos.k20_codigo,           ";
  $campos .= "cancdebitos.k20_usuario,          ";
  $campos .= "cancdebitos.k20_data,             ";
  $campos .= "cancdebitos.k20_hora,             ";
  $campos .= "cancdebitosreg.k21_sequencia,     ";
  $campos .= "cancdebitosreg.k21_numpre,        ";
  $campos .= "cancdebitosreg.k21_numpar,        ";
  $campos .= "cancdebitosreg.k21_receit,        ";
  $campos .= "cancdebitosreg.k21_obs,           ";
  $campos .= "arrecant.k00_valor,               ";
  $campos .= "c.nome as nome,                   ";
  $campos .= "p.nome as nome_proc,              ";
  $campos .= "cancdebitosproc.*                 ";
  
  $sSqlCancDebitos = $clcancdebitos->sql_pendentesproc($campos, "", "k21_codigo =".$chavepesquisa." and k20_instit = ".db_getsession("DB_instit"));
  $result = $clcancdebitos->sql_record($sSqlCancDebitos);
  
  if ($clcancdebitos->numrows > 0) {
    
    @ db_fieldsmemory($result, 0);
    if ($k20_cancdebitostipo == 1) {
    	$cancdebitostipo = "Normal";
    } else {
      
    	$cancdebitostipo = "Renuncia";
    	
	    $sqlPeculiar  = "select k72_cancdebitos,c58_sequencial as tipo,c58_descr as caracteristica  ";
	    $sqlPeculiar .= "  from cancdebitosconcarpeculiar                                           ";
	    $sqlPeculiar .= "  inner join concarpeculiar on k72_concarpeculiar = c58_sequencial         ";
	    $sqlPeculiar .= "  where k72_cancdebitos = $k20_codigo                                      ";
	    
	    $rsPeculiar     = db_query($sqlPeculiar);
	    $linhasPeculiar = pg_num_rows($rsPeculiar);
	    if($linhasPeculiar > 0 ){
    
	      db_fieldsmemory($rsPeculiar, 0);
	      $c58_sequencial = $tipo;
        $c58_descr      = $caracteristica;
	    }
    }
	  $tipoDebito = $k20_cancdebitostipo;
    
    // processados
    if( $k23_cancdebitostipo == 1){
    	$cancdebitostipoproc = "Normal";
    } else {
      
    	$cancdebitostipoproc = "Renuncia";
	    $cancdebitostipo     = "Renuncia";
	    $sqlPeculiarproc  = "select c58_sequencial as tipoproc ,c58_descr as caracproc , k74_cancdebitosproc  ";
	    $sqlPeculiarproc .= "          from cancdebitosprocconcarpeculiar                                     ";
	    $sqlPeculiarproc .= "  inner join concarpeculiar on c58_sequencial = k74_concarpeculiar               ";
	    $sqlPeculiarproc .= "  where k74_cancdebitosproc =$k23_codigo                                         ";
      
	    $rsPeculiarproc     = db_query($sqlPeculiarproc);
	    $linhasPeculiarproc = pg_num_rows($rsPeculiarproc);
	    if($linhasPeculiarproc > 0 ){
	      db_fieldsmemory($rsPeculiarproc,0);
	    }
	  
    }
    $rsVerificaSuspensao = $clcancdebitossusp->sql_record($clcancdebitossusp->sql_query(null,"ar19_suspensao",null," ar21_cancdebitos = {$k20_codigo}"));
    if ($clcancdebitossusp->numrows > 0 ){
      db_fieldsmemory($rsVerificaSuspensao,0);
      $suspensao = "s"; 	
    } else {
      $suspensao = "n";
    }
  
  }
  
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="" bgcolor="#cccccc">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="left" valign="top" bgcolor="#CCCCCC">
<br><br>
<center>
<?
require_once("forms/db_frmcanccancproc.php");
?>
</center>
</td>
</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?

if (isset($processa) && isset($chaves)) {

  if ($sqlerro) {
  	db_msgbox($erromsg);
  } else {
  	
    db_msgbox("Operação concluída com sucesso!");
  	echo "<script>document.location.href='{$_SERVER['PHP_SELF']}';</script>";
  }
	
}

if (!isset ($chavepesquisa)) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>