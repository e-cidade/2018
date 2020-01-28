<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
  $clempnota->e69_numero     = "S/ Número"; 
  $clempnota->e69_numemp     = $e60_numemp ; 
  $clempnota->e69_id_usuario = $id_usuario; 
  $clempnota->e69_dtnota     = $data_hoje; 
  $clempnota->e69_dtrecebe   = $data_hoje;
  $clempnota->incluir(null);
  $e69_codnota = $clempnota->e69_codnota;
  if($clempnota->erro_status == 0){
    $erro_msg = $clempnota->erro_msg;
    $sqlerro  = true;
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
 *
 * Determina que é inclusão de uma entrada de ordem de compra
 *
 */
if($sqlerro == false){
  $clmatestoqueini->m80_data     = $data_hoje;
  //$clmatestoqueini->m80_hora     = date('H:i',db_getsession("DB_datausu")); 
  $clmatestoqueini->m80_hora     = date('H:i:s');
  $clmatestoqueini->m80_coddepto = $m51_depto;
  $clmatestoqueini->m80_login    = $id_usuario;
  $clmatestoqueini->m80_codtipo  = '12';
  $clmatestoqueini->m80_obs      = $m80_obs;
  $clmatestoqueini->incluir(null);
  if($clmatestoqueini->erro_status == 0){
    $erro_msg = $clmatestoqueini->erro_msg;
    $sqlerro  = true;
  }else{
    $codini = $clmatestoqueini->m80_codigo;
  }
}

/**
 *
 * Loop que busca itens de ordem de compra e inclui no estoque
 *
 */
$result_matordemitem = $clmatordemitem->sql_record($clmatordemitem->sql_query(null,"*",null,"m52_codordem=$m51_codordem"));
$numrows_matordemitem = $clmatordemitem->numrows;
for($i=0;$i<$numrows_matordemitem;$i++){
  db_fieldsmemory($result_matordemitem,$i);
  /**
   *
   * Busca itens da ordem de compra e inclui na tabela matmater (materiais do almoxarifado)
   *
   */
  $codmatordemitem =  $m52_codlanc;
  $result_newmater = $clmatordemitem->sql_record($clmatordemitem->sql_query($codmatordemitem, "pc01_codmater,pc01_descrmater,pc01_complmater"));
  if($clmatordemitem->numrows > 0){
    db_fieldsmemory($result_newmater,0);
  }

  /**
   *
   * Busca descrição dos itens no empenho
   *
   */
  $result_resum=$clmatordemitem->sql_record($clmatordemitem->sql_query_servico($codmatordemitem,"e62_descr"));
  if($clmatordemitem->numrows>0){
    db_fieldsmemory($result_resum,0);
  }

  $e62_descr       = str_replace(chr(10), " ", $e62_descr);
  $pc01_descrmater = str_replace(chr(10), " ",$pc01_descrmater);
  $descr_newmater  = $pc01_descrmater." ".@$e62_descr;
  $descr_newmater  = addslashes($descr_newmater);
  $descr_newmater  = str_replace(chr(10), " ", $descr_newmater);

  $result_material_existe = $cltransmater->sql_record($cltransmater->sql_query_file(null, "m63_codmatmater as codmatmater", "", "m63_codpcmater = ".$pc01_codmater));
  $numrows_material_existe = $cltransmater->numrows;
  if($numrows_material_existe == 0){
    $clmatmater->m60_ativo      = 't';
    $clmatmater->m60_descr      = substr($descr_newmater,0,80);
    $clmatmater->m60_codmatunid = 1;
    $clmatmater->m60_quantent   = 1;
    $clmatmater->m60_codant     = "";  
    $clmatmater->incluir(null);
    if($clmatmater->erro_status == 0){
      $erro_msg = $clmatmater->erro_msg;
      $sqlerro  = true;
      break;
    }
    $codmatmater = $clmatmater->m60_codmater;
 
    /**
     *
     * Inclui a unidade de saída do material
     * Sempre 1
     *
     */
    $clmatmaterunisai->incluir($codmatmater,1);
    if($clmatmaterunisai->erro_status == 0){
      $erro_msg = $clmatmaterunisai->erro_msg;
      $sqlerro  = true;
      break;
    }
 
    /**
     *
     * Liga material no almoxarifado à tabela de materias do compras
     *
     */
    $cltransmater->m63_codpcmater  = $pc01_codmater;
    $cltransmater->m63_codmatmater = $codmatmater;
    $cltransmater->incluir();
    if($cltransmater->erro_status == 0){
      $erro_msg = $cltransmater->erro_msg;
      $sqlerro  = true;
      break;
    }
  }else{
    db_fieldsmemory($result_material_existe, 0);
  }

  /**
   *
   * Verifica existência de material no estoque do departamento informado
   * 
   */
  $result_estoque_existe  = $clmatestoque->sql_record($clmatestoque->sql_query_file("","*","","m70_coddepto=$m51_depto and m70_codmatmater=$codmatmater"));
  $numrows_estoque_existe = $clmatestoque->numrows;
  if($sqlerro == false){

    /**
     *
     * Se existir irá setar a variavel de classe m70_codigo com o código do do estoque , caso contrário, irá incluir um estoque novo
     * 
     */
    if($numrows_estoque_existe == 0){
      $clmatestoque->m70_codmatmater = $codmatmater;
      $clmatestoque->m70_coddepto    = $m51_depto;
      $clmatestoque->m70_quant       = "0";
      $clmatestoque->m70_valor       = "0";
      $clmatestoque->incluir(null);
      if($clmatestoque->erro_status == 0){
        $sqlerro = true;
        $erro_msg = $clmatestoque->erro_msg;
        break;
      }
    }else{
      db_fieldsmemory($result_estoque_existe,0);
      $clmatestoque->m70_codigo = $m70_codigo;
    }
  }
  $codestoque = $clmatestoque->m70_codigo;

  /**
   *
   * Incluir no matestoqueitem
   *
   */
  if($sqlerro == false){
    $clmatestoqueitem->m71_codmatestoque = $codestoque;
    $clmatestoqueitem->m71_data          = $data_hoje;
    $clmatestoqueitem->m71_quantatend    = "0.00";
    $clmatestoqueitem->m71_quant         = "$e55_quant";
    $clmatestoqueitem->m71_valor         = "$e55_vlrun";
    $clmatestoqueitem->incluir(null);
    if($clmatestoqueitem->erro_status == 0){
      $erro_msg = $clmatestoqueitem->erro_msg;
      $sqlerro  = true;
      break;
    }
    $codigoestoitem = $clmatestoqueitem->m71_codlanc;
  }

  /**
   *
   * Incluir no matestoqueitemlanc
   *
   */
  if($sqlerro == false){
    $clmatestoqueitemlanc->m95_id_usuario = $id_usuario;
    $clmatestoqueitemlanc->m95_data       = $data_hoje;
    $clmatestoqueitemlanc->m95_verificado = "false";
    $clmatestoqueitemlanc->incluir($codigoestoitem);
    if($clmatestoqueitemlanc->erro_status == 0){
      $erro_msg = $clmatestoqueitemlanc->erro_msg;
      $sqlerro  = true;
      break;
    }
  }

  /**
   *
   * Ex.: Se forem cadastradas duas caixas de dez canetas
   *     - m75_quant será 2 e o m75_quantmult será 10
   *
   */
  if($sqlerro == false){
    $clmatestoqueitemunid->m75_codmatestoqueitem = $codigoestoitem;
    $clmatestoqueitemunid->m75_codmatunid        = 1;// unidade
    $clmatestoqueitemunid->m75_quant             = "$e55_quant";
    $clmatestoqueitemunid->m75_quantmult         = 1;
    $clmatestoqueitemunid->incluir($codigoestoitem);
    if($clmatestoqueitemunid->erro_status == 0){
      $erro_msg = $clmatestoqueitemunid->erro_msg;
      $sqlerro  = true;
      break;
    }
  } 

  /**
   *
   * Liga o item à ordem de compra
   *
   */
  if ($sqlerro == false){    
    $clmatestoqueitemoc->incluir($codigoestoitem,$codmatordemitem);
    if($clmatestoqueitemoc->erro_status == 0){
      $erro_msg = $clmatestoqueitemoc->erro_msg;
      $sqlerro  = true;
      break;
    }
  }

  /**
   *
   * Liga o item à nota
   *
   */
  if($sqlerro == false){    
    $clmatestoqueitemnota->incluir($codigoestoitem,$clempnotaord->m72_codnota);
    if($clmatestoqueitemnota->erro_status == 0){
      $erro_msg = $clmatestoqueitemnota->erro_msg;
      $sqlerro  = true;
      break;
    }
  }

  /**
   *
   * Ligação entre matestoqueini e matestoqueitem
   *
   */
  if($sqlerro == false){
    if(isset($codigoestoitem) && $codigoestoitem!=""){  
      $clmatestoqueinimei->m82_matestoqueini = $codini;
      $clmatestoqueinimei->m82_matestoqueitem= $codigoestoitem;
      $clmatestoqueinimei->m82_quant         = $e55_quant;
      $clmatestoqueinimei->incluir(null);
      if($clmatestoqueinimei->erro_status == 0){
        $erro_msg = $clmatestoqueinimei->erro_msg;
        $sqlerro  = true;
        break;
      }
    }
  }
}

/**
 * ordem de pagamento = Nota de liquidação
 */
if ($sqlerro==false){
    $clpagordem->e50_numemp     = $e60_numemp;
    $clpagordem->e50_data       = date("Y-m-d",db_getsession("DB_datausu"));
    $clpagordem->e50_obs        = "Nota de liquidação";
    $clpagordem->e50_id_usuario = db_getsession("DB_id_usuario");
    $clpagordem->e50_hora       = db_hora();
    $clpagordem->e50_anousu     = db_getsession("DB_anousu");
    $clpagordem->incluir(null);
    if($clpagordem->erro_status==0){
       $sqlerro=true;
       $erro_msg = $clpagordem->erro_msg;
    }else{
       $e50_codord =  $clpagordem->e50_codord ;
    }   
}
/**
* lança pagordemele = empnotaele
*/
if ($sqlerro==false){
    $clpagordemele->e53_codord  = $e50_codord;
    $clpagordemele->e53_codele  = $e64_codele;
    $clpagordemele->e53_valor   = $e60_vlremp;
    $clpagordemele->e53_vlranu  = '0.00' ;
    $clpagordemele->e53_vlrpag  = '0.00' ;
    $clpagordemele->incluir($e50_codord,$e64_codele);
    if($clpagordemele->erro_status==0){
        $sqlerro=true;
        $erro_msg = $clpagordemele->erro_msg;
    }
}  
/**
* ligação da nota de liquidação (pagordem) com a nota do fornecedor ( empnota )
*/
if($sqlerro==false){ 
    $clpagordemnota->e71_codord  = $e50_codord;
    $clpagordemnota->e71_codnota = $clempnota->e69_codnota;
    $clpagordemnota->e71_anulado = "false";
    $clpagordemnota->incluir($e50_codord,$clpagordemnota->e71_codnota);
    if($clpagordemnota->erro_status==0){
         $erro_msg=$clpagordemnota->erro_msg;
         $sqlerro=true;
    }	
}

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