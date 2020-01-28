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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_veicmanut_classe.php");
include("classes/db_veicmanutitem_classe.php");
include("classes/db_veicmanutoficina_classe.php");
include("classes/db_veicmanutretirada_classe.php");
include("classes/db_veiculos_classe.php");
include("classes/db_veictipoabast_classe.php");
include("classes/db_veicretirada_classe.php"); 
db_app::import("veiculos.*");
db_postmemory($HTTP_POST_VARS);

$clveicmanut         = new cl_veicmanut;
$clveicmanutoficina  = new cl_veicmanutoficina;
$clveicmanutretirada = new cl_veicmanutretirada;
$clveiculos          = new cl_veiculos;
$clveictipoabast     = new cl_veictipoabast;
$clveicretirada      = new cl_veicretirada;

$db_opcao = 22;
$db_botao = false;
$sqlerro=false;

if (isset($alterar)) {

  /*
   -- Codigo Comentado pois foi efetuada toda validacao necessaria na interface (em Javascript)
  $result_retirada=$clveicretirada->sql_record($clveicretirada->sql_query_file(null,"ve60_medidasaida",null,"ve60_veiculo=$ve62_veiculos order by ve60_codigo desc limit 1 "));
  if ($clveicretirada->numrows>0) {
    $oRetirada=db_utils::fieldsMemory($result_retirada,0);
    if ($oRetirada->ve60_medidasaida>$ve62_medida) {
      $sqlerro=true;
      $erro_msg="Medida ($ve62_medida) menor que última medida de retirada ($oRetirada->ve60_medidasaida) ";
    }
  }  
  
  $result_proximamedida = $clveiculos->sql_record($clveiculos->sql_query_proximamedida(@$ve62_veiculos,@$ve62_dtmanut,'')); 
  if($clveiculos->numrows>0) {
   $oRetirada = db_utils::fieldsMemory($result_proximamedida,0);  
   if($oRetirada->proximamedida < $ve62_medida){
    $sqlerro  = true;
    $erro_msg = "Medida ($ve62_medida) maior que última medida ($oRetirada->proximamedida) ";   
   }
  }
  */
  if ($sqlerro==false){  
    db_inicio_transacao();
    $clveicmanut->alterar($ve62_codigo);
    if($clveicmanut->erro_status==0){
      $sqlerro=true;
    } 
    $erro_msg = $clveicmanut->erro_msg; 
    if ($sqlerro==false){
      $result_oficina=$clveicmanutoficina->sql_record($clveicmanutoficina->sql_query(null,"ve66_codigo",null,"ve66_veicmanut=$ve62_codigo"));   
      if (isset($ve66_veiccadoficinas)&&$ve66_veiccadoficinas!=""){
        if($clveicmanutoficina->numrows>0){ 			  			
          db_fieldsmemory($result_oficina,0);
          $clveicmanutoficina->ve66_codigo=$ve66_codigo;
          $clveicmanutoficina->alterar($ve66_codigo);
          if ($clveicmanutoficina->erro_status=="0"){
            $erro_msg=$clveicmanutoficina->erro_msg;
            $sqlerro=true;
          }
        }else{
          $clveicmanutoficina->ve66_veicmanut=$clveicmanut->ve62_codigo;
          $clveicmanutoficina->incluir(null);
          if ($clveicmanutoficina->erro_status=="0"){
            $erro_msg=$clveicmanutoficina->erro_msg;
            $sqlerro=true;
          }
        }
      }else{
        if($clveicmanutoficina->numrows>0){
          $clveicmanutoficina->excluir(null,"ve66_veicmanut=$ve62_codigo");
          if ($clveicmanutoficina->erro_status=="0"){
            $erro_msg=$clveicmanutoficina->erro_msg;
            $sqlerro=true;
          }
        }		  	
      }
    } 
    if ($sqlerro==false){
      $result_retirada=$clveicmanutretirada->sql_record($clveicmanutretirada->sql_query(null,"ve65_codigo",null,"ve65_veicmanut=$ve62_codigo"));
      if (isset($ve65_veicretirada)&&$ve65_veicretirada!=""){
        if($clveicmanutretirada->numrows>0){
          db_fieldsmemory($result_retirada,0);
          $clveicmanutretirada->ve65_codigo=$ve65_codigo;
          $clveicmanutretirada->alterar($ve65_codigo);
          if ($clveicmanutretirada->erro_status=="0"){
            $erro_msg=$clveicmanutretirada->erro_msg;
            $sqlerro=true;
          }
        }else{  	
          $clveicmanutretirada->ve65_veicmanut=$clveicmanut->ve62_codigo;
          $clveicmanutretirada->incluir(null);
          if ($clveicmanutretirada->erro_status=="0"){
            $erro_msg=$clveicmanutretirada->erro_msg;
            $sqlerro=true;
          }
        }  		
      }else{
        if($clveicmanutretirada->numrows>0){
          $clveicmanutretirada->excluir(null,"ve65_veicmanut=$ve62_codigo");
          if ($clveicmanutretirada->erro_status=="0"){
            $erro_msg=$clveicmanutretirada->erro_msg;
            $sqlerro=true;
          }
        }
      }
    }
    db_fim_transacao($sqlerro);
  }
  $db_opcao = 2;
  $db_botao = true;
}else if(isset($chavepesquisa)){
  $db_opcao = 2;
  $db_botao = true;
  $result = $clveicmanut->sql_record($clveicmanut->sql_query($chavepesquisa)); 
  db_fieldsmemory($result,0);
  $result_oficina=$clveicmanutoficina->sql_record($clveicmanutoficina->sql_query(null,"*",null,"ve66_veicmanut=$chavepesquisa"));
  if($clveicmanutoficina->numrows>0){
  	db_fieldsmemory($result_oficina,0);
  }
  $result_retirada=$clveicmanutretirada->sql_record($clveicmanutretirada->sql_query(null,"*",null,"ve65_veicmanut=$ve62_codigo"));
  if($clveicmanutretirada->numrows>0){
  	db_fieldsmemory($result_retirada,0);
  }   

  $result = $clveiculos->sql_record($clveiculos->sql_query($ve62_veiculos,"ve01_veictipoabast"));
  db_fieldsmemory($result,0);

  $result_veictipoabast = $clveictipoabast->sql_record($clveictipoabast->sql_query($ve01_veictipoabast,"ve07_sigla"));
  if ($clveictipoabast->numrows > 0){
    db_fieldsmemory($result_veictipoabast,0);
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
<body bgcolor=#CCCCCC style='margin-top: 25px;' leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
	<?
	include("forms/db_frmveicmanut.php");
	?>
</body>
</html>
<?
if(isset($alterar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clveicmanut->erro_campo!=""){
      echo "<script> document.form1.".$clveicmanut->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clveicmanut->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
  }
}
if(isset($chavepesquisa)){
 echo "
  <script>
      
      js_pesquisa_medida();
      
      function js_db_libera(){
         parent.document.formaba.veicmanutitem.disabled=false;
         top.corpo.iframe_veicmanutitem.location.href='vei1_veicmanutitem001.php?ve63_veicmanut=".@$ve62_codigo."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('veicmanutitem');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>