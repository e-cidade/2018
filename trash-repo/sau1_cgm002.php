<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
  include("dbforms/db_funcoes.php");
  include("classes/db_cgm_classe.php");
  include("classes/db_cgmalt_classe.php");
  include("classes/db_db_cgmruas_classe.php");
  include("classes/db_db_cgmbairro_classe.php");
  include("classes/db_db_cgmcgc_classe.php");
  include("classes/db_db_cgmcpf_classe.php");
  include("classes/db_db_cepmunic_classe.php");
  include("classes/db_ruascep_classe.php");
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  db_postmemory($HTTP_POST_VARS);
  $db_opcao = 2;
  $clcgm = new cl_cgm;
  $clcgmalt = new cl_cgmalt;
  $cldb_cgmruas = new cl_db_cgmruas;
  $cldb_cgmbairro = new cl_db_cgmbairro;
  $cldb_cgmcpf = new cl_db_cgmcpf;
  $cldb_cgmcgc = new cl_db_cgmcgc;
  $db_botao = false;
  if (isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]=="Alterar"){
  $cgm_alt=$z01_numcgm;  
  $result_cgm=$clcgm->sql_record($clcgm->sql_query_file($cgm_alt));  
  $numrows=$clcgm->numrows;
  if ($numrows!=0){
    db_fieldsmemory($result_cgm,0);
    $clcgmalt->z05_numcgm   = $z01_numcgm    ;
    $clcgmalt->z05_numcon   = $z01_numcon    ;
    $clcgmalt->z05_estciv   = $z01_estciv    ;
    $clcgmalt->z05_nacion   = $z01_nacion    ;
    $clcgmalt->z05_tipcre   = $z01_tipcre    ;
    $clcgmalt->z05_hora_alt = db_hora();  
    $clcgmalt->z05_data_alt = date ('Y-m-d',db_getsession("DB_datausu"));  
    $clcgmalt->z05_login_alt= db_getsession("DB_id_usuario") ;
    $clcgmalt->z05_nomefanta= $z01_nomefanta ;
    $clcgmalt->z05_contato  = $z01_contato   ;
    $clcgmalt->z05_sexo     = $z01_sexo      ;
    $clcgmalt->z05_fax      = $z01_fax       ;
    $clcgmalt->z05_nasc     = $z01_nasc      ;
    $clcgmalt->z05_mae      = $z01_mae       ;
    $clcgmalt->z05_pai      = $z01_pai       ;
    $clcgmalt->z05_ultalt   = $z01_ultalt    ;
    $clcgmalt->z05_cpf      = @$z01_cpf       ;
    $clcgmalt->z05_cgc      = @$z01_cgc       ;
    $clcgmalt->z05_cep      = $z01_cep        ; 
    $clcgmalt->z05_ender    = $z01_ender     ;
    $clcgmalt->z05_cxposcon = $z01_cxposcon  ;
    $clcgmalt->z05_cepcon   = $z01_cepcon    ;
    $clcgmalt->z05_baicon   = $z01_baicon    ;
    $clcgmalt->z05_celcon   = $z01_celcon    ;
    $clcgmalt->z05_bairro   = $z01_bairro    ;
    $clcgmalt->z05_uf       = $z01_uf        ;
    $clcgmalt->z05_telef    = $z01_telef     ;
    $clcgmalt->z05_telcon   = $z01_telcon    ;
    $clcgmalt->z05_telcel   = $z01_telcel    ;
    $clcgmalt->z05_profis   = $z01_profis    ;
    $clcgmalt->z05_incest   = $z01_incest    ;
    $clcgmalt->z05_ident    = $z01_ident     ;
    $clcgmalt->z05_endcon   = $z01_endcon    ;
    $clcgmalt->z05_cxpostal = $z01_cxpostal  ;
    $clcgmalt->z05_comcon   = $z01_comcon    ;
    $clcgmalt->z05_cgccpf   = $z01_cgccpf    ;
    $clcgmalt->z05_ufcon    = $z01_ufcon     ;
    $clcgmalt->z05_muncon   = $z01_muncon    ;
    $clcgmalt->z05_nome     = $z01_nome      ;
    $clcgmalt->z05_munic    = $z01_munic     ;
    $clcgmalt->z05_emailc   = $z01_emailc    ;
    $clcgmalt->z05_email    = $z01_email     ;
    $clcgmalt->z05_numero   = $z01_numero    ;
    $clcgmalt->z05_cadast   = $z01_cadast    ;
    $clcgmalt->z05_login    = $z01_login     ;
    $clcgmalt->z05_compl    = $z01_compl    ; 
    $clcgmalt->z05_hora    = @$z01_hora    ; 
    $clcgmalt->z05_tipo_alt = "A" ; 
    $clcgmalt->incluir(null);
  }
  $cgccpf = "";
  if(isset($z01_cgc) || isset($z01_cpf)){
    if(isset($z01_cgc) && $z01_cgc != ""){
      $cgccpf = $z01_cgc;
    }
    if(isset($z01_cpf) && $z01_cpf != ""){
      $cgccpf = $z01_cpf;
    }
    if(db_permissaomenu(db_getsession("DB_anousu"),604,3775) == "false"){
      if(trim($cgccpf) == '00000000000'){
       echo "
       <script>
	 alert('Você não tem permissão para incluir CPF zerado, contate o administrador para obter esta permissão!');
	";
	if(!isset($testanome)){
	 echo "
	 document.location.href = 'prot1_cadcgm002.php';";
	}
	   echo"
       </script>";
        exit;
      }
    }
    $result = $clcgm->sql_record($clcgm->sql_query("","z01_cgccpf,cgm.z01_numcgm,cgm.z01_nome",""," z01_cgccpf = '$cgccpf' and z01_cgccpf <> '' and z01_cgccpf <> '00000000000' and cgm.z01_numcgm <> $z01_numcgm")); 
    $z01_numcgm_old = $z01_numcgm;
    if($clcgm->numrows > 0){
       db_fieldsmemory($result,0);
       echo "
	 <script>
	   alert('CNPJ/CPF já existe no cadastro do CGM \\n CGM número: $z01_numcgm \\n Nome: $z01_nome');
	 </script>";
         if(isset($testanome)){
	   $camp = split("\|",$valores);
	   $vals = "";
	   $vir = "";
	   for($f=1;$f<count($camp);$f++){
	     $vals .= $vir.$$camp[$f];
	     $vir = ",";
	   }
	   db_redireciona("func_nome.php?z01_numcgm=$z01_numcgm_old&funcao_js=$funcao_js&testanome=true");
	 }else{
	   db_redireciona("prot1_cadcgm002.php");
	 }
	 exit;
    }
  }
     $cgccpf = (@$z01_cgc==""?@$z01_cpf:@$z01_cgc);
     $HTTP_POST_VARS["z01_cgccpf"] = (@$z01_cgc==""?@$z01_cpf:@$z01_cgc);
     db_inicio_transacao();
 	 $clcgm->alterar($z01_numcgm);
 	 $clcgm->z01_numcgm = $z01_numcgm;
	 if($j14_codigo!=""){
           $result = $cldb_cgmruas->sql_record($cldb_cgmruas->sql_query($cldb_cgmruas->z01_numcgm,"*",""," db_cgmruas.z01_numcgm = ".$clcgm->z01_numcgm.""));
	   $HTTP_POST_VARS['j14_codigo'] = $j14_codigo;
	   if($cldb_cgmruas->numrows > 0){
             $cldb_cgmruas->alterar($clcgm->z01_numcgm); 
	   }else{
             $cldb_cgmruas->incluir($clcgm->z01_numcgm); 
	   }
	 }else{
           $result = $cldb_cgmruas->sql_record($cldb_cgmruas->sql_query($cldb_cgmruas->z01_numcgm,"*",""," db_cgmruas.z01_numcgm = ".$clcgm->z01_numcgm.""));
	   $HTTP_POST_VARS['j14_codigo'] = $j14_codigo;
	   if($cldb_cgmruas->numrows > 0){
             $cldb_cgmruas->excluir($clcgm->z01_numcgm); 
	   }
	 }
	 if($j13_codi!=""){
	   $HTTP_POST_VARS['j13_codi'] = $j13_codi;
           $result = $cldb_cgmbairro->sql_record($cldb_cgmbairro->sql_query($cldb_cgmbairro->z01_numcgm,"*",""," db_cgmbairro.z01_numcgm = ".$clcgm->z01_numcgm.""));
	   if($cldb_cgmbairro->numrows > 0){
	     $cldb_cgmbairro->alterar($clcgm->z01_numcgm); 
	   }else{
             $cldb_cgmbairro->incluir($clcgm->z01_numcgm); 
	   }
	 }else{
	   $HTTP_POST_VARS['j13_codi'] = $j13_codi;
           $result = $cldb_cgmbairro->sql_record($cldb_cgmbairro->sql_query($cldb_cgmbairro->z01_numcgm,"*",""," db_cgmbairro.z01_numcgm = ".$clcgm->z01_numcgm.""));
	   if($cldb_cgmbairro->numrows > 0){
	     $cldb_cgmbairro->excluir($clcgm->z01_numcgm); 
	   }
	 }  
     $cldb_cgmcpf->sql_record($cldb_cgmcpf->sql_query_file($clcgm->z01_numcgm)); 
     if($cldb_cgmcpf->numrows>0){
       if($z01_cpf!=""){
	 $cldb_cgmcpf->alterar($clcgm->z01_numcgm); 
       }elseif($z01_cpf==""){
         $cldb_cgmcpf->excluir($clcgm->z01_numcgm); 
       }
     }else{
       if($z01_cpf!=""){
	 $cldb_cgmcpf->incluir($clcgm->z01_numcgm); 
       }
     }
     $cldb_cgmcgc->sql_record($cldb_cgmcgc->sql_query_file($clcgm->z01_numcgm)); 
     if($cldb_cgmcgc->numrows>0){
       if($z01_cgc!=""){
	 $cldb_cgmcgc->alterar($clcgm->z01_numcgm); 
       }elseif($z01_cgc==""){
	 $cldb_cgmcgc->excluir($clcgm->z01_numcgm); 
       }
     }else{
       if($z01_cgc!=""){
	 $cldb_cgmcgc->incluir($clcgm->z01_numcgm); 
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
       $clcgm->erro(true,false);
       db_fim_transacao();
       if($z01_cgc == "" && $z01_cpf == ""){
         db_redireciona("func_nome.php?z01_numcgm=$z01_numcgm&funcao_js=$funcao_js&testanome=true");
       }else{
         db_redireciona("func_nome.php?script=$testanome&valores=$vals");
       }
       exit;
     }else{
       $clcgm->erro(true,true);
       db_fim_transacao();
     }
  }else if(isset($chavepesquisa)){
    $result = $clcgm->sql_record($clcgm->sql_query($chavepesquisa)); 
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
	<?
    include("forms/db_frmcgm.php");
	?>
	</td>
  </tr>
</table>
</body>
</html>
<?
if($clcgm->erro_status=="0"){
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clcgm->erro_campo!=""){
    echo "<script> document.form1.".$clcgm->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clcgm->erro_campo.".focus();</script>";
  }
}else{
 db_msgbox($clcgm->erro_msg);
 
}
?>