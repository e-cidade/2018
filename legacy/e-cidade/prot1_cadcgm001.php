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
  require_once(modification("libs/db_sessoes.php"));
  require_once(modification("libs/db_usuariosonline.php"));
  require_once(modification("dbforms/db_funcoes.php"));
  require_once(modification("classes/db_cgm_classe.php"));
  require_once(modification("classes/db_db_cgmruas_classe.php"));
  require_once(modification("classes/db_db_cgmbairro_classe.php"));
  require_once(modification("classes/db_db_cgmcgc_classe.php"));
  require_once(modification("classes/db_db_cgmcpf_classe.php"));
  require_once(modification("classes/db_db_cepmunic_classe.php"));
  require_once(modification("classes/db_ruascep_classe.php"));
  require_once(modification("classes/db_db_replica_classe.php"));
  require_once(modification("classes/db_cidadao_classe.php"));
  require_once(modification("classes/db_cidadaocgm_classe.php"));
  require_once(modification("classes/db_cgmfisico_classe.php"));
  require_once(modification("classes/db_cgmjuridico_classe.php"));
  
  db_postmemory($HTTP_SERVER_VARS);
  db_postmemory($HTTP_POST_VARS);
  
  $db_opcao = 1;
  
  $clcgm           = new cl_cgm;
  $cldb_cgmruas    = new cl_db_cgmruas;
  $cldb_cgmbairro  = new cl_db_cgmbairro;
  $cldb_cgmcpf     = new cl_db_cgmcpf;
  $cldb_cgmcgc     = new cl_db_cgmcgc;
  $cldb_replica    = new cl_db_replica;
  $clcidadao       = new cl_cidadao();
  $clcidadaocgm    = new cl_cidadaocgm();
  $oDaoCgmFisico   = new cl_cgmfisico();
  $oDaoCgmJuridico = new cl_cgmjuridico();
  
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
  function js_abreJanelaBairros(){
    listaFuncbairros.jan.location.href = 'func_bairros.php?nomeBairro=' + document.form1.z01_bairro.value+"&funcao_js=parent.js_insereCODBairro|1";
    listaFuncbairros.mostraMsg();
    listaFuncbairros.show();
    listaFuncbairros.focus();
  }
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<?

  $db_botao = false;
  
  if (isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"] =="Incluir") {
    
    $cgccpf = "";
    if (isset($z01_cgc) || isset($z01_cpf)) {
      
      if (isset($z01_cgc) && $z01_cgc != "") {
        $cgccpf = $z01_cgc;
      }
      if (isset($z01_cpf) && $z01_cpf != "") {
        $cgccpf = $z01_cpf;
      }
      $cgccpf = str_replace(".","",$cgccpf);
      $cgccpf = str_replace("/","",$cgccpf);
      $cgccpf = str_replace("-","",$cgccpf); 
      $z01_cgccpf = $cgccpf;
      //$result = $clcgm->sql_record($clcgm->sql_query("","z01_cgccpf,cgm.z01_numcgm,cgm.z01_nome",""," z01_cgccpf = '$cgccpf' and z01_cgccpf <> '' and cgm.z01_numcgm <> $z01_numcgm")); 
      $result = $clcgm->sql_record($clcgm->sql_query("","z01_cgccpf,cgm.z01_numcgm","","z01_cgccpf = '$cgccpf' and z01_cgccpf <> '00000000000' and z01_cgccpf <> '00000000000000'")); 
      $z01_numcgm_old = $z01_numcgm;
      
      if ($clcgm->numrows > 0) {
        
         db_fieldsmemory($result,0);
         echo "
         <script>
            var x = confirm('CNPJ/CPF já existe no cadastro do CGM \\n CGM número: $z01_numcgm \\n Deseja visualiza-lo?');
            if(x)
              document.location.href = 'prot1_cadcgm001.php?mostradadoscgm=sim&z01_numcgm=$z01_numcgm';
            else "; 
         if (isset($testanome)) {
           
          $camp = split("\|",$valores);
          $vals = "";
          $vir = "";
          
          for ($f=1;$f<count($camp);$f++) {
            
             $vals .= $vir.$$camp[$f];
             $vir = ",";
           }
           if ($z01_cgc == "" && $z01_cpf == "") {
             echo " document.location.href = 'func_nome.php?z01_numcgm=$z01_numcgm_old&funcao_js=$funcao_js&testanome=true';";
           } else {
            echo " document.location.href = 'func_nome.php?script=$testanome&valores=$vals';";
           }
           echo " document.location.href = 'func_nome.php?z01_cgccpf=vazio&funcao_js=$funcao_js&testanome=true';";
         } else {
           echo " document.location.href = 'prot1_cadcgm001.php';";
         } 
        echo"</script>";
        exit;
      }
    }
    $HTTP_POST_VARS["z01_cgccpf"] = ($z01_cgc==""?$z01_cpf:$z01_cgc);
    db_inicio_transacao();
     /*if(isset($z01_cpf)){
     if($z01_cpf == '00000000000'){
             $HTTP_POST_VARS["z01_cgccpf"] = "";
       $clcgm->z01_cgccpf = '';
     }
   }*/
   $clcgm->z01_hora = db_hora();
   $clcgm->incluir($z01_numcgm);
   
   if (strlen($z01_cgccpf) <= 11) {
    
    $oDaoCgmFisico->z04_numcgm = $clcgm->z01_numcgm;
    $oDaoCgmFisico->z04_rhcbo  = $rh70_sequencial;
    $oDaoCgmFisico->incluir(null);
    
    if ($oDaoCgmFisico->erro_status == "0") {
      $oDaoCgmFisico->erro(false, false);
    }
    
   } else {
     
     $oDaoCgmJuridico->z08_numcgm = $clcgm->z01_numcgm;  
     $oDaoCgmJuridico->z08_nire   = "";
     $oDaoCgmJuridico->incluir(null);

     if ($oDaoCgmJuridico->erro_status == "0") {
       $oDaoCgmJuridico->erro(false, false);
     }
      
   }
   
   if ($j14_codigo!="") {
     
    $cldb_cgmruas->incluir($clcgm->z01_numcgm); 
    $cldb_cgmruas->erro(false,false);
   }  
   if ($j13_codi!="") {
     
    $cldb_cgmbairro->incluir($clcgm->z01_numcgm); 
    $cldb_cgmbairro->erro(false,false);
   }
   if ($z01_cgc!="") {
     
    $cldb_cgmcgc->incluir($clcgm->z01_numcgm);
    $cldb_cgmcgc->erro(false,false);
   } else if ($z01_cpf != "") {
     
      $cldb_cgmcpf->incluir($clcgm->z01_numcgm);
      $cldb_cgmcpf->erro(false,false);
   }
   $z01_numcgm = $clcgm->z01_numcgm;
   $z01_nome   = $clcgm->z01_nome;
   
   if ($z01_numcgm > 0){
     
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
   
   
   // ---------- NÃO ENTRA
   //Aqui verifico se o campo hidden ov02_sequencial esta preenchido se estiver inserir na cidadaocgm
   if (isset($ov02_sequencial) && trim($ov02_sequencial)!="") {
     
    $clcidadaocgm->ov03_cidadao = $ov02_sequencial;
    $clcidadaocgm->ov03_numcgm  = $clcgm->z01_numcgm;
    $clcidadaocgm->ov03_seq     = $ov02_seq;
      
    $clcidadaocgm->incluir(null);
      
    if ($clcidadaocgm->erro_status == '0') {
        
      db_query("rollback");
      $clcgm->erro_msg    = $clcidadaocgm->erro_msg;
      $clcgm->erro_status = "0";
      //echo $clcidadaocgm->erro_msg;
    
    }
    
    $clcidadao->ov02_situacaocidadao = 1;
    $clcidadao->alterar_where($ov02_sequencial,$ov02_seq,"ov02_sequencial = $ov02_sequencial and ov02_seq = $ov02_seq");
    
    if ($clcidadao->erro_status == '0') {
        
      db_query("rollback");
      $clcgm->erro_msg    = $clcidadao->erro_msg;
      $clcgm->erro_status = "0";
      //echo $clcidadaocgm->erro_msg;
    
    }
    
   } // ------------------------ FIM NÃO ENTRA -----------------------------------------------   
   
   if (isset($testanome)) {
     $camp = split("\|",$valores);
     $vals = "";
     $vir = "";
     for ($f=1;$f<count($camp);$f++) {
       $vals .= $vir.$$camp[$f];
       $vir = ",";
     }
     $clcgm->erro(true,false);
   
     db_fim_transacao();
     if ($z01_cgc == "" && $z01_cpf == "") {
        db_redireciona("func_nome.php?z01_numcgm=$z01_numcgm&funcao_js=$funcao_js&testanome=true");
     } else {
       db_redireciona("func_nome.php?script=$testanome&valores=$vals");
     }
   } else {
     $clcgm->erro(true,true);
     db_fim_transacao();

     $resultreplica = $cldb_replica->sql_record($cldb_replica->sql_query("","*","",""));
     if ($cldb_replica->numrows > 0) {
       
       for ($replica = 0; $replica < $cldb_replica->numrows; $replica++) {
       db_fieldsmemory($resultreplica,$replica);
                   
       $con_replica = pg_connect("host=$db40_ipreplica dbname=$db40_basereplica user=postgres");
       if ($con_replica!=false) {    
         
         $sqlreplica = "insert into cgm (  z01_numcgm,
              z01_cgccpf, 
              z01_nome   ,
              z01_ender  ,
              z01_munic  ,
              z01_uf     ,
              z01_cep    ,
              z01_telef  ,
              z01_ident  ,
              z01_digito ,
              z01_login  ,
              z01_bairro ,
              z01_incest ,
              z01_telcel ,
              z01_email  ,
              z01_endcon ,
              z01_muncon ,
              z01_baicon ,
              z01_ufcon  ,
              z01_cepcon ,
              z01_telcon ,
              z01_celcon ,
              z01_emailc
              ) 
              values ($z01_numcgm ,
                '".$HTTP_POST_VARS["z01_cgccpf"]."',
                '".$HTTP_POST_VARS["z01_nome"]."',  
                '".$HTTP_POST_VARS["z01_ender"].",".$HTTP_POST_VARS["z01_numero"]."', 
                '".$HTTP_POST_VARS["z01_munic"]."' ,
                '".$HTTP_POST_VARS["z01_uf"]."'   , 
                '".$HTTP_POST_VARS["z01_cep"]."'   ,
                '".$HTTP_POST_VARS["z01_telef"]."' ,
                '".$HTTP_POST_VARS["z01_ident"]."' ,
                '".$HTTP_POST_VARS["z01_digito"]."',
                '".$HTTP_POST_VARS["z01_login"]."' ,
                '".$HTTP_POST_VARS["z01_bairro"]."',
                '".$HTTP_POST_VARS["z01_incest"]."',
                '".$HTTP_POST_VARS["z01_telcel"]."',
                '".$HTTP_POST_VARS["z01_email"]."' ,
                '".$HTTP_POST_VARS["z01_endcon"]."',
                '".$HTTP_POST_VARS["z01_muncon"]."',
                '".$HTTP_POST_VARS["z01_baicon"]."',
                '".$HTTP_POST_VARS["z01_ufcon"]."' ,
                '".$HTTP_POST_VARS["z01_cepcon"]."',
                '".$HTTP_POST_VARS["z01_telcon"]."',
                '".$HTTP_POST_VARS["z01_celcon"]."',
                '".$HTTP_POST_VARS["z01_emailc"]."'
               )
              ";
             
      $result_replica = db_query($con_replica,$sqlreplica);
      
      if ($result_replica==false) {
        echo "erro no cgm $sqlreplica";exit;
        db_query("rollback");
      }
     }
      
     db_query("commit");
    }
     
   }
   require_once(modification("libs/db_conecta.php"));
       
   }
     $db_botao = true;
  
  } else {
    $db_botao = true;
  }
?>
<?
if (!isset($testanome)) {
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
}
?>
<table width="100%" height="430" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
  <?
  //die('antes do form');
  include(modification("forms/db_frmcgm.php"));

  ?>
  </td>
  </tr>
</table>
<?
if(!isset($testanome)){
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
}
?>
</body>
</html>



<?
//$cldb_cgmruas->erro(true,false);
//$cldb_cgmbairro->erro(true,false);
if($clcgm->erro_status=="0"){
  $db_botao=true;
  if($clcgm->erro_campo!=""){
    echo "<script> document.form1.".$clcgm->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clcgm->erro_campo.".focus();</script>";
  }
  $clcgm->erro(true,false);

}else{
  $clcgm->erro(true,true);
}
?>
