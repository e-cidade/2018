<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_far_retirada_classe.php");
require_once("classes/db_far_retiradaitens_classe.php");

db_postmemory($HTTP_POST_VARS);

$clrotulo            = new rotulocampo;
$clfar_retiradaitens = new cl_far_retiradaitens;
$clfar_retirada      = new cl_far_retirada;

$result = $clfar_retiradaitens->sql_record($clfar_retiradaitens->sql_query_retiradaitens(null,"fa06_i_retirada,m60_descr,m77_lote,m77_dtvalidade,m61_descr,case when fa09_f_quant is null then fa06_f_quant else fa09_f_quant end as fa09_f_quant","fa09_f_quant","fa06_i_retirada=$chavepesquisaconsulta"));
db_fieldsmemory($result,0);
?>
<html>
  <head>
    <title>Retirada</title>
  </head>
  <body>
    <center>
      <br><br><br>
	    <fieldset style="width:95%"><legend><b>Retirada</b></legend>
          <table border="0" width="90%" id="table1" cellspacing="0" cellpadding="0">
            <tr>
              <td colspan="2"><font size="4"><b> Retirada: &nbsp;&nbsp;<?=$fa06_i_retirada?></b></font></td>
            </tr>
            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2">
                <table border="0" width="100%" id="table2" cellspacing="4" cellpadding="0">
                  <tr>
                    <td bgcolor="#E6E6E6"><b><font size="4">Código</b></font></td>
                    <td bgcolor="#E6E6E6"><b><font size="4">Medicamento</b></font></td>
                    <td bgcolor="#E6E6E6"><b><font size="4">Lote</b></font></td>
                    <td bgcolor="#E6E6E6" width="96"><b><font size="4">Validade</b></font></td>
                    <td bgcolor="#E6E6E6" width="98"><b><font size="4">Unidade</b></font></td>
                    <td bgcolor="#E6E6E6" width="86"><b><font size="4">Quantidade</b></font></td>
                  </tr>
                  <?
                  for($i=0;$i<$clfar_retiradaitens->numrows;$i++){

                    db_fieldsmemory($result,$i); 
                  ?>	
                  <tr>
                    <td bgcolor="#E6E6E6"><?=$fa06_i_retirada?></td>
                    <td bgcolor="#E6E6E6"><?=$m60_descr?></td>
                    <td bgcolor="#E6E6E6"><?=$m77_lote?></td>
                    <td bgcolor="#E6E6E6" width="96"><?=db_formatar($m77_dtvalidade,'d')?></td>
                    <td bgcolor="#E6E6E6" width="98"><?=$m61_descr ?></td>
                    <td bgcolor="#E6E6E6" width="86"><?=$fa09_f_quant?></td> 
                  </tr>
                  <?
                  }
                  ?>
                </table>
              </td>
            </tr>
            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td width="60%">&nbsp;</td>
              <td width="34%"><input name="recibo" type="button" id="recibo" value="Recibo Retirada" onclick='js_recibo();'></td>
              <td width="34%"><input name="voltar" type="button" id="voltar" value="Voltar" onclick='js_voltar();'></td>
            </tr>
            <tr>
              <td width="66%">
                <p align="right">&nbsp;</td>
              <td width="34%">&nbsp;</td>
            </tr>
          </table>
        </fieldset>
     </center>
  </body>
</html>
<script>
function js_recibo(){		

  var query = "";
  query     = "ini=" + <?=$fa06_i_retirada?>;
  query    += "&fim=" + <?=$fa06_i_retirada?>;
  jan       = window.open('far1_atendretira001.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
  
}
function js_voltar(){

  parametros    = '?consultar&chavepesquisaconsulta=<?=$chavepesquisaconsulta?>&fa04_i_cgsund=<?=$fa04_i_cgsund?>&z01_v_nome=<?=$z01_v_nome?>';
  parametros   += '&data1=<?=@$data1?>&data2=<?=@$data2?>';
  location.href ='far3_consultapaciente002.php'+parametros;

}
</script>