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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_cadferia_classe.php"));
include(modification("classes/db_selecao_classe.php"));
include(modification("classes/db_cfpess_classe.php"));
include(modification("classes/db_rhpessoal_classe.php"));
include(modification("classes/db_rhpesrescisao_classe.php"));
include(modification("classes/db_rhcadastroferiaslote_classe.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("libs/db_libpessoal.php"));
include(modification("libs/db_utils.php"));
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

$clcadferia             = new cl_cadferia;
$clselecao              = new cl_selecao;
$clcfpess               = new cl_cfpess;
$clrhpessoal            = new cl_rhpessoal;
$clrhpesrescisao        = new cl_rhpesrescisao;
$oDaoFeriasLote         = new cl_rhcadastroferiaslote;
$db_opcao               = 1;
$db_botao               = true;
$dbopcao                = false;
$rescindido             = false;
$nexistfunc             = true;
$nexistsele             = false;
$sPeriodosvencidosate   = '';
$iPeriodoaquisitivo     = '';
$sWereListaFuncionarios = "";

if ( isset($_POST['periodosvencidosate']) ) {
  //$sPeriodosvencidosate = implode("-", array_reverse(explode("/",$_POST['periodosvencidosate'])));
  $sPeriodosvencidosate = $_POST['periodosvencidosate'];
}
if ( isset($_POST['periodoaquisitivo']) ) {
  $iPeriodoaquisitivo     = $_POST['periodoaquisitivo'];	
}

$anofolha = db_anofolha();
$mesfolha = db_mesfolha();


if(isset($semdireito)){
  $result_cgm = $clrhpessoal->sql_record($clrhpessoal->sql_query_file($r30_regist));
  if($clrhpessoal->numrows > 0){
    db_fieldsmemory($result_cgm, 0);
  }
  
  //echo $semdireito;
  //die();

  $subpes = $anofolha."/".$mesfolha;

  db_inicio_transacao();
  $matriz1 = array();
  $matriz2 = array();
  $matriz1[1] = "r30_regist";
  $matriz1[2] = "r30_numcgm";
  $matriz1[3] = "r30_perai";
  $matriz1[4] = "r30_ponto";
  $matriz1[5] = "r30_peraf";
  $matriz1[6] = "r30_faltas";
  $matriz1[7] = "r30_per1i";
  $matriz1[8] = "r30_per1f";
  $matriz1[9] = "r30_proc1";
  $matriz1[10] = "r30_tip1";
  $matriz1[11] = "r30_abono";
  $matriz1[12] = "r30_anousu";
  $matriz1[13] = "r30_mesusu";
  $matriz1[14] = "r30_obs";
  $matriz2[1] = $r30_regist;
  $matriz2[2] = $rh01_numcgm;
  $matriz2[3] = db_nulldata($r30_perai_ano."-".$r30_perai_mes."-".$r30_perai_dia);
  $matriz2[4] = $ponto;
  $matriz2[5] = db_nulldata($r30_peraf_ano."-".$r30_peraf_mes."-".$r30_peraf_dia);
  $matriz2[6] = $r30_faltas+0;
  $matriz2[7] = db_nulldata($r30_per1i_ano."-".$r30_per1i_mes."-".$r30_per1i_dia);
  $matriz2[8] = db_nulldata($r30_per1f_ano."-".$r30_per1f_mes."-".$r30_per1f_dia);
  $matriz2[9] = $subpes;
  $matriz2[10] = "00";
  $matriz2[11] = 0;
  $matriz2[12] = db_val( db_substr($subpes,1,4));
  $matriz2[13] = db_val( db_substr($subpes,6,2));
  

  if ($r30_faltas != null && $r30_obs == null) {
  	
  	$matriz2[14] = "Perda de direito por excesso de faltas";
  	
  } else {
  	
  	$matriz2[14] = $r30_obs;
  }
  
  $result_insert = db_insert( "cadferia", $matriz1, $matriz2, false);
  db_fim_transacao();
  $r30_faltas = "";
}



if((isset($r30_regist) && !isset($semdireito)) || isset($enviar_selecao) || (isset($campomatriculas) && 
                                                trim($campomatriculas) != "") || isset($ultmatric) || isset($proximo)) {
  


   $oCompetencia = DBPessoal::getCompetenciaFolha();
   $oInstituicao = InstituicaoRepository::getInstituicaoByCodigo(db_getsession("DB_instit"));

   /**
    * Retornar todas as rubricas especiais relacionadas a férias
    */
   $oDaoRubricaEspecial         = new cl_cfpess();
   $sCampos                     = "r11_feradi";
   $sSqlRubricasEspeciaisFerias = $oDaoRubricaEspecial->sql_query_file($oCompetencia->getAno(), 
                                                                       $oCompetencia->getMes(), 
                                                                       $oInstituicao->getCodigo(), 
                                                                       $sCampos);
   $rsRubricasEspeciaisFerias    = $oDaoRubricaEspecial->sql_record($sSqlRubricasEspeciaisFerias);
   
   if ($rsRubricasEspeciaisFerias && $oDaoRubricaEspecial->numrows > 0) {
     $oDadosRubricasFerias = db_utils::fieldsMemory($rsRubricasEspeciaisFerias, 0);

     if (empty($oDadosRubricasFerias->r11_feradi)) {
       db_msgbox("Rubrica de Adiantamento de Férias não configurada. \n Acesse Procedimentos > Manutenção de Parâmetros > Rubricas Especiais.");
       db_redireciona('pes4_cadferia001.php');
     }
   }
 

  if((isset($enviar_selecao) && !isset($campomatriculas)) || isset($ultmatric) || isset($proximo)) {
    $retorno = 'true';
    if (isset($proximo)) {
      $ultmatric = $r30_regist;
    }
    $result_selecao = $clselecao->sql_record($clselecao->sql_query_file($r44_selec,db_getsession("DB_instit") ));
    if($clselecao->numrows > 0){
      db_fieldsmemory($result_selecao, 0);
      if ($r44_where != ""){
      	$r44_where .= " and ";
      }
      $r44_where .= " rh05_seqpes is null";
      if(isset($ultmatric) && trim($ultmatric) != ""){
        $r44_where .= " and rh01_regist > $ultmatric";
      }

      $sMensagem = "";
      if (isset($filtraferiasprocessadas) && $filtraferiasprocessadas == 2) {
        $sMensagem = "- Não estão sendo considerados servidores que já possuem cadastro de férias em Lote.";
        $r44_where .= " and not exists (select 1 ";  
        $r44_where .= "                  from rhcadastroferiaslote ";    
        $r44_where .= "                 where rh93_mesusu  = {$mesfolha}  ";
        $r44_where .= "                   and rh93_anousu = {$anofolha} ";    
        $r44_where .= "                   and rh93_regist   = rh01_regist ";  
        $r44_where .= "                )";        
      }
      /*
       * nova verificação, conforme a seleção do usuário no filtro periodoaquisitivo.  
       * Essa verificação so deverá ocorrer caso o campo o periodosvencidosate seja diferente de 3.
       */
      if ($iPeriodoaquisitivo != 3 || $iPeriodoaquisitivo != '3') {

      	  /*
      	   * se o periodo de vencimento nao foi especificado, definimos o periodo
      	   * como a data da seção
      	   */
      	if ($sPeriodosvencidosate == null || $sPeriodosvencidosate == '') {
      		$sPeriodosvencidosate = date('Y-m-d', db_getsession('DB_datausu'));
      	}
      	/*
      	 * Para verificar quais funcionários possuem periodos aquisitivos vencidos, utilizamos a 
      	 * função funcionarioferiasvencidas (libs/db_libpessoal.php),onde passamos como parâmetro a data 
      	 * que queremos verificar o período aquisitivo vencido. O retorno dessa função é uma coleção de objetos. 
      	 * A propriedade que interessa para a validação da função, 
      	 * é a propriedade matricula. Criamos uma string, separada por virgula, de todas as matrículas que 
      	 */
      	//$sWhereTeste = "rh01_regist in(1861, 1862, 1863) ";
      	$aFuncionarios      = funcionarioferiasvencidas($sPeriodosvencidosate);
      	$sVirgula           = "";
      	$sListaFuncionarios = "";

        foreach ($aFuncionarios as $sListaIndice => $sListaValor ) {
        	
          if ( count($sListaValor->periodosvencidos) > 0 ) {
          	
            $sListaFuncionarios .=  $sVirgula.$sListaValor -> matricula;
            $sVirgula = ", ";           
          }
    
        }
      	/*
      	 * retornaram na chamada da função. Com essa string criamos uma cláusula where conforme o 
         * valor escolhido no campo  periodosvencidosate.
      	 */
      	switch ($iPeriodoaquisitivo) {
      		
      		case 1 :
      			
      			$sWereListaFuncionarios = " and rh01_regist in ({$sListaFuncionarios}) ";

      		break;

          case 2 :
       	
            $sWereListaFuncionarios = " and rh01_regist not in ({$sListaFuncionarios}) ";
            
          break;      		
      		
      	}
      	
      }
      
      
      /**
       * verificamos se as ferias já estão cadastradas no lote.
       * caso não esteja, incluimos.
       */
      if (isset($ultmatric) && trim($ultmatric) != "") {
        
        $sWhere      = "rh93_mesusu = {$mesfolha} and rh93_anousu = {$anofolha} and rh93_regist = {$ultmatric}";
        $sSqlNoLote  = $oDaoFeriasLote->sql_query_file(null,"rh93_sequencial", null, $sWhere);
        $rsNoLote    = $oDaoFeriasLote->sql_record($sSqlNoLote);
        if ($oDaoFeriasLote->numrows == 0) {

          $oDaoFeriasLote->rh93_anousu     = $anofolha;
          $oDaoFeriasLote->rh93_mesusu     = $mesfolha;
          $oDaoFeriasLote->rh93_processado = "true";
          $oDaoFeriasLote->rh93_regist     = $ultmatric;
          $oDaoFeriasLote->incluir(null); 
          
        }
      }
      include(modification("libs/db_sql.php"));
      $clsql = new cl_gera_sql_folha;
      $clsql->usar_pes = true;
      $clsql->usar_pad = true;
      $clsql->usar_cgm = true;
      $clsql->usar_fun = true;
      $clsql->usar_lot = true;
      $clsql->usar_exe = true;
      $clsql->usar_org = true;
      $clsql->usar_atv = true;
      $clsql->usar_res = true;
      $clsql->usar_fgt = true;
      $clsql->usar_cad = true;
      $clsql->usar_tra = true;
      $clsql->usar_car = true;
      $clsql->usar_afa = true;
      $campomatriculas = "";
      $virgumatriculas = "";
      
      $sql = $clsql->gerador_sql("", $anofolha, $mesfolha, null, null, " rh01_regist ", "rh01_regist", $r44_where.$sWereListaFuncionarios);

      $result = $clsql->sql_record($sql) or die("Erro no SQL: $sql");
      if ($clsql->numrows_exec > 0) {
      	
        for ($i=0; $i<$clsql->numrows_exec; $i++) {
        	
          db_fieldsmemory($result, $i);
          if ($i > 0) {
          	
            $campomatriculas .= $virgumatriculas . $rh01_regist;
            $virgumatriculas  = ",";
          } else {
            $r30_regist = $rh01_regist;
          }
        }
      } else {
      	
        if(!isset($ultmatric)) {
      	  db_msgbox("Aviso:\\nNão foram encontradas matriculas para cadastrar as férias em lote.\\n\\n$sMensagem");
        }

      	db_redireciona("pes4_cadferialote001.php");  
      }

    } else {
    	
      $r30_regist = "";
      $z01_nome   = "";
      $nexistsele = true;
    }
    
  } else if ( isset($campomatriculas) && trim($campomatriculas) != "" ) {
  	
    $retorno = 'true';
    $arr_matriculas = split(",", $campomatriculas);
    $r30_regist = array_shift($arr_matriculas);
    $campomatriculas = implode(",", $arr_matriculas);
  }
  if (isset($r30_regist) && $nexistsele == false) {
  	
    $result_rescisao = $clrhpesrescisao->sql_record($clrhpesrescisao->sql_query_ngeraferias(null,"*","",
                                  "rh02_regist = $r30_regist and rh02_anousu = $anofolha and rh02_mesusu = $mesfolha"));
    if($clrhpesrescisao->numrows > 0){
      $rescindido = true;
    } else {
    	
      $result_admissao = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm($r30_regist,"z01_nome,z01_numcgm,rh01_admiss"));
      
      if ($clrhpessoal->numrows > 0) {
      	
        db_fieldsmemory($result_admissao, 0);
        $nexistfunc = false;
      }

      $result_cadferia = $clcadferia->sql_record(
                                                 $clcadferia->sql_query_file(
                                                                             null,
                                                                             "
                                                                              r30_regist,r30_obs, r30_perai, r30_peraf, 
                                                                              r30_faltas, r30_ndias, r30_abono,
                                                                              r30_proc1, r30_proc2, r30_per1i, 
                                                                              r30_per2i, r30_per1f, r30_per2f, 
                                                                              r30_dias1, r30_dias2,
                                                                              case when r30_proc1 = '".$anofolha."/".$mesfolha."'
                                                                                   then '1' else '0'
                                                                              end as proc1,
                                                                              case when r30_proc2 = '".$anofolha."/".$mesfolha."'
                                                                                   then '1' else '0'
                                                                              end as proc2,
                                                                              case when r30_proc1d = '".$anofolha."/".$mesfolha."'
                                                                                   then '1' else '0'
                                                                              end as proc1d,
                                                                              case when r30_proc2d = '".$anofolha."/".$mesfolha."'
                                                                                   then '1' else '0' 
                                                                              end as proc2d,
                                                                              r30_tipoapuracaomedia,
                                                                              r30_periodolivreinicial,
                                                                              r30_periodolivrefinal
                                                                             ",
                                                                             "r30_regist,r30_perai desc",
                                                                             "
                                                                                  r30_anousu = ".$anofolha." 
                                                                              and r30_mesusu = ".$mesfolha."
                                                                              and r30_regist = ".$r30_regist
                                                                            )
                                                );
      $db_opcao = 1;
      if($clcadferia->numrows > 0){
        db_fieldsmemory($result_cadferia, 0);
        if($proc1 != "1" && $proc2 != "1" && $proc1d != "1" && $proc2d != "1"){
          $nsaldo = $r30_ndias - ($r30_dias1 + $r30_dias2 + $r30_abono);
          if($nsaldo > 0){
            if($r30_abono != 0){
              $dbopcao = true;
              $mtipo = "09";
            }else{
              $dbopcao = true;
            }
          }else{
            $r30_perai = mktime(0,0,0,$r30_peraf_mes,($r30_peraf_dia + 1),$r30_peraf_ano);
            $r30_perai_dia = date("d",$r30_perai);
            $r30_perai_mes = date("m",$r30_perai); 
            $r30_perai_ano = date("Y",$r30_perai);

            $r30_peraf = mktime(0,0,0,$r30_perai_mes,($r30_perai_dia - 1),($r30_perai_ano + 1));
            $r30_peraf_dia = date("d",$r30_peraf);
            $r30_peraf_mes = date("m",$r30_peraf);
            $r30_peraf_ano = date("Y",$r30_peraf);
            unset($r30_faltas, $r30_ndias, $r30_abono, $r30_proc1, $r30_proc2, $r30_per1i, $r30_per2i, $r30_per1f, $r30_per2f, $nsaldo, $r30_dias1, $r30_dias2);
          }
        }
      }else{
        if(isset($rh01_admiss)){
          $r30_perai = $rh01_admiss;
          $r30_perai_dia = $rh01_admiss_dia;
          $r30_perai_mes = $rh01_admiss_mes; 
          $r30_perai_ano = $rh01_admiss_ano;

          $r30_peraf = mktime(0,0,0,$r30_perai_mes,($r30_perai_dia - 1),($r30_perai_ano + 1));
          $r30_peraf_dia = date("d",$r30_peraf);
          $r30_peraf_mes = date("m",$r30_peraf);
          $r30_peraf_ano = date("Y",$r30_peraf);
        }
      }
      $diferenca = mktime(0,0,0,$r30_peraf_mes,$r30_peraf_dia,$r30_peraf_ano);
      $diferenca-= mktime(0,0,0,$r30_perai_mes,$r30_perai_dia,$r30_perai_ano);
      $diferenca = intval($diferenca/2592000); // 2592000 se refere a 60 * 60 * 24 * 30 // 60 segundos - 60 minutos - 24 horas - 30 dias
    }
  }
  if(isset($r44_selec) && trim($r44_selec) != ""){
    
    $r30_per2i     = $perini_ano . "-" . $perini_mes . "-" . $perini_dia;
    $r30_per2f     = $perfim_ano . "-" . $perfim_mes . "-" . $perfim_dia;
    $r30_per2i_dia = $perini_dia;
    $r30_per2i_mes = $perini_mes;
    $r30_per2i_ano = $perini_ano;
    $r30_per2f_dia = $perfim_dia;
    $r30_per2f_mes = $perfim_mes;
    $r30_per2f_ano = $perfim_ano;
    $r30_per1i     = $perini_ano . "-" . $perini_mes . "-" . $perini_dia;
    $r30_per1f     = $perfim_ano . "-" . $perfim_mes . "-" . $perfim_dia;
    $r30_per1i_dia = $perini_dia;
    $r30_per1i_mes = $perini_mes;
    $r30_per1i_ano = $perini_ano;
    $r30_per1f_dia = $perfim_dia;
    $r30_per1f_mes = $perfim_mes;
    $r30_per1f_ano = $perfim_ano;
    $mtipo         = $tipofer;
    $ponto         = $pontofer;
    $paga_13       = $pagafer13;
    $anopagto      = $preanopagto;
    $mespagto      = $premespagto;
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
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScrpit" type="text/javascript" src="scripts/classes/DBViewFormularioFolha/ValidarFolhaPagamento.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
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
        include(modification("forms/db_frmcadferia001.php"));
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
if(isset($semdireito)){
  if( ($result_insert == true && trim($retorno) == "") || ($result_insert == true && isset($campomatriculas) )){
    db_msgbox("Inclusão efetuada com sucesso.");
    echo "<script>location.href = 'pes4_cadferia001.php';</script>";
  }else if($result_insert == true){
    db_msgbox("Erro ao incluir no cadferia. Contate o suporte.");
  }
}

if(isset($r30_regist)){
  if($nexistsele == true){
    db_msgbox("Seleção ".$r44_selec." não encontrada. Verifique.");
    echo "<script>location.href = 'pes4_cadferialote001.php';</script>";
  }else if($nexistfunc == true && !isset($semdireito)){
    db_msgbox("Funcionário ".$r30_regist." não encontrado. Verifique.");
    echo "<script>location.href = 'pes4_cadferia".((isset($retorno) && trim($retorno) != "") ? "lote" : "")."001.php';</script>";
  }else if(isset($proc1) || isset($proc2) || isset($proc1d) || isset($proc2d)){
    if($proc1 == "1"){
      db_msgbox("Funcionário com férias já cadastradas para este ano / mês.");
      echo "
            <script>
              if(document.form1.proximo){
                document.form1.proximo.click();
              }else{
                document.form1.voltar.click();
              }
            </script>
           ";
    }else if($proc2 == "1"){
      db_msgbox("Funcionário com saldo de férias para este ano / mês.");
      echo "
            <script>
              if(document.form1.proximo){
                document.form1.proximo.click();
              }else{
                document.form1.voltar.click();
              }
            </script>
           ";
    }else if($proc1d == "1"){
      db_msgbox("Funcionário com diferença de férias para este ano / mês.");
      echo "
            <script>
              if(document.form1.proximo){
                document.form1.proximo.click();
              }else{
                document.form1.voltar.click();
              }
            </script>
           ";
    }else if($proc2d == "1"){
      db_msgbox("Funcionário com diferença de saldo de férias já cadastradas para este ano / mês.");
      echo "
            <script>
              if(document.form1.proximo){
                document.form1.proximo.click();
              }else{
                document.form1.voltar.click();
              }
            </script>
           ";
    }
  }
}

if($dbopcao == true){
  if(isset($mtipo)){
    echo "
          <script>
            js_tabulacaoforms('form1','r30_per2i_dia',true,1,'r30_per2i_dia',true);
          </script>
         ";
  }else{
    echo "
          <script>
            js_tabulacaoforms('form1','saldo',true,1,'saldo',true);
          </script>
         ";
  }
}else{
  echo "
        <script>
          js_tabulacaoforms('form1','r30_perai_dia',true,1,'r30_perai_dia',true);
        </script>
       ";
}

if($rescindido == true){
  db_msgbox("Funcionário rescindiu contrato.");
  echo "
        <script>
          if(document.form1.proximo){
            document.form1.proximo.click();
          }else{
            document.form1.voltar.click();
          }
        </script>
       ";
}
?>