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
//include("fpdf151/fpdf.php");
require_once('fpdf151/PDF_Label.php');
db_postmemory($HTTP_SERVER_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$inicial = 1000;
$final   = 1100;

//name		= nome da etiqueta
//paper-size	= tipo de pagina
//metric	= sistema de medida
//marginLeft	= margem esquerda
//marginTop	= margem de cima
//NX		= numero de etiquetas horizontal
//NY		= numero de etiquetas vertical
//SpaceX	= espaco entre etiquetas(lados)
//SpaceY	= espaco entre etiquetas(altura)
//width		= largura da etiqueta
//height	= altura da etiqueta
//font-size	= tamanho da fonte

$pdf = new PDF_Label (array('name'=>'5164','paper-size'=>'a3','metric'=>'mm','marginLeft'=>20,'marginTop'=>0,'NX'=>2,'NY'=>12,'SpaceX'=>2,'SpaceY'=>6,'width'=>81,'height'=>26,'font-size'=>9),1,1);
//$pdf = new PDF_Label('8600', 'mm', 2, 12);
$pdf->Open();
$pdf->_Line_Height = 6 ;
for($x=$inicial;$x < $final;$x++){
	$pdf->Add_PDF_Label(sprintf("                            PROTOCOLO\nN".chr(176).": %s  _________________ Lv: ______________\nData: _______/_______/_______\nAss.: _____________________________________ ", "$x"));
	$pdf->Add_PDF_Label(sprintf("                            PROTOCOLO\nN".chr(176).": %s  _________________ Lv: ______________\nData: _______/_______/_______\nAss.: _____________________________________ ", "$x"));
}
$pdf->Output();