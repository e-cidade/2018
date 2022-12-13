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

/**
 * Variável Especifica de Debug da rotina
 */
$lDebugMaricaAtivo = false;

if (isset ($processar)) {

  try {

  	 /**
      * Recebe codbco,codage,tamanho
      * Verifica banco e agencia
      */
     db_postmemory($_FILES["arqret"]);

     $arq_name    = basename($name);
     $arq_type    = $type;
     $arq_tmpname = basename($tmp_name);
     $arq_size    = $size;
     $arq_array   = file($tmp_name);
		 $sMd5Arquivo = md5(file_get_contents($tmp_name));

		 db_putsession('disarq_md5', $sMd5Arquivo);

     /**
      * Verifica se arquivo já foi importado
      */
     $sSqlArquivoImportado = $clDisArq->sql_query_file(null, 'true', null, "md5 = '$sMd5Arquivo'");
     $rsArquivoImportado   = $clDisArq->sql_record($sSqlArquivoImportado);

     if ($clDisArq->numrows > 0) {
     	 throw new BusinessException( _M( MENSAGENS . "arquivo_importado" ) );
     }

     system("cp -f ".$tmp_name." " . ECIDADE_PATH ." tmp/", $intret);

     if($intret != 0) {

       $oParametrosMsg               = new stdClass();
       $oParametrosMsg->sNomeArquivo = $tmp_name;
       $sMsg                         = _M( MENSAGENS . 'erro_ao_copiar_arquivo', $oParametrosMsg);
       throw new Exception($sMsg);
     }

     $sWhere     = "     k15_codbco = {$k15_codbco}    ";
     $sWhere    .= " and k15_codage = '{$k15_codage}'  ";
     $sWhere    .= " and k15_instit = {$iInstitSessao} ";
     $sSqlCadBan = $clCadBan->sql_query(null,"*",null,$sWhere);
     $rsCadBan   = $clCadBan->sql_record($sSqlCadBan);

     if ($clCadBan->numrows == 0) {
       throw new Exception( _M( MENSAGENS . "banco_agencia_nao_encontrados" ) );
     }

     db_fieldsmemory($rsCadBan, 0);

     $_tamanprilinha = $arq_array[0];
     $atipo          = substr($arq_array[0], 0, 3);
     $totalproc      = sizeof($arq_array) - 2;
     $priregistro    = 1;
     $acodbco        = substr($arq_array[0], substr($k15_posbco, 0, 3), substr($k15_posbco, 3, 3));

     $k15_codbco     = (int) $k15_codbco;
     $acodbco        = (int) $acodbco;

     if ( strlen($_tamanprilinha) != $k15_taman ) {

       $oParametrosMsg                   = new stdClass();
       $oParametrosMsg->sTamanhoRegistro = strlen($arq_array[0]);
       $oParametrosMsg->sSistema         = $k15_taman;
       $sMsg                             = _M( MENSAGENS . 'tamanho_sistema_invalido', $oParametrosMsg);
       throw new Exception($sMsg);
     }

     if ($k15_codbco != $acodbco && $atipo != "BSJ") {

       $oParametrosMsg                   = new stdClass();
       $oParametrosMsg->sBancoDigitado   = $k15_codbco;
       $oParametrosMsg->sArquivoCodBanco = $acodbco;
       $sMsg                             = _M( MENSAGENS . 'banco_digitado_nao_confere', $oParametrosMsg);
       throw new Exception($sMsg);
     }

     $situacao = 1;
     $sCampos  = "codret as codretexiste,      ";
     $sCampos .= "k15_codbco as bancoexiste,   ";
     $sCampos .= "k15_codage as agenciaexiste, ";
     $sCampos .= "dtarquivo as dtarquivoexiste ";
     $rsDisArq = $clDisArq->sql_record($clDisArq->sql_query_file( null,
                                                                  $sCampos,
                                                                  null,
                                                                  "arqret = '$arq_name' and instit = $iInstitSessao"));

     if ($clDisArq->numrows > 0) {
       db_fieldsmemory($rsDisArq, 0);
     }

     $totalvalorpago = 0;

     for ($i = $priregistro; $i <= $totalproc - ($priregistro == 0 ? 1 : 0); $i ++) {

      /**
       * Grava arquivo disbanco
       */
       if ($k15_taman == 242) {

         //Acerto 1/2 para arapiraca
         $totalproc = sizeof($arq_array) - 3;
         if (substr($arq_array[$i], 7, 1) != '3' || substr($arq_array[$i], 13, 1) != 'U') {
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

         if (substr($arq_array[$i], 0, 3) <> "104" && substr($arq_array[$i], 0, 3) <> "BSJ") {
           continue;
         }
       }

       if (substr($arq_array[$i], 0, 4) == 'BSJI') {

         if (substr($arq_array[0], 0, 5) == 'BSJI0' && $i == 1) {

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
         $dtarq  = $dtarquivo;
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
       $convenio = substr($arq_array[$i], substr($k15_poscon, 0, 3) - 1, substr($k15_poscon, 3, 3));
       $cedente  = substr($arq_array[$i], substr($k15_posced, 0, 3) - 1, substr($k15_posced, 3, 3));

       $totalvalorpago += $vlrpago;
     }

  } catch (Exception $oException) {
     db_msgbox("{$oException->getMessage()}");
  }

} else if (isset ($geradisbanco)) {

  $situacao = 2;
  $sWhere   = "     k15_codbco = {$k15_codbco}    ";
  $sWhere  .= " and k15_codage = '{$k15_codage}'  ";
  $sWhere  .= " and k15_instit = {$iInstitSessao} ";
  $rsCadBan = $clCadBan->sql_record($clCadBan->sql_query_file(null,"*",null,$sWhere));
  db_fieldsmemory($rsCadBan, 0);

  $arq_array = file(ECIDADE_PATH."tmp/".$arqret);

  if ($arq_array == false) {

    $oParametrosMsg                = new stdClass();
    $oParametrosMsg->sDocumentRoot = ECIDADE_PATH;
    $sMsg                          = _M( MENSAGENS . 'erro_permissao_pasta', $oParametrosMsg);
    db_msgbox( $sMsg );
    exit;
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load("scripts.js, strings.js, prototype.js, estilos.css");
?>
</head>
<body class="body-default" onLoad="a=1">
<?php

  if ($situacao == "") {
    include(modification("forms/db_caiarq001.php"));
  } else if ($situacao == 1 && empty($codretexiste)) {
    include(modification("forms/db_caiarq002.php"));
  } else if ($situacao == 2) {
    include(modification("forms/db_caiarq003.php"));
  }
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?php

if ($situacao == 1) {

  if (isset ($codretexiste) && $codretexiste != "") {

    $oParametrosMsg                   = new stdClass();
    $oParametrosMsg->sBancoExiste     = $bancoexiste;
    $oParametrosMsg->sAgenciaExiste   = $agenciaexiste;
    $oParametrosMsg->sDtArquivoExiste = db_formatar($dtarquivoexiste, 'd');
    $sMsg                             = _M( MENSAGENS . 'arquivo_ja_existe', $oParametrosMsg);

    echo "<script>
           alert('$sMsg');
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

    /**
     * Grava arquivo disarq
     */
    db_inicio_transacao();

     if (substr($k15_pdano, 3, 3) == '002') {
       $dtarquivo = '20'.substr($arq_array[0], substr($k15_pdano, 0, 3) - 1, substr($k15_pdano, 3, 3));
     } else {
       $dtarquivo = substr($arq_array[0], substr($k15_pdano, 0, 3) - 1, substr($k15_pdano, 3, 3));
     }

     $dtarquivo .= "-".substr($arq_array[0], substr($k15_pdmes, 0, 3) - 1, substr($k15_pdmes, 3, 3));
     $dtarquivo .= "-".substr($arq_array[0], substr($k15_posdta, 0, 3) - 1, substr($k15_posdta, 3, 3));

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

      $oParametrosMsg           = new stdClass();
      $oParametrosMsg->sMsgErro = $clDisArq->erro_msg;
      $sMsg                     = _M( MENSAGENS . 'erro_inclusao_disarq', $oParametrosMsg);
      throw new DBException($sMsg);
    }

     $codret         = $clDisArq->codret;

     $achou_arrecant = 0;

     $sWhereCadBan  = "     k15_codbco = {$k15_codbco}    ";
     $sWhereCadBan .= " and k15_codage = '{$k15_codage}'  ";
     $sWhereCadBan .= " and k15_instit = {$iInstitSessao} ";

     $sSqlCabBan    = $clCadBan->sql_query_file(null, "k15_seq, k15_poscon, k15_contat", null, $sWhereCadBan);
     $rsCadBan      = $clCadBan->sql_record($sSqlCabBan);

     db_fieldsmemory($rsCadBan, 0);

     $k15_numpreori = $k15_numpre;
     $k15_numparori = $k15_numpar;
     $priregistro   = 1;

     /**
      * Processa Registros do Arquivo para Gravar em DISBANCO
      */
     $passou_pelo_t  = true;
     $k15_numbco_ant = $k15_numbco;

     $total_tx_bancaria = 0;

     $aGuardar = array();

     for ($i = $priregistro; $i <= $totalproc - ($priregistro == 0 ? 1 : 0); $i ++) {

       if ($lDebugAtivo == true) {
         echo "i: $i - cgc: $cgc - passou_pelo_t: $passou_pelo_t<br/>";
       }

       $k15_numbco    = $k15_numbco_ant;
       $tipo_convenio = "";

       /**
        * Testa tipo do registro
        */
       if ($k15_taman == 242) {

         if (substr($arq_array[$i], 7, 1) != '3' && (substr($arq_array[$i], 13, 1) != 'U' && substr($arq_array[$i], 13, 1) != 'T')) {

           if ($lDebugAtivo == true) {
             echo "   continuando 1.00110011...<br/>";
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

         if (substr($arq_array[$i], 0, 3) <> "104" && substr($arq_array[$i], 0, 3) <> "BSJ") {
           continue;
         }
       }

       /**
        * Com registro
        */
       if ( $k15_codbco == 1 && $k15_codage == "2280C" ) {

         if ( substr($arq_array[$i],0,1) == "2" ) {

           if ( substr($arq_array[$i],41,30) != "PREFEITURA MUNICIPAL DE MARICA" && substr($arq_array[$i],41,30) != "DBSELLER SERVICOS DE INFORMATI" ) {

            continue;
           }
         } else if ( substr($arq_array[$i],0,1) == "7" && ( substr($arq_array[$i],108,2) == "05" || substr($arq_array[$i],108,2) == "06" ) ) {
            //echo "   7=sem continue<br/>";
         } else {
          continue;
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

       /**
        * Grava arquivo disbanco
        */
       $k15_numpre = $k15_numpreori;
       $k15_numpar = $k15_numparori;

       if (@$numpre == "") {

         $numpre = substr($arq_array[$i], substr($k15_numpre, 0, 3) - 1, substr($k15_numpre, 3, 3));
         $numpar = substr($arq_array[$i], substr($k15_numpar, 0, 3) - 1, substr($k15_numpar, 3, 3));
       }

       // SIGCB - k15_contat
       if ($k15_contat == 'BDL' || $k15_contat == 'SIGCB') {

         if (substr($arq_array[$i], 13, 1) == 'T' || substr($arq_array[$i], 13, 1) == 'U') {

           $tipo_convenio = strtolower($k15_contat);
           $passou_pelo_t = false;
         }
       }

       if ($lDebugAtivo == true) {
         echo("numpre [1]: $numpre - numpar: $numpar <br/>");
       }

       if ($k15_taman == 242) {

         if (substr($arq_array[$i], 13, 1) == 'T') {

           $numbco   = substr($arq_array[$i], substr($k15_numbco, 0, 3) - 1, substr($k15_numbco, 3, 3));
           $convenio = substr($arq_array[$i], substr($k15_poscon, 0, 3) - 1, substr($k15_poscon, 3, 3));
           if ($lDebugAtivo == true) {
             echo "   continuando 5...<br/>";
           }
           continue;
         }

       } else {

         $convenio = substr($arq_array[$i], substr($k15_poscon, 0, 3) - 1, substr($k15_poscon, 3, 3));
         $numbco   = substr($arq_array[$i], substr($k15_numbco, 0, 3) - 1, substr($k15_numbco, 3, 3));
       }

       /**
        * Com registro
        */
       if ( substr($numbco,0,7) == "1146159" ) {

         if ( substr($arq_array[$i],0,1) == "2" ) {

           die($arq_array[$i]);
           continue;
         }

       }

       /**
        * 00000006500065000
        * 00016527079203111
        * 00000000089884711
        */
       if ( $lDebugAtivo == true ) {
         echo("<br/>x: $numbco<br/>");
       }

       /**
        * 1117474
        * 1146159
        */
       if ( $lDebugAtivo == true ) {
         echo "numbco: $numbco - digito 14: " . substr($numbco,14,1) . " === ";
       }

       /**
        * Só deve entrar se for numpre novo, pois numpres antigos vai encontrar pelo numbco
        */
       if ( substr($numbco,0,7) != "1117474" && substr($numbco,0,7) != "1146159" ) {

         if ( ( substr($numbco,15,2) != "09"    &&
                substr($numbco,15,2) != "10"    &&
                substr($numbco,15,2) != "11"    &&
                $k15_codage          != "2280F" &&
                $k15_codage          != "9334G" ) ||
              ( substr($numbco,0,6) == "000000" && substr($numbco,14,1) == "0" ) ) {

           if ( $lDebugAtivo == true ) {
             echo " entrou ... === ";
           }

           $k15_numbco = "000000";
           $numbco     = "";
           $k15_numpre = "071008";
           $k15_numpar = "079003";
           $numpre     = substr($arq_array[$i], substr($k15_numpre, 0, 3) - 1, substr($k15_numpre, 3, 3));
           $numpar     = substr($arq_array[$i], substr($k15_numpar, 0, 3) - 1, substr($k15_numpar, 3, 3));

         } else {

           if ( $lDebugAtivo == true ) {
             echo " nao entrou ... === ";
           }
         }
       }

       if ( $lDebugAtivo == true ) {
         echo("numbco: $numbco - numpre: $numpre - numpar: $numpar<br/>");
       }

       if ( $k15_codage == "9334G" && $numpre < 8000000 ) {
         $numpar = 999;
       }

       if ($lDebugAtivo == true) {
         echo("numpre [2]: $numpre - numpar: $numpar <br/>");
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
         $dtcredito = substr($arq_array[$i], substr($k15_anocredito, 0, 3) - 1, substr($k15_anocredito, 3, 3));
       }

       $dtcredito .= "-".substr($arq_array[$i], substr($k15_mescredito, 0, 3) - 1, substr($k15_mescredito, 3, 3));
       $dtcredito .= "-".substr($arq_array[$i], substr($k15_diacredito, 0, 3) - 1, substr($k15_diacredito, 3, 3));

       if (empty($dtcredito) || $dtcredito == '0000-00-00') {
         $dtcredito = $dtpago;
       }

       if ($lDebugAtivo == true) {
         echo "   continuando 6 - numbco: [$numbco]...<br/>";
       }

       if ($numbco != "") {

         if ($lDebugAtivo == true) {
           echo "   continuando 6.1...<br/>";
         }

         $numbco = $k15_seq.$convenio.$numbco;

         $sqlprocura  = "select arrebanco.k00_numpre as numpre,                                       ";
         $sqlprocura .= "       arrebanco.k00_numpar as numpar                                        ";
         $sqlprocura .= "  from arrebanco                                                             ";
         $sqlprocura .= "       inner join arreinstit on arreinstit.k00_numpre = arrebanco.k00_numpre ";
         $sqlprocura .= " where arrebanco.k00_numbco  = trim('".trim($numbco)."')                     ";
         $sqlprocura .= "   and arreinstit.k00_instit = $iInstitSessao                                ";
         $resultprocura = db_query($sqlprocura) or die($sqlprocura);

         if (pg_numrows($resultprocura) == 0) {

             /**
              * 11174740000815830
              * 11174740650120100
              */
             $lContinuar = 1;
             if ( ( substr($numbco,0,7) == "1117474" && substr($numbco,0,10) != "1117474000" ) ) {
               $lContinuar = 0;
             }

             if ( ( substr($numbco,0,7) == "1146159" && substr($numbco,0,10) != "1146159000" ) ) {
               $lContinuar = 0;
             }

             if ( $lContinuar == 0 ) { // e-cidade

               $k15_numbco = "000000";
               $numbco     = "";
               $k15_numpre = "071008";
               $k15_numpar = "079003";
               $numpre     = substr($arq_array[$i], substr($k15_numpre, 0, 3) - 1, substr($k15_numpre, 3, 3));
               $numpar     = substr($arq_array[$i], substr($k15_numpar, 0, 3) - 1, substr($k15_numpar, 3, 3));
             } else {

               /**
                * 00003585078397711
                * 00056074086234211
                */
               if ( $lDebugMaricaAtivo == true ) {
                 echo "<br/> nao achou (1): $numbco <br/>";
               }

               $iIdCobrancaCob = substr($numbco,8,7);
               $cControleCob   = substr($numbco,2,6);
               $iAnoEmissaoCob = substr($numbco,15,2);

               $sBuscaSitm  = "  select sequencial_parc     as sequencial_parc_sitm,                                                ";
               $sBuscaSitm .= "         tributo_parc        as tributo_parc_sitm,                                                   ";
               $sBuscaSitm .= "         cota_cob            as cota_cob_sitm,                                                       ";
               $sBuscaSitm .= "         sum(valor_inic_cob) as valor_inic_cob_sitm,                                                 ";
               $sBuscaSitm .= "         sum(juros_cob)      as juros_cob_sitm,                                                      ";
               $sBuscaSitm .= "         sum(multa_cob)      as multa_cob_sitm                                                       ";
               $sBuscaSitm .= "    from cobrancas_sitm_bxbanco_naoapagar                                                            ";
               $sBuscaSitm .= "         inner join parcelamentos_sitm_bxbanco_naoapagar on controle_parc         = controle_cob     ";
               $sBuscaSitm .= "                                                        and tributo_parc          = tributo_cob      ";
               $sBuscaSitm .= "                                                        and seq_controle_cob      = seqcontrole_parc ";
               $sBuscaSitm .= "                                                        and totalparcelas_parc    = total_cotas_cob  ";
               $sBuscaSitm .= "                                                        and trim(exercicios_parc) <> ''              ";
               $sBuscaSitm .= "   where idcobranca_cob             = '$iIdCobrancaCob'                                              ";
               $sBuscaSitm .= "     and controle_cob               = '$cControleCob'                                                ";
               $sBuscaSitm .= "     and substr(dt_emissao_cob,7,2) = '$iAnoEmissaoCob'                                              ";
               $sBuscaSitm .= "group by sequencial_parc, tipocontribuinte, tributo_parc, cota_cob                                   ";

               if ( $lDebugMaricaAtivo == true ) {
                 echo "<br/>$sBuscaSitm<br/>";
               }

               $rsBuscaSitm = db_query($sBuscaSitm) or die($sBuscaSitm);

               if (pg_numrows($rsBuscaSitm) == 1) {

                 db_fieldsmemory($rsBuscaSitm, 0);

                 $sBuscaTermo  = "select arrecad.*                                                                  ";
                 $sBuscaTermo .= "  from divida.termo                                                               ";
                 $sBuscaTermo .= "        inner join caixa.arrecad    on termo.v07_numpre      = arrecad.k00_numpre ";
                 $sBuscaTermo .= "        inner join caixa.arrematric on arrematric.k00_numpre = arrecad.k00_numpre ";
                 $sBuscaTermo .= " where termo.v07_parcel = $sequencial_parc_sitm                                   ";
                 $sBuscaTermo .= "   and k00_numpar       = $cota_cob_sitm                                          ";

                 $rsBuscaTermo = db_query($sBuscaTermo) or die($sBuscaTermo);

                 if (pg_numrows($rsBuscaTermo) == 1) {

                   $oTermo = db_utils::fieldsMemory($rsBuscaTermo,0);

                   $sNumpref  = "select nextval('caixa.numpref_k03_numpre_seq') as numpre_novo";
                   $rsNumpref = db_query($sNumpref) or die($sNumpref);

                   db_fieldsmemory($rsNumpref, 0);

                   $sInsert  = " insert into caixa.db_reciboweb                                                                                   ";
                   $sInsert .= " ( k99_numpre, k99_numpar, k99_numpre_n, k99_codbco, k99_codage, k99_numbco, k99_desconto, k99_tipo, k99_origem ) ";
                   $sInsert .= " values                                                                                                           ";
                   $sInsert .= " ( $oTermo->k00_numpre, $oTermo->k00_numpar, $numpre_novo, 0, '', '', 0, 24, 1 )                                  ";
                   $rsInsert = db_query($sInsert) or die($sInsert);

                   $sInsert  = " insert into caixa.recibopaga                                                                                                                               ";
                   $sInsert .= " ( k00_numcgm, k00_dtoper, k00_receit, k00_hist, k00_valor, k00_dtvenc, k00_numpre, k00_numpar, k00_numtot, k00_numdig, k00_conta, k00_dtpaga, k00_numnov ) ";
                   $sInsert .= " values                                                                                                                                                     ";
                   $sInsert .= " ( $oTermo->k00_numcgm, '$oTermo->k00_dtoper', $oTermo->k00_receit, $oTermo->k00_hist, $valor_inic_cob_sitm, '$oTermo->k00_dtvenc', $oTermo->k00_numpre, $oTermo->k00_numpar, $oTermo->k00_numtot, $oTermo->k00_numdig, '0', null, $numpre_novo )";
                   $rsInsert = db_query($sInsert);

                   if ( $lDebugMaricaAtivo == true ) {
                     echo "<br/>insert 1 - termo: $sInsert<br/>";
                   }

                   $sInsert  = " insert into caixa.recibopaga                                                                                                                               ";
                   $sInsert .= " ( k00_numcgm, k00_dtoper, k00_receit, k00_hist, k00_valor, k00_dtvenc, k00_numpre, k00_numpar, k00_numtot, k00_numdig, k00_conta, k00_dtpaga, k00_numnov ) ";
                   $sInsert .= " values                                                                                                                                                     ";
                   $sInsert .= " ( $oTermo->k00_numcgm, '$oTermo->k00_dtoper', 360, 400, $juros_cob_sitm, '$oTermo->k00_dtvenc', $oTermo->k00_numpre, $oTermo->k00_numpar, $oTermo->k00_numtot, $oTermo->k00_numdig, '0', null, $numpre_novo )";
                   $rsInsert = db_query($sInsert);

                   if ( $lDebugMaricaAtivo == true ) {
                      echo "<br/>insert 2 - termo: $sInsert<br/>";
                   }

                   $sInsert  = " insert into caixa.recibopaga                                                                                                                               ";
                   $sInsert .= " ( k00_numcgm, k00_dtoper, k00_receit, k00_hist, k00_valor, k00_dtvenc, k00_numpre, k00_numpar, k00_numtot, k00_numdig, k00_conta, k00_dtpaga, k00_numnov ) ";
                   $sInsert .= " values                                                                                                                                                     ";
                   $sInsert .= " ( $oTermo->k00_numcgm, '$oTermo->k00_dtoper', 352, 401, $multa_cob_sitm, '$oTermo->k00_dtvenc', $oTermo->k00_numpre, $oTermo->k00_numpar, $oTermo->k00_numtot, $oTermo->k00_numdig, '0', null, $numpre_novo )";
                   $rsInsert = db_query($sInsert);

                   if ( $lDebugMaricaAtivo == true ) {
                     echo "<br/>insert 3 - termo: $sInsert<br/>";
                   }

                   $numpre = $numpre_novo;
                   $numpar = 0;

                 } else {

                   $numpre = 0;
                   $numpar = 0;
                 }

               } else {

                 $sBuscaSitmUnica  = "  select tributo_cob                                   as tributo_cob_sitm,    ";
                 $sBuscaSitmUnica .= "         ano_cob                                       as ano_cob_sitm,        ";
                 $sBuscaSitmUnica .= "         cota_cob                                      as cota_cob_sitm,       ";
                 $sBuscaSitmUnica .= "         valor_inic_cob                                as valor_inic_cob_sitm, ";
                 $sBuscaSitmUnica .= "         coalesce(juros_cob,0)                         as juros_cob_sitm,      ";
                 $sBuscaSitmUnica .= "         coalesce(multa_cob,0)                         as multa_cob_sitm,      ";
                 $sBuscaSitmUnica .= "         sum( valor_inic_cob + juros_cob + multa_cob ) as valortotal_sitm      ";
                 $sBuscaSitmUnica .= "    from cobrancas_sitm_bxbanco_naoapagar                                      ";
                 $sBuscaSitmUnica .= "   where idcobranca_cob = '$iIdCobrancaCob'                                    ";
                 $sBuscaSitmUnica .= "     and controle_cob   = '$cControleCob'                                      ";
                 $sBuscaSitmUnica .= "     and substr(dt_emissao_cob,7,2) = '$iAnoEmissaoCob'                        ";
                 $sBuscaSitmUnica .= "group by tributo_cob, ano_cob, cota_cob, valor_inic_cob, juros_cob, multa_cob  ";

                 $rsBuscaSitmUnica = db_query($sBuscaSitmUnica) or die("sql1: $sBuscaSitmUnica");

                 if (pg_numrows($rsBuscaSitmUnica) > 0) {

                   $iNaoAchou          = 0;
                   $iCotaUnicaIptuSitm = 0;

                   for ( $iUnica = 0; $iUnica < pg_numrows($rsBuscaSitmUnica); $iUnica++ ) {

                     db_fieldsmemory($rsBuscaSitmUnica, $iUnica);

                     $sBuscaDivida  = " select arrecad.k00_numpre, arrecad.k00_numpar                                    ";
                     $sBuscaDivida .= "   from divida.divida                                                             ";
                     $sBuscaDivida .= "        inner join caixa.arrecad    on divida.v01_numpre     = arrecad.k00_numpre ";
                     $sBuscaDivida .= "                                   and divida.v01_numpar     = arrecad.k00_numpar ";
                     $sBuscaDivida .= "        inner join caixa.arrematric on arrematric.k00_numpre = arrecad.k00_numpre ";
                     $sBuscaDivida .= "  where divida.v01_exerc = $ano_cob_sitm                                          ";
                     $sBuscaDivida .= "    and k00_matric = $cControleCob                                                ";
                     $sBuscaDivida .= "    and v01_proced = 179                                                          ";

                     $rsBuscaDivida = db_query($sBuscaDivida) or die($sBuscaDivida);

                     if (pg_numrows($rsBuscaDivida) == 0) {

                       $sBuscaIptu  = " select arrecad.*                                                                   ";
                       $sBuscaIptu .= "   from caixa.arrecad                                                               ";
                       $sBuscaIptu .= "        inner join cadastro.iptunump on arrecad.k00_numpre    = iptunump.j20_numpre ";
                       $sBuscaIptu .= "        inner join caixa.arrematric  on arrematric.k00_numpre = arrecad.k00_numpre  ";
                       $sBuscaIptu .= "  where iptunump.j20_anousu = $ano_cob_sitm                                         ";
                       $sBuscaIptu .= "    and k00_matric          = $cControleCob                                         ";

                       if ( $cota_cob_sitm > 0 ) {
                         $sBuscaIptu .= " and arrecad.k00_numpar = $cota_cob_sitm                                          ";
                       }

                       $sBuscaIptu .= "    and arrecad.k00_receit = 105                                                    ";
                       $sBuscaIptu .= "order by arrecad.k00_numpar                                                         ";

                       if ( $lDebugMaricaAtivo == true ) {
                         echo "<br/>sBuscaIptu: $sBuscaIptu<br/>";
                       }

                       $rsBuscaIptu = db_query($sBuscaIptu) or die($sBuscaIptu);

                       if (pg_numrows($rsBuscaIptu) > 0) {

                         $iNaoAchou = 2; // IPTU
                         if ( $cota_cob_sitm == 0 ) {
                           $iCotaUnicaIptuSitm = 1;
                         } else {
                           $iCotaUnicaIptuSitm = 0;
                         }

                       } else {
                         $iNaoAchou = 1;
                       } // Fim Busca iptu

                     }// Fim BuscaDivida

                   } // Fim BuscaSitmUnica

                   if ( $iNaoAchou == 0 ) {

                     $numpre = 0;
                     $numpar = 0;

                   } elseif ( $iNaoAchou == 2 ) {

                     $sNumpref  = "select nextval('caixa.numpref_k03_numpre_seq') as numpre_novo";
                     $rsNumpref = db_query($sNumpref) or die("$sNumpref");

                     db_fieldsmemory($rsNumpref, 0);

                     for ( $iIptu = 0; $iIptu < pg_numrows($rsBuscaIptu); $iIptu++ ) {

                       $oIptu = db_utils::fieldsMemory($rsBuscaIptu,$iIptu);

                       $sCalcula  = "select substr(fc_calcula,2,13)::float8  as vlrhis,                                              ";
                       $sCalcula .= "       substr(fc_calcula,15,13)::float8 as vlrcor,                                              ";
                       $sCalcula .= "       substr(fc_calcula,28,13)::float8 as vlrjuros,                                            ";
                       $sCalcula .= "       substr(fc_calcula,41,13)::float8 as vlrmulta                                             ";
                       $sCalcula .= "  from ( select fc_calcula($oIptu->k00_numpre," . ($iCotaUnicaIptuSitm == 1?0:$oIptu->k00_numpar);
                       $sCalcula .= "                ,0,'$dtpago','$dtpago'," . ( 2000 + (int) substr($dtpago,8,2) ) . ") ) as x     ";

                       if ( $lDebugMaricaAtivo == true ) {
                         echo "<br/>fc_calcula: $sCalcula<br/>";
                       }

                       $rsCalcula = db_query($sCalcula) or die($sCalcula);
                       $oCalcula  = db_utils::fieldsMemory($rsCalcula,0);

                       $sInsert  = " insert into caixa.db_reciboweb                                                                                   ";
                       $sInsert .= " ( k99_numpre, k99_numpar, k99_numpre_n, k99_codbco, k99_codage, k99_numbco, k99_desconto, k99_tipo, k99_origem ) ";
                       $sInsert .= " values                                                                                                           ";
                       $sInsert .= " ( $oIptu->k00_numpre, $oIptu->k00_numpar, $numpre_novo, 0, '', '', 0, 24, 1 )                                    ";

                       $rsInsert = db_query($sInsert) or die($sInsert);

                       $valor_inic_cob_sitm = $oCalcula->vlrcor;

                       if ( $cota_cob_sitm == 0 ) {

                         $oIptu->k00_hist  = 890;
                         $oIptu->k00_valor = round( $valor_inic_cob_sitm / pg_numrows($rsBuscaIptu),2 );
                         $oIptu->k00_juros = round( $oCalcula->vlrjuros  / pg_numrows($rsBuscaIptu),2 );
                         $oIptu->k00_multa = round( $oCalcula->vlrmulta  / pg_numrows($rsBuscaIptu),2 );
                       } else {

                         $oIptu->k00_valor = $valor_inic_cob_sitm;
                         $oIptu->k00_juros = $oCalcula->vlrjuros;
                         $oIptu->k00_multa = $oCalcula->vlrmulta;
                       }

                       if ( $cota_cob_sitm == 0 && $iIptu == pg_numrows($rsBuscaIptu) - 1 ) {

                         $oIptu->k00_valor = $oIptu->k00_valor + round ( round( $oIptu->k00_valor * pg_numrows($rsBuscaIptu),2 ) - $valor_inic_cob_sitm ,2);
                         $oIptu->k00_juros = $oIptu->k00_juros + round ( round( $oIptu->k00_juros * pg_numrows($rsBuscaIptu),2 ) - $oCalcula->vlrjuros,2);
                         $oIptu->k00_multa = $oIptu->k00_multa + round ( round( $oIptu->k00_multa * pg_numrows($rsBuscaIptu),2 ) - $oCalcula->vlrmulta,2);
                       }

                       if ( $lDebugMaricaAtivo == true ) {
                          echo "numbco: $numbco - valor: $oIptu->k00_valor - juros: $oIptu->k00_juros - multa: $oIptu->k00_multa<br/>";
                       }

                       $sInsert  = " insert into caixa.recibopaga ";
                       $sInsert .= " ( k00_numcgm, k00_dtoper, k00_receit, k00_hist, k00_valor, k00_dtvenc, k00_numpre, k00_numpar, k00_numtot, k00_numdig, k00_conta, k00_dtpaga, k00_numnov ) ";
                       $sInsert .= " values ";
                       $sInsert .= " ( $oIptu->k00_numcgm, '$oIptu->k00_dtoper', $oIptu->k00_receit, $oIptu->k00_hist, $oIptu->k00_valor, '$oIptu->k00_dtvenc', $oIptu->k00_numpre, $oIptu->k00_numpar, $oIptu->k00_numtot, $oIptu->k00_numdig, '0', null, $numpre_novo )";
                       $rsInsert = db_query($sInsert) or die($sInsert);

                       if ( $oIptu->k00_juros > 0 ) {

                         $sInsert  = " insert into caixa.recibopaga ";
                         $sInsert .= " ( k00_numcgm, k00_dtoper, k00_receit, k00_hist, k00_valor, k00_dtvenc, k00_numpre, k00_numpar, k00_numtot, k00_numdig, k00_conta, k00_dtpaga, k00_numnov ) ";
                         $sInsert .= " values ";
                         $sInsert .= " ( $oIptu->k00_numcgm, '$oIptu->k00_dtoper', 358, 400, $oIptu->k00_juros, '$oIptu->k00_dtvenc', $oIptu->k00_numpre, $oIptu->k00_numpar, $oIptu->k00_numtot, $oIptu->k00_numdig, '0', null, $numpre_novo )";
                         $rsInsert = db_query($sInsert) or die("erro no sql 4: $sInsert");

                       }

                       if ( $oIptu->k00_multa > 0 ) {

                         $sInsert  = " insert into caixa.recibopaga ";
                         $sInsert .= " ( k00_numcgm, k00_dtoper, k00_receit, k00_hist, k00_valor, k00_dtvenc, k00_numpre, k00_numpar, k00_numtot, k00_numdig, k00_conta, k00_dtpaga, k00_numnov ) ";
                         $sInsert .= " values ";
                         $sInsert .= " ( $oIptu->k00_numcgm, '$oIptu->k00_dtoper', 350, 401, $oIptu->k00_multa, '$oIptu->k00_dtvenc', $oIptu->k00_numpre, $oIptu->k00_numpar, $oIptu->k00_numtot, $oIptu->k00_numdig, '0', null, $numpre_novo )";
                         $rsInsert = db_query($sInsert) or die($sInsert);

                       }

                     }

                     $numpre = $numpre_novo;
                     $numpar = 0;

                   } else {

                     $numpre = 0;
                     $numpar = 0;
                   }

                 } else {

                   $numpre = 0;
                   $numpar = 0;
                 }

               }

             }

         } else {

           db_fieldsmemory($resultprocura, 0);

             if ( substr($numbco,0,7) != "1117474" && substr($numbco,0,7) != "1146159" ) {

               if ( $numpar == 0 ) {

                 $iIdCobrancaCob = substr($numbco,8,7);
                 $cControleCob   = substr($numbco,2,6);
                 $iAnoEmissaoCob = substr($numbco,15,2);

                 if ( $lDebugMaricaAtivo == true ) {
                   echo "<br/> achou: $numbco <br/>";
                 }

                 $sReciboPaga  = "select * from caixa.recibopaga where k00_numnov = $numpre";
                 $rsReciboPaga = db_query($sReciboPaga) or die("erro sql: $sReciboPaga");

                 if ( pg_numrows($rsReciboPaga) > 0 ) {

                   $sBuscaSitmUnica  = " select tributo_cob                                   as tributo_cob_sitm,    ";
                   $sBuscaSitmUnica .= "        cota_cob                                      as cota_cob_sitm,       ";
                   $sBuscaSitmUnica .= "        sum(valor_inic_cob)                           as valor_inic_cob_sitm, ";
                   $sBuscaSitmUnica .= "        coalesce(sum(juros_cob),0)                    as juros_cob_sitm,      ";
                   $sBuscaSitmUnica .= "        coalesce(sum(multa_cob),0)                    as multa_cob_sitm,      ";
                   $sBuscaSitmUnica .= "        sum( valor_inic_cob + juros_cob + multa_cob ) as valortotal_sitm      ";
                   $sBuscaSitmUnica .= "   from cobrancas_sitm_bxbanco_naoapagar                                      ";
                   $sBuscaSitmUnica .= "  where idcobranca_cob                   = '$iIdCobrancaCob'                  ";
                   $sBuscaSitmUnica .= "    and controle_cob                     = '$cControleCob'                    ";
                   $sBuscaSitmUnica .= "    and substr(dt_emissao_cob::text,7,2) = '$iAnoEmissaoCob'                  ";
                   $sBuscaSitmUnica .= "  group by tributo_cob, cota_cob                                              ";

                   $rsBuscaSitmUnica = db_query($sBuscaSitmUnica) or die("erro ao rodar sql (2): $sBuscaSitmUnica");

                   if ( $lDebugMaricaAtivo == true ) {
                     echo "sql sBuscaSitmUnica: $sBuscaSitmUnica<br/>";
                   }

                   if (pg_numrows($rsBuscaSitmUnica) > 0) {

                     db_fieldsmemory($rsBuscaSitmUnica, 0);

                     $sUpdateReciboPaga  = "update caixa.recibopaga set k00_valor = $valor_inic_cob_sitm where k00_numnov = $numpre";
                     $rsUpdateReciboPaga = db_query($sUpdateReciboPaga) or die($sUpdateReciboPaga);

                     if ( $lDebugMaricaAtivo == true ) {
                       echo "valor: $valor_inic_cob_sitm - juros: $juros_cob_sitm - multa: $multa_cob_sitm<br/>";
                     }

                     $sInsert  = " insert into caixa.recibopaga ";
                     $sInsert .= " ( k00_numcgm, k00_dtoper, k00_receit, k00_hist, k00_valor, k00_dtvenc, k00_numpre, k00_numpar, k00_numtot, k00_numdig, k00_conta, k00_dtpaga, k00_numnov ) ";
                     $sInsert .= " select ";
                     $sInsert .= " k00_numcgm, k00_dtoper, 360, 400, $juros_cob_sitm, k00_dtvenc, k00_numpre, k00_numpar, k00_numtot, k00_numdig, k00_conta, k00_dtpaga, k00_numnov ";
                     $sInsert .= " from caixa.recibopaga where k00_numnov = $numpre and k00_hist not in (400,401) ";

                     if ( $lDebugMaricaAtivo == true ) {
                       echo "<br/> insert 1 recibopaga receita 360 - achou : $sInsert<br/>";
                     }

                     $rsInsert = db_query($sInsert) or die("erro no sql 3: $sInsert");

                     $sInsert  = " insert into caixa.recibopaga ";
                     $sInsert .= " ( k00_numcgm, k00_dtoper, k00_receit, k00_hist, k00_valor, k00_dtvenc, k00_numpre, k00_numpar, k00_numtot, k00_numdig, k00_conta, k00_dtpaga, k00_numnov ) ";
                     $sInsert .= " select ";
                     $sInsert .= " k00_numcgm, k00_dtoper, 352, 401, $multa_cob_sitm, k00_dtvenc, k00_numpre, k00_numpar, k00_numtot, k00_numdig, k00_conta, k00_dtpaga, k00_numnov ";
                     $sInsert .= " from caixa.recibopaga where k00_numnov = $numpre and k00_hist not in (400,401) ";

                     if ( $lDebugMaricaAtivo == true ) {
                       echo "<br/> insert 2 recibopaga receita 352 - achou : $sInsert<br/>";
                     }

                     $rsInsert = db_query($sInsert) or die($sInsert);

                   } else {

                     $numpre = 0;
                     $numpar = 0;
                   }

                   if ( $lDebugMaricaAtivo == true ) {

                     db_criatabela(db_query("select * from caixa.recibopaga where k00_numnov = $numpre"));
                     db_criatabela(db_query("select arrecad.* from caixa.recibopaga inner join caixa.arrecad on arrecad.k00_numpre = recibopaga.k00_numpre and arrecad.k00_numpar = recibopaga.k00_numpar where k00_numnov = $numpre"));
                   }

                 } else {
                   die("não encontrou em recibopaga: $sReciboPaga");
                 }

               } //Numpar == 0

             }

         } // Fim else resultprocura

       } else { // numbco é nulo

         if ($lDebugAtivo == true) {
           echo "   continuando 6.2...<br/>";
         }

         $processaposnumpre = true;

         if ( $k15_contat == "BDL" || $k15_contat == "SIGCB" ) {
           $processaposnumpre = false;
         }

         if ( $processaposnumpre == true) {

           $numpre = substr($arq_array[$i], substr($k15_numpre, 0, 3) - 1, substr($k15_numpre, 3, 3));
           $numpar = substr($arq_array[$i], substr($k15_numpar, 0, 3) - 1, substr($k15_numpar, 3, 3));
         }

       }

       if ($lDebugAtivo == true) {
         echo("numpre [3]: $numpre - numpar: $numpar <br/>");
       }

       if ($lDebugAtivo == true) {
         echo "      continuando 7 - tipo_convenio: $tipo_convenio - numpre: $numpre - numpar: $numpar<br/>";
       }

       if ( ( $tipo_convenio == "sigcb" || $tipo_convenio == "bdl" ) && $k15_codage != "2280F" && $k15_codage != "9334G" ) {

         if ($lDebugAtivo == true) {
           echo "      continuando 8 ( numbco: $numbco )...<br/>";
         }

         if ($tipo_convenio == "sigcb" ) {
           $numbco = substr($numbco,2,3) . substr($numbco,0,1) . substr($numbco,5,3) . substr($numbco,1,1) . substr($numbco,8,9);
         }

         $numbco = trim($numbco);

         if ($lDebugAtivo == true) {
           echo "         continuando 8.1 ( numbco: $numbco )...<br/>";
         }

         $sSqlArrebanco  = "select arrebanco.k00_numpre, arrebanco.k00_numpar                            ";
         $sSqlArrebanco .= "  from arrebanco                                                             ";
         $sSqlArrebanco .= "       inner join arreinstit on arreinstit.k00_numpre = arrebanco.k00_numpre ";
         $sSqlArrebanco .= " where arrebanco.k00_numbco = '$numbco'                                      ";
         $sSqlArrebanco .= "   and arreinstit.k00_instit = $iInstitSessao                                ";

         $rsArrebanco = db_query($sSqlArrebanco) or die($sSqlArrebanco);

         if (pg_numrows($rsArrebanco) == 0) {

           $oParametrosMsg            = new stdClass();
           $oParametrosMsg->iNumBanco = $numbco;
           $sMsg                      = _M( MENSAGENS . 'erro_numbanco_nao_encontrado_instit', $oParametrosMsg);
           echo "<script>alert('".$sMsg."');</script>";
         } else {

           db_fieldsmemory($resultbanco, 0, true);
           $numpre = $k00_numpre;
           $numpar = $k00_numpar;
         }

         if ($tipo_convenio == "sigcb" ) {
           $numbco = substr($numbco,2,20);
         }

         if ($lDebugAtivo == true) {
           echo "            continuando 8.2 ( numbco: $numbco )...<br/>";
         }

       }

       $vlrpago  = (substr($arq_array[$i], substr($k15_posvlr, 0, 3) - 1, substr($k15_posvlr, 3, 3)) / 100) + 0;
       $vlrjuros = (substr($arq_array[$i], substr($k15_posjur, 0, 3) - 1, substr($k15_posjur, 3, 3)) / 100) + 0;
       $vlrmulta = (substr($arq_array[$i], substr($k15_posmul, 0, 3) - 1, substr($k15_posmul, 3, 3)) / 100) + 0;
       $vlracres = (substr($arq_array[$i], substr($k15_posacr, 0, 3) - 1, substr($k15_posacr, 3, 3)) / 100) + 0;
       $vlrdesco = (substr($arq_array[$i], substr($k15_posdes, 0, 3) - 1, substr($k15_posdes, 3, 3)) / 100) + 0;
       $cedente  = $convenio;
       $convenio = "";

       /**
        * Com registro
        */
       if ( $k15_codbco == 1 && $k15_codage == "2280C" ) {

         if ( substr($arq_array[$i],0,1) == "7" && ( substr($arq_array[$i],108,2) == "05" || substr($arq_array[$i],108,2) == "06" ) ) {

           $aGuardar["numpre"]    = $numpre;
           $aGuardar["numpar"]    = $numpar;
           $aGuardar["numbco"]    = $numbco;
           $aGuardar["dtpago"]    = $dtpago;
           $aGuardar["dtarq"]     = $dtarq;
           $aGuardar["dtcredito"] = $dtcredito;

           continue;

         } else {

           $numpre  = $aGuardar["numpre"];
           $numpar  = $aGuardar["numpar"];
           $numbco  = $aGuardar["numbco"];
           $dtpago  = $aGuardar["dtpago"];
           $dtarq   = $aGuardar["dtarq"];
           $dtcredito = $aGuardar["dtcredito"];
         }

       } elseif ( $k15_codbco == 1 && $k15_codage == "2280F" ) {

         /**
          * Pagamento em cheque - não processar registro, pois vem com valor zerado
          */
         if (substr($arq_array[$i], 13, 1) == 'U' && substr($arq_array[$i],15,2) == '50' ) {

           $numpre = "";
           $numpar = "";
           continue;
         }

       }

       /**
        * Função de tratamento da taxa bancaria
        */
       $oParametrosTaxaBancaria = new stdClass();
       $oParametrosTaxaBancaria->codret          = $codret;
       $oParametrosTaxaBancaria->numpre          = $numpre;
       $oParametrosTaxaBancaria->k15_codage      = $k15_codage;
       $oParametrosTaxaBancaria->k15_codbco      = $k15_codbco;
       $oParametrosTaxaBancaria->k03_pgtoparcial = $oDadosParametrosNumpref->k03_pgtoparcial;
       $oParametrosTaxaBancaria->dtarq           = $dtarq;
       $oParametrosTaxaBancaria->dtpago          = $dtpago;
       $oParametrosTaxaBancaria->dtcredito       = $dtcredito;

       $iVlrTaxaBancaria = geraTaxaBancaria($oParametrosTaxaBancaria);

       if($lDebugMaricaAtivo == true){
         echo " >> numpre: $numpre - taxa: $iVlrTaxaBancaria - valor: $vlrpago <br/>";
       }

       if ($iVlrTaxaBancaria > 0 && $vlrpago > $iVlrTaxaBancaria) {
         $vlrpago -= $iVlrTaxaBancaria;
       }

       $GLOBALS["HTTP_POST_VARS"]["k00_numpre"] = $numpre;
       $GLOBALS["HTTP_POST_VARS"]["k00_numpar"] = $numpar;

       $numpar = trim($numpar);

       /**
        * Habilita variavel de sessao para permitir numpre's de outras instituições
        */
       permiteNumpreOutraInstituicao( true );

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
       $clDisBanco->k00_numpre = !empty($numpre)?$numpre:'0';
       $clDisBanco->k00_numpar = !empty($numpar)?$numpar:'0';
       $clDisBanco->convenio   = $convenio;
       $clDisBanco->instit     = $iInstitSessao;
       $clDisBanco->incluir(null);

       if ($clDisBanco->erro_status == "0") {

         $oParametrosMsg           = new stdClass();
         $oParametrosMsg->iNumpre  = $clDisBanco->k00_numpre;
         $oParametrosMsg->sMsgErro = $clDisBanco->erro_msg;
         $sMsg                     = _M( MENSAGENS . 'erro_inclusao_disbanco_numpre', $oParametrosMsg);
         throw new DBException($sMsg);
       }

       /**
        * Desabilita variavel de sessao para permitir numpre's de outras instituições
        */
       permiteNumpreOutraInstituicao( false );

       $idRet = $clDisBanco->idret;

       if ( $lDebugMaricaAtivo == true ) {
         echo("<br/><br/>numpre: $numpre - numpar: $numpar<br/><br/>");
       }

       echo "<script>js_termometro(". ($i +1).");</script>";
       flush();

       $numpre = "";
       $numpar = "";
     }

     if ($lDebugAtivo == true) {
       echo "<br/>F I M<br/>";
       exit;
     }

     $sSql   = "  select dtarq,                  ";
     $sSql  .= "         sum(vlrpago)            ";
     $sSql  .= "    from disbanco                ";
     $sSql  .= "   where codret = $codret        ";
     $sSql  .= "     and instit = $iInstitSessao ";
     $sSql  .= "group by dtarq                   ";
     $result = db_query($sSql);

     $total = 0;

     for ($x = 0; $x < pg_numrows($result); $x ++) {

       db_fieldsmemory($result, $x, true);
       echo "Data: $dtarq - Valor: ".db_formatar($sum, "f")."<br/>";
       $total += $sum;
     }

     $oParametrosMsg                 = new stdClass();
     $oParametrosMsg->iTotalArquivo  = trim(db_formatar($total, "f"));
     $oParametrosMsg->iCodRet        = $codret;

     echo "Total do Arquivo: ".db_formatar($total, "f")." - Codret: $codret<br/>";

     if ($achou_arrecant == 0) {

       /*
        * Verificamos a forma de processamento do arquivo txt
        *
        * 0 - Classificação por arquivo:           Gerado somente um registros na disarq
        * 1 - Classificação por data do Pagamento: Gerado mais de um registro na disarq de acordo com a quantidade
        *                                          de datas de pagamentos encontradas no arquivo (campo: dtpago)
        * 2 - Classificação por data de Crédito:   Gerado mais de um registro na disarq de acordo com a quantidade
        *                                          de datas de créditos encontradas no arquivo (campo: dtcredito)
        */
       if (  $oDadosParametrosNumpref->k03_agrupadorarquivotxtbaixabanco == 1
     			|| $oDadosParametrosNumpref->k03_agrupadorarquivotxtbaixabanco == 2 ) {

     		$iCodRet = $codret;
     		include_once(modification("cai4_desmembramentodisbanco001.php"));
     	 }

      if($lDebugMaricaAtivo == true){

       db_criatabela(db_query("select * from disbanco where codret = $codret"));
       die;
      }

      db_fim_transacao(false);
		  unset($_POST);

      db_msgbox( _M( MENSAGENS . 'documento_processado', $oParametrosMsg ) );
		  db_redireciona();

     } else {
       throw new BusinessException( _M( MENSAGENS . 'documento_nao_processado' ) );
     }

 } catch (DBException $eErro){          // DB Exception

   db_fim_transacao(true);
   db_msgbox($eErro->getMessage());
   db_redireciona();

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