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

class bver_ant {
  var $arq=null;


  function bver_ant($header){

    umask(74);
    $this->arq = fopen("tmp/BVER_ANT.TXT",'w+');
    fputs($this->arq,$header);
    fputs($this->arq,"\r\n");
  }

  function processa($instit=1,$data_ini="",$data_fim="",$tribinst,$subelemento="") {

    global $instituicoes,$contador,$nomeinst,$sinal_anterior,$sinal_final;

    $where = " c61_instit in ($instit)";

    $anousu   = (db_getsession("DB_anousu")-1);
    $data_ini = $anousu."-01-01";
    $data_fim = $anousu."-12-31";
    $nomeArq = 'BVER_ANT.TXT';

    /*
     * verifica se ja existe arquivo no banco
     */
    $oDaoArquivosPad  = db_utils::getDao("conarquivospad");
    $rsDaoArquivosPad = $oDaoArquivosPad->sql_record($oDaoArquivosPad->sql_query(null,
                                                                                 "c54_codarq, c54_anousu, c54_nomearq, c54_arquivo",
                                                                                 "",
                                                                                 "c54_anousu      =  {$anousu}
                                                                                     and c54_nomearq = '{$nomeArq}'"
    ));

    if ($oDaoArquivosPad->numrows > 0 ) {

      $oArquivo   = db_utils::fieldsMemory($rsDaoArquivosPad,0);
      $sArquivo   = $oArquivo->c54_arquivo;
      fputs($this->arq, str_replace("\n\r", "", $sArquivo));
      fputs($this->arq,"\r\n");
      $contador = count(explode("\n",$sArquivo));

    } else {

      $result 		   = db_planocontassaldo_matriz($anousu,$data_ini,$data_fim,false,$where,'',false,'true');
      $contador 	   = 0;
      $teste_debito  = 0;
      $teste_credito = 0;

      $array_teste = array();

      for($x = 0; $x < pg_numrows($result);$x++) {

        global $instituicoes,$c61_instit,$c61_reduz,$nivel,$estrutural,$saldo_anterior,$saldo_anterior_debito,$saldo_anterior_credito,$saldo_final,$c60_descr;
        db_fieldsmemory($result,$x);
        //if ($x == 3494) {
        //db_fieldsmemory($result,$x,true,true);exit;
        //}

        //if ($estrutural == "00000523410200020100") {
        //db_fieldsmemory($result,$x,true,true);exit;
        //}

        $line  = formatar($estrutural,20,'n');

        if($c61_instit == 0 || empty($c61_instit))
          $line .= "0000";
        else
          $line .= $instituicoes[$c61_instit];    // aqui é o codtrib, da tabela db_config

        if ($sinal_anterior=='D') {

          $line .= formatar(dbround_php_52($saldo_anterior,2),13,'v');
          $line .= formatar(0,13,'v');
        } else {

          $line .= formatar(0,13,'v');
          $line .= formatar(dbround_php_52($saldo_anterior,2),13,'v');
        }

        if ($saldo_anterior_debito == 7600000) {

          $line .= formatar(7600000,13,'v');
          $saldo_anterior_debito = 7600000;
        } elseif ($saldo_anterior_debito == 96100000) {

          $line .= formatar(96100000,13,'v');
          $saldo_anterior_debito = 96100000;
        } elseif ($saldo_anterior_debito == 1400000) {

          $line .= formatar(1400000,13,'v');
          $saldo_anterior_debito = 1400000;
        } else {

          $line .= formatar(dbround_php_52($saldo_anterior_debito,2),13,'v');
        }

        if ($saldo_anterior_credito == 7600000) {

          $line .= formatar(7600000,13,'v');
          $saldo_anterior_credito = 7600000;
        } elseif ($saldo_anterior_credito == 96100000) {

          $line .= formatar(96100000,13,'v');
          $saldo_anterior_credito = 96100000;
        } elseif ($saldo_anterior_credito == 1400000) {

          $line .= formatar(1400000,13,'v');
          $saldo_anterior_credito = 1400000;
        } else {
          $line .= formatar(dbround_php_52($saldo_anterior_credito,2),13,'v');
        }

        if ($sinal_final=='D') {

          $line .= formatar(dbround_php_52($saldo_final,2),13,'v');
          $line .= formatar(0,13,'v');
        } else {

          $line .= formatar(0,13,'v');
          $line .= formatar(dbround_php_52($saldo_final,2),13,'v');
        }

        $line .= formatar($c60_descr,148,'c');
        $line .= ($c61_reduz == 0?'S':'A');

        // pesquisa nivel da conta

        $sql = "select fc_nivel_plano2005('$estrutural') as nivel ";
        $resultsis = db_query($sql);
        $nivel = pg_result($resultsis,0,'nivel');

        $line .= formatar($nivel,2,'n');

        // pesquisa o sistema da conta orcamentaria, financeiro, etc
        $sql = "select c52_descrred
		               from conplano
		                   inner join consistema on c60_codsis = c52_codsis
			       where c60_anousu = ".$anousu." and c60_estrut = '$estrutural'";


        $resultsis        = db_query($sql);
        $sSistemaContabil = "";

        if (pg_numrows($resultsis) > 0) {
          $sSistemaContabil =  pg_result($resultsis,0,'c52_descrred');
        } else {
          $sSistemaContabil = "F";
        }

        $sEscrituracao                 = " ";
        $sNaturezaInformacao           = " ";
        $sIndicadorSuperavitFinanceiro = " ";

        if (USE_PCASP) {

          $sSistemaContabil = " ";
          $iEstrtutural     = substr($estrutural, 0, 1);

          /**
           * @todo criar metodo estatico que revceba o estrutural e devolta a natureza
           * definimos natureza da Informaçao
           */
          switch ($iEstrtutural) {

            case  1:
            case  2:
            case  3:
            case  4:
              $sNaturezaInformacao = "P";
              break;

            case  5:
            case  6:
              $sNaturezaInformacao = "O";
              break;

            case  7:
            case  8:
              $sNaturezaInformacao = "C";
              break;

            case 9:
              switch ($sSistemaContabil) {

                case "F":
                case "N":
                  $sNaturezaInformacao = "P";
                  break;
              }
              break;
          }

          $sEscrituracao = "N";
          if ($c61_reduz != 0) {
            $sEscrituracao = "S";
          }

          // definimos superavit
          $sSqlSuperavit  = "    select c60_identificadorfinanceiro              ";
          $sSqlSuperavit .= "      from conplano                                 ";
          $sSqlSuperavit .= "     where c60_anousu = ".(db_getsession("DB_anousu")-1) ;
          $sSqlSuperavit .= "       and c60_estrut = '{$estrutural}'             ";
          $rsSuperavit    = db_query($sSqlSuperavit);



          if(pg_numrows($rsSuperavit) > 0) {

            $sIndicadorSuperavitFinanceiro =  pg_result($rsSuperavit,0,'c60_identificadorfinanceiro');

            if ($sIndicadorSuperavitFinanceiro == "N") {
              $sIndicadorSuperavitFinanceiro = "P";
            }
          }else{
            $sIndicadorSuperavitFinanceiro = "P";
          }
        }

        $line .= $sSistemaContabil;
        $line .= $sEscrituracao;
        $line .= $sNaturezaInformacao;
        $line .= $sIndicadorSuperavitFinanceiro;

        $contador ++;

        $teste_debito  += $saldo_anterior_debito+0.0;
        $teste_credito += $saldo_anterior_credito+0.0;

        fputs($this->arq,$line);
        fputs($this->arq,"\r\n");

      }
    }
    //  trailer
    $contador = espaco(10-(strlen($contador)),'0').$contador;
    $line = "FINALIZADOR".$contador;
    fputs($this->arq,$line);
    fputs($this->arq,"\r\n");

    fclose($this->arq);

    $teste = "true";

    @db_query("drop table work_pl");

    return $teste;
  }
}