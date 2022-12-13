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
include("classes/db_issnotaavulsaservico_classe.php");
include("classes/db_issnotaavulsa_classe.php");
include("classes/db_issnotaavulsatomador_classe.php");
include("classes/db_parissqnviasnotaavulsavias_classe.php");

$clissnotaavulsaservico       = new cl_issnotaavulsaservico();
$clissnotaavulsa              = new cl_issnotaavulsa();
$clissnotaavulsatomador       = new cl_issnotaavulsatomador();
$clparissqnviasnotaavulsavias = new cl_parissqnviasnotaavulsavias();

$sqlpref = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = pg_exec($sqlpref);
$oInst = db_utils::fieldsmemory($resultpref,0);
$get   = db_utils::postmemory($_GET);
$pdf   = new scpdf();
$pdf->open();
$pdf1  = new db_impcarne($pdf,'49');
$pdf1->rsConfig = $clparissqnviasnotaavulsavias->sql_record($clparissqnviasnotaavulsavias->sql_query());
$pdf1->prefeitura     = $oInst->nomeinst;
$pdf1->enderpref      = $oInst->ender.", ".$oInst->numero;
$pdf1->cgcpref        = $oInst->cgc;
$pdf1->municpref      = $oInst->munic;
$pdf1->telefpref      = $oInst->telef;
$pdf1->emailpref      = $oInst->email;
$pdf1->logo			      = $oInst->logo;

//dados do prestador
$rsPrest = $clissnotaavulsa->sql_record($clissnotaavulsa->sql_query($get->q51_sequencial));
$oPrest = db_utils::fieldsMemory($rsPrest,0);
$pdf1->dadosPrestador = $oPrest;
//dados do tomador
$rsTom     = $clissnotaavulsatomador->sql_record($clissnotaavulsatomador->sql_query_tomador($get->q51_sequencial));
//dados do servico
$rsServico = $clissnotaavulsaservico->sql_record($clissnotaavulsaservico->sql_query(null,"*","q62_sequencial",
                                              "q62_issnotaavulsa = ".$get->q51_sequencial));
$pdf1->qteServicos = $clissnotaavulsaservico->numrows;
$pdf1->rsServico   = $rsServico;
$pdf1->dadosTomador = db_utils::fieldsMemory($rsTom,0);
//dados de config 
$pdf1->imprime();
$pdf1->objpdf->Output();
?>