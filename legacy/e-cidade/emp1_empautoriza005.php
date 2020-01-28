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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_empautpresta_classe.php");
require_once("classes/db_empprestatip_classe.php");
require_once("classes/db_empautoriza_classe.php");
require_once("classes/db_empautitem_classe.php");
require_once("classes/db_orcreserva_classe.php");
require_once("classes/db_orcreservaaut_classe.php");
require_once("classes/db_empauthist_classe.php");
require_once("classes/db_emphist_classe.php");
require_once("classes/db_emptipo_classe.php");
require_once("classes/db_cflicita_classe.php");
require_once("classes/db_db_depusu_classe.php");
require_once("classes/db_pctipocompra_classe.php");
require_once("classes/db_pcprocitem_classe.php");
require_once("classes/db_pcparam_classe.php");
require_once("classes/db_empparametro_classe.php");
require_once("classes/db_concarpeculiar_classe.php");
require_once("model/CgmFactory.model.php");
require_once("model/fornecedor.model.php");
require_once("classes/db_empautorizaprocesso_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clempautpresta                   = new cl_empautpresta;
$clempprestatip                   = new cl_empprestatip;
$clpctipocompra                   = new cl_pctipocompra;
$clempautoriza                    = new cl_empautoriza;
$clempautitem                     = new cl_empautitem;
$clempauthist                     = new cl_empauthist;
$clorcreserva                     = new cl_orcreserva;
$clorcreservaaut                  = new cl_orcreservaaut;
$clemphist                        = new cl_emphist;
$clemptipo                        = new cl_emptipo;
$clcflicita                       = new cl_cflicita;
$cldb_depusu                      = new cl_db_depusu;
$clpcprocitem                     = new cl_pcprocitem;
$clempparametro                   = new cl_empparametro;
$clpcparam                        = new cl_pcparam;
$clconcarpeculiar                 = new cl_concarpeculiar;
$oDaoEmpenhoProcessoAdminitrativo = new cl_empautorizaprocesso;

$sUrlEmpenho      = "emp1_empempenho001.php";
$rsemparam = $clempparametro->sql_record($clempparametro->sql_query(db_getsession("DB_anousu")));

if ($clempparametro->numrows > 0) {
  db_fieldsmemory($rsemparam, 0);
  if ($e30_notaliquidacao != '') {
    $sUrlEmpenho      = "emp4_empempenho001.php";
  } 
}  

$anulacao=false;//padrao
$sqlerro =false;

if ( isset($alterar) ) {
	
  try {
    $oFornecedor = new fornecedor($e54_numcgm);
    $oFornecedor->verificaBloqueioAutorizacaoEmpenho(null);
    $iStatusBloqueio = $oFornecedor->getStatusBloqueio();      
  } catch (Exception $eException) {
    $sqlerro  = true;
    $erro_msg = $eException->getMessage();
  }
  
  if ( !$sqlerro ) {
  	
	  if($iStatusBloqueio == 2){
	    db_msgbox("\\nusuário:\\n\\n Fornecedor com débito na prefeitura !\\n\\n\\n\\n");
	  }	  
  }
}


if(isset($tipocompra) || isset($chavepesquisa) ){
  $db_opcao = 2;
  $db_botao = true;
}else{//se for anulado tambem entra aqui
  $db_opcao = 22;
  $db_botao = false;
}  
if(isset($alterar) && !$sqlerro ){
  
  if($sqlerro == false){  
	  $res_pcparam = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"),"pc30_fornecdeb"));
	  if ($clpcparam->numrows > 0){
	       db_fieldsmemory($res_pcparam,0);
	       
	       $sql_fornec     = "select * from fc_tipocertidao($e54_numcgm,'c','".date("Y-m-d",db_getsession("DB_datausu"))."','') as retorno"; 
	       $res_fornec     = @pg_query($sql_fornec);
	       $numrows_fornec = @pg_numrows($res_fornec);
	
	       if ($numrows_fornec > 0){
	            db_fieldsmemory($res_fornec,0);
	       }
	
	       if (isset($retorno) && $retorno == "positiva"){

	       	    //if ($pc30_fornecdeb == 3) {
	       	    if ($pc30_fornecdeb == 3 && $iStatusBloqueio != 1) {
	                 $clempautoriza->erro_campo = "e54_numcgm";
	                 $erro_msg = "Fornecedor com debito!";
	                 $sqlerro  = true;
	            }
	       }
	  }
  }
  db_inicio_transacao();

  $db_opcao = 2;
  $db_botao = true;

  if ($sqlerro == false){
       $clempautoriza->e54_autori = $e54_autori;
       $clempautoriza->alterar($e54_autori);
       if($clempautoriza->erro_status=='0'){
           $erro_msg = $clempautoriza->erro_msg;
           $sqlerro=true;
       }
  }

  if($sqlerro==false){ // begin if
    $clempauthist->sql_record($clempauthist->sql_query_file($e54_autori));
    //rotina para EXCLUIR EMPAUTHIST
    if($clempauthist->numrows>0){
       $clempauthist->e57_autori=$e54_autori;
       $clempauthist->excluir($e54_autori);
       if($clempauthist->erro_status=='0'){
          $sqlerro=true;
       }
    } 
    //fim
    
    //rotina pra incluir no empauthist
    if($sqlerro==false && $e57_codhist!="0" && $e57_codhist!=""){
      $clempauthist->e57_autori=$e54_autori;
      $clempauthist->e57_codhist=$e57_codhist;
      $clempauthist->incluir($e54_autori);
      if($clempauthist->erro_status=='0'){
     	$sqlerro=true;
      }
    }
    //fim
    if($sqlerro==false  && isset($e44_tipo) && $e44_tipo!="") {
    	    $result = $clempautpresta->sql_record($clempautpresta->sql_query_file(null,"e58_autori","e58_autori","e58_autori=$e54_autori"));
  	    if($clempautpresta->numrows==0) {
		  $res_prestatip = $clempprestatip->sql_record($clempprestatip->sql_query_file($e44_tipo,"e44_obriga"));
		  if ($clempprestatip->numrows >0){
      		         db_fieldsmemory($res_prestatip,0);
		         if ( isset($e44_obriga) && $e44_obriga!=0) {
	  	            $clempautpresta->e58_autori = $e54_autori;
 	                    $clempautpresta->e58_tipo   = $e44_tipo;
  	                    $clempautpresta->incluir();
	                    if($clempautpresta->erro_status=='0'){
	                        $sqlerro=true;
	                    }
	                 }       
		  } 
	    } else {
 	        $clempautpresta->e58_tipo = $e44_tipo;
  	        $clempautpresta->alterar($e54_autori);
	        if($clempautpresta->erro_status=='0'){
	            $sqlerro=true;
	        }
	    } 
    }
  }
  
  /**
   * Alteração do Processo administrativo 
   */
  if (!$sqlerro) {
    
    $e150_sequencial              = null;
    $sWhereProcessoAdministrativo = " e150_empautoriza = {$e54_autori}";
    $sSqlProcessoAdministrativo   = $oDaoEmpenhoProcessoAdminitrativo->sql_query_file(null,
                                                                                      "e150_sequencial",
                                                                                      null,
                                                                                      $sWhereProcessoAdministrativo);
    $rsProcessoAdministrativo     = $oDaoEmpenhoProcessoAdminitrativo->sql_record($sSqlProcessoAdministrativo);
    if ($oDaoEmpenhoProcessoAdminitrativo->numrows > 0) {
      $e150_sequencial = db_utils::fieldsMemory($rsProcessoAdministrativo, 0)->e150_sequencial;
    }
    if (!empty($e150_sequencial)) {
    
      $oDaoEmpenhoProcessoAdminitrativo->e150_numeroprocesso = $e150_numeroprocesso;
      $oDaoEmpenhoProcessoAdminitrativo->e150_empautoriza    = $e54_autori;
      $oDaoEmpenhoProcessoAdminitrativo->e150_sequencial     = $e150_sequencial;
      $oDaoEmpenhoProcessoAdminitrativo->alterar($e150_sequencial);
    
      if ($oDaoEmpenhoProcessoAdminitrativo->erro_status == 0) {
    
        $sqlerro  = true;
        $erro_msg = $oDaoEmpenhoProcessoAdminitrativo->erro_msg;
      }
    }
  }
  
  db_fim_transacao($sqlerro);
} else if(isset($chavepesquisa)) {
  
  $result = $clempautoriza->sql_record($clempautoriza->sql_query($chavepesquisa)); 
  db_fieldsmemory($result,0);
  if($e54_login != db_getsession("DB_id_usuario")) {
    
    $result = $cldb_depusu->sql_record($cldb_depusu->sql_query_file(db_getsession("DB_id_usuario"),$e54_depto,'coddepto as cod02'));

    if ($cldb_depusu->numrows == 0) {
      $erro_msg="Usuário sem permissão de alterar!";
    }
  }
  $result = $clempautpresta->sql_record($clempautpresta->sql_query_file(null,"*","e58_autori","e58_autori=$e54_autori"));
  if ($clempautpresta->numrows>0) {
    
  	db_fieldsmemory($result,0);
  	$e44_tipo = $e58_tipo;
  }
  if (empty($erro_msg)) {
    
    if ($e54_anulad != "") {
      
	    $anulacao=true;
	    $db_opcao = 22;
	    $db_botao = false;
	  } else {
	    
	    $anulacao=false;
	    $db_opcao = 2;
	    $db_botao = true;
	  }
	   
    $result=$clempauthist->sql_record($clempauthist->sql_query_file($e54_autori));
    if($clempauthist->numrows>0){
       db_fieldsmemory($result,0);
    }
    
    /**
     * Busca os Dados do Processo administrativo
     */
    $sWhereProcessoAdministrativo = " e150_empautoriza = {$e54_autori}";
    $sSqlProcessoAdministrativo   = $oDaoEmpenhoProcessoAdminitrativo->sql_query_file(null,
                                                                                		  "e150_numeroprocesso",
                                                                                      null,
                                                                                      $sWhereProcessoAdministrativo);
    $rsProcessoAdministrativo     = $oDaoEmpenhoProcessoAdminitrativo->sql_record($sSqlProcessoAdministrativo);
    
    if ($oDaoEmpenhoProcessoAdminitrativo->numrows > 0) {
      $e150_numeroprocesso = db_utils::fieldsMemory($rsProcessoAdministrativo, 0)->e150_numeroprocesso;
    }
  } 
    
}

if(isset($e54_autori)){
  $emprocesso = false;
  $result_autoriza_de_pc = $clpcprocitem->sql_record($clpcprocitem->sql_query_itememautoriza(null,"e55_sequen",""," e55_autori=$e54_autori and e54_anulad is null "));
  if ($clpcprocitem->numrows > 0) {
    
    $db_botao = true;
    $emprocesso = true;
  }
  /**
   * Verifica se autorizacao é de contrato
   */
  $oDaoAutorizaContrato = db_utils::getDao("acordoitemexecutadoempautitem");
  $sSqlAutoriza         = $oDaoAutorizaContrato->sql_query(null,"ac20_acordoposicao", 
                                                           null, "e54_autori={$e54_autori}"
                                                          );
  $rsDadosContrato      = $oDaoAutorizaContrato->sql_record($sSqlAutoriza);
  if ($oDaoAutorizaContrato->numrows > 0) {
    $emprocesso = true;
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js, strings.js, widgets/dbtextField.widget.js,
               dbViewNotificaFornecedor.js, dbmessageBoard.widget.js, dbautocomplete.widget.js,
               dbcomboBox.widget.js,datagrid.widget.js,widgets/dbtextFieldData.widget.js");
  db_app::load("estilos.css, grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
	<div style="margin-top: 25px; width: 600px;">
	<?
	include("forms/db_frmempautoriza.php");
	?>
 </div>
</center>
</body>
</html>
<?
if(isset($erro_msg)){
  db_msgbox($erro_msg);
  //db_redireciona("emp1_empautoriza005.php");
}
//////////////////////////////////////////////////

if(isset($chavepesquisa)){
  if($anulacao==false && $emprocesso==false){
    echo "
           <script>
	       function js_libera(recar){
		  parent.document.formaba.empautitem.disabled=false;\n
		  parent.document.formaba.empautidot.disabled=false;\n
		  parent.document.formaba.prazos.disabled=false;\n
		  parent.document.formaba.anulacao.disabled=false;\n
                  // parent.document.formaba.empautret.disabled=false;\n
                  // top.corpo.iframe_empautret.location.href='emp1_empautret001.php?e66_autori=$e54_autori&inclusao=true';\n
		  top.corpo.iframe_empautitem.location.href='emp1_empautitem001.php?e55_autori=$e54_autori';\n
		  top.corpo.iframe_prazos.location.href='emp1_empautoriza007.php?chavepesquisa=$e54_autori';\n
		  top.corpo.iframe_anulacao.location.href='emp1_empautoriza006.php?e54_autori=$e54_autori';\n
		  top.corpo.iframe_empautidot.location.href='emp1_empautidot001.php?e56_autori=$e54_autori';\n
	       }   
	       js_libera();
           </script>
         ";  
  }else{ 
    if($anulacao == true){
      echo "
            <script>
              function js_bloqueia(recar){
                parent.document.formaba.empautitem.disabled=false;\n
                parent.document.formaba.empautidot.disabled=false;\n
                parent.document.formaba.prazos.disabled=false;\n
                parent.document.formaba.anulacao.disabled=false;\n
                // parent.document.formaba.empautret.disabled=false;\n
                // top.corpo.iframe_empautret.location.href='emp1_empautret001.php?e66_autori=$e54_autori&inclusao=true';\n
                top.corpo.iframe_empautitem.location.href='emp1_empautitem001.php?db_opcaoal=33&e55_autori=$e54_autori';\n
                top.corpo.iframe_prazos.location.href='emp1_empautoriza007.php?db_opcao=33&chavepesquisa=$e54_autori';\n
                top.corpo.iframe_anulacao.location.href='emp1_empautoriza006.php?e54_autori=$e54_autori';\n
                top.corpo.iframe_empautidot.location.href='emp1_empautidot001.php?anulacao=true&db_opcao=33&e56_autori=$e54_autori';\n
              }
              js_bloqueia();
            </script>
           ";
    }else{
      echo "
            <script>
              function js_bloqueia(recar){
                parent.document.formaba.empautitem.disabled=false;\n
                parent.document.formaba.empautidot.disabled=false;\n
                parent.document.formaba.prazos.disabled=true;\n
                parent.document.formaba.anulacao.disabled=true;\n
                // parent.document.formaba.empautret.disabled=false;\n
                // top.corpo.iframe_empautret.location.href='emp1_empautret001.php?e66_autori=$e54_autori&inclusao=true';\n
                top.corpo.iframe_empautitem.location.href='emp1_empautitem001.php?db_opcaoal=33&e55_autori=$e54_autori';\n
                top.corpo.iframe_prazos.location.href='emp1_empautoriza007.php?db_opcao=33&chavepesquisa=$e54_autori';\n
                top.corpo.iframe_anulacao.location.href='emp1_empautoriza006.php?db_opcao=33&e54_autori=$e54_autori';\n
                top.corpo.iframe_empautidot.location.href='emp1_empautidot001.php?anulacao=true&db_opcao=33&e56_autori=$e54_autori';\n
              }
              js_bloqueia();
            </script>
           ";
    }
  }    
}  

/////////////////////////////////////////////
if(isset($alterar)){
  if($sqlerro == true){
//    $clempautoriza->erro(true,false);
    $db_botao=true;
    if($clempautoriza->erro_campo!=""){
      echo "<script> document.form1.".$clempautoriza->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clempautoriza->erro_campo.".focus();</script>";
    }
  }else{
    $clempautoriza->erro(true,false);
  }
}
if($db_opcao==22 && $anulacao==false){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>