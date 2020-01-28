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

include("fpdf151/pdf.php");
include("classes/db_db_versao_classe.php");
include("classes/db_db_versaoant_classe.php");
include("classes/db_db_versaousutarefa_classe.php");
include("classes/db_db_config_classe.php");
include("classes/db_clientes_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$cldb_versao = new cl_db_versao;
$cldb_versaoant = new cl_db_versaoant;
$cldb_versaousutarefa = new cl_db_versaousutarefa;

if( isset( $versao_inicial ) ){
  $result = $cldb_versaoant->sql_record($cldb_versaoant->sql_query(null," db31_codver,fc_versao(db30_codversao, db30_codrelease) as versao_ini ", "db31_codver", " db31_codver = $versao_inicial "));
}else{
  $result = $cldb_versaoant->sql_record($cldb_versaoant->sql_query(null," db31_codver,fc_versao(db30_codversao, db30_codrelease) as versao_ini ", "db31_codver", " db31_codver desc limit 1 "));
}

//db_criatabela($result); exit;

$versao_inicial = 0;
$versao_ini = '';
if($cldb_versaoant->numrows > 0){
  db_fieldsmemory($result,0);
  $versao_inicial = $db31_codver;
}


$result = $cldb_versao->sql_record($cldb_versao->sql_query_file(null," db30_codver,fc_versao(db30_codversao, db30_codrelease) as versao",' db30_codver desc limit 1'));

$head3 = "Versão: $versao_ini ";
if($cldb_versao->numrows > 0){
  db_fieldsmemory($result,0);
  $head3 .= " a $versao";
}

$head5 = "Atualizações realizadas nesta versão";
$clclientes = new cl_clientes;


if( isset($cliente) ){

  if( $tipo_relatorio == "1" || $cliente == "" ){
  
    $filtra_cliente = "";
    
  }else{

    $filtra_cliente = " at01_ativo is true and at01_codcli in ( $cliente ) ";

    
  }  
  
  $result = $clclientes->sql_record($clclientes->sql_query(null," at01_codcli "," at01_nomecli ",$filtra_cliente));
    
  $separadorc = "";
  $filtra_cliente = " at01_ativo is true and at01_codcli in ( ";
  for($c=0;$c<$clclientes->numrows;$c++){
    db_fieldsmemory($result,$c);
    $filtra_cliente .= $separadorc." $at01_codcli ";
    $separadorc = ",";
  }
  $filtra_cliente .= " ) ";


}else{

  $cldb_config = new cl_db_config;
  $result = $cldb_config->sql_record($cldb_config->sql_query(null," db21_codcli "," codigo = ".db_getsession("DB_instit")));
  db_fieldsmemory($result,0);

  $filtra_cliente = " at01_codcli in ($db21_codcli) ";

  $tipo_relatorio = "2";

  $dirpadrao = "tmp";
  
}


$result = $clclientes->sql_record($clclientes->sql_query(null," at01_codcli, at01_nomecli "," at01_nomecli ",$filtra_cliente));
$separador_download = "";
$arquivo_gerado = "";

for($c=0;$c<$clclientes->numrows;$c++){

  db_fieldsmemory($result,$c);
  
  $filtra_cliente = " and ( db27_codcli is null or db27_codcli = $at01_codcli ) ";
  if($tipo_relatorio == "1"){
    $filtra_cliente = "";
  }

$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas

$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->SetFont('Arial','B',11);



$sql_modulo = "select distinct modulo,nome_modulo 
          from db_versaousu 
               inner join db_menu on db32_id_item = db_menu.id_item 
               inner join db_modulos on modulo = db_modulos.id_item
               left join db_versaousucliente on db32_codusu = db27_codusu
          where db32_codver >= $versao_inicial 
              $filtra_cliente         
          union 
          select distinct modulo,nome_modulo 
          from db_versaousu 
               inner join db_menu on db32_id_item = id_item_filho 
               inner join db_modulos on modulo = db_modulos.id_item
               left join db_versaousucliente on db32_codusu = db27_codusu
          where db32_codver >=  $versao_inicial 
              $filtra_cliente         
          order by modulo
          ";
 

$sql = "select * from ($sql_modulo) as x ";

if(isset($id_item) && $id_item != 0){
  $sql .= " where modulo = $id_item ";
}
$sql .= "
        order by nome_modulo";

$res = pg_exec($sql);

$numrows = pg_numrows($res);


for($i=0;$i<$numrows;$i++){
    
  db_fieldsmemory($res,$i);

  $espacos = $modulo;

  $matriz_item = array();
  $matriz_item_seleciona = array();

  $resultx = $cldb_versao->sql_record($cldb_versao->sql_query(null,'distinct db30_codversao, db30_codrelease,db32_id_item ','db30_codversao,db30_codrelease'," db30_codver >= $versao_inicial  and not db32_obs is null and db32_id_item in (select distinct id_item from db_menu where modulo = $modulo union select distinct id_item_filho from db_menu where modulo = $modulo) "));

  if( $cldb_versao->numrows > 0 ) {

    $pdf->addpage();
    
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(15,4,"Módulo: $nome_modulo",0,1,"L");
    $pdf->SetFont('Arial','',7);
 
    for($ii=0;$ii<pg_numrows($resultx);$ii++){
      $x = pg_result($resultx,$ii,2);
      $lista[$x]= $x;
    }

    $matriz_item_seleciona = array();

    monta_menu($modulo,$modulo,$espacos,$lista);
    
    $itens_listados = array();//("$id_item"=>"$id_item");

    for($x=0;$x<count($matriz_item_seleciona);$x++){
      $impmat = split("-",$matriz_item_seleciona[$x]);
      for($imp=0;$imp<count($impmat);$imp++){
        if( ! isset($itens_listados[$impmat[$imp]])){
          
          $sql = "select distinct db30_codversao,db30_codrelease,db32_obs
                  from db_versaousu
                       inner join db_versao on db32_codver = db30_codver
                       left join db_versaousucliente on db32_codusu = db27_codusu
                  where db30_codver >= $versao_inicial
                    $filtra_cliente
                    and db32_id_item = ".$impmat[$imp];
          $resid = pg_exec($sql);
          if( pg_numrows($resid) > 0 ){


            $itens_listados[$impmat[$imp]] = $impmat[$imp] ;
            $sql = "select descricao 
                  from db_itensmenu
                  where id_item = ".$impmat[$imp];
            $resi = pg_exec($sql);
            $descr = pg_result($resi,0,0);

            //$pdf->Cell($imp*5,4,$matriz_item_seleciona[$x],0,0,"L");
            $pdf->Cell($imp*5,4,'',0,0,"L");
          
            $pdf->SetFont('Arial','',7);
            $pdf->Cell(60,4,$descr,0,1,"L");
          }
        }
      }
    }

    $pdf->Cell(60,4,'',0,1,"L");
  
    // lista as descricoes

    $itens_listados = array();
    for($x=0;$x<count($matriz_item_seleciona);$x++){
      $contador = 0;
      $impmat = split("-",$matriz_item_seleciona[$x]);
      for($imp=0;$imp<count($impmat);$imp++){
        $contador += 1;
        if( ! isset($itens_listados[$impmat[$imp]])){

          $sql = "select distinct db30_codversao,db30_codrelease,db32_obs
                  from db_versaousu
                       inner join db_versao on db32_codver = db30_codver
                       left join db_versaousucliente on db32_codusu = db27_codusu
                  where db30_codver >= $versao_inicial
                    $filtra_cliente
                    and db32_id_item = ".$impmat[$imp];
          $resid = pg_exec($sql);
          if( pg_numrows($resid) > 0 ){
          
            $itens_listados[$impmat[$imp]] = $impmat[$imp] ;
            $sql = "select descricao 
                  from db_itensmenu
                  where id_item = ".$impmat[$imp];
            $resi = pg_exec($sql);
            $descr = pg_result($resi,0,0);

            //$pdf->Cell($imp*5,4,$matriz_item_seleciona[$x],0,0,"L");
            $pdf->Cell($contador*5,4,'',0,0,"L");
          
            $pdf->SetFont('Arial','B',7);
            //$pdf->Cell(60,4,$impmat[$imp]."-".$descr,0,1,"L");
            $pdf->Cell(60,4,$descr,0,1,"L");
            $pdf->SetFont('Arial','',7);

            for($o=0;$o<pg_numrows($resid);$o++){
            
              db_fieldsmemory($resid,$o);
          
              $pdf->SetFont('Arial','I',7);
              $pdf->Cell(($contador+1)*5,4,"2.".$db30_codversao.".".$db30_codrelease,0,0,"R");

              $sql = "select distinct db32_codusu
                  from db_versaousu
                       inner join db_versao on db32_codver = db30_codver
                       left join db_versaousucliente on db32_codusu = db27_codusu
                  where db30_codver >= $versao_inicial and
                        db30_codversao = $db30_codversao and db30_codrelease = $db30_codrelease
                    $filtra_cliente
                    and db32_id_item = ".$impmat[$imp];
              $residu = pg_exec($sql);
              if( pg_numrows($residu) > 0 ){
                $tarefas = 'Tarefa(s): ';
                $separador = "";
                $inusu = "";
                for($codu=0;$codu<pg_numrows($residu);$codu++){
                  db_fieldsmemory($residu,$codu);
                  $inusu .= $separador.$db32_codusu;
                  $separador = ",";
                }
                  
                $separador = "";  
                $restaf = $cldb_versaousutarefa->sql_record($cldb_versaousutarefa->sql_query(null,'distinct db28_tarefa',''," db28_codusu in ($inusu) "));
                if( $cldb_versaousutarefa->numrows > 0 ){
                  for($xx=0;$xx<$cldb_versaousutarefa->numrows;$xx++){
                    db_fieldsmemory($restaf,$xx);
                    $tarefas .= $separador.$db28_tarefa;
                    $separador = ", ";
                  }
                }
                $pdf->Cell(160,4,"$tarefas",0,1,"L");
                $pdf->Cell(($contador+1)*5,4,"",0,0,"R");
              }
              
              $pdf->SetFont('Arial','',7);
              $pdf->multicell(160,4,$db32_obs);

            }
          }

        }
      }
    }
  }


}

  if( isset($cliente) ){


    $pdf->Output(null,null,true);

    if($tipo_relatorio == "1"){

      system("mv ".$pdf->arquivo_retorno." ".$dirpadrao."/geral_".$versao.".pdf");    
      $arquivo_gerado = $dirpadrao."/geral_".$versao.".pdf#Arquivo Gerado em: ".$dirpadrao."/geral_".$versao.".pdf";
      break;
    
    }else{
    
      $nomearq = split(" ",$at01_nomecli);
      $nomearq = strtolower($nomearq[0])."_".$versao.".pdf";
    
      system("mv ".$pdf->arquivo_retorno." ".$dirpadrao."/$nomearq");    
      $arquivo_gerado .= $separador_download."$dirpadrao/".$nomearq."#Arquivo Gerado em: $dirpadrao/".$nomearq;

    
      $separador_download = "|";
    }
  }else{
    $pdf->Output();
    break;
  }
  
  
}
if( isset($cliente) ){
  echo "<script>";
  echo "parent.js_montarlista('$arquivo_gerado','form1');";
  echo "</script>";
}
?>