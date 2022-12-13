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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_sysfuncoes
class cl_db_sysfuncoes { 
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
   var $codfuncao = 0; 
   var $nomefuncao = null; 
   var $nomearquivo = null; 
   var $obsfuncao = null; 
   var $corpofuncao = null; 
   var $triggerfuncao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 codfuncao = int4 = Código Função 
                 nomefuncao = varchar(100) = Nome 
                 nomearquivo = varchar(100) = Nome do arquivo 
                 obsfuncao = text = Observação 
                 corpofuncao = text = Corpo da Função 
                 triggerfuncao = char(1) = Tipo 
                 ";
   //funcao construtor da classe 
   function cl_db_sysfuncoes() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_sysfuncoes"); 
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
       $this->codfuncao = ($this->codfuncao == ""?@$GLOBALS["HTTP_POST_VARS"]["codfuncao"]:$this->codfuncao);
       $this->nomefuncao = ($this->nomefuncao == ""?@$GLOBALS["HTTP_POST_VARS"]["nomefuncao"]:$this->nomefuncao);
       $this->nomearquivo = ($this->nomearquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["nomearquivo"]:$this->nomearquivo);
       $this->obsfuncao = ($this->obsfuncao == ""?@$GLOBALS["HTTP_POST_VARS"]["obsfuncao"]:$this->obsfuncao);
       $this->corpofuncao = ($this->corpofuncao == ""?@$GLOBALS["HTTP_POST_VARS"]["corpofuncao"]:$this->corpofuncao);
       $this->triggerfuncao = ($this->triggerfuncao == ""?@$GLOBALS["HTTP_POST_VARS"]["triggerfuncao"]:$this->triggerfuncao);
     }else{
       $this->codfuncao = ($this->codfuncao == ""?@$GLOBALS["HTTP_POST_VARS"]["codfuncao"]:$this->codfuncao);
     }
   }
   // funcao para inclusao
   function incluir ($codfuncao){ 
      $this->atualizacampos();
     if($this->nomefuncao == null ){ 
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "nomefuncao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->nomearquivo == null ){ 
       $this->erro_sql = " Campo Nome do arquivo nao Informado.";
       $this->erro_campo = "nomearquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->obsfuncao == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "obsfuncao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->corpofuncao == null ){ 
       $this->erro_sql = " Campo Corpo da Função nao Informado.";
       $this->erro_campo = "corpofuncao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->triggerfuncao == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "triggerfuncao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($codfuncao == "" || $codfuncao == null ){
       $result = db_query("select nextval('db_sysfuncoes_codfuncao_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_sysfuncoes_codfuncao_seq do campo: codfuncao"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->codfuncao = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_sysfuncoes_codfuncao_seq");
       if(($result != false) && (pg_result($result,0,0) < $codfuncao)){
         $this->erro_sql = " Campo codfuncao maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->codfuncao = $codfuncao; 
       }
     }
     if(($this->codfuncao == null) || ($this->codfuncao == "") ){ 
       $this->erro_sql = " Campo codfuncao nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_sysfuncoes(
                                       codfuncao 
                                      ,nomefuncao 
                                      ,nomearquivo 
                                      ,obsfuncao 
                                      ,corpofuncao 
                                      ,triggerfuncao 
                       )
                values (
                                $this->codfuncao 
                               ,'$this->nomefuncao' 
                               ,'$this->nomearquivo' 
                               ,'$this->obsfuncao' 
                               ,'$this->corpofuncao' 
                               ,'$this->triggerfuncao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Funcoes do sistema postgresql ($this->codfuncao) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Funcoes do sistema postgresql já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Funcoes do sistema postgresql ($this->codfuncao) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codfuncao;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->codfuncao));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,774,'$this->codfuncao','I')");
       $resac = db_query("insert into db_acount values($acount,146,774,'','".AddSlashes(pg_result($resaco,0,'codfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,146,775,'','".AddSlashes(pg_result($resaco,0,'nomefuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,146,9466,'','".AddSlashes(pg_result($resaco,0,'nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,146,776,'','".AddSlashes(pg_result($resaco,0,'obsfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,146,777,'','".AddSlashes(pg_result($resaco,0,'corpofuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,146,778,'','".AddSlashes(pg_result($resaco,0,'triggerfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($codfuncao=null) { 
      $this->atualizacampos();
     $sql = " update db_sysfuncoes set ";
     $virgula = "";
     if(trim($this->codfuncao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codfuncao"])){ 
       $sql  .= $virgula." codfuncao = $this->codfuncao ";
       $virgula = ",";
       if(trim($this->codfuncao) == null ){ 
         $this->erro_sql = " Campo Código Função nao Informado.";
         $this->erro_campo = "codfuncao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->nomefuncao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["nomefuncao"])){ 
       $sql  .= $virgula." nomefuncao = '$this->nomefuncao' ";
       $virgula = ",";
       if(trim($this->nomefuncao) == null ){ 
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "nomefuncao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->nomearquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["nomearquivo"])){ 
       $sql  .= $virgula." nomearquivo = '$this->nomearquivo' ";
       $virgula = ",";
       if(trim($this->nomearquivo) == null ){ 
         $this->erro_sql = " Campo Nome do arquivo nao Informado.";
         $this->erro_campo = "nomearquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->obsfuncao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["obsfuncao"])){ 
       $sql  .= $virgula." obsfuncao = '$this->obsfuncao' ";
       $virgula = ",";
       if(trim($this->obsfuncao) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "obsfuncao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->corpofuncao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["corpofuncao"])){ 
       $sql  .= $virgula." corpofuncao = '$this->corpofuncao' ";
       $virgula = ",";
       if(trim($this->corpofuncao) == null ){ 
         $this->erro_sql = " Campo Corpo da Função nao Informado.";
         $this->erro_campo = "corpofuncao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->triggerfuncao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["triggerfuncao"])){ 
       $sql  .= $virgula." triggerfuncao = '$this->triggerfuncao' ";
       $virgula = ",";
       if(trim($this->triggerfuncao) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "triggerfuncao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($codfuncao!=null){
       $sql .= " codfuncao = $this->codfuncao";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->codfuncao));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,774,'$this->codfuncao','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codfuncao"]))
           $resac = db_query("insert into db_acount values($acount,146,774,'".AddSlashes(pg_result($resaco,$conresaco,'codfuncao'))."','$this->codfuncao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["nomefuncao"]))
           $resac = db_query("insert into db_acount values($acount,146,775,'".AddSlashes(pg_result($resaco,$conresaco,'nomefuncao'))."','$this->nomefuncao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["nomearquivo"]))
           $resac = db_query("insert into db_acount values($acount,146,9466,'".AddSlashes(pg_result($resaco,$conresaco,'nomearquivo'))."','$this->nomearquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["obsfuncao"]))
           $resac = db_query("insert into db_acount values($acount,146,776,'".AddSlashes(pg_result($resaco,$conresaco,'obsfuncao'))."','$this->obsfuncao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["corpofuncao"]))
           $resac = db_query("insert into db_acount values($acount,146,777,'".AddSlashes(pg_result($resaco,$conresaco,'corpofuncao'))."','$this->corpofuncao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["triggerfuncao"]))
           $resac = db_query("insert into db_acount values($acount,146,778,'".AddSlashes(pg_result($resaco,$conresaco,'triggerfuncao'))."','$this->triggerfuncao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Funcoes do sistema postgresql nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->codfuncao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Funcoes do sistema postgresql nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->codfuncao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codfuncao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($codfuncao=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($codfuncao));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,774,'$codfuncao','E')");
         $resac = db_query("insert into db_acount values($acount,146,774,'','".AddSlashes(pg_result($resaco,$iresaco,'codfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,146,775,'','".AddSlashes(pg_result($resaco,$iresaco,'nomefuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,146,9466,'','".AddSlashes(pg_result($resaco,$iresaco,'nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,146,776,'','".AddSlashes(pg_result($resaco,$iresaco,'obsfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,146,777,'','".AddSlashes(pg_result($resaco,$iresaco,'corpofuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,146,778,'','".AddSlashes(pg_result($resaco,$iresaco,'triggerfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_sysfuncoes
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($codfuncao != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " codfuncao = $codfuncao ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Funcoes do sistema postgresql nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$codfuncao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Funcoes do sistema postgresql nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$codfuncao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$codfuncao;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_sysfuncoes";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $codfuncao=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_sysfuncoes ";
     $sql .= " left join db_sysfuncoescliente on db41_funcao = codfuncao ";
     $sql2 = "";
     if($dbwhere==""){
       if($codfuncao!=null ){
         $sql2 .= " where db_sysfuncoes.codfuncao = $codfuncao "; 
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
   function sql_query_file ( $codfuncao=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_sysfuncoes ";
     $sql2 = "";
     if($dbwhere==""){
       if($codfuncao!=null ){
         $sql2 .= " where db_sysfuncoes.codfuncao = $codfuncao "; 
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