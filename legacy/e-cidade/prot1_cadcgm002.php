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

  require_once(modification("libs/db_stdlib.php"));
  require_once(modification("libs/db_conecta.php"));
  require_once(modification("libs/db_utils.php"));
  require_once(modification("libs/db_sessoes.php"));
  require_once(modification("libs/db_usuariosonline.php"));
  require_once(modification("dbforms/db_funcoes.php"));
  require_once(modification("classes/db_cgm_classe.php"));
  require_once(modification("classes/db_issbase_classe.php"));
  require_once(modification("classes/db_cgmalt_classe.php"));
  require_once(modification("classes/db_db_cgmruas_classe.php"));
  require_once(modification("classes/db_db_cgmbairro_classe.php"));
  require_once(modification("classes/db_db_cgmcgc_classe.php"));
  require_once(modification("classes/db_db_cgmcpf_classe.php"));
  require_once(modification("classes/db_db_cepmunic_classe.php"));
  require_once(modification("classes/db_ruascep_classe.php"));
  require_once(modification("classes/db_db_replica_classe.php"));
  require_once(modification("libs/db_app.utils.php"));
  require_once(modification("classes/db_cidadaocgm_classe.php"));
  require_once(modification("classes/db_cidadao_classe.php"));
  require_once(modification("model/logCgm.model.php"));

  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  db_postmemory($HTTP_POST_VARS);
  db_postmemory($HTTP_SERVER_VARS);

  $oPost = db_utils::postMemory($_POST);
  $oGet  = db_utils::postMemory($_GET);

  $clcgm 					= new cl_cgm;
  $clcgmalt 			= new cl_cgmalt;
  $cldb_cgmruas 	= new cl_db_cgmruas;
  $cldb_cgmbairro = new cl_db_cgmbairro;
  $cldb_cgmcpf 		= new cl_db_cgmcpf;
  $cldb_cgmcgc 		= new cl_db_cgmcgc;
  $cldb_replica		= new cl_db_replica;
  $clcidadao      = new cl_cidadao;
  $clcidadaocgm		= new cl_cidadaocgm;
  $clissbase      = new cl_issbase;
  $cllogcgm       = new logcgm;

  $oDaoCgmFisico   = db_utils::getDao('cgmfisico');
  $oDaoCgmJuridico = db_utils::getDao('cgmjuridico');


  $db_opcao = 2;
  $db_botao = false;
  $sqlerro  = false;

  if(db_permissaomenu(db_getsession("DB_anousu"),604,1306) == "false"){
  	db_msgbox("Usuário sem permissão para alterar CGM !");
  	if ( !isset($inconsistenciaSimples)){
  	  db_redireciona($HTTP_REFERER);
  	}
  }

  if (isset($z01_numcgm) && !empty($z01_numcgm)) {

	  $sCampos  = "cgm.z01_numcgm,cgm.z01_nome as nomeantigo,cgm.z01_ender as enderecoantigo";
	  $sSqlCgm  = $clcgm->sql_query($z01_numcgm,$sCampos,null,"");
//	  die($sSqlCgm);
	  $rsSqlCgm = $clcgm->sql_record($sSqlCgm);
	  if ($clcgm->numrows > 0) {
	    $oCgm = db_utils::fieldsMemory($rsSqlCgm,0);
	  }
  }


  if (isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]=="Alterar") {

  $cgm_alt             = $z01_numcgm;
  $result_cgm          = $clcgm->sql_record($clcgm->sql_query_file($cgm_alt));
  $numrows             = $clcgm->numrows;
  $cgccpf              = "";
  $sZero               = "";
  $lPermissaoCpfZerado = "false";

  if ( isset($z01_cgc) || isset($z01_cpf) || isset($cnpj) || isset($cpf)) {

    if((isset($z01_cgc) && $z01_cgc != "") || isset($cnpj)){

      $cgccpf              = $z01_cgc;
      $sZero               = "00000000000000";
      $lPermissaoCpfZerado = db_permissaomenu(db_getsession("DB_anousu"),604,4459);
    }

    if((isset($z01_cpf) && $z01_cpf != "") || isset($cpf)){
      $sZero               = "00000000000";
      $cgccpf              = $z01_cpf;
      $lPermissaoCpfZerado = db_permissaomenu(db_getsession("DB_anousu"),604,3775);

    }

    if ($cgccpf != '') {

      //Validações de cpf e cnpj.
      $rsCgcCpf = $clcgm->sql_record($clcgm->sql_query("","cgm.z01_cgccpf","","z01_numcgm = {$z01_numcgm}"));
      $iCgcCpf  = db_utils::fieldsMemory($rsCgcCpf, 0)->z01_cgccpf;

      if ($cgccpf == "{$sZero}") {

        if ($iCgcCpf != "{$sZero}" && $lPermissaoCpfZerado == "false") {

          echo "
                  <script>
	                  alert('Você não tem permissão para incluir CPF/CNPJ zerado, contate o administrador para obter esta permissão!');
	             ";
	        echo "
	                  document.location.href = 'prot1_cadcgm002.php';";
	        echo"
                 </script>";
          exit;
        }
      }

    } else {

    	if ( $lPermissaoCpfZerado == "false" ) {
    	  $sTipo = (isset($cnpj)?"CNPJ":"CPF");
    	  echo " <script> ";
    	  echo "  alert('Informe o campo {$sTipo}'); ";
    	  echo " </script> ";
    	  db_redireciona("prot1_cadcgm002.php?chavepesquisa={$chavepesquisa}");
    	  exit;
    	}

    }

    $result = $clcgm->sql_record($clcgm->sql_query("","z01_cgccpf,cgm.z01_numcgm,cgm.z01_nome",""," z01_cgccpf = '$cgccpf' and z01_cgccpf <> '' and z01_cgccpf <> '{$sZero}' and cgm.z01_numcgm <> $z01_numcgm"));
    $z01_numcgm_old = $z01_numcgm;

    if($clcgm->numrows > 0){
       db_fieldsmemory($result,0);
       echo "
						 <script>
						   alert('(2) CNPJ/CPF já existe no cadastro do CGM \\n CGM número: $z01_numcgm \\n Nome: $z01_nome');
						 </script>";
    if(isset($testanome) && !isset($inconsistenciaSimples)){

	     $camp = split("\|",$valores);
	     $vals = "";
	     $vir = "";
	     for($f=1;$f<count($camp);$f++){
	       $vals .= $vir.$$camp[$f];
	       $vir = ",";
	     }
	     if ( !isset($inconsistenciaSimples)){
	       db_redireciona("func_nome.php?z01_numcgm=$z01_numcgm_old&funcao_js=$funcao_js&testanome=true");
	     }

	 }else{

	 	 if ( !isset($inconsistenciaSimples)){
	     db_redireciona("prot1_cadcgm002.php");
	     exit;
	 	 } else {
	 	 	  db_redireciona("prot1_cadcgm002.php?chavepesquisa={$chavepesquisa}&testanome=1&inconsistenciaSimples=1");
	 	 }

	 }
    }
  }

  $cgccpf = (@$z01_cgc==""?@$z01_cpf:@$z01_cgc);
  $HTTP_POST_VARS["z01_cgccpf"] = (@$z01_cgc==""?@$z01_cpf:@$z01_cgc);
  // grava base anterior

   $sWhere       = "q02_numcgm = {$z01_numcgm}";
   $sSqlIssBase  = $clissbase->sql_query(null,"issbase.*",null,$sWhere);
   $rsSqlIssBase = $clissbase->sql_record($sSqlIssBase);

   if ($clissbase->numrows > 0) {

     $oIssBase = db_utils::fieldsMemory($rsSqlIssBase,0);
     db_inicio_transacao();

     try {

       $sNomeAntigo = "";
       if (isset($oCgm->nomeantigo) && !empty($oCgm->nomeantigo)) {
         $sNomeAntigo = $oCgm->nomeantigo;
       }

       if (isset($oPost->z01_nome) && !empty($oPost->z01_nome)) {

       	 if (trim($oPost->z01_nome) != trim($sNomeAntigo)) {
       	   $cllogcgm->identificaAlteracao($oIssBase->q02_inscr,2,8,$oPost->z01_nome,$sNomeAntigo,"","");
       	 }
       }

       $sEnderecoAntigo = "";
       if (isset($oCgm->enderecoantigo) && !empty($oCgm->enderecoantigo)) {
         $sEnderecoAntigo = $oCgm->enderecoantigo;
       }

       if (isset($oPost->z01_ender) && !empty($oPost->z01_ender)) {

       	 if (trim($oPost->z01_ender) != trim($sEnderecoAntigo)) {
       	   $cllogcgm->identificaAlteracao($oIssBase->q02_inscr,2,2,"","",$oPost->z01_ender,$sEnderecoAntigo);
       	 }
       }

       $cllogcgm->gravarLog();
     } catch ( Exception $eExeption ){

       $sqlerro  = true;
       $sMsgErro = $eExeption->getMessage();
     }

     db_fim_transacao($sqlerro); //descomentar
     //db_query('rollback');
   }

	 // ************************** cgm *************************

	 db_inicio_transacao();
 	 $clcgm->alterar($z01_numcgm);
 	 $clcgm->z01_numcgm = $z01_numcgm;


 	 /**
 	  * Nova funcionalidade de alteração de cgmfisico e cgmjuridico
 	  */

 	 if (strlen($cgccpf) <= 11) {

 	   $oDaoCgmFisico->excluir(null, "z04_numcgm = {$z01_numcgm}");
 	   if ($oDaoCgmFisico->erro_status == "0") {
 	     $oDaoCgmFisico->erro(false, false);
 	   }

 	   $oDaoCgmJuridico->excluir(null, "z08_numcgm = {$z01_numcgm}");
 	   if ($oDaoCgmJuridico->erro_status == "0") {
 	     $oDaoCgmJuridico->erro(false, false);
 	   }

 	   $oDaoCgmFisico->z04_numcgm = $z01_numcgm;
 	   $oDaoCgmFisico->z04_rhcbo  = $rh70_sequencial;
 	   $oDaoCgmFisico->incluir(null);

 	   if ($oDaoCgmFisico->erro_status == "0") {
 	     $oDaoCgmFisico->erro(false, false);
 	   }

 	 } else {

 	   $oDaoCgmFisico->excluir(null, "z04_numcgm = {$z01_numcgm}");
 	   if ($oDaoCgmFisico->erro_status == "0") {
 	     $oDaoCgmFisico->erro(false, false);
 	   }

 	   $oDaoCgmJuridico->excluir(null, "z08_numcgm = {$z01_numcgm}");
 	   if ($oDaoCgmJuridico->erro_status == "0") {
 	     $oDaoCgmJuridico->erro(false, false);
 	   }

 	   $oDaoCgmJuridico->z08_numcgm = $z01_numcgm;
 	   $oDaoCgmJuridico->z08_nire   = "";
 	   $oDaoCgmJuridico->incluir(null);

 	   if ($oDaoCgmJuridico->erro_status == "0") {
 	     $oDaoCgmJuridico->erro(false, false);
 	   }

 	 }


 	 //Inserir vinculo na cidadao cgm se campo ov02_seq !=0 e $ov02_sequencial !=0
		if(isset($ov02_seq) && isset($ov02_sequencial) && $ov02_seq != 0 && $ov02_sequencial != 0){

			$clcidadaocgm->ov03_cidadao = $ov02_sequencial;
			$clcidadaocgm->ov03_seq 		= $ov02_seq;
			$clcidadaocgm->ov03_numcgm 	= $z01_numcgm;
			$clcidadaocgm->incluir(null);

			$clcidadao->ov02_situacaocidadao = 1;

	 		$clcidadao->alterar_where(null,null,"ov02_sequencial = $ov02_sequencial and ov02_ativo is true");

		}

	 	if($clcidadao->erro_status == '0'){

		  db_query("rollback");
		  $clcgm->erro_msg		= $clcidadao->erro_msg;
		  $clcgm->erro_status = "0";
		  //echo $clcidadaocgm->erro_msg;

	 	}
	 	//***************************************************

    if ($z01_numcgm > 0 && !isset($inconsistenciaSimples)) {

      // Var "tipoDocumento" refere-se a ligação da tabela de caddocumento a cadtipodocumento.

       $script = "
        <script type='text/javascript'>
          var get = 'z06_numcgm=$clcgm->z01_numcgm&z01_nome=$clcgm->z01_nome&tipoDocumento=1';

          var confirme = confirm('Deseja cadastrar os Documentos?');
          if (confirme){
            location.href='prot1_lancdoc001.php?'+get;
          }
        </script>
       ";
       echo $script;
     }


	 if($j14_codigo!=""){
           $result = $cldb_cgmruas->sql_record($cldb_cgmruas->sql_query($cldb_cgmruas->z01_numcgm,"*",""," db_cgmruas.z01_numcgm = ".$clcgm->z01_numcgm.""));
	   $HTTP_POST_VARS['j14_codigo'] = $j14_codigo;
	   if($cldb_cgmruas->numrows > 0){
             $cldb_cgmruas->alterar($clcgm->z01_numcgm);
  	     //$cldb_cgmruas->erro(true,true);
	   }else{
             $cldb_cgmruas->incluir($clcgm->z01_numcgm);
  	     //$cldb_cgmruas->erro(true,true);
	   }
	 } else {
     $result = $cldb_cgmruas->sql_record($cldb_cgmruas->sql_query($cldb_cgmruas->z01_numcgm,"*",""," db_cgmruas.z01_numcgm = ".$clcgm->z01_numcgm.""));
	   $HTTP_POST_VARS['j14_codigo'] = $j14_codigo;
	   if($cldb_cgmruas->numrows > 0){
             $cldb_cgmruas->excluir($clcgm->z01_numcgm);
  	     //$cldb_cgmruas->erro(true,true);
	   }
	 }
	 if($j13_codi!=""){
	   $HTTP_POST_VARS['j13_codi'] = $j13_codi;
           $result = $cldb_cgmbairro->sql_record($cldb_cgmbairro->sql_query($cldb_cgmbairro->z01_numcgm,"*",""," db_cgmbairro.z01_numcgm = ".$clcgm->z01_numcgm.""));
	   if($cldb_cgmbairro->numrows > 0){
	     $cldb_cgmbairro->alterar($clcgm->z01_numcgm);
  	     //$cldb_cgmbairro->erro(false,false);
	   }else{
             $cldb_cgmbairro->incluir($clcgm->z01_numcgm);
  	     //$cldb_cgmbairro->erro(true,true);
	   }
	 }else{
	   $HTTP_POST_VARS['j13_codi'] = $j13_codi;
           $result = $cldb_cgmbairro->sql_record($cldb_cgmbairro->sql_query($cldb_cgmbairro->z01_numcgm,"*",""," db_cgmbairro.z01_numcgm = ".$clcgm->z01_numcgm.""));
	   if($cldb_cgmbairro->numrows > 0){
	     $cldb_cgmbairro->excluir($clcgm->z01_numcgm);
	     //$cldb_cgmbairro->erro(true,true);
	   }
	 }
     $cldb_cgmcpf->sql_record($cldb_cgmcpf->sql_query_file($clcgm->z01_numcgm));
     if($cldb_cgmcpf->numrows>0){
       if($z01_cpf!=""){
	 $cldb_cgmcpf->alterar($clcgm->z01_numcgm);
  	 //$cldb_cgmcpf->erro(true,true);
       }elseif($z01_cpf==""){
         $cldb_cgmcpf->excluir($clcgm->z01_numcgm);
  	 //$cldb_cgmcpf->erro(true,true);
       }
     }else{
       if($z01_cpf!=""){
	 $cldb_cgmcpf->incluir($clcgm->z01_numcgm);
  	 //$cldb_cgmcpf->erro(true,true);
       }
     }
     $cldb_cgmcgc->sql_record($cldb_cgmcgc->sql_query_file($clcgm->z01_numcgm));
     if($cldb_cgmcgc->numrows>0){
       if($z01_cgc!=""){
	 $cldb_cgmcgc->alterar($clcgm->z01_numcgm);
  	 //$cldb_cgmcgc->erro(true,true);
       }elseif($z01_cgc==""){
	 $cldb_cgmcgc->excluir($clcgm->z01_numcgm);
  	 //$cldb_cgmcgc->erro(true,true);
       }
     }else{
       if($z01_cgc!=""){
	 $cldb_cgmcgc->incluir($clcgm->z01_numcgm);
  	 //$cldb_cgmcgc->erro(true,true);
       }
     }
     if(isset($testanome)){
       $camp = split("\|",@$valores);
       $vals = "";
       $vir = "";
       for($f=1;$f<count($camp);$f++){
         $vals .= $vir.$$camp[$f];
	 $vir = ",";
       }
       db_fim_transacao(); //descomentar
       //db_query('rollback');

       $clcgm->erro(true,false);
       if (isset($autoinfra)&&$autoinfra!=""){
	 if($clcgm->erro_status=="0"){
         }else{
	   echo "<script>
		        parent.location.href='fis1_auto001.php?z01_numcgm=$z01_numcgm&abas=1&pri=true';
	          parent.document.form1.db_iframe_altcgm.hide();
		      </script>";
	 }
       }
       if (isset($autonotific)&&$autonotific!=""){
	 if($clcgm->erro_status=="0"){
         }else{
	   echo "<script>
		        parent.location.href='fis1_fiscal001.php?z01_numcgm=$z01_numcgm&abas=1&pri=true';
	          parent.document.form1.db_iframe_altcgm.hide();
		       </script>";
	       }
       }
       if (isset($autoprot)&&$autoprot!=""){
	 	if($clcgm->erro_status=="0"){
         }else{
	   echo "<script>
             parent.document.form1.p58_requer.value='" . $HTTP_POST_VARS["z01_nome"] . "';
	           parent.document.form1.z01_nome.value='" . $HTTP_POST_VARS["z01_nome"] . "';
             parent.document.form1.alterou.value=1;
             parent.db_iframe_altcgm.hide();
		       </script>";
//	   echo "<script>
//		  parent.location.href='pro4_protprocesso002.php?p58_codigo=$p58_codigo&p58_numcgm=$z01_numcgm';
//	          parent.document.form1.db_iframe_altcgm.hide();
//		 </script>";
	 }
       } else if (isset($execfunction)) {
         echo "<script>{$execfunction}({$z01_numcgm})</script>";
       }
       if (isset($autoc)&&$autoc!=""){

       	if($clcgm->erro_status=="0"){

        }else{
	   			echo "<script>
	                 parent.document.form1.submit();
	                 parent.db_iframe_altcgm.hide();
		            </script>";
 	     exit;
	 }
       }
       if($z01_cgc == "" && $z01_cpf == ""){

       	if ( !isset($inconsistenciaSimples)){
       	  db_redireciona("func_nome.php?z01_numcgm=$z01_numcgm&funcao_js=$funcao_js&testanome=true");
       	}

       }else{

       	if ( !isset($inconsistenciaSimples)){
       	   db_redireciona("func_nome.php?script=$testanome&valores=$vals");
       	}
       }
       if ( !isset($inconsistenciaSimples)){
         exit;
       } else {
       	echo "<script>parent.fechaIframeCgc();</script>";
       }


     } else {

       db_fim_transacao();  //descoment
     	//db_query('rollback');

       $clcgm->erro(true,true);
     }


  } else if(isset($chavepesquisa)) {

    $result = $clcgm->sql_record($clcgm->sql_query($chavepesquisa));

    db_fieldsmemory($result,0);

    $db_botao = true;

    $sQueryCidadaoCGM = " select ov03_numcgm,ov03_cidadao as ov02_sequencial,ov03_seq as ov02_seq
                            from cidadaocgm
                           where ov03_numcgm = $z01_numcgm ";

    $rsCidadaoCGM = $clcidadaocgm->sql_record($sQueryCidadaoCGM);

    if($clcidadaocgm->numrows > 0){

    	db_fieldsmemory($rsCidadaoCGM,0);
    }

    if(isset($ov02_seq) && isset($ov02_sequencial) && $ov02_seq != 0 && $ov02_sequencial != 0){
    	$ov03_numcgm = $chavepesquisa;
    }

    $oDaoCgmFisico = db_utils::getDao('cgmfisico');
    $sSqlCgmFisico = $oDaoCgmFisico->sql_query(null, "*", null, "z04_numcgm = {$z01_numcgm}");
    $rsCgmFisico   = $oDaoCgmFisico->sql_record($sSqlCgmFisico);

    if($oDaoCgmFisico->numrows > 0){

      $oCgmFisico      = db_utils::fieldsMemory($rsCgmFisico, 0);

      $rh70_sequencial = $oCgmFisico->rh70_sequencial;
      $rh70_descr      = $oCgmFisico->rh70_estrutural . " - " . $oCgmFisico->rh70_descr;

    }


  }
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
	db_app::load('strings.js,scripts.js,datagrid.widget.js,prototype.js');
	db_app::load('estilos.css,grid.style.css');
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<?
if(!isset($testanome)){
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<?
}else{
  db_input('autoinfra',10,'',true,'hidden',3);
  db_input('autonotific',10,'',true,'hidden',3);
  db_input('autoprot',10,'',true,'hidden',3);
  db_input('autoc',10,'',true,'hidden',3);
}
?>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
    <center>
	<?
    include(modification("forms/db_frmcgm.php"));
	?>
    </center>
	</td>
  </tr>
</table>
<?
if(!isset($testanome)){//esta variavel eh passada qdo esta pagina é acessada pela func_nome, pq ela é montada dentro do iframe de pesquisa e não necessita de menu, se não for a func_nome que acessa o menu é montado normalmente
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
}
?>
</body>
<?
if (isset($sMsgErro) && !empty($sMsgErro)) {
	db_msgbox($sMsgErro);
}

$clcgm->erro(true,false);
$cldb_cgmruas->erro(true,false);
$cldb_cgmbairro->erro(true,false);

if($clcgm->erro_status=="0"){
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clcgm->erro_campo!=""){
    echo "<script> document.form1.".$clcgm->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clcgm->erro_campo.".focus();</script>";
  }
}




//if(($db_botao==false))
//  echo "<script>js_func_nome();</script>  ";
?>
</html>