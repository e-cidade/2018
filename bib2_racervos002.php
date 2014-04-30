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

require_once ("fpdf151/pdf.php");
require_once ("libs/db_sql.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
$clacervo           = new cl_acervo;
$clautor            = new cl_autor;
$clautoracervo      = new cl_autoracervo;
$cllocalexemplar    = new cl_localexemplar;
$cltipoitem         = new cl_tipoitem;
$cleditora          = new cl_editora;
$clclassiliteraria  = new cl_classiliteraria;
$cllocalizacao      = new cl_localizacao;
$claquisicao        = new cl_aquisicao;
$classunto          = new cl_assunto;
$clexemplar         = new cl_exemplar;
$oDaoColecao        = new cl_colecaoacervo;

$sql_autor          = false;

$where = " bi06_biblioteca = $biblioteca ";
$head4 = "";
if ($tipo != "") {
  
  $where .= " and bi06_tipoitem = $tipo";
  $db     = $cltipoitem->sql_record($cltipoitem->sql_query("","bi05_nome",""," bi05_codigo = $tipo"));
  db_fieldsmemory($db, 0);
  $head4 .= "- Tipo Ítem: $bi05_nome\n";
}
if ($editora != "") {
  
  $where .= " and bi06_editora = $editora";
  $db     = $cleditora->sql_record($cleditora->sql_query("","bi02_nome",""," bi02_codigo = $editora"));
  db_fieldsmemory($db,0);
  $head4 .= "- Editora: $bi02_nome\n";
}
if ($classi != "") {
  
  $where .= " and bi06_classiliteraria = $classi";
  $db     = $clclassiliteraria->sql_record($clclassiliteraria->sql_query("","bi03_classificacao",""," bi03_codigo = $classi"));
  db_fieldsmemory($db,0);
  $head4 .= "- Class. Literária: $bi03_classificacao\n";
}

if ($autor != "") {
  
  $where .= " and bi21_autor = $autor";
  $db     = $clautor->sql_record($clautor->sql_query("","bi01_nome",""," bi01_codigo = $autor"));
  db_fieldsmemory($db,0);
  $head4 .= "- Autor: $bi01_nome\n";
  $sql_autor = true;
}

if ($localizacao != "") {
  
  $where .= " and bi20_localizacao = $localizacao";
  $db     = $cllocalizacao->sql_record($cllocalizacao->sql_query("","bi09_nome",""," bi09_codigo = $localizacao"));
  db_fieldsmemory($db,0);
  $head4 .= "- Localização: $bi09_nome\n";
}

if ($iColecao != '') {
  
  $where .= " and bi06_colecaoacervo = {$iColecao}";
  //echo $oDaoColecao->sql_query_file($iColecao) ;
  $rs     = $oDaoColecao->sql_record($oDaoColecao->sql_query_file($iColecao));
  $head5  = "Coleção: " . db_utils::fieldsMemory($rs, 0)->bi29_nome;
}

$campos = "bi06_seq,
           bi06_titulo,
           bi06_dataregistro,
           bi06_anoedicao,
           bi09_nome,
           bi06_classcdd,
           bi06_isbn,
           bi06_volume,
           bi05_nome,
           bi02_nome,
           bi03_classificacao,
		       bi20_sequencia,
           bi29_abreviatura
          ";

if ($sql_autor) {
  $result = $clacervo->sql_record($clacervo->sql_query_autores(""," DISTINCT ".$campos,$ordem,$where));
} else {
  $result = $clacervo->sql_record($clacervo->sql_query("",$campos,$ordem,$where));
}
if ($clacervo->numrows == 0) { 
  db_redireciona('db_erros.php?fechar=true&db_erro=Não foram encontrados registros.');
}
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(243);
$head1 = "RELATÓRIO DE ACERVOS";
$head2 = "Biblioteca: $desc_biblioteca";
$head3 = $rel=="resumido"?"RESUMIDO":"COMPLETO";
$head3 = "Relatório: ".$head3;
if ($head4 == "") {
  $head4 = "- TODAS";
}
$pdf->addpage('P');
$pdf->ln(5);
if ($rel == "resumido") {
  $pdf->setfont('arial','b',7);
  $pdf->cell(20,4,"Código Acervo",1,0,"C",1);
  $pdf->cell(90,4,"Titulo","BTR",0,"L",1);
  $pdf->cell(20,4,"Ano Edição",1,0,"C",1);
  $pdf->cell(20,4,"Data Registro",1,0,"C",1);
  $pdf->cell(40,4,"Localização",1,0,"C",1);
  $pdf->cell(7,4,"Local",1,1,"C",1);
}
for ($x = 0; $x < $clacervo->numrows; $x++) {
  
  db_fieldsmemory($result,$x);
  if ($rel == "completo") {
    
    $pdf->setfont('arial','b',7);
    $pdf->cell(20,4,"Código Acervo",1,0,"C",1);
    $pdf->cell(90,4,"Titulo","RTB",0,"L",1);
    $pdf->cell(20,4,"Ano Edição",1,0,"C",1);
    $pdf->cell(20,4,"Data Registro",1,0,"C",1);
    $pdf->cell(40,4,"Localização",1,0,"C",1);
    $pdf->cell(7,4,"Local",1,1,"C",1);
  }
  $pdf->setfont('arial','',6);
  $pdf->cell(20,4,str_pad(@$bi06_seq,6,0, STR_PAD_LEFT),1,0,"C",0);
  $pdf->cell(90,4,substr($bi06_titulo,0,70) . " - $bi29_abreviatura", "RTB",0,"L",0);
  $pdf->cell(20,4,$bi06_anoedicao, 1,0,"C",0);
  $pdf->cell(20,4,db_formatar(@$bi06_dataregistro,'d'),1,0,"C",0);
  $pdf->cell(40,4,$bi09_nome,1,0,"C",0);
  $pdf->cell(7,4,$bi20_sequencia,1,1,"C",0);
  if ($rel == "completo") {
    
    $pdf->cell(20,3,"",0,0,"C",0);
    $pdf->cell(20,3,"Clas. CDD:","L",0,"L",0);
    $pdf->cell(40,3,$bi06_classcdd,0,0,"L",0);
    $pdf->cell(20,3,"ISBN:",0,0,"L",0);
    $pdf->cell(40,3,$bi06_isbn,0,0,"L",0);
    $pdf->cell(20,3,"Volume:",0,0,"L",0);
    $pdf->cell(32,3,$bi06_volume,"R",1,"L",0);
    
    $pdf->cell(20,3,"",0,0,"C",0);
    $pdf->cell(20,3,"Tipo Item:","L",0,"L",0);
    $pdf->cell(40,3,$bi05_nome,0,0,"L",0);
    $pdf->cell(20,3,"Editora:",0,0,"L",0);
    $pdf->cell(40,3,$bi02_nome,0,0,"L",0);
    $pdf->cell(20,3,"Clas. Literária:",0,0,"L",0);
    $pdf->cell(32,3,$bi03_classificacao,"R",1,"L",0);
    
    $pdf->setfont('arial','b',7);
    $pdf->cell(20,4,"",0,0,"C",0);
    $pdf->cell(172,4,"Autores :","LRB",1,"L",0);
    $result_autor = $clautoracervo->sql_record($clautoracervo->sql_query("","bi01_nome","bi01_nome"," bi21_acervo = $bi06_seq"));
    $autores = "";
    $sep = "";
    for ($y=0;$y<$clautoracervo->numrows;$y++) {
      
      db_fieldsmemory($result_autor,$y);
      $autores .= $sep.$bi01_nome;
      $sep      = ",";
    }
    $pdf->setfont('arial','',6);
    $pdf->cell(20,3,"",0,0,"C",0);
    $pdf->multicell(172,3, $autores,"LR",1,"L",0);
    $pdf->setfont('arial','b',7);
    $pdf->cell(20,4,"",0,0,"C",0);
    $pdf->cell(172,4,"Assuntos :","LRB",1,"L",0);
    $pdf->setfont('arial','',6);
    $result_assunto = $classunto->sql_record($classunto->sql_query_file("","*",""," bi15_acervo = $bi06_seq"));
    for($z=0;$z<$classunto->numrows;$z++){
    
      db_fieldsmemory($result_assunto,$z);
      $pdf->cell(20,3,"",0,0,"C",0);
      $pdf->multicell(172,3,"* ".$bi15_assunto,"LR",1,"L",0);
    }
    $pdf->cell(20,1,"",0,0,"C",0);
    $pdf->cell(172,1,"","RL",1,"L",0);
    $pdf->setfont('arial','b',7);
    $pdf->cell(20,4,"",0,0,"C",0);
    $pdf->cell(172,4,"Exemplares :","LRB",1,"L",0);
    $pdf->setfont('arial','',6);
    $result_exemplar = $clexemplar->sql_record($clexemplar->sql_query("","*","bi23_codigo"," bi23_acervo = $bi06_seq"));
    for ($z = 0; $z < $clexemplar->numrows; $z++) {
      
      db_fieldsmemory($result_exemplar,$z);
      $result0 = $cllocalexemplar->sql_record($cllocalexemplar->sql_query("","bi20_sequencia,bi27_letra",""," bi27_exemplar = $bi23_codigo"));
      if ($cllocalexemplar->numrows > 0) {
        
        db_fieldsmemory($result0,0);
        $sequencia = $bi23_situacao=="N"?"":" - Ordem: ".$bi20_sequencia.($bi27_letra!=""?"-".$bi27_letra:"");
      } else {
        $sequencia = "";
      }
      $pdf->cell(20,3,"",0,0,"C",0);
      $pdf->multicell(172,3,$bi23_codigo." - ".$bi23_codbarras." - Adquirido em ".db_formatar($bi23_dataaquisicao,'d')." - Situação: ".($bi23_situacao=="S"?"ATIVO":"INATIVO")." - Tipo de Aquisiçao: ".$bi04_forma.($sequencia!=""?$sequencia:""),"LR",1,"L",0);
    }
    $pdf->cell(20,1,"",0,0,"C",0);
    $pdf->cell(172,1,"","RBL",1,"L",0);
  }
}
$pdf->Output();
?>