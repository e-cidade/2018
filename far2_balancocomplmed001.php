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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
//include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include('libs/db_stdlibwebseller.php');
include("libs/db_usuariosonline.php");
include("classes/db_far_retirada_classe.php");
include("classes/db_far_farmacia_classe.php");
include("classes/db_matestoqueinimei_classe.php");
include("classes/db_matestoque_classe.php");
include("classes/db_db_config_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$x=data_farmacia($ano,$periodo);
$datas=db_formatar($x[0],'d').'-'.db_formatar($x[1],'d');    

$clfar_retirada = new cl_far_retirada;
$clfar_farmacia = new cl_far_farmacia;
$clmatestoqueinimei = new cl_matestoqueinimei;
$cldb_config = new cl_db_config;
$clrotulo = new rotulocampo;
$exerc=date('Y');
$depto=db_getsession("DB_nomedepto");
$coddepto=db_getsession("DB_coddepto");
/**
 * Calcula o saldo de um item do estoque em determinada data 
 * data no formato "dd/mm/aaaa"
 *
 * @param String   $data
 * @param Interger $material
 */
function material_saldo($data,$material){
$dat = explode("/",$data);
$date= $dat[2]."-".$dat[1]."-".$dat[0];
$sql  = "select sum(case when m81_entrada is true then m82_quant else m82_quant*-1 end) as saldoInicial ";       
$sql .= "	from ( select m81_entrada, ";
$sql .= "	              m82_quant, ";
//$sql .= "              (fc_calculapm(m70_codmatmater::integer, m71_data, m80_codigo::integer) * m82_quant) as m71_valor ";
$sql .= "	             (m82_valorunitario * m82_quant) as m71_valor ";
$sql .= "	       from matestoqueini ";
$sql .= "	           inner join matestoquetipo on m80_codtipo = m81_codtipo ";
$sql .= "	           inner join matestoqueinimei on m82_matestoqueini = m80_codigo ";
$sql .= "	           inner join matestoqueitem on m82_matestoqueitem = m71_codlanc ";
$sql .= "	           inner join matestoque on m71_codmatestoque = m70_codigo ";
$sql .= "	           inner join db_depart on m80_coddepto = coddepto ";
$sql .= "	       where m70_codmatmater = $material and m80_data < '$date'::date ) as x ";

 $result= pg_query($sql);
 //die($sql); 
 if(pg_num_rows($result)>0){
   $saldoInicial=pg_result($result,0,0);
   return $saldoInicial;
  }else{
    return null;
  }

}


$explode= $datas;
$exp = explode("-",$explode);
$ini= $exp[0];
$fin= $exp[1];
$ini1= converte_data($ini);
$fin1= converte_data($fin);

$sql="select DISTINCT fa30_c_concentracao,m80_codtipo,m60_codmater,m60_descr,m70_quant,m82_quant,m71_quant,fa27_c_denominacao,fa28_c_numero,m71_quantatend from far_matersaude
   inner join matmater on matmater.m60_codmater       = far_matersaude.fa01_i_codmater   
   left  join far_medanvisa       on fa14_i_codigo      = far_matersaude.fa01_i_medanvisa
   left  join far_codigodcb       on fa28_i_medanvisa   = far_medanvisa.fa14_i_codigo
   left  join far_tipodc          on fa27_i_codigo      = far_codigodcb.fa28_i_codigo
   left  join far_listacontroladomed on fa35_i_medanvisa      = far_medanvisa.fa14_i_codigo
   left  join far_listacontrolado on fa15_i_codigo = far_listacontroladomed. fa35_i_listacontrolado
   left join far_concentracaomed on fa37_i_codigo =  far_matersaude.fa01_i_concentracaomed
   left join far_concentracao on fa30_i_codigo = far_concentracaomed. fa37_i_concentracao      
   inner join matestoque          on m70_codmatmater    = matmater.m60_codmater
   inner join matestoqueitem      on m71_codmatestoque  = matestoque.m70_codigo
   inner join matestoqueinimei    on m82_matestoqueitem = matestoqueitem.m71_codlanc
   inner join matestoqueini       on m80_codigo         = matestoqueinimei.m82_matestoqueini
where m80_data between '$ini1' and '$fin1'";
$sql.= " and (fa15_i_codigo=1
              or trim(upper(substr(fa15_c_listacontrolado,0,9)))='LISTA A2' 
              or trim(upper(substr(fa15_c_listacontrolado,0,9)))='LISTA A3' 
              or trim(upper(substr(fa15_c_listacontrolado,0,9)))='LISTA B2' 
              or trim(upper(substr(fa15_c_listacontrolado,0,9)))='LISTA C1')"; 
