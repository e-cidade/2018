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

//MODULO: caixa
//CLASSE DA ENTIDADE debcontapedido
class cl_debcontapedido {
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
   var $d63_codigo = 0;
   var $d63_instit = 0;
   var $d63_banco = 0;
   var $d63_agencia = null;
   var $d63_conta = null;
   var $d63_datalanc_dia = null;
   var $d63_datalanc_mes = null;
   var $d63_datalanc_ano = null;
   var $d63_datalanc = null;
   var $d63_horalanc = null;
   var $d63_status = 0;
   var $d63_idempresa = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 d63_codigo = int4 = Codigo sequencial
                 d63_instit = int4 = codigo da instituicao
                 d63_banco = int4 = codigo do banco
                 d63_agencia = char(4) = Agencia
                 d63_conta = varchar(14) = Conta
                 d63_datalanc = date = Data de lancamento
                 d63_horalanc = char(5) = Hora de lancamento
                 d63_status = int4 = Status
                 d63_idempresa = varchar(25) = Id Empresa
                 ";
   //funcao construtor da classe
   function cl_debcontapedido() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("debcontapedido");
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
       $this->d63_codigo = ($this->d63_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["d63_codigo"]:$this->d63_codigo);
       $this->d63_instit = ($this->d63_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["d63_instit"]:$this->d63_instit);
       $this->d63_banco = ($this->d63_banco == ""?@$GLOBALS["HTTP_POST_VARS"]["d63_banco"]:$this->d63_banco);
       $this->d63_agencia = ($this->d63_agencia == ""?@$GLOBALS["HTTP_POST_VARS"]["d63_agencia"]:$this->d63_agencia);
       $this->d63_conta = ($this->d63_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["d63_conta"]:$this->d63_conta);
       if($this->d63_datalanc == ""){
         $this->d63_datalanc_dia = ($this->d63_datalanc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["d63_datalanc_dia"]:$this->d63_datalanc_dia);
         $this->d63_datalanc_mes = ($this->d63_datalanc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["d63_datalanc_mes"]:$this->d63_datalanc_mes);
         $this->d63_datalanc_ano = ($this->d63_datalanc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["d63_datalanc_ano"]:$this->d63_datalanc_ano);
         if($this->d63_datalanc_dia != ""){
            $this->d63_datalanc = $this->d63_datalanc_ano."-".$this->d63_datalanc_mes."-".$this->d63_datalanc_dia;
         }
       }
       $this->d63_horalanc = ($this->d63_horalanc == ""?@$GLOBALS["HTTP_POST_VARS"]["d63_horalanc"]:$this->d63_horalanc);
       $this->d63_status = ($this->d63_status == ""?@$GLOBALS["HTTP_POST_VARS"]["d63_status"]:$this->d63_status);
       $this->d63_idempresa = ($this->d63_idempresa == ""?@$GLOBALS["HTTP_POST_VARS"]["d63_idempresa"]:$this->d63_idempresa);
     }else{
       $this->d63_codigo = ($this->d63_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["d63_codigo"]:$this->d63_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($d63_codigo){
      $this->atualizacampos();
     if($this->d63_instit == null ){
       $this->erro_sql = " Campo codigo da instituicao nao Informado.";
       $this->erro_campo = "d63_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d63_banco == null ){
       $this->erro_sql = " Campo codigo do banco nao Informado.";
       $this->erro_campo = "d63_banco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d63_agencia == null ){
       $this->erro_sql = " Campo Agencia nao Informado.";
       $this->erro_campo = "d63_agencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d63_conta == null ){
       $this->erro_sql = " Campo Conta nao Informado.";
       $this->erro_campo = "d63_conta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d63_datalanc == null ){
       $this->erro_sql = " Campo Data de lancamento nao Informado.";
       $this->erro_campo = "d63_datalanc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d63_horalanc == null ){
       $this->erro_sql = " Campo Hora de lancamento nao Informado.";
       $this->erro_campo = "d63_horalanc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d63_status == null ){
       $this->erro_sql = " Campo Status nao Informado.";
       $this->erro_campo = "d63_status";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($d63_codigo == "" || $d63_codigo == null ){
       $result = db_query("select nextval('debcontapedido_d63_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: debcontapedido_d63_codigo_seq do campo: d63_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->d63_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from debcontapedido_d63_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $d63_codigo)){
         $this->erro_sql = " Campo d63_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->d63_codigo = $d63_codigo;
       }
     }
     if(($this->d63_codigo == null) || ($this->d63_codigo == "") ){
       $this->erro_sql = " Campo d63_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into debcontapedido(
                                       d63_codigo
                                      ,d63_instit
                                      ,d63_banco
                                      ,d63_agencia
                                      ,d63_conta
                                      ,d63_datalanc
                                      ,d63_horalanc
                                      ,d63_status
                                      ,d63_idempresa
                       )
                values (
                                $this->d63_codigo
                               ,$this->d63_instit
                               ,$this->d63_banco
                               ,'$this->d63_agencia'
                               ,'$this->d63_conta'
                               ,".($this->d63_datalanc == "null" || $this->d63_datalanc == ""?"null":"'".$this->d63_datalanc."'")."
                               ,'$this->d63_horalanc'
                               ,$this->d63_status
                               ,'$this->d63_idempresa'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Pedido do debito em conta ($this->d63_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Pedido do debito em conta já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Pedido do debito em conta ($this->d63_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d63_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->d63_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7941,'$this->d63_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1330,7941,'','".AddSlashes(pg_result($resaco,0,'d63_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1330,7944,'','".AddSlashes(pg_result($resaco,0,'d63_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1330,7945,'','".AddSlashes(pg_result($resaco,0,'d63_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1330,7946,'','".AddSlashes(pg_result($resaco,0,'d63_agencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1330,7947,'','".AddSlashes(pg_result($resaco,0,'d63_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1330,7948,'','".AddSlashes(pg_result($resaco,0,'d63_datalanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1330,7949,'','".AddSlashes(pg_result($resaco,0,'d63_horalanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1330,7950,'','".AddSlashes(pg_result($resaco,0,'d63_status'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1330,8829,'','".AddSlashes(pg_result($resaco,0,'d63_idempresa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($d63_codigo=null) {
      $this->atualizacampos();
     $sql = " update debcontapedido set ";
     $virgula = "";
     if(trim($this->d63_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d63_codigo"])){
       $sql  .= $virgula." d63_codigo = $this->d63_codigo ";
       $virgula = ",";
       if(trim($this->d63_codigo) == null ){
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "d63_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d63_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d63_instit"])){
       $sql  .= $virgula." d63_instit = $this->d63_instit ";
       $virgula = ",";
       if(trim($this->d63_instit) == null ){
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "d63_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d63_banco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d63_banco"])){
       $sql  .= $virgula." d63_banco = $this->d63_banco ";
       $virgula = ",";
       if(trim($this->d63_banco) == null ){
         $this->erro_sql = " Campo codigo do banco nao Informado.";
         $this->erro_campo = "d63_banco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d63_agencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d63_agencia"])){
       $sql  .= $virgula." d63_agencia = '$this->d63_agencia' ";
       $virgula = ",";
       if(trim($this->d63_agencia) == null ){
         $this->erro_sql = " Campo Agencia nao Informado.";
         $this->erro_campo = "d63_agencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d63_conta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d63_conta"])){
       $sql  .= $virgula." d63_conta = '$this->d63_conta' ";
       $virgula = ",";
       if(trim($this->d63_conta) == null ){
         $this->erro_sql = " Campo Conta nao Informado.";
         $this->erro_campo = "d63_conta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d63_datalanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d63_datalanc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["d63_datalanc_dia"] !="") ){
       $sql  .= $virgula." d63_datalanc = '$this->d63_datalanc' ";
       $virgula = ",";
       if(trim($this->d63_datalanc) == null ){
         $this->erro_sql = " Campo Data de lancamento nao Informado.";
         $this->erro_campo = "d63_datalanc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["d63_datalanc_dia"])){
         $sql  .= $virgula." d63_datalanc = null ";
         $virgula = ",";
         if(trim($this->d63_datalanc) == null ){
           $this->erro_sql = " Campo Data de lancamento nao Informado.";
           $this->erro_campo = "d63_datalanc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->d63_horalanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d63_horalanc"])){
       $sql  .= $virgula." d63_horalanc = '$this->d63_horalanc' ";
       $virgula = ",";
       if(trim($this->d63_horalanc) == null ){
         $this->erro_sql = " Campo Hora de lancamento nao Informado.";
         $this->erro_campo = "d63_horalanc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d63_status)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d63_status"])){
       $sql  .= $virgula." d63_status = $this->d63_status ";
       $virgula = ",";
       if(trim($this->d63_status) == null ){
         $this->erro_sql = " Campo Status nao Informado.";
         $this->erro_campo = "d63_status";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d63_idempresa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d63_idempresa"])){
       $sql  .= $virgula." d63_idempresa = '$this->d63_idempresa' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($d63_codigo!=null){
       $sql .= " d63_codigo = $this->d63_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->d63_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7941,'$this->d63_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d63_codigo"]) || $this->d63_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1330,7941,'".AddSlashes(pg_result($resaco,$conresaco,'d63_codigo'))."','$this->d63_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d63_instit"]) || $this->d63_instit != "")
           $resac = db_query("insert into db_acount values($acount,1330,7944,'".AddSlashes(pg_result($resaco,$conresaco,'d63_instit'))."','$this->d63_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d63_banco"]) || $this->d63_banco != "")
           $resac = db_query("insert into db_acount values($acount,1330,7945,'".AddSlashes(pg_result($resaco,$conresaco,'d63_banco'))."','$this->d63_banco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d63_agencia"]) || $this->d63_agencia != "")
           $resac = db_query("insert into db_acount values($acount,1330,7946,'".AddSlashes(pg_result($resaco,$conresaco,'d63_agencia'))."','$this->d63_agencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d63_conta"]) || $this->d63_conta != "")
           $resac = db_query("insert into db_acount values($acount,1330,7947,'".AddSlashes(pg_result($resaco,$conresaco,'d63_conta'))."','$this->d63_conta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d63_datalanc"]) || $this->d63_datalanc != "")
           $resac = db_query("insert into db_acount values($acount,1330,7948,'".AddSlashes(pg_result($resaco,$conresaco,'d63_datalanc'))."','$this->d63_datalanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d63_horalanc"]) || $this->d63_horalanc != "")
           $resac = db_query("insert into db_acount values($acount,1330,7949,'".AddSlashes(pg_result($resaco,$conresaco,'d63_horalanc'))."','$this->d63_horalanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d63_status"]) || $this->d63_status != "")
           $resac = db_query("insert into db_acount values($acount,1330,7950,'".AddSlashes(pg_result($resaco,$conresaco,'d63_status'))."','$this->d63_status',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d63_idempresa"]) || $this->d63_idempresa != "")
           $resac = db_query("insert into db_acount values($acount,1330,8829,'".AddSlashes(pg_result($resaco,$conresaco,'d63_idempresa'))."','$this->d63_idempresa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pedido do debito em conta nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->d63_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Pedido do debito em conta nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->d63_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d63_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($d63_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($d63_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7941,'$d63_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1330,7941,'','".AddSlashes(pg_result($resaco,$iresaco,'d63_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1330,7944,'','".AddSlashes(pg_result($resaco,$iresaco,'d63_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1330,7945,'','".AddSlashes(pg_result($resaco,$iresaco,'d63_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1330,7946,'','".AddSlashes(pg_result($resaco,$iresaco,'d63_agencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1330,7947,'','".AddSlashes(pg_result($resaco,$iresaco,'d63_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1330,7948,'','".AddSlashes(pg_result($resaco,$iresaco,'d63_datalanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1330,7949,'','".AddSlashes(pg_result($resaco,$iresaco,'d63_horalanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1330,7950,'','".AddSlashes(pg_result($resaco,$iresaco,'d63_status'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1330,8829,'','".AddSlashes(pg_result($resaco,$iresaco,'d63_idempresa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from debcontapedido
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($d63_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d63_codigo = $d63_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pedido do debito em conta nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$d63_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Pedido do debito em conta nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$d63_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$d63_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:debcontapedido";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $d63_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from debcontapedido ";
     $sql .= "      inner join db_config  on  db_config.codigo = debcontapedido.d63_instit";
     $sql .= "      inner join bancos  on  bancos.codbco = debcontapedido.d63_banco";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($d63_codigo!=null ){
         $sql2 .= " where debcontapedido.d63_codigo = $d63_codigo ";
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
   function sql_query_file ( $d63_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from debcontapedido ";
     $sql2 = "";
     if($dbwhere==""){
       if($d63_codigo!=null ){
         $sql2 .= " where debcontapedido.d63_codigo = $d63_codigo ";
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
   function sql_query_info ( $d63_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from debcontapedido ";
     $sql .= "      inner join db_config  on  db_config.codigo = debcontapedido.d63_instit";
     $sql .= "      inner join bancos  on  bancos.codbco = debcontapedido.d63_banco";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      left join debcontapedidocgm on d70_codigo = d63_codigo";
     $sql .= "      left join debcontapedidomatric on d68_codigo = d63_codigo";
     $sql .= "      left join debcontapedidoinscr on d69_codigo = d63_codigo";
     $sql .= "      left join debcontapedidoaguacontrato on d81_codigo = d63_codigo";
     $sql .= "      left join debcontapedidoaguacontratoeconomia on d82_codigo = d63_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($d63_codigo!=null ){
         $sql2 .= " where debcontapedido.d63_codigo = $d63_codigo ";
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

  function sql_query_deb_conta($d63_codigo=null,$campos="*",$ordem=null,$dbwhere="") {
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
     $sql .= " from debcontapedido ";
     $sql .= "      inner join debcontapedidotipo on d66_codigo = d63_codigo";
     $sql .= "      inner join arretipo           on k00_tipo   = d66_arretipo";
     $sql2 = "";
     if($dbwhere==""){
       if($d63_codigo!=null ){
         $sql2 .= " where debcontapedido.d63_codigo = $d63_codigo ";
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

  public function sql_query_pedido_agua($sCampos = '*', $sWhere = null, $sOrder = null)
  {
    $aSql = array();

    $aSql[] = "select {$sCampos}";
    $aSql[] = "from debcontapedido";
    $aSql[] = "inner join bancos on d63_banco = codbco";
    $aSql[] = "left join debcontapedidoaguacontrato on d81_codigo = d63_codigo";
    $aSql[] = "left join debcontapedidoaguacontratoeconomia on d82_codigo = d63_codigo";

    if ($sWhere) {
      $aSql[] = "where {$sWhere}";
    }

    if ($sOrder) {
      $aSql[] = "order by {$sOrder}";
    }

    return implode(' ', $aSql);
  }
}
