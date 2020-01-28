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


require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("classes/db_conaberturaexe_classe.php");
require_once ("classes/db_db_config_classe.php");
require_once ("classes/db_conplanoconta_classe.php");
require_once ("classes/db_conplano_classe.php");
require_once ("classes/db_orcelemento_classe.php");
require_once ("classes/db_orcorgao_classe.php");
require_once ("classes/db_orcunidade_classe.php");
require_once ("classes/db_orcprograma_classe.php");
require_once ("classes/db_orcprojativ_classe.php");
require_once ("classes/db_orcfontes_classe.php");
require_once ("classes/db_orcparametro_classe.php");
require_once ("classes/db_orcfontesdes_classe.php");
require_once ("classes/db_conplanoreduz_classe.php");
require_once ("dbforms/db_funcoes.php");

( string ) $sMsg = null;

$p = db_utils::postMemory($_POST);
$g = db_utils::postMemory($_GET);

$clconaberturaexe = new cl_conaberturaexe();
$clconplano 			= new cl_conplano();
$clorcelemento 		= new cl_orcelemento();
$clorcorgao 			= new cl_orcorgao();
$clorcunidade 		= new cl_orcunidade();
$clorcprograma 		= new cl_orcprograma();
$clorcprojativ 		= new cl_orcprojativ();
$clorcfontes      = new cl_orcfontes();
$clorcfontesdes   = new cl_orcfontesdes();
$clorcparametro   = new cl_orcparametro();
$clconplanoreduz  = new cl_conplanoreduz();
$clconplanoconta  = new cl_conplanoconta();
$cldb_config      = new cl_db_config();

$clconaberturaexe->rotulo->label();

$clrotulo         = new rotulocampo();
$clrotulo->label("nomeinst");
$clrotulo->label("nome");

$db_opcao = 1;
$db_botao = true;

$rs1 = $clconaberturaexe->sql_record($clconaberturaexe->sql_query(null, 
		                                                              '*', 
		                                                              '', 
		                                                              'c91_tipo = 1 and c91_anousudestino= '.(db_getsession("DB_anousu") + 1)));
