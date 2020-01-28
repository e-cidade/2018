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

//MODULO: Agua
//CLASSE DA ENTIDADE aguacoletorexportadados
class cl_aguacoletorexportadados {
   // cria variaveis de erro
   var $rotulo     = null;
   var $query_sql  = null;
   var $numrows    = 0;
   var $numrows_incluir = 0;
   var $numrows_alterar = 0;
   var $numrows_excluir = 0;
   var $erro_status= null;
   var $erro_sql   = null;
   var $erro_banco = null;
   var $erro_msg   = null;
   var $erro_campo = null;
   var $pagina_retorno = null;
   // cria variaveis do arquivo
   var $x50_sequencial = 0;
   var $x50_aguacoletorexportadados = 0;
   var $x50_aguacoletorexporta = 0;
   var $x50_matric = 0;
   var $x50_rota = 0;
   var $x50_tipo = 0;
   var $x50_codlogradouro = 0;
   var $x50_codbairro = 0;
   var $x50_codhidrometro = 0;
   var $x50_zona = 0;
   var $x50_ordem = 0;
   var $x50_responsavel = null;
   var $x50_nomelogradouro = null;
   var $x50_numero = 0;
   var $x50_letra = null;
   var $x50_complemento = null;
   var $x50_nomebairro = null;
   var $x50_cidade = null;
   var $x50_estado = null;
   var $x50_quadra = 0;
   var $x50_economias = 0;
   var $x50_categorias = null;
   var $x50_areaconstruida = 0;
   var $x50_nrohidro = null;
   var $x50_numpre = 0;
   var $x50_natureza = null;
   var $x50_dtleituraatual_dia = null;
   var $x50_dtleituraatual_mes = null;
   var $x50_dtleituraatual_ano = null;
   var $x50_dtleituraatual = null;
   var $x50_diasleitura = 0;
   var $x50_dtleituraanterior_dia = null;
   var $x50_dtleituraanterior_mes = null;
   var $x50_dtleituraanterior_ano = null;
   var $x50_dtleituraanterior = null;
   var $x50_consumo = 0;
   var $x50_mediadiaria = 0;
   var $x50_consumopadrao = 0;
   var $x50_consumomaximo = 0;
   var $x50_vencimento_dia = null;
   var $x50_vencimento_mes = null;
   var $x50_vencimento_ano = null;
   var $x50_vencimento = null;
   var $x50_valoracrescimo = 0;
   var $x50_valordesconto = 0;
   var $x50_valortotal = 0;
   var $x50_linhadigitavel = null;
   var $x50_codigobarras = null;
   var $x50_imprimeconta = 0;
   var $x50_valor_m3_excesso = 0;
   var $x50_leituracoletada = 0;
   var $x50_observacao = null;
   var $x50_contaimpressa = 0;
   var $x50_avisoleiturista = null;

   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 x50_sequencial = int8 = Código Exportação Dados
                 x50_aguacoletorexportadados = int8 = Código Exportação Dados
                 x50_aguacoletorexporta = int4 = Código Exportação
                 x50_matric = int4 = Matrícula
                 x50_rota = int4 = Rota
                 x50_tipo = int4 = Código
                 x50_codlogradouro = int4 = Cod. Logradouro
                 x50_codbairro = int4 = Cód. do Bairro
                 x50_codhidrometro = int4 = Cód Hidrometro
                 x50_zona = int8 = Código
                 x50_ordem = int4 = Ordem de Registro da Matricula
                 x50_responsavel = varchar(100) = Nome do Responsavel
                 x50_nomelogradouro = varchar(40) = Logradouro
                 x50_numero = int4 = Número Logradouro
                 x50_letra = char(1) = Letra Logradouro
                 x50_complemento = varchar(30) = Complemento
                 x50_nomebairro = varchar(40) = Nome Bairro
                 x50_cidade = varchar(40) = Cidade
                 x50_estado = char(2) = Estado
                 x50_quadra = int4 = Quadra
                 x50_economias = int4 = Economias
                 x50_categorias = varchar(40) = Categoria
                 x50_areaconstruida = float8 = Área Construida
                 x50_nrohidro = varchar(20) = Numero Hidrometro
                 x50_numpre = int4 = Numpre
                 x50_natureza = varchar(20) = Natureza
                 x50_dtleituraatual = date = Data Leitura Atual
                 x50_diasleitura = int4 = Dias Leitura
                 x50_dtleituraanterior = date = Data Leitura Anterior
                 x50_consumo = int4 = Consumo
                 x50_mediadiaria = int4 = Media Diaria
                 x50_consumopadrao = float8 = Consumo Padrao
                 x50_consumomaximo = float8 = Consumo Máximo
                 x50_vencimento = date = Data Vencimento
                 x50_valoracrescimo = float8 = Valor Acrescimo
                 x50_valordesconto = float8 = Valor Desconto
                 x50_valortotal = float8 = Valor Total
                 x50_linhadigitavel = varchar(70) = Linha Digitavel
                 x50_codigobarras = varchar(70) = Código Barras
                 x50_imprimeconta = int4 = Imprime Conta
                 x50_valor_m3_excesso = float8 = Valor m3 Excesso
                 x50_leituracoletada = int4 = Leitura Coletada
                 x50_observacao = text = Observação da Matrícula
                 x50_contaimpressa = int4 = Conta Impressa
                 x50_avisoleiturista = text = Aviso Leiturista
                 ";
   //funcao construtor da classe
   function cl_aguacoletorexportadados() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguacoletorexportadados");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro
   function erro($mostra,$retorna) {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->x50_sequencial = ($this->x50_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_sequencial"]:$this->x50_sequencial);
       $this->x50_aguacoletorexportadados = ($this->x50_aguacoletorexportadados == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_aguacoletorexportadados"]:$this->x50_aguacoletorexportadados);
       $this->x50_aguacoletorexporta = ($this->x50_aguacoletorexporta == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_aguacoletorexporta"]:$this->x50_aguacoletorexporta);
       $this->x50_matric = ($this->x50_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_matric"]:$this->x50_matric);
       $this->x50_rota = ($this->x50_rota == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_rota"]:$this->x50_rota);
       $this->x50_tipo = ($this->x50_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_tipo"]:$this->x50_tipo);
       $this->x50_codlogradouro = ($this->x50_codlogradouro == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_codlogradouro"]:$this->x50_codlogradouro);
       $this->x50_codbairro = ($this->x50_codbairro == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_codbairro"]:$this->x50_codbairro);
       $this->x50_codhidrometro = ($this->x50_codhidrometro == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_codhidrometro"]:$this->x50_codhidrometro);
       $this->x50_zona = ($this->x50_zona == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_zona"]:$this->x50_zona);
       $this->x50_ordem = ($this->x50_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_ordem"]:$this->x50_ordem);
       $this->x50_responsavel = ($this->x50_responsavel == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_responsavel"]:$this->x50_responsavel);
       $this->x50_nomelogradouro = ($this->x50_nomelogradouro == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_nomelogradouro"]:$this->x50_nomelogradouro);
       $this->x50_numero = ($this->x50_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_numero"]:$this->x50_numero);
       $this->x50_letra = ($this->x50_letra == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_letra"]:$this->x50_letra);
       $this->x50_complemento = ($this->x50_complemento == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_complemento"]:$this->x50_complemento);
       $this->x50_nomebairro = ($this->x50_nomebairro == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_nomebairro"]:$this->x50_nomebairro);
       $this->x50_cidade = ($this->x50_cidade == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_cidade"]:$this->x50_cidade);
       $this->x50_estado = ($this->x50_estado == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_estado"]:$this->x50_estado);
       $this->x50_quadra = ($this->x50_quadra == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_quadra"]:$this->x50_quadra);
       $this->x50_economias = ($this->x50_economias == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_economias"]:$this->x50_economias);
       $this->x50_categorias = ($this->x50_categorias == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_categorias"]:$this->x50_categorias);
       $this->x50_areaconstruida = ($this->x50_areaconstruida == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_areaconstruida"]:$this->x50_areaconstruida);
       $this->x50_nrohidro = ($this->x50_nrohidro == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_nrohidro"]:$this->x50_nrohidro);
       $this->x50_numpre = ($this->x50_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_numpre"]:$this->x50_numpre);
       $this->x50_natureza = ($this->x50_natureza == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_natureza"]:$this->x50_natureza);
       if($this->x50_dtleituraatual == ""){
         $this->x50_dtleituraatual_dia = ($this->x50_dtleituraatual_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_dtleituraatual_dia"]:$this->x50_dtleituraatual_dia);
         $this->x50_dtleituraatual_mes = ($this->x50_dtleituraatual_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_dtleituraatual_mes"]:$this->x50_dtleituraatual_mes);
         $this->x50_dtleituraatual_ano = ($this->x50_dtleituraatual_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_dtleituraatual_ano"]:$this->x50_dtleituraatual_ano);
         if($this->x50_dtleituraatual_dia != ""){
            $this->x50_dtleituraatual = $this->x50_dtleituraatual_ano."-".$this->x50_dtleituraatual_mes."-".$this->x50_dtleituraatual_dia;
         }
       }
       $this->x50_diasleitura = ($this->x50_diasleitura == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_diasleitura"]:$this->x50_diasleitura);
       if($this->x50_dtleituraanterior == ""){
         $this->x50_dtleituraanterior_dia = ($this->x50_dtleituraanterior_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_dtleituraanterior_dia"]:$this->x50_dtleituraanterior_dia);
         $this->x50_dtleituraanterior_mes = ($this->x50_dtleituraanterior_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_dtleituraanterior_mes"]:$this->x50_dtleituraanterior_mes);
         $this->x50_dtleituraanterior_ano = ($this->x50_dtleituraanterior_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_dtleituraanterior_ano"]:$this->x50_dtleituraanterior_ano);
         if($this->x50_dtleituraanterior_dia != ""){
            $this->x50_dtleituraanterior = $this->x50_dtleituraanterior_ano."-".$this->x50_dtleituraanterior_mes."-".$this->x50_dtleituraanterior_dia;
         }
       }
       $this->x50_consumo = ($this->x50_consumo == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_consumo"]:$this->x50_consumo);
       $this->x50_mediadiaria = ($this->x50_mediadiaria == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_mediadiaria"]:$this->x50_mediadiaria);
       $this->x50_consumopadrao = ($this->x50_consumopadrao == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_consumopadrao"]:$this->x50_consumopadrao);
       $this->x50_consumomaximo = ($this->x50_consumomaximo == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_consumomaximo"]:$this->x50_consumomaximo);
       if($this->x50_vencimento == ""){
         $this->x50_vencimento_dia = ($this->x50_vencimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_vencimento_dia"]:$this->x50_vencimento_dia);
         $this->x50_vencimento_mes = ($this->x50_vencimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_vencimento_mes"]:$this->x50_vencimento_mes);
         $this->x50_vencimento_ano = ($this->x50_vencimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_vencimento_ano"]:$this->x50_vencimento_ano);
         if($this->x50_vencimento_dia != ""){
            $this->x50_vencimento = $this->x50_vencimento_ano."-".$this->x50_vencimento_mes."-".$this->x50_vencimento_dia;
         }
       }
       $this->x50_valoracrescimo = ($this->x50_valoracrescimo == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_valoracrescimo"]:$this->x50_valoracrescimo);
       $this->x50_valordesconto = ($this->x50_valordesconto == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_valordesconto"]:$this->x50_valordesconto);
       $this->x50_valortotal = ($this->x50_valortotal == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_valortotal"]:$this->x50_valortotal);
       $this->x50_linhadigitavel = ($this->x50_linhadigitavel == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_linhadigitavel"]:$this->x50_linhadigitavel);
       $this->x50_codigobarras = ($this->x50_codigobarras == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_codigobarras"]:$this->x50_codigobarras);
       $this->x50_imprimeconta = ($this->x50_imprimeconta == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_imprimeconta"]:$this->x50_imprimeconta);
       $this->x50_valor_m3_excesso = ($this->x50_valor_m3_excesso == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_valor_m3_excesso"]:$this->x50_valor_m3_excesso);
       $this->x50_leituracoletada = ($this->x50_leituracoletada == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_leituracoletada"]:$this->x50_leituracoletada);
       $this->x50_observacao = ($this->x50_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_observacao"]:$this->x50_observacao);
       $this->x50_contaimpressa = ($this->x50_contaimpressa == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_contaimpressa"]:$this->x50_contaimpressa);
       $this->x50_avisoleiturista = ($this->x50_avisoleiturista == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_avisoleiturista"]:$this->x50_avisoleiturista);

     }else{
       $this->x50_sequencial = ($this->x50_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["x50_sequencial"]:$this->x50_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($x50_sequencial){
      $this->atualizacampos();
     if($this->x50_aguacoletorexportadados == null ){
       $this->x50_aguacoletorexportadados = "0";
     }
     if($this->x50_aguacoletorexporta == null ){
       $this->erro_sql = " Campo Código Exportação nao Informado.";
       $this->erro_campo = "x50_aguacoletorexporta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x50_matric == null ){
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "x50_matric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x50_rota == null ){
       $this->erro_sql = " Campo Rota nao Informado.";
       $this->erro_campo = "x50_rota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x50_tipo == null ){
       $this->x50_tipo = "0";
     }
     if($this->x50_codlogradouro == null ){
       $this->erro_sql = " Campo Cod. Logradouro nao Informado.";
       $this->erro_campo = "x50_codlogradouro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x50_codbairro == null ){
       $this->erro_sql = " Campo Cód. do Bairro nao Informado.";
       $this->erro_campo = "x50_codbairro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x50_codhidrometro == null ){
       $this->erro_sql = " Campo Cód Hidrometro nao Informado.";
       $this->erro_campo = "x50_codhidrometro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x50_zona == null ){
       $this->x50_zona = "0";
     }
     if($this->x50_ordem == null ){
       $this->x50_ordem = "0";
     }
     if($this->x50_numero == null ){
       $this->x50_numero = "0";
     }
     if($this->x50_quadra == null ){
       $this->x50_quadra = "0";
     }
     if($this->x50_economias == null ){
       $this->x50_economias = "0";
     }
     if($this->x50_areaconstruida == null ){
       $this->x50_areaconstruida = "0";
     }
     if($this->x50_numpre == null ){
       $this->x50_numpre = "0";
     }
     if($this->x50_dtleituraatual == null ){
       $this->x50_dtleituraatual = "null";
     }
     if($this->x50_diasleitura == null ){
       $this->x50_diasleitura = "0";
     }
     if($this->x50_dtleituraanterior == null ){
       $this->x50_dtleituraanterior = "null";
     }
     if($this->x50_consumo == null ){
       $this->x50_consumo = "0";
     }
     if($this->x50_mediadiaria == null ){
       $this->x50_mediadiaria = "0";
     }
     if($this->x50_consumopadrao == null ){
       $this->x50_consumopadrao = "0";
     }
     if($this->x50_consumomaximo == null ){
       $this->x50_consumomaximo = "0";
     }
     if($this->x50_vencimento == null ){
       $this->x50_vencimento = "null";
     }
     if($this->x50_valoracrescimo == null ){
       $this->x50_valoracrescimo = "0";
     }
     if($this->x50_valordesconto == null ){
       $this->x50_valordesconto = "0";
     }
     if($this->x50_valortotal == null ){
       $this->x50_valortotal = "0";
     }
     if($this->x50_imprimeconta == null ){
       $this->x50_imprimeconta = "0";
     }
     if($this->x50_valor_m3_excesso == null ){
       $this->x50_valor_m3_excesso = "0";
     }
     if($this->x50_leituracoletada == null ){
       $this->x50_leituracoletada = "0";
     }
     if($this->x50_contaimpressa == null ){
       $this->x50_contaimpressa = "0";
     }
     if($x50_sequencial == "" || $x50_sequencial == null ){
       $result = db_query("select nextval('aguacoletorexportadados_x50_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguacoletorexportadados_x50_sequencial_seq do campo: x50_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->x50_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from aguacoletorexportadados_x50_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $x50_sequencial)){
         $this->erro_sql = " Campo x50_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x50_sequencial = $x50_sequencial;
       }
     }
     if(($this->x50_sequencial == null) || ($this->x50_sequencial == "") ){
       $this->erro_sql = " Campo x50_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguacoletorexportadados(
                                       x50_sequencial
                                      ,x50_aguacoletorexportadados
                                      ,x50_aguacoletorexporta
                                      ,x50_matric
                                      ,x50_rota
                                      ,x50_tipo
                                      ,x50_codlogradouro
                                      ,x50_codbairro
                                      ,x50_codhidrometro
                                      ,x50_zona
                                      ,x50_ordem
                                      ,x50_responsavel
                                      ,x50_nomelogradouro
                                      ,x50_numero
                                      ,x50_letra
                                      ,x50_complemento
                                      ,x50_nomebairro
                                      ,x50_cidade
                                      ,x50_estado
                                      ,x50_quadra
                                      ,x50_economias
                                      ,x50_categorias
                                      ,x50_areaconstruida
                                      ,x50_nrohidro
                                      ,x50_numpre
                                      ,x50_natureza
                                      ,x50_dtleituraatual
                                      ,x50_diasleitura
                                      ,x50_dtleituraanterior
                                      ,x50_consumo
                                      ,x50_mediadiaria
                                      ,x50_consumopadrao
                                      ,x50_consumomaximo
                                      ,x50_vencimento
                                      ,x50_valoracrescimo
                                      ,x50_valordesconto
                                      ,x50_valortotal
                                      ,x50_linhadigitavel
                                      ,x50_codigobarras
                                      ,x50_imprimeconta
                                      ,x50_valor_m3_excesso
                                      ,x50_leituracoletada
                                      ,x50_observacao
                                      ,x50_contaimpressa
                                      ,x50_avisoleiturista
                       )
                values (
                                $this->x50_sequencial
                               ,$this->x50_aguacoletorexportadados
                               ,$this->x50_aguacoletorexporta
                               ,$this->x50_matric
                               ,$this->x50_rota
                               ,$this->x50_tipo
                               ,$this->x50_codlogradouro
                               ,$this->x50_codbairro
                               ,$this->x50_codhidrometro
                               ,$this->x50_zona
                               ,$this->x50_ordem
                               ,'$this->x50_responsavel'
                               ,'$this->x50_nomelogradouro'
                               ,$this->x50_numero
                               ,'$this->x50_letra'
                               ,'$this->x50_complemento'
                               ,'$this->x50_nomebairro'
                               ,'$this->x50_cidade'
                               ,'$this->x50_estado'
                               ,$this->x50_quadra
                               ,$this->x50_economias
                               ,'$this->x50_categorias'
                               ,$this->x50_areaconstruida
                               ,'$this->x50_nrohidro'
                               ,$this->x50_numpre
                               ,'$this->x50_natureza'
                               ,".($this->x50_dtleituraatual == "null" || $this->x50_dtleituraatual == ""?"null":"'".$this->x50_dtleituraatual."'")."
                               ,$this->x50_diasleitura
                               ,".($this->x50_dtleituraanterior == "null" || $this->x50_dtleituraanterior == ""?"null":"'".$this->x50_dtleituraanterior."'")."
                               ,$this->x50_consumo
                               ,$this->x50_mediadiaria
                               ,$this->x50_consumopadrao
                               ,$this->x50_consumomaximo
                               ,".($this->x50_vencimento == "null" || $this->x50_vencimento == ""?"null":"'".$this->x50_vencimento."'")."
                               ,$this->x50_valoracrescimo
                               ,$this->x50_valordesconto
                               ,$this->x50_valortotal
                               ,'$this->x50_linhadigitavel'
                               ,'$this->x50_codigobarras'
                               ,$this->x50_imprimeconta
                               ,$this->x50_valor_m3_excesso
                               ,$this->x50_leituracoletada
                               ,'$this->x50_observacao'
                               ,$this->x50_contaimpressa
                               ,'$this->x50_avisoleiturista'
                      )";

     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Agua Coletor Exporta Dados ($this->x50_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Agua Coletor Exporta Dados já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Agua Coletor Exporta Dados ($this->x50_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x50_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x50_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15360,'$this->x50_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2703,15360,'','".AddSlashes(pg_result($resaco,0,'x50_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15568,'','".AddSlashes(pg_result($resaco,0,'x50_aguacoletorexportadados'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15361,'','".AddSlashes(pg_result($resaco,0,'x50_aguacoletorexporta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15362,'','".AddSlashes(pg_result($resaco,0,'x50_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15363,'','".AddSlashes(pg_result($resaco,0,'x50_rota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15364,'','".AddSlashes(pg_result($resaco,0,'x50_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15365,'','".AddSlashes(pg_result($resaco,0,'x50_codlogradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15366,'','".AddSlashes(pg_result($resaco,0,'x50_codbairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15367,'','".AddSlashes(pg_result($resaco,0,'x50_codhidrometro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15368,'','".AddSlashes(pg_result($resaco,0,'x50_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15369,'','".AddSlashes(pg_result($resaco,0,'x50_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15370,'','".AddSlashes(pg_result($resaco,0,'x50_responsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15371,'','".AddSlashes(pg_result($resaco,0,'x50_nomelogradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15372,'','".AddSlashes(pg_result($resaco,0,'x50_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15373,'','".AddSlashes(pg_result($resaco,0,'x50_letra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15374,'','".AddSlashes(pg_result($resaco,0,'x50_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15375,'','".AddSlashes(pg_result($resaco,0,'x50_nomebairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15376,'','".AddSlashes(pg_result($resaco,0,'x50_cidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15377,'','".AddSlashes(pg_result($resaco,0,'x50_estado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15378,'','".AddSlashes(pg_result($resaco,0,'x50_quadra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15379,'','".AddSlashes(pg_result($resaco,0,'x50_economias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15380,'','".AddSlashes(pg_result($resaco,0,'x50_categorias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15381,'','".AddSlashes(pg_result($resaco,0,'x50_areaconstruida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15382,'','".AddSlashes(pg_result($resaco,0,'x50_nrohidro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15383,'','".AddSlashes(pg_result($resaco,0,'x50_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15384,'','".AddSlashes(pg_result($resaco,0,'x50_natureza'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15385,'','".AddSlashes(pg_result($resaco,0,'x50_dtleituraatual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15386,'','".AddSlashes(pg_result($resaco,0,'x50_diasleitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15387,'','".AddSlashes(pg_result($resaco,0,'x50_dtleituraanterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15388,'','".AddSlashes(pg_result($resaco,0,'x50_consumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15389,'','".AddSlashes(pg_result($resaco,0,'x50_mediadiaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15547,'','".AddSlashes(pg_result($resaco,0,'x50_consumopadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15548,'','".AddSlashes(pg_result($resaco,0,'x50_consumomaximo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15390,'','".AddSlashes(pg_result($resaco,0,'x50_vencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15391,'','".AddSlashes(pg_result($resaco,0,'x50_valoracrescimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15392,'','".AddSlashes(pg_result($resaco,0,'x50_valordesconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15393,'','".AddSlashes(pg_result($resaco,0,'x50_valortotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15394,'','".AddSlashes(pg_result($resaco,0,'x50_linhadigitavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15395,'','".AddSlashes(pg_result($resaco,0,'x50_codigobarras'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15396,'','".AddSlashes(pg_result($resaco,0,'x50_imprimeconta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15569,'','".AddSlashes(pg_result($resaco,0,'x50_valor_m3_excesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15570,'','".AddSlashes(pg_result($resaco,0,'x50_leituracoletada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,15616,'','".AddSlashes(pg_result($resaco,0,'x50_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,16564,'','".AddSlashes(pg_result($resaco,0,'x50_contaimpressa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2703,18422,'','".AddSlashes(pg_result($resaco,0,'x50_avisoleiturista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");

     }
     return true;
   }
   // funcao para alteracao
   function alterar ($x50_sequencial=null) {
      $this->atualizacampos();
     $sql = " update aguacoletorexportadados set ";
     $virgula = "";
     if(trim($this->x50_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_sequencial"])){
       $sql  .= $virgula." x50_sequencial = $this->x50_sequencial ";
       $virgula = ",";
       if(trim($this->x50_sequencial) == null ){
         $this->erro_sql = " Campo Código Exportação Dados nao Informado.";
         $this->erro_campo = "x50_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x50_aguacoletorexportadados)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_aguacoletorexportadados"])){
        if(trim($this->x50_aguacoletorexportadados)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x50_aguacoletorexportadados"])){
           $this->x50_aguacoletorexportadados = "0" ;
        }
       $sql  .= $virgula." x50_aguacoletorexportadados = $this->x50_aguacoletorexportadados ";
       $virgula = ",";
     }
     if(trim($this->x50_aguacoletorexporta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_aguacoletorexporta"])){
       $sql  .= $virgula." x50_aguacoletorexporta = $this->x50_aguacoletorexporta ";
       $virgula = ",";
       if(trim($this->x50_aguacoletorexporta) == null ){
         $this->erro_sql = " Campo Código Exportação nao Informado.";
         $this->erro_campo = "x50_aguacoletorexporta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x50_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_matric"])){
       $sql  .= $virgula." x50_matric = $this->x50_matric ";
       $virgula = ",";
       if(trim($this->x50_matric) == null ){
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "x50_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x50_rota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_rota"])){
       $sql  .= $virgula." x50_rota = $this->x50_rota ";
       $virgula = ",";
       if(trim($this->x50_rota) == null ){
         $this->erro_sql = " Campo Rota nao Informado.";
         $this->erro_campo = "x50_rota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x50_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_tipo"])){
        if(trim($this->x50_tipo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x50_tipo"])){
           $this->x50_tipo = "0" ;
        }
       $sql  .= $virgula." x50_tipo = $this->x50_tipo ";
       $virgula = ",";
     }
     if(trim($this->x50_codlogradouro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_codlogradouro"])){
       $sql  .= $virgula." x50_codlogradouro = $this->x50_codlogradouro ";
       $virgula = ",";
       if(trim($this->x50_codlogradouro) == null ){
         $this->erro_sql = " Campo Cod. Logradouro nao Informado.";
         $this->erro_campo = "x50_codlogradouro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x50_codbairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_codbairro"])){
       $sql  .= $virgula." x50_codbairro = $this->x50_codbairro ";
       $virgula = ",";
       if(trim($this->x50_codbairro) == null ){
         $this->erro_sql = " Campo Cód. do Bairro nao Informado.";
         $this->erro_campo = "x50_codbairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x50_codhidrometro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_codhidrometro"])){
       $sql  .= $virgula." x50_codhidrometro = $this->x50_codhidrometro ";
       $virgula = ",";
       if(trim($this->x50_codhidrometro) == null ){
         $this->erro_sql = " Campo Cód Hidrometro nao Informado.";
         $this->erro_campo = "x50_codhidrometro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x50_zona)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_zona"])){
        if(trim($this->x50_zona)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x50_zona"])){
           $this->x50_zona = "0" ;
        }
       $sql  .= $virgula." x50_zona = $this->x50_zona ";
       $virgula = ",";
     }
     if(trim($this->x50_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_ordem"])){
        if(trim($this->x50_ordem)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x50_ordem"])){
           $this->x50_ordem = "0" ;
        }
       $sql  .= $virgula." x50_ordem = $this->x50_ordem ";
       $virgula = ",";
     }
     if(trim($this->x50_responsavel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_responsavel"])){
       $sql  .= $virgula." x50_responsavel = '$this->x50_responsavel' ";
       $virgula = ",";
     }
     if(trim($this->x50_nomelogradouro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_nomelogradouro"])){
       $sql  .= $virgula." x50_nomelogradouro = '$this->x50_nomelogradouro' ";
       $virgula = ",";
     }
     if(trim($this->x50_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_numero"])){
        if(trim($this->x50_numero)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x50_numero"])){
           $this->x50_numero = "0" ;
        }
       $sql  .= $virgula." x50_numero = $this->x50_numero ";
       $virgula = ",";
     }
     if(trim($this->x50_letra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_letra"])){
       $sql  .= $virgula." x50_letra = '$this->x50_letra' ";
       $virgula = ",";
     }
     if(trim($this->x50_complemento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_complemento"])){
       $sql  .= $virgula." x50_complemento = '$this->x50_complemento' ";
       $virgula = ",";
     }
     if(trim($this->x50_nomebairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_nomebairro"])){
       $sql  .= $virgula." x50_nomebairro = '$this->x50_nomebairro' ";
       $virgula = ",";
     }
     if(trim($this->x50_cidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_cidade"])){
       $sql  .= $virgula." x50_cidade = '$this->x50_cidade' ";
       $virgula = ",";
     }
     if(trim($this->x50_estado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_estado"])){
       $sql  .= $virgula." x50_estado = '$this->x50_estado' ";
       $virgula = ",";
     }
     if(trim($this->x50_quadra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_quadra"])){
        if(trim($this->x50_quadra)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x50_quadra"])){
           $this->x50_quadra = "0" ;
        }
       $sql  .= $virgula." x50_quadra = $this->x50_quadra ";
       $virgula = ",";
     }
     if(trim($this->x50_economias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_economias"])){
        if(trim($this->x50_economias)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x50_economias"])){
           $this->x50_economias = "0" ;
        }
       $sql  .= $virgula." x50_economias = $this->x50_economias ";
       $virgula = ",";
     }
     if(trim($this->x50_categorias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_categorias"])){
       $sql  .= $virgula." x50_categorias = '$this->x50_categorias' ";
       $virgula = ",";
     }
     if(trim($this->x50_areaconstruida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_areaconstruida"])){
        if(trim($this->x50_areaconstruida)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x50_areaconstruida"])){
           $this->x50_areaconstruida = "0" ;
        }
       $sql  .= $virgula." x50_areaconstruida = $this->x50_areaconstruida ";
       $virgula = ",";
     }
     if(trim($this->x50_nrohidro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_nrohidro"])){
       $sql  .= $virgula." x50_nrohidro = '$this->x50_nrohidro' ";
       $virgula = ",";
     }
     if(trim($this->x50_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_numpre"])){
        if(trim($this->x50_numpre)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x50_numpre"])){
           $this->x50_numpre = "0" ;
        }
       $sql  .= $virgula." x50_numpre = $this->x50_numpre ";
       $virgula = ",";
     }
     if(trim($this->x50_natureza)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_natureza"])){
       $sql  .= $virgula." x50_natureza = '$this->x50_natureza' ";
       $virgula = ",";
     }
     if(trim($this->x50_dtleituraatual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_dtleituraatual_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x50_dtleituraatual_dia"] !="") ){
       $sql  .= $virgula." x50_dtleituraatual = '$this->x50_dtleituraatual' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["x50_dtleituraatual_dia"])){
         $sql  .= $virgula." x50_dtleituraatual = null ";
         $virgula = ",";
       }
     }
     if(trim($this->x50_diasleitura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_diasleitura"])){
        if(trim($this->x50_diasleitura)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x50_diasleitura"])){
           $this->x50_diasleitura = "0" ;
        }
       $sql  .= $virgula." x50_diasleitura = $this->x50_diasleitura ";
       $virgula = ",";
     }
     if(trim($this->x50_dtleituraanterior)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_dtleituraanterior_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x50_dtleituraanterior_dia"] !="") ){
       $sql  .= $virgula." x50_dtleituraanterior = '$this->x50_dtleituraanterior' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["x50_dtleituraanterior_dia"])){
         $sql  .= $virgula." x50_dtleituraanterior = null ";
         $virgula = ",";
       }
     }
     if(trim($this->x50_consumo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_consumo"])){
        if(trim($this->x50_consumo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x50_consumo"])){
           $this->x50_consumo = "0" ;
        }
       $sql  .= $virgula." x50_consumo = $this->x50_consumo ";
       $virgula = ",";
     }
     if(trim($this->x50_mediadiaria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_mediadiaria"])){
        if(trim($this->x50_mediadiaria)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x50_mediadiaria"])){
           $this->x50_mediadiaria = "0" ;
        }
       $sql  .= $virgula." x50_mediadiaria = $this->x50_mediadiaria ";
       $virgula = ",";
     }
     if(trim($this->x50_consumopadrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_consumopadrao"])){
        if(trim($this->x50_consumopadrao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x50_consumopadrao"])){
           $this->x50_consumopadrao = "0" ;
        }
       $sql  .= $virgula." x50_consumopadrao = $this->x50_consumopadrao ";
       $virgula = ",";
     }
     if(trim($this->x50_consumomaximo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_consumomaximo"])){
        if(trim($this->x50_consumomaximo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x50_consumomaximo"])){
           $this->x50_consumomaximo = "0" ;
        }
       $sql  .= $virgula." x50_consumomaximo = $this->x50_consumomaximo ";
       $virgula = ",";
     }
     if(trim($this->x50_vencimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_vencimento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x50_vencimento_dia"] !="") ){
       $sql  .= $virgula." x50_vencimento = '$this->x50_vencimento' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["x50_vencimento_dia"])){
         $sql  .= $virgula." x50_vencimento = null ";
         $virgula = ",";
       }
     }
     if(trim($this->x50_valoracrescimo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_valoracrescimo"])){
        if(trim($this->x50_valoracrescimo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x50_valoracrescimo"])){
           $this->x50_valoracrescimo = "0" ;
        }
       $sql  .= $virgula." x50_valoracrescimo = $this->x50_valoracrescimo ";
       $virgula = ",";
     }
     if(trim($this->x50_valordesconto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_valordesconto"])){
        if(trim($this->x50_valordesconto)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x50_valordesconto"])){
           $this->x50_valordesconto = "0" ;
        }
       $sql  .= $virgula." x50_valordesconto = $this->x50_valordesconto ";
       $virgula = ",";
     }
     if(trim($this->x50_valortotal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_valortotal"])){
        if(trim($this->x50_valortotal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x50_valortotal"])){
           $this->x50_valortotal = "0" ;
        }
       $sql  .= $virgula." x50_valortotal = $this->x50_valortotal ";
       $virgula = ",";
     }
     if(trim($this->x50_linhadigitavel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_linhadigitavel"])){
       $sql  .= $virgula." x50_linhadigitavel = '$this->x50_linhadigitavel' ";
       $virgula = ",";
     }
     if(trim($this->x50_codigobarras)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_codigobarras"])){
       $sql  .= $virgula." x50_codigobarras = '$this->x50_codigobarras' ";
       $virgula = ",";
     }
     if(trim($this->x50_imprimeconta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_imprimeconta"])){
        if(trim($this->x50_imprimeconta)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x50_imprimeconta"])){
           $this->x50_imprimeconta = "0" ;
        }
       $sql  .= $virgula." x50_imprimeconta = $this->x50_imprimeconta ";
       $virgula = ",";
     }
     if(trim($this->x50_valor_m3_excesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_valor_m3_excesso"])){
        if(trim($this->x50_valor_m3_excesso)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x50_valor_m3_excesso"])){
           $this->x50_valor_m3_excesso = "0" ;
        }
       $sql  .= $virgula." x50_valor_m3_excesso = $this->x50_valor_m3_excesso ";
       $virgula = ",";
     }
     if(trim($this->x50_leituracoletada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_leituracoletada"])){
        if(trim($this->x50_leituracoletada)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x50_leituracoletada"])){
           $this->x50_leituracoletada = "0" ;
        }
       $sql  .= $virgula." x50_leituracoletada = $this->x50_leituracoletada ";
       $virgula = ",";
     }
     if(trim($this->x50_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_observacao"])){
       $sql  .= $virgula." x50_observacao = '$this->x50_observacao' ";
       $virgula = ",";
     }
     if(trim($this->x50_contaimpressa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_contaimpressa"])){
        if(trim($this->x50_contaimpressa)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x50_contaimpressa"])){
           $this->x50_contaimpressa = "0" ;
        }
       $sql  .= $virgula." x50_contaimpressa = $this->x50_contaimpressa ";
       $virgula = ",";
     }
     if(trim($this->x50_avisoleiturista)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x50_avisoleiturista"])){
       $sql  .= $virgula." x50_avisoleiturista = '$this->x50_avisoleiturista' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($x50_sequencial!=null){
       $sql .= " x50_sequencial = $this->x50_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x50_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15360,'$this->x50_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_sequencial"]) || $this->x50_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2703,15360,'".AddSlashes(pg_result($resaco,$conresaco,'x50_sequencial'))."','$this->x50_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_aguacoletorexportadados"]) || $this->x50_aguacoletorexportadados != "")
           $resac = db_query("insert into db_acount values($acount,2703,15568,'".AddSlashes(pg_result($resaco,$conresaco,'x50_aguacoletorexportadados'))."','$this->x50_aguacoletorexportadados',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_aguacoletorexporta"]) || $this->x50_aguacoletorexporta != "")
           $resac = db_query("insert into db_acount values($acount,2703,15361,'".AddSlashes(pg_result($resaco,$conresaco,'x50_aguacoletorexporta'))."','$this->x50_aguacoletorexporta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_matric"]) || $this->x50_matric != "")
           $resac = db_query("insert into db_acount values($acount,2703,15362,'".AddSlashes(pg_result($resaco,$conresaco,'x50_matric'))."','$this->x50_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_rota"]) || $this->x50_rota != "")
           $resac = db_query("insert into db_acount values($acount,2703,15363,'".AddSlashes(pg_result($resaco,$conresaco,'x50_rota'))."','$this->x50_rota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_tipo"]) || $this->x50_tipo != "")
           $resac = db_query("insert into db_acount values($acount,2703,15364,'".AddSlashes(pg_result($resaco,$conresaco,'x50_tipo'))."','$this->x50_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_codlogradouro"]) || $this->x50_codlogradouro != "")
           $resac = db_query("insert into db_acount values($acount,2703,15365,'".AddSlashes(pg_result($resaco,$conresaco,'x50_codlogradouro'))."','$this->x50_codlogradouro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_codbairro"]) || $this->x50_codbairro != "")
           $resac = db_query("insert into db_acount values($acount,2703,15366,'".AddSlashes(pg_result($resaco,$conresaco,'x50_codbairro'))."','$this->x50_codbairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_codhidrometro"]) || $this->x50_codhidrometro != "")
           $resac = db_query("insert into db_acount values($acount,2703,15367,'".AddSlashes(pg_result($resaco,$conresaco,'x50_codhidrometro'))."','$this->x50_codhidrometro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_zona"]) || $this->x50_zona != "")
           $resac = db_query("insert into db_acount values($acount,2703,15368,'".AddSlashes(pg_result($resaco,$conresaco,'x50_zona'))."','$this->x50_zona',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_ordem"]) || $this->x50_ordem != "")
           $resac = db_query("insert into db_acount values($acount,2703,15369,'".AddSlashes(pg_result($resaco,$conresaco,'x50_ordem'))."','$this->x50_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_responsavel"]) || $this->x50_responsavel != "")
           $resac = db_query("insert into db_acount values($acount,2703,15370,'".AddSlashes(pg_result($resaco,$conresaco,'x50_responsavel'))."','$this->x50_responsavel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_nomelogradouro"]) || $this->x50_nomelogradouro != "")
           $resac = db_query("insert into db_acount values($acount,2703,15371,'".AddSlashes(pg_result($resaco,$conresaco,'x50_nomelogradouro'))."','$this->x50_nomelogradouro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_numero"]) || $this->x50_numero != "")
           $resac = db_query("insert into db_acount values($acount,2703,15372,'".AddSlashes(pg_result($resaco,$conresaco,'x50_numero'))."','$this->x50_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_letra"]) || $this->x50_letra != "")
           $resac = db_query("insert into db_acount values($acount,2703,15373,'".AddSlashes(pg_result($resaco,$conresaco,'x50_letra'))."','$this->x50_letra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_complemento"]) || $this->x50_complemento != "")
           $resac = db_query("insert into db_acount values($acount,2703,15374,'".AddSlashes(pg_result($resaco,$conresaco,'x50_complemento'))."','$this->x50_complemento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_nomebairro"]) || $this->x50_nomebairro != "")
           $resac = db_query("insert into db_acount values($acount,2703,15375,'".AddSlashes(pg_result($resaco,$conresaco,'x50_nomebairro'))."','$this->x50_nomebairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_cidade"]) || $this->x50_cidade != "")
           $resac = db_query("insert into db_acount values($acount,2703,15376,'".AddSlashes(pg_result($resaco,$conresaco,'x50_cidade'))."','$this->x50_cidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_estado"]) || $this->x50_estado != "")
           $resac = db_query("insert into db_acount values($acount,2703,15377,'".AddSlashes(pg_result($resaco,$conresaco,'x50_estado'))."','$this->x50_estado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_quadra"]) || $this->x50_quadra != "")
           $resac = db_query("insert into db_acount values($acount,2703,15378,'".AddSlashes(pg_result($resaco,$conresaco,'x50_quadra'))."','$this->x50_quadra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_economias"]) || $this->x50_economias != "")
           $resac = db_query("insert into db_acount values($acount,2703,15379,'".AddSlashes(pg_result($resaco,$conresaco,'x50_economias'))."','$this->x50_economias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_categorias"]) || $this->x50_categorias != "")
           $resac = db_query("insert into db_acount values($acount,2703,15380,'".AddSlashes(pg_result($resaco,$conresaco,'x50_categorias'))."','$this->x50_categorias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_areaconstruida"]) || $this->x50_areaconstruida != "")
           $resac = db_query("insert into db_acount values($acount,2703,15381,'".AddSlashes(pg_result($resaco,$conresaco,'x50_areaconstruida'))."','$this->x50_areaconstruida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_nrohidro"]) || $this->x50_nrohidro != "")
           $resac = db_query("insert into db_acount values($acount,2703,15382,'".AddSlashes(pg_result($resaco,$conresaco,'x50_nrohidro'))."','$this->x50_nrohidro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_numpre"]) || $this->x50_numpre != "")
           $resac = db_query("insert into db_acount values($acount,2703,15383,'".AddSlashes(pg_result($resaco,$conresaco,'x50_numpre'))."','$this->x50_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_natureza"]) || $this->x50_natureza != "")
           $resac = db_query("insert into db_acount values($acount,2703,15384,'".AddSlashes(pg_result($resaco,$conresaco,'x50_natureza'))."','$this->x50_natureza',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_dtleituraatual"]) || $this->x50_dtleituraatual != "")
           $resac = db_query("insert into db_acount values($acount,2703,15385,'".AddSlashes(pg_result($resaco,$conresaco,'x50_dtleituraatual'))."','$this->x50_dtleituraatual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_diasleitura"]) || $this->x50_diasleitura != "")
           $resac = db_query("insert into db_acount values($acount,2703,15386,'".AddSlashes(pg_result($resaco,$conresaco,'x50_diasleitura'))."','$this->x50_diasleitura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_dtleituraanterior"]) || $this->x50_dtleituraanterior != "")
           $resac = db_query("insert into db_acount values($acount,2703,15387,'".AddSlashes(pg_result($resaco,$conresaco,'x50_dtleituraanterior'))."','$this->x50_dtleituraanterior',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_consumo"]) || $this->x50_consumo != "")
           $resac = db_query("insert into db_acount values($acount,2703,15388,'".AddSlashes(pg_result($resaco,$conresaco,'x50_consumo'))."','$this->x50_consumo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_mediadiaria"]) || $this->x50_mediadiaria != "")
           $resac = db_query("insert into db_acount values($acount,2703,15389,'".AddSlashes(pg_result($resaco,$conresaco,'x50_mediadiaria'))."','$this->x50_mediadiaria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_consumopadrao"]) || $this->x50_consumopadrao != "")
           $resac = db_query("insert into db_acount values($acount,2703,15547,'".AddSlashes(pg_result($resaco,$conresaco,'x50_consumopadrao'))."','$this->x50_consumopadrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_consumomaximo"]) || $this->x50_consumomaximo != "")
           $resac = db_query("insert into db_acount values($acount,2703,15548,'".AddSlashes(pg_result($resaco,$conresaco,'x50_consumomaximo'))."','$this->x50_consumomaximo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_vencimento"]) || $this->x50_vencimento != "")
           $resac = db_query("insert into db_acount values($acount,2703,15390,'".AddSlashes(pg_result($resaco,$conresaco,'x50_vencimento'))."','$this->x50_vencimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_valoracrescimo"]) || $this->x50_valoracrescimo != "")
           $resac = db_query("insert into db_acount values($acount,2703,15391,'".AddSlashes(pg_result($resaco,$conresaco,'x50_valoracrescimo'))."','$this->x50_valoracrescimo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_valordesconto"]) || $this->x50_valordesconto != "")
           $resac = db_query("insert into db_acount values($acount,2703,15392,'".AddSlashes(pg_result($resaco,$conresaco,'x50_valordesconto'))."','$this->x50_valordesconto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_valortotal"]) || $this->x50_valortotal != "")
           $resac = db_query("insert into db_acount values($acount,2703,15393,'".AddSlashes(pg_result($resaco,$conresaco,'x50_valortotal'))."','$this->x50_valortotal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_linhadigitavel"]) || $this->x50_linhadigitavel != "")
           $resac = db_query("insert into db_acount values($acount,2703,15394,'".AddSlashes(pg_result($resaco,$conresaco,'x50_linhadigitavel'))."','$this->x50_linhadigitavel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_codigobarras"]) || $this->x50_codigobarras != "")
           $resac = db_query("insert into db_acount values($acount,2703,15395,'".AddSlashes(pg_result($resaco,$conresaco,'x50_codigobarras'))."','$this->x50_codigobarras',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_imprimeconta"]) || $this->x50_imprimeconta != "")
           $resac = db_query("insert into db_acount values($acount,2703,15396,'".AddSlashes(pg_result($resaco,$conresaco,'x50_imprimeconta'))."','$this->x50_imprimeconta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_valor_m3_excesso"]) || $this->x50_valor_m3_excesso != "")
           $resac = db_query("insert into db_acount values($acount,2703,15569,'".AddSlashes(pg_result($resaco,$conresaco,'x50_valor_m3_excesso'))."','$this->x50_valor_m3_excesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_leituracoletada"]) || $this->x50_leituracoletada != "")
           $resac = db_query("insert into db_acount values($acount,2703,15570,'".AddSlashes(pg_result($resaco,$conresaco,'x50_leituracoletada'))."','$this->x50_leituracoletada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_observacao"]) || $this->x50_observacao != "")
           $resac = db_query("insert into db_acount values($acount,2703,15616,'".AddSlashes(pg_result($resaco,$conresaco,'x50_observacao'))."','$this->x50_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_contaimpressa"]) || $this->x50_contaimpressa != "")
           $resac = db_query("insert into db_acount values($acount,2703,16564,'".AddSlashes(pg_result($resaco,$conresaco,'x50_contaimpressa'))."','$this->x50_contaimpressa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x50_avisoleiturista"]) || $this->x50_avisoleiturista != "")
           $resac = db_query("insert into db_acount values($acount,2703,18422,'".AddSlashes(pg_result($resaco,$conresaco,'x50_avisoleiturista'))."','$this->x50_avisoleiturista',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agua Coletor Exporta Dados nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x50_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Agua Coletor Exporta Dados nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x50_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x50_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($x50_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x50_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15360,'$x50_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2703,15360,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15568,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_aguacoletorexportadados'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15361,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_aguacoletorexporta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15362,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15363,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_rota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15364,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15365,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_codlogradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15366,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_codbairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15367,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_codhidrometro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15368,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15369,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15370,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_responsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15371,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_nomelogradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15372,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15373,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_letra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15374,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15375,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_nomebairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15376,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_cidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15377,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_estado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15378,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_quadra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15379,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_economias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15380,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_categorias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15381,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_areaconstruida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15382,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_nrohidro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15383,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15384,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_natureza'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15385,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_dtleituraatual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15386,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_diasleitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15387,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_dtleituraanterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15388,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_consumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15389,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_mediadiaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15547,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_consumopadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15548,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_consumomaximo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15390,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_vencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15391,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_valoracrescimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15392,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_valordesconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15393,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_valortotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15394,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_linhadigitavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15395,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_codigobarras'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15396,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_imprimeconta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15569,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_valor_m3_excesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15570,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_leituracoletada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,15616,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,16564,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_contaimpressa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2703,18422,'','".AddSlashes(pg_result($resaco,$iresaco,'x50_avisoleiturista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguacoletorexportadados
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x50_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x50_sequencial = $x50_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agua Coletor Exporta Dados nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x50_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Agua Coletor Exporta Dados nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x50_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x50_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   function sql_record($sql) {
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:aguacoletorexportadados";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $x50_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from aguacoletorexportadados ";
     $sql .= "      inner join bairro  on  bairro.j13_codi = aguacoletorexportadados.x50_codbairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = aguacoletorexportadados.x50_codlogradouro";
     $sql .= "      left  join zonas  on  zonas.j50_zona = aguacoletorexportadados.x50_zona";
     $sql .= "      inner join aguahidromatric  on  aguahidromatric.x04_codhidrometro = aguacoletorexportadados.x50_codhidrometro";
     $sql .= "      inner join aguabase  on  aguabase.x01_matric = aguacoletorexportadados.x50_matric";
     $sql .= "      inner join aguarota  on  aguarota.x06_codrota = aguacoletorexportadados.x50_rota";
     $sql .= "      left  join ruastipo  on  ruastipo.j88_codigo = aguacoletorexportadados.x50_tipo";
     $sql .= "      inner join aguacoletorexporta  on  aguacoletorexporta.x49_sequencial = aguacoletorexportadados.x50_aguacoletorexporta";
     $sql .= "      left  join aguacoletorexportadados  on  aguacoletorexportadados.x50_sequencial = aguacoletorexportadados.x50_aguacoletorexportadados";
     $sql .= "      inner join aguahidromarca  on  aguahidromarca.x03_codmarca = aguahidromatric.x04_codmarca";
     $sql .= "      inner join aguabase  as a on   a.x01_matric = aguahidromatric.x04_matric";
     $sql .= "      inner join aguahidrodiametro  on  aguahidrodiametro.x15_coddiametro = aguahidromatric.x04_coddiametro";
     $sql .= "      inner join bairro  on  bairro.j13_codi = aguabase.x01_codbairro";
     $sql .= "      inner join ruas  as b on   b.j14_codigo = aguabase.x01_codrua";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = aguabase.x01_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = aguacoletorexporta.x49_instit";
     $sql .= "      inner join aguacoletor  as c on   c.x46_sequencial = aguacoletorexporta.x49_aguacoletor";
     $sql .= "      inner join bairro  on  bairro.j13_codi = aguacoletorexportadados.x50_codbairro";
     $sql .= "      inner join ruas  as d on   d.j14_codigo = aguacoletorexportadados.x50_codlogradouro";
     $sql .= "      left  join zonas  as d on   d.j50_zona = aguacoletorexportadados.x50_zona";
     $sql .= "      inner join aguahidromatric  as d on   d.x04_codhidrometro = aguacoletorexportadados.x50_codhidrometro";
     $sql .= "      inner join aguabase  as d on   d.x01_matric = aguacoletorexportadados.x50_matric";
     $sql .= "      inner join aguarota  as d on   d.x06_codrota = aguacoletorexportadados.x50_rota";
     $sql .= "      left  join ruastipo  as d on   d.j88_codigo = aguacoletorexportadados.x50_tipo";
     $sql .= "      inner join aguacoletorexporta  as d on   d.x49_sequencial = aguacoletorexportadados.x50_aguacoletorexporta";
     $sql .= "      left  join aguacoletorexportadados  as d on   d.x50_sequencial = aguacoletorexportadados.x50_aguacoletorexportadados";
     $sql2 = "";
     if($dbwhere==""){
       if($x50_sequencial!=null ){
         $sql2 .= " where aguacoletorexportadados.x50_sequencial = $x50_sequencial ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql
   function sql_query_file ( $x50_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from aguacoletorexportadados ";
     $sql2 = "";
     if($dbwhere==""){
       if($x50_sequencial!=null ){
         $sql2 .= " where aguacoletorexportadados.x50_sequencial = $x50_sequencial ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   public function sql_query_leituras_anteriores($iMatricula, $iAno, $iMes) {

   	$sSqlLeituras = "select x21_codleitura,
                            x21_exerc,
           									x21_mes,
           									x21_situacao,
           									x17_descr,
           									x21_leitura,
           									case
                						  when x21_excesso >= 0 then x21_consumo + x21_excesso
                              else x21_consumo
              							end as x21_consumo,
              							case
							                when x21_excesso < 0 then 0
							                else x21_excesso
							              end as x21_excesso,
						           			30::integer as x21_dias,
           									x21_dtleitura,
                            x21_saldo,
           									fc_agua_mesesultimaleitura(x21_exerc, x21_mes, x04_matric, x21_codleitura) as x99_mesultimaleitura
      								 from agualeitura
           						inner join aguahidromatric on x04_codhidrometro = x21_codhidrometro
           						inner join aguasitleitura on x17_codigo = x21_situacao
     									where x04_matric = {$iMatricula}
       									and x21_status = 1
                        and (x21_exerc, x21_mes) in (select extract(year from data)  as anousu,
                                                            extract(month from data) as mesusu
                                                       from (select cast(date '$iAno-$iMes-01' - cast(cast(mes as text) ||cast(' month' as text) as interval) as date) as data
                                                               from generate_series(0, 7) as mes) as x)
                   order by x21_exerc desc, x21_mes desc
                   limit 6";

    return $sSqlLeituras;
  }
   public function sql_query_categoria_imovel ($iMatricula) {

    $sqlCategoria = "select j31_descr from aguaconstr
                      inner join aguaconstrcar on x12_codconstr = x11_codconstr
                      inner join caracter on j31_codigo = x12_codigo and j31_grupo = 80
                      where x11_matric = $iMatricula";

    return $sqlCategoria;
  }
   public function sql_query_hidrometro_ativo ($iMatricula) {

    $sqlHidrometroAtivo = "select x04_codhidrometro, x04_nrohidro, x04_avisoleiturista
                             from aguahidromatric
                             left join aguahidrotroca on x28_codhidrometro = x04_codhidrometro
                            where x04_matric = $iMatricula
                              and x28_codigo is null";
    return $sqlHidrometroAtivo;
  }
   public function sql_query_dados_matriculas($iRota, $iRotaRuas) {

    $sqlDadosMatriculas = "select coalesce(x06_codrota, 999999) as x07_codrota, x01_matric,
                                  z01_nome, x01_codrua, j88_codigo, j14_nome, x01_numero, x01_orientacao,
                          		  case
                          		    when x32_codcorresp is not null then
                          			  x02_complemento
                          			else
                          			  x11_complemento
                          		  end as x99_complemento,
                                  case
                          		    when x32_codcorresp is not null then
                          			  bairro2.j13_descr
                          			else
                          			  bairro.j13_descr
                          		  end as x99_bairro,
                          		  x01_zona, x01_quadra,
                                  fc_agua_qtdeconomias(x01_matric) as x01_qtdeconomia,
                                  x01_codbairro, j88_codigo, nextval('numpref_k03_numpre_seq') as numpre,
                                  to_char(fc_agua_areaconstr(x01_matric), '999990.00') as x99_areaconstr,
                                  x32_codcorresp
	                         from aguabase
                            left  join aguarotarua                    on x07_codrua           = x01_codrua
                            left  join aguarota                       on x06_codrota          = x07_codrota
                            inner join cgm                            on z01_numcgm           = x01_numcgm
                            left  join aguabasecorresp                on x32_matric           = x01_matric
                            left  join aguacorresp                    on x02_codcorresp       = x32_codcorresp
                            left  join ruas                           on ruas.j14_codigo      = x01_codrua
                            left  join aguaconstr                     on x11_matric           = x01_matric
                            left  join bairro                         on bairro.j13_codi      = x01_codbairro
                            left  join bairro   as bairro2            on bairro2.j13_codi     = x02_codbairro
                            left  join ruastipo                       on ruastipo.j88_codigo  = ruas.j14_tipo
                            left  join aguabasebaixa                  on x08_matric           = x01_matric
                          where x07_codrota in ($iRota)
                            and x07_codrotarua in ($iRotaRuas)
                            and x01_numero between x07_nroini and x07_nrofim
                            and x01_orientacao = x07_orientacao
                            and fc_agua_hidrometroinstalado(x01_matric) is true
                            and x08_matric is null
                          order by x07_codrota, x07_ordem, x01_codrua, x01_orientacao, x01_numero
                          ";

    return $sqlDadosMatriculas;
  }
   // funcao do sql dados
	function sql_query_dados ( $x50_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
		$sql = "select ";
		if($campos != "*" ){
			$campos_sql = split("#",$campos);
			$virgula = "";
			for($i=0;$i<sizeof($campos_sql);$i++){
				$sql .= $virgula.$campos_sql[$i];
				$virgula = ",";
			}
		}else{
			$sql .= $campos;
		}
		$sql .= " from aguacoletorexportadados ";
		$sql .= "      inner join bairro  on  bairro.j13_codi = aguacoletorexportadados.x50_codbairro";
		$sql .= "      inner join ruas  on  ruas.j14_codigo = aguacoletorexportadados.x50_codlogradouro";
		$sql .= "      inner join zonas  on  zonas.j50_zona = aguacoletorexportadados.x50_zona";
		$sql .= "      inner join aguahidromatric  on  aguahidromatric.x04_codhidrometro = aguacoletorexportadados.x50_codhidrometro";
		$sql .= "      inner join aguabase  on  aguabase.x01_matric = aguacoletorexportadados.x50_matric";
		$sql .= "      inner join aguarota  on  aguarota.x06_codrota = aguacoletorexportadados.x50_rota";
		$sql .= "      inner join ruastipo  on  ruastipo.j88_codigo = aguacoletorexportadados.x50_tipo";
		$sql .= "      inner join aguacoletorexporta  on  aguacoletorexporta.x49_sequencial = aguacoletorexportadados.x50_aguacoletorexporta";
		$sql .= "      inner join aguahidromarca  on  aguahidromarca.x03_codmarca = aguahidromatric.x04_codmarca";
		$sql .= "      inner join aguahidrodiametro  on  aguahidrodiametro.x15_coddiametro = aguahidromatric.x04_coddiametro";
		$sql .= "      inner join bairro as bairro2  on  bairro2.j13_codi = aguabase.x01_codbairro";
		$sql .= "      inner join ruas  as b on   b.j14_codigo = aguabase.x01_codrua";
		$sql .= "      inner join cgm  on  cgm.z01_numcgm = aguabase.x01_numcgm";
		$sql .= "      inner join db_config  on  db_config.codigo = aguacoletorexporta.x49_instit";
		$sql .= "      inner join aguacoletor  as c on   c.x46_sequencial = aguacoletorexporta.x49_aguacoletor";
    $sql .= "      inner join aguarotarua on x07_codrua = x50_codlogradouro and x07_codrota = x50_rota and x07_orientacao = x50_letra";
		$sql2 = "";
		if($dbwhere==""){
			if($x50_sequencial!=null ){
				$sql2 .= " where aguacoletorexportadados.x50_sequencial = $x50_sequencial ";
			}
		}else if($dbwhere != ""){
			$sql2 = " where $dbwhere";
		}
		$sql .= $sql2;
		if($ordem != null ){
			$sql .= " order by ";
			$campos_sql = split("#",$ordem);
			$virgula = "";
			for($i=0;$i<sizeof($campos_sql);$i++){
				$sql .= $virgula.$campos_sql[$i];
				$virgula = ",";
			}
		}
		return $sql;
	}

  public function getSqlArrecadRecibo($iCodColetorExportaDados) {

    $sql = "
      select k00_numcgm,
         k00_dtoper,
         k00_receit,
         k00_hist,
         k00_valor,
         k00_dtvenc,
         k00_numpre,
         k00_numpar,
         k00_numtot,
         k00_numdig,
         0::integer as k00_conta,
         null as k00_dtpaga,
         x50_numpre as k00_numnov

    from aguacoletorexportadados
         inner join aguacoletorexportadadosreceita on x52_aguacoletorexportadados = x50_sequencial
         inner join arrecad  on k00_numpre = x52_numpre
                            and k00_numpar = cast(x52_numpar as integer)
                            and k00_receit = x52_receita
   where x50_sequencial = $iCodColetorExportaDados
     and x52_valor      > 0";

    return $sql;
  }

}
