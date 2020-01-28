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
include("libs/db_stdlibwebseller.php");
include("classes/db_mer_restricao_classe.php");
include("classes/db_mer_restriitem_classe.php");
include("classes/db_mer_restricaointolerancia_classe.php");
include("classes/db_turma_classe.php");
include("classes/db_matricula_classe.php");
$clmer_restricao             = new cl_mer_restricao;
$clmer_restriitem            = new cl_mer_restriitem;
$clmer_restricaointolerancia = new cl_mer_restricaointolerancia;
$clturma                     = new cl_turma;
$clmatricula                 = new cl_matricula;
$escola                      = db_getsession("DB_coddepto");
$result                      = $clturma->sql_record($clturma->sql_query_turmaserie("",
                                                                                   "*",
                                                                                   "ed57_c_descr",
                                                                                   " ed220_i_codigo in ($turma)" 
                                                                                  )
                                                   );
if ($clturma->numrows == 0) {
    echo "<table width='100%'>
        <tr>
         <td align='center'><font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatório<br>
         <input type='button' value='Fechar' onclick='window.close()'></b></font></td>
        </tr>
       </table>";
   exit;
}
db_fieldsmemory($result,0); 

$sCampos  = " me25_i_codigo,me24_i_codigo,mer_restriitem.*, ";
$sCampos .= " alimento.me35_c_nomealimento as sub, ";
$sCampos .= " mer_alimento.me35_c_nomealimento,ed47_i_codigo,ed47_v_nome, ed47_d_nasc,ed47_v_sexo ";
$sOrder   = " to_ascii(ed47_v_nome)";
$result2 = $clmer_restriitem->sql_record($clmer_restriitem->sql_query("",
                                                                      $sCampos,
                                                                      $sOrder,
                                                                      "ed47_i_codigo in ($disciplinas)"
                                                                     )
                                        );
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head1 = "LISTA ALUNOS COM RESTRIÇÃO ALIMENTAR";
$head2 = "Turma: $ed57_c_descr";
$head3 = "Curso: $ed29_i_codigo - $ed29_c_descr";
$head4 = "Calendário: $ed52_c_descr";
$head5 = "Etapa: $ed11_c_descr";
$head6 = "Turno: $ed15_c_nome";
$pdf->ln(5);
$pdf->addpage('L');
$total = 0;
$cont  = 0;
$d     = 0;
$w     = 0;
$pdf->setfont('arial','b',10);
$pdf->cell(20,4,"Código ",1,0,"C",0);
$pdf->cell(80,4,"Aluno ",1,0,"C",0);
$pdf->cell(25,4,"Turma ",1,0,"C",0);
$pdf->cell(20,4,"Série ",1,0,"C",0);
$pdf->cell(65,4,"Restrição",1,0,"C",0);
$pdf->cell(65,4,"Alimento Substituto ",1,1,"C",0);
 
for ($s=0; $s < $clmer_restriitem->numrows; $s++) {
	
  db_fieldsmemory($result2,$s); 
   
 
  if ($cont == 12) {
  	
    $pdf->ln(5);
    $pdf->addpage('L');
    $pdf->setfont('arial','b',10);
    $pdf->cell(20,4,"Código ",1,0,"C",0);
    $pdf->cell(80,4,"Aluno ",1,0,"C",0);
    $pdf->cell(25,4,"Turma ",1,0,"C",0);
    $pdf->cell(60,4,"Série ",1,0,"C",0);
    $pdf->cell(65,4,"Restrição",1,0,"C",0);
    $pdf->cell(65,4,"Alimento Substituto ",1,1,"C",0);    
    $cont=0;
    $pdf->setfont('arial','',8);
    
  }    
  if ($d != $ed47_i_codigo) {  
  	
    $pdf->setfont('arial','',8);
    $pdf->setfillcolor(240);
    $pdf->cell(20,4,$ed47_i_codigo,1,0,"L",1);
    $pdf->cell(80,4,substr($ed47_v_nome,0,80),1,0,"L",1);
    $pdf->cell(25,4,$ed57_c_descr,1,0,"L",1);
    $pdf->cell(20,4,$ed11_c_descr,1,0,"L",1); 
    $pdf->cell(65,4,"",1,0,"L",1);   
    $pdf->cell(65,4,"",1,1,"L",1);      
    $d = $ed47_i_codigo; 
    $total +=1;

    $result3 = $clmer_restricaointolerancia->sql_record($clmer_restricaointolerancia->sql_query("",
                                                                                            "*",
                                                                                            "",
                                                                                            "me34_i_restricao = $me24_i_codigo"
                                                                                            )
                                                       );
                                                         
    for ($v=0; $v < $clmer_restricaointolerancia->numrows; $v++) {
        db_fieldsmemory($result3,$v);          
        $pdf->setfont('arial','b',8);  
        $pdf->cell(35,4,"Intolerância Alimentar : ",0,0,"L",0);        
        $pdf->cell(35,4,substr($me33_c_descr,0,40),0,1,"L",0);
        
    }
    
  }
  $pdf->setfont('arial','',8);
  $pdf->cell(25,4,"",0,0,"L",0);
  $pdf->cell(25,4,"",0,0,"L",0);
  $pdf->cell(25,4,"",0,0,"L",0);
  $pdf->cell(10,4,"",0,0,"L",0);  
  $pdf->cell(60,4,"",0,0,"L",0); 
  $pdf->cell(65,4,substr($me35_c_nomealimento,0,40),0,0,"L",0);
  $pdf->cell(65,4,substr($sub,0,40),0,1,"L",0);      
  $cont++;
}

$pdf->Output();
?>