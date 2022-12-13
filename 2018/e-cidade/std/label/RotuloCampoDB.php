<?php 

//class rotulocampo_original {
class RotuloCampoDB {
  //|00|//rotulocampo
  //|10|//Esta classe gera as variáveis de controle do sistema de uma determinada tabela
  //|15|//[variavel] = new rotulocampo($campo);
  //|20|//campo  : Nome do campo a ser pesquisada
  //|40|//Gera todas as variáveis de controle do campo
  //|99|//Exemplo:
  //|99|//[variavel] = new rotulocampo("z01_nome");
  //|99|//ou
  //|99|//[variavel] = new rotulocampo();
  //|99|//[variavel]->label("z01_nome");
  function label($campo = "") {
    //#00#//label
    //#10#//Este método gera o label do campo
    //#15#//label($campo);
    //#20#//nome  : Nome do campo a ser gerado as variáveis de controle
    //#99#//Nome das variáveis geradas:
    //#99#//"I" + nome do campo -> Tipo de consistencia javascript a ser gerada no formulário (|aceitatipo|)
    //#99#//"A" + nome do campo -> Variavel para determinar o autocomplete no objeto (!autocompl|)
    //#99#//"U" + nome do campo -> Variavel para preenchimento obrigatorio do campo (|nulo|)
    //#99#//"G" + nome do campo -> Variavel para colocar se letras do objeto devem ser maiusculo ou não (|maiusculo|)
    //#99#//"S" + nome do campo -> Variavel para colocar mensagem de erro do javascript de preenchimento de campo (|rotulo|)
    //#99#//"L" + nome do campo -> Variavel para colocar como label de campo (|rotulo|)
    //#99#//                       Coloca o campo com a primeira letra maiuscula e entre tags strong (negrito) (|rotulo|)
    //#99#//"T" + nome do campo -> Variavel para colocat na tag title dos campos (|descricao|)
    //#99#//"M" + nome do campo -> Variavel para incluir o tamanho da propriedade maxlength dos campos (|tamanho|)
    //#99#//"N" + nome do campo -> Variavel para controle da cor de fundo quando o  campo aceitar nulo (|nulo|)
    //#99#//                       style="background-color:#E6E4F1";
    //#99#//"RL"+ nome do campo -> Variavel para colocar como label de campo nos relatorios
    //#99#//"TC"+ nome do campo -> Variavel com o tipo de campo do banco de dados
    //#99#//"LS"+ nome do campo -> Variavel para colocar como label de campo sem as tags STRONG

    $sCampoTrim = trim($campo);
    $result = pg_exec("select c.descricao,
                              c.rotulo,
                              c.nomecam,
                              c.tamanho,
                              c.nulo,
                              c.maiusculo,
                              c.autocompl,
                              c.conteudo,
                              c.aceitatipo,
                              c.valorinicial,
                              c.rotulorel
                         from db_syscampo c
                        where c.nomecam = '${sCampoTrim}'");
    $numrows = pg_numrows($result);
    for ($i = 0; $i < $numrows; $i ++) {

      /// variavel com o tipo de campo
      $variavel = trim("I".pg_result($result, $i, "nomecam"));
      global $$variavel;
      $$variavel = pg_result($result, $i, "aceitatipo");

      /// variavel para determinar o autocomplete
      $variavel = trim("A".pg_result($result, $i, "nomecam"));
      global $$variavel;
      if (pg_result($result, $i, "autocompl") == 'f') {
        $$variavel = "off";
      } else {
        $$variavel = "on";
      }
      /// variavel para preenchimento obrigatorio
      $variavel = trim("U".pg_result($result, $i, "nomecam"));
      global $$variavel;
      $$variavel = pg_result($result, $i, "nulo");
      /// variavel para colocar maiusculo
      $variavel = trim("G".pg_result($result, $i, "nomecam"));
      global $$variavel;
      $$variavel = pg_result($result, $i, "maiusculo");



      /// variavel para colocar no erro do javascript de preenchimento de campo
      $variavel = trim("S".pg_result($result, $i, "nomecam"));
      global $$variavel;
      $$variavel = pg_result($result, $i, "rotulo");
      /// variavel para colocar como label de campo
      $variavel = trim("L".pg_result($result, $i, "nomecam"));
      global $$variavel;
      $$variavel = "<strong>".ucfirst(pg_result($result, $i, "rotulo")).":</strong>";

      /// variavel para colocar como label de campo
      $variavel = trim("LS".pg_result($result, $i, "nomecam"));
      global $$variavel;
      $$variavel = ucfirst(pg_result($result, $i, "rotulo"));

      /// vaariavel para colocat na tag title dos campos
      $variavel = trim("T".pg_result($result, $i, "nomecam"));
      global $$variavel;
      $$variavel = ucfirst(pg_result($result, $i, "descricao"))."\n\nCampo:".pg_result($result, $i, "nomecam");

      /// variavel para incluir o tamanhoda tag maxlength dos campos
      $variavel = trim("M".pg_result($result, $i, "nomecam"));
      global $$variavel;
      $$variavel = pg_result($result, $i, "tamanho");

      /// variavel para controle de campos nulos
      $variavel = trim("N".pg_result($result, $i, "nomecam"));
      global $$variavel;
      $$variavel = pg_result($result, $i, "nulo");
      if ($$variavel == "t")
        $$variavel = "style=\"background-color:#E6E4F1\"";
      else
        $$variavel = "";

      /// variavel para colocar como label de campo nos relatorios
      $variavel = trim("RL".pg_result($result, $i, "nomecam"));
      global $$variavel;
      $$variavel = ucfirst(pg_result($result, $i, "rotulorel"));
      /// variavel para colocar o tipo de campo
      $variavel = "TC".trim(pg_result($result, $i, "nomecam"));
      global $$variavel;
      $$variavel = pg_result($result, $i, "conteudo");

      if ('DBtxt' == substr(trim(pg_result($result, $i, "nomecam")), 0, 5)) {
        $variavel = trim(pg_result($result, $i, "nomecam"));
        global $$variavel;
        $$variavel = pg_result($result, $i, "valorinicial");
      }

    }
  }
  //|XX|//
}
