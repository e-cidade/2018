<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_diasemana_classe.php");
include("classes/db_mer_cardapio_classe.php");
include("classes/db_mer_cardapiodata_classe.php");
include("dbforms/db_funcoes.php");

//Classes Materiais
include("classes/db_matrequiitem_classe.php");
include("classes/db_matrequi_classe.php");
include("classes/db_atendrequi_classe.php");
include("classes/db_atendrequiitem_classe.php");
include("classes/db_atendrequiitemmei_classe.php");
include("classes/db_matestoque_classe.php");
include("classes/db_matestoqueini_classe.php");
include("classes/db_matestoqueinimei_classe.php");
include("classes/db_matestoqueinimeiari_classe.php");
include("classes/db_matestoqueitem_classe.php");
include("classes/db_db_almoxdepto_classe.php");
include("classes/db_db_almox_classe.php");
include("classes/db_matmater_classe.php");
include("classes/db_matparam_classe.php");
include("classes/db_db_departorg_classe.php");
require("classes/requisicaoMaterial.model.php");
require("libs/db_utils.php");
require("std/db_stdClass.php");
require("classes/materialestoque.model.php");

require_once "libs/db_app.utils.php";
db_app::import("contabilidade.contacorrente.ContaCorrenteFactory");
db_app::import("Acordo");
db_app::import("AcordoComissao");
db_app::import("CgmFactory");
db_app::import("financeiro.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("Dotacao");
db_app::import("contabilidade.planoconta.*");
db_app::import("contabilidade.contacorrente.*");

$escola = db_getsession("DB_coddepto");
$login = DB_getsession("DB_id_usuario");
db_postmemory($HTTP_POST_VARS);
$cldiasemana = new cl_diasemana;
$clmer_cardapio = new cl_mer_cardapio;
$clmer_cardapiodata = new cl_mer_cardapiodata;
$clmatparam               = new cl_matparam;
$cldb_departorg           = new  cl_db_departorg;
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clmatrequiitem           = new cl_matrequiitem;
$clmatrequi               = new cl_matrequi;
$clatendrequiitem         = new cl_atendrequiitem;
$clatendrequiitemmei      = new cl_atendrequiitemmei;
$clatendrequi             = new cl_atendrequi;
$clmatestoque             = new cl_matestoque;
$clmatestoqueini          = new cl_matestoqueini;
$clmatestoqueinimei       = new cl_matestoqueinimei;
$clmatestoqueinimeiari    = new cl_matestoqueinimeiari;
$clmatestoqueitem         = new cl_matestoqueitem;
$cldb_almoxdepto          = new cl_db_almoxdepto;
$cldb_almox               = new cl_db_almox;
$clmatmater               = new cl_matmater;
$clmaterialEstoque        = new materialEstoque;

$db_botao                 = true;
$db_opcao = 1;
$db_botao = true;
if (isset($incluir)) {
	
  db_inicio_transacao();  
  $sql    = "select me12_i_cardapio,me12_i_diasemana from mer_cardapiodia";
  $result = pg_query($sql);
  $linhas = pg_num_rows($result);
  for ($x=0;$x<$linhas;$x++) {
  	
  	db_fieldsmemory($result,$x);
  	$weeke                               = date("w", mktime(0,0,0,date("m"),date("d"),date("Y")));
    $fator                               = $me12_i_diasemana-($weeke+1);
  	$data                                = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")+$fator, date("y")));
  	$clmer_cardapiodata->me13_d_data     = $data; 
  	$clmer_cardapiodata->me13_i_cardapio = $me12_i_cardapio;     
    $clmer_cardapiodata->incluir("");
     
  }
  $vetcod   = explode(",",$cod);
  $vetitem  = explode(",",$item);
  $vetquant = explode(",",$quant);  
  $sqlerro  = "N";   
  $coddepto = db_getsession("DB_coddepto");
  $sqlalmox = $cldb_almox->sql_query_file(null, "*", null, "m91_depto=$coddepto");
  $resalmox = $cldb_almox->sql_record($sqlalmox);
  if ($cldb_almox->numrows>0) {
    db_fieldsmemory($resalmox, 0);
  } else {
  	
    $sqlerro="S";
    $erro_msg="Departamento $coddepto não é um Almoxarifado!";
    
  }
  if ($sqlerro=="N") {
  	
    $clmatrequi->m40_data  = date("Y-m-d",db_getsession("DB_datausu"));
    $clmatrequi->m40_auto  = 't';
    $clmatrequi->m40_depto = $escola;
    $clmatrequi->m40_login = $login;
    $clmatrequi->m40_almox = $m91_codigo;
    $clmatrequi->m40_hora  = db_hora();
    $clmatrequi->m40_obs   = "";   
    $clmatrequi->incluir(null);
    $codigorequi=$clmatrequi->m40_codigo;
    if ($clmatrequi->erro_status==0) {
    	
      $sqlerro="S";
      $erro_msg=$clmatrequi->erro_msg;
      
    }   	 
  }
  if ($sqlerro=="N") {
  	
   	for ($i=0;$i<count($vetcod);$i++) {
   		       
   	  $clmatrequiitem->m41_codunid     = '1';
      $clmatrequiitem->m41_codmatrequi = $clmatrequi->m40_codigo;
	  $clmatrequiitem->m41_codmatmater = $vetitem[$i];
	  $clmatrequiitem->m41_quant       = $vetquant[$i];
	  $clmatrequiitem->m41_obs         = "";
      $clmatrequiitem->incluir(null);
      if ($clmatrequiitem->erro_status==0) {
      	
        $erro_msg = $clmatrequiitem->erro_msg;
        $sqlerro  = "S";
        
      }
      $codmater                   = $clmatrequiitem->m41_codmatmater;
      $codreqitem                 = $clmatrequiitem->m41_codigo;
      $tot_quant                  = $clmatrequiitem->m41_quant;
      $aItens                     = array();
      $aSubItens[$i]->iCodMater   = $codmater;  
      $aSubItens[$i]->iCodItemReq = $codreqitem;
      $aSubItens[$i]->iCodalmox   = $coddepto;
      $aSubItens[$i]->nQtde       = $tot_quant;
      $aItens[] = $aSubItens;	
	}	  
	
	try{
		
	   	 $oRequisicao = new requisicaoMaterial($clmatrequi->m40_codigo);
         $oRequisicao->atenderRequisicao(17, $aSubItens, $coddepto,$clatendrequi->m42_codigo);
         
    }
    catch (Exception $eErro) {
    	         
         $sqlerro  = "S";
         $erro_msg = $eErro->getMessage();
         
    } 
    db_fim_transacao(($sqlerro == "S"?true:false));     
  }
  
  $m41_codmatmater = "";
  $m60_descr       = "";
  $m41_quant       = "";
  $m41_obs         = "";
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
<table border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
	<br><br>
    <fieldset style="width:105%"><legend><b>Baixa Semanal</b></legend>
	<? include("forms/db_frmmerbaixa.php");;?>
	</fieldset>
	</td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),
        db_getsession("DB_modulo"),
        db_getsession("DB_anousu"),
        db_getsession("DB_instit")
       );
?>
</body>
</html>
<?
if (isset($incluir) || isset($alterar) || isset($excluir)) {
	
  if ($sqlerro==true) {
  	
    if ($clmatrequiitem->erro_campo!="") {
    	
      echo "<script> parent.document.form1.".$clmatrequiitem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> parent.document.form1.".$clmatrequiitem->erro_campo.".focus();</script>";
      
    }
  } else {
    db_msgbox('Inclusão Efetuada com Sucesso!!');    
  }  
  ?>
  <script>js_reload();</script>
  <?
}  
?>