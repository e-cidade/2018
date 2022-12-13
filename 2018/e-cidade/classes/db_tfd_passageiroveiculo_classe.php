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

//MODULO: TFD
//CLASSE DA ENTIDADE tfd_passageiroveiculo
class cl_tfd_passageiroveiculo {
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
   var $tf19_i_codigo = 0;
   var $tf19_i_cgsund = 0;
   var $tf19_i_veiculodestino = 0;
   var $tf19_i_pedidotfd = 0;
   var $tf19_i_valido = 0;
   var $tf19_i_tipopassageiro = 0;
   var $tf19_i_fica = 0;
   var $tf19_i_colo = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 tf19_i_codigo = int4 = Código
                 tf19_i_cgsund = int4 = CGS
                 tf19_i_veiculodestino = int4 = Veículo Destino
                 tf19_i_pedidotfd = int4 = Pedido
                 tf19_i_valido = int4 = Válido
                 tf19_i_tipopassageiro = int4 = Tipo passgeiro
                 tf19_i_fica = int4 = Fica
                 tf19_i_colo = int4 = Colo
                 ";
   //funcao construtor da classe
   function cl_tfd_passageiroveiculo() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tfd_passageiroveiculo");
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
       $this->tf19_i_codigo = ($this->tf19_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf19_i_codigo"]:$this->tf19_i_codigo);
       $this->tf19_i_cgsund = ($this->tf19_i_cgsund == ""?@$GLOBALS["HTTP_POST_VARS"]["tf19_i_cgsund"]:$this->tf19_i_cgsund);
       $this->tf19_i_veiculodestino = ($this->tf19_i_veiculodestino == ""?@$GLOBALS["HTTP_POST_VARS"]["tf19_i_veiculodestino"]:$this->tf19_i_veiculodestino);
       $this->tf19_i_pedidotfd = ($this->tf19_i_pedidotfd == ""?@$GLOBALS["HTTP_POST_VARS"]["tf19_i_pedidotfd"]:$this->tf19_i_pedidotfd);
       $this->tf19_i_valido = ($this->tf19_i_valido == ""?@$GLOBALS["HTTP_POST_VARS"]["tf19_i_valido"]:$this->tf19_i_valido);
       $this->tf19_i_tipopassageiro = ($this->tf19_i_tipopassageiro == ""?@$GLOBALS["HTTP_POST_VARS"]["tf19_i_tipopassageiro"]:$this->tf19_i_tipopassageiro);
       $this->tf19_i_fica = ($this->tf19_i_fica == ""?@$GLOBALS["HTTP_POST_VARS"]["tf19_i_fica"]:$this->tf19_i_fica);
       $this->tf19_i_colo = ($this->tf19_i_colo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf19_i_colo"]:$this->tf19_i_colo);
     }else{
       $this->tf19_i_codigo = ($this->tf19_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf19_i_codigo"]:$this->tf19_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($tf19_i_codigo){
      $this->atualizacampos();
     if($this->tf19_i_cgsund == null ){
       $this->erro_sql = " Campo CGS nao Informado.";
       $this->erro_campo = "tf19_i_cgsund";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf19_i_veiculodestino == null ){
       $this->erro_sql = " Campo Veículo Destino nao Informado.";
       $this->erro_campo = "tf19_i_veiculodestino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf19_i_pedidotfd == null ){
       $this->erro_sql = " Campo Pedido nao Informado.";
       $this->erro_campo = "tf19_i_pedidotfd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf19_i_valido == null ){
       $this->erro_sql = " Campo Válido nao Informado.";
       $this->erro_campo = "tf19_i_valido";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf19_i_tipopassageiro == null ){
       $this->erro_sql = " Campo Tipo passgeiro nao Informado.";
       $this->erro_campo = "tf19_i_tipopassageiro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf19_i_fica == null ){
       $this->erro_sql = " Campo Fica nao Informado.";
       $this->erro_campo = "tf19_i_fica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf19_i_colo == null ){
       $this->erro_sql = " Campo Colo nao Informado.";
       $this->erro_campo = "tf19_i_colo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tf19_i_codigo == "" || $tf19_i_codigo == null ){
       $result = db_query("select nextval('tfd_passageiroveiculo_tf19_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tfd_passageiroveiculo_tf19_i_codigo_seq do campo: tf19_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->tf19_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from tfd_passageiroveiculo_tf19_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $tf19_i_codigo)){
         $this->erro_sql = " Campo tf19_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tf19_i_codigo = $tf19_i_codigo;
       }
     }
     if(($this->tf19_i_codigo == null) || ($this->tf19_i_codigo == "") ){
       $this->erro_sql = " Campo tf19_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tfd_passageiroveiculo(
                                       tf19_i_codigo
                                      ,tf19_i_cgsund
                                      ,tf19_i_veiculodestino
                                      ,tf19_i_pedidotfd
                                      ,tf19_i_valido
                                      ,tf19_i_tipopassageiro
                                      ,tf19_i_fica
                                      ,tf19_i_colo
                       )
                values (
                                $this->tf19_i_codigo
                               ,$this->tf19_i_cgsund
                               ,$this->tf19_i_veiculodestino
                               ,$this->tf19_i_pedidotfd
                               ,$this->tf19_i_valido
                               ,$this->tf19_i_tipopassageiro
                               ,$this->tf19_i_fica
                               ,$this->tf19_i_colo
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tfd_passageiroveiculo ($this->tf19_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tfd_passageiroveiculo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tfd_passageiroveiculo ($this->tf19_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf19_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->tf19_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16418,'$this->tf19_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2875,16418,'','".AddSlashes(pg_result($resaco,0,'tf19_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2875,16420,'','".AddSlashes(pg_result($resaco,0,'tf19_i_cgsund'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2875,16421,'','".AddSlashes(pg_result($resaco,0,'tf19_i_veiculodestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2875,16419,'','".AddSlashes(pg_result($resaco,0,'tf19_i_pedidotfd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2875,16422,'','".AddSlashes(pg_result($resaco,0,'tf19_i_valido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2875,16705,'','".AddSlashes(pg_result($resaco,0,'tf19_i_tipopassageiro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2875,17301,'','".AddSlashes(pg_result($resaco,0,'tf19_i_fica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2875,17302,'','".AddSlashes(pg_result($resaco,0,'tf19_i_colo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($tf19_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update tfd_passageiroveiculo set ";
     $virgula = "";
     if(trim($this->tf19_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf19_i_codigo"])){
       $sql  .= $virgula." tf19_i_codigo = $this->tf19_i_codigo ";
       $virgula = ",";
       if(trim($this->tf19_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "tf19_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf19_i_cgsund)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf19_i_cgsund"])){
       $sql  .= $virgula." tf19_i_cgsund = $this->tf19_i_cgsund ";
       $virgula = ",";
       if(trim($this->tf19_i_cgsund) == null ){
         $this->erro_sql = " Campo CGS nao Informado.";
         $this->erro_campo = "tf19_i_cgsund";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf19_i_veiculodestino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf19_i_veiculodestino"])){
       $sql  .= $virgula." tf19_i_veiculodestino = $this->tf19_i_veiculodestino ";
       $virgula = ",";
       if(trim($this->tf19_i_veiculodestino) == null ){
         $this->erro_sql = " Campo Veículo Destino nao Informado.";
         $this->erro_campo = "tf19_i_veiculodestino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf19_i_pedidotfd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf19_i_pedidotfd"])){
       $sql  .= $virgula." tf19_i_pedidotfd = $this->tf19_i_pedidotfd ";
       $virgula = ",";
       if(trim($this->tf19_i_pedidotfd) == null ){
         $this->erro_sql = " Campo Pedido nao Informado.";
         $this->erro_campo = "tf19_i_pedidotfd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf19_i_valido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf19_i_valido"])){
       $sql  .= $virgula." tf19_i_valido = $this->tf19_i_valido ";
       $virgula = ",";
       if(trim($this->tf19_i_valido) == null ){
         $this->erro_sql = " Campo Válido nao Informado.";
         $this->erro_campo = "tf19_i_valido";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf19_i_tipopassageiro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf19_i_tipopassageiro"])){
       $sql  .= $virgula." tf19_i_tipopassageiro = $this->tf19_i_tipopassageiro ";
       $virgula = ",";
       if(trim($this->tf19_i_tipopassageiro) == null ){
         $this->erro_sql = " Campo Tipo passgeiro nao Informado.";
         $this->erro_campo = "tf19_i_tipopassageiro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf19_i_fica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf19_i_fica"])){
       $sql  .= $virgula." tf19_i_fica = $this->tf19_i_fica ";
       $virgula = ",";
       if(trim($this->tf19_i_fica) == null ){
         $this->erro_sql = " Campo Fica nao Informado.";
         $this->erro_campo = "tf19_i_fica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf19_i_colo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf19_i_colo"])){
       $sql  .= $virgula." tf19_i_colo = $this->tf19_i_colo ";
       $virgula = ",";
       if(trim($this->tf19_i_colo) == null ){
         $this->erro_sql = " Campo Colo nao Informado.";
         $this->erro_campo = "tf19_i_colo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($tf19_i_codigo!=null){
       $sql .= " tf19_i_codigo = $this->tf19_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->tf19_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16418,'$this->tf19_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf19_i_codigo"]) || $this->tf19_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2875,16418,'".AddSlashes(pg_result($resaco,$conresaco,'tf19_i_codigo'))."','$this->tf19_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf19_i_cgsund"]) || $this->tf19_i_cgsund != "")
           $resac = db_query("insert into db_acount values($acount,2875,16420,'".AddSlashes(pg_result($resaco,$conresaco,'tf19_i_cgsund'))."','$this->tf19_i_cgsund',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf19_i_veiculodestino"]) || $this->tf19_i_veiculodestino != "")
           $resac = db_query("insert into db_acount values($acount,2875,16421,'".AddSlashes(pg_result($resaco,$conresaco,'tf19_i_veiculodestino'))."','$this->tf19_i_veiculodestino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf19_i_pedidotfd"]) || $this->tf19_i_pedidotfd != "")
           $resac = db_query("insert into db_acount values($acount,2875,16419,'".AddSlashes(pg_result($resaco,$conresaco,'tf19_i_pedidotfd'))."','$this->tf19_i_pedidotfd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf19_i_valido"]) || $this->tf19_i_valido != "")
           $resac = db_query("insert into db_acount values($acount,2875,16422,'".AddSlashes(pg_result($resaco,$conresaco,'tf19_i_valido'))."','$this->tf19_i_valido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf19_i_tipopassageiro"]) || $this->tf19_i_tipopassageiro != "")
           $resac = db_query("insert into db_acount values($acount,2875,16705,'".AddSlashes(pg_result($resaco,$conresaco,'tf19_i_tipopassageiro'))."','$this->tf19_i_tipopassageiro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf19_i_fica"]) || $this->tf19_i_fica != "")
           $resac = db_query("insert into db_acount values($acount,2875,17301,'".AddSlashes(pg_result($resaco,$conresaco,'tf19_i_fica'))."','$this->tf19_i_fica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf19_i_colo"]) || $this->tf19_i_colo != "")
           $resac = db_query("insert into db_acount values($acount,2875,17302,'".AddSlashes(pg_result($resaco,$conresaco,'tf19_i_colo'))."','$this->tf19_i_colo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_passageiroveiculo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf19_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_passageiroveiculo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf19_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf19_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($tf19_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($tf19_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16418,'$tf19_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2875,16418,'','".AddSlashes(pg_result($resaco,$iresaco,'tf19_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2875,16420,'','".AddSlashes(pg_result($resaco,$iresaco,'tf19_i_cgsund'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2875,16421,'','".AddSlashes(pg_result($resaco,$iresaco,'tf19_i_veiculodestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2875,16419,'','".AddSlashes(pg_result($resaco,$iresaco,'tf19_i_pedidotfd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2875,16422,'','".AddSlashes(pg_result($resaco,$iresaco,'tf19_i_valido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2875,16705,'','".AddSlashes(pg_result($resaco,$iresaco,'tf19_i_tipopassageiro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2875,17301,'','".AddSlashes(pg_result($resaco,$iresaco,'tf19_i_fica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2875,17302,'','".AddSlashes(pg_result($resaco,$iresaco,'tf19_i_colo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tfd_passageiroveiculo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tf19_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tf19_i_codigo = $tf19_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_passageiroveiculo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tf19_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_passageiroveiculo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tf19_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tf19_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tfd_passageiroveiculo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $tf19_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from tfd_passageiroveiculo ";
     $sql .= "      inner join tfd_pedidotfd  on  tfd_pedidotfd.tf01_i_codigo = tfd_passageiroveiculo.tf19_i_pedidotfd";
     $sql .= "      inner join tfd_veiculodestino  on  tfd_veiculodestino.tf18_i_codigo = tfd_passageiroveiculo.tf19_i_veiculodestino";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = tfd_passageiroveiculo.tf19_i_cgsund";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tfd_pedidotfd.tf01_i_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = tfd_pedidotfd.tf01_i_depto";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = tfd_pedidotfd.tf01_i_rhcbo";
     $sql .= "      inner join tfd_tipotratamento  on  tfd_tipotratamento.tf04_i_codigo = tfd_pedidotfd.tf01_i_tipotratamento";
     $sql .= "      inner join tfd_situacaotfd  on  tfd_situacaotfd.tf26_i_codigo = tfd_pedidotfd.tf01_i_situacao";
     $sql .= "      inner join tfd_tipotransporte  on  tfd_tipotransporte.tf27_i_codigo = tfd_pedidotfd.tf01_i_tipotransporte";
     $sql .= "      inner join cgs_und  as a on   a.z01_i_cgsund = tfd_pedidotfd.tf01_i_cgsund";
     $sql .= "      inner join veiculos  on  veiculos.ve01_codigo = tfd_veiculodestino.tf18_i_veiculo";
     $sql .= "      left  join veicmotoristas  on  veicmotoristas.ve05_codigo = tfd_veiculodestino.tf18_i_motorista";
     $sql .= "      inner join tfd_destino  on  tfd_destino.tf03_i_codigo = tfd_veiculodestino.tf18_i_destino";
     $sql .= "      left  join familiamicroarea  on  familiamicroarea.sd35_i_codigo = cgs_und.z01_i_familiamicroarea";
     $sql .= "      inner join cgs  as b on   b.z01_i_numcgs = cgs_und.z01_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($tf19_i_codigo!=null ){
         $sql2 .= " where tfd_passageiroveiculo.tf19_i_codigo = $tf19_i_codigo ";
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
   function sql_query_file ( $tf19_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from tfd_passageiroveiculo ";
     $sql2 = "";
     if($dbwhere==""){
       if($tf19_i_codigo!=null ){
         $sql2 .= " where tfd_passageiroveiculo.tf19_i_codigo = $tf19_i_codigo ";
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

  public function sql_query_passageiro_veiculo ($ed290_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from tfd_passageiroveiculo ";
     $sql .= " inner join tfd_veiculodestino on tf19_i_veiculodestino = tf18_i_codigo ";

     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed290_sequencial)){
         $sql2 .= " where sec_parametros.ed290_sequencial = $ed290_sequencial ";
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
?>