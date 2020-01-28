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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhferias
class cl_rhferias {
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
   var $rh109_sequencial = 0;
   var $rh109_regist = 0;
   var $rh109_periodoaquisitivoinicial_dia = null;
   var $rh109_periodoaquisitivoinicial_mes = null;
   var $rh109_periodoaquisitivoinicial_ano = null;
   var $rh109_periodoaquisitivoinicial = null;
   var $rh109_periodoaquisitivofinal_dia = null;
   var $rh109_periodoaquisitivofinal_mes = null;
   var $rh109_periodoaquisitivofinal_ano = null;
   var $rh109_periodoaquisitivofinal = null;
   var $rh109_diasdireito = 0;
   var $rh109_faltasperiodoaquisitivo = 0;
   var $rh109_observacao = null;
   var $rh109_perdeudireitoferias = 'f';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 rh109_sequencial = int4 = Sequencial 
                 rh109_regist = int4 = Matrícula 
                 rh109_periodoaquisitivoinicial = date = Período aquisitivo inicial 
                 rh109_periodoaquisitivofinal = date = Período aquisitivo final 
                 rh109_diasdireito = int4 = Dias de direito 
                 rh109_faltasperiodoaquisitivo = int4 = Faltas Durante o Período Aquisitivo 
                 rh109_observacao = text = Observação 
                 rh109_perdeudireitoferias = bool = Perdeu Direito a Férias 
                 ";
   //funcao construtor da classe
   function cl_rhferias() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhferias");
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
       $this->rh109_sequencial = ($this->rh109_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh109_sequencial"]:$this->rh109_sequencial);
       $this->rh109_regist = ($this->rh109_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh109_regist"]:$this->rh109_regist);
       if($this->rh109_periodoaquisitivoinicial == ""){
         $this->rh109_periodoaquisitivoinicial_dia = ($this->rh109_periodoaquisitivoinicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh109_periodoaquisitivoinicial_dia"]:$this->rh109_periodoaquisitivoinicial_dia);
         $this->rh109_periodoaquisitivoinicial_mes = ($this->rh109_periodoaquisitivoinicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh109_periodoaquisitivoinicial_mes"]:$this->rh109_periodoaquisitivoinicial_mes);
         $this->rh109_periodoaquisitivoinicial_ano = ($this->rh109_periodoaquisitivoinicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh109_periodoaquisitivoinicial_ano"]:$this->rh109_periodoaquisitivoinicial_ano);
         if($this->rh109_periodoaquisitivoinicial_dia != ""){
            $this->rh109_periodoaquisitivoinicial = $this->rh109_periodoaquisitivoinicial_ano."-".$this->rh109_periodoaquisitivoinicial_mes."-".$this->rh109_periodoaquisitivoinicial_dia;
         }
       }
       if($this->rh109_periodoaquisitivofinal == ""){
         $this->rh109_periodoaquisitivofinal_dia = ($this->rh109_periodoaquisitivofinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh109_periodoaquisitivofinal_dia"]:$this->rh109_periodoaquisitivofinal_dia);
         $this->rh109_periodoaquisitivofinal_mes = ($this->rh109_periodoaquisitivofinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh109_periodoaquisitivofinal_mes"]:$this->rh109_periodoaquisitivofinal_mes);
         $this->rh109_periodoaquisitivofinal_ano = ($this->rh109_periodoaquisitivofinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh109_periodoaquisitivofinal_ano"]:$this->rh109_periodoaquisitivofinal_ano);
         if($this->rh109_periodoaquisitivofinal_dia != ""){
            $this->rh109_periodoaquisitivofinal = $this->rh109_periodoaquisitivofinal_ano."-".$this->rh109_periodoaquisitivofinal_mes."-".$this->rh109_periodoaquisitivofinal_dia;
         }
       }
       $this->rh109_diasdireito = ($this->rh109_diasdireito == ""?@$GLOBALS["HTTP_POST_VARS"]["rh109_diasdireito"]:$this->rh109_diasdireito);
       $this->rh109_faltasperiodoaquisitivo = ($this->rh109_faltasperiodoaquisitivo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh109_faltasperiodoaquisitivo"]:$this->rh109_faltasperiodoaquisitivo);
       $this->rh109_observacao = ($this->rh109_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh109_observacao"]:$this->rh109_observacao);
       $this->rh109_perdeudireitoferias = ($this->rh109_perdeudireitoferias == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh109_perdeudireitoferias"]:$this->rh109_perdeudireitoferias);
     }else{
       $this->rh109_sequencial = ($this->rh109_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh109_sequencial"]:$this->rh109_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh109_sequencial){
      $this->atualizacampos();
     if($this->rh109_regist == null ){
       $this->erro_sql = " Campo Matrícula não informado.";
       $this->erro_campo = "rh109_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh109_periodoaquisitivoinicial == null ){
       $this->erro_sql = " Campo Período aquisitivo inicial não informado.";
       $this->erro_campo = "rh109_periodoaquisitivoinicial_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh109_periodoaquisitivofinal == null ){
       $this->erro_sql = " Campo Período aquisitivo final não informado.";
       $this->erro_campo = "rh109_periodoaquisitivofinal_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh109_diasdireito == null ){
       $this->erro_sql = " Campo Dias de direito não informado.";
       $this->erro_campo = "rh109_diasdireito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh109_faltasperiodoaquisitivo == null ){
       $this->rh109_faltasperiodoaquisitivo = "0";
     }
     if($this->rh109_perdeudireitoferias == null ){
       $this->erro_sql = " Campo Perdeu Direito a Férias não informado.";
       $this->erro_campo = "rh109_perdeudireitoferias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh109_sequencial == "" || $rh109_sequencial == null ){
       $result = db_query("select nextval('rhferias_rh109_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhferias_rh109_sequencial_seq do campo: rh109_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->rh109_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from rhferias_rh109_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh109_sequencial)){
         $this->erro_sql = " Campo rh109_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh109_sequencial = $rh109_sequencial;
       }
     }
     if(($this->rh109_sequencial == null) || ($this->rh109_sequencial == "") ){
       $this->erro_sql = " Campo rh109_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhferias(
                                       rh109_sequencial 
                                      ,rh109_regist 
                                      ,rh109_periodoaquisitivoinicial 
                                      ,rh109_periodoaquisitivofinal 
                                      ,rh109_diasdireito 
                                      ,rh109_faltasperiodoaquisitivo 
                                      ,rh109_observacao 
                                      ,rh109_perdeudireitoferias 
                       )
                values (
                                $this->rh109_sequencial 
                               ,$this->rh109_regist 
                               ,".($this->rh109_periodoaquisitivoinicial == "null" || $this->rh109_periodoaquisitivoinicial == ""?"null":"'".$this->rh109_periodoaquisitivoinicial."'")." 
                               ,".($this->rh109_periodoaquisitivofinal == "null" || $this->rh109_periodoaquisitivofinal == ""?"null":"'".$this->rh109_periodoaquisitivofinal."'")." 
                               ,$this->rh109_diasdireito 
                               ,$this->rh109_faltasperiodoaquisitivo 
                               ,'$this->rh109_observacao' 
                               ,'$this->rh109_perdeudireitoferias' 
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de ferias ($this->rh109_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de ferias já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de ferias ($this->rh109_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh109_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh109_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18957,'$this->rh109_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3373,18957,'','".AddSlashes(pg_result($resaco,0,'rh109_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3373,18958,'','".AddSlashes(pg_result($resaco,0,'rh109_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3373,18959,'','".AddSlashes(pg_result($resaco,0,'rh109_periodoaquisitivoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3373,18960,'','".AddSlashes(pg_result($resaco,0,'rh109_periodoaquisitivofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3373,18961,'','".AddSlashes(pg_result($resaco,0,'rh109_diasdireito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3373,18966,'','".AddSlashes(pg_result($resaco,0,'rh109_faltasperiodoaquisitivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3373,20166,'','".AddSlashes(pg_result($resaco,0,'rh109_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3373,1009478,'','".AddSlashes(pg_result($resaco,0,'rh109_perdeudireitoferias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($rh109_sequencial=null) {
      $this->atualizacampos();
     $sql = " update rhferias set ";
     $virgula = "";
     if(trim($this->rh109_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh109_sequencial"])){
       $sql  .= $virgula." rh109_sequencial = $this->rh109_sequencial ";
       $virgula = ",";
       if(trim($this->rh109_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh109_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh109_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh109_regist"])){
       $sql  .= $virgula." rh109_regist = $this->rh109_regist ";
       $virgula = ",";
       if(trim($this->rh109_regist) == null ){
         $this->erro_sql = " Campo Matrícula não informado.";
         $this->erro_campo = "rh109_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh109_periodoaquisitivoinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh109_periodoaquisitivoinicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh109_periodoaquisitivoinicial_dia"] !="") ){
       $sql  .= $virgula." rh109_periodoaquisitivoinicial = '$this->rh109_periodoaquisitivoinicial' ";
       $virgula = ",";
       if(trim($this->rh109_periodoaquisitivoinicial) == null ){
         $this->erro_sql = " Campo Período aquisitivo inicial não informado.";
         $this->erro_campo = "rh109_periodoaquisitivoinicial_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh109_periodoaquisitivoinicial_dia"])){
         $sql  .= $virgula." rh109_periodoaquisitivoinicial = null ";
         $virgula = ",";
         if(trim($this->rh109_periodoaquisitivoinicial) == null ){
           $this->erro_sql = " Campo Período aquisitivo inicial não informado.";
           $this->erro_campo = "rh109_periodoaquisitivoinicial_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh109_periodoaquisitivofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh109_periodoaquisitivofinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh109_periodoaquisitivofinal_dia"] !="") ){
       $sql  .= $virgula." rh109_periodoaquisitivofinal = '$this->rh109_periodoaquisitivofinal' ";
       $virgula = ",";
       if(trim($this->rh109_periodoaquisitivofinal) == null ){
         $this->erro_sql = " Campo Período aquisitivo final não informado.";
         $this->erro_campo = "rh109_periodoaquisitivofinal_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh109_periodoaquisitivofinal_dia"])){
         $sql  .= $virgula." rh109_periodoaquisitivofinal = null ";
         $virgula = ",";
         if(trim($this->rh109_periodoaquisitivofinal) == null ){
           $this->erro_sql = " Campo Período aquisitivo final não informado.";
           $this->erro_campo = "rh109_periodoaquisitivofinal_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh109_diasdireito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh109_diasdireito"])){
       $sql  .= $virgula." rh109_diasdireito = $this->rh109_diasdireito ";
       $virgula = ",";
       if(trim($this->rh109_diasdireito) == null ){
         $this->erro_sql = " Campo Dias de direito não informado.";
         $this->erro_campo = "rh109_diasdireito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh109_faltasperiodoaquisitivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh109_faltasperiodoaquisitivo"])){
        if(trim($this->rh109_faltasperiodoaquisitivo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh109_faltasperiodoaquisitivo"])){
           $this->rh109_faltasperiodoaquisitivo = "0" ;
        }
       $sql  .= $virgula." rh109_faltasperiodoaquisitivo = $this->rh109_faltasperiodoaquisitivo ";
       $virgula = ",";
     }
     if(trim($this->rh109_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh109_observacao"])){
       $sql  .= $virgula." rh109_observacao = '$this->rh109_observacao' ";
       $virgula = ",";
     }
     if(trim($this->rh109_perdeudireitoferias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh109_perdeudireitoferias"])){
       $sql  .= $virgula." rh109_perdeudireitoferias = '$this->rh109_perdeudireitoferias' ";
       $virgula = ",";
       if(trim($this->rh109_perdeudireitoferias) == null ){
         $this->erro_sql = " Campo Perdeu Direito a Férias não informado.";
         $this->erro_campo = "rh109_perdeudireitoferias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh109_sequencial!=null){
       $sql .= " rh109_sequencial = $this->rh109_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh109_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,18957,'$this->rh109_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh109_sequencial"]) || $this->rh109_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3373,18957,'".AddSlashes(pg_result($resaco,$conresaco,'rh109_sequencial'))."','$this->rh109_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh109_regist"]) || $this->rh109_regist != "")
             $resac = db_query("insert into db_acount values($acount,3373,18958,'".AddSlashes(pg_result($resaco,$conresaco,'rh109_regist'))."','$this->rh109_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh109_periodoaquisitivoinicial"]) || $this->rh109_periodoaquisitivoinicial != "")
             $resac = db_query("insert into db_acount values($acount,3373,18959,'".AddSlashes(pg_result($resaco,$conresaco,'rh109_periodoaquisitivoinicial'))."','$this->rh109_periodoaquisitivoinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh109_periodoaquisitivofinal"]) || $this->rh109_periodoaquisitivofinal != "")
             $resac = db_query("insert into db_acount values($acount,3373,18960,'".AddSlashes(pg_result($resaco,$conresaco,'rh109_periodoaquisitivofinal'))."','$this->rh109_periodoaquisitivofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh109_diasdireito"]) || $this->rh109_diasdireito != "")
             $resac = db_query("insert into db_acount values($acount,3373,18961,'".AddSlashes(pg_result($resaco,$conresaco,'rh109_diasdireito'))."','$this->rh109_diasdireito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh109_faltasperiodoaquisitivo"]) || $this->rh109_faltasperiodoaquisitivo != "")
             $resac = db_query("insert into db_acount values($acount,3373,18966,'".AddSlashes(pg_result($resaco,$conresaco,'rh109_faltasperiodoaquisitivo'))."','$this->rh109_faltasperiodoaquisitivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh109_observacao"]) || $this->rh109_observacao != "")
             $resac = db_query("insert into db_acount values($acount,3373,20166,'".AddSlashes(pg_result($resaco,$conresaco,'rh109_observacao'))."','$this->rh109_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh109_perdeudireitoferias"]) || $this->rh109_perdeudireitoferias != "")
             $resac = db_query("insert into db_acount values($acount,3373,1009478,'".AddSlashes(pg_result($resaco,$conresaco,'rh109_perdeudireitoferias'))."','$this->rh109_perdeudireitoferias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de ferias não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh109_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de ferias não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh109_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh109_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($rh109_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh109_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,18957,'$rh109_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3373,18957,'','".AddSlashes(pg_result($resaco,$iresaco,'rh109_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3373,18958,'','".AddSlashes(pg_result($resaco,$iresaco,'rh109_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3373,18959,'','".AddSlashes(pg_result($resaco,$iresaco,'rh109_periodoaquisitivoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3373,18960,'','".AddSlashes(pg_result($resaco,$iresaco,'rh109_periodoaquisitivofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3373,18961,'','".AddSlashes(pg_result($resaco,$iresaco,'rh109_diasdireito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3373,18966,'','".AddSlashes(pg_result($resaco,$iresaco,'rh109_faltasperiodoaquisitivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3373,20166,'','".AddSlashes(pg_result($resaco,$iresaco,'rh109_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3373,1009478,'','".AddSlashes(pg_result($resaco,$iresaco,'rh109_perdeudireitoferias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhferias
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh109_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh109_sequencial = $rh109_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de ferias não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh109_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de ferias não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh109_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh109_sequencial;
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
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:rhferias";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($rh109_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= " from rhferias ";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhferias.rh109_regist";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      left  join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao and  rhfuncao.rh37_instit = rhpessoal.rh01_instit";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql .= "      left  join rhsindicato  on  rhsindicato.rh116_sequencial = rhpessoal.rh01_rhsindicato";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh109_sequencial)) {
         $sql2 .= " where rhferias.rh109_sequencial = $rh109_sequencial ";
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
    public function sql_query_file ($rh109_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

        $sql  = "select {$campos} ";
        $sql .= "  from rhferias ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($rh109_sequencial)){
                $sql2 .= " where rhferias.rh109_sequencial = $rh109_sequencial ";
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
   * Sql que deverá retornar servidores que não possuem período aquisitivo cadastrado ou o último período aquisitivo
   * registrado estiver terminando entre o período informado
   * @param DBDate $oDataFechamentoFolhaInicio
   * @param DBDate $oDataFechamentoFolhaFim
   * @return string
   */
  function sql_queryAlteracaoPeriodoAquisitivoServidores(DBDate $oDataFechamentoFolhaInicio, DBDate $oDataFechamentoFolhaFim) {

  	$iInstituicao = db_getsession('DB_instit');

  	$sSqlServidoresPeriodoAquisitivo  = "select rhpessoal.rh01_regist                           as matricula,                                                                                           ";
  	$sSqlServidoresPeriodoAquisitivo .= "       rhpessoal.rh01_admiss                           as data_admissao,																																				                ";
  	$sSqlServidoresPeriodoAquisitivo .= "       rhregime.rh30_periodoaquisitivo                 as meses_periodo_aquisitivo,                                                                            ";
  	$sSqlServidoresPeriodoAquisitivo .= "       rhregime.rh30_periodogozoferias                 as dias_periodo_gozo,                                                                                   ";
  	$sSqlServidoresPeriodoAquisitivo .= "       (select max(rh109_sequencial)                                                                                                                           ";
  	$sSqlServidoresPeriodoAquisitivo .= "          from rhferias r                                                                                                                                      ";
  	$sSqlServidoresPeriodoAquisitivo .= "         where r.rh109_regist = rhpessoal.rh01_regist) as ultimo_periodo_aquisitivo                                                                            ";
  	$sSqlServidoresPeriodoAquisitivo .= "                                                                                                                                                               ";
  	$sSqlServidoresPeriodoAquisitivo .= "  from rhpessoal                                                                                                                                               ";
  	$sSqlServidoresPeriodoAquisitivo .= " inner join rhpessoalmov on rhpessoalmov.rh02_regist = rhpessoal.rh01_regist                                                                                   ";
  	$sSqlServidoresPeriodoAquisitivo .= "                        and rhpessoalmov.rh02_instit = rhpessoal.rh01_instit                                                                                   ";
  	$sSqlServidoresPeriodoAquisitivo .= " inner join rhregime     on rhregime.rh30_codreg     = rhpessoalmov.rh02_codreg                                                                                ";
  	$sSqlServidoresPeriodoAquisitivo .= " where (not exists(select 1                                                                                                                                    ";
  	$sSqlServidoresPeriodoAquisitivo .= "                     from rhferias                                                                                                                             ";
  	$sSqlServidoresPeriodoAquisitivo .= "                    where rhferias.rh109_regist = rhpessoal.rh01_regist)                                                                                       ";
  	$sSqlServidoresPeriodoAquisitivo .= "         or                                                                                                                                                    ";
  	$sSqlServidoresPeriodoAquisitivo .= "            exists(select 1                                                                                                                                    ";
  	$sSqlServidoresPeriodoAquisitivo .= "                     from rhferias                                                                                                                             ";
  	$sSqlServidoresPeriodoAquisitivo .= "                    where rhferias.rh109_regist = rhpessoal.rh01_regist                                                                                        ";
  	$sSqlServidoresPeriodoAquisitivo .= "                      and rhferias.rh109_sequencial = (select max(rh109_sequencial)                                                                            ";
  	$sSqlServidoresPeriodoAquisitivo .= "                                                         from rhferias r                                                                                       ";
  	$sSqlServidoresPeriodoAquisitivo .= "                                                        where r.rh109_regist = rhpessoal.rh01_regist)                                                          ";
  	$sSqlServidoresPeriodoAquisitivo .= "                     and rhferias.rh109_periodoaquisitivofinal between '{$oDataFechamentoFolhaInicio->getDate()}' and '{$oDataFechamentoFolhaFim->getDate()}'))";
  	$sSqlServidoresPeriodoAquisitivo .= "   and rhpessoalmov.rh02_anousu = {$oDataFechamentoFolhaInicio->getAno()}                                                                                      ";
  	$sSqlServidoresPeriodoAquisitivo .= "   and rhpessoalmov.rh02_mesusu = {$oDataFechamentoFolhaInicio->getMes()}                                                                                      ";
  	$sSqlServidoresPeriodoAquisitivo .= "   and rhpessoalmov.rh02_instit = {$iInstituicao}                                                                                                              ";

  	return $sSqlServidoresPeriodoAquisitivo;

  }

  function sql_query_busca_matriculas_selecao($iAnoUsu, $iMesUsu, $sCampos, $sWhere) {

  	$sWhereSql = "";
  	$iInstit   = db_getsession('DB_instit');
  	if(!empty($sWhere)){
  		$sWhereSql = " and {$sWhere} ";
  	}

  	$sSql  = " select {$sCampos} ";
  	$sSql .= "   from rhpessoal ";
  	$sSql .= "        inner join rhpessoalmov    on rhpessoalmov.rh02_regist           = rhpessoal.rh01_regist        ";
  	$sSql .= "                                  and rhpessoalmov.rh02_anousu           = {$iAnoUsu}                   ";
  	$sSql .= "                                  and rhpessoalmov.rh02_mesusu           = {$iMesUsu}                   ";
  	$sSql .= "                                  and rhpessoalmov.rh02_instit           = {$iInstit}                   ";
  	$sSql .= "        left  join rhpeslocaltrab  on rhpeslocaltrab.rh56_seqpes         = rhpessoalmov.rh02_seqpes     ";
  	$sSql .= "                                  and rhpeslocaltrab.rh56_princ          = 't'                          ";
  	$sSql .= "        left  join rhlocaltrab     on rhlocaltrab.rh55_codigo            = rhpeslocaltrab.rh56_localtrab";
  	$sSql .= "                                  and rhlocaltrab.rh55_instit            = rhpessoalmov.rh02_instit     ";
  	$sSql .= "        left  join rhpescargo      on rhpescargo.rh20_seqpes             = rhpessoalmov.rh02_seqpes     ";
  	$sSql .= "        left  join rhcargo         on rhcargo.rh04_codigo                = rhpescargo.rh20_cargo        ";
  	$sSql .= "                                  and rhcargo.rh04_instit                = rhpessoalmov.rh02_instit     ";
  	$sSql .= "        inner join rhinstrucao     on rhinstrucao.rh21_instru            = rhpessoal.rh01_instru        ";
  	$sSql .= "        inner join rhestcivil      on rhestcivil.rh08_estciv             = rhpessoal.rh01_estciv        ";
  	$sSql .= "        inner join rhnacionalidade on rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion        ";
  	$sSql .= "        left  join rhpesfgts       on rhpesfgts.rh15_regist              = rhpessoal.rh01_regist        ";
  	$sSql .= "        left  join db_bancos       on db_bancos.db90_codban              = rhpesfgts.rh15_banco         ";
  	$sSql .= "        inner join cgm             on cgm.z01_numcgm                     = rhpessoal.rh01_numcgm        ";
  	$sSql .= "        inner join rhfuncao        on rhfuncao.rh37_funcao               = rhpessoal.rh01_funcao        ";
  	$sSql .= "                                  and rhfuncao.rh37_instit               = {$iInstit}                   ";
  	$sSql .= "        inner join rhlota          on rhlota.r70_codigo                  = rhpessoalmov.rh02_lota       ";
  	$sSql .= "                                  and rhlota.r70_instit                  = rhpessoalmov.rh02_instit     ";
  	$sSql .= "        inner join rhregime        on rhregime.rh30_codreg               = rhpessoalmov.rh02_codreg     ";
  	$sSql .= "                                  and rhregime.rh30_instit               = rhpessoalmov.rh02_instit     ";
  	$sSql .= "        left  join afasta          on afasta.r45_anousu                  = rhpessoalmov.rh02_anousu     ";
  	$sSql .= "                                  and afasta.r45_mesusu                  = rhpessoalmov.rh02_mesusu     ";
  	$sSql .= "                                  and afasta.r45_regist                  = rhpessoalmov.rh02_regist     ";
  	$sSql .= "        left  join rhlotaexe       on rhlotaexe.rh26_anousu              = rhpessoalmov.rh02_anousu     ";
  	$sSql .= "                                  and rhlotaexe.rh26_codigo              = rhlota.r70_codigo            ";
  	$sSql .= "        left  join orcunidade      on orcunidade.o41_anousu              = rhlotaexe.rh26_anousu        ";
  	$sSql .= "                                  and orcunidade.o41_orgao               = rhlotaexe.rh26_orgao         ";
  	$sSql .= "                                  and orcunidade.o41_unidade             = rhlotaexe.rh26_unidade       ";
  	$sSql .= "        left  join orcorgao        on orcorgao.o40_anousu                = orcunidade.o41_anousu        ";
  	$sSql .= "                                  and orcorgao.o40_orgao                 = orcunidade.o41_orgao         ";
  	$sSql .= "        left  join rhpesrescisao   on rhpesrescisao.rh05_seqpes          = rhpessoalmov.rh02_seqpes     ";
  	$sSql .= "        left  join rhpespadrao     on rhpespadrao.rh03_seqpes            = rhpessoalmov.rh02_seqpes     ";
  	$sSql .= "                                  and rhpespadrao.rh03_anousu            = {$iAnoUsu}                   ";
  	$sSql .= "                                  and rhpespadrao.rh03_mesusu            = {$iMesUsu}                   ";
  	$sSql .= "        left  join padroes         on padroes.r02_anousu                 = rhpespadrao.rh03_anousu      ";
  	$sSql .= "                                  and padroes.r02_mesusu                 = rhpespadrao.rh03_mesusu      ";
  	$sSql .= "                                  and padroes.r02_regime                 = rhpespadrao.rh03_regime      ";
  	$sSql .= "                                  and padroes.r02_codigo                 = rhpespadrao.rh03_padrao      ";
  	$sSql .= "                                  and padroes.r02_instit                 = {$iInstit}                   ";
  	$sSql .= "  where rhpessoalmov.rh02_anousu = {$iAnoUsu}                                                           ";
  	$sSql .= "    and rhpessoalmov.rh02_mesusu = {$iMesUsu}                                                           ";
  	$sSql .= $sWhereSql;

  	return $sSql;

  }

  function sql_query_ferias_selecao($iAnoUsu, $iMesUsu, $sCampos, $sWhere) {

  	$sWhereSql = "";
  	if(!empty($sWhere)){
  		$sWhereSql = " and {$sWhere} ";
  	}

  	$sSql  = "select {$sCampos} ";
  	$sSql .= "  from rhferias                                                                                   ";
  	$sSql .= "  left join rhferiasperiodo on rhferiasperiodo.rh110_rhferias     = rhferias.rh109_sequencial     ";
  	$sSql .= " inner join rhpessoal       on rhpessoal.rh01_regist              = rhferias.rh109_regist         ";
  	$sSql .= " inner join rhpessoalmov    on rhpessoalmov.rh02_regist           = rhpessoal.rh01_regist         ";
  	$sSql .= "                           and rhpessoalmov.rh02_anousu           = {$iAnoUsu}                    ";
  	$sSql .= "                           and rhpessoalmov.rh02_mesusu           = {$iMesUsu}                    ";
  	$sSql .= "                           and rhpessoalmov.rh02_instit           = ".db_getsession("DB_instit");
  	$sSql .= "  left join rhpeslocaltrab  on rhpeslocaltrab.rh56_seqpes         = rhpessoalmov.rh02_seqpes      ";
  	$sSql .= "                           and rhpeslocaltrab.rh56_princ          = 't'                           ";
  	$sSql .= "  left join rhlocaltrab     on rhlocaltrab.rh55_codigo            = rhpeslocaltrab.rh56_localtrab ";
  	$sSql .= "                           and rhlocaltrab.rh55_instit            = rhpessoalmov.rh02_instit      ";
  	$sSql .= "  left join rhpescargo      on rhpescargo.rh20_seqpes             = rhpessoalmov.rh02_seqpes      ";
  	$sSql .= "  left join rhcargo         on rhcargo.rh04_codigo                = rhpescargo.rh20_cargo         ";
  	$sSql .= "                           and rhcargo.rh04_instit                = rhpessoalmov.rh02_instit      ";
  	$sSql .= " inner join rhinstrucao     on rhinstrucao.rh21_instru            = rhpessoal.rh01_instru         ";
  	$sSql .= " inner join rhestcivil      on rhestcivil.rh08_estciv             = rhpessoal.rh01_estciv         ";
  	$sSql .= " inner join rhnacionalidade on rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion         ";
  	$sSql .= "  left join rhpesfgts       on rhpesfgts.rh15_regist              = rhpessoal.rh01_regist         ";
  	$sSql .= "  left join db_bancos       on db_bancos.db90_codban              = rhpesfgts.rh15_banco          ";
  	$sSql .= " inner join cgm             on cgm.z01_numcgm                     = rhpessoal.rh01_numcgm         ";
  	$sSql .= " inner join rhfuncao        on rhfuncao.rh37_funcao               = rhpessoal.rh01_funcao         ";
  	$sSql .= "                           and rhfuncao.rh37_instit               = ".db_getsession("DB_instit");
  	$sSql .= " inner join rhlota          on rhlota.r70_codigo                  = rhpessoalmov.rh02_lota        ";
  	$sSql .= "                           and rhlota.r70_instit                  = rhpessoalmov.rh02_instit      ";
  	$sSql .= " inner join rhregime        on rhregime.rh30_codreg               = rhpessoalmov.rh02_codreg      ";
  	$sSql .= "                           and rhregime.rh30_instit               = rhpessoalmov.rh02_instit      ";
  	$sSql .= "  left join afasta          on afasta.r45_anousu                  = rhpessoalmov.rh02_anousu      ";
  	$sSql .= "                           and afasta.r45_mesusu                  = rhpessoalmov.rh02_mesusu      ";
  	$sSql .= "                           and afasta.r45_regist                  = rhpessoalmov.rh02_regist      ";
  	$sSql .= "  left join rhlotaexe       on rhlotaexe.rh26_anousu              = rhpessoalmov.rh02_anousu      ";
  	$sSql .= "                           and rhlotaexe.rh26_codigo              = rhlota.r70_codigo             ";
  	$sSql .= "  left join orcunidade      on orcunidade.o41_anousu              = rhlotaexe.rh26_anousu         ";
  	$sSql .= "                           and orcunidade.o41_orgao               = rhlotaexe.rh26_orgao          ";
  	$sSql .= "                           and orcunidade.o41_unidade             = rhlotaexe.rh26_unidade        ";
  	$sSql .= "  left join orcorgao        on orcorgao.o40_anousu                = orcunidade.o41_anousu         ";
  	$sSql .= "                           and orcorgao.o40_orgao                 = orcunidade.o41_orgao          ";
  	$sSql .= "  left join rhpesrescisao   on rhpesrescisao.rh05_seqpes          = rhpessoalmov.rh02_seqpes      ";
  	$sSql .= "  left join rhpespadrao     on rhpespadrao.rh03_seqpes            = rhpessoalmov.rh02_seqpes      ";
  	$sSql .= "                           and rhpespadrao.rh03_anousu            = {$iAnoUsu}                    ";
  	$sSql .= "                           and rhpespadrao.rh03_mesusu            = {$iMesUsu}                    ";
  	$sSql .= "  left join padroes         on padroes.r02_anousu                 = rhpespadrao.rh03_anousu       ";
  	$sSql .= "                           and padroes.r02_mesusu                 = rhpespadrao.rh03_mesusu       ";
  	$sSql .= "                           and padroes.r02_regime                 = rhpespadrao.rh03_regime       ";
  	$sSql .= "                           and padroes.r02_codigo                 = rhpespadrao.rh03_padrao       ";
  	$sSql .= "                           and padroes.r02_instit                 = ".db_getsession("DB_instit");
  	$sSql .= " where rhpessoalmov.rh02_anousu = {$iAnoUsu}                                                      ";
  	$sSql .= "   and rhpessoalmov.rh02_mesusu = {$iMesUsu}                                                      ";
  	$sSql .= "   and rhferias.rh109_anousu = {$iAnoUsu}                                                         ";
  	$sSql .= "   and rhferias.rh109_mesusu = {$iMesUsu}                                                         ";
  	$sSql .= "   and rhferiasperiodo.rh110_anopagamento >= {$iAnoUsu}                                           ";
  	$sSql .= "   and rhferiasperiodo.rh110_mespagamento >= {$iMesUsu}                                           ";
  	$sSql .= "   and rhpesrescisao.rh05_seqpes is null                                                          ";
  	$sSql .= $sWhereSql;

  	return $sSql;

  }

  /**
   * Retorna o último periodo aquisitivo com dias de direito disponível para a matricula informada como parametro.
   * @param  integer $iMatricula Número da matricula que deve ser buscado o peíodo aquisitivo
   * @param  string  $sCampos    Campos que devem ser informados no select
   * @return string  $sSql
   */
  function sql_query_proximo_periodo_aquisitivo($iMatricula, $sCampos){

    $sSql  = "  select {$sCampos}                                                                     ";
    $sSql .= "    from rhferias                                                                       ";
    $sSql .= "   where rh109_regist = {$iMatricula}                                                   ";
    $sSql .= "     and (rh109_diasdireito - ( select coalesce(sum(rh110_dias + rh110_diasabono ),0)   ";
    $sSql .= "                                 from rhferiasperiodo                                   ";
    $sSql .= "                                where rh110_rhferias = rh109_sequencial)::integer ) > 0 ";
    $sSql .= "order by rh109_periodoaquisitivoinicial limit 1;                                        ";

  	return $sSql;
  }

  /**
   * Retorna os periodos de gozo cadastrados, para a competencia e a situação informados.
   * @param  integer $iAnoPagamento Ano competência
   * @param  integer $iMesPagamento Mês competência
   * @param  integer $iSituacao    Situação do periodo
   * @return string                sSql
   */
  function sql_query_periodos_aquisitivos_competencia($iAnoPagamento, $iMesPagamento, $iSituacao){

    $sSql  = "select z01_nome,                                                                       ";
    $sSql .= "       rh110_sequencial,                                                               ";
    $sSql .= "       rh110_datainicial,                                                              ";
    $sSql .= "       rh110_datafinal,                                                                ";
    $sSql .= "       rh110_dias,                                                                     ";
    $sSql .= "       rh109_regist,                                                                   ";
    $sSql .= "       case when rh110_periodoespecificoinicial is not null                            ";
    $sSql .= "              then rh110_periodoespecificoinicial                                      ";
    $sSql .= "              else rh109_periodoaquisitivoinicial                                      ";
    $sSql .= "       end as periodoaquisitivoinicial,                                                ";
    $sSql .= "       case when rh110_periodoespecificofinal is not null                              ";
    $sSql .= "              then rh110_periodoespecificofinal                                        ";
    $sSql .= "              else rh109_periodoaquisitivofinal                                        ";
    $sSql .= "       end as periodoaquisitivofinal                                                   ";
    $sSql .= "  from rhferias                                                                        ";
    $sSql .= "       inner join rhferiasperiodo on rh110_rhferias = rh109_sequencial                 ";
    $sSql .= "       inner join rhpessoal on rh01_regist = rh109_regist                              ";
    $sSql .= "       inner join cgm on z01_numcgm = rh01_numcgm                                      ";
    $sSql .= " where rh110_anopagamento = {$iAnoPagamento} and rh110_mespagamento = {$iMesPagamento} ";
    $sSql .= "       and rh110_situacao = {$iSituacao};                                              ";

    return $sSql;
  }

  /**
   * Retorna o último periodo aquisitivo com dias de direito disponível para a matricula informada como parametro.
   * @param  integer $iMatricula Número da matricula que deve ser buscado o peíodo aquisitivo
   * @param  string  $sCampos    Campos que devem ser informados no select
   * @return string  $sSql
   */
  function sql_query_periodos_aquisitivos_com_saldo($iMatricula, $sCampos) {

    $sSql  = "  select {$sCampos}                                                                     ";
    $sSql .= "    from rhferias                                                                       ";
    $sSql .= "   where rh109_regist = {$iMatricula}                                                   ";
    $sSql .= "     and (rh109_diasdireito - ( select coalesce(sum(rh110_dias + rh110_diasabono ),0)   ";
    $sSql .= "                                 from rhferiasperiodo                                   ";
    $sSql .= "                                where rh110_rhferias = rh109_sequencial)::integer ) > 0 ";
    $sSql .= "order by rh109_periodoaquisitivoinicial;                                        ";

    return $sSql;
  }
}