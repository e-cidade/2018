<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

//include(modification("fpdf151/scpdf.php"));
include(modification("fpdf151/pdf.php"));
include(modification("classes/db_contlot_classe.php"));
include(modification("classes/db_contrib_classe.php"));
include(modification("classes/db_contlotv_classe.php"));
include(modification("classes/db_contricalc_classe.php"));
include(modification("classes/db_editalserv_classe.php"));
include(modification("classes/db_editalrua_classe.php"));
include(modification("classes/db_editalruaproj_classe.php"));

include(modification("libs/db_utils.php"));

$clcontlot				= new cl_contlot;
$clcontrib				= new cl_contrib;
$clcontricalc			= new cl_contricalc;
$clcontlotv				= new cl_contlotv;
$cleditalserv		  = new cl_editalserv;
$cleditalrua			= new cl_editalrua;
$cleditalruaproj  = new cl_editalruaproj;

db_postmemory($HTTP_GET_VARS);
$objGet  = db_utils::postmemory($_GET);
$iQuebra = 0;
//die($cleditalrua->sql_query("","d02_codigo,d01_numero,d02_contri,j14_nome,d01_data,(100 - d01_perc) as d01_perc ,d02_valorizacao","d02_contri,j14_nome","d02_codedi = {$objGet->edital}"));
$rsEditais = $cleditalrua->sql_record($cleditalrua->sql_query("","d02_codigo,d01_numero,d02_contri,j14_nome,d01_data,(100 - d01_perc) as d01_perc ,d02_valorizacao","d02_contri,j14_nome","d02_codedi = {$objGet->edital}"));
$iNumrows  = $cleditalrua->numrows;
$objEdital = db_utils::fieldsMemory($rsEditais,0);

$iAno = db_getsession('DB_anousu');

if( $iNumrows == 0 ){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não foram encontradas contribuições para este edital.');
}

$cont         = 0;
$contot       = 0;
$valorcont    = 0;
$totvalorcont = 0;

$contriz = '';
$virgz = '';

$lin    = 0;
$pri    = false;
$pripag = "true";
//$pdf    = new SCPDF();
$pdf    = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','',11);

$head1 = " Edital : {$objEdital->d01_numero} ";

$sSqlConfig  = " select nomeinst,bairro,cgc,ender,upper(munic) as munic,uf,telef,email,url,logo,";
$sSqlConfig .= "        db12_extenso ";
$sSqlConfig .= "   from db_config ";
$sSqlConfig .= "        inner join db_uf on db12_uf = uf ";
$sSqlConfig .= "  where codigo = ".db_getsession("DB_instit");

$rsConfig    = db_query($sSqlConfig);

$objConfig   = db_utils::fieldsMemory($rsConfig,0);

(float)$nValorVenal              = 0;
(float)$nAreaRealTotal           = 0;
(float)$nAreaTotal               = 0;
(float)$nValorM2                 = 0;
(float)$nValorizacao             = 0;
(float)$nAreaParcial             = 0;
(float)$nAreaCorrigida           = 0;
(float)$nValorFinal              = 0;
(float)$nCusto                   = 0;
(float)$nCustoTotal              = 0;
(float)$nTotalTestada            = 0;
(float)$nTotalAreaParcial        = 0;
(float)$nTotalAreaCorrigida      = 0;
(float)$iTotalRegistros          = 0;
(float)$nTotalValorVenal         = 0;
(float)$nTotalValorFinal         = 0;
(float)$nTotalCusto              = 0;
(float)$nTotalCustoInte          = 0;
(float)$iTotalGeralRegistros     = 0;
(float)$nTotalGeralAreaCorrigida = 0;
(float)$nTotalGeralValorVenal    = 0;
(float)$nTotalGeralValorFinal    = 0;
(float)$nTotalGeralCusto         = 0;
(float)$nTotalGeralCustoInte     = 0;
(float)$nTotalGeralAreaParcial   = 0;
(float)$nTotalGeralTestada       = 0;
(int)$iSequencial                = 0;

