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
$clrotulo = new rotulocampo;
$clrotulo->label('rh61_regist');
$clrotulo->label('z01_nome');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_GET_VARS);
$orderby = " rh30_codreg, z01_nome ";
$ordenacao = "Alfabética";
if(isset($ordem) && $ordem == "n"){
  $ordenacao = "Numérico";
  $orderby = " rh30_codreg, rh01_regist ";
}

$where = " rh05_recis is null ";

if(isset($ano) && trim($ano) != ""){
  $where .= " and rh02_anousu = " . $ano;
}

if(isset($mes) && trim($mes) != ""){
  $where .= " and rh02_mesusu = " . $mes;
}

if(isset($selec) && $selec != ''){
  $where .= " and rh02_codreg in (".$selec.") ";
}

if($sel != 0){
  $result_sel = db_query("select r44_where from selecao where r44_selec = {$sel} and r44_instit = " . db_getsession("DB_instit"));
  if(pg_numrows($result_sel) > 0){
    db_fieldsmemory($result_sel, 0, 1);
    $where .= " and ".$r44_where;
  }
}
if($sinana == 'a'){
  $campos = "rh01_regist as matricula,
             z01_nome as nome,
             rh01_admiss,
             rh30_codreg,
             rh30_descr ,
             rh37_descr as funcao,
             rh03_padrao as padrao,
             substr(db_fxxx(rh02_regist, rh02_anousu, rh02_mesusu,".db_getsession("DB_instit")."),111,11) as salario_base";
  $grupo  = "";
}else{
  $orderby = " rh30_codreg, rh30_descr ";
  $campos  = " rh30_codreg,
               rh30_descr ,
               count(*) as total ";
  $grupo = " group by rh30_codreg, rh30_descr ";
}

$head3 = "RELATÓRIO POR TIPO DE VINCULO";
$head4 = "ORDEM: ".$ordenacao;

////////////////////////////////////////////////

//// Cria a variável $sql com o sql criado
$sql = "select $campos 
            from rhpessoal
       inner join cgm           on     rhpessoal.rh01_numcgm = cgm.z01_numcgm
       inner join rhpessoalmov  on     rhpessoal.rh01_regist = rhpessoalmov.rh02_regist and rh02_instit = ".db_getsession("DB_instit")." 
       inner join rhregime      on      rhregime.rh30_codreg = rhpessoalmov.rh02_codreg
                               and     rh30_instit = rh02_instit
       inner join rhfuncao      on     rhpessoal.rh01_funcao = rhfuncao.rh37_funcao and rh37_instit = ".db_getsession("DB_instit")." 
       left  join rhpespadrao   on   rhpespadrao.rh03_seqpes = rh02_seqpes
                                             and rh03_anousu = rh02_anousu
                                             and rh03_mesusu = rh02_mesusu
       left join rhpesrescisao on rh02_seqpes = rh05_seqpes
            where $where
            $grupo
            order by ".$orderby;
$resultado_sql  = db_query($sql);
$qtd_linhas_sql = pg_numrows($resultado_sql);
if($qtd_linhas_sql == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem funcionários cadastrados no período.');
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);

if ($sinana == 'a') {

  $imprime_cabecalho = true;
  $alt               = 4;
  $quebra            = 0;
  $total_fun         = 0;
  $total             = 0;

  for($x=0; $x<$qtd_linhas_sql; $x++){
  
    db_fieldsmemory($resultado_sql, $x);
  
    if($quebra != $rh30_codreg){
      $quebra = $rh30_codreg;
      $pdf->ln(2);
      $pdf->cell(0,$alt,'TOTAL DO VINCULO :  '.$total_fun."  FUNCIONARIOS",0,1,"L",0);
      $total_fun = 0;
      $imprime_cabecalho = true;
    }
    if ($pdf->gety() > $pdf->h - 30 || $imprime_cabecalho == true){
       $pdf->addpage('L');
       $pdf->setfont('arial','b',8);
       $pdf->cell(20,$alt,'MATRICULA',1,0,"C",1);
       $pdf->cell(70,$alt,'NOME DO FUNCIONÁRIO',1,0,"C",1);
       $pdf->cell(17,$alt,'ADMISSÃO',1,0,"C",1);
       $pdf->cell(40,$alt,'REGIME',1,0,"C",1);
       if($compadrao == 's'){
          $pdf->cell(60,$alt,'FUNCÃO',1,0,"C",1);
          $pdf->cell(20,$alt,'PADRAO',1,0,"C",1);
          $pdf->cell(20,$alt,'SALARIO',1,1,"C",1);
       }else{
          $pdf->cell(60,$alt,'FUNCÃO',1,1,"C",1);
       }
       $pre = 1;
       
       $pdf->cell(0,$alt,'TIPO DE VINCULO '.$rh30_codreg.' - '.$rh30_descr,0,1,"L",0);
         
       $imprime_cabecalho = false;
    }
    
    if ($pre == 1){
      $pre = 0;
    }else{
      $pre = 1;
    }
    
    $pdf->setfont('arial','',7);
    $pdf->cell(20,$alt,$matricula,0,0,"C",$pre);
    $pdf->cell(70,$alt,$nome,0,0,"L",$pre);
    $pdf->cell(17,$alt,db_formatar($rh01_admiss,'d'),0,0,"L",$pre);
    $pdf->cell(40,$alt,$rh30_descr,0,0,"L",$pre);
    
    if ($compadrao == 's') {
       $pdf->cell(60,$alt,$funcao,0,0,"L",$pre);
       $pdf->cell(20,$alt,$padrao,0,0,"L",$pre);
       $pdf->cell(20,$alt,db_formatar($salario_base,'f'),0,1,"R",$pre);
       
    } else { 
       $pdf->cell(60,$alt,$funcao,0,1,"L",$pre);
    }
    
    $total_fun += 1;
    $total     += 1;
  
  }
 
  $pdf->cell(0,$alt,'TOTAL DO VINCULO :  '.$total_fun."  FUNCIONARIOS",0,1,"L",0);
  $pdf->ln(2);
  $pdf->setfont('arial','b',8);
  $pdf->cell(0,$alt,'TOTAL GERAL :  '.$total."  FUNCIONARIOS",0,1,"L",0);
  
} else {
  $alt               = 4;
  $total_func        = 0;
  $imprime_cabecalho = true;
  
  for ($x=0; $x<$qtd_linhas_sql; $x++) {
    
    db_fieldsmemory($resultado_sql, $x);
    
    if ($pdf->gety() > $pdf->h - 30 || $imprime_cabecalho == true) {
       $pdf->addpage();
       $pdf->setfont('arial','b',8);
       $pdf->cell(15,$alt,'CODIGO',1,0,"C",1);
       $pdf->cell(70,$alt,'DESCRICAO',1,0,"C",1);
       $pdf->cell(15,$alt,'TOTAL',1,1,"C",1);
       $pre = 1;
       $imprime_cabecalho = false;
    }
    
    if ($pre == 1) {
      $pre = 0;
    } else {
      $pre = 1;
    }
    
    $pdf->cell(15,$alt,$rh30_codreg,0,0,"R",$pre);
    $pdf->cell(70,$alt,$rh30_descr ,0,0,"L",$pre);
    $pdf->cell(15,$alt,$total      ,0,1,"R",$pre);
    $total_func += $total;    
  }
  
  $pdf->ln(2);
  $pdf->setfont('arial','b',8);
  $pdf->cell(85,$alt,'TOTAL GERAL :  ',0,0,"L",0);
  $pdf->cell(15,$alt,$total_func,0,1,"R",0);

}

$pdf->Output();
?>