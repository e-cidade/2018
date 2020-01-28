<?php
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


require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("model/recibo.model.php");

require_once("classes/db_db_config_classe.php");
require_once("classes/db_cadban_classe.php");
require_once("classes/db_disarq_classe.php");
require_once("classes/db_disbanco_classe.php");
require_once("classes/db_disbancotxt_classe.php");
require_once("classes/db_disbancotxtreg_classe.php");

db_app::import('exceptions.*');

db_postmemory($HTTP_POST_VARS);

$cldb_config      = new cl_db_config();
$clCadBan         = new cl_cadban();
$clDisArq         = new cl_disarq();
$clDisBanco       = new cl_disbanco();
$clDisBancoTXT    = new cl_disbancotxt();
$clDisBancoTXTReg = new cl_disbancotxtreg();

$situacao      = "";
$iDebuga       = 0;
$sMd5Arquivo   = null;

$iInstitSessao = db_getsession("DB_instit");

$result        = $cldb_config->sql_record($cldb_config->sql_query_file($iInstitSessao, "cgc, db21_codcli"));
db_fieldsmemory($result, 0);

if ($db21_codcli == 19985) {
  require_once("cai4_baixabanco001_codcli_19985.php");
  exit;
}

if (isset ($processar)) {

  try {                

    // recebe codbco,codage,tamanho
    // verifica banco e agencia
    db_postmemory($_FILES["arqret"]);
    $arq_name = basename($name);
    $arq_type = $type;
    $arq_tmpname = basename($tmp_name);
    $arq_size = $size;
    $arq_array = file($tmp_name);

    $sMd5Arquivo = md5(file_get_contents($tmp_name));
    db_putsession('disarq_md5', $sMd5Arquivo);
    
    /**
     * verifica se arquivo j� foi importado
     */
    
    $sSqlArquivoImportado = $clDisArq->sql_query_file(null, 'true', null, "md5 = '$sMd5Arquivo'");
    $rsArquivoImportado   = $clDisArq->sql_record($sSqlArquivoImportado);
    
    if ($clDisArq->numrows > 0) {
      throw new BusinessException("Arquivo j� importado para o sistema");
    }
     
    system("cp -f ".$tmp_name." ".$DOCUMENT_ROOT."/tmp/", $intret);
    if($intret != 0) {
      throw new Exception("Erro ao copiar arquivo : {$tmp_name}");
    }
     
    $sWhere     = " k15_codbco = {$k15_codbco} and k15_codage  = '{$k15_codage}' and k15_instit = {$iInstitSessao}";
    $sSqlCadBan = $clCadBan->sql_query(null,"*",null,$sWhere);
    $rsCadBan   = $clCadBan->sql_record($sSqlCadBan);
    
    if ($clCadBan->numrows == 0) {
      throw new Exception("Banco / Agencia nao cadastrados.");
    }
     
    db_fieldsmemory($rsCadBan, 0);
     
    $_tamanprilinha = $arq_array[0];
    $atipo = substr($arq_array[0], 0, 3);
    $totalproc = sizeof($arq_array) - 2;
    $priregistro = 1;
    $acodbco = substr($arq_array[0], substr($k15_posbco, 0, 3), substr($k15_posbco, 3, 3));
     
    if ($cgc == '88073291000199') { // bage
      if (substr($arq_name, 0, 4) == "daeb") {
         
        if (substr($arq_array[0], 0, 3) == "826")
        {
          $_tamanprilinha = str_repeat(" ", $k15_taman);
          $atipo = "XXX";
          $totalproc = sizeof($arq_array);
          $priregistro = 0;
          $acodbco = 999;
           
        }
         
      }
    }
     
    $k15_codbco = (int) $k15_codbco;
    $acodbco = (int) $acodbco;
     
    if (strlen($_tamanprilinha) != $k15_taman) {
      throw new Exception("Tamanho do registro [".strlen($arq_array[0])."] Sistema : [{$k15_taman}] Inv�lido.");
    }
     
    if ($k15_codbco != $acodbco and $atipo != "BSJ") {
      throw new Exception("Banco Digitado [{$k15_codbco}] n�o confere com o arquivo [{$acodbco}] especificado.");
    }

    $situacao = 1;
    $sCampos  = "codret as codretexiste,      ";
    $sCampos .= "k15_codbco as bancoexiste,   ";
    $sCampos .= "k15_codage as agenciaexiste, ";
    $sCampos .= "dtarquivo as dtarquivoexiste ";
    $rsDisArq = $clDisArq->sql_record($clDisArq->sql_query_file(null, 
                                      $sCampos, 
                                      null, 
                                      "md5 = '{$sMd5Arquivo}' and instit = $iInstitSessao"));

    if ($clDisArq->numrows > 0) {
      db_fieldsmemory($rsDisArq, 0);
    }
     
    $totalvalorpago = 0;
     
    for ($i = $priregistro; $i <= $totalproc - ($priregistro == 0 ? 1 : 0); $i ++) {
       
      // grava arquivo disbanco
      if ($k15_taman == 242) {
        // acerto 1/2 para arapiraca em 14/04 por evandro
        $totalproc = sizeof($arq_array) - 3;
        if (substr($arq_array[$i], 7, 1) != '3' or substr($arq_array[$i], 13, 1) != 'U') {
          continue;
        }
         
      } elseif ($k15_taman == 402) {
        if (substr($arq_array[$i], 0, 1) == '9') {
          continue;
        }
      } elseif ($k15_taman == 90) {
        if (substr($arq_array[$i], 0, 5) <> 'BSJI2') {
          continue;
        }
      } elseif (substr($arq_array[$i], 0, 1) <> "G") {
        if (substr($arq_array[$i], 0, 3) <> "104" and substr($arq_array[$i], 0, 3) <> "BSJ") {
          continue;
        }
      }
       
      if (substr($arq_array[$i], 0, 4) == 'BSJI') {
        if (substr($arq_array[0], 0, 5) == 'BSJI0' and $i == 1) {
          if (substr($k15_plano, 3, 3) == '002') {
            $dtarq = '20'.substr($arq_array[0], substr($k15_plano, 0, 3) - 1, substr($k15_plano, 3, 3));
          } else {
            $dtarq = substr($arq_array[0], substr($k15_plano, 0, 3) - 1, substr($k15_plano, 3, 3));
          }
           
          $dtarq .= "-".substr($arq_array[0], substr($k15_plmes, 0, 3) - 1, substr($k15_plmes, 3, 3));
          $dtarq .= "-".substr($arq_array[0], substr($k15_poslan, 0, 3) - 1, substr($k15_poslan, 3, 3));
           
        }
      } else {
        if (substr($k15_plano, 3, 3) == '002') {
          $dtarq = '20'.substr($arq_array[$i], substr($k15_plano, 0, 3) - 1, substr($k15_plano, 3, 3));
        } else {
          $dtarq = substr($arq_array[$i], substr($k15_plano, 0, 3) - 1, substr($k15_plano, 3, 3));
        }
         
        $dtarq .= "-".substr($arq_array[$i], substr($k15_plmes, 0, 3) - 1, substr($k15_plmes, 3, 3));
        $dtarq .= "-".substr($arq_array[$i], substr($k15_poslan, 0, 3) - 1, substr($k15_poslan, 3, 3));
         
      }
       
      if (substr($k15_ppano, 3, 3) == '002') {
        $dtpago = '20'.substr($arq_array[$i], substr($k15_ppano, 0, 3) - 1, substr($k15_ppano, 3, 3));
      } else {
        $dtpago = substr($arq_array[$i], substr($k15_ppano, 0, 3) - 1, substr($k15_ppano, 3, 3));
      }

      $dtpago .= "-".substr($arq_array[$i], substr($k15_ppmes , 0, 3) - 1, substr($k15_ppmes, 3, 3));
      $dtpago .= "-".substr($arq_array[$i], substr($k15_pospag, 0, 3) - 1, substr($k15_pospag, 3, 3));
       
       
      if ($dtpago == '0000-00-00') {
        $dtpago = $dtarquivo;
        $dtarq = $dtarquivo;
      }
       
      if (substr($k15_anocredito, 3, 3) == '002') {
        $dtcredito = '20'.substr($arq_array[$i], substr($k15_anocredito, 0, 3) - 1, substr($k15_anocredito, 3, 3));
      } else {
        $dtcredito = substr($arq_array[$i], substr($k15_anocredito, 0, 3) - 1, substr($k15_anocredito, 3, 3));
      }
      $dtcredito .= "-".substr($arq_array[$i], substr($k15_mescredito, 0, 3) - 1, substr($k15_mescredito, 3, 3));
      $dtcredito .= "-".substr($arq_array[$i], substr($k15_diacredito, 0, 3) - 1, substr($k15_diacredito, 3, 3));
       
       
      if (empty($dtcredito) || $dtcredito == '0000-00-00') {
        $dtcredito = $dtpago;
      }
       
      $vlrpago  = (substr($arq_array[$i], substr($k15_posvlr, 0, 3) - 1, substr($k15_posvlr, 3, 3)) / 100) + 0;
      $vlrjuros = (substr($arq_array[$i], substr($k15_posjur, 0, 3) - 1, substr($k15_posjur, 3, 3)) / 100) + 0;
      $vlrmulta = (substr($arq_array[$i], substr($k15_posmul, 0, 3) - 1, substr($k15_posmul, 3, 3)) / 100) + 0;
      $vlracres = (substr($arq_array[$i], substr($k15_posacr, 0, 3) - 1, substr($k15_posacr, 3, 3)) / 100) + 0;
      $vlrdesco = (substr($arq_array[$i], substr($k15_posdes, 0, 3) - 1, substr($k15_posdes, 3, 3)) / 100) + 0;
      $convenio =  substr($arq_array[$i], substr($k15_poscon, 0, 3) - 1, substr($k15_poscon, 3, 3));
      $cedente  =  substr($arq_array[$i], substr($k15_posced, 0, 3) - 1, substr($k15_posced, 3, 3));
       
      $totalvalorpago += $vlrpago;
       
    }
     
  } catch (Exception $oException) {
    db_msgbox("{$oException->getMessage()}");
  }

} else if (isset ($geradisbanco)) {

  $situacao = 2;
  $sWhere = " k15_codbco = {$k15_codbco} and k15_codage  = '{$k15_codage}' and k15_instit = {$iInstitSessao}";
  $rsCadBan = $clCadBan->sql_record($clCadBan->sql_query_file(null,"*",null,$sWhere));
  db_fieldsmemory($rsCadBan, 0);

  $arq_array = file($DOCUMENT_ROOT."/tmp/".$arqret);

  if ($arq_array == false) {
    db_msgbox("Erro ao acessar pasta: $DOCUMENT_ROOT/tmp/ do servidor! Verifique!");
    exit;
  }

}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="javascript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
  <?
  if ($situacao == "") {
    include ("forms/db_caiarq001.php");    
  } else if ($situacao == 1 and empty($codretexiste)) {
    include ("forms/db_caiarq002.php");
  } else if ($situacao == 2) {
    include ("forms/db_caiarq003.php");
  }
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
  ?>
