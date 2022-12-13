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

//MODULO: escola
//CLASSE DA ENTIDADE parametroprogressaoparcialetapa
class cl_parametroprogressaoparcialetapa { 
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
   var $ed113_sequencial = 0; 
   var $ed113_parametroprogressaoparcial = 0; 
   var $ed113_serie = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed113_sequencial = int4 = Código 
                 ed113_parametroprogressaoparcial = int4 = Parâmetros da progressão parcial 
                 ed113_serie = int4 = Série 
                 ";
   //funcao construtor da classe 
   function cl_parametroprogressaoparcialetapa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("parametroprogressaoparcialetapa"); 
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
       $this->ed113_sequencial = ($this->ed113_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed113_sequencial"]:$this->ed113_sequencial);
       $this->ed113_parametroprogressaoparcial = ($this->ed113_parametroprogressaoparcial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed113_parametroprogressaoparcial"]:$this->ed113_parametroprogressaoparcial);
       $this->ed113_serie = ($this->ed113_serie == ""?@$GLOBALS["HTTP_POST_VARS"]["ed113_serie"]:$this->ed113_serie);
     }else{
       $this->ed113_sequencial = ($this->ed113_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed113_sequencial"]:$this->ed113_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed113_sequencial){ 
      $this->atualizacampos();
     if($this->ed113_parametroprogressaoparcial == null ){ 
       $this->erro_sql = " Campo Parâmetros da progressão parcial nao Informado.";
       $this->erro_campo = "ed113_parametroprogressaoparcial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed113_serie == null ){ 
       $this->erro_sql = " Campo Série nao Informado.";
       $this->erro_campo = "ed113_serie";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed113_sequencial == "" || $ed113_sequencial == null ){
       $result = db_query("select nextval('parametroprogressaoparcialetapa_ed113_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: parametroprogressaoparcialetapa_ed113_sequencial_seq do campo: ed113_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed113_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from parametroprogressaoparcialetapa_ed113_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed113_sequencial)){
         $this->erro_sql = " Campo ed113_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed113_sequencial = $ed113_sequencial; 
       }
     }
     if(($this->ed113_sequencial == null) || ($this->ed113_sequencial == "") ){ 
       $this->erro_sql = " Campo ed113_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into parametroprogressaoparcialetapa(
                                       ed113_sequencial 
                                      ,ed113_parametroprogressaoparcial 
                                      ,ed113_serie 
                       )
                values (
                                $this->ed113_sequencial 
                               ,$this->ed113_parametroprogressaoparcial 
                               ,$this->ed113_serie 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Etapa da progressão parcial ($this->ed113_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Etapa da progressão parcial já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Etapa da progressão parcial ($this->ed113_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed113_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed113_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19533,'$this->ed113_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3471,19533,'','".AddSlashes(pg_result($resaco,0,'ed113_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3471,19534,'','".AddSlashes(pg_result($resaco,0,'ed113_parametroprogressaoparcial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3471,19535,'','".AddSlashes(pg_result($resaco,0,'ed113_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed113_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update parametroprogressaoparcialetapa set ";
     $virgula = "";
     if(trim($this->ed113_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed113_sequencial"])){ 
       $sql  .= $virgula." ed113_sequencial = $this->ed113_sequencial ";
       $virgula = ",";
       if(trim($this->ed113_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed113_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed113_parametroprogressaoparcial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed113_parametroprogressaoparcial"])){ 
       $sql  .= $virgula." ed113_parametroprogressaoparcial = $this->ed113_parametroprogressaoparcial ";
       $virgula = ",";
       if(trim($this->ed113_parametroprogressaoparcial) == null ){ 
         $this->erro_sql = " Campo Parâmetros da progressão parcial nao Informado.";
         $this->erro_campo = "ed113_parametroprogressaoparcial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed113_serie)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed113_serie"])){ 
       $sql  .= $virgula." ed113_serie = $this->ed113_serie ";
       $virgula = ",";
       if(trim($this->ed113_serie) == null ){ 
         $this->erro_sql = " Campo Série nao Informado.";
         $this->erro_campo = "ed113_serie";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed113_sequencial!=null){
       $sql .= " ed113_sequencial = $this->ed113_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed113_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19533,'$this->ed113_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed113_sequencial"]) || $this->ed113_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3471,19533,'".AddSlashes(pg_result($resaco,$conresaco,'ed113_sequencial'))."','$this->ed113_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed113_parametroprogressaoparcial"]) || $this->ed113_parametroprogressaoparcial != "")
           $resac = db_query("insert into db_acount values($acount,3471,19534,'".AddSlashes(pg_result($resaco,$conresaco,'ed113_parametroprogressaoparcial'))."','$this->ed113_parametroprogressaoparcial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed113_serie"]) || $this->ed113_serie != "")
           $resac = db_query("insert into db_acount values($acount,3471,19535,'".AddSlashes(pg_result($resaco,$conresaco,'ed113_serie'))."','$this->ed113_serie',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Etapa da progressão parcial nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed113_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Etapa da progressão parcial nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed113_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed113_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed113_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed113_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19533,'$ed113_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3471,19533,'','".AddSlashes(pg_result($resaco,$iresaco,'ed113_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3471,19534,'','".AddSlashes(pg_result($resaco,$iresaco,'ed113_parametroprogressaoparcial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3471,19535,'','".AddSlashes(pg_result($resaco,$iresaco,'ed113_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from parametroprogressaoparcialetapa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed113_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed113_sequencial = $ed113_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Etapa da progressão parcial nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed113_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Etapa da progressão parcial nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed113_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed113_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:parametroprogressaoparcialetapa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed113_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from parametroprogressaoparcialetapa ";
     $sql .= "      inner join parametroprogressaoparcial  on  parametroprogressaoparcial.ed112_sequencial = parametroprogressaoparcialetapa.ed113_parametroprogressaoparcial";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = parametroprogressaoparcialetapa.ed113_serie";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = parametroprogressaoparcial.ed112_escola";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = serie.ed11_i_ensino";
     $sql2 = "";
     if($dbwhere==""){
       if($ed113_sequencial!=null ){
         $sql2 .= " where parametroprogressaoparcialetapa.ed113_sequencial = $ed113_sequencial "; 
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
   function sql_query_file ( $ed113_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from parametroprogressaoparcialetapa ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed113_sequencial!=null ){
         $sql2 .= " where parametroprogressaoparcialetapa.ed113_sequencial = $ed113_sequencial "; 
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