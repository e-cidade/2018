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

include("fpdf151/pdfwebseller.php");
include("classes/db_atividaderh_classe.php");
$clatividaderh = new cl_atividaderh;
$escola = db_getsession("DB_coddepto");
$head2 = "Atividade(s): ";
$sep = "";
$result3 = $clatividaderh->sql_record($clatividaderh->sql_query("","ed01_c_descr","ed01_c_descr"," ed01_i_codigo in ($atividades)"));
for($c=0;$c<$clatividaderh->numrows;$c++){
 db_fieldsmemory($result3,$c);
 $head2 .= $sep.$ed01_c_descr;
 $sep = ", ";
}
$instit = db_getsession("DB_instit");
$ano = db_anofolha();
$mes = db_mesfolha();
$sql = "SELECT case when ed20_i_tiposervidor = 1 then rechumanopessoal.ed284_i_rhpessoal else rechumanocgm.ed285_i_cgm end as ed20_i_codigo,
               case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome,
               case when ed20_i_tiposervidor = 1 then cgmrh.z01_cgccpf else cgmcgm.z01_cgccpf end  as z01_cgccpf,
               ed01_c_descr,
               case when ed20_i_tiposervidor = 1
                then regimerh.rh30_descr
                else regimecgm.rh30_descr
               end as rh30_descr
        FROM rechumano
         inner join rechumanoescola on ed75_i_rechumano = ed20_i_codigo
         left join rechumanoativ on ed22_i_rechumanoescola = ed75_i_codigo
         left join atividaderh on ed01_i_codigo = ed22_i_atividade
         left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo
         left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal
         left join rhpessoalmov on rhpessoalmov.rh02_anousu  = $ano
                               and rhpessoalmov.rh02_mesusu  = $mes
                               and rhpessoalmov.rh02_regist  = rhpessoal.rh01_regist
                               and rhpessoalmov.rh02_instit  = $instit
         left join rhregime as regimerh on  regimerh.rh30_codreg = rhpessoalmov.rh02_codreg
         left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm
         left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo
         left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm
         left join rhregime as regimecgm on  regimecgm.rh30_codreg = rechumano.ed20_i_rhregime
        WHERE ed75_i_escola = $escola
        AND ed01_i_codigo in ($atividades)
        ORDER BY $ordem
       ";
$result = pg_query($sql);
//db_criatabela($result);
$linhas = pg_num_rows($result);
if($linhas==0){?>
 <table width='100%'>
  <tr>
   <td align='center'>
    <font color='#FF0000' face='arial'>
     <b>Nenhum registro encontrado.<br>
     <input type='button' value='Fechar' onclick='window.close()'></b>
    </font>
   </td>
  </tr>
 </table>
 <?
 exit;
}
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head1 = "RELATÓRIO RECURSOS HUMANOS POR ATIVIDADE";
$pdf->ln(5);
$troca = 1;
$cor1 = "0";
$cor2 = "1";
$cor = "";
$cont = 0;
for($c=0;$c<$linhas;$c++){
 db_fieldsmemory($result,$c);
 if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
  $pdf->addpage('P');
  $pdf->setfillcolor(215);
  $pdf->setfont('arial','b',8);
  $pdf->cell(25,4,"Matrícula/CGM",1,0,"C",1);
  $pdf->cell(70,4,"Nome",1,0,"L",1);
  $pdf->cell(20,4,"CPF",1,0,"C",1);
  $pdf->cell(40,4,"Atividade",1,0,"C",1);
  $pdf->cell(35,4,"Regime",1,1,"C",1);  
  $troca = 0;
 }
 if($cor==$cor1){
  $cor = $cor2;
 }else{
  $cor = $cor1;
 }
 $pdf->setfillcolor(240);
 $pdf->setfont('arial','',7);
 $pdf->cell(25,4,$ed20_i_codigo,0,0,"C",$cor);
 $pdf->cell(70,4,$z01_nome,0,0,"L",$cor);
 $pdf->cell(20,4,$z01_cgccpf,0,0,"C",$cor);
 $pdf->cell(40,4,$ed01_c_descr==""?"Não informado":$ed01_c_descr,0,0,"C",$cor);
 $pdf->cell(35,4,$rh30_descr,0,1,"C",$cor); 
 $cont++;
}
$pdf->setfont('arial','b',7);
$pdf->cell(190,5,"Total de Recursos Humanos: $cont",1,1,"L",0);
$pdf->Output();
?>