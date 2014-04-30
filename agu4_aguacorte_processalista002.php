<?
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

require("fpdf151/pdf.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_SERVER_VARS);

/***
 *
 * Rotina que Imprime a Lista de Corte
 *
 */

$rotulo = new rotulocampo();
$rotulo->label("x01_codrua");
$rotulo->label("j14_nome");
$rotulo->label("x01_numero");
$rotulo->label("x11_complemento");
$rotulo->label("x04_nrohidro");
$rotulo->label("x03_sigla");
$rotulo->label("x42_codsituacao");
$rotulo->label("x01_matric");

$where = "";
$wherehidro = "";

if(!empty($x40_codcorte)) {
  $where .= " and x41_codcorte = $x40_codcorte ";
  $head7 = "Corte: $x40_codcorte   ";

  $sQueryAguaCorte = "select x40_tipomatricula from aguacorte where x40_codcorte = $x40_codcorte";
  $result = db_query($sQueryAguaCorte) or die($sQueryAguaCorte);
  $x40_tipomatricula = pg_result($result,0);

  if ($x40_tipomatricula == 1){
    $head7 .= "  Matrícula: TODAS   ";
  }else if($x40_tipomatricula == 2){
    $head7 .= "  Matrícula: TERRITORIAIS   ";
  }else if ($x40_tipomatricula == 3){
    $head7 .= "  Matrícula: PREDIAIS   ";
  }
}

if(!empty($x01_codrua)) {
  $where .= " and x01_codrua = $x01_codrua ";
}

if(!empty($x01_zona)) {
  $where .= " and x01_zona = $x01_zona ";
}

if(!empty($x01_entrega)) {
  $where .= " and x01_entrega = $x01_entrega ";
}

if ($hidrometros == "c") {
  //$where .= " and x04_nrohidro is not null ";
  $where      .= " and fc_agua_hidrometroinstalado(x01_matric) is true ";
  $wherehidro  = " and fc_agua_hidrometroativo(x04_codhidrometro) is true ";
} elseif ($hidrometros == "s") {
  //$where .= " and x04_nrohidro is null ";
  $where      .= " and fc_agua_hidrometroinstalado(x01_matric) is false ";
  $wherehidro  = " and fc_agua_hidrometroativo(x04_codhidrometro) is false ";
} else {
  $wherehidro  = " and fc_agua_hidrometroativo(x04_codhidrometro) is true ";
}

if(!empty($dtini) && !empty($dtfim)) {
  if (empty($x40_codcorte)) {
    $where .= "and fc_agua_ultimadatacorte(x01_matric) between '$dtini' and '$dtfim' ";
  } else {
    $where .= "and fc_agua_ultimadatacorte(x01_matric, $x40_codcorte) between '$dtini' and '$dtfim' ";
  }
  $head8 = "Periodo: ".db_formatar($dtini, "d")." a ".db_formatar($dtfim, "d");
}

if ($ultimohistorico == "s") {
  if (empty($x40_codcorte)) {
    $where    .= " and fc_agua_ultimasituacaocorte(x01_matric) = $x43_codsituacao ";
    $situacao  = "fc_agua_ultimasituacaocorte(x01_matric)";
  } else {
    $where    .= " and fc_agua_ultimasituacaocorte(x01_matric, $x40_codcorte) = $x43_codsituacao ";
    $situacao  = "fc_agua_ultimasituacaocorte(x01_matric, $x40_codcorte)";
  }

} else {

  if(!empty($dtini) && !empty($dtfim)) {
    $situacao = "(select x42_codsituacao
                    from aguacortematmov
                   where x42_codcortemat = x41_codcortemat 
                     and x42_codsituacao = $x43_codsituacao
                     and x42_data between '$dtini' and '$dtfim'
                   limit 1) ";

  }else{
    $situacao = "(select x42_codsituacao
                    from aguacortematmov
                   where x42_codcortemat = x41_codcortemat 
                     and x42_codsituacao = $x43_codsituacao
                   limit 1) ";
                     
  }

  $where .= " and exists $situacao ";
  
}

//$sQueryAguaCorte = "select x40_tipomatricula from aguacorte where x40_codcorte = $x40_codcorte";
//$result = db_query($sQueryAguaCorte) or die($sQueryAguaCorte);
//$x40_tipomatricula = pg_result($result,0);


