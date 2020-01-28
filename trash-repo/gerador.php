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
include("classes/db_db_certidaoweb_classe.php");
$clcertidao = new cl_db_certidaoweb;
$result = $clcertidao->sql_record($clcertidao->sql_query("","*","","codcert = $cod"));
db_fieldsmemory($result,0);
$dtvenc = str_replace("-","",$cerdtvenc);
$data = getdate();
$dia = $data['mday'];
$mes = $data['mon'];
$ano = $data['year'];
if (strlen($mes)=="1")
  $mes = "0".$mes;
if (strlen($dia)=="1")
  $dia = "0".$dia;
$dtat = $ano.$mes.$dia;
if(strcmp($dtat, $dtvenc)<"0"){
  if($cerweb == true){
    echo pg_result($result,0,'cerhtml');
  }else{
    echo pg_result($result,0,'cercertidao');
  }
}elseif(strcmp($dtat, $dtvenc)>"0"){
//passou 90 dias
  include("certidaovencida.php");
}
?>