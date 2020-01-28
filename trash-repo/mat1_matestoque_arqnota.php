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

include_once("classes/db_matordem_classe.php");
include_once("classes/db_matordemitem_classe.php");
include_once("classes/db_empnotaord_classe.php");
include_once("classes/db_matestoqueini_classe.php");
include_once("classes/db_matestoque_classe.php");
include_once("classes/db_empnotaord_classe.php");
include_once("classes/db_matmater_classe.php");
include_once("classes/db_matmaterunisai_classe.php");
include_once("classes/db_transmater_classe.php");
include_once("classes/db_matestoqueitem_classe.php");
include_once("classes/db_matestoqueitemlanc_classe.php");
include_once("classes/db_matestoqueitemunid_classe.php");
include_once("classes/db_matestoqueitemoc_classe.php");
include_once("classes/db_matestoqueitemnota_classe.php");
include_once("classes/db_matestoqueinimei_classe.php");
include_once("classes/db_pagordemrec_classe.php");

$clmatordem       = new cl_matordem;
$clmatordemitem   = new cl_matordemitem;
$clempnotaord  = new cl_empnotaord;
$clpagordemrec = new cl_pagordemrec;

$clmatestoqueini   = new cl_matestoqueini;
$clmatestoque      = new cl_matestoque;
$clempnotaord      = new cl_empnotaord;
$clmatmater        = new cl_matmater;
$clmatmaterunisai  = new cl_matmaterunisai;
$cltransmater      =  new cl_transmater;
$clmatestoqueitem     =  new cl_matestoqueitem;
$clmatestoqueitemlanc =  new cl_matestoqueitemlanc;
$clmatestoqueitemunid =  new cl_matestoqueitemunid;
$clmatestoqueitemoc   =  new cl_matestoqueitemoc;
$clmatestoqueitemnota =  new cl_matestoqueitemnota;
$clmatestoqueinimei   =  new cl_matestoqueinimei;

$mostrar_dados_incluidos = false;

/**
 *
 * Variáveis fixas do script
 *
 */
$data_hoje  = date('Y-m-d',db_getsession("DB_datausu"));
$id_usuario = db_getsession("DB_id_usuario");
$m51_depto  = db_getsession("DB_coddepto");
$m80_obs    = "S/Obs";

/**
 *
 * Lançamento de ordem de compra
 *
 */
$clmatordem->m51_codordem   = null; // auto-inc 
$clmatordem->m51_data       = $data_hoje;
$clmatordem->m51_depto      = db_getsession("DB_coddepto");
$clmatordem->m51_numcgm     = $e54_numcgm;
$clmatordem->m51_obs        = $e54_resumo;
$clmatordem->m51_valortotal = $e60_vlremp;
$clmatordem->m51_prazoent   = 30;
$clmatordem->m51_tipo       = 2;
$clmatordem->incluir($clmatordem->m51_codordem);
if($clmatordem->erro_status == 0){
  $erro_msg = "Erro ao incluir Ordem de Compra".$clmatordem->erro_msg;
  $sqlerro  = true;
}
$m51_codordem = $clmatordem->m51_codordem;

/**
 *
 * Inclui itens da ordem de compra
 *
 */
if($sqlerro == false){
  $result  = $clempautitem->sql_record($clempautitem->sql_query_file($e54_autori));
  $numrows = $clempautitem->numrows;
  for($i=0; $i<$numrows; $i++){
    db_fieldsmemory($result,$i);

    $clmatordemitem->m52_codordem = $m51_codordem;
    $clmatordemitem->m52_numemp   = $e60_numemp;
    $clmatordemitem->m52_sequen   = $e55_sequen;
    $clmatordemitem->m52_quant    = $e55_quant;
    $clmatordemitem->m52_valor    = $e55_vltot;
    $clmatordemitem->m52_vlruni   = $e55_vlrun;
    $clmatordemitem->incluir(null);
    if($clmatordemitem->erro_status == 0){
      $erro_msg = "Não consegui incluir ítens de Ordem de Compra".$clmatordemitem->erro_msg;
      $sqlerro  = true;
      break;
    }
  }    
  
}
$result_depto = $clmatordem->sql_record($clmatordem->sql_query_file(null,"m51_depto",null,"m51_codordem=$m51_codordem"));
if($clmatordem->numrows > 0){
  db_fieldsmemory($result_depto,0);
}
/**
 *
 * Inclui a nota fiscal do fornecedor
 *
 */
