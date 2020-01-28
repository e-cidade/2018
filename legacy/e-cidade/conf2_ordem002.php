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
include("libs/db_sql.php");

$clrotulo = new rotulocampo;
$clrotulo->label('');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

/*
 data
 data1
 tipo [1|2|3]
*/
$usa_datas= true;
if (($data =="--")||($data1 =="--")) {
  $data = "";
  $data1= "";
  $usa_datas= false;
}
$sql = "  select distinct o.codordem, ";
if ($tipo==2){
  $sql=" select distinct o.codordem, ";
}
   
  $sql.=" dataordem, 
           o.descricao,
           o.id_usuario, 
           o.usureceb, 
           o.coddepto, 
           dataprev, 
           u.nome,
           q.nome as destino
     from db_ordem o
         left join db_usuarios u on u.id_usuario = o.id_usuario			      
         left join db_usuarios q on q.id_usuario = o.usureceb			      
	 left join db_ordemandam andam on andam.codordem = o.codordem
         left join db_ordemfim on db_ordemfim.codordem = o.codordem  
      ";			  

if ($tipo == 1 ){ // sem andamento
   $sql .="where andam.codordem is null 
            and db_ordemfim.codordem is null   
           ";
} else if ($tipo==2){ // com andamento e nao finalizados
  $sql .=" 
           where andam.codordem is not null 
             and db_ordemfim.codordem is null
          ";
} else if ($tipo==3){ // finalizadas
   $sql .=" where db_ordemfim.codordem not is null ";
} else if ($tipo==4){ // finalizadas
   $sql .=" where db_ordemfim.codordem is null ";
}  
if ($usa_datas ==true){
  $sql.=" and dataordem betwenn '$data' and '$data1' ";
}
if ($origem != 0 ){
  $sql.=" and codorigem = $origem  ";
}  

$sql .=" order by  $ordem  ";
 
 

$head2 = "RELATÓRIO DE ORDEM DE SERVIÇO";
$head3 = "Origem $origem";
$head4 = "Tipo  $tipo ";
$head5 = "Ordem $ordem";
//echo $sql;exit;

$result = pg_exec($sql);

if (pg_numrows($result) == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem ordens cadastradas para filtros selecionados.');
}
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$p=0;
 
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x,true);

   $sqlmod = "	select nome_modulo from db_ordemmod 
   		inner join db_modulos on db_ordemmod.id_item = db_modulos.id_item
   		where codordem = $codordem limit 1";
   $resultmod = pg_exec($sqlmod);
   if (pg_numrows($resultmod) == 0) {
     $nome_modulo = "";
   } else {
     db_fieldsmemory($resultmod,0);
   }
   
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      $pdf->cell(10,$alt,"Ordem",1,0,"C",1);
      $pdf->cell(20,$alt,"Prev",1,0,"C",1);
      $pdf->cell(40,$alt,"Destinatario",1,0,"L",1);
      $pdf->cell(40,$alt,"Solicitante",1,0,"L",1);       
      $pdf->cell(170,$alt,"Descrição",1,1,"L",1);
      
      /*Ordem          
        Prev        
        Solicitante 
        Destinatario
        Descrição   
        Anotações      */ 
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(10,$alt,$codordem,0,0,"C",$p);
   $pdf->cell(20,$alt,$dataprev,0,0,"C",$p);
//   $pdf->cell(40,$alt,strtoUpper(substr($destino,0,20)),0,0,"L",$p);
   $pdf->cell(40,$alt,strtoUpper(substr($nome_modulo,0,20)),0,0,"L",$p);
   $pdf->cell(40,$alt,strToUpper(substr($nome,0,20)),0,0,"L",0,$p);   
   $pdf->multicell(170,$alt,$descricao,0,"L",$p);
   $pdf->cell(280,$alt,"","T",1,"C",$p);
   $total++;
     
   /*
   $codordem      
   $dataprev
   $nome 
   $destino 
   $descricao*/
}
$pdf->setfont('arial','b',8);
$pdf->cell(130,$alt,'TOTAL DE ORDENS  :  '.$total,0,0,"L",0);
$pdf->Output();
?>