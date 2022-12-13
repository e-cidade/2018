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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhgeracaofolha
class cl_rhgeracaofolha {
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
   var $rh102_sequencial = 0;
   var $rh102_descricao = null;
   var $rh102_usuario = 0;
   var $rh102_dtproc_dia = null;
   var $rh102_dtproc_mes = null;
   var $rh102_dtproc_ano = null;
   var $rh102_dtproc = null;
   var $rh102_ativo = 'f';
   var $rh102_mesusu = 0;
   var $rh102_anousu = 0;
   var $rh102_instit = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 rh102_sequencial = int4 = Código Sequencial
                 rh102_descricao = varchar(100) = Descrição
                 rh102_usuario = int4 = Código Usuário
                 rh102_dtproc = date = Data Processo
                 rh102_ativo = bool = Ativo
                 rh102_mesusu = int4 = Mes
                 rh102_anousu = int4 = Ano
                 rh102_instit = int4 = Instituição
                 ";
   //funcao construtor da classe
   function cl_rhgeracaofolha() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhgeracaofolha");
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
       $this->rh102_sequencial = ($this->rh102_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh102_sequencial"]:$this->rh102_sequencial);
       $this->rh102_descricao = ($this->rh102_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh102_descricao"]:$this->rh102_descricao);
       $this->rh102_usuario = ($this->rh102_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["rh102_usuario"]:$this->rh102_usuario);
       if($this->rh102_dtproc == ""){
         $this->rh102_dtproc_dia = ($this->rh102_dtproc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh102_dtproc_dia"]:$this->rh102_dtproc_dia);
         $this->rh102_dtproc_mes = ($this->rh102_dtproc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh102_dtproc_mes"]:$this->rh102_dtproc_mes);
         $this->rh102_dtproc_ano = ($this->rh102_dtproc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh102_dtproc_ano"]:$this->rh102_dtproc_ano);
         if($this->rh102_dtproc_dia != ""){
            $this->rh102_dtproc = $this->rh102_dtproc_ano."-".$this->rh102_dtproc_mes."-".$this->rh102_dtproc_dia;
         }
       }
       $this->rh102_ativo = ($this->rh102_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh102_ativo"]:$this->rh102_ativo);
       $this->rh102_mesusu = ($this->rh102_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh102_mesusu"]:$this->rh102_mesusu);
       $this->rh102_anousu = ($this->rh102_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh102_anousu"]:$this->rh102_anousu);
       $this->rh102_instit = ($this->rh102_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh102_instit"]:$this->rh102_instit);
     }else{
       $this->rh102_sequencial = ($this->rh102_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh102_sequencial"]:$this->rh102_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh102_sequencial){
      $this->atualizacampos();
     if($this->rh102_descricao == null ){
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "rh102_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh102_usuario == null ){
       $this->erro_sql = " Campo Código Usuário não informado.";
       $this->erro_campo = "rh102_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh102_dtproc == null ){
       $this->erro_sql = " Campo Data Processo não informado.";
       $this->erro_campo = "rh102_dtproc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh102_ativo == null ){
       $this->erro_sql = " Campo Ativo não informado.";
       $this->erro_campo = "rh102_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh102_mesusu == null ){
       $this->erro_sql = " Campo Mes não informado.";
       $this->erro_campo = "rh102_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh102_anousu == null ){
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "rh102_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh102_instit == null ){
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "rh102_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh102_sequencial == "" || $rh102_sequencial == null ){
       $result = db_query("select nextval('rhgeracaofolha_rh102_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhgeracaofolha_rh102_sequencial_seq do campo: rh102_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->rh102_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from rhgeracaofolha_rh102_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh102_sequencial)){
         $this->erro_sql = " Campo rh102_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh102_sequencial = $rh102_sequencial;
       }
     }
     if(($this->rh102_sequencial == null) || ($this->rh102_sequencial == "") ){
       $this->erro_sql = " Campo rh102_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhgeracaofolha(
                                       rh102_sequencial
                                      ,rh102_descricao
                                      ,rh102_usuario
                                      ,rh102_dtproc
                                      ,rh102_ativo
                                      ,rh102_mesusu
                                      ,rh102_anousu
                                      ,rh102_instit
                       )
                values (
                                $this->rh102_sequencial
                               ,'$this->rh102_descricao'
                               ,$this->rh102_usuario
                               ,".($this->rh102_dtproc == "null" || $this->rh102_dtproc == ""?"null":"'".$this->rh102_dtproc."'")."
                               ,'$this->rh102_ativo'
                               ,$this->rh102_mesusu
                               ,$this->rh102_anousu
                               ,$this->rh102_instit
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhgeracaofolha ($this->rh102_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhgeracaofolha já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhgeracaofolha ($this->rh102_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh102_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh102_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18085,'$this->rh102_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3197,18085,'','".AddSlashes(pg_result($resaco,0,'rh102_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3197,18086,'','".AddSlashes(pg_result($resaco,0,'rh102_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3197,18087,'','".AddSlashes(pg_result($resaco,0,'rh102_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3197,18088,'','".AddSlashes(pg_result($resaco,0,'rh102_dtproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3197,18089,'','".AddSlashes(pg_result($resaco,0,'rh102_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3197,18117,'','".AddSlashes(pg_result($resaco,0,'rh102_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3197,18116,'','".AddSlashes(pg_result($resaco,0,'rh102_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3197,18090,'','".AddSlashes(pg_result($resaco,0,'rh102_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($rh102_sequencial=null) {
      $this->atualizacampos();
     $sql = " update rhgeracaofolha set ";
     $virgula = "";
     if(trim($this->rh102_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh102_sequencial"])){
       $sql  .= $virgula." rh102_sequencial = $this->rh102_sequencial ";
       $virgula = ",";
       if(trim($this->rh102_sequencial) == null ){
         $this->erro_sql = " Campo Código Sequencial não informado.";
         $this->erro_campo = "rh102_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh102_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh102_descricao"])){
       $sql  .= $virgula." rh102_descricao = '$this->rh102_descricao' ";
       $virgula = ",";
       if(trim($this->rh102_descricao) == null ){
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "rh102_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh102_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh102_usuario"])){
       $sql  .= $virgula." rh102_usuario = $this->rh102_usuario ";
       $virgula = ",";
       if(trim($this->rh102_usuario) == null ){
         $this->erro_sql = " Campo Código Usuário não informado.";
         $this->erro_campo = "rh102_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh102_dtproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh102_dtproc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh102_dtproc_dia"] !="") ){
       $sql  .= $virgula." rh102_dtproc = '$this->rh102_dtproc' ";
       $virgula = ",";
       if(trim($this->rh102_dtproc) == null ){
         $this->erro_sql = " Campo Data Processo não informado.";
         $this->erro_campo = "rh102_dtproc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh102_dtproc_dia"])){
         $sql  .= $virgula." rh102_dtproc = null ";
         $virgula = ",";
         if(trim($this->rh102_dtproc) == null ){
           $this->erro_sql = " Campo Data Processo não informado.";
           $this->erro_campo = "rh102_dtproc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh102_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh102_ativo"])){
       $sql  .= $virgula." rh102_ativo = '$this->rh102_ativo' ";
       $virgula = ",";
       if(trim($this->rh102_ativo) == null ){
         $this->erro_sql = " Campo Ativo não informado.";
         $this->erro_campo = "rh102_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh102_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh102_mesusu"])){
       $sql  .= $virgula." rh102_mesusu = $this->rh102_mesusu ";
       $virgula = ",";
       if(trim($this->rh102_mesusu) == null ){
         $this->erro_sql = " Campo Mes não informado.";
         $this->erro_campo = "rh102_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh102_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh102_anousu"])){
       $sql  .= $virgula." rh102_anousu = $this->rh102_anousu ";
       $virgula = ",";
       if(trim($this->rh102_anousu) == null ){
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "rh102_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh102_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh102_instit"])){
       $sql  .= $virgula." rh102_instit = $this->rh102_instit ";
       $virgula = ",";
       if(trim($this->rh102_instit) == null ){
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "rh102_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh102_sequencial!=null){
       $sql .= " rh102_sequencial = $this->rh102_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh102_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,18085,'$this->rh102_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh102_sequencial"]) || $this->rh102_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3197,18085,'".AddSlashes(pg_result($resaco,$conresaco,'rh102_sequencial'))."','$this->rh102_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh102_descricao"]) || $this->rh102_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3197,18086,'".AddSlashes(pg_result($resaco,$conresaco,'rh102_descricao'))."','$this->rh102_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh102_usuario"]) || $this->rh102_usuario != "")
             $resac = db_query("insert into db_acount values($acount,3197,18087,'".AddSlashes(pg_result($resaco,$conresaco,'rh102_usuario'))."','$this->rh102_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh102_dtproc"]) || $this->rh102_dtproc != "")
             $resac = db_query("insert into db_acount values($acount,3197,18088,'".AddSlashes(pg_result($resaco,$conresaco,'rh102_dtproc'))."','$this->rh102_dtproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh102_ativo"]) || $this->rh102_ativo != "")
             $resac = db_query("insert into db_acount values($acount,3197,18089,'".AddSlashes(pg_result($resaco,$conresaco,'rh102_ativo'))."','$this->rh102_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh102_mesusu"]) || $this->rh102_mesusu != "")
             $resac = db_query("insert into db_acount values($acount,3197,18117,'".AddSlashes(pg_result($resaco,$conresaco,'rh102_mesusu'))."','$this->rh102_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh102_anousu"]) || $this->rh102_anousu != "")
             $resac = db_query("insert into db_acount values($acount,3197,18116,'".AddSlashes(pg_result($resaco,$conresaco,'rh102_anousu'))."','$this->rh102_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh102_instit"]) || $this->rh102_instit != "")
             $resac = db_query("insert into db_acount values($acount,3197,18090,'".AddSlashes(pg_result($resaco,$conresaco,'rh102_instit'))."','$this->rh102_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhgeracaofolha nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh102_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhgeracaofolha nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh102_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh102_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($rh102_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($rh102_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,18085,'$rh102_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3197,18085,'','".AddSlashes(pg_result($resaco,$iresaco,'rh102_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3197,18086,'','".AddSlashes(pg_result($resaco,$iresaco,'rh102_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3197,18087,'','".AddSlashes(pg_result($resaco,$iresaco,'rh102_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3197,18088,'','".AddSlashes(pg_result($resaco,$iresaco,'rh102_dtproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3197,18089,'','".AddSlashes(pg_result($resaco,$iresaco,'rh102_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3197,18117,'','".AddSlashes(pg_result($resaco,$iresaco,'rh102_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3197,18116,'','".AddSlashes(pg_result($resaco,$iresaco,'rh102_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3197,18090,'','".AddSlashes(pg_result($resaco,$iresaco,'rh102_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhgeracaofolha
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh102_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh102_sequencial = $rh102_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhgeracaofolha nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh102_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhgeracaofolha nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh102_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh102_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhgeracaofolha";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $rh102_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from rhgeracaofolha ";
     $sql .= "      inner join db_config  on  db_config.codigo = rhgeracaofolha.rh102_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = rhgeracaofolha.rh102_usuario";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($rh102_sequencial!=null ){
         $sql2 .= " where rhgeracaofolha.rh102_sequencial = $rh102_sequencial ";
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
   function sql_query_file ( $rh102_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from rhgeracaofolha ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh102_sequencial!=null ){
         $sql2 .= " where rhgeracaofolha.rh102_sequencial = $rh102_sequencial ";
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
	 * Função para gerar sql de consulta a servidores para geração de folha de pagamento
	 *
   * @param Object  $oParam
	 * @param Array   $aFiltroSelecionados
   * @param Boolean $geraFormularioOculto
	 * @return String
	 */
	function sqlGeracaoFolha($oParam, $aFiltroSelecionados = null, $geraFormularioOculto= false){

	    if($aFiltroSelecionados == null and count($aFiltroSelecionados) == 0){
	      $sFiltroInclusao     = " ";
	    } else {
	      $sFiltroSelecionados = implode(",",$aFiltroSelecionados);
	      $sFiltroInclusao     = " and regist in ({$sFiltroSelecionados}) ";
	    }

	   // echo $sFiltroInclusao;exit;
	    $oDados    = $oParam->oDados;
	    $clfolha   = new cl_folha;
	    $clselecao = new cl_selecao;
	    $clgerfsal = new cl_gerfsal;
	    $clgerfadi = new cl_gerfadi;
	    $clgerfres = new cl_gerfres;
	    $clgerfs13 = new cl_gerfs13;
	    $clgerfcom = new cl_gerfcom;
	    $clgerffx  = new cl_gerffx;
	    $db_opcao  = 1;
	    $db_botao  = true;

	    $contaREG = "Nenhum registro encontrado";
	    $sqlerro = false;
	    $sqlsal = "";
	    $sqladi = "";
	    $sqlres = "";
	    $sql13o = "";
	    $sqlcom = "";
      $sqlffx = "";
      $sqlsupl = "";

	    $DBwhere = "";
	    $DBands = "";
	    $DBgerrs = "  ";
	    if(isset($oDados->folhaselecion) && trim($oDados->folhaselecion) != ""){
	      if(trim($oDados->anofolha) == ""){
	        $oDados->anofolha = db_anofolha();
	      }
	      if(trim($oDados->mesfolha) == ""){
	        $oDados->mesfolha = db_mesfolha();
	      }
	      $oDados->faixa_lotac = str_replace("\'","'",$oDados->faixa_lotac);
	      $oDados->faixa_orgao = str_replace("\'","'",$oDados->faixa_orgao);

        $DBwhere .= $DBands." [sigla]_anousu = ".$oDados->anofolha."
                          and [sigla]_mesusu = ".$oDados->mesfolha."
                          and [sigla]_instit = ".db_getsession("DB_instit")."
                          and [sigla]_pd    != 3";
     
	      $sGroupBy = "  group by [sigla]_regist,
	                              [sigla]_lotac,
	                              [sigla]_anousu,
	                              [sigla]_mesusu";

	      $sOrder = "";

	      $campos = "    [sigla]_regist                                              as regist,
	                     [sigla]_lotac                                               as lotac,
	                     sum(case when [sigla]_pd = 1 then [sigla]_valor else 0 end) as proven,
	                     sum(case when [sigla]_pd = 2 then [sigla]_valor else 0 end) as descon,
	                     [sigla]_anousu                                              as anousu,
	                     [sigla]_mesusu                                              as mesusu,
	                     '[label_tipo]'::varchar                                     as label_tipo_folha,
	                     '[tipo_folha]'::varchar                                     as tipo_folha
	                     ";

	      $aFolhaSelecionados = split(",",$oDados->folhaselecion);

	      foreach($aFolhaSelecionados as $iFolhaSelecionada){

          switch($iFolhaSelecionada){

	          case 0:
	            $DBwhere1  = str_replace("[sigla]","r14",$DBwhere);
	            $DBwhere1 .= str_replace("[sigla]","r14",$sGroupBy);
	            $campos1   = str_replace("[sigla]","r14",$campos);
	            $campos1   = str_replace("[label_tipo]","Salário",$campos1);
	            $campos1   = str_replace("[tipo_folha]","$iFolhaSelecionada",$campos1);
	            $sqlsal    = $clgerfsal->sql_query_file(null,null,null,null,$campos1,"",$DBwhere1);

              if ( DBPessoal::verificarUtilizacaoEstruturaSuplementar() ) {

                $oCompetencia         = new DBCompetencia($oDados->anofolha, $oDados->mesfolha);
                $clrhhistoricocalculo = new cl_rhhistoricocalculo();
                $aFolhaPagamento      = FolhaPagamento::getFolhaCompetenciaTipo( $oCompetencia, FolhaPagamento::TIPO_FOLHA_SALARIO);
                $sqlsal               = $clrhhistoricocalculo->sql_query_geracao($aFolhaPagamento[0]->getSequencial(), "Salário", $iFolhaSelecionada);
              }
	          break;
	          case 1:

	            $DBwhere2  = str_replace("[sigla]","r22",$DBwhere);
	            $DBwhere2 .= str_replace("[sigla]","r22",$sGroupBy);
	            $campos2 = str_replace("[sigla]","r22",$campos);
	            $campos2 = str_replace("[label_tipo]","Adiantamento",$campos2);
	            $campos2 = str_replace("[tipo_folha]","$iFolhaSelecionada",$campos2);
	            $sqladi  = $clgerfadi->sql_query_file(null,null,null,null,$campos2,"",$DBwhere2);
	          break;
	          case 3:

	            $DBgerrs = "";
	            $DBwhere4  = str_replace("[sigla]","r20",$DBwhere);
	            $DBwhere4 .= str_replace("[sigla]","r20",$sGroupBy);
	            $campos4 = str_replace("[sigla]","r20",$campos);
	            $campos4 = str_replace("[label_tipo]","Rescisão",$campos4);
	            $campos4 = str_replace("[tipo_folha]","$iFolhaSelecionada",$campos4);
	            $sqlres  = $clgerfres->sql_query_file(null,null,null,null,null,$campos4,"",$DBwhere4);
	          break;
	          case 4:

	            $DBwhere5  = str_replace("[sigla]","r35",$DBwhere);
	            $DBwhere5 .= str_replace("[sigla]","r35",$sGroupBy);
	            $campos5 = str_replace("[sigla]","r35",$campos);
	            $campos5 = str_replace("[label_tipo]","Saldo do 13°",$campos5);
	            $campos5 = str_replace("[tipo_folha]","$iFolhaSelecionada",$campos5);
	            $sql13o  = $clgerfs13->sql_query_file(null,null,null,null,$campos5,"",$DBwhere5);
	          break;
	          case 5;

	            $DBwhere6  = str_replace("[sigla]","r48",$DBwhere);
	            if(isset($oDados->complementares) && $oDados->complementares != 0){
	            	$DBwhere6.= " and r48_semest = ".$oDados->complementares;
	            }
	            $DBwhere6 .= str_replace("[sigla]","r48",$sGroupBy);
	            $campos6 = str_replace("[sigla]","r48",$campos);
	            $campos6 = str_replace("[label_tipo]","Complementar",$campos6);
	            $campos6 = str_replace("[tipo_folha]","$iFolhaSelecionada",$campos6);
	            $sqlcom  = $clgerfcom->sql_query_file(null,null,null,null,$campos6,"",$DBwhere6);
              
              if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()){

                $oCompetencia         = new DBCompetencia($oDados->anofolha, $oDados->mesfolha);
                $clrhhistoricocalculo = new cl_rhhistoricocalculo();
                $aFolhaPagamento      = FolhaPagamento::getFolhaCompetenciaTipo( $oCompetencia, FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR, $oDados->ListaFolhas );
                $sqlcom               = $clrhhistoricocalculo->sql_query_geracao( $aFolhaPagamento[0]->getSequencial(), "Complementar", $iFolhaSelecionada);
              }
	          break;
	          case 6:

	            $DBwhere7  = str_replace("[sigla]","r53",$DBwhere);
	            $DBwhere7 .= str_replace("[sigla]","r53",$sGroupBy);
	            $campos7 = str_replace("[sigla]","r53",$campos);
	            $campos7 = str_replace("[label_tipo]","Fixo",$campos7);
	            $campos7 = str_replace("[tipo_folha]","$iFolhaSelecionada",$campos7);
	            $sqlffx  = $clgerffx->sql_query_file(null,null,null,null,$campos7,"",$DBwhere7);


              if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()){

                $oCompetencia         = new DBCompetencia($oDados->anofolha, $oDados->mesfolha);
                $clrhhistoricocalculo = new cl_rhhistoricocalculo();
                $aFolhaPagamento      = FolhaPagamento::getFolhaCompetenciaTipo( $oCompetencia, FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR, $oDados->ListaFolhas);
                $sqlsupl              = $clrhhistoricocalculo->sql_query_geracao($aFolhaPagamento[0]->getSequencial(), "Suplementar", $iFolhaSelecionada);
              }

	          break;
	        }
	      }

	      $valorunion = "";
	      $sSqlUnion = "";

	      if($sqlsal != ""){

	        $sSqlUnion.= $valorunion.$sqlsal;
	        $valorunion = " union all ";
	      }
	      if($sqladi != ""){

	        $sSqlUnion.= $valorunion.$sqladi;
	        $valorunion = " union all ";
	      }
	      if($sqlres != ""){

	        $sSqlUnion.= $valorunion.$sqlres;
	        $valorunion = " union all ";
	      }
	      if($sql13o != ""){

	        $sSqlUnion.= $valorunion.$sql13o;
	        $valorunion = " union all ";
	      }
	      if($sqlcom != ""){

	        $sSqlUnion.= $valorunion.$sqlcom;
	        $valorunion = " union all ";
	      }
	      if($sqlffx = ""){

	        $sSqlUnion.= $valorunion.$sqlfx;
	        $valorunion = " union all ";
	      }

        if($sqlsupl != ""){

          $sSqlUnion.= $valorunion.$sqlsupl;
          $valorunion = " union all ";
        }
        
	      if (trim($oDados->selecao) != "") {

	        $result_selecao = $clselecao->sql_record($clselecao->sql_query_file($oDados->selecao,db_getsession('DB_instit'),"r44_where"));
	        if($clselecao->numrows > 0){
	          $DBwhere = " where 1=1 and ".db_utils::fieldsMemory($result_selecao, 0)->r44_where;
	        }
	      }else{
	        $DBwhere = " where 1=1 ";
	      }

	      if(isset($oDados->lotaci) && trim($oDados->lotaci) != "" && isset($oDados->lotacf) && trim($oDados->lotacf) != ""){
	        // Se for por intervalos e vier lotação inicial e final
	        $DBwhere .= " and r70_estrut between '{$oDados->lotaci}' and '{$oDados->lotacf}' ";
	      }else if(isset($oDados->lotaci) && trim($oDados->lotaci) != ""){
	        // Se for por intervalos e vier somente lotação inicial
	        $DBwhere .= " and r70_estrut >= '{$oDados->lotaci}' ";
	      }else if(isset($oDados->lotacf) && trim($oDados->lotacf) != ""){
	        // Se for por intervalos e vier somente lotação final
	        $DBwhere .= " and r70_estrut <= '{$oDados->lotacf}' ";
	      }else if(isset($oDados->faixa_lotac) && $oDados->faixa_lotac != ''){
	        $DBwhere.= " and r70_estrut in ({$oDados->faixa_lotac}) ";
	      }

	      if(isset($oDados->orgaoi) && trim($oDados->orgaoi) != "" && isset($oDados->orgaof) && trim($oDados->orgaof) != ""){

	        // Se for por intervalos e vier órgão inicial e final
	        $DBwhere .= " and o40_orgao between {$oDados->orgaoi} and {$oDados->orgaof}";
	      }else if(isset($oDados->orgaoi) && trim($oDados->orgaoi) != ""){
	        // Se for por intervalos e vier somente órgão inicial
	        $DBwhere .= " and o40_orgao >= {$oDados->orgaoi}";
	      }else if(isset($oDados->orgaof) && trim($oDados->orgaof) != ""){
	        // Se for por intervalos e vier somente órgão final
	        $DBwhere .= " and o40_orgao <= {$oDados->orgaof}";
	      }else if(isset($oDados->faixa_orgao) && trim($oDados->faixa_orgao) != ""){
	        // Se for por selecionados
	        $DBwhere .= " and o40_orgao in ({$oDados->faixa_orgao}) ";
	      }

	      if(isset($oDados->registini) && trim($oDados->registini) != "" && isset($oDados->registfim) && trim($oDados->registfim) != ""){

	        // Se for por intervalos e vier órgão inicial e final
	        $DBwhere .= " and rh02_regist between {$oDados->registini} and {$oDados->registfim}";
	      }else if(isset($oDados->registini) && trim($oDados->registini) != "") {
	        // Se for por intervalos e vier somente órgão inicial
	        $DBwhere .= " and rh02_regist >= {$oDados->registini}";
	      }else if(isset($oDados->registfim) && trim($oDados->registfim) != "") {
	        // Se for por intervalos e vier somente órgão final
	        $DBwhere .= " and rh02_regist <= {$oDados->registfim}";
	      } else if (isset($oDados->faixa_matricula) && $oDados->faixa_matricula != "") {
	        $DBwhere .= " and rh02_regist in ({$oDados->faixa_matricula}) ";
	      }

	      if (isset($oDados->pagtosaldo) && $oDados->pagtosaldo == "t") {

	        $oDados->pagarliq = 999999999.99;
	        $oDados->pagarperc= 100;
	        $liquidar = " round( (
	                               ( sum(proven) - sum(descon) ) -
	                               ( ( (sum(proven) - sum(descon) ) / 100 ) * {$oDados->percpago} )
	                             ),2
	                           )  ";
	      } else if(trim($oDados->pagarliq) == "") {

	        $liquidar = " (sum(proven) - sum(descon)) ";
	      } else {
	        $liquidar = " (case
	                         when (sum(proven) - sum(descon)) > {$oDados->pagarliq}
	                           then {$oDados->pagarliq}
	                         else
	                           (sum(proven) - sum(descon))
	                       end ) ";
	      }

	      if($oDados->pagarperc == 0 || trim($oDados->pagarperc) == ""){
	        $sCase   = " round(({$liquidar}),2) as liquido, ";
	        $sHaving = " round(({$liquidar}),2) ";
	      }else{
	        $oDados->pagarperc = ($oDados->pagarperc / 100);
	        $oDados->percpago  = ($oDados->percpago / 100);
	        if(isset($oDados->pagtosaldo) && $oDados->pagtosaldo == "t"){
	          $sCase   = " round({$liquidar},2) as liquido, ";
	          $sHaving = " round({$liquidar},2) ";
	        }else{
	          $sCase   = " round(({$liquidar} * ({$oDados->pagarperc})),2) as liquido, ";
	          $sHaving = " round(({$liquidar} * ({$oDados->pagarperc})),2) ";
	        }
	      }

	      $sControleHaving  = " having {$sHaving} > 0 ";
	      $sControleHaving .= "    and round((sum(proven) - sum(descon)),2)::float8 between {$oDados->liquido1}::float8 and {$oDados->liquido2}::float8";
        if ( $geraFormularioOculto == true) {
         $sControleHaving = " and ( having {$sHaving} != 0 and  {$sHaving} > sum(rh104_vlrliquido) )";
        }

       $sSql ="select                                                                                                 \n";
	     $sSql.="       regist,                                                                                         \n";
	     $sSql.="       z01_nome,                                                                                       \n";
	     $sSql.="       rh02_seqpes,                                                                                    \n";
	     $sSql.="       z01_numcgm,                                                                                     \n";
	     $sSql.="       rh30_regime as rh02_codreg,                                                                     \n";
	     $sSql.="       lotac,                                                                                          \n";
	     $sSql.="       rh30_vinculo,                                                                                   \n";
	     $sSql.="       rh03_padrao,                                                                                    \n";
	     $sSql.="       substr(                                                                                         \n";
	     $sSql.="              db_fxxx(regist,                                                                          \n";
	     $sSql.="                      {$oDados->anofolha},                                                             \n";
	     $sSql.="                      {$oDados->mesfolha},                                                             \n";
	     $sSql.="                      ".db_getsession('DB_instit')."                                                   \n";
	     $sSql.="                     )                                                                                 \n";
	     $sSql.="              ,111                                                                                     \n";
	     $sSql.="              ,11                                                                                      \n";
	     $sSql.="             ) as f010,                                                                                \n";
	     $sSql.="       rh37_descr,                                                                                     \n";
	     $sSql.="       1 as r38_situac,                                                                                \n";
	     $sSql.="       rh02_tbprev,                                                                                    \n";
	     $sSql.="       {$sCase}                                                                                        \n";
	     $sSql.="       round(sum(proven),2) as proven,                                                                 \n";
	     $sSql.="       round(sum(descon),2) as descon,                                                                 \n";
	     $sSql.="       rh44_codban,                                                                                    \n";
	     $sSql.="       lpad(                                                                                           \n";
	     $sSql.="            trim(                                                                                      \n";
	     $sSql.="                 to_char(                                                                              \n";
	     $sSql.="                         to_number(rh44_agencia,'9999'),                                               \n";
	     $sSql.="                         '9999'                                                                        \n";
	     $sSql.="                        )                                                                              \n";
	     $sSql.="                )::varchar(4) || rh44_dvagencia,                                                       \n";
	     $sSql.="            5,                                                                                         \n";
	     $sSql.="           '0'                                                                                         \n";
	     $sSql.="           ) as rh44_agencia,                                                                          \n";
	     $sSql.="       rh44_conta||rh44_dvconta as rh44_conta,                                                         \n";
	     $sSql.="       rh02_fpagto,                                                                                    \n";
	     $sSql.="       r70_estrut,                                                                                     \n";
	     $sSql.="       r70_descr,                                                                                      \n";
	     $sSql.="       label_tipo_folha,                                                                               \n";
	     $sSql.="       tipo_folha,                                                                                     \n";
	     $sSql.="       ( select round(coalesce(sum(rh104_vlrliquido),0),2)                                             \n";
       $sSql.="           from rhgeracaofolhareg                                                                      \n";
       $sSql.="                inner join rhgeracaofolha     on rh102_sequencial = rh104_rhgeracaofolha               \n";
       $sSql.="                inner join rhgeracaofolhatipo on rh104_sequencial = rh103_rhgeracaofolhareg            \n";
       $sSql.="          where rh102_ativo is TRUE                                                                    \n";
       $sSql.="            and rh104_seqpes    = rhpessoalmov.rh02_seqpes                                             \n";
       $sSql.="            and rh103_tipofolha = tipo_folha::integer                                                  \n";
       $sSql.="       ) as valor_recebido,                                                                            \n";
       $sSql.="       regist ||'_'|| tipo_folha as regist_tipofolha                                                   \n";
       $sSql.="                                                                                                       \n";
	     $sSql.="  from ($sSqlUnion) x                                                                                  \n";
       $sSql.=" inner join rhpessoalmov    on rhpessoalmov.rh02_anousu   = x.anousu                                   \n";
	     $sSql.="                           and rhpessoalmov.rh02_mesusu   = x.mesusu                                   \n";
	     $sSql.="                           and rhpessoalmov.rh02_instit   = ".db_getsession("DB_instit")."             \n";
	     $sSql.="                           and rhpessoalmov.rh02_regist   = x.regist                                   \n";
	     $sSql.="                           and rhpessoalmov.rh02_instit   = ".db_getsession("DB_instit")."             \n";
       $sSql.=" inner join rhpessoal       on rhpessoal.rh01_regist      = rhpessoalmov.rh02_regist                   \n";
	     $sSql.=" left  join rhpesbanco      on rhpesbanco.rh44_seqpes     = rhpessoalmov.rh02_seqpes                   \n";
	     $sSql.=" inner join cgm             on cgm.z01_numcgm             = rhpessoal.rh01_numcgm                      \n";
	     $sSql.=" left  join rhfuncao        on rhfuncao.rh37_funcao       = rhpessoal.rh01_funcao                      \n";
	     $sSql.="                           and rhfuncao.rh37_instit       = ".db_getsession("DB_instit")."             \n";
	     $sSql.=" inner join rhlota          on rhlota.r70_codigo          = rhpessoalmov.rh02_lota                     \n";
	     $sSql.="                           and rhlota.r70_instit          = rhpessoalmov.rh02_instit                   \n";
	     $sSql.=" left  join rhlotaexe       on rhlotaexe.rh26_anousu      = rhpessoalmov.rh02_anousu                   \n";
	     $sSql.="                           and rhlotaexe.rh26_codigo      = rhlota.r70_codigo                          \n";
	     $sSql.=" left  join orcunidade      on orcunidade.o41_anousu      = rhlotaexe.rh26_anousu                      \n";
	     $sSql.="                           and orcunidade.o41_orgao       = rhlotaexe.rh26_orgao                       \n";
	     $sSql.="                           and orcunidade.o41_unidade     = rhlotaexe.rh26_unidade                     \n";
	     $sSql.=" left  join orcorgao        on orcorgao.o40_anousu        = orcunidade.o41_anousu                      \n";
	     $sSql.="                           and orcorgao.o40_orgao         = orcunidade.o41_orgao                       \n";
	     $sSql.=" left  join rhregime        on rhregime.rh30_codreg       = rhpessoalmov.rh02_codreg                   \n";
	     $sSql.="                           and rhregime.rh30_instit       = rhpessoalmov.rh02_instit                   \n";
	     $sSql.=" left  join rhpesrescisao   on rhpesrescisao.rh05_seqpes  = rhpessoalmov.rh02_seqpes                   \n";
	     $sSql.=" left  join rhpespadrao     on rhpespadrao.rh03_seqpes    = rhpessoalmov.rh02_seqpes                   \n";
	     $sSql.="                           and rhpespadrao.rh03_anousu    = rhpessoalmov.rh02_anousu                   \n";
	     $sSql.="                           and rhpespadrao.rh03_mesusu    = rhpessoalmov.rh02_mesusu                   \n";
	     $sSql.=" left  join padroes         on padroes.r02_anousu         = rhpespadrao.rh03_anousu                    \n";
	     $sSql.="                           and padroes.r02_mesusu         = rhpespadrao.rh03_mesusu                    \n";
	     $sSql.="                          and padroes.r02_regime         = rhpespadrao.rh03_regime                     \n";
	     $sSql.="                           and padroes.r02_codigo         = rhpespadrao.rh03_padrao                    \n";
	     $sSql.="                           and padroes.r02_instit         = ".db_getsession("DB_instit")."             \n";
	     $sSql.=" ".strtolower($DBwhere)."                                                                              \n";
	     $sSql.="   and regist not in( select rh101_regist                                                              \n";
	     $sSql.="                        from rhsuspensaopag                                                            \n";
	     $sSql.="                       where rh101_regist = x.regist                                                   \n";
	     $sSql.="                         and rh101_dtdesativacao is null                                               \n";
	     $sSql.="                    )                                                                                  \n";
	     $sSql.="  {$sFiltroInclusao}                                                                                   \n";
	     $sSql.="                                                                                                       \n";
	     $sSql.=" group by regist,                                                                                      \n";
	     $sSql.="          lotac,                                                                                       \n";
	     $sSql.="          z01_nome,                                                                                    \n";
	     $sSql.="          z01_numcgm,                                                                                  \n";
	     $sSql.="          rh02_seqpes,                                                                                 \n";
	     $sSql.="          r70_estrut,                                                                                  \n";
	     $sSql.="          rh30_regime,                                                                                 \n";
	     $sSql.="          r70_descr,                                                                                   \n";
	     $sSql.="          rh30_vinculo,                                                                                \n";
	     $sSql.="          rh03_padrao,                                                                                 \n";
	     $sSql.="          rh37_descr,                                                                                  \n";
	     $sSql.="          rh02_tbprev,                                                                                 \n";
	     $sSql.="          rh44_codban,                                                                                 \n";
	     $sSql.="          trim(to_char(to_number(rh44_agencia,'9999'),'9999'))::varchar(4)||rh44_dvagencia,            \n";
	     $sSql.="          rh44_conta||rh44_dvconta,                                                                    \n";
	     $sSql.="          rh02_fpagto,                                                                                 \n";
	     $sSql.="          rh05_seqpes,                                                                                 \n";
	     $sSql.="          label_tipo_folha,                                                                            \n";
	     $sSql.="          tipo_folha                                                                                   \n";
	     $sSql.="                                                                                                       \n";
	     $sSql.=" {$sControleHaving}                                                                                    \n";
	     $sSql.="                                                                                                       \n";
	     $sSql.=" order by tipo_folha,regist                                                                            \n";

      }

	    return $sSql;
	}

	function sqlGeracaoFolhaArquivoBancario($iCodigoGeracao, $sCampos = "*", $sWhere = null, $sOrdem = null, $lSomenteServidoresOpcaoContaCorrente = true) {

		$sSql  = "select {$sCampos}                                                                                               ";
		$sSql .= "  from rhgeracaofolha                                                                                           ";
		$sSql .= "       inner join rhgeracaofolhareg on rhgeracaofolhareg.rh104_rhgeracaofolha = rhgeracaofolha.rh102_sequencial ";
    $sSql .= "       inner join rhpessoalmov      on rhpessoalmov.rh02_seqpes               = rhgeracaofolhareg.rh104_seqpes  ";
    $sSql .= "                                   and rhpessoalmov.rh02_anousu               = rhgeracaofolha.rh102_anousu     ";
    $sSql .= "                                   and rhpessoalmov.rh02_mesusu               = rhgeracaofolha.rh102_mesusu     ";
    $sSql .= "                                   and rhpessoalmov.rh02_instit               = rhgeracaofolha.rh102_instit     ";
    $sSql .= "       inner join rhpessoal         on rhpessoal.rh01_regist                  = rhpessoalmov.rh02_regist        ";
    $sSql .= "       inner join cgm               on cgm.z01_numcgm                         = rhpessoal.rh01_numcgm           ";
    $sSql .= "       inner join rhlota            on rhlota.r70_codigo                      = rhpessoalmov.rh02_lota          ";
    $sSql .= "                                   and rhlota.r70_instit                      = rhpessoalmov.rh02_instit        ";
    $sSql .= "       inner join rhregime          on rhregime.rh30_codreg                   = rhpessoalmov.rh02_codreg        ";
    $sSql .= "       inner join rhfuncao          on rhfuncao.rh37_funcao                   = rhpessoal.rh01_funcao           ";
    $sSql .= "                                   and rhfuncao.rh37_instit                   = rhpessoalmov.rh02_instit        ";
    $sSql .= "       inner join rhpesbanco        on rhpesbanco.rh44_seqpes                 = rhpessoalmov.rh02_seqpes        ";
    $sSql .= "       left join rhpespadrao        on rhpespadrao.rh03_seqpes                = rhpessoalmov.rh02_seqpes        ";
    $sSql .= " where rhgeracaofolha.rh102_instit = ".db_getsession("DB_instit");

    if( $lSomenteServidoresOpcaoContaCorrente == true ){

      $sSql .= " and rh02_fpagto = 3 ";
    }

    if ( !empty($iCodigoGeracao) ) {
      $sSql .= " and rh102_sequencial = $iCodigoGeracao " ;
    }

    if ( !empty($sWhere) ) {
      $sSql .= " {$sWhere} ";
    }

    if (!empty($sOrdem) ) {
      $sSql .= " order by {$sOrdem}";
    }

    return $sSql;
	}
  
  /**
   * Retorna uma query com os seguintes relacionamentos:
   * ==> rhgeracaofolha
   * ==> rhgeracaofolhareg
   * ==> rhpessoalmov
   * ==> rhgeracaofolhatipo
   * 
   * @access public
   * @param Integer $rh102_sequencial
   * @param String $sCampos
   * @param String $sOrdem
   * @param String $sWhere
   * @return String
   */
  public function sql_query_rhgeracaofolha ($rh102_sequencial = null, $sCampos = "*", $sOrdem = null, $sWhere = ""){
    
    $sSql  = "select {$sCampos}";
    $sSql .= " from rhgeracaofolha ";
    $sSql .= "      inner join rhgeracaofolhareg  on rhgeracaofolhareg.rh104_rhgeracaofolha = rhgeracaofolha.rh102_sequencial ";
    $sSql .= "      inner join rhpessoalmov       on rhpessoalmov.rh02_seqpes = rhgeracaofolhareg.rh104_seqpes and  rhpessoalmov.rh02_instit = rhgeracaofolhareg.rh104_instit";
    $sSql .= "      inner join rhgeracaofolhatipo on rhgeracaofolhatipo.rh103_rhgeracaofolhareg = rhgeracaofolhareg.rh104_sequencial ";
    
    $sSql2 = "";
    
    if (empty($sWhere)) {
       if (!empty($rh102_sequencial)){
         $sSql2 .= " where rhgeracaofolha.rh102_sequencial = $rh102_sequencial "; 
       } 
     } else if (!empty($sWhere)) {
       $sSql2 = " where $sWhere";
     }
     $sSql .= $sSql2;
     if (!empty($sOrdem)) {
       $sSql .= " order by {$sOrdem}";
     }
     return $sSql;
  }
  
}

