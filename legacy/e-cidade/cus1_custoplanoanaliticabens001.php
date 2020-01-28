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
require("libs/db_utils.php");
include("classes/db_custoplanoanalitica_classe.php");
include("classes/db_custoplanotipoconta_classe.php");
include("classes/db_custoplanoanaliticabens_classe.php");
include("classes/db_custocriteriorateiobens_classe.php");
require("model/custoRegraRateio.model.php");
require("model/custorateio.model.php");
include("dbforms/db_funcoes.php");
include("classes/db_custoplanoanaliticacriteriorateio_classe.php");
include("classes/db_custocriteriorateio_classe.php");
db_postmemory($_POST);
db_postmemory($_GET);

$clcustoplanoanaliticabens = new cl_custoplanoanaliticabens;
$clcustoplanotipoconta     = new cl_custoplanotipoconta;
$clcustoplanoanalitica     = new cl_custoplanoanalitica;
$erro_msg                  = 0;
if(isset($opcao) && $opcao=="excluir"){
  $db_opcao = 3;
  $db_botao = true;
} else {
  $db_opcao = 1;
  $db_botao = true;
}

$lSqlErro = false; 
if(isset($incluir)){

  db_inicio_transacao();
  $lSqlErro = false;
  
  /**
   * Pesquisamos o bem, para descobrir se ele já está incluido na conta
   */
  $sSqlCustoPlanoBem = $clcustoplanoanaliticabens->sql_query(null,"*",
                                                             null,
                                                             "cc05_custoplanoanalitica = {$cc05_custoplanoanalitica}
                                                             and cc05_bens = {$cc05_bens}"
                                                             );
                                                              
  $rsCustoPlanoBem = $clcustoplanoanaliticabens->sql_record($sSqlCustoPlanoBem);
  if ($clcustoplanoanaliticabens->numrows > 0) {

    $lSqlErro = true;
    $erro_msg = "Erro [0] - Bem {$t52_descr} já cadastrado para essa conta.";
    
  }
  /*
   * Verificamos se o bem já está vinculado a outro centro de custo ativo.
   * caso esteje, devemos alertar o usuário e desativar o criterio para o outro bem. 
   */
  if (!$lSqlErro) {
    
    $sSqlCriterioBem = $clcustoplanoanaliticabens->sql_query_criteriobens(null,
                                                                          "bens.*, custoplano.*",
                                                                          null,
                                                                          "cc05_bens = {$cc05_bens}
                                                                           and cc06_ativo is true");
                                                                           
    $rsCriterioBem = $clcustoplanoanaliticabens->sql_record($sSqlCriterioBem);
    if ($clcustoplanoanaliticabens->numrows > 0){
  
       $oCriterioBem = db_utils::fieldsMemory($rsCriterioBem, 0);
       $lSqlErro     = true;
       $erro_msg     = "Erro [1] - Bem já cadastrado para o centro de custo {$oCriterioBem->cc01_estrutural}";
       
    }
  }
  //$lSqlErro = true;                                                                         
  if (!$lSqlErro) {

    $clcustoplanoanaliticabens->incluir(null);
    if ($clcustoplanoanaliticabens->erro_status == 0) {
      
      $lSqlErro = true;
      $erro_msg = "Erro [1] - ".$clcustoplanoanaliticabens->erro_msg;
      
    }
  }
  
  /*
   * Incluimos um criterio automatico para esse bem (vinculamos a conta do bem a regra de rateio)
   */
  if (!$lSqlErro) {
    
    $clcustoplanoanalitica              = new cl_custoplanoanalitica;
    $sSqlCustoPlano                     = $clcustoplanoanalitica->sql_query($cc05_custoplanoanalitica);
    $rsCustoPlano                       = $clcustoplanoanalitica->sql_record($sSqlCustoPlano);
    $oCustoPlano                        = db_utils::fieldsMemory($rsCustoPlano, 0);
    $oDaoCustoCriterio                  = new cl_custocriteriorateio;
    $oDaoCustoCriterioBens              = new cl_custocriteriorateiobens;
    $oDaoCustoCriterio->cc08_automatico = "true";
    $oDaoCustoCriterio->cc08_ativo      = "true";
    $oDaoCustoCriterio->cc08_coddepto   = db_getsession("DB_coddepto");
    $oDaoCustoCriterio->cc08_descricao  = "Bem {$t52_descr}";
    $oDaoCustoCriterio->cc08_obs        = "Bem {$t52_descr}";
    $oDaoCustoCriterio->cc08_instit     = db_getsession("DB_instit");
    $oDaoCustoCriterio->cc08_matunid    = 1;
    $oDaoCustoCriterio->incluir(null);
    if ($oDaoCustoCriterio->erro_status == 0) {
      
      $lSqlErro = true;
      $erro_msg = "Erro [1] - ".$oDaoCustoCriterio->erro_msg;
      
    }
    
    try {
      
      $oCriterioRateio = new custorateio($oDaoCustoCriterio->cc08_sequencial);
      $oRegraRateio    = new custoRegraRateio($oDaoCustoCriterio->cc08_sequencial);
      $oRegraRateio->setContaPlano($cc05_custoplanoanalitica);
      $oRegraRateio->setQuantidade(1);
      $oCriterioRateio->addRegraRateio($oRegraRateio);
      $oCriterioRateio->save(true);
      
    } catch (Exception $eErro) {
      
      $lSqlErro = true;
      $erro_msg = "Erro [".$eErro->getMessage()."] - ".$eErro->getMessage();
       
    }
  }
  /*
   * Incluimos a ligacao do criterio com o cadastro do bem 
   */
  if (!$lSqlErro) {

    $oDaoCustoCriterioBens->cc06_custocriteriorateio     = $oDaoCustoCriterio->cc08_sequencial;
    $oDaoCustoCriterioBens->cc06_custoplanoanaliticabens = $clcustoplanoanaliticabens->cc05_sequencial;
    $oDaoCustoCriterioBens->cc06_ativo                   = "true";
    $oDaoCustoCriterioBens->incluir(null);
    if ($oDaoCustoCriterioBens->erro_status == 0) {
  
      $lSqlErro = true;
      $erro_msg = "Erro [1] - ".$oDaoCustoCriterio->erro_msg;
      
    }
  }
  db_fim_transacao($lSqlErro);
  
} else if(isset($excluir)) {
	
  if ($lSqlErro==false) {
     
    $lSqlErro = false;
    db_inicio_transacao();
    /*
     * Excluimos o criterio cadastrado.
     */
    $oDaoCustoCriterioBens = new cl_custocriteriorateiobens;
    $oDaoCustoCriterio     = new cl_custocriteriorateio;
    $sSqlCriterio          = $oDaoCustoCriterioBens->sql_query(null,"*",null,
                                                      "cc06_custoplanoanaliticabens = {$cc05_sequencial}
                                                      and cc06_ativo is true");
                                                      
    $rsCriterio   = $oDaoCustoCriterioBens->sql_record($sSqlCriterio);
    echo $oDaoCustoCriterioBens->numrows;
    if ($oDaoCustoCriterioBens->numrows > 0) {

      $oCriterioBem  = db_utils::fieldsMemory($rsCriterio, 0);
      $oDaoCustoCriterioBens->excluir(null,"cc06_custoplanoanaliticabens = {$oCriterioBem->cc05_sequencial}");
      if ($oDaoCustoCriterioBens->erro_status == 0) {
        
        $lSqlErro = true;
        $erro_msg = "Erro [2] - ".$oDaoCustoCriterioBens->erro_msg;
        
      }
      if (!$lSqlErro) {
        
        $oCriterioRateio = new custorateio($oCriterioBem->cc08_sequencial);
        $aRegras  = $oCriterioRateio->getRegrasRateio();
        /**
      	 * Excluimos as regras de rateio
       	 */
        try {
        
          foreach ($aRegras as $oRegra) {
            $oCriterioRateio->excluirRegraRateio($oRegra->getCodigoRegra());
          }
        } catch (Exception $eErro) {
          
          $lSqlErro = true;
          $erro_msg = "Erro [".$eErro->getMessage()."] - ".$eErro->getMessage();
        }
      }
      if (!$lSqlErro) {
        
        $oDaoCustoCriterio->excluir($oCriterioBem->cc08_sequencial);
        if ($oDaoCustoCriterio->erro_status == 0) {
          
          $lSqlErro = true;
          $erro_msg = "Erro [3] - ".$oDaoCustoCriterio->erro_msg;
          
        }
        
      }
      //$lSqlErro = true;
      if (!$lSqlErro) {

        $clcustoplanoanaliticabens->excluir($cc05_sequencial);
        $erro_msg = $clcustoplanoanaliticabens->erro_msg;
        if($clcustoplanoanaliticabens->erro_status==0){
          $lSqlErro = true;
        }
      }
    }
    db_fim_transacao($lSqlErro);
  }
  
}else if(isset($opcao)){
	
   $result = $clcustoplanoanaliticabens->sql_record($clcustoplanoanaliticabens->sql_query($cc05_sequencial));
   if($result!=false && $clcustoplanoanaliticabens->numrows>0){
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
   <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" 
   onLoad="a=1">
    <center>
    <table style="padding-top:15px">
    	<tr>
    	  <td>
	      <?
	        include("forms/db_frmcustoplanoanaliticabens.php");
	      ?>
	      </td>
	    </tr>
	  </table>
    </center>
</body>
</html>
<script>
js_tabulacaoforms("form1","cc05_bens",true,1,"cc05_bens",true);
</script>
<?
if(isset($incluir)){
 
  if ($lSqlErro) {
    db_msgbox($erro_msg);
  }
  if($clcustoplanoanaliticabens->erro_status=="0"){
    //$clcustoplanoanaliticabens->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false; </script>  ";
    if($clcustoplanoanaliticabens->erro_campo!=""){
      echo "<script> document.form1.".$clcustoplanoanaliticabens->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcustoplanoanaliticabens->erro_campo.".focus();</script>";
    }
  }
}

if (isset($excluir) or isset($incluir)) {
  echo " <script>                                \n";
  echo "   document.form1.cc05_bens.value = '';  \n";
  echo "   document.form1.t52_descr.value = '';  \n";
  echo " </script>                               \n"; 	
}

?>