$sql  = "select * from ( ";
$sql .= "  select	x01_matric, ";
$sql .= "         max(x04_codhidrometro) as x04_codhidrometro, ";
$sql .= "         (select x04_dtinst   from aguahidromatric h where x04_codhidrometro = max(aguahidromatric.x04_codhidrometro) $wherehidro) as x04_dtinst,";
$sql .= "         (select x04_nrohidro from aguahidromatric h where x04_codhidrometro = max(aguahidromatric.x04_codhidrometro) $wherehidro) as x04_nrohidro, ";
$sql .= "		      x01_codrua, ";
$sql .= "					j88_sigla, ";
$sql .= "					j14_nome, ";
$sql .= "  			  x01_numero, ";
$sql .= "         x01_orientacao, ";
$sql .= "		      x01_entrega, ";
$sql .= "					(select x11_complemento from aguaconstr where x11_matric=x01_matric limit 1) as x11_complemento, ";
$sql .= "					$situacao as x42_codsituacao";
$sql .= "    from aguacortemat ";
$sql .= "         left join aguabase        on x01_matric   = x41_matric   ";
$sql .= "         left join ruas            on j14_codigo   = x01_codrua   ";
$sql .= "         left join aguahidromatric on x04_matric   = x41_matric   ";
$sql .= "         left join aguahidromarca  on x03_codmarca = x04_codmarca ";
$sql .= "         left join ruastipo        on x01_codrua   = j88_codigo   ";
$sql .= "  	where	1=1 ";
$sql .= "    $where ";
$sql .= "  group by x01_matric, x01_codrua, j88_sigla, j14_nome, x01_numero, x01_orientacao, x01_entrega, ";
$sql .= "   (select x11_complemento from aguaconstr where x11_matric=x01_matric limit 1), ";
$sql .= "   $situacao ";
$sql .= " order by x04_dtinst desc, x04_nrohidro, x01_matric ";

$sql .= ") as x ";
$sql .= "	order by " . ($quebrarentrega == "s"?"x01_entrega, ":"") . "j14_nome, x01_numero, x11_complemento, x01_matric; ";
//die( $sql );

$result = db_query($sql) or die($sql);
$numrows = pg_numrows($result);

if ($numrows == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Nao existem itens cadastrados para fazer a consulta.');
}

$head2 = "LISTAGEM DE CORTE";
$head3 = "Situacao: " . $x43_codsituacao . " " . $x43_descr;
$head4 = "Logradouro: $x01_codrua - Somente último histórico: " . ($ultimohistorico == "s"?"SIM":"NÃO");
$head5 = "Zona Fiscal: $x01_zona - Hidrômetros: " . ($hidrometros == "t"?"TODOS":($hidrometros == "c"?"COM":"SEM"));
$head6 = "Zona de Entrega: $x01_entrega";

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);

$alt = 7;
$fator = 1.5;
$largSit = 52;

$largCod  = 05;
$largLeit = 14;
$largData = 12;
$largObs  = 83;

$ultlograd=0;
$ultentrega=0;

$totalporlograd=0;
$totalporentrega=0;
$total=0;

