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

//MODULO: ISSQN
//CLASSE DA ENTIDADE meiimportalinhaatividade
class cl_meiimportalinhaatividade { 
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
   var $q106_sequencial = 0; 
   var $q106_meiimportalinha = 0; 
   var $q106_cnae = null; 
   var $q106_descricao = null; 
   var $q106_principal = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q106_sequencial = int4 = Sequencial 
                 q106_meiimportalinha = int4 = Linha de Importação do MEI 
                 q106_cnae = varchar(8) = Cnae 
                 q106_descricao = varchar(70) = Descrição 
                 q106_principal = bool = Principal 
                 ";
   //funcao construtor da classe 
   function cl_meiimportalinhaatividade() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("meiimportalinhaatividade"); 
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
       $this->q106_sequencial = ($this->q106_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q106_sequencial"]:$this->q106_sequencial);
       $this->q106_meiimportalinha = ($this->q106_meiimportalinha == ""?@$GLOBALS["HTTP_POST_VARS"]["q106_meiimportalinha"]:$this->q106_meiimportalinha);
       $this->q106_cnae = ($this->q106_cnae == ""?@$GLOBALS["HTTP_POST_VARS"]["q106_cnae"]:$this->q106_cnae);
       $this->q106_descricao = ($this->q106_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["q106_descricao"]:$this->q106_descricao);
       $this->q106_principal = ($this->q106_principal == "f"?@$GLOBALS["HTTP_POST_VARS"]["q106_principal"]:$this->q106_principal);
     }else{
       $this->q106_sequencial = ($this->q106_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q106_sequencial"]:$this->q106_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q106_sequencial){ 
      $this->atualizacampos();
     if($this->q106_meiimportalinha == null ){ 
       $this->erro_sql = " Campo Linha de Importação do MEI nao Informado.";
       $this->erro_campo = "q106_meiimportalinha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q106_cnae == null ){ 
       $this->erro_sql = " Campo Cnae nao Informado.";
       $this->erro_campo = "q106_cnae";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q106_principal == null ){ 
       $this->erro_sql = " Campo Principal nao Informado.";
       $this->erro_campo = "q106_principal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q106_sequencial == "" || $q106_sequencial == null ){
       $result = db_query("select nextval('meiimportalinhaatividade_q106_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: meiimportalinhaatividade_q106_sequencial_seq do campo: q106_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q106_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from meiimportalinhaatividade_q106_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q106_sequencial)){
         $this->erro_sql = " Campo q106_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q106_sequencial = $q106_sequencial; 
       }
     }
     if(($this->q106_sequencial == null) || ($this->q106_sequencial == "") ){ 
       $this->erro_sql = " Campo q106_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into meiimportalinhaatividade(
                                       q106_sequencial 
                                      ,q106_meiimportalinha 
                                      ,q106_cnae 
                                      ,q106_descricao 
                                      ,q106_principal 
                       )
                values (
                                $this->q106_sequencial 
                               ,$this->q106_meiimportalinha 
                               ,'$this->q106_cnae' 
                               ,'$this->q106_descricao' 
                               ,'$this->q106_principal' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Importação do MEI por Atividade ($this->q106_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Importação do MEI por Atividade já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Importação do MEI por Atividade ($this->q106_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q106_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q106_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16239,'$this->q106_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2849,16239,'','".AddSlashes(pg_result($resaco,0,'q106_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2849,16240,'','".AddSlashes(pg_result($resaco,0,'q106_meiimportalinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2849,16242,'','".AddSlashes(pg_result($resaco,0,'q106_cnae'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2849,16241,'','".AddSlashes(pg_result($resaco,0,'q106_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2849,16243,'','".AddSlashes(pg_result($resaco,0,'q106_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q106_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update meiimportalinhaatividade set ";
     $virgula = "";
     if(trim($this->q106_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q106_sequencial"])){ 
       $sql  .= $virgula." q106_sequencial = $this->q106_sequencial ";
       $virgula = ",";
       if(trim($this->q106_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q106_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q106_meiimportalinha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q106_meiimportalinha"])){ 
       $sql  .= $virgula." q106_meiimportalinha = $this->q106_meiimportalinha ";
       $virgula = ",";
       if(trim($this->q106_meiimportalinha) == null ){ 
         $this->erro_sql = " Campo Linha de Importação do MEI nao Informado.";
         $this->erro_campo = "q106_meiimportalinha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q106_cnae)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q106_cnae"])){ 
       $sql  .= $virgula." q106_cnae = '$this->q106_cnae' ";
       $virgula = ",";
       if(trim($this->q106_cnae) == null ){ 
         $this->erro_sql = " Campo Cnae nao Informado.";
         $this->erro_campo = "q106_cnae";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q106_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q106_descricao"])){ 
       $sql  .= $virgula." q106_descricao = '$this->q106_descricao' ";
       $virgula = ",";
     }
     if(trim($this->q106_principal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q106_principal"])){ 
       $sql  .= $virgula." q106_principal = '$this->q106_principal' ";
       $virgula = ",";
       if(trim($this->q106_principal) == null ){ 
         $this->erro_sql = " Campo Principal nao Informado.";
         $this->erro_campo = "q106_principal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q106_sequencial!=null){
       $sql .= " q106_sequencial = $this->q106_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q106_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16239,'$this->q106_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q106_sequencial"]) || $this->q106_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2849,16239,'".AddSlashes(pg_result($resaco,$conresaco,'q106_sequencial'))."','$this->q106_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q106_meiimportalinha"]) || $this->q106_meiimportalinha != "")
           $resac = db_query("insert into db_acount values($acount,2849,16240,'".AddSlashes(pg_result($resaco,$conresaco,'q106_meiimportalinha'))."','$this->q106_meiimportalinha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q106_cnae"]) || $this->q106_cnae != "")
           $resac = db_query("insert into db_acount values($acount,2849,16242,'".AddSlashes(pg_result($resaco,$conresaco,'q106_cnae'))."','$this->q106_cnae',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q106_descricao"]) || $this->q106_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2849,16241,'".AddSlashes(pg_result($resaco,$conresaco,'q106_descricao'))."','$this->q106_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q106_principal"]) || $this->q106_principal != "")
           $resac = db_query("insert into db_acount values($acount,2849,16243,'".AddSlashes(pg_result($resaco,$conresaco,'q106_principal'))."','$this->q106_principal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Importação do MEI por Atividade nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q106_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Importação do MEI por Atividade nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q106_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q106_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q106_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q106_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16239,'$q106_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2849,16239,'','".AddSlashes(pg_result($resaco,$iresaco,'q106_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2849,16240,'','".AddSlashes(pg_result($resaco,$iresaco,'q106_meiimportalinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2849,16242,'','".AddSlashes(pg_result($resaco,$iresaco,'q106_cnae'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2849,16241,'','".AddSlashes(pg_result($resaco,$iresaco,'q106_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2849,16243,'','".AddSlashes(pg_result($resaco,$iresaco,'q106_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from meiimportalinhaatividade
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q106_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q106_sequencial = $q106_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Importação do MEI por Atividade nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q106_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Importação do MEI por Atividade nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q106_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q106_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:meiimportalinhaatividade";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q106_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meiimportalinhaatividade ";
     $sql .= "      inner join meiimportalinha  on  meiimportalinha.q105_sequencial = meiimportalinhaatividade.q106_meiimportalinha";
     $sql .= "      inner join meiimporta  on  meiimporta.q104_sequencial = meiimportalinha.q105_meiimporta";
     $sql2 = "";
     if($dbwhere==""){
       if($q106_sequencial!=null ){
         $sql2 .= " where meiimportalinhaatividade.q106_sequencial = $q106_sequencial "; 
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
   function sql_query_file ( $q106_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meiimportalinhaatividade ";
     $sql2 = "";
     if($dbwhere==""){
       if($q106_sequencial!=null ){
         $sql2 .= " where meiimportalinhaatividade.q106_sequencial = $q106_sequencial "; 
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