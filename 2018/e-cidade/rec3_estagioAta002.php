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
require("libs/db_libdocumento.php");
include("dbforms/db_funcoes.php");
include("classes/estagioAvaliacoes.classe.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhestagioagenda_classe.php");
include("classes/db_rhestagioagendadata_classe.php");

$rhPessoal            = new cl_rhpessoal();
$clEstagioAgenda      = new cl_rhestagioagenda();
$clEstagioAgendaData  = new cl_rhestagioagendadata();
$get                  = db_utils::postMemory($_GET);
$sqlpref              = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref           = pg_exec($sqlpref);
$oInst                = db_utils::fieldsmemory($resultpref,0);
$get                  = db_utils::postmemory($_GET);
$rAgenda              = $clEstagioAgenda->sql_record($clEstagioAgenda->sql_query_resultado($get->iCodExame));
if (!$rAgenda){
   echo "sem agenda cadastrada.";  
}else{
   $oDadosExame  = db_utils::fieldsMemory($rAgenda,0);
}
$SQLQuesitos  = "select h51_descr,                                                                               ";
$SQLQuesitos .= "       h51_sequencial,                                                                           ";
$SQLQuesitos .= "       sum(h52_pontos) as pontos                                                                ";
$SQLQuesitos .= "  from rhestagioquesito                                                                         ";
$SQLQuesitos .= "       inner join rhestagioquesitopergunta   on h53_rhestagioquesito           = h51_sequencial ";
$SQLQuesitos .= "       inner join rhestagioquesitoresposta   on h54_rhestagioquesitopergunta   = h53_sequencial ";
$SQLQuesitos .= "       inner join rhestagiocriterio          on h54_rhestagiocriterio          = h52_sequencial ";
$SQLQuesitos .= "       left  join rhestagioavaliacaoresposta on h58_rhestagioquesitoresposta   = h54_sequencial ";
$SQLQuesitos .= "       left  join rhestagioavaliacao         on h58_rhestagioavaliacao         = h56_sequencial ";
$SQLQuesitos .= "       left  join rhestagioagendadata        on h56_rhestagioagenda            = h64_sequencial ";
$SQLQuesitos .= "       left  join rhestagioagenda            on h64_estagioagenda              = h57_sequencial ";
$SQLQuesitos .= " where h57_sequencial = {$get->iCodExame}                                                     ";
$SQLQuesitos .= " group by  h51_descr,h51_sequencial                                                             ";
$SQLQuesitos .= " order by h51_sequencial                                                                        ";
$rQuesitos    = pg_query($SQLQuesitos);
for ($j = 0; $j < pg_num_rows($rQuesitos); $j++){

  $oQuesitos = db_utils::fieldsMemory($rQuesitos, $j);
  $aQuesitos[$oQuesitos->h51_sequencial] = array("descricao" => $oQuesitos->h51_descr, "pontos" => $oQuesitos->pontos); 
}
$pdf                    = new scpdf("P","mm","A4");
$pdf->open();
$pdf1                  = new db_impcarne($pdf,'53');
$pdf1->prefeitura      = $oInst->nomeinst;
$pdf1->munic           = $oInst->munic;
$pdf1->logo            = $oInst->logo;
$pdf1->aQuesitos       = $aQuesitos;
$pdf1->dadosAvaliacao  = $oDadosExame;

$pdf1->imprime();
$pdf1->objpdf->Output();