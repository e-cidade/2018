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
include("classes/db_rhrubelemento_classe.php");
include("classes/db_rhrubelementoprinc_classe.php");
include("classes/db_rhrubricas_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clrhrubelemento      = new cl_rhrubelemento;
$clrhrubelementoprinc = new cl_rhrubelementoprinc;
$clrhrubricas         = new cl_rhrubricas;
$db_opcao             = 22;
$db_botao             = false;
if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
}
if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    if($rh24_eleprinc == 1){
      $clrhrubelementoprinc->excluir($rh23_rubric);
      //db_msgbox($clrhrubelementoprinc->erro_msg);
      if($clrhrubelementoprinc->erro_status == 0){
        $erro_msg = $clrhrubelementoprinc->erro_msg;
        $sqlerro = true;
      }
      if($sqlerro == false){
        $clrhrubelementoprinc->incluir($rh23_rubric,$rh23_codele);
        $erro_msg = $clrhrubelementoprinc->erro_msg;
        if($clrhrubelementoprinc->erro_status == 0){
          $sqlerro = true;
        }
      }   
    }

    if($sqlerro==false){
      $clrhrubelemento->incluir($rh23_rubric,$rh23_codele);
      $erro_msg = $clrhrubelemento->erro_msg;
      if($clrhrubelemento->erro_status == 0 ){
        $sqlerro=true;
      }
    }
      
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    if($rh24_eleprinc == 1){
      $clrhrubelementoprinc->excluir($rh23_rubric);
      if($clrhrubelementoprinc->erro_status == 0){
        $erro_msg = $clrhrubelementoprinc->erro_msg;
        $sqlerro = true;
      }
      if($sqlerro==false){
        $clrhrubelemento->excluir($rh23_rubric,$rh23_codele);
        if($clrhrubelemento->erro_status == 0){
          $erro_msg = $clrhrubelemento->erro_msg;
          $sqlerro = true;
        }
        if($sqlerro==false){
          $clrhrubelementoprinc->incluir($rh23_rubric,$rh23_codele); 
          $erro_msg = $clrhrubelementoprinc->erro_msg;
          if($clrhrubelementoprinc->erro_status == 0){
            $erro_msg = $clrhrubelementoprinc->erro_msg;
            $sqlerro = true;
          }
        }
      }
    }else{
      $clrhrubelemento->excluir($rh23_rubric,$rh23_codele);
      if($clrhrubelemento->erro_status == 0){
        $erro_msg = $clrhrubelemento->erro_msg;
        $sqlerro = true;
      }

      if($sqlerro==false){
        $clrhrubelemento->incluir($rh23_rubric,$rh23_codele);
        if($clrhrubelemento->erro_status == 0){
          $erro_msg = $clrhrubelemento->erro_msg;
          $sqlerro = true;
        }
      }
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    
    $clrhrubelementoprinc->excluir($rh23_rubric,$rh23_codele);
    if($clrhrubelementoprinc->erro_status == 0){
      $erro_msg = $clrhrubelementoprinc->erro_msg;
      $sqlerro = true;
    }
  
    if($sqlerro==false){    
      $clrhrubelemento->excluir($rh23_rubric,$rh23_codele);
      $erro_msg = $clrhrubelemento->erro_msg;
      if($clrhrubelemento->erro_status==0){
        $sqlerro=true;
      }
    }
    
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
  // echo "<BR><BR>".($clrhrubelemento->sql_query(null,null,"*","","rh23_rubric = '$rh23_rubric' and rh23_codele = $rh23_codele"));
  $result = $clrhrubelemento->sql_record($clrhrubelemento->sql_query(null,null,"*","","rh23_rubric = '$rh23_rubric' and rh23_codele = $rh23_codele"));
  if($clrhrubelemento->numrows>0){
    db_fieldsmemory($result,0);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <center>
	<?
	include("forms/db_frmrhrubelemento.php");
	?>
    </center>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($clpagordemrec->erro_campo!=""){
        echo "<script> document.form1.".$clrhrubelemento->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clrhrubelemento->erro_campo.".focus();</script>";
    }
    
        
    db_redireciona("pes1_rhrubelemento001.php?rh23_rubric=".$rh23_rubric);
}
?>