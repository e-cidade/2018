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
  include("classes/db_issplanit_classe.php");
  include("classes/db_issplanitinscr_classe.php");
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  $clissplanit = new cl_issplanit;
  $clissplanitinscr = new cl_issplanitinscr;
  $clissplanit->rotulo->label();
  $clissplanitinscr->rotulo->label();
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<style>
.cabec {
       text-align: center;
       font-size: 10px;
       font-weight: bold;
       background-color:#aacccc ;
       color: darkblue;
       
       }
.corpo {
       background-color:#ccddcc;       
       text-align: center;
       }
				            
</style>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post" action="iss3_consissvar003.php">
<table width="94%" height="90%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="top">
      <table border="1"  >
        <tr class="cabec" bgcolor="<?=$color?>" >
	  <td>&nbsp;<?=$RLq31_inscr?></td>
	  <td>&nbsp;<?=$RLq21_nome?></td>
	  <td>&nbsp;<?=$RLq21_cnpj?></td>
	  <td>&nbsp;<?=$RLq21_servico?></td>
	  <td>&nbsp;<?=$RLq21_valor?></td>
	  <td>&nbsp;<?=$RLq21_valorser?></td>
	  <td>&nbsp;<?=$RLq21_aliq?></td>
	  <td>&nbsp;<?=$RLq21_nota?></td>
	  <td>&nbsp;<?=$RLq21_serie?></td>
	</tr>  
<?
      //  $result00=$clissplanit->sql_record($clissplanit->sql_query_file("","issplanit.* ","q21_nota","q21_planilha  = $q21_planilha and  q21_status = 1 "));
      // die($clissplanit->sql_query_file("","issplanit.*","q21_nota","q21_planilha  = $q21_planilha and  q21_status = 1 "));
        $sql1 = "select issplanit.*,q31_inscr 
				        from issplanit 
				        left join issplanitinscr on q31_issplanit = q21_sequencial
				        where q21_planilha = $q21_planilha and q21_status = 1 
								order by q21_nota ";
							//	die("xxxxxxxxxx".$sql1);
				$result1 = pg_query($sql1);			
        $linhas  = pg_num_rows($result1);
        $notas="";
        $susteni="";
	for($c=0; $c<$linhas; $c++){
	  db_fieldsmemory($result1,$c);
?>
	  
  <tr class="corpo" bgcolor="<?=$color?>" >
	  <td>&nbsp;<?=$q31_inscr?></td>
	  <td>&nbsp;<?=$q21_nome?></td>
	  <td>&nbsp;<?=$q21_cnpj?></td>
	  <td>&nbsp;<?=$q21_servico?></td>
	  <td>&nbsp;<?=db_formatar($q21_valor,"f")?></td>
	  <td>&nbsp;<?=db_formatar($q21_valorser,"f")?></td>
	  <td>&nbsp;<?=$q21_aliq?></td>
	  <td>&nbsp;<?=$q21_nota?></td>
	  <td>&nbsp;<?=$q21_serie?></td>
	</tr>  
<?
	}
?>
	  
      </table>
    </td>
  </tr>
</table>
</form>
</body>
</html>