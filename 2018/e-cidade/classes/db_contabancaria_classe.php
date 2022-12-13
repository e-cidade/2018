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

//MODULO: configuracoes
//CLASSE DA ENTIDADE contabancaria
class cl_contabancaria {
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
   var $db83_sequencial = 0;
   var $db83_descricao = null;
   var $db83_bancoagencia = 0;
   var $db83_conta = null;
   var $db83_dvconta = null;
   var $db83_identificador = null;
   var $db83_codigooperacao = null;
   var $db83_tipoconta = 0;
   var $db83_contaplano = 'f';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 db83_sequencial = int4 = Codigo sequencial da conta bancaria
                 db83_descricao = varchar(100) = Descrição da Conta
                 db83_bancoagencia = int4 = Codigo da Agencia
                 db83_conta = varchar(15) = Conta
                 db83_dvconta = varchar(1) = Digito da Conta
                 db83_identificador = char(14) = Identificador (CNPJ )
                 db83_codigooperacao = varchar(4) = Código da Operação
                 db83_tipoconta = int4 = Tipo Conta
                 db83_contaplano = bool = Conta plano
                 ";
   //funcao construtor da classe
   function cl_contabancaria() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("contabancaria");
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
       $this->db83_sequencial = ($this->db83_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db83_sequencial"]:$this->db83_sequencial);
       $this->db83_descricao = ($this->db83_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["db83_descricao"]:$this->db83_descricao);
       $this->db83_bancoagencia = ($this->db83_bancoagencia == ""?@$GLOBALS["HTTP_POST_VARS"]["db83_bancoagencia"]:$this->db83_bancoagencia);
       $this->db83_conta = ($this->db83_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["db83_conta"]:$this->db83_conta);
       $this->db83_dvconta = ($this->db83_dvconta == ""?@$GLOBALS["HTTP_POST_VARS"]["db83_dvconta"]:$this->db83_dvconta);
       $this->db83_identificador = ($this->db83_identificador == ""?@$GLOBALS["HTTP_POST_VARS"]["db83_identificador"]:$this->db83_identificador);
       $this->db83_codigooperacao = ($this->db83_codigooperacao == ""?@$GLOBALS["HTTP_POST_VARS"]["db83_codigooperacao"]:$this->db83_codigooperacao);
       $this->db83_tipoconta = ($this->db83_tipoconta == ""?@$GLOBALS["HTTP_POST_VARS"]["db83_tipoconta"]:$this->db83_tipoconta);
       $this->db83_contaplano = ($this->db83_contaplano == "f"?@$GLOBALS["HTTP_POST_VARS"]["db83_contaplano"]:$this->db83_contaplano);
     }else{
       $this->db83_sequencial = ($this->db83_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db83_sequencial"]:$this->db83_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db83_sequencial){
      $this->atualizacampos();
     if($this->db83_descricao == null ){
       $this->erro_sql = " Campo Descrição da Conta nao Informado.";
       $this->erro_campo = "db83_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db83_bancoagencia == null ){
       $this->erro_sql = " Campo Codigo da Agencia nao Informado.";
       $this->erro_campo = "db83_bancoagencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db83_conta == null ){
       $this->erro_sql = " Campo Conta nao Informado.";
       $this->erro_campo = "db83_conta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db83_dvconta == null ){
       $this->erro_sql = " Campo Digito da Conta nao Informado.";
       $this->erro_campo = "db83_dvconta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db83_identificador == null ){
       $this->erro_sql = " Campo Identificador (CNPJ ) nao Informado.";
       $this->erro_campo = "db83_identificador";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db83_tipoconta == null ){
       $this->db83_tipoconta = "0";
     }
     if($this->db83_contaplano == null ){
       $this->erro_sql = " Campo Conta plano nao Informado.";
       $this->erro_campo = "db83_contaplano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db83_sequencial == "" || $db83_sequencial == null ){
       $result = db_query("select nextval('contabancaria_db83_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: contabancaria_db83_sequencial_seq do campo: db83_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->db83_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from contabancaria_db83_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db83_sequencial)){
         $this->erro_sql = " Campo db83_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db83_sequencial = $db83_sequencial;
       }
     }
     if(($this->db83_sequencial == null) || ($this->db83_sequencial == "") ){
       $this->erro_sql = " Campo db83_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into contabancaria(
                                       db83_sequencial
                                      ,db83_descricao
                                      ,db83_bancoagencia
                                      ,db83_conta
                                      ,db83_dvconta
                                      ,db83_identificador
                                      ,db83_codigooperacao
                                      ,db83_tipoconta
                                      ,db83_contaplano
                       )
                values (
                                $this->db83_sequencial
                               ,'$this->db83_descricao'
                               ,$this->db83_bancoagencia
                               ,'$this->db83_conta'
                               ,'$this->db83_dvconta'
                               ,'$this->db83_identificador'
                               ,'$this->db83_codigooperacao'
                               ,$this->db83_tipoconta
                               ,'$this->db83_contaplano'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de contas bancaria ($this->db83_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de contas bancaria já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de contas bancaria ($this->db83_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db83_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db83_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15622,'$this->db83_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2740,15622,'','".AddSlashes(pg_result($resaco,0,'db83_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2740,15623,'','".AddSlashes(pg_result($resaco,0,'db83_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2740,15624,'','".AddSlashes(pg_result($resaco,0,'db83_bancoagencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2740,15625,'','".AddSlashes(pg_result($resaco,0,'db83_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2740,15626,'','".AddSlashes(pg_result($resaco,0,'db83_dvconta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2740,15641,'','".AddSlashes(pg_result($resaco,0,'db83_identificador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2740,15642,'','".AddSlashes(pg_result($resaco,0,'db83_codigooperacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2740,15643,'','".AddSlashes(pg_result($resaco,0,'db83_tipoconta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2740,18251,'','".AddSlashes(pg_result($resaco,0,'db83_contaplano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($db83_sequencial=null) {
      $this->atualizacampos();
     $sql = " update contabancaria set ";
     $virgula = "";
     if(trim($this->db83_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db83_sequencial"])){
       $sql  .= $virgula." db83_sequencial = $this->db83_sequencial ";
       $virgula = ",";
       if(trim($this->db83_sequencial) == null ){
         $this->erro_sql = " Campo Codigo sequencial da conta bancaria nao Informado.";
         $this->erro_campo = "db83_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db83_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db83_descricao"])){
       $sql  .= $virgula." db83_descricao = '$this->db83_descricao' ";
       $virgula = ",";
       if(trim($this->db83_descricao) == null ){
         $this->erro_sql = " Campo Descrição da Conta nao Informado.";
         $this->erro_campo = "db83_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db83_bancoagencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db83_bancoagencia"])){
       $sql  .= $virgula." db83_bancoagencia = $this->db83_bancoagencia ";
       $virgula = ",";
       if(trim($this->db83_bancoagencia) == null ){
         $this->erro_sql = " Campo Codigo da Agencia nao Informado.";
         $this->erro_campo = "db83_bancoagencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db83_conta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db83_conta"])){
       $sql  .= $virgula." db83_conta = '$this->db83_conta' ";
       $virgula = ",";
       if(trim($this->db83_conta) == null ){
         $this->erro_sql = " Campo Conta nao Informado.";
         $this->erro_campo = "db83_conta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db83_dvconta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db83_dvconta"])){
       $sql  .= $virgula." db83_dvconta = '$this->db83_dvconta' ";
       $virgula = ",";
       if(trim($this->db83_dvconta) == null ){
         $this->erro_sql = " Campo Digito da Conta nao Informado.";
         $this->erro_campo = "db83_dvconta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db83_identificador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db83_identificador"])){
       $sql  .= $virgula." db83_identificador = '$this->db83_identificador' ";
       $virgula = ",";
       if(trim($this->db83_identificador) == null ){
         $this->erro_sql = " Campo Identificador (CNPJ ) nao Informado.";
         $this->erro_campo = "db83_identificador";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db83_codigooperacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db83_codigooperacao"])){
       $sql  .= $virgula." db83_codigooperacao = '$this->db83_codigooperacao' ";
       $virgula = ",";
     }
     if(trim($this->db83_tipoconta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db83_tipoconta"])){
        if(trim($this->db83_tipoconta)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db83_tipoconta"])){
           $this->db83_tipoconta = "0" ;
        }
       $sql  .= $virgula." db83_tipoconta = $this->db83_tipoconta ";
       $virgula = ",";
     }
     if(trim($this->db83_contaplano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db83_contaplano"])){
       $sql  .= $virgula." db83_contaplano = '$this->db83_contaplano' ";
       $virgula = ",";
       if(trim($this->db83_contaplano) == null ){
         $this->erro_sql = " Campo Conta plano nao Informado.";
         $this->erro_campo = "db83_contaplano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db83_sequencial!=null){
       $sql .= " db83_sequencial = $this->db83_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db83_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15622,'$this->db83_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db83_sequencial"]) || $this->db83_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2740,15622,'".AddSlashes(pg_result($resaco,$conresaco,'db83_sequencial'))."','$this->db83_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db83_descricao"]) || $this->db83_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2740,15623,'".AddSlashes(pg_result($resaco,$conresaco,'db83_descricao'))."','$this->db83_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db83_bancoagencia"]) || $this->db83_bancoagencia != "")
           $resac = db_query("insert into db_acount values($acount,2740,15624,'".AddSlashes(pg_result($resaco,$conresaco,'db83_bancoagencia'))."','$this->db83_bancoagencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db83_conta"]) || $this->db83_conta != "")
           $resac = db_query("insert into db_acount values($acount,2740,15625,'".AddSlashes(pg_result($resaco,$conresaco,'db83_conta'))."','$this->db83_conta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db83_dvconta"]) || $this->db83_dvconta != "")
           $resac = db_query("insert into db_acount values($acount,2740,15626,'".AddSlashes(pg_result($resaco,$conresaco,'db83_dvconta'))."','$this->db83_dvconta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db83_identificador"]) || $this->db83_identificador != "")
           $resac = db_query("insert into db_acount values($acount,2740,15641,'".AddSlashes(pg_result($resaco,$conresaco,'db83_identificador'))."','$this->db83_identificador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db83_codigooperacao"]) || $this->db83_codigooperacao != "")
           $resac = db_query("insert into db_acount values($acount,2740,15642,'".AddSlashes(pg_result($resaco,$conresaco,'db83_codigooperacao'))."','$this->db83_codigooperacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db83_tipoconta"]) || $this->db83_tipoconta != "")
           $resac = db_query("insert into db_acount values($acount,2740,15643,'".AddSlashes(pg_result($resaco,$conresaco,'db83_tipoconta'))."','$this->db83_tipoconta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db83_contaplano"]) || $this->db83_contaplano != "")
           $resac = db_query("insert into db_acount values($acount,2740,18251,'".AddSlashes(pg_result($resaco,$conresaco,'db83_contaplano'))."','$this->db83_contaplano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de contas bancaria nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db83_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de contas bancaria nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db83_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db83_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($db83_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db83_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15622,'$db83_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2740,15622,'','".AddSlashes(pg_result($resaco,$iresaco,'db83_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2740,15623,'','".AddSlashes(pg_result($resaco,$iresaco,'db83_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2740,15624,'','".AddSlashes(pg_result($resaco,$iresaco,'db83_bancoagencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2740,15625,'','".AddSlashes(pg_result($resaco,$iresaco,'db83_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2740,15626,'','".AddSlashes(pg_result($resaco,$iresaco,'db83_dvconta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2740,15641,'','".AddSlashes(pg_result($resaco,$iresaco,'db83_identificador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2740,15642,'','".AddSlashes(pg_result($resaco,$iresaco,'db83_codigooperacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2740,15643,'','".AddSlashes(pg_result($resaco,$iresaco,'db83_tipoconta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2740,18251,'','".AddSlashes(pg_result($resaco,$iresaco,'db83_contaplano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from contabancaria
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db83_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db83_sequencial = $db83_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de contas bancaria nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db83_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de contas bancaria nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db83_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db83_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:contabancaria";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $db83_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from contabancaria ";
     $sql .= "      inner join bancoagencia  on  bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia";
     $sql .= "      inner join db_bancos  on  db_bancos.db90_codban = bancoagencia.db89_db_bancos";
     $sql2 = "";
     if($dbwhere==""){
       if($db83_sequencial!=null ){
         $sql2 .= " where contabancaria.db83_sequencial = $db83_sequencial ";
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
   function sql_query_file ( $db83_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from contabancaria ";
     $sql2 = "";
     if($dbwhere==""){
       if($db83_sequencial!=null ){
         $sql2 .= " where contabancaria.db83_sequencial = $db83_sequencial ";
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
   function sql_query_concilia( $db83_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from contabancaria ";
     $sql .= "      inner join bancoagencia           on bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia ";
     $sql .= "      inner join db_bancos              on db_bancos.db90_codban        = bancoagencia.db89_db_bancos     ";
     $sql .= "      inner join concilia               on concilia.k68_contabancaria   = contabancaria.db83_sequencial   ";
     $sql .= "      left  join conplanocontabancaria  on c56_contabancaria            = db83_sequencial";
     $sql .= "      left  join conplano               on c60_codcon                   = c56_codcon ";
     $sql .= "                                       and c60_anousu                   = c56_anousu ";
     $sql .= "      left  join conplanoreduz          on c61_codcon                   = c60_codcon ";
     $sql2 = "";
     if($dbwhere==""){
       if($db83_sequencial!=null ){
         $sql2 .= " where contabancaria.db83_sequencial = $db83_sequencial ";
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

  function sql_query_planocontas ( $db83_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){

    $sql = "select {$campos}";
    $iAnoSessao = db_getsession("DB_anousu");
    $sql .= " from contabancaria ";
    $sql .= "      inner join bancoagencia           on  bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia";
    $sql .= "      inner join db_bancos              on  db_bancos.db90_codban = bancoagencia.db89_db_bancos";
    $sql .= "      left  join conplanocontabancaria  on c56_contabancaria = db83_sequencial";
    $sql .= "      left  join conplano               on c60_codcon = c56_codcon ";
    $sql .= "                                       and c60_anousu = {$iAnoSessao}";
    $sql .= "      left  join conplanoreduz          on c61_codcon = c60_codcon ";
    $sql .= "                                       and c61_anousu = c60_anousu ";

    $sql2 = "";

    if($dbwhere==""){
      if($db83_sequencial!=null ){
        $sql2 .= " where contabancaria.db83_sequencial = $db83_sequencial ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;

    if($ordem != null ){
      $sql .= " order by {$ordem}";
    }
    return $sql;
  }

  public function sql_query_conta_pagadora ($sCampos = "*", $sWhere = null, $sOrder = null){

    $sql  = " select {$sCampos}";
    $sql .= "   from contabancaria ";
    $sql .= "        inner join bancoagencia           on  bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia";
    $sql .= "        inner join db_bancos              on  db_bancos.db90_codban = bancoagencia.db89_db_bancos";
    $sql .= "        inner join conplanocontabancaria  on c56_contabancaria = db83_sequencial";
    $sql .= "        inner join conplano               on c60_codcon = c56_codcon ";
    $sql .= "                                         and c60_anousu = c60_anousu";
    $sql .= "        inner  join conplanoreduz         on c61_codcon = c60_codcon ";
    $sql .= "                                         and c61_anousu = c60_anousu ";
    $sql .= "        inner join empagetipo on empagetipo.e83_conta = conplanoreduz.c61_reduz";
    $sql .= "        inner join empagepag  on empagepag.e85_codtipo = empagetipo.e83_codtipo ";
    $sql .= "        inner join empagemov  on empagemov.e81_codmov = empagepag.e85_codmov ";

    if (!empty($sWhere)) {
      $sql .= " where {$sWhere} ";
    }

    if (!empty($sOrder)) {
      $sql .= " order by {$sOrder} ";
    }


    return $sql;
  }
}
?>
