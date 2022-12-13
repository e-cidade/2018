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
include("libs/db_utils.php");

include("classes/db_custoplano_classe.php");
include("classes/db_custoplanoanalitica_classe.php");
include("classes/db_custoplanoanaliticabens_classe.php");
include("classes/db_custotipoconta_classe.php");
include("classes/db_custoplanotipoconta_classe.php");
include("classes/db_parcustos_classe.php");
include("classes/db_db_depart_classe.php");

include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clcustoplanotipoconta     = new cl_custoplanotipoconta;
$clcustoplano 		   	   = new cl_custoplano;
$clcustotipoconta      	   = new cl_custotipoconta;
$clcustoplanoanalitica 	   = new cl_custoplanoanalitica;
$clcustoplanoanaliticabens = new cl_custoplanoanaliticabens;
$clparcustos           	   = new cl_parcustos;
$cldb_estrut           	   = new cl_db_estrut;
$cldb_depart               = new cl_db_depart;

$db_botao = false;
$db_opcao = 33;
$lSqlErro = false;

if(isset($excluir)){
  
  $db_opcao = 3;
  
  // --> se o usu�rio est� excluindo uma conta estrutural com n�vel pai pendente acusa erro
   
  // se retornar valores maior do que 1 indica que o n�vel do estrutural n�o � do estrutural pai ent�o a exclus�o 
  // � permitida. Caso retonar 1 no count o estrutural � do pai e � verificado se existem pend�ncias abaixo deste 
  // n�vel
  $rsCustoPlano = $clcustoplano->sql_record($clcustoplano->sql_query_file(null,"fc_estrutural_nivel(cc01_estrutural)"));
  $sRetornoNivel = pg_result($rsCustoPlano,0,0);
  
  //se o n�vel do estrutural for o primeiro significa que � do estrutural pai, ent�o � preciso verificar se o estrutural
  //pai possui pend�ncias antes de executar a exclus�o
  
  if($sRetornoNivel == 1){
 	
   //obt�m se o pai do estrutural n�o tem pend�ncias
   $rsPendenciasPai = $clcustoplano->sql_record($clcustoplano->sql_query_file(null,"count(cc01_estrutural)",null,"fc_estrutural_pai(cc01_estrutural) = '$cc01_estrutural' "));
   $sRetornoPendencias = pg_result($rsPendenciasPai,0,0);
    
    if($sRetornoPendencias > 0) {   
	  db_msgbox("Opera��o abortada! N�o � permitido excluir uma conta sint�tica com pend�ncias no seu estrutural!");
      db_redireciona("cus1_custoplano003.php");  
	  exit();
	}
  }
  
  /*
   * retorna os registros das tabelas custoplanoanalitica, custoplanotipoconta para exclus�o e 
   * verifica se existem bens pendentes na tabela custoplanoanaliticabens
   */
  $rsAnalitica = $clcustoplanoanalitica->sql_record($clcustoplanoanalitica->sql_query_left(null,"*",null," cc04_custoplano = {$cc01_sequencial}"));

  if($clcustoplanoanalitica-> numrows > 0) {
 
   @$oRetorno = db_utils::fieldsMemory($rsAnalitica,0);
 
   //verifica se existem registros na tabela custoplanoanaliticabens se n�o existem exclui registros das tabelas:
   //custoplanotipoconta, custoplanoanalitica, custoplano 
   if($oRetorno->cc05_sequencial == null){

    db_inicio_transacao();
  
      //n�o pode ter ordem de exclus�o alterada devido as restri��es (FK) de relacionamento do banco de dados  
		
      $clcustoplanotipoconta->excluir("","cc03_custoplanoanalitica = $oRetorno->cc04_sequencial");   
   	  if($clcustoplanotipoconta->erro_status == 0) {
	    $lSqlErro = true;	 	
	    $sMsgErro = $clcustoplanotipoconta->erro_msg;
      }
   
      $clcustoplanoanalitica->excluir($oRetorno->cc04_sequencial);
	  if($clcustoplanoanalitica->erro_status == 0) {
	    $lSqlErro = true;	 	
	    $sMsgErro = $clcustoplanoanalitica->erro_msg;
      }
	
      $clcustoplano->excluir($cc01_sequencial); 
      if($clcustoplano->erro_status == 0) {
	    $lSqlErro = true;	 	
	    $sMsgErro = $clcustoplano->erro_msg;
      }
    
    db_fim_transacao($lSqlErro);
  
  } 
	
  //se existem bens na tabela custoplanoanaliticabens a exclus�o � cancelada
  else if($oRetorno->cc05_sequencial != null){
  
    db_msgbox("Inclus�o cancelada n�o � permitido excluir uma conta com bens vinculados!");
    db_redireciona("cus1_custoplano003.php");  
 
  }

  //se a conta � anal�tica faz a exclus�o somente na tabela custoplano
} else{

  db_inicio_transacao();

  $clcustoplano->excluir($cc01_sequencial);		

    if($clcustoplano->erro_status == 0) {
      $lSqlErro = true;	 	
      $sMsgErro = $clcustoplano->erro_msg;
    }

  db_fim_transacao($lSqlErro);
		
}
 
} else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $db_botao = true;
   $result   = $clcustoplano->sql_record($clcustoplano->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
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
<table align="center"  style="padding-top:15px;">
  <tr> 
    <td> 
    <center>
	<?
	include("forms/db_frmcustoplano.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($excluir)){
  if($clcustoplano->erro_status=="0"){
    $clcustoplano->erro(true,false);
  }else{
    $clcustoplano->erro(true,true);
  }
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>