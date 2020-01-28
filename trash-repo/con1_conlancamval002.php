<?PHP
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
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

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clconplano     = new cl_conplano;
$clconlancamval = new cl_conlancamval;
$clconlancamcompl = new cl_conlancamcompl;
$clconlancamdig   = new cl_conlancamdig;
$clconlancam      = new cl_conlancam;
$clconlancamdoc   = new cl_conlancamdoc;

$anousu = db_getsession("DB_anousu");
$db_opcao = 22;
$db_botao = false;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
     $erro = false;
     $data = "$c70_data_ano-$c70_data_mes-$c70_data_dia";
     if (strlen($data) < 9 ){
         echo "<script> alert('Data inválida ! '); </script>";
     }  
     if ($c69_debito =="" || $c69_debito =="0"){
          echo "<script> alert('Conta Débito não informada ! '); </script>";
	  $db_opcao=2;  
	  $db_botao=true;
     } else if($c69_credito=="" || $c69_credito=="0"){   
          echo "<script> alert('Conta Credito não informada !  '); </script>";
     } else if ($c69_credito == $c69_debito){
          echo "<script> alert('Contas não podem ser iguais !  '); </script>";
	  $db_opcao=2; 
	  $db_botao=true;
     } else if ($c69_valor=="" || $c69_valor=="0"){ 
          echo "<script> alert('Valor não informado ! '); </script>";
	  $db_opcao=2;  	  $db_botao=true;
     } else if($c69_codhist=="" || $c69_codhist=="0"){
          echo "<script> alert('Histórico não informado !  '); </script>";
	  $db_opcao=2;  	  $db_botao=true;
     } else {

  

        db_inicio_transacao();
        if ($c78_chave !="") {  
	       // se nao existe incluir
	       $rr = $clconlancamdig->sql_record($clconlancamdig->sql_query_file($c70_codlan));
	       if ($clconlancamdig->numrows > 0) {
		    // db_msgbox("aki");
	        $clconlancamdig->c78_chave = $c78_chave ;
		      $clconlancamdig->c78_codlan = $c70_codlan;
	        $clconlancamdig->c78_data  = $data;
          $clconlancamdig->alterar($c70_codlan);
	        } else {
                    $clconlancamdig->c78_chave = $c78_chave ;
                    $clconlancamdig->c78_data = "$c70_data_ano-$c70_data_mes-$c70_data_dia";
                    $clconlancamdig->incluir($c70_codlan);
		    }  
        } else {
	       // lote vazio, manda excluir
           $clconlancamdig->c78_chave = $c78_chave ;
		       $clconlancamdig->c78_data = $data;
		       $clconlancamdig->c78_codlan= $c70_codlan;
           $clconlancamdig->excluir($c70_codlan);
        }		  
	    // --
	    if ($c72_complem !=""){
	       $r=$clconlancamcompl->sql_record($clconlancamcompl->sql_query_file($c70_codlan));
	       if ($clconlancamcompl->numrows > 0 ) {
                   $clconlancamcompl->c72_complem = $c72_complem;     
		   $clconlancamcompl->c72_codlan = $c70_codlan;
                   $clconlancamcompl->alterar($c70_codlan);
	       } else {  
                   $clconlancamcompl->c72_complem = $c72_complem;     
                   $clconlancamcompl->incluir($c70_codlan);
           }
	    }
      $db_opcao = 2;
      $clconlancam->c70_valor = $c69_valor;
	    $clconlancam->alterar($c70_codlan);
	    // exlcui do conlancamval e inclui novamente
	    // devido a trigers do conplanoexe
        $clconlancamval->excluir($c69_sequen);
        if($clconlancamval->erro_status=="0"){
          $erro = true;
          $erro_msg="$clconlancamval->erro_msg";
        }
	    // inclui

        if($erro == false) {
        if($c71_coddoc=="0"){
	      $clconlancamdoc->excluir($c70_codlan);
        }else{
          $resdoc = $clconlancamdoc->sql_record($clconlancamdoc->sql_query($c70_codlan));
          if($clconlancamdoc->numrows>0){
            $clconlancamdoc->c71_codlan = $c70_codlan;
            $clconlancamdoc->c71_coddoc = $c71_coddoc;
            $clconlancamdoc->c71_data   = "$c70_data_ano-$c70_data_mes-$c70_data_dia";
	        $clconlancamdoc->alterar($c70_codlan);
          }else{
            $clconlancamdoc->c71_codlan = $c70_codlan;
            $clconlancamdoc->c71_coddoc = $c71_coddoc;
            $clconlancamdoc->c71_data   = "$c70_data_ano-$c70_data_mes-$c70_data_dia";
	        $clconlancamdoc->incluir($c70_codlan);
          }
        }
        // ------------------------ verifica se o sistema de contas esta correto, por exemplo
  	    // ------- não pode haver lançemento entre contas Patrimonial X Financeiro
  	    /* VALIDAÇÃO EXISTENTE APENAS PARA CLIENTES QUE NÃO UTILIZAM PCASP */
        
        if (!USE_PCASP) {
          
          $sSqlDebito = $clconplano->sql_query(null,null,"c52_descrred as sistema_debito",null,"c61_anousu =$anousu and c61_reduz=$c69_debito");
          $r          = $clconplano->sql_record($sSqlDebito);
          if ($clconplano->numrows){
            db_fieldsmemory($r,0);
          }
          
          $sSqlCredito = $clconplano->sql_query(null,null,"c52_descrred as sistema_credito",null,"c61_anousu =$anousu and c61_reduz=$c69_credito");
          $r           = $clconplano->sql_record();
          if ($clconplano->numrows){
            db_fieldsmemory($r,0);
          }
          
          if (isset($sistema_debito ) && isset($sistema_credito)) {
            
            if (($sistema_debito == 'F') && ($sistema_credito == 'P') || ($sistema_debito == 'P') && ($sistema_credito == 'F')) {
              
               $erro_msg="Não é permitido lançamentos entre o sistema Financeiro e Patrimonial ! ";
               $erro = true;
            } 
          }
        }
          // ------------------------ * ------------------- * --------------------------
          if($erro==false){
            $clconlancamval->c69_anousu  = $c70_data_ano; 
            $clconlancamval->c69_codlan  = $c70_codlan; // codigo do lançamento 
            $clconlancamval->c69_codhist = $c69_codhist; // chave estrangeira  
            $clconlancamval->c69_debito  = $c69_debito; 
            $clconlancamval->c69_credito = $c69_credito;
            $clconlancamval->c69_valor   = $c69_valor ;
	        $clconlancamval->c69_data    = $clconlancam->c70_data;
	        $clconlancamval->c69_sequen  = "0";
            $clconlancamval->incluir($c69_sequen);
          }
          
          $oConlancamdoc             = db_utils::getDao("conlancamdoc");
          $oConlancamdoc->c71_codlan = $c70_codlan;
          $oConlancamdoc->c71_coddoc = $iDocumento;
          $oConlancamdoc->c71_data   = $clconlancam->c70_data;
          $oConlancamdoc->incluir($c70_codlan);
        }
        db_fim_transacao($erro);
   }
}else if(isset($chavepesquisa)){
      $db_opcao = 2;
      if (isset($sequen)){
	      $result = $clconlancamval->sql_record($clconlancamval->sql_query("","*","","c69_codlan=$chavepesquisa and c69_sequen=$sequen")); 
      }	else {
         $result = $clconlancamval->sql_record($clconlancamval->sql_query($chavepesquisa)); 
      }	 
      db_fieldsmemory($result,0);
      $db_botao = true;

	  $result = $clconlancamdoc->sql_record($clconlancamdoc->sql_query($c69_codlan,'*')); 
      if($clconlancamdoc->numrows!=0){
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
<?PHP
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?PHP
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
//  if($clconlancamval->erro_status=="0"){
//    $clconlancamval->erro(true,false);
 
 
 if ($erro==true){
     db_msgbox($erro_msg);
 
     $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clconlancamval->erro_campo!=""){
      echo "<script> document.form1.".$clconlancamval->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clconlancamval->erro_campo.".focus();</script>";
    };
  }else{
    $clconlancamval->erro(true,true);
  };
};
if($db_opcao==22 and $desabilitafunc==false){
 echo "<script>document.form1.pesquisar.click();</script>";
}
?>