if($sqlerro == false){
  if ($e69_numero == '') {
    $e69_numero = "s/ Número";
  }
  $dtNota = $data_hoje;
  if ($e69_dtnota != "") {

    $dtParts = explode("/",$e69_dtnota);
    $dtNota  = "{$dtParts[2]}-{$dtParts[1]}-{$dtParts[0]}";

  }
  $clempnota->e69_numero     = $e69_numero; 
  $clempnota->e69_numemp     = $e60_numemp ; 
  $clempnota->e69_id_usuario = $id_usuario; 
  $clempnota->e69_dtnota     = $dtNota; 
  $clempnota->e69_dtrecebe   = $data_hoje;
  $clempnota->e69_dtservidor = date('Y-m-d');
  $clempnota->e69_dtinclusao = date('Y-m-d',db_getsession("DB_datausu"));
  $clempnota->e69_anousu     = db_getsession("DB_anousu");
  if (!isset($e69_tipodocumentosfiscal) || $e69_tipodocumentosfiscal == "") {
    $clempnota->e69_tipodocumentosfiscal = 4;  
  }
  $clempnota->incluir(null);
  $e69_codnota = $clempnota->e69_codnota;
  if($clempnota->erro_status == 0){
    $erro_msg = $clempnota->erro_msg;
    $sqlerro  = true;
  }
  if (!class_exists("cl_empnotaitem")){
    require ("classes/db_empnotaitem_classe.php");
  } 
  if (!$sqlerro) {
	  for ($i = 0; $i < $clempautitem->numrows; $i++){
	   
	    $oAut    = db_utils::fieldsMemory($result,$i); 
	    $sSQL    = "select e62_sequencial";
	    $sSQL   .= "   from empempitem ";
	    $sSQL   .= "  where e62_numemp = {$e60_numemp} ";
	    $sSQL   .= "    and e62_sequen  = {$oAut->e55_sequen}";
	    $rsItem  = pg_query($sSQL);
	    $oItem   = db_utils::fieldsMemory($rsItem,0);
	    $objEmpNotaItem = new cl_empnotaitem();
	    $objEmpNotaItem->e72_codnota    = $e69_codnota;
	    $objEmpNotaItem->e72_empempitem = $oItem->e62_sequencial;
	    $objEmpNotaItem->e72_qtd        = $oAut->e55_quant;
	    $objEmpNotaItem->e72_valor      = $oAut->e55_vltot;
	    $objEmpNotaItem->incluir(null);
	    if ($objEmpNotaItem->erro_status == 0){
	      
	      $sqlerro  = true;
	      $erro_msg = "Erro (1) - não Foi possível gerar itens da ordem de compra .\nerro:{$objEmpNotaItem->erro_msg}"; 
	   }  
	  }
  }
} 

/**
 *
 * Ligação de uma ordem de compra a uma nota fiscal (de um fornecedor)
 *
 */
if($sqlerro == false){
  $clempnotaord->incluir($e69_codnota,$m51_codordem);
  if($clempnotaord->erro_status == 0){
    $erro_msg = $clempnotaord->erro_msg;
    $sqlerro  = true;
  }
}

/**
 *
 * Inclui os elementos na nota
 *
 */
if($sqlerro == false){
  $result  = $clempautitem->sql_record($clempautitem->sql_query_elemento($e54_autori));
  $numrows = $clempautitem->numrows;
  for($i=0; $i<$numrows; $i++){
    db_fieldsmemory($result,$i);
    $clempnotaele->e70_codnota = $clempnotaord->m72_codnota;
    $clempnotaele->e70_codele  = $e55_codele;
    $clempnotaele->e70_valor   = $e55_vltot;
    $clempnotaele->e70_vlranu  = '0.00';
    $clempnotaele->e70_vlrliq  = '0.00';
    $clempnotaele->incluir($clempnotaele->e70_codnota,$clempnotaele->e70_codele);
    if($clempnotaele->erro_status == 0){
      $erro_msg = "Falha: ".$clempnotaele->erro_msg;
      $sqlerro  = true;
      break;
    }
  }
}

