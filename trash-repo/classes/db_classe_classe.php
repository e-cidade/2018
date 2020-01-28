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

//MODULO: issqn
//CLASSE DA ENTIDADE classe
class cl_classe { 
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
   var $q12_classe = 0; 
   var $q12_descr = null; 
   var $q12_fisica = 'f'; 
   var $q12_calciss = 'f'; 
   var $q12_integrasani = 'f'; 
   var $q12_alvaraautomatico = 't'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q12_classe = int4 = codigo da classe 
                 q12_descr = varchar(40) = descricao da classe 
                 q12_fisica = bool = Pessoa 
                 q12_calciss = bool = Calcula ISS 
                 q12_integrasani = bool = Integração automatica com sanitario 
                 q12_alvaraautomatico = bool = Gera Automático 
                 ";
   //funcao construtor da classe 
   function cl_classe() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("classe"); 
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
       $this->q12_classe = ($this->q12_classe == ""?@$GLOBALS["HTTP_POST_VARS"]["q12_classe"]:$this->q12_classe);
       $this->q12_descr = ($this->q12_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["q12_descr"]:$this->q12_descr);
       $this->q12_fisica = ($this->q12_fisica == "f"?@$GLOBALS["HTTP_POST_VARS"]["q12_fisica"]:$this->q12_fisica);
       $this->q12_calciss = ($this->q12_calciss == "f"?@$GLOBALS["HTTP_POST_VARS"]["q12_calciss"]:$this->q12_calciss);
       $this->q12_integrasani = ($this->q12_integrasani == "f"?@$GLOBALS["HTTP_POST_VARS"]["q12_integrasani"]:$this->q12_integrasani);
       $this->q12_alvaraautomatico = ($this->q12_alvaraautomatico == "f"?@$GLOBALS["HTTP_POST_VARS"]["q12_alvaraautomatico"]:$this->q12_alvaraautomatico);
     }else{
       $this->q12_classe = ($this->q12_classe == ""?@$GLOBALS["HTTP_POST_VARS"]["q12_classe"]:$this->q12_classe);
     }
   }
   // funcao para inclusao
   function incluir ($q12_classe){ 
      $this->atualizacampos();
     if($this->q12_descr == null ){ 
       $this->erro_sql = " Campo descricao da classe nao Informado.";
       $this->erro_campo = "q12_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q12_fisica == null ){ 
       $this->erro_sql = " Campo Pessoa nao Informado.";
       $this->erro_campo = "q12_fisica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q12_calciss == null ){ 
       $this->erro_sql = " Campo Calcula ISS nao Informado.";
       $this->erro_campo = "q12_calciss";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q12_integrasani == null ){ 
       $this->erro_sql = " Campo Integração automatica com sanitario nao Informado.";
       $this->erro_campo = "q12_integrasani";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q12_alvaraautomatico == null ){ 
       $this->erro_sql = " Campo Gera Automático nao Informado.";
       $this->erro_campo = "q12_alvaraautomatico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q12_classe == "" || $q12_classe == null ){
       $result = db_query("select nextval('classe_q12_classe_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: classe_q12_classe_seq do campo: q12_classe"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q12_classe = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from classe_q12_classe_seq");
       if(($result != false) && (pg_result($result,0,0) < $q12_classe)){
         $this->erro_sql = " Campo q12_classe maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q12_classe = $q12_classe; 
       }
     }
     if(($this->q12_classe == null) || ($this->q12_classe == "") ){ 
       $this->erro_sql = " Campo q12_classe nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into classe(
                                       q12_classe 
                                      ,q12_descr 
                                      ,q12_fisica 
                                      ,q12_calciss 
                                      ,q12_integrasani 
                                      ,q12_alvaraautomatico 
                       )
                values (
                                $this->q12_classe 
                               ,'$this->q12_descr' 
                               ,'$this->q12_fisica' 
                               ,'$this->q12_calciss' 
                               ,'$this->q12_integrasani' 
                               ,'$this->q12_alvaraautomatico' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->q12_classe) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->q12_classe) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q12_classe;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q12_classe));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,304,'$this->q12_classe','I')");
       $resac = db_query("insert into db_acount values($acount,57,304,'','".AddSlashes(pg_result($resaco,0,'q12_classe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,57,305,'','".AddSlashes(pg_result($resaco,0,'q12_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,57,6390,'','".AddSlashes(pg_result($resaco,0,'q12_fisica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,57,6510,'','".AddSlashes(pg_result($resaco,0,'q12_calciss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,57,9632,'','".AddSlashes(pg_result($resaco,0,'q12_integrasani'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,57,18324,'','".AddSlashes(pg_result($resaco,0,'q12_alvaraautomatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q12_classe=null) { 
      $this->atualizacampos();
     $sql = " update classe set ";
     $virgula = "";
     if(trim($this->q12_classe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q12_classe"])){ 
       $sql  .= $virgula." q12_classe = $this->q12_classe ";
       $virgula = ",";
       if(trim($this->q12_classe) == null ){ 
         $this->erro_sql = " Campo codigo da classe nao Informado.";
         $this->erro_campo = "q12_classe";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q12_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q12_descr"])){ 
       $sql  .= $virgula." q12_descr = '$this->q12_descr' ";
       $virgula = ",";
       if(trim($this->q12_descr) == null ){ 
         $this->erro_sql = " Campo descricao da classe nao Informado.";
         $this->erro_campo = "q12_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q12_fisica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q12_fisica"])){ 
       $sql  .= $virgula." q12_fisica = '$this->q12_fisica' ";
       $virgula = ",";
       if(trim($this->q12_fisica) == null ){ 
         $this->erro_sql = " Campo Pessoa nao Informado.";
         $this->erro_campo = "q12_fisica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q12_calciss)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q12_calciss"])){ 
       $sql  .= $virgula." q12_calciss = '$this->q12_calciss' ";
       $virgula = ",";
       if(trim($this->q12_calciss) == null ){ 
         $this->erro_sql = " Campo Calcula ISS nao Informado.";
         $this->erro_campo = "q12_calciss";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q12_integrasani)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q12_integrasani"])){ 
       $sql  .= $virgula." q12_integrasani = '$this->q12_integrasani' ";
       $virgula = ",";
       if(trim($this->q12_integrasani) == null ){ 
         $this->erro_sql = " Campo Integração automatica com sanitario nao Informado.";
         $this->erro_campo = "q12_integrasani";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q12_alvaraautomatico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q12_alvaraautomatico"])){ 
       $sql  .= $virgula." q12_alvaraautomatico = '$this->q12_alvaraautomatico' ";
       $virgula = ",";
       if(trim($this->q12_alvaraautomatico) == null ){ 
         $this->erro_sql = " Campo Gera Automático nao Informado.";
         $this->erro_campo = "q12_alvaraautomatico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q12_classe!=null){
       $sql .= " q12_classe = $this->q12_classe";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q12_classe));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,304,'$this->q12_classe','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q12_classe"]) || $this->q12_classe != "")
           $resac = db_query("insert into db_acount values($acount,57,304,'".AddSlashes(pg_result($resaco,$conresaco,'q12_classe'))."','$this->q12_classe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q12_descr"]) || $this->q12_descr != "")
           $resac = db_query("insert into db_acount values($acount,57,305,'".AddSlashes(pg_result($resaco,$conresaco,'q12_descr'))."','$this->q12_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q12_fisica"]) || $this->q12_fisica != "")
           $resac = db_query("insert into db_acount values($acount,57,6390,'".AddSlashes(pg_result($resaco,$conresaco,'q12_fisica'))."','$this->q12_fisica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q12_calciss"]) || $this->q12_calciss != "")
           $resac = db_query("insert into db_acount values($acount,57,6510,'".AddSlashes(pg_result($resaco,$conresaco,'q12_calciss'))."','$this->q12_calciss',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q12_integrasani"]) || $this->q12_integrasani != "")
           $resac = db_query("insert into db_acount values($acount,57,9632,'".AddSlashes(pg_result($resaco,$conresaco,'q12_integrasani'))."','$this->q12_integrasani',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q12_alvaraautomatico"]) || $this->q12_alvaraautomatico != "")
           $resac = db_query("insert into db_acount values($acount,57,18324,'".AddSlashes(pg_result($resaco,$conresaco,'q12_alvaraautomatico'))."','$this->q12_alvaraautomatico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q12_classe;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q12_classe;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q12_classe;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q12_classe=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q12_classe));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,304,'$q12_classe','E')");
         $resac = db_query("insert into db_acount values($acount,57,304,'','".AddSlashes(pg_result($resaco,$iresaco,'q12_classe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,57,305,'','".AddSlashes(pg_result($resaco,$iresaco,'q12_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,57,6390,'','".AddSlashes(pg_result($resaco,$iresaco,'q12_fisica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,57,6510,'','".AddSlashes(pg_result($resaco,$iresaco,'q12_calciss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,57,9632,'','".AddSlashes(pg_result($resaco,$iresaco,'q12_integrasani'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,57,18324,'','".AddSlashes(pg_result($resaco,$iresaco,'q12_alvaraautomatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from classe
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q12_classe != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q12_classe = $q12_classe ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q12_classe;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q12_classe;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q12_classe;
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
        $this->erro_sql   = "Record Vazio na Tabela:classe";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q12_classe=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from classe ";
     $sql2 = "";
     if($dbwhere==""){
       if($q12_classe!=null ){
         $sql2 .= " where classe.q12_classe = $q12_classe "; 
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
   function sql_query_file ( $q12_classe=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from classe ";
     $sql2 = "";
     if($dbwhere==""){
       if($q12_classe!=null ){
         $sql2 .= " where classe.q12_classe = $q12_classe "; 
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