$pdf->AddPage("L");

//
// For percorrendo as listas do edital
//
for( $i = 0; $i < $iNumrows; $i++ ) {

  $objEdital = db_utils::fieldsMemory($rsEditais,$i);
//  db_criatabela($rsEditais);exit;

//  die($clcontrib->sql_query_file($objEdital->d02_contri,"","d07_valor,d07_venal"));
  $clcontrib->sql_record($clcontrib->sql_query_file($objEdital->d02_contri,"","d07_valor,d07_venal"));

  if($clcontrib->numrows>0){

    $cabec  = "";
    $pri01  = "false";
    $propag = "true";

    $sSqlMatricula  = " select distinct  ";
    $sSqlMatricula .= "        j01_matric, ";
    $sSqlMatricula .= "        j40_refant, ";
    $sSqlMatricula .= "        z01_nome, ";
    $sSqlMatricula .= "        lote.j34_idbql, ";
    $sSqlMatricula .= "        lote.j34_area, ";
    $sSqlMatricula .= "        j34_setor, ";
    $sSqlMatricula .= "        j34_quadra, ";
    $sSqlMatricula .= "        j34_lote, ";
    $sSqlMatricula .= "        j34_zona, ";
    $sSqlMatricula .= "        case      ";
    $sSqlMatricula .= "          when j39_numero is not null ";
    $sSqlMatricula .= "            then j39_numero||(case when j39_compl is not null and j39_compl != '' then '/'||j39_compl else '' end)";
    $sSqlMatricula .= "          when j15_numero is not null ";
    $sSqlMatricula .= "            then j15_numero||(case when j15_compl is not null and j15_compl != '' then '/'||j15_compl else '' end)";
    $sSqlMatricula .= "        end as numero_complemento,";
    $sSqlMatricula .= "        d41_testada + d41_eixo as d05_testad ";
    $sSqlMatricula .= "   from contlot ";
    $sSqlMatricula .= "        inner join lote                on j34_idbql               = d05_idbql ";
    $sSqlMatricula .= "        left  join testada             on testada.j36_idbql       = lote.j34_idbql ";
    $sSqlMatricula .= "                                      and testada.j36_codigo      = {$objEdital->d02_codigo} ";
    $sSqlMatricula .= "        left  join face                on face.j37_face           = testada.j36_face";
    $sSqlMatricula .= "                                      and face.j37_codigo         = {$objEdital->d02_codigo} ";
    $sSqlMatricula .= "        left  join testadanumero       on testadanumero.j15_idbql = lote.j34_idbql ";
    $sSqlMatricula .= "                                      and testadanumero.j15_face  = face.j37_face ";
    $sSqlMatricula .= "        inner join iptubase            on j34_idbql               = j01_idbql ";
    $sSqlMatricula .= "        left  join iptuconstr          on j01_matric              = j39_matric ";
    $sSqlMatricula .= "                                      and j39_idprinc is true ";
    $sSqlMatricula .= "                                      and j39_codigo              = {$objEdital->d02_codigo} ";
    $sSqlMatricula .= "        left  join iptuant             on j40_matric              = j01_matric ";
    $sSqlMatricula .= "        inner join cgm                 on j01_numcgm              = z01_numcgm ";
    $sSqlMatricula .= "        inner join editalruaproj       on d11_contri              = d05_contri ";
    $sSqlMatricula .= "        inner join projmelhoriasmatric on d41_codigo              = d11_codproj ";
    $sSqlMatricula .= "                                      and d41_matric              = j01_matric  ";
    $sSqlMatricula .= "  where d05_contri = {$objEdital->d02_contri} ";
    $sSqlMatricula .= "  order by j40_refant ";

    $rsMatriculas = db_query($sSqlMatricula) or die($sSqlMatricula);

    $sqlSomaTestada  = " select sum(d41_testada + d41_eixo) as total_testada  ";
    $sqlSomaTestada .= "   from contlot  ";
    $sqlSomaTestada .= "        inner join lote                on j34_idbql = d05_idbql ";
    $sqlSomaTestada .= "        inner join iptubase            on j34_idbql = j01_idbql ";
    $sqlSomaTestada .= "        inner join editalruaproj       on d11_contri = d05_contri ";
    $sqlSomaTestada .= "        inner join projmelhoriasmatric on d41_codigo = d11_codproj ";
    $sqlSomaTestada .= "                                      and d41_matric = j01_matric  ";
    $sqlSomaTestada .= "  where d05_contri = {$objEdital->d02_contri} ";

    $rsSomaTestada   = db_query($sqlSomaTestada) or die($sqlSomaTestada);

    if (pg_numrows($rsSomaTestada) == 0) {
      $total_testada = 0;
    } else {
      $objSomaTestada  = db_utils::fieldsMemory($rsSomaTestada,0);
      $total_testada   = $objSomaTestada->total_testada;
    }

    $iNumrowsMatricula = pg_numrows($rsMatriculas);

    $linha = 60;


    if($pri01=="false"){// testa quando e uma nova contribucao


      $pri01 = "true";
      $y     = $pdf->GetY();

      $rsEditalServ  = $cleditalserv->sql_record($cleditalserv->sql_query($objEdital->d02_contri,"","(100 - d01_perc) as d01_perc ,d01_descr,d02_profun,d04_quant,d04_vlrcal,d04_vlrval,d04_mult,d04_vlrobra "));
      $objEditalServ = db_utils::fieldsMemory($rsEditalServ,0);

      $rsEditalRuaProj  = $cleditalruaproj->sql_record($cleditalruaproj->sql_query($objEdital->d02_contri,"","d40_trecho"));
			$oEditalRuaProj   = db_utils::fieldsMemory($rsEditalRuaProj,0);


			(float)$nLarguraRua = ($objEditalServ->d02_profun * 2);

      $pripag="false";

      if ( $i > 0 ) {
        $pdf->Ln(6);
      }

      /// seta a margem esquerda que veio do relatorio
      $S = $pdf->lMargin;
      $Letra  = 'Times';


      // Altera o tamanho da celula rua e trecho
			$iTamanhoTrecho = strlen($oEditalRuaProj->d40_trecho);

			if($iTamanhoTrecho < 50){
				$iTamanhoTrecho = 50;
			}
			$iColunaInf = (190 - (round(1.6 * $iTamanhoTrecho)));
			$iColTrech  = round(1.6 * $iTamanhoTrecho);

			$pdf->SetFillColor(220);

      if ($objGet->tipocusto == 2 ) {
        $objEditalServ->d01_perc = 100;
      }
      if ( $tipocusto != '3' ) {
        $iQuebra = 1;
      }

			$pdf->SetFont($Letra,'B',7);
      $pdf->SetX($iColunaInf);
      $pdf->Cell(20,4,"Edital Nº"      ,1,0,"C",1);
      $pdf->Cell($iColTrech,4,"Rua"    ,1,0,"C",1);
      $pdf->Cell(20,4,"Extensão"       ,1,0,"C",1);
      $pdf->Cell(20,4,"Área Total"     ,1,0,"C",1);
      $pdf->Cell(20,4,"Fator Valoriz"  ,1,0,"C",1);
      $pdf->Cell(20,4,"Valor M2"       ,1,1,"C",1);

      $pdf->SetX($iColunaInf);
      $pdf->Cell(20,4,$objEdital->d01_numero      ,1,0,"C",0);
      $pdf->Cell($iColTrech,4,$objEdital->j14_nome        ,1,0,"C",0);
      $pdf->Cell(20,4,db_formatar( ( $objEditalServ->d04_quant ),'f') ,1,0,"C",0);
      $pdf->Cell(20,4,db_formatar( ( $objEditalServ->d04_quant * $nLarguraRua ),'f') ,1,0,"C",0);
      $pdf->Cell(20,4,db_formatar( $objEdital->d02_valorizacao,'f')                                  ,1,0,"C",0);
      $pdf->Cell(20,4,db_formatar(( $objEditalServ->d04_vlrobra / ( $objEditalServ->d04_quant * $nLarguraRua ) ),'f') ,1,1,"C",0);

      $pdf->SetX($iColunaInf);
      $pdf->Cell(20,4,"Número da Obra" ,1,0,"C",1);
      $pdf->Cell($iColTrech,4,"Trecho da Obra" ,1,0,"C",1);
      $pdf->Cell(20,4,"Largura da rua" ,1,0,"C",1);
      $pdf->Cell(20,4,"Valor da Obra"  ,1,0,"C",1);
      $pdf->Cell(20,4,"Fator Resgate"  ,1,0,"C",1);
      $pdf->Cell(20,4,"Resgate"        ,1,1,"C",1);

      $pdf->SetX($iColunaInf);
      $pdf->Cell(20,4,"1"																						 ,1,0,"C",0);
      //$pdf->Cell(60,4,$objEditalServ->d01_descr   ,1,0,"C",0);
      $pdf->Cell($iColTrech,4,$oEditalRuaProj->d40_trecho					   ,1,0,"C",0);
      $pdf->Cell(20,4,db_formatar( $nLarguraRua ,'f')								 ,1,0,"C",0);
      $pdf->Cell(20,4,db_formatar( $objEditalServ->d04_vlrobra,'f')  ,1,0,"C",0);
      $pdf->Cell(20,4,db_formatar( $objEditalServ->d01_perc ,'f')    ,1,0,"C",0);
      $pdf->Cell(20,4,db_formatar( ( ( $objEditalServ->d04_vlrobra / 100) * $objEditalServ->d01_perc ),'f') ,1,1,"C",0);


      $pdf->Ln(10);
      $pdf->SetFont($Letra,'B',12);
      $pdf->MultiCell(0,4,"Planilha Demonstrativa para Contribuição de Melhoria {$objEdital->d02_contri} - {$head1} ",0,"C",0);
      $pdf->Ln(10);

      $propag="false";


      $cabec="1";
      $rsContribuicao= $cleditalrua->sql_record($cleditalrua->sql_query($objEdital->d02_contri,"d02_codedi,j14_nome,d02_profun,d02_valorizacao"));
      $objContribuicao = db_utils::fieldsMemory($rsContribuicao,0);

      //
      // Mostra o cabecalho
      //
      cabecalhoRelatorio( $pdf,$tipocusto );

    }

    $pri02="false";

    for($b = 0; $b < $iNumrowsMatricula; $b++){

      $y02=$pdf->getY();

      if($y02 > 180){

        $pdf->AddPage("L");
        $pri02="false";
        $cabec="";
        $propag="true";

      }

      if($pri02=="false" && $propag=="true" && $cabec!="1"){

        $pri02 = "true";
        $rsContribuicao  = $cleditalrua->sql_record($cleditalrua->sql_query($objEdital->d02_contri,"d02_codedi,j14_nome,d02_profun,d02_valorizacao"));
        $objContribuicao = db_utils::fieldsMemory($rsContribuicao,0);

        //
        // Funcao para criar o cabecalho
        //
        cabecalhoRelatorio($pdf,$tipocusto );

      }

      $cont++;

      $objMatriculas = db_utils::fieldsMemory($rsMatriculas ,$b);

      $rsEditalServ = $cleditalserv->sql_record($cleditalserv->sql_query($objEdital->d02_contri,"","d04_quant,d04_vlrcal,d04_vlrval,d04_mult,d04_vlrobra "));
      $iNumrowsServ = $cleditalserv->numrows;

      $valmetro    = "";
      $valmetroval = "";

      for ( $u = 0; $u < $iNumrowsServ; $u++ ) {

        $objEditalServ = db_utils::fieldsMemory($rsEditalServ,$u);

        $sql  = " select sum( case  ";
        $sql .= "               when j22_valor is null  ";
        $sql .= "                 then 0  ";
        $sql .= "               else j22_valor  ";
        $sql .= "             end + j23_vlrter ) as j23_vlrter ";
        $sql .= "   from ( select ( select sum(j22_valor) ";
        $sql .= "                     from iptucale  ";
        $sql .= "                    where j22_anousu = {$iAno}";
        $sql .= "                      and j22_matric = {$objMatriculas->j01_matric} ) as j22_valor,  ";
        $sql .= "                 ( select j23_vlrter  ";
        $sql .= "                     from iptucalc  ";
        $sql .= "                    where j23_anousu = {$iAno} ";
        $sql .= "                      and j23_matric = {$objMatriculas->j01_matric} ) as j23_vlrter ) as j23_vlrter ";

        $rsValorVenal = db_query($sql) or die($sql);

        $objValorVenal = db_utils::fieldsMemory($rsValorVenal,0);

        (float)$nValorVenal     = $objValorVenal->j23_vlrter;

        // Área Real Total
        (float)$nAreaRealTotal = ( $objEditalServ->d04_quant * $nLarguraRua );

        // area total
        (float)$nAreaTotal     = ( $objSomaTestada->total_testada *  $objContribuicao->d02_profun );

        // valor do m2

        //(float)$nValorM2       = round( ( $objEditalServ->d04_vlrobra / $nAreaRealTotal ) ,2);
        (float)$nValorM2       = ( $objEditalServ->d04_vlrobra / $nAreaRealTotal );

        // valor valorizacao
        (float)$nValorizacao   = ( $nValorVenal * $objContribuicao->d02_valorizacao / 100 );

        // area parcial
        (float)$nAreaParcial   = ( $objMatriculas->d05_testad * $objContribuicao->d02_profun );

        // area corrigida
        (float)$nAreaCorrigida = ( $nAreaParcial / $nAreaTotal * $nAreaRealTotal );

        // valor venal
        (float)$nValorFinal    = ( $nValorVenal + $nValorizacao );

        // Custo
        (float)$nCusto         = ( $nAreaCorrigida * $nValorM2 )  * ( $objEdital->d01_perc / 100 ) ;

        //Custo Integral
        (float)$nCustoIntegral = ( $nAreaCorrigida * $nValorM2 );

        //
        // Se Custo maior que a valorizacao entao custo fica a valorizacao
        //
        if ( $nCusto > $nValorizacao ) {

          (float)$nCusto = $nValorizacao;

        }

        (float)$nCustoTotal += $nCusto;

      }

      $pdf->SetFont('Times','',6);

      //
      // Dados da matricula
      //
      $sImovel = "";
      if ($objMatriculas->j34_setor != '' && $objMatriculas->j34_quadra != '' && $objMatriculas->j34_lote != '') {
        $sImovel =  "{$objMatriculas->j34_setor} - {$objMatriculas->j34_quadra} - {$objMatriculas->j34_lote} ";
      }

      if( $b % 2 == 0 ) {
        $corfundo = 245;
      }else{
        $corfundo = 255;
      }

      $pdf->SetFillColor($corfundo);

      $sNumeroComplemento = ($objMatriculas->numero_complemento != ""?",{$objMatriculas->numero_complemento}":"");

      $pdf->Cell(10,4,(++$iSequencial)                                            ,1,0,"C",1);
      $pdf->Cell(10,4,$objMatriculas->j01_matric                                  ,1,0,"C",1);
      $pdf->Cell(20,4,$sImovel                                                    ,1,0,"C",1);
      $pdf->Cell(60,4,$objMatriculas->z01_nome                                    ,1,0,"L",1);
      $pdf->Cell(45,4,trim($objContribuicao->j14_nome).trim($sNumeroComplemento)  ,1,0,"L",1);
      $pdf->Cell(15,4,db_formatar($objMatriculas->d05_testad,'f')                 ,1,0,"R",1);
      $pdf->Cell(20,4,db_formatar($nAreaParcial,'f')                              ,1,0,"R",1);
      $pdf->Cell(20,4,db_formatar($nAreaCorrigida,'f')                            ,1,0,"R",1);
      $pdf->Cell(20,4,db_formatar($nValorVenal,'f')                               ,1,0,"R",1);
      $pdf->Cell(20,4,db_formatar($nValorFinal,'f')                               ,1,0,"R",1);

      if ( $tipocusto == '1' ) {
        $pdf->Cell(20,4,db_formatar($nCusto,'f')           ,1,1,"R",1); // 280
      }else if ( $tipocusto == '2' ) {
        $pdf->Cell(20,4,db_formatar( $nCustoIntegral ,'f') ,1,1,"R",1);
      }else{
        $pdf->Cell(20,4,db_formatar($nCusto,'f')           ,1,0,"R",1); // 280
        $pdf->Cell(20,4,db_formatar( $nCustoIntegral ,'f') ,1,1,"R",1);
      }

      $iTotalRegistros++;
      $nTotalValorVenal    += round($nValorVenal,2);
      $nTotalValorFinal    += round($nValorFinal,2);
      $nTotalCusto         += round($nCusto,2);
      $nTotalCustoInte     += round($nCustoIntegral,2);
      $nTotalTestada       += round($objMatriculas->d05_testad,2);
      $nTotalAreaParcial   += round($nAreaParcial,2);
      $nTotalAreaCorrigida += round($nAreaCorrigida,2);

      $iTotalGeralRegistros++;
      $nTotalGeralValorVenal    += round($nValorVenal,2);
      $nTotalGeralValorFinal    += round($nValorFinal,2);
      $nTotalGeralCusto         += round($nCusto,2);
      $nTotalGeralCustoInte     += round($nCustoIntegral,2);
      $nTotalGeralTestada       += round($objMatriculas->d05_testad,2);
			$nTotalGeralAreaParcial   += round($nAreaParcial,2);

			$nTotalGeralAreaCorrigida += $nAreaCorrigida;

      if ( $iNumrowsMatricula == ($b+1) && $iNumrows > 1 ) {

        $pdf->SetFont('Arial','B',7);

        $pdf->Ln(1);
        $pdf->Cell(110,4,"SubTotal de Registros : {$iTotalRegistros} " ,1,0,"L",1);
        $pdf->Cell(35, 4,"SubTotais : "                            ,1,0,"R",1);
        $pdf->Cell(15, 4,db_formatar($nTotalTestada,'f')           ,1,0,"R",1);
        $pdf->Cell(20, 4,db_formatar($nTotalAreaParcial,'f')       ,1,0,"R",1);
        $pdf->Cell(20, 4,db_formatar($nTotalAreaCorrigida,'f')     ,1,0,"R",1);
        $pdf->Cell(20, 4,db_formatar($nTotalValorVenal,'f')        ,1,0,"R",1);
        $pdf->Cell(20, 4,db_formatar($nTotalValorFinal,'f')        ,1,0,"R",1);

				if ( $tipocusto == '1' ) {
					$pdf->Cell(20,4,db_formatar($nTotalCusto,'f')						 ,1,1,"R",1); // 280
				}else if ( $tipocusto == '2' ) {
					$pdf->Cell(20,4,db_formatar( $nTotalCustoInte ,'f')			 ,1,1,"R",1);
				}else{
					$pdf->Cell(20,4,db_formatar( $nTotalCusto,'f')					 ,1,0,"R",1); // 280
					$pdf->Cell(20,4,db_formatar( $nTotalCustoInte ,'f')			 ,1,1,"R",1);
				}

        $iTotalRegistros      = 0;
        $nTotalTestada        = 0;
        $nTotalAreaParcial    = 0;
        $nTotalAreaCorrigida  = 0;
        $nTotalValorVenal     = 0;
        $nTotalValorFinal     = 0;
        $nTotalCusto          = 0;
        $nTotalCustoInte      = 0;

      }

    }

  }else{

    $contriz .= $virgz.$d02_contri;
    $virgz = ', ';
    continue;

  }

}

