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
include("libs/db_utils.php");
include("classes/db_mer_tpcardapioturma_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clmer_tpcardapioturma = new cl_mer_tpcardapioturma;

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
<form name="form1" method="post" action="">
<center>
   <fieldset style="width:95%"><legend><b>Escolas Atendidas</b></legend>
   <table border="1" align="left" width="95%">
    <tr>
     <td>
      <table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
       <tr>        
       </tr>
       <?      
        $sSql  = "SELECT ed18_i_codigo,
                        ed18_c_nome,
                        me27_i_ano,
                        (select count(*) from turma
                          inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo
                          inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat                          
                          inner join calendario on ed52_i_codigo = ed57_i_calendario
                          inner join mer_tpcardapioturma on me28_i_cardapioescola = me32_i_codigo
                                                        and me28_i_serie = ed223_i_serie
                         where me27_i_ano = ed52_i_ano
                         and me32_i_escola = ed57_i_escola) as qtdturma,
                        (select count(*) from matricula
                          inner join turma on ed57_i_codigo = ed60_i_turma
                          inner join matriculaserie on ed221_i_matricula = ed60_i_codigo
                                                   and ed221_c_origem = 'S'  
                          inner join calendario on ed52_i_codigo = ed57_i_calendario
                          inner join mer_tpcardapioturma on me28_i_cardapioescola = me32_i_codigo
                                                        and me28_i_serie = ed221_i_serie
                         where ed60_c_situacao = 'MATRICULADO'
                         and me27_i_ano = ed52_i_ano
                         and me32_i_escola = ed57_i_escola) as qtdaluno
                        FROM mer_cardapio
                   inner join mer_tipocardapio on mer_tipocardapio.me27_i_codigo = mer_cardapio.me01_i_tipocardapio
                   inner join mer_cardapioescola on mer_cardapioescola.me32_i_tipocardapio = mer_tipocardapio.me27_i_codigo
                   inner join escola on escola.ed18_i_codigo = mer_cardapioescola.me32_i_escola
                  WHERE me01_i_codigo = $me06_i_cardapio
                  ORDER BY ed18_c_nome 
                 ";  
        $rsEscola = pg_query($sSql);
        //db_criatabela($result);
        $iLinhas = pg_num_rows($rsEscola);
        if ($iLinhas>0) {
        	
          $cor1 = "#dbdbdb";
          $cor2 = "#f3f3f3";
          ?>
          <tr>
          <td>
          <b>Escolas</b>
          </td>
          <td>
          <b>Turmas</b>
          </td>
          <td>
          <b>Alunos</b>
          </td>
          </tr>
          <?
          for ($c=0;$c<$iLinhas;$c++) {
          	
            $oEscola = db_utils::fieldsmemory($rsEscola,$c);
            ?>
            <tr bgcolor="<?=$cor2?>">
            <td width="40%">
              <?=$oEscola->ed18_c_nome?>
            </td>
             <td>
              <a href="javascript:js_matriculas(<?=$me06_i_cardapio?>,<?=$oEscola->ed18_i_codigo?>,<?=$oEscola->me27_i_ano?>)"><?=$oEscola->qtdturma?></a>             
            </td>
             <td>
             <?=$oEscola->qtdaluno?>              
            </td>
            </tr>
            <?           
          }
        } else {
        	
          ?>
          <table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
           <tr bgcolor="#EAEAEA">
            <td class='aluno'>NENHUMA TURMA NESTE CALENDÁRIO.</td>
           </tr>
          </table>
          <?
          
        }
         ?>
         </td>
         </tr>
          </table>
         </td>
        </tr>
        

   </table>
   </fieldset>
   </center>
</form>
</body>
</html>
<script>
function js_matriculas(refeicao,escola,ano){
	  
  js_OpenJanelaIframe('','db_iframe_matriculas','func_mer_turmarefeicao.php?escola='+escola+'&ano='+ano+'&refeicao='+refeicao,'Turmas',true);
  location.href = "#topo";
	  
}
</script>