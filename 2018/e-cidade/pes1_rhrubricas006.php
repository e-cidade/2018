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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_rhrubricas_classe.php"));
include(modification("classes/db_rhbasesr_classe.php"));
include(modification("classes/db_basesr_classe.php"));
include(modification("classes/db_rhrubelemento_classe.php"));
include(modification("classes/db_rhrubelementoprinc_classe.php"));
include(modification("classes/db_gerfadi_classe.php"));
include(modification("classes/db_gerfcom_classe.php"));
include(modification("classes/db_gerffer_classe.php"));
include(modification("classes/db_gerffx_classe.php"));
include(modification("classes/db_gerfres_classe.php"));
include(modification("classes/db_gerfs13_classe.php"));
include(modification("classes/db_gerfsal_classe.php"));
include(modification("classes/db_rhrubretencao_classe.php"));


$clrhrubricas = new cl_rhrubricas;
$clrhbasesr = new cl_rhbasesr;
$clbasesr = new cl_basesr;
$clrhrubelemento = new cl_rhrubelemento;
$clrhrubelementoprinc = new cl_rhrubelementoprinc;
$clgerfadi = new cl_gerfadi;
$clgerfcom = new cl_gerfcom;
$clgerffer = new cl_gerffer;
$clgerffx  = new cl_gerffx;
$clgerfres = new cl_gerfres;
$clgerfs13 = new cl_gerfs13;
$clgerfsal = new cl_gerfsal;
$clrhrubretencao = new cl_rhrubretencao();

