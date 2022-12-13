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

class empenho {
  var $arq=null;

  function empenho($header){
    umask(74);
    $this->arq = fopen("tmp/EMPENHO.TXT",'w+');
    fputs($this->arq,$header);
    fputs($this->arq,"\r\n");
  }

  function processa($instit=1,$data_ini="",$data_fim="",$tribinst=null,$subelemento="") {
    global $contador,$nomeinst,$o58_unidade,$o58_funcao,$o58_subfuncao,$o58_programa,$o58_subprograma,$o58_coddot,$e60_numemp,$e60_anousu,$e60_concarpeculiar;

    $contador=0;
    $sele = " ($instit) ";

    $sSqlTesta = "select e60_anousu, e60_numemp, e60_instit, e64_codele, o56_elemento, o56_descr from empelemento 
inner join empempenho on empempenho.e60_numemp = empelemento.e64_numemp 
inner join conlancamemp on c75_numemp = e60_numemp
left join orcelemento on orcelemento.o56_codele = empelemento.e64_codele and orcelemento.o56_anousu = empempenho.e60_anousu 
where o56_elemento is null and c75_data >='$data_ini' and c75_data <='$data_fim' and e60_emiss <='$data_fim' and e60_instit in $sele";
    $rsTesta  = db_query($sSqlTesta) or die($sSqlTesta);

    if ( pg_numrows($rsTesta) > 0 ) {
      echo "<br><b>PROVAVEIS ERROS NOS REGISTROS - SEM DESDOBRAMENTO VINCULADO:</b><br>";
      for ($x=0;$x < pg_numrows($rsTesta);$x++) {

        $anousu_erro = pg_result($rsTesta,$x,"e60_anousu");
        $numemp_erro = pg_result($rsTesta,$x,"e60_numemp");
        $instit_erro = pg_result($rsTesta,$x,"e60_instit");
        $codele_erro = pg_result($rsTesta,$x,"e64_codele");

        echo "ano: $anousu_erro - numemp: $numemp_erro - instit: $instit_erro - codele: $codele_erro <br>";

      }
      echo "<br>";
      flush();
    }


    $sql=" select
             e60_numemp,
	           e60_anousu,
		         trim(e60_codemp)::integer as e60_codemp,
		         o58_coddot,
             o58_orgao,
             o58_unidade,
    	       o58_funcao,
 	           o58_subfuncao,
             o58_programa,
 	           o58_projativ, 
		         case when o58_anousu >= 2005 then
  		              substr(trim(substr(o56_elemento,2,14))||'00000000000',1,15)::varchar(15)
  		       else
		              substr(trim(o56_elemento)||'000000000',1,15)::varchar(15)
		         end as rubrica,
	           o58_codigo as recurso,
		         (case when c71_coddoc in(32,31) then c70_data else e60_emiss end) as e60_emiss,
	           c70_valor as valor_empenho,
	           (case when c53_tipo = 10 then '+' else '-' end)::char(1) as sinal,  
	           e60_numcgm,
             ('DOT:['||e60_coddot||'] '|| 'NUMEMP:['||e60_numemp||']'||e60_resumo) as e60_resumo,
		         e60_instit,
             e60_concarpeculiar,
             e60_numerol
           from empempenho 
	            inner join conlancamemp on c75_numemp = e60_numemp
	            inner join conlancamdoc on c71_codlan = c75_codlan
              inner join conhistdoc on c53_coddoc = c71_coddoc
	            inner join conlancam on c70_codlan = c75_codlan
              inner join orcdotacao on o58_coddot=e60_coddot and o58_anousu=e60_anousu and o58_instit = e60_instit
		          inner join orcelemento on o56_codele = o58_codele and o56_anousu = o58_anousu
           where c75_data >='$data_ini' and c75_data <='$data_fim'  
	           and e60_emiss <='$data_fim' 
--		         and c71_coddoc in (1,2,31,32)
		         and c53_tipo in (10,11)
             and e60_instit in $sele
		       
            /*   1  - ref. a empenho
		             2  - ref. a anulacao de empenho
		            31 - ref. a anulacao de RP
		            32 - ref. a anulacao de RP
		        */

           union all

	           select  distinct (e91_numemp) ,
		                 e60_anousu,
		                 trim(e60_codemp)::integer as e60_codemp,
		                 o58_coddot,
                     o58_orgao,
                     o58_unidade,
    	               o58_funcao,
 	                   o58_subfuncao,
                     o58_programa,
 	                   o58_projativ,
		                 case when o58_anousu >= 2005 then
  		                  substr(trim(substr(o56_elemento,2,14))||'00000000000',1,15)::varchar(15)
  		               else
		                    substr(trim(o56_elemento)||'000000000',1,15)::varchar(15)
		                 end as rubrica,
	                   o58_codigo as recurso,
	                   e60_emiss,
	                   round((e91_vlremp-e91_vlranu-e91_vlrpag),2)::float8 as valor_empenho,
	                   '+'::char(1) as sinal,  
	                   e60_numcgm,
                     ('DOT:['||e60_coddot||'] '|| 'NUMEMP:['||e60_numemp||']'||e60_resumo) as e60_resumo,
		                 e60_instit,
                     e60_concarpeculiar,
                     e60_numerol
            from empresto
                 inner join empempenho on e60_numemp = e91_numemp        
	               inner join orcdotacao on o58_coddot=e60_coddot and  o58_anousu=e60_anousu and o58_instit = e60_instit
	               inner join orcelemento on o56_codele = o58_codele and o56_anousu = o58_anousu
	          where e91_anousu = ".db_getsession("DB_anousu")."	
                    and e60_instit in $sele
                    and e91_rpcorreto is false
                    

            union all
            
            
            select
             e60_numemp,
	         e60_anousu,
		     trim(e60_codemp)::integer as e60_codemp,
		     o58_coddot,
             o58_orgao,
             o58_unidade,
    	     o58_funcao,
 	         o58_subfuncao,
             o58_programa,
 	         o58_projativ, 
		       case when o58_anousu >= 2005 then
  		              substr(trim(substr(o56_elemento,2,14))||'00000000000',1,15)::varchar(15)
  		       else
		              substr(trim(o56_elemento)||'000000000',1,15)::varchar(15)
		         end as rubrica,
	           o58_codigo as recurso,
		         (case when c71_coddoc in(32,31) then c70_data else e60_emiss end) as e60_emiss,
	           c70_valor as valor_empenho,
	           (case when c53_tipo = 10 then '+' else '-' end)::char(1) as sinal,  
	           e60_numcgm,
             ('DOT:['||e60_coddot||'] '|| 'NUMEMP:['||e60_numemp||']'||e60_resumo) as e60_resumo,
		     e60_instit,
             e60_concarpeculiar,
             e60_numerol
           from empresto
                inner join empempenho on e91_numemp = e60_numemp
	            inner join conlancamemp on c75_numemp = e60_numemp
	            inner join conlancamdoc on c71_codlan = c75_codlan
              inner join conhistdoc on c53_coddoc = c71_coddoc
	            inner join conlancam on c70_codlan = c75_codlan
                inner join orcdotacao on o58_coddot=e60_coddot and o58_anousu=e60_anousu and o58_instit = e60_instit
		        inner join orcelemento on o56_codele = o58_codele and o56_anousu = o58_anousu
           where e91_anousu = ".db_getsession("DB_anousu")." 
               and c75_data between '$data_ini' and '$data_fim'
               and e91_rpcorreto is true
               and e60_instit in $sele



	          order by  
                   o58_orgao,
                   o58_unidade,
    	           o58_funcao,
 	           o58_subfuncao,
                   o58_programa,
 	           o58_projativ, 
		   rubrica,
		   e60_emiss
       ";

    $res  = db_query($sql) or die($sql);
    $rows = pg_numrows($res);

    //db_criatabela($res); exit;
    for ($x=0;$x < $rows;$x++){
      db_fieldsmemory($res,$x);
      $ano     = pg_result($res,$x,"e60_anousu");
      $orgao   = formatar(pg_result($res,$x,"o58_orgao"),2,'n');
      $instituicao= pg_result($res,$x,"e60_instit");
      // se ano menor que 2005, pega funcao, subfuncao e subprograma da tabel orcdotacaorp
      if ($ano < 2005){
        $sql = "select o73_funcao      as o58_funcao,         
	                    o73_subfuncao   as o58_subfuncao,
			    o73_subprograma as o58_subprograma
                     from orcdotacaorp 
		     where o73_anousu=$ano
		        and o73_coddot=$o58_coddot
                 ";
        $rr = db_query($sql);
        if (pg_numrows($rr)>0){
          db_fieldsmemory($rr,0);
        }

        $sql = "select o32_subprog as o58_subprograma  
                     from orcsubprogramarp 
		                 where o32_anousu=$ano
                    ";
        $rr = db_query($sql);
        if (pg_numrows($rr)>0){
          db_fieldsmemory($rr,0);
        }

      }

      // de acordo com o Marcus Vinicius o subprograma deverá ser zero, pois o PAD não usará mais essa informação
      $o58_subprograma=000;
      $unidade      = formatar($o58_unidade,2,'n');
      $funcao       = formatar($o58_funcao,2,'n');
      $subfuncao    = formatar($o58_subfuncao,3,'n');
      $programa     = formatar($o58_programa,4,'n');
      $subprograma  = formatar($o58_subprograma,3,'n');
      $proj_ativ    = formatar(pg_result($res,$x,"o58_projativ"),5,'n');

      //--- * --- * ---
      // $rubrica = pg_result($res,$x,"rubrica");
      // $rubrica = substr(trim($rubrica),0,15)."00000000000000";//

      $iModalidadeLicitacao          = '';
      $sDescricaoModalidadeLicitacao = '';
      $sRegistroPreco                = 'N';
      $sOutrasModalidades            = '';
      $sNumeroLicitacao              = '';
      $iAnoLicitacao                 = 0;
      $sSqlEmpAutItem     = " select distinct l20_numero as l20_codigo,                                                                                         ";
      $sSqlEmpAutItem    .= "        l20_anousu,                                                                                                  ";
      $sSqlEmpAutItem    .= "        ( select l44_codigotribunal from pctipocompratribunal where l44_sequencial = l03_pctipocompratribunal ) as l44_codigotribunal, ";
      //          $sSqlEmpAutItem    .= "        l44_codigotribunal,                                                                                          ";
      $sSqlEmpAutItem    .= "        pc50_descr                                                                                                   ";
      $sSqlEmpAutItem    .= "   from empautitem                                                                                                   ";
      $sSqlEmpAutItem    .= "        inner join empautitempcprocitem on empautitempcprocitem.e73_sequen = empautitem.e55_sequen                   ";
      $sSqlEmpAutItem    .= "                                       and empautitempcprocitem.e73_autori = empautitem.e55_autori                   ";
      $sSqlEmpAutItem    .= "        inner join liclicitem           on liclicitem.l21_codpcprocitem    = empautitempcprocitem.e73_pcprocitem     ";
      $sSqlEmpAutItem    .= "        inner join liclicita            on liclicitem.l21_codliclicita     = liclicita.l20_codigo                    ";
      $sSqlEmpAutItem    .= "        inner join cflicita             on liclicita.l20_codtipocom        = cflicita.l03_codigo                     ";
      $sSqlEmpAutItem    .= "        inner join pctipocompra         on cflicita.l03_codcom             = pc50_codcom                             ";
      //          $sSqlEmpAutItem    .= "        inner join pctipocompratribunal on l03_pctipocompratribunal        = l44_sequencial                          ";
      $sSqlEmpAutItem    .= "        inner join empautoriza          on empautoriza.e54_autori          = empautitem.e55_autori                   ";
      $sSqlEmpAutItem    .= "        inner join empempaut            on e61_autori                      = e54_autori                              ";
      $sSqlEmpAutItem    .= "  where e61_numemp = {$e60_numemp}                                                                                   ";
      $rsSqlEmpAutItem    = db_query($sSqlEmpAutItem);
      $iNumRowsEmpAutItem = pg_num_rows($rsSqlEmpAutItem);
      if ($iNumRowsEmpAutItem > 0) {

        $oEmpAutItem                   = db_utils::fieldsMemory($rsSqlEmpAutItem, 0);
        $iModalidadeLicitacao          = $oEmpAutItem->l44_codigotribunal;
        $sDescricaoModalidadeLicitacao = $oEmpAutItem->pc50_descr;
        $sNumeroLicitacao              = $oEmpAutItem->l20_codigo;
        $iAnoLicitacao                 = $oEmpAutItem->l20_anousu;
      } else {

        $sSqlEmpEmpenho     = " select distinct l44_codigotribunal,                                                  ";
        $sSqlEmpEmpenho    .= "        pc50_descr                                                                    ";
        $sSqlEmpEmpenho    .= "   from empempenho                                                                    ";
        $sSqlEmpEmpenho    .= "        inner join pctipocompra         on e60_codcom                = pc50_codcom    ";
        $sSqlEmpEmpenho    .= "        inner join pctipocompratribunal on pc50_pctipocompratribunal = l44_sequencial ";
        $sSqlEmpEmpenho    .= "  where e60_numemp = {$e60_numemp}                                                    ";
        $rsSqlEmpEmpenho    = db_query($sSqlEmpEmpenho);
        $iNumRowsEmpEmpenho = pg_num_rows($rsSqlEmpEmpenho);
        if ($iNumRowsEmpEmpenho > 0) {

          $oEmpEmpenho                   = db_utils::fieldsMemory($rsSqlEmpEmpenho, 0);
          $iModalidadeLicitacao          = $oEmpEmpenho->l44_codigotribunal;
          $sDescricaoModalidadeLicitacao = $oEmpEmpenho->pc50_descr;
        }
      }

      if ($iModalidadeLicitacao == '99') {
        $sOutrasModalidades = "{$iModalidadeLicitacao} - {$sDescricaoModalidadeLicitacao}";
      }

      $sSqlRegistroPreco     = " select pc11_numero,                                                            ";
      $sSqlRegistroPreco    .= "        pc10_solicitacaotipo                                                    ";
      $sSqlRegistroPreco    .= "   from empempenho                                                              ";
      $sSqlRegistroPreco    .= "        inner join empempitem           on e60_numemp        = e62_numemp       ";
      $sSqlRegistroPreco    .= "        inner join empempaut            on e61_numemp        = e60_numemp       ";
      $sSqlRegistroPreco    .= "        inner join empautoriza          on e61_autori        = e54_autori       ";
      $sSqlRegistroPreco    .= "        inner join empautitem           on e54_autori        = e55_autori       ";
      $sSqlRegistroPreco    .= "                                       and e62_sequen        = e55_sequen       ";
      $sSqlRegistroPreco    .= "        inner join empautitempcprocitem on e73_sequen        = e55_sequen       ";
      $sSqlRegistroPreco    .= "                                       and e73_autori        = e55_autori       ";
      $sSqlRegistroPreco    .= "        inner join pcprocitem           on  pc81_codprocitem = e73_pcprocitem   ";
      $sSqlRegistroPreco    .= "        inner join solicitem            on pc11_codigo       = pc81_solicitem   ";
      $sSqlRegistroPreco    .= "        inner join solicita             on pc11_numero       = pc10_numero      ";
      $sSqlRegistroPreco    .= "  where e62_numemp           = {$e60_numemp}                                    ";
      $sSqlRegistroPreco    .= "    and pc10_solicitacaotipo = 5                                                ";
      $rsSqlRegistroPreco    = db_query($sSqlRegistroPreco);
      $iNumRowsRegistroPreco = pg_num_rows($rsSqlRegistroPreco);
      if ($iNumRowsRegistroPreco > 0) {
        $sRegistroPreco = 'S';
      }

      $rubrica_despesa   = formatar(pg_result($res,$x,"rubrica"),15,'c'); // pendente

      if($e60_anousu >= 2005 ){

        $sqlele = "select o56_elemento
                  from empempenho
                       inner join empelemento on e60_numemp = e64_numemp
                       inner join orcelemento on o56_codele = e64_codele and o56_anousu = e60_anousu
                  where e60_numemp = $e60_numemp limit 1";

        $resrub = db_query($sqlele);
        if($resrub == false || pg_numrows($resrub)==0){
          echo "Verifique empenho (numemp: $e60_numemp) sem elemento cadastrado";exit;
        }
        $rubrica_despesa = formatar(substr(pg_result($resrub,0,"o56_elemento"),1,12)."000",15,'c'); // pendente

      }

      $recurso               = formatar(pg_result($res,$x,"recurso"),4,'n');
      $contrapartida_recurso =  espaco(4,'0'); // pendente
      $numero_empenho        = $ano.str_pad($instituicao, 2, "0", STR_PAD_LEFT)."0".formatar(pg_result($res,$x,"e60_codemp"),6,'n');
      $data_empenho          = formatar(pg_result($res,$x,"e60_emiss"),8,'d');
      $valor_empenho         = formatar(pg_result($res,$x,"valor_empenho")  ,13,'v');
      $sinal_valor           = pg_result($res,$x,"sinal");

      $codigo_credor         = formatar(pg_result($res,$x,"e60_numcgm"),10,'n');
      //$codigo_credor = "9999999999";

      $hist                  = pg_result($res,$x,"e60_resumo");
      $e60_numerol           = pg_result($res,$x,"e60_numerol");
      $concarpeculiar        = formatar((int) pg_result($res,$x,"e60_concarpeculiar"),3,"n");


      if (trim($e60_numerol) != "" && trim($e60_numerol) != '0') {
        if ($sNumeroLicitacao == "") {

          $sNumeroLicitacao = trim($e60_numerol);
          $iAnoLicitacao    = $ano;
        }
      }
      if (($iModalidadeLicitacao != "00" && $iModalidadeLicitacao != '01') && trim($sNumeroLicitacao) == "") {

        $sNumeroLicitacao = "S/N";
        if (db_getsession("DB_anousu") >= 2014) {
          $sNumeroLicitacao = "";
        }
        $iAnoLicitacao    = $ano;
      }
      if ($hist == "") {
        $hist = "sem resumo";
      }

      if (db_getsession("DB_anousu") >= 2014 && empty($sNumeroLicitacao)) {
        $iAnoLicitacao = '';
      }

      $hist              = str_replace("\n", " ", $hist);
      $hist              = str_replace("\r", "",  $hist);
      $historico_empenho = formatar(" ", 165, 'c');

      $sNovoHistorico    = formatar($hist, 400, 'c');



      // A partir de 2008, vigora o uso de CARACTERISTICA PECULIAR
      if (db_getsession("DB_anousu") > 2007){

        $line = $orgao
          . $unidade
          . $funcao
          . $subfuncao
          . $programa
          . $subprograma
          . $proj_ativ
          . $rubrica_despesa
          . $recurso
          . $contrapartida_recurso
          . $numero_empenho
          . $data_empenho
          . $valor_empenho
          . $sinal_valor
          . $codigo_credor
          . $historico_empenho
          . $concarpeculiar ;

        if (db_getsession("DB_anousu") >= 2011) {

          $sNumeroLicitacaoPAD = ' ';
          if (db_getsession("DB_anousu") >= 2014) {
            $sNumeroLicitacao = str_replace('/', '', $sNumeroLicitacao);
            $sNumeroLicitacaoPAD = '0';
          }

          $iModalidadeLicitacao = str_pad($iModalidadeLicitacao ,  2, ' ', STR_PAD_LEFT);
          $sRegistroPreco       = str_pad($sRegistroPreco       ,  1, ' ', STR_PAD_LEFT);
          $sOutrasModalidades   = substr(str_pad($sOutrasModalidades   , 20, ' ', STR_PAD_LEFT),0,20);
          $sNumeroLicitacao     = str_pad($sNumeroLicitacao     , 20, $sNumeroLicitacaoPAD, STR_PAD_LEFT);
          $iAnoLicitacao        = str_pad($iAnoLicitacao        ,  4, '0', STR_PAD_LEFT);


          ///echo "antigo *{$historico_empenho}* <br> novo *{$sNovoHistorico}*";
          //die();


          if (db_getsession("DB_anousu") >= 2014) {

            $aSiglasCodigos = array(
              '00' => 'NSA',
              '05' => 'CNC',
              '04' => 'TMP',
              '03' => 'CNV',
              '07' => 'PRE',
              '06' => 'PRP',
              '02' => 'PRI',
              '08' => 'RIN',
              '09' => 'CNS',
              '10' => 'RDC',
              '11' => 'RPO',
              '12' => 'DPV',
              '13' => 'PRD',
              '14' => 'CHP',
            );

            $sSigla = isset($aSiglasCodigos[$iModalidadeLicitacao]) ? $aSiglasCodigos[$iModalidadeLicitacao] : 'NSA';

            /**
             * Campos obsoletos
             */
            $iModalidadeLicitacao = str_repeat(' ', 2);
            $sOutrasModalidades = str_repeat(' ', 20);
          }

          $line .= $iModalidadeLicitacao.$sRegistroPreco.$sOutrasModalidades.$sNumeroLicitacao.$iAnoLicitacao.$sNovoHistorico;

          if (db_getsession("DB_anousu") >= 2014) {
            $line .= $sSigla;
          }

        }
      } else {
        $line = $orgao
          . $unidade
          . $funcao
          . $subfuncao
          . $programa
          . $subprograma
          . $proj_ativ
          . $rubrica_despesa
          . $recurso
          . $contrapartida_recurso
          . $numero_empenho
          . $data_empenho
          . $valor_empenho
          . $sinal_valor
          . $codigo_credor
          . $historico_empenho;
      }

      fputs($this->arq, $line);
      fputs($this->arq,"\r\n");

      $contador = $contador+1; // incrementa contador global
    }



    $sql_testa_rp = "select * from empresto where e91_anousu = " . db_getsession("DB_anousu") . " and round(e91_vlrpag,2) > round(e91_vlrliq,2)";
    $result_testa_rp = db_query($sql_testa_rp) or die($sql_testa_rp);

    if (pg_numrows($result_testa_rp) > 0) {
      echo "<br><b>RESTOS A PAGAR COM REGISTROS DE PAGAMENTOS MAIORES QUE LIQUIDAÇÕES:</b><br>";
      for ($x=0; $x < pg_numrows($result_testa_rp); $x++) {
        db_fieldsmemory($result_testa_rp, $x);
        echo "NUMEMP: $e91_numemp - VALOR LIQUIDADO: $e91_vlrliq - VALOR PAGO: $e91_vlrpag<br>";
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

?>
