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
include("classes/db_mer_requi_classe.php");
include("classes/db_mer_requiitem_classe.php");
include("classes/db_mer_requiitem_classe_ext.php");
include("classes/db_mer_item_classe.php");
include("classes/db_mer_estoque_classe.php");
include("dbforms/db_funcoes.php");
require("libs/db_utils.php");
include("dbforms/db_classesgenericas.php");
require("classes/requisicaoMaterial.model.php");

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

//############ Função Material ############
  /**
   * Atende a requisicao, e faz as baixas no estoque
   * @method  atenderRequisicao;
   * @param integer $iCodtipo tipo da Requisição
   * @param array $aItensRequisicao array de objetos no formato {indice=>valor}(iCodMater iCodItemReq, nQtde ,iCodAlmox)
   * @param integer $iCodAlmox codigo do almoxarifado(depto)
   * @param integer $iCodAtend codigo do atendimento, caso houver
   * @return void
   */
function atenderRequisicao($iCodtipo, $aItensRequisicao, $iCodAlmox, $iCodAtend = null) {
  //die("Função errada =S ");
     /**
     * Devemos estar dentro de uma transaçao com o banco.
     */
  if (! db_utils::inTransaction()) {
    throw new Exception("Não existe transação ativa. Operação Cancelada");
  } 
     
  if (empty($iCodtipo)) {
    throw new Exception("Parametro iCodtipo nulo.");
  }
      
  if (! is_array($aItensRequisicao)) {
    throw new Exception("Parametro aItensRequisicao deve ser um array.");
  }
     
  if (count($aItensRequisicao) == 0) {
    throw new Exception("Nenhum item para ser atendido.");
  }
     
  $dData = date("Y-m-d", db_getsession("DB_datausu"));
  $iCodDepto = db_getsession("DB_coddepto");
  $tHora = db_hora();
  $iUsuario = db_getsession("DB_id_usuario");
    /**
     * salvamos o atendimento, nas tabelas:atendrequi, e os itens atendidos na atendrequitem
     * 1ª tabela : atendRequi (atendimento de requisicao;)
     * 
     */
  if ($iCodAtend == null) {
     
    $oDaoAtendrequi = db_utils::getDao("atendrequi");
    $oDaoAtendrequi->m42_data = $dData;
    $oDaoAtendrequi->m42_depto = $iCodDepto;
    $oDaoAtendrequi->m42_hora = $tHora;
    $oDaoAtendrequi->m42_login = $iUsuario;
    $oDaoAtendrequi->incluir(null);
    $iCodigoAtendimento = $oDaoAtendrequi->m42_codigo;
    if ($oDaoAtendrequi->erro_status == 0) {
        
      throw new Exception("Erro[1] - Não Foi possível efetuar o atendimento.\nErro Técnico: \n{$oDaoAtendrequi->erro_msg}");
      return false;
            
    }
  } else {
    $iCodigoAtendimento = $iCodAtend;	  
  }
    
    /**
     * iniciamos o movimento no estoque, gravando na matestoqueini 
     * com o codigo do tipo passado para o atendimento
     */
    
  $oDaoMatestoqueIni = db_utils::getDao("matestoqueini");
  $oDaoMatestoqueIni->m80_login = $iUsuario;
  $oDaoMatestoqueIni->m80_data = $dData;
  $oDaoMatestoqueIni->m80_hora = date('H:i:s');
  $oDaoMatestoqueIni->m80_obs = "";
  $oDaoMatestoqueIni->m80_codtipo = $iCodtipo;
  $oDaoMatestoqueIni->m80_coddepto = $iCodDepto;
  $oDaoMatestoqueIni->incluir(null);
  $iCodMatEstoqueIni = $oDaoMatestoqueIni->m80_codigo;
  if ($oDaoMatestoqueIni->erro_status == 0) {
      
    $sMsgErro = "Erro[2] - Não Foi possível Iniciar a movimentação no estoque).";
    $sMsgErro .= "\nErro Técnico: \n{$oDaoAtendrequiItem->erro_msg}";
    throw new Exception($sMsgErro);
    return false;
    
  }
    
    /**
     * Atualizamos o saldo no estoque, na tabela matestoqueitem, 
     * aqui, instaciamos o modelo materialEstoque,  para realizar o rateio 
     * dos itens conforme a regra do mesmo, e também obedecendo  o rateio feito pelo usuário
     * 
     * 1º )Incluimos os itens atendidos , na tabela atendrequiitem
     */
  $iTotItens = count($aItensRequisicao);
  for ($iInd = 0; $iInd < $iTotItens; $iInd ++) {
  	
    $oItemAtual = $this->getItens($aItensRequisicao [$iInd]->iCodItemReq);
    $oDaoAtendrequiItem = db_utils::getDao("atendrequiitem");
    $oDaoAtendrequiItem->m43_codatendrequi = $iCodigoAtendimento;
    $oDaoAtendrequiItem->m43_codmatrequiitem = $aItensRequisicao [$iInd]->iCodItemReq;
    $oDaoAtendrequiItem->m43_quantatend = $aItensRequisicao [$iInd]->nQtde;
    $oDaoAtendrequiItem->incluir(null);
    if ($oDaoAtendrequiItem->erro_status == 0) {
        
      $sMsgErro = "Erro[3] - Não Foi possível efetuar o atendimento do material({$oItemAtual->m41_codmatmater}).";
      $sMsgErro .= "\nErro Técnico: \n{$oDaoAtendrequiItem->erro_msg}";
      throw new Exception($sMsgErro);
      return false;
      
    }
      /**
       * Atualizamos a matestoqueitem.
       * primeiro, temos que descobrir os lotes do item (se existir).
       * o metodo ratearLotes do modelo materialEstoque realiza esse trabalho, 
       * onde ele ira retornar um array contendo as informações necessárias para 
       * fazermos a baixa do estoque.
       */
    if (! class_exists("materialEstoque")) {
      require 'classes/materialestoque.model.php';
    }     
    $oMaterialEstoque = new materialEstoque($oItemAtual->m41_codmatmater);
    $aItemsEstoque = $oMaterialEstoque->ratearLotes($aItensRequisicao [$iInd]->nQtde, null, $iCodAlmox);
      
      /**
       * Percorremos os lotes ou itens existes no estoque, para o material escolhido, 
       * para realizar a Baixa.
       */
    for($iIndEst = 0; $iIndEst < count($aItemsEstoque); $iIndEst ++) {
        
      if ($aItemsEstoque [$iIndEst]->rateio > 0) {
          
        $oDaoMatestoqueItem = db_utils::getDao("matestoqueitem");
        $nQuantidade = $aItemsEstoque [$iIndEst]->m71_quantatend + $aItemsEstoque [$iIndEst]->rateio;
        $oDaoMatestoqueItem->m71_quantatend = "$nQuantidade";
        $oDaoMatestoqueItem->m71_codlanc = $aItemsEstoque [$iIndEst]->m71_codlanc;
        $oDaoMatestoqueItem->alterar($aItemsEstoque [$iIndEst]->m71_codlanc);
        if ($oDaoMatestoqueItem->erro_status == 0) {
            
          $sMsgErro = "Erro[4] - Não Foi possível atualizar saldo do estoque do material({$oItemAtual->m41_codmatmater}).";
          $sMsgErro .= "\nErro Técnico: \n{$oDaoMatestoqueItem->erro_msg}";
          throw new Exception($sMsgErro);
          return false;
          
        }
          
          /**
           * incluimos na tabela AtentRequiItemMEI (Ligacao AtendRequiItem e MatEstoqueItem)
           * 
           */
          
        $oDaoAtendRequiItemMei = db_utils::getDao("atendrequiitemmei");
        $oDaoAtendRequiItemMei->m44_codatendreqitem = $oDaoAtendrequiItem->m43_codigo;
        $oDaoAtendRequiItemMei->m44_codmatestoqueitem = $aItemsEstoque [$iIndEst]->m71_codlanc;         
        $oDaoAtendRequiItemMei->m44_quant = $aItemsEstoque [$iIndEst]->rateio;
        $oDaoAtendRequiItemMei->incluir(null);
        if ($oDaoAtendRequiItemMei->erro_status == 0) {
            
          $sMsgErro = "Erro[5] - Não Foi possível atualizar saldo do estoque do material({$oItemAtual->m41_codmatmater}).";
          $sMsgErro .= "\nErro Técnico: \n{$oDaoAtendRequiItemMei->erro_msg}";
          throw new Exception($sMsgErro);
          return false;
          
        }         
        // Gera MatEstoqueIniMEI (Ligacao MatEstoqueIni e MatEstoqueItem)
        $oDaoMatEstoqueIniMei = db_utils::getDao("matestoqueinimei");
        $oDaoMatEstoqueIniMei->m82_matestoqueitem = $aItemsEstoque [$iIndEst]->m71_codlanc;
        $oDaoMatEstoqueIniMei->m82_matestoqueini = $iCodMatEstoqueIni;
        $oDaoMatEstoqueIniMei->m82_quant = $aItemsEstoque [$iIndEst]->rateio;
        $oDaoMatEstoqueIniMei->incluir(null);
        if ($oDaoMatEstoqueIniMei->erro_status == 0) {
            
          $sMsgErro = "Erro[6] - Não Foi possível atualizar saldo do estoque do material({$aItensRequisicao->iCodMater}).";
          $sMsgErro .= "\nErro Técnico: \n{$oDaoMatEstoqueIniMei->erro_msg}";
          throw new Exception($sMsgErro);
          return false;
          
        }
        $oMatEstoqueIniMeiARI = db_utils::getDao("matestoqueinimeiari");
        $oMatEstoqueIniMeiARI->m49_codatendrequiitem = $oDaoAtendrequiItem->m43_codigo;
        $oMatEstoqueIniMeiARI->m49_codmatestoqueinimei = $oDaoMatEstoqueIniMei->m82_codigo;
        $oMatEstoqueIniMeiARI->incluir(null);
        if ($oMatEstoqueIniMeiARI->erro_status == 0) {
            
          $sMsgErro = "Erro[7] - Não Foi possível atualizar saldo do estoque do material({$aItensRequisicao->iCodMater}).";
          $sMsgErro .= "\nErro Técnico: \n{$oMatEstoqueIniMeiARI->erro_msg}";
          throw new Exception($sMsgErro);
          return false;
          
        }
        $oMaterialEstoque->cancelarLoteSession();
      }
    }
  }
  return true;
}
//#########################################

