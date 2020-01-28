<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

/* parametros

   prof
   ordem
   ano
   mes
   selec
   lotaini
   lotafin
*/

$iInstit = db_getsession("DB_instit");

db_postmemory($HTTP_GET_VARS);
$orderby = " z01_nome ";
$ordenacao = "Alfabética";
if(isset($ordem) && $ordem == "n"){
  $ordenacao = "Numérico";
  $orderby = " rh01_regist ";
}

//// Variáveis do cabeçalho
//// $head1 = linha 1
//// $head2 = linha 2
///  ...
//// $head9 = linha 9

$where  = " rh05_recis is null ";
$where .= " and rh30_instit = {$iInstit} ";

if(isset($ano) && trim($ano) != ""){
  $where .= " and rh02_anousu = " . $ano;
}

if(isset($mes) && trim($mes) != ""){
  $where .= " and rh02_mesusu = " . $mes;
}

if(isset($selec) && $selec != ''){
  $where .= " and rh02_codreg in (".$selec.") ";
}

if(isset($lotaini) && trim($lotaini) != "" && isset($lotafim) && trim($lotafim) != ""){
  $where .= " and rh02_lota between " . $lotaini . " and " . $lotafim;
}else if(isset($lotaini) && trim($lotaini) != ""){
  $where .= " and rh02_lota >= " . $lotaini;
}else if(isset($lotafim) && trim($lotafim) != ""){
  $where .= " and rh02_lota <= " . $lotafim;
}

if($prof == 'p'){
  $where .= ' and (rh37_funcao  between 0440 and 0446)';
  $head5 = 'PROFESSORES';
}elseif($prof == 'd'){
  $where .= ' and (rh37_funcao  < 0440 or rh37_funcao > 0446)';
  $head5 = 'FUNCIONÁRIOS';
}else{
  $where .= '';
  $head5 = 'TODOS FUNCIONÁRIOS';
}

$head3 = "RELATÓRIO DE SALARIO BASE";
$head4 = "ORDEM: ".$ordenacao;
////////////////////////////////////////////////

//// Cria a variável $sql com o sql criado
$sql = "select rh01_regist as matricula,
               z01_nome as nome,
               rh01_admiss,
               rh30_descr as regime ,
               rh37_descr as funcao,
               rh03_padrao as padrao,
               substr(db_fxxx(rh02_regist, rh02_anousu, rh02_mesusu,".db_getsession("DB_instit")."),111,11) as salario_base
        from rhpessoalmov
             inner join rhpessoal     on     rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
             inner join cgm           on     rhpessoal.rh01_numcgm = cgm.z01_numcgm
             inner join rhregime      on      rhregime.rh30_codreg = rhpessoalmov.rh02_codreg
                                     and      rhregime.rh30_instit = rhpessoalmov.rh02_instit  
             inner join rhfuncao      on     rhpessoal.rh01_funcao = rhfuncao.rh37_funcao
                                     and    rhfuncao.rh37_instit   = rhpessoalmov.rh02_instit        
             left join rhpespadrao   on   rhpespadrao.rh03_seqpes = rh02_seqpes
             left join rhpesrescisao on rh02_seqpes = rh05_seqpes
        where $where
        order by ".$orderby;
;

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
$imprime_cabecalho = true;
$alt = 4;

for($x=0; $x<$qtd_linhas_sql; $x++){

  db_fieldsmemory($resultado_sql, $x);

  if ($pdf->gety() > $pdf->h - 30 || $imprime_cabecalho == true){
     $pdf->addpage('L');
     $pdf->setfont('arial','b',8);

     $pdf->cell(20,$alt,'MATRICULA',1,0,"C",1);
     $pdf->cell(70,$alt,'NOME DO FUNCIONÁRIO',1,0,"C",1);
     $pdf->cell(20,$alt,'ADMISSÃO',1,0,"C",1);
     $pdf->cell(40,$alt,'REGIME',1,0,"C",1);
     $pdf->cell(60,$alt,'FUNCÃO',1,0,"C",1);
     if($salario == 't'){
        $pdf->cell(20,$alt,'PADRAO',1,0,"C",1);
        $pdf->cell(20,$alt,'SALARIO',1,1,"C",1);
     }else{
        $pdf->cell(20,$alt,'PADRAO',1,1,"C",1);
     }
     $pre = 1;

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
  $pdf->cell(20,$alt,db_formatar($rh01_admiss,'d'),0,0,"L",$pre);
  $pdf->cell(40,$alt,$regime,0,0,"L",$pre);
  $pdf->cell(60,$alt,$funcao,0,0,"L",$pre);
   if($salario == 't'){
     $pdf->cell(20,$alt,$padrao,0,0,"L",$pre);
     $pdf->cell(20,$alt,db_formatar($salario_base,'f'),0,1,"R",$pre);
   }else{
     $pdf->cell(20,$alt,$padrao,0,1,"L",$pre);
   }
}

$pdf->setfont('arial','b',8);
$pdf->cell(225,$alt,'TOTAL DE REGISTROS  : '.$qtd_linhas_sql,"L",1,"C",0);
$pdf->Output();
?>
