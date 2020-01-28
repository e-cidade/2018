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
include("classes/db_pcfornecon_classe.php");
include("classes/db_pcforneconpad_classe.php");
include("classes/db_pcforne_classe.php");
include("classes/db_empagemovconta_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clpcfornecon = new cl_pcfornecon;
$clpcforneconpad = new cl_pcforneconpad;
$clpcforne = new cl_pcforne;
$clempagemovconta = new cl_empagemovconta;
$db_opcao = 22;
$db_botao = false;

if(isset($alterar) || isset($excluir) || isset($incluir)){

  $sqlerro = false;

}

if(isset($novo) && isset($z01_numcgm)){

  $result = $clpcforne->sql_record($clpcforne->sql_query_file($z01_numcgm)); 

  if($clpcforne->numrows>0){

    $pc63_numcgm = $z01_numcgm;

  }else{  

    $clpcforne->pc60_dtlanc = date("Y-m-d",db_getsession("DB_datausu"));  
    $clpcforne->pc60_bloqueado  ='false';
    $clpcforne->pc60_numcgm = $z01_numcgm;
    $clpcforne->pc60_hora = db_hora();
    $clpcforne->pc60_usuario = db_getsession("DB_id_usuario");
    $clpcforne->incluir($z01_numcgm);

    if($clpcforne->erro_status==0){

      $clpcforne->erro_msg;
      db_msgbox("Erro ao cadastrar cgm como fornecedor... Contate suporte.\\n\\n".$clpcforne->erro_msg);

    }else{

      $pc63_numcgm = $z01_numcgm;

    } 

  }  

}



if(isset($incluir)){

  if($sqlerro==false){

    db_inicio_transacao();
    $pc63_contabanco=0;

    if(strlen($pc63_banco)>3){

      $sqlerro=true;
      $erro_msg = "Usuário:\\n\\nBanco deve ter no máximo três(3) caracteres.\\n\\nAdministrador:";

    }else if(strlen($pc63_agencia)>5){

      $sqlerro=true;
      $erro_msg = "Usuário:\\n\\nAgência deve ter no máximo cinco(5) caracteres.\\n\\nAdministrador:";

    }else if(strlen($pc63_conta)>12){

      $sqlerro = true;
      $erro_msg = "Usuário:\\n\\nConta deve ter no máximo doze(12) caracteres.\\n\\nAdministrador.";

    }

    if($sqlerro==false){

      if(isset($conferido)){

        $clpcfornecon->pc63_dataconf = date("Y-m-d",db_getsession("DB_datausu"));

      }
      
      $clpcfornecon->pc63_codigooperacao = str_pad(@$pc63_codigooperacao,4,"0",STR_PAD_LEFT);
      $clpcfornecon->incluir($pc63_contabanco);

      if($clpcfornecon->erro_status==0){

        $erro_msg = $clpcfornecon->erro_msg;
        $sqlerro=true;

      }else{

        $erro_msg = $clpcfornecon->erro_msg;

        if($pc64_contabanco=='t'){

          $result = $clpcfornecon->sql_record($clpcfornecon->sql_query(null,"pc63_contabanco as contabco","pc63_contabanco","pc63_numcgm = $pc63_numcgm"));

          if($result != 0 && $clpcfornecon->numrows){

            for($i=0;$i<$clpcfornecon->numrows;$i++){

              db_fieldsmemory($result,$i);
              $clpcforneconpad->excluir($contabco);

            }

          }

          $clpcforneconpad->pc64_contabanco = $clpcfornecon->pc63_contabanco;
          $clpcforneconpad->incluir($clpcfornecon->pc63_contabanco);

          if($clpcforneconpad->erro_status==0){

            $erro_msg = $clpcforneconpad->erro_msg;
            $sqlerro=true;

          }

        }

      }

    }

    db_fim_transacao($sqlerro);

  }

}else if(isset($alterar)){

   if(strlen($pc63_banco)>3){

     $sqlerro=true;
     $erro_msg = "Usuário:\\n\\nBanco deve ter no máximo três(3) caracteres.\\n\\nAdministrador:";

   }else if(strlen($pc63_agencia)>5){

     $sqlerro=true;
     $erro_msg = "Usuário:\\n\\nAgência deve ter no máximo cinco(5) caracteres.\\n\\nAdministrador:";

   }else if(strlen($pc63_conta)>12){

     $sqlerro = true;
     $erro_msg = "Usuário:\\n\\nConta deve ter no máximo doze(12) caracteres.\\n\\nAdministrador.";

   }

   if($sqlerro==false){

     db_inicio_transacao();

     if(isset($conferido)){

       $clpcfornecon->pc63_dataconf = date("Y-m-d",db_getsession("DB_datausu"));

     }
     $clpcfornecon->pc63_codigooperacao = str_pad(@$pc63_codigooperacao,4,"0",STR_PAD_LEFT);
     $clpcfornecon->alterar($pc63_contabanco);
     $erro_msg = $clpcfornecon->erro_msg;

     if($clpcfornecon->erro_status==0){

       $sqlerro=true;

     }else{

       $result = $clpcfornecon->sql_record($clpcfornecon->sql_query(null,"pc63_contabanco as contabco","pc63_contabanco","pc63_numcgm = $pc63_numcgm"));
       $numrows33 = $clpcfornecon->numrows;

       if($pc64_contabanco=='t'){

         if($result != 0 && $clpcfornecon->numrows){

           for($i=0;$i<$clpcfornecon->numrows;$i++){

             db_fieldsmemory($result,$i);
             $clpcforneconpad->excluir($contabco);

             if($clpcforneconpad->erro_status==0){

               $erro_msg = $clpcforneconpad->erro_msg;
               $sqlerro=true;
               break;

             }

           }

         }

         $clpcforneconpad->pc64_contabanco = $clpcfornecon->pc63_contabanco;
         $clpcforneconpad->incluir($clpcfornecon->pc63_contabanco);

         if($clpcforneconpad->erro_status==0){

           $erro_msg = $clpcforneconpad->erro_msg;
           $sqlerro=true;

         }

       }else{

         if($numrows33>0 && $padrao == "t"){

            $erro_msg = "Alteração não efetuada.\\n Esta é a conta padrão. Selecione outra antes de alterá-la."; 
            $sqlerro  = true; 

         }  
         
         if($sqlerro == false){

           $clpcforneconpad->excluir($pc63_contabanco);

           if($clpcforneconpad->erro_status==0){

             $erro_msg = $clpcforneconpad->erro_msg;
             $sqlerro=true;

           }

         }  

       }

     }

     db_fim_transacao($sqlerro);

  }

}else if(isset($excluir)){
  $result_movconta = $clempagemovconta->sql_record($clempagemovconta->sql_query_file(null,"*","","e98_contabanco = $pc63_contabanco limit 1 "));
  if($clempagemovconta->numrows > 0){
    $sqlerro = true;
    $erro_msg = "Conta incluída em pagamento de agenda. \\n\\nExclusão abortada";
  }
  if($sqlerro==false){

    db_inicio_transacao();
    $clpcforneconpad->excluir($pc63_contabanco);

    if($clpcforneconpad->erro_status==0){

      $erro_msg = $clpcforneconpad->erro_msg;
      $sqlerro=true;

    }
    
    if($sqlerro==false){

      $clpcfornecon->excluir($pc63_contabanco);
      $erro_msg = $clpcfornecon->erro_msg;

      if($clpcfornecon->erro_status==0){

        $sqlerro=true;

      }

    }

    db_fim_transacao($sqlerro);

  }

}else if(isset($opcao)){

   $result = $clpcfornecon->sql_record($clpcfornecon->sql_query($pc63_contabanco));

   if($result!=false && $clpcfornecon->numrows>0){

     db_fieldsmemory($result,0);
     $result = $clpcforneconpad->sql_record($clpcforneconpad->sql_query_file($pc63_contabanco));

     if($result!=false && $clpcforneconpad->numrows>0){

       $pc64_contabanco = 't';

     }else{

       $pc64_contabanco = 'f';

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
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<!--
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
-->
	<?
	include("forms/db_frmpcfornecon.php");
	?>
<!--
    </center>
	</td>
  </tr>
</table>
-->
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
  db_msgbox($erro_msg);
  if($sqlerro==true){
    echo "<script> document.form1.".$clpcfornecon->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clpcfornecon->erro_campo.".focus();</script>";
  }else if(isset($submita) && $sqlerro==false){
    echo "<script> parent.document.form1.submit();</script>";
  } else if(isset($reload) && !$sqlerro) {
     echo "<script>parent.ordem.location.href=parent.ordem.location;</script>";
     echo "<script>parent.db_iframe_pcfornecon.hide();</script>";
  }
}
?>