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

//MODULO: juridico
//CLASSE DA ENTIDADE processoforo
class cl_processoforo {
   // cria variaveis de erro
   var $rotulo          = null;
   var $query_sql       = null;
   var $numrows         = 0;
   var $numrows_incluir = 0;
   var $numrows_alterar = 0;
   var $numrows_excluir = 0;
   var $erro_status     = null;
   var $erro_sql        = null;
   var $erro_banco      = null;
   var $erro_msg        = null;
   var $erro_campo      = null;
   var $pagina_retorno  = null;
   // cria variaveis do arquivo
   var $v70_sequencial  = 0;
   var $v70_codforo     = null;
   var $v70_processoforomov = 0;
   var $v70_id_usuario  = 0;
   var $v70_vara        = 0;
   var $v70_data_dia    = null;
   var $v70_data_mes    = null;
   var $v70_data_ano = null;
   var $v70_data = null;
   var $v70_valorinicial = 0;
   var $v70_observacao = null;
   var $v70_anulado = 'f';
   var $v70_instit = 0;
   var $v70_cartorio = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 v70_sequencial = int4 = Processo do Sistema
                 v70_codforo = varchar(30) = Código Processo Foro
                 v70_processoforomov = int4 = Processo foro movimentação
                 v70_id_usuario = int4 = Id usuário
                 v70_vara = int4 = Vara
                 v70_data = date = Data
                 v70_valorinicial = float4 = Valor Inicial da Ação
                 v70_observacao = text = Observação
                 v70_anulado = bool = Anulado
                 v70_instit = int4 = Instituição
                 v70_cartorio = int4 = Cartório
                 ";
   //funcao construtor da classe
   function cl_processoforo() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("processoforo");
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
       $this->v70_sequencial = ($this->v70_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v70_sequencial"]:$this->v70_sequencial);
       $this->v70_codforo = ($this->v70_codforo == ""?@$GLOBALS["HTTP_POST_VARS"]["v70_codforo"]:$this->v70_codforo);
       $this->v70_processoforomov = ($this->v70_processoforomov == ""?@$GLOBALS["HTTP_POST_VARS"]["v70_processoforomov"]:$this->v70_processoforomov);
       $this->v70_id_usuario = ($this->v70_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["v70_id_usuario"]:$this->v70_id_usuario);
       $this->v70_vara = ($this->v70_vara == ""?@$GLOBALS["HTTP_POST_VARS"]["v70_vara"]:$this->v70_vara);
       if($this->v70_data == ""){
         $this->v70_data_dia = ($this->v70_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v70_data_dia"]:$this->v70_data_dia);
         $this->v70_data_mes = ($this->v70_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v70_data_mes"]:$this->v70_data_mes);
         $this->v70_data_ano = ($this->v70_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v70_data_ano"]:$this->v70_data_ano);
         if($this->v70_data_dia != ""){
            $this->v70_data = $this->v70_data_ano."-".$this->v70_data_mes."-".$this->v70_data_dia;
         }
       }
       $this->v70_valorinicial = ($this->v70_valorinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["v70_valorinicial"]:$this->v70_valorinicial);
       $this->v70_observacao = ($this->v70_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["v70_observacao"]:$this->v70_observacao);
       $this->v70_anulado = ($this->v70_anulado == "f"?@$GLOBALS["HTTP_POST_VARS"]["v70_anulado"]:$this->v70_anulado);
       $this->v70_instit = ($this->v70_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["v70_instit"]:$this->v70_instit);
       $this->v70_cartorio = ($this->v70_cartorio == ""?@$GLOBALS["HTTP_POST_VARS"]["v70_cartorio"]:$this->v70_cartorio);
     }else{
       $this->v70_sequencial = ($this->v70_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v70_sequencial"]:$this->v70_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($v70_sequencial){
      $this->atualizacampos();
     if($this->v70_codforo == null ){
       $this->erro_sql = " Campo Código Processo Foro nao Informado.";
       $this->erro_campo = "v70_codforo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v70_processoforomov == null ){
       $this->v70_processoforomov = "null";
     }
     if($this->v70_id_usuario == null ){
       $this->erro_sql = " Campo Id usuário nao Informado.";
       $this->erro_campo = "v70_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v70_vara == null ){
       $this->erro_sql = " Campo Vara nao Informado.";
       $this->erro_campo = "v70_vara";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v70_data == null ){
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "v70_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v70_valorinicial == null ){
       $this->v70_valorinicial = "null";
     }
     if($this->v70_anulado == null ){
       $this->erro_sql = " Campo Anulado nao Informado.";
       $this->erro_campo = "v70_anulado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v70_instit == null ){
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "v70_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v70_cartorio == null ){
       $this->erro_sql = " Campo Cartório nao Informado.";
       $this->erro_campo = "v70_cartorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v70_sequencial == "" || $v70_sequencial == null ){
       $result = db_query("select nextval('processoforo_v70_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: processoforo_v70_sequencial_seq do campo: v70_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->v70_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from processoforo_v70_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v70_sequencial)){
         $this->erro_sql = " Campo v70_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v70_sequencial = $v70_sequencial;
       }
     }
     if(($this->v70_sequencial == null) || ($this->v70_sequencial == "") ){
       $this->erro_sql = " Campo v70_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into processoforo(
                                       v70_sequencial
                                      ,v70_codforo
                                      ,v70_processoforomov
                                      ,v70_id_usuario
                                      ,v70_vara
                                      ,v70_data
                                      ,v70_valorinicial
                                      ,v70_observacao
                                      ,v70_anulado
                                      ,v70_instit
                                      ,v70_cartorio
                       )
                values (
                                $this->v70_sequencial
                               ,'$this->v70_codforo'
                               ,$this->v70_processoforomov
                               ,$this->v70_id_usuario
                               ,$this->v70_vara
                               ,".($this->v70_data == "null" || $this->v70_data == ""?"null":"'".$this->v70_data."'")."
                               ,$this->v70_valorinicial
                               ,'$this->v70_observacao'
                               ,'$this->v70_anulado'
                               ,$this->v70_instit
                               ,$this->v70_cartorio
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "processoforo ($this->v70_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "processoforo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "processoforo ($this->v70_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v70_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v70_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17343,'$this->v70_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3069,17343,'','".AddSlashes(pg_result($resaco,0,'v70_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3069,17367,'','".AddSlashes(pg_result($resaco,0,'v70_codforo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3069,17344,'','".AddSlashes(pg_result($resaco,0,'v70_processoforomov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3069,17345,'','".AddSlashes(pg_result($resaco,0,'v70_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3069,17346,'','".AddSlashes(pg_result($resaco,0,'v70_vara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3069,17347,'','".AddSlashes(pg_result($resaco,0,'v70_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3069,17436,'','".AddSlashes(pg_result($resaco,0,'v70_valorinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3069,17437,'','".AddSlashes(pg_result($resaco,0,'v70_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3069,17348,'','".AddSlashes(pg_result($resaco,0,'v70_anulado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3069,17812,'','".AddSlashes(pg_result($resaco,0,'v70_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3069,18155,'','".AddSlashes(pg_result($resaco,0,'v70_cartorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($v70_sequencial=null) {
      $this->atualizacampos();
     $sql = " update processoforo set ";
     $virgula = "";
     if(trim($this->v70_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v70_sequencial"])){
       $sql  .= $virgula." v70_sequencial = $this->v70_sequencial ";
       $virgula = ",";
       if(trim($this->v70_sequencial) == null ){
         $this->erro_sql = " Campo Processo do Sistema nao Informado.";
         $this->erro_campo = "v70_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v70_codforo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v70_codforo"])){
       $sql  .= $virgula." v70_codforo = '$this->v70_codforo' ";
       $virgula = ",";
       if(trim($this->v70_codforo) == null ){
         $this->erro_sql = " Campo Código Processo Foro nao Informado.";
         $this->erro_campo = "v70_codforo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v70_processoforomov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v70_processoforomov"])){
        if(trim($this->v70_processoforomov)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v70_processoforomov"])){
           $this->v70_processoforomov = "null" ;
        }
       $sql  .= $virgula." v70_processoforomov = $this->v70_processoforomov ";
       $virgula = ",";
     }
     if(trim($this->v70_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v70_id_usuario"])){
       $sql  .= $virgula." v70_id_usuario = $this->v70_id_usuario ";
       $virgula = ",";
       if(trim($this->v70_id_usuario) == null ){
         $this->erro_sql = " Campo Id usuário nao Informado.";
         $this->erro_campo = "v70_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v70_vara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v70_vara"])){
       $sql  .= $virgula." v70_vara = $this->v70_vara ";
       $virgula = ",";
       if(trim($this->v70_vara) == null ){
         $this->erro_sql = " Campo Vara nao Informado.";
         $this->erro_campo = "v70_vara";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v70_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v70_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v70_data_dia"] !="") ){
       $sql  .= $virgula." v70_data = '$this->v70_data' ";
       $virgula = ",";
       if(trim($this->v70_data) == null ){
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "v70_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["v70_data_dia"])){
         $sql  .= $virgula." v70_data = null ";
         $virgula = ",";
         if(trim($this->v70_data) == null ){
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "v70_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v70_valorinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v70_valorinicial"])){
        if(trim($this->v70_valorinicial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v70_valorinicial"])){
           $this->v70_valorinicial = "0" ;
        }
       $sql  .= $virgula." v70_valorinicial = $this->v70_valorinicial ";
       $virgula = ",";
     }
     if(trim($this->v70_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v70_observacao"])){
       $sql  .= $virgula." v70_observacao = '$this->v70_observacao' ";
       $virgula = ",";
     }
     if(trim($this->v70_anulado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v70_anulado"])){
       $sql  .= $virgula." v70_anulado = '$this->v70_anulado' ";
       $virgula = ",";
       if(trim($this->v70_anulado) == null ){
         $this->erro_sql = " Campo Anulado nao Informado.";
         $this->erro_campo = "v70_anulado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v70_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v70_instit"])){
       $sql  .= $virgula." v70_instit = $this->v70_instit ";
       $virgula = ",";
       if(trim($this->v70_instit) == null ){
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "v70_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v70_cartorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v70_cartorio"])){
       $sql  .= $virgula." v70_cartorio = $this->v70_cartorio ";
       $virgula = ",";
       if(trim($this->v70_cartorio) == null ){
         $this->erro_sql = " Campo Cartório nao Informado.";
         $this->erro_campo = "v70_cartorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v70_sequencial!=null){
       $sql .= " v70_sequencial = $this->v70_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v70_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17343,'$this->v70_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v70_sequencial"]) || $this->v70_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3069,17343,'".AddSlashes(pg_result($resaco,$conresaco,'v70_sequencial'))."','$this->v70_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v70_codforo"]) || $this->v70_codforo != "")
           $resac = db_query("insert into db_acount values($acount,3069,17367,'".AddSlashes(pg_result($resaco,$conresaco,'v70_codforo'))."','$this->v70_codforo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v70_processoforomov"]) || $this->v70_processoforomov != "")
           $resac = db_query("insert into db_acount values($acount,3069,17344,'".AddSlashes(pg_result($resaco,$conresaco,'v70_processoforomov'))."','$this->v70_processoforomov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v70_id_usuario"]) || $this->v70_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3069,17345,'".AddSlashes(pg_result($resaco,$conresaco,'v70_id_usuario'))."','$this->v70_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v70_vara"]) || $this->v70_vara != "")
           $resac = db_query("insert into db_acount values($acount,3069,17346,'".AddSlashes(pg_result($resaco,$conresaco,'v70_vara'))."','$this->v70_vara',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v70_data"]) || $this->v70_data != "")
           $resac = db_query("insert into db_acount values($acount,3069,17347,'".AddSlashes(pg_result($resaco,$conresaco,'v70_data'))."','$this->v70_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v70_valorinicial"]) || $this->v70_valorinicial != "")
           $resac = db_query("insert into db_acount values($acount,3069,17436,'".AddSlashes(pg_result($resaco,$conresaco,'v70_valorinicial'))."','$this->v70_valorinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v70_observacao"]) || $this->v70_observacao != "")
           $resac = db_query("insert into db_acount values($acount,3069,17437,'".AddSlashes(pg_result($resaco,$conresaco,'v70_observacao'))."','$this->v70_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v70_anulado"]) || $this->v70_anulado != "")
           $resac = db_query("insert into db_acount values($acount,3069,17348,'".AddSlashes(pg_result($resaco,$conresaco,'v70_anulado'))."','$this->v70_anulado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v70_instit"]) || $this->v70_instit != "")
           $resac = db_query("insert into db_acount values($acount,3069,17812,'".AddSlashes(pg_result($resaco,$conresaco,'v70_instit'))."','$this->v70_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v70_cartorio"]) || $this->v70_cartorio != "")
           $resac = db_query("insert into db_acount values($acount,3069,18155,'".AddSlashes(pg_result($resaco,$conresaco,'v70_cartorio'))."','$this->v70_cartorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "processoforo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v70_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "processoforo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v70_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v70_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($v70_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v70_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17343,'$v70_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3069,17343,'','".AddSlashes(pg_result($resaco,$iresaco,'v70_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3069,17367,'','".AddSlashes(pg_result($resaco,$iresaco,'v70_codforo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3069,17344,'','".AddSlashes(pg_result($resaco,$iresaco,'v70_processoforomov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3069,17345,'','".AddSlashes(pg_result($resaco,$iresaco,'v70_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3069,17346,'','".AddSlashes(pg_result($resaco,$iresaco,'v70_vara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3069,17347,'','".AddSlashes(pg_result($resaco,$iresaco,'v70_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3069,17436,'','".AddSlashes(pg_result($resaco,$iresaco,'v70_valorinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3069,17437,'','".AddSlashes(pg_result($resaco,$iresaco,'v70_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3069,17348,'','".AddSlashes(pg_result($resaco,$iresaco,'v70_anulado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3069,17812,'','".AddSlashes(pg_result($resaco,$iresaco,'v70_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3069,18155,'','".AddSlashes(pg_result($resaco,$iresaco,'v70_cartorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from processoforo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v70_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v70_sequencial = $v70_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "processoforo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v70_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "processoforo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v70_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v70_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:processoforo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $v70_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from processoforo ";
     $sql .= "      inner join db_config  on  db_config.codigo = processoforo.v70_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = processoforo.v70_id_usuario";
     $sql .= "      inner join vara  on  vara.v53_codvara = processoforo.v70_vara";
     $sql .= "      left  join processoforomov  on  processoforomov.v73_sequencial = processoforo.v70_processoforomov";
     $sql .= "      inner join cartorio  on  cartorio.v82_sequencial = processoforo.v70_cartorio";
     $sql .= "                          and  cartorio.v82_extrajudicial = false";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      left  join db_usuarios  as a on   a.id_usuario = processoforomov.v73_id_usuario";
     $sql .= "      left  join processoforo  as b on   b.v70_sequencial = processoforomov.v73_processoforo";
     $sql .= "      left  join processoforomovsituacao  on  processoforomovsituacao.v74_sequencial = processoforomov.v73_processoforomovsituacao";
     $sql .= "      inner join cgm  as c on   c.z01_numcgm = cartorio.v82_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($v70_sequencial!=null ){
         $sql2 .= " where processoforo.v70_sequencial = $v70_sequencial ";
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
   function sql_query_file ( $v70_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from processoforo ";
     $sql2 = "";
     if($dbwhere==""){
       if($v70_sequencial!=null ){
         $sql2 .= " where processoforo.v70_sequencial = $v70_sequencial ";
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

   function sql_query_cgm_inicial ( $v70_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select distinct ";
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
     $sql .= " from processoforo ";
     $sql .= "      inner join db_usuarios              on db_usuarios.id_usuario                 = processoforo.v70_id_usuario                 ";
     $sql .= "      inner join vara                     on vara.v53_codvara                       = processoforo.v70_vara                       ";
     $sql .= "      left  join processoforomov          on processoforomov.v73_sequencial         = processoforo.v70_processoforomov            ";
     $sql .= "      left  join processoforomovsituacao  on processoforomovsituacao.v74_sequencial = processoforomov.v73_processoforomovsituacao ";
     $sql .= "      left  join processoforoinicial      on processoforoinicial.v71_processoforo   = processoforo.v70_sequencial                 ";
     $sql .= "      left  join inicial                  on inicial.v50_inicial                    = processoforoinicial.v71_inicial             ";
     $sql .= "      left  join inicialnomes             on inicialnomes.v58_inicial               = inicial.v50_inicial                         ";
     $sql .= "      left  join cgm                      on inicialnomes.v58_numcgm                = cgm.z01_numcgm                              ";
     $sql2 = "";
     if($dbwhere==""){
       if($v70_sequencial!=null ){
         $sql2 .= " where processoforo.v70_sequencial = $v70_sequencial ";
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
   function sql_query_cgm_nome ( $v70_sequencial=null,$campos="*",$ordem=null,$dbwhere="",$mostra_nome=true){
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
     if ($mostra_nome){
        $sql .= " , case when cgmr.z01_nome is not null then cgmr.z01_nome else cgm.z01_nome end as dl_nome ";
     }
     $sql .= " from processoforo ";
     $sql .= "      inner join db_usuarios              on db_usuarios.id_usuario                 = processoforo.v70_id_usuario                 ";
     $sql .= "      inner join vara                     on vara.v53_codvara                       = processoforo.v70_vara                       ";
     $sql .= "      left  join processoforomov          on processoforomov.v73_sequencial         = processoforo.v70_processoforomov            ";
     $sql .= "      left  join processoforomovsituacao  on processoforomovsituacao.v74_sequencial = processoforomov.v73_processoforomovsituacao ";
     $sql .= "      left  join processoforoinicial      on processoforoinicial.v71_processoforo   = processoforo.v70_sequencial                 ";
     $sql .= "      left  join inicial                  on inicial.v50_inicial                    = processoforoinicial.v71_inicial             ";
     $sql .= "      left  join inicialnomes             on inicialnomes.v58_inicial               = inicial.v50_inicial                         ";
     $sql .= "      left  join cgm                      on inicialnomes.v58_numcgm                = cgm.z01_numcgm                              ";
     $sql .= "      left  join processoforonumcgm       on processoforonumcgm.v75_seqprocforo     = processoforo.v70_sequencial                 ";
     $sql .= "      left  join cgm as cgmr              on cgmr.z01_numcgm                        = processoforonumcgm.v75_numcgm               ";
     $sql .= "      left  join cgm as cgm_advogado      on cgm_advogado.z01_numcgm                = inicial.v50_advog                           ";
     $sql2 = "";
     if($dbwhere==""){
       if($v70_sequencial!=null ){
         $sql2 .= " where processoforo.v70_sequencial = $v70_sequencial ";
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

   function sql_query_envolvidos ( $v70_sequencial=null,$campos="*",$ordem=null,$dbwhere="",$mostra_nome=true){
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
     if ($mostra_nome){
        $sql .= " , case when cgmr.z01_nome is not null then cgmr.z01_nome else cgm.z01_nome end as dl_nome ";
     }
     $sql .= " from processoforo ";
     $sql .= "      inner join db_usuarios              on db_usuarios.id_usuario                 = processoforo.v70_id_usuario                 ";
     $sql .= "      inner join vara                     on vara.v53_codvara                       = processoforo.v70_vara                       ";
     $sql .= "      left  join processoforopartilha     on processoforopartilha.v76_processoforo  = processoforo.v70_sequencial                 ";
     $sql .= "      left  join processoforomov          on processoforomov.v73_sequencial         = processoforo.v70_processoforomov            ";
     $sql .= "      left  join processoforomovsituacao  on processoforomovsituacao.v74_sequencial = processoforomov.v73_processoforomovsituacao ";
     $sql .= "      left  join processoforoinicial      on processoforoinicial.v71_processoforo   = processoforo.v70_sequencial                 ";
     $sql .= "      left  join inicial                  on inicial.v50_inicial                    = processoforoinicial.v71_inicial             ";
     $sql .= "      left  join inicialnumpre            on inicialnumpre.v59_inicial              = inicial.v50_inicial                         ";
     $sql .= "      left  join arrematric               on arrematric.k00_numpre                  = inicialnumpre.v59_numpre                    ";
     $sql .= "      left  join arreinscr                on arreinscr.k00_numpre                   = inicialnumpre.v59_numpre                    ";
     $sql .= "      left  join arrenumcgm               on arrenumcgm.k00_numpre                  = inicialnumpre.v59_numpre                    ";
     $sql .= "      left  join inicialnomes             on inicialnomes.v58_inicial               = inicial.v50_inicial                         ";
     $sql .= "      left  join cgm                      on inicialnomes.v58_numcgm                = cgm.z01_numcgm                              ";
     $sql .= "      left  join processoforonumcgm       on processoforonumcgm.v75_seqprocforo     = processoforo.v70_sequencial                 ";
     $sql .= "      left  join cgm as cgmr              on cgmr.z01_numcgm                        = processoforonumcgm.v75_numcgm               ";
     $sql .= "      left  join cgm as cgm_advogado      on cgm_advogado.z01_numcgm                = inicial.v50_advog                           ";
     $sql2 = "";
     if($dbwhere==""){
       if($v70_sequencial!=null ){
         $sql2 .= " where processoforo.v70_sequencial = $v70_sequencial ";
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
?>