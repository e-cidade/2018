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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhempenhofolharubricaretencao
class cl_rhempenhofolharubricaretencao { 
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
   var $rh78_sequencial = 0; 
   var $rh78_rhempenhofolharubrica = 0; 
   var $rh78_retencaotiporec = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh78_sequencial = int4 = Sequencial 
                 rh78_rhempenhofolharubrica = int4 = rhempenhofolharubrica 
                 rh78_retencaotiporec = int4 = Retenção 
                 ";
   //funcao construtor da classe 
   function cl_rhempenhofolharubricaretencao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhempenhofolharubricaretencao"); 
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
       $this->rh78_sequencial = ($this->rh78_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh78_sequencial"]:$this->rh78_sequencial);
       $this->rh78_rhempenhofolharubrica = ($this->rh78_rhempenhofolharubrica == ""?@$GLOBALS["HTTP_POST_VARS"]["rh78_rhempenhofolharubrica"]:$this->rh78_rhempenhofolharubrica);
       $this->rh78_retencaotiporec = ($this->rh78_retencaotiporec == ""?@$GLOBALS["HTTP_POST_VARS"]["rh78_retencaotiporec"]:$this->rh78_retencaotiporec);
     }else{
       $this->rh78_sequencial = ($this->rh78_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh78_sequencial"]:$this->rh78_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh78_sequencial){ 
      $this->atualizacampos();
     if($this->rh78_rhempenhofolharubrica == null ){ 
       $this->erro_sql = " Campo rhempenhofolharubrica nao Informado.";
       $this->erro_campo = "rh78_rhempenhofolharubrica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh78_retencaotiporec == null ){ 
       $this->erro_sql = " Campo Retenção nao Informado.";
       $this->erro_campo = "rh78_retencaotiporec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh78_sequencial == "" || $rh78_sequencial == null ){
       $result = db_query("select nextval('rhempenhofolharubricaretencao_rh78_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhempenhofolharubricaretencao_rh78_sequencial_seq do campo: rh78_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh78_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhempenhofolharubricaretencao_rh78_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh78_sequencial)){
         $this->erro_sql = " Campo rh78_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh78_sequencial = $rh78_sequencial; 
       }
     }
     if(($this->rh78_sequencial == null) || ($this->rh78_sequencial == "") ){ 
       $this->erro_sql = " Campo rh78_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhempenhofolharubricaretencao(
                                       rh78_sequencial 
                                      ,rh78_rhempenhofolharubrica 
                                      ,rh78_retencaotiporec 
                       )
                values (
                                $this->rh78_sequencial 
                               ,$this->rh78_rhempenhofolharubrica 
                               ,$this->rh78_retencaotiporec 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhempenhofolharubricaretencao ($this->rh78_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhempenhofolharubricaretencao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhempenhofolharubricaretencao ($this->rh78_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh78_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh78_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14409,'$this->rh78_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2540,14409,'','".AddSlashes(pg_result($resaco,0,'rh78_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2540,14410,'','".AddSlashes(pg_result($resaco,0,'rh78_rhempenhofolharubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2540,14411,'','".AddSlashes(pg_result($resaco,0,'rh78_retencaotiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh78_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhempenhofolharubricaretencao set ";
     $virgula = "";
     if(trim($this->rh78_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh78_sequencial"])){ 
       $sql  .= $virgula." rh78_sequencial = $this->rh78_sequencial ";
       $virgula = ",";
       if(trim($this->rh78_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh78_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh78_rhempenhofolharubrica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh78_rhempenhofolharubrica"])){ 
       $sql  .= $virgula." rh78_rhempenhofolharubrica = $this->rh78_rhempenhofolharubrica ";
       $virgula = ",";
       if(trim($this->rh78_rhempenhofolharubrica) == null ){ 
         $this->erro_sql = " Campo rhempenhofolharubrica nao Informado.";
         $this->erro_campo = "rh78_rhempenhofolharubrica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh78_retencaotiporec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh78_retencaotiporec"])){ 
       $sql  .= $virgula." rh78_retencaotiporec = $this->rh78_retencaotiporec ";
       $virgula = ",";
       if(trim($this->rh78_retencaotiporec) == null ){ 
         $this->erro_sql = " Campo Retenção nao Informado.";
         $this->erro_campo = "rh78_retencaotiporec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh78_sequencial!=null){
       $sql .= " rh78_sequencial = $this->rh78_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh78_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14409,'$this->rh78_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh78_sequencial"]) || $this->rh78_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2540,14409,'".AddSlashes(pg_result($resaco,$conresaco,'rh78_sequencial'))."','$this->rh78_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh78_rhempenhofolharubrica"]) || $this->rh78_rhempenhofolharubrica != "")
           $resac = db_query("insert into db_acount values($acount,2540,14410,'".AddSlashes(pg_result($resaco,$conresaco,'rh78_rhempenhofolharubrica'))."','$this->rh78_rhempenhofolharubrica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh78_retencaotiporec"]) || $this->rh78_retencaotiporec != "")
           $resac = db_query("insert into db_acount values($acount,2540,14411,'".AddSlashes(pg_result($resaco,$conresaco,'rh78_retencaotiporec'))."','$this->rh78_retencaotiporec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhempenhofolharubricaretencao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh78_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhempenhofolharubricaretencao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh78_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh78_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh78_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh78_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14409,'$rh78_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2540,14409,'','".AddSlashes(pg_result($resaco,$iresaco,'rh78_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2540,14410,'','".AddSlashes(pg_result($resaco,$iresaco,'rh78_rhempenhofolharubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2540,14411,'','".AddSlashes(pg_result($resaco,$iresaco,'rh78_retencaotiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhempenhofolharubricaretencao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh78_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh78_sequencial = $rh78_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhempenhofolharubricaretencao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh78_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhempenhofolharubricaretencao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh78_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh78_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhempenhofolharubricaretencao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh78_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhempenhofolharubricaretencao ";
     $sql .= "      inner join retencaotiporec  on  retencaotiporec.e21_sequencial = rhempenhofolharubricaretencao.rh78_retencaotiporec";
     $sql .= "      inner join rhempenhofolharubrica  on  rhempenhofolharubrica.rh73_sequencial = rhempenhofolharubricaretencao.rh78_rhempenhofolharubrica";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = retencaotiporec.e21_receita";
     $sql .= "      inner join db_config  on  db_config.codigo = retencaotiporec.e21_instit";
     $sql .= "      inner join retencaotipocalc  on  retencaotipocalc.e32_sequencial = retencaotiporec.e21_retencaotipocalc";
     $sql .= "      inner join retencaotiporecgrupo  on  retencaotiporecgrupo.e01_sequencial = retencaotiporec.e21_retencaotiporecgrupo";
     $sql .= "      inner join db_config  as a on   a.codigo = rhempenhofolharubrica.rh73_instit";
     $sql .= "      inner join rhpessoalmov  on  rhpessoalmov.rh02_seqpes = rhempenhofolharubrica.rh73_seqpes and  rhpessoalmov.rh02_instit = rhempenhofolharubrica.rh73_instit";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = rhempenhofolharubrica.rh73_rubric and  rhrubricas.rh27_instit = rhempenhofolharubrica.rh73_instit";
     $sql .= "      inner join rhempenhofolha  as b on   b.rh72_sequencial = rhempenhofolharubrica.rh73_rhempenhofolha";
     $sql2 = "";
     if($dbwhere==""){
       if($rh78_sequencial!=null ){
         $sql2 .= " where rhempenhofolharubricaretencao.rh78_sequencial = $rh78_sequencial "; 
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
   function sql_query_file ( $rh78_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhempenhofolharubricaretencao ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh78_sequencial!=null ){
         $sql2 .= " where rhempenhofolharubricaretencao.rh78_sequencial = $rh78_sequencial "; 
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