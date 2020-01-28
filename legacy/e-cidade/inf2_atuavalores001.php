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

//require("libs/db_stdlib.php");
//require("libs/db_conecta.php");
//include("libs/db_sessoes.php");
include("libs/db_sql.php");
//include("dbforms/db_funcoes.php");
include("classes/db_infcab_classe.php");
include("classes/db_infcor_classe.php");
include("classes/db_tabrec_classe.php");
include("fpdf151/pdf.php");
$clinfcab = new cl_infcab;
$clinfcor = new cl_infcor;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
if ( $i03_codigo == null ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Código da lista nao preenchido!');
}

$pdf = new pdf("L");
$largura = 6;

$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();

$result = $clinfcor->sql_record($clinfcor->sql_query($i03_codigo,"","*","",""));

db_fieldsmemory($result,0);
if ( pg_numrows($result) == 0 ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado com o codigo' . $i03_codigo . '!');
}

//$pdf->SetFont('Arial','',8);
$pdf->Cell(70,$largura,'PROPRIETÁRIO',1,0,"C",0);

$pdf->Output();

?>