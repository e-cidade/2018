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


// Para garantir que nao houve erros em outros itens
if($sqlerro==false) {

  // VINCULAÇÕES LOTAÇÕES
  include(modification("classes/db_rhlotavinc_classe.php"));
  include(modification("classes/db_rhlotavincativ_classe.php"));
  include(modification("classes/db_rhlotavincele_classe.php"));
  include(modification("classes/db_rhlotavincrec_classe.php"));

  $clrhlotavincativ = new cl_rhlotavincativ;
  $clrhlotavinc     = new cl_rhlotavinc;
  $clrhlotavincele  = new cl_rhlotavincele;
  $clrhlotavincrec  = new cl_rhlotavincrec;

  $sqlrhlotavinc = "select * from rhlotavinc where rh25_anousu = ".$anodestino;
  $resultrhlotavinc = db_query($sqlrhlotavinc);
  $linhasrhlotavinc = pg_num_rows($resultrhlotavinc);

  if ($linhasrhlotavinc == 0) {
    $sql1  = "select * ";
    $sql1 .= "  from rhlotavinc ";
    $sql1 .= "       inner join orctiporec   on o15_codigo    = rh25_recurso  ";
    $sql1 .= "       inner join orcprojativ  on rh25_projativ = o55_projativ  ";
    $sql1 .= "                              and o55_anousu    = {$anodestino} ";
    $sql1 .= "  where rh25_anousu = {$anodestino}";

    $result1 = db_query($sql1);
    $linhas1 = pg_num_rows($result1);
    if ($linhas1==0) {

      $sql2  = "select * ";
      $sql2 .= "  from rhlotavinc ";
      $sql2 .= "       inner join orctiporec   on o15_codigo    = rh25_recurso  ";
      $sql2 .= "       inner join orcprojativ  on rh25_projativ = o55_projativ  ";
      $sql2 .= "                              and o55_anousu    = {$anodestino} ";
      $sql2 .= " where rh25_anousu = {$anoorigem} ";
      $result2 = db_query($sql2);
      $linhas2 = pg_num_rows($result2);
      if ($linhas2==0) {
        $cldb_viradaitemlog->c35_log           = "Nao existem dados de proj/atividades e recursos da folha cadastrados para o exercicio {$anoorigem}";
        $cldb_viradaitemlog->c35_codarq        =  749;
        $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
        $cldb_viradaitemlog->c35_data          = date("Y-m-d");
        $cldb_viradaitemlog->c35_hora          = date("H:i");
        $cldb_viradaitemlog->incluir(null);
        if ($cldb_viradaitemlog->erro_status==0) {
          $sqlerro   = true;
          $erro_msg .= $cldb_viradaitemlog->erro_msg;
        }

      } else {
        $sql3  = "select * ";
        $sql3 .= "  from rhlotavinc ";
        $sql3 .= "       inner join orctiporec   on o15_codigo    = rh25_recurso  ";
        $sql3 .= "       inner join orcprojativ  on rh25_projativ = o55_projativ  ";
        $sql3 .= "                              and o55_anousu    = {$anodestino} ";
        $sql3 .= " where rh25_anousu = {$anoorigem} ";
        $result3 = db_query($sql3);
        $linhas3 = pg_num_rows($result3);

        for ($xx=0; $xx<$linhas3; $xx++) {
          db_fieldsmemory($result3,$xx);
          db_atutermometro($xx, $linhas3, 'termometroitem', 1, $sMensagemTermometroItem);

          $seq_ant = $rh25_codlotavinc;
          $rh25_anousu      = $anodestino;
          $clrhlotavinc->rh25_codigo   = $rh25_codigo;
          $clrhlotavinc->rh25_vinculo  = $rh25_vinculo;
          $clrhlotavinc->rh25_anousu   = $anodestino;
          $clrhlotavinc->rh25_projativ = $rh25_projativ;
          $clrhlotavinc->rh25_recurso  = $rh25_recurso;
          $clrhlotavinc->incluir(null);
          if ($clrhlotavinc->erro_status==0) {
            $sqlerro   = true;
            $erro_msg .= $clrhlotavinc->erro_msg;
            break;
          }

          $sql_vincativ = "select * from rhlotavincativ where rh39_codlotavinc = ".$seq_ant;
          $result_vincativ = db_query($sql_vincativ);
          $linhas_vincativ = pg_num_rows($result_vincativ);

          if ($linhas_vincativ > 0) {

            for ( $iRegistroVinculoAtividade = 0; $iRegistroVinculoAtividade < $linhas_vincativ; $iRegistroVinculoAtividade++) {

              $oDadosVinculoAtividade = db_utils::fieldsMemory($result_vincativ, $iRegistroVinculoAtividade);
              if ($oDadosVinculoAtividade->rh39_programa == '') {
              	$oDadosVinculoAtividade->rh39_programa = "null";
              }

              if ($oDadosVinculoAtividade->rh39_funcao == '') {
              	$oDadosVinculoAtividade->rh39_funcao = "null";
              }

              if ($oDadosVinculoAtividade->rh39_subfuncao == '') {
              	$oDadosVinculoAtividade->rh39_subfuncao = "null";
              }

              $clrhlotavincativ->rh39_codlotavinc = $clrhlotavinc->rh25_codlotavinc;
              $clrhlotavincativ->rh39_codelenov   = $oDadosVinculoAtividade->rh39_codelenov;
              $clrhlotavincativ->rh39_anousu      = $anodestino;
              $clrhlotavincativ->rh39_projativ    = $oDadosVinculoAtividade->rh39_projativ;
              $clrhlotavincativ->rh39_programa    = $oDadosVinculoAtividade->rh39_programa;
              $clrhlotavincativ->rh39_funcao      = $oDadosVinculoAtividade->rh39_funcao;
              $clrhlotavincativ->rh39_subfuncao   = $oDadosVinculoAtividade->rh39_subfuncao;
              $clrhlotavincativ->incluir($clrhlotavinc->rh25_codlotavinc, $oDadosVinculoAtividade->rh39_codelenov);
              if ($clrhlotavincativ->erro_status==0) {
                $sqlerro   = true;
                $erro_msg .= $clrhlotavincativ->erro_msg;
                break;
              }
            }

          }

          $sql_vincele = "select * from rhlotavincele where rh28_codlotavinc = ".$seq_ant;
          $result_vincele = db_query($sql_vincele);
          $linhas_vincele = pg_num_rows($result_vincele);

          if ($linhas_vincele > 0) {

            for ($iVincEle = 0; $iVincEle < $linhas_vincele; $iVincEle++) {

              db_fieldsmemory($result_vincele,$iVincEle);
              $clrhlotavincele->rh28_codlotavinc = $clrhlotavinc->rh25_codlotavinc;
              $clrhlotavincele->rh28_codeledef   = $rh28_codeledef;
              $clrhlotavincele->rh28_codelenov   = $rh28_codelenov;
              $clrhlotavincele->incluir($clrhlotavinc->rh25_codlotavinc,$rh28_codeledef);
              if ($clrhlotavincele->erro_status==0) {
                $sqlerro   = true;
                $erro_msg .= $clrhlotavincele->erro_msg;
                break;
              }

            }
          }

          $sql_vincrec = "select * from rhlotavincrec where rh43_codlotavinc = ".$seq_ant;
          $result_vincrec = db_query($sql_vincrec);
          $linhas_vincrec = pg_num_rows($result_vincrec);
          if ($linhas_vincrec > 0) {

            for ($iVincRec = 0; $iVincRec < $linhas_vincrec; $iVincRec++) {

              db_fieldsmemory($result_vincrec,$iVincRec);

              $clrhlotavincrec ->rh43_codlotavinc = $clrhlotavinc->rh25_codlotavinc;
              $clrhlotavincrec ->rh43_codelenov   = $rh43_codelenov;
              $clrhlotavincrec ->rh43_recurso     = $rh43_recurso;
              $clrhlotavincrec ->incluir($clrhlotavinc->rh25_codlotavinc,$rh43_codelenov);

              if ($clrhlotavincrec->erro_status==0) {
                $sqlerro   = true;
                $erro_msg .= $clrhlotavincrec->erro_msg;
                break;
              }

            }
          }
        } // for
      }

    } else {
      $cldb_viradaitemlog->c35_log           = "Ja processados proj/atividades e recursos da folha para o exercicio $anodestino";
      $cldb_viradaitemlog->c35_codarq        =  749;
      $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
      $cldb_viradaitemlog->c35_data          = date("Y-m-d");
      $cldb_viradaitemlog->c35_hora          = date("H:i");
      $cldb_viradaitemlog->incluir(null);
      if ($cldb_viradaitemlog->erro_status==0) {
        $sqlerro   = true;
        $erro_msg .= $cldb_viradaitemlog->erro_msg;
      }

    }


  } else {
    $cldb_viradaitemlog->c35_log           = "já processado para o exercício $anodestino";
    $cldb_viradaitemlog->c35_codarq        =  1182;
    $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
    $cldb_viradaitemlog->c35_data          = date("Y-m-d");
    $cldb_viradaitemlog->c35_hora          = date("H:i");
    $cldb_viradaitemlog->incluir(null);
    if ($cldb_viradaitemlog->erro_status==0) {
      $sqlerro   = true;
      $erro_msg .= $cldb_viradaitemlog->erro_msg;
    }
  }

}
?>