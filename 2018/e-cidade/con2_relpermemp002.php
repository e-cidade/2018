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

include("classes/db_db_permemp_classe.php");
include("classes/db_db_usupermemp_classe.php");
include("classes/db_db_depusuemp_classe.php");
include("classes/db_orcorgao_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_orcunidade_classe.php");
include("classes/db_orcdotacao_classe.php");
include("classes/db_orcelemento_classe.php");

$clorcelemento   = new cl_orcelemento;
$clorcorgao      = new cl_orcorgao;
$cldb_usuarios   = new cl_db_usuarios;
$cldb_depart     = new cl_db_depart;
$clorcunidade    = new cl_orcunidade;
$clorcdotacao    = new cl_orcdotacao;
$cldb_permemp    = new cl_db_permemp;
$cldb_usupermemp = new cl_db_usupermemp;
$cldb_depusuemp  = new cl_db_depusuemp;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);


// @ parametros
// # quebra = 'u' = usuario, 'e' = estrutura (orgao, unidade, etc )
// # -

// @ modelo
// @ $quebra = 'u' 
//
// Usuario:Nome (CGM) 
//     Orgao, unidade, funcão, [...]
//         1       2        3, [...]
//         2       0        3, [...]
//

// @ modelo
// @ $quebra = 'e' 
//
// Orgao, Unidade
//     1        0 
//      usuarios: Nome
//             1  João  - (Usuario)
//             3  Finanças  - (Departamento)
//
// Orgao, Unidade
//     1        1 
//      usuarios: Nome
//             3  João  - (Usuario)
//             1  Finanças  - (Departamento)
//
// Orgao, Unidade
//     2        0 
//      usuarios: Nome
//             3  João  - (Usuario)
//             1  Finanças  - (Departamento)
//

////////////////////////////////////////////////////////////////////////


// modelo ='u'

// ordena por usuario
$sql = "
        select 
	      db20_orgao, 
	      db20_unidade,
	      db20_funcao,
	      db20_subfuncao,
	      db20_programa,
	      db20_projativ,
	      db20_codele,
	      db20_codigo,
	      case when db20_tipoperm='C' 
	      then 'Consulta'
	      else 'Manutenção'
	      end as db20_tipoperm,
	      usuario,
	      nome,
	      usuario_tipo
	
	from (
           /* permissoes por usuario */
           select db_permemp.*,
	         db21_id_usuario as usuario,
		 nome            as nome,
	         'Usuario'       as usuario_tipo 		
           from db_permemp 
               inner join db_usupermemp  on  db_usupermemp.db21_codperm = db_permemp.db20_codperm
	       inner join db_usuarios on id_usuario= db_usupermemp.db21_id_usuario
           where db20_anousu=  $exercicio	    
	
	   union all
	
	   /* permissoes por departamento */
	   select db_permemp.*,
    	          db22_coddepto  as usuario,
		  descrdepto     as nome,
	          'Departamento' as usuario_tipo	       
           from db_permemp 
               inner join db_depusuemp  on  db_depusuemp.db22_codperm = db_permemp.db20_codperm
	       inner join db_depart on coddepto = db_depusuemp.db22_coddepto
           where db20_anousu=  $exercicio	    
        ) as x
	  /* nao torcar esse order by por usuario eh obrigatorio para funcionar o relatorio  */
	  order by usuario,
	           db20_orgao,
		   db20_unidade,
		   db20_funcao,
		   db20_subfuncao,
		   db20_programa,
		   db20_projativ,
		   db20_codele,
		   db20_codigo,
		   db20_tipoperm
       ";
       
// $res = pg_query($sql);
// echo $sql;
// db_criatabela($res);
// exit;
$result  = $cldb_permemp->sql_record($sql);     


$head2 = "Permissões de Empenho ";
$head3 = "Exercício: $exercicio ";
$head6 = "Quebra: ".($quebra=='u'?"Usuarios":"Estrutura(Orgão/Unidade)");


$pdf = new pdf();
$pdf->Open();

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFillColor(220);


$pdf->SetFont('Arial','',8);

$altura = 4;
$repete_header =false;
// usado para controlar quando altera o usuario
$cod_usuario ='';

for($i=0; $i< $cldb_permemp->numrows; $i++){  
    db_fieldsmemory($result,$i);
    
    if ($pdf->gety() > ($pdf->h-40)) {
	$pdf->AddPage();
	
        $repete_header = true;
    
    }
    if ($cod_usuario!=$usuario || $repete_header==true){
       $repete_header =false;
       $cod_usuario = $usuario;
       $pdf->Ln();
       
       $pdf->Cell(20,4,$usuario,'TBR',0,"C",0);
       $pdf->Cell(60,4,$nome,'TB',0,"L",0);       
       $pdf->Ln();        
       $pdf->Cell(20,$altura,"ORGÃO",'TB',0,"C",0);
       $pdf->Cell(20,$altura,"UNIDADE",1,0,"C",0);
       $pdf->Cell(20,$altura,"FUNÇÃO",1,0,"C",0);
       $pdf->Cell(20,$altura,"SUBFUNÇÃO",1,0,"C",0);
       $pdf->Cell(20,$altura,"PROGRAMA",1,0,"C",0);
       $pdf->Cell(20,$altura,"PROJ/ATIV",1,0,"C",0);
       $pdf->Cell(20,$altura,"ELEMENTO",1,0,"C",0);
       $pdf->Cell(20,$altura,"RECURSO",1,0,"C",0);
       $pdf->Cell(20,$altura,"TIPO",'TB',0,"C",0);
       $pdf->Ln();        
       
    }

    $pdf->Cell(20,$altura,$db20_orgao,'TB',0,"C",0);
    $pdf->Cell(20,$altura,$db20_unidade,1,0,"C",0);
    $pdf->Cell(20,$altura,$db20_funcao,1,0,"C",0);
    $pdf->Cell(20,$altura,$db20_subfuncao,1,0,"C",0);
    $pdf->Cell(20,$altura,$db20_programa,1,0,"C",0);
    $pdf->Cell(20,$altura,$db20_projativ,1,0,"C",0);
    $pdf->Cell(20,$altura,$db20_codele,1,0,"C",0);
    $pdf->Cell(20,$altura,$db20_codigo,1,0,"C",0);
    $pdf->Cell(20,$altura,$db20_tipoperm,'TB',0,"L",0);
    $pdf->Ln();
}

$pdf->Output();


?>