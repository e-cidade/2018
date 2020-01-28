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
include("libs/db_libcontabilidade.php");
include("libs/db_liborcamento.php");
include("classes/db_orcparamrel_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
$orcparamrel = new cl_orcparamrel;


$head2 = "ESTRUTURA DO RELATORIO";
$head3 = "PARAMETROS SELECIONADOS";


// $relatorio deve ser passada como parametro
// gera matris com todos os estruturais selecionados nas configurações do relatorio
$m_todos = $orcparamrel->sql_parametro($relatorio);
$virgula='';
$lista = '(';
$tt = sizeof($m_todos);
for ($x=0; $x <sizeof($m_todos);$x++){
  $lista .= $virgula."'".$m_todos[$x]."'";
  if ($x == $tt-1)  	
  $virgula ='';
  else $virgula =',';   	  
}
$lista = $lista.')';

// seleciona do plano de contas os estruturas que foram selecionados nos parametros do relatorio

 $sql = "select * from conplano 
         where c60_anousu = ".db_getsession("DB_anousu")." and c60_estrut in $lista 
	 order by c60_estrut
	 ";

 $res = pg_query($sql);
 //   echo $sql;
 //   db_criatabela($res);

 if (pg_numrows($res)>0){

    $pdf = new PDF(); 
    $pdf->Open(); 
    $pdf->AliasNbPages(); 
    $pdf->setfillcolor(235);
    $alt            = 4;
    $pagina         = 1;
    $cl = 16;  //tamanho da celula
    $tp ='B'; // tipo do contorno
    $pdf->setfont('arial','b',7);
    $pdf->addpage("L");
    $pdf->cell(30,$alt,"ESTRUTURAL",'1',0,"L",0);
    $pdf->cell(100,$alt,"DESCRIÇÂO", '1',0,"L",0);
    $pdf->cell(145,$alt,"FINALIDADE",'1',1,"L",0);

    $pdf->setfont('arial','',7);
    for($i=0;$i < pg_numrows($res);$i++){
      db_fieldsmemory($res,$i);

      $pdf->cell(30,$alt,"$c60_estrut",'1',0,"L",0);
      $pdf->cell(100,$alt,"$c60_descr", '1',0,"L",0);
      $pdf->cell(145,$alt,"$c60_finali",'1',1,"L",0);


    }
 }  


 $pdf->Output();
   
?>