</body>
</html>
<?
if ($situacao == 1) {
  
  if (isset ($codretexiste) && $codretexiste != "") {
    
    echo "<script>
    alert('J� Existe um Arquivo com este nome no sistema. \\\n Banco: $bancoexiste \\\n Agencia: $agenciaexiste \\\n Data: ".db_formatar($dtarquivoexiste, 'd')."');
    location.href='cai4_baixabanco001.php';
  </script>";
    flush();
  }

}

if ($situacao == 2) {

  echo "<script>
  function js_termometro(xvar){
  document.form1.processa.value = xvar;
}
</script>";

  flush();

  try {

    // grava arquivo disarq
    db_inicio_transacao();
     
     
    if (substr($k15_pdano, 3, 3) == '002') {
      $dtarquivo = '20'.substr($arq_array[0], substr($k15_pdano, 0, 3) - 1, substr($k15_pdano, 3, 3));
    } else {
      $dtarquivo = substr($arq_array[0], substr($k15_pdano, 0, 3) - 1, substr($k15_pdano, 3, 3));
    }
    $dtarquivo .= "-".substr($arq_array[0], substr($k15_pdmes, 0, 3) - 1, substr($k15_pdmes, 3, 3));
    $dtarquivo .= "-".substr($arq_array[0], substr($k15_posdta, 0, 3) - 1, substr($k15_posdta, 3, 3));
     
     
    if ($cgc == '88073291000199') { // bage
      if (substr($arqname, 0, 4) == "daeb") {
         
        if (substr($arq_array[0], 0, 3) == "826") {
          $dtarquivo = substr($arqname, 4, 8);
        }
         
      }
    }
     
    $clDisArq->id_usuario = db_getsession("DB_id_usuario");
    $clDisArq->k15_codbco = $k15_codbco;
    $clDisArq->k15_codage = $k15_codage;
    $clDisArq->arqret     = $arqname;
    $clDisArq->textoret   = "";
    $clDisArq->dtretorno  = date('Y-m-d', db_getsession("DB_datausu"));
    $clDisArq->dtarquivo  = $dtarquivo;
    $clDisArq->k00_conta  = $k15_conta;
    $clDisArq->autent     = "false";
    $clDisArq->instit     = $iInstitSessao;
    $clDisArq->md5        = db_getsession('disarq_md5');
    $clDisArq->incluir(null);
    if ($clDisArq->erro_status == "0") {
      $sMsg  = "Opera��o Abortada!\\n";
      $sMsg .= "[ 0 ] - Erro incluindo registros na disarq\\n";
      $sMsg .= "Erro: {$clDisArq->erro_msg}";
      throw new DBException($sMsg);
    }
     
    $codret = $clDisArq->codret;
     
    $achou_arrecant = 0;
     
    $sWhereCadBan = "k15_codbco = {$k15_codbco} and k15_codage = '{$k15_codage}' and k15_instit = {$iInstitSessao}";
    $sSqlCabBan   = $clCadBan->sql_query_file(null, "k15_seq, k15_poscon, k15_contat", null, $sWhereCadBan);
    $rsCadBan     = $clCadBan->sql_record($sSqlCabBan);
     
    db_fieldsmemory($rsCadBan, 0);
     
    $k15_numpreori = $k15_numpre;
    $k15_numparori = $k15_numpar;
    $priregistro = 1;
     
    if ($cgc == '88073291000199') { // bage
      if (substr($arqname, 0, 4) == "daeb") {
        $priregistro = 0;
      }
    }
     
    //
    // Processa Registros do Arquivo para Gravar em DISBANCO
    //
    $passou_pelo_t=true;
    $k15_numbco_ant = $k15_numbco;
     
    $total_tx_bancaria = 0;
     
    for ($i = $priregistro; $i <= $totalproc - ($priregistro == 0 ? 1 : 0); $i ++) {
       
      if ($iDebuga == 1) {
        echo "i: $i - cgc: $cgc - passou_pelo_t: $passou_pelo_t<br>";
      }
       
      $k15_numbco = $k15_numbco_ant;
      $tipo_convenio = "";
       
      // Testa tipo do registro
      if ($k15_taman == 242) {
        if (substr($arq_array[$i], 7, 1) != '3' and (substr($arq_array[$i], 13, 1) != 'U' and substr($arq_array[$i], 13, 1) != 'T')) {
          if ($iDebuga == 1) {
            echo "   continuando 1.00110011...<br>";
          }
          continue;
        }
        
        
      } elseif ($k15_taman == 402) {
        if (substr($arq_array[$i], 0, 1) == '9') {
          continue;
        }
         
      } elseif ($k15_taman == 90) {
        if (substr($arq_array[$i], 0, 5) <> 'BSJI2') {
          continue;
        }
      } elseif (substr($arq_array[$i], 0, 1) <> "G") {
        if (substr($arq_array[$i], 0, 3) <> "104" and substr($arq_array[$i], 0, 3) <> "BSJ") {
          continue;
        }
      }
       
      // grava arquivo disbanco
      $k15_numpre = $k15_numpreori;
      $k15_numpar = $k15_numparori;
       
      if (@$numpre == "") {
        $numpre = substr($arq_array[$i], substr($k15_numpre, 0, 3) - 1, substr($k15_numpre, 3, 3));
        $numpar = substr($arq_array[$i], substr($k15_numpar, 0, 3) - 1, substr($k15_numpar, 3, 3));
      }
       
      // bage
      if ($cgc == '88073291000199') {
        if (substr($numpre, 0, 2) == "00") {
          if (substr($arqname, 0, 4) == "daeb") {
            $k15_numpre = "034008";
            $k15_numpar = "042003";
          } else {
            $k15_numpre = "071008";
            $k15_numpar = "079003";
          }
        }
      }
       
      // itaqui
      if ($cgc == '88120662000146') {
        if (substr($numpre, 0, 2) == "00") {
          $k15_numpre = "071008";
          $k15_numpar = "079003";
        }
      }
       
      // osorio
      if ($cgc == '88814181000130') {
        if (substr($numpre, 0, 2) == "00")
        {
          $k15_numpre = "052008";
          $k15_numpar = "060003";
        }
      }
       
      // eldorado
      if ($cgc == '92324706000127') {
        if (substr($numpre, 0, 2) == "  ") {
          $k15_numbco = "069006";
        } else {
          $k15_numbco = "";
        }
      }
       
      //arroio
      if ($cgc == '91103093000135') {
        // numpre do sistema novo
        if (substr($numpre, 0, 2) == "00") {
          $k15_numpre = "071008";
          $k15_numpar = "079003";
        }
      }
       
      // capivari
      if ($cgc == '01610503000141') {
        // numpre do sistema novo
        if (substr($numpre, 0, 2) == "00") {
          $k15_numpre = "071008";
          $k15_numpar = "079003";
        }
      }
       
      // dom feliciano
      if ($cgc == '88601943000110') {
         
        // numpre do sistema novo
        if ( (int) substr($numpre, 0, 4) == 0 ) {
          $k15_numpre = "071008";
          $k15_numpar = "079003";
          $k15_numbco = ""; // comentado pelo evandro no dia 14/07/2010 as 21h
        } else { // sistema antigo
           
          $teste_numpre = (int) substr($arq_array[$i], 65, 1);
          $xxx          = substr($arq_array[$i], 60, 10);
           
          if ( $teste_numpre == 0 ) {
            $k15_numpre = "070011";
            $k15_numpar = "079003";
          }
           
        }
      }

      // araruama/rj
      if ($cgc == '28531762000133' and $k15_contat != 'BDL') {
         
        $k15_seq  = "";
        $convenio = "";
         
        if ($k15_codbco != 399 or ($k15_codbco == 399 and strcmp(trim($k15_codage), "1252") == 0) ) {
        $numbco   = "";
           
          // verificar se numpre eh do nosso sistema ou do anterior, posicao 65,5
          $isNumpre = (substr($arq_array[$i], 64, 5) === "00000");
           
          // Se nao eh numpre do e-cidade...
          if (!$isNumpre) {
             
            $iTipo = (int)substr($arq_array[$i], 64, 3);
             
            // Verifica se eh DAM do sistema anterior
            $isDam = ($iTipo == 803);
             
            if ($isDam) {
              $k15_numpre = "068014";
              $k15_numbco = $k15_numpre;
               
              $numbco   = substr($arq_array[$i], substr($k15_numbco, 0, 3) - 1, substr($k15_numbco, 3, 3));
              $k15_seq  = "";
              $convenio = "";
               
              //echo "Eh DAM do sistema anterior $numbco <br>";
            } else {
               
              /* Verificar REFIS em funcao da parcela com 3 digitos */
              if ($iTipo >= 201 and $iTipo <= 380) {
                $k15_numpre = "072008";
                 
                $numpre = substr($arq_array[$i], substr($k15_numpre, 0, 3) - 1, substr($k15_numpre, 3, 3));
                $numpar = (200 - $iTipo);
                 
              } else {
                $k15_numpre = "072008";
                $k15_numpar = "080002";
                 
                $numpre = substr($arq_array[$i], substr($k15_numpre, 0, 3) - 1, substr($k15_numpre, 3, 3));
                $numpar = substr($arq_array[$i], substr($k15_numpar, 0, 3) - 1, substr($k15_numpar, 3, 3));
              }
               
            }
             
          }
           
        }
      } // fim araruama/rj
       
      if ($cgc == '12198693000158') {
        // arapiraca
         
        if (substr($arq_array[$i], 13, 1) == 'T') {
           
          // acerto 2/2 para arapiraca em 14/04 por evandro
          if($k15_codage == '542-8' and substr($numpre,0,3) != '000'){
            if ($iDebuga == 1) {
              echo "   continuando 1.001 === 542-8...<br>";
            }
            $passou_pelo_t=false;
          }
        }
        
      }
       
      // coruripe sigcb
      if ($cgc == '12264230000147') {
         
        if (substr($arq_array[$i], 13, 1) == 'T') {
           
          if ($iDebuga == 1) {
            echo "   continuando 1.002001 === numpre: $numpre...<br>";
          }
           
          if($k15_codage == '2117S' and substr($numpre,0,3) != '000') {
            if ($iDebuga == 1) {
              echo "   continuando 1.002002 === 2117S...<br>";
            }
            //$passou_pelo_t = false;
            //para coruripe nao precisa, na realidade nao precisa para nenhum cliente sigcb/sicob a nao ser arapiraca
          }
        }
      }
       
      /*
       * Duplicado if de arapiraca para que o sistema consiga baixar debitos
      *   com convenio do tipo sicob pois atualmente ja funciona em arapiraca
      *   TAREFA 36779
      */
      // sapiranga
      if ($cgc == '87366159000102') {
        // sapiranga
        if (substr($arq_array[$i], 13, 1) == 'T' or substr($arq_array[$i], 13, 1) == 'U') {
           
          if ($iDebuga == 1) {
            echo "      continuando 1.1 - k15_codage: $k15_codage<br>";
          }
           
          if($k15_codage == '00514' and $k15_codbco != '104'){
            $tipo_convenio = "sigcb";
            $passou_pelo_t=false;
          }
          
        }
         
      }
       
      // SIGCB - k15_contat
      if ($k15_contat == 'BDL' or $k15_contat == 'SIGCB') {
         
        if (substr($arq_array[$i], 13, 1) == 'T' or substr($arq_array[$i], 13, 1) == 'U') {
          $tipo_convenio = strtolower($k15_contat);
          $passou_pelo_t=false;
           
        }
        
        if (substr($arq_array[$i], 13, 1) == 'U' and substr($arq_array[$i],15,2) == '50' ) {
            
          $numpre = "";
          $numpar = "";
          continue;
        
        }
        
      }
       
      // itaqui sigcb
      if ($cgc == '88120662000146') {
         
        if (substr($arq_array[$i], 13, 1) == 'T' or substr($arq_array[$i], 13, 1) == 'U') {
           
          if ($iDebuga == 1) {
            echo "      continuando 1.2 - k15_codage: $k15_codage<br>";
            echo "   continuando 1.003003 === ITAQUI ( 104/0484X )... - tipo_convenio: $tipo_convenio<br>";
          }
           
          if($k15_codage == '0484X' and substr($numpre,0,3) != '000'){
            $tipo_convenio = "sigcb";
          }
        }
      }
       
      // arapiraca sigcb
      if ($cgc == '12198693000158') {
         
        if ($iDebuga == 1) {
          echo "      continuando 1.3<br>";
        }
         
        if ( substr($arq_array[$i], 13, 1) == 'T' or substr($arq_array[$i], 13, 1) == 'U' ) {
           
          if ($iDebuga == 1) {
            echo "      continuando 1.4 - k15_codage: $k15_codage<br>";
          }
           
          if ($k15_codage == '0056') {
            if ($iDebuga == 1) {
              echo "      passando para false 1... numpre: $numpre<br>";
            }
            $tipo_convenio = "sigcb";
          }
        }
      }
       
      // coruripe sigcb
      if ($cgc == '12264230000147') {
         
        if ($iDebuga == 1) {
          echo "      continuando 1.5<br>";
        }
         
        if ( substr($arq_array[$i], 13, 1) == 'T' or substr($arq_array[$i], 13, 1) == 'U' ) {
           
          if ($iDebuga == 1) {
            echo "      continuando 1.6 - k15_codage: $k15_codage<br>";
          }
           
          if ($k15_codage == '2117S') {
            if ($iDebuga == 1) {
              echo "      passando para false 1... numpre: $numpre<br>";
            }
            $tipo_convenio = "sigcb";
          }
        }
      }

      if ($k15_taman == 242) {
         
        if (substr($arq_array[$i], 13, 1) == 'T') {
          $numbco = substr($arq_array[$i], substr($k15_numbco, 0, 3) - 1, substr($k15_numbco, 3, 3));
          $convenio = substr($arq_array[$i], substr($k15_poscon, 0, 3) - 1, substr($k15_poscon, 3, 3));
          if ($iDebuga == 1) {
            echo "   continuando 5...<br>";
          }
          continue;
        }
         
      } else {
        $convenio = substr($arq_array[$i], substr($k15_poscon, 0, 3) - 1, substr($k15_poscon, 3, 3));
        $numbco = substr($arq_array[$i], substr($k15_numbco, 0, 3) - 1, substr($k15_numbco, 3, 3));
      }
       
      // dom feliciano
      if ($cgc == '88601943000110') {
         
        // numpre do sistema antigo
        if ( (int) substr($numpre, 0, 4) != 0 ) {
           
          $teste_numpre = (int) substr($arq_array[$i], 65, 1);

          if ( $teste_numpre == 0 ) {
            $k15_numbco = "071011";
            $numbco = substr($arq_array[$i], substr($k15_numbco, 0, 3) - 1, substr($k15_numbco, 3, 3));
          }
        }
      }
       
      if ($iDebuga == 1) {
        echo "   continuando 6...<br>";
      }
       
      if ($numbco != "") {
         
        if ($iDebuga == 1) {
          echo "   continuando 6.1...<br>";
        }
         
        $numbco = $k15_seq.$convenio.$numbco;
         
        $sSqlBuscaArrebanco  = "select arrebanco.k00_numpre as numpre, ";
        $sSqlBuscaArrebanco .= "       arrebanco.k00_numpar as numpar ";
        $sSqlBuscaArrebanco .= "  from arrebanco ";
        $sSqlBuscaArrebanco .= "       inner join arreinstit on arreinstit.k00_numpre = arrebanco.k00_numpre ";
        $sSqlBuscaArrebanco .= " where arrebanco.k00_numbco  = trim('".trim($numbco)."')";
        $sSqlBuscaArrebanco .= "   and arreinstit.k00_instit = $iInstitSessao ";
        $rsArrebanco = db_query($sSqlBuscaArrebanco);
         
        if (pg_numrows($rsArrebanco) == 0) {
          $numpre = 0;
          $numpar = 0;
           
           
          /**
           * Verificamos se o cliente � Canela e realizamos uma l�gica diferente para tentar encontrar o arrebanco do registro a ser processado
           *
           * L�gica implementada devido a problemas na gera��o do arrebanco onde foram trocadas algumas posi��es indevidamente
           *
           */
          if ($cgc == "88585518000185") {
             
            $sSqlArrebanco  = "select arrebanco.k00_numpre as numpre, ";
            $sSqlArrebanco .= "       arrebanco.k00_numpar as numpar";
            $sSqlArrebanco .= "  from arrebanco ";
            $sSqlArrebanco .= " where substr(k00_numbco,1,2)||substr(k00_numbco,5,1)||substr(k00_numbco,7,1)||substr(k00_numbco,6,1)||substr(k00_numbco,7,1)||substr(k00_numbco,8,1) = '".substr($numbco,0,7)."'";
            $sSqlArrebanco .= "   and substr(k00_numbco,9,3) = '".substr($numbco,8,3)."'";
            $sSqlArrebanco .= "   and substr(k00_numbco,1,4) = '8282'";
            $rsArrebanco = db_query($sSqlArrebanco);
            if (pg_num_rows($rsArrebanco) == 1) {
              db_fieldsmemory($rsArrebanco, 0);
            }
             
          }
           
        } else  {
          db_fieldsmemory($rsArrebanco, 0);
        }
         
      } else {
         
        if ($iDebuga == 1) {
          echo "   continuando 6.2...<br>";
        }
         
        $processaposnumpre = true;
         
        // Arapiraca
        if ($numpre <> "" and $cgc=='12198693000158') {
          $processaposnumpre = false;
        }
         
        // Coruripe
        if ($numpre <> "" and $cgc=='12264230000147') {
          $processaposnumpre = false;
        }
         
        // Alegrete
        if ($numpre <> "" and $cgc=='87896874000157') {
          $processaposnumpre = false;
        }
         
        if ( $k15_contat == "BDL" or $k15_contat == "SIGCB" ) {
          $processaposnumpre = false;
        }
         
        /*
         * Duplicado if de arapiraca para que o sistema consiga baixar debitos
        *   com convenio do tipo sicob pois atualmente ja funciona em arapiraca
        *   TAREFA 36779
        */
        // Sapiranga
        if ($numpre <> "" and $cgc=='87366159000102') {
          $processaposnumpre = false;
        }
         
        if( $processaposnumpre==true) {
          $numpre = substr($arq_array[$i], substr($k15_numpre, 0, 3) - 1, substr($k15_numpre, 3, 3));
          $numpar = substr($arq_array[$i], substr($k15_numpar, 0, 3) - 1, substr($k15_numpar, 3, 3));
        }
         
      }
       
      if ($iDebuga == 1) {
        echo "      continuando 7 - tipo_convenio: $tipo_convenio<br>";
      }
       
      if ($k15_codage === "00110" and $k15_codbco == 41) {
        $sqlbanco  = "select arrebanco.k00_numpre, arrebanco.k00_numpar ";
        $sqlbanco .= "  from arrebanco ";
        $sqlbanco .= "       inner join arreinstit on arreinstit.k00_numpre = arrebanco.k00_numpre ";
        $sqlbanco .= " where arrebanco.k00_numbco = '".substr($arq_array[$i], ( (int) substr($k15_numbco, 0, 3) ) - 1, ( (int) substr($k15_numbco, 3, 3) ) )."'";
        $sqlbanco .= "   and arreinstit.k00_instit = $iInstitSessao ";
        $resultbanco = db_query($sqlbanco) or die($sqlbanco);
        if (pg_numrows($resultbanco) == 0) {
          echo "<script>alert('[1] Numbco ".substr($arq_array[$i], 6, 13)." nao encontrado!');</script>";
        } else {
          db_fieldsmemory($resultbanco, 0, true);
          $numpre = $k00_numpre;
          $numpar = $k00_numpar;
        }
      } else if ($k15_codage === "00712" and $k15_codbco == 41 and $cgc === "01610503000141" ) {
         
        // Para CAPIVARI
         
        $sqlbanco  = "select arrebanco.k00_numpre, arrebanco.k00_numpar ";
        $sqlbanco .= "  from arrebanco ";
        $sqlbanco .= "       inner join arreinstit on arreinstit.k00_numpre = arrebanco.k00_numpre ";
        $sqlbanco .= " where arrebanco.k00_numbco = '".substr($arq_array[$i], ( (int) substr($k15_numbco, 0, 3) ) - 1, ( (int) substr($k15_numbco, 3, 3) ) )."'";
        $sqlbanco .= "   and arreinstit.k00_instit = $iInstitSessao ";
        $resultbanco = db_query($sqlbanco) or die($sqlbanco);
        if (pg_numrows($resultbanco) == 0) {
          echo "<script>alert('[3] Numbco $numbco nao encontrado [procurando arrebanco por $numbco]!');</script>";
        } else {
          db_fieldsmemory($resultbanco, 0, true);
          $numpre = $k00_numpre;
          $numpar = $k00_numpar;
        }
         
         
         
         
      } elseif ($tipo_convenio == "sigcb" or $tipo_convenio == "bdl" ) {
         
        if ($iDebuga == 1) {
          echo "      continuando 8 ( numbco: $numbco )...<br>";
        }
         
        $numbco = trim($numbco);
         
        if ($iDebuga == 1) {
          echo "         continuando 8.1 ( numbco: $numbco )...<br>";
        }
         
        $sSqlArrebanco  = "select arrebanco.k00_numpre, arrebanco.k00_numpar ";
        $sSqlArrebanco .= "  from arrebanco ";
        $sSqlArrebanco .= "       inner join arreinstit on arreinstit.k00_numpre = arrebanco.k00_numpre ";
        $sSqlArrebanco .= " where arrebanco.k00_numbco = '$numbco'";
        $sSqlArrebanco .= "   and arreinstit.k00_instit = $iInstitSessao ";
         
        $rsArrebanco = db_query($sSqlArrebanco);
        if (pg_numrows($rsArrebanco) == 0) {
          echo "<script>alert('[2] Numbco {$numbco} nao encontrado!');</script>";
        } else {
          db_fieldsmemory($rsArrebanco, 0, true);
          $numpre = $k00_numpre;
          $numpar = $k00_numpar;
        }
         
        if ($iDebuga == 1) {
          echo "            continuando 8.2 ( numbco: $numbco )...<br>";
        }
         
      }
       
      if (substr($arq_array[0], 0, 5) == 'BSJI0') {
         
        if (substr($k15_plano, 3, 3) == '002') {
          $dtarq = '20'.substr($arq_array[0], substr($k15_plano, 0, 3) - 1, substr($k15_plano, 3, 3));
        } else {
          $dtarq = substr($arq_array[0], substr($k15_plano, 0, 3) - 1, substr($k15_plano, 3, 3));
        }
        $dtarq .= "-".substr($arq_array[0], substr($k15_plmes, 0, 3) - 1, substr($k15_plmes, 3, 3));
        $dtarq .= "-".substr($arq_array[0], substr($k15_poslan, 0, 3) - 1, substr($k15_poslan, 3, 3));
         
      } else {
         
        if (substr($k15_plano, 3, 3) == '002') {
          $dtarq = '20'.substr($arq_array[$i], substr($k15_plano, 0, 3) - 1, substr($k15_plano, 3, 3));
        } else {
          $dtarq = substr($arq_array[$i], substr($k15_plano, 0, 3) - 1, substr($k15_plano, 3, 3));
        }
        $dtarq .= "-".substr($arq_array[$i], substr($k15_plmes, 0, 3) - 1, substr($k15_plmes, 3, 3));
        $dtarq .= "-".substr($arq_array[$i], substr($k15_poslan, 0, 3) - 1, substr($k15_poslan, 3, 3));
         
      }
       
      if (substr($k15_ppano, 3, 3) == '002') {
        $dtpago = '20'.substr($arq_array[$i], substr($k15_ppano, 0, 3) - 1, substr($k15_ppano, 3, 3));
      } else {
        $dtpago = substr($arq_array[$i], substr($k15_ppano, 0, 3) - 1, substr($k15_ppano, 3, 3));
      }
      $dtpago .= "-".substr($arq_array[$i], substr($k15_ppmes, 0, 3) - 1, substr($k15_ppmes, 3, 3));
      $dtpago .= "-".substr($arq_array[$i], substr($k15_pospag, 0, 3) - 1, substr($k15_pospag, 3, 3));
       
       
      if ($dtpago == '0000-00-00') {
        $dtpago = $dtarquivo;
        $dtarq  = $dtarquivo;
      }
       
      if (substr($k15_anocredito, 3, 3) == '002') {
        $dtcredito = '20'.substr($arq_array[$i], substr($k15_anocredito, 0, 3) - 1, substr($k15_anocredito, 3, 3));
      } else {
        $dtcredito  = substr($arq_array[$i], substr($k15_anocredito, 0, 3) - 1, substr($k15_anocredito, 3, 3));
      }
       
      $dtcredito .= "-".substr($arq_array[$i], substr($k15_mescredito, 0, 3) - 1, substr($k15_mescredito, 3, 3));
      $dtcredito .= "-".substr($arq_array[$i], substr($k15_diacredito, 0, 3) - 1, substr($k15_diacredito, 3, 3));
       
       
      if (empty($dtcredito) || $dtcredito == '0000-00-00') {
        $dtcredito = $dtpago;
      }
       
      $vlrpago  = (substr($arq_array[$i], substr($k15_posvlr, 0, 3) - 1, substr($k15_posvlr, 3, 3)) / 100) + 0;
      $vlrjuros = (substr($arq_array[$i], substr($k15_posjur, 0, 3) - 1, substr($k15_posjur, 3, 3)) / 100) + 0;
      $vlrmulta = (substr($arq_array[$i], substr($k15_posmul, 0, 3) - 1, substr($k15_posmul, 3, 3)) / 100) + 0;
      $vlracres = (substr($arq_array[$i], substr($k15_posacr, 0, 3) - 1, substr($k15_posacr, 3, 3)) / 100) + 0;
      $vlrdesco = (substr($arq_array[$i], substr($k15_posdes, 0, 3) - 1, substr($k15_posdes, 3, 3)) / 100) + 0;
      $cedente = $convenio;
      $convenio = "";
       
      /*
       * D A E B
      *
      * Caso o cnpj seja da institui��o DAEB
      * Inclu�mos o arquivo cai4_baixabanco_daeb.php que possui a l�gica pr�pria para o processamento dos arquivos
      * de baixa banc�ria destinbados ao DAEB.
      */
      if ($cgc == '90940172000138') {
         
        include("cai4_baixabanco_daeb.php");
        continue;
         
      }

      if ($k15_codage == '88888') {
         
        $sqlverresult = "select arrematric.k00_numpre,
        numpremigra.k00_numpar as numpre_migra,
        arrematric.k00_matric
        from numpremigra
        inner join arrematric on arrematric.k00_matric = numpremigra.k00_matric
        where numpremigra.k00_numpre = $convenio";
        $verresult = db_query($sqlverresult);
        if (pg_numrows($verresult) != false) {
          $numpre_migra = pg_result($verresult, 0, 0);
          $numpar = pg_result($verresult, 0, 1);
          $matric = pg_result($verresult, 0, 2);
        }
         
        $sqlverresult = "select k00_numpar
        from numpremigra
        where numpremigra.k00_numpre = $convenio";
        $verresult = db_query($sqlverresult);
        if (pg_result($verresult, 0) == "0") { // unica
          $sqlverresult = "select arrecad.k00_numpre,
          arrecad.k00_numpar,
          sum(arrecad.k00_valor) as k00_valor
          from numpremigra
          inner join arrematric on arrematric.k00_matric = numpremigra.k00_matric
          inner join arrecad    on arrecad.k00_numpre    = arrematric.k00_numpre
          where numpremigra.k00_numpre = $convenio
          and arrecad.k00_tipo = 5
          and k00_dtoper >= '2004-01-01'
          group by arrecad.k00_numpre, arrecad.k00_numpar";
        } else {
          $sqlverresult = "select arrecad.k00_numpre,
          arrecad.k00_numpar,
          sum(arrecad.k00_valor) as k00_valor
          from numpremigra
          inner join arrematric on arrematric.k00_matric = numpremigra.k00_matric
          inner join arrecad    on arrecad.k00_numpre    = arrematric.k00_numpre
          and arrecad.k00_numpar    = numpremigra.k00_numpar
          where numpremigra.k00_numpre = $convenio
          and arrecad.k00_tipo = 5
          and k00_dtoper >= '2004-01-01'
          group by arrecad.k00_numpre, arrecad.k00_numpar";
        }
         
        $verresult = db_query($sqlverresult);
        if (pg_numrows($verresult) != false) {
          $numpre = pg_result($verresult, 0, 0);
        }
         
        if (pg_numrows($verresult) > 0) {
           
          for ($xresult = 0; $xresult < pg_numrows($verresult); $xresult ++) {
            $xtotal += pg_result($verresult, $xresult, 2);
          }
          $xxtotal = 0;
           
          for ($xresult = 0; $xresult < pg_numrows($verresult); $xresult ++) {
            $xpago = pg_result($verresult, $xresult, 2);
            $numpre = pg_result($verresult, $xresult, 0);
            $numpar = pg_result($verresult, $xresult, 1);
            $vlrpagonew = round($vlrpago * ($xpago / $xtotal), 2);
            $xxtotal += $vlrpagonew;
             
            if ($xresult == pg_numrows($verresult) - 1) {
              $diferenca = $vlrpago - $xxtotal;
              $vlrpagonew += $diferenca;
              $xxtotal += $diferenca;
            }

            $numpar = trim($numpar);
            $clDisBanco->codret     = $codret;
            $clDisBanco->k15_codbco = $k15_codbco;
            $clDisBanco->k15_codage = $k15_codage;
            $clDisBanco->k00_numbco = $numbco;
            $clDisBanco->dtarq      = $dtarq;
            $clDisBanco->dtpago     = $dtpago;
            $clDisBanco->dtcredito  = $dtcredito;
            $clDisBanco->vlrpago    = "$vlrpagonew";
            $clDisBanco->vlrjuros   = "$vlrjuros";
            $clDisBanco->vlrmulta   = "$vlrmulta";
            $clDisBanco->vlracres   = "$vlracres";
            $clDisBanco->vlrdesco   = "$vlrdesco";
            $clDisBanco->vlrcalc    = "$vlrpago+$vlrjuros+$vlrmulta+$vlracres-$vlrdesco";
            $clDisBanco->cedente    = $cedente;
            $clDisBanco->vlrtot     = "$vlrpagonew+$vlrjuros+$vlrmulta+$vlracres-$vlrdesco";
            $clDisBanco->classi     = "false";
            $clDisBanco->k00_numpre = "".($numpre+0)."";
            $clDisBanco->k00_numpar = "".($numpar+0)."";
            $clDisBanco->convenio   = $convenio;
            $clDisBanco->instit     = $iInstitSessao;
            $clDisBanco->incluir(null);
            if ($clDisBanco->erro_status == "0") {
              $sMsg  = "Opera��o Abortada!\\n";
              $sMsg .= "[ 1 ] - Erro incluindo registros na disbanco\\n";
              $sMsg .= "Erro: {$clDisBanco->erro_msg}";
              throw new DBException($sMsg);
            }
            $idRet = $clDisBanco->idret;
             
            echo "<script>js_termometro(".$i.");</script>";
            flush();
            echo "<br>xtotal: $xtotal - xxtotal: $xxtotal - vlrpago: $vlrpago - vlrpagonew: $vlrpagonew<br>";
          }
           
        } else {
           
          $achou_arrecant = 1;
          $sqlverresult = "select arrecant.k00_numpre,
          arrecant.k00_numpar,
          sum(arrecant.k00_valor) as k00_valor
          from numpremigra
          inner join arrematric on arrematric.k00_matric = numpremigra.k00_matric
          inner join arrecant    on arrecant.k00_numpre    = arrematric.k00_numpre and arrecant.k00_numpar = numpremigra.k00_numpar
          where numpremigra.k00_numpre = $convenio
          and arrecant.k00_tipo = 5
          and k00_dtoper >= '2004-01-01'
          group by arrecant.k00_numpre, arrecant.k00_numpar";
          $verresult = db_query($sqlverresult);
          if (pg_numrows($verresult) > 0) {
            echo "<br>passou arrecant... xxtotal: $xxtotal - convenio: $convenio - numpre_migra: $numpre_migra - numpar: $numpar - matric: $matric<br>";
          }
        }
         
      } else {
         
        if ($cgc == '88585518000185') { // canela

          $nTaxaBanco = 2.35;
          if ($vlrpago > 2) {

            if ( (int) $k15_codbco == 41 and $k15_codage == '555' ) { // IPTU
              $vlrpago = $vlrpago - $nTaxaBanco;
            } elseif ( (int) $k15_codbco == 41 and $k15_codage == '0555' ) { // ISSQN
              $vlrpago = $vlrpago - $nTaxaBanco;
            }
          }
           
        } else if ($cgc == '28531762000133') { // araruama/rj
           
          $numpre_procura = $numpre;
          $sql_recibo     = "select k00_numpre from recibopaga where k00_numnov = $numpre_procura limit 1";
          $result_recibo  = db_query($sql_recibo);
           
          if (pg_numrows($result_recibo) > 0) {
            $numpre_procura = pg_result($result_recibo, 0);
          }
           
          $sql_arretipo    = " (select k00_tipo from arrecad    where k00_numpre = $numpre_procura limit 1) ";
          $sql_arretipo   .= "   union                                                                      ";
          $sql_arretipo   .= " (select k00_tipo from arrecant   where k00_numpre = $numpre_procura limit 1) ";
          $sql_arretipo   .= "   union                                                                      ";
          $sql_arretipo   .= " (select k00_tipo from arreold    where k00_numpre = $numpre_procura limit 1) ";
          $sql_arretipo   .= "   union                                                                      ";
          $sql_arretipo   .= " (select k00_tipo from arreforo   where k00_numpre = $numpre_procura limit 1) ";
          $sql_arretipo   .= "   union                                                                      ";
          $sql_arretipo   .= " (select k30_tipo from arreprescr where k30_numpre = $numpre_procura limit 1) ";
          $sql_arretipo   .= "   union                                                                      ";
          $sql_arretipo   .= " (select k00_tipo from recibo     where k00_numpre = $numpre_procura limit 1) ";
          $result_arretipo = db_query($sql_arretipo);

          if (pg_numrows($result_arretipo)>0) {
            $k00_tipo = pg_result($result_arretipo, 0);
          } else {
            $k00_tipo = 1; // Forca ser o ARRETIPO 1-IPTU caso NAO ENCONTRE o tipo de debito do NUMPRE
          }

          $sSqlGrupoDebito = "select k03_tipo from cadtipo natural join arretipo where k00_tipo = $k00_tipo";
          $rsGrupoTipo     = db_query($sSqlGrupoDebito);
          
          $iGrupoTipo      = db_utils::fieldsMemory($rsGrupoTipo, 0)->k03_tipo;
                    
          if ($iGrupoTipo == 8) {

            $oDaoParITBI     = new cl_paritbi(); 
            $oData           = new DBDate($dtpago);
            $iAnoPagamento   = $oData->getAno();
            $rsTxParItbi     = db_query($oDaoParITBI->sql_query($iAnoPagamento, 'it24_taxabancaria'));
                    
            if (pg_numrows($rsTxParItbi) > 0) {
              $k00_txban = db_utils::fieldsMemory($rsTxParItbi, 0)->it24_taxabancaria;
            } else {
              $k00_txban = 0;
            }
          } else {

            $sql_arretipo    = "select k00_txban from arretipo where k00_tipo = $k00_tipo and k00_instit = $iInstitSessao";
            $result_arretipo = db_query($sql_arretipo);
                      
            if (pg_numrows($result_arretipo)>0) {
              $k00_txban = pg_result($result_arretipo, 0);
            } else {
              $k00_txban = 0;
            }
         }          
           
          if ( (float)$vlrpago > $k00_txban ) {

            $total_tx_bancaria += $k00_txban;
            $vlrpago           -= $k00_txban;
          }
           
          $nTaxaExpediente = $k00_txban;
          $nTarifaBancaria = $k15_txban;
           
          $clDisBancoTXT->k34_numpremigra = (string) $numpre;
          $clDisBancoTXT->k34_valor       = (string) ($nTaxaExpediente+0);
          $clDisBancoTXT->k34_dtvenc      = $dtpago;
          $clDisBancoTXT->k34_dtpago      = $dtpago;
          $clDisBancoTXT->k34_codret      = $codret;
          $clDisBancoTXT->k34_diferenca   = $nTarifaBancaria+0;
          $clDisBancoTXT->incluir(null);
          if ($clDisBancoTXT->erro_status == "0") {
            $sMsg  = "Opera��o Abortada!\\n";
            $sMsg .= "[ 3 ] - Erro incluindo registros na disbancotxt\\n";
            $sMsg .= "Linha do TXT : [".$arq_array[$i]."]\\n";
            $sMsg .= "Erro: {$clDisBancoTXT->erro_msg}";
            throw new DBException($sMsg);
          }
          $k34_sequencial = $clDisBancoTXT->k34_sequencial;
           
          $vlracres = 0;
          $vlrdesco = 0;
           
        }

        $clDisBanco->codret     = $codret;
        $clDisBanco->k15_codbco = $k15_codbco;
        $clDisBanco->k15_codage = $k15_codage;
        $clDisBanco->k00_numbco = $numbco;
        $clDisBanco->dtarq      = $dtarq;
        $clDisBanco->dtpago     = $dtpago;
        $clDisBanco->dtcredito  = $dtcredito;
        $clDisBanco->vlrpago    = "$vlrpago";
        $clDisBanco->vlrjuros   = "$vlrjuros";
        $clDisBanco->vlrmulta   = "$vlrmulta";
        $clDisBanco->vlracres   = "$vlracres";
        $clDisBanco->vlrdesco   = "$vlrdesco";
        $clDisBanco->vlrcalc    = "$vlrpago+$vlrjuros+$vlrmulta+$vlracres-$vlrdesco";
        $clDisBanco->cedente    = $cedente;
        $clDisBanco->vlrtot     = "$vlrpago+$vlrjuros+$vlrmulta+$vlracres-$vlrdesco";
        $clDisBanco->classi     = "false";
        $clDisBanco->k00_numpre = "".($numpre+0)."";
        $clDisBanco->k00_numpar = "".($numpar+0)."";
        $clDisBanco->convenio   = $convenio;
        $clDisBanco->instit     = $iInstitSessao;
        $clDisBanco->incluir(null);
        if ($clDisBanco->erro_status == "0") {
          $sMsg  = "Opera��o Abortada!\\n";
          $sMsg .= "Numpre: {$clDisBanco->k00_numpre}\\n";
          $sMsg .= "[ 2 ] - Erro incluindo registros na disbanco\\n";
          $sMsg .= "Erro: {$clDisBanco->erro_msg}";
          throw new DBException($sMsg);
        }
        $idRet = $clDisBanco->idret;
         
        if ($cgc == '28531762000133') { // araruama/rj
           
          $clDisBancoTXTReg->k35_disbancotxt = $k34_sequencial;
          $clDisBancoTXTReg->k35_idret       = $idRet;
          $clDisBancoTXTReg->incluir(null);
          if ($clDisBancoTXTReg->erro_status == "0") {
            $sMsg  = "Opera��o Abortada!\\n";
            $sMsg .= "[ 4 ] - Erro incluindo registros na disbancotxtreg\\n";
            $sMsg .= "Erro: {$clDisBancoTXTReg->erro_msg}";
            throw new DBException($sMsg);
          }
           
        }
         
        echo "<script>js_termometro(". $i.");</script>";
        flush();
         
        $numpre = "";
        $numpar = "";
         
      }

    }
     
    if ($iDebuga == 1) {
      echo "<br>F I M<br>";
      exit;
    }
     
    $sql = "select dtarq,
    sum(vlrpago)
    from disbanco
    where codret = $codret
    and instit = $iInstitSessao group by dtarq";
    $result = db_query($sql);
     
    $total = 0;
     
    for ($x = 0; $x < pg_numrows($result); $x ++) {
       
      db_fieldsmemory($result, $x, true);
      echo "data: $dtarq - valor: ".db_formatar($sum, "f")."<br>";
      $total += $sum;
    }
    echo "total: ".db_formatar($total, "f")." - codret: $codret<br>";
     
    if ($cgc == '28531762000133') { // araruama/rj
      echo "total taxa bancaria: ".db_formatar($total_tx_bancaria, "f")."<br>";
      echo "total arquivo: ".db_formatar($total+$total_tx_bancaria, "f")."<br>";
    }
     
    if ($achou_arrecant == 0) {
       
      /*
       * Verificamos a forma de processamento do arquivo txt
      *
      * 0 - Classifica��o por arquivo:           Gerado somente um registros na disarq
      * 1 - Classifica��o por data do Pagamento: Gerado mais de um registro na disarq de acordo com a quantidade
      *                                          de datas de pagamentos encontradas no arquivo (campo: dtpago)
      * 2 - Classifica��o por data de Cr�dito:   Gerado mais de um registro na disarq de acordo com a quantidade
      *                                          de datas de cr�ditos encontradas no arquivo (campo: dtcredito)
      */
      $oDaoNumpref = db_utils::getDao("numpref");
      $sSqlProcessamentoArquivoTXT = $oDaoNumpref->sql_query_file(db_getsession("DB_anousu"), db_getsession("DB_instit"), "k03_agrupadorarquivotxtbaixabanco");
      $rsProcessamentoArquivoTXT   = $oDaoNumpref->sql_record($sSqlProcessamentoArquivoTXT);
      $iTipoProcessamento          = db_utils::fieldsMemory($rsProcessamentoArquivoTXT,0)->k03_agrupadorarquivotxtbaixabanco;
      if ($iTipoProcessamento == 1 || $iTipoProcessamento == 2) {
         
        $iCodRet = $codret;
        include_once("cai4_desmembramentodisbanco001.php");

      }
       
      db_fim_transacao(false);
      unset($_POST);
      db_msgbox("Documento processado!");
      db_redireciona();
       
    } else {
      throw new BusinessException("Documento nao processado porque tem pagamentos!");
    }

  } catch (DBException $eErro){          // DB Exception

    db_fim_transacao(true);
    echo $eErro->getMessage();
    db_msgbox($eErro->getMessage());
     
  } catch (BusinessException $eErro){     // Business Exception
     
    db_fim_transacao(true);
    db_msgbox($eErro->getMessage());
     
  } catch (ParameterException $eErro){     // Parameter Exception
     
    db_fim_transacao(true);
    db_msgbox($eErro->getMessage());
     
  } catch (Exception $eErro){

    db_fim_transacao(true);
    db_msgbox($eErro->getMessage());
  }

}
?>