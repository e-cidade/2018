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

include("fpdf151/pdfwebseller.php");
include("classes/db_trocaserie_classe.php");
include("classes/db_matricula_classe.php");
$cltrocaserie = new cl_trocaserie;
$clmatricula = new cl_matricula;
$escola = db_getsession("DB_coddepto");
$sql = "SELECT DISTINCT
         trocaserie.*,
         ed47_v_nome,
         (select ed11_c_descr
          from serie
           inner join matriculaserie on ed221_i_serie = ed11_i_codigo
           inner join matricula on ed60_i_codigo = ed221_i_matricula
          where ed60_i_turma = turmaorig.ed57_i_codigo
          and ed60_c_situacao = 'AVANÇADO'
          and ed60_i_aluno = ed47_i_codigo
          and ed221_c_origem = 'S') as nomeserieorig,
         (select ed11_c_descr
          from serie
           inner join matriculaserie on ed221_i_serie = ed11_i_codigo
           inner join matricula on ed60_i_codigo = ed221_i_matricula
          where ed60_i_turma = turmadest.ed57_i_codigo
          and ed60_c_situacao != 'AVANÇADO'
          and ed60_i_aluno = ed47_i_codigo
          and ed221_c_origem = 'S') as nomeseriedest
        FROM trocaserie
         inner join aluno on ed47_i_codigo = ed101_i_aluno
         inner join turma as turmaorig on turmaorig.ed57_i_codigo = ed101_i_turmaorig
         inner join turma as turmadest on turmadest.ed57_i_codigo = ed101_i_turmadest
        WHERE turmaorig.ed57_i_calendario = $calendario
        AND ed101_c_tipo = '$tipo'
        AND ed101_i_aluno in ($alunos)
        ORDER BY ed47_v_nome
       ";
$result = $cltrocaserie->sql_record($sql);
if($cltrocaserie->numrows==0){?>
 <table width='100%'>
  <tr>
   <td align='center'>
    <font color='#FF0000' face='arial'>
     <b>Nenhuma registro encontrado.<br>
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
$linhas = $cltrocaserie->numrows;
for($x=0;$x<$linhas;$x++){
 db_fieldsmemory($result,$x);
 $head1 = "ATA DE PROGRESSÃO DE ALUNOS";
 $pdf->setfillcolor(223);
 $pdf->addpage();
 $pdf->ln(5);
 $sql1 = $clmatricula->sql_query_file("","extract(month from ed60_d_datamatricula) as mes,extract(year from ed60_d_datamatricula) as ano",""," ed60_i_aluno = $ed101_i_aluno AND ed60_i_turma = $ed101_i_turmadest");
 $result1 = $clmatricula->sql_record($sql1);
 db_fieldsmemory($result1,0);
 if($tipo=="C"){
  $texto = "       Mediante os trabalhos que o(a) $ed47_v_nome apresentou ao chegar na escola no mês de ".db_mes($mes,1)." de $ano, lavramos a presente ata para informar à família do aluno(a) que o(a) mesmo(a) irá ser classificado(a) da etapa $nomeserieorig para a etapa $nomeseriedest.\n\n       A classificação visa a melhoria do desenvolvimento escolar do aluno, ficando a família ciente de que, no ano letivo, deverá acompanhar o rendimento do aluno, bem como incentivar a sua participação nas aulas.\n\n       $ed101_t_obs ";
 }else{
  $texto = "       Mediante os trabalhos que o(a) $ed47_v_nome apresentou durante o mês de ".db_mes($mes,1)." de $ano, lavramos a presente ata para informar à família do aluno(a) que o(a) mesmo(a) irá avançar da etapa $nomeserieorig para a etapa $nomeseriedest.\n\n       O avanço visa a melhoria do desenvolvimento escolar do aluno, ficando a família ciente de que, no ano letivo, deverá acompanhar o rendimento do aluno, bem como incentivar a sua participação nas aulas.\n\n       $ed101_t_obs ";
 }
 $pdf->setfont('arial','b',13);
 $pdf->cell(190,20,"ATA",0,1,"C",0);
 $pdf->setfont('arial','b',9);
 $pdf->multicell(190,4,$texto,0,"J",0,0);
 $pdf->cell(190,20,"",0,1,"C",0);
 $pdf->cell(190,6,"Assinatura do(a) professor(a):_____________________________________________",0,1,"L",0);
 $pdf->cell(190,6,"Assinatura do(a) responsável:_____________________________________________",0,1,"L",0);
 $pdf->cell(190,6,"Assinatura da supervisão:________________________________________________",0,1,"L",0);
 $pdf->cell(190,6,"Assinatura da direção:___________________________________________________",0,1,"L",0);
}
$pdf->Output();
?>