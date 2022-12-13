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

//MODULO: divida
//CLASSE DA ENTIDADE acertid
class cl_acertid {
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
   var $v15_codigo = 0;
   var $v15_certid = 0;
   var $v15_data_dia = null;
   var $v15_data_mes = null;
   var $v15_data_ano = null;
   var $v15_data = null;
   var $v15_hora = null;
   var $v15_usuario = 0;
   var $v15_parcial = 'f';
   var $v15_instit = 0;
   var $v15_observacao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 v15_codigo = int8 = Código
                 v15_certid = int8 = Código da Certidão
                 v15_data = date = Data
                 v15_hora = char(5) = Hora da anulação
                 v15_usuario = int4 = Cod. Usuário
                 v15_parcial = bool = Parcial
                 v15_instit = int4 = Cod. Instituição
                 v15_observacao = text = Observação
                 ";
   //funcao construtor da classe
   function cl_acertid() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acertid");
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
       $this->v15_codigo = ($this->v15_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["v15_codigo"]:$this->v15_codigo);
       $this->v15_certid = ($this->v15_certid == ""?@$GLOBALS["HTTP_POST_VARS"]["v15_certid"]:$this->v15_certid);
       if($this->v15_data == ""){
         $this->v15_data_dia = ($this->v15_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v15_data_dia"]:$this->v15_data_dia);
         $this->v15_data_mes = ($this->v15_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v15_data_mes"]:$this->v15_data_mes);
         $this->v15_data_ano = ($this->v15_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v15_data_ano"]:$this->v15_data_ano);
         if($this->v15_data_dia != ""){
            $this->v15_data = $this->v15_data_ano."-".$this->v15_data_mes."-".$this->v15_data_dia;
         }
       }
       $this->v15_hora = ($this->v15_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["v15_hora"]:$this->v15_hora);
       $this->v15_usuario = ($this->v15_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["v15_usuario"]:$this->v15_usuario);
       $this->v15_parcial = ($this->v15_parcial == "f"?@$GLOBALS["HTTP_POST_VARS"]["v15_parcial"]:$this->v15_parcial);
       $this->v15_instit = ($this->v15_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["v15_instit"]:$this->v15_instit);
       $this->v15_observacao = ($this->v15_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["v15_observacao"]:$this->v15_observacao);
     }else{
       $this->v15_codigo = ($this->v15_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["v15_codigo"]:$this->v15_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($v15_codigo){
      $this->atualizacampos();
     if($this->v15_certid == null ){
       $this->erro_sql = " Campo Código da Certidão nao Informado.";
       $this->erro_campo = "v15_certid";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v15_data == null ){
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "v15_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v15_hora == null ){
       $this->erro_sql = " Campo Hora da anulação nao Informado.";
       $this->erro_campo = "v15_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v15_usuario == null ){
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "v15_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v15_parcial == null ){
       $this->erro_sql = " Campo Parcial nao Informado.";
       $this->erro_campo = "v15_parcial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v15_instit == null ){
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "v15_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v15_codigo == "" || $v15_codigo == null ){
       $result = db_query("select nextval('acertid_v15_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acertid_v15_codigo_seq do campo: v15_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->v15_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from acertid_v15_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $v15_codigo)){
         $this->erro_sql = " Campo v15_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v15_codigo = $v15_codigo;
       }
     }
     if(($this->v15_codigo == null) || ($this->v15_codigo == "") ){
       $this->erro_sql = " Campo v15_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acertid(
                                       v15_codigo
                                      ,v15_certid
                                      ,v15_data
                                      ,v15_hora
                                      ,v15_usuario
                                      ,v15_parcial
                                      ,v15_instit
                                      ,v15_observacao
                       )
                values (
                                $this->v15_codigo
                               ,$this->v15_certid
                               ,".($this->v15_data == "null" || $this->v15_data == ""?"null":"'".$this->v15_data."'")."
                               ,'$this->v15_hora'
                               ,$this->v15_usuario
                               ,'$this->v15_parcial'
                               ,$this->v15_instit
                               ,'$this->v15_observacao'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Certidões Anuladas ($this->v15_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Certidões Anuladas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Certidões Anuladas ($this->v15_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v15_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v15_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7625,'$this->v15_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1265,7625,'','".AddSlashes(pg_result($resaco,0,'v15_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1265,7626,'','".AddSlashes(pg_result($resaco,0,'v15_certid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1265,7627,'','".AddSlashes(pg_result($resaco,0,'v15_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1265,7628,'','".AddSlashes(pg_result($resaco,0,'v15_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1265,7629,'','".AddSlashes(pg_result($resaco,0,'v15_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1265,7630,'','".AddSlashes(pg_result($resaco,0,'v15_parcial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1265,10571,'','".AddSlashes(pg_result($resaco,0,'v15_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1265,19289,'','".AddSlashes(pg_result($resaco,0,'v15_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($v15_codigo=null) {
      $this->atualizacampos();
     $sql = " update acertid set ";
     $virgula = "";
     if(trim($this->v15_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v15_codigo"])){
       $sql  .= $virgula." v15_codigo = $this->v15_codigo ";
       $virgula = ",";
       if(trim($this->v15_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "v15_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v15_certid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v15_certid"])){
       $sql  .= $virgula." v15_certid = $this->v15_certid ";
       $virgula = ",";
       if(trim($this->v15_certid) == null ){
         $this->erro_sql = " Campo Código da Certidão nao Informado.";
         $this->erro_campo = "v15_certid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v15_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v15_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v15_data_dia"] !="") ){
       $sql  .= $virgula." v15_data = '$this->v15_data' ";
       $virgula = ",";
       if(trim($this->v15_data) == null ){
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "v15_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["v15_data_dia"])){
         $sql  .= $virgula." v15_data = null ";
         $virgula = ",";
         if(trim($this->v15_data) == null ){
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "v15_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v15_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v15_hora"])){
       $sql  .= $virgula." v15_hora = '$this->v15_hora' ";
       $virgula = ",";
       if(trim($this->v15_hora) == null ){
         $this->erro_sql = " Campo Hora da anulação nao Informado.";
         $this->erro_campo = "v15_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v15_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v15_usuario"])){
       $sql  .= $virgula." v15_usuario = $this->v15_usuario ";
       $virgula = ",";
       if(trim($this->v15_usuario) == null ){
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "v15_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v15_parcial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v15_parcial"])){
       $sql  .= $virgula." v15_parcial = '$this->v15_parcial' ";
       $virgula = ",";
       if(trim($this->v15_parcial) == null ){
         $this->erro_sql = " Campo Parcial nao Informado.";
         $this->erro_campo = "v15_parcial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v15_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v15_instit"])){
       $sql  .= $virgula." v15_instit = $this->v15_instit ";
       $virgula = ",";
       if(trim($this->v15_instit) == null ){
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "v15_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v15_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v15_observacao"])){
       $sql  .= $virgula." v15_observacao = '$this->v15_observacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($v15_codigo!=null){
       $sql .= " v15_codigo = $this->v15_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v15_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7625,'$this->v15_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v15_codigo"]) || $this->v15_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1265,7625,'".AddSlashes(pg_result($resaco,$conresaco,'v15_codigo'))."','$this->v15_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v15_certid"]) || $this->v15_certid != "")
           $resac = db_query("insert into db_acount values($acount,1265,7626,'".AddSlashes(pg_result($resaco,$conresaco,'v15_certid'))."','$this->v15_certid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v15_data"]) || $this->v15_data != "")
           $resac = db_query("insert into db_acount values($acount,1265,7627,'".AddSlashes(pg_result($resaco,$conresaco,'v15_data'))."','$this->v15_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v15_hora"]) || $this->v15_hora != "")
           $resac = db_query("insert into db_acount values($acount,1265,7628,'".AddSlashes(pg_result($resaco,$conresaco,'v15_hora'))."','$this->v15_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v15_usuario"]) || $this->v15_usuario != "")
           $resac = db_query("insert into db_acount values($acount,1265,7629,'".AddSlashes(pg_result($resaco,$conresaco,'v15_usuario'))."','$this->v15_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v15_parcial"]) || $this->v15_parcial != "")
           $resac = db_query("insert into db_acount values($acount,1265,7630,'".AddSlashes(pg_result($resaco,$conresaco,'v15_parcial'))."','$this->v15_parcial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v15_instit"]) || $this->v15_instit != "")
           $resac = db_query("insert into db_acount values($acount,1265,10571,'".AddSlashes(pg_result($resaco,$conresaco,'v15_instit'))."','$this->v15_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v15_observacao"]) || $this->v15_observacao != "")
           $resac = db_query("insert into db_acount values($acount,1265,19289,'".AddSlashes(pg_result($resaco,$conresaco,'v15_observacao'))."','$this->v15_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Certidões Anuladas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v15_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Certidões Anuladas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v15_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v15_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($v15_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v15_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7625,'$v15_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1265,7625,'','".AddSlashes(pg_result($resaco,$iresaco,'v15_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1265,7626,'','".AddSlashes(pg_result($resaco,$iresaco,'v15_certid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1265,7627,'','".AddSlashes(pg_result($resaco,$iresaco,'v15_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1265,7628,'','".AddSlashes(pg_result($resaco,$iresaco,'v15_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1265,7629,'','".AddSlashes(pg_result($resaco,$iresaco,'v15_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1265,7630,'','".AddSlashes(pg_result($resaco,$iresaco,'v15_parcial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1265,10571,'','".AddSlashes(pg_result($resaco,$iresaco,'v15_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1265,19289,'','".AddSlashes(pg_result($resaco,$iresaco,'v15_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acertid
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v15_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v15_codigo = $v15_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Certidões Anuladas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v15_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Certidões Anuladas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v15_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v15_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:acertid";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $v15_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from acertid ";
     $sql .= "      inner join db_config  on  db_config.codigo = acertid.v15_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = acertid.v15_usuario";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($v15_codigo!=null ){
         $sql2 .= " where acertid.v15_codigo = $v15_codigo ";
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
   function sql_query_file ( $v15_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from acertid ";
     $sql2 = "";
     if($dbwhere==""){
       if($v15_codigo!=null ){
         $sql2 .= " where acertid.v15_codigo = $v15_codigo ";
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
   function sql_query_info ( $v15_codigo=null,$campos="*",$ordem=null,$dbwhere="",$considerarInstit=false){

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
     $sql .= " from acertid ";
     $sql .= "      inner join db_usuarios on  db_usuarios.id_usuario  = acertid.v15_usuario";
     $sql .= "      left  join acertter    on  acertter.v14_codacertid = acertid.v15_codigo";
     $sql .= "      left  join acertdiv    on  acertdiv.v14_codacertid = acertid.v15_codigo";
     $sql .= "      left  join divida      on  divida.v01_coddiv       = acertdiv.v14_coddiv";
		 if ($considerarInstit){
		   $sql .= "                          and  divida.v01_instit       = ".db_getsession('DB_instit') ;
		 }
     $sql .= "      left  join termo       on  termo.v07_parcel        = acertter.v14_parcel";
		 if ($considerarInstit){
		   $sql .= "                          and  termo.v07_instit        = ".db_getsession('DB_instit') ;
		 }
     $sql2 = "";
     if($dbwhere==""){
       if($v15_codigo!=null ){
         $sql2 .= " where acertid.v15_codigo = $v15_codigo ";
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
   * Buscamos os dados das movimentações da certidão
   *
   * @param  int    $iSequencial  sequencial da certidão
   * @param  string $sCampos      Campos do select
   *
   * @return string               query montada
   */
  function sql_query_movimentacao( $iSequencial = null, $sCampos = "*" ) {

    $sSql  = " select {$sCampos} ";
    $sSql .= "   from acertid ";
    $sSql .= "        inner join certidcartorio on v31_certid = v15_certid ";
    $sSql .= "        inner join certidmovimentacao on v31_sequencial = v32_certidcartorio ";
    $sSql .= "  where v31_certid = {$iSequencial} ";
    $sSql .= "  order by v32_sequencial desc ";

    return $sSql;
  }
}
?>