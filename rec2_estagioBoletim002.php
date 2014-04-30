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
$sSQLAvaliacoes  = " select h64_sequencial,";
$sSQLAvaliacoes .= "        h64_seqaval,";
$sSQLAvaliacoes .= "        h64_data,";
$sSQLAvaliacoes .= "        h53_rhestagioquesito,";
$sSQLAvaliacoes .= "        sum(h52_pontos) as total";
$sSQLAvaliacoes .= "   from rhestagioagendadata ";
$sSQLAvaliacoes .= "        left join rhestagioavaliacao         on h56_rhestagioagenda          = h64_sequencial";
$sSQLAvaliacoes .= "        left join rhestagioavaliacaoresposta on h58_rhestagioavaliacao       = h56_sequencial";
$sSQLAvaliacoes .= "        left join rhestagioquesitoresposta   on h58_rhestagioquesitoresposta = h54_sequencial ";
$sSQLAvaliacoes .= "        left join rhestagiocriterio          on h54_rhestagiocriterio        = h52_sequencial";
$sSQLAvaliacoes .= "        left join rhestagioquesitopergunta   on h54_rhestagioquesitopergunta = h53_sequencial";
$sSQLAvaliacoes .= "  where h64_estagioagenda = {$get->iCodExame}";
$sSQLAvaliacoes .= "  group by h64_sequencial,h64_seqaval,  h64_data,h53_rhestagioquesito";
$sSQLAvaliacoes .= "  order by h64_seqaval,h64_data,h53_rhestagioquesito";
$rAvaliacoes     = pg_query($sSQLAvaliacoes);
$aDadosAvaliacoes = array();
for ($i = 0; $i< pg_num_rows($rAvaliacoes); $i++){
      
    $oAvaliacoes = db_utils::fieldsMemory($rAvaliacoes,$i);
    if (!isset($aDadosAvaliacoes[$oAvaliacoes->h64_sequencial])){
      $aDadosAvaliacoes[$oAvaliacoes->h64_sequencial]["seqaval"] = $oAvaliacoes->h64_seqaval;
      $aDadosAvaliacoes[$oAvaliacoes->h64_sequencial]["data"] = $oAvaliacoes->h64_data;
      $aDadosAvaliacoes[$oAvaliacoes->h64_sequencial]["quesito"][$oAvaliacoes->h53_rhestagioquesito] = $oAvaliacoes->total;
    }else{
      $aDadosAvaliacoes[$oAvaliacoes->h64_sequencial]["quesito"][$oAvaliacoes->h53_rhestagioquesito] = $oAvaliacoes->total;
    }
}
$SQLQuesitos  = "select h51_descr, ";
$SQLQuesitos .= "       h51_sequencial";
$SQLQuesitos .= "  from rhestagioquesito";
$SQLQuesitos .= " where h51_rhestagio = {$oDadosExame->h57_rhestagio}";
$SQLQuesitos .= " order by h51_sequencial";
$rQuesitos    = pg_query($SQLQuesitos);
for ($j = 0; $j < pg_num_rows($rQuesitos); $j++){

  $oQuesitos = db_utils::fieldsMemory($rQuesitos, $j);
  $aQuesitos[$oQuesitos->h51_sequencial] = $oQuesitos->h51_descr; 
}
$pdf                    = new scpdf("L","mm","A4");
$pdf->open();
$pdf1                  = new db_impcarne($pdf,'52');
$pdf1->prefeitura      = $oInst->nomeinst;
$pdf1->munic           = $oInst->munic;
$pdf1->logo            = $oInst->logo;
$pdf1->aDadosAvaliacao = $aDadosAvaliacoes;
$pdf1->aQuesitos       = $aQuesitos;
$pdf1->dadosAvaliacao  = $oDadosExame;

$pdf1->imprime();
$pdf1->objpdf->Output();