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
?>
<form name="form1" method="post" action="vac4_integrapni001.php">
<center>
<fieldset style='width: 40%;'> <legend><b>Exportação de Dados PNI</b></legend>
<table>
  <tr>
    <td align="center">
      <fieldset style='width: 50%;'> <legend><b>Competência / Estrategia</b></legend>
      <table border="0" width="100%">
        <tr>
          <td nowrap >
            <b>Mês:</b>
          </td>
          <td nowrap="nowrap">
            <? $x = array('1'=>'Janeiro',
                          '2'=>'Fevereiro',
                          '3'=>'Março',
                          '4'=>'Abril',
                          '5'=>'Maio',
                          '6'=>'Junho',
                          '7'=>'Julho',
                          '8'=>'Agosto',
                          '9'=>'Setembro',
                          '10'=>'Outubro',
                          '11'=>'Novembro',
                          '12'=>'Dezembro');
               db_select('mes',$x,true,$db_opcao,"");?>
          </td>
          <td nowrap >
            <b>Ano:</b>
          </td>
          <td nowrap="nowrap">
            <? db_input('ano',4,null,true,'text',$db_opcao);?>
          </td>
          <td nowrap title="Sala de Vacinação">
            <b>Estratégia:</b>
          </td>
          <td nowrap="nowrap">
            <? $x = array('1'=>'Rotina',
                          '2'=>'Campanha');
               db_select('estrategia',$x,true,$db_opcao,"");?>
          </td>
        </tr>
      </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td align="center">
      <br>
      <?=db_criatermometro ( 'termometro', 'Concluido...', 'blue', 1, $sEstado);?>
      <br>
      <input type="Submit" Value="Gerar Arquivo" name="confirma" id="confirma" onclick="return js_valida();">
    </td>
  </tr>
</table>
</fieldset>
</center>
</form>
<script>

</script>