/**
 * ordem de pagamento = Nota de liquidação
 */
//if ($sqlerro==false){
//    $clpagordem->e50_numemp     = $e60_numemp;
//    $clpagordem->e50_data       = date("Y-m-d",db_getsession("DB_datausu"));
//    $clpagordem->e50_obs        = $e50_obs;
//    $clpagordem->e50_id_usuario = db_getsession("DB_id_usuario");
//    $clpagordem->e50_hora       = db_hora();
//    $clpagordem->e50_anousu     = db_getsession("DB_anousu");
//    $clpagordem->incluir(null);
//    if($clpagordem->erro_status==0){
//       $sqlerro=true;
//       $erro_msg = $clpagordem->erro_msg;
//    }else{
//       $e50_codord =  $clpagordem->e50_codord ;
//    }   
//}
///**
//* lança pagordemele = empnotaele
//*/
//if ($sqlerro==false){
//    $clpagordemele->e53_codord  = $e50_codord;
//    $clpagordemele->e53_codele  = $e64_codele;
//    $clpagordemele->e53_valor   = $e60_vlremp;
//    $clpagordemele->e53_vlranu  = '0.00' ;
//    $clpagordemele->e53_vlrpag  = '0.00' ;
//    $clpagordemele->incluir($e50_codord,$e64_codele);
//    if($clpagordemele->erro_status==0){
//        $sqlerro=true;
//        $erro_msg = $clpagordemele->erro_msg;
//    }
//}  
/**
* ligação da nota de liquidação (pagordem) com a nota do fornecedor ( empnota )
*/
//if($sqlerro==false){ 
//    $clpagordemnota->e71_codord  = $e50_codord;
//    $clpagordemnota->e71_codnota = $clempnota->e69_codnota;
//    $clpagordemnota->e71_anulado = "false";
//    $clpagordemnota->incluir($e50_codord,$clpagordemnota->e71_codnota);
//    if($clpagordemnota->erro_status==0){
//         $erro_msg=$clpagordemnota->erro_msg;
//         $sqlerro=true;
//    }	
//}

/**
* arquivo de retenções na nota de liquidação ( ordem de pagamento )

*/
if($sqlerro == false){
    /**
    * seleciona as retenções lançadas na autorização e
    * lança para o empenho (duplica-as)
    */    
    $result_retencao = $clempautret->sql_record($clempautret->sql_query($e54_autori, null, "empretencao.*"));
    $numrows_retencao = $clempautret->numrows;
    for($i=0; $i<$numrows_retencao; $i++){
      db_fieldsmemory($result_retencao, $i);      
      $clpagordemrec->e52_codord = $e50_codord;
      $clpagordemrec->e52_receit = $e65_receita;
      $clpagordemrec->e52_valor  = $e65_valor;
      $clpagordemrec->incluir($clpagordemrec->e52_codord,$clpagordemrec->e52_receit);
      if($clpagordemrec->erro_status==0){
          $erro_msg=$clpagordemrec->erro_msg;
          $sqlerro=true;
          break;
      }
    }// end loop
}

require_once 'model/agendaPagamento.model.php';

/*
 * Caso o usuário marcou que devemos agendar automaticamente 
 * a nota liquidada, fizemos o lancamento.
 */
