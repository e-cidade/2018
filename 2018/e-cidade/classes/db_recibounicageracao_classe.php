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

//MODULO: arrecadacao
//CLASSE DA ENTIDADE recibounicageracao
class cl_recibounicageracao {
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
   var $ar40_sequencial = 0;
   var $ar40_db_usuarios = 0;
   var $ar40_dtoperacao_dia = null;
   var $ar40_dtoperacao_mes = null;
   var $ar40_dtoperacao_ano = null;
   var $ar40_dtoperacao = null;
   var $ar40_dtvencimento_dia = null;
   var $ar40_dtvencimento_mes = null;
   var $ar40_dtvencimento_ano = null;
   var $ar40_dtvencimento = null;
   var $ar40_percentualdesconto = 0;
   var $ar40_tipogeracao = null;
   var $ar40_ativo = 'f';
   var $ar40_observacao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ar40_sequencial = int4 = Sequencial
                 ar40_db_usuarios = int4 = Usuario
                 ar40_dtoperacao = date = Data de Operação
                 ar40_dtvencimento = date = Data vencimento
                 ar40_percentualdesconto = numeric(10) = Percentual de Desconto
                 ar40_tipogeracao = char(1) = Tipo de Geracao da parcela Unica
                 ar40_ativo = bool = SItuacao da Geracao
                 ar40_observacao = text = Observação
                 ";
   //funcao construtor da classe
   function cl_recibounicageracao() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("recibounicageracao");
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
       $this->ar40_sequencial = ($this->ar40_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar40_sequencial"]:$this->ar40_sequencial);
       $this->ar40_db_usuarios = ($this->ar40_db_usuarios == ""?@$GLOBALS["HTTP_POST_VARS"]["ar40_db_usuarios"]:$this->ar40_db_usuarios);
       if($this->ar40_dtoperacao == ""){
         $this->ar40_dtoperacao_dia = ($this->ar40_dtoperacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ar40_dtoperacao_dia"]:$this->ar40_dtoperacao_dia);
         $this->ar40_dtoperacao_mes = ($this->ar40_dtoperacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ar40_dtoperacao_mes"]:$this->ar40_dtoperacao_mes);
         $this->ar40_dtoperacao_ano = ($this->ar40_dtoperacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ar40_dtoperacao_ano"]:$this->ar40_dtoperacao_ano);
         if($this->ar40_dtoperacao_dia != ""){
            $this->ar40_dtoperacao = $this->ar40_dtoperacao_ano."-".$this->ar40_dtoperacao_mes."-".$this->ar40_dtoperacao_dia;
         }
       }
       if($this->ar40_dtvencimento == ""){
         $this->ar40_dtvencimento_dia = ($this->ar40_dtvencimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ar40_dtvencimento_dia"]:$this->ar40_dtvencimento_dia);
         $this->ar40_dtvencimento_mes = ($this->ar40_dtvencimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ar40_dtvencimento_mes"]:$this->ar40_dtvencimento_mes);
         $this->ar40_dtvencimento_ano = ($this->ar40_dtvencimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ar40_dtvencimento_ano"]:$this->ar40_dtvencimento_ano);
         if($this->ar40_dtvencimento_dia != ""){
            $this->ar40_dtvencimento = $this->ar40_dtvencimento_ano."-".$this->ar40_dtvencimento_mes."-".$this->ar40_dtvencimento_dia;
         }
       }
       $this->ar40_percentualdesconto = ($this->ar40_percentualdesconto == ""?@$GLOBALS["HTTP_POST_VARS"]["ar40_percentualdesconto"]:$this->ar40_percentualdesconto);
       $this->ar40_tipogeracao = ($this->ar40_tipogeracao == ""?@$GLOBALS["HTTP_POST_VARS"]["ar40_tipogeracao"]:$this->ar40_tipogeracao);
       $this->ar40_ativo = ($this->ar40_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["ar40_ativo"]:$this->ar40_ativo);
       $this->ar40_observacao = ($this->ar40_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ar40_observacao"]:$this->ar40_observacao);
     }else{
       $this->ar40_sequencial = ($this->ar40_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar40_sequencial"]:$this->ar40_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ar40_sequencial){
      $this->atualizacampos();
     if($this->ar40_db_usuarios == null ){
       $this->erro_sql = " Campo Usuario nao Informado.";
       $this->erro_campo = "ar40_db_usuarios";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar40_dtoperacao == null ){
       $this->erro_sql = " Campo Data de Operação nao Informado.";
       $this->erro_campo = "ar40_dtoperacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar40_dtvencimento == null ){
       $this->erro_sql = " Campo Data vencimento nao Informado.";
       $this->erro_campo = "ar40_dtvencimento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar40_percentualdesconto == null ){
       $this->erro_sql = " Campo Percentual de Desconto nao Informado.";
       $this->erro_campo = "ar40_percentualdesconto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar40_tipogeracao == null ){
       $this->erro_sql = " Campo Tipo de Geracao da parcela Unica nao Informado.";
       $this->erro_campo = "ar40_tipogeracao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar40_ativo == null ){
       $this->erro_sql = " Campo SItuacao da Geracao nao Informado.";
       $this->erro_campo = "ar40_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ar40_sequencial == "" || $ar40_sequencial == null ){
       $result = db_query("select nextval('recibounicageracao_ar40_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: recibounicageracao_ar40_sequencial_seq do campo: ar40_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ar40_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from recibounicageracao_ar40_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ar40_sequencial)){
         $this->erro_sql = " Campo ar40_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ar40_sequencial = $ar40_sequencial;
       }
     }
     if(($this->ar40_sequencial == null) || ($this->ar40_sequencial == "") ){
       $this->erro_sql = " Campo ar40_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into recibounicageracao(
                                       ar40_sequencial
                                      ,ar40_db_usuarios
                                      ,ar40_dtoperacao
                                      ,ar40_dtvencimento
                                      ,ar40_percentualdesconto
                                      ,ar40_tipogeracao
                                      ,ar40_ativo
                                      ,ar40_observacao
                       )
                values (
                                $this->ar40_sequencial
                               ,$this->ar40_db_usuarios
                               ,".($this->ar40_dtoperacao == "null" || $this->ar40_dtoperacao == ""?"null":"'".$this->ar40_dtoperacao."'")."
                               ,".($this->ar40_dtvencimento == "null" || $this->ar40_dtvencimento == ""?"null":"'".$this->ar40_dtvencimento."'")."
                               ,$this->ar40_percentualdesconto
                               ,'$this->ar40_tipogeracao'
                               ,'$this->ar40_ativo'
                               ,'$this->ar40_observacao'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "recibounicageração ($this->ar40_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "recibounicageração já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "recibounicageração ($this->ar40_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar40_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ar40_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18230,'$this->ar40_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3266,18230,'','".AddSlashes(pg_result($resaco,0,'ar40_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3266,18473,'','".AddSlashes(pg_result($resaco,0,'ar40_db_usuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3266,18474,'','".AddSlashes(pg_result($resaco,0,'ar40_dtoperacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3266,18480,'','".AddSlashes(pg_result($resaco,0,'ar40_dtvencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3266,18481,'','".AddSlashes(pg_result($resaco,0,'ar40_percentualdesconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3266,18475,'','".AddSlashes(pg_result($resaco,0,'ar40_tipogeracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3266,18476,'','".AddSlashes(pg_result($resaco,0,'ar40_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3266,18477,'','".AddSlashes(pg_result($resaco,0,'ar40_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ar40_sequencial=null) {
      $this->atualizacampos();
     $sql = " update recibounicageracao set ";
     $virgula = "";
     if(trim($this->ar40_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar40_sequencial"])){
       $sql  .= $virgula." ar40_sequencial = $this->ar40_sequencial ";
       $virgula = ",";
       if(trim($this->ar40_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ar40_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar40_db_usuarios)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar40_db_usuarios"])){
       $sql  .= $virgula." ar40_db_usuarios = $this->ar40_db_usuarios ";
       $virgula = ",";
       if(trim($this->ar40_db_usuarios) == null ){
         $this->erro_sql = " Campo Usuario nao Informado.";
         $this->erro_campo = "ar40_db_usuarios";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar40_dtoperacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar40_dtoperacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ar40_dtoperacao_dia"] !="") ){
       $sql  .= $virgula." ar40_dtoperacao = '$this->ar40_dtoperacao' ";
       $virgula = ",";
       if(trim($this->ar40_dtoperacao) == null ){
         $this->erro_sql = " Campo Data de Operação nao Informado.";
         $this->erro_campo = "ar40_dtoperacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ar40_dtoperacao_dia"])){
         $sql  .= $virgula." ar40_dtoperacao = null ";
         $virgula = ",";
         if(trim($this->ar40_dtoperacao) == null ){
           $this->erro_sql = " Campo Data de Operação nao Informado.";
           $this->erro_campo = "ar40_dtoperacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ar40_dtvencimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar40_dtvencimento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ar40_dtvencimento_dia"] !="") ){
       $sql  .= $virgula." ar40_dtvencimento = '$this->ar40_dtvencimento' ";
       $virgula = ",";
       if(trim($this->ar40_dtvencimento) == null ){
         $this->erro_sql = " Campo Data vencimento nao Informado.";
         $this->erro_campo = "ar40_dtvencimento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ar40_dtvencimento_dia"])){
         $sql  .= $virgula." ar40_dtvencimento = null ";
         $virgula = ",";
         if(trim($this->ar40_dtvencimento) == null ){
           $this->erro_sql = " Campo Data vencimento nao Informado.";
           $this->erro_campo = "ar40_dtvencimento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ar40_percentualdesconto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar40_percentualdesconto"])){
       $sql  .= $virgula." ar40_percentualdesconto = $this->ar40_percentualdesconto ";
       $virgula = ",";
       if(trim($this->ar40_percentualdesconto) == null ){
         $this->erro_sql = " Campo Percentual de Desconto nao Informado.";
         $this->erro_campo = "ar40_percentualdesconto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar40_tipogeracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar40_tipogeracao"])){
       $sql  .= $virgula." ar40_tipogeracao = '$this->ar40_tipogeracao' ";
       $virgula = ",";
       if(trim($this->ar40_tipogeracao) == null ){
         $this->erro_sql = " Campo Tipo de Geracao da parcela Unica nao Informado.";
         $this->erro_campo = "ar40_tipogeracao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar40_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar40_ativo"])){
       $sql  .= $virgula." ar40_ativo = '$this->ar40_ativo' ";
       $virgula = ",";
       if(trim($this->ar40_ativo) == null ){
         $this->erro_sql = " Campo SItuacao da Geracao nao Informado.";
         $this->erro_campo = "ar40_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar40_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar40_observacao"])){
       $sql  .= $virgula." ar40_observacao = '$this->ar40_observacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ar40_sequencial!=null){
       $sql .= " ar40_sequencial = $this->ar40_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ar40_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18230,'$this->ar40_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar40_sequencial"]) || $this->ar40_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3266,18230,'".AddSlashes(pg_result($resaco,$conresaco,'ar40_sequencial'))."','$this->ar40_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar40_db_usuarios"]) || $this->ar40_db_usuarios != "")
           $resac = db_query("insert into db_acount values($acount,3266,18473,'".AddSlashes(pg_result($resaco,$conresaco,'ar40_db_usuarios'))."','$this->ar40_db_usuarios',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar40_dtoperacao"]) || $this->ar40_dtoperacao != "")
           $resac = db_query("insert into db_acount values($acount,3266,18474,'".AddSlashes(pg_result($resaco,$conresaco,'ar40_dtoperacao'))."','$this->ar40_dtoperacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar40_dtvencimento"]) || $this->ar40_dtvencimento != "")
           $resac = db_query("insert into db_acount values($acount,3266,18480,'".AddSlashes(pg_result($resaco,$conresaco,'ar40_dtvencimento'))."','$this->ar40_dtvencimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar40_percentualdesconto"]) || $this->ar40_percentualdesconto != "")
           $resac = db_query("insert into db_acount values($acount,3266,18481,'".AddSlashes(pg_result($resaco,$conresaco,'ar40_percentualdesconto'))."','$this->ar40_percentualdesconto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar40_tipogeracao"]) || $this->ar40_tipogeracao != "")
           $resac = db_query("insert into db_acount values($acount,3266,18475,'".AddSlashes(pg_result($resaco,$conresaco,'ar40_tipogeracao'))."','$this->ar40_tipogeracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar40_ativo"]) || $this->ar40_ativo != "")
           $resac = db_query("insert into db_acount values($acount,3266,18476,'".AddSlashes(pg_result($resaco,$conresaco,'ar40_ativo'))."','$this->ar40_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar40_observacao"]) || $this->ar40_observacao != "")
           $resac = db_query("insert into db_acount values($acount,3266,18477,'".AddSlashes(pg_result($resaco,$conresaco,'ar40_observacao'))."','$this->ar40_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "recibounicageração nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar40_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "recibounicageração nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar40_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar40_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ar40_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ar40_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18230,'$ar40_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3266,18230,'','".AddSlashes(pg_result($resaco,$iresaco,'ar40_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3266,18473,'','".AddSlashes(pg_result($resaco,$iresaco,'ar40_db_usuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3266,18474,'','".AddSlashes(pg_result($resaco,$iresaco,'ar40_dtoperacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3266,18480,'','".AddSlashes(pg_result($resaco,$iresaco,'ar40_dtvencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3266,18481,'','".AddSlashes(pg_result($resaco,$iresaco,'ar40_percentualdesconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3266,18475,'','".AddSlashes(pg_result($resaco,$iresaco,'ar40_tipogeracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3266,18476,'','".AddSlashes(pg_result($resaco,$iresaco,'ar40_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3266,18477,'','".AddSlashes(pg_result($resaco,$iresaco,'ar40_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from recibounicageracao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ar40_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ar40_sequencial = $ar40_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "recibounicageração nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ar40_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "recibounicageração nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ar40_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ar40_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:recibounicageracao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ar40_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from recibounicageracao ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = recibounicageracao.ar40_db_usuarios";
     $sql2 = "";
     if($dbwhere==""){
       if($ar40_sequencial!=null ){
         $sql2 .= " where recibounicageracao.ar40_sequencial = $ar40_sequencial ";
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
   function sql_query_file ( $ar40_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from recibounicageracao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ar40_sequencial!=null ){
         $sql2 .= " where recibounicageracao.ar40_sequencial = $ar40_sequencial ";
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

  /**
   * Busca tipos de débito conforme a origem da pesquisa
   *
   * @param unknown_type $sTipoPesquisa
   * @param unknown_type $sChavePesquisa
   * @return string
   */
   function sql_query_pesquisa($sTipoPesquisa, $sChavePesquisa,$lNumpres = false, $iCadTipoDebito = null){

     switch($sTipoPesquisa){
       case 'C':
         $sTabela = "arrenumcgm";
         $sCampo  = $sTabela.".k00_numcgm";
       break;
       case 'M':
         $sTabela = "arrematric";
         $sCampo  = $sTabela.".k00_matric";
       break;
       case 'I':
         $sTabela = "arreinscr";
         $sCampo  = $sTabela.".k00_inscr";
       break;
     }

     $sSqlPesquisa = " select distinct                                                                  \n";
     if (!$lNumpres && $iCadTipoDebito == null) {

       $sSqlPesquisa.= "        cadtipo.k03_tipo,                                                       \n";
       $sSqlPesquisa.= "        cadtipo.k03_descr                                                       \n";
     } else {
       $sSqlPesquisa.= "        arrecad.k00_numpre                                                      \n";
     }
     $sSqlPesquisa.= "   from {$sTabela}                                                                \n";
     $sSqlPesquisa.= "        inner join arrecad  on arrecad.k00_numpre = {$sTabela}.k00_numpre         \n";
     $sSqlPesquisa.= "        inner join arretipo on arrecad.k00_tipo   = arretipo.k00_tipo             \n";
     $sSqlPesquisa.= "        inner join cadtipo  on cadtipo.k03_tipo   = arretipo.k03_tipo             \n";
     $sSqlPesquisa.= "                           and cadtipo.k03_tipo   in (1, 2, 4, 7, 9, 11, 19, 21)  \n";

     if ($sTabela == "arrematric") {
       $sSqlPesquisa.= " inner join iptunump  on j20_numpre = arrematric.k00_numpre ";
       $sSqlPesquisa.= "                     and j20_matric = arrematric.k00_matric ";
     }
     if(!empty($sChavePesquisa) && (!$lNumpres && $iCadTipoDebito == null)){
       $sSqlPesquisa.= "    where {$sCampo} = {$sChavePesquisa}                                         \n";
     }
     if($lNumpres && $iCadTipoDebito != null){
       $sSqlPesquisa.= "    where cadtipo.k03_tipo = {$iCadTipoDebito}                                  \n";
     }
     return $sSqlPesquisa;
   }

   function sql_query_debitosExercicios($sTipoPesquisa, $sChavePesquisa, $iCadTipoDebito, $lExercicios = true, $iExercicioPesquisa = 0) {

     $sSql = "select * from ( \n";
   	switch ((int)$iCadTipoDebito) {

   	  case 1:  // IPTU

   	    $sSql.= " select distinct                                                       \n";
   	    if(!$lExercicios){
   	      $sSql.= "        arrecad.k00_numpre     as numpre,                            \n";
   	      $sSql.= "        sum(arrecad.k00_valor) as valor,                             \n";
   	    }
 	      $sSql.= "        j20_anousu as exercicio                                        \n";
   	    $sSql.= "   from iptunump                                                       \n";
   	    $sSql.= "        inner join arrecad  on arrecad.k00_numpre   = j20_numpre       \n";
   	    break;
   	  case 2:  // ISSQN FIXO

   	    $sSql.= " select distinct                                                       \n";
   	    if(!$lExercicios){
   	      $sSql.= "        arrecad.k00_numpre     as numpre,                            \n";
   	      $sSql.= "        sum(arrecad.k00_valor) as valor,                             \n";
   	    }
   	    $sSql.= "        q01_anousu as exercicio                                        \n";
   	    $sSql.= "   from isscalc                                                        \n";
   	    $sSql.= "        inner join arrecad on arrecad.k00_numpre = q01_numpre          \n";
   	    $sSql.= "        inner join cadcalc on q85_codigo = q01_cadcal                  \n";
   	    $sSql.= "                          and q85_codigo in(2)                         \n";

   	    break;
   	  case 4:  // CONTRIBUICAO DE MELHORIA

   	    $sSql.= " select distinct                                                       \n";
   	    if(!$lExercicios){
   	      $sSql.= "        arrecad.k00_numpre     as numpre,                            \n";
   	      $sSql.= "        sum(arrecad.k00_valor) as valor,                             \n";
   	    }
   	    $sSql.= "        extract(year from d07_data) as exercicio                       \n";
   	    $sSql.= "   from contricalc                                                     \n";
   	    $sSql.= "        inner join contrib on d09_contri = d07_contri                  \n";
   	    $sSql.= "        inner join arrecad on arrecad.k00_numpre = d09_numpre          \n";
   	    break;
   	  case 7:  //DIVERSOS

   	    $sSql.= " select distinct                                                       \n";
   	    if(!$lExercicios){
   	      $sSql.= "        arrecad.k00_numpre     as numpre,                            \n";
   	      $sSql.= "        sum(arrecad.k00_valor) as valor,                             \n";
   	    }
   	    $sSql.= "        extract(year from dv05_dtinsc) as exercicio                    \n";
   	    $sSql.= "   from diversos                                                       \n";
   	    $sSql.= "        inner join arrecad on dv05_numpre = arrecad.k00_numpre         \n";
   	    break;
   	  case 9:  //ALVARA
   	    $sSql.= " select distinct                                                       \n";
   	    if(!$lExercicios){
   	      $sSql.= "        arrecad.k00_numpre     as numpre,                            \n";
   	      $sSql.= "        sum(arrecad.k00_valor) as valor,                             \n";
   	    }
   	    $sSql.= "        q01_anousu as exercicio                                        \n";
   	    $sSql.= "   from isscalc                                                        \n";
   	    $sSql.= "        inner join arrecad  on arrecad.k00_numpre = q01_numpre         \n";
   	    $sSql.= "        inner join cadcalc  on q85_codigo = q01_cadcal                 \n";
   	    $sSql.= "                           and q85_codigo in(1,2)                      \n";
   	    break;
   	  case 11: //AUTO DE INFRACAO

   	    $sSql.= " select distinct                                                       \n";
   	    if(!$lExercicios){
   	      $sSql.= "        arrecad.k00_numpre     as numpre,                            \n";
   	      $sSql.= "        sum(arrecad.k00_valor) as valor,                             \n";
   	    }
   	    $sSql.= "        extract(year from y50_data) as exercicio                       \n";
   	    $sSql.= "   from auto                                                           \n";
   	    $sSql.= "        inner join autonumpre on y17_codauto = y50_codauto             \n";
   	    $sSql.= "        inner join arrecad    on arrecad.k00_numpre  = y17_numpre      \n";
   	    break;
   	  case 19: //VISTORIAS

   	    $sSql.= " select distinct                                                       \n";
   	    if(!$lExercicios){
   	      $sSql.= "        arrecad.k00_numpre     as numpre,                            \n";
   	      $sSql.= "        sum(arrecad.k00_valor) as valor,                             \n";
   	    }
   	    $sSql.= "        extract(year from y70_data) as exercicio                       \n";
   	    $sSql.= "   from vistorias                                                      \n";
   	    $sSql.= "        inner join vistorianumpre on y69_codvist = y70_codvist         \n";
   	    $sSql.= "        inner join arrecad        on arrecad.k00_numpre  = y69_numpre  \n";
   	    break;
   	  case 21: //CEMITERIO

   	    $sSql.= " select distinct                                                       \n";
   	    if(!$lExercicios){
   	      $sSql.= "        arrecad.k00_numpre     as numpre,                            \n";
   	      $sSql.= "        sum(arrecad.k00_valor) as valor,                             \n";
   	    }
   	    $sSql.= "        extract(year from cm10_d_data) as exercicio                    \n";
   	    $sSql.= "   from itenserv                                                       \n";
   	    $sSql.= "        inner join arrecad on arrecad.k00_numpre = cm10_i_numpre       \n";
   	    break;
   	  default: //CASO NÃO ENCONTRE NENHUMA DAS ANTERIORES DISPARA ERRO
   	    return false;
   	  break;
   	}

   	if (!empty($sChavePesquisa)) {

   	  switch($sTipoPesquisa){
   	    case 'C':
   	      $sTabela = "arrenumcgm";
   	      $sCampo  = $sTabela.".k00_numcgm";
   	      break;
   	    case 'M':
   	      $sTabela = "arrematric";
   	      $sCampo  = $sTabela.".k00_matric";
   	      break;
   	    case 'I':
   	      $sTabela = "arreinscr";
   	      $sCampo  = $sTabela.".k00_inscr";
   	      break;
   	    default:
   	      return false;
   	      break;
   	  }
   	  $sSql.= " inner join {$sTabela} on {$sTabela}.k00_numpre = arrecad.k00_numpre   \n";
   	  $sSql.= "                      and {$sCampo}             = {$sChavePesquisa}    \n";
   	}
   	if(!$lExercicios){
   	  $sSql.= " group by exercicio,numpre \n";
   	}
   	$sSql.= " order by exercicio ";
   	$sSql.= ") as sql_debitos \n";

   	if($iExercicioPesquisa != 0 && !$lExercicios){
   	  $sSql.= "where exercicio = {$iExercicioPesquisa} \n";
   	}
   	return $sSql;
   }

   /**
    * Query que busca os dados da geracao da unica, quantidade de recibos em aberto e com registro de pagamento
    * @param  integer $iCodigo Codigo da geracao
    * @return string
    */
  function sql_query_unica_geral($iCodigo){

    $sSql  = "select ar40_dtoperacao,                                                   ";
    $sSql .= "       ar40_dtvencimento,                                                 ";
    $sSql .= "       ar40_percentualdesconto,                                           ";
    $sSql .= "       nome,                                                              ";
    $sSql .= "       login,                                                             ";
    $sSql .= "       (select count(k00_sequencial)                                      ";
    $sSql .= "          from recibounica                                                ";
    $sSql .= "         where k00_recibounicageracao = ar40_sequencial                   ";
    $sSql .= "       ) as quantidade_recibos,                                           ";
    $sSql .= "       (select coalesce(count(k00_sequencial), 0)                         ";
    $sSql .= "          from recibounica                                                ";
    $sSql .= "         where k00_recibounicageracao = ar40_sequencial                   ";
    $sSql .= "           and exists(select 1                                            ";
    $sSql .= "                        from arrepaga                                     ";
    $sSql .= "                       where arrepaga.k00_numpre = recibounica.k00_numpre ";
    $sSql .= "                         and arrepaga.k00_hist   = 990)                   ";
    $sSql .= "       ) as quantidade_recibos_pagos                                      ";
    $sSql .= "  from recibounicageracao                                                 ";
    $sSql .= "       inner join db_usuarios on id_usuario = ar40_db_usuarios            ";
    $sSql .= " where ar40_sequencial = {$iCodigo}                                       ";

    return $sSql;
  }

}