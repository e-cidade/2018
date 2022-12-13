<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require("fpdf151/scpdf.php");
include("fpdf151/impcarne.php");
include("libs/db_sql.php");
include("classes/db_matpedido_classe.php");
include("classes/db_matpedidoitem_classe.php");
include("classes/db_db_depusu_classe.php");
$cldb_depusu = new cl_db_depusu;
$clmatpedido     = new cl_matpedido;
$clmatpedidoitem = new cl_matpedidoitem;
$m97_sequencial     = null;

$sqlpref        = "select * from db_config where codigo = ".db_getsession("DB_instit");
$tObserva       = "18";

$resultpref = db_query($sqlpref);
db_fieldsmemory($resultpref,0);

db_postmemory($_GET);

$iCodigoDepto         = db_getsession('DB_coddepto');
$where_matpedido = " (matpedido.m97_coddepto = {$iCodigoDepto} or m91_depto = {$iCodigoDepto})";

switch (true) {

	case ( isset($ini) && !isset($fim) && trim($ini) != ""):
	  $where_matpedido .= " and m97_sequencial >= {$ini}";
	break;
	
	case ( isset($fim) && !isset($ini) && trim($fim) != ""):
	  $where_matpedido .= " and m97_sequencial <= {$fim}";
	break;
	
	case ( isset($ini) && trim($ini) != "" && isset($fim) && trim($fim) != "" ):
	    $where_matpedido .= " and m97_sequencial between {$ini} and {$fim}";
	break;
	
}

// --> monta o SQL para a consulta do período

// verifica se somente o mês inicial foi preenchido
if (isset($perini) || isset($perfim)){
 
 if ( trim($perini) != "" && trim($perfim) == "" ) {
   $where_matpedido .= " and m97_data >= '".implode('-',array_reverse(explode('/',$perini)))."'";

 // verifica se somente o mês final foi preenchido
 } else if ( trim($perfim) != "" && trim($perini) == "" ) {
   $where_matpedido .= " and  m97_data <= '".implode('-',array_reverse(explode('/',$perfim)))."'";
 
 // se todos períodos foram preenchidos
 } else if ( trim($perini) != "" && trim($perfim) != "" ) {
   $where_matpedido .= " and m97_data between '".implode('-',array_reverse(explode('/',$perini)))."' and '".implode('-',array_reverse(explode('/',$perfim)))."'";
 }
	
}

$campos  = " distinct m97_sequencial, ";
$campos .= "			 	  m97_data,	  ";
$campos .= "                  m97_db_almox, ";
$campos .= "				  m97_coddepto,	";
$campos .= "				  db_depart.descrdepto as deptoorigem,	";
$campos .= "				  a.descrdepto as deptoalmox,	";
$campos .= "				  m97_hora,	  ";
$campos .= "				  m97_obs,		";
$campos .= "				  nome				";
$sSql = $clmatpedido->sql_query(null, $campos, "m97_sequencial", $where_matpedido);
//die($sSql);
$result_pesq_matpedido = $clmatpedido->sql_record($sSql);
$numrows_matpedido     = $clmatpedido->numrows;

if ( $numrows_matpedido == 0 ) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum Registro Encontrado! ");
  exit;
} 

$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,889);
$pdf1->objpdf->SetTextColor(0,0,0);
$pdf1->Snumero_ant = "";
for($contador=0;$contador<$numrows_matpedido;$contador++){
  db_fieldsmemory($result_pesq_matpedido,$contador);
  $pdf1->logo			  = $logo; 
  $pdf1->prefeitura = $nomeinst;
  $pdf1->enderpref  = $ender;
  $pdf1->municpref  = $munic;
  $pdf1->telefpref  = $telef;
  $pdf1->emailpref  = $email;
  $pdf1->emissao    = date("Y-m-d",db_getsession("DB_datausu"));
  $pdf1->cgcpref    = $cgc;
  $pdf1->Rnumero    = $m97_sequencial;
  $pdf1->coddepartamento    = $m97_coddepto;
  $pdf1->nomedepto    = $deptoorigem;
  $pdf1->almox    = $deptoalmox;
  $pdf1->codalmox    = $m97_db_almox;
  
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
							       m64_almox    = m97_db_almox) as localizacao,    
                     b.m61_abrev as m61_descr,
                     m98_quant,
                     m82_quant as totalAtendido,
                     fc_infolotesrequisicao(m98_sequencial::integer) || ' '
                     || coalesce(m98_obs,' ') as m98_obs
                     ";
	$sMatpedido    = $clmatpedido->sql_query_matpedidorequi($m97_sequencial,$sCampos,"m60_descr");
  $result_itens = $clmatpedido->sql_record($sMatpedido);
  $pdf1->Rdepart = $deptoorigem;
  $pdf1->Rdata   = $m97_data;
  $pdf1->Rhora   = $m97_hora;
  $pdf1->Rnomeus = $nome;
  $pdf1->Rresumo = $m97_obs;
  $pdf1->emissao = date("Y-m-d",db_getsession("DB_datausu"));

  $numrows_itens = $clmatpedido->numrows;

  $pdf1->rcodmaterial   = "m60_codmater";
  $pdf1->rdescmaterial  = "m60_descr";
  $pdf1->runidadesaida  = "m61_descr";
  $pdf1->rquantdeitens  = "m98_quant";
  $pdf1->rquantatend    = "totalAtendido";
  $pdf1->rlocalizacao   = "localizacao";
  $pdf1->robsdositens   = "m98_obs";
  $pdf1->recorddositens = $result_itens;
  $pdf1->linhasdositens = $numrows_itens;
  $pdf1->imprime();
  $pdf1->Snumero_ant = $m97_sequencial;
}

if(isset($argv[1])){
  $pdf1->objpdf->Output("/tmp/teste.pdf");
}else{
  $pdf1->objpdf->Output();
}
?>