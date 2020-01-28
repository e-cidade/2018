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


require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_cursoturno_classe.php");
include("classes/db_cursoescola_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$clcursoturno  = new cl_cursoturno;
$clcursoescola = new cl_cursoescola;

$db_opcao      = 1;
$db_botao      = true;
$ed85_i_escola = db_getsession("DB_coddepto");
$ed18_c_nome   = db_getsession("DB_nomedepto");

$result_ver = $clcursoescola->sql_record($clcursoescola->sql_query("","ed71_i_codigo as nada",""," ed71_i_curso = $ed85_i_curso AND ed71_i_escola = $ed85_i_escola"));
$result1 = $clcursoturno->sql_record($clcursoturno->sql_query("","ed85_i_turno as turnojacad",""," ed85_i_escola = $ed85_i_escola AND ed85_i_curso = $ed85_i_curso"));

if ($clcursoturno->numrows > 0) {
	
  $sep       = "";
  $turno_cad = "";
  
  for($c = 0; $c < $clcursoturno->numrows; $c++) {
    
  	db_fieldsmemory($result1,$c);
    $turno_cad .= $sep.$turnojacad;
    $sep = ",";
  
  }

} else {
  $turno_cad = 0;
}

if (isset($incluir)) {

  if ( validaTurno( $clcursoturno, $ed85_i_escola, $ed85_i_curso, $ed85_i_turno) ) {

    db_inicio_transacao();
    $clcursoturno->incluir($ed85_i_codigo);
    db_fim_transacao();
  
    $db_botao = false;
  }
}

if (isset($alterar)) {
	
  if ( validaTurno( $clcursoturno, $ed85_i_escola, $ed85_i_curso, $ed85_i_turno) ) {

    db_inicio_transacao();
    $db_opcao = 2;
    $clcursoturno->alterar($ed85_i_codigo);
    db_fim_transacao();
    
    $db_botao = false;
  }
}

if (isset($excluir)) {
	
  db_inicio_transacao();
  $db_opcao = 3;
  $clcursoturno->excluir($ed85_i_codigo);
  db_fim_transacao();

}

/**
 * Valida se o curso já possui o vínculo com o turno informado
 * @return boolean
 */
function validaTurno( $oDaoCursoTurno, $iEscola, $iCurso, $iTurno ) {

  if ( empty($iTurno) ) {
    return false;
  }

  /**
   * Valida se a escola possui o turno informado
   */
  $oDaoPeriodoEscola    = new cl_periodoescola();
  $sWherePeriodoEscola  = "     ed17_i_escola = {$iEscola}";
  $sWherePeriodoEscola .= " and ed17_i_turno  = {$iTurno}";
  $sSqlPeriodoEscola    = $oDaoPeriodoEscola->sql_query_file( null, "ed17_i_codigo", null, $sWherePeriodoEscola );
  $rsPeriodoEscola      = db_query( $sSqlPeriodoEscola );
  
  if ( pg_num_rows( $rsPeriodoEscola ) == 0 ) {
    return false;
  }

  /**
   * Verifica se turno informado já possui vínculo com o curso
   */
  $sWhereTurno  = "     ed85_i_escola = {$iEscola}";
  $sWhereTurno .= " and ed85_i_curso  = {$iCurso}";
  $sWhereTurno .= " and ed85_i_turno  = {$iTurno}";
  $sSqlTurno    = $oDaoCursoTurno->sql_query_file( null, "ed85_i_codigo", null, $sWhereTurno );
  $rsTurno      = db_query( $sSqlTurno );

  if ( pg_num_rows( $rsTurno ) > 0) {
    return false;
  }

  return true;
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
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Informe os turnos deste curso na escola <?=$ed18_c_nome?></b></legend>
    <?
    if($clcursoescola->numrows==0){
     echo "<br><center>Para ter acesso a esta rotina, primeiro vincule este curso nesta escola. (Aba Vincular Curso)</center>";
     exit;
    }else{
     include("forms/db_frmcursoturno.php");
    }
    ?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed85_i_turno",true,1,"ed85_i_turno",true);
</script>
<?
if(isset($incluir)){
 if($clcursoturno->erro_status=="0"){
  $clcursoturno->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clcursoturno->erro_campo!=""){
   echo "<script> document.form1.".$clcursoturno->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clcursoturno->erro_campo.".focus();</script>";
  }
 }else{
  $clcursoturno->erro(true,true);
 }
}
if(isset($alterar)){
 if($clcursoturno->erro_status=="0"){
  $clcursoturno->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clcursoturno->erro_campo!=""){
   echo "<script> document.form1.".$clcursoturno->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clcursoturno->erro_campo.".focus();</script>";
  }
 }else{
  $clcursoturno->erro(true,true);
 }
}
if(isset($excluir)){
 if($clcursoturno->erro_status=="0"){
  $clcursoturno->erro(true,false);
 }else{
  $clcursoturno->erro(true,true);
 }
}
if(isset($cancelar)){
 echo "<script>location.href='".$clcursoturno->pagina_retorno."'</script>";
}
?>