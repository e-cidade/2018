<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

//MODULO: issqn
//CLASSE DA ENTIDADE issarquivoretencaoregistro
class cl_issarquivoretencaoregistro { 
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
   var $q91_sequencial = 0; 
   var $q91_issarquivoretencao = 0; 
   var $q91_sequencialregistro = 0; 
   var $q91_dataemissaodocumento_dia = null; 
   var $q91_dataemissaodocumento_mes = null; 
   var $q91_dataemissaodocumento_ano = null; 
   var $q91_dataemissaodocumento = null; 
   var $q91_datavencimento_dia = null; 
   var $q91_datavencimento_mes = null; 
   var $q91_datavencimento_ano = null; 
   var $q91_datavencimento = null; 
   var $q91_numerodocumento = null; 
   var $q91_cnpjtomador = null; 
   var $q91_codigomunicipiotomador = 0; 
   var $q91_cpfcnpjprestador = null; 
   var $q91_codigomunicipionota = 0; 
   var $q91_esferareceita = null; 
   var $q91_anousu = 0; 
   var $q91_mesusu = 0; 
   var $q91_valorprincipal = 0; 
   var $q91_valormulta = 0; 
   var $q91_valorjuros = 0; 
   var $q91_numeronotafiscal = 0; 
   var $q91_serienotafiscal = null; 
   var $q91_subserienotafiscal = 0; 
   var $q91_dataemissaonotafiscal_dia = null; 
   var $q91_dataemissaonotafiscal_mes = null; 
   var $q91_dataemissaonotafiscal_ano = null; 
   var $q91_dataemissaonotafiscal = null; 
   var $q91_valornotafiscal = 0; 
   var $q91_aliquota = 0; 
   var $q91_valorbasecalculo = 0; 
   var $q91_observacao = null; 
   var $q91_codigomunicipiofavorecido = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q91_sequencial = int4 = Código do Registro 
                 q91_issarquivoretencao = int4 = Código Arquivo de Retenção 
                 q91_sequencialregistro = int4 = Sequencial Registro 
                 q91_dataemissaodocumento = date = Data de Emissão 
                 q91_datavencimento = date = Data de Vencimento 
                 q91_numerodocumento = varchar(12) = Número do Documento 
                 q91_cnpjtomador = varchar(14) = CNPJ Tomador 
                 q91_codigomunicipiotomador = int4 = Código Município Tomador 
                 q91_cpfcnpjprestador = varchar(14) = CPF ou CNPJ Prestador 
                 q91_codigomunicipionota = int4 = Código Múnicípio Nota 
                 q91_esferareceita = varchar(1) = Esfera Receita 
                 q91_anousu = int4 = Ano 
                 q91_mesusu = int4 = Mes 
                 q91_valorprincipal = float8 = Valor Principal 
                 q91_valormulta = float8 = Valor Multa 
                 q91_valorjuros = float8 = Valor Juros 
                 q91_numeronotafiscal = int4 = Nota Fiscal 
                 q91_serienotafiscal = varchar(5) = Serie Nota Fiscal 
                 q91_subserienotafiscal = int4 = Sub-Série Nota Fiscal 
                 q91_dataemissaonotafiscal = date = Data Emissão Nota Fiscal 
                 q91_valornotafiscal = float8 = Valor Nota Fiscal 
                 q91_aliquota = float8 = Aliquota 
                 q91_valorbasecalculo = float8 = Valor Base Cálculo 
                 q91_observacao = text = Observações 
                 q91_codigomunicipiofavorecido = int4 = Código Município Favorecido 
                 ";
   //funcao construtor da classe 
   function cl_issarquivoretencaoregistro() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issarquivoretencaoregistro"); 
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
       $this->q91_sequencial = ($this->q91_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_sequencial"]:$this->q91_sequencial);
       $this->q91_issarquivoretencao = ($this->q91_issarquivoretencao == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_issarquivoretencao"]:$this->q91_issarquivoretencao);
       $this->q91_sequencialregistro = ($this->q91_sequencialregistro == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_sequencialregistro"]:$this->q91_sequencialregistro);
       if($this->q91_dataemissaodocumento == ""){
         $this->q91_dataemissaodocumento_dia = ($this->q91_dataemissaodocumento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_dataemissaodocumento_dia"]:$this->q91_dataemissaodocumento_dia);
         $this->q91_dataemissaodocumento_mes = ($this->q91_dataemissaodocumento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_dataemissaodocumento_mes"]:$this->q91_dataemissaodocumento_mes);
         $this->q91_dataemissaodocumento_ano = ($this->q91_dataemissaodocumento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_dataemissaodocumento_ano"]:$this->q91_dataemissaodocumento_ano);
         if($this->q91_dataemissaodocumento_dia != ""){
            $this->q91_dataemissaodocumento = $this->q91_dataemissaodocumento_ano."-".$this->q91_dataemissaodocumento_mes."-".$this->q91_dataemissaodocumento_dia;
         }
       }
       if($this->q91_datavencimento == ""){
         $this->q91_datavencimento_dia = ($this->q91_datavencimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_datavencimento_dia"]:$this->q91_datavencimento_dia);
         $this->q91_datavencimento_mes = ($this->q91_datavencimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_datavencimento_mes"]:$this->q91_datavencimento_mes);
         $this->q91_datavencimento_ano = ($this->q91_datavencimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_datavencimento_ano"]:$this->q91_datavencimento_ano);
         if($this->q91_datavencimento_dia != ""){
            $this->q91_datavencimento = $this->q91_datavencimento_ano."-".$this->q91_datavencimento_mes."-".$this->q91_datavencimento_dia;
         }
       }
       $this->q91_numerodocumento = ($this->q91_numerodocumento == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_numerodocumento"]:$this->q91_numerodocumento);
       $this->q91_cnpjtomador = ($this->q91_cnpjtomador == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_cnpjtomador"]:$this->q91_cnpjtomador);
       $this->q91_codigomunicipiotomador = ($this->q91_codigomunicipiotomador == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_codigomunicipiotomador"]:$this->q91_codigomunicipiotomador);
       $this->q91_cpfcnpjprestador = ($this->q91_cpfcnpjprestador == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_cpfcnpjprestador"]:$this->q91_cpfcnpjprestador);
       $this->q91_codigomunicipionota = ($this->q91_codigomunicipionota == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_codigomunicipionota"]:$this->q91_codigomunicipionota);
       $this->q91_esferareceita = ($this->q91_esferareceita == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_esferareceita"]:$this->q91_esferareceita);
       $this->q91_anousu = ($this->q91_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_anousu"]:$this->q91_anousu);
       $this->q91_mesusu = ($this->q91_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_mesusu"]:$this->q91_mesusu);
       $this->q91_valorprincipal = ($this->q91_valorprincipal == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_valorprincipal"]:$this->q91_valorprincipal);
       $this->q91_valormulta = ($this->q91_valormulta == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_valormulta"]:$this->q91_valormulta);
       $this->q91_valorjuros = ($this->q91_valorjuros == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_valorjuros"]:$this->q91_valorjuros);
       $this->q91_numeronotafiscal = ($this->q91_numeronotafiscal == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_numeronotafiscal"]:$this->q91_numeronotafiscal);
       $this->q91_serienotafiscal = ($this->q91_serienotafiscal == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_serienotafiscal"]:$this->q91_serienotafiscal);
       $this->q91_subserienotafiscal = ($this->q91_subserienotafiscal == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_subserienotafiscal"]:$this->q91_subserienotafiscal);
       if($this->q91_dataemissaonotafiscal == ""){
         $this->q91_dataemissaonotafiscal_dia = ($this->q91_dataemissaonotafiscal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_dataemissaonotafiscal_dia"]:$this->q91_dataemissaonotafiscal_dia);
         $this->q91_dataemissaonotafiscal_mes = ($this->q91_dataemissaonotafiscal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_dataemissaonotafiscal_mes"]:$this->q91_dataemissaonotafiscal_mes);
         $this->q91_dataemissaonotafiscal_ano = ($this->q91_dataemissaonotafiscal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_dataemissaonotafiscal_ano"]:$this->q91_dataemissaonotafiscal_ano);
         if($this->q91_dataemissaonotafiscal_dia != ""){
            $this->q91_dataemissaonotafiscal = $this->q91_dataemissaonotafiscal_ano."-".$this->q91_dataemissaonotafiscal_mes."-".$this->q91_dataemissaonotafiscal_dia;
         }
       }
       $this->q91_valornotafiscal = ($this->q91_valornotafiscal == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_valornotafiscal"]:$this->q91_valornotafiscal);
       $this->q91_aliquota = ($this->q91_aliquota == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_aliquota"]:$this->q91_aliquota);
       $this->q91_valorbasecalculo = ($this->q91_valorbasecalculo == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_valorbasecalculo"]:$this->q91_valorbasecalculo);
       $this->q91_observacao = ($this->q91_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_observacao"]:$this->q91_observacao);
       $this->q91_codigomunicipiofavorecido = ($this->q91_codigomunicipiofavorecido == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_codigomunicipiofavorecido"]:$this->q91_codigomunicipiofavorecido);
     }else{
       $this->q91_sequencial = ($this->q91_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q91_sequencial"]:$this->q91_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($q91_sequencial){ 
      $this->atualizacampos();
     if($this->q91_issarquivoretencao == null ){ 
       $this->erro_sql = " Campo Código Arquivo de Retenção não informado.";
       $this->erro_campo = "q91_issarquivoretencao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q91_sequencialregistro == null ){ 
       $this->erro_sql = " Campo Sequencial Registro não informado.";
       $this->erro_campo = "q91_sequencialregistro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q91_dataemissaodocumento == null ){ 
       $this->erro_sql = " Campo Data de Emissão não informado.";
       $this->erro_campo = "q91_dataemissaodocumento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q91_datavencimento == null ){ 
       $this->erro_sql = " Campo Data de Vencimento não informado.";
       $this->erro_campo = "q91_datavencimento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q91_cnpjtomador == null ){ 
       $this->erro_sql = " Campo CNPJ Tomador não informado.";
       $this->erro_campo = "q91_cnpjtomador";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q91_codigomunicipiotomador == null ){ 
       $this->erro_sql = " Campo Código Município Tomador não informado.";
       $this->erro_campo = "q91_codigomunicipiotomador";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q91_cpfcnpjprestador == null ){ 
       $this->erro_sql = " Campo CPF ou CNPJ Prestador não informado.";
       $this->erro_campo = "q91_cpfcnpjprestador";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q91_codigomunicipionota == null ){ 
       $this->erro_sql = " Campo Código Múnicípio Nota não informado.";
       $this->erro_campo = "q91_codigomunicipionota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q91_esferareceita == null ){ 
       $this->erro_sql = " Campo Esfera Receita não informado.";
       $this->erro_campo = "q91_esferareceita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q91_anousu == null ){ 
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "q91_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q91_mesusu == null ){ 
       $this->erro_sql = " Campo Mes não informado.";
       $this->erro_campo = "q91_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q91_valorprincipal == null ){ 
       $this->erro_sql = " Campo Valor Principal não informado.";
       $this->erro_campo = "q91_valorprincipal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q91_valormulta == null ){ 
       $this->q91_valormulta = "0";
     }
     if($this->q91_valorjuros == null ){ 
       $this->q91_valorjuros = "0";
     }
     if($this->q91_numeronotafiscal == null ){ 
       $this->erro_sql = " Campo Nota Fiscal não informado.";
       $this->erro_campo = "q91_numeronotafiscal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q91_subserienotafiscal == null ){ 
       $this->q91_subserienotafiscal = "0";
     }
     if($this->q91_dataemissaonotafiscal == null ){ 
       $this->erro_sql = " Campo Data Emissão Nota Fiscal não informado.";
       $this->erro_campo = "q91_dataemissaonotafiscal_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q91_valornotafiscal == null ){ 
       $this->erro_sql = " Campo Valor Nota Fiscal não informado.";
       $this->erro_campo = "q91_valornotafiscal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q91_aliquota == null ){ 
       $this->erro_sql = " Campo Aliquota não informado.";
       $this->erro_campo = "q91_aliquota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q91_valorbasecalculo == null ){ 
       $this->erro_sql = " Campo Valor Base Cálculo não informado.";
       $this->erro_campo = "q91_valorbasecalculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q91_observacao == null ){ 
       $this->erro_sql = " Campo Observações não informado.";
       $this->erro_campo = "q91_observacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q91_codigomunicipiofavorecido == null ){ 
       $this->erro_sql = " Campo Código Município Favorecido não informado.";
       $this->erro_campo = "q91_codigomunicipiofavorecido";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q91_sequencial == "" || $q91_sequencial == null ){
       $result = db_query("select nextval('issarquivoretencaoregistro_q91_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issarquivoretencaoregistro_q91_sequencial_seq do campo: q91_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q91_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from issarquivoretencaoregistro_q91_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q91_sequencial)){
         $this->erro_sql = " Campo q91_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q91_sequencial = $q91_sequencial; 
       }
     }
     if(($this->q91_sequencial == null) || ($this->q91_sequencial == "") ){ 
       $this->erro_sql = " Campo q91_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issarquivoretencaoregistro(
                                       q91_sequencial 
                                      ,q91_issarquivoretencao 
                                      ,q91_sequencialregistro 
                                      ,q91_dataemissaodocumento 
                                      ,q91_datavencimento 
                                      ,q91_numerodocumento 
                                      ,q91_cnpjtomador 
                                      ,q91_codigomunicipiotomador 
                                      ,q91_cpfcnpjprestador 
                                      ,q91_codigomunicipionota 
                                      ,q91_esferareceita 
                                      ,q91_anousu 
                                      ,q91_mesusu 
                                      ,q91_valorprincipal 
                                      ,q91_valormulta 
                                      ,q91_valorjuros 
                                      ,q91_numeronotafiscal 
                                      ,q91_serienotafiscal 
                                      ,q91_subserienotafiscal 
                                      ,q91_dataemissaonotafiscal 
                                      ,q91_valornotafiscal 
                                      ,q91_aliquota 
                                      ,q91_valorbasecalculo 
                                      ,q91_observacao 
                                      ,q91_codigomunicipiofavorecido 
                       )
                values (
                                $this->q91_sequencial 
                               ,$this->q91_issarquivoretencao 
                               ,$this->q91_sequencialregistro 
                               ,".($this->q91_dataemissaodocumento == "null" || $this->q91_dataemissaodocumento == ""?"null":"'".$this->q91_dataemissaodocumento."'")." 
                               ,".($this->q91_datavencimento == "null" || $this->q91_datavencimento == ""?"null":"'".$this->q91_datavencimento."'")." 
                               ,'$this->q91_numerodocumento' 
                               ,'$this->q91_cnpjtomador' 
                               ,$this->q91_codigomunicipiotomador 
                               ,'$this->q91_cpfcnpjprestador' 
                               ,$this->q91_codigomunicipionota 
                               ,'$this->q91_esferareceita' 
                               ,$this->q91_anousu 
                               ,$this->q91_mesusu 
                               ,$this->q91_valorprincipal 
                               ,$this->q91_valormulta 
                               ,$this->q91_valorjuros 
                               ,$this->q91_numeronotafiscal 
                               ,'$this->q91_serienotafiscal' 
                               ,$this->q91_subserienotafiscal 
                               ,".($this->q91_dataemissaonotafiscal == "null" || $this->q91_dataemissaonotafiscal == ""?"null":"'".$this->q91_dataemissaonotafiscal."'")." 
                               ,$this->q91_valornotafiscal 
                               ,$this->q91_aliquota 
                               ,$this->q91_valorbasecalculo 
                               ,'$this->q91_observacao' 
                               ,$this->q91_codigomunicipiofavorecido 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Registros Arquivo Retenção ($this->q91_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Registros Arquivo Retenção já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Registros Arquivo Retenção ($this->q91_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q91_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q91_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21077,'$this->q91_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3797,21077,'','".AddSlashes(pg_result($resaco,0,'q91_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21078,'','".AddSlashes(pg_result($resaco,0,'q91_issarquivoretencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21079,'','".AddSlashes(pg_result($resaco,0,'q91_sequencialregistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21080,'','".AddSlashes(pg_result($resaco,0,'q91_dataemissaodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21081,'','".AddSlashes(pg_result($resaco,0,'q91_datavencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21082,'','".AddSlashes(pg_result($resaco,0,'q91_numerodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21083,'','".AddSlashes(pg_result($resaco,0,'q91_cnpjtomador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21084,'','".AddSlashes(pg_result($resaco,0,'q91_codigomunicipiotomador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21085,'','".AddSlashes(pg_result($resaco,0,'q91_cpfcnpjprestador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21086,'','".AddSlashes(pg_result($resaco,0,'q91_codigomunicipionota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21087,'','".AddSlashes(pg_result($resaco,0,'q91_esferareceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21088,'','".AddSlashes(pg_result($resaco,0,'q91_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21090,'','".AddSlashes(pg_result($resaco,0,'q91_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21092,'','".AddSlashes(pg_result($resaco,0,'q91_valorprincipal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21093,'','".AddSlashes(pg_result($resaco,0,'q91_valormulta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21094,'','".AddSlashes(pg_result($resaco,0,'q91_valorjuros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21096,'','".AddSlashes(pg_result($resaco,0,'q91_numeronotafiscal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21098,'','".AddSlashes(pg_result($resaco,0,'q91_serienotafiscal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21099,'','".AddSlashes(pg_result($resaco,0,'q91_subserienotafiscal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21101,'','".AddSlashes(pg_result($resaco,0,'q91_dataemissaonotafiscal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21102,'','".AddSlashes(pg_result($resaco,0,'q91_valornotafiscal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21103,'','".AddSlashes(pg_result($resaco,0,'q91_aliquota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21104,'','".AddSlashes(pg_result($resaco,0,'q91_valorbasecalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21105,'','".AddSlashes(pg_result($resaco,0,'q91_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3797,21106,'','".AddSlashes(pg_result($resaco,0,'q91_codigomunicipiofavorecido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($q91_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update issarquivoretencaoregistro set ";
     $virgula = "";
     if(trim($this->q91_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_sequencial"])){ 
       $sql  .= $virgula." q91_sequencial = $this->q91_sequencial ";
       $virgula = ",";
       if(trim($this->q91_sequencial) == null ){ 
         $this->erro_sql = " Campo Código do Registro não informado.";
         $this->erro_campo = "q91_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q91_issarquivoretencao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_issarquivoretencao"])){ 
       $sql  .= $virgula." q91_issarquivoretencao = $this->q91_issarquivoretencao ";
       $virgula = ",";
       if(trim($this->q91_issarquivoretencao) == null ){ 
         $this->erro_sql = " Campo Código Arquivo de Retenção não informado.";
         $this->erro_campo = "q91_issarquivoretencao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q91_sequencialregistro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_sequencialregistro"])){ 
       $sql  .= $virgula." q91_sequencialregistro = $this->q91_sequencialregistro ";
       $virgula = ",";
       if(trim($this->q91_sequencialregistro) == null ){ 
         $this->erro_sql = " Campo Sequencial Registro não informado.";
         $this->erro_campo = "q91_sequencialregistro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q91_dataemissaodocumento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_dataemissaodocumento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q91_dataemissaodocumento_dia"] !="") ){ 
       $sql  .= $virgula." q91_dataemissaodocumento = '$this->q91_dataemissaodocumento' ";
       $virgula = ",";
       if(trim($this->q91_dataemissaodocumento) == null ){ 
         $this->erro_sql = " Campo Data de Emissão não informado.";
         $this->erro_campo = "q91_dataemissaodocumento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q91_dataemissaodocumento_dia"])){ 
         $sql  .= $virgula." q91_dataemissaodocumento = null ";
         $virgula = ",";
         if(trim($this->q91_dataemissaodocumento) == null ){ 
           $this->erro_sql = " Campo Data de Emissão não informado.";
           $this->erro_campo = "q91_dataemissaodocumento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q91_datavencimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_datavencimento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q91_datavencimento_dia"] !="") ){ 
       $sql  .= $virgula." q91_datavencimento = '$this->q91_datavencimento' ";
       $virgula = ",";
       if(trim($this->q91_datavencimento) == null ){ 
         $this->erro_sql = " Campo Data de Vencimento não informado.";
         $this->erro_campo = "q91_datavencimento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q91_datavencimento_dia"])){ 
         $sql  .= $virgula." q91_datavencimento = null ";
         $virgula = ",";
         if(trim($this->q91_datavencimento) == null ){ 
           $this->erro_sql = " Campo Data de Vencimento não informado.";
           $this->erro_campo = "q91_datavencimento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q91_numerodocumento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_numerodocumento"])){ 
       $sql  .= $virgula." q91_numerodocumento = '$this->q91_numerodocumento' ";
       $virgula = ",";
     }
     if(trim($this->q91_cnpjtomador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_cnpjtomador"])){ 
       $sql  .= $virgula." q91_cnpjtomador = '$this->q91_cnpjtomador' ";
       $virgula = ",";
       if(trim($this->q91_cnpjtomador) == null ){ 
         $this->erro_sql = " Campo CNPJ Tomador não informado.";
         $this->erro_campo = "q91_cnpjtomador";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q91_codigomunicipiotomador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_codigomunicipiotomador"])){ 
       $sql  .= $virgula." q91_codigomunicipiotomador = $this->q91_codigomunicipiotomador ";
       $virgula = ",";
       if(trim($this->q91_codigomunicipiotomador) == null ){ 
         $this->erro_sql = " Campo Código Município Tomador não informado.";
         $this->erro_campo = "q91_codigomunicipiotomador";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q91_cpfcnpjprestador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_cpfcnpjprestador"])){ 
       $sql  .= $virgula." q91_cpfcnpjprestador = '$this->q91_cpfcnpjprestador' ";
       $virgula = ",";
       if(trim($this->q91_cpfcnpjprestador) == null ){ 
         $this->erro_sql = " Campo CPF ou CNPJ Prestador não informado.";
         $this->erro_campo = "q91_cpfcnpjprestador";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q91_codigomunicipionota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_codigomunicipionota"])){ 
       $sql  .= $virgula." q91_codigomunicipionota = $this->q91_codigomunicipionota ";
       $virgula = ",";
       if(trim($this->q91_codigomunicipionota) == null ){ 
         $this->erro_sql = " Campo Código Múnicípio Nota não informado.";
         $this->erro_campo = "q91_codigomunicipionota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q91_esferareceita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_esferareceita"])){ 
       $sql  .= $virgula." q91_esferareceita = '$this->q91_esferareceita' ";
       $virgula = ",";
       if(trim($this->q91_esferareceita) == null ){ 
         $this->erro_sql = " Campo Esfera Receita não informado.";
         $this->erro_campo = "q91_esferareceita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q91_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_anousu"])){ 
       $sql  .= $virgula." q91_anousu = $this->q91_anousu ";
       $virgula = ",";
       if(trim($this->q91_anousu) == null ){ 
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "q91_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q91_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_mesusu"])){ 
       $sql  .= $virgula." q91_mesusu = $this->q91_mesusu ";
       $virgula = ",";
       if(trim($this->q91_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes não informado.";
         $this->erro_campo = "q91_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q91_valorprincipal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_valorprincipal"])){ 
       $sql  .= $virgula." q91_valorprincipal = $this->q91_valorprincipal ";
       $virgula = ",";
       if(trim($this->q91_valorprincipal) == null ){ 
         $this->erro_sql = " Campo Valor Principal não informado.";
         $this->erro_campo = "q91_valorprincipal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q91_valormulta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_valormulta"])){ 
        if(trim($this->q91_valormulta)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q91_valormulta"])){ 
           $this->q91_valormulta = "0" ; 
        } 
       $sql  .= $virgula." q91_valormulta = $this->q91_valormulta ";
       $virgula = ",";
     }
     if(trim($this->q91_valorjuros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_valorjuros"])){ 
        if(trim($this->q91_valorjuros)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q91_valorjuros"])){ 
           $this->q91_valorjuros = "0" ; 
        } 
       $sql  .= $virgula." q91_valorjuros = $this->q91_valorjuros ";
       $virgula = ",";
     }
     if(trim($this->q91_numeronotafiscal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_numeronotafiscal"])){ 
       $sql  .= $virgula." q91_numeronotafiscal = $this->q91_numeronotafiscal ";
       $virgula = ",";
       if(trim($this->q91_numeronotafiscal) == null ){ 
         $this->erro_sql = " Campo Nota Fiscal não informado.";
         $this->erro_campo = "q91_numeronotafiscal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q91_serienotafiscal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_serienotafiscal"])){ 
       $sql  .= $virgula." q91_serienotafiscal = '$this->q91_serienotafiscal' ";
       $virgula = ",";
     }
     if(trim($this->q91_subserienotafiscal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_subserienotafiscal"])){ 
        if(trim($this->q91_subserienotafiscal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q91_subserienotafiscal"])){ 
           $this->q91_subserienotafiscal = "0" ; 
        } 
       $sql  .= $virgula." q91_subserienotafiscal = $this->q91_subserienotafiscal ";
       $virgula = ",";
     }
     if(trim($this->q91_dataemissaonotafiscal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_dataemissaonotafiscal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q91_dataemissaonotafiscal_dia"] !="") ){ 
       $sql  .= $virgula." q91_dataemissaonotafiscal = '$this->q91_dataemissaonotafiscal' ";
       $virgula = ",";
       if(trim($this->q91_dataemissaonotafiscal) == null ){ 
         $this->erro_sql = " Campo Data Emissão Nota Fiscal não informado.";
         $this->erro_campo = "q91_dataemissaonotafiscal_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q91_dataemissaonotafiscal_dia"])){ 
         $sql  .= $virgula." q91_dataemissaonotafiscal = null ";
         $virgula = ",";
         if(trim($this->q91_dataemissaonotafiscal) == null ){ 
           $this->erro_sql = " Campo Data Emissão Nota Fiscal não informado.";
           $this->erro_campo = "q91_dataemissaonotafiscal_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q91_valornotafiscal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_valornotafiscal"])){ 
       $sql  .= $virgula." q91_valornotafiscal = $this->q91_valornotafiscal ";
       $virgula = ",";
       if(trim($this->q91_valornotafiscal) == null ){ 
         $this->erro_sql = " Campo Valor Nota Fiscal não informado.";
         $this->erro_campo = "q91_valornotafiscal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q91_aliquota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_aliquota"])){ 
       $sql  .= $virgula." q91_aliquota = $this->q91_aliquota ";
       $virgula = ",";
       if(trim($this->q91_aliquota) == null ){ 
         $this->erro_sql = " Campo Aliquota não informado.";
         $this->erro_campo = "q91_aliquota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q91_valorbasecalculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_valorbasecalculo"])){ 
       $sql  .= $virgula." q91_valorbasecalculo = $this->q91_valorbasecalculo ";
       $virgula = ",";
       if(trim($this->q91_valorbasecalculo) == null ){ 
         $this->erro_sql = " Campo Valor Base Cálculo não informado.";
         $this->erro_campo = "q91_valorbasecalculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q91_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_observacao"])){ 
       $sql  .= $virgula." q91_observacao = '$this->q91_observacao' ";
       $virgula = ",";
       if(trim($this->q91_observacao) == null ){ 
         $this->erro_sql = " Campo Observações não informado.";
         $this->erro_campo = "q91_observacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q91_codigomunicipiofavorecido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q91_codigomunicipiofavorecido"])){ 
       $sql  .= $virgula." q91_codigomunicipiofavorecido = $this->q91_codigomunicipiofavorecido ";
       $virgula = ",";
       if(trim($this->q91_codigomunicipiofavorecido) == null ){ 
         $this->erro_sql = " Campo Código Município Favorecido não informado.";
         $this->erro_campo = "q91_codigomunicipiofavorecido";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q91_sequencial!=null){
       $sql .= " q91_sequencial = $this->q91_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q91_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21077,'$this->q91_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_sequencial"]) || $this->q91_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3797,21077,'".AddSlashes(pg_result($resaco,$conresaco,'q91_sequencial'))."','$this->q91_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_issarquivoretencao"]) || $this->q91_issarquivoretencao != "")
             $resac = db_query("insert into db_acount values($acount,3797,21078,'".AddSlashes(pg_result($resaco,$conresaco,'q91_issarquivoretencao'))."','$this->q91_issarquivoretencao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_sequencialregistro"]) || $this->q91_sequencialregistro != "")
             $resac = db_query("insert into db_acount values($acount,3797,21079,'".AddSlashes(pg_result($resaco,$conresaco,'q91_sequencialregistro'))."','$this->q91_sequencialregistro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_dataemissaodocumento"]) || $this->q91_dataemissaodocumento != "")
             $resac = db_query("insert into db_acount values($acount,3797,21080,'".AddSlashes(pg_result($resaco,$conresaco,'q91_dataemissaodocumento'))."','$this->q91_dataemissaodocumento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_datavencimento"]) || $this->q91_datavencimento != "")
             $resac = db_query("insert into db_acount values($acount,3797,21081,'".AddSlashes(pg_result($resaco,$conresaco,'q91_datavencimento'))."','$this->q91_datavencimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_numerodocumento"]) || $this->q91_numerodocumento != "")
             $resac = db_query("insert into db_acount values($acount,3797,21082,'".AddSlashes(pg_result($resaco,$conresaco,'q91_numerodocumento'))."','$this->q91_numerodocumento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_cnpjtomador"]) || $this->q91_cnpjtomador != "")
             $resac = db_query("insert into db_acount values($acount,3797,21083,'".AddSlashes(pg_result($resaco,$conresaco,'q91_cnpjtomador'))."','$this->q91_cnpjtomador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_codigomunicipiotomador"]) || $this->q91_codigomunicipiotomador != "")
             $resac = db_query("insert into db_acount values($acount,3797,21084,'".AddSlashes(pg_result($resaco,$conresaco,'q91_codigomunicipiotomador'))."','$this->q91_codigomunicipiotomador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_cpfcnpjprestador"]) || $this->q91_cpfcnpjprestador != "")
             $resac = db_query("insert into db_acount values($acount,3797,21085,'".AddSlashes(pg_result($resaco,$conresaco,'q91_cpfcnpjprestador'))."','$this->q91_cpfcnpjprestador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_codigomunicipionota"]) || $this->q91_codigomunicipionota != "")
             $resac = db_query("insert into db_acount values($acount,3797,21086,'".AddSlashes(pg_result($resaco,$conresaco,'q91_codigomunicipionota'))."','$this->q91_codigomunicipionota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_esferareceita"]) || $this->q91_esferareceita != "")
             $resac = db_query("insert into db_acount values($acount,3797,21087,'".AddSlashes(pg_result($resaco,$conresaco,'q91_esferareceita'))."','$this->q91_esferareceita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_anousu"]) || $this->q91_anousu != "")
             $resac = db_query("insert into db_acount values($acount,3797,21088,'".AddSlashes(pg_result($resaco,$conresaco,'q91_anousu'))."','$this->q91_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_mesusu"]) || $this->q91_mesusu != "")
             $resac = db_query("insert into db_acount values($acount,3797,21090,'".AddSlashes(pg_result($resaco,$conresaco,'q91_mesusu'))."','$this->q91_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_valorprincipal"]) || $this->q91_valorprincipal != "")
             $resac = db_query("insert into db_acount values($acount,3797,21092,'".AddSlashes(pg_result($resaco,$conresaco,'q91_valorprincipal'))."','$this->q91_valorprincipal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_valormulta"]) || $this->q91_valormulta != "")
             $resac = db_query("insert into db_acount values($acount,3797,21093,'".AddSlashes(pg_result($resaco,$conresaco,'q91_valormulta'))."','$this->q91_valormulta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_valorjuros"]) || $this->q91_valorjuros != "")
             $resac = db_query("insert into db_acount values($acount,3797,21094,'".AddSlashes(pg_result($resaco,$conresaco,'q91_valorjuros'))."','$this->q91_valorjuros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_numeronotafiscal"]) || $this->q91_numeronotafiscal != "")
             $resac = db_query("insert into db_acount values($acount,3797,21096,'".AddSlashes(pg_result($resaco,$conresaco,'q91_numeronotafiscal'))."','$this->q91_numeronotafiscal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_serienotafiscal"]) || $this->q91_serienotafiscal != "")
             $resac = db_query("insert into db_acount values($acount,3797,21098,'".AddSlashes(pg_result($resaco,$conresaco,'q91_serienotafiscal'))."','$this->q91_serienotafiscal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_subserienotafiscal"]) || $this->q91_subserienotafiscal != "")
             $resac = db_query("insert into db_acount values($acount,3797,21099,'".AddSlashes(pg_result($resaco,$conresaco,'q91_subserienotafiscal'))."','$this->q91_subserienotafiscal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_dataemissaonotafiscal"]) || $this->q91_dataemissaonotafiscal != "")
             $resac = db_query("insert into db_acount values($acount,3797,21101,'".AddSlashes(pg_result($resaco,$conresaco,'q91_dataemissaonotafiscal'))."','$this->q91_dataemissaonotafiscal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_valornotafiscal"]) || $this->q91_valornotafiscal != "")
             $resac = db_query("insert into db_acount values($acount,3797,21102,'".AddSlashes(pg_result($resaco,$conresaco,'q91_valornotafiscal'))."','$this->q91_valornotafiscal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_aliquota"]) || $this->q91_aliquota != "")
             $resac = db_query("insert into db_acount values($acount,3797,21103,'".AddSlashes(pg_result($resaco,$conresaco,'q91_aliquota'))."','$this->q91_aliquota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_valorbasecalculo"]) || $this->q91_valorbasecalculo != "")
             $resac = db_query("insert into db_acount values($acount,3797,21104,'".AddSlashes(pg_result($resaco,$conresaco,'q91_valorbasecalculo'))."','$this->q91_valorbasecalculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_observacao"]) || $this->q91_observacao != "")
             $resac = db_query("insert into db_acount values($acount,3797,21105,'".AddSlashes(pg_result($resaco,$conresaco,'q91_observacao'))."','$this->q91_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q91_codigomunicipiofavorecido"]) || $this->q91_codigomunicipiofavorecido != "")
             $resac = db_query("insert into db_acount values($acount,3797,21106,'".AddSlashes(pg_result($resaco,$conresaco,'q91_codigomunicipiofavorecido'))."','$this->q91_codigomunicipiofavorecido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registros Arquivo Retenção não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q91_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Registros Arquivo Retenção não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q91_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q91_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($q91_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($q91_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21077,'$q91_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3797,21077,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21078,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_issarquivoretencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21079,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_sequencialregistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21080,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_dataemissaodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21081,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_datavencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21082,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_numerodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21083,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_cnpjtomador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21084,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_codigomunicipiotomador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21085,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_cpfcnpjprestador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21086,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_codigomunicipionota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21087,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_esferareceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21088,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21090,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21092,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_valorprincipal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21093,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_valormulta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21094,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_valorjuros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21096,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_numeronotafiscal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21098,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_serienotafiscal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21099,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_subserienotafiscal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21101,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_dataemissaonotafiscal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21102,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_valornotafiscal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21103,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_aliquota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21104,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_valorbasecalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21105,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3797,21106,'','".AddSlashes(pg_result($resaco,$iresaco,'q91_codigomunicipiofavorecido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from issarquivoretencaoregistro
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($q91_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " q91_sequencial = $q91_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registros Arquivo Retenção não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q91_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Registros Arquivo Retenção não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q91_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q91_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   public function sql_record($sql) { 
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:issarquivoretencaoregistro";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($q91_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from issarquivoretencaoregistro ";
     $sql .= "      inner join issarquivoretencao  on  issarquivoretencao.q90_sequencial = issarquivoretencaoregistro.q91_issarquivoretencao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($q91_sequencial)) {
         $sql2 .= " where issarquivoretencaoregistro.q91_sequencial = $q91_sequencial "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql 
   public function sql_query_file ($q91_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from issarquivoretencaoregistro ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($q91_sequencial)){
         $sql2 .= " where issarquivoretencaoregistro.q91_sequencial = $q91_sequencial "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

}
