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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_rhrubricas_classe.php"));
include(modification("classes/db_rhrubelemento_classe.php"));
include(modification("classes/db_rhrubretencao_classe.php"));
include(modification("classes/db_basesr_classe.php"));
include(modification("classes/db_rhtipomedia_classe.php"));

$clrhrubricas    = new cl_rhrubricas();
$clrhrubelemento = new cl_rhrubelemento();
$clrhrubretencao = new cl_rhrubretencao();
$clbasesr        = new cl_basesr();
$clrhtipomedia   = new cl_rhtipomedia();

db_postmemory($HTTP_POST_VARS);

$db_opcao = 1;
$db_botao = true;
if(isset($incluir) || isset($novasrubricas)){

  db_inicio_transacao();
  $calc1 = "";
  $calc2 = "";
  $calc3 = "";
  $sqlerro = false;

  $clrhrubricas->rh27_form             = str_replace("\\","",$rh27_form);
  $clrhrubricas->rh27_form2            = str_replace("\\","",$rh27_form2);
  $clrhrubricas->rh27_form3            = str_replace("\\","",$rh27_form3);
  $clrhrubricas->rh27_formq            = str_replace("\\","",$rh27_formq);
  $clrhrubricas->rh27_cond2            = str_replace("\\","",$rh27_cond2);
  $clrhrubricas->rh27_cond3            = str_replace("\\","",$rh27_cond3);
  $clrhrubricas->rh27_obs              = str_replace("\\","",$rh27_obs);
  $clrhrubricas->rh27_valorpadrao      = str_replace("\\","",$rh27_valorpadrao);
  $clrhrubricas->rh27_quantidadepadrao = str_replace("\\","",$rh27_quantidadepadrao);

  if(!isset($novasrubricas)){
    if($rh27_calc1 > 0){
      $rubricateste = $rh27_rubric + 2000;
      // die($clrhrubricas->sql_query_file($rubricateste));
      $result_teste = $clrhrubricas->sql_record($clrhrubricas->sql_query_file($rubricateste,db_getsession("DB_instit")));
      if($clrhrubricas->numrows == 0){
        $calc1 = $rubricateste;
      }
    }

    if($rh27_calc2 > 0){
      $rubricateste = $rh27_rubric + 4000;
      // die($clrhrubricas->sql_query_file($rubricateste));
      $result_teste = $clrhrubricas->sql_record($clrhrubricas->sql_query_file($rubricateste,db_getsession("DB_instit")));
      if($clrhrubricas->numrows == 0){
        $calc2 = $rubricateste;
      }
    }

    if($rh27_calc3 == 't'){
      $rubricateste = $rh27_rubric + 6000;
      // die($clrhrubricas->sql_query_file($rubricateste));
      $result_teste = $clrhrubricas->sql_record($clrhrubricas->sql_query_file($rubricateste,db_getsession("DB_instit")));
      if($clrhrubricas->numrows == 0){
        $calc3 = $rubricateste;
      }
    }
  }else{
    $arr_codigos = split(",",$novasrubricas);

    for($i=0; $i<count($arr_codigos); $i++){
      $rubricainclui = $arr_codigos[$i];
      if($i == 0){
        $varsubstr = $rh27_descr." S/ FÉRIAS";
        $descricinclui = substr($varsubstr,0,30);
      }else if($i == 1){
        $varsubstr = $rh27_descr." S/ 13o SALÁRIO";
        $descricinclui = substr($varsubstr,0,30);
      }else if($i == 2){
        $varsubstr = $rh27_descr." S/ RESCISÃO";
        $descricinclui = substr($varsubstr,0,30);
      }

      if($rubricainclui != 0){

        if ( !empty($rh27_rhfundamentacaolegal) ) {
          $oDaoFundamentacaoLegal = new cl_rhfundamentacaolegal();
          $sSqlFundamentacaoLegal = $oDaoFundamentacaoLegal->sql_query_file($rh27_rhfundamentacaolegal);
          $rsFundamentacao        = db_query($sSqlFundamentacaoLegal);

          if ( !$rsFundamentacao ) {
            $sqlerro = true;
            $erro_msg = 'Não foi possível consultar a fundamentação legal informada.';
          }

          if ( pg_num_rows($rsFundamentacao) == 0 ) {
            $sqlerro = false;
            $clrhrubricas->rh27_rhfundamentacaolegal = null;
            $GLOBALS["HTTP_POST_VARS"]["rh27_rhfundamentacaolegal"] = null;
          }
        }

        if($sqlerro == false){
          $clrhrubricas->rh27_tipo              = "2";
          $clrhrubricas->rh27_calc1             = "0";
          $clrhrubricas->rh27_calc2             = "0";
          $clrhrubricas->rh27_calc3             = "false";
          $clrhrubricas->rh27_descr             = $descricinclui;
          $clrhrubricas->rh27_instit            = db_getsession("DB_instit");
          $clrhrubricas->rh27_periodolancamento = $rh27_periodolancamento == 't' ? 'true' : 'false';
          $clrhrubricas->incluir($rubricainclui,db_getsession("DB_instit"));
          $erro_msg = $clrhrubricas->erro_msg;
          if($clrhrubricas->erro_status==0){
            $sqlerro = true;
            break;
          }
          // <!-- ContratosPADRS: tipo de rubrica inserir -->
        }
      }
    }
  }

  if($calc1 == "" && $calc2 == "" && $calc3 == "" && $sqlerro == false){
    if($rh27_calc3 == 't'){
      $inccalc3 = 'true';
    }else{
      $inccalc3 = 'false';
    }

    if (!empty($rh27_rubric)) {

	    $sSqlRhRubricas = $clrhrubricas->sql_query_file($rh27_rubric, db_getsession("DB_instit"));
	    $rsRhRubricas   = db_query($sSqlRhRubricas);

	    if (pg_num_rows($rsRhRubricas) > 0) {
	    	$sqlerro  = true;
	    	$erro_msg = "Código Rubrica já cadastrado para a instituição.";
	    }
    }

    if ( !empty($rh27_rhfundamentacaolegal) ) {
      $oDaoFundamentacaoLegal = new cl_rhfundamentacaolegal();
      $sSqlFundamentacaoLegal = $oDaoFundamentacaoLegal->sql_query_file($rh27_rhfundamentacaolegal);
      $rsFundamentacao        = db_query($sSqlFundamentacaoLegal);

      if ( !$rsFundamentacao ) {
        $sqlerro = true;
        $erro_msg = 'Não foi possível consultar a fundamentação legal informada.';
      }

      if ( pg_num_rows($rsFundamentacao) == 0 ) {
        $sqlerro = false;
        $clrhrubricas->rh27_rhfundamentacaolegal = null;
        $GLOBALS["HTTP_POST_VARS"]["rh27_rhfundamentacaolegal"] = null;
      }
    }

    if (!$sqlerro) {

	    $clrhrubricas->rh27_tipo  = $rh27_tipo;
	    $clrhrubricas->rh27_calc1 = $rh27_calc1;
	    $clrhrubricas->rh27_calc2 = $rh27_calc2;
	    $clrhrubricas->rh27_calc3 = $inccalc3;
	    $clrhrubricas->rh27_descr = $rh27_descr;
	    $clrhrubricas->rh27_instit = db_getsession("DB_instit");

	    $clrhrubricas->incluir($rh27_rubric,db_getsession("DB_instit"));
	    $erro_msg = $clrhrubricas->erro_msg;
	    $rh27_rubric = $clrhrubricas->rh27_rubric;
	    if($clrhrubricas->erro_status==0){
	      $sqlerro = true;
	    }

        // <!-- ContratosPADRS: tipo de rubrica inserir -->

    }

    if ( !$sqlerro && isset($tipo) && trim($tipo) != '' ) {
    	if ( $tipo == 'e') {
		    if( isset($rh23_codele) && trim($rh23_codele) != ""){
		      $clrhrubelemento->incluir($rh27_rubric,db_getsession("DB_instit"));
		      $erro_msg = $clrhrubelemento->erro_msg;
		      if($clrhrubelemento->erro_status == 0 ){
		        $sqlerro=true;
		      }
		    }
    	} else if ( $tipo == 'c' || $tipo == 'p' || $tipo == 'd') {
		    if( isset($rh75_retencaotiporec) && trim($rh75_retencaotiporec) != ""){
		    	$clrhrubretencao->rh75_retencaotiporec = $rh75_retencaotiporec;
		    	$clrhrubretencao->rh75_instit          = db_getsession('DB_instit');
		    	$clrhrubretencao->rh75_rubric          = $rh27_rubric;
		    	$clrhrubretencao->incluir(null);
		      $erro_msg = $clrhrubretencao->erro_msg;
		      if($clrhrubretencao->erro_status == 0 ){
		        $sqlerro=true;
		      }
		    }
    	}
    }

    if(isset($codigo_importa)){
      $anousu = db_anofolha();
      $mesusu = db_mesfolha();
      $instit = db_getsession('DB_instit');
      $result_basesr = $clbasesr->sql_record($clbasesr->sql_query_file($anousu,$mesusu,null,$codigo_importa,$instit));
      $numrows_basesr = $clbasesr->numrows;
      for($i=0; $i<$numrows_basesr; $i++){
        db_fieldsmemory($result_basesr, $i);
        $clbasesr->r09_rubric = $rh27_rubric;
      	$clbasesr->r09_base   = $r09_base;
       	$clbasesr->incluir($anousu,$mesusu,$r09_base, $rh27_rubric,$instit);
        if($clbasesr->erro_status == 0 ){
          $erro_msg = $clbasesr->erro_msg;
          $sqlerro=true;
       	  break;
        }
      }
    }
  }

  db_fim_transacao($sqlerro);
  $db_opcao = 1;
  $db_botao = true;
}else if(isset($importar)){
  $result = $clrhrubricas->sql_record($clrhrubricas->sql_query($importar,db_getsession("DB_instit"),"rhrubricas.*, db_config.*, rhtipomedia.rh29_descr, b.rh29_descr as rh29_descr2"));
  db_fieldsmemory($result,0);

  $result = $clrhrubelemento->sql_record($clrhrubelemento->sql_query($importar,db_getsession("DB_instit")));
  if($clrhrubelemento->numrows > 0){
    db_fieldsmemory($result, 0);
  }
  $rh27_rubric = "";
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewFormularioFolha/ValidarCodigoRubrica.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/numbers.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/DBFormularios.css" rel="stylesheet" type="text/css">
</head>
<body>
	<?
	  include(modification("forms/db_frmrhrubricas.php"));
	?>
</body>
</html>
<?
if(isset($incluir) || isset($novasrubricas)){
  if($calc1 == "" && $calc2 == "" && $calc3 == ""){
    if($sqlerro==true){
      db_msgbox($erro_msg);
      if($clrhrubricas->erro_campo!=""){
        echo "<script> document.form1.".$clrhrubricas->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clrhrubricas->erro_campo.".focus();</script>";
      };
    }else{
      db_msgbox($erro_msg);
      db_redireciona("pes1_rhrubricas005.php?liberaaba=true&chavepesquisa=$rh27_rubric");
    }
  }else{
    $ccalculos = "";
    $dcalculos = "";
    $cvirgulas = "";
    $dvirgulas = "";
    if($calc1 != ""){
      $ccalculos.= $cvirgulas.$calc1;
      $dcalculos.= $dvirgulas.$calc1." - (".$rh27_descr." S/ FÉRIAS)";
      $cvirgulas = ",";
      if($calc2 != "" && $calc3 != ""){
        $dvirgulas = ", ";
      }else{
        $dvirgulas = " e ";
      }
    }else{
      $ccalculos.= $cvirgulas."0";
      $cvirgulas = ",";
    }
    if($calc2 != ""){
      $ccalculos.= $cvirgulas.$calc2;
      $dcalculos.= $dvirgulas.$calc2." - (".$rh27_descr." S/ 13o SALÁRIO)";
      $cvirgulas = ",";
      $dvirgulas = " e ";
    }else{
      $ccalculos.= $cvirgulas."0";
      $cvirgulas = ",";
    }
    if($calc3 != ""){
      $ccalculos.= $cvirgulas.$calc3;
      $dcalculos.= $dvirgulas.$calc3." - (".$rh27_descr." S/ RESCISÃO)";
    }else{
      $ccalculos.= $cvirgulas."0";
      $cvirgulas = ",";
    }
    echo "
          <script>
            alert('Também será(ão) gerada(s) a(s) rubrica(s) ".$dcalculos.", não esqueça de configurar suas bases e seus elementos.');
            obj=document.createElement('input');
            obj.setAttribute('name','novasrubricas');
            obj.setAttribute('type','hidden');
            obj.setAttribute('value','".$ccalculos."');
            document.form1.appendChild(obj);
            document.form1.submit();
          </script>
         ";
  }
}
?>
