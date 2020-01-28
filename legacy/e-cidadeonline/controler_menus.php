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

session_start();
global $HTTP_SESSION_VARS;
include ("libs/db_stdlib.php");
db_postmemory($HTTP_SERVER_VARS);

if (!isset ($login)) { // sem estar logado
	
  if (!isset ($DB_LOGADO)) { // entra aqui tb
    // echo("destruindo sessoes!");
    session_destroy();
  } else {
    session_register("DB_acesso");
  }
} else {
  session_register("DB_acesso");
}

if($menu == "issqn"){
   echo "<script>location.href = 'digitainscricao.php';</script>";
}
if($menu == "issqn_retido"){
   echo "<script>location.href = 'digitaissqn.php';</script>";
}
if($menu == "cad_empresa"){
   echo "<script>location.href = 'listaescritorios.php';</script>";
}
if($menu == "certidao"){
   echo "<script>location.href = 'certidao.php';</script>";
}
if($menu == "imoveis"){
   echo "<script>location.href = 'digitamatricula_arapiraca.php';</script>";
}
if($menu == "itbi_urbano"){
   echo "<script>location.href = 'itbi_urbano.php';</script>";
}
if($menu == "itbi_rural"){
   echo "<script>location.href = 'itbi_rural.php';</script>";
}
if($menu == "itbi_consulta"){
   echo "<script>location.href = 'itbi_consulta.php';</script>";
}
if($menu == "dai"){
   echo "<script>location.href = 'digitadae.php';</script>";
}
if($menu == "contribuinte"){
   echo "<script>location.href = 'digitacontribuinte.php';</script>";
}	
if($menu == "aidof"){
   echo "<script>location.href = 'digitaaidof.php';</script>";
}
if($menu == "protocolo"){
   echo "<script>location.href = 'digitaconsultaprocesso.php';</script>";
}
if($menu == "licitacao"){
   echo "<script>location.href = 'lic_menu.php';</script>";
}
if($menu == "fornecedor"){
   echo "<script>location.href = 'digitafornecedor.php';</script>";
}
if($menu == "orcamento"){
   echo "<script>location.href = 'for_orcamento.php';</script>";
}
if($menu == "ordem_compra"){
   echo "<script>location.href = 'for_ordemcompra.php';</script>";
}
?>