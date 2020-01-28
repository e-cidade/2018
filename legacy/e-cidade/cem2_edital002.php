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
include("classes/db_renovacoes_classe.php");
include("classes/db_sepulta_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clrenovacoes = new cl_renovacoes;
$clsepulta = new cl_sepulta;

 $data1 = str_replace("X","-",$data1);
 $data2 = str_replace("X","-",$data2);


/*$sql = "select z01_nome,
               cm05_c_numero,
	       to_char(cm01_d_falecimento,'dd/mm/yyyy') as cm01_d_falecimento,
               to_char(cm07_d_vencimento,'dd/mm/yyyy') as cm07_d_vencimento,
               cm19_c_descr,
               cm22_c_quadra,
               cm23_i_lotecemit
          from renovacoes
         inner join sepultamentos on sepultamentos.cm01_i_codigo    = renovacoes.cm07_i_sepultamento
         inner join cgm           on cgm.z01_numcgm                 = sepultamentos.cm01_i_codigo
         inner join sepulta       on sepulta.cm24_i_sepultamento    = sepultamentos.cm01_i_codigo
         inner join sepulturas    on sepulturas.cm05_i_codigo       = sepulta.cm24_i_sepultura
         inner join campas        on sepulturas.cm05_i_campa        = campas.cm19_i_codigo
         inner join lotecemit     on lotecemit.cm23_i_codigo        = sepulturas.cm05_i_lotecemit
         inner join quadracemit   on quadracemit.cm22_i_codigo      = lotecemit.cm23_i_quadracemit
         where cm07_d_vencimento BETWEEN '$data1' and '$data2'
         order by z01_nome,cm07_d_vencimento";
die($sql);*/


$sql = "select distinct z01_nome, 
               cm05_c_numero, 
               to_char(cm01_d_falecimento,'dd/mm/yyyy') as cm01_d_falecimento, 
 
               (select case when to_char(cm07_d_vencimento,'dd/mm/yyyy') is null 
                     then '--' else to_char(cm07_d_vencimento,'dd/mm/yyyy') end as cm07_d_vencimento 
                  from renovacoes
  			     where renovacoes.cm07_i_sepultamento = cm01_i_codigo
                 order by cm07_d_vencimento desc limit 1) as cm07_d_vencimento ,

  	           cm19_c_descr, 
	           cm22_c_quadra, 
	           cm23_i_lotecemit 
   	      from sepulta
  	     inner join sepultamentos on sepultamentos.cm01_i_codigo = sepulta.cm24_i_sepultamento 
         inner join cgm on cgm.z01_numcgm = sepultamentos.cm01_i_codigo   
         inner join sepulturas on sepulturas.cm05_i_codigo = sepulta.cm24_i_sepultura 
         inner join campas on sepulturas.cm05_i_campa = campas.cm19_i_codigo 
         inner join lotecemit on lotecemit.cm23_i_codigo = sepulturas.cm05_i_lotecemit 
         inner join quadracemit on quadracemit.cm22_i_codigo = lotecemit.cm23_i_quadracemit 
          left join renovacoes on sepulta.cm24_i_sepultamento = renovacoes.cm07_i_sepultamento  
         where cm07_d_vencimento BETWEEN '$data1' and '$data2' 
            or  ( extract(year from '$data1'::date) - extract(year from cm24_d_entrada::date)) = 5 and cm07_d_vencimento is null 
            and ( extract(month from '$data2'::date) = extract(month from cm24_d_entrada::date)) 
          order by z01_nome,cm07_d_vencimento";       

$result = pg_query($sql);
if(pg_numrows($result) == 0){
 echo "<table width='100%'>
       <tr>
        <td align='center'>
	 <font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatório<br>
	  <input type='button' value='Fechar' onclick='window.close()'></b>
	 </font>
        </td>
       </tr>
      </table>";
 exit;
}

$pdf = new PDF();
$pdf->Open(); 
$pdf->AliasNbPages(); 
$p = 0;

$head2 = "EDITAL";
$head3 = "Periodo:".substr($data1,8,2)."/".substr($data1,5,2)."/".substr($data1,0,4)." A ".substr($data2,8,2)."/".substr($data2,5,2)."/".substr($data2,0,4);

  $pdf->addpage();
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',7);
  $pdf->cell(190,4,"",0,1,"L",0);

  $pdf->cell(190,4,"Conforme informação do Setor Cemitério, desta Secretaria, estamos encaminhando a V. Sa. a relação das CAMPAS vencidas neste período as quais não foram",0,1,"L",0);
  $pdf->cell(190,4,"providenciadas renovações ou retiradas por familiares",0,1,"L",0);

  $pdf->cell(190, 3, "",            0, 1, "L", 0);
  $pdf->cell(80,  4, "Nome",        1, 0, "L", 1);
  $pdf->cell(18,  4, "Falecimento", 1, 0, "C", 1);
  $pdf->cell(18,  4, "Vencimento",  1, 0, "C", 1);
  $pdf->cell(15,  4, "Numero",      1, 0, "C", 1);
  $pdf->cell(35,  4, "Campa",       1, 0, "C", 1);
  $pdf->cell(10,  4, "Quadra",      1, 0, "C", 1);
  $pdf->cell(8,   4, "Lote",        1, 1, "C", 1);
  
 for ($i = 0;$i < pg_numrows($result); $i++){
 db_fieldsmemory($result,$i);
    if (($pdf->gety() > $pdf->h -30)){
      
      $pdf->addpage();
      $pdf->setfillcolor(235);
      $pdf->setfont('arial','b',7);

      $pdf->cell(80, 4, "Nome",        1, 0, "L", 1);
      $pdf->cell(18, 4, "Falecimento", 1, 0, "C", 1);
      $pdf->cell(18, 4, "Vencimento",  1, 0, "C", 1);
      $pdf->cell(15, 4, "Numero",      1, 0, "C", 1);
      $pdf->cell(35, 4, "Campa",       1, 0, "C", 1);
      $pdf->cell(10, 4, "Quadra",      1, 0, "C", 1);
      $pdf->cell(8,  4, "Lote",        1, 1, "C", 1);
    }

  $pdf->setfont('arial','',7);

  $pdf->cell(80,4,$z01_nome,0,0,"L",$p);
  $pdf->cell(18, 4, $cm01_d_falecimento ,0, 0, "C", $p);
  
  if($cm07_d_vencimento == '--'){
   
   $data = explode("/", $cm01_d_falecimento);
   $cm07_d_vencimento = $data[0]."/".$data[1]."/".($data[2] + 5);
   
   $pdf->cell(18, 4, $cm07_d_vencimento  ,0, 0, "C", $p);

  }else{
  
   $pdf->cell(18, 4, $cm07_d_vencimento  ,0, 0, "C", $p);

  }

  $pdf->cell(15, 4, $cm05_c_numero      ,0, 0, "C", $p);
  $pdf->cell(35, 4, $cm19_c_descr       ,0, 0, "L", $p);
  $pdf->cell(10, 4, $cm22_c_quadra      ,0, 0, "C", $p);
  $pdf->cell(8,  4, $cm23_i_lotecemit   ,0, 1, "C", $p);
  
  if($p == 1){
   $p = 0;
  }else{
   $p = 1;
  }
 }
 $pdf->cell(185, 4, "Total de Registros: ".pg_numrows($result), 1, 1, "L", 1);
 $pdf->Output();
?>