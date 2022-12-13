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

require_once("fpdf151/scpdf.php");
require_once("fpdf151/impcarne.php");
require_once("libs/db_sql.php");
require_once("classes/db_matrequi_classe.php");
require_once("classes/db_matrequiitem_classe.php");
require_once("std/db_stdClass.php");
require_once("libs/db_utils.php");

$clmatrequi     = new cl_matrequi;
$clmatrequiitem = new cl_matrequiitem;

$m40_codigo     = null;
$sqlpref        = "select * from db_config where codigo = ".db_getsession("DB_instit");
$tObserva       = "18";

$resultpref = pg_exec($sqlpref);
db_fieldsmemory($resultpref,0);

$aParametro = db_stdClass::getParametro('matparam');
$oParametro = $aParametro[0];

db_postmemory($_GET);

$iCodigoDepto         = db_getsession('DB_coddepto');
$where_matrequi = " (matrequi.m40_depto = {$iCodigoDepto} or m91_depto = {$iCodigoDepto})";

switch (true) {

	case ( isset($ini) && !isset($fim) && trim($ini) != ""):
	  $where_matrequi .= " and m40_codigo >= {$ini}";
	break;
	
	case ( isset($fim) && !isset($ini) && trim($fim) != ""):
	  $where_matrequi .= " and m40_codigo <= {$fim}";
	break;
	
	case ( isset($ini) && trim($ini) != "" && isset($fim) && trim($fim) != "" ):
	    $where_matrequi .= " and m40_codigo between {$ini} and {$fim}";
	break;
	
}

// --> monta o SQL para a consulta do período

// verifica se somente o mês inicial foi preenchido
if (isset($perini) || isset($perfim)){
 
 if ( trim($perini) != "" && trim($perfim) == "" ) {
   $where_matrequi .= " AND exists (select * from 
                                     matanulitemrequi
                                      inner join matanulitem on m103_codigo = m102_matanulitem
                                      inner join matrequiitem on m41_codigo = m102_matrequiitem 
                                     where m41_codmatrequi = m40_codigo
                                     and m103_data >= '".implode('-',array_reverse(explode('/',$perini)))."')";   
   
 // verifica se somente o mês final foi preenchido
 } else if ( trim($perfim) != "" && trim($perini) == "" ) {
   $where_matrequi .= " AND exists (select * from 
                                     matanulitemrequi
                                      inner join matanulitem on m103_codigo = m102_matanulitem
                                      inner join matrequiitem on m41_codigo = m102_matrequiitem 
                                     where m41_codmatrequi = m40_codigo
                                     and m103_data <= '".implode('-',array_reverse(explode('/',$perfim)))."')";
      
 // se todos períodos foram preenchidos
 } else if ( trim($perini) != "" && trim($perfim) != "" ) {
   $where_matrequi .= " AND exists (select * from 
                                     matanulitemrequi
                                      inner join matanulitem on m103_codigo = m102_matanulitem
                                      inner join matrequiitem on m41_codigo = m102_matrequiitem 
                                     where m41_codmatrequi = m40_codigo
                                     and m103_data between '".implode('-',array_reverse(explode('/',$perini)))."' and '".implode('-',array_reverse(explode('/',$perfim)))."')";   
   
 }
	
}

$campos  = " distinct m40_codigo, ";
$campos .= "		  m42_codigo, ";
$campos .= "          m40_almox, ";
$campos .= "          m40_auto, ";
$campos .= "		  m40_data,	  ";
$campos .= "		  m40_depto,	";
$campos .= "		  descrdepto, ";
$campos .= "		  m40_hora,	  ";
$campos .= "		  m40_obs,		";
$campos .= "		  nome,				";
$campos .= "          coalesce((SELECT SUM(m41_quant) 
                        FROM matrequiitem
                       WHERE m41_codmatrequi = m40_codigo ),0) AS total_itens";

$sSql = $clmatrequi->sql_query_requisaidaalmox(null, $campos, "m40_codigo", $where_matrequi);

//echo $sSql; die;

$result_pesq_matrequi = $clmatrequi->sql_record($sSql);
$numrows_matrequi     = $clmatrequi->numrows;

if ( $numrows_matrequi == 0 ) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum Registro Encontrado! ");
} 

