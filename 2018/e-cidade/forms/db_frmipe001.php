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
$clrotulo->label("r11_codipe");
$clrotulo->label("r36_anousu");
$clrotulo->label("r36_mesusu");
?>
<form name="form1" method="post" action="">
<center>
<table>
  <tr>
    <td nowrap title="Ano / Mês de competência" align="right">
      <b>Ano / Mês:</b>
    </td>
    <td> 
      <?
      $r36_anousu = db_anofolha('DB_anousu');
      db_input('r36_anousu',4,$Ir36_anousu,true,'text',1)
      ?>
      &nbsp;&nbsp;<b>/</b>&nbsp;&nbsp;
      <?
      $r36_mesusu = db_mesfolha('DB_mesusu');
      db_input('r36_mesusu',4,$Ir36_mesusu,true,'text',1)
      ?>
    </td>
  </tr>
  <tr>
    <td align='right'><?=$Lr11_codipe?></td>
    <td align='left'>
      <?
      $codipe = $r11_codipe;
      db_input('r11_codipe', 10, $Ir11_codipe, true, 'text', 1,"","codipe");
      ?>
    </td>
  </tr>
  <tr>
    <td align='right'><b>Identificador:</b></td>
    <td align='left'>
      <?
      $identificador = 1;
      $arr_identificador = Array('1'=>'Manutenção','3'=>'Inclusão');
      db_select("identificador",$arr_identificador,true,1);
      ?>
    </td>
  </tr>
  <tr>
    <td align='right'><b>Unifica I.P.E:</b></td>
    <td align='left'>
      <?
      $arr_unifica  = Array('f'=>'Não', 't'=>'Sim');
      db_select("unifica_ipe",$arr_unifica,true,1);
      ?>
    </td>
  </tr>
</table>
<input name="incluir" type="submit" id="db_opcao" value="Gerar IPE" onclick="return js_verificacampos();">
</center>
</form>
<script>
function js_verificacampos(){
  if(document.form1.r36_anousu.value == ""){
    alert("Informe o ano.");
    document.form1.r36_anousu.focus();
    return false;
  }else if(document.form1.r36_mesusu.value == ""){
    alert("Informe o mês.");
    document.form1.r36_mesusu.focus();
    return false;
  }else if(document.form1.codipe.value == ""){
    alert("Informe o código do IPE.");
    document.form1.codipe.focus();
    return false;
  }else{
    return true;
  }
}
</script>