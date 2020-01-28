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
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_rhsuspensaopag_classe.php");
require_once("classes/db_rhpessoal_classe.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

$oGet  = db_utils::postMemory($_GET);


$clrhpessoal          = new cl_rhpessoal();
$clrhsuspensaopag     = new cl_rhsuspensaopag();
$clrhsuspensaopag->rotulo->label();
$rsRhPessoal          = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm((int)$iMatricula,"rh01_regist, z01_nome"));
$clrhpessoalCampos    = db_utils::fieldsMemory($rsRhPessoal,0);
$clrotulo             = new rotulocampo;

$clrotulo->label("rh01_numcgm");
$clrotulo->label("nome");

$rh101_regist         = $clrhpessoalCampos->rh01_regist;
$z01_nome             = $clrhpessoalCampos->z01_nome;
$sSqlSuspensoesAtivas = $clrhsuspensaopag->sql_query("","     rh101_sequencial,
                                      rh101_regist,
                                      z01_nome,
                                      rh101_dtcadastro,
                                      rh101_usuario,
                                      login,
                                      rh101_dtinicial,
                                      rh101_dtfinal,
                                      rh101_dtdesativacao,
                                      rh101_obs
                                      ",
                                  "","rh101_regist = {$oGet->iMatricula} and rh101_dtdesativacao IS NULL");
$rsSuspensoesAtivass       = $clrhsuspensaopag->sql_record($sSqlSuspensoesAtivas);
$oSuspensoesAtivas         = db_utils::getColectionByRecord($rsSuspensoesAtivass);
$iTotalSuspensoesAtivas    = count($oSuspensoesAtivas);


$rh101_dtinicial           = array("","","");
$rh101_dtfinal             = array("","","");

if($iTotalSuspensoesAtivas > 0 ){
	$oSuspensoesAtivas         = $oSuspensoesAtivas[0];
	
	$rh101_sequencial          = $oSuspensoesAtivas->rh101_sequencial;
	$rh101_dtinicial           = split("-", $oSuspensoesAtivas->rh101_dtinicial);
	$rh101_dtfinal             = split("-", $oSuspensoesAtivas->rh101_dtfinal);

} else{
	
  
}
$lblCadastrar              = $iTotalSuspensoesAtivas > 0 ? "Desativar" : "Cadastrar";
$db_opcao                  = $iTotalSuspensoesAtivas > 0 ? 22 : 2;
$jsCadastrar               = $iTotalSuspensoesAtivas > 0 ? "js_validaFormulario1();" : "js_validaFormulario2();";

