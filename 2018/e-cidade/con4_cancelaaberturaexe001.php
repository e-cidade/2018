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

require ("libs/db_stdlib.php");
require ("libs/db_utils.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("classes/db_conaberturaexe_classe.php");
include ("classes/db_db_config_classe.php");
include ("classes/db_conplanoconta_classe.php");
include ("classes/db_conplano_classe.php");
include ("classes/db_orcelemento_classe.php");
include ("classes/db_orcorgao_classe.php");
include ("classes/db_orcunidade_classe.php");
include ("classes/db_orcprograma_classe.php");
include ("classes/db_orcprojativ_classe.php");
include ("classes/db_orcfontes_classe.php");
include ("classes/db_orcparametro_classe.php");
include ("classes/db_orcfontesdes_classe.php");
include ("classes/db_conplanoreduz_classe.php");
include ("dbforms/db_funcoes.php");

(boolean) $sSqlErro = false;
(string) $sErro = null;
(int) $iNumRows = 0;
$p = db_utils::postMemory($_POST);
$g = db_utils::postMemory($_GET);
$clconaberturaexe = new cl_conaberturaexe();
$clconplano       = new cl_conplano();
$clorcelemento    = new cl_orcelemento();
$clorcorgao       = new cl_orcorgao();
$clorcunidade     = new cl_orcunidade();
$clorcprograma    = new cl_orcprograma();
$clorcparametro   = new cl_orcparametro();
$clorcprojativ    = new cl_orcprojativ();
$clorcfontes      = new cl_orcfontes();
$clorcfontesdes   = new cl_orcfontesdes();
$clconplanoreduz  = new cl_conplanoreduz();
$clconplanoconta  = new cl_conplanoconta();
$cldb_config      = new cl_db_config();
$clconaberturaexe->rotulo->label();
$clrotulo = new rotulocampo();
$clrotulo->label("nomeinst");
$clrotulo->label("nome");
$db_opcao = 22;
$db_botao = true;
if (isset($g->chavepesquisa)) {
  
  $db_opcao = 2;
  $result   = $clconaberturaexe->sql_record($clconaberturaexe->sql_query($g->chavepesquisa));
  db_fieldsmemory($result, 0);
  $db_botao = true;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <form name="form1" method="post" action="">
  
  
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<table>
<tr>
  <td>
  <fieldset><legend><b>Cancelar Exercício Contábil</b></legend>
  <table>
    <tr>
      <td nowrap title="<?=@$Tc91_sequencial?>">
                 <?=@$Lc91_sequencial?>
                </td>
      <td> 
                 <?
                db_input('c91_sequencial', 5, $Ic91_sequencial, true, 'text', 3, "")?>
								</td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tc91_anousuorigem?>">
                 <?=@$Lc91_anousuorigem?>
                </td>
      <td> 
                 <?
                $c91_anousuorigem = db_getsession('DB_anousu');
                db_input('c91_anousuorigem', 5, $Ic91_anousuorigem, true, 'text', 3, "")?>
								</td>
    </tr>
    <tr>

      <td nowrap title="<?=@$Tc91_anousudestino?>">
                  <?=@$Lc91_anousudestino?>
                 </td>
      <td> 
                 <?
                //$c91_anodestino = db_getsession('DB_anousu');
                db_input('c91_anousudestino', 5, $Ic91_anousudestino, true, 'text', 3, "")?>
								</td>
    </tr>
  </table>
  </fieldset>
  </td>
  </tr>
</table>
<input name="cancelar" onclick='return js_cancela()' type="submit" id="db_opcao" value="Cancelar"
  <?=($db_botao == false ? "disabled" : "")?>> <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar"
  onclick="js_pesquisa();"></center>
</td>
</tr>
</table>
</center>
</form>
</body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_conaberturaexe','func_conaberturaexe.php?tipo=1&funcao_js=parent.js_preenchepesquisa|c91_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_conaberturaexe.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '" . basename($GLOBALS ["HTTP_SERVER_VARS"] ["PHP_SELF"]) . "?chavepesquisa='+chave";
  }
  ?>
}
function js_cancela(){

 return (confirm('Confirma o cancelamento?'));
}
</script>
<?
if (isset($p->cancelar)) {
  
  $iTotProcedimentos = 17;
  $iProcedimento     = 0;
  
  db_inicio_transacao();
  $sqlVer = "select * 
	                from  conaberturaexe
								 where c91_tipo <> 1
								   and c91_situacao in (1,2)
									 and c91_anousudestino = " . (db_getsession("DB_anousu") + 1) . "
									 and c91_instit        = " . db_getsession("DB_instit");
  $rs = pg_query($sqlVer);
  if (pg_num_rows($rs) > 0) {
    
    $sSqlErro = true;
    $sErro = "Há configurações em aberto para	o Exericício " . (db_getsession("DB_anousu") + 1) . "\\n Verifique";
  }
  $usuInsti = db_getsession("DB_instit");
  $sqlPref = "select * from db_config where prefeitura is true";
  $rsPref = pg_query($sqlPref);
  $oPref = db_utils::fieldsMemory($rsPref, 0);
  $rsAb = $clconaberturaexe->sql_record($clconaberturaexe->sql_query($p->c91_sequencial));
  $iNumRows = $clconaberturaexe->numrows;
  if ($iNumRows == 0) {
    break;
  } else {
    $oAb = db_utils::fieldsmemory($rsAb, 0);
  }
  
  $clconaberturaexe->c91_situacao = '3';
  $clconaberturaexe->alterar($p->c91_sequencial);
  db_criatermometro("divexclui", 'concluido...', 'blue', 1);
  flush();
  if ($clconaberturaexe->erro_status == 0) {
    $sSqlErro = true;
    $sErro = $clonaberturaexe->erro_msg;
    break;
  }
  if ($sSqlErro == false) {
    
    if ($oPref->codigo == $usuInsti) {
      $clorcparametro->excluir($oAb->c91_anousudestino);
      if ($clorcparametro->erro_status == 0) {
        $sSqlErro = true;
        $sErro = $clorcparametro->erro_msg;
        //break;
      }
    }
    db_atutermometro($iProcedimento++, $iTotProcedimentos, 'divexclui');
    flush();
  }
  
  if ($sSqlErro == false) {
  
    $oDaoConplano  = db_utils::getDao("conplanoconplanoorcamento");
    $oDaoConplano->excluir(null, "c72_anousu=" . $oAb->c91_anousudestino);
  
    if ($oDaoConplano->erro_status == 0) {
  
      $sSqlErro = true;
      $sErro    = $oDaoConplano->erro_msg;
    }
  
    db_atutermometro($iProcedimento++, $iTotProcedimentos, 'divexclui');
    flush();
  }

  /*
   * Excluimos conplanogrupo para o exercicio;
   * Somente poderá ser exluido caso a instituiçao logada seja a prefeitura.
   */
  if ($sSqlErro == false) {
    
    if ($oPref->codigo == $usuInsti) {
      $oDaoConPlanoGrupo = db_utils::getDao("conplanogrupo");
      $oDaoConPlanoGrupo->excluir(null, "c21_anousu={$oAb->c91_anousudestino}");
      
      if ($oDaoConPlanoGrupo->erro_status == 0) {
        $sSqlErro = true;
        $sErro = $oDaoConPlanoGrupo->erro_msg;
        //break;
      }
    }
    db_atutermometro($iProcedimento++, $iTotProcedimentos, 'divexclui');
    flush();
  }
  
  /**
   * Excluimos conplanoorcamentogrupo para o exercicio;
   * Somente poderá ser exluido caso a instituiçao logada seja a prefeitura.
   */
  
  if (!$sSqlErro) {
    
    if ($oPref->codigo == $usuInsti) {
      
      $oDaoOrcamentoGrupo = db_utils::getDao("conplanoorcamentogrupo");
      $oDaoOrcamentoGrupo->excluir(null, "c21_anousu = {$oAb->c91_anousudestino}");
      if ($oDaoOrcamentoGrupo->erro_status == 0) {
        
        $sSqlErro = true;
        $sErro    = $oDaoOrcamentoGrupo->erro_msg;
      }
    }
    db_atutermometro($iProcedimento++, $iTotProcedimentos, 'divexclui');
    flush();
  }
  
  /**
   * Busca os dados na tabela SALTES
   */
  if ($sSqlErro == false) {
    
    /**
     * Instancia a classe DAO saltes e busca todos os registros em que o campo k13_limite esteja nulo.
     */
    $oDaoSaltes       = db_utils::getDao("saltes");
    $sSqlBuscaSaltes  = $oDaoSaltes->sql_query_file(null, "*", null, "k13_limite is null");
    $rsBuscaSaltes    = $oDaoSaltes->sql_record($sSqlBuscaSaltes);
    $iRowsBuscaSaltes = $oDaoSaltes->numrows;
    
    /**
     * Array que armazenará as contas que tiveram update realizado
     */
    $aContasUpdate = array();
    if ($iRowsBuscaSaltes > 0) {

      /**
       * Percorre o ResultSet e faz update no campo k13_limite para a data corrente.
       */
      for ($iRow = 0; $iRow < $iRowsBuscaSaltes; $iRow++) {
        
        $oDadosSaltes           = db_utils::fieldsMemory($rsBuscaSaltes, $iRow);
        $oDaoSaltes->k13_limite = date("Y-m-d", db_getsession('DB_datausu'));
        $oDaoSaltes->k13_conta  = $oDadosSaltes->k13_conta;
        $oDaoSaltes->alterar($oDadosSaltes->k13_conta);
        
        if ($oDaoSaltes->erro_status == "0") {
          $sErro    = $oDaoSaltes->erro_msg;
          $sSqlErro = true;
        }
        
        // Armazena a conta que teve o campo k13_limit alterado
        $aContasUpdate[] = $oDadosSaltes->k13_conta;
      }
    }
  }
  
  
  //exclui da conplanoreduz 
  if ($sSqlErro == false) {
    
    $clconplanoreduz->excluir(null, null, "c61_anousu      = " . $oAb->c91_anousudestino);
			                                     
    if ($clconplanoreduz->erro_status == 0) {
      $sSqlErro = true;
      $sErro = $clconplanoreduz->erro_msg;
//       break;
    }
    db_atutermometro($iProcedimento++, $iTotProcedimentos, 'divexclui');
    flush();
  }
  /**
   * Excluimos conplanoorcamentoanalitica para o exercicio;
   */
  if (!$sSqlErro) {
    
    $oDaoAnalitica = db_utils::getDao("conplanoorcamentoanalitica");
    $oDaoAnalitica->excluir(null, $oAb->c91_anousudestino);
    
    if ($oDaoAnalitica->erro_status == 0) {
      
      $sSqlErro = true;
      $sErro    = $oDaoAnalitica->erro_msg;
    }
    db_atutermometro($iProcedimento++, $iTotProcedimentos, 'divexclui');
    flush();
  }
  
  //exclui da conplanoconta 
  if ($sSqlErro == false) {
    
    if ($oPref->codigo == $usuInsti) {
      $clconplanoconta->excluir(null, null, "c63_anousu = " . $oAb->c91_anousudestino);
      if ($clconplanoconta->erro_status == 0) {
        $sSqlErro = true;
        $sErro = $clconplanoconta->erro_msg;
        //break;
      }
    }
    db_atutermometro($iProcedimento++, $iTotProcedimentos, 'divexclui');
    flush();
  }
  
  /**
   * Excluimos conplanoorcamentoconta para o exercicio;
   * Somente poderá ser exluido caso a instituiçao logada seja a prefeitura.
   */
  if (!$sSqlErro) {
  
    if ($oPref->codigo == $usuInsti) {
      
      $oDaoOrcamentoConta = db_utils::getDao('conplanoorcamentoconta');
      $oDaoOrcamentoConta->excluir(null, null, "c63_anousu = {$oAb->c91_anousudestino}");
      if ($oDaoOrcamentoConta->erro_status == 0) {
        
        $sSqlErro = true;
        $sErro    = $clconplanoconta->erro_msg;
      }
    }
    db_atutermometro($iProcedimento++, $iTotProcedimentos, 'divexclui');
    flush();
  }
  //exclui da conplano 
  if ($sSqlErro == false) {
    
    if ($oPref->codigo == $usuInsti) {
      $clconplano->excluir(null, null, "c60_anousu = " . $oAb->c91_anousudestino);
      if ($clconplano->erro_status == 0) {
        $sSqlErro = true;
        $sErro = $clconplano->erro_msg;
        //break;
      }
    }
    db_atutermometro($iProcedimento++, $iTotProcedimentos, 'divexclui');
    flush();
  }
  /**
  * Excluimos conplanoorcamento para o exercicio;
  * Somente poderá ser exluido caso a instituiçao logada seja a prefeitura.
  */
  if (!$sSqlErro) {
  
    if ($oPref->codigo == $usuInsti) {
      
      $oDaoOrcamento = db_utils::getDao('conplanoorcamento');
      $oDaoOrcamento->excluir(null, null, "c60_anousu = {$oAb->c91_anousudestino}");
      if ($oDaoOrcamento->erro_status == 0) {
        
        $sSqlErro = true;
        $sErro    = $clconplano->erro_msg;
      }
    }
    db_atutermometro($iProcedimento++, $iTotProcedimentos, 'divexclui');
    flush();
  }
  
  /**
   * Altera os dados do campo k13_limite novamente na tabela SALTES
   */
  if ($sSqlErro == false) {
    
    if (count($aContasUpdate) > 0) {

      /**
       * Percorre o array criado com as contas da tabela SALTES e faz update
       * do campo k13_limite para null
       */
      foreach ($aContasUpdate as $iContaSaltes) {
        
        $HTTP_POST_VARS["k13_limite_dia"] = '';
        $oDaoSaltes->k13_limite  = null;
        $oDaoSaltes->k13_conta   = $iContaSaltes;
        $oDaoSaltes->alterar($iContaSaltes);
        
        if ($oDaoSaltes->erro_status == "0") {
          
          $sSqlErro = true;
          $sErro    = $oDaoSaltes->erro_msg;
        }
      }
    }
  }
  
  //exclui da orcfontesdes
  if ($sSqlErro == false) {
    
    if ($oPref->codigo == $usuInsti) {
      $clorcfontesdes->excluir($oAb->c91_anousudestino);
      if ($clorcfontesdes->erro_status == 0) {
        $sSqlErro = true;
        $sErro = $clorcfontesdes->erro_msg;
        //break;
      }
    }
    db_atutermometro($iProcedimento++, $iTotProcedimentos, 'divexclui');
    flush();
  }
  
  //exclui da orcfontes
  if ($sSqlErro == false) {
    
    if ($oPref->codigo == $usuInsti) {
      $clorcfontes->excluir(null, $oAb->c91_anousudestino);
      if ($clorcfontes->erro_status == 0) {
        $sSqlErro = true;
        $sErro = $clorcfontes->erro_msg;
        //break;
      }
    }
    db_atutermometro($iProcedimento++, $iTotProcedimentos, 'divexclui');
    flush();
  }
  //exclui da orcprojativ
  if ($sSqlErro == false) {
    
    if ($oPref->codigo == $usuInsti) {
      $clorcprojativ->excluir($oAb->c91_anousudestino);
      if ($clorcprojativ->erro_status == 0) {
        $sSqlErro = true;
        $sErro = $clorcprojativ->erro_msg;
        //break;
      }
    }
    db_atutermometro($iProcedimento++, $iTotProcedimentos, 'divexclui');
    flush();
  }
  //exclui da orcprograma
  if ($sSqlErro == false) {
    
    if ($oPref->codigo == $usuInsti) {
      $clorcprograma->excluir($oAb->c91_anousudestino);
      if ($clorcprograma->erro_status == 0) {
        $sSqlErro = true;
        $sErro = $clorcprograma->erro_msg;
        //break;
      }
    }
    db_atutermometro($iProcedimento++, $iTotProcedimentos, 'divexclui');
    flush();
  }
  //exclui da orcunidade
  if ($sSqlErro == false) {
    
    $clorcunidade->excluir(null, null, null, "o41_anousu = $oAb->c91_anousudestino and o41_instit = " . $usuInsti);
    if ($clorcunidade->erro_status == 0) {
      $sSqlErro = true;
      $sErro = $clorcunidade->erro_msg;
      //break;
    }
    db_atutermometro($iProcedimento++, $iTotProcedimentos, 'divexclui');
    flush();
  }
  //exclui da orcorgao
  if ($sSqlErro == false) {
    
    if ($oPref->codigo == $usuInsti) {
      $clorcorgao->excluir($oAb->c91_anousudestino);
      if ($clorcorgao->erro_status == 0) {
        $sSqlErro = true;
        $sErro = $clorcorgao->erro_msg;
        //break;
      }
    }
    db_atutermometro($iProcedimento++, $iTotProcedimentos, 'divexclui');
    flush();
  }
  
  if ($sSqlErro == false) {
    
    if ($oPref->codigo == $usuInsti) {
      $clorcelemento->excluir(null, null, "o56_anousu=" . $oAb->c91_anousudestino);
      if ($clorcelemento->erro_status == 0) {
        $sSqlErro = true;
        $sErro = $clorcelemento->erro_msg;
        //break;
      }
    }
    db_atutermometro($iProcedimento++, $iTotProcedimentos, 'divexclui');
    flush();
  }
  
  if ($sSqlErro == true) {
    db_msgbox("Houve um erro ao cancelar a abertura!\\nAdministrador: $sErro");
  } else {
    db_msgbox("Cancelamento Efetuado com Sucesso");
  }
  db_fim_transacao($sSqlErro);
}

if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>