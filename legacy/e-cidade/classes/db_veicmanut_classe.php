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

//MODULO: veiculos
//CLASSE DA ENTIDADE veicmanut
class cl_veicmanut {
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
   var $ve62_codigo = 0;
   var $ve62_veiculos = 0;
   var $ve62_dtmanut_dia = null;
   var $ve62_dtmanut_mes = null;
   var $ve62_dtmanut_ano = null;
   var $ve62_dtmanut = null;
   var $ve62_vlrmobra = 0;
   var $ve62_vlrpecas = 0;
   var $ve62_descr = null;
   var $ve62_notafisc = null;
   var $ve62_veiccadtiposervico = 0;
   var $ve62_usuario = 0;
   var $ve62_data_dia = null;
   var $ve62_data_mes = null;
   var $ve62_data_ano = null;
   var $ve62_data = null;
   var $ve62_hora = null;
   var $ve62_medida = 0;
   var $ve62_observacao = null;
   var $ve62_situacao = 0;
   var $ve62_numero = null;
   var $ve62_anousu = null;
   var $ve62_veicmotoristas = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ve62_codigo = int4 = Código Manutenção
                 ve62_veiculos = int4 = Veiculo
                 ve62_dtmanut = date = Data
                 ve62_vlrmobra = float8 = Valor da Mão de Obra
                 ve62_vlrpecas = float8 = Valor em Peças
                 ve62_descr = varchar(60) = Serviço Executado
                 ve62_notafisc = varchar(10) = Nº da Nota Fiscal
                 ve62_veiccadtiposervico = int4 = Tipo de Serviço
                 ve62_usuario = int4 = Usuário
                 ve62_data = date = Data da Inclusão/Alteração
                 ve62_hora = char(5) = Hora da Inclusão/Alteração
                 ve62_medida = float8 = Medida
                 ve62_observacao = text = Observação
                 ve62_situacao = int4 = Situação
                 ve62_numero = int4 = Número
                 ve62_anousu = int4 = Ano
                 ve62_veicmotoristas = int4 = Motorista
                 ";
   //funcao construtor da classe
   function cl_veicmanut() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("veicmanut");
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
       $this->ve62_codigo = ($this->ve62_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve62_codigo"]:$this->ve62_codigo);
       $this->ve62_veiculos = ($this->ve62_veiculos == ""?@$GLOBALS["HTTP_POST_VARS"]["ve62_veiculos"]:$this->ve62_veiculos);
       if($this->ve62_dtmanut == ""){
         $this->ve62_dtmanut_dia = ($this->ve62_dtmanut_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve62_dtmanut_dia"]:$this->ve62_dtmanut_dia);
         $this->ve62_dtmanut_mes = ($this->ve62_dtmanut_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ve62_dtmanut_mes"]:$this->ve62_dtmanut_mes);
         $this->ve62_dtmanut_ano = ($this->ve62_dtmanut_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ve62_dtmanut_ano"]:$this->ve62_dtmanut_ano);
         if($this->ve62_dtmanut_dia != ""){
            $this->ve62_dtmanut = $this->ve62_dtmanut_ano."-".$this->ve62_dtmanut_mes."-".$this->ve62_dtmanut_dia;
         }
       }
       $this->ve62_vlrpecas = (($this->ve62_vlrpecas === "" || $this->ve62_vlrpecas === null) ? @$GLOBALS["HTTP_POST_VARS"]["ve62_vlrpecas"] : $this->ve62_vlrpecas);
       $this->ve62_vlrmobra = (($this->ve62_vlrmobra === "" || $this->ve62_vlrmobra === null) ? @$GLOBALS["HTTP_POST_VARS"]["ve62_vlrmobra"] : $this->ve62_vlrmobra);
       $this->ve62_descr = ($this->ve62_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ve62_descr"]:$this->ve62_descr);
       $this->ve62_notafisc = ($this->ve62_notafisc == ""?@$GLOBALS["HTTP_POST_VARS"]["ve62_notafisc"]:$this->ve62_notafisc);
       $this->ve62_veiccadtiposervico = ($this->ve62_veiccadtiposervico == ""?@$GLOBALS["HTTP_POST_VARS"]["ve62_veiccadtiposervico"]:$this->ve62_veiccadtiposervico);
       $this->ve62_usuario = ($this->ve62_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ve62_usuario"]:$this->ve62_usuario);
       if($this->ve62_data == ""){
         $this->ve62_data_dia = ($this->ve62_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve62_data_dia"]:$this->ve62_data_dia);
         $this->ve62_data_mes = ($this->ve62_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ve62_data_mes"]:$this->ve62_data_mes);
         $this->ve62_data_ano = ($this->ve62_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ve62_data_ano"]:$this->ve62_data_ano);
         if($this->ve62_data_dia != ""){
            $this->ve62_data = $this->ve62_data_ano."-".$this->ve62_data_mes."-".$this->ve62_data_dia;
         }
       }
       $this->ve62_hora = ($this->ve62_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ve62_hora"]:$this->ve62_hora);
       $this->ve62_medida = ($this->ve62_medida == ""?@$GLOBALS["HTTP_POST_VARS"]["ve62_medida"]:$this->ve62_medida);
       $this->ve62_observacao = ($this->ve62_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ve62_observacao"]:$this->ve62_observacao);
       $this->ve62_situacao = ($this->ve62_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ve62_situacao"]:$this->ve62_situacao);
       $this->ve62_numero = ($this->ve62_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["ve62_numero"]:$this->ve62_numero);
       $this->ve62_anousu = ($this->ve62_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["ve62_anousu"]:$this->ve62_anousu);
       $this->ve62_veicmotoristas = ($this->ve62_veicmotoristas == ""?@$GLOBALS["HTTP_POST_VARS"]["ve62_veicmotoristas"]:$this->ve62_veicmotoristas);
     }else{
       $this->ve62_codigo = ($this->ve62_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve62_codigo"]:$this->ve62_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($ve62_codigo){
      $this->atualizacampos();
     if($this->ve62_veiculos == null ){
       $this->erro_sql = " Campo Veículo não informado.";
       $this->erro_campo = "ve62_veiculos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve62_dtmanut == null ){
       $this->erro_sql = " Campo Data não informado.";
       $this->erro_campo = "ve62_dtmanut_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve62_vlrmobra === null ){
       $this->erro_sql = " Campo Valor da Mão de Obra não informado.";
       $this->erro_campo = "ve62_vlrmobra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve62_vlrpecas === null ){
       $this->erro_sql = " Campo Valor em Peças não informado.";
       $this->erro_campo = "ve62_vlrpecas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve62_descr == null ){
       $this->erro_sql = " Campo Serviço Executado não informado.";
       $this->erro_campo = "ve62_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve62_veiccadtiposervico == null ){
       $this->erro_sql = " Campo Tipo de Serviço não informado.";
       $this->erro_campo = "ve62_veiccadtiposervico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve62_usuario == null ){
       $this->erro_sql = " Campo Usuário não informado.";
       $this->erro_campo = "ve62_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve62_data == null ){
       $this->erro_sql = " Campo Data da Inclusão/Alteração não informado.";
       $this->erro_campo = "ve62_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve62_hora == null ){
       $this->erro_sql = " Campo Hora da Inclusão/Alteração não informado.";
       $this->erro_campo = "ve62_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve62_medida == null ){
       $this->erro_sql = " Campo Medida não informado.";
       $this->erro_campo = "ve62_medida";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve62_situacao == null ){
       $this->erro_sql = " Campo Situação não informado.";
       $this->erro_campo = "ve62_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve62_numero == null ){
       $this->erro_sql = " Campo Número não informado.";
       $this->erro_campo = "ve62_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve62_anousu == null ){
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "ve62_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ve62_codigo == "" || $ve62_codigo == null ){
       $result = db_query("select nextval('veicmanut_ve62_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: veicmanut_ve62_codigo_seq do campo: ve62_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ve62_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from veicmanut_ve62_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ve62_codigo)){
         $this->erro_sql = " Campo ve62_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ve62_codigo = $ve62_codigo;
       }
     }
     if(($this->ve62_codigo == null) || ($this->ve62_codigo == "") ){
       $this->erro_sql = " Campo ve62_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into veicmanut(
                                       ve62_codigo
                                      ,ve62_veiculos
                                      ,ve62_dtmanut
                                      ,ve62_vlrmobra
                                      ,ve62_vlrpecas
                                      ,ve62_descr
                                      ,ve62_notafisc
                                      ,ve62_veiccadtiposervico
                                      ,ve62_usuario
                                      ,ve62_data
                                      ,ve62_hora
                                      ,ve62_medida
                                      ,ve62_observacao
                                      ,ve62_situacao
                                      ,ve62_numero
                                      ,ve62_anousu
                                      ,ve62_veicmotoristas
                       )
                values (
                                $this->ve62_codigo
                               ,$this->ve62_veiculos
                               ,".($this->ve62_dtmanut == "null" || $this->ve62_dtmanut == ""?"null":"'".$this->ve62_dtmanut."'")."
                               ,$this->ve62_vlrmobra
                               ,$this->ve62_vlrpecas
                               ,'$this->ve62_descr'
                               ,'$this->ve62_notafisc'
                               ,$this->ve62_veiccadtiposervico
                               ,$this->ve62_usuario
                               ,".($this->ve62_data == "null" || $this->ve62_data == ""?"null":"'".$this->ve62_data."'")."
                               ,'$this->ve62_hora'
                               ,$this->ve62_medida
                               ,'$this->ve62_observacao'
                               ,$this->ve62_situacao
                               ,$this->ve62_numero
                               ,$this->ve62_anousu
                               ,$this->ve62_veicmotoristas
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Manutenção dos Veículos ($this->ve62_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Manutenção dos Veículos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Manutenção dos Veículos ($this->ve62_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve62_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ve62_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9327,'$this->ve62_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1603,9327,'','".AddSlashes(pg_result($resaco,0,'ve62_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1603,9352,'','".AddSlashes(pg_result($resaco,0,'ve62_veiculos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1603,9328,'','".AddSlashes(pg_result($resaco,0,'ve62_dtmanut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1603,9329,'','".AddSlashes(pg_result($resaco,0,'ve62_vlrmobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1603,9330,'','".AddSlashes(pg_result($resaco,0,'ve62_vlrpecas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1603,9331,'','".AddSlashes(pg_result($resaco,0,'ve62_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1603,9332,'','".AddSlashes(pg_result($resaco,0,'ve62_notafisc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1603,9334,'','".AddSlashes(pg_result($resaco,0,'ve62_veiccadtiposervico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1603,9335,'','".AddSlashes(pg_result($resaco,0,'ve62_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1603,9336,'','".AddSlashes(pg_result($resaco,0,'ve62_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1603,9337,'','".AddSlashes(pg_result($resaco,0,'ve62_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1603,11084,'','".AddSlashes(pg_result($resaco,0,'ve62_medida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1603,18840,'','".AddSlashes(pg_result($resaco,0,'ve62_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1603,21351,'','".AddSlashes(pg_result($resaco,0,'ve62_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1603,21352,'','".AddSlashes(pg_result($resaco,0,'ve62_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1603,21353,'','".AddSlashes(pg_result($resaco,0,'ve62_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1603,21376,'','".AddSlashes(pg_result($resaco,0,'ve62_veicmotoristas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($ve62_codigo=null) {
      $this->atualizacampos();
     $sql = " update veicmanut set ";
     $virgula = "";
     if(trim($this->ve62_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve62_codigo"])){
       $sql  .= $virgula." ve62_codigo = $this->ve62_codigo ";
       $virgula = ",";
       if(trim($this->ve62_codigo) == null ){
         $this->erro_sql = " Campo Código Manutenção não informado.";
         $this->erro_campo = "ve62_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve62_veiculos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve62_veiculos"])){
       $sql  .= $virgula." ve62_veiculos = $this->ve62_veiculos ";
       $virgula = ",";
       if(trim($this->ve62_veiculos) == null ){
         $this->erro_sql = " Campo Veículo não informado.";
         $this->erro_campo = "ve62_veiculos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve62_dtmanut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve62_dtmanut_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ve62_dtmanut_dia"] !="") ){
       $sql  .= $virgula." ve62_dtmanut = '$this->ve62_dtmanut' ";
       $virgula = ",";
       if(trim($this->ve62_dtmanut) == null ){
         $this->erro_sql = " Campo Data não informado.";
         $this->erro_campo = "ve62_dtmanut_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ve62_dtmanut_dia"])){
         $sql  .= $virgula." ve62_dtmanut = null ";
         $virgula = ",";
         if(trim($this->ve62_dtmanut) == null ){
           $this->erro_sql = " Campo Data não informado.";
           $this->erro_campo = "ve62_dtmanut_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ve62_vlrmobra) !== "" || $this->ve62_vlrmobra !== null || trim($this->ve62_vlrmobra) === 0 || isset($GLOBALS["HTTP_POST_VARS"]["ve62_vlrmobra"])) {
       $sql  .= $virgula." ve62_vlrmobra = $this->ve62_vlrmobra ";
       $virgula = ",";
       if(trim($this->ve62_vlrmobra) == null ){
         $this->erro_sql = " Campo Valor da Mão de Obra não informado.";
         $this->erro_campo = "ve62_vlrmobra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve62_vlrpecas) !== "" || $this->ve62_vlrpecas !== null || isset($GLOBALS["HTTP_POST_VARS"]["ve62_vlrpecas"])) {
       $sql  .= $virgula." ve62_vlrpecas = $this->ve62_vlrpecas ";
       $virgula = ",";
       if(trim($this->ve62_vlrpecas) == null ){
         $this->erro_sql = " Campo Valor em Peças não informado.";
         $this->erro_campo = "ve62_vlrpecas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve62_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve62_descr"])){
       $sql  .= $virgula." ve62_descr = '$this->ve62_descr' ";
       $virgula = ",";
       if(trim($this->ve62_descr) == null ){
         $this->erro_sql = " Campo Serviço Executado não informado.";
         $this->erro_campo = "ve62_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve62_notafisc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve62_notafisc"])){
       $sql  .= $virgula." ve62_notafisc = '$this->ve62_notafisc' ";
       $virgula = ",";
     }
     if(trim($this->ve62_veiccadtiposervico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve62_veiccadtiposervico"])){
       $sql  .= $virgula." ve62_veiccadtiposervico = $this->ve62_veiccadtiposervico ";
       $virgula = ",";
       if(trim($this->ve62_veiccadtiposervico) == null ){
         $this->erro_sql = " Campo Tipo de Serviço não informado.";
         $this->erro_campo = "ve62_veiccadtiposervico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve62_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve62_usuario"])){
       $sql  .= $virgula." ve62_usuario = $this->ve62_usuario ";
       $virgula = ",";
       if(trim($this->ve62_usuario) == null ){
         $this->erro_sql = " Campo Usuário não informado.";
         $this->erro_campo = "ve62_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve62_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve62_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ve62_data_dia"] !="") ){
       $sql  .= $virgula." ve62_data = '$this->ve62_data' ";
       $virgula = ",";
       if(trim($this->ve62_data) == null ){
         $this->erro_sql = " Campo Data da Inclusão/Alteração não informado.";
         $this->erro_campo = "ve62_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ve62_data_dia"])){
         $sql  .= $virgula." ve62_data = null ";
         $virgula = ",";
         if(trim($this->ve62_data) == null ){
           $this->erro_sql = " Campo Data da Inclusão/Alteração não informado.";
           $this->erro_campo = "ve62_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ve62_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve62_hora"])){
       $sql  .= $virgula." ve62_hora = '$this->ve62_hora' ";
       $virgula = ",";
       if(trim($this->ve62_hora) == null ){
         $this->erro_sql = " Campo Hora da Inclusão/Alteração não informado.";
         $this->erro_campo = "ve62_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve62_medida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve62_medida"])){
       $sql  .= $virgula." ve62_medida = $this->ve62_medida ";
       $virgula = ",";
       if(trim($this->ve62_medida) == null ){
         $this->erro_sql = " Campo Medida não informado.";
         $this->erro_campo = "ve62_medida";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve62_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve62_observacao"])){
       $sql  .= $virgula." ve62_observacao = '$this->ve62_observacao' ";
       $virgula = ",";
     }
     if(trim($this->ve62_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve62_situacao"])){
       $sql  .= $virgula." ve62_situacao = $this->ve62_situacao ";
       $virgula = ",";
       if(trim($this->ve62_situacao) == null ){
         $this->erro_sql = " Campo Situação não informado.";
         $this->erro_campo = "ve62_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve62_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve62_numero"])){
       $sql  .= $virgula." ve62_numero = $this->ve62_numero ";
       $virgula = ",";
       if(trim($this->ve62_numero) == null ){
         $this->erro_sql = " Campo Número não informado.";
         $this->erro_campo = "ve62_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve62_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve62_anousu"])){
       $sql  .= $virgula." ve62_anousu = $this->ve62_anousu ";
       $virgula = ",";
       if(trim($this->ve62_anousu) == null ){
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "ve62_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve62_veicmotoristas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve62_veicmotoristas"])){
        if(trim($this->ve62_veicmotoristas)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ve62_veicmotoristas"])){
           $this->ve62_veicmotoristas = "null" ;
        }
       $sql  .= $virgula." ve62_veicmotoristas = $this->ve62_veicmotoristas ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ve62_codigo!=null){
       $sql .= " ve62_codigo = $this->ve62_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ve62_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,9327,'$this->ve62_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve62_codigo"]) || $this->ve62_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1603,9327,'".AddSlashes(pg_result($resaco,$conresaco,'ve62_codigo'))."','$this->ve62_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve62_veiculos"]) || $this->ve62_veiculos != "")
             $resac = db_query("insert into db_acount values($acount,1603,9352,'".AddSlashes(pg_result($resaco,$conresaco,'ve62_veiculos'))."','$this->ve62_veiculos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve62_dtmanut"]) || $this->ve62_dtmanut != "")
             $resac = db_query("insert into db_acount values($acount,1603,9328,'".AddSlashes(pg_result($resaco,$conresaco,'ve62_dtmanut'))."','$this->ve62_dtmanut',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve62_vlrmobra"]) || $this->ve62_vlrmobra != "")
             $resac = db_query("insert into db_acount values($acount,1603,9329,'".AddSlashes(pg_result($resaco,$conresaco,'ve62_vlrmobra'))."','$this->ve62_vlrmobra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve62_vlrpecas"]) || $this->ve62_vlrpecas != "")
             $resac = db_query("insert into db_acount values($acount,1603,9330,'".AddSlashes(pg_result($resaco,$conresaco,'ve62_vlrpecas'))."','$this->ve62_vlrpecas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve62_descr"]) || $this->ve62_descr != "")
             $resac = db_query("insert into db_acount values($acount,1603,9331,'".AddSlashes(pg_result($resaco,$conresaco,'ve62_descr'))."','$this->ve62_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve62_notafisc"]) || $this->ve62_notafisc != "")
             $resac = db_query("insert into db_acount values($acount,1603,9332,'".AddSlashes(pg_result($resaco,$conresaco,'ve62_notafisc'))."','$this->ve62_notafisc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve62_veiccadtiposervico"]) || $this->ve62_veiccadtiposervico != "")
             $resac = db_query("insert into db_acount values($acount,1603,9334,'".AddSlashes(pg_result($resaco,$conresaco,'ve62_veiccadtiposervico'))."','$this->ve62_veiccadtiposervico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve62_usuario"]) || $this->ve62_usuario != "")
             $resac = db_query("insert into db_acount values($acount,1603,9335,'".AddSlashes(pg_result($resaco,$conresaco,'ve62_usuario'))."','$this->ve62_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve62_data"]) || $this->ve62_data != "")
             $resac = db_query("insert into db_acount values($acount,1603,9336,'".AddSlashes(pg_result($resaco,$conresaco,'ve62_data'))."','$this->ve62_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve62_hora"]) || $this->ve62_hora != "")
             $resac = db_query("insert into db_acount values($acount,1603,9337,'".AddSlashes(pg_result($resaco,$conresaco,'ve62_hora'))."','$this->ve62_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve62_medida"]) || $this->ve62_medida != "")
             $resac = db_query("insert into db_acount values($acount,1603,11084,'".AddSlashes(pg_result($resaco,$conresaco,'ve62_medida'))."','$this->ve62_medida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve62_observacao"]) || $this->ve62_observacao != "")
             $resac = db_query("insert into db_acount values($acount,1603,18840,'".AddSlashes(pg_result($resaco,$conresaco,'ve62_observacao'))."','$this->ve62_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve62_situacao"]) || $this->ve62_situacao != "")
             $resac = db_query("insert into db_acount values($acount,1603,21351,'".AddSlashes(pg_result($resaco,$conresaco,'ve62_situacao'))."','$this->ve62_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve62_numero"]) || $this->ve62_numero != "")
             $resac = db_query("insert into db_acount values($acount,1603,21352,'".AddSlashes(pg_result($resaco,$conresaco,'ve62_numero'))."','$this->ve62_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve62_anousu"]) || $this->ve62_anousu != "")
             $resac = db_query("insert into db_acount values($acount,1603,21353,'".AddSlashes(pg_result($resaco,$conresaco,'ve62_anousu'))."','$this->ve62_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve62_veicmotoristas"]) || $this->ve62_veicmotoristas != "")
             $resac = db_query("insert into db_acount values($acount,1603,21376,'".AddSlashes(pg_result($resaco,$conresaco,'ve62_veicmotoristas'))."','$this->ve62_veicmotoristas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Manutenção dos Veículos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve62_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Manutenção dos Veículos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve62_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve62_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($ve62_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ve62_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,9327,'$ve62_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1603,9327,'','".AddSlashes(pg_result($resaco,$iresaco,'ve62_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1603,9352,'','".AddSlashes(pg_result($resaco,$iresaco,'ve62_veiculos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1603,9328,'','".AddSlashes(pg_result($resaco,$iresaco,'ve62_dtmanut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1603,9329,'','".AddSlashes(pg_result($resaco,$iresaco,'ve62_vlrmobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1603,9330,'','".AddSlashes(pg_result($resaco,$iresaco,'ve62_vlrpecas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1603,9331,'','".AddSlashes(pg_result($resaco,$iresaco,'ve62_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1603,9332,'','".AddSlashes(pg_result($resaco,$iresaco,'ve62_notafisc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1603,9334,'','".AddSlashes(pg_result($resaco,$iresaco,'ve62_veiccadtiposervico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1603,9335,'','".AddSlashes(pg_result($resaco,$iresaco,'ve62_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1603,9336,'','".AddSlashes(pg_result($resaco,$iresaco,'ve62_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1603,9337,'','".AddSlashes(pg_result($resaco,$iresaco,'ve62_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1603,11084,'','".AddSlashes(pg_result($resaco,$iresaco,'ve62_medida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1603,18840,'','".AddSlashes(pg_result($resaco,$iresaco,'ve62_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1603,21351,'','".AddSlashes(pg_result($resaco,$iresaco,'ve62_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1603,21352,'','".AddSlashes(pg_result($resaco,$iresaco,'ve62_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1603,21353,'','".AddSlashes(pg_result($resaco,$iresaco,'ve62_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1603,21376,'','".AddSlashes(pg_result($resaco,$iresaco,'ve62_veicmotoristas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from veicmanut
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ve62_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ve62_codigo = $ve62_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Manutenção dos Veículos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ve62_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Manutenção dos Veículos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ve62_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ve62_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:veicmanut";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($ve62_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from veicmanut ";
     $sql .= "      inner join veiccadtiposervico  on  veiccadtiposervico.ve28_codigo = veicmanut.ve62_veiccadtiposervico";
     $sql .= "      inner join veiculos  on  veiculos.ve01_codigo = veicmanut.ve62_veiculos";
     $sql .= "      left  join veicmotoristas  on  veicmotoristas.ve05_codigo = veicmanut.ve62_veicmotoristas";
     $sql .= "      left  join cgm as motorista  on  veicmotoristas.ve05_numcgm = motorista.z01_numcgm";
     $sql .= "      inner join ceplocalidades  on  ceplocalidades.cp05_codlocalidades = veiculos.ve01_ceplocalidades";
     $sql .= "      inner join veiccadtipo  on  veiccadtipo.ve20_codigo = veiculos.ve01_veiccadtipo";
     $sql .= "      inner join veiccadmarca  on  veiccadmarca.ve21_codigo = veiculos.ve01_veiccadmarca";
     $sql .= "      inner join veiccadmodelo  on  veiccadmodelo.ve22_codigo = veiculos.ve01_veiccadmodelo";
     $sql .= "      inner join veiccadcor  on  veiccadcor.ve23_codigo = veiculos.ve01_veiccadcor";
     $sql .= "      inner join veiccadtipocapacidade  on  veiccadtipocapacidade.ve24_codigo = veiculos.ve01_veiccadtipocapacidade";
     $sql .= "      inner join veiccadcategcnh  on  veiccadcategcnh.ve30_codigo = veiculos.ve01_veiccadcategcnh";
     $sql .= "      inner join veiccadproced  on  veiccadproced.ve25_codigo = veiculos.ve01_veiccadproced";
     $sql .= "      inner join veiccadpotencia  on  veiccadpotencia.ve31_codigo = veiculos.ve01_veiccadpotencia";
     $sql .= "      inner join veiccadcateg  as a on   a.ve32_codigo = veiculos.ve01_veiccadcateg";
     $sql .= "      inner join veictipoabast  on  veictipoabast.ve07_sequencial = veiculos.ve01_veictipoabast";
     $sql .= "       left join veiccentral            on  veiccentral.ve40_veiculos                = veiculos.ve01_codigo ";
     $sql .= "       left join veiccadcentral         on  veiccadcentral.ve36_sequencial           = veiccentral.ve40_veiccadcentral";
     $sql .= "       left join veiccadcentraldepart   on  veiccadcentraldepart.ve37_veiccadcentral = veiccadcentral.ve36_sequencial	";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ve62_codigo)) {
         $sql2 .= " where veicmanut.ve62_codigo = $ve62_codigo ";
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

  /**
   * Busca as manutenções com ou sem itens cadastrados
   *
   * @param  integer $ve63_codigo
   * @param  string  $sCampos
   * @param  string  $sOrdem
   * @param  string  $sWhere
   * @return string
   */
  public function sql_query_sem_itens($sCampos = "*", $sOrdem = null, $sWhere = "") {

    $sSql  = "select {$sCampos} ";
    $sSql .= "  from veicmanut ";
    $sSql .= "      left join veicmanutitem on veicmanut.ve62_codigo = veicmanutitem.ve63_veicmanut ";

    if (!empty($sWhere)) {
      $sSql .= " where $sWhere";
    }

    if (!empty($sOrdem)) {
      $sSql .= " order by {$sOrdem}";
    }

    return $sSql;
  }

   // funcao do sql
   public function sql_query_file ($ve62_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from veicmanut ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ve62_codigo)){
         $sql2 .= " where veicmanut.ve62_codigo = $ve62_codigo ";
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

   function sql_query_info ( $ve62_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from veicmanut ";
     $sql .= "      inner join veiccadtiposervico    on veiccadtiposervico.ve28_codigo     = veicmanut.ve62_veiccadtiposervico";
     $sql .= "      inner join veiculos              on veiculos.ve01_codigo               = veicmanut.ve62_veiculos";
     $sql .= "      inner join ceplocalidades        on ceplocalidades.cp05_codlocalidades = veiculos.ve01_ceplocalidades";
     $sql .= "      inner join veiccadtipo           on veiccadtipo.ve20_codigo   = veiculos.ve01_veiccadtipo";
     $sql .= "      inner join veiccadmarca          on veiccadmarca.ve21_codigo  = veiculos.ve01_veiccadmarca";
     $sql .= "      inner join veiccadmodelo         on veiccadmodelo.ve22_codigo = veiculos.ve01_veiccadmodelo";
     $sql .= "      inner join veiccadcor            on veiccadcor.ve23_codigo    = veiculos.ve01_veiccadcor";
     $sql .= "      inner join veiccadtipocapacidade on veiccadtipocapacidade.ve24_codigo = veiculos.ve01_veiccadtipocapacidade";
     $sql .= "      inner join veiculoscomb          on veiculoscomb.ve06_veiculos  = veiculos.ve01_codigo";
     $sql .= "      inner join veiccadcomb           on veiccadcomb.ve26_codigo     = veiculoscomb.ve06_veiccadcomb";
     $sql .= "      inner join veiccadcategcnh       on veiccadcategcnh.ve30_codigo = veiculos.ve01_veiccadcategcnh";
     $sql .= "      inner join veiccadproced         on veiccadproced.ve25_codigo   = veiculos.ve01_veiccadproced";
     $sql .= "      inner join veiccadpotencia       on veiccadpotencia.ve31_codigo = veiculos.ve01_veiccadpotencia";
     $sql .= "      inner join veiccadcateg          on veiccadcateg.ve32_codigo    = veiculos.ve01_veiccadcateg";
     $sql .= "		left join veicmanutretirada on veicmanutretirada.ve65_veicmanut   = veicmanut.ve62_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ve62_codigo!=null ){
         $sql2 .= " where veicmanut.ve62_codigo = $ve62_codigo ";
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
}