if(isset($oGet->db_opcao)){
  $db_opcao = $oGet->db_opcao;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
 db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js, estilos.css, grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<BR>
<center>
<form method="post" action="" id="formCadastro" >
	<fieldset style="width: 750px">
	<input type="hidden" value="<?= $db_opcao?>" name="tipoCadastro">
	  <legend>
	    <strong>Cadastro de Suspensão</strong>
	  </legend>
	
	  <table id="tableForm">
	    <tr>
	      <td><?= $Lrh101_sequencial?></td>
	      <td><? db_input('rh101_sequencial',5,$Irh101_sequencial,true,'text',3,"") ?></td>
	    </tr>
	    <tr>
	      <td><?= $Lrh101_regist; ?></td>
	      <td>
	      <? 
	        db_input('rh101_regist',5,$Irh101_regist,true,'text',3,"");
	        db_input('z01_nome',30,$Irh01_numcgm ,true,'text',3,"");
	      ?>
	      </td>
	    </tr>
	    <tr>
	      <td><?= $Lrh101_dtinicial?></td>
	      <td><? db_inputdata('rh101_dtinicial', $rh101_dtinicial[2], $rh101_dtinicial[1], $rh101_dtinicial[0], true,"",$db_opcao,""); ?></td>
	    </tr>
	    <tr>
	      <td><?= $Lrh101_dtfinal?></td>
	      <td><? db_inputdata('rh101_dtfinal', $rh101_dtfinal[2], $rh101_dtfinal[1], $rh101_dtfinal[0], true, "", $db_opcao, ""); ?></td>
	    </tr>
	    <tr <?= ($iTotalSuspensoesAtivas > 0  &&  $db_opcao != 3) ? "" : "style='display:none'" ;?> >
	      <td colspan="2">
		      <fieldset><legend><?= $Lrh101_obs; ?></legend>
		        <? db_textarea("rh101_obs",5,"","","","",2,"style='width:100%;'");?>
		      </fieldset>
	      </td>
	    </tr>
	  </table>
	</fieldset>

<?
if($db_opcao != 3){
  
  db_input("lblCadastrar","20","","","submit",1,"onClick='return {$jsCadastrar}'");
}
?> 
</form>
<fieldset style="width: 600px">
  <legend>
    <strong>Suspensões Cadastradas</strong>
  </legend>
  <div id="SuspensoesCadastradas"></div>
</fieldset>

</center>
</body>

</html>
<?
if(isset($_POST['tipoCadastro'])){
	$oPost = db_utils::postMemory($_POST);
	
	if($oPost->tipoCadastro == 22){
	 db_inicio_transacao();
   $clrhsuspensaopag->rh101_dtdesativacao_dia = date('d',db_getsession('DB_datausu')); 
   $clrhsuspensaopag->rh101_dtdesativacao_mes = date('m',db_getsession('DB_datausu')); 
   $clrhsuspensaopag->rh101_dtdesativacao_ano = date('Y',db_getsession('DB_datausu')); 
   $clrhsuspensaopag->rh101_dtdesativacao     = date('Y-m-d',db_getsession('DB_datausu')); 
   $clrhsuspensaopag->rh101_obs               = $oPost->rh101_obs ; 
   $clrhsuspensaopag->alterar($oPost->rh101_sequencial);
   if($clrhsuspensaopag->erro_status == 0){

   	 db_msgbox($clrhsuspensaopag->erro_msg);
   	 db_fim_transacao(true);
     db_redireciona("?iMatricula={$iMatricula}");
   } else {
   	
	   db_fim_transacao();
	   db_msgbox($clrhsuspensaopag->erro_msg);
     db_redireciona("?iMatricula={$iMatricula}");
   }

	}
	if($oPost->tipoCadastro == 2){
		
		$clrhsuspensaopag->rh101_regist            = null;  
		$clrhsuspensaopag->rh101_usuario           = db_getsession('DB_id_usuario'); 
		$clrhsuspensaopag->rh101_dtinicial_dia     = $oPost->rh101_dtinicial_dia; 
		$clrhsuspensaopag->rh101_dtinicial_mes     = $oPost->rh101_dtinicial_mes; 
		$clrhsuspensaopag->rh101_dtinicial_ano     = $oPost->rh101_dtinicial_ano; 
		$clrhsuspensaopag->rh101_dtinicial         = $oPost->rh101_dtinicial;
		$clrhsuspensaopag->rh101_dtcadastro        = date('Y-m-d',db_getsession('DB_datausu'));
		$clrhsuspensaopag->rh101_dtfinal_dia       = $oPost->rh101_dtfinal_dia; 
		$clrhsuspensaopag->rh101_dtfinal_mes       = $oPost->rh101_dtfinal_mes; 
		$clrhsuspensaopag->rh101_dtfinal_ano       = $oPost->rh101_dtfinal_ano; 
		$clrhsuspensaopag->rh101_dtfinal           = $oPost->rh101_dtfinal; 
		$clrhsuspensaopag->rh101_dtdesativacao     = null; 
		$clrhsuspensaopag->rh101_obs               = null; 
		$clrhsuspensaopag->incluir("");
	  if($clrhsuspensaopag->erro_status == 0){

	  	db_fim_transacao(true);
	  	db_msgbox($clrhsuspensaopag->erro_msg);
	  	
	  } else {
	  	
	    db_fim_transacao();
      db_msgbox($clrhsuspensaopag->erro_msg);
      db_redireciona("?iMatricula={$iMatricula}");
		}
  }
}
?>
<script>
function js_validaFormulario1(){
  $('rh101_obs').style.backgroundColor = "#FFFFFF";
  if( $('rh101_obs').getValue() == "" ){
  
    $('rh101_obs').style.backgroundColor = "#99A9AE";
    $('rh101_obs').focus();
    return false;
  }
  return true;
  
}
function js_validaFormulario2(){
  var sData1 = $('rh101_dtinicial').getValue();
  var sData2 = $('rh101_dtfinal').getValue();
 
  $('rh101_dtinicial').style.backgroundColor = "#FFFFFF";
  $('rh101_dtfinal')  .style.backgroundColor = "#FFFFFF";
    
  if( sData1 == "" ){

    alert("Campo DATA INICIAL não Informado");
    $('rh101_dtinicial').style.backgroundColor = "#99A9AE";
    $('rh101_dtinicial').focus();
    return false;
  }
  if( sData2 == "" ){
   
    alert("Campo DATA FINAL não Informado");
    $('rh101_dtfinal').style.backgroundColor = "#99A9AE";
    $('rh101_dtfinal').focus();
    return false;
  }

  if(js_diferenca_datas(js_formatar(sData1,"d"), js_formatar(sData2, "d"),3) == true){
    alert("Data Incial MAIOR que Data Final");
    $('rh101_dtinicial').style.backgroundColor = "#99A9AE";
    $('rh101_dtfinal')  .style.backgroundColor = "#99A9AE";
    $('rh101_dtinicial').focus();
  }
  return true;
  


}
function js_montaGrid() {

   oGridSuspensoes              = new DBGrid('oGridSuspensoes');
   oGridSuspensoes.nameInstance = 'oGridSuspensoes';
   oGridSuspensoes.sName        = 'oGridSuspensoes';
   oGridSuspensoes.setCellAlign(new Array("center", "center", "center", "center", "center", "center","left"));
   aHeaders                     = new Array('Seq.', 'Data de Cadastro', 'Usuário','&nbsp;Data Incial&nbsp;', '&nbsp;Data Final&nbsp;', 'Desativação','Observações');
   oGridSuspensoes.setHeader(aHeaders);

   oGridSuspensoes.show($('SuspensoesCadastradas'));
}
function getDadosSuspensoes(){
    var me                      = this;
    this.sRPC                   = 'pes1_rhsuspensaopag.RPC.php';
    var oParam                  = new Object();
    oParam.exec                 = 'getSuspensoes';
    oParam.oDados               = new Object();
    oParam.oDados.rh101_regist  = <?= $oGet->iMatricula; ?>; 
    js_divCarregando('Obtento Informações das Suspensões do Servidor', 'msgBox');
    var oAjax  = new Ajax.Request(me.sRPC,
                                {method: 'post',
                                parameters: 'json='+Object.toJSON(oParam), 
                                onComplete: function(oAjax) {
                                  js_removeObj('msgBox');
                                  var oRetorno = eval("("+oAjax.responseText+")");
                                  if (oRetorno.status== "2") {
                                    alert(oRetorno.message.urlDecode());
                                  } else {

                                    oGridSuspensoes.clearAll(true);
                                      if (oRetorno.aSuspensoes.length > 0) {
                                         
                                         for (i = 0; i < oRetorno.aSuspensoes.length; i++) {
                                         
                                           with (oRetorno.aSuspensoes[i]) {
                                    
                                             var aLinha = new Array();
                                             aLinha[0]  = rh101_sequencial;             
                                             aLinha[1]  = js_formatar(rh101_dtcadastro,"d");             
                                             aLinha[2]  = rh101_usuario + " - " + nome.urlDecode();             
                                             aLinha[3]  = js_formatar(rh101_dtinicial,"d");  
                                             aLinha[4]  = js_formatar(rh101_dtfinal,"d");  
                                             aLinha[5]  = js_formatar(rh101_dtdesativacao,"d");  
                                             aLinha[6]  = rh101_obs.urlDecode();
                                             oGridSuspensoes.addRow(aLinha);             
                                          }
                                        }
                                        oGridSuspensoes.renderRows();
                                     }  
                                  }
                                }
                              }) ;

}
js_montaGrid();
getDadosSuspensoes();
</script>