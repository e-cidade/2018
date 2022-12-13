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
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style type="text/css">
<!--
td {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
}
input {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        height: 17px;
        border: 1px solid #999999;
}
-->
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC">

<?
db_postmemory($HTTP_POST_VARS);

$query = "SELECT 
w11_revisado as \"Revisado\",
w11_login as \"Login do Usuário\",
w11_nome,
w11_cgccpf,
w11_ender as endereço,
w11_munic as municipio,
w11_uf,
w11_cep,
to_char(w11_cadast,'DD-MM-YYYY') as \"Data do Cadastramento\",
w11_telef as telefone,
w11_ident as \"Carteira de Identidade\",
w11_bairro,
w11_incest as \"Inscrição Estadual\",
w11_telcel as \"Telefone Celular\",
w11_email,
w11_endcon as \"Endereço pra Contato\",
w11_muncon as \"Municipio pra Contato\",
w11_baicon as \"Bairro pra Contato\",
w11_ufcon as \"UF pra Contato\",
w11_cepcon as \"CEP pra Contato\",
w11_telcon as \"Telefone pra Contato\",
w11_celcon as \"Telefone Celular pra Contato\",
w11_emailc as \"Email pra Contato\"
FROM db_cgmatualiza WHERE 2 > 1";

if(empty($usr_nome) && empty($usr_login) && empty($data_dia) && empty($data_mes) && empty($data_ano) && empty($cgccpf) && empty($numcgm) && empty($endereco) && empty($data_nula))
  $query .= " ";
//  $query .= " AND revisado >= (CURRENT_DATE - 10)";
else {
  if(!empty($usr_nome))
    $query .= " AND upper(w11_nome) like upper('$usr_nome%')";
  if(!empty($usr_login))
    $query .= " AND upper(w11_login) like upper('$usr_login%')";
//  if((!empty($data_dia) && !empty($data_mes) && !empty($data_ano)) || !empty($data_nula))
//    $query .= " AND w11_revisado ".($data_nula != "1"?" >= '$data_ano-$data_mes-$data_dia'":" is null");
  if(!empty($cgccpf))
    $query .= " AND upper(w11_cgccpf) like upper('$cgccpf%')";
  if(!empty($numcgm))
    $query .= " AND upper(w11_numcgm) like upper('$numcgm%')";
  if(!empty($endereco))
    $query .= " AND upper(w11_ender) like upper('$endereco%')";
}

db_lov($query,100);
/*
$db_corcabec  = "cyan";
$db_corlinha1 = "#66CCFF" ;
$db_corlinha2 = "#03BCCB" ;
if (!isset($offset))
  db_browse($query,'',10,0,"1&usr_nome=".@$usr_nome."&usr_login=".@$usr_login."&data_dia=".@$data_dia."&data_mes=".@$data_mes."&data_ano=".@$data_ano."&cgccpf=".@$cgccpf."&numcgm=".@$numcgm."&endereco=".@$endereco);
else
  db_browse($query,'',10,$offset,"1&usr_nome=".@$usr_nome."&usr_login=".@$usr_login."&data_dia=".@$data_dia."&data_mes=".@$data_mes."&data_ano=".@$data_ano."&cgccpf=".@$cgccpf."&numcgm=".@$numcgm."&endereco=".@$endereco);
*/
?>        
</body>
</html>