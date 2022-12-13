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
//CLASSE DA ENTIDADE procjur
class cl_procjur {
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
   var $v62_sequencial = 0;
   var $v62_procjurtipo = 0;
   var $v62_descricao = null;
   var $v62_data_dia = null;
   var $v62_data_mes = null;
   var $v62_data_ano = null;
   var $v62_data = null;
   var $v62_hora = null;
   var $v62_usuario = 0;
   var $v62_dataini_dia = null;
   var $v62_dataini_mes = null;
   var $v62_dataini_ano = null;
   var $v62_dataini = null;
   var $v62_datafim_dia = null;
   var $v62_datafim_mes = null;
   var $v62_datafim_ano = null;
   var $v62_datafim = null;
   var $v62_situacao = 0;
   var $v62_obs = null;
   var $v62_instit = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 v62_sequencial = int4 = Sequencial
                 v62_procjurtipo = int4 = Tipo Processo
                 v62_descricao = varchar(40) = Descrição
                 v62_data = date = Data
                 v62_hora = char(5) = Hora
                 v62_usuario = int4 = Usuário
                 v62_dataini = date = Data Inicial
                 v62_datafim = date = Data Final
                 v62_situacao = int4 = Situação
                 v62_obs = text = Observação
                 v62_instit = int4 = Instituição
                 ";
   //funcao construtor da classe
   function cl_procjur() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("procjur");
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
       $this->v62_sequencial = ($this->v62_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v62_sequencial"]:$this->v62_sequencial);
       $this->v62_procjurtipo = ($this->v62_procjurtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["v62_procjurtipo"]:$this->v62_procjurtipo);
       $this->v62_descricao = ($this->v62_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["v62_descricao"]:$this->v62_descricao);
       if($this->v62_data == ""){
         $this->v62_data_dia = ($this->v62_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v62_data_dia"]:$this->v62_data_dia);
         $this->v62_data_mes = ($this->v62_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v62_data_mes"]:$this->v62_data_mes);
         $this->v62_data_ano = ($this->v62_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v62_data_ano"]:$this->v62_data_ano);
         if($this->v62_data_dia != ""){
            $this->v62_data = $this->v62_data_ano."-".$this->v62_data_mes."-".$this->v62_data_dia;
         }
       }
       $this->v62_hora = ($this->v62_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["v62_hora"]:$this->v62_hora);
       $this->v62_usuario = ($this->v62_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["v62_usuario"]:$this->v62_usuario);
       if($this->v62_dataini == ""){
         $this->v62_dataini_dia = ($this->v62_dataini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v62_dataini_dia"]:$this->v62_dataini_dia);
         $this->v62_dataini_mes = ($this->v62_dataini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v62_dataini_mes"]:$this->v62_dataini_mes);
         $this->v62_dataini_ano = ($this->v62_dataini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v62_dataini_ano"]:$this->v62_dataini_ano);
         if($this->v62_dataini_dia != ""){
            $this->v62_dataini = $this->v62_dataini_ano."-".$this->v62_dataini_mes."-".$this->v62_dataini_dia;
         }
       }
       if($this->v62_datafim == ""){
         $this->v62_datafim_dia = ($this->v62_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v62_datafim_dia"]:$this->v62_datafim_dia);
         $this->v62_datafim_mes = ($this->v62_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v62_datafim_mes"]:$this->v62_datafim_mes);
         $this->v62_datafim_ano = ($this->v62_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v62_datafim_ano"]:$this->v62_datafim_ano);
         if($this->v62_datafim_dia != ""){
            $this->v62_datafim = $this->v62_datafim_ano."-".$this->v62_datafim_mes."-".$this->v62_datafim_dia;
         }
       }
       $this->v62_situacao = ($this->v62_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["v62_situacao"]:$this->v62_situacao);
       $this->v62_obs = ($this->v62_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["v62_obs"]:$this->v62_obs);
       $this->v62_instit = ($this->v62_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["v62_instit"]:$this->v62_instit);
     }else{
       $this->v62_sequencial = ($this->v62_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v62_sequencial"]:$this->v62_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($v62_sequencial){
      $this->atualizacampos();
     if($this->v62_procjurtipo == null ){
       $this->erro_sql = " Campo Tipo Processo não informado.";
       $this->erro_campo = "v62_procjurtipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v62_descricao == null ){
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "v62_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v62_data == null ){
       $this->erro_sql = " Campo Data não informado.";
       $this->erro_campo = "v62_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v62_hora == null ){
       $this->erro_sql = " Campo Hora não informado.";
       $this->erro_campo = "v62_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v62_usuario == null ){
       $this->erro_sql = " Campo Usuário não informado.";
       $this->erro_campo = "v62_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v62_dataini == null ){
       $this->erro_sql = " Campo Data Inicial não informado.";
       $this->erro_campo = "v62_dataini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v62_situacao == null ){
       $this->erro_sql = " Campo Situação não informado.";
       $this->erro_campo = "v62_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v62_obs == null ){
       $this->erro_sql = " Campo Observação não informado.";
       $this->erro_campo = "v62_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v62_instit == null ){
       $this->v62_instit = db_getsession("DB_instit");
     }
     if($v62_sequencial == "" || $v62_sequencial == null ){
       $result = db_query("select nextval('procjur_v62_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: procjur_v62_sequencial_seq do campo: v62_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->v62_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from procjur_v62_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v62_sequencial)){
         $this->erro_sql = " Campo v62_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v62_sequencial = $v62_sequencial;
       }
     }
     if(($this->v62_sequencial == null) || ($this->v62_sequencial == "") ){
       $this->erro_sql = " Campo v62_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into procjur(
                                       v62_sequencial
                                      ,v62_procjurtipo
                                      ,v62_descricao
                                      ,v62_data
                                      ,v62_hora
                                      ,v62_usuario
                                      ,v62_dataini
                                      ,v62_datafim
                                      ,v62_situacao
                                      ,v62_obs
                                      ,v62_instit
                       )
                values (
                                $this->v62_sequencial
                               ,$this->v62_procjurtipo
                               ,'$this->v62_descricao'
                               ,".($this->v62_data == "null" || $this->v62_data == ""?"null":"'".$this->v62_data."'")."
                               ,'$this->v62_hora'
                               ,$this->v62_usuario
                               ,".($this->v62_dataini == "null" || $this->v62_dataini == ""?"null":"'".$this->v62_dataini."'")."
                               ,".($this->v62_datafim == "null" || $this->v62_datafim == ""?"null":"'".$this->v62_datafim."'")."
                               ,$this->v62_situacao
                               ,'$this->v62_obs'
                               ,$this->v62_instit
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Processo Juridico ($this->v62_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Processo Juridico já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Processo Juridico ($this->v62_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->v62_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->v62_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12665,'$this->v62_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,2212,12665,'','".AddSlashes(pg_result($resaco,0,'v62_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2212,12666,'','".AddSlashes(pg_result($resaco,0,'v62_procjurtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2212,12667,'','".AddSlashes(pg_result($resaco,0,'v62_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2212,12668,'','".AddSlashes(pg_result($resaco,0,'v62_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2212,12669,'','".AddSlashes(pg_result($resaco,0,'v62_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2212,12670,'','".AddSlashes(pg_result($resaco,0,'v62_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2212,12671,'','".AddSlashes(pg_result($resaco,0,'v62_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2212,12672,'','".AddSlashes(pg_result($resaco,0,'v62_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2212,12673,'','".AddSlashes(pg_result($resaco,0,'v62_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2212,12674,'','".AddSlashes(pg_result($resaco,0,'v62_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2212,1009352,'','".AddSlashes(pg_result($resaco,0,'v62_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($v62_sequencial=null) {
      $this->atualizacampos();
     $sql = " update procjur set ";
     $virgula = "";
     if(trim($this->v62_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v62_sequencial"])){
       $sql  .= $virgula." v62_sequencial = $this->v62_sequencial ";
       $virgula = ",";
       if(trim($this->v62_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "v62_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v62_procjurtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v62_procjurtipo"])){
       $sql  .= $virgula." v62_procjurtipo = $this->v62_procjurtipo ";
       $virgula = ",";
       if(trim($this->v62_procjurtipo) == null ){
         $this->erro_sql = " Campo Tipo Processo não informado.";
         $this->erro_campo = "v62_procjurtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v62_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v62_descricao"])){
       $sql  .= $virgula." v62_descricao = '$this->v62_descricao' ";
       $virgula = ",";
       if(trim($this->v62_descricao) == null ){
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "v62_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v62_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v62_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v62_data_dia"] !="") ){
       $sql  .= $virgula." v62_data = '$this->v62_data' ";
       $virgula = ",";
       if(trim($this->v62_data) == null ){
         $this->erro_sql = " Campo Data não informado.";
         $this->erro_campo = "v62_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["v62_data_dia"])){
         $sql  .= $virgula." v62_data = null ";
         $virgula = ",";
         if(trim($this->v62_data) == null ){
           $this->erro_sql = " Campo Data não informado.";
           $this->erro_campo = "v62_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v62_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v62_hora"])){
       $sql  .= $virgula." v62_hora = '$this->v62_hora' ";
       $virgula = ",";
       if(trim($this->v62_hora) == null ){
         $this->erro_sql = " Campo Hora não informado.";
         $this->erro_campo = "v62_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v62_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v62_usuario"])){
       $sql  .= $virgula." v62_usuario = $this->v62_usuario ";
       $virgula = ",";
       if(trim($this->v62_usuario) == null ){
         $this->erro_sql = " Campo Usuário não informado.";
         $this->erro_campo = "v62_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v62_dataini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v62_dataini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v62_dataini_dia"] !="") ){
       $sql  .= $virgula." v62_dataini = '$this->v62_dataini' ";
       $virgula = ",";
       if(trim($this->v62_dataini) == null ){
         $this->erro_sql = " Campo Data Inicial não informado.";
         $this->erro_campo = "v62_dataini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["v62_dataini_dia"])){
         $sql  .= $virgula." v62_dataini = null ";
         $virgula = ",";
         if(trim($this->v62_dataini) == null ){
           $this->erro_sql = " Campo Data Inicial não informado.";
           $this->erro_campo = "v62_dataini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v62_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v62_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v62_datafim_dia"] !="") ){
       $sql  .= $virgula." v62_datafim = '$this->v62_datafim' ";
       $virgula = ",";       
     } else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["v62_datafim_dia"])){
         $sql  .= $virgula." v62_datafim = null ";
         $virgula = ",";        
       }
     }
     if(trim($this->v62_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v62_situacao"])){
       $sql  .= $virgula." v62_situacao = $this->v62_situacao ";
       $virgula = ",";
       if(trim($this->v62_situacao) == null ){
         $this->erro_sql = " Campo Situação não informado.";
         $this->erro_campo = "v62_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v62_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v62_obs"])){
       $sql  .= $virgula." v62_obs = '$this->v62_obs' ";
       $virgula = ",";
       if(trim($this->v62_obs) == null ){
         $this->erro_sql = " Campo Observação não informado.";
         $this->erro_campo = "v62_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v62_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v62_instit"])){
       $sql  .= $virgula." v62_instit = $this->v62_instit ";
       $virgula = ",";
       if(trim($this->v62_instit) == null ){
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "v62_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v62_sequencial!=null){
       $sql .= " v62_sequencial = $this->v62_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->v62_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,12665,'$this->v62_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v62_sequencial"]) || $this->v62_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,2212,12665,'".AddSlashes(pg_result($resaco,$conresaco,'v62_sequencial'))."','$this->v62_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v62_procjurtipo"]) || $this->v62_procjurtipo != "")
             $resac = db_query("insert into db_acount values($acount,2212,12666,'".AddSlashes(pg_result($resaco,$conresaco,'v62_procjurtipo'))."','$this->v62_procjurtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v62_descricao"]) || $this->v62_descricao != "")
             $resac = db_query("insert into db_acount values($acount,2212,12667,'".AddSlashes(pg_result($resaco,$conresaco,'v62_descricao'))."','$this->v62_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v62_data"]) || $this->v62_data != "")
             $resac = db_query("insert into db_acount values($acount,2212,12668,'".AddSlashes(pg_result($resaco,$conresaco,'v62_data'))."','$this->v62_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v62_hora"]) || $this->v62_hora != "")
             $resac = db_query("insert into db_acount values($acount,2212,12669,'".AddSlashes(pg_result($resaco,$conresaco,'v62_hora'))."','$this->v62_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v62_usuario"]) || $this->v62_usuario != "")
             $resac = db_query("insert into db_acount values($acount,2212,12670,'".AddSlashes(pg_result($resaco,$conresaco,'v62_usuario'))."','$this->v62_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v62_dataini"]) || $this->v62_dataini != "")
             $resac = db_query("insert into db_acount values($acount,2212,12671,'".AddSlashes(pg_result($resaco,$conresaco,'v62_dataini'))."','$this->v62_dataini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v62_datafim"]) || $this->v62_datafim != "")
             $resac = db_query("insert into db_acount values($acount,2212,12672,'".AddSlashes(pg_result($resaco,$conresaco,'v62_datafim'))."','$this->v62_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v62_situacao"]) || $this->v62_situacao != "")
             $resac = db_query("insert into db_acount values($acount,2212,12673,'".AddSlashes(pg_result($resaco,$conresaco,'v62_situacao'))."','$this->v62_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v62_obs"]) || $this->v62_obs != "")
             $resac = db_query("insert into db_acount values($acount,2212,12674,'".AddSlashes(pg_result($resaco,$conresaco,'v62_obs'))."','$this->v62_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v62_instit"]) || $this->v62_instit != "")
             $resac = db_query("insert into db_acount values($acount,2212,1009352,'".AddSlashes(pg_result($resaco,$conresaco,'v62_instit'))."','$this->v62_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Processo Juridico não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v62_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Processo Juridico não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v62_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->v62_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($v62_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($v62_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,12665,'$v62_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,2212,12665,'','".AddSlashes(pg_result($resaco,$iresaco,'v62_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2212,12666,'','".AddSlashes(pg_result($resaco,$iresaco,'v62_procjurtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2212,12667,'','".AddSlashes(pg_result($resaco,$iresaco,'v62_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2212,12668,'','".AddSlashes(pg_result($resaco,$iresaco,'v62_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2212,12669,'','".AddSlashes(pg_result($resaco,$iresaco,'v62_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2212,12670,'','".AddSlashes(pg_result($resaco,$iresaco,'v62_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2212,12671,'','".AddSlashes(pg_result($resaco,$iresaco,'v62_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2212,12672,'','".AddSlashes(pg_result($resaco,$iresaco,'v62_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2212,12673,'','".AddSlashes(pg_result($resaco,$iresaco,'v62_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2212,12674,'','".AddSlashes(pg_result($resaco,$iresaco,'v62_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2212,1009352,'','".AddSlashes(pg_result($resaco,$iresaco,'v62_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from procjur
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($v62_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " v62_sequencial = $v62_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Processo Juridico não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v62_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Processo Juridico não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v62_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$v62_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:procjur";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($v62_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= " from procjur ";
     $sql .= "      inner join procjurtipo      on  procjurtipo.v66_sequencial    = procjur.v62_procjurtipo     ";
     $sql .= "      inner join procjurtiporegra   on  procjurtiporegra.v61_sequencial = procjurtipo.v66_procjurtiporegra  ";
     $sql .= "      inner join db_usuarios      on  db_usuarios.id_usuario      = procjur.v62_usuario         ";
     $sql .= "      left  join procjuradm         on  procjuradm.v64_procjur      = procjur.v62_sequencial      ";
     $sql .= "      left  join protprocesso       on  protprocesso.p58_codproc    = procjuradm.v64_protprocesso     ";
     $sql .= "      left  join procjurjudicial    on  procjurjudicial.v63_procjur   = procjur.v62_sequencial      ";
     $sql .= "      left  join localiza       on  localiza.v54_codlocal     = procjurjudicial.v63_localiza    ";
     $sql .= "      left  join vara         on  vara.v53_codvara        = procjurjudicial.v63_vara      ";
     $sql .= "      left  join procjurjudicialadvog on procjurjudicial.v63_sequencial = procjurjudicialadvog.v65_procjurjudicial ";
     $sql .= "      left  join advog        on  advog.v57_numcgm          = procjurjudicialadvog.v65_advog  ";

     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($v62_sequencial)) {
         $sql2 .= " where procjur.v62_sequencial = $v62_sequencial ";
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
   public function sql_query_file ($v62_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from procjur ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($v62_sequencial)){
         $sql2 .= " where procjur.v62_sequencial = $v62_sequencial ";
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


   function sql_query_susp ( $v62_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from procjur ";
     $sql .= "      inner join suspensao        on  suspensao.ar18_procjur      = procjur.v62_sequencial      ";
     $sql .= "      inner join procjurtipo      on  procjurtipo.v66_sequencial    = procjur.v62_procjurtipo     ";
     $sql .= "      inner join procjurtiporegra   on  procjurtiporegra.v61_sequencial = procjurtipo.v66_procjurtiporegra  ";
     $sql .= "      inner join db_usuarios      on  db_usuarios.id_usuario      = procjur.v62_usuario         ";
     $sql .= "      left  join procjuradm         on  procjuradm.v64_procjur      = procjur.v62_sequencial      ";
     $sql .= "      left  join protprocesso       on  protprocesso.p58_codproc    = procjuradm.v64_protprocesso     ";
     $sql .= "      left  join procjurjudicial    on  procjurjudicial.v63_procjur   = procjur.v62_sequencial      ";
     $sql .= "      left  join localiza       on  localiza.v54_codlocal     = procjurjudicial.v63_localiza    ";
     $sql .= "      left  join vara         on  vara.v53_codvara        = procjurjudicial.v63_vara      ";
     $sql .= "      left  join procjurjudicialadvog on procjurjudicial.v63_sequencial = procjurjudicialadvog.v65_procjurjudicial ";
     $sql .= "      left  join advog        on  advog.v57_numcgm          = procjurjudicialadvog.v65_advog  ";

     $sql2 = "";
     if($dbwhere==""){
       if($v62_sequencial!=null ){
         $sql2 .= " where procjur.v62_sequencial = $v62_sequencial ";
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