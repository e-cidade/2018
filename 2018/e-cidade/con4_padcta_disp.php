<?
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


class cta_disp {
  var $arq = null;

  function cta_disp($header){
    umask(74);
    $this->arq = fopen("tmp/CTA_DISP.TXT",'w+');
    fputs($this->arq,$header);
    fputs($this->arq,"\r\n");
  }

  function processa($instit=1,$data_ini="",$data_fim="",$tribinst="",$subelemento="") {

    global $db21_idtribunal,$contador,$instituicoes,$contador,$codcla,$c60_codcla,$nomeinst,$c60_estrut,$c61_codigo,$c63_banco,$c63_agencia,$c63_conta,$c61_instit;
    $sele = " ($instit) ";

    /*
     0101 - camara
     0201 - pref
    */

    $sql="select c60_estrut as c60_estrut,
                    c61_instit,
					c61_codigo,
					case when c63_banco is null then '00'
					   else c63_banco end as c63_banco,
					case when c63_agencia is null then '000'
					   else c63_agencia end as c63_agencia,
					case when c63_conta is null then '0000'
					   else c63_conta end as c63_conta,
					c60_codcla
      from conplano
	      left outer join conplanoconta on c63_codcon = c60_codcon and c63_anousu=c60_anousu
	      left outer join conplanoreduz on c61_codcon = c60_codcon and c60_anousu=c61_anousu
	 where c61_instit in $sele";
    if (USE_PCASP) {
      $sql .= " and ( c60_estrut like '11111%' or c60_estrut like '114%' )";
    } else {
      $sql .= " and ( c60_estrut like '1111%' or c60_estrut like '115%' )";
    }
    $sql .= " and c60_anousu=".db_getsession("DB_anousu")."
	 order by c60_estrut,c61_instit
	 ";

    $result = db_query($sql);


    for($x = 0; $x < pg_numrows($result);$x++){
      db_fieldsmemory($result,$x);


      //if($c60_codcla>0 and $c60_codcla < 4)
      //  $cla = $c60_codcla;
      //else
      //  $cla = 9;
      $resintit = db_query("select db21_idtribunal
                            from db_config
                                inner join db_tipoinstit on db21_codtipo=db21_tipoinstit
                            where codigo = $c61_instit");
      if (pg_numrows($resintit)==0){
        echo "Parametro db21_idtribunal não configurado na tabela db_config->db_tipoinstit";
        exit;
      } else {
        db_fieldsmemory($resintit,0);
      }
      if ($db21_idtribunal == 01 )
        $cla = 1; // prefeitura
      elseif ($db21_idtribunal == 02 )
        $cla = 2; // camara
      elseif ($db21_idtribunal == 05 )
        $cla = 3; // RPPS
      elseif ($db21_idtribunal == 06 )
        $cla = 3; // RPPS
      else
        $cla = 9;

      /*
      if($c61_instit<4)
        $cla = $c61_instit;
      else
        $cla = 9;
      */
      //------------------------------------

      $line  = formatar($c60_estrut,20,'n');


      //if ($c61_instit==1){
      //  $line .="0201";  // gab-prefeito
      //  $cla = 1;
      //}else if ($c61_instit==2){
      //  $line .="0101";  // camara
      //  $cla = 2;
      //}else {
      //  $line .= formatar($orgaotrib,4,'c');
      //  $cla = 3;
      //}
      $line .= $instituicoes[$c61_instit];

      $line .= formatar($c61_codigo,4,'n');
      $line .= formatar(trim($c63_banco),5,'n');
      $line .= formatar(trim($c63_agencia),5,'n');
      $line .= formatar(trim(str_replace('-','',str_replace('.','',trim($c63_conta)))),20,'n');

      $sEstrutural = substr($c60_estrut, 0, 7);
      if ($sEstrutural == '1111101') {
        $line .= '1'; // caixa
      } else if (
        $sEstrutural == '1111106' ||
        $sEstrutural == '1111116' ||
        $sEstrutural == '1111130' ||
        $sEstrutural == '11112'
      ) {

        $line .= '2'; // banco conta movimento

      } else if ($sEstrutural == '1111150'){
        $line .= '3'; // banco conta aplicacao
      } else if (substr($c60_estrut,0,11) == '11251020001' ||
        substr($c60_estrut,0,11) == '11251020002' ||
        substr($c60_estrut,0,11) == '11251020003') {
        $line .= '4'; // deposito sentencas judiciais
      } else if(substr($c60_estrut,0,11) == '11251020004' ||
        substr($c60_estrut,0,11) == '11251020005' ||
        substr($c60_estrut,0,11) == '11251020006') {
        $line .= '5'; // depositos sentencas judiciais rp
      } else {
        $line .= '2'; // depositos sentencas judiciais rp
      }


      /*
      if($c60_codcla>0 and $c60_codcla < 4)
        $line .= '1';//formatar($c60_codcla,1,'n');
      else
        $line .= formatar(9,1,'n');
      */
      // abaixo, ajuste do recurso 50
      if($c61_codigo==50)
        $cla = '3';


      $line .= formatar($cla,1,'n');
      $contador ++;

      fputs($this->arq,$line);
      fputs($this->arq,"\r\n");

    }

    // trailer
    $contador = espaco(10-(strlen($contador)),'0').$contador;
    $line = "FINALIZADOR".$contador;
    fputs($this->arq,$line);
    fputs($this->arq,"\r\n");

    fclose($this->arq);

    $teste ="true";
    return $teste;

  }

}