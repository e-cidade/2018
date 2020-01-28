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

//MODULO: Merenda
$clmer_intoleranciaalimentar->rotulo->label();
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tme33_i_codigo?>">
       <?=@$Lme33_i_codigo?>
    </td>
    <td> 
    <?db_input('me33_i_codigo',10,$Ime33_i_codigo,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme33_c_descr?>">
       <?=@$Lme33_c_descr?>
    </td>
    <td> 
     <?db_input('me33_c_descr',50,$Ime33_c_descr,true,'text',$db_opcao,"")?>    
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
       type="submit" id="db_opcao"
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
        <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="button" id="cancela" value="Cancelar" 
       onclick="js_cancela();"  <?=($db_botao==false?"disabled":"")?> >
<br><br>
<?
$chavepri                                = array("me33_i_codigo"=>@$me33_i_codigo);
$cliframe_alterar_excluir->chavepri      = $chavepri;
$cliframe_alterar_excluir->sql           = $clmer_intoleranciaalimentar->sql_query(null,'*',null,"");
$cliframe_alterar_excluir->campos        ="me33_i_codigo,me33_c_descr";
$cliframe_alterar_excluir->legenda       ="Intolerância Alimentar";
$cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
$cliframe_alterar_excluir->textocabec    = "darkblue";
$cliframe_alterar_excluir->textocorpo    = "black";
$cliframe_alterar_excluir->fundocabec    = "#aacccc";
$cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
$cliframe_alterar_excluir->iframe_width  = "100%";
$cliframe_alterar_excluir->iframe_height = "200";
$cliframe_alterar_excluir->opcoes        = 1;
$cliframe_alterar_excluir->iframe_alterar_excluir(1);
?>
</form>
<script>
function js_cancela() {
    location.href='mer1_mer_intoleranciaalimentar001.php';
}
</script>