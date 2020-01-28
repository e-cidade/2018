<?php
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

/**
 * 
 * @author Iuri Guntchnigg
 * @revision $Author: dbiuri $
 * @version $Revision: 1.2 $
 */
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_conplano_classe.php");
require_once("classes/db_orcfontes_classe.php");
require_once("classes/db_orcelemento_classe.php");
require_once("classes/db_conplanoexe_classe.php");
require_once("classes/db_conplanoreduz_classe.php");
$oGet      = db_utils::postMemory($_GET);
$sFileName = $oGet->sFileName;
$aLinhas   = file("tmp/{$sFileName}");
if (substr($aLinhas[0],0,24) != "1.0.0.0.0.00.00.00.00.00") {

  db_msgbox("o Arquivo {$sFileName} Não é um arquivo de plano de contas. Operação cancelada.");
  echo "<script>parent.iframe_importar.hide()</script>";
  exit;
}  

$sSqlConplano  = "select  c60_codcon ";
$sSqlConplano .= "  from  conplano ";
$sSqlConplano .= "  where c60_anousu = ".db_getsession("DB_anousu");
$rsConplano    = db_query($sSqlConplano);
if (pg_num_rows($rsConplano) > 0) {
   
  $sMsg  = "já existe plano de contas cadastrado para os anos ".(db_getsession("DB_anousu") -1)." e ";
  $sMsg .= db_getsession("DB_anousu");
  db_msgbox($sMsg);
  echo "<script>parent.iframe_importar.hide()</script>";
  exit;
}

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
     db_app::load("scripts.js, prototype.js");
     db_app::load("estilos.css");
    ?>
    <script>
      function js_emite() {
   
        var sUrl =  'con2_consistenciaplano002.php?instit=<?=db_getsession("DB_instit");?>';
        jan = window.open(sUrl, '', 'height='+(screen.availHeight-40)+',scrollbars=1,location=0');
        jan.moveTo(0,0);
     }
    </script>
  </head>
  <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <?
   
    db_criatermometro("ano"," da importação concluída");
    db_criatermometro("plano"," da importação concluída");
    $aContas = array();
    $iLinha  = 0;
    $aLinhasInvalidas = array(); 
    foreach ($aLinhas as $sLinha) {
      
      $iLinha++;
      $aCamposLinha = explode(";", $sLinha);
      $sEstrutural = $aCamposLinha[0];
      
      /**
       * Verificamos se é um estrutural valido.
       * para tanto, o estruturaçl devera ter 9 pontos
       * a ER abaixo daz essa validação:
       * ^.=  procuramos pelo caractere ponto
       * {9} = deve existir 9 ocorrencias do caractece "." 
       */
      if (!preg_match("/^.{9}/", $sEstrutural) && strlen(str_replace(".", "", $sEstrutural)) != 15) {
        $aLinhasInvalidas [] = $sLinha;
        continue;
      }
      
      if (count($aCamposLinha) != 7) {

        $aLinhasInvalidas[] = $sLinha;
        continue;
      }
      $sDescricaoConta                 = $aCamposLinha[1];
      $sFinalidadeConta                = $aCamposLinha[2];
      $sNaturezaSaldo                  = trim($aCamposLinha[3]);
      $sTipoConta                      = trim($aCamposLinha[4]);
      $sSistema                        = $aCamposLinha[5];
      $sIdentificadorFinanceiro        = trim($aCamposLinha[6]);
      $oConta                          = new stdClass();
      $oConta->estrutural              = str_replace(".", "", $sEstrutural); 
      $oConta->descricao               = trim(substr($sDescricaoConta, 0, 50)); 
      $oConta->finalidade              = trim(($sFinalidadeConta)); 
      $oConta->codigo                  = null; 
      $oConta->sistema                 = "0"; 
      $oConta->reduzido                = "";
      $oConta->estruturalcompleto      = $aCamposLinha[0];
      $oConta->subsistema              = "0";
      $oConta->identificadorfinanceiro = $sIdentificadorFinanceiro == 'X'?"N":$sIdentificadorFinanceiro;  
      $oConta->naturezasaldo           = $sNaturezaSaldo == ''?'A':$sNaturezaSaldo;  
      $oConta->tipo                    = $sTipoConta == 'N'?'S':"A"; 
      $oConta->classificacao           = 1; 
      if ($sSistema == "P") {

        $oConta->sistema = 1;
        if ($oConta->estrutural == '111110100000000') {
          $oConta->sistema = 7;
        }
        
        if ($oConta->estrutural == '111110600000000' || $oConta->estrutural == '111111900000000') {
          $oConta->sistema  = 6;
        }
      }
      switch ($sSistema) {
        
        case 'F':
          
          $oConta->subsistema  = 1;
          break;
  
        case 'P':
          
          $oConta->subsistema  = 2;
          break;  
  
        case 'C':
          
          $oConta->subsistema  = 4;
          break;
        default:
          
          $oConta->subsistema = "0";
          break;
      }
        
       if (trim($oConta->identificadorfinanceiro) == "") {
         $oConta->identificadorfinanceiro = 'N';
      }
      switch ($sNaturezaSaldo) {
        
        case 'D' :
          
          $oConta->naturezasaldo = 1;
          break;
          
        case 'C' :
          
          $oConta->naturezasaldo = 2;
          break;

        default:
          
          $oConta->naturezasaldo = 3;
          break;
      }
      $aContas[] = $oConta;
    }
    
    $iAnoInicial           = db_getsession("DB_anousu");
    $iAnoFinal             = db_getsession("DB_anousu");
    /**
     * Consultamos o ultimo ano de Cadastro do plano de contas do orçamento
     */
    $oDaoConplanoOrcamento = db_utils::getDao("conplanoorcamento");
    $sSqlUltimoAno         = $oDaoConplanoOrcamento->sql_query_file(null, null, "max(c60_anousu) as ano");
    $rsUltimoAno           = $oDaoConplanoOrcamento->sql_record($sSqlUltimoAno);
    if ($oDaoConplanoOrcamento->numrows > 0) {
       $iAnoFinal = db_utils::fieldsMemory($rsUltimoAno, 0)->ano;
    }
    $iTotalLinhas          = count($aContas);
    $aAnos                 = array(db_getsession("DB_anousu"));
    db_inicio_transacao();
    $oDaoConplano      = new cl_conplano;
    $oDaoConplanoReduz = new cl_conplanoreduz;
    $oDaoConplanoExe   = new cl_conplanoexe;
    $lErro             = false;
    $iTotalAno         = 1;
    $iTotalDeAnos      = $iAnoFinal - $iAnoInicial; 
    for ($iAno = $iAnoInicial; $iAno <= $iAnoFinal; $iAno++) {
      
      $iLinha = 1;
      db_atutermometro($iLinha, $iTotalLinhas,"plano");
      for ($iConta = 0; $iConta < count($aContas); $iConta++) {
          
         /**
          * incluimos conplano
          */
         if (!$lErro) {
          
           $oDaoConplano->c60_anousu                   = $iAno;
           $oDaoConplano->c60_codcla                   = $aContas[$iConta]->classificacao;
           $oDaoConplano->c60_codsis                   = $aContas[$iConta]->sistema;
           $oDaoConplano->c60_descr                    = $aContas[$iConta]->descricao;
           $oDaoConplano->c60_estrut                   = $aContas[$iConta]->estrutural;
           $oDaoConplano->c60_finali                   = addslashes($aContas[$iConta]->finalidade);
           $oDaoConplano->c60_consistemaconta          = $aContas[$iConta]->subsistema;
           $oDaoConplano->c60_naturezasaldo            = $aContas[$iConta]->naturezasaldo;
           $oDaoConplano->c60_identificadorfinanceiro  = $aContas[$iConta]->identificadorfinanceiro;
          
           if ($aContas[$iConta]->codigo != null) {
             $oDaoConplano->incluir($aContas[$iConta]->codigo, $iAno);
           } else {
             $oDaoConplano->incluir(null, $iAno);
           }
           if ($oDaoConplano->erro_status == 0) {
           
             $sEstrutural = $aContas[$iConta]->estruturalcompleto;
             db_msgbox($oDaoConplano->erro_msg."\\n{$sEstrutural}");
             $lErro = true;
             break;
           }
           $aContas[$iConta]->codigo = $oDaoConplano->c60_codcon;
         }
         /**
          * Verificamos se a conta é analitica
          */
         if ($aContas[$iConta]->tipo == "A") {
             
           $oDaoConplanoReduz->c61_anousu        = $iAno;    
           $oDaoConplanoReduz->c61_codcon        = $aContas[$iConta]->codigo;    
           $oDaoConplanoReduz->c61_instit        = 1;    
           $oDaoConplanoReduz->c61_codigo        = 1;    
           $oDaoConplanoReduz->c61_contrapartida = "0";
           if ($aContas[$iConta]->reduzido != null) {
             $oDaoConplanoReduz->incluir($aContas[$iConta]->reduzido, $iAno);
           } else {
             $oDaoConplanoReduz->incluir(null, $iAno);    
           }
           if ($oDaoConplanoReduz->erro_status == 0) {
  
             db_msgbox($oDaoConplanoReduz->erro_msg);
             $lErro = true;
             break;
           }
           $aContas[$iConta]->reduzido = $oDaoConplanoReduz->c61_reduz;
           
           /**
            * incluimos conplanoexe
            */
           $oDaoConplanoExe->c62_anousu        = $iAno;    
           $oDaoConplanoExe->c62_codrec        = 1;     
           $oDaoConplanoExe->c62_reduz         = $aContas[$iConta]->reduzido;     
           $oDaoConplanoExe->c62_vlrcre        = "0"; 
           $oDaoConplanoExe->c62_vlrdeb        = "0";
           $oDaoConplanoExe->incluir($iAno, $aContas[$iConta]->reduzido);
           if ($oDaoConplanoExe->erro_status == 0) {
             db_msgbox($oDaoConplanoExe->erro_msg);
             $lErro = true;
             break;
           }
         }
         
         db_atutermometro($iLinha, $iTotalLinhas,"plano");
         flush();
         $iLinha++;
       }

       db_atutermometro($iTotalAno,$iTotalDeAnos, "ano");
       $iTotalAno++;
     }
     db_fim_transacao($lErro);
     #db_fim_transacao(true);
     if (!$lErro) {

       echo "<script>";
       echo "js_emite();\n";
       echo "parent.iframe_importar.hide()</script>";
     }
   ?>
  </body>  
</html>