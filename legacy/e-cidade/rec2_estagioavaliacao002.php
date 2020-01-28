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

include("fpdf151/impcarne.php");
include("fpdf151/scpdf.php");
require("libs/db_conecta.php");
require("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/estagioAvaliacoes.classe.php");

$get                  = db_utils::postMemory($_GET);
$mostra = true;
if (isset($get->mostraResultado) && $get->mostraResultado == 'n'){
   unset($_SESSION["avaliacao"]);
   $mostra = false;
}
$clestagioAvaliacao   = new estagioAvaliacao($get->iCodExame,$mostra);
$sqlpref              = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref           = pg_exec($sqlpref);
$oInst                = db_utils::fieldsmemory($resultpref,0);
$get                  = db_utils::postmemory($_GET);
$pdf                  = new scpdf();
$pdf->open();
$pdf1                 = new db_impcarne($pdf,'51');
$pdf1->prefeitura     = $oInst->nomeinst;
$pdf1->logo				    = $oInst->logo;
$pdf1->dadosAvaliacao = $clestagioAvaliacao->agendaData->dados;
$pdf1->rsQuesitos     = $clestagioAvaliacao->getQuesitos();
$pdf1->iTotquesitos   = $clestagioAvaliacao->iTotquesitos;
$pdf1->objEstagio     = $clestagioAvaliacao;
$pdf1->imprime();
$pdf1->objpdf->Output();