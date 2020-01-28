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


class brub_ant {

  function brub_ant($header){
    umask(74);
    $this->arq = fopen("tmp/BRUB_ANT.TXT",'w+');
    fputs($this->arq,$header);
    fputs($this->arq,"\r\n");
  }

  function processa($instit=1,$data_ini="",$data_fim="",$orgaotrib=null,$subelemento="") {
    global $o58_codigo,$o58_orgao,$o58_unidade,$o58_funcao,$o58_subfuncao,$o58_programa,$o58_projativ,$o56_elemento;
    global $dot_ini,$suplementado_acumulado,$reduzido_acumulado,$empenhado,$anulado,$liquidado,$pago;
    global $contador,$o58_coddot,$emp1,$emp2,$emp3,$emp4,$emp5,$emp6,$eemp1,$eemp2,$eemp3,$eemp4,$eemp5,$eemp6,
           $liq1,$liq2,$liq3,$liq4,$liq5,$liq6,$eliq1,$eliq2,$eliq3,$eliq4,$eliq5,$eliq6,
           $pag1,$pag2,$pag3,$pag4,$pag5,$pag6,$epag1,$epag2,$epag3,$epag4,$epag5,$epag6;
    $contador=0;

    $tipo_mesini = 1;
    $tipo_mesfim = 1;
    $tipo_agrupa = 3;
    $tipo_nivel = 6;

    $qorgao = 0;
    $qunidade = 0;

    $xtipo = 0;
    $origem = "B";
    $opcao = 3;

    $sele_work = ' o58_instit in ('.str_replace('-',', ',$instit).') ';

    $nomeArq = 'BRUB_ANT.TXT';
    $anousu = (db_getsession("DB_anousu")-1);

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

    if ($oDaoArquivosPad->numrows > 0 ){

      $oArquivo  = db_utils::fieldsMemory($rsDaoArquivosPad,0);
      $sArquivo   =  $oArquivo->c54_arquivo;

      fputs($this->arq, str_replace("\n\r", "", $sArquivo));
      fputs($this->arq,"\r\n");

      $contador = count(explode("\n",$sArquivo));


    } else {


      $sSql = "select o58_orgao,
	     	               o58_unidade,
	     		             o58_funcao,
	     		             o58_subfuncao,
	     		             o58_programa,
	     	               o58_projativ,
	     		             o58_codele as c67_codele,
	     		             o56_elemento,
	     		             o58_codigo,

	     	               round( sum(case when c70_data >= '$anousu-01-01' and c70_data < '$anousu-03-01' and c53_tipo   = 10 then c70_valor end) ,2)       as emp1,
	     	               round( sum(case when c70_data >= '$anousu-01-01' and c70_data < '$anousu-03-01' and c53_tipo   = 11 then c70_valor end) ,2)       as eemp1,
	     	               round( sum(case when c70_data >= '$anousu-01-01' and c70_data < '$anousu-03-01' and c53_tipo   = 20 then c70_valor end) ,2)      as liq1,
	     	               round( sum(case when c70_data >= '$anousu-01-01' and c70_data < '$anousu-03-01' and c53_tipo   = 21 then c70_valor end) ,2)      as eliq1,
	     	               round( sum(case when c70_data >= '$anousu-01-01' and c70_data < '$anousu-03-01' and c71_coddoc = 5 then c70_valor end) ,2)       as pag1,
	     	               round( sum(case when c70_data >= '$anousu-01-01' and c70_data < '$anousu-03-01' and c71_coddoc = 6 then c70_valor end) ,2)       as epag1,

	     	               round( sum(case when c70_data >= '$anousu-03-01' and c70_data < '$anousu-05-01' and c53_tipo   = 10 then c70_valor end) ,2)       as emp2,
	     	               round( sum(case when c70_data >= '$anousu-03-01' and c70_data < '$anousu-05-01' and c53_tipo   = 11 then c70_valor end) ,2)       as eemp2,
	     	               round( sum(case when c70_data >= '$anousu-03-01' and c70_data < '$anousu-05-01' and c53_tipo   = 20 then c70_valor end) ,2)      as liq2,
	     	               round( sum(case when c70_data >= '$anousu-03-01' and c70_data < '$anousu-05-01' and c53_tipo   = 21 then c70_valor end) ,2)      as eliq2,
	     	               round( sum(case when c70_data >= '$anousu-03-01' and c70_data < '$anousu-05-01' and c71_coddoc = 5 then c70_valor end) ,2)       as pag2,
	     	               round( sum(case when c70_data >= '$anousu-03-01' and c70_data < '$anousu-05-01' and c71_coddoc = 6 then c70_valor end) ,2)       as epag2,

	     	               round( sum(case when c70_data >= '$anousu-05-01' and c70_data < '$anousu-07-01' and c53_tipo   = 10 then c70_valor end) ,2)       as emp3,
	     	               round( sum(case when c70_data >= '$anousu-05-01' and c70_data < '$anousu-07-01' and c53_tipo   = 11 then c70_valor end) ,2)       as eemp3,
	     	               round( sum(case when c70_data >= '$anousu-05-01' and c70_data < '$anousu-07-01' and c53_tipo   = 20 then c70_valor end) ,2)      as liq3,
	     	               round( sum(case when c70_data >= '$anousu-05-01' and c70_data < '$anousu-07-01' and c53_tipo   = 21 then c70_valor end) ,2)      as eliq3,
	     	               round( sum(case when c70_data >= '$anousu-05-01' and c70_data < '$anousu-07-01' and c71_coddoc = 5 then c70_valor end) ,2)       as pag3,
	     	               round( sum(case when c70_data >= '$anousu-05-01' and c70_data < '$anousu-07-01' and c71_coddoc = 6 then c70_valor end) ,2)       as epag3,

	     	               round( sum(case when c70_data >= '$anousu-07-01' and c70_data < '$anousu-09-01' and c53_tipo   = 10 then c70_valor end) ,2)       as emp4,
	     	               round( sum(case when c70_data >= '$anousu-07-01' and c70_data < '$anousu-09-01' and c53_tipo   = 11 then c70_valor end) ,2)       as eemp4,
	     	               round( sum(case when c70_data >= '$anousu-07-01' and c70_data < '$anousu-09-01' and c53_tipo   = 20 then c70_valor end) ,2)      as liq4,
	     	               round( sum(case when c70_data >= '$anousu-07-01' and c70_data < '$anousu-09-01' and c53_tipo   = 21 then c70_valor end) ,2)      as eliq4,
	     	               round( sum(case when c70_data >= '$anousu-07-01' and c70_data < '$anousu-09-01' and c71_coddoc = 5 then c70_valor end) ,2)       as pag4,
	     	               round( sum(case when c70_data >= '$anousu-07-01' and c70_data < '$anousu-09-01' and c71_coddoc = 6 then c70_valor end) ,2)       as epag4,

	     	               round( sum(case when c70_data >= '$anousu-09-01' and c70_data < '$anousu-11-01' and c53_tipo   = 10 then c70_valor end) ,2)       as emp5,
	     	               round( sum(case when c70_data >= '$anousu-09-01' and c70_data < '$anousu-11-01' and c53_tipo   = 11 then c70_valor end) ,2)       as eemp5,
	     	               round( sum(case when c70_data >= '$anousu-09-01' and c70_data < '$anousu-11-01' and c53_tipo   = 20 then c70_valor end) ,2)      as liq5,
	     	               round( sum(case when c70_data >= '$anousu-09-01' and c70_data < '$anousu-11-01' and c53_tipo   = 21 then c70_valor end) ,2)      as eliq5,
	     	               round( sum(case when c70_data >= '$anousu-09-01' and c70_data < '$anousu-11-01' and c71_coddoc = 5 then c70_valor end) ,2)       as pag5,
	     	               round( sum(case when c70_data >= '$anousu-09-01' and c70_data < '$anousu-11-01' and c71_coddoc = 6 then c70_valor end) ,2)       as epag5,

	     	               round( sum(case when c70_data >= '$anousu-11-01' and c70_data <= '$anousu-12-31' and c53_tipo   = 10 then c70_valor end) ,2)       as emp6,
	     	               round( sum(case when c70_data >= '$anousu-11-01' and c70_data <= '$anousu-12-31' and c53_tipo   = 11 then c70_valor end) ,2)       as eemp6,
	     	               round( sum(case when c70_data >= '$anousu-11-01' and c70_data <= '$anousu-12-31' and c53_tipo   = 20 then c70_valor end) ,2)      as liq6,
	     	               round( sum(case when c70_data >= '$anousu-11-01' and c70_data <= '$anousu-12-31' and c53_tipo   = 21 then c70_valor end) ,2)      as eliq6,
	     	               round( sum(case when c70_data >= '$anousu-11-01' and c70_data <= '$anousu-12-31' and c71_coddoc = 5 then c70_valor end) ,2)       as pag6,
	     	               round( sum(case when c70_data >= '$anousu-11-01' and c70_data <= '$anousu-12-31' and c71_coddoc = 6 then c70_valor end) ,2)       as epag6

	     	          from orcdotacao
	     	               inner join conlancamdot on o58_anousu = c73_anousu
	     	                                      and o58_coddot = c73_coddot
	     	               inner join conlancamdoc on c73_codlan = c71_codlan
	     	               inner join conhistdoc   on c53_coddoc = c71_coddoc
	     	               inner join conlancam    on c70_codlan = c73_codlan
	     	               inner join conlancamele on c67_codlan = c73_codlan
	     	               inner join orcelemento  on o58_anousu = o56_anousu
	     	                                      and o56_codele = c67_codele
	     	               inner join conlancamemp on c75_codlan = c70_codlan
	     	         where o58_anousu    = $anousu
	     	           and ".$sele_work."
	     	         group by o58_orgao,
	     	                  o58_unidade,
	     		                o58_funcao,
	     		                o58_subfuncao,
	     		                o58_programa,
	     	                  o58_projativ,
               			      o58_codele,
               			      o56_elemento,
               			      o58_codigo";
      $result = db_query($sSql);

      $totalzao = 0;
      $totalsup = 0;
      $totalcre = 0;
      $totalesp = 0;

      for ($i=0;$i<pg_numrows($result);$i++) {

        db_fieldsmemory($result,$i);

        if ($o58_codigo > 0) {

          $line  = formatar($o58_orgao,2,'n');
          $line .= formatar($o58_unidade,2,'n');
          $line .= formatar($o58_funcao,2,'n');
          $line .= formatar($o58_subfuncao,3,'n');
          $line .= formatar($o58_programa,4,'n');
          $line .= formatar(0,3,'n'); // subprograma
          $line .= formatar($o58_projativ,5,'n');
          // $line .= substr($o56_elemento,0,13).'00';
          if ((db_getsession("DB_anousu")-1) >= 2005) {
            // veja o arquivo con4_padrubrica.php
            $ele  = substr($o56_elemento,1,14);
            $ele  = trim($ele).'0000';
            $line .=  substr($ele,0,13).'00';
          } else {
            $ele  = substr($o56_elemento,0,14);
            $ele  = trim($ele).'0000';
            $line .=  substr($ele,0,13).'00';
          }

          $line .= formatar($o58_codigo,4,'n');

          for($mes=1;$mes<7;$mes++){

            $e     = "emp".$mes;
            $ee    = "eemp".$mes;
            $valor = dbround_php_52($$e - $$ee,2);
            if ($valor < 0) {
              $line .= "-"  . formatar(abs($valor),10,'v');
            } else {
              $line .= formatar($valor,11,'v');
            }

          }

          for ($mes=1;$mes<7;$mes++) {

            $e     = "liq".$mes;
            $ee    = "eliq".$mes;
            $valor = dbround_php_52($$e - $$ee,2);
            if ($valor < 0) {
              $line .= "-"  . formatar(abs($valor),10,'v');
            } else {
              $line .= formatar($valor,11,'v');
            }
          }

          for ($mes = 1; $mes < 7; $mes++) {

            $e     = "pag".$mes;
            $ee    = "epag".$mes;
            $valor = dbround_php_52($$e - $$ee,2);
            if ($valor < 0) {
              $line .= "-"  . formatar(abs($valor),10,'v');
            } else {
              $line .= formatar($valor,11,'v');
            }
          }

          $contador ++;

          fputs($this->arq,$line);
          fputs($this->arq,"\r\n");
        }

      }

    }

    //  trailer
    $contador = espaco(10-(strlen($contador)),'0').$contador;
    $line = "FINALIZADOR".$contador;
    fputs($this->arq,$line);
    fputs($this->arq,"\r\n");
    fclose($this->arq);
    $teste = "true";
    return $teste;
  }
}