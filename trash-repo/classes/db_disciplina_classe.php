<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: educa��o
//CLASSE DA ENTIDADE disciplina
class cl_disciplina {
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
   var $ed12_i_codigo = 0;
   var $ed12_i_ensino = 0;
   var $ed12_i_caddisciplina = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed12_i_codigo = int8 = C�digo
                 ed12_i_ensino = int8 = Ensino
                 ed12_i_caddisciplina = int8 = Disciplina
                 ";
   //funcao construtor da classe
   function cl_disciplina() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("disciplina");
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
       $this->ed12_i_codigo = ($this->ed12_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed12_i_codigo"]:$this->ed12_i_codigo);
       $this->ed12_i_ensino = ($this->ed12_i_ensino == ""?@$GLOBALS["HTTP_POST_VARS"]["ed12_i_ensino"]:$this->ed12_i_ensino);
       $this->ed12_i_caddisciplina = ($this->ed12_i_caddisciplina == ""?@$GLOBALS["HTTP_POST_VARS"]["ed12_i_caddisciplina"]:$this->ed12_i_caddisciplina);
     }else{
       $this->ed12_i_codigo = ($this->ed12_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed12_i_codigo"]:$this->ed12_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed12_i_codigo){
      $this->atualizacampos();
     if($this->ed12_i_ensino == null ){
       $this->erro_sql = " Campo Ensino nao Informado.";
       $this->erro_campo = "ed12_i_ensino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed12_i_caddisciplina == null ){
       $this->erro_sql = " Campo Disciplina nao Informado.";
       $this->erro_campo = "ed12_i_caddisciplina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed12_i_codigo == "" || $ed12_i_codigo == null ){
       $result = db_query("select nextval('disciplina_ed12_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: disciplina_ed12_i_codigo_seq do campo: ed12_i_codigo";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed12_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from disciplina_ed12_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed12_i_codigo)){
         $this->erro_sql = " Campo ed12_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed12_i_codigo = $ed12_i_codigo;
       }
     }
     if(($this->ed12_i_codigo == null) || ($this->ed12_i_codigo == "") ){
       $this->erro_sql = " Campo ed12_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into disciplina(
                                       ed12_i_codigo
                                      ,ed12_i_ensino
                                      ,ed12_i_caddisciplina
                       )
                values (
                                $this->ed12_i_codigo
                               ,$this->ed12_i_ensino
                               ,$this->ed12_i_caddisciplina
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Disciplinas por Ensino ($this->ed12_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Disciplinas por Ensino j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Disciplinas por Ensino ($this->ed12_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed12_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed12_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008267,'$this->ed12_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010046,1008267,'','".AddSlashes(pg_result($resaco,0,'ed12_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010046,1008268,'','".AddSlashes(pg_result($resaco,0,'ed12_i_ensino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010046,11712,'','".AddSlashes(pg_result($resaco,0,'ed12_i_caddisciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed12_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update disciplina set ";
     $virgula = "";
     if(trim($this->ed12_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed12_i_codigo"])){
       $sql  .= $virgula." ed12_i_codigo = $this->ed12_i_codigo ";
       $virgula = ",";
       if(trim($this->ed12_i_codigo) == null ){
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "ed12_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed12_i_ensino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed12_i_ensino"])){
       $sql  .= $virgula." ed12_i_ensino = $this->ed12_i_ensino ";
       $virgula = ",";
       if(trim($this->ed12_i_ensino) == null ){
         $this->erro_sql = " Campo Ensino nao Informado.";
         $this->erro_campo = "ed12_i_ensino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed12_i_caddisciplina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed12_i_caddisciplina"])){
       $sql  .= $virgula." ed12_i_caddisciplina = $this->ed12_i_caddisciplina ";
       $virgula = ",";
       if(trim($this->ed12_i_caddisciplina) == null ){
         $this->erro_sql = " Campo Disciplina nao Informado.";
         $this->erro_campo = "ed12_i_caddisciplina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed12_i_codigo!=null){
       $sql .= " ed12_i_codigo = $this->ed12_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed12_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008267,'$this->ed12_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed12_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010046,1008267,'".AddSlashes(pg_result($resaco,$conresaco,'ed12_i_codigo'))."','$this->ed12_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed12_i_ensino"]))
           $resac = db_query("insert into db_acount values($acount,1010046,1008268,'".AddSlashes(pg_result($resaco,$conresaco,'ed12_i_ensino'))."','$this->ed12_i_ensino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed12_i_caddisciplina"]))
           $resac = db_query("insert into db_acount values($acount,1010046,11712,'".AddSlashes(pg_result($resaco,$conresaco,'ed12_i_caddisciplina'))."','$this->ed12_i_caddisciplina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Disciplinas por Ensino nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed12_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Disciplinas por Ensino nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed12_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed12_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed12_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed12_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008267,'$ed12_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010046,1008267,'','".AddSlashes(pg_result($resaco,$iresaco,'ed12_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010046,1008268,'','".AddSlashes(pg_result($resaco,$iresaco,'ed12_i_ensino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010046,11712,'','".AddSlashes(pg_result($resaco,$iresaco,'ed12_i_caddisciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from disciplina
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed12_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed12_i_codigo = $ed12_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Disciplinas por Ensino nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed12_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Disciplinas por Ensino nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed12_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed12_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:disciplina";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed12_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from disciplina ";
     $sql .= "      inner join caddisciplina  on  caddisciplina.ed232_i_codigo = disciplina.ed12_i_caddisciplina";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = disciplina.ed12_i_ensino";
     $sql .= "      inner join tipoensino  on  tipoensino.ed36_i_codigo = ensino.ed10_i_tipoensino";
     $sql .= "      left join cursoedu on cursoedu.ed29_i_ensino = ensino.ed10_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed12_i_codigo!=null ){
         $sql2 .= " where disciplina.ed12_i_codigo = $ed12_i_codigo ";
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
   function sql_query_file ( $ed12_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from disciplina ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed12_i_codigo!=null ){
         $sql2 .= " where disciplina.ed12_i_codigo = $ed12_i_codigo ";
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
   function sql_query_mps ( $ed12_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from disciplina ";
     $sql .= "      inner join caddisciplina  on  caddisciplina.ed232_i_codigo = disciplina.ed12_i_caddisciplina";
     $sql .= "      inner join ensino on ensino.ed10_i_codigo = disciplina.ed12_i_ensino";
     $sql .= "      inner join tipoensino on tipoensino.ed36_i_codigo = ensino.ed10_i_tipoensino";
     $sql .= "      inner join cursoedu on cursoedu.ed29_i_ensino = ensino.ed10_i_codigo";
     $sql .= "      inner join basemps on basemps.ed34_i_disciplina = disciplina.ed12_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed12_i_codigo!=null ){
         $sql2 .= " where disciplina.ed12_i_codigo = $ed12_i_codigo ";
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

 function sql_query_disciplina($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= " from disciplina " ;
    $sSql .= "      inner join caddisciplina  on  caddisciplina.ed232_i_codigo = disciplina.ed12_i_caddisciplina ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where disciplina.ed12_i_codigo  = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }

  function sql_query_disciplina_censo ($ed12_i_codigo = null,$campos = "*",$ordem = null, $dbwhere = "") {

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
     $sql .= " from disciplina ";
     $sql .= "      inner join caddisciplina      on caddisciplina.ed232_i_codigo           = disciplina.ed12_i_caddisciplina";
     $sql .= "      inner join censocaddisciplina on censocaddisciplina.ed294_caddisciplina = caddisciplina.ed232_i_codigo";
     $sql .= "      inner join censodisciplina    on censodisciplina.ed265_i_codigo         = censocaddisciplina.ed294_censodisciplina ";
     $sql .= "      inner join ensino             on ensino.ed10_i_codigo                   = disciplina.ed12_i_ensino";
     $sql .= "      inner join tipoensino         on tipoensino.ed36_i_codigo               = ensino.ed10_i_tipoensino";
     $sql2 = "";
     if($dbwhere==""){
       if($ed12_i_codigo!=null ){
         $sql2 .= " where disciplina.ed12_i_codigo = $ed12_i_codigo ";
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

  function sql_query_disciplinas_na_escola( $ed12_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from disciplina ";
    $sql .= "      inner join caddisciplina  on  caddisciplina.ed232_i_codigo = disciplina.ed12_i_caddisciplina";
    $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = disciplina.ed12_i_ensino";
    $sql .= "      inner join tipoensino  on  tipoensino.ed36_i_codigo = ensino.ed10_i_tipoensino";
    $sql .= "      inner join cursoedu on cursoedu.ed29_i_ensino    = ensino.ed10_i_codigo";
    $sql .= "      inner join cursoescola on cursoedu.ed29_i_codigo = cursoescola.ed71_i_curso";
    $sql2 = "";
    if($dbwhere==""){
      if($ed12_i_codigo!=null ){
        $sql2 .= " where disciplina.ed12_i_codigo = $ed12_i_codigo ";
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