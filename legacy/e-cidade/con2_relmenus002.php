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

include("fpdf151/pdf.php");
include("classes/db_db_versao_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
//echo "mod = $mod";

$cldb_versao = new cl_db_versao;

$head5 = "Menus do Sistema";
$head6 = "Data: ".date("d-m-Y",db_getsession("DB_datausu"));
$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas

$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->SetFont('Arial','B',11);

// variaveis com as versoes para gerar o relatorio, devera ser as mesmas do pacote.
$result = $cldb_versao->sql_record($cldb_versao->sql_query_file(null,' min(db30_codver) as codversao_inicial,max(db30_codver) as codversao_final '));
db_fieldsmemory($result,0);

$sql = "select m.id_item,nome_modulo 
        from db_modulos m
             inner join db_itensmenu i on i.id_item = m.id_item
				where m.id_item in ($mod)
				order by nome_modulo";

$res = pg_exec($sql);

$numrows = pg_numrows($res);

for($i=0;$i<$numrows;$i++){
    
  db_fieldsmemory($res,$i);

  $espacos = $id_item;

  $matriz_item = array();
  $matriz_item_seleciona = array();


    $head3 = "Modulo: $nome_modulo";
    $pdf->addpage();
    
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(15,4,"Modulo: $nome_modulo",0,1,"L");
    $pdf->SetFont('Arial','',7);
 
    $matriz_item_seleciona = array();

    $lista = array();
    
    monta_menu($id_item,$id_item,$espacos,$lista);
    
    $itens_listados = array("$id_item"=>"$id_item");

    for($x=0;$x<count($matriz_item_seleciona);$x++){
      $impmat = split("-",$matriz_item_seleciona[$x]);
      for($imp=0;$imp<count($impmat);$imp++){
        if( ! isset($itens_listados[$impmat[$imp]])){
          
          $itens_listados[$impmat[$imp]] = $impmat[$imp] ;
          $sql = "select descricao 
                  from db_itensmenu
                  where id_item = ".$impmat[$imp]." and itemativo = 1";
          //echo "$sql";
          $resi = pg_exec($sql);
          $linhas = pg_num_rows($resi);
          if($linhas >0){
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
  

}


$pdf->Output();
?>