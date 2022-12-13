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

set_time_limit(0);
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_sql.php");
include("classes/db_notificacao_classe.php");
include("classes/db_notidebitos_classe.php");
include("dbforms/db_funcoes.php");
$clnotificacao = new cl_notificacao;
$clrotulo = new rotulocampo;
$clrotulo->label("k50_notifica");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if(isset($db_datausu)){
  if(!checkdate(substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4))){
     echo "Data para Cálculo Inválida. <br><br>";
     echo "Data deverá se superior a : ".date('Y-m-d',db_getsession("DB_datausu"));
	 exit;
  }
  if(mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4)) < 
     mktime(0,0,0,date('m',db_getsession("DB_datausu")),date('d',db_getsession("DB_datausu")),date('Y',db_getsession("DB_datausu"))) ){
     echo "Data não permitida para cálculo. <br><br>";
     echo "Data deverá se superior a : ".date('Y-m-d',db_getsession("DB_datausu"));
	 exit;
  }
  $DB_DATACALC = mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4));
}else{
  $DB_DATACALC = db_getsession("DB_datausu");
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script>
function js_listadebitos(notifica){
  document.form1.notifica.value = notifica;
  document.form1.submit();
  
}
</script>

<style type="text/css">
<!--
.borda {
	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: #000000;
}
-->
</style>
</head>
<body> 
<center>
<?

$clnotificacao = new cl_notificacao;

if(!isset($notifica)){
   $numpres = "";
   if($chave1=="matric"){
     $numpres .= " k55_matric = $chave ";
   }else if($chave1 == "inscr"){
     $numpres .= " k56_inscr = $chave ";
   }else if($chave1 == "numcgm"){
     $numpres .= " k57_numcgm = $chave ";
   }else if($chave1 == "numpre"){
     $numpres .= " notidebitos.k53_notifica = notificacao.k50_notifica and notidebitos.k53_numpre = $chave ";
     if(isset($chave2)){
        $numpres .= " and notidebitos.k53_numpar = $chave2";
     }
   }else{
     $numpres = "";
   }
   $sql = $clnotificacao->sql_query(""," distinct k50_notifica,k50_dtemite,k51_descr,k50_obs,db_usuarios.nome,k52_hora","",$numpres." and k52_id_usuario = db_usuarios.id_usuario and k50_instit = ".db_getsession('DB_instit') );
   db_lovrot($sql,10,"()","","js_listadebitos|k50_notifica");
?>
<form name="form1" method="post">
<input name="notifica" value="" style="visibility:hidden">
<input name="chave" value="<?=$chave?>" style="visibility:hidden">
<input name="chave1" value="<?=$chave1?>" style="visibility:hidden">
</form>
<?
}else{
  $clnotidebitos = new cl_notidebitos;
  $sql = "select n.k53_numpre,n.k53_numpar,
          case when a.k00_dtvenc is null then r.k00_dtvenc else a.k00_dtvenc end as k00_dtvenc,
          case when h.k01_descr  is null then hh.k01_descr else h.k01_descr  end as k01_descr,
	  case when t.k00_descr  is null then tt.k00_descr else t.k00_descr  end as k00_descr,
	  case when a.k00_numpre is null then 'Quitado'    else 'Aberto'   end::varchar as db_m_situacao
          from notidebitos n
	       left outer join arrecad a on  a.k00_numpre = n.k53_numpre and
	                                     a.k00_numpar = n.k53_numpar
	       left outer join arretipo t on a.k00_tipo = t.k00_tipo
	       left outer join histcalc h on a.k00_hist = h.k01_codigo
	       left outer join arrecant r on r.k00_numpre = n.k53_numpre and
	                                     r.k00_numpar = n.k53_numpar
               left outer join arretipo tt on a.k00_tipo = tt.k00_tipo
	       left outer join histcalc hh on a.k00_hist = hh.k01_codigo
	 where k53_notifica = $notifica
	 ";
  $result = pg_exec($sql);

  if(pg_numrows($result)>0){
    ?>
    <form name="form1" method="post" action="cai3_gerfinanc061.php?<?=$HTTP_SERVER_VARS['QUERY_STRING']?>">
   <table>
    <tr>
       <td nowrap title="<?=@$Tk50_notifica?>"><?=$Lk50_notifica?></td>
       <td>
       <?
       $k50_notifica = $notifica;
       db_input('k50_notifica',8,$Ik50_notifica,true,'text',3)
       ?>
       <input name="retorna" value="Retorna" type="submit">
       </td>
    </tr>
    </table>

 </form>
    <?
    $repassa = array("notifica"=>$notifica);
    db_lovrot($sql,50,"()","","","","NoMe",$repassa);
  }
}
?>
</center>
</body>
</html>