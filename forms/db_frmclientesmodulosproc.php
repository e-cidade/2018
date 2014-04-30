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



include("classes/db_db_syscadproceditem_classe.php");

//MODULO: atendimento
$clclientesmodulosproc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("at74_codcli");
$clrotulo->label("descrproced");

$cldb_syscadproceditem = new cl_db_syscadproceditem;

$db_opcao = 2;

$sql = "
        select pp.codproced, pp.descrproced
        from db_menu m
             inner join db_syscadproceditem p on p.id_item = m.id_item
             inner join db_syscadproced pp on pp.codproced = p.codproced
        where modulo = $id_modulo
        union 
        select pp.codproced, pp.descrproced
        from db_menu m
             inner join db_syscadproceditem p on p.id_item = m.id_item_filho
             inner join db_syscadproced pp on pp.codproced = p.codproced
        where modulo = $id_modulo
        order by descrproced
        ";
$result = db_query($sql);

$at75_seqclimod = $sequencial;

?>
<form name="form1" method="post" action="">
<table border="0" width='100%'>
<?
db_input('at75_seqclimod',6,$Iat75_seqclimod,true,'hidden',3,"");

for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $var1= "at75_codproced_".$codproced;
  global $$var1;
  $$var1 = $codproced;
  $var2= "descrproced_".$codproced;
  global $$var2;
  $$var2 = $descrproced;
  
  $sql = "select at75_data,at75_obs, at75_sequen
          from clientesmodulosproc 
               inner join clientesmodulos on at75_seqclimod = at74_sequencial
          where at75_seqclimod = $sequencial
            and at75_codproced = $codproced
          ";

  $res = db_query($sql);
  if(pg_numrows($res)>0){
    db_fieldsmemory($res,0);
    $var3= "at75_data_".$codproced;
    global $$var3;
    $$var3 = $at75_data;
    $at75_data_dia = substr($at75_data,8,2);
    $at75_data_mes = substr($at75_data,5,2);
    $at75_data_ano = substr($at75_data,0,4);
    $var4= "at75_obs_".$codproced;
    global $$var4;
    $$var4= $at75_obs;
  }else{
    $at75_data_dia = "";
    $at75_data_mes = "";
    $at75_data_ano = "";
    $var4= "at75_obs_".$codproced;
    global $$var4;
    $$var4= "";
    $at75_sequen = 0;
  }

?>
  <tr width="100%">
    <td> 
    <?
    db_input('at75_codproced_'.$codproced,10,$Iat75_codproced,true,'text',3)
    ?>
    <?
    db_input('descrproced_'.$codproced,40,$Idescrproced,true,'text',3,'')
    ?>
    </td>
    <td> 
    <?
    db_inputdata('at75_data_'.$codproced,@$at75_data_dia,@$at75_data_mes,@$at75_data_ano,true,'text',$db_opcao,"")
    ?>
    </td>
    <td> 
    <?
    db_textarea('at75_obs_'.$codproced,1,100,$Iat75_obs,true,'text',$db_opcao,"")
    ?>
    </td>
    <td> 
    <input name="usuarios" value="Usuarios" type="button" onclick="js_procedimentos_usuarios(<?=$codproced?>,'<?=$descrproced?>',<?=$at75_sequen?>)">
    <input name="usuarios_sel" value="" type="hidden">
    <input name="at76_sequen_<?=$codproced?>" value="<?=$at76_sequen?>" type="hidden">
    </td>
  </tr>
<?
}
?>
  </table>
<input name="atualizar" type="submit" id="atualizar" value="Atualizar"  >
</form>
<script>
function js_procedimentos_usuarios(procedimento,nomeproced,sequen){
  if(sequen==0){
    alert('Devera ser incluido primeiramente uma data para esta procedimento.');
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_clientesmodulos','db_iframe_clientes_usu','func_db_usuclientestre.php?codproced='+procedimento+'&cliente=<?=@$cliente?>&nomeproced='+nomeproced+'&at76_sequen='+sequen,'Pesquisa',true);
  }
}
</script>