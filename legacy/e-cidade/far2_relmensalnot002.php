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
//include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_far_farmacia_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_db_config_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
$clfar_farmacia = new cl_far_farmacia;
$cldb_depart = new cl_db_depart;
$cldb_config = new cl_db_config;
$clrotulo = new rotulocampo;
$depto = db_getsession("DB_coddepto");
$nomedepto = db_getsession("DB_nomedepto");
$exerc=date('Y');
$datar= date('d/m/y');

$dataini="$ano-$mes-01";
$datafim=date("Y-m-t", mktime(0, 0, 0, $mes, 1, $ano));
 
$sqlfarmacia = "select distinct on(m60_codmater) m60_codmater,fa28_c_numero,fa27_c_denominacao,m60_descr,fa30_c_concentracao,fa04_i_tiporeceita,fa04_d_data,z01_nome,sd03_i_crm,fa06_f_quant,fa10_i_quantidade from far_retiradaitens
inner join far_retirada on far_retirada.fa04_i_codigo =far_retiradaitens.fa06_i_retirada
inner join far_matersaude on far_matersaude.fa01_i_codigo = far_retiradaitens.fa06_i_matersaude
inner join matmater on matmater.m60_codmater = far_matersaude.fa01_i_codmater
left join far_medanvisa on far_medanvisa.fa14_i_codigo = far_matersaude.fa01_i_medanvisa
left join far_codigodcb on far_codigodcb.fa28_i_medanvisa = far_medanvisa.fa14_i_codigo
left join far_tipodc on far_tipodc.fa27_i_codigo= far_codigodcb.fa28_i_tipodcb
left join medicos  on  medicos.sd03_i_codigo = far_retirada.fa04_i_profissional
left join cgm  on  cgm.z01_numcgm = medicos.sd03_i_cgm
left join far_controlemed on far_retiradaitens.fa06_i_matersaude =far_controlemed.fa10_i_medicamento 
left join far_tiporeceita on far_tiporeceita.fa03_i_codigo= far_retirada.fa04_i_tiporeceita
left join far_listacontroladomed on far_listacontroladomed.fa35_i_medanvisa = far_medanvisa.fa14_i_codigo
left join far_listacontrolado on far_listacontrolado.fa15_i_codigo=far_listacontroladomed.fa35_i_listacontrolado
left join far_concentracaomed on far_concentracaomed.fa37_i_medanvisa= far_medanvisa.fa14_i_codigo
left join far_concentracao on far_concentracao.fa30_i_codigo = far_concentracaomed.fa37_i_concentracao";
$sqlfarmacia .=" where fa04_d_data between '$dataini' and '$datafim'";
$sqlfarmacia .=" and (trim(upper(substr(fa15_c_listacontrolado,0,9)))='LISTA A1'
                 or trim(upper(substr(fa15_c_listacontrolado,0,9)))='LISTA A2' 
                 or trim(upper(substr(fa15_c_listacontrolado,0,9)))='LISTA A3')";

$resultfarmacia = $clfar_farmacia->sql_record($sqlfarmacia);

//die($sqlfarmacia);

