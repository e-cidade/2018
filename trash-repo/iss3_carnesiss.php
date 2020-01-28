<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

///////////////////////////////////
//// programa desabilitado ///////
/////////////////////////////////

require_once("fpdf151/scpdf.php");
require_once("fpdf151/impcarne.php");
require_once("libs/db_sql.php");
require_once("model/recibo.model.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_SERVER_VARS);

$tipo_debito = 3;

$result = pg_exec("select * from arretipo where k00_tipo = $tipo_debito");
db_fieldsmemory($result,0);

$result = pg_exec("select * from db_config where codigo = " . db_getsession("DB_instit"));
db_fieldsmemory($result,0);

$pdf = new scpdf();
$pdf->Open();

$pdf1 = new db_impcarne($pdf);

$pdf1->prefeitura = $nomeinst;
$pdf1->secretaria = 'SECRETARIA DA FAZENDA';
$pdf1->tipodebito = $k00_descr;
$pdf1->logo = $logo;


$pdf->SetMargins(5,2);


pg_exec("BEGIN");
$sqliss = "
 select isscalc.* 
 from isscalc 
 where q01_cadcal = 3 and q01_anousu = " . db_getsession("DB_anousu") . " order by q01_inscr";

$resultiss = pg_exec($sqliss);
@$totalregistros  =  pg_numrows($resultiss);
for($ii = 800;$ii < pg_numrows($resultiss);$ii++){ 
db_fieldsmemory($resultiss,$ii);
$sqliss1 = " select *
             from arrecad 
             where k00_numpre = $q01_numpre 
           ";
$resultiss1 = pg_exec($sqliss1);
@$totalcarnes = pg_numrows($resultiss1);
for($volta = 0;$volta < pg_numrows($resultiss1);$volta++) {
//for($volta = 0;$volta < 5;$volta++) {
  db_fieldsmemory($resultiss1,$volta);

  $HTTP_POST_VARS["ver_inscr"] = $q01_inscr;

  $k00_numpre = $q01_numpre;  
  
  $resulttipo = pg_exec("select k00_descr,k00_codbco,k00_codage,k00_txban,k00_rectx,
                            k00_hist1,k00_hist2,k00_hist3,k00_hist4,k00_hist5,
                            k00_hist6,k00_hist7,k00_hist8 
                     from arretipo 
		     where k00_tipo = $tipo_debito ");
  db_fieldsmemory($resulttipo,0);

  if ( $tipo_debito==28 ){
     $sql28 = "select b.* 
               from diversos a 
                    left outer join procdiver b on a.procdiver=b.procdiver 
               where k00_numpre = $k00_numpre limit 1";
     $result28 = pg_exec($sql28);
     if (pg_numrows($result28) > 0){
        db_fieldsmemory($result28,0);
        $pdf1->tipodebito = 'PARCELAMENTO DE '.$dcopdiver;
     }
  }
  if ( $tipo_debito==25 ){
     $sql25 = "select b.* 
               from diversos a 
                    left outer join procdiver b on a.procdiver = b.procdiver 
									                             and dv05_instit = ".db_getsession('DB_instit')."
               where k00_numpre = $k00_numpre limit 1";
     $result25 = pg_exec($sql25);
     db_fieldsmemory($result25,0);
     $pdf1->tipodebito = $dcopdiver;

     if ( $procdiver == 1284 ){
        $pdf1->secretaria = 'FUNDO MUNICIPAL DE HABITAÇÃO';
     //   $texto1  = 'PRESTAÇÃO DE LOTE URBANIZADO - LOT. SOL NASCENTE';
        $k00_hist1 = 'Convênio SEHAB nº 72/99 - Programa Especial do Funco de Desenvolvimento Social';
        $k00_hist2 = 'Aprovação do Conselho Estadual de Habitação em 08/09/1999';
     }else if ( $procdiver == 221 ){
        $pdf1->secretaria = 'FUNDO MUNICIPAL DE HABITAÇÃO';
     //   $texto1  = 'PRESTAÇÃO DE LOTE URBANIZADO COM CASA - LOT. POR-DO-SOL';
        $k00_hist1  = 'Lei Municipal nº 3049/2002, de 04/12/2002';
        $k00_hist2  = 'Aprovação do Conselho Estadual de Habitação em dez/2002';
     }
  }

  if(!empty($HTTP_POST_VARS["ver_matric"])) {
     $numero = $HTTP_POST_VARS["ver_matric"];
     $descr = 'Matricula';
     $Identificacao = pg_exec("select *
   	                       from proprietario
		  	       where j01_matric = ".$HTTP_POST_VARS["ver_matric"]." limit 1");

  } else if(!empty($HTTP_POST_VARS["ver_inscr"])) {
     $numero = $HTTP_POST_VARS["ver_inscr"];
     $descr = 'Inscrição';
     $Identificacao = pg_exec("select * from empresa where q02_inscr = $numero ");

  } else {
     $numero = $HTTP_POST_VARS["ver_numcgm"];
     $descr = 'CGM';
     $Identificacao = pg_exec("select z01_nome,z01_ender,z01_munic,z01_uf,z01_cep,''::bpchar as nomepri,''::bpchar as j39_compl,''::bpchar as j39_numero,z01_bairro as j13_descr 
                               from cgm
	  		       where z01_numcgm = ".$HTTP_POST_VARS["ver_numcgm"]);
  } 
  db_fieldsmemory($Identificacao,0); 
  if ( ( $tipo_debito==6 ) || ( $tipo_debito== 21 ) || ( $tipo_debito== 26 ) || ( $tipo_debito== 28 ) ) {
         $sqltipodeb = "
                select termo.*,z01_nome,z01_ender,z01_numero,z01_compl,z01_bairro,
			coalesce(k00_matric,0) as matric,
			coalesce(k00_inscr,0) as inscr
		from termo
			left outer join arrematric	on v07_numpre = arrematric.k00_numpre
			left outer join arreinscr	on v07_numpre = arreinscr.k00_numpre
			inner join cgm 			on v07_numcgm = z01_numcgm
                        where v07_numpre = $k00_numpre
                  ";
    $resulttipodeb = pg_exec($sqltipodeb);
    db_fieldsmemory($resulttipodeb,0);
  } 
  $exercicio = '';
  if ( $tipo_debito==6 ) {
     $sqldivida = "select distinct v01_exerc 
                   from termodiv 
                        inner join divida on v01_coddiv = coddiv 
                   where parcel = $v07_parcel";
     $resultdivida = pg_exec($sqldivida);
     $traco = '';
     $exercicio = ' - Exerc : ';
     for ($k = 0;$k < pg_numrows($resultdivida);$k++){  
       $exercicio .= $traco.substr(pg_result($resultdivida,$k,"v01_exerc"),2,2);
       $traco = '-';
     }
  }
  if(!empty($HTTP_POST_VARS["ver_matric"])) {
    $sqlcgm = "select * from cgm where z01_numcgm = $z01_numcgm";
    $resultcgm = pg_exec($sqlcgm);
    
    db_fieldsmemory($resultcgm,0);
  }
//echo db_getsession($DB_datausu);
  $result = pg_exec("select fc_numbco($k00_codbco,'$k00_codage')");
  db_fieldsmemory($result,0);
  $H_ANOUSU  = db_getsession("DB_anousu");
  $H_DATAUSU = mktime(0,0,0,'02','13',$H_ANOUSU);
  $valores = 0;
//  $k00_numpre = $valores[0];
//  $k00_numpar = 1;  
  $k03_anousu = $H_ANOUSU;
  $DadosPagamento = debitos_numpre_carne($k00_numpre,$k00_numpar,$H_DATAUSU,$H_ANOUSU);
  db_fieldsmemory($DadosPagamento,0);
  $sql1 = "select k00_dtvenc,k00_numtot 
           from arrecad 
           where k00_numpre = $k00_numpre 
             and k00_numpar = $k00_numpar 
           limit 1";  
  db_fieldsmemory(pg_exec($sql1),0);
  $k00_dtvenc = db_formatar($k00_dtvenc,'d');
  $sqlvalor = "select k00_impval from arretipo where k00_tipo = $tipo_debito";
  db_fieldsmemory(pg_exec($sqlvalor),0);
  if ($k00_impval == 't' ){
      $k00_valor = $total;
      $vlrbar = "0".str_replace('.','',str_pad(number_format($k00_valor,2,"","."),11,"0",STR_PAD_LEFT));
      $ninfla = '';

      if($k00_valor > 999)
         $vlrbar = "0".$vlrbar;
      if($k00_valor > 9999)
         $vlrbar = "0".$vlrbar;
      if($k00_valor > 99999)
         $vlrbar = "0".$vlrbar;
  }else{
      $k00_valor = $qinfla;
      $vlrbar = "00000000000";
  }

//  $numbanco = "4268" ;// deve ser tirado do db_config
  $resultnumbco = pg_exec("select numbanco, segmento, formvencfebraban from db_config where codigo = " . db_getsession("DB_instit"));
  db_fieldsmemory($resultnumbco,0) ;// deve ser tirado do db_config
  $numpre = db_numpre($k00_numpre).db_formatar($k00_numpar,'s',"0",3,"e");
  $dtvenc = substr($k00_dtvenc,6,4).substr($k00_dtvenc,3,2).substr($k00_dtvenc,0,2);   
  $datavencimento = $k00_dtvenc;
  
  if ($formvencfebraban == 1) {
    $db_dtvenc = str_replace("-","",$datavencimento);
    $vencbar = $db_dtvenc . '000000';
  } elseif ($formvencfebraban == 2) {
    $db_dtvenc = str_replace("-","",$datavencimento);
    $db_dtvenc = substr($db_dtvenc,6,2) . substr($db_dtvenc,4,2) . substr($db_dtvenc,2,2);
    $vencbar = $db_dtvenc . '00000000';
  }

  $inibar="8" . $segmento . "7";
  $resultcod = pg_exec("select fc_febraban('$inibar'||'$vlrbar'||'$numbanco'||'".$vencbar."'||'$numpre')");
  db_fieldsmemory($resultcod,0);

  if ($fc_febraban == "") {
    db_msgbox("Erro ao gerar codigo de barras (3)!");
    exit;
  }

  $codigo_barras   = substr($fc_febraban,0,strpos($fc_febraban,','));
  $linha_digitavel = substr($fc_febraban,strpos($fc_febraban,',')+1);

  $result = pg_exec("select k15_local,k15_aceite,k15_carte,k15_espec,k15_ageced
 		     from cadban
                     where k15_codbco = $k00_codbco 
                       and k15_codage = '$k00_codage'");
  if(pg_numrows($result) > 0) {	
    $k15_local=pg_result($result,0,0);
    $k15_aceite=pg_result($result,0,1);
    $k15_carte=pg_result($result,0,2);
    $k15_espec=pg_result($result,0,3);
    $k15_ageced=pg_result($result,0,4);
    $fc_numbco=$fc_numbco;
    $dt_hoje=date('d/m/Y',$H_DATAUSU);
  }
  $numpre = db_sqlformatar($k00_numpre,8,'0').'000999';
  $numpre = $numpre . db_CalculaDV($numpre,11);

  $numbanco   = $fc_numbco;
  global $pdf;
  $pdf1->titulo1   = $descr; 
  $pdf1->descr1    = $numero; 
  $pdf1->descr2    = db_numpre($k00_numpre,0).db_formatar($k00_numpar,'s',"0",3,"e"); 
  $pdf1->descr3_1  = $z01_nome;   
  $pdf1->descr3_2  = strtoupper($z01_ender).', '.$z01_numero.'  '.$z01_compl;
 
  if ( $k00_hist1 == '' || $k00_hist2 == '' ){
     $pdf1->descr12_1 = $k00_numpar.'a PARCELA'; 
  }else{
     $pdf1->descr12_1 = $k00_hist1; 
     $pdf1->descr12_2 = $k00_hist2; 
  }
  
  if ( $tipo_debito==2 ){
     $pdf1->titulo4   = 'Atividade'; 
     $pdf1->descr4    = $q07_ativ.'-'.$q03_descr; 
     $pdf1->titulo13  = 'Atividade';
     $pdf1->descr13   = $q07_ativ;
  }else if ( ( $tipo_debito==6 ) || ( $tipo_debito==21 ) ){
     $pdf1->titulo4   = 'Parcelamento';
     $pdf1->descr4    = $v07_parcel.$exercicio;
     $pdf1->titulo13 = 'Parcelamento';
     $pdf1->descr13  = $v07_parcel;
  }
  $pdf1->descr4_1 = '- LOCAIS DE PAGAMENTO ATÉ O VENCIMENTO: BANCO DO BRASIL, BANRISUL, CAIXA ECONÔMICA FEDERAL E LOTÉRICAS, VIA INTERNET E HOME BANKING.';
//  $pdf1->descr4   = $k00_numpar.'a PARCELA  -  Alíquota '.$q01_valor.'%   EXERCÍCIO : '.$H_ANOUSU;
  $pdf1->descr5    = $k00_numpar.' / '.$k00_numtot; 
  $pdf1->descr6    = $k00_dtvenc; 
//  $pdf1->descr7    = ($ninfla==''?db_formatar($k00_valor,'f'):$k00_valor); 
  $pdf1->titulo8   = $descr;
  $pdf1->descr8    = $numero; 
  $pdf1->descr9    = db_numpre($k00_numpre,0).db_formatar($k00_numpar,'s',"0",3,"e");
  $pdf1->descr10   = $k00_numpar.' / '.$k00_numtot;;
  $pdf1->descr11_1 = $z01_nome;
  $pdf1->descr11_2 = strtoupper($z01_ender).', '.$z01_numero.'  '.$z01_compl;
  $pdf1->descr12_1 = $k00_numpar.'a PARCELA           EXERCÍCIO : '.$H_ANOUSU; 
  $pdf1->descr12_2 = 'Alíquota '.$q01_valor.'%';
  $pdf1->titulo15  = 'Valor pago'; 
  $pdf1->titulo13  = 'Valor da receita tributável';
  $pdf1->descr14   = $k00_dtvenc;
  $k  = $ii + 1;
  $kk = $volta + 1;
  $pdf1->texto     = $k.' / '.$totalregistros.'  -  '.$kk.' / '.$totalcarnes;
  $pdf1->linha_digitavel = $linha_digitavel;
  $pdf1->codigo_barras   = $codigo_barras;
  $pdf1->imprime();
}
//']echo $passou++."<br>";
}

$pdf1->objpdf->Output();


?>