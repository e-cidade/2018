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


include("fpdf151/pdf.php");
include("libs/db_libpessoal.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhfopag_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_POST_VARS,2);
$clrhfopag = new cl_rhfopag;

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
<br><br><br>
<center>
<? 
db_criatermometro('calculo_folha','Concluido...','blue',1,'Efetuando Importação');
?>

</center>
</body>
<?
$db_erro = false;

$erro_msg = ImportaArquivo($AArquivo,$exclui);

//exit;

if(!empty($erro_msg)){
  echo "
  <script>
    parent.js_erro('$erro_msg');
  </script>
  ";
}
//echo "<BR> antes do fim db_fim_transacao()";
//flush();
db_fim_transacao();
//flush();
db_redireciona("pes4_rhfopag91001.php");

function ImportaArquivo($AArquivo,$exclui){
  if($exclui == 1){
    $clrhfopag->excluir(null,null,"1=1");
  }
  $LinhasArquivo = file($AArquivo);


	$nRegistro = 0;
  $slqerro = false;
  $erro_msg = "";

  for($cL=0;$cL<count($LinhasArquivo);$cL++) {

    $nRegistro++;

    $cLinha = $LinhasArquivo[$cL];     
    $rh66_regist = db_val(trim(db_substr($cLinha, 08,15)));
    $rh66_pis    = db_substr($cLinha, 23,11);
    $rh66_valor  = str_replace(",",".",db_substr($cLinha,107,11))*1;
    $rh66_proces = db_substr($cLinha,151,01);
    $rh66_instit = db_getsession("DB_instit");

    db_inicio_transacao();
    $clrhfopag->incluir($rh66_regist,db_getsession("DB_instit"));
    if($clrhfopag->erro_status=="0"){
       $sqlerro = true;
       $erro_msg = $clrhfopag->erro_msg;
    }
    db_fim_transacao($sqlerro);
    if($sqlerro == true){
      break;
    }
	}

}


?>