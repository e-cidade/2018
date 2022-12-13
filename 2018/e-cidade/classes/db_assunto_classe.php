<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: biblioteca
//CLASSE DA ENTIDADE assunto
class cl_assunto { 
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
   var $bi15_codigo = 0; 
   var $bi15_assunto = null; 
   var $bi15_acervo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 bi15_codigo = int8 = Código 
                 bi15_assunto = text = Assunto 
                 bi15_acervo = int8 = Acervo 
                 ";
   //funcao construtor da classe 
   function cl_assunto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("assunto"); 
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
       $this->bi15_codigo = ($this->bi15_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi15_codigo"]:$this->bi15_codigo);
       $this->bi15_assunto = ($this->bi15_assunto == ""?@$GLOBALS["HTTP_POST_VARS"]["bi15_assunto"]:$this->bi15_assunto);
       $this->bi15_acervo = ($this->bi15_acervo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi15_acervo"]:$this->bi15_acervo);
     }else{
       $this->bi15_codigo = ($this->bi15_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi15_codigo"]:$this->bi15_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($bi15_codigo){ 
      $this->atualizacampos();
     if($this->bi15_assunto == null ){ 
       $this->erro_sql = " Campo Assunto nao Informado.";
       $this->erro_campo = "bi15_assunto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi15_acervo == null ){ 
       $this->erro_sql = " Campo Acervo nao Informado.";
       $this->erro_campo = "bi15_acervo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($bi15_codigo == "" || $bi15_codigo == null ){
       $result = db_query("select nextval('assunto_bi15_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: assunto_bi15_codigo_seq do campo: bi15_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->bi15_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from assunto_bi15_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $bi15_codigo)){
         $this->erro_sql = " Campo bi15_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->bi15_codigo = $bi15_codigo; 
       }
     }
     if(($this->bi15_codigo == null) || ($this->bi15_codigo == "") ){ 
       $this->erro_sql = " Campo bi15_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into assunto(
                                       bi15_codigo 
                                      ,bi15_assunto 
                                      ,bi15_acervo 
                       )
                values (
                                $this->bi15_codigo 
                               ,'$this->bi15_assunto' 
                               ,$this->bi15_acervo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Assunto do Livro ($this->bi15_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Assunto do Livro já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Assunto do Livro ($this->bi15_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi15_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->bi15_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008154,'$this->bi15_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1008020,1008154,'','".AddSlashes(pg_result($resaco,0,'bi15_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008020,1008155,'','".AddSlashes(pg_result($resaco,0,'bi15_assunto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008020,1008156,'','".AddSlashes(pg_result($resaco,0,'bi15_acervo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($bi15_codigo=null) { 
      $this->atualizacampos();
     $sql = " update assunto set ";
     $virgula = "";
     if(trim($this->bi15_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi15_codigo"])){ 
       $sql  .= $virgula." bi15_codigo = $this->bi15_codigo ";
       $virgula = ",";
       if(trim($this->bi15_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "bi15_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi15_assunto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi15_assunto"])){ 
       $sql  .= $virgula." bi15_assunto = '$this->bi15_assunto' ";
       $virgula = ",";
       if(trim($this->bi15_assunto) == null ){ 
         $this->erro_sql = " Campo Assunto nao Informado.";
         $this->erro_campo = "bi15_assunto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi15_acervo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi15_acervo"])){ 
       $sql  .= $virgula." bi15_acervo = $this->bi15_acervo ";
       $virgula = ",";
       if(trim($this->bi15_acervo) == null ){ 
         $this->erro_sql = " Campo Acervo nao Informado.";
         $this->erro_campo = "bi15_acervo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($bi15_codigo!=null){
       $sql .= " bi15_codigo = $this->bi15_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->bi15_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008154,'$this->bi15_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi15_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1008020,1008154,'".AddSlashes(pg_result($resaco,$conresaco,'bi15_codigo'))."','$this->bi15_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi15_assunto"]))
           $resac = db_query("insert into db_acount values($acount,1008020,1008155,'".AddSlashes(pg_result($resaco,$conresaco,'bi15_assunto'))."','$this->bi15_assunto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi15_acervo"]))
           $resac = db_query("insert into db_acount values($acount,1008020,1008156,'".AddSlashes(pg_result($resaco,$conresaco,'bi15_acervo'))."','$this->bi15_acervo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Assunto do Livro nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi15_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Assunto do Livro nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi15_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi15_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($bi15_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($bi15_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008154,'$bi15_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1008020,1008154,'','".AddSlashes(pg_result($resaco,$iresaco,'bi15_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008020,1008155,'','".AddSlashes(pg_result($resaco,$iresaco,'bi15_assunto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008020,1008156,'','".AddSlashes(pg_result($resaco,$iresaco,'bi15_acervo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from assunto
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($bi15_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " bi15_codigo = $bi15_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Assunto do Livro nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$bi15_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Assunto do Livro nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$bi15_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$bi15_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:assunto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $bi15_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from assunto ";
     $sql .= "      inner join acervo  on  acervo.bi06_seq = assunto.bi15_acervo";
     $sql .= "      inner join biblioteca  on  biblioteca.bi17_codigo = acervo.bi06_biblioteca";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = biblioteca.bi17_coddepto";
     $sql .= "      inner join editora  on  editora.bi02_codigo = acervo.bi06_editora";
     $sql .= "      inner join classiliteraria  on  classiliteraria.bi03_codigo = acervo.bi06_classiliteraria";
     $sql .= "      inner join tipoitem  on  tipoitem.bi05_codigo = acervo.bi06_tipoitem";
     $sql2 = "";
     if($dbwhere==""){
       if($bi15_codigo!=null ){
         $sql2 .= " where assunto.bi15_codigo = $bi15_codigo ";
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
   function sql_query_exemplar ( $bi15_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from assunto ";
     $sql .= "      inner join acervo  on  acervo.bi06_seq = assunto.bi15_acervo";
     $sql .= "      inner join exemplar  on  exemplar.bi23_acervo = acervo.bi06_seq";
     $sql .= "      inner join biblioteca  on  biblioteca.bi17_codigo = acervo.bi06_biblioteca";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = biblioteca.bi17_coddepto";
     $sql .= "      inner join editora  on  editora.bi02_codigo = acervo.bi06_editora";
     $sql .= "      inner join classiliteraria  on  classiliteraria.bi03_codigo = acervo.bi06_classiliteraria";
     $sql .= "      inner join tipoitem  on  tipoitem.bi05_codigo = acervo.bi06_tipoitem";
     $sql .= "      left join localacervo  on  localacervo.bi20_acervo = acervo.bi06_seq";
     $sql .= "      left join localizacao  on  localizacao.bi09_codigo = localacervo.bi20_localizacao";
     $sql2 = "";
     if($dbwhere==""){
       if($bi15_codigo!=null ){
         $sql2 .= " where assunto.bi15_codigo = $bi15_codigo ";
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
   function sql_query_consacervo ( $bi15_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from assunto ";
     $sql .= "      inner join acervo  on  acervo.bi06_seq = assunto.bi15_acervo";
     $sql .= "      inner join biblioteca  on  biblioteca.bi17_codigo = acervo.bi06_biblioteca";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = biblioteca.bi17_coddepto";
     $sql .= "      inner join editora  on  editora.bi02_codigo = acervo.bi06_editora";
     $sql .= "      inner join classiliteraria  on  classiliteraria.bi03_codigo = acervo.bi06_classiliteraria";
     $sql .= "      inner join tipoitem  on  tipoitem.bi05_codigo = acervo.bi06_tipoitem";
     $sql .= "      left join localacervo  on  localacervo.bi20_acervo = acervo.bi06_seq";
     $sql .= "      left join localizacao  on  localizacao.bi09_codigo = localacervo.bi20_localizacao";
     $sql2 = "";
     if($dbwhere==""){
       if($bi15_codigo!=null ){
         $sql2 .= " where assunto.bi15_codigo = $bi15_codigo ";
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
  
   function sql_query_file ( $bi15_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from assunto ";
     $sql2 = "";
     if($dbwhere==""){
       if($bi15_codigo!=null ){
         $sql2 .= " where assunto.bi15_codigo = $bi15_codigo "; 
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