if($clfar_farmacia->numrows == 0){
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
   //die($clfar_farmacia->sql_query(null,"*","","fa13_i_departamento=$depto"));
  $resultfar = $clfar_farmacia->sql_record($clfar_farmacia->sql_query(null,"*","","fa13_i_departamento=$depto"));
   if($clfar_farmacia->numrows > 0){
	db_fieldsmemory($resultfar,0);
   }
   
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
//$head1 = "RELAÇÃO MENSAL DE NOTIFICAÇÃO";
//$head2 = "";
$contador=0;
 $pdf->ln(5);
 $pdf->addpage('L');
 $total=0;
 $cont=0;
 $pdf->setfont('arial','b',6);
 $pdf->rect( 10, 36, 63, 36, "D");
 $pdf->cell(50,35,"Carimbo do C.N.P.J.",0,1,"C",0);
 $pdf->setY(35);
 $pdf->setX(35);
 $pdf->setfont('arial','b',8);
 $pdf->cell(280,5,"SECRETARIA DE SAÚDE: $nomedepto",0,1,"C",0);
 $pdf->setY(40);
 $pdf->setX(30);
 $pdf->cell(280,5,"AUTORIDADE SANITÁRIA: $fa13_c_autosanitaria",0,1,"C",0);
 $pdf->setY(45);
 $pdf->setX(103);
 $pdf->setfont('arial','b',12);
 $pdf->cell(280,6,"RELAÇÃO MENSAL DE NOTIFICAÇÕES DE RECEITAS 'A' (RMNRA) ",0,0,"L",0);
 $pdf->setY(50);
 $pdf->setX(120);
 $pdf->setfont('arial','',12);
 $pdf->cell(280,10,"NÚMERO DA LICENÇA DE FUNCIONAMENTO  $fa13_c_numlicenca",0,0,"L",0);
 $pdf->setY(75);
 $pdf->setfont('arial','b',6);
 $pdf->cell(100,5,"NOME DO ESTABELECIMENTO :  $nomedepto",0,0,"L",0);
 $pdf->cell(160,5,"",0,0,"L",0);
 $pdf->cell(50,5,"EXERCÍCIO : $ano",0,1,"L",0);
 $pdf->cell(100,5,"ENDEREÇO : $z01_ender ",0,0,"L",0);
 $pdf->cell(160,5,"",0,0,"L",0);
 $pdf->cell(50,5,"MÊS :  $nome",0,1,"L",0);
 $pdf->cell(100,8,"NOME DO FARMACÊUTICO RESPONSÁVEL E C.R.F. :$nomeresponsavel - $fa13_c_crf",0,1,"L",0);      
 $pdf->setfillcolor(240);
 $pdf->cell(15,4,"Código",1,0,"C",1);///1
 $pdf->cell(15,4,"Descrição",1,0,"C",1);///2
 $pdf->cell(80,4,"Nome do ",1,0,"C",1);///3
 $pdf->cell(30,4,"Apresentação e ",1,0,"C",1);///4
 $pdf->cell(25,4,"N° Notificação ",1,0,"C",1);///5
 $pdf->cell(15,4,"Data da ",1,0,"C",1);///6
 $pdf->cell(50,4,"Nome do ",1,0,"C",1);///7
 $pdf->cell(20,4,"N° do CR do ",1,0,"C",1);///8
 $pdf->cell(15,4,"Quantidade ",1,0,"C",1);///9
 $pdf->cell(15,4,"Quantidade ",1,1,"C",1);///10
 $pdf->cell(15,4,"DCB","BL",0,"C",1);//1
 $pdf->cell(15,4,"DCB","BL",0,"C",1);//2
 $pdf->cell(80,4,"Medicamento","BL",0,"C",1);//3
 $pdf->cell(30,4,"Concentração","BL",0,"C",1);//4
 $pdf->cell(25,4,"de Receita 'A'(NRA)","BL",0,"C",1);//5
 $pdf->cell(15,4,"NRA","BL",0,"C",1);//6
 $pdf->cell(50,4,"Prescritor","BL",0,"C",1);//7
 $pdf->cell(20,4,"Prescritor","BL",0,"C",1);//8
 $pdf->cell(15,4,"Prescrita","BL",0,"C",1);//9
 $pdf->cell(15,4,"Dispensada","BLR",1,"C",1);//10
 for($i=0; $i<pg_num_rows($resultfarmacia); $i++){
     db_fieldsmemory($resultfarmacia,$i);
     if($contador==32){
	   $pdf->ln(5);
       $pdf->addpage('L');
       $pdf->setfillcolor(240);
	   $pdf->cell(15,4,"Código",1,0,"C",1);///1
       $pdf->cell(15,4,"Descrição",1,0,"C",1);///2
       $pdf->cell(80,4,"Nome do ",1,0,"C",1);///3
       $pdf->cell(30,4,"Apresentação e ",1,0,"C",1);///4
       $pdf->cell(25,4,"N° Notificação ",1,0,"C",1);///5
       $pdf->cell(15,4,"Data da ",1,0,"C",1);///6
       $pdf->cell(50,4,"Nome do ",1,0,"C",1);///7
       $pdf->cell(20,4,"N° do CR do ",1,0,"C",1);///8
       $pdf->cell(15,4,"Quantidade ",1,0,"C",1);///9
       $pdf->cell(15,4,"Quantidade ",1,1,"C",1);///10
       $pdf->cell(15,4,"DCB","BL",0,"C",1);//1
       $pdf->cell(15,4,"DCB","BL",0,"C",1);//2
       $pdf->cell(80,4,"Medicamento","BL",0,"C",1);//3
       $pdf->cell(30,4,"Concentração","BL",0,"C",1);//4
       $pdf->cell(25,4,"de Receita 'A'(NRA)","BL",0,"C",1);//5
       $pdf->cell(15,4,"NRA","BL",0,"C",1);//6
       $pdf->cell(50,4,"Prescritor","BL",0,"C",1);//7
       $pdf->cell(20,4,"Prescritor","BL",0,"C",1);//8
       $pdf->cell(15,4,"Prescrita","BL",0,"C",1);//9
       $pdf->cell(15,4,"Dispensada","BLR",1,"C",1);//10
	   $contador=0;
	  } 
     $pdf->cell(15,4,"$fa28_c_numero",1,0,"L",0);
     $pdf->cell(15,4,"$fa27_c_denominacao",1,0,"L",0);
     $pdf->cell(80,4,"$m60_descr",1,0,"L",0);
     $pdf->cell(30,4,"$fa30_c_concentracao",1,0,"L",0);
     $pdf->cell(25,4,"$fa04_i_tiporeceita",1,0,"R",0);
     $pdf->cell(15,4,db_formatar($fa04_d_data,'d'),1,0,"R",0);
     $pdf->cell(50,4,"$z01_nome",1,0,"L",0);
     $pdf->cell(20,4,"$sd03_i_crm",1,0,"R",0);
     if($fa10_i_quantidade=null){
        $pdf->cell(15,4,"$fa10_i_quantidade",1,0,"R",0);
     }else{
        $pdf->cell(15,4,"$fa06_f_quant",1,0,"R",0);
     }
     $pdf->cell(15,4,"$fa06_f_quant",1,1,"R",0);
  }
  $pdf->setfont('arial','b',6);
  $pdf->cell(100,10,"ASSINATURA DO RESPONSÁVEL TÉCNICO: $fa13_c_resptecnico",0,1,"L",0);
  $pdf->cell(70,7,"RECEBIDO POR :",0,0,"L",0);
  $pdf->cell(80,7,"RG : ",0,0,"L",0);
  $pdf->cell(90,7,"ÓRGÃO/SETOR :",0,0,"L",0);
  $pdf->cell(110,7,"DATA :",0,1,"L",0); 
  $pdf->cell(70,7,"CONFERIDO POR: ",0,0,"L",0);
  $pdf->cell(80,7,"RG :",0,0,"L",0);
  $pdf->cell(90,7,"ÓRGÃO/SETOR : ",0,0,"L",0);
  $pdf->cell(110,7,"DATA :",0,1,"L",0); 
  $pdf->Output();
?>