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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_cgm_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_matordem_classe.php");
include("classes/db_matordemanu_classe.php");
include("classes/db_matordemitem_classe.php");
include("classes/db_empempenho_classe.php");
include("classes/db_matestoqueitemoc_classe.php");


$clmatordem = new cl_matordem;
$clmatordemanu = new cl_matordemanu;
$clmatordemitem = new cl_matordemitem;
$clempempenho = new cl_empempenho;
$clcgm = new cl_cgm;
$cldbdepart = new cl_db_depart;
$clmatestoqueitemoc = new cl_matestoqueitemoc;





$clrotulo = new rotulocampo;
$clempempenho->rotulo->label();
$clcgm->rotulo->label();
$clmatordem->rotulo->label();
$cldbdepart->rotulo->label();
$clmatordemanu->rotulo->label();

$where=" 1=1 ";

if (isset($e60_numcgm) && $e60_numcgm!=''){
  $where= "m51_numcgm = $e60_numcgm ";
}

if (isset($e60_numemp) && $e60_numemp!=''){
  $where.= " and m52_numemp = $e60_numemp ";
}

if(isset($m51_codordem) && $m51_codordem!=''){
  $where.= " and m51_codordem = $m51_codordem";
}
     $sql = "select m51_codordem,
                    m51_data,
		                m51_depto,
								    m51_numcgm,
								    z01_nome,
								    descrdepto,
								    m53_data,
								    m51_obs,
								    m51_valortotal,
								    pc50_descr 
	         from matordem 

	                left join matordemanu on m53_codordem = m51_codordem
		              left join matordemitem on m52_codordem = m51_codordem
		              left join empempenho on m52_numemp = e60_numemp
		              inner join cgm on z01_numcgm = m51_numcgm 
		              inner join db_depart on coddepto = m51_depto
		              left join pctipocompra on empempenho.e60_codcom = pctipocompra.pc50_codcom 
		              
		             
		              
		       where $where ";
    
      //die($sql); 
     
     $result = pg_exec($sql); 
     if (pg_numrows($result)==0){
          db_redireciona('db_erros.php?fechar=true&db_erro=Este registro não possui ordem de compra.');
     
     }
     db_fieldsmemory($result,0);

  ?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<style>
<?$cor="#999999"?>
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
.bordas{
         border: 1px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #cccccc;
}
</style>
<form name="form1" method="post" action="" >
<center>
<table border='0'>
  <tr align = 'left'>
    <td align="left">
      <table border="0">
        <tr>
          <td nowrap align="right" title="<?=@$Te60_numcgm?>"><?=@$Le60_numcgm?></td>
          <td> 
            <?
              db_input('m51_numcgm',20,$Im51_numcgm,true,'text',3)
            ?>
          </td>
          <td nowrap align="right" title="<?=@$z01_nome?>"><?=@$Lz01_nome?></td>
          <td>
            <?
              db_input('z01_nome',48,$Iz01_nome,true,'text',3)
            ?>
          </td>
	</tr>
        <tr>
          <td nowrap align="right" title="<?=@$Tm51_codordem?>"><b>Ordem de Compra:</b></td>
          <td>
	    <?
              db_input('m51_codordem',20,$Im51_codordem,true,'text',3)
	    ?>
	  </td>
          <td nowrap align="right" title="<?=@$Tm53_data?>"><b>Data da anula&ccedil;&atilde;o:</b></td>
          <td> 
	    <?		
       $ano=substr(@$m53_data,0,4);
	     $mes=substr(@$m53_data,5,2);
	     $dia=substr(@$m53_data,8,2);
	     db_inputdata('dataemis',"$dia","$mes","$ano",true,'text',3);
	    ?>
          </td>
        </tr>
        
        <tr>
          <td nowrap align="right" title="<?=@$Tm51_data?>"><b>Data da emiss&atilde;o:</b></td>
          <td> 
	    <?
             $ano=substr($m51_data,0,4);
	     $mes=substr($m51_data,5,2);
	     $dia=substr($m51_data,8,2);
	    db_inputdata('dataemis',"$dia","$mes","$ano",true,'text',3);
            ?>
          </td>
          <td nowrap align="right" title="<?=@$descrdepto?>">
             <?=@$Lcoddepto?>
          <td> 
             <?
             db_input('m51_depto',6,$Im51_depto,true,'text',3);
             db_input('descrdepto',40,$Idescrdepto,true,'text',3);
             ?>
          </td>
        </tr>
        
        <tr>
        <td nowrap align="right">
             <b>Tipo da Compra:</b>
        </td>
        
        <td colspan="3">
          <?
            db_input('pc50_descr',87,'',true,'text',3);
          ?>
        </td>
       </tr>
   
  <tr> 
	<td align='right'><b>Obs:</b></td>
   <td colspan='3' align='left'>
	 <? 
	 db_textarea("m51_obs","","85",$Im51_obs,true,'text',3);
	 
	 ?>
	  </td>
        
	</tr>  
        <tr>
          <td colspan='4' align='center'>
	  </td>
        </tr>
      </table>
    </td>
    </tr>
    <tr>
   <td align='center' valign='top' colspan='1' align='center'>
  <?if(isset($m51_codordem)){?>  
     <table>
       <tr>
         <td>
           <iframe name="itens" id="itens" src="forms/db_frmmatordemitemconsulta.php?m51_codordem=<?=$m51_codordem?>&e60_numemp=<?=@$e60_numemp?>&e60_codemp=<?=@$e60_codemp?>" width="820" height="150" marginwidth="0" marginheight="0" frameborder="0"></iframe>
         </td>
       </tr>
       <tr>
         <td align='right'>
	 <?$lanca=0;
	   $lancado=0;
	   $result_lancado=$clmatestoqueitemoc->sql_record($clmatestoqueitemoc->sql_query(null,null,"sum(m71_valor) as soma",
	      null,"m52_codordem=$m51_codordem and m73_cancelado is false"));
           if ($clmatestoqueitemoc->numrows!=0){
	     db_fieldsmemory($result_lancado,0);
	     $lanca=$soma;
	     $lancado=db_formatar($soma,'f');
	   }else{
	     $lanca=0;
	     $lancado=db_formatar('0','f');
	   }
	 ?>
	 <b>
	 <?
	  $alancar=$m51_valortotal-$lanca;
	  $alancar=db_formatar($alancar,'f');
          $valortotal=db_formatar($m51_valortotal,'f');
	 ?>
	 Total da Ordem:<?db_input('valortotal',15,'',true,'text',3)?>Valor Lançado:<?db_input('lancado',15,'',true,'text',3)?>A Lançar:<?db_input('alancar',15,'',true,'text',3)?>
	 </b>
	 </td>
       </tr>
     </table>
  <?}?>  
   </td>
  </tr>
</table>
</center>
</form>
</body>
</html>