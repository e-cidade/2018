<?PHP
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_conlancamval_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_conlancam_classe.php");
require_once("classes/db_conlancamcompl_classe.php");
require_once("classes/db_conlancamdig_classe.php");
require_once("classes/db_conlancamdoc_classe.php");
require_once("classes/db_conplano_classe.php");
require_once("libs/db_utils.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clconplano       = new cl_conplano;
$clconlancamval   = new cl_conlancamval;
$clconlancamcompl = new cl_conlancamcompl;
$clconlancamdig   = new cl_conlancamdig;
$clconlancamdoc   = new cl_conlancamdoc;
$clconlancam      = new cl_conlancam;

$db_botao = false;
$db_opcao = 33;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){

$sql1="select c71_codlan from conlancamdoc where c71_codlan=$c70_codlan";
$result1=db_query($sql1);
$linhas1=pg_numrows($result1);

if ($linhas1 > 0){

     $sql2="select * from conlancamdoc inner join conhistdoc on c71_coddoc=c53_coddoc where c71_codlan=$c70_codlan";
     $result2=db_query($sql2);
     $linhas2=pg_numrows($result2);
     if ($linhas2>0){
       
       $oResultado  = db_utils::fieldsMemory($result2, 0);
       if ($oResultado->c53_coddoc == 1000 or $oResultado->c53_coddoc == 2000 or $oResultado->c53_tipo == 3000) {
               $alt=true;
               }
           else{
                $alt=false;
                db_msgbox('Não é permitido excluir lançamentos contábeis automáticos (conhistdoc <>1000 e <>2000)');
               }
     }
     else{
          $alt=false;
          db_msgbox('Não é permitido excluir lançamentos contábeis automáticos. (conhistdoc 1000 e 2000)');
         }
}
else{
      $alt=true;
}



if ($alt==true){

       db_inicio_transacao();
        $db_opcao = 3;
      	$erro = false;
        $msg_erro = '';
        $resdoc = $clconlancamdoc->sql_record($clconlancamdoc->sql_query($c70_codlan));
        if($clconlancamdoc->numrows>0){
          
          $clconlancamdoc = db_utils::fieldsMemory($resdoc,0);
          
          if ($clconlancamdoc->c53_tipo=1000 || $clconlancamdoc->c53_tipo=2000) {
            
            $clconlancamdoc   = new cl_conlancamdoc;
            $clconlancamdoc->excluir($c70_codlan);
          } else {
          	$erro = true;
          	$msg_erro = 'Lançamento com documento. Exclusão não permitida';
          }
        }
        
        if($erro==false){
          $clconlancamcompl->excluir($c70_codlan);
          if($clconlancamcompl->erro_status == '0'){
            $erro = true;
            $msg_erro = $clconlancamcompl->msg_erro;
          }
        }
        if($erro==false){
          $clconlancamdig->excluir($c70_codlan);
          if($clconlancamdig->erro_status == '0'){
            $erro = true;
            $msg_erro = $clconlancamdig->msg_erro;
          }
        }
        if($erro==false){
          $clconlancamval->excluir($c69_sequen);
          if($clconlancamval->erro_status == '0'){
            $erro = true;
            $msg_erro = $clconlancamval->msg_erro;
          }
        }
        if ($erro == false) {

          $oDaoConlancamInstit = new cl_conlancaminstit();
          $oDaoConlancamInstit->excluir(null, "c02_codlan = {$c70_codlan}");
          if ($oDaoConlancamInstit->erro_status == '0') {

            $erro = true;
            $msg_erro = $oDaoConlancamInstit->msg_erro;
          }
        }

        if ($erro == false) {

          $oDaoConlancamOrdem = new cl_conlancamordem();
          $oDaoConlancamOrdem->excluir(null, "c03_codlan = {$c70_codlan}");
          if ($oDaoConlancamOrdem->erro_status == '0') {

            $erro = true;
            $msg_erro = $oDaoConlancamOrdem->msg_erro;
          }
        }

        if($erro==false){
          $clconlancam->excluir($c70_codlan);
          if($clconlancam->erro_status == '0'){
            $erro = true;
            $msg_erro = $clconlancam->msg_erro;
          }
        }
        db_fim_transacao($erro);
}
}else if(isset($chavepesquisa)){
       $db_opcao = 3;
       $result = $clconlancamval->sql_record($clconlancamval->sql_query($chavepesquisa)); 
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
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<center>
    	<?PHP
    	if (USE_PCASP) {
    	  require_once("forms/db_frmconlancamval.php");
    	  } else {
require_once("forms/db_frmconlancamval_old.php");
}
    	?>
</center>
<?PHP db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
</body>
</html>
<?PHP
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
    if($erro==true){
        db_msgbox($msg_erro);
    }else{
       $clconlancam->erro(true,true);
    }
}
if($db_opcao==33){
   echo "<script>document.form1.pesquisar.click();</script>";
}
?>