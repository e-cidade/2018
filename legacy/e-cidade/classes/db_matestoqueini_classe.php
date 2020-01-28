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

//MODULO: material
//CLASSE DA ENTIDADE matestoqueini
class cl_matestoqueini {
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
   var $m80_codigo = 0;
   var $m80_login = 0;
   var $m80_data_dia = null;
   var $m80_data_mes = null;
   var $m80_data_ano = null;
   var $m80_data = null;
   var $m80_hora = null;
   var $m80_obs = null;
   var $m80_codtipo = 0;
   var $m80_coddepto = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 m80_codigo = int8 = Lançamento
                 m80_login = int4 = Cod. Usuário
                 m80_data = date = Data do lançamento
                 m80_hora = varchar(5) = Hora do lançamento
                 m80_obs = text = Observação do lançamento
                 m80_codtipo = int4 = Tipo
                 m80_coddepto = int4 = Depart.
                 ";
   //funcao construtor da classe
   function cl_matestoqueini() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matestoqueini");
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
       $this->m80_codigo = ($this->m80_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m80_codigo"]:$this->m80_codigo);
       $this->m80_login = ($this->m80_login == ""?@$GLOBALS["HTTP_POST_VARS"]["m80_login"]:$this->m80_login);
       if($this->m80_data == ""){
         $this->m80_data_dia = ($this->m80_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["m80_data_dia"]:$this->m80_data_dia);
         $this->m80_data_mes = ($this->m80_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["m80_data_mes"]:$this->m80_data_mes);
         $this->m80_data_ano = ($this->m80_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["m80_data_ano"]:$this->m80_data_ano);
         if($this->m80_data_dia != ""){
            $this->m80_data = $this->m80_data_ano."-".$this->m80_data_mes."-".$this->m80_data_dia;
         }
       }
       $this->m80_hora = ($this->m80_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["m80_hora"]:$this->m80_hora);
       $this->m80_obs = ($this->m80_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["m80_obs"]:$this->m80_obs);
       $this->m80_codtipo = ($this->m80_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["m80_codtipo"]:$this->m80_codtipo);
       $this->m80_coddepto = ($this->m80_coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["m80_coddepto"]:$this->m80_coddepto);
     }else{
       $this->m80_codigo = ($this->m80_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m80_codigo"]:$this->m80_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($m80_codigo){
      $this->atualizacampos();
     if($this->m80_login == null ){
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "m80_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m80_data == null ){
       $this->erro_sql = " Campo Data do lançamento nao Informado.";
       $this->erro_campo = "m80_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m80_hora == null ){
       $this->erro_sql = " Campo Hora do lançamento nao Informado.";
       $this->erro_campo = "m80_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m80_codtipo == null ){
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "m80_codtipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($this->m80_obs == null ){
       $this->erro_sql = " Campo Observação do lançamento nao Informado.";
       $this->erro_campo = "m80_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($this->m80_coddepto == null ){
       $this->erro_sql = " Campo Depart. nao Informado.";
       $this->erro_campo = "m80_coddepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m80_codigo == "" || $m80_codigo == null ){
       $result = db_query("select nextval('matestoqueini_m80_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matestoqueini_m80_codigo_seq do campo: m80_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->m80_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from matestoqueini_m80_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $m80_codigo)){
         $this->erro_sql = " Campo m80_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m80_codigo = $m80_codigo;
       }
     }
     if(($this->m80_codigo == null) || ($this->m80_codigo == "") ){
       $this->erro_sql = " Campo m80_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matestoqueini(
                                       m80_codigo
                                      ,m80_login
                                      ,m80_data
                                      ,m80_hora
                                      ,m80_obs
                                      ,m80_codtipo
                                      ,m80_coddepto
                       )
                values (
                                $this->m80_codigo
                               ,$this->m80_login
                               ,".($this->m80_data == "null" || $this->m80_data == ""?"null":"'".$this->m80_data."'")."
                               ,'$this->m80_hora'
                               ,'$this->m80_obs'
                               ,$this->m80_codtipo
                               ,$this->m80_coddepto
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Estoque Inicial ($this->m80_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Estoque Inicial já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Estoque Inicial ($this->m80_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m80_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m80_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6889,'$this->m80_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1133,6889,'','".AddSlashes(pg_result($resaco,0,'m80_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1133,6892,'','".AddSlashes(pg_result($resaco,0,'m80_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1133,6890,'','".AddSlashes(pg_result($resaco,0,'m80_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1133,6891,'','".AddSlashes(pg_result($resaco,0,'m80_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1133,6893,'','".AddSlashes(pg_result($resaco,0,'m80_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1133,6899,'','".AddSlashes(pg_result($resaco,0,'m80_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1133,6904,'','".AddSlashes(pg_result($resaco,0,'m80_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }

   // funcao para alteracao
   function alterar ($m80_codigo=null) {
      $this->atualizacampos();
     $sql = " update matestoqueini set ";
     $virgula = "";
     if(trim($this->m80_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m80_codigo"])){
       $sql  .= $virgula." m80_codigo = $this->m80_codigo ";
       $virgula = ",";
       if(trim($this->m80_codigo) == null ){
         $this->erro_sql = " Campo Lançamento nao Informado.";
         $this->erro_campo = "m80_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m80_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m80_login"])){
       $sql  .= $virgula." m80_login = $this->m80_login ";
       $virgula = ",";
       if(trim($this->m80_login) == null ){
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "m80_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m80_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m80_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["m80_data_dia"] !="") ){
       $sql  .= $virgula." m80_data = '$this->m80_data' ";
       $virgula = ",";
       if(trim($this->m80_data) == null ){
         $this->erro_sql = " Campo Data do lançamento nao Informado.";
         $this->erro_campo = "m80_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["m80_data_dia"])){
         $sql  .= $virgula." m80_data = null ";
         $virgula = ",";
         if(trim($this->m80_data) == null ){
           $this->erro_sql = " Campo Data do lançamento nao Informado.";
           $this->erro_campo = "m80_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->m80_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m80_hora"])){
       $sql  .= $virgula." m80_hora = '$this->m80_hora' ";
       $virgula = ",";
       if(trim($this->m80_hora) == null ){
         $this->erro_sql = " Campo Hora do lançamento nao Informado.";
         $this->erro_campo = "m80_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m80_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m80_obs"])){

       $sql  .= $virgula." m80_obs = '$this->m80_obs' ";
       $virgula = ",";

       if(trim($this->m80_obs) == null ){
         $this->erro_sql = " Campo Observação do lançamento nao Informado.";
         $this->erro_campo = "m80_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m80_codtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m80_codtipo"])){
       $sql  .= $virgula." m80_codtipo = $this->m80_codtipo ";
       $virgula = ",";
       if(trim($this->m80_codtipo) == null ){
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "m80_codtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m80_coddepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m80_coddepto"])){
       $sql  .= $virgula." m80_coddepto = $this->m80_coddepto ";
       $virgula = ",";
       if(trim($this->m80_coddepto) == null ){
         $this->erro_sql = " Campo Depart. nao Informado.";
         $this->erro_campo = "m80_coddepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m80_codigo!=null){
       $sql .= " m80_codigo = $this->m80_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m80_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6889,'$this->m80_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m80_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1133,6889,'".AddSlashes(pg_result($resaco,$conresaco,'m80_codigo'))."','$this->m80_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m80_login"]))
           $resac = db_query("insert into db_acount values($acount,1133,6892,'".AddSlashes(pg_result($resaco,$conresaco,'m80_login'))."','$this->m80_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m80_data"]))
           $resac = db_query("insert into db_acount values($acount,1133,6890,'".AddSlashes(pg_result($resaco,$conresaco,'m80_data'))."','$this->m80_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m80_hora"]))
           $resac = db_query("insert into db_acount values($acount,1133,6891,'".AddSlashes(pg_result($resaco,$conresaco,'m80_hora'))."','$this->m80_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m80_obs"]))
           $resac = db_query("insert into db_acount values($acount,1133,6893,'".AddSlashes(pg_result($resaco,$conresaco,'m80_obs'))."','$this->m80_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m80_codtipo"]))
           $resac = db_query("insert into db_acount values($acount,1133,6899,'".AddSlashes(pg_result($resaco,$conresaco,'m80_codtipo'))."','$this->m80_codtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m80_coddepto"]))
           $resac = db_query("insert into db_acount values($acount,1133,6904,'".AddSlashes(pg_result($resaco,$conresaco,'m80_coddepto'))."','$this->m80_coddepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Estoque Inicial nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m80_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Estoque Inicial nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m80_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m80_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($m80_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m80_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6889,'$m80_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1133,6889,'','".AddSlashes(pg_result($resaco,$iresaco,'m80_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1133,6892,'','".AddSlashes(pg_result($resaco,$iresaco,'m80_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1133,6890,'','".AddSlashes(pg_result($resaco,$iresaco,'m80_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1133,6891,'','".AddSlashes(pg_result($resaco,$iresaco,'m80_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1133,6893,'','".AddSlashes(pg_result($resaco,$iresaco,'m80_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1133,6899,'','".AddSlashes(pg_result($resaco,$iresaco,'m80_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1133,6904,'','".AddSlashes(pg_result($resaco,$iresaco,'m80_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matestoqueini
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m80_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m80_codigo = $m80_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Estoque Inicial nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m80_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Estoque Inicial nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m80_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m80_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:matestoqueini";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m80_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matestoqueini ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matestoqueini.m80_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matestoqueini.m80_coddepto";
     $sql .= "      inner join matestoquetipo  on  matestoquetipo.m81_codtipo = matestoqueini.m80_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($m80_codigo!=null ){
         $sql2 .= " where matestoqueini.m80_codigo = $m80_codigo ";
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
   function sql_query_file ( $m80_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matestoqueini ";
     $sql2 = "";
     if($dbwhere==""){
       if($m80_codigo!=null ){
         $sql2 .= " where matestoqueini.m80_codigo = $m80_codigo ";
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
   function sql_query_mater ( $m80_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matestoqueini ";
     $sql .= "      inner join matestoqueinimei  on  matestoqueinimei.m82_matestoqueini = matestoqueini.m80_codigo";
     $sql .= "       left join matestoquetransf  on  matestoquetransf.m83_matestoqueini = matestoqueini.m80_codigo";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueinimei.m82_matestoqueitem";
     $sql .= "      left join  matestoqueitemlote on  matestoqueitem.m71_codlanc = matestoqueitemlote.m77_matestoqueitem";
     $sql .= "      left join  matestoqueitemfabric on  matestoqueitem.m71_codlanc = matestoqueitemfabric.m78_matestoqueitem";
     $sql .= "      left join  matfabricante on matestoqueitemfabric.m78_matfabricante = matfabricante.m76_sequencial";
     $sql .= "      inner join matestoque  on  matestoque.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql .= "      inner join matmater    on  matmater.m60_codmater =  matestoque.m70_codmatmater";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matestoqueini.m80_login";
     $sql .= "      inner join db_depart   on  db_depart.coddepto =  matestoque.m70_coddepto";
     $sql .= "       left join matestoqueinil   on  matestoqueinil.m86_matestoqueini = matestoqueini.m80_codigo";
     $sql .= "       left join matestoqueinill  on  matestoqueinill.m87_matestoqueinil = matestoqueinil.m86_codigo";
     $sql .= "       left join matestoqueini b  on  b.m80_codigo = matestoqueinill.m87_matestoqueini";
     $sql .= "       left join matestoqueitemnotafiscalmanual on matestoqueitemnotafiscalmanual.m79_matestoqueitem = matestoqueitem.m71_codlanc";
     $sql2 = "";
     if($dbwhere==""){
       if($m80_codigo!=null ){
         $sql2 .= " where matestoqueini.m80_codigo = $m80_codigo ";
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
   function sql_query_qua ( $m80_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matestoqueini ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matestoqueini.m80_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matestoqueini.m80_coddepto";
     $sql .= "      inner join matestoquetipo  on  matestoquetipo.m81_codtipo = matestoqueini.m80_codtipo";
     $sql .= "      inner join matestoqueinimei  on  matestoqueinimei.m82_matestoqueini = matestoqueini.m80_codigo";
     $sql .= "      inner join matestoqueitem  on  matestoqueinimei.m82_matestoqueitem = matestoqueitem.m71_codlanc";
     $sql .= "      inner join matestoque  on  matestoque.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matestoque.m70_codmatmater";
     $sql .= "      left  join matmaterunisai  on  matmater.m60_codmater = matmaterunisai.m62_codmater";
     $sql .= "      left  join matunid  on  matunid.m61_codmatunid = matmaterunisai.m62_codmatunid";
     $sql .= "      left  join matestoqueitemlote on matestoqueinimei.m82_matestoqueitem = m77_matestoqueitem";
     $sql2 = "";
     if($dbwhere==""){
       if($m80_codigo!=null ){
         $sql2 .= " where matestoqueini.m80_codigo = $m80_codigo ";
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
   function sql_query_transf ( $m80_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matestoqueini transfere";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = transfere.m80_login";
     $sql .= "      inner join matestoquetransf  on  matestoquetransf.m83_matestoqueini = transfere.m80_codigo";
     $sql .= "      inner join db_depart as b  on  b.coddepto = transfere.m80_coddepto";
     $sql .= "      inner join matestoquetipo  on  matestoquetipo.m81_codtipo = transfere.m80_codtipo";
     $sql .= "      inner join db_depart as a on  a.coddepto = matestoquetransf.m83_coddepto";
     $sql .= "      left join matestoqueinil   on  matestoqueinil.m86_matestoqueini = transfere.m80_codigo";
     $sql .= "      left join matestoqueini recebe on matestoqueinil.m86_matestoqueini = transfere.m80_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($m80_codigo!=null ){
         $sql2 .= " where transfere.m80_codigo = $m80_codigo ";
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

  function sql_query_movimentacoes ( $m80_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula    = "";

      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matestoqueini ";
    $sql .= "      inner join matestoquetipo     on m80_codtipo        = m81_codtipo          ";
    $sql .= "      inner join matestoqueinimei   on m82_matestoqueini  = m80_codigo           ";
    $sql .= "      inner join matestoqueinimeipm on m82_codigo         = m89_matestoqueinimei ";
    $sql .= "      inner join matestoqueitem     on m82_matestoqueitem = m71_codlanc          ";
    $sql .= "      inner join matestoque         on m71_codmatestoque  = m70_codigo           ";
    $sql2 = "";
    if ($dbwhere == "") {

      if ($m80_codigo != null) {
        $sql2 .= " where transfere.m80_codigo = $m80_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {

      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sql;
  }

  function sql_query_movimentacoes_gerais ( $m80_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula    = "";

      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matestoqueini ";
    $sql .= "      inner join matestoquetipo     on m80_codtipo        = m81_codtipo          ";
    $sql .= "      inner join matestoqueinimei   on m82_matestoqueini  = m80_codigo           ";
    $sql .= "      left join  db_usuarios         on m80_login          = id_usuario          ";
    $sql .= "      left join  db_depart deptousu  on m80_coddepto       = deptousu.coddepto   ";
    $sql .= "      inner join matestoqueitem     on m82_matestoqueitem = m71_codlanc          ";
    $sql .= "      inner join matestoqueinimeipm on m82_codigo         = m89_matestoqueinimei ";
    $sql .= "      inner join matestoque         on m71_codmatestoque  = m70_codigo           ";
    $sql .= "      inner join db_depart deptoest on m70_coddepto       = deptoest.coddepto    ";
    $sql .= "      inner join matmater           on m60_codmater       = m70_codmatmater      ";
    $sql2 = "";
    if ($dbwhere == "") {

      if ($m80_codigo != null) {
        $sql2 .= " where m80_codigo = $m80_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {

      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sql;
  }

  /**
   * O método monta uma query que vincula os tipos dos grupos com as movimentações do estoque
   *
   * @param string $m80_codigo
   * @param string $campos
   * @param string $ordem
   * @param string $dbwhere
   * @return string
   */
  function sql_query_movimentacoes_por_tipo_grupo($m80_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

   $sql = "select ";
   if ($campos != "*" ) {

     $campos_sql = split("#",$campos);
     $virgula    = "";

     for ($i = 0; $i < sizeof($campos_sql); $i++) {

       $sql    .= $virgula.$campos_sql[$i];
       $virgula = ",";
     }
   } else {
     $sql .= $campos;
   }

   $sql .= "from matestoqueini";
   $sql .= "   inner join db_depart on m80_coddepto     = coddepto and instit = ".db_getsession("DB_instit");
   $sql .= "   inner join db_almox  on db_almox.m91_depto = db_depart.coddepto                 ";
   $sql .= "   inner join matestoquetipo on m80_codtipo = m81_codtipo and m81_tipo <> 4        ";
	 $sql .= "   inner join matestoqueinimei on m80_codigo = m82_matestoqueini                   ";
	 $sql .= "   inner join matestoqueinimeipm on m82_codigo = m89_matestoqueinimei              ";
	 $sql .= "   inner join matestoqueitem on m71_codlanc = m82_matestoqueitem                   ";
	 $sql .= "   inner join matestoque on m70_codigo = m71_codmatestoque                         ";
	 $sql .= "   inner join matmater on m60_codmater = m70_codmatmater                           ";
	 $sql .= "   inner join matmatermaterialestoquegrupo on m68_matmater = m60_codmater          ";
	 $sql .= "   inner join materialestoquegrupo on m68_materialestoquegrupo = m65_sequencial    ";
	 $sql .= "   inner join materialtipogrupovinculo on m04_materialestoquegrupo = m65_sequencial";
	 $sql .= "   left  join matestoqueinil on matestoqueinil.m86_matestoqueini = matestoqueini.m80_codigo";
   $sql2 = "";

   if ($dbwhere == "") {

     if ($m80_codigo != null) {
       $sql2 .= " where m80_codigo = $m80_codigo ";
     }
   } else if ($dbwhere != "") {
     $sql2 = " where $dbwhere";
   }
   $sql .= $sql2;
   if ($ordem != null) {

     $sql        .= " order by ";
     $campos_sql  = split("#",$ordem);
     $virgula     = "";
     for ($i = 0; $i < sizeof($campos_sql); $i++) {

       $sql     .= $virgula.$campos_sql[$i];
       $virgula  = ",";
     }
   }
   return $sql;
  }

}