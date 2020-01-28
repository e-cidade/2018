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
include("classes/db_prontuarios_classe.php");
include("classes/db_unidades_classe.php");
include("classes/db_unidademedicos_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
set_time_limit(0);
$clunidades = new cl_unidades;
$clunidademedicos = new cl_unidademedicos;
$clprontuarios = new cl_prontuarios;
$clprontuarios->rotulo->label();
$clunidades->rotulo->label("sd02_i_codigo");
$clunidades->rotulo->label("sd02_c_nome");
$clunidades->rotulo->label("");
$unidade = str_replace("X",",",$unidades); 

 $sql_und = " SELECT  * 
                FROM unidades
               INNER JOIN db_depart on db_depart.coddepto = unidades.sd02_i_codigo
                left JOIN cgm on cgm.z01_numcgm = unidades.sd02_i_numcgm
               where sd02_i_codigo in ($unidade) 
                 and EXISTS (SELECT * 
                               FROM prontuarios
                              inner join prontproced on prontproced.sd29_i_prontuario = prontuarios.sd24_i_codigo
                              left  join prontanulado on prontuarios.sd24_i_codigo    = prontanulado.sd57_i_prontuario
                              WHERE prontuarios.sd24_i_unidade = unidades.sd02_i_codigo
                                and prontanulado.sd57_i_prontuario is null 
                                and extract(year from prontproced.sd29_d_data)=$anocomp1 
                                and extract(month from prontproced.sd29_d_data)=$mescomp1
                                and sd24_c_digitada = 'S'
                             )
                order by unidades.sd02_i_codigo";

$query_und = @db_query($sql_und) or die(pg_errormessage());
$linhas = @pg_num_rows($query_und);
if($linhas == 0){
 echo "<table width='100%'>
        <tr>
         <td align='center'><font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatório<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
        </tr>
       </table>";
 exit;
}

if((int)$pab == 1) {
  $sTipoBpa = 'PAB';
} else if((int)$pab == 2) {
  $sTipoBpa = 'NPAB';
} else {
  $sTipoBpa = 'TODOS';
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->ln(5);
$head2 = "Relatório do Boletim de Produção Ambulatorial";
$head4 = 'Tipo de BPA: '.$sTipoBpa;
$pri= true;
$limite=20;
$tot=0;
for($xx=0; $xx < $linhas; $xx++){
     db_fieldsmemory($query_und,$xx);
     if (  ($pdf->gety() > $pdf->h -30)  || $pri==true ){

          if( $pri == true ){
             $pdf->addpage();
             $pdf->setfillcolor(235);
             $pdf->setfont('arial','b',7);
             $pdf->cell(190,10,"UNIDADE(S) SELECIONADA(S)",1,1,"C",0);
             for( $x_und=0; $x_und < $linhas; $x_und++ ){
                  db_fieldsmemory($query_und,$x_und);
                  $pdf->cell(40,4,$sd02_i_codigo,1,0,"C",1);
                  $pdf->cell(150,4,$descrdepto,1,1,"C",1);

             }

          }
           $pri = false;
    }
}

//    $pdf->addpage();
//    $pdf->setfillcolor(235);
//    $head3 =$descrdepto;

$pri= true;
$abc=75;
$tot=0;
$totalgeral=0;
$limite=20;
$lim=40;
$seq=0;
$cont_geral=0;

$strBPA1 = "";
$strBPA2 = "";
if( $bpa == 0){
	$strBPA1 = "fc_idade(z01_d_nasc,sd29_d_data) as idade, ";
	$strBPA2 = ", fc_idade(z01_d_nasc,sd29_d_data) ";
}else{
	$strBPA1 = "sd63_c_nome as idade, ";
	$strBPA2 = ", sd63_c_nome ";
	
}

$strPab = '';
if($pab == '1') {
  $strPab = " and  sd65_c_financiamento='01' ";
} 
if($pab == '2') {
  $strPab = " and  sd65_c_financiamento<>'01' ";
}


if( $agrupar == 0){
	$sql_meio = "select sd63_c_procedimento, rh70_estrutural,$strBPA1 count(*)
	                   from prontproced
	
	                  inner join especmedico on especmedico.sd27_i_codigo = prontproced.sd29_i_profissional
	                  inner join unidademedicos on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed
	                  inner join rhcbo on rhcbo.rh70_sequencial =  especmedico.sd27_i_rhcbo
	
	                  inner join sau_procedimento on sau_procedimento.sd63_i_codigo = prontproced.sd29_i_procedimento
		                inner join sau_financiamento on sau_financiamento.sd65_i_codigo   = sau_procedimento.sd63_i_financiamento
	                  inner join prontuarios on prontuarios.sd24_i_codigo = prontproced.sd29_i_prontuario
	                  inner join cgs_und on cgs_und.z01_i_cgsund= prontuarios.sd24_i_numcgs
	                  inner join unidades on unidades.sd02_i_codigo = unidademedicos.sd04_i_unidade
	                  inner join db_depart on db_depart.coddepto = unidades.sd02_i_codigo
	                  where sd02_i_codigo in ($unidade)
                      $strPab
	                    and extract(year from prontproced.sd29_d_data)=$anocomp1 
	                    and extract(month from prontproced.sd29_d_data)=$mescomp1
                      and sd24_c_digitada = 'S'
	                  group by sd63_c_procedimento,
	                           rh70_estrutural
	                           $strBPA2,
	                           extract(year from prontproced.sd29_d_data),
	                           extract(month from prontproced.sd29_d_data)
	                  order by sd63_c_procedimento, rh70_estrutural $strBPA2";	
}else if( $agrupar == 1 ){
	$sql_meio = "select coddepto,descrdepto,sd04_i_unidade,sd63_c_procedimento, 
						rh70_estrutural, $strBPA1 count(*)
	                   from prontproced
	
	                  inner join especmedico on especmedico.sd27_i_codigo = prontproced.sd29_i_profissional
	                  inner join unidademedicos on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed
	                  inner join rhcbo on rhcbo.rh70_sequencial =  especmedico.sd27_i_rhcbo
	
	                  inner join sau_procedimento on sau_procedimento.sd63_i_codigo = prontproced.sd29_i_procedimento
		                inner join sau_financiamento on sau_financiamento.sd65_i_codigo   = sau_procedimento.sd63_i_financiamento
	                  inner join prontuarios on prontuarios.sd24_i_codigo = prontproced.sd29_i_prontuario
	                  inner join cgs_und on cgs_und.z01_i_cgsund= prontuarios.sd24_i_numcgs
	                  inner join unidades on unidades.sd02_i_codigo = unidademedicos.sd04_i_unidade
	                  inner join db_depart on db_depart.coddepto = unidades.sd02_i_codigo
	                  where sd02_i_codigo in ($unidade)
                      $strPab
	                    and extract(year from prontproced.sd29_d_data)=$anocomp1 
	                    and extract(month from prontproced.sd29_d_data)=$mescomp1
                      and sd24_c_digitada = 'S'
	                  group by coddepto,
	                           descrdepto,
	                           sd04_i_unidade,
	                           sd63_c_procedimento,
	                           rh70_estrutural
	                           $strBPA2,
	                           extract(year from prontproced.sd29_d_data),
	                           extract(month from prontproced.sd29_d_data)
	                  order by sd04_i_unidade,sd63_c_procedimento, rh70_estrutural $strBPA2";
}             
//echo "<BR> $sql_meio"; exit;
$query_meio = db_query($sql_meio);
$linhas_meio = pg_num_rows($query_meio);
$primeiro=0; //pg_result($query_meio,0,"sd04_i_unidade");

   for($u=0; $u < $linhas_meio; $u++){
     db_fieldsmemory($query_meio,$u);
     if (  ($pdf->gety() > $pdf->h -30)  || $pri==true ){
     	     $head3 = $agrupar == 1 ? $coddepto." - ".$descrdepto : "";
     	     $pdf->addpage();
             $pdf->setfillcolor(235);
             $pdf->setfont('arial','b',7);
     }
     
     
     if( isset( $sd04_i_unidade ) && $primeiro!=$sd04_i_unidade){
        if( $u != 0 ){
          $pdf->setY(245);
          $pdf->setX(162);
          $pdf->cell(20,4,"Total",1,1,"C",0);
          $pdf->setY(245);
          $pdf->setX(182);
          $pdf->cell(20,4,$tot,1,1,"C",0);
          $totalgeral += $tot;
          $tot=0;
          $seq=0;
          $abc=75;
         	//rodape primeiro retangulo
          $x5=10; //largura
          $y5=255; //altura
          $pdf->Rect($x5,$y5,95,30,$style='');
          $pdf->setY(255);
          $pdf->cell(30,4,"RESPONSAVEL(Estabelecimento de Saude)","L",0,"L",0);
          $pdf->setY(258);
          $pdf->setX(10);
          $pdf->cell(15,4,"Carimbo",0,0,0,0);
          $pdf->setY(258);
          $pdf->setX(40);
          $pdf->cell(50,4,"Rubrica",0,0,0,0);
          $pdf->setY(268);
          $pdf->setX(10);
          $pdf->cell(10,4,"Data",0,0,0,0);
          $pdf->setY(272);
          $pdf->setX(10);
          $pdf->cell(20,4,date("d/m/Y"),0,0,0,0);
           ///////////////////////////////////////
          //segundo retangulo rodape
          $x6=110;  //largura
          $y6=255; //altura
          $pdf->Rect($x6,$y6,95,30,$style='');
          $pdf->setY(255);
          $pdf->setX(110);
          $pdf->cell(48,4,"GESTOR MUNICIPAL / ESTADUAL",0,0,0,0);
          $pdf->setY(258);
          $pdf->setX(110);
          $pdf->cell(15,4,"Carimbo",0,0,0,0);
          $pdf->setY(258);
          $pdf->setX(140);
          $pdf->cell(50,4,"Rubrica",0,0,0,0);
          $pdf->setY(268);
          $pdf->setX(110);
          $pdf->cell(10,4,"Data",0,0,0,0);
          $pdf->setY(272);
          $pdf->setX(110);
          $pdf->cell(20,4,date("d/m/Y"),0,0,0,0);
          $head3 = $coddepto." - ".$descrdepto;
          $pdf->addpage();
        }
        
        
     }
     if( $pri == true  || (isset( $sd04_i_unidade ) && $primeiro!=$sd04_i_unidade) ){
        $pri=false;
        $primeiro=@$sd04_i_unidade;
        
        if( $agrupar == 1 ){      
	        $sql_und1 = " SELECT  * 
	                      FROM unidades
	                     INNER JOIN db_depart on db_depart.coddepto = unidades.sd02_i_codigo
	                      left JOIN cgm on cgm.z01_numcgm = unidades.sd02_i_numcgm
	                     where unidades.sd02_i_codigo = $sd04_i_unidade ";
	        $query_und1 = @db_query($sql_und1) or die(pg_errormessage());
	        db_fieldsmemory($query_und1,0);
        }


        $x=10;
        $y=35;
        $pdf->Rect(10,35,95,10,$style='');
        $pdf->setX(10);
        $pdf->cell(30,4,"CNES:","L",0,"L",0);
        $pdf->setX(10);
        $pdf->setY(40);
        $pdf->cell(30,4,@$sd02_v_cnes,"L",0,"L",0);
        ///////////////////////////////////////
        //segundo retangulo cabecalho
        $x1=10+100;  //largura
        $y1=35; //altura
        $pdf->Rect($x1,$y1,95,10,$style='');
        $pdf->setY(35);
        $pdf->setX(110);
        $pdf->cell(30,4,"NOME ESTABELECIMENTO:","L",0,"L",0);
        $pdf->setY(40);
        $pdf->setX(110);
        if( $agrupar == 1 ){
           $pdf->cell(30,4,@$coddepto." - ".@$descrdepto ,"L",0,"L",0);
        }
        /////////////////////////////////
        //terceiro retangulo cabecalho
        $x2=10;  //largura
        $y2=35+15; //altura
        $pdf->Rect($x2,$y2,65,10,$style='');
        $pdf->setY(50);
        $pdf->setX(10);
        $pdf->cell(30,4,"UF:","L",0,"L",0);
        $pdf->setY(55);
        $pdf->setX(10);
        $pdf->cell(30,4,$z01_uf,"L",0,"L",0);
        /////////////////////////////////
         //quarto retangulo cabecalho
        $x3=10+70;  //largura
        $y3=35+15; //altura
        $pdf->Rect($x3,$y3,65,10,$style='');
        $pdf->setY(50);
        $pdf->setX(82);
        $pdf->cell(21,4,"COMPETENCIA:",0,0,0,0);
        $pdf->setY(55);
        $pdf->setX(80);
        $pdf->cell(12,4,$anocomp1."/".$mescomp1,0,0,0,0);
        ////////////////////////////////
        //quinto retangulo cabecalho
        $x4=80+70;  //largura
        $y4=35+15; //altura
        $pdf->Rect($x4,$y4,55,10,$style='');
        $pdf->setY(50);
        $pdf->setX(150);
        $pdf->cell(12,4,"FOLHA:",0,0,0,0);
        $pdf->setY(55);
        $pdf->setX(150);
        $pdf->cell(12,4,$pdf->PageNo(),0,0,0,0);
        $xmeio=10;  //largura
        $ymeio=65; //altura
        $pdf->Rect($xmeio,$ymeio,195,185,$style='');
        $pdf->setY(65);
        $pdf->setX(10);
        $pdf->setfont('arial','b',8);
        $pdf->cell(195,4,"Atendimento Realizado",1,1,"C",0);
	    $pdf->setY(70);
	    $pdf->setX(10);
	    if( $bpa == 0 ){
		    $pdf->cell(38,4,"Sequencia",1,1,"C",0);
		    $pdf->setY(70);
		    $pdf->setX(48);
		    $pdf->cell(38,4,"Procedimento",1,1,"C",0);
		    $pdf->setY(70);
		    $pdf->setX(86);
	    	$pdf->cell(38,4,"CBO",1,1,"C",0);
		    $pdf->setY(70);
		    $pdf->setX(124);
		    $pdf->cell(38,4,($bpa == 0?"Idade":""),1,1,"C",0);
		    $pdf->setY(70);
		    $pdf->setX(162);
		    $pdf->cell(38,4,"Quantidade",1,1,"C",0);
	    }else{
		    $pdf->cell(18,4,"Sequencia",1,0,"C",0);
		    $pdf->cell(18,4,"Procedimento",1,0,"C",0);
	    	$pdf->cell(18,4,"CBO",1,0,"C",0);
		    $pdf->cell(118,4,"Descrição",1,0,"C",0);
		    $pdf->cell(18,4,"Quantidade",1,1,"C",0);
	    }
        
        $primeiro=@$sd04_i_unidade;
        
        
     } // fim primeiro != $sd04_i_unidade
	if( $bpa == 0){
	    $pdf->setY($abc);
	    $pdf->setX(10);
	    $pdf->cell(38,4,$seq+1,1,1,"C",0);
	    $pdf->setY($abc);
	    $pdf->setX(48);
	    $pdf->cell(38,4,$sd63_c_procedimento,1,1,"C",0);
	    $pdf->setY($abc);
	    $pdf->setX(86);
	    $pdf->cell(38,4,$rh70_estrutural,1,1,"C",0);
	    $pdf->setY($abc);
	    $pdf->setX(124);
	    $pdf->cell(38,4,$idade,1,1,"C",0);
	    $pdf->setY($abc);
	    $pdf->setX(162);
	    $pdf->cell(38,4,$count,1,1,"C",0);
	}else{
	    $pdf->cell(18,4,$seq+1,1,0,"C",0);
	    $pdf->cell(18,4,$sd63_c_procedimento,1,0,"C",0);
	    $pdf->cell(18,4,$rh70_estrutural,1,0,"C",0);
	    $pdf->setfont('arial','',5);	    
	    $pdf->cell(118,4,$idade,1,0,"L",0);
        $pdf->setfont('arial','b',8);
	    $pdf->cell(18,4,$count,1,1,"C",0);
	}
    $abc+=4;
    $tot+=$count;
    $seq++;
    
    if($lim==$seq){
	   	$pdf->setY(245);
	    $pdf->setX(162);
	    $pdf->cell(20,4,"Total",1,1,"C",0);
	    $pdf->setY(245);
	    $pdf->setX(182);
	    $pdf->cell(20,4,$tot,1,1,"C",0);
	    $totalgeral += $tot;
	    $tot=0;
	    $seq=0;
	    $abc=75;
	   	//rodape primeiro retangulo
	    $x5=10; //largura
	    $y5=255; //altura
	    $pdf->Rect($x5,$y5,95,30,$style='');
	    $pdf->setY(255);
	    $pdf->cell(30,4,"RESPONSAVEL(Estabelecimento de Saude)","L",0,"L",0);
	    $pdf->setY(258);
	    $pdf->setX(10);
	    $pdf->cell(15,4,"Carimbo",0,0,0,0);
	    $pdf->setY(258);
	    $pdf->setX(40);
	    $pdf->cell(50,4,"Rubrica",0,0,0,0);
	    $pdf->setY(268);
	    $pdf->setX(10);
	    $pdf->cell(10,4,"Data",0,0,0,0);
	    $pdf->setY(272);
	    $pdf->setX(10);
	    $pdf->cell(20,4,date("d/m/Y"),0,0,0,0);
	     ///////////////////////////////////////
	    //segundo retangulo rodape
	    $x6=110;  //largura
	    $y6=255; //altura
	    $pdf->Rect($x6,$y6,95,30,$style='');
	    $pdf->setY(255);
	    $pdf->setX(110);
	    $pdf->cell(48,4,"GESTOR MUNICIPAL / ESTADUAL",0,0,0,0);
	    $pdf->setY(258);
	    $pdf->setX(110);
	    $pdf->cell(15,4,"Carimbo",0,0,0,0);
	    $pdf->setY(258);
	    $pdf->setX(140);
	    $pdf->cell(50,4,"Rubrica",0,0,0,0);
	    $pdf->setY(268);
	    $pdf->setX(110);
	    $pdf->cell(10,4,"Data",0,0,0,0);
	    $pdf->setY(272);
	    $pdf->setX(110);
	    $pdf->cell(20,4,date("d/m/Y"),0,0,0,0);
	    $pdf->addpage();
	    $x=10;
	    $y=35;
	    $pdf->Rect(10,35,95,10,$style='');
	    $pdf->setX(10);
	    $pdf->cell(30,4,"CNES:","L",0,"L",0);
	    $pdf->setX(10);
	    $pdf->setY(40);
	    $pdf->cell(30,4,$sd02_v_cnes,"L",0,"L",0);
	    ///////////////////////////////////////
	    //segundo retangulo cabecalho
	    $x1=10+100;  //largura
	    $y1=35; //altura
	    $pdf->Rect($x1,$y1,95,10,$style='');
	    $pdf->setY(35);
	    $pdf->setX(110);
	    $pdf->cell(30,4,"NOME ESTABELECIMENTO:","L",0,"L",0);
	    $pdf->setY(40);
	    $pdf->setX(110);
	    //$pdf->cell(30,4,$descrdepto ,"L",0,"L",0);
	    if( $agrupar == 1 ){
	       $pdf->cell(30,4,@$coddepto." - ".@$descrdepto ,"L",0,"L",0);
	    }
	    /////////////////////////////////
	    //terceiro retangulo cabecalho
	    $x2=10;  //largura
	    $y2=35+15; //altura
	    $pdf->Rect($x2,$y2,65,10,$style='');
	    $pdf->setY(50);
	    $pdf->setX(10);
	    $pdf->cell(30,4,"UF:","L",0,"L",0);
	    $pdf->setY(55);
	    $pdf->setX(10);
	    $pdf->cell(30,4,$z01_uf,"L",0,"L",0);
	    /////////////////////////////////
	     //quarto retangulo cabecalho
	    $x3=10+70;  //largura
	    $y3=35+15; //altura
	    $pdf->Rect($x3,$y3,65,10,$style='');
	    $pdf->setY(50);
	    $pdf->setX(82);
	    $pdf->cell(21,4,"COMPETENCIA:",0,0,0,0);
	    $pdf->setY(55);
	    $pdf->setX(80);
	    $pdf->cell(12,4,$anocomp1."/".$mescomp1,0,0,0,0);
	    ////////////////////////////////
	    //quinto retangulo cabecalho
	    $x4=80+70;  //largura
	    $y4=35+15; //altura
	    $pdf->Rect($x4,$y4,55,10,$style='');
	    $pdf->setY(50);
	    $pdf->setX(150);
	    $pdf->cell(12,4,"FOLHA:",0,0,0,0);
	    $pdf->setY(55);
	    $pdf->setX(150);
	    $pdf->cell(12,4,$pdf->PageNo(),0,0,0,0);
	    $xmeio=10;  //largura
	    $ymeio=65; //altura
	    $pdf->Rect($xmeio,$ymeio,195,185,$style='');
	    $pdf->setY(65);
	    $pdf->setX(10);
	    $pdf->setfont('arial','b',8);
	    $pdf->cell(195,4,"Atendimento Realizado",1,1,"C",0);
	    $pdf->setY(70);
	    $pdf->setX(10);
	    $pdf->cell(38,4,"Sequencia",1,1,"C",0);
	    $pdf->setY(70);
	    $pdf->setX(48);
	    $pdf->cell(38,4,"Procedimento",1,1,"C",0);
	    $pdf->setY(70);
	    $pdf->setX(86);
	    $pdf->cell(38,4,"CBO",1,1,"C",0);
	    $pdf->setY(70);
	    $pdf->setX(124);
	    $pdf->cell(38,4,($bpa == 0?"Idade":""),1,1,"C",0);
	    $pdf->setY(70);
	    $pdf->setX(162);
	    $pdf->cell(38,4,"Quantidade",1,1,"C",0);
    }

 }
$pdf->setY(240);
$pdf->setX(162);
$pdf->cell(20,4,"Total",1,1,"C",0);
$pdf->setY(240);
$pdf->setX(182);
$pdf->cell(20,4,$tot,1,1,"C",0);

$pdf->setY(245);
$pdf->setX(162);
$pdf->cell(20,4,"Total Geral",1,1,"C",0);
$pdf->setY(245);
$pdf->setX(182);
$pdf->cell(20,4,$totalgeral+$tot,1,1,"C",0);

/////////////////////////////////

//rodape primeiro retangulo
$x5=10; //largura
$y5=255; //altura
$pdf->Rect($x5,$y5,95,30,$style='');
$pdf->setY(255);
$pdf->cell(30,4,"RESPONSAVEL(Estabelecimento de Saude)","L",0,"L",0);
$pdf->setY(258);
$pdf->setX(10);
$pdf->cell(15,4,"Carimbo",0,0,0,0);
$pdf->setY(258);
$pdf->setX(40);
$pdf->cell(50,4,"Rubrica",0,0,0,0);
$pdf->setY(268);
$pdf->setX(10);
$pdf->cell(10,4,"Data",0,0,0,0);
$pdf->setY(272);
$pdf->setX(10);
$pdf->cell(20,4,date("d/m/Y"),0,0,0,0);
///////////////////////////////////////
//segundo retangulo rodape
$x6=110;  //largura
$y6=255; //altura
$pdf->Rect($x6,$y6,95,30,$style='');
$pdf->setY(255);
$pdf->setX(110);
$pdf->cell(48,4,"GESTOR MUNICIPAL / ESTADUAL",0,0,0,0);
$pdf->setY(258);
$pdf->setX(110);
$pdf->cell(15,4,"Carimbo",0,0,0,0);
$pdf->setY(258);
$pdf->setX(140);
$pdf->cell(50,4,"Rubrica",0,0,0,0);
$pdf->setY(268);
$pdf->setX(110);
$pdf->cell(10,4,"Data",0,0,0,0);
$pdf->setY(272);
$pdf->setX(110);
$pdf->cell(20,4,date("d/m/Y"),0,0,0,0);


$pdf->Output();
?>