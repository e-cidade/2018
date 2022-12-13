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

//MODULO: Vacinas
$clvac_vacina->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("m60_descr");
$clrotulo->label("vc04_i_codigo");
?>
<fieldset style='width: 75%;'> <legend><b>Vacina</b></legend>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tvc06_i_codigo?>">
       <?=@$Lvc06_i_codigo?>
    </td>
    <td> 
     <?
       db_input('vc06_i_codigo',10,$Ivc06_i_codigo,true,'text',3,"");
       db_input('vc06_c_descr',40,$Ivc06_c_descr,true,'text',3,"");
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc06_t_administacao?>">
       <?=@$Lvc06_t_administacao?>
    </td>
    <td> 
     <?db_textarea('vc06_t_administacao',3,50,$Ivc06_t_administacao,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc06_t_material?>">
       <?=@$Lvc06_t_material?>
    </td>
    <td> 
     <?db_textarea('vc06_t_material',3,50,$Ivc06_t_material,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc06_c_prazo?>">
       <?=@$Lvc06_c_prazo?>
    </td>
    <td> 
     <?db_input('vc06_c_prazo',20,$Ivc06_c_prazo,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <fieldset style='width: 60%;'> <legend><b>Prazo da Aplicação</b></legend>
      <table>
        <tr>
          <td nowrap title="<?=@$Tvc06_n_quant?>">
            <?=@$Lvc06_n_quant?>
          </td>
          <td> 
           <?db_input('vc06_n_quant',10,$Ivc06_n_quant,true,'text',$db_opcao,"")?>
          </td>
          <td nowrap title="<?=@$Tvc06_i_tipo?>">
            <?=@$Lvc06_i_tipo?>
          </td>
          <td> 
           <?
             $x = array('1'=>'HORAS','2'=>'DIAS');
             db_select('vc06_i_tipo',$x,true,$db_opcao,"");
           ?>
          </td>
        </tr>
      </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc06_t_obs?>">
       <?=@$Lvc06_t_obs?>
    </td>
    <td> 
    <?db_textarea('vc06_t_obs',3,50,$Ivc06_t_obs,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  </table>
  </center>
<input name="alterar" type="submit" id="db_opcao" value="Alterar" <?=($db_botao==false?"disabled":"")?> >
</form>
</fieldset>