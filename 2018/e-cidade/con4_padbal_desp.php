<?
class bal_desp  {

  var $arq=null;

  function bal_desp($header){
    umask(74);
    $this->arq = fopen("tmp/BAL_DESP.TXT",'w+');
    fputs($this->arq,$header);
    fputs($this->arq,"\r\n");
  }  

  function processa($instit=1,$data_ini="",$data_fim="",$orgaotrib=null,$subelemento="") {
    global $o58_codigo,$o58_orgao,$o58_unidade,$o58_funcao,$o58_subfuncao,$o58_programa,$o58_projativ,$o58_elemento,$o58_codele;
    global $dot_ini,$suplementado_acumulado,$reduzido_acumulado,$empenhado,$anulado,$liquidado,$pago;
    global $contador,$o58_coddot;
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

    $sql_teste = "
      select 	orcdotacao.o58_coddot, 
              (select count(*) from conlancamdot where conlancamdot.c73_data between '$data_ini' and '$data_fim' and conlancamdot.c73_coddot = orcdotacao.o58_coddot) as quant, 
              orcdotacao.o58_orgao, 
              orcdotacao.o58_unidade, 
              orcdotacao.o58_funcao, 
              orcdotacao.o58_subfuncao, 
              orcdotacao.o58_programa, 
              orcdotacao.o58_projativ, 
              orcdotacao.o58_codele, 
              orcdotacao.o58_codigo, 
              orcdotacao.o58_valor from (
                  select 	o58_orgao, 
                  o58_unidade, 
                  o58_funcao, 
                  o58_subfuncao, 
                  o58_programa, 
                  o58_projativ, 
                  o58_codele, 
                  o58_codigo, 
                  count(*) 
                  from orcdotacao 
                  where o58_anousu = " . db_getsession("DB_anousu") . "
                  group by 	o58_orgao, 
                  o58_unidade, 
                  o58_funcao, 
                  o58_subfuncao, 
                  o58_programa, 
                  o58_projativ, 
                  o58_codele, 
                  o58_codigo 
                  having count(*) > 1) as x 
                  inner join orcdotacao on orcdotacao.o58_orgao = x.o58_orgao and orcdotacao.o58_unidade = x.o58_unidade and orcdotacao.o58_funcao = x.o58_funcao and orcdotacao.o58_subfuncao = x.o58_subfuncao and orcdotacao.o58_programa = x.o58_programa and orcdotacao.o58_projativ = x.o58_projativ and orcdotacao.o58_codele = x.o58_codele and orcdotacao.o58_codigo = x.o58_codigo 
                  where orcdotacao.o58_anousu = " . db_getsession("DB_anousu") . "
                  order by orcdotacao.o58_orgao, orcdotacao.o58_unidade, orcdotacao.o58_funcao, orcdotacao.o58_subfuncao, orcdotacao.o58_programa, orcdotacao.o58_projativ, orcdotacao.o58_codele, orcdotacao.o58_codigo";
    $result_teste = db_query($sql_teste) or die($sql_teste);
    //db_criatabela($result_teste);exit;
    if (pg_numrows($result_teste) > 0) {

      $dotacoes = "";

      db_fieldsmemory($result_teste, 0);
      $ult_estrut = formatar($o58_orgao,2,'n') . formatar($o58_unidade,2,'n') . formatar($o58_funcao,2,'n') . formatar($o58_subfuncao,3,'n') . formatar($o58_programa,4,'n') . formatar($o58_projativ,5,'n') . formatar($o58_codele,10,'n') . formatar($o58_codigo,4,'n');

      for ($x=0; $x < pg_numrows($result_teste); $x++) {
        db_fieldsmemory($result_teste, $x);

        $atu_estrut = formatar($o58_orgao,2,'n') . formatar($o58_unidade,2,'n') . formatar($o58_funcao,2,'n') . formatar($o58_subfuncao,3,'n') . formatar($o58_programa,4,'n') . formatar($o58_projativ,5,'n') . formatar($o58_codele,10,'n') . formatar($o58_codigo,4,'n');

        if ($ult_estrut === $atu_estrut) {
          //	  echo "igual - ultimo: $ult_estrut - atu: $atu_estrut <br><br><br><br><br>";
        } else {
          //	  echo "dif - ultimo: $ult_estrut - atu: $atu_estrut <br><br><br><br><br>";
          //          $dotacoes .= "<br> === <br>";
          $dotacoes .= "<br>";
        }

        $dotacoes .= $o58_coddot . " - ";

        $ult_estrut = formatar($o58_orgao,2,'n') . formatar($o58_unidade,2,'n') . formatar($o58_funcao,2,'n') . formatar($o58_subfuncao,3,'n') . formatar($o58_programa,4,'n') . formatar($o58_projativ,5,'n') . formatar($o58_codele,10,'n') . formatar($o58_codigo,4,'n');

      }
      echo "<font color='red'><br><b>DOTACOES DUPLICADAS:</b><br>$dotacoes<br></font>";

    }

    $sele_work = ' w.o58_instit in ('.str_replace('-',', ',$instit).') ';

    db_query("begin");
    db_query("create temp table t as select * from orcdotacao where o58_anousu = ".db_getsession("DB_anousu"));

    $sele_work = " w.o58_instit in ($instit)";

    $anousu = db_getsession("DB_anousu");

    if ($subelemento=="sim"){
      $result = db_dotacaosaldo(8,1,4,true,$sele_work,$anousu,$data_ini,$data_fim,'8','0',false,'1',true,"sim");
    }else {	
      $result = db_dotacaosaldo(8,1,4,true,$sele_work,$anousu,$data_ini,$data_fim);
    }
    db_query("rollback");
//    db_criatabela($result);exit;

    $dotacoes = "";
    for($i=0;$i<pg_numrows($result);$i++){
      db_fieldsmemory($result,$i);

      if($o58_coddot > 0 and $o58_codigo <= 0){
        $dotacoes .= $o58_coddot . " - <br>";
      }

    }
    if ( $dotacoes != "" ) {
      echo "<font color='red'><br><b>DOTACOES COM RECURSO ZERADO:</b><br>$dotacoes<br></font>";
    }

    $totalzao = 0;
    $totalsup = 0;
    $totalcre = 0;
    $totalesp = 0;
    for($i=0;$i<pg_numrows($result);$i++){
      db_fieldsmemory($result,$i);
      if($o58_codigo > 0){
        $line  = formatar($o58_orgao,2,'n');
        $line .= formatar($o58_unidade,2,'n');
        $line .= formatar($o58_funcao,2,'n');
        $line .= formatar($o58_subfuncao,3,'n');
        $line .= formatar($o58_programa,4,'n');
        $line .= formatar(0,3,'n'); // subprograma
        $line .= formatar($o58_projativ,5,'n');
        $line .= substr($o58_elemento,1,6);
        $line .= formatar($o58_codigo,4,'n');
        $line .= formatar($dot_ini,13,'v'); // dotacao inicial
        $line .= formatar(0,13,'v'); // atualizacao monetaria
        $sup=0;
        $cre=0;
        $esp=0;

        // leandro contador pediu para passar teste do coddoc 71 de cre (credito especial) para sup (credito suplementar)
        // pois estava dando problema em sapiranga
        // 2007-03-27_15:30

        $sql = "
          select sum(case when c71_coddoc in (7,52,53,54,55,71) then c70_valor else 0 end ) -
          sum(case when c71_coddoc in (8) then c70_valor else 0 end )
          as sup ,
             sum(case when c71_coddoc in (62,56,58,59,60,61,64) then c70_valor else 0 end ) -
               sum(case when c71_coddoc in (10) then c70_valor else 0 end )
               as cre,
             sum(case when c71_coddoc in (63) then c70_valor else 0 end )-
               sum(case when c71_coddoc in (14) then c70_valor else 0 end )
               as esp
               from conlancamdoc 
               inner join conlancam on c70_codlan = c71_codlan
               inner join conlancamdot on c73_codlan = c71_codlan 
               inner join conlancamsup on c79_codlan = c71_codlan 
               where c71_coddoc in (7,8,10,14,52,53,54,55,56,58,59,60,61,62,63,64,71)
               and c71_data between '$data_ini' and '$data_fim'
               and c73_coddot = $o58_coddot and
               c73_anousu = ".db_getsession("DB_anousu");

        $sql_desdobramento = "
          select sum(case when c71_coddoc in (7,52,53,54,55) then c70_valor else 0 end ) as sup ,
                 sum(case when c71_coddoc in (56,58,59,60,61,64) then c70_valor else 0 end ) as cre,
                 sum(case when c71_coddoc in (62,63) then c70_valor else 0 end ) as esp
                   from conlancamdoc 
                   inner join conlancam on c70_codlan = c71_codlan
                   inner join conlancamdot on c73_codlan = c71_codlan 
                   inner join conlancamsup on c79_codlan = c71_codlan
                   inner join orcdotacao  on c73_coddot = o58_coddot and 
                   o58_anousu = ".db_getsession("DB_anousu")." and 
                   o58_orgao = $o58_orgao and 
                   o58_unidade = $o58_unidade and 
                   o58_funcao = $o58_funcao and 
                   o58_subfuncao = $o58_subfuncao and 
                   o58_programa = $o58_programa and 
                   o58_projativ = $o58_projativ and o58_codigo = $o58_codigo
                   inner join orcelemento on o56_codele = o58_codele and o56_anousu = o58_anousu
                   where c71_coddoc in (7,52,53,54,55,56,58,59,60,61,62,63,64)
                   and c71_data between '$data_ini' and '$data_fim'
                   /* and c73_coddot = $o58_coddot */
                   and substr(o56_elemento,1,7)='".substr($o58_elemento,0,7)."'
                   and c73_anousu = ".db_getsession("DB_anousu"); 


                   if ($subelemento=="sim"){
                     $resultsup = db_query($sql_desdobramento);	    
                   } else {
                     $resultsup = db_query($sql);	   
                   }  
        if (pg_numrows($resultsup)>0){
          $sup = pg_result($resultsup,0,0)+0; 
          $cre = pg_result($resultsup,0,1)+0; 
          $esp = pg_result($resultsup,0,2)+0; 

        }

        $totalzao += $sup + $cre + $esp;
        $totalsup += $sup;
        $totalcre += $cre;
        $totalesp += $esp;

        $line .= formatar(round($sup,2),13,'v'); // creditos suple
        $line .= formatar(round($cre,2),13,'v'); // creditos especial
        $line .= formatar(round($esp,2),13,'v'); // creditos extraordinarios

        $line .= formatar(abs(round($reduzido_acumulado,2)),13,'v'); // reducoes
        $line .= formatar(0,13,'v'); // suple recurso vinculado
        $line .= formatar(0,13,'v'); // reducao recurso vinculado
        $line .= formatar(abs(round($empenhado-$anulado,2)),13,'v');
        $line .= formatar(abs(round($liquidado,2)),13,'v'); // liquidado
        $line .= formatar(abs(round($pago,2)),13,'v'); // pago
        $line .= formatar(0,13,'v'); // limitado
        $line .= formatar(0,13,'v'); // recomposi��o
        $line .= formatar(0,13,'v'); // previsao

        $contador ++;

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
    return $teste;

  }

}

?>
