<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

include("libs/db_utils.php");
include("fpdf151/pdf.php");

$clrotulo = new rotulocampo;
$clrotulo->label('x01_matric');
$clrotulo->label('x04_nrohidro');
$clrotulo->label('x04_codhidrometro');
$clrotulo->label('x04_dtinst');
$clrotulo->label('x01_codrua');
$clrotulo->label('j88_sigla');
$clrotulo->label('j14_nome');
$clrotulo->label('x01_numero');
$clrotulo->label('x01_orientacao');
$clrotulo->label('x01_entrega');
$clrotulo->label('x21_leitura');
$clrotulo->label('x42_leitura');
$clrotulo->label('x43_descr');


$oGet = db_utils::postMemory($_GET);

$iIdListaCorte = $oGet->corte;
$sOrdenar      = $oGet->ordenar;

if($sOrdenar == 'matricula') {
  $orderby = 'x01_matric';
  $sOrdenadopor = 'Matrícula';
} else if($sOrdenar == 'logradouro') {
  $orderby = 'j14_nome';
  $sOrdenadopor = 'Logradouro';
} else if($sOrdenar == 'leituracorte') {
  $orderby = 'x42_leitura';
  $sOrdenadopor = 'Leitura do Corte';
} else if($sOrdenar == 'leituraleiturista') {
  $orderby = 'x21_leitura';
} else if($sOrdenar == 'data') {
  $orderby = 'x04_dtinst';
  $sOrdenadopor = 'Data de Instalação';
}


$sSql = "select x01_matric, x04_codhidrometro, x04_dtinst, x04_nrohidro, x01_codrua, j88_sigla, j14_nome, x01_numero, x01_orientacao, x01_entrega, x21_leitura, x42_leitura, x43_descr
				   from aguacortemat
				  inner join aguabase        on x01_matric        = x41_matric
				  inner join vw_leitura      on x04_matric        = x41_matric
				  inner join aguacortematmov on x42_codcortemat   = x41_codcortemat
				  inner join aguacortesituacao on x43_codsituacao = fc_agua_ultimasituacaocorte(x01_matric, {$iIdListaCorte})
				   left join ruas            on j14_codigo        = x01_codrua 
				   left join ruastipo        on x01_codrua        = j88_codigo 
				 
				  where x41_codcorte   = {$iIdListaCorte}
				    and x21_codleitura = (select x21_codleitura from vw_leitura where x04_matric = x41_matric order by x21_exerc DESC, x21_mes DESC limit 1) 
				    and x42_codmov     = (select x42_codmov from aguacortematmov  where x42_codcortemat = x41_codcortemat  and x42_leitura < vw_leitura.x21_leitura and x42_leitura <> 0 limit 1)
				  group by x01_matric, x04_codhidrometro, x04_dtinst, x04_nrohidro, x01_codrua, j88_sigla, j14_nome, x01_numero, x01_orientacao, x01_entrega, x21_leitura, x42_leitura, x43_descr
				  order by {$orderby}";

$rSql = pg_query($sSql);

if(pg_num_rows($rSql) == 0) {
  
  db_redireciona('db_erros.php?fechar=true&db_erro=Nao existem matriculas.');
  
}

$head2 = 'Lista de Corte:' . $iIdListaCorte;
$head4 = 'Ordenado por:  ' . $sOrdenadopor;

$oPDF = new PDF();
$oPDF->Open();
$oPDF->AliasNbPages();
$oPDF->setfillcolor(235);
$oPDF->setfont('arial', 'b', 7);
$total   = 0;
$troca   = 1;
$alt     = 4;
$totalog = 0;
$p       = 0;

for($i = 0; $i < pg_num_rows($rSql); $i++){
  
  $oSql = db_utils::fieldsMemory($rSql, $i, true);
   
  if ($oPDF->gety() > $oPDF->h - 30 || $troca != 0 ){
    $oPDF->addpage("L");
    $oPDF->setfont('arial','b',8);
    $oPDF->cell(30, $alt, $RLx01_matric       , 1, 0, "C", 1);
    $oPDF->cell(30, $alt, $RLx04_dtinst       , 1, 0, "C", 1);
    $oPDF->cell(30, $alt, $RLx04_nrohidro     , 1, 0, "C", 1);
    $oPDF->cell(80, $alt, $RLj14_nome         , 1, 0, "C", 1);
    $oPDF->cell(20, $alt, $RLx01_entrega      , 1, 0, "C", 1);
    $oPDF->cell(30, $alt, 'Leitura'           , 1, 0, "C", 1);
    $oPDF->cell(30, $alt, 'Leitura Corte'     , 1, 0, "C", 1);
    $oPDF->cell(30, $alt, 'Situação'          , 1, 1, "C", 1); 
    $troca = 0;
    $p     = 0;
  }
   
  $oPDF->setfont('arial','',7);   
  
  $oPDF->cell(30, $alt, $oSql->x01_matric       , 0, 0, "C", $p);
  $oPDF->cell(30, $alt, $oSql->x04_dtinst       , 0, 0, "C", $p);
  $oPDF->cell(30, $alt, $oSql->x04_nrohidro     , 0, 0, "C", $p);
  $oPDF->cell(80, $alt, $oSql->j14_nome   . ', ' . 
                        $oSql->x01_numero . ' - ' . 
                        $oSql->x01_orientacao   , 0, 0, "L", $p);
  $oPDF->cell(20, $alt, $oSql->x01_entrega      , 0, 0, "C", $p);
  $oPDF->cell(30, $alt, $oSql->x21_leitura      , 0, 0, "C", $p);
  $oPDF->cell(30, $alt, $oSql->x42_leitura      , 0, 0, "C", $p);
  $oPDF->cell(30, $alt, $oSql->x43_descr        , 0, 1, "L", $p);
  
  $p = $p == 0 ? 1 : 0; 
      
  $total++;
}

$oPDF->setfont('arial','b',8);
$oPDF->cell(280, $alt, 'TOTAL DE REGISTROS : ' . $total, "T", 0, "L", 0);
$oPDF->Output();

?>