for($x=0; $x<$numrows; $x++) {
  db_fieldsmemory($result, $x);

  if ((($quebrarlograd == "s" or $quebrarentrega == "s") and $ultlograd != $x01_codrua) or ($quebrarentrega == "s" and $ultentrega != $x01_entrega) or ($quebrarlograd == "n" and $quebrarentrega == "n") or ($ultlograd == 0 or $ultentrega == 0)) {

    $pdf->setfont('arial','b',10);

    $impzona=false;
    if ($quebrarlograd == "s" and $ultlograd != 0 and ($ultlograd != $x01_codrua) or ($ultlograd == 0 or $ultentrega == 0)) {
      if ($quebrarentrega == "s") {
        $impzona=true;
      }
      $pdf->addpage("L");
    } elseif ($quebrarentrega == "s" and $ultentrega != 0 and ($ultentrega != $x01_entrega)) {
      if ($quebrarentrega == "s") {
        $impzona=true;
      }
      $pdf->addpage("L");
    }

    if (($quebrarentrega == "s" and $quebrarlograd == "s")) {
      $impzona=true;
    }

    if ($impzona == true) {
      $pdf->cell(192,$alt,"ZONA DE ENTREGA: $x01_entrega","T",1,"L",0);
      $totalporentrega=0;
    }

    if ($ultlograd != $x01_codrua) {

      $pdf->cell(275,$alt,$j88_sigla . " " . $x01_codrua . " - " . $j14_nome,"T",1,"L",0);
      $pdf->setfont('arial','',9);
      $pdf->cell($Mx01_numero*$fator,$alt,substr($RLx01_numero,0,3),1,0,"C",1);
      $pdf->cell($Mx11_complemento*$fator,$alt,$RLx11_complemento,1,0,"C",1);
      $pdf->cell($Mx04_nrohidro*$fator,$alt,$RLx04_nrohidro,1,0,"C",1);
      $pdf->cell($Mx42_codsituacao*$fator,$alt,substr($RLx42_codsituacao,0,3),1,0,"C",1);
      $pdf->cell($Mx01_matric*$fator,$alt,$RLx01_matric,1,0,"C",1); 
      $pdf->cell($largCod *$fator,$alt,"Cod",1,0,"C",1);
      $pdf->cell($largData*$fator,$alt,"Data",1,0,"C",1);
      $pdf->cell($largLeit*$fator,$alt,"Leitura",1,0,"C",1);
      $pdf->cell($largObs* $fator,$alt,"Observacoes",1,1,"C",1);

      $ultlograd      = $x01_codrua;
      $totalporlograd = 0;
      $ultentrega     = $x01_entrega;

    }
  }

  $pdf->setfont('arial','',9);

  if ($opcaohistorico == "s" or $opcaoleitura == "s") { 
    
		$sSqlHistLeitu  = "select                                                             ";
		$sSqlHistLeitu .= ($opcaohistorico == "s"?"SUBSTRING(x42_historico, 1, 70) as x42_historico":"");
		$sSqlHistLeitu .= ($opcaoleitura == "s"?($opcaohistorico == "s"?",":"")."x42_leitura":"");
		$sSqlHistLeitu .= "  from aguacortemat                                                ";
		$sSqlHistLeitu .= "	inner join aguacortematmov on x42_codcortemat = x41_codcortemat   ";
		$sSqlHistLeitu .= "	where x41_codcorte =                                              ";
    if (!empty($x40_codcorte)) {
      $sSqlHistLeitu .= "  $x40_codcorte                                                  ";
    } else {
		  $sSqlHistLeitu .= "                   ( select MAX(x41_codcorte)                    ";
	    $sSqlHistLeitu .= "	                      from aguacortemat                         ";
		  $sSqlHistLeitu .= "	                     where x41_matric = $x01_matric )           ";
    }
		$sSqlHistLeitu .= "	  and x42_codmov   = ( select MAX(x42_codmov)                     ";
		$sSqlHistLeitu .= "	                         from aguacortematmov                     ";
		$sSqlHistLeitu .= "	                        where                                     "; 
		$sSqlHistLeitu .= "                               x42_codsituacao = $x42_codsituacao  ";
		$sSqlHistLeitu .= "	                          and x42_codcortemat = x41_codcortemat ) ";
		$sSqlHistLeitu .= "	  and x41_matric      = $x01_matric                               ";
		$sSqlHistLeitu .= "	  and x42_codsituacao = $x42_codsituacao                          ";
		$sSqlHistLeitu .= " order by x42_data DESC                                            ";
    $sSqlHistLeitu .= " limit 1                                                           ";    
    
    //die($sSqlHistLeitu);
    $rHistLeitu = db_query($sSqlHistLeitu) or die($sSqlHistLeitu);
    
    db_fieldsmemory($rHistLeitu, 0);

  }
  
  $fundo = 0;
  $pdf->cell($Mx01_numero*$fator,      $alt, $x01_numero . trim(substr($x01_orientacao,0,1)),1,0,"C",$fundo);
  $pdf->cell($Mx11_complemento*$fator, $alt, $x11_complemento,1,0,"C",$fundo);
  $pdf->cell($Mx04_nrohidro*$fator,    $alt, $x04_nrohidro,1,0,"L",$fundo);
  $pdf->cell($Mx42_codsituacao*$fator, $alt, $x42_codsituacao,1,0,"C",$fundo);
  $pdf->cell($Mx01_matric*$fator,      $alt, $x01_matric,1,0,"R",$fundo);

  $pdf->cell($largCod *$fator,$alt,"",1,0,"C",$fundo);
  $pdf->cell($largData*$fator,$alt,"",1,0,"C",$fundo);
  $pdf->cell($largLeit*$fator,$alt,@$x42_leitura <> 0?$x42_leitura:"",1,0,"C",$fundo);
  $pdf->cell($largObs* $fator,$alt,@$x42_historico,1,1,"C",$fundo);

  
  $totalporlograd++;
  $totalporentrega++;
  $total++;

  $imptotallograd=false;
  $imptotalentrega=false;

  if ( $x == $numrows-1 ) {
    $imptotallograd=true;
    if ($quebrarentrega == "s") {
      $imptotalentrega=true;
    }
  } else {

    if ($ultlograd != pg_result($result,$x+1,"x01_codrua")) {
      $imptotallograd=true;
    }

    if ($quebrarentrega == "s") {
      if ($quebrarlograd == "s") {

        if ($ultentrega != pg_result($result,$x+1,"x01_entrega") or $imptotallograd == true) {
          $imptotalentrega=true;
        } 

      } else {
        if ($ultentrega != pg_result($result,$x+1,"x01_entrega")) {
          $imptotalentrega=true;
        }
      }
    }

  }

  if ($imptotallograd == true or $imptotalentrega == true) {
    $pdf->cell(275,$alt,'REGISTROS DESTE LOGRADOURO: ' . $totalporlograd,"T",1,"L",0);
    $ultlograd = 999999999;
  }

  if ($imptotalentrega == true) {
    $pdf->cell(275,$alt,'REGISTROS DESTA ZONA DE ENTREGA: ' . $totalporentrega,"T",1,"L",0);
  }

}

$pdf->ln(3);
$pdf->setfont('arial','b',8);
$pdf->cell(275,$alt,'TOTAL DE REGISTROS DA LISTAGEM:  '.$total,"T",0,"L",0);

$pdf->Output();

?>