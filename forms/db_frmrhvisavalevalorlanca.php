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

//MODULO: pessoal
$clrhvisavale->rotulo->label();
$clrhvisavalecad->rotulo->label();
$clrotulo = new rotulocampo;
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
<td>
      <fieldset><legend><b>Processar valor mensal </b></legend>
<table>
  <tr>
    <td nowrap title="Ano / Mês de competência" align="right">
      <b>Ano / Mês:</b>
    </td>
    <td> 
      <?
      if(!isset($rh49_anousu)){
        $rh49_anousu = db_anofolha();
      }
      if(!isset($rh49_mesusu)){
        $rh49_mesusu = db_mesfolha();
      }
      db_input('rh49_anousu',4,$Irh49_anousu,true,'text',3,"")
      ?>
      &nbsp;/&nbsp;
      <?
      db_input('rh49_mesusu',2,$Irh49_mesusu,true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr >
    <td align="left" nowrap title="Digite o Ano / Mes para levar em consideração os afastamentos" >
    <strong>Ano / Mês Afastamentos:</strong>
    </td>
    <td>
      <?
       if(!isset($anoafa)){
         $anoafa = db_anofolha();
       }
       db_input('anoafa',4, 1,true,'text',2,'');
      ?>
      &nbsp;/&nbsp;
      <?
       if(!isset($mesafa)){
         $mesafa = db_mesfolha();
       }
       db_input('mesafa',2, 1,true,'text',2,'');
      ?>
    </td>
  </tr>
</table>  
</fieldset>
</td>  
</table>
</center>
<input name="incluir" type="submit" id="db_opcao" value="Processar" <?=($db_botao==false?"disabled":"")?> onclick="return js_testacampos();">
</form>
<script>
function js_testacampos(){
  if(confirm("Todos os registros serão processados.Deseja continuar?")){
    return true;
  }else{
  return false;
  }
}
</script>