<?php
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
require_once("std/db_stdClass.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_matrequiitem_classe.php");
require_once("classes/db_matrequi_classe.php");
require_once("classes/db_atendrequi_classe.php");
require_once("classes/db_atendrequiitem_classe.php");
require_once("classes/db_atendrequiitemmei_classe.php");
require_once("classes/db_matestoque_classe.php");
require_once("classes/db_matestoqueini_classe.php");
require_once("classes/db_matestoqueinimei_classe.php");
require_once("classes/db_matestoqueinimeiari_classe.php");
require_once("classes/db_matestoqueitem_classe.php");
require_once("classes/db_db_almoxdepto_classe.php");
require_once("classes/db_matmater_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_matparam_classe.php");
require_once("classes/db_db_departorg_classe.php");
require_once("classes/requisicaoMaterial.model.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

require_once("model/contabilidade/contacorrente/ContaCorrenteFactory.model.php");
require_once("model/contabilidade/contacorrente/ContaCorrenteBase.model.php");
require_once("model/financeiro/ContaBancaria.model.php");
require_once("model/contabilidade/planoconta/ContaPlano.model.php");
require_once("model/contabilidade/planoconta/ClassificacaoConta.model.php");
require_once("model/contabilidade/planoconta/ContaCorrente.model.php");
require_once("model/contabilidade/planoconta/ContaOrcamento.model.php");
require_once("model/contabilidade/planoconta/ContaPlanoPCASP.model.php");

db_app::import("Acordo");
db_app::import("AcordoComissao");
db_app::import("CgmFactory");
db_app::import("financeiro.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("Dotacao");
db_app::import("contabilidade.contacorrente.*");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clmatparam               = new cl_matparam;
$cldb_departorg           = new  cl_db_departorg;
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clmatrequiitem           = new cl_matrequiitem;
$clmatrequi               = new cl_matrequi;
$clatendrequiitem         = new cl_atendrequiitem;
$clatendrequiitemmei      = new cl_atendrequiitemmei;
$clatendrequi             = new cl_atendrequi;
$clmatestoque             =  new cl_matestoque;
$clmatestoqueini          =  new cl_matestoqueini;
$clmatestoqueinimei       =  new cl_matestoqueinimei;
$clmatestoqueinimeiari    =  new cl_matestoqueinimeiari;
$clmatestoqueitem         =  new cl_matestoqueitem;
$cldb_almoxdepto          = new cl_db_almoxdepto;
$clmatmater               = new cl_matmater;
$db_botao                 = true;
$rsParam                  = $clmatparam->sql_record($clmatparam->sql_query_file());
$oParam                   = db_utils::fieldsMemory($rsParam,0);
$tobserva                 = $oParam->m90_modrelsaidamat;

$iTipoControleCustos = 0;
$oDaoParcustos = new cl_parcustos();
$sWhereParametros = 'cc09_anousu = '. db_getsession("DB_anousu") . ' and cc09_instit = ' . db_getsession('DB_instit');
$sSqlParametros = $oDaoParcustos->sql_query_file(null, 'cc09_tipocontrole', null, $sWhereParametros);
$rsParametros = db_query($sSqlParametros); 

if ($rsParametros && pg_num_rows($rsParametros) > 0) {
  $iTipoControleCustos = db_utils::fieldsMemory($rsParametros, 0)->cc09_tipocontrole;
}

if (isset($m40_codigo)) {

  $sSqlAlmox   = "select m40_depto ";
  $sSqlAlmox  .= "  from matrequi  ";
  $sSqlAlmox  .= " where m40_codigo = {$m40_codigo}";

  $rsDeptoRequi = $clmatrequi->sql_record($sSqlAlmox);
  $oDeptoRequi = ($clmatrequi->numrows > 0) ? db_utils::fieldsMemory($rsDeptoRequi, 0) : null;
}

if (isset($incluir)) {
  $sqlerro=false;
  db_inicio_transacao();

  $sSqlAlmox  = "select m91_depto,m40_depto ";
  $sSqlAlmox .= "  from matrequi  ";
  $sSqlAlmox .= "         inner join db_almox on m40_almox = m91_codigo ";
  $sSqlAlmox .= " where m40_codigo = {$m40_codigo}";
  $oMatRequi = db_utils::fieldsMemory($clmatrequi->sql_record($sSqlAlmox),0);

  $result_mat = $clmatrequiitem->sql_record($clmatrequiitem->sql_query(null,'*',null,"m41_codmatrequi = $m40_codigo and m41_codmatmater = $m41_codmatmater "));
  if ($clmatrequiitem->numrows>0){
       $m41_codmatmater = "";
       $m60_descr       = "";
       $m41_quant       = "";
       $m41_obs         = "";
       $erro_msg        = "Material ja incluido nesta requisicao!!";
       $sqlerro         = true;

  }

  if ($sqlerro==false){
       // Dados da MatRequi
       $sqlmatrequi = $clmatrequi->sql_query_file($m40_codigo, "*", null, null);
       $resmatrequi = $clmatrequi->sql_record($sqlmatrequi);
       db_fieldsmemory($resmatrequi, 0);

  //db_criatabela($resmatrequi);

       $clmatrequiitem->m41_codunid     = '1';
       $clmatrequiitem->m41_codmatrequi = $m40_codigo;
       $clmatrequiitem->incluir(null);
       if ($clmatrequiitem->erro_status==0) {
    //db_msgbox('1');
            $erro_msg = $clmatrequiitem->erro_msg;
            $sqlerro  = true;
       }
       $codmater   = $clmatrequiitem->m41_codmatmater;
       $codreqitem = $clmatrequiitem->m41_codigo;
       $tot_quant  = $clmatrequiitem->m41_quant;

       // Gera Array Com Itens do Atendimento
       //(iCodMater iCodItemReq, nQtde ,iCodAlmox)
       $aItens = array();
       $aSubItens[0]->iCodMater      = $codmater;
       $aSubItens[0]->iCodItemReq    = $codreqitem;
       $aSubItens[0]->iCodalmox      = $oMatRequi->m91_depto;
       $aSubItens[0]->nQtde          = $tot_quant;
       if ($iTipoControleCustos  == 2) {

         if ($cc08_sequencial == "") {

           $erro_msg = "Centro de Custos não informado.";
           $sqlerro  = true;

         }
       }
       if (isset($cc08_sequencial) && $cc08_sequencial != "") {
         $aSubItens[0]->iCentroDeCusto = $cc08_sequencial;
       }

       $aItens[] = $aSubItens;

       //var_dump($aItens);
       //die();

       // Efetua atendimento da requisicao
       try {

         $oRequisicao = new requisicaoMaterial($m40_codigo);

         if ($oParam->m90_tipocontrol == "S") {
           $depto_passar = $departamento;
         } else {
           $depto_passar = $oMatRequi->m91_depto;
         }

         $oRequisicao->atenderRequisicao(17, $aSubItens, $depto_passar,$m42_codigo);
       }
       catch (Exception $eErro) {

         $sqlerro = true;
         $erro_msg = $eErro->getMessage();

       }
       //$sqlerro = ($erro_msg <> "");

       db_fim_transacao($sqlerro);

       $m41_codmatmater = "";
       $m60_descr       = "";
       $m41_quant       = "";
       $m41_obs         = "";
  }
} else if (isset($excluir)) {
  $sqlerro=false;
  db_inicio_transacao();
  $clatendrequiitem->excluir(null,"m43_codmatrequiitem = $m41_codigo");
  $clmatrequiitem->excluir($m41_codigo);
  $erro_msg=$clmatrequiitem->erro_msg;
  if ($clmatrequiitem->erro_status==0) {
    $sqlerro=true;
  }
  if ($sqlerro==false) {
    $m41_codmatmater="";
    $m41_obs="";
    $m41_quant="";
    $m60_descr="";
  }
  $db_botao = true;
  $db_opcao = 1;
  db_fim_transacao($sqlerro);
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
   <center>
	<?
	include("forms/db_frmmatrequiitemauto.php");
	?>
    </center>
</body>
</html>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  if($sqlerro==true){
    db_msgbox(str_replace("\n","\\n",$erro_msg));
    //$clmatrequiitem->erro(true,false);
    if($clmatrequiitem->erro_campo!=""){
      echo "<script> document.form1.".$clmatrequiitem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmatrequiitem->erro_campo.".focus();</script>";
    }
  }else{
    db_msgbox('Inclusão Efetuada com Sucesso!!');
    echo "<script>
               parent.iframe_matrequiitem.location.href='mat1_matrequiitemauto001.php?m40_codigo=".@$m40_codigo."&m42_codigo=".@$m42_codigo."&m80_codigo=".@$m80_codigo."';\n
	 </script>";

  }
}
?>
