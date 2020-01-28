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

//MODULO: escola
//CLASSE DA ENTIDADE rechumanonecessidade
class cl_rechumanonecessidade { 
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
   var $ed310_sequencial = 0; 
   var $ed310_rechumano = 0; 
   var $ed310_necessidade = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed310_sequencial = int4 = Código 
                 ed310_rechumano = int4 = Código do Recurso Humano 
                 ed310_necessidade = int4 = Código da Necessidade 
                 ";
   //funcao construtor da classe 
   function cl_rechumanonecessidade() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rechumanonecessidade"); 
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
       $this->ed310_sequencial = ($this->ed310_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed310_sequencial"]:$this->ed310_sequencial);
       $this->ed310_rechumano = ($this->ed310_rechumano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed310_rechumano"]:$this->ed310_rechumano);
       $this->ed310_necessidade = ($this->ed310_necessidade == ""?@$GLOBALS["HTTP_POST_VARS"]["ed310_necessidade"]:$this->ed310_necessidade);
     }else{
       $this->ed310_sequencial = ($this->ed310_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed310_sequencial"]:$this->ed310_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed310_sequencial){ 
      $this->atualizacampos();
     if($this->ed310_rechumano == null ){ 
       $this->erro_sql = " Campo Código do Recurso Humano nao Informado.";
       $this->erro_campo = "ed310_rechumano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed310_necessidade == null ){ 
       $this->erro_sql = " Campo Código da Necessidade nao Informado.";
       $this->erro_campo = "ed310_necessidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed310_sequencial == "" || $ed310_sequencial == null ){
       $result = db_query("select nextval('rechumanonecessidade_ed310_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rechumanonecessidade_ed310_sequencial_seq do campo: ed310_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed310_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rechumanonecessidade_ed310_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed310_sequencial)){
         $this->erro_sql = " Campo ed310_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed310_sequencial = $ed310_sequencial; 
       }
     }
     if(($this->ed310_sequencial == null) || ($this->ed310_sequencial == "") ){ 
       $this->erro_sql = " Campo ed310_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rechumanonecessidade(
                                       ed310_sequencial 
                                      ,ed310_rechumano 
                                      ,ed310_necessidade 
                       )
                values (
                                $this->ed310_sequencial 
                               ,$this->ed310_rechumano 
                               ,$this->ed310_necessidade 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dados das necessidades especiais ($this->ed310_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dados das necessidades especiais já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dados das necessidades especiais ($this->ed310_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed310_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed310_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18909,'$this->ed310_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3361,18909,'','".AddSlashes(pg_result($resaco,0,'ed310_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3361,18910,'','".AddSlashes(pg_result($resaco,0,'ed310_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3361,18911,'','".AddSlashes(pg_result($resaco,0,'ed310_necessidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed310_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rechumanonecessidade set ";
     $virgula = "";
     if(trim($this->ed310_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed310_sequencial"])){ 
       $sql  .= $virgula." ed310_sequencial = $this->ed310_sequencial ";
       $virgula = ",";
       if(trim($this->ed310_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed310_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed310_rechumano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed310_rechumano"])){ 
       $sql  .= $virgula." ed310_rechumano = $this->ed310_rechumano ";
       $virgula = ",";
       if(trim($this->ed310_rechumano) == null ){ 
         $this->erro_sql = " Campo Código do Recurso Humano nao Informado.";
         $this->erro_campo = "ed310_rechumano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed310_necessidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed310_necessidade"])){ 
       $sql  .= $virgula." ed310_necessidade = $this->ed310_necessidade ";
       $virgula = ",";
       if(trim($this->ed310_necessidade) == null ){ 
         $this->erro_sql = " Campo Código da Necessidade nao Informado.";
         $this->erro_campo = "ed310_necessidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed310_sequencial!=null){
       $sql .= " ed310_sequencial = $this->ed310_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed310_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18909,'$this->ed310_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed310_sequencial"]) || $this->ed310_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3361,18909,'".AddSlashes(pg_result($resaco,$conresaco,'ed310_sequencial'))."','$this->ed310_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed310_rechumano"]) || $this->ed310_rechumano != "")
           $resac = db_query("insert into db_acount values($acount,3361,18910,'".AddSlashes(pg_result($resaco,$conresaco,'ed310_rechumano'))."','$this->ed310_rechumano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed310_necessidade"]) || $this->ed310_necessidade != "")
           $resac = db_query("insert into db_acount values($acount,3361,18911,'".AddSlashes(pg_result($resaco,$conresaco,'ed310_necessidade'))."','$this->ed310_necessidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados das necessidades especiais nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed310_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados das necessidades especiais nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed310_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed310_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed310_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed310_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18909,'$ed310_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3361,18909,'','".AddSlashes(pg_result($resaco,$iresaco,'ed310_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3361,18910,'','".AddSlashes(pg_result($resaco,$iresaco,'ed310_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3361,18911,'','".AddSlashes(pg_result($resaco,$iresaco,'ed310_necessidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rechumanonecessidade
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed310_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed310_sequencial = $ed310_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados das necessidades especiais nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed310_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados das necessidades especiais nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed310_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed310_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rechumanonecessidade";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed310_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rechumanonecessidade ";
     $sql .= "      inner join necessidade  on  necessidade.ed48_i_codigo = rechumanonecessidade.ed310_necessidade";
     $sql .= "      inner join rechumano  on  rechumano.ed20_i_codigo = rechumanonecessidade.ed310_rechumano";
     $sql .= "      left  join rhregime  on  rhregime.rh30_codreg = rechumano.ed20_i_rhregime";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = rechumano.ed20_i_pais";
     $sql .= "      left  join censouf  on  censouf.ed260_i_codigo = rechumano.ed20_i_censoufcert and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufender and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufnat and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufident";
     $sql .= "      left  join censomunic  on  censomunic.ed261_i_codigo = rechumano.ed20_i_censomunicnat and  censomunic. = rechumano.ed20_i_censomunicender";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = rechumano.ed20_i_censoorgemiss";
     $sql .= "      left  join censocartorio  on  censocartorio.ed291_i_codigo = rechumano.ed20_i_censocartorio";
     $sql .= "      left  join rechumano  as a on   a.ed20_i_codigo = rechumano.ed20_i_censocartorio";
     $sql2 = "";
     if($dbwhere==""){
       if($ed310_sequencial!=null ){
         $sql2 .= " where rechumanonecessidade.ed310_sequencial = $ed310_sequencial "; 
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
   function sql_query_file ( $ed310_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rechumanonecessidade ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed310_sequencial!=null ){
         $sql2 .= " where rechumanonecessidade.ed310_sequencial = $ed310_sequencial "; 
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
    * Retorna sql com todas as necessidades especiais
    * @param Integer $iRecursoHumano
    * @param string  $sCampos
    * @param string  $sWhere
    * @return string
    */
   function sql_query_necessidade ($iRecursoHumano, $sCampos = "*", $sWhere = null) { 
     
     $sql = "select ";
     if ($sCampos != "*" ) {

       $campos_sql = split("#", $sCampos);
       $virgula    = "";
       for ($i = 0; $i < sizeof($campos_sql); $i++) {
         
         $sql     .= $virgula.$campos_sql[$i];
         $virgula  = ",";
       }
     } else {
       $sql .= "*";
     }
     
     $sSqlWhere = "";
     
     if (isset($sWhere)) {
       
       $sSqlWhere = " where {$sWhere} ";
     }
     
     $sql .= " from necessidade  ";
     $sql .= "      left join rechumanonecessidade on  necessidade.ed48_i_codigo = rechumanonecessidade.ed310_necessidade";
     $sql .= "                                   and rechumanonecessidade.ed310_rechumano = {$iRecursoHumano} ";
     $sql .= "      left join rechumano  on  rechumano.ed20_i_codigo = rechumanonecessidade.ed310_rechumano ";
     $sql .= $sSqlWhere;
     $sql .= " order by ed48_i_codigo";
     return $sql;
  }
}
?>