<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
 * @version $Revision: 1.3 $
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
if (substr($aLinhas[1],0,24) != "1.0.0.0.0.00.00.00.00.00") {

  db_msgbox("o Arquivo {$sFileName} Não é um arquivo de plano de contas. Operação cancelada.");
  echo "<script>parent.iframe_importar.hide()</script>";
  exit;
}  

$sSqlConplano  = "select  c60_codcon ";
$sSqlConplano .= "  from  conplano ";
$sSqlConplano .= "  where c60_anousu = ".(db_getsession("DB_anousu") -1);
$sSqlConplano .= "    or  c60_anousu = ".db_getsession("DB_anousu");
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
   
    db_criatermometro("plano"," da importação concluída");
    $aContas = array();
    $iLinha  = 0; 
    foreach ($aLinhas as $sLinha) {
      
      $iLinha++;
      $aCamposLinha = split("\t", $sLinha);
      if ($iLinha == 1 || !isset($aCamposLinha[5])) {
        continue;
      }
      
      $oConta = new stdClass();
      $oConta->estrutural    = str_replace(".","",$aCamposLinha[0]); 
      $oConta->descricao     = trim(utf8_decode(substr($aCamposLinha[5], 0, 50))); 
      $oConta->finalidade    = ""; 
      $oConta->codigo        = null; 
      $oConta->sistema       = ""; 
      $oConta->reduzido      = ""; 
      $oConta->tipo          = $aCamposLinha[3]; 
      $oConta->classificacao = 1; 
      $sSistema = $aCamposLinha[2];
      switch ($sSistema) {
       
        case "F":
          
          $oConta->sistema = 1;
          break;
        case "P":
          
          $oConta->sistema = 2;
          break;
        case "O":
          
          $oConta->sistema = 3;
          break;  
        case "C":
          
          $oConta->sistema = 4;
          break;

        default:
          
          $oConta->sistema = 2;
          break; 
          
      }
      if (substr($oConta->estrutural,0,5) == "11111" && $oConta->tipo == "A" ) {
        
        $oConta->sistema == 5;
      } else if ((substr($oConta->estrutural,0,5) == "11112"|| substr($oConta->estrutural,0,5) == "11113" ||
                  substr($oConta->estrutural,0,5) == "11113") && $oConta->tipo == "A") {
        $oConta->sistema == 6; 
      }
      if (substr($aCamposLinha[1],0,1) == "S" && $aCamposLinha[3] == "A") {
        $oConta->classificacao = 3;       
      }
      $aContas[] = $oConta;
      
    }
    $iTotalLinhas = $iLinha * 2;
    $aAnos = array(db_getsession("DB_anousu") -1, db_getsession("DB_anousu"));
    db_inicio_transacao();
    $oDaoConplano      = new cl_conplano;
    $oDaoOrcFontes     = new cl_orcfontes;
    $oDaoOrcElemento   = new cl_orcelemento;
    $oDaoConplanoReduz = new cl_conplanoreduz;
    $oDaoConplanoExe   = new cl_conplanoexe;
    $lErro  = false;
    $iLinha = 1;
    foreach ($aAnos as $iAno) {
      
      for ($iConta = 0; $iConta < count($aContas); $iConta++) {
        
        /**
         * incluimos conplano
         */
        if (!$lErro) {
          
          $oDaoConplano->c60_anousu = $iAno;
          $oDaoConplano->c60_codcla = $aContas[$iConta]->classificacao;
          $oDaoConplano->c60_codsis = $aContas[$iConta]->sistema;
          $oDaoConplano->c60_descr  = $aContas[$iConta]->descricao;
          $oDaoConplano->c60_estrut = $aContas[$iConta]->estrutural;
          $oDaoConplano->c60_finali = $aContas[$iConta]->finalidade;
          if ($aContas[$iConta]->codigo != null) {
            
            $oDaoConplano->incluir($aContas[$iConta]->codigo, $iAno);
          } else {
            $oDaoConplano->incluir(null, $iAno);
          }
          if ($oDaoConplano->erro_status == 0) {
          
            db_msgbox($oDaoConplano->erro_msg);
            $lErro = true;
            break;
          }
          $aContas[$iConta]->codigo = $oDaoConplano->c60_codcon;
        }
      
        /**
         * Verifica se a conta é receita
         */
        if (substr($aContas[$iConta]->estrutural, 0, 1) == 4 || substr($aContas[$iConta]->estrutural, 0, 1) == 9) {
  
          $oDaoOrcFontes->o57_anousu = $iAno;
          $oDaoOrcFontes->o57_codfon = $aContas[$iConta]->codigo;
          $oDaoOrcFontes->o57_descr  = $aContas[$iConta]->descricao;
          $oDaoOrcFontes->o57_finali = $aContas[$iConta]->finalidade;
          $oDaoOrcFontes->o57_fonte  = $aContas[$iConta]->estrutural;
          $oDaoOrcFontes->incluir($aContas[$iConta]->codigo, $iAno);
          if ($oDaoOrcFontes->erro_status == 0) {
            
            db_msgbox($oDaoOrcFontes->erro_msg);
            $lErro = true;
            break;
          }
        } else if (substr($aContas[$iConta]->estrutural, 0, 1) == 3) {
          
          $oDaoOrcElemento->o56_anousu   = $iAno;
          $oDaoOrcElemento->o56_codele   = $aContas[$iConta]->codigo;
          $oDaoOrcElemento->o56_descr    = $aContas[$iConta]->descricao;
          $oDaoOrcElemento->o56_elemento = substr($aContas[$iConta]->estrutural, 0, 13);
          $oDaoOrcElemento->o56_finali   = '';
          $oDaoOrcElemento->o56_orcado   = 'false';
          $oDaoOrcElemento->incluir($aContas[$iConta]->codigo, $iAno); 
          if ($oDaoOrcElemento->erro_status == 0) {
            
            db_msgbox($oDaoOrcElemento->erro_msg);
            $lErro = true;
            break;
          } 
        }
        
        /**
         * Verificamos se a conta é analitica
         */
        if ($aContas[$iConta]->tipo == "A" && (substr($aContas[$iConta]->estrutural, 0, 1) == 4 
            || substr($aContas[$iConta]->estrutural, 0, 1) == 9 || substr($aContas[$iConta]->estrutural, 0, 1) == 3)) {
             
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
    }
    db_fim_transacao($lErro);
    if (!$lErro) {

      db_msgbox("Importação Efetuada com sucesso!");
      echo "<script>";
      echo "js_emite();\n";
      echo "parent.iframe_importar.hide()</script>";
    }
  ?>
  </body>  
</html>