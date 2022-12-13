<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE diarioclasseregenciahorario
class cl_diarioclasseregenciahorario { 
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
   var $ed302_sequencial = 0; 
   var $ed302_regenciahorario = 0; 
   var $ed302_diarioclasse = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed302_sequencial = int4 = Sequencial interno 
                 ed302_regenciahorario = int8 = Codigo da Regencia 
                 ed302_diarioclasse = int4 = Diario de classe 
                 ";
   //funcao construtor da classe 
   function cl_diarioclasseregenciahorario() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("diarioclasseregenciahorario"); 
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
       $this->ed302_sequencial = ($this->ed302_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed302_sequencial"]:$this->ed302_sequencial);
       $this->ed302_regenciahorario = ($this->ed302_regenciahorario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed302_regenciahorario"]:$this->ed302_regenciahorario);
       $this->ed302_diarioclasse = ($this->ed302_diarioclasse == ""?@$GLOBALS["HTTP_POST_VARS"]["ed302_diarioclasse"]:$this->ed302_diarioclasse);
     }else{
       $this->ed302_sequencial = ($this->ed302_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed302_sequencial"]:$this->ed302_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed302_sequencial){ 
      $this->atualizacampos();
     if($this->ed302_regenciahorario == null ){ 
       $this->erro_sql = " Campo Codigo da Regencia nao Informado.";
       $this->erro_campo = "ed302_regenciahorario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed302_diarioclasse == null ){ 
       $this->erro_sql = " Campo Diario de classe nao Informado.";
       $this->erro_campo = "ed302_diarioclasse";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed302_sequencial == "" || $ed302_sequencial == null ){
       $result = db_query("select nextval('diarioclasseregenciahorario_ed302_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: diarioclasseregenciahorario_ed302_sequencial_seq do campo: ed302_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed302_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from diarioclasseregenciahorario_ed302_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed302_sequencial)){
         $this->erro_sql = " Campo ed302_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed302_sequencial = $ed302_sequencial; 
       }
     }
     if(($this->ed302_sequencial == null) || ($this->ed302_sequencial == "") ){ 
       $this->erro_sql = " Campo ed302_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into diarioclasseregenciahorario(
                                       ed302_sequencial 
                                      ,ed302_regenciahorario 
                                      ,ed302_diarioclasse 
                       )
                values (
                                $this->ed302_sequencial 
                               ,$this->ed302_regenciahorario 
                               ,$this->ed302_diarioclasse 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Periodos dados no dia ($this->ed302_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Periodos dados no dia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Periodos dados no dia ($this->ed302_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed302_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed302_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18797,'$this->ed302_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3333,18797,'','".AddSlashes(pg_result($resaco,0,'ed302_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3333,18798,'','".AddSlashes(pg_result($resaco,0,'ed302_regenciahorario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3333,18799,'','".AddSlashes(pg_result($resaco,0,'ed302_diarioclasse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed302_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update diarioclasseregenciahorario set ";
     $virgula = "";
     if(trim($this->ed302_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed302_sequencial"])){ 
       $sql  .= $virgula." ed302_sequencial = $this->ed302_sequencial ";
       $virgula = ",";
       if(trim($this->ed302_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial interno nao Informado.";
         $this->erro_campo = "ed302_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed302_regenciahorario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed302_regenciahorario"])){ 
       $sql  .= $virgula." ed302_regenciahorario = $this->ed302_regenciahorario ";
       $virgula = ",";
       if(trim($this->ed302_regenciahorario) == null ){ 
         $this->erro_sql = " Campo Codigo da Regencia nao Informado.";
         $this->erro_campo = "ed302_regenciahorario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed302_diarioclasse)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed302_diarioclasse"])){ 
       $sql  .= $virgula." ed302_diarioclasse = $this->ed302_diarioclasse ";
       $virgula = ",";
       if(trim($this->ed302_diarioclasse) == null ){ 
         $this->erro_sql = " Campo Diario de classe nao Informado.";
         $this->erro_campo = "ed302_diarioclasse";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed302_sequencial!=null){
       $sql .= " ed302_sequencial = $this->ed302_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed302_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18797,'$this->ed302_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed302_sequencial"]) || $this->ed302_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3333,18797,'".AddSlashes(pg_result($resaco,$conresaco,'ed302_sequencial'))."','$this->ed302_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed302_regenciahorario"]) || $this->ed302_regenciahorario != "")
           $resac = db_query("insert into db_acount values($acount,3333,18798,'".AddSlashes(pg_result($resaco,$conresaco,'ed302_regenciahorario'))."','$this->ed302_regenciahorario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed302_diarioclasse"]) || $this->ed302_diarioclasse != "")
           $resac = db_query("insert into db_acount values($acount,3333,18799,'".AddSlashes(pg_result($resaco,$conresaco,'ed302_diarioclasse'))."','$this->ed302_diarioclasse',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Periodos dados no dia nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed302_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Periodos dados no dia nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed302_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed302_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed302_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed302_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18797,'$ed302_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3333,18797,'','".AddSlashes(pg_result($resaco,$iresaco,'ed302_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3333,18798,'','".AddSlashes(pg_result($resaco,$iresaco,'ed302_regenciahorario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3333,18799,'','".AddSlashes(pg_result($resaco,$iresaco,'ed302_diarioclasse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from diarioclasseregenciahorario
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed302_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed302_sequencial = $ed302_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Periodos dados no dia nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed302_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Periodos dados no dia nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed302_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed302_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:diarioclasseregenciahorario";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed302_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from diarioclasseregenciahorario ";
     $sql .= "      inner join diarioclasse  on  diarioclasse.ed300_sequencial = diarioclasseregenciahorario.ed302_diarioclasse";
     $sql .= "      inner join regenciahorario  on  regenciahorario.ed58_i_codigo = diarioclasseregenciahorario.ed302_regenciahorario";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = diarioclasse.ed300_id_usuario";
     $sql .= "      inner join periodoescola  on  periodoescola.ed17_i_codigo = regenciahorario.ed58_i_periodo";
     $sql .= "      inner join regencia  as a on   a.ed59_i_codigo = regenciahorario.ed58_i_regencia";
     $sql .= "      inner join rechumano  on  rechumano.ed20_i_codigo = regenciahorario.ed58_i_rechumano";
     $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = regenciahorario.ed58_i_diasemana";
     $sql2 = "";
     if($dbwhere==""){
       if($ed302_sequencial!=null ){
         $sql2 .= " where diarioclasseregenciahorario.ed302_sequencial = $ed302_sequencial "; 
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
   function sql_query_file ( $ed302_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from diarioclasseregenciahorario ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed302_sequencial!=null ){
         $sql2 .= " where diarioclasseregenciahorario.ed302_sequencial = $ed302_sequencial "; 
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
  
  function sql_query_diario_classe ( $ed302_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from diarioclasseregenciahorario ";
     $sql .= "      inner join diarioclasse  on  diarioclasse.ed300_sequencial = diarioclasseregenciahorario.ed302_diarioclasse";
     $sql2 = "";
     if($dbwhere==""){
       if($ed302_sequencial!=null ){
         $sql2 .= " where diarioclasseregenciahorario.ed302_sequencial = $ed302_sequencial "; 
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