db_postmemory($HTTP_POST_VARS);
$db_opcao = 33;
$db_botao = false;
if(isset($excluir)){
  db_inicio_transacao();
  $sqlerro=false;

  // die($clgerfadi->sql_query_file(null,null,null,$rh27_rubric,"*","r22_rubric limit 1"));
  $result_sqladi = $clgerfadi->sql_record($clgerfadi->sql_query_file(null,null,null,$rh27_rubric,"*","r22_rubric limit 1"));
  if($clgerfadi->numrows > 0){
  	$sqlerro = true;
  }else{
  	// die($clgerfcom->sql_query_file(null,null,null,$rh27_rubric,"*","r48_rubric limit 1"));
	  $result_sqlcom = $clgerfcom->sql_record($clgerfcom->sql_query_file(null,null,null,$rh27_rubric,"*","r48_rubric limit 1"));
	  if($clgerfcom->numrows > 0){
	  	$sqlerro = true;
    }else{
    	// die($clgerffer->sql_query_file(null,null,null,$rh27_rubric,null,"*","r31_rubric limit 1"));
		  $result_sqlfer = $clgerffer->sql_record($clgerffer->sql_query_file(null,null,null,$rh27_rubric,null,"*","r31_rubric limit 1"));
		  if($clgerffer->numrows > 0){
		  	$sqlerro = true;
		  }else{
		  	// die($clgerffx->sql_query_file(null,null,null,$rh27_rubric,"*","r53_rubric limit 1"));
		    $result_sqlfx  = $clgerffx->sql_record($clgerffx->sql_query_file(null,null,null,$rh27_rubric,"*","r53_rubric limit 1"));
			  if($clgerffx->numrows > 0){
			  	$sqlerro = true;
			  }else{
				  // die($clgerfres->sql_query_file(null,null,null,$rh27_rubric,null,"*","r20_rubric limit 1"));
				  $result_sql = $clgerfres->sql_record($clgerfres->sql_query_file(null,null,null,$rh27_rubric,null,"*","r20_rubric limit 1"));
				  if($clgerfres->numrows > 0){
				  	$sqlerro = true;
				  }else{
					  // die($clgerfs13->sql_query_file(null,null,null,$rh27_rubric,"*","r35_rubric limit 1"));
					  $result_sqls13 = $clgerfs13->sql_record($clgerfs13->sql_query_file(null,null,null,$rh27_rubric,"*","r35_rubric limit 1"));
					  if($clgerfs13->numrows > 0){
					  	$sqlerro = true;
            }else{
						  // die($clgerfsal->sql_query_file(null,null,null,$rh27_rubric,"*","r14_rubric limit 1"));
						  $result_sql = $clgerfsal->sql_record($clgerfsal->sql_query_file(null,null,null,$rh27_rubric,"*","r14_rubric limit 1"));
						  if($clgerfsal->numrows > 0){
						  	$sqlerro = true;
						  }
					  }
				  }
			  }
		  }
	  }
  }
  if($sqlerro == true){
    $erro_msg = "Usuário:\\n\\nCálculos já efetuados com esta rubrica.\\nExclusão abortada.\\n\\nAdministrador:";
  }

  if($sqlerro == false){
	  if($sqlerro == false){
		  $clrhrubelemento->rh23_rubric=$rh27_rubric;
		  $clrhrubelemento->excluir($rh27_rubric,db_getsession("DB_instit"));
		  if($clrhrubelemento->erro_status==0){
		  	$erro_msg = $clrhrubelemento->erro_msg;
		    $sqlerro=true;
		  }
	  }

    if ( !$sqlerro ) {
      $sWhereExcluiRetencao  = "     rh75_rubric = '{$rh27_rubric}' ";
      $sWhereExcluiRetencao .= " and rh75_instit = ".db_getsession('DB_instit');
      $clrhrubretencao->excluir(null,$sWhereExcluiRetencao);
      if($clrhrubretencao->erro_status == 0 ){
        $erro_msg = $clrhrubretencao->erro_msg;
        $sqlerro=true;
      }
    }

	  if($sqlerro == false){
       $anousu = db_anofolha();
       $mesusu = db_mesfolha();
       $clbasesr->excluir(null,null,null,$rh27_rubric,db_getsession("DB_instit"));
		   if($clbasesr->erro_status==0){
		   	$erro_msg = $clrhbasesr->erro_msg;
		     $sqlerro=true;
		   }
	  }
	  if($sqlerro == false){
		  $clrhrubricas->excluir($rh27_rubric,db_getsession("DB_instit"));
		  $erro_msg = $clrhrubricas->erro_msg;
		  if($clrhrubricas->erro_status==0){
		    $sqlerro=true;
		  }
	  }
      // <!-- ContratosPADRS: tipo de rubrica excluir -->
  }
  db_fim_transacao($sqlerro);
  $db_opcao = 3;
  $db_botao = true;
}else if(isset($chavepesquisa)){
  $db_opcao = 3;
  $db_botao = true;
  $result = $clrhrubricas->sql_record($clrhrubricas->sql_query($chavepesquisa,db_getsession("DB_instit")));
  db_fieldsmemory($result,0);

  $sWhereRhrubelemento    = "      rhrubelemento.rh23_rubric = '{$chavepesquisa}'";
  $sWhereRhrubelemento   .= " and rhrubelemento.rh23_instit = ". db_getsession("DB_instit");
  $sWhereRhrubelemento   .= " and o56_anousu = ". db_getsession("DB_anousu");
  $result = $clrhrubelemento->sql_record($clrhrubelemento->sql_query($chavepesquisa, db_getsession("DB_instit"), "*", " o56_anousu desc", $sWhereRhrubelemento));
  if($clrhrubelemento->numrows > 0){
    db_fieldsmemory($result, 0);
  }

  $sWhereRetencao   = "     rh75_rubric = '{$chavepesquisa}'";
  $sWhereRetencao  .= " and rh75_instit = ".db_getsession('DB_instit');

  $sCamposRetencao  = " rh75_retencaotiporec,   ";
  $sCamposRetencao .= " e21_descricao,          ";
  $sCamposRetencao .= " e21_retencaotiporecgrupo";

  $rsRetencao = $clrhrubretencao->sql_record($clrhrubretencao->sql_query(null,$sCamposRetencao,null,$sWhereRetencao));
  if ( $clrhrubretencao->numrows > 0 ) {
    db_fieldsmemory($rsRetencao,0);
  }

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?
	include(modification("forms/db_frmrhrubricas.php"));
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($excluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clrhrubricas->erro_campo!=""){
      echo "<script> document.form1.".$clrhrubricas->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrhrubricas->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
	 echo "
	  <script>
	    function js_db_tranca(){
	      parent.location.href='pes1_rhrubricas003.php';
	    }\n
	    js_db_tranca();
	  </script>\n
	 ";
  }
}
if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         /*
         parent.document.formaba.rhrubelemento.disabled=false;
         (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_rhrubelemento.location.href='pes1_rhrubelemento001.php?db_opcaoal=33&rh23_rubric=".@$rh27_rubric."';
         */
         parent.document.formaba.rhbases.disabled=false;
         (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_rhbases.location.href='pes1_rhbases004.php?r09_rubric=".@$rh27_rubric."';          
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('rhbases');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