$pdf->Ln(1);
$pdf->SetFont('Arial','B',7);

$pdf->Cell(90,4,"Total de Registros : {$iTotalGeralRegistros} " ,1,0,"L",1);
$pdf->Cell(55, 4,"Totalizadores : "                             ,1,0,"R",1);
$pdf->Cell(15, 4,db_formatar($nTotalGeralTestada,'f')           ,1,0,"R",1);
$pdf->Cell(20, 4,db_formatar($nTotalGeralAreaParcial,'f')       ,1,0,"R",1);
$pdf->Cell(20, 4,db_formatar($nTotalGeralAreaCorrigida,'f')     ,1,0,"R",1);
$pdf->Cell(20, 4,db_formatar($nTotalGeralValorVenal,'f')        ,1,0,"R",1);
$pdf->Cell(20, 4,db_formatar($nTotalGeralValorFinal,'f')        ,1,0,"R",1);

if ( $tipocusto == '1' ) {
  $pdf->Cell(20, 4,db_formatar($nTotalGeralCusto,'f')           ,1,1,"R",1); // 280
}else if ( $tipocusto == '2' ) {
  $pdf->Cell(20, 4,db_formatar($nTotalGeralCustoInte,'f')       ,1,1,"R",1); // 280
}else{
  $pdf->Cell(20, 4,db_formatar($nTotalGeralCusto,'f')           ,1,0,"R",1); // 280
  $pdf->Cell(20, 4,db_formatar($nTotalGeralCustoInte,'f')       ,1,1,"R",1); // 280
}

