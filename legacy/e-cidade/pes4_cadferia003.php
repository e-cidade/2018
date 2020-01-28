<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_libpessoal.php");
include("classes/db_cadferia_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clcadferia = new cl_cadferia;
$db_opcao = 1;
$db_botao = true;
if(isset($excluir)){

  try {
    
    $erro_msg      = "";
    $lFolhaFechada = false;
    
    if(DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
      
        $iMatricula   = $r30_regist;
        $oServidor    = new Servidor($iMatricula);
        $oCompetencia = DBPessoal::getCompetenciaFolha();
        $oInstituicao = InstituicaoRepository::getInstituicaoByCodigo(db_getsession("DB_instit"));
        
        
        /**
         * Retornar todas as rubricas especiais relacionadas a férias
         */
        $oDaoRubricaEspecial          = new cl_cfpess();
        $sCampos                      = "r11_ferias, r11_fer13, r11_ferabo, r11_fer13a, ";
        $sCampos                     .= "r11_feradi, r11_fadiab, r11_ferant, r11_feabot ";
        $sSqlRubricasEspeciaisFerias  = $oDaoRubricaEspecial->sql_query_file($oCompetencia->getAno(), 
                                                                             $oCompetencia->getMes(), 
                                                                             $oInstituicao->getCodigo(), 
                                                                             $sCampos);
        $rsRubricasEspeciaisFerias    = $oDaoRubricaEspecial->sql_record($sSqlRubricasEspeciaisFerias);
        
        if ($rsRubricasEspeciaisFerias && $oDaoRubricaEspecial->numrows > 0) {
          
          $oDadosRubricasFerias = db_utils::fieldsMemory($rsRubricasEspeciaisFerias, 0);
          
          $aRubricasFerias   = array();
          $aRubricasFerias[] = $oDadosRubricasFerias->r11_ferias;
          $aRubricasFerias[] = $oDadosRubricasFerias->r11_fer13;
          $aRubricasFerias[] = $oDadosRubricasFerias->r11_ferabo;
          $aRubricasFerias[] = $oDadosRubricasFerias->r11_fer13a;
          $aRubricasFerias[] = $oDadosRubricasFerias->r11_feradi;
          $aRubricasFerias[] = $oDadosRubricasFerias->r11_fadiab;
          $aRubricasFerias[] = $oDadosRubricasFerias->r11_ferant;
          $aRubricasFerias[] = $oDadosRubricasFerias->r11_feabot;
        }
        
        /**
         * Retorna todas as folhas complementares fechadas do servidor da competência atual
         */
        $aFolhasPagamento = FolhaPagamento::getFolhaServidor($oServidor,
                                                             $oCompetencia, 
                                                             $oInstituicao ,
                                                             FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR, 
                                                             false);
        
        /**
         * Verifica se as folhas complementares fechadas possuem alguma rubrica especial
         */
        foreach ($aFolhasPagamento as $oFolha) {
          $lFolhaFechada = FolhaPagamento::hasRubricasFolha($oFolha, $aRubricasFerias);
        }
    }
    
    /**
     * Não vai excluir aS férias do servidor se a folha complementar estiver fechada
     */
    if (!$lFolhaFechada) {
      
      $subpes = db_anofolha()."/".db_mesfolha();
      $subant = $subpes;
    
      db_inicio_transacao();
    
      db_selectmax("cfpess", "select * from cfpess ".bb_condicaosubpes("r11_"));
    
      $condicaoaux  = " and rh02_regist = ".db_sqlformat($r30_regist);
      db_selectmax("pessoal", "select rh01_regist as r01_regist,
                                      rh01_numcgm as r01_numcgm,
                                      rh02_tbprev as r01_tbprev
                               from rhpessoalmov 
                               inner join rhpessoal on rh01_regist = rh02_regist
                              ".bb_condicaosubpes("rh02_").$condicaoaux);
    
      $condicaoaux  = " and r30_regist = ".db_sqlformat($r30_regist);
      $condicaoaux .= " and r30_perai = '".$r30_perai_ano."-".$r30_perai_mes."-".$r30_perai_dia."'";
      db_selectmax("cadferia", "select * from cadferia ".bb_condicaosubpes("r30_").$condicaoaux." order by r30_perai desc");
      $periodo_aquisitivo = $cadferia[0]["r30_perai"];
      if($cadferia[0]["r30_proc2"] >= $subpes){
    
        $subpes = $cadferia[0]["r30_proc2"];
        $matriz1 = array();
        $matriz2 = array();
        $matriz1[1] = "r30_per2i";
        $matriz1[2] = "r30_per2f";
        $matriz1[3] = "r30_proc2";
        $matriz1[4] = "r30_dias2";
        $matriz1[5] = "r30_psal2";
        $matriz1[6] = "r30_abono";
    
        $matriz2[1] = db_nulldata("");
        $matriz2[2] = db_nulldata("");
        $matriz2[3] = bb_space(7);
        $matriz2[4] = 0;
        $matriz2[5] = "f";
        if($cadferia[0]["r30_tip2"] == "10"){
          $matriz2[6] = 0;
        }else{
          $matriz2[6] = $cadferia[0]["r30_abono"];
        }
        db_update("cadferia", $matriz1, $matriz2, bb_condicaosubpes("r30_").$condicaoaux);
      }else if($cadferia[0]["r30_proc1"] >= $subpes){
        db_delete("cadferia", bb_condicaosubpes("r30_").$condicaoaux);
      }
    
      $condicaoaux  = " where r40_regist = ".db_sqlformat($pessoal[0]["r01_regist"]);
      $condicaoaux .= " and r40_proc = ".db_sqlformat($subpes);
      db_delete("fgtsfer", $condicaoaux);
    
      $condicaoaux = " and r29_regist = ".db_sqlformat($r30_regist);
      db_delete("pontofe", bb_condicaosubpes("r29_").$condicaoaux);
    
      $subpes = $subant;
      $condicaoaux = " and r31_regist = ".db_sqlformat($r30_regist);
      db_delete("gerffer", bb_condicaosubpes("r31_").$condicaoaux);
    
      $erro_msg = "Usuário, alguns procedimentos não podem ser feitos automaticamente \\n
                   pelo sistema, portanto proceda da seguinte maneira:\\n\\n
                   - Reinicialize o ponto de salário para este funcionário.";
      if(strtolower($cadferia[0]["r30_ponto"]) == "c"){
        $condicaoaux = " and r47_regist = ".db_sqlformat($r30_regist);
        db_delete("pontocom", bb_condicaosubpes("r47_").$condicaoaux);
    
        $condicaoaux = " and r48_regist = ".db_sqlformat($r30_regist);
        db_delete("gerfcom", bb_condicaosubpes("r48_").$condicaoaux);
    
        $erro_msg.= "\\n- Lance e recalcule sua folha complementar.";
      }else{
        $condicaoaux = " and r10_regist = ".db_sqlformat($pessoal[0]["r01_regist"]);
        if(db_selectmax("pontofs", "select * from pontofs ".bb_condicaosubpes("r10_").$condicaoaux)){
          $condicaoaux .= " and r10_rubric = ".db_sqlformat($cfpess[0]["r11_ferias"]);
          $condicaoaux .= " and r10_rubric = ".db_sqlformat($cfpess[0]["r11_fer13o"]);
          db_delete("pontofs", bb_condicaosubpes("r10_").$condicaoaux);
        }
    
        $condicaoaux = " and r14_regist = ".db_sqlformat($pessoal[0]["r01_regist"]);
        db_delete("gerfsal", bb_condicaosubpes("r14_").$condicaoaux);
        $erro_msg.= "\\n- Recalcule sua folha de salário.";
      }
    
      $condicaoaux  = " and r60_numcgm = ".db_sqlformat($pessoal[0]["r01_numcgm"]);
      $condicaoaux .= " and r60_tbprev = ".db_sqlformat($pessoal[0]["r01_tbprev"]);
      $condicaoaux .= " and r60_rubric = ".db_sqlformat("R977");
      $condicaoaux .= " and r60_regist = ".db_sqlformat($pessoal[0]["r01_regist"]);
      if(db_selectmax("previden", "select * from previden ".bb_condicaosubpes("r60_").$condicaoaux)){
        db_delete("previden", bb_condicaosubpes("r60_").$condicaoaux);
        $erro_msg.= "\\n\\OBS.: Este funcionário tem mais de um vínculo no cgm.";
        $erro_msg.= "\\nRecalcule todas as matrículas do seguinte cgm: ".$pessoal[0]["r01_numcgm"];
      }
      global $pensao;
          $condicaoaux  = " and  rh05_recis is null ";
          $condicaoaux .= " and r52_regist = ".db_sqlformat($pessoal[0]["r01_regist"]);
          $condicaoaux .= " order by r52_regist ";
          $sql = "select distinct(r52_regist+r52_numcgm), 
                                         pensao.*, 
                                         rh01_regist as r01_regist,
                                         trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac 
                                  from pensao
                                      inner join rhpessoalmov on pensao.r52_anousu         = rhpessoalmov.rh02_anousu 
                                                             and pensao.r52_mesusu         = rhpessoalmov.rh02_mesusu 
                                                             and pensao.r52_regist         = rhpessoalmov.rh02_regist
                                      left  join pontofe      on pontofe.r29_anousu        = rhpessoalmov.rh02_anousu 
                                                             and pontofe.r29_mesusu        = rhpessoalmov.rh02_mesusu 
                                                             and pontofe.r29_regist        = rhpessoalmov.rh02_regist
                                      inner join rhpessoal    on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist
                                      inner join rhlota       on rhlota.r70_codigo         = rhpessoalmov.rh02_lota
                                                             and rhlota.r70_instit         = rhpessoalmov.rh02_instit  
                                      inner join cgm          on cgm.z01_numcgm            = rhpessoal.rh01_numcgm
                                      left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
                                      ".bb_condicaosubpes("r52_" ).$condicaoaux ;
//echo "<BR> ".count($pensao)." $sql";exit;
      db_selectmax("pensao", $sql);
      for ($Ipensao=0; $Ipensao<count($pensao); $Ipensao++) {
          $matriz1 = array();
          $matriz2 = array();
          $condicaoaux  = " and r52_regist = ".db_sqlformat($pensao[$Ipensao]["r52_regist"]);
          $condicaoaux .= " and r52_numcgm = ".db_sqlformat($pensao[$Ipensao]["r52_numcgm"]);
          
          $matriz1[1] = "r52_valor";
          $matriz1[2] = "r52_valfer";
          $matriz2[1] = 0;
          $matriz2[2] = 0;
//echo "<BR> ".bb_condicaosubpes("r52_").$condicaoaux ;
          $retornar = db_update("pensao", $matriz1, $matriz2, bb_condicaosubpes("r52_").$condicaoaux );
      }
      db_fim_transacao();
    } else {
      
      $iNumeroFolha = $aFolhasPagamento[0]->getNumero();
      $erro_msg     = "Não foi possível excluir as férias do servidor {$iMatricula}, porque a complementar {$iNumeroFolha} encontra-se fechada.";
    }  
  } catch (Exception $ex) {
    $erro_msg = $ex->getMessage();
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
      <?
      include("forms/db_frmexcferia.php");
      ?>
      </center>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($excluir)) {
  
  $sMsgNotificacao = "Processamento concluído.\\nNão esqueça de inicializar o ponto do funcionário.\\nTomar cuidado com as rubricas variáveis informadas no ponto.";
  if(trim($erro_msg) != ""){
    $sMsgNotificacao = $erro_msg;
  }
  db_msgbox($sMsgNotificacao);  
  
  echo "<script>location.href = 'pes4_cadferia003.php';</script>";
}
?>
<script>
js_tabulacaoforms("form1","r30_regist",true,1,"r30_regist",true);
</script>
