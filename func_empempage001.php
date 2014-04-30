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
include("classes/db_empagemov_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clempagemov = new cl_empagemov;
$clrotulo = new rotulocampo;
$clrotulo->label("o56_descr");
$clrotulo->label("o56_elemento");
$clempagemov->rotulo->label();
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <center>
      <input name="fechar" type="button" value="Fechar" onclick="parent.db_iframe_agendamento.hide();">
      <?
      //$sql = $clempagemov->sql_query_consemp(null,"e81_codage,e81_codmov,e82_codord,e83_descr as DL_Forma,e81_valor,e90_codgera as DL_Arquivo,e87_dataproc,e76_codret,e92_descrerro","e81_codage,e81_codmov","e60_numemp=$e60_numemp");
      $sql = $clempagemov->sql_query_consemp(null,"
             distinct e81_codage,
             e43_ordempagamento as DL_OP_auxiliar,
             e81_codmov,
             e80_data,
             e82_codord,
             e83_descr as DL_Conta_Pagadora,
             e81_valor,
             e90_codgera as DL_Arquivo,
             case when e96_descr = 'DIN' then 'DINHEIRO'
                  else case when e96_descr = 'CHE' then 'CHEQUE'
                       else case when e96_descr = 'TRA' then 'TRANSMISSÃO'
                            else case when e86_codmov is not null and e86_cheque <> '0' then 'CHEQUE'
                                 else e96_descr
                            end
                       end
                 end
             end as DL_Forma
             ,
             e86_cheque,
             case when e86_codmov is not null  then e86_data 
                  else e87_dataproc 
             end as e87_dataproc,
             e76_codret,
             case when e86_codmov is not null 
                   and e86_cheque <> '0' 
                   and round(e81_valor,2)-round(e53_valor,2)-round(e53_vlranu,2)-round(e53_vlrpag,2) <= 0
                   and corconf.k12_id is not null then 'MOVIMENTO PAGO'
              else 
                 ( case when e86_codmov is not null and e86_cheque <> '0'
                           and round(e81_valor,2)-round(e53_valor,2)-round(e53_vlranu,2)-round(e53_vlrpag,2) > 0 then 'A PAGAR'
                   when e86_codmov is not null and e86_cheque = '0' or e86_cheque is not null and corempagemov.k12_id is not null then 'MOVIMENTO PAGO'
                       else e92_descrerro end)
             end as e92_descrerro, e92_descrerro as dl_OcorrenciaRetorno","
             e81_codage,
             e81_codmov","
             e80_instit = " . db_getsession("DB_instit") . " and e60_numemp=$e60_numemp and e81_cancelado is null"
             );
      db_lovrot($sql,15,"()","","");
       ?>
      </center>
    </td>
  </tr>
</table>
</body>
</html>