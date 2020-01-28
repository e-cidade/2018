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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_liclicitem_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS, 2);
$clliclicitem = new cl_liclicitem;
$clrotulo = new rotulocampo;
$clrotulo->label("l20_codigo");

// Funcao Locao para formatar um campo
function lic2_relitenhtml002_formatar($valor, $separador, $delimitador) {
  $del="";
  if($delimitador=="1") {
    $del = "\"";
  }else if($delimitador == "2") {
    $del = "'";
  }
  $valor = str_replace("\n"," ",$valor);
  $valor = str_replace("\r"," ",$valor);
  return "{$del}{$valor}{$del}{$separador}";
}

$campos = "l21_ordem,pc11_codigo,pc01_descrmater,pc11_resum,m61_descr,pc11_quant,pc11_vlrun,pcprocitem.pc81_codprocitem";
$campos = "distinct ".$campos;

$result_itens = $clliclicitem->sql_record($clliclicitem->sql_query_inf(null, "$campos", "l21_ordem", "l21_codliclicita = $l20_codigo and l20_instit = ".db_getsession("DB_instit")));
if ($clliclicitem->numrows == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Itens cadastrados para Licitação.');
  exit;
}

$clabre_arquivo = new cl_abre_arquivo("/tmp/licitacao_$l20_codigo.csv");
if ($clabre_arquivo->arquivo != false) {
  $vir = $separador;
  $del = $delimitador;
  fputs($clabre_arquivo->arquivo, lic2_relitenhtml002_formatar("ITEM",     $vir, $del));

  if ( $layout != 2 ) {
    fputs($clabre_arquivo->arquivo, lic2_relitenhtml002_formatar("SEQ ITEM", $vir, $del));
  }
  fputs($clabre_arquivo->arquivo, lic2_relitenhtml002_formatar("PRODUTO",  $vir, $del));
  fputs($clabre_arquivo->arquivo, lic2_relitenhtml002_formatar("QUANT.",   $vir, $del));
  fputs($clabre_arquivo->arquivo, lic2_relitenhtml002_formatar("UNID.",    $vir, $del));
  fputs($clabre_arquivo->arquivo, lic2_relitenhtml002_formatar("VALOR UNITÁRIO (R$)", $vir, $del));
  fputs($clabre_arquivo->arquivo, "\n");
  
  for ($w = 0; $w < $clliclicitem->numrows; $w ++) {
    db_fieldsmemory($result_itens, $w);
    fputs($clabre_arquivo->arquivo, lic2_relitenhtml002_formatar($l21_ordem                        , $vir, $del));
    if ( $layout != 2 ) {
      fputs($clabre_arquivo->arquivo, lic2_relitenhtml002_formatar($pc81_codprocitem                 , $vir, $del));
    }
    fputs($clabre_arquivo->arquivo, lic2_relitenhtml002_formatar("{$pc01_descrmater} {$pc11_resum}", $vir, $del));
    fputs($clabre_arquivo->arquivo, lic2_relitenhtml002_formatar($pc11_quant                       , $vir, $del));
    fputs($clabre_arquivo->arquivo, lic2_relitenhtml002_formatar($m61_descr                        , $vir, $del));
    fputs($clabre_arquivo->arquivo, lic2_relitenhtml002_formatar(db_formatar($pc11_vlrun,"v")      , $vir, $del));
    fputs($clabre_arquivo->arquivo, "\n");
  }
  
  fclose($clabre_arquivo->arquivo);

  echo "<script>";
  echo "  jan = window.open('db_download.php?arquivo=".$clabre_arquivo->nomearq."','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');";
  echo "  jan.moveTo(0,0);";
  echo "</script>";

}

?>