$clmer_requi = new cl_mer_requi;
$clmer_requiitem = new cl_mer_requiitem;
$clmer_requiitem_ext = new cl_mer_requiitem_ext;
$clmer_item = new cl_mer_item;
$clmer_estoque = new cl_mer_estoque;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$db_botao                 = true;

if (isset($incluir)) {
	
  $sqlerro=false;
  db_inicio_transacao();  
  $sSqlAlmox  = "select m91_depto ";
  $sSqlAlmox .= "  from mer_requi  ";
  $sSqlAlmox .= "         inner join db_almox on m40_almox = m91_codigo ";
  $sSqlAlmox .= " where me16_codigo = {$me16_codigo}"; 
  $oMatRequi = db_utils::fieldsMemory($clmatrequi->sql_record($sSqlAlmox),0);  
  $result_mat = $clmer_requiitem->sql_record(
                                             $clmatrequiitem->sql_query(null,
                                                                        '*',
                                                                        null,
                                                                        "m41_codmatrequi = $m40_codigo 
                                                                         AND m41_codmatmater = $m41_codmatmater "
                                                                       )
                                            );
  if ($clmer_requiitem->numrows>0){
  	
    $m17_codigo = "";
    $me09c_descr_descr  = "";
    $me17_quant = "";
    $me17_obs   = "";
    $erro_msg   = "Material ja incluido nesta requisicao!!";
    $sqlerro     = true;
       
  }
  if ($sqlerro==false){
  	
    // Dados da MatRequi
    $sqlmatrequi = $clmer_requi->sql_query_file($m40_codigo, "*", null, null);
    $resmatrequi = $clmer_requi->sql_record($sqlmatrequi);
    db_fieldsmemory($resmatrequi, 0);
    $clmer_requiitem->me17_i_unidade     = '1';
    $clmer_requiitem->me17_i_item = $m40_codigo;
    $clmer_requiitem->incluir(null);
    if ($clmer_requiitem->erro_status==0) {
    //db_msgbox('1');
      $erro_msg = $clmer_requiitem->erro_msg;
      $sqlerro  = true;
      
    }
    $me10_i_codigo   = $clmer_requiitem->me17_i_item;
    $me17_i_codigo= $clmer_requiitem->me17_i_codigo;
    $tot_quant  = $clmer_requiitem->me17_f_quant;
    $aItens = array();
    $aSubItens[0]->iCodMater   = $codmater;
    $aSubItens[0]->iCodItemReq = $codreqitem;
    $aSubItens[0]->iCodalmox   = $oMatRequi->m91_depto;
    $aSubItens[0]->nQtde       = $tot_quant;    
    $aItens[] = $aSubItens;
    try {
    	      
      //$oRequisicao = new requisicaoMaterial($me16_i_codigo);
      atenderRequisicao(17, $aSubItens, $oMatRequi->m91_depto,$m42_codigo);
      
    }
    catch (Exception $eErro) {
         
      $sqlerro = true;
      $erro_msg = $eErro->getMessage();
         
    }
    db_fim_transacao($sqlerro);       
    $me10_codigo = "";
    $me10_c_descr       = "";
    $me17_quant       = "";
  }
} else if (isset($excluir)) {
  $sqlerro=false;
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
    <fieldset style="width:95%"><legend><b>Exclusão Requisição de Itens</b></legend>
	<?	include("forms/db_frmmer_req.php");?>
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
	include("forms/db_frmmer_req.php");
	?>
    </center>
<?db_menu(db_getsession("DB_id_usuario"),
          db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),
          db_getsession("DB_instit")
         );
?>
</body>
</html>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clmatrequiitem->erro_campo!=""){
      echo "<script> parent.document.form1.".$clmatrequiitem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> parent.document.form1.".$clmatrequiitem->erro_campo.".focus();</script>";
    }
  }else{
    db_msgbox('Inclusão Efetuada com Sucesso!!');
    echo "<script>
            parent.iframe_matrequiitem.location.href='mat1_matrequiitemauto001.php?m40_codigo=".@$m40_codigo."
                                                   &m42_codigo=".@$m42_codigo."&m80_codigo=".@$m80_codigo."';\n
	      </script>";

  }
}  
?>