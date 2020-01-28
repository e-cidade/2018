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

//MODULO: Cemiterio
//CLASSE DA ENTIDADE lotecemit
class cl_lotecemit {
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
   var $cm23_i_codigo = 0;
   var $cm23_i_quadracemit = 0;
   var $cm23_i_lotecemit = 0;
   var $cm23_c_situacao = null;
   var $cm23_b_selecionado = 'f';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 cm23_i_codigo = int4 = Código Lote
                 cm23_i_quadracemit = int4 = Quadra Cemitério
                 cm23_i_lotecemit = int4 = Numero Lote
                 cm23_c_situacao = char(1) = Situação
                 cm23_b_selecionado = bool = Lote Selecionado
                 ";
   //funcao construtor da classe
   function cl_lotecemit() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lotecemit");
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
       $this->cm23_i_codigo = ($this->cm23_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm23_i_codigo"]:$this->cm23_i_codigo);
       $this->cm23_i_quadracemit = ($this->cm23_i_quadracemit == ""?@$GLOBALS["HTTP_POST_VARS"]["cm23_i_quadracemit"]:$this->cm23_i_quadracemit);
       $this->cm23_i_lotecemit = ($this->cm23_i_lotecemit == ""?@$GLOBALS["HTTP_POST_VARS"]["cm23_i_lotecemit"]:$this->cm23_i_lotecemit);
       $this->cm23_c_situacao = ($this->cm23_c_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["cm23_c_situacao"]:$this->cm23_c_situacao);
       $this->cm23_b_selecionado = ($this->cm23_b_selecionado == "f"?@$GLOBALS["HTTP_POST_VARS"]["cm23_b_selecionado"]:$this->cm23_b_selecionado);
     }else{
       $this->cm23_i_codigo = ($this->cm23_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm23_i_codigo"]:$this->cm23_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($cm23_i_codigo){
      $this->atualizacampos();
     if($this->cm23_i_quadracemit == null ){
       $this->erro_sql = " Campo Quadra Cemitério nao Informado.";
       $this->erro_campo = "cm23_i_quadracemit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm23_i_lotecemit == null ){
       $this->erro_sql = " Campo Numero Lote nao Informado.";
       $this->erro_campo = "cm23_i_lotecemit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm23_c_situacao == null ){
       $this->cm23_c_situacao = "D";
     }
     if($this->cm23_b_selecionado == null ){
       $this->cm23_b_selecionado = "false";
     }
     if($cm23_i_codigo == "" || $cm23_i_codigo == null ){
       $result = db_query("select nextval('lotecemit_cm23_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lotecemit_cm23_i_codigo_seq do campo: cm23_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->cm23_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from lotecemit_cm23_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $cm23_i_codigo)){
         $this->erro_sql = " Campo cm23_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cm23_i_codigo = $cm23_i_codigo;
       }
     }
     if(($this->cm23_i_codigo == null) || ($this->cm23_i_codigo == "") ){
       $this->erro_sql = " Campo cm23_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lotecemit(
                                       cm23_i_codigo
                                      ,cm23_i_quadracemit
                                      ,cm23_i_lotecemit
                                      ,cm23_c_situacao
                                      ,cm23_b_selecionado
                       )
                values (
                                $this->cm23_i_codigo
                               ,$this->cm23_i_quadracemit
                               ,$this->cm23_i_lotecemit
                               ,'$this->cm23_c_situacao'
                               ,'$this->cm23_b_selecionado'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastra os lotes das quadras para o cemiterio ($this->cm23_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastra os lotes das quadras para o cemiterio já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastra os lotes das quadras para o cemiterio ($this->cm23_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm23_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cm23_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10344,'$this->cm23_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1790,10344,'','".AddSlashes(pg_result($resaco,0,'cm23_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1790,10345,'','".AddSlashes(pg_result($resaco,0,'cm23_i_quadracemit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1790,10346,'','".AddSlashes(pg_result($resaco,0,'cm23_i_lotecemit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1790,10347,'','".AddSlashes(pg_result($resaco,0,'cm23_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1790,10348,'','".AddSlashes(pg_result($resaco,0,'cm23_b_selecionado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($cm23_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update lotecemit set ";
     $virgula = "";
     if(trim($this->cm23_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm23_i_codigo"])){
       $sql  .= $virgula." cm23_i_codigo = $this->cm23_i_codigo ";
       $virgula = ",";
       if(trim($this->cm23_i_codigo) == null ){
         $this->erro_sql = " Campo Código Lote nao Informado.";
         $this->erro_campo = "cm23_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm23_i_quadracemit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm23_i_quadracemit"])){
       $sql  .= $virgula." cm23_i_quadracemit = $this->cm23_i_quadracemit ";
       $virgula = ",";
       if(trim($this->cm23_i_quadracemit) == null ){
         $this->erro_sql = " Campo Quadra Cemitério nao Informado.";
         $this->erro_campo = "cm23_i_quadracemit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm23_i_lotecemit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm23_i_lotecemit"])){
       $sql  .= $virgula." cm23_i_lotecemit = $this->cm23_i_lotecemit ";
       $virgula = ",";
       if(trim($this->cm23_i_lotecemit) == null ){
         $this->erro_sql = " Campo Numero Lote nao Informado.";
         $this->erro_campo = "cm23_i_lotecemit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm23_c_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm23_c_situacao"])){
       $sql  .= $virgula." cm23_c_situacao = '$this->cm23_c_situacao' ";
       $virgula = ",";
     }
     if(trim($this->cm23_b_selecionado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm23_b_selecionado"])){
       $sql  .= $virgula." cm23_b_selecionado = '$this->cm23_b_selecionado' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($cm23_i_codigo!=null){
       $sql .= " cm23_i_codigo = $this->cm23_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cm23_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10344,'$this->cm23_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm23_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1790,10344,'".AddSlashes(pg_result($resaco,$conresaco,'cm23_i_codigo'))."','$this->cm23_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm23_i_quadracemit"]))
           $resac = db_query("insert into db_acount values($acount,1790,10345,'".AddSlashes(pg_result($resaco,$conresaco,'cm23_i_quadracemit'))."','$this->cm23_i_quadracemit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm23_i_lotecemit"]))
           $resac = db_query("insert into db_acount values($acount,1790,10346,'".AddSlashes(pg_result($resaco,$conresaco,'cm23_i_lotecemit'))."','$this->cm23_i_lotecemit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm23_c_situacao"]))
           $resac = db_query("insert into db_acount values($acount,1790,10347,'".AddSlashes(pg_result($resaco,$conresaco,'cm23_c_situacao'))."','$this->cm23_c_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm23_b_selecionado"]))
           $resac = db_query("insert into db_acount values($acount,1790,10348,'".AddSlashes(pg_result($resaco,$conresaco,'cm23_b_selecionado'))."','$this->cm23_b_selecionado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastra os lotes das quadras para o cemiterio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm23_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastra os lotes das quadras para o cemiterio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm23_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm23_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($cm23_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cm23_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10344,'$cm23_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1790,10344,'','".AddSlashes(pg_result($resaco,$iresaco,'cm23_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1790,10345,'','".AddSlashes(pg_result($resaco,$iresaco,'cm23_i_quadracemit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1790,10346,'','".AddSlashes(pg_result($resaco,$iresaco,'cm23_i_lotecemit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1790,10347,'','".AddSlashes(pg_result($resaco,$iresaco,'cm23_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1790,10348,'','".AddSlashes(pg_result($resaco,$iresaco,'cm23_b_selecionado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from lotecemit
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cm23_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cm23_i_codigo = $cm23_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastra os lotes das quadras para o cemiterio nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cm23_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastra os lotes das quadras para o cemiterio nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cm23_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cm23_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lotecemit";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $cm23_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from lotecemit ";
     $sql .= "      inner join quadracemit  on  quadracemit.cm22_i_codigo = lotecemit.cm23_i_quadracemit";
     $sql .= "      inner join cemiterio  on  cemiterio.cm14_i_codigo = quadracemit.cm22_i_cemiterio";
     $sql .= " left join cemiteriorural on cm14_i_codigo = cm16_i_cemiterio";
     $sql .= " left join cemiteriocgm on cm14_i_codigo = cm15_i_cemiterio";
     $sql .= " left join cgm on z01_numcgm = cm15_i_cgm";

     $sql2 = "";
     if($dbwhere==""){
       if($cm23_i_codigo!=null ){
         $sql2 .= " where lotecemit.cm23_i_codigo = $cm23_i_codigo ";
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
   function sql_query_file ( $cm23_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from lotecemit ";
     $sql2 = "";
     if($dbwhere==""){
       if($cm23_i_codigo!=null ){
         $sql2 .= " where lotecemit.cm23_i_codigo = $cm23_i_codigo ";
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
   * Montamos uma query para atualizar a situação do lote
   *
   * @param  integer $iCodigoLotecemit
   * @param  string  $sSituacao
   *
   * @return string                   query
   */
  public function sql_query_atualiza_situacao( $iCodigoLotecemit, $sSituacao ){

    $sUpdateLote  = " update lotecemit                           ";
    $sUpdateLote .= "    set cm23_c_situacao = '{$sSituacao}'    ";
    $sUpdateLote .= "  where cm23_i_codigo = {$iCodigoLotecemit} ";

    return $sUpdateLote;
  }
}
?>