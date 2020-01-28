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

include("libs/db_sql.php");
include("fpdf151/pdf3.php");
//db_postmemory($HTTP_SERVER_VARS,2);exit;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
if ( $lista == '' ) {
   db_redireciona('db_erros.php?fechar=true&db_erro=Lista não encontrada!');
   exit; 
}

$head1 = 'Secretaria de Financas';
$pdf = new PDF3(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 

$pdf->SetAutoPageBreak(true,0); 

$pdf->AddPage();
$pdf->SetFont('Arial','',13);

$db02_espaca=1;
$db02_inicia=30;

$pdf->MultiCell(0,4+$db02_espaca,"Atenciosamente.","0","J",0,$db02_inicia+0);
$pdf->MultiCell(0,4+$db02_espaca,"Estou testando para ver se realmente quando um paragrafo tiver mais de uma linha o pdf vai realmente colocar o inicio do paragrafo com tantos centimetros foram solicitados e quando for apenas uma palavra por exemplo vamos ver se vai funcionar, nesse caso agora eu nao faco nem ideia quantas linhas vao ter esse paragrafo.","0","J",0,$db02_inicia+0);
$pdf->MultiCell(0,4+$db02_espaca,"Joao da Silva.","0","J",0,$db02_inicia+0);

$pdf->Output();