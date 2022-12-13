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

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$cldb_versao = new cl_db_versao;
$cldb_versaoant = new cl_db_versaoant;

$result = $cldb_versaoant->sql_record($cldb_versaoant->sql_query(null," db31_codver,fc_versao(db30_codversao, db30_codrelease) as versao_ini ",' db31_codver desc limit 1 '));

$versao_inicial = 0;
if($cldb_versaoant->numrows > 0){
  db_fieldsmemory($result,0);
  $versao_inicial = $db31_codver;
}

$result = $cldb_versao->sql_record($cldb_versao->sql_query_file(null," db30_codver,fc_versao(db30_codversao, db30_codrelease) as versao",' db30_codver desc limit 1'));
$head3 = "Versao : $versao_ini ";
$versao_final = 0;
if($cldb_versao->numrows > 0){
  db_fieldsmemory($result,0);
  $head3 .= " a Versao: $versao";
  $versao_final = $db30_codver;
}

$head5 = "Atualizacoes realizadas nesta versao";
$head6 = "Por procedimentos";
$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas

$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->SetFont('Arial','',8);
$pdf->addpage();

$sql_modulo = "select modulo,nome_modulo 
          from db_versaousu 
               inner join db_menu on db32_id_item = db_menu.id_item 
               inner join db_modulos on modulo = db_modulos.id_item
          where db32_codver >= $versao_inicial
          union 
          select modulo,nome_modulo 
          from db_versaousu 
               inner join db_menu on db32_id_item = id_item_filho 
               inner join db_modulos on modulo = db_modulos.id_item
          where db32_codver >= $versao_inicial
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

      $sql = "select i.codproced,descrproced
              from ( select id_item,modulo from (
                     select id_item,modulo
                     from db_menu
                     union
                     select id_item_filho,modulo
                     from db_menu
                     ) as x where id_item in ( 
                                             select distinct db32_id_item 
                                             from db_versaousu
                                             where db32_codver >= $versao_inicial
                                             ) 

                   ) as x
                        inner join db_syscadproceditem i on i.id_item = x.id_item
                        inner join db_syscadproced c on c.codproced = i.codproced
                        inner join db_modulos m on m.id_item = x.modulo
              where modulo = $modulo
              order by i.codproced";

  $result = pg_query($sql);

  if(pg_numrows($result)>0){
        
    $pdf->Cell(60,4,'Modulo: '.$nome_modulo,0,1,"L");

    for($m=0;$m<pg_numrows($result);$m++){
      db_fieldsmemory($result,$m);
      $pdf->Cell(60,4,'  '.$descrproced,0,1,"L");
      $sql = "select distinct db30_codversao,db30_codrelease,trim(db32_obs) as db32_obs
              from db_versaousu
                   inner join db_versao on db30_codver = db32_codver
                   inner join db_syscadproceditem i on i.id_item = db32_id_item
                   inner join db_syscadproced c on c.codproced = i.codproced
              where db32_codver >= $versao_inicial
              and db32_id_item in 
              (
                 select id_item
                 from db_menu
                 where modulo = $modulo
                 union
                 select id_item_filho
                 from db_menu
                 where modulo = $modulo
              ) ";

      $resitem = pg_query($sql);
      for($mi=0;$mi<pg_numrows($resitem);$mi++){
        db_fieldsmemory($resitem,$mi);
        $pdf->Cell(5,4,"");
        $pdf->Cell(15,4,"2.$db30_codversao.$db30_codrelease");
        $pdf->multicell(0,4,"$db32_obs");
      }
    }
  }
}


$pdf->Output();
?>