if ($clconaberturaexe->numrows == 0) {
  
  $rsCfg = $cldb_config->sql_record($cldb_config->sql_query_file(db_getSession("DB_instit")));
  $dCfg = db_utils::fieldsMemory($rsCfg, 0);
  if ($dCfg->prefeitura == 'f') {
    
    db_msgbox('Primeira abertura de exercício deve ser feito na instituição prefeitura!\\n Verifique');
    $db_botao = false;
  
  } else {
  	
    $rs = $clconaberturaexe->sql_record($clconaberturaexe->sql_query(null, 
    		                                                             '*', 
    		                                                             '', 
    		                                                             'c91_tipo = 1 and c91_anousudestino= '.(db_getsession("DB_anousu") + 1)));
    
    if ($clconaberturaexe->numrows > 0) {
      
      $oConplano = db_utils::fieldsMemory($rs, 0);
      //$c91_anousudestino = $oConplano->c91_anousu + 1;
    
    } else {
      
      $rsc = $clconplano->sql_record($clconplano->sql_query(null, null, 'distinct c60_anousu', 'c60_anousu desc limit 1'));
      if ($clconplano->numrows > 0) {
        $oConplano = db_utils::fieldsMemory($rsc, 0);
        //$c91_anousudestino = $oConplano->c60_anousu + 1;
      
      }
    }
    
  }

} else {
  $rs2 = $clconaberturaexe->sql_record($clconaberturaexe->sql_query(null, '*', '', 'c91_tipo = 1 
		                                                                   and c91_anousudestino =' . (db_getSession("DB_anousu") + 1) . ' 
		                                                                   and c91_situacao in (1,2)
		                                                                   and c91_instit        =' . db_getSession("DB_instit")));
  if ($clconaberturaexe->numrows > 0) {
    
    $oAbert = db_utils::fieldsMemory($rs2, 0);
    if ($oAbert->c91_situacao == 1 or $oAbert->c91_situacao == 2) {
      
      $sMsg = 'Já existe uma abertura de exercício para ' . $oAbert->c91_anousudestino . '! Verifique.';
      $db_botao = false;
    }
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
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<form name="form1" method="post" action="">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<table>
  <td>
  <fieldset><legend><b>Abrir Exercício Contábil</b></legend>
  <table>
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
                db_input('c91_anousudestino', 5, $Ic91_anousudestino, true, 'text', 1, "")?>
								</td>
    </tr>
  </table>
  </fieldset>
  </td>
  </tr>
</table>
<input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>"
  id='db_opcao' onclick='return js_geraImp()' type="submit" id="db_opcao"
  value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
  <?=($db_botao == false ? "disabled" : "")?>>
</td>
</tr>
</table>

</form>
</center>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>

<?
if ($sMsg != null && ! isset($g->sMostraMsg)) {
  echo "<center><h2>$sMsg</h2></center>";
}

?>
</body>
</html>
<script>
//js_tabulacaoforms("form1","c97_instit",true,1,"c97_instit",true);
function js_geraImp(){

 return (confirm('Confirma a abertura  do exercício até o exercicio de '+$F('c91_anousudestino')+"?"));
}
</script>
<?

if (isset($p->incluir)) {
  
  $sSqlErro = false;
  $sErro    = "";
  
  
  try {
  	
  
  if ($p->c91_anousudestino <= $p->c91_anousuorigem) {
    
    $sSqlErro = true;
    $sErro    = "Ano de destino menor que o ano de origem.";
    
  }
  
  echo "<center>";
  echo "<script>document.getElementById('db_opcao').disabled=true;</script>";
  db_inicio_transacao();
  
  $iAnoFinal   = $p->c91_anousudestino;
  $iAnoInicial = $p->c91_anousuorigem+1;
  echo "<div id='lblimp' style='font-weight:bold'></div>";
  db_criatermometro("divimp2", 'concluido...', 'blue', 1, null);
  flush();
  for ($iAno = $iAnoInicial; $iAno <= $iAnoFinal; $iAno++) {
    
    $p->c91_anousudestino = $iAno;
    $clconaberturaexe->c91_id_usuario    = db_getSession("DB_id_usuario");
    $clconaberturaexe->c91_instit        = db_getSession("DB_instit");
    $clconaberturaexe->c91_hora          = db_hora();
    $clconaberturaexe->c91_data          = date("Ymd", db_getsession("DB_datausu"));
    $clconaberturaexe->c91_anousuorigem  = $p->c91_anousuorigem;
    $clconaberturaexe->c91_anousudestino = $p->c91_anousudestino;
    $clconaberturaexe->c91_situacao      = 2;
    $clconaberturaexe->c91_tipo          = 1;
    $clconaberturaexe->c91_ppa           = '0';
    $clconaberturaexe->c91_origem        = '0';
    $clconaberturaexe->incluir(null);
    if ($clconaberturaexe->erro_status = 0) {
      
    	$sErro  = " [1] Erro ao incluir dados em conaberturaexe. <br>";
    	$sErro .= " Erro: {$clconaberturaexe->erro_msg}";
      throw new Exception($sErro);
    }
  
    $sql1 = "select 1 from conplano where c60_anousu = " . $clconaberturaexe->c91_anousudestino;
    $rs = db_query($sql1);
    if (pg_num_rows($rs) == 0) {
        
      $rsPl = $clconplano->sql_record($clconplano->sql_query_file(null, null, "*", "", "c60_anousu=" . $clconaberturaexe->c91_anousuorigem));
      if ($clconplano->numrows > 0) {
          
        echo "<script>document.getElementById('lblimp').innerHTML = 'Importando Plano de Contas - {$iAno}'</script>";
        flush();
        $iNumRows = $clconplano->numrows;
        for($i = 0; $i < $iNumRows; $i ++) {
            
          db_atutermometro($i, $iNumRows, 'divimp2');
          flush();
          $oConp = db_utils::fieldsMemory($rsPl, $i);
          $clconplano->c60_codcon                  = $oConp->c60_codcon;
          $clconplano->c60_anousu                  = $clconaberturaexe->c91_anousudestino;
          $clconplano->c60_estrut                  = $oConp->c60_estrut;
          $clconplano->c60_descr                   = addslashes($oConp->c60_descr);
          $clconplano->c60_finali                  = addslashes($oConp->c60_finali);
          $clconplano->c60_codsis                  = $oConp->c60_codsis;
          $clconplano->c60_codcla                  = $oConp->c60_codcla;
          $clconplano->c60_consistemaconta         = $oConp->c60_consistemaconta;
          $clconplano->c60_identificadorfinanceiro = $oConp->c60_identificadorfinanceiro;
          $clconplano->c60_naturezasaldo           = $oConp->c60_naturezasaldo;
          $clconplano->c60_funcao                  = addslashes($oConp->c60_funcao == "" ? "" : $oConp->c60_funcao);
          $clconplano->incluir($oConp->c60_codcon, $clconplano->c60_anousu);
          
          if ($clconplano->erro_status == "0") {
            
            $sErro  = " [2] Erro ao incluir dados em conplano.<br>"; 
            $sErro .= "Erro: {$clconaberturaexe->erro_msg}";
            throw new Exception($sErro);
            
          }
          
        }
        
        db_atutermometro($i, $iNumRows, 'divimp2');
        flush();
      }
      
    }
      
    /**
     * Duplica registros de complanoOrcamentopara o exercercio destino.
     */
    if (USE_PCASP) {
    	
      $iNumRows = 0;
      $oDaoPlanoOrcamentario = db_utils::getDao("conplanoorcamento");
      $sSqlPlanoOrcamentario = $oDaoPlanoOrcamentario->sql_query_file(null, 
                                                                      null, 
      																																"*", 
                                                                      "", 
                                                                      "c60_anousu={$clconaberturaexe->c91_anousudestino}");
      $rsPlanoOrcamentario  = $oDaoPlanoOrcamentario->sql_record($sSqlPlanoOrcamentario);
      
      if ($oDaoPlanoOrcamentario->numrows == 0) {
        
        $sSqlPlanoOrcamentario = null;
        $rsPlanoOrcamentario   = null;
        $sSqlPlanoOrcamentario = $oDaoPlanoOrcamentario->sql_query_file(null, 
                                                                        null, 
        																																"*", 
                                                                        "", 
                                                                        "c60_anousu={$clconaberturaexe->c91_anousuorigem}");
        $rsPlanoOrcamentario = $oDaoPlanoOrcamentario->sql_record($sSqlPlanoOrcamentario);
        $iNumRows            = $oDaoPlanoOrcamentario->numrows;
        if ($iNumRows > 0) {
          
          for ($i = 0; $i < $iNumRows; $i++) {
            
            db_atutermometro($i, $iNumRows, 'divimp2');
            flush();
            $oPlanoOrcarmento = db_utils::fieldsMemory($rsPlanoOrcamentario ,$i);
            $oDaoPlanoOrcamentario->c60_codcon                  = $oPlanoOrcarmento->c60_codcon;
            $oDaoPlanoOrcamentario->c60_anousu                  = $clconaberturaexe->c91_anousudestino;
            $oDaoPlanoOrcamentario->c60_estrut                  = $oPlanoOrcarmento->c60_estrut;
            $oDaoPlanoOrcamentario->c60_descr                   = addslashes($oPlanoOrcarmento->c60_descr);
            $oDaoPlanoOrcamentario->c60_finali                  = addslashes($oPlanoOrcarmento->c60_finali);
            $oDaoPlanoOrcamentario->c60_codsis                  = $oPlanoOrcarmento->c60_codsis;
            $oDaoPlanoOrcamentario->c60_codcla                  = $oPlanoOrcarmento->c60_codcla;
            $oDaoPlanoOrcamentario->c60_consistemaconta         = $oPlanoOrcarmento->c60_consistemaconta;
            $oDaoPlanoOrcamentario->c60_identificadorfinanceiro = $oPlanoOrcarmento->c60_identificadorfinanceiro;
            $oDaoPlanoOrcamentario->c60_naturezasaldo           = $oPlanoOrcarmento->c60_naturezasaldo;
            $oDaoPlanoOrcamentario->c60_funcao                  = addslashes($oPlanoOrcarmento->c60_funcao == "" ? "" : $oPlanoOrcarmento->c60_funcao);
            $oDaoPlanoOrcamentario->incluir($oPlanoOrcarmento->c60_codcon, $oDaoPlanoOrcamentario->c60_anousu);
  
            if ($oDaoPlanoOrcamentario->erro_status == "0") {
            
               $sErro  = " [3] Erro ao incluir dados em conplanoorcamento.<br>"; 
               $sErro .= "Erro: {$oDaoPlanoOrcamentario->erro_msg}";
               throw new Exception($sErro);
            }
            
          }
          
        }
        
        db_atutermometro($i, $iNumRows, 'divimp2');
        
      }
      
      flush();
      
    }
      
    echo "<script>document.getElementById('lblimp').innerHTML = 'Importando Contas Analíticas - {$iAno}'</script>";
    flush();
    $sSqlReduz  = "select conplanoreduz.* ";
    $sSqlReduz .= "  from conplanoreduz  ";
    $sSqlReduz .= "       left join conplanoreduz reduzexercicio on reduzexercicio.c61_reduz  = conplanoreduz.c61_reduz";
    $sSqlReduz .= "                                             and reduzexercicio.c61_instit = conplanoreduz.c61_instit";
    $sSqlReduz .= "                                             and reduzexercicio.c61_anousu = {$clconaberturaexe->c91_anousudestino}";
    $sSqlReduz .= " where  conplanoreduz.c61_anousu = {$clconaberturaexe->c91_anousuorigem}";
    $sSqlReduz .= "   and  conplanoreduz.c61_instit = {$clconaberturaexe->c91_instit}";
    $sSqlReduz .= "   and  reduzexercicio.c61_anousu is null";
    $rsRed = $clconplanoreduz->sql_record($sSqlReduz);
    if ($clconplanoreduz->numrows > 0) {
      
      ( int ) $iNumRows = $clconplanoreduz->numrows;
      for($i = 0; $i < $iNumRows; $i ++) {
        
        db_atutermometro($i, $iNumRows, 'divimp2');
        
        $oConr = db_utils::fieldsMemory($rsRed, $i);
        $clconplanoreduz->c61_codcon        = $oConr->c61_codcon;
        $clconplanoreduz->c61_anousu        = $clconaberturaexe->c91_anousudestino;
        $clconplanoreduz->c61_reduz         = $oConr->c61_reduz;
        $clconplanoreduz->c61_instit        = $oConr->c61_instit;
        $clconplanoreduz->c61_codigo        = $oConr->c61_codigo;
        $clconplanoreduz->c61_contrapartida = $oConr->c61_contrapartida;
        $clconplanoreduz->incluir($oConr->c61_reduz, $clconaberturaexe->c91_anousudestino);
        
        if ($clconplanoreduz->erro_status == "0") {
          
          $sErro  = " [4] Erro ao incluir dados em conplanoreduz.<br>";
          $sErro .= "Erro: {$clconplanoreduz->erro_msg}";
          throw new Exception($sErro);
        
        }
        
      }
    
    }

    /**
     * Duplica registros de complanoOrcamentoAnalitica para o exercercio destino.
     */
    if (USE_PCASP) {
      
      $iNumRows = 0;
      $oDaoAnalitico = db_utils::getDao("conplanoorcamentoanalitica");
      $sSqlAnalitico = $oDaoAnalitico->sql_query_analiticaProximoExercicio ($clconaberturaexe->c91_anousuorigem,
                                                                            $clconaberturaexe->c91_anousudestino,
                                                                            $clconaberturaexe->c91_instit);
      $rsAnalitico   = $oDaoAnalitico->sql_record($sSqlAnalitico);
      $iNumRows      = $oDaoAnalitico->numrows;
      
      if ($iNumRows > 0) {
        
        for($i = 0; $i < $iNumRows; $i ++) {
        
          db_atutermometro($i, $iNumRows, 'divimp2');
        
          $oAnalitico                       = db_utils::fieldsMemory($rsAnalitico, $i);
          $oDaoAnalitico->c61_codcon        = $oAnalitico->c61_codcon;
          $oDaoAnalitico->c61_anousu        = $clconaberturaexe->c91_anousudestino;
          $oDaoAnalitico->c61_reduz         = $oAnalitico->c61_reduz;
          $oDaoAnalitico->c61_instit        = $oAnalitico->c61_instit;
          $oDaoAnalitico->c61_codigo        = $oAnalitico->c61_codigo;
          $oDaoAnalitico->c61_contrapartida = $oAnalitico->c61_contrapartida;
          $oDaoAnalitico->incluir($oAnalitico->c61_reduz, $clconaberturaexe->c91_anousudestino);
        
          if ($oDaoAnalitico->erro_status == "0") {
        
            $sErro  = " [5] Erro ao incluir dados em conplanoorcamentoanalitica.<br>";
            $sErro .= "Erro: {$oDaoAnalitico->erro_msg}";
            throw new Exception($sErro);
            
          }
          
        }
        
      }
      
    }
    
    /*
     * Registros da Conplanoexe.
     */
      	
    echo "<script>document.getElementById('lblimp').innerHTML = 'Importando ConplanoExe - {$iAno}'</script>";
    flush();
    
    $oDaoConPlanoExe = db_utils::getDao("conplanoexe");
    $sSqlConplanoExe = "select * 
                          from conplanoexe 
                         inner join conplanoreduz  on conplanoreduz.c61_reduz  = conplanoexe.c62_reduz  
                                                  and conplanoreduz.c61_anousu = $clconaberturaexe->c91_anousudestino                                 
                         where c62_anousu = ".$clconaberturaexe->c91_anousuorigem." 
                           and not exists ( select 1 
                                              from conplanoexe as x 
                                             where x.c62_reduz  = conplanoexe.c62_reduz  
                                               and x.c62_codrec = conplanoexe.c62_codrec
                                               and x.c62_anousu = $clconaberturaexe->c91_anousudestino)";
    $rsConPlanoExe   = $oDaoConPlanoExe->sql_record($sSqlConplanoExe);
    $iNumRows = $oDaoConPlanoExe->numrows;                                                        
    if ($oDaoConPlanoExe->numrows > 0) {
    
    	for ($i = 0; $i < $iNumRows; $i++) {
    
        db_atutermometro($i, $iNumRows, 'divimp2');
        $oConPlanoExe = db_utils::fieldsMemory($rsConPlanoExe, $i);
        $oDaoConPlanoExe->c62_anousu = $clconaberturaexe->c91_anousudestino;
        $oDaoConPlanoExe->c62_reduz  = $oConPlanoExe->c62_reduz;
        $oDaoConPlanoExe->c62_codrec = $oConPlanoExe->c62_codrec;
        $oDaoConPlanoExe->c62_vlrcre = $oConPlanoExe->c62_vlrcre;
        $oDaoConPlanoExe->c62_vlrdeb = $oConPlanoExe->c62_vlrdeb;
        $oDaoConPlanoExe->incluir($clconaberturaexe->c91_anousudestino,$oConPlanoExe->c62_reduz);
        if ($oDaoConPlanoExe->erro_status == "0") {
        	
           $sErro  = " [6] Erro ao incluir dados em conplanoexe. <br>";
    	     $sErro .= " Erro: {$oDaoConPlanoExe->erro_msg}";
           throw new Exception($sErro);
        }
        
      }
      
    }
    
    echo "<script>document.getElementById('lblimp').innerHTML = 'Importando Contas Bancárias - {$iAno}'</script>";
    flush();
    
    $sql1 = "select 1 from conplanoconta where c63_anousu = " . $clconaberturaexe->c91_anousudestino;
    $rs = db_query($sql1);
    if (pg_num_rows($rs) == 0) {
      $rsCon = $clconplanoconta->sql_record($clconplanoconta->sql_query(null, 
                                                                        null, 
                                                                        "*", 
                                                                        "", 
                                                                        "c63_anousu=" . $clconaberturaexe->c91_anousuorigem));
      if ($clconplanoconta->numrows > 0) {
        
        ( int ) $iNumRows = $clconplanoconta->numrows;
        
        for($i = 0; $i < $iNumRows; $i ++) {
          
          db_atutermometro($i, $iNumRows, 'divimp2');
          $oConc = db_utils::fieldsMemory($rsCon, $i);
          
          $clconplanoconta->c63_codcon        =  $oConc->c63_codcon;
          $clconplanoconta->c63_anousu        = $clconaberturaexe->c91_anousudestino;
          $clconplanoconta->c63_banco         = $oConc->c63_banco == '' ? 0 : $oConc->c63_banco;
          $clconplanoconta->c63_agencia       = $oConc->c63_agencia == '' ? 0 : $oConc->c63_agencia;
          $clconplanoconta->c63_conta         = $oConc->c63_conta == '' ? 0 : $oConc->c63_conta;
          $clconplanoconta->c63_dvconta       = $oConc->c63_dvconta == '' ? 'x' : $oConc->c63_dvconta;
          $clconplanoconta->c63_dvagencia     = $oConc->c63_dvagencia == '' ? 'x' : $oConc->c63_dvagencia;
          $clconplanoconta->c63_identificador = $oConc->c63_identificador == '' ? 0 : $oConc->c63_identificador;
          $clconplanoconta->incluir($oConc->c63_codcon, $clconplanoconta->c63_anousu);
          
          if ($clconplanoconta->erro_status == "0") {
            
            $sErro  = " [7] Erro ao incluir dados em conplanoconta. <br>";
    	      $sErro .= " Erro: {$clconplanoconta->erro_msg}";
            throw new Exception($sErro);
            
          }
          
        }
      
      }
      
    }

    /**
     * Duplica registros de complanoOrcamentoConta para o exercercio destino.
     */
    echo "<script>document.getElementById('lblimp').innerHTML = 'Importando Contas Bancárias - {$iAno}'</script>";
    flush();
    
    $iNumRows           = 0;
    $oDaoOrcamentoConta = db_utils::getDao("conplanoorcamentoconta");
    $sWhere             = "c63_anousu = {$clconaberturaexe->c91_anousudestino}";
    $sSqlOrcamentoConta = $oDaoOrcamentoConta->sql_query_file (null, null, "1", null, $sWhere);
    $rsOrcamentoConta   = $oDaoOrcamentoConta->sql_record($sSqlOrcamentoConta);
    $iNumRows           = $oDaoOrcamentoConta->numrows;
    
    if ($iNumRows == 0) {
      
      db_atutermometro($i, $iNumRows, 'divimp2');
      $iNumRows           = 0;
      $rsOrcamentoConta   = null;
      $sWhere             = "c63_anousu = {$clconaberturaexe->c91_anousuorigem}";
      $sSqlOrcamentoConta = $oDaoOrcamentoConta->sql_query_file (null,
                                                                 null,
                                                                 "*", 
                                                                 "", 
                                                                 $sWhere);
      $rsOrcamentoConta   = $oDaoOrcamentoConta->sql_record($sSqlOrcamentoConta);
      $iNumRows           = $oDaoOrcamentoConta->numrows;
      
      if ($iNumRows > 0) {
        
        for ($i = 0; $i < $iNumRows; $i++) {
          
          $oOrcamentoConta                       = db_utils::fieldsMemory($rsOrcamentoConta, $i);
          $oDaoOrcamentoConta->c63_codcon        = $oOrcamentoConta->c63_codcon;
          $oDaoOrcamentoConta->c63_anousu        = $clconaberturaexe->c91_anousudestino;
          $oDaoOrcamentoConta->c63_banco         = $oOrcamentoConta->c63_banco == '' ? 0 : $oOrcamentoConta->c63_banco;
          $oDaoOrcamentoConta->c63_agencia       = $oOrcamentoConta->c63_agencia == '' ? 0 : $oOrcamentoConta->c63_agencia;
          $oDaoOrcamentoConta->c63_conta         = $oOrcamentoConta->c63_conta == '' ? 0 : $oOrcamentoConta->c63_conta;
          $oDaoOrcamentoConta->c63_dvconta       = $oOrcamentoConta->c63_dvconta == '' ? 'x' : $oOrcamentoConta->c63_dvconta;
          $oDaoOrcamentoConta->c63_dvagencia     = $oOrcamentoConta->c63_dvagencia == '' ? 'x' : $oOrcamentoConta->c63_dvagencia;
          $oDaoOrcamentoConta->c63_identificador = $oOrcamentoConta->c63_identificador == '' ? 0 : $oOrcamentoConta->c63_identificador;
          $oDaoOrcamentoConta->incluir($oOrcamentoConta->c63_codcon, $oDaoOrcamentoConta->c63_anousu);
          if ($oDaoOrcamentoConta->erro_status == "0") {
          
            $sErro  = " [8] Erro ao incluir dados em conplanoorcamentoconta. <br>";
    	      $sErro .= " Erro: {$oDaoOrcamentoConta->erro_msg}";
            throw new Exception($sErro);
            
          }
          
        }
        
      }
      
    }
    
    
    echo "<script>document.getElementById('lblimp').innerHTML = 'Importando Elementos de Despesa - {$iAno}'</script>";
    flush();
    
    $sql1 = "select 1 from orcelemento where o56_anousu = " . $clconaberturaexe->c91_anousudestino;
    $rs = db_query($sql1);
    if (pg_num_rows($rs) == 0) {
    	
      $rsEle    = $clorcelemento->sql_record($clorcelemento->sql_query_file(null, null, "*", null, "o56_anousu=" . $clconaberturaexe->c91_anousuorigem));
      $iNumRows = $clorcelemento->numrows;
      if ($iNumRows > 0) {
        
        for($i = 0; $i < $iNumRows; $i ++) {
          
          db_atutermometro($i, $iNumRows, 'divimp2');
          $oConc = db_utils::fieldsMemory($rsEle, $i);
          $clorcelemento->o56_codele   = $oConc->o56_codele;
          $clorcelemento->o56_anousu   = $clconaberturaexe->c91_anousudestino;
          $clorcelemento->o56_elemento = $oConc->o56_elemento;
          $clorcelemento->o56_descr    = $oConc->o56_descr;
          $clorcelemento->o56_finali   = $oConc->o56_finali;
          $clorcelemento->o56_orcado   = $oConc->o56_orcado;
          $clorcelemento->incluir($oConc->o56_codele, $clorcelemento->o56_anousu);
          if ($clorcelemento->erro_status == "0") {
            
            $sErro  = " [9] Erro ao incluir dados em orcelemento. <br>";
    	      $sErro .= " Erro: {$clorcelemento->erro_msg}";
            throw new Exception($sErro);
          
          }
          
        }
        
      }
      
    }
    
    // importando orcorgao
    echo "<script>document.getElementById('lblimp').innerHTML = 'Importando Orgaos - {$iAno}'</script>";
    flush();
    
    $sql1 = "select 1 from orcorgao where o40_anousu = " . $clconaberturaexe->c91_anousudestino;
    $rs = db_query($sql1);
    if (pg_num_rows($rs) == 0) {
      $rsOrg = $clorcorgao->sql_record($clorcorgao->sql_query_file(null, null, "*", null, "o40_anousu=" . $clconaberturaexe->c91_anousuorigem));
      
      if ($clorcorgao->numrows > 0) {
        
        ( int ) $iNumRows = $clorcorgao->numrows;
        for($i = 0; $i < $iNumRows; $i ++) {
          
          db_atutermometro($i, $iNumRows, 'divimp2');
          $oConc = db_utils::fieldsMemory($rsOrg, $i);
          $clorcorgao->o40_orgao  = $oConc->o40_orgao;
          $clorcorgao->o40_anousu = $clconaberturaexe->c91_anousudestino;
          $clorcorgao->o40_codtri = $oConc->o40_codtri == '' ? 0 : $oConc->o40_codtri;
          $clorcorgao->o40_descr  = $oConc->o40_descr;
          $clorcorgao->o40_finali = $oConc->o40_finali;
          $clorcorgao->o40_instit = $oConc->o40_instit;
          $clorcorgao->incluir($clorcorgao->o40_anousu, $clorcorgao->o40_orgao);
          if ($clorcorgao->erro_status == "0") {
            
            $sErro  = " [10] Erro ao incluir dados em orcorgao. <br>";
    	      $sErro .= " Erro: {$clorcorgao->erro_msg}";
            throw new Exception($sErro);
          
          }
          
        }
      
      }
      
    }

    // importando orcunidade
    echo "<script>document.getElementById('lblimp').innerHTML = 'Importando Unidades - {$iAno}'</script>";
    flush();
    
    $rsUni = $clorcunidade->sql_record($clorcunidade->sql_query_file(null, null, null, "*", null, "o41_anousu     = " . $clconaberturaexe->c91_anousuorigem . "
  																  and o41_instit = " . $clconaberturaexe->c91_instit));
    
    if ($clorcunidade->numrows > 0) {
      
      ( int ) $iNumRows = $clorcunidade->numrows;
      for($i = 0; $i < $iNumRows; $i ++) {
        
        db_atutermometro($i, $iNumRows, 'divimp2');
        $oConc = db_utils::fieldsMemory($rsUni, $i);
        $clorcunidade->o41_orgao   = $oConc->o41_orgao;
        $clorcunidade->o41_anousu  = $clconaberturaexe->c91_anousudestino;
        $clorcunidade->o41_codtri  = $oConc->o41_codtri;
        $clorcunidade->o41_descr   = $oConc->o41_descr;
        $clorcunidade->o41_instit  = $oConc->o41_instit;
        $clorcunidade->o41_unidade = $oConc->o41_unidade;
        $clorcunidade->o41_cnpj    = $oConc->o41_cnpj;
        $clorcunidade->o41_indent  = $oConc->o41_indent;
        $clorcunidade->o41_ident   = $oConc->o41_ident;
        $clorcunidade->incluir($clorcunidade->o41_anousu, $oConc->o41_orgao, $oConc->o41_unidade);
        
        if ($clorcunidade->erro_status == "0") {
          
          $sErro  = " [11] Erro ao incluir dados em orcunidade. <br>";
    	    $sErro .= " Erro: {$clorcunidade->erro_msg}";
          throw new Exception($sErro);
        
        }
        
      }
    
    }
        
    // importando orcprograma
    $sql1 = "select 1 from orcprograma where o54_anousu = " . $clconaberturaexe->c91_anousudestino;
    $rs = db_query($sql1);
    if (pg_num_rows($rs) == 0) {
      
      echo "<script>document.getElementById('lblimp').innerHTML = 'Importando Programas - {$iAno}'</script>";
      flush();
      $rsPro = $clorcprograma->sql_record($clorcprograma->sql_query_file($clconaberturaexe->c91_anousuorigem));
      
      if ($clorcprograma->numrows > 0) {
        
        ( int ) $iNumRows = $clorcprograma->numrows;
        for($i = 0; $i < $iNumRows; $i ++) {
          
          db_atutermometro($i, $iNumRows, 'divimp2');
          $oConc = db_utils::fieldsMemory($rsUni, $i);
          $oConc = db_utils::fieldsMemory($rsPro, $i);
          $clorcprograma->o54_anousu       = $clconaberturaexe->c91_anousudestino;
          $clorcprograma->o54_programa     = $oConc->o54_programa;
          $clorcprograma->o54_codtri       = $oConc->o54_codtri;
          $clorcprograma->o54_descr        = $oConc->o54_descr;
          $clorcprograma->o54_finali       = $oConc->o54_finali;
          $clorcprograma->o54_tipoprograma = $oConc->o54_tipoprograma == ""?"0":$oConc->o54_tipoprograma;
          $clorcprograma->incluir($clorcprograma->o54_anousu, $oConc->o54_programa);
          if ($clorcprograma->erro_status == "0") {
            
            $sErro  = " [12] Erro ao incluir dados em orcprograma. <br>";
    	      $sErro .= " Erro: {$clorcprograma->erro_msg}";
            throw new Exception($sErro);
          
          }
          
        }
        
      }
      
    }
    
    
    //importando orcprojativ
    $sql1 = "select 1 from orcprojativ where o55_anousu = " . $clconaberturaexe->c91_anousudestino;
    $rs = db_query($sql1);
    if (pg_num_rows($rs) == 0) {
      echo "<script>document.getElementById('lblimp').innerHTML = 'Importando Projetos/Atividades - {$iAno}'</script>";
      flush();
      
      $rsProj = $clorcprojativ->sql_record($clorcprojativ->sql_query_file(null, null, "*", null, "o55_anousu     =" . $clconaberturaexe->c91_anousuorigem));
      
      if ($clorcprojativ->numrows > 0) {
        
        ( int ) $iNumRows = $clorcprojativ->numrows;
        for($i = 0; $i < $iNumRows; $i ++) {
          
          db_atutermometro($i, $iNumRows, 'divimp2');
          $oConc = db_utils::fieldsMemory($rsProj, $i);
          $clorcprojativ->o55_anousu             = $clconaberturaexe->c91_anousudestino;
          $clorcprojativ->o55_tipo               = $oConc->o55_tipo;
          $clorcprojativ->o55_projativ           = $oConc->o55_projativ;
          $clorcprojativ->o55_descr              = $oConc->o55_descr;
          $clorcprojativ->o55_finali             = $oConc->o55_finali;
          $clorcprojativ->o55_instit             = $oConc->o55_instit;
          $clorcprojativ->o55_tipoacao           = $oConc->o55_tipoacao==""?"0":$oConc->o55_tipoacao;
          $clorcprojativ->o55_orcproduto         = $oConc->o55_orcproduto==""?"0":$oConc->o55_orcproduto;
          $clorcprojativ->o55_formaimplementacao = $oConc->o55_formaimplementacao==""?"0":$oConc->o55_formaimplementacao;
          $clorcprojativ->incluir($clorcprojativ->o55_anousu, $oConc->o55_projativ);
          if ($clorcprojativ->erro_status == "0") {
            
            $sErro  = " [13] Erro ao incluir dados em orcprojativ. <br>";
    	      $sErro .= " Erro: {$clorcprojativ->erro_msg}";
            throw new Exception($sErro);
          
          }
          
        }
        
      }
    
    }
    
    
    //importando orcfontes
    $sql1 = "select 1 from orcfontes where o57_anousu = " . $clconaberturaexe->c91_anousudestino;
    $rs = db_query($sql1);
    if (pg_num_rows($rs) == 0) {
      
      echo "<script>document.getElementById('lblimp').innerHTML = 'Importando Fontes de Receita - {$iAno}'</script>";
      flush();
      $rsFon = $clorcfontes->sql_record($clorcfontes->sql_query_file(null, null, "*", null, "o57_anousu     =" . $clconaberturaexe->c91_anousuorigem));
      
      if ($clorcfontes->numrows > 0) {
        
        ( int ) $iNumRows = $clorcfontes->numrows;
        for($i = 0; $i < $iNumRows; $i ++) {
          
          db_atutermometro($i, $iNumRows, 'divimp2');
          $oConc = db_utils::fieldsMemory($rsFon, $i);
          $clorcfontes->o57_codfon = $oConc->o57_codfon;
          $clorcfontes->o57_anousu = $clconaberturaexe->c91_anousudestino;
          $clorcfontes->o57_fonte  = $oConc->o57_fonte;
          $clorcfontes->o57_descr  = $oConc->o57_descr;
          $clorcfontes->o57_finali = $oConc->o57_finali;
          $clorcfontes->incluir($oConc->o57_codfon, $clorcfontes->o57_anousu);
          if ($clorcfontes->erro_status == "0") {
            
            $sErro  = " [14] Erro ao incluir dados em orcfontes. <br>";
    	      $sErro .= " Erro: {$clorcfontes->erro_msg}";
            throw new Exception($sErro);
          
          }
          
        }
      
      }
      
    }

    //importando orcfontesdes
    $sql1 = "select 1 from orcfontesdes where o60_anousu = " . $clconaberturaexe->c91_anousudestino;
    $rs = db_query($sql1);
    if (pg_num_rows($rs) == 0) {
      
      echo "<script>document.getElementById('lblimp').innerHTML = 'Importando Desdobramentos da Receita - {$iAno}'</script>";
      flush();
      
      $rsFon = $clorcfontesdes->sql_record($clorcfontesdes->sql_query(null, null, "*", null, "o60_anousu     =" . $clconaberturaexe->c91_anousuorigem));
      
      if ($clorcfontesdes->numrows > 0) {
        
        ( int ) $iNumRows = $clorcfontesdes->numrows;
        for($i = 0; $i < $iNumRows; $i ++) {
          
          db_atutermometro($i, $iNumRows, 'divimp2');
          $oConc = db_utils::fieldsMemory($rsFon, $i);
          $clorcfontesdes->o60_codfon = $oConc->o60_codfon;
          $clorcfontesdes->o60_anousu = $clconaberturaexe->c91_anousudestino;
          $clorcfontesdes->o60_perc   = $oConc->o60_perc;
          $clorcfontesdes->incluir($clorcfontesdes->o60_anousu, $clorcfontesdes->o60_codfon);
          if ($clorcfontesdes->erro_status == "0") {
            
            $sErro  = " [15] Erro ao incluir dados em orcfontesdes. <br>";
    	      $sErro .= " Erro: {$clorcfontesdes->erro_msg}";
            throw new Exception($sErro);
          
          }
          
        }
      
      }
      
    }
      
    $sql1 = "select 1 from orcparametro where o50_anousu = " . $clconaberturaexe->c91_anousudestino;
    $rs = db_query($sql1);
    if (pg_num_rows($rs) == 0) {
      
      echo "<script>document.getElementById('lblimp').innerHTML = 'Importando Parâmetros do Orçamento - {$iAno}'</script>";
      flush();
      
      $rsPars = $clorcparametro->sql_record($clorcparametro->sql_query($clconaberturaexe->c91_anousuorigem));
      
      if ($clorcparametro->numrows > 0) {
        
        ( int ) $iNumRows = $clorcparametro->numrows;
        for($i = 0; $i < $iNumRows; $i ++) {
          
          db_atutermometro($i, $iNumRows, 'divimp2');
          $oConc = db_utils::fieldsMemory($rsPars, $i);
          $oConc->o50_subelem  = $oConc->o50_subelem == "f" ? "false":"true";
          $clorcparametro->o50_coddot           = $oConc->o50_coddot;
          $clorcparametro->o50_anousu           = $clconaberturaexe->c91_anousudestino;
          $clorcparametro->o50_subelem          = $oConc->o50_subelem;
          $clorcparametro->o50_programa         = $oConc->o50_programa;
          $clorcparametro->o50_estrutdespesa    = $oConc->o50_estrutdespesa;
          $clorcparametro->o50_estrutreceita    = $oConc->o50_estrutreceita;
          $clorcparametro->o50_estrutelemento   = $oConc->o50_estrutelemento;
          $clorcparametro->o50_tipoproj         = $oConc->o50_tipoproj;
          $clorcparametro->o50_utilizapacto     = $oConc->o50_utilizapacto=="t"?"true":"false";
          $clorcparametro->o50_liberadecimalppa = $oConc->o50_liberadecimalppa=="t"?"true":"false";
          $clorcparametro->o50_estruturarecurso = $oConc->o50_estruturarecurso;  
          $clorcparametro->o50_estruturacp      = $oConc->o50_estruturacp;  
          $clorcparametro->incluir($clorcparametro->o50_anousu);
          if ($clorcparametro->erro_status == "0") {
            
            $sErro  = " [16] Erro ao incluir dados em orcparametro. <br>";
    	      $sErro .= " Erro: {$clorcparametro->erro_msg}";
            throw new Exception($sErro);
          
          }
          
        }
      
      }
      
    }
    
    /*
     * Duplicamos os dados da tabela conplano grupo para o exercercio destino.
     */
    $sql1 = "select 1 from conplanogrupo where c21_anousu = " . $clconaberturaexe->c91_anousudestino;
    $rs = db_query($sql1);
    if (pg_num_rows($rs) == 0) {
      
      $oDaoConPlanoGrupo = db_utils::getDao("conplanogrupo");
      $rsConplanoGrupo   = $oDaoConPlanoGrupo->sql_record($oDaoConPlanoGrupo->sql_query_file(null, 
      		                                                                                   "*", 
                                                                                             null, 
      		                                                                                   "c21_anousu = {$clconaberturaexe->c91_anousuorigem}"));
      echo "<script>document.getElementById('lblimp').innerHTML = 'Importando Grupo de Contas - {$iAno}'</script>";
      flush();
      $iNumRows = $oDaoConPlanoGrupo->numrows;                                                        
      if ($oDaoConPlanoGrupo->numrows > 0) {
  
        for ($i = 0; $i < $iNumRows; $i++) {
  
          db_atutermometro($i, $iNumRows, 'divimp2');
          $oConplanoGrupo = db_utils::fieldsMemory($rsConplanoGrupo, $i);
          $oDaoConPlanoGrupo->c21_anousu   = $clconaberturaexe->c91_anousudestino;
          $oDaoConPlanoGrupo->c21_codcon   = $oConplanoGrupo->c21_codcon;
          $oDaoConPlanoGrupo->c21_congrupo = $oConplanoGrupo->c21_congrupo;
          $oDaoConPlanoGrupo->incluir(null);
          if ($oDaoConPlanoGrupo->erro_status == "0") {
            
            $sErro  = " [17] Erro ao incluir dados em conplanogrupo. <br>";
    	      $sErro .= " Erro: {$oDaoConPlanoGrupo->erro_msg}";
            throw new Exception($sErro);
            
          }
        }
        
      }
      
    }
      
    /**
     *  Duplicamos os dados da conplanoorcamentogrupo para o exercercio destino.
     */
    $iNumRows           = 0;
    $oDaoOrcamentoGrupo = db_utils::getDao("conplanoorcamentogrupo");
    $sWhere             = "c21_anousu = {$clconaberturaexe->c91_anousudestino} and c21_instit = ". db_getsession("DB_instit");
    $sSqlOrcamentoGrupo = $oDaoOrcamentoGrupo->sql_query_file(null, "1", null, $sWhere);
    $rsOrcamentoGrupo   = $oDaoOrcamentoGrupo->sql_record($sSqlOrcamentoGrupo);
    $iNumRows           = $oDaoOrcamentoGrupo->numrows;
    echo "<script>document.getElementById('lblimp').innerHTML = 'Importando Grupo de Contas Orçamentarias - {$iAno}'</script>";
    flush();
    if ($iNumRows == 0) {
      
      $iNumRows           = 0;
      $sWhere             = "     c21_anousu = {$clconaberturaexe->c91_anousuorigem} ";
      $sWhere            .= " and c21_instit = {$clconaberturaexe->c91_instit} ";
      $sSqlOrcamentoGrupo = $oDaoOrcamentoGrupo->sql_query_file(null, "*", null, $sWhere);
      $rsOrcamentoGrupo   = $oDaoOrcamentoGrupo->sql_record($sSqlOrcamentoGrupo);
      $iNumRows           = $oDaoOrcamentoGrupo->numrows;
      
      if ($iNumRows > 0) {
        
        for ($i = 0; $i < $iNumRows; $i++) {

          db_atutermometro($i, $iNumRows, 'divimp2');
          $oOrcamentoGrupo = db_utils::fieldsMemory($rsOrcamentoGrupo, $i);
          $oDaoOrcamentoGrupo->c21_anousu     = $clconaberturaexe->c91_anousudestino;
          $oDaoOrcamentoGrupo->c21_codcon     = $oOrcamentoGrupo->c21_codcon;
          $oDaoOrcamentoGrupo->c21_congrupo   = $oOrcamentoGrupo->c21_congrupo;
          $oDaoOrcamentoGrupo->c21_instit     = $oOrcamentoGrupo->c21_instit;
          $oDaoOrcamentoGrupo->incluir(null);
          if ($oDaoOrcamentoGrupo->erro_status == "0") {
          
            $sErro  = " [18] Erro ao incluir dados em conplanoorcamentogrupo. <br>";
    	      $sErro .= " Erro: {$oDaoOrcamentoGrupo->erro_msg}";
            throw new Exception($sErro);
          
          }
          
        }
        
      }
      
    }
    
      
    /**
     *  Duplicamos os dados da conplanoconplanoorcamento para o exercercio destino.
     */
    $iNumRows      = 0;
    $oDaoConplano  = db_utils::getDao("conplanoconplanoorcamento");
    $sWhere        = "c72_anousu = {$clconaberturaexe->c91_anousudestino}";
    $sSqlOrcamento = $oDaoConplano->sql_query_file(null, "1", null, $sWhere);
    $rsOrcamento   = $oDaoConplano->sql_record($sSqlOrcamento);
    $iNumRows      = $oDaoConplano->numrows;
    echo "<script>document.getElementById('lblimp').innerHTML = 'Importando Vinculo das Contas Orçamentarias - {$iAno}'</script>";
    flush();
    if ($iNumRows == 0) {
    
      $iNumRows      = 0;
      $sWhere        = "c72_anousu = {$clconaberturaexe->c91_anousuorigem}";
      $sSqlOrcamento = $oDaoConplano->sql_query_file(null, "*", null, $sWhere);
      $rsOrcamento   = $oDaoConplano->sql_record($sSqlOrcamento);
      $iNumRows      = $oDaoConplano->numrows;
    
      if ($iNumRows > 0) {
    
        for ($i = 0; $i < $iNumRows; $i++) {
    
          db_atutermometro($i, $iNumRows, 'divimp2');
          $oOrcamentoVinculo                        = db_utils::fieldsMemory($rsOrcamento, $i);
          $oDaoConplanoCopia                        = db_utils::getDao("conplanoconplanoorcamento");
          $oDaoConplanoCopia->c72_conplano          = $oOrcamentoVinculo->c72_conplano;         
          $oDaoConplanoCopia->c72_conplanoorcamento = $oOrcamentoVinculo->c72_conplanoorcamento;
          $oDaoConplanoCopia->c72_anousu            = $clconaberturaexe->c91_anousudestino;
          $oDaoConplanoCopia->incluir(null);
          
          if ($oDaoConplanoCopia->erro_status == "0") {
    
            $sErro  = " [19] Erro ao incluir dados em conplanoconplanoorcamento. <br>";
    	      $sErro .= " Erro: {$oDaoConplanoCopia->erro_msg}";
            throw new Exception($sErro);
            
          }
          
        }
        
      }
      
    }
    
  }
  
  db_fim_transacao(false);
  db_msgbox('Abertura do exercício realizada com sucesso.');
  
  } catch (Exception $oErro) {
  	
  	db_fim_transacao(true);
  	$sErro  = "Processamento não realizado!<br>";
  	$sErro .= $oErro->getMessage();
  	echo $sErro;
    exit;  	
  }
  
  db_redireciona('con1_conaberturaexe001.php?sMostraMsg=n');

}

?>