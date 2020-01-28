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
//CLASSE DA ENTIDADE matestoque
class cl_matestoque {
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
   var $m70_codigo = 0;
   var $m70_codmatmater = 0;
   var $m70_coddepto = 0;
   var $m70_quant = 0;
   var $m70_valor = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 m70_codigo = int8 = Codigo sequencial do registro 
                 m70_codmatmater = int8 = Código do material 
                 m70_coddepto = int4 = Departamento 
                 m70_quant = float8 = Quantidade em estoque 
                 m70_valor = float8 = Valor em estoque 
                 ";
   //funcao construtor da classe
   function cl_matestoque() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matestoque");
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
       $this->m70_codigo = ($this->m70_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m70_codigo"]:$this->m70_codigo);
       $this->m70_codmatmater = ($this->m70_codmatmater == ""?@$GLOBALS["HTTP_POST_VARS"]["m70_codmatmater"]:$this->m70_codmatmater);
       $this->m70_coddepto = ($this->m70_coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["m70_coddepto"]:$this->m70_coddepto);
       $this->m70_quant = ($this->m70_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["m70_quant"]:$this->m70_quant);
       $this->m70_valor = ($this->m70_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["m70_valor"]:$this->m70_valor);
     }else{
       $this->m70_codigo = ($this->m70_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m70_codigo"]:$this->m70_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($m70_codigo){
      $this->atualizacampos();
     if($this->m70_codmatmater == null ){
       $this->erro_sql = " Campo Código do material nao Informado.";
       $this->erro_campo = "m70_codmatmater";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m70_coddepto == null ){
       $this->erro_sql = " Campo Departamento nao Informado.";
       $this->erro_campo = "m70_coddepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m70_quant == null ){
       $this->erro_sql = " Campo Quantidade em estoque nao Informado.";
       $this->erro_campo = "m70_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m70_valor == null ){
       $this->erro_sql = " Campo Valor em estoque nao Informado.";
       $this->erro_campo = "m70_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m70_codigo == "" || $m70_codigo == null ){
       $result = db_query("select nextval('matestoque_m70_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matestoque_m70_codigo_seq do campo: m70_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->m70_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from matestoque_m70_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $m70_codigo)){
         $this->erro_sql = " Campo m70_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m70_codigo = $m70_codigo;
       }
     }
     if(($this->m70_codigo == null) || ($this->m70_codigo == "") ){
       $this->erro_sql = " Campo m70_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matestoque(
                                       m70_codigo 
                                      ,m70_codmatmater 
                                      ,m70_coddepto 
                                      ,m70_quant 
                                      ,m70_valor 
                       )
                values (
                                $this->m70_codigo 
                               ,$this->m70_codmatmater 
                               ,$this->m70_coddepto 
                               ,$this->m70_quant 
                               ,$this->m70_valor 
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Estoque de materiais ($this->m70_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Estoque de materiais já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Estoque de materiais ($this->m70_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m70_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m70_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6269,'$this->m70_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1019,6269,'','".AddSlashes(pg_result($resaco,0,'m70_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1019,6270,'','".AddSlashes(pg_result($resaco,0,'m70_codmatmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1019,6271,'','".AddSlashes(pg_result($resaco,0,'m70_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1019,6272,'','".AddSlashes(pg_result($resaco,0,'m70_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1019,6273,'','".AddSlashes(pg_result($resaco,0,'m70_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($m70_codigo=null) {
      $this->atualizacampos();
     $sql = " update matestoque set ";
     $virgula = "";
     if(trim($this->m70_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m70_codigo"])){
       $sql  .= $virgula." m70_codigo = $this->m70_codigo ";
       $virgula = ",";
       if(trim($this->m70_codigo) == null ){
         $this->erro_sql = " Campo Codigo sequencial do registro nao Informado.";
         $this->erro_campo = "m70_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m70_codmatmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m70_codmatmater"])){
       $sql  .= $virgula." m70_codmatmater = $this->m70_codmatmater ";
       $virgula = ",";
       if(trim($this->m70_codmatmater) == null ){
         $this->erro_sql = " Campo Código do material nao Informado.";
         $this->erro_campo = "m70_codmatmater";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m70_coddepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m70_coddepto"])){
       $sql  .= $virgula." m70_coddepto = $this->m70_coddepto ";
       $virgula = ",";
       if(trim($this->m70_coddepto) == null ){
         $this->erro_sql = " Campo Departamento nao Informado.";
         $this->erro_campo = "m70_coddepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m70_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m70_quant"])){
       $sql  .= $virgula." m70_quant = $this->m70_quant ";
       $virgula = ",";
       if(trim($this->m70_quant) == null ){
         $this->erro_sql = " Campo Quantidade em estoque nao Informado.";
         $this->erro_campo = "m70_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m70_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m70_valor"])){
       $sql  .= $virgula." m70_valor = $this->m70_valor ";
       $virgula = ",";
       if(trim($this->m70_valor) == null ){
         $this->erro_sql = " Campo Valor em estoque nao Informado.";
         $this->erro_campo = "m70_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m70_codigo!=null){
       $sql .= " m70_codigo = $this->m70_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m70_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6269,'$this->m70_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m70_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1019,6269,'".AddSlashes(pg_result($resaco,$conresaco,'m70_codigo'))."','$this->m70_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m70_codmatmater"]))
           $resac = db_query("insert into db_acount values($acount,1019,6270,'".AddSlashes(pg_result($resaco,$conresaco,'m70_codmatmater'))."','$this->m70_codmatmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m70_coddepto"]))
           $resac = db_query("insert into db_acount values($acount,1019,6271,'".AddSlashes(pg_result($resaco,$conresaco,'m70_coddepto'))."','$this->m70_coddepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m70_quant"]))
           $resac = db_query("insert into db_acount values($acount,1019,6272,'".AddSlashes(pg_result($resaco,$conresaco,'m70_quant'))."','$this->m70_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m70_valor"]))
           $resac = db_query("insert into db_acount values($acount,1019,6273,'".AddSlashes(pg_result($resaco,$conresaco,'m70_valor'))."','$this->m70_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Estoque de materiais nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m70_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Estoque de materiais nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m70_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m70_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($m70_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m70_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6269,'$m70_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1019,6269,'','".AddSlashes(pg_result($resaco,$iresaco,'m70_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1019,6270,'','".AddSlashes(pg_result($resaco,$iresaco,'m70_codmatmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1019,6271,'','".AddSlashes(pg_result($resaco,$iresaco,'m70_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1019,6272,'','".AddSlashes(pg_result($resaco,$iresaco,'m70_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1019,6273,'','".AddSlashes(pg_result($resaco,$iresaco,'m70_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matestoque
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m70_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m70_codigo = $m70_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Estoque de materiais nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m70_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Estoque de materiais nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m70_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m70_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:matestoque";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function atualizaEstoque($iCodMater, $iCodDepto, $iCodMatTipo, $nQuantidade, $dData, $nValor, $aAuxiliar, $sErroMsg,$iCodMatestoqueitem, $iCodEstoque='') {
    // Declara Variaveis
    $sErro = "";
    $lErro = false;

    // Qual tipo de Atualizacao
    switch ($iCodMatTipo) {

      //
      // ATENDIMENTO DE REQUISICAO
      //
      case 17:
      $lErro = $this->atualizaEstoqueAtendRequi($iCodMater, $iCodDepto, $aAuxiliar["codatenditem"], $aAuxiliar["codmatestoqueini"], $nQuantidade, $dData, $nValor, $sErro,$iCodMatestoqueitem, $iCodEstoque);
      break; // FIM ATENDIMENTO REQUISICAO

      default:
      $sErro = "Tipo de Atualizacao de Estoque $iCodMatTipo não implementada! (Contate Suporte)";
      $lErro = true;
      break;

    }

    $sErroMsg = $sErro;

    return $lErro;

    //
  }
   function atualizaEstoqueAtendRequi($iCodMater, $iCodDepto, $iCodAtendItem, $iCodMatEstoqueIni, $nQuantidade, $dData, $nValor, &$sErro,$iCodMatestoqueitem, $iCodEstoque = '') {
    // Declara Variaveis
    $lErro  = false;
    $lDebug = false; // Para debugar metodo

    // Instancia Classes
    $oMatEstoqueItem       = new cl_matestoqueitem;
    $oMatEstoqueIniMEI     = new cl_matestoqueinimei;
    $oMatEstoqueIniMeiARI  = new cl_matestoqueinimeiari;
    $oAtendRequiItemMEI    = new cl_atendrequiitemmei;

    // Busca Estoque para Atualização
    $sCampos    = " distinct m70_codigo as codestoque, m70_quant as qtdestoque, m70_valor, matestoqueitem.*";
    $sWhere     = "m70_codmatmater=$iCodMater and m71_quantatend < m71_quant";
    if ($iCodMatestoqueitem!="" ){
      $sWhere  .= " and m71_codlanc = $iCodMatestoqueitem ";
    }

    if ($iCodEstoque != null ){
      $sWhere  .= " and m70_coddepto = {$iCodEstoque}";
    }

    $sqlEstoque = $this->sql_query_retitem(null,$sCampos, "m71_data, m71_codlanc", $sWhere,$iCodDepto);
    if($lDebug) {
      echo "Estoque 01: $sqlEstoque <br>";
    }
   //   echo "<br><br> $sqlEstoque <br>";

    $resEstoque = $this->sql_record($sqlEstoque);
    $numrows    = $this->numrows;
    if ($numrows==0){
        $lErro = true;
        $sErro = "Estoque Nao Disponivel";

    }

    if($lDebug) {
      db_criatabela($resEstoque);
    }

    $nQtdResta  = $nQuantidade;
    $nQtdInc    = 0;
    $lFim       = false;
    $nQuantReal = 0;

    // Processa Entradas para abater a Saida da Requisicao (matestoqueitem)
    for ($w = 0; $w < $numrows; $w ++) {
      // Cria Variaveis do ResultSet
      $codestoque     = pg_result($resEstoque, $w, "codestoque");
      $m71_quant      = pg_result($resEstoque, $w, "m71_quant");
      $m71_quantatend = pg_result($resEstoque, $w, "m71_quantatend");
      $m71_valor      = pg_result($resEstoque, $w, "m71_valor");
      $m71_codlanc    = pg_result($resEstoque, $w, "m71_codlanc");

      // Calcula Valor da MatEstoqueItem (QtdAtendida)
      $nValorInc    = 0;
      $nQuantAtual  = $m71_quant;                   // Salva Quantidade Atual
      $nQuantReal   = $m71_quant - $m71_quantatend; // Calcula Quantidade Real

      // Se o primeiro lancamento pode ser atendida a quantidade, finaliza
      if($nQtdResta <= $nQuantReal) {
        $oMatEstoqueItem->m71_quantatend = $m71_quantatend + $nQtdResta;
        $nQtdInc   = $nQtdResta;
        $nValorInc = $m71_valor / $nQuantAtual;
        $lFim      = true;
        // Caso Contrario abate o saldo (qtd - qtdatend) disponivel e continua a processar os lancamentos
      } else {
        $oMatEstoqueItem->m71_quantatend = $m71_quantatend + $nQuantReal;
        $nQtdInc   = $nQuantReal;
        $nValorInc = $m71_valor / $nQuantAtual;
        $nQtdResta = $nQtdResta - $nQuantReal;
      }

      if($lDebug) {
        echo "<br>Linha=$w<br>nValorInc=$nValorInc<br>nQuantAtual=$nQuantAtual<br>m71_quant=$m71_quant<br>nQtdInc=$nQtdInc<br>nQtdResta=$nQtdResta" ;
      }

      // Atualiza MATESTOQUEITEM
      $oMatEstoqueItem->m71_valor   = $m71_valor;
      $oMatEstoqueItem->m71_quant   = $nQuantAtual;
      $oMatEstoqueItem->m71_codlanc = $m71_codlanc;
      $oMatEstoqueItem->alterar($m71_codlanc);

      // Informacoes da Classe
      $sErro = $oMatEstoqueItem->erro_msg;
      $lErro = ($oMatEstoqueItem->erro_status == 0);

      if($lErro) {
        break;
      }

      // Gera AtentRequiItemMEI (Ligacao AtendRequiItem e MatEstoqueItem)
      if (!$lErro) {
        $oAtendRequiItemMEI->m44_codatendreqitem   = $iCodAtendItem;
        $oAtendRequiItemMEI->m44_codmatestoqueitem = $m71_codlanc;
        $oAtendRequiItemMEI->m44_quant             = $nQtdInc;
        $oAtendRequiItemMEI->incluir(null);
        // Informacoes da Classe
        $sErro =  $oAtendRequiItemMEI->erro_msg;
        $lErro = ($oAtendRequiItemMEI->erro_status == 0);

        if($lErro) {
          break;
        }
      }

      // Gera MatEstoqueIniMEI (Ligacao MatEstoqueIni e MatEstoqueItem)
      if (!$lErro) {
        $oMatEstoqueIniMEI->m82_matestoqueitem = $m71_codlanc;
        $oMatEstoqueIniMEI->m82_matestoqueini  = $iCodMatEstoqueIni;
        $oMatEstoqueIniMEI->m82_quant          = $nQtdInc;
        $oMatEstoqueIniMEI->incluir(@$m82_codigo);
        // Informacoes da Classe
        $sErro =  $oMatEstoqueIniMEI->erro_msg;
        $lErro = ($oMatEstoqueIniMEI->erro_status == 0);

        if($lErro) {
          break;
        }

        $iCodMatEstoqueIniMei = $oMatEstoqueIniMEI->m82_codigo;
      }

      // Gera MatEstoqueIniMeiARI (Ligacao MatEstoqueIniMei e AtendRequiItem)
      if (!$lErro) {
        $oMatEstoqueIniMeiARI->m49_codatendrequiitem   = $iCodAtendItem;
        $oMatEstoqueIniMeiARI->m49_codmatestoqueinimei = $iCodMatEstoqueIniMei;
        $oMatEstoqueIniMeiARI->incluir(null);
        // Informacoes da Classe
        $sErro =  $oMatEstoqueIniMeiARI->erro_msg;
        $lErro = ($oMatEstoqueIniMeiARI->erro_status == 0);

        if($lErro) {
          break;
        }
      }

      if ($lFim) {
        break;
      }

    } // fim for

    if(!$lErro) {
      //
      // Atualiza MATESTOQUE
      //
      $sCampos       = "sum(m71_quant) as quantidade, sum(m71_valor) as valor, sum(m71_quantatend) as quantatend";
      $sWhere        = "m71_codmatestoque = $codestoque";
      $sqlMatEstoque = $oMatEstoqueItem->sql_query(null, $sCampos, null, $sWhere);
      $resMatEstoque = db_query($sqlMatEstoque);

      if($lDebug) {
        echo "<br>SqlMatEstoqueItem: $sqlMatEstoque<br>";
        db_criatabela($resMatEstoque);

        echo "<br>Linhas:".pg_numrows($resMatEstoque)."<br>";
      }

      if(pg_numrows($resMatEstoque) > 0) {
        $quantidade = pg_result($resMatEstoque, 0, "quantidade");
        $valor      = pg_result($resMatEstoque, 0, "valor");
        $quantatend = pg_result($resMatEstoque, 0, "quantatend");

        // Calcula Quantidade Real Disponivel
        $nQtdReal   = $quantidade - $quantatend;
        $nValorReal = round($valor / $quantidade, 2) * $nQtdReal;

        if($lDebug) {
          echo "<br>QTD REAL: $nQtdReal<br>";
        }

        // Setando Valores
        $this->m70_codigo = "$codestoque";
        $this->m70_quant  = "$nQtdReal";

        // Calcula novo Valor Financeiro em Estoque, considerando o novo atendimento
        $this->m70_valor  = "$nValorReal";
        $this->alterar($codestoque);
        // Informacoes da Classe
        $sErro =  $this->erro_msg;
        $lErro = ($this->erro_status == 0);

        if($lDebug) {
          var_dump($this);
        }

      } else {
        $lErro = true;
        $sErro = "Estoque Nao Disponivel";
      }
    }

    if($lDebug) {
      echo "Erro $sErro<br>";
      $res = db_query("select * from matestoque where m70_codigo = $codestoque");
      db_criatabela($res);
      db_fim_transacao(true);
      //die("<br><br>Estoque 01: $sqlEstoque");
    }

    // Retorna mensagem de erro
    return $lErro;
  }

   function sql_query($m70_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matestoque ";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = matestoque.m70_coddepto";
    $sql .= "      inner join matmater  on  matmater.m60_codmater = matestoque.m70_codmatmater";
    $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($m70_codigo != null) {
        $sql2 .= " where matestoque.m70_codigo = $m70_codigo ";
      }
    } else
    if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }


  function sql_query_pcmater($m70_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matestoque ";
    $sql .= "      inner join db_depart   on  db_depart.coddepto         = matestoque.m70_coddepto";
    $sql .= "      inner join matmater    on  matmater.m60_codmater      = matestoque.m70_codmatmater";
    $sql .= "      inner join matunid     on  matunid.m61_codmatunid     = matmater.m60_codmatunid";
	  $sql .= "       left join transmater  on transmater.m63_codmatmater  = matmater.m60_codmater";
    $sql .= "       left join pcmater     on pcmater.pc01_codmater       = transmater.m63_codpcmater";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($m70_codigo != null) {
        $sql2 .= " where matestoque.m70_codigo = $m70_codigo ";
      }
    } else
    if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }



   function sql_query_almox($m70_codigo = null, $campos = "*", $ordem = null, $dbwhere = "", $group_by = "", $consulta = "",$departamento = "") {

    /*
    >> * É necessario declarar as classes no programa q usa este metodo

    include(modification("db_matparam_classe.php"));
    include(modification("db_db_departorg_classe.php"));
    include(modification("db_db_almoxdepto_classe.php"));
    */

    global $permissao;
    $clmatparam = new cl_matparam;
    $cldb_departorg = new cl_db_departorg;
    $cldb_almoxdepto = new cl_db_almoxdepto;
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matestoque ";
    if ($departamento==""){
			$sql .= "      inner join matmater                on matmater.m60_codmater      = matestoque.m70_codmatmater";
			$sql .= "      inner join matunid                 on matunid.m61_codmatunid     = matmater.m60_codmatunid";
			$sql .= "      inner join db_depart               on db_depart.coddepto         = matestoque.m70_coddepto";
			$sql .= "      inner join db_departorg            on db_departorg.db01_coddepto = db_depart.coddepto and";
      $sql .= "                                            db_departorg.db01_anousu   = ".db_getsession("DB_anousu");
			$sql .= "      inner join orcunidade              on orcunidade.o41_orgao       = db_departorg.db01_orgao   and";
      $sql .= "                                            orcunidade.o41_unidade     = db_departorg.db01_unidade and";
      $sql .= "                                            orcunidade.o41_anousu      = db_departorg.db01_anousu  and";
      $sql .= "                                            orcunidade.o41_instit      = ".db_getsession("DB_instit");
      $sql .= "      inner join orcorgao                on orcorgao.o40_orgao         = orcunidade.o41_orgao";
      $sql .= "                                        and orcorgao.o40_anousu        = orcunidade.o41_anousu";
    }

    $where = "";
		$depto_atual = "";
    if ($departamento!=""){
      $depto_atual = $departamento;
    } else if (1==2) {
      $depto_atual = db_getsession("DB_coddepto");
    }
    if ($consulta == "true") {
      $permissao = db_permissaomenu(db_getsession("DB_anousu"), 480, 4390);
    } else {
      $permissao = "false";
    }
    if ($permissao == "true") {
    } else {
      $result_param = $clmatparam->sql_record($clmatparam->sql_query_file());
      if ($clmatparam->numrows) {
        global $m90_tipocontrol;
        global $db01_orgao;
        global $m91_codigo;
        global $m92_codalmox;
        db_fieldsmemory($result_param, 0);
        if ($m90_tipocontrol == 'S') {
          $result_orgao = $cldb_departorg->sql_record($cldb_departorg->sql_query_file($depto_atual,db_getsession("DB_anousu")));
          if ($cldb_departorg->numrows) {
            db_fieldsmemory($result_orgao, 0);
            $where = " db01_orgao = $db01_orgao";
             $sql .= "      inner join db_departorg on db_departorg.db01_coddepto = matestoque.m70_coddepto and db_departorg.db01_anousu=".db_getsession("DB_anousu");
            //$sql .= "      inner join orcorgao  on orcorgao.o40_orgao = db_departorg.db01_orgao and orcorgao.o40_anousu = db_departorg.db01_anousu";
          }
        } elseif ($m90_tipocontrol == 'G') {
          $result_almox=$cldb_almoxdepto->sql_record($cldb_almoxdepto->sql_query_file(null,$depto_atual));
          if ($cldb_almoxdepto->numrows>0){
            db_fieldsmemory($result_almox,0);
            $where = "m91_codigo = $m92_codalmox";
            $sql .= "      inner join db_almoxdepto on m92_depto = db_depart.coddepto ";
            $sql .= "      inner join db_almox on m91_codigo = m92_codalmox";
          }else{
            $where = "1=2";
          }
        } elseif ($m90_tipocontrol == 'D') {

        	if (empty($depto_atual)) {
        		$depto_atual = db_getsession("DB_coddepto");
        	}
          $where = " m70_coddepto = $depto_atual ";

        } elseif ($m90_tipocontrol == 'F' and $depto_atual != "") {
            $where = " m70_coddepto = $depto_atual";
        } elseif ($m90_tipocontrol == 'F' and 1==2) {
          $result_almox = $cldb_almoxdepto->sql_record($cldb_almoxdepto->sql_query_file(null, $depto_atual));
          if ($cldb_almoxdepto->numrows > 0) {
            db_fieldsmemory($result_almox,0);
            $where = " m91_codigo = $m92_codalmox";
            $sql .= "      inner join db_almoxdepto on m92_depto = db_depart.coddepto ";
            $sql .= "      inner join db_almox on m91_codigo = m92_codalmox";
          }else{
            $where = "1=2";
          }

        }
      }
    }
    $sql2 = " where ";
    $and  = "";
    if ($where != "") {
      $sql2.= " $where ";
      $and  = " and ";
    }
    if ($dbwhere == "") {
      if ($m70_codigo != null) {
        $sql2.= $and . " matestoque.m70_codigo = $m70_codigo ";
        $and  = " and ";
      }
    } else {
      if ($dbwhere != "") {
        $sql2.= $and . " $dbwhere";
        $and  = " and ";
      }
    }
    //	$sql2 .= $and . " m95_codlanc is null ";
    //	if ($group_by != "") {
      //		$sql2 .= " $group_by";
    //	}
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
   function sql_query_almoxitem($iCodMater = null, $campos = "*", $ordem = null, $dbwhere = "", $group_by = "", $consulta = "") {

    /*
    >> * É necessario declarar as classes no programa q usa este metodo

    include(modification("db_matparam_classe.php"));
    include(modification("db_db_departorg_classe.php"));
    include(modification("db_db_almoxdepto_classe.php"));
    */

    global $permissao;
    $clmatparam = new cl_matparam;
    $cldb_departorg = new cl_db_departorg;
    $cldb_almoxdepto = new cl_db_almoxdepto;
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matestoque ";
    $sql .= "      inner join matestoqueitem on matestoqueitem.m71_codmatestoque = matestoque.m70_codigo";
    //$sql .= "      inner join matmater  on  matmater.m60_codmater = matestoque.m70_codmatmater";
    //$sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = matestoque.m70_coddepto";
    //$sql .= "      inner join db_departorg  on db_departorg.db01_coddepto = db_depart.coddepto and db_departorg.db01_anousu=".db_getsession("DB_anousu");
    //$sql .= "      inner join orcorgao  on orcorgao.o40_orgao = db_departorg.db01_orgao
    //and orcorgao.o40_anousu = db_departorg.db01_anousu
    //and orcorgao.o40_instit = " . db_getsession("DB_instit");
    $where = "";
    $depto_atual = db_getsession("DB_coddepto");
    if ($consulta == "true") {
      $permissao = db_permissaomenu(db_getsession("DB_anousu"), 480, 4390);
    } else {
      $permissao = "false";
    }
    if ($permissao == "true") {
    } else {
      $result_param = $clmatparam->sql_record($clmatparam->sql_query_file());
      if ($clmatparam->numrows) {
        global $m90_tipocontrol;
        global $db01_orgao;
        global $m91_codigo;
        global $m92_codalmox;
        db_fieldsmemory($result_param, 0);
        if ($m90_tipocontrol == 'S') {
          $result_orgao = $cldb_departorg->sql_record($cldb_departorg->sql_query_file($depto_atual,db_getsession("DB_anousu")));
          if ($cldb_departorg->numrows) {
            db_fieldsmemory($result_orgao, 0);
            $where = "db_departorg.db01_orgao = $db01_orgao";
            $sql .= "      inner join db_departorg as db_deptoorg on db_deptoorg.db01_coddepto = db_depart.coddepto and db_deptoorg.db01_anousu=".db_getsession("DB_anousu");
            $sql .= "      inner join orcorgao     as orcorg      on orcorg.o40_orgao = db_deptoorg.db01_orgao and orcorg.o40_anousu = db_deptoorg.db01_anousu";

          }
        } else if ($m90_tipocontrol == 'G') {
          $result_almox=$cldb_almoxdepto->sql_record($cldb_almoxdepto->sql_query(null,$depto_atual));
          if ($cldb_almoxdepto->numrows>0){
            db_fieldsmemory($result_almox,0);
            $where = " m91_codigo = $m92_codalmox";
            $sql .= "      inner join db_almoxdepto on m92_depto = db_depart.coddepto ";
            $sql .= "      inner join db_almox on m91_codigo = m92_codalmox";
          }else{
            $where = "1=2";
          }
        } else	if ($m90_tipocontrol == 'D') {
          $where = " m70_coddepto = $depto_avtual ";
        } else	if ($m90_tipocontrol == 'F') {
					$where = " m70_coddepto = $depto_atual and m70_codmatmater = $iCodMater";
        } else	if ($m90_tipocontrol == 'F' and 1==2) {
          $result_almox = $cldb_almoxdepto->sql_record($cldb_almoxdepto->sql_query_file(null, $depto_atual));
          if ($cldb_almoxdepto->numrows > 0) {
            db_fieldsmemory($result_almox,0);
            $where = " m91_codigo = $m92_codalmox";
            $sql .= "      inner join db_almoxdepto on m92_depto = db_depart.coddepto ";
            $sql .= "      inner join db_almox on m91_codigo = m92_codalmox";
          }else{
            $where = "1=2";
          }

        }
      }
    }
    if ($where != "") {
      $sql2 = " where $where ";
    } else {
      $sql2 = " where 1=1 "; //so para teste depois arrumar
    }
    if ($dbwhere == "") {
      if ($m70_codigo != null) {
        $sql2 .= " and  matestoque.m70_codigo = $m70_codigo ";
      }
    } else
    if ($dbwhere != "") {
      $sql2 .= " and $dbwhere";
    }
    if ($group_by != "") {
      $sql2 .= " $group_by";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
   function sql_query_ent($m70_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matestoque ";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = matestoque.m70_coddepto";
    $sql .= "      inner join matmater  on  matmater.m60_codmater = matestoque.m70_codmatmater";
    $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
    $sql .= "      inner join matestoqueitem on m71_codmatestoque = m70_codigo";
    $sql .= "      inner join matestoqueitemnota on m74_codmatestoqueitem = m71_codlanc";
    $sql .= "      inner join matestoqueitemoc on m73_codmatestoqueitem = m71_codlanc";
    $sql .= "      inner join matordemitem on m52_codlanc = m73_codmatordemitem";
    $sql .= "      inner join empnota on e69_codnota = m74_codempnota";
    $sql .= "      inner join matordem on m52_codordem = m51_codordem";
    $sql .= "      inner join empnotaele on e69_codnota = e70_codnota";
    $sql .= "      inner join cgm on z01_numcgm = m51_numcgm";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($m70_codigo != null) {
        $sql2 .= " where matestoque.m70_codigo = $m70_codigo ";
      }
    } else
    if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
   function sql_query_file($m70_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matestoque ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($m70_codigo != null) {
        $sql2 .= " where matestoque.m70_codigo = $m70_codigo ";
      }
    } else
    if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
   function sql_query_item($m70_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matestoque ";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = matestoque.m70_coddepto";
    $sql .= "      inner join matmater  on  matmater.m60_codmater = matestoque.m70_codmatmater";
    $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
    $sql .= "      inner join matestoqueitem  on  matestoque.m70_codigo = matestoqueitem.m71_codmatestoque";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($m70_codigo != null) {
        $sql2 .= " where matestoque.m70_codigo = $m70_codigo ";
      }
    } else
    if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
   function sql_query_item_grupo($m70_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matestoque ";
    $sql .= "      inner join db_depart       on  db_depart.coddepto              = matestoque.m70_coddepto						";
    $sql .= "      inner join matmater        on  matmater.m60_codmater           = matestoque.m70_codmatmater				";
    $sql .= "      inner join matunid         on  matunid.m61_codmatunid          = matmater.m60_codmatunid						";
    $sql .= "      inner join matestoqueitem  on matestoqueitem.m71_codmatestoque = matestoque.m70_codigo             ";
    $sql .= "      left  join matmatermaterialestoquegrupo on m68_matmater                          = m60_codmater    ";
    $sql .= "      left  join materialestoquegrupo         on materialestoquegrupo.m65_sequencial   = matmatermaterialestoquegrupo.m68_materialestoquegrupo  ";
    $sql .= "      left  join materialestoquegrupoconta    on materialestoquegrupoconta. m66_materialestoquegrupo  = materialestoquegrupo.m65_sequencial     ";
    $sql .= "      left  join conplano                     on conplano.c60_codcon = materialestoquegrupoconta.m66_codcon                                     ";
    $sql .= "      																				and conplano.c60_anousu = materialestoquegrupoconta.m66_anousu                                     ";
    $sql .= "      left  join conplanoreduz                on conplanoreduz.c61_codcon = conplano.c60_codcon                                                 ";
    $sql .= "      																				and conplanoreduz.c61_anousu = conplano.c60_anousu                                                 ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($m70_codigo != null) {
        $sql2 .= " where matestoque.m70_codigo = $m70_codigo ";
      }
    } else
    if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

   function sql_query_org($m70_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matestoque ";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = matestoque.m70_coddepto";
    $sql .= "      inner join db_departorg  on db01_coddepto = db_depart.coddepto and db01_anousu=".db_getsession("DB_anousu");
    $sql .= "      inner join orcorgao  on orcorgao.o40_orgao = db_departorg.db01_orgao and orcorgao.o40_anousu = db_departorg.db01_anousu";
    $sql .= "      inner join matmater  on  matmater.m60_codmater = matestoque.m70_codmatmater";
    $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($m70_codigo != null) {
        $sql2 .= " where matestoque.m70_codigo = $m70_codigo ";
      }
    } else
    if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
   function sql_query_retitem($m70_codigo = null, $campos = "*", $ordem = null, $dbwhere = "",$depto=null) {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matestoque ";
    $sql .= "      inner join matestoqueitem on m71_codmatestoque = m70_codigo";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = matestoque.m70_coddepto";
    $sql .= "      inner join matmater  on  matmater.m60_codmater = matestoque.m70_codmatmater";
    $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
    $sql .= "      inner join db_almox on m91_depto = db_depart.coddepto ";
    $sql .= "      inner join db_almoxdepto on m91_codigo = m92_codalmox";
    $sql2 = "";
    if ($depto==null){
      $depto = db_getsession("DB_coddepto");
    }
    $sql2 .= " where m92_depto = $depto ";
    if ($dbwhere == "") {
      if ($m70_codigo != null) {
        $sql2 .= " and matestoque.m70_codigo = $m70_codigo ";
      }
    } else
    if ($dbwhere != "") {
      $sql2 .= " and $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }


   function sql_query_saida($m70_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matestoque ";
    $sql .= "      inner join db_depart      on db_depart.coddepto     = matestoque.m70_coddepto";
    $sql .= "      inner join matmater       on matmater.m60_codmater  = matestoque.m70_codmatmater";
    $sql .= "      inner join matunid        on matunid.m61_codmatunid = matmater.m60_codmatunid";
    $sql .= "      inner join matestoqueitem on matestoqueitem.m71_codmatestoque = matestoque.m70_codigo";
    $sql .= "      inner join matestoqueinimei on matestoqueinimei.m82_matestoqueitem = matestoqueitem.m71_codlanc";
    $sql .= "      inner join matestoqueinimeipm on matestoqueinimeipm.m89_matestoqueinimei = matestoqueinimei.m82_codigo";
    $sql .= "      inner join matestoqueini    on matestoqueini.m80_codigo            = matestoqueinimei.m82_matestoqueini";
    $sql .= "      inner join matestoquetipo   on matestoquetipo.m81_codtipo          = matestoqueini.m80_codtipo";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($m70_codigo != null) {
        $sql2 .= " where matestoque.m70_codigo = $m70_codigo ";
      }
    } else
    if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  /**
   * Busca matarias com almoxarifado
   *
   * @param string $sCampos
   * @param string $sOrdem
   * @param string $sWhere
   * @access public
   * @return string
   */
  public function sql_query_almoxarifado($sCampos = '*', $sOrdem = null, $sWhere = null) {

    $sSql  = "select $sCampos                                                      ";
    $sSql .= "  from matestoque                                                    ";
    $sSql .= "       inner join matestoqueitem on m71_codmatestoque = m70_codigo   ";
    $sSql .= "                                and m71_servico is false             ";
    $sSql .= "       inner join db_depart      on coddepto          = m70_coddepto ";
    $sSql .= "       inner join db_almox       on m91_depto         = coddepto     ";

    if (!empty($sWhere)) {
      $sSql .= " where $sWhere ";
    }

    if (!empty($sOrdem)) {
      $sSql .= " order by $sOrdem ";
    }

    return $sSql;
  }

}