/*
$rsParametros   = $clempparametro->sql_record($clempparametro->sql_query_file(db_getsession("DB_anousu")));
if ($clempparametro->numrows > 0){
  $oParametros = db_utils::fieldsMemory($rsParametros,0);
} else {
  $erro_msg = "Erro [1] - Não foi possível encontrar parametros do empenho para o ano.";
  $sqlerro  = true; 
}
if ($oParametros->e30_agendaautomatico == "t" && !$sqlerro) {

  require_once("model/agendaPagamento.model.php"); 
  $oAgenda = new agendaPagamento();
  $oAgenda->setCodigoAgenda($oAgenda->newAgenda());
  //Criamos o objeto da nota, que sera agendada.
  $oNota  = new stdClass;
  $oNota->iNumEmp   = $e60_numemp;
  $oNota->iCodNota  = $clpagordem->e50_codord;
  $oNota->nValor    = $e60_vlremp;
  $oNota->iCodTipo  = null;
       
  try {
    $iCodigoMovimento = $oAgenda->addMovimentoAgenda(1, $oNota);
   }
   catch (Exception $eErroNota) {

    $sqlerro  = true;
    $erro_msg = $eErroNota->getMessage();
        
   }
}
*/
if($mostrar_dados_incluidos == true){
  $res = pg_exec("select empempitem.* from empempitem inner join empempenho on e62_numemp = e60_numemp where e60_numemp = $e60_numemp;");
  db_criatabela($res);
  for($i=0; $i<pg_num_rows($res); $i++){
    db_fieldsmemory($res, $i);

    echo "<BR><BR><BR><BR><b> Virou! </b>";

    echo "<BR>empnota";
    $res1 = pg_exec("select * from empnota where e69_numemp = $e60_numemp;");
    db_fieldsmemory($res1, 0);
    db_criatabela($res1);

    echo "empnotaele";
    $res1 = pg_exec("select * from empnotaele where e70_codnota = $e69_codnota;");
    db_criatabela($res1);

    echo "empnotaord";
    $res1 = pg_exec("select * from empnotaord where m72_codnota = $e69_codnota;");
    db_criatabela($res1);

    echo "matordemitem";
    $res1 = pg_exec("select * from matordemitem where m52_numemp = $e60_numemp;");
    db_fieldsmemory($res1, 0);
    db_criatabela($res1);
    
    echo "matordem";
    $res1 = pg_exec("select * from matordem where m51_codordem = $m52_codordem");
    db_criatabela($res1);

    echo "matestoque";
    $res1 = pg_exec("select matestoque.* from transmater inner join matestoque on m70_codmatmater = m63_codmatmater where m63_codpcmater = $e62_item");
    db_fieldsmemory($res1, 0);
    db_criatabela($res1);

    echo "matestoqueitem";
    $res1 = pg_exec("select * from matestoqueitem where m71_codmatestoque = $m70_codigo");
    db_fieldsmemory($res1, 0);
    db_criatabela($res1);

    echo "matestoqueunid";
    $res1 = pg_exec("select * from matestoqueitemunid where m75_codmatestoqueitem = $m71_codlanc");
    db_criatabela($res1);

    echo "matestoqueitemoc";
    $res1 = pg_exec("select * from matestoqueitemoc where m73_codmatestoqueitem = $m71_codlanc");
    db_fieldsmemory($res1, 0);
    db_criatabela($res1);
    
    echo "matestoqueitem";
    $res1 = pg_exec("select * from matordemitem where m52_codlanc = $m73_codmatordemitem;");
    db_fieldsmemory($res1, 0);
    db_criatabela($res1);
    
    echo "matordem";
    $res1 = pg_exec("select * from matordem where m51_codordem = $m52_codordem");
    db_criatabela($res1);
    
    echo "empnotaord";
    $res1 = pg_exec("select * from empnotaord where m72_codordem = $m52_codordem and m72_codnota = $e69_codnota");
    db_criatabela($res1);

    echo "matestoqueitemnota";
    $res1 = pg_exec("select * from matestoqueitemnota where m74_codmatestoqueitem = $m71_codlanc");
    db_fieldsmemory($res1, 0);
    db_criatabela($res1);

    echo "empnota";
    $res1 = pg_exec("select * from empnota where e69_codnota = $e69_codnota");
    db_criatabela($res1);
    
    echo "matestoqueinimei";
    $res1 = pg_exec("select * from matestoqueinimei where m82_matestoqueitem = $m71_codlanc");
    db_fieldsmemory($res1, 0);
    db_criatabela($res1);
    
    echo "matestoqueini";
    $res1 = pg_exec("select * from matestoqueini where m80_codigo = $m82_matestoqueini");
    db_criatabela($res1);
  }
  exit;
}
// teste, pra ver se as transações não foram interrompidas durante a execução programática do script !
// db_criatabela(pg_query("select  * from db_config"));



?>