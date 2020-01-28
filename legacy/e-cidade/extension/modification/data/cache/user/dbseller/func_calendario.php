<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

function checa_eventos($dia,$mes,$ano){
  return 'false';
}

class calendario{
   var $sem;//Array com os dias da semana como índice
   var $mes;//Array com os meses do ano
   var $nome_objeto_data;
   var $shutdown_function = "";

   function inicializa(){//Atribui valores para $sem e $mes.
       $this->sem=array('Sun'=>1,'Mon'=>2,'Tue'=>3,'Wed'=>4,'Thu'=>5,'Fri'=>6,'Sat'=>7);
       $this->mes=array('1'=>'JANEIRO','2'=>'FEVEREIRO','3'=>'MARÇO','4'=>'ABRIL','5'=>'MAIO','6'=>'JUNHO','7'=>'JULHO','8'=>'AGOSTO','9'=>'SETEMBRO','10'=>'OUTUBRO','11'=>'NOVEMBRO','12'=>'DEZEMBRO');
   }

   function aux($i){//Complementa a tabela com espaços em branco
      $retval="";
      for($k=0;$k < $i;$k++){
         $retval.="<td width=\"20\">&nbsp;</td>";
      }
      return $retval;
   }
   function cria($dia,$mes,$ano,$marca=0){

      $this->inicializa();
      $last  = date ("d", mktime (0,0,0,$mes+1,0,$ano));/*Inteiro do ultimo dia do mês*/
      if($last<$dia) {
        $dia = $last;
      }
      $verf=date ("d/n/Y", mktime (0,0,0,$mes,$dia,$ano));/*Corrige qualquer data invalida*/
      $pieces=explode("/",$verf);
      $dia=$pieces[0];
      $mes=$pieces[1];
      $ano=$pieces[2];
      $diasem=date ("D", mktime (0,0,0,$mes,1,$ano));/*String com dia da semana em inglês*/
      $str = "";
      if($this->sem[$diasem] != 1){/*Se dia semana diferente de domingo,completa com colunas em branco*/
         $valor=$this->sem[$diasem]-1;
         $str="<tr align=\"center\" >".$this->aux($valor);
      }
      for($i=1;$i < ($last+1);$i++){       //; pega todos os dias do mes informado....
         $diasem=date ("D", mktime (0,0,0,$mes,$i,$ano));
         if($this->sem[$diasem] == 1){
            $str.="<tr align=\"center\" >";
            $s="$i";
         }else{
            $s="$i";
         }
         $data_script = "$ano-$mes-$s";
         $str.="<td     ";
         if($marca != 0){  // marca o dia atual em laranja
            if($dia == $i){
               $str.= " class=\"diaatual\" ";  // marcar o dia atual
            }
	 }
	 if($this->sem[$diasem] == 1 || $this->sem[$diasem] == 7){
            $str.="  class=\"fimsemana\" ";
         }
         $str .="  width=\"25\">
                 <a  onclick=\"return janela($s,$mes,$ano);\">$s</a>
              </td>";

         if($this->sem[$diasem] == 7){
            $str.="</tr>";
         }
      }
      $diasem=date ("D", mktime (0,0,0,$mes,$last,$ano));
      if($this->sem[$diasem] != 7){
         $valor=7-$this->sem[$diasem];
         $str=$str.$this->aux($valor)."</tr>";
      }

      $str="
  <link href=\"estilos.css\" rel=\"stylesheet\" type=\"text/css\">
  <table border=\"1\"  cellspacing=\"0\" cellpadding=\"0\" class=\"dbdatepicker\">
  <tr>
   <td>
     <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
     <tr align=\"center\">
       <td width=\"100%\" colspan=\"7\" nowrap>
        <FONT SIZE='1' FACE='Verdana' COLOR='black'>
           <a href=\"func_calendario.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&mes_solicitado=".($mes)."&ano_solicitado=".($ano-1)."\"> << </a>
           	  <span class=\"label\">$ano</span>
	         <a href=\"func_calendario.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&mes_solicitado=".($mes)."&ano_solicitado=".($ano+1)."\"> >> </a>
        </font>
       </td>
     </tr>
     <tr align=\"center\">
       <td width=\"100%\" colspan=\"7\" nowrap>
        <FONT SIZE='1' FACE='Verdana' COLOR='black'>
         <a href=\"func_calendario.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&ano_solicitado=".($ano)."&mes_solicitado=".($mes-1)."\"> << </a>
         <span class=\"label\">".$this->mes[$mes]."</span>
         <a href=\"func_calendario.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&ano_solicitado=".($ano)."&mes_solicitado=".($mes+1)."\"> >> </a>

	</FONT>
       </td>
     </tr>
     <tr align=\"center\">
       <td width=\"100%\" colspan=\"7\" nowrap>
        <FONT SIZE='1' FACE='Verdana' COLOR='black'>
         <a  onclick=\"return janela_zera();return false;\" class=\"zeraData\">Zera Data</a>
        </FONT>
       </td>
     </tr>
     <tr align=\"center\">
       <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>D </font></td>
       <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>S </font></td>
       <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>T </font></td>
       <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>Q </font></td>
       <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>Q </font></td>
       <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>S </font></td>
       <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>S </font></td>
       </tr>
       ".$str."
     </table>
     </td>
     </tr>
    </table>
     ";
      echo $str;
   }
}

$clcalendario=new calendario;
if (!isset($mes_solicitado)){
  $mes_solicitado = date("n",db_getsession("DB_datausu"));
}
if (!isset($ano_solicitado)){
  $ano_solicitado = date("Y",db_getsession("DB_datausu"));
}
if(isset($shutdown_function)){
  $clcalendario->shutdown_function = $shutdown_function;
}
$clcalendario->nome_objeto_data = $nome_objeto_data;

$clcalendario->cria(date("d",db_getsession("DB_datausu")),date("$mes_solicitado"),date("$ano_solicitado"),1);

?>
<script>
function janela(d,m,a){
  <?
  echo "parent.document.getElementById('".$nome_objeto_data."_dia').value = (d<10?'0'+d:d);\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_mes').value = (m<10?'0'+m:m);\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_ano').value = a;\n";
  echo "parent.js_comparaDatas".$nome_objeto_data."((d<10?'0'+d:d),(m<10?'0'+m:m),a);\n";
  echo "parent.iframe_data_".$nome_objeto_data.".hide();\n";

  if (isset($shutdown_function) && ($shutdown_function!='none')){
      echo $shutdown_function."\n";
  }

  ?>
}
function janela_zera(){
  <?
  echo "parent.document.getElementById('".$nome_objeto_data."').value     = '';\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_dia').value = '';\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_mes').value = '';\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_ano').value = '';\n";
  echo "parent.iframe_data_".$nome_objeto_data.".hide();\n";

  if (isset($shutdown_function) && ($shutdown_function!='none')){
      echo $shutdown_function."\n";
  }

  ?>
}

</script>