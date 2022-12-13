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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
require("libs/db_liborcamento.php");
include("classes/db_matordemitement_classe.php");

$clmatordemitement = new cl_matordemitement;

db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_GET_VARS,2);

if (isset($incluir)&&$incluir!=""){
db_inicio_transacao();
$sqlerro=false;

$clmatordemitement->m54_codmatordemitem=$codmatordemitem;
$clmatordemitement->m54_codpcmater=$codpcmater;
$clmatordemitement->m54_codmatmater=$codmatmater;
$clmatordemitement->m54_quantidade=$quantidade;
$clmatordemitement->m54_valor_unitario=$valor_unitario;
$clmatordemitement->m54_quantmulti=$quant_multi;
$clmatordemitement->m54_codmatunid=$codunid;
$clmatordemitement->incluir(null);
$erro=$clmatordemitement->erro_msg;
if ($clmatordemitement->erro_status==0){
  $sqlerro=true;
}
db_fim_transacao($sqlerro);
if ($clmatordemitement->erro_status==0){
  db_msgbox("Erro ao lançar itens!!");
  db_msgbox($erro);
}else{
  echo "<script>parent.itens.document.form1.submit();</script>";
}
}else if (isset($excluir)&&$excluir!=""){
  if (isset($atualizaquant)&&$atualizaquant!=""){
      $sql="delete from matordemitement";
      $result_deleta=pg_exec($sql);
  }else{
    db_inicio_transacao();
    $sqlerro=false;
    $clmatordemitement->excluir(@$codent);
    $erro=$clmatordemitement->erro_msg;
    if ($clmatordemitement->erro_status==0){
     $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
    if ($clmatordemitement->erro_status==0){
      db_msgbox("Erro ao excluir itens!!");
      db_msgbox($erro);
    }else{
       echo "<script>parent.itens.document.form1.submit();</script>";
    }
  }
}
?>