$pdf->Output();

//
// Funcao para montar o cabecalho do relatorio
//
function cabecalhoRelatorio( &$pdf, $iTipo=1 ) {

  $iQuebra = 0;
  $pdf->SetFont('Arial','B',7);
  $pdf->SetFillColor(220);

  $pdf->Ln(1);

  $pdf->Cell(10,4,"Seq"                   ,1,0,"C",1);
  $pdf->Cell(10,4,"Mat"                   ,1,0,"C",1);
  $pdf->Cell(20,4,"Imóvel"                ,1,0,"C",1);
  $pdf->Cell(60,4,"Proprietário"          ,1,0,"C",1);
  $pdf->Cell(45,4,"Localização do Imóvel" ,1,0,"C",1);
  $pdf->Cell(15,4,"Testada"               ,1,0,"C",1);
  $pdf->Cell(20,4,"Área"                  ,1,0,"C",1);
  $pdf->Cell(20,4,"Área Corrigida"        ,1,0,"C",1);
  $pdf->Cell(20,4,"Valor Venal"           ,1,0,"C",1);
  $pdf->Cell(20,4,"Valor Final"           ,1,0,"C",1);

  if ( $iTipo == '1' ) {
    $pdf->Cell(20,4,"C. Melhoria"     ,1,1,"C",1);
  }else if ( $iTipo == '2' ) {
    $pdf->Cell(20,4,"Custo Obra" ,1,1,"C",1);
  }else{
    $pdf->Cell(20,4,"C. Melhoria"     ,1,0,"C",1);
    $pdf->Cell(20,4,"Custo Obra" ,1,1,"C",1);
  }

}

?>