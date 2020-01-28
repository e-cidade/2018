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
$clrotulo = new rotulocampo;
$clrotulo->label('rh61_regist');
$clrotulo->label('z01_nome');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);

/* parametros

   prof
   ordem
   ano
   mes
   selec
   lotaini
   lotafin
*/

db_postmemory($HTTP_GET_VARS);
$orderby = " rh30_codreg, z01_nome ";
$ordenacao = "Alfabética";
if(isset($ordem) && $ordem == "n"){
  $ordenacao = "Numérico";
  $orderby = " rh30_codreg, rh01_regist ";
}

//// Variáveis do cabeçalho
//// $head1 = linha 1
//// $head2 = linha 2
///  ...
//// $head9 = linha 9

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

if($sinana == 'a'){
  $campos = "rh01_regist as matricula,
             z01_nome as nome,
             z01_ident,
             z01_cgccpf,
             rh01_nasc,
	     rh30_codreg,
	     rh30_descr ";
  $grupo  = "";
}else{
  $orderby = " rh30_codreg, rh30_descr ";
  $campos  = " rh30_codreg,
               rh30_desrc ";
  $grupo = " group by rh30_codreg, rh30_descr ";
}

$head3 = "RELATÓRIO POR TIPO DE VINCULO";
$head4 = "ORDEM: ".$ordenacao;

////////////////////////////////////////////////

//// Cria a variável $sql com o sql criado
$sql = "select $campos 
		from cgm 
			inner join rhpessoal on z01_numcgm = rh01_numcgm 
			inner join rhpessoalmov on rh01_regist = rh02_regist 
			left join rhpesrescisao on rh02_seqpes = rh05_seqpes 
			inner join rhregime on rh02_codreg = rh30_codreg 
            where $where
            $grupo
            order by ".$orderby;
;

//die($sql);
//// pg_exec - executa $sql no banco e gera um RECORDSET criado na variável $resultado_sql com os dados da execução
//// da variável $sql no $resultado_sql = pg_exec($sql);
//// pg_numrows - verifica quantas linhas vieram no RECORDSET e coloca o resultado na variávei $qtd_linhas_sql
$resultado_sql = pg_query($sql);
$qtd_linhas_sql = pg_numrows($resultado_sql);
if($qtd_linhas_sql == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem funcionários cadastrados no período.');
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);

if($sinana == 'a'){

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
       $pdf->addpage('P');
       $pdf->setfont('arial','b',8);
       $pdf->cell(20,$alt,'MATRICULA',1,0,"C",1);
       $pdf->cell(70,$alt,'NOME DO FUNCIONÁRIO',1,0,"C",1);
       $pdf->cell(25,$alt,'RG',1,0,"C",1);
       $pdf->cell(40,$alt,'CPF',1,0,"C",1);
       $pdf->cell(40,$alt,'DATA DE NASCIMENTO',1,1,"C",1);
     
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
    $pdf->cell(25,$alt,$z01_ident,0,0,"C",$pre);
    $pdf->cell(40,$alt,$z01_cgccpf,0,0,"C",$pre);
    $pdf->cell(40,$alt,db_formatar($rh01_nasc,'d'),0,1,"C",$pre);
    

    $total_fun += 1;
    $total     += 1;
  
  }
 
  $pdf->cell(0,$alt,'TOTAL DO VINCULO :  '.$total_fun."  FUNCIONARIOS",0,1,"L",0);
  $pdf->ln(2);
  $pdf->setfont('arial','b',8);
//  $pdf->cell(0,$alt,'TOTAL GERAL :  '.$total."  FUNCIONARIOS",0,1,"L",0);
}else{
  $alt               = 4;
  $total_func        = 0;
  $imprime_cabecalho = true;
  
  for($x=0; $x<$qtd_linhas_sql; $x++){
    db_fieldsmemory($resultado_sql, $x);
    if ($pdf->gety() > $pdf->h - 30 || $imprime_cabecalho == true){
       $pdf->addpage();
       $pdf->setfont('arial','b',8);
       $pdf->cell(15,$alt,'CODIGO',1,0,"C",1);
       $pdf->cell(70,$alt,'DESCRICAO',1,0,"C",1);
       $pdf->cell(15,$alt,'TOTAL',1,1,"C",1);
       $pre = 1;
       $imprime_cabecalho = false;
    }
    if ($pre == 1){
      $pre = 0;
    }else{
      $pre = 1;
    }
    $pdf->cell(15,$alt,$rh30_codreg,0,0,"R",$pre);
    $pdf->cell(70,$alt,$rh30_descr,0,0,"L",$pre);
    $pdf->cell(15,$alt,$total,0,1,"R",$pre);
    $total_func += $total;    
  }
  $pdf->ln(2);
  $pdf->setfont('arial','b',8);
  $pdf->cell(85,$alt,'TOTAL GERAL :  ',0,0,"L",0);
  $pdf->cell(15,$alt,$total_func,0,1,"R",0);

}
$pdf->Output();
?>