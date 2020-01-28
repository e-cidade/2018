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
  include("classes/db_db_cgmruas_classe.php");
  include("classes/db_db_cgmbairro_classe.php");
  include("classes/db_db_cgmcgc_classe.php");
  include("classes/db_db_cgmcpf_classe.php");
  include("classes/db_db_cepmunic_classe.php");
  include("classes/db_ruascep_classe.php");
  db_postmemory($HTTP_SERVER_VARS);
  db_postmemory($HTTP_POST_VARS);
  $db_opcao = 1;
  $clcgm = new cl_cgm;
  $cldb_cgmruas = new cl_db_cgmruas;
  $cldb_cgmbairro = new cl_db_cgmbairro;
  $cldb_cgmcpf = new cl_db_cgmcpf;
  $cldb_cgmcgc = new cl_db_cgmcgc;
  $db_botao = false;
  if (isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"] =="Incluir"){
  $cgccpf = "";
  if(isset($z01_cgc) || isset($z01_cpf)){
    if(isset($z01_cgc) && $z01_cgc != ""){
      $cgccpf = $z01_cgc;
    }
    if(isset($z01_cpf) && $z01_cpf != ""){
      $cgccpf = $z01_cpf;
    }
    $cgccpf = str_replace(".","",$cgccpf);
    $cgccpf = str_replace("/","",$cgccpf);
    $cgccpf = str_replace("-","",$cgccpf); 
    //$result = $clcgm->sql_record($clcgm->sql_query("","z01_cgccpf,cgm.z01_numcgm,cgm.z01_nome",""," z01_cgccpf = '$cgccpf' and z01_cgccpf <> '' and cgm.z01_numcgm <> $z01_numcgm")); 
    $result = $clcgm->sql_record($clcgm->sql_query("","z01_cgccpf,cgm.z01_numcgm","","z01_cgccpf = '$cgccpf' and z01_cgccpf <> '00000000000' and z01_cgccpf <> '00000000000000'")); 
    $z01_numcgm_old = $z01_numcgm;
    if($clcgm->numrows > 0){
       db_fieldsmemory($result,0);
       echo "
       <script>
	 var x = confirm('CNPJ/CPF já existe no cadastro do CGM \\n CGM número: $z01_numcgm \\n Deseja visualiza-lo?');
	 if(x)
	   document.location.href = 'sau1_cgm001.php?mostradadoscgm=sim&z01_numcgm=$z01_numcgm';
	 else ";
           if(isset($testanome)){
	     $camp = split("\|",$valores);
	     $vals = "";
	     $vir = "";
	     for($f=1;$f<count($camp);$f++){
	       $vals .= $vir.$$camp[$f];
	       $vir = ",";
	     }
	     if($z01_cgc == "" && $z01_cpf == ""){
	       echo "
                 document.location.href = 'func_nome.php?z01_numcgm=$z01_numcgm_old&funcao_js=$funcao_js&testanome=true';";
	     }else{
	      echo "
                document.location.href = 'func_nome.php?script=$testanome&valores=$vals';";
	     }
	 echo "
           document.location.href = 'func_nome.php?z01_cgccpf=vazio&funcao_js=$funcao_js&testanome=true';";
	   }else{
	 echo "
	   document.location.href = 'sau1_cgm001.php';";
	   }
	   echo"
       </script>";
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
	 if($j14_codigo!=""){
           $cldb_cgmruas->incluir($clcgm->z01_numcgm);
	 }
	 if($j13_codi!=""){
           $cldb_cgmbairro->incluir($clcgm->z01_numcgm);
	 }
     if ($z01_cgc!=""){
        $cldb_cgmcgc->incluir($clcgm->z01_numcgm);
     } elseif ($z01_cpf != ""){
        $cldb_cgmcpf->incluir($clcgm->z01_numcgm);
     }
     $z01_numcgm = $clcgm->z01_numcgm;
     if(isset($testanome)){
       $camp = split("\|",$valores);
       $vals = "";
       $vir = "";
       for($f=1;$f<count($camp);$f++){
	 $vals .= $vir.$$camp[$f];
	 $vir = ",";
       }
       db_msgbox($cl_cgm->erro_msg);
       db_fim_transacao();
       if($z01_cgc == "" && $z01_cpf == ""){
         db_redireciona("func_nome.php?z01_numcgm=$z01_numcgm&funcao_js=$funcao_js&testanome=true");
       }else{
         db_redireciona("func_nome.php?script=$testanome&valores=$vals");
       }
     }else{
       db_msgbox($clcgm->erro_msg);
       db_fim_transacao();
       db_redireciona("sau1_cgs001.php?cgm=$clcgm->z01_numcgm");
     }
     $db_botao = true;
  }else{
    $db_botao = true;
  }
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<?
if(!isset($testanome)){
?>
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
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
<table width="790" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
	<?
	include("forms/db_frmcgm.php");

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
 //db_redireciona("sau1_cgs001.php&cgm=$clcgm->z01_numcgm");
}else{
  $clcgm->erro(true,false);
}
?>