$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,891);
$pdf1->objpdf->SetTextColor(0,0,0);
$pdf1->Snumero_ant = "";
for($contador=0;$contador<$numrows_matrequi;$contador++){
  db_fieldsmemory($result_pesq_matrequi,$contador);
  
  $pdf1->iCorFundo  = @$oParametro->m90_corfundorequisicao;
  $pdf1->logo			  = $logo; 
  $pdf1->prefeitura = $nomeinst;
  $pdf1->enderpref  = $ender;
  $pdf1->municpref  = $munic;
  $pdf1->telefpref  = $telef;
  $pdf1->emailpref  = $email;
  $pdf1->emissao    = date("Y-m-d",db_getsession("DB_datausu"));
  $pdf1->cgcpref    = $cgc;
  $pdf1->Rnumero    = $m40_codigo;  
  $pdf1->Rauto      = ($m40_auto == 't' ? 'AUTOMÁTICA' : 'MANUAL');
  $pdf1->Ttotalreq  = $total_itens;
  

  if ( isset($m42_codigo) && trim($m42_codigo) != "") {
    $pdf1->Ratendrequi = $m42_codigo;
  } else {
    $pdf1->Ratendrequi = null;
  }

	// monta consulta da classe matrequi
	$sCampos = "m60_codmater,
              m60_descr,              
              (select m64_localizacao
                 from matmaterestoque
                 where m64_matmater = m60_codmater and 
							         m64_almox    = m40_almox) as localizacao,    
              m61_abrev as m61_descr,
              coalesce(m41_quant,0) as m41_quant,
              fc_infolotesrequisicao(m41_codigo::integer) || ' '
                     || coalesce(m41_obs,' ') as m41_obs,
                     coalesce(m103_quantanulada,0) as m43_quantatend";

	$where_matrequi_item = " m40_codigo = {$m40_codigo}";
	
	$sMatrequi    = $clmatrequiitem->sql_query_matrequianul(null, $sCampos,"m60_descr",$where_matrequi_item);
	//echo $sMatrequi; die;
	
	$result_itens = $clmatrequi->sql_record($sMatrequi);
	
	/*
   * PEGAR OS DADOS DO ALMOXARIFADO SEM INTERFERIR NA QUERY ANTERIOR 
   */
  $sAlmoxarifado  = "SELECT m91_codigo AS almoxarifado_cod, descrdepto AS almoxarifado_nome FROM db_almox ";
  $sAlmoxarifado .= " INNER JOIN db_depart ON m91_depto = coddepto ";
  $sAlmoxarifado .= "WHERE m91_codigo = ".$m40_almox;

  $rsAlmoxarifado = db_query($sAlmoxarifado);
  $oAlmoxarifado  = db_utils::fieldsmemory($rsAlmoxarifado,0);
  
  $pdf1->Ralmoxarifado_nome = $oAlmoxarifado->almoxarifado_nome;
  $pdf1->Ralmoxarifado_cod  = $oAlmoxarifado->almoxarifado_cod;
  
  $pdf1->RdepartCod = $m40_depto;
  $pdf1->Rdepart = $descrdepto;
  $pdf1->Rdata   = $m40_data;
  $pdf1->Rhora   = $m40_hora;
  $pdf1->Rnomeus = $nome;
  $pdf1->Rresumo = $m40_obs;
  $pdf1->emissao = date("Y-m-d",db_getsession("DB_datausu"));

  $numrows_itens = $clmatrequi->numrows;

  $pdf1->rcodmaterial   = "m60_codmater";
  $pdf1->rdescmaterial  = "m60_descr";
  $pdf1->runidadesaida  = "m61_descr";
  $pdf1->rquantdeitens  = "m41_quant";
  $pdf1->rquantatend    = "m43_quantatend";
  $pdf1->rlocalizacao   = "localizacao";
  $pdf1->robsdositens   = "m41_obs";
  $pdf1->recorddositens = $result_itens;
  $pdf1->linhasdositens = $numrows_itens;
  $pdf1->imprime();
  $pdf1->Snumero_ant = $m40_codigo;
}

if(isset($argv[1])){
  $pdf1->objpdf->Output("/tmp/teste.pdf");
}else{
  $pdf1->objpdf->Output();
}
?>