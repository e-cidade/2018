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

  function geraArquivoBPA($oDados,$rsCabecalho,$rsProducao){
    require_once ("dbforms/db_layouttxt.php");
    include("classes/db_db_layoutcampos_classe.php");

    $sArquivo       = "/tmp/arquivobpa.txt";
    $cldb_layouttxt = new db_layouttxt(85, $sArquivo, "");
    $sValida        = "/tmp/validacns.bpa";
    $pValidacns     = fopen ( $oDados->sValida, "w" ); //cria arquivo para gravação

    $iPagina = 1;
    $iLinhaPagina  = 1;

    $oCabecalho = db_utils::fieldsMemory($rsCabecalho,0);
    $oCabecalho->cbc_fim = "";
    $cldb_layouttxt->setByLineOfDBUtils($oCabecalho,1);

    $lErro             = false;
    $objValida         = new stdClass();
    $objValida->valida = array(array(),array(),array());
    for ($iIndice = 0; $iIndice < $oDados->iLinhas; $iIndice ++) {

      $oProducao = db_utils::fieldsMemory($rsProducao,$iIndice) or die("fieldsmemory da Produçao! ");
      db_atutermometro ( $iIndice, $oDados->iLinhas, 'termometro' );
      $oProducao->prd_flh = str_pad ( $iPagina, 3, "0", STR_PAD_LEFT );
      $oProducao->prd_seq = str_pad ( $iLinhaPagina, 2, "0", STR_PAD_LEFT );
      $oProducao->prd_fim = "";
      if ($oDados->sTipo == "01") {

        $oProducao->prd_cmp = $oDados->compano.str_pad ($oDados->compmes,2, "0", STR_PAD_LEFT );
        $oProducao->prd_cnspac = str_pad (' ',15, ' ', STR_PAD_LEFT );
        $oProducao->prd_sexo   = str_pad (' ',2, ' ', STR_PAD_LEFT );
        $oProducao->prd_ibge   = str_pad (' ',6, ' ', STR_PAD_LEFT );
        $oProducao->prd_cid    = str_pad (' ',4, ' ', STR_PAD_LEFT );
        $oProducao->prd_cnsmed = str_pad (' ',15, ' ', STR_PAD_LEFT );
        $oProducao->prd_dtaten = str_pad (' ',8, ' ', STR_PAD_LEFT );
        $oProducao->prd_caten  = str_pad (' ',2, ' ', STR_PAD_LEFT );
        $oProducao->prd_nmpac  = str_pad (' ',30, ' ', STR_PAD_LEFT );
        $oProducao->prd_dtnasc = str_pad (' ',8, ' ', STR_PAD_LEFT );
        $oProducao->prd_raca   = "99";
        $oProducao->prd_cbo    = str_pad (' ',6, ' ', STR_PAD_LEFT );
        $oProducao->prd_naut   = str_pad (' ',13, ' ', STR_PAD_LEFT );
        $oProducao->prd_org    = "BPA";

      }else{
        if ($oProducao->valida_cns_cgs == 'f') {
          if(  ( arr_search( $objValida->valida[0],
                             "$oProducao->cgs_pac, $oProducao->prd_nmpac, $oProducao->prd_cnspac" )
              == false) ){

            $objValida->valida[0][] = "$oProducao->cgs_pac, $nome, $s115_c_cartaosus";
            $lErro=true;

          }
        }
        if ($oProducao->valida_cns_med == 'f') {
          if(  ( arr_search( $objValida->valida[1],
                             "$oProducao->cod_prof, $oProducao->nome_med , $oProducao->prd_cnsmed")
             == false) ){

            $objValida->valida[1][] = "$oProducao->cod_prof, $oProducao->nome_med , $oProducao->prd_cnsmed";
            $lErro=true;

          }
        }
        if ($oProducao->prd_cid == "") {
           if(  ( arr_search( $objValida->valida[2],"$oProducao->cod_procedimento") == false) ){

             $objValida->valida[2][] = "$oProducao->cod_procedimento";
             $lErro=true;

           }
        }
      }
      $cldb_layouttxt->setByLineOfDBUtils($oProducao,3);
      if ($iLinhaPagina == 20) {

        $iLinhaPagina = 1;
        $iPagina++;

      }
      $iLinhaPagina ++;
    }
    $cldb_layouttxt->fechaArquivo();

    if(($lErro==true)&&($oDados->sTipo == "02")){

      db_msgbox("Arquivo possui inconsistências. Verifique!");
      asort($objValida->valida[0]);
      for( $iX=0; $iX < sizeof( $objValida->valida[0] ); $iX++){
        fwrite( $pValidacns, "PACIENTES:".$objValida->valida[0][ $iX ]. "\n" );
      }
      asort($objValida->valida[1]);
      for( $iX=0; $iX < sizeof( $objValida->valida[1] ); $iX++){
        fwrite( $pValidacns, "PROFISSIONAIS:".$objValida->valida[1][ $iX ]."\n" );
      }
      for( $iX=0; $iX < sizeof( $objValida->valida[2] ); $iX++){
        fwrite( $pValidacns, "Procedimento sem CID:".$objValida->valida[2][ $iX ]."\n" );
      }
      fclose( $pValidacns );
      ?>
      <script>
        jan = window.open('sau2_bpa001.php?bpa=<?=$sValida?>','',
                          'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,
                          location=0 ');
        jan.moveTo(0,0);
      </script>
      <?
      return false;

    }else{
      ?>
      <script>
        listagem = '<?=$sArquivo?>#Download arquivo TXT (BPA)|';
        js_montarlista(listagem,'form1');
      </script>
      <?
      return true;
    }
    function arr_search( $array, $valor ){
      for( $x=0; $x < count($array); $x++){
        if( $array[$x] == $valor ){
          return true;
        }
      }
      return false;
    }
  }