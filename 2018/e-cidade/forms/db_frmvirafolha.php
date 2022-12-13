<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
?>
<!--
<div id="processando" style="position:absolute; left:25px; top:106px; width:975px; height:400px; z-index:1; visibility: hidden; background-color: #FFFFFF; layer-background-color: #FFFFFF; border: 1px none #000000;">
-->
<div id="processando" style="position:absolute; left:0px; top:20px; width:1022px; height:581px; z-index:1; visibility: hidden; background-color: #FFFFFF; layer-background-color: #FFFFFF; border: 1px none #000000;">
<Table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="center" id="texto">
    </td>
  </tr>
</Table>
</div>
<table  align="center">
  <form name="form1" method="post" action="">
  <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td align="left" nowrap title="Período atual" >
      <strong>Período atual&nbsp;&nbsp;</strong>
    </td>
    <td>
    <?
    db_inputdata("dataii",$diaii,$mesii,$anoii,true,'text',3);
    ?>
    <b>a</b>
    <?
    db_inputdata("dataif",$diaif,$mesii,$anoii,true,'text',3);
    ?>
    </td>
  </tr>
  <tr>
    <td align="left" nowrap title="Novo período" >
      <strong><?=($db_opcao == 1?"Novo período":"Período retorno")?>&nbsp;&nbsp;</strong>
    </td>
    <td>
    <?
    db_inputdata("datafi",$diafi,$mesfi,$anofi,true,'text',3);
    ?>
    <b>a</b>
    <?
    db_inputdata("dataff",$diaff,$mesfi,$anofi,true,'text',3);
    ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align = "center"> 
      <input name="<?=($db_opcao == 1?'false':'true')?>" id="processar" type="button" value="<?=($db_opcao == 1?'Processar':'Desprocessar')?>" onclick="js_processar();" >
    </td>
  </tr>
  </form>
</table>
<script>
function js_mostrardiv(TorF,texto){
  if(TorF == true){
  	document.getElementById('processando').style.height = (screen.availHeight-155)+'px';
  	document.getElementById('processando').style.width = (screen.availWidth-15)+'px';
  	document.getElementById('processando').style.visibility = 'visible';
    document.getElementById('texto').innerHTML = '<h3>' + texto + '...</h3>';
  }else{
  	document.getElementById('processando').style.visibility = 'hidden';
  	document.getElementById('texto').innerHTML = '';
  }
}
function js_processar(){

  var lLotesFechados = <?php echo $lLotesFechados ?>;

  if (lLotesFechados) {
    if (!confirm("Existem lotes não processados, deseja continuar?")) {
      return false;
    }
  }

  x = document.form1;
  qry = "?dataii_dia="+x.dataii_dia.value;
  qry+= "&dataii_mes="+x.dataii_mes.value;
  qry+= "&dataii_ano="+x.dataii_ano.value;

  qry+= "&datafi_dia="+x.datafi_dia.value;
  qry+= "&datafi_mes="+x.datafi_mes.value;
  qry+= "&datafi_ano="+x.datafi_ano.value;

  qry+= "&dataif_dia="+x.dataif_dia.value;
  qry+= "&dataif_mes="+x.dataif_mes.value;
  qry+= "&dataif_ano="+x.dataif_ano.value;

  qry+= "&dataff_dia="+x.dataff_dia.value;
  qry+= "&dataff_mes="+x.dataff_mes.value;
  qry+= "&dataff_ano="+x.dataff_ano.value;

  if(document.getElementById("processar").name == "false"){
    if(confirm("Confirma fechamento do mês "+x.dataii_mes.value+"/"+x.dataii_ano.value+" ?")){
  	  js_mostrardiv(true,"Verificando dados para virada");
      js_OpenJanelaIframe('top.corpo','db_iframe_virafolha','dbforms/db_virafolha.php'+qry,'Virada da folha',false);
    }
  }else{
    qry+= "&desprocess="+document.getElementById("processar").name;
    if(confirm("Confirma cancelamento do mês "+x.dataii_mes.value+"/"+x.dataii_ano.value+" ?")){
  	  js_mostrardiv(true,"Verificando dados para cancelamento");
      js_OpenJanelaIframe('top.corpo','db_iframe_virafolha','dbforms/db_virafolha.php'+qry,'Virada da folha',false);
    }
  }

}
</script>