//die($sql);

$result=$clfar_retirada->sql_record($sql);


if($clfar_retirada->numrows == 0){
	echo "<table width='100%'>
        <tr>
         <td align='center'><font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatório<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
        </tr>
       </table>";
   exit;
}

  
   $resultconfig = $cldb_config->sql_record($cldb_config->sql_query(null,"*","",""));
   if($cldb_config->numrows > 0){
	db_fieldsmemory($resultconfig,0);
   }
  $resultfar = $clfar_farmacia->sql_record($clfar_farmacia->sql_query(null,"*","","fa13_i_departamento=$coddepto"));
   if($clfar_farmacia->numrows > 0){
	db_fieldsmemory($resultfar,0);
   }
   
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head1 = "BALANÇO COMPLETO DE MEDICAMENTOS";
$head2 = "Data:  ".db_formatar($ini1,'d'). " A " .db_formatar($fin1,'d');


$pdf->ln(5);
$pdf->addpage('L');
$total=0;
$cont=0;
$contador=0;
$pdf->setfont('arial','b',12);
 $pdf->cell(280,4,"BALANÇO COMPLETO DE MEDICAMENTOS",0,1,"C",0);
 $pdf->setfont('arial','b',6);
 $pdf->cell(200,5,"C.N.P.J : $fa13_c_cnpj",0,0,"L",0);
 $pdf->cell(30,5,"",0,0,"L",0); 
 $pdf->cell(200,7,"N° da licença de funcionamento : $fa13_c_numlicenca",0,1,"L",0);
 $pdf->cell(200,5,"Exercício : $exerc",0,0,"L",0);
 $pdf->cell(30,5,"",0,0,"L",0);  
 $pdf->cell(200,7,"Periocidade TRIMESTRAL:".db_formatar($ini1,'d'). ' A ' .db_formatar($fin1,'d'),0,1,"L",0);   
 $pdf->setfillcolor(240);
 $pdf->cell(20,4,"N° do Código ",1,0,"C",1); ///1
 $pdf->cell(20,4,"Descriminação",1,0,"C",1);///2
 $pdf->cell(110,4,"Nome do ",1,0,"C",1);///3
 $pdf->cell(30,4,"Apresentação e ",1,0,"C",1);///4
 $pdf->cell(20,4,"Estoque ",1,0,"C",1);///5
 $pdf->cell(20,4,"Entrada",1,0,"C",1);///6
 $pdf->cell(20,4,"Saídas",1,0,"C",1);///7
 $pdf->cell(20,4,"Perda",1,0,"C",1);///8
 $pdf->cell(20,4,"Estoque",1,1,"C",1);///9
 $pdf->cell(20,4,"Na DCB","BL",0,"C",1);//1
 $pdf->cell(20,4,"DCB","BL",0,"C",1);//2
 $pdf->cell(110,4,"Medicamento","BL",0,"C",1);//3
 $pdf->cell(30,4,"Concentração","BL",0,"C",1);//4
 $pdf->cell(20,4,"Inicial","BL",0,"C",1);//5
 $pdf->cell(20,4,"(Aquisição)","BL",0,"C",1);//6
 $pdf->cell(20,4,"(Venda)","BL",0,"C",1);//7
 $pdf->cell(20,4,"","BRL",0,"C",1);//8
 $pdf->cell(20,4,"Final","BRL",1,"C",1);//9
 db_fieldsmemory($result,0);
 $atual=$m60_codmater;
 $voltavalor=material_saldo($ini,$m60_codmater);
 for($i=0; $i<$clfar_retirada->numrows; $i++){
    db_fieldsmemory($result,$i);
    if($contador==32){
	   $pdf->ln(5);
       $pdf->addpage('L');
       $pdf->setfillcolor(240);
	   $pdf->cell(20,4,"N° do Código ",1,0,"C",1);///1 
 	   $pdf->cell(20,4,"Descriminação",1,0,"C",1);///2
       $pdf->cell(110,4,"Nome do ",1,0,"C",1);///3
       $pdf->cell(30,4,"Apresentação e ",1,0,"C",1);///4
       $pdf->cell(20,4,"Estoque ",1,0,"C",1);///5
       $pdf->cell(20,4,"Entrada",1,0,"C",1);///6
       $pdf->cell(20,4,"Saídas",1,0,"C",1);///7
       $pdf->cell(20,4,"Perda",1,0,"C",1);///8
       $pdf->cell(20,4,"Estoque final",1,1,"C",1);///9
       $pdf->cell(20,4,"Na DCB","BL",0,"C",1);//1
       $pdf->cell(20,4,"DCB","BL",0,"C",1);//2
       $pdf->cell(110,4,"Medicamento","BL",0,"C",1);//3
       $pdf->cell(30,4,"Concentração","BL",0,"C",1);//4
       $pdf->cell(20,4,"Inicial","BL",0,"C",1);//5
       $pdf->cell(20,4,"(Aquisição)","BL",0,"C",1);//6
       $pdf->cell(20,4,"(Venda)","BL",0,"C",1);//7
       $pdf->cell(20,4,"","BRL",0,"C",1);//8
       $pdf->cell(20,4,"Final","BRL",1,"C",1);//9
	   $contador=0;
	  } 
    
   $entrada="";
   $saida="";
   $perda="";
   switch ($m80_codtipo) {
     case  1:$entrada=$m82_quant;break;
     case  2:$perda  =$m82_quant;break;
     case  3:$entrada=$m82_quant;break;
     case  4:$perda  =$m82_quant;break;
     case  5:$perda  =$m82_quant;break;
     case  6:$entrada=$m82_quant;break;
     case  7:$perda  =$m82_quant;break;
     case  8:$entrada=$m82_quant;break;
     case  9:$entrada=$m82_quant;break;
     case 10:$perda  =$m82_quant;break;
     case 11:$perda  =$m82_quant;break;
     case 12:$entrada=$m82_quant;break;
     case 13:$perda  =$m82_quant;break;
     case 14:$entrada=$m82_quant;break;
     case 15:$entrada=$m82_quant;break;
     case 16:$entrada=$m82_quant;break;
     case 17:$saida  =$m82_quant;break;
     case 18:$perda  =$m82_quant;break;
     case 19:$perda  =$m82_quant;break;
   }
 	$pdf->cell(20,4,"$fa28_c_numero",1,0,"L",0);
    $pdf->cell(20,4,"$fa27_c_denominacao",1,0,"L",0);
    $pdf->cell(110,4,"".substr($m60_descr,0,20),1,0,"L",0);
    $pdf->cell(30,4,"$fa30_c_concentracao",1,0,"L",0);
    $saldoinicial=material_saldo($ini,$m60_codmater);
    if($atual==$m60_codmater){
       $saldoinicial=$voltavalor;
    }else{
      $atual=$m60_codmater;
    }
    $pdf->cell(20,4,"$saldoinicial",1,0,"R",0); // estoque inicial
   
    
    $pdf->cell(20,4,"$entrada",1,0,"R",0); //entrada aquisição
    $pdf->cell(20,4,"$saida",1,0,"R",0);        
    $pdf->cell(20,4,"$perda",1,0,"R",0);
    
    
    $saldofinal=$saldoinicial;    
    if($entrada!=""){
       $saldofinal=$saldofinal+$entrada;       
    }elseif($saida!=""){
      $saldofinal=$saldofinal-$saida;
    }else{
      $saldofinal=$saldofinal-$perda;
    }
    $pdf->cell(20,4,"$saldofinal",1,1,"R",0);
    $voltavalor=$saldofinal;
    $contador++;
 }
 $pdf->cell(30,15,"ASSINATURA DO RESPONSÁVEL TÉCNICO : $fa13_c_resptecnico",0,0,"L",0);
$pdf->Output();
?>