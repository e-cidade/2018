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
include("classes/db_acervo_classe.php");
include("classes/db_tipoitem_classe.php");
include("classes/db_editora_classe.php");
include("classes/db_classiliteraria_classe.php");
include("classes/db_localizacao_classe.php");
include("classes/db_aquisicao_classe.php");
include("classes/db_biblioteca_classe.php");
$clbiblioteca = new cl_biblioteca;
$clacervo = new cl_acervo;
$cltipoitem = new cl_tipoitem;
$depto = db_getsession("DB_coddepto");
$result = $clbiblioteca->sql_record($clbiblioteca->sql_query("","bi17_codigo,bi17_nome",""," bi17_coddepto = $depto"));
if($clbiblioteca->numrows!=0){
 db_fieldsmemory($result,0);
}
if(@$tipo!=""){
 $where = " and bi06_tipoitem = $tipo";
 $db = $cltipoitem->sql_record($cltipoitem->sql_query("","bi05_nome",""," bi05_codigo = $tipo"));
 db_fieldsmemory($db,0);
 $head3 = "Tipo Ítem: $bi05_nome\n";
}else{
 $where = "";
 $head3 = "Tipo Ítem: TODOS";
}
$sql = "SELECT bi06_seq,bi06_titulo,bi06_edicao,bi06_volume,bi06_tipoitem,bi05_nome,count(*)
        FROM acervo
         inner join exemplar on bi23_acervo = bi06_seq
         inner join emprestimoacervo on bi19_exemplar = bi23_codigo
         inner join emprestimo on bi18_codigo = bi19_emprestimo
         inner join tipoitem on bi05_codigo = bi06_tipoitem
        WHERE exists(select * from emprestimoacervo where bi19_exemplar = bi23_codigo)
        AND bi06_biblioteca = $bi17_codigo
        AND bi18_retirada between '$data_ini' and '$data_fim'
        $where
        GROUP BY bi06_seq,bi06_titulo,bi06_edicao,bi06_volume,bi06_tipoitem,bi05_nome
        ORDER BY count desc
        LIMIT $quant";
@$result = pg_exec($sql);
@$linhas = pg_numrows($result);
//db_criatabela($result);
//exit;
if($linhas==0){?>
 <table width='100%'>
  <tr>
   <td align='center'>
    <font color='#FF0000' face='arial'>
     <b>Nenhum Registro para o Relatório<br>
     <input type='button' value='Fechar' onclick='window.close()'></b>
    </font>
   </td>
  </tr>
 </table>
 <?
 exit;
}
$head1 = "RELATÓRIO DE ÍTENS MAIS EMPRESTADOS";
$head2 = "PERÍODO: ".db_formatar($data_ini,'d')." à ".db_formatar($data_fim,'d');
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$troca = 1;
$cor1 = "0";
$cor2 = "1";
$cor = "";
for($x=0;$x<$linhas;$x++){
 db_fieldsmemory($result,$x);
 $pdf->setfillcolor(215);
 if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
  $pdf->addpage('P');
  $pdf->setfont('arial','b',8);
  $pdf->cell(25,4,"Código Acervo",1,0,"C",1);
  $pdf->cell(85,4,"Titulo",1,0,"L",1);
  $pdf->cell(20,4,"Edição",1,0,"C",1);
  $pdf->cell(20,4,"Volume",1,0,"C",1);
  $pdf->cell(20,4,"Tipo",1,0,"C",1);
  $pdf->cell(20,4,"Quantidade",1,1,"C",1);
  $troca = 0;
 }
 if($cor==$cor1){
  $cor = $cor2;
 }else{
  $cor = $cor1;
 }
 $pdf->setfillcolor(240);
 $pdf->setfont('arial','',7);
 $pdf->cell(25,4,$bi06_seq,0,0,"C",$cor);
 $pdf->cell(85,4,$bi06_titulo,0,0,"L",$cor);
 $pdf->cell(20,4,$bi06_edicao,0,0,"C",$cor);
 $pdf->cell(20,4,$bi06_volume,0,0,"C",$cor);
 $pdf->cell(20,4,$bi05_nome,0,0,"C",$cor);
 $pdf->cell(20,4,$count,0,1,"C",$cor);